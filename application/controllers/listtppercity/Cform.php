<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020303';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        require('php/fungsi.php');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $iarea      = $this->mmaster->cekarea($username, $idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
            'area'      => $this->mmaster->bacaarea($iarea, $username, $idcompany),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    public function data(){
        $tahun      = $this->input->post('tahun');
        $bulan      = $this->input->post('bulan');
        $iarea      = $this->input->post('iarea');
        if($tahun==''){
            $tahun=$this->uri->segment(4);
        }
        if($bulan==''){
            $bulan=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        echo $this->mmaster->data($tahun,$bulan,$iarea);
    }
    
    public function view(){
        $tahun  = $this->input->post('tahun');
        $bulan  = $this->input->post('bulan');
        $iarea  = $this->input->post('iarea');
        if($tahun==''){
            $tahun=$this->uri->segment(4);
        }
        if($bulan==''){
            $bulan=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'iarea'         => $iarea,
            'total'         => $this->mmaster->total($tahun,$bulan,$iarea)->row()
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$tahun.$bulan);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
