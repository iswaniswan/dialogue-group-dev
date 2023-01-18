<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070206';

    public function __construct()
    {
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
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
    	$bulan = $this->uri->segment('4');
        $tahun = $this->uri->segment('5');
            
    	echo $this->mmaster->data($bulan,$tahun, $this->i_menu);
    }
    
    public function view(){
    	$bulan = $this->input->post('bulan');
        $tahun   = $this->input->post('tahun');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'bulan' => $bulan,
            'tahun' => $tahun
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iperiode    = $this->uri->segment(4);
		$isalesman   = $this->uri->segment(5);
        $data   = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
            'isalesman'     => $isalesman
        );   

        $this->Logger->write('Membuka Menu detail '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    function detail(){
    	$iperiode   = $this->uri->segment('4');
        $isalesman  = $this->uri->segment('5');
            
    	echo $this->mmaster->detail($iperiode,$isalesman);
    }
}

/* End of file Cform.php */
