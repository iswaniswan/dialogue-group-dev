<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT (b.i_store),
                b.e_store_name,
                c.i_store_location,
                c.e_store_locationname,
                a.i_area
            FROM
                tr_area a,
                tr_store b,
                tr_store_location c
            WHERE 
                a.i_area=b.i_store 
                and b.i_store=c.i_store
                and (a.i_area in ( 
                    select 
                        i_area 
                    from 
                        public.tm_user_area 
                    where 
                        username='$username') 
                    )
                and not a.i_store in ('AA','PB')
                and c.i_store_location='00'
            ORDER BY 
                b.i_store, 
                c.i_store_location
        ", FALSE)->result();
    }

    public function getarea(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT *
            FROM public.tm_user_area
            WHERE username = '$username'
            AND id_company = '$id_company'
            AND i_area IN ('PB','00')
            ", FALSE);
        if ($query->num_rows()>0) {
            $key = $query->row();
            $iarea = $key->i_area;
            return 'PB';
        }else{
            return 'xx';
        }
    }

    public function customer($cari,$iarea){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        if ($iarea!='xx') {
            return $this->db->query("
                SELECT
                    a.*,
                    b.e_customer_name
                FROM
                    tr_spg a,
                    tr_customer b
                WHERE
                    a.i_customer = b.i_customer
                    AND (UPPER(a.i_customer)LIKE '%$cari%'
                    OR UPPER(b.e_customer_name)LIKE '%$cari%')
                ORDER BY
                    a.i_customer
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    a.*,
                    b.e_customer_name
                FROM
                    tr_spg a,
                    tr_customer b
                WHERE
                    a.i_customer = b.i_customer
                    AND a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username')
                        AND id_company = '$id_company')
                    AND (UPPER(a.i_customer)LIKE '%$cari%'
                    OR UPPER(b.e_customer_name)LIKE '%$cari%')
                ORDER BY
                    a.i_customer
            ", FALSE);
        }
    }

    public function bacastore($istore){
        return $this->db->query("
                                select
                                    c.i_store_location,
                                    b.i_store,
                                    a.i_area
                                from 
                                    tr_area a,
                                    tr_store b,
                                    tr_store_location c
                                where 
                                    a.i_store = b.i_store
                                    and b.i_store = c.i_store
                                    and a.i_store = '$istore'"
                                ,false);
    }

    public function baca($iperiode,$icustomer){
        if($iperiode>'201512'){
            $query = $this->db->query("	SELECT i_product,
                                            SUM(n_saldo_awal) AS n_saldo_awal,
                                            SUM(n_mutasi_daripusat) AS n_mutasi_daripusat,
                                            SUM(n_mutasi_darilang) AS n_mutasi_darilang,
                                            SUM(n_mutasi_kepusat) AS n_mutasi_kepusat,
                                            SUM(n_mutasi_penjualan) AS n_mutasi_penjualan,
                                            SUM(n_saldo_akhir) AS n_saldo_akhir,
                                            e_mutasi_periode,
                                            i_customer,
                                            SUM(n_saldo_stockopname) AS n_saldo_stockopname,
                                            e_product_name,
                                            e_customer_name
                                        FROM
                                            f_mutasi_stock_mo_cust_all_saldoakhir('$iperiode')
                                        WHERE
                                            i_customer = '$icustomer'
                                        GROUP BY
                                            i_product,
                                            e_mutasi_periode,
                                            i_customer,
                                            e_product_name,
                                            e_customer_name
                                        ORDER BY
                                            i_product,
                                            e_product_name,
                                            e_mutasi_periode,
                                            i_customer,
                                            e_customer_name ",false);
        }else{
            $query = $this->db->query("	SELECT
                                            i_product,
                                            SUM(n_saldo_awal) AS n_saldo_awal,
                                            SUM(n_mutasi_daripusat) AS n_mutasi_daripusat,
                                            SUM(n_mutasi_darilang) AS n_mutasi_darilang,
                                            SUM(n_mutasi_kepusat) AS n_mutasi_kepusat,
                                            SUM(n_mutasi_penjualan) AS n_mutasi_penjualan,
                                            SUM(n_saldo_akhir) AS n_saldo_akhir,
                                            e_mutasi_periode,
                                            i_customer,
                                            SUM(n_saldo_stockopname) AS n_saldo_stockopname,
                                            e_product_name,
                                            e_customer_name
                                        FROM
                                            f_mutasi_stock_mo_cust_all('$iperiode')
                                        WHERE
                                            i_customer = '$icustomer'
                                        GROUP BY
                                            i_product,
                                            e_mutasi_periode,
                                            i_customer,
                                            e_product_name,
                                            e_customer_name
                                        ORDER BY
                                            i_product,
                                            e_product_name,
                                            e_mutasi_periode,
                                            i_customer,
                                            e_customer_name "                                                                               ,false);#->limit($num,$offset);
        }
		if ($query->num_rows() > 0){
		  return $query->result();
		}
        $query->free_result();
    }

    public function detail($iperiode,$icustomer,$iproduct){
        $query =  $this->db->query(" SELECT
                                        b.e_product_name,
                                        a.ireff,
                                        a.dreff,
                                        a.customer,
                                        a.periode,
                                        a.product,
                                        e.e_customer_name,
                                        sum(a.in) as in,
                                        sum(a.out) as out
                                    from
                                        tr_product b,
                                        vmutasiconsigmentdetail a
                                    left join tr_customer e on
                                        a.customer = e.i_customer
                                    where
                                        b.i_product = a.product
                                        and a.periode = '$iperiode'
                                        and a.customer = '$icustomer'
                                        and a.product = '$iproduct'
                                    group by
                                        b.e_product_name,
                                        a.ireff,
                                        a.dreff,
                                        a.customer,
                                        a.periode,
                                        a.product,
                                        e.e_customer_name
                                    order by
                                        dreff,
                                        ireff ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
        $query->free_result();
    }

    public function getdetailspg($icustomer){
        $this->db->select(" *
                            FROM
                                tr_customer_consigment a,
                                tr_customer b,
                                tr_spg c
                            WHERE
                                a.i_customer = b.i_customer
                                AND a.i_customer = '$icustomer'
                                AND b.i_customer = c.i_customer
                            ORDER BY
                                a.i_customer",false);
        return $this->db->get();    
    }
}

/* End of file Mmaster.php */
