<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020405';

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
        require('php/fungsi.php');
    }  

    public function index(){
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function view(){
        $dfrom		    = $this->input->post('dfrom');
        $dto            = $this->input->post('dto');
		$iproductgroup  = $this->input->post('iproductgroup');
		if($dfrom==''){
            $dfrom	= $this->uri->segment(4);
        } 
        if($dto==''){
            $dto    = $this->uri->segment(5);
        } 
        if($iproductgroup==''){
            $iproductgroup    = $this->uri->segment(6);
        } 
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iproductgroup' => $iproductgroup,
            'isi'           => $this->mmaster->baca($dfrom,$dto,$iproductgroup),
            'ob'            => $this->mmaster->bacaob($dfrom,$dto, $iproductgroup)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']." Periode : ".$dfrom.$dto);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
}
/* End of file Cform.php */
