<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_jns_setor_pajak.i_setoran, tr_jns_setor_pajak.i_akun_pajak, tr_jns_setor_pajak.i_jsetor_pajak, tr_jns_setor_pajak.e_jsetor_pajak, $i_menu as i_menu FROM tr_jns_setor_pajak");

		$datatables->add('action', function ($data) {
            $isetoran = trim($data['i_setoran']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jenissetorpajak/cform/view/$isetoran/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jenissetorpajak/cform/edit/$isetoran/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_jns_setor_pajak');
    $this->db->where('i_setoran', $id);

    return $this->db->get();
    }
    
    function get_akunpajak(){
        $this->db->select('*');
        $this->db->from('tr_jns_setor_pajak');
    return $this->db->get();
    }

    function get_setoranpajak(){
        $this->db->select('*');
        $this->db->from('tr_jns_setor_pajak');
    return $this->db->get();
    }

	public function insert($isetoran, $iakunpajak, $ijsetorpajak, $ejsetorpajak, $eketerangan){       
        $dentry = date("Y-m-d H:i:s");
        $kode = $this->db->query("SELECT i_setoran FROM tr_jns_setor_pajak ORDER BY i_setoran DESC LIMIT 1");
        if ($kode->num_rows() > 0) {
            $row_kode = $kode->row();
            $isetoran= $row_kode->i_setoran+1;
        }
        else
            $isetoran = 1; 
          
        $data = array(
              'i_setoran'        => $isetoran,
              'i_akun_pajak'     => $iakunpajak, 
              'i_jsetor_pajak'   => $ijsetorpajak,
              'e_jsetor_pajak'   => $ejsetorpajak,
              'keterangan'       => $eketerangan, 
              'd_entry'          => $dentry,               
    );  
    
    $this->db->insert('tr_jns_setor_pajak', $data);
    }

    public function update($isetoran, $iakunpajak, $ijsetorpajak, $ejsetorpajak, $eketerangan){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
              'i_setoran'        => $isetoran,
              'i_akun_pajak'     => $iakunpajak, 
              'i_jsetor_pajak'   => $ijsetorpajak,
              'e_jsetor_pajak'   => $ejsetorpajak,
              'keterangan'       => $eketerangan, 
              'd_update'         => $dupdate,       
    );

    $this->db->where('i_setoran', $isetoran);
    $this->db->update('tr_jns_setor_pajak', $data);
    }

}

/* End of file Mmaster.php */