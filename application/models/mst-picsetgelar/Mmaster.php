<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);         
		$datatables->query("SELECT 0 AS NO, id,
                e_pic_name,
                CASE
                    WHEN f_cutting = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS cutting,
                CASE
                    WHEN f_gelar = TRUE THEN 'Ya'
                    ELSE 'Tidak'
                END AS gelar,
                CASE
                    WHEN f_status = TRUE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                $i_menu AS i_menu,
                '$folder' AS folder
            FROM
                tr_pic
            WHERE
                id_company = '$this->id_company'
            ORDER BY
                id", FALSE);

        $datatables->edit('status', 
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
            $id         = trim($data['id']);
            $folder     = $data['folder'];
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='fa-lg ti-eye text-success mr-3'></i></a>";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='fa-lg ti-pencil-alt'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');

        return $datatables->generate();
    }

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_pic');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row    = $query->row();
            $status = $row->f_status;
            if ($status=='t') {
                $stat = 'f';
            }else{
                $stat = 't';
            }
        }
        $data = array(
            'f_status' => $stat 
        );
        $this->db->where('id', $id);
        $this->db->update('tr_pic', $data);
    }

    public function cek_data($id){
        $this->db->select('*');
        $this->db->from('tr_pic');
        $this->db->where('id', $id);
        return $this->db->get();
    }

    public function insert($e_pic_name, $f_cutting, $f_gelar){
        $data = array(
            'e_pic_name' => capitalize($e_pic_name),
            'f_cutting'  => $f_cutting,
            'f_gelar'    => $f_gelar,
            'id_company' => $this->id_company,
        );
        $this->db->insert('tr_pic', $data);
    }

    public function update($id, $e_pic_name, $f_cutting, $f_gelar){
        $data = array(
            'e_pic_name' => capitalize($e_pic_name),
            'f_cutting'  => $f_cutting,
            'f_gelar'    => $f_gelar,
        );
        $this->db->where('id', $id);
        $this->db->update('tr_pic', $data);
    }

}

/* End of file Mmaster.php */