<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010701';

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
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = date('d-m-Y');
            }
        }

        $idtypemakloon = $this->input->post('idtypemakloon', TRUE);
        if($idtypemakloon == ''){
            $idtypemakloon =  $this->uri->segment(5);
            if($idtypemakloon == ''){
                $idtypemakloon = '0';
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'dfrom'         => $dfrom,
            'idtypemakloon' => $idtypemakloon,
            'etypemakloon'  => $this->mmaster->getnamemakloon($idtypemakloon)
        );


        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom         = $this->uri->segment(4);
        $idtypemakloon = $this->uri->segment(5);
		echo $this->mmaster->data($dfrom, $idtypemakloon, $this->global['folder'], $this->i_menu);
    }

    public function getsupplieradd()
    {
        $filter = [];
        $data   = $this->mmaster->getsupplieradd(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->i_supplier,
                'text'  => $row->i_supplier.' - '.$row->e_supplier_name,
            );
        }
        echo json_encode($filter);
    }

    public function gettypemakloon()
    {
        $isupplier = $this->input->post('id');
        $query = $this->mmaster->gettypemakloon($isupplier);
        if($query->num_rows()>0) {
            $c  = "";
            $makloon = $query->result();
            foreach($makloon as $row) {
                $c.="<option value=".$row->id." >".$row->e_type_makloon_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Jenis Makloon -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Makloon Tidak Ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getmakloonlist()
    {
        $filter = [];
        $data   = $this->mmaster->getmakloonlist(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id,
                'text'  => $row->e_type_makloon_name,
            );
        }
        echo json_encode($filter);
    }

    public function getidsupp(){
        header("Content-Type: application/json", true);
        $id = $this->input->post('id');
        $this->db->select("id");
        $this->db->from("tr_supplier");
        $this->db->where("i_supplier", $id);
        $this->db->where("id_company", $this->session->userdata('id_company'));
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function getkelompokbarang(){
        $isupplier    = $this->input->post('id');
        $query = $this->mmaster->getkelompokbarang($isupplier);
        if($query->num_rows()>0) {
            $c  = "";
            $kelompok = $query->result();
            foreach($kelompok as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->i_kode_kelompok." - ".$row->e_nama_kelompok." (".$row->e_nama_group_barang.") "."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Kelompok Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Kelompok Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function satuan(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_satuan a");
            $data = $this->db->get();
            foreach ($data->result() as $icolor) {
                $filter[] = array(
                    'id' => $icolor->i_satuan,
                    'text' => $icolor->e_satuan,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getkel(){
        $igroupbrg = $this->input->post('igroupbrg');
        $query = $this->mmaster->getkel($igroupbrg);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->i_kode_kelompok." - ".$row->e_nama."</option>";
            }
            $kop  = "<option value=\"AKB\"> -- Semua Kategori Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getid(){
        $ikode2 = $this->input->post('ikodeunit');
        $query = $this->mmaster->get_kodeunit($ikode2);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->id." >".$row->id."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Jenis Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
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
            'idtypemakloon' => $this->uri->segment(5)
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }


    public function proses(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier     = $this->input->post('idsupp',true);
        $ikodekelompok = $this->input->post('ikodekelompok',true);
        $ikodejenis    = $this->input->post('ikodejenis',true);
        $igroupbrg     = $this->input->post('igroupbrg',true);
        $iproduct      = $this->input->post('iproduct',true);
        $itypemakloon  = $this->input->post('itypemakloon', true);
        $etype         = $this->input->post('etype',true);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'datasup'       => $this->mmaster->cek_sup($isupplier, $itypemakloon)->row(),
            'groupbarang'   => $this->mmaster->cek_group($igroupbrg)->row(),
            'proses'        => $this->mmaster->get_hargas($ikodekelompok, $ikodejenis, $isupplier, $iproduct, $itypemakloon),
            'satuan'        => $this->mmaster->get_satuan(),
            'supplier'      => $this->mmaster->get_supplier($itypemakloon),
            'groupbarang'   => $this->mmaster->get_groupbarang($itypemakloon),
            'kodekelompok'  => $this->mmaster->getkelompokbarang($isupplier),
            'etype'         => $etype
        );

        if($isupplier == '' || $ikodekelompok == ''){
            $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

            $this->load->view($this->global['folder'].'/vformadd', $data);  
        }else{
            $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

            $this->load->view($this->global['folder'].'/vforminput', $data);
        }
    }

    public function getrumus(){
        $satuan_awal  = $this->input->post('satuan_awal');
        $satuan_akhir = $this->input->post('satuan_akhir');
        $idcompany    = $this->session->userdata('id_company');

        $query = $this->mmaster->getrumus($satuan_awal, $satuan_akhir, $idcompany);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_rumus_konversi." >".$row->i_rumus_konversi."</option>";
            }
            $kop  = "<option value=".$row->i_rumus_konversi." >".$row->i_rumus_konversi."</option>";
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

    public function getrumusfaktor(){
        $satuan_awal  = $this->input->post('satuan_awal');
        $satuan_akhir = $this->input->post('satuan_akhir');
        $idcompany    = $this->session->userdata('id_company');

        $query     = $this->mmaster->getrumus($satuan_awal, $satuan_akhir, $idcompany);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_rumus_konversi." >".$row->i_rumus_konversi."</option>";
            }
            $kop  = "<option value=".$row->n_angka_faktor_konversi." >".$row->n_angka_faktor_konversi."</option>";
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

    public function gettype(){
        $type = $this->input->post('itype');
        echo strtolower($this->db->query("select e_type_makloon from tm_type_makloon where i_type_makloon='$type'")->row()->e_type_makloon);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier       = $this->input->post('idsupplier', TRUE);   
        $isupp           = $this->input->post('isupplier', TRUE);      
        $itypemakloon    = $this->input->post('idmakloon', TRUE);
        $jml             = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $isupp);
        $id = $this->mmaster->runningid();
        $this->mmaster->insertheader($id, $isupplier, $itypemakloon);
        if ($isupplier != ''){
            for($i=1;$i<=$jml;$i++){ 
                if($this->input->post('cek'.$i)=='cek'){
                    $kodebrg        = $this->input->post('kodebrg'.$i, TRUE); 
                    $idkodebrg      = $this->input->post('idkodebrg'.$i, TRUE);
                    $isatuanint     = $this->input->post('isatuanint'.$i, TRUE);
                    $vpriceint      = str_replace(',','',$this->input->post('hargaint'.$i, TRUE));
                    $isatuaneks     = $this->input->post('isatuaneks'.$i, TRUE);
                    $vpriceeks      = str_replace(',','',$this->input->post('hargaeks'.$i, TRUE));
                    $irumuskonversi = $this->input->post('konversiharga'.$i, TRUE);
                    $dateberlaku    = $this->input->post('dberlaku'.$i, TRUE);
                    $itypepajak     = $this->input->post('ippn'.$i, TRUE);
                    if($dateberlaku){
                         $tmp   = explode('-', $dateberlaku);
                         $day   = $tmp[0];
                         $month = $tmp[1];
                         $year  = $tmp[2];
                         $yearmonth = $year.$month;
                         $dberlaku = $year.'-'.$month.'-'.$day;
                    }
                    $this->mmaster->insertdetail($id, $kodebrg, $idkodebrg, $isatuanint, $vpriceint, $isatuaneks, $vpriceeks, $irumuskonversi, $dberlaku, $itypepajak);                     
                }
            }          
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $isupp,
                    'id'     => $id,
                );
            }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);     
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier 	    = $this->input->post('isupplier', TRUE);
        $igroupbrg      = $this->input->post('igroupbrg', TRUE);
        $itypemakloon   = $this->input->post('itypemakloon', TRUE);
        $ikodekelompok  = $this->input->post('ikodekelompok', TRUE);   
        $ikodejenis     = $this->input->post('ikodejenis', TRUE);   
        $kodebrg 	    = $this->input->post('kodebrg', TRUE);
        $harga 		    = $this->input->post('harga', TRUE);
        $isatuan        = $this->input->post('isatuan', TRUE);
        $itipe          = $this->input->post('itipe', TRUE);
        $dateberlaku    = $this->input->post('dberlaku', TRUE);
        $datesebelum    = $this->input->post('dberlakusebelum', TRUE);
        $ipriceno       = '1';
        $etype          = $this->input->post('etype', TRUE);
        $status         = $this->input->post('status', TRUE);

        $tmp   = explode('-', $dateberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $datesebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dsebelum = $year.'-'.$month.'-'.$day;

        if ($isupplier != '' && $harga != ''){
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$kodebrg);
                if($dberlaku == $dsebelum){
                    $this->mmaster->update($isupplier, $kodebrg, $harga, $itipe, $isatuan, $dsebelum, $dberlaku, $etype, $status);
                }else{
                    $this->mmaster->insert($isupplier, $kodebrg, $harga, $ipriceno, $dberlaku, $igroupbrg, $itypemakloon, $ikodekelompok, $ikodejenis, $isatuan,  $itipe, $etype);
                    $this->mmaster->updatetglakhir($isupplier, $kodebrg, $dsebelum, $dberlaku, $etype);
                }
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

    public function ubahtanggalberlaku(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $id             = $this->input->post('id', TRUE);
        $isupplier 	    = $this->input->post('isupplier', TRUE);
        $igroupbrg      = $this->input->post('igroupbrg', TRUE);
        $itypemakloon   = $this->input->post('itypemakloon', TRUE);
        $ikodekelompok  = $this->input->post('ikodekelompok', TRUE);   
        $ikodejenis     = $this->input->post('ikodejenis', TRUE);   
        $idkodebrg 	    = $this->input->post('idkodebrg', TRUE);
        $kodebrg 	    = $this->input->post('kodebrg', TRUE);
        $hargaint		= str_replace(',','',$this->input->post('hargaint', TRUE));
        $isatuanint     = $this->input->post('isatuanint', TRUE);
        $hargaeks 		= str_replace(',','',$this->input->post('hargaeks', TRUE));
        $isatuaneks     = $this->input->post('isatuaneks', TRUE);
        $itipe          = $this->input->post('itipe', TRUE);
        $dateberlaku    = $this->input->post('dateberlaku', TRUE);
        $datesebelum    = $this->input->post('datesebelum', TRUE);
        $dakhirsebelum  = $this->input->post('dakhirsebelum', TRUE);
        $status         = $this->input->post('status', TRUE);

        $tmp   = explode('-', $dateberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $datesebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dsebelum = $year.'-'.$month.'-'.$day;

        $this->db->trans_begin();
        $data =  $this->mmaster->update($id, $isupplier, $kodebrg, $idkodebrg, $hargaint, $itipe, $isatuanint, $hargaeks, $isatuaneks, $dsebelum, $dberlaku, $dakhirsebelum, $status, $itypemakloon);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Ubah Harga Makloon by Supplier ' . $kodebrg);
            echo json_encode($data);
        }
    }

    public function inserttanggalberlaku(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $isupplier 	    = $this->input->post('isupplier', TRUE);
        $igroupbrg      = $this->input->post('igroupbrg', TRUE);
        $itypemakloon   = $this->input->post('itypemakloon', TRUE);
        $ikodekelompok  = $this->input->post('ikodekelompok', TRUE);   
        $ikodejenis     = $this->input->post('ikodejenis', TRUE);   
        $idkodebrg 	    = $this->input->post('idkodebrg', TRUE);
        $kodebrg 	    = $this->input->post('kodebrg', TRUE);
        $hargaint		= str_replace(',','',$this->input->post('hargaint', TRUE));
        $isatuanint     = $this->input->post('isatuanint', TRUE);
        $hargaeks 		= str_replace(',','',$this->input->post('hargaeks', TRUE));
        $isatuaneks     = $this->input->post('isatuaneks', TRUE);
        $itipe          = $this->input->post('itipe', TRUE);
        $dateberlaku    = $this->input->post('dateberlaku', TRUE);
        $datesebelum    = $this->input->post('datesebelum', TRUE);
        $dakhirsebelum  = $this->input->post('dakhirsebelum', TRUE);
        $status         = $this->input->post('status', TRUE);
        $irumuskonversi = $this->input->post('konversiharga', TRUE);

        $tmp   = explode('-', $dateberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $datesebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dsebelum = $year.'-'.$month.'-'.$day;

        $this->db->trans_begin();
        $idbaru = $this->mmaster->runningid();
        $data =  $this->mmaster->updatetglakhir($id, $idbaru, $isupplier, $kodebrg, $idkodebrg, $hargaint, $itipe, $isatuanint, $hargaeks, $isatuaneks, $dsebelum, $dberlaku, $dakhirsebelum, $status, $itypemakloon, $irumuskonversi);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Ubah Harga Makloon by Supplier ' . $kodebrg);
            echo json_encode($data);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id            = $this->uri->segment(4);
        $dberlaku      = $this->uri->segment(5);
        $dfrom         = $this->uri->segment(6);
        $idtypemakloon = $this->uri->segment(7);
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'idtypemakloon' => $idtypemakloon,
            'dberlaku'      => $dberlaku,
            'data'          => $this->mmaster->cek_data($id,$dberlaku)->row(),
            'satuan'        => $this->mmaster->get_satuan()
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    function getproductname(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $this->db->select("id, kode_brg, nama_brg");
        $this->db->from("tm_barang_wip");
        $this->db->where("UPPER(kode_brg)", $iproduct);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("id, kode_brg, nama_brg");
            $this->db->from("tm_barang_wip");
            $this->db->like("UPPER(kode_brg)", $cari);
            $this->db->or_like("UPPER(nama_brg)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->kode_brg,  
                    'text' => $product->kode_brg//.' - '.$product->e_product_basename
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getjenis(){
            $ikodekelompok = $this->input->post('ikodekelompok');
            $query = $this->mmaster->getjenis($ikodekelompok);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_type_code." >".$row->i_type_code." - ".$row->e_type_name."</option>";
                }
                $kop  = "<option value=\"AJB\"> -- Semua Jenis Barang -- ".$c."</option>";
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

    public function getmaterial(){
            $isupplier      = $this->input->post('idsupp');
            $ikodejenis     = $this->input->post('ikodejenis');
            $ikodekelompok  = $this->input->post('ikodekelompok');
            $itypemakloon   = $this->input->post('itypemakloon');
            $query = $this->mmaster->getmaterial($isupplier, $ikodejenis, $ikodekelompok, $itypemakloon);
            if($query->num_rows() > 0){
                $c  = "";
                $material = $query->result();
                foreach($material as $row) {
                    $c.="<option value=".$row->i_product." >".$row->i_product." - ".$row->e_product_name."</option>";
                }
                $kop  = "<option value=\"BRG\"> -- Semua Barang -- ".$c."</option>";
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

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isupplier  = $this->input->post('i_supplier', true);
        $kodebrg    = $this->input->post('kode_brg', true);
        $etype      = $this->input->post('etype', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isupplier, $kodebrg, $etype);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Harga Makloon by Supplier ' . $kodebrg);
            echo json_encode($data);
        }
    }

    public function view(){
        $id            = $this->uri->segment(4);
        $dberlaku      = $this->uri->segment(5);
        $dfrom         = $this->uri->segment(6);
        $idtypemakloon = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'idtypemakloon' => $idtypemakloon,
            'data'          => $this->mmaster->cek_data($id,$dberlaku)->row(),
            'satuan'        => $this->mmaster->get_satuan()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function view2(){
        $dfrom        = $this->input->post('dberlaku');
        $itypemakloon = $this->input->post('idtypemakloon');

        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }

        if($itypemakloon == ''){
            $itypemakloon =  $this->uri->segment(5);
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'idtypemakloon' => $itypemakloon,
            'etypemakloon'  => $this->mmaster->getnamemakloon($itypemakloon)
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

}

/* End of file Cform.php */
