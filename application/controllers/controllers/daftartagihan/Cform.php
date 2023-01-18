<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10503';

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
            'area'      => $this->mmaster->bacaarea($username, $idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getnota(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getnota($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_nota,  
                    'text'  => $kuy->i_nota." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailnota(){
        header("Content-Type: application/json", true);
        $inota = $this->input->post('inota');
        $iarea = $this->input->post('iarea');
        $data  = $this->mmaster->getdetailnota($inota,$iarea);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idt        = $this->input->post('idt', TRUE);
        $ddt        = $this->input->post('ddt', TRUE);
        if($ddt!=''){
            $tmp=explode("-",$ddt);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $ddt=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iarea      = $this->input->post('iarea', TRUE);
        $vjumlahtot = $this->input->post('vjumlah', TRUE);
        $vjumlahtot = str_replace(',','',$vjumlahtot);
        $jml        = $this->input->post('jml', TRUE);
        if(($iarea!='') && ($idt!='') && ($ddt!='') && ($vjumlahtot!='0') && ($jml!='0')){
            $this->db->trans_begin();
            $cekdt = $this->mmaster->cekdt($idt, $iarea);
            if($cekdt->num_rows() > 0){
                $xdt = $this->mmaster->runningnumberdt($iarea,$thbl);
                echo "<script>swal('No DT ".$idt." Area ".$iarea." Sudah Ada, Dirubah Jadi No DT ".$xdt."');</script>";
                $idt = $xdt;
            }else{
                $idt = $idt;
            }
            $fsisa='f';
            for($i=1;$i<=$jml;$i++){
                $inota              = $this->input->post('inota'.$i, TRUE);
                $dnota              = $this->input->post('dnota'.$i, TRUE);
                $icustomer          = $this->input->post('icustomer'.$i, TRUE);
                $vsisa              = $this->input->post('vsisa'.$i, TRUE);
                $vsisa              = str_replace(',','',$vsisa);
                $vjumlah            = $this->input->post('vjumlah'.$i, TRUE);
                $vjumlah            = str_replace(',','',$vjumlah);
                if($vsisa>0){
                    $fsisa='t';
                }
                $this->mmaster->insertdetail($idt,$ddt,$inota,$iarea,$dnota,$icustomer,$vsisa,$vjumlah,$i);
            }
            $this->mmaster->insertheader($idt,$iarea,$ddt,$vjumlahtot,$fsisa);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input DT Area:'.$iarea.' No:'.$idt);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idt
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
