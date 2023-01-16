<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("select a.i_bonk, a.d_bonk, a.i_tujuan_kirim, b.e_nama_master, a.e_remark, a.f_cancel, $i_menu as i_menu 
                        from duta_prod.tm_bonkeluar_bp_aksesories a
                        join tr_master_gudang b on a.i_tujuan_kirim = b.i_kode_master");

    $datatables->edit('f_cancel', function ($data) {
      $f_cancel = trim($data['f_cancel']);
      if($f_cancel == 't'){
         return  "Batal";
      }else {
        return "Aktif";
      }
    });
        
    $datatables->add('action', function ($data) {
        $ibonk = trim($data['i_bonk']);
        $i_menu = $data['i_menu'];
        $data = '';
        if(check_role($i_menu, 3)){
            $data .= "<a href=\"#\" onclick='show(\"bonkeluarbpaksesories/cform/edit/$ibonk/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
        }
	  return $data;
    });
    $datatables->hide('i_menu');
    $datatables->hide('i_tujuan_kirim');

    return $datatables->generate();
	}

  public function bacagudang(){
      $this->db->select("* from duta_prod.tr_master_gudang where i_kode_master in('G01', 'G05', 'G06', 'G07', 'G08', 'G09', 'G010', 'G12', 'G13')",false);
      return $this->db->get();
  }

  function runningnumber($thbl){
      $th = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='BON'
                          and i_area='G02'
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
                                and i_area='G02'
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
                           values ('BON','G02','$asal',1)");
              return $nobonmk;
          }
  }

  public function insertheader($datebonk, $itujuankirim, $eremark, $ibonk){
      $dentry = date("Y-m-d H:i:s");
      $data = array(
            'i_bonk'        => $ibonk,
            'd_bonk'        => $datebonk,            
            'i_tujuan_kirim'=> $itujuankirim,
            'e_remark'      => $eremark,
            'i_kode_master' => 'G02',
            'd_entry'       => $dentry,   
    );
    $this->db->insert('tm_bonkeluar_bp_aksesories', $data);
  }

  public function insertdetail($ibonk, $imaterial, $nquantity, $isatuan, $inoitem)
  {       
      $dentry = date("Y-m-d H:i:s");        
      $data = array(       
          'i_bonk'          => $ibonk,
          'i_material'      => $imaterial,
          'n_qty'           => $nquantity,
          'i_satuan'        => $isatuan,
          'i_kode_master'   => 'G02',
          'd_entry'         => $dentry,
          'i_no_item'       => $inoitem,  
  );
  $this->db->insert('tm_bonkeluar_bp_aksesories_detail', $data);
  }

  function cek_data($ibonk){
      $this->db->select('a.*, b.*');
      $this->db->from('tm_bonkeluar_bp_aksesories a');
      $this->db->join('tr_master_gudang b', 'a.i_tujuan_kirim = b.i_kode_master');
      $this->db->where('i_bonk', $ibonk);
  return $this->db->get();
  }

  function cekdatadetail($ibonk){
      $this->db->select('a.*, b.*, c.*');
      $this->db->from('tm_bonkeluar_bp_aksesories_detail a');
      $this->db->join('tr_satuan b', 'a.i_satuan = b.i_satuan');
      $this->db->join('tr_material c', 'a.i_material = c.i_material');
      $this->db->where('i_bonk', $ibonk);
  return $this->db->get();
  }

  public function updateheader($dbonk, $itujuankirim, $eremark, $ibonk){
    $dupdate = date("Y-m-d H:i:s");     
        $data = array(
            'i_bonk'          => $ibonk,
            'd_bonk'          => $dbonk,            
            'i_tujuan_kirim'  => $itujuankirim,
            'e_remark'        => $eremark,
            'i_kode_master'   => 'G02',
            'd_update'        => $dupdate,
    );

  $this->db->where('i_bonk', $ibonk);
  $this->db->update('tm_bonkeluar_bp_aksesories', $data);
  }
    
  function deletedetail($ibonk, $imaterial){
        $this->db->query("DELETE FROM tm_bonkeluar_bp_aksesories_detail WHERE i_bonk='$ibonk' and i_material='$imaterial'");
  }
}

/* End of file Mmaster.php */
