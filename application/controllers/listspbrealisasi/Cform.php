<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020112';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
            'area'      => $this->mmaster->bacaarea($username, $idcompany)->result(),
            'xarea'     => $this->mmaster->cekarea($username, $idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $iarea  = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
    	echo $this->mmaster->data($dfrom,$dto,$iarea);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
        if($area==''){
            $area=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
