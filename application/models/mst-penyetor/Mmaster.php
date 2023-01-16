<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_penyetor.i_penyetor, tr_penyetor.e_penyetor, $i_menu as i_menu FROM tr_penyetor");

		$datatables->add('action', function ($data) {
            $ipenyetor = trim($data['i_penyetor']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-penyetor/cform/view/$ipenyetor/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-penyetor/cform/edit/$ipenyetor/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_penyetor');
    $this->db->where('i_penyetor', $id);

    return $this->db->get();
    }
    
    function get_penyetor(){
        $this->db->select('*');
        $this->db->from('tr_penyetor');
    return $this->db->get();
    }

	public function insert($ipenyetor, $epenyetor){  
        $dentry = date("Y-m-d H:i:s");     
        $kode = $this->db->query("SELECT i_penyetor FROM tr_penyetor ORDER BY i_penyetor DESC LIMIT 1");
        if ($kode->num_rows() > 0) {
            $row_kode = $kode->row();
            $ipenyetor= $row_kode->i_penyetor+1;
        }
        else
            $ipenyetor = 1; 
        
        $data = array(
              'i_penyetor'   => $ipenyetor,
              'e_penyetor'   => $epenyetor,
              'd_entry'      => $dentry,                            
    );
    
    $this->db->insert('tr_penyetor', $data);
    }

    public function update($ipenyetor, $epenyetor){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_penyetor'      => $ipenyetor,
            'e_penyetor'      => $epenyetor, 
            'd_update'       => $dupdate,     
    );

    $this->db->where('i_penyetor', $ipenyetor);
    $this->db->update('tr_penyetor', $data);
    }

}

/* End of file Mmaster.php */