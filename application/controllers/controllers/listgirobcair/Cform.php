<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10701130201';
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
        $dfrom     = $this->input->post('dfrom', TRUE);
        if ($dfrom =='') {
            $dfrom = $this->uri->segment(4);
        }
        $dto       = $this->input->post('dto', TRUE);
        if ($dto   =='') {
            $dto   = $this->uri->segment(5);
        }
        $iarea     = $this->input->post('iarea', TRUE);
        if ($iarea =='') {
            $iarea = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder'], $this->global['title']);
    }

    public function detail(){
        $igiro    = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $dfrom    = $this->uri->segment(6);
        $dto      = $this->uri->segment(7);
        $ipl      = $this->uri->segment(8);
        $idt      = $this->uri->segment(9);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' =>'List '.$this->global['title'],
            'igiro' => $igiro,
            'iarea' => $iarea,
            'dfrom' => $dfrom,
            'dto'   => $dto,
            'isi'   => $this->mmaster->bacagiro($igiro,$iarea),
            'detail'   => $this->mmaster->bacadetailpl($ipl,$iarea,$idt)
        );
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }
}
/* End of file Cform.php */
