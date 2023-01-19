<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select a.i_city, a.e_city_name, a.i_area, b.e_area_name, '$i_menu' as i_menu from tr_city a JOIN tr_area b ON b.i_area = a.i_area where a.i_city_status != '0'");
		$datatables->add('action', function ($data) {
            $i_city = trim($data['i_city']);
            $i_area = trim($data['i_area']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"kota/cform/view/$i_city/$i_area/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"kota/cform/edit/$i_city/$i_area/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($icity,$iarea){
        $this->db->select('a.*, b.e_area_name, c.e_city_typename, d.e_city_groupname, e.e_city_statusname');
        $this->db->join('tr_area b','a.i_area = b.i_area');
        $this->db->join('tr_city_type c','a.i_city_type = c.i_city_type');
        $this->db->join('tr_city_group d','a.i_city_group = d.i_city_group');
        $this->db->join('tr_city_status e','a.i_city_status = e.i_city_status');
        $this->db->where('a.i_city',$icity);
        $this->db->where('a.i_area',$iarea);
        return $this->db->get('tr_city a');
        /*return $this->db->join('tr_area b','b.i_area = a.i_area')->join('tr_city_type c','c.i_city_type = a.i_city_type')->join('tr_city_typeperarea d','d.i_city_typeperarea = a.i_city_typeperarea')->join('tr_city_status e','e.i_city_status = a.i_city_status')->join('tr_city_group f','f.i_city_group = a.i_city_group')->where('i_city', $icity)->where('a.i_area', $iarea)->get('tr_city a')->row();*/
	}

	public function insert($icity,$iarea,$icitytype,$icitytypearea,$icitygroup,$icitystatus,$ecityname,$ntoleransipusat,$ntoleransicabang){
        $dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_city' => $icity,
            'i_area' => $iarea,
            'i_city_type' => $icitytype,
            'i_city_typeperarea' => $icitytypeperarea,
            'i_city_group' => $icitytypegroup,
            'i_city_status' => $icitytypestatus,
            'e_city_name' => $ecityname,
            'd_city_entry' => $dentry,
            'n_toleransi_pusat' => $ntoleransipusat,
            'n_toleransi_cabang' => $ntoleransicabang,
    );
    
    $this->db->insert('tr_city', $data);
    }

    public function update($icity,$iarea,$icitytype,$icitytypearea,$icitygroup,$icitystatus,$ecityname,$ntoleransipusat,$ntoleransicabang){
        $dupdate = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_area' => $iarea,
            'i_city_type' => $icitytype,
            'i_city_typeperarea' => $icitytypeperarea,
            'i_city_group' => $icitytypegroup,
            'i_city_status' => $icitytypestatus,
            'e_city_name' => $ecityname,
            'd_city_entry' => $dupdate,
            'n_toleransi_pusat' => $ntoleransipusat,
            'n_toleransi_cabang' => $ntoleransicabang,
    );

    $this->db->where('i_city', $icity);
    $this->db->update('tr_city', $data);
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacajeniskota(){
        return $this->db->order_by('i_city_type','ASC')->get('tr_city_type')->result();
    }

    public function bacagrupkota(){
        return $this->db->order_by('i_city_group','ASC')->get('tr_city_group')->result();
    }

    public function bacastatuskota(){
        return $this->db->order_by('i_city_status', 'ASC')->get('tr_city_status')->result();
    }
}

/* End of file Mmaster.php */
