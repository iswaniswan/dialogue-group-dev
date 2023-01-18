<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070505';

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
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function view(){
        $bulan  = $this->input->post('bulan');
        $tahun  = $this->input->post('tahun');

        if($bulan==''){
            $bulan = substr($this->uri->segment(4), 4,2);
        }
        if($tahun==''){
            $tahun = substr($this->uri->segment(4), 0,4);
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bulan'         => $bulan,
            'tahun'         => $tahun,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $bulan  = $this->input->post('bulan');
        $tahun  = $this->input->post('tahun');
        if($bulan==''){
            $bulan=$this->uri->segment(4);
        }
        if($tahun==''){
            $tahun=$this->uri->segment(5);
        }
        echo $this->mmaster->data($bulan,$tahun,$this->global['folder'],$this->i_menu);
    }

    public function edit(){
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ido        = $this->uri->segment(4);
            $isupplier  = $this->uri->segment(5);
            $iperiode   = $this->uri->segment(6);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'ido'           => $ido,
                'isupplier'     => $isupplier,
                'iperiode'      => $iperiode,
                'isi'           => $this->mmaster->baca($ido,$isupplier),
                'detail'        => $this->mmaster->bacadetail($ido,$isupplier),
            );   
        }        

        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }
}

/* End of file Cform.php */
