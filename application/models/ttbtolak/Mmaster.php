<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu,$now,$dudet){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("select a.i_sj, a.d_sj, c.e_area_name, b.e_customer_name,'$i_menu' as i_menu
                            from tm_nota a, tr_customer b, tr_area c 
                            where a.i_customer=b.i_customer and a.i_area=c.i_area 
                            and a.f_ttb_tolak='f' and a.f_nota_cancel='f'
                            and (a.i_sj like '%-$now-%' or a.i_sj like '%-$dudet-%')");
        // $datatables->query("Select i_product, e_product_name, i_price_group, i_product_grade, v_product_retail, 
                            // n_product_margin, d_product_priceentry, d_product_priceupdate,'$i_menu' as i_menu
                            // from tr_product_price");

        
        $datatables->add('action', function ($data) {
            $i_sj = trim($data['i_sj']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"ttbtolak/cform/view/$i_sj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"ttbtolak/cform/edit/$i_sj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        // $datatables->edit('d_product_priceentry', function ($data) {
        //     $d_product_priceentry = $data['d_product_priceentry'];
        //     if($d_product_priceentry == ''){
        //         return '';
        //     }else{
        //         return date("d-m-Y", strtotime($d_product_priceentry) );
        //     }
        // });

        // $datatables->edit('d_product_priceupdate', function ($data) {
        //     $d_product_priceupdate = $data['d_product_priceupdate'];
        //     if($d_product_priceupdate == ''){
        //         return '';
        //     }else{
        //         return date("d-m-Y", strtotime($d_product_priceupdate) );
        //     }
        // });

        $datatables->hide('i_menu');
        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tm_nota a');
        $this->db->join('tr_area b','a.i_area = b.i_area');
        $this->db->join('tr_customer c','a.i_customer = c.i_customer');
        $this->db->join('tr_salesman d','a.i_salesman = d.i_salesman');
        $this->db->join('tr_customer_pkp e','a.i_customer = e.i_customer','c.i_customer = e.i_customer');
        $this->db->where('i_sj', $id);
        return $this->db->get();
    }
    function cek_data2($id){
		$this->db->select('*');
        $this->db->from('tm_nota_item ');
        // $this->db->join('tr_area b','a.i_area = b.i_area');
        // $this->db->join('tr_customer c','a.i_customer = c.i_customer');
        // $this->db->join('tr_salesman d','a.i_salesman = d.i_salesman');
        // $this->db->join('tr_customer_pkp e','a.i_customer = e.i_customer','c.i_customer = e.i_customer');
        $this->db->where('i_sj', $id);
        return $this->db->get()->result();
	}
    function get_productgrade(){
        $this->db->select('*');
        $this->db->from('tr_product_grade');
        return $this->db->get();
    }
	public function insert($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill){
        $data = array(
            'iproduct'               => $iproduct,
            'eproductname'           => $eproductname,
            'iproductgrade'          => $iproductgrade,
            'nproductmargin'         => $nproductmargin,
            'vproductmill'           => $vproductmill
    );
    
    $this->db->insert('tr_product_price', $data);
    }

    public function update($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill){
        $data = array(
            'e_product_name'          => $eproductname,
            'i_product_grade'         => $iproductgrade,
            'n_product_margin'        => $nproductmargin,
            'v_product_mill'          => $vproductmill
    );

    $this->db->where('i_product', $iproduct);
    $this->db->update('tr_product_price', $data);
    }
    function runningnumberbbm($thbl){
        $th	= substr($thbl,0,4);
    $asal=$thbl;
    $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='BBM'
                        and substr(e_periode,1,4)='$th' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
              $terakhir=$row->max;
            }
            $nobbm  =$terakhir+1;
      $this->db->query(" update tm_dgu_no 
                          set n_modul_no=$nobbm
                          where i_modul='BBM'
                          and substr(e_periode,1,4)='$th' ", false);
            settype($nobbm,"string");
            $a=strlen($nobbm);
            while($a<6){
              $nobbm="0".$nobbm;
              $a=strlen($nobbm);
            }
      
            $nobbm  ="BBM-".$thbl."-".$nobbm;
            return $nobbm;
        }else{
            $nobbm  ="000001";
            $nobbm  ="BBM-".$thbl."-".$nobbm;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('BBM','00',$asal,1)");
            return $nobbm;
        }
  }
  function insertheader(	$iarea,$ittb,$dttb,$icustomer,$isalesman,$inota,$dnota,$nttbdiscount1,$nttbdiscount2,
							$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$fttbpkp,$fttbplusppn,
							$fttbplusdiscount,$vttbgross,$vttbdiscounttotal,$vttbnetto,$ettbremark,$fttbcancel,
							$dreceive1,$tahun,$isj)
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
					'i_nota'				=> $inota,
					'd_nota'				=> $dnota,
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
					'e_ttb_remark'			=> $ettbremark,
					'f_ttb_cancel'			=> $fttbcancel,
					'd_receive1'			=> $dreceive1,
					'd_entry'				=> $dentry,
					'n_ttb_year'			=> $tahun,
          'i_ttb_refference'=> $isj
    		)
    	);
    	$this->db->insert('tm_ttbtolak');
		$this->db->query("update tm_nota set f_ttb_tolak = 't' where i_nota='$inota'",false);
    }
	function insertdetail($iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver,$i,$isj)
    {
    	$this->db->set(
    		array(
					'i_area'    			=> $iarea,
					'i_ttb'			    	=> $ittb,
					'd_ttb'		    		=> $dttb,
					'i_product'		  	=> $iproduct,
					'i_product_grade'	=> $iproductgrade,
					'i_product_motif'	=> $iproductmotif,
					'n_quantity'  		=> $nquantity,
					'n_deliver'		  	=> $ndeliver,
					'v_unit_price'  	=> $vunitprice,
					'e_ttb_remark'  	=> $ettbremark,
					'n_ttb_year'	  	=> $tahun,
          'n_item_no'       => $i,
          'i_ttb_refference'=> $isj
    		)
    	);
    	
    	$this->db->insert('tm_ttbtolak_item');
    }
	function updatebbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea)
    {
    	$this->db->set(
    		array(
				'i_refference_document'	=> $ittb,
				'd_refference_document'	=> $dttb,
				'd_bbm'					=> $dbbm,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea
    		)
    	);
    	$this->db->where('i_bbm',$ibbm);
		$this->db->where('i_bbm_type',$ibbmtype);
    	$this->db->update('tm_bbm');
    }
	function insertbbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isalesman)
    {
    	$this->db->set(
    		array(
				'i_bbm'					=> $ibbm,
				'i_bbm_type'			=> $ibbmtype,
				'i_refference_document'	=> $ittb,
				'd_refference_document'	=> $dttb,
				'd_bbm'					=> $dbbm,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea,
				'i_salesman'			=> $isalesman
    		)
    	);
    	
    	$this->db->insert('tm_bbm');
    }
	function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$ittb,$ibbm,$eremark,$dttb,$ibbmtype,$i)
    {
      $th=substr($dttb,0,4);
      $bl=substr($dttb,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_bbm'					=> $ibbm,
				'i_bbm_type'					=> $ibbmtype,
				'i_refference_document'	=> $ittb,
				'i_product'				=> $iproduct,
				'i_product_motif'		=> $iproductmotif,
				'i_product_grade'		=> $iproductgrade,
				'e_product_name'		=> $eproductname,
				'n_quantity'			=> $nquantity,
				'v_unit_price'			=> $vunitprice,
				'e_remark'				=> $eremark,
				'd_refference_document'	=> $dttb,
        'e_mutasi_periode'      => $pr,
        'n_item_no'             => $i
    		)
    	);
    	
    	$this->db->insert('tm_bbm_item');
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
                                  '$eproductname', '$isj', '$now', $q_in+$qsj, $q_out, $q_ak+$qsj, $q_aw
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
                                set n_mutasi_bbm=n_mutasi_bbm+$qsj, n_saldo_akhir=n_saldo_akhir+$qsj
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
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,0,0,$qsj,0,0,0,$qsj,0,'f')
                              ",false);
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
	function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=$q_ak+$qsj

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
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $ndeliver, 't'
                                )
                              ",false);
    }
    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                order by d_transaction desc",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
}

/* End of file Mmaster.php */
