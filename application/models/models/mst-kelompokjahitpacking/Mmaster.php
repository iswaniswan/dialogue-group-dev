<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tm_link_unit.i_jahitpacking, tm_link_unit.e_nama_jahitpacking, tm_link_unit.e_unitjahit_name, tm_link_unit.e_nama_packing, tm_link_unit.d_update, $i_menu as i_menu FROM tm_link_unit");

		$datatables->add('action', function ($data) {
            $ijahitpacking = trim($data['i_jahitpacking']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kelompokjahitpacking/cform/view/$ijahitpacking/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kelompokjahitpacking/cform/edit/$ijahitpacking/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tm_link_unit');
    $this->db->join('tr_unit_jahit', 'tm_link_unit.i_unit_jahit = tr_unit_jahit.i_unit_jahit');
    $this->db->join('tr_unit_packing', 'tm_link_unit.i_unit_packing= tr_unit_packing.i_unit_packing');
    $this->db->where('tm_link_unit.i_jahitpacking', $id);

    return $this->db->get();
    }
    
    function get_kelompokjahitpacking(){
        $this->db->select('*');
        $this->db->from('tm_link_unit');
    return $this->db->get();
    }

    function get_unitjahit(){
        $this->db->select('*');
        $this->db->from('tr_unit_jahit');
    return $this->db->get();
    }

    function get_unitpacking(){
        $this->db->select('*');
        $this->db->from('tr_unit_packing');
    return $this->db->get();
    }

	public function insert($ijahitpacking, $iunitjahit, $iunitpacking, $enamajahitpacking, $eunitjahitname, $enamapacking){
        $dentry = date("Y-m-d H:i:s");
        $kode = $this->db->query("SELECT i_jahitpacking FROM tm_link_unit ORDER BY i_jahitpacking DESC LIMIT 1");
        if ($kode->num_rows() > 0) {
            $row_kode = $kode->row();
            $ijahitpacking= $row_kode->i_jahitpacking+1;
        }
        else
            $ijahitpacking = 1; 
          
        $data = array(
              'i_jahitpacking'      => $ijahitpacking,
              'i_unit_jahit'        => $iunitjahit,
              'i_unit_packing'      => $iunitpacking, 
              'e_nama_jahitpacking' => $enamajahitpacking, 
              'e_unitjahit_name'    => $eunitjahitname,
              'e_nama_packing'      => $enamapacking,  
              'd_entry'             => $dentry,        
    );
    
    $this->db->insert('tm_link_unit', $data);
    }

    public function update($ijahitpacking, $iunitjahit, $iunitpacking, $enamajahitpacking, $eunitjahitname, $enamapacking){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
              'i_jahitpacking'      => $ijahitpacking,
              'i_unit_jahit'        => $iunitjahit,
              'i_unit_packing'      => $iunitpacking, 
              'e_nama_jahitpacking' => $enamajahitpacking, 
              'e_unitjahit_name'    => $eunitjahitname,
              'e_nama_packing'      => $enamapacking,     
              'd_update'            => $dupdate, 

    );

    $this->db->where('i_jahitpacking', $ijahitpacking);
    $this->db->update('tm_link_unit', $data);
    }

}

/* End of file Mmaster.php */