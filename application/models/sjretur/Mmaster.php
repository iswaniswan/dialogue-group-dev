<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select a.i_product_type, a.e_product_typename, b.e_product_groupname, a.e_product_typenameprint1, a.e_product_typenameprint2, '$i_menu' as i_menu from tr_product_type a, tr_product_group b
		where a.i_product_group = b.i_product_group");
        $datatables->add('action', function ($data) {
            $i_product_type = trim($data['i_product_type']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"class/cform/view/$i_product_type/\",\"#main\")'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"class/cform/edit/$i_product_type/\",\"#main\")'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_supplier_group');
        $this->db->where('i_supplier_group', $id);
        return $this->db->get();
    }
    
    public function bacaproductx($iproduct) {
        return $this->db->query("
          SELECT
              a.i_product AS kode,
              a.i_product_motif AS motif,
              a.e_product_motifname AS namamotif,
              c.i_product_status,
              e.e_product_statusname,
              c.e_product_name AS nama,
              b.v_product_retail AS harga
          FROM
              tr_product_motif a,
              tr_product_price b,
              tr_product c,
              tr_product_type d,
              tr_product_status e
          WHERE
              d.i_product_type = c.i_product_type
              AND b.i_product = a.i_product
              AND a.i_product_motif = '00'
              AND a.i_product = c.i_product
              AND c.i_product_status = e.i_product_status
              AND c.i_product_status <> '4'
              AND b.i_product_grade = 'A'
              AND c.i_product = '$iproduct' "
          );
    }
  
    public function bacaproducticx($istore,$iproduct){
      return $this->db->query(" 
        SELECT
            a.i_product AS kode,
            a.i_product_motif AS motif,
            a.e_product_motifname AS namamotif,
            c.i_product_status,
            e.e_product_statusname,
            c.e_product_name AS nama,
            b.v_product_retail AS harga
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e,
            tm_ic f
        WHERE
            d.i_product_type = c.i_product_type
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND b.i_product_grade = 'A'
            AND a.i_product = f.i_product
            AND f.i_store = '$istore'
            AND f.f_product_active = 't'
            AND f.n_quantity_stock>0
            AND b.i_product_grade = f.i_product_grade
            AND c.i_product = '$iproduct' ");
    }

    function baca($isjr,$iarea)
    {
		$this->db->select(" distinct(c.i_store), c.i_store_location, a.*, b.e_area_name 
                        from tm_sjr a, tr_area b, tm_sjr_item c
						            where a.i_area=b.i_area and a.i_sjr=c.i_sjr 
                        and a.i_area=c.i_area
						            and a.i_sjr ='$isjr' and a.i_area='$iarea' ", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }
    function bacadetail($isjr, $iarea)
    {
		$this->db->select("a.i_sjr,a.d_sjr,a.i_area,
                       a.i_product,a.i_product_grade,a.i_product_motif,a.n_quantity_retur,
                       a.n_quantity_receive,a.v_unit_price,a.e_product_name,a.i_store,
                       a.i_store_location,a.i_store_locationbin,a.e_remark,
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
		$query=$this->db->query(" select a.i_product as kode, a.i_product_motif as motif,
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

    function runningnumbersj($iarea,$thbl){
	    $th	= substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
		  $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SJR'
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
                            where i_modul='SJR'
                            and substr(e_periode,1,4)='$th' 
                            and i_area='$iarea'", false);
			  settype($nosj,"string");
			  $a=strlen($nosj);
			  while($a<4){
			    $nosj="0".$nosj;
			    $a=strlen($nosj);
			  }
        
			  $nosj  ="SJR-".$thbl."-".$iarea.$nosj;
			  return $nosj;
		  }else{
			  $nosj  ="0001";
			  $nosj  ="SJR-".$thbl."-".$iarea.$nosj;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJR','$iarea','$asal',1)");
			  return $nosj;
		  }
    }
    function insertsjheader($isj,$dsj,$iarea,$vspbnetto,$isjold)
    {
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjentry	= $row->c;
    	$this->db->set(
    		array(
				'i_sjr'				=> $isj,
				'i_sjr_old'		=> $isjold,
				'd_sjr'				=> $dsj,
				'i_area'		  => $iarea,
				'v_sjr'		    => $vspbnetto,
				'd_sjr_entry'	=> $dsjentry,
				'f_sjr_cancel'=> 'f'
    		)
    	);
    	
    	$this->db->insert('tm_sjr');
    }
    function insertsjheader2($isj,$dsj,$iarea,$vspbnetto,$isjold)
    {
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dsjentry	= $row->c;
    	$this->db->set(
    		array(
				'i_sjr' 				=> $isj,
				'i_sjr_old'			=> $isjold,
				'd_sjr'				  => $dsj,
				'i_area'		  	=> $iarea,
				'v_sjr'		      => $vspbnetto,
				'd_sjr_entry'		=> $dsjentry,
				'f_sjr_cancel'	=> 'f'
    		)
    	);
    	
    	$this->db->insert('tm_nota');
    }    
    function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,
			                      $vunitprice,$isj,$dsj,$iarea, $istore,$istorelocation,$istorelocationbin,$eremark,$i)
    {
      $th=substr($dsj,0,4);
      $bl=substr($dsj,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_sjr'			        => $isj,
				'd_sjr'			        => $dsj,
				'i_area'		        => $iarea,
				'i_product'       		=> $iproduct,
				'i_product_motif'   	=> $iproductmotif,
				'i_product_grade'   	=> $iproductgrade,
				'e_product_name'    	=> $eproductname,
				'n_quantity_retur'  	=> $nretur,
				'n_quantity_receive'	=> $nreceive,
				'v_unit_price'		    => $vunitprice,
				'i_store'         		=> $istore,
				'i_store_location'	    => $istorelocation,
				'i_store_locationbin'	=> $istorelocationbin, 
                'e_remark'              => $eremark,
                'e_mutasi_periode'      => $pr,
                'n_item_no'             => $i
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
    function updatesjheader($isj,$iarea,$isjold,$dsj,$vsjnetto)
    {
      $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjupdate= $row->c;
    	$this->db->set(
    		array(
				'i_sjr_old'	  => $isjold,
				'v_sjr'	      => $vsjnetto,
				'd_sjr'       => $dsj,
        'd_sjr_update'=> $dsjupdate

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
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function inserttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
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
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
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
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    }
    function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_git=n_mutasi_git+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$q_aw,$q_ak)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close, n_mutasi_git)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',$q_aw,0,0,0,0,0,0,$q_ak,0,'f',$qsj)
                              ",false);
    }
    function updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=$q_ak-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0-$qsj, 't'
                                )
                              ",false);
    }
    function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
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
    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname)
    {
      $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' 
                                    order by i_trans desc",false);
#and i_refference_document='$isj'
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
                                    '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
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
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function deletesjheader($isjr,$iarea)
	  {
		  $this->db->query(" delete from tm_sjr where i_sjr='$isjr' and i_area='$iarea' ",false);
	  }    
    function bacaproduct($istore,$istorelocation,$num,$offset,$cari)
    {
        $area1	= $this->session->userdata('i_area');
        $this->db->select("a.i_product as kode, b.e_product_name as nama, b.v_product_retail as harga, a.n_quantity_stock as stok,
                          a.i_product_motif as motif, c.e_product_motifname as namamotif, a.i_store, a.i_store_location
                          from tm_ic a, tr_product b, tr_product_motif c
                          where a.i_product=b.i_product and b.i_product=c.i_product and c.i_product_motif='00'
                          and a.i_product_motif=c.i_product_motif and i_store='$istore' and i_store_location='$istorelocation'
                          and (upper(a.i_product) like '%$cari%' or upper(b.e_product_name) like '%$cari%')
                          order by b.e_product_name", false)->limit($num,$offset);
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->result();
			}
    }



}

/* End of file Mmaster.php */
