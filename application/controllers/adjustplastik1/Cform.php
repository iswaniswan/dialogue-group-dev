<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20513082051308';

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
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
		echo $this->mmaster->data($this->i_menu, $username, $idcompany, $idepartemen, $ilevel);
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
            'jenisbarang'   => $this->mmaster->jenisbarang(),
            'kodemaster'    => $this->mmaster->bacagudang(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function datajenisbarang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->jenisbarang($cari);
            foreach($data->result() as  $jenis){       
                    $filter[] = array(
                    'id' => $jenis->i_type_code,  
                    'text' => $jenis->e_type_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    } 

    function getjenisbarang(){
        header("Content-Type: application/json", true);
        $ejenisbarang = $this->input->post('ejenisbarang');
        $this->db->select("i_type_code, e_type_name");
            $this->db->from("tr_item_type");
            $this->db->where("i_type_code", $ejenisbarang);            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function datamaterial(){
        $filter = [];
        $istore             = $this->uri->segment(4);
        $ijenisbarang       = $this->uri->segment(5);
        //var_dump($istore, $ijenisbarang);
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $where = '';
              if($ijenisbarang != 'JBR'){
                $where .= "AND a.i_type_code = '$ijenisbarang'";
              }

            $data = $this->db->query("select a.*,b.e_satuan 
                from tr_material a
                INNER JOIN tr_satuan b on (a.i_satuan_code=b.i_satuan_code)
                INNER JOIN tm_kelompok_barang d on (a.i_kode_kelompok = d.i_kode_kelompok)
                where a.i_satuan_code=b.i_satuan_code  
                and d.i_kode_master='$istore' $where 
                and (a.i_material like '%$cari%' or a.e_material_name like '%$cari%') 
                order by a.i_material");
            foreach ($data->result() as $material) {
                $filter[] = array(
                    'id'   => $material->i_material,
                    'name' => $material->e_material_name,
                    'text' => $material->i_material.' - '.$material->e_material_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }  

    function getmaterial(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('i_material');
        $this->db->select("a.i_material, e_material_name, a.i_satuan_code , b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan_code=b.i_satuan_code");
            $this->db->where("UPPER(i_material)", $imaterial);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $istore  = $this->input->post("istore",true);
        $dadjus  = $this->input->post("dadjus",true);
        if ($dadjus) {
            $tmp = explode('-', $dadjus);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dateadjus = $year . '-' . $month . '-' . $day;
        }

        $remark   = $this->input->post('eremark', TRUE);
        $noadjus  = $this->mmaster->runningnumberadjustment($yearmonth, $istore);
        $jml      = $this->input->post('jml', TRUE);

        // var_dump($dadjus,$noadjus, $jml);
        $this->db->trans_begin();
        $this->mmaster->insertheader($noadjus, $dateadjus, $remark, $istore);
            $urutan = 1;
            for($i=1;$i<=$jml;$i++){
                $imaterial = $this->input->post('imaterial'.$i, TRUE);
                if ($imaterial != '' or $imaterial != null) {
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $this->mmaster->insertdetail($noadjus, $imaterial, $nquantity, $isatuan, $edesc, $urutan);
                    $urutan++;
                }
                
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$noadjus);
                $data = array(
                    'sukses' => true,
                    'kode'      => $noadjus,
                );
        }
        $this->load->view('pesan', $data);      
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->change($kode);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->reject($kode);
    }


    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iadjustment    = $this->uri->segment('4');
        $i_status       = $this->uri->segment('5');
        $i_departement  = $this->uri->segment('6');
        $i_level        = $this->uri->segment('7');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'jenisbarang'   => $this->mmaster->jenisbarang(),
            'head'          => $this->mmaster->baca_header($iadjustment)->row(),
            'detail'        => $this->mmaster->baca_detail($iadjustment)->result(),
            'i_status'      => $i_status,
            'i_departement' => $i_departement,
            'i_level'       => $i_level
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dadjus = $this->input->post("dadjus",true);
        if ($dadjus) {
            $tmp = explode('-', $dadjus);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dateadjus = $year . '-' . $month . '-' . $day;
        }

        $remark   = $this->input->post('eremark', TRUE);
        $istore   = $this->input->post('istore', TRUE);
        $noadjus  = $this->input->post('i_adjus', TRUE);
        $jml      = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->mmaster->updateheader($noadjus, $dateadjus, $remark, $istore);
        $this->mmaster->deletedetail($noadjus);
            $urutan = 1;
            for($i=1;$i<=$jml;$i++){
                $imaterial = $this->input->post('imaterial'.$i, TRUE);
                if ($imaterial != '' or $imaterial != null) {
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $this->mmaster->insertdetail($noadjus, $imaterial, $nquantity, $isatuan, $edesc, $urutan);     
                    $urutan++;
                }
                
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$noadjus);
                $data = array(
                    'sukses' => true,
                    'kode'      => $noadjus,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_adjus = $this->uri->segment('4');
        $i_status = $this->uri->segment('5');
        $i_departement = $this->uri->segment('6');
        $i_level = $this->uri->segment('7');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            //'jenisbarang'    => $this->mmaster->jenisbarang(),
            'head' => $this->mmaster->baca_header($i_adjus)->row(),
            'detail' => $this->mmaster->baca_detail($i_adjus)->result(),
            'i_status' => $i_status,
            'i_departement' => $i_departement,
            'i_level' => $i_level
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iadjust   = $this->input->post('iadjust');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iadjust);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Adjusment Bahan Baku '.$iadjust);
            echo json_encode($data);
        }
    }

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $i_adjus   = $this->input->post('i_adjus');
        $this->db->trans_begin();
        $this->mmaster->approve($i_adjus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $i_adjus,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function view(){

        $iadjustment    = $this->uri->segment('4');
        $i_status       = $this->uri->segment('5');
        $i_departement  = $this->uri->segment('6');
        $i_level        = $this->uri->segment('7');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'jenisbarang'   => $this->mmaster->jenisbarang(),
            'head'          => $this->mmaster->baca_header($iadjustment)->row(),
            'detail'        => $this->mmaster->baca_detail($iadjustment)->result(),
            'i_status'      => $i_status,
            'i_departement' => $i_departement,
            'i_level'       => $i_level
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

}
/* End of file Cform.php */