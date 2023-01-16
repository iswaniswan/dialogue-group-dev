<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107030216';

    public function __construct(){
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Info ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        echo $this->mmaster->data($this->global['folder']);
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        if($this->uri->segment(4)!=''){
            $isjp	= $this->uri->segment(4);
		    $iarea= $this->uri->segment(5);
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'isjp'           => $isjp,
                'iarea'          => $iarea,
                'isi'            => $this->mmaster->baca($isjp,$iarea)->row(),
                'detail'         => $this->mmaster->bacadetail($isjp,$iarea)->result()
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }
    }

}

/* End of file Cform.php */
