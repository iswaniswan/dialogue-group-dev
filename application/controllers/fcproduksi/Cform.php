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
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090701';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->departement  = $this->session->i_departement;
        $this->company      = $this->session->id_company;
        $this->level        = $this->session->i_level;
        $this->username     = $this->session->username;
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
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

    public function overbudget()
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
            'title'         => $this->global['title'].' Over Budget',
            'title_list'    => "List " . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vforminput_overbudget', $data);
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian     = $this->input->post('ibagian', TRUE);
        $bulan       = $this->input->post('bulan', TRUE);
        $tahun       = $this->input->post('tahun', TRUE);
        $filename    = $this->id_company."_FC_Produksi_" . $tahun . $bulan . ".xls";

        $config = array(
            'upload_path'   => "./import/fcproduksi/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Forecast Produksi Periode : ' . $tahun . $bulan);

            $param =  array(
                'ibagian'   => $ibagian,
                'bulan'     => $bulan,
                'tahun'     => $tahun,
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

    public function tambah()
    {

        $data = check_role($this->i_menu, 2);
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

        // var_dump($ibagian, $tahun, $bulan, $dfrom, $dto, $id);
        // die();
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input " . $this->global['title'],
            'title_list'    => "List " . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->company)->row(),
            'head'          => $this->mmaster->dataheader($this->company, $tahun . $bulan)->row(),
            'datadetail'    => $this->mmaster->datadetail($this->company, $tahun . $bulan, $id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }

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
            foreach (range('A', 'V') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $validation = $spreadsheet->getActiveSheet()->getCell("W1")->getDataValidation();
            $validation->setType(DataValidation::TYPE_WHOLE);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Input is not allowed!');
            $validation->setPromptTitle('Allowed input');
            $validation->setPrompt("Only Number Value allowed");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Forcast Periode : ' . $tahun . $bulan);
            $spreadsheet->getActiveSheet()->setTitle('FC' . $tahun . $bulan);
            $spreadsheet->getActiveSheet()->mergeCells("A1:V1");
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
                ->setCellValue('R2', 'Stok Packing')
                ->setCellValue('S2', 'Jumlah FC Produksi Perhitungkan')
                ->setCellValue('T2', 'Up Qty')
                ->setCellValue('U2', 'Jumlah FC Produksi yang di Budgeting')
                ->setCellValue('V2', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:V2');

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
                    ->setCellValue('R' . $kolom, $row->n_packing)
                    ->setCellValue('S' . $kolom, $row->n_quantity)
                    ->setCellValue('T' . $kolom, 0)
                    ->setCellValue('U' . $kolom, "=SUM(S$kolom,T$kolom)")
                    ->setCellValue('V' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':V' . $kolom);

                $kolom++;
                $nomor++;
            }
            $x = 3;
            $y = $kolom - 1;

            $spreadsheet->getActiveSheet()->setDataValidation("T$x:T$y", $validation);
            $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
            $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
            $spreadsheet->getActiveSheet()->getStyle("T$x:T$y")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
            $spreadsheet->getActiveSheet()->getStyle("V$x:V$y")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

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


    public function edit()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);
        $id         = $this->input->post("id", true);


        if ($dfrom == "") $dfrom = $this->uri->segment(4);
        if ($dto == "") $dto = $this->uri->segment(5);

        if ($id == "") $id = $this->uri->segment(6);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update " . $this->global['title'],
            'title_list'    => "List " . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'head'          => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  CARI BARANG  ----------*/

    public function product()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $data = $this->mmaster->product(str_replace("'", "", $this->input->get('q')));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'        => $row->id,
                        'text'      => $row->i_product_base . ' - ' . $row->e_product_basename . ' - ' . $row->e_color_name
                    );
                }
            } else {
                $filter[] = array(
                    'id'        => null,
                    'text'      => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'        => null,
                'text'      => "Cari Berdasarkan Nama / Kode Barang!"
            );
        }
        echo json_encode($filter);
    }

    public function classproduct()
    {
        $idproduct = $this->input->post('idproduct');
        $query = $this->mmaster->getclassproduct($idproduct);
        if ($query->num_rows() > 0) {
            $c  = "";
            $eclass = $query->result();
            foreach ($eclass as $row) {
                $eclassname  = $row->kategori;
            }
            echo json_encode(array(
                'eclassname'    => $eclassname
            ));
        }
    }

    public function loadview()
    {

        $tahun    = $this->uri->segment(4);
        $bulan    = $this->uri->segment(5);
        $ibagian    = $this->uri->segment(6);
        $filename = $this->id_company."_FC_Produksi_" . $tahun . $bulan . ".xls";

        $dfrom = $this->uri->segment(7);
        $dto = $this->uri->segment(8);

        $inputFileName = './import/fcproduksi/' . $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('B');
        $totalqty = 0;
        $id = 0;
        $aray = array();
        for ($n = 3; $n <= $hrow; $n++) {
            $idproduct = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
            $qty  = $spreadsheet->getActiveSheet()->getCell('T' . $n)->getValue();
            
            $cek_produk = $this->mmaster->cek_produk($idproduct);
            if ($cek_produk->num_rows() > 0) {
                $data = array(
                    'id_product_base'  => $idproduct,
                    'n_persen_up'        => $qty
                );
                $this->db->insert("tm_forecast_produksi_item_tmp", $data);
            }

        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input " . $this->global['title'],
            'title_list'    => "List " . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->company)->row(),
            'head'          => $this->mmaster->dataheader($this->company, $tahun . $bulan)->row(),
            'datadetail'    => $this->mmaster->datadetail($this->company, $tahun . $bulan, $id)->result_array(),
        );
        $this->db->query("delete from tm_forecast_produksi_item_tmp",FALSE);
        $this->Logger->write('Membuka Menu Upload ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }

    public function barang() {
        $filter = [];
        $data = $this->mmaster->barang(str_replace("'","",$this->input->get('q')),$this->input->get('ibagian'), $this->input->get('ddocument') );
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
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

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->input->post('id', TRUE);
        $ibagian    = $this->input->post('ibagian', TRUE);
        /* $icustomer  = $this->input->post('icustomer', TRUE);
        $istatus    = $this->input->post('istatus', TRUE); */
        $ibulan     = $this->input->post('ibulan', TRUE);
        $tahun      = $this->input->post('tahun', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $f_over_budget = $this->input->post('f_over_budget', TRUE);
        $f_over_budget = ($f_over_budget=='t') ? 't' : 'f' ;
        $idcompany  = $this->company;
        $periode    = $tahun . $ibulan;
        $thbl       = substr($periode, 2, 4);
        $jml        = $this->input->post('jml', FALSE);
        $query = $this->db->query("SELECT periode FROM tm_forecast_produksi WHERE periode = '$periode' AND f_over_budget = 'f' AND id_company = '$this->id_company' AND i_status IN ('1', '2', '3', '6')", FALSE);
        // $xperiode = '"' . $periode . '"';
        if ($query->num_rows() > 0 && $f_over_budget == 'f') {
            echo '<script>
            swal({
                title: "Peringatan!",
                text: "Dokumen FC Produksi Periode tersebut sedang dalam proses" ,
                type: "warning",
                showConfirmButton: false,
                timer: 2500
            });      
            </script>';
        } else {
            $this->db->trans_begin();
            // if($istatus == '4' || $istatus == '3' || $istatus == '9'){
            //     $this->mmaster->changestatus($id, 1);
            // }
            // if ($id == "" || $id == NULL)
            $id = $this->mmaster->runningid();
            $idocument = $this->mmaster->runningnumber($thbl, $periode, $ibagian);
            $this->mmaster->simpan($id, $idcompany, $ibagian, $periode, $eremarkh, $idocument, $f_over_budget);
            $this->mmaster->hapusdetail($idcompany, $id);
            for ($i = 1; $i <= $jml; $i++) {
                $idproduct  = $this->input->post('idproduct' . $i, TRUE);
                $persen_up = str_replace(",", "", $this->input->post('persen_up' . $i, TRUE));
                $persen_up = ($persen_up=='') ? '0' : $persen_up ;
                $qty = str_replace(",", "", $this->input->post('nquantity' . $i, TRUE));
                $qty = ($qty=='') ? '0' : $qty ;
                $qty_fc = str_replace(",", "", $this->input->post('nquantity_fc' . $i));
                $qty_fc = ($qty_fc=='') ? '0' : $qty_fc ;
                $qty_stock = str_replace(",", "", $this->input->post('nquantity_stock' . $i));
                $qty_stock = ($qty_stock=='') ? '0' : $qty_stock ;
                $n_fc_berjalan = str_replace(",", "", $this->input->post('n_fc_berjalan' . $i));
                $n_fc_berjalan = ($n_fc_berjalan=='') ? '0' : $n_fc_berjalan ;
                $estimasi = str_replace(",", "", $this->input->post('estimasi' . $i));
                $estimasi = ($estimasi=='') ? '0' : $estimasi ;
                $n_fc_next = str_replace(",", "", $this->input->post('n_fc_next' . $i));
                $n_fc_next = ($n_fc_next=='') ? '0' : $n_fc_next ;
                $nquantity_stock_wip  = str_replace(",", "", $this->input->post('nquantity_stock_wip' . $i));
                $nquantity_stock_wip = ($nquantity_stock_wip=='') ? '0' : $nquantity_stock_wip ;
                $nquantity_stock_jahit  = str_replace(",", "", $this->input->post('nquantity_stock_jahit' . $i));
                $nquantity_stock_jahit = ($nquantity_stock_jahit=='') ? '0' : $nquantity_stock_jahit ;
                $nquantity_stock_pengadaan  = str_replace(",", "", $this->input->post('nquantity_stock_pengadaan' . $i));
                $nquantity_stock_pengadaan = ($nquantity_stock_pengadaan=='') ? '0' : $nquantity_stock_pengadaan ;
                $nquantity_stock_packing  = str_replace(",", "", $this->input->post('nquantity_stock_packing' . $i));
                $nquantity_stock_packing = ($nquantity_stock_packing=='') ? '0' : $nquantity_stock_packing ;
                $nquantity_tmp  = str_replace(",", "", $this->input->post('nquantity_tmp' . $i));
                $nquantity_tmp = ($nquantity_tmp=='') ? '0' : $nquantity_tmp ;

                $eremark    = $this->input->post('eremark' . $i, TRUE);
                if (($idproduct != null || $idproduct != '')) {
                    $this->mmaster->simpandetail($idcompany, $id, $idproduct, $persen_up, $qty, $eremark, $qty_fc, $qty_stock, $n_fc_berjalan, $estimasi, $n_fc_next, $nquantity_stock_wip, $nquantity_stock_jahit, $nquantity_stock_pengadaan, $nquantity_stock_packing, $nquantity_tmp);
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
                    'kode'   => $periode,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }

            $this->load->view('pesan2', $data);
        }
    }


    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->input->post('id', TRUE);
        $ibagian    = $this->input->post('ibagian', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        $ibulan     = $this->input->post('ibulan', TRUE);
        $tahun      = $this->input->post('tahun', TRUE);
        $periode    = $tahun . $ibulan;
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idcompany  = $this->company;
        $jml        = $this->input->post('jml', FALSE);
        $this->db->trans_begin();

        $this->mmaster->update($id, $idcompany, $ibagian, $eremarkh);
        $this->mmaster->hapusdetail($idcompany, $id);
        for ($i = 1; $i <= $jml; $i++) {
            $idproduct  = $this->input->post('idproduct' . $i, TRUE);
            $persen_up    = str_replace(",", "", $this->input->post('persen_up' . $i, TRUE));
            $qty        = str_replace(",", "", $this->input->post('nquantity' . $i, TRUE));
            $qty_fc     = str_replace(",", "", $this->input->post('nquantity_fc' . $i));
            $qty_stock  = str_replace(",", "", $this->input->post('nquantity_stock' . $i));
            $n_fc_berjalan  = str_replace(",", "", $this->input->post('n_fc_berjalan' . $i));
            $estimasi  = str_replace(",", "", $this->input->post('estimasi' . $i));
            $n_fc_next  = str_replace(",", "", $this->input->post('n_fc_next' . $i));
            $nquantity_stock_wip  = str_replace(",", "", $this->input->post('nquantity_stock_wip' . $i));
            $nquantity_stock_jahit  = str_replace(",", "", $this->input->post('nquantity_stock_jahit' . $i));
            $nquantity_stock_pengadaan  = str_replace(",", "", $this->input->post('nquantity_stock_pengadaan' . $i));
            $nquantity_stock_packing  = str_replace(",", "", $this->input->post('nquantity_stock_packing' . $i));
            $nquantity_tmp  = str_replace(",", "", $this->input->post('nquantity_tmp' . $i));

            $eremark    = $this->input->post('eremark' . $i, TRUE);
            if (($idproduct != null || $idproduct != '')) {
                $this->mmaster->simpandetail($idcompany, $id, $idproduct, $persen_up, $qty, $eremark, $qty_fc, $qty_stock, $n_fc_berjalan, $estimasi, $n_fc_next, $nquantity_stock_wip, $nquantity_stock_jahit, $nquantity_stock_pengadaan, $nquantity_stock_packing, $nquantity_tmp);
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
                'kode'   => $periode,
                'id'     => $id
            );
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        $this->load->view('pesan2', $data);
    }

    public function export()
    {
        $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $query = $this->mmaster->cek_datadet($this->company)->result();

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
            foreach (range('A', 'G') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID Barang')
                ->setCellValue('B1', 'Kode Barang')
                ->setCellValue('C1', 'Nama Barang')
                ->setCellValue('D1', 'Warna')
                ->setCellValue('E1', 'QTY FC')
                ->setCellValue('F1', 'Harga Barang')
                ->setCellValue('G1', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:H1');

            $kolom = 2;
            $nomor = 1;
            foreach ($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $row->id_product_base)
                    ->setCellValue('B' . $kolom, removeEmoji($row->i_product_base))
                    ->setCellValue('C' . $kolom, removeEmoji($row->e_product_basename))
                    ->setCellValue('D' . $kolom, removeEmoji($row->e_color_name))
                    ->setCellValue('E' . $kolom, $row->n_quantity)
                    ->setCellValue('F' . $kolom, $row->v_harga)
                    ->setCellValue('G' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':G' . $kolom);

                $kolom++;
                $nomor++;
            }
            $writer = new Xls($spreadsheet);
            $nama_file = "FC_Produksi" . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    public function export_excel()
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
            foreach (range('A', 'V') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Forcast Periode : ' . $tahun . $bulan);
            $spreadsheet->getActiveSheet()->setTitle('FC' . $tahun . $bulan);
            $spreadsheet->getActiveSheet()->mergeCells("A1:U1");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'Kode Barang')
                ->setCellValue('C2', 'Nama Barang')
                ->setCellValue('D2', 'Warna')
                ->setCellValue('E2', 'Kategori Penjualan')
                ->setCellValue('F2', 'Sub Kategori')
                ->setCellValue('G2', 'Brand')
                ->setCellValue('H2', 'Series')
                ->setCellValue('I2', 'FC Bulan Berjalan')
                ->setCellValue('J2', 'DO Bulan Berjalan')
                ->setCellValue('K2', 'FC Distributor')
                ->setCellValue('L2', 'FC Bulan Selanjutnya')
                ->setCellValue('M2', 'Stok Jadi')
                ->setCellValue('N2', 'Stok WIP')
                ->setCellValue('O2', 'Stok Jahit')
                ->setCellValue('P2', 'Stok Pengadaan')
                ->setCellValue('Q2', 'Stok Packing')
                ->setCellValue('R2', 'Jumlah FC Produksi Perhitungkan')
                ->setCellValue('S2', 'Up Qty')
                ->setCellValue('T2', 'Jumlah FC Produksi yang di Budgeting')
                ->setCellValue('U2', '% Up')
                ->setCellValue('V2', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:V2');

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, removeEmoji($row->i_product_base))
                    ->setCellValue('C' . $kolom, removeEmoji($row->e_product_basename))
                    ->setCellValue('D' . $kolom, removeEmoji($row->e_color_name))
                    ->setCellValue('E' . $kolom, removeEmoji($row->kategori))
                    ->setCellValue('F' . $kolom, removeEmoji($row->sub_kategori))
                    ->setCellValue('G' . $kolom, removeEmoji($row->brand))
                    ->setCellValue('H' . $kolom, removeEmoji($row->style))
                    ->setCellValue('I' . $kolom, $row->n_fc_berjalan)
                    ->setCellValue('J' . $kolom, $row->qty_do)
                    ->setCellValue('K' . $kolom, $row->n_quantity_fc)
                    ->setCellValue('L' . $kolom, $row->n_fc_next)
                    ->setCellValue('M' . $kolom, $row->n_quantity_stock)
                    ->setCellValue('N' . $kolom, $row->n_quantity_wip)
                    ->setCellValue('O' . $kolom, $row->n_quantity_unitjahit)
                    ->setCellValue('P' . $kolom, $row->n_quantity_pengadaan)
                    ->setCellValue('Q' . $kolom, $row->n_quantity_packing)
                    ->setCellValue('R' . $kolom, $row->n_quantity_tmp)
                    ->setCellValue('S' . $kolom, $row->persen_up)
                    ->setCellValue('T' . $kolom, $row->n_quantity)
                    ->setCellValue('U' . $kolom, number_format(($row->persen_up > 0 && $row->n_quantity_tmp > 0) ? ($row->persen_up * 100 / $row->n_quantity_tmp) : 0, 0) . ' %')
                    ->setCellValue('V' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':V' . $kolom);

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

    public function view()
    {
        $data = check_role($this->i_menu, 2);
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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => "List " . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->company)->row(),
            'head'          => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );

        $this->Logger->write('Membuka Menu Lihat  ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval()
    {
        $data = check_role($this->i_menu, 7);
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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approval " . $this->global['title'],
            'title_list'    => "List " . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->company)->row(),
            'head'          => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );

        $this->Logger->write('Membuka Menu Approve  ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
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


    public function checkfcproduksi()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $bulan       = $this->input->post('bulan', TRUE);
        $tahun       = $this->input->post('tahun', TRUE);
        $periode = $tahun . $bulan;

        $query = $this->db->query("SELECT periode FROM tm_forecast_produksi WHERE periode = '$periode' AND f_over_budget = 'f' AND id_company = '$this->id_company' AND i_status IN ('1', '2', '3', '6')", FALSE);

        if ($query->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /** overbudget */
    public function export_template_overbudget()
    {
        $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $query = $this->mmaster->get_list_barang_template_overbudget();
        $allBarang = $query->result();

        $spreadsheet = new Spreadsheet;
        $sharedStyle = new Style();
        $sharedStyle->applyFromArray(
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

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Calibri')
            ->setSize(9);
        foreach (range('A', 'G') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

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
        $validation->setFormula1(1);
        $validation->setFormula2(999999999);

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '#')
            ->setCellValue('B1', 'ID Barang')
            ->setCellValue('C1', 'KODE')
            ->setCellValue('D1', 'Nama Barang')
            ->setCellValue('E1', 'Warna')
            ->setCellValue('F1', 'Quantity')
            ->setCellValue('G1', 'Keterangan');

        $index = 1;
        $row = 2;
        foreach ($allBarang as $barang) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A$row", $index)
                ->setCellValue("B$row", $barang->id)
                ->setCellValue("C$row", $barang->i_product_wip)
                ->setCellValue("D$row", $barang->e_product_wipname)
                ->setCellValue("E$row", $barang->e_color_name)
                ->setCellValue("F$row", "")
                ->setCellValue("G$row", "");

            $spreadsheet->getActiveSheet()
                        ->duplicateStyle($sharedStyle, "A$row:G$row");

            $index++;
            $row++;
        }

        $spreadsheet->getActiveSheet()->setDataValidation("F2:F$row", $validation);
        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        $spreadsheet->getActiveSheet()->getStyle("F2:G$row")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        $writer = new Xls($spreadsheet);
        $nama_file = "Format_Upload_Forecast_Overbudget.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    public function read_data_from_excel()
    {
        $config = array(
            'upload_path'   => "./import/fcproduksi/",
            'allowed_types' => "xls",
            'file_name'     => "TEST_UPLOAD_FILE_OVERBUDGET.xls",
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload("file")) {
            echo 'error';
            return;
        }

        $inputFileName = $config['upload_path'] . $config['file_name'];
        $spreadsheet   = IOFactory::load($inputFileName);
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');

        $bagian = @$this->input->get('bagian') ?? 'PPIC';
        $query = $this->mmaster->get_list_barang_template_overbudget();
        $allBarang = $query->result();

        $dataUpload = [];
        for ($n = 2; $n <= $hrow; $n++) {
            $id_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
            $qty = (int)$spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue();
            $keterangan = $spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue();

            foreach ($allBarang as $barang) {
                if ($qty <= 0) {
                    continue;
                }
                if ($barang->id == $id_product) {
                    $item = [
                        'id_product' => $id_product,
                        'qty' => $qty,
                        'keterangan' => $keterangan
                    ];
                    $text = $barang->i_product_wip.' - '.$barang->e_product_wipname.' ('.$barang->e_color_name.')';
                    $item['text'] = $text;

                    $dataUpload[] = $item;
                }
            }
        }
        echo json_encode($dataUpload);
    }

}
/* End of file Cform.php */