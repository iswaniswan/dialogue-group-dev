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
            $sql = "AND tm_nota.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        SELECT 
        tm_nota.i_sj as i_sj,
        tm_nota.d_sj, 
        c.e_customer_name, 
        tr_area.e_area_name,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$folder' as folder,
                '$status' as status 
        from dgu.tm_nota 
        inner join dgu.tr_customer c on (tm_nota.i_customer=c.i_customer)
        inner join dgu.tr_area on (substring(tm_nota.i_sj,9,2)=tr_area.i_area)
        where tm_nota.d_sj >= to_date('$dfrom','dd-mm-yyyy') and tm_nota.d_sj <= to_date('$dto','dd-mm-yyyy')
        $sql
        order by tm_nota.i_sj desc"
        , FALSE);

        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('status');
        $datatables->add('action', function ($data) {
        $folder         = $data['folder'];
        $dfrom          = $data['dfrom'];
        $dto            = $data['dto'];
        $isj            = $data['i_sj'];
        $data='';
        // $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/cetak/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
        $data      .= "<a href=\"javascript:yyy('".$isj."');\"><i class='fa fa-print'></i></a>";
        if($this->session->userdata("user_id") == 'ani'){
            $data = "<a href=\"javascript:xxx('".$isj."');\"><i class='fa fa-print'></i></a>";
            }
            return $data;
        });
        return $datatables->generate();
    }function baca($isj,$area)
    {
        $this->db->select(" tm_nota.i_sj,
            tm_nota.d_sj,
            tm_nota.i_spb,
            tm_nota.n_nota_discount1,
            tm_nota.n_nota_discount2,
            tm_nota.n_nota_discount3,
            tm_nota.n_nota_discount4,
            tm_nota.v_nota_discounttotal,
         tm_nota.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_city, c.f_customer_plusppn as f_plus_ppn,
                            c.e_customer_phone, tr_area.e_area_name, tm_spb.i_spb_po, tm_spb.f_spb_consigment, tr_area.e_area_phone, d.e_customer_ownername
                            , e.e_customer_pkpname, c.f_customer_pkp from dgu.tm_nota
                                      inner join dgu.tr_customer c on (tm_nota.i_customer=c.i_customer)
                                      inner join dgu.tr_customer_owner d on (tm_nota.i_customer=d.i_customer)
                                      inner join dgu.tr_customer_pkp e on (tm_nota.i_customer=e.i_customer)
                                      inner join dgu.tr_area on (substring(tm_nota.i_sj,9,2)=tr_area.i_area)
                                      left join tm_spb on (tm_spb.i_spb=tm_nota.i_spb and tm_spb.i_area=tm_nota.i_area)
                                      where tm_nota.i_sj = '$isj' and substring(tm_nota.i_sj,9,2)='$area'",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    function bacadetail($isj,$area)
    {
      $cust='';
          $tes=$this->db->query("select i_customer from dgu.tm_nota where i_sj = '$isj' and substring(i_sj,9,2)='$area'",false);
          if ($tes->num_rows() > 0){
        foreach($tes->result() as $xx){
              $cust=$xx->i_customer;
        }
          }
      $group='';
          $que  = $this->db->query(" select i_customer_plugroup from dgu.tr_customer_plugroup where i_customer='$cust'",false);
          if($que->num_rows()>0){
        foreach($que->result() as $hmm){
          $group=$hmm->i_customer_plugroup;
        }
      }
      if($group==''){
            $this->db->select(" * from dgu.tm_nota_item
                                          inner join dgu.tr_product_motif on (tm_nota_item.i_product_motif=tr_product_motif.i_product_motif
                                                                      and tm_nota_item.i_product=tr_product_motif.i_product)
                                          where i_sj = '$isj' and substring(i_sj,9,2)='$area' order by n_item_no",false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                return $query->result();
            }
      }else{
            $this->db->select(" a.i_sj, a.i_nota, a.i_product as product, a.i_product_grade, a.i_product_motif, a.n_deliver, a.v_unit_price,
                            a.e_product_name, a.i_area, a.d_nota, a.n_item_no, c.i_customer_plu, c.i_product from dgu.tm_nota_item a
                                          inner join dgu.tr_product_motif b on (a.i_product_motif=b.i_product_motif and a.i_product=b.i_product)
                            left join dgu.tr_customer_plu c on (c.i_customer_plugroup='$group' and a.i_product=c.i_product) 
                                          where i_sj = '$isj' and substring(i_sj,9,2)='$area' order by n_item_no",false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                return $query->result();
            }
      }
    }

    function updatesj($isj, $area)
    {
          $query    = $this->db->query("SELECT current_timestamp as c");
          $row      = $query->row();
          $dprint   = $row->c;
      $this->db->set(
            array(
              'd_sj_print'          => $dprint
            )
        );
          $this->db->where('i_sj', $isj);
          $this->db->where('i_area', $area);
          $this->db->update('tm_nota'); 
    }

}


