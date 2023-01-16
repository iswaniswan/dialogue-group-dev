<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10303';

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
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getarea(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iperiode') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iperiode   = $this->input->get('iperiode', FALSE);
            $data       = $this->mmaster->getarea($cari,$iperiode);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_area,  
                    'text'  => $kuy->i_area." - ".$kuy->e_area_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getvarea(){
        header("Content-Type: application/json", true);
        $iperiode = $this->input->post('iperiode');
        $iarea    = $this->input->post('iarea');
        $data     = $this->mmaster->getvarea($iperiode, $iarea);      
        echo json_encode($data->result_array());  
    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iperiode') !='' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iperiode   = $this->input->get('iperiode', FALSE);
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getsalesman($cari,$iperiode,$iarea);
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

    public function getvsalesman(){
        header("Content-Type: application/json", true);
        $iperiode  = $this->input->post('iperiode');
        $iarea     = $this->input->post('iarea');
        $isalesman = $this->input->post('isalesman');
        $data      = $this->mmaster->getvsalesman($iperiode, $iarea, $isalesman);      
        echo json_encode($data->result_array());  
    }

    public function getcity(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iperiode') !='' && $this->input->get('iarea') !='' && $this->input->get('isalesman') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iperiode   = $this->input->get('iperiode', FALSE);
            $iarea      = $this->input->get('iarea', FALSE);
            $isalesman  = $this->input->get('isalesman', FALSE);
            $data       = $this->mmaster->getcity($cari,$iperiode,$iarea,$isalesman);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_city,  
                    'text'  => $kuy->i_city." - ".$kuy->e_city_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getvcity(){
        header("Content-Type: application/json", true);
        $iperiode  = $this->input->post('iperiode');
        $iarea     = $this->input->post('iarea');
        $isalesman = $this->input->post('isalesman');
        $icity     = $this->input->post('icity');
        $data      = $this->mmaster->getvcity($iperiode, $iarea, $isalesman, $icity);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea           = $this->input->post('iarea', TRUE);
        $isalesman       = $this->input->post('isalesman', TRUE);
        $icity           = $this->input->post('icity', TRUE);
        $vareatarget     = $this->input->post('vareatarget', TRUE);
        $vareatarget     = str_replace(',','',$vareatarget);
        $vsalesmantarget = $this->input->post('vsalesmantarget', TRUE);
        $vsalesmantarget = str_replace(",","",$vsalesmantarget);
        $vcitytarget     = $this->input->post('vcitytarget', TRUE);
        $vcitytarget     = str_replace(",","",$vcitytarget);
        $bulan           = $this->input->post('bulan', TRUE);
        $tahun           = $this->input->post('tahun', TRUE);
        $iperiode        = $this->input->post('tahun', TRUE).$this->input->post('bulan', TRUE);
        if ((isset($iarea) && $iarea != '')  && (isset($isalesman) && $isalesman != '') && (isset($icity) && $icity != '')  && (isset($bulan) && $bulan != '') && (isset($tahun) && $tahun != '') && (isset($iperiode) && $iperiode != '')){
            $this->db->trans_begin();
            $this->mmaster->insert($iperiode, $iarea, $isalesman, $icity, $vareatarget, $vsalesmantarget, $vcitytarget);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Target Sales Periode:'.$iperiode.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isalesman
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
