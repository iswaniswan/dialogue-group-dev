<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select a.i_city_typeperarea, a.e_city_typeperareaname, b.e_area_name, c.e_city_typename, '$i_menu' as i_menu from tr_city_typeperarea a JOIN tr_area b ON b.i_area = a.i_area JOIN tr_city_type c ON c.i_city_type = a.i_city_type");
		$datatables->add('action', function ($data) {
            $i_city_typeperarea = trim($data['i_city_typeperarea']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"jeniskotaarea/cform/view/$i_city_typeperarea/\",\"#main\")'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"jeniskotaarea/cform/edit/$i_city_typeperarea/\",\"#main\")'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->join('tr_area','tr_city_typeperarea.i_area = tr_area.i_area');
        $this->db->join('tr_city_type','tr_city_type.i_city_type = tr_city_typeperarea.i_city_type');
        $this->db->from('tr_city_typeperarea');
        $this->db->where('i_city_typeperarea', $id);
        return $this->db->get();

	}

	public function insert($icitytypeperarea,$ecitytypeperareaname,$iarea,$icitytype){
    $dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
    $data = array(
        'i_city_typeperarea' => $icitytypeperarea,
        'e_city_typeperareaname' => $ecitytypeperareaname,
        'i_area' => $iarea,
        'i_city_type' => $icitytype,
        'd_city_typeperareaentry' => $dentry,
    );
    
    $this->db->insert('tr_city_typeperarea', $data);
    }

    public function update($icitytypeperarea,$ecitytypeperareaname,$iarea,$icitytype){
    $dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
    $data = array(
        'e_city_typeperareaname' => $ecitytypeperareaname,
        'i_area' => $iarea,
        'i_city_type' => $icitytype,
        'd_city_typeperareaupdate' => $dentry,
    );

    $this->db->where('i_city_typeperarea', $icitytypeperarea);
    $this->db->update('tr_city_typeperarea', $data);
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacajeniskota(){
        return $this->db->order_by('i_city_type','ASC')->get('tr_city_type')->result();
    }	
}

/* End of file Mmaster.php */
