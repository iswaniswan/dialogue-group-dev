<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020231';

    public function __construct(){
        parent::__construct();
        cek_session();
        
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
            'title'     => "Info ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $bulan = $this->input->post('bulan');
        $tahun  = $this->input->post('tahun');
        if($bulan==''){
            $bulan=$this->uri->segment(4);
        }
        if($tahun==''){
            $tahun=$this->uri->segment(5);
        }
        
    	echo $this->mmaster->data($bulan,$tahun);
    }
    
    public function view(){
        $bulan  = $this->input->post('bulan');
        $tahun    = $this->input->post('tahun');
        if($bulan==''){
            $bulan=$this->uri->segment(4);
        }
        if($tahun==''){
            $tahun=$this->uri->segment(5);
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'total'         => $this->mmaster->total($bulan,$tahun)->row()
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$bulan.$tahun);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
