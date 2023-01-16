<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("select a.i_kas_bank, a.d_bulan, a.d_tahun, c.i_bank, c.e_bank_name, d.i_area, d.e_area_name, a.i_no_transaksi, b.i_coa, a.d_date, a.e_deskripsi, a.v_total, a. d_entry,$i_menu as i_menu 
      FROM tt_kas_bank a 
              JOIN tr_coa b ON a.i_coa = b.i_coa
              JOIN tr_bank c on  a.i_bank = c.i_bank
              JOIN tr_area d on d.i_area = a.i_area
              WHERE a.is_kredit = 'f'");
     
		$datatables->add('action', function ($data) {
            $ikasbank= trim($data['i_kas_bank']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"kasbankkeluar/cform/view/$ikasbank/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"kasbankkeluar/cform/edit/$ikasbank/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

 public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('id_company',$idcompany);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iarea = $kuy->i_area; 
        }else{
            $iarea = '';
        }
        return $iarea;
  }

  public function cekearea($iarea){
        $this->db->select('e_area_name');
        $this->db->from('tr_area');
        $this->db->where('i_area',$iarea);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $earea = $kuy->e_area_name; 
        }else{
            $earea = '';
        }
        return $earea;
  }

  public function cekperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = '';
        }
        return $iperiode;
  }

  function get_bank(){
      $this->db->select('*');
      $this->db->from('tr_bank');
      $this->db->order_by('i_bank');
      return $this->db->get();
  }

  function get_area(){
      $this->db->select('*');
      $this->db->from('tr_area');
      $this->db->order_by('i_area');
      return $this->db->get();
  }

  public function cek_area($iarea){
      $this->db->select('*');
          $this->db->from('tr_area');
          $this->db->where('i_area',$iarea);
      return $this->db->get();
  }

  public function cek_bank($ibank){
      $this->db->select('*');
          $this->db->from('tr_bank');
          $this->db->where('i_bank',$ibank);
      return $this->db->get();
  }

  public function getcoabank($ibank){
        $this->db->select('i_coa');
        $this->db->from('tr_bank');
        $this->db->where('i_bank', $ibank);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $icoabank = $kuy->i_coa; 
        }else{
            $icoabank = '';
        }
        return $icoabank;
    }

  public function bacasaldo($area,$tanggal,$icoabank){     
        $tmp    = explode("-", $tanggal);
        $thn    = $tmp[0];
        $bln    = $tmp[1];
        $tgl    = $tmp[2];
        $dsaldo = $thn."/".$bln."/".$tgl;
        $dtos   = $this->fungsi->dateAdd("d",1,$dsaldo);
        $tmp1   = explode("-", $dtos,strlen($dtos));
        $th     = $tmp1[0];
        $bl     = $tmp1[1];
        $dt     = $tmp1[2];
        $dtos   = $th.$bl;
        $this->db->select('v_saldo_awal');
        $this->db->from('tm_coa_saldo');
        $this->db->where('i_periode',$dtos);
        $this->db->where('i_coa',$icoabank);
        $query = $this->db->get();
        $saldo=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $saldo=$row->v_saldo_awal;
            }
        }

        $this->db->select("
                SUM(x.v_bank) AS v_bank
            FROM
                (
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_rv x,
                    tm_rv_item z,
                    tm_kbank b
                WHERE
                    x.i_rv = z.i_rv
                    AND x.i_area = z.i_area
                    AND x.i_rv_type = z.i_rv_type
                    AND x.i_rv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 't'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode
            UNION ALL
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_pv x,
                    tm_pv_item z,
                    tm_kbank b
                WHERE
                    x.i_pv = z.i_pv
                    AND x.i_area = z.i_area
                    AND x.i_pv_type = z.i_pv_type
                    AND x.i_pv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 't'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode ) AS x
        ",false);
        $query = $this->db->get();
        $kredit=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $kredit=$row->v_bank;
            }
        }
        $this->db->select("
                SUM(x.v_bank) AS v_bank
            FROM
                (
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_rv x,
                    tm_rv_item z,
                    tm_kbank b
                WHERE
                    x.i_rv = z.i_rv
                    AND x.i_area = z.i_area
                    AND x.i_rv_type = z.i_rv_type
                    AND x.i_rv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 'f'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode
            UNION ALL
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_pv x,
                    tm_pv_item z,
                    tm_kbank b
                WHERE
                    x.i_pv = z.i_pv
                    AND x.i_area = z.i_area
                    AND x.i_pv_type = z.i_pv_type
                    AND x.i_pv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 'f'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode ) AS x
        ",false);
        $query = $this->db->get();
        $debet=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $debet=$row->v_bank;
            }
        }
        if($saldo=='' || $saldo==null){
            $saldo=0;
        }
        if($debet=='' || $debet==null){
            $debet=0;
        }
        if($kredit=='' || $kredit==null){
            $kredit=0;
        }
        $saldo = $saldo + $debet - $kredit;
        return $saldo;
    }

  public function bacaarea($iarea){
        if ($iarea=="00") {
            return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
        }else{
            $this->db->select('*');
            $this->db->from('tr_area');
            $this->db->where('i_area', $iarea);
            return $this->db->get()->result();
        }
   }

  function dateAdd($interval,$number,$dateTime) {
    $dateTime = (strtotime($dateTime) != -1) ? strtotime($dateTime) : $dateTime;
    $dateTimeArr=getdate($dateTime);
    $yr=$dateTimeArr['year'];
    $mon=$dateTimeArr['mon'];
    $day=$dateTimeArr['mday'];
    $hr=$dateTimeArr['hours'];
    $min=$dateTimeArr['minutes'];
    $sec=$dateTimeArr['seconds'];
    switch($interval) {
        case "s"://seconds
            $sec += $number;
            break;
        case "n"://minutes
            $min += $number;
            break;
        case "h"://hours
            $hr += $number;
            break;
        case "d"://days
            $day += $number;
            break;
        case "ww"://Week
            $day += ($number * 7);
            break;
        case "m": //similar result "m" dateDiff Microsoft
            $mon += $number;
            break;
        case "yyyy": //similar result "yyyy" dateDiff Microsoft
            $yr += $number;
            break;
        default:
            $day += $number;
         }      
        $dateTime = mktime($hr,$min,$sec,$mon,$day,$yr);
        $dateTimeArr=getdate($dateTime);
        $nosecmin = 0;
        $min=$dateTimeArr['minutes'];
        $sec=$dateTimeArr['seconds'];
        if ($hr==0){$nosecmin += 1;}
        if ($min==0){$nosecmin += 1;}
        if ($sec==0){$nosecmin += 1;}
        if ($nosecmin>2){     
        return(date("Y-m-d",$dateTime));
      } else {     
        return(date("Y-m-d G:i:s",$dateTime));
      }
  }

  public function runningnumberpvb($th,$bl,$icoabank,$iarea){
        $this->db->select(" max(substr(i_pvb,11,6)) as max 
            from tm_pvb 
            where substr(i_pvb,4,2)='$th' 
            and substr(i_pvb,6,2)='$bl' 
            and i_coa_bank='$icoabank'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nopvb  =$terakhir+1;
            settype($nopvb,"string");
            $a=strlen($nopvb);
            while($a<6){
                $nopvb="0".$nopvb;
                $a=strlen($nopvb);
            }
            $nopvb  ="PV-".$th.$bl."-".$iarea.$nopvb;
            return $nopvb;
        }else{
            $nopvb  ="000001";
            $nopvb  ="PV-".$th.$bl."-".$iarea.$nopvb;
            return $nopvb;
        }
    }

  function cekpvb($ipvb, $ipvtype, $iperiodeth, $ibank){
      $ipbvcut=substr($ipvb, 10,6);
      $this->db->select(" i_pvb from tm_pvb where i_pvb='$ipvb' and i_pv_type='$ipvtype' and substr(i_pvb,4,2)='$iperiodeth' AND i_coa_bank = '$ibank' ",false);
      $query=$this->db->get();
      if($query->num_rows()>0){
        return "ada";
      } else {
        return "tidak ada";
      }
  }

  public function runningnumberpv($th,$bl,$iarea,$ipvtype){
        $this->db->select(" max(substr(i_pv,11,6)) as max 
            from tm_pv 
            where substr(i_pv,4,2)='$th' 
            and substr(i_pv,6,2)='$bl' 
            and i_area='$iarea'
            and i_pv_type='$ipvtype'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nopv  =$terakhir+1;
            settype($nopv,"string");
            $a=strlen($nopv);
            while($a<6){
                $nopv="0".$nopv;
                $a=strlen($nopv);
            }
            $nopv  ="PV-".$th.$bl."-".$iarea.$nopv;
            return $nopv;
        }else{
            $nopv  ="000001";
            $nopv  ="PV-".$th.$bl."-".$iarea.$nopv;
            return $nopv;
        }
    }

    public function runningnumberbank($th,$bl,$iarea,$icoabank){
        $this->db->select(" max(substr(i_kbank,9,5)) as max from tm_kbank 
            where substr(i_kbank,4,2)='$th' and substr(i_kbank,6,2)='$bl' and i_coa_bank='$icoabank'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nogj  =$terakhir+1;
            settype($nogj,"string");
            $a=strlen($nogj);
            while($a<5){
                $nogj="0".$nogj;
                $a=strlen($nogj);
            }
            $nogj  ="BK-".$th.$bl."-".$nogj;
            return $nogj;
        }else{
            $nogj  ="00001";
            $nogj  ="BK-".$th.$bl."-".$nogj;
            return $nogj;
        }
    }

  public function getpv($ipvb, $ipvtype, $iperiodeth, $ibank){
      $this->db->select(" i_pv from tm_pvb where i_pvb='$ipvb' and i_pv_type='$ipvtype' and substr(i_pvb,4,2)='$iperiodeth' AND i_coa_bank = '$ibank' ",false);
      $query = $this->db->get();
      return $query;
  }

  public function inserttransheader($ikode,$iareax,$eremark,$fclose,$dbank,$icoabank){
        $dentry  = current_datetime();
        $eremark = str_replace("'","''",$eremark);
        $this->db->query("insert into tm_jurnal_transharian 
                         (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi,i_coa_bank)
                              values
                         ('$ikode','$iareax','$dentry','$eremark','$fclose','$dbank','$dbank','$icoabank')");
  }

    public function namaacc($icoa){
        $this->db->select("e_coa_name");
        $this->db->from("tr_coa");
        $this->db->where("i_coa",$icoa);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $tmp){
                $xxx=$tmp->e_coa_name;
            }
            return $xxx;
        }
    }

    public function inserttransitemdebet($accdebet,$ikode,$namadebet,$iareax,$eremark,$vbank,$dbank,$icoabank){
        $fdebet     = 't';
        $fposting   = 't';
        $dentry     = current_datetime();
        $namadebet  =str_replace("'","''",$namadebet);
        $this->db->set(
            array(
                'i_coa'             => $accdebet,
                'i_refference'      => $ikode,
                'e_coa_description' => $namadebet,
                'f_debet'           => $fdebet,
                'f_posting'         => $fposting,
                'v_mutasi_debet'    => $vbank,
                'd_refference'      => $dbank,
                'd_mutasi'          => $dbank,
                'd_entry'           => $dentry,
                'i_area'            => $iareax,
                'i_coa_bank'        => $icoabank
            )
        );
        $this->db->insert('tm_jurnal_transharianitem');
    }

    public function updatesaldodebet($accdebet,$iperiode,$vbank){        
        $this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet+$vbank, v_saldo_akhir=v_saldo_akhir+$vbank where i_coa='$accdebet' and i_periode='$iperiode'");
    }
    public function inserttransitemkredit($acckredit,$ikode,$namakredit,$iareax,$eremark,$vbank,$dbank,$icoabank){
        $fdebet     = 'f';
        $fposting   = 't';
        $dentry = current_datetime();
        $namakredit=str_replace("'","''",$namakredit);
        $this->db->set(
            array(
                'i_coa'             => $acckredit,
                'i_refference'      => $ikode,
                'e_coa_description' => $namakredit,
                'f_debet'           => $fdebet,
                'f_posting'         => $fposting,
                'v_mutasi_kredit'   => $vbank,
                'd_refference'      => $dbank,
                'd_mutasi'          => $dbank,
                'd_entry'           => $dentry,
                'i_area'            => $iareax,
                'i_coa_bank'        => $icoabank
            )
        );
        $this->db->insert('tm_jurnal_transharianitem');
    }

    public function updatesaldokredit($acckredit,$iperiode,$vjumlah){
        $this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vjumlah, v_saldo_akhir=v_saldo_akhir-$vjumlah where i_coa='$acckredit' and i_periode='$iperiode'");
    }

    public function insertgldebet($accdebet,$ikode,$namadebet,$iareax,$vbank,$dbank,$eremark,$icoabank){
        $fdebet = 'f';
        $vjumlah = '0';
        $dentry = current_datetime();
        $eremark=str_replace("'","''",$eremark);
        $namadebet=str_replace("'","''",$namadebet);
        $this->db->set(
            array(                
                'i_refference'      => $ikode,
                'i_coa'             => $accdebet,
                'd_mutasi'          => $dbank,
                'e_coa_name'        => $namadebet,                
                'f_debet'           => $fdebet,                
                'v_mutasi_debet'    => $vjumlah,
                'i_area'            => $iareax,
                'd_refference'      => $dbank,
                'e_description'     => $eremark,              
                'd_entry'           => $dentry,                
                'i_coa_bank'        => $icoabank
            )
        );
        $this->db->insert('tm_general_ledger');
    }

    public function insertglkredit($acckredit,$ikode,$namakredit,$iareax,$vbank,$dbank,$eremark,$icoabank){
        $dentry = current_datetime();
        $fdebet = 't';
        $eremark=str_replace("'","''",$eremark);
        $namakredit=str_replace("'","''",$namakredit);
        $this->db->set(
            array(                
                'i_refference'      => $ikode,
                'i_coa'             => $acckredit,
                'd_mutasi'          => $dbank,
                'e_coa_name'        => $namakredit,                
                'f_debet'           => $fdebet,                
                'v_mutasi_kredit'   => $vbank,
                'i_area'            => $iareax,
                'd_refference'      => $dbank,
                'e_description'     => $eremark,              
                'd_entry'           => $dentry,                
                'i_coa_bank'        => $icoabank
            )
        );
        $this->db->insert('tm_general_ledger');
    }

    public function insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vbank,$edescription,$ikode,$ipvtype,$iareax,$icoabank){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'     => $iarea,
                'i_pv'       => $ipv,
                'i_coa'      => $icoa,
                'e_coa_name' => $ecoaname,
                'v_pv'       => $vbank,
                'e_remark'   => $edescription,
                'i_kk'       => $ikode,
                'i_pv_type'  => $ipvtype,
                'i_area_kb'  => $iareax,
                'i_coa_bank' => $icoabank
            )
        );
        $this->db->insert('tm_pv_item');
    }

    public function insertpvb($ipvb,$icoabank,$ipv,$iarea,$ipvtype){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_pvb'       => $ipvb,
                'i_coa_bank'  => $icoabank,
                'i_pv'        => $ipv,
                'i_area'      => $iarea,
                'i_pv_type'   => $ipvtype,
                'd_entry'     => $dentry,
            )
        );
        $this->db->insert('tm_pvb');
    }

    public function insertpv($ipv,$iarea,$iperiode,$icoabank,$dpv,$tot,$eremark,$ipvtype){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_pv'      => $ipv,
                'i_area'    => $iarea,
                'i_periode' => $iperiode,
                'i_coa'     => $icoabank,
                'd_pv'      => $dpv,
                'v_pv'      => $tot,
                'd_entry'   => $dentry,
                'i_pv_type' => $ipvtype
            )
        );
        $this->db->insert('tm_pv');
    }

	public function insert($iareax,$ikode,$iperiode,$icoa,$vbank,$ecoaname,$edescription,$fdebet,$icoabank, $dbank){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'        => $iareax,
                'i_kbank'       => $ikode,             
                'i_periode'     => $iperiode,
                'i_coa'         => $icoa,
                'v_bank'        => $vbank,
                'v_sisa'        => $vbank,                
                'e_coa_name'    => $ecoaname,
                'e_description' => $edescription,                
                'f_debet'       => $fdebet,
                'i_coa_bank'    => $icoabank,
                'd_bank'        => $dbank,
                'd_entry'       => $dentry,
            )
        );
        $this->db->insert('tm_kbank');
    }
}

/* End of file Mmaster.php */