<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_sjpb,
                a.i_area,
                to_char(d_sjpb, 'dd-mm-yyyy') AS d_sjpb, 
                i_sjp,
                to_char(d_sjp, 'dd-mm-yyyy') AS d_sjp, 
                i_entry,
                CASE WHEN d_sjpb_receive ISNULL THEN 'Belum' ELSE 'Sudah' END AS terima,
                CASE WHEN f_sjpb_cancel = 'f' THEN 'Tidak' ELSE 'Ya' END AS status,
                '$folder' AS folder
            FROM
                tm_sjpb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND a.f_sjpb_cancel = 'f'
                AND a.d_sjpb_receive IS NULL
            ORDER BY
                a.i_sjpb DESC
        ", false);
        $datatables->add('action', function ($data) {
            $isjpb  = trim($data['i_sjpb']);
            $iarea  = trim($data['i_area']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjpb/$iarea\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($isjpb, $iarea){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_area_name
            FROM
                tm_sjpb a,
                tr_area b,
                tm_sjpb_item c
            WHERE
                a.i_area = b.i_area
                AND a.i_sjpb = c.i_sjpb
                AND a.i_area = c.i_area
                AND a.i_sjpb = '$isjpb'
                AND a.i_area = '$iarea'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isjpb, $iarea){
        $query = $this->db->query("
            SELECT
                a.i_sjpb,
                a.d_sjpb,
                a.i_area,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                a.n_receive,
                a.n_deliver,
                a.v_unit_price,
                a.e_product_name,
                b.e_product_motifname
            FROM
                tm_sjpb_item a,
                tr_product_motif b
            WHERE
                a.i_sjpb = '$isjpb'
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
