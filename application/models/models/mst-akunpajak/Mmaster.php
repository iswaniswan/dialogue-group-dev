<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_akun_pajak.i_akun, tr_akun_pajak.i_akun_pajak, $i_menu as i_menu FROM tr_akun_pajak");

		$datatables->add('action', function ($data) {
            $iakun = trim($data['i_akun']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-akunpajak/cform/view/$iakun/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-akunpajak/cform/edit/$iakun/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_akun_pajak');
    $this->db->where('i_akun', $id);

    return $this->db->get();
    }
    
    function get_akunpajak(){
        $this->db->select('*');
        $this->db->from('tr_akun_pajak');
    return $this->db->get();
    }

	public function insert($iakun, $iakunpajak){  
        $dentry = date("Y-m-d H:i:s");    
        $kode = $this->db->query("SELECT i_akun FROM tr_akun_pajak ORDER BY i_akun DESC LIMIT 1");
        if ($kode->num_rows() > 0) {
            $row_kode = $kode->row();
            $iakun= $row_kode->i_akun+1;
        }
        else
            $iakun = 1; 

        $data = array(
              'i_akun'          => $iakun,
              'i_akun_pajak'   => $iakunpajak,
              'd_entry'        => $dentry,                            
    );
    
    $this->db->insert('tr_akun_pajak', $data);
    }

    public function update($iakun, $iakunpajak){
        $dupdate =date("Y-m-d H:i:s");
        $data = array(
            'i_akun'         => $iakun,
            'i_akun_pajak'   => $iakunpajak, 
            'd_update'       => $dupdate,     
    );

    $this->db->where('i_akun', $iakun);
    $this->db->update('tr_akun_pajak', $data);
    }

}

/* End of file Mmaster.php */