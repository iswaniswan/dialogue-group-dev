<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("Select i_store, e_store_name, d_store_register, '$i_menu' as i_menu from tr_store");
		$datatables->add('action', function ($data) {
            $i_store    = trim($data['i_store']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"store/cform/view/$i_store/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"store/cform/edit/$i_store/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('d_store_register', function ($data) {
        $d_store_register = $data['d_store_register'];
        if($d_store_register == ''){
            return '';
        }else{
            return date("d-m-Y", strtotime($d_store_register) );
        }
        });

        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('*');
        $this->db->from('tr_store');
        $this->db->where('i_store', $id);
        return $this->db->get();
	}

	public function insert($istore,$estorename,$estoreshortname,$dstoreregister){
        $dstoreentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_store'           => $istore,
            'e_store_name'      => $estorename,
            'e_store_shortname' => $estoreshortname,
            'd_store_register'  => $dstoreregister,
            'd_store_entry'     => $dstoreentry
        );
    
    $this->db->insert('tr_store', $data);
    }

    public function update($istore,$estorename,$estoreshortname,$dstoreregister){
        $dstoreupdate = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'e_store_name'        => $estorename,
            'e_store_shortname'   => $estoreshortname,
            'd_store_register'    => $dstoreregister,
            'd_store_update'      => $dstoreupdate
        );

    $this->db->where('i_store', $istore);
    $this->db->update('tr_store', $data);
    }
}

/* End of file Mmaster.php */
