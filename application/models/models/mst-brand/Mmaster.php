<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder)
    {
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query("
                            SELECT 0 as no,
                                id,
                                i_brand, 
                                e_brand_name,
                                to_char(d_entry, 'YYYY Mon DD HH24:MI:SS') as tgl_input, 
                                id_company,
                                CASE 
                                    WHEN f_status = TRUE 
                                    THEN 'Aktif' 
                                    ELSE 'Tidak Aktif' 
                                END AS status, 
                                '$i_menu' AS i_menu, 
                                '$folder' AS folder 
                            FROM tr_brand
                            WHERE
                                id_company = '$idcompany'
                            ORDER BY 
                                id
                        ", FALSE);

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
            $id     = trim($data['id']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data   = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('id_company');

        return $datatables->generate();
    }

    public function status($id)
    {
        $this->db->select('f_status');
        $this->db->from('tr_brand');
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
        $this->db->update('tr_brand', $data);
    }

	public function cek_data($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tr_brand');
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    public function cek_kode($kode)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tr_brand');
        $this->db->where('i_brand', $kode);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

	public function insert($ibrand,$ebrandname)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'i_brand'        => $ibrand,
            'e_brand_name'   => $ebrandname,    
            'id_company'     => $idcompany, 
            'd_entry'        => current_datetime(),    
        );
        $this->db->insert('tr_brand', $data);
    }

    public function update($id,$ibrand,$ebrandname)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_brand'        => $ibrand,
            'e_brand_name'   => $ebrandname, 
            'd_update'       => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_brand', $data);
    }
}
/* End of file Mmaster.php */