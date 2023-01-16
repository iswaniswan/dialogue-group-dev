<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2060401';

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
    }

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function view(){
        $dfrom = $this->input->post('dfrom1');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }

        $dto = $this->input->post('dto1');
        if($dto==''){
            $dto=$this->uri->segment(5);
        }       

        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$dd.'-'.$mm.'-'.$yy;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$dd.'-'.$mm.'-'.$yy;  

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'data'          => $this->mmaster->getmutasi($from, $to, $dd, $yy)->result(),
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */