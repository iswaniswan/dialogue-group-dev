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
                c.e_city_name,
                a.i_salesman,
                SUM(a.v_spb) AS spb
            FROM
                tm_spb a,
                tr_customer b,
                tr_city c
            WHERE
                a.f_spb_cancel = 'f'
                AND TO_CHAR(a.d_spb, 'yyyymm')= '$iperiode'
                AND a.i_area = '$iarea'
                AND a.i_customer = b.i_customer
                AND b.i_city = c.i_city
                AND b.i_area = c.i_area
            GROUP BY
                a.i_customer,
                b.e_customer_name,
                b.e_customer_address,
                c.e_city_name,
                a.i_salesman
            ORDER BY
                c.e_city_name"
        , FALSE);
        $datatables->edit('spb', function ($data) {
            return number_format($data['spb']);
        });
        return $datatables->generate();
    }

    public function total($tahun, $bulan, $iarea){  
        $iperiode = $tahun.$bulan ;    
        return $this->db->query("
            SELECT
                SUM(a.v_spb) AS nilaispb
            FROM
                tm_spb a,
                tr_customer b,
                tr_city c
            WHERE
                a.f_spb_cancel = 'f'
                AND TO_CHAR(a.d_spb, 'yyyymm')= '$iperiode'
                AND a.i_area = '$iarea'
                AND a.i_customer = b.i_customer
                AND b.i_city = c.i_city
                AND b.i_area = c.i_area"
        , FALSE);
    }
}

/* End of file Mmaster.php */
