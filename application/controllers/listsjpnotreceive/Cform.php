<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107030202';

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
        $this->load->library('fungsi');
        /*require_once("php/fungsi.php");*/
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        echo $this->mmaster->data($this->global['folder']);
    }

    public function edit(){
        $isjp  = $this->uri->segment(4);
        $iarea = $this->uri->segment(5);  
        $data = array(
            'folder'  => $this->global['folder'],
            'title'   => $this->global['title'],
            'isjp'    => $isjp,
            'iarea'   => $iarea,
            'isi'     => $this->mmaster->baca($isjp,$iarea),
            'detail'  => $this->mmaster->bacadetail($isjp,$iarea),
        );
        $this->Logger->write('Membuka Detail '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }
}
/* End of file Cform.php */
