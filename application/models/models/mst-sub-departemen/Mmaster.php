<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT a.i_sub_bagian, a.e_sub_bagian, a.i_kode, b.e_nama, a.d_update, $i_menu as i_menu 
            FROM tm_sub_bagian a
            JOIN tm_bagian b on a.i_kode=b.i_kode");
        
		$datatables->add('action', function ($data) {
            $isubbagian = trim($data['i_sub_bagian']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-sub-departemen/cform/view/$isubbagian/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-sub-departemen/cform/edit/$isubbagian/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_kode');

        return $datatables->generate();
	}

	function cek_data($isubbagian){
    $this->db->select('*');
    $this->db->from('tm_sub_bagian a');
    $this->db->join('tm_bagian b', 'a.i_kode = b.i_kode');
    $this->db->where('a.i_sub_bagian', $isubbagian);

    return $this->db->get();
    }
    
    function get_departemen(){
        $this->db->select('*');
        $this->db->from('tm_bagian');
    return $this->db->get();
    }

	public function insert($isubbagian, $enama, $idepartemen){
        $dentry = date("Y-m-d");
        $data = array(
              'i_sub_bagian'    => $isubbagian,
              'e_sub_bagian'    => $enama,              
              'i_kode'          => $idepartemen,             
              'd_entry'         => $dentry,        
    );
    
    $this->db->insert('tm_sub_bagian', $data);
    }

    public function update($isubbagian, $enama, $idepartemen){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_sub_bagian'      => $isubbagian,
            'e_sub_bagian'      => $enama,              
            'i_kode'            => $idepartemen,                    
            'd_update'          => $dupdate, 
    );

    $this->db->where('i_sub_bagian', $isubbagian);
    $this->db->update('tm_sub_bagian', $data);
    }
}
/* End of file Mmaster.php */