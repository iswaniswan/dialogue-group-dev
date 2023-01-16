<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("
                                SELECT
                                   ROW_NUMBER () OVER (
                                ORDER BY
                                   i_kode_jenis) as no,
                                   tr_jenis_gudang.i_kode_jenis,
                                   tr_jenis_gudang.e_nama_jenis,
                                   to_char(tr_jenis_gudang.d_update, 'dd-mm-yyyy') as d_update,
                                   case
                                      when
                                         status = TRUE 
                                      then
                                         'Aktif' 
                                      else
                                         'Tidak Aktif' 
                                   end
                                   as status, $i_menu as i_menu, '$folder' as folder 
                                FROM
                                   tr_jenis_gudang", FALSE);

        $datatables->edit(
        'status', 
                function ($data) {
                    $id         = trim($data['i_kode_jenis']);
                    $folder     = $data['folder'];
                    $id_menu    = $data['i_menu'];
                    $status     = $data['status'];
                    if ($status=='Aktif') {
                        $warna = 'success';
                    }else{
                        $warna = 'danger';
                    }
                    $data    = '';
                    if(check_role($id_menu, 3)){
                        $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }else{
                        $data   .= "<span class=\"label label-$warna\">$status</span>";
                    }
                    return $data;
                }
        );

		$datatables->add('action', function ($data) {
            $ikodejenis = trim($data['i_kode_jenis']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jenisgudang/cform/view/$ikodejenis/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-jenisgudang/cform/edit/$ikodejenis/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');

        return $datatables->generate();
	}

    public function status($id){
            $this->db->select('status');
            $this->db->from('tr_jenis_gudang');
            $this->db->where('i_kode_jenis', $id);
            $query = $this->db->get();
            if ($query->num_rows()>0) {
                $row    = $query->row();
                $status = $row->status;
                if ($status=='t') {
                    $stat = 'f';
                }else{
                    $stat = 't';
                }
            }
            $data = array(
                'status' => $stat 
            );
            $this->db->where('i_kode_jenis', $id);
            $this->db->update('tr_jenis_gudang', $data);
    }

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_jenis_gudang');
    $this->db->where('i_kode_jenis', $id);

    return $this->db->get();
    }
    
    function get_jenis_gudang(){
        $this->db->select('*');
        $this->db->from('tr_jenis_gudang');
    return $this->db->get();
    }

	public function insert($ikodejenis, $enamajenis, $igroup){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
                      'i_kode_jenis'    => $ikodejenis,
                      'e_nama_jenis '   => $enamajenis,   
                      'i_group_barang'  => $igroup, 
                      'd_input'         => $dentry
    );
    
    $this->db->insert('tr_jenis_gudang', $data);
    }

    public function cek_group(){
        $this->db->select('*');
        $this->db->from('tm_group_barang');
        return $this->db->get();
    }

    public function update($ikodejenis, $enamajenis, $igroup){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_kode_jenis'    => $ikodejenis,
            'e_nama_jenis'    => $enamajenis,
            'i_group_barang'  => $igroup,    
            'd_update'        => $dupdate,  

    );

    $this->db->where('i_kode_jenis', $ikodejenis);
    $this->db->update('tr_jenis_gudang', $data);
    }

}

/* End of file Mmaster.php */