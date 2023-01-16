<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu)
    {
		$datatables = new Datatables(new CodeigniterAdapter);
        //$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("Select i_bbk_type, e_bbk_typename, '$i_menu' as i_menu from tr_bbk_type");
		$datatables->add('action', function ($data) {
            $i_bbk_type = trim($data['i_bbk_type']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"bbktype/cform/view/$i_bbk_type/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bbktype/cform/edit/$i_bbk_type/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->hide('i_menu');

        return $datatables->generate();
	}

    function cek_data($id)
    {
		$this->db->select('*');
        $this->db->from('tr_bbk_type');
        $this->db->where('i_bbk_type', $id);
        return $this->db->get();
	}

    public function insert($ibbktype,$ebbktypename)
    {
        $dbbktypeentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_bbk_type' => $ibbktype,
            'e_bbk_typename' => $ebbktypename,
            'd_bbk_typeentry' => $dbbktypeentry,
        ); 
        $this->db->insert('tr_bbk_type', $data);
    }

    public function update($ibbktype,$ebbktypename)
    {
        $dbbktypeupdate = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'e_bbk_typename'    => $ebbktypename,
            'd_bbk_typeupdate'  => $dbbktypeupdate
    );
        $this->db->where('i_bbk_type', $ibbktype);
        $this->db->update('tr_bbk_type', $data);
    }
}

/* End of file Mmaster.php */
