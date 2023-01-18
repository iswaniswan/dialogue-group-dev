<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("select a.i_bonm, a.d_bonm, a.i_penerima, a.i_sj, a.e_remark, a.f_bonm_cancel, $i_menu as i_menu 
                        from tm_bonmasuk_returpinjaman a", false); 

    $datatables->edit('f_bonm_cancel', function ($data) {
            $f_bonm_cancel = trim($data['f_bonm_cancel']);
            if($f_bonm_cancel == 't'){
               return  "Batal";
            }else {
              return "Aktif";
            }
    });

    $datatables->add('action', function ($data) {
          $ibonm = trim($data['i_bonm']);
          $f_bonm_cancel = trim($data['f_bonm_cancel']);
          $i_menu = $data['i_menu'];
          $data = '';
          if(check_role($i_menu, 3)){
              $data .= "<a href=\"#\" onclick='show(\"bonmasukreturpinjaman/cform/edit/$ibonm/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
          }if ($f_bonm_cancel!='t') {
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ibonm\"); return false;'><i class='fa fa-trash'></i></a>";
          }
      return $data;
    });

    $datatables->hide('i_menu');
    return $datatables->generate();
  }

  function cancelheader($ibonm){
        $this->db->set(
            array(
                'f_bonm_cancel' => TRUE,
            )
        );
        $this->db->where('i_bonm',$ibonm);
        $this->db->update('tm_bonmasuk_returpinjaman');
  }

  function cancelsemuadetail($ibonm){
        $this->db->set(
            array(
                    'f_item_cancel' =>TRUE,
                )
            );
        $this->db->where('i_bonm',$ibonm);
        $this->db->update('tm_bonmasuk_returpinjaman_detail');
  }

  function cek_datasj($from, $to){
        $this->db->select("a.i_sj, a.d_sj, a.i_penerima, a.i_jenis, b.e_jenis_keluar, a.f_cancel, c.i_product, c.e_product_name, c.i_color, d.e_color_name, c.n_quantity
                        from tm_sjkeluar_gdjadi a
                        join tm_sjkeluar_gdjadi_item c on a.i_sj=c.i_sj
                        join tr_jenis_keluargdjadi b on a.i_jenis=b.i_jenis 
                        join tr_color d on c.i_color=d.i_color
                        where a.d_sj >= to_date('$from', 'dd-mm-yyyy')
                        AND a.d_sj <= to_date('$to', 'dd-mm-yyyy')",false);
        return $this->db->get();
  }

  function runningnumber($thbl){
        $th = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
            $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='BON'
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
                                  where i_modul='BON'
                                  and e_periode='$asal' 
                                  and i_area='00'
                                  and substring(e_periode,1,4)='$th'", false);
                settype($nobonmk,"string");
                $a=strlen($nobonmk);
                while($a<6){
                  $nobonmk="0".$nobonmk;
                  $a=strlen($nobonmk);
                }
                    $nobonmk  ="BON-".$thbl."-".$nobonmk;
                return $nobonmk;
            }else{
                $nobonmk  ="000001";
                $nobonmk  ="BON-".$thbl."-".$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('BON','00','$asal',1)");
                return $nobonmk;
          }
  }

  function insertheader($ibonm, $isj, $dbonm, $eremark, $ipenerima){ 
      $dentry = date("Y-m-d H:i:s");    
        $data = array(
                  'i_bonm'          => $ibonm,
                  'i_sj'            => $isj,
                  'd_bonm'          => $dbonm,
                  'e_remark'        => $eremark,
                  'i_penerima'      => $ipenerima,
                  'd_entry'         => $dentry
        );
        $this->db->insert('tm_bonmasuk_returpinjaman', $data);
  } 

  function insertdetail($ibonm, $isj, $iproduct, $icolor, $eproductname, $nquantity, $eremark, $inoitem){      
          $data = array(
                  'i_bonm'          => $ibonm,
                  'i_sj'            => $isj,
                  'i_product'       => $iproduct,
                  'e_product_name'  => $eproductname,
                  'i_color'         => $icolor,
                  'n_quantity'      => $nquantity,
                  'e_remark'        => $eremark,
                  'n_item_no'       => $inoitem
          );
          $this->db->insert('tm_bonmasuk_returpinjaman_detail', $data);
  }

  public function cek_dataheader($ibonm){
      $this->db->select("a.*
                        from tm_bonmasuk_returpinjaman a                        
                        where a.i_bonm = '$ibonm'",false);
      return $this->db->get();
  }

  public function cek_datadetail($ibonm){
      $this->db->select("a.*, c.*, d.e_color_name
                        from tm_bonmasuk_returpinjaman a 
                        join tm_bonmasuk_returpinjaman_detail c on a.i_bonm = c.i_bonm
                        join tr_color d on c.i_color = d. i_color
                        where a.i_bonm = '$ibonm'",false);
      return $this->db->get();
  }

  public function update($ibonm, $isj, $dbonm, $eremark){
        $dupdate = date("Y-m-d H:i:s");

        $data = array(
                'i_bonm'          => $ibonm,
                'i_sj'            => $isj,
                'd_bonm'          => $dbonm,
                'e_remark'        => $eremark,
                //'i_penerima'      => $ipenerima,
                'd_update'         => $dupdate      
    );
    $this->db->where('i_bonm', $ibonm);
    $this->db->update('tm_bonmasuk_returpinjaman', $data);
    }
}
/* End of file Mmaster.php */