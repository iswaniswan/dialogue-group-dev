<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070114';
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
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $icustomer     = $this->input->post('icustomer', TRUE);
        if ($icustomer =='') {
            $icustomer = $this->uri->segment(4);
        }
        $iproduct       = $this->input->post('iproduct', TRUE);
        if ($iproduct   =='') {
            $iproduct   = $this->uri->segment(5);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'icustomer' => $icustomer,
            'iproduct'  => $iproduct,
            'isi'       => $this->mmaster->bacacustomer($icustomer)->result()
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $icustomer      = $this->uri->segment(4);
        $iproduct       = $this->uri->segment(5);
        echo $this->mmaster->data($icustomer, $iproduct, $this->global['folder'],$this->global['title']);
    }

}
/* End of file Cform.php */
