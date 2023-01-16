<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select a.i_store_location, a.e_store_locationname, b.e_store_name, a.i_store, a.i_store_locationbin, '$i_menu' as i_menu from tr_store_location a, tr_store b
                            where a.i_store = b.i_store");                
		$datatables->add('action', function ($data) {
            $i_store_location       = trim($data['i_store_location']);
            $i_store                = trim($data['i_store']);
            $i_store_locationbin    = trim($data['i_store_locationbin']);
            $i_menu                 = $data['i_menu'];
            $data                   = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"storelocation/cform/view/$i_store_location/$i_store/$i_store_locationbin/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"storelocation/cform/edit/$i_store_location/$i_store/$i_store_locationbin/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_store');
        $datatables->hide('i_store_locationbin');

        return $datatables->generate();
	}

	function cek_data($istorelocation, $istore, $istorelocationbin){
		$this->db->select('tr_store_location.*, tr_store.e_store_name');
        $this->db->from('tr_store_location');
        $this->db->join('tr_store', 'tr_store_location.i_store=tr_store.i_store');
        $this->db->where('i_store_location', $istorelocation);
        $this->db->where('tr_store.i_store', $istore);
        $this->db->where('i_store_locationbin', $istorelocationbin);

        return $this->db->get();
    }

    public function bacagudang(){
        return $this->db->order_by('i_store','ASC')->get('tr_store')->result();
    }

	public function insert($istorelocation,$estorelocationname,$istore,$istorelocationbin){
        $data = array(
            'i_store_location'      => $istorelocation,
            'e_store_locationname'  => $estorelocationname,
            'i_store'               => $istore,
            'i_store_locationbin'   => $istorelocationbin
        );
        $this->db->insert('tr_store_location', $data);
    }

    public function update($istorelocation,$estorelocationname,$istore,$istorelocationbin){
        $data = array(
            'e_store_locationname'          => $estorelocationname,
            'i_store'                       => $istore,
            'i_store_locationbin'           => $istorelocationbin
        );
        $this->db->where('i_store_location', $istorelocation);
        $this->db->update('tr_store_location', $data);
    }	
}

/* End of file Mmaster.php */
