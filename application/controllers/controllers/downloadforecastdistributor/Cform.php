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

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20719';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->i_menu = '20719';
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        // var_dump($this->session->userdata('id_company'));
        // die();

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];


        $this->load->model($this->global['folder'] . '/mmaster');
    }   

    public function index(){

        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-01-' . date('Y');
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
            'folder' => $this->global['folder'],
            'title' => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformmain', $data);
    }

    function data()
    {
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

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post("ibagian", true);
        $idcustomer = $this->input->post("idcustomer", true);
        $tahun      = $this->input->post("tahun", true);
        $bulan      = $this->input->post("bulan", true);

        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);

        $id         = $this->input->post("id", true);
        $iclass     = $this->input->post("class", true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($idcustomer == "") $idcustomer = $this->uri->segment(5);
        if ($tahun == "") $tahun = $this->uri->segment(6);
        if ($bulan == "") $bulan = $this->uri->segment(7);

        //get dto and dfrom
        if ($dfrom == "") $dfrom = $this->uri->segment(8);
        if ($dto == "") $dto = $this->uri->segment(9);

        //get id forecast
        if ($id == "") $id = $this->uri->segment(10);

        if ($iclass == NULL || $iclass == '') {
            $iclass = $this->uri->segment(11);
            if ($iclass == null || $iclass == '') {
                $iclass = 'all';
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'ibagian'       => $ibagian,
            'idcustomer'    => $idcustomer,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'id'            => $id,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iclass'        => $iclass,
            'class'         => $this->db->get('tr_class_product'),
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'customer'      => $this->mmaster->get_customer($idcustomer, $this->session->userdata('id_company'), $tahun, $bulan)->row(),
            'head'          => $this->mmaster->dataheader($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan, $id)->row(),
            'datadetail'    => $this->mmaster->datadetail($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan, $id, $iclass)->result_array(),
        );

        //var_dump($this->mmaster->getpartnerbyid($partner));
        //die();
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        //var_dump($this->mmaster->cek_datadet($dso)->result_array());
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function export_excel(){

        $idcompany   = $this->id_company;
        $bulan      = $this->uri->segment(4);
        $tahun      = $this->uri->segment(5);
        $periode    = $tahun.$bulan;

        $query = $this->mmaster->dataexportdetail($idcompany,$periode);
        
        if ($query) {

            $spreadsheet = new Spreadsheet;
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(
                [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
                ]
            );

            $sharedStyle1->applyFromArray(
                [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'right' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]
            );

            $sharedStyle2->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Arial',
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
                'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]
        );
        $spreadsheet->getDefaultStyle()
        ->getFont()
        ->setName('Calibri')
        ->setSize(9);
        foreach(range('A','K') as $columnID) {
          $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
            $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Forcast Periode : '.$tahun.$bulan);
            $spreadsheet->getActiveSheet()->setTitle('FC'.$tahun.$bulan);
            $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
            $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A2', 'No')
                      ->setCellValue('B2', 'Kode')
                      ->setCellValue('C2', 'Nama Barang')
                      ->setCellValue('D2', 'Periode')
                      ->setCellValue('E2', 'Distributor')
                      ->setCellValue('F2', 'Tanggal Input')
                      ->setCellValue('G2', 'Kategori Penjualan')
                      ->setCellValue('H2', 'Harga')
                      ->setCellValue('I2', 'Rata-rata OP(3 bln)')
                      ->setCellValue('J2', 'Jumlah FC')
                      ->setCellValue('K2', 'Keterangan');
          
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:L2');

          $kolom = 3;
          $nomor = 1;
          foreach($query->result() as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $row->i_product_base)
                        ->setCellValue('C' . $kolom, $row->e_product_basename. ' - '.$row->e_color_name)
                        ->setCellValue('D' . $kolom, $row->periode)
                        ->setCellValue('E' . $kolom, $row->e_customer_name)
                        ->setCellValue('F' . $kolom, $row->d_entry)
                        ->setCellValue('G' . $kolom, $row->e_class_name)
                        ->setCellValue('H' . $kolom, $row->v_harga)
                        ->setCellValue('I' . $kolom, $row->n_rata2)
                        ->setCellValue('J' . $kolom, $row->n_quantity)
                        ->setCellValue('K' . $kolom, $row->e_remark);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':K'.$kolom);
            $spreadsheet->getActiveSheet()
            ->getStyle('G'.$kolom)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $spreadsheet->getActiveSheet()
            ->getStyle('J'.$kolom)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "Report Forecast Distributor_".$bulan.$tahun.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
        }
    }

}