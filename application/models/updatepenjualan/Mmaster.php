<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function updatemodul($iperiode){
        $no = array(
            'e_periode' => $iperiode 
        );
        $this->db->where('i_modul', 'VPJ');
        $this->db->update('tm_dgu_no',$no);
    }

    public function simpan($iperiode){
        /*per area*/
        $this->db->select("
                i_area,
                SUM(a.v_nota_netto) AS v_nota_netto,
                SUM(a.v_nota_gross) AS v_nota_gross,
                SUM(a.v_nota_grossinsentif) AS v_nota_grossinsentif,
                SUM(a.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                SUM(a.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                SUM(a.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                SUM(a.v_nota_reguler) AS v_nota_reguler,
                SUM(a.v_nota_baby) AS v_nota_baby,
                SUM(a.v_nota_babyinsentif) AS v_nota_babyinsentif,
                SUM(a.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                SUM(a.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                SUM(a.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                SUM(a.v_spb_gross) AS v_spb_gross,
                SUM(a.v_spb_netto) AS v_spb_netto,
                SUM(a.v_retur_insentif) AS v_retur_insentif,
                SUM(a.v_retur_noninsentif) AS v_retur_noninsentif
            FROM
                (
                SELECT
                    i_area,
                    SUM(v_netto) AS v_nota_netto,
                    SUM(v_gross) AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    SUM(v_spb) AS v_spb_gross,
                    SUM(v_spb)-SUM(v_spbdiscount) AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    TO_CHAR(d_docspb, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    SUM(v_gross) AS v_nota_grossinsentif,
                    SUM(v_netto) AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 't'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    SUM(v_kn) AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 't'
                    AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    SUM(v_gross) AS v_nota_grossnoninsentif,
                    SUM(v_netto) AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 'f'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    SUM(v_kn) AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 'f'
                    AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group <> '00'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    SUM(v_gross) AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group = '00'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    SUM(v_gross) AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group <> '00'
                    AND f_insentif = 't'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    SUM(v_gross) AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group <> '00'
                    AND f_insentif = 'f'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    SUM(v_gross) AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group = '00'
                    AND f_insentif = 't'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area
            UNION ALL
                SELECT
                    i_area,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    SUM(v_gross) AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    SUM(v_gross) AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group = '00'
                    AND f_insentif = 'f'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area ) AS a
            GROUP BY
                i_area",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $dproses  = current_datetime();
                $tmp      = explode("-",$dproses);
                $th       = $tmp[0];
                $bl       = $tmp[1];
                $hr       = $tmp[2];
                $dentry   = $th.'-'.$bl.'-'.$hr;
                $this->db->select('i_area');
                $this->db->from('tm_target');
                $this->db->where('i_periode', $iperiode);
                $this->db->where('i_area', $row->i_area);
                $cek = $this->db->get();
                if($cek->num_rows()>0){
                    $this->db->query("
                        UPDATE
                            tm_target
                        SET
                            v_real_insentif = $row->v_nota_grossinsentif,
                            v_real_regularinsentif = $row->v_nota_regulerinsentif,
                            v_real_babyinsentif = $row->v_nota_babyinsentif,
                            v_real_noninsentif = $row->v_nota_grossnoninsentif,
                            v_real_regularnoninsentif = $row->v_nota_regulernoninsentif,
                            v_real_babynoninsentif = $row->v_nota_babynoninsentif,
                            v_retur_insentif = $row->v_retur_insentif,
                            v_retur_noninsentif = $row->v_retur_noninsentif,
                            v_spb_gross = $row->v_spb_gross,
                            v_spb_netto = $row->v_spb_netto,
                            v_nota_gross = $row->v_nota_gross,
                            v_nota_netto = $row->v_nota_netto,
                            v_nota_grossinsentif = $row->v_nota_grossinsentif,
                            v_nota_nettoinsentif = $row->v_nota_nettoinsentif,
                            v_nota_grossnoninsentif = $row->v_nota_grossnoninsentif,
                            v_nota_nettononinsentif = $row->v_nota_nettononinsentif,
                            d_process = '$dproses'
                        WHERE
                            i_periode = '$iperiode'
                            AND i_area = '$row->i_area'");
                }else{
                    $this->db->query("
                        INSERT
                            INTO
                            tm_target (i_periode,
                            i_area,
                            v_target,
                            v_real_insentif,
                            v_real_regularinsentif,
                            v_real_babyinsentif,
                            v_real_noninsentif,
                            v_real_regularnoninsentif,
                            v_real_babynoninsentif,
                            v_retur_insentif,
                            v_retur_noninsentif,
                            v_spb_gross,
                            v_spb_netto,
                            v_nota_gross,
                            v_nota_netto,
                            v_nota_grossinsentif,
                            v_nota_nettoinsentif,
                            v_nota_grossnoninsentif,
                            v_nota_nettononinsentif,
                            d_entry,
                            d_process)
                        VALUES ('$iperiode',
                        '$row->i_area',
                        0,
                        $row->v_nota_grossinsentif,
                        $row->v_nota_regulerinsentif,
                        $row->v_nota_babyinsentif,
                        $row->v_nota_grossnoninsentif,
                        $row->v_nota_regulernoninsentif,
                        $row->v_nota_babynoninsentif,
                        $row->v_retur_insentif,
                        $row->v_retur_noninsentif,
                        $row->v_spb_gross,
                        $row->v_spb_netto,
                        $row->v_nota_gross,
                        $row->v_nota_netto,
                        $row->v_nota_grossinsentif,
                        $row->v_nota_nettoinsentif,
                        $row->v_nota_grossnoninsentif,
                        $row->v_nota_nettononinsentif,
                        '$dentry',
                        '$dproses')
                    ");
                }
            }
        }

        /*per sales*/
        $this->db->select("   
                i_area,
                i_salesman,
                SUM(a.v_nota_netto) AS v_nota_netto,
                SUM(a.v_nota_gross) AS v_nota_gross,
                SUM(a.v_nota_grossinsentif) AS v_nota_grossinsentif,
                SUM(a.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                SUM(a.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                SUM(a.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                SUM(a.v_nota_reguler) AS v_nota_reguler,
                SUM(a.v_nota_baby) AS v_nota_baby,
                SUM(a.v_nota_babyinsentif) AS v_nota_babyinsentif,
                SUM(a.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                SUM(a.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                SUM(a.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                SUM(a.v_spb_gross) AS v_spb_gross,
                SUM(a.v_spb_netto) AS v_spb_netto,
                SUM(a.v_retur_insentif) AS v_retur_insentif,
                SUM(a.v_retur_noninsentif) AS v_retur_noninsentif
            FROM
                (
                SELECT
                    i_area,
                    i_salesman,
                    SUM(v_netto) AS v_nota_netto,
                    SUM(v_gross) AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    SUM(v_spb) AS v_spb_gross,
                    SUM(v_spb)-SUM(v_spbdiscount) AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    TO_CHAR(d_docspb, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    SUM(v_gross) AS v_nota_grossinsentif,
                    SUM(v_netto) AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 't'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    SUM(v_kn) AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 't'
                    AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    SUM(v_gross) AS v_nota_grossnoninsentif,
                    SUM(v_netto) AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 'f'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    SUM(v_kn) AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    f_insentif = 'f'
                    AND TO_CHAR(d_kn, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group <> '00'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    SUM(v_gross) AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group = '00'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    SUM(v_gross) AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group <> '00'
                    AND f_insentif = 't'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    SUM(v_gross) AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group <> '00'
                    AND f_insentif = 'f'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    SUM(v_gross) AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group = '00'
                    AND f_insentif = 't'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman
            UNION ALL
                SELECT
                    i_area,
                    i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    SUM(v_gross) AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    SUM(v_gross) AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    vpenjualan
                WHERE
                    i_product_group = '00'
                    AND f_insentif = 'f'
                    AND TO_CHAR(d_doc, 'yyyymm')= '$iperiode'
                GROUP BY
                    i_area,
                    i_salesman ) AS a
            GROUP BY
                i_area,
                i_salesman",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $dproses  = current_datetime();
                $this->db->select('i_salesman');
                $this->db->from('tm_target_itemsls');
                $this->db->where('i_periode', $iperiode);
                $this->db->where('i_area', $row->i_area);
                $this->db->where('i_salesman', $row->i_salesman);
                $cek = $this->db->get();
                if($cek->num_rows()>0){
                    $this->db->query("
                        UPDATE
                            tm_target_itemsls
                        SET
                            v_real_insentif = $row->v_nota_grossinsentif,
                            v_real_regularinsentif = $row->v_nota_regulerinsentif,
                            v_real_babyinsentif = $row->v_nota_babyinsentif,
                            v_real_noninsentif = $row->v_nota_grossnoninsentif,
                            v_real_regularnoninsentif = $row->v_nota_regulernoninsentif,
                            v_real_babynoninsentif = $row->v_nota_babynoninsentif,
                            v_retur_insentif = $row->v_retur_insentif,
                            v_retur_noninsentif = $row->v_retur_noninsentif,
                            v_spb_gross = $row->v_spb_gross,
                            v_spb_netto = $row->v_spb_netto,
                            v_nota_gross = $row->v_nota_gross,
                            v_nota_netto = $row->v_nota_netto,
                            v_nota_grossinsentif = $row->v_nota_grossinsentif,
                            v_nota_nettoinsentif = $row->v_nota_nettoinsentif,
                            v_nota_grossnoninsentif = $row->v_nota_grossnoninsentif,
                            v_nota_nettononinsentif = $row->v_nota_nettononinsentif
                        WHERE
                            i_periode = '$iperiode'
                            AND i_area = '$row->i_area'
                            AND i_salesman = '$row->i_salesman'
                    ");
                }else{
                    $this->db->query("
                        INSERT
                            INTO
                            tm_target_itemsls (v_real_insentif,
                            v_real_regularinsentif,
                            v_real_babyinsentif,
                            v_real_noninsentif,
                            v_real_regularnoninsentif,
                            v_real_babynoninsentif,
                            v_retur_insentif,
                            v_retur_noninsentif,
                            v_spb_gross,
                            v_spb_netto,
                            v_nota_gross,
                            v_nota_netto,
                            v_nota_grossinsentif,
                            v_nota_nettoinsentif,
                            v_nota_grossnoninsentif,
                            v_nota_nettononinsentif,
                            i_periode,
                            i_area,
                            i_salesman)
                        VALUES ($row->v_nota_grossinsentif,
                        $row->v_nota_regulerinsentif,
                        $row->v_nota_babyinsentif,
                        $row->v_nota_grossnoninsentif,
                        $row->v_nota_regulernoninsentif,
                        $row->v_nota_babynoninsentif,
                        $row->v_retur_insentif,
                        $row->v_retur_noninsentif,
                        $row->v_spb_gross,
                        $row->v_spb_netto,
                        $row->v_nota_gross,
                        $row->v_nota_netto,
                        $row->v_nota_grossinsentif,
                        $row->v_nota_nettoinsentif,
                        $row->v_nota_grossnoninsentif,
                        $row->v_nota_nettononinsentif,
                        '$iperiode',
                        '$row->i_area',
                        '$row->i_salesman')
                    ");
                }
            }
        }
    
        /*per nota*/
        $this->db->where('i_periode', $iperiode);
        $this->db->delete('tm_target_itemnota');
        $this->db->select("
                TO_CHAR(a.d_doc, 'yyyymm') AS i_periode,
                a.i_area,
                a.i_salesman,
                a.i_customer,
                c.i_city_type,
                c.i_city_typeperarea,
                c.i_city_group,
                b.i_city,
                a.i_doc AS i_nota,
                a.i_docspb AS i_spb,
                a.d_doc AS d_nota,
                a.d_docspb AS d_spb,
                a.e_remark,
                a.f_masalah,
                a.f_insentif,
                a.v_netto
            FROM
                vpenjualan a
            INNER JOIN tr_customer b ON
                (a.i_customer = b.i_customer
                AND a.i_area = b.i_area)
            INNER JOIN tr_city c ON
                (b.i_city = c.i_city
                AND a.i_area = c.i_area)
            INNER JOIN tr_area d ON
                (a.i_area = d.i_area)
            WHERE
                TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $this->db->query("
                    INSERT
                        INTO
                        tm_target_itemnota
                    VALUES('$iperiode',
                    '$row->i_area',
                    '$row->i_salesman',
                    '$row->i_customer',
                    '$row->i_city_type',
                    '$row->i_city_typeperarea',
                    '$row->i_city_group',
                    '$row->i_city',
                    '$row->i_nota',
                    '$row->i_spb',
                    '$row->d_nota',
                    '$row->d_spb',
                    '$row->e_remark',
                    '$row->f_masalah',
                    '$row->f_insentif',
                    $row->v_netto)
                ");
            }
        }

        /*per kota*/
        $this->db->select("
                i_area,
                i_city,
                i_salesman,
                SUM(a.v_nota_netto) AS v_nota_netto,
                SUM(a.v_nota_gross) AS v_nota_gross,
                SUM(a.v_nota_grossinsentif) AS v_nota_grossinsentif,
                SUM(a.v_nota_nettoinsentif) AS v_nota_nettoinsentif,
                SUM(a.v_nota_grossnoninsentif) AS v_nota_grossnoninsentif,
                SUM(a.v_nota_nettononinsentif) AS v_nota_nettononinsentif,
                SUM(a.v_nota_reguler) AS v_nota_reguler,
                SUM(a.v_nota_baby) AS v_nota_baby,
                SUM(a.v_nota_babyinsentif) AS v_nota_babyinsentif,
                SUM(a.v_nota_babynoninsentif) AS v_nota_babynoninsentif,
                SUM(a.v_nota_regulerinsentif) AS v_nota_regulerinsentif,
                SUM(a.v_nota_regulernoninsentif) AS v_nota_regulernoninsentif,
                SUM(a.v_spb_gross) AS v_spb_gross,
                SUM(a.v_spb_netto) AS v_spb_netto,
                SUM(a.v_retur_insentif) AS v_retur_insentif,
                SUM(a.v_retur_noninsentif) AS v_retur_noninsentif
            FROM
                (
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    SUM(a.v_netto) AS v_nota_netto,
                    SUM(a.v_gross) AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    SUM(a.v_spb) AS v_spb_gross,
                    SUM(a.v_spb)-SUM(a.v_spbdiscount) AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_docspb, 'yyyymm')= '$iperiode')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    SUM(a.v_gross) AS v_nota_grossinsentif,
                    SUM(a.v_netto) AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.f_insentif = 't')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    SUM(a.v_kn) AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_kn, 'yyyymm')= '$iperiode'
                    AND a.f_insentif = 't')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    SUM(a.v_gross) AS v_nota_grossnoninsentif,
                    SUM(a.v_netto) AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.f_insentif = 'f')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    SUM(a.v_kn) AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_kn, 'yyyymm')= '$iperiode'
                    AND a.f_insentif = 'f')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(a.v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.i_product_group <> '00')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    SUM(a.v_gross) AS v_nota_reguler,
                    0 AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.i_product_group = '00')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(a.v_gross) AS v_nota_baby,
                    SUM(a.v_gross) AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.i_product_group <> '00'
                    AND a.f_insentif = 't')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(a.v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    SUM(a.v_gross) AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.i_product_group <> '00'
                    AND a.f_insentif = 'f')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(a.v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    0 AS v_nota_babynoninsentif,
                    SUM(a.v_gross) AS v_nota_regulerinsentif,
                    0 AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.i_product_group = '00'
                    AND a.f_insentif = 't')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman
            UNION ALL
                SELECT
                    c.i_area,
                    c.i_city,
                    a.i_salesman,
                    0 AS v_nota_netto,
                    0 AS v_nota_gross,
                    0 AS v_nota_grossinsentif,
                    0 AS v_nota_nettoinsentif,
                    0 AS v_nota_grossnoninsentif,
                    0 AS v_nota_nettononinsentif,
                    0 AS v_nota_reguler,
                    SUM(a.v_gross) AS v_nota_baby,
                    0 AS v_nota_babyinsentif,
                    SUM(a.v_gross) AS v_nota_babynoninsentif,
                    0 AS v_nota_regulerinsentif,
                    SUM(a.v_gross) AS v_nota_regulernoninsentif,
                    0 AS v_spb_gross,
                    0 AS v_spb_netto,
                    0 AS v_retur_insentif,
                    0 AS v_retur_noninsentif
                FROM
                    tr_city c,
                    tr_customer b
                LEFT JOIN vpenjualan a ON
                    (a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_doc, 'yyyymm')= '$iperiode'
                    AND a.i_product_group = '00'
                    AND a.f_insentif = 'f')
                WHERE
                    b.i_city = c.i_city
                    AND b.i_area = c.i_area
                GROUP BY
                    c.i_area,
                    c.i_city,
                    a.i_salesman ) AS a
            GROUP BY
                i_area,
                i_city,
                i_salesman
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $this->db->select('i_area');
                $this->db->from('tm_target_itemkota');
                $this->db->where('i_periode', $iperiode);
                $this->db->where('i_area', $row->i_area);
                $this->db->where('i_salesman', $row->i_salesman);
                $this->db->where('i_city', $row->i_city);
                $cek = $this->db->get();
                if ($cek->num_rows() > 0){
                    $dproses= current_datetime();
                    $this->db->query("
                        UPDATE
                            tm_target_itemkota
                        SET
                            v_real_insentif = $row->v_nota_grossinsentif,
                            v_real_regularinsentif = $row->v_nota_regulerinsentif,
                            v_real_babyinsentif = $row->v_nota_babyinsentif,
                            v_real_noninsentif = $row->v_nota_grossnoninsentif,
                            v_real_regularnoninsentif = $row->v_nota_regulernoninsentif,
                            v_real_babynoninsentif = $row->v_nota_babynoninsentif,
                            v_retur_insentif = $row->v_retur_insentif,
                            v_retur_noninsentif = $row->v_retur_noninsentif,
                            v_spb_gross = $row->v_spb_gross,
                            v_spb_netto = $row->v_spb_netto,
                            v_nota_gross = $row->v_nota_gross,
                            v_nota_netto = $row->v_nota_netto,
                            v_nota_grossinsentif = $row->v_nota_grossinsentif,
                            v_nota_nettoinsentif = $row->v_nota_nettoinsentif,
                            v_nota_grossnoninsentif = $row->v_nota_grossnoninsentif,
                            v_nota_nettononinsentif = $row->v_nota_nettononinsentif,
                            d_process = '$dproses'
                        WHERE
                            i_periode = '$iperiode'
                            AND i_area = '$row->i_area'
                            AND i_city = '$row->i_city'
                            AND i_salesman = '$row->i_salesman'
                    ");
                }elseif($row->i_salesman!='' || $row->i_salesman==null){
                    $dproses= current_datetime();
                    $this->db->query("
                        INSERT
                            INTO
                            tm_target_itemkota
                        VALUES('$iperiode',
                        '$row->i_area',
                        '$row->i_salesman',
                        '$row->i_city',
                        0,
                        $row->v_nota_grossinsentif,
                        $row->v_nota_regulerinsentif,
                        $row->v_nota_babyinsentif,
                        $row->v_nota_grossnoninsentif,
                        $row->v_nota_regulernoninsentif,
                        $row->v_nota_babynoninsentif,
                        $row->v_retur_insentif,
                        $row->v_retur_noninsentif,
                        $row->v_spb_gross,
                        $row->v_spb_netto,
                        $row->v_nota_gross,
                        $row->v_nota_netto,
                        $row->v_nota_grossinsentif,
                        $row->v_nota_nettoinsentif,
                        $row->v_nota_grossnoninsentif,
                        $row->v_nota_nettononinsentif,
                        '$dproses',
                        '$dproses')
                    ");
                }
            }
        }

        /*hitung retur*/
        $this->db->where('i_periode', $iperiode);
        $this->db->delete('tm_target_itemretur');
        $this->db->select("
                TO_CHAR(a.d_doc, 'yyyymm') AS i_periode,
                a.i_area,
                a.i_salesman,
                a.i_customer,
                c.i_city_type,
                c.i_city_typeperarea,
                c.i_city_group,
                b.i_city,
                a.i_kn,
                a.d_kn,
                a.e_remark,
                a.f_masalah,
                a.f_insentif,
                a.v_kn
            FROM
                vpenjualan a
            INNER JOIN tr_customer b ON
                (a.i_customer = b.i_customer
                AND a.i_area = b.i_area)
            INNER JOIN tr_city c ON
                (b.i_city = c.i_city
                AND a.i_area = c.i_area)
            INNER JOIN tr_area d ON
                (a.i_area = d.i_area)
            WHERE
                TO_CHAR(a.d_kn, 'yyyymm')= '$iperiode'
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $this->db->query("
                    INSERT
                        INTO
                        tm_target_itemretur
                    VALUES('$iperiode',
                    '$row->i_area',
                    '$row->i_salesman',
                    '$row->i_customer',
                    '$row->i_city_type',
                    '$row->i_city_typeperarea',
                    '$row->i_city_group',
                    '$row->i_city',
                    '$row->i_kn',
                    '$row->d_kn',
                    '$row->e_remark',
                    '$row->f_masalah',
                    '$row->f_insentif',
                    $row->v_kn)
                ");
            }
        }
    }

    public function baca($iperiode){
        $this->db->select("
                a.i_area,
                a.e_area_name,
                v_target,
                v_nota_grossinsentif,
                v_real_regularinsentif,
                v_real_babyinsentif,
                v_retur_insentif,
                v_nota_grossnoninsentif,
                v_retur_noninsentif,
                v_spb_gross,
                TO_CHAR(d_process, 'dd-mm-yyyy hh:mi:ss') AS d_process
            FROM
                tr_area a
            LEFT JOIN tm_target b ON
                (a.i_area = b.i_area)
            WHERE
                a.f_area_real = 't'
                AND b.i_periode = '$iperiode'
            ORDER BY
                a.i_area", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacapersales($iperiode, $iarea){
        $this->db->select(" 
                a.i_area,
                a.e_area_name,
                c.i_salesman,
                c.e_salesman_name,
                v_target,
                v_nota_grossinsentif,
                v_real_regularinsentif,
                v_real_babyinsentif,
                v_retur_insentif,
                v_nota_grossnoninsentif,
                v_retur_noninsentif,
                v_spb_gross
            FROM
                tr_area a
            INNER JOIN tm_target_itemsls b ON
                (a.i_area = b.i_area
                AND b.i_periode = '$iperiode'
                AND b.i_area = '$iarea')
            INNER JOIN tr_salesman c ON
                (b.i_salesman = c.i_salesman)
            WHERE
                a.f_area_real = 't'
            ORDER BY
                c.i_salesman
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacapernota($iperiode, $iarea){
        $this->db->select(" 
                a.i_area,
                a.e_area_name,
                c.i_salesman,
                c.e_salesman_name,
                b.i_nota,
                b.d_nota,
                b.v_netto,
                b.i_customer,
                d.e_customer_name,
                d.e_customer_address,
                e.e_city_name
            FROM
                tr_area a
            INNER JOIN tm_target_itemnota b ON
                (a.i_area = b.i_area
                AND b.i_periode = '$iperiode'
                AND b.i_area = '$iarea')
            INNER JOIN tr_salesman c ON
                (b.i_salesman = c.i_salesman)
            INNER JOIN tr_customer d ON
                (b.i_customer = d.i_customer)
            INNER JOIN tr_city e ON
                (b.i_city = e.i_city
                AND a.i_area = e.i_area)
            WHERE
                a.f_area_real = 't'
            ORDER BY
                b.i_nota
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacaperkota($iperiode, $iarea){
        $this->db->select(" 
                a.i_area,
                a.e_area_name,
                c.i_salesman,
                c.e_salesman_name,
                b.i_city,
                b.v_target,
                b.v_nota_grossinsentif,
                b.v_real_regularinsentif,
                b.v_real_babyinsentif,
                b.v_retur_insentif,
                b.v_nota_grossnoninsentif,
                b.v_retur_noninsentif,
                b.v_spb_gross,
                e.e_city_name
            FROM
                tr_area a
            INNER JOIN tm_target_itemkota b ON
                (a.i_area = b.i_area
                AND b.i_periode = '$iperiode'
                AND b.i_area = '$iarea')
            LEFT JOIN tr_salesman c ON
                (b.i_salesman = c.i_salesman)
            INNER JOIN tr_city e ON
                (b.i_city = e.i_city
                AND a.i_area = e.i_area)
            WHERE
                a.f_area_real = 't'
            ORDER BY
                e.e_city_name,
                i_salesman
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacaretur($iperiode, $iarea){
        $this->db->select(" 
                a.i_area,
                a.e_area_name,
                c.i_salesman,
                c.e_salesman_name,
                b.i_kn,
                b.d_kn,
                b.v_netto,
                b.i_customer,
                d.e_customer_name,
                d.e_customer_address,
                e.e_city_name
            FROM
                tr_area a
            INNER JOIN tm_target_itemretur b ON
                (a.i_area = b.i_area
                AND b.i_periode = '$iperiode'
                AND b.i_area = '$iarea')
            INNER JOIN tr_salesman c ON
                (b.i_salesman = c.i_salesman)
            INNER JOIN tr_customer d ON
                (b.i_customer = d.i_customer)
            INNER JOIN tr_city e ON
                (b.i_city = e.i_city
                AND a.i_area = e.i_area)
            WHERE
                a.f_area_real = 't'
            ORDER BY
                c.i_salesman,
                b.d_kn,
                b.i_kn
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
