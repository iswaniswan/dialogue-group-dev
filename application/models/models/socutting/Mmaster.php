<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function cek_data($iperiode){
        $this->db->select(" b.*, c.e_material_name, '0' as so 
                          from tm_bonkeluar_cutting a
                          join tm_bonkeluar_cutting_item b on a.i_bonk = b.i_bonk
                          join tr_material c on b.i_material = c.i_material
                          where a.i_periode = '$iperiode'" ,FALSE);
    return $this->db->get();
  }

  function runningnumber($yearmonth){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
// var_dump($bl);
//var_dump($area);
        //$asal=$yearmonth;
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);
        //$yearmonth=substr($yearmonth,2,2).substr($yearmonth,4,2);
// var_dump($yearmonth);
// die;
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='SO'
                            and i_area='CT'
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
                            and i_area='CT'
                            and substring(e_periode,1,4)='$th'", false);
          settype($nopp,"string");
          $a=strlen($nopp);
  
          //u/ 0
          while($a<7){
            $nopp="0".$nopp;
            $a=strlen($nopp);
          }
            $nopp  ="SO-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="0000001";
          $nopp  ="SO-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SO','CT','$asal',1)");
          return $nopp;
        }
  }

  public function insertheader($istokopname, $dateso, $yearmonth){
        $dentry = date("d F Y");
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);

        $data = array(
            'i_stok_opname_cutting'   => $istokopname,
            'd_so'                    => $dateso,
            'd_bulan'                 => $bl,
            'd_tahun'                 => $th,
            'i_periode'               => $th.$bl,
            'd_entry'                 => $dentry,
        );
    $this->db->insert('tt_stok_opname_cutting', $data);
  }

  public function insertdetail($istokopname, $imaterial, $stokopname, $nitemno){
      $data = array(
          'i_stok_opname_cutting'     => $istokopname,
          'i_kode_brg'                => $imaterial,
          'v_jum_stok_opname'         => $stokopname,
          'n_item_no'                 => $nitemno, 
      );
  $this->db->insert('tt_stok_opname_cutting_detail', $data);
  }

  public function cek_dataheader($iperiodebl, $iperiodeth){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_cutting_detail a
                      join tt_stok_opname_cutting b on a.i_stok_opname_cutting = b.i_stok_opname_cutting
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where b.f_status_approve = 'f' 
                      and b.d_bulan ='$iperiodebl' and b.d_tahun='$iperiodeth'", false);
    return $this->db->get();
  }

  public function cek_datadetail($iperiodebl, $iperiodeth){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_cutting_detail a
                      join tt_stok_opname_cutting b on a.i_stok_opname_cutting = b.i_stok_opname_cutting
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where b.f_status_approve = 'f' 
                      and b.d_bulan ='$iperiodebl' and b.d_tahun='$iperiodeth'", false);
    return $this->db->get();
  }

  public function updateheader($ikodeso, $dbulan, $dtahun){
    $dupdate = date("d F Y");
    $data=array(
        'f_status_approve' => 't',
        'd_update'         => $dupdate,

    );
    $this->db->where('i_stok_opname_cutting', $ikodeso);
    $this->db->where('d_bulan', $dbulan);
    $this->db->where('d_tahun', $dtahun);
    $this->db->update('tt_stok_opname_cutting', $data);
  } 
}
/* End of file Mmaster.php */