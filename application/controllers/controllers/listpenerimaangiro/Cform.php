<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2060501';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'] . '/mmaster');
    }

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iarea'     => $this->mmaster->cekarea(),
            'customer'  => $this->db->query('select * from tr_customer order by i_customer'),

        );
        $this->Logger->write('Membuka Menu ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vmainform', $data);

    }

    public function view()
    {
        $icustomer  = $this->input->post('icustomer', true);
        $dfrom      = $this->input->post('dfrom', true);
        $dto        = $this->input->post('dto', true);
        
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'icustomer' => $icustomer,
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );
        $this->Logger->write('Membuka Menu ' . $this->global['title']) . ' Tanggal : ' . $dfrom . ' S/d : ' . $dto;
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function data()
    {
        
        $icustomer  = $this->input->post('icustomer');
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($icustomer==''){
            $icustomer=$this->uri->segment(6);
        }

        echo $this->mmaster->bacaperiode($icustomer,$dfrom,$dto,$this->global['folder'],$this->i_menu);
    }

}
/* End of file Cform.php */