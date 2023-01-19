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

    public function bacaarea($username,$idcompany){
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

    public function data($dfrom,$dto,$iarea){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "WHERE i_area = '$iarea'";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                e_product_groupname,
                i_area||' - '||e_area_name AS area,
                e_city_name,
                i_spb,
                promo,
                d_spb,
                i_customer ||' - '|| e_customer_name AS customer,
                i_salesman ||' - '|| e_salesman_name AS salesman,
                e_product_categoryname,
                i_product,
                e_product_name,
                n_order,
                n_deliver,
                v_unit_price,
                i_sj,
                i_nota,
                d_nota
            FROM
                (
                SELECT
                    g.e_product_groupname,
                    a.i_salesman,
                    f.i_area,
                    f.e_area_name,
                    a.i_spb,
                    TO_CHAR(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                    a.i_customer,
                    e.e_customer_name,
                    b.i_product,
                    b.e_product_name,
                    b.n_order,
                    d.n_deliver,
                    b.v_unit_price,
                    c.i_nota,
                    c.i_sj,
                    TO_CHAR(c.d_nota, 'dd-mm-yyyy') AS d_nota,
                    a.i_spb_program AS promo,
                    j.e_salesman_name,
                    k.e_city_name,
                    l.e_product_categoryname
                FROM
                    tr_customer e,
                    tr_area f,
                    tr_product_group g,
                    tr_product h,
                    tr_product_type i,
                    tm_spb a,
                    tr_salesman j,
                    tr_city k,
                    tr_product_category l,
                    tm_spb_item b
                LEFT JOIN tm_nota c ON
                    (b.i_spb = c.i_spb
                    AND b.i_area = c.i_area
                    AND c.f_nota_cancel = 'f'
                    AND TO_DATE('$dfrom', 'dd-mm-yyyy')<= c.d_sj)
                LEFT JOIN tm_nota_item d ON
                    (c.i_sj = d.i_sj
                    AND c.i_area = d.i_area
                    AND d.i_product = b.i_product)
                WHERE
                    a.i_spb = b.i_spb
                    AND a.i_area = b.i_area
                    AND a.i_customer = e.i_customer
                    AND a.f_spb_consigment = 'f'
                    AND a.i_area = f.i_area
                    AND f_spb_cancel = 'f'
                    AND (TO_DATE('$dfrom', 'dd-mm-yyyy')<= a.d_spb
                    AND TO_DATE('$dto', 'dd-mm-yyyy')>= a.d_spb)
                    AND b.i_product = h.i_product
                    AND h.i_product_type = i.i_product_type
                    AND i.i_product_group = g.i_product_group
                    AND a.i_salesman = j.i_salesman
                    AND a.i_area = k.i_area
                    AND e.i_city = k.i_city
                    AND e.i_area = k.i_area
                    AND h.i_product_category = l.i_product_category
                    AND h.i_product_class = l.i_product_class
            UNION ALL
                SELECT
                    'Konsinyasi' AS e_product_groupname,
                    a.i_salesman,
                    f.i_area,
                    f.e_area_name,
                    a.i_spb,
                    TO_CHAR(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                    a.i_customer,
                    e.e_customer_name,
                    b.i_product,
                    b.e_product_name,
                    b.n_order,
                    d.n_deliver,
                    b.v_unit_price,
                    c.i_nota ,
                    c.i_sj,
                    TO_CHAR(c.d_nota, 'dd-mm-yyyy') AS d_nota,
                    a.i_spb_program AS promo,
                    j.e_salesman_name,
                    k.e_city_name,
                    l.e_product_categoryname
                FROM
                    tr_customer e,
                    tr_area f,
                    tm_spb a,
                    tr_salesman j,
                    tr_product h,
                    tr_city k,
                    tr_product_category l,
                    tm_spb_item b
                LEFT JOIN tm_nota c ON
                    (b.i_spb = c.i_spb
                    AND b.i_area = c.i_area
                    AND c.f_nota_cancel = 'f'
                    AND TO_DATE('$dfrom', 'dd-mm-yyyy')<= c.d_sj)
                LEFT JOIN tm_nota_item d ON
                    (c.i_sj = d.i_sj
                    AND c.i_area = d.i_area
                    AND d.i_product = b.i_product)
                WHERE
                    a.i_spb = b.i_spb
                    AND a.i_area = b.i_area
                    AND a.i_customer = e.i_customer
                    AND a.f_spb_consigment = 't'
                    AND a.i_area = f.i_area
                    AND f_spb_cancel = 'f'
                    AND a.i_salesman = j.i_salesman
                    AND a.i_area = k.i_area
                    AND e.i_city = k.i_city
                    AND e.i_area = k.i_area
                    AND b.i_product = h.i_product
                    AND h.i_product_category = l.i_product_category
                    AND h.i_product_class = l.i_product_class
                    AND (TO_DATE('$dfrom', 'dd-mm-yyyy')<= a.d_spb
                    AND TO_DATE('$dto', 'dd-mm-yyyy')>= a.d_spb) )AS a
            $sql
            ORDER BY
                a.i_area,
                a.i_customer,
                a.i_spb"
        , FALSE);
        /*$datatables->edit('pending', function ($data) {
            return number_format($data['pending'],2);
        });*/
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
