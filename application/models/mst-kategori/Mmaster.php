<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT tr_categories.i_category, tr_categories.i_class, tr_categories.e_category_name, $i_menu as i_menu FROM tr_categories");

		$datatables->add('action', function ($data) {
            $icategory = trim($data['i_category']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kategori/cform/view/$icategory/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kategori/cform/edit/$icategory/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
        $this->db->select('*');
        $this->db->from('tr_categories');
        $this->db->join('tr_class', 'tr_categories.i_class = tr_class.i_class');
        $this->db->where('tr_categories.i_category', $id);
    return $this->db->get();
    }
    
    function get_kategori_barang(){
        $this->db->select('*');
        $this->db->from('tr_categories');
    return $this->db->get();
    }

    function get_kelas(){
        $this->db->select('*');
        $this->db->from('tr_class');
    return $this->db->get();
    }

	public function insert($icategory, $ecategoryname, $iclass){
        $data = array(
              'i_category'        => $icategory,
              'e_category_name'   => $ecategoryname,
              'i_class'           => $iclass, 
                          
              
    );
    
    $this->db->insert('tr_categories', $data);
    }

    public function update($icategory, $ecategoryname, $iclass){
        $data = array(
            'i_category'        => $icategory,
            'e_category_name'   => $ecategoryname, 
            'i_class'           => $iclass,                
    );

    $this->db->where('i_category', $icategory);
    $this->db->update('tr_categories', $data);
    }

}

/* End of file Mmaster.php */