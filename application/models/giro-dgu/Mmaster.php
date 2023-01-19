<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function insert($igiro,$isupplier,$ipv,$dgiro,$dpv,$dgiroduedate,$egirodescription,$egirobank,
				    $vjumlah,$vsisa){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
    			'i_giro' 			=> $igiro,
				'i_supplier' 		=> $isupplier,
				'i_pv' 				=> $ipv,
				'd_giro' 			=> $dgiro,
				'd_pv' 				=> $dpv,
				'd_giro_duedate' 	=> $dgiroduedate,
				'd_entry' 			=> $dentry,
				'e_giro_description'=> $egirodescription,
				'e_giro_bank' 		=> $egirobank,
				'v_jumlah' 			=> $vjumlah,
				'v_sisa' 			=> $vsisa
    		)
    	);
    	$this->db->insert('tm_giro_dgu');
	}
	
	function runningnumberpv($pvth){
		$this->db->select(" trim(to_char(count(i_pv)+1,'000000')) as no from tm_giro_dgu where to_char(d_pv,'yyyy')='$pvth'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row)
			{
			  $pv=$row->no;
			}
			return $pv;
		}else{
			$pv='000001';
			return $pv;
		}
	}
}

/* End of file Mmaster.php */
