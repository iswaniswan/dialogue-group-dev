<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107010708';
    public function __construct()
    {
        parent::__construct();
        cek_session();
        require('php/fungsi.php');
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
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
            'iarea'     => $iarea,
            'total'     => $this->mmaster->total($dfrom,$dto,$iarea)->row()
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $iperiode   = $this->mmaster->cekperiode();
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder'], $iperiode, $this->global['title']);
    }

    public function edit(){
        $inota    = $this->uri->segment(4);
        $ispb     = $this->uri->segment(5);
        $iarea    = $this->uri->segment(6);
        $dfrom    = $this->uri->segment(7);
        $dto      = $this->uri->segment(8);
        $query    = $this->db->query("select i_nota from tm_nota_item where i_nota='$inota' and i_area='$iarea' and n_deliver>0");
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'jmlitem'       => $query->num_rows(),
            'isi'           => $this->mmaster->bacanota($inota,$ispb,$iarea)->row(),
            'detail'        => $this->mmaster->bacadetailnota($inota,$iarea)->result()

        );
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

}
/* End of file Cform.php */
