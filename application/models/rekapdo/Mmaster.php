<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function data($dfrom,$dto,$isupplier,$total){
		$datatables = new Datatables(new CodeigniterAdapter);
		$tahun = date('Y');
    	if($isupplier == 'AS'){
			$datatables->query("select row_number() over(order by idtap) as i,idtap, ddtap, cancel, spb, spbold, dtapc, supplier, area, spmb, ddo, ido, iop, istore, istoreloc, dspmb, spmbold, total
								from(select distinct f.i_dtap as idtap, f.d_dtap as ddtap, a.f_do_cancel as cancel,  c.i_spb as spb, c.i_spb_old as spbold, g.f_dtap_cancel as dtapc, a.i_supplier as supplier, a.i_area as area, 
								d.i_spmb as spmb, a.d_do as ddo, a.i_do as ido, a.i_op as iop, d.i_store as istore, d.i_store_location as istoreloc, d.d_spmb as dspmb,d.i_spmb_old as spmbold, '$total' AS total from tr_supplier e,
								tm_op b left join tm_spb c on(b.i_reff=c.i_spb and b.i_area=c.i_area) 
								left join tm_spmb d on(b.i_reff=d.i_spmb and b.i_area=d.i_area),
								tm_do a left join tm_dtap_item f on(a.i_do=f.i_do)
								left join tm_dtap g on (f.i_dtap=g.i_dtap and f.i_area=g.i_area and f.i_supplier=g.i_supplier 
								and g.f_dtap_cancel='f')
								where a.d_do >= to_date('$dfrom','dd-mm-yyyy') and a.d_do <= to_date('$dto','dd-mm-yyyy')
								and a.i_op=b.i_op and a.i_area=b.i_area and a.i_supplier=e.i_supplier
								and a.i_area <> '00'
								and not d.i_spmb isnull
								and a.i_do not in(select substr(e_remark, 9, 15) from tm_sjp_item where d_sjp >= to_date('01-01-$tahun','dd-mm-yyyy') and e_remark like '%Dari DO DO%' )
								order by a.i_supplier) as data");
		}else{
			$datatables->query("select row_number() over(order by idtap) as i,idtap, ddtap, cancel, spb, spbold, dtapc, supplier, area, spmb, ddo, ido, iop, istore, istoreloc, dspmb, spmbold, total
								from(select distinct f.i_dtap as idtap, f.d_dtap as ddtap, a.f_do_cancel as cancel,  c.i_spb as spb, c.i_spb_old as spbold, g.f_dtap_cancel as dtapc, a.i_supplier as supplier, a.i_area as area, 
								d.i_spmb as spmb, a.d_do as ddo, a.i_do as ido, a.i_op as iop, d.i_store as istore, d.i_store_location as istoreloc, d.d_spmb as dspmb,d.i_spmb_old as spmbold,'$total' AS total from tr_supplier e,
								tm_op b left join tm_spb c on(b.i_reff=c.i_spb and b.i_area=c.i_area) 
								left join tm_spmb d on(b.i_reff=d.i_spmb and b.i_area=d.i_area),
								tm_do a left join tm_dtap_item f on(a.i_do=f.i_do)
								left join tm_dtap g on (f.i_dtap=g.i_dtap and f.i_area=g.i_area and f.i_supplier=g.i_supplier 
								and g.f_dtap_cancel='f')
								where a.d_do >= to_date('$dfrom','dd-mm-yyyy') and a.d_do <= to_date('$dto','dd-mm-yyyy')
								and a.i_op=b.i_op and a.i_area=b.i_area and a.i_supplier=e.i_supplier and a.i_supplier='$isupplier'
								and a.i_area <> '00'
								and not d.i_spmb isnull
								and a.i_do not in(select substr(e_remark, 9, 15) from tm_sjp_item where d_sjp >= to_date('01-01-$tahun','dd-mm-yyyy') and e_remark like '%Dari DO DO%' )
								order by a.i_supplier) as data");
		}
        
		$datatables->add('action', function ($data) {
			$i_do    = trim($data['ido']);
			$i_supplier    = trim($data['supplier']);
			$d_do    = trim($data['ddo']);
			$i_op    = trim($data['iop']);
			$i_area    = trim($data['area']);
			$i_spmb    = trim($data['spmb']);
			$i_store    = trim($data['istore']);
			$i_store_location    = trim($data['istoreloc']);
			$d_spmb    = trim($data['dspmb']);
			$i_spmb_old    = trim($data['spmbold']);
			$i_dtap    = trim($data['idtap']);
			$d_dtap    = trim($data['ddtap']);
			$f_do_cancel    = trim($data['cancel']);
			$i_spb    = trim($data['spb']);
			$i_spb_old    = trim($data['spbold']);
			$f_dtap_cancel    = trim($data['dtapc']);
			$total    = trim($data['total']);
			$i    = trim($data['i']);
			$data       = '';
			$data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\">&nbsp;&nbsp;&nbsp;&nbsp;
                		<label class=\"custom-control custom-checkbox\">
						<input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
						<input type='hidden' name='i_supplier".$i."' id='i_supplier".$i."' value='$i_supplier'>
						<input type='hidden' name='i_area".$i."' id='i_area".$i."' value='$i_area'>
						<input type='hidden' name='i_spmb".$i."' id='i_spmb".$i."' value='$i_spmb'>
						<input type='hidden' name='d_do".$i."' id='d_do".$i."' value='$d_do'>
						<input type='hidden' name='i_do".$i."' id='i_do".$i."' value='$i_do'>
						<input type='hidden' name='i_op".$i."' id='i_op".$i."' value='$i_op'>
						<input type='hidden' name='i_store".$i."' id='i_store".$i."' value='$i_store'>
						<input type='hidden' name='i_storeloc".$i."' id='i_storeloc".$i."' value='$i_store_location'>
						<input type='hidden' name='d_spmb".$i."' id='d_spmb".$i."' value='$d_spmb'>
						<input type='hidden' name='i_spmb_old".$i."' id='i_spmb_old".$i."' value='$i_spmb_old'>
                		<span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>";
			return $data;
        });

        $datatables->edit('ddo', function ($data) {
        $ddo = $data['ddo'];
        if($ddo == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($ddo) );
        }
		});
		
		$datatables->hide('istore');
		$datatables->hide('istoreloc');
		$datatables->hide('dspmb');
		$datatables->hide('spmbold');
		$datatables->hide('total');
		$datatables->hide('idtap');
		$datatables->hide('ddtap');
		$datatables->hide('cancel');
		$datatables->hide('spb');
		$datatables->hide('spbold');
		$datatables->hide('dtapc');

        return $datatables->generate();
	}

	public function total(){
		return $this->db->query("select distinct a.i_supplier, a.i_area, d.i_spmb, a.d_do, a.i_do, a.i_op, d.i_store, d.i_store_location, d.d_spmb,d.i_spmb_old from tr_supplier e,
								tm_op b left join tm_spb c on(b.i_reff=c.i_spb and b.i_area=c.i_area) 
								left join tm_spmb d on(b.i_reff=d.i_spmb and b.i_area=d.i_area),
								tm_do a left join tm_dtap_item f on(a.i_do=f.i_do)
								left join tm_dtap g on (f.i_dtap=g.i_dtap and f.i_area=g.i_area and f.i_supplier=g.i_supplier 
								and g.f_dtap_cancel='f')
								where
								a.i_op=b.i_op and a.i_area=b.i_area and a.i_supplier=e.i_supplier
								and a.i_area <> '00'
								and not d.i_spmb isnull
								and a.i_do not in(select substr(e_remark, 9, 15) from tm_sjp_item where e_remark like '%Dari DO DO%' )
								order by a.i_supplier",false);
	}

	function bacadetail($isjp, $iarea, $ispmb){
		$this->db->select("a.i_sjp,a.d_sjp,a.i_area,
                       a.i_product,a.i_product_grade,a.i_product_motif,a.n_quantity_order,
                       a.n_quantity_deliver,a.v_unit_price,a.e_product_name,a.i_store,
                       a.i_store_location,a.i_store_locationbin,a.e_remark,
                       b.e_product_motifname from tm_sjp_item a, tr_product_motif b
				       where a.i_sjp = '$isjp' and a.i_area='$iarea' 
                       and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                       order by a.n_item_no", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function insertheader($ispmb, $dspmb, $iarea, $fop, $nprint){
    	$this->db->set(
    		array(
			'i_spmb'	=> $ispmb,
			'd_spmb'	=> $dspmb,
			'i_area'	=> $iarea,
			'f_op'		=> 'f',
			'n_print'	=> 0
    		)
    	);	
    	$this->db->insert('tm_spmb');
	}
	
    function insertdetail($ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$vunitprice,$iproductmotif,$eremark){
    	$this->db->set(
    		array(
					'i_spmb'			=> $ispmb,
					'i_product'			=> $iproduct,
					'i_product_grade'	=> $iproductgrade,
					'i_product_motif'	=> $iproductmotif,
					'n_order'			=> $norder,
					'v_unit_price'		=> $vunitprice,
					'e_product_name'	=> $eproductname,
					'e_remark'			=> $eremark
    		)
    	);
    	
    	$this->db->insert('tm_spmb_item');
    }

    function updateheader($ispmb, $dspmb, $iarea){
    	$this->db->set(
    		array(
			'd_spmb'	=> $dspmb,
			'i_area'	=> $iarea
    		)
    	);
    	$this->db->where('i_spmb',$ispmb);
    	$this->db->update('tm_spmb');
    }

    public function deletedetail($iproduct, $iproductgrade, $ispmb, $iproductmotif) {
		$this->db->query("DELETE FROM tm_spmb_item WHERE i_spmb='$ispmb'
										and i_product='$iproduct' and i_product_grade='$iproductgrade' 
										and i_product_motif='$iproductmotif'");
		return TRUE;
    }
	
    public function delete($ispmb) {
		$this->db->query('DELETE FROM tm_spmb WHERE i_spmb=\''.$ispmb.'\'');
		$this->db->query('DELETE FROM tm_spmb_item WHERE i_spmb=\''.$ispmb.'\'');
		return TRUE;
	}
	
    function bacasemua(){
		$this->db->select("* from tm_spmb order by i_spmb desc",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function bacaspmb($storelocation,$area,$num,$offset){
	if($offset=='') $offset=0;
    	if($storelocation=='PB' || $area=='PB'){
			  $this->db->select("distinct(a.*) from tm_spmb a, tm_spmb_item b
								where a.i_area='$area' and a.f_spmb_cancel='f'
								and a.i_spmb=b.i_spmb and a.i_area=b.i_area and a.f_spmb_acc='t'
								and a.f_spmb_close='f' and a.f_spmb_consigment='t'
								and (a.i_store !='' and a.i_store_location !='' and a.f_spmb_pemenuhan='true')
								order by a.i_spmb desc limit $num offset $offset",false);
    	}else{
			  $this->db->select("distinct(a.*) from tm_spmb a
 								inner join tm_spmb_item b on b.i_spmb=a.i_spmb and a.i_area=b.i_area
			  					left join tr_area c on c.i_area =a.i_area
								where c.i_store='$area' and a.f_spmb_cancel='f'
								and a.i_spmb=b.i_spmb and a.i_area=b.i_area and a.f_spmb_acc='t'
								and a.f_spmb_close='f' and a.f_spmb_consigment='f'
								and (a.i_store !='' and a.i_store_location !='' and a.f_spmb_pemenuhan='true')
								order by a.i_spmb desc limit $num offset $offset",false);

    	}
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function carispmb($storelocation,$cari,$area,$num,$offset){
		if($offset=='') $offset=0;
    	if($storelocation=='PB' || $area=='PB'){
			  $this->db->select("distinct(a.*) from tm_spmb a, tm_spmb_item b
								where a.i_area='$area' and a.f_spmb_cancel='f' and a.f_spmb_close='f'
								and a.i_spmb=b.i_spmb and a.i_area=b.i_area and a.f_spmb_acc='t'
								and (upper(a.i_spmb)like '%$cari%') and a.f_spmb_consigment='t'
								order by a.i_spmb desc limit $num offset $offset",false);
    	}else{
			  $this->db->select("distinct(a.*) from tm_spmb a, tm_spmb_item b
								where a.i_area='$area' and a.f_spmb_cancel='f' and a.f_spmb_close='f'
								and a.i_spmb=b.i_spmb and a.i_area=b.i_area and a.f_spmb_acc='t'
								and (upper(a.i_spmb)like '%$cari%') and a.f_spmb_consigment='f'
								order by a.i_spmb desc limit $num offset $offset",false);
    	}
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }
	
	function product($spmb){
		$query=$this->db->query("  select a.i_product as kode, a.i_product_motif as motif,
						        a.e_product_motifname as namamotif, b.n_acc as n_order, b.n_saldo as n_qty,
						        c.e_product_name as nama,c.v_product_retail as harga, b.i_product_grade as grade
						        from tr_product_motif a,tr_product_price c, tm_spmb_item b
						        where a.i_product=c.i_product 
						        and b.i_product_motif=a.i_product_motif
						        and c.i_product=b.i_product
								and c.i_price_group='00'
								and c.i_product_grade='A'
						        and b.i_spmb='$spmb' and b.n_deliver<b.n_acc order by b.i_product asc ",false);		
		
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
    function runningnumber(){
    	$query 	= $this->db->query("SELECT to_char(current_timestamp,'yymm') as c");
		$row   	= $query->row();
		$thbl	= $row->c;
		$th		= substr($thbl,0,2);
		$this->db->select(" max(substr(i_spmb,11,6)) as max from tm_spmb 
				  			where substr(i_spmb,6,2)='$th' ", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$nospmb  =$terakhir+1;
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
			return $nospmb;
		}
	}
	
    function runningnumbersj($iarea,$thbl){
		  $th	= substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
		  $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SJP'
                          and substr(e_periode,1,4)='$th' 
                          and i_area='$iarea' for update", false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
			    $terakhir=$row->max;
			  }
			  $nosj  =$terakhir+1;
        $this->db->query(" update tm_dgu_no 
                            set n_modul_no=$nosj
                            where i_modul='SJP'
                            and substr(e_periode,1,4)='$th' 
                            and i_area='$iarea'", false);
			  settype($nosj,"string");
			  $a=strlen($nosj);
			  while($a<4){
			    $nosj="0".$nosj;
			    $a=strlen($nosj);
			  }
        
			  $nosj  ="SJP-".$thbl."-".$iarea.$nosj;
			  return $nosj;
		  }else{
			  $nosj  ="0001";
			  $nosj  ="SJP-".$thbl."-".$iarea.$nosj;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJP','$iarea','$asal',1)");
			  return $nosj;
		  }
	}
	
    function insertsjheader($ispmb,$dspmb,$isj,$dsj,$iarea,$vspbnetto,$isjold){
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjentry	= $row->c;
    	$this->db->set(
    		array(
				'i_sjp'			=> $isj,
				'i_sjp_old'		=> $isjold,
				'i_spmb'		=> $ispmb,
				'd_spmb'		=> $dspmb,
				'd_sjp'			=> $dsj,
				'i_area'		=> $iarea,
				'v_sjp'		    => $vspbnetto,
				'd_sjp_entry'	=> $dsjentry,
				'f_sjp_cancel'	=> 'f'
    		)
    	);
    	
    	$this->db->insert('tm_sjp');
	}
	
    function insertsjheader2($ispb,$dspb,$isj,$dsj,$iarea,$vspbnetto,$isjold){
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjentry	= $row->c;
    	$this->db->set(
    		array(
				'i_sjp' 				=> $isj,
				'i_sjp_old'			=> $isjold,
				'i_spmb'				=> $ispb,
				'd_spmb'				=> $dspb,
				'd_sjp'				  => $dsj,
				'i_area'		  	=> $iarea,
				'v_sjp'		      => $vspbnetto,
				'd_sjp_entry'		=> $dsjentry,
				'f_sjp_cancel'	=> 'f'
    		)
    	);
    	
    	$this->db->insert('tm_nota');
	}    
	
    function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,$ndeliver,
			                $vunitprice,$ispmb,$dspmb,$isj,$dsj,$iarea,
			                $istore,$istorelocation,$istorelocationbin,$eremark,$i)
    {
      $th=substr($dsj,0,4);
      $bl=substr($dsj,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_sjp'			          => $isj,
				'd_sjp'			          => $dsj,
				'i_area'		          => $iarea,
				'i_product'       		=> $iproduct,
				'i_product_motif'   	=> $iproductmotif,
				'i_product_grade'   	=> $iproductgrade,
				'e_product_name'    	=> $eproductname,
				'n_quantity_order'  	=> $norder,
				'n_quantity_deliver'	=> $ndeliver,
				'v_unit_price'		    => $vunitprice,
				'i_store'         		=> $istore,
				'i_store_location'	  => $istorelocation,
				'i_store_locationbin'	=> $istorelocationbin, 
        'e_remark'            => $eremark,
        'e_mutasi_periode'    => $pr,
        'n_item_no'           => $i
    		)
    	);
    	
    	$this->db->insert('tm_sjp_item');
    }
    function updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea)
    {
	    $this->db->query(" update tm_spmb_item set n_deliver = n_deliver+$ndeliver, n_saldo=n_saldo-$ndeliver
			                   where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' and i_product_grade='$iproductgrade'
			                   and i_product_motif='$iproductmotif' ",false);
    }
    function nambihspmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea)
    {
	    $this->db->query(" update tm_spmb_item set n_deliver = n_deliver-$ndeliver, n_saldo=n_saldo+$ndeliver
			                   where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' and i_product_grade='$iproductgrade'
			                   and i_product_motif='$iproductmotif' ",false);
    }
    function updatesjheader($isj,$iarea,$isjold,$dsj,$vsjnetto)
    {
      $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjupdate= $row->c;
    	$this->db->set(
    		array(
				'i_sjp_old'	  => $isjold,
				'v_sjp'	      => $vsjnetto,
				'd_sjp'       => $dsj,
        'd_sjp_update'=> $dsjupdate

    		)
    	);
    	$this->db->where('i_sjp',$isj);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjp');
    }
    function searchsjheader($isjp,$iarea)
    {
		  return $this->db->query(" SELECT * FROM tm_sjp WHERE i_sjp='$isjp' AND i_area='$iarea' ");
	  }    
    public function deletesjdetail($ispmb, $isj, $iarea, $iproduct, $iproductgrade, $iproductmotif, $ndeliver) 
    {
      $cek=$this->db->query("select * from tm_sjp_item WHERE i_sjp='$isj' 
                          and i_area='$iarea'
										      and i_product='$iproduct' and i_product_grade='$iproductgrade' 
										      and i_product_motif='$iproductmotif'");
      if($cek->num_rows()>0)
      {
		    $this->db->query("DELETE FROM tm_sjp_item WHERE i_sjp='$isj'
                          and i_area='$iarea'
										      and i_product='$iproduct' and i_product_grade='$iproductgrade' 
										      and i_product_motif='$iproductmotif'");
      }
    }
    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                order by i_trans desc",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function inserttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )

                              ",false);
    }
    function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    }
    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $hasil='kosong';
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$hasil='ada';
			}
      return $hasil;
    }
    function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_git=n_mutasi_git+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,n_mutasi_git,f_mutasi_close)
                                values
                                (
                                  '$iproduct','00','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,0,0,0,0,0,0,0,0,$qsj,'f')
                              ",false);
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=$q_ak-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0-$ndeliver, 't'
                                )
                              ",false);
    }
    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname)
    {
      $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' 
                                    order by i_trans desc");
      if ($queri->num_rows() > 0){
    	  $row   		= $queri->row();
        $que 	= $this->db->query("SELECT current_timestamp as c");
	      $ro 	= $que->row();
	      $now	 = $ro->c;
        if($ntmp!=0 || $ntmp!=''){
          $query=$this->db->query(" 
                                  INSERT INTO tm_ic_trans
                                  (
                                    i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                    i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                    n_quantity_in, n_quantity_out,
                                    n_quantity_akhir, n_quantity_awal)
                                  VALUES 
                                  (
                                    '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                    '$eproductname', '$isj', '$now', $ntmp, 0, $row->n_quantity_akhir+$ntmp, $row->n_quantity_akhir
                                  )
                                ",false);
        }
      }
      if(isset($row->i_trans)){
        if($row->i_trans!=''){
          return $row->i_trans;
        }else{
          return 1;
        }
      }else{
        return 1;
      }
    }
    function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$qsj, n_mutasi_git=n_mutasi_git-$qsj, n_saldo_akhir=n_saldo_akhir+$qsj

                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function deletesjheader($isjp,$iarea)
	  {
		  $this->db->query(" delete from tm_sjp where i_sjp='$isjp' and i_area='$iarea' ",false);
	  }    
    function bacadetail2($i_do, $i_supplier){
		  $query = $this->db->query(" select * from tm_do_item where i_do = '$i_do' and i_supplier = '$i_supplier'", FALSE);
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }else{
		  	echo "<h1>Error Barang Tidak Ada dari Do ".$i_do."</h1>";
			die();
		  }
    }
    function bacadetail3($i_do, $i_supplier){
		$this->db->select(" * from tm_dofc_item where i_do = '$i_do' and i_supplier = '$i_supplier'", FALSE);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		  return $query->result();
		}else{
			echo "<h1>Error Barang Tidak Ada dari Do ".$i_do."</h1>";
		die();
		}
    }
}

/* End of file Mmaster.php */