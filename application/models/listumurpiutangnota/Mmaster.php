<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacaperiode($tahun,$bulan,$d_opname){
        $iperiode = $tahun.$bulan;
		$query = $this->db->query("select a.*, b.e_area_name from f_umur_piutang('$iperiode','$d_opname') a, tr_area b
                               		where a.i_area = b.i_area order by a.i_area, i_umur_piutang, i_customer");
		if ($query->num_rows() > 0){
		  return $query->result();
		}
    }
}

/* End of file Mmaster.php */