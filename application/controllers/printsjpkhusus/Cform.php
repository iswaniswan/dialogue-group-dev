<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090801';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Cetak ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $iarea  = $this->session->userdata('i_area');
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
        $xarea = $this->mmaster->cekarea();
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu,$xarea);
    }
    
    public function view(){
    	$area	= $this->session->userdata('i_area');
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
            'title'         => "List ".$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function cetak(){
            $isj  = $this->uri->segment(4);
            $area = $this->session->userdata('i_area');
            $id_company = $this->session->userdata('id_company');
            $this->load->model('printsjpkhusus/mmaster');$data['isj']=$isj;
            $data['page_title'] = $this->lang->line('printsjp');
            $data['isi']=$this->mmaster->baca($isj,$area);
            $data['detail']=$this->mmaster->bacadetail($isj,$area);
            $data['company']=$this->mmaster->company($id_company)->row();
            $data['user']   = $this->session->userdata('user_id');
            // $data['host']   = $ip_address;
            $data['uri']    = $this->session->userdata('printeruri');
            $data['iarea']  = $area;

        $this->Logger->write('Cetak SJP Area:'.$area.' No:'.$isj);

        $this->load->view($this->global['folder'].'/vformrpt', $data);
    }
}

/* End of file Cform.php */
