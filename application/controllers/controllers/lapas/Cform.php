<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020224';

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
        require('php/fungsi.php');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }
    
    public function view(){
        $tahun	= $this->input->post('tahun');
        $bulan	= $this->input->post('bulan');
        if($tahun==''){
            $tahun=$this->uri->segment(4);
        }
        if($bulan==''){
            $bulan=$this->uri->segment(5);
        }
        $iperiode  = $tahun.$bulan;
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->getarea($username, $idcompany);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'cash'          => $this->mmaster->bacacash($iperiode, $iarea, $username, $idcompany)->result(),
            'credit'        => $this->mmaster->bacacredit($iperiode, $iarea, $username, $idcompany)->result(),
            'penjualan'     => $this->mmaster->bacapenjualan($iperiode, $iarea, $username, $idcompany)->result()
        );
        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode:'.$tahun.' s/d '.$bulan);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
}

/* End of file Cform.php */
