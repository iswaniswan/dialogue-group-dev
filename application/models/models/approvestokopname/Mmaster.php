<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
function data($i_menu, $from, $to, $ibank){
    $datatables = new Datatables(new CodeigniterAdapter);
    
      $datatables->query("select a.i_kbank, d.i_pv, a.d_bank, b.e_area_name, a.v_bank, a.v_sisa, c. i_bank, d.e_remark, '$i_menu' as i_menu
      FROM tm_kbank a, tr_area b, tr_bank c, tm_pv_item d 
      WHERE a.i_area = b.i_area AND a.i_kbank LIKE '%BK-%' AND a.d_bank >= to_date('$from','dd-mm-yyyy') AND a.d_bank <= to_date('$to','dd-mm-yyyy') 
      AND a.i_coa LIKE '%210-1%' AND a.i_coa_bank = c.i_coa AND c.i_bank = '$ibank' AND a.f_kbank_cancel = 'f' AND a.v_sisa > 0 AND d.i_kk = a.i_kbank ORDER BY a.i_kbank ");

        $datatables->add('action', function ($data) {
            $ikbank = trim($data['i_kbank']);
            $i_menu = $data['i_menu'];
            $data = '';
      return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_bank');
        return $datatables->generate();
  }

  public function bacagudang($ikodemaster){
        $this->db->select('e_nama_master');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master',$ikodemaster);
        return $this->db->get();
  }

  public function cek_data($iperiodebl, $iperiodeth, $ikodemaster){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_bahan_baku_detail a
                      join tt_stok_opname_bahan_baku b on a.i_stok_opname_bahanbaku = b.i_stok_opname_bahanbaku
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where a.f_status_approve = 'f' and b.f_status_approve = 'f' 
                      and b.d_bulan ='$iperiodebl' and b.d_tahun='$iperiodeth'
                      and b.i_kode_master = '$ikodemaster'", false);
    return $this->db->get();
  }

  public function cek_datadetail($iperiodebl, $iperiodeth, $ikodemaster){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_bahan_baku_detail a
                      join tt_stok_opname_bahan_baku b on a.i_stok_opname_bahanbaku = b.i_stok_opname_bahanbaku
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where a.f_status_approve = 'f' and b.f_status_approve = 'f' 
                      and b.d_bulan ='$iperiodebl' and b.d_tahun='$iperiodeth'
                      and b.i_kode_master = '$ikodemaster'", false);
    return $this->db->get();
  }

  public function updateheader($ikodeso, $ikodemaster, $bl, $th){

    $data=array(
        'f_status_approve' => 't',
    );
    $this->db->where('i_stok_opname_bahanbaku', $ikodeso);
    $this->db->where('i_kode_master', $ikodemaster);
    $this->db->where('d_bulan', $bl);
    $this->db->where('d_tahun', $th);
    $this->db->update('tt_stok_opname_bahan_baku', $data);
  } 

  public function updatedetail($ikodeso, $imaterial){

      $data = array(
            'f_status_approve'                 => 't', 
      );
     $this->db->where('i_stok_opname_bahanbaku', $ikodeso);
     $this->db->where('i_kode_brg', $imaterial);
     $this->db->update('tt_stok_opname_bahan_baku_detail', $data);
  }
}
/* End of file Mmaster.php */
