<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_brand.i_brand, tr_brand.e_brand_name, tr_brand.c_brand_code, $i_menu as i_menu FROM tr_brand");

		$datatables->add('action', function ($data) {
            $ibrand = trim($data['i_brand']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-merk/cform/view/$ibrand/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-merk/cform/edit/$ibrand/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_brand');
    $this->db->where('i_brand', $id);

    return $this->db->get();
    }
    
    function get_brand(){
        $this->db->select('*');
        $this->db->from('tr_brand');
    return $this->db->get();
    }

	public function insert($ibrand, $ebrandname, $ebrandcode){
        $dentry = date("d F Y H:i:s");
        $brand = $this->db->query("SELECT i_brand FROM tr_brand ORDER BY i_brand DESC LIMIT 1");
        if ($brand->num_rows() > 0) {
            $row_brand = $brand->row();
            $ibrand= $row_brand->i_brand+1;
        }
        else
            $ibrand = 1;

        $data = array(
              'i_brand'        => $ibrand,
              'e_brand_name'   => $ebrandname,    
              'c_brand_code'   => $ebrandcode,    
              'd_entry'        => $dentry,    
    );
    
    $this->db->insert('tr_brand', $data);
    }

    public function update($ibrand, $ebrandname, $ebrandcode){
        $dupdate = date("d F Y H:i:s");
        $data = array(
            'i_brand'        => $ibrand,
            'e_brand_name'   => $ebrandname,    
            'c_brand_code'   => $ebrandcode, 
            'd_update'       => $dupdate,

    );

    $this->db->where('i_brand', $ibrand);
    $this->db->update('tr_brand', $data);
    }

}

/* End of file Mmaster.php */