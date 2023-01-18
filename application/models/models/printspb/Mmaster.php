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
            SELECT a.i_spb, 
            b.d_spb, 
            a.e_customer_name, 
            a.i_area, 
            b.i_approve1, 
            b.i_approve2,
            b.f_spb_cancel,
            b.i_notapprove,
            b.i_store,
            b.i_nota,
            b.f_spb_stockdaerah,
            b.f_spb_siapnotagudang,
            b.f_spb_siapnotasales,
            b.f_spb_op,
            b.f_spb_opclose,
            b.i_sj,
            c.i_dkb,
            c.d_sj_receive,
            b.n_print,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$folder' as folder,
                '$status' as status
            from dgu.tr_customer_tmp a, dgu.tm_spb b
            left join tm_nota c on(b.i_spb=c.i_spb and b.i_area=c.i_area)
            where a.i_spb=b.i_spb and a.i_area=b.i_area
                $sql
                AND (b.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND b.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy'))"
        , FALSE);

        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('status');
        $datatables->hide('f_spb_cancel');
        $datatables->hide('i_approve2');
        $datatables->hide('i_notapprove');
        $datatables->hide('i_store');
        $datatables->hide('i_nota');
        $datatables->hide('f_spb_op');
        $datatables->hide('f_spb_opclose');
        $datatables->hide('i_sj');
        $datatables->hide('i_dkb');
        $datatables->hide('f_spb_stockdaerah');
        $datatables->hide('f_spb_siapnotagudang');
        $datatables->hide('f_spb_siapnotasales');
        $datatables->hide('d_sj_receive');
        $datatables->add('statuus', function ($data) {
            if(
                  ($data['f_spb_cancel'] == 't') 
                 ){
            $data='<span class="label label-inverse label-rouded">Batal</span>';
        }
          elseif(
                  ($data['i_approve1'] == null) && ($data['i_notapprove'] == null)
                 ){
            $data='<span class="label label-inverse label-rouded">Sales</span>';
          }
          elseif(
                  ($data['i_approve1'] == null) && ($data['i_notapprove'] != null)
                 ){
            $data='<span class="label label-inverse label-rouded">Reject (sls)</span>';
          }
          elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] == null) &
                  ($data['i_notapprove'] == null)
                 ){
            $data='<span class="label label-inverse label-rouded">Keuangan</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] == null) && 
                  ($data['i_notapprove'] != null)
                 ){
            $data='<span class="label label-inverse label-rouded">Reject (ar)</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] == null)
                 ){
            $data='<span class="label label-inverse label-rouded">Gudang</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') && 
                  ($data['f_spb_siapnotagudang'] == 'f') && ($data['f_spb_op'] == 'f')
                 ){
            $data='<span class="label label-inverse label-rouded">Pemenuhan SPB</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') &&
                  ($data['f_spb_siapnotagudang'] == 'f') && ($data['f_spb_op'] == 't') && ($data['f_spb_opclose'] == 'f')
                 ){
            $data='<span class="label label-inverse label-rouded">Proses OP</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') &&
                  ($data['f_spb_siapnotagudang'] == 'f') && ($data['f_spb_siapnotasales'] == 'f') && ($data['f_spb_opclose'] == 't')
                 ){
            $data='<span class="label label-inverse label-rouded">OP Close</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') &&
                  ($data['f_spb_siapnotagudang'] == 't') && ($data['f_spb_siapnotasales'] == 'f')
                 ){
            $data='<span class="label label-inverse label-rouded">Siap SJ (sales)</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') &&
                  ($data['f_spb_siapnotagudang'] == 't') && ($data['f_spb_siapnotasales'] == 't') && ($data['i_sj'] == null)
                 ){
#               $data='<span class="label label-inverse label-rouded">Siap SJ (gudang)</span>';
            $data='<span class="label label-inverse label-rouded">Siap SJ</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') && 
                  ($data['f_spb_siapnotagudang'] == 't') && ($data['f_spb_siapnotasales'] == 't') && ($data['i_sj'] == null)
                 ){
            $data='<span class="label label-inverse label-rouded">Siap SJ</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && ($data['i_dkb'] == null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') && 
                  ($data['f_spb_siapnotagudang'] == 't') && ($data['f_spb_siapnotasales'] == 't') && ($data['i_sj'] != null)
                 ){
            $data='<span class="label label-inverse label-rouded">Siap DKB</span>';
    }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && ($data['i_dkb'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['f_spb_stockdaerah'] == 'f') && 
                  ($data['f_spb_siapnotagudang'] == 't') && ($data['f_spb_siapnotasales'] == 't') && ($data['i_sj'] != null)
                 ){
            $data='<span class="label label-inverse label-rouded">Siap Nota</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && 
                  ($data['f_spb_stockdaerah'] == 't') && ($data['i_sj'] == null)
                 ){
            $data='<span class="label label-inverse label-rouded">Siap SJ</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['i_dkb'] == null) && 
                  ($data['f_spb_stockdaerah'] == 't') && ($data['i_sj'] != null)
                 ){
            $data='<span class="label label-inverse label-rouded">Siap DKB</span>';
          }elseif(
                  ($data['i_approve1'] != null) && ($data['i_approve2'] != null) && 
                  ($data['i_store'] != null) && ($data['i_nota'] == null) && ($data['i_dkb'] != null) && 
                  ($data['f_spb_stockdaerah'] == 't') && ($data['i_sj'] != null)
                 ){
            $data='<span class="label label-inverse label-rouded">Siap Nota</span>';
          }elseif(
            ($data['i_approve1'] != null) && 
              ($data['i_approve2'] != null) &&
               ($data['i_store'] != null) && 
            ($data['i_nota'] != null) && ($data['d_sj_receive'] != null) 
            ){
                $data='<span class="label label-inverse label-rouded">Sudah diterima</span>';             
            }elseif(
                  ($data['i_approve1'] != null) && 
                  ($data['i_approve2'] != null) &&
                  ($data['i_store'] != null) && 
                  ($data['i_nota'] != null) 
                 ){
            $data='<span class="label label-inverse label-rouded">Sudah dinotakan</span>';            
          }elseif(($data['i_nota'] != null)){
            $data='<span class="label label-inverse label-rouded">Sudah dinotakan</span>';
          }else{
            $data='<span class="label label-inverse label-rouded">Unknown</span>';      
          }
            return $data;
        });
        $datatables->add('action', function ($data) {
        $folder         = $data['folder'];
        $dfrom          = $data['dfrom'];
        $dto            = $data['dto'];
        $ispb           = $data['i_spb'];
        $iarea          = $data['i_area'];
        $data='';
        // $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/cetak/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
        $data      .= "<a href=\"javascript:yyy('".$data['i_spb']."','".$iarea."');\"><i class='fa fa-print'></i></a>";
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
        $datatables->edit('n_print', function ($data) {
        if($data['n_print']==0) {
          $data = "<span class='label label-info label-rouded'>Belum</span>"; }
        else {
          $data = "<span class='label label-inverse label-rouded'>Sudah</span>"; } 
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
            a.i_spb, a.d_spb, a.i_customer, a.i_salesman,a.e_remark1,a.e_approve1,a.e_approve2, a.n_spb_toplength, a.i_price_group, a.v_spb, a.n_spb_discount1, a.n_spb_discount2, a.n_spb_discount3, a.n_spb_discount4, a.v_spb_discounttotal, f.v_flapond, f.n_ratatelat, b.e_customer_name,b.e_customer_address,b.e_customer_city, b.f_customer_pkp, b.d_signin, c.e_salesman_name, d.e_customer_classname, e.e_price_groupname, f.i_customer_groupar, g.e_customer_ownername, h.e_customer_pkpname
            from tm_spb a, tr_customer b, tr_salesman c, tr_customer_class d, tr_price_group e, tr_customer_groupar f
                             , tr_customer_owner g, tr_customer_pkp h
                             where a.i_spb = '$ispb' and a.i_area='$area'
                             and a.i_customer=b.i_customer and a.i_customer=f.i_customer
                             and a.i_salesman=c.i_salesman
                             and a.i_customer=g.i_customer
                             and a.i_customer=h.i_customer
                             and (e.n_line=b.i_price_group or e.i_price_group=b.i_price_group)
                             and b.i_customer_class=d.i_customer_class
                             order by a.i_spb desc",false);
#and (a.n_print=0 or a.n_print isnull)
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    function baca_nilai($i_customer){
      $thbln = date('ym');
      return $this->db->query("select (x.v_spb - x.diskon) as nilai_spb from(
select sum(v_spb) as v_spb, sum(v_spb_discounttotal) as diskon from tm_spb where i_customer = '$i_customer' and i_spb like '%SPB-$thbln-%' and f_spb_cancel = 'f' 
) as x");
    }
    function close($area,$ispb)
    {
      $this->db->query("   update tm_spb set n_print=n_print+1
                              where i_spb = '$ispb' and i_area = '$area' ",false);
    }
    function bacadetail($ispb,$area)
    {
        $this->db->select(" a.i_spb,
                          a.i_product,
                          a.i_product_grade,
                          a.i_product_motif,
                          a.n_order,
                          a.n_deliver,
                          a.n_stock,
                          a.v_unit_price,
                          substr(a.e_product_name,1,46) as e_product_name,
                          a.i_op,
                          a.i_area,
                          a.e_remark,
                          a.n_item_no,
                          tr_product.i_product_status from tm_spb_item a
                         inner join tr_product on (a.i_product=tr_product.i_product)
                         inner join tr_product_motif on (a.i_product_motif=tr_product_motif.i_product_motif
                         and a.i_product=tr_product_motif.i_product)
                         where a.i_spb = '$ispb' and a.i_area='$area' order by a.n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    function bacapiutang($ispb,$area)
    {
      $this->db->select(" i_customer from tm_spb where i_spb = '$ispb' and i_area='$area'",false);
      $quer = $this->db->get();
      $cust='';      
      $saldo=0;
      if ($quer->num_rows() > 0){
        foreach($quer->result() as $rowi){
          $cust=$rowi->i_customer;
        }
        $this->db->select(" sum(v_sisa) as sisa from tm_nota where i_customer = '$cust' and f_nota_cancel='f' and not i_nota isnull",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $saldo=$row->sisa;
          }
        }
      }
      return $saldo;
    }

    function bacanotapiutang($ispb,$area)
    {
      $this->db->select(" i_customer from tm_spb where i_spb = '$ispb' and i_area='$area'",false);
      $quer = $this->db->get();
      $cust='';      
      $nota=0;
      if ($quer->num_rows() > 0){
        foreach($quer->result() as $rowi){
          $cust=$rowi->i_customer;
        }
        $this->db->select(" i_nota from tm_nota where i_customer = '$cust' and f_nota_cancel='f' and not i_nota isnull",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $nota=$row->i_nota;
          }
        }
      }
      return $nota;
    }

}


