<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_layout.i_layout, tr_layout.e_layout_name, tr_layout.d_entry, tr_layout.d_update, $i_menu as i_menu FROM tr_layout");

		$datatables->add('action', function ($data) {
            $ilayout = trim($data['i_layout']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-layout/cform/view/$ilayout/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-layout/cform/edit/$ilayout/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_layout');
    $this->db->where('i_layout', $id);

    return $this->db->get();
    }
    
    function get_brand(){
        $this->db->select('*');
        $this->db->from('tr_layout');
    return $this->db->get();
    }

	public function insert($ilayout, $elayoutname){
        $dentry = date("d F Y H:i:s");
        $data = array(
              'i_layout'        => $ilayout,
              'e_layout_name'   => $elayoutname,    
              'd_entry'         => $dentry,        
    );
    
    $this->db->insert('tr_layout', $data);
    }

    public function update($ilayout, $elayoutname){
        $dupdate = date("d F Y H:i:s");
        $data = array(
            'i_layout'        => $ilayout,
            'e_layout_name'   => $elayoutname,    
            'd_update'         => $dupdate, 
    );

    $this->db->where('i_layout', $ilayout);
    $this->db->update('tr_layout', $data);
    }

}

/* End of file Mmaster.php */