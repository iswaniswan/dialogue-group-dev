<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020301';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    public function data(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $iarea      = $this->mmaster->cekarea($username, $idcompany);
        $tahun      = $this->input->post('tahun');
        $bulan      = $this->input->post('bulan');
        if($tahun==''){
            $tahun=$this->uri->segment(4);
        }
        if($bulan==''){
            $bulan=$this->uri->segment(5);
        }
        echo $this->mmaster->data($tahun,$bulan,$iarea,$username,$idcompany,$this->global['folder']);
    }
    
    public function view(){
        $tahun  = $this->input->post('tahun');
        $bulan  = $this->input->post('bulan');
        if($tahun==''){
            $tahun=$this->uri->segment(4);
        }
        if($bulan==''){
            $bulan=$this->uri->segment(5);
        }
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $iarea      = $this->mmaster->cekarea($username, $idcompany);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'total'         => $this->mmaster->total($tahun,$bulan,$iarea,$username,$idcompany)->row()
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$tahun.$bulan);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function detailnota(){
        $iarea    = $this->uri->segment(4);
        $iperiode = $this->uri->segment(5);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
            'isi'           => $this->mmaster->bacadetailnota($iarea,$iperiode)
        );
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function detailkn(){
        $iarea    = $this->uri->segment(4);
        $iperiode = $this->uri->segment(5);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
            'isi'           => $this->mmaster->bacadetailkn($iarea,$iperiode)
        );
        $this->load->view($this->global['folder'].'/vformdetailkn', $data);
    }
}

/* End of file Cform.php */
