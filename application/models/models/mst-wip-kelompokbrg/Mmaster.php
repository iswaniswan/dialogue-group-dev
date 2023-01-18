<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tm_kel_brg_wip.i_kelbrg_wip, tm_kel_brg_wip.e_nama_kel, tm_kel_brg_wip.e_keterangan, 
        tm_kel_brg_wip.f_status_aktif, tm_kel_brg_wip.d_update, $i_menu as i_menu FROM tm_kel_brg_wip");

        $datatables->edit('f_status_aktif', function ($data) {
            $f_status_aktif = trim($data['f_status_aktif']);
            if($f_status_aktif == 'f'){
               return  "Tidak Aktif";
            }else {
              return "Aktif";
            }
        });

		$datatables->add('action', function ($data) {
            $ikode= trim($data['i_kelbrg_wip']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-wip-kelompokbrg/cform/view/$ikode/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-wip-kelompokbrg/cform/edit/$ikode/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if(check_role($i_menu, 4)){
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ikode\"); return false;'><i class='fa fa-trash'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tm_kel_brg_wip');
    $this->db->where('i_kelbrg_wip', $id);

    return $this->db->get();
    }
    
    function get_wipbarang(){
        $this->db->select('*');
        $this->db->from('tm_kel_brg_wip');
    return $this->db->get();
    }

	public function insert($ikode, $enama, $eketerangan){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_kelbrg_wip'    => $ikode,
              'e_nama_kel'      => $enama, 
              'e_keterangan'    => $eketerangan,   
              'd_entry'         => $dentry,        
    );
    
    $this->db->insert('tm_kel_brg_wip', $data);
    }

    public function update($ikode, $enama, $eketerangan){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_kelbrg_wip'    => $ikode,
            'e_nama_kel'      => $enama, 
            'e_keterangan'    => $eketerangan,  
            'd_update'        => $dupdate,  

    );

    $this->db->where('i_kelbrg_wip', $ikode);
    $this->db->update('tm_kel_brg_wip', $data);
    }

    public function cancel($ikode){
        $data = array(
          'f_status_aktif'=>'f',
      );
        $this->db->where('i_kelbrg_wip', $ikode);
        $this->db->update('tm_kel_brg_wip', $data);
      }

}

/* End of file Mmaster.php */