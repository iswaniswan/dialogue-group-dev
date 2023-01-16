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

  public function getkategori($ikodemaster){
        $this->db->select('*');
        $this->db->from('tm_kelompok_barang');
        $this->db->where('i_kode_master',$ikodemaster);
        $this->db->where('i_kode_group_barang','GRB0001');
        $this->db->where('i_kode_kelompok','KTB0002');
        return $this->db->get();
  }

  public function getjenis($ikelompok){
        $this->db->select('*');
        $this->db->from('tr_item_type');
       // $this->db->where('i_kode_master',$ikodemaster);
        $this->db->where('i_kode_group_barang','GRB0001');
        $this->db->where('i_kode_kelompok',$ikelompok);
        return $this->db->get();
  }

  public function kategoribarang($ikelompok){
        $this->db->select('e_nama');
        $this->db->from('tm_kelompok_barang');
        $this->db->where('i_kode_kelompok',$ikelompok);
        return $this->db->get();
  }

  public function jenisbarang($jnsbarang){
    $where = '';
        if($jnsbarang != 'JNB'){
          $where .= " where a.i_type_code = '$jnsbarang'";
        }
        $this->db->select("a.e_type_name from tr_item_type a $where", false);
        
        return $this->db->get();
  }

  function cek_datadet($dfrom, $dto, $ikelompok, $jnsbarang, $ikodemaster){
        $pisah1 = explode("-", $dfrom);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];
        $iperiode = $thn1.$bln1;
        if($bln1 == 1) {
          $bln_query = 12;
          $thn_query = $thn1-1;
        }else {
          $bln_query = $bln1-1;
          $thn_query = $thn1;
          if ($bln_query < 10){
            $bln_query = "0".$bln_query;
          }
        }
        $pisah1 = explode("-", $dto);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];

      $dtoback3 = date('d-m-Y',strtotime('-1 month', strtotime($dfrom)));
      $dtoback1 = date('d-m-Y',strtotime('-2 month', strtotime($dfrom)));
        $bln2= substr($dtoback1, 3,2);
        $thn2= substr($dtoback1, 6,4);//t
      $dto1 = date('d-m-Y',strtotime($dto."last day of previous month"));
        $bln4= substr($dto1, 3,2);
        $thn4= substr($dto1, 6,4);
    
    $where = '';
        if($jnsbarang != 'JNB'){
          $where .= "AND b.i_type_code = '$jnsbarang'";
        }
    //if ($iperiode<='202001') {
    $this->db->select("
      kode, barang, kodegudang, gudang, satuan, saldoawal, bonmasuk1, bonmasukmakloon, bonmasuklain, bonkeluar, bonkeluarlain, returpembelian, saldoakhir, so, selisih
      FROM f_saldoso('$bln_query', $thn_query, '$dfrom','$dto', '$bln1', $thn1) a
      JOIN tr_material b on a.kode=b.i_material
      WHERE 
      b.i_kode_kelompok = '$ikelompok'
      and kodegudang ='$ikodemaster' $where
      order by kodegudang" ,FALSE);
      // }else{ 
      // $this->db->select("
      //  id_brg,  kode, barang, gudang, satuan, saldoawal, bonmasuk1, bonmasuklain, bonkeluar, bonkeluarlain, saldoakhir, so, selisih
      //  FROM f_saldo('$bln1', $thn1, '$dfrom', '$dto', '$bln1',$thn1,'$bln2',$thn2, '$dtoback3', '$dto1', '$bln4',$thn4)
      // WHERE id_gudang='$gudang' order by barang
      // ",FALSE);
      // }
    return $this->db->get();
  }
}
/* End of file Mmaster.php */