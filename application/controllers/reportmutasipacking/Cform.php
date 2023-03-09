<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    // public $i_menu = '210150501';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        /* $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        } */

        $data = check_role_folder($this->uri->segment(1), 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_menu = $data[0]['i_menu'];
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }   

    public function index()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function gudang(){
        $filter = [];
        $search = str_replace("'", "", $this->input->get('search'));
        $data = $this->mmaster->getbagian($search);
        foreach ($data->result() as $ibagian) {
            $filter[] = array(
                'id'    => $ibagian->i_bagian,
                'text'  => $ibagian->e_bagian_name,
            );
        }
        echo json_encode($filter);
    }

    public function getkategori()
    {
        $filter = [];
        $filter[] = array(
            'id'    => 'null',
            'text'  => 'SEMUA KATEGORI',
        );
        $search = str_replace("'","",$this->input->get('search'));
        $data = $this->mmaster->getkategori($search);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->i_kode_kelompok,
                'text'  => $row->e_nama_kelompok,
            );
        }
        echo json_encode($filter);
    }

    public function getjenis()
    {
        $filter = [];
        $filter[] = array(
            'id'    => 'null',
            'text'  => 'SEMUA SUB KATEGORI',
        );
        $search = str_replace("'", "", $this->input->get('search'));
        $kodekelompok = $this->input->get('ikodekelompok');
        $data = $this->mmaster->getjenis($kodekelompok,$search);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->i_type_code,
                'text'  => $row->e_type_name,
            );
        }
        echo json_encode($filter);
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id_company = $this->session->userdata('id_company');
        $ibagian    = $this->input->post("ibagian",true);
        $jnsbarang  = $this->input->post("jnsbarang",true);
        $ikelompok  = $this->input->post("ikelompok",true);

        $awal = DateTime::createFromFormat('d-m-Y', $this->input->post("dfrom",true));
        $akhir   = DateTime::createFromFormat('d-m-Y', $this->input->post("dto",true));

        $dfrom = $awal->format('Y-m-d');
        $dto = $akhir->format('Y-m-d');
        $d_jangka_awal = $awal->format('Y-m-01');
        $i_periode = $awal->format('Ym');
        $d_jangka_akhir = $awal->modify('-1 day')->format('Y-m-d');

        if ($d_jangka_awal == $dfrom) {
            $d_jangka_awal = '9999-01-01';
            $d_jangka_akhir = '9999-01-31';
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bacabagian($ibagian)->row(),
            'dfrom'         => $this->input->post("dfrom",true),
            'dto'           => $this->input->post("dto",true),
            'kategori'      => $this->mmaster->kategoribarang($ikelompok, $id_company)->row(),
            'jenis'         => $this->mmaster->jenisbarang($jnsbarang, $id_company)->row(),
            'data2'         => $this->mmaster->get_data($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function cetak(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id_company = $this->session->userdata('id_company');
        $ibagian    = $this->uri->segment(4);
        $bagian = ($ibagian=='null') ? '' : $ibagian ;
        $jnsbarang  = $this->uri->segment(5);
        $ikelompok  = $this->uri->segment(6);

        $awal = DateTime::createFromFormat('d-m-Y', $this->uri->segment(7));
        $akhir   = DateTime::createFromFormat('d-m-Y', $this->uri->segment(8));

        $dfrom = $awal->format('Y-m-d');
        $dto = $akhir->format('Y-m-d');
        $d_jangka_awal = $awal->format('Y-m-01');
        $i_periode = $awal->format('Ym');
        $d_jangka_akhir = $awal->modify('-1 day')->format('Y-m-d');

        if ($d_jangka_awal == $dfrom) {
            $d_jangka_awal = '9999-01-01';
            $d_jangka_akhir = '9999-01-31';
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bacabagian($ibagian)->row(),
            'i_bagian'      => $bagian,
            'i_periode'     => $i_periode,
            'd_jangka_awal' => $d_jangka_awal,
            'd_jangka_akhir'=> $d_jangka_akhir,
            'dfrom'         => $this->uri->segment(7),
            'dto'           => $this->uri->segment(8),
            'ikelompok'     => $ikelompok,
            'jnsbarang'     => $jnsbarang,
            'kategori'      => $this->mmaster->kategoribarang($ikelompok, $id_company)->row(),
            'jenis'         => $this->mmaster->jenisbarang($jnsbarang, $id_company)->row(),
            // 'data2'         => $this->mmaster->cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $bagian, $ikelompok, $jnsbarang)->result(),
            'data'         => $this->mmaster->get_data($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $bagian, $ikelompok, $jnsbarang),
        );
        $this->Logger->write('Membuka Menu Cetak '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }

    public function get_data_20220618()
    {
        header("Content-Type: application/json", true);
        $bagian = $this->input->post('i_bagian');
        $i_periode = $this->input->post('i_periode');
        $d_jangka_awal = $this->input->post('d_jangka_awal');
        $d_jangka_akhir = $this->input->post('d_jangka_akhir');
        $dfrom = $this->input->post('d_from');
        $dto = $this->input->post('d_to');
        $ikelompok = $this->input->post('i_kelompok');
        $jnsbarang = $this->input->post('jenis_barang');
        $data = $this->mmaster->get_data($this->id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, formatYmd($dfrom), formatYmd($dto), $bagian, $ikelompok, $jnsbarang)->result();
        echo json_encode($data);
    }

    public function get_data()
    {
        header("Content-Type: application/json", true);
        $bagian = $this->input->post('i_bagian');
        $i_periode = $this->input->post('i_periode');
        $d_jangka_awal = $this->input->post('d_jangka_awal');
        $d_jangka_akhir = $this->input->post('d_jangka_akhir');
        $dfrom = $this->input->post('d_from');
        $dto = $this->input->post('d_to');
        $ikelompok = $this->input->post('i_kelompok');
        $jnsbarang = $this->input->post('jenis_barang');
        $data = $this->mmaster->get_data($this->id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, formatYmd($dfrom), formatYmd($dto), $bagian, $ikelompok, $jnsbarang)->result();
        echo json_encode($data);
    }
}
/* End of file Cform.php */