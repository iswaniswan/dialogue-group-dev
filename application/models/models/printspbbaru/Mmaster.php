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
            SELECT a.i_spb, b.d_spb, a.e_customer_name, a.i_area, b.i_approve1,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$folder' as folder,
                                '$status' as status
            from dgu.tr_customer_tmp a, dgu.tm_spb b
            where a.i_spb=b.i_spb and a.i_area=b.i_area
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
        // $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/cetak/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
        $data      .= "<a href=\"javascript:yyy('".$ispb."','".$iarea."');\"><i class='fa fa-print'></i></a>";
            return $data;
        });
        $datatables->edit('i_approve1', function ($data) {
            if ($data['i_approve1']!=null) {
                $data = '<span class="label label-success label-rouded">YA</span>';
            }else{
                $data = '<span class="label label-warning label-rouded">TIDAK</span>';
            }
            return $data;
        });
        return $datatables->generate();
    }

    function bacalang($ispb,$iarea)
    {
          $this->db->select("a.i_spb, a.i_area, a.e_customer_contact, a.e_customer_name, a.f_spb_pkp, a.e_customer_address, a.e_customer_owner, a.e_postal1, a.e_customer_owneraddress, a.e_customer_phone, a.e_fax1, a.e_customer_pkpnpwp, a.e_customer_mail, a.e_customer_ownerphone, a.e_customer_ownerhp, a.n_spb_toplength, a.i_salesman, a.n_customer_discount, a.e_customer_refference, a.e_customer_age, a.n_shop_broad, b.e_area_name, c.e_customer_classname, d.e_paymentmethod, e.n_spb_discount1, e.n_spb_discount2,
                          e.n_spb_discount3, e.n_spb_discount4, e.i_price_group, f.e_salesman_name, g.d_spb, g.v_spb, g.v_spb_discounttotal,
                          h.e_shop_status
                          from dgu.tr_area b, dgu.tm_spb g, dgu.tr_customer_tmp a
                          inner join dgu.tr_customer_class c on (c.i_customer_class=a.i_customer_class)
                          inner join dgu.tr_paymentmethod d on(d.i_paymentmethod=a.i_paymentmethod)
                          inner join dgu.tm_spb e on(a.i_spb=e.i_spb and a.i_area=e.i_area)
                          inner join dgu.tr_salesman f on(a.i_salesman=f.i_salesman)
                          left join dgu.tr_shop_status h on(a.i_shop_status=h.i_shop_status)
                          where a.i_spb = '$ispb' and a.i_area='$iarea' and a.i_area=b.i_area and a.i_spb=g.i_spb and a.i_area=g.i_area",false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              return $query->result();
          }
    }
    function baca($ispb,$area)
    {
        $this->db->select(" 
            a.i_spb, a.d_spb, b.e_customer_name,b.e_customer_address,b.e_customer_city, b.f_customer_pkp, b.d_signin, c.e_salesman_name, d.e_customer_classname, e.e_price_groupname, f.i_customer_groupar, g.e_customer_ownername, h.e_customer_pkpname
            from dgu.tm_spb a, dgu.tr_customer b, dgu.tr_salesman c, dgu.tr_customer_class d, dgu.tr_price_group e, dgu.tr_customer_groupar f, dgu.tr_customer_owner g, dgu.tr_customer_pkp h
            where a.i_spb = '$ispb' and a.i_area='$area' 
            and a.i_customer=b.i_customer 
            and a.i_customer=f.i_customer
            and a.i_salesman=c.i_salesman
            and a.i_customer=g.i_customer
            and a.i_customer=h.i_customer
            and (e.n_line=b.i_price_group 
            or e.i_price_group=b.i_price_group)
            and b.i_customer_class=d.i_customer_class
            order by a.i_spb desc",false);
#and (a.n_print=0 or a.n_print isnull)
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    function bacadetail($ispb,$area)
    {
        $this->db->select(" tm_spb_item.i_spb,tm_spb_item.i_product,tm_spb_item.i_product_grade,tm_spb_item.i_product_motif,tm_spb_item.n_order,tm_spb_item.n_deliver,tm_spb_item.n_stock,tm_spb_item.v_unit_price,tm_spb_item.e_product_name,tm_spb_item.i_op,tm_spb_item.i_area,tm_spb_item.e_remark,tm_spb_item.n_item_no,tm_spb_item.i_product_status from tm_spb_item
                    inner join tr_product on (tm_spb_item.i_product=tr_product.i_product)
                    inner join tr_product_motif on (tm_spb_item.i_product_motif=tr_product_motif.i_product_motif
                    and tm_spb_item.i_product=tr_product_motif.i_product)
                    where i_spb = '$ispb' and i_area='$area' order by n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

}


