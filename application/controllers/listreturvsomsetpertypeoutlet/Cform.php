<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107012502';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }
    
    public function view(){
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        $interval = $this->mmaster->interval($dfrom,$dto);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'interval'      => $interval,
            'isi'           => $this->mmaster->bacaperiode($dfrom,$dto,$interval)
        );
        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode:'.$dfrom.' s/d '.$dto);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
}

/* End of file Cform.php */
