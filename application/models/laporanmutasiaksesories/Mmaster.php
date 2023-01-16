<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  public function getbagian() {
    $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
    $this->db->from('tr_bagian a');
    $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
    $this->db->where('i_departement', $this->session->userdata('i_departement'));
    $this->db->where('i_level', $this->session->userdata('i_level'));
    $this->db->where('username', $this->session->userdata('username'));
    $this->db->where('b.id_company', $this->session->userdata('id_company'));
    $this->db->where('a.id_company', $this->session->userdata('id_company'));
    $this->db->where('a.i_type', '02');
    $this->db->order_by('e_bagian_name');
    return $this->db->get();
  }

  public function getkategori($ibagian){
    $id_company = $this->session->userdata('id_company');
    return $this->db->query("
                            SELECT 
                              a.i_kode_kelompok,
                              b.e_nama_kelompok
                            FROM 
                              tr_bagian_kelompokbarang a
                              INNER JOIN 
                                tr_kelompok_barang b ON (a.i_kode_kelompok = b.i_kode_kelompok)
                            WHERE
                              a.i_bagian = '$ibagian'
                              AND a.id_company = '$id_company'
                            ");
  }

  public function getjenis($ikelompok, $ibagian){
    $id_company = $this->session->userdata('id_company');
    $where = '';
    if ($ikelompok == "KTB") {
      $where = " AND a.i_kode_kelompok IN (Select i_kode_kelompok from tr_bagian_kelompokbarang where i_bagian = '$ibagian' and id_company = '$id_company')";
    } else {
      $where = " AND a.i_kode_kelompok = '$ikelompok' ";
    }

    $this->db->select("
      a.i_type_code, a.e_type_name from tr_item_type a where a.id_company = '$id_company' and a.i_kode_group_barang = 'GRB0005' $where

    ", false);
    return $this->db->get();
  }

  public function bacabagian($ibagian) {
    $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
    $this->db->from('tr_bagian a');
    $this->db->where('a.id_company', $this->session->userdata('id_company'));
    $this->db->where('a.i_bagian', $ibagian);
    $this->db->order_by('e_bagian_name');
    return $this->db->get();
  }

  public function kategoribarang($ikelompok, $id_company){

    if($ikelompok != 'KTB'){
       $this->db->select("e_nama_kelompok from tr_kelompok_barang where i_kode_kelompok = '$ikelompok' and id_company = '$id_company' ", false);
    } else {
       $this->db->select("'Semua Kategori Barang' as e_nama_kelompok", false);
    }
    return $this->db->get();
  }

  public function jenisbarang($jnsbarang, $id_company){
    if($jnsbarang != 'JNB'){
       $this->db->select("e_type_name from tr_item_type where i_type_code = '$jnsbarang' and id_company = '$id_company' ", false);
    } else {
       $this->db->select("'Semua Barang' as e_type_name", false);
    }
    return $this->db->get();
  }

  function cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang){   
    $where = '';
    if($jnsbarang != 'JNB'){
      $where .= "AND a.i_type_code = '$jnsbarang'";
    }

    $where2 = '';
    if($ikelompok != 'KTB') {
      $where2 .= "AND (a.i_kode_kelompok = '$ikelompok')";
    }

    $this->db->select("
      x.*, a.e_material_name, b.e_satuan_name from f_mutasi_saldoawal_aks('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
     inner join tr_material a on (a.id_company = x.id_company and a.i_material = x.i_material)
      inner join tr_satuan b on (a.id_company = b.id_company and a.i_satuan_code = b.i_satuan_code)
      where x.id_company is not null
      $where $where2
    ",FALSE);
    return $this->db->get();
  }
}
/* End of file Mmaster.php */