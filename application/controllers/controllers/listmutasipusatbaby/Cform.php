<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070408';

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
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }
    
    public function view(){
        $iperiode   = $this->input->post('tahun', TRUE).$this->input->post('bulan', TRUE);
        if($iperiode==''){
            $iperiode=$this->uri->segment(4);
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'iperiode'      => $iperiode,
            'isi'           => $this->mmaster->bacaperiode($iperiode)
        );
        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode:'.$iperiode);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
    
    public function detail(){
        $iperiode = $this->uri->segment(4);
        $iproduct = $this->uri->segment(5);
        $saldo    = $this->uri->segment(6);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'iperiode'      => $iperiode,
            'iproduct'      => $iproduct,
            'saldo'         => $saldo,
            'detail'        => $this->mmaster->detail($iperiode,$iproduct)
        );
        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode:'.$iperiode);
        $this->load->view($this->global['folder'].'/vformlistdetail', $data);
    }
    
    public function cetakdetail(){
        $iperiode = $this->uri->segment(4);
        $iproduct = $this->uri->segment(5);
        $saldo    = $this->uri->segment(6);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'iperiode'      => $iperiode,
            'iproduct'      => $iproduct,
            'saldo'         => $saldo,
            'detail'        => $this->mmaster->detail($iperiode,$iproduct)
        );
        $this->Logger->write('Cetak Detail Mutasi PB Pelanggan Data '.$this->global['title'].' Periode:'.$iperiode);
        $this->load->view($this->global['folder'].'/vformprintdetail', $data);
    }
}

/* End of file Cform.php */
