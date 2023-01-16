<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $icustomer, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("Select a.i_notapb, a.d_notapb, b.e_customer_name, c.e_area_name, d.e_spg_name, a.i_customer, d.i_spg, a.i_cek, a.d_cek, '$dfrom' as dfrom, '$dto' as dto, '$i_menu' as i_menu
                            from tm_notapb a, tr_customer b, tr_area c, tr_spg d
                            where a.i_customer=b.i_customer
                            and a.i_area=c.i_area and b.i_area=c.i_area
                            and a.i_spg=d.i_spg and b.i_customer=d.i_customer
                            and a.i_customer='$icustomer'
                            and (a.d_notapb >= to_date('$dfrom','yyyy-mm-dd')
                            and a.d_notapb <= to_date('$dto','yyyy-mm-dd'))
                            order by a.d_notapb, a.i_notapb",false);
		$datatables->add('action', function ($data) {
            $i_notapb       = trim($data['i_notapb']);
            $i_customer     = trim($data['i_customer']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $d_notapb       = trim($data['d_notapb']);
            $i_spg          = trim($data['i_spg']);
            $i_menu         = $data['i_menu'];
            $data           = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"cekbonkons/cform/approve/$i_notapb/$i_customer/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('d_notapb', function ($data) {
        $d_notapb = $data['d_notapb'];
            if($d_notapb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_notapb) );
            }
        });

        $datatables->edit('e_customer_name', function ($data) {
            $e_customer_name = $data['e_customer_name'];
            $i_customer = $data['i_customer'];
            if($e_customer_name != ''){
                return $i_customer.' - '.$e_customer_name;
            }else{
                return $e_customer_name;
            }
        });

        $datatables->edit('e_spg_name', function ($data) {
            $e_spg_name = $data['e_spg_name'];
            $i_spg = $data['i_spg'];
            if($e_spg_name != ''){
                return $i_spg.' - '.$e_spg_name;
            }else{
                return $e_spg_name;
            }
        });

        $datatables->edit('i_cek', function ($data) {
            $i_cek = $data['i_cek'];
            $d_cek = $data['d_cek'];
            if($i_cek != '') {
                if($d_cek != '') {
                    $tmpck=explode('-',$d_cek);	
                    $tglck=$tmpck[2];
                    $blnck=$tmpck[1];
                    $thnck=$tmpck[0];
                    return (@$tmpck[2]!='' && $d_cek!='' )?($tglck.'-'.$blnck.'-'.$thnck):('System');
                } else {
                    return 'System';
                }
            } else {
                return 'Belum';
            }
        });

        $datatables->hide('i_menu');
        $datatables->hide('i_customer');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('d_cek');
        $datatables->hide('i_spg');

        return $datatables->generate();
	}

	function baca($inotapb, $icustomer){
        $this->db->select("a.*, b.e_area_name, c.e_customer_name, d.e_spg_name");
        $this->db->from("tm_notapb a");
        $this->db->join("tr_area b","a.i_area=b.i_area");
        $this->db->join("tr_customer c","a.i_customer=c.i_customer");
        $this->db->join("tr_spg d","a.i_spg=d.i_spg");
        $this->db->where("upper(a.i_notapb)", $inotapb);
        $this->db->where("upper(a.i_customer)", $icustomer);
        return $this->db->get();
    }

	function bacadetail($inotapb, $icustomer){
        $this->db->select("a.*, b.e_product_motifname");
        $this->db->from("tm_notapb_item a");
        $this->db->join("tr_product_motif b","a.i_product_motif=b.i_product_motif");
        $this->db->where("a.i_product=b.i_product");
        $this->db->where("upper(a.i_notapb)", $inotapb);
        $this->db->where("upper(a.i_customer)", $icustomer);
        return $this->db->get();
    }

    function updateheader($inotapb,$icustomer,$ecek,$user){
        $query 		= $this->db->query("SELECT current_timestamp as c");
        $row   		= $query->row();
        $dnotapbupdate	= $row->c;
        $data = array(
            'e_cek'		    => $ecek,
            'd_cek'		    => $dnotapbupdate,
            'i_cek'		    => $user
        );
        $this->db->where('i_notapb', $inotapb);
        $this->db->where('i_customer', $icustomer);
        $this->db->update('tm_notapb', $data); 
    }
}

/* End of file Mmaster.php */
