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
                (UPPER(i_coa) LIKE '61%$cari%'
                OR UPPER(e_coa_name) LIKE '%$cari%')
                AND (i_coa like '61%')
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
                AND (i_coa like '61%')
        ", FALSE);
    }

    function baca($ikk,$iperiode,$iarea){
		$this->db->select("	a.i_kendaraan from tm_kk a 
							inner join tr_area b on(a.i_area=b.i_area)
							where a.i_periode='$iperiode' and a.i_kk='$ikk' and a.i_area='$iarea'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $tmp)			
			{
				$xxx=$tmp->i_kendaraan;
			}
		}else{
			$xxx='';
		}

		if(trim($xxx)==''){
			$this->db->select("	a.*, b.e_area_name, '' as e_pengguna from tm_kk a, tr_area b 
								where a.i_area=b.i_area and a.i_periode='$iperiode' and a.i_kk='$ikk' and a.i_area='$iarea'",false);
		}else{
			$this->db->select("	a.*, b.e_area_name, c.e_pengguna
								from tm_kk a, tr_area b, tr_kendaraan c
								where a.i_area=b.i_area and a.i_kendaraan=c.i_kendaraan and a.i_periode=c.i_periode
								and a.i_periode='$iperiode' and a.i_kk='$ikk' and a.i_area='$iarea'",false);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function insert($iarea,$ikk,$iperiode,$icoa,$vkk,$dkk,$dbukti,$ecoaname,$edescription,$fdebet){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	 		  => $iarea,
				'i_kk'		 		  => $ikk,				
				'i_periode'		  => $iperiode,
				'i_coa'				  => $icoa,
				'v_kk'		 	  	=> $vkk,
				'd_kk'			  	=> $dkk,
				'e_coa_name'	  => $ecoaname,
				'e_description'	=> $edescription,
				'd_entry'			  => $dentry,
				'd_bukti'			  => $dbukti,
				'f_debet'			  => $fdebet
    		)
    	);
    	$this->db->insert('tm_kk');
	}
	
    function update($iarea,$ikk,$iperiode,$icoa,$vkk,$dkk,$dbukti,$ecoaname,$edescription,$fdebet,$irvtype){
      $irv='';
      $vrv=0;
      $this->db->select(" i_rv, v_rv from tm_rv_item where i_kk='$ikk' and i_area='$iarea' and i_rv_type='$irvtype'", false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
			    $irv=$row->i_rv;
			    $vrv=$row->v_rv;
			  }
        $this->db->query(" update tm_rv set v_rv=v_rv-$vrv where i_rv='$irv' and i_area='$iarea' and i_rv_type='$irvtype'", false);
        $this->db->query(" delete from tm_rv_item where i_rv='$irv' and i_area='$iarea' and i_kk='$ikk' and i_rv_type='$irvtype'", false);
      	$this->db->set(
      		array(
			    'i_area'	            => $iarea,
			    'i_rv'	            	=> $irv,
			    'i_coa'              	=> $icoa,
			    'e_coa_name'	        => $ecoaname,
			    'v_rv'		            => $vkk,
			    'e_remark'    	      => $edescription,
			    'i_kk'                => $ikk,
			    'i_rv_type'           => $irvtype,
			    'i_area_kb'           => $iarea
      		)
      	);
      	$this->db->insert('tm_rv_item');
		    $quer 	= $this->db->query("SELECT current_timestamp as c");
		    $row   	= $quer->row();
		    $dupdate= $row->c;
      	$this->db->query(" update tm_rv set v_rv=v_rv+$vkk, d_update='$dupdate' where i_rv='$irv' and i_area='$iarea' and i_rv_type='$irvtype'", false);
	    } 
      $this->db->query("insert into th_jurnal_transharian select * from tm_jurnal_transharian 
                        where i_refference='$ikk' and i_area='$iarea'");
      $this->db->query("insert into th_jurnal_transharianitem select * from tm_jurnal_transharianitem 
                        where i_refference='$ikk' and i_area='$iarea'");
      $this->db->query("insert into th_general_ledger select * from tm_general_ledger
                        where i_refference='$ikk' and i_area='$iarea'");

      $quer 	= $this->db->query("SELECT i_coa, v_mutasi_debet, v_mutasi_kredit, to_char(d_refference,'yyyymm') as periode 
                                  from tm_general_ledger
                                  where i_refference='$ikk' and i_area='$iarea'");
  	  if($quer->num_rows()>0){
        foreach($quer->result() as $xx){
          $this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet-$xx->v_mutasi_debet, 
                            v_mutasi_kredit=v_mutasi_kredit-$xx->v_mutasi_kredit,
                            v_saldo_akhir=v_saldo_akhir-$xx->v_mutasi_debet+$xx->v_mutasi_kredit
                            where i_coa='$xx->i_coa' and i_periode='$xx->periode'");
        }
      }

      $this->db->query("delete from tm_jurnal_transharian where i_refference='$ikk' and i_area='$iarea'");
      $this->db->query("delete from tm_jurnal_transharianitem where i_refference='$ikk' and i_area='$iarea'");
      $this->db->query("delete from tm_general_ledger where i_refference='$ikk' and i_area='$iarea'");
      $query 	= $this->db->query("SELECT current_timestamp as c");
      $row   	= $query->row();
      $dupdate= $row->c;
      $this->db->set(
    		array(
				'i_coa'	  			=> $icoa,
				'v_kk'	  	 		=> $vkk,
				'd_kk'  			=> $dkk,
        		'd_bukti'			=> $dbukti,
				'e_coa_name'		=> $ecoaname,
				'e_description'		=> $edescription,
				'd_update'			=> $dupdate,
				'f_debet'			=> $fdebet
    		)
    	);
		$this->db->where('i_area',$iarea);
		$this->db->where('i_kk',$ikk);
		$this->db->where('i_periode',$iperiode);
    	$this->db->update('tm_kk');
	}
	
    function insertrv($irv,$iarea,$iperiode,$icoa,$drv,$tot,$eremark,$irvtype){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_rv'	            	=> $irv,
				'i_area'	            => $iarea,
				'i_periode'         	=> $iperiode,
				'i_coa' 	            => $icoa,
				'd_rv'		            => $drv,
				'v_rv'		            => $tot,
				'd_entry'           	=> $dentry,
				'i_rv_type'           => $irvtype
    		)
    	);
    	$this->db->insert('tm_rv');
	}
	
    function insertrvitem($irv,$iarea,$icoa,$ecoaname,$vrv,$edescription,$ikk,$irvtype,$iareax){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	            => $iarea,
				'i_rv'	            	=> $irv,
				'i_coa'              	=> $icoa,
				'e_coa_name'	        => $ecoaname,
				'v_rv'		            => $vrv,
				'e_remark'    	      => $edescription,
				'i_kk'                => $ikk,
				'i_rv_type'           => $irvtype,
				'i_area_kb'           => $iareax
    		)
    	);
    	$this->db->insert('tm_rv_item');
	}
	
	function runningnumberrv($th,$bl,$iarea,$irvtype){
		$this->db->select(" max(substr(i_rv,11,6)) as max 
		                    from tm_rv 
		                    where substr(i_rv,4,2)='$th' and substr(i_rv,6,2)='$bl' and i_area='$iarea'
		                    and i_rv_type='$irvtype'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$norv  =$terakhir+1;
			settype($norv,"string");
			$a=strlen($norv);
			while($a<6){
			  $norv="0".$norv;
			  $a=strlen($norv);
			}
			$norv  ="RV-".$th.$bl."-".$iarea.$norv;
			return $norv;
		}else{
			$nopv  ="000001";
			$nopv  ="RV-".$th.$bl."-".$iarea.$norv;
			return $norv;
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
	
	function bacasaldo($area,$periode,$tgl){
		$this->db->select(" v_saldo_awal from tm_coa_saldo
							where i_periode='$periode' and substr(i_coa,7,2)='$area' and substr(i_coa,1,6)='110-12' ",false);
		$query = $this->db->get();
		$saldo=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$saldo=$row->v_saldo_awal;
			}
		}
		$this->db->select(" sum(v_kk) as v_kk from tm_kk
							where i_periode='$periode' and i_area='$area'
							and d_kk<='$tgl' and f_debet='t' and f_kk_cancel='f'",false);
		$query = $this->db->get();
		$kredit=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$kredit=$row->v_kk;
			}
		}
	
		$this->db->select(" sum(v_kk) as v_kk from tm_kk
							where i_periode='$periode' and i_area='$area'
							and d_kk<='$tgl' and f_debet='f' and f_kk_cancel='f'",false);
		$query = $this->db->get();
		$debet=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$debet=$row->v_kk;
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
			foreach($query->result() as $row)
			{
				$nama=$row->e_area_name;
				return $nama;
			}
		}
	}
	
	function namaacc($icoa){
		$this->db->select(" e_coa_name from tr_coa where i_coa='$icoa' ",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $tmp)			
			{
				$xxx=$tmp->e_coa_name;
			}
			return $xxx;
		}
    }
	function carisaldo($icoa,$iperiode)
	{
		$query = $this->db->query("select * from tm_coa_saldo where i_coa='$icoa' and i_periode='$iperiode'");
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row;
		}	
	}

	function inserttransheader(	$inota,$iarea,$eremark,$fclose,$dkn ){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_jurnal_transharian 
						 (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
						  	  values
					  	 ('$inota','$iarea','$dentry','$eremark','$fclose','$dkn','$dkn')");
	}
	function inserttransitemdebet($accdebet,$ikn,$namadebet,$fdebet,$fposting,$iarea,$eremark,$vjumlah,$dkn)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry)
						  	  values
					  	 ('$accdebet','$ikn','$namadebet','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry')");
	}

	function inserttransitemkredit($acckredit,$ikn,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dkn){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry)
						  	  values
					  	 ('$acckredit','$ikn','$namakredit','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry')");
	}

	function insertgldebet($accdebet,$ikn,$namadebet,$fdebet,$iarea,$vjumlah,$dkn,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ikn','$accdebet','$dkn','$namadebet','$fdebet',$vjumlah,'$iarea','$dkn','$eremark','$dentry')");
	}

	function insertglkredit($acckredit,$ikn,$namakredit,$fdebet,$iarea,$vjumlah,$dkn,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
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

	function bacarv($irv,$iarea){
		$this->db->select(" * from tm_rv a, tm_rv_item b 
		                    where a.i_rv=b.i_rv and a.i_area=b.i_area and a.i_rv_type=b.i_rv_type
		                    and a.i_area='$iarea' and a.i_rv='$irv' and a.i_rv_type='00' order by b.i_kk",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	  }
	  
	function bacarvprint($cari,$area,$periode,$num,$offset){
    	if($cari=='sikasep'){
		  	$this->db->select(" distinct on (a.i_rv) * from tm_rv a, tm_rv_item b 
		  	                    where a.i_rv=b.i_rv and a.i_area=b.i_area and a.i_rv_type=b.i_rv_type and a.i_rv_type='00' 
		  	                    and a.i_area='$area' and a.i_periode='$periode' order by a.i_rv, b.i_kk",false)->limit($num,$offset);
    	}else{
		  	$this->db->select(" distinct on (a.i_rv) * from tm_rv a, tm_rv_item b 
		  	                    where a.i_rv=b.i_rv and a.i_area=b.i_area and a.i_rv_type=b.i_rv_type and a.i_rv_type='00'
		  	                    and a.i_area='$area' and a.i_periode='$periode'
		  	                    and upper(a.i_rv) like '%$cari%' order by a.i_rv, b.i_kk",false)->limit($num,$offset);
    	}
  		$query = $this->db->get();
  		if ($query->num_rows() > 0){
  			return $query->result();
  		}
  	}
}

/* End of file Mmaster.php */