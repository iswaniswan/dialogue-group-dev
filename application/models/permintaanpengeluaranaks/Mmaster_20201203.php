<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacagudang(){
    $gudang = $this->session->userdata('i_departement');
    $gudang = trim($gudang);
        return $this->db->query("
                                SELECT
                                  *
                                FROM
                                  public.tr_departement
                                WHERE
                                  i_departement ='$gudang'
                                ORDER BY
                                  e_departement_name asc
                                ",FALSE);
  }

  public function gudang(){
    $idept = $this->session->userdata('i_departement');
    $idept = trim($idept);
    
    if($idept == '1'){
      return $this->db->query("
                            SELECT
                              i_kode_master,
                              e_nama_master
                            FROM
                              tr_master_gudang
                            ",FALSE);
    }else{
      return $this->db->query("
                              SELECT
                                i_kode_master,
                                e_nama_master
                              FROM
                                tr_master_gudang
                              WHERE
                                i_departemen = '$idept'
                              ",FALSE);
    }
  }

  public function bacajeniskeluar(){
        $this->db->select('*');
        $this->db->from('tr_jenis_pengeluaran');
        $this->db->order_by('e_nama_jenis','ASC');
        return $this->db->get()->result();
  }
  
  public function partner($tujuankeluar){
      if ($tujuankeluar == "internal") {
        return $this->db->query("
            select i_sub_bagian as id, e_sub_bagian as partner from tm_sub_bagian
            order by partner asc", 
        FALSE);
      } else {
        return $this->db->query("
            select i_supplier as id, e_supplier_name as partner from tr_supplier
            order by partner asc", 
        FALSE);
      }       
  }

  public function karyawan(){
      return $this->db->query("
          select i_karyawan as id, e_nama_karyawan as nama from tm_karyawan
          order by e_nama_karyawan asc", 
      FALSE);    
  }

  public function product($cari, $gudang){
        $idept = $this->session->userdata('i_departement');
        $cari = str_replace("'", "", $cari);
        if($idept == '1'){
          return $this->db->query("
                                  SELECT
                                      a.*, d.e_satuan
                                  FROM
                                      tr_material a
                                  INNER JOIN 
                                     tm_kelompok_barang b on (a.i_kode_kelompok=b.i_kode_kelompok) 
                                  INNER JOIN 
                                     tr_master_gudang c on (b.i_kode_master=c.i_kode_master) 
                                  INNER JOIN
                                     tr_satuan d on (a.i_satuan_code=d.i_satuan_code)
                                  WHERE
                                     a.i_kode_kelompok='KTB0001'
                                     AND (UPPER(a.i_material) LIKE '%$cari%'
                                     OR UPPER(a.e_material_name) LIKE '%$cari%')
                                     order by a.i_material", 
                                  FALSE);
        }else{
          return $this->db->query("
                                  SELECT
                                      a.*, d.e_satuan
                                  FROM
                                      tr_material a
                                  INNER JOIN 
                                     tm_kelompok_barang b on (a.i_kode_kelompok=b.i_kode_kelompok) 
                                  INNER JOIN 
                                     tr_master_gudang c on (b.i_kode_master=c.i_kode_master) 
                                  INNER JOIN
                                     tr_satuan d on (a.i_satuan_code=d.i_satuan_code)
                                  WHERE
                                     a.i_kode_kelompok='KTB0001' AND
                                      b.i_kode_master = '$gudang'
                                      AND (UPPER(a.i_material) LIKE '%$cari%'
                                      OR UPPER(a.e_material_name) LIKE '%$cari%')
                                      order by a.i_material", 
                                FALSE);
        }
       
  }

  function runningnumbermemopengeluaran($yearmonth,$istore){
      $bl = substr($yearmonth,4,2);
      $th = substr($yearmonth,2,2);
      $this->db->select("max(substr(i_permintaan,10,6)) as max from tm_permintaanpengeluaranaks where substr(i_permintaan,5,2)='$th' and substr(i_permintaan,7,2)='$bl' and i_kode_master='$istore'", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nogj  =$terakhir+1;
        settype($nogj,"string");
        $a=strlen($nogj);
        while($a<6){
          $nogj="0".$nogj;
          $a=strlen($nogj);
        }
        $nogj  ="PKA-".$th.$bl."-".$nogj;
        return $nogj;
      }else{
        $nogj  ="000001";
        $nogj  ="PKA-".$th.$bl."-".$nogj;
        return $nogj;
      }
    }

    public function insertheader($dbonk, $dback, $istore, $tujuankeluar, $pic, $dept, $remark, $nobonkeluar, $epic, $jenispengeluaran, $ireffmemo){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
            'i_permintaan'     => $nobonkeluar,
            'd_pp'             => $dbonk,
            'i_kode_master'    => $istore,
            'jenis_pengeluaran' => $jenispengeluaran,
            'tujuan_keluar'    => $tujuankeluar,
            'pic'              => $pic,
            'partner'          => $dept,
            'e_remark'         => $remark,
            'pic_eks'          => $epic,
            'i_status'          => '1',
            'd_insert'         => $dentry,
            'i_reff_memo'      => $ireffmemo,
            'd_back'          => $dback
            
        );
        $this->db->insert('tm_permintaanpengeluaranaks', $data);
    }

    public function insertdetail($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $no, $imaterial2, $nquantity2,$isatuan2){               
        $data = array(        

            'i_permintaan'    => $nobonkeluar,
            'i_kode_master'   => $istore,
            'i_material'      => $imaterial,
            'n_qty'           => $nquantity,
            'n_qty_sisa'      => $nquantity,
            'i_satuan_code'   => $isatuan,
            'i_material2'      => $imaterial2,
            'n_qty2'           => $nquantity2,
            'n_qty_sisa2'      => $nquantity2,
            'i_satuan_code2'   => $isatuan2,
            'e_remark'        => $edesc,
            'i_no_item'       => $no
            
        );
        $this->db->insert('tm_permintaanpengeluaranaks_detail', $data);
    }


	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select distinct
                              b.i_permintaan,
                              b.d_pp,
                              b.i_kode_master,
                              c.e_nama_jenis,
                              b.tujuan_keluar,
                              x.partner,
                              z.epic,
                              b.pic_eks,
                              b.e_remark,
                              b.i_status,
                              x.id,
                              s.e_status as namastatus,
                              '$i_menu' as i_menu,
                              s.label_color as label
                            from
                              tm_permintaanpengeluaranaks b 
                              inner join
                                 tr_master_gudang g 
                                 on (b.i_kode_master = g.i_kode_master) 
                              inner join
                                 tr_jenis_pengeluaran c 
                                 on (b.jenis_pengeluaran = c.i_jenis) 
                              left join
                                 (
                                    select
                                      'Supplier' as id,
                                      'Supplier' as partner 
                                    from
                                      tr_supplier 
                                    union all
                                    select
                                      i_departement as id,
                                      e_departement_name as partner 
                                    from
                                      public.tr_departement
                                    union all
                                    select 
                                      'Customer' as id,
                                      'Customer' as partner
                                    from
                                      tr_customer 
                                 )
                                 as x 
                                 on (b.partner = x.id) 
                              left join
                                (
                                  select 
                                    i_karyawan as ipic,
                                    e_nama_karyawan as epic
                                  from
                                    tm_karyawan
                                  union all
                                  select 
                                    i_supplier as ipic,
                                    e_supplier_name as epic
                                  from 
                                    tr_supplier
                                  union all
                                  select
                                    i_customer as ipic,
                                    e_customer_name as ipic
                                  from
                                    tr_customer
                                ) as z
                                on (b.pic = z.ipic)  
                              inner join
                                 tm_status_dokumen s 
                                 on (b.i_status = s.i_status)
                            order by
                              b.i_permintaan
                          ", FALSE);
        
        $datatables->add('action', function ($data) {
            $i_permintaan  = trim($data['i_permintaan']);
            $partner       = trim($data['id']);
            $gudang        = trim($data['i_kode_master']);
            $tujuan_keluar = trim($data['tujuan_keluar']);
            $status        = trim($data['i_status']);
            $i_menu        = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
               $data .= "<a href=\"#\" onclick='show(\"permintaanpengeluaranaks/cform/view/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
              if ($status == '1' || $status == '3' || $status == '7') { //tambah validasi jika sudah ada bonmasuk tidak bisa cancel/edit
                $data .= "<a href=\"#\" onclick='show(\"permintaanpengeluaranaks/cform/edit/$i_permintaan/$gudang/$tujuan_keluar/$partner/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                $data .= "<a href=\"#\" onclick='cancel(\"$i_permintaan\",\"$gudang\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
              }

              if ($status == '2') { //tambah validasi jika sudah ada bonmasuk tidak bisa cancel/edit
                $data .= "<a href=\"#\" onclick='show(\"permintaanpengeluaranaks/cform/approval/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
              }
                
            }
            return $data;
        });

        $datatables->edit('i_status', function ($data) {
          $i_status = trim($data['i_status']);
          if($i_status == 9){
            return '<span class="label label-danger label-rouded">Batal</span>';
          }else {
            return '<span class="label label-'.$data['label'].' label-rouded">'.$data['namastatus'].'</span>';
          }
        });

        $datatables->edit('d_pp', function ($data) {
          $d_pp = $data['d_pp'];
          if($d_pp == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_pp) );
          }
        });
        $datatables->hide('i_kode_master');
        $datatables->hide('namastatus');
        $datatables->hide('i_menu');
        $datatables->hide('id');
        $datatables->hide('tujuan_keluar');
        $datatables->hide('epic');
        $datatables->hide('pic_eks');
        $datatables->hide('label');

        return $datatables->generate();
	}

   public function baca_header($i_permintaan, $gudang, $tujuankeluar){
      return $this->db->query("
                              select
                                 b.i_permintaan,
                                 to_char(b.d_pp, 'dd-mm-yyyy') as d_pp,
                                 b.i_kode_master,
                                 g.e_nama_master,
                                 c.e_nama_jenis,
                                 b.tujuan_keluar,
                                 b.partner,
                                 b.pic_eks,
                                 b.e_remark,
                                 b.i_status,
                                 s.e_status,
                                 b.jenis_pengeluaran,
                                 b.pic ,
                                 x.partner_name,
                                 to_char(b.d_back, 'dd-mm-yyyy') as d_back,
                                 z.epic
                              from
                                  tm_permintaanpengeluaranaks b 
                                  inner join
                                     tr_master_gudang g 
                                     on (b.i_kode_master = g.i_kode_master) 
                                  inner join
                                     tr_jenis_pengeluaran c 
                                     on (b.jenis_pengeluaran = c.i_jenis) 
                                  left join
                                     (
                                        select
                                           'Supplier' as id,
                                           'Supplier' as partner_name
                                        from
                                           tr_supplier 
                                        union all
                                        select
                                           i_departement as id,
                                           e_departement_name as partner_name
                                        from
                                           public.tr_departement  
                                        union all
                                        select 
                                          'Customer' as id,
                                          'Customer' as partner_name
                                        from
                                          tr_customer
                                     )
                                     as x 
                                     on (b.partner = x.id) 
                                  left join
                                      (
                                        select 
                                          i_karyawan as ipic,
                                          e_nama_karyawan as epic
                                        from
                                          tm_karyawan
                                        union all
                                        select 
                                          i_supplier as ipic,
                                          e_supplier_name as epic
                                        from 
                                          tr_supplier
                                        union all
                                        select
                                          i_customer as ipic,
                                          e_customer_name as ipic
                                        from
                                          tr_customer
                                      ) as z
                                      on (b.pic = z.ipic)
                                  inner join
                                     tm_status_dokumen s 
                                     on (b.i_status = s.i_status) 
                              where
                                 b.i_permintaan = '$i_permintaan' 
                                 and b.i_kode_master = '$gudang'
                                    
                              ", false); 
    }

    public function baca_detail($i_permintaan,$gudang){
      return $this->db->query("select sj.*, m.e_material_name, s.e_satuan from tm_permintaanpengeluaranaks_detail sj
            inner join tr_material m on (sj.i_material = m.i_material)
            inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
            where i_permintaan = '$i_permintaan' and sj.i_kode_master = '$gudang' ", false);
    }

    public function getpermintaan_detail($ipermintaan, $gudang){
      return $this->db->query("
           select sj.*, m.e_material_name, s.e_satuan, m2.e_material_name as e_material_name2, s2.e_satuan as e_satuan2
          from tm_permintaanpengeluaranaks_detail sj
          left join tr_material m on (sj.i_material = m.i_material)
          left join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
          left join tr_material m2 on (sj.i_material2 = m2.i_material)
          left join tr_satuan s2 on (s2.i_satuan_code = m2.i_satuan_code)
          where i_permintaan = '$ipermintaan' and sj.i_kode_master = '$gudang'
          order by sj.i_material
        ", false);
    }

    public function getpicIN($ikodemaster){
      return $this->db->query("
          select i_departement as i_sub_bagian, e_departement_name as e_sub_bagian from public.tr_departement where i_departement != '$ikodemaster' AND i_departement != '10'
          order by i_departement asc", 
      FALSE); 
    }

    public function getppic($ipartner){
      if($ipartner == 'Customer'){
        return $this->db->query("
                                  SELECT
                                    i_customer as i_ppic,
                                    e_customer_name as e_ppic
                                  FROM
                                    tr_customer
                                  WHERE
                                    f_customer_aktif = 't'
                                  ", FALSE);
      }else if($ipartner == 'Supplier'){
        return $this->db->query("
                                  SELECT
                                    i_supplier as i_ppic,
                                    e_supplier_name as e_ppic
                                  FROM
                                    tr_supplier
                                  WHERE
                                    f_status_supplier = 't'
                                  ", FALSE);
      }else if($ipartner == "Karyawan"){
        return $this->db->query("
                                  SELECT
                                    i_karyawan as i_ppic,
                                    e_nama_karyawan as e_ppic
                                  FROM
                                    tm_karyawan
                                  WHERE
                                    f_aktif = 't'
                                  ", FALSE);
      }
    }

    public function updateheader($nobonkeluar, $istore, $dbonk, $jenispengeluaran, $tujuankeluar, $pic, $dept, $remark, $epic, $dback){
      $dupdate = date("Y-m-d");
        $data = array(
            'd_pp'              => $dbonk,
            'jenis_pengeluaran' => $jenispengeluaran,
            'tujuan_keluar'     => $tujuankeluar,
            'pic'               => $pic,
            'partner'           => $dept,
            'e_remark'          => $remark,
            'pic_eks'           => $epic,
            'd_update'          => $dupdate,
            'd_back'            => $dback      
        );
        $this->db->where('i_permintaan', $nobonkeluar);
        $this->db->where('i_kode_master', $istore);
        $this->db->update('tm_permintaanpengeluaranaks', $data);
    }

  public function deletedetail($nobonkeluar, $istore){
        $this->db->query("DELETE FROM tm_permintaanpengeluaranaks_detail WHERE i_permintaan='$nobonkeluar' and i_kode_master='$istore' ");
  }

  public function cek_product($bonmkp,$gudang, $i_material){
      return $this->db->query("select i_material from tm_bonmkeluar_pinjamanbb_detail where i_bonmk = '$bonmkp' and i_kode_master = '$gudang' and i_material = '$i_material' ", false);
  }

  public function updatedetail($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $urutan){
        $this->db->query("UPDATE tm_bonmkeluar_pinjamanbb_detail SET n_qty = n_qty+$nquantity WHERE i_bonmk='$nobonkeluar' and i_kode_master='$istore' and i_material = '$imaterial' ");
  }

  public function cancel($bonmkp, $gudang){
        $this->db->set(
            array(
                'i_status'  => '9'
            )
        );
        $this->db->where('i_permintaan',$bonmkp);
        $this->db->where('i_kode_master',$gudang);
        return $this->db->update('tm_permintaanpengeluaranaks');
  }

  public function send($kode, $gudang){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranaks', $data);
    }

  public function change($kode, $gudang){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranaks', $data);
    }

  public function reject($kode, $gudang){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranaks', $data);
  }

  public function tarikdokumen($kode, $gudang){
      $data = array(
          'i_status'    => '7'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranaks', $data);
  }

  public function approve($kode, $gudang){
      $now = date("Y-m-d");
      $data = array(
          'i_status'   => '6',
          'd_approve' => $now
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranaks', $data);
  }

}

/* End of file Mmaster.php */
