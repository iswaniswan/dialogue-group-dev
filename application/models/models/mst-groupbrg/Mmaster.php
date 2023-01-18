<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query("
                            SELECT
                               0 as no,
                               i_kode_group_barang,
                               e_nama_group_barang,
                               id_company,
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
                               '$folder' as folder 
                            FROM
                               tr_group_barang
                            WHERE 
                                id_company ='$idcompany'
                            ORDER BY
                                i_kode_group_barang
                            ", FALSE);
        
        $datatables->edit(
                'status', 
                function ($data) {
                    $id         = trim($data['i_kode_group_barang']);
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
            $igroupbrg = trim($data['i_kode_group_barang']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$igroupbrg/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$igroupbrg/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('id_company');

        return $datatables->generate();
	}

    public function status($id){
        $idcompany = $this->session->userdata('id_company');

        $this->db->select('f_status');
        $this->db->from('tr_group_barang');
        $this->db->where('i_kode_group_barang', $id);
        $this->db->where('id_company', $idcompany);
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
        $this->db->where('i_kode_group_barang', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_group_barang', $data);
    }

    public function cekkode($kode){
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT i_kode_group_barang FROM tr_group_barang WHERE i_kode_group_barang ='$kode' and id_company=$idcompany", FALSE);
    }

	function cek_data($id){
        $idcompany = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tr_group_barang');
        $this->db->where('i_kode_group_barang', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

	public function insert($igroupbrg, $egroupname){
        $dentry = date("Y-m-d H:i:s");
        $idcompany  = $this->session->userdata('id_company');
  
        $data = array(
              'i_kode_group_barang'    => $igroupbrg,
              'e_nama_group_barang'    => $egroupname,  
              'id_company'             => $idcompany,      
              'd_entry'                => $dentry,        
    );
    
    $this->db->insert('tr_group_barang', $data);
    }

    public function update($id, $igroupbrg, $egroupname){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_kode_group_barang'      => $igroupbrg,
            'e_nama_group_barang'      => $egroupname,        
            'd_update'                 => $dupdate, 
    );

    $this->db->where('id', $id);
    $this->db->update('tr_group_barang', $data);
    }

}

/* End of file Mmaster.php */
