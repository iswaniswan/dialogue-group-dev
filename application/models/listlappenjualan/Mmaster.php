<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_nota, a.d_nota, a.i_seri_pajak, a.d_pajak, a.i_area, b.e_customer_name, a.v_nota_netto from tm_nota a, tr_customer b
							where a.i_customer=b.i_customer 
							and a.f_ttb_tolak='f' and not a.i_seri_pajak isnull
							and not a.i_nota isnull
							and a.d_nota >= to_date('$dfrom','yyyy-mm-dd') 
							and a.d_nota <= to_date('$dto','yyyy-mm-dd')
							ORDER BY a.d_pajak, a.i_seri_pajak",false);

        $datatables->edit('d_nota', function ($data) {
        $d_nota = $data['d_nota'];
        if($d_nota == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($d_nota) );
        }
        });

        
        $datatables->edit('d_pajak', function ($data) {
            $d_pajak = $data['d_pajak'];
            if($d_pajak == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_pajak) );
            }
        });

        return $datatables->generate();
	}

	function cekfaktur($ifakturkomersial){
      $ada=false;
      $this->db->select("i_faktur_komersial from tm_nota where i_faktur_komersial='$ifakturkomersial'", false);
		  $query = $this->db->get();
	  	if ($query->num_rows() > 0){
    		$ada=true;
      	}else{
      	  	$this->db->select("i_faktur_komersial from tm_bbk_pajak where i_faktur_komersial='$ifakturkomersial'", false);
			    $quer = $this->db->get();
			if ($quer->num_rows() > 0){
      	    	$ada=true;
      	  	}else{
      	    	$ada=false;
      	  	}
      	}
      return $ada;
	}
	
  	function insertfkom($ifkom,$ibbk){
      $this->db->select("* from tm_bbk where i_bbk = '$ibbk' and i_bbk_type='03'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		  	foreach($query->result() as $row){
        		$d = new DateTime($row->d_bbk);
        		$tglpajak=$d->format( 'Y-m-t' );
        		$row->e_remark=str_replace("'","''",$row->e_remark);
        		$this->db->query("insert into tm_bbk_pajak (i_bbk, i_bbk_type, i_faktur_komersial, i_refference_document, i_area, d_pajak, 
            	                i_salesman, i_supplier,d_bbk, e_remark) values
            	                ('$row->i_bbk','$row->i_bbk_type','$ifkom','$row->i_refference_document','$row->i_area',
            	                 '$tglpajak','$row->i_salesman','$row->i_supplier','$row->d_bbk','$row->e_remark')");
        	}
	    }
    }


    public function getAll($dfrom, $dto){
      return $this->db->query("
        SELECT
            a.*,
            b.e_customer_name,
            b.f_customer_pkp
        FROM
            tm_nota a,
            tr_customer b
        WHERE
            a.i_customer = b.i_customer
            AND NOT a.i_seri_pajak ISNULL
            AND a.f_ttb_tolak = 'f'
            AND NOT a.i_nota ISNULL
            AND a.d_nota >= TO_DATE('$dfrom', 'dd-mm-yyyy')
            AND a.d_nota <= TO_DATE('$dto', 'dd-mm-yyyy')
        ORDER BY
            a.i_nota",false);
    }
}

/* End of file Mmaster.php */