<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10501';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        echo $this->mmaster->data($this->global['folder'], $siareana, $username, $id_company);
    }

    public function approve(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        $ispb    = $this->uri->segment(4);
        $iarea   = $this->uri->segment(5);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'isi'       => $this->mmaster->baca($ispb,$iarea),
            'detail'    => $this->mmaster->bacadetail($ispb,$iarea)
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb       = $this->input->post('ispb', TRUE);
        $iarea      = $this->input->post('iarea',TRUE);
        $eapprove2  = $this->input->post('eapprove2',TRUE);
        if($eapprove2==''){
            $eapprove2=null;
        }
        $user                   = $this->session->userdata('username');
        if(($iarea!='') && ($ispb!='')){
            $this->db->trans_begin();
            $this->mmaster->approve($ispb, $iarea, $eapprove2, $user);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Approve SPB Area '.$iarea.' No:'.$ispb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }

    public function notapprove(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ispb       = $this->input->post('nospb', TRUE);
        $iarea      = $this->input->post('kdarea',TRUE);
        $eapprove   = $this->input->post('enotapprove',TRUE);
        if($eapprove==''){
            $eapprove=null;
        }
        $user       = $this->session->userdata('username');
        if(($iarea!='') && ($ispb!='')){
            $this->db->trans_begin();
            $this->mmaster->notapprove($ispb, $iarea, $eapprove, $user);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Not Approve SPB Area '.$iarea.' No:'.$ispb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
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
