<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany  = $this->session->userdata('id_company');

        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_pp BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_pp
            WHERE
                i_status <> '5'
                AND id_company = '" . $this->session->userdata('id_company') . "'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND username = '" . $this->session->userdata('username') . "')

        ", FALSE);
        if ($this->session->userdata('i_departement') == '4' || $this->session->userdata('i_departement') == '1') {
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
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND username = '" . $this->session->userdata('username') . "')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                a.id,
                i_pp,
                to_char(d_pp, 'dd-mm-yyyy') AS d_pp,
                to_char(d_batas_pengiriman, 'dd-mm-yyyy') AS d_batas_pengiriman,
                e_bagian_name,
                e_remark,
                a.i_status,
                e_status_name,
                label_color,
                f.i_level,
			    l.e_level_name,
                a.id_company,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_pp a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_status_document c ON
                (c.i_status = a.i_status)
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
            AND 
                a.id_company = '$idcompany'
                $where
                $bagian
            ORDER BY
                a.id DESC ", FALSE);
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id      = trim($data['id']);
            $i_menu  = $data['i_menu'];
            $i_status = $data['i_status'];
            $i_level = $data['i_level'];
            $folder  = $data['folder'];
            $dfrom   = $data['dfrom'];
            $dto     = $data['dto'];
            $data    = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success mr-3 fa-lg'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer mr-3 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3 fa-lg'></i></a>";
            }
            if (check_role($i_menu, 6) && $i_status == '6') {
                $data .= "<a href=\"".base_url($folder.'/cform/export/'.encrypt_url($id))."\" target='_blank' title='Export PP'><i class='ti-download text-dark mr-3 fa-lg'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('id_company');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');

        return $datatables->generate();
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_pp');
        $this->db->from('tm_pp');
        $this->db->where('i_pp', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_pp, 1, 2) AS kode 
            FROM tm_pp 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata('id_company') . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'PP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_pp, 9, 6)) AS max
            FROM
                tm_pp
            WHERE to_char (d_pp, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata('id_company') . "'
            AND substring(i_pp, 1, 2) = '$kode'
            AND substring(i_pp, 4, 2) = substring('$thbl',1,2)
        ", false);
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

    public function kelompok($cari, $ibagian)
    {
        $cari = str_replace("'", "", $cari);
        if ($this->session->userdata('i_departement') != '4') {
            $bagian = "AND i_bagian = '$ibagian'";
        } else {
            $bagian = "";
        }
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
                    AND id_company = '" . $this->session->userdata('id_company') . "'
                    $bagian )
                AND id_company = '" . $this->session->userdata('id_company') . "'
            ORDER BY
                e_nama_kelompok
        ", FALSE);
    }

    public function jenis($cari, $ikelompok, $ibagian)
    {
        $jenis = "";
        if ($this->session->userdata('i_departement') != '4' || $this->session->userdata('i_departement') != '1') {
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
                    AND id_company = '" . $this->session->userdata('id_company') . "')";
            }
        }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                e_type_name ILIKE '%$cari%'
                AND f_status = 't'
                AND id_company = '" . $this->session->userdata('id_company') . "'
                $jenis
            ORDER BY
                e_type_name
        ", FALSE);
    }

    public function supplier($cari)
    {
        return $this->db->query("SELECT
            DISTINCT 
                a.id,
                a.i_supplier,
                a.e_supplier_name
            FROM
                tr_supplier a
            INNER JOIN tr_supplier_materialprice b ON
                (b.i_supplier = a.i_supplier)
            WHERE
                a.f_status = 't'
                AND (a.e_supplier_name ILIKE '%$cari%'
                    OR a.i_supplier ILIKE '%$cari%')
                    AND a.id_company = '$this->id_company'
        ", FALSE);
    }

    public function budgeting($cari)
    {
        // Backup 2022-09-21
        /* return $this->db->query("SELECT
                a.id, a.i_document, to_char(a.d_document, 'DD FMMonth YYYY') AS d_document, to_char(to_date(b.periode, 'yyyymm'), 'FMMonth YYYY') as periode
            FROM
                tm_budgeting a
            INNER JOIN tm_forecast_produksi b on (a.id_referensi = b.id)
            WHERE
                a.i_status = '6' and a.i_status_rp = '6' and a.id not in ( select id_budgeting from tm_pp where i_status not in ('5', '9', '4') and id_company = '$this->id_company' and id_budgeting is not null)
                AND (a.i_document ILIKE '%$cari%')
                AND a.id_company = '$this->id_company'
        ", FALSE); */
        return $this->db->query("SELECT DISTINCT a.id,
                a.i_document,
                to_char(a.d_document, 'DD FMMonth YYYY') AS d_document,
                to_char(to_date(b.periode, 'yyyymm'), 'FMMonth YYYY') AS periode
            FROM
                tm_budgeting a
            INNER JOIN tm_forecast_produksi b ON (a.id_referensi = b.id)
            INNER JOIN tm_budgeting_item_material c ON (c.id_document = a.id)
            INNER JOIN tr_material d ON (d.id = c.id_material)
            WHERE a.i_status = '6'
                AND a.i_status_rp = '6'
                AND a.id || d.i_material NOT IN (
                    SELECT
                        id_budgeting || i_material
                    FROM
                        tm_pp a,
                        tm_pp_item b
                    WHERE
                        a.id = b.id_pp
                        AND i_status NOT IN ('5', '9', '4')
                            AND a.id_company = '$this->id_company'
                            AND id_budgeting IS NOT NULL
                )
                AND (a.i_document ILIKE '%$cari%')
                AND a.id_company = '$this->id_company'
                ORDER BY 2;
        ", FALSE);
    }

    public function material($cari, $ikategori, $ijenis, $ibagian)
    {
        $kategori = "";
        $jenis    = "";
        if ($this->session->userdata('i_departement') != '4' || $this->session->userdata('i_departement') != '1') {
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
                        AND id_company = '" . $this->session->userdata('id_company') . "')";
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
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND i_kode_kelompok IN 
                            (SELECT
                                i_kode_kelompok
                            FROM
                                tr_bagian_kelompokbarang
                            WHERE
                                i_bagian = '$ibagian'
                                AND id_company = '" . $this->session->userdata('id_company') . "'))";
            }
        }
        return $this->db->query("
            SELECT
                i_material,
                e_material_name,
                i_kode_kelompok,
                e_satuan_name,
                a.i_satuan_code
            FROM
                tr_material a,
                tr_satuan b
            WHERE
                a.i_satuan_code = b.i_satuan_code
                AND a.f_status = 't'
                AND (i_material ILIKE '%$cari%' 
                OR e_material_name ILIKE '%$cari%')
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
                AND b.id_company = '" . $this->session->userdata('id_company') . "'

            ORDER BY
                i_material
        ", FALSE);
    }

    public function getmaterial($imaterial)
    {
        return $this->db->query("
            SELECT
                i_material,
                e_material_name,
                i_kode_kelompok,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN a.i_satuan_code
                    ELSE c.i_satuan_code_konversi
                END AS i_satuan_code,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN b.e_satuan_name
                    ELSE d.e_satuan_name
                END AS e_satuan_name
            FROM
                tr_material a
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code
                    AND a.id_company = b.id_company)
            LEFT JOIN tr_material_konversi c ON
                (c.id_material = a.id 
                AND c.f_default = 't')
            LEFT JOIN tr_satuan d ON
                (d.i_satuan_code = c.i_satuan_code_konversi
                    AND c.id_company = d.id_company)
            WHERE
                a.i_satuan_code = b.i_satuan_code
                AND a.f_status = 't'
                AND i_material = '$imaterial'
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
                AND b.id_company = '" . $this->session->userdata('id_company') . "'
        ", FALSE);
    }

    public function materialbudget($cari, $ikategori, $ijenis, $ibagian, $dpp)
    {
        $iperiode = date('Ym', strtotime($dpp));
        $kategori = "";
        $jenis    = "";
        if ($this->session->userdata('i_departement') != '4' || $this->session->userdata('i_departement') != '1') {
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
                        AND id_company = '" . $this->session->userdata('id_company') . "')";
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
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND i_kode_kelompok IN 
                            (SELECT
                                i_kode_kelompok
                            FROM
                                tr_bagian_kelompokbarang
                            WHERE
                                i_bagian = '$ibagian'
                                AND id_company = '" . $this->session->userdata('id_company') . "'))";
            }
        }
        return $this->db->query("
            SELECT DISTINCT 
                i_material,
                e_material_name,
                i_kode_kelompok,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN b.e_satuan_name
                    ELSE e.e_satuan_name
                END AS e_satuan_name,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN b.i_satuan_code
                    ELSE c.i_satuan_code_konversi
                END AS i_satuan_code
            FROM
                tr_material a
            INNER JOIN 
                tm_budgeting_item_material c ON
                (a.id = c.id_material)
            INNER JOIN tm_budgeting d ON
                (d.id = c.id_document)
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code
                    AND a.id_company = b.id_company)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = c.i_satuan_code_konversi AND c.id_company = e.id_company)
            WHERE
                to_char(d.d_document, 'YYYYmm') = '$iperiode'
                AND a.f_status = 't'
                AND (i_material ILIKE '%$cari%'
                    OR e_material_name ILIKE '%$cari%')
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
                AND b.id_company = '" . $this->session->userdata('id_company') . "'
                AND d.i_status = '6'
                $kategori
                $jenis
            ORDER BY
                i_material
        ", FALSE);
    }

    /** Rubah 2021-11-24 */
    public function getmaterialbudgetold($imaterial, $dpp)
    {
        $iperiode = date('Ym', strtotime($dpp));
        return $this->db->query("
            SELECT DISTINCT 
                i_material,
                e_material_name,
                i_kode_kelompok,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN b.e_satuan_name
                    ELSE e.e_satuan_name
                END AS e_satuan_name,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN b.i_satuan_code
                    ELSE c.i_satuan_code_konversi
                END AS i_satuan_code,
                c.n_budgeting_sisa AS n_sisa
            FROM
                tr_material a
            INNER JOIN 
                tm_budgeting_item_material c ON
                (a.id = c.id_material)
            INNER JOIN tm_budgeting d ON
                (d.id = c.id_document)
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code
                    AND a.id_company = b.id_company)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = c.i_satuan_code_konversi AND c.id_company = e.id_company)
            WHERE
                to_char(d.d_document, 'YYYYmm') = '$iperiode'
                AND a.f_status = 't'
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
                AND b.id_company = '" . $this->session->userdata('id_company') . "'
                AND d.i_status = '6'
                AND i_material = '$imaterial'

            /* SELECT
                i_material,
                e_material_name,
                i_kode_kelompok,
                e_satuan_name,
                a.i_satuan_code,
                c.n_budgeting_sisa AS n_sisa
            FROM
                tr_material a,
                tr_satuan b,
                tm_budgeting_item_material c,
                tm_budgeting d
            WHERE
                a.i_satuan_code = b.i_satuan_code
                AND a.id = c.id_material
                AND c.id_document = d.id
                AND to_char(d.d_document, 'YYYYmm') = '$iperiode'
                AND a.f_status = 't'
                AND i_material = '$imaterial'
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
                AND b.id_company = '" . $this->session->userdata('id_company') . "' */
        ", FALSE);
    }

    public function getmaterialbudget($i_budgeting, $i_bagian)
    {
        return $this->db->query("SELECT DISTINCT 
                i_material,
                e_material_name,
                i_kode_kelompok,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN b.e_satuan_name
                    ELSE e.e_satuan_name
                END AS e_satuan_name,
                CASE
                    WHEN c.i_satuan_code_konversi ISNULL THEN b.i_satuan_code
                    ELSE c.i_satuan_code_konversi
                END AS i_satuan_code,
                c.n_budgeting_sisa AS n_sisa,
                CASE WHEN (c.e_remark ISNULL OR c.e_remark = '' OR c.e_remark = 'null') THEN '' ELSE c.e_remark END AS e_remark,
                c.id_supplier, c.v_price - (c.v_price*n_ppn) as v_price, c.v_price_adj - (c.v_price_adj*n_ppn) as v_price_adj,
                f.i_supplier, f.e_supplier_name
            FROM
                tr_material a
            INNER JOIN 
                tm_budgeting_item_material c ON
                (a.id = c.id_material)
            INNER JOIN tm_budgeting d ON
                (d.id = c.id_document)
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code
                    AND a.id_company = b.id_company)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = c.i_satuan_code_konversi AND c.id_company = e.id_company)
            LEFT JOIN tr_supplier f ON (c.id_supplier = f.id)
            WHERE
                d.id = $i_budgeting
                AND a.i_kode_group_barang IN (
                    SELECT i_kode_group_barang 
                    FROM tr_type 
                    WHERE i_type IN (
                        SELECT i_type 
                        FROM tr_bagian 
                        WHERE i_bagian = '$i_bagian' 
                        AND id_company = '$this->id_company'
                    )
                )
                AND d.id || a.i_material NOT IN (
                    SELECT
                        id_budgeting || i_material
                    FROM
                        tm_pp a,
                        tm_pp_item b
                    WHERE
                        a.id = b.id_pp
                        AND i_status NOT IN ('5', '9', '4')
                            AND a.id_company = '$this->id_company'
                            AND id_budgeting IS NOT NULL
                )
        ", FALSE);
    }

    public function getmaterialprice($i_supplier, $i_material, $d_document)
    {
        return $this->db->query("SELECT
                v_price
            FROM
                tr_supplier_materialprice a
            INNER JOIN tr_supplier b ON
                (b.i_supplier = a.i_supplier
                    AND a.id_company = b.id_company)
            WHERE
                a.id_company = '$this->id_company'
                AND i_status = '6'
                AND i_material = '$i_material'
                AND b.id = '$i_supplier'
                AND d_akhir ISNULL
        ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_pp');
        return $this->db->get()->row()->id + 1;
    }

    public function insertheader($id, $ibagian, $ipp, $dpp, $dbp, $remark, $budgeting, $ibudgeting)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id'                => $id,
            'i_pp'              => $ipp,
            'd_pp'              => $dpp,
            'd_batas_pengiriman' => $dbp,
            'i_bagian'          => $ibagian,
            'e_remark'          => $remark,
            'id_company'        => $idcompany,
            'd_entry'           => current_datetime(),
            'f_budgeting'       => $budgeting,
            'id_budgeting'      => $ibudgeting,
        );
        $this->db->insert('tm_pp', $data);
    }

    public function insertdetail($id, $ipp, $imaterial, $ikode, $isatuan, $nquantity, $eremark, $isupplier, $harga_adj, $harga_sup)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id_pp'          => $id,
            'i_pp'           => $ipp,
            'i_material'     => $imaterial,
            'i_satuan_code'  => $isatuan,
            'i_kode_kelompok' => $ikode,
            'n_quantity'     => $nquantity,
            'n_sisa'         => $nquantity,
            'e_remark'       => $eremark,
            'id_company'     => $idcompany,
            'id_supplier'    => $isupplier,
            'v_price'        => $harga_sup,
            'v_price_adj'    => $harga_adj,
        );
        $this->db->insert('tm_pp_item', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				from tm_pp a
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $query = $this->db->query("SELECT f_budgeting, d_pp FROM tm_pp WHERE id = '$id' ", FALSE);
                    if ($query->num_rows() > 0) {
                        $budgeting = $query->row()->f_budgeting;
                        $iperiode  = date('Ym', strtotime($query->row()->d_pp));
                        if ($budgeting == 't') {
                            $getitem = $this->db->query("SELECT i_material, n_quantity FROM tm_pp_item WHERE id_pp = '$id' ", FALSE);
                            if ($getitem->num_rows() > 0) {
                                foreach ($getitem->result() as $key) {
                                    $this->db->query("UPDATE
                                        tm_budgeting_item_material x
                                    SET
                                        n_budgeting_sisa = n_budgeting_sisa - $key->n_quantity
                                    FROM
                                        tm_budgeting y,
                                        tr_material z
                                    WHERE
                                        y.id = x.id_document
                                        AND x.id_material = z.id
                                        AND to_char(y.d_document, 'YYYYmm') = '$iperiode'
                                        AND z.i_material = '$key->i_material' ", FALSE);
                                }
                            }
                        }
                    }
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->session->userdata('username'),
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_pp');", FALSE);
            }
        }
        /* if ($istatus=='6') {
            $query = $this->db->query("SELECT f_budgeting, d_pp FROM tm_pp WHERE id = '$id' ", FALSE);
            if ($query->num_rows()>0) {
                $budgeting = $query->row()->f_budgeting;
                $iperiode  = date('Ym', strtotime($query->row()->d_pp));
                if ($budgeting=='t') {
                    $getitem = $this->db->query("SELECT i_material, n_quantity FROM tm_pp_item WHERE id_pp = '$id' ", FALSE);
                    if ($getitem->num_rows()>0) {
                        foreach ($getitem->result() as $key) {
                            $this->db->query("UPDATE
                                tm_budgeting_item_material x
                            SET
                                n_budgeting_sisa = n_budgeting_sisa - $key->n_quantity
                            FROM
                                tm_budgeting y,
                                tr_material z
                            WHERE
                                y.id = x.id_document
                                AND x.id_material = z.id
                                AND to_char(y.d_document, 'YYYYmm') = '$iperiode'
                                AND z.i_material = '$key->i_material' ", FALSE);
                        }
                    }
                }
                $data = array(
                    'i_status'  => $istatus,
                    'e_approve' => $this->session->userdata('username'),
                    'd_approve' => date('Y-m-d'),
                );
            }else{
                $data = array(
                    'i_status'  => $istatus,
                    'e_approve' => $this->session->userdata('username'),
                    'd_approve' => date('Y-m-d'),
                );
            }
        } */ else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_pp', $data);
    }

    public function dataheader($id)
    {
        $this->db->select('a.*, b.e_bagian_name');
        $this->db->from('tm_pp a');
        $this->db->join('tr_bagian b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
        $this->db->where('a.id', $id);
        return $this->db->get();
    }

    public function datadetail($id)
    {
        $this->db->select('a.*, b.e_material_name, c.e_satuan_name, d.i_supplier, d.e_supplier_name');
        $this->db->from('tm_pp_item a');
        $this->db->join('tr_material b', 'b.i_material = a.i_material AND a.id_company = b.id_company', 'inner');
        $this->db->join('tr_satuan c', 'c.i_satuan_code = a.i_satuan_code AND a.id_company = c.id_company', 'inner');
        $this->db->join('tr_supplier d', 'd.id = a.id_supplier AND a.id_company = d.id_company', 'left');
        $this->db->where('a.id_pp', $id);
        $this->db->order_by('b.e_material_name', 'ASC');
        return $this->db->get();
    }

    public function datadetail1($id)
    {
        return $this->db->query("SELECT a.*, b.e_material_name, c.e_satuan_name, d.i_supplier, d.e_supplier_name
            FROM tm_pp_item a
            INNER JOIN tr_material b ON b.i_material = a.i_material AND a.id_company = b.id_company
            INNER JOIN tr_satuan c ON c.i_satuan_code = a.i_satuan_code AND a.id_company = c.id_company
            left JOIN tr_supplier d ON d.id = a.id_supplier AND a.id_company = d.id_company
            WHERE a.id_pp = '$id'
            AND b.i_kode_group_barang = CASE
                WHEN (
                SELECT
                    i_kode_group_barang
                FROM
                    tr_type
                WHERE
                    i_departement = '$this->i_departement') ISNULL THEN b.i_kode_group_barang
                ELSE (
                SELECT
                    i_kode_group_barang
                FROM
                    tr_type
                WHERE
                    i_departement = '$this->i_departement')
            END
            ORDER BY b.e_material_name
        ");
    }

    public function updateheader($id, $ibagian, $ipp, $dpp, $dbp, $remark)
    {
        $data = array(
            'id'                => $id,
            'i_pp'              => $ipp,
            'd_pp'              => $dpp,
            'd_batas_pengiriman' => $dbp,
            'i_bagian'          => $ibagian,
            'i_status'          => 1,
            'e_remark'          => $remark,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_pp', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_pp', $id);
        $this->db->delete('tm_pp_item');
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function get_data_export_header($id)
    {
        $this->db->select("b.e_bagian_name, a.d_pp, a.i_pp, CASE WHEN f_budgeting = TRUE THEN 'Budgeting' ELSE 'Out of Budget' END budgeting");
        $this->db->from("tm_pp a");
        $this->db->join("tr_bagian b","b.i_bagian = a.i_bagian AND a.id_company = b.id_company","inner");
        $this->db->where("a.id", $id);
        return $this->db->get();
    }

    public function get_data_export_item($id)
    {
        $this->db->select("
            a.i_material, upper(trim(b.e_material_name)) e_material_name, 
            upper(trim(c.e_satuan_name)) e_satuan_name, a.n_quantity, 
            CASE WHEN a.e_remark = 'null' OR a.e_remark = '' THEN NULL ELSE a.e_remark END e_remark
        ");
        $this->db->from("tm_pp_item a");
        $this->db->join("tr_material b","(b.i_material  = a.i_material AND a.id_company  = b.id_company)","inner");
        $this->db->join("tr_satuan c","(c.i_satuan_code = a.i_satuan_code AND a.id_company = c.id_company)","inner");
        $this->db->where("a.id_pp", $id);
        $this->db->order_by(1);
        return $this->db->get();
    }
}
/* End of file Mmaster.php */
