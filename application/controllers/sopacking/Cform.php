<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
/* use PhpOffice\PhpSpreadsheet\Style\Fill; */
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment; */
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;



class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090607';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
        $data = check_role_folder($this->uri->segment(1), 2);
        // $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_menu = $data[0]['i_menu'];
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        // var_dump($this->uri->segment(1));
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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    public function index2()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SO-".date('ym')."-1234"
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function tambah(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post("ibagian",true);
        $ddocument  = $this->input->post("ddocument",true);
        $idocument  = $this->input->post("idocument",true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($ddocument == "") $ddocument = $this->uri->segment(5);
        if ($idocument == "") $idocument = $this->uri->segment(6);
        $dfrom      = $this->input->post("dfrom",true);
        $dto        = $this->input->post("dto",true);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'ddocument'     => $ddocument,
            'idocument'     => $idocument,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->id_company)->row(),
            'datadetail'    => $this->mmaster->datadetail($this->id_company,$ibagian)->result_array(),
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    /*----------  CARI BARANG  ----------*/
    public function barang() {
        $filter = [];
        $data = $this->mmaster->barang(str_replace("'","",$this->input->get('q')),$this->input->get('ibagian'), $this->input->get('ddocument') );
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id.'|'.$row->e_color_name,
                    'text' => $row->i_product_wip.' - '.$row->e_product_wipname.' ('.$row->e_color_name.')'
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }


    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE); 
        $idocument  = $this->input->post('idocument', TRUE);
        
        $eremarkh  = $this->input->post('eremarkh', TRUE);
        $idcompany = $this->id_company;
        $jml        = $this->input->post('xml', TRUE);

        $format     = DateTime::createFromFormat('d-m-Y', $this->input->post('ddocument', TRUE));
        $iperiode   = $format->format('Ym');
        $ddocument  = $format->format('Y-m-d');
        $this->db->trans_begin();
        $id = $this->mmaster->runningid();
        $this->mmaster->simpan($id,$idcompany,$ibagian,$idocument,$ddocument,$iperiode, $eremarkh);
        //$this->mmaster->hapusdetail($idcompany, $id);
        for ($i = 1; $i <= $jml; $i++) {
            $idmaterial = $this->input->post('idmaterial' . $i, TRUE);
            $idmaterial = explode('|', $idmaterial);
            $qty        = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
            $qty_repair = str_replace(",","",$this->input->post('nquantity_repair' . $i, TRUE));
            $eremark    = $this->input->post('eremark' . $i, TRUE);
            if ($idmaterial[0]!=null || $idmaterial[0]!='') {
                $this->mmaster->simpandetail($idcompany, $id, $idmaterial[0], $qty, $qty_repair, $eremark);
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        $this->load->view('pesan2', $data); 
    }

    public function changestatus(){
        $id         = $this->input->post('id', true);
        $istatus    = $this->input->post('istatus', true);
        $estatus    = $this->mmaster->estatus($istatus);
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

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);
       
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE); 
        $idocument  = $this->input->post('idocument', TRUE);        
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idcompany  = $this->id_company;
        $jml        = $this->input->post('xml', TRUE);

        $format     = DateTime::createFromFormat('d-m-Y', $this->input->post('ddocument', TRUE));
        $iperiode   = $format->format('Ym');
        $ddocument  = $format->format('Y-m-d');
        $this->db->trans_begin();
        $id = $this->input->post('id', TRUE);
        /* var_dump($_POST);
        die; */
        $this->mmaster->updateheader($id, $eremarkh);
        $this->mmaster->hapusdetail($id);
        for ($i = 1; $i <= $jml; $i++) {
            $idmaterial = $this->input->post('idmaterial' . $i, TRUE);
            $idmaterial = explode('|', $idmaterial);
            $qty = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
            $eremark   = $this->input->post('eremark' . $i, TRUE);
            if ($idmaterial[0]!=null || $idmaterial[0]!='') {
                $this->mmaster->simpandetail($idcompany, $id, $idmaterial[0], $qty, $eremark);            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        $this->load->view('pesan2', $data); 
    }
     

    public function approval(){
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);
       

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

     public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        // var_dump($id, $dfrom, $dto);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function export()
    {
        /** Parameter */
        /* $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $i_supplier = $this->input->post('i_supplier');
        $laporan = $this->input->post('laporan');
        $check = $this->input->post('check'); */
        $nama_file = "";
        /** End Parameter */

        /** Style And Create New Spreedsheet */
        $spreadsheet  = new Spreadsheet;
        $sharedTitle = new Style();
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        /* $conditional3 = new Conditional(); */
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
            [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
            ]
        );

        $sharedTitle->applyFromArray(
            [
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'size'   => 26
                ],
            ]
        );

        $sharedStyle1->applyFromArray(
            [
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'italic' => false,
                    'size'   => 14
                ],
            ]
        );

        $sharedStyle2->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => false,
                    'italic' => false,
                    'size'   => 11
                ],
                'borders' => [
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
            ]

        );

        $sharedStyle3->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'italic' => false,
                    'size'   => 11,
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );
        /** End Style */

        /** validation */
        $validation = $spreadsheet->getActiveSheet()->getCell("F1")->getDataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Input is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Number Value allowed");
        $validation->setFormula1(0);
        $validation->setFormula2(999999999);

        $abjad  = range('A', 'Z');
        $satu = 1;
        $dua = 2;
        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
            ->setCellValue("A2", "FORMAT UPLOAD STOCKOPNAME PACKING");
        $spreadsheet->getActiveSheet()->setTitle('Format Stockopname');
        $h = 3;
        $header = ['#', 'ID BARANG', 'KODE BARANG', 'NAMA BARANG', 'WARNA', 'SO (BAGUS)','SO (Repair)', 'KETERANGAN', 'ASAL'];
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
        }
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $dua);
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        // $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
        $j = 4;
        $x = 4;
        $no = 0;
        $sql = $this->mmaster->get_export_so();
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
                $no++;
                $isi = [
                    $no, $row->id, $row->i_product_base, $row->e_product_basename, $row->e_color_name, 0, 0, "", $row->name
                ];
                for ($i = 0; $i < count($isi); $i++) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    if (($abjad[$i] == 'F') or ($abjad[$i] == 'G')) {
                        $spreadsheet->getActiveSheet()->setDataValidation($abjad[$i].$j, $validation);
                    }
                }
                $j++;
            }
        }
        $y = $j - 1;
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        /** End Sheet */

        /** protection */
        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        $hrow = $spreadsheet->getActiveSheet(0)->getHighestDataRow('A');
        
        $spreadsheet->getActiveSheet()->getStyle("F4:H" . $y)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        

        $writer = new Xls($spreadsheet);
        $nama_file = "SO_Packing.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian = $this->input->post('ibagian', TRUE);
        $i_so = $this->input->post('i_so', TRUE);
        $ddocument = $this->input->post('ddocument', TRUE);
        $dfrom = $this->input->post('dfrom', TRUE);
        $dto = $this->input->post('dto', TRUE);
        $filename = $this->id_company . "_SO_" . $ddocument . ".xls";

        $config = array(
            'upload_path'   => "./import/soproduksi/packing/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $this->Logger->write('Upload File SO Packing : ' . $i_so);

            $param =  array(
                'ibagian'   => $ibagian,
                'i_so'      => $i_so,
                'ddocument' => $ddocument,
                'dfrom'     => $dfrom,
                'dto'       => $dto,
                'status'    => 'berhasil'
            );
            echo json_encode($param);
        } else {
            $param =  array(
                'status' => 'gagal'
            );
            echo json_encode($param);
        }
    }

    public function loadview()
    {
        $ibagian    = $this->uri->segment(4);
        $i_so       = $this->uri->segment(5);
        $ddocument  = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);

        $filename = $this->id_company . "_SO_" . $ddocument . ".xls";


        $inputFileName = "./import/soproduksi/packing/" . $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');

        $aray = array();
        for ($n = 4; $n <= $hrow; $n++) {
            $id_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
            $qty        = (int)$spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue();
            $qty_repair = (int)$spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue();
            if ((($qty <= 0) and ($qty_repair <= 0 )) or ($id_product == "")) {
                continue;
            }

            $company = $this->mmaster->get_company_by_product($id_product)->row();

            $aray[] = array(
                'id'                => $id_product,
                'i_product_wip'     => strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue()),
                'e_product_wipname' => strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue()),
                'e_color_name'      => strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue()),
                'qty'               => $qty,
                'qty_repair'        => $qty_repair,
                'e_remark'          => $spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue(),
                'company'           => $spreadsheet->getActiveSheet()->getCell('I' . $n)->getValue(),
                'id_company' => $company->id_company,
            );
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'ddocument'     => $ddocument,
            'idocument'     => $i_so,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->id_company)->row(),
            'datadetail'    => $aray,
        );
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }

    public function get_company_by_product()
    {
        $id_product = $this->input->get('id_product');

        $result = [];

        $query = $this->mmaster->get_company_by_product($id_product);
        if ($query->row() != null) {
            $result['data'] = $query->row();
        }

        echo json_encode($result);
    }
}
/* End of file Cform.php */