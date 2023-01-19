<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function data($folder, $total){
		  $datatables = new Datatables(new CodeigniterAdapter);
      $datatables->query("select distinct(a.i_op) as op, row_number() over(order by a.i_op) as i, a.d_op, a.i_reff, a.d_reff, a.i_area, o.i_do, o.d_do, b.e_supplier_name, c.e_area_name,'$folder' AS folder,'$total' AS total
                          from tm_op a 
                          left join tm_do o on (a.i_op=o.i_op),tr_supplier b, tr_area c
                          where a.i_supplier=b.i_supplier 
                          and a.f_op_cancel='f' 
                          and a.i_area=c.i_area 
                          and a.f_op_close='f'
                          order by op desc",false);
      $datatables->add('action', function ($data) {
      $op      = trim($data['op']);
      $iarea   = trim($data['i_area']);
      $ireff   = trim($data['i_reff']);
      $folder = $data['folder'];
      $total  = $data['total'];
      $i      = trim($data['i']);
      $data = '';
      $data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\">&nbsp;&nbsp;&nbsp;&nbsp;
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"op".$i."\" value=\"".$op."\" type=\"hidden\">
                <input name=\"ireff".$i."\" value=\"".$ireff."\" type=\"hidden\">
                <input name=\"iarea".$i."\" value=\"".$iarea."\" type=\"hidden\">";
      return $data;
      });
      $datatables->hide('i_area');
      $datatables->hide('i');
      $datatables->hide('folder');
      $datatables->hide('total');
      return $datatables->generate();
    }

    public function total(){
      return $this->db->query("select a.i_op from tm_op a 
                              left join tm_do o on (a.i_op=o.i_op), tr_supplier b, tr_area c
                              where a.i_supplier=b.i_supplier and a.f_op_cancel='f' and a.i_area=c.i_area and a.f_op_close='f'",false);
  }
    
    function updateop($iop)
    {
		  $data = array(
		             'f_op_close' => 't'
		          );
		  $this->db->where('i_op', $iop);
		  $this->db->update('tm_op', $data); 
    }

    function updatespb($ireff,$iop,$iarea)
    {
	    $tmp=explode('-',$ireff);
	    if($tmp[0]=='SPB'){
        $this->db->query(" 	update tm_spb_item set i_op='$iop' where i_spb='$ireff' and i_area='$iarea' and n_order>n_deliver
                            and i_product in (select i_product from tm_op_item where i_op='$iop')");
        $this->db->query(" 	update tm_spb set f_spb_opclose='t' where i_spb='$ireff' and i_area='$iarea'");
	    }elseif($tmp[0]=='SPMB'){
        $this->db->query(" 	update tm_spmb_item set i_op='$iop' where i_spmb='$ireff' and n_order>n_deliver
                            and i_product in (select i_product from tm_op_item where i_op='$iop')");
		    $this->db->query(" 	update tm_spmb set f_spmb_opclose='t' where upper(i_spmb)='$ireff'",false);
	    }
    }
}

/* End of file Mmaster.php */
