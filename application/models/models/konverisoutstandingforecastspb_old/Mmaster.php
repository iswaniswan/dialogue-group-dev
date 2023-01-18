<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT a.i_customer, b.e_customer_name, a.periode, a.i_product, c.e_product_basename, $i_menu as i_menu 
                            FROM tm_forecast a 
                            join tr_customer b on a.i_customer = b.i_customer 
                            join tr_product_base c on a.i_product = c.i_product_base");

		$datatables->add('action', function ($data) {
            $icustomer      = trim($data['i_customer']);
            $periode         = trim($data['periode']);
            $i_menu          = $data['i_menu'];
            $data            = '';

            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"konverisoutstandingforecastspb/cform/edit/$icustomer/$periode/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_customer');
        //$datatables->hide('i_product');
        return $datatables->generate();
	}

    function getforecast($icustomer, $periode){
        $this->db->select("a.i_customer, b.e_customer_name, a.periode, a.i_product, c.e_product_basename
                            FROM tm_forecast a 
                            join tr_customer b on a.i_customer = b.i_customer 
                            join tr_product_base c on a.i_product = c.i_product_base
                            where a.i_customer= '$icustomer' and a.periode= '$periode'");
        return $this->db->get();
    }

    function getforecastdetail($icustomer, $periode){
        $this->db->select("a.i_customer, b.e_customer_name, a.periode, a.i_product, c.e_product_basename, a.i_color, a.n_quantity, (a.n_quantity-a.n_sisa) as sisa, d.v_price, f.e_color_name
                            FROM tm_forecast a 
                            join tr_customer b on a.i_customer = b.i_customer 
                            join tr_product_base c on a.i_product = c.i_product_base
                            join tr_product_price d on a.i_product = d.i_product
                            join tr_customer_discount e on a.i_customer = e.i_customer
                            join tr_color f on a.i_color = f.i_color
                            where a.i_customer= '$icustomer' and a.periode= '$periode'");
        return $this->db->get();
    }

    function runningnumber($thbl){
      $th   = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SP'
                          and i_area='1'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' for update", false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              foreach($query->result() as $row){
                $terakhir=$row->max;
              }
              $nosj = $terakhir+1;
              $this->db->query("update tm_dgu_no 
                          set n_modul_no=$nosj
                          where i_modul='SP'
                          and e_periode='$asal' 
                          and i_area='1'
                          and substring(e_periode,1,4)='$th'", false);
              settype($nosj,"string");
              $a=strlen($nosj);
              while($a<7){
                $nosj="0".$nosj;
                $a=strlen($nosj);
              }
                $nosj  ="SP-".$thbl."-".$nosj;
              return $nosj;
          }else{
              $nosj  ="000001";
            $nosj  ="SP-".$thbl."-".$nosj;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SP','1','$asal',1)");
              return $nosj;
        }
    }

    function getdiscount($icustomer){
        $this->db->select(" n_customer_discount1,n_customer_discount2,n_customer_discount3 from tr_customer_discount where i_customer='$icustomer'");
        return $this->db->get();
    }

	public function insert($icustomer, $dateo, $ispb, $vtotaldiscount, $vtotalnetto, $vtotalgross){
        $dentry = date("Y-m-d H:i:s");

        $data = array(
                'i_op_code'         => $ispb,
                'i_customer'        => $icustomer,
                'i_branch'          => $icustomer,
                'd_op'              => $dateo,
                'v_total_gross'     => $vtotalgross,
                'v_total_discount'  => $vtotaldiscount,
                'v_total_netto'     => $vtotalnetto,
                'd_entry'           => $dentry,
                'i_area'            => 00     
    );
    $this->db->insert('tm_op', $data);
    }

    public function insertdetail($ispb, $iproduct, $eproduct, $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3, $vprice, $icolor){
        $dentry = date("Y-m-d H:i:s");

        $data = array(
                'i_op_code'             => $ispb,
                'i_product'             => $iproduct,
                'e_product_name'        => $eproduct,
                'n_customer_discount1'  => $ncustomerdiscount1,
                'n_customer_discount2'  => $ncustomerdiscount2,
                'n_customer_discount3'  => $ncustomerdiscount3,
                'v_price'               => $vprice,
                'd_entry'               => $dentry,
                'i_area'                => 00,
                'i_color'               => $icolor    
    );
    $this->db->insert('tm_op_item', $data);
    }
}
/* End of file Mmaster.php */