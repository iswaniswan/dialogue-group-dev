<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050501';

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
            'area'      => $this->mmaster->bacaarea()
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

    public function getdt(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $tgl1       = date('Y-m-d'); /*pendefinisian tanggal awal*/
            $tgl2       = date('Y-m-d', strtotime('-3 month', strtotime($tgl1))); /*operasi penjumlahan tanggal sebanyak 6 bulan*/
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getdt($cari,$iarea,$tgl2);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_dt,  
                    'text'  => $kuy->i_dt." - ".$kuy->d_dt
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetaildt(){
        header("Content-Type: application/json", true);
        $iarea  = $this->input->post('iarea');
        $idt    = $this->input->post('idt');
        $data   = $this->mmaster->getdetaildt($iarea, $idt);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $igiro              = $this->input->post('igiro', TRUE);
        $iarea              = $this->input->post('iarea', TRUE);
        $idt                = $this->input->post('idt', TRUE);
        $icustomer          = $this->input->post('icustomer', TRUE);
        $dgiro              = $this->input->post('dgiro', TRUE);
        if($dgiro!=''){
            $tmp=explode("-",$dgiro);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dgiro=$th."-".$bl."-".$hr;
        }
        $dsetor         = $this->input->post('dsetor', TRUE);
        if($dsetor!=''){
            $tmp=explode("-",$dsetor);
            $rvth=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsetor=$rvth."-".$bl."-".$hr;
        }
        $dgiroduedate   = $this->input->post('dgiroduedate', TRUE);
        if($dgiroduedate!=''){
            $tmp=explode("-",$dgiroduedate);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dgiroduedate=$th."-".$bl."-".$hr;
        }
        $dgiroterima            = $this->input->post('dgiroterima', TRUE);
        if($dgiroterima!=''){
            $tmp=explode("-",$dgiroterima);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dgiroterima=$th."-".$bl."-".$hr;
        }
        $egirodescription   = $this->input->post('egirodescription', TRUE);
        $egirobank          = $this->input->post('egirobank', TRUE);
        $vjumlah            = $this->input->post('vjumlah', TRUE);
        $vjumlah            = str_replace(',','',$vjumlah);
        $vsisa              = $vjumlah;
        if ((isset($igiro) && $igiro != '') && (isset($idt) && $idt != '') && (isset($dgiroterima) && $dgiroterima != '') && (isset($dgiro) && $dgiro != '') && (isset($iarea) && $iarea != '') && (isset($icustomer) && $icustomer != '') && (isset($dgiro) && $dgiro != '') && (isset($dgiroduedate) && $dgiroduedate != '') && (isset($vsisa) && $vsisa != '')){
            $this->db->trans_begin();
            $irv = $this->mmaster->runningnumberrv($rvth);
            if($dsetor==''){
                $dsetor=null;
            }
            $this->mmaster->insert($igiro,$iarea,$icustomer,$irv,$dgiro,$dsetor,$dgiroduedate,$egirodescription,$egirobank,$vsisa,$vsisa,$idt,$dgiroterima);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Giro No:'.$igiro.' Pelanggan:'.$icustomer);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $igiro
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
