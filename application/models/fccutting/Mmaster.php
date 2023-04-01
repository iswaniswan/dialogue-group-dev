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
        $datatables->query("SELECT
                DISTINCT 0 AS NO,
                a.id,
                i_document,
                to_char(d_document, 'DD FMMonth YYYY') AS d_document,
                to_char(to_date(i_periode,'yyyymm'), 'FMMonth YYYY') AS i_periode,
                i_periode periode,
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
            $periode  = $data['periode'];
            $data     = '';

            if (check_role($i_menu, 6)) {
                $data     .= "<a href=\"" . base_url($folder . '/cform/export_excel/' . $id . '/' . $dfrom . '/' . $dto . '/' . $periode) . "\" title='Export'><i class='ti-download text-success fa-lg mr-3'></i></a>";
            }

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$periode\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$periode\",\"#main\"); return false;'><i class='ti-pencil-alt  fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && ($i_status == '2')) {
                if (($i_level == $this->session->userdata('i_level')) || $this->session->userdata('i_level') == 1) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$periode\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg mr-3'></i></a>";
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
        $datatables->hide('periode');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    /*----------  DAFTAR DATA REFERENSI  ----------*/

    public function datareferensi($folder, $i_menu, $dfrom, $dto)
    {
        $datatables = new Datatables(new CodeigniterAdapter);

        $sql = "WITH CTE AS (SELECT
                    0 AS NO, a.id, a.i_document, a.d_document,
                    bulan(to_date(a.i_periode, 'YYYYmm')) || ' ' || substring(a.i_periode, 1, 4) AS i_periode,
                    substring(a.i_periode, 1, 4) AS tahun, substring(a.i_periode, 5, 6) AS bulan,
                    c.e_bagian_name, b.name as company_name, e_remark, a.i_bagian, '$i_menu' AS i_menu, '$folder' AS folder,
                    '$dfrom' AS dfrom, '$dto' AS dto, ROW_NUMBER() OVER (ORDER BY a.id) AS i
                FROM
                    tm_schedule_jahit a
                INNER JOIN tr_bagian c ON(
                    a.i_bagian = c.i_bagian AND a.id_company = c.id_company
                )
                inner join public.company b ON (b.id = a.id_company)
                WHERE
                    to_date(a.i_periode, 'YYYYmm') BETWEEN to_date('$dfrom', '01-mm-yyyy') AND to_date('$dto', '01-mm-yyyy')
                    AND a.i_status = '6' AND (a.id_company = '$this->company' OR a.id_company_referensi = '$this->company') 
                    AND a.id NOT IN (SELECT unnest(id_referensi) FROm tm_fccutting WHERE i_status IN ('1','2','3','6'))
                )
                SELECT id, i, i_document, d_document, i_periode, tahun, bulan, e_bagian_name, company_name, e_remark, i_bagian,
                i_menu, dfrom, dto, folder,
                (select count(i) as jml from CTE) As jml from CTE";

        // var_dump($sql); die();

        $datatables->query($sql, FALSE);

        $datatables->add('action', function ($data) {
            $id           = $data['id'];
            $i            = $data['i'];
            $jml          = $data['jml'];
            $i_menu       = $data['i_menu'];
            /* $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_bagian     = $data['i_bagian'];*/
            $tahun        = $data['tahun'];
            $bulan        = $data['bulan'];
            $i_periode    = $data['tahun'] . $data['bulan'];
            $data         = '';
            if (check_role($i_menu, 1)) {
                /* $data .= "<a href=\"#\" title='Tambah Budgeting' onclick='show(\"$folder/cform/prosesdata/$id/$dfrom/$dto/$tahun/$bulan\",\"#main\"); return false;'><i class='ti-new-window'></i></a>"; */
                $data .= "<label class='custom-control custom-checkbox'> 
                <input type='checkbox' id='chk$i' name='chk$i' class='custom-control-input'>
                <span class='custom-control-indicator'></span>
                <span class='custom-control-description'></span>
                <input id='id$i' name='id$i' value='$id' type='hidden'>
                <input id='tahun$i' name='tahun$i' value='$tahun' type='hidden'>
                <input id='bulan$i' name='bulan$i' value='$bulan' type='hidden'>
                <input id='i_periode$i' name='i_periode$i' value='$i_periode' type='hidden'>
                <input id='jml' name='jml' value='$jml' type='hidden'>";
            }
            return $data;
        });
        $datatables->hide('i');
        $datatables->hide('jml');
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

    public function datadetail($tahun, $bulan, $id_schedule)
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
        /* return $this->db->query("
            SELECT DISTINCT
                d.id AS id_product_base,
                d.i_product_wip,
                initcap(d.e_product_wipname) AS e_product_basename,
                a.n_quantity_fc AS n_quantity,
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
            where b.id = '$id' and e.v_gelar > 0
        ", FALSE); */

        /* return $this->db->query("SELECT
                DISTINCT d.id AS id_product_base,
                d.i_product_wip,
                initcap(d.e_product_wipname) AS e_product_basename,
                trim(h.e_color_name) AS e_color_name,
                sum(a.n_quantity - persen_up) AS n_quantity
            FROM
                tm_forecast_produksi_item a
            INNER JOIN tm_forecast_produksi b ON
                (b.id = a.id_forecast)
            INNER JOIN tr_product_base c ON
                (c.id = a.id_product)
            LEFT JOIN tr_product_wip d ON
                (d.i_product_wip = c.i_product_wip
                    AND c.id_company = d.id_company AND d.i_color = c.i_color)
            LEFT JOIN tr_color h ON
                (h.i_color = d.i_color
                    AND c.id_company = h.id_company)
            WHERE
                b.id = '$id'
            GROUP BY 1,2,3,4
            ORDER BY 2,3,5
        ", FALSE); */


        /************ BACKUP 2022-07-28 ***********/
        /* $dfrom = date('Y-m-01');
        $dto   = date('Y-m-t', strtotime($dfrom));
        $periode = date('Ym');
        return $this->db->query("SELECT DISTINCT
                a.id_forecast,
                a.id_product,
                d.id AS id_product_wip,
                d.i_product_wip,
                d.e_product_wipname,
                c.e_color_name,
                a.n_quantity - persen_up AS fc_produksi,
                0 AS schedule_jahit,
                0 AS bahan_baku,
                COALESCE (f.saldo_akhir,
                0) AS n_stock_pengadaan,
                COALESCE (g.n_saldo_awal,
                0) AS n_stock_pengesetan,
                0 AS pendingan_bulan_sebelumnya
            FROM
                tm_forecast_produksi_item a
            LEFT JOIN tr_product_base b ON
                (b.id = a.id_product)
            LEFT JOIN tr_product_wip d ON
                (d.i_product_wip = b.i_product_wip
                    AND b.i_color = d.i_color
                    AND d.id_company = b.id_company)
            LEFT JOIN tr_color c ON
                (c.i_color = d.i_color
                    AND d.id_company = c.id_company)
            LEFT JOIN f_mutasi_saldoawal_pengadaan_new ('$this->id_company',
            '$periode',
            '9999-01-01',
            '9999-01-31',
            '$dfrom',
            '$dto',
            '') f ON
            (f.id_product_base = a.id_product
                AND a.id_company = f.id_company)
            LEFT JOIN tm_mutasi_saldoawal_base_pengesetan g ON
            (g.i_product_wip = b.i_product_wip
                AND b.i_color = g.i_color
                AND b.id_company = g.id_company)
            WHERE
            a.id_company = '$this->id_company'
            AND a.id_forecast = '$id'
            ORDER BY
            d.i_product_wip ASC
        ", FALSE); */

        /* $dfrom = date('Y-m-01');
        $dto   = date('Y-m-t', strtotime($dfrom));
        $periode = date('Ym');
        return $this->db->query("SELECT DISTINCT a.id_forecast, a.id_product, c.id AS id_product_wip, c.i_product_wip, c.e_product_wipname, d.e_color_name, 
            COALESCE (n_quantity_schedule_jahit,0) n_schedule_jahit, COALESCE (n_quantity_stb_pengadaan, 0) n_stb_pengadaan, COALESCE (n_quantity_schedule_jahit,0) - COALESCE (n_quantity_stb_pengadaan, 0) n_sisa_schedule_berjalan, a.n_quantity - persen_up AS fc_produksi,
            COALESCE (g.n_saldo_akhir,0) n_stock_pengadaan, COALESCE (h.n_saldo_akhir,0) n_stock_pengesetan
            FROM tm_forecast_produksi_item a
            INNER JOIN tr_product_base b ON (b.id = a.id_product)
            INNER JOIN tr_product_wip c ON (c.i_product_wip = b.i_product_wip AND b.i_color = c.i_color AND c.id_company = b.id_company)
            INNER JOIN tr_color d ON (d.i_color = c.i_color AND c.id_company = d.id_company)
            LEFT JOIN (
                SELECT a.id_product_wip, sum(n_quantity) n_quantity_schedule_jahit 
                FROM tm_schedule_jahit_item_new a
                INNER JOIN tm_schedule_jahit b ON (b.id = a.id_document)
                WHERE b.i_status = '6' AND to_char(b.d_document,'YYYYMM') = '$periode' 
                GROUP BY 1
            ) e ON (e.id_product_wip = c.id)
            LEFT JOIN (
                SELECT id_product_wip, sum(n_quantity_product_wip) n_quantity_stb_pengadaan  
                FROM tm_keluar_pengadaan_item_new a
                INNER JOIN tm_keluar_pengadaan b ON (b.id = a.id_keluar_pengadaan)
                WHERE b.i_status = '6' AND to_char(b.d_keluar_pengadaan,'YYYYMM') = '$periode'
                GROUP BY 1
            ) f ON (f.id_product_wip = c.id)
            LEFT JOIN (
                SELECT id_product_wip, n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengadaan_newbie('$this->id_company', '$periode', '9999-01-01', '9999-01-31', '$dfrom', '$dto', '')
            ) g ON (g.id_product_wip = c.id)
            LEFT JOIN (
                SELECT id_product_wip, max(n_saldo_akhir) n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengesettan('$this->id_company', '$periode', '9999-01-01', '9999-01-31', '$dfrom', '$dto', 'PST01') 
                GROUP BY 1
            ) h ON (h.id_product_wip = c.id)
            WHERE
                a.id_company = '$this->id_company'
                AND a.id_forecast = '$id'
            ORDER BY
                c.i_product_wip ASC
        "); */

        /** Backup 2022-08-31 */
        /* $periode_schedule = $tahun . '-' . $bulan;
        $periode_data = date('Y-m', strtotime('-1 month', strtotime($periode_schedule)));
        $dfrom = $periode_data . '-01';
        $dto = date('Y-m-t', strtotime($dfrom));
        $periode = date('Ym', strtotime($periode_data));
        $periode_schedule = date('Ym', strtotime($periode_schedule));
        return $this->db->query("SELECT a.id_product_wip, b.i_product_wip, b.e_product_wipname, c.e_color_name,
                COALESCE (n_quantity_stb_pengadaan, 0) n_stb_pengadaan,
                COALESCE (g.n_saldo_akhir,0) n_stock_pengadaan, 
                COALESCE (h.n_saldo_akhir,0) n_stock_pengesetan,
                sum(COALESCE (e.n_quantity,0)) - COALESCE (n_quantity_stb_pengadaan, 0) n_sisa_schedule_berjalan,	
                sum(COALESCE (e.n_quantity,0)) n_schedule_jahit, 
                sum(a.n_quantity) fc_produksi
            FROM tm_schedule_jahit_item_new a
            INNER JOIN tm_schedule_jahit ab ON (ab.id = a.id_document)
            INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)
            INNER JOIN tr_color c ON (
                c.i_color = b.i_color AND b.id_company = c.id_company
            )
            LEFT JOIN (
                SELECT a.id_product_wip, sum(n_quantity) n_quantity 
                FROM tm_schedule_jahit_item_new a
                INNER JOIN tm_schedule_jahit b ON (b.id = a.id_document)
                WHERE b.i_status = '6' AND b.i_periode = '$periode' 
                GROUP BY 1
            ) e ON (e.id_product_wip = a.id_product_wip)
            LEFT JOIN (
                SELECT id_product_wip, sum(n_quantity_product_wip) n_quantity_stb_pengadaan  
                FROM tm_keluar_pengadaan_item_new a
                INNER JOIN tm_keluar_pengadaan b ON (b.id = a.id_keluar_pengadaan)
                WHERE b.i_status = '6' AND to_char(b.d_keluar_pengadaan,'YYYYMM') = '$periode'
                GROUP BY 1
            ) f ON (f.id_product_wip = a.id_product_wip)
            LEFT JOIN (
                SELECT id_product_wip, n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengadaan_newbie('$this->id_company', '$periode', '9999-01-01', '9999-01-31', '$dfrom', '$dto', '')
            ) g ON (g.id_product_wip = a.id_product_wip)
            LEFT JOIN (
                SELECT a.id_product_wip, min(CEIL(n_saldo_akhir/n_qty_penyusun)) n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengesettan('$this->id_company', '$periode', '9999-01-01', '9999-01-31', '$dfrom', '$dto', 'PST01') a
                INNER JOIN tm_panel_item b ON (b.id  = a.id_panel_item)
                GROUP BY 1
            ) h ON (h.id_product_wip = a.id_product_wip)
            WHERE ab.id_company = '$this->id_company'
                AND ab.i_periode = '$periode_schedule'
                AND ab.id IN ($id_schedule)
            GROUP BY 1,2,3,4,5,6,7
            ORDER BY b.i_product_wip ASC
        "); */


        $periode = $tahun . '-' . $bulan;
        $dfrom = $periode . '-01';
        $dto = date('Y-m-t', strtotime($dfrom));
        $periode = date('Ym', strtotime($periode));
        $periode_data = date('Y-m', strtotime('-1 month', strtotime($periode)));

        $i_periode_now = date('Ym');
        /*$dfrom = $periode_data . '-01';
        $dto = date('Y-m-t', strtotime($dfrom));
        $periode = date('Ym', strtotime($periode_data));
        $periode_schedule = date('Ym', strtotime($periode_schedule)); */
        return $this->db->query("SELECT DISTINCT a.id_product_wip, b.i_product_wip, b.e_product_wipname, c.e_color_name, j.id id_material, j.i_material, j.i_material||' - '||j.e_material_name material,
                COALESCE (g.n_saldo_akhir,0) n_stock_pengadaan, COALESCE (h.n_saldo_akhir,0) n_stock_pengesetan,
                COALESCE(i.v_set,0) v_set, COALESCE(fc.n_quantity,0) n_quantity_dibudgetkan, COALESCE (e.n_quantity,0) n_schedule_jahit
            FROM tm_schedule_jahit_item_new a
            INNER JOIN tm_schedule_jahit ab ON (ab.id = a.id_document)
            INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)
            INNER JOIN tr_color c ON (
                c.i_color = b.i_color AND b.id_company = c.id_company
            )
            LEFT JOIN (
                SELECT a.id_product_wip, sum(n_quantity) n_quantity 
                FROM tm_schedule_jahit_item_new a
                INNER JOIN tm_schedule_jahit b ON (b.id = a.id_document)
                WHERE b.i_status = '6' AND b.i_periode = '$periode' and id_document in ($id_schedule)
                GROUP BY 1
            ) e ON (e.id_product_wip = a.id_product_wip)
            LEFT JOIN (
                SELECT id_product_wip, n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengadaan_newbie('$this->id_company', '$i_periode_now', '9999-01-01', '9999-01-31', '$dfrom', '$dto', '')
            ) g ON (g.id_product_wip = a.id_product_wip)
            LEFT JOIN (
                SELECT a.id_product_wip, min(CEIL(n_saldo_akhir/NULLIF(n_qty_penyusun,0))) n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengesettan('$this->id_company', '$i_periode_now', '9999-01-01', '9999-01-31', '$dfrom', '$dto', 'PST01') a
                INNER JOIN tm_panel_item b ON (b.id  = a.id_panel_item)
                GROUP BY 1
            ) h ON (h.id_product_wip = a.id_product_wip)
            LEFT JOIN tr_polacutting_new i ON (
                i.id_product_wip = a.id_product_wip AND i.f_kain_utama = 't' AND i.f_marker_utama = 't'
            )
            LEFT JOIN tr_material j ON (j.id = i.id_material)
            LEFT JOIN (
                SELECT id_forecast, c.id id_product_wip, n_quantity FROM tm_forecast_produksi_item a
                INNER JOIN tr_product_base b ON (b.id = a.id_product)
                INNER JOIN tr_product_wip c ON (c.i_product_wip = b.i_product_wip AND b.i_color = c.i_color AND c.id_company = b.id_company)
            ) fc ON (fc.id_forecast = ab.id_referensi AND a.id_product_wip = fc.id_product_wip)
            WHERE (ab.id_company = '$this->id_company' OR ab.id_company_referensi = '$this->id_company')
                AND ab.i_periode = '$periode'
                AND ab.id IN ($id_schedule)
            /* GROUP BY 1,2,3,4,5,6,7,8,9,10,11 */
            ORDER BY b.i_product_wip ASC
        ");
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
        /* return $this->db->query("SELECT
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
        ", FALSE); */
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
            WHERE a.f_marker_utama = 't'
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
            $kode = 'PC';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_fccutting
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND i_document ILIKE '%$kode%'
                /* AND substring(i_document, 4, 2) = substring('$thbl',1,2) */
                AND to_char (d_document, 'yymm') = '$thbl'
            ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
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

    public function insertheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $iperiode, $eremark)
    {
        /* var_dump($idreferensi);*/
        $idreferensi = "{" . $idreferensi . "}";
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

    public function insertdetailbase($id_forecast, $id_product_wip, $n_fc_perhitungan, $n_schedule_jahit, $n_bahan_baku, $n_sisa_schedule, $n_stock_pengadaan, $n_stock_pengesetan, $n_pendingan_permintaan_cutting, $n_kondisi_stock, $n_permintaan_cutting, $n_up_cutting, $n_fc_cutting, $e_remark)
    {
        $data = array(
            'id_company' => $this->id_company,
            'id_forecast' => $id_forecast,
            'id_product_wip' => $id_product_wip,
            'n_fc_perhitungan' => (int)$n_fc_perhitungan,
            'n_schedule_jahit' => (int)$n_schedule_jahit,
            'n_bahan_baku' => (int)$n_bahan_baku,
            'n_sisa_schedule' => (int)$n_sisa_schedule,
            'n_stock_pengadaan' => (int)$n_stock_pengadaan,
            'n_stock_pengesetan' => (int)$n_stock_pengesetan,
            'n_pendingan_permintaan_cutting' => (int)$n_pendingan_permintaan_cutting,
            'n_kondisi_stock' => (int)$n_kondisi_stock,
            'n_permintaan_cutting' => (int)$n_permintaan_cutting,
            'n_up_cutting' => (int)$n_up_cutting,
            'n_fc_cutting' => (int)$n_fc_cutting,
            'e_remark' => $e_remark,
        );
        $this->db->insert('tm_fccutting_item_new', $data);
    }

    /*----------  SIMPAN DATA ITEM ----------*/

    public function insert_detail(
        $id_forecast,
        $id_product_wip,
        $n_sisa_schedule_berjalan,
        $n_schedule_jahit,
        $n_stb_pengadaan,
        $n_stock_pengadaan,
        $n_stock_pengesetan,
        $n_sisa_permintaan_cutting,
        $n_kondisi_stock,
        $n_fc_produksi_perhitungan,
        $n_up_cutting,
        $n_fc_cutting,
        $e_remark,
        $v_set = 0,
        $n_jumlah_gelar = 0,
        $id_material = null,
        $n_fc_yang_dibutgetkan = 0,
        $n_total_qty_kain_utama = 0
    ) {
        $data = array(
            'id_company' => $this->id_company,
            'id_forecast' => $id_forecast,
            'id_product_wip' => $id_product_wip,
            'n_sisa_schedule_berjalan' => $n_sisa_schedule_berjalan,
            'n_schedule_jahit' => $n_schedule_jahit,
            'n_stb_pengadaan' => $n_stb_pengadaan,
            'n_stock_pengadaan' => $n_stock_pengadaan,
            'n_stock_pengesetan' => $n_stock_pengesetan,
            'n_sisa_permintaan_cutting' => $n_sisa_permintaan_cutting,
            'n_kondisi_stock' => $n_kondisi_stock,
            'n_fc_produksi_perhitungan' => $n_fc_produksi_perhitungan,
            'n_up_cutting' => $n_up_cutting,
            'n_fc_cutting' => $n_fc_cutting,
            'v_set' => $v_set,
            'n_jumlah_gelar' => $n_jumlah_gelar,
            'e_remark' => $e_remark,
            'id_material' => $id_material,
            'n_fc_produksi_dibadgetkan' => $n_fc_yang_dibutgetkan,
            'n_qty_kain_utama' => $n_total_qty_kain_utama,
        );
        $this->db->insert('tm_fccutting_detail', $data);
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
    //     $this->db->insert('tm_fccutting_item_new', $data);
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

    public function edititembase($id, $iperiode)
    {

        $dfrom = date('Y-m-01');
        $dto   = date('Y-m-t', strtotime($dfrom));
        $periode = date('Ym');
        /** Backup 2022-03-28 */
        // return $this->db->query("SELECT DISTINCT a.id,
        //         a.id_product_wip AS id_product_base,
        //         d.i_product_wip,
        //         initcap(d.e_product_wipname) AS e_product_basename,
        //         a.n_quantity_fc AS n_quantity,
        //         n_fc_perhitungan,
        //         n_stock_pengesetan,
        //         n_perkalian,
        //         /* a.id_material, f.i_material, initcap(f.e_material_name) AS e_material_name, initcap(g.e_satuan_name) AS e_satuan_name, */
        //         trim(h.e_color_name) AS e_color_name,
        //         /* a.v_gelar , a.v_set , a.n_quantity_wip / a.v_set as jml_gelar, (a.n_quantity_wip/a.v_set) * a.v_gelar as p_kain, */
        //         a.e_remark,
        //         e_brand_name AS brand,
        //         e_style_name AS STYLE,
        //         COALESCE (sum(j.saldo_akhir),
        //         0) AS n_quantity_pengadaan
        //     FROM
        //         tm_fccutting_item_new a
        //     LEFT JOIN tr_product_wip d on (a.id_product_wip = d.id)
        //     left join tr_product_base i on (i.i_product_wip = d.i_product_wip and d.id_company = i.id_company AND d.i_color = i.i_color)
        //     /* left join tr_class_product b on (b.id_class_product = d.id) */
        //     left join tr_item_type e on (d.i_type_code = e.i_type_code and e.id_company = d.id_company)
        //     left join tr_brand f on (f.i_brand = d.i_brand and f.id_company = d.id_company)
        //     left join tr_style g on (g.i_style = d.i_style and g.id_company = d.id_company)
        //     /* LEFT JOIN tr_material f on (f.id = a.id_material)
        //     LEFT JOIN tr_satuan g on (g.i_satuan_code = f.i_satuan_code AND f.id_company = g.id_company) */
        //     LEFT JOIN tr_color h on (h.i_color = d.i_color AND d.id_company = h.id_company)
        //     LEFT JOIN f_mutasi_saldoawal_pengadaan_new ('$this->id_company','$periode','9999-01-01','9999-01-31','$dfrom','$dto','') j ON (j.id_product_base =i.id AND i.id_company = j.id_company) 
        //     where a.id_forecast = '$id'
        //     GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12
        //     order by d.i_product_wip
        // ", FALSE);
        /* return $this->db->query("SELECT
                a.*,
                b.i_product_wip,
                b.e_product_wipname,
                f.e_brand_name,
                g.e_style_name,
                h.e_color_name 
            FROM
                tm_fccutting_item_new a
            LEFT JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            LEFT JOIN tr_brand f ON
                (f.i_brand = b.i_brand
                    AND f.id_company = b.id_company)
            LEFT JOIN tr_style g ON
                (g.i_style = b.i_style
                    AND g.id_company = b.id_company)
            LEFT JOIN tr_color h ON
                (h.i_color = b.i_color
                    AND b.id_company = h.id_company)
            WHERE
                a.id_forecast = '$id'
            ORDER BY
                b.i_product_wip
        ", FALSE); */
        return $this->db->query("SELECT
                a.*,
                b.i_product_wip,
                b.e_product_wipname,
                f.e_brand_name,
                g.e_style_name,
                h.e_color_name,
                i_material,
                i_material||' - '||e_material_name material
            FROM
                tm_fccutting_detail a
            LEFT JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            LEFT JOIN tr_brand f ON
                (f.i_brand = b.i_brand
                    AND f.id_company = b.id_company)
            LEFT JOIN tr_style g ON
                (g.i_style = b.i_style
                    AND g.id_company = b.id_company)
            LEFT JOIN tr_color h ON
                (h.i_color = b.i_color
                    AND b.id_company = h.id_company)
            LEFT JOIN tr_material m ON (m.id = a.id_material)
            WHERE
                a.id_forecast = '$id'
            ORDER BY
                b.i_product_wip
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
                tm_fccutting_item_new a
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
            WHERE a.f_marker_utama = 't'
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
        $this->db->delete('tm_fccutting_item_new');
    }

    /*----------  UPDATE HEADER  ----------*/

    public function updateheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $iperiode, $eremark)
    {
        $data = array(
            // 'id_company'       => $this->company,
            // 'i_document'       => $idocument,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            // 'id_referensi'     => $idreferensi,
            // 'i_periode'        => $iperiode,
            'e_remark'         => $eremark,
            'i_status'         => '1',
            'd_update'         => current_datetime()
        );
        $this->db->where('id', $id);
        $this->db->update('tm_fccutting', $data);
    }

    /*----------  UPDATE DETAIL  ----------*/

    public function updatedetailbase($id_forecast, $id_product_wip, $n_fc_perhitungan, $n_schedule_jahit, $n_bahan_baku, $n_sisa_schedule, $n_stock_pengadaan, $n_stock_pengesetan, $n_pendingan_permintaan_cutting, $n_kondisi_stock, $n_permintaan_cutting, $n_up_cutting, $n_fc_cutting, $e_remark)
    {
        $data = array(
            'n_fc_perhitungan' => (int)$n_fc_perhitungan,
            'n_schedule_jahit' => (int)$n_schedule_jahit,
            'n_bahan_baku' => (int)$n_bahan_baku,
            'n_sisa_schedule' => (int)$n_sisa_schedule,
            'n_stock_pengadaan' => (int)$n_stock_pengadaan,
            'n_stock_pengesetan' => (int)$n_stock_pengesetan,
            'n_pendingan_permintaan_cutting' => (int)$n_pendingan_permintaan_cutting,
            'n_kondisi_stock' => (int)$n_kondisi_stock,
            'n_permintaan_cutting' => (int)$n_permintaan_cutting,
            'n_up_cutting' => (int)$n_up_cutting,
            'n_fc_cutting' => (int)$n_fc_cutting,
            'e_remark' => $e_remark,
        );
        $this->db->where('id_company', $this->id_company);
        $this->db->where('id_forecast', $id_forecast);
        $this->db->where('id_product_wip', $id_product_wip);
        $this->db->update('tm_fccutting_item_new', $data);
    }

    public function update_detail(
        $id,
        $id_product_wip,
        $n_sisa_schedule_berjalan,
        $n_schedule_jahit,
        $n_stb_pengadaan,
        $n_stock_pengadaan,
        $n_stock_pengesetan,
        $n_sisa_permintaan_cutting,
        $n_kondisi_stock,
        $n_fc_produksi_perhitungan,
        $n_up_cutting,
        $n_fc_cutting,
        $e_remark,
        $v_set,
        $n_jumlah_gelar,
        $id_material,
        $n_fc_yang_dibutgetkan,
        $n_total_qty_kain_utama
    ) {
        $data = array(
            'n_sisa_schedule_berjalan' => $n_sisa_schedule_berjalan,
            'n_schedule_jahit' => $n_schedule_jahit,
            'n_stb_pengadaan' => $n_stb_pengadaan,
            'n_stock_pengadaan' => $n_stock_pengadaan,
            'n_stock_pengesetan' => $n_stock_pengesetan,
            'n_sisa_permintaan_cutting' => $n_sisa_permintaan_cutting,
            'n_kondisi_stock' => $n_kondisi_stock,
            'n_fc_produksi_perhitungan' => $n_fc_produksi_perhitungan,
            'n_up_cutting' => $n_up_cutting,
            'n_fc_cutting' => $n_fc_cutting,
            'v_set' => $v_set,
            'n_jumlah_gelar' => $n_jumlah_gelar,
            'e_remark' => $e_remark,
            'id_material' => $id_material,
            'n_fc_produksi_dibadgetkan' => $n_fc_yang_dibutgetkan,
            'n_qty_kain_utama' => $n_total_qty_kain_utama,
        );
        $this->db->where('id_forecast', $id);
        $this->db->where('id_product_wip', $id_product_wip);
        $this->db->update('tm_fccutting_detail', $data);
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

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_fccutting a
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
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_fccutting', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/


    /*----- TAMBAH KE FCCUTTING MATERIAL -----*/
    public function insertDetailMaterial($id)
    {
        // $cek = $this->db->get_where('tm_fccutting_material', ['id_forecast' => $id]);
        // if($cek->num_rows() > 0) {
        //     $this->db->where('id_forecast', $id);
        //     $this->db->delete('tm_fccutting_material');
        // }
        $this->db->query("delete from tm_fccutting_material where id_forecast = '$id'; ");
        $res = $this->db->query("WITH CTE AS (
            SELECT 0 AS no, a.id_forecast, ab.id_referensi, ab.i_periode, c.i_product_wip, initcap(c.e_product_wipname) e_product_wipname,  a.id_product_wip, e.e_color_name, 
            b.id_material, d.i_material, initcap(d.e_material_name) e_material_name, initcap(f.e_satuan_name) e_satuan_name, a.n_fc_cutting, b.n_quantity as n_quantity, 
            (round(b.n_quantity * a.n_fc_cutting, 2) - COALESCE(h.n_quantity,0)) qty,
            string_agg(g.d_document::varchar,', ') tanggal_schedule, ROW_NUMBER() OVER (ORDER BY a.id) AS i, a.id, b.id_type_makloon
            FROM tm_fccutting_detail a
            INNER JOIN tm_fccutting ab ON (ab.id = a.id_forecast)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
            INNER JOIN tr_color e ON (e.i_color = c.i_color AND c.id_company = e.id_company)
            INNER JOIN (
                SELECT a.id_product_wip, a.id_material, a.f_marker_utama, a.id_type_makloon, 
                sum((1 / a.v_set) * a.v_gelar) / nullif(cardinality(a.id_type_makloon),0) as n_quantity
                FROM tr_polacutting_new a
                INNER JOIN tr_type_makloon b ON (b.id = ANY(a.id_type_makloon))
                WHERE cardinality(a.id_type_makloon) > 0
                AND (b.e_type_makloon_name ILIKE '%CUTTING%' or b.e_type_makloon_name ILIKE '%AUTO%') AND a.v_bisbisan = '0' AND a.f_marker_utama = 't' and a.f_status = 't' group by 1,2,3,4   
                ) b ON (b.id_product_wip = a.id_product_wip)
            INNER JOIN tr_material d ON (d.id = b.id_material)
            INNER JOIN tr_satuan f ON (f.i_satuan_code = d.i_satuan_code AND d.id_company = f.id_company)
            LEFT JOIN (
                SELECT id_document, id_product_wip, d_schedule d_document FROM tm_schedule_jahit_item_new
            ) g ON ( g.id_product_wip = a.id_product_wip AND g.id_document = any(ab.id_referensi))
            LEFT JOIN (
                SELECT b.id_referensi, id_product_wip, id_material, sum(n_quantity) as n_quantity
                FROM tm_stb_material_cutting_item a, tm_stb_material_cutting b 
                WHERE b.id = a.id_document AND i_status IN ('1','2','3','6')  group by 1,2,3
            ) h ON ( h.id_product_wip = b.id_product_wip AND b.id_material = h.id_material AND a.id_forecast = ANY(h.id_referensi))
            WHERE ab.i_status = '6' AND ab.id_company = '$this->id_company'
            AND (round(b.n_quantity * a.n_fc_cutting, 2) - COALESCE (h.n_quantity,0)) > 0
            AND d.i_kode_group_barang = 'GRB0001' AND ab.id = '$id'
            GROUP BY 12,2,3,4,5,6,7,8,9,10,11,12,13, 14, a.id,h.n_quantity,b.id_type_makloon
            ORDER BY id_product_wip, e_color_name, i_material
        )
        SELECT no, id, i, id_forecast, id_referensi, i_periode, i_product_wip, e_product_wipname, id_product_wip, e_color_name, 
        id_material, i_material, e_material_name, e_satuan_name, n_fc_cutting, 
        n_quantity, qty, tanggal_schedule, (select count(i) as jml from CTE) As jml, id_type_makloon from CTE;");
        if($res->num_rows() > 0) {
            foreach($res->result() as $row) {
                $data = [
                    'no' => $row->no,
                    'id_item' => $row->id,
                    'i' => $row->i,
                    'id_forecast' => $row->id_forecast,
                    'id_referensi' => $row->id_referensi,
                    'i_periode' => $row->i_periode,
                    'i_product_wip' => $row->i_product_wip,
                    'e_product_wipname' => $row->e_product_wipname,
                    'id_product_wip' => $row->id_product_wip,
                    'e_color_name' => $row->e_color_name,
                    'id_material' => $row->id_material,
                    'i_material' => $row->i_material,
                    'e_material_name' => $row->e_material_name,
                    'e_satuan_name' => $row->e_satuan_name,
                    'n_fc_cutting' => $row->n_fc_cutting,
                    'n_quantity' => $row->n_quantity,
                    'qty' => $row->qty,
                    'tanggal_schedule' => $row->tanggal_schedule,
                    'jml' => $row->jml,
                    'id_type_makloon' => $row->id_type_makloon
                ];
                $this->db->insert('tm_fccutting_material', $data);
            }
        }
    }
    /*-- END OF TAMBAH KE FCCUTTING MATERIAL --*/
}
/* End of file Mmaster.php */