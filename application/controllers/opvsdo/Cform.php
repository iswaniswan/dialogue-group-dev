<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070506';

    public function __construct() {
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

    public function index() {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function datasupplier(){
        /*$filter = [];
        if($this->input->get('q') != '') {*/
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->bacasupplier($cari);
            foreach($data->result() as $row){
                    $filter[] = array(
                    'id'    => $row->i_supplier,  
                    'text'  => $row->e_supplier_name
                );
            }
            echo json_encode($filter);
        /*} else {
            echo json_encode($filter);
        }*/
    }
    
    public function view(){
        $dfrom      = $this->input->post('dfrom', TRUE);
        $dto        = $this->input->post('dto', TRUE);
    	$isupplier	= $this->input->post('isupplier', TRUE);
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($isupplier==''){
            $isupplier=$this->uri->segment(6);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'isupplier'     => $isupplier
            
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $dfrom     = $this->uri->segment(4);
        $dto       = $this->uri->segment(5);
        $isupplier = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom,$dto,$isupplier);
    }
}

/* End of file Cform.php */
