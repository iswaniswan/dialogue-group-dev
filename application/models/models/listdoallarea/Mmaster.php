<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($bulan,$tahun,$folder,$i_menu){
        $iperiode = $tahun.$bulan;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_do,
                to_char(a.d_do, 'dd-mm-yyyy') AS d_do,
                a.i_supplier,
                a.i_area,
                a.i_op,
                c.i_spb,
                d.i_spmb,
                e.e_supplier_name,
                CASE WHEN a.f_do_cancel = 'TRUE' THEN 'Ya' ELSE 'Tidak' END AS status,
                '$iperiode' AS iperiode,
                '$folder' AS folder
            FROM
                tm_do a,
                tr_supplier e,
                tm_op b
            LEFT JOIN tm_spb c ON
                (b.i_reff = c.i_spb
                AND b.i_area = c.i_area)
            LEFT JOIN tm_spmb d ON
                (b.i_reff = d.i_spmb
                AND b.i_area = d.i_area)
            WHERE
                to_char(a.d_do::timestamp WITH time ZONE, 'yyyymm'::TEXT)= '$iperiode'
                AND a.i_op = b.i_op
                AND a.i_area = b.i_area
                AND a.i_supplier = e.i_supplier
            ORDER BY
                e.e_supplier_name,
                a.i_do,
                a.i_area"
        , FALSE);

        $datatables->add('action', function ($data) {
            $i_do        = trim($data['i_do']);
            $i_supplier  = trim($data['i_supplier']);
            $iperiode    = trim($data['iperiode']);
            $folder      = trim($data['folder']);
            $data        = '';
            $data       .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_do/$i_supplier/$iperiode\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            return $data;
        });
        $datatables->hide('i_supplier');
        $datatables->hide('iperiode');
        $datatables->hide('folder');
        return $datatables->generate();
    }
    
    public function baca($ido,$isupplier){
        $query = $this->db->query("
            SELECT
                a.*,
                b.*,
                c.*
            FROM
                tm_do a,
                tr_supplier b,
                tr_area c
            WHERE
                a.i_supplier = b.i_supplier
                AND a.i_area = c.i_area
                AND a.i_do = '$ido'
                AND a.i_supplier = '$isupplier'", FALSE);
        if ($query->num_rows() > 0){          
            return $query->row();
        }
    }

    public function bacadetail($ido,$isupplier){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_product_motifname,
                c.n_order
            FROM
                tm_do_item a,
                tr_product_motif b,
                tm_op_item c
            WHERE
                a.i_do = '$ido'
                AND i_supplier = '$isupplier'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
                AND a.i_product = c.i_product
                AND a.i_op = c.i_op
            ORDER BY
                a.i_product
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
