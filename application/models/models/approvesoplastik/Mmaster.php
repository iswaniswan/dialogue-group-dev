<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacagudang($ikodemaster){
        $this->db->select('e_nama_master');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master',$ikodemaster);
        return $this->db->get();
  }

  public function cek_data($dfrom, $dto, $ikodemaster){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_plastik_detail a
                      join tt_stok_opname_plastik b on a.i_stok_opname_plastik = b.i_stok_opname_plastik
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where a.f_status_approve = 'f' and b.f_status_approve = 'f' 
                      and b.d_so >= to_date('$dfrom','dd-mm-yyyy') and b.d_so <= to_date('$dto','dd-mm-yyyy')
                      and b.i_kode_master = '$ikodemaster'", false);
    return $this->db->get();
  }

  public function cek_datadetail($dfrom, $dto, $ikodemaster){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_plastik_detail a
                      join tt_stok_opname_plastik b on a.i_stok_opname_plastik = b.i_stok_opname_plastik
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where a.f_status_approve = 'f' and b.f_status_approve = 'f' 
                      and b.d_so >= to_date('$dfrom','dd-mm-yyyy') and b.d_so <= to_date('$dto','dd-mm-yyyy')
                      and b.i_kode_master = '$ikodemaster'", false);
    return $this->db->get();
  }

  public function updateheader($ikodeso, $ikodemaster, $bl, $th){

    $data=array(
        'f_status_approve' => 't',
    );
    $this->db->where('i_stok_opname_plastik', $ikodeso);
    $this->db->where('i_kode_master', $ikodemaster);
    $this->db->where('d_bulan', $bl);
    $this->db->where('d_tahun', $th);
    $this->db->update('tt_stok_opname_plastik', $data);
  } 

  public function updatedetail($ikodeso, $imaterial){

      $data = array(
            'f_status_approve'                 => 't', 
      );
     $this->db->where('i_stok_opname_plastik', $ikodeso);
     $this->db->where('i_kode_brg', $imaterial);
     $this->db->update('tt_stok_opname_plastik_detail', $data);
  }
}
/* End of file Mmaster.php */
