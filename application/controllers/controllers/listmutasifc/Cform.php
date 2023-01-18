<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1040108';

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
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'bulan'     => date('m'),
            'tahun'     => date('Y')

        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformawal', $data);

    }

    function data(){
        $periode    = $this->uri->segment('4');
            
    	echo $this->mmaster->data($periode);
    }

    function proses(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $bulan      = $this->input->post('iperiodebl', TRUE);
        $tahun      = $this->input->post('iperiodeth', TRUE);
        $periode    = $tahun.$bulan;
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'periode'  => $periode
        );
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
}
/* End of file Cform.php */
