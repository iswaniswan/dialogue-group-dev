<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $this->db->select('i_area, e_area_name');
        $this->db->from('tr_area');
        $this->db->where('f_area_real', 't');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return $query->result();
        }
    }

    public function bacaproductnya($iperiode){
        $this->db->distinct();
        $this->db->select('i_product, e_product_name, e_product_groupname');
        $this->db->from("vpenjualanperprodukspb");
        $this->db->where("i_periode",$iperiode);
        $this->db->order_by("e_product_groupname","ASC");
        $this->db->order_by("i_product","ASC");
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacaperiode($iperiode){
        $query = $this->db->query("
            SELECT
                a.i_area,
                a.i_product,
                a.e_product_name,
                b.e_area_name,
                e.i_product_group,
                SUM(a.v_unit_price*a.n_order) AS nilai,
                SUM(a.n_order) AS jumlah
            FROM
                tm_spb c,
                tm_spb_item a,
                tr_area b,
                tr_product d,
                tr_product_type e
            WHERE
                c.f_spb_cancel = 'f'
                AND c.i_spb = a.i_spb
                AND c.i_area = a.i_area
                AND c.i_area = b.i_area
                AND TO_CHAR(c.d_spb, 'yyyymm')= '$iperiode'
                AND a.i_product = d.i_product
                AND d.i_product_type = e.i_product_type
            GROUP BY
                a.i_area,
                e.i_product_group,
                a.i_product,
                a.e_product_name,
                b.e_area_name
            ORDER BY
                a.i_area,
                e.i_product_group,
                a.i_product,
                a.e_product_name,
                b.e_area_name
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
