<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaperiode($iperiode){
        if($iperiode>'201602'){
            $this->db->select("
                    *
                FROM
                    f_mutasi_stock_mo_pb_saldoakhir('$iperiode')
                WHERE
                    i_product_grade = 'A'
                ORDER BY
                    i_product
            ",false);
        }else{
            $this->db->select("
                    *
                FROM
                    f_mutasi_stock_mo_pb('$iperiode')
                WHERE
                    i_product_grade = 'A'
                ORDER BY
                    i_product
            ",false);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
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
