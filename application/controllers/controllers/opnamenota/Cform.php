<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107010703';
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
        $notajt     = $this->input->post('notajt', TRUE);
        if ($notajt =='') {
            $notajt = $this->uri->segment(4);
        }
        $dopname       = $this->input->post('dopname', TRUE);
        if ($dopname   =='') {
            $dopname   = $this->uri->segment(5);
        }
        $iarea     = $this->input->post('iarea', TRUE);
        if ($iarea =='') {
            $iarea = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'notajt'   => $notajt,
            'dopname'   => $dopname,
            'iarea'     => $iarea,
            'total'     => $this->mmaster->total($notajt,$dopname,$iarea)->row()
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $notajt     = $this->uri->segment(4);
        $dopname    = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $iperiode   = $this->mmaster->cekperiode();
        echo $this->mmaster->data($notajt, $dopname, $iarea, $this->global['folder'], $iperiode, $this->global['title']);
    }

    public function detail(){
        $icustomer    = $this->uri->segment(4);
        $isalesman    = $this->uri->segment(5);
        $dopname      = $this->uri->segment(6);
        $notajt       = $this->uri->segment(7);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'isi'           => $this->mmaster->bacadetail($icustomer,$isalesman,$dopname)
        );
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

}
/* End of file Cform.php */
