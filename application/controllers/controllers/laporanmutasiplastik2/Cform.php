<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2060101';

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
    

    public function index()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function gudang(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_master_gudang');
            $this->db->where('i_kode_master','GD10001');
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
        $ikodemaster = $this->input->post('ikodemaster');
        $query = $this->mmaster->getkategori($ikodemaster);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->e_nama."</option>";
            }
            $kop  = "<option value=".$row->i_kode_kelompok." >".$row->e_nama."</option>";
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
        $query = $this->mmaster->getjenis($ikelompok);
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

        $dfrom          = $this->input->post("dfrom",true);
        $dto            = $this->input->post("dto",true);
        $ikodemaster    = $this->input->post("ikodemaster",true);
        $jnsbarang      = $this->input->post("jnsbarang",true);
        $ikelompok      = $this->input->post("ikelompok",true);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'gudang'        => $this->mmaster->bacagudang($ikodemaster)->row(),
            'kodemaster'    => $ikodemaster,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'kategori'      => $this->mmaster->kategoribarang($ikelompok)->row(),
            'jenis'         => $this->mmaster->jenisbarang($jnsbarang)->row(),
            'data2'         => $this->mmaster->cek_datadet($dfrom, $dto, $ikelompok, $jnsbarang, $ikodemaster)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */
