<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070303';

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
            'title'     => "Info ".$this->global['title'],
            'area'      => $this->mmaster->bacaarea(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    public function data(){
        $tgl       = $this->input->post('tgl');
        $iperiode  = $this->input->post('iperiode');
        if($tgl==''){
            $tgl=$this->uri->segment(4);
        }
        if($iperiode==''){
            $iperiode=$this->uri->segment(5);
        }
    	echo $this->mmaster->data($tgl,$iperiode);
    }
    
    public function view(){
        $tgl  = $this->input->post('dfrom');
        if($tgl==''){
            $tgl=$this->uri->segment(4);
        }

        $iperiode = '';
        if ($tgl!='') {
            $tgl = date('Ymd', strtotime($tgl));
            $iperiode = date('Ym', strtotime($tgl));
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'tgl'           => $tgl,
            'iperiode'      => $iperiode,
            'isi'           => $this->mmaster->baca($tgl,$iperiode),
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$iperiode);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
