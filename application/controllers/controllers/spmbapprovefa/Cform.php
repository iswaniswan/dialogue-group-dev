<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020904';

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
        $this->load->library('fungsi');
        /*require_once("php/fungsi.php");*/
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        echo $this->mmaster->data($this->global['folder']);
    }

    public function approve(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ispmb      = $this->uri->segment(4);
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'isi'               => $this->mmaster->baca($ispmb),
            'detail'            => $this->mmaster->bacadetail($ispmb)
        );
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getproduct($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,  
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailproduct(){
        header("Content-Type: application/json", true);        
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $tgl    = date('d-m-Y');
        $tmp    = explode("-",$tgl);
        $thak   = substr($tmp[2],2,2);
        $blak   = $tmp[1];
        $blaw   = $tmp[1];
        $thaw=$thak;
        for($z=1;$z<=3;$z++){
            settype($blaw,'integer');
            $blaw=$blaw-1;
            if($blaw==0){
                $blaw=12;
                $thaw=$thaw-1;
            }
        }
        settype($blaw,'string');
        if(strlen($blaw)==1){
            $blaw='0'.$blaw;
        }
        $peraw=$thaw.$blaw;
        $perak=$thak.$blak;
        $fpaw ='FP-'.$peraw;
        $fpak ='FP-'.$perak;
        $iproduct = $this->input->post('iproduct');
        $data = $this->mmaster->getdetailproduct($iproduct,$fpaw,$fpak,$username,$idcompany);      
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispmb      = $this->input->post('ispmb', TRUE);
        $eapprove2  = $this->input->post('eapprove2',TRUE);
        if($eapprove2==''){
            $eapprove2=null;
        }
        $user       = $this->session->userdata('username');
        if($ispmb!=''){
            $this->db->trans_begin();
            $this->mmaster->approve($ispmb,$eapprove2,$user);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('ACC SPMB No:'.$ispmb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispmb
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
