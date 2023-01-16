<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040308';

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
        echo $this->mmaster->data($this->i_menu, $username, $idcompany, $idepartemen, $ilevel);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),  
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function kasbank(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_kas_bank");
            $data = $this->db->get();
            foreach($data->result() as  $ikdoe){
                    $filter[] = array(
                    'id'   => $ikdoe->i_kode_kas,  
                    'text' => $ikdoe->e_nama_kas
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    // function refferensi(){
    //     $filter = [];
    //     if($this->input->get('q') != '') {
    //         $filter = [];
    //         $cari = strtoupper($this->input->get('q'));
    //         $this->db->select("*");
    //         $this->db->from("tm_permintaan_pembayaranap");
    //         $data = $this->db->get();
    //         foreach($data->result() as  $ikdoe){
    //                 $filter[] = array(
    //                 'id'   => $ikdoe->i_pembayaran,
    //                 'text' => $ikdoe->i_pembayaran
    //             );
    //         }
    //         echo json_encode($filter);
    //     } else {
    //         echo json_encode($filter);
    //     }
    // }

    function bank(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("* from tr_bank where e_bank_name like '%$cari%' order by e_bank_name");
            $data = $this->db->get();
            foreach($data->result() as  $ikdoe){
                    $filter[] = array(
                    'id'   => $ikdoe->i_bank,  
                    'text' => $ikdoe->e_bank_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

     function getrefferensi(){
        header("Content-Type: application/json", true);
        $irefferensi  = $this->input->post('irefferensi');
        $jeniskeluar  = $this->input->post('jeniskeluar');

        if($jeniskeluar=='kasbon'){
            $dataa = array(
                // 'head'       => $this->mmaster->getheadrefferensikasbon($irefferensi)->row(),
                'dataitem'   => $this->mmaster->getrefferensikasbon($irefferensi)->result_array(),
            );
        }else{
            $dataa = array(
                // 'head'       => $this->mmaster->getheadrefferensikaskeluar($irefferensi)->row(),
                'dataitem'   => $this->mmaster->getrefferensikaskeluar($irefferensi)->result_array(),
            );
        }

        echo json_encode($dataa);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibagian   = $this->input->post("ibagian",true);
        $dkasbankkeluar = $this->input->post("dkasbankkeluar",true);
        if($dkasbankkeluar){
            $tmp   = explode('-', $dkasbankkeluar);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datekeluar = $year.'-'.$month.'-'.$day;
        }

        // $ipembayaran = $this->input->post('irefferensi', TRUE);
        $irefferensi    = $this->input->post('irefferensi', TRUE);
        // $partner     = $this->input->post('partner', TRUE);
        $ijeniskeluar   = $this->input->post('jeniskeluar',TRUE);
        $ikasbank       = $this->input->post('ikasbank', TRUE);
        $ibank          = $this->input->post('ibank', TRUE);
        // $vsisa       = $this->input->post('vsisa', TRUE);
        // $vbayar      = $this->input->post('vbayar', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
       
        $jml         = $this->input->post('jml', TRUE); 
       
        $this->db->trans_begin();
        $ikasbankkeluarnap   = $this->mmaster->runningnumber($yearmonth, $ibagian);
        //$this->mmaster->insertheader($ikasbankkeluar, $ibagian, $datekeluar, $ipembayaran, $ibank, $partner, $ikasbank, $vbayar, $eremark);
        
        // $vsisabaru = $vsisa - $vbayar;
        // $this->mmaster->updatesisa($ipembayaran, $vsisabaru);

        for($i=1;$i<=$jml;$i++){
                // $inota      = $this->input->post('inota'.$i, TRUE);
                // $dnota      = $this->input->post('dnota'.$i, TRUE);
                // $vtotal     = str_replace(',','',$this->input->post('vnilai'.$i,TRUE));
                // $eremark    = $this->input->post('edesc'.$i, TRUE);
                $drefferensi    = $this->input->post('drefferensi'.$i, TRUE);
                $vnilai         = str_replace(',','',$this->input->post('vnilai'.$i,TRUE));
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $nitemno        = $i;

                //$this->mmaster->insertdetail($ikasbankkeluar, $partner, $inota, $dnota, $vtotal, $eremark, $nitemno); 
                $this->mmaster->insertheader($ikasbankkeluarnap, $ibagian, $datekeluar, $ijeniskeluar, $irefferensi, $ikasbank, $ibank, $eremark, $drefferensi, $vnilai, $edesc);
        }
            
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikasbankkeluarnap);
            $data = array(
                'sukses' => true,
                'kode'      => $ikasbankkeluarnap,
            );
        }
    $this->load->view('pesan', $data);      
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $ikasbankkeluar = $this->uri->segment('4');
        $ijeniskeluar   = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            //'customer'      => $this->mmaster->bacacustomer(),
            'kasbank'       => $this->mmaster->bacakasbank(),
            'bank'          => $this->mmaster->bacabank(),
            'refferensi'    => $this->mmaster->getrefferensibayar($ikasbankkeluar,$ijeniskeluar)->result(),
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'data'          => $this->mmaster->baca_header($ikasbankkeluar)->row(),
            'datadetail'    => $this->mmaster->baca_detail($ikasbankkeluar)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ikasbankkeluar     = $this->input->post("ikasbankkeluar",true);
        $ibagian            = $this->input->post("ibagian",true);
        $dkasbankkeluar     = $this->input->post("dkasbankkeluar",true);
        if($dkasbankkeluar){
            $tmp   = explode('-', $dkasbankkeluar);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datekeluar = $year.'-'.$month.'-'.$day;
        }

        $ijeniskeluar       = $this->input->post("jeniskeluar",true);
        $irefferensi        = $this->input->post('irefferensi', TRUE);

        $ikasbank           = $this->input->post('ikasbank', TRUE);
        $ibank              = $this->input->post('ibank', TRUE);
        // $vsisa              = $this->input->post('vsisa', TRUE);
        // $vbayar             = $this->input->post('vbayar', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
       
        $jml          = $this->input->post('jml', TRUE); 

        $this->db->trans_begin();
        // $this->mmaster->updateheader($ikasbankkeluar, $ibagian, $dkasbankkeluar, $ikasbank, $ibank, $irefferensi, $vsisa, $vbayar, $eremark);

        // $vsisabaru = $vsisa - $vbayar;
        // $this->mmaster->updatesisa($irefferensi, $vsisabaru);

        // $this->mmaster->deletedetail($ikasbankkeluar);

        for($i=1;$i<=$jml;$i++){
                // $inota      = $this->input->post('inota'.$i, TRUE);
                $drefferensi      = $this->input->post('drefferensi'.$i, TRUE);
                $vnilai     = str_replace(',','',$this->input->post('vnilai'.$i,TRUE));
                $edesc      = $this->input->post('edesc'.$i, TRUE);
                $nitemno    = $i;

                // $this->mmaster->insertdetail($ikasbankkeluar, $partner, $inota, $dnota, $vtotal, $eremark, $nitemno); 
                $this->mmaster->updateheader($ikasbankkeluar, $ibagian, $datekeluar, $ijeniskeluar, $irefferensi, $ikasbank, $ibank, $edesc, $eremark, $drefferensi, $vnilai);
        }

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data '.$this->global['title'].' No Kas/Bank Keluar : '.$ikasbankkeluar);
            $data = array(
                'sukses' => true,
                'kode'      => $ikasbankkeluar,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $ikasbankkeluar = $this->input->post('ikasbankkeluar');
        $this->mmaster->sendd($ikasbankkeluar);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $ikasbankkeluar = $this->input->post('ikasbankkeluar');
        $this->mmaster->cancel_approve($ikasbankkeluar);
    }

    public function view(){

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $ikasbankkeluarnonap = $this->uri->segment('4');
        $ijeniskeluar   = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kasbank'       => $this->mmaster->bacakasbank(),
            'bank'          => $this->mmaster->bacabank(),
            'refferensi'    => $this->mmaster->getrefferensibayar($ikasbankkeluarnonap,$ijeniskeluar)->result(),
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'data'          => $this->mmaster->baca_header($ikasbankkeluarnonap)->row(),
            'datadetail'    => $this->mmaster->baca_detail($ikasbankkeluarnonap)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approve(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $ikasbankkeluar = $this->uri->segment('4');
        $ijeniskeluar   = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            //'customer'      => $this->mmaster->bacacustomer(),
            'kasbank'       => $this->mmaster->bacakasbank(),
            'bank'          => $this->mmaster->bacabank(),
            'refferensi'    => $this->mmaster->getrefferensibayar($ikasbankkeluar,$ijeniskeluar)->result(),
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'data'          => $this->mmaster->baca_header($ikasbankkeluar)->row(),
            'datadetail'    => $this->mmaster->baca_detail($ikasbankkeluar)->result(),
        );
        $this->Logger->write('Membuka Menu approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ikasbankkeluar = $this->input->post('ikasbankkeluar', true);
       
        $this->db->trans_begin();
        $this->mmaster->approve($ikasbankkeluar);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $ikasbankkeluar,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $ikasbankkeluar = $this->input->post('ikasbankkeluar');
        $this->mmaster->change_approve($ikasbankkeluar);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $ikasbankkeluar = $this->input->post('ikasbankkeluar');
        $this->mmaster->reject_approve($ikasbankkeluar);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ikasbankkeluar = $this->input->post('ikasbankkeluar', true);

        $this->db->trans_begin();

        $this->mmaster->cancelpermintaanpembayaran($ikasbankkeluar);
        $data = $this->mmaster->cancel($ikasbankkeluar);
        
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Kas/Bank Keluar' . $ikasbankkeluar);
            echo json_encode($data);
        }
    }

    public function getjenis(){
        $jeniskeluar = $this->input->post('jeniskeluar');
        if($jeniskeluar == 'kasbon'){
            $query = $this->mmaster->getjeniskasbon();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_kas_bon." >".$row->i_kas_bon."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih -- ".$c."</option>";
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
        }else {
            $query = $this->mmaster->getjeniskaskeluar();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_kas_masuk." >".$row->i_kas_masuk."</option>";
                    
                }
                $kop  = "<option value=\"\"> -- Pilih -- ".$c."</option>";
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
        
    }
}
/* End of file Cform.php */