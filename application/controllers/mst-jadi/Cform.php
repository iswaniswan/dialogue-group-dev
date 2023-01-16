<?php
defined("BASEPATH") or exit("No direct script access allowed");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;*/
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller
{
    public $global = [];
    public $i_menu = "2010212";

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), "refresh");
        }

        $this->id_company = $this->session->id_company;
        $this->global["folder"] = $data[0]["e_folder"];
        $this->global["title"] = $data[0]["e_menu"];

        $this->load->model($this->global["folder"] . "/mmaster");
    }

    public function index()
    {
        $data = [
            "folder" => $this->global["folder"],
            "title" => $this->global["title"],
        ];

        $this->Logger->write("Membuka Menu " . $this->global["title"]);

        $this->load->view($this->global["folder"] . "/vformlist", $data);
    }

    function data()
    {
        echo $this->mmaster->data($this->i_menu, $this->global["folder"]);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), "refresh");
        }

        $id = $this->input->post("id", true);
        if ($id == "") {
            $id = $this->uri->segment(4);
        }
        if ($id != "") {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write(
                    "Update status " . $this->global["title"] . " Id : " . $id
                );
                echo json_encode($data);
            }
        }
    }

    public function cekkode()
    {
        $kode = $this->input->post("kode");
        $query = $this->mmaster->cekkode($kode);
        if ($query->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), "refresh");
        }

        $data = [
            "folder" => $this->global["folder"],
            "title" => "Tambah " . $this->global["title"],
            "title_list" => "List " . $this->global["title"],
            "class" => $this->db->get("tr_class_product")->result(),
        ];

        $this->Logger->write("Membuka Menu Tambah " . $this->global["title"]);

        $this->load->view($this->global["folder"] . "/vformadd", $data);
    }

    public function getdivisi()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $data = $this->mmaster->getdivisi($cari);
        foreach ($data->result() as $row) {
            $filter[] = [
                "id" => $row->id,
                "text" => $row->i_kode_divisi . " || " . $row->e_nama_divisi,
            ];
        }
        echo json_encode($filter);
    }

    public function getgroupdivisi()
    {
        $cari = strtoupper($this->input->get("q"));
        $data = $this->mmaster->getdivisi($cari);
        if ($data->num_rows() > 0) {
            $c = "";
            $spb = $data->result();
            foreach ($spb as $row) {
                $c .=
                    "<option value=" .
                    $row->id .
                    " >" .
                    $row->i_kode_divisi . " || " . $row->e_nama_divisi .
                    "</option>";
            }
            $kop =
                "<option value=\"\"> -- Pilih Divisi -- " .
                $c .
                "</option>";
            echo json_encode([
                "kop" => $kop,
            ]);
        } else {
            $kop = "<option value=\"\">Data Kosong</option>";
            echo json_encode([
                "kop" => $kop,
                "kosong" => "kopong",
            ]);
        }
    }

    public function getgroup()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $data = $this->mmaster->getgroup($cari);
        foreach ($data->result() as $igroup) {
            $filter[] = [
                "id" => $igroup->i_kode_group_barang,
                "text" => $igroup->e_nama_group_barang,
            ];
        }
        echo json_encode($filter);
    }

    public function getkelompok()
    {
        $igroupbrg = $this->input->post("igroupbrg");
        $idivisi = $this->input->post("idivisi");
        $query = $this->mmaster->getkelompok($igroupbrg, $idivisi);
        if ($query->num_rows() > 0) {
            $c = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .=
                    "<option value=" .
                    $row->i_kode_kelompok .
                    " >" .
                    $row->e_nama_kelompok .
                    "</option>";
            }
            $kop =
                "<option value=\"\"> -- Pilih Kategori barang -- " . "</option>" .
                $c;
            echo json_encode([
                "kop" => $kop,
            ]);
        } else {
            $kop = "<option value=\"\">Data Kosong</option>";
            echo json_encode([
                "kop" => $kop,
                "kosong" => "kopong",
            ]);
        }
    }

    public function getkelompokedit()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $igroupbrg = $this->input->post("igroupbrg");
        $idivisi = $this->input->post("idivisi");
        $data = $this->mmaster->getkelompokedit($cari, $igroupbrg, $idivisi);
        foreach ($data->result() as $row) {
            $filter[] = [
                "id" => $row->i_kode_kelompok,
                "text" => $row->e_nama_kelompok,
            ];
        }
        echo json_encode($filter);
    }

    public function getjenisedit()
    {
        $filter = [];
        $ikelompok = $this->input->post("ikelompok");
        $data = $this->mmaster->getjenis($ikelompok);
        foreach ($data->result() as $row) {
            $filter[] = [
                "id" => $row->i_type_code,
                "text" => $row->e_type_name,
            ];
        }
        echo json_encode($filter);
    }

    public function get_product()
    {
        $filter = [];
        $cari = str_replace("'", "", $this->input->get('q'));
        $data = $this->mmaster->get_product($cari);
        foreach ($data->result() as $row) {
            $filter[] = [
                "id" => $row->id,
                "text" => $row->text,
            ];
        }
        echo json_encode($filter);
    }

    public function getjenis()
    {
        $ikelompok = $this->input->post("ikelompok");
        $query = $this->mmaster->getjenis($ikelompok);
        if ($query->num_rows() > 0) {
            $c = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .=
                    "<option value=" .
                    $row->i_type_code .
                    " >" .
                    $row->e_type_name .
                    "</option>";
            }
            $kop =
                "<option value=\"\"> -- Pilih Jenis barang -- " .
                $c .
                "</option>";
            echo json_encode([
                "kop" => $kop,
            ]);
        } else {
            $kop = "<option value=\"\">Data Kosong</option>";
            echo json_encode([
                "kop" => $kop,
                "kosong" => "kopong",
            ]);
        }
    }

    public function getdatawip()
    {
        $iproductwip = explode("|", $this->input->post("iproductwip"));
        $i_productwip = $iproductwip[0];
        $i_product_wip = $iproductwip[1];

        $iproductbasenew = "";
        $wip = strlen($i_productwip);
        if ($wip == "7") {
            $qwarna = $this->db->query(
                "SELECT i_product_base FROM tr_product_base where i_product_wip='$i_productwip' ORDER BY i_product_base DESC LIMIT 1"
            );
            if ($qwarna->num_rows() > 0) {
                $row_warna = $qwarna->row();
                $iproductbase2 = $row_warna->i_product_base;
                $imotif = substr($iproductbase2, 8, 1);
                $imotif2 = substr($iproductbase2, 0, 8);
                $ikodemotif = $imotif + 1;
                $iproductbasenew = $imotif2 . $ikodemotif;
            } else {
                $iproductbasenew = $i_productwip . "00";
            }
        } else {
            $iproductbasenew = $i_productwip;
        }

        $query = $this->mmaster->getkodebarang($i_productwip);
        if ($query->num_rows() > 0) {
            $c = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .=
                    "<option value=" .
                    $row->i_status_produksi .
                    " >" .
                    $row->e_status_produksi .
                    "</option>";
            }
            $kop =
                "<option value=" .
                $row->i_status_produksi .
                " >" .
                $row->e_status_produksi .
                "</option>";
            $warna =
                "<option value=" .
                $row->i_color .
                " >" .
                $row->e_color_name .
                "</option>";
            $style =
                "<option value=" .
                $row->i_style .
                " >" .
                $row->e_style_name .
                "</option>";
            $brand =
                "<option value=" .
                $row->i_brand .
                " >" .
                $row->e_brand_name .
                "</option>";
            $satuan =
                "<option value=" .
                $row->i_satuan_code .
                " >" .
                $row->e_satuan_name .
                "</option>";
            $iproductbase = $iproductbasenew;
            echo json_encode([
                "kop" => $kop,
                "warna" => $warna,
                "style" => $style,
                "brand" => $brand,
                "satuan" => $satuan,
                "iproductbase" => $iproductbase,
            ]);
        } else {
            $kop = "<option value=\"\">Data Kosong</option>";
            $warna = "<option value=\"\">Data Kosong</option>";
            $style = "<option value=\"\">Data Kosong</option>";
            $brand = "<option value=\"\">Data Kosong</option>";
            $satuan = "<option value=\"\">Data Kosong</option>";
            $iproductbase = $iproductbasenew;
            echo json_encode([
                "kop" => $kop,
                "warna" => $warna,
                "style" => $style,
                "brand" => $brand,
                "satuan" => $satuan,
                "iproductbase" => $iproductbase,
                "kosong" => "kopong",
            ]);
        }
    }

    public function getbrand()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $data = $this->mmaster->getbrand($cari);
        foreach ($data->result() as $key) {
            $filter[] = [
                "id" => $key->i_brand,
                "text" => $key->e_brand_name,
            ];
        }
        echo json_encode($filter);
    }

    public function getstyle()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $ibrand = strtoupper($this->input->get("ibrand"));
        $data = $this->mmaster->getstyle($cari, $ibrand);
        foreach ($data->result() as $key) {
            $filter[] = [
                "id" => $key->i_style,
                "text" => $key->e_style_name,
            ];
        }
        echo json_encode($filter);
    }

    public function getsatuanbarang()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $data = $this->mmaster->getsatuanbarang($cari);
        foreach ($data->result() as $key) {
            $filter[] = [
                "id" => $key->i_satuan_code,
                "text" => $key->e_satuan_name,
            ];
        }
        echo json_encode($filter);
    }

    public function getstatusproduksi()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $data = $this->mmaster->getstatusproduksi($cari);
        foreach ($data->result() as $key) {
            $filter[] = [
                "id" => $key->i_status_produksi,
                "text" => $key->e_status_produksi,
            ];
        }
        echo json_encode($filter);
    }

    public function getbarangwip()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $data = $this->mmaster->getbarangwip($cari);

        foreach ($data->result() as $key) {
            $filter[] = [
                "id" => $key->i_product_wip . "|" . $key->i_color,
                "text" => $key->i_product_wip . "-" . $key->e_product_wipname,
            ];
        }
        echo json_encode($filter);
    }

    public function getkodebarang()
    {
        header("Content-Type: application/json", true);
        $iproductwip = explode("|", $this->input->post("iproductwip"));
        $i_productwip = $iproductwip[0];
        $i_product_wip = $iproductwip[1];

        $data = $this->mmaster->getkodebarang($i_productwip)->result_array();
        echo json_encode($data);
    }

    public function getwarnamotif()
    {
        $filter = [];
        $cari = strtoupper($this->input->get("q"));
        $i_product_base = $this->input->get("i_product_base");
        $data = $this->mmaster->getwarnamotif($cari, $i_product_base);

        foreach ($data->result() as $icolor) {
            $filter[] = [
                "id" => $icolor->i_color,
                "text" => $icolor->e_color_name,
            ];
        }
        echo json_encode($filter);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), "refresh");
        }

        $iproductbase = $this->input->post("iproductbase", true);

        $idivisi = $this->input->post("idivisi", true);

        $ikodegroupbarang = $this->input->post("igroupbrg", true);
        $ikodekelompok = $this->input->post("ikelompok", true);
        $istyle = $this->input->post("istyle", true);
        /* $iproductwip = explode("|", $this->input->post("iproductwip"));
        $i_productwip = $iproductwip[0];
        $i_product_wip_color = $iproductwip[1]; */
        // $icolor = $this->input->post("iwarna", true);
        $icolor = $this->input->post("iwarna[]", true);
        $isatuancode = $this->input->post("isatuan", true);
        $dateproductregister = $this->input->post("dproductregister", true);
        if ($dateproductregister) {
            $tmp = explode("-", $dateproductregister);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dproductregister = $year . "-" . $month . "-" . $day;
        }
        $istatusproduksi = $this->input->post("istatusproduksi", true);
        $datetanggalpenawaran = $this->input->post("dtanggalpenawaran", true);
        if ($datetanggalpenawaran) {
            $tmp = explode("-", $datetanggalpenawaran);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dsuratpenawaran = $year . "-" . $month . "-" . $day;
        }
        $datelaunching = $this->input->post("dlaunching", true);
        if ($datelaunching) {
            $tmp = explode("-", $datelaunching);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dlaunch = $year . "-" . $month . "-" . $day;
        } else {
            $dlaunch = null;
        }
        $datestp = $this->input->post("dstp", true);
        if ($datestp) {
            $tmp = explode("-", $datestp);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dstp = $year . "-" . $month . "-" . $day;
        } else {
            $dstp = null;
        }
        $esuratpenawaran = $this->input->post("esuratpenawaran", true);
        $eremark = $this->input->post("edeskripsi", true);
        $itypecode = $this->input->post("ijenisbrg", true);
        $iclass = $this->input->post("ikelasbrg", true);
        $ibrand = $this->input->post("ibrand", true);
        $eproductbasename = $this->input->post("eproductbasename", true);
        $npanjang = $this->input->post("npanjang", true);
        $nlebar = $this->input->post("nlebar", true);
        $ntinggi = $this->input->post("ntinggi", true);
        $isatuanukuran = $this->input->post("isatuanukuran", true);
        $nberat = $this->input->post("nberat", true);
        $isatuanberat = $this->input->post("isatuanberat", true);
        $id_product_base = $this->input->post("id_product_base", true);
        $id_product_base = ($id_product_base == '') ? null : $id_product_base;
        $vhjp = str_replace(",", "", $this->input->post("ehjp", true));
        $vgrosir = str_replace(
            ",",
            "",
            $this->input->post("fhargagrosir", true)
        );
        if ($vhjp == "" || $vhjp == null) {
            $vhjp = 0;
        }
        if ($vgrosir == "" || $vgrosir == null) {
            $vgrosir = 0;
        }
        $this->db->trans_begin();
        if (
            /* $iproductwip != "" && */
            $iproductbase != "" &&
            $eproductbasename != ""
        ) {
            $this->Logger->write(
                "Simpan Data " .
                    $this->global["title"] .
                    " Kode : " .
                    $iproductbase
            );

            if (is_array($icolor) || is_object($icolor)) {
                foreach ($icolor as $color) {
                    $this->mmaster->insert($iproductbase, $eproductbasename,/* $i_productwip,$i_product_wip_color, */ $color, $isatuancode, $ikodegroupbarang, $ikodekelompok, $itypecode, $ibrand, $istyle, $istatusproduksi, $vhjp, $vgrosir, $esuratpenawaran, $dsuratpenawaran, $npanjang, $nlebar, $ntinggi, $isatuanukuran, $nberat, $isatuanberat, $eremark, $dproductregister, $iclass, $dlaunch, $dstp, $id_product_base);
                }
            }
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = [
                    "sukses" => false,
                ];
            } else {
                $this->db->trans_commit();
                $data = [
                    "sukses" => true,
                    "kode" => $iproductbase,
                ];
            }
            $this->load->view("pesan", $data);
        }
    }

    public function view()
    {
        $iproductbase = $this->uri->segment(4);
        $id = $this->uri->segment(5);

        $data = [
            "folder" => $this->global["folder"],
            "title" => "View " . $this->global["title"],
            "title_list" => "List " . $this->global["title"],
            "data" => $this->mmaster->cek_data($iproductbase, $id)->row(),
            "class" => $this->db->get("tr_class_product")->result(),
        ];

        $this->Logger->write("Membuka Menu View " . $this->global["title"]);
        $this->load->view($this->global["folder"] . "/vformview", $data);
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), "refresh");
        }

        $iproductbase = $this->uri->segment(4);
        $id = $this->uri->segment(5);

        $data = [
            "folder" => $this->global["folder"],
            "title" => "Edit " . $this->global["title"],
            "title_list" => "List " . $this->global["title"],
            "data" => $this->mmaster->cek_data($iproductbase, $id)->row(),
            "class" => $this->db->get("tr_class_product")->result(),
        ];
        $this->Logger->write("Membuka Menu Edit " . $this->global["title"]);

        $this->load->view($this->global["folder"] . "/vformedit", $data);
    }

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), "refresh");
        }

        $iproductbase = $this->input->post("iproductbase", true);
        $id = $this->input->post("id", true);

        $idivisi = $this->input->post("idivisi", true);

        $ikodegroupbarang = $this->input->post("igroupbrg", true);
        $ikodekelompok = $this->input->post("ikelompok", true);
        $istyle = $this->input->post("istyle", true);
        $icolor = $this->input->post("iwarna", true);
        $icolor_old = $this->input->post("iwarna_old", true);
        $isatuancode = $this->input->post("isatuan", true);
        $dateproductregister = $this->input->post("dproductregister", true);
        if ($dateproductregister) {
            $tmp = explode("-", $dateproductregister);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dproductregister = $year . "-" . $month . "-" . $day;
        } else {
            $dproductregister = NULL;
        }
        $istatusproduksi = $this->input->post("istatusproduksi", true);
        $datetanggalpenawaran = $this->input->post("dtanggalpenawaran", true);
        if ($datetanggalpenawaran) {
            $tmp = explode("-", $datetanggalpenawaran);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dsuratpenawaran = $year . "-" . $month . "-" . $day;
        } else {
            $dsuratpenawaran = NULL;
        }
        $datelaunching = $this->input->post("dlaunching", true);
        if ($datelaunching) {
            $tmp = explode("-", $datelaunching);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dlaunch = $year . "-" . $month . "-" . $day;
        } else {
            $dlaunch = null;
        }
        $datestp = $this->input->post("dstp", true);
        if ($datestp) {
            $tmp = explode("-", $datestp);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $yearmonth = $year . $month;
            $dstp = $year . "-" . $month . "-" . $day;
        } else {
            $dstp = null;
        }
        $esuratpenawaran = $this->input->post("esuratpenawaran", true);
        $eremark = $this->input->post("edeskripsi", true);
        $itypecode = $this->input->post("ijenisbrg", true);
        $iclass = $this->input->post("ikelasbrg", true);
        $ibrand = $this->input->post("ibrand", true);
        $eproductbasename = $this->input->post("eproductbasename", true);
        $id_product_base = $this->input->post("id_product_base", true);
        $id_product_base = ($id_product_base == '') ? null : $id_product_base;
        $npanjang = $this->input->post("npanjang", true);
        if (!$npanjang) {
            $npanjang = 0;
        }
        $nlebar = $this->input->post("nlebar", true);
        if (!$nlebar) {
            $nlebar = 0;
        }
        $ntinggi = $this->input->post("ntinggi", true);
        if (!$ntinggi) {
            $ntinggi = 0;
        }
        $isatuanukuran = $this->input->post("isatuanukuran", true);
        $nberat = $this->input->post("nberat", true);
        if (!$nberat) {
            $nberat = 0;
        }
        $isatuanberat = $this->input->post("isatuanberat", true);
        $vhjp = str_replace(",", "", $this->input->post("ehjp", true));
        $vgrosir = str_replace(
            ",",
            "",
            $this->input->post("fhargagrosir", true)
        );
        if ($vhjp == "" || $vhjp == null) {
            $vhjp = 0;
        }
        if ($vgrosir == "" || $vgrosir == null) {
            $vgrosir = 0;
        }

        if ($icolor != "" && $eproductbasename != "") {
            $this->mmaster->update(
                $id,
                $eproductbasename,
                $iproductbase,
                $icolor,
                $icolor_old,
                $isatuancode,
                $ikodegroupbarang,
                $ikodekelompok,
                $itypecode,
                $ibrand,
                $istyle,
                $istatusproduksi,
                $vhjp,
                $vgrosir,
                $esuratpenawaran,
                $dsuratpenawaran,
                $npanjang,
                $nlebar,
                $ntinggi,
                $isatuanukuran,
                $nberat,
                $isatuanberat,
                $eremark,
                $dproductregister,
                $iclass,
                $dlaunch,
                $dstp,
                $id_product_base
            );
            $data = [
                "sukses" => true,
                "kode" => $iproductbase,
            ];
        } else {
            $data = [
                "sukses" => false,
            ];
        }
        $this->load->view("pesan", $data);
    }

    public function export()
    {
        /* $data = check_role($this->i_menu, 6);
        if(!$data){
            redirect(base_url(),'refresh');
        } */

        $query = $this->mmaster->data_export();
        $spreadsheet = new Spreadsheet();
        $sharedStyle1 = new Style();
        $sharedStyle11 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedStylex = new Style();

        $spreadsheet
            ->getActiveSheet()
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sharedStyle1->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "DFF1D0"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle11->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "f7a19a"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle2->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                /* 'top'    => ['borderStyle' => Border::BORDER_THIN],
                 'bottom' => ['borderStyle' => Border::BORDER_THIN], */
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
            "alignment" => [
                "vertical" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStylex->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                /* 'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN] */
            ],
            "alignment" => [
                "vertical" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStyle3->applyFromArray([
            "font" => [
                "name" => "Times New Roman",
                "bold" => true,
                "italic" => false,
                "size" => 12,
            ],
            "alignment" => [
                "horizontal" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        $spreadsheet
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        foreach (range("A", "L") as $columnID) {
            $spreadsheet
                ->getActiveSheet()
                ->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $spreadsheet->getActiveSheet()->mergeCells("A1:K3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:K3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $spreadsheet
            ->setActiveSheetIndex(0)
            ->setCellValue("A1", "MASTER " . strtoupper($this->global["title"]))
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "ID")
            ->setCellValue("C5", "Kode Barang")
            ->setCellValue("D5", "Nama Barang")
            ->setCellValue("E5", "Warna")
            ->setCellValue("F5", "Divisi")
            ->setCellValue("G5", "Kategori")
            ->setCellValue("H5", "Sub Kategori")
            ->setCellValue("I5", "Brand")
            ->setCellValue("J5", "Series")
            ->setCellValue("K5", "Kategori Penjualan")
            /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:K5");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "I5");
        $spreadsheet
            ->setActiveSheetIndex(0)
            ->setCellValue("K2", "KATEGORI PENJUALAN")
            ->setCellValue("K5", "ID Kategori")
            ->setCellValue("L5", "Nama Kategori");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "L5");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "K5"); */



        $kolom = 6;
        $no = 1;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data = $this->db
                    ->query(
                        "SELECT string_agg(e_class_name,', ') AS name FROM tr_class_product"
                    )
                    ->row()->name;
                $sheet = $spreadsheet->getActiveSheet();
                $validation = $sheet->getCell("K" . $kolom)->getDataValidation();
                $validation
                    ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setFormula1('"' . $data . '"')
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

                $spreadsheet
                    ->setActiveSheetIndex(0)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->id)
                    ->setCellValue("C" . $kolom, $row->i_product_base)
                    ->setCellValue("D" . $kolom, $row->e_product_basename)
                    ->setCellValue("E" . $kolom, $row->e_color_name)
                    ->setCellValue("F" . $kolom, $row->e_nama_divisi)
                    ->setCellValue("G" . $kolom, $row->e_nama_kelompok)
                    ->setCellValue("H" . $kolom, $row->e_type_name)
                    ->setCellValue("I" . $kolom, $row->brand)
                    ->setCellValue("J" . $kolom, $row->series)
                    ->setCellValue("K" . $kolom, $row->e_class_name)
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $spreadsheet
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":K" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        $sheet->setDataValidation("K6:K" . $kolom, $validation);
        $spreadsheet
            ->getActiveSheet()
            ->duplicateStyle($sharedStylex, "A" . $kolom . ":K" . $kolom);

        /* $kolom = 6;
        $sql = $this->db->get("tr_class_product");
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
                $spreadsheet
                    ->setActiveSheetIndex(0)
                    ->setCellValue("K" . $kolom, $row->id)
                    ->setCellValue("L" . $kolom, $row->e_class_name);
                $spreadsheet
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "K" . $kolom . ":L" . $kolom
                    );
                $kolom++;
            }
        }
        $spreadsheet
            ->getActiveSheet()
            ->duplicateStyle($sharedStylex, "K" . $kolom . ":L" . $kolom); */
        $writer = new Xls($spreadsheet);
        $nama_file = "Master_Barang_Jadi_" . date('Ymd_His') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $nama_file . "");
        header("Cache-Control: max-age=0");
        ob_end_clean();
        ob_start();
        $writer->save("php://output");
        /* }else{
            echo "<center><h1> Tidak Ada Data :(</h1></center>";
        } */
    }

    public function form_upload()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformmain', $data);
    }

    public function load()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $filename = $_FILES['userfile']['name'];

        $config = array(
            'upload_path'   => "./import/master/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());
            $inputFileName = './import/master/' . $filename;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestDataRow('A');
            for ($n = 6; $n <= $hrow; $n++) {
                $id_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
                $name       = strtolower($spreadsheet->getActiveSheet()->getCell('K' . $n)->getValue());
                if (($id_product != '' || $id_product != null) && ($name != '' || $name != null)) {
                    $id = $this->mmaster->get_kategori_penjualan($name);
                    if ($id->num_rows() > 0) {
                        $this->mmaster->update_product($id_product, $id->row()->id);
                    }
                }
            }
            $param =  array(
                'status' => 'berhasil'
            );
            echo json_encode($param);
        } else {
            $param =  array(
                'status' => 'gagal'
            );
            echo json_encode($param);
        }
    }
}

/* End of file Cform.php */