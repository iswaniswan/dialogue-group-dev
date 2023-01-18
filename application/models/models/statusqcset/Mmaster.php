<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("select a.d_spbb, a.i_spbb, a.d_schedule, a.i_schedule, b.e_nama_master as gudang, a.e_remark, a.f_spbb_cancel, '$i_menu' as i_menu  
                FROM tm_spbb a LEFT JOIN tr_master_gudang b ON a.i_gudang=b.i_kode_master 
                group by a.i_spbb, b.e_nama_master order by a.i_spbb");

    $datatables->edit('f_spbb_cancel', function ($data) {
          $f_spbb_cancel = trim($data['f_spbb_cancel']);
          if($f_spbb_cancel == 't'){
             return "Batal";
          }else if($f_spbb_cancel == 'f'){
            return "Gudang";
         
          }
      });

    $datatables->add('action', function ($data) {
            $ispbb = trim($data['i_spbb']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"cuttingpermintaan/cform/view/$ispbb/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"cuttingpermintaan/cform/edit/$ispbb/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
      return $data;
        });
        $datatables->hide('i_menu');
        return $datatables->generate();
  }

  function bacadetail($ischedule){
    $this->db->select("a.i_schedule, a.i_product, a.e_product_name, a.i_color, a.warna, a.i_material, a.material_name, a.n_quantity, a.v_set, a.v_gelar, a.total_gelar, a.panjang_kain, a.f_bisbisan, a.n_item_no from (
      select a.i_schedule , b.i_product ,b.e_product_name, b.i_color ,c.e_color_name as warna ,d.i_material ,e.e_material_name as material_name, b.n_quantity ,d.v_set,d.v_gelar
          ,(b.n_quantity/d.v_set) as total_gelar , ((b.n_quantity/d.v_set*v_gelar)) as panjang_kain,d.f_bisbisan,d.n_item_no
          from duta_prod.tm_schedule a , duta_prod.tm_schedule_item b , duta_prod.tr_color c , duta_prod.tr_polacutting d, duta_prod.tr_material e
          where a.i_schedule=b.i_schedule
          and b.i_color=c.i_color
          and b.i_product=d.i_product
          and b.i_color=d.i_color
          and b.f_item_cancel='false'
          and d.i_material=e.i_material
          and a.i_schedule='$ischedule'
          and d.f_bisbisan='false'
          group by a.i_schedule , b.i_product ,b.e_product_name, b.i_color ,c.e_color_name , b.n_quantity ,d.i_material,material_name, d.v_set, d.v_gelar,d.n_item_no,d.f_bisbisan
          
      union all

      select a.i_schedule , b.i_product ,b.e_product_name, b.i_color ,c.e_color_name as warna ,d.i_material ,e.e_material_name as material_name, b.n_quantity ,d.v_set,d.v_gelar
          , d.n_bagibis as total_gelar , ((b.n_quantity*d.v_gelar*d.v_set)/d.n_bagibis) as panjang_kain ,d.f_bisbisan,d.n_item_no
          from duta_prod.tm_schedule a , duta_prod.tm_schedule_item b , duta_prod.tr_color c , duta_prod.tr_polacutting d, duta_prod.tr_material e
          where a.i_schedule=b.i_schedule
          and b.i_color=c.i_color
          and b.i_product=d.i_product
          and b.i_color=d.i_color
          and b.f_item_cancel='false'
          and d.i_material=e.i_material
          and a.i_schedule='$ischedule'
          and d.f_bisbisan='true'
          group by a.i_schedule , b.i_product ,b.e_product_name, b.i_color ,c.e_color_name , b.n_quantity ,d.i_material,material_name, d.v_set, d.v_gelar,d.n_item_no,d.n_bagibis,d.f_bisbisan
      ) as a
      order by a.i_product, a.f_bisbisan , a.n_item_no", false);
    return $this->db->get();
  }
  
  function runningnumberispbb($thbl){
      $th = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
      $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SPB'
                          and i_area='CT'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nospbb  =$terakhir+1;
        $this->db->query(" update tm_dgu_no 
                                set n_modul_no=$nospbb
                                where i_modul='SPB'
                                and i_area='CT'
                                and e_periode='$asal' 
                                and substring(e_periode,1,4)='$th'", false);
        settype($nospbb,"string");
        $a=strlen($nospbb);
        while($a<3){
          $nospbb="0".$nospbb;
          $a=strlen($nospbb);
        }
          $nospbb  ="SPBB-".$thbl."-CT".$nospbb;
        return $nospbb;
      }else{
        $nospbb  ="001";
        $nospbb  ="SPBB-".$thbl."-CT".$nospbb;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SPB','CT','$asal',1)");
        return $nospbb;
      }
    }

  public function insertheader($ispbb, $datespbb, $ischedule, $dschedule, $eremarkh, $igudang){  
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_spbb'        => $ispbb,
              'd_spbb'        => $datespbb,
              'i_schedule'    => $ischedule,
              'd_schedule'    => $dschedule,
              'e_remark'      => $eremarkh,
              'i_gudang'      => $igudang,
              'd_entry'       => $dentry,
        );       
        $this->db->insert('tm_spbb', $data);
    }

    public function insertdetail($iproduct,$eproductname,$icolor, $imaterial,$ematerialname, $nquantity, $vset, $vgelar, $jumgelar, $pjgkain, $fbisbisan, $nitemno, $ispbb, $datespbb, $ischedule, $dschedule){
        $data = array(
              'i_product'       => $iproduct,
              'e_product_name'  => $eproductname,
              'i_color'         => $icolor,
              'i_material'      => $imaterial,
              'e_material_name' => $ematerialname,
              'n_quantity'      => $nquantity,
              'n_set'           => $vset,
              'n_gelar'         => $vgelar,
              'jumlah_gelar'    => $jumgelar,
              'panjang_kain'    => $pjgkain,
              'i_schedule'      => $ischedule,
              'd_schedule'      => $dschedule,
              'i_spbb'          => $ispbb,
              'd_spbb'          => $datespbb,
              'f_bisbisan'      => $fbisbisan,
              'n_item_no'       => $nitemno,
        );       
        $this->db->insert('tm_spbb_item', $data); 
    }
    
    public function updateheaderschedule($ischedule,$ispbb,$datespbb,$igudang){
        $data = array(
            'i_spbb'    => $ispbb,
            'd_spbb'    => $datespbb,
            'i_gudang'  => $igudang,
    );

    $this->db->where('i_schedule', $ischedule);
    $this->db->update('tm_schedule', $data);
    }

    public function cek_data($ispbb){          
          $this->db->select("a.* , b.e_nama_master as gudang from tm_spbb a ,tr_master_gudang b 
              where a.i_gudang=b.i_kode_master
              and a.i_spbb='$ispbb'
              order by d_spbb", false);
          return $this->db->get();
    }

    public function cek_datadetail($ispbb){
          $this->db->select("a.*, b.e_color_name as warna from tm_spbb_item a, tr_color b 
              where a.i_color=b.i_color             
              and a.i_spbb='$ispbb'
              group by a.i_product, a.e_product_name , a.i_color, a.i_material, a.e_material_name , 
              a.n_quantity , a.n_set , a.n_gelar , a.jumlah_gelar , a.panjang_kain, a.n_pemenuhan ,
              a.i_schedule, a.d_schedule, a.i_spbb, b.e_color_name, a.d_spbb , a.n_item_no, f_spbb_cancel
              ORDER BY a.i_product, a.f_bisbisan , a.n_item_no",false);
           return $this->db->get();
    }

    function updateheader($ispbb,$dspbb,$igudang,$eremarkh){ 
        $dupdate = date("Y-m-d H:i:s");
         $data = array(
            'd_spbb'    => $dspbb,
            'i_gudang'  => $igudang,
            'd_update'  => $dupdate,
            'e_remark'  => $eremarkh,
    );

    $this->db->where('i_spbb', $ispbb);
    $this->db->update('tm_spbb', $data);
    }

    function updateheadersch($ischedule,$ispbb,$dspbb,$igudang){
          $data = array(
                'd_spbb'    => $dspbb,
                'i_gudang'  => $igudang,
          );

    $this->db->where('i_schedule', $ischedule);
    $this->db->update('tm_schedule', $data);
    }

    function deletedetail($ispbb,$iproduct,$icolor,$imaterial){
      $this->db->query("DELETE FROM tm_spbb_item WHERE i_spbb='$ispbb' and i_product='$iproduct' and i_color='$icolor' and i_material='$imaterial'");
    }

     public function detailup($iproduct,$eproductname,$icolor,$imaterial,$ematerialname,$nquantity,$vset,$vgelar,$jumgelar,$pjgkain,$nitemno,$ischedule,$dschedule,$ispbb,$dspbb,$fbisbisan){
        $data = array(
              'i_product'       => $iproduct,
              'e_product_name'  => $eproductname,
              'i_color'         => $icolor,
              'i_material'      => $imaterial,
              'e_material_name' => $ematerialname,
              'n_quantity'      => $nquantity,
              'n_set'           => $vset,
              'n_gelar'         => $vgelar,
              'jumlah_gelar'    => $jumgelar,
              'panjang_kain'    => $pjgkain,
              'i_schedule'      => $ischedule,
              'd_schedule'      => $dschedule,
              'i_spbb'          => $ispbb,
              'd_spbb'          => $dspbb,
              'f_bisbisan'      => $fbisbisan,
              'n_item_no'       => $nitemno,
        );       
        $this->db->insert('tm_spbb_item', $data); 
    }
}
/* End of file Mmaster.php */