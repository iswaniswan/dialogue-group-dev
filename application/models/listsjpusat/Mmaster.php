<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE);
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_spb,
                to_char(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                a.i_sj,
                to_char(a.d_sj, 'dd-mm-yyyy') AS d_sj,
                CASE WHEN substr(a.i_customer, 3,3)!='000' THEN '( '||a.i_customer||') - '||c.e_customer_name ELSE c.e_customer_name END AS customer,
                a.i_area,
                a.i_dkb,
                to_char(a.d_dkb, 'dd-mm-yyyy') AS d_dkb,
                a.i_bapb,
                to_char(a.d_bapb, 'dd-mm-yyyy') AS d_bapb,
                a.i_nota,
                a.f_nota_cancel,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$folder' AS folder,
                '$i_menu' AS i_menu
            FROM
                tr_area b,
                tr_customer c,
                tm_spb e,
                tm_nota a
            LEFT JOIN tm_bapb d ON
                (a.i_bapb = d.i_bapb
                AND a.i_area = d.i_area)
            WHERE
                a.i_area = b.i_area
                AND a.i_customer = c.i_customer
                AND substr(a.i_sj, 9, 2) = '00'
                AND a.i_spb = e.i_spb
                AND a.i_area = e.i_area
                AND a.i_area = '$iarea'
                AND (a.d_sj >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_sj <= to_date('$dto', 'dd-mm-yyyy'))
            ORDER BY
                a.i_sj ASC,
                a.d_sj DESC"
        , FALSE);

        $datatables->add('action', function ($data) {
            $id             = trim($data['i_sj']);
            $ispb           = trim($data['i_spb']);
            $i_area         = $data['i_area'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$i_area/$dfrom/$dto/$ispb\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            return $data;
        });

        $datatables->edit('i_sj', function ($data) {
            if ($data['f_nota_cancel']=='f') {
                $data = '<span class="label label-danger label-rouded">'.$data['i_sj'].'</span>';
            }else{
                $data = '<span class="label label-success label-rouded">'.$data['i_sj'].'</span>';
                /*$data = $data['i_sj'];*/
            }
            return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_nota_cancel');
        return $datatables->generate();
    }

    public function baca($isj){
        $query = $this->db->query("
            SELECT
                a.i_nota,
                a.d_sj,
                a.d_spb,
                a.i_sj,
                a.i_area,
                a.i_spb,
                a.i_sj_old,
                a.v_nota_netto,
                a.i_customer,
                c.e_customer_name,
                b.e_area_name,
                d.v_spb,
                a.i_nota
            FROM
                tm_nota a,
                tr_area b,
                tr_customer c,
                tm_spb d
            WHERE
                a.i_area = b.i_area
                AND a.i_customer = c.i_customer
                AND a.i_spb = d.i_spb
                AND a.i_area = d.i_area
                AND a.i_sj = '$isj'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isj){
        $query = $this->db->query("
            SELECT
                a.i_product_motif,
                a.i_product,
                a.e_product_name,
                b.e_product_motifname,
                d.v_unit_price AS harga,
                a.v_unit_price,
                a.n_deliver,
                d.n_order,
                a.i_product_grade,
                d.n_order AS n_qty,
                e.v_spb
            FROM
                tm_nota_item a,
                tr_product_motif b,
                tm_nota c,
                tm_spb_item d,
                tm_spb e
            WHERE
                a.i_sj = '$isj'
                AND a.i_product = b.i_product
                AND a.i_sj = c.i_sj
                AND a.i_area = c.i_area
                AND c.i_spb = e.i_spb
                AND e.i_spb = d.i_spb
                AND c.i_area = e.i_area
                AND c.i_spb = d.i_spb
                AND c.i_area = d.i_area
                AND a.i_product = d.i_product
                AND a.i_product_grade = d.i_product_grade
                AND a.i_product_motif = d.i_product_motif
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetail1($isj,$ispb,$iarea){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_product_motifname
            FROM
                tm_spb_item a,
                tr_product_motif b,
                tm_spb c
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$iarea'
                AND a.i_product NOT IN (
                SELECT
                    i_product
                FROM
                    tm_nota_item
                WHERE
                    i_sj = '$isj'
                    AND i_area = '$iarea')
                AND a.i_spb = c.i_spb
                AND a.i_area = c.i_area
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.i_product
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
