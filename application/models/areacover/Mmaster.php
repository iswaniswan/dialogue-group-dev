<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_area, i_area_cover, e_area_cover_name, '$i_menu' as i_menu from tr_area_cover");
		$datatables->add('action', function ($data) {
            $i_area_cover = trim($data['i_area_cover']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"areacover/cform/view/$i_area_cover/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"areacover/cform/edit/$i_area_cover/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_area_cover');
        $this->db->join('tr_area','tr_area.i_area = tr_area_cover.i_area');
        $this->db->where('i_area_cover', $id);
        return $this->db->get();
	}	

    function simpan($iarea,$iareacover,$eareacovername){
        $dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_area' => $iarea,
            'i_area_cover' => $iareacover,
            'e_area_cover_name' => $eareacovername,
            'd_area_cover_entry' => $dentry,
        );
        $this->db->insert('tr_area_cover',$data);
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }
}

/* End of file Mmaster.php */
