<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacacustomer(){
    return $this->db->query("SELECT i_customer, e_customer_name FROM tr_customer WHERE f_status='t'",FALSE)->result();
  }

  public function getbarang(){
    return $this->db->query("
                            select
                              i_product_motif,
                              e_product_basename,
                              i_color
                            from
                              tr_product_base
                            ", FALSE);
  }

  public function bacaproduct($cari){
    return $this->db->query("
                            select 
                              i_product_motif,
                              e_product_basename,
                              i_color
                            from 
                              tr_product_base 
                            where
                              (
                                i_product_motif like '%$cari%' 
                                or e_product_basename like '%$cari%'
                              ) 
                            order by 
                              i_product_motif
                            ");
  }

  public function runningnumber($yearmonth,$isubbagian){
    $bl       = substr($yearmonth,4,2);
    $th       = substr($yearmonth,0,4);
    $thn      = substr($yearmonth,2,2);
    $area     = trim($isubbagian);
    $asal     = substr($yearmonth,0,4);
    $yearmonth= substr($yearmonth,0,4);

    $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='FCD'
                        and i_area='$area'
                        and e_periode='$asal' 
                        and substring(e_periode,1,4)='$th' for update", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $nopp  =$terakhir+1;
            $this->db->query("update tm_dgu_no 
                        set n_modul_no=$nopp
                        where i_modul='FCD'
                        and e_periode='$asal' 
                        and i_area='$area'
                        and substring(e_periode,1,4)='$th'", false);
      settype($nopp,"string");
      $a=strlen($nopp);
      //u/ 0
      while($a<5){
        $nopp="0".$nopp;
        $a=strlen($nopp);
      }
        $nopp  ="FCD"."-".$area."-".$thn.$bl."-".$nopp;
      return $nopp;
    }else{
      $nopp  ="00001";
      $nopp  ="FCD"."-".$area."-".$thn.$bl."-".$nopp;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('FCD','$area','$asal',1)");
      return $nopp;
    }
  }

  public function cekfc($yearmonth, $icustomer){
    return $this->db->query("
                            SELECT
                              i_fc
                            FROM
                              tm_fc_distributor
                            WHERE
                              e_fc_periode = '$yearmonth'
                              AND i_customer = '$icustomer'
                            ",false);
  }

  public function insertheader($ifc, $dfrom, $yearmonth, $icustomer){         
    $dentry   = date('Y-m-d');   
    $this->db->set(
      array(
        'i_fc'         => $ifc,
        'd_fc'         => $dfrom,
        'e_fc_periode' => $yearmonth,
        'f_cancel'     => 'f',
        'd_entry'      => $dentry,
        'i_customer'   => $icustomer
      ) 
    );
    $this->db->insert('tm_fc_distributor');
  }

  public function updateheaderfc($ifc, $dfrom, $yearmonth, $icustomer){
        $dentry = date('Y-m-d');   
        $data = array(
            'd_fc'          => $dfrom,
            'e_fc_periode'  => $yearmonth,
            'd_update'      => $dentry
        );
       $this->db->where('i_fc', $ifc);
       $this->db->where('i_customer', $icustomer);
       $this->db->update('tm_fc_distributor', $data);
  }

  public function deletedetail($ifc){
        $this->db->query("
                          DELETE 
                          FROM 
                            tm_fc_distributor_detail
                          WHERE 
                            i_fc='$ifc'
                          ");
  }

  public function deletedetailinput($iproduct){
    $this->db->query("
                    DELETE FROM 
                      tm_fc_distributor_detail 
                    WHERE 
                      i_product='$iproduct' 
                    ");
  }


  public function insertdetail($ifc, $iproduct, $icolor, $nquantity, $nitemno){
      $data = array(
          'i_fc'           => $ifc,
          'i_product'      => $iproduct,
          'i_color'        => $icolor,
          'n_quantity'     => $nquantity,
          'n_item_no'      => $nitemno, 
      );
    $this->db->insert('tm_fc_distributor_detail', $data);
  }

  public function cek_eksport($dfrom){
    $tmp       = explode('-', $dfrom);
    $day       = $tmp[0];
    $month     = $tmp[1];
    $year      = $tmp[2];
    $periode   = $year.$month;

    return $this->db->query("
                            SELECT
                               a.d_fc,
                               b.i_product,
                               b.i_color,
                               b.n_quantity as jumlah,
                               c.e_product_basename 
                            FROM
                               tm_fc_distributor a 
                               JOIN
                                  tm_fc_distributor_detail b 
                                  ON (a.i_fc = b.i_fc) 
                               LEFT JOIN
                                  tr_product_base c 
                                  ON (b.i_product = c.i_product_motif) 
                            WHERE
                               a.e_fc_periode='$periode'
                            order by
                              b.i_product
                            ", FALSE);
  }

  public function bacafc($dfrom, $icustomer){
    $tmp       = explode('-', $dfrom);
    $day       = $tmp[2];
    $month     = $tmp[1];
    $year      = $tmp[0];
    $periode   = $year.$month;

    return $this->db->query("
                            SELECT
                              i_fc
                            FROM
                              tm_fc_distributor
                            WHERE
                              e_fc_periode='$periode'
                              AND i_customer='$icustomer'
                            ",FALSE);
  }

  public function bacadetailfc($ifc,$dfrom,$dto){
    $tmp       = explode('-', $dfrom);
    $day       = $tmp[0];
    $month     = $tmp[1];
    $year      = $tmp[2];
    $periode   = $year.$month;
  
    if($ifc == 'ALL'){
      return $this->db->query("
                              SELECT
                                 b.i_product,
                                 b.i_color,
                                 sum(b.n_quantity) as n_quantity,
                                 c.e_product_basename 
                              FROM
                                 tm_fc_distributor a 
                                 JOIN
                                    tm_fc_distributor_detail b 
                                    ON (a.i_fc = b.i_fc) 
                                 LEFT JOIN
                                    tr_product_base c 
                                    ON (b.i_product = c.i_product_motif) 
                              WHERE
                                 e_fc_periode = '$periode'
                              group by
                                 b.i_product,
                                 b.i_color,
                                 c.e_product_basename
                              order by
                                b.i_product
                              ", FALSE);
    }else{
      return $this->db->query("
                              SELECT
                                a.i_product,
                                b.e_product_basename,
                                a.i_color,
                                a.n_quantity
                              FROM
                                tm_fc_distributor_detail a
                                LEFT JOIN tm_fc_distributor c ON (a.i_fc = c.i_fc)
                                LEFT JOIN tr_product_base b ON (a.i_product = b.i_product_motif)
                              WHERE
                                a.i_fc = '$ifc'
                              ", FALSE);
    }
  }

  public function cek_dataheader($yearmonth, $icustomer){
    return $this->db->query("
                            SELECT
                              a.*,
                              b.e_customer_name
                            FROM
                              tm_fc_distributor a
                              LEFT JOIN tr_customer b
                              ON (a.i_customer = b.i_customer)
                            WHERE
                              a.e_fc_periode = '$yearmonth'
                              AND a.i_customer='$icustomer'
                            ",FALSE);
  }

  public function cek_datadetail($yearmonth){
    return $this->db->query("
                          SELECT
                             b.i_product,
                             b.i_color,
                             b.n_quantity as jumlah,
                             c.e_product_basename 
                          FROM
                             tm_fc_distributor a 
                             JOIN
                                tm_fc_distributor_detail b 
                                ON (a.i_fc = b.i_fc) 
                             LEFT JOIN
                                tr_product_base c 
                                ON (b.i_product = c.i_product_motif) 
                          WHERE
                             a.e_fc_periode = '$yearmonth'
                          order by
                            b.i_product
                          ", FALSE);
  }

  public function updateheader($ifc){
    $dentry = date('Y-m-d');   
    $data = array(
        'd_update'      => $dentry
    );
   $this->db->where('i_fc', $ifc);
   $this->db->update('tm_fc_distributor', $data);
  }

  public function updatedetail($ifc, $iproduct, $nquantity){

      $data = array(
            'n_quantity' => $nquantity, 
      );
     $this->db->where('i_fc', $ifc);
     $this->db->where('i_product', $iproduct);
     $this->db->update('tm_fc_distributor_detail', $data);
  }
}
/* End of file Mmaster.php */