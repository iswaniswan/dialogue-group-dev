<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($periode,$folder, $total){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select row_number() over(order by bbk) as i, bbk, tanggal, ket, sum(qty*harga) as nilai, '$folder' AS folder,'$total' AS total
							from(select 
							a.i_bbk as bbk, a.d_bbk as tanggal, b.e_remark as ket, b.n_quantity as qty, b.v_unit_price as harga 
							from tm_bbk a
							join tm_bbk_item b on (a.i_bbk=b.i_bbk) 
							join tr_area c on (a.i_area=c.i_area)
							where a.i_bbk not in(select i_bbk from tm_bbk_pajak)
							and to_char(a.d_bbk,'yyyymm') = '$periode'
							and a.i_bbk_type='03' and f_bbk_cancel='f')
							as x 
							group by bbk, tanggal, ket");
		$datatables->add('action', function ($data) {
			$bbk      = trim($data['bbk']);
			$dbbk      = trim($data['tanggal']);
			$nilai      = trim($data['nilai']);
			$folder = $data['folder'];
			$total  = $data['total'];
			$i      = trim($data['i']);
			$data = '';
			$data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\">&nbsp;&nbsp;&nbsp;&nbsp;
					  <label class=\"custom-control custom-checkbox\">
					  <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
					  <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
					  <input name=\"bbk".$i."\" value=\"".$bbk."\" type=\"hidden\">
					  <input name=\"tanggal".$i."\" value=\"".$dbbk."\" type=\"hidden\">
					  <input name=\"nilai".$i."\" value=\"".$nilai."\" type=\"hidden\">";
			return $data;
		});
		$datatables->hide('i');
      	$datatables->hide('folder');
      	$datatables->hide('total');
        $datatables->edit('tanggal', function ($data) {
        $d_bbk = $data['tanggal'];
        if($d_bbk == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($d_bbk) );
        }
        });

        return $datatables->generate();
	}

	public function total($periode){
		return $this->db->query("select a.i_bbk as bbk, a.d_bbk as tanggal, b.e_remark as ket, b.n_quantity as qty, b.v_unit_price as harga 
								from tm_bbk a
								join tm_bbk_item b on (a.i_bbk=b.i_bbk) 
								join tr_area c on (a.i_area=c.i_area)
								where a.i_bbk not in(select i_bbk from tm_bbk_pajak)
								and to_char(a.d_bbk,'yyyymm') = '$periode'
								and a.i_bbk_type='03' and f_bbk_cancel='f' group by bbk, tanggal, qty, harga, ket",false);
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
}

/* End of file Mmaster.php */