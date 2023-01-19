<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('i_area','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $area = true;
        }else{
            $area = false;
        }
        return $area;
    }

    public function data($folder, $siareana, $username, $id_company){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_spmb,
                to_char(d_spmb, 'dd-mm-yyyy') AS d_spmb,
                e_area_name,
                i_spmb_old,
                f_spmb_cancel,
                f_spmb_acc,
                f_spmb_close,
                i_approve2,
                '$folder' AS folder
            FROM
                tm_spmb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND a.f_spmb_cancel = 'f'
                AND f_spmb_acc = 'f'
                AND a.i_approve2 ISNULL
            ORDER BY
                i_spmb
        ", false);
        $datatables->edit('f_spmb_cancel', function($data){
            if($data['f_spmb_cancel'] == "t"){
                return "Batal";
            }elseif($data['f_spmb_acc']!='t'){
                return "Gudang";
            }elseif($data['f_spmb_acc']=='t' && $data['i_approve2']==null){
                return "Acc Gudang";
            }elseif($data['f_spmb_acc']=='t' && $data['i_approve2']!=null) {
                return "Approved Gudang";
            }elseif($data['f_spmb_close']=='t') {
                return "Close";
            }
        });
        $datatables->add('action', function ($data) {
            $ispmb  = trim($data['i_spmb']);
            $dspmb  = trim($data['d_spmb']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ispmb/$dspmb\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        /*$datatables->hide('i_spmb');*/
        $datatables->hide('f_spmb_acc');
        $datatables->hide('f_spmb_close');
        $datatables->hide('i_approve2');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function fspb($ispb, $iarea){
        $this->db->select('f_spb_consigment');
        $this->db->from('tm_spb');
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $fspb = $row->f_spb_consigment;
        }else{
            $fspb = 'f';
        }
        return $fspb;
    }

    public function topspb($isj, $iarea){
        $this->db->select('n_spb_toplength');
        $this->db->from('tm_spb');
        $this->db->where('i_sj', $isj);
        $this->db->where('i_area', $iarea);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $top = $row->n_spb_toplength;
        }else{
            $top = 0;
        }
        return $top;
    }

    public function baca($ispmb){
        $this->db->select("
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
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($ispmb,$fpaw,$fpak,$username,$idcompany){
        /*$this->db->select("
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
                a.n_item_no
        ", false);*/
        $query = $this->db->query("
            SELECT
                a.i_product_motif,
                a.i_product,
                a.e_product_name,
                a.v_unit_price,
                a.n_order,
                a.n_acc,
                x.vrata,
                x.nrata,
                a.e_remark,
                b.e_product_motifname
            FROM
                tm_spmb_item a
            INNER JOIN tr_product_motif b ON
                (a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif)
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
                GROUP BY
                    i_product) AS x ON
                (x.i_product = a.i_product)
            WHERE
                a.i_spmb = '$ispmb'
            ORDER BY
                a.n_item_no
        ", false);
        /*$query = $this->db->get();*/
        if ($query->num_rows() > 0){
            return $query->result();
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

    public function getnota($isj, $iarea){
        $this->db->select('a.*, b.e_area_name, c.e_customer_name');
        $this->db->from('tm_nota a');
        $this->db->join('tr_area b','b.i_area = a.i_area');
        $this->db->join('tr_customer c','c.i_customer = a.i_customer');
        $this->db->where('i_sj', $isj);
        return $this->db->get();
    }

    public function getstore($iareasj){
        $this->db->select('i_store');
        $this->db->from('tr_area');
        $this->db->where('i_area', $iareasj);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $x = $row->i_store;
        }else{
            $x = '';
        }
        return $x;
    }

    public function getholiday($newdate){
        $this->db->select('*');
        $this->db->from('tr_holiday');
        $this->db->where('d_holiday',$newdate);
        return $this->db->get();
    }

    public function cekdaerah($ispb){
        $this->db->select('f_spb_stockdaerah');
        $this->db->from('tm_spb');
        $this->db->where('i_spb',$ispb);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $x   = $row->f_spb_stockdaerah;
        }else{
            $x   = 'f';
        }
        return $x;
    }

    public function stock($istore, $iproduct){
        return $this->db->query("
            SELECT n_quantity_stock
            FROM tm_ic
            WHERE i_product = '$iproduct' 
            AND i_store = '$istore'",false);
    }

    public function updateheader($ispmb, $ispmbold){
        $this->db->set(
            array(
                'f_spmb_acc' => 't',
                'i_spmb_old' => $ispmbold
            )
        );
        $this->db->where('i_spmb',$ispmb);
        $this->db->update('tm_spmb');
    }

    public function deletedetail($iproduct,$iproductgrade,$ispmb,$iproductmotif){
        $this->db->where('i_spmb', $ispmb);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', $iproductmotif);
        $this->db->delete('tm_spmb_item');
    }

    public function insertdetail($ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$nacc,$vunitprice,$iproductmotif,$eremark,$iarea,$i){
        $this->db->set(
            array(
                'i_spmb'          => $ispmb,
                'i_product'       => $iproduct,
                'i_product_grade' => $iproductgrade,
                'i_product_motif' => $iproductmotif,
                'n_order'         => $norder,
                'n_acc'           => $nacc,
                'n_saldo'         => $nacc,
                'v_unit_price'    => $vunitprice,
                'e_product_name'  => $eproductname,
                'i_area'          => $iarea,
                'e_remark'        => $eremark,
                'n_item_no'       => $i
            )
        );        
        $this->db->insert('tm_spmb_item');    
    }
}

/* End of file Mmaster.php */
