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
                a.i_nota,
                to_char(a.d_nota, 'dd-mm-yyyy') AS d_nota,
                to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') AS d_jatuh_tempo,
                c.e_city_name,
                a.i_salesman,
                a.v_nota_netto AS jumlah
            FROM
                tm_nota a,
                tr_customer b
            LEFT JOIN tr_city c ON
                (b.i_city = c.i_city
                AND b.i_area = c.i_area)
            WHERE
                a.f_nota_cancel = 'f'
                AND TO_CHAR(a.d_nota, 'yyyymm')= '$iperiode'
                AND NOT a.i_nota ISNULL
                AND a.i_area = '$iarea'
                AND a.i_customer = b.i_customer
            ORDER BY
                b.e_customer_name,
                a.i_nota"
        , FALSE);
        $datatables->edit('jumlah', function ($data) {
            return number_format($data['jumlah']);
        });
        return $datatables->generate();
    }

    public function total($tahun, $bulan, $iarea){  
        $iperiode = $tahun.$bulan ;    
        return $this->db->query("
            SELECT
                sum(a.v_nota_netto) AS jumlah
            FROM
                tm_nota a,
                tr_customer b
            LEFT JOIN tr_city c ON
                (b.i_city = c.i_city
                AND b.i_area = c.i_area)
            WHERE
                a.f_nota_cancel = 'f'
                AND TO_CHAR(a.d_nota, 'yyyymm')= '$iperiode'
                AND NOT a.i_nota ISNULL
                AND a.i_area = '$iarea'
                AND a.i_customer = b.i_customer"
        , FALSE);
    }
}

/* End of file Mmaster.php */
