<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        // $datatables->query("sselect a.i_bonk, d_bonk, b.e_unitjahit_name as tujuankirim, a.e_remark, a.f_cancel, a.i_tujuan, '$i_menu' as i_menu
        // from tm_bonkeluarbb a
        // inner join tr_unit_jahit b on(b.i_unit_jahit=a.i_tujuan_kirim)
        // where a.i_tujuan='UJ'
        // union all 
        // select a.i_bonk, d_bonk, b.e_nama_packing as tujuankirim, a.e_remark, a.f_cancel, a.i_tujuan, '$i_menu' as i_menu
        // from tm_bonkeluarbb a
        // inner join tr_unit_packing b on(b.i_unit_packing=a.i_tujuan_kirim)
        // where a.i_tujuan='UP'
        // union all
        // select a.i_bonk, d_bonk, b.i_spbb as tujuankirim, a.e_remark, a.f_cancel, a.i_tujuan, '$i_menu' as i_menu
        // from tm_bonkeluarbb a
        // inner join tm_spbb b on(b.i_schedule=a.i_tujuan_kirim)
        // where a.i_tujuan='CT'
        // union all
        // select i_bonk, d_bonk, 'tidak ada'as tujuankirim, e_remark, f_cancel, i_tujuan, '$i_menu' as i_menu
        // from tm_bonkeluarbb ");
        $datatables->query("select a.i_bonk, a.d_bonk, a.i_tujuan, b.e_tujuan, a.f_cancel, '$i_menu' as i_menu
                            from tm_bonkeluarbb a
                            left join tr_jenis_kirimbb b on a.i_tujuan=b.i_tujuan");

        $datatables->edit('f_cancel', function ($data) {
        $f_cancel = trim($data['f_cancel']);
          if($f_cancel == 't'){
             return  "Batal";
          }else {
            return "Aktif";
          }
        });
        
        $datatables->add('action', function ($data) {
            $i_bonk = trim($data['i_bonk']);
            $tujuankirim = trim($data['i_tujuan']);
            $i_menu = $data['i_menu'];
            $data = '';
            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"bonmkeluarbbp/cform/view/$i_bonk/$tujuankirim\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bonmkeluarbbp/cform/edit/$i_bonk/$tujuankirim\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }

			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_tujuan');
        //$datatables->hide('tujuankirim');
        return $datatables->generate();
	}

	function cek_data($i_bonk, $tujuankirim){
    //var_dump($tujuankirim);
    if($tujuankirim == "UP"){
      $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan, e.e_nama_packing as tujuan
          from duta_prod.tm_bonkeluarbb a 
          inner join duta_prod.tr_master_gudang b on (a.i_kode_master = b.i_kode_master) 
          inner join duta_prod.tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char) 
          inner join duta_prod.tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan) 
          inner join duta_prod.tr_unit_packing e on(e.i_unit_packing=a.i_tujuan_kirim)
          where i_bonk = '$i_bonk' and a.i_tujuan = '$tujuankirim'",false);
    }else if($tujuankirim == "UJ"){
      $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan, e.e_unitjahit_name as tujuan
          from duta_prod.tm_bonkeluarbb a 
          inner join duta_prod.tr_master_gudang b on (a.i_kode_master = b.i_kode_master) 
          inner join duta_prod.tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char) 
          inner join duta_prod.tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan) 
          inner join duta_prod.tr_unit_jahit e on(e.i_unit_jahit=a.i_tujuan_kirim)
          where i_bonk = '$i_bonk' and a.i_tujuan = '$tujuankirim'",false);
    }else if($tujuankirim == "CT"){
      $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan, e.i_schedule as tujuan
          from duta_prod.tm_bonkeluarbb a 
          inner join duta_prod.tr_master_gudang b on (a.i_kode_master = b.i_kode_master) 
          inner join duta_prod.tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char) 
          inner join duta_prod.tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan) 
          inner join duta_prod.tm_spbb e on(e.i_spbb=a.i_tujuan_kirim)
          where i_bonk = '$i_bonk' and a.i_tujuan = '$tujuankirim'",false);
    }else{
          $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan 
          from tm_bonkeluarbb a
          inner join tr_master_gudang b on (a.i_kode_master = b.i_kode_master)
          inner join tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char)
          inner join tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan)
          where i_bonk = '$i_bonk' ",false);
    }
        return $this->db->get();
  }

  function cek_datadet($i_bonk){
		$this->db->select('a.*, b.e_satuan, c.e_material_name');
        $this->db->from('tm_bonkeluarbb_detail a');
        $this->db->join('tr_satuan b','a.i_satuan = b.i_satuan');
        $this->db->join('tr_material c','a.i_material = c.i_material');
        $this->db->where('a.i_bonk', $i_bonk);
        return $this->db->get();
	}

  function cek_dataheader($nobonk){
    $this->db->select('*');
        $this->db->from('tm_bonkeluarbb');
        // $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
        $this->db->where('i_bonk', $nobonk);
        return $this->db->get();
  }

  function cekdatadetail($nobonk, $imaterial){
    $this->db->select('*');
        $this->db->from('tm_bonkeluarbb_detail');
        $this->db->where('i_bonk', $nobonk);
        $this->db->where('i_material', $imaterial);
        return $this->db->get();
  }

  public function bacagudang(){
       $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', 'GD10001');
        return $this->db->get();
  }
  
  public function bacajenis(){
      return $this->db->order_by('e_jenis_keluar','ASC')->get('tr_jenis_keluarbb')->result();
  }
  
  public function bacatujuan(){
      return $this->db->order_by('e_tujuan','ASC')->get('tr_jenis_kirimbb')->result();
  }

  public function gettujuanUJ(){
      $this->db->select("i_unit_jahit as itujuank, e_unitjahit_name as etujuank");
      $this->db->from('tr_unit_jahit');
      $this->db->order_by('i_unit_jahit');
      return $this->db->get();
  }

  public function gettujuanUP(){
      $this->db->select("i_unit_packing as itujuank, e_nama_packing as etujuank");
      $this->db->from('tr_unit_packing');
      $this->db->order_by('i_unit_packing');
      return $this->db->get();
  }
  public function gettujuanCT(){
      $this->db->select("i_spbb as itujuank, i_schedule as etujuank");
      $this->db->from('tm_spbb');
      $this->db->order_by('i_spbb');
      return $this->db->get();
  }

  function runningnumberbonk($th,$bl,$ikodemaster){
#      $store=$this->session->userdata('store');
      $this->db->select(" max(substr(i_bonk,12,4)) as max from tm_bonkeluarbb where substr(i_bonk,7,2)='$th' and substr(i_bonk,9,2)='$bl' and i_kode_master='$ikodemaster'", false);
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
        $nogj  ="BONMK-".$th.$bl."-".$nogj;
        return $nogj;
      }else{
        $nogj  ="0001";
        $nogj  ="BONMK-".$th.$bl."-".$nogj;
        return $nogj;
      }
  }

	public function insertheader($dbonk, $ikodemaster, $itujuan, $jnskeluar, $remark, $nobonk, $bonkcancel, $itujuankirim){
        $dentry = date("d F Y");
        $data = array(
            'i_bonk'        => $nobonk,
            'd_bonk'        => $dbonk,
            'i_jenis_keluar'=> $jnskeluar,
            'i_tujuan'      => $itujuan,
            'i_tujuan_kirim'=> $itujuankirim,
            'e_remark'      => $remark,
            'i_kode_master' => $ikodemaster,
            'd_insert'      => $dentry,
            
    );
    $this->db->insert('tm_bonkeluarbb', $data);
  }

  public function insertdetail($nobonk, $imaterial, $nquantity, $nquantitykonv, $isatuan, $esatuankonv, $edesc, $fbisbisan, $ikodemaster, $no){   
      $dentry = date("d F Y");            
      $data = array(        

          'i_bonk'          => $nobonk,
          'i_material'      => $imaterial,
          'n_qty'           => $nquantity,
          'n_qty_unit_first'=> $nquantitykonv,
          'f_convertion'    => 'f',
          'i_satuan'        => $isatuan,
          'i_convertion'    => $esatuankonv,
          'i_formula'       => '0',
          'n_formula_factor'=> '0',
          'i_no_item'       => $no,
          'i_kode_master'   => $ikodemaster,
          'f_bisbisan'      => $fbisbisan,
          'e_remark'        => $edesc,
          
  );
  $this->db->insert('tm_bonkeluarbb_detail', $data);
  }
    
  public function updateheader($nobonk, $dbonk, $remark){
        $dupdate = date("d F Y");   
        $data = array(
            'd_bonk'        => $dbonk,
            'e_remark'      => $remark,
            'd_update'      => $dupdate,
    );

    $this->db->where('i_bonk', $nobonk);
    $this->db->update('tm_bonkeluarbb', $data);
  }

  public function updatedetail($nquantity,$nquantitykonv,$nobonk, $imaterial){
      $data = array(
          'n_qty' => $nquantity,
          'n_qty_unit_first' => $nquantity,
      );

      $this->db->where('i_bonk', $nobonk);
      $this->db->where('i_material', $imaterial);
      $this->db->update('tm_bonkeluarbb_detail', $data);
  }

  public function deletedetail($nobonk){
      $this->db->query("DELETE FROM tm_bonkeluarbb_detail WHERE i_bonk='$nobonk'");
  }
}
/* End of file Mmaster.php */