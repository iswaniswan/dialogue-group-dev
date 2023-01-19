<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function updateplafond($icust,$iperiodeawal,$iperiodeakhir,$plafonacc){
        $dentry = date("Y-m-d");
        $iacc   = $this->session->userdata('username');
        $query=$this->db->query("
                                UPDATE
                                    tm_plafond
                                SET
                                    v_plafond_acc=$plafonacc,
                                    i_acc='$iacc',
                                    d_acc='$dentry'
                                WHERE
                                    i_customer_groupbayar='$icust'
                                    AND e_periode_awal = '$iperiodeawal'
                                    AND e_periode_akhir = '$iperiodeakhir'",false);
    }

    function updategroupbayar($icust,$plafonacc){
        $dentry = current_datetime();
        $sql	  = "	update tr_customer_groupbayar set v_plafon ='$plafonacc' where i_customer_groupbayar='$icust'";
  		$rssal	= pg_query($sql);
        $sql	    = "	select i_customer, i_customer_groupbayar, v_plafon from tr_customer_groupbayar where i_customer_groupbayar='$icust'";
		$sr=pg_query($sql);
		while($raw=pg_fetch_assoc($sr)){
		    $i_cust  = $raw['i_customer'];
		    $ibayar = $raw['i_customer_groupbayar'];
		    $byr_plafon = $raw['v_plafon'];
            $sql	    = "update tr_customer_groupar set v_flapond = $byr_plafon where i_customer = '$i_cust'";
    		$pr=pg_query($sql);
  		}
    }
}

/* End of file Mmaster.php */