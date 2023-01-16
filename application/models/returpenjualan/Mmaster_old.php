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

    function insertstock($iproduct, $ikodelokasi, $eproductname, $nretur, $icolor){    
    	$this->db->set(
    		array(
                'i_product'             =>$iproduct,
                'i_product_grade'       =>'B',
                'i_kode_lokasi'         =>$ikodelokasi,
                'e_product_name'        =>$eproductname, 
                'n_quantity_stock'      =>$nretur,
                'i_color'               =>$icolor,
    		)
    	);
    	$this->db->insert('tm_ic');
    }

    function insertheader( $icustomer, $ibranch, $inota, $vttbdiscounttotal,
    $vttbnetto, $vttbgross, $ialasanretur, $ittb, $dttb, $fttbcancel, $ettbremark){    
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
          'i_ttb'               => $ittb,
          'd_ttb'               => $dttb,
          'i_kode_lokasi'       => '01',
          'i_customer'          => $icustomer,
          'i_branch'            => $ibranch,
          'i_alasan_retur'      => $ialasanretur,
          'i_nota'              => $inota,
          'v_ttb_gross'         => $vttbgross,
          'v_ttb_discounttotal' => $vttbdiscounttotal,
          'v_ttb_netto'         => $vttbnetto,
          'e_ttb_remark'        => $ettbremark,
          'f_ttb_cancel'        => $fttbcancel,
          'd_entry'             => $dentry,

    		)
    	);
    	$this->db->insert('tm_ttbretur');
    }
	function insertdetail($ittb, $dttb, $th, $inota, $iproduct, $eproductname, $icolor, $vunitprice,
    $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3, $nretur, $nquantity,$i)
    {
    	$this->db->set(
    		array(
                'i_ttb'                 => $ittb, 
                'd_ttb'                 => $dttb,
                'i_nota'                => $inota, 
                // 'd_nota'                => $th, 
                'i_product'             => $iproduct,
                'i_product_grade'       => 'B', 
                'i_color'               => $icolor, 
                'n_customer_discount1'  => $ncustomerdiscount1, 
                'n_customer_discount2'  => $ncustomerdiscount2,
                'n_customer_discount3'  => $ncustomerdiscount3, 
                'n_quantity_faktur'     => $nquantity, 
                'n_quantity_retur'      => $nretur, 
                'v_unit_price'          => $vunitprice, 
                'n_ttb_year'            => $th,
                'n_item_no'             => $i,
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
    public function getbranch($icustomer){
        $this->db->select("*");
        $this->db->from('tr_branch');
        $this->db->where('i_customer', $icustomer);
        $this->db->order_by('i_customer');
        return $this->db->get();

    }

    public function getfaktur($ibranch){
        $this->db->select("*");
        $this->db->from('tm_faktur_do_t');
        $this->db->where('i_branch', $ibranch);
        $this->db->order_by('i_faktur_code');
        return $this->db->get();

    }
    
    

    public function getcustomer($ibranch) {
        $this->db->select("i_customer, e_customer_name");
        $this->db->from('tr_customer');
        $this->db->where('i_branch', $ibranch);
        $this->db->order_by('e_customer_name');
        return $this->db->get();
    }

    public function cekstock($iproduct,$kodelokasi,$icolor){
        $this->db->select("n_quantity_stock from tm_ic 
                        where i_product = '$iproduct' and i_kode_lokasi = '$kodelokasi' 
                        and i_product_grade = 'B' and i_color = '$icolor'",false);
        return $this->db->get();
    }

    function updatestock($iproduct, $total, $ikodelokasi){
        $this->db->set(
          array(          
            'n_quantity_stock'  => $total,
            )
          );
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_kode_lokasi',$ikodelokasi);
        $this->db->where('i_product_grade','B');
        $this->db->update('tm_ic');
    }

    public function bacagudang(){
        $this->db->select("i_kode_master, e_nama_master, i_kode_lokasi from tr_master_gudang 
        where i_kode_master in ('G08','G13')",false);
        // return $this->db->get()->result();
        return $this->db->get()->result();
    }

    public function bacabagian(){
        $this->db->select("a.i_sub_bagian, a.e_sub_bagian FROM duta_prod.tm_sub_bagian a WHERE i_sub_bagian='SDP0011'",false);
        return $this->db->get()->result();
    }

    public function bacapelanggan(){
        return $this->db->order_by('e_customer_name','ASC')->get('tr_customer')->result();
        // $this->db->select('*');
        // $this->db->from('tr_customer');
        // $this->db->order_by('i_customer');
        // $this->db->where('i_area', $iarea);
        // return $this->db->get();
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
