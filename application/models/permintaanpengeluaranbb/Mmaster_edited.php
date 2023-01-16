<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacagudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_jenis','JNG0001');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
        // return $->get('tr_master_gudang')->result();
  }

  public function bacajeniskeluar(){
        $this->db->select('*');
        $this->db->from('tr_jenis_pengeluaran');
        $this->db->order_by('e_nama_jenis','ASC');
        return $this->db->get()->result();
        // return $->get('tr_master_gudang')->result();
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
        $cari = str_replace("'", "", $cari);
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
               a.i_kode_kelompok='KTB0002' AND
                b.i_kode_master = '$gudang'
                AND (UPPER(a.i_material) LIKE '%$cari%'
                OR UPPER(a.e_material_name) LIKE '%$cari%')
                order by a.i_material", 
        FALSE);
  }

  function runningnumbermemopengeluaran($yearmonth,$istore){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,2,2);
        
#      $store=$this->session->userdata('store');
      //select substr('SJKM-2003-000021',11,6) as max, substr('SJKM-2003-000021',6,2) as th, substr('SJKM-2003-000021',8,2) as bl 
      $this->db->select("max(substr(i_permintaan,10,6)) as max from tm_permintaanpengeluaranbb where substr(i_permintaan,5,2)='$th' and substr(i_permintaan,7,2)='$bl' and i_kode_master='$istore'", false);
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
        $nogj  ="PKB-".$th.$bl."-".$nogj;
        return $nogj;
      }else{
        $nogj  ="000001";
        $nogj  ="PKB-".$th.$bl."-".$nogj;
        return $nogj;
      }
    }

    public function insertheader($dbonk, $istore, $tujuankeluar, $pic, $dept, $remark, $nobonkeluar, $epic, $jenispengeluaran){
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
            'd_insert'         => $dentry
            
        );
        $this->db->insert('tm_permintaanpengeluaranbb', $data);
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
        $this->db->insert('tm_permintaanpengeluaranbb_detail', $data);
    }
  
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
          select b.i_permintaan, b.d_pp, b.i_kode_master, c.e_nama_jenis, 
          b.tujuan_keluar, x.partner, d.e_nama_karyawan, b.pic_eks, b.e_remark, b.i_status, s.e_status , '$i_menu' as i_menu  
          from tm_permintaanpengeluaranbb b 
          inner join tr_master_gudang g on (b.i_kode_master = g.i_kode_master)
          inner join tr_jenis_pengeluaran c on (b.jenis_pengeluaran = c.i_jenis)
          left join ( 
            select i_supplier as id, e_supplier_name as partner from tr_supplier
            union all 
            select i_sub_bagian as id, e_sub_bagian as partner from tm_sub_bagian
          ) as x on (b.partner = x.id)
          left join tm_karyawan d on (b.pic = d.i_karyawan)
          inner join tm_status_dokumen s on (b.i_status = s.i_status)
          order by b.i_permintaan
        ");

      //   $datatables->edit('f_cancel', function ($data) {
      //     $f_cancel = trim($data['f_cancel']);
      //     if($f_cancel == 't'){
      //        return  "Batal";
      //     }else {
      //       return "Aktif";
      //     }
      // });
        
        $datatables->add('action', function ($data) {
            $i_permintaan         = trim($data['i_permintaan']);
            $gudang        = trim($data['i_kode_master']);
            $tujuan_keluar = trim($data['tujuan_keluar']);
            $status = trim($data['i_status']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
               $data .= "<a href=\"#\" onclick='show(\"permintaanpengeluaranbb/cform/view/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
              if ($status == '1' || $status == '3' || $status == '7') { //tambah validasi jika sudah ada bonmasuk tidak bisa cancel/edit
                $data .= "<a href=\"#\" onclick='show(\"permintaanpengeluaranbb/cform/edit/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                $data .= "<a href=\"#\" onclick='cancel(\"$i_permintaan\",\"$gudang\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
              }

              if ($status == '2') { //tambah validasi jika sudah ada bonmasuk tidak bisa cancel/edit
                $data .= "<a href=\"#\" onclick='show(\"permintaanpengeluaranbb/cform/approval/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
              }
                
            }
            return $data;
        });
        $datatables->hide('i_kode_master');
        $datatables->hide('i_status');
        $datatables->hide('i_menu');
        $datatables->hide('tujuan_keluar');
        $datatables->hide('e_nama_karyawan');
        $datatables->hide('pic_eks');

        return $datatables->generate();
	}

   public function baca_header($i_permintaan,$gudang, $tujuankeluar){
     //if($tujuankeluar == 'internal'){
      return $this->db->query("select b.i_permintaan,  to_char(b.d_pp,'dd-mm-yyyy') as d_pp, b.i_kode_master, c.e_nama_jenis, 
          b.tujuan_keluar, b.partner, d.e_nama_karyawan, b.pic_eks, b.e_remark, b.i_status, s.e_status, b.jenis_pengeluaran, b.pic
          from tm_permintaanpengeluaranbb b 
          inner join tr_master_gudang g on (b.i_kode_master = g.i_kode_master)
          inner join tr_jenis_pengeluaran c on (b.jenis_pengeluaran = c.i_jenis)
          left join ( 
            select i_supplier as id, e_supplier_name as partner from tr_supplier
            union all 
            select i_sub_bagian as id, e_sub_bagian as partner from tm_sub_bagian
          ) as x on (b.partner = x.id)
          left join tm_karyawan d on (b.pic = d.i_karyawan)
          inner join tm_status_dokumen s on (b.i_status = s.i_status)
          where b.i_permintaan = '$i_permintaan' and b.i_kode_master = '$gudang'", false); 
         
      // }else if($tujuankeluar == 'external'){
      //    return $this->db->query("select a.i_bonmk, to_char(a.d_bonmk, 'dd-mm-yyyy') as d_bonmk, a.i_memo, to_char(a.d_memo, 'dd-mm-yyyy') as d_memo, a.i_kode_master, a.tujuan_keluar, a.pic, b.e_nama_karyawan, a.department, c.e_supplier_name as dept, a.e_remark, a.f_cancel, a.pic_eks 
      //       from duta_prod.tm_bonmkeluar_pinjamanbpplastik a
      //       join duta_prod.tm_karyawan b on a.pic = b.i_karyawan
      //       join duta_prod.tr_supplier c on a.department = c.i_supplier
      //       where i_bonmk = '$bonmkp' and i_kode_master = '$gudang'", false);
      // }
    }

    public function baca_detail($i_permintaan,$gudang){
      return $this->db->query("select sj.*, m.e_material_name, s.e_satuan from tm_permintaanpengeluaranbb_detail sj
            inner join tr_material m on (sj.i_material = m.i_material)
            inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
            where i_permintaan = '$i_permintaan' and sj.i_kode_master = '$gudang' ", false);
    }

    public function getpermintaan_detail($ipermintaan, $gudang){
      return $this->db->query("
           select sj.*, m.e_material_name, s.e_satuan, m2.e_material_name as e_material_name2, s2.e_satuan as e_satuan2
          from tm_permintaanpengeluaranbb_detail sj
          left join tr_material m on (sj.i_material = m.i_material)
          left join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
          left join tr_material m2 on (sj.i_material2 = m2.i_material)
          left join tr_satuan s2 on (s2.i_satuan_code = m2.i_satuan_code)
          where i_permintaan = '$ipermintaan' and sj.i_kode_master = '$gudang'
          order by sj.i_material
        ", false);
    }

    public function departemen($cari,$itujuan)
    {
        $dep = $this->session->userdata('i_departement');
        $cari = str_replace("'", "", $cari);
        if (trim($itujuan)=='internal') {
            return $this->db->query("
                SELECT
                    *
                FROM
                    (
                    SELECT
                        i_departement AS id, e_departement_name AS name
                    FROM
                        public.tr_departement where i_departement <> '$dep'
                UNION ALL
                    SELECT
                        i_karyawan AS id, e_nama_karyawan AS name
                    FROM
                        tm_karyawan) AS x
                WHERE
                    (UPPER(name) LIKE '%$cari%')
                ORDER BY name
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    i_supplier AS id,
                    e_supplier_name AS name
                FROM
                    tr_supplier
                WHERE
                    (UPPER(e_supplier_name) LIKE '%$cari%')
                ORDER BY name
            ", FALSE);
        }
    }

    public function getpicIN($ikodemaster){
      // return $this->db->query("
      //     select 'dept' as x, i_sub_bagian as i_sub_bagian, e_sub_bagian as e_sub_bagian from tm_sub_bagian
      //     union all
      //     select 'karyawan' as x, i_karyawan as i_sub_bagian, e_nama_karyawan as e_sub_bagian from tm_karyawan
      //     order by x asc", 
      // FALSE); 
      return $this->db->query("
          select i_departement as i_sub_bagian, e_departement_name as e_sub_bagian from public.tr_departement where i_departement != '$ikodemaster' 
          order by i_departement asc", 
      FALSE); 
    }

    public function getpicEK(){
        return $this->db->query("
          select 'supllier' as x, i_supplier as i_supplier, e_supplier_name as e_supplier_name from tr_supplier
          union all
          select 'zcustomer' as x, i_customer as i_supplier, e_customer_name as e_supplier_name from tr_customer
          order by e_supplier_name asc", 
      FALSE);   
    }

    public function updateheader($dbonk, $istore, $tujuankeluar, $pic, $dept, $remark, $nobonkeluar, $epic, $jenispengeluaran){
      $dupdate = date("d F Y");
        $data = array(
            'd_pp'       => $dbonk,
            'jenis_pengeluaran' => $jenispengeluaran,
            'tujuan_keluar' => $tujuankeluar,
            'pic'           => $pic,
            'partner'    => $dept,
            'e_remark'      => $remark,
            'pic_eks'       => $epic,
            'd_update'      => $dupdate          
        );
        $this->db->where('i_permintaan', $nobonkeluar);
        $this->db->where('i_kode_master', $istore);
        $this->db->update('tm_permintaanpengeluaranbb', $data);
    }

  public function deletedetail($nobonkeluar, $istore){
        $this->db->query("DELETE FROM tm_permintaanpengeluaranbb_detail WHERE i_permintaan='$nobonkeluar' and i_kode_master='$istore' ");
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
        return $this->db->update('tm_permintaanpengeluaranbb');
  }

  public function send($kode, $gudang){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranbb', $data);
    }

  public function change($kode, $gudang){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranbb', $data);
    }

  public function reject($kode, $gudang){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranbb', $data);
  }

  public function tarikdokumen($kode, $gudang){
      $data = array(
          'i_status'    => '7'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranbb', $data);
  }

  public function approve($kode, $gudang){
      $now = date("Y-m-d");
      $data = array(
          'i_status'   => '5',
          'd_approve' => $now
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaranbb', $data);
  }

}

/* End of file Mmaster.php */
