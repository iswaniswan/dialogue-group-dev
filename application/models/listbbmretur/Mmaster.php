<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function bacaarea($username,$idcompany){
      return $this->db->query("
                              SELECT
                                  *
                              FROM
                                  tr_area
                              WHERE
                                  i_area IN (
                                  SELECT
                                      i_area
                                  FROM
                                      public.tm_user_area
                                  WHERE
                                      username = '$username'
                                      AND id_company = '$idcompany')
                              ",FALSE)->result();
    }

    public function data($dfrom,$dto,$iarea,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $thn = substr($dfrom,6,4);
        if($iarea == 'NA'){
          $datatables->query("
                              select
                                a.i_bbm,
                                a.d_bbm,
                                c.i_ttb,
                                c.d_ttb,
                                d.f_kn_cancel,
                                d.i_kn,
                                d.d_kn,
                                c.i_area,
                                c.i_customer,
                                b.e_customer_name,
                                a.f_bbm_cancel,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$iarea' as area,
                                '$folder' as folder
                              from
                                tr_customer b,
                                tm_ttbretur c,
                                tm_bbm a 
                              left join
                                 tm_kn d 
                                 on (a.i_bbm = d.i_refference) 
                              where
                                c.i_customer = b.i_customer 
                                and a.i_refference_document = c.i_ttb 
                                and c.i_customer = b.i_customer 
                                and a.i_area = c.i_area 
                                and a.d_bbm >= to_date('$dfrom', 'dd-mm-yyyy') 
                                and a.d_bbm <= to_date('$dto', 'dd-mm-yyyy') 
                                and c.n_ttb_year = '$thn' 
                              order by
                                a.i_area,
                                a.i_bbm desc"
                              );
        }else{
          $datatables->query("
                            select
                              a.i_bbm,
                              a.d_bbm,
                              c.i_ttb,
                              c.d_ttb,
                              d.f_kn_cancel,
                              d.i_kn,
                              d.d_kn,
                              c.i_area,
                              c.i_customer,
                              b.e_customer_name,
                              a.f_bbm_cancel,
                              '$dfrom' as dfrom,
                              '$dto' as dto,
                              '$iarea' as area,
                              '$folder' as folder
                            from
                              tr_customer b,
                              tm_ttbretur c,
                              tm_bbm a 
                            left join
                               tm_kn d 
                               on (a.i_bbm = d.i_refference) 
                            where
                              c.i_customer = b.i_customer 
                              and a.i_refference_document = c.i_ttb 
                              and c.i_customer = b.i_customer 
                              and a.i_area = c.i_area 
                              and a.d_bbm >= to_date('$dfrom', 'dd-mm-yyyy') 
                              and a.d_bbm <= to_date('$dto', 'dd-mm-yyyy') 
                              and c.n_ttb_year = '$thn' 
                              and a.i_area = '$iarea'
                            order by
                              a.i_area,
                              a.i_bbm desc"
                            );
        }
        
        
        $datatables->add('action', function ($data) {
            $ibbm          = trim($data['i_bbm']);
            $dbbm          = trim($data['d_bbm']);
            $ittb          = trim($data['i_ttb']);
            $dttb          = trim($data['d_ttb']);
            $fkncancel     = trim($data['f_kn_cancel']);
            $ikn           = trim($data['i_kn']);
            $dkn           = trim($data['d_kn']);
            $iarea         = trim($data['i_area']);
            $dfrom         = trim($data['dfrom']);
            $dto           = trim($data['dto']);
            $folder        = trim($data['folder']);
            $fbbmcancel    = trim($data['f_bbm_cancel']);
            $area          = trim($data['area']);
            $data          = '';

            $tmp=explode('-',$dbbm);
				    $tgl=$tmp[2];
				    $bln=$tmp[1];
				    $thn=$tmp[0];
				    $dbbm=$tgl.'-'.$bln.'-'.$thn;
				    $tmp=explode('-',$dttb);
				    $tgl=$tmp[2];
				    $bln=$tmp[1];
				    $thn=$tmp[0];
				    $dttb=$tgl.'-'.$bln.'-'.$thn;
				    $ttbyear=$thn;
            if($area = 'NA'){
              $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ibbm/$ittb/$ttbyear/$iarea/$area/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
           
            if(($fbbmcancel != 't' && $ikn == '') || ($fbbmcancel != 't' && $fkncancel=='t')){
              $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ibbm\",\"$ittb\",\"$iarea\",\"$ttbyear\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
            }

			return $data;
        });

      $datatables->edit('d_bbm', function ($data) {
          $d_bbm = $data['d_bbm'];
          if($d_bbm == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_bbm) );
          }
      });

      $datatables->edit('i_customer', function ($data) {
        $i_customer = $data['i_customer'];
        $e_customer_name = $data['e_customer_name'];
        return '('.$i_customer.')'.' - '.$e_customer_name;
    });

      $datatables->edit('d_ttb', function ($data) {
        $d_ttb = $data['d_ttb'];
        if($d_ttb == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($d_ttb) );
        }
      });

      $datatables->edit('d_kn', function ($data) {
        $d_kn = $data['d_kn'];
        if($d_kn == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($d_kn) );
        }
      });

      $datatables->edit('i_bbm', function ($data) {
        $i_bbm = "<h2><b>".$data['i_bbm']."</b></h2>";
        $fbbmcancel = $data['f_bbm_cancel'];
        if($fbbmcancel == 't'){
            return $i_bbm;
        }else{
            return $data['i_bbm'];
        }
      });

      $datatables->edit('i_kn', function ($data) {
        $i_kn = "<h2><b>".$data['i_kn']."</b></h2>";
        $fkncancel = $data['f_kn_cancel'];
        if($fkncancel == 't'){
            return $i_kn;
        }else{
            return $data['i_kn'];
        }
      });

        $datatables->hide('folder');
        $datatables->hide('e_customer_name');
        $datatables->hide('f_kn_cancel');
        $datatables->hide('f_bbm_cancel');
        $datatables->hide('dto');
        $datatables->hide('dfrom');
        $datatables->hide('area');

        return $datatables->generate();  
    }

    public function jumlah($ibbm){
      return $this->db->query("
                              select
                                a.*,
                                b.e_product_motifname 
                              from
                                tm_bbm_item a,
                                tr_product_motif b 
                              where
                                a.i_bbm = '$ibbm' 
                                and a.i_product = b.i_product 
                                and a.i_product_motif = b.i_product_motif
                              ",false);
    }

    public function delete($ibbm,$ittb,$iarea,$tahun) 
    {
      $ittb=str_replace('%20','',$ittb);
    	$this->db->set(
    		array(
					'i_bbm'			=> null,
					'd_bbm'			=> null,
					'd_receive2'=> null
    		)
    	);
			$this->db->where('i_area',$iarea);
			$this->db->where('i_ttb',$ittb);
			$this->db->where('n_ttb_year',$tahun);
			$this->db->update('tm_ttbretur');

    	$this->db->set(
    		array(
					'i_product2'					=> null,
					'i_product2_grade'		=> null,
					'i_product2_motif'		=> null,
					'n_quantity_receive'	=> null
    		)
    	);
    	$this->db->where('i_ttb',$ittb);
    	$this->db->where('n_ttb_year',$tahun);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_ttbretur_item');

		$this->db->query("update tm_bbm set f_bbm_cancel='t' WHERE i_bbm='$ibbm' and i_bbm_type='05'");
######
		  $ibbm=trim($ibbm);
		  $query = $this->db->query("select b.e_product_name, a.d_bbm, b.n_quantity, b.i_product, b.i_product_grade, b.i_product_motif 
                          from tm_bbm a, tm_bbm_item b WHERE a.i_bbm=b.i_bbm and a.i_bbm='$ibbm' and a.i_bbm_type='05'");
		  foreach($query->result() as $row){
			  $jml    = $row->n_quantity;
			  $product= $row->i_product;
			  $grade  = $row->i_product_grade;
			  $motif  = $row->i_product_motif;
			  $eproductname = $row->e_product_name;
        $dbbm    = $row->d_bbm;
        $istore				    = 'AA';
				$istorelocation		= '01';
				$istorelocationbin= '00';
        $th=substr($dbbm,0,4);
			  $bl=substr($dbbm,5,2);
			  $emutasiperiode=$th.$bl;
			  $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' and i_refference_document='$ibbm'
                                    order by d_transaction desc, i_trans desc",false);
        if ($queri->num_rows() > 0){
      	  $row   		= $queri->row();
          $que 	= $this->db->query("SELECT current_timestamp as c");
	        $ro 	= $que->row();
	        $now	 = $ro->c;
          $this->db->query(" 
                              INSERT INTO tm_ic_trans
                              (
                                i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                n_quantity_in, n_quantity_out,
                                n_quantity_akhir, n_quantity_awal)
                              VALUES 
                              (
                                '$product','$grade','$motif','$istore','$istorelocation','$istorelocationbin', 
                                '$eproductname', '$ibbm', '$now', 0, $jml, $row->n_quantity_akhir-$jml, $row->n_quantity_akhir
                              )
                           ",false);
        }
        if( ($jml!='') && ($jml!=0) ){
          $this->db->query(" 
                            UPDATE tm_mutasi set n_mutasi_returoutlet=n_mutasi_returoutlet-$jml, n_saldo_akhir=n_saldo_akhir-$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                            and e_mutasi_periode='$emutasiperiode'
                           ",false);
          $this->db->query(" 
                            UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                           ",false);
        }
		  }
######
#		$this->db->query("DELETE FROM tm_bbm_item WHERE i_bbm='$ibbm'");
    }

    public function baca($ibbm){
      return $this->db->query("
                              select
                                a.*,
                                e.i_customer,
                                b.e_customer_name,
                                c.e_area_name,
                                d.e_salesman_name,
                                e.i_ttb,
                                e.d_ttb,
                                f.i_kn 
                              from
                                tr_customer b,
                                tr_area c,
                                tr_salesman d,
                                tm_ttbretur e,
                                tm_bbm a 
                                left join
                                   tm_kn f 
                                   on(a.i_bbm = f.i_refference) 
                              where
                                e.i_customer = b.i_customer 
                                and a.i_area = c.i_area 
                                and a.i_salesman = d.i_salesman 
                                and a.i_bbm = e.i_bbm 
                                and a.i_bbm = '$ibbm' 
                              order by
                                a.i_bbm desc"
                            ,false);
    }

    public function bacadetail($ibbm){
      return $this->db->query("
                              select distinct
                                a.i_bbm,
                                a.i_product as i_product2,
                                a.i_product_motif as i_product2_motif,
                                a.i_product_grade as i_product2_grade,
                                a.e_product_name as e_product2_name,
                                a.n_quantity as n_quantity_receive,
                                a.v_unit_price as v_unit_price,
                                b.e_product_motifname as e_product2_motifname,
                                d.n_quantity as n_quantity,
                                d.i_product1,
                                d.i_product1_motif,
                                d.i_product1_grade,
                                d.i_nota,
                                c.i_ttb as i_ttb,
                                c.n_ttb_year as year,
                                c.i_area as i_area 
                              from
                                tm_bbm_item a,
                                tr_product_motif b,
                                tm_ttbretur c,
                                tm_ttbretur_item d 
                              where
                                a.i_bbm = '$ibbm' 
                                and a.i_product = b.i_product 
                                and a.i_product_motif = b.i_product_motif 
                                and a.i_product = d.i_product2 
                                and a.i_bbm = c.i_bbm 
                                and c.i_ttb = d.i_ttb 
                                and c.i_area = d.i_area 
                              order by
                                a.i_product"
                              ,false);
    }

    public function bacadetail2($ittb,$iarea,$nyear){
      return $this->db->query("
                              select
                                 d.*,
                                 a.e_product_name as e_product1_name,
                                 b.e_product_motifname as e_product1_motifname 
                              from
                                 tr_product a,
                                 tr_product_motif b,
                                 tm_ttbretur c,
                                 tm_ttbretur_item d 
                              where
                                 c.i_ttb = '$ittb' 
                                 and c.i_area = '$iarea' 
                                 and c.n_ttb_year = '$nyear' 
                                 and c.i_ttb = d.i_ttb 
                                 and c.i_area = d.i_area 
                                 and c.n_ttb_year = d.n_ttb_year 
                                 and b.i_product = d.i_product1  
                                 and a.i_product = d.i_product1
                              ",false);
    }

    function updatettbheader($ittb,$thttb,$iarea,$ibbm,$dbbm){
		  $query 	= $this->db->query("SELECT current_timestamp as c");
		  $row   	= $query->row();
		  $drec	= $row->c;
      	$this->db->set(
      		array(
		  			'i_bbm'		=> $ibbm,
		  			'd_bbm'		=> $dbbm,
		  			'd_receive2'=> $drec
      		)
      	);
      $this->db->where('i_area',$iarea);
		  $this->db->where('i_ttb',$ittb);
		  $this->db->where('n_ttb_year',$thttb);
      $this->db->update('tm_ttbretur');
    }

    function updatettbdetail($iproduct,$iproductgrade,$iproductmotif,$nttb,
							                $iproductxxx,$iproductgradexxx,$iproductmotifxxx,$nbbm,
							                $ittb,$thttb,$iarea,$inota){
    	$this->db->set(
    		array(
					'i_product2'			=> $iproductxxx,
					'i_product2_grade'		=> $iproductgradexxx,
					'i_product2_motif'		=> $iproductmotifxxx,
					'n_quantity_receive'	=> $nbbm
    		)
    	);
    	$this->db->where('i_ttb',$ittb);
    	$this->db->where('n_ttb_year',$thttb);
    	$this->db->where('i_area',$iarea);
    	$this->db->where('i_product1',$iproduct);
    	$this->db->where('i_product1_grade',$iproductgrade);
    	$this->db->where('i_product1_motif',$iproductmotif);
    	$this->db->update('tm_ttbretur_item');
    }

    function hapusttbdetail($iproduct,$iproductgrade,$iproductmotif,$ittb,$thttb,$iarea){
    	$this->db->set(
    		array(
					'i_product2'			    => null,
					'i_product2_grade'		=> null,
					'i_product2_motif'		=> null,
					'n_quantity_receive'	=> null
    		)
    	);
    	$this->db->where('i_ttb',$ittb);
    	$this->db->where('n_ttb_year',$thttb);
    	$this->db->where('i_area',$iarea);
    	$this->db->where('i_product1',$iproduct);
    	$this->db->where('i_product1_grade',$iproductgrade);
    	$this->db->where('i_product1_motif',$iproductmotif);
    	$this->db->update('tm_ttbretur_item');
    }

    function insertbbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isalesman)
    {
    	$this->db->set(
    		array(
				'i_bbm'					        => $ibbm,
				'i_bbm_type'			      => $ibbmtype,
				'i_refference_document'	=> $ittb,
				'd_refference_document'	=> $dttb,
				'd_bbm'					        => $dbbm,
				'e_remark'				      => $eremark,
				'i_area'				        => $iarea,
				'i_salesman'			      => $isalesman
    		)
    	);
    	
    	$this->db->insert('tm_bbm');
    }
	function deletebbmheader($ibbm)
    {
		$this->db->query("delete from tm_bbm where i_bbm='$ibbm'");
    }
	function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$ittb,$ibbm,$eremark,$dttb,$ibbmtype,$i,$dbbm)
    {
      $th=substr($dbbm,0,4);
      $bl=substr($dbbm,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_bbm'					        => $ibbm,
				'i_bbm_type'		  			=> $ibbmtype,
				'i_refference_document'	=> $ittb,
				'i_product'     				=> $iproduct,
				'i_product_motif'   		=> $iproductmotif,
				'i_product_grade'   		=> $iproductgrade,
				'e_product_name'    		=> $eproductname,
				'n_quantity'			      => $nquantity,
				'v_unit_price'    			=> $vunitprice,
				'e_remark'				      => $eremark,
				'd_refference_document'	=> $dttb,
        'e_mutasi_periode'      => $pr,
        'n_item_no'             => $i
    		)
    	);
    	$this->db->insert('tm_bbm_item');
    }
	function deletebbmdetail($iproductxxx,$iproductgradexxx,$iproductmotifxxx,$ibbm,$ibbmtype)
    {
    	$this->db->query("	delete from tm_bbm_item where i_product='$iproductxxx' and i_product_grade='$iproductgradexxx' 
              and i_product_motif='$iproductmotifxxx' and i_bbm='$ibbm' and i_bbm_type='$ibbmtype'");
              return true;
    }

	function insertheader($iarea,$ittb,$dttb,$icustomer,$isalesman,$nttbdiscount1,$nttbdiscount2,
							$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$fttbpkp,$fttbplusppn,
							$fttbplusdiscount,$vttbgross,$vttbdiscounttotal,$vttbnetto,$ettbremark,$fttbcancel,
							$dreceive1,$tahun	)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
					'i_area'				=> $iarea,
					'i_ttb'					=> $ittb,
					'd_ttb'					=> $dttb,
					'i_customer'			=> $icustomer,
					'i_salesman'			=> $isalesman,
					'n_ttb_discount1'		=> $nttbdiscount1,
					'n_ttb_discount2'		=> $nttbdiscount2,
					'n_ttb_discount3'		=> $nttbdiscount3,
					'v_ttb_discount1'		=> $vttbdiscount1,
					'v_ttb_discount2'		=> $vttbdiscount2,
					'v_ttb_discount3'		=> $vttbdiscount3,
					'f_ttb_pkp'				=> $fttbpkp,
					'f_ttb_plusppn'			=> $fttbplusppn,
					'f_ttb_plusdiscount'	=> $fttbplusdiscount,
					'v_ttb_gross'			=> $vttbgross,
					'v_ttb_discounttotal'	=> $vttbdiscounttotal,
					'v_ttb_netto'			=> $vttbnetto,
					'v_ttb_sisa'			=> $vttbnetto,
					'e_ttb_remark'			=> $ettbremark,
					'f_ttb_cancel'			=> $fttbcancel,
					'd_receive1'			=> $dreceive1,
					'd_entry'				=> $dentry,
					'n_ttb_year'			=> $tahun
    		)
    	);
    	$this->db->insert('tm_ttbretur');
    }
	function insertdetail($iarea,$ittb,$dttb,$inota,$dnota,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver)
    {
    	$this->db->set(
    		array(
					'i_area'			=> $iarea,
					'i_ttb'				=> $ittb,
					'i_nota'			=> $inota,
					'd_ttb'				=> $dttb,
					'd_nota'			=> $dnota,
					'i_product1'		=> $iproduct,
					'i_product1_grade'	=> $iproductgrade,
					'i_product1_motif'	=> $iproductmotif,
					'n_quantity'		=> $nquantity,
					'v_unit_price'		=> $vunitprice,
					'e_ttb_remark'		=> $ettbremark,
					'n_ttb_year'		=> $tahun
    		)
    	);
    	
    	$this->db->insert('tm_ttbretur_item');
    }
	function updateheader(	$ittb,$iarea,$tahun,$dttb,$dreceive1,$eremark,
							$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,
							$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,
							$vttbgross)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dupdate= $row->c;
    	$this->db->set(
    		array(
			'd_ttb'					=> $dttb,
			'd_receive1'			=> $dreceive1,
			'e_ttb_remark'			=> $eremark,
			'd_update'				=> $dupdate,
			'n_ttb_discount1'		=> $nttbdiscount1,
			'n_ttb_discount2'		=> $nttbdiscount2,
			'n_ttb_discount3'		=> $nttbdiscount3,
			'v_ttb_discount1'		=> $vttbdiscount1,
			'v_ttb_discount2'		=> $vttbdiscount2,
			'v_ttb_discount3'		=> $vttbdiscount3,
			'v_ttb_gross'			=> $vttbgross,
			'v_ttb_discounttotal'	=> $vttbdiscounttotal,
			'v_ttb_netto'			=> $vttbnetto,
			'v_ttb_sisa'			=> $vttbnetto
    		)
    	);
		$this->db->where('i_ttb',$ittb);
		$this->db->where('i_area',$iarea);
		$this->db->where('n_ttb_year',$tahun);
    	$this->db->update('tm_ttbretur');
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
    function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak)
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
                                  '$eproductname', '$ido', '$now', $qdo, 0, $q_ak+$qdo, $q_ak
                                )
                              ",false);
    }
    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_returoutlet=n_mutasi_returoutlet+$qdo, n_saldo_akhir=n_saldo_akhir+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','00','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,0,$qdo,0,0,0,0,$qdo,0,'f')
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
    function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=$q_ak+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname',$qdo, 't'
                                )
                              ",false);
    }
    function inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$qbbm,$q_aw,$q_ak)
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
                                  '$eproductname', '$ibbm', '$now', $q_in+$qbbm, $q_out, $q_ak+$qbbm, $q_aw
                                )
                              ",false);
    }
    function updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_bbm=n_mutasi_bbm+$qbbm, n_saldo_akhir=n_saldo_akhir+$qbbm
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qbbm,0,0,0,$qbbm,0,'f')
                              ",false);
    }
    function updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qbbm
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbm)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qbbm, 't'
                                )
                              ",false);
    }
    function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbbk,$q_aw,$q_ak)
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
                                  '$eproductname', '$ibbk', '$now', $q_in, $q_out+$qbbk, $q_ak-$qbbk, $q_aw
                                )
                              ",false);
    }
    function updatemutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_penjualan=n_mutasi_penjualan+$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbk,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,$qbbk,0,0,$qbbk,0,'f')
                              ",false);
    }
    function updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_bbk=n_mutasi_bbk+$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbk,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,0,0,$qbbk,$qbbk,0,'f')
                              ",false);
    }
    function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbk)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0, 't'
                                )
                              ",false);
    }
    function bacattbdetail($ittb,$iarea,$thn)
    {
		  $query=$this->db->query(" 	select a.i_product, a.i_product_motif, a.e_product_motifname, c.e_product_name,
									                b.v_unit_price, b.n_quantity, b.i_nota, b.i_product1_grade as i_product_grade
									                from tr_product_motif a,tr_product c, tm_ttbretur_item b
									                where trim(b.i_ttb)='$ittb' and b.i_area='$iarea' and n_ttb_year=$thn
                                  and a.i_product=c.i_product and b.i_product1=a.i_product and b.i_product1_motif=a.i_product_motif",false);
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ibbm,$ntmp,$eproductname)
    {
      $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' 
                                    order by i_trans desc",false);
#and i_refference_document='$ibbm'
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
                                    '$eproductname', '$ibbm', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir
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
    function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_returoutlet=n_mutasi_returoutlet-$qbbk, n_saldo_akhir=n_saldo_akhir-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qbbk
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }


}

  /* End of file Mmaster.php */
