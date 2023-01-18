<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '107011303';

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
        $username = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'supplier' => $this->db->query('select * from tr_supplier order by i_supplier'),

        );
        $this->Logger->write('Membuka Menu ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vmainform', $data);

    }

    public function view()
    {
        $isupplier = $this->input->post('isupplier', true);
        $dfrom = $this->input->post('dfrom', true);
        $dto = $this->input->post('dto', true);
        $data = array(
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'isupplier' => $isupplier,
            'dfrom' => $dfrom,
            'dto' => $dto
        );
        $this->Logger->write('Membuka Menu ' . $this->global['title']) . ' Tanggal : ' . $dfrom . ' S/d : ' . $dto;
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function data()
    {
        $dfrom = $this->uri->segment(4);
        $dto = $this->uri->segment(5);
        $isupplier = $this->uri->segment(6);
        echo $this->mmaster->bacaperiode($isupplier, $dfrom, $dto, $this->global['folder']);
    }

}
/* End of file Cform.php */