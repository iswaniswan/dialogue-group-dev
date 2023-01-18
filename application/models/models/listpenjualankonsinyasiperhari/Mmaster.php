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

    public function bacacustomer($username, $idcompany){    
        return $this->db->query("
            SELECT
                a.*,
                b.e_customer_name,
                c.e_area_name
            FROM
                tr_spg a,
                tr_customer b,
                tr_area c
            WHERE
                a.i_customer=b.i_customer 
                AND a.i_area=b.i_area
                AND a.i_area=c.i_area
                AND
                a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE)->result();
    }

    function bacaperiode($dfrom,$dto,$icustomer){
        $query = $this->db->query(" 
                                SELECT 
                                    a.d_notapb, 
                                    a.i_customer, 
                                    b.e_customer_name, 
                                    SUM(c.n_quantity) AS jumlah, 
                                    a.n_notapb_discount,
                                    SUM(a.v_notapb_discount) AS diskon, 
                                    SUM(c.n_quantity*c.v_unit_price) AS kotor, 
                                    a.i_area
                                FROM 
                                    tm_notapb a, 
                                    tr_customer b,
                                    tm_notapb_item c
                                WHERE 
                                    a.i_customer=b.i_customer 
                                    AND a.i_area=b.i_area
                                    AND (a.d_notapb >= to_date('$dfrom','dd-mm-yyyy')
                                    AND a.d_notapb <= to_date('$dto','dd-mm-yyyy')) 
                                    AND a.i_customer='$icustomer'
                                    AND a.i_notapb=c.i_notapb 
                                    AND a.i_customer=c.i_customer 
                                    AND a.i_area=c.i_area
                                GROUP BY 
                                    a.d_notapb, 
                                    a.i_customer, 
                                    b.e_customer_name, 
                                    a.n_notapb_discount, 
                                    a.i_area
                                ORDER BY 
                                    a.d_notapb, 
                                    a.n_notapb_discount"
                                ,false);
		if ($query->num_rows() > 0){
		    return $query->result();
		}
    }

    function bacadiskon($dfrom,$dto,$icustomer){
        $query = $this->db->query(" 
                                SELECT DISTINCT (n_notapb_discount) AS diskon 
                                FROM 
                                    tm_notapb
                                WHERE 
                                    (d_notapb >= to_date('$dfrom','dd-mm-yyyy')
                                    AND d_notapb <= to_date('$dto','dd-mm-yyyy')) 
                                    AND i_customer='$icustomer'
                                ORDER BY 
                                    n_notapb_discount"
                                ,false);
		if ($query->num_rows() > 0){
		    return $query->result();
		}
    }

    function bacatotal($dfrom,$dto,$icustomer){
        $query = $this->db->query(" 
                                SELECT DISTINCT (a.i_customer), 
                                    b.e_customer_name, 
                                    SUM(c.n_quantity) AS totalpcs, 
                                    a.n_notapb_discount,
                                    SUM(c.n_quantity*c.v_unit_price) AS totalkotor, 
                                    a.i_area
                                FROM 
                                    tm_notapb a, 
                                    tr_customer b, 
                                    tm_notapb_item c
                                WHERE 
                                    a.i_customer=b.i_customer 
                                    AND a.i_area=b.i_area
                                    AND (a.d_notapb >= to_date('$dfrom','dd-mm-yyyy')
                                    AND a.d_notapb <= to_date('$dto','dd-mm-yyyy')) 
                                    AND a.i_customer='$icustomer'
                                    AND a.i_notapb=c.i_notapb 
                                    AND a.i_customer=c.i_customer 
                                    AND a.i_area=c.i_area
                                GROUP BY 
                                    a.i_customer, 
                                    b.e_customer_name, 
                                    a.n_notapb_discount, 
                                    a.i_area
                                ORDER BY 
                                    a.n_notapb_discount"
                                ,false);
		if ($query->num_rows() > 0){
		  return $query->result();
		}
    }
}
