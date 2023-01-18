<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021004';

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
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->getarea($iarea, $username, $idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getso(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = strtoupper($this->input->get('iarea'));
            $data       = $this->mmaster->getso($cari, $iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_stockopname,  
                    'text'  => $kuy->i_stockopname." - ".$kuy->d_so
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('istore') != '' && $this->input->get('istorelocation') != '') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $istore = strtoupper($this->input->get('istore'));
            $istorelocation = strtoupper($this->input->get('istorelocation'));
            $data   = $this->mmaster->getproduct($cari, $istore, $istorelocation);
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
        $istorelocation = $this->input->post('istorelocation');
        $iproduct    = $this->input->post('iproduct');
        $istore   = $this->input->post('istore');
        $data  = $this->mmaster->getdetailproduct($iproduct, $istore, $istorelocation);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iadj       = $this->input->post('iadj', TRUE);
        $dadj       = $this->input->post('dadj', TRUE);
        if($dadj!=''){
            $tmp=explode("-",$dadj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dadj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iarea          = $this->input->post('iarea', TRUE);
        $istore         = $this->input->post('istore', TRUE);
        $istorelocation = $this->input->post('istorelocation', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $istockopname   = $this->input->post('istockopname', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if($dadj!='' && $iarea!=''){
            $this->db->trans_begin();
            $iadj = $this->mmaster->runningnumber($thbl,$iarea);
            $this->mmaster->insertheader($iadj, $iarea, $dadj, $istockopname, $eremark, $istore, $istorelocation);
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
                    $this->mmaster->insertdetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$x);
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Adjustment No:'.$iadj.' Area'.$iarea);
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
