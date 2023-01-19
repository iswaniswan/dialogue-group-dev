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

    public function cekstatus($idcompany,$username){
        $query = $this->db->select("i_status from public.tm_user where id_company='$idcompany' 
                                     and username='$username'",FALSE);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $ar =  $query->row();
            $status = $ar->i_status;
        }else{
            $status='';
        }
        return $status;
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

    public function data($dfrom,$dto,$iarea,$folder,$i_menu,$status){
        if ($iarea=='') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        SELECT 
        a.i_sjp, c.e_area_name,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$folder' as folder,
                '$status' as status 
        from dgu.tm_sjp a
        inner join dgu.tr_area c on (a.i_area=c.i_area)
        where a.d_sjp >= to_date('$dfrom','dd-mm-yyyy') and a.d_sjp <= to_date('$dto','dd-mm-yyyy')
        $sql
        order by a.i_area, a.i_sjp desc"
        , FALSE);

        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('status');
        $datatables->add('action', function ($data) {
        $folder         = $data['folder'];
        $dfrom          = $data['dfrom'];
        $dto            = $data['dto'];
        $isjp            = $data['i_sjp'];
        $data='';
        // $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/cetak/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
        $data      .= "<a href=\"javascript:yyy('".$isjp."');\"><i class='fa fa-print'></i></a>";
        return $data;
        });
        return $datatables->generate();
    }
    function baca($isj,$area)
    {
        if ($area=='') {
            $sql = "";
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
            $sql = "";
        }else{
            $sql = "and tr_area.i_area='$area'";
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


