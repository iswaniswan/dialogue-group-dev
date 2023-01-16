<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '21201';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->doc_qe = $data[0]['doc_qe'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'] . '/mmaster');
    }


    public function index()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Export All " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'supplier'      => $this->db->order_by('e_supplier_name', 'ASC')->get_where("tr_supplier", ['f_status' => 't', 'id_company' => $this->id_company]),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vform', $data);
    }

    public function export_laporan()
    {
        /** Parameter */
        $date_from = formatYmd($this->input->post('date_from'));
        $date_to = formatYmd($this->input->post('date_to'));
        $i_supplier = $this->input->post('i_supplier');
        $laporan = $this->input->post('laporan');
        $check = $this->input->post('check');
        $nama_file = "";
        $ada_data = true;
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

        $abjad  = range('A', 'Z');
        $satu = 1;
        $dua = 2;
        $tiga = 3;
        $empat = 4;
        $lima = 5;
        if ($laporan == 'exp_pembelian') {
            /** Start Sheet Credit */
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN PEMBELIAN")
                ->setCellValue("A3", "Jenis Pembelian : Credit")
                ->setCellValue("A4", "Kategori Pembelian : Pembelian Bahan Baku/Pembantu")
                ->setCellValue("A5", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Laporan Pembelian Credit');
            $h = 7;
            $header = ['#', 'KODE SUPPLIER', 'SUPPLIER', 'NO SJ', 'TGL SJ', 'KODE BARANG', 'LIST BARANG', 'KATEGORI', 'SUB KATEGORI', 'COA', 'HARGA EXCLUDE (RP)', 'QTY', 'SATUAN', 'TOTAL', 'DISKON', 'DPP', 'PPN', 'SUBTOTAL HUTANG DAGANG', 'BAHAN BAKU', 'BAHAN PEMBANTU', 'BARANG WIP', 'BIAYA LAINNYA'];
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $empat . ":" . $abjad[count($header) - 1] . $empat);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $lima . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 8;
            $x = 8;
            $xx = 0;
            $no = 0;
            $sj = "";
            $credit = $this->mmaster->get_laporan_pembelian_credit($date_from, $date_to, $check, $i_supplier);
            if ($credit->num_rows() > 0) {
                foreach ($credit->result() as $row) {
                    $bahanbaku = 0;
                    if ($row->i_coa == '510-10100') {
                        $bahanbaku = $row->dpp + $row->ppn;
                    }
                    $bahanpembantu = 0;
                    if ($row->i_coa == '510-10200') {
                        $bahanpembantu = $row->dpp + $row->ppn;
                    }
                    $wip = 0;
                    if ($row->i_coa == '510-10400') {
                        $wip = $row->dpp + $row->ppn;
                    }
                    $no++;
                    $isi = [
                        $no, $row->i_supplier, $row->e_supplier_name, $row->i_sj_supplier, Date::PHPToExcel($row->d_sj_supplier),
                        $row->i_material, $row->e_material_name, $row->e_nama_kelompok, $row->e_type_name, $row->i_coa, $row->v_price, $row->n_quantity,
                        $row->e_satuan_name, $row->total, $row->discount, $row->dpp, $row->ppn, $row->hutang_dagang, $bahanbaku, $bahanpembantu, $wip
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    if ($sj == $row->i_supplier . $row->i_sj_supplier) {
                        $xx++;
                        $spreadsheet->getActiveSheet()->mergeCells($abjad[17] . ($j - $xx) . ":" . $abjad[17] . $j);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[17] . ($j - $xx), "=SUM(" . $abjad[15] . ($j - $xx) . ":" . $abjad[16] . ($j) . ")");
                    } else {
                        $xx = 0;
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[17] . $j, "=SUM(" . $abjad[15] . $j . ":" . $abjad[16] . $j . ")");
                    }
                    $sj = $row->i_supplier . $row->i_sj_supplier;
                    $j++;
                }
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[4] . $x . ":" . $abjad[4] . $y)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[10] . $j);
            if ($credit->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, "GRAND TOTAL")
                    ->setCellValue($abjad[11] . $j, "=SUM(" . $abjad[11] . $x . ":" . $abjad[11] . $y . ")")
                    ->setCellValue($abjad[13] . $j, "=SUM(" . $abjad[13] . $x . ":" . $abjad[13] . $y . ")")
                    ->setCellValue($abjad[14] . $j, "=SUM(" . $abjad[14] . $x . ":" . $abjad[14] . $y . ")")
                    ->setCellValue($abjad[15] . $j, "=SUM(" . $abjad[15] . $x . ":" . $abjad[15] . $y . ")")
                    ->setCellValue($abjad[16] . $j, "=SUM(" . $abjad[16] . $x . ":" . $abjad[16] . $y . ")")
                    ->setCellValue($abjad[17] . $j, "=SUM(" . $abjad[17] . $x . ":" . $abjad[17] . $y . ")")
                    ->setCellValue($abjad[18] . $j, "=SUM(" . $abjad[18] . $x . ":" . $abjad[18] . $y . ")")
                    ->setCellValue($abjad[19] . $j, "=SUM(" . $abjad[19] . $x . ":" . $abjad[19] . $y . ")")
                    ->setCellValue($abjad[20] . $j, "=SUM(" . $abjad[20] . $x . ":" . $abjad[20] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[10] . $x . ":" . $abjad[10] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[13] . $x . ":" . $abjad[19] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            /** End Sheet Credit */

            /** Start Sheet Cash */
            $spreadsheet->createSheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN PEMBELIAN")
                ->setCellValue("A3", "Jenis Pembelian : Cash")
                ->setCellValue("A4", "Kategori Pembelian : Pembelian Bahan Baku/Pembantu")
                ->setCellValue("A5", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Laporan Pembelian Cash');
            $h = 7;
            $header = ['#', 'KODE SUPPLIER', 'SUPPLIER', 'NO SJ', 'TGL SJ', 'KODE BARANG', 'LIST BARANG', 'KATEGORI', 'SUB KATEGORI', 'COA', 'HARGA EXCLUDE (RP)', 'QTY', 'SATUAN', 'TOTAL', 'DISKON', 'DPP', 'PPN', 'SUBTOTAL HUTANG DAGANG', 'BAHAN BAKU', 'BAHAN PEMBANTU', 'BARANG WIP', 'BIAYA LAINNYA'];
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $empat . ":" . $abjad[count($header) - 1] . $empat);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $lima . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 8;
            $x = 8;
            $xx = 0;
            $no = 0;
            $sj = "";
            $cash = $this->mmaster->get_laporan_pembelian_cash($date_from, $date_to, $check, $i_supplier);
            if ($cash->num_rows() > 0) {
                foreach ($cash->result() as $row) {
                    $bahanbaku = 0;
                    if ($row->i_coa == '510-10100') {
                        $bahanbaku = $row->dpp + $row->ppn;
                    }
                    $bahanpembantu = 0;
                    if ($row->i_coa == '510-10200') {
                        $bahanpembantu = $row->dpp + $row->ppn;
                    }
                    $wip = 0;
                    if ($row->i_coa == '510-10400') {
                        $wip = $row->dpp + $row->ppn;
                    }
                    $no++;
                    $isi = [
                        $no, $row->i_supplier, $row->e_supplier_name, $row->i_sj_supplier, Date::PHPToExcel($row->d_sj_supplier),
                        $row->i_material, $row->e_material_name, $row->e_nama_kelompok, $row->e_type_name, $row->i_coa, $row->v_price, $row->n_quantity,
                        $row->e_satuan_name, $row->total, $row->discount, $row->dpp, $row->ppn, $row->hutang_dagang, $bahanbaku, $bahanpembantu, $wip
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    if ($sj == $row->i_supplier . $row->i_sj_supplier) {
                        $xx++;
                        $spreadsheet->getActiveSheet()->mergeCells($abjad[17] . ($j - $xx) . ":" . $abjad[17] . $j);
                        $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[17] . ($j - $xx), "=SUM(" . $abjad[15] . ($j - $xx) . ":" . $abjad[16] . ($j) . ")");
                    } else {
                        $xx = 0;
                        $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[17] . $j, "=SUM(" . $abjad[15] . $j . ":" . $abjad[16] . $j . ")");
                    }
                    $sj = $row->i_supplier . $row->i_sj_supplier;
                    $j++;
                }
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[4] . $x . ":" . $abjad[4] . $y)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[10] . $j);
            if ($cash->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[0] . $j, "GRAND TOTAL")
                    ->setCellValue($abjad[11] . $j, "=SUM(" . $abjad[11] . $x . ":" . $abjad[11] . $y . ")")
                    ->setCellValue($abjad[13] . $j, "=SUM(" . $abjad[13] . $x . ":" . $abjad[13] . $y . ")")
                    ->setCellValue($abjad[14] . $j, "=SUM(" . $abjad[14] . $x . ":" . $abjad[14] . $y . ")")
                    ->setCellValue($abjad[15] . $j, "=SUM(" . $abjad[15] . $x . ":" . $abjad[15] . $y . ")")
                    ->setCellValue($abjad[16] . $j, "=SUM(" . $abjad[16] . $x . ":" . $abjad[16] . $y . ")")
                    ->setCellValue($abjad[17] . $j, "=SUM(" . $abjad[17] . $x . ":" . $abjad[17] . $y . ")")
                    ->setCellValue($abjad[18] . $j, "=SUM(" . $abjad[18] . $x . ":" . $abjad[18] . $y . ")")
                    ->setCellValue($abjad[19] . $j, "=SUM(" . $abjad[19] . $x . ":" . $abjad[19] . $y . ")")
                    ->setCellValue($abjad[20] . $j, "=SUM(" . $abjad[20] . $x . ":" . $abjad[20] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[10] . $x . ":" . $abjad[10] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[13] . $x . ":" . $abjad[19] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            if ($credit->num_rows() <= 0 && $cash->num_rows() <= 0) {
                $ada_data = false;
            }
            /** End Sheet Cash */
            $nama_file = "Laporan_Pembelian_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_kartu') {
            $h = 6;
            // $header = ['Tanggal Faktur', 'Tanggal SJ', 'Uraian', 'No. Faktur/SJ', 'Saldo Awal', 'Pelunasan', 'D/N', 'Pembulatan', 'Pembelian', 'C/N', 'Pembulatan', 'Saldo Akhir'];
            $header = ['Tgl. Nota', 'No. Nota', 'Saldo Awal', 'Pelunasan', 'D/N', 'Pembulatan', 'Pembelian', 'C/N', 'Pembulatan', 'Saldo Akhir'];
            $get_supplier = $this->mmaster->get_supplier($date_from, $date_to, $i_supplier);
            if ($get_supplier->num_rows() > 0) {
                $index = 0;
                foreach ($get_supplier->result() as $key) {
                    $spreadsheet->createSheet();
                    $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
                    $spreadsheet->setActiveSheetIndex($index)
                        ->setCellValue("A$satu", "KARTU HUTANG")
                        ->setCellValue("A$empat", "Nama Supplier : $key->e_supplier_name")
                        ->setCellValue("H$empat", "Masa Pembayaran : 1 Bulan");
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedTitle, $abjad[0] . $satu);
                    $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
                    $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $empat . ":" . $abjad[4] . $empat);
                    $spreadsheet->getActiveSheet()->mergeCells($abjad[7] . $empat . ":" . $abjad[9] . $empat);
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $empat . ":" . $abjad[4] . $empat);
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[7] . $empat . ":" . $abjad[9] . $empat);
                    $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $empat . ":" . $abjad[2] . $empat)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                    $spreadsheet->getActiveSheet()->getStyle($abjad[7] . $empat . ":" . $abjad[9] . $empat)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                    $spreadsheet->getActiveSheet()->setTitle($key->e_supplier_name);
                    $j = 8;
                    for ($i = 0; $i < count($header); $i++) {
                        $spreadsheet->setActiveSheetIndex($index)->setCellValue($abjad[$i] . $h, $header[$i]);
                    }
                    $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
                    $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');

                    $j = 7;
                    $x = 7;
                    $no = 0;
                    $query2 = $this->mmaster->get_kartu_hutang($date_from, $date_to, $key->i_supplier, $check);
                    if ($query2->num_rows() > 0) {
                        foreach ($query2->result() as $row) {
                            $no++;
                            /* $isi = [
                                Date::PHPToExcel($row->d_faktur_supplier), Date::PHPToExcel($row->d_sj_supplier), $row->e_material_name, $row->faktur_sj,
                                '','','','',$row->v_total
                            ]; */
                            $isi = [
                                Date::PHPToExcel($row->d_nota), $row->i_nota, '', $row->v_total_bayar,
                                '', '', $row->v_total, '', '', $row->saldo_akhir
                            ];
                            for ($i = 0; $i < count($isi); $i++) {
                                $spreadsheet->setActiveSheetIndex($index)->setCellValue($abjad[$i] . $j, $isi[$i]);
                            }
                            $j++;
                        }
                    }
                    $y = $j - 1;
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
                    $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $x . ":" . $abjad[1] . $y)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
                    $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[1] . $j);
                    $spreadsheet->setActiveSheetIndex($index)->setCellValue($abjad[0] . $j, "TOTAL")
                        ->setCellValue($abjad[2] . $j, "=SUM(" . $abjad[2] . $x . ":" . $abjad[2] . $y . ")")
                        ->setCellValue($abjad[3] . $j, "=SUM(" . $abjad[3] . $x . ":" . $abjad[3] . $y . ")")
                        ->setCellValue($abjad[4] . $j, "=SUM(" . $abjad[4] . $x . ":" . $abjad[4] . $y . ")")
                        ->setCellValue($abjad[5] . $j, "=SUM(" . $abjad[5] . $x . ":" . $abjad[5] . $y . ")")
                        ->setCellValue($abjad[6] . $j, "=SUM(" . $abjad[6] . $x . ":" . $abjad[6] . $y . ")")
                        ->setCellValue($abjad[7] . $j, "=SUM(" . $abjad[7] . $x . ":" . $abjad[7] . $y . ")")
                        ->setCellValue($abjad[8] . $j, "=SUM(" . $abjad[8] . $x . ":" . $abjad[8] . $y . ")")
                        ->setCellValue($abjad[9] . $j, "=" . $abjad[9] . ($j - 1));
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                    $spreadsheet->getActiveSheet()->getStyle($abjad[2] . $x . ":" . $abjad[9] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                    $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                    $index++;
                }
            } else {
                $ada_data = false;
            }
            $nama_file = "Kartu_Hutang_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_opname') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN OPNAME HUTANG DAGANG")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Laporan Opname Hutang Dagang');

            $query3 = $this->mmaster->get_laporan_opname_hutang($date_from, $date_to, $check);
            $h = 5;
            $header = ['#', 'TGL FAKTUR', 'T.O.P', 'TGL JT. TEMPO', 'NOMOR SJ', 'KODE SUPPLIER', 'SUPPLIER', 'JENIS', 'TOTAL (RP.)', 'SUBTOTAL (RP.)'];
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);

            $supplier = '';
            $j = 6;
            $x = 6;
            $no = 0;
            $total = 0;
            if ($query3->num_rows() > 0) {
                foreach ($query3->result() as $row) {
                    $no++;
                    if ($supplier != '') {
                        if ($supplier != $row->e_supplier_name) {
                            $no = 1;

                            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, $supplier)->setCellValue($abjad[count($header) - 2] . $j, "TOTAL")->setCellValue($abjad[count($header) - 1] . $j, $total);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);

                            $total = 0;
                            $j = $j + 1;
                        }
                    }
                    $isi = [
                        $no, Date::PHPToExcel($row->d_faktur_supplier), $row->n_top, Date::PHPToExcel($row->d_jatuh_tempo),
                        $row->i_sj_supplier, $row->i_supplier, $row->e_supplier_name, $row->e_supplier_group_name, $row->v_sisa
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[1] . $j . ":" . $abjad[1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[3] . $j . ":" . $abjad[3] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[8] . $j . ":" . $abjad[8] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                    }
                    $supplier = $row->e_supplier_name;
                    $total += $row->v_sisa;

                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, $supplier)->setCellValue($abjad[count($header) - 2] . $j, "TOTAL")->setCellValue($abjad[count($header) - 1] . $j, $total);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[count($header) - 1] . $x . ":" . $abjad[count($header) - 1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /** End Sheet */
            $nama_file = "Opname_Hutang_Dagang_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_rekapitulasi') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "REKAPITULASI HUTANG DAGANG")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Rekapitulasi Hutang Dagang');
            $header = ['#', 'KODE SUPPLIER', 'SUPPLIER', 'SALDO AWAL', 'PEMBELIAN BAHAN BAKU / PEMBANTU', 'PEMBELIAN LAIN-LAIN', 'PEMBELIAN MAKLOON', 'RETUR', 'PELUNASAN A/P', 'C/N', 'PEMBULATAN', 'SALDO AKHIR'];
            $h = 5;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 6;
            $x = 6;
            $no = 0;
            $query4 = $this->mmaster->get_rekapitulasi($date_from, $date_to, $check);
            if ($query4->num_rows() > 0) {
                foreach ($query4->result() as $row) {
                    $no++;
                    $isi = [
                        $no, $row->i_supplier, $row->e_supplier_name, $row->saldo_awal, $row->pembelian, $row->pembelian_lain,
                        $row->pembelian_makloon, $row->retur, $row->pelunasan, $row->cn, $row->pembulatan, $row->saldo_akhir
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[2] . $j);
            if ($query4->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, "TOTAL")
                    ->setCellValue($abjad[3] . $j, "=SUM(" . $abjad[3] . $x . ":" . $abjad[3] . $y . ")")
                    ->setCellValue($abjad[4] . $j, "=SUM(" . $abjad[4] . $x . ":" . $abjad[4] . $y . ")")
                    ->setCellValue($abjad[5] . $j, "=SUM(" . $abjad[5] . $x . ":" . $abjad[5] . $y . ")")
                    ->setCellValue($abjad[6] . $j, "=SUM(" . $abjad[6] . $x . ":" . $abjad[6] . $y . ")")
                    ->setCellValue($abjad[7] . $j, "=SUM(" . $abjad[7] . $x . ":" . $abjad[7] . $y . ")")
                    ->setCellValue($abjad[8] . $j, "=SUM(" . $abjad[8] . $x . ":" . $abjad[8] . $y . ")")
                    ->setCellValue($abjad[9] . $j, "=SUM(" . $abjad[9] . $x . ":" . $abjad[9] . $y . ")")
                    ->setCellValue($abjad[10] . $j, "=SUM(" . $abjad[10] . $x . ":" . $abjad[10] . $y . ")")
                    ->setCellValue($abjad[11] . $j, "=SUM(" . $abjad[11] . $x . ":" . $abjad[11] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[3] . $x . ":" . $abjad[11] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            /** End Sheet */
            $nama_file = "Rekapitulasi_Hutang_Dagang_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_buku') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "REKAPITULASI BUKU PEMBELIAN")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Rekapitulasi Buku Pembelian');
            $header = ['#', 'SUPPLIER', 'COA', 'AP', 'DPP', 'PPN', 'RETUR', 'PPH 21', 'PPH 23', 'SKB', 'TOTAL HUTANG DAGANG'];
            $h = 5;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 6;
            $x = 6;
            $no = 0;
            $query4 = $this->mmaster->get_rekapitulasi_buku($date_from, $date_to, $check);
            if ($query4->num_rows() > 0) {
                foreach ($query4->result() as $row) {
                    $no++;
                    $isi = [
                        $no, $row->e_supplier_name, $row->coa, $row->ap, $row->dpp, $row->ppn, $row->retur, $row->pph21, $row->pph23, $row->skb, $row->total_hutang
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[2] . $j);
            if ($query4->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, "TOTAL")
                    ->setCellValue($abjad[3] . $j, "=SUM(" . $abjad[3] . $x . ":" . $abjad[3] . $y . ")")
                    ->setCellValue($abjad[4] . $j, "=SUM(" . $abjad[4] . $x . ":" . $abjad[4] . $y . ")")
                    ->setCellValue($abjad[5] . $j, "=SUM(" . $abjad[5] . $x . ":" . $abjad[5] . $y . ")")
                    ->setCellValue($abjad[6] . $j, "=SUM(" . $abjad[6] . $x . ":" . $abjad[6] . $y . ")")
                    ->setCellValue($abjad[7] . $j, "=SUM(" . $abjad[7] . $x . ":" . $abjad[7] . $y . ")")
                    ->setCellValue($abjad[8] . $j, "=SUM(" . $abjad[8] . $x . ":" . $abjad[8] . $y . ")")
                    ->setCellValue($abjad[9] . $j, "=SUM(" . $abjad[9] . $x . ":" . $abjad[9] . $y . ")")
                    ->setCellValue($abjad[10] . $j, "=SUM(" . $abjad[10] . $x . ":" . $abjad[10] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[3] . $x . ":" . $abjad[count($header) - 1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            /** End Sheet */
            $nama_file = "Rekapitulasi_Buku_Pembelian_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_opvsbtb') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN OP VS BTB")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Laporan OP vs BTB');
            $header = [
                '#', 'TGL. OP', 'NO. OP', 'KODE SUPPLIER', 'NAMA SUPPLIER', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'QTY OP', 'QTY BTB/SJ', 'SISA OP'
            ];
            $h = 5;
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);

            $supplier = '';
            $j = 6;
            $x = 6;
            $no = 0;
            $total = 0;
            $total_sj = 0;
            $total_sisa = 0;
            $total_n_quantity = 0;
            $total_n_quantity_sj = 0;
            $total_n_quantity_sisa = 0;
            $query5 = $this->mmaster->get_op_vs_btb($date_from, $date_to, $i_supplier);
            if ($query5->num_rows() > 0) {
                foreach ($query5->result() as $row) {
                    $no++;
                    $total_n_quantity += $row->n_quantity;
                    $total_n_quantity_sj += $row->n_quantity_sj;
                    $total_n_quantity_sisa += $row->n_quantity_sisa;
                    if ($supplier != '') {
                        if ($supplier != $row->e_supplier_name) {
                            $no = 1;

                            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 5] . $j);
                            $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue($abjad[0] . $j, $supplier)
                                ->setCellValue($abjad[count($header) - 4] . $j, "TOTAL")
                                ->setCellValue($abjad[count($header) - 3] . $j, $total)
                                ->setCellValue($abjad[count($header) - 2] . $j, $total_sj)
                                ->setCellValue($abjad[count($header) - 1] . $j, $total_sisa);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
                            $total = 0;
                            $total_sj = 0;
                            $total_sisa = 0;
                            $j = $j + 1;
                        }
                    }
                    $isi = [
                        $no, Date::PHPToExcel($row->d_op), $row->i_op, $row->i_supplier, $row->e_supplier_name, $row->i_material, $row->e_material_name, $row->e_satuan_name, $row->n_quantity, $row->n_quantity_sj, $row->n_quantity_sisa
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[1] . $j . ":" . $abjad[1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
                    }
                    $supplier = $row->e_supplier_name;
                    $total += $row->n_quantity;
                    $total_sj += $row->n_quantity_sj;
                    $total_sisa += $row->n_quantity_sisa;

                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 5] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, $supplier)
                ->setCellValue($abjad[count($header) - 4] . $j, "TOTAL")
                ->setCellValue($abjad[count($header) - 3] . $j, $total)
                ->setCellValue($abjad[count($header) - 2] . $j, $total_sj)
                ->setCellValue($abjad[count($header) - 1] . $j, $total_sisa);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $j = $j + 1;
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 4] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, "TOTAL KESELURUHAN")
                ->setCellValue($abjad[count($header) - 3] . $j, $total_n_quantity)
                ->setCellValue($abjad[count($header) - 2] . $j, $total_n_quantity_sj)
                ->setCellValue($abjad[count($header) - 1] . $j, $total_n_quantity_sisa);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /** End Sheet */
            $nama_file = "Laporan_OP_vs_BTB_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_budgeting_realisasi') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "BUDGETING VS REALISASI")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Budgeting vs Realisasi');
            $header = ['#', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'QTY BUDGETING', 'RP BUDGETING', 'QTY REALISASI', 'RP REALISASI'];
            $h = 5;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 6;
            $x = 6;
            $no = 0;
            $query6 = $this->mmaster->get_budgeting_realisasi($date_from, $date_to);
            if ($query6->num_rows() > 0) {
                foreach ($query6->result() as $row) {
                    $no++;
                    $isi = [
                        $no, $row->i_material, $row->e_material_name, $row->e_satuan_name, $row->n_budgeting_qty, $row->n_budgeting_rp, $row->n_realisasi_qty, $row->n_realisasi_rp
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[3] . $j);
            if ($query6->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, "TOTAL")
                    ->setCellValue($abjad[4] . $j, "=SUM(" . $abjad[4] . $x . ":" . $abjad[4] . $y . ")")
                    ->setCellValue($abjad[5] . $j, "=SUM(" . $abjad[5] . $x . ":" . $abjad[5] . $y . ")")
                    ->setCellValue($abjad[6] . $j, "=SUM(" . $abjad[6] . $x . ":" . $abjad[6] . $y . ")")
                    ->setCellValue($abjad[7] . $j, "=SUM(" . $abjad[7] . $x . ":" . $abjad[7] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[5] . $x . ":" . $abjad[5] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[7] . $x . ":" . $abjad[7] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            /** End Sheet */
            $nama_file = "Budgeting_vs_Realisasi_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_btb_faktur') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN BTB VS FAKTUR")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Laporan BTB vs FAKTUR');
            $header = [
                '#', 'TGL. BTB', 'NO. BTB', 'SJ SUPPLIER', 'KODE SUPPLIER', 'NAMA SUPPLIER', 'TGL. NOTA', 'NO. NOTA', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'QTY BTB', 'QTY FAKUTR'
            ];
            $h = 5;
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);

            $supplier = '';
            $j = 6;
            $x = 6;
            $no = 0;
            $total_btb = 0;
            $total_faktur = 0;
            $total_qty_btb = 0;
            $total_qty_faktur = 0;
            $query7 = $this->mmaster->get_btb_vs_faktur($date_from, $date_to, $i_supplier);
            if ($query7->num_rows() > 0) {
                foreach ($query7->result() as $row) {
                    $no++;
                    $total_qty_btb += $row->n_quantity_btb;
                    $total_qty_faktur += $row->n_quantity_faktur;
                    if ($supplier != '') {
                        if ($supplier != $row->e_supplier_name) {
                            $no = 1;

                            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 4] . $j);
                            $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue($abjad[0] . $j, $supplier)
                                ->setCellValue($abjad[count($header) - 3] . $j, "TOTAL")
                                ->setCellValue($abjad[count($header) - 2] . $j, $total_btb)
                                ->setCellValue($abjad[count($header) - 1] . $j, $total_faktur);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
                            $total_btb = 0;
                            $total_faktur = 0;
                            $j = $j + 1;
                        }
                    }

                    if ($row->d_btb == '' || $row->d_btb == null) {
                        $row->d_btb = '';
                    } else {
                        $row->d_btb = Date::PHPToExcel($row->d_btb);
                    }

                    if ($row->d_nota == '' || $row->d_nota == null) {
                        $row->d_nota = '';
                    } else {
                        $row->d_nota = Date::PHPToExcel($row->d_nota);
                    }
                    $isi = [
                        $no, $row->d_btb, trim($row->i_btb), trim($row->i_sj_supplier), trim($row->i_supplier), trim($row->e_supplier_name), $row->d_nota,
                        trim($row->i_nota), trim($row->i_material), upper(trim($row->e_material_name)), trim($row->e_satuan_name), $row->n_quantity_btb, $row->n_quantity_faktur
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[1] . $j . ":" . $abjad[1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[6] . $j . ":" . $abjad[6] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
                    }
                    $supplier = $row->e_supplier_name;
                    $total_btb += $row->n_quantity_btb;
                    $total_faktur += $row->n_quantity_faktur;

                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 4] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, $supplier)
                ->setCellValue($abjad[count($header) - 3] . $j, "TOTAL")
                ->setCellValue($abjad[count($header) - 2] . $j, $total_btb)
                ->setCellValue($abjad[count($header) - 1] . $j, $total_faktur);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $j = $j + 1;
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, "TOTAL KESELURUHAN")
                ->setCellValue($abjad[count($header) - 2] . $j, $total_qty_btb)
                ->setCellValue($abjad[count($header) - 1] . $j, $total_qty_faktur);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /** End Sheet */
            $nama_file = "Laporan_BTB_vs_FAKTUR_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_rekap_supplier') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "REKAP PEMBELIAN PER SUPPLIER")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Rekap Pembelian Persupplier');
            $header = [
                '#', 'KODE SUPPLIER', 'NAMA SUPPLIER', 'TOTAL'
            ];
            $h = 5;
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);

            $group = '';
            $j = 6;
            $x = 6;
            $no = 0;
            $total = 0;
            $grand_total = 0;
            $query7 = $this->mmaster->get_rekap_persupplier($date_from, $date_to);
            if ($query7->num_rows() > 0) {
                foreach ($query7->result() as $row) {
                    $no++;
                    $grand_total += $row->v_total;
                    if ($group != '') {
                        if ($group != $row->e_supplier_group_name) {
                            $no = 1;

                            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
                            $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue($abjad[0] . $j, $group)
                                ->setCellValue($abjad[count($header) - 2] . $j, "TOTAL")
                                ->setCellValue($abjad[count($header) - 1] . $j, $total);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
                            $total = 0;
                            $j = $j + 1;
                        }
                    }
                    $isi = [
                        $no, trim($row->i_supplier), trim($row->e_supplier_name), $row->v_total
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                    }
                    $group = $row->e_supplier_group_name;
                    $total += $row->v_total;

                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, $group)
                ->setCellValue($abjad[count($header) - 2] . $j, "TOTAL")
                ->setCellValue($abjad[count($header) - 1] . $j, $total);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $j = $j + 1;
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 2] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, "TOTAL KESELURUHAN")
                ->setCellValue($abjad[count($header) - 1] . $j, $grand_total);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle($abjad[count($header) - 1] . $x . ":" . $abjad[count($header) - 1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /** End Sheet */
            $nama_file = "Rekap_Pembelian_PerSupplier_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_btb_dan_faktur') {
            /** Start Sheet BTB */
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN PEMBELIAN BTB")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Laporan Pembelian BTB');
            $h = 5;
            $header = ['#', 'TGL. BTB', 'NO. BTB', 'NO SJ SUPPLIER', 'KODE SUPPLIER', 'NAMA SUPPLIER', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'QTY', 'HARGA'];
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 6;
            $x = 6;
            $no = 0;
            $query_btb = $this->mmaster->get_btb($date_from, $date_to, $i_supplier);
            if ($query_btb->num_rows() > 0) {
                foreach ($query_btb->result() as $row) {
                    $no++;
                    $isi = [
                        $no, Date::PHPToExcel($row->d_btb), $row->i_btb, trim($row->i_sj_supplier), $row->i_supplier, $row->e_supplier_name,
                        $row->i_material, upper(trim($row->e_material_name)), upper(trim($row->e_satuan_name)), $row->n_quantity, $row->v_price
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    $j++;
                }
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[1] . $x . ":" . $abjad[1] . $y)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
            if ($query_btb->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, "GRAND TOTAL")
                    ->setCellValue($abjad[count($header) - 2] . $j, "=SUM(" . $abjad[count($header) - 2] . $x . ":" . $abjad[count($header) - 2] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[count($header) - 1] . $x . ":" . $abjad[count($header) - 1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            /** End Sheet BTB */

            /** Start Sheet Faktur */
            $spreadsheet->createSheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN PEMBELIAN FAKTUR")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Laporan Pembelian Faktur');
            $h = 5;
            $header = ['#', 'TGL. NOTA', 'NO. NOTA', 'KODE SUPPLIER', 'NAMA SUPPLIER', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'QTY', 'HARGA'];
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 6;
            $x = 6;
            $no = 0;
            $query_faktur = $this->mmaster->get_faktur($date_from, $date_to, $i_supplier);
            if ($query_faktur->num_rows() > 0) {
                foreach ($query_faktur->result() as $row) {
                    $no++;
                    $isi = [
                        $no, Date::PHPToExcel($row->d_nota), $row->i_nota, $row->i_supplier, $row->e_supplier_name,
                        trim($row->i_material), upper(trim($row->e_material_name)), upper(trim($row->e_satuan_name)), $row->n_quantity, $row->v_price
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    $j++;
                }
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[1] . $x . ":" . $abjad[1] . $y)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
            if ($query_faktur->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[0] . $j, "GRAND TOTAL")
                    ->setCellValue($abjad[count($header) - 2] . $j, "=SUM(" . $abjad[count($header) - 2] . $x . ":" . $abjad[count($header) - 2] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[count($header) - 1] . $x . ":" . $abjad[count($header) - 1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            if ($query_btb->num_rows() <= 0 && $query_faktur->num_rows() <= 0) {
                $ada_data = false;
            }
            /** End Sheet Faktur */
            $nama_file = "Laporan_BTB_dan_Faktur_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_per_kategori') {
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "REKAP PEMBELIAN PER KATEGORI")
                ->setCellValue("A3", "Periode : $date_from s/d $date_to");
            $spreadsheet->getActiveSheet()->setTitle('Rekap Pembelian PerKategori');
            $header = [
                '#', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'KATEGORI BARANG', 'SUB KATEGORI BARANG', 'QTY', 'HARGA'
            ];
            $h = 5;
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);

            $group = '';
            $j = 6;
            $x = 6;
            $no = 0;
            $total = 0;
            $grand_total = 0;
            $query8 = $this->mmaster->get_kategori($date_from, $date_to);
            if ($query8->num_rows() > 0) {
                foreach ($query8->result() as $row) {
                    $no++;
                    $grand_total += $row->n_quantity;
                    if ($group != '') {
                        if ($group != $row->e_nama_group_barang) {
                            $no = 1;

                            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 4] . $j);
                            $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue($abjad[0] . $j, $group)
                                ->setCellValue($abjad[count($header) - 3] . $j, "TOTAL")
                                ->setCellValue($abjad[count($header) - 2] . $j, $total);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
                            $total = 0;
                            $j = $j + 1;
                        }
                    }
                    $isi = [
                        $no, trim($row->i_material), upper(trim($row->e_material_name)), upper(trim($row->e_satuan_name)),
                        upper(trim($row->e_nama_group_barang)), upper(trim($row->e_nama_kelompok)), $row->n_quantity, $row->v_price
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
                    }
                    $group = $row->e_nama_group_barang;
                    $total += $row->n_quantity;

                    $j++;
                }
            } else {
                $ada_data = false;
            }
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 4] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, $group)
                ->setCellValue($abjad[count($header) - 3] . $j, "TOTAL")
                ->setCellValue($abjad[count($header) - 2] . $j, $total);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $j = $j + 1;
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 3] . $j);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue($abjad[0] . $j, "TOTAL KESELURUHAN")
                ->setCellValue($abjad[count($header) - 2] . $j, $grand_total);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle($abjad[count($header) - 1] . $x . ":" . $abjad[count($header) - 1] . $j)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            /** End Sheet */
            $nama_file = "Rekap_Pembelian_PerKategori_" . $date_from . "_" . $date_to . ".xls";
        } elseif ($laporan == 'exp_pp') {
            /** Start Sheet PP */
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                ->setCellValue("A2", "LAPORAN PEMBELIAN PP")
                ->setCellValue("A3", "Periode : " . format_bulan($date_from) . " s/d " . format_bulan($date_to));
            $spreadsheet->getActiveSheet()->setTitle('Laporan Pembelian PP');
            $h = 5;
            $header = ['#', 'TGL. PP', 'NO. PP', 'BAGIAN', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'QTY PP', 'QTY OP', 'QTY SISA PP BELUM OP'];
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            }
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $lima);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $tiga . ":" . $abjad[count($header) - 1] . $tiga);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
            $j = 6;
            $x = 6;
            $no = 0;
            $query_pp = $this->mmaster->get_pp($date_from, $date_to);
            if ($query_pp->num_rows() > 0) {
                foreach ($query_pp->result() as $row) {
                    $no++;
                    $isi = [
                        $no, Date::PHPToExcel($row->d_pp), $row->i_pp, $row->e_bagian_name,
                        $row->i_material, upper(trim($row->e_material_name)), upper(trim($row->e_satuan_name)), $row->n_quantity, ($row->n_quantity - $row->n_sisa), $row->n_sisa
                    ];
                    for ($i = 0; $i < count($isi); $i++) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                    }
                    $j++;
                }
            }
            $y = $j - 1;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
            $spreadsheet->getActiveSheet()->getStyle($abjad[1] . $x . ":" . $abjad[1] . $y)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DD_MMMM_YYYY);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[count($header) - 4] . $j);
            if ($query_pp->num_rows() > 0) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue($abjad[0] . $j, "GRAND TOTAL")
                    ->setCellValue($abjad[count($header) - 3] . $j, "=SUM(" . $abjad[count($header) - 3] . $x . ":" . $abjad[count($header) - 3] . $y . ")")
                    ->setCellValue($abjad[count($header) - 2] . $j, "=SUM(" . $abjad[count($header) - 2] . $x . ":" . $abjad[count($header) - 2] . $y . ")")
                    ->setCellValue($abjad[count($header) - 1] . $j, "=SUM(" . $abjad[count($header) - 1] . $x . ":" . $abjad[count($header) - 1] . $y . ")");
            }
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
            $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            /** End Sheet PP */
            $nama_file = "Laporan_PP_belum_OP_" . $date_from . "_" . $date_to . ".xls";
        }
        $writer = new Xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_start();
        $writer->save('php://output');
        $exceldata = ob_get_contents();
        ob_end_clean();
        $response =  array(
            'file'      => "data:application/vnd.ms-excel;base64," . base64_encode($exceldata),
            'nama_file' => $nama_file,
            'data'      => $ada_data,
        );
        die(json_encode($response));
    }

    public function view_laporan()
    {
        /** Parameter */
        $date_from = formatYmd($this->input->get('date_from'));
        $date_to = formatYmd($this->input->get('date_to'));
        $i_supplier = $this->input->get('i_supplier');
        $laporan = $this->input->get('laporan');
        $title = replace_space($this->input->get('title'));
        $check = $this->input->get('check');
        /** End Parameter */
        /** Query Data */
        /* if ($laporan == 'exp_pembelian') {
            $query = $this->mmaster->get_laporan_pembelian_credit($date_from, $date_to, $check, $i_supplier)->result();
        } elseif ($laporan == 'exp_kartu') {
            $query = $this->mmaster->get_kartu_hutang($date_from, $date_to, $i_supplier, $check)->result();
        } elseif ($laporan == 'exp_opname') {
            $query = $this->mmaster->get_laporan_opname_hutang($date_from, $date_to, $check)->result();            
        } elseif ($laporan == 'exp_rekapitulasi') {
            $query = $this->mmaster->get_rekapitulasi($date_from, $date_to, $check)->result();
        } elseif ($laporan == 'exp_buku') {
            $query = $this->mmaster->get_rekapitulasi_buku($date_from, $date_to, $check)->result();
        } elseif ($laporan == 'exp_opvsbtb') {
            $query = $this->mmaster->get_op_vs_btb($date_from, $date_to, $i_supplier)->result();
        } elseif ($laporan == 'exp_budgeting_realisasi') {
            $query = $this->mmaster->get_budgeting_realisasi($date_from, $date_to)->result();
        } elseif ($laporan == 'exp_btb_faktur') {
            $query = $this->mmaster->get_btb_vs_faktur($date_from, $date_to, $i_supplier)->result();
        } elseif ($laporan == 'exp_rekap_supplier') {
            $query = $this->mmaster->get_rekap_persupplier($date_from, $date_to)->result();
        } elseif ($laporan == 'exp_btb_dan_faktur') {
            $query = $this->mmaster->get_btb($date_from, $date_to, $i_supplier)->result();
            // $query_faktur = $this->mmaster->get_faktur($date_from, $date_to, $i_supplier)->result();
        } elseif ($laporan == 'exp_per_kategori') {
            $query = $this->mmaster->get_kategori($date_from, $date_to)->result();
        } elseif ($laporan == 'exp_pp') {
            $query = $this->mmaster->get_pp($date_from, $date_to)->result();
        } */
        $response =  array(
            'folder'    => $this->global['folder'],
            /* 'data'      => $query, */
            'laporan'   => $laporan,
            'title'     => $title,
            'd_from'    => $date_from,
            'd_to'      => $date_to,
            'i_supplier' => $i_supplier
        );
        $this->load->view($this->global['folder'] . '/vform_laporan', $response);
    }

    public function data()
    {
        header('Content-type: application/json; charset=utf-8');
        /** Parameter GET */
        $search = $this->input->get('search');
        $offset = $this->input->get('offset');
        $limit = $this->input->get('limit');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        $i_supplier = $this->input->get('i_supplier');
        $laporan = $this->input->get('laporan');
        $type = $this->input->get('type');
        /** End Parameter */

        $data   = [];
        $rows   = $this->mmaster->data($search, $offset, $limit, $date_from, $date_to, $i_supplier, $laporan, $type, false)->num_rows();
        $query  = $this->mmaster->data($search, $offset, $limit, $date_from, $date_to, $i_supplier, $laporan, $type, true);
        $detail = [];
        if ($query->num_rows() > 0) {
            $no = 0;
            if ($laporan == 'exp_pembelian') {
                foreach ($query->result() as $key) {
                    $no++;
                    $bahanbaku = 0;
                    if ($key->i_coa == '510-10100') {
                        $bahanbaku = $key->dpp + $key->ppn;
                    }
                    $bahanpembantu = 0;
                    if ($key->i_coa == '510-10200') {
                        $bahanpembantu = $key->dpp + $key->ppn;
                    }
                    $wip = 0;
                    if ($key->i_coa == '510-10400') {
                        $wip = $key->dpp + $key->ppn;
                    }
                    $detail[] = array(
                        'id'                => $no,
                        'i_supplier'        => $key->i_supplier,
                        'e_supplier_name'   => $key->e_supplier_name,
                        'i_sj_supplier'     => $key->i_sj_supplier,
                        'd_sj_supplier'     => $key->d_sj_supplier,
                        'i_material'        => $key->i_material,
                        'e_material_name'   => $key->e_material_name,
                        'e_nama_kelompok'   => $key->e_nama_kelompok,
                        'e_type_name'       => $key->e_type_name,
                        'i_coa'             => $key->i_coa,
                        'v_price'           => $key->v_price,
                        'n_quantity'        => $key->n_quantity,
                        'e_satuan_name'     => $key->e_satuan_name,
                        'total'             => number_format($key->total),
                        'discount'          => number_format($key->discount),
                        'dpp'               => number_format($key->dpp),
                        'ppn'               => number_format($key->ppn),
                        'hutang_dagang'     => number_format($key->hutang_dagang),
                        'bahan_baku'        => number_format($bahanbaku),
                        'bahan_pembantu'    => number_format($bahanpembantu),
                        'wip'               => number_format($wip),
                        'lainnaya'          => 0,
                    );
                }
            } elseif ($laporan == 'exp_kartu') {
                foreach ($query->result() as $key) {
                    $no++;
                    $detail[] = array(
                        'id'                => $no,
                        'e_supplier_name'   => $key->e_supplier_name,
                        'i_nota'            => $key->i_nota,
                        'd_nota'            => $key->d_nota,
                        'v_saldo_awal'      => number_format(0),
                        'v_pelunasan'       => number_format($key->v_total_bayar),
                        'v_dn'              => number_format(0),
                        'v_pembulatan_1'    => number_format(0),
                        'v_pembelian'       => number_format($key->v_total),
                        'v_cn'              => number_format(0),
                        'v_pembulatan_2'    => number_format(0),
                        'v_saldo_akhir'     => number_format($key->saldo_akhir),
                    );
                }
            } elseif ($laporan == 'exp_opname') {
                $supplier = '';
                $total = 0;
                foreach ($query->result() as $key) {
                    $no++;
                    if ($supplier != $key->e_supplier_name && $supplier != '') {
                        $no = 1;
                        $detail[] = array(
                            'id'                => '',
                            'd_faktur_supplier' => '',
                            'n_top' => '',
                            'd_jatuh_tempo' => '',
                            'i_sj_supplier' => '',
                            'i_supplier' => '',
                            'e_supplier_name' => '',
                            'e_supplier_group_name' => '',
                            'v_sisa' => '<b>TOTAL</b>',
                            'v_total' => '<b>' . number_format($total) . '</b>',
                        );
                        $total = 0;
                    }
                    $supplier = $key->e_supplier_name;
                    $total += $key->v_sisa;
                    $detail[] = array(
                        'id' => $no,
                        'd_faktur_supplier' => $key->d_faktur_supplier,
                        'n_top' => $key->n_top,
                        'd_jatuh_tempo' => $key->d_jatuh_tempo,
                        'i_sj_supplier' => $key->i_sj_supplier,
                        'i_supplier' => $key->i_supplier,
                        'e_supplier_name' => $key->e_supplier_name,
                        'e_supplier_group_name' => $key->e_supplier_group_name,
                        'v_sisa' => number_format($key->v_sisa),
                        'v_total' => ''
                    );
                }
                $detail[] = array(
                    'id'                => '',
                    'd_faktur_supplier' => '',
                    'n_top' => '',
                    'd_jatuh_tempo' => '',
                    'i_sj_supplier' => '',
                    'i_supplier' => '',
                    'e_supplier_name' => '',
                    'e_supplier_group_name' => '',
                    'v_sisa' => '<b>TOTAL</b>',
                    'v_total' => '<b>' . number_format($total) . '</b>',
                );
            } elseif ($laporan == 'exp_rekapitulasi') {
                foreach ($query->result() as $key) {
                    $no++;
                    $detail[] = array(
                        'id' => $no,
                        'i_supplier' => $key->i_supplier,
                        'e_supplier_name' => $key->e_supplier_name,
                        'v_saldo_awal' => number_format($key->saldo_awal),
                        'v_pembelian' => number_format($key->pembelian),
                        'v_pembelian_lain' => number_format($key->pembelian_lain),
                        'v_pembelian_makloon' => number_format($key->pembelian_makloon),
                        'v_retur' => number_format($key->retur),
                        'v_pelunasan' => number_format($key->pelunasan),
                        'v_cn' => number_format($key->cn),
                        'v_pembulatan' => number_format($key->pembulatan),
                        'v_saldo_akhir' => number_format($key->saldo_akhir),
                    );
                }
            } elseif ($laporan == 'exp_buku') {
                foreach ($query->result() as $key) {
                    $no++;
                    $detail[] = array(
                        'id' => $no,
                        'e_supplier_name' => $key->e_supplier_name,
                        'coa' => $key->coa,
                        'v_ap' => number_format($key->ap),
                        'v_dpp' => number_format($key->dpp),
                        'v_ppn' => number_format($key->ppn),
                        'v_retur' => number_format($key->retur),
                        'v_pph21' => number_format($key->pph21),
                        'v_pph23' => number_format($key->pph23),
                        'v_skb' => number_format($key->skb),
                        'v_total' => number_format($key->total_hutang),
                    );
                }
            } elseif ($laporan == 'exp_opvsbtb') {
                $supplier = '';
                $total = 0;
                $total_sj = 0;
                $total_sisa = 0;
                foreach ($query->result() as $key) {
                    $no++;
                    if ($supplier != $key->e_supplier_name && $supplier != '') {
                        $no = 1;
                        $detail[] = array(
                            'id' => '',
                            'd_op' => '',
                            'i_op' => '',
                            'i_supplier' => '',
                            'e_supplier_name' => '',
                            'i_material' => '',
                            'e_material_name' => "<b>$supplier</b>",
                            'e_satuan_name' => '<b>TOTAL</b>',
                            'n_quantity' => '<b>' . number_format($total) . '</b>',
                            'n_quantity_sj' => '<b>' . number_format($total_sj) . '</b>',
                            'n_quantity_sisa' => '<b>' . number_format($total_sisa) . '</b>',
                        );
                        $total = 0;
                        $total_sj = 0;
                        $total_sisa = 0;
                    }
                    $supplier = $key->e_supplier_name;
                    $total += $key->n_quantity;
                    $total_sj += $key->n_quantity_sj;
                    $total_sisa += $key->n_quantity_sisa;
                    $detail[] = array(
                        'id' => $no,
                        'd_op' => $key->d_op,
                        'i_op' => $key->i_op,
                        'i_supplier' => $key->i_supplier,
                        'e_supplier_name' => $key->e_supplier_name,
                        'i_material' => $key->i_material,
                        'e_material_name' => $key->e_material_name,
                        'e_satuan_name' => $key->e_satuan_name,
                        'n_quantity' => $key->n_quantity,
                        'n_quantity_sj' => $key->n_quantity_sj,
                        'n_quantity_sisa' => $key->n_quantity_sisa,
                    );
                }
                $detail[] = array(
                    'id' => '',
                    'd_op' => '',
                    'i_op' => '',
                    'i_supplier' => '',
                    'e_supplier_name' => '',
                    'i_material' => '',
                    'e_material_name' => "<b>$supplier</b>",
                    'e_satuan_name' => '<b>TOTAL</b>',
                    'n_quantity' => '<b>' . number_format($total) . '</b>',
                    'n_quantity_sj' => '<b>' . number_format($total_sj) . '</b>',
                    'n_quantity_sisa' => '<b>' . number_format($total_sisa) . '</b>',
                );
            } elseif ($laporan == 'exp_btb_faktur') {
                $supplier = '';
                $total_btb = 0;
                $total_nota = 0;
                foreach ($query->result() as $key) {
                    $no++;
                    if ($supplier != $key->e_supplier_name && $supplier != '') {
                        $no = 1;
                        $detail[] = array(
                            'id' => '',
                            'd_btb' => '',
                            'i_btb' => '',
                            'i_sj_supplier' => '',
                            'i_supplier' => '',
                            'e_supplier_name' => '',
                            'd_nota' => '',
                            'i_nota' => "",
                            'i_material' => '',
                            'e_material_name' => "<b>$supplier</b>",
                            'e_satuan_name' => '<b>TOTAL</b>',
                            'n_quantity_btb' => '<b>' . number_format($total_btb) . '</b>',
                            'n_quantity_nota' => '<b>' . number_format($total_nota) . '</b>',
                        );
                        $total_btb = 0;
                        $total_nota = 0;
                    }
                    $supplier = $key->e_supplier_name;
                    $total_btb += $key->n_quantity_btb;
                    $total_nota += $key->n_quantity_nota;
                    $detail[] = array(
                        'id' => $no,
                        'd_btb' => $key->d_btb,
                        'i_btb' => $key->i_btb,
                        'i_sj_supplier' => $key->i_sj_supplier,
                        'i_supplier' => $key->i_supplier,
                        'e_supplier_name' => $key->e_supplier_name,
                        'd_nota' => $key->d_nota,
                        'i_nota' => $key->i_nota,
                        'i_material' => $key->i_material,
                        'e_material_name' => $key->e_material_name,
                        'e_satuan_name' => $key->e_satuan_name,
                        'n_quantity_btb' => number_format($key->n_quantity_btb),
                        'n_quantity_nota' => number_format($key->n_quantity_nota),
                    );
                }
                $detail[] = array(
                    'id' => '',
                    'd_btb' => '',
                    'i_btb' => '',
                    'i_sj_supplier' => '',
                    'i_supplier' => '',
                    'e_supplier_name' => '',
                    'd_nota' => '',
                    'i_nota' => "",
                    'i_material' => '',
                    'e_material_name' => "<b>$supplier</b>",
                    'e_satuan_name' => '<b>TOTAL</b>',
                    'n_quantity_btb' => '<b>' . number_format($total_btb) . '</b>',
                    'n_quantity_nota' => '<b>' . number_format($total_nota) . '</b>',
                );
            } elseif ($laporan == 'exp_rekap_supplier') {
                $group = '';
                $total = 0;
                foreach ($query->result() as $key) {
                    $no++;
                    if ($group != $key->e_supplier_group_name && $group != '') {
                        $no = 1;
                        $detail[] = array(
                            'id' => '',
                            'i_supplier' => "<b>$group</b>",
                            'e_supplier_name' => '<b>TOTAL</b>',
                            'v_total' => '<b>' . number_format($total) . '</b>',
                        );
                        $total = 0;
                    }
                    $group = $key->e_supplier_group_name;
                    $total += $key->v_total;
                    $detail[] = array(
                        'id' => $no,
                        'i_supplier' => $key->i_supplier,
                        'e_supplier_name' => $key->e_supplier_name,
                        'v_total' => number_format($key->v_total),
                    );
                }
                $detail[] = array(
                    'id' => '',
                    'i_supplier' => "<b>$group</b>",
                    'e_supplier_name' => '<b>TOTAL</b>',
                    'v_total' => '<b>' . number_format($total) . '</b>',
                );
            } elseif ($laporan == 'exp_btb_dan_faktur') {
                if ($type=='btb') {
                    foreach ($query->result() as $key) {
                        $no++;
                        $detail[] = array(
                            'id' => $no,
                            'd_btb' => $key->d_btb,
                            'i_btb' => $key->i_btb,
                            'i_sj_supplier' => $key->i_sj_supplier,
                            'i_supplier' => $key->i_supplier,
                            'e_supplier_name' => $key->e_supplier_name,
                            'i_material' => $key->i_material,
                            'e_material_name' => $key->e_material_name,
                            'e_satuan_name' => $key->e_satuan_name,
                            'n_qty' => $key->n_quantity,
                            'v_price' => $key->v_price,
                        );
                    }
                }else{
                    foreach ($query->result() as $key) {
                        $no++;
                        $detail[] = array(
                            'id' => $no,
                            'd_nota' => $key->d_nota,
                            'i_nota' => $key->i_nota,
                            'i_supplier' => $key->i_supplier,
                            'e_supplier_name' => $key->e_supplier_name,
                            'i_material' => $key->i_material,
                            'e_material_name' => $key->e_material_name,
                            'e_satuan_name' => $key->e_satuan_name,
                            'n_qty' => $key->n_quantity,
                            'v_price' => $key->v_price,
                        );
                    }
                }
            } elseif ($laporan == 'exp_per_kategori') {
                $group = '';
                $total = 0;
                foreach ($query->result() as $key) {
                    $no++;
                    if ($group != $key->e_nama_group_barang && $group != '') {
                        $no = 1;
                        $detail[] = array(
                            'id' => '',
                            'i_material' => '',
                            'e_material_name' => '',
                            'e_satuan_name' => '',
                            'e_kelompok_barang_name' => "<b>$group</b>",
                            'e_type_name' => '<b>TOTAL</b>',
                            'n_qty' => '<b>' . number_format($total) . '</b>',
                            'v_price' => ''
                        );
                        $total = 0;
                    }
                    $group = $key->e_nama_group_barang;
                    $total += $key->n_quantity;
                    $detail[] = array(
                        'id' => $no,
                        'i_material' => $key->i_material,
                        'e_material_name' => $key->e_material_name,
                        'e_satuan_name' => $key->e_satuan_name,
                        'e_kelompok_barang_name' => $key->e_nama_group_barang,
                        'e_type_name' => $key->e_nama_kelompok,
                        'n_qty' => number_format($key->n_quantity),
                        'v_price' => number_format($key->v_price),
                    );
                }
                $detail[] = array(
                    'id' => '',
                    'i_material' => '',
                    'e_material_name' => '',
                    'e_satuan_name' => '',
                    'e_kelompok_barang_name' => "<b>$group</b>",
                    'e_type_name' => '<b>TOTAL</b>',
                    'n_qty' => '<b>' . number_format($total) . '</b>',
                    'v_price' => ''
                );
            } elseif ($laporan == 'exp_pp') {
                foreach ($query->result() as $key) {
                    $n_op = ($key->n_quantity - $key->n_sisa);
                    $no++;
                    $detail[] = array(
                        'id' => $no,
                        'd_pp' => $key->d_pp,
                        'i_pp' => $key->i_pp,
                        'e_bagian_name' => $key->e_bagian_name,
                        'i_material' => $key->i_material,
                        'e_material_name' => $key->e_material_name,
                        'e_satuan_name' => $key->e_satuan_name,
                        'n_quantity' => $key->n_quantity,
                        'n_op' => $n_op,
                        'n_sisa' => $key->n_sisa,
                    );
                }
            }
        }
        $data = array(
            'total' => $rows,
            'totalNotFiltered' => $rows,
            "rows" => $detail,
        );
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
/* End of file Cform.php */