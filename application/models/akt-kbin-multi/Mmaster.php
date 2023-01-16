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

    public function bacabank(){
        return $this->db->order_by('i_bank','ASC')->get('tr_bank')->result();
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

    function baca($ikk,$iperiode,$iarea){
		$this->db->select("	a.i_kendaraan from tm_kk a 
							inner join tr_area b on(a.i_area=b.i_area)
							where a.i_periode='$iperiode' and a.i_kk='$ikk' and a.i_area='$iarea'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $tmp){
				$xxx=$tmp->i_kendaraan;
			}
		}else{
			$xxx='';
		}
		if(trim($xxx)==''){
			$this->db->select("	a.*, b.e_area_name, '' as e_pengguna from tm_kk a, tr_area b 
								where a.i_area=b.i_area and a.i_periode='$iperiode' and a.i_kk='$ikk' and a.i_area='$iarea'",false);
		}else{
			$this->db->select("	a.*, b.e_area_name
													from tm_kk a, tr_area b, tr_kendaraan c
													where a.i_area=b.i_area and a.i_kendaraan=c.i_kendaraan and a.i_periode=c.i_periode
													and a.i_periode='$iperiode' and a.i_kk='$ikk' and a.i_area='$iarea'",false);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }
    public function getdetailcoa($icoa){
        return $this->db->query("
            SELECT
                e_coa_name
            FROM
                tr_coa
            WHERE
                i_coa = '$icoa'
                AND (NOT i_coa LIKE '110-2%')
        ", FALSE);
    }

	/*function bacaarea($num,$offset,$area1,$area2,$area3,$area4,$area5){
        if($area1=='00'){
	    	  $this->db->select("* from tr_area order by i_area", false)->limit($num,$offset);
        }else{
	    	  $this->db->select("* from tr_area where i_area = '$area1' or i_area = '$area2' or i_area = '$area3'
	    					     or i_area = '$area4' or i_area = '$area5' order by i_area", false)->limit($num,$offset);
        }
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }*/

	function cariarea($cari,$num,$offset,$area1,$area2,$area3,$area4,$area5){
        if($area1=='00'){
	    	  $this->db->select("i_area, e_area_name from tr_area where (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%') order by i_area ", FALSE)->limit($num,$offset);
        }else{
	    	  $this->db->select("i_area, e_area_name from tr_area where (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%')
	    					     and (i_area = '$area1' or i_area = '$area2' or i_area = '$area3'
	    					     or i_area = '$area4' or i_area = '$area5') order by i_area ", FALSE)->limit($num,$offset);
        }
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
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
                (UPPER(i_coa) LIKE '%$cari%'
                OR UPPER(e_coa_name) LIKE '%$cari%')
                AND (NOT i_coa LIKE '110-2%')
        ", FALSE);
    }

	function bacakendaraan($cari,$area,$periode,$num,$offset){
        if($cari=='sikasep'){
	    	  $this->db->select(" * from tr_kendaraan a
	    						inner join tr_kendaraan_jenis b on (a.i_kendaraan_jenis=b.i_kendaraan_jenis)
	    						inner join tr_kendaraan_bbm c on(a.i_kendaraan_bbm=c.i_kendaraan_bbm)
	    						where a.i_area='$area' and a.i_periode='$periode'
	    					    order by a.i_kendaraan",false)->limit($num,$offset);
        }else{
	    	  $this->db->select(" * from tr_kendaraan a
	    						inner join tr_kendaraan_jenis b on (a.i_kendaraan_jenis=b.i_kendaraan_jenis)
	    						inner join tr_kendaraan_bbm c on(a.i_kendaraan_bbm=c.i_kendaraan_bbm)
	    						where a.i_area='$area' and a.i_periode='$periode'
                                and (upper(i_kendaraan) like '%$cari%' or upper(e_pengguna) like '%$cari%')
	    						order by a.i_kendaraan",false)->limit($num,$offset);
        }
	    $query = $this->db->get();
	    if ($query->num_rows() > 0){
	    	return $query->result();
	    }
    }

	function carikendaraan($area,$periode,$cari,$num,$offset){
		$this->db->select(" * from tr_kendaraan a
					    inner join tr_kendaraan_jenis b on (a.i_kendaraan_jenis=b.i_kendaraan_jenis)
					    inner join tr_kendaraan_bbm c on(a.i_kendaraan_bbm=c.i_kendaraan_bbm)
					    where (upper(a.i_kendaraan) like '%$cari%' or upper(a.e_pengguna) like '%$cari%')
					    and a.i_area='$area' and a.i_periode='$periode'
					    order by a.i_kendaraan",false)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function insert($iareax,$ikb,$iperiode,$icoa,$vkb,$dkb,$ecoaname,$edescription,$fdebet){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	            => $iareax,
				'i_kb'	            	=> $ikb,				
				'i_periode'         	=> $iperiode,
				'i_coa'	            	=> $icoa,
				'v_kb'		            => $vkb,
				'd_kb'		            => $dkb,
				'e_coa_name'	        => $ecoaname,
				'e_description'	        => $edescription,
				'd_entry'           	=> $dentry,
				'f_debet'	            => $fdebet,
				'd_bukti'				=> $dkb
    		)
    	);
    	$this->db->insert('tm_kb');
    }

    function update($iarea,$ikk,$iperiode,$icoa,$ikendaraan,$vkk,$dkk,$ecoaname,$edescription,$ejamin,$ejamout,$nkm,$etempat,$fdebet,$dbukti,$enamatoko,$epengguna,$ibukti){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dupdate= $row->c;
    	$this->db->set(
    		array(
				'i_kendaraan'	        => $ikendaraan,
				'i_coa'		            => $icoa,
				'v_kk'		            => $vkk,
				'i_bukti_pengeluaran'	=> $ibukti,
				'd_kk'		            => $dkk,
				'e_coa_name'	        => $ecoaname,
				'e_description'	        => $edescription,
				'e_jam_in'	            => $ejamin,
				'e_jam_out'	            => $ejamout,
				'n_km'		            => $nkm,
				'e_tempat'	            => $etempat,
				'd_update'	            => $dupdate,
				'd_bukti'	            => $dbukti,
				'f_debet'	            => $fdebet,
				'e_nama_toko'	        => $enamatoko,
				'e_pengguna'	        => $epengguna
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
				'i_rv'	     => $irv,
				'i_area'	 => $iarea,
				'i_periode'  => $iperiode,
				'i_coa' 	 => $icoa,
				'd_rv'		 => $drv,
				'v_rv'		 => $tot,
				'd_entry'    => $dentry,
				'i_rv_type'  => $irvtype
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
				'i_area'	          => $iarea,
				'i_rv'	              => $irv,
				'i_coa'               => $icoa,
				'e_coa_name'	      => $ecoaname,
				'v_rv'		          => $vrv,
				'e_remark'    	      => $edescription,
				'i_kk'                => $ikk,
				'i_rv_type'           => $irvtype,
				'i_area_kb'           => $iareax
    		)
    	);
    	$this->db->insert('tm_rv_item');
    }

	function runningnumberrv($th,$bl,$iarea,$irvtype){
		$this->db->select(" max(substr(i_rv,9,8)) as max 
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
			$norv  ="000001";
			$norv  ="RV-".$th.$bl."-".$iarea.$norv;
			return $norv;
		}
    }

	function runningnumberkb($th,$bl,$iarea){
		$this->db->select(" max(substr(i_kb,9,5)) as max from tm_kb where substr(i_kb,4,2)='$th' and substr(i_kb,6,2)='$bl'", false);
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

	function bacasaldo($area,$tanggal,$coa){	    
		$tmp = explode("-", $tanggal);
		$thn	= $tmp[0];
		$bln	= $tmp[1];
		$tgl 	= $tmp[2];
		$dsaldo	= $thn."/".$bln."/".$tgl;
		$dtos	= $this->mmaster->dateAdd("d",-1,$dsaldo);
		$tmp1 	= explode("-", $dtos,strlen($dtos));
		$th	= $tmp1[0];
		$bl	= $tmp1[1];
		$dt	= $tmp1[2];
		$dtos	= $th.$bl;
		$this->db->select(" v_saldo_awal from tm_coa_saldo where i_periode='$dtos' and i_coa='$coa' ",false);
		$query = $this->db->get();
		$saldo=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$saldo=$row->v_saldo_awal;
			}
		}
		$this->db->select(" sum(b.v_kb) as v_kb from tm_pv x, tm_pv_item z, tm_kb b
							where x.i_pv=z.i_pv and x.i_area=z.i_area and x.i_pv_type=z.i_pv_type and x.i_pv_type='01'
							and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='t' 
							and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode",false);
		$query = $this->db->get();
		$kredit=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$kredit=$row->v_kb;
			}
		}
		$this->db->select(" sum(b.v_kb) as v_kb from tm_rv x, tm_rv_item z, tm_kb b
							where x.i_rv=z.i_rv and x.i_area=z.i_area and x.i_rv_type=z.i_rv_type and x.i_rv_type='01'
							and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='f' 
							and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode",false);
		$query = $this->db->get();
		$debet=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$debet=$row->v_kb;
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

	function bacakgroup($cari,$num,$offset){
		$this->db->select(" * from tr_kk_group where upper(i_kk_group) like '%$cari%' or upper(e_kk_groupname) like '%$cari%' order by i_kk_group",false)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

	function bacapv($ipv,$iarea){
		$this->db->select(" * from tm_pv a, tm_pv_item b 
		                    where a.i_pv=b.i_pv and a.i_area=b.i_area and a.i_area='$iarea' 
		                    and a.i_pv_type= b.i_pv_type and a.i_pv='$ipv'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

	function inserttransheader(	$inota,$iarea,$eremark,$fclose,$dkn ){
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
    
	function inserttransitemkredit($acckredit,$ikn,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dkn){
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
			foreach($query->result() as $tmp)			
			{
				$xxx=$tmp->e_coa_name;
			}
			return $xxx;
		}
  }

	function bacarv($irv,$iarea){
		$this->db->select(" * from tm_rv a, tm_rv_item b 
		                    where a.i_rv=b.i_rv and a.i_area=b.i_area and a.i_area='$iarea' and a.i_rv='$irv' and a.i_rv_type='01' and b.i_rv_type='01'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    
    }
	function bacarvprint($cari,$area,$periode,$num,$offset){
        if($cari=='sikasep'){
	      	$this->db->select(" distinct on (a.i_rv) *, a.v_rv from tm_rv a, tm_rv_item b 
	      	                    where a.i_rv=b.i_rv and a.i_area=b.i_area and a.i_rv_type='01'  and b.i_rv_type='01'
	      	                    and a.i_area='$area' and a.i_periode='$periode'",false)->limit($num,$offset);
        }else{
	      	$this->db->select(" distinct on (a.i_rv) *, a.v_rv from tm_rv a, tm_rv_item b 
	      	                    where a.i_rv=b.i_rv and a.i_area=b.i_area and a.i_rv_type='01' and b.i_rv_type='01'
	      	                    and a.i_area='$area' and a.i_periode='$periode'
	      	                    and upper(a.i_rv) like '%$cari%'",false)->limit($num,$offset);
        }
  	    $query = $this->db->get();
  	    if ($query->num_rows() > 0){
  	    	return $query->result();
  	    }
    }
}

/* End of file Mmaster.php */