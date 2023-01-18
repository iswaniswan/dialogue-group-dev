<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function baca ($ispg,$iarea){
		$this->db->select("a.i_customer, a.i_area, a.i_spg, a.e_spg_name, b.e_area_name, c.e_customer_name");
		$this->db->from("tr_spg a");
		$this->db->join("tr_area b","a.i_area=b.i_area");
		$this->db->join("tr_customer c","a.i_customer=c.i_customer");
		$this->db->where("upper(a.i_spg) = '$ispg'");
		$this->db->where("a.i_area='$iarea'");
		return $this->db->get();
	}

	function bacadetail($inotapb,$icustomer){
		$this->db->select(" a.*, b.e_product_motifname from tm_notapb_item a, tr_product_motif b
					 		where a.i_notapb = '$inotapb' and a.i_customer='$icustomer' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
					 		order by a.n_item_no ", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
	function insertheader($iorderpb, $dorderpb, $iarea, $ispg, $icustomer){
      	$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_orderpb'       => $iorderpb,
      			'i_area'          => $iarea,
      			'i_spg'           => $ispg,
      			'i_customer'      => $icustomer,
      			'd_orderpb'       => $dorderpb,
      			'f_orderpb_cancel'=> 'f',
      			'd_orderpb_entry' => $dentry
    		));
    	
    	$this->db->insert('tm_orderpb');
	}
	
    function insertdetail($iorderpb,$iarea,$icustomer,$dorderpb,$iproduct,$iproductmotif,$iproductgrade,$nquantityorder,$nquantitystock,$i,$eproductname,$eremark){
      	$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_orderpb'       => $iorderpb,
          		'i_area'          => $iarea,
          		'i_customer'      => $icustomer,
          		'd_orderpb'       => $dorderpb,
          		'i_product'       => $iproduct,
          		'e_product_name'  => $eproductname,
          		'i_product_motif' => $iproductmotif,
          		'i_product_grade' => $iproductgrade,
          		'n_quantity_order'=> $nquantityorder,
          		'n_quantity_stock'=> $nquantitystock,
          		'd_orderpb_entry' => $dentry,
          		'e_remark'        => $eremark,
          		'n_item_no'       => $i
    	));
    	$this->db->insert('tm_orderpb_item');
	}
	function runningnumber($thbl){
		$th	= '20'.substr($thbl,0,2);
		$asal='20'.$thbl;
		$thbl=substr($thbl,0,2).substr($thbl,2,2);
		$this->db->select(" n_modul_no as max from tm_dgu_no 
						where i_modul='OPB'
						and substr(e_periode,1,4)='$th' for update", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$noopb  =$terakhir+1;
		  	$this->db->query(" update tm_dgu_no 
							  set n_modul_no=$noopb
							  where i_modul='OPB'
							  and substr(e_periode,1,4)='$th' ", false);
			settype($noopb,"string");
			$a=strlen($noopb);
			while($a<5){
			  $noopb="0".$noopb;
			  $a=strlen($noopb);
			}
			$noopb  ="OPB-".$thbl."-".$noopb;
			return $noopb;
		}else{
			$noopb  ="00001";
			$noopb  ="OPB-".$thbl."-".$noopb;
		  	$this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
							 values ('OPB','00','$asal',1)");
			return $noopb;
		}
	}
}

/* End of file Mmaster.php */
