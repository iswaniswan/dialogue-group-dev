<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("select a.i_sj,  a.i_memo, a.d_sj, a.d_memo, b.e_customer_name, a.f_sj_cancel, '$i_menu' as i_menu  
                        from tm_sj_keluar_penjualanaksesoris a
                        inner join tr_customer b on a.i_customer = b.i_customer
                        order by i_sj");

    $datatables->edit('f_sj_cancel', function ($data) {
          $f_sj_cancel = trim($data['f_sj_cancel']);
          if($f_sj_cancel == 't'){
             return "Batal";
          }else if($f_sj_cancel == 'f'){
            return "Aktif";
          }
      });

    $datatables->add('action', function ($data) {
            $ibonk = trim($data['i_sj']);
            $i_menu = $data['i_menu'];
            $data = '';
            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"sjkeluaraksesoris/cform/view/$ibonk/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"sjkeluaraksesoris/cform/edit/$ibonk/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
      return $data;
        });
        $datatables->hide('i_menu');
        return $datatables->generate();
  }

  function bacadetail($ischedule){
    $this->db->select("a.i_schedule, a.d_schedule, b.i_product, b.e_product_name, b.i_color, c.nama as e_color_name, b.n_quantity, b.n_pemenuhan as saldo, b.e_remark, b.f_item_cancel
               FROM tm_schedule a, tm_schedule_item b, tm_warna c
               WHERE a.i_schedule = b.i_schedule
               AND b.i_color = c.id
               AND a.i_schedule = '$ischedule'
               AND b.n_quantity <> b.n_pemenuhan
               order by b.n_item_no", false);
    return $this->db->get();
  }
  
  function runningnumberbonk($thbl){
      $th = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
      $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SJA'
                          and i_area='00'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nobonmk  =$terakhir+1;
        $this->db->query(" update tm_dgu_no 
                                set n_modul_no=$nobonmk
                                where i_modul='SJA'
                                and e_periode='$asal' 
                                and i_area='00'
                                and substring(e_periode,1,4)='$th'", false);
        settype($nobonmk,"string");
        $a=strlen($nobonmk);
        while($a<6){
          $nobonmk="0".$nobonmk;
          $a=strlen($nobonmk);
        }
          $nobonmk  ="SJA-".$thbl."-".$nobonmk;
        return $nobonmk;
      }else{
        $nobonmk  ="000001";
        $nobonmk  ="SJA-".$thbl."-".$nobonmk;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJA','00','$asal',1)");
        return $nobonmk;
      }
    }

    public function getmemo($imemo){
      return $this->db->query("select a.*, b.e_customer_name, b.e_customer_address, b.v_customer_discount from tm_opaksesoris a
      inner join tr_customer b on (a.i_customer = b.i_customer) where i_op_code ='$imemo'", false);
  }

  public function getmemo_detail($imemo){
      return $this->db->query(" select a.*, c.e_satuan, b.i_satuan_code
                                from tm_op_itemaksesoris a 
                                inner join tr_material b on (a.i_product = b.i_material)
                                inner join tr_satuan c on (b.i_satuan_code = c.i_satuan_code)
                                where  i_op_code = '$imemo' and n_delivery < n_order", false);
  }

  public function insertheader($isj, $icustomer, $dsj, $ikodemaster, $imemo, $dmemo,$eremark){  
        $dentry = date("Y-m-d H:i:s");
        $data = array(
          'i_sj'          =>$isj,
          'i_customer'    =>$icustomer,
          'd_sj'          =>$dsj,
          'd_entry'       =>$dentry,
          'i_kode_master' =>$ikodemaster,
          'i_memo'        =>$imemo,
          'd_memo'        =>$dmemo,
          'e_remark'      =>$eremark
        );       
        $this->db->insert('tm_sj_keluar_penjualanaksesoris', $data);
    }

    function insertdetail($isj, $iproduct, $i, $qty, $remark, $isatuan){

        $data = array(
          'i_sj'      =>$isj,
          'i_product' =>$iproduct,
          'n_item_no' =>$i,
          'n_quantity'=>$qty,
          'i_satuan'  =>$isatuan,
          'e_remark'  =>$remark
        );
        $this->db->insert('tm_sj_keluar_penjualanaksesoris_item', $data);
    }

  //   function insertbonkdetailitem($iproduct,$icolor,$npemenuhan,$ibonk,$ischedule,$nitemno, $imaterial, $nquantity){
  //     $dentry = date("Y-m-d H:i:s");
  //     $data = array(
  //           // 'i_bonk'         => $ibonk,
  //           // 'i_spbb'         => $ischedule,
  //           // 'i_schedule'     => $isched,
  //           // 'i_product'      => $iproduct,
  //           // 'e_product_name' => $eproductname,
  //           // 'i_color'        => $icolor,
  //           // 'n_quantity'     => $npemenuhan,
  //           // 'e_remark'       => $eremark,
  //           // 'n_item_no'      => $nitemno,
  //           'i_bonk'          => $ibonk  ,
  //           'i_product'       => $iproduct  ,
  //           'i_color'         => $icolor ,
  //           'i_material'      => $imaterial  ,
  //           'n_quantity'      => $nquantity ,
  //           'd_entry'         => $dentry ,
  //           'n_item_no'       => $nitemno ,
  //           'n_no'            => $nitemno ,
  //           'n_pemenuhan'     => $npemenuhan 
  //     );
  //     $this->db->insert('tm_bonmkeluar_cutting_itemdetail', $data);
  // }

    // function updatescheduledetail($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk){
    // $query  = $this->db->query("SELECT SUM(n_pemenuhan) as saldo FROM tm_schedule_item
    //               WHERE i_product='$iproduct'
    //               AND i_color='$icolor'
    //               AND f_item_cancel='FALSE'
    //               AND i_schedule='$ischedule'");
    //     if($query->num_rows()>0){
    //     $row  = $query->row();
    //     $nsaldo = $row->saldo;
    //     $nsaldopemenuhan = $nsaldo + $npemenuhan;
    //       $this->db->set(
    //         array(
    //           'i_bonk'      => $ibonk,
    //           'd_bonk'      => $datebonk,
    //           'n_pemenuhan' => $nsaldopemenuhan,
    //         )
    //       );
    //       $this->db->where('i_schedule',$ischedule);
    //       $this->db->where('i_product',$iproduct);
    //       $this->db->where('i_color',$icolor);
    //       $this->db->where('n_quantity',$nquantity);
    //       $this->db->update('tm_schedule_item');
    //     }else{
    //       $this->db->set(
    //         array(
    //           'i_bonk'      => $ibonk,
    //           'd_bonk'      => $datebonk,
    //           'n_pemenuhan' => $npemenuhan,
    //         )
    //       );
    //       $this->db->where('i_schedule',$ischedule);
    //       $this->db->where('i_product',$iproduct);
    //       $this->db->where('i_color',$icolor);
    //       $this->db->where('n_quantity',$nquantity);
    //       $this->db->update('tm_schedule_item');
    //     }
    //   }


    function updatememo($imemo, $iproduct, $qtydelivery){
      // if()    
      $this->db->set(
            
            array(
              'f_do_created' => TRUE,
              'f_delivered' => TRUE,
              'n_delivery'  => $qtydelivery
            )
          );
          $this->db->where('i_op_code',$imemo);
          $this->db->where('i_product',$iproduct);
          $this->db->update('tm_op_itemaksesoris');
    }

    public function bacamemo(){          
          $this->db->select("* from tm_opaksesoris ", false);
          return $this->db->get();
    }

    public function bacagudang(){          
      $this->db->select("* from tr_master_gudang where i_kode_master = 'GD10002' ", false);
      return $this->db->get();
    }

    public function cek_schedule($ibonk){          
          $this->db->select("distinct(i_schedule) as i_schedule from tm_bonmkeluar_cutting_item where i_bonk ='$ibonk'", false);
          return $this->db->get();
    }

    public function cek_datadetail($ibonk){
          $this->db->select(" a.*, b.e_material_name, c.e_satuan
                              from tm_sj_keluar_penjualanaksesoris_item a 
                              inner join tr_material b on a.i_product = b.i_material
                              inner join tr_satuan c on a.i_satuan = c.i_satuan_code
                              where i_sj = '$ibonk' order by a.i_product",false);
           return $this->db->get();
    }

    public function cek_datadetail2($ibonk){
      $this->db->select(" a.i_product, a.n_delivery from tm_op_itemaksesoris a
                          inner join tm_opaksesoris b on a.i_op_code = b.i_op_code 
                          inner join tm_sj_keluar_penjualanaksesoris c on a.i_op_code = c.i_memo
                          where c.i_sj = '$ibonk' order by a.i_product",false);
       return $this->db->get();
}

    public function cek_data($ibonk){
      $this->db->select(" a.*, b.e_customer_name 
                          from tm_sj_keluar_penjualanaksesoris a
                          inner join tr_customer b on a.i_customer = b.i_customer
                          where i_sj = '$ibonk'",false);
       return $this->db->get();
}

    function updateheader($isj, $dsj, $eremarkh){ 
    $dupdate  = date("Y-m-d H:i:s");
    $query= $this->db->query("select i_memo from tm_sj_keluar_penjualanaksesoris where i_sj = '$isj'");
        
    $imemo = $query->row()->i_memo;
  
        $data = array(
            'e_op_remark'  => $eremarkh,
        );
      $this->db->where('i_memo',$imemo);
      $this->db->update('tm_opaksesoris', $data);
    }

    function updatedetail($isj, $iproduct, $nquantity){ 
      // $dupdate  = date("Y-m-d H:i:s");
      
    
          $data = array(
              'n_quantity'  => $nquantity,
          );
        $this->db->where('i_sj',$isj);
        $this->db->where('i_product',$iproduct);
        $this->db->update('tm_sj_keluar_penjualanaksesoris_item', $data);
      }

      function updatedetailop($isj, $iproduct, $nquantity){ 
        // $dupdate  = date("Y-m-d H:i:s");

        $query= $this->db->query("select i_memo from tm_sj_keluar_penjualanaksesoris where i_sj = '$isj'");
        
        $imemo = $query->row()->i_memo;
        
      
            $data = array(
                'n_delivery'  => $nquantity,
            );
          $this->db->where('i_op_code',$imemo);
          $this->db->where('i_product',$iproduct);
          $this->db->update('tm_op_itemaksesoris', $data);
        }

    function updatescheduledetail($isj, $dsj, $eremarkh){ 
      $dupdate  = date("Y-m-d H:i:s");
    
          $data = array(
              'e_remark'  => $eremarkh,
          );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_opaksesoris', $data);
      }

    function deletedetail($ibonk,$iproduct,$ischedule,$icolor){
      $this->db->query("DELETE FROM tm_bonmkeluar_cutting_item WHERE i_schedule='$ischedule' and i_bonk='$ibonk' and i_product='$iproduct' and i_color='$icolor' ");
    }

     function insertdetail2($ischedule,$iproduct,$icolor,$eproductname,$nquantity,$npemenuhan,$nsaldo,$eremark,$ibonk,$fitemcancel,$nitemno){ 
    
        $data = array(
            'i_product'           => $iproduct,
            'e_product_name'      => $eproductname,
            'i_color'             => $icolor,
            'n_quantity'          => $nquantity,
            'e_remark'            => $eremark,
            'i_bonk'              => $ibonk,
            'i_schedule'          => $ischedule,
            'n_item_no'           => $nitemno,
            'f_item_cancel'       => $fitemcancel
        );
      $this->db->insert('tm_bonmkeluar_cutting_item', $data);
    }

    function updatesaldo($ibonk,$dbonk,$ischedule,$iproduct,$icolor){
    $query  = $this->db->query("SELECT SUM(n_quantity) as saldo FROM tm_bonmkeluar_cutting_item
                  WHERE i_product='$iproduct'
                  AND i_color='$icolor'
                  AND f_item_cancel='FALSE'
                  AND i_schedule='$ischedule'");
    $row  = $query->row();
    $nsaldopemenuhan = $row->saldo;
      $this->db->set(
        array(
          'i_bonk'      => $ibonk,
          'd_bonk'      => $dbonk,
          'n_pemenuhan' => $nsaldopemenuhan,
        )
      );
      $this->db->where('i_schedule',$ischedule);
      $this->db->where('i_product',$iproduct);
      $this->db->where('i_color',$icolor);
      $this->db->update('ttm_schedule_item');
    }
}
/* End of file Mmaster.php */