<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select a.i_salesman, a.e_salesman_name, b.e_area_name, a.e_salesman_address, a.e_salesman_city, a.d_salesman_entry, '$i_menu' as i_menu from tr_salesman a JOIN tr_area b ON b.i_area = a.i_area");
		$datatables->add('action', function ($data) {
            $i_salesman = trim($data['i_salesman']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"salesman/cform/view/$i_salesman/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"salesman/cform/edit/$i_salesman/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_salesman');
        $this->db->join('tr_area','tr_salesman.i_area = tr_area.i_area');
        $this->db->where('i_salesman', $id);
        return $this->db->get();

	}

	public function insert($isalesman,$iarea,$esalesmanname,$esalesmanaddress,$esalesmancity,$esalesmanpostal,$dsalesmanentry,$fsalesmanaktif){
        $data = array(
            'i_salesman' => $isalesman,
            'i_area' => $iarea,
            'e_salesman_name' => $esalesmanname,
            'e_salesman_address' => $esalesmanaddress,
            'e_salesman_city' => $esalesmancity,
            'e_salesman_postal' => $esalesmanpostal,
            'd_salesman_entry' => $dsalesmanentry,
            'f_salesman_aktif' => $fsalesmanaktif,
        );
        $this->db->insert('tr_salesman', $data);
    }

    public function update($isalesman,$iarea,$esalesmanname,$esalesmanaddress,$esalesmancity,$esalesmanpostal,$dsalesmanentry,$fsalesmanaktif){
        $data = array(
            'e_salesman_name' => $esalesmanname,
            'e_salesman_address' => $esalesmanaddress,
            'e_salesman_city' => $esalesmancity,
            'e_salesman_postal' => $esalesmanpostal,
            'd_salesman_entry' => $dsalesmanentry,
            'f_salesman_aktif' => $fsalesmanaktif,
    );

    $this->db->where('i_salesman', $isalesman);
    $this->db->update('tr_salesman', $data);
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }	
}

/* End of file Mmaster.php */
