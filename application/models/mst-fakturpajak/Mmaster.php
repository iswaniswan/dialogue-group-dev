<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_format_pajak.id, tr_format_pajak.segmen1, tr_format_pajak.nourut_awal, tr_format_pajak.nourut_akhir, tr_format_pajak.d_update, $i_menu as i_menu FROM tr_format_pajak");
        
		$datatables->add('action', function ($data) {
            $id = trim($data['id']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-fakturpajak/cform/view/$id/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-fakturpajak/cform/edit/$id/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_format_pajak');
    $this->db->where('id', $id);

    return $this->db->get();
    }
    
    function get_formatpajak(){
        $this->db->select('*');
        $this->db->from('tr_format_pajak');
    return $this->db->get();
    }

	public function insert($id, $segmen1, $nourutawal, $nourutakhir){
        $dentry = date("Y-m-d H:i:s");
        $qid = $this->db->query("SELECT id FROM tr_format_pajak ORDER BY id DESC LIMIT 1");
        if ($qid->num_rows() > 0) {
            $row_id = $qid->row();
            $id= $row_id->id+1;
        }
        else
            $id = 1;

        $data = array(
              'id'            => $id,
              'segmen1'       => $segmen1,
              'nourut_awal'   => $nourutawal,
              'nourut_akhir'  => $nourutakhir,             
              'd_entry'       => $dentry,        
    );
    
    $this->db->insert('tr_format_pajak', $data);
    }

    public function update($id, $segmen1, $nourutawal, $nourutakhir){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'id'            => $id,
            'segmen1'       => $segmen1,
            'nourut_awal'   => $nourutawal,
            'nourut_akhir'  => $nourutakhir,            
            'd_update'      => $dupdate, 

    );

    $this->db->where('id', $id);
    $this->db->update('tr_format_pajak', $data);
    }

}

/* End of file Mmaster.php */