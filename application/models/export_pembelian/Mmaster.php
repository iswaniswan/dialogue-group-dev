<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    public function get_laporan_pembelian_credit($date_from, $date_to, $check, $i_supplier = '0')
    {
        if ($check == 'tgl_sj') {
            $and = "AND a.d_sj_supplier BETWEEN '$date_from' AND '$date_to' ";
        } else {
            $and = "AND a.d_btb BETWEEN '$date_from' AND '$date_to' ";
        }

        $where = '';
        if ($i_supplier != '0') {
            $where = " AND a.i_supplier = '$i_supplier' ";
        }
        // return $this->db->query("SELECT
        //         *,
        //         total / n_dpp dpp,
        //         ((total / n_dpp) * ppn) ppn
        //     FROM
        //         (
        //         SELECT
        //             a.i_supplier,
        //             upper(a.e_supplier_name) e_supplier_name,
        //             i_sj_supplier,
        //             d_sj_supplier,
        //             b.i_material,
        //             c.e_material_name,
        //             c.i_kode_group_barang,
        //             e.i_coa,
        //             bc.v_price v_price ,
        //             (b.n_quantity + b.n_toleransi) n_quantity,
        //             d.e_satuan_name,
        //             bc.v_price * (b.n_quantity + b.n_toleransi) total,
        //             '0' discount,
        //             SUM(bc.v_price * (b.n_quantity + b.n_toleransi)) OVER ( PARTITION BY a.i_sj_supplier,
        //                 -- d_sj_supplier,
        //                 a.i_supplier
        //             ORDER BY
        //                 i_sj_supplier,
        //                 d_sj_supplier,
        //                 a.i_supplier ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) hutang_dagang,
        //             (bc.n_ppn + 100) /100 AS n_dpp,
        //             bc.n_ppn / 100 ppn
        //         FROM
        //             tm_btb a
        //         INNER JOIN tm_btb_item b ON
        //             (b.id_btb = a.id)
        //         INNER JOIN tm_opbb_item bc ON 
        //             (bc.id_op = b.id_op AND b.i_material = bc.i_material AND bc.id_company = b.id_company)
        //         INNER JOIN tm_opbb cc ON 
        //             (cc.id = bc.id_op AND cc.jenis_pembelian = 'credit')
        //         INNER JOIN tr_material c ON
        //             (c.i_material = b.i_material AND b.id_company = c.id_company)
        //         INNER JOIN tr_satuan d ON
        //             (d.i_satuan_code = c.i_satuan_code AND c.id_company = d.id_company)
        //         LEFT JOIN tr_kelompok_barang e ON
        //             (e.i_kode_kelompok = c.i_kode_kelompok
        //                 AND c.id_company = e.id_company)
        //         LEFT JOIN tm_notabtb_item n ON (n.id_btb = b.id_btb AND b.i_material = n.i_material)
        //         /* LEFT JOIN public.tr_tax_amount e ON
        //             (a.d_btb BETWEEN e.d_start AND e.d_finish) */
        //         WHERE
        //             a.i_status = '6'
        //             $and
        //         ORDER BY
        //             i_sj_supplier,
        //             d_sj_supplier,
        //             a.i_supplier) x
        // ");
        return $this->db->query("SELECT
                *,
                /* total / n_dpp dpp,
                ((total / n_dpp) * ppn) ppn */
                total dpp,
                (total * ppn) ppn
            FROM
                (
                SELECT
                    a.i_supplier,
                    upper(a.e_supplier_name) e_supplier_name,
                    i_sj_supplier,
                    d_sj_supplier,
                    b.i_material,
                    c.e_material_name,
                    c.i_kode_group_barang,
                    e.i_coa,
                    e.e_nama_kelompok , f.e_type_name,
                    /*bc.v_price v_price,*/
                    CASE
                        WHEN n.v_price_manual ISNULL THEN bc.v_price
                        ELSE n.v_price_manual
                    END v_price,
                    CASE
                        WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi)
                        WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi)
                        ELSE b.n_quantity
                    END n_quantity,
                    (b.n_quantity + b.n_toleransi) n_quantity,
                    d.e_satuan_name,
                    (CASE
                        WHEN n.v_price_manual ISNULL THEN bc.v_price
                        ELSE n.v_price_manual
                    END) * (CASE
                        WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi)
                        WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi)
                        ELSE b.n_quantity
                    END) total,
                    '0' discount,
                    SUM(
                    (CASE WHEN n.v_price_manual ISNULL THEN bc.v_price ELSE n.v_price_manual END) * (CASE WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi) WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi) ELSE b.n_quantity END)) OVER ( PARTITION BY a.i_sj_supplier,
                    -- d_sj_supplier,
                    a.i_supplier
                ORDER BY
                    i_sj_supplier,
                    d_sj_supplier,
                    a.i_supplier ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) hutang_dagang,
                    (bc.n_ppn + 100) / 100 AS n_dpp,
                    bc.n_ppn / 100 ppn
                FROM
                    tm_btb a
                INNER JOIN tm_btb_item b ON
                    (b.id_btb = a.id)
                INNER JOIN tm_opbb_item bc ON
                    (bc.id_op = b.id_op
                        AND b.i_material = bc.i_material
                        AND bc.id_company = b.id_company)
                INNER JOIN tm_opbb cc ON
                    (cc.id = bc.id_op
                        AND cc.jenis_pembelian = 'credit')
                INNER JOIN tr_material c ON (c.i_material = b.i_material AND b.id_company = c.id_company)
                INNER JOIN tr_satuan d ON (d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company)
                LEFT JOIN tr_kelompok_barang e ON (e.i_kode_kelompok = c.i_kode_kelompok AND c.id_company = e.id_company)
                left join tr_item_type f on (c.i_type_code = f.i_type_code and c.id_company = f.id_company) 
                LEFT JOIN tm_notabtb_item n ON (n.id_btb = b.id_btb AND b.i_material = n.i_material)
                LEFT JOIN tm_notabtb mm ON (n.id_nota = mm.id)
                /* LEFT JOIN public.tr_tax_amount e ON
                    (a.d_btb BETWEEN e.d_start AND e.d_finish) */
                WHERE
                    a.i_status = '6' and (mm.i_status is null or mm.i_status in ('11','12','13'))
                    AND a.id_company = '$this->id_company'
                    $where 
                    $and
                ORDER BY
                    i_sj_supplier,
                    d_sj_supplier,
                    a.i_supplier) x;
                ");
    }

    public function get_laporan_pembelian_cash($date_from, $date_to, $check, $i_supplier = '0')
    {
        if ($check == 'tgl_sj') {
            $and = "AND a.d_sj_supplier BETWEEN '$date_from' AND '$date_to' ";
        } else {
            $and = "AND a.d_btb BETWEEN '$date_from' AND '$date_to' ";
        }

        $where = '';
        if ($i_supplier != '0') {
            $where = " AND a.i_supplier = '$i_supplier' ";
        }

        return $this->db->query("SELECT
                *,
                /* total / n_dpp dpp,
                ((total / n_dpp) * ppn) ppn */
                total dpp,
                (total * ppn) ppn
            FROM
                (
                SELECT
                    a.i_supplier,
                    upper(a.e_supplier_name) e_supplier_name,
                    i_sj_supplier,
                    d_sj_supplier,
                    b.i_material,
                    c.e_material_name,
                    c.i_kode_group_barang,
                    e.i_coa,
                    e.e_nama_kelompok , f.e_type_name,
                    /*bc.v_price v_price,*/
                    CASE
                        WHEN n.v_price_manual ISNULL THEN bc.v_price
                        ELSE n.v_price_manual
                    END v_price,
                    CASE
                        WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi)
                        WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi)
                        ELSE b.n_quantity
                    END n_quantity,
                    (b.n_quantity + b.n_toleransi) n_quantity,
                    d.e_satuan_name,
                    (CASE
                        WHEN n.v_price_manual ISNULL THEN bc.v_price
                        ELSE n.v_price_manual
                    END) * (CASE
                        WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi)
                        WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi)
                        ELSE b.n_quantity
                    END) total,
                    '0' discount,
                    SUM(
                    (CASE WHEN n.v_price_manual ISNULL THEN bc.v_price ELSE n.v_price_manual END) * (CASE WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi) WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi) ELSE b.n_quantity END)) OVER ( PARTITION BY a.i_sj_supplier,
                    -- d_sj_supplier,
                    a.i_supplier
                ORDER BY
                    i_sj_supplier,
                    d_sj_supplier,
                    a.i_supplier ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) hutang_dagang,
                    (bc.n_ppn + 100) / 100 AS n_dpp,
                    bc.n_ppn / 100 ppn
                FROM
                    tm_btb a
                INNER JOIN tm_btb_item b ON
                    (b.id_btb = a.id)
                INNER JOIN tm_opbb_item bc ON
                    (bc.id_op = b.id_op
                        AND b.i_material = bc.i_material
                        AND bc.id_company = b.id_company)
                INNER JOIN tm_opbb cc ON
                    (cc.id = bc.id_op
                        AND cc.jenis_pembelian = 'cash')
                INNER JOIN tr_material c ON
                    (c.i_material = b.i_material
                        AND b.id_company = c.id_company)
                INNER JOIN tr_satuan d ON
                    (d.i_satuan_code = b.i_satuan_code
                        AND b.id_company = d.id_company)
                LEFT JOIN tr_kelompok_barang e ON
                    (e.i_kode_kelompok = c.i_kode_kelompok
                        AND c.id_company = e.id_company)
                left join tr_item_type f on (c.i_type_code = f.i_type_code and c.id_company = f.id_company) 
                LEFT JOIN tm_notabtb_item n ON
                    (n.id_btb = b.id_btb
                        AND b.i_material = n.i_material)
                LEFT JOIN tm_notabtb mm ON (n.id_nota = mm.id)
                /* LEFT JOIN public.tr_tax_amount e ON
                    (a.d_btb BETWEEN e.d_start AND e.d_finish) */
                WHERE
                    a.i_status = '6' and (mm.i_status is null or mm.i_status in ('11','12','13'))
                    AND a.id_company = '$this->id_company'
                    $where
                    $and
                ORDER BY
                    i_sj_supplier,
                    d_sj_supplier,
                    a.i_supplier) x;
        ");
    }

    public function get_supplier($date_from, $date_to, $i_supplier)
    {
        $and = '';
        if ($i_supplier != '0') {
            $and = "AND i_supplier = '$i_supplier'";
        }
        return $this->db->query("SELECT DISTINCT
                i_supplier,
                upper(e_supplier_name) e_supplier_name
            FROM
                tm_notabtb
            WHERE
                i_status IN ('6', '11', '13')
                AND d_nota BETWEEN '$date_from' AND '$date_to'
                AND id_company = '$this->id_company'
                $and
            ORDER BY 2");
    }

    public function get_kartu_hutang($date_from, $date_to, $id_supplier, $check)
    {
        if ($check == 'tgl_sj') {
            $and = "AND b.d_faktur_supplier BETWEEN '$date_from' AND '$date_to' ";
        } else {
            $and = "AND b.d_nota BETWEEN '$date_from' AND '$date_to' ";
        }
        /* return $this->db->query("SELECT
                b.e_supplier_name,
                b.d_nota,
                d.d_sj_supplier,
                b.d_faktur_supplier,
                e.e_material_name,
                CASE WHEN b.i_faktur_supplier ISNULL OR b.i_faktur_supplier = '' THEN d.i_sj_supplier ELSE b.i_faktur_supplier||'/'||d.i_sj_supplier END faktur_sj,
                a.v_total
            FROM
                tm_notabtb_item a
            INNER JOIN tm_notabtb b ON
                (b.id = a.id_nota)
            INNER JOIN tm_btb_item c ON
                (c. id_btb = a.id_btb
                    AND a.i_material = c.i_material
                    AND c.id_company = a.id_company)
            INNER JOIN tm_btb d ON
                (d.id = c.id_btb)
            INNER JOIN tr_material e ON
                (e.i_material = a.i_material
                    AND a.id_company = e.id_company)
            WHERE
                b.i_status IN ('6', '11', '13')
                AND b.d_nota BETWEEN '$date_from' AND '$date_to'
                AND b.id_company = '$this->id_company'
                AND b.i_supplier = '$id_supplier'
            ORDER BY d.d_sj_supplier, b.d_faktur_supplier, b.d_nota ASC"); */
        return $this->db->query("SELECT *, 
            SUM(v_total - COALESCE (v_total_bayar,0)) OVER ( PARTITION BY e_supplier_name
                ORDER BY
                    e_supplier_name, d_nota ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) saldo_akhir 
            FROM (
                SELECT DISTINCT
                    b.e_supplier_name,
                    b.i_nota,
                    b.d_nota,
                    b.d_faktur_supplier,
                    b.i_faktur_supplier faktur_sj,
                    b.v_total,
                    b.v_total-b.v_sisa v_total_bayar
                FROM
                    tm_notabtb_item a
                INNER JOIN tm_notabtb b ON
                    (b.id = a.id_nota)
                /* LEFT JOIN (SELECT DISTINCT a.id_nota, a.v_nota_bayar, a.id_company 
                FROM tm_kasbank_keluarap_item a 
                INNER JOIN tm_kasbank_keluarap b ON (b.id = a.id_kasbank_keluarap AND b.i_jenis_faktur = '1')
                WHERE b.i_status = '6' AND b.id_company = '$this->id_company') e ON (e.id_nota = b.id AND b.id_company = e.id_company) */
                WHERE b.i_status IN ('6', '11', '13')
                $and
                AND b.id_company = '$this->id_company'
                AND b.i_supplier = '$id_supplier'
                ORDER BY e_supplier_name, d_nota
            ) x");
    }

    public function get_laporan_opname_hutang($date_from, $date_to, $check)
    {
        if ($check == 'tgl_sj') {
            $and = "AND a.d_faktur_supplier BETWEEN '$date_from' AND '$date_to' ";
        } else {
            $and = "AND a.d_nota BETWEEN '$date_from' AND '$date_to' ";
        }
        return $this->db->query("SELECT DISTINCT
                a.d_nota,
                a.i_nota,
                c.d_sj_supplier,
                a.n_top ||' Hari' n_top,
                a.d_faktur_supplier,
                a.d_jatuh_tempo,
                c.i_sj_supplier,
                a.i_supplier,
                a.e_supplier_name,
                e.e_supplier_group_name,
                a.v_total v_sisa
            FROM
                tm_notabtb a
            INNER JOIN (
                SELECT
                    DISTINCT id_btb,
                    id_nota
                FROM
                    tm_notabtb_item) b ON
                (b.id_nota = a.id)
            INNER JOIN tm_btb c ON
                (c.id = b.id_btb AND c.i_status = '6')
            INNER JOIN tr_supplier d ON
                (d.i_supplier = a.i_supplier
                    AND a.id_company = d.id_company)
            INNER JOIN tr_supplier_group e ON
                (e.i_supplier_group = d.i_supplier_group
                    AND d.id_company = e.id_company)
            WHERE
                a.i_status IN ('6', '11', '13')
                AND a.id_company = '$this->id_company'
                $and
            ORDER BY
                a.e_supplier_name,c.d_sj_supplier;    
        ");
    }

    public function get_rekapitulasi($date_from, $date_to, $check)
    {
        if ($check == 'tgl_sj') {
            $and = "AND a.d_faktur_supplier BETWEEN '$date_from' AND '$date_to' ";
        } else {
            $and = "AND a.d_nota BETWEEN '$date_from' AND '$date_to' ";
        }
        return $this->db->query("SELECT
                i_supplier,
                e_supplier_name,
                0 saldo_awal,
                sum(v_total) pembelian,
                0 pembelian_lain,
                0 pembelian_makloon,
                0 retur,
                sum(v_total - v_sisa) pelunasan,
                -- COALESCE (sum(e.v_nota_bayar),
                --0) pelunasan,
                0 cn,
                0 pembulatan,
                (sum(v_total) - sum(v_total - v_sisa)) saldo_akhir
                /* sum(v_total) - COALESCE (sum(e.v_nota_bayar),
                0) saldo_akhir */
            FROM
                tm_notabtb a
            /* LEFT JOIN (
                SELECT
                    DISTINCT a.id_nota,
                    a.v_nota_bayar,
                    a.id_company
                FROM
                    tm_kasbank_keluarap_item a
                INNER JOIN tm_kasbank_keluarap b ON
                    (b.id = a.id_kasbank_keluarap
                        AND b.i_jenis_faktur = '1')
                WHERE
                    b.i_status = '6'
                    AND b.id_company = '$this->id_company') e ON
                (e.id_nota = a.id
                    AND a.id_company = e.id_company) */
            WHERE
                a.id_company = '$this->id_company'
                AND a.i_status IN ('6', '11', '13')
                $and
            GROUP BY
                1,
                2;");
    }

    public function get_rekapitulasi_buku($date_from, $date_to, $check)
    {
        if ($check == 'tgl_sj') {
            $and = "AND a.d_faktur_supplier BETWEEN '$date_from' AND '$date_to' ";
        } else {
            $and = "AND a.d_nota BETWEEN '$date_from' AND '$date_to' ";
        }
        return $this->db->query("SELECT
                e_supplier_name,
                '' coa,
                sum(v_total) ap,
                sum(v_dpp) dpp,
                sum(v_ppn) ppn,
                0 retur,
                0 pph21,
                0 pph23,
                0 skb,
                sum(v_total) total_hutang
            FROM
                tm_notabtb a
            WHERE
                a.id_company = '$this->id_company'
                AND a.i_status IN ('6', '11', '13')
                $and
            GROUP BY
                1");
    }

    public function get_op_vs_btb($date_from, $date_to, $i_supplier)
    {
        $and = '';
        if ($i_supplier != '0') {
            $and = "AND a.i_supplier = '$i_supplier' ";
        }
        return $this->db->query(
            "SELECT 
                a.d_op, a.i_op, a.i_supplier, c.e_supplier_name, 
                d.i_material, trim(upper(d.e_material_name)) e_material_name, upper(trim(e.e_satuan_name)) e_satuan_name,
                b.n_quantity, COALESCE (f.n_quantity,0) n_quantity_sj, b.n_quantity - COALESCE (f.n_quantity,0) n_quantity_sisa
            FROM tm_opbb a
            INNER JOIN tm_opbb_item b ON (b.id_op = a.id)
            INNER JOIN tr_supplier c ON (
                c.i_supplier = a.i_supplier AND a.id_company = c.id_company
            )
            INNER JOIN tr_material d ON (
                d.i_material = b.i_material AND b.id_company = d.id_company
            )
            INNER JOIN tr_satuan e ON (
                e.i_satuan_code = d.i_satuan_code AND d.id_company = e.id_company
            )
            LEFT JOIN (
                SELECT id_op, i_material, b.i_supplier, sum(n_quantity) n_quantity
                FROM tm_btb_item a
                INNER JOIN tm_btb b ON (b.id = a.id_btb)
                WHERE b.id_company = '$this->id_company' AND b.i_status = '6'
                GROUP BY 1,2,3
            ) f ON (f.id_op = b.id_op AND b.i_material = f.i_material AND a.i_supplier = f.i_supplier)
            WHERE a.i_status = '6' AND a.d_op BETWEEN '$date_from' AND '$date_to'
            AND a.id_company = '$this->id_company' 
            $and
            ORDER BY c.e_supplier_name, a.d_op, a.i_op;"
        );
    }

    public function get_budgeting_realisasi($date_from, $date_to)
    {
        return $this->db->query(
            "SELECT c.i_material, upper(c.e_material_name) e_material_name, upper(d.e_satuan_name) e_satuan_name, 
                sum(b.n_budgeting) n_budgeting_qty, sum(b.n_budgeting * (b.v_price+(b.v_price * b.n_ppn))) n_budgeting_rp, 
                sum(e.n_quantity) n_realisasi_qty, sum(e.v_total) n_realisasi_rp
            FROM
            tm_budgeting a
            INNER JOIN tm_budgeting_item_material b ON (b.id_document = a.id)
            INNER JOIN tr_material c ON (c.id = b.id_material)
            INNER JOIN tr_satuan d ON (
                d.i_satuan_code = b.i_satuan_code_konversi AND b.id_company = d.id_company
            )
            LEFT JOIN (
                SELECT a.id_budgeting, e.i_material, e.n_quantity, e.v_total, e.id_company
                FROM tm_pp a
                INNER JOIN tm_pp_item b ON (b.id_pp = a.id)
                INNER JOIN tm_notabtb_item e ON (
                    e.id_pp = b.id_pp AND b.i_material = e.i_material AND e.id_company = b.id_company
                )
                INNER JOIN tm_notabtb f ON (f.id = e.id_nota)
                WHERE a.f_budgeting = 't' AND a.i_status = '6' AND f.i_status IN ('11','12','13') AND a.id_company = '$this->id_company'
            ) e ON (e.id_budgeting = b.id_document AND c.i_material = e.i_material AND e.id_company = b.id_company)
            WHERE
            a.d_document BETWEEN '$date_from' AND '$date_to' AND a.i_status = '6' AND b.id_supplier NOTNULL 
            AND a.id_company = '$this->id_company'
            GROUP BY 1,2,3
            ORDER BY 1,2,3;"
        );
    }

    public function get_btb_vs_faktur($date_from, $date_to, $i_supplier)
    {
        $and = '';
        if ($i_supplier != '0') {
            $and = "AND a.i_supplier = '$i_supplier' ";
        }
        return $this->db->query(
            "SELECT a.d_btb, a.i_btb, a.i_sj_supplier, a.i_supplier, e.e_supplier_name, f.i_nota, f.d_nota, 
                c.i_material, c.e_material_name, d.e_satuan_name, 
                sum(b.n_quantity) n_quantity_btb, sum(f.n_quantity) n_quantity_faktur
            FROM tm_btb a
            INNER JOIN tm_btb_item b ON (b.id_btb = a.id)
            INNER JOIN tr_material c ON (
                c.i_material = b.i_material AND b.id_company = c.id_company
            )
            INNER JOIN tr_satuan d ON (
                d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company
            )
            INNER JOIN tr_supplier e ON (
                e.i_supplier = a.i_supplier AND a.id_company = e.id_company
            )
            LEFT JOIN (
                SELECT a.i_nota, a.d_nota, b.id_btb, b.i_material, a.i_supplier, a.id_company, sum(b.n_quantity) n_quantity
                FROM tm_notabtb a
                INNER JOIN tm_notabtb_item b ON (b.id_nota = a.id)
                WHERE a.i_status IN ('11','12','13') AND a.id_company = '$this->id_company'
                GROUP BY 1,2,3,4,5,6
            ) f ON (f.id_btb = b.id_btb AND b.i_material = f.i_material AND f.id_company = b.id_company AND a.i_supplier = f.i_supplier)
            WHERE a.i_status = '6' AND a.d_btb BETWEEN '$date_from' AND '$date_to'
            AND a.id_company = '$this->id_company' 
            $and
            GROUP BY 1,2,3,4,5,6,7,8,9,10
            ORDER BY 5,1,2,4,6;
            "
        );
    }

    public function get_rekap_persupplier($date_from, $date_to)
    {
        return $this->db->query(
            "SELECT a.i_supplier, b.e_supplier_name, c.e_supplier_group_name, sum(a.v_total) v_total 
            FROM tm_notabtb a
            INNER JOIN tr_supplier b ON (
                b.i_supplier = a.i_supplier AND a.id_company = b.id_company
            )
            INNER JOIN tr_supplier_group c ON (
                c.i_supplier_group = b.i_supplier_group AND b.id_company = c.id_company
            )
            WHERE a.i_status IN ('11','12','13')
            AND a.id_company = '$this->id_company' AND d_nota BETWEEN '$date_from' AND '$date_to'
            GROUP BY 1,2,3
            ORDER BY 3,2
            "
        );
    }

    public function get_btb($date_from, $date_to, $i_supplier)
    {
        $and = '';
        if ($i_supplier != '0') {
            $and = "AND a.i_supplier = '$i_supplier' ";
        }
        return $this->db->query(
            "SELECT a.d_btb, a.i_btb, a.i_sj_supplier, c.i_material, c.e_material_name, d.e_satuan_name,
                    a.i_supplier, e.e_supplier_name, b.n_quantity, b.v_price, f.n_ppn  
            FROM tm_btb a
            INNER JOIN tm_btb_item b ON (b.id_btb = a.id)
            INNER JOIN tr_material c ON (
                c.i_material = b.i_material AND b.id_company = c.id_company
            )
            INNER JOIN tr_satuan d ON (
                d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company
            )
            INNER JOIN tr_supplier e ON (
                e.i_supplier = a.i_supplier AND a.id_company = e.id_company
            )
            INNER JOIN tm_opbb_item f ON (
                f.id_op = b.id_op AND b.i_material = f.i_material AND f.id_company = b.id_company
            )
            WHERE a.i_status = '6' AND a.id_company = '$this->id_company' AND a.d_btb BETWEEN '$date_from' AND '$date_to' $and
            ORDER BY 1,2,4,5"
        );
    }

    public function get_faktur($date_from, $date_to, $i_supplier)
    {
        $and = '';
        if ($i_supplier != '0') {
            $and = "AND a.i_supplier = '$i_supplier' ";
        }
        return $this->db->query(
            "SELECT a.i_nota, a.d_nota, a.i_supplier, f.e_supplier_name, b.i_material, d.e_material_name, e.e_satuan_name, 
                    b.n_quantity, b.v_price, b.n_diskon, b.v_total_diskon, b.v_dpp, b.v_ppn, b.v_total
            FROM tm_notabtb a
            INNER JOIN tm_notabtb_item b ON (b.id_nota = a.id)
            INNER JOIN tm_btb_item c ON (
                c.id_btb = b.id_btb AND b.i_material = c.i_material AND c.id_company = b.id_company
            )
            INNER JOIN tr_material d ON (
                d.i_material = b.i_material AND b.id_company = d.id_company
            )
            INNER JOIN tr_satuan e ON (
                e.i_satuan_code = c.i_satuan_code AND c.id_company = e.id_company
            )
            INNER JOIN tr_supplier f ON (
                f.i_supplier = a.i_supplier AND a.id_company = f.id_company
            )
            WHERE a.id_company = '$this->id_company' AND a.i_status IN ('11','12','13') 
            AND a.d_nota BETWEEN '$date_from' AND '$date_to' $and
            ORDER BY 2,1,5,6"
        );
    }

    public function get_kategori($date_from, $date_to)
    {
        return $this->db->query(
            "SELECT b.i_material, d.e_material_name, e.e_satuan_name, g.e_nama_group_barang, h.e_nama_kelompok, b.v_price, sum(b.n_quantity) n_quantity
            FROM tm_notabtb a
            INNER JOIN tm_notabtb_item b ON (b.id_nota = a.id)
            INNER JOIN tm_btb_item c ON (
                c.id_btb = b.id_btb AND b.i_material = c.i_material AND c.id_company = b.id_company
            )
            INNER JOIN tr_material d ON (
                d.i_material = b.i_material AND b.id_company = d.id_company
            )
            INNER JOIN tr_satuan e ON (
                e.i_satuan_code = c.i_satuan_code AND c.id_company = e.id_company
            )
            INNER JOIN tr_supplier f ON (
                f.i_supplier = a.i_supplier AND a.id_company = f.id_company
            )
            INNER JOIN tr_group_barang g ON (
                g.i_kode_group_barang = d.i_kode_group_barang AND d.id_company = g.id_company
            )
            INNER JOIN tr_kelompok_barang h ON (
                h.i_kode_kelompok = d.i_kode_kelompok AND d.id_company = h.id_company
            )
            WHERE a.id_company = '$this->id_company' AND a.i_status IN ('11','12','13') 
            AND a.d_nota BETWEEN '$date_from' AND '$date_to'
            GROUP BY 1,2,3,4,5,6
            ORDER BY 4,5,2,3;
            "
        );
    }

    public function get_pp($date_from, $date_to)
    {
        return $this->db->query(
            "SELECT
                a.d_pp,
                a.i_pp,
                g.e_bagian_name,
                c.i_material,
                c.e_material_name,
                d.e_satuan_name,
                b.n_quantity,
                b.n_sisa,
                CASE
                    WHEN b.e_remark = 'null'
                    OR b.e_remark = '' THEN NULL
                    ELSE b.e_remark
                END AS e_remark,
                CASE
                    WHEN a.f_budgeting = TRUE THEN 'Budgeting'
                    ELSE 'Out of Budget'
                END budgeting
            FROM
                tm_pp a
            INNER JOIN tm_pp_item b ON (b.id_pp = a.id)
            INNER JOIN tr_material c ON (c.i_material = b.i_material AND a.id_company = c.id_company)
            INNER JOIN tr_satuan d ON (
                d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company
            )
            INNER JOIN tr_bagian g ON (
                g.i_bagian = a.i_bagian AND a.id_company = g.id_company
            )
            WHERE
                a.id_company = '$this->id_company'
                AND a.d_pp BETWEEN '$date_from' AND '$date_to'
                AND a.i_status = '6'
            ORDER BY
                1,2,4,5
            "
        );
    }

    public function data($search, $offset, $limit, $date_from, $date_to, $i_supplier, $laporan, $type, $no_limit = true)
    {
        $where = '';
        $like = '';
        $and = '';
        $or = '';
        $paginate = '';
        if (strlen($limit) > 0 && $no_limit == true) {
            $paginate = "LIMIT $limit OFFSET $offset";
        }

        if ($laporan == 'exp_pembelian') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    c.i_material ILIKE '%$search%' OR 
                    c.e_material_name ILIKE '%$search%' OR 
                    a.i_supplier ILIKE '%$search%' OR
                    a.e_supplier_name ILIKE '%$search%'
                ) ";
            }
            return $this->db->query("SELECT 
                *,
                total dpp,
                (total * ppn) ppn
            FROM
                (
                SELECT DISTINCT
                    a.i_supplier,
                    upper(a.e_supplier_name) e_supplier_name,
                    i_sj_supplier,
                    d_sj_supplier,
                    b.i_material,
                    c.e_material_name,
                    c.i_kode_group_barang,
                    e.i_coa,
                    e.e_nama_kelompok , 
                    f.e_type_name,
                    CASE
                        WHEN n.v_price_manual ISNULL THEN bc.v_price
                        ELSE n.v_price_manual
                    END v_price,
                    CASE
                        WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi)
                        WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi)
                        ELSE b.n_quantity
                    END n_quantity,
                    /* (b.n_quantity + b.n_toleransi) n_quantity, */
                    d.e_satuan_name,
                    (CASE
                        WHEN n.v_price_manual ISNULL THEN bc.v_price
                        ELSE n.v_price_manual
                    END) * (CASE
                        WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi)
                        WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi)
                        ELSE b.n_quantity
                    END) total,
                    '0' discount,
                    SUM(
                    (CASE WHEN n.v_price_manual ISNULL THEN bc.v_price ELSE n.v_price_manual END) * (CASE WHEN n.f_toleransi ISNULL THEN (b.n_quantity + b.n_toleransi) WHEN n.f_toleransi = 't' THEN (b.n_quantity + b.n_toleransi) ELSE b.n_quantity END) * (bc.n_ppn + 100) / 100) OVER ( PARTITION BY a.i_sj_supplier,
                    -- d_sj_supplier,
                    a.i_supplier
                ORDER BY
                    i_sj_supplier,
                    d_sj_supplier,
                    a.i_supplier ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) hutang_dagang,
                    (bc.n_ppn + 100) / 100 AS n_dpp,
                    bc.n_ppn / 100 ppn
                FROM
                    tm_btb a
                INNER JOIN tm_btb_item b ON
                    (b.id_btb = a.id)
                INNER JOIN tm_opbb_item bc ON
                    (bc.id_op = b.id_op
                        AND b.i_material = bc.i_material
                        AND bc.id_company = b.id_company)
                INNER JOIN tm_opbb cc ON
                    (cc.id = bc.id_op)
                INNER JOIN tr_material c ON (c.i_material = b.i_material AND b.id_company = c.id_company)
                INNER JOIN tr_satuan d ON (d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company)
                LEFT JOIN tr_kelompok_barang e ON (e.i_kode_kelompok = c.i_kode_kelompok AND c.id_company = e.id_company)
                left join tr_item_type f on (c.i_type_code = f.i_type_code and c.id_company = f.id_company) 
                LEFT JOIN tm_notabtb_item n ON (n.id_btb = b.id_btb AND b.i_material = n.i_material)
                LEFT JOIN tm_notabtb mm ON (n.id_nota = mm.id)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->id_company'
                    AND lower(cc.jenis_pembelian) = lower(trim('$type'))
                    AND a.d_btb BETWEEN '$date_from' AND '$date_to'
                    AND (mm.i_status is null or mm.i_status in ('11','12','13')) $where $like
                ORDER BY
                    i_sj_supplier,
                    d_sj_supplier,
                    a.i_supplier) x $paginate;
                ");
        } elseif ($laporan == 'exp_kartu') {
            if ($i_supplier != '0') {
                $where = " AND i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    b.i_faktur_supplier ILIKE '%$search%' OR 
                    b.i_nota ILIKE '%$search%' OR
                    b.e_supplier_name ILIKE '%$search%'
                ) ";
            }
            return $this->db->query("SELECT *, 
            SUM(v_total - COALESCE (v_total_bayar,0)) OVER ( PARTITION BY e_supplier_name
                ORDER BY
                    e_supplier_name, d_nota ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) saldo_akhir 
            FROM (
                SELECT DISTINCT
                    b.e_supplier_name,
                    b.i_nota,
                    b.d_nota,
                    b.d_faktur_supplier,
                    b.i_faktur_supplier faktur_sj,
                    b.v_total,
                    b.v_total-b.v_sisa v_total_bayar
                FROM
                    tm_notabtb_item a
                INNER JOIN tm_notabtb b ON
                    (b.id = a.id_nota)
                WHERE b.i_status IN ('6', '11', '13')
                AND b.d_nota BETWEEN '$date_from' AND '$date_to'
                AND b.id_company = '$this->id_company' $where $like
                ORDER BY e_supplier_name, d_nota
            ) x $paginate");
        } elseif ($laporan == 'exp_opname') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    a.i_faktur_supplier ILIKE '%$search%' OR 
                    a.i_nota ILIKE '%$search%' OR
                    a.e_supplier_name ILIKE '%$search%' OR
                    a.i_supplier ILIKE '%$search%'
                ) ";
            }
            return $this->db->query("SELECT DISTINCT
                    a.d_nota,
                    a.i_nota,
                    c.d_sj_supplier,
                    a.n_top ||' Hari' n_top,
                    a.d_faktur_supplier,
                    a.d_jatuh_tempo,
                    c.i_sj_supplier,
                    a.i_supplier,
                    a.e_supplier_name,
                    e.e_supplier_group_name,
                    a.v_total v_sisa
                FROM
                    tm_notabtb a
                INNER JOIN (
                    SELECT
                        DISTINCT id_btb,
                        id_nota
                    FROM
                        tm_notabtb_item) b ON
                    (b.id_nota = a.id)
                INNER JOIN tm_btb c ON
                    (c.id = b.id_btb AND c.i_status = '6')
                INNER JOIN tr_supplier d ON
                    (d.i_supplier = a.i_supplier
                        AND a.id_company = d.id_company)
                INNER JOIN tr_supplier_group e ON
                    (e.i_supplier_group = d.i_supplier_group
                        AND d.id_company = e.id_company)
                WHERE
                    a.i_status IN ('6', '11', '13')
                    AND a.d_nota BETWEEN '$date_from' AND '$date_to'
                    AND a.id_company = '$this->id_company' $where $like
                ORDER BY
                    a.e_supplier_name,c.d_sj_supplier $paginate;    
            ");
        } elseif ($laporan == 'exp_rekapitulasi') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    a.e_supplier_name ILIKE '%$search%' OR
                    a.i_supplier ILIKE '%$search%'
                ) ";
            }
            return $this->db->query("SELECT
                i_supplier,
                e_supplier_name,
                0 saldo_awal,
                sum(v_total) pembelian,
                0 pembelian_lain,
                0 pembelian_makloon,
                0 retur,
                sum(v_total - v_sisa) pelunasan,
                0 cn,
                0 pembulatan,
                (sum(v_total) - sum(v_total - v_sisa)) saldo_akhir
            FROM
                tm_notabtb a
            WHERE
                a.id_company = '$this->id_company'
                AND a.i_status IN ('6', '11', '13')
                AND a.d_nota BETWEEN '$date_from' AND '$date_to' $where $like
            GROUP BY 1, 2 
            ORDER BY 2
            $paginate;");
        } elseif ($laporan == 'exp_buku') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    a.e_supplier_name ILIKE '%$search%' OR
                    a.i_supplier ILIKE '%$search%'
                ) ";
            }
            return $this->db->query("SELECT
                e_supplier_name,
                '' coa,
                sum(v_total) ap,
                sum(v_dpp) dpp,
                sum(v_ppn) ppn,
                0 retur,
                0 pph21,
                0 pph23,
                0 skb,
                sum(v_total) total_hutang
            FROM
                tm_notabtb a
            WHERE
                a.id_company = '$this->id_company'
                AND a.i_status IN ('6', '11', '13')
                AND a.d_nota BETWEEN '$date_from' AND '$date_to'
                $where $like
            GROUP BY
                1
            ORDER BY 1 $paginate;");
        } elseif ($laporan == 'exp_opvsbtb') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    a.e_supplier_name ILIKE '%$search%' OR
                    a.i_supplier ILIKE '%$search%' OR
                    d.i_material ILIKE '%$search%' OR
                    d.e_material_name ILIKE '%$search%'
                ) ";
            }
            return $this->db->query(
                "SELECT 
                    a.d_op, a.i_op, a.i_supplier, c.e_supplier_name, 
                    d.i_material, trim(upper(d.e_material_name)) e_material_name, upper(trim(e.e_satuan_name)) e_satuan_name,
                    b.n_quantity, COALESCE (f.n_quantity,0) n_quantity_sj, b.n_quantity - COALESCE (f.n_quantity,0) n_quantity_sisa
                FROM tm_opbb a
                INNER JOIN tm_opbb_item b ON (b.id_op = a.id)
                INNER JOIN tr_supplier c ON (
                    c.i_supplier = a.i_supplier AND a.id_company = c.id_company
                )
                INNER JOIN tr_material d ON (
                    d.i_material = b.i_material AND b.id_company = d.id_company
                )
                INNER JOIN tr_satuan e ON (
                    e.i_satuan_code = d.i_satuan_code AND d.id_company = e.id_company
                )
                LEFT JOIN (
                    SELECT id_op, i_material, b.i_supplier, sum(n_quantity) n_quantity
                    FROM tm_btb_item a
                    INNER JOIN tm_btb b ON (b.id = a.id_btb)
                    WHERE b.id_company = '$this->id_company' AND b.i_status = '6'
                    GROUP BY 1,2,3
                ) f ON (f.id_op = b.id_op AND b.i_material = f.i_material AND a.i_supplier = f.i_supplier)
                WHERE a.i_status = '6' AND a.d_op BETWEEN '$date_from' AND '$date_to'
                AND a.id_company = '$this->id_company' 
                AND a.d_op BETWEEN '$date_from' AND '$date_to' $where $like
                ORDER BY c.e_supplier_name, a.d_op, a.i_op $paginate;"
            );
        } elseif ($laporan == 'exp_btb_faktur') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    e.e_supplier_name ILIKE '%$search%' OR
                    e.i_supplier ILIKE '%$search%' OR
                    c.i_material ILIKE '%$search%' OR
                    c.e_material_name ILIKE '%$search%'
                ) ";
            }
            return $this->db->query(
                "SELECT a.d_btb, a.i_btb, a.i_sj_supplier, a.i_supplier, e.e_supplier_name, f.i_nota, f.d_nota, 
                    c.i_material, c.e_material_name, d.e_satuan_name, 
                    sum(b.n_quantity) n_quantity_btb, sum(f.n_quantity) n_quantity_nota
                FROM tm_btb a
                INNER JOIN tm_btb_item b ON (b.id_btb = a.id)
                INNER JOIN tr_material c ON (
                    c.i_material = b.i_material AND b.id_company = c.id_company
                )
                INNER JOIN tr_satuan d ON (
                    d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company
                )
                INNER JOIN tr_supplier e ON (
                    e.i_supplier = a.i_supplier AND a.id_company = e.id_company
                )
                LEFT JOIN (
                    SELECT a.i_nota, a.d_nota, b.id_btb, b.i_material, a.i_supplier, a.id_company, sum(b.n_quantity) n_quantity
                    FROM tm_notabtb a
                    INNER JOIN tm_notabtb_item b ON (b.id_nota = a.id)
                    WHERE a.i_status IN ('11','12','13') AND a.id_company = '$this->id_company'
                    GROUP BY 1,2,3,4,5,6
                ) f ON (f.id_btb = b.id_btb AND b.i_material = f.i_material AND f.id_company = b.id_company AND a.i_supplier = f.i_supplier)
                WHERE a.i_status = '6' AND a.d_btb BETWEEN '$date_from' AND '$date_to'
                AND a.id_company = '$this->id_company' $where $like
                GROUP BY 1,2,3,4,5,6,7,8,9,10
                ORDER BY 5,1,2,4,6 $paginate;
                "
            );
        } elseif ($laporan == 'exp_rekap_supplier') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    b.e_supplier_name ILIKE '%$search%' OR
                    b.i_supplier ILIKE '%$search%'
                ) ";
            }
            return $this->db->query(
                "SELECT a.i_supplier, b.e_supplier_name, c.e_supplier_group_name, sum(a.v_total) v_total 
                FROM tm_notabtb a
                INNER JOIN tr_supplier b ON (
                    b.i_supplier = a.i_supplier AND a.id_company = b.id_company
                )
                INNER JOIN tr_supplier_group c ON (
                    c.i_supplier_group = b.i_supplier_group AND b.id_company = c.id_company
                )
                WHERE a.i_status IN ('11','12','13')
                AND a.id_company = '$this->id_company' AND d_nota BETWEEN '$date_from' AND '$date_to'
                $where $like
                GROUP BY 1,2,3
                ORDER BY 3,2 $paginate"
            );
        } elseif ($laporan == 'exp_btb_dan_faktur') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    a.i_supplier ILIKE '%$search%' OR
                    a.e_supplier_name ILIKE '%$search%' OR
                    e_material_name ILIKE '%$search%' OR
                    b.i_material ILIKE '%$search%'
                ) ";
            }

            if ($type=='btb') {                
                return $this->db->query(
                    "SELECT a.d_btb, a.i_btb, a.i_sj_supplier, c.i_material, c.e_material_name, d.e_satuan_name,
                            a.i_supplier, e.e_supplier_name, b.n_quantity, b.v_price, f.n_ppn  
                    FROM tm_btb a
                    INNER JOIN tm_btb_item b ON (b.id_btb = a.id)
                    INNER JOIN tr_material c ON (
                        c.i_material = b.i_material AND b.id_company = c.id_company
                    )
                    INNER JOIN tr_satuan d ON (
                        d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company
                    )
                    INNER JOIN tr_supplier e ON (
                        e.i_supplier = a.i_supplier AND a.id_company = e.id_company
                    )
                    INNER JOIN tm_opbb_item f ON (
                        f.id_op = b.id_op AND b.i_material = f.i_material AND f.id_company = b.id_company
                    )
                    WHERE a.i_status = '6' AND a.id_company = '$this->id_company' 
                    AND a.d_btb BETWEEN '$date_from' AND '$date_to' $where $like
                    ORDER BY 1,2,4,5 $paginate"
                );
            }else{
                return $this->db->query(
                    "SELECT a.i_nota, a.d_nota, a.i_supplier, f.e_supplier_name, b.i_material, d.e_material_name, e.e_satuan_name, 
                            b.n_quantity, b.v_price, b.n_diskon, b.v_total_diskon, b.v_dpp, b.v_ppn, b.v_total
                    FROM tm_notabtb a
                    INNER JOIN tm_notabtb_item b ON (b.id_nota = a.id)
                    INNER JOIN tm_btb_item c ON (
                        c.id_btb = b.id_btb AND b.i_material = c.i_material AND c.id_company = b.id_company
                    )
                    INNER JOIN tr_material d ON (
                        d.i_material = b.i_material AND b.id_company = d.id_company
                    )
                    INNER JOIN tr_satuan e ON (
                        e.i_satuan_code = c.i_satuan_code AND c.id_company = e.id_company
                    )
                    INNER JOIN tr_supplier f ON (
                        f.i_supplier = a.i_supplier AND a.id_company = f.id_company
                    )
                    WHERE a.id_company = '$this->id_company' AND a.i_status IN ('11','12','13') 
                    AND a.d_nota BETWEEN '$date_from' AND '$date_to' $where $like
                    ORDER BY 2,1,5,6 $paginate"
                );
            }
        } elseif ($laporan == 'exp_per_kategori') {
            if ($i_supplier != '0') {
                $where = " AND a.i_supplier = '$i_supplier' ";
            }

            if (strlen($search) > 0) {
                $like = "AND (
                    d.e_material_name ILIKE '%$search%' OR
                    d.i_material ILIKE '%$search%'
                ) ";
            }
            return $this->db->query(
                "SELECT b.i_material, d.e_material_name, e.e_satuan_name, g.e_nama_group_barang, h.e_nama_kelompok, b.v_price, sum(b.n_quantity) n_quantity
                FROM tm_notabtb a
                INNER JOIN tm_notabtb_item b ON (b.id_nota = a.id)
                INNER JOIN tm_btb_item c ON (
                    c.id_btb = b.id_btb AND b.i_material = c.i_material AND c.id_company = b.id_company
                )
                INNER JOIN tr_material d ON (
                    d.i_material = b.i_material AND b.id_company = d.id_company
                )
                INNER JOIN tr_satuan e ON (
                    e.i_satuan_code = c.i_satuan_code AND c.id_company = e.id_company
                )
                INNER JOIN tr_supplier f ON (
                    f.i_supplier = a.i_supplier AND a.id_company = f.id_company
                )
                INNER JOIN tr_group_barang g ON (
                    g.i_kode_group_barang = d.i_kode_group_barang AND d.id_company = g.id_company
                )
                INNER JOIN tr_kelompok_barang h ON (
                    h.i_kode_kelompok = d.i_kode_kelompok AND d.id_company = h.id_company
                )
                WHERE a.id_company = '$this->id_company' AND a.i_status IN ('11','12','13') 
                AND a.d_nota BETWEEN '$date_from' AND '$date_to'
                $where $like
                GROUP BY 1,2,3,4,5,6
                ORDER BY 4,5,2,3 $paginate;"
            );
        } elseif ($laporan == 'exp_pp') {
            if (strlen($search) > 0) {
                $like = "AND (
                    a.i_pp ILIKE '%$search%' OR
                    c.e_material_name ILIKE '%$search%' OR
                    c.i_material ILIKE '%$search%'
                ) ";
            }
            return $this->db->query(
                "SELECT
                    a.d_pp,
                    a.i_pp,
                    g.e_bagian_name,
                    c.i_material,
                    c.e_material_name,
                    d.e_satuan_name,
                    b.n_quantity,
                    b.n_sisa,
                    CASE
                        WHEN b.e_remark = 'null'
                        OR b.e_remark = '' THEN NULL
                        ELSE b.e_remark
                    END AS e_remark,
                    CASE
                        WHEN a.f_budgeting = TRUE THEN 'Budgeting'
                        ELSE 'Out of Budget'
                    END budgeting
                FROM
                    tm_pp a
                INNER JOIN tm_pp_item b ON (b.id_pp = a.id)
                INNER JOIN tr_material c ON (c.i_material = b.i_material AND a.id_company = c.id_company)
                INNER JOIN tr_satuan d ON (
                    d.i_satuan_code = b.i_satuan_code AND b.id_company = d.id_company
                )
                INNER JOIN tr_bagian g ON (
                    g.i_bagian = a.i_bagian AND a.id_company = g.id_company
                )
                WHERE
                    a.id_company = '$this->id_company'
                    AND a.d_pp BETWEEN '$date_from' AND '$date_to'
                    AND a.i_status = '6'
                    $like
                ORDER BY 1,2,4,5 $paginate;"
            );
        }
    }
}
/* End of file Mmaster.php */