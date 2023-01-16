<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  public function bacagudang($ikodemaster){
        $this->db->select('e_nama_master');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master',$ikodemaster);
        return $this->db->get();
  }

  public function getbarang($ikodemaster){
      $this->db->select("a.i_material, a.e_material_name
                        from tr_material a
                        join tm_kelompok_barang b on a.i_kode_kelompok = b.i_kode_kelompok
                        where b.i_kode_master = '$ikodemaster'
                        and a.i_kode_kelompok='KTB0002'
                        order by a.i_material", false);
        return $this->db->get();
  }

  function cek_datadet($dso, $ikodebarang, $ikodemaster, $kelompokbrg){
       $interval = new DateInterval('P1M');

        //
        $dateTime = new DateTime($dso);
        $bulan = $dateTime->format('m');
        $tahun = $dateTime->format('Y');
        $bulanlalu = $dateTime->sub($interval)->format('m');
        $tahunlalu = $dateTime->sub($interval)->format('Y');

        $datefrom = "01-".$bulan."-".$tahun;
        $dateto = new DateTime($dso); 
        $dateto = $dateto->format('t-m-Y' );

    //var_dump($ikodebarang);
    $where = '';
        if($ikodebarang != 'BRG'){
          $where .= "AND a.kode = '$ikodebarang'";
        }

    //if ($iperiode<='202001') {

        $this->db->select("
                            kode, barang, kodegudang, gudang, satuan, saldoawal, bonmasuk1, bonmasuklain, bonkeluar, bonkeluarlain, saldoakhir, so, selisih 
                            FROM
                               f_saldoso('$bulanlalu', '$tahunlalu', '$datefrom', '$dateto', '$bulan', '$tahun') a 
                               JOIN
                                  tr_material b 
                                  on a.kode = b.i_material 
                                  WHERE
                                     kodegudang = '$ikodemaster' 
                                     and b.i_kode_kelompok = '$kelompokbrg'
                                  $where
                                  order by kodegudang" ,FALSE);
    return $this->db->get();
  }

  function runningnumber($yearmonth,$ikodemaster){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= substr($ikodemaster,5,2);
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
                            where i_modul='SO'
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
            $nopp  ="SO-".$thn.$bl."-".$area.$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="SO-".$thn.$bl."-".$area.$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SO','$area','$asal',1)");
          return $nopp;
        }
  }

  public function insertheader($istokopname, $ikodemaster, $dateso, $yearmonth){
        $dentry = date("d F Y");
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);

        $data = array(
            'i_stok_opname_bahanbaku' => $istokopname,
            'i_kode_master'           => $ikodemaster,
            'd_so'                    => $dateso,
            'd_bulan'                 => $bl,
            'd_tahun'                 => $th,
            'i_periode'               => $th.$bl,
            'd_entry'                 => $dentry,
        );
    $this->db->insert('tt_stok_opname_bahan_baku', $data);
  }

  public function updateheaderso($istokopname, $ikodemaster, $datefrom, $yearmonth){
        $dentry = date("d F Y");
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);

        $data = array(
            'd_so'                    => $datefrom,
            'd_update'                => $dentry,
        );
       $this->db->where('i_stok_opname_bahanbaku', $istokopname);
       $this->db->where('i_kode_master', $ikodemaster);
       $this->db->update('tt_stok_opname_bahan_baku', $data);
  }

  public function deletedetail($istokopname, $ikodemaster){
        $this->db->query("DELETE FROM tt_stok_opname_bahan_baku_detail WHERE i_stok_opname_bahanbaku='$istokopname'");
  }

  public function insertdetail($istokopname, $imaterial, $saldoawal, $saldoakhir, $stokopname, $nitemno){
      $data = array(
          'i_stok_opname_bahanbaku'   => $istokopname,
          'i_kode_brg'                => $imaterial,
          'v_jum_stok_opname'         => $stokopname,
          'v_stok_awal'               => $saldoawal,
          'v_saldo_akhir'             => $saldoakhir,
          'n_item_no'                 => $nitemno, 
      );
  $this->db->insert('tt_stok_opname_bahan_baku_detail', $data);
  }

  public function cek_data($iperiodebl, $iperiodeth, $ikodemaster){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_bahan_baku_detail a
                      join tt_stok_opname_bahan_baku b on a.i_stok_opname_bahanbaku = b.i_stok_opname_bahanbaku
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where a.f_status_approve = 'f' and b.f_status_approve = 'f' 
                      and b.d_bulan ='$iperiodebl' and b.d_tahun='$iperiodeth'
                      and b.i_kode_master = '$ikodemaster'", false);
    return $this->db->get();
  }

  public function cek_datadetail($iperiodebl, $iperiodeth, $ikodemaster){
    $this->db->select("a.*, b.*, c.e_material_name, d.e_satuan
                      from tt_stok_opname_bahan_baku_detail a
                      join tt_stok_opname_bahan_baku b on a.i_stok_opname_bahanbaku = b.i_stok_opname_bahanbaku
                      join tr_material c on a.i_kode_brg = c.i_material
                      join tr_satuan d on c.i_satuan_code = d.i_satuan_code
                      where a.f_status_approve = 'f' and b.f_status_approve = 'f' 
                      and b.d_bulan ='$iperiodebl' and b.d_tahun='$iperiodeth'
                      and b.i_kode_master = '$ikodemaster'", false);
    return $this->db->get();
  }

  public function updateheader($ikodeso, $ikodemaster, $dbulan, $dtahun){

    $data=array(
        'f_status_approve' => 't',
    );
    $this->db->where('i_stok_opname_bahanbaku', $ikodeso);
    $this->db->where('i_kode_master', $ikodemaster);
    $this->db->where('d_bulan', $dbulan);
    $this->db->where('d_tahun', $dtahun);
    $this->db->update('tt_stok_opname_bahan_baku', $data);
  } 

  public function updatedetail($ikodeso, $imaterial){

      $data = array(
            'f_status_approve'                 => 't', 
      );
     $this->db->where('i_stok_opname_bahanbaku', $ikodeso);
     $this->db->where('i_kode_brg', $imaterial);
     $this->db->update('tt_stok_opname_bahan_baku_detail', $data);
  }
}
/* End of file Mmaster.php */