<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050208';

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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang()->result(),
            'jnskeluar'     => $this->mmaster->bacajenis(),
            'tujuan'        => $this->mmaster->bacatujuan(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function tujuan(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_tujuan, a.e_tujuan");
            $this->db->from("tr_jenis_kirimbb a");
            //$this->db->or_like("UPPER(a.i_type_makloon)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $itype) {
                $filter[] = array(
                    'id' => $itype->i_tujuan,
                    'text' => $itype->e_tujuan,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function tujuankirim(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_unit_jahit, a.e_unitjahit_name");
            $this->db->from("tr_unit_jahit a");
            //$this->db->or_like("UPPER(a.i_type_makloon)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $itype) {
                $filter[] = array(
                    'id' => $itype->i_unit_jahit,
                    'text' => $itype->e_unitjahit_name,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function gettujuankirim(){
        $itujuan = $this->input->post('itujuan');
        if($itujuan == "UJ"){
        $query = $this->mmaster->gettujuanUJ();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->itujuank." >".$row->etujuank."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Tujuan Kirim -- ".$c."</option>";
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
        }else if ($itujuan == "UP"){
            $query = $this->mmaster->gettujuanUP();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->itujuank." >".$row->etujuank."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Tujuan Kirim -- ".$c."</option>";
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
        }else if(($itujuan == "CT")){
            $query = $this->mmaster->gettujuanCT();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->itujuank." >".$row->etujuank."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Tujuan Kirim -- ".$c."</option>";
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
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
                echo json_encode(array(
                    'kop'    => $kop,
                    'kosong' => 'kopong'
                ));
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

    function material(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_material, a.e_material_name ,b.i_satuan, b.e_satuan");
            $this->db->from("from tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->or_like("UPPER(i_color)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->nama,
        
                );
            }          
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dbonm = $this->input->post("dbonk",true);
        $tmp = explode('-', $this->input->post('dbonk'));
		$hr = $tmp[0];
		$bl = $tmp[1];
		$th = substr($tmp[2],2,2);
        $dbonk = $tmp[2].'-'.$bl.'-'.$hr;
        $ikodemaster 	    = $this->input->post('ikodemaster', TRUE);
        $jnskeluar 			= $this->input->post('jnskeluar', TRUE);
        $itujuan 			= $this->input->post('itujuan', TRUE);
        $itujuankirim       = $this->input->post('itujuankirim', TRUE);
        $remark             = $this->input->post('eremark', TRUE);
        $nobonk             = $this->mmaster->runningnumberbonk($th,$bl,$ikodemaster);
        $jml                = $this->input->post('jml', TRUE); 
        $bonkcancel         = 'f';

        $i_material      = $this->input->post('imaterial[]', TRUE);
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $n_quantitykonv  = $this->input->post('nquantitykonv[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $e_satuankonv    = $this->input->post('esatuankonv[]', TRUE);
        //$f_konv          = $this->input->post('fkonv[]', TRUE);
        $f_bisbisan      = $this->input->post('fbisbisan[]', TRUE);
        $e_desc          = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nobonk);
        $this->mmaster->insertheader($dbonk, $ikodemaster, $itujuan, $jnskeluar, $remark, $nobonk, $bonkcancel, $itujuankirim);

            $no=0;
            foreach ($i_material as $imaterial) {
                $imaterial      = $imaterial;
                $nquantity      = $n_quantity[$no];
                $nquantitykonv  = $n_quantitykonv[$no];
                $isatuan        = $i_satuan[$no];
                $esatuankonv    = $e_satuankonv[$no];
                $fbisbisan       = $f_bisbisan[$no];
                //if(isset($fbisbisan)=='on'){
                // if($fbisbisan=='on'){
                //     $fbisbisan = 't';
                // }else{
                //     $fbisbisan = 'f';
                // }

                $edesc          = $e_desc[$no];

                $this->mmaster->insertdetail($nobonk, $imaterial, $nquantity, $nquantitykonv, $isatuan, $esatuankonv, $edesc, $fbisbisan, $ikodemaster, $no);

                $no++;
            }
            // for($i=1;$i<=$jml;$i++){
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
               // $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nobonk);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nobonk,
                );
        }
    $this->load->view('pesan', $data);      
    }

    function datamaterial(){
        $filter = [];
        $ikodemaster = $this->uri->segment(4);
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*,b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan_code=b.i_satuan_code");
            $this->db->join("tm_kelompok_barang c", "a.i_kode_kelompok=c.i_kode_kelompok");
            $this->db->where("c.i_kode_master",$ikodemaster);
            $this->db->where("a.i_material like '%$cari%' or a.e_material_name like '%$cari%'");
            // $this->db->order_by('a.i_material', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id'   => $material->i_material,
                    'name' => $material->e_material_name, 
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_bonk      = $this->uri->segment('4');
        $tujuankirim = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($i_bonk, $tujuankirim)->row(),
            'data2'         => $this->mmaster->cek_datadet($i_bonk)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dbonk                  = $this->input->post("dbonk",true);
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $remark                 = $this->input->post('eremark', TRUE);
        $nobonk                 = $this->input->post('nobonk', TRUE);
        $jml                    = $this->input->post('jml', TRUE); 
        $bonmcancel             = 'f';
        
        $i_material      = $this->input->post('imaterial[]', TRUE);
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $n_quantitykonv  = $this->input->post('nquantitykonv[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $e_satuankonv    = $this->input->post('esatuankonv[]', TRUE);
        //$f_konv          = $this->input->post('fkonv[]', TRUE);
        $f_bisbisan      = $this->input->post('fbisbisan[]', TRUE);
        $e_desc          = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin();
        $this->mmaster->updateheader($nobonk, $dbonk, $remark);
        $this->mmaster->deletedetail($nobonk);

        if ($nobonk != '' && $ikodemaster != ''){
            $cekada = $this->mmaster->cek_dataheader($nobonk);
            if($cekada->num_rows() > 0){
            //for($i=1;$i<=$jml;$i++){
            $no=0;
            foreach ($i_material as $imaterial){
                $imaterial      = $imaterial;
                $nquantity      = $n_quantity[$no];
                $nquantitykonv  = $n_quantitykonv[$no];
                $isatuan        = $i_satuan[$no];
                $esatuankonv    = $e_satuankonv[$no];
                $fbisbisan       = $f_bisbisan[$no];
                //if(isset($fbisbisan)=='on'){
                // if($fbisbisan=='on'){
                //     $fbisbisan = 't';
                // }else{
                //     $fbisbisan = 'f';
                // }

                $edesc          = $e_desc[$no];
                
                $cekdet = $this->mmaster->cekdatadetail($nobonk, $imaterial);
                if($cekdet->num_rows() > 0){
                    $this->mmaster->updatedetail($nquantity,$nquantitykonv,$nobonk, $imaterial);
                }else{
                    $this->mmaster->insertdetail($nobonk, $imaterial, $nquantity, $nquantitykonv, $isatuan, $esatuankonv, $edesc, $fbisbisan, $ikodemaster, $no);
                }
                $no++;
                
            }
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$nobonk);
        }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $nobonk,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
}


    public function view(){

        $ipp = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($ipp)->row(),
            'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */
