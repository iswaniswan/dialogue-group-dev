<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select a.i_bonm, a.d_bonm, c.e_nama_jenis, b.e_nama_master, a.f_bonm_cancel, a.e_desc, '$i_menu' as i_menu 
        from duta_prod.tm_bonmasuk_lain a, tr_master_gudang b, tr_jenis_msk_lain c, tm_bonmasuk_lain_detail d
        where a.i_bonm=d.i_bonm and a.i_kode_master=b.i_kode_master and a.i_trans_type=c.i_kode_jenis 
        group by a.i_bonm, a.d_bonm, b.e_nama_master, c.e_nama_jenis
        order by a.d_bonm, a.i_bonm desc");

        $datatables->edit('f_bonm_cancel', function ($data) {
          $f_bonm_cancel = trim($data['f_bonm_cancel']);
          if($f_bonm_cancel == 't'){
             return  "Batal";
          }else {
            return "Aktif";
          }
      });
    //   $datatables->edit('e_approve', function ($data) {
    //     $e_approve = trim($data['e_approve']);
    //     if($e_approve == 't'){
    //        return  "Approve";
    //     }else {
    //       return "Belum Approve";
    //     }
    // });
        
        $datatables->add('action', function ($data) {
            $i_bonm = trim($data['i_bonm']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"bonmlain2/cform/view/$i_bonm/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bonmlain2/cform/edit/$i_bonm/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
          //   if(check_role($i_menu, 1)){
          //     $data .= "<a href=\"#\" onclick='show(\"pembelianpp/cform/approve/$i_pp/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
          // }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($i_bonm){
		$this->db->select('a.*, b.e_nama_master, c.e_nama_jenis');
        $this->db->from('tm_bonmasuk_lain a');
        $this->db->join('tr_master_gudang b','a.i_kode_master = b.i_kode_master');
        $this->db->join('tr_jenis_msk_lain c','a.i_trans_type = c.i_kode_jenis');
        $this->db->where('a.i_bonm', $i_bonm);
        return $this->db->get();
  }
  function cek_datadet($i_bonm){
		$this->db->select('a.*, b.e_satuan');
        $this->db->from('tm_bonmasuk_lain_detail a');
        $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
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
        $this->db->from('tm_bonmasuk_lain');
        // $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
        $this->db->where('i_bonm', $nobonm);
        return $this->db->get();
  }
  function cekdatadetail($nobonm, $imaterial){
    $this->db->select('*');
        $this->db->from('tm_bonmasuk_lain_detail');
        $this->db->where('i_bonm', $nobonm);
        $this->db->where('i_material', $imaterial);
        return $this->db->get();
  }
    public function bacagudang(){
        return $this->db->order_by('e_nama_master','ASC')->get('tr_master_gudang')->result();
    }
    public function bacajenis(){
      return $this->db->order_by('e_nama_jenis','ASC')->get('tr_jenis_msk_lain')->result();
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
      $nogj  ="BONL-".$th.$bl."-".$nogj;
      return $nogj;
    }else{
      $nogj  ="0001";
      $nogj  ="BONL-".$th.$bl."-".$nogj;
      return $nogj;
    }
  }
	public function insertheader($dbonm, $ikodemaster, $ibonmanual, $ikodejenis, $remark, $nobonm, $bonmcancel, $now){
        $data = array(
            'i_bonm'        => $nobonm,
            'i_bonm_manual' => $ibonmanual,
            'd_bonm'        => $dbonm,
            'i_trans_type'  => $ikodejenis,
            'i_kode_master' => $ikodemaster,
            'f_bonm_cancel' => $bonmcancel,
            'e_desc'        => $remark,
            'd_entry'       => $now,
            
    );
    $this->db->insert('tm_bonmasuk_lain', $data);
    }
  public function insertdetail($nobonm, $imaterial, $ematerialname, $nquantity, $nquantitykonv, $isatuan, $esatuankonv, $edesc, $now, $i){
      $data = array(        
          'i_bonm'            => $nobonm,
          'i_material'        => $imaterial,
          'e_name_material'   => $ematerialname,
          'n_qty'             => $nquantity,
          'n_qty_unit_first'  => $nquantitykonv,
          'i_unit'            => $isatuan,
          'i_unit_conversion' => $esatuankonv,
          'e_desc'            => $edesc,
          'd_entry'           => $now,
          'i_no_item'         => $i
          
  );
  $this->db->insert('tm_bonmasuk_lain_detail', $data);
  }
    

    public function updateheader($nobonm, $dbonm, $ibonmanual, $remark, $now){
        $data = array(
            'i_bonm_manual' => $ibonmanual,
            'd_bonm'        => $dbonm,
            'e_desc'        => $remark,
            'd_update'       => $now,
    );

    $this->db->where('i_bonm', $nobonm);
    $this->db->update('tm_bonmasuk_lain', $data);
    }
    public function updatedetail($nquantity,$nquantitykonv,$nobonm, $imaterial){
      $data = array(
          'n_qty' => $nquantity,
          'n_qty_unit_first' => $nquantity,
      );

      $this->db->where('i_bonm', $nobonm);
      $this->db->where('i_material', $imaterial);
      $this->db->update('tm_bonmasuk_lain_detail', $data);
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
