<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tm_kelompok_unit.i_kelompok_unit, tm_kelompok_unit.nama_kelompok, $i_menu as i_menu FROM tm_kelompok_unit");

		$datatables->add('action', function ($data) {
            $ikelompokunit = trim($data['i_kelompok_unit']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-pengelompokanunit/cform/view/$ikelompokunit/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-pengelompokanunit/cform/edit/$ikelompokunit/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tm_kelompok_unit');
    $this->db->join('tm_kelompok_unit_detail', 'tm_kelompok_unit.i_kelompok_unit = tm_kelompok_unit_detail.i_kelompok_unit');
    $this->db->join('tr_unit_jahit', 'tm_kelompok_unit_detail.i_unit_jahit =tr_unit_jahit.i_unit_jahit');
    $this->db->join('tr_unit_packing', 'tm_kelompok_unit_detail.i_unit_packing = tr_unit_packing.i_unit_packing');
    $this->db->where('tm_kelompok_unit.i_kelompok_unit', $id);

    return $this->db->get();
    }

    function cek_data2($ikelompokunit){
    $this->db->select('a.id, a.i_kelompok_unit, c.i_unit_packing, c.e_nama_packing, b.i_unit_jahit, b.e_unitjahit_name');
    $this->db->from('tm_kelompok_unit_detail a');   
    $this->db->join('tr_unit_jahit b', 'a.i_unit_jahit =b.i_unit_jahit');
    $this->db->join('tr_unit_packing c', 'a.i_unit_packing = c.i_unit_packing');
    $this->db->where('a.i_kelompok_unit', $ikelompokunit);
    return $this->db->get()->result();
    }
    
    /*function cek_data2($ikelompokunit){
        $this->db->select('*');
        $this->db->from('tm_kelompok_unit_detail ');  
        $this->db->join('tm_kelompok_unit', 'tm_kelompok_unit.i_kelompok_unit = tm_kelompok_unit_detail.i_kelompok_unit');     
        $this->db->join('tr_unit_jahit', 'tm_kelompok_unit_detail.i_unit_jahit =tr_unit_jahit.i_unit_jahit');
        $this->db->join('tr_unit_packing', 'tm_kelompok_unit_detail.i_unit_packing = tr_unit_packing.i_unit_packing');
        $this->db->where('tm_kelompok_unit_detail.i_kelompok_unit', $ikelompokunit);
        return $this->db->get();
    }*/

    function get_kelompokunit(){
        $this->db->select('*');
        $this->db->from('tm_kelompok_unit');
    return $this->db->get();
    }

    function get_unitdetail(){
        $this->db->select('*');
        $this->db->from('tm_kelompok_unit_detail');
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

    function pop_get_unit_jahit(){
        $this->db->select('*');
        $this->db->from('tr_unit_jahit');
        $this->db->where('f_kelompok');
        $this->db->order_by('i_unit_jahit','ASC');
      
      return $this->db->get();
      }

	public function insert($ikelompokunit, $namakelompok){  
        $dentry = date("Y-m-d H:i:s");
        /*$kelompok = $this->db->query("SELECT i_kelompok_unit FROM tm_kelompok_unit ORDER BY i_kelompok_unit DESC LIMIT 1");
        if ($kelompok->num_rows() > 0) {
            $row_kelompok = $kelompok->row();
            $ikelompokunit= $row_kelompok->i_kelompok_unit+1;
        }
        else
            $ikelompokunit = 1; */      
        $data = array(
              'i_kelompok_unit'   => $ikelompokunit,
              'nama_kelompok'     => $namakelompok,
              'd_entry'           => $dentry,
        );       
        $this->db->insert('tm_kelompok_unit', $data);
    }

    public function insert2($ikelompokunit, $iunitjahit, $iunitpacking){
        $unit = $this->db->query("SELECT id FROM tm_kelompok_unit_detail ORDER BY id DESC LIMIT 1");
        if ($unit->num_rows() > 0) {
            $row_unit = $unit->row();
            $id= $row_unit->id+1;
        }else        
            $id = 1;
            $data = array(
              'id'                => $id,
              'i_kelompok_unit'   => $ikelompokunit,
              'i_unit_jahit'      => $iunitjahit,
              'i_unit_packing'    => $iunitpacking,
        );       
        $this->db->insert('tm_kelompok_unit_detail', $data); 
    }

    function deletedetail($ikelompokunit, $iunitjahit, $iunitpacking){
        $this->db->query("DELETE FROM tm_kelompok_unit_detail WHERE i_kelompok_unit='$ikelompokunit' and i_unit_jahit='$iunitjahit' and i_unit_packing='$iunitpacking' ");
    }

    public function update($ikelompokunit, $namakelompok){
        $data = array(
            'i_kelompok_unit'   => $ikelompokunit,
            'nama_kelompok'     => $namakelompok,     
    );

    $this->db->where('i_kelompok_unit', $ikelompokunit);
    $this->db->update('tm_kelompok_unit', $data);
    }
}
/* End of file Mmaster.php */