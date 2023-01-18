<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020501';

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

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea(),
            'kirim'     => $this->mmaster->bacadkb($username,$idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getpelanggan(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('i_area') != '' ) {
            $filter = [];
            $iarea  = $this->input->get('i_area');
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getpelanggan($iarea, $cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_customer,  
                    'text'  => $row->i_customer.' - '.$row->e_customer_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function datasj(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='' && $this->input->get('icus') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = $this->input->get('iarea', FALSE);
            $icust   = $this->input->get('icus', FALSE);
            $data    = $this->mmaster->bacasj($cari,$iarea,$icust);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sj,  
                    'text'  => $kuy->i_sj
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailsj(){
        header("Content-Type: application/json", true);
        $isj   = $this->input->post('isj', FALSE);
        $iarea = $this->input->post('iarea', FALSE);
        $icus  = $this->input->post('icus', FALSE);
        $data = $this->mmaster->bacasjx($iarea,$icus,$isj);
        echo json_encode($data->result_array());  
    }

    public function dataex(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->bacaex($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_ekspedisi,  
                    'text'  => $kuy->i_ekspedisi." - ".$kuy->e_ekspedisi
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailex(){
        header("Content-Type: application/json", true);
        $iekspedisi = $this->input->post('iekspedisi', FALSE);
        $data = $this->mmaster->bacaexx($iekspedisi);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iarea  = $this->mmaster->areanya();
        $dbapb  = $this->input->post('dbapb', TRUE);
        if($dbapb!=''){
            $tmp=explode("-",$dbapb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dbapb=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }else{
            $dbapb = date('Y-m-d');
        }
        $ibapbold       = $this->input->post('ibapbold', TRUE);
        $ibapb          = $this->input->post('ibapb', TRUE);
        $iareasj        = $this->input->post('iarea', TRUE);
        $idkbkirim      = $this->input->post('idkbkirim', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $nbal           = $this->input->post('nbal', TRUE);
        $nbal           = str_replace(',','',$nbal);
        $jml            = $this->input->post('jml', TRUE);
        $jmlx           = $this->input->post('jmlx', TRUE);
        $vbapb          = $this->input->post('vbapb', TRUE);
        $vbapb          = str_replace(',','',$vbapb);
        $vkirim         = $this->input->post('vkirim', TRUE);
        $vkirim         = str_replace(',','',$vkirim);
        if($dbapb!='' && $iareasj!='' && $idkbkirim!='' && $jml!='0' && $jmlx!='0' && $vbapb!='0' ){
            $this->db->trans_begin();
            $ibapb = $this->mmaster->runningnumber($iarea, $thbl);
            $this->mmaster->insertheader($ibapb, $dbapb, $iareasj, $idkbkirim, $icustomer, $nbal, $ibapbold, $vbapb, $vkirim);
            for($i=1;$i<=$jml;$i++){              
                $isj     = $this->input->post('isj'.$i, TRUE);
                $dsj     = $this->input->post('dsj'.$i, TRUE);
                $vsj     = $this->input->post('vsj'.$i, TRUE);
                $vsj     = str_replace(',','',$vsj);
                $eremark = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertdetail($ibapb,$iareasj,$isj,$dbapb,$dsj,$eremark,$i,$vsj);
                $this->mmaster->updatesj($ibapb,$isj,$iareasj,$dbapb);
            }
            for($i=1;$i<=$jmlx;$i++){
                $iekspedisi = $this->input->post('iekspedisi'.$i, TRUE);
                $eremark    = $this->input->post('eremarkx'.$i, TRUE);
                $this->mmaster->insertdetailekspedisi($ibapb,$iareasj,$iekspedisi,$dbapb,$eremark);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input BAPB No:'.$ibapb.' Area:'.$iareasj);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibapb
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
