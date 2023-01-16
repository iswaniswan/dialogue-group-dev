<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_color.i_color, tr_color.i_kode_color, tr_color.e_color_name, tr_color.d_entry, tr_color.d_update, $i_menu as i_menu FROM tr_color");

		$datatables->add('action', function ($data) {
            $icolor= trim($data['i_color']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-warna/cform/view/$icolor/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-warna/cform/edit/$icolor/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_color');
    $this->db->where('i_color', $id);

    return $this->db->get();
    }
    
    function get_warna(){
        $this->db->select('*');
        $this->db->from('tr_color');
    return $this->db->get();
    }

	public function insert($icolorcode, $ecolorname){
        $dentry = date("d F Y H:i:s");
        $qcolor = $this->db->query("SELECT i_color FROM tr_color ORDER BY i_color DESC LIMIT 1");
        if ($qcolor->num_rows() > 0) {
            $row_color = $qcolor->row();
            $icolor= $row_color->i_color+1;
        }
        else
            $icolor = 1;

        $data = array(
              'i_color'        => $icolor,
              'i_kode_color'   => $icolorcode,
              'e_color_name'   => $ecolorname,    
              'd_entry'        => $dentry,               
    );
    
    $this->db->insert('tr_color', $data);
    }

    public function update($icolor, $icolorcode, $ecolorname){
        $dupdate = date("d F Y H:i:s");
        $data = array(
            'i_color'        => $icolor,
            'i_kode_color'   => $icolorcode,
            'e_color_name'   => $ecolorname,    
            'd_update'       => $dupdate,  
    );

    $this->db->where('i_color', $icolor);
    $this->db->update('tr_color', $data);
    }

}

/* End of file Mmaster.php */