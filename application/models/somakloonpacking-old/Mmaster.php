<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function getso($dso, $iunitpacking, $igudang){
     if($dso!=''){
      $tmp=explode("-",$dso);
      $th=$tmp[2];
      $bl=$tmp[1];
      $hr=$tmp[0];
      $dateso=$th."-".$bl."-".$hr;
      $thbl=$th.$bl;
    }
    $dso = date('d-m-Y',strtotime($dateso . "first day of this month"));
    $dso1 = date('d-m-Y',strtotime($dateso . "last day of this month"));

    $dso2 = date('d-m-Y',strtotime($dateso . "first day of previous month"));
    if($dso2!=''){
      $tmp=explode("-",$dso2);
      $th2=$tmp[2];
      $bl2=$tmp[1];
      $hr2=$tmp[0];
      $thbl2=$th2.$bl2;
    }
    $dso3 = date('d-m-Y',strtotime($dateso . "last day of previous month"));
    if($thbl<='202003'){
    $this->db->SELECT("* from f_stockopname_makloonpacking('$thbl', to_date('$dso','dd-mm-yyyy'), to_date('$dso1','dd-mm-yyyy'))", false);
   
    }else{
      $this->db->SELECT("* from duta_prod.f_stockopname_makloonpacking_saldoawal('$thbl2', to_date('$dso','dd-mm-yyyy'), to_date('$dso1','dd-mm-yyyy'),'$thbl2', to_date('$dso2','dd-mm-yyyy'), to_date('$dso3','dd-mm-yyyy'))", false);
   
    }
    return $this->db->get();
  }

  public function packing($iunitpacking){
    $this->db->SELECT("*");
    $this->db->FROM("tr_unit_packing");
    $this->db->WHERE("i_unit_packing", $iunitpacking);
    return $this->db->get();
  }

  public function gudang($igudang){
      $this->db->select('*');
          $this->db->from('tr_master_gudang');
          $this->db->where('i_kode_master',$igudang);
      return $this->db->get();
  }

	public function runningnumberpl($iarea, $thbl){
    $th   = substr($thbl,0,4);
    $asal = $thbl;
    $thbl = substr($thbl,2,2).substr($thbl,4,2);
    $this->db->select(" n_modul_no as max FROM tm_dgu_no
      WHERE i_modul='BAO'
      AND substr(e_periode,1,4)='$th'
      AND i_area='$iarea' for update", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $noal  =$terakhir+1;
      $this->db->query(" UPDATE tm_dgu_no SET n_modul_no= $noal WHERE i_modul='BAO' AND substr(e_periode,1,4)='$th' AND i_area='$iarea'", false);
      settype($noal,"string");
      $a=strlen($noal);
      while($a<5){
        $noal="0".$noal;
        $a=strlen($noal);
      }
      $noal  ="AO-".$thbl."-".$noal;
      return $noal;
    }else{
      $noal  ="00001";
      $noal  ="AO-".$thbl."-".$noal;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
        values ('BAO','$iarea','$asal',1)");
      return $noal;
    }
  }
  
  public function insertheader($dateso, $thbl, $iunitpacking, $igudang){
    $dentry = date("d F Y H:i:s");

    $data=array(
        'd_so'            => $dateso, 
        'periode'         => $thbl,
        'i_unit_packing'  => $iunitpacking,
        'i_gudang'        => $igudang,
        'd_entry'         => $dentry
    );
    $this->db->insert('tt_stokopname_unitpacking',$data);
  } 

  public function insertdetail($thbl, $saldoawal, $iproduct, $eproduct, $salhir, $nitemno){
     $data=array(
        'periode'         => $thbl,
        'i_product'       => $iproduct, 
        'e_product_name'  => $eproduct,
        'saldo_akhir'     => $salhir,
        'saldo_awal'      => $saldoawal,
        'n_item_no'       => $nitemno,
    );
    $this->db->insert('tt_stokopname_unitpacking_item', $data);
  }
}
/* End of file Mmaster.php */