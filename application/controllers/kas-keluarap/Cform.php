<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040309';

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
    

    public function index(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $iop = $this->uri->segment('4');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'], 
            'dfrom'     => $dfrom,
            'dto'       => $dto
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
		echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))));
        }
        echo json_encode($number);
    }

    public function bacajenisfaktur(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->bacajenisfaktur($cari);
        if($data->num_rows() > 0){
            foreach($data->result() as  $key){
                $filter[] = array(
                    'id'   => $key->i_jenis_faktur,  
                    'text' => $key->e_jenis_faktur_name
                );
            }         
        }else{
            $filter[] = array(
                'text' => "Data Faktur Kosong"
            );
        }
        echo json_encode($filter);
    }

    public function bacasupplier(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $ijenis = $this->input->get('ijenis');
        $data = $this->mmaster->bacasupplier($cari, $ijenis);
        if($data->num_rows() > 0){
            foreach($data->result() as  $key){
                $filter[] = array(
                    'id'   => $key->i_supplier,  
                    'text' => $key->e_supplier_name
                );
            }         
        }else{
            $filter[] = array(
                'text' => "Data Supplier Kosong"
            );
        }
        echo json_encode($filter);
    }

    public function getreferensi(){
        $isupplier = $this->input->post('isupplier');
        $ijenis = $this->input->post('ijenis');
        $query = $this->mmaster->getreferensi($isupplier, $ijenis);
        if($query->num_rows()>0) {
            $c  = "";
            $ppap = $query->result();
            foreach($ppap as $row) {
                $c.="<option value=".$row->id." >".$row->i_ppap."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Referensi -- ".$c."</option>";
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

    public function getitem(){
        header("Content-Type: application/json", true);
        $ireferensi  = $this->input->post('irefferensi', TRUE);
        $isupplier   = $this->input->post('isupplier', TRUE);
        $ijenis      = $this->input->post('ijenis', TRUE);

        $query = array(
            'head'   => $this->mmaster->getheadreff($ireferensi, $isupplier, $ijenis)->row(),
            'detail' => $this->mmaster->getitemreff($ireferensi, $isupplier, $ijenis)->result_array(),
        );
        echo json_encode($query);
    }

    public function kasbank(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->getkasbank($cari);
        foreach($data->result() as  $ikdoe){
                $filter[] = array(
                'id'   => $ikdoe->i_kode_kas,  
                'text' => $ikdoe->e_kas_name
            );
        }
        echo json_encode($filter);
    }

    public function getbank(){
        header("Content-Type: application/json", true);
        $ikodekas  = $this->input->post('ikodekas', TRUE);
        $query = array(
            'head'   => $this->mmaster->getbank($ikodekas)->row()
        );
        echo json_encode($query);
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
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "KBAP-".date('ym')."-123456"
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibagian          = $this->input->post('ibagian', TRUE);
        $ikasbankkeluarap = $this->input->post('ikasbankkeluarap', TRUE);
        $dkasbankkeluarap = $this->input->post("dkasbankkeluarap",TRUE);
        $year = date('Y');
        if($dkasbankkeluarap){
            $tmp   = explode('-', $dkasbankkeluarap);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datekeluar = $year.'-'.$month.'-'.$day;
        }
        $ipembayaran = $this->input->post('ireferensi', TRUE);
        $partner     = $this->input->post('isupplier', TRUE);
        $ikasbank    = $this->input->post('ikasbank', TRUE);
        $ijenis      = $this->input->post('ijenis', TRUE);
        $vsisa       = str_replace(',','',$this->input->post('vsisa', TRUE));
        $vbayar      = str_replace(',','',$this->input->post('vbayar', TRUE));
        $eremark     = $this->input->post('eremark', TRUE);
       
        $jml         = $this->input->post('jml', TRUE); 

        if($ikasbankkeluarap != ''  and $dkasbankkeluarap != ''){
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $i_voucher = $this->mmaster->running_voucher($year, $ikasbank, date('ym', strtotime($this->input->post('dkasbankkeluarap', TRUE))));
            $this->mmaster->insertheader($id, $ikasbankkeluarap, $ibagian, $datekeluar, $ipembayaran, $partner, $ikasbank, $vbayar, $vsisa, $eremark, $ijenis,$i_voucher);
            for($i=0;$i<=$jml;$i++){
                $idppap      = $this->input->post('idppap'.$i, TRUE);
                $idnota      = $this->input->post('idnota'.$i, TRUE);
                $vnota       = str_replace(',','',$this->input->post('v_nilai_reff'.$i, TRUE));
                $vbayar      = str_replace(',','',$this->input->post('v_nilai'.$i, TRUE));
                $edesc       = $this->input->post('edesc'.$i, TRUE);
                $this->mmaster->insertdetail($id, $idppap, $idnota, $vnota, $vbayar, $edesc); 
            }
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikasbankkeluarap);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ikasbankkeluarap. " No Voucher : ".$i_voucher,
                    'id'     => $id,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        } 
        $this->load->view('pesan2', $data);      
    }

    public function view(){

        $idkasbankkeluarap = $this->uri->segment(4);
        $ikasbankkeluarap  = $this->uri->segment(5);
        $idppap            = $this->uri->segment(6);
        $isupplier         = $this->uri->segment(7);
        $dfrom             = $this->uri->segment(8);
        $dto               = $this->uri->segment(9);
        $ijenisfaktur      = $this->uri->segment(10);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'data'          => $this->mmaster->baca_header($idkasbankkeluarap, $isupplier)->row(),
            'detail'        => $this->mmaster->baca_detail($idkasbankkeluarap, $idppap, $ijenisfaktur)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idkasbankkeluarap = $this->uri->segment(4);
        $ikasbankkeluarap  = $this->uri->segment(5);
        $idppap            = $this->uri->segment(6);
        $isupplier         = $this->uri->segment(7);
        $dfrom             = $this->uri->segment(8);
        $dto               = $this->uri->segment(9);
        $ijenisfaktur      = $this->uri->segment(10);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "KBAP-".date('ym')."-123456",
            'data'          => $this->mmaster->baca_header($idkasbankkeluarap, $isupplier)->row(),
            'detail'        => $this->mmaster->baca_detail($idkasbankkeluarap, $idppap, $ijenisfaktur)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id               = $this->input->post('id', TRUE);
        $ibagian          = $this->input->post('ibagian', TRUE);
        $ikasbankkeluarap = $this->input->post('ikasbankkeluarap', TRUE);
        $dkasbankkeluarap = $this->input->post("dkasbankkeluarap",TRUE);

        if($dkasbankkeluarap){
            $tmp   = explode('-', $dkasbankkeluarap);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datekeluar = $year.'-'.$month.'-'.$day;
        }

        $ipembayaran = $this->input->post('ireferensi', TRUE);
        $partner     = $this->input->post('isupplier', TRUE);
        $ikasbank    = $this->input->post('ikasbank', TRUE);
        $ijenis      = $this->input->post('ijenis', TRUE);
        $vsisa       = str_replace(',','',$this->input->post('vsisa', TRUE));
        $vbayar      = str_replace(',','',$this->input->post('vbayar', TRUE));
        $eremark     = $this->input->post('eremark', TRUE);
       
        $jml         = $this->input->post('jml', TRUE); 

        if($ikasbankkeluarap != ''  and $dkasbankkeluarap != ''){
            $this->db->trans_begin();
            $this->mmaster->updateheader($id, $ikasbankkeluarap, $ibagian, $datekeluar, $ipembayaran, $partner, $ikasbank, $vbayar, $vsisa, $eremark, $ijenis);
            $this->mmaster->deletedetail($id);

            for($i=0;$i<=$jml;$i++){
                $idppap      = $this->input->post('idppap'.$i, TRUE);
                $idnota      = $this->input->post('idnota'.$i, TRUE);
                $vnota       = str_replace(',','',$this->input->post('v_nilai_reff'.$i, TRUE));
                $vbayar      = str_replace(',','',$this->input->post('v_nilai'.$i, TRUE));
                $edesc       = $this->input->post('edesc'.$i, TRUE);
                $this->mmaster->insertdetail($id, $idppap, $idnota, $vnota, $vbayar, $edesc); 
            }
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikasbankkeluarap);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ikasbankkeluarap,
                    'id'     => $id,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }   
        $this->load->view('pesan2', $data);
    }


    public function changestatus(){
        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $ijenis  = $this->mmaster->getjenisfaktur($id);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus, $ijenis);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idkasbankkeluarap = $this->uri->segment(4);
        $ikasbankkeluarap  = $this->uri->segment(5);
        $idppap            = $this->uri->segment(6);
        $isupplier         = $this->uri->segment(7);
        $dfrom             = $this->uri->segment(8);
        $dto               = $this->uri->segment(9);
        $ijenisfaktur      = $this->uri->segment(10);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($idkasbankkeluarap, $isupplier)->row(),
            'detail'        => $this->mmaster->baca_detail($idkasbankkeluarap, $idppap, $ijenisfaktur)->result(),
        );


        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */