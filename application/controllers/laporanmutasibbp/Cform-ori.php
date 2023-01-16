<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050212';

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
        $gudang      = $this->session->userdata('gudang');
        $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_master_gudang');
            $this->db->where('i_kode_master',$gudang);
            $data = $this->db->get();
            foreach ($data->result() as $itype) {
                $filter[] = array(
                    'id' => $itype->i_kode_master,
                    'text' => $itype->e_nama_master,

                );
            }

            echo json_encode($filter);
        
    }

    public function getkategori(){
        $kelompok_barang      = $this->session->userdata('kelompok_barang');
        $ikodemaster = $this->input->post('ikodemaster');
        $query = $this->mmaster->getkategori($ikodemaster, $kelompok_barang);
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

        $from = explode("-", $dfrom);
        $tgl= $from[0];
        $bln= $from[1];
        $thn= $from[2];

        $to = explode("-", $dto);
        $tgl2= $to[0];
        $bln2= $to[1];
        $thn2= $to[2];
        //$datenow = "01-".$pbulan."-".$ptahun;
        $d = new DateTime($dto);
        $one_month = new DateInterval('P1M');
        $one_month_ago = new DateTime($dfrom);
        $one_month_ago->sub($one_month);

        // $awal  = $d->format('01-m-Y');
        // $akhir = $d->format('d-m-Y');
        $blalu = $one_month_ago->format('m');
        $tlalu = $one_month_ago->format('Y');
        $bnow  = $d->format('m');
        $tnow  = $d->format('Y');


        $one_day = new DateInterval('P1D');
        $one_day_ago = new DateTime($dfrom);
        $one_day_ago->sub($one_day);

        $djangka = new DateTime($dfrom);
        $dawal  = $djangka->format('01-m-Y');
        $dlalu_j = $one_day_ago->format('d');
        $blalu_j = $one_day_ago->format('m');
        $tlalu_j = $one_day_ago->format('Y');

        $dakhir = $dlalu_j."-".$blalu_j."-".$tlalu_j;
        $ikodemaster2;
        if ($dawal == $dfrom) {
            $ikodemaster2 = "xx";
        } else {
            $ikodemaster2 = $ikodemaster;
        }


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
            'data2'         => $this->mmaster->cek_datadet($blalu,$tlalu,$dfrom,$dto,$bnow,$tnow,$ikodemaster, $dawal, $dakhir, $ikodemaster2, $ikelompok, $jnsbarang)->result(),
        );
        
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */
