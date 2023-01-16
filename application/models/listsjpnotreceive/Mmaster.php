<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_sjp,
                a.i_area,
                to_char(d_sjp, 'dd-mm-yyyy') AS d_sjp, 
                i_bapb,
                to_char(d_bapb, 'dd-mm-yyyy') AS d_bapb, 
                e_area_name,
                CASE WHEN d_sjp_receive ISNULL THEN 'Belum' ELSE 'Sudah' END AS terima,
                CASE WHEN f_sjp_cancel = 'f' THEN 'Tidak' ELSE 'Ya' END AS status,
                '$folder' AS folder
            FROM
                tm_sjp a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND a.f_sjp_cancel = 'f'
                AND a.d_sjp_receive IS NULL
            ORDER BY
                a.i_bapb DESC,
                a.i_area,
                a.i_sjp
        ", false);
        $datatables->add('action', function ($data) {
            $isjp   = trim($data['i_sjp']);
            $iarea  = trim($data['i_area']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjp/$iarea\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($isjp, $iarea){
        $query = $this->db->query("
            SELECT
                DISTINCT(c.i_store),
                a.*,
                b.e_area_name
            FROM
                tm_sjp a,
                tr_area b,
                tm_sjp_item c
            WHERE
                a.i_area = b.i_area
                AND a.i_sjp = c.i_sjp
                AND a.i_area = c.i_area
                AND a.i_sjp = '$isjp'
                AND a.i_area = '$iarea'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isjp, $iarea){
        $query = $this->db->query("
            SELECT
                a.i_sjp,
                a.d_sjp,
                a.i_area,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                a.n_quantity_receive,
                a.n_quantity_deliver,
                a.v_unit_price,
                a.e_product_name,
                a.i_store,
                a.i_store_location,
                a.i_store_locationbin,
                a.e_remark,
                b.e_product_motifname
            FROM
                tm_sjp_item a,
                tr_product_motif b
            WHERE
                a.i_sjp = '$isjp'
                AND a.i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
