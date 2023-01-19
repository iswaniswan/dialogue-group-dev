<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacasemua($area1,$area2,$area3,$area4,$area5,$cari, $num,$offset,$dfrom,$dto)
    {
		$this->db->select(" a.*, b.e_area_name, c.e_customer_name from tm_nota a, tr_area b, tr_customer c
					        where a.i_customer=c.i_customer and a.i_area=b.i_area and (upper(a.i_nota) like '%$cari%' 
                            or upper(a.i_nota_old) like '%$cari%')
                            and a.i_faktur_komersial isnull and a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                            and a.d_nota <= to_date('$dto','dd-mm-yyyy')
          				    order by a.d_nota, a.i_area, a.i_nota",false)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

	function bacanota($inota,$ispb,$area){
		$this->db->select(" tm_nota.i_nota, tm_nota.d_nota, tm_nota.i_customer, tm_nota.i_salesman, tm_nota.i_area,
							tm_nota.n_nota_toplength, tm_nota.e_remark, tm_nota.f_cicil, tm_nota.i_nota_old, tm_nota.v_nota_netto,
							tm_nota.v_nota_discount1, tm_nota.v_nota_discount2, tm_nota.v_nota_discount3, tm_nota.v_nota_discount4,
							tm_nota.n_nota_discount1, tm_nota.n_nota_discount2, tm_nota.n_nota_discount3, tm_nota.n_nota_discount4,
							tm_nota.v_nota_gross, tm_nota.v_nota_discounttotal, tm_nota.n_price, tm_nota.v_nota_ppn, tm_nota.f_cicil,
							tm_nota.i_spb, tm_spb.i_spb_old, tm_nota.d_spb, tm_spb.v_spb, tm_spb.f_spb_consigment, tm_spb.i_spb_po,
							tm_spb.v_spb_discounttotal, tm_spb.f_spb_plusppn, tm_spb.f_spb_plusdiscount, tm_nota.i_sj, tm_nota.d_sj,
							tr_customer.e_customer_name, tm_nota.f_masalah, tm_nota.f_insentif,
							tr_customer_area.e_area_name,
							tr_salesman.e_salesman_name
				            from tm_nota 
				            left join tm_spb on (tm_nota.i_spb=tm_spb.i_spb and tm_nota.i_area=tm_spb.i_area and tm_spb.i_spb = '$ispb')
				            left join tm_promo on (tm_nota.i_spb_program=tm_promo.i_promo)
				            inner join tr_customer on (tm_nota.i_customer=tr_customer.i_customer)
				            inner join tr_salesman on (tm_nota.i_salesman=tr_salesman.i_salesman)
				            inner join tr_customer_area on (tm_nota.i_customer=tr_customer_area.i_customer)
				            where tm_nota.i_nota = '$inota' and tm_nota.i_area='$area'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }
	
	function bacadetailnota($inota,$area){
		$this->db->select("* from tm_nota_item
						   inner join tr_product_motif on (tr_product_motif.i_product_motif=tm_nota_item.i_product_motif
							and tr_product_motif.i_product=tm_nota_item.i_product)
						   where tm_nota_item.i_nota = '$inota' and tm_nota_item.i_area = '$area'  
						   order by tm_nota_item.n_item_no", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }
	
	function baca($inota,$area){
		$this->db->select(" tm_nota.i_nota, tm_nota.d_nota, tm_nota.i_customer, tm_nota.i_salesman, tm_nota.i_area,
							tm_nota.n_nota_toplength, tm_nota.e_remark, tm_nota.f_cicil, tm_nota.i_nota_old, tm_nota.v_nota_netto,
							tm_nota.v_nota_discount1, tm_nota.v_nota_discount2, tm_nota.v_nota_discount3, tm_nota.v_nota_discount4,
							tm_nota.n_nota_discount1, tm_nota.n_nota_discount2, tm_nota.n_nota_discount3, tm_nota.n_nota_discount4,
							tm_nota.v_nota_gross, tm_nota.v_nota_discounttotal, tm_nota.n_price, tm_nota.v_nota_ppn, tm_nota.f_cicil,
							tm_nota.i_spb, tm_spb.i_spb_old, tm_nota.d_spb, tm_spb.v_spb, tm_spb.f_spb_consigment, tm_spb.i_spb_po,
							tm_spb.v_spb_discounttotal, tm_spb.f_spb_plusppn, tm_spb.f_spb_plusdiscount, tm_nota.i_sj, tm_nota.d_sj,
							tr_customer.e_customer_name, tm_nota.f_masalah, tm_nota.f_insentif,
							tr_customer_area.e_area_name,
							tr_salesman.e_salesman_name
				            from tm_nota 
				            left join tm_spb on (tm_nota.i_spb=tm_spb.i_spb and tm_nota.i_area=tm_spb.i_area)
				            left join tm_promo on (tm_nota.i_spb_program=tm_promo.i_promo)
				            inner join tr_customer on (tm_nota.i_customer=tr_customer.i_customer)
				            inner join tr_salesman on (tm_nota.i_salesman=tr_salesman.i_salesman)
				            inner join tr_customer_area on (tm_nota.i_customer=tr_customer_area.i_customer)
				            where tm_nota.i_nota = '$inota' and tm_nota.i_area='$area'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }
	
	function bacadetail($inota,$area){
		$this->db->select("* from tm_nota_item
						   inner join tr_product_motif on (tr_product_motif.i_product_motif=tm_nota_item.i_product_motif
						   and tr_product_motif.i_product=tm_nota_item.i_product)
						   where tm_nota_item.i_nota = '$inota' and tm_nota_item.i_area = '$area'  
						   order by tm_nota_item.n_item_no", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }
	
	function updatespb($ispb,$iarea,$inota,$dnota,$vspbdiscounttotalafter,$vspbafter){
      $data = array(
		                'i_nota'				  => $inota,
		                'd_nota'				  => $dnota,
		                'v_spb_discounttotalafter'=> $vspbdiscounttotalafter, 
		                'v_spb_after'			  => $vspbafter
		               );
      $this->db->where('i_spb', $ispb);
      $this->db->where('i_area', $iarea);
      $this->db->update('tm_spb', $data); 
    }
	
	function updatesj($isj,$iarea,$inota,$dnota){
		$data = array(
					'i_nota'    => $inota,
					'd_nota'    => $dnota
					 );
    	$this->db->where('i_sj', $isj);
    	$this->db->where('i_area_from', $iarea);
    	$this->db->where('i_sj_type', '04');
		$this->db->update('tm_nota', $data); 
    }

	function runningnumber($area,$thbl){
      $th	= substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
	  $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='FK'
                        and substr(e_periode,1,4)='$th' for update", false);
	  $query = $this->db->get();
	  if ($query->num_rows() > 0){
		  foreach($query->result() as $row){
		    $terakhir=$row->max;
		  }
		  $nonota  =$terakhir+1;
      $this->db->query(" update tm_dgu_no 
                          set n_modul_no=$nonota
                          where i_modul='FK'
                          and substr(e_periode,1,4)='$th' ", false);
		  settype($nonota,"string");
		  $a=strlen($nonota);
		  while($a<6){
		    $nonota="0".$nonota;
		    $a=strlen($nonota);
		  }
		  $nonota  ="FK-".$thbl."-".$nonota;
		  return $nonota;
	  }else{
		  $nonota  ="000001";
		  $nonota  ="FK-".$thbl."-".$nonota;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('FK','$iarea','$asal',1)");
		  return $nonota;
	  }
  }
	function insertheader($inota,$ispb,$iarea,$icustomer,$isalesman,$ispbprogram,$ispbpo,$dspb,$dnota,
				  $djatuhtempo,$eremark,$fmasalah,$finsentif,$flunas,$nnotatoplength,$nnotadiscount1,
				  $nnotadiscount2,$nnotadiscount3,$vnotadiscount1,$vnotadiscount2,$vnotadiscount3,
				  $vnotadiscounttotal,$vnotanetto,$vsisa,$vspbdiscounttotal,$vspb,$fspbplusppn,
				  $fspbplusdiscount,$nprice,$vnotappn,$vnotagross,$vnotadiscount,$nnotadiscount4,
				  $vnotadiscount4,$fcicil,$inotaold,$isj,$dsj)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
			'i_nota'        			=> $inota,
			'i_spb'       				=> $ispb,
			'i_area'        			=> $iarea,
			'i_customer'      		=> $icustomer,
			'i_salesman'       		=> $isalesman,
 			'i_spb_program'   		=> $ispbprogram,
			'i_spb_po'		      	=> $ispbpo,
			'd_spb'       				=> $dspb,
			'd_nota'        			=> $dnota,
			'd_jatuh_tempo'   		=> $djatuhtempo,
			'e_remark'			      => $eremark,
			'f_masalah'     			=> $fmasalah,
 			'f_insentif'		      => $finsentif,
 			'f_lunas'       			=> $flunas,
 			'f_cicil'			        => $fcicil,
			'n_nota_toplength'	  => $nnotatoplength,
			'n_nota_discount1'	  => $nnotadiscount1,
			'n_nota_discount2'	  => $nnotadiscount2,
			'n_nota_discount3'	  => $nnotadiscount3,
			'n_nota_discount4'	  => $nnotadiscount4,
			'v_nota_discount1'	  => $vnotadiscount1,
			'v_nota_discount2'	  => $vnotadiscount2,
			'v_nota_discount3'	  => $vnotadiscount3,
			'v_nota_discount4'	  => $vnotadiscount4,
			'v_nota_discount'	    => $vnotadiscount,
			'v_nota_discounttotal'=> $vnotadiscounttotal,
			'v_nota_netto'    		=> $vnotanetto,
			'v_nota_gross'	    	=> $vnotagross,
			'v_nota_ppn'      		=> $vnotappn,
			'n_price'       			=> $nprice,
			'f_plus_ppn'      		=> $fspbplusppn,
			'f_plus_discount'   	=> $fspbplusdiscount,
			'v_sisa'        			=> $vsisa,
			'd_nota_entry'    		=> $dentry,
			'f_nota_cancel'   		=> 'f',
			'i_sj'	        			=> $isj,
			'd_sj'        				=> $dsj,
			'i_nota_old'      		=> $inotaold
    		)
    	);
    	$this->db->insert('tm_nota');

    	$this->db->set(
  		array(
      			'f_customer_first'		=> 'f'
        		)
    	);
      $this->db->where('i_customer',$icustomer);      
    	$this->db->update('tr_customer');

    }
    
	function insertdetail($inota,$iarea,$iproduct,$iproductgrade,$eproductname,$ndeliver,$vunitprice,$iproductmotif,$dnota)
    {
    	$this->db->set(
    		array(
					'i_nota'			=> $inota,
					'd_nota'			=> $dnota,
					'i_area'			=> $iarea,
					'i_product'			=> $iproduct,
					'i_product_grade'	=> $iproductgrade,
					'i_product_motif'	=> $iproductmotif,
					'n_deliver'			=> $ndeliver,
					'v_unit_price'		=> $vunitprice,
					'e_product_name'	=> $eproductname
    		)
    	);
    	
    	$this->db->insert('tm_nota_item');
    }
	function updateheader($inota,$iarea,$ispb,$icustomer,$isalesman,$ipricegroup,$ispbprogram,$ispbpo,$dspb,$dnota,
						  $djatuhtempo,$eremark,$fmasalah,$finsentif,$flunas,$nnotatoplength,$nnotadiscount1,
						  $nnotadiscount2,$nnotadiscount3,$nnotadiscount4,$vnotadiscount1,$vnotadiscount2,$vnotadiscount3,
						  $vnotadiscount4,$vnotadiscounttotal,$vnota,$vsisa,$vnotagross,$fcicil)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
			'f_masalah'				=> $fmasalah,
  			'f_insentif'			=> $finsentif,
  			'f_lunas'				=> $flunas,
  			'f_cicil'				=> $fcicil,
			'd_nota'				=> $dnota,
			'e_remark'				=> $eremark,
			'n_nota_discount1'		=> $nnotadiscount1,
			'n_nota_discount2'		=> $nnotadiscount2,
			'n_nota_discount3'		=> $nnotadiscount3,
			'n_nota_discount4'		=> $nnotadiscount4,
			'v_nota_discount1'		=> $vnotadiscount1,
			'v_nota_discount2'		=> $vnotadiscount2,
			'v_nota_discount3'		=> $vnotadiscount3,
			'v_nota_discount4'		=> $vnotadiscount4,
			'v_nota_discounttotal'	=> $vnotadiscounttotal,
			'v_nota_discount'		=> $vnotadiscounttotal,
			'v_nota_netto'			=> $vnota,
			'v_sisa'				=> $vsisa,
			'v_nota_gross'			=> $vnotagross,
			'd_nota_update'			=> $dentry
    		)
    	);
		$this->db->where('i_nota',$inota);
		$this->db->where('i_area',$iarea);
    	$this->db->update('tm_nota');
    }
	function updatedetail($inota,$iarea,$iproduct,$iproductgrade,$eproductname,$ndeliver,$vunitprice,$iproductmotif)
    {
    	$this->db->set(
    		array(
					'i_nota'			=> $inota,
					'i_product'			=> $iproduct,
					'i_product_grade'	=> $iproductgrade,
					'i_product_motif'	=> $iproductmotif,
					'n_deliver'			=> $ndeliver,
					'v_unit_price'		=> $vunitprice,
					'e_product_name'	=> $eproductname
    		)
    	);
		$this->db->where('i_nota',$inota);
		$this->db->where('i_area',$iarea);
		$this->db->where('i_product',$iproduct);
		$this->db->where('i_product_grade',$iproductgrade);
		$this->db->where('i_product_motif',$iproductmotif);
    	$this->db->update('tm_nota_item');
    }

	function updatenota($isj,$inota,$iarea,$ifakturkomersial)
    {
    	$this->db->set(
    		array(
					'i_faktur_komersial'=> $ifakturkomersial
    		)
    	);
    	$this->db->where('i_sj',$isj);
    	$this->db->where('i_nota',$inota);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_nota');
    }
    function close($fakturfrom,$fakturto,$iseri)
    {
	    $query=$this->db->query("select i_sj, i_area from tm_nota where i_faktur_komersial >= '$fakturfrom' 
	                             and i_faktur_komersial <= '$fakturto' 
                               and not i_faktur_komersial isnull and f_nota_cancel='f' order by i_faktur_komersial");
      if($query->num_rows()>0){
        settype($iseri,"string");
        $satu =substr($iseri,0,14);
        $dua  =substr($iseri,14,5);
        foreach($query->result() as $row){
          	$quer   = $this->db->query("SELECT to_char(current_timestamp,'yyyy-mm-dd') as c");
          	$ro     = $quer->row();
          	$dentry = $ro->c;
          	settype($dua,"string");
          	$a=strlen($dua);
          	$dua=str_repeat("0",5-$a).$dua;
          	$iseri=$satu.$dua;
  	      	$this->db->query("update tm_nota set n_pajak_print=n_pajak_print+1, d_pajak_print='$dentry', d_pajak=d_nota, 
  	      	                  i_seri_pajak='$iseri' where i_sj='$row->i_sj' and i_area='$row->i_area'",false);
          	settype($dua,"integer");
          	$dua++;
        }
      }
    }
}

/* End of file Mmaster.php */
