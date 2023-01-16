<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT DISTINCT 
                a.i_spmb,
                to_char(d_spmb, 'dd-mm-yyyy') AS d_spmb,
                e_area_name,
                '$folder' AS folder
            FROM
                tm_spmb_item b, 
                tm_spmb a, 
                tr_area c
            WHERE
                NOT a.i_approve2 ISNULL
                AND NOT a.i_store ISNULL
                AND NOT a.i_store_location ISNULL
                AND a.f_op = 'f' AND a.f_spmb_pemenuhan='f'
                AND a.f_spmb_close = 'f'
                AND a.f_spmb_cancel = 'f'
                AND a.i_spmb = b.i_spmb 
                AND b.n_order > b.n_stock
                AND a.i_area = c.i_area
            ORDER BY 
                a.i_spmb DESC
        ", false);
        $datatables->add('action', function ($data) {
            $ispmb  = trim($data['i_spmb']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='cek(\"$ispmb\"); return false;'><i class='fa fa-pencil'></i></a>";
            /*$data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ispmb\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";*/
            return $data;
        });
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($ispmb){
        $query = $this->db->query("
            SELECT
                a.*,
                to_char(a.d_spmb, 'dd-mm-yyyy') AS dspmb,
                b.e_area_name
            FROM
                tm_spmb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND i_spmb ='$ispmb'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($ispmb){
        $query = $this->db->query(" 
            SELECT
                a.*,
                b.e_product_motifname
            FROM
                tm_spmb_item a,
                tr_product_motif b
            WHERE
                a.i_spmb = '$ispmb'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.i_product ASC
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }else{
            $query = $this->db->query("
                SELECT
                    a.*,
                    b.e_product_motifname,
                    0 AS n_stock
                FROM
                    tm_spmb_item a,
                    tr_product_motif b
                WHERE
                    a.i_spmb = '$ispmb'
                    AND a.i_product = b.i_product
                    AND a.i_product_motif = b.i_product_motif
                ORDER BY
                    a.i_product ASC
            ", false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }
    }

    public function getproduct($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product,
                c.e_product_name
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')
            ORDER BY
                c.i_product,
                a.e_product_motifname", 
        FALSE);
    } 

    public function getdetailproduct($iproduct,$fpaw,$fpak,$username,$idcompany){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                c.e_product_name AS nama,
                c.v_product_mill AS harga,
                COALESCE(sum(x.vrata),0) AS vrata,
                COALESCE(sum(x.nrata),0) AS nrata
            FROM
                tr_product_motif a
            INNER JOIN tr_product c ON (c.i_product = a.i_product)
            LEFT JOIN (
                SELECT
                    TRUNC(SUM(n_deliver*v_unit_price)/ 3) AS vrata,
                    TRUNC(SUM(n_deliver)/ 3) AS nrata,
                    i_product
                FROM
                    tm_nota_item
                WHERE
                    i_nota > '$fpaw'
                    AND i_nota < '$fpak'
                    AND i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
                    GROUP BY i_product) AS x ON (x.i_product = c.i_product)
            WHERE
                a.i_product = '$iproduct'
            GROUP BY
                a.i_product,
                a.i_product_motif,
                a.e_product_motifname,
                c.e_product_name,
                c.v_product_mill
            ORDER BY
                a.i_product,
                a.e_product_motifname", 
        FALSE);
    } 

    public function stock($iproduct,$iproductgrade,$iproductmotif){
        return $this->db->query("
            SELECT n_quantity_stock
            FROM tm_ic
            WHERE i_product = '$iproduct' 
                AND i_product_grade = '$iproductgrade' 
                AND i_product_motif = '$iproductmotif' 
                AND i_store = 'AA' 
                AND i_store_location = '01' 
                AND i_store_locationbin = '00'
        ",false);
    }

    public function updateheader($ispmb,$fspmbop,$fspmbclose,$fspmbcancel){
        $data = array(
            'f_op'          => $fspmbop,
            'f_spmb_close'  => $fspmbclose,
            'f_spmb_cancel' => $fspmbcancel
        );
        $this->db->where('i_spmb', $ispmb);
        $this->db->update('tm_spmb', $data); 
    }

    public function updatedetail($ispmb,$iproduct,$iproductgrade,$iproductmotif,$nstock,$iarea){
        $this->db->set(
            array(
                'n_stock' => $nstock
            )
        );
        $this->db->where('i_spmb',$ispmb);
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_spmb_item');
    }

    public function spmbsiapsj($ispmb){
        $this->db->set(
            array(
                'f_spmb_pemenuhan'  => 't'
            )
        );
        $this->db->where('i_spmb',$ispmb);
        return $this->db->update('tm_spmb');
    }
}

/* End of file Mmaster.php */
