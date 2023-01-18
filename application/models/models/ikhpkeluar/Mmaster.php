<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function baca($ikhp){
		$this->db->select(" * from tm_ikhp a, tr_area b, tr_ikhp_type c 
                        where a.i_ikhp=$ikhp and a.i_area=b.i_area
                        and a.i_ikhp_type=c.i_ikhp_type",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
	} 
	
	function insert($iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro){
    	$this->db->set(
    		array(
				'i_area'			=> $iarea,
				'd_bukti'			=> $dbukti,
				'i_bukti'			=> $ibukti,
				'i_coa'				=> $icoa,
				'i_ikhp_type'		=> $iikhptype,
				'v_terima_tunai'	=> $vterimatunai,
				'v_terima_giro'		=> $vterimagiro,
				'v_keluar_tunai'	=> $vkeluartunai,
				'v_keluar_giro'		=> $vkeluargiro
    		)
    	);
    	
    	$this->db->insert('tm_ikhp');
	}
	
    function update($iikhp,$iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro){
    	$this->db->set(
    		array(
				'i_area'			=> $iarea,
				'd_bukti'			=> $dbukti,
				'i_bukti'			=> $ibukti,
				'i_coa'				=> $icoa,
				'i_ikhp_type'		=> $iikhptype,
				'v_terima_tunai'	=> $vterimatunai,
				'v_terima_giro'		=> $vterimagiro,
				'v_keluar_tunai'	=> $vkeluartunai,
				'v_keluar_giro'		=> $vkeluargiro
    		)
    	);
    	$this->db->where('i_ikhp',$iikhp);
    	$this->db->update('tm_ikhp');
    }
}

/* End of file Mmaster.php */
