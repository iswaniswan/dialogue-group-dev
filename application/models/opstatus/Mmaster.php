<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select i_op_status, e_op_statusname, '$i_menu' as i_menu from tr_op_status");
		$datatables->add('action', function ($data) {
            $i_op_status = trim($data['i_op_status']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"opstatus/cform/view/$i_op_status/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"opstatus/cform/edit/$i_op_status/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_op_status');
        $this->db->where('i_op_status', $id);
        return $this->db->get();
	}

	public function insert($i_op_status,$e_op_statusname){
        $data = array(
            'i_op_status' => $i_op_status,
            'e_op_statusname' => $e_op_statusname,
            'd_entry' => current_datetime()
    );
    
    $this->db->insert('tr_op_status', $data);
    }

    public function update($i_op_status,$e_op_statusname){
        $data = array(
            'e_op_statusname' => $e_op_statusname,
    );

    $this->db->where('i_op_status', $i_op_status);
    $this->db->update('tr_op_status', $data);
    }
	
}

/* End of file Mmaster.php */
