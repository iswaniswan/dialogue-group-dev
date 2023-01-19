<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom,$dto,$icustomer,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
           SELECT
                c.i_customer,
                e_customer_name,
                i_adj AS id,
                to_char(d_adj, 'dd-mm-yyyy') AS d_adj,
                e_remark,
                f_adj_cancel AS status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' AS i_menu, 
                '$folder' AS folder
            FROM
                tr_customer c,
                tm_adjmo a
            WHERE
                a.i_customer = c.i_customer
                AND a.i_customer = '$icustomer'
                AND a.d_adj >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_adj <= to_date('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.d_adj,
                a.i_customer,
                a.i_adj DESC"
        , FALSE);

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $status         = $data['status'];
            $i_customer     = $data['i_customer'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$i_customer/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $status != 't'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$i_customer\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('status', function ($data) {
            if ($data['status']!='t') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->hide('i_customer');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function baca($iadj,$icustomer){
        $this->db->select("a.*, b.e_customer_name from tm_adjmo a, tr_customer b 
                           where a.i_customer=b.i_customer
                           and i_adj ='$iadj' and a.i_customer='$icustomer'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($iadj,$icustomer){
        $this->db->select(" a.*, b.e_product_motifname from tm_adjmo_item a, tr_product_motif b
           where a.i_adj = '$iadj' and i_customer='$icustomer' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
           order by a.n_item_no ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function cancel($iadj,$icustomer){
        $this->db->query("
            UPDATE
                tm_adjmo
            SET
                f_adj_cancel = 't'
            WHERE
                i_adj = '$iadj'
                AND i_customer = '$icustomer'
        ", FALSE);
    }

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
                substring(i_customer, 1, 2)= 'PB'
                AND f_customer_aktif = 't'
                AND (i_customer LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%')
            ORDER BY
                e_customer_name", 
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

    public function updateheader($iadj, $icustomer, $dadj, $istockopname, $eremark){
        $now = current_datetime();
        $this->db->set(
            array(
                'd_adj'         => $dadj,
                'i_stockopname' => $istockopname,
                'e_remark'      => $eremark,
                'd_update'      => $now
            )
        );
        $this->db->where('i_adj',$iadj);
        $this->db->where('i_customer',$icustomer);
        $this->db->update('tm_adjmo');
    }

    public function updatedetail($iadj,$icustomer,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i){
        $this->db->set(
            array(
                'n_quantity'     => $nquantity,
                'e_product_name' => $eproductname,
                'e_remark'       => $eremark,
                'n_item_no'      => $i
            )
        );
        $this->db->where('i_adj',$iadj);
        $this->db->where('i_customer',$icustomer);
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->update('tm_adjmo_item');
    }

    public function deletedetail($iadj,$icustomer,$iproduct,$iproductmotif,$iproductgrade){
        $this->db->query("DELETE FROM tm_adjmo_item WHERE i_adj='$iadj' and i_customer='$icustomer' and i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }
}

/* End of file Mmaster.php */