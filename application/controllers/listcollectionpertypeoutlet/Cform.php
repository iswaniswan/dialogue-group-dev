<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107012701';
    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        require('php/fungsi.php');
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
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $dfrom     = $this->input->post('dfrom', TRUE);
        if ($dfrom =='') {
            $dfrom = $this->uri->segment(4);
        }
        $dto       = $this->input->post('dto', TRUE);
        if ($dto   =='') {
            $dto   = $this->uri->segment(5);
        }

        $interval  = $this->mmaster->interval($dfrom,$dto);
        $sumperiode = $this->mmaster->sumperiode($dfrom,$dto,$interval);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'interval'  => $interval,
            'sumperiode'=> $sumperiode,
            'isi'       => $this->mmaster->bacaperiode($dfrom,$dto,$interval)
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        echo $this->mmaster->data($dfrom, $dto, $this->global['folder'], $this->global['title']);
    }*/

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
