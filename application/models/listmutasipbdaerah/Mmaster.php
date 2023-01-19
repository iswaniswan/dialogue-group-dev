<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT (b.i_store),
                b.e_store_name,
                c.i_store_location,
                c.e_store_locationname
            FROM
                tr_area a,
                tr_store b,
                tr_store_location c
            WHERE
                a.i_store = b.i_store
                AND b.i_store = c.i_store
                AND (a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company= '$id_company') )
                AND NOT a.i_store IN ('AA', 'PB')
                AND c.f_store_mo = '1'
            ORDER BY
                b.i_store,
                c.i_store_location
        ", FALSE);
    }

    public function getdetailstore($iarea){
        $username = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT (b.i_store),
                b.e_store_name,
                c.i_store_location,
                c.e_store_locationname
            FROM
                tr_area a,
                tr_store b,
                tr_store_location c
            WHERE
                a.i_store = b.i_store
                AND b.i_store = c.i_store
                AND (a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company= '$id_company') )
                AND NOT a.i_store IN ('AA', 'PB')
                AND c.f_store_mo = '1'
                AND a.i_store = '$iarea'
            ORDER BY
                b.i_store,
                c.i_store_location
        ", FALSE);
    }

    public function baca($istorelocation,$iperiode,$istore){
        $this->db->select(" 
                n_modul_no AS max
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'MTS'
                AND i_area = '$istore' FOR
            UPDATE", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    e_periode = '$iperiode'
                WHERE
                    i_modul = 'MTS'
                    AND i_area = '$istore'
                    AND i_store_location = '$istorelocation'
            ", false);
        }else{
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, i_store_location)
                VALUES ('MTS', '$istore', '$iperiode', '$istorelocation')
            ");
        }
        $query->free_result();
        if($iperiode>'201512'){
            $this->db->select("
                    *
                FROM
                    f_mutasi_stock_mo_daerah_saldoakhir('$iperiode', '$istore', '$istorelocation')
            ",false);
        }else{
            $this->db->select("
                    *
                FROM
                    f_mutasi_stock_mo_daerah('$iperiode', '$istore', '$istorelocation')
            ",false);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
        $query->free_result();
    }

    public function detail($iperiode,$iproduct){
        $this->db->select("
                b.e_product_name,
                a.ireff,
                a.dreff,
                a.periode,
                a.product,
                sum(a.in) AS masuk,
                sum(a.out) AS keluar,
                d.i_customer,
                z.i_customer AS i_customer1,
                e.e_customer_name AS e_customer_name1,
                y.e_customer_name AS e_customer_name2,
                w.i_spmb
            FROM
                tr_product b,
                vmutasidetailpb a
                /*SBR*/
            LEFT JOIN tm_sjpbr_item c ON
                c.i_sjpbr = a.ireff
                AND a.product = c.i_product
            LEFT JOIN tm_sjpbr d ON
                d.i_sjpbr = c.i_sjpbr
                AND c.i_area = d.i_area
                /*SB*/
            LEFT JOIN tm_sjpb_item x ON
                x.i_sjpb = a.ireff
                AND a.product = x.i_product
            LEFT JOIN tm_sjpb z ON
                z.i_sjpb = x.i_sjpb
                AND z.i_area = x.i_area
                /*SJP*/
            LEFT JOIN tm_sjp w ON
                w.i_sjp = a.ireff
            LEFT JOIN tr_customer e ON
                d.i_customer = e.i_customer
            LEFT JOIN tr_customer y ON
                y.i_customer = z.i_customer
            WHERE
                b.i_product = a.product
                AND a.periode = '$iperiode'
                AND a.product = '$iproduct'
                AND area = 'PB'
            GROUP BY
                b.e_product_name,
                a.ireff,
                a.dreff,
                a.periode,
                a.product,
                d.i_customer,
                e_customer_name1,
                e_customer_name2,
                w.i_spmb,
                i_customer1
            ORDER BY
                dreff,
                keluar    
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
