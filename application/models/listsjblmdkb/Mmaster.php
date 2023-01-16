<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
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
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'NA';
        }else{
            return 'XX';
        }
    }

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
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
        ", FALSE);
    }

    public function data($iarea){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND (substring(a.i_sj, 9, 2)= '$iarea' OR (substring(a.i_sj, 9, 2)= 'BK' AND a.i_area = '$iarea') )";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_sj,
                to_char(d_sj, 'dd-mm-yyyy') AS d_sj,
                CASE
                    WHEN substring(c.i_customer, 3, 3) != '000' THEN e_customer_name
                    ELSE ''
                END AS e_customer_name,
                v_nota_netto,
                b.e_area_name,
                d.e_city_name,
                i_spb
            FROM
                tm_nota a,
                tr_area b,
                tr_customer c,
                tr_city d
            WHERE
                a.i_area = b.i_area
                AND a.i_customer = c.i_customer
                $sql
                AND a.i_dkb ISNULL
                AND a.f_nota_cancel = 'f'
                AND c.i_area = d.i_area
                AND c.i_city = d.i_city
            ORDER BY
                a.i_sj ASC,
                a.d_sj DESC"
        , FALSE);
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
