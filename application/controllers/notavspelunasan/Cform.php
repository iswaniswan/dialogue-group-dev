<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070128';
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
            'iarea'     => $this->mmaster->bacaarea($idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $bulan     = $this->input->post('bulan', TRUE);
        if ($bulan =='') {
            $bulan = $this->uri->segment(4);
        }

        $tahun       = $this->input->post('tahun', TRUE);
        if ($tahun   =='') {
            $tahun   = $this->uri->segment(5);
        }

        $iarea       = $this->input->post('iarea', TRUE);
        if ($iarea   =='') {
            $iarea   = $this->uri->segment(6);
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'iarea'     => $iarea
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $bulan      = $this->uri->segment(4);
        $tahun      = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);

        echo $this->mmaster->data($bulan, $tahun, $iarea);
    }
}
/* End of file Cform.php */
