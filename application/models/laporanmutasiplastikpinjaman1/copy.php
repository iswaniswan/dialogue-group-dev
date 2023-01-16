<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("select e_unit_name, a.i_sj, a.d_sj, a.i_periode_forcast, a.e_jenis_keluar, a.f_cancel, a.f_receive, a.e_remark, i_sumber, $i_menu as i_menu
                    FROM (
                      SELECT a.i_sj, a.d_sj, a.i_periode_forcast, a.i_jenis, b.e_jenis_keluar, 
                      a.e_remark, a.i_unit_jahit AS i_unit, c.e_unitjahit_name AS e_unit_name, 
                      a.f_cancel, a.f_receive, a.d_receive, 'UJ' AS i_sumber 
                      FROM duta_prod.tm_sjkeluar_unitjahit a, duta_prod.tr_jenis_keluarunitjahit b, duta_prod.tr_unit_jahit c 
                      WHERE a.f_cancel='false' 
                      AND (a.i_jenis='1' OR a.i_jenis='2') 
                      AND a.i_jenis=b.i_jenis 
                      AND a.i_tujuan_kirim='G07' 
                      AND a.i_unit_jahit=c.i_unit_jahit 

                    UNION ALL 

                      SELECT a.i_sj, a.d_sj, a.i_periode_forcast, a.i_jenis, b.e_jenis_keluar, a.e_remark, a.i_unit_packing AS i_unit, 
                      c.e_nama_packing AS e_unit_name, a.f_cancel, a.f_receive, a.d_receive, 'UP' AS i_sumber 
                      FROM duta_prod.tm_sj_keluarpacking a, duta_prod.tr_jenis_keluarpacking b, duta_prod.tr_unit_packing c 
                      WHERE a.f_cancel='false' 
                      AND (a.i_jenis='1' OR a.i_jenis='2') 
                      AND a.i_jenis=b.i_jenis 
                      AND a.i_tujuan_kirim='G07' 
                      AND a.i_unit_packing=c.i_unit_packing

                    UNION ALL 

                      SELECT a.i_sj, a.d_sj, '' AS i_periode_forcast, a.i_jenis, b.e_jenis_keluar, a.e_remark, 'G08' AS i_unit, 
                      'Gudang Jadi' AS e_unit_name, a.f_cancel, a.f_receive, a.d_receive, 'G08' AS i_sumber 
                      FROM duta_prod.tm_sjkeluar_gdjadi a, duta_prod.tr_jenis_keluargdjadi b 
                      WHERE a.f_cancel='false' 
                      AND a.i_jenis='6' 
                      AND a.i_jenis=b.i_jenis
                      ) a ORDER BY d_sj DESC, i_sumber",false);
          //select a.i_sj, a.d_sj, a.i_periode_forcast, a.i_jenis, a.e_jenis_keluar, a.e_remark, i_unit, e_unit_name, a.f_cancel, a.f_receive, a.d_receive, i_sumber, 

    $datatables->edit('f_cancel', function ($data) {
            $f_cancel = trim($data['f_cancel']);
            $f_receive = trim($data['f_receive']);
            if($f_cancel == 't'){
               return  "Batal";
            }else if($f_receive == 'f'){
              return "Belum Approve";
            }else{
              return "Approve";
            }
    });
 
    $datatables->add('action', function ($data) {
            $isj     = trim($data['i_sj']);
            $isumber = trim($data['i_sumber']);
            $i_menu  = $data['i_menu'];
            $data    = '';
        
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"sjmasukqc/cform/edit/$isj/$isumber\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
      return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('f_receive');
        $datatables->hide('i_sumber');
        return $datatables->generate();
  }

  public function cek_data($isj, $isumber){
          $this->db->select("a.i_sj, a.d_sj, a.i_periode_forcast, a.i_jenis, a.e_jenis_keluar, a.e_remark, i_unit, e_unit_name, a.f_cancel, 
              a.f_receive, a.d_receive, i_sumber 
              FROM (
              SELECT a.i_sj, a.d_sj, a.i_periode_forcast, a.i_jenis, b.e_jenis_keluar, 
              a.e_remark, a.i_unit_jahit AS i_unit, c.e_unitjahit_name AS e_unit_name, 
              a.f_cancel, a.f_receive, a.d_receive, 'UJ' AS i_sumber 
              FROM tm_sjkeluar_unitjahit a, tr_jenis_keluarunitjahit b, tr_unit_jahit c 
              WHERE a.f_cancel='false' 
              AND (a.i_jenis='1' OR a.i_jenis='2') 
              AND a.i_jenis=b.i_jenis 
              AND a.i_tujuan_kirim='G07' 
              AND a.i_unit_jahit=c.i_unit_jahit 

              UNION ALL 

              SELECT a.i_sj, a.d_sj, a.i_periode_forcast, a.i_jenis, b.e_jenis_keluar, a.e_remark, a.i_unit_packing AS i_unit, 
              c.e_nama_packing AS e_unit_name, a.f_cancel, a.f_receive, a.d_receive, 'UP' AS i_sumber 
              FROM tm_sj_keluarpacking a, tr_jenis_keluarpacking b, tr_unit_packing c 
              WHERE a.f_cancel='false' AND (a.i_jenis='1' OR a.i_jenis='2') 
              AND a.i_jenis=b.i_jenis AND a.i_tujuan_kirim='G07' 
              AND a.i_unit_packing=c.i_unit_packing

              UNION ALL 

              SELECT a.i_sj, a.d_sj, '' AS i_periode_forcast, a.i_jenis, b.e_jenis_keluar, a.e_remark, 'G08' AS i_unit, 
              'Gudang Jadi' AS e_unit_name, a.f_cancel, a.f_receive, a.d_receive, 'G08' AS i_sumber 
              FROM tm_sjkeluar_gdjadi a, tr_jenis_keluargdjadi b 
              WHERE a.f_cancel='false' AND a.i_jenis='6' 
              AND a.i_jenis=b.i_jenis
              ) a WHERE a.i_sj='$isj' AND a.i_sumber='$isumber' 
              ORDER BY d_sj DESC, i_sumber", false);
      return $this->db->get();
    }

    public function cek_datadetail($isj, $isumber){
        $this->db->select("a.i_sj, a.i_product, a.e_product_name, a.i_color, a.n_quantity, a.e_remark, a.n_item_no, a.f_item_cancel, 
              a.e_color_name, a.i_sumber 
              FROM( 
              SELECT a.i_sj, b.i_product, b.e_product_name, b.i_color, b.n_quantity, b.e_remark, b.n_item_no, b.f_item_cancel, 
              c.e_color_name, 'UJ' AS i_sumber 
              FROM tm_sjkeluar_unitjahit a 
              INNER JOIN tm_sjkeluar_unitjahit_item b ON a.i_sj=b.i_sj 
              INNER JOIN tr_color c ON b.i_color=c.i_color
              WHERE a.f_cancel='false' 

              UNION ALL 

              SELECT a.i_sj, b.i_product, b.e_product_name, b.i_color, b.n_quantity, b.e_remark, b.n_item_no, b.f_item_cancel, 
              c.e_color_name, 'UP' AS i_sumber 
              FROM tm_sj_keluarpacking a 
              INNER JOIN tm_sj_keluarpacking_item b ON a.i_sj=b.i_sj 
              INNER JOIN tr_color c ON b.i_color=c.i_color 
              WHERE a.f_cancel='false' 

              UNION ALL 

              SELECT a.i_sj, b.i_product, b.e_product_name, b.i_color, b.n_quantity, b.e_remark, b.n_item_no, b.f_item_cancel, 
              c.e_color_name, 'G08' AS i_sumber 
              FROM tm_sjkeluar_gdjadi a 
              INNER JOIN tm_sjkeluar_gdjadi_item b ON a.i_sj=b.i_sj 
              INNER JOIN tr_color c ON b.i_color=c.i_color
              WHERE a.f_cancel='false' 
              
              ) a 
              WHERE a.i_sj='$isj' AND a.i_sumber='$isumber' 
              order by a.n_item_no");
        return $this->db->get();
    }

    function deleteheader($isj,$isumber){
      $this->db->query("DELETE FROM tm_sjmasuk_gudangqc WHERE i_sj='$isj' AND i_sumber='$isumber' ");
    }

    function insertheader($isj, $dsj, $isumber, $iunitjahit, $ijenis, $eremark, $datereceive){ 
    $dentry = date("Y-m-d H:i:s");
  
      $data = array(
                'i_sj'        => $isj,
                'd_sj'        => $dsj,
                'i_sumber'    => $isumber,
                'i_unit'      => $iunitjahit,
                'i_jenis'     => $ijenis,
                'e_remark'    => $eremark,
                'd_receive'   => $datereceive,
                'd_entry'     => $dentry
      );
      $this->db->insert('tm_sjmasuk_gudangqc', $data);
    }

    function updateheader($isj,$datereceive,$isumber){ 
    $dreceiveentry = date("Y-m-d H:i:s");
      $this->db->set(
        array(
          'd_receive'         => $datereceive,
          'f_receive'         => TRUE,
          'd_receive_entry'   => $dreceiveentry
        )
      );

      $this->db->where('i_sj',$isj);

      if($isumber=='UJ'){
        $this->db->update('tm_sjkeluar_unitjahit');
      }elseif ($isumber=='UP'){
        $this->db->update('tm_sj_keluarpacking');
      }elseif ($isumber=='G08'){
        $this->db->update('tm_sjkeluar_gdjadi');
      } 
    }

    function deletedetail($iproduct, $icolor, $isj, $isumber) {
      $this->db->query("DELETE FROM tm_sjmasuk_gudangqc_item WHERE i_sj='$isj' AND i_product='$iproduct' AND i_color='$icolor' AND i_sumber='$isumber' ");
    }

    function insertdetail($iproduct, $eproductname, $icolor, $ecolorname, $nquantity, $eremark, $isj, $inoitem, $dsj, $isumber){
      
      $data = array(
              'i_product'       => $iproduct,
              'e_product_name'  => $eproductname,
              'i_color'         => $icolor,
              'n_pemenuhan'     => $nquantity,
              'e_remark'        => $eremark,
              'i_sj'            => $isj,
              'i_sumber'        => $isumber,
              'n_item_no'       => $inoitem
      );
      $this->db->insert('tm_sjmasuk_gudangqc_item', $data);
    }
}
/* End of file Mmaster.php */