<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_status.i_status, tr_status.e_status_name, tr_status.d_entry, tr_status.d_update, $i_menu as i_menu FROM tr_status");

		$datatables->add('action', function ($data) {
            $istatus = trim($data['i_status']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-status/cform/view/$istatus/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-status/cform/edit/$istatus/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_status');
    $this->db->where('i_status', $id);

    return $this->db->get();
    }
    
    function get_status(){
        $this->db->select('*');
        $this->db->from('tr_status');
    return $this->db->get();
    }

	public function insert($istatus, $estatusname){
        $dentry = date("d F Y H:i:s");
        $data = array(
              'i_status'        => $istatus,
              'e_status_name'   => $estatusname,    
              'd_entry'         => $dentry,        
    );
    
    $this->db->insert('tr_status', $data);
    }

    public function update($istatus, $estatusname){
        $dupdate = date("d F Y H:i:s");
        $data = array(
            'i_status'        => $istatus,
            'e_status_name'   => $estatusname,    
            'd_update'        => $dupdate,  
    );

    $this->db->where('i_status', $istatus);
    $this->db->update('tr_status', $data);
    }

}

/* End of file Mmaster.php */