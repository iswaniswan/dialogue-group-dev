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
    public $i_menu = '20701';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->i_menu = '20701';
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
            'title_list'    => 'List ' . $this->global['title'],
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
        $idcustomer  = $this->input->post('idcustomer', TRUE);
        $bulan       = $this->input->post('bulan', TRUE);
        $tahun       = $this->input->post('tahun', TRUE);
        $filename = "FC_Distributor_" . $idcustomer . "_" . $tahun . $bulan . ".xls";

        $config = array(
            'upload_path'   => "./import/fcdistributor/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Forecast Customer : ' . $idcustomer . "Periode : " . $tahun . $bulan);

            $param =  array(
                'ibagian' => $ibagian,
                'idcustomer' => $idcustomer,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => 'berhasil'
            );

            echo json_encode($param);
        } else {
            $param =  array(
                'status' => 'gagal'
            );
            echo json_encode($param);
            //echo 'gagal';
        }
    }

    public function load_warna()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }


        $ibagian  = $this->input->post('ibagian', TRUE);
        $idcustomer  = $this->input->post('idcustomer', TRUE);
        $bulan       = $this->input->post('bulan', TRUE);
        $tahun       = $this->input->post('tahun', TRUE);
        $filename = "FC_Distributor_Warna_" . $idcustomer . "_" . $tahun . $bulan . ".xls";

        $config = array(
            'upload_path'   => "./import/fcdistributor/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile_warna")) {
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Forecast Perwarna Customer : ' . $idcustomer . "Periode : " . $tahun . $bulan);

            $param =  array(
                'ibagian' => $ibagian,
                'idcustomer' => $idcustomer,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'status' => 'berhasil'
            );

            echo json_encode($param);
        } else {
            $param =  array(
                'status' => 'gagal'
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

    public function get_product_detail()
    {
        header("Content-Type: application/json", true);
        $id = $this->input->post('id');
        $year = $this->input->post('tahun');
        $month = $this->input->post('bulan');
        $i_periode1 = date('Ym', strtotime('-1 month', strtotime($year . '-' . $month)));
        /** Penambahan Bulan Sebanyak 1 Bulan */
        $i_periode2 = date('Ym', strtotime('-2 month', strtotime($year . '-' . $month)));
        /** Penambahan Bulan Sebanyak 1 Bulan */
        $i_periode3 = date('Ym', strtotime('-3 month', strtotime($year . '-' . $month)));
        /** Penambahan Bulan Sebanyak 1 Bulan */
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
        ", false);
        /* $this->db->select("b.e_class_name");
        $this->db->from("tr_product_base a");
        $this->db->join("tr_class_product b", "b.id=a.id_class_product");
        $this->db->where("a.id", $id);
        $data = $this->db->get(); */
        echo json_encode($data->result_array());
    }


    public function loadview_warna()
    {

        $idcustomer    = $this->uri->segment(4);
        $tahun    = $this->uri->segment(5);
        $bulan    = $this->uri->segment(6);
        $ibagian    = $this->uri->segment(7);
        // $filename = "SO_QC-SET_".$dso.".xls";
        $filename = "FC_Distributor_Warna_" . $idcustomer . "_" . $tahun . $bulan . ".xls";
        //$e_bulan =mbulan($bulan);

        $dfrom = $this->uri->segment(8);
        $dto = $this->uri->segment(9);

        //var_dump($filename);
        $inputFileName = './import/fcdistributor/' . $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('B');

        $aray = array();
        $aray_notfound = array();
        for ($n = 2; $n <= $hrow; $n++) {
            $idproduct = strtoupper($spreadsheet->getActiveSheet()->getCell('A' . $n)->getValue());
            $i_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
            $query = $this->db->query("SELECT v_unitprice FROM tr_product_base WHERE f_status = 't' AND id = '$idproduct' AND id_company = '$this->id_company'", FALSE);
            if ($query->num_rows() > 0) {
                $v_harga = $query->row()->v_unitprice;
            } else {
                $v_harga = 0;
            }
            $rata2  = $spreadsheet->getActiveSheet()->getCell('K' . $n)->getValue();
            $qty  = $spreadsheet->getActiveSheet()->getCell('L' . $n)->getCalculatedValue();
            /* $v_harga  = $spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue(); */
            $e_remark  = $spreadsheet->getActiveSheet()->getCell('M' . $n)->getValue();

            //$ambilsaldo = $this->mmaster->cek_datadet_upload($dso, $kodewip,$icolor,$partner )->row();
            // var_dump($ambilsaldo);
            // die();
            // break;
            // $saldoawal = $ambilsaldo->saldoawal;
            // $saldoakhir = $ambilsaldo->saldoakhir;

            // $so = $spreadsheet->getActiveSheet()->getCell('G'.$n)->getCalculatedValue();
            // $selisih = $so - abs($saldoakhir);
            // var_dump($so, $saldoakhir, $selisih);
            // die();

            // var_dump($qty);

            if ($qty > 0 && $idproduct != "") {
                $cek_produk = $this->mmaster->cek_produk($idproduct, $i_product);
                if ($cek_produk->num_rows() > 0) {
                    $aray[] = array(
                        'id_product_base'       => $idproduct,
                        'i_product_base'        => $i_product,
                        'e_product_basename'    => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
                        'e_nama_divisi'         => $spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue(),
                        'e_class_name'          => $spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue(),
                        'v_harga'               => $v_harga,
                        'n_rata2'               => $rata2,
                        'n_quantity'            => $qty,
                        'n_quantity_sisa'       => $qty,
                        'e_remark'              => $e_remark,
                        'e_color_name'          => $spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue(),
                    );
                } else {
                    $aray_notfound[] = array(
                        'id_product_base'       => null,
                        'i_product_base'        => $i_product,
                        'e_product_basename'    => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
                        'e_nama_divisi'         => $spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue(),
                        'e_class_name'          => $spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue(),
                        'v_harga'               => $v_harga,
                        'n_rata2'               => $rata2,
                        'n_quantity'            => $qty,
                        'n_quantity_sisa'       => $qty,
                        'e_remark'              => $e_remark,
                        'e_color_name'          => $spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue(),
                    );
                }
            } else if ($qty > 0) {
                $aray_notfound[] = array(
                    'id_product_base'       => null,
                    'i_product_base'        => $i_product,
                    'e_product_basename'    => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
                    'e_nama_divisi'         => $spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue(),
                    'e_class_name'          => $spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue(),
                    'v_harga'               => $v_harga,
                    'n_rata2'               => $rata2,
                    'n_quantity'            => $qty,
                    'n_quantity_sisa'       => $qty,
                    'e_remark'              => $e_remark,
                    'e_color_name'          => $spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue(),
                );
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
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'customer'      => $this->mmaster->get_customer($idcustomer, $this->session->userdata('id_company'), $tahun, $bulan)->row(),
            /* 'head'          => $this->mmaster->dataheader($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan)->row(), */
            'datadetail'    => $aray,
            'datadetailnon'    => $aray_notfound,
        );
        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformupload', $data);
    }


    function is_decimal($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }

    function order_by_array($a, $b)
    {
        /*return $b['weight'] > $a['weight'] ? 1 : -1;*/
        return $this->is_decimal($b['n_quantity']) == true and $this->is_decimal($a['n_quantity']) == false ? 1 : 0;
    }

    public function loadview()
    {

        $idcustomer = $this->uri->segment(4);
        $tahun    = $this->uri->segment(5);
        $bulan    = $this->uri->segment(6);
        $ibagian  = $this->uri->segment(7);
        $filename = "FC_Distributor_" . $idcustomer . "_" . $tahun . $bulan . ".xls";

        $dfrom = $this->uri->segment(8);
        $dto = $this->uri->segment(9);

        $inputFileName = './import/fcdistributor/' . $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('B');

        $aray = array();
        $aray_notfound = array();
        for ($n = 2; $n <= $hrow; $n++) {
            $i_product = strtoupper($spreadsheet->getActiveSheet()->getCell('A' . $n)->getValue());
            $query = $this->db->query("SELECT DISTINCT v_unitprice FROM tr_product_base WHERE f_status = 't' AND i_product_base = '$i_product' AND id_company = '$this->id_company'", FALSE);
            if ($query->num_rows() > 0) {
                $v_harga = $query->row()->v_unitprice;
            } else {
                $v_harga = 0;
            }
            $rata2 = $spreadsheet->getActiveSheet()->getCell('I' . $n)->getValue();
            $qty = $spreadsheet->getActiveSheet()->getCell('J' . $n)->getCalculatedValue();
            $e_remark  = $spreadsheet->getActiveSheet()->getCell('K' . $n)->getValue();

            if ($qty > 0 && $i_product != "") {
                $cek_produk = $this->mmaster->cek_produk_warna($i_product);
                if ($cek_produk->num_rows() > 0) {
                    foreach ($cek_produk->result() as $row) {
                        $quantity = $qty / $cek_produk->num_rows();
                        $aray[] = array(
                            'id_product_base'       => $row->id,
                            'i_product_base'        => $row->i_product_base,
                            'e_product_basename'    => $row->e_product_basename,
                            'e_nama_divisi'         => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
                            'e_class_name'          => $spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue(),
                            'v_harga'               => $v_harga,
                            'n_rata2'               => $rata2,
                            'n_quantity'            => $quantity,
                            'n_quantity_sisa'       => $quantity,
                            'e_remark'              => $e_remark,
                            'e_color_name'          => $row->e_color_name,
                        );
                    }
                } else {
                    $aray_notfound[] = array(
                        'id_product_base'       => null,
                        'i_product_base'        => $i_product,
                        'e_product_basename'    => $spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue(),
                        'e_nama_divisi'         => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
                        'e_class_name'          => $spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue(),
                        'v_harga'               => $v_harga,
                        'n_rata2'               => $rata2,
                        'n_quantity'            => $qty,
                        'n_quantity_sisa'       => $qty,
                        'e_remark'              => $e_remark,
                        'e_color_name'          => "",
                    );
                }
            } else if ($qty > 0) {
                $aray_notfound[] = array(
                    'id_product_base'       => null,
                    'i_product_base'        => $i_product,
                    'e_product_basename'    => $spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue(),
                    'e_nama_divisi'         => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
                    'e_class_name'          => $spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue(),
                    'v_harga'               => $v_harga,
                    'n_rata2'               => $rata2,
                    'n_quantity'            => $qty,
                    'n_quantity_sisa'       => $qty,
                    'e_remark'              => $e_remark,
                    'e_color_name'          => "",
                );
            }
        }



        // usort($aray, 'order_by_array');
        /* usort($aray, function($a, $b) {
            return ceil(strlen($b['n_quantity']) - strlen($a['n_quantity']));
        }); */
        usort($aray, function ($b, $a) {
            return strlen($a['n_quantity']) <=> strlen($b['n_quantity']) ?: $a['n_quantity'] <=> $b['n_quantity'] ?: $a['i_product_base'] <=> $b['i_product_base'] ?: $b['e_color_name'] <=> $a['e_color_name'];
            //return strlen($b['n_quantity']) <=> strlen($a['n_quantity']) ?: $b['i_product_base'] <=> $a['i_product_base']?: $a['e_color_name'] <=> $b['e_color_name'];
        });

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'customer'      => $this->mmaster->get_customer($idcustomer, $this->session->userdata('id_company'), $tahun, $bulan)->row(),
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

        $id    = $this->input->post('id', TRUE);
        $ibagian    = $this->input->post('ibagian', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        $ibulan  = $this->input->post('ibulan', TRUE);
        $tahun  = $this->input->post('tahun', TRUE);
        $idcompany = $this->session->userdata('id_company');
        $periode = $tahun . $ibulan;

        $jml  = $this->input->post('jml', TRUE);
        //idproduct  nquantity eremark

        $this->db->trans_begin();
        if ($id == "" || $id == NULL) $id = $this->mmaster->runningid();
        $this->mmaster->simpan($id, $idcompany, $ibagian, $icustomer, $periode);
        /* $this->mmaster->hapusdetail($idcompany, $id); */
        for ($i = 1; $i <= $jml; $i++) {
            $idproduct = $this->input->post('idproduct' . $i, TRUE);
            $v_harga = str_replace(",", "", $this->input->post('price' . $i, TRUE));
            $rata = str_replace(",", "", $this->input->post('rata' . $i, TRUE));
            $qty = str_replace(",", "", $this->input->post('nquantity' . $i, TRUE));
            $eremark   = $this->input->post('eremark' . $i, TRUE);
            if ($qty > 0 && ($idproduct != null || $idproduct != '')) {
                $this->mmaster->simpandetail($idcompany, $id, $idproduct, $v_harga, $qty, $eremark, $rata);
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

    public function export_warna()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $month = $this->uri->segment(4);
        $year  = $this->uri->segment(5);

        $query = $this->mmaster->cek_datadet($this->session->userdata('id_company'), $year, $month)->result();

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
            foreach (range('A', 'M') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            }
            /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
            // $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
            // $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
            // $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID Barang')
                ->setCellValue('B1', 'Kode Barang')
                ->setCellValue('C1', 'Nama Barang')
                ->setCellValue('D1', 'Warna')
                ->setCellValue('E1', 'Divisi')
                ->setCellValue('F1', 'Kategori')
                ->setCellValue('G1', 'Sub Kategori')
                ->setCellValue('H1', 'Brand')
                ->setCellValue('I1', 'Series')
                ->setCellValue('J1', 'Kategori Penjualan')
                ->setCellValue('K1', 'Rata-rata OP')
                ->setCellValue('L1', 'Jumlah FC')
                ->setCellValue('M1', 'Keterangan');

            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H1');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:H2');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:H3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:M1');

            $kolom = 2;
            $nomor = 1;
            foreach ($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $row->id_product_base)
                    ->setCellValue('B' . $kolom, $row->i_product_base)
                    ->setCellValue('C' . $kolom, $row->e_product_basename)
                    ->setCellValue('D' . $kolom, $row->e_color_name)
                    ->setCellValue('E' . $kolom, $row->e_nama_divisi)
                    ->setCellValue('F' . $kolom, $row->e_nama_kelompok)
                    ->setCellValue('G' . $kolom, $row->e_type_name)
                    ->setCellValue('H' . $kolom, $row->e_brand_name)
                    ->setCellValue('I' . $kolom, $row->e_style_name)
                    ->setCellValue('J' . $kolom, $row->e_class_name)
                    ->setCellValue('K' . $kolom, $row->n_rata2)
                    ->setCellValue('L' . $kolom, $row->n_quantity)
                    ->setCellValue('M' . $kolom, $row->e_remark);
                //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':M' . $kolom);

                $kolom++;
                $nomor++;
            }

            $writer = new Xls($spreadsheet);
            $nama_file = "FC_Distributor_Warna" . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    public function export()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $month = $this->uri->segment(4);
        $year  = $this->uri->segment(5);

        $query = $this->mmaster->cek_datadetail_warna($this->session->userdata('id_company'), $year, $month)->result();

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
            foreach (range('A', 'K') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Kode Barang')
                ->setCellValue('B1', 'Nama Barang')
                ->setCellValue('C1', 'Divisi')
                ->setCellValue('D1', 'Kategori')
                ->setCellValue('E1', 'Sub Kategori')
                ->setCellValue('F1', 'Brand')
                ->setCellValue('G1', 'Series')
                ->setCellValue('H1', 'Kategori Penjualan')
                ->setCellValue('I1', 'Rata-rata OP')
                ->setCellValue('J1', 'Jumlah FC')
                ->setCellValue('K1', 'Keterangan');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:K1');

            $kolom = 2;
            $nomor = 1;
            foreach ($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $row->i_product_base)
                    ->setCellValue('B' . $kolom, $row->e_product_basename)
                    ->setCellValue('C' . $kolom, $row->e_nama_divisi)
                    ->setCellValue('D' . $kolom, $row->e_nama_kelompok)
                    ->setCellValue('E' . $kolom, $row->e_type_name)
                    ->setCellValue('F' . $kolom, $row->e_brand_name)
                    ->setCellValue('G' . $kolom, $row->e_style_name)
                    ->setCellValue('H' . $kolom, $row->e_class_name)
                    ->setCellValue('I' . $kolom, $row->n_rata2)
                    ->setCellValue('J' . $kolom, $row->n_quantity)
                    ->setCellValue('K' . $kolom, $row->e_remark);
                //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':K' . $kolom);

                $kolom++;
                $nomor++;
            }

            $writer = new Xls($spreadsheet);
            $nama_file = "FC_Distributor" . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    public function export_data()
    {
        $data = check_role($this->i_menu, 3);
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

        $bagian = $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row();
        $customer = $this->mmaster->get_customer($idcustomer, $this->session->userdata('id_company'), $tahun, $bulan)->row();
        $head = $this->mmaster->dataheader($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan, $id)->row();
        $query = $this->mmaster->dataexport($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan, $id, $iclass)->result_array();
        //$query = $this->mmaster->cek_datadet($this->session->userdata('id_company'), $tahun , $bulan)->result_array();
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
            foreach (range('A', 'M') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            }
            /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
            // $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
            // $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
            // $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No')
                ->setCellValue('B1', 'Kode Barang')
                ->setCellValue('C1', 'Nama Barang')
                ->setCellValue('D1', 'Warna')
                ->setCellValue('E1', 'Divisi')
                ->setCellValue('F1', 'Kategori')
                ->setCellValue('G1', 'Sub Kategori')
                ->setCellValue('H1', 'Kategori Penjualan')
                ->setCellValue('I1', 'Harga')
                ->setCellValue('J1', 'Rata2 OP (3 bln)')
                ->setCellValue('K1', 'Jumlah FC')
                ->setCellValue('L1', 'Keterangan');

            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H1');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:H2');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:H3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:L1');

            $kolom = 2;
            $nomor = 1;
            foreach ($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $row['id_product_base'])
                    ->setCellValue('B' . $kolom, $row['i_product_base'])
                    ->setCellValue('C' . $kolom, $row['e_product_basename'])
                    ->setCellValue('D' . $kolom, $row['e_color_name'])
                    ->setCellValue('E' . $kolom, $row['e_nama_divisi'])
                    ->setCellValue('F' . $kolom, $row['e_nama_kelompok'])
                    ->setCellValue('G' . $kolom, $row['e_type_name'])
                    ->setCellValue('H' . $kolom, $row['e_class_name'])
                    ->setCellValue('I' . $kolom, $row['v_harga'])
                    ->setCellValue('J' . $kolom, $row['n_rata2'])
                    ->setCellValue('K' . $kolom, $row['n_quantity'])
                    ->setCellValue('L' . $kolom, $row['e_remark']);
                //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':L' . $kolom);

                $kolom++;
                $nomor++;
            }

            $writer = new Xls($spreadsheet);
            $nama_file = "FC_Distributor.xls";
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
        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $bulan          = $this->input->post('bulan', TRUE);
        $bulan          = date("m", strtotime($bulan));
        $tahun          = $this->input->post('tahun', TRUE);
        $periode        = $tahun . $bulan;
        $jml            = $this->input->post('jml', TRUE);
        $idcompany      = $this->id_company;

        $this->Logger->write('Edit Data ' . $this->global['title'] . ' Kode : ' . $id . ' Periode : ' . $periode);
        $this->mmaster->updatedataheader($id, $ibagian, $icustomer, $periode);
        $this->mmaster->deletedatadetail($id);

        for ($i = 1; $i <= $jml; $i++) {
            $idproduct  = $this->input->post('idproduct' . $i, TRUE);
            $ikategori  = $this->input->post('category_penjualan' . $i, TRUE);
            $vprice     = $this->input->post('price' . $i, TRUE);
            $nrata      = $this->input->post('rata' . $i, TRUE);
            $nqty       = $this->input->post('nquantity' . $i, TRUE);
            $eremark    = $this->input->post('eremark' . $i, TRUE);
            //$this->mmaster->updatedatadetail($idproduct, $vprice, $nqty, $eremark, $id);
            $this->mmaster->simpandetail($idcompany, $id, $idproduct, $vprice, $nqty, $eremark, $nrata);
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
                'kode'      => $id,
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
            'head'          => $this->mmaster->dataheader($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan, $id)->row(),
            'datadetail'    => $this->mmaster->datadetail($this->session->userdata('id_company'), $idcustomer, $tahun . $bulan, $id, 'all')->result_array(),
        );

        //var_dump($this->mmaster->getpartnerbyid($partner));
        //die();
        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);
        //var_dump($this->mmaster->cek_datadet($dso)->result_array());
        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }
}
/* End of file Cform.php */