<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacaperiode($tahun,$bulan,$d_opname){
        $iperiode = $tahun.$bulan;
		$query = $this->db->query("select * from f_umur_piutang_area('$iperiode','$d_opname')");
		if ($query->num_rows() > 0){
		  return $query->result();
		}
    }
}

/* End of file Mmaster.php */