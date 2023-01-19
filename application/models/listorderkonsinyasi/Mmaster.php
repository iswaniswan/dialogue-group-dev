<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($folder,$imenu,$dfrom,$dto){
    	$ispg   = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        	SELECT
	        	a.i_orderpb,
	        	to_char(a.d_orderpb, 'dd-mm-yyyy') AS d_orderpb, 
	        	a.i_customer,
	        	'( '||a.i_customer||' ) - '||c.e_customer_name AS customer,
	        	a.f_orderpb_cancel,
	        	a.f_orderpb_rekap,
	        	a.i_spmb,
			    '$folder' AS folder,
			    '$imenu' AS imenu,
			    '$dfrom' AS dfrom,
			    '$dto' AS dto
			FROM
			    tm_orderpb a,
			    tr_spg b,
			    tr_customer c
			WHERE
			    a.i_spg = b.i_spg
			    AND a.i_customer = c.i_customer
			    /*AND b.i_spg = '$ispg'*/
			    AND a.d_orderpb >= to_date('$dfrom', 'dd-mm-yyyy')
			    AND a.d_orderpb <= to_date('$dto', 'dd-mm-yyyy')
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
            $frekap    = $data['f_orderpb_rekap'];
            $folder    = $data['folder'];
            $data      = '';
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iorderpb/$icustomer/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($imenu, 4) && $fcancel=='f' && $frekap=='f' && ($ispmb=='' || $ispmb==null)){
            	$data .= "<a href=\"#\" onclick='cancel(\"$iorderpb\",\"$icustomer\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('f_orderpb_cancel', function ($data) {
            if ($data['f_orderpb_cancel']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->hide('i_customer');
        $datatables->hide('f_orderpb_rekap');
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
    		where a.i_orderpb = '$iorderpb' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
    		order by a.n_item_no ", false);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0){
    		return $query->result();
    	}
    }

    public function deleteheader($xiorderpb, $iarea, $icustomer){
		$this->db->query("
			DELETE
			FROM
			    tm_orderpb
			WHERE
			    i_orderpb = '$xiorderpb'
			    AND i_area = '$iarea'
			    AND i_customer = '$icustomer'
		", FALSE);
    }
	
	public function insertheader($iorderpb, $dorderpb, $iarea, $ispg, $icustomer){
		$dentry		= current_datetime();
    	$this->db->set(
    		array(
				'i_orderpb'       => $iorderpb,
      			'i_area'          => $iarea,
      			'i_spg'           => $ispg,
      			'i_customer'      => $icustomer,
      			'd_orderpb'       => $dorderpb,
      			'f_orderpb_cancel'=> 'f',
      			'd_orderpb_entry' => $dentry
    		));    	
    	$this->db->insert('tm_orderpb');
	}

	public function deletedetail($iproduct, $iproductgrade, $iorderpb, $iarea, $icustomer, $iproductmotif,$i){
		$this->db->query("
			DELETE
			FROM
			    tm_orderpb_item
			WHERE
			    i_orderpb = '$iorderpb'
			    AND i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_customer = '$icustomer'
			    AND i_area = '$iarea'
			    AND n_item_no = '$i'
		");
    }
	
    public function insertdetail($iorderpb,$iarea,$icustomer,$dorderpb,$iproduct,$iproductmotif,$iproductgrade,$nquantityorder,$nquantitystock,$i,$eproductname,$eremark){
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
    			'n_quantity_order'=> $nquantityorder,
    			'n_quantity_stock'=> $nquantitystock,
    			'd_orderpb_entry' => $dentry,
    			'e_remark'        => $eremark,
    			'n_item_no'       => $i
    		)
    	);
    	$this->db->insert('tm_orderpb_item');
    }
}

/* End of file Mmaster.php */
