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

  public function getkategori($ikodemaster, $kelompok_barang){
        $this->db->select('*');
        $this->db->from('tm_kelompok_barang');
        $this->db->where('i_kode_master',$ikodemaster);
        $this->db->where('i_kode_kelompok', $kelompok_barang);
        return $this->db->get();
  }

  public function getjenis($ikelompok){
        $this->db->select('*');
        $this->db->from('tr_item_type');
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

  function cek_datadet($blalu, $tlalu, $dfrom, $dto, $bnow, $tnow, $ikodemaster, $dawal, $dakhir, $ikodemaster2, $ikelompok, $jnsbarang){
        $where = '';
        if($jnsbarang != 'JNB'){
          $where .= "AND b.i_type_code = '$jnsbarang'";
        }
        $this->db->select("
                            x.kode, b.e_material_name, x.i_satuan_code, c.e_satuan, x.saldoawal, x.sjmasuk, x.sjmasukmakloon, x.sjkeluar, x.sjkeluarmakloon, x.adjustment, x.retur, x.saldoakhir, x.git, x.so, x.selisih 
                            FROM
                               f_mutasi_bahanbaku('$blalu', '$tlalu', '$dfrom', '$dto', '$bnow', '$tnow', '$ikodemaster', '$dawal', '$dakhir', '$ikodemaster2') x 
                               LEFT JOIN
                                  tr_material b 
                                  on x.kode = b.i_material 
                                  LEFT JOIN
                                     tr_satuan c 
                                     on trim(x.i_satuan_code) = trim(c.i_satuan_code) 
                                     WHERE
                                        b.i_kode_kelompok = '$ikelompok' 
                                        $where
                                           order by
                                              x.kode" ,FALSE);
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