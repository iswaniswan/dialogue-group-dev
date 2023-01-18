<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    // public $i_menu = '210150501';
    public $i_menu = '2050111';

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
        /* $filter[] = array(
            'id'    => 'null',
            'text'  => 'SEMUA UNIT',
        ); */
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

    public function getkategori_old(){
        $ibagian = $this->input->post('ibagian');
        $query = $this->mmaster->getkategori();
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->e_nama_kelompok."</option>";
            }
            $kop  = "<option value=\"KTB\">  Semua Kategori Barang  ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
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

    public function getjenis_old(){
        $ikelompok = $this->input->post('ikelompok');
        $ibagian = $this->input->post('ibagian');
        $query = $this->mmaster->getjenis($ikelompok,$ibagian);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_type_code." >".$row->e_type_name."</option>";
            }
            $kop  = "<option value=\"JNB\">  Semua Jenis Barang  ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
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
            'data2'         => $this->mmaster->cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
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
            'dfrom'         => $this->uri->segment(7),
            'dto'           => $this->uri->segment(8),
            'kategori'      => $this->mmaster->kategoribarang($ikelompok, $id_company)->row(),
            'jenis'         => $this->mmaster->jenisbarang($jnsbarang, $id_company)->row(),
            'data'         => $this->mmaster->cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $bagian, $ikelompok, $jnsbarang),
            // 'data2'         => $this->mmaster->cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $bagian, $ikelompok, $jnsbarang)->result(),
        );
        $this->Logger->write('Membuka Menu Cetak '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }
}
/* End of file Cform.php */