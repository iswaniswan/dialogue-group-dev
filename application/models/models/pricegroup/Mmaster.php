<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_price_group, e_price_groupname, '$i_menu' as i_menu from tr_price_group");
		$datatables->add('action', function ($data) {
            $i_price_group = trim($data['i_price_group']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"pricegroup/cform/view/$i_price_group/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"pricegroup/cform/edit/$i_price_group/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_price_group');
        $this->db->where('i_price_group', $id);
        return $this->db->get();

	}

    function baca($ipricegroup)
    {
		$this->db->select('i_price_group, e_price_groupname')->from('tr_price_group')->where('i_price_group', $ipricegroup);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
	}
	
	public function insert($ipricegroup,$epricegroupname){
        $data = array(
            'i_price_group' 	=> $ipricegroup,
            'e_price_groupname' => $epricegroupname
    );
    	$this->db->insert('tr_price_group', $data);
    }
    
    public function update($ipricegroup,$epricegroupname){
        $data = array(
            'e_price_groupname' => $epricegroupname,
    );

    $this->db->where('i_price_group', $ipricegroup);
    $this->db->update('tr_price_group', $data);
    }
}
?>
