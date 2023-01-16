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
    $this->db->where('username', $this->session->userdata('username'));
    $this->db->where('b.id_company', $this->session->userdata('id_company'));
    $this->db->where('a.id_company', $this->session->userdata('id_company'));
    $this->db->where('a.i_type', '04');
    $this->db->order_by('e_bagian_name');
    return $this->db->get();
  }

  public function partner($cari, $ibagian, $dawal, $dakhir, $periode){
      $idcompany = $this->session->userdata('id_company');
      return $this->db->query("
                                SELECT DISTINCT
                                  b.id_partner, 
                                  b.i_partner,
                                  b.type_partner, 
                                  x.e_partner
                                FROM (
                                    SELECT
                                        id as id_partner,
                                        e_nama_karyawan AS e_partner,
                                        e_nik AS i_partner,
                                        'karyawan' AS type_partner,
                                        id_company
                                    FROM
                                        tr_karyawan
                                    WHERE
                                        f_status = 't'
                                        AND (e_nama_karyawan ILIKE '%$cari%')
                                        AND id_company = '$idcompany'
                                    UNION ALL
                                    SELECT
                                        id as id_partner,
                                        e_bagian_name AS e_partner,
                                        i_bagian AS i_partner,
                                        'bagian' AS type_partner,
                                        id_company
                                    FROM
                                        tr_bagian
                                    WHERE
                                        f_status = 't'
                                        AND (e_bagian_name ILIKE '%$cari%')
                                        AND id_company = '$idcompany'
                                    UNION ALL
                                    SELECT
                                        id as id_partner,
                                        e_customer_name AS e_partner,
                                        i_customer AS i_partner,
                                        'customer' AS type_partner,
                                        id_company
                                    FROM
                                        tr_customer
                                    WHERE
                                        f_status = 't'
                                        AND (e_customer_name ILIKE '%$cari%')
                                        AND id_company = '$idcompany'
                                    UNION ALL
                                    SELECT
                                        id as id_partner,
                                        e_supplier_name AS e_partner,
                                        i_supplier AS i_partner,
                                        'supplier' AS type_partner,
                                        id_company
                                    FROM
                                        tr_supplier
                                    WHERE
                                        f_status = 't'
                                        AND (e_supplier_name ILIKE '%$cari%')
                                        AND id_company = '$idcompany'
                                ) AS x
                                INNER JOIN 
                                  f_mutasi_saldoawal_gdjadipinjaman_jangka('$idcompany', '$periode', to_date('$dawal','dd-mm-yyyy'), to_date('$dakhir','dd-mm-yyyy'), '$ibagian') b
                                ON (x.id_partner = b.id_partner and x.i_partner = b.i_partner and x.type_partner = b.type_partner and x.id_company = b.id_company)
                                ORDER BY
                                    2 
                              ", FALSE);
  }

  public function getkategori(){
    $id_company = $this->session->userdata('id_company');
    return $this->db->query("     
                            SELECT DISTINCT
                              a.i_kode_kelompok,
                              b.e_nama_kelompok
                            FROM 
                              tr_product_base a
                              INNER JOIN 
                                tr_kelompok_barang b ON (a.i_kode_kelompok = b.i_kode_kelompok and a.id_company = b.id_company)
                            WHERE
                              a.id_company = '$id_company'
                            UNION ALL 
                            SELECT DISTINCT 
                              a.i_kode_kelompok,
                              b.e_nama_kelompok
                            FROM
                              tr_material a 
                            INNER JOIN 
                                tr_kelompok_barang b ON (a.i_kode_kelompok = b.i_kode_kelompok and a.id_company = b.id_company) 
                            WHERE 
                                a.i_kode_group_barang = 'GRB0003'
                                and a.id_company = '$id_company'
                            ");
  }

  public function getjenis($ikelompok, $ibagian){
    $id_company = $this->session->userdata('id_company');
    $where = '';
    if ($ikelompok == "KTB") {
      $where = " AND a.i_kode_kelompok IN (Select i_kode_kelompok from tr_product_base where id_company = '$id_company')";
    } else {
      $where = " AND a.i_kode_kelompok = '$ikelompok' ";
    }

    $this->db->select("
      a.i_type_code, a.e_type_name from tr_item_type a where a.id_company = '$id_company' and a.i_kode_group_barang in ('GRB0003') $where

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

  public function bacapartner($idpartner, $typepartner, $id_company){
    if($idpartner != 'ALL'){       
      $this->db->select(" 
                                * 
                                FROM (
                                    SELECT
                                        id as id_partner,
                                        e_nama_karyawan AS e_partner,
                                        e_nik AS i_partner,
                                        'karyawan' AS type_partner,
                                        id_company
                                    FROM
                                        tr_karyawan
                                    WHERE
                                        f_status = 't'
                                        AND id_company = '$id_company'
                                    UNION ALL
                                    SELECT
                                        id as id_partner,
                                        e_bagian_name AS epartner,
                                        i_bagian AS i_partner,
                                        'bagian' AS type_partner,
                                        id_company
                                    FROM
                                        tr_bagian
                                    WHERE
                                        f_status = 't'
                                        AND id_company = '$id_company'
                                    UNION ALL
                                    SELECT
                                        id as id_partner,
                                        e_customer_name AS e_partner,
                                        i_customer AS i_partner,
                                        'customer' AS type_partner,
                                        id_company
                                    FROM
                                        tr_customer
                                    WHERE
                                        f_status = 't'
                                        AND id_company = '$id_company'
                                    UNION ALL
                                    SELECT
                                        id as id_partner,
                                        e_supplier_name AS e_partner,
                                        i_supplier AS i_partner,
                                        'supplier' AS type_partner,
                                        id_company
                                    FROM
                                        tr_supplier
                                    WHERE
                                        f_status = 't'
                                        AND id_company = '$id_company'
                                ) AS x
                                WHERE 
                                 id_partner = '$idpartner' 
                                 AND type_partner = '$typepartner' 
                                 AND id_company = '$id_company' 
                                ORDER BY
                                    2 
                            ", false);
    } else {
       $this->db->select("'Semua Supplier' as e_partner", false);
    }
    return $this->db->get();
  }

  function cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang, $idpartner, $typepartner){   
    $where = '';
    if($jnsbarang != 'JNB'){
      $where .= "AND a.i_type_code = '$jnsbarang'";
    }

    $where2 = '';
    if($ikelompok != 'KTB') {
      $where2 .= "AND (a.i_kode_kelompok = '$ikelompok')";
    }

    $where3 = '';
    if($idpartner != 'ALL'){
      $where  .= "AND id_partner = '$idpartner' AND type_partner = '$typepartner'";
    }
    $this->db->select("
                           x.id_company,
                           x.i_product_base,
                           sum(x.saldoawal) as saldoawal,
                           sum(x.m_masuk) as m_masuk,
                           sum(x.k_keluar) as k_keluar,
                           sum(x.saldo_akhir) as saldo_akhir,
                           a.e_product_basename,
                           x.i_color,
                           b.e_color_name 
                        from
                            f_mutasi_saldoawal_gdjadipinjaman('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x 
                           inner join
                              tr_product_base a 
                              on (a.id_company = x.id_company 
                              and a.i_product_base = x.i_product_base)
                           inner join
                              tr_color b 
                              on (x.id_company = b.id_company 
                              and x.i_color = b.i_color) 
                        where
                           x.id_company is not null
                              $where $where2 $where3
                        group by
                          x.id_company,
                          x.i_product_base,                   
                          a.e_product_basename,
                          b.e_color_name,
                          x.i_color
    ",FALSE);
    return $this->db->get();
  }
}
/* End of file Mmaster.php */