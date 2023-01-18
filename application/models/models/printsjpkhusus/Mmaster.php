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

    public function cekarea(){
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
            return '00';
        }else{
            return 'XX';
        }
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu,$xarea){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if ($xarea=='00') {
            $sql = "";
        }else{
            $sql = "
                    AND a.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
            ";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        SELECT 
        a.i_sjp,c.e_area_name, 
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$folder' as folder
        from dgu.tm_sjp a
        inner join dgu.tr_area c on (a.i_area=c.i_area)
        where a.d_sjp >= to_date('$dfrom','dd-mm-yyyy') and a.d_sjp <= to_date('$dto','dd-mm-yyyy')
        $sql
        order by a.i_sjp desc
        ", FALSE);
        $datatables->add('action', function ($data) {
            $isjp     = $data['i_sjp'];
            $folder         = $data['folder'];
            $data           = '';
            $data      .= "<a href=\"javascript:yyy('".$isjp."');\"><i class='fa fa-print'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    function baca($isj,$area)
    {
        if ($area=='') {
            $sql = '';
        }else{
            $sql = "and tr_area.i_area='$area'";
        }
        $this->db->select(" tm_sjp.*, tr_area.e_area_name, tm_spmb.i_spmb  from dgu.tm_sjp
                                      inner join dgu.tr_area on (tm_sjp.i_area=tr_area.i_area)
                                      left join dgu.tm_spmb on (tm_spmb.i_spmb=tm_sjp.i_spmb and tm_spmb.i_area=tm_sjp.i_area)
                                      where tm_sjp.i_sjp = '$isj' $sql",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    function bacadetail($isj,$area)
    {
        if ($area=='') {
            $sql = '';
        }else{
            $sql = "and i_area='$area'";
        }
        $this->db->select(" * from dgu.tm_sjp_item
                          inner join dgu.tr_product_motif on (tm_sjp_item.i_product_motif=tr_product_motif.i_product_motif
                          and tm_sjp_item.i_product=tr_product_motif.i_product)
                          where i_sjp = '$isj' $sql order by n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function close($id,$iarea){
        return $this->db->query("
            UPDATE
                tm_spb
            SET
                n_print = n_print + 1
            WHERE
                i_spb = '$id'
                AND i_area = '$iarea'
        ",false);
    }
    public function company($id_company){
        return $this->db->query("
            SELECT
                *
            FROM
                public.company
            WHERE
                id = '$id_company'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
