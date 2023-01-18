<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("Select a.i_sjr, a.d_sjr, a.i_sjr_old, a.i_area, b.e_area_name, '$i_menu' as i_menu
                            from tm_sjr a, tr_area b, tm_sjr_item c
                            where a.i_area=b.i_area and a.i_sjr=c.i_sjr 
                            and a.d_sjr_receive is null and a.f_sjr_cancel='f'
                            and a.i_area=c.i_area", false);
		$datatables->add('action', function ($data) {
            $i_sjr    = trim($data['i_sjr']);
            $i_area    = trim($data['i_area']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"sjrreceive/cform/edit/$i_sjr/$i_area/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('d_sjr', function ($data) {
        $d_sjr = $data['d_sjr'];
        if($d_sjr == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($d_sjr) );
        }
        });

        $datatables->hide('i_menu');
        $datatables->hide('i_area');

        return $datatables->generate();
    }
    
    function baca($isjr,$iarea){
		$this->db->select(" distinct(c.i_store), a.*, b.e_area_name 
                        from tm_sjr a, tr_area b, tm_sjr_item c
					    where a.i_area=b.i_area and a.i_sjr=c.i_sjr 
                        and a.i_area=c.i_area
					    and a.i_sjr ='$isjr' and a.i_area='$iarea' ", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		  return $query->row();
		}
    }

    function bacadetail($isjr, $iarea){
		$this->db->select("a.i_sjr,a.d_sjr,a.i_area,a.i_product,a.i_product_grade,a.i_product_motif,
                       a.n_quantity_receive,a.n_quantity_retur,a.v_unit_price,a.e_product_name,
                       a.i_store,a.i_store_location,a.i_store_locationbin,a.e_remark,
                       b.e_product_motifname from tm_sjr_item a, tr_product_motif b
			           where a.i_sjr = '$isjr' and a.i_area='$iarea' 
                       and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                       order by a.n_item_no", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		  return $query->result();
		}
    }

    function insertheader($ispmb, $dspmb, $iarea, $fop, $nprint)
    {
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
    function insertdetail($ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$vunitprice,$iproductmotif,$eremark)
    {
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

    function updateheader($ispmb, $dspmb, $iarea)
    {
    	$this->db->set(
    		array(
			'd_spmb'	=> $dspmb,
			'i_area'	=> $iarea
    		)
    	);
    	$this->db->where('i_spmb',$ispmb);
    	$this->db->update('tm_spmb');
    }

    public function deletedetail($iproduct, $iproductgrade, $ispmb, $iproductmotif) 
    {
		  $this->db->query("DELETE FROM tm_spmb_item WHERE i_spmb='$ispmb'
										  and i_product='$iproduct' and i_product_grade='$iproductgrade' 
										  and i_product_motif='$iproductmotif'");
		  return TRUE;
    }
	
    public function delete($ispmb) 
    {
		  $this->db->query('DELETE FROM tm_spmb WHERE i_spmb=\''.$ispmb.'\'');
		  $this->db->query('DELETE FROM tm_spmb_item WHERE i_spmb=\''.$ispmb.'\'');
		  return TRUE;
    }
    function bacasemua()
    {
		  $this->db->select("* from tm_spmb order by i_spmb desc",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function bacaspmb($area,$num,$offset)
    {
		  if($offset=='') $offset=0;
		  $this->db->select("	distinct(a.*) from tm_spmb a, tm_spmb_item b
												  where a.i_area='$area' and a.f_spmb_cancel='f'
												  and a.i_spmb=b.i_spmb and a.i_area=b.i_area and a.f_spmb_acc='t'
												  and b.n_acc>b.n_deliver and a.f_spmb_close='f' and a.f_spmb_pemenuhan='t'
												  order by a.i_spmb desc limit $num offset $offset",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function carispmb($cari,$area,$num,$offset)
    {
		  if($offset=='') $offset=0;
		  $this->db->select("	distinct(a.*) from tm_spmb a, tm_spmb_item b
												  where a.i_area='$area' and a.f_spmb_cancel='f' and a.f_spmb_close='f'
												  and a.i_spmb=b.i_spmb and a.i_area=b.i_area and a.f_spmb_acc='t' and a.f_spmb_pemenuhan='t'
												  and b.n_acc>b.n_deliver and (upper(a.i_spmb)like '%$cari%')
												  order by a.i_spmb desc limit $num offset $offset",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function product($spmb)
    {
		  $query=$this->db->query("   select a.i_product as kode, a.i_product_motif as motif,
						                      a.e_product_motifname as namamotif, b.n_acc as n_order, b.n_acc-b.n_deliver as n_qty,
						                      c.e_product_name as nama,c.v_product_retail as harga, b.i_product_grade as grade
						                      from tr_product_motif a,tr_product_price c, tm_spmb_item b
						                      where a.i_product=c.i_product 
						                      and b.i_product_motif=a.i_product_motif
						                      and c.i_product=b.i_product
											            and c.i_price_group='00'
											            and c.i_product_grade='A'
						                      and b.i_spmb='$spmb' and b.n_deliver<b.n_acc order by b.n_item_no ",false);		
		
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

    function cari($cari,$num,$offset)
    {
		  $this->db->select(" * from tm_spmb where upper(i_spmb) like '%$cari%' 
					  order by i_spmb",FALSE)->limit($num,$offset);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function cariproduct($cari,$num,$offset)
    {
		if($offset=='')
			$offset=0;
		  $query=$this->db->query(" select a.i_product as kode, a.i_product_motif as motif,
								  a.e_product_motifname as namamotif, 
								  c.e_product_name as nama,c.v_product_mill as harga
								  from tr_product_motif a,tr_product c
								  where a.i_product=c.i_product
							     	and (upper(a.i_product) like '%$cari%' or upper(c.e_product_name) like '%$cari%')
								  order by a.e_product_motifname asc limit $num offset $offset",false);
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function bacaarea($num,$offset,$area1,$area2,$area3,$area4,$area5)
    {
			if($area1=='00' or $area2=='00' or $area3=='00' or $area4=='00' or $area5=='00'){
				$this->db->select("* from tr_area order by i_area", false)->limit($num,$offset);
			}else{
				$this->db->select("* from tr_area where i_area = '$area1' or i_area = '$area2' or i_area = '$area3'
									 or i_area = '$area4' or i_area = '$area5' order by i_area", false)->limit($num,$offset);
			}
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->result();
			}
    }
		function cariarea($cari,$num,$offset,$area1,$area2,$area3,$area4,$area5)
    {
			if($area1=='00' or $area2=='00' or $area3=='00' or $area4=='00' or $area5=='00'){
				$this->db->select("i_area, e_area_name from tr_area where (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%')
									 order by i_area ", FALSE)->limit($num,$offset);
			}else{
				$this->db->select("i_area, e_area_name from tr_area where (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%')
									 and (i_area = '$area1' or i_area = '$area2' or i_area = '$area3'
									 or i_area = '$area4' or i_area = '$area5') order by i_area ", FALSE)->limit($num,$offset);
			}
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function runningnumbersj($iarea,$thbl)
    {
		  $th	= substr($thbl,0,4);
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
		  $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='sjr'
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
                            where i_modul='sjr'
                            and substr(e_periode,1,4)='$th' 
                            and i_area='$iarea'", false);
			  settype($nosj,"string");
			  $a=strlen($nosj);
			  while($a<4){
			    $nosj="0".$nosj;
			    $a=strlen($nosj);
			  }
        
			  $nosj  ="sjr-".$thbl."-".$iarea.$nosj;
			  return $nosj;
		  }else{
			  $nosj  ="0001";
			  $nosj  ="sjr-".$thbl."-".$iarea.$nosj;
        $this->db->query(" update tm_dgu_no 
                            set n_modul_no=1
                            where i_modul='sjr'
                            and substr(e_periode,1,4)='$th' 
                            and i_area='$iarea'", false);
			  return $nosj;
		  }
    }
    function insertsjheader($ispmb,$dspmb,$isj,$dsj,$iarea,$vspbnetto,$isjold)
    {
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjentry	= $row->c;
    	$this->db->set(
    		array(
				'i_sjr'				=> $isj,
				'i_sjr_old'		=> $isjold,
				'i_spmb'			=> $ispmb,
				'd_spmb'			=> $dspmb,
				'd_sjr'				=> $dsj,
				'i_area'		  => $iarea,
				'v_sjr'		    => $vspbnetto,
				'd_sjr_entry'	=> $dsjentry,
				'f_sjr_cancel'=> 'f'
    		)
    	);
    	
    	$this->db->insert('tm_sjr');
    }
    function insertsjheader2($ispb,$dspb,$isj,$dsj,$iarea,$vspbnetto,$isjold)
    {
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjentry	= $row->c;
    	$this->db->set(
    		array(
				'i_sjr' 				=> $isj,
				'i_sjr_old'			=> $isjold,
				'i_spmb'				=> $ispb,
				'd_spmb'				=> $dspb,
				'd_sjr'				  => $dsj,
				'i_area'		  	=> $iarea,
				'v_sjr'		      => $vspbnetto,
				'd_sjr_entry'		=> $dsjentry,
				'f_sjr_cancel'	=> 'f'
    		)
    	);
    	
    	$this->db->insert('tm_nota');
    }    
    function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nretur,
			                      $vunitprice,$isj,$dsj,$iarea,
			                      $istore,$istorelocation,$istorelocationbin,$eremark,$i,$nreceive)
    {
      $th=substr($dsj,0,4);
      $bl=substr($dsj,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_sjr'			          => $isj,
				'd_sjr'			          => $dsj,
				'i_area'		          => $iarea,
				'i_product'       		=> $iproduct,
				'i_product_motif'   	=> $iproductmotif,
				'i_product_grade'   	=> $iproductgrade,
				'e_product_name'    	=> $eproductname,
				'n_quantity_retur'  	=> $nretur,
				'v_unit_price'		    => $vunitprice,
				'i_store'         		=> $istore,
				'i_store_location'	  => $istorelocation,
				'i_store_locationbin'	=> $istorelocationbin, 
        'e_remark'            => $eremark,
        'e_mutasi_periode'    => $pr,
        'n_item_no'           => $i,
        'n_quantity_receive'  => $nreceive
    		)
    	);
    	
    	$this->db->insert('tm_sjr_item');
    }
    function updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea)
    {
	    $this->db->query(" update tm_spmb_item set n_deliver = n_deliver+$ndeliver
			                   where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' and i_product_grade='$iproductgrade'
			                   and i_product_motif='$iproductmotif' ",false);
    }
    function updatesjheader($isj,$iarea,$dsjreceive,$vsjnetto,$vsjrec)
    {
      $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjupdate= $row->c;
    	$this->db->set(
    		array(
				'v_sjr_receive' => $vsjrec,
				'd_sjr_receive' => $dsjreceive,
        'd_sjr_update'  => $dsjupdate

    		)
    	);
    	$this->db->where('i_sjr',$isj);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjr');
    }
    function searchsjheader($isjr,$iarea)
    {
		  return $this->db->query(" SELECT * FROM tm_sjr WHERE i_sjr='$isjr' AND i_area='$iarea' ");
	  }    
    public function deletesjdetail($isj, $iarea, $iproduct, $iproductgrade, $iproductmotif) 
    {
		    $this->db->query("DELETE FROM tm_sjr_item WHERE i_sjr='$isj'
                          and i_area='$iarea'
										      and i_product='$iproduct' and i_product_grade='$iproductgrade' 
										      and i_product_motif='$iproductmotif'");
    }
    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'
                                order by i_trans desc",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'
                              ",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
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
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', $q_in, $q_out+$qsj, $q_ak-$qsj, $q_aw
                                )
                              ",false);
    }
    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $hasil='kosong';
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
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
                                set n_mutasi_bbk=n_mutasi_bbk+$qsj, n_saldo_akhir=n_saldo_akhir-$qsj, n_mutasi_git=n_mutasi_git-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
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
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,0,0,0,0,0,$qsj,0-$qsj,0,'f')
                              ",false);
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'
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
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0-$ndeliver, 't'
                                )
                              ",false);
    }
    function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	= $row->c;
      $query=$this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','AA','01','00', 
                                  '$eproductname', '$isj', '$now', $qsj, 0, $q_ak+$qsj, $q_ak
                                )
                              ",false);
    }
    function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_bbm=n_mutasi_bbm+$qsj, n_saldo_akhir=n_saldo_akhir+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if($emutasiperiodesj==$emutasiperiode){
        $query=$this->db->query(" 
                                  UPDATE tm_mutasi 
                                  set n_mutasi_bbk=n_mutasi_bbk+$qsj, n_mutasi_git=n_mutasi_git-$qsj,n_saldo_akhir=n_saldo_akhir-$qsj
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  and e_mutasi_periode='$emutasiperiodesj'
                                ",false);
      }else{
        $query=$this->db->query(" 
                                  UPDATE tm_mutasi 
                                  set n_mutasi_bbk=n_mutasi_bbk+$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  and e_mutasi_periode='$emutasiperiode'
                                ",false);
      }
    }
    function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qsj,0,0,0,$qsj,0,'f')
                              ",false);
      if($emutasiperiodesj==$emutasiperiode){
        $query=$this->db->query(" 
                                  UPDATE tm_mutasi 
                                  set n_mutasi_git=n_mutasi_git-$qsj
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  and e_mutasi_periode='$emutasiperiode'
                                ",false);
      }
    }
    function updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=$q_ak+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'
                              ",false);
    }
    function insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', 'AA', '01', '00', '$eproductname', $qsj, 't'
                                )
                              ",false);
    }
    function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $hasil='kosong';
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$hasil='ada';
			}
      return $hasil;
    }
    function deletesjheader($isjr,$iarea)
  	{
		  $this->db->query(" delete from tm_sjr where i_sjr='$isjr' and i_area='$iarea' ",false);
  	}    

    function bacaperiode($num,$offset,$cari)
    {
			  $this->db->select("	a.*, b.e_area_name from tm_sjr a, tr_area b
													  where a.i_area=b.i_area
													  and (upper(a.i_sjr) like '%$cari%' or upper(a.i_sjr_old) like '%$cari%')
													  and a.d_sjr_receive is null and a.f_sjr_cancel='f'
													  ORDER BY a.i_area, a.i_sjr desc",false)->limit($num,$offset);
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function bacaproduct($num,$offset,$cari)
    {
			$this->db->select("	a.i_product as kode, a.e_product_name as nama, b.v_product_retail as harga, 
                          c.i_product_motif as motif, c.e_product_motifname as namamotif
                          from tr_product a, tr_product_price b, tr_product_motif c
                          where a.i_product=b.i_product and b.i_price_group='00'
                          and (upper(a.i_product) like '%$cari%' or upper(a.e_product_name) like '%$cari%')
                          and a.i_product=c.i_product 
													ORDER BY a.e_product_name",false)->limit($num,$offset);
													#and a.i_product_status<>'4'
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname)
    {
      $queri 		= $this->db->query("sSELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='AA' and i_store_location='01' and i_store_locationbin='00' 
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
                                    '$iproduct','$iproductgrade','$iproductmotif','AA','01','00', 
                                    '$eproductname', '$isj', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir
                                  )
                                ",false);
        }
      }
    }
    function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbm=n_mutasi_bbm-$qsj, 
                                n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if($emutasiperiodesj==$emutasiperiode){
        $query=$this->db->query(" 
                                  UPDATE tm_mutasi set n_mutasi_git=n_mutasi_git+$qsj
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='AA' and i_store_location='01' and i_store_locationbin='00' and e_mutasi_periode='$emutasiperiodesj'
                                ",false);
      }
    }
    function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      if( ($qsj=='')||($qsj==null) ) $qsj=0;
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function updatesjdetail($iproduct,$iproductgrade,$iproductmotif,$isj,$dsj,$iarea,$nreceive,$ntmp)
    {
      $th=substr($dsj,0,4);
      $bl=substr($dsj,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
        'n_quantity_receive'  => $nreceive
    		)
    	);
      $this->db->where('i_sjr',$isj);
      $this->db->where('i_area',$iarea);
      $this->db->where('i_product',$iproduct);
      $this->db->where('i_product',$iproduct);
      $this->db->where('i_product_motif',$iproductmotif);
      $this->db->where('i_product_grade',$iproductgrade);
    	$this->db->update('tm_sjr_item');
    }
    function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbm=n_mutasi_bbm-$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00' and e_mutasi_periode='$emutasiperiode'
                              ",false);
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_git=n_mutasi_git+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$qsj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'
                              ",false);
    }
}

/* End of file Mmaster.php */
