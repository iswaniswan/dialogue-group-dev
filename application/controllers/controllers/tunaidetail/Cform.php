<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10506';

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
        $iarea     = $this->mmaster->cekuser($username,$idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea($iarea, $username, $idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='' && $this->input->get('dtunai') !='') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $tgl    = $this->input->get('dtunai', FALSE);
            $tmp=explode('-',$tgl);
            $yy=$tmp[2];
            $mm=$tmp[1];
            $per=$yy.$mm;
            $iarea  = $this->input->get('iarea', FALSE);
            $data   = $this->mmaster->getcustomer($cari,$iarea,$per);
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

    public function getdetailcus(){
        header("Content-Type: application/json", true);
        $icustomer  = strtoupper($this->input->post('icustomer', FALSE));
        $tgl        = $this->input->post('dtunai', FALSE);
        $tmp        = explode('-',$tgl);
        $yy         = $tmp[2];
        $mm         = $tmp[1];
        $per        = $yy.$mm;
        $iarea      = $this->input->post('iarea', FALSE);
        $data = $this->mmaster->getdetailcus($icustomer, $per, $iarea);
        echo json_encode($data->result_array());  
    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='' && $this->input->get('dtunai') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $tgl        = $this->input->get('dtunai', FALSE);
            $tmp        = explode('-',$tgl);
            $yy         = $tmp[2];
            $mm         = $tmp[1];
            $per        = $yy.$mm;
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getsalesman($cari,$iarea,$per);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_salesman,  
                    'text'  => $kuy->i_salesman." - ".$kuy->e_salesman_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function nota(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '' && $this->input->get('dtunai') != '' && $this->input->get('icustomer') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea');
            $icustomer  = $this->input->get('icustomer');
            $dtunai     = $this->input->get('dtunai');
            $tmp        = explode("-",$dtunai);
            $dd         = $tmp[0];
            $mm         = $tmp[1];
            $yy         = $tmp[2];
            $dtunaix    = $yy.'-'.$mm.'-'.$dd;
            $data       = $this->mmaster->nota($cari,$iarea,$icustomer,$dtunaix);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_nota,  
                    'text'  => $kuy->i_nota
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailnota(){
        header("Content-Type: application/json", true);
        $inota      = $this->input->post('inota');
        $iarea      = $this->input->post('iarea');
        $icustomer  = $this->input->post('icustomer');
        $dtunai     = $this->input->post('dtunai');
        $tmp        = explode("-",$dtunai);
        $dd         = $tmp[0];
        $mm         = $tmp[1];
        $yy         = $tmp[2];
        $dtunaix    = $yy.'-'.$mm.'-'.$dd;
        $data = $this->mmaster->getdetailnota($inota,$iarea,$icustomer,$dtunaix);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dtunai = $this->input->post('dtunai', TRUE);
        if($dtunai!=''){
            $tmp=explode("-",$dtunai);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dtunai=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
            $tahun=$th;
        }
        $isalesman        = $this->input->post('xsalesman', TRUE);
        $iarea            = $this->input->post('iarea', TRUE);
        $icustomer        = $this->input->post('icustomer', TRUE);
        $icustomergroupar = $this->input->post('icustomergroupar', TRUE);
        $xeremark         = $this->input->post('xeremark', TRUE);
        $vjumlah          = $this->input->post('vjumlah', TRUE);
        $vjumlah          = str_replace(',','',$vjumlah);
        $jml              = $this->input->post('jml', TRUE);
        $jml              = str_replace(',','',$jml);
        $lebihbayar       = $this->input->post('lebihbayar',TRUE);
        if($lebihbayar!=''){
            $lebihbayar= 't';
        }else{
            $lebihbayar= 'f';
        }
        if (($dtunai != '') && ($iarea!='') && ($vjumlah!='') && ($vjumlah!='0')){
            $this->db->trans_begin();
            $itunai = $this->mmaster->runningnumber($iarea,$thbl);
            $this->mmaster->insert($itunai,$dtunai,$iarea,$icustomer,$icustomergroupar,$isalesman,$xeremark,$vjumlah,$vjumlah,$lebihbayar);
            for($i=1;$i<=$jml;$i++){
                $iarea        = $this->input->post('iarea'.$i, TRUE);
                $inota        = $this->input->post('inota'.$i, TRUE);
                $vjumlah      = $this->input->post('vjumlah'.$i, TRUE);
                $vjumlah      = str_replace(',','',$vjumlah);
                $this->mmaster->insertdetail($itunai,$iarea,$inota,$vjumlah,$i);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Tunai Area '.$iarea.' No:'.$itunai);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $itunai
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
