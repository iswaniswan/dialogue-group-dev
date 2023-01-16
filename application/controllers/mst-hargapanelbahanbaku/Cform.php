<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010702';

    public function __construct(){
        
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        $this->load->library('pagination');

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index($offset=NULL){

        $dberlaku = $this->input->post('dberlaku', TRUE);
        if ($dberlaku == '') {
            $dberlaku = $this->uri->segment(4);
            if ($dberlaku == '') {
                $dberlaku = date('d-m-Y');
            }
        }

        $isupplier = $this->input->post('isupplier', TRUE);
        if($isupplier == NULL || $isupplier == ""){
            $isupplier = $this->uri->segment(6);
            //var_dump($isupplier);
            if($isupplier == NULL || $isupplier == ""){
                $isupplier = 'ALL';
            }
        }

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['offset'] = $offset;
        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dberlaku)),
            'isupplier' => $isupplier,
            'esupplier' => $this->mmaster->getnamasupplier($isupplier, $idcompany)->result()
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $dberlaku = $this->uri->segment(4);
        $isupplier= $this->uri->segment(5);
		echo $this->mmaster->data($dberlaku, $isupplier, $this->i_menu, $this->global['folder']);
    }

    public function view2(){
        $dberlaku     = $this->input->post('dberlaku');
        $isupplier    = $this->input->post('isupplier');
        $idcompany    = $this->session->userdata('id_company');

        if($dberlaku == ''){
            $dberlaku = $this->uri->segment(4);
        }

        if($isupplier == ''){
            $isupplier =  $this->uri->segment(5);
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "View ".$this->global['title'],
            'title_list'=> 'List '.$this->global['title'],
            'dfrom'     => $dberlaku,
            'isupplier' => $isupplier,
            'esupplier' => $this->mmaster->getnamasupplier($isupplier, $idcompany)->row()

        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function supplierlist(){
        $filter = [];
        $cari         = strtoupper($this->input->get('q'));
        $idcompany    = $this->session->userdata('id_company');

        $data = $this->mmaster->supplierlist($cari, $idcompany);
        $filter[] = array(
            'id'   => 'ALL',  
            'text' => 'All Supplier',
        );
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_supplier,  
                'text' => $key->e_supplier_name,
            );
        }          
        echo json_encode($filter);
    }


    public function status(){
        $data = check_role($this->i_menu, 3);

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        $str = explode('|', $id);
        $isupplier = $str[0];
        $id_panel_item = $str[1];
        $id        = $str[2];

        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($isupplier, $id_panel_item, $id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function tambahold(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $group_supplier   = $this->session->userdata('group_supplier');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title']
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $format = $this->mmaster->getformat();
        if($format->num_rows() < 1){
            $no = 0;
        }
        else{
            $format = $format->row();
            $no  = substr($format->i_document,8);    
        }
        $no = (int)$no + 1;
        $num = sprintf("%04d", $no);
        $str = "SC-".date("ym")."-".$num;

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'format'     => $str,
            'bagian'     => $this->mmaster->bagian()->result(),
            'periode'    => $this->mmaster->getperiode()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformaddnew', $data);
    }

    /*-------------- CARI MARKER ------------- */
    public function marker()
    {
        $filter = [];
        $data = $this->mmaster->marker(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->e_marker_name
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function panel()
    {
        $filter = [];
        $data = $this->mmaster->panel(str_replace("'", "", $this->input->get('q')), str_replace("'", "", $this->input->get('marker')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->i_panel . ' - ' . $row->e_color_name
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function supplier(){
        $filter     = [];
        $cari       = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');

        $data = $this->mmaster->supplier($cari, $idcompany);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_supplier,  
                'text' => $key->e_supplier_name,
                'ppn'  => $key->i_type_pajak
            );
        }          
        echo json_encode($filter);
    }

    public function getkelompokbarang(){
        $isupplier = $this->input->post('id');
        $idcompany = $this->session->userdata('id_company');
        $query     = $this->mmaster->getkelompokbarang($isupplier, $idcompany);
        if($query->num_rows()>0) {
            $c   = "";
            $kelompokbarang = $query->result();
            foreach($kelompokbarang as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->e_nama_kelompok."</option>";
            }
            $kop  =  "<option value=\"\">Pilih Kategori Barang".$c."</option>";
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

    public function getjenisbarang(){
        $ikodekelompok = $this->input->post('ikodekelompok');
        $idcompany     = $this->session->userdata('id_company');

        $query = $this->mmaster->getjenisbarang($ikodekelompok, $idcompany);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_type_code." >".$row->e_type_name."</option>";
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
            $cari = $this->input->get('q');
            $isupplier      = $this->input->get('isupplier');
            $isubkategori     = $this->input->get('isubkategori');
            $ikategori  = $this->input->get('ikategori');
            $idcompany      = $this->session->userdata('id_company');
            $filter = [];

            if(empty($isupplier)){
                $isupplier = '';
            }
            if(empty($isubkategori)){
                $isubkategori = '';
            }
            if(empty($ikategori)){
                $ikategori = '';
            }

            $query = $this->mmaster->getmaterial($cari, $isupplier, $isubkategori, $ikategori, $idcompany);
            if ($query->num_rows()>0) {
                foreach($query->result() as  $row){
                    $filter[] = array(
                        'id'   => $row->i_material,  
                        'text' => $row->i_material." - ".$row->e_material_name,
                        'satuancode' => $row->i_satuan_code,
                        'satuanname' => $row->e_satuan_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data",
                );
            }
            echo json_encode($filter);
    }

    public function proses(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier     = $this->input->post('isupplier',true);
        $ikodekelompok = $this->input->post('ikodekelompok',true);
        $ikodejenis    = $this->input->post('ikodejenis',true);
        $imaterial     = $this->input->post('imaterial',true);
        $idcompany     = $this->session->userdata('id_company');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'isupplier'     => $isupplier,
            'ikodekelompok' => $ikodekelompok,
            'ikodejenis'    => $ikodejenis,
            'imaterial'     => $imaterial,
            'datasup'       => $this->mmaster->getnamasupplier($isupplier, $idcompany)->row(),
            'proses'        => $this->mmaster->getinput($ikodekelompok, $ikodejenis, $isupplier, $imaterial, $idcompany),
            'satuan'        => $this->mmaster->getsatuan($idcompany),
        );
        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);  
    }

    public function satuan()
    {
        $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_satuan a");
            $data = $this->db->get();
            foreach ($data->result() as $ikode) {
                $filter[] = array(
                    'id'   => $ikode->i_satuan_code,
                    'text' => $ikode->e_satuan_name,

                );
            }

            echo json_encode($filter);
    }

    public function getrumus(){
        $satuan_awal  = $this->input->post('satuan_awal');
        $satuan_akhir = $this->input->post('satuan_akhir');
        $idcompany    = $this->session->userdata('id_company');

        $query        = $this->mmaster->getrumus($satuan_awal, $satuan_akhir, $idcompany);
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

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier       = $this->input->post('isupplier', TRUE);       
        // $ikategori   = $this->input->post('kategori', TRUE);   
        // $isubkategori      = $this->input->post('subkategori', TRUE);
        $ppn            = $this->input->post('ppn', TRUE);   
        $jml             = $this->input->post('jml', TRUE);

        //var_dump($_POST);
        if ($isupplier != ''){
            $this->db->trans_begin();
            for($i=1;$i<=$jml;$i++){ 
                //if($this->input->post('cek'.$i)=='cek'){
                    $barang            = $this->input->post('namabrg'.$i, TRUE); 
                    $marker            = $this->input->post('marker'.$i, TRUE); 
                    if($ppn == 'Include'){
                        $fppn = 't';
                    }else if($ppn == 'Exclude'){
                        $fppn = 'f';
                    }
                    // $isatuansupp        = $this->input->post('isatuansupplier'.$i, TRUE);
                    $hargakonversi      = $this->input->post('hargakonversi'.$i, TRUE);
                    $hargakonversi      = str_replace(',','',$hargakonversi);

                    $harga = $hargakonversi;

                    $norder             = $this->input->post('norder'.$i, TRUE);
                    if($norder == '' || $norder == null){
                        $norder = '0';
                    }
                    $dberlaku       = $this->input->post('dberlaku'.$i, TRUE);
                    $dateberlaku    = date('Y-m-d', strtotime($dberlaku));
                    
                    $this->mmaster->insert($isupplier, $barang, $marker, $harga, $hargakonversi, $norder, $dateberlaku, $fppn);   
                //}
                
            }     
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $isupplier
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode Supplier : '.$isupplier);
            }        
            // $data = array(
            //     'sukses'    => true,
            //     'kode'      => $isupplier
            // );
        }else{
            $this->db->trans_rollback();
            $data = array(
                    'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);     
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $id_panel_item = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $dberlaku   = $this->uri->segment(7);
        $suppfilter = $this->uri->segment(8);
        $dfrom      = $this->uri->segment(9);
        $idcompany  = $this->session->userdata('id_company');

        // var_dump($id);
        // die();
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dberlaku'      => $dberlaku,
            'suppfilter'    => $suppfilter,
            'dfrom'         => $dfrom,
            'id_panel_item' => $id_panel_item,
            'isupplier'     => $isupplier,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($id_panel_item,$isupplier, $id, $idcompany)->row(),
            'satuan'        => $this->mmaster->getsatuan($idcompany),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function ubahtanggalberlaku(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id              = $this->input->post('id', TRUE);
        $isupplier       = $this->input->post('isupplier', TRUE);
        $id_panel_item   = $this->input->post('id_panel_item', TRUE);
        $marker          = $this->input->post('marker', TRUE);
        $harga           = str_replace(',','',$this->input->post('harga', TRUE));
        $norder          = $this->input->post('norder', TRUE);   
        $hargakonversi   = $this->input->post('hargakonversi', TRUE);
        $hargakonversi   = str_replace(',','',$hargakonversi);
        $fppn            = $this->input->post('fppn', TRUE);
        $dberlaku        = $this->input->post('dberlaku', TRUE);
        $dberlakusebelum = $this->input->post('dberlakusebelum', TRUE);
        $dakhirsebelum   = $this->input->post('dakhirsebelum', TRUE);
        $dfrom           = $this->input->post('dfrom', TRUE);
        $idcompany       = $this->session->userdata('id_company');

        $tmp   = explode('-', $dberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dateberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $dberlakusebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dateberlakusebelum = $year.'-'.$month.'-'.$day;

        $this->db->trans_begin();
        $data =  $this->mmaster->update($id, $isupplier, $id_panel_item, $marker, $harga, $hargakonversi, $norder, $dateberlaku, $fppn, $idcompany);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Ubah Harga Bahan Baku dan Bahan Pembantu by Supplier ' . $id_panel_item);
            echo json_encode($data);
        }
    }

    public function inserttanggalberlaku(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id              = $this->input->post('id', TRUE);
        $isupplier       = $this->input->post('isupplier', TRUE);
        $id_panel_item   = $this->input->post('id_panel_item', TRUE);
        $marker          = $this->input->post('marker', TRUE);
        $harga           = str_replace(',','',$this->input->post('harga', TRUE));
        $norder          = $this->input->post('norder', TRUE);   
        $hargakonversi   = $this->input->post('hargakonversi', TRUE);
        $hargakonversi   = str_replace(',','',$hargakonversi);
        $fppn            = $this->input->post('fppn', TRUE);
        $dberlaku        = $this->input->post('dberlaku', TRUE);
        $dberlakusebelum = $this->input->post('dberlakusebelum', TRUE);
        $dakhirsebelum   = $this->input->post('dakhirsebelum', TRUE);
        $dfrom           = $this->input->post('dfrom', TRUE);
        $idcompany       = $this->session->userdata('id_company');

        $tmp   = explode('-', $dberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dateberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $dberlakusebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dateberlakusebelum = $year.'-'.$month.'-'.$day;

        $this->db->trans_begin();
        $data =  $this->mmaster->updatetglakhir($id, $isupplier, $id_panel_item, $marker, $harga, $hargakonversi, $norder, $fppn, $dateberlaku, $dateberlakusebelum, $idcompany);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Ubah Harga Bahan Baku dan Bahan Pembantu by Supplier ' . $id_panel_item);
            echo json_encode($data);
        }
    }

    public function update(){

            $data = check_role($this->i_menu, 3);
            if(!$data){
                redirect(base_url(),'refresh');
            }
            
            $id             = $this->input->post('id', TRUE);
            $isupplier 	    = $this->input->post('isupplier', TRUE);
            $id_panel_item  = $this->input->post('id_panel_item', TRUE);
            $marker         = $this->input->post('marker', TRUE);
            $harga 		    = $this->input->post('harga', TRUE);
            $norder         = $this->input->post('norder', TRUE);
            $hargakonversi  = $this->input->post('hargakonversi', TRUE);
            $hargakonversi  = str_replace(',','',$hargakonversi);
            $konversiharga  = $this->input->post('konversiharga', TRUE);
            $konversiharga  = str_replace(',','',$konversiharga);
            $angkafaktor    = $this->input->post('angkafaktor', TRUE);
            $fppn            = $this->input->post('ippn', TRUE);
            if($fppn == 1){
                $fppn = 't';
            }else if($fppn == 0){
                $fppn = 'f';
            }
            $dberlaku       = $this->input->post('dberlaku', TRUE);
            if($dberlaku){
                $tmp   = explode('-', $dberlaku);
                $day   = $tmp[0];
                $month = $tmp[1];
                $year  = $tmp[2];
                $yearmonth = $year.$month;
                $dateberlaku = $year.'-'.$month.'-'.$day;
            }
            $dberlakusebelum = $this->input->post('dberlakusebelum', TRUE);
            if($dberlakusebelum){
                $tmp   = explode('-', $dberlaku);
                $day   = $tmp[0];
                $month = $tmp[1];
                $year  = $tmp[2];
                $yearmonth = $year.$month;
                $dateberlakusebelum = $year.'-'.$month.'-'.$day;
            }
            $idcompany       = $this->session->userdata('id_company');

            if ($isupplier != '' && $harga != ''){
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$id_panel_item);
                if($dateberlaku == $dateberlakusebelum){
                    $this->mmaster->update($id, $isupplier, $id_panel_item, $marker, $harga, $hargakonversi, $norder, $dateberlaku, $fppn, $idcompany);
                }else{
                    $this->mmaster->insert($isupplier, $id_panel_item, $marker, $harga, $isatuansupp, $hargakonversi, $norder, $dateberlaku, $fppn);  
                    $this->mmaster->updatetglakhir($id, $isupplier, $id_panel_item, $marker, $dateberlakusebelum, $dateberlaku, $idcompany);
                }
                $data = array(
                    'sukses'    => true,
                    'kode'      => $id_panel_item
                );
            }else{
                $data = array(
                    'sukses' => false,
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function view(){

        $id         = $this->uri->segment(4);
        $id_panel_item    = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $dberlaku   = $this->uri->segment(7);
        $suppfilter = $this->uri->segment(8);
        $dfrom      = $this->uri->segment(9);
        $idcompany  = $this->session->userdata('id_company');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dberlaku'      => $dberlaku,
            'suppfilter'    => $suppfilter,
            'dfrom'         => $dfrom,
            'id_panel_item' => $id_panel_item,
            'isupplier'     => $isupplier,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($id_panel_item,$isupplier, $id, $idcompany)->row(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){

        $id         = $this->uri->segment(4);
        $id_panel_item = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $dberlaku   = $this->uri->segment(7);
        $suppfilter = $this->uri->segment(8);
        $dfrom      = $this->uri->segment(9);
        $idcompany  = $this->session->userdata('id_company');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dberlaku'      => $dberlaku,
            'suppfilter'    => $suppfilter,
            'dfrom'         => $dfrom,
            'id_panel_item' => $id_panel_item,
            'isupplier'     => $isupplier,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($id_panel_item,$isupplier, $id, $idcompany)->row(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function changestatus() {
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

    public function kategori(){
        $filter = [];
            $data = $this->mmaster->getkategori($this->input->get('isupplier'),str_replace("'", "", $this->input->get('q')));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_kode_kelompok,  
                    'text' => ucwords(strtolower($key->e_nama_kelompok)),
                );
            }
        echo json_encode($filter);
    }

    public function subkategori(){
        $filter = [];
        if ($this->input->get('ikategori')!='') {
            $data = $this->mmaster->getsubkategori($this->input->get('ikategori'),str_replace("'", "", $this->input->get('q')));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_type_code,  
                    'text' => ucwords(strtolower($key->e_type_name)),
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Kategori Tidak Boleh Kosong!',
            );
        }
        echo json_encode($filter);
    }

    /* referensi schedule cutting */

    public function getdataitem()
    {
        header("Content-Type: application/json", true);
        $isupplier    = $this->input->post('idreff');
        $kategori    = $this->input->post('kategori');
        $subkategori    = $this->input->post('subkategori');
        // $ipengirim = $this->input->post('ipengirim');
        $jml = $this->mmaster->getinputnew($isupplier,$kategori, $subkategori);
        $data = array(
            'jmlitem'    => $jml->num_rows(),
            'dataitem'   => $this->mmaster->getinputnew($isupplier, $kategori, $subkategori)->result_array(),
        );
        echo json_encode($data);
    }
}

/* End of file Cform.php */
