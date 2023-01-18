<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cek_op($iop){
      $this->db->select('*');
      $this->db->from('tm_op');
      $this->db->where('i_op',$iop);
      return $this->db->get();
    }

    function bacadetailop($iop){
      return $this->db->query(" select a.*, b.e_product_motifname from tm_opfc_item a, tr_product_motif b
                        where a.i_op = '$iop' and (a.n_delivery<a.n_order or n_delivery isnull)
                        and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                        order by a.i_product", false);
    }

    function baca($ido,$isupplier){
      $this->db->select(" a.*, b.*, c.*
                     from tm_dofc a, tr_supplier b, tr_area c
                     where a.i_supplier=b.i_supplier
                     and a.i_area=c.i_area
                     and a.i_do ='$ido'
                     and a.i_supplier='$isupplier'", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->row();
      }
    }

    function bacadetail($ido,$isupplier){
		  $sql = " a.*,d.*, b.e_product_motifname, c.n_order, (d.n_deliver * d.v_product_mill) as total from tm_dofc a, tr_product_motif b, tm_opfc_item c, tm_dofc_item d
            where a.i_do=d.i_do and a.i_do = '$ido' and a.i_supplier='$isupplier' and d.i_product=b.i_product and d.i_product_motif=b.i_product_motif
            and d.i_product=c.i_product and a.i_op=c.i_op
            order by d.i_product "; //echo $sql; die();
      $this->db->select($sql, false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }
    function insertheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross){
      $query   = $this->db->query("SELECT current_timestamp as c");
      $row     = $query->row();
      $dentry  = $row->c;
      $this->db->set(
         array(
               'i_do'      => $ido,
               'i_supplier'=> $isupplier,
               'i_op'      => $iop,
               'i_area'    => $iarea,
               'd_do'      => $ddo,
               'v_do_gross'=> $vdogross,
               'd_entry'   => $dentry
         )
      );
      $this->db->insert('tm_dofc');
    }
    function insertdetail($iop,$ido,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vproductmill,$ddo,$eremark,$i,$idoold){
      $th=substr($ddo,0,4);
      $bl=substr($ddo,5,2);
      $pr=$th.$bl;
      $this->db->set(
         array(
              'i_do'            => $ido,
              'd_do'            => $ddo,
              'i_supplier'      => $isupplier,
              'i_product'       => $iproduct,
              'i_product_grade' => $iproductgrade,
              'i_product_motif' => $iproductmotif,
              'e_product_name'  => $eproductname,
              'n_deliver'       => $ndeliver,
              'v_product_mill'  => $vproductmill,
              'i_op'            => $iop,
              'e_remark'        => $eremark,
              'e_mutasi_periode'=> $pr,
              'n_item_no'       => $i
         )
      );
      $this->db->insert('tm_dofc_item');
    }
    function updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$ndeliverhidden,$ntmp){
     if($ntmp==''){
      $this->db->query("update tm_opfc_item set n_delivery=n_delivery+$ndeliver
                                     where i_op='$iop' and i_product='$iproduct' and i_product_grade='$iproductgrade'
                                     and i_product_motif='$iproductmotif'");              
      }else{

      $this->db->query("update tm_opfc_item set n_delivery=n_delivery+$ndeliver-$ntmp
                                     where i_op='$iop' and i_product='$iproduct' and i_product_grade='$iproductgrade'
                                     and i_product_motif='$iproductmotif'");
      }
    }
    function updatesaldofc($iproduct,$ndeliver,$period){
      $prod ='';
      $query = $this->db->query(" select e_periode from tm_saldoawal_fc where i_product='$iproduct' and e_periode='$period'", false);
         foreach($query->result() as $row){
            $prod=$row->i_product;
         }
         if($prod!=NULL){
            $this->db->query("update tmp_saldoawal_fc set n_sisa=n_sisa-$ndeliver
                                     where i_product='$iproduct' and e_periode='$period'");              
         }
    }
    function updatespbdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver){
         $this->db->select("  i_reff, i_area from tm_op where i_op='$iop'", false);
         $query = $this->db->get();
         foreach($query->result() as $row){
            $spb =$row->i_reff;
            $area=$row->i_area;
         }
         $que=$this->db->query(" select n_order, n_deliver from tm_spb_item
                                             where i_spb='$spb' and i_area='$area' and i_product='$iproduct'
                                                and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
         if($que->num_rows>0){
            $tmp=0;
           foreach($que->result() as $raw){
               $jmlord =$raw->n_order;
               $jmldel =$raw->n_deliver;
               $tmp=$ndeliver+$jmldel;
            }
            if($jmlord>=$tmp){
            $this->db->query("update tm_spb_item set n_deliver=n_deliver+$ndeliver, n_stock=n_stock+$ndeliver, i_op='$iop'
                                     where i_spb='$spb' and i_area='$area' and i_product='$iproduct' and i_product_grade='$iproductgrade'
                                     and i_product_motif='$iproductmotif'");
            }
         }
    }
    function updateheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross,$idoold){
      $data = array(
      'i_do'      => $ido,
      'i_supplier'=> $isupplier,
      'i_op'      => $iop,
      'i_area' => $iarea,
      'd_do'      => $ddo,
      'v_do_gross'=> $vdogross
      );
      $this->db->where('i_do', $idoold);
      $this->db->where('i_supplier', $isupplier);
      $this->db->where('i_op', $iop);
      $this->db->update('tm_dofc', $data);
    }

    public function deletedetail($iproduct, $iproductgrade, $ido, $isupplier, $iproductmotif, $tahun, $idoold){
      $this->db->query("DELETE FROM tm_dofc_item WHERE i_do='$idoold' and i_supplier='$isupplier'
                    and i_product='$iproduct' and i_product_grade='$iproductgrade'
                    and i_product_motif='$iproductmotif'");
      return TRUE;
    }

    function uphead($ido,$isupplier,$iop,$iarea,$ddo,$vdogross){
      $data = array(
               'i_do'      => $ido,
               'i_supplier'=> $isupplier,
               'i_op'      => $iop,
               'i_area' => $iarea,
               'd_do'      => $ddo,
               'v_do_gross'=> $vdogross

            );
      $this->db->where('i_do', $ido);
      $this->db->where('i_supplier', $isupplier);
      $this->db->update('tm_dofc', $data);
    }

    public function delete($ido,$isupplier, $iop){
      return TRUE;
    }
    function bacasemua(){
      $this->db->select("* from tm_dofc order by i_do desc",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }
    function bacaop($num,$offset)
    {
      $this->db->select(" a.*, b.e_supplier_name, c.e_area_name, b.n_supplier_discount, b.n_supplier_discount2
                          from tm_opfc a , tr_supplier b, tr_area c
                          where a.i_supplier=b.i_supplier and a.i_area=c.i_area
                          and a.f_op_cancel='f' and a.f_op_close='f'
                          and b.i_supplier_group<>'G0000'
                          and a.i_op in (select i_op from tm_opfc_item where (n_delivery isnull or n_delivery<n_order))
                          order by b.e_supplier_name, a.i_op",false)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }
    function runningnumberbbm($thbl){
        $th    = substr($thbl,0,2);
        $this->db->select(" max(substr(i_bbm,10,6)) as max from tm_bbm where substr(i_bbm,5,2)='$th' and i_bbm_type='04'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
           foreach($query->result() as $row){
             $terakhir=$row->max;
           }
           $noso  =$terakhir+1;
           settype($noso,"string");
           $a=strlen($noso);
           while($a<6){
             $noso="0".$noso;
             $a=strlen($noso);
           }
           $noso  ="BBM-".$thbl."-".$noso;
           return $noso;
        }else{
           $noso  ="000001";
           $noso  ="BBM-".$thbl."-".$noso;
           return $noso;
        }
    }
    function insertbbmheader($ido,$ddo,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isupplier)
    {
      $this->db->set(
         array(
            'i_bbm'                      => $ibbm,
            'i_bbm_type'               => $ibbmtype,
            'i_refference_document' => $ido,
            'd_refference_document' => $ddo,
            'd_bbm'                    => $dbbm,
            'e_remark'                 => $eremark,
            'i_area'                   => $iarea,
            'i_supplier'               => $isupplier
         )
      );

      $this->db->insert('tm_bbm');
    }
    function updatebbmheader($ido,$ddo,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isupplier)
    {
      $this->db->set(
         array(
            'i_refference_document' => $ido,
            'd_refference_document' => $ddo,
            'd_bbm'                      => $dbbm,
            'e_remark'                 => $eremark,
            'i_area'                => $iarea
         )
      );
      $this->db->where('i_bbm',$ibbm);
      $this->db->where('i_bbm_type',$ibbmtype);
      $this->db->where('i_supplier',$isupplier);
      $this->db->update('tm_bbm');
    }
    function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$ido,$ibbm,$eremark,$ddo)
    {
      $th=substr($ddo,0,4);
      $bl=substr($ddo,5,2);
      $pr=$th.$bl;
      $this->db->set(
         array(
            'i_bbm'                    => $ibbm,
            'i_refference_document' => $ido,
            'i_product'                => $iproduct,
            'i_product_motif'       => $iproductmotif,
            'i_product_grade'       => $iproductgrade,
            'e_product_name'        => $eproductname,
            'n_quantity'               => $nquantity,
            'v_unit_price'             => $vunitprice,
            'e_remark'                 => $eremark,
            'd_refference_document' => $ddo,
        'e_mutasi_periode'      => $pr,
        'i_bbm_type'            => '04'
         )
      );

      $this->db->insert('tm_bbm_item');
    }
   function runningnumberbbk($thbl){
     $th    = substr($thbl,0,2);
     $this->db->select(" max(substr(i_bbk,10,6)) as max from tm_bbk where substr(i_bbk,5,2)='$th' ", false);
     $query = $this->db->get();
     if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nobbk  =$terakhir+1;
        settype($nobbk,"string");
        $a=strlen($nobbk);
        while($a<6){
          $nobbk="0".$nobbk;
          $a=strlen($nobbk);
        }
        $nobbk  ="BBK-".$thbl."-".$nobbk;
        return $nobbk;
     }else{
        $nobbk  ="000001";
        $nobbk  ="BBK-".$thbl."-".$nobbk;
        return $nobbk;
     }
  }
   function insertbbkheader($ispb,$dspb,$ibbk,$dbbk,$ibbktype,$eremark,$iarea,$isupplier)
    {
      $this->db->set(
         array(
            'i_bbk'              => $ibbk,
            'i_bbk_type'      => $ibbktype,
            'i_refference_document' => $ispb,
            'd_refference_document' => $dspb,
            'd_bbk'              => $dbbk,
            'e_remark'        => $eremark,
            'i_area'          => $iarea,
            'i_supplier'      => $isupplier
         )
      );

      $this->db->insert('tm_bbk');
    }
   function insertbbkdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,
                      $vunitprice,$ispb,$ibbk,$eremark,$dspb,$ibbktype,
                      $istore,$istorelocation,$istorelocationbin)
    {
      $th=substr($dspb,0,4);
      $bl=substr($dspb,5,2);
      $pr=$th.$bl;
      $this->db->set(
         array(
            'i_bbk'              => $ibbk,
            'i_bbk_type'         => $ibbktype,
            'i_refference_document' => $ispb,
            'i_product'          => $iproduct,
            'i_product_motif'    => $iproductmotif,
            'i_product_grade'    => $iproductgrade,
            'e_product_name'     => $eproductname,
            'n_quantity'         => $nquantity,
            'v_unit_price'       => $vunitprice,
            'e_remark'           => $eremark,
            'd_refference_document' => $dspb,
        'e_mutasi_periode'      => $pr
         )
      );

      $this->db->insert('tm_bbk_item');
    }
   function updatebbkheader($ido,$ddo,$ibbk,$dbbk,$ibbktype,$eremark,$iarea,$isupplier)
    {
      $this->db->set(
         array(
            'i_refference_document' => $ido,
            'd_refference_document' => $ddo,
            'd_bbk'              => $dbbk,
            'e_remark'           => $eremark,
            'i_area'          => $iarea
         )
      );
      $this->db->where('i_bbk',$ibbk);
      $this->db->where('i_bbk_type',$ibbktype);
      $this->db->where('i_supplier',$isupplier);
      $this->db->update('tm_bbk');
    }
    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                order by i_trans desc",false);
      if ($query->num_rows() > 0){
            return $query->result();
         }
    }
    function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {

      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
            return $query->result();
         }
    }
    function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak)
    {
      $query   = $this->db->query("SELECT current_timestamp as c");
       $row    = $query->row();
       $now   = $row->c;
      $query=$this->db->query("
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location,
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction,
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin',
                                  '$eproductname', '$ido', '$now', $qdo, 0, $q_ak+$qdo, $q_ak
                                )
                              ",false);
    }
    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
            $ada=true;
         }
      return $ada;
    }
    function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
    {
      $query=$this->db->query("
                                UPDATE tm_mutasi
                                set n_mutasi_pembelian=n_mutasi_pembelian+$qdo, n_saldo_akhir=n_saldo_akhir+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
    {
      $query=$this->db->query("
                                insert into tm_mutasi
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,$qdo,0,0,0,0,0,$qdo,0,'f')
                              ",false);
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
            $ada=true;
         }
      return $ada;
    }
    function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak)
    {
      $query=$this->db->query("
                                UPDATE tm_ic set n_quantity_stock=$q_ak+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo)
    {
      $query=$this->db->query("
                                insert into tm_ic
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname',$qdo, 't'
                                )
                              ",false);
    }
    function inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$qbbm,$q_aw,$q_ak)
    {
      $query   = $this->db->query("SELECT current_timestamp as c");
       $row    = $query->row();
       $now   = $row->c;
      $query=$this->db->query("
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location,
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction,
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin',
                                  '$eproductname', '$ibbm', '$now', $q_in+$qbbm, $q_out, $q_ak+$qbbm, $q_aw
                                )
                              ",false);
    }
    function updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode)
    {
      $query=$this->db->query("
                                UPDATE tm_mutasi
                                set n_mutasi_bbm=n_mutasi_bbm+$qbbm, n_saldo_akhir=n_saldo_akhir+$qbbm
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode)
    {
      $query=$this->db->query("
                                insert into tm_mutasi
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qbbm,0,0,0,$qbbm,0,'f')
                              ",false);
    }
    function updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$q_ak)
    {
      $query=$this->db->query("
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qbbm
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbm)
    {
      $query=$this->db->query("
                                insert into tm_ic
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qbbm, 't'
                                )
                              ",false);
    }
    function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbbk,$q_aw,$q_ak)
    {
      $query   = $this->db->query("SELECT current_timestamp as c");
       $row    = $query->row();
       $now   = $row->c;
      $query=$this->db->query("
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location,
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction,
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin',
                                  '$eproductname', '$ibbk', '$now', $q_in, $q_out+$qbbk, $q_ak-$qbbk, $q_aw
                                )
                              ",false);
    }
    function updatemutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query("
                                UPDATE tm_mutasi
                                set n_mutasi_penjualan=n_mutasi_penjualan+$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query("
                                insert into tm_mutasi
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbk,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,$qbbk,0,0,$qbbk,0,'f')
                              ",false);
    }
    function updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query("
                                UPDATE tm_mutasi
                                set n_mutasi_bbk=n_mutasi_bbk+$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query("
                                insert into tm_mutasi
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbk,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,0,0,$qbbk,$qbbk,0,'f')
                              ",false);
    }
    function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$q_ak)
    {
      $query=$this->db->query("
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbk)
    {
      $query=$this->db->query("
                                insert into tm_ic
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0, 't'
                                )
                              ",false);
    }
    function hitungtotal($iop)
    {
        $this->db->select(" sum(n_order*v_product_mill) as total from tm_opfc_item where i_op='$iop'
                          and (n_delivery<n_order or n_delivery isnull)", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
           foreach($query->result() as $tes){
          $total=$tes->total;
        }
        return $total;
        }
    }
function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ido,$ntmp,$eproductname)
    {
      $queri      = $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' 
                                    order by i_trans desc",false);
      if ($queri->num_rows() > 0){
        $row         = $queri->row();
        $que   = $this->db->query("SELECT current_timestamp as c");
        $ro   = $que->row();
        $now   = $ro->c;
        if($ntmp!=0 || $ntmp!=''){
          $query=$this->db->query("
                                  INSERT INTO tm_ic_trans
                                  (
                                    i_product, i_product_grade, i_product_motif, i_store, i_store_location,
                                    i_store_locationbin, e_product_name, i_refference_document, d_transaction,
                                    n_quantity_in, n_quantity_out,
                                    n_quantity_akhir, n_quantity_awal)
                                  VALUES
                                  (
                                    '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin',
                                    '$eproductname', '$ido', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir
                                  )
                                ",false);
        }
      }
      if(isset($row->i_trans)){
        if($row->i_trans!=''){
          return $row->i_trans;
        }else{
          return 1;
        }
      }else{
        return 1;
      }
    }
    function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query("
                                UPDATE tm_mutasi set n_mutasi_pembelian=n_mutasi_pembelian-$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      $query=$this->db->query("
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
}

/* End of file Mmaster.php */
