<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10304';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $bulan     = $this->input->post('bulan', TRUE);
        $tahun     = $this->input->post('tahun', TRUE);
        $iperiode  = $tahun.$bulan;
        $this->mmaster->updatemodul($iperiode);
        $this->mmaster->simpan($iperiode);
        $this->Logger->write('Proses Update Penjualan Periode:'.$iperiode);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'iperiode'  => $iperiode,
            'data'      => $this->mmaster->baca($iperiode)
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function persales(){
        $iperiode = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iperiode'  => $iperiode,
            'data'      => $this->mmaster->bacapersales($iperiode, $iarea)
        );
        $this->load->view($this->global['folder'].'/vformviewsales', $data);
    }

    public function pernota(){
        $iperiode = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iperiode'  => $iperiode,
            'data'      => $this->mmaster->bacapernota($iperiode, $iarea)
        );
        $this->load->view($this->global['folder'].'/vformviewnota', $data);
    }

    public function perkota(){
        $iperiode = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iperiode'  => $iperiode,
            'data'      => $this->mmaster->bacaperkota($iperiode, $iarea)
        );
        $this->load->view($this->global['folder'].'/vformviewkota', $data);
    }

    public function retur(){
        $iperiode = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iperiode'  => $iperiode,
            'data'      => $this->mmaster->bacaretur($iperiode, $iarea)
        );
        $this->load->view($this->global['folder'].'/vformviewretur', $data);
    }
}
/* End of file Cform.php */
