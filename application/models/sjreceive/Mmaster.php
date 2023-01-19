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
                a.i_sj,
                to_char(a.d_sj, 'dd-mm-yyyy') AS d_sj,
                c.i_customer||' - '||c.e_customer_name AS customer,
                b.i_area ||' - '||b.e_area_name AS area,
                a.i_nota,
                a.f_nota_cancel,
                b.i_area,
                a.i_spb,
                '$folder' AS folder
            FROM
                tm_nota a,
                tr_area b,
                tr_customer c
            WHERE
                a.i_area = b.i_area
                AND a.d_sj_receive IS NULL
                AND NOT a.i_dkb IS NULL
                AND (UPPER(a.i_sj) LIKE '%%')
                AND a.f_nota_cancel = 'f'
                AND a.i_customer = c.i_customer
                AND (a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$id_company'))
                AND a.d_sj >= '2019-01-01'
            ORDER BY
                a.i_sj ASC
        ", false);
        $datatables->edit('f_nota_cancel', function($data){
            if($data['f_nota_cancel'] == "t"){
                return "Ya";
            }else{
                return "Tidak";
            }
        });
        $datatables->add('action', function ($data) {
            $isj    = trim($data['i_sj']);
            $ispb   = trim($data['i_spb']);
            $iarea  = trim($data['i_area']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isj/$iarea/$ispb\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('i_spb');
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

    public function baca($isj, $iarea){
        $this->db->select("
                a.i_nota,
                a.f_plus_ppn,
                to_char(a.d_sj, 'dd-mm-yyyy') AS dsj,
                a.d_sj,
                to_char(a.d_spb, 'dd-mm-yyyy') AS dspb,
                a.d_spb,
                a.i_sj,
                a.i_area,
                a.i_spb,
                a.i_sj_old,
                a.v_nota_netto,
                a.i_customer,
                to_char(a.d_dkb, 'dd-mm-yyyy') AS d_dkb,
                c.e_customer_name,
                b.e_area_name,
                a.d_sj_receive,
                a.e_sj_receive,
                a.i_dkb
            FROM
                tm_nota a,
                tr_area b,
                tr_customer c
            WHERE
                a.i_area = b.i_area
                AND a.i_customer = c.i_customer
                AND a.i_sj = '$isj'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isj, $iarea){
        $this->db->select("
                a.i_product_motif,
                a.i_product,
                a.e_product_name,
                b.e_product_motifname,
                d.v_unit_price AS harga,
                a.v_unit_price,
                a.n_deliver,
                d.n_order,
                a.i_product_grade,
                d.n_order AS n_qty
            FROM
                tm_nota_item a,
                tr_product_motif b,
                tm_nota c,
                tm_spb_item d
            WHERE
                a.i_sj = '$isj'
                AND a.i_product = b.i_product
                AND a.i_sj = c.i_sj
                AND a.i_area = c.i_area
                AND c.i_spb = d.i_spb
                AND c.i_area = d.i_area
                AND a.i_product = d.i_product
                AND a.i_product_grade = d.i_product_grade
                AND a.i_product_motif = d.i_product_motif
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
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

    public function stock1($thbl, $iproduct, $iproductgrade){
        return $this->db->query("
            SELECT n_saldo_akhir AS qty 
            FROM f_mutasi_stock_pusat_saldoakhir('$thbl') 
            WHERE i_product = '$iproduct' 
            AND i_product_grade = '$iproductgrade'",false);
    }

    public function stock2($thbl, $iproduct, $iproductgrade, $istore){
        return $this->db->query("
            SELECT n_saldo_akhir AS qty
            FROM f_mutasi_stock_daerah_all_saldoakhir('$thbl') 
            WHERE i_product = '$iproduct' 
            AND i_product_grade = '$iproductgrade'
            AND i_store = '$istore' ",false);
    }

    public function updatesj($isj,$iarea,$dsjreceive,$eremark,$dudet){
        $now = current_datetime();
        $tmp = explode("-",$dsjreceive);
        $th  = $tmp[2];
        $bl  = $tmp[1];
        $hr  = $tmp[0];
        $dsjreceive = $th."-".$bl."-".$hr;
        $cek_sj = $this->db->query("select d_sj from tm_nota where i_sj='$isj' and i_area ='$iarea'")->row();
        if($cek_sj->d_sj < '2019-10-01'){
            $this->db->query("update tm_nota set d_sj_receive='$dsjreceive', e_sj_receive='$eremark', d_sj_receiveentry='$now'
                where i_sj='$isj' and i_area ='$iarea'",false);
        }else{
            $this->db->query("update tm_nota set d_sj_receive='$dsjreceive', e_sj_receive='$eremark', d_sj_receiveentry='$now', d_jatuh_tempo = '$dudet'
                where i_sj='$isj' and i_area ='$iarea'",false);
        }
    }
}

/* End of file Mmaster.php */
