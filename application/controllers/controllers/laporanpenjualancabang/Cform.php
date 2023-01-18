<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070213';

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
        require_once("php/fungsi.php");
    }
    

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
            'iarea'  => $iarea,
            'area'   => $this->mmaster->bacaarea($username, $idcompany, $iarea)
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
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
            'title'         => $this->global['title'],
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'iarea'         => $iarea,
            'isi'           => $this->mmaster->bacaperiode($tahun.$bulan,$iarea)
        );
        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode:'.$tahun.$bulan.' Area : '.$iarea);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
    
    public function persales(){
        $iperiode = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'].' Persales',
            'iperiode'      => $iperiode,
            'iarea'         => $iarea,
            'isi'           => $this->mmaster->bacapersales($iperiode,$iarea)
        );
        $this->Logger->write('Membuka Data '.$this->global['title'].' Persales Periode:'.$iperiode);
        $this->load->view($this->global['folder'].'/vformlistsales', $data);
    }
}

/* End of file Cform.php */
