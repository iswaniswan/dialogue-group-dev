<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select a.i_bonm, a.d_bonm, c.e_jenis_masuk, b.e_sumber, a.f_bonm_cancel, a.e_desc, '$i_menu' as i_menu
        from duta_prod.tm_bonmasuk_cutting a
        inner join tr_sumber b on (a.i_sumber = b.i_sumber)
        inner join tr_jenis_masuk c on (a.i_jenis_masuk = c.i_jenis_masuk)");

        $datatables->edit('f_bonm_cancel', function ($data) {
          $f_bonm_cancel = trim($data['f_bonm_cancel']);
          if($f_bonm_cancel == 't'){
             return  "Batal";
          }else {
            return "Aktif";
          }
      });
        $datatables->add('action', function ($data) {
            $i_bonm = trim($data['i_bonm']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"bonmhasilcutting/cform/view/$i_bonm/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bonmhasilcutting/cform/edit/$i_bonm/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($i_bonm){
		$this->db->select('a.*, b.e_sumber ,c.e_jenis_masuk');
        $this->db->from('tm_bonmasuk_cutting a');
        $this->db->join('tr_sumber b','a.i_sumber = b.i_sumber');
        $this->db->join('tr_jenis_masuk c','a.i_jenis_masuk = c.i_jenis_masuk');
        $this->db->where('a.i_bonm', $i_bonm);
        return $this->db->get();
  }
  function cek_datadet($i_bonm){
		$this->db->select('a.*, b.e_color_name');
        $this->db->from('tm_bonmasuk_cutting_detail a');
        $this->db->join('tr_color b','a.i_color = b.i_kode_color');
        $this->db->where('a.i_bonm', $i_bonm);
        return $this->db->get();
	}
  // function cek_datadet($i_bonm){
	// 	$this->db->select('a.*, b.e_satuan');
  //       $this->db->from('tm_bonmasuk_lain_detail a');
  //       $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
  //       $this->db->where('a.i_bonm', $i_bonm);
  //       return $this->db->get();
  // }
  function cek_dataheader($nobonm){
    $this->db->select('*');
        $this->db->from('tm_bonmasuk_cutting');
        // $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
        $this->db->where('i_bonm', $nobonm);
        return $this->db->get();
  }
  function cekdatadetail($nobonm, $kodebrg){
    $this->db->select('*');
        $this->db->from('tm_bonmasuk_cutting_detail');
        $this->db->where('i_bonm', $nobonm);
        $this->db->where('kode_brg', $kodebrg);
        return $this->db->get();
  }
    public function bacasumber(){
        return $this->db->order_by('e_sumber','ASC')->get('tr_sumber')->result();
    }
    public function bacajenis(){
      return $this->db->order_by('e_jenis_masuk','ASC')->get('tr_jenis_masuk')->result();
  }
  function runningnumberbonm($th,$bl)
  {
    $this->db->select(" max(substr(i_bonm,11,4)) as max from tm_bonmasuk_lain where substr(i_bonm,6,2)='$th' and substr(i_bonm,8,2)='$bl'", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $nogj  =$terakhir+1;
      settype($nogj,"string");
      $a=strlen($nogj);
      while($a<4){
        $nogj="0".$nogj;
        $a=strlen($nogj);
      }
      $nogj  ="BONMC-".$th.$bl."-".$nogj;
      return $nogj;
    }else{
      $nogj  ="0001";
      $nogj  ="BONMC-".$th.$bl."-".$nogj;
      return $nogj;
    }
  }
	public function insertheader($dbonm, $isumber, $ibonmanual, $ijenis, $remark, $nobonm, $bonmcancel, $now){
        $data = array(
            'i_bonm'          => $nobonm,
            'i_bonm_manual'   => $ibonmanual,
            'd_bonm'          => $dbonm,
            'f_bonm_cancel'   => $bonmcancel,
            'e_desc'          => $remark,
            'd_entry'         => $now,
            'i_sumber'        => $isumber,
            'i_jenis_masuk'   => $ijenis,    
    );
    $this->db->insert('tm_bonmasuk_cutting', $data);
    }
  public function insertdetail($nobonm, $kodebrg, $enamabrg, $nquantity, $icolor, $edesc, $now, $i){
      $data = array(        
          'i_bonm'            => $nobonm,  
          'kode_brg'          => $kodebrg,
          'nama_brg'          => $enamabrg,
          'n_qty'             => $nquantity,
          'i_color'           => $icolor,
          'e_desc'            => $edesc,
          'd_entry'           => $now,
          'i_no_item'         => $i
          
  );
  $this->db->insert('tm_bonmasuk_cutting_detail', $data);
  }
    

    public function updateheader($nobonm, $dbonm, $ibonmanual, $remark, $now){
        $data = array(
            'i_bonm_manual' => $ibonmanual,
            'd_bonm'        => $dbonm,
            'e_desc'        => $remark,
            'd_update'       => $now,
    );

    $this->db->where('i_bonm', $nobonm);
    $this->db->update('tm_bonmasuk_cutting', $data);
    }
    public function updatedetail($nquantity,$nobonm, $kodebrg,$edesc){
      $data = array(
          'n_qty'   => $nquantity,
          'e_desc'  => $edesc
      );

      $this->db->where('i_bonm', $nobonm);
      $this->db->where('kode_brg', $kodebrg);
      $this->db->update('tm_bonmasuk_cutting_detail', $data);
    }
    public function approve($ipp, $now){
      $data = array(
        'e_approve' => 't',
        'd_approve' => $now,
    );
    $this->db->where('i_pp', $ipp);
      $this->db->update('tm_pp', $data);
    }
}

/* End of file Mmaster.php */
