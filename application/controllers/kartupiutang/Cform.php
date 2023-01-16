<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107011104';
    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iarea'  => $this->mmaster->bacaarea($idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $tahun      = $this->input->post('tahun');
        $bulan      = $this->input->post('bulan');
        if($tahun==''){
            $tahun=$this->uri->segment(4);
        }
        if($bulan==''){
            $bulan=$this->uri->segment(5);
        }
        $iarea     = $this->input->post('iarea', TRUE);
        if ($iarea =='') {
            $iarea = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'tahun'     => $tahun,
            'bulan'     => $bulan,
            'iarea'     => $iarea/*,
            'total'     => $this->mmaster->total($tahun,$bulan,$iarea)->row()*/
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $tahun      = $this->uri->segment(4);
        $bulan      = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $iperiode   = $this->mmaster->cekperiode();
        echo $this->mmaster->data($tahun, $bulan, $iarea, $this->global['folder'], $iperiode, $this->global['title']);
    }

    public function detail(){
        $inota    = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'isi'           => $this->mmaster->bacadetail($inota,$iarea)
        );
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

}
/* End of file Cform.php */
