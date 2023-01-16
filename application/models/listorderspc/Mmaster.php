<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	public function getarea(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT *
            FROM public.tm_user_area
            WHERE username = '$username'
            AND id_company = '$id_company'
            AND i_area IN ('PB','00')
            ", FALSE);
        if ($query->num_rows()>0) {
            $key = $query->row();
            $iarea = $key->i_area;
            return 'PB';
        }else{
            return 'xx';
        }
    }

    public function data($folder,$imenu,$dfrom,$dto,$iarea){
    	$username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
    	if ($iarea=='PB') {
    		$sql = "";
    	}else{
    		$sql = "
    			AND a.i_area IN (
			    SELECT
			        i_area
			    FROM
			        public.tm_user_area
			    WHERE
			        username = '$username'
			        AND id_company = '$id_company')
    		";
    	}
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        	SELECT
			    a.i_orderpb,
			    i_spmb,
			    a.i_customer,
			    to_char(a.d_orderpb, 'dd-mm-yyyy') AS d_orderpb,
			    a.i_customer||' - '||c.e_customer_name AS customer,
			    a.i_spg||' - '||UPPER(b.e_spg_name) AS spg,
			    SUM(d.n_quantity_order) AS jumlah_order,
			    CASE WHEN a.f_orderpb_cancel = 't' THEN 'Ya' ELSE 'Tidak' END AS status,
			    a.f_orderpb_cancel,
			    '$folder' AS folder,
			    '$imenu' AS imenu,
			    '$dfrom' AS dfrom,
			    '$dto' AS dto
			FROM
			    tr_spg b,
			    tr_customer c,
			    tm_orderpb a
			LEFT JOIN tm_orderpb_item d ON
			    (a.i_orderpb = d.i_orderpb
			    AND a.i_area = d.i_area
			    AND a.i_customer = d.i_customer)
			WHERE
			    a.i_spg = b.i_spg
			    AND a.i_customer = c.i_customer
			    $sql
			    AND a.d_orderpb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
			    AND a.d_orderpb <= TO_DATE('$dto', 'dd-mm-yyyy')
			GROUP BY
			    a.i_orderpb,
			    i_spmb,
			    a.i_customer,
			    a.d_orderpb,
			    a.i_spg,
			    b.e_spg_name,
			    a.i_customer,
			    c.e_customer_name,
			    a.f_orderpb_cancel
			ORDER BY
			    a.i_orderpb
        ", FALSE);
        $datatables->add('action', function ($data) {
            $iorderpb  = trim($data['i_orderpb']);
            $ispmb     = trim($data['i_spmb']);
            $imenu     = $data['imenu'];
            $dfrom     = $data['dfrom'];
            $dto       = $data['dto'];
            $icustomer = $data['i_customer'];
            $fcancel   = $data['f_orderpb_cancel'];
            $folder    = $data['folder'];
            $data      = '';
            if(check_role($imenu, 2)||check_role($imenu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iorderpb/$icustomer/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($imenu, 4) && $fcancel=='f' && ($ispmb=='' || $ispmb==null)){
            	$data .= "<a href=\"#\" onclick='cancel(\"$iorderpb\",\"$icustomer\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('i_customer');
        $datatables->hide('f_orderpb_cancel');
        $datatables->hide('i_spmb');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('folder');
        $datatables->hide('imenu');
        return $datatables->generate();
    }

    public function cancel($iorderpb,$icustomer){
        $this->db->set(
            array(
                'f_orderpb_cancel' => 't'
            )
        );
        $this->db->where('i_orderpb',$iorderpb);
        $this->db->where('i_customer',$icustomer);
        $this->db->update('tm_orderpb');
    }


    public function baca($iorderpb,$icustomer){
    	$this->db->select("a.*, b.e_area_name, c.e_customer_name, d.e_spg_name from tm_orderpb a, tr_area b, tr_customer c, tr_spg d
    		where a.i_area=b.i_area and a.i_customer=c.i_customer and a.i_spg=d.i_spg
    		and a.i_orderpb ='$iorderpb' and a.i_customer='$icustomer'", false);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0){
    		return $query->row();
    	}
    }

    public function bacadetail($iorderpb,$icustomer){
    	$this->db->select(" a.*, b.e_product_motifname from tm_orderpb_item a, tr_product_motif b
    		where a.i_orderpb = '$iorderpb' and a.i_customer='$icustomer' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif order by a.n_item_no ", false);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0){
    		return $query->result();
    	}
    }

    public function deleteheader($xiorderpb, $iarea, $icustomer){
		$this->db->query("DELETE FROM tm_orderpb WHERE i_orderpb='$xiorderpb' and i_area='$iarea' and i_customer='$icustomer'");
    }

    public function insertheader($iorderpb, $dorderpb, $iarea, $ispg, $icustomer){
    	$dentry	= current_datetime();
    	$this->db->set(
    		array(
    			'i_orderpb'       => $iorderpb,
    			'i_area'          => $iarea,
    			'i_spg'           => $ispg,
    			'i_customer'      => $icustomer,
    			'd_orderpb'       => $dorderpb,
    			'f_orderpb_cancel'=> 'f',
    			'd_orderpb_entry' => $dentry
    		)
    	);    	
    	$this->db->insert('tm_orderpb');
    }

    public function deletedetail($iproduct, $iproductgrade, $xiorderpb, $iarea, $icustomer, $iproductmotif){
    	$this->db->query("DELETE FROM tm_orderpb_item WHERE i_orderpb='$xiorderpb' and i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }

    public function insertdetail($iorderpb,$iarea,$icustomer,$dorderpb,$iproduct,$eproductname,$iproductmotif,$iproductgrade,$nquantity,$nstock,$eremark,$i){
    	$dentry	= current_datetime();
    	$this->db->set(
    		array(
    			'i_orderpb'       => $iorderpb,
    			'i_area'          => $iarea,
    			'i_customer'      => $icustomer,
    			'd_orderpb'       => $dorderpb,
    			'i_product'       => $iproduct,
    			'e_product_name'  => $eproductname,
    			'i_product_motif' => $iproductmotif,
    			'i_product_grade' => $iproductgrade,
    			'n_quantity_order'=> $nquantity,
    			'n_quantity_stock'=> $nstock,
    			'd_orderpb_entry' => $dentry,
    			'e_remark'        => $eremark,
    			'n_item_no'       => $i
    		)
    	);	
    	$this->db->insert('tm_orderpb_item');
    }
}

/* End of file Mmaster.php */
