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

    /*----------  Created By A  ----------*/

    public $global = array();
    public $i_menu = '2090702';

    public function __construct()
    {
        parent::__construct();

        /*----------  Cek Session Di Helper  ----------*/
        cek_session();

        /*----------  Cek Menu Di Helper  ----------*/
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /*----------  Deklarasi Session, Folder dan Nama / Judul Menu  ----------*/
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/
        $this->load->model($this->global['folder'] . '/mmaster');

        /*----------  Load Librabry  ----------*/
        $this->load->library('fungsi');
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

    /*----------  DAFTAR DATA  ----------*/

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

        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    /*----------  REDIRECT LIST REFERENSI  ----------*/

    public function indexx()
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

        $this->load->view($this->global['folder'] . '/vformlistreferensi', $data);
    }


    public function index2()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => "List " . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformmain', $data);
    }

    /*----------  EXPORT TEMPLATE EXCEL  ----------*/
    public function export_template()
    {
        $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post("ibagian", true);
        $tahun      = $this->input->post("tahun", true);
        $bulan      = $this->input->post("bulan", true);

        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);

        $id         = $this->input->post("id", true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($tahun == "") $tahun = $this->uri->segment(5);
        if ($bulan == "") $bulan = $this->uri->segment(6);

        if ($dfrom == "") $dfrom = $this->uri->segment(7);
        if ($dto == "") $dto = $this->uri->segment(8);

        if ($id == "") $id = $this->uri->segment(9);

        // $query = $this->mmaster->datadetail($this->company, $tahun . $bulan, $id);
        $query = $this->mmaster->datadetail($this->company, $tahun . $bulan, $id);

        // var_dump($query);
        // die();
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
            foreach (range('A', 'U') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Forcast Periode : ' . $tahun . $bulan);
            $spreadsheet->getActiveSheet()->setTitle('FC' . $tahun . $bulan);
            $spreadsheet->getActiveSheet()->mergeCells("A1:U1");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'ID PRODUK')
                ->setCellValue('C2', 'Kode Barang')
                ->setCellValue('D2', 'Nama Barang')
                ->setCellValue('E2', 'Warna')
                ->setCellValue('F2', 'Kategori Penjualan')
                ->setCellValue('G2', 'Sub Kategori')
                ->setCellValue('H2', 'Brand')
                ->setCellValue('I2', 'Series')
                ->setCellValue('J2', 'FC Bulan Berjalan')
                ->setCellValue('K2', 'DO Bulan Berjalan')
                ->setCellValue('L2', 'FC Distributor')
                ->setCellValue('M2', 'FC Bulan Selanjutnya')
                ->setCellValue('N2', 'Stok Jadi')
                ->setCellValue('O2', 'Stok WIP')
                ->setCellValue('P2', 'Stok Jahit')
                ->setCellValue('Q2', 'Stok Pengadaan')
                ->setCellValue('R2', 'Jumlah FC Produksi Perhitungkan')
                ->setCellValue('S2', 'Up Qty')
                ->setCellValue('T2', 'Jumlah FC Produksi yang di Budgeting')
                ->setCellValue('U2', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:U2');

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->id_product_base)
                    ->setCellValue('C' . $kolom, removeEmoji($row->i_product_base))
                    ->setCellValue('D' . $kolom, removeEmoji($row->e_product_basename))
                    ->setCellValue('E' . $kolom, removeEmoji($row->e_color_name))
                    ->setCellValue('F' . $kolom, removeEmoji($row->kategori))
                    ->setCellValue('G' . $kolom, removeEmoji($row->sub_kategori))
                    ->setCellValue('H' . $kolom, removeEmoji($row->brand))
                    ->setCellValue('I' . $kolom, removeEmoji($row->style))
                    ->setCellValue('J' . $kolom, $row->n_fc_berjalan)
                    ->setCellValue('K' . $kolom, $row->qty_do)
                    ->setCellValue('L' . $kolom, $row->n_quantity_fc)
                    ->setCellValue('M' . $kolom, $row->n_fc_next)
                    ->setCellValue('N' . $kolom, $row->n_quantity_stock)
                    ->setCellValue('O' . $kolom, $row->n_quantity_wip)
                    ->setCellValue('P' . $kolom, $row->n_quantity_unitjahit)
                    ->setCellValue('Q' . $kolom, $row->n_quantity_pengadaan)
                    ->setCellValue('R' . $kolom, $row->n_quantity)
                    ->setCellValue('S' . $kolom, 0)
                    ->setCellValue('T' . $kolom, $row->n_quantity)
                    ->setCellValue('U' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':U' . $kolom);

                $kolom++;
                $nomor++;
            }

            $writer = new Xls($spreadsheet);
            $nama_file = "FC_Produksi_" . $tahun . $bulan . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    /*----------  DAFTAR DATA REFERENSI  ----------*/

    public function datareferensi()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->datareferensi($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    /*----------  PROSES DATA  ----------*/

    public function prosesdata()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'tahun'      => $this->uri->segment(7),
            'bulan'      => $this->uri->segment(8),
            'bagian'     => $this->mmaster->bagian()->result(),
            'datadetail' => $this->mmaster->datadetail($this->uri->segment(4))->result(),
            'datadetaill' => $this->mmaster->datadetaill($this->uri->segment(4), $this->uri->segment(7), $this->uri->segment(8))->result(),
            'bisbisan'   => $this->mmaster->datadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "BGT-" . date('ym') . "-123456",
        );
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/

    public function cekkode()
    {
        if ($this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE))->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument          = $this->input->post('idocument', TRUE);
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     != "") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        $jml_item           = $this->input->post('jml_item', TRUE);

        // var_dump($idocument . " ". $ddocument . " ".$ibagian . " ". $idreferensi . " ". $jml_item);
        // die();
        if ($idocument != '' && $ddocument != '' && $ibagian != '' && $idreferensi != '' && $jml_item > 0) {
            $cekkode = $this->mmaster->cek_kode($idocument, $ibagian);

            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();


                /** Simpan Data Header */
                $this->mmaster->insertheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $eremark);

                /** Simpan Data Detail Yang Barang Jadi */
                // for ($i = 1; $i <= $jml; $i++) {
                //     $id_product_base    = $this->input->post('id_product_base'.$i, TRUE);
                //     $nilai_base         = str_replace(",","",$this->input->post('nilai_base'.$i, TRUE));
                //     $id_material        = $this->input->post('id_material'.$i, TRUE);
                //     $nilai_pemakaian    = str_replace(",","",$this->input->post('nilai_pemakaian'.$i, TRUE));
                //     $nilai_kebutuhan    = str_replace(",","",$this->input->post('nilai_kebutuhan'.$i, TRUE));
                //     if ($nilai_base > 0 && ($id_product_base!=null || $id_product_base!='')) {
                //         $this->mmaster->insertdetailbase($id,$idreferensi,$id_product_base,$nilai_base,$id_material,$nilai_pemakaian,$nilai_kebutuhan);
                //     }
                // }

                /** Simpan Data Detail Yang Material */

                for ($i = 1; $i <= $jml_item; $i++) {
                    $id_material_item       = $this->input->post('id_material_item' . $i, TRUE);
                    $i_satuan_konversi      = $this->input->post('i_satuan_konversi' . $i, TRUE);

                    $acc_pelengkap          = str_replace(",", "", $this->input->post('acc_pelengkap' . $i, TRUE));
                    $nilai_kebutuhan_item   = str_replace(",", "", $this->input->post('budgeting_awal' . $i, TRUE));
                    $nilai_mutasi           = str_replace(",", "", $this->input->post('stok' . $i, TRUE));
                    $nilai_budgeting        = str_replace(",", "", $this->input->post('nilai_budgeting' . $i, TRUE));
                    $nilai_actual           = str_replace(",", "", $this->input->post('nilai_actual' . $i, TRUE));
                    $nilai_estimasi         = str_replace(",", "", $this->input->post('schedule' . $i, TRUE));
                    $nilai_op_sisa          = str_replace(",", "", $this->input->post('sisaop' . $i, TRUE));
                    $up                     = str_replace(",", "", $this->input->post('up' . $i, TRUE));

                    $ket                    = $this->input->post('ket' . $i, TRUE);

                    if (($id_material_item != null || $id_material_item != '')) {
                        $this->mmaster->insertdetailmaterial($id, $idreferensi, $id_material_item, $nilai_kebutuhan_item, $nilai_mutasi, $nilai_budgeting, $i_satuan_konversi, $up, $ket, $nilai_estimasi, $nilai_op_sisa, $nilai_actual, $acc_pelengkap);
                    }
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
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
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            'datadetaill' => $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "BGT-" . date('ym') . "-123456",
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

        $id                 = $this->input->post('id', TRUE);
        $idocumentold       = $this->input->post('idocumentold', TRUE);
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $idocument          = $this->input->post('idocument', TRUE);
        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument      != '') {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $ibagianold         = $this->input->post('ibagianold', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        $jml_item           = $this->input->post('jml_item', TRUE);
        if ($id != '' && $idocument != '' && $ddocument != '' && $ibagian != '' && $idreferensi != '' && $jml_item > 0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument, $ibagian, $idocumentold, $ibagianold);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                /** Update Header */
                $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $eremark);

                /** Delete Item Sebelum Insert */
                $this->mmaster->delete($id);

                /** Simpan Data Detail Yang Barang Jadi */
                // for ($i = 1; $i <= $jml; $i++) {
                //     $id_product_base = $this->input->post('id_product_base'.$i, TRUE);
                //     $nilai_base      = str_replace(",","",$this->input->post('nilai_base'.$i, TRUE));
                //     $id_material     = $this->input->post('id_material'.$i, TRUE);
                //     $nilai_pemakaian = str_replace(",","",$this->input->post('nilai_pemakaian'.$i, TRUE));
                //     $nilai_kebutuhan = str_replace(",","",$this->input->post('nilai_kebutuhan'.$i, TRUE));
                //     if ($nilai_base > 0 && ($id_product_base!=null || $id_product_base!='') && ($id_material!=null || $id_material!='')) {
                //         $this->mmaster->insertdetailbase($id,$idreferensi,$id_product_base,$nilai_base,$id_material,$nilai_pemakaian,$nilai_kebutuhan);
                //     }
                // }

                /** Simpan Data Detail Yang Material */
                for ($i = 1; $i <= $jml_item; $i++) {
                    $id_material_item       = $this->input->post('id_material_item' . $i, TRUE);
                    $i_satuan_konversi      = $this->input->post('i_satuan_konversi' . $i, TRUE);

                    $acc_pelengkap          = str_replace(",", "", $this->input->post('acc_pelengkap' . $i, TRUE));
                    $nilai_kebutuhan_item   = str_replace(",", "", $this->input->post('budgeting_awal' . $i, TRUE));
                    $nilai_mutasi           = str_replace(",", "", $this->input->post('stok' . $i, TRUE));
                    $nilai_budgeting        = str_replace(",", "", $this->input->post('nilai_budgeting' . $i, TRUE));
                    $nilai_actual           = str_replace(",", "", $this->input->post('nilai_actual' . $i, TRUE));
                    $nilai_estimasi         = str_replace(",", "", $this->input->post('schedule' . $i, TRUE));
                    $nilai_op_sisa          = str_replace(",", "", $this->input->post('sisaop' . $i, TRUE));
                    $up                     = str_replace(",", "", $this->input->post('up' . $i, TRUE));

                    $ket                    = $this->input->post('ket' . $i, TRUE);

                    if (($id_material_item != null || $id_material_item != '')) {
                        $this->mmaster->insertdetailmaterial($id, $idreferensi, $id_material_item, $nilai_kebutuhan_item, $nilai_mutasi, $nilai_budgeting, $i_satuan_konversi, $up, $ket, $nilai_estimasi, $nilai_op_sisa, $nilai_actual, $acc_pelengkap);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
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
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU APPROVE  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            'datadetaill' => $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            'datadetaill' => $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

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

    public function export_excel()
    {

        $ibagian    = $this->input->post("ibagian", true);
        $tahun      = $this->input->post("tahun", true);
        $bulan      = $this->input->post("bulan", true);
        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);

        $id         = $this->input->post("id", true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($bulan == "") $bulan = $this->uri->segment(5);
        if ($tahun == "") $tahun = $this->uri->segment(6);
        if ($dfrom == "") $dfrom = $this->uri->segment(7);
        if ($dto == "") $dto = $this->uri->segment(8);

        if ($id == "") $id = $this->uri->segment(9);

        $query = $this->mmaster->edititemmaterial_export($id);

        $spreadsheet = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedStyletitle = new Style();
        $conditional3 = new Conditional();
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
            [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
            ]
        );

        $sharedStyletitle->applyFromArray(
            [
                'font' => [
                    'name'  => 'Calibri',
                    'bold'  => true,
                    'italic' => false,
                    'size'  => 12
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );

        $sharedStyle1->applyFromArray(
            [
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
        // foreach (range('A', 'S') as $columnID) {
        //     $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        // }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Forcast Periode : ' . $tahun . $bulan);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyletitle, 'A1');
        $spreadsheet->getActiveSheet()->setTitle('FC' . $tahun . $bulan);
        $spreadsheet->getActiveSheet()->mergeCells("A1:S1");
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'No')
            ->setCellValue('B2', 'Kode')
            ->setCellValue('C2', 'Nama Material')
            ->setCellValue('D2', 'Group Barang')
            ->setCellValue('E2', 'Kategori Barang')
            ->setCellValue('F2', 'Sub Kategori Barang')

            ->setCellValue('G2', 'Kebutuhan')
            ->setCellValue('H2', 'Acc Pelengkap')
            ->setCellValue('I2', 'Stock Gudang')
            ->setCellValue('J2', 'Sisa Schedule Yang Belum Terkirim')
            ->setCellValue('K2', 'Total Perhitungan Kebutuhan')
            ->setCellValue('L2', 'Satuan Pemakaian')
            ->setCellValue('M2', 'OP Sisa')
            ->setCellValue('N2', 'Budgeting Perhitungan')
            ->setCellValue('O2', 'Up Qty')
            ->setCellValue('P2', 'Aktual')
            ->setCellValue('Q2', 'Satuan Pembelian')
            ->setCellValue('R2', '% UP')
            ->setCellValue('S2', 'Keterangan');

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:S2');
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(50);

        $kolom = 3;
        $nomor = 1;
        if ($query) {
            foreach ($query->result() as $row) {
                $persen_up = 0;
                if ($row->n_budgeting_perhitungan != 0) {
                    $persen_up = ($row->persen_up / $row->n_budgeting_perhitungan) * 100;
                }
                $budgeting = $row->kebutuhan - ($row->mutasi) + $row->n_acc_pelengkap + $row->estimasi ;

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, removeEmoji($row->i_material))
                    ->setCellValue('C' . $kolom, removeEmoji(ucwords(strtolower($row->e_material_name))))
                    ->setCellValue('D' . $kolom, $row->e_nama_group_barang)
                    ->setCellValue('E' . $kolom, $row->e_nama_kelompok)
                    ->setCellValue('F' . $kolom, $row->e_type_name)

                    ->setCellValue('G' . $kolom, $row->kebutuhan)
                    ->setCellValue('H' . $kolom, $row->n_acc_pelengkap)
                    ->setCellValue('I' . $kolom, $row->mutasi)
                    ->setCellValue('J' . $kolom, $row->estimasi)
                    ->setCellValue('K' . $kolom, $budgeting)
                    ->setCellValue('L' . $kolom, $row->e_satuan_name)
                    ->setCellValue('M' . $kolom, $row->op_sisa)
                    ->setCellValue('N' . $kolom, $row->n_budgeting_perhitungan)
                    ->setCellValue('O' . $kolom, $row->persen_up)
                    ->setCellValue('P' . $kolom, $row->n_budgeting)
                    ->setCellValue('Q' . $kolom, $row->e_satuan_konversi)
                    ->setCellValue('R' . $kolom, number_format($persen_up, 0))
                    ->setCellValue('S' . $kolom, $row->e_remark);

                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':S' . $kolom);

                $spreadsheet->getActiveSheet()
                ->getStyle('H'.$kolom.':O'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
                // $spreadsheet->getActiveSheet()
                // ->getStyle('J'.$kolom)
                // ->getNumberFormat()
                // ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $kolom++;
                $nomor++;
            }
        }

        $spreadsheet->getActiveSheet()->getStyle('J2')->getAlignment()->setWrapText(true); 
        $spreadsheet->getActiveSheet()->getStyle('K2')->getAlignment()->setWrapText(true); 

        /** Start Sheet Kedua */
        $spreadsheet->createSheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('A1', "Penggunaan Bisbisan");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyletitle, 'A1');
        $spreadsheet->getActiveSheet()->setTitle('Penggunaan Bisbisan');
        $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
        $spreadsheet->setActiveSheetIndex(1)
            ->setCellValue('A2', 'No')
            ->setCellValue('B2', 'Kode Material')
            ->setCellValue('C2', 'Nama Material')
            ->setCellValue('D2', 'Jenis Potong')
            ->setCellValue('E2', 'Ukuran')
            ->setCellValue('F2', 'Pemakaian')
            ->setCellValue('G2', 'Kebutuhan')
            ->setCellValue('H2', 'Satuan');

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:H2');
        $query1 = $this->mmaster->editdatadetailbisbisan($id);
        $kolom = 3;
        $nomor = 1;
        if ($query1) {
            foreach ($query1->result() as $row) {
                $spreadsheet->setActiveSheetIndex(1)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, removeEmoji($row->i_material))
                    ->setCellValue('C' . $kolom, removeEmoji(ucwords(strtolower($row->e_material_name))))
                    ->setCellValue('D' . $kolom, $row->e_jenis_potong)
                    ->setCellValue('E' . $kolom, $row->n_bisbisan)
                    ->setCellValue('F' . $kolom, $row->pemakaian)
                    ->setCellValue('G' . $kolom, $row->kebutuhan)
                    ->setCellValue('H' . $kolom, $row->e_satuan_name);

                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':H' . $kolom);
                $kolom++;
                $nomor++;
            }
        }

        /** Start Sheet Ketiga */
        $spreadsheet->createSheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('A1', "Redaksi");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyletitle, 'A1');
        $spreadsheet->getActiveSheet()->setTitle('Redaksi');
        $spreadsheet->getActiveSheet()->mergeCells("A1:M1");
        $spreadsheet->setActiveSheetIndex(2)
            ->setCellValue('A2', 'No')
            ->setCellValue('B2', 'Kode Barang')
            ->setCellValue('C2', 'Nama Barang')
            ->setCellValue('D2', 'Warna')
            ->setCellValue('E2', 'Quantity')
            ->setCellValue('F2', 'Kode Material')
            ->setCellValue('G2', 'Nama Material')
            ->setCellValue('H2', 'Pemakaian')
            ->setCellValue('I2', 'Kebutuhan')
            ->setCellValue('J2', 'Satuan')
            ->setCellValue('K2', 'Operator')
            ->setCellValue('L2', 'Faktor')
            ->setCellValue('M2', 'Satuan Konversi');

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:M2');
        $query2 = $this->mmaster->export_data($id);
        $kolom1 = 3;
        $nomor = 1;
        if ($query2) {
            foreach ($query2->result() as $row) {
                $spreadsheet->setActiveSheetIndex(2)
                    ->setCellValue('A' . $kolom1, $nomor)
                    ->setCellValue('B' . $kolom1, removeEmoji($row->i_product_base))
                    ->setCellValue('C' . $kolom1, removeEmoji($row->e_product_basename))
                    ->setCellValue('D' . $kolom1, removeEmoji($row->e_color_name))
                    ->setCellValue('E' . $kolom1, $row->n_quantity)
                    ->setCellValue('F' . $kolom1, removeEmoji($row->i_material))
                    ->setCellValue('G' . $kolom1, removeEmoji(ucwords(strtolower($row->e_material_name))))
                    ->setCellValue('H' . $kolom1, $row->pemakaian)
                    ->setCellValue('I' . $kolom1, $row->kebutuhan)
                    ->setCellValue('J' . $kolom1, $row->e_satuan_name)
                    ->setCellValue('K' . $kolom1, $row->e_operator)
                    ->setCellValue('L' . $kolom1, $row->n_faktor)
                    ->setCellValue('M' . $kolom1, $row->satuan_konversi);

                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom1 . ':M' . $kolom1);
                $kolom1++;
                $nomor++;
            }
        }

        /** Start Sheet Ketiga */
        /* $spreadsheet->createSheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('A1', "Redaksi");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyletitle, 'A1');
        $spreadsheet->getActiveSheet()->setTitle('Redaksi');
        $spreadsheet->getActiveSheet()->mergeCells("A1:F1");
        $spreadsheet->setActiveSheetIndex(2)
            ->setCellValue('A2', 'No')
            ->setCellValue('B2', 'Kode Material')
            ->setCellValue('C2', 'Nama Material')
            ->setCellValue('D2', 'Pemakaian')
            ->setCellValue('E2', 'Kebutuhan')
            ->setCellValue('F2', 'Satuan');

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:F2');
        $query2 = $this->mmaster->edititembase($id);
        $kolom1 = 3;
        $kolom2 = 4;
        $nomor = 1;
        if ($query2) {
            $group = "";
            foreach ($query2->result() as $row) {
                if ($group == '') {
                    $spreadsheet->getActiveSheet()->mergeCells("A$kolom1:D$kolom1");
                    $spreadsheet->setActiveSheetIndex(2)
                        ->setCellValue('A' . $kolom1, "Barang Jadi : " . $row->i_product_base . " " . ucwords(strtolower($row->e_product_basename)) . " " . ucwords(strtolower($row->e_color_name)))
                        ->setCellValue('E' . $kolom1, $row->n_quantity);

                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom1 . ':F' . $kolom1);
                } else {
                    if ($group != $row->id_product_base) {
                        $spreadsheet->getActiveSheet()->mergeCells("A$kolom2:D$kolom2");
                        $spreadsheet->setActiveSheetIndex(2)
                            ->setCellValue('A' . $kolom2, "Barang Jadi : " . $row->i_product_base . " " . ucwords(strtolower($row->e_product_basename)) . " " . ucwords(strtolower($row->e_color_name)))
                            ->setCellValue('E' . $kolom2, $row->n_quantity);

                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom2 . ':F' . $kolom2);
                        $nomor = 1;
                        $kolom2 = $kolom2+1;
                    }
                }
                $group = $row->id_product_base;
                $spreadsheet->setActiveSheetIndex(2)
                    ->setCellValue('A' . $kolom2, $nomor)
                    ->setCellValue('B' . $kolom2, removeEmoji($row->i_material))
                    ->setCellValue('C' . $kolom2, removeEmoji(ucwords(strtolower($row->e_material_name))))
                    ->setCellValue('D' . $kolom2, $row->pemakaian)
                    ->setCellValue('E' . $kolom2, $row->kebutuhan)
                    ->setCellValue('F' . $kolom2, $row->e_satuan_name);

                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom2 . ':F' . $kolom2);
                $kolom2++;
                $nomor++;
            }
        } */


        $writer = new Xls($spreadsheet);
        $nama_file = "Budgeting_" . $bulan . $tahun . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }
}
/* End of file Cform.php */