<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tm_bagian.i_kode, tm_bagian.e_nama, tm_bagian.d_update, $i_menu as i_menu FROM tm_bagian");
        
		$datatables->add('action', function ($data) {
            $ikode = trim($data['i_kode']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-departemen/cform/view/$ikode/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-departemen/cform/edit/$ikode/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($kode){
    $this->db->select('*');
    $this->db->from('tm_bagian');
    $this->db->join('tr_master_gudang', 'tm_bagian.i_kode_master = tr_master_gudang.i_kode_master');
    $this->db->where('tm_bagian.i_kode', $kode);

    return $this->db->get();
    }
    
    function get_departemen(){
        $this->db->select('*');
        $this->db->from('tm_bagian');
    return $this->db->get();
    }

    function get_gudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
    return $this->db->get();
    }

	public function insert($kode, $nama, $igudang){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_kode'         => $kode,
              'e_nama'         => $nama,              
              'i_kode_master'  => $igudang,             
              'd_entry'        => $dentry,        
    );
    
    $this->db->insert('tm_bagian', $data);
    }

    public function update($kode, $nama, $igudang){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_kode'          => $kode,
            'e_nama'          => $nama,            
            'i_kode_master'   => $igudang,                    
            'd_update'        => $dupdate, 

    );

    $this->db->where('i_kode', $kode);
    $this->db->update('tm_bagian', $data);
    }
}
/* End of file Mmaster.php */