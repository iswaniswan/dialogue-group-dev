<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070308';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        /*$this->load->library('fungsi');*/
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
    
    public function view(){
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $iperiode = $tahun.$bulan;
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
            'isi'           => $this->mmaster->baca($iperiode),
            'store'         => $this->mmaster->bacastore($iperiode)
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : ');

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
