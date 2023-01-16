<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  function data($i_menu, $dfrom, $dto, $username, $idcompany, $idepartemen, $ilevel){
    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" select '$i_menu' as i_menu, a.i_stok_opname_makloonjahit, to_char(a.d_so, 'dd-mm-yyyy') as d_so, a.i_periode, b.e_unitjahit_name, 
          a.f_status_approve, a.partner, '$ilevel' as i_level, '$idepartemen' as i_departement
        from tt_stok_opname_makloonjahit a inner join tr_unit_jahit b on (a.partner = b.i_unit_jahit)
        where a.d_so between to_date('$dfrom', 'dd-mm-yyyy') and to_date('$dto', 'dd-mm-yyyy') order by d_so ",false);
        
        $datatables->edit('f_status_approve', function ($data) {
            $f_status_approve = trim($data['f_status_approve']);
            if($f_status_approve == 'f'){
                return  "Belum Approve";
            }else {
                return "Sudah Approve";
            }
        });

            // $datatables->edit('e_status', function ($data) {
            //   return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
            // });

            $datatables->add('action', function ($data) {
            $i_stok_opname_makloonjahit    = trim($data['i_stok_opname_makloonjahit']);
            $i_menu     = $data['i_menu'];
            $i_departement= trim($data['i_departement']);
            $i_level      = trim($data['i_level']);
            $partner      = trim($data['partner']);
            $f_status_approve = trim($data['f_status_approve']);
            
            $data       = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"somakloonjahit/cform/view/$i_stok_opname_makloonjahit/$partner/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }

            if(check_role($i_menu, 3)){
                if ((($i_departement == '16' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $f_status_approve == 'f') {
                    $data .= "<a href=\"#\" onclick='show(\"somakloonjahit/cform/approval/$i_stok_opname_makloonjahit/$partner/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
                }
            }
            
            
      return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('partner');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        return $datatables->generate();
  }

  public function getpartner(){
      $this->db->select("i_unit_jahit, e_unitjahit_name from tr_unit_jahit order by e_unitjahit_name", false);
      return $this->db->get()->result();
  }

  public function getpartnerbyid($partner){
      $this->db->select("i_unit_jahit, e_unitjahit_name from tr_unit_jahit where i_unit_jahit = '$partner'", false);
      return $this->db->get()->row();
  }

  public function getbarang($ikodemaster){
      $this->db->select("a.i_material, a.e_material_name
                        from tr_material a
                        join tm_kelompok_barang b on a.i_kode_kelompok = b.i_kode_kelompok
                        where 
                        a.i_kode_kelompok='KTB0004' or a.i_kode_kelompok='KTB0005'
                        order by a.i_material", false);
        return $this->db->get();
  }

  function cek_datadet($dso,$partner){
        $interval = new DateInterval('P1M');

        $dateTime = new DateTime($dso);
        $bulan = $dateTime->format('m');
        $tahun = $dateTime->format('Y');
        $bulanlalu = $dateTime->sub($interval)->format('m');
        $tahunlalu = $dateTime->sub($interval)->format('Y');

        $datefrom = "01-".$bulan."-".$tahun;
        $dateto = new DateTime($dso); 
        $dateto = $dateto->format('t-m-Y' );

        $dawal = $datefrom;
        $dakhir = $datefrom;
        $partner2 = 'xx';

        //var_dump($bulanlalu, $tahunlalu, $datefrom, $dateto, $bulan, $tahun, $ikodebarang, $ikodemaster);
        //die();
        $this->db->select("* from f_mutasi_makloonjahit('$bulanlalu','$tahunlalu','$datefrom','$dateto','$bulan','$tahun','$partner', '$dawal', '$dakhir', '$partner2') order by kodewip, icolor;" ,FALSE);
        return $this->db->get();
  }

  function runningnumber($yearmonth, $partner, $lokasi){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= 'MJ';
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
            $nopp  ="SO-".$lokasi."-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="SO-".$lokasi."-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SO','$area','$asal',1)");
          return $nopp;
        }
  }

  function cek_datadet_upload($dso, $kodewip,$icolor,$partner ){
        $interval = new DateInterval('P1M');

        $dateTime = new DateTime($dso);
        $bulan = $dateTime->format('m');
        $tahun = $dateTime->format('Y');
        $bulanlalu = $dateTime->sub($interval)->format('m');
        $tahunlalu = $dateTime->sub($interval)->format('Y');

        $datefrom = "01-".$bulan."-".$tahun;
        $dateto = new DateTime($dso); 
        $dateto = $dateto->format('t-m-Y' );

        $dawal = $datefrom;
        $dakhir = $datefrom;
        $partner2 = 'xx';

        //var_dump($bulanlalu,$tahunlalu,$datefrom,$dateto,$bulan,$tahun,$partner, $dawal, $dakhir, $partner2);
        //die();
        
        $query = $this->db->query("
          select saldoawal, saldoakhir from f_mutasi_makloonjahit('$bulanlalu','$tahunlalu','$datefrom','$dateto','$bulan','$tahun','$partner', '$dawal', '$dakhir', '$partner2') 
          where kodewip='$kodewip' and icolor = '$icolor' order by kodewip, icolor;
        ", false);

        return $query;
  }

  public function insertheader($istokopname, $dso, $yearmonth, $partner, $year, $month){
        $dentry = date("d F Y");
        $data = array(
            'i_stok_opname_makloonjahit'   => $istokopname,
            'd_so'                    => $dso,
            'd_bulan'                 => $month,
            'd_tahun'                 => $year,
            'i_periode'               => $yearmonth,
            'f_status_approve'        => 'f',
            'd_entry'                 => $dentry,
            'partner'                 => $partner,
        );
    $this->db->insert('tt_stok_opname_makloonjahit', $data);
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
       $this->db->where('i_stok_opname_makloonjahit', $istokopname);
       $this->db->update('tt_stok_opname_makloonjahit', $data);
  }

  public function deletedetail($istokopname){
        $this->db->query("DELETE FROM tt_stok_opname_makloonjahit_detail WHERE i_stok_opname_makloonjahit='$istokopname'");
  }

  public function insertdetail($istokopname, $iwip,$icolor, $saldoawal, $saldoakhir, $stokopname, $nitemno,$partner){
      $data = array(
          'i_stok_opname_makloonjahit' => $istokopname,
          'i_product'           => $iwip,
          'i_color'             => $icolor,
          'grade'               => 'A',
          'v_stok_opname'       => $stokopname,
          'v_stok_awal'         => $saldoawal,
          'v_saldo_akhir'       => $saldoakhir,
          'f_status_approve'    => 'f',
          'n_item_no'           => $nitemno,
          'partner'             => $partner,
      );
      $this->db->insert('tt_stok_opname_makloonjahit_detail', $data);
  }

  public function updateheader($ikodeso, $periode,$partner){

    $data=array(
        'f_status_approve' => 't',
    );
    $this->db->where('i_stok_opname_makloonjahit', $ikodeso);
    $this->db->where('i_periode', $periode);
    $this->db->where('partner', $partner);
    $this->db->update('tt_stok_opname_makloonjahit', $data);
  } 

  public function updatedetail($ikodeso, $partner){

      $data = array(
            'f_status_approve'                 => 't', 
      );
     $this->db->where('i_stok_opname_makloonjahit', $ikodeso);
     $this->db->where('partner', $partner);
     $this->db->update('tt_stok_opname_makloonjahit_detail', $data);
  }


  public function cek_dataheader($iso, $partner){
    $this->db->select("a.i_stok_opname_makloonjahit, to_char(a.d_so, 'dd-mm-yyyy') as d_so, a.f_status_approve, a.i_periode, a.partner, b.e_unitjahit_name from tt_stok_opname_makloonjahit a
      inner join tr_unit_jahit b on (a.partner = b.i_unit_jahit)
      where a.i_stok_opname_makloonjahit = '$iso' and a.partner = '$partner'", false);
    return $this->db->get();
  }

  public function cek_datadetail($iso, $partner){
    $this->db->select("b.i_product as kodewip, wi.e_namabrg as barangwip, co.e_color_name as ecolor,
    b.i_color as icolor, b.v_stok_awal as saldoawal, b.v_saldo_akhir as saldoakhir, b.v_stok_opname as so
    from tt_stok_opname_makloonjahit a
    JOIN tt_stok_opname_makloonjahit_detail b ON a.i_stok_opname_makloonjahit = b.i_stok_opname_makloonjahit   
    JOIN tm_barang_wip wi ON b.i_product = wi.i_kodebrg
    JOIN tr_color co ON b.i_color = co.i_color
    where a.i_stok_opname_makloonjahit = '$iso' and a.partner = '$partner'", false);
    return $this->db->get();
  }

}
/* End of file Mmaster.php */