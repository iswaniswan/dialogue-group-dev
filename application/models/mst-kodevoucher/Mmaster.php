<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_kode_voucher.i_voucher, tr_kode_voucher.i_voucher_code, tr_kode_voucher.e_voucher_name, tr_kode_voucher.e_description, $i_menu as i_menu FROM tr_kode_voucher");

		$datatables->add('action', function ($data) {
            $ivoucher = trim($data['i_voucher_code']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kodevoucher/cform/view/$ivoucher/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-kodevoucher/cform/edit/$ivoucher/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_kode_voucher');
    $this->db->join('tr_jenis_voucher', 'tr_kode_voucher.i_jenis = tr_jenis_voucher.i_jenis');
    $this->db->where('i_voucher', $id);

    return $this->db->get();
    }
    
    function get_jenisvoucher(){
        $this->db->select('*');
        $this->db->from('tr_jenis_voucher');
    return $this->db->get();
    }

    function get_kodevoucher(){
        $this->db->select('*');
        $this->db->from('tr_kode_voucher');
    return $this->db->get();
    }

	public function insert($ivoucher, $ikodevoucher, $ijenis, $evouchername, $edescription){
        $dentry = date("Y-m-d H:i:s");
        $kode = $this->db->query("SELECT i_voucher FROM tr_kode_voucher ORDER BY i_voucher DESC LIMIT 1");
        if ($kode->num_rows() > 0) {
            $row_kode = $kode->row();
            $ivoucher= $row_kode->i_voucher+1;
        }
        else
            $ivoucher = 1;

        $kode2 = $this->db->query("SELECT i_voucher_code FROM tr_kode_voucher ORDER BY i_voucher_code DESC LIMIT 1");
        if ($kode2->num_rows() > 0) {
            $row_kode = $kode2->row();
            $ikodevoucher= $row_kode->i_voucher_code+1;
        }
        else
            $ikodevoucher = 1;
          
        $data = array(
              'i_voucher'         => $ivoucher,
              'i_voucher_code'    => $ikodevoucher,
              'i_jenis'           => $ijenis,
              'e_voucher_name'    => $evouchername, 
              'e_description'     => $edescription,
              'd_entry'           => $dentry,                
              
    );
    
    $this->db->insert('tr_kode_voucher', $data);
    }

    public function update($ivoucher, $ikodevoucher, $ijenis, $evouchername, $edescription){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
              'i_voucher'         => $ivoucher,
              'i_voucher_code'    => $ikodevoucher,
              'i_jenis'           => $ijenis,
              'e_voucher_name'    => $evouchername, 
              'e_description'     => $edescription,
              'd_update'          => $dupdate,               
    );

    $this->db->where('i_voucher', $ivoucher);
    $this->db->update('tr_kode_voucher', $data);
    }

}

/* End of file Mmaster.php */