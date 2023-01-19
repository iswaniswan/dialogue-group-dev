<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

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

    public function bacacoa($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_coa,
                e_coa_name
            FROM
                tr_coa
            WHERE
                (UPPER(i_coa) LIKE '800-2000' or UPPER(i_coa) LIKE '6%$cari%'
                OR UPPER(e_coa_name) LIKE '%$cari%')
                AND (i_coa like '6%' or i_coa like '800-2000')
        ", FALSE);
    }

    public function getdetailcoa($icoa){
        return $this->db->query("
            SELECT
                e_coa_name
            FROM
                tr_coa
            WHERE
                i_coa = '$icoa'
                AND (i_coa like '6%' or i_coa like '800-2000')
        ", FALSE);
    }

    function insert($iarea,$ikk,$iperiode,$icoa,$vkk,$dkk,$ecoaname,$edescription,$fdebet,$dbukti,$ibukti){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	            => $iarea,
				'i_kk'	            	=> $ikk,				
				'i_periode'         	=> $iperiode,
				'i_coa'	            	=> $icoa,
				'v_kk'		            => $vkk,
				'i_bukti_pengeluaran'	=> $ibukti,
				'd_kk'		            => $dkk,
				'e_coa_name'	        => $ecoaname,
				'e_description'	      	=> $edescription,
				'd_entry'           	=> $dentry,
				'd_bukti'	            => $dbukti,
				'f_debet'	            => $fdebet
    		)
    	);
    	$this->db->insert('tm_kk');
    }

    function update($iarea,$ikk,$iperiode,$icoa,$vkk,$dkk,$ecoaname,$edescription,$fdebet,$dbukti,$enamatoko,$ibukti){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dupdate= $row->c;
    	$this->db->set(
    		array(
				'i_coa'		=> $icoa,
				'v_kk'		=> $vkk,
				'i_bukti_pengeluaran'	=> $ibukti,
				'd_kk'		=> $dkk,
				'e_coa_name'	=> $ecoaname,
				'e_description'	=> $edescription,
				'd_update'	=> $dupdate,
				'd_bukti'	=> $dbukti,
				'f_debet'	=> $fdebet,
				'e_nama_toko'	=> $enamatoko,
				'e_pengguna'	=> $epengguna
    		)
    	);
		$this->db->where('i_area',$iarea);
		$this->db->where('i_kk',$ikk);
		$this->db->where('i_periode',$iperiode);
   		$this->db->update('tm_kk');
    }

    function insertpv($ipv,$iarea,$iperiode,$icoa,$dpv,$tot,$eremark,$ipvtype){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_pv'	            	=> $ipv,
				'i_area'	            => $iarea,
				'i_periode'         	=> $iperiode,
				'i_coa' 	            => $icoa,
				'd_pv'		            => $dpv,
				'v_pv'		            => $tot,
				'd_entry'           	=> $dentry,
				'i_pv_type'           => $ipvtype
    		)
    	);
    	$this->db->insert('tm_pv');
	}
	
    function insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vpv,$edescription,$ikk,$ipvtype){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	            => $iarea,
				'i_pv'	            	=> $ipv,
				'i_coa'              	=> $icoa,
				'e_coa_name'	        => $ecoaname,
				'v_pv'		            => $vpv,
				'e_remark'    	      => $edescription,
				'i_kk'                => $ikk,
				'i_pv_type'           => $ipvtype
    		)
    	);
    	$this->db->insert('tm_pv_item');
    }

	function runningnumberpv($th,$bl,$iarea,$ipvtype){
		$this->db->select(" max(substr(i_pv,11,6)) as max 
		                    from tm_pv 
		                    where substr(i_pv,4,2)='$th' and substr(i_pv,6,2)='$bl' and i_area='$iarea'
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
	function runningnumberkk($th,$bl,$iarea){
		$this->db->select(" max(substr(i_kk,9,5)) as max from tm_kk where substr(i_kk,4,2)='$th' and substr(i_kk,6,2)='$bl' and i_area='$iarea'", false);
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
			$nogj  ="KK-".$th.$bl."-".$nogj;
			return $nogj;
		}else{
			$nogj  ="00001";
			$nogj  ="KK-".$th.$bl."-".$nogj;
			return $nogj;
		}
	}
	
	function bacasaldo($area,$tanggal){
		$tmp = explode("-", $tanggal);
		$thn	= $tmp[2];
		$bln	= $tmp[1];
		$tgl 	= $tmp[0];
		$dtos	= $thn.$bln;
		$tanggal=$thn.'-'.$bln.'-'.$tgl;
		$this->db->select(" i_area from tr_area_mapping where i_area_mapping='$area'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
	      		$area=$row->i_area;	
      		}
    	}
		$this->db->select(" a.v_saldo_awal, a.i_coa from tm_coa_saldo a, tr_coa b
		where a.i_periode='$dtos' 
		and a.i_coa = b.i_coa
		and b.i_area = '$area'
		and b.e_coa_name like '%Kas Kecil%'",false);
		$query = $this->db->get();
		$saldo=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$saldo=$row->v_saldo_awal;
				$coa_area = $row->i_coa;
			}
		}
		$this->db->select(" sum(v_kk) as v_kk from tm_kk
							where i_periode='$dtos' and i_area='$area'
							and d_kk<='$tanggal' and f_debet='t' and f_kk_cancel='f'",false);						 
		$query = $this->db->get();
		$kredit=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$kredit=$kredit+$row->v_kk;
			}
		}
		$this->db->select(" sum(v_kk) as v_kk from tm_kk
							where i_periode='$dtos' and i_area='$area'
							and d_kk<='$tanggal' and f_debet='f' and f_kk_cancel='f'",false);							
		$query = $this->db->get();
		$debet=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$debet=$row->v_kk;
			}
		}
		$coaku=$coa_area;
		$kasbesar=KasBesar;
    	$bank=Bank;
		$this->db->select(" sum(v_bank) as v_bank from tm_kbank a where a.i_periode='$dtos' and a.i_area='$area' and a.d_bank<='$tanggal' 
		                    and a.f_debet='t' and a.f_kbank_cancel='f' and a.i_coa='$coaku'
		                    and a.v_bank not in (select b.v_kk as v_bank from tm_kk b where b.d_kk = a.d_bank and b.i_area='$area' 
	                      	and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '$bank%' and b.i_periode='$dtos')",false);							
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$debet=$debet+$row->v_bank;
			}
		}
	  	$this->db->select(" sum(v_kb) as v_kb from tm_kb a where a.i_periode='$dtos' and a.i_area='$area' and a.d_kb<='$tanggal' 
	                      and a.f_debet='t' and a.f_kb_cancel='f' and a.i_coa='$coaku'
	                      and a.v_kb not in (select b.v_kk as v_kb from tm_kk b where b.d_kk = a.d_kb and b.i_area='$area' 
	                      and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '900-000%' and b.i_periode='$dtos')",false);
	  	$query = $this->db->get();
	  	if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
				  $debet=$debet+$row->v_kb;
			  }
	  	}
		$saldo=$saldo+$debet-$kredit;
		return $saldo;
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

	function area($iarea){
		$this->db->select(" e_area_name from tr_area where i_area='$iarea'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$nama=$row->e_area_name;
				return $nama;
			}
		}
	}
	
	function bacakkgroup($cari,$num,$offset){
		$this->db->select(" * from tr_kk_group where upper(i_kk_group) like '%$cari%' or upper(e_kk_groupname) like '%$cari%' order by i_kk_group",false)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
	function bacapv($ipv,$iarea){
		$this->db->select(" * from tm_pv a, tm_pv_item b
		                    where a.i_pv=b.i_pv and a.i_area=b.i_area and a.i_area='$iarea' and a.i_pv='$ipv' 
		                    and a.i_pv_type= b.i_pv_type and a.i_pv_type='00'
		                    order by b.i_kk",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	  }
	  
	function bacapvprint($cari,$area,$periode,$num,$offset){
    	if($cari=='sikasep'){
		  	$this->db->select(" distinct on (a.i_pv) a.* from tm_pv a, tm_pv_item b 
		  	                    where a.i_pv=b.i_pv and a.i_area=b.i_area and a.i_pv_type='00' 
		  	                    and a.i_area='$area' and a.i_periode='$periode' order by a.i_pv, b.i_kk",false)->limit($num,$offset);
    	}else{
		  	$this->db->select(" distinct on (a.i_pv) a.* from tm_pv a, tm_pv_item b 
		  	                    where a.i_pv=b.i_pv and a.i_area=b.i_area and a.i_pv_type='00'
		  	                    and a.i_area='$area' and a.i_periode='$periode'
		  	                    and upper(a.i_pv) like '%$cari%' order by a.i_pv, b.i_kk",false)->limit($num,$offset);
    	}
  		$query = $this->db->get();
  		if ($query->num_rows() > 0){
  			return $query->result();
  		}
  	}

	function inserttransheader(	$inota,$iarea,$eremark,$fclose,$dkn )
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$eremark=str_replace("'","''",$eremark);
		$this->db->query("insert into tm_jurnal_transharian 
						 (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
						  	 values
					  	 ('$inota','$iarea','$dentry','$eremark','$fclose','$dkn','$dkn')");
	}

	function inserttransitemdebet($accdebet,$ikn,$namadebet,$fdebet,$fposting,$iarea,$eremark,$vjumlah,$dkn){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namadebet=str_replace("'","''",$namadebet);
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry, i_area)
						  	  values
					  	 ('$accdebet','$ikn','$namadebet','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry','$iarea')");
	}

	function inserttransitemkredit($acckredit,$ikn,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dkn) {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namakredit=str_replace("'","''",$namakredit);
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_area)
							values
					  	 ('$acckredit','$ikn','$namakredit','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry','$iarea')");
	}

	function insertgldebet($accdebet,$ikn,$namadebet,$fdebet,$iarea,$vjumlah,$dkn,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$eremark=str_replace("'","''",$eremark);
		$namadebet=str_replace("'","''",$namadebet);
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ikn','$accdebet','$dkn','$namadebet','$fdebet',$vjumlah,'$iarea','$dkn','$eremark','$dentry')");
	}

	function insertglkredit($acckredit,$ikn,$namakredit,$fdebet,$iarea,$vjumlah,$dkn,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$eremark=str_replace("'","''",$eremark);
		$namakredit=str_replace("'","''",$namakredit);
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ikn','$acckredit','$dkn','$namakredit','$fdebet','$vjumlah','$iarea','$dkn','$eremark','$dentry')");
	}

	function updatekk($ikn,$iarea,$iperiode){
		$this->db->query("update tm_kk set f_posting='t' where i_kk='$ikn' and i_area='$iarea' and i_periode='$iperiode'");
	}

	function updatesaldodebet($accdebet,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet+$vjumlah, v_saldo_akhir=v_saldo_akhir+$vjumlah
						  where i_coa='$accdebet' and i_periode='$iperiode'");
	}

	function updatesaldokredit($acckredit,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vjumlah, v_saldo_akhir=v_saldo_akhir-$vjumlah
						  where i_coa='$acckredit' and i_periode='$iperiode'");
	}

	function namaacc($icoa){
		$this->db->select(" e_coa_name from tr_coa where i_coa='$icoa' ",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $tmp){
				$xxx=$tmp->e_coa_name;
			}
			return $xxx;
		}
  }
}

/* End of file Mmaster.php */