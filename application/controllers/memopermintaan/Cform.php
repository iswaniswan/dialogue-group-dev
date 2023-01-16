<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2050206';

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
        $this->doc_qe = $data[0]['doc_qe'];

        $this->load->model($this->global['folder'] . '/mmaster');
    }

    /*----------  DEFAULT CONTROLLERS  ----------*/

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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/

    public function data()
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

    /*----------  MEMBUKA FORM TAMBAH DATA  ----------*/

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'type'          => $this->mmaster->type(),
            'bagian'        => $this->mmaster->bagian(),
            'tujuan'        => $this->mmaster->tujuan()->result(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function product_wip()
    {
        $filter = [];
        $data = $this->mmaster->product_wip(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => '[' . $key->i_product . '] - ' . $key->e_product . ' ' . $key->e_color_name
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data."
            );
        }
        echo json_encode($filter);
    }

    /*-------------- CARI MARKER ------------- */
    public function marker()
    {
        $filter = [];
        $data = $this->mmaster->marker(str_replace("'", "", $this->input->get('q')), str_replace("'", "", $this->input->get('id_product_wip')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id_marker,
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

    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function material()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $id_product = $this->input->get('id_product');
        $id_type = $this->input->get('id_type');
        $id_marker = $this->input->get('id_marker');
        $data = $this->mmaster->material($cari, $id_product, $id_type, $id_marker);
        if ($data->num_rows() > 0) {
            $arr     = [];
            foreach ($data->result() as $key) {
                $arr[] = $key->e_group;
            }
            $unique_data = array_unique($arr);
            foreach ($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {
                    if ($val == $row->e_group) {
                        $child[] = array(
                            'id' => $row->id,
                            'text' => '[' . $row->i_material . '] - ' . $row->e_material_name . ' ' . $row->e_satuan_name,
                            'title' => $row->e_material_name,
                        );
                    }
                }
                $filter[] = array(
                    'id' => 0,
                    'text' => strtoupper($val),
                    'children' => $child
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

    /* public function material_old()
    {
        $filter = [];
        $id_product = $this->input->get('id_product');
        if ($id_product != '') {
            $data = $this->mmaster->material(str_replace("'", "", $this->input->get('q')), $id_product);
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key) {
                    $filter[] = array(
                        'id'   => $key->id,
                        'text' => '[' . $key->i_material . '] - ' . $key->e_material_name . ' ' . $key->e_satuan_name
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data."
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih Barang WIP terlebih dahulu.."
            );
        }
        echo json_encode($filter);
    } */

    /*----------  DETAIL ITEM REFERENSI  ----------*/

    public function get_material_detail()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detail_material($this->input->post('id_material', TRUE), $this->input->post('id_product_wip', TRUE))->result_array(),
        );
        echo json_encode($query);
    }

    public function get_material_onchange_detail()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detail_material_onchange($this->input->post('id_marker', TRUE), $this->input->post('id_product_wip', TRUE), $this->input->post('id_type', TRUE))->result_array(),
        );
        echo json_encode($query);
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_document     = $this->input->post('idocument', TRUE);
        $d_document     = $this->input->post('ddocument', TRUE);
        $d_kirim     = $this->input->post('dkirim', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        if($d_kirim != '') {
            $d_kirim = formatYmd($d_kirim);
        }
        $i_bagian       = $this->input->post('ibagian', TRUE);
        $i_tujuan       = $this->input->post('itujuan_kirim', TRUE);
        $id_type        = $this->input->post('id_type', TRUE);
        $e_remark       = $this->input->post('eremark', TRUE);
        $id             = $this->mmaster->runningid();
        if ($i_bagian != '' && $id_type != '' && $d_document != '') {
            $this->db->trans_begin();
            $i_document = $this->mmaster->runningnumber(format_ym($d_document), format_Y($d_document), $i_bagian);
            $this->mmaster->simpan_header($id, $i_document, $d_document, $d_kirim, $i_bagian, $i_tujuan, $e_remark, $id_type);
            $material = $this->input->post('id_material');
            $i = 0;
            foreach ($material as $id_material) {
                $id_product = $this->input->post('id_product[]')[$i];
                $id_marker = $this->input->post('id_marker[]')[$i];
                $n_quantity_product = str_replace(",","",$this->input->post('n_quantity_product[]')[$i]);
                $n_kebutuhan_material = str_replace(",","",$this->input->post('n_kebutuhan_material[]')[$i]);
                $e_note = $this->input->post('e_note[]')[$i];
                if ($id_product !='' && $id_material != '') {
                    $this->mmaster->simpan_detail($id, $id_product, $id_marker, $id_material, $n_quantity_product, $n_kebutuhan_material, $e_note);
                }
                $i++;
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $i_document,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $i_document,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
            }
        } else {
            $data = array(
                'kode'      => $i_document,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA FORM EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'type'       => $this->mmaster->type(),
            'bagian'     => $this->mmaster->bagian(),
            'tujuan'     => $this->mmaster->tujuan()->result(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->data_detail($this->uri->segment(4),$this->uri->segment(7))->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_document     = $this->input->post('idocument', TRUE);
        $d_document     = $this->input->post('ddocument', TRUE);
        $d_kirim     = $this->input->post('dkirim', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        if($d_kirim != '') {
            $d_kirim = formatYmd($d_kirim);
        }
        $i_bagian       = $this->input->post('ibagian', TRUE);
        $i_tujuan       = $this->input->post('itujuan_kirim', TRUE);
        $id_type        = $this->input->post('id_type', TRUE);
        $e_remark       = $this->input->post('eremark', TRUE);
        $id             = $this->input->post('id', TRUE);
        if ($id != '' && $i_bagian != '' && $id_type != '' && $d_document != '') {
            $this->db->trans_begin();
            $this->mmaster->update_header($id, $i_document, $d_document, $d_kirim, $i_bagian, $i_tujuan, $e_remark, $id_type);
            $this->mmaster->delete_detail($id);
            $material = $this->input->post('id_material');
            $i = 0;
            foreach ($material as $id_material) {
                $id_product = $this->input->post('id_product[]')[$i];
                $id_marker = $this->input->post('id_marker[]')[$i];
                $n_kebutuhan_material = str_replace(",","",$this->input->post('n_kebutuhan_material[]')[$i]);
                $n_quantity_product = str_replace(",","",$this->input->post('n_quantity_product[]')[$i]);
                $e_note = $this->input->post('e_note[]')[$i];
                if ($id_product !='' && $id_material != '') {
                    $this->mmaster->simpan_detail($id, $id_product, $id_marker, $id_material, $n_quantity_product, $n_kebutuhan_material, $e_note);
                }
                $i++;
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $i_document,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $i_document,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
            }
        } else {
            $data = array(
                'kode'      => $i_document,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU FORM VIEW  ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->data_detail($this->uri->segment(4),$this->uri->segment(7))->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  MEMBUKA MENU FORM APPROVE  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->data_detail($this->uri->segment(4),$this->uri->segment(7))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {

        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }

    /*---------  EXPORT EXCEL  ----------*/
    public function export()
    {
        /** Parameter */
        $id = $this->uri->segment(6);
        $ddocument = $this->uri->segment(7);
        $ibagian = $this->uri->segment(8);
        $dsplit = explode('-',$ddocument);
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
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
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
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );
        /** End Style */

        $abjad  = range('A', 'Z');
        $zero = 1;
        $satu = 2;
        $dua = 3;

        $validation = $spreadsheet->getActiveSheet()->getCell("AZ1")->getDataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Input is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Number Value allowed");

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A$zero", $id)
            ->setCellValue("A$satu", strtoupper($this->session->e_company_name))
            ->setCellValue("A$dua", "FORMAT UPLOAD MEMO PERMINTAAN BAHAN BAKU");
        $spreadsheet->getActiveSheet()->setTitle('Format Memo Permintaan BB');

        $validation = $spreadsheet->getActiveSheet()->getCell('K1')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Alphabet is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Value number allowed");

        $h = 4;
        $header = [
            '#',
            'ID BARANG',
            'KODE',
            'NAMA BARANG',
            'WARNA',
            'ID MARKER',
            'MARKER',
            'QTY',
        ];
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":H" . $h);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "A2:H2");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "A3:H3");
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':H' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        $spreadsheet->getActiveSheet()->mergeCells('A2:H2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:H3');
        $j = 4;
        $i = 0;
        $no = 1;
        $sql = $this->mmaster->get_product_polacutting();
        if ($sql->num_rows() > 0) {
            // $group = "";
            foreach($sql->result() as $row) {
                // if ($group != $row->e_type_name) {
                //     // $spreadsheet->setActiveSheetIndex(0)->setCellValue("A". $j, "#");
                //     $j = $j + 1;
                //     $spreadsheet->setActiveSheetIndex(0)->setCellValue("A". $j, $row->e_type_name);
                //     $spreadsheet->getActiveSheet()->mergeCells('A'. $j .':I' . $j);
                //     $spreadsheet->getActiveSheet()->getStyle('A' . $j . ':I' . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('bbbbbb');
                //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . $j . ":I". $j);
                //     $no = 1;
                // }
                // $group = $row->e_type_name;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("A". ($j + 1), $no);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("B". ($j + 1), $row->id_product_wip);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("C". ($j + 1), $row->i_product_wip);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("D". ($j + 1), $row->e_product_wipname);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("E". ($j + 1), $row->e_color_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("F". ($j + 1), $row->id_marker);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("G". ($j + 1), $row->e_marker_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("H". ($j + 1), "");
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . ($j + 1) . ":H". ($j + 1));
                $spreadsheet->getActiveSheet()->setDataValidation("H" . ($j + 1), $validation);
                $j++;
                $no++;
                $i++;
            }
        }
        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        $hrow = $spreadsheet->getActiveSheet(0)->getHighestDataRow('A');

        $spreadsheet->getActiveSheet()->getStyle("H5:H" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->setAutoFilter("A4:H" . $hrow);

        /** End Sheet */

        $writer = new Xls($spreadsheet);
        $nama_file = "Format_Memo_Permintaan_BB_Referensi.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    /*----------  EXPORT EXCEL MEMO PERMINTAAN BB  ----------*/
    public function export_excel()
    {
        $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post("ibagian", true);
        // $tahun      = $this->input->post("tahun", true);
        // $bulan      = $this->input->post("bulan", true);

        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);

        $id         = $this->input->post("id", true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        // if ($tahun == "") $tahun = $this->uri->segment(5);
        // if ($bulan == "") $bulan = $this->uri->segment(6);

        if ($dfrom == "") $dfrom = $this->uri->segment(5);
        if ($dto == "") $dto = $this->uri->segment(6);

        if ($id == "") $id = $this->uri->segment(7);

        // $query = $this->mmaster->datadetail($this->company, $tahun . $bulan, $id);
        $qHeader = $this->mmaster->datadetail_header($id)->row();
        $query = $this->mmaster->datadetail_edit($id);
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
                        'top' => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'right' => ['borderStyle' => Border::BORDER_THIN],
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
            foreach (range('A', 'M') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Perusahaan Pengirim : ' . $qHeader->company_name)
                ->setCellValue('A2', 'Bagian : ' . $qHeader->e_bagian_name)
                ->setCellValue('A3', 'Nomor Dokumen : ' . $qHeader->i_document)
                ->setCellValue('A4', 'Status Dokumen : ' . $qHeader->e_status_name);
            $spreadsheet->getActiveSheet()->setTitle('Memo Permintaan' . $qHeader->periode);
            $spreadsheet->getActiveSheet()->mergeCells("A1:M1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:M2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:M3");
            $spreadsheet->getActiveSheet()->mergeCells("A4:M4");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A6', 'No')
                ->setCellValue('B6', 'Periode')
                ->setCellValue('C6', 'Tgl Memo')
                ->setCellValue('D6', 'Tgl Kirim')
                ->setCellValue('E6', 'Tujuan Kirim')
                ->setCellValue('F6', 'Grup')
                ->setCellValue('G6', 'Hari')
                ->setCellValue('H6', 'Tgl Pengerjaan')
                ->setCellValue('I6', 'Kode Barang')
                ->setCellValue('J6', 'Nama Barang')
                ->setCellValue('K6', 'Warna')
                ->setCellValue('L6', 'Qty')
                // ->setCellValue('L6', 'Qty Sisa')
                ->setCellValue('M6', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A6:M6');

            $kolom = 7;
            $nomor = 1;
            // $totalQtyFC = 0;
            // $totalQtyUrai = 0;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->periode)
                    ->setCellValue('C' . $kolom, $row->tgl_dokumen)
                    ->setCellValue('D' . $kolom, $row->tgl_kirim)
                    ->setCellValue('E' . $kolom, $row->i_tujuan . ' - ' . $row->company_tujuan)
                    ->setCellValue('F' . $kolom, '')
                    ->setCellValue('G' . $kolom, '')
                    ->setCellValue('H' . $kolom, '')
                    ->setCellValue('I' . $kolom, $row->i_product)
                    ->setCellValue('J' . $kolom, $row->e_product)
                    ->setCellValue('K' . $kolom, $row->e_color_name)
                    ->setCellValue('L' . $kolom, $row->n_quantity_product)
                    // ->setCellValue('L' . $kolom, $row->n_quantity_sisa)
                    ->setCellValue('M' . $kolom, '');
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':M' . $kolom);
                // $totalQtyFC += $row->quantity_forecast;
                // $totalQtyUrai += $row->quantity_urai;
                $kolom++;
                $nomor++;
            }

            // $spreadsheet->setActiveSheetIndex(0)
            //     ->setCellValue('G6', 'Total : ' . $totalQtyFC)
            //     ->setCellValue('H6', 'Total : ' . $totalQtyUrai);
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'G6:H6');
            $writer = new Xls($spreadsheet);
            $nama_file = "Memo_Permintaan_" . $qHeader->periode . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /* $ibagian = $this->input->post('ibagian', TRUE);
        $i_so = $this->input->post('i_so', TRUE);
        $ddocument = $this->input->post('ddocument', TRUE);
        $dfrom = $this->input->post('dfrom', TRUE);
        $dto = $this->input->post('dto', TRUE); */
        // $abjadBanyak = array();
        // foreach (excelColumnRange('A', 'ZZ') as $value) {
        //     array_push($abjadBanyak, $value);
        // }
        $idforecast = $this->input->post('idforecast');
        $id_type = $this->input->post('id_type');
        $filename = $this->id_company . "_Memo_Permintaan_BB.xls";
        $aray = array();
        $fc_jahit = 0;
        $fc_jahit_sisa = 0;
        $fc_jahit_urai = 0;

        $config = array(
            'upload_path'   => "./import/memopermintaanbb/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());

            $inputFileName = "./import/memopermintaanbb/" . $filename;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestDataRow('A');
            // $idprod        = array();
            $datamaterial = array();
            for ($n = 5; $n <= $hrow; $n++) {
                $n_quantity = strtoupper($spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue());
                if ($n_quantity != "") {
                    // array_push($idprod, strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue()));
                    $aray[] = array(
                        'id'                => strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue()),
                        'i_product_wip'     => strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue()),
                        'e_product_name' => strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue()),
                        'e_color_name'      => strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue()),
                        'id_marker'        => strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue()),
                        'e_marker_name'        => strtoupper($spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue()),
                        'n_quantity'   => $n_quantity,
                        // 'e_remark'          => $e_remark,
                    );
                    $detailmaterial = $this->mmaster->detail_material_onchange(strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue()) ,strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue()), $id_type)->result_array();
                    array_push($datamaterial, $detailmaterial);
                }
            }
            // $idprodstr = implode(',',$idprod);
            // echo '<pre>' . var_export($aray, true) . '</pre>';
            // var_dump($data2);
            // usort($aray, function ($b, $a) {
            //     return $b['d_schedule'] <=> $a['d_schedule'];
            // });
            $param = array(
                'folder'        => $this->global['folder'],
                'title'         => "Tambah " . $this->global['title'],
                'title_list'    => $this->global['title'],
                'datadetail'    => $aray,
                'detail_material' => $datamaterial,
                'status'        => 'berhasil',
                'sama'          => true,
            );
            echo json_encode($param);
        } else {
            $param =  array(
                'status'        => 'gagal',
                'datadetail'    => $aray,
                'sama'          => false
            );
            echo json_encode($param);
        }
    }
}

/* End of file Cform.php */