<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function cek($ikuk,$tahun){
		$this->db->select(" i_kuk from tm_kuk where i_kuk='$ikuk' and n_kuk_year='$tahun'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
    function insert($ikuk,$dkuk,$tahun,$ebankname,$isupplier,
				    $eremark,$vjumlah,$vsisa){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_kuk'				=> $ikuk,
				'i_supplier'		=> $isupplier,
				'd_kuk'				=> $dkuk,
				'd_entry'			=> $dentry,
				'e_bank_name'		=> $ebankname,
				'e_remark'			=> $eremark,
				'n_kuk_year'		=> $tahun,
				'v_jumlah'			=> $vjumlah,
				'v_sisa'			=> $vsisa
    		)
    	);
    	
    	$this->db->insert('tm_kuk');
    }
}

/* End of file Mmaster.php */
