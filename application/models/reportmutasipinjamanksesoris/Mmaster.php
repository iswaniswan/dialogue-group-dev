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

    public function partner($cari, $dfrom1, $dto1, $periode, $idcompany, $ibagian)
    {
        return $this->db->query("
                                select distinct
                                    a.id_partner, 
                                    a.i_partner,
                                    a.type_partner, 
                                    b.e_partner_name
                                from 
                                    f_mutasi_saldoawal_akspinjaman_jangka('$idcompany','$periode','$dfrom1','$dto1','$ibagian') a
                                    inner join
                                    (select id as id_partner, i_supplier as i_partner, e_supplier_name as e_partner_name, 'supplier' as type_partner, id_company
                                        from tr_supplier
                                        union all 
                                        select id as id_partner, i_customer as i_partner, e_customer_name as e_partner_name, 'customer' as type_partner, id_company
                                        from tr_customer
                                        union all 
                                        select id as id_partner, i_bagian as i_partner, e_bagian_name as e_partner_name, 'bagian' as type_partner, id_company
                                        from tr_bagian
                                        union all 
                                        select id as id_partner, e_nik as i_partner, e_nama_karyawan as e_partner_name, 'karyawan' as type_partner, id_company
                                        from tr_karyawan
                                    ) b on (a.id_partner = b.id_partner and a.i_partner = b.i_partner and a.type_partner = b.type_partner 
                                    and a.id_company = b.id_company)
                                ", FALSE);
    }

    public function bacabagian($ibagian) {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.i_bagian', $ibagian);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
  }

  public function getkategori($ibagian){
      $id_company = $this->session->userdata('id_company');
      $this->db->select("
        a.i_kode_kelompok, b.e_nama_kelompok from tr_bagian_kelompokbarang a
        inner join tr_kelompok_barang b on (a.i_kode_kelompok = b.i_kode_kelompok)
        where a.i_bagian = '$ibagian' and a.id_company = '$id_company' 

      ", false);
      return $this->db->get();
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

  public function bacapartner($ipartner){
      if($ipartner != 'all'){
         $this->db->select("
                            distinct
                                *
                            from
                                (select id as id_partner, i_supplier as i_partner, e_supplier_name as e_partner_name, 'supplier' as type_partner, id_company
                                    from tr_supplier
                                    union all 
                                    select id as id_partner, i_customer as i_partner, e_customer_name as e_partner_name, 'customer' as type_partner, id_company
                                    from tr_customer
                                    union all 
                                    select id as id_partner, i_bagian as i_partner, e_bagian_name as e_partner_name, 'bagian' as type_partner, id_company
                                    from tr_bagian
                                    union all 
                                    select id as id_partner, e_nik as i_partner, e_nama_karyawan as e_partner_name, 'karyawan' as type_partner, id_company
                                    from tr_karyawan
                                ) b 
                            where 
                                b.i_partner = '$ipartner'
                                and b.id_company = '".$this->session->userdata('id_company')."'
                            ", false);
      } else {
         $this->db->select("
                             'Semua Partner' as e_partner_name
                            ", false);
      }
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

  function cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang, $ipartner){   
      $where = '';
      if($jnsbarang != 'JNB'){
        $where .= "AND a.i_type_code = '$jnsbarang'";
      }

      $where2 = '';
      if($ikelompok != 'KTB') {
        $where2 .= "AND (a.i_kode_kelompok = '$ikelompok')";
      }

      $where3 = '';
      if($ipartner != 'all') {
        $where3 .= "AND (x.i_partner = '$ipartner')";
      }

    $this->db->select("
                      x.id_company,
                      x.i_material, 
                      sum(x.saldoawal) as saldoawal,
                      sum(x.m_masuk) as m_masuk,
                      sum(x.k_keluar) as k_keluar,
                      sum(x.konversi) as konversi,
                      sum(x.saldo_akhir) as saldo_akhir,
                      a.e_material_name, 
                      b.e_satuan_name 
                      from 
                        f_mutasi_saldoawal_akspinjaman(
                          '$id_company', '$i_periode', '$d_jangka_awal', 
                          '$d_jangka_akhir', '$dfrom', '$dto', 
                          '$ibagian'
                        ) x 
                        inner join tr_material a on (
                          a.id_company = x.id_company 
                          and a.i_material = x.i_material
                        ) 
                        inner join tr_satuan b on (
                          a.id_company = b.id_company 
                          and a.i_satuan_code = b.i_satuan_code
                        ) 
                      where 
                        x.id_company is not null $where $where2 $where3    
                      group by 
                          x.id_company, 
                          x.i_material,
                          a.e_material_name,
                          b.e_satuan_name
    ",FALSE);
    return $this->db->get();
  }
}

/* End of file Mmaster.php */
