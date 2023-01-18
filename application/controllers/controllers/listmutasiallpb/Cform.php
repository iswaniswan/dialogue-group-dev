<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070415';

    public function __construct(){
        parent::__construct();
        cek_session();
        require('php/fungsi.php');
        
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
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }
    
    public function view(){
        $iperiode = $this->input->post('tahun').$this->input->post('bulan');
        
        if($iperiode == ''){
            $iperiode = $this->uri->segment(4);
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'iperiode'          => $iperiode,
            'isi'               => $this->mmaster->baca($iperiode),
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$iperiode);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function detail(){
        $iperiode   = $this->uri->segment(4);
        $icustomer  = $this->uri->segment(5);
        $iproduct   = $this->uri->segment(6);
        $saldo      = $this->uri->segment(7);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'iperiode'   => $iperiode,
            'icustomer'  => $icustomer,
            'iproduct'   => $iproduct,
            'saldo'      => $saldo,
            'detail'     => $this->mmaster->detail($iperiode,$icustomer,$iproduct)
        );

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function cetakdetail(){
        $iperiode = $this->uri->segment(4);
        $icustomer = $this->uri->segment(5);
        $iproduct = $this->uri->segment(6);
        $saldo = $this->uri->segment(7);

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Print ".$this->global['title'],
            'iperiode'          => $iperiode,
            'icustomer'         => $icustomer,
            'iproduct'          => $iproduct,
            'saldo'             => $saldo,
            'detail'            => $this->mmaster->detail($iperiode,$icustomer,$iproduct)
        );

        $this->Logger->write('Cetak Detail Mutasi PB Pelanggan : '.$icustomer.' Product : ' .$iproduct);

        $this->load->view($this->global['folder'].'/vformprintdetail', $data);
    }
}

/* End of file Cform.php */
