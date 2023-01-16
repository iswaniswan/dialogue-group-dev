<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
/* use PhpOffice\PhpSpreadsheet\Style\Fill; */
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment; */
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090104';

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
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'] . '/mmaster');
    }

    public function index()
    {
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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );


        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
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

    public function bagianpengirim()
    {
        $filter = [];
        $data   = $this->mmaster->bagianpengirim(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->i_bagian,
                'text'  => $row->e_bagian_name,
            );
        }
        echo json_encode($filter);
    }

    public function referensi()
    {
        $filter = [];
        $data   = $this->mmaster->referensi(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id,
                'text'  => $row->i_document . ' - ' . $row->i_periode,
            );
        }
        echo json_encode($filter);
    }

    public function getdataitem()
    {
        header("Content-Type: application/json", true);
        $idreff    = $this->input->post('idreff');
        // $ipengirim = $this->input->post('ipengirim');
        $jml = $this->mmaster->getdataitem($idreff);
        $data = array(
            'datahead'   => $this->mmaster->getdataheader($idreff)->row(),
            'jmlitem'    => $jml->num_rows(),
            'dataitem'   => $this->mmaster->getdataitem($idreff)->result_array()
        );
        echo json_encode($data);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => ' List ' . $this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "IP-" . date('ym') . "-123456"
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibonm        = $this->input->post('idocument', TRUE);
        $ikodemaster  = $this->input->post('ibagian', TRUE);
        $dbonm        = $this->input->post('ddocument', TRUE);
        if ($dbonm) {
            $tmp   = explode('-', $dbonm);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $dbonm = $year . '-' . $month . '-' . $day;
        }

        // $iasal        = $this->input->post('ipengirim', TRUE);
        $ireff        = $this->input->post('ireff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        if ($ibonm != ''  && $dbonm != '' && $ikodemaster != '' && $ireff != '') {
            $cekkode = $this->mmaster->cek_kode($ibonm, $ikodemaster);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_begin();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $ibonm, $dbonm, $ikodemaster, $ireff, $eremark);
                for ($x = 0; $x <= $jml; $x++) {
                    $idproduct         = $this->input->post('idproduct' . $x, TRUE);
                    $nquantitywipmasuk = str_replace(",", ".", $this->input->post('nquantitywipsisa' . $x, TRUE));
                    $i = 0;
                    if ($idproduct != "" || $idproduct != NULL) {
                        foreach ($this->input->post("id_product_wip[]", TRUE) as $id_product_wip) {
                            if ($idproduct ==  $id_product_wip) {
                                $id_fccutting_item = $this->input->post("id_fccutting_item[]", TRUE)[$i];
                                $id_material       = $this->input->post("id_material[]", TRUE)[$i];
                                $e_bagian          = $this->input->post("e_bagian[]", TRUE)[$i];
                                $n_qty_wip         = str_replace(",", "", $this->input->post("n_qty_wip[]", TRUE)[$i]);
                                $n_gelar           = str_replace(",", "", $this->input->post("n_gelar[]", TRUE)[$i]);
                                $n_set             = str_replace(",", "", $this->input->post("n_set[]", TRUE)[$i]);
                                $n_panjang_gelar   = str_replace(",", "", $this->input->post("n_panjang_gelar[]", TRUE)[$i]);
                                $n_panjang_kain    = str_replace(",", "", $this->input->post("n_panjang_kain[]", TRUE)[$i]);
                                $f_auto_cutter     = $this->input->post("f_auto[]", TRUE)[$i];
                                $f_badan           = $this->input->post("f_bdn[]", TRUE)[$i];
                                $f_print           = $this->input->post("f_prnt[]", TRUE)[$i];
                                $f_bordir          = $this->input->post("f_brdr[]", TRUE)[$i];
                                $f_quilting        = $this->input->post("f_qltn[]", TRUE)[$i];
                                $edesc             = $this->input->post("edesc[]", TRUE)[$i];
                                if ($id_material != "") {
                                    $this->mmaster->insertdetail($id, $ireff, $id_product_wip, $id_material, $id_fccutting_item, $e_bagian, $n_gelar, $n_set, $n_panjang_gelar, $n_panjang_kain, $f_auto_cutter, $edesc, $n_qty_wip);
                                }
                            }
                            $i++;
                        }
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
                        'kode'   => $ibonm,
                        'id'     => $id,
                    );
                }
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result()
        );
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result()
        );


        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id           = $this->input->post('id', TRUE);
        $ibonm        = $this->input->post('idocument', TRUE);
        $ibonmold     = $this->input->post('idocumentold', TRUE);
        $ikodemaster  = $this->input->post('ibagian', TRUE);
        $dbonm        = $this->input->post('ddocument', TRUE);
        if ($dbonm) {
            $tmp   = explode('-', $dbonm);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $dbonm = $year . '-' . $month . '-' . $day;
        }

        $ireff        = $this->input->post('ireff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($ibonm != '' && $ikodemaster != '' && $dbonm != '') {
            $cekkode = $this->mmaster->cek_kodeedit($ibonm, $ibonmold, $ikodemaster);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_begin();
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
                $this->mmaster->updateheader($id, $ikodemaster, $ibonm, $dbonm, $eremark, $ireff);
                $this->mmaster->deletedetail($id);

                for ($x = 0; $x <= $jml; $x++) {
                    $idproduct         = $this->input->post('idproduct' . $x, TRUE);
                    $nquantitywipmasuk = str_replace(",", ".", $this->input->post('nquantitywipsisa' . $x, TRUE));
                    $i = 0;
                    if ($idproduct != "" || $idproduct != NULL) {
                        foreach ($this->input->post("id_product_wip[]", TRUE) as $id_product_wip) {
                            if ($idproduct ==  $id_product_wip) {
                                $id_fccutting_item = $this->input->post("id_fccutting_item[]", TRUE)[$i];
                                $id_material       = $this->input->post("id_material[]", TRUE)[$i];
                                $e_bagian          = $this->input->post("e_bagian[]", TRUE)[$i];
                                $n_gelar           = $this->input->post("n_gelar[]", TRUE)[$i];
                                $n_set             = $this->input->post("n_set[]", TRUE)[$i];
                                $n_panjang_gelar   = $this->input->post("n_panjang_gelar[]", TRUE)[$i];
                                $n_panjang_kain    = $this->input->post("n_panjang_kain[]", TRUE)[$i];
                                $f_auto_cutter     = $this->input->post("f_auto[]", TRUE)[$i];
                                $edesc             = $this->input->post("edesc[]", TRUE)[$i];
                                if ($id_material != "") {
                                    $this->mmaster->insertdetail($id, $ireff, $id_product_wip, $id_material, $id_fccutting_item, $e_bagian, $n_gelar, $n_set, $n_panjang_gelar, $n_panjang_kain, $f_auto_cutter, $edesc);
                                }
                            }
                            $i++;
                        }
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
                        'kode'   => $ibonm,
                        'id'     => $id,
                    );
                }
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(6),
            'dto'        => $this->uri->segment(7),
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function export_excel()
    {
        /* $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        } */
        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);
        $id         = $this->input->post("id", true);
        if ($id == "") $id = $this->uri->segment(4);
        if ($dfrom == "") $dfrom = $this->uri->segment(5);
        if ($dto == "") $dto = $this->uri->segment(6);
        $query = $this->mmaster->cek_datadetail($id);

        if ($query->num_rows() > 0) {

            $spreadsheet = new Spreadsheet;
            $title = new Style();
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
                ]
            );

            $title->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => true,
                        'italic' => false,
                        'size'  => 12
                    ],
                ]
            );

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
            $spreadsheet->getDefaultStyle()
                ->getFont()
                ->setName('Calibri')
                ->setSize(9);
            foreach (range('A', 'L') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'IP Cutting');
            $spreadsheet->getActiveSheet()->duplicateStyle($title, 'A1');
            $spreadsheet->getActiveSheet()->setTitle('IP Cutting');
            $spreadsheet->getActiveSheet()->mergeCells("A1:R1");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'Kode Barang')
                ->setCellValue('C2', 'Nama Barang')
                ->setCellValue('D2', 'Warna')
                ->setCellValue('E2', 'Qty')
                ->setCellValue('F2', 'Kode Material')
                ->setCellValue('G2', 'Nama Material')
                ->setCellValue('H2', 'Bagian')
                ->setCellValue('I2', 'Gelar')
                ->setCellValue('J2', 'Set')
                ->setCellValue('K2', 'Panjang Gelar')
                ->setCellValue('L2', 'Panjang Kain')
                ->setCellValue('M2', 'Auto Cutter')
                ->setCellValue('N2', 'Badan')
                ->setCellValue('O2', 'Print')
                ->setCellValue('P2', 'Bordir')
                ->setCellValue('Q2', 'Quilting')
                ->setCellValue('R2', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:R2');
            $sheet = $spreadsheet->getActiveSheet();
            $validation = $sheet->getCell("M3")->getDataValidation();
            $validation
                ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                ->setFormula1('"TRUE,FALSE"')
                ->setAllowBlank(false)
                ->setShowDropDown(true)
                ->setShowInputMessage(true)
                ->setPromptTitle("Note")
                ->setPrompt("Must select one from the drop down options.")
                ->setShowErrorMessage(true)
                ->setErrorStyle(
                    \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
                )
                ->setErrorTitle("Invalid option")
                ->setError("Select one from the drop down list.");

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $f_auto_cutter = ($row->f_auto_cutter == 't') ? 'Auto Cutter' : 'Manual';
                /* $f_auto_cutter = ($row->f_auto_cutter == 't') ? 'TRUE' : 'FALSE'; */
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, removeEmoji($row->i_product_wip))
                    ->setCellValue('C' . $kolom, removeEmoji($row->e_product_wipname))
                    ->setCellValue('D' . $kolom, removeEmoji($row->e_color_name))
                    ->setCellValue('E' . $kolom, $row->n_qty_wip)
                    ->setCellValue('F' . $kolom, removeEmoji($row->i_material))
                    ->setCellValue('G' . $kolom, removeEmoji($row->e_material_name))
                    ->setCellValue('H' . $kolom, removeEmoji($row->e_bagian))
                    ->setCellValue('I' . $kolom, $row->n_gelar)
                    ->setCellValue('J' . $kolom, $row->n_set)
                    ->setCellValue('K' . $kolom, $row->n_panjang_gelar)
                    ->setCellValue('L' . $kolom, $row->n_panjang_kain)
                    ->setCellValue('M' . $kolom, $f_auto_cutter)
                    ->setCellValue('N' . $kolom, '')
                    ->setCellValue('O' . $kolom, '')
                    ->setCellValue('P' . $kolom, '')
                    ->setCellValue('Q' . $kolom, '')
                    ->setCellValue('R' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':R' . $kolom);
                $kolom++;
                $nomor++;
            }
            $sheet->setDataValidation("M3:M" . $kolom, $validation);
            $writer = new Xls($spreadsheet);
            $nama_file = "IP_Cutting.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        } else {
            echo "<center><h1>Tidak Ada Data :)</h1></center>";
        }
    }

    /* Form Upload IP Cutting */
    public function upload()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Upload " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagianpembuat()->result(),
            'number'     => "IP-" . date('ym') . "-123456"
        );

        $this->Logger->write('Membuka Menu Upload ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformupload', $data);
    }

    public function download_file()
    {
        /* $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        } */
        $id         = $this->input->post("id", true);
        if ($id == "") $id = $this->uri->segment(4);
        $query = $this->mmaster->getdataitem($id);
        if ($query->num_rows() > 0) {

            $spreadsheet = new Spreadsheet;
            $title = new Style();
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
                ]
            );

            $title->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => true,
                        'italic' => false,
                        'size'  => 12
                    ],
                ]
            );

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
            $spreadsheet->getDefaultStyle()
                ->getFont()
                ->setName('Calibri')
                ->setSize(9);
            foreach (range('A', 'X') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'IP Cutting');
            $spreadsheet->getActiveSheet()->duplicateStyle($title, 'A1');
            $spreadsheet->getActiveSheet()->setTitle('IP Cutting');
            $spreadsheet->getActiveSheet()->mergeCells("A1:W1");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'ID PC')
                ->setCellValue('C2', 'ID Barang')
                ->setCellValue('D2', 'Kode Barang')
                ->setCellValue('E2', 'Nama Barang')
                ->setCellValue('F2', 'Warna')
                ->setCellValue('G2', 'Qty')
                ->setCellValue('H2', 'ID Material')
                ->setCellValue('I2', 'Kode Material')
                ->setCellValue('J2', 'Nama Material')
                ->setCellValue('K2', 'Kategori')
                ->setCellValue('L2', 'Sub Kategori')
                ->setCellValue('M2', 'Bagian')
                ->setCellValue('N2', 'Gelar')
                ->setCellValue('O2', 'Set')
                ->setCellValue('P2', 'Panjang Gelar')
                ->setCellValue('Q2', 'Panjang Kain')
                ->setCellValue('R2', 'Auto Cutter')
                ->setCellValue('S2', 'Badan')
                ->setCellValue('T2', 'Print')
                ->setCellValue('U2', 'Bordir')
                ->setCellValue('V2', 'Quilting')
                ->setCellValue('W2', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:W2');
            $sheet = $spreadsheet->getActiveSheet();
            $validation = $sheet->getCell("P3")->getDataValidation();
            $validation
                ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                ->setFormula1('"TRUE,FALSE"')
                ->setAllowBlank(false)
                ->setShowDropDown(true)
                ->setShowInputMessage(true)
                ->setPromptTitle("Note")
                ->setPrompt("Must select one from the drop down options.")
                ->setShowErrorMessage(true)
                ->setErrorStyle(
                    \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
                )
                ->setErrorTitle("Invalid option")
                ->setError("Select one from the drop down list.");

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                /* $f_auto_cutter = ($row->f_auto_cutter == 't') ? 'Auto Cutter' : 'Manual'; */
                $f_auto_cutter = ($row->f_auto_cutter == 't') ? 'TRUE' : 'FALSE';
                $f_badan       = ($row->f_badan == 't') ? 'TRUE' : 'FALSE';
                $f_print       = ($row->f_print == 't') ? 'TRUE' : 'FALSE';
                $f_bordir      = ($row->f_bordir == 't') ? 'TRUE' : 'FALSE';
                $f_quilting    = ($row->f_quilting == 't') ? 'TRUE' : 'FALSE';
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->id)
                    ->setCellValue('C' . $kolom, $row->id_product_wip)
                    ->setCellValue('D' . $kolom, removeEmoji($row->i_product_wip))
                    ->setCellValue('E' . $kolom, removeEmoji($row->e_product_wipname))
                    ->setCellValue('F' . $kolom, removeEmoji($row->e_color_name))
                    ->setCellValue('G' . $kolom, $row->n_qty_wip)
                    ->setCellValue('H' . $kolom, $row->id_material)
                    ->setCellValue('I' . $kolom, removeEmoji($row->i_material))
                    ->setCellValue('J' . $kolom, removeEmoji($row->e_material_name))
                    ->setCellValue('K' . $kolom, $row->e_nama_group_barang)
                    ->setCellValue('L' . $kolom, $row->e_nama_kelompok)
                    ->setCellValue('M' . $kolom, removeEmoji($row->e_bagian))
                    ->setCellValue('N' . $kolom, $row->n_gelar)
                    ->setCellValue('O' . $kolom, $row->n_set)
                    ->setCellValue('P' . $kolom, $row->n_panjang_gelar)
                    ->setCellValue('Q' . $kolom, $row->n_panjang_kain)
                    ->setCellValue('R' . $kolom, $f_auto_cutter)
                    ->setCellValue('S' . $kolom, $f_badan)
                    ->setCellValue('T' . $kolom, $f_print)
                    ->setCellValue('U' . $kolom, $f_bordir)
                    ->setCellValue('V' . $kolom, $f_quilting)
                    ->setCellValue('W' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':W' . $kolom);
                $kolom++;
                $nomor++;
            }
            $sheet->setDataValidation("P3:P" . $kolom, $validation);
            $writer = new Xls($spreadsheet);
            $nama_file = "Permintaan_Cutting.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        } else {
            echo "<center><h1>Tidak Ada Data :)</h1></center>";
        }
    }

    public function getdata()
    {
        header("Content-Type: application/json", true);
        $idreff    = $this->input->post('idreff');
        $data = array(
            'datahead'   => $this->mmaster->getdataheader($idreff)->row(),
        );
        echo json_encode($data);
    }

    public function act_upload()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $filename     = $_FILES['userfile']['name'];
        $tmp_file     = $_FILES['userfile']['tmp_name'];
        if (!empty($filename)) {
            $filename = str_replace(' ', '_', $filename);
            $exsten      = explode('.', $filename)[1];

            if ($tmp_file != "") {
                $kop = "./import/ipcutting/" . $filename;
                $pattern = "/^.*\.(" . $exsten . ")$/i";
                if (preg_match_all($pattern, $kop) >= 1) {
                    if (move_uploaded_file($tmp_file, $kop)) {
                        @chmod("./import/ipcutting/" . $filename, 0777);
                        $ibonm        = $this->input->post('idocument', TRUE);
                        $ikodemaster  = $this->input->post('ibagian', TRUE);
                        $dbonm        = $this->input->post('ddocument', TRUE);
                        if ($dbonm) {
                            $dbonm = date('Y-m-d', strtotime($dbonm));
                        }
                        $ireff        = $this->input->post('ireff', TRUE);
                        $eremark      = $this->input->post('eremark', TRUE);
                        if ($ibonm != ''  && $dbonm != '' && $ikodemaster != '' && $ireff != '') {
                            $cekkode = $this->mmaster->cek_kode($ibonm, $ikodemaster);
                            if ($cekkode->num_rows() > 0) {
                                $data = array(
                                    'sukses' => false,
                                    'kode' => ''
                                );
                            } else {
                                $this->db->trans_begin();
                                $id = $this->mmaster->runningid();
                                $this->mmaster->insertheader($id, $ibonm, $dbonm, $ikodemaster, $ireff, $eremark);
                                $inputFileName = './import/ipcutting/' . $filename;
                                $spreadsheet   = IOFactory::load($inputFileName);
                                $sheet         = $spreadsheet->getSheet(0);
                                $hrow          = $sheet->getHighestDataRow('B');
                                for ($rows = 3; $rows <= $hrow; $rows++) {
                                    $id_fccutting_item = $spreadsheet->getActiveSheet()->getCell('B' . $rows)->getValue();
                                    $id_product_base = $spreadsheet->getActiveSheet()->getCell('C' . $rows)->getValue();
                                    $n_qty_wip = $spreadsheet->getActiveSheet()->getCell('G' . $rows)->getValue();
                                    $id_material = $spreadsheet->getActiveSheet()->getCell('H' . $rows)->getValue();
                                    $e_bagian = $spreadsheet->getActiveSheet()->getCell('M' . $rows)->getValue();
                                    $n_gelar = $spreadsheet->getActiveSheet()->getCell('N' . $rows)->getValue();
                                    $n_set = $spreadsheet->getActiveSheet()->getCell('O' . $rows)->getValue();
                                    $n_panjang_gelar = $spreadsheet->getActiveSheet()->getCell('P' . $rows)->getValue();
                                    $n_panjang_kain = $spreadsheet->getActiveSheet()->getCell('Q' . $rows)->getValue();
                                    $f_auto_cutter = $spreadsheet->getActiveSheet()->getCell('R' . $rows)->getValue();
                                    $edesc = $spreadsheet->getActiveSheet()->getCell('W' . $rows)->getValue();
                                    if ($id_material != "") {
                                        $this->mmaster->insertdetail($id, $ireff, $id_product_base, $id_material, $id_fccutting_item, $e_bagian, $n_gelar, $n_set, $n_panjang_gelar, $n_panjang_kain, $f_auto_cutter, $edesc, $n_qty_wip);
                                    }
                                }
                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    $data = array(
                                        'sukses' => false,
                                        'kode' => ''
                                    );
                                } else {
                                    $this->db->trans_commit();
                                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
                                    $data = array(
                                        'sukses' => true,
                                        'kode'   => $ibonm,
                                        'id'     => $id,
                                    );
                                }
                            }
                        } else {
                            $data = array(
                                'sukses' => false,
                                'kode' => ''
                            );
                        }
                    } else {
                        $data =  array(
                            'sukses'     => false,
                            'kode' => ''
                        );
                    }
                } else {
                    $data =  array(
                        'sukses'     => false,
                        'kode' => ''
                    );
                }
            } else {
                $data =  array(
                    'sukses'     => false,
                    'kode' => ''
                );
            }
        }
        /* $this->load->view('pesan2', $data); */
        echo json_encode($data);
    }
}
/* End of file Cform.php */