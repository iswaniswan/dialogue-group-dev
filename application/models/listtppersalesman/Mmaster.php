<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($tahun,$bulan){
        $iperiode   = $tahun.$bulan ;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_area,
                c.i_salesman || ' - ' || c.e_salesman_name AS salesman,
                v_target,
                v_nota_grossinsentif,
                CASE WHEN v_target <> 0 THEN (v_nota_grossinsentif / v_target)* 100
                ELSE 0 END AS persen,
                v_real_regularinsentif,
                CASE WHEN v_nota_grossinsentif <> 0 THEN (v_real_regularinsentif / v_nota_grossinsentif)* 100
                ELSE 0 END AS persenreg,
                v_real_babyinsentif,
                CASE WHEN v_nota_grossinsentif <> 0 THEN (v_real_babyinsentif / v_nota_grossinsentif)* 100
                ELSE 0 END AS persenbaby,
                v_retur_insentif,
                CASE WHEN v_nota_grossinsentif <> 0 THEN (v_retur_insentif / v_nota_grossinsentif)* 100
                ELSE 0 END AS persenretur,
                v_nota_grossnoninsentif,
                v_retur_noninsentif,
                v_spb_gross,
                CASE WHEN v_target <> 0 THEN (v_spb_gross / v_target)* 100
                ELSE 0 END AS persenspb
            FROM
                tr_area a
            INNER JOIN tm_target_itemsls b ON
                (a.i_area = b.i_area
                AND b.i_periode = '$iperiode')
            INNER JOIN tr_salesman c ON
                (b.i_salesman = c.i_salesman)
            WHERE
                a.f_area_real = 't'
            ORDER BY
                a.i_area,
                c.i_salesman"
        , FALSE);
        
        $datatables->edit('v_target', function ($data) {
            return number_format($data['v_target']);
        });
        $datatables->edit('v_nota_grossinsentif', function ($data) {
            return number_format($data['v_nota_grossinsentif']);
        });
        $datatables->edit('persen', function ($data) {
            return number_format($data['persen'],2)." %";
        });
        $datatables->edit('v_real_regularinsentif', function ($data) {
            return number_format($data['v_real_regularinsentif']);
        });
        $datatables->edit('persenreg', function ($data) {
            return number_format($data['persenreg'],2)." %";
        });
        $datatables->edit('v_real_babyinsentif', function ($data) {
            return number_format($data['v_real_babyinsentif']);
        });
        $datatables->edit('persenbaby', function ($data) {
            return number_format($data['persenbaby'],2)." %";
        });
        $datatables->edit('v_retur_insentif', function ($data) {
            return number_format($data['v_retur_insentif']);
        });
        $datatables->edit('persenretur', function ($data) {
            return number_format($data['persenretur'],2)." %";
        });
        $datatables->edit('v_nota_grossnoninsentif', function ($data) {
            return number_format($data['v_nota_grossnoninsentif']);
        });
        $datatables->edit('v_retur_noninsentif', function ($data) {
            return number_format($data['v_retur_noninsentif']);
        });
        $datatables->edit('v_spb_gross', function ($data) {
            return number_format($data['v_spb_gross']);
        });
        $datatables->edit('persenspb', function ($data) {
            return number_format($data['persenspb'],2)." %";
        });
        return $datatables->generate();
    }

    public function total($tahun,$bulan){  
        $iperiode   = $tahun.$bulan; 
        return $this->db->query("
            SELECT
                sum(v_target) AS target,
                sum(v_nota_grossinsentif) AS penjualan,
                sum(v_real_regularinsentif) AS reguler,
                sum(v_real_babyinsentif) AS baby,
                sum(v_retur_insentif) AS retur,
                sum(v_nota_grossnoninsentif) AS jualnoninsentif,
                sum(v_retur_noninsentif) AS returnoninsentif,
                sum(v_spb_gross) AS spb
            FROM
                tr_area a
            INNER JOIN tm_target_itemsls b ON
                (a.i_area = b.i_area
                AND b.i_periode = '$iperiode')
            INNER JOIN tr_salesman c ON
                (b.i_salesman = c.i_salesman)
            WHERE
                a.f_area_real = 't'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
