<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;


class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010303';

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
        $year = $this->input->post('year');        
        if ($year == null) {
            $year =  date('Y');
        }

        $month = $this->input->post('month');
        if ($month == null) {
            $month = date('m');
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'year' => $year,
            'month' => $month
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $year = $this->uri->segment(4);
        $month = $this->uri->segment(5);
		echo $this->mmaster->data($this->i_menu, $this->global['folder'], $year, $month);
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
            'year' => $this->uri->segment(4),
            'month' => $this->uri->segment(5),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
	}

    public function area(){
        $filter = [];
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->area($cari);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_area,
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

    public function customer(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->customer($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_customer_name,
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

    public function salesman(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->salesman($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_sales,
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

    public function brand(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->brand($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_brand_name,                    
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
	
	public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea 		    = $this->input->post('iarea', TRUE);
        $icustomer 	    = $this->input->post('icustomer[]', TRUE);
        $isalesman 	    = $this->input->post('id_salesman', TRUE);
        $ibrand 		= $this->input->post('ibrand', TRUE);
        $bl             = $this->input->post('bulan', TRUE);
        $th             = $this->input->post('tahun', TRUE);
        $iperiode       = $th.$bl;
        $idcompany      = $this->session->userdata('id_company');
        $id             = '';
        $items = $this->input->post('items');

        $data = [
            'sukses'    => false,
            'kode'      => null
        ];

        if ((isset($items)) and (count($items) > 0)) {
            $this->mmaster->update_batch($id, $iarea, $icustomer, $isalesman, $ibrand, $iperiode, $idcompany, $items);
            $data['sukses'] = true;
        } else {
            $this->db->trans_begin();

            if (is_array($icustomer) || is_object($icustomer)) {
                foreach($icustomer AS $customer){
                    $cekada = $this->mmaster->cek_data($iarea, $customer, $isalesman, $ibrand, $iperiode, $idcompany);
                    if($cekada->num_rows() > 0){
                        $data = array(
                            'sukses' => false
                        );
                    }else{
                        $id = $this->mmaster->runningid();
                        $this->mmaster->insert($id, $iarea, $customer, $isalesman, $ibrand, $iperiode, $idcompany);
                    }
                }
            }
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();

                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$id);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $id
                );
            }
        }
        
        $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id_salesman = $this->uri->segment('4');
        $e_periode     = $this->uri->segment('5');
        $idcompany   = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            // 'data'          => $this->mmaster->get_data($id, $idcompany)->row(),
            'data' => $this->mmaster->get_data($id_salesman, $e_periode)->row(),
            'detail' => $this->mmaster->get_all_customer_salesman($id_salesman, $e_periode),
            'periode'       => $e_periode,
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $isalesman      = $this->input->post('id_salesman', TRUE);
        $ibrand         = $this->input->post('ibrand', TRUE);
        $bl             = $this->input->post('bulan', TRUE);
        $th             = $this->input->post('tahun', TRUE);
        $iperiode       = $th.$bl;
        $idcompany      = $this->session->userdata('id_company');
        $items = $this->input->post('items');

        if ($iarea != '' && $isalesman != '' && $iperiode != ''){
            $this->db->trans_begin();

            // $this->mmaster->update($id, $iarea, $icustomer, $isalesman, $ibrand, $iperiode, $idcompany);
            $this->mmaster->update_batch($id, $iarea, $icustomer, $isalesman, $ibrand, $iperiode, $idcompany, $items);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$id);
                
                $data = array(
                    'sukses'    => true,
                    'kode'      => $id
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

        $id_salesman          = $this->uri->segment('4');
        $e_periode     = $this->uri->segment('5');
        $idcompany   = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            // 'data'          => $this->mmaster->get_data($id, $idcompany)->row(),
            'data' => $this->mmaster->get_data($id_salesman, $e_periode)->row(),
            'detail' => $this->mmaster->get_all_customer_salesman($id_salesman, $e_periode),
            'periode'       => $e_periode,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function status()
    {
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

    private function get_text_periode($periode)
    {
        $year = substr($periode, 0, 4);
        $month = substr($periode, -2);
        
        $array_bulan = getBulan();

        return $array_bulan[$month] . ' ' . $year;
    }

    public function export()
    {
        $id_salesman = $this->input->get('id_salesman');
        $e_periode = $this->input->get('e_periode');
        $id_company = $this->session->userdata('id_company');

        $text_periode = $this->get_text_periode($e_periode);

        $query_salesman = $this->mmaster->get_salesman($id_salesman);
        $salesman = $query_salesman->row();

        $all_customer_salesman = $this->mmaster->get_all_customer_salesman($id_salesman, $e_periode);
        $all_customer = $this->mmaster->get_customer_export(null, $id_company);        

        /** Style And Create New Spreedsheet */
        $spreadsheet  = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0);
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

        $sharedStyle1->applyFromArray([
                'font' => [
                    'name'  => 'Calibri',
                    'bold'  => false,
                    'italic' => false,
                    'size'  => 10
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
        ]);

        $sharedStyle2->applyFromArray(
            [
                'font' => [
                    'name'  => 'Calibri',
                    'bold'  => false,
                    'italic' => false,
                    'size'  => 10
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]
        );

        $sharedStyle3->applyFromArray(
            [
                'font' => [
                    'name'  => 'Calibri',
                    'bold'  => false,
                    'italic' => false,
                    'size'  => 10
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]
        );

        $abjad  = range('A', 'Z');
        $satu = 1;
        $dua = 2;

        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A1", "FORMAT UPLOAD CUSTOMER SALESMAN")
            ->setCellValue("A2", "NAMA SALESMAN : $salesman->e_sales, PERIODE: $text_periode");
            
        $spreadsheet->getActiveSheet()->setTitle('Format Upload');
        $h = 3;
        $header = ['#', 'ID CUSTOMER', 'KODE CUSTOMER', 'NAMA CUSTOMER', 'AREA', 'KOTA', 'ALAMAT', 'PILIH'];
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
        }

        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $dua);
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)
                                        ->getFill()
                                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()
                                        ->setRGB('CEE7FF');
        
        $j = 4;
        $x = 4;
        $no = 0;
        
        
        foreach ($all_customer->result() as $row) {
            $no++;
            
            $default_pilih = 'NON AKTIF';
            foreach ($all_customer_salesman->result() as $result) {
                if ($result->id_customer == $row->id) {
                    $default_pilih = 'AKTIF';
                }
            }

            $isi = [
                $no, $row->id, $row->i_customer, $row->e_customer_name, $row->e_area, $row->e_city_name, $row->e_customer_address, $default_pilih
            ];
            for ($i = 0; $i < count($isi); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
            }
            $j++;
        }

        $y = $j - 1;
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        foreach (range('A','H') as $col) {
            if ($col == 'G') {                
                /** set width column address */
                $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(40);
                continue;
            }
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        /** hide column id_customer */
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setCollapsed(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setVisible(false);

        /** input validation dropdown */
        $validation_dropdown = $spreadsheet->getActiveSheet()->getCell('H4')->getDataValidation();
        $validation_dropdown->setType( DataValidation::TYPE_LIST );
        $validation_dropdown->setErrorStyle( DataValidation::STYLE_INFORMATION );
        $validation_dropdown->setAllowBlank(false);
        $validation_dropdown->setShowInputMessage(true);
        $validation_dropdown->setShowErrorMessage(true);
        $validation_dropdown->setShowDropDown(true);
        $validation_dropdown->setErrorTitle('Input error');
        $validation_dropdown->setError('Input Salah');
        $validation_dropdown->setPromptTitle('Pilih');
        $validation_dropdown->setPrompt('Pilih Status Aktif');
        $validation_dropdown->setFormula1('"NON AKTIF, AKTIF"');

        $spreadsheet->getActiveSheet()->setDataValidation("H5:H$y", $validation_dropdown);

        /** disable edit */
        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true); 
        $spreadsheet->getActiveSheet()->getStyle("H5:H$y")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        /** set autofilter */
        $spreadsheet->getActiveSheet()->setAutoFilter("A3:H$y");

        $writer = new Xls($spreadsheet);
        $nama_file = "Format_Upload_Customer_salesman.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    public function load_view()
    {
        $filename = "Format_Upload_Customer_salesman.xls";

        $config = array(
            'upload_path'   => "./import/master/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        $result = [
            'status' => 'gagal',
            'error' => $this->upload->display_errors(),
        ];

        if ($this->upload->do_upload("userfile") == false) {
            echo json_encode($result);
            return;
        } 

        $inputFileName = "import/master/". $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');

        $data = [];
        for ($n = 4; $n <= $hrow; $n++) {
            $pilih = $spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue();
            if (strtolower($pilih) != 'aktif') {
                continue;
            }

            $data[] = [
                'id_customer' => $spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue(),
                'i_customer' => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
                'e_customer_name' => $spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue(),
                'e_area' => $spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue(),
                'e_city_name' => $spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue(),
                'e_customer_address' => $spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue() ?? '',
                'pilih' => $pilih
            ];
        }

        $result = [
            'status' => 'berhasil',
            'data' => $data
        ];

        echo json_encode($result);
    }

}