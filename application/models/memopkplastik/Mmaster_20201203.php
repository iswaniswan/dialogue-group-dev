<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacagudang($ikodejenis){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_jenis','JNG0006');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
        // return $->get('tr_master_gudang')->result();
  }

  function bacagudang1($ilevel, $idepart, $lokasi, $username, $idcompany){
        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name, d.i_bagian
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level
                                  inner join public.tm_user d on a.id_company = d.id_company and a.username = d.username
                                  WHERE a.username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany' ", FALSE);
      
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
               a.i_kode_kelompok='KTB0005' AND
                b.i_kode_master = '$gudang'
                AND (UPPER(a.i_material) LIKE '%$cari%'
                OR UPPER(a.e_material_name) LIKE '%$cari%')
                order by a.i_material", 
        FALSE);
  }

  function runningnumbermemopengeluaran($yearmonth,$ibagian){
      $bl = substr($yearmonth,4,2);
      $th = substr($yearmonth,0,4);
      $thn = substr($yearmonth,2,2);
      $area= trim($ibagian);
      $b   = strlen($area);
      if($b <= 1){
            settype($b,"string");
            $b="0".$b;
            $area = $b;
      }else{
            $area;
      }
      $asal= substr($yearmonth,0,4);
      $yearmonth= substr($yearmonth,0,4);
      $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='PKBP'
                          and i_area='$area'
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
                          where i_modul='PKBP'
                          and e_periode='$asal' 
                          and i_area='$area'
                          and substring(e_periode,1,4)='$th'", false);
        settype($nopp,"string");
        $a=strlen($nopp);

        //u/ 0
        while($a<5){
          $nopp="0".$nopp;
          $a=strlen($nopp);
        }
          $nopp  ="PKBP"."-".$area."-".$thn.$bl."-".$nopp;
        return $nopp;
      }else{
        $nopp  ="00001";
        $nopp  ="PKBP"."-".$area."-".$thn.$bl."-".$nopp;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('PKBP','$area','$asal',1)");
        return $nopp;
      }
  }

    public function insertheader($dbonk, $istore, $tujuankeluar, $pic, $dept, $remark, $nobonkeluar, $epic, $jenispengeluaran, $memo, $dmemo){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
            'i_permintaan'     => $nobonkeluar,
            'd_pp'             => $dbonk,
            'i_kode_master'    => $istore,
            'jenis_pengeluaran'=> $jenispengeluaran,
            'tujuan_keluar'    => $tujuankeluar,
            'pic'              => $pic,
            'partner'          => $dept,
            'e_remark'         => $remark,
            'pic_eks'          => $epic,
            'i_status'         => '1',
            'memo'             => $memo,
            'd_memo'           => $dmemo,
            'd_insert'         => $dentry
            
        );
        $this->db->insert('tm_permintaanpengeluaran_plastik', $data);
    }

    public function insertdetail($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $no, $imaterial2, $nquantity2,$isatuan2){               
        $data = array(        

            'i_permintaan'    => $nobonkeluar,
            'i_kode_master'   => $istore,
            'i_material'      => $imaterial,
            'n_qty'           => $nquantity,
            'n_qty_sisa'      => $nquantity,
            'i_satuan_code'   => $isatuan,
            'i_material2'     => $imaterial2,
            'n_qty2'          => $nquantity2,
            'n_qty_sisa2'     => $nquantity2,
            'i_satuan_code2'  => $isatuan2,
            'e_remark'        => $edesc,
            'i_no_item'       => $no
            
        );
        $this->db->insert('tm_permintaanpengeluaran_plastik_detail', $data);
    }

    public function send($kode, $gudang){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaran_plastik', $data);
    }


  function data($i_menu,$folder,$dfrom,$dto){
    if ($dfrom!='' && $dto!='') {
        $dfrom = date('Y-m-d', strtotime($dfrom));
        $dto   = date('Y-m-d', strtotime($dto));
        $where = "WHERE b.d_pp BETWEEN '$dfrom' AND '$dto'";
    }else{
        $where = "";
    }

    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
          select b.i_permintaan, b.d_pp, b.i_kode_master, c.e_nama_jenis, 
          b.tujuan_keluar, x.partner, d.e_nama_karyawan, b.pic_eks, b.e_remark, b.i_status, s.e_status , s.label_color as label, '$i_menu' as i_menu,
          '$folder' AS folder 
          from tm_permintaanpengeluaran_plastik b 
          inner join tr_master_gudang g on (b.i_kode_master = g.i_kode_master)
          inner join tr_jenis_pengeluaran c on (b.jenis_pengeluaran = c.i_jenis)
          left join ( 
            select i_supplier as id, e_supplier_name as partner from tr_supplier
            union all 
            select i_sub_bagian as id, e_sub_bagian as partner from tm_sub_bagian
          ) as x on (b.partner = x.id)
          left join tm_karyawan d on (b.pic = d.i_karyawan)
          inner join tm_status_dokumen s on (b.i_status = s.i_status)
          $where
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
        $datatables->edit('e_status', function ($data) {
              return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $i_permintaan         = trim($data['i_permintaan']);
            $gudang        = trim($data['i_kode_master']);
            $tujuan_keluar = trim($data['tujuan_keluar']);
            $status = trim($data['i_status']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
               $data .= "<a href=\"#\" onclick='show(\"memopkplastik/cform/view/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
              if ($status == '1' || $status == '3' || $status == '7') { //tambah validasi jika sudah ada bonmasuk tidak bisa cancel/edit
                $data .= "<a href=\"#\" onclick='show(\"memopkplastik/cform/edit/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                $data .= "<a href=\"#\" onclick='cancel(\"$i_permintaan\",\"$gudang\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
              }

              if ($status == '2') { //tambah validasi jika sudah ada bonmasuk tidak bisa cancel/edit
                $data .= "<a href=\"#\" onclick='show(\"memopkplastik/cform/approval/$i_permintaan/$gudang/$tujuan_keluar/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
              }
             }   
            
            return $data;
              
        });
        $datatables->hide('i_kode_master');
        $datatables->hide('folder');
        $datatables->hide('label');
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
          from tm_permintaanpengeluaran_plastik b 
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
    }


    public function baca_detail($i_permintaan,$gudang){
      return $this->db->query("select sj.*, m.e_material_name, s.e_satuan from tm_permintaanpengeluaran_plastik_detail sj
            inner join tr_material m on (sj.i_material = m.i_material)
            inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
            where i_permintaan = '$i_permintaan' and sj.i_kode_master = '$gudang' ", false);
      
    }

    public function getpermintaan_detail($ipermintaan, $gudang){
      return $this->db->query("
           select sj.*, m.e_material_name, s.e_satuan, m2.e_material_name as e_material_name2, s2.e_satuan as e_satuan2
          from tm_permintaanpengeluaran_plastik_detail sj
          left join tr_material m on (sj.i_material = m.i_material)
          left join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
          left join tr_material m2 on (sj.i_material2 = m2.i_material)
          left join tr_satuan s2 on (s2.i_satuan_code = m2.i_satuan_code)
          where i_permintaan = '$ipermintaan' and sj.i_kode_master = '$gudang'
          order by sj.i_material
        ", false);
    }

    public function getpicIN(){
        $this->db->select('i_sub_bagian, e_sub_bagian');
        $this->db->from('tm_sub_bagian');
        return $this->db->get(); 
    }

    public function getpicEK(){
        return $this->db->query("
          select i_supplier as i_supplier, e_supplier_name as e_supplier_name from tr_supplier
          group by i_supplier, e_supplier_name order by e_supplier_name asc", 
      FALSE);   
    }

    public function updateheader($dbonk, $istore, $tujuankeluar, $pic, $dept, $remark, $nobonkeluar, $epic, $jenispengeluaran){
      $dupdate = date("d F Y");
        $data = array(
            'd_pp'              => $dbonk,
            'jenis_pengeluaran' => $jenispengeluaran,
            'tujuan_keluar'     => $tujuankeluar,
            'pic'               => $pic,
            'partner'           => $dept,
            'e_remark'          => $remark,
            'pic_eks'           => $epic,
            'd_update'          => $dupdate          
        );
        $this->db->where('i_permintaan', $nobonkeluar);
        $this->db->where('i_kode_master', $istore);
        $this->db->update('tm_permintaanpengeluaran_plastik', $data);
    }

  public function deletedetail($nobonkeluar, $istore){
        $this->db->query("DELETE FROM tm_permintaanpengeluaran_plastik_detail WHERE i_permintaan='$nobonkeluar' and i_kode_master='$istore' ");
  }

  public function cek_product($bonmkp,$gudang, $i_material){
      return $this->db->query("select i_material from tm_bonmkeluar_pinjaman_plastik_detail where i_bonmk = '$bonmkp' and i_kode_master = '$gudang' and i_material = '$i_material' ", false);
  }

  public function updatedetail($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $urutan){
        $this->db->query("UPDATE tm_bonmkeluar_pinjaman_plastik_detail SET n_qty = n_qty+$nquantity WHERE i_bonmk='$nobonkeluar' and i_kode_master='$istore' and i_material = '$imaterial' ");
  }

  public function cancel($bonmkp, $gudang){
        $this->db->set(
            array(
                'i_status'  => '9'
            )
        );
        $this->db->where('i_permintaan',$bonmkp);
        $this->db->where('i_kode_master',$gudang);
        return $this->db->update('tm_permintaanpengeluaran_plastik');
  }

  public function change($kode, $gudang){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaran_plastik', $data);
    }

  public function reject($kode, $gudang){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaran_plastik', $data);
  }

  public function tarikdokumen($kode, $gudang){
      $data = array(
          'i_status'    => '7'
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaran_plastik', $data);
  }

  public function approve($kode, $gudang){
      $now = date("Y-m-d");
      $data = array(
          'i_status'   => '6',
          'd_approve' => $now
      );

      $this->db->where('i_permintaan', $kode);
      $this->db->where('i_kode_master', $gudang);
      $this->db->update('tm_permintaanpengeluaran_plastik', $data);
  }

}

/* End of file Mmaster.php */
