<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacadiskon($iperiode){
        $query = $this->db->query("
            SELECT
                DISTINCT(n_notapb_discount) AS diskon
            FROM
                tm_notapb
            WHERE
                TO_CHAR(d_notapb::TIMESTAMP WITH TIME ZONE, 'yyyymm'::TEXT)= '$iperiode'
            ORDER BY
                n_notapb_discount
        ",FALSE);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacaperiode($iperiode){
        $query = $this->db->query("
            SELECT
                DISTINCT(a.i_customer),
                b.e_customer_name,
                SUM(c.n_quantity) AS jumlah,
                a.n_notapb_discount,
                SUM(a.v_notapb_discount) AS diskon,
                SUM(a.v_notapb_gross) AS kotor,
                a.i_area
            FROM
                tm_notapb a,
                tr_customer b,
                tm_notapb_item c
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = b.i_area
                AND TO_CHAR(a.d_notapb::TIMESTAMP WITH TIME ZONE, 'yyyymm'::TEXT)= '$iperiode'
                AND a.i_notapb = c.i_notapb
                AND a.i_customer = c.i_customer
                AND a.i_area = c.i_area
                AND a.f_spb_rekap = 't'
                AND NOT a.i_cek IS NULL
                AND (NOT a.i_spb IS NULL
                OR TRIM(a.i_spb)!= '')
            GROUP BY
                a.i_customer,
                b.e_customer_name,
                a.n_notapb_discount,
                a.i_area
            ORDER BY
                a.i_customer,
                a.n_notapb_discount
        ",FALSE);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
