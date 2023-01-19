<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_spmb,
                to_char(d_spmb, 'dd-mm-yyyy') AS d_spmb,
                e_area_name,
                i_spmb_old,
                b.i_area,
                '$folder' AS folder
            FROM
                tm_spmb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND NOT i_approve2 ISNULL
                AND a.i_store ISNULL
                AND i_store_location ISNULL
                AND f_spmb_cancel = 'f'
                AND f_spmb_close = 'f'
                AND f_spmb_acc = 't'
            ORDER BY
                i_spmb
        ", false);
        $datatables->add('action', function ($data) {
            $ispmb  = trim($data['i_spmb']);
            $iarea  = trim($data['i_area']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ispmb/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($ispmb,$iarea){
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
                AND b.i_area = '$iarea'
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

    public function updateheader($ispmb,$istore,$istorelocation,$fspmbcancel,$fspmbclose){
        $data = array(
            'i_store'          => $istore,
            'i_store_location' => $istorelocation,
            'f_spmb_close'     => $fspmbclose,
            'f_spmb_cancel'    => $fspmbcancel,
            'f_spmb_pemenuhan' => 'f'
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

    public function langsungclose($ispmb){
        $data = array(
            'f_spmb_pemenuhan'=>'t'
        );
        $this->db->where('i_spmb', $ispmb);
        $this->db->update('tm_spmb', $data); 
    }
}

/* End of file Mmaster.php */
