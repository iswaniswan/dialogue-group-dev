<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010504';

    public function __construct(){

        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        $this->load->library('pagination');

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $status = $this->input->post('fstatus');
        if ($status=='') {
            $status = $this->uri->segment(4);
        }
        $dfrom = $this->input->post('dfrom');
        if ($dfrom=='') {
            $dfrom = $this->uri->segment(5);
        }
        $dto = $this->input->post('dto');
        if ($dto=='') {
            $dto = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'status'    => $status,
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $status = $this->input->post('fstatus');
        if ($status=='') {
            $status = $this->uri->segment(4);
        }
        $dfrom = $this->input->post('dfrom');
        if ($dfrom=='') {
            $dfrom = $this->uri->segment(5);
        }
        $dto = $this->input->post('dto');
        if ($dto=='') {
            $dto = $this->uri->segment(6);
        }

        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $status, $dfrom, $dto);
    }


    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodekelompok'  => $this->mmaster->get_kodekelompok()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function jenis(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->getjenis($cari,$this->input->get('ikodekelompok'));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->name,
            );
        }          
        echo json_encode($filter);
    }

    public function product(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->getproduct($cari,$this->input->get('ikodekelompok'),$this->input->get('ikodejenis'));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->id.' - '.$key->name,
            );
        }          
        echo json_encode($filter);
    }

    public function proses(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikodekelompok = $this->input->post('ikodekelompok',true);
        $ikodejenis    = $this->input->post('ikodejenis',true);
        $iproduct      = $this->input->post('i_product',true);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'ikodekelompok' => $ikodekelompok,
            'ikodejenis'    => $ikodejenis,
            'iproduct'      => $iproduct,
            'kelompok'      => $this->mmaster->cek_kelompok($ikodekelompok)->row(),
            'jenis'         => $this->mmaster->cek_jenis($ikodejenis)->row(),
            'product'       => $this->mmaster->cek_product($iproduct)->row(),
            'proses'        => $this->mmaster->get_hargas($ikodekelompok, $ikodejenis, $iproduct),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);  
    }

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $jml             = $this->input->post('jml', TRUE);
        for($i=1;$i<=$jml;$i++){ 
            if($this->input->post('cek'.$i)=='on'){
                $kodebrg        = $this->input->post('kodebrg'.$i, TRUE); 
                $icolor         = $this->input->post('icolor'.$i, TRUE);
                $harga          = $this->input->post('harga'.$i, TRUE);
                $harga          = str_replace(',','',$harga);
                $dberlaku       = $this->input->post('dberlaku'.$i, TRUE);
                if($dberlaku){
                    $tmp   = explode('-', $dberlaku);
                    $day   = $tmp[0];
                    $month = $tmp[1];
                    $year  = $tmp[2];
                    $yearmonth = $year.$month;
                    $dateberlaku = $year.'-'.$month.'-'.$day;
                }

                if ($dberlaku != "") {
                    $this->mmaster->insert($kodebrg, $icolor, $harga, $dateberlaku); 
                }              
            }
        }          
        $data = array(
            'sukses' => true,
            'kode'   => "Tambah Harga Berhasil"
        );

        $this->load->view('pesan', $data);     
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_price         = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($i_price)->row(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_price        = $this->input->post('i_price', TRUE);
        $kodebrg        = $this->input->post('kodebrg', TRUE);
        $harga          = removetext($this->input->post('harga', TRUE));
        $dberlaku       = $this->input->post('dberlaku', TRUE);
        $aktif          = $this->input->post('aktif', TRUE);
        if($dberlaku){
            $tmp   = explode('-', $dberlaku);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dateberlaku = $year.'-'.$month.'-'.$day;
        }

        if ($harga != ''){
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$kodebrg);
            $this->mmaster->update($i_price, $kodebrg, $harga, $dateberlaku, $aktif);
            $data = array(
                'sukses'    => true,
                'kode'      => $kodebrg
            );
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function view(){
        $i_price         = $this->uri->segment('4');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($i_price)->row(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function status(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }
}

/* End of file Cform.php */
