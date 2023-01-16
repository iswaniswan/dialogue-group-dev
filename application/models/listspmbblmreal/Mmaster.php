<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea()
    {
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
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
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'NA';
        }else{
            return 'XX';
        }
    }
    
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

    public function baca($ispmb){
        $query = $this->db->query(" SELECT
                                        DISTINCT(a.i_spmb) AS i_spmb,
                                        to_char(a.d_spmb,'dd-mm-yyyy') AS d_spmb,
                                        a.i_area,
                                        c.e_area_name,
                                        a.e_remark,
                                        a.i_spmb_old
                                    from
                                        tm_spmb a,
                                        tm_spmb_item b,
                                        tr_area c
                                    where
                                        a.i_spmb = b.i_spmb
                                        AND a.i_area = b.i_area
                                        AND NOT a.i_approve2 ISNULL
                                        AND NOT a.i_store ISNULL
                                        AND b.n_deliver<b.n_saldo
                                        AND a.i_spmb = '$ispmb'
                                        AND a.i_area = c.i_area
                                    ORDER BY
                                        a.i_spmb ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($ispmb){
        $query = $this->db->query(" SELECT
                                        a.i_spmb,
                                        to_char(a.d_spmb,'dd-mm-yyyy') AS d_spmb,
                                        a.i_area,
                                        b.i_product,
                                        b.e_product_name,
                                        b.n_order,
                                        b.n_acc,
                                        b.n_saldo
                                    FROM
                                        tm_spmb a,
                                        tm_spmb_item b
                                    WHERE
                                        a.i_spmb = b.i_spmb
                                        AND a.i_area = b.i_area
                                        AND NOT a.i_approve2 ISNULL
                                        AND NOT a.i_store ISNULL
                                        AND b.n_deliver<b.n_saldo
                                        AND a.i_spmb = '$ispmb'
                                    ORDER BY
                                        b.n_item_no ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function data($iarea,$folder,$imenu){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        #$idepartement = $this->session->userdata('i_departement');

        /* if ($iarea=='NA') {
            $sql = "";
            $areax = "NA";
        }else{
            $sql = "AND a.i_area = '$iarea'";
            $areax = $iarea;
        } */

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT
                                DISTINCT(a.i_spmb) AS i_spmb,
                                TO_CHAR(a.d_spmb,'dd-mm-yyyy') as d_spmb,
                                a.i_area,
                                c.e_area_name,
                                a.f_spmb_consigment,
                                a.f_spmb_cancel,
                                '$imenu' AS i_menu,
                                '$folder' AS folder
                            FROM
                                tm_spmb a,
                                tm_spmb_item b,
                                tr_area c
                            WHERE
                                a.i_spmb = b.i_spmb
                                AND a.i_area = b.i_area
                                AND NOT a.i_approve2 ISNULL
                                AND NOT a.i_store ISNULL
                                AND b.n_deliver<b.n_saldo
                                AND a.i_area = '$iarea'
                                AND a.i_area = c.i_area
                            ORDER BY
                                a.i_spmb DESC", FALSE);

        $datatables->edit('f_spmb_consigment', function ($data) {
            if ($data['f_spmb_consigment']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->edit('i_spmb', function ($data) {
            if ($data['f_spmb_cancel']=='t') {
                $data = '<p class="h2 text-danger">'.$data['i_spmb'].'</p>';
            }else{
                $data = $data['i_spmb'];
            }
            return $data;
        });

        $datatables->add('action', function ($data) {
            $i_spmb             = trim($data['i_spmb']);
            $i_area             = $data['i_area'];
            $i_menu             = $data['i_menu'];
            $folder             = $data['folder'];
            $data               = '';

            if(check_role($i_menu, 2)||check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_spmb/$i_area/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('f_spmb_cancel');
        #$datatables->hide('idepartement');
        
        return $datatables->generate();
    }
}


/* End of file Mmaster.php */