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

  public function bacasaldo($area,$tanggal,$icoabank,$periode){     
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

        $this->db->select("sum(x.v_kb) as v_kb from 
        (
        select sum(b.v_kb) as v_kb from tm_rv x, tm_rv_item z, tm_kb b
        where x.i_rv=z.i_rv and x.i_area=z.i_area and x.i_rv_type=z.i_rv_type and x.i_rv_type='01'
        and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='t' 
        and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
        UNION ALL
        select sum(b.v_kb) as v_kb from tm_pv x, tm_pv_item z, tm_kb b
        where x.i_pv=z.i_pv and x.i_area=z.i_area and x.i_pv_type=z.i_pv_type and x.i_pv_type='01'
        and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='t' 
        and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
    ) as x 
        ",false);
        $query = $this->db->get();
        $kredit=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $kredit=$row->v_kb;
            }
        }
        if($kredit==null)$kredit=0;
        $this->db->select("
        sum(x.v_kb) as v_kb from 
        (
        select sum(b.v_kb) as v_kb from tm_rv x, tm_rv_item z, tm_kb b
        where x.i_rv=z.i_rv and x.i_area=z.i_area and x.i_rv_type=z.i_rv_type and x.i_rv_type='01'
        and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='f' 
        and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
        UNION ALL							
        select sum(b.v_kb) as v_kb from tm_pv x, tm_pv_item z, tm_kb b
        where x.i_pv=z.i_pv and x.i_area=z.i_area and x.i_pv_type=z.i_pv_type and x.i_pv_type='01'
        and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='f' 
        and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
        ) as x",false);
        $query = $this->db->get();
        $debet=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $debet=$row->v_kb;
            }
        }
        if ($periode<='201912') {
    		$coaku=KasKecilx.$area;
    		$kasbesar=KasBesarx;
    	}else{
    		$coaku=KasKecil.$area;
    		$kasbesar=KasBesar;
    	}

    	$bank=Bank;
    	$this->db->select(" sum(v_bank) as v_bank from tm_kbank a 
    		where a.i_periode='$dtos' and a.i_area='$area' and a.d_bank<'$tanggal' and a.f_debet='t' and 
    		a.f_kbank_cancel='f' and a.i_coa='$coaku'
    		and a.v_bank not in (select b.v_kk as v_bank from tm_kk b where b.d_kk < '$tanggal' and b.i_area='$area' 
    		and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '$bank%' and b.i_periode='$dtos')
    		",false);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0){
    		$bank=Bank;
    		foreach($query->result() as $row){
    			$debet=$debet+$row->v_bank;
    		}
    	}
    	$this->db->select(" sum(v_kk) as v_kk from tm_kk a where a.i_periode='$dtos' and a.i_area='$area' and a.d_kk<'$tanggal' 
    		and a.f_debet='t' and a.f_kk_cancel='f' and a.i_coa='$coaku'
    		and a.v_kk not in (select b.v_kb as v_kk from tm_kb b where b.d_kb < '$tanggal' and b.i_area='$area' 
    		and b.f_kb_cancel='f' and b.f_debet='f' and b.i_coa like '$coaku' 
    		and b.i_periode='$dtos')",false);							
    	$query = $this->db->get();
    	if ($query->num_rows() > 0){
    		foreach($query->result() as $row){
    			$debet=$debet+$row->v_kk;
    		}
    	}
    	if($debet==null)$debet=0;
    	$saldo=$saldo+$debet-$kredit;
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

    public function runningnumberbank($th,$bl,$iarea){
        $this->db->select(" max(substr(i_kb,9,5)) as max from tm_kb 
            where substr(i_kb,4,2)='$th' and substr(i_kb,6,2)='$bl'", false);
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
            $nogj  ="KB-".$th.$bl."-".$nogj;
            return $nogj;
        }else{
            $nogj  ="00001";
            $nogj  ="KB-".$th.$bl."-".$nogj;
            return $nogj;
        }
    }

  public function getpv($ipvb, $ipvtype, $iperiodeth, $ibank){
      $this->db->select(" i_pv from tm_pvb where i_pvb='$ipvb' and i_pv_type='$ipvtype' and substr(i_pvb,4,2)='$iperiodeth' AND i_coa_bank = '$ibank' ",false);
      $query = $this->db->get();
      return $query;
  }

   public function //inserttransheader( $inota,$iarea,$eremark,$fclose,$dkn)
   inserttransheader($ikb,$iareax,$eremark,$fclose,$dkb,$now){
        $dentry  = current_datetime();
        $eremark = str_replace("'","''",$eremark);
        $this->db->query("insert into tm_jurnal_transharian 
                         (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
                              values
                         ('$ikb','$iareax','$now','$eremark','$fclose','$dkb','$dkb')");
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

     public function //inserttransitemdebet($accdebet,$ikb,$namadebet,$fdebet,$fposting,$iarea,$eremark,$vjumlah,$dkn,$icoabank)
    inserttransitemdebet($accdebet,$ikb,$namadebet,$iareax,$eremark,$vkb,$dkb,$now){
        $fdebet     = 't';
        $fposting   = 't';
    // inserttransitemdebet($accdebet,$ikb,$namadebet,'t','t',$iareax,$eremark,$vkb,$dkb,$now)
        $dentry = current_datetime();
        $namadebet=str_replace("'","''",$namadebet);
        $this->db->query("insert into tm_jurnal_transharianitem
           (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_area)
           values
           ('$accdebet','$ikb','$namadebet','$fdebet','$fposting','$vkb','$dkb','$dkb','$now','$iareax')");
    }

    public function //updatesaldodebet($accdebet,$iperiode,$vjumlah)
    updatesaldodebet($accdebet,$iperiode,$vkb){        
        $this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vkb, 
        v_saldo_akhir=v_saldo_akhir+$vkb 
        where i_coa='$accdebet' and i_periode='$iperiode'");
    }

    public function //inserttransitemkredit($acckredit,$ikn,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dkn,$icoabank)
    inserttransitemkredit($acckredit,$ikb,$namakredit,$iareax,$eremark,$vkb,$dkb,$now){
        //inserttransitemkredit($acckredit,$ikb,$namakredit,'f','t',$iareax,$eremark,$vkb,$dkb,$now)
        $fdebet     = 'f';
        $fposting   = 't';
        $dentry = current_datetime();
        $namakredit=str_replace("'","''",$namakredit);
        $this->db->query("insert into tm_jurnal_transharianitem
           (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_area)
           values
           ('$acckredit','$ikb','$namakredit','$fdebet','$fposting','$vkb','$dkb','$dkb',',$now','$iareax')");
    }

    public function updatesaldokredit($acckredit,$iperiode,$vkb){
        $this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vkb, v_saldo_akhir=v_saldo_akhir-$vkb where i_coa='$acckredit' and i_periode='$iperiode'");
    }

    public function insertgldebet($accdebet,$ikb,$namadebet,$iareax,$vkb,$dkb,$eremark){
        $dentry = current_datetime();
        $fdebet = 'f';
        $vjumlah = '0';
        $eremark=str_replace("'","''",$eremark);
        $namadebet=str_replace("'","''",$namadebet);
        $this->db->query("insert into tm_general_ledger
                         (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
                              values
                         ('$ikb','$accdebet','$dkb','$namadebet','$fdebet',$vjumlah,'$iareax','$dkb','$eremark','$dentry')");
    }

    public function //insertglkredit($acckredit,$ikn,$namakredit,$fdebet,$iarea,$vjumlah,$dkn,$eremark,$icoabank)
    insertglkredit($acckredit,$ikb,$namakredit,$iareax,$vkb,$dkb,$eremark){
    // insertglkredit($acckredit,$ikb,$namakredit,'t',$iareax,$vkb,$dkb,$eremark)
        $fdebet = 't';
        $dentry = current_datetime();
        $eremark=str_replace("'","''",$eremark);
        $namakredit=str_replace("'","''",$namakredit);
        $this->db->query("insert into tm_general_ledger
                         (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry)
                              values
                         ('$ikb','$acckredit','$dkb','$namakredit','$fdebet','$vkb','$iareax','$dkb','$eremark','$dentry')");
    }

    public function //insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vpv,$edescription,$ikk,$ipvtype,$iareax,$icoabank)
    insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vkb,$edescription,$ikb,$ipvtype,$iareax){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'     => $iarea,
                'i_pv'       => $ipv,
                'i_coa'      => $icoa,
                'e_coa_name' => $ecoaname,
                'v_pv'       => $vkb,
                'e_remark'   => $edescription,
                'i_kk'       => $ikb,
                'i_pv_type'  => $ipvtype,
                'i_area_kb'  => $iareax,
                // 'i_coa_bank' => $icoabank
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

    public function insertpv($ipv,$iarea,$iperiode,$icoa,$dpv,$tot,$eremark,$ipvtype){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_pv'      => $ipv,
                'i_area'    => $iarea,
                'i_periode' => $iperiode,
                'i_coa'     => $icoa,
                'd_pv'      => $dpv,
                'v_pv'      => $tot,
                'd_entry'   => $dentry,
                'i_pv_type' => $ipvtype
            )
        );
        $this->db->insert('tm_pv');
    }
    public function //insert($iareax,$ikb,$iperiode,$icoa,$vkb,$dkb,$ecoaname,$edescription,$fdebet)
    insert($iareax,$ikb,$iperiode,$icoa,$vkb,$dbukti,$ecoaname,$edescription,$fdebet,$dkb){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'        => $iareax,
                'i_kb'          => $ikb,             
                'i_periode'     => $iperiode,
                'i_coa'         => $icoa,
                'v_kb'          => $vkb,
                'v_sisa'        => $vkb,
                'd_kb'          => $dkb,
                'e_coa_name'    => $ecoaname,
                'e_description' => $edescription,
                'd_entry'       => $dentry,
                'f_debet'       => $fdebet,
                'f_posting'     => FALSE,
                'f_close'       => FALSE,
                'f_posting'     => FALSE,
                'd_bukti'       => $dbukti,
                'f_kb_cancel'   => FALSE,
                // 'i_coa_bank'    => $icoabank
            )
        );
        $this->db->insert('tm_kb');
    }
}

/* End of file Mmaster.php */