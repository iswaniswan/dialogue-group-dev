<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function data($folder, $total){
		  $datatables = new Datatables(new CodeigniterAdapter);
      $datatables->query("select row_number() over(order by a.i_op) as i, a.i_reff, a.i_op, b.e_supplier_name,'$folder' AS folder,'$total' AS total
                          from tm_op a, tr_supplier b
                          where a.i_supplier=b.i_supplier and a.f_op_cancel='f' and a.f_op_close='f'
                          order by a.i_op desc",false);
      $datatables->add('action', function ($data) {
      $op      = trim($data['i_op']);
      $folder = $data['folder'];
      $total  = $data['total'];
      $i      = trim($data['i']);
      $data = '';
      $data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\">&nbsp;&nbsp;&nbsp;&nbsp;
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"op".$i."\" value=\"".$op."\" type=\"hidden\">";
      return $data;
      });
      $datatables->hide('i');
      $datatables->hide('folder');
      $datatables->hide('total');
      return $datatables->generate();
    }

    public function total(){
      return $this->db->query("select a.*, b.e_supplier_name from tm_op a, tr_supplier b
                              where a.i_supplier=b.i_supplier and a.f_op_cancel='f' and a.f_op_close='f'",false);
    }
    
    function updateop($iop)
    {
		  $data = array(
		             'f_op_cancel' => 't'
		          );
		  $this->db->where('i_op', $iop);
		  $this->db->update('tm_op', $data); 
    }
}

/* End of file Mmaster.php */
