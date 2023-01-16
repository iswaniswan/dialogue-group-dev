<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
    $idcompany  = $this->session->userdata('id_company');

		$datatables->query("SELECT
                                   0 as no,
                                   a.id,
                                   a.i_level,
                                   a.e_level_name,
                                   a.id_company,
                                   case
                                      when
                                         f_status = TRUE 
                                      then
                                         'Aktif' 
                                      else
                                         'Tidak Aktif' 
                                   end
                                   as status, $i_menu as i_menu, '$folder' as folder 
                                FROM
                                   tr_level_perusahaan a 
                                WHERE
                                    a.id_company = '$idcompany'
                                ORDER BY a.e_level_name", FALSE);
        
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
                    if(check_role($id_menu, 3) && ($id != 'PLV00' && $id != 'PLV01') ){
                        $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }else{
                        $data   .= "<span class=\"label label-$warna\">$status</span>";
                    }
                    return $data;
                }
        );

		$datatables->add('action', function ($data) {
            $id     = trim($data['id']);
            $ilevel = trim($data['i_level']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && ($ilevel != 'PLV00' && $ilevel != 'PLV01')){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('id_company');

        return $datatables->generate();
	}

    public function status($id){
            $this->db->select('f_status');
            $this->db->from('tr_level_perusahaan');
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
            $this->db->update('tr_level_perusahaan', $data);
    }

	function cek_data($id){
    $idcompany  = $this->session->userdata('id_company');

    $this->db->select('*');
    $this->db->from('tr_level_perusahaan');
    $this->db->where('id', $id);
    $this->db->where('id_company', $idcompany);
    return $this->db->get();
  }

  function cek_data_edit($oldid, $id){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('*');
        $this->db->from('tr_level_perusahaan');
        $this->db->where('i_level <>', $oldid);
        $this->db->where('i_level', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
  }

	public function insert($ilevel, $elevel){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
              'i_level'         => $ilevel,
              'e_level_name'    => $elevel,  
              'id_company'      => $idcompany,     
              'd_entry'         => current_datetime(),        
    );
    
    $this->db->insert('tr_level_perusahaan', $data);
    }

    public function update($id, $oldilevel, $ilevel, $elevel){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
             'i_level'        => $ilevel,
             'e_level_name'   => $elevel,           
             'd_update'       => current_datetime(), 
    );

    $this->db->where('id', $id);
    $this->db->where('id_company', $idcompany);
    $this->db->update('tr_level_perusahaan', $data);
    }

}

/* End of file Mmaster.php */