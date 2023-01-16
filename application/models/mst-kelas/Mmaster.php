<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_class.i_class, tr_class.e_class_name, tr_class.d_entry, tr_class.d_update, $i_menu as i_menu FROM tr_class");

		$datatables->add('action', function ($data) {
            $ikelas = trim($data['i_class']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kelas/cform/view/$ikelas/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kelas/cform/edit/$ikelas/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_class');
    $this->db->where('i_class', $id);

    return $this->db->get();
    }
    
    function get_kelas_barang(){
        $this->db->select('*');
        $this->db->from('tr_class');
    return $this->db->get();
    }

	public function insert($ikelas, $enamakelas){
        $dentry = date("d F Y H:i:s");
        $data = array(
              'i_class'        => $ikelas,
              'e_class_name'   => $enamakelas, 
              'd_entry'        => $dentry,                
              
    );
    
    $this->db->insert('tr_class', $data);
    }

    public function update($ikelas, $enamakelas){
        $dupdate = date("d F Y H:i:s");
        $data = array(
              'i_class'       => $ikelas,
              'e_class_name'  => $enamakelas,
              'd_update'      => $dupdate,               
    );

    $this->db->where('i_class', $ikelas);
    $this->db->update('tr_class', $data);
    }

}

/* End of file Mmaster.php */