<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090402';

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

    public function data(){
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
		echo $this->mmaster->data($username,$idcompany,$idepartemen,$ilevel,$this->i_menu,$this->global['folder']);
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
            'departement'   => $this->mmaster->bacadepartement()
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getmakloonjahit(){
        $id = $this->input->post('id');
        $query = $this->mmaster->getmakloonjahit($id);
        if($query->num_rows()>0) {
            $c  = "";
            $makloon = $query->result();
            foreach($makloon as $row) {
                $c.="<option value=".$row->i_supplier." >".$row->i_supplier." - ".$row->e_supplier_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Supplier -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Supplier Tidak Ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getdiskonsupplier(){
        header("Content-Type: application/json", true);
        $isupplier = $this->input->post('id');
        $query  = array(
            'isi' => $this->mmaster->getdiskonsupplier($isupplier)->row()
        );
        echo json_encode($query); 
    }

    public function getproductwip(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getproductwip($cari);
            foreach($data->result() as  $wip){       
                    $filter[] = array(
                    'id'    => $wip->i_product."|".$wip->i_color,  
                    'name'  => $wip->e_namabrg,  
                    'text'  => $wip->i_product.' - '.$wip->e_namabrg.' - '.$wip->e_color_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function getwip(){
        header("Content-Type: application/json", true);
        $iwip   = $this->input->post('iproductwip');
        $icolor = $this->input->post('icolor');
        $dsjk   = $this->input->post('dsjk');

        $query  = array(
            'isi' => $this->mmaster->getwiphead($iwip,$icolor, $dsjk)->row(),
            'detail' => $this->mmaster->getwipdetail($iwip,$icolor)->result_array()
        );
        echo json_encode($query); 
    }


    public function getmaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari       = strtoupper($this->input->get('q'));
            $iproduct   = $this->uri->segment(4);
            $data       = $this->mmaster->getmaterial($cari,$iproduct);
            foreach($data->result() as  $wip){       
                    $filter[] = array(
                    'id'    => $wip->i_material,  
                    'name'  => $wip->e_material_name,  
                    'text'  => $wip->i_material.' - '.$wip->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function getdetailproductmaterial(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('imaterial');
        $data  = $this->mmaster->getdetailproductmaterial($imaterial);
        echo json_encode($data->result_array());  
    }

    public function getdetailsj(){
        header("Content-Type: application/json", true);
        $isj     = $this->input->post('isj', FALSE);
        $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'detail' => $this->mmaster->bacadetail($isj, $gudang)->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
      
        $dsjk   = $this->input->post("dsjk",true);
        if($dsjk != ''){
            $tmp = explode('-', $dsjk);
            $hr = $tmp[0];
            $bl = $tmp[1];
            $th = substr($tmp[2],2,2);
            $dsjk   = $tmp[2].'-'.$bl.'-'.$hr;
        }
        $dback    = $this->input->post('dback', TRUE);
        if($dback != ''){
            $tmp   = explode('-', $dback);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dback = $year.'-'.$month.'-'.$day;
        }
        $dforecast    = $this->input->post('dforecast', TRUE);
        if($dforecast != ''){
            $tmp   = explode('-', $dforecast);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dforecast = $year.'-'.$month.'-'.$day;
        }
      
        $isubbagian      = $this->input->post('idepartement', TRUE);
        $iforecast       = $this->input->post('iforecast', TRUE);
        $iunitjahit      = $this->input->post('iunitjahit', TRUE);
        $pkp             = $this->input->post('pkp', TRUE);
        $ndiscount       = $this->input->post('ndiscount', TRUE);
        $edesc           = $this->input->post('edesc', TRUE);
        
        $jml             = $this->input->post('jml', TRUE); 
        $fsjcancel       = 'f';
        //ITEM
        $i_productwip    = $this->input->post('iproductwip[]',TRUE);
        $v_price         = $this->input->post('vprice[]', TRUE);
        
        $i_color         = $this->input->post('icolor[]',TRUE);
        $i_material      = $this->input->post('imaterial[]',TRUE);
        $e_materialname  = $this->input->post('ematerialname[]',TRUE);
        $n_quantitywip   = $this->input->post('nquantitywip[]',TRUE);
        $n_quantity      = $this->input->post('nquantity[]',TRUE);
        $e_remark        = $this->input->post('eremark[]',TRUE);
        $this->db->trans_begin();
        $isj  = $this->mmaster->runningnumbersj($yearmonth,$isubbagian);
        $this->mmaster->insertheader($isj, $dsjk, $iforecast, $dforecast, $iunitjahit, $pkp, $ndiscount, $isubbagian, $dback, $edesc);
        $no=0;
        $lastwip='';
        $lastcolor='';
        $nquantitywip=0;
        foreach ($i_productwip as $iwip) {     
            $iwip        = $iwip;
            $vprice     = $v_price[$no];
            $icolor      = $i_color[$no];
            $imaterial   = $i_material[$no];
            $nquantity   = $n_quantity[$no];
            $eremark     = $e_remark[$no];
            
            if ($lastwip == $iwip && $lastcolor == $icolor) {
                $nquantitywip   = $lastqtybarang;
            } else {
                $nquantitywip   = $n_quantitywip[$no];
                //var_dump($nquantitywip);
            }
           
            $this->mmaster->insertdetail($isj, $iwip, $vprice, $imaterial, $icolor,$eremark, $nquantity, $nquantitywip, $no); 
            $lastqtybarang   = $nquantitywip;
            $lastwip = $iwip;
            $lastcolor = $icolor;
            $no++;
            //var_dump($no);
           // var_dump($isj, $iwip, $imaterial, $icolor,$eremark, $nquantity, $nquantitywip, $no);
        }
        //die();
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
            $data = array(
                'sukses' => true,
                'kode'   => $isj,
            );
        } 
        $this->load->view('pesan', $data);      
    }

    public function view(){
        $sj         = $this->uri->segment(4);
        $gudang     = $this->uri->segment(5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'sj'            => $sj,
            'gudang'        => $gudang,
            'isi'           => $this->mmaster->baca($sj,$gudang)->row()
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj    = $this->uri->segment('4');
        $gudang = $this->uri->segment('5');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'isi' => $this->mmaster->baca($isj,$gudang)->row(),
            'detail' => $this->mmaster->bacadetail($isj,$gudang)->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
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

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $isj   = $this->input->post('isj');
        $this->db->trans_begin();
        $this->mmaster->approve($isj);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $sj         = $this->uri->segment('4');
        $gudang     = $this->uri->segment('5');
        $query      = $this->mmaster->bacadetail($sj,$gudang);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'sj'            => $sj,
            'gudang'        => $gudang,
            'jmlitem'       => $query->num_rows(),
            'isi'           => $this->mmaster->baca($sj, $gudang)->row(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dsjk = $this->input->post("dsjk",true);
        if($dsjk != ''){
            $tmp = explode('-', $dsjk);
            $hr = $tmp[0];
            $bl = $tmp[1];
            $th = substr($tmp[2],2,2);
            $dsjk = $tmp[2].'-'.$bl.'-'.$hr;
        }
        $dback    = $this->input->post('dback', TRUE);
        if($dback != ''){
            $tmp   = explode('-', $dback);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dback = $year.'-'.$month.'-'.$day;
        }
        $dforecast    = $this->input->post('dforecast', TRUE);
        if($dforecast != ''){
            $tmp   = explode('-', $dforecast);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dforecast = $year.'-'.$month.'-'.$day;
        }
        $isj             = $this->input->post('isj',TRUE);
        $isubbagian      = $this->input->post('idepartement', TRUE);
        $iforecast       = $this->input->post('iforecast', TRUE);
        $iunitjahit      = $this->input->post('iunitjahit', TRUE);
        $edesc           = $this->input->post('edesc', TRUE);
        $jml             = $this->input->post('jml', TRUE); 
        $fsjcancel       = 'f';
        //ITEM
        $i_productwip    = $this->input->post('iproductwip[]',TRUE);
        $v_price         = $this->input->post('vprice[]',TRUE);
        $i_color         = $this->input->post('icolor[]',TRUE);
        $i_material      = $this->input->post('imaterial[]',TRUE);
        $e_materialname  = $this->input->post('ematerialname[]',TRUE);
        $n_quantitywip   = $this->input->post('nquantitywip[]',TRUE);
        $n_quantity      = $this->input->post('nquantity[]',TRUE);
        $e_remark        = $this->input->post('eremark[]',TRUE);
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
        $this->mmaster->updateheader($isj, $dsjk, $dback, $edesc);
        $this->mmaster->deletedetail($isj);
        $no=0;
        $lastwip='';
        $lastcolor='';
        $nquantitywip=0;
        foreach ($i_productwip as $iwip) {     
            $iwip        = $iwip;
            $vprice      = $v_price[$no];
            $icolor      = $i_color[$no];
            $imaterial   = $i_material[$no];
            $nquantity   = $n_quantity[$no];
            $eremark     = $e_remark[$no];
            
            if ($lastwip == $iwip && $lastcolor == $icolor) {
                $nquantitywip   = $lastqtybarang;
            } else {
                $nquantitywip   = $n_quantitywip[$no];
            }
           
            $this->mmaster->insertdetail($isj, $iwip, $vprice, $imaterial, $icolor, $eremark, $nquantity, $nquantitywip, $no); 
            $lastqtybarang   = $nquantitywip;
            $lastwip         = $iwip;
            $lastcolor       = $icolor;
            $no++;
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Uddate Data SJ Keluar Makloon Unit Jahit'.$this->global['title'].' No SJ : '.$isj.' Unit jahit :'.$iunitjahit);
            $data = array(
                'sukses' => true,
                'kode'   => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function deletedetail(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj			= $this->input->post('isj', TRUE);
		$iproduct		= $this->input->post('iproduct', TRUE);
		$imaterial	    = $this->input->post('imaterial', TRUE);
		$icolor	        = $this->input->post('icolor', TRUE);

        $this->db->trans_begin();
        $this->mmaster->deletedetail($isj, $iproduct, $imaterial, $icolor);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete Item SJ Keluar Makloon Unit Jahit : '.$isj.' Produk : '.$iproduct.' Material : '.$imaterial );
            echo json_encode($data);
        }
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $sj         = $this->input->post('sj');
        $gudang     = $this->input->post('gudang');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($sj, $gudang);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJ Keluar Makloon Unit Jahit : '.$sj.' Unit Jahit :'.$gudang);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */