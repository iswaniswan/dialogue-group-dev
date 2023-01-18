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
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT a.i_area, a.i_spb, b.d_spb, a.e_customer_name,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$folder' as folder,
                                '$status' as status
            from dgu.tr_customer_tmp a, dgu.tm_spb b
            where upper(a.i_customer) like '%000' and a.i_spb=b.i_spb and a.i_area=b.i_area
                $sql
                AND (b.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND b.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy'))"
        , FALSE);

        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('status');
        $datatables->add('action', function ($data) {
        $folder         = $data['folder'];
        $dfrom          = $data['dfrom'];
        $dto            = $data['dto'];
        $ispb           = $data['i_spb'];
        $iarea          = $data['i_area'];
        $data='';
#        $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/cetak/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
	$data      .= "<a href=\"javascript:yyy('".$ispb."','".$iarea."');\"><i class='fa fa-print'></i></a>";
            return $data;
        });
        return $datatables->generate();
    }

    function bacalang($ispb,$area)
    {
          $this->db->select(" a.*, b.e_area_name, c.e_customer_classname, d.e_paymentmethod, e.n_spb_discount1, e.n_spb_discount2
                          , e.n_spb_discount3, e.n_spb_discount4, e.i_price_group, f.e_salesman_name
                          from dgu.tr_area b, dgu.tr_customer_tmp a
                          inner join dgu.tr_customer_class c on (c.i_customer_class=a.i_customer_class)
                          inner join dgu.tr_paymentmethod d on(d.i_paymentmethod=a.i_paymentmethod)
                          inner join dgu.tm_spb e on(a.i_spb=e.i_spb and a.i_area=e.i_area)
                          inner join dgu.tr_salesman f on(a.i_salesman=f.i_salesman)
                          where a.i_spb = '$ispb' and a.i_area='$area' and a.i_area=b.i_area",false);
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


