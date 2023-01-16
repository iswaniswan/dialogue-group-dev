<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;*/
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010210';
   
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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu, $this->global['folder']);
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
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getgroup(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->getgroup($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $igroup){
                $filter[] = array(
                    'id'   => $igroup->i_kode_group_barang,  
                    'text' => $igroup->e_nama_group_barang,
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

    public function get_satuan_konversi(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->get_satuan_konversi($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'   => $row->i_satuan_code,  
                    'text' => $row->e_satuan_name,
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

    public function getkategori(){
        $filter = [];
        $cari      = str_replace("'", "", $this->input->get('q'));
        $igroup    = $this->input->get('igroup');
        $idcompany = $this->session->userdata('id_company');

        $data   = $this->mmaster->getkategori($cari, $igroup, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'   => $row->i_kode_kelompok,  
                    'text' => $row->e_nama_kelompok,
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

    public function getjenis(){
        $filter = [];
        $cari      = str_replace("'", "", $this->input->get('q'));
        $idcompany = $this->session->userdata('id_company');
        $ikategori    = $this->input->get('ikategori');

        $data   = $this->mmaster->getjenis($cari, $ikategori, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'   => $row->i_type_code,  
                    'text' => $row->e_type_name,
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
    
    public function style(){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');
        $data = $this->mmaster->style($cari, $idcompany);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_style,  
                'text' => $key->e_style_name,
            );
        }          
        echo json_encode($filter);
    }

    public function brand(){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');
        $data = $this->mmaster->brand($cari, $idcompany);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_brand,  
                'text' => $key->e_brand_name
            );
        }          
        echo json_encode($filter);
    }

    public function satuan(){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');
        $data = $this->mmaster->satuan($cari, $idcompany);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_satuan_code,  
                'text' => $key->e_satuan_name,
            );
        }          
        echo json_encode($filter);
    }

    public function supplier(){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');
        $data = $this->mmaster->supplier($cari, $idcompany);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_supplier,  
                'text' => $key->e_supplier_name,
            );
        }          
        echo json_encode($filter);
    }

    public function statusproduksi(){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));
        $data = $this->mmaster->statusproduksi($cari);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_status_produksi,  
                'text' => $key->e_status_produksi,
            );
        }          
        echo json_encode($filter);
    }

    public function divisi(){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');
        $data = $this->mmaster->divisi($cari, $idcompany);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_kode_divisi,  
                'text' => $key->e_nama_divisi,
            );
        }          
        echo json_encode($filter);
    }

    public function cekkode(){
        $kode = $this->input->post('kode');
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->mmaster->cekkode($kode, $idcompany);
        if($query->num_rows() > 0){
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

   /* public function getkelompok1(){
        $typecode = $this->input->post('itypecode');
        $query = $this->mmaster->getkelompokbarang($typecode);
        if($query->num_rows()>0) {
            $c  = "";
            $ikelompok = $query->result();
            foreach($ikelompok as $row) {
                $itype       = $row->i_type_code;                
                $eitype      = $row->e_type_name;                
                $ikategori   = $row->i_kode_kelompok;                
                $ekategori   = $row->e_kategori;                
                $igroup      = $row->i_kode_group_barang;
                $egroupname  = $row->e_nama_group_barang;
            }
            echo json_encode(array(
                'itype'         => $itype,
                'eitype'        => $eitype,
                'ikategori'     => $ikategori,
                'ekategori'     => $ekategori,
                'igroup'        => $igroup,
                'egroupname'    => $egroupname,
            ));
        }
    }*/

    public function getkode(){
        $ijenisbrg = $this->input->post('ijenisbrg', TRUE);
        if ($ijenisbrg != '') {
            $ikode      = $this->mmaster->getkode($ijenisbrg);
            if($ikode != null || $ikode != ''){                
                $awal  =  substr($ikode, 0, 3);
                $akhir = (substr($ikode, 3, 4))+1;
                switch(strlen($akhir)) {
                    case "1": $akhir_new = "000".$akhir;
                    break;
                    case "2": $akhir_new = "00".$akhir;
                    break;  
                    case "3": $akhir_new = "0".$akhir;
                    break;
                    case "4": $akhir_new = $akhir;
                    break;  
                }
                $ikode_new = "$awal".$akhir_new;
            }else{
                $ikode_new = '';
            }
        }
        echo json_encode($ikode_new);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikodebrg           = $this->input->post('ikodebrg', TRUE); 
        $ikelompok          = $this->input->post('ikategori', TRUE);
        $ijenisbrg          = $this->input->post('ijenisbrg', TRUE);         
        $isatuan            = $this->input->post('isatuan', TRUE); 
        $enamabrg           = $this->input->post('enamabrg', TRUE); 
        $edeskripsi         = $this->input->post('edeskripsi', TRUE);
        $isupplier          = $this->input->post('isupplier', TRUE); 
        $igroupbrg          = $this->input->post('igroupbrg', TRUE);       
        $npanjang           = $this->input->post('npanjang', TRUE);      
        $nlebar             = $this->input->post('nlebar', TRUE);      
        $ntinggi            = $this->input->post('ntinggi', TRUE);      
        $nberat             = $this->input->post('nberat', TRUE); 
        $isatuanberat       = $this->input->post('isatuanberat', TRUE); 
        $isatuanukuran      = $this->input->post('isatuanukuran', TRUE);   
        $ibrand             = $this->input->post('ibrand', TRUE);
        $istyle             = $this->input->post('istyle', TRUE);  
        $istatusproduksi    = $this->input->post('istatusproduksi', TRUE);  
        //$idivisi            = $this->input->post('idivisi', TRUE);
        $dregister          = $this->input->post('dregister', TRUE); 
        if($dregister){
                 $tmp   = explode('-', $dregister);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $dateregister = $year.'-'.$month.'-'.$day;
        }
        
        if ($ikodebrg != '' && $ijenisbrg != '' && $ikelompok != '' && $isatuan != ''){
            $this->db->trans_begin();
            $this->mmaster->insert($ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $dateregister);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikodebrg);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodebrg
                );
            }
        }else{
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
        $igroupbrg  = $this->uri->segment(5);
        $ikategori  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'            => $this->mmaster->cek_data_detail($id, $idcompany),
            'bisbisan'          => $this->mmaster->cek_data_detail_bisbisan($id, $idcompany),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id                 = $this->input->post('id', TRUE);
        $ikodebrg           = $this->input->post('ikodebrg', TRUE); 
        $ikelompok          = $this->input->post('ikategori', TRUE);
        $ijenisbrg          = $this->input->post('ijenisbrg', TRUE);         
        $isatuan            = $this->input->post('isatuan', TRUE); 
        $enamabrg           = $this->input->post('enamabrg', TRUE); 
        $edeskripsi         = $this->input->post('edeskripsi', TRUE);
        $isupplier          = $this->input->post('isupplier', TRUE); 
        $igroupbrg          = $this->input->post('igroupbrg', TRUE);       
        $npanjang           = $this->input->post('npanjang', TRUE);      
        $nlebar             = $this->input->post('nlebar', TRUE);      
        $ntinggi            = $this->input->post('ntinggi', TRUE);      
        $nberat             = $this->input->post('nberat', TRUE); 
        $isatuanberat       = $this->input->post('isatuanberat', TRUE); 
        $isatuanukuran      = $this->input->post('isatuanukuran', TRUE);   
        $ibrand             = $this->input->post('ibrand', TRUE);
        $istyle             = $this->input->post('istyle', TRUE);  
        $istatusproduksi    = $this->input->post('istatusproduksi', TRUE);  
        //$idivisi           = $this->input->post('idivisi', TRUE);
        $dregister          = $this->input->post('dregister', TRUE);  
        if($dregister){
                 $tmp   = explode('-', $dregister);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $dateregister = $year.'-'.$month.'-'.$day;
        }
        if($npanjang == '' || $nlebar == '' ||  $ntinggi == '' ||  $nberat == ''){
            $npanjang       = 0;      
            $nlebar         = 0;   
            $ntinggi        = 0;      
            $nberat         = 0;
        } 
        
        // if($istatusproduksi == ''){
        //     $istatusproduksi = null;
        // }

        // if($ibrand == ''){
        //     $ibrand = null;
        // }
        
        // if($istyle == ''){
        //     $istyle = null;
        // }

        // if($isupplier == ''){
        //     $isupplier = null;
        // }

        if ($ikodebrg != '' && $ikelompok != '' && $ijenisbrg != '' && $isatuan != '' && $igroupbrg != ''){  
            $this->db->trans_begin();           
            $this->mmaster->update($id, $ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $dateregister);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ikodebrg);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodebrg
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        } 

        $id         = $this->uri->segment(4);
        $igroupbrg  = $this->uri->segment(5);
        $ikategori  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'            => $this->mmaster->cek_data_detail($id, $idcompany),
            'bisbisan'          => $this->mmaster->cek_data_detail_bisbisan($id, $idcompany),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function get_jenis_potong(){
        $filter = [];
        //$idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->get_jenis_potong($cari);
        if ($data->num_rows()>0) {
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'   => $row->id,  
                    'text' => $row->e_jenis_potong,
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

        /** Get Jenis Potong Detail */
    public function get_jenis_potong_detail()
    {
        header("Content-Type: application/json", true);
        $id = $this->input->post('id', TRUE);
        $query  = array(
            'data' => $this->mmaster->get_jenis_potong_detail($id)->row()
        );
        echo json_encode($query);
    }

    public function export()
    {
        /* $data = check_role($this->i_menu, 6);
        if(!$data){
            redirect(base_url(),'refresh');
        } */

        $query          = $this->mmaster->get_dataheader();
        $idmaterial     = $this->mmaster->get_dataheader()->result_array();
        $idmaterial     = array_column($idmaterial,"id");
        $idmaterial     = implode("','",$idmaterial);
        $detail         = $this->mmaster->get_datamaterial($idmaterial);
        $bisbisan       = $this->mmaster->get_databisbisan($idmaterial);
        $spreadsheet = new Spreadsheet();
        $sharedStyle1 = new Style();
        $sharedStyle11 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedStylex = new Style();

        $sheet1 = $spreadsheet;
        $sheet2 = $spreadsheet;
        $sheet3 = $spreadsheet;
        
        $sheet1
            ->getActiveSheet()
            ->setTitle("Master Material & Spare part")
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sharedStyle1->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "DFF1D0"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle11->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "f7a19a"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle2->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                /* 'top'    => ['borderStyle' => Border::BORDER_THIN],
                 'bottom' => ['borderStyle' => Border::BORDER_THIN], */
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStylex->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                /* 'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN] */
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStyle3->applyFromArray([
            "font" => [
                "name" => "Times New Roman",
                "bold" => true,
                "italic" => false,
                "size" => 12,
            ],
            "alignment" => [
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet1
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        foreach (range("A", "J") as $columnID) {
            $sheet1
                ->getActiveSheet()
                ->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $sheet1->getActiveSheet()->mergeCells("A1:I3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet1->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:I3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet1
            ->setActiveSheetIndex(0)
            ->setCellValue("A1", "MATERIAL & SPARE PART")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Barang")
            ->setCellValue("C5", "Nama Barang")
            ->setCellValue("D5", "Satuan")
            ->setCellValue("E5", "Sub Kategori")
            ->setCellValue("F5", "Kategori")
            ->setCellValue("G5", "Grup Barang")
            ->setCellValue("H5", "Supplier Utama")
            ->setCellValue("I5", "Tanggal Daftar")
            /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        $sheet1->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:I5");
        $sheet1->getActiveSheet()->freezePane('E6');
        $sheet1->getActiveSheet()->setAutoFilter('A5:I5');
        $sheet1->getActiveSheet()->getSheetView()->setZoomScale(75);

        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "I5");
        $spreadsheet
            ->setActiveSheetIndex(0)
            ->setCellValue("K2", "KATEGORI PENJUALAN")
            ->setCellValue("K5", "ID Kategori")
            ->setCellValue("L5", "Nama Kategori");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "L5");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "K5"); */

        

        $kolom = 6;
        $no = 1;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {

                $sheet1
                    ->setActiveSheetIndex(0)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, trim($row->i_material))
                    ->setCellValue("C" . $kolom, trim($row->e_material_name))
                    ->setCellValue("D" . $kolom, $row->e_satuan_name)
                    ->setCellValue("E" . $kolom, $row->e_type_name)
                    ->setCellValue("F" . $kolom, $row->e_nama_kelompok)
                    ->setCellValue("G" . $kolom, $row->e_nama_group_barang)
                    ->setCellValue("H" . $kolom, $row->e_supplier_name)
                    ->setCellValue("I" . $kolom, trim($row->d_register))
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet1
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":I" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        // check point sheet1

        $sheet2
            ->createSheet()
            ->setTitle("Master Bisbisan")
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sheet2
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        foreach (range("A", "M") as $columnID) {
            $sheet2
                ->getActiveSheet()
                ->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $sheet2->getActiveSheet()->mergeCells("A1:L3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet2->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:L3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet2
            ->setActiveSheetIndex(1)
            ->setCellValue("A1", "BISBISAN")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Barang")
            ->setCellValue("C5", "Nama Barang")
            ->setCellValue("D5", "Ukuran Bisbisan")
            ->setCellValue("E5", "Lebar Kain")
            ->setCellValue("F5", "Jenis Potong")
            ->setCellValue("G5", "% Hilang Lebar Kain")
            ->setCellValue("H5", "Lebar Kain Jadi")
            ->setCellValue("I5", "Jml Roll")
            ->setCellValue("J5", "% Tambah Panjang Kain")
            ->setCellValue("K5", "Panjang Bisbisan")
            ->setCellValue("L5", "Panjang Bisbisan per 1m")
            /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        $sheet2->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:L5");
        $sheet2->getActiveSheet()->freezePane('D6');
        $sheet2->getActiveSheet()->setAutoFilter('A5:L5');
        $sheet2->getActiveSheet()->getSheetView()->setZoomScale(75);
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "I5");
        $spreadsheet
            ->setActiveSheetIndex(0)
            ->setCellValue("K2", "KATEGORI PENJUALAN")
            ->setCellValue("K5", "ID Kategori")
            ->setCellValue("L5", "Nama Kategori");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "L5");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "K5"); */

        

        $kolom = 6;
        $no = 1;
        if ($bisbisan->num_rows() > 0) {
            foreach ($bisbisan->result() as $row) {

                $sheet2
                    ->setActiveSheetIndex(1)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_material)
                    ->setCellValue("C" . $kolom, $row->e_material_name)
                    ->setCellValue("D" . $kolom, $row->n_bisbisan)
                    ->setCellValue("E" . $kolom, $row->v_lebar_kain_awal)
                    ->setCellValue("F" . $kolom, $row->e_jenis_potong)
                    ->setCellValue("G" . $kolom, $row->n_hilang_lebar)
                    ->setCellValue("H" . $kolom, $row->v_lebar_kain_akhir)
                    ->setCellValue("I" . $kolom, number_format($row->v_jumlah_roll,4))
                    ->setCellValue("J" . $kolom, $row->n_tambah_panjang)
                    ->setCellValue("K" . $kolom, $row->n_panjang_bis)
                    ->setCellValue("L" . $kolom, number_format($row->v_panjang_bis,4))
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet2
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":L" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        // check point sheet2

        $sheet3
            ->createSheet()
            ->setTitle("Konversi Material")
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sheet3
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        foreach (range("A", "G") as $columnID) {
            $sheet3
                ->getActiveSheet()
                ->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $sheet3->getActiveSheet()->mergeCells("A1:H3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet3->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:H3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet3
            ->setActiveSheetIndex(2)
            ->setCellValue("A1", "KONVERSI MATERIAL")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Barang")
            ->setCellValue("C5", "Nama Barang")
            ->setCellValue("D5", "Satuan Produksi")
            ->setCellValue("E5", "Operator")
            ->setCellValue("F5", "Faktor")
            ->setCellValue("G5", "Satuan Pembelian")
            ->setCellValue("H5", "Default");
        $sheet3->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:H5");
        $sheet3->getActiveSheet()->freezePane('E6');
        $sheet3->getActiveSheet()->setAutoFilter('A5:H5');
        $sheet3->getActiveSheet()->getSheetView()->setZoomScale(75);
        

        $kolom = 6;
        $no = 1;
        if ($detail->num_rows() > 0) {
            foreach ($detail->result() as $row) {

                $sheet3
                    ->setActiveSheetIndex(2)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_material)
                    ->setCellValue("C" . $kolom, $row->e_material_name)
                    ->setCellValue("D" . $kolom, $row->e_satuan_name)
                    ->setCellValue("E" . $kolom, $row->e_operator)
                    ->setCellValue("F" . $kolom, number_format($row->n_faktor,4))
                    ->setCellValue("G" . $kolom, $row->e_satuan_name_konversi)
                    ->setCellValue("H" . $kolom, $row->f_default)
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet3
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":H" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

       
        $writer = new Xls($spreadsheet);
        $nama_file = "Master_Barang_Jadi_" . date('Ymd_His') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $nama_file . "");
        header("Cache-Control: max-age=0");
        ob_end_clean();
        ob_start();
        $writer->save("php://output");
        /* }else{
            echo "<center><h1> Tidak Ada Data :(</h1></center>";
        } */
    }
    

}

/* End of file Cform.php */