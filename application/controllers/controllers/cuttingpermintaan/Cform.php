<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090102';
   
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
        $dfrom = $this->input->post('dfrom');
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom  = date('01-m-Y');
            }
        }
        $dto = $this->input->post('dto');
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }

        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }


    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom = $this->input->post('dfrom');
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto');
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        $thbl = date('Ym');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'], 
            'bagian'            => $this->mmaster->bagian()->result(),
            'number'            => "SPBB-".date('ym')."-000001",
            'dfrom'             => $dfrom,
            'dto'               => $dto,
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function number() {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function cekkode() {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode',TRUE),$this->input->post('kodeold',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function getreferensi(){
        //$cari = $this->input->post('cari');
        $query = $this->mmaster->getreferensi();
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->id." >".$row->i_document." | ". $row->d_document."</option>";
            }
            $kop  = "<option value=\"\">Pilih No Schedule".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Ada Data</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }

    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bagianedit()->result(),
            'data'          => $this->mmaster->cek_data($id)->row(),
            'dfrom'         => $dfrom,
            'dto'           => $dto,       
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function changestatus()
    {
        // $data = check_role($this->i_menu, 3);
        // if (!$data) {
        //     redirect(base_url(), 'refresh');
        // }

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $data = $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
             echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            //echo json_encode($data);
             echo json_encode (true);
        }
    }

    public function hapus()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $data = $this->mmaster->hapus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode($data);
        }
    }

    function getschedule(){
        header("Content-Type: application/json", true);
        $id_schedule = $this->input->post('id_schedule');
        $id_company     = $this->session->userdata('id_company');
        $this->db->select("to_char(d_document,'dd-mm-yyyy') AS d_schedule");
        $this->db->from("tm_schedule");
        $this->db->where("id", $id_schedule);
        $data = $this->db->get();

        $query2   = $this->db->query(" SELECT * FROM tm_schedule_item WHERE id_schedule = '$id_schedule' AND n_quantity_sisa > 0 ");
        
        //$jmlitem = $query2->num_rows();

        $dataa = array(
            'data'      => $data->result_array(),
            'jmlitem'   => $query2->num_rows(),
            'brgop'     => $this->mmaster->bacadetail($id_schedule, $id_company)->result_array()
        );
        echo json_encode($dataa);
    }

    function getscheduleedit(){
        header("Content-Type: application/json", true);
        $id_spbb = $this->input->post('id_spbb');
        $id_company     = $this->session->userdata('id_company');
        $dataa = array(
            'brgop'     => $this->mmaster->bacadetailedit($id_spbb, $id_company)->result_array()
        );
        echo json_encode($dataa);
    }

     public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibagian  = $this->input->post('ibagian', TRUE);
        $dspbb  = $this->input->post('dspbb', TRUE);
        $ispbb  = $this->input->post('ispbb', TRUE);

        if($dspbb){
             $tmp   = explode('-', $dspbb);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $thbl = $year.$month;
             $datespbb = $year.'-'.$month.'-'.$day;
        }

        $ischedule    = $this->input->post('ischedule', TRUE);
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml          = $this->input->post('jml', TRUE);  

        if ($ispbb!='' && $dspbb != '' && $ischedule!='') {

            $gudang = array();
            for($q=0;$q<$jml;$q++){ 
                $i_type = $this->input->post('i_type'.$q, TRUE);
                array_push($gudang, $i_type); 
            }
            
            $gudang = array_unique($gudang);
            $this->db->trans_begin();
            //print_r($gudang);
            $id = $this->mmaster->runningid();
            $dokumen = array();
            $iddokumen = array();
            $x= 1;
            foreach ($gudang as $row) {
                $gudang = $row;
                if ($x != 1) {
                    $ispbb = $this->mmaster->nextnumber($ispbb, $ibagian);
                    $id = $this->mmaster->runningid();
                }
                $res = TRUE;
                $this->mmaster->insertheader($id, $ibagian, $ispbb, $datespbb, $ischedule, $eremarkh, $gudang); 
                $sumsisa = 0;
                for($q=0;$q<$jml;$q++){ 
                    $i_type = $this->input->post('i_type'.$q, TRUE);
                    if ($i_type == $gudang) {
                         // id_spbb, id_product, id_material, n_quantity, n_quantity_sisa, n_set, n_gelar, n_jumlah_gelar, n_panjang_kain, n_panjang_kain_sisa, f_bisbisan
                        $id_spbb         = $id;
                        $id_product      = $this->input->post('id_product'.$q, TRUE);
                        $id_material     = $this->input->post('id_material'.$q, TRUE);
                        $sisa            = $this->input->post('sisa'.$q, TRUE);
                        $v_gelar         = $this->input->post('v_gelar'.$q, TRUE);
                        $v_set           = $this->input->post('v_set'.$q, TRUE);
                        $total_gelar     = $this->input->post('total_gelar'.$q, TRUE);
                        $panjang_kain    = $this->input->post('panjang_kain'.$q, TRUE);
                        $fbisbisan       = $this->input->post('fbisbisan'.$q, TRUE);
                        
                        $sumsisa = $sumsisa + $sisa;
                        if ($sisa <= 0) {
                            
                        } else {
                            $this->mmaster->insertdetail($id_spbb, $id_product, $id_material, $sisa, $v_gelar, $v_set, $total_gelar, $panjang_kain, $fbisbisan); 
                        }                  
                        //var_dump($id_spbb, $id_product, $id_material, $sisa, $v_gelar, $v_set, $total_gelar, $panjang_kain, $fbisbisan, $i_type);
                    }
                }

                if ($sumsisa <= 0) {
                    $this->mmaster->deleteheader($id);
                    $res = FALSE;
                }
                if ($res == TRUE) {
                    array_push($dokumen, $ispbb);
                    array_push($iddokumen, $id_spbb); 
                }
                $ispbb = $ispbb;
                $x++;
            }
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                    );
            }else{
                $this->db->trans_commit();
                
                $data = array(
                    'sukses' => true,
                    'kode'   => implode(", ",$dokumen),
                    'id'     => implode("|",$iddokumen),
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '. implode(", ",$dokumen));
            }
        }else{
            $data = array(
                'sukses'    => false,
            );
        }
        $this->load->view('pesan2', $data); 
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->cek_data($id)->row(),
            'dfrom'         => $dfrom,
            'dto'           => $dto,       
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function approve(){

        // $data = check_role($this->i_menu, 3);
        // if(!$data){
        //     redirect(base_url(),'refresh');
        // }
        $id_spbb        = $this->input->post('id', TRUE);
        $ispbb          = $this->input->post('ispbb', TRUE);
        $id_schedule    = $this->input->post('id_schedule', TRUE);
        $d_entry        = $this->input->post('d_entry', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        
        $this->db->trans_begin();
        
        // $this->mmaster->updateheader($ispbb,$dspbb,$igudang,$eremarkh);
        // $this->mmaster->updateheadersch($ischedule,$ispbb,$dspbb,$igudang);    
        $ada = array();
                 
        for($q=0;$q<$jml;$q++){ 
                 // id_spbb, id_product, id_material, n_quantity, n_quantity_sisa, n_set, n_gelar, n_jumlah_gelar, n_panjang_kain, n_panjang_kain_sisa, f_bisbisan
                $i_product       = $this->input->post('i_product'.$q, TRUE);
                $i_color         = $this->input->post('i_color'.$q, TRUE);
                $nquantity       = $this->input->post('nquantity'.$q, TRUE);
                $nquantitysisa   = $this->input->post('nquantitysisa'.$q, TRUE);
        
                //$this->mmaster->insertdetail($id_product, $nquantity, $nquantitysisa); 
                if ($i_product != null) {
                    $x = $this->mmaster->update_schedule($id_spbb,$id_schedule, $d_entry, $i_product, $i_color, $nquantity, $nquantitysisa);
                    if ($x == 1) {
                        array_push($ada, $i_product." Kode Warna ". $i_color);   
                    }
                }                            
        }     
        $this->mmaster->changestatus($id_spbb, '6');
       // die();
        if ($this->db->trans_status() === FALSE && count($ada) > 0){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    'kode'    => implode(", ",$ada),
                    
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Approve Data '.$this->global['title'].' Kode : '.$id_spbb);
            $data = array(
                'sukses' => true,
                'kode'      => $ispbb,
                'id'      => $id_spbb,
            );
        }
        $this->load->view('pesan2', $data); 
        
    }

    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bagianedit()->result(),
            'data'          => $this->mmaster->cek_data($id)->row(),
            'dfrom'         => $dfrom,
            'dto'           => $dto,       
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */