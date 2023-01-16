<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040314';

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

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function bacajenisdebet(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->bacajenisdebet($cari);
        if($data->num_rows() > 0){
            foreach($data->result() as  $key){
                $filter[] = array(
                    'id'   => $key->i_jenis_debet,  
                    'text' => $key->e_jenis_debet_name
                );
            }         
        }else{
            $filter[] = array(
                'text' => "Data Debet Kosong"
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
                    'id'   => $key->id_supplier.'|'.$key->i_supplier,  
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

    public function getdebet(){
        $supplier = explode('|', $this->input->post('isupplier'));
        $idsupplier = $supplier[0];
        $isupplier  = $supplier[1];
        $ijenis = $this->input->post('ijenis');
        $query = $this->mmaster->getdebet($idsupplier, $ijenis);
        if($query->num_rows()>0) {
            $c  = "";
            $ppap = $query->result();
            foreach($ppap as $row) {
                $c.="<option value=".$row->id." >".$row->i_document."</option>";
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

    public function getjenisfaktur(){
        $supplier = explode('|', $this->input->post('isupplier'));
        $idsupplier = $supplier[0];
        $isupplier  = $supplier[1];
        $ijenis = $this->input->post('ijenisdebet');
        $query = $this->mmaster->bacajenisfaktur($isupplier, $ijenis);
        if($query->num_rows()>0) {
            $c  = "";
            $ppap = $query->result();
            foreach($ppap as $row) {
                $c.="<option value=".$row->i_jenis_faktur." >".$row->e_jenis_faktur_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Jenis Faktur -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Faktur Kosong/option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getreferensi(){
        $supplier = explode('|', $this->input->post('isupplier'));
        $idsupplier = $supplier[0];
        $isupplier  = $supplier[1];
        $ijenis = $this->input->post('ijenisfaktur');
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

    public function getjumdebet(){
        header("Content-Type: application/json", true);
        $ireferensi  = $this->input->post('id', TRUE);
        $supplier    = explode('|', $this->input->post('isupplier', TRUE));
        $idsupplier  = $supplier[0];
        $isupplier   = $supplier[1];
        $ijenis      = $this->input->post('ijenis', TRUE);

        $query = array(
            'head'   => $this->mmaster->bacajumdebet($ireferensi, $isupplier, $ijenis)->row(),
        );
        echo json_encode($query);
    }

    public function getitem(){
        header("Content-Type: application/json", true);
        $ireferensi  = $this->input->post('irefferensi', TRUE);
        $supplier    = explode('|', $this->input->post('isupplier', TRUE));
        $idsupplier  = $supplier[0];
        $isupplier   = $supplier[1];
        $ijenis      = $this->input->post('ijenis', TRUE);

        $query = array(
            'head'   => $this->mmaster->getheadreff($ireferensi, $isupplier, $ijenis)->row(),
            'detail' => $this->mmaster->getitemreff($ireferensi, $isupplier, $ijenis)->result_array(),
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
            'number'        => "AL-".date('ym')."-000001"
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
        if($dkasbankkeluarap){
            $tmp   = explode('-', $dkasbankkeluarap);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datekeluar = $year.'-'.$month.'-'.$day;
        }
        $partner     = explode('|', $this->input->post('isupplier', TRUE));
        $idsupplier  = $partner[0];
        $isupplier   = $partner[1];
        $ijenisdebet = $this->input->post('ijenisdebet', TRUE);
        $idebet      = $this->input->post('idebet', TRUE);
        $ijenisfaktur= $this->input->post('ijenisfaktur', TRUE);
        $ireferensi  = $this->input->post('ireferensi', TRUE);
        $vsisa       = str_replace(',','',$this->input->post('vsisadebet', TRUE));
        $vbayar      = str_replace(',','',$this->input->post('vbayarfaktur', TRUE));
        $eremark     = $this->input->post('eremark', TRUE);
       
        $jml         = $this->input->post('jml', TRUE); 

        if($ikasbankkeluarap != ''  && $dkasbankkeluarap != '' && $ibagian != ''){
            $cekkode = $this->mmaster->cek_kode($ikasbankkeluarap, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                  'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $ikasbankkeluarap, $datekeluar, $ibagian, $idsupplier, $ijenisdebet, $idebet, $ijenisfaktur, $vsisa, $vbayar, $eremark);
                for($i=1;$i<=$jml;$i++){
                    $idppap      = $this->input->post('idppap'.$i, TRUE);
                    $idnota      = $this->input->post('idnota'.$i, TRUE);
                    $vnota       = str_replace(',','',$this->input->post('vnilai'.$i, TRUE));
                    $vbayar      = str_replace(',','',$this->input->post('vbayarnota'.$i, TRUE));
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
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        } 
        $this->load->view('pesan2', $data);      
    }

    public function view(){

        $id          = $this->uri->segment(4);
        $idsupplier  = $this->uri->segment(5);
        $ijenisdebet = $this->uri->segment(6);
        $dfrom       = $this->uri->segment(7);
        $dto         = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $id, 
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id, $idsupplier, $ijenisdebet)->row(),
            'detail'        => $this->mmaster->baca_detail($id, $idsupplier)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id          = $this->uri->segment(4);
        $idsupplier  = $this->uri->segment(5);
        $ijenisdebet = $this->uri->segment(6);
        $dfrom       = $this->uri->segment(7);
        $dto         = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $id,
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "AL-".date('ym')."-000001",
            'data'          => $this->mmaster->baca_header($id, $idsupplier, $ijenisdebet)->row(),
            'detail'        => $this->mmaster->baca_detail($id, $idsupplier)->result(),
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
        $ikodeold         = $this->input->post('ikodeold', TRUE);
        $ibagian          = $this->input->post('ibagian', TRUE);
        $ikasbankkeluarap = $this->input->post('ialokasidebet', TRUE);
        $dkasbankkeluarap = $this->input->post("dalokasidebet",TRUE);
        if($dkasbankkeluarap){
            $tmp   = explode('-', $dkasbankkeluarap);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datekeluar = $year.'-'.$month.'-'.$day;
        }
        $partner     = explode('|', $this->input->post('isupplier', TRUE));
        $idsupplier  = $partner[0];
        $isupplier   = $partner[1];
        $ijenisdebet = $this->input->post('ijenisdebet', TRUE);
        $idebet      = $this->input->post('idebet', TRUE);
        $ijenisfaktur= $this->input->post('ijenisfaktur', TRUE);
        $ireferensi  = $this->input->post('ireferensi', TRUE);
        $vsisa       = str_replace(',','',$this->input->post('vsisadebet', TRUE));
        $vbayar      = str_replace(',','',$this->input->post('vbayarnow', TRUE));
        $eremark     = $this->input->post('eremark', TRUE);
       
        $jml         = $this->input->post('jml', TRUE); 
        // var_dump($jml);
        // die();
        if($ikasbankkeluarap != ''  && $dkasbankkeluarap != '' && $ibagian != ''){
            $cekkode = $this->mmaster->cek_kodeedit($ikasbankkeluarap, $ikodeold, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                  'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $ikasbankkeluarap, $datekeluar, $ibagian, $idsupplier, $ijenisdebet, $idebet, $ijenisfaktur, $vsisa, $vbayar, $eremark);
                $this->mmaster->deletedetail($id);
                for($i=1;$i<=$jml;$i++){
                    $idppap = $this->input->post('idppap'.$i, TRUE);
                    $idnota = $this->input->post('idnota'.$i, TRUE);
                    $vnota  = str_replace(',','',$this->input->post('vnilai'.$i, TRUE));
                    $vbayar = str_replace(',','',$this->input->post('vbayarnota'.$i, TRUE));
                    $edesc  = $this->input->post('edesc'.$i, TRUE);
                    $this->mmaster->insertdetail($id, $idppap, $idnota, $vnota, $vbayar, $edesc); 
                }
                if($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Ubah Data '.$this->global['title'].' Kode : '.$ikasbankkeluarap);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $ikasbankkeluarap,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        } 
        $this->load->view('pesan2', $data);      
    }

    public function changestatus(){

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
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

        $id          = $this->uri->segment(4);
        $idsupplier  = $this->uri->segment(5);
        $ijenisdebet = $this->uri->segment(6);
        $dfrom       = $this->uri->segment(7);
        $dto         = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approved ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $id, 
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id, $idsupplier, $ijenisdebet)->row(),
            'detail'        => $this->mmaster->baca_detail($id, $idsupplier)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }
}
/* End of file Cform.php */