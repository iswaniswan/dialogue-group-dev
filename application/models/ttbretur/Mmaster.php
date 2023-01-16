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
    
//     select a.*, b.e_supplier_name
// from duta_prod.tm_opbb a
// inner join duta_prod.tr_supplier b on (a.i_supplier = b.i_supplier)
// where a.i_op = 'OP-2002-0000003'
// and a.i_supplier = 'SA001'
// and a.i_payment_type = '1'
    
    function bacakode()
    {
		  $this->db->select(" * from tr_price_group order by i_price_group", false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function insertheader(	$iarea,$ittb,$dttb,$icustomer,
                            $isalesman,
                            $nttbdiscount1,$nttbdiscount2,
							$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$fttbpkp,$fttbplusppn,
							$fttbplusdiscount,$vttbgross,$vttbdiscounttotal,$vttbnetto,$ettbremark,$fttbcancel,
							$dreceive1,$tahun,$ialasanretur,$ipricegroup,$inota)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
					'i_area'				      => $iarea,
					'i_ttb'					      => $ittb,
					'd_ttb'					      => $dttb,
					'i_customer'			    => $icustomer,
					 'i_salesman'			    => $isalesman,
					'n_ttb_discount1'	    => $nttbdiscount1,
					'n_ttb_discount2'	    => $nttbdiscount2,
					'n_ttb_discount3'	    => $nttbdiscount3,
					'v_ttb_discount1'	    => $vttbdiscount1,
					'v_ttb_discount2'	    => $vttbdiscount2,
					'v_ttb_discount3'	    => $vttbdiscount3,
					'f_ttb_pkp'		    		=> $fttbpkp,
					'f_ttb_plusppn'		    => $fttbplusppn,
					'f_ttb_plusdiscount'  => $fttbplusdiscount,
					'v_ttb_gross'			    => $vttbgross,
					'v_ttb_discounttotal'	=> $vttbdiscounttotal,
					'v_ttb_netto'   			=> $vttbnetto,
					'v_ttb_sisa'		    	=> $vttbnetto,
					'e_ttb_remark'	  		=> $ettbremark,
					'f_ttb_cancel'	  		=> $fttbcancel,
					'd_receive1'		    	=> $dreceive1,
					'd_entry'				      => $dentry,
					'n_ttb_year'		    	=> $tahun,
          'i_alasan_retur'      => $ialasanretur,
          'i_price_group'       => $ipricegroup,
          'i_nota'              => $inota
    		)
    	);
    	$this->db->insert('tm_ttbretur');
    }
	function insertdetail($iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver,$i)
    {
    	$this->db->set(
    		array(
					'i_area'			      => $iarea,
					'i_ttb'	      			=> $ittb,
					'd_ttb'				      => $dttb,
					'i_product1'		    => $iproduct,
					'i_product1_grade'	=> $iproductgrade,
					'i_product1_motif'	=> $iproductmotif,
					'n_quantity'    		=> $nquantity,
					'v_unit_price'	  	=> $vunitprice,
					'e_ttb_remark'	  	=> $ettbremark,
					'n_ttb_year'		    => $tahun,
          			'n_item_no'         => $i
    		)
    	);
    	$this->db->insert('tm_ttbretur_item');
    }

	public function insert($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill,$vproductretail){
        $query = $this->db->query("SELECT current_timestamp as c");
		$row   = $query->row();
		$dentry= $row->c;
        $data = array(
            'i_product'         => $iproduct,
            'e_product_name'    => $eproductname,
            'i_product_grade'   => $iproductgrade,
            'i_price_group'     => $ipricegroup,
            'v_product_retail'  => $vproductretail,
            'v_product_mill'             => $vproductmill,
            'd_product_priceentry'       => $dentry
    );
    $this->db->insert('tr_product_price', $data);
    }

    public function update($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill,$vproductretail){
        $query = $this->db->query("SELECT current_timestamp as c");
        $row   = $query->row();
        $dupdate= $row->c;
        $data = array(
            'i_product'         => $iproduct,
            'e_product_name'    => $eproductname,
            'i_product_grade'   => $iproductgrade,
            'i_price_group'     => $ipricegroup,
            'v_product_retail'  => $vproductretail,
            'v_product_mill'    => $vproductmill,
            'd_product_priceentry'           => $dupdate
    );

    $this->db->where('i_product', $iproduct);
    $this->db->update('tr_product_price', $data);
    }
    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }
    public function getfaktur($icustomer){
        $this->db->select("*");
        $this->db->from('tm_nota');
        $this->db->where('i_customer', $icustomer);
        $this->db->order_by('i_nota');
        return $this->db->get();

    }
    public function getcustomer($iarea) {
        $this->db->select("i_customer, e_customer_name");
        $this->db->from('tr_customer');
        $this->db->where('i_area', $iarea);
        $this->db->order_by('e_customer_name');
        return $this->db->get();
    }

    public function bacapelanggan($iarea){
        $this->db->select('*');
        $this->db->from('tr_customer');
        $this->db->where('i_area', $iarea);
        return $this->db->get();
        // return $this->db->order_by('i_customer','ASC')->get('tr_area')->result();
    }
    public function bacaalasan(){
        return $this->db->order_by('i_alasan_retur','ASC')->get('tr_alasan_retur')->result();
    }
    public function bacanota(){
        // $this->db->select('*');
        // $this->db->from('tm_nota');
        // $this->db->where('i_supplier_group', $id);
        // return $this->db->get();
        return $this->db->order_by('i_nota','ASC')->get('tm_nota')->result();
    }
    public function bacagrade(){
        return $this->db->order_by('i_product_grade','ASC')->get('tr_product_grade')->result();
    }


	
}

/* End of file Mmaster.php */
