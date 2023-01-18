<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021006';

    public function __construct(){
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
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getcustomer($cari);
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

    public function getso(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('icustomer') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $icustomer  = strtoupper($this->input->get('icustomer'));
            $data       = $this->mmaster->getso($cari, $icustomer);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sopb,  
                    'text'  => $kuy->i_sopb." - ".$kuy->d_sopb
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('icustomer') != '') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $icustomer = strtoupper($this->input->get('icustomer'));
            $data   = $this->mmaster->getproduct($cari, $icustomer);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->kode,  
                    'text'  => $kuy->kode." - ".$kuy->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailproduct(){
        header("Content-Type: application/json", true);
        $iproduct    = $this->input->post('iproduct');
        $icustomer   = $this->input->post('icustomer');
        $data  = $this->mmaster->getdetailproduct($iproduct, $icustomer);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iadj           = $this->input->post('iadj', TRUE);
        $dadj           = $this->input->post('dadj', TRUE);
        $thbl           = date('Ym', strtotime($dadj));
        $dadj           = date('Y-m-d', strtotime($dadj)); // Hasil Tahun Bulan Hari
        $icustomer      = $this->input->post('icustomer', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $istockopname   = $this->input->post('istockopname', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if($dadj!='' && $icustomer!=''){
            $this->db->trans_begin();
            $iadj = $this->mmaster->runningnumber($thbl,$icustomer);
            $this->mmaster->insertheader($iadj, $icustomer, $dadj, $istockopname, $eremark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct = $this->input->post('iproduct'.$i, TRUE);
                if (($iproduct!=''||$iproduct!=null)) {
                    $x++;
                    $iproductgrade    = $this->input->post('grade'.$i, TRUE);
                    $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                    $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                    $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                    $eremark          = $this->input->post('eremark'.$i, TRUE);
                    $this->mmaster->insertdetail($iadj,$icustomer,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$x);
                }
            }
            if(($this->db->trans_status() === False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Adjustment Counter No:'.$iadj.' Counter'.$icustomer);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iadj
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
