<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050305';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dept = $this->session->userdata('i_departement');
        $dept = trim($dept);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dept'          => $dept,
            'kodemaster'    => $this->mmaster->bacagudang()->row(),
            'kodegudang'    => $this->mmaster->gudang()->result(),
            'jeniskeluar'   => $this->mmaster->bacajeniskeluar(),
            //'tujuan'=> $this->mmaster->bacatujuan(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    // public function getppic(){
    //     $filter = [];
    //     if($this->input->get('q') != '') {
    //         $filter = [];
    //         $cari = strtoupper($this->input->get('q'));
    //         $this->db->select("*");
    //         $this->db->from("tm_karyawan");
    //         $this->db->like("UPPER(i_karyawan)", $cari);
    //         $this->db->or_like("UPPER(e_nama_karyawan)", $cari);
    //         $data = $this->db->get();
    //         foreach($data->result() as  $icolor){
    //                 $filter[] = array(
    //                 'id'   => $icolor->i_karyawan,  
    //                 'text' => $icolor->e_nama_karyawan,
    //             );
    //         }          
    //         echo json_encode($filter);
    //     } else {
    //         echo json_encode($filter);
    //     }
    // }

    public function getppic(){
        $ipartner        = $this->input->post('ipartner');
        $query = $this->mmaster->getppic($ipartner);
        if($query->num_rows()>0) {
            $c         = "";
            $ppic  = $query->result();
            foreach($ppic as $row) {
                $c.="<option value=".$row->i_ppic." >".$row->e_ppic."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih PPIC -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">PPIC Tidak Ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang =  $this->uri->segment(4);
            $data = $this->mmaster->product($cari,$gudang);
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id' => $material->i_material, 
                    'name' => $material->e_material_name, 
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerialname = $this->input->post('ematerialname');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan_code=b.i_satuan_code");
            $this->db->where("UPPER(i_material)", $ematerialname);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function getreffmemo(){
        header("Content-Type: application/json", true);
        $id = $this->input->post('id');
        $this->db->select("substr(i_jenis,6,2) as jeniskeluar");
        $this->db->from("tr_jenis_pengeluaran");
        $this->db->where("i_jenis", $id);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

     public function getpic(){
        $tujuankeluar = $this->input->post('tujuankeluar');
        $ikodemaster  = $this->uri->segment(4);
        if($tujuankeluar == 'internal'){
            $query = $this->mmaster->getpicIN($ikodemaster);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_sub_bagian." >".$row->e_sub_bagian."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Partner -- ".$c."</option>";
                echo json_encode(array(
                    'kop'   => $kop
                ));
            }else{
                $kop  = "<option value=\"\">Data Kosong</option>";
                echo json_encode(array(
                    'kop'    => $kop,
                    'kosong' => 'kopong'
                ));
            }
        }else {
            $kop  = "<option value=\"\"> -- Pilih Partner -- </option><option value=\"Customer\">Customer</option><option value=\"Supplier\">Supplier</option><option value=\"Karyawan\">Karyawan</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
            // $query = $this->mmaster->getpicEK();
            // if($query->num_rows()>0) {
            //     $c  = "";
            //     $spb = $query->result();
            //     foreach($spb as $row) {
            //         $c.="<option value=".$row->i_supplier." >".$row->e_supplier_name."</option>";
                    
            //     }
            //     $kop  = "<option value=\"\"> -- Pilih Partner -- ".$c."</option>";
            //     echo json_encode(array(
            //         'kop'   => $kop
            //     ));
            // }else{
            //     $kop  = "<option value=\"\">Data Kosong</option>";
            //     echo json_encode(array(
            //         'kop'    => $kop,
            //         'kosong' => 'kopong'
            //     ));
            // }
        }
        
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dbonk = $this->input->post("dbonk",true);
        if ($dbonk) {
            $tmp = explode('-', $dbonk);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dbonk = $year . '-' . $month . '-' . $day;
        }

        $dback = $this->input->post("dback",true);
        if ($dback) {
            $tmp = explode('-', $dback);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $dback = $year . '-' . $month . '-' . $day;
        }


        $jenispengeluaran = $this->input->post('jenispengeluaran', TRUE);
        $istore         = $this->input->post('istore', TRUE);

        $tujuankeluar   = $this->input->post('tujuankeluar', TRUE);
        $pic            = $this->input->post('ppic', TRUE);
        $epic           = $this->input->post('epic', TRUE);
        $dept           = $this->input->post('edept', TRUE);
        $remark         = $this->input->post('eremark', TRUE);
        $nobonkeluar    = $this->mmaster->runningnumbermemopengeluaran($yearmonth,$istore);
        $jml            = $this->input->post('jml', TRUE); 
        $cancel         = 'f';
        $ireffmemo      = $this->input->post('ireffmemo', TRUE);

        $i_material      = $this->input->post('imaterial[]', TRUE);    
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $i_material2      = $this->input->post('imaterial2[]', TRUE);    
        $n_quantity2      = $this->input->post('nquantity2[]', TRUE);
        $i_satuan2        = $this->input->post('isatuan2[]', TRUE); 
        $e_desc          = $this->input->post('edesc[]', TRUE);
                     
        $this->db->trans_begin();
        $this->mmaster->insertheader($dbonk, $dback, $istore, $tujuankeluar, $pic, $dept, $remark, $nobonkeluar, $epic, $jenispengeluaran, $ireffmemo);

            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial     = $imaterial;
                $nquantity     = $n_quantity[$no];
                $isatuan       = $i_satuan[$no];
                $edesc         = $e_desc[$no];

                if ($jenispengeluaran == "JK00002") {
                    $imaterial2     = $i_material2[$no];
                    $nquantity2     = $n_quantity2[$no];
                    $isatuan2       = $i_satuan2[$no];
                } else {
                    $imaterial2     = $imaterial;
                    $nquantity2     = $nquantity;
                    $isatuan2       = $isatuan;
                }
               
                
                $this->mmaster->insertdetail($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $no, $imaterial2, $nquantity2,$isatuan2);
                //var_dump($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $no, $imaterial2, $nquantity2,$isatuan2);
                 $no++;
            }
            // for($i=1;$i<=$jml;$i++){
            //     $imaterial = $this->input->post('imaterial'.$i, TRUE);
            //     if ($imaterial != '' or $imaterial != null) {
            //         $nquantity      = $this->input->post('nquantity'.$i, TRUE);
            //         $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
            //         $edesc          = $this->input->post('edesc'.$i, TRUE);
            //        
            //         $urutan++;
            //     }
                
            // }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nobonkeluar);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nobonkeluar
                );
        }
        $this->load->view('pesan', $data);      
    
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $gudang = $this->input->post('gudang');
        $this->mmaster->send($kode,$gudang);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_permintaan   = $this->uri->segment('4');
        $gudang         = $this->uri->segment('5');
        $tujuankeluar   = $this->uri->segment('6');
        $ipartner             = $this->uri->segment('7');

        $head             = $this->mmaster->baca_header($i_permintaan, $gudang, $tujuankeluar)->row();
        $partner          = $this->mmaster->partner($tujuankeluar)->result();
        $karyawan         = $this->mmaster->karyawan()->result();
        $jenispengeluaran = $this->mmaster->bacajeniskeluar();
        $ppic             = $this->mmaster->getppic($ipartner);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'partner'       => $partner,
            'karyawan'      => $karyawan,
            'jeniskeluar'   => $jenispengeluaran,
            'head'          => $head,
            'ppic'          => $ppic,
        );
        //var_dump($partner, $head);
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dbonk = $this->input->post("dbonk",true);
        if ($dbonk) {
            $tmp = explode('-', $dbonk);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dbonk = $year . '-' . $month . '-' . $day;
        }

        $dback = $this->input->post("dback",true);
        if ($dback) {
            $tmp = explode('-', $dback);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $dback = $year . '-' . $month . '-' . $day;
        }


        $jenispengeluaran = $this->input->post('jenispengeluaran', TRUE);
        $istore         = $this->input->post('istore', TRUE);

        $tujuankeluar   = $this->input->post('tujuankeluar', TRUE);
        $pic            = $this->input->post('ppic', TRUE);
        $epic           = $this->input->post('epic', TRUE);
        $dept           = $this->input->post('edept', TRUE);
        $remark         = $this->input->post('eremark', TRUE);
        $nobonkeluar    = $this->input->post('i_bonmk', TRUE);
        $jml            = $this->input->post('jml', TRUE); 
        $cancel         = 'f';

        $i_material      = $this->input->post('imaterial[]', TRUE);    
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $i_material2     = $this->input->post('imaterial2[]', TRUE);    
        $n_quantity2     = $this->input->post('nquantity2[]', TRUE);
        $i_satuan2       = $this->input->post('isatuan2[]', TRUE); 
        $e_desc          = $this->input->post('edesc[]', TRUE);
       
        //var_dump($dbonk, $istore, $tujuankeluar, $pic, $dept, $remark, $nobonkeluar);
        $this->db->trans_begin();
        $this->mmaster->updateheader($nobonkeluar, $istore, $dbonk, $jenispengeluaran, $tujuankeluar, $pic, $dept, $remark, $epic, $dback);
        $this->mmaster->deletedetail($nobonkeluar, $istore);

            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial     = $imaterial;
                $nquantity     = $n_quantity[$no];
                $isatuan       = $i_satuan[$no];
                $edesc         = $e_desc[$no];

                if ($jenispengeluaran == "JK00002") {
                    $imaterial2     = $i_material2[$no];
                    $nquantity2     = $n_quantity2[$no];
                    $isatuan2       = $i_satuan2[$no];
                } else {
                    $imaterial2     = $imaterial;
                    $nquantity2     = $nquantity;
                    $isatuan2       = $isatuan;
                }
                //var_dump($imaterial, $imaterial2);
                $this->mmaster->insertdetail($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $no, $imaterial2, $nquantity2,$isatuan2); 
                //var_dump($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $no, $imaterial2, $nquantity2,$isatuan2)  ;
                  $no++;
            }   
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$nobonkeluar);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nobonkeluar,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $bonmkp   = $this->input->post('bonmk');
        $gudang  = $this->input->post('gudang');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($bonmkp, $gudang);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Bon Keluar Pinjaman Bahan Baku '.$bonmkp.' Gudang:'.$gudang);
            echo json_encode($data);
        }
    }


    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_permintaan   = $this->uri->segment('4');
        $gudang         = $this->uri->segment('5');
        $tujuankeluar   = $this->uri->segment('6');

        $head               = $this->mmaster->baca_header($i_permintaan, $gudang, $tujuankeluar)->row();
        $partner            = $this->mmaster->partner($tujuankeluar)->result();
        $karyawan           = $this->mmaster->karyawan()->result();
        $jenispengeluaran   = $this->mmaster->bacajeniskeluar();
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'partner'       => $partner,
            'karyawan'      => $karyawan,
            'jeniskeluar'   => $jenispengeluaran,
            'head'          => $head,
            //'detail'        => $this->mmaster->baca_detail($i_permintaan, $gudang)->result(),
        );
        //var_dump($partner, $head);
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_permintaan       = $this->uri->segment('4');
        $gudang       = $this->uri->segment('5');
        $tujuankeluar = $this->uri->segment('6');

        $head = $this->mmaster->baca_header($i_permintaan, $gudang, $tujuankeluar)->row();
        $partner = $this->mmaster->partner($tujuankeluar)->result();
        $karyawan = $this->mmaster->karyawan()->result();
        $jenispengeluaran = $this->mmaster->bacajeniskeluar();
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'partner'    => $partner,
            'karyawan'    => $karyawan,
            'jeniskeluar' => $jenispengeluaran,
            'head'          => $head,
            //'detail'        => $this->mmaster->baca_detail($i_permintaan, $gudang)->result(),
        );
        //var_dump($partner, $head);
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $kode   = $this->input->post('i_bonmk');
        $gudang   = $this->input->post('istore');
        $this->db->trans_begin();
        $this->mmaster->approve($kode, $gudang);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $kode,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function getdetailpermintaan(){
        header("Content-Type: application/json", true);
        $ipermintaan  = $this->input->post('ipermintaan', FALSE);
        $gudang  = $this->input->post('gudang', FALSE);
        //var_dump($isjkm, $isjmm, $gudang);
        $query  = array(
            'detail' => $this->mmaster->getpermintaan_detail($ipermintaan, $gudang)->result_array()
        );
        echo json_encode($query);  
    }

    public function change(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $gudang = $this->input->post('gudang');
        $this->mmaster->change($kode,$gudang);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $gudang = $this->input->post('gudang');
        $this->mmaster->reject($kode,$gudang);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $gudang = $this->input->post('gudang');
        $this->mmaster->tarikdokumen($kode,$gudang);
    }

}
/* End of file Cform.php */
