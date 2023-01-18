<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("Select b.i_customer, b.e_customer_name, c.e_area_name, d.e_area_rayon_name, '$i_menu' as i_menu from tr_customer_rayon a
            join tr_customer b on a.i_customer = b.i_customer join tr_area c on b.i_area = c.i_area join tr_rayon d on a.i_area_rayon = d.i_area_rayon");
		$datatables->add('action', function ($data) {
            $i_area_rayon = trim($data['i_area_rayon']);
            $i_customer = trim($data['i_customer']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"arearayon/cform/view/$i_area_rayon/$i_customer/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"arearayon/cform/edit/$i_area_rayon/$i_customer/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($iarearayon,$icustomer){
        $this->db->select('b.i_customer, b.e_customer_name, c.e_area_name, d.e_area_rayon_name');
        $this->db->join('tr_customer b','b.i_customer = a.i_customer');
        $this->db->join('tr_area c','b.i_area = c.i_area');
        $this->db->join('tr_rayon d', 'a.i_area_rayon = d.i_area_rayon');
        $this->db->where('a.i_area_rayon',$iarearayon);
        $this->db->where('a.i_customer',$icustomer);
        return $this->db->get('tr_customer_rayon a')->get();
	}

	public function insert($iarearayon,$icustomer){
        $dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
        $data = array(
            'i_area_rayon' => $iarearayon,
            'i_customer' => $icustomer,
            'd_area_rayon_entry' => $dentry,
        );
        
        $this->db->insert('tr_customer_rayon', $data);
    }

    public function bacacustomer(){
        return $this->db->order_by('i_customer', 'ASC')->get('tr_customer')->result();
    }

    public function bacarayon(){
        return $this->db->order_by('i_area_rayon','ASC')->get('tr_rayon')->result();
    }



	
}

/* End of file Mmaster.php */
