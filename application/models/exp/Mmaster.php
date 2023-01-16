<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("Select i_ekspedisi, e_ekspedisi, e_ekspedisi_address, e_ekspedisi_city, e_ekspedisi_phone, '$i_menu' as i_menu from tr_ekspedisi");
		$datatables->add('action', function ($data) {
            $i_ekspedisi = trim($data['i_ekspedisi']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"exp/cform/view/$i_ekspedisi/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"exp/cform/edit/$i_ekspedisi/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
		$this->db->select('a.*, b.e_area_name');
        $this->db->from('tr_ekspedisi a');
        $this->db->join('tr_area b', 'a.i_area=b.i_area');
        $this->db->where('a.i_ekspedisi', $id);

        return $this->db->get();

	}

	public function insert($iekspedisi,$eekspedisi,$iarea,$eekspedisiaddress,$eekspedisicity,$eekspedisiphone,$eekspedisifax){
        $dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_ekspedisi'           => $iekspedisi,
            'e_ekspedisi'           => $eekspedisi,
            'i_area'                => $iarea,
            'e_ekspedisi_address'   => $eekspedisiaddress,
            'e_ekspedisi_city'      => $eekspedisicity,
            'e_ekspedisi_phone'     => $eekspedisiphone,
            'e_ekspedisi_fax'       => $eekspedisifax,
            'd_entry'               => $dentry
    );
    
    $this->db->insert('tr_ekspedisi', $data);
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function update($iekspedisi,$eekspedisi,$iarea,$eekspedisiaddress,$eekspedisicity,$eekspedisiphone,$eekspedisifax){
        $dupdate = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'e_ekspedisi'           => $eekspedisi,
            'i_area'                => $iarea,
            'e_ekspedisi_address'   => $eekspedisiaddress,
            'e_ekspedisi_city'      => $eekspedisicity,
            'e_ekspedisi_phone'     => $eekspedisiphone,
            'e_ekspedisi_fax'       => $eekspedisifax,
            'd_update'              => $dupdate
    );

    $this->db->where('i_ekspedisi', $iekspedisi);
    $this->db->update('tr_ekspedisi', $data);
    }



	
}

/* End of file Mmaster.php */
