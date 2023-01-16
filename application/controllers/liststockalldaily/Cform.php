<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070304';

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
        $tgl  = $this->input->post('dfrom');
        if($tgl != ''){
            $tmp=explode('-',$tgl);
            $dd=$tmp[0];
            $mm=$tmp[1];
            $yy=$tmp[2];
            $tgl=$yy.'-'.$mm.'-'.$dd;
        }else{
            echo "pilih dulu tanggal !";
            die;
        }
        $iperiode   = $yy.$mm;
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
            'tgl'           => $tgl,
            'isi'           => $this->mmaster->baca($tgl,$iperiode),
            'store'         => $this->mmaster->bacastore($tgl,$iperiode)
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : ');

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
