<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select a.i_teritori, a.e_teritori_name, b.e_country_name, to_char(a.d_teritori_entry,'dd-mm-yyyy'), '$i_menu' as i_menu from tr_teritori a join 
            tr_country b on b.i_country = a.i_country");
		$datatables->add('action', function ($data) {
            $i_teritori = trim($data['i_teritori']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"teritori/cform/view/$i_teritori/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"teritori/cform/edit/$i_teritori/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_teritori');
        $this->db->join('tr_country', 'tr_country.i_country = tr_teritori.i_country');
        $this->db->where('i_teritori', $id);
        return $this->db->get();

	}

	public function insert($iteritori,$eteritoriname,$icountry){
        $data = array(
            'i_teritori' => $iteritori,
            'e_teritori_name' => $eteritoriname,
            'i_country' => $icountry,
            'd_teritori_entry' => $this->db->query('SELECT current_timestamp AS c')->row()->c,
    );
    
    $this->db->insert('tr_supplier_group', $data);
    }

    public function update($iteritori,$eteritoriname,$icountry){
        $data = array(
            'e_teritori_name' => $eteritoriname,
            'i_country' => $icountry,
            'd_teritori_update' => $this->db->query('SELECT current_timestamp AS c')->row()->c,
    );

    $this->db->where('i_teritori', $iteritori);
    $this->db->update('tr_teritori', $data);
    }

    public function bacanegara(){
        return $this->db->order_by('i_country')->get('tr_country')->result();
    }



	
}

/* End of file Mmaster.php */
