<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacagudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_jenis', 'JNG0001');
        $this->db->where('i_kode_master', 'GD10002');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
  }

  public function sjkm($cari, $gudang){
        //$cari = strtoupper($this->input->get('q'));
        //$cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_sj, to_char(d_sj, 'dd-mm-yyyy') AS d_sj 
            FROM
                tm_sjkeluarmakloonaksesoris
            WHERE
                i_kode_master = '$gudang' and f_cancel = 'f'
                AND (UPPER(i_sj) LIKE '%$cari%'
                OR UPPER(to_char(d_sj, 'dd-mm-yyyy')) LIKE '%$cari%')", 
        FALSE);
  }

  public function getsjkm($sj,$gudang){
      return $this->db->query("
          select sj.i_jenis_keluar, jk.e_jenis_keluar, sj.i_supplier, s.e_supplier_name  from tm_sjkeluarmakloonaksesoris sj
          inner join tr_jenis_keluarbb jk on (sj.i_jenis_keluar = jk.i_jenis)
          inner join tr_supplier s on (sj.i_supplier = s.i_supplier)
          where sj.i_sj='$sj' and sj.i_kode_master = '$gudang'
        ", false);
  }

  public function getsjkm_detail($sj,$gudang){
      return $this->db->query("
            select sj.*, m.e_material_name, s.e_satuan 
            from tm_sjkeluarmakloonaksesoris_detail sj
            inner join tr_material m on (sj.i_material = m.i_material)
            inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
            where i_sj = '$sj' and sj.i_kode_master = '$gudang' 
        ", false);
  }

  public function baca_header($sj,$gudang){
      return $this->db->query("    
        select sj.i_sj, to_char(sj.d_sj, 'dd-mm-yyyy') as d_sj, sj.i_sj_reff, sjkm.d_sj as d_sjkm, sj.i_supplier, s.e_supplier_name, sj.i_kode_master , g.e_nama_master, sj.e_remark  from tm_sjmasukmakloonaksesoris sj
        inner join tr_master_gudang g on (sj.i_kode_master = g.i_kode_master)
        inner join tr_supplier s on (sj.i_supplier = s.i_supplier)
        inner join tm_sjkeluarmakloonaksesoris sjkm on (sjkm.i_kode_master = sj.i_kode_master and sjkm.i_sj = sj.i_sj_reff)
          where sj.i_sj='$sj' and sj.i_kode_master = '$gudang'
        ", false);
  }

  public function getsjmm_detail($isjkm, $isjmm, $gudang){
      return $this->db->query("
           select * from (
            select sj.i_sj, sj.i_kode_master, sj.i_material, m.e_material_name, sj.n_qty, sj.i_satuan, s.e_satuan 
                  from tm_sjkeluarmakloonaksesoris_detail sj
                  inner join tr_material m on (sj.i_material = m.i_material)
                  inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
                  where i_sj = '$isjkm' and sj.i_kode_master = '$gudang' 
            ) as x left join (
              select sjm.i_sj_reff, sjm.i_material_reff, sjm.i_kode_master, sjm.i_material as i_2material , m.e_material_name as e_2material_name, sjm.n_qty as n_2qty,
              sjm.i_satuan as i_2satuan, s.e_satuan as e_2satuan, sjm.e_remark from tm_sjmasukmakloonaksesoris_detail sjm 
              inner join tr_material m on (sjm.i_material = m.i_material)
              inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
              where sjm.i_sj = '$isjmm' and sjm.i_kode_master = '$gudang' 
            ) as y  on (x.i_sj = y.i_sj_reff and x.i_kode_master = y.i_kode_master and x.i_material = y.i_material_reff)
        ", false);
  }

  function runningnumbermasukm($th,$bl,$ikodemaster)
    {
#      $store=$this->session->userdata('store');
      //select substr('SJKM-2003-000021',11,6) as max, substr('SJKM-2003-000021',6,2) as th, substr('SJKM-2003-000021',8,2) as bl 
      $this->db->select(" max(substr(i_sj,11,6)) as max from tm_sjmasukmakloonaksesoris where substr(i_sj,6,2)='$th' and substr(i_sj,8,2)='$bl' and i_kode_master='$ikodemaster'", false);
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
        $nogj  ="SJMM-".$th.$bl."-".$nogj;
        return $nogj;
      }else{
        $nogj  ="000001";
        $nogj  ="SJMM-".$th.$bl."-".$nogj;
        return $nogj;
      }
    }

  public function product($cari, $gudang){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.*, b.e_satuan
            FROM
                tr_material a
            INNER JOIN 
                tr_satuan b on (a.i_satuan_code=b.i_satuan_code)
            INNER JOIN 
                tm_kelompok_barang c on (a.i_kode_kelompok=c.i_kode_kelompok)
            WHERE
                c.i_kode_master = '$gudang'
                AND (a.i_material LIKE '%$cari%' OR a.e_material_name LIKE '%$cari%')", 
        FALSE);
  }

  public function productsj($cari, $gudang, $sjkm){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.e_material_name, a.i_material, c.n_qty, b.e_satuan
            FROM
                tr_material a
            INNER JOIN 
                tr_satuan b on (a.i_satuan_code=b.i_satuan_code)
            INNER JOIN 
                tm_sjkeluarmakloonaksesoris_detail c on (a.i_material = c.i_material)
            WHERE 
                c.i_kode_master = '$gudang' and c.i_sj = '$sjkm'
                AND (a.i_material LIKE '%$cari%' OR a.e_material_name LIKE '%$cari%')", 
        FALSE);
  }

  function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    //$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("
            select sj.i_sj, sj.d_sj, s.e_supplier_name,
                  sj.e_remark, sj.f_cancel, '$i_menu' as i_menu, sj.i_kode_master 
            from tm_sjmasukmakloonaksesoris sj 
            inner join tr_master_gudang g on (sj.i_kode_master = g.i_kode_master)
            inner join tr_supplier s on (sj.i_supplier = s.i_supplier)
            order by sj.i_sj desc
        ");

        $datatables->edit('f_cancel', function ($data) {
          $f_cancel = trim($data['f_cancel']);
          if($f_cancel == 't'){
             return  "Batal";
          }else {
            return "Aktif";
          }
      });
        
        $datatables->add('action', function ($data) {
            $sj       = trim($data['i_sj']);
            $gudang   = trim($data['i_kode_master']);
            $f_cancel = trim($data['f_cancel']);
            $i_menu   = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"sjmasukmakloonaksesoris/cform/view/$sj/$gudang\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)&& $f_cancel == 'f'){
                $data .= "<a href=\"#\" onclick='show(\"sjmasukmakloonaksesoris/cform/edit/$sj/$gudang\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }if(check_role($i_menu, 3) && $f_cancel == 'f'){
                $data .= "<a href=\"#\" onclick='cancel(\"$sj\",\"$gudang\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
          //   if(check_role($i_menu, 1)){
          //     $data .= "<a href=\"#\" onclick='show(\"pembelianpp/cform/approve/$i_pp/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
          // }
      return $data;
        });
        $datatables->hide('i_kode_master');
        $datatables->hide('i_menu');

        return $datatables->generate();
  }

  public function insertheader($nosjmasuk, $dsjk,$nosjkeluar, $istore,$supplier, $remark){
        $dentry = date("Y-m-d");
        $data = array(
            'i_sj'          => $nosjmasuk,
            'd_sj'          => $dsjk,
            'i_sj_reff'     => $nosjkeluar,
            'i_kode_master' => $istore,
            'i_supplier'    => $supplier,
            'e_remark'      => $remark,
            'd_insert'      => $dentry   
        );
        $this->db->insert('tm_sjmasukmakloonaksesoris', $data);
    }

    public function insertdetail($nosjmasuk,$istore,$nosjkeluar, $imaterial_reff, $imaterial, $nquantity,$isatuan, $edesc, $i) {               
        $data = array(        

            'i_sj'            => $nosjmasuk,
            'i_kode_master'   => $istore,
            'i_sj_reff'       => $nosjkeluar,
            'i_material_reff' => $imaterial_reff,
            'i_material'      => $imaterial,
            'n_qty'           => $nquantity,
            'i_satuan'        => $isatuan,
            'e_remark'        => $edesc,
            'i_no_item'       => $i      
        );
        $this->db->insert('tm_sjmasukmakloonaksesoris_detail', $data);
    }

    public function updateheader($nosjkeluar, $dsjk,$nosjmasuk, $istore,$supplier, $remark){
      $dupdate = date("Y-m-d");
        $data = array(
            'd_sj'          => $dsjk,
            'i_sj_reff'     => $nosjkeluar,
            'i_supplier'    => $supplier,
            'e_remark'      => $remark,
            'd_update'      => $dupdate
            
        );
        $this->db->where('i_sj', $nosjmasuk);
        $this->db->where('i_kode_master', $istore);
        $this->db->update('tm_sjmasukmakloonaksesoris', $data);
    }

    public function deletedetail($nosjkeluar, $istore, $nosjmasuk){
        $this->db->query("DELETE FROM tm_sjmasukmakloonaksesoris_detail WHERE i_sj='$nosjmasuk' and i_kode_master='$istore' and i_sj_reff = '$nosjkeluar' ");
    }

  public function bacajenis(){
    return $this->db->order_by('i_jenis','ASC')->get('tr_jenis_keluarbb')->result();
  }

  public function bacatujuan(){
      $this->db->select('*');
      $this->db->from('tr_supplier');
      //$this->db->where('i_supplier_group', '02');
      return $this->db->get()->result();
  }

   public function cancel($sj, $gudang){
        $this->db->set(
            array(
                'f_cancel'  => 't'
            )
        );
        $this->db->where('i_sj',$sj);
        $this->db->where('i_kode_master',$gudang);
        return $this->db->update('tm_sjmasukmakloonaksesoris');
    }
}
/* End of file Mmaster.php */