<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username, $idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE)->result();
    }

    public function data($tahun,$bulan,$iarea){
        $iperiode   = $tahun.$bulan ;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_customer,
                b.e_customer_name,
                b.e_customer_address,
                UPPER(d.e_customer_classname) AS type,
                a.i_salesman,
                SUM(a.v_nota_gross) AS nota,
                SUM(a.v_nota_netto) AS bersih
            FROM
                tm_nota a,
                tr_customer b,
                tr_city c,
                tr_customer_class d
            WHERE
                a.f_nota_cancel = 'f'
                AND TO_CHAR(a.d_nota, 'yyyymm')= '$iperiode'
                AND NOT a.i_nota ISNULL
                AND a.i_area = '$iarea'
                AND a.i_customer = b.i_customer
                AND b.i_city = c.i_city
                AND b.i_area = c.i_area
                AND b.i_customer_class = d.i_customer_class
            GROUP BY
                a.i_customer,
                b.e_customer_name,
                b.e_customer_address,
                c.e_city_name,
                a.i_salesman,
                b.i_customer_class,
                d.e_customer_classname
            ORDER BY
                d.e_customer_classname"
        , FALSE);
        $datatables->edit('nota', function ($data) {
            return number_format($data['nota']);
        });
        $datatables->edit('bersih', function ($data) {
            return number_format($data['bersih']);
        });
        return $datatables->generate();
    }

    public function total($tahun, $bulan, $iarea){  
        $iperiode = $tahun.$bulan ;    
        return $this->db->query("
            SELECT
                SUM(a.v_nota_gross) AS nota,
                SUM(a.v_nota_netto) AS bersih
            FROM
                tm_nota a,
                tr_customer b,
                tr_city c,
                tr_customer_class d
            WHERE
                a.f_nota_cancel = 'f'
                AND TO_CHAR(a.d_nota, 'yyyymm')= '$iperiode'
                AND NOT a.i_nota ISNULL
                AND a.i_area = '$iarea'
                AND a.i_customer = b.i_customer
                AND b.i_city = c.i_city
                AND b.i_area = c.i_area
                AND b.i_customer_class = d.i_customer_class"
        , FALSE);
    }
}

/* End of file Mmaster.php */
