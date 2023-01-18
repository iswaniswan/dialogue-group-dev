<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function cek_data($dso){
       $interval = new DateInterval('P1M');

        $dateTime = new DateTime($dso);
        $bulan = $dateTime->format('m');
        $tahun = $dateTime->format('Y');
        $bulanlalu = $dateTime->sub($interval)->format('m');
        $tahunlalu = $dateTime->sub($interval)->format('Y');

        $datefrom = "01-".$bulan."-".$tahun;
        $dateto = new DateTime($dso); 
        $dateto = $dateto->format('t-m-Y' );

        //$this->db->select("* from f_mutasi_pengadaan('$bulanlalu', $tahunlalu, '$datefrom','$dateto', '$bulan', $tahun) order by kodewip, icolor;" ,FALSE);
        $this->db->select("* from f_mutasi_makloon_packing('$bulanlalu', $tahunlalu, '$datefrom','$dateto', '$bulan', $tahun) order by kodebarang, icolor;" ,FALSE);

        return $this->db->get();
  }

  function runningnumber($yearmonth){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);

        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='SO'
                            and i_area='MK'
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
                            and i_area='MK'
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
                             values ('SO','MK','$asal',1)");
          return $nopp;
        }
  }

  public function insertheader($istokopname, $dateso, $yearmonth){
        $dentry = date("d F Y");
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);

        $data = array(
            /*'i_stok_opname_pengadaan' => $istokopname,
            'd_so'                    => $dateso,
            'd_bulan'                 => $bl,
            'd_tahun'                 => $th,
            'i_periode'               => $th.$bl,
            'd_entry'                 => $dentry,*/
            'i_stok_opname_makloonpacking'  => $istokopname,
            'd_so'                          => $dateso,
            'd_bulan'                       => $bl,
            'd_tahun'                       => $th,
            'i_periode'                     => $th.$bl,
            'd_entry'                       => $dentry,
        );
    //$this->db->insert('tt_stok_opname_pengadaan', $data);
        $this->db->insert('tt_stok_opname_makloonpacking', $data);
  }

  /*public function insertdetail($istokopname, $iproduct, $icolor, $imaterial, $saldoawal, $saldoakhir, $stokopname, $nitemno){*/
    public function insertdetail($istokopname, $iproduct, $icolor, $saldoawal, $saldoakhir, $stokopname, $nitemno){
      $data = array(
          /*'i_stok_opname_pengadaan'   => $istokopname,
          'i_product'                 => $iproduct,
          'i_color'                   => $icolor,
          'i_material'                => $imaterial,
          'v_jum_stok_opname'         => $stokopname,
          'v_stok_awal'               => $saldoawal,
          'v_saldo_akhir'             => $saldoakhir,
          'n_item_no'                 => $nitemno, */
          'i_stok_opname_makloonpacking' => $istokopname,
          'i_product'                    => $iproduct,
          'i_color'                      => $icolor,
          'v_stok_opname'                => $stokopname,
          'v_stok_awal'                  => $saldoawal,
          'v_saldo_akhir'                => $saldoakhir,
          'n_item_no'                    => $nitemno,
      );
  //$this->db->insert('tt_stok_opname_pengadaan_detail', $data);
    $this->db->insert('tt_stok_opname_makloonpacking_detail', $data);
  }

  public function updateheaderso($istokopname, $datefrom, $yearmonth){
        $dentry = date("d F Y");
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);

        $data = array(
            'd_so'                    => $datefrom,
            'd_update'                => $dentry,
            'f_status_approve'        => 'f'
        );
       //$this->db->where('i_stok_opname_pengadaan', $istokopname);
       $this->db->where('i_stok_opname_makloonpacking', $istokopname);
       //$this->db->update('tt_stok_opname_pengadaan', $data);
       $this->db->update('tt_stok_opname_makloonpacking', $data);
  }

  public function deletedetail($istokopname){
        //$this->db->query("DELETE FROM tt_stok_opname_pengadaan_detail WHERE i_stok_opname_pengadaan='$istokopname'");
        $this->db->query("DELETE FROM tt_stok_opname_makloonpacking_detail WHERE i_stok_opname_makloonpacking='$istokopname'");
  }

  public function cek_dataheader($yearmonth){
    //$this->db->select("i_stok_opname_pengadaan, to_char(d_so, 'dd-mm-yyyy') as d_so, f_status_approve, i_periode from tt_stok_opname_pengadaan where i_periode = '$yearmonth' and f_status_approve='f'", false);
    $this->db->select("i_stok_opname_makloonpacking, to_char(d_so, 'dd-mm-yyyy') as d_so, f_status_approve, i_periode from tt_stok_opname_makloonpacking where i_periode = '$yearmonth' and f_status_approve='f'", false);
    return $this->db->get();
  }

  public function cek_datadetail($yearmonth){
    /*return $this->db->query("SELECT
                            b.i_product as kodewip,
                            wip.e_namabrg as barangwip,
                            b.i_color as icolor,
                            co.e_color_name as ecolor,
                            b.i_material as kode,
                            ma.e_material_name as barang,
                            b.v_stok_awal as saldoawal,
                            b.v_saldo_akhir as saldoakhir,
                            b.v_jum_stok_opname as so 
                        FROM tt_stok_opname_pengadaan a 
                        JOIN tt_stok_opname_pengadaan_detail b ON a.i_stok_opname_pengadaan = b.i_stok_opname_pengadaan 
                        JOIN tm_barang_wip wip ON b.i_product = wip.i_kodebrg
                        JOIN tr_color co ON b.i_color = co.i_color 
                        JOIN tr_material ma on b.i_material = ma.i_material
                        WHERE a.i_periode = '$yearmonth'
                        and a.f_status_approve='f'", false);*/
    return $this->db->query("SELECT
                            b.i_product as kodewip,
                            wip.e_namabrg as barangwip,
                            b.i_color as icolor,
                            co.e_color_name as ecolor,
                            b.v_stok_awal as saldoawal,
                            b.v_saldo_akhir as saldoakhir,
                            b.v_stok_opname as so  
                        FROM tt_stok_opname_makloonpacking a 
                        /*JOIN tt_stok_opname_makloonpacking_detail b ON a.i_stok_opname_makloonpacking = b.i_stok_opname_makloonpacking 
                        JOIN tm_barang_wip wip ON b.i_product = wip.i_kodebrg
                        JOIN tr_color co ON b.i_color = co.i_color */
                        JOIN tt_stok_opname_makloonpacking_detail b ON a.i_stok_opname_makloonpacking = b.i_stok_opname_makloonpacking 
                        JOIN tr_product_base c ON c.i_product_motif=b.i_product
                        JOIN tm_barang_wip wip ON wip.i_kodebrg = c.i_product_base
                        JOIN tr_color co ON b.i_color = co.i_color 
                        WHERE a.i_periode = '$yearmonth'
                        and a.f_status_approve='f'", false);
  }

  public function updateheader($ikodeso, $dbulan, $dtahun){
    $dupdate = date("d F Y");
    $data=array(
        'f_status_approve' => 't',
        'd_update'         => $dupdate,

    );
    //$this->db->where('i_stok_opname_pengadaan', $ikodeso);
    $this->db->where('i_stok_opname_makloonpacking', $ikodeso);
    $this->db->where('d_bulan', $dbulan);
    $this->db->where('d_tahun', $dtahun);
    //$this->db->update('tt_stok_opname_pengadaan', $data);
    $this->db->update('tt_stok_opname_makloonpacking', $data);
  } 
}
/* End of file Mmaster.php */