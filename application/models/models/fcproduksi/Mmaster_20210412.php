<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

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

  function runningnumber($yearmonth){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='FCP'
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
                      where i_modul='FCP'
                      and e_periode='$asal' 
                      and substring(e_periode,1,4)='$th'", false);
          settype($nopp,"string");
          $a=strlen($nopp);
          while($a<5){
            $nopp="0".$nopp;
            $a=strlen($nopp);
          }
            $nopp  ="FCP-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="FCP-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('FCP','','$asal',1)");
          return $nopp;
        }
  }

  public function cekfc($dfrom){
    return $this->db->query("
                            SELECT
                              i_fc
                            FROM
                              tm_fc_produksi
                            WHERE
                              d_fc = '$dfrom'
                            ",false);
  }

  public function insertheader($ifc, $dfrom, $yearmonth){         
    $dentry   = date('Y-m-d');   
    $this->db->set(
      array(
        'i_fc'         => $ifc,
        'd_fc'       => $dfrom,
        // 'd_to'         => $dto,
        'e_fc_periode' => $yearmonth,
        'f_cancel'     => 'f',
        'd_entry'      => $dentry
      ) 
    );
    $this->db->insert('tm_fc_produksi');
  }

  public function updateheaderfc($ifc, $dfrom, $yearmonth){
        $dentry = date('Y-m-d');   
        $data = array(
            'd_fc'        => $dfrom,
            // 'd_to'          => $dto,
            'e_fc_periode'  => $yearmonth,
            'd_update'      => $dentry
        );
       $this->db->where('i_fc', $ifc);
       $this->db->update('tm_fc_provduksi', $data);
  }

  public function deletedetail($ifc){
        $this->db->query("
                          DELETE 
                          FROM 
                            tm_fc_produksi_detail
                          WHERE 
                            i_fc='$ifc'
                          ");
  }

  public function deletedetailinput($iproduct){
    $this->db->query("
                    DELETE FROM 
                      tm_fc_produksi_detail 
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
    $this->db->insert('tm_fc_produksi_detail', $data);
  }

  public function cek_eksport($dfrom,$dto){
    return $this->db->query("
                            SELECT
                               a.d_fc,
                               b.i_product,
                               b.i_color,
                               b.n_quantity as jumlah,
                               c.e_product_basename 
                            FROM
                               tm_fc_produksi a 
                               JOIN
                                  tm_fc_produksi_detail b 
                                  ON (a.i_fc = b.i_fc) 
                               LEFT JOIN
                                  tr_product_base c 
                                  ON (b.i_product = c.i_product_motif) 
                            WHERE
                               a.d_fc >= '$dfrom'
                               and a.d_fc <= '$dto'
                            order by
                              b.i_product
                            ", FALSE);
  }

  public function bacafc($dfrom,$dto){
    return $this->db->query("
                            SELECT
                              i_fc
                            FROM
                              tm_fc_produksi
                            WHERE
                              d_fc >= '$dfrom'
                              and d_fc <= '$dto'
                            ",FALSE);
  }

  public function bacadetailfc($ifc,$dfrom,$dto){

    $tmp       = explode('-', $dfrom);
    $day       = $tmp[0];
    $month     = $tmp[1];
    $year      = $tmp[2];
    $yearmonthfrom = $year.$month;
  
    if($ifc == 'ALL'){
      return $this->db->query("
                              SELECT
                                 b.i_product,
                                 b.i_color,
                                 sum(b.n_quantity) as n_quantity,
                                 c.e_product_basename 
                              FROM
                                 tm_fc_produksi a 
                                 JOIN
                                    tm_fc_produksi_detail b 
                                    ON (a.i_fc = b.i_fc) 
                                 LEFT JOIN
                                    tr_product_base c 
                                    ON (b.i_product = c.i_product_motif) 
                              WHERE
                                 e_fc_periode = '$yearmonthfrom'
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
                                tm_fc_produksi_detail a
                                LEFT JOIN tm_fc_produksi c ON (a.i_fc = c.i_fc)
                                LEFT JOIN tr_product_base b ON (a.i_product = b.i_product_motif)
                              WHERE
                                a.i_fc = '$ifc'
                              ", FALSE);
    }
  }

  public function cek_dataheader($dfrom,$dto){
    return $this->db->query("
                            SELECT
                              *
                            FROM
                              tm_fc_produksi
                            WHERE
                              d_fc >= '$dfrom'
                              and d_fc <= '$dto'
                            ",FALSE);
  }

  public function cek_datadetail($dfrom, $dto){
    // var_dump($dfrom, $dto);
    $tmp       = explode('-', $dfrom);
    $day       = $tmp[2];
    $month     = $tmp[1];
    $year      = $tmp[0];
    $yearmonthfrom = $year.$month;
    // var_dump($yearmonthfrom);

    $tmp       = explode('-', $dto);
    $day       = $tmp[2];
    $month     = $tmp[1];
    $year      = $tmp[0];
    $yearmonthto = $year.$month;
    // var_dump($yearmonthto);

    if($yearmonthfrom == $yearmonthto){
      return $this->db->query("
                            SELECT
                               b.i_product,
                               b.i_color,
                               sum(b.n_quantity) as jumlah,
                               c.e_product_basename 
                            FROM
                               tm_fc_produksi a 
                               JOIN
                                  tm_fc_produksi_detail b 
                                  ON (a.i_fc = b.i_fc) 
                               LEFT JOIN
                                  tr_product_base c 
                                  ON (b.i_product = c.i_product_motif) 
                            WHERE
                               e_fc_periode = '$yearmonthfrom'
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
                               a.d_fc,
                               b.i_product,
                               b.i_color,
                               b.n_quantity as jumlah,
                               c.e_product_basename 
                            FROM
                               tm_fc_produksi a 
                               JOIN
                                  tm_fc_produksi_detail b 
                                  ON (a.i_fc = b.i_fc) 
                               LEFT JOIN
                                  tr_product_base c 
                                  ON (b.i_product = c.i_product_motif) 
                            WHERE
                               a.d_fc >= '$dfrom'
                               and a.d_fc <= '$dto'
                            order by
                              b.i_product
                            ", FALSE);
    }
  }

  public function updateheader($ifc,$yearmonth){
    $dentry = date('Y-m-d');   
    $data = array(
        'd_update'      => $dentry
    );
   $this->db->where('i_fc', $ifc);
   $this->db->update('tm_fc_produksi', $data);
  }

  public function updatedetail($ifc, $iproduct, $nquantity){

      $data = array(
            'n_quantity' => $nquantity, 
      );
     $this->db->where('i_fc', $ifc);
     $this->db->where('i_product', $iproduct);
     $this->db->update('tm_fc_produksi_detail', $data);
  }
}
/* End of file Mmaster.php */