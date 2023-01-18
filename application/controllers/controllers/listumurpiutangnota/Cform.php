<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107010602';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }
    
    public function view(){
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $d_opname = $this->input->post('dopname');
        
        $tmp=explode("-",$d_opname);
		$th=$tmp[2];
		$bl=$tmp[1];
		$hr=$tmp[0];
        $d_opname=$th."-".$bl."-".$hr;
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'd_opname'      => $d_opname,
            'isi'           => $this->mmaster->bacaperiode($tahun,$bulan,$d_opname)
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$tahun.$bulan);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
