<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_retur, a.d_retur, a.i_tujuan, b.e_supplier_name, a.e_remark, a.f_status, '$i_menu' as i_menu 
                            from tm_retur_makloon_unitjahit a
                            join tr_supplier b on a.i_tujuan = b.i_supplier",false);

        $datatables->edit('f_status', function ($data) {
              $f_status = trim($data['f_status']);
              if($f_status == 'f'){
                 return  '<font color="red">Batal</font>';
              }else {
                return "Aktif";
              }
        });

        $datatables->add('action', function ($data) {
        $iretur    = trim($data['i_retur']);
        $f_status  = trim($data['f_status']);
        $i_menu    = $data['i_menu'];
        $data      = '';

        if(check_role($i_menu, 2)){
               $data .= "<a href=\"#\" onclick='show(\"returbarangunitjahit/cform/detail/$iretur/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
        if(check_role($i_menu, 3) && $f_status == 't'){            
            $data .= "<a href=\"#\" onclick='show(\"returbarangunitjahit/cform/edit/$iretur/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
        }
        if ($f_status!='f') {
            $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iretur\"); return false;'><i class='fa fa-trash'></i></a>";
        }
		return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_tujuan');

        return $datatables->generate();
	}

    public function getdataitem($referensi){
        $referensi        = $this->input->post('referensi');

        $this->db->select("a.i_sj, a.i_wip, b.e_namabrg, a.i_color, d.e_color_name, a.i_product, sum(a.n_quantity) as n_quantity
                         from tm_sj_masuk_makloonunitjahit_item a
                         join tm_barang_wip b on a.i_wip = b.i_kodebrg
                         join tr_color d on a.i_color = d.i_color
                         where a.i_sj = '$referensi'
                         group by a.i_sj, a.i_wip, b.e_namabrg, a.i_color, d.e_color_name, a.i_product
                            ",false);
        $data = $this->db->get();
        return $data;
  }

    function runningnumber($thbl, $ibagian){
      $th   = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='RUJM'
                          and i_area='08'
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
                                where i_modul='RUJM'
                                and e_periode='$asal' 
                                and i_area='08'
                                and substring(e_periode,1,4)='$th'", false);
              settype($nobonmk,"string");
              $a=strlen($nobonmk);
              while($a<6){
                $nobonmk="0".$nobonmk;
                $a=strlen($nobonmk);
              }
                $nobonmk  ="RUJM-".$thbl."-".$nobonmk;
              return $nobonmk;
          }else{
            $nobonmk  ="000001";
            $nobonmk  ="RUJM-".$thbl."-".$nobonmk;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('RUJM','08','$asal',1)");
              return $nobonmk;
          }
    }

    function insertheader($iretur, $dateretur, $ibagian, $itujuan, $ireferensi, $eremark){
        $dentry = date("Y-m-d");
        
        $data = array(
                'i_retur'      => $iretur,
                'd_retur'      => $dateretur,
                'i_bagian'     => $ibagian,
                'i_tujuan'     => $itujuan,
                'i_referensi'  => $ireferensi,
                'e_remark'     => $eremark,
                'd_entry'      => $dentry,
        );
        $this->db->insert('tm_retur_makloon_unitjahit', $data);
    }

    function insertdetail($iretur, $iproduct, $icolor, $brgjadi, $nquantity, $edesc, $inoitem){         
        $data = array(
                    'i_retur'            => $iretur,
                    'i_product'          => $iproduct,
                    'i_color'            => $icolor,
                    'i_product_jadi'     => $brgjadi,
                    'n_quantity_retur'   => $nquantity,
                    'e_remark'           => $edesc,
                    'n_quantity_sisa'    => $nquantity,
                    'i_no_item'          => $inoitem,
        );
        $this->db->insert('tm_retur_makloon_unitjahit_detail', $data);
    }

    function baca_bagian(){
        $this->db->select("* from tm_sub_bagian", false);
        return $this->db->get();
    }

    function baca_tujuan(){
        $this->db->select("* from tr_supplier",false);
        return $this->db->get();
    }

    function cek_data($iretur){
        $this->db->select("a.i_retur, to_char(a.d_retur, 'dd-mm-yyyy') as d_retur, a.i_bagian, a.i_tujuan, a.e_remark, a.i_referensi
                            from tm_retur_makloon_unitjahit a
                            where i_retur = '$iretur'",false);
        return $this->db->get();
    }

    function cek_datadetail($iretur){
        $this->db->select("a.*, b.e_namabrg, d.e_color_name from tm_retur_makloon_unitjahit_detail a
                        join tm_barang_wip b on a.i_product = b.i_kodebrg
                        join tr_color d on d.i_color = a.i_color
                        where a.i_retur = '$iretur'", false);
        return $this->db->get();
    }
    
    function updateheader($iretur, $dateretur, $ibagian, $itujuan, $ireff, $eremark){
       $dupdate = date("Y-m-d");
        
        $data = array(
                'd_retur'      => $dateretur,
                'i_bagian'     => $ibagian,
                'i_tujuan'     => $itujuan,
                'i_referensi'  => $ireff,
                'e_remark'     => $eremark,
                'd_update'     => $dupdate,
        );
        $this->db->where('i_retur',$iretur);
        $this->db->update('tm_retur_makloon_unitjahit', $data);
    }

    function deletedetail($iretur){
		  $this->db->query("DELETE FROM tm_retur_makloon_unitjahit_detail  WHERE i_retur='$iretur'");
    }

    public function cancel($iretur){
        $data = array(
          'f_status'=>'f',
      );
        $this->db->where('i_retur', $iretur);
        $this->db->update('tm_retur_makloon_unitjahit', $data);
  }
}
/* End of file Mmaster.php */