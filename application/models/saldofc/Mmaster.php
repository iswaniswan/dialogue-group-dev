<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  function insertdetail($thbl,$iproduct,$iproductgrade,$eproductname,$norder,$iproductmotif,$i){
    $this->db->set(
       array(
             'e_periode'       => $thbl,
             'i_product'       => $iproduct,
             'i_product_grade' => $iproductgrade,
             'i_product_motif' => $iproductmotif,
             'n_saldo_awal'    => $norder,
             'n_sisa'          => $norder
      )
    );
    $this->db->insert('tm_saldoawal_fc');
  }

  public function deletedetail($iproduct, $iproductgrade, $iop, $iproductmotif){
      $this->db->query("DELETE FROM tm_opfc_item WHERE i_op='$iop'
                     and i_product='$iproduct' and i_product_grade='$iproductgrade'
                     and i_product_motif='$iproductmotif'");
      return TRUE;
  }

  function runningnumber($thbl){
      $th = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
      $this->db->select(" n_modul_no as max from tm_dgu_no
                        where i_modul='OPF'
                        and substr(e_periode,1,4)='$th' for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         foreach($query->result() as $row){
           $terakhir=$row->max;
         }
         $noop  =$terakhir+1;
      $this->db->query(" update tm_dgu_no
                          set n_modul_no=$noop
                          where i_modul='OPF'
                          and substr(e_periode,1,4)='$th' ", false);
         settype($noop,"string");
         $a=strlen($noop);
         while($a<6){
           $noop="0".$noop;
           $a=strlen($noop);
         }

         $noop  ="OP-".$thbl."-".$noop;
         return $noop;
       }else{
         $noop  ="000001";
         $noop  ="OP-".$thbl."-".$noop;
         $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                         values ('OPF','00',$asal,1)");
         return $noop;
      }
  }

  function carisupplier($cari,$num,$offset){
      $this->db->select(" * from tr_supplier
                     where upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%'
                     order by i_supplier",FALSE)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
  }

  function caricustomer($cari,$num,$offset){
      $this->db->select(" a.*, b.i_store_location, b.e_store_locationname from tr_store a, tr_store_location b
               where a.i_store=b.i_store
                 and (upper(a.i_store) like '%$cari%' or upper(a.e_store_name) like '%$cari%'

               or upper(b.i_store_location) like '%$cari%' or upper(b.e_store_locationname) like '%$cari%')
               order by a.i_store",FALSE)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
  }
    
  function bacaarea($num,$offset){
      $this->db->select("* from tr_area where i_area='00'",false)->limit($num,$offset);
      $query = $this->db->get();
      var_dump($query);
         die();
      if ($query->num_rows() > 0){
         return $query->result();
      }
  }

  function cariarea($cari,$num,$offset,$allarea,$iuser){
      if($allarea=='t'){
         $this->db->select("* from tr_area where (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%') order by i_area", false)->limit($num,$offset);
      }
      else
      {
         $this->db->select("* from tr_area where i_area in ( select i_area from tm_user_area where i_user='$iuser') and (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%') order by i_area", false)->limit($num,$offset);
      }

      $query = $this->db->get();

      if ($query->num_rows() > 0){
         return $query->result();
      }
  }
  
  function cekproduct($iproduct, $thbl){
      $query=$this->db->query("select i_product from tm_saldoawal_fc where e_periode = '$thbl' and i_product = '$iproduct'",false);
      if ($query->num_rows() > 0){
         echo "Kode Barang : ".$iproduct." Sudah Ada !";
         die;
      }
  }
  
  function cariproduct($num,$offset){
          $stquery="distinct c.e_product_motifname,c.i_product_motif, d.v_product_mill, d.i_product, d.e_product_name
                                       from tr_product_motif c, tr_product d
                                       where d.i_product_status<>'4' and d.i_product=c.i_product
                                       order by d.i_product";             
        
      
      $this->db->select($stquery,false)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
  }
  
  function bacaproduct($cari,$num,$offset){
      $stquery="distinct c.e_product_motifname,c.i_product_motif, d.v_product_mill, d.i_product, d.e_product_name
                                       from tr_product_motif c, tr_product d
                                       where d.i_product_status<>'4' and d.i_product=c.i_product
                                       and (upper(d.i_product) like '%$cari%' or upper(d.e_product_name) like '%$cari%')
                                       order by d.i_product";           
      
      $this->db->select($stquery,false)->limit($num,$offset);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
        }
  }

}

/* End of file Mmaster.php */
