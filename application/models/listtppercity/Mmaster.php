<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return '00';
        }else{
            return 'xx';
        }
    }

    public function bacaarea($iarea, $username, $idcompany){
        if ($iarea=='00') {
            return $this->db->query("
                SELECT
                    *
                FROM
                    tr_area
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    *
                FROM
                    tr_area
                WHERE i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
            ", FALSE);
        }
    }

    public function data($tahun,$bulan,$iarea){
        $iperiode   = $tahun.$bulan;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                salesman,
                v_target_sales,
                e_city_name,
                v_target,
                v_nota_netto,
                round(persenptt,2)|| ' %' AS persenptt,
                round(persenrtp,2)|| ' %' AS persenrtp,
                round(persenreg,2)|| ' %' || ' - ' ||round(persenreg2,2)|| ' %' AS persenreg,
                round(persenbab,2)|| ' %' || ' - ' ||round(persenbab2,2)|| ' %' AS persenbab
            FROM
                (
                SELECT
                    c.i_salesman || ' - ' || e_salesman_name AS salesman,
                    CASE WHEN c.i_salesman ISNULL THEN NULL
                    ELSE v_target END AS v_target_sales,
                    e_city_name,
                    v_target,
                    v_nota_netto,
                    CASE WHEN v_target <> 0 THEN (v_nota_netto / v_target)* 100
                    ELSE 0 END AS persenptt,
                    CASE WHEN v_nota_netto <> 0 THEN ((v_retur_insentif + v_retur_noninsentif)/ v_nota_netto)* 100
                    ELSE 0 END AS persenrtp,
                    CASE WHEN v_target <> 0 THEN ((v_real_regularnoninsentif + v_real_regularinsentif)/ v_target)* 100
                    ELSE 0 END AS persenreg2,
                    CASE WHEN v_nota_netto <> 0 THEN ((v_real_regularnoninsentif + v_real_regularinsentif)/ v_nota_netto)* 100
                    ELSE 0 END AS persenreg,
                    CASE WHEN v_target <> 0 THEN ((v_real_babynoninsentif + v_real_babyinsentif)/ v_target)* 100
                    ELSE 0 END AS persenbab2,
                    CASE WHEN v_nota_netto <> 0 THEN ((v_real_babynoninsentif + v_real_babyinsentif)/ v_nota_netto)* 100
                    ELSE 0 END AS persenbab
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
                    b.i_salesman) AS x"
        , FALSE);

        $datatables->edit('v_target_sales', function ($data) {
            return number_format($data['v_target_sales']);
        });
        $datatables->edit('v_target', function ($data) {
            return number_format($data['v_target']);
        });
        $datatables->edit('v_nota_netto', function ($data) {
            return number_format($data['v_nota_netto']);
        });
        return $datatables->generate();
    }

    public function total($tahun,$bulan,$iarea){  
        $iperiode   = $tahun.$bulan;
        return $this->db->query("
            SELECT
                sum(CASE WHEN c.i_salesman ISNULL THEN NULL ELSE v_target END) AS sales,
                sum(v_target) AS target,
                sum(v_nota_netto) AS penjualan
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
        ", FALSE);
    }
}

/* End of file Mmaster.php */
