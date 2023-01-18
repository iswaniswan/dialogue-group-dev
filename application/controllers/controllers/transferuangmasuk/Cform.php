<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050401';

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
            'bank'      => $this->mmaster->bacabank()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcustomer($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailcustomer(){
        header("Content-Type: application/json", true);
        $iarea     = $this->input->post('iarea');
        $icustomer = $this->input->post('icustomer');
        $data      = $this->mmaster->getdetailcustomer($iarea, $icustomer);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikum   = $this->input->post('ikum', TRUE);
        $dkum   = $this->input->post('dkum', TRUE);
        if($dkum!=''){
            $tmp=explode("-",$dkum);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dkum=$th."-".$bl."-".$hr;
            $tahun=$th;
        }
        $ibank            = $this->input->post('ibank', TRUE);
        $ebankname        = $this->input->post('ebankname', TRUE);
        $iarea            = $this->input->post('iarea', TRUE);
        $icustomer        = $this->input->post('icustomer', TRUE);
        $icustomergroupar = $this->input->post('icustomergroupar', TRUE);
        $isalesman        = $this->input->post('isalesman', TRUE);
        $esalesmanname    = $this->input->post('esalesmanname', TRUE);
        $eremark          = $this->input->post('eremark', TRUE);
        $vjumlah          = $this->input->post('vjumlah', TRUE);
        $vjumlah          = str_replace(',','',$vjumlah);
        $vsisa            = $this->input->post('vsisa', TRUE);
        $vsisa            = str_replace(',','',$vsisa);
        if (($ikum != '') && ($iarea!='') && ($tahun!='') && ($ibank!='')){
            $this->db->trans_begin();
            $cek = $this->mmaster->cek($iarea,$ikum,$tahun);
            if(!$cek){                          
                $this->mmaster->insert($ikum,$dkum,$tahun,$ebankname,$iarea,$icustomer,$icustomergroupar,$isalesman,$eremark,$vjumlah,$vsisa,$ibank);
            }else{
                $ikum="Bukti transfer ".$ikum." sudah ada, untuk mengedit lewat menu edit Transfer";
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input KU Area '.$iarea.' No:'.$ikum);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikum
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
