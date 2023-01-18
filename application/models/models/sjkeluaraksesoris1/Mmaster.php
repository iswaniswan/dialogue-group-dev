<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu, $from, $to){
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("select d_bonk, i_bonk, e_remark, d_entry, d_update, f_bonk_cancel, '$i_menu' as i_menu  
                    FROM tm_bonmkeluar_cutting 
                    WHERE (d_bonk >= to_date('$from','dd-mm-yyyy')
                    and d_bonk <= to_date('$to','dd-mm-yyyy')) order by i_bonk");

    $datatables->edit('f_bonk_cancel', function ($data) {
          $f_bonk_cancel = trim($data['f_bonk_cancel']);
          if($f_bonk_cancel == 't'){
             return "Batal";
          }else if($f_bonk_cancel == 'f'){
            return "Aktif";
          }
      });

    $datatables->add('action', function ($data) {
            $ibonk = trim($data['i_bonk']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"bonmkeluarcutting/cform/view/$ibonk/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bonmkeluarcutting/cform/edit/$ibonk/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
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
                          where i_modul='CBK'
                          and i_area='CT'
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
                                where i_modul='CBK'
                                and e_periode='$asal' 
                                and i_area='CT'
                                and substring(e_periode,1,4)='$th'", false);
        settype($nobonmk,"string");
        $a=strlen($nobonmk);
        while($a<6){
          $nobonmk="0".$nobonmk;
          $a=strlen($nobonmk);
        }
          $nobonmk  ="CBK-".$thbl."-".$nobonmk;
        return $nobonmk;
      }else{
        $nobonmk  ="000001";
        $nobonmk  ="CBK-".$thbl."-".$nobonmk;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('CBK','CT','$asal',1)");
        return $nobonmk;
      }
    }

    public function getspbb($ispbb){
      return $this->db->query("
          select * from tm_spbb
          where i_spbb='$ispbb'
        ", false);
  }

  public function getspbb_detail($ispbb){
      return $this->db->query("
      select a.*,  b.e_color_name, c.e_product_name ,c.e_material_name 
      from tm_spbb_itemdetail a
      inner join tr_color b on(a.i_color = b.i_color)
      inner join tm_spbb_item c on(a.i_product = c.i_product and a.i_material = c.i_material)
            where a.i_spbb = '$ispbb'
        ", false);
  }

  public function insertheader($ibonk, $datebonk, $eremarkh, $igudang){  
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_bonk'        => $ibonk,
              'd_bonk'        => $datebonk,
              'e_remark'      => $eremarkh,
              'i_gudang'      => $igudang,
              'd_entry'       => $dentry,
        );       
        $this->db->insert('tm_bonmkeluar_cutting', $data);
    }

    function insertbonkdetail($iproduct,$eproductname,$icolor,$npemenuhan,$eremark,$ibonk,$ischedule,$nitemno,$isched){

        $data = array(
              'i_bonk'         => $ibonk,
              'i_spbb'         => $ischedule,
              'i_schedule'     => $isched,
              'i_product'      => $iproduct,
              'e_product_name' => $eproductname,
              'i_color'        => $icolor,
              'n_quantity'     => $npemenuhan,
              'e_remark'       => $eremark,
              'n_item_no'      => $nitemno,
        );
        $this->db->insert('tm_bonmkeluar_cutting_item', $data);
    }

    function insertbonkdetailitem($iproduct,$icolor,$npemenuhan,$ibonk,$ischedule,$nitemno, $imaterial, $nquantity){
      $dentry = date("Y-m-d H:i:s");
      $data = array(
            // 'i_bonk'         => $ibonk,
            // 'i_spbb'         => $ischedule,
            // 'i_schedule'     => $isched,
            // 'i_product'      => $iproduct,
            // 'e_product_name' => $eproductname,
            // 'i_color'        => $icolor,
            // 'n_quantity'     => $npemenuhan,
            // 'e_remark'       => $eremark,
            // 'n_item_no'      => $nitemno,
            'i_bonk'          => $ibonk  ,
            'i_product'       => $iproduct  ,
            'i_color'         => $icolor ,
            'i_material'      => $imaterial  ,
            'n_quantity'      => $nquantity ,
            'd_entry'         => $dentry ,
            'n_item_no'       => $nitemno ,
            'n_no'            => $nitemno ,
            'n_pemenuhan'     => $npemenuhan 
      );
      $this->db->insert('tm_bonmkeluar_cutting_itemdetail', $data);
  }

    function updatescheduledetail($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk){
    $query  = $this->db->query("SELECT SUM(n_pemenuhan) as saldo FROM tm_schedule_item
                  WHERE i_product='$iproduct'
                  AND i_color='$icolor'
                  AND f_item_cancel='FALSE'
                  AND i_schedule='$ischedule'");
        if($query->num_rows()>0){
        $row  = $query->row();
        $nsaldo = $row->saldo;
        $nsaldopemenuhan = $nsaldo + $npemenuhan;
          $this->db->set(
            array(
              'i_bonk'      => $ibonk,
              'd_bonk'      => $datebonk,
              'n_pemenuhan' => $nsaldopemenuhan,
            )
          );
          $this->db->where('i_schedule',$ischedule);
          $this->db->where('i_product',$iproduct);
          $this->db->where('i_color',$icolor);
          $this->db->where('n_quantity',$nquantity);
          $this->db->update('tm_schedule_item');
        }else{
          $this->db->set(
            array(
              'i_bonk'      => $ibonk,
              'd_bonk'      => $datebonk,
              'n_pemenuhan' => $npemenuhan,
            )
          );
          $this->db->where('i_schedule',$ischedule);
          $this->db->where('i_product',$iproduct);
          $this->db->where('i_color',$icolor);
          $this->db->where('n_quantity',$nquantity);
          $this->db->update('tm_schedule_item');
        }
      }


    function updateheaderschedule($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk){
        $query  = $this->db->query("select i_schedule, i_bonk , n_quantity , n_pemenuhan from tm_schedule_item where i_schedule='$ischedule' and n_quantity <> n_pemenuhan ");


        $row        = $query->row();
        $nqty       = $row->n_quantity;
        $npemenuhan = $row->n_pemenuhan;
        
        if ($nqty == $npemenuhan){
          $this->db->set(
            array(
              'f_status_complete' => TRUE,
            )
          );
          $this->db->where('i_schedule',$ischedule);
          $this->db->update('tm_schedule');
        }
    }

    public function cek_data($ibonk){          
          $this->db->select("a.* ,b.id,b.e_nama_master as e_gudang_name from tm_bonmkeluar_cutting a 
                          LEFT JOIN tr_master_gudang b ON a.i_gudang=b.i_kode_master where a.i_bonk ='$ibonk' ", false);
          return $this->db->get();
    }

    public function cek_schedule($ibonk){          
          $this->db->select("distinct(i_schedule) as i_schedule from tm_bonmkeluar_cutting_item where i_bonk ='$ibonk'", false);
          return $this->db->get();
    }

    public function cek_datadetail($ibonk){
          $this->db->select("a.* , b.nama as warna, c.n_pemenuhan from tm_bonmkeluar_cutting_item a 
               LEFT JOIN tm_warna b ON a.i_color=b.id 
               LEFT JOIN tm_schedule_item c ON a.i_schedule=c.i_schedule and a.i_product=c.i_product
               and a.i_color=c.i_color
               WHERE a.i_bonk='$ibonk'
               and a.f_item_cancel='false'
               group by a.i_bonk , a.i_product , a.e_product_name, a.i_color, a.n_quantity, 
               c.n_pemenuhan,a.e_remark,a.i_schedule, a.n_item_no,b.nama,c.n_pemenuhan
               order by a.n_item_no",false);
           return $this->db->get();
    }

    function updateheader($ibonk,$datebonk,$ischedule,$igudang,$eremark){ 
    $dupdate  = date("Y-m-d H:i:s");
  
        $data = array(
            'd_bonk'    => $datebonk,
            'd_update'  => $dupdate,
            'i_gudang'  => $igudang,
            'e_remark'  => $eremark,
        );
      $this->db->where('i_bonk',$ibonk);
      $this->db->update('tm_bonmkeluar_cutting', $data);
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