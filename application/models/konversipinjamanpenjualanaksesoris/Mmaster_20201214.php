<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacagudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', 'GD10002');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
        // return $->get('tr_master_gudang')->result();
  }
 
  public function customer($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            select i_supplier as id, e_supplier_name as partner from tr_supplier
            union all
            select i_customer as id, e_customer_name as partner from tr_customer
            order by partner asc", 
        FALSE);
  }


 public function bonmk($cari, $gudang, $icustomer){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_bonmk, to_char(d_bonmk, 'dd-mm-yyyy') AS d_bonmk 
            FROM
                tm_bonmkeluar_pinjamanaksesoris
            WHERE
                i_kode_master = '$gudang'  AND tujuan_keluar ilike '%external%' and department = '$icustomer'
                AND f_cancel = 'f'", 
        FALSE);
  }

  public function getbonmk($ibonmk,$gudang){
      return $this->db->query("
          select a.i_bonmk, to_char(a.d_bonmk, 'dd-mm-yyyy') as d_bonmk
          from tm_bonmkeluar_pinjamanaksesoris a
          where a.i_bonmk='$ibonmk' and a.i_kode_master = '$gudang'
        ", false);
  }

  public function getbonmk_detail($ibonmk,$gudang){
      return $this->db->query("
            select a.i_bonmk, a.i_kode_master, a.i_material, a.n_qty, a.e_remark, a.i_satuan, a.e_material_name, a.e_satuan,
            case when b.konversi is null then a.sisa-0 else a.sisa-b.konversi end as sisa from
            ( 
            select final.*, case when n_deliver is null then n_qty-0 else n_qty-n_deliver end as sisa  from (
                            select x.* ,y.n_deliver from (
                              select b.*, m.e_material_name, s.e_satuan 
                                    from tm_bonmkeluar_pinjamanaksesoris_detail b
                                    inner join tm_bonmkeluar_pinjamanaksesoris a on (b.i_bonmk = a.i_bonmk and b.i_kode_master = a.i_kode_master)
                                    inner join tr_material m on (b.i_material = m.i_material)
                                    inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
                                    where b.i_bonmk = '$ibonmk' and b.i_kode_master = '$gudang' and a.f_cancel = 'f'
                            ) as x
                            left join (
                            select a.i_bonmk, a.i_kode_master, b.i_material, sum(n_deliver) as n_deliver
                            from tm_bonmmasuk_pinjamanaksesoris a
                            inner join tm_bonmmasuk_pinjamanaksesoris_detail b on (a.i_bonmm = b.i_bonmm and a.i_kode_master = b.i_kode_master)
                            where a.i_bonmk = '$ibonmk' and a.i_kode_master = '$gudang' and a.f_cancel = 'f'
                            group by a.i_bonmk, a.i_kode_master, b.i_material
                            ) y on (x.i_bonmk = y.i_bonmk and x.i_kode_master = y.i_kode_master and x.i_material = y.i_material)
                         ) as final
            ) as a
            left join (
            select a.i_reff, a.i_kode_master, b.i_material, sum(n_konversi) as konversi
            from tm_konversipinjamanpenjualan_aksesoris a
            inner join tm_konversipinjamanpenjualan_aksesoris_detail b on (a.i_konv = b.i_konv and a.i_kode_master = b.i_kode_master)
            where a.i_reff = '$ibonmk' and a.i_kode_master = '$gudang' and a.f_cancel = 'f'
            group by a.i_reff, a.i_kode_master, b.i_material
            ) as b on (a.i_bonmk = b.i_reff and a.i_kode_master = b.i_kode_master and a.i_material = b.i_material)
        ", false);
  }

  function runningnumberkonversi($th,$bl,$ikodemaster)
    {
#      $store=$this->session->userdata('store');
      //select substr('SJKM-2003-000021',11,6) as max, substr('SJKM-2003-000021',6,2) as th, substr('SJKM-2003-000021',8,2) as bl 
      $this->db->select("max(substr(i_konv,10,7)) as max from tm_konversipinjamanpenjualan_aksesoris where substr(i_konv,5,2)='$th' and substr(i_konv,7,2)='$bl' and i_kode_master='$ikodemaster'", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nogj  =$terakhir+1;
        settype($nogj,"string");
        $a=strlen($nogj);
        while($a<7){
          $nogj="0".$nogj;
          $a=strlen($nogj);
        }
        $nogj  ="KPP-".$th.$bl."-".$nogj;
        return $nogj;
      }else{
        $nogj  ="0000001";
        $nogj  ="KPP-".$th.$bl."-".$nogj;
        return $nogj;
      }
    }

    public function insertheader($dbonk,$istore, $remark, $nobonkeluar, $nokonversi, $now, $icustomer){
                    // insertheader($dbonk,$istore, $remark, $nobonkeluar, $nobonmasuk, $now);
        $data = array(
            'i_konv'          => $nokonversi,
            'd_konv'          => $dbonk,
            'i_reff'          => $nobonkeluar,
            'i_kode_master'    => $istore,
            'e_remark'         => $remark,
            'd_insert'         => $now,
            'i_customer'       => $icustomer
            
        );
        $this->db->insert('tm_konversipinjamanpenjualan_aksesoris', $data);
    }

    public function insertdetail($nokonversi,$istore, $imaterial, $nquantity,$isatuan, $edesc, $urutan, $ndeliver)
    {               
        $data = array(        

            'i_konv'        => $nokonversi,
            'i_kode_master' => $istore,
            'i_material'    => $imaterial,
            'n_qty'         => $nquantity,
            'n_konversi'     => $ndeliver,
            'i_satuan_code' => $isatuan,
            'e_remark'      => $edesc,
            'i_no_item'     => $urutan
            
        );
        $this->db->insert('tm_konversipinjamanpenjualan_aksesoris_detail', $data);
    }

    function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("
        select a.i_konv, a.d_konv, a.i_customer, x.partner, a.i_reff, a.e_remark, a.f_cancel , a.i_kode_master, '$i_menu' as i_menu  from tm_konversipinjamanpenjualan_aksesoris a
          inner join (
          select i_supplier as id, e_supplier_name as partner from tr_supplier
          union all
          select i_customer as id, e_customer_name as partner from tr_customer
          order by partner asc
          ) as x on (a.i_customer = x.id)
        ");

        $datatables->edit('f_cancel', function ($data) {
          $f_cancel = trim($data['f_cancel']);
            if($f_cancel == 't'){
               return  "Batal";
            }else {
              return "Aktif";
            }
        });
      //   $datatables->edit('f_cancel', function ($data) {
      //   $f_cancel = trim($data['f_cancel']);
      //   if($f_cancel == 't'){
      //      return  "Batal";
      //   }else {
      //     return "Aktif";
      //   }
      // });
        
        $datatables->add('action', function ($data) {
            $i_konv = trim($data['i_konv']);
            $gudang = trim($data['i_kode_master']);
            $i_menu = $data['i_menu'];
            $i_reff = trim($data['i_reff']);
            $i_customer = trim($data['i_customer']);
            $f_cancel = trim($data['f_cancel']);
            $data = '';
            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"bonmkeluarbbp/cform/view/$i_bonk/$tujuankirim\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            if(check_role($i_menu, 3)){
              if ($f_cancel == 'f') {
                $data .= "<a href=\"#\" onclick='show(\"konversipinjamanpenjualanaksesoris/cform/edit/$i_konv/$gudang/$i_reff\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                $data .= "<a href=\"#\" onclick='cancel(\"$i_konv\",\"$gudang\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
              }
                
            }
          //   if(check_role($i_menu, 1)){
          //     $data .= "<a href=\"#\" onclick='show(\"pembelianpp/cform/approve/$i_pp/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
          // }
			return $data;
        });
        $datatables->hide('i_kode_master');
        $datatables->hide('i_customer');
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	public function baca_header($i_konv,$gudang){
      return $this->db->query("
        select a.i_konv, a.d_konv, a.i_customer, x.partner, a.i_reff, a.e_remark, a.f_cancel , a.i_kode_master, c.e_nama_master from tm_konversipinjamanpenjualan_aksesoris a
          inner join (
          select i_supplier as id, e_supplier_name as partner from tr_supplier
          union all
          select i_customer as id, e_customer_name as partner from tr_customer
          order by partner asc
          ) as x on (a.i_customer = x.id)
        inner join tr_master_gudang c on (a.i_kode_master = c.i_kode_master)
  		  where a.i_konv = '$i_konv' and a.i_kode_master = '$gudang' ", false);
    }

    public function baca_detail($i_konv, $gudang,$i_reff){
      return $this->db->query("
      	select x.*,case when y.qty2 is null then 0 else y.qty2 end as qty2, y.e_remark as e_remark2 from (
          select a.i_bonmk, a.i_kode_master, a.i_material, a.n_qty, a.e_remark, a.i_satuan, a.e_material_name, a.e_satuan,
                    case when b.konversi is null then a.sisa-0 else a.sisa-b.konversi end as sisa from
                    ( 
                    select final.*, case when n_deliver is null then n_qty-0 else n_qty-n_deliver end as sisa  from (
                                    select x.* ,y.n_deliver from (
                                      select b.*, m.e_material_name, s.e_satuan 
                                            from tm_bonmkeluar_pinjamanaksesoris_detail b
                                            inner join tm_bonmkeluar_pinjamanaksesoris a on (b.i_bonmk = a.i_bonmk and b.i_kode_master = a.i_kode_master)
                                            inner join tr_material m on (b.i_material = m.i_material)
                                            inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
                                            where b.i_bonmk = '$i_reff' and b.i_kode_master = '$gudang' and a.f_cancel = 'f'
                                    ) as x
                                    left join (
                                    select a.i_bonmk, a.i_kode_master, b.i_material, sum(n_deliver) as n_deliver
                                    from tm_bonmmasuk_pinjamanaksesoris a
                                    inner join tm_bonmmasuk_pinjamanaksesoris_detail b on (a.i_bonmm = b.i_bonmm and a.i_kode_master = b.i_kode_master)
                                    where a.i_bonmk = '$i_reff' and a.i_kode_master = '$gudang' and a.f_cancel = 'f'
                                    group by a.i_bonmk, a.i_kode_master, b.i_material
                                    ) y on (x.i_bonmk = y.i_bonmk and x.i_kode_master = y.i_kode_master and x.i_material = y.i_material)
                                 ) as final
                    ) as a
                    left join (
                    select a.i_reff, a.i_kode_master, b.i_material, sum(n_konversi) as konversi
                    from tm_konversipinjamanpenjualan_aksesoris a
                    inner join tm_konversipinjamanpenjualan_aksesoris_detail b on (a.i_konv = b.i_konv and a.i_kode_master = b.i_kode_master)
                    where a.i_reff = '$i_reff' and a.i_kode_master = '$gudang' and a.f_cancel = 'f'
                    group by a.i_reff, a.i_kode_master, b.i_material
                    ) as b on (a.i_bonmk = b.i_reff and a.i_kode_master = b.i_kode_master and a.i_material = b.i_material)
        ) as x 
        left join (
                select a.i_reff, a.i_kode_master, b.i_material, b.n_konversi as qty2, b.e_remark
                    from tm_konversipinjamanpenjualan_aksesoris a
                    inner join tm_konversipinjamanpenjualan_aksesoris_detail b on (a.i_konv = b.i_konv and a.i_kode_master = b.i_kode_master)
                        inner join tr_material m on (b.i_material = m.i_material)
                        inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
                        where b.i_konv = '$i_konv' and b.i_kode_master = '$gudang'  and a.f_cancel = 'f'
        ) as y on (x.i_bonmk = y.i_reff and x.i_kode_master = y.i_kode_master and x.i_material = y.i_material) ", false);
    }

    public function updateheader($dkonversi, $remark, $nokonversi, $now, $istore){
        $data = array(
            'd_konv'       => $dkonversi,
            'e_remark'      => $remark,
            'd_update'      => $now
            
        );
        $this->db->where('i_konv', $nokonversi);
        $this->db->where('i_kode_master', $istore);
        $this->db->update('tm_konversipinjamanpenjualan_aksesoris', $data);
    }

    public function updatedetail($nobonmasuk,$istore, $imaterial, $remark){
        $this->db->query("UPDATE tm_bonmmasuk_pinjamanbb_detail SET e_remark = '$remark' WHERE i_bonmm='$nobonmasuk' and i_kode_master='$istore' and i_material = '$imaterial' ");
  	}


  // public function product($cari, $gudang){
  //       $cari = str_replace("'", "", $cari);
  //       return $this->db->query("
  //           SELECT
  //               a.*, b.e_satuan
  //           FROM
  //               tr_material a
  //           INNER JOIN 
  //               tr_satuan b on (a.i_satuan=b.i_satuan)
  //           WHERE
  //               a.i_store = '$gudang'
  //               AND (UPPER(a.i_material) LIKE '%$cari%'
  //               OR UPPER(a.e_material_name) LIKE '%$cari%')", 
  //       FALSE);
  // }
      

  public function deletedetail($nokonversi, $istore){
        $this->db->query("DELETE FROM tm_konversipinjamanpenjualan_aksesoris_detail WHERE i_konv='$nokonversi' and i_kode_master='$istore' ");
  }

  public function cek_product($bonmkp,$gudang, $i_material){
      return $this->db->query("select i_material from tm_bonmkeluar_pinjamanbb_detail where i_bonmk = '$bonmkp' and i_kode_master = '$gudang' and i_material = '$i_material' ", false);
  }

  

  public function cancel($ikonv, $gudang){
        $this->db->set(
            array(
                'f_cancel'  => 't'
            )
        );
        $this->db->where('i_konv',$ikonv);
        $this->db->where('i_kode_master',$gudang);
        return $this->db->update('tm_konversipinjamanpenjualan_aksesoris');
  }

	// function cek_data($i_bonk, $tujuankirim){
 //    if($tujuankirim = "UP"){
 //      $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan, e.e_nama_packing as tujuan
 //          from duta_prod.tm_bonkeluarbb a 
 //          inner join duta_prod.tr_master_gudang b on (a.i_kode_master = b.i_kode_master) 
 //          inner join duta_prod.tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char) 
 //          inner join duta_prod.tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan) 
 //          inner join duta_prod.tr_unit_packing e on(e.i_unit_packing=a.i_tujuan_kirim)
 //          where i_bonk = '$i_bonk' and a.i_tujuan = '$tujuankirim'",false);
 //    }elseif($tujuankirim = "UJ"){
 //      $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan, e.e_unitjahit_name as tujuan
 //          from duta_prod.tm_bonkeluarbb a 
 //          inner join duta_prod.tr_master_gudang b on (a.i_kode_master = b.i_kode_master) 
 //          inner join duta_prod.tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char) 
 //          inner join duta_prod.tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan) 
 //          inner join duta_prod.tr_unit_jahit e on(e.i_unit_jahit=a.i_tujuan_kirim)
 //          where i_bonk = '$i_bonk' and a.i_tujuan = '$tujuankirim'",false);
 //    }elseif($tujuankirim = "CT"){
 //      $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan, e.i_schedule as tujuan
 //          from duta_prod.tm_bonkeluarbb a 
 //          inner join duta_prod.tr_master_gudang b on (a.i_kode_master = b.i_kode_master) 
 //          inner join duta_prod.tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char) 
 //          inner join duta_prod.tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan) 
 //          inner join duta_prod.tm_spbb e on(e.i_spbb=a.i_tujuan_kirim)
 //          where i_bonk = '$i_bonk' and a.i_tujuan = '$tujuankirim'",false);
 //    }else{
 //          $this->db->select("a.*, b.e_nama_master, c.e_jenis_keluar, d.e_tujuan 
 //          from tm_bonkeluarbb a
 //          inner join tr_master_gudang b on (a.i_kode_master = b.i_kode_master)
 //          inner join tr_jenis_keluarbb c on (a.i_jenis_keluar = c.i_jenis::char)
 //          inner join tr_jenis_kirimbb d on (a.i_tujuan = d.i_tujuan)
 //          where i_bonk = '$i_bonk' ",false);
 //    }
 //        return $this->db->get();
 //  }
 //  function cek_datadet($i_bonk){
	// 	$this->db->select('a.*, b.e_satuan, c.e_material_name');
 //        $this->db->from('tm_bonkeluarbb_detail a');
 //        $this->db->join('tr_satuan b','a.i_satuan = b.i_satuan');
 //        $this->db->join('tr_material c','a.i_material = c.i_material');
 //        $this->db->where('a.i_bonk', $i_bonk);
 //        return $this->db->get();
	// }
 //  // function cek_datadet($i_bonm){
	// // 	$this->db->select('a.*, b.e_satuan');
 //  //       $this->db->from('tm_bonmasuk_lain_detail a');
 //  //       $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
 //  //       $this->db->where('a.i_bonm', $i_bonm);
 //  //       return $this->db->get();
 //  // }
 //  function cek_dataheader($nobonk){
 //    $this->db->select('*');
 //        $this->db->from('tm_bonkeluarbb');
 //        // $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
 //        $this->db->where('i_bonk', $nobonk);
 //        return $this->db->get();
 //  }
 //  function cekdatadetail($nobonk, $imaterial){
 //    $this->db->select('*');
 //        $this->db->from('tm_bonkeluarbb_detail');
 //        $this->db->where('i_bonk', $nobonk);
 //        $this->db->where('i_material', $imaterial);
 //        return $this->db->get();
 //    }
    
  
    
 //    public function gettujuanUJ(){
 //      $this->db->select("i_unit_jahit as itujuank, e_unitjahit_name as etujuank");
 //      $this->db->from('tr_unit_jahit');
 //      $this->db->order_by('i_unit_jahit');
 //      return $this->db->get();
 //    }
 //    public function gettujuanUP(){
 //      $this->db->select("i_unit_packing as itujuank, e_nama_packing as etujuank");
 //      $this->db->from('tr_unit_packing');
 //      $this->db->order_by('i_unit_packing');
 //      return $this->db->get();
 //    }
 //    public function gettujuanCT(){
 //      $this->db->select("i_spbb as itujuank, i_schedule as etujuank");
 //      $this->db->from('tm_spbb');
 //      $this->db->order_by('i_spbb');
 //      return $this->db->get();
 //    }
    
	 
    
   

    

    
    // public function updateheader($nobonk, $dbonk, $remark, $now){
    //     $data = array(
    //         'd_bonk'        => $dbonk,
    //         'e_remark'        => $remark,
    //         'd_update'       => $now,
    // );

    // $this->db->where('i_bonk', $nobonk);
    // $this->db->update('tm_bonkeluarbb', $data);
    // }
    // public function updatedetail($nquantity,$nquantitykonv,$nobonk, $imaterial){
    //   $data = array(
    //       'n_qty' => $nquantity,
    //       'n_qty_unit_first' => $nquantity,
    //   );

    //   $this->db->where('i_bonk', $nobonk);
    //   $this->db->where('i_material', $imaterial);
    //   $this->db->update('tm_bonkeluarbb_detail', $data);
    // }
    // public function approve($ipp, $now){
    //   $data = array(
    //     'e_approve' => 't',
    //     'd_approve' => $now,
    // );
    // $this->db->where('i_pp', $ipp);
    //   $this->db->update('tm_pp', $data);
    // }
}

/* End of file Mmaster.php */
