<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050502';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        /*require('php/fungsi.php');*/
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
             'title_list'    => 'List '.$this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    public function gudang(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_master_gudang');
            $this->db->where('i_kode_master','GD10004');
            // $this->db->like("UPPER(i_kode_master)", $cari);
            // $this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $itype) {
                $filter[] = array(
                    'id' => $itype->i_kode_master,
                    'text' => $itype->e_nama_master,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getkategori(){
        $ibagian = $this->input->post('ibagian');
        $query = $this->mmaster->getkategori($ibagian);
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

    public function getjenis(){
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

    public function cetak(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id_company = $this->session->userdata('id_company');
        $ibagian    = $this->uri->segment(4);
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
            'dfrom'         => $this->uri->segment(7),
            'dto'           => $this->uri->segment(8),
            'kategori'      => $this->mmaster->kategoribarang($ikelompok, $id_company)->row(),
            'jenis'         => $this->mmaster->jenisbarang($jnsbarang, $id_company)->row(),
            'data2'         => $this->mmaster->cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }
}
/* End of file Cform.php */