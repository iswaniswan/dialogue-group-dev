<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function baca($ijurnal){
		$this->db->select(" * from tm_general_jurnal 
				   inner join tr_area on (tm_general_jurnal.i_area=tr_area.i_area)
				   where tm_general_jurnal.i_jurnal='$ijurnal'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
	}
	
    function bacadetail($ijurnal){
		$this->db->select("	* from tm_general_jurnalitem 
							inner join tr_coa on(tm_general_jurnalitem.i_coa=tr_coa.i_coa)				
						   	where tm_general_jurnalitem.i_jurnal='$ijurnal'
						   	order by tm_general_jurnalitem.f_debet desc", false);//and i_supplier='$isupplier' 
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function sumdebet($ijurnal){
		$this->db->select("	sum(v_mutasi_debet) as debet from tm_general_jurnalitem 
						   	where tm_general_jurnalitem.i_jurnal='$ijurnal'", false);//and i_supplier='$isupplier' 
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$tmp=$row->debet;
			}
			return $tmp;
		}
	}
	
    function sumkredit($ijurnal){
		$this->db->select("	sum(v_mutasi_kredit) as kredit from tm_general_jurnalitem 
						   	where tm_general_jurnalitem.i_jurnal='$ijurnal'", false);//and i_supplier='$isupplier' 
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$tmp=$row->kredit;
			}
			return $tmp;
		}
	}
	
    function insertheader($ijurnal,$iarea,$djurnal,$edescription,$vdebet,$vkredit,$fposting,$fclose){
	   	$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
					'i_jurnal'		=> $ijurnal,
					'd_jurnal' 		=> $djurnal,
					'e_description' => $edescription,
					'v_debet' 		=> $vdebet,
					'v_kredit' 		=> $vkredit,
					'f_posting' 	=> $fposting,
					'f_close' 		=> $fclose,
					'i_area'		=> $iarea,
					'd_entry'		=> $dentry

    		)
    	);
    	
    	$this->db->insert('tm_general_jurnal');
	}
	
    function insertdetail($ijurnal,$icoa,$ecoaname,$fdebet,$vdebet,$vkredit,$iarea,$irefference,$drefference){
    	$this->db->set(
    		array(
					'i_jurnal'			=>$ijurnal,
					'i_coa'				=>$icoa,
  					'e_coa_name'		=>$ecoaname,
  					'f_debet'			=>$fdebet,
  					'v_mutasi_debet'	=>$vdebet,
  					'v_mutasi_kredit'	=>$vkredit,
  					'i_area'			=>$iarea
    		)
    	);
    	
    	$this->db->insert('tm_general_jurnalitem');
	}
	
    function deleteheader($ijurnal){
		$this->db->query("delete from tm_general_jurnal where i_jurnal='$ijurnal'");
    }

    public function deletedetail($ijurnal, $icoa, $debet, $kredit) {
		$this->db->query("update tm_general_jurnal set v_debet=v_debet-$debet, v_kredit=v_kredit-$kredit WHERE i_jurnal='$ijurnal'");
		$this->db->query("DELETE FROM tm_general_jurnalitem WHERE i_jurnal='$ijurnal' and i_coa='$icoa'");
	}
	
    function cari($cari,$num,$offset){
		$this->db->select(" * from tm_ap where upper(i_ap) like '%$cari%' or upper(i_supplier) like '%$cari%'
							order by i_ap",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
	function runningnumberjurnal($thbl){
    $th			= substr($thbl,0,2);
    $bl			= substr($thbl,2,2);
		$this->db->select(" max(substr(i_jurnal,9,5)) as max from tm_general_jurnal where substr(i_jurnal,4,2)='$th'", false);
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
			$nogj  ="GJ-".$th.$bl."-".$nogj;
			return $nogj;
		}else{
			$nogj  ="00001";
			$nogj  ="GJ-".$th.$bl."-".$nogj;
			return $nogj;
		}
	}
	
	function inserttransheader(	$ijurnal,$iarea,$edescription,$fclose,$djurnal ){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_jurnal_transharian 
						 (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
						  	  values
					  	 ('$ijurnal','$iarea','$dentry','$edescription','$fclose','$djurnal','$djurnal')");
	}

	function inserttransitemdebet($acc,$ijurnal,$accname,$fdebet,$fposting,$iarea,$edescription,$vdebet,$djurnal){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry)
						  	  values
					  	 ('$acc','$ijurnal','$accname','$fdebet','$fposting','$vdebet','$djurnal','$djurnal','$dentry')");
	}

	function inserttransitemkredit($acc,$ijurnal,$accname,$fdebet,$fposting,$iarea,$edescription,$vkredit,$djurnal){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry)
						  	  values
					  	 ('$acc','$ijurnal','$accname','$fdebet','$fposting','$vkredit','$djurnal','$djurnal','$dentry')");
	}

	function insertgldebet($acc,$ijurnal,$accnamae,$fdebet,$iarea,$vdebet,$djurnal,$edescription){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ijurnal','$acc','$djurnal','$accnamae','$fdebet',$vdebet,'$iarea','$djurnal','$edescription','$dentry')");
	}

	function insertglkredit($acc,$ijurnal,$accname,$fdebet,$iarea,$vkredit,$djurnal,$edescription){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ijurnal','$acc','$djurnal','$accname','$fdebet','$vkredit','$iarea','$djurnal','$edescription','$dentry')");
	}

	function updatesaldodebet($accdebet,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet+$vjumlah, v_saldo_akhir=v_saldo_akhir+$vjumlah
						  where i_coa='$accdebet' and i_periode='$iperiode'");
	}

	function updatesaldokredit($acckredit,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vjumlah, v_saldo_akhir=v_saldo_akhir-$vjumlah
						  where i_coa='$acckredit' and i_periode='$iperiode'");
	}
}

/* End of file Mmaster.php */
