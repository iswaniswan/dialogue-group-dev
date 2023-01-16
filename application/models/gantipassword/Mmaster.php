<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("Select id_company, username, e_password, e_name, '$i_menu' as i_menu from public.tm_user");
		$datatables->add('action', function ($data) {
            $id_company    = trim($data['id_company']);
            $username      = trim($data['username']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            /*if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"gantipassword/cform/view/$id_company/$username\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }*/
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"gantipassword/cform/edit/$id_company/$username\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id,$username){
		$this->db->select('*');
        $this->db->from('public.tm_user');
        $this->db->where('id_company', $id);
        $this->db->where('username', $username);
        return $this->db->get();
	}

    public function update($idcompany,$username,$epassword,$ename){
        $data = array(
            'e_name'       => $ename,
            'e_password'   => $epassword,
            'modifiedat'   => current_datetime()
        );

    $this->db->where('id_company', $idcompany);
    $this->db->where('username', $username);
    $this->db->update('public.tm_user', $data);
    }
}

/* End of file Mmaster.php */
