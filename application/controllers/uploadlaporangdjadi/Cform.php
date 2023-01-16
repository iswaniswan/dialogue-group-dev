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

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2050124';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->i_menu = '2050124';
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
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'customer'      => $this->mmaster->customer($this->session->userdata('id_company'))->result(),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformmain', $data);
    }

    public function gudang()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_master_gudang');
            $this->db->where('i_kode_jenis', 'JNG0006');
            //$this->db->like("UPPER(i_kode_master)", $cari);
            //$this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $itype) {
                $filter[] = array(
                    'id' => $itype->i_kode_master,
                    'text' => $itype->e_nama_master,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getbarang()
    {
        $ikodemaster = $this->input->post('ikodemaster');
        $query = $this->mmaster->getbarang($ikodemaster);
        if ($query->num_rows() > 0) {
            $c  = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .= "<option value=" . $row->i_material . " >" . $row->i_material . "-" . $row->e_material_name . "</option>";
            }
            $kop  = "<option value=\"BRG\" selected>  Semua Barang  " . $c . "</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        } else {
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }


        $ibagian  = $this->input->post('ibagian', TRUE);
        $bulan       = $this->input->post('bulan', TRUE);
        $tahun       = $this->input->post('tahun', TRUE);
        $filename = "Laporan_Gudang_Jadi_" . $ibagian . "_" . $tahun . $bulan . ".xls";

        $config = array(
            'upload_path'   => "./import/gudangjadi/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write("Upload File Laporan Gudang Jadi Periode : " . $tahun . $bulan);

            $param =  array(
                'ibagian' => $ibagian,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => 'berhasil'
            );

            echo json_encode($param);
        } else {
            $error = array('error' => $this->upload->display_errors());
            $param =  array(
                'status' => 'gagal',
                'error'  => $error
            );
            echo json_encode($param);
            //echo 'gagal';
        }
    }


    public function tambah()
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

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($idcustomer == "") $idcustomer = $this->uri->segment(5);
        if ($tahun == "") $tahun = $this->uri->segment(6);
        if ($bulan == "") $bulan = $this->uri->segment(7);


        if ($dfrom == "") $dfrom = $this->uri->segment(8);
        if ($dto == "") $dto = $this->uri->segment(9);

        if ($id == "") $id = $this->uri->segment(10);

        //var_dump($tahun, $bulan);
        //var_dump($ibagian);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input " . $this->global['title'],
            'title_list'    => $this->global['title'],
            // 'dso'           => $dso,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'customer'      => $this->mmaster->get_customer($idcustomer, $this->session->userdata('id_company'), $tahun, $bulan)->row(),
            /* 'head'       => $this->mmaster->dataheader($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan)->row(), */
            /* 'datadetail' => $this->mmaster->datadetail($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan, $id, 'all')->result_array(), */
        );

        //var_dump($this->mmaster->getpartnerbyid($partner));
        //die();
        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);
        //var_dump($this->mmaster->cek_datadet($dso)->result_array());
        $this->load->view($this->global['folder'] . '/vforminput', $data);
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
                        'id'   => $row->id,
                        'text' => $row->i_product_base . ' - ' . $row->e_product_basename . ' - ' . $row->e_color_name
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Cari Berdasarkan Nama / Kode Barang!"
            );
        }
        echo json_encode($filter);
    }

    public function productcolor()
    {
        $filter = [];
        $data = $this->mmaster->productcolor($this->input->post('q'));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'iproductbase'   => $row->i_product_base,
                        'icolor'         => $row->i_color,
                        'colorname'      => $row->e_color_name
                    );
                }
            }
        echo json_encode($filter);

    }

    public function get_product_detail()
    {
        header("Content-Type: application/json", true);
        $id = $this->input->post('id');
        $year = $this->input->post('tahun');
        $month = $this->input->post('bulan');
        $i_periode1 = date('Ym', strtotime('-1 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode2 = date('Ym', strtotime('-2 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
		$i_periode3 = date('Ym', strtotime('-3 month', strtotime($year.'-'.$month))); /** Penambahan Bulan Sebanyak 1 Bulan */
        $data = $this->db->query("
            WITH cte AS (
                SELECT
                    id_product,
                    sum(n_quantity) AS n_quantity,
                    round(sum(n_quantity) / sum(count_op),2) AS n_rata2
                FROM
                    (
                    SELECT
                        id_product,
                        sum(n_quantity) AS n_quantity,
                        CASE
                            WHEN sum(n_quantity) > 0 THEN 1
                            ELSE 0
                        END AS count_op
                    FROM
                        tm_spb_distributor_item a
                    INNER JOIN tm_spb_distributor b ON
                        (b.id = a.id_document)
                    WHERE
                        b.i_status = '6'
                        AND to_char(d_document, 'YYYYMM') = '$i_periode1'
                        AND b.id_company = '$this->id_company '
                    GROUP BY
                        1
                UNION ALL
                    SELECT
                        id_product,
                        sum(n_quantity) AS n_quantity,
                        CASE
                            WHEN sum(n_quantity) > 0 THEN 1
                            ELSE 0
                        END AS count_op
                    FROM
                        tm_spb_distributor_item a
                    INNER JOIN tm_spb_distributor b ON
                        (b.id = a.id_document)
                    WHERE
                        b.i_status = '6'
                        AND to_char(d_document, 'YYYYMM') = '$i_periode2'
                        AND b.id_company = '$this->id_company '
                    GROUP BY
                        1
                UNION ALL
                    SELECT
                        id_product,
                        sum(n_quantity) AS n_quantity,
                        CASE
                            WHEN sum(n_quantity) > 0 THEN 1
                            ELSE 0
                        END AS count_op
                    FROM
                        tm_spb_distributor_item a
                    INNER JOIN tm_spb_distributor b ON
                        (b.id = a.id_document)
                    WHERE
                        b.i_status = '6'
                        AND to_char(d_document, 'YYYYMM') = '$i_periode3'
                        AND b.id_company = '$this->id_company '
                    GROUP BY
                        1
                    ) AS x
                GROUP BY
                    1
            )
            SELECT b.e_class_name, coalesce(n_rata2,0) AS n_rata2, a.v_unitprice
            FROM tr_product_base a
            INNER JOIN tr_class_product b ON (b.id=a.id_class_product)
            LEFT JOIN cte c ON (c.id_product = a.id)
            WHERE a.id = '$id'
        ",false);
        /* $this->db->select("b.e_class_name");
        $this->db->from("tr_product_base a");
        $this->db->join("tr_class_product b", "b.id=a.id_class_product");
        $this->db->where("a.id", $id);
        $data = $this->db->get(); */
        echo json_encode($data->result_array());
    }


    public function loadview()
    {

        $ibagian    = $this->uri->segment(4);
        $tahun    = $this->uri->segment(5);
        $bulan    = $this->uri->segment(6);
        
        $filename = "Laporan_Gudang_Jadi_" . $ibagian . "_" . $tahun . $bulan . ".xls";
        

        $dfrom = $this->uri->segment(8);
        $dto = $this->uri->segment(9);

        //var_dump($filename);
        $inputFileName = './import/gudangjadi/' . $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');

        $aray = array();
        $aray_notfound = array();
        for ($n = 2; $n <= $hrow; $n++) {

            $id_product_base    = $spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue();
			$i_product_base     = $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue();
			$i_color            = $spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue();
            $e_product_basename = $spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue();
            $e_color_name       = $spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue();
			$n_saldo_awal       = $spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue();
			

            if ($ibagian != "") {
                $cek_produk = $this->mmaster->cek_produk($id_product_base, $i_product_base);
                if ($cek_produk->num_rows() > 0) {
                    $aray[] = array(
                        'i_product_base'        => $i_product_base,
                        'e_product_basename'    => $cek_produk->row()->e_product_basename,
                        'i_color'               => $i_color,
                        'e_color_name'          => $cek_produk->row()->e_color_name,
                        'n_saldo_awal'          => $n_saldo_awal,
                        'id_product_base'       => $id_product_base
                    );
                } else {
                    $aray_notfound[] = array(
                        'i_product_base'        => $i_product_base,
                        'i_color'               => $i_color,
                        'n_saldo_awal'          => $n_saldo_awal,
                        'id_product_base'       => $id_product_base
                    );
                }
            }
        }

        // $data = array(
        //     'folder'        => $this->global['folder'],
        //     'title'         => "Input ".$this->global['title'],
        //     'title_list'    => $this->global['title'],
        //     'dso'           => $dso,
        //     'partner'       => $this->mmaster->getpartnerbyid($partner),
        //     'data2'         => $aray,
        // );

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input " . $this->global['title'],
            'title_list'    => $this->global['title'],
            // 'dso'           => $dso,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            /* 'head'          => $this->mmaster->dataheader($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan)->row(), */
            'datadetail'    => $aray,
            'datadetailnon' => $aray_notfound,
        );
        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformupload', $data);
    }

    public function datamaterial()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("
                select a.*,b.e_satuan from tr_material a, tr_satuan b 
                where a.i_satuan_code=b.i_satuan_code 
                and (a.i_kode_kelompok='KTB0004' or a.i_kode_kelompok='KTB0005')
                and (a.i_material like '%$cari%' or a.e_material_name like '%$cari%') order by a.i_material");
            foreach ($data->result() as $material) {
                $filter[] = array(
                    'id'   => $material->i_material,
                    'name' => $material->e_material_name,
                    'text' => $material->i_material . ' - ' . $material->e_material_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getmaterial()
    {
        header("Content-Type: application/json", true);
        $ematerialname = $this->input->post('ematerialname');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
        $this->db->from("tr_material a");
        $this->db->join("tr_satuan b", "a.i_satuan_code=b.i_satuan_code");
        $this->db->where("UPPER(i_material)", $ematerialname);
        $this->db->order_by('a.i_material', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function datawip()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("
                select x.*, c.e_color_name from (
                    select a.i_product, b.e_namabrg, a.i_color  from tr_polacutting a
                    inner join tm_barang_wip b on (b.i_kodebrg = a.i_product)
                    group by  a.i_product,b.e_namabrg, a.i_color 
                ) as x 
                left join tr_color c on (x.i_color = c.i_color)
                where x.e_namabrg ilike '%$cari%' or x.i_product ilike '%$cari%'
                order by x.e_namabrg");
            foreach ($data->result() as $data) {
                $filter[] = array(
                    'id'   => $data->i_product . "|" . $data->i_color,
                    'name' => $data->e_namabrg,
                    'text' => $data->i_product . ' - ' . $data->e_namabrg . ' - ' . $data->e_color_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getwip()
    {
        header("Content-Type: application/json", true);
        $iwip = $this->input->post('iwip');
        $icolor = $this->input->post('icolor');
        $data = $this->db->query("
                select x.*, c.e_color_name from (
                    select a.i_product, b.e_namabrg, a.i_color  from tr_polacutting a
                    inner join tm_barang_wip b on (b.i_kodebrg = a.i_product)
                    where a.i_product = '$iwip' and a.i_color = '$icolor'
                    group by  a.i_product,b.e_namabrg, a.i_color 
                ) as x 
                left join tr_color c on (x.i_color = c.i_color)
                order by x.e_namabrg");
        echo json_encode($data->result_array());
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);
        $bulan  = $this->input->post('bulan', TRUE);
        $tahun  = $this->input->post('tahun', TRUE);
        $idcompany = $this->session->userdata('id_company');
        $periode = $tahun . $bulan;

        $jml  = $this->input->post('jml', TRUE);
        //idproduct  nquantity eremark

        $this->db->trans_begin();
        $this->mmaster->deletedata($idcompany, $ibagian, $periode);        
        for ($i = 1; $i <= $jml; $i++) {
            $iproduct = $this->input->post('i_product_base' . $i, TRUE);
            $icolor = str_replace(",", "", $this->input->post('i_color' . $i, TRUE));
            $nsaldoawal = str_replace(",", "", $this->input->post('n_saldo_awal' . $i, TRUE));
            $idproduct = $this->input->post('id_product_base' . $i, TRUE);

            $this->mmaster->simpan($idcompany, $ibagian, $periode, $iproduct, $icolor, $nsaldoawal, $idproduct);

        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'id'     => $ibagian
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $periode,
                'id'     => $ibagian
            );
            $this->Logger->write('Simpan Data ' . $this->global['title']);
        }

        $this->load->view('pesan2', $data);
    }

    public function export()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian = $this->uri->segment(4);
        $month = $this->uri->segment(5);
        $year  = $this->uri->segment(6);
        $periode = $year.$month;

        $query = $this->mmaster->cek_datadet($ibagian, $year, $month)->result();

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
            foreach (range('A', 'F') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);

            }
            /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
            // $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
            // $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
            // $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'ID Produk Base')
                ->setCellValue('C1', 'Kode Barang')
                ->setCellValue('D1', 'Kode Warna')
                ->setCellValue('E1', 'Nama Barang')
                ->setCellValue('F1', 'Warna')
                ->setCellValue('G1', 'Saldo Awal')
                ;

            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H1');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:H2');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:H3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:G1');

            $kolom = 2;
            $nomor = 1;
            foreach ($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->id)
                    ->setCellValue('C' . $kolom, $row->i_product_base)
                    ->setCellValue('D' . $kolom, $row->i_color)
                    ->setCellValue('E' . $kolom, $row->e_product_basename)
                    ->setCellValue('F' . $kolom, $row->e_color_name)
                    ->setCellValue('G' . $kolom, $row->n_saldo_awal);
                //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':G' . $kolom);

                $kolom++;
                $nomor++;
            }

            $writer = new Xls($spreadsheet);
            $nama_file = "Laporan_Saldoawal_Gudang_Jadi_".$periode . ".xls";
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

        $idcompany   = $this->input->post("idcompany", true);

        $ibagian    = $this->input->post("ibagian", true);

        $periode    = $this->input->post("periode", true);

        //get company
        if ($idcompany == "") $idcompany = $this->uri->segment(4);

        //get bagian
        if ($ibagian == "") $ibagian = $this->uri->segment(5);

        //get periode
        if ($periode == "") $periode = $this->uri->segment(6);
        $tahun = substr($periode,0,4);
        $bulan = substr($periode,4,5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'idcompany'     => $idcompany,
            'ibagian'       => $ibagian,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'datadetail'    => $this->mmaster->datadetail($this->session->userdata('id_company'), $ibagian, $periode)->result_array()
        );

        //var_dump($this->mmaster->getpartnerbyid($partner));
        //die();
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        //var_dump($this->mmaster->cek_datadet($dso)->result_array());
        $this->load->view($this->global['folder'] . '/vformview', $data);
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

        $this->Logger->write('Membuka Menu Approval ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function approve()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Approval " . $this->global['title'],
            'tahun'     => date('Y'),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);
        $bulan  = $this->input->post('bulan', TRUE);
        $tahun  = $this->input->post('tahun', TRUE);
        $idcompany = $this->session->userdata('id_company');
        $periode = $tahun . $bulan;

        $jml            = $this->input->post('jml', TRUE);

        for ($i = 1; $i <= $jml; $i++) {
            $id = $this->input->post('id' . $i, TRUE);
            $iproduct = $this->input->post('i_product_base' . $i, TRUE);
            $icolor = str_replace(",", "", $this->input->post('i_color' . $i, TRUE));
            $nsaldoawal = str_replace(",", "", $this->input->post('n_saldo_awal' . $i, TRUE));
            $idproduct = $this->input->post('id_product_base' . $i, TRUE);
            $this->mmaster->updatedetail($id, $idcompany, $ibagian, $periode, $iproduct, $icolor, $nsaldoawal, $idproduct);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses'    => true,
                'kode'      => $periode,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany   = $this->input->post("idcompany", true);

        $ibagian    = $this->input->post("ibagian", true);

        $periode    = $this->input->post("periode", true);

        //get company
        if ($idcompany == "") $idcompany = $this->uri->segment(4);

        //get bagian
        if ($ibagian == "") $ibagian = $this->uri->segment(5);

        //get periode
        if ($periode == "") $periode = $this->uri->segment(6);
        $tahun = substr($periode,0,4);
        $bulan = substr($periode,4,5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'idcompany'     => $idcompany,
            'ibagian'       => $ibagian,
            'tahun'         => $tahun,
            'bulan'         => $bulan,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'datadetail'    => $this->mmaster->datadetail($this->session->userdata('id_company'), $ibagian, $periode)->result_array()
        );

        //var_dump($this->mmaster->getpartnerbyid($partner));
        //die();
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        //var_dump($this->mmaster->cek_datadet($dso)->result_array());
        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function exportperiode()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany   = $this->uri->segment(4);

        $ibagian    = $this->uri->segment(5);

        $periode    = $this->uri->segment(6);

        $query = $this->mmaster->exportperiode($ibagian, $periode)->result();

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
            foreach (range('A', 'F') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);

            }
            /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
            // $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
            // $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
            // $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'ID Produk Base')
                ->setCellValue('C1', 'Kode Barang')
                ->setCellValue('D1', 'Kode Warna')
                ->setCellValue('E1', 'Nama Barang')
                ->setCellValue('F1', 'Warna')
                ->setCellValue('G1', 'Saldo Awal')
                ;

            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H1');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:H2');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:H3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:G1');

            $kolom = 2;
            $nomor = 1;
            foreach ($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->id)
                    ->setCellValue('C' . $kolom, $row->i_product_base)
                    ->setCellValue('D' . $kolom, $row->i_color)
                    ->setCellValue('E' . $kolom, $row->e_product_basename)
                    ->setCellValue('F' . $kolom, $row->e_color_name)
                    ->setCellValue('G' . $kolom, $row->n_saldo_awal);
                //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':G' . $kolom);

                $kolom++;
                $nomor++;
            }

            $writer = new Xls($spreadsheet);
            $nama_file = "Laporan_Saldoawal_Gudang_Jadi_".$periode . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

}
/* End of file Cform.php */