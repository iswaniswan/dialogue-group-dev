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
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $iarea = $row->i_area;
        }
        return $iarea;
    }

    public function getcustomer($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_customer,
                e_customer_name
            FROM
                tr_customer
            WHERE
                SUBSTRING(i_customer, 1, 2)= 'PB'
                AND f_customer_aktif = 't'
            ORDER BY
                i_customer", 
        FALSE);
    } 

    public function getso($cari, $icustomer){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_sopb,
                to_char(d_sopb, 'dd-mm-yyyy') AS d_sopb
            FROM
                tm_sopb a,
                tr_customer c
            WHERE
                a.f_sopb_cancel = 'f'
                AND a.i_customer = '$icustomer'
                AND a.i_customer = c.i_customer
                AND UPPER(i_sopb) LIKE '%$cari%'
            ORDER BY
                a.i_sopb DESC", 
        FALSE);
    } 

    public function getproduct($cari, $icustomer){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                d.i_product_grade AS grade,
                c.e_product_name AS nama,
                c.v_product_mill AS harga
            FROM
                tr_product_motif a,
                tr_product c,
                tm_ic_consigment d
            WHERE
                a.i_product = c.i_product
                AND c.i_product = d.i_product
                AND d.i_customer = '$icustomer'
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')
            ORDER BY
                c.i_product,
                a.e_product_motifname",
        FALSE);
    } 

    public function getdetailproduct($iproduct, $icustomer){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                d.i_product_grade AS grade,
                c.e_product_name AS nama,
                c.v_product_mill AS harga
            FROM
                tr_product_motif a,
                tr_product c,
                tm_ic_consigment d
            WHERE
                a.i_product = c.i_product
                AND c.i_product = d.i_product
                AND d.i_customer = '$icustomer'
                AND a.i_product = '$iproduct'
            ORDER BY
                c.i_product,
                a.e_product_motifname",
        FALSE);
    } 

    public function runningnumber($thbl,$icustomer){
        $th = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select("
                MAX(SUBSTRING(i_adj, 10, 6)) AS MAX
            FROM
                tm_adjmo
            WHERE
                i_customer = '$icustomer'
                AND SUBSTRING(i_adj, 5, 2)= '$th'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                if($row->max==null)$row->max=0;
                $terakhir=$row->max;
            }
            $noadj  =$terakhir+1;
            settype($noadj,"string");
            $a=strlen($noadj);
            while($a<6){
                $noadj="0".$noadj;
                $a=strlen($noadj);
            }
            $noadj  ="ADJ-".$thbl."-".$noadj;
            return $noadj;
        }else{
            $noadj  ="000001";
            $noadj  ="ADJ-".$thbl."-".$noadj;
            return $noadj;
        }
    }

    public function insertheader($iadj, $icustomer, $dadj, $istockopname, $eremark){
        $now = current_datetime();
        $this->db->set(
            array(
                'i_adj'         => $iadj,
                'i_customer'    => $icustomer,
                'd_adj'         => $dadj,
                'i_stockopname' => $istockopname,
                'e_remark'      => $eremark,
                'd_entry'       => $now
            )
        );
        $this->db->insert('tm_adjmo');
    }

    public function insertdetail($iadj,$icustomer,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i){
        $this->db->set(
            array(
                'i_adj'           => $iadj,
                'i_customer'      => $icustomer,
                'i_product'       => $iproduct,
                'i_product_grade' => $iproductgrade,
                'i_product_motif' => $iproductmotif,
                'n_quantity'      => $nquantity,
                'e_product_name'  => $eproductname,
                'e_remark'        => $eremark,
                'n_item_no'       => $i
            )
        );
        $this->db->insert('tm_adjmo_item');
    }
}

/* End of file Mmaster.php */