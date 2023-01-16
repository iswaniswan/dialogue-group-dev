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

    public function data($folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_spmb,
                to_char(d_spmb, 'dd-mm-yyyy') AS d_spmb,
                e_area_name,
                '$folder' AS folder
            FROM
                tm_spmb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND a.i_approve2 ISNULL
                AND a.f_spmb_cancel = 'f'
                AND f_spmb_acc = 'f'
                AND a.i_approve2 ISNULL
            ORDER BY
                i_spmb
        ", false);
        $datatables->add('action', function ($data) {
            $ispmb  = trim($data['i_spmb']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/approve/$ispmb\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
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
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function approve($ispmb,$eapprove2,$user){
        $dentry = current_datetime();
        $data = array(
            'e_approve2' => $eapprove2,
            'd_approve2' => $dentry,
            'i_approve2' => $user
        );
        $this->db->where('i_spmb', $ispmb);
        $this->db->update('tm_spmb', $data);
    }
}

/* End of file Mmaster.php */
