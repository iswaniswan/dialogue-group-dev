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
        ", FALSE);
    }

    public function bacacustomer($iarea,$ispg){
        return $this->db->query("  
                                SELECT 
                                    a.i_customer, 
                                    a.i_area, 
                                    a.e_spg_name, 
                                    b.e_area_name, 
                                    c.e_customer_name 
                                FROM 
                                    tr_spg a, 
                                    tr_area b, 
                                    tr_customer c
                                WHERE 
                                    upper(a.i_spg) = '$ispg' 
                                    AND a.i_area='$iarea' 
                                    AND a.i_area=b.i_area 
                                    AND a.i_customer=c.i_customer
                                ",false)->result();
    }

    public function data($dfrom,$dto,$ispg,$i_menu){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT
                                a.i_notapb, 
                                a.d_notapb, 
                                a.i_spg, 
                                a.i_customer,
                                c.e_customer_name, 
                                a.v_notapb_gross, 
                                a.v_notapb_discount,   
                                a.f_notapb_cancel, 
                                a.f_spb_rekap, 
                                a.i_spb, 
                                a.i_cek,
                                '$i_menu' as i_menu,
                                '$dfrom' as dfrom,
                                '$dto' as dto
                            FROM 
                                tm_notapb a, 
                                tr_spg b, 
                                tr_customer c
                            WHERE 
                                a.i_spg=b.i_spg 
                                AND a.i_customer=c.i_customer
                                AND b.i_spg='$ispg' 
                                AND a.d_notapb >= to_date('$dfrom','dd-mm-yyyy') 
                                AND a.d_notapb <= to_date('$dto','dd-mm-yyyy')
                            ORDER BY 
                                a.i_notapb"
                            );
        $datatables->add('action', function ($data) {
            $i_notapb           = trim($data['i_notapb']);
            $i_customer         = trim($data['i_customer']);
            $f_notapb_cancel    = trim($data['f_notapb_cancel']);
            $f_spb_rekap        = trim($data['f_spb_rekap']);
            $i_spb              = trim($data['i_spb']);
            $i_cek              = trim($data['i_cek']);
            $i_menu             = $data['i_menu'];
            $dfrom              = $data['dfrom'];
            $dto                = $data['dto'];
            $data               = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"listpenjualankonsinyasi-bon/cform/edit/$i_notapb/$i_customer/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }

            if($f_notapb_cancel == 'f' &&  $f_spb_rekap == 'f' && ($i_spb == '' || $i_spb == null) && ($i_cek == '' || $i_cek == null)){
                if(check_role($i_menu, 4)){
                    $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_notapb\",\"$i_customer\"); return false;'><i class='fa fa-trash'></i></a>";
                }
            }

			return $data;
        });

        $datatables->edit('v_notapb_gross', function ($data) {
            $v_notapb_gross = $data['v_notapb_gross'];
                return number_format($v_notapb_gross);
        });

        $datatables->edit('d_notapb', function ($data) {
            $d_notapb = $data['d_notapb'];
            if($d_notapb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_notapb) );
            }
        });

        $datatables->edit('i_customer', function ($data) {
            $i_customer      = $data['i_customer'];
            $e_customer_name = $data['e_customer_name'];
            if($i_customer == ''){
                return '';
            }else{
                return "(".$i_customer.")"." - ".$e_customer_name;
            }
        });

        $datatables->hide('i_menu');
        $datatables->hide('i_spg');
        $datatables->hide('e_customer_name');
        $datatables->hide('v_notapb_discount');
        $datatables->hide('f_notapb_cancel');
        $datatables->hide('f_spb_rekap');
        $datatables->hide('i_spb');
        $datatables->hide('i_cek');
        $datatables->hide('dfrom');
        $datatables->hide('dto');

        return $datatables->generate();  
    }

    function baca($inotapb,$icustomer){
        $query = $this->db->query(" 
                                SELECT 
                                    a.*, 
                                    b.e_area_name, 
                                    c.e_customer_name, 
                                    d.e_spg_name 
                                FROM 
                                    tm_notapb a, 
                                    tr_area b, 
                                    tr_customer c, 
                                    tr_spg d
                                WHERE 
                                    a.i_area=b.i_area 
                                    AND a.i_customer=c.i_customer 
                                    AND a.i_spg=d.i_spg
                                    AND a.i_notapb ='$inotapb' 
                                    AND a.i_customer='$icustomer'"
                                , false);
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacadetail($inotapb,$icustomer){
        $query = $this->db->query(" 
                                SELECT 
                                    a.*, 
                                    b.e_product_motifname 
                                FROM 
                                    tm_notapb_item a, 
                                    tr_product_motif b
                                WHERE 
                                    a.i_notapb = '$inotapb' 
                                    AND a.i_customer='$icustomer' 
                                    AND a.i_product=b.i_product 
                                    AND a.i_product_motif=b.i_product_motif
                                ORDER BY 
                                    a.n_item_no "
                                , false);
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    public function deletedetail($iproduct, $iproductgrade, $inotapb, $iarea, $icustomer, $iproductmotif, $vunitprice) {
		$this->db->query("
                        DELETE 
                        FROM 
                            tm_notapb_item 
                        WHERE 
                            i_notapb='$inotapb' 
                            and i_product='$iproduct' 
                            and i_product_grade='$iproductgrade' 
                            and i_product_motif='$iproductmotif' 
                            and i_customer='$icustomer' 
                            and v_unit_price=$vunitprice
                        ");
    }
	
    public function deleteheader($xinotapb, $iarea, $icustomer) {
		$this->db->query("
                        DELETE 
                        FROM 
                            tm_notapb 
                        WHERE 
                            i_notapb='$xinotapb' 
                            AND i_area='$iarea' 
                            AND i_customer='$icustomer'
                        ");
    }

    function insertheader($inotapb, $dnotapb, $iarea, $ispg, $icustomer, $nnotapbdiscount, $vnotapbdiscount, $vnotapbgross){
      $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dentry	= $row->c;
    	$this->db->set(
    		array(
			    'i_notapb'          => $inotapb,
                'i_area'            => $iarea,
                'i_spg'             => $ispg,
                'i_customer'        => $icustomer,
                'd_notapb'          => $dnotapb,
                'n_notapb_discount' => $nnotapbdiscount,
                'v_notapb_discount' => $vnotapbdiscount,
                'v_notapb_gross'    => $vnotapbgross,
                'f_notapb_cancel'   => 'f',
                'd_notapb_entry'    => $dentry
    		    )
    	    );
    	
    	$this->db->insert('tm_notapb');
    }

    function insertdetail($inotapb,$iarea,$icustomer,$dnotapb,$iproduct,$iproductmotif,$iproductgrade,$nquantity,$vunitprice,$i,$eproductname,$ipricegroupco,$eremark){
        $query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
					'i_notapb'        => $inotapb,
                    'i_area'          => $iarea,
                    'i_customer'      => $icustomer,
                    'd_notapb'        => $dnotapb,
                    'i_product'       => $iproduct,
                    'i_product_motif' => $iproductmotif,
                    'i_product_grade' => $iproductgrade,
                    'n_quantity'      => $nquantity,
                    'v_unit_price'    => $vunitprice,
                    'd_notapb_entry'  => $dentry,
                    'n_item_no'       => $i,
                    'e_product_name'  => $eproductname,
                    'i_price_groupco' => $ipricegroupco,
                    'e_remark'        => $eremark
    		    )
    	);
    	$this->db->insert('tm_notapb_item');
    }

    public function delete($inotapb,$icustomer) {
        $this->db->query("
                        UPDATE 
                            tm_notapb 
                        SET 
                            f_notapb_cancel='t' 
                        WHERE 
                            i_notapb='$inotapb' 
                            AND i_customer='$icustomer'
                        ");
		return TRUE;
    }
}

/* End of file Mmaster.php */
