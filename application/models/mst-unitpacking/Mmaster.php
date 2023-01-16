<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_unit_packing.i_unit_packing, tr_unit_packing.e_nama_packing, 
        tr_unit_packing.e_lokasi_packing, tr_unit_packing.d_input,  tr_unit_packing.f_status_aktif,
        tr_unit_packing.d_update, $i_menu as i_menu FROM tr_unit_packing");

        $datatables->edit('f_status_aktif', function ($data) {
            $f_status_aktif = trim($data['f_status_aktif']);
            if($f_status_aktif == 'f'){
               return  "Tidak Aktif";
            }else {
              return "Aktif";
            }
        });

		$datatables->add('action', function ($data) {
            $iunitpacking = trim($data['i_unit_packing']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-unitpacking/cform/view/$iunitpacking/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-unitpacking/cform/edit/$iunitpacking/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if(check_role($i_menu, 4)){
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iunitpacking\"); return false;'><i class='fa fa-trash'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_unit_packing');
    $this->db->where('i_unit_packing', $id);

    return $this->db->get();
    }
    
    function get_unitpacking(){
        $this->db->select('*');
        $this->db->from('tr_unit_packing');
    return $this->db->get();
    }

	public function insert($iunitpacking, $eunitpackingname, $epackinglocation){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_unit_packing'          => $iunitpacking,
              'e_nama_packing'          => $eunitpackingname,  
              'e_lokasi_packing'        => $epackinglocation,              
              'd_input'                 => $dentry,        
    );
    
    $this->db->insert('tr_unit_packing', $data);
    }

    public function cancel($iunitpacking){
        $data = array(
          'f_status_aktif'=>'f',
      );
        $this->db->where('i_unit_packing', $iunitpacking);
        $this->db->update('tr_unit_packing', $data);
      }

    public function update($iunitpacking, $eunitpackingname, $epackinglocation){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
              'i_unit_packing'          => $iunitpacking,
              'e_nama_packing'          => $eunitpackingname,  
              'e_lokasi_packing'        => $epackinglocation, 
              'd_update'                => $dupdate,  

    );

    $this->db->where('i_unit_packing', $iunitpacking);
    $this->db->update('tr_unit_packing', $data);
    }

}

/* End of file Mmaster.php */