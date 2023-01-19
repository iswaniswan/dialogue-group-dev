<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  public function cek_gudang($ikodemaster){
        $this->db->select('e_nama_master');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master',$ikodemaster);
        return $this->db->get();
  }

  public function getbarang($ikodemaster){
        $this->db->select("* from tr_material ma
                          join tm_kelompok_barang b on ma.i_kode_kelompok=b.i_kode_kelompok
                          join tr_master_gudang c on b.i_kode_master=c.i_kode_master
                          where b.i_kode_master='$ikodemaster'
                          order by ma.i_material", false);       
        return $this->db->get();
  }

  public function cek_barang($ikodebarang){
        $this->db->select("* from tr_material 
                          where i_material='$ikodebarang'");
        return $this->db->get();
  }

  function cek_datadetail($dfrom, $dto, $ikodebarang, $ikodemaster){
        $pisah1 = explode("-", $dfrom);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];
        
    $this->db->select("kodebarang, nodok, to_char(tgldok, 'dd-mm-yyyy') AS tgldok, saldoawal, masuk, keluar, saldo from f_kartustok_plastik('$bln1', '$thn1', '$dfrom', '$dto') a
      join tr_material ma on a.kodebarang=ma.i_material  
                      join tm_kelompok_barang ka on ma.i_kode_kelompok=ka.i_kode_kelompok
                      where a.kodebarang='$ikodebarang'
                      and ka.i_kode_master='$ikodemaster'" ,FALSE);
    return $this->db->get();
  }
}
/* End of file Mmaster.php */