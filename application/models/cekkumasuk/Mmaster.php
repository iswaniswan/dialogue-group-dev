<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $iarea, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select c.e_area_name, a.i_kum, a.d_kum, a.e_bank_name, a.i_customer, b.e_customer_name, a.v_jumlah, a.v_sisa, a.i_cek, a.d_cek,
                            '$dfrom' as dfrom, '$dto' as dto, '$i_menu' as i_menu, a.i_area
                            from tm_kum a, tr_customer b, tr_area c
                            where a.i_area='$iarea'
                            and d_kum >= to_date('$dfrom','yyyy-mm-dd') 
                            and d_kum <= to_date('$dto','yyyy-mm-dd') 
                            and a.i_customer = b.i_customer
                            and a.i_area = c.i_area 
                            and a.f_kum_cancel='f'",false);
		$datatables->add('action', function ($data) {
            $i_kum    = trim($data['i_kum']);
            $i_area    = trim($data['i_area']);
            $dfrom    = trim($data['dfrom']);
            $dto    = trim($data['dto']);
            $d_kum    = trim($data['d_kum']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"cekkumasuk/cform/edit/$i_kum/$i_area/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('d_kum', function ($data) {
        $d_kum = $data['d_kum'];
            if($d_kum == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_kum) );
            }
        });

        $datatables->edit('e_customer_name', function ($data) {
            $e_customer_name = $data['e_customer_name'];
            $i_customer = $data['i_customer'];
            if($e_customer_name == ''){
                return '';
            }else{
                return '('.$i_customer.')'.'-'.$e_customer_name;
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
        $datatables->hide('d_cek');
        $datatables->hide('i_area');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_customer');

        return $datatables->generate();
	}

	function bacakum($iarea,$ikum,$y_kum){
        $this->db->select("a.*, b.e_area_name, c.e_customer_name, d.e_salesman_name");
        $this->db->from("tm_kum a");
        $this->db->join("tr_area b","a.i_area = b.i_area");
        $this->db->join("tr_customer c","c.i_customer = a.i_customer");
        $this->db->join("tr_salesman d","d.i_salesman = a.i_salesman");
        $this->db->where("a.i_area",$iarea);
        $this->db->where("a.i_kum",$ikum);
        $this->db->where("a.n_kum_year",$y_kum);
		return $this->db->get();
	}
	
	function updatecekku($ecek,$user,$ikum,$dkum,$iarea){
		$query 	= $this->db->query("SELECT to_char(current_timestamp,'yyyy-mm-dd') as c");
		$row   	= $query->row();
		$dentry	= $row->c;
        	$data = array(
				'e_cek'		=> $ecek,
				'd_cek'		=> $dentry,
				'i_cek'		=> $user
  			);
        $this->db->where('i_kum', $ikum);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_kum', $data);
  	}
}

/* End of file Mmaster.php */
