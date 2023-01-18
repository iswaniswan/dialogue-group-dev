<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090502';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Cetak ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        echo $this->mmaster->data();
    }

    public function cetak(){
        $id_company = $this->session->userdata('id_company');
        $ispmb = $this->uri->segment(4);
        $this->load->model('printspmb/mmaster');
        $data['ispmb']=$ispmb;
        $data['page_title'] = $this->lang->line('printspmb');
        $data['isi']=$this->mmaster->baca($ispmb);
        $data['company']  = $this->mmaster->company($id_company)->row();
        $data['detail']=$this->mmaster->bacadetail($ispmb);$data['host']=$_SERVER['REMOTE_ADDR'];
        $data['uri']  = $this->session->userdata('uri');
        $this->Logger->write('Cetak SPMB No:'.$ispmb);

        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    public function update(){
        $id     = $this->input->post('id');
        $this->db->trans_begin();
        $data = $this->mmaster->close($id);
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            echo 'fail';
        } else {
            $this->db->trans_commit();
            echo $id;
        }
    }
}

/* End of file Cform.php */
