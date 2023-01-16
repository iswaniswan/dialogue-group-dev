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
	
    function insertheader($inotapb, $dnotapb, $iarea, $ispg, $icustomer, $nnotapbdiscount, $vnotapbdiscount, $vnotapbgross){
    	$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    	array(
			'i_notapb'    		=> $inotapb,
      		'i_area'      		=> $iarea,
      		'i_spg'       		=> $ispg,
      		'i_customer'  		=> $icustomer,
      		'd_notapb'    		=> $dnotapb,
      		'n_notapb_discount' => $nnotapbdiscount,
      		'v_notapb_discount' => $vnotapbdiscount,
      		'v_notapb_gross'    => $vnotapbgross,
      		'f_notapb_cancel'   => 'f',
      		'd_notapb_entry'    => $dentry
    		)
    	);
    	$this->db->insert('tm_notapb');
	}
	
    function insertdetail($inotapb,$iarea,$icustomer,$dnotapb,$iproduct,$iproductmotif,$iproductgrade,$nquantity,$vunitprice,$i,$eproductname,$ipricegroupco,$eremark){
      	$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_notapb'        => $inotapb,
          		'i_area'          => $iarea,
          		'i_customer'      => $icustomer,
          		'd_notapb'        => $dnotapb,
          		'i_product'       => $iproduct,
          		'i_product_motif' => $iproductmotif,
          		'i_product_grade' => $iproductgrade,
          		'n_quantity'      => $nquantity,
          		'v_unit_price'    => $vunitprice,
          		'd_notapb_entry'  => $dentry,
          		'n_item_no'       => $i,
          		'e_product_name'  => $eproductname,
          		'i_price_groupco' => $ipricegroupco,
          		'e_remark'        => $eremark
    		)
    	);
    	$this->db->insert('tm_notapb_item');
    }

    function updateheader($ispmb, $dspmb, $iarea, $ispmbold, $eremark){
    	$this->db->set(
    		array(
			'd_spmb'	  => $dspmb,
			'i_spmb_old'=> $ispmbold,
			'i_area'	  => $iarea,
      		'e_remark'  => $eremark
    		));
    	$this->db->where('i_spmb',$ispmb);
    	$this->db->update('tm_spmb');
    }

    public function deletedetail($iproduct, $iproductgrade, $inotapb, $iarea, $icustomer, $iproductmotif) {
		  $this->db->query("DELETE FROM tm_notapb_item WHERE i_notapb='$inotapb' and i_product='$iproduct' and i_product_grade='$iproductgrade' 
						and i_product_motif='$iproductmotif' and i_customer='$icustomer'");
    }
	
    public function deleteheader($xinotapb, $iarea, $icustomer) {
		  $this->db->query("DELETE FROM tm_notapb WHERE i_notapb='$xinotapb' and i_area='$iarea' and i_customer='$icustomer'");
	}
	
    function bacasemua(){
		$this->db->select("* from tm_spmb order by i_spmb desc",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function bacaproduct($num,$offset,$cari,$cust){
		if($offset=='')
			$offset=0;
		  	$query=$this->db->query(" select b.i_product as kode, b.i_price_group, a.i_product_motif as motif,
						            a.e_product_motifname as namamotif, b.v_product_retail as harga,
						            c.e_product_name as nama
						            from tr_product_motif a,tr_product c, tr_product_priceco b, tr_customer_consigment d
						            where a.i_product=c.i_product and a.i_product=b.i_product 
						            and (upper(a.i_product) like '%$cari%' or upper(c.e_product_name) like '%$cari%')
						            and d.i_customer='$cust' and d.i_price_groupco=b.i_price_groupco and a.i_product_motif='00'
                                	order by c.i_product, a.e_product_motifname, b.i_price_group
                                	limit $num offset $offset",false);
		  	if ($query->num_rows() > 0){
			  return $query->result();
		  	}
    }

    function runningnumber($thbl){
      	$th	= '20'.substr($thbl,0,2);
      	$asal='20'.$thbl;
      	$thbl=substr($thbl,0,2).substr($thbl,2,2);
		$this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SPM'
                          and substr(e_periode,1,4)='$th' for update", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$terakhir=$row->max;
			}
			$nospmb  =$terakhir+1;
        	$this->db->query(" update tm_dgu_no 
        	                    set n_modul_no=$nospmb
        	                    where i_modul='SPM'
        	                    and substr(e_periode,1,4)='$th' ", false);
			settype($nospmb,"string");
			$a=strlen($nospmb);
			while($a<6){
			  $nospmb="0".$nospmb;
			  $a=strlen($nospmb);
			}
			$nospmb  ="SPMB-".$thbl."-".$nospmb;
			return $nospmb;
		}else{
			$nospmb  ="000001";
			$nospmb  ="SPMB-".$thbl."-".$nospmb;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SPM','00','$asal',1)");
			return $nospmb;
		}
	}
	
    function cari($cari,$num,$offset){
		$this->db->select(" * from tm_spmb where upper(i_spmb) like '%$cari%' 
							order by i_spmb",FALSE)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function qic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
	}
	
    function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode){
      $hasil='kosong';
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$hasil='ada';
			}
      return $hasil;
	}
	
    function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode){
      $query=$this->db->query(" 
                                UPDATE tm_mutasi_consigment
                                set n_mutasi_penjualan=n_mutasi_penjualan+$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                              ",false);
	}
	
    function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode,$q_aw,$q_ak){
      $query=$this->db->query(" 
                                insert into tm_mutasi_consigment
                                (
                                  i_product,i_product_motif,i_product_grade,i_customer,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_daripusat,n_mutasi_darilang,n_mutasi_penjualan,n_mutasi_kepusat,
                                  n_saldo_akhir,n_saldo_stockopname,f_mutasi_close, n_mutasi_git)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$icustomer','$emutasiperiode',$q_aw,0,0,$qsj,0,$q_ak-$qsj,0,'f',$qsj)
                              ",false);
	}
	
    function cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
	}
	
    function updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$q_ak){
      $query=$this->db->query(" 
                                UPDATE tm_ic_consigment set n_quantity_stock=$q_ak-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'",false);
	}
	
    function insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$qsj){
      $query=$this->db->query(" 
                                insert into tm_ic_consigment
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$icustomer', '$eproductname', 0-$qsj, 't'
                                )
                              ",false);
	}
	
	function cek_data($i_notapb, $i_customer){
		$data = $this->db->query("select * from tm_notapb where i_notapb = '$i_notapb' and i_area = 'PB' and i_customer = '$i_customer'");
		return $data;
	}
    
}

/* End of file Mmaster.php */
