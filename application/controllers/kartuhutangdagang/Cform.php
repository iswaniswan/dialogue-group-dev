<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '107011004';

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
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'bulan' => date('m'),
            'tahun' => date('Y'),

        );
        $this->Logger->write('Membuka Menu ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformawal', $data);

    }

    public function proses()
    {
        $bulan = $this->input->post('iperiodebl', true);
        $tahun = $this->input->post('iperiodeth', true);
        $periode = $tahun . $bulan;
        $data = array(
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'periode' => $periode,
            'isi' => $this->mmaster->bacaperiode($periode),
        );
        $this->Logger->write('Membuka Menu ' . $this->global['title']) . ' Periode : ' . $periode;
        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }
}
/* End of file Cform.php */