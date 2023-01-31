<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    /*----------  DAFTAR DATA SPB  ----------*/

    public function data($folder, $i_menu, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_spb
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company)
            ", FALSE);
        if ($this->departement == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_customer,
                b.e_customer_name,
                a.i_referensi,
                a.e_jenis_spb,
                e_remark,
                e_status_name,
                label_color,
                l.i_level,
                l.e_level_name,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_spb a
            INNER JOIN tr_customer b ON
                (a.id_customer = b.id)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            LEFT JOIN public.tr_menu_approve e on 
                (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on 
                (e.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company 
                AND a.i_promo ISNULL
                $and 
                $bagian
            ORDER BY
                a.id desc
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $idcustomer = trim($data['id_customer']);
            $ddocument  = $data['d_document'];
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $i_status   = $data['i_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $jenis      = $data['e_jenis_spb'];
            $i_level    = $data['i_level'];
            $data       = '';

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if ($i_level == $this->level || 1 == $this->level) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print SPB' onclick='cetak(\"$id\",\"$ddocument\",\"$idcustomer\",\"$jenis\"); return false;'><i class='ti-printer text-warning fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg mr-3'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_customer');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        // $datatables->hide('e_jenis_spb');
        return $datatables->generate();
    }

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */

        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				LEFT JOIN tr_type c on (a.i_type = c.i_type)
				LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/

    public function runningnumber($thbl, $tahun, $ibagian, $idcustomer)
    {
        $kode = 'SPB';
        if ($idcustomer != '' || $idcustomer != null) {
            $query = $this->db->query("SELECT e_doc_penjualan 
            FROM tr_supplier_group WHERE i_supplier_group IN (
                SELECT i_supplier_group FROM tr_customer WHERE id_company = '$this->id_company' AND id = $idcustomer)
            AND id_company = '$this->id_company'");
            if ($query->num_rows() > 0) {
                $kode = $query->row()->e_doc_penjualan;
            }
        }
        if (strlen($kode) == 4) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 11, 4)) AS max 
                FROM tm_spb
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 3) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 10, 4)) AS max 
                FROM tm_spb
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 2) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 9, 4)) AS max 
                FROM tm_spb
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        }
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 4) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "0001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    public function runningnumber_20220622($thbl, $tahun, $ibagian, $idcustomer)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 3) AS kode
            FROM tm_spb
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SPB';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_spb
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 3) = '$kode'
                AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
            ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /*----------  CEK DOKUMEN SUDAH ADA  ----------*/

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_spb');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI PELANGGAN  ----------*/

    public function customer($cari, $iarea)
    {
        return $this->db->query("
            SELECT
                a.id,
                i_customer,
                e_customer_name
            FROM
                tr_customer a
            INNER JOIN tr_type_industry b ON
                (b.i_type_industry = a.i_type_industry
                AND a.id_company = b.id_company)
            /* INNER JOIN tr_type_spb c ON
                (c.id_type_industry = b.id) */
            WHERE
                a.id_company = $this->company
                AND (i_customer ILIKE '%$cari%'
                OR e_customer_name ILIKE '%$cari%')
                AND a.f_status = 't'
                /* AND c.i_type = '3' */
            ORDER BY
                e_customer_name
            ", FALSE);
    }

    /*----------  GET DISKON SESUAI PELANGGAN  ----------*/

    public function getdetailcustomer($idcustomer)
    {
        return $this->db->query("            
            SELECT 
                v_customer_discount,
                v_customer_discount2,
                v_customer_discount3,
                id_harga_kode,
                i_harga||' - '||e_harga AS e_harga_kode,
                e_customer_name, (select id from tr_salesman where id_company = '$this->id_company' and e_sales ilike '%office%' limit 1) as id_sales
            FROM
                tr_customer a
            INNER JOIN tr_harga_kode b ON
                (b.id = a.id_harga_kode)
            WHERE
                a.id = $idcustomer
            ", FALSE);
    }

    /*----------  DATA MASTER AREA  ----------*/

    public function area()
    {
        return $this->db->query("
            SELECT
                id,
                i_area,
                e_area
            FROM
                tr_area
            WHERE
                f_status = 't'
            ORDER BY
                i_area
        ", FALSE);
    }

    /*----------  DATA MASTER SALESMAN  ----------*/

    public function sales()
    {
        return $this->db->query("
            SELECT
                id,
                i_sales,
                e_sales
            FROM
                tr_salesman
            WHERE
                f_status = 't'
            ORDER BY
                i_sales
        ", FALSE);
    }

    /*----------  DATA KELOMPOK BARANG  ----------*/

    public function kelompok($cari, $ibagian)
    {
        return $this->db->query("
            SELECT
                i_kode_kelompok,
                e_nama_kelompok
            FROM
                tr_kelompok_barang
            WHERE
                f_status = 't'
                AND i_kode_kelompok IN (
                    SELECT
                        i_kode_kelompok
                    FROM
                        tr_bagian_kelompokbarang
                    WHERE
                        e_nama_kelompok ILIKE '%$cari%'
                        AND id_company = $this->company
                        AND i_bagian = '$ibagian' )
                        AND id_company = $this->company
                    ORDER BY
                        e_nama_kelompok
            ", FALSE);
    }

    /*----------  DATA JENIS BARANG  ----------*/

    public function jenis($cari, $ikelompok, $ibagian)
    {
        $jenis = "";
        if ($this->departement != '5' || $this->departement != '1') {
            if (($ikelompok != '' || $ikelompok != null) && $ikelompok != 'all') {
                $jenis = "AND i_kode_kelompok = '$ikelompok' ";
            } else {
                $jenis = "AND i_kode_kelompok IN 
                (SELECT
                    i_kode_kelompok
                FROM
                    tr_bagian_kelompokbarang
                WHERE
                    i_bagian = '$ibagian'
                    AND id_company = $this->company)";
            }
        }
        return $this->db->query("
            SELECT
                DISTINCT i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                e_type_name ILIKE '%$cari%'
                AND f_status = 't'
                AND id_company = $this->company
                $jenis
            ORDER BY
                e_type_name
        ", FALSE);
    }

    /*----------  BACA BARANG JADI  ----------*/

    public function product($cari, $ikategori, $ijenis, $ibagian, $idharga, $id_jenis_barang_keluar)
    {
        $kategori = "";
        $jenis    = "";
        if ($this->departement != '5' || $this->departement != '1') {
            if (($ikategori != '' || $ikategori != null) && $ikategori != 'all') {
                $kategori = "AND i_kode_kelompok = '$ikategori' ";
            } else {
                $kategori = "AND i_kode_kelompok 
                IN (SELECT
                        i_kode_kelompok
                    FROM
                        tr_bagian_kelompokbarang
                    WHERE
                        i_bagian = '$ibagian'
                        AND id_company = $this->company)";
            }

            if (($ijenis != '' || $ijenis != null) && $ijenis != 'all') {
                $jenis = "AND i_type_code = '$ijenis' ";
            } else {
                $jenis = "AND i_type_code 
                IN (SELECT
                        i_type_code
                    FROM
                        tr_item_type
                    WHERE
                        f_status = 't'
                        AND id_company = $this->company
                        AND i_kode_kelompok IN 
                            (SELECT
                                i_kode_kelompok
                            FROM
                                tr_bagian_kelompokbarang
                            WHERE
                                i_bagian = '$ibagian'
                                AND id_company = $this->company))";
            }
        }
        return $this->db->query("SELECT DISTINCT
                a.id,
                i_product_base,
                e_product_basename || ' - '|| e_color_name AS e_product_basename
            FROM
                tr_product_base a
            INNER JOIN tr_harga_jualbrgjd b ON 
                (a.id = id_product_base 
                AND a.id_company = b.id_company AND id_harga_kode = '$idharga' AND id_jenis_barang_keluar = '$id_jenis_barang_keluar')
            INNER JOIN tr_color c ON
                (c.id_company = a.id_company AND a.i_color = c.i_color)
            WHERE
                a.f_status = 't'
                AND (i_product_base ILIKE '%$cari%' 
                OR e_product_basename ILIKE '%$cari%')
                AND a.id_company = $this->company
                /* $kategori
                $jenis */
            ORDER BY
                i_product_base
        ", FALSE);
    }

    /*----------  GET DETAIL BARANG JADI  ----------*/

    public function getproduct($idproduct, $idharga, $ddocument, $idcustomer, $id_jenis_barang_keluar)
    {
        $dberlaku  = date('Y-m-d', strtotime($ddocument));
        $dakhir    = date('Y-m-d', strtotime('+1 year', strtotime($ddocument))); /*tambah tanggal sebanyak 1 tahun*/
        $periode   = date('Ym', strtotime($ddocument));

        return $this->db->query("
            SELECT
                x.*,
                CASE
                    WHEN e.id IS NOT NULL THEN COALESCE(d.n_quantity_fc, 0)
                    ELSE 9999
                END AS fc
            FROM
                (
                SELECT
                    a.id_company,
                    a.id AS id_product,
                    a.i_product_base,
                    a.e_product_basename,
                    b.v_price,
                    b.d_berlaku,
                    CASE
                        WHEN d_akhir ISNULL THEN '$dakhir'
                        ELSE d_akhir
                    END AS d_akhir,
                    $idcustomer AS id_customer,
                    '$periode' AS periode
                FROM
                    tr_product_base a
                INNER JOIN tr_harga_jualbrgjd b ON
                    (a.id = id_product_base
                    AND a.id_company = b.id_company)
                WHERE
                    a.id_company = $this->company
                    AND a.id = '$idproduct'
                    AND id_harga_kode = '$idharga'
                    AND id_jenis_barang_keluar = '$id_jenis_barang_keluar' ) AS x
            LEFT JOIN f_get_forecast_distributor($this->company,
                '$periode',$idcustomer) d ON
                (d.id_company = x.id_company
                AND d.periode = x.periode
                AND d.id_customer = x.id_customer
                AND d.id_product = x.id_product)
            LEFT JOIN tr_customer_transfer e ON
                (x.id_company = e.id_company
                AND x.id_customer = e.id_customer)
            WHERE
                x.d_berlaku <= '$dberlaku'
                AND x.d_akhir >= '$dberlaku'
            ", FALSE);
    }

    /*----------  RUNNING ID SPBD  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_spb');
        return $this->db->get()->row()->id + 1;
    }

    /*----------  NOT RUNNING ID SPBD  ----------*/

    public function notrunningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_spb');
        return $this->db->get()->row()->id;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id, $idocument, $ddocument, $ibagian, $idcustomer, $ecustomername, $idarea, $idsales, $ireferensi, $vdiskon, $vkotor, $vppn, $vbersih, $eremarkh, $vdpp, $idharga, $etypespb, $id_jenis_barang_keluar, $nppn)
    {
        if($etypespb == 'Transfer') {
            $data = array(
                'id'                => $id,
                'id_company'        => $this->company,
                'i_document'        => $idocument,
                'd_document'        => $ddocument,
                'i_bagian'          => $ibagian,
                'id_customer'       => $idcustomer,
                'id_area'           => $idarea,
                'id_salesman'       => $idsales,
                'i_referensi'       => $ireferensi,
                'v_bruto'           => $vkotor,
                'v_discount'        => $vdiskon,
                'v_dpp'             => $vdpp,
                'v_ppn'             => $vppn,
                'v_netto'           => $vbersih,
                'id_harga_kode'     => $idharga,
                'e_remark'          => $eremarkh,
                'e_jenis_spb'       => $etypespb,
                'd_entry'           => current_datetime(),
                'id_jenis_barang_keluar' => $id_jenis_barang_keluar,
                'n_ppn'             => $nppn,
                'i_status'          => '6'
            );
        } else {
            $data = array(
                'id'                => $id,
                'id_company'        => $this->company,
                'i_document'        => $idocument,
                'd_document'        => $ddocument,
                'i_bagian'          => $ibagian,
                'id_customer'       => $idcustomer,
                'id_area'           => $idarea,
                'id_salesman'       => $idsales,
                'i_referensi'       => $ireferensi,
                'v_bruto'           => $vkotor,
                'v_discount'        => $vdiskon,
                'v_dpp'             => $vdpp,
                'v_ppn'             => $vppn,
                'v_netto'           => $vbersih,
                'id_harga_kode'     => $idharga,
                'e_remark'          => $eremarkh,
                'e_jenis_spb'       => $etypespb,
                'd_entry'           => current_datetime(),
                'id_jenis_barang_keluar' => $id_jenis_barang_keluar,
                'n_ppn'             => $nppn
            );
        }
        $this->db->insert('tm_spb', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/

    public function insertdetail($id, $idproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vtotaldiskon, $vtotal, $eremark)
    {
        $data = array(
            'id_company'        => $this->company,
            'id_document'       => $id,
            'id_product'        => $idproduct,
            'n_quantity'        => $nquantity,
            'n_quantity_sisa'   => $nquantity,
            'v_price'           => $vprice,
            'n_diskon1'         => $ndiskon1,
            'n_diskon2'         => $ndiskon2,
            'n_diskon3'         => $ndiskon3,
            'v_diskon1'         => $vdiskon1,
            'v_diskon2'         => $vdiskon2,
            'v_diskon3'         => $vdiskon3,
            'v_diskon_tambahan' => $vdiskonplus,
            'v_diskon_total'    => $vtotaldiskon,
            'v_total'           => $vtotal,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_spb_item', $data);
    }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/

    public function editheader($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                e_bagian_name,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                id_customer,
                i_customer,
                e.e_customer_name,
                a.id_harga_kode,
                i_harga,
                e_harga,
                a.id_area,
                a.id_salesman,
                e_area,
                i_area,
                i_referensi,
                e_remark,
                i_status,
                v_netto AS v_bersih,
                v_discount AS v_diskon,
                v_dpp,
                v_ppn,
                v_bruto AS v_kotor,
                e.v_customer_discount,
                e.v_customer_discount2,
                e.v_customer_discount3,
                e_customer_address,
                e_city_name,
                e_sales,
                to_char(e.d_join, 'dd-mm-yyyy') AS d_join,
                id_jenis_barang_keluar,
                n_ppn,
                e_jenis_name
            FROM
                tm_spb a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_harga_kode c ON
                (c.id = a.id_harga_kode)
            INNER JOIN tr_area d ON
                (d.id = a.id_area)
            INNER JOIN tr_customer e ON
                (e.id = a.id_customer)
            INNER JOIN tr_city f ON 
                (f.id = e.id_city)
            INNER JOIN tr_salesman g ON 
                (g.id = a.id_salesman)
            INNER JOIN tr_jenis_barang_keluar h ON
                (h.id = a.id_jenis_barang_keluar)
            WHERE
                a.id = $id
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM  ----------*/

    public function edititem($id, $jenis)
    {
        if ($jenis == "Manual") {
            return $this->db->query("
                SELECT DISTINCT
                    a.*,
                    b.i_product_base,
                    b.e_product_basename,
                    c.id_customer,
                    to_char(c.d_document, 'yyyymm'),
                    a.n_quantity_sisa as fc, 
                    e_satuan_name,
                    e_color_name, v_diskon_total
                FROM
                   tm_spb_item a 
                   INNER JOIN
                      tr_product_base b 
                      ON (b.id = a.id_product) 
                   INNER JOIN
                      tr_satuan s 
                      ON (s.i_satuan_code = b.i_satuan_code 
                      AND b.id_company = s.id_company) 
                   INNER JOIN
                      tm_spb c 
                      ON (a.id_document = c.id) 
                    INNER JOIN
                        tr_color t 
                        ON (t.i_color = b.i_color 
                        AND t.id_company = b.id_company) 
                WHERE
                   a.id_document = '$id'
                ORDER BY
                   a.id
            ", FALSE);
        } else if ($jenis == "FC") {
            return $this->db->query("
                SELECT DISTINCT
                    a.*,
                    b.i_product_base,
                    b.e_product_basename,
                    c.id_customer, 
                    to_char(c.d_document, 'yyyymm'),
                    case when e.id is not null then coalesce(d.n_quantity_fc, 0) else 9999 end as fc,
                    e_satuan_name, co.e_color_name
                FROM
                    tm_spb_item a
                    INNER JOIN tr_product_base b ON (b.id = a.id_product)
                    inner join tr_color co on (b.i_color = co.i_color and b.id_company = co.id_company)
                    INNER JOIN tr_satuan s ON (s.i_satuan_code = b.i_satuan_code AND b.id_company = s.id_company)
                    INNER JOIN tm_spb c ON (a.id_document = c.id)
                    LEFT JOIN f_get_forecast_distributor($this->company,to_char(d_document, 'yyyymm'), c.id_customer) d 
                        ON (d.id_company = a.id_company and d.periode = to_char(c.d_document, 'yyyymm')  and c.id_customer = d.id_customer and a.id_product = d.id_product)
                    LEFT JOIN tr_customer_transfer e on (a.id_company = e.id_company and c.id_customer = e.id_customer)
                WHERE
                    a.id_document = $id
                ORDER BY
                    a.id
            ", FALSE);
        } else if ($jenis == "Transfer") {
            return $this->db->query("
                SELECT DISTINCT
                    a.*,
                    b.i_product_base,
                    b.e_product_basename,
                    c.id_customer, 
                    to_char(c.d_document, 'yyyymm'),
                    a.n_quantity_sisa as fc,
                    e_satuan_name, co.e_color_name
                FROM
                    tm_spb_item a
                    INNER JOIN tr_product_base b ON (b.id = a.id_product)
                    inner join tr_color co on (b.i_color = co.i_color and b.id_company = co.id_company)
                    INNER JOIN tr_satuan s ON (s.i_satuan_code = b.i_satuan_code AND b.id_company = s.id_company)
                    INNER JOIN tm_spb c ON (a.id_document = c.id)
                    LEFT JOIN tr_customer_transfer e on (a.id_company = e.id_company and c.id_customer = e.id_customer)
                WHERE
                    a.id_document = $id
                ORDER BY
                    a.id
            ", FALSE);
        }
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode, $ibagian, $kodeold, $ibagianold)
    {
        $this->db->select('i_document');
        $this->db->from('tm_spb');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  DELETE DETAIL PAS EDIT  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_spb_item');
    }

    /*----------  UPDATE HEADER  ----------*/

    public function updateheader($id, $idocument, $ddocument, $ibagian, $idcustomer, $ecustomername, $idarea, $idsales, $ireferensi, $vdiskon, $vkotor, $vppn, $vbersih, $eremarkh, $vdpp, $idharga, $id_jenis_barang_keluar, $nppn)
    {
        $data = array(
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_customer'       => $idcustomer,
            'id_area'           => $idarea,
            'id_salesman'       => $idsales,
            'i_referensi'       => $ireferensi,
            'v_bruto'           => $vkotor,
            'v_discount'        => $vdiskon,
            'v_dpp'             => $vdpp,
            'v_ppn'             => $vppn,
            'v_netto'           => $vbersih,
            'id_harga_kode'     => $idharga,
            'e_remark'          => $eremarkh,
            'd_update'          => current_datetime(),
            'id_jenis_barang_keluar' => $id_jenis_barang_keluar,
            'n_ppn'             => $nppn
        );
        $this->db->where('id', $id);
        $this->db->update('tm_spb', $data);
    }

    /*----------  RUBAH STATUS  ----------*/

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus)
    {
        /* if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_spb', $data); */
        $now = date('Y-m-d');
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("
            	SELECT b.i_menu , a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut from tm_spb a
				inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
				where a.id = '$id'
				group by 1,2", FALSE)->row();

            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0) {
                    $data = array(
                        'i_status'  => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("delete from tm_menu_approve where i_menu = '$this->i_menu' and i_level = '$this->level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => $now,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $this->db->query("
            		INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					 ('$this->i_menu','$this->level','$id','$this->username','$now','tm_spb');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_spb', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/

    /*----------  CARI PELANGGAN  ----------*/

    public function customertransfer()
    {
        return $this->db->query("
            SELECT
                a.id,
                i_customer,
                e_customer_name
            FROM
                tr_customer a
            INNER JOIN tr_customer_transfer b ON
                (b.id_customer = a.id)
            WHERE
                a.id_company = $this->company
                AND a.f_status = 't'
            ORDER BY
                e_customer_name
            ", FALSE);
    }

    /*----------  DAFTAR DATA TRANSFER OP  ----------*/

    public function datatransfer($folder, $i_menu, $dfrom, $dto, $icustomer)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_op BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        if ($icustomer != 'SD') {
            $where   = "AND y.id_customer = '$icustomer'";
        } else {
            $where   = "";
        }

        $custransfer = $this->db->query("
            SELECT
                y.*
            FROM
                tr_customer a
            INNER JOIN tr_customer_transfer y ON
                (y.id_customer = a.id)
            WHERE
                y.id_company = $this->company
                AND y.f_status = 't'
                $where
        ", FALSE);

        $sql = '';
        if ($custransfer->num_rows() > 1) {
            foreach ($custransfer->result() as $key) {
                $sql .= "
                    SELECT
                        0 AS no,
                        x.i_supplier,
                        i_op,
                        to_char(d_op, 'dd-mm-yyyy') AS d_op,
                        e_customer_name,
                        e_area,
                        e_op_remark,
                        x.db_name,
                        x.id_customer,
                        '$dfrom' AS dfrom,
                        '$dto' AS dto,
                        '$folder' AS folder,
                        '$i_menu' AS i_menu
                    FROM
                        (
                        SELECT
                            *
                        FROM
                            dblink('host=$key->url_db port=$key->db_port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                            $$
                            SELECT
                                a.i_supplier,
                                a.i_op,
                                a.d_op,
                                a.e_op_remark,
                                a.n_top_length,
                                a.i_area || ' - ' || e_area_name AS area,
                                '$key->db_name' AS db_name,
                                '$key->id_customer' AS id_customer
                            FROM
                                tm_op a,
                                tr_area b
                            WHERE
                                a.i_area = b.i_area
                                AND a.f_op_cancel = 'f'
                                AND a.i_supplier = '$key->id_customer_from'
                                $and
                            ORDER BY
                                a.i_op ASC $$) AS get_op ( i_supplier varchar(5),
                            i_op varchar(14),
                            d_op date,
                            e_op_remark CHARACTER VARYING (100),
                            n_top_length NUMERIC (3,
                            0),
                            e_area CHARACTER(50),
                            db_name CHARACTER(15),
                            id_customer integer)) AS x
                    INNER JOIN tr_customer_transfer y ON
                        (y.id_customer_from = x.i_supplier
                        AND y.db_name = '$key->db_name')
                    INNER JOIN tr_customer z ON
                        (z.id = y.id_customer)
                    WHERE
                        (x.i_op NOT IN (
                        SELECT
                            i_referensi
                        FROM
                            tm_spb
                        WHERE
                            i_status in ('1','2','3','6')
                            AND id_customer = $key->id_customer))
                        $where
                    UNION ALL";
            }
            $sql = substr($sql, 0, -9);
        } else {
            foreach ($custransfer->result() as $key) {
                $sql .= "
                    SELECT
                        0 AS no,
                        x.i_supplier,
                        i_op,
                        to_char(d_op, 'dd-mm-yyyy') AS d_op,
                        e_customer_name,
                        e_area,
                        e_op_remark,
                        x.db_name,
                        x.id_customer,
                        '$dfrom' AS dfrom,
                        '$dto' AS dto,
                        '$folder' AS folder,
                        '$i_menu' AS i_menu
                    FROM
                        (
                        SELECT
                            *
                        FROM
                            dblink('host=$key->url_db port=$key->db_port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                            $$
                            SELECT
                                a.i_supplier,
                                a.i_op,
                                a.d_op,
                                a.e_op_remark,
                                a.n_top_length,
                                a.i_area || ' - ' || e_area_name AS area,
                                '$key->db_name' AS db_name,
                                '$key->id_customer' AS id_customer
                            FROM
                                tm_op a,
                                tr_area b
                            WHERE
                                a.i_area = b.i_area
                                AND a.f_op_cancel = 'f'
                                AND a.i_supplier = '$key->id_customer_from'
                                $and
                            ORDER BY
                                a.i_op ASC $$) AS get_op ( i_supplier varchar(5),
                            i_op varchar(14),
                            d_op date,
                            e_op_remark CHARACTER VARYING (100),
                            n_top_length NUMERIC (3,
                            0),
                            e_area CHARACTER(50),
                            db_name CHARACTER(15),
                            id_customer integer)) AS x
                    INNER JOIN tr_customer_transfer y ON
                        (y.id_customer_from = x.i_supplier
                        AND y.db_name = '$key->db_name')
                    INNER JOIN tr_customer z ON
                        (z.id = y.id_customer)
                    WHERE
                        (x.i_op NOT IN (
                        SELECT
                            i_referensi
                        FROM
                            tm_spb
                        WHERE
                           i_status in ('1','2','3','6')
                            AND id_customer = $key->id_customer))
                        $where";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            WITH xx AS (
                SELECT
                    NO,
                    ROW_NUMBER() OVER (
                    ORDER BY id_customer, i_op) AS i,
                    id_customer,
                    i_supplier,
                    i_op,
                    d_op,
                    e_customer_name,
                    e_area,
                    e_op_remark,
                    db_name,
                    dfrom,
                    dto,
                    folder,
                    i_menu
                FROM
                    ($sql) AS x
            )
            SELECT
                NO,
                i,
                (
                SELECT
                    count(i) AS jml
                FROM
                    xx) AS jml,
                i_supplier,
                id_customer,
                i_op,
                d_op,
                e_customer_name,
                e_area,
                e_op_remark,
                db_name,
                dfrom,
                dto,
                folder,
                i_menu
            FROM
                xx
            ORDER BY 
                id_customer, i_op, e_customer_name
        ", FALSE);

        $datatables->add('action', function ($data) {
            $jml             = $data['jml'];
            $i               = $data['i'];
            $i_op            = $data['i_op'];
            $i_menu          = $data['i_menu'];
            $id_customer     = $data['id_customer'];
            $e_customer_name = $data['e_customer_name'];
            $db_name         = $data['db_name'];
            $folder          = $data['folder'];
            $dfrom           = $data['dfrom'];
            $dto             = $data['dto'];
            $data            = '';
            if (check_role($i_menu, 1)) {
                $data  .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk" . $i . "\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"jml\" value=\"" . $jml . "\" type=\"hidden\">
                <input name=\"iop" . $i . "\" value=\"" . $i_op . "\" type=\"hidden\">
                <input name=\"dfrom\" value=\"" . $dfrom . "\" type=\"hidden\">
                <input name=\"dto\" value=\"" . $dto . "\" type=\"hidden\">
                <input name=\"idcustomer" . $i . "\" value=\"" . $id_customer . "\" type=\"hidden\">";
            }
            return $data;
        });
        $datatables->hide('id_customer');
        $datatables->hide('i_supplier');
        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('i');
        $datatables->hide('jml');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('db_name');
        return $datatables->generate();
    }

    /*----------  GET DATA HEADER DISTIBUTOR  ----------*/

    public function dataheader($idcustomer)
    {
        return $this->db->query("
            SELECT
                e_customer_name,
                id_harga_kode,
                i_harga,
                e_harga,
                v_customer_discount,
                v_customer_discount2,
                v_customer_discount3,
                id_area
            FROM
                tr_customer a,
                tr_harga_kode b
            WHERE
                b.id = a.id_harga_kode
                AND a.id_company = b.id_company
                AND a.f_status = 't'
                AND b.f_status = 't'
                AND a.id = $idcustomer
        ", FALSE);
    }

    public function datadetail($idcustomer, $iop, $dfrom, $dto)
    {

        $i_periode_mutasi = date('Ym');
        $dfrom_mutasi = date('Y-m-01');
        $dto_mutasi = date('Y-m-t');

        $and   = "AND a.i_op IN (" . $iop . ")";
        $query = $this->db->query("
            SELECT 
                *
            FROM
                tr_customer_transfer
            WHERE 
                id_customer = $idcustomer
        ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                return $this->db->query("
                   SELECT *, to_char(d_op, 'dd-mm-yyyy') AS d_referensi FROM ( 
                         SELECT x.*, coalesce(e.n_quantity_fc, 0) AS fc, a.id AS id_product_base, a.e_product_basename, co.e_color_name , b.id AS id_area, d.e_customer_name, 
                         coalesce(c.v_price,0) as v_price, b.e_area, CASE WHEN d_akhir ISNULL THEN '9999-12-31' ELSE d_akhir END AS d_akhir ,
                         trim(trailing '00' FROM (round(x.n_order / count(a.id) over (partition by x.i_op, a.id_company, a.i_product_base),2))::text)::numeric as n_order_warna,
                         COALESCE(f.n_saldo_akhir,0) - COALESCE(h.n_quantity_sj,0) AS n_stock_outstanding, COALESCE(f.n_saldo_akhir,0) as saldo_akhir,coalesce(i.n_quantity_op,0) as n_op_kumulatif, coalesce(i.n_quantity_do,0) as n_do_kumulatif
                         FROM ( 
                              SELECT * FROM dblink('host=$key->url_db port=$key->db_port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                              $$ 
                              SELECT a.i_op, a.d_op, a.e_op_remark, a.i_area, b.i_product AS i_product, b.n_order, $idcustomer AS id_customer 
                              FROM tm_op a INNER JOIN tm_op_item b ON (b.i_op = a.i_op) WHERE a.f_op_cancel = 'f' AND a.i_supplier = '$key->id_customer_from' $and
                               ORDER BY a.i_op, b.i_product ASC 
                              $$
                              ) AS get_op ( 
                                   i_op varchar(14), d_op date, e_op_remark CHARACTER VARYING (100), i_area CHARACTER(3), i_product CHARACTER VARYING(20), 
                                   n_order NUMERIC, id_customer integer
                              )
                         ) AS x 
                         INNER JOIN (SELECT t.id, t.i_product_base, t.e_product_basename, t.id_company, t.f_status, t.i_color
                                        FROM (
                                            SELECT t.*, LAG(i_product_base) OVER (ORDER BY i_product_base) AS prev_name FROM tr_product_base t where id_company = '$this->id_company' and f_status = true
                                        ) t
                                    WHERE
                                        (prev_name IS NULL
                                            OR prev_name <> i_product_base)
                                            AND id_company = $this->id_company AND f_status='t'
                         ) a ON (a.i_product_base = x.i_product AND a.id_company =  $this->id_company and a.f_status = true) 
                         inner join tr_color co on (a.i_color = co.i_color and a.id_company = co.id_company)
                         INNER JOIN tr_area b ON (b.i_area = x.i_area AND a.id_company =  $this->id_company) 
                         INNER JOIN tr_customer d ON (d.id = x.id_customer) 
                         left JOIN tr_harga_jualbrgjd c ON (c.id_product_base = a.id AND c.id_harga_kode = d.id_harga_kode and c.d_berlaku <= x.d_op and CASE WHEN d_akhir ISNULL THEN '9999-12-31' ELSE d_akhir END >= x.d_op  AND id_jenis_barang_keluar = '1')
                         LEFT JOIN f_get_forecast_distributor( $this->id_company,to_char(x.d_op, 'yyyymm'), $idcustomer) e on (a.id_company = e.id_company and a.id = e.id_product and d.id = e.id_customer)
                         LEFT JOIN produksi.f_mutasi_gudang_jadi ('$this->id_company','$i_periode_mutasi','9999-01-01','9999-01-31','$dfrom_mutasi','$dto_mutasi','') f ON (f.id_product_base = a.id AND a.id_company = f.id_company) 
                         LEFT JOIN (SELECT id_product, sum(n_quantity) AS n_quantity_sj FROM tm_sj a, tm_sj_item b WHERE a.id = b.id_document AND a.i_status IN ('1','2','3') AND a.id_company = '$this->id_company' GROUP BY 1) h ON (h.id_product = a.id)
                         left join f_get_opdo_kumulatif( $this->id_company,to_char(x.d_op, 'yyyymm'), $idcustomer) i ON (i.id_product = a.id) 
                    ) AS op ORDER by i_op asc, scale(n_order_warna) desc, i_product asc, e_color_name asc
                ", FALSE);
            }
        }
    }

    /*----------  CEK SPB REFERENSI SUDAH ADA  ----------*/

    public function cekspb($iopref, $idcustomer)
    {
        // $this->db->select('i_referensi');
        // $this->db->from('tm_spb');
        // $this->db->where('i_referensi', $iopref);
        // $this->db->where('id_customer', $idcustomer);
        // $this->db->where('id_company', $this->company);
        // $this->db->where_in('i_status', '5');

        return $this->db->query("select i_referensi from tm_spb where i_referensi = '$iopref' and id_customer = $idcustomer and id_company = $this->company and i_status in ('1','2','3', '6')", FALSE);
    }

    public function getforecast($cari, $idcustomer) {
        return $this->db->query("
            WITH cte as (
                 select distinct periode , substring(periode,0,5) as tahun, to_char(to_date(periode, 'yyyymm'),'FMMonth') as bulan 
                 from tm_forecast_distributor where id_customer = '$idcustomer' and id_company = '$this->id_company' and i_status = '6'
            )
            select * from cte
            where periode ilike '%$cari%' or tahun ilike '%$cari%' or bulan ilike '%$cari%'
        ", false);
    }
    /*----------  GET DETAIL BARANG JADI FORECAST  ----------*/

    public function getdetailforecast($idcustomer, $periode)
    {
        $tanggal = date('d');
        $ddocument = substr($periode,0,4).'-'.substr($periode,4,6).'-'.$tanggal;
        $dberlaku  = date('Y-m-d', strtotime($ddocument));
        $dakhir    = date('Y-m-d', strtotime('+1 year', strtotime($ddocument))); /*tambah tanggal sebanyak 1 tahun*/
        //$periode   = date('Ym', strtotime($ddocument));

        return $this->db->query("
            SELECT * from (
                 select DISTINCT on (a.id_product) a.id_product, b.i_product_base AS i_product, b.e_product_basename AS e_product, 
                 COALESCE (a.n_quantity_sisa, 0) AS n_quantity_fc, coalesce(c.v_price,0) as v_price, c.d_berlaku, co.e_color_name
                 from f_get_forecast_distributor($this->company, '$periode', $idcustomer) a
                 INNER JOIN tr_product_base b on (b.id = a.id_product AND a.id_company = b.id_company)
                 INNER JOIN tr_color co on (b.i_color = co.i_color AND co.id_company = b.id_company)
                 inner join tr_customer cu on (cu.id = $idcustomer)
                 left JOIN tr_harga_jualbrgjd c on (c.id_product_base = b.id AND b.id_company = c.id_company and c.id_harga_kode = cu.id_harga_kode AND id_jenis_barang_keluar = '1'
                 and c.d_berlaku <= '$dberlaku' and CASE WHEN d_akhir ISNULL THEN '9999-12-31' ELSE d_akhir END >= '$dakhir')
                 where a.n_quantity_sisa > 0 
            ) AS x
            ORDER by 2, 3
            ", FALSE);
    }

    /*----------  BACA HISTORY PENJUALAN 6 BULAN KE BELAKANG  ----------*/

    public function history($ddocument, $idcustomer)
    {
        $periode1 = date('Ym', strtotime('-1 month', strtotime($ddocument)));
        $periode2 = date('Ym', strtotime('-2 month', strtotime($ddocument)));
        $periode3 = date('Ym', strtotime('-3 month', strtotime($ddocument)));
        $periode4 = date('Ym', strtotime('-4 month', strtotime($ddocument)));
        $periode5 = date('Ym', strtotime('-5 month', strtotime($ddocument)));
        $periode6 = date('Ym', strtotime('-6 month', strtotime($ddocument)));
        return $this->db->query("
            SELECT
                sum(v_total1) AS v_total1,
                sum(v_total2) AS v_total2,
                sum(v_total3) AS v_total3,
                sum(v_total4) AS v_total4,
                sum(v_total5) AS v_total5,
                sum(v_total6) AS v_total6
            FROM(
                SELECT
                    sum(v_bersih) AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode1'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    sum(v_bersih) AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode2'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    sum(v_bersih) AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode3'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    sum(v_bersih) AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode4'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    sum(v_bersih) AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode5'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    sum(v_bersih) AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode6'
                    AND id_customer = '$idcustomer'
            ) AS x
        ", FALSE);
    }

    /*----------  BACA SISA PIUTANG CUSTOMER  ----------*/

    public function piutang($idcustomer)
    {
        return $this->db->query("
            SELECT
                coalesce(sum(v_sisa),0) AS piutang
            FROM
                tm_nota_penjualan
            WHERE
                id_company = '$this->company'
                AND i_status = '6'
                AND id_customer = '$idcustomer'
                AND v_sisa > 0
        ", FALSE)->row()->piutang;
    }

    /*----------  UPDATE STATUS PRINT  ----------*/

    public function updateprint($id)
    {
        $this->db->query("
            UPDATE tm_spb SET n_print = n_print + 1 WHERE id = $id
        ", FALSE);
    }

    public function get_ppn($date)
    {
        return $this->db->query("SELECT n_tax FROM public.tr_tax_amount WHERE f_active = 't' AND '$date' BETWEEN d_start AND d_finish")->row()->n_tax;
    }


    public function update_spb_new_word($id_spb)
    {
        $query = $this->db->query(
            "SELECT b.id id_item, n_ppn, id_product, a.id, n_quantity, v_price, n_diskon1, n_diskon2, n_diskon3, n_diskon4, v_diskon_tambahan
            FROM tm_spb a, tm_spb_item b
            WHERE a.id = b.id_document AND a.id = '$id_spb' "
        );

        if ($query->num_rows()>0) {
            $v_bruto = 0;
            $v_netto = 0;
            $v_dpp = 0;
            $v_ppn = 0;
            $v_discount = 0;
            $n_ppn = $query->row()->n_ppn;
            foreach ($query->result() as $key) {
                $jumlah = $key->n_quantity * $key->v_price;
                $v_discount1 = $jumlah * ($key->n_diskon1/100);
                $v_discount2 = ($jumlah - $v_discount1) * ($key->n_diskon2/100);
                $v_discount3 = ($jumlah - $v_discount1 - $v_discount2) * ($key->n_diskon3/100);
                // $v_discount4 = ($jumlah - $v_discount1 - $v_discount2 - $v_discount3) * ($key->n_diskon4/100);
                $v_total_discount = $v_discount1 + $v_discount2 + $v_discount3 + $key->v_diskon_tambahan;
                // $v_total = $jumlah - $v_total_discount;

                $item = array(
                    'v_diskon1' => $v_discount1, 
                    'v_diskon2' => $v_discount2, 
                    'v_diskon3' => $v_discount3, 
                    'v_diskon_total' => $v_total_discount, 
                    'v_total' => $jumlah, 
                );
                $this->db->where('id', $key->id_item);
                $this->db->update('tm_spb_item', $item);

                $v_bruto += $jumlah;
                $v_discount += $v_total_discount;
            }
            $v_dpp = $v_bruto - $v_discount;
            $v_ppn = $v_dpp * ($n_ppn / 100);
            $v_netto = $v_dpp + $v_ppn;
            $header = array(
                'v_bruto' => $v_bruto,
                'v_discount' => $v_discount,
                'v_dpp' => $v_dpp,
                'v_ppn' => $v_ppn,
                'v_netto' => $v_netto,
            );
            $this->db->where('id', $id_spb);
            $this->db->update('tm_spb', $header);
        }
    }

}
/* End of file Mmaster.php */