<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select a.i_area, a.e_area_name, b.e_area_typename, to_char(a.d_area_entry,'dd-mm-yyyy'), '$i_menu' as i_menu from tr_area a left join tr_area_type b ON
            b.i_area_type = a.i_area_type");
		$datatables->add('action', function ($data) {
            $i_area = trim($data['i_area']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"area/cform/view/$i_area/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"area/cform/edit/$i_area/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_area');
        $this->db->join('tr_area_type','tr_area_type.i_area_type = tr_area.i_area_type');
        $this->db->join('tr_store','tr_store.i_store = tr_area.i_store');
        $this->db->where('i_area', $id);
        return $this->db->get();

	}

	public function insert($iarea,$eareaname,$iareatype,$istore,$eareaaddress,$eareacity,$eareashortname,$eareaphone,$nareatoleransi,$earearemark){
        $dareaentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_area' => $iarea,
            'e_area_name' => $eareaname,
            'i_area_type' => $iareatype,
            'i_store' => $istore,
            'e_area_address' => $eareaaddress,
            'e_area_city' => $eareacity,
            'e_area_shortname' => $eareashortname,
            'e_area_phone' => $eareaphone,
            'n_area_toleransi' => $nareatoleransi,
            'd_area_entry' => $dareaentry,
            'e_area_remark' => $earearemark,
        );
        
        $this->db->insert('tr_area', $data);
    }

    public function update($iarea,$eareaname,$iareatype,$istore,$eareaaddress,$eareacity,$eareashortname,$eareaphone,$nareatoleransi,$earearemark){
        $data = array(
            'e_area_name' => $eareaname,
            'i_area_type' => $iareatype,
            'i_store' => $istore,
            'e_area_address' => $eareaaddress,
            'e_area_city' => $eareacity,
            'e_area_shortname' => $eareashortname,
            'e_area_phone' => $eareaphone,
            'n_area_toleransi' => $nareatoleransi,
            'e_area_remark' => $earearemark,
        );

        $this->db->where('i_area', $iarea);
        $this->db->update('tr_area', $data);
    }

    public function bacajenisarea(){
        return $this->db->get('tr_area_type')->result();
    }

    public function bacagudang(){
        return $this->db->order_by('i_store','ASC')->get('tr_store')->result();
    }



	
}

/* End of file Mmaster.php */
