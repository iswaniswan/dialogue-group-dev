<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu,$folder){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("

                            SELECT
                               0 as no,
                               id,
                               i_jenis_bayar,
                               e_jenis_bayar_name,
                               case
                                  when
                                     f_status = TRUE 
                                  then
                                     'Aktif' 
                                  else
                                     'Tidak Aktif' 
                               end
                               as status, 
                               $i_menu as i_menu, 
                               '$folder' AS folder 
                            FROM
                               tr_jenis_bayar 
                            WHERE
                               i_jenis_bayar < '05'", false);

         $datatables->edit(
        'status', 
                function ($data) {
                    $id         = trim($data['id']);
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
            $id = trim($data['id']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');

        return $datatables->generate();
    }

    public function status($id){
        $this->db->select('status');
        $this->db->from('tm_jenis_bayar');
        $this->db->where('i_jenis_bayar', $id);
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
        $this->db->where('i_jenis_bayar', $id);
        $this->db->update('tm_jenis_bayar', $data);
    }

    public function cek_data($id){
        $this->db->select('*');
        $this->db->from('tr_jenis_bayar');
        $this->db->where('id', $id);
        return $this->db->get();
    }
}

/* End of file Mmaster.php */