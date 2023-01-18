<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  function data($i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT a.i_sj, a.d_sj, a.i_tujuan, b.e_sub_bagian, a.e_remark, a.f_cancel, $i_menu as i_menu
            FROM tm_sjkeluar_pengadaan a
            JOIN tm_sub_bagian b on a.i_tujuan = b.i_sub_bagian");     

        $datatables->edit('f_cancel', function ($data) {
            $f_cancel = trim($data['f_cancel']);
            if($f_cancel == 'f'){
               return  "Aktif";
            }else {
              return "Tidak Aktif";
            }
        });   

        $datatables->add('action', function ($data) {
            $isj = trim($data['i_sj']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"sjkeluarpengadaan/cform/view/$isj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"sjkeluarpengadaan/cform/edit/$isj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_tujuan');

        return $datatables->generate();
    }

  function runningnumber($thbl, $ikodemaster){
      $th   = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SJ'
                          and i_area='07'
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
                                where i_modul='SJ'
                                and e_periode='$asal' 
                                and i_area='07'
                                and substring(e_periode,1,4)='$th'", false);
              settype($nobonmk,"string");
              $a=strlen($nobonmk);
              while($a<6){
                $nobonmk="0".$nobonmk;
                $a=strlen($nobonmk);
              }
                $nobonmk  ="SJ-".$thbl."-".$nobonmk;
              return $nobonmk;
          }else{
              $nobonmk  ="000001";
            $nobonmk  ="SJ-".$thbl."-".$nobonmk;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJ','07','$asal',1)");
              return $nobonmk;
          }
    }

  function insertheader($isj, $datesj, $ikodemaster, $itujuan, $eremark){   
        $dentry = date("Y-m-d");
        
        $data = array(
                'i_sj'                  => $isj,
                'd_sj'                  => $datesj,
                'i_kode_master'         => $ikodemaster,
                'i_tujuan'              => $itujuan,
                'e_remark'              => $eremark,
                'd_entry'               => $dentry     
        );
        $this->db->insert('tm_sjkeluar_pengadaan', $data);
   }

   function insertdetail($isj, $iproduct, $icolor, $imaterial, $nquantity, $edesc, $inoitem){           
        $this->db->set(
        $data = array(
                'i_sj'                  => $isj,
                'i_product'             => $iproduct,
                'i_material'            => $imaterial,
                'i_color'               => $icolor,          
                'n_quantity'            => $nquantity,
                'e_remark'              => $edesc,                
                'n_item_no'             => $inoitem,
            )
        );
        $this->db->insert('tm_sjkeluar_pengadaan_item', $data);
    }

    function baca_header($isj){
        $this->db->select("a.i_sj, to_char(a.d_sj, 'dd-mm-yyyy') as d_sj, a.i_kode_master, a.i_tujuan, a.e_remark, b.e_sub_bagian as e_nama_master, d.e_sub_bagian as e_tujuan
                          from tm_sjkeluar_pengadaan a
                          join tm_sub_bagian b on a.i_kode_master = b.i_sub_bagian
                          join tm_sub_bagian d on a.i_tujuan = d.i_sub_bagian
                          where a.i_sj = '$isj'");
        return $this->db->get();
    }

    function baca_detail($isj){
        $this->db->select(" a.*, b.e_namabrg, c.e_material_name, d.e_color_name 
                            from tm_sjkeluar_pengadaan_item a
                            join tm_barang_wip b on a.i_product = b.i_kodebrg
                            join tr_material c on a.i_material = c.i_material
                            join tr_color d on a.i_color=d.i_color
                            where a.i_sj = '$isj'", false);
        return $this->db->get();
    }

    function baca_gudang(){
        $this->db->select("* from tm_sub_bagian", false);
        return $this->db->get();
    }

    function baca_tujuan(){
        $this->db->select("* from tm_sub_bagian",false);
        return $this->db->get();
    }

    function updateheader($isj, $datesj, $ikodemaster, $itujuan, $eremark){
        $dupdate = date("Y-m-d");

        $data = array(
                    'd_sj'                  => $datesj,
                    'i_kode_master'         => $ikodemaster,
                    'i_tujuan'              => $itujuan,
                    'e_remark'              => $eremark,    
                    'd_update'              => $dupdate,      
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sjkeluar_pengadaan', $data);
    }

    function deletedetail($isj){
         $this->db->query("DELETE FROM tm_sjkeluar_pengadaan_item WHERE i_sj='$isj'");
    }
}
/* End of file Mmaster.php */