<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_unit_jahit.i_unit_jahit, tr_unit_jahit.e_unitjahit_name, 
        tr_unit_jahit.e_perusahaan_name, tr_unit_jahit.e_unitjahit_address ,tr_unit_jahit.e_penanggung_jawab_name, 
        tr_unit_jahit.e_admin_name, tr_unit_jahit.f_status_aktif,tr_unit_jahit.d_update, $i_menu as i_menu FROM tr_unit_jahit");

        $datatables->edit('f_status_aktif', function ($data) {
            $f_status_aktif = trim($data['f_status_aktif']);
            if($f_status_aktif == 'f'){
               return  "Tidak Aktif";
            }else {
              return "Aktif";
            }
        });

		$datatables->add('action', function ($data) {
            $iunitjahit = trim($data['i_unit_jahit']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-unitjahit/cform/view/$iunitjahit/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-unitjahit/cform/edit/$iunitjahit/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if(check_role($i_menu, 4)){
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iunitjahit\"); return false;'><i class='fa fa-trash'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_unit_jahit');
    $this->db->where('i_unit_jahit', $id);

    return $this->db->get();
    }
    
    function get_unitjahit(){
        $this->db->select('*');
        $this->db->from('tr_unit_jahit');
    return $this->db->get();
    }

	public function insert($iunitjahit, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_unit_jahit'            => $iunitjahit,
              'e_unitjahit_name'        => $eunitjahitname,  
              'e_unitjahit_address'     => $eunitjahitaddress,
              'e_perusahaan_name'       => $eperusahaanname,
              'e_penanggung_jawab_name' => $epenanggungjawabname, 
              'e_admin_name'            => $eadminname, 
              'd_entry'                 => $dentry,        
    );
    
    $this->db->insert('tr_unit_jahit', $data);
    }

    public function cancel($iunitjahit){
        $data = array(
          'f_status_aktif'=>'f',
      );
        $this->db->where('i_unit_jahit', $iunitjahit);
        $this->db->update('tr_unit_jahit', $data);
      }

    public function update($iunitjahit, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_unit_jahit'              => $iunitjahit,
              'e_unitjahit_name'        => $eunitjahitname,  
              'e_unitjahit_address'     => $eunitjahitaddress,
              'e_perusahaan_name'       => $eperusahaanname,
              'e_penanggung_jawab_name' => $epenanggungjawabname, 
              'e_admin_name'            => $eadminname, 
              'd_update'                => $dupdate,  

    );

    $this->db->where('i_unit_jahit', $iunitjahit);
    $this->db->update('tr_unit_jahit', $data);
    }

}

/* End of file Mmaster.php */