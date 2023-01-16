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

  public function getproduct($ilokasigudang){
    $this->db->select("a.*, b.e_color_name");
    $this->db->from("tm_ic a");
    $this->db->join("tr_color b", "a.i_color=b.i_color");
    $this->db->where("a.i_kode_lokasi", $ilokasigudang);
    // $this->db->where("a.i_kode_master", $ikodemaster);
    return $this->db->get();
  }
  
  public function getkodemaster($ikodemaster){
    $this->db->select("*");
    $this->db->from("tr_master_gudang ");
    // $this->db->join("tr_color b", "a.i_color=b.i_color");
    $this->db->where("i_kode_master", $ikodemaster);
    // $this->db->where("a.i_kode_master", $ikodemaster);
    return $this->db->get();
  }
 
  public function cek_so_bahanbaku($ilokasigudang, $iperiodebl, $iperiodeth){
      $this->db->select('i_stok_opname_bahanbaku, d_so, f_status_approve');
          $this->db->from('tt_stok_opname_bahan_baku');
          $this->db->where('d_bulan',$iperiodebl);
          $this->db->where('d_tahun',$iperiodeth);
          $this->db->where('i_kode_lokasi',$ilokasigudang);
      return $this->db->get();
  }
  
  public function cekso_sebelum($ilokasigudang, $iperiodebl, $iperiodeth){
        $sql = "select i_stok_opname_bahanbaku FROM tt_stok_opname_bahan_baku WHERE f_status_approve = 'f' AND i_kode_lokasi = '$ilokasigudang' ";
        if ($iperiodebl == 1) {
            $sql.=" AND d_bulan<='12' AND d_tahun<'$iperiodeth' ";
         }else
            $sql.= " AND ((d_bulan < '$iperiodebl' AND d_tahun ='$iperiodeth') OR (d_bulan <='12' AND d_tahun <'$iperiodeth')) ";
        
        $query3 = $this->db->query($sql);
        return $query3;
  }

  public function cekso($ilokasigudang, $iperiodebl, $iperiodeth){
       $this->db->select("i_stok_opname_bahanbaku 
                        FROM tt_stok_opname_bahan_baku WHERE d_bulan > '$iperiodebl' AND d_tahun >= '$iperiodeth' AND i_kode_lokasi = '$ilokasigudang'");
        return $this->db->get();
  }

  public function get_all_stok_opname_bahanbaku($ilokasigudang, $iperiodebl, $iperiodeth){
       $this->db->select(" kode, qty, nama_barang, satuan
                      from
                        (select
                          a.i_kode_brg as kode,
                          a.v_jum_stok_opname as qty,
                          c.e_nama_brg as nama_barang,
                          d.e_satuan as satuan
                            from duta_prod.tt_stok_opname_bahan_baku_detail a
                                inner join duta_prod.tm_barang2 c ON c.i_kode_brg = a.i_kode_brg
                                inner join duta_prod.tr_satuan d on d.i_satuan_code = c.i_satuan_code
                                inner join duta_prod.tt_stok_opname_bahan_baku e on e.i_stok_opname_bahanbaku = a.i_stok_opname_bahanbaku
                                    WHERE e.i_kode_lokasi = '$ilokasigudang' 
                                    AND c.f_status_aktif = 't' 
                                    AND e.d_bulan = '$iperiodebl' 
                                    AND e.d_tahun = '$iperiodeth'
                        
                        union all
                        select 
                        b.i_kode_brg as kode,   
                        b.v_stok as qty,
                        c.e_nama_brg as nama_barang,
                        d.e_satuan as satuan
                          from duta_prod.tm_stok b
                              inner join duta_prod.tm_barang2 c ON c.i_kode_brg = b.i_kode_brg
                              inner join duta_prod.tr_satuan d on d.i_satuan_code = c.i_satuan_code
                                WHERE c.i_kode_lokasi = '$ilokasigudang' AND c.f_status_aktif='t' 
                                AND c.i_kode_brg NOT IN 
                                (select b.i_kode_brg FROM duta_prod.tt_stok_opname_bahan_baku a
                                INNER JOIN duta_prod.tt_stok_opname_bahan_baku_detail b ON a.i_stok_opname_bahanbaku = b.i_stok_opname_bahanbaku 
                                WHERE a.d_bulan = '$iperiodebl' AND a.d_tahun = '$iperiodeth' AND a.i_kode_lokasi='$ilokasigudang')
                      )as x", false);
       return $this->db->get();
  }

  // public function get_all_stok_opname_bahanbaku($ilokasigudang, $iperiodebl, $iperiodeth){
  //      $this->db->select("kode, qty, nama_barang, satuan, lokasi
  //                     from
  //                       (select
  //                         a.i_kode_brg as kode,
  //                         a.v_jum_stok_opname as qty,
  //                         c.i_material as kode_barang,
  //                         c.e_material_name as nama_barang,
  //                         f.i_kode_lokasi as lokasi,
  //                         d.e_satuan as satuan
  //                           from duta_prod.tt_stok_opname_bahan_baku_detail a
  //                               inner join duta_prod.tr_material c on c.i_material = a.i_kode_brg
  //                               inner join duta_prod.tr_satuan d on d.i_satuan= c.i_satuan
  //                               inner join duta_prod.tr_master_gudang f on f.i_kode_master = c.i_store
  //                               inner join duta_prod.tt_stok_opname_bahan_baku e on e.i_stok_opname_bahanbaku = a.i_stok_opname_bahanbaku
  //                                   WHERE e.i_kode_lokasi = '$ilokasigudang' 
  //                                   AND c.f_active = 't' 
  //                                   AND e.d_bulan = '$iperiodebl' 
  //                                   AND e.d_tahun = '$iperiodeth'
                        
  //                       union all
  //                       select 
  //                       b.i_kode_brg as kode,   
  //                       b.v_stok as qty,
  //                       c.i_material as kode_barang,
  //                       c.e_material_name as nama_barang,
  //                       f.i_kode_lokasi as lokasi,
  //                       d.e_satuan as satuan
  //                         from duta_prod.tm_stok b
  //                             inner join duta_prod.tr_material c on c.i_material = b.i_kode_brg
  //                             inner join duta_prod.tr_master_gudang f on f.i_kode_master = c.i_store                        
  //                             inner join duta_prod.tr_satuan d on d.i_satuan = c.i_satuan
  //                               WHERE 
  //                                f.i_kode_lokasi = '$ilokasigudang' 
  //                               AND c.f_active='t' 
  //                               AND c.i_material NOT IN 
  //                               (select b.i_kode_brg FROM duta_prod.tt_stok_opname_bahan_baku a
  //                               INNER JOIN duta_prod.tt_stok_opname_bahan_baku_detail b ON a.i_stok_opname_bahanbaku = b.i_stok_opname_bahanbaku 
  //                               WHERE a.d_bulan = '$iperiodebl' AND a.d_tahun = '$iperiodeth' AND a.i_kode_lokasi='$ilokasigudang')
  //                     )as x", false);
  //      return $this->db->get();
  //}

  public function getall_stok_bahanbaku($ilokasigudang, $iperiodebl, $iperiodeth){
      $this->db->select("distinct a.i_stok_opname_bahanbaku, d.i_kode_brg, b.e_nama_brg, d.v_jum_stok_opname as v_jum, b.i_satuan_konversi, b.rumus_konversi,
                           b.angka_faktor_konversi, c.e_satuan 
                           FROM tt_stok_opname_bahan_baku a 
                           JOIN tt_stok_opname_bahan_baku_detail d ON a.i_stok_opname_bahanbaku = d.i_stok_opname_bahanbaku
                           JOIN tm_barang2 b ON d.i_kode_brg = b.i_kode_brg
                           JOIN tr_satuan c ON c.i_satuan_code = b.i_satuan_code
                           WHERE b.i_kode_lokasi = '$ilokasigudang' 
                           AND b.f_status_aktif = 't' 
                           AND a.d_bulan = '$iperiodebl' 
                           AND a.d_tahun = '$iperiodeth'", false);
      return $this->db->get();
  }

  public function get_detail_stokbrgbaru($ilokasigudang, $iperiodebl, $iperiodeth){
          $this->db->select("b.i_kode_brg, a.i_stok, b.e_nama_brg, c.e_satuan, b.i_satuan_konversi, b.rumus_konversi, b.angka_faktor_konversi
                FROM tm_barang2 b 
                LEFT JOIN tm_stok a ON a.i_kode_brg = b.i_kode_brg
                LEFT JOIN tr_satuan c ON c.i_satuan_code = b.i_satuan_code
                WHERE b.i_kode_lokasi = '$ilokasigudang' AND b.f_status_aktif='t' 
                AND b.i_kode_brg NOT IN 
                (select b.i_kode_brg FROM tt_stok_opname_bahan_baku a
                INNER JOIN tt_stok_opname_bahan_baku_detail b ON a.i_stok_opname_bahanbaku = b.i_stok_opname_bahanbaku 
                WHERE a.d_bulan = '$iperiodebl' AND a.d_tahun = '$iperiodeth' AND a.i_kode_lokasi='$ilokasigudang')", false);
           return $this->db->get();
  } 

  function runningnumber($yearmonth, $ilokasigudang){
    $th = substr($yearmonth,0,4);
    $asal=$yearmonth;
    $yearmonth=substr($yearmonth,2,2).substr($yearmonth,4,2);
    $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='SO'
                        and i_area='$ilokasigudang'
                        and e_periode='$asal' 
                        and substring(e_periode,1,4)='$th' for update", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $nopp  =$terakhir+1;
            $this->db->query("update tm_dgu_no 
                        set n_modul_no=$nopp
                        where i_modul='SO'
                        and e_periode='$asal' 
                        and i_area='$ilokasigudang'
                        and substring(e_periode,1,4)='$th'", false);
      settype($nopp,"string");
      $a=strlen($nopp);
      while($a<7){
        $nopp="0".$nopp;
        $a=strlen($nopp);
      }
        $nopp  ="SO-".$yearmonth."-".$nopp;
      return $nopp;
    }else{
      $nopp  ="0000001";
      $nopp  ="SO-".$yearmonth."-".$nopp;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('SO','$ilokasigudang','$asal',1)");
      return $nopp;
    }
  }
  
  public function insertheader($iso, $dateso, $per, $ilokasigudang){
    $dentry = current_datetime();
    $data=array(
        'i_so'        => $iso,
        'd_so'        => $dateso,
        'd_periode'   => $per,
        'i_status_so' => '1',
        'd_entry'     => $dentry,
        'i_kode_lokasi'=> $ilokasigudang
        
    );
    $this->db->insert('tt_stokopname_gdjadi',$data);
  } 

  public function insertdetail($iso, $per, $iproduct, $icolor, $qty, $iproductgrade){
      $data = array(
            'i_so_item'       =>'0',
            'i_so'            =>$iso, 
            'd_periode'       =>$per,
            'i_product'       =>$iproduct,
            'i_color'         =>$icolor,
            'n_quantity_awal' =>$qty,
            'i_product_grade' =>$iproductgrade
      );
      $this->db->insert('tt_stokopname_gdjadi_detail', $data);
  }
}
/* End of file Mmaster.php */
