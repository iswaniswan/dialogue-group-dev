<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050802';

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
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $iarea  = $this->input->get('iarea', FALSE);
            $data   = $this->mmaster->getcustomer($cari,$iarea);
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
        $iarea      = $this->input->post('iarea', FALSE);
        $data = $this->mmaster->getdetailcus($icustomer, $iarea);
        echo json_encode($data->result_array());  
    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='' && $this->input->get('icustomer') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $icustomer  = $this->input->get('icustomer', FALSE);
            $data       = $this->mmaster->getsalesman($cari,$iarea,$icustomer);
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

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea              = $this->input->post('iarea', TRUE);
        $ikn                = $this->input->post('ikn', TRUE);
        $icustomer          = $this->input->post('icustomer', TRUE);
        $irefference        = $this->input->post('irefference', TRUE);
        $icustomergroupar   = $this->input->post('icustomergroupar', TRUE);
        $isalesman          = $this->input->post('xsalesman', TRUE);
        $ikntype            = '02';
        $drefference        = $this->input->post('drefference', TRUE);
        if($drefference!=''){
            $tmp=explode("-",$drefference);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $drefference=$th."-".$bl."-".$hr;
        }
        $dkn            = $this->input->post('dkn', TRUE);
        if($dkn!=''){
            $tmp=explode("-",$dkn);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dkn=$th."-".$bl."-".$hr;
            $nknyear=$th;
        }
        $fcetak         = 'f';
        $fmasalah       = $this->input->post('fmasalah', TRUE);
        if($fmasalah==''){
            $fmasalah='f';
        }else{
            $fmasalah='t';
        }
        $finsentif  = $this->input->post('finsentif', TRUE);
        if($finsentif==''){
            $finsentif='f';
        }else{
            $finsentif='t';
        }
        $vnetto     = $this->input->post('vnetto', TRUE);
        $vnetto     = str_replace(',','',$vnetto);
        $vsisa      = $vnetto;
        $vgross     = $this->input->post('vgross', TRUE);
        $vgross     = str_replace(',','',$vgross);
        $vdiscount  = $this->input->post('vdiscount', TRUE);
        $vdiscount  = str_replace(',','',$vdiscount);
        $eremark    = $this->input->post('eremark', TRUE);
        if ((isset($irefference) && $irefference != '') && (isset($iarea) && $iarea != '') && (isset($icustomer) && $icustomer != '')){
            $this->db->trans_begin();
            $ikn = $this->mmaster->runningnumberkn($nknyear,$iarea,$ikn);
            $this->mmaster->insert( $iarea,$ikn,$icustomer,$irefference,$icustomergroupar,$isalesman,$ikntype,$dkn,$nknyear,$fcetak,$fmasalah, $finsentif,$vnetto,$vsisa,$vgross,$vdiscount,$eremark,$drefference);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input KN Non Retur No:'.$ikn.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikn
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
