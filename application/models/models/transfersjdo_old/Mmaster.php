<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function bacado($periode){       
        $this->db->select("distinct a.i_do, b.i_do, b.d_do, c.i_op, c.d_op, b.i_customer, d.i_code, d.e_branch_name
                          FROM tm_do_item a 
                          INNER JOIN tm_do b ON b.i_do=a.i_do
                          INNER JOIN tm_opbb c ON c.i_op=a.i_op
                          INNER JOIN tr_branch d ON d.i_branch_code=b.i_branch
                          WHERE b.f_do_cancel='f' 
                          AND b.f_transfer='f' 
                          AND b.d_do >= '$periode' 
                          AND length(a.e_product_name)>10 
                          ORDER BY b.i_do ASC",false);
        return $this->db->get();
    }

    // function insert($ido, $ddo, $iop, $dop, $ibranch, $iproduct, $eproduct, $ndeliver, $vdogross, $icustomer, $eremark, $cek, $jml){
    function insert($ido, $ddo, $iop, $dop, $ibranch, $icustomer){
        $dentry = date("d F Y H:i:s");
        $query1 = $this->db->query("SELECT * from tm_do_item where i_do='$ido'");
        if ($query1->num_rows() > 0){
              $hasil = $query1->row();
              $iproduct = $hasil->i_product;
        $qcek = $this->db->query("SELECT i_do_code, i_op_code, i_branch, i_product FROM tm_trans_do WHERE i_do_code='$ido' AND  i_op_code='$iop' AND i_branch='$ibranch' AND i_product='$iproduct' ");
      }
        if($qcek->num_rows()==0){
          $query2 = $this->db->query("SELECT * from tm_do_item where i_do='$ido'");
          if ($query2->num_rows() > 0){
              $hasil = $query2->result();
              foreach ($hasil as $row1) { 
                $iproduct = $row1->i_product;
                $eproduct = $row1->e_product_name;
                $ndeliver = $row1->n_deliver;
                $vdogross = $row1->v_do_gross;
                $eremark  = $row1->e_remark;
              
            $data   = array(
                      'i_do_code'     =>$ido,
                      'i_op_code'     =>$iop,
                      'd_do'          =>$ddo,
                      'd_op'          =>$dop,
                      'i_customer'    =>$icustomer,
                      'i_branch'      =>$ibranch,
                      'i_product'     =>$iproduct,
                      'e_product_name'=>$eproduct,
                      'n_deliver'     =>$ndeliver,
                      'v_do_gross'    =>$vdogross,
                      'e_note'        =>$eremark,
                      'd_entry'       =>$dentry
            );
            $this->db->insert('tm_trans_do', $data);
            $this->db->query(" UPDATE tm_do SET f_transfer='t' WHERE i_do='$ido' AND f_do_cancel='f' ");
          }
        }
        }else{
              $this->db->query(" UPDATE tm_trans_do SET n_deliver='$njmldo', v_do_gross='$vdogross' WHERE i_do_code='$idocode' AND i_op_code='$iopcode' AND i_branch='$ibranch' AND i_product='$iproduct' ");      

              $this->db->query(" UPDATE tm_do SET f_transfer='t' WHERE i_do='$ido' AND f_do_cancel='f' ");
        }
    }
}

/* End of file Mmaster.php */
