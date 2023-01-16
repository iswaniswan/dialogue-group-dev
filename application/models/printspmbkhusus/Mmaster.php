<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cekarea = $this->db->query("
            SELECT
                *
            FROM
                public.tm_user_area
            WHERE
                username = '$username'
                AND id_company = '$idcompany'
                AND i_area = '00'
        ", FALSE);
        if ($cekarea->num_rows()>0) {
            $sql = "";
        }else{
            $sql = "AND a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'
            )";
        }
        $thbl = date('Ym');
        $iperiode = date('Ym', strtotime('-1 month', strtotime($thbl)));
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_spmb,
                to_char(d_spmb, 'dd-mm-yyyy') AS d_spmb, 
                e_area_name,
                f_spmb_cancel,
                n_print,
                f_spmb_acc,
                i_approve2,
                f_spmb_close
            FROM
                tm_spmb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND to_char(d_spmb, 'yyyymm') >= '202001' 
                $sql
            ORDER BY
                a.i_spmb DESC
        ", FALSE);
        $datatables->add('action', function ($data) {
            $id             = trim($data['i_spmb']);
            $n_print        = $data['n_print'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='printx(\"$id\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
            return $data;
        });

        $datatables->edit('f_spmb_cancel', function ($data) {
            if ($data['f_spmb_cancel'] =='t') {
                $status = 'BATAL';
            }elseif ($data['f_spmb_acc'] != 't') {
                $status = 'GUDANG';
            }elseif (($data['f_spmb_acc'] == 't') && ($data['i_approve2'] == null)) {
                $status = 'ACC GUDANG';
            }elseif (($data['f_spmb_acc'] == 't') && ($data['i_approve2'] != null)) {
                $status = 'APPROVED GUDANG';
            }elseif ($data['f_spmb_close'] == 't') {
                $status = 'CLOSE';
            }
            $data = '<span class="label label-inverse label-rouded">'.strtoupper($status).'</span>';
            return $data;
        });

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print']=='0') {
                $data = '<span class="label label-info label-rouded">BELUM</span>';
            }else{
                $data = '<span class="label label-success label-rouded">SUDAH</span>';
            }
            return $data;
        });
        $datatables->hide('f_spmb_acc');
        $datatables->hide('i_approve2');
        $datatables->hide('f_spmb_close');
        return $datatables->generate();
    }

    public function baca($id){   
        $this->db->select("
                *
            FROM
                tm_spmb
            INNER JOIN tr_area ON
                (tm_spmb.i_area = tr_area.i_area)
            WHERE
                tm_spmb.i_spmb = '$id'
            ORDER BY
                tm_spmb.i_spmb DESC
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacadetail($id){
        $this->db->select(" 
                *
            FROM
                tm_spmb_item
            INNER JOIN tr_product_motif ON
                (tm_spmb_item.i_product_motif = tr_product_motif.i_product_motif
                AND tm_spmb_item.i_product = tr_product_motif.i_product)
            WHERE
                i_spmb = '$id'
            ORDER BY
                tm_spmb_item.i_product ASC"
        ,false);        
        $query = $this->db->get();        
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function close($id){
        return $this->db->query("
            UPDATE
                tm_spmb
            SET
                n_print = n_print + 1
            WHERE
                i_spmb = '$id'
        ",false);
    }
}

/* End of file Mmaster.php */
