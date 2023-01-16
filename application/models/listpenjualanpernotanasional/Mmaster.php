<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($tahun,$bulan){
        $thbl = $tahun.$bulan ;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_area,
                b.i_customer,
                b.e_customer_name, 
                a.i_nota, 
                a.d_nota, 
                a.d_jatuh_tempo, 
                c.e_city_name, 
                a.i_salesman, 
                a.v_nota_netto
            FROM
                tm_nota a, 
                tr_customer b
                LEFT JOIN 
                    tr_city c on (b.i_city=c.i_city AND b.i_area=c.i_area)
            WHERE
                a.f_nota_cancel='f' 
                AND to_char(a.d_nota,'yyyymm')='$thbl' 
                AND not a.i_nota isnull
                AND a.i_customer=b.i_customer
            ORDER BY
                a.i_area, 
                a.i_nota"
        , FALSE);
        $datatables->edit('v_nota_netto', function ($data) {
            return number_format($data['v_nota_netto']);
        });
        $datatables->edit('d_nota', function ($data) {
            return date("d-m-Y", strtotime($data['d_nota']));
        });
        $datatables->edit('d_jatuh_tempo', function ($data) {
            return date("d-m-Y", strtotime($data['d_jatuh_tempo']));
        });
        return $datatables->generate();
    }

    public function total($tahun, $bulan){  
        $thbl = $tahun.$bulan ;    
        return $this->db->query("
            SELECT
                SUM(notatot) AS notatot
            FROM
                (
                SELECT
                    SUM(a.v_nota_netto) AS notatot
                FROM
                    tm_nota a, 
                    tr_customer b
                LEFT JOIN 
                    tr_city c on (b.i_city=c.i_city AND b.i_area=c.i_area)
                WHERE
                    a.f_nota_cancel='f' 
                    AND to_char(a.d_nota,'yyyymm')='$thbl' 
                    AND not a.i_nota isnull
                    AND a.i_customer=b.i_customer
                GROUP BY
                    a.v_nota_netto) AS x"
        , FALSE);
    }
}

/* End of file Mmaster.php */
