<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username,$idcompany){
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

    public function baca($isj,$iarea){
        $query = $this->db->query(" SELECT
                                        a.i_nota,
                                        a.f_plus_ppn,
                                        a.d_sj,
                                        a.d_spb,
                                        a.i_sj,
                                        a.i_area,
                                        a.i_spb,
                                        a.i_sj_old,
                                        a.v_nota_netto,
                                        a.i_customer,
                                        a.i_dkb,
                                        c.e_customer_name,
                                        b.e_area_name,
                                        to_char(a.d_sj_receive,'dd-mm-yyyy') AS d_sj_receive,
                                        a.e_sj_receive,
                                        a.f_nota_cancel
                                    from
                                        tm_nota a,
                                        tr_area b,
                                        tr_customer c
                                    where
                                        a.i_area = b.i_area
                                        and a.i_customer = c.i_customer
                                        and a.i_sj = '$isj' ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isj,$iarea){
        $query = $this->db->query(" SELECT
                                        a.i_product_motif,
                                        a.i_product,
                                        a.e_product_name,
                                        b.e_product_motifname,
                                        d.v_unit_price as harga,
                                        a.v_unit_price,
                                        a.n_deliver,
                                        d.n_order,
                                        a.i_product_grade,
                                        d.n_order as n_qty
                                    from
                                        tm_nota_item a,
                                        tr_product_motif b,
                                        tm_nota c,
                                        tm_spb_item d
                                    where
                                        a.i_sj = '$isj'
                                        and a.i_product = b.i_product
                                        and a.i_sj = c.i_sj
                                        and a.i_area = c.i_area
                                        and c.i_spb = d.i_spb
                                        and c.i_area = d.i_area
                                        and a.i_product = d.i_product
                                        and a.i_product_grade = d.i_product_grade
                                        and a.i_product_motif = d.i_product_motif
                                        and a.i_product_motif = b.i_product_motif
                                    order by
                                        a.n_item_no ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function data($dfrom,$dto,$iarea,$folder,$imenu){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        #$idepartement = $this->session->userdata('i_departement');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT
                                a.i_sj,
                                a.i_spb,
                                a.i_dkb,
                                to_char(a.d_dkb,'dd-mm-yyyy') AS ddkb,
                                c.e_customer_name,
                                to_char(a.d_sj,'dd-mm-yyyy') AS dsj,
                                to_char(a.d_sj_receive,'dd-mm-yyyy') AS dsjrec,
                                a.i_area,
                                b.e_area_name,
                                a.v_nota_netto AS vsj,
                                '$folder' AS folder,
                                '$imenu' AS i_menu,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto
                            FROM
                                tm_nota a,
                                tr_area b,
                                tr_customer c
                            WHERE
                                a.i_area = b.i_area
                                AND a.d_sj_receive IS NOT NULL
                                AND a.i_customer = c.i_customer
                                AND a.i_area IN (
                                SELECT
                                    i_area
                                FROM
                                    public.tm_user_area
                                WHERE
                                    username = '$username'
                                    AND id_company = '$idcompany')
                                AND a.i_area = '$iarea'
                                AND (a.d_sj >= to_date('$dfrom', 'dd-mm-yyyy')
                                AND a.d_sj <= to_date('$dto', 'dd-mm-yyyy'))
                            ORDER BY
                                a.i_sj desc", FALSE);

        $datatables->edit('vsj', function ($data) {
            return number_format($data['vsj']);
        });

        $datatables->add('action', function ($data) {
            $i_sj               = trim($data['i_sj']);
            $i_area             = $data['i_area'];
            $i_spb              = $data['i_spb'];
            $i_menu             = $data['i_menu'];
            $folder             = $data['folder'];
            $dfrom              = $data['dfrom'];
            $dto                = $data['dto'];
            #$idepartement       = trim($data['idepartement']);
            $data               = '';

            if(check_role($i_menu, 2)||check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_sj/$i_area/$i_spb/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });


        $datatables->hide('i_area');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        #$datatables->hide('idepartement');
        
        return $datatables->generate();
    }
}


/* End of file Mmaster.php */