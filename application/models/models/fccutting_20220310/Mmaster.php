<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{


    /*----------  DATA HEADER KN  ----------*/
    /*
    * Bercocok Tanam Diprogram
    * Jenis Gudang Retur{
        Jika 1 = Gudang Bahan Baku
        Jika 2 = Gudang Aksesoris
        Jika 3 = Gudang Bahan Pembantu
        Jika 4 = Gudang Jadi
    }
    */

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
                tm_fccutting
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
                        AND id_company = '$this->company') ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 0 AS NO,
                a.id,
                i_document,
                to_char(d_document, 'DD FMMonth YYYY') AS d_document,
                to_char(to_date(i_periode,'yyyymm'), 'FMMonth YYYY') AS i_periode,
                e_bagian_name,
                e_remark,
                e_status_name,
                label_color,
                a.i_status,
                l.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_fccutting a
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tr_bagian b ON 
                (b.i_bagian = a.i_bagian 
                    AND a.id_company = b.id_company)
            LEFT JOIN public.tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (e.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->company'
                $and
                $bagian
            ORDER BY
                a.id DESC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $i_level  = $data['i_level'];
            $data     = '';

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && ($i_status == '2')) {
                if (($i_level == $this->session->userdata('i_level')) || $this->session->userdata('i_level') == 1) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
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
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    /*----------  DAFTAR DATA REFERENSI  ----------*/

    public function datareferensi($folder, $i_menu, $dfrom, $dto)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                a.id,
                bulan(to_date(a.periode, 'YYYYmm')) || ' ' || substring(a.periode, 1, 4) AS periode,
                substring(a.periode, 1, 4) AS tahun,
                substring(a.periode, 5, 6) AS bulan,
                c.e_bagian_name,
                e_remark_supplier,
                a.i_bagian,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_forecast_produksi a
            INNER JOIN tr_bagian c ON
                (a.i_bagian = c.i_bagian
                    AND a.id_company = c.id_company)
            WHERE
                to_date(a.periode, 'YYYYmm') BETWEEN to_date('$dfrom', '01-mm-yyyy') AND to_date('$dto', '01-mm-yyyy')
                AND a.i_status <> '5'
                AND a.id_company = '$this->company' and a.id not in (select id_referensi from tm_fccutting where i_status in ('1','2','3','6'))
            ", FALSE);

        $datatables->add('action', function ($data) {
            $id           = $data['id'];
            $i_menu       = $data['i_menu'];
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_bagian     = $data['i_bagian'];
            $tahun        = $data['tahun'];
            $bulan        = $data['bulan'];
            $data         = '';
            if (check_role($i_menu, 1)) {
                $data .= "<a href=\"#\" title='Tambah Budgeting' onclick='show(\"$folder/cform/prosesdata/$id/$dfrom/$dto/$tahun/$bulan\",\"#main\"); return false;'><i class='ti-new-window'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_bagian');
        $datatables->hide('tahun');
        $datatables->hide('bulan');
        return $datatables->generate();
    }

    /*----------  GET DATA DETAIL REFERENSI  ----------*/

    public function datadetail($id)
    {

        /*SELECT DISTINCT
                c.id AS id_product_base,
                c.i_product_base,
                initcap(c.e_product_basename) AS e_product_basename,
                a.n_quantity AS n_quantity,
                f.id AS id_material,
                f.i_material,
                initcap(f.e_material_name) AS e_material_name,
                initcap(g.e_satuan_name) AS e_satuan_name,
                trim(h.e_color_name) AS e_color_name,
                e.v_gelar , e.v_set , a.n_quantity / e.v_set as jml_gelar, (a.n_quantity/e.v_set) * e.v_gelar as p_kain
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tm_forecast_produksi b on (b.id = a.id_forecast)
            INNER JOIN tr_product_base c on (c.id = a.id_product)
            LEFT JOIN tr_product_wip d on (d.i_product_wip = c.i_product_wip AND c.id_company = d.id_company)
            LEFT JOIN tr_polacutting_new e on (d.id = e.id_product_wip)
            LEFT JOIN tr_material f on (f.id = e.id_material)
            LEFT JOIN tr_satuan g on (g.i_satuan_code = f.i_satuan_code AND f.id_company = g.id_company)
            LEFT JOIN tr_color h on (h.i_color = c.i_color AND c.id_company = h.id_company)
            WHERE
                b.id = '$id' and e.v_gelar > 0;
            */
        return $this->db->query("
            SELECT DISTINCT
                d.id AS id_product_base,
                d.i_product_wip,
                initcap(d.e_product_wipname) AS e_product_basename,
                a.n_quantity AS n_quantity,
                f.id AS id_material,
                f.i_material,
                initcap(f.e_material_name) AS e_material_name,
                initcap(g.e_satuan_name) AS e_satuan_name,
                trim(h.e_color_name) AS e_color_name,
                e.v_gelar , e.v_set , a.n_quantity / e.v_set as jml_gelar, (a.n_quantity/e.v_set) * e.v_gelar as p_kain
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tm_forecast_produksi b on (b.id = a.id_forecast)
            INNER JOIN tr_product_base c on (c.id = a.id_product)
            LEFT JOIN tr_product_wip d on (d.i_product_wip = c.i_product_wip AND c.id_company = d.id_company)
            LEFT JOIN tr_polacutting_new e on (d.id = e.id_product_wip)
            LEFT JOIN tr_material f on (f.id = e.id_material)
            LEFT JOIN tr_satuan g on (g.i_satuan_code = f.i_satuan_code AND f.id_company = g.id_company)
            LEFT JOIN tr_color h on (h.i_color = d.i_color AND c.id_company = h.id_company)
            where b.id = '$id' and e.v_gelar > 0;
        ", FALSE);
    }

    /*----------  GET DATA DETAIL ITEM REFERENSI  ----------*/

    /* Perbaikan 2021-11-19 */
    /* public function datadetaill($id)
    {
        return $this->db->query("SELECT
                DISTINCT x.id_material,
                i_material,
                e_material_name,
                x.e_satuan_name,
                COALESCE (saldoakhir,
                0) AS mutasi,
                y.e_operator,
                y.n_faktor,
                CASE
                    WHEN y.i_satuan_code_konversi ISNULL THEN x.i_satuan_code
                    ELSE y.i_satuan_code_konversi
                END AS i_satuan_konversi,
                CASE
                    WHEN z.e_satuan_name ISNULL THEN x.e_satuan_name
                    ELSE z.e_satuan_name
                END AS e_satuan_konversi,
                sum(kebutuhan) AS kebutuhan
            FROM
                (
                SELECT
                    c.id AS id_product_base,
                    c.i_product_base,
                    c.e_product_basename,
                    a.n_quantity,
                    e.n_quantity AS pemakaian,
                    f.id AS id_material,
                    f.i_material,
                    f.e_material_name,
                    a.n_quantity * e.n_quantity AS kebutuhan,
                    g.i_satuan_code,
                    g.e_satuan_name,
                    h.saldoakhir
                FROM
                    tm_forecast_produksi_item a
                INNER JOIN tm_forecast_produksi b ON
                    (b.id = a.id_forecast)
                INNER JOIN tr_product_base c ON
                    (c.id = a.id_product)
                LEFT JOIN tr_product_wip d ON
                    (d.i_product_wip = c.i_product_wip
                        AND c.id_company = d.id_company)
                LEFT JOIN tr_product_wip_item e ON
                    (e.id_product_wip = d.id)
                LEFT JOIN tr_material f ON
                    (f.id = e.id_material)
                LEFT JOIN tr_satuan g ON
                    (g.i_satuan_code = f.i_satuan_code
                        AND f.id_company = g.id_company)
                LEFT JOIN (
                    SELECT
                        *
                    FROM
                        f_mutasi_stock_harian (5,
                        to_char(current_date, 'YYYYmm'),
                        '9999-12-01',
                        '9999-12-31',
                        TO_CHAR(current_date, 'yyyy-mm-01')::date,
                        current_date,
                        'i_bagian')) h ON
                    (h.id_company = a.id_company
                        AND f.i_material = h.i_material)
                WHERE
                    b.id = '$id') AS x
            LEFT JOIN tr_material_konversi y ON
                (y.id_material = x.id_material
                    AND y.f_default = 't')
            LEFT JOIN tr_satuan z ON
                (z.id_company = y.id_company
                    AND y.i_satuan_code_konversi = z.i_satuan_code)
            GROUP BY
                1,2,3,4,5,6,7,8,9
        ", FALSE);
    } */

    /*----------  GET DATA DETAIL ITEM REFERENSI  ----------*/
    /* Perbaikan 2021-11-19 */
    public function datadetaill($id)
    {
        return $this->db->query("SELECT
                DISTINCT x.id_material,
                x.i_material,
                e_material_name,
                x.e_satuan_name,
                COALESCE (saldoakhir,
                0) AS mutasi,
                COALESCE (estimasi,
                0) AS estimasi,
                COALESCE (op_sisa,
                0) AS op_sisa,
                y.e_operator,
                y.n_faktor,
                CASE
                    WHEN y.i_satuan_code_konversi ISNULL THEN x.i_satuan_code
                    ELSE y.i_satuan_code_konversi
                END AS i_satuan_konversi,
                CASE
                    WHEN z.e_satuan_name ISNULL THEN x.e_satuan_name
                    ELSE z.e_satuan_name
                END AS e_satuan_konversi,
                sum(kebutuhan) AS kebutuhan
            FROM
                (
                SELECT
                    c.id AS id_product_base,
                    c.i_product_base,
                    initcap(c.e_product_basename) AS e_product_basename,
                    a.n_quantity,
                    e.n_quantity AS pemakaian,
                    f.id AS id_material,
                    f.i_material,
                    initcap(f.e_material_name) AS e_material_name,
                    a.n_quantity * e.n_quantity AS kebutuhan,
                    g.i_satuan_code,
                    initcap(g.e_satuan_name) AS e_satuan_name,
                    h.saldoakhir,
                    a.id_company,
                    estimasi,
                    op_sisa
                FROM
                    tm_forecast_produksi_item a
                INNER JOIN tm_forecast_produksi b ON
                    (b.id = a.id_forecast)
                INNER JOIN tr_product_base c ON
                    (c.id = a.id_product)
                LEFT JOIN tr_product_wip d ON
                    (d.i_product_wip = c.i_product_wip
                        AND c.id_company = d.id_company)
                LEFT JOIN tr_product_wip_item e ON
                    (e.id_product_wip = d.id)
                LEFT JOIN tr_material f ON
                    (f.id = e.id_material)
                LEFT JOIN tr_satuan g ON
                    (g.i_satuan_code = f.i_satuan_code
                        AND f.id_company = g.id_company)
                LEFT JOIN (
                    SELECT
                        *
                    FROM
                        f_mutasi_stock_harian (5,
                        to_char(current_date, 'YYYYmm'),
                        '9999-12-01',
                        '9999-12-31',
                        TO_CHAR(current_date, 'yyyy-mm-01')::date,
                        current_date,
                        'i_bagian')) h ON
                    (h.id_company = a.id_company
                        AND f.i_material = h.i_material)
                LEFT JOIN (
                    SELECT
                        a.i_material,
                        a.id_company,
                        sum(a.n_sisa) + COALESCE (sum(c.n_sisa),0) AS estimasi
                    FROM
                        tm_pp_item a
                    JOIN tm_pp b ON (a.id_pp = b.id) 
                    LEFT JOIN tm_opbb_item c ON (c.id_pp = a.id_pp AND a.i_material = c.i_material)
                    LEFT JOIN tm_opbb d ON (d.id = c.id_op AND d.i_status = '6' AND d.f_op_close = 'f')
                    WHERE b.i_status = '6'
                        AND a.id_company = '$this->company'
                        AND a.id_pp NOT IN (
                        SELECT
                            id_pp
                        FROM
                            tm_btb_item a,
                            tm_btb b
                        WHERE
                            a.id_btb = b.id
                            AND b.i_status = '6'
                            AND b.id_company = '$this->company'
                            AND a.id_pp NOTNULL)
                    GROUP BY 1,2) q ON
                    (q.i_material = f.i_material
                        AND a.id_company = q.id_company)
                LEFT JOIN (
                    SELECT
                        b.i_material,
                        b.id_company,
                        sum(b.n_sisa) AS op_sisa
                    FROM
                        tm_opbb a
                    INNER JOIN tm_opbb_item b ON
                        (b.id_op = a.id)
                    WHERE
                        a.i_status = '6'
                        AND f_op_close = 't'
                        AND a.id_company = '$this->company'
                        AND to_char(a.d_op, 'YYYYMM') = to_char(current_date, 'YYYYMM')
                    GROUP BY
                        1,
                        2) s ON
                    (s.i_material = f.i_material
                        AND a.id_company = s.id_company)
                WHERE
                    b.id = '$id') AS x
            LEFT JOIN tr_material_konversi y ON
                (y.id_material = x.id_material
                    AND y.f_default = 't')
            LEFT JOIN tr_satuan z ON
                (z.id_company = y.id_company
                    AND y.i_satuan_code_konversi = z.i_satuan_code)
            GROUP BY
                1,2,3,4,5,6,7,8,9,10,11
        ", FALSE);
    }

    /*----------  GET DATA DETAIL ITEM REFERENSI BISBISAN  ----------*/

    public function datadetailbisbisan($id)
    {
        return $this->db->query("WITH cte AS (
            SELECT
                c.id AS id_product_wip,
                a.n_quantity
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tr_product_base b ON
                (a.id_product = b.id)
            INNER JOIN tr_product_wip c ON
                (b.i_product_wip = c.i_product_wip
                    AND b.i_color_wip = c.i_color )
            WHERE
                a.id_forecast = '$id'
            )
            SELECT
                c.i_material,
                initcap(c.e_material_name) AS e_material_name,
                d.n_quantity,
                b.n_bisbisan ,
                initcap(e.e_jenis_potong) AS e_jenis_potong,
                initcap(f.e_satuan_name) AS e_satuan_name,
                COALESCE (a.v_bisbisan / b.v_panjang_bis,
                0) AS pemakaian,
                COALESCE (sum((a.v_bisbisan / b.v_panjang_bis) * n_quantity),
                0) AS kebutuhan
            FROM
                tr_polacutting_new a
            INNER JOIN tr_material_bisbisan b ON
                (b.id = a.id_bisbisan)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN cte d ON
                (a.id_product_wip = d.id_product_wip)
            INNER JOIN tr_jenis_potong e ON
                (b.id_jenis_potong = e.id)
            INNER JOIN tr_satuan f ON
                (c.i_satuan_code = f.i_satuan_code
                    AND c.id_company = f.id_company)
            GROUP BY
                1,2,3,4,5,6,7
        ", FALSE);
    }

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian', 'inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("
            SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
            INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            left join tr_type c on (a.i_type = c.i_type)
            left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
            WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
            ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode
            FROM tm_fccutting
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'FC';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_fccutting
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 2) = '$kode'
                AND substring(i_document, 4, 2) = substring('$thbl',1,2)
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
        $this->db->from('tm_fccutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  RUNNING ID  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_fccutting');
        return $this->db->get()->row()->id + 1;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id,$idocument,$ddocument,$ibagian,$idreferensi,$iperiode,$eremark)
    {
        $data = array(
            'id'               => $id,
            'id_company'       => $this->company,
            'i_document'       => $idocument,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_referensi'     => $idreferensi,
            'i_periode'        => $iperiode,
            'e_remark'         => $eremark,
            'i_status'         => 1,
        );
        $this->db->insert('tm_fccutting', $data);
    }

    /*----------  SIMPAN DATA ITEM BASE ----------*/

    public function insertdetailbase($id,$id_product_base,$nilai_base,$id_material,$v_gelar,$v_set,$p_kain,$e_remark)
    {
        $data = array(
            'id_company'            => $this->company,
            'id_forecast'           => $id,
            'id_product_wip'        => $id_product_base,
            'n_quantity_wip'        => $nilai_base,
            'n_sisa_wip'            => $nilai_base,
            'id_material'           => $id_material,
            'v_gelar'               => $v_gelar,
            'v_set'                 => $v_set,
            'n_quantity_material'   => $p_kain,
            'n_sisa_material'       => $p_kain,
            'e_remark'              => $e_remark,
        );
        $this->db->insert('tm_fccutting_item', $data);
    }

    /*----------  SIMPAN DATA ITEM MATERIAL ----------*/

    // public function insertdetailmaterial($id, $idreferensi, $id_material_item, $nilai_kebutuhan_item, $nilai_mutasi, $nilai_budgeting, $i_satuan_konversi, $up, $ket, $nilai_estimasi,$nilai_op_sisa)
    // {
    //     $data = array(
    //         'id_company'             => $this->company,
    //         'id_document'            => $id,
    //         'id_referensi'           => $idreferensi,
    //         'id_material'            => $id_material_item,
    //         'n_kebutuhan'            => $nilai_kebutuhan_item,
    //         'n_mutasi'               => $nilai_mutasi,
    //         'n_budgeting'            => $nilai_budgeting,
    //         'n_budgeting_sisa'       => $nilai_budgeting,
    //         'i_satuan_code_konversi' => $i_satuan_konversi,
    //         'persen_up'              => $up,
    //         'e_remark'               => $ket,
    //         'n_estimasi'             => $nilai_estimasi,
    //         'n_op_sisa'              => $nilai_op_sisa,
    //     );
    //     $this->db->insert('tm_fccutting_item', $data);
    // }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/

    public function editheader($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                to_char(a.d_document, 'DD Month YYYY') AS ddocument,
                id_referensi,i_periode,
                substring(a.i_periode, 1, 4) AS tahun,
                substring(a.i_periode, 5, 6) AS bulan,
                a.e_remark,
                a.i_status
            FROM
                tm_fccutting a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM BASE ----------*/

    public function edititembase($id)
    {
        
        return $this->db->query("
           SELECT DISTINCT
                a.id_product_wip AS id_product_base,
                d.i_product_wip,
                initcap(d.e_product_wipname) AS e_product_basename,
                a.n_quantity_wip AS n_quantity,
                a.id_material,
                f.i_material,
                initcap(f.e_material_name) AS e_material_name,
                initcap(g.e_satuan_name) AS e_satuan_name,
                trim(h.e_color_name) AS e_color_name,
                a.v_gelar , a.v_set , a.n_quantity_wip / a.v_set as jml_gelar, (a.n_quantity_wip/a.v_set) * a.v_gelar as p_kain,
                a.e_remark 
            FROM
                tm_fccutting_item a
            LEFT JOIN tr_product_wip d on (a.id_product_wip = d.id)
            LEFT JOIN tr_material f on (f.id = a.id_material)
            LEFT JOIN tr_satuan g on (g.i_satuan_code = f.i_satuan_code AND f.id_company = g.id_company)
            LEFT JOIN tr_color h on (h.i_color = d.i_color AND d.id_company = h.id_company)
            where a.id_forecast = '$id';
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM MATERIAL ----------*/

    public function edititemmaterial($id)
    {
        return $this->db->query("SELECT
                a.id_material,
                b.i_material,
                e_material_name,
                c.e_satuan_name,
                a.i_satuan_code_konversi AS i_satuan_konversi,
                f.e_satuan_name AS e_satuan_konversi,
                COALESCE (d.saldoakhir,0) AS mutasi,
                a.n_kebutuhan AS kebutuhan,
                a.n_budgeting,
                a.e_remark,
                a.persen_up,
                a.n_estimasi AS estimasi,
                a.n_op_sisa AS op_sisa,
                y.e_operator,
                y.n_faktor
            FROM
                tm_fccutting_item a
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                    AND b.id_company = c.id_company)
            LEFT JOIN tr_satuan f ON
                (f.i_satuan_code = a.i_satuan_code_konversi
                    AND a.id_company = f.id_company)
            LEFT JOIN (SELECT * FROM f_mutasi_stock_harian ($this->company,to_char(current_date, 'YYYYmm'),'9999-12-01','9999-12-31',TO_CHAR(current_date, 'yyyy-mm-01')::date,current_date,'i_bagian')) d ON 
                (d.id_company = a.id_company AND b.i_material = d.i_material)
            LEFT JOIN tr_material_konversi y ON
                (y.id_material = a.id_material
                    AND y.f_default = 't')
            WHERE
                a.id_document = '$id'
            ORDER BY a.id ASC
        ", FALSE);
    }

    /*----------  GET DATA EDIT DETAIL ITEM REFERENSI BISBISAN  ----------*/

    public function editdatadetailbisbisan($id)
    {
        return $this->db->query("WITH cte AS (
            SELECT
                c.id AS id_product_wip,
                a.n_quantity
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tr_product_base b ON
                (a.id_product = b.id)
            INNER JOIN tr_product_wip c ON
                (b.i_product_wip = c.i_product_wip
                    AND b.i_color_wip = c.i_color )
            WHERE
                a.id_forecast = (
                SELECT
                    id_referensi
                FROM
                    tm_fccutting
                WHERE
                    id = '$id')
            )
            SELECT
                c.i_material,
                initcap(c.e_material_name) AS e_material_name,
                d.n_quantity,
                b.n_bisbisan ,
                initcap(e.e_jenis_potong) AS e_jenis_potong,
                initcap(f.e_satuan_name) AS e_satuan_name,
                COALESCE (a.v_bisbisan / b.v_panjang_bis,
                0) AS pemakaian,
                COALESCE (sum((a.v_bisbisan / b.v_panjang_bis) * n_quantity),
                0) AS kebutuhan
            FROM
                tr_polacutting_new a
            INNER JOIN tr_material_bisbisan b ON
                (b.id = a.id_bisbisan)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN cte d ON
                (a.id_product_wip = d.id_product_wip)
            INNER JOIN tr_jenis_potong e ON
                (b.id_jenis_potong = e.id)
            INNER JOIN tr_satuan f ON
                (c.i_satuan_code = f.i_satuan_code
                    AND c.id_company = f.id_company)
            GROUP BY
                1,2,3,4,5,6,7
        ", FALSE);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode, $ibagian, $kodeold, $ibagianold)
    {
        $this->db->select('i_document');
        $this->db->from('tm_fccutting');
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
        $this->db->where('id_forecast', $id);
        $this->db->delete('tm_fccutting_item');
    }

    /*----------  UPDATE HEADER  ----------*/

    public function updateheader($id,$idocument,$ddocument,$ibagian,$idreferensi,$iperiode,$eremark)
    {
        $data = array(
            'id_company'       => $this->company,
            'i_document'       => $idocument,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_referensi'     => $idreferensi,
            'i_periode'        => $iperiode,
            'e_remark'         => $eremark,
            'd_update'         => current_datetime()
        );
        $this->db->where('id', $id);
        $this->db->update('tm_fccutting', $data);
    }

    /*----------  RUBAH STATUS  ----------*/

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    // public function changestatus($id, $istatus)
    // {
    //     if ($istatus == '6') {
    //         $data = array(
    //             'i_status'  => $istatus,
    //             'e_approve' => $this->username,
    //             'd_approve' => date('Y-m-d'),
    //         );
    //     } else {
    //         $data = array(
    //             'i_status'  => $istatus,
    //         );
    //     }
    //     $this->db->where('id', $id);
    //     $this->db->update('tm_fccutting', $data);
    // }

    public function changestatus($id,$istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_fccutting a
                inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
                where a.id = '$id'
                group by 1,2", FALSE)->row();
            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0 ) {
                    $data = array(
                        'i_status'  => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
                if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_fccutting');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_fccutting', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/
}
/* End of file Mmaster.php */