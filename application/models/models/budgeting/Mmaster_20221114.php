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
                tm_budgeting
            WHERE
                i_status = '6'
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
                a.i_document,
                to_char(d_document, 'DD Month YYYY') AS d_document,
                bulan(to_date(p.periode, 'YYYYmm')) || ' ' || substring(p.periode, 1, 4) AS periode,
                a.i_bagian,
                e_bagian_name,
                a.e_remark,
                e_status_name,
                label_color,
                (substring(to_char(d_document, 'YYYYMM'), 1, 4)) AS bulan,
                (substring(to_char(d_document, 'YYYYMM'), 5, 6)) AS tahun,
                a.i_status,
                l.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_budgeting a
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tr_bagian b ON 
                (b.i_bagian = a.i_bagian 
                    AND a.id_company = b.id_company)
            INNER JOIN tm_forecast_produksi p ON
                (p.id = a.id_referensi)
            LEFT JOIN public.tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (e.i_level = l.i_level)
            WHERE
                a.i_status <> '4'
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
            $i_bagian = $data['i_bagian'];
            
            $bulan    = $data['bulan'];
            $tahun    = $data['tahun'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $i_level  = $data['i_level'];
            $data     = '';


                $data .= "<a href=\"".base_url($folder.'/cform/export_excel/'.$i_bagian.'/'.$tahun.'/'.$bulan.'/'.$dfrom.'/'.$dto.'/'.$id)."\" title='Export'><i class='ti-download mr-3 fa-lg text-success'></i></a>";


            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye mr-3 fa-lg'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt  mr-3 fa-lg'></i></a>";
                }
            }

            if ($i_status == '2') {
                
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
                
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3 fa-lg'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('i_bagian');
        $datatables->hide('tahun');
        $datatables->hide('bulan');
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
                a.i_document,
                bulan(to_date(a.periode, 'YYYYmm')) || ' ' || substring(a.periode, 1, 4) AS periode,
                substring(a.periode, 1, 4) AS tahun,
                substring(a.periode, 5, 6) AS bulan,
                case when a.f_over_budget = TRUE then 'Ya' else 'Tidak' end as over_budget,
                c.e_bagian_name,
                e_remark_supplier,
                a.i_bagian,
                a.i_status,
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
                AND a.i_status = '6' 
                AND a.id_company = '$this->company' and a.id not in (select id_referensi from tm_budgeting where i_status IN ('6','1', '2', '3'))
            ", FALSE);

        $datatables->add('action', function ($data) {
            $id           = $data['id'];
            $i_menu       = $data['i_menu'];
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_bagian     = $data['i_bagian'];
            $i_status     = $data['i_status'];
            //$status       = $data['status_budgeting'];
            $tahun        = $data['tahun'];
            $bulan        = $data['bulan'];
            $data         = '';
            
            if (check_role($i_menu, 1)) {
                $data .= "<a href=\"#\" title='Tambah Budgeting' onclick='show(\"$folder/cform/prosesdata/$id/$dfrom/$dto/$tahun/$bulan\",\"#main\"); return false;'><i class='ti-new-window fa-lg'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_bagian');
        $datatables->hide('i_status');
        //$datatables->hide('status_budgeting');
        $datatables->hide('tahun');
        $datatables->hide('bulan');
        return $datatables->generate();
    }

    /*----------  GET DATA DETAIL REFERENSI  ----------*/

    public function datadetail($id)
    {
        return $this->db->query("
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
                sum(e.n_quantity) AS pemakaian,
                a.n_quantity * sum(e.n_quantity) AS kebutuhan
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tm_forecast_produksi b ON (b.id = a.id_forecast)
            INNER JOIN tr_product_base c ON (c.id = a.id_product)
            LEFT JOIN tr_product_wip d ON (d.i_product_wip = c.i_product_wip and c.i_color  = d.i_color AND c.id_company = d.id_company)
            LEFT JOIN tr_product_wip_item e ON (e.id_product_wip = d.id)
            LEFT JOIN tr_material f ON (f.id = e.id_material)
            LEFT JOIN tr_satuan g ON (g.i_satuan_code = f.i_satuan_code AND f.id_company = g.id_company)
            LEFT JOIN tr_color h ON (h.i_color = c.i_color AND c.id_company = h.id_company)
            WHERE b.id = '$id' and a.n_quantity > 0
            GROUP BY 1,2,3,4,5,6,7,8,9
            ORDER BY 1,6 */

            SELECT DISTINCT
                  c.id AS id_product_base,
                  c.i_product_base,
                  initcap(c.e_product_basename) AS e_product_basename,
                  a.n_quantity AS n_quantity,
                  f.id AS id_material,
                  f.i_material,
                  initcap(f.e_material_name) AS e_material_name,
                  initcap(e.bagian) AS bagian,
                  initcap(g.e_satuan_name) AS e_satuan_name,
                  trim(h.e_color_name) AS e_color_name,
                  sum(e.n_quantity) AS pemakaian,
                  a.n_quantity * sum(e.n_quantity) AS kebutuhan
              FROM
                  tm_forecast_produksi_item a
              INNER JOIN tm_forecast_produksi b ON (b.id = a.id_forecast)
              INNER JOIN tr_product_base c ON (c.id = a.id_product)
              LEFT JOIN tr_product_wip d ON (d.i_product_wip = c.i_product_wip and c.i_color  = d.i_color AND c.id_company = d.id_company)
              LEFT JOIN tr_product_wip_item e ON (e.id_product_wip = d.id)
              LEFT JOIN tr_material f ON (f.id = e.id_material)
              LEFT JOIN tr_satuan g ON (g.i_satuan_code = f.i_satuan_code AND f.id_company = g.id_company)
              LEFT JOIN tr_color h ON (h.i_color = c.i_color AND c.id_company = h.id_company)
              WHERE b.id = '$id' and a.n_quantity > 0
              GROUP BY 1,2,3,4,5,6,7,8,9,10
              ORDER by 1,6
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
    public function datadetaill($id,$tahun,$bulan)
    {
        $i_periode = $tahun.$bulan;
        $date_from = date('Y-m-d',strtotime($tahun.'-'.$bulan.'-01'));
        $date_to = date('Y-m-t',strtotime($tahun.'-'.$bulan));
        return $this->db->query("SELECT
                DISTINCT x.id_material,
                x.i_material,
                e_material_name,
                e_nama_group_barang,
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
                sum(kebutuhan) AS kebutuhan,
				sum(acc_pelengkap) as acc_pelengkap 
            FROM
                (
                SELECT
                    c.id AS id_product_base,
                    c.i_product_base,
                    initcap(c.e_product_basename) AS e_product_basename,
                    e_nama_group_barang,
                    a.n_quantity,
                    e.n_quantity AS pemakaian,
                    f.id AS id_material,
                    f.i_material,
                    initcap(f.e_material_name) AS e_material_name,
                    a.n_quantity * e.n_quantity AS view_kebutuhan,
                    case when f.i_kode_group_barang = 'GRB0004' then 0 else a.n_quantity * e.n_quantity end as kebutuhan,
                    case 
				          when f.i_kode_group_barang = 'GRB0004' then (greatest(a.n_quantity,a.n_fc_next ) - a.n_quantity_stock - coalesce(mp.sa_packing,0)) * e.n_quantity
				          /*when f.i_kode_group_barang = 'GRB0005' then (a.n_quantity + a.n_fc_next ) - (a.n_quantity_stock + a.n_quantity_wip +  a.n_quantity_unitjahit) */
                          /*Khusus Acc Packing , max(fc berjalan, do) + fc dist - Stok gudang jadi - Stok packing*/
				          else '0'
				    end as acc_pelengkap,
                    g.i_satuan_code,
                    initcap(g.e_satuan_name) AS e_satuan_name,
                    h.n_saldo_akhir AS saldoakhir,
                    a.id_company,
                    estimasi,
                    op_sisa
                FROM
                    tm_forecast_produksi_item a
                INNER JOIN tm_forecast_produksi b ON
                    (b.id = a.id_forecast)
                INNER JOIN tr_product_base c ON
                    (c.id = a.id_product)
                INNER JOIN tr_product_wip d ON
                    (d.i_product_wip = c.i_product_wip and c.i_color  = d.i_color 
                        AND c.id_company = d.id_company)
                INNER JOIN tr_product_wip_item e ON
                    (e.id_product_wip = d.id)
                LEFT JOIN tr_material f ON
                    (f.id = e.id_material)
                LEFT JOIN tr_satuan g ON
                    (g.i_satuan_code = f.i_satuan_code
                        AND f.id_company = g.id_company)
                LEFT JOIN tr_group_barang gb ON (
                    gb.i_kode_group_barang = f.i_kode_group_barang 
                        AND f.id_company = gb.id_company
                )
                LEFT JOIN (
                    select id_product_base, n_saldo_akhir as sa_packing from 
                    f_mutasi_packing('$this->id_company', '$i_periode', '9999-01-01', '9999-12-01', '$date_from', '$date_to', '')
                ) as mp on (a.id_product = mp.id_product_base)
                LEFT JOIN (
                    SELECT
                        *
                    FROM
                        f_mutasi_material ('$this->id_company','$i_periode',
                        '9999-12-01',
                        '9999-12-31',
                        '$date_from',
                        '$date_to','')) h ON
                    (h.id_company = a.id_company
                        AND f.id = h.id_material)
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
                    b.id = '$id' and a.n_quantity > 0 AND e.f_budgeting = 't'
            ) AS x
            LEFT JOIN tr_material_konversi y ON
                (y.id_material = x.id_material
                    AND y.f_default = 't')
            LEFT JOIN tr_satuan z ON
                (z.id_company = y.id_company
                    AND y.i_satuan_code_konversi = z.i_satuan_code)
            GROUP BY
                1,2,3,4,5,6,7,8,9,10,11,12
            ORDER BY
                e_nama_group_barang, i_material
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
            INNER JOIN tr_product_base b ON (a.id_product = b.id)
            INNER JOIN tr_product_wip c ON (b.i_product_wip = c.i_product_wip AND b.i_color_wip = c.i_color )
            WHERE a.id_forecast = '$id' and a.n_quantity > 0
            )
            SELECT
                c.i_material,
                initcap(c.e_material_name) AS e_material_name,
                sum(d.n_quantity) as n_quantity,
                b.n_bisbisan ,
                initcap(e.e_jenis_potong) AS e_jenis_potong,
                initcap(f.e_satuan_name) AS e_satuan_name,
                sum(COALESCE (a.v_bisbisan / b.v_panjang_bis,
                0)) AS pemakaian,
                COALESCE (sum((a.v_bisbisan / b.v_panjang_bis) * n_quantity),
                0) AS kebutuhan
            FROM
                tr_polacutting_new a
            INNER JOIN tr_material_bisbisan b ON (b.id = a.id_bisbisan)
            INNER JOIN tr_material c ON (c.id = a.id_material)
            INNER JOIN cte d ON (a.id_product_wip = d.id_product_wip)
            INNER JOIN tr_jenis_potong e ON (b.id_jenis_potong = e.id)
            INNER JOIN tr_satuan f ON (c.i_satuan_code = f.i_satuan_code AND c.id_company = f.id_company)
            GROUP BY
                1,2,4,5,6
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
                substring(i_document, 1, 3) AS kode
            FROM tm_budgeting
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BGT';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_budgeting
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
        $this->db->from('tm_budgeting');
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
        $this->db->from('tm_budgeting');
        return $this->db->get()->row()->id + 1;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $eremark)
    {
        $data = array(
            'id'               => $id,
            'id_company'       => $this->company,
            'i_document'       => $idocument,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_referensi'     => $idreferensi,
            'e_remark'         => $eremark,
            'i_status'         => 1,
        );
        $this->db->insert('tm_budgeting', $data);
    }

    /*----------  SIMPAN DATA ITEM BASE ----------*/

    public function insertdetailbase($id, $idreferensi, $id_product_base, $nilai_base, $id_material, $nilai_pemakaian, $nilai_kebutuhan)
    {
        if($nilai_pemakaian==''){
            $nilai_pemakaian = 0;
        }
        if($nilai_kebutuhan==''){
            $nilai_kebutuhan = 0;
        }
        if($id_material!=''){
            $data = array(
                'id_company'       => $this->company,
                'id_document'      => $id,
                'id_referensi'     => $idreferensi,
                'id_product_base'  => $id_product_base,
                'n_base'           => $nilai_base,
                'id_material'      => $id_material,
                'n_pemakaian'      => $nilai_pemakaian,
                'n_kebutuhan'      => $nilai_kebutuhan,
            );
            $this->db->insert('tm_budgeting_item_base', $data);
        }
    }

    /*----------  SIMPAN DATA ITEM MATERIAL ----------*/

    public function insertdetailmaterial($id, $idreferensi, $id_material_item, $nilai_kebutuhan_item, $nilai_mutasi, $nilai_budgeting, $i_satuan_konversi, $up, $ket, $nilai_estimasi,$nilai_op_sisa,$nilai_actual, $acc_pelengkap)
    {
        $data = array(
            'id_company'             => $this->company,
            'id_document'            => $id,
            'id_referensi'           => $idreferensi,
            'id_material'            => $id_material_item,
            'n_kebutuhan'            => $nilai_kebutuhan_item,
            'n_mutasi'               => $nilai_mutasi,
            'n_budgeting'            => $nilai_actual,
            'n_budgeting_sisa'       => $nilai_actual,
            'n_budgeting_perhitungan'=> $nilai_budgeting,
            'i_satuan_code_konversi' => $i_satuan_konversi,
            'persen_up'              => $up,
            'e_remark'               => $ket,
            'n_estimasi'             => $nilai_estimasi,
            'n_op_sisa'              => $nilai_op_sisa,
            'n_acc_pelengkap'        => $acc_pelengkap,
        );
        $this->db->insert('tm_budgeting_item_material', $data);
    }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/

    public function editheader($id)
    {
        return $this->db->query("SELECT
                a.id,
                a.i_bagian,
                e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                to_char(a.d_document, 'DD Month YYYY') AS ddocument,
                id_referensi,
                a.e_remark,
                a.i_status,
                substring(c.periode, 1,4) AS tahun,
                substring(c.periode, 5,2) AS bulan
            FROM
                tm_budgeting a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tm_forecast_produksi c ON
                (c.id = a.id_referensi)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM BASE ----------*/

    public function edititembase($id)
    {
        /* return $this->db->query("
            SELECT
                a.id_product_base,
                b.i_product_base,
                b.e_product_basename,
                a.n_base AS n_quantity,
                a.n_pemakaian AS pemakaian,
                a.id_material,
                c.i_material,
                c.e_material_name ,
                a.n_kebutuhan AS kebutuhan,
                d.e_satuan_name,
                e.e_color_name
            FROM
                tm_budgeting_item_base a
            INNER JOIN tr_product_base b ON
                (b.id = a.id_product_base)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN tr_satuan d ON
                (d.i_satuan_code = c.i_satuan_code
                    AND c.id_company = d.id_company)
            INNER JOIN tr_color e ON
                (e.i_color = b.i_color
                    AND b.id_company = e.id_company)
            WHERE
                a.id_document = '$id'
            ORDER BY
                2,
                7
        ", FALSE); */
        
        return $this->db->query("SELECT
                DISTINCT c.id AS id_product_base,
                c.i_product_base,
                initcap(c.e_product_basename) AS e_product_basename,
                a.n_quantity AS n_quantity,
                f.id AS id_material,
                f.i_material,
                initcap(f.e_material_name) AS e_material_name,
                initcap(g.e_satuan_name) AS e_satuan_name,
                trim(h.e_color_name) AS e_color_name,
                sum(e.n_quantity) AS pemakaian,
                a.n_quantity * sum(e.n_quantity) AS kebutuhan
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tm_forecast_produksi b ON
                (b.id = a.id_forecast)
            INNER JOIN tr_product_base c ON
                (c.id = a.id_product)
            LEFT JOIN tr_product_wip d ON
                (d.i_product_wip = c.i_product_wip and c.i_color  = d.i_color 
                    AND c.id_company = d.id_company)
            LEFT JOIN tr_product_wip_item e ON
                (e.id_product_wip = d.id)
            LEFT JOIN tr_material f ON
                (f.id = e.id_material)
            LEFT JOIN tr_satuan g ON
                (g.i_satuan_code = f.i_satuan_code
                    AND f.id_company = g.id_company)
            LEFT JOIN tr_color h ON
                (h.i_color = c.i_color
                    AND c.id_company = h.id_company)
            WHERE
                b.id = (
                SELECT
                    id_referensi
                FROM
                    tm_budgeting
                WHERE
                    id = '$id') and a.n_quantity > 0
            GROUP BY
                1,2,3,4,5,6,7,8,9
            ORDER BY
                1,
                6
        ", FALSE);
    }

 public function export_data($id)
    {
        /* return $this->db->query("
            SELECT
                a.id_product_base,
                b.i_product_base,
                b.e_product_basename,
                a.n_base AS n_quantity,
                a.n_pemakaian AS pemakaian,
                a.id_material,
                c.i_material,
                c.e_material_name ,
                a.n_kebutuhan AS kebutuhan,
                d.e_satuan_name,
                e.e_color_name
            FROM
                tm_budgeting_item_base a
            INNER JOIN tr_product_base b ON
                (b.id = a.id_product_base)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN tr_satuan d ON
                (d.i_satuan_code = c.i_satuan_code
                    AND c.id_company = d.id_company)
            INNER JOIN tr_color e ON
                (e.i_color = b.i_color
                    AND b.id_company = e.id_company)
            WHERE
                a.id_document = '$id'
            ORDER BY
                2,
                7
        ", FALSE); */
        
        return $this->db->query("
            SELECT
                c.id AS id_product_base,
                c.i_product_base,
                initcap(c.e_product_basename) AS e_product_basename,
                a.n_quantity AS n_quantity,
                f.id AS id_material,
                f.i_material,
                initcap(f.e_material_name) AS e_material_name,
                initcap(g.e_satuan_name) AS e_satuan_name,
                trim(h.e_color_name) AS e_color_name,
                e.n_quantity AS pemakaian,
                a.n_quantity * e.n_quantity AS kebutuhan,
                j.e_satuan_name as satuan_konversi, 
                i.e_operator , 
                n_faktor
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tm_forecast_produksi b on (b.id = a.id_forecast)
            INNER JOIN tr_product_base c on (c.id = a.id_product)
            LEFT JOIN tr_product_wip d on (d.i_product_wip = c.i_product_wip and c.i_color  = d.i_color AND c.id_company = d.id_company)
            LEFT JOIN tr_product_wip_item e on (e.id_product_wip = d.id)
            LEFT JOIN tr_material f on (f.id = e.id_material)
            LEFT JOIN tr_satuan g on (g.i_satuan_code = f.i_satuan_code AND f.id_company = g.id_company)
            LEFT JOIN tr_color h on (h.i_color = c.i_color AND c.id_company = h.id_company)
            left join tr_material_konversi i on (f.id = i.id_material and f_default = true)
            LEFT JOIN tr_satuan j on (i.i_satuan_code_konversi = j.i_satuan_code AND i.id_company = j.id_company)
            where b.id = ( select id_referensi from tm_budgeting where id = '$id') and a.n_quantity > 0 
            ORDER by 1, 6

        ", FALSE);
    }
    /*----------  GET VIEW, EDIT & APPROVE ITEM MATERIAL ----------*/

    public function edititemmaterial($id)
    {
        // return $this->db->query("SELECT
        //         a.id_material,
        //         b.i_material,
        //         e_material_name,
        //         c.e_satuan_name,
        //         a.i_satuan_code_konversi AS i_satuan_konversi,
        //         f.e_satuan_name AS e_satuan_konversi,
        //         a.n_mutasi AS mutasi,
        //         a.n_kebutuhan AS kebutuhan,
        //         a.n_budgeting,
        //         a.e_remark,
        //         a.persen_up,
        //         a.n_estimasi AS estimasi,
        //         a.n_op_sisa AS op_sisa,
        //         y.e_operator,
        //         y.n_faktor
        //     FROM
        //         tm_budgeting_item_material a
        //     INNER JOIN tr_material b ON
        //         (b.id = a.id_material)
        //     INNER JOIN tr_satuan c ON
        //         (c.i_satuan_code = b.i_satuan_code
        //             AND b.id_company = c.id_company)
        //     LEFT JOIN tr_satuan f ON
        //         (f.i_satuan_code = a.i_satuan_code_konversi
        //             AND a.id_company = f.id_company)
        //     LEFT JOIN (SELECT * FROM f_mutasi_stock_harian ($this->company,to_char(current_date, 'YYYYmm'),'9999-12-01','9999-12-31',TO_CHAR(current_date, 'yyyy-mm-01')::date,current_date,'i_bagian')) d ON 
        //         (d.id_company = a.id_company AND b.i_material = d.i_material)
        //     LEFT JOIN tr_material_konversi y ON
        //         (y.id_material = a.id_material
        //             AND y.f_default = 't')
        //     WHERE
        //         a.id_document = '$id'
        //     ORDER BY b.i_material ASC
        // ", FALSE);

        return $this->db->query("SELECT
                a.id_material,
                b.i_material,
                e_material_name,
                e_nama_group_barang,
                c.e_satuan_name,
                a.i_satuan_code_konversi AS i_satuan_konversi,
                f.e_satuan_name AS e_satuan_konversi,
                a.n_mutasi AS mutasi,
                a.n_kebutuhan AS kebutuhan,
                a.n_budgeting,
                a.n_budgeting_perhitungan,
                a.e_remark,
                a.persen_up,
                a.n_estimasi AS estimasi,
                a.n_op_sisa AS op_sisa,
                y.e_operator,
                y.n_faktor,
                a.n_acc_pelengkap
            FROM
                tm_budgeting_item_material a
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_group_barang gb ON (
                gb.i_kode_group_barang = b.i_kode_group_barang AND b.id_company = gb.id_company
            )
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                    AND b.id_company = c.id_company)
            LEFT JOIN tr_satuan f ON
                (f.i_satuan_code = a.i_satuan_code_konversi
                    AND a.id_company = f.id_company)
            LEFT JOIN tr_material_konversi y ON
                (y.id_material = a.id_material
                    AND y.f_default = 't')
            WHERE
                a.id_document = '$id'
            ORDER BY e_nama_group_barang, b.i_material ASC
        ", FALSE);
    }

    public function edititemmaterial_export($id)
    {

        return $this->db->query("
            SELECT
                  a.id_material,
                  b.i_material,
                  e_material_name,
                  c.e_satuan_name,
                  a.i_satuan_code_konversi AS i_satuan_konversi,
                  f.e_satuan_name AS e_satuan_konversi,
                  a.n_mutasi AS mutasi,
                  a.n_kebutuhan AS kebutuhan,
                  a.n_budgeting,
                  a.n_budgeting_perhitungan,
                  a.e_remark,
                  a.persen_up,
                  a.n_estimasi AS estimasi,
                  a.n_op_sisa AS op_sisa,
                  db.e_nama_group_barang , kb.e_nama_kelompok , skb.e_type_name ,
                  a.n_acc_pelengkap
            FROM
              tm_budgeting_item_material a
            INNER JOIN tr_material b on (b.id = a.id_material)
            INNER JOIN tr_satuan c on (c.i_satuan_code = b.i_satuan_code AND b.id_company = c.id_company)
            inner join tr_group_barang db on (b.i_kode_group_barang = db.i_kode_group_barang and b.id_company = db.id_company)
            inner join tr_kelompok_barang kb on (b.i_kode_kelompok = kb.i_kode_kelompok and b.id_company = kb.id_company)
            inner join tr_item_type skb on (b.i_type_code = skb.i_type_code and b.id_company = skb.id_company)
            LEFT JOIN tr_satuan f on (f.i_satuan_code = a.i_satuan_code_konversi AND a.id_company = f.id_company)
            LEFT JOIN tr_material_konversi y on (y.id_material = a.id_material AND y.f_default = 't')
            WHERE
              a.id_document = '$id'
            ORDER BY b.i_material asc
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
                    tm_budgeting
                WHERE
                    id = '$id') and a.n_quantity > 0
            )
            SELECT
                c.i_material,
                initcap(c.e_material_name) AS e_material_name,
                sum(d.n_quantity) as n_quantity,
                b.n_bisbisan ,
                initcap(e.e_jenis_potong) AS e_jenis_potong,
                initcap(f.e_satuan_name) AS e_satuan_name,
                sum(COALESCE (a.v_bisbisan / b.v_panjang_bis,
                0)) AS pemakaian,
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
                1,2,4,5,6
        ", FALSE);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode, $ibagian, $kodeold, $ibagianold)
    {
        $this->db->select('i_document');
        $this->db->from('tm_budgeting');
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
        $this->db->delete('tm_budgeting_item_base');
        $this->db->where('id_document', $id);
        $this->db->delete('tm_budgeting_item_material');
    }

    /*----------  UPDATE HEADER  ----------*/

    public function updateheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $eremark)
    {
        $data = array(
            'id_company'       => $this->company,
            'i_document'       => $idocument,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_referensi'     => $idreferensi,
            'e_remark'         => $eremark,
            'i_status'         => '1',
            'd_update'         => current_datetime()
        );
        $this->db->where('id', $id);
        $this->db->update('tm_budgeting', $data);
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
    //     $this->db->update('tm_budgeting', $data);
    // }

    public function changestatus($id,$istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_budgeting a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_budgeting');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_budgeting', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/
}
/* End of file Mmaster.php */