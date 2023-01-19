<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_spb, a.d_survey, a.i_customer, a.e_customer_name, a.e_customer_address, a.e_customer_phone, a.i_price_group, a.i_area, '$i_menu' as i_menu 
                            from tr_customer_tmp a, tr_area c
                            where a.i_area=c.i_area and a.f_approve='t' 
                            and (a.i_approve <>'' or not a.i_approve isnull)
                            and (a.i_approve_ar='' or a.i_approve_ar isnull)",false);
		$datatables->add('action', function ($data) {
            $i_spb          = trim($data['i_spb']);
            $i_area         = trim($data['i_area']);
            $i_price_group  = trim($data['i_price_group']);
            $i_menu         = $data['i_menu'];
            $data           = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customerapprovear/cform/edit/$i_spb/$i_area/$i_price_group/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('d_survey', function ($data) {
            $d_survey = $data['d_survey'];
            if($d_survey == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_survey) );
            }
        });

        $datatables->hide('i_menu');
        $datatables->hide('i_area');
        $datatables->hide('i_price_group');

        return $datatables->generate();
    }

    function baca($ispb,$iarea){
		$this->db->select(" * FROM tr_customer_tmp a
				  LEFT JOIN tr_city
				  ON (a.i_city = tr_city.i_city and a.i_area = tr_city.i_area)
				  LEFT JOIN tr_customer_group
				  ON (a.i_customer_group = tr_customer_group.i_customer_group)
				  LEFT JOIN tr_area
				  ON (a.i_area = tr_area.i_area)
				  LEFT JOIN tr_customer_status 
				  ON (a.i_customer_status = tr_customer_status.i_customer_status)
				  LEFT JOIN tr_customer_producttype
				  ON (a.i_customer_producttype = tr_customer_producttype.i_customer_producttype)
				  LEFT JOIN tr_customer_specialproduct
			      ON (a.i_customer_specialproduct = tr_customer_specialproduct.i_customer_specialproduct)
				  LEFT JOIN tr_customer_grade
				  ON (a.i_customer_grade = tr_customer_grade.i_customer_grade)
				  LEFT JOIN tr_customer_service
				  ON (a.i_customer_service = tr_customer_service.i_customer_service)
				  LEFT JOIN tr_customer_salestype
				  ON (a.i_customer_salestype = tr_customer_salestype.i_customer_salestype)
				  LEFT JOIN tr_customer_class 
				  ON (a.i_customer_class=tr_customer_class.i_customer_class)
				  LEFT JOIN tr_shop_status 
				  ON (a.i_shop_status=tr_shop_status.i_shop_status)
				  LEFT JOIN tr_marriage 
				  ON (a.i_marriage=tr_marriage.i_marriage)
				  LEFT JOIN tr_jeniskelamin 
				  ON (a.i_jeniskelamin=tr_jeniskelamin.i_jeniskelamin)
				  LEFT JOIN tr_religion 
				  ON (a.i_religion=tr_religion.i_religion)
				  LEFT JOIN tr_traversed 
				  ON (a.i_traversed=tr_traversed.i_traversed)
				  LEFT JOIN tr_paymentmethod 
				  ON (a.i_paymentmethod=tr_paymentmethod.i_paymentmethod)
				  LEFT JOIN tr_call 
				  ON (a.i_call=tr_call.i_call)
				  LEFT JOIN tr_customer_plugroup
				  ON (a.i_customer_plugroup=tr_customer_plugroup.i_customer_plugroup)
				  LEFT JOIN tr_price_group
				  ON (a.i_price_group=tr_price_group.i_price_group)
				  where a.i_spb = '$ispb' and a.i_area='$iarea'
			", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacaspb($ispb,$iarea){
			$this->db->select(" tm_spb.e_remark1 AS emark1, * from tm_spb 
						        left join tr_customer on (tm_spb.i_customer=tr_customer.i_customer)
						        left join tr_customer_tmp on (tm_spb.i_spb=tr_customer_tmp.i_spb and tm_spb.i_area=tr_customer_tmp.i_area)
						        inner join tr_salesman on (tm_spb.i_salesman=tr_salesman.i_salesman)
						        left join tr_customer_area on (tm_spb.i_customer=tr_customer_area.i_customer)
						        inner join tr_price_group on (tm_spb.i_price_group=tr_price_group.i_price_group)
						        where tm_spb.i_spb ='$ispb' and tm_spb.i_area='$iarea'", false);
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->row();
			}
    }

    function bacadetail($ispb,$iarea,$ipricegroup){
		$this->db->select("a.i_spb,a.i_product,a.i_product_grade,a.i_product_motif,a.n_order,a.n_deliver,a.n_stock,a.v_unit_price,a.e_product_name,a.i_op,a.i_area,d.e_area_name,a.e_remark as ket,a.n_item_no, b.e_product_motifname, c.v_product_retail as hrgnew 
		                  from tm_spb_item a, tr_product_motif b, tr_product_price c, tr_area d
					      where a.i_spb = '$ispb' and a.i_area='$iarea' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif 
                          and a.i_product=c.i_product and c.i_price_group='$ipricegroup'
                          and a.i_area=d.i_area
					      order by a.n_item_no", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup){
			return $this->db->query(" select (sum(a.n_order * a.v_unit_price)) AS nilaiorderspb from tm_spb_item a
												        where a.i_spb = '$ispb' and a.i_area='$iarea' ", false);
    }
    
    public function cek_data($ispb){

    }

    public function bacaarea(){
      return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacaretensi(){
      return $this->db->order_by('i_retensi','ASC')->get('tr_retensi')->result();
    }

    public function bacashop(){
      return $this->db->order_by('i_shop_status','ASC')->get('tr_shop_status')->result();
    }

    public function bacastatus(){
      return $this->db->order_by('i_marriage','ASC')->get('tr_marriage')->result();
    }

    public function bacakelamin(){
      return $this->db->order_by('i_jeniskelamin','ASC')->get('tr_jeniskelamin')->result();
    }

    public function bacaagama(){
      return $this->db->order_by('i_religion','ASC')->get('tr_religion')->result();
    }

    public function bacatraversed(){
      return $this->db->order_by('i_traversed','ASC')->get('tr_traversed')->result();
    }

    public function bacaclass(){
      return $this->db->order_by('i_customer_class','ASC')->get('tr_customer_class')->result();
    }

    public function bacapayment(){
      return $this->db->order_by('i_paymentmethod','ASC')->get('tr_paymentmethod')->result();
    }

    public function bacacall(){
      return $this->db->order_by('i_call','ASC')->get('tr_call')->result();
    }

    public function bacacustomergroup(){
      return $this->db->order_by('i_customer_group','ASC')->get('tr_customer_group')->result();
    }

    public function bacaplugroup(){
      return $this->db->order_by('i_customer_plugroup','ASC')->get('tr_customer_plugroup')->result();
    }

    public function bacacustomertype(){
      return $this->db->order_by('i_customer_producttype','ASC')->get('tr_customer_producttype')->result();
    }

    public function bacacustomerstatus(){
      return $this->db->order_by('i_customer_status','ASC')->get('tr_customer_status')->result();
    }

    public function bacacustomergrade(){
      return $this->db->order_by('i_customer_grade','ASC')->get('tr_customer_grade')->result();
    }

    public function bacacustomerservice(){
      return $this->db->order_by('i_customer_service','ASC')->get('tr_customer_service')->result();
    }

    public function bacacustomersalestype(){
      return $this->db->order_by('i_customer_salestype','ASC')->get('tr_customer_salestype')->result();
    }

    public function bacapricegroup(){
      return $this->db->order_by('i_price_group','ASC')->get('tr_price_group')->result();
    }

    public function getkota($iarea,$cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_city,
                e_city_name
            FROM
                tr_city
            WHERE
                (UPPER(i_city) LIKE '%$cari%'
                OR UPPER(e_city_name) LIKE '%$cari%')
                AND i_area = '$iarea'
            ORDER BY
                i_city", 
        FALSE);
    }

    public function getsalesman($iarea,$cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                *
            FROM
                tr_salesman
            WHERE
                (UPPER(i_salesman) LIKE '%$cari%'
                OR UPPER(e_salesman_name) LIKE '%$cari%')
                AND f_salesman_aktif = 'true'
            ORDER BY
                i_salesman", 
        FALSE);
    }

    public function getcustomerspecialproduct($iproducttype) {
        $this->db->select("
                *
            FROM
                tr_customer_specialproduct
            WHERE
                i_customer_producttype = '$iproducttype'
            ORDER BY
                i_customer_specialproduct ",false);
        return $this->db->get();
    }

    public function bacaproduct($cari,$kdharga) {      
        $cari = str_replace("'", "", $cari);      
        return $this->db->query("
            SELECT
                DISTINCT a.i_product AS kode,
                c.e_product_name AS nama
            FROM
                tr_product_motif a,
                tr_product_price b,
                tr_product c,
                tr_product_type d,
                tr_product_status e
            WHERE
                b.i_product = a.i_product
                AND c.i_product_status = e.i_product_status
                AND a.i_product = c.i_product
                AND d.i_product_type = c.i_product_type
                AND b.i_price_group = '$kdharga'
                AND a.i_product_motif = '00'
                AND c.f_product_pricelist = 't'
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')", 
        FALSE);
    }

    public function bacaproductx($kdharga, $iproduct){
        return $this->db->query(" 
            SELECT
                DISTINCT a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                d.i_product_group,
                c.e_product_name AS nama,
                b.v_product_retail AS harga,
                c.i_product_status,
                e.e_product_statusname
            FROM
                tr_product_motif a,
                tr_product_price b,
                tr_product c,
                tr_product_type d,
                tr_product_status e
            WHERE
                b.i_product = a.i_product
                AND c.i_product_status = e.i_product_status
                AND a.i_product = c.i_product
                AND d.i_product_type = c.i_product_type
                AND b.i_price_group = '$kdharga'
                AND a.i_product_motif = '00'
                AND c.f_product_pricelist = 't'
                AND a.i_product = '$iproduct'",
        FALSE);
    }

    function update($ispb, $iarea, $iapprove, $eapprove, $fapprove, $fparkir, $fkuli, $fkontrabon){
		$query  = $this->db->query("SELECT to_char(current_timestamp,'yyyy-mm-dd') as c");
		$row    = $query->row();
		$dapprove= $row->c;
      	$data = array(
		                'e_approve_ar' 	=> $eapprove, 
		                'i_approve_ar' 	=> $iapprove,
		                'd_approve_ar'	=> $dapprove,
                        'f_parkir'      => $fparkir,
                        'f_kuli'        => $fkuli,
                        'f_kontrabon'   => $fkontrabon
                );
		  $this->db->where('i_spb', $ispb);
		  $this->db->where('i_area', $iarea);
		  $this->db->update('tr_customer_tmp', $data); 
        $query 	= $this->db->query("SELECT to_char(current_timestamp,'yyyy-mm-dd') as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		if($fapprove=='t'){
      	    $data = array(
		    		    'e_approve2'		=> $eapprove,
		    		    'd_approve2'		=> $dentry,
		    		    'i_approve2'		=> $iapprove
      	    			);
        }else{
      	    $data = array(
		    		    'e_notapprove'		=> $eapprove,
		    		    'd_notapprove'		=> $dentry,
		    		    'i_notapprove'		=> $iapprove
      	    			);
        }
    	$this->db->where('i_spb', $ispb);
    	$this->db->where('i_area', $iarea);
		$this->db->update('tm_spb', $data); 
    }
}

/* End of file Mmaster.php */
