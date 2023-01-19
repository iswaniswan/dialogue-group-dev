<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT i_customer_grade, e_customer_gradename, $i_menu as i_menu FROM tr_customer_grade");

		$datatables->add('action', function ($data) {
            $i_customer_grade = trim($data['i_customer_grade']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"customergrade/cform/view/$i_customer_grade/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"customergrade/cform/edit/$i_customer_grade/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_customer_grade');
    $this->db->where('i_customer_grade', $id);
    return $this->db->get();

	}


	public function insert($icustomergrade, $icustomergradename){
        $data = array(
              'i_customer_grade'     			=> $icustomergrade,
              'e_customer_gradename'       	    => $icustomergradename
    );
    $this->db->insert('tr_customer_grade', $data);
    }

    public function update($icustomergrade, $icustomergradename){

        $data = array(
             'e_customer_gradename'       	=> $icustomergradename
    );

    $this->db->where('i_customer_grade', $icustomergrade);
    $this->db->update('tr_customer_grade', $data);
    }

}

/* End of file Mmaster.php */
