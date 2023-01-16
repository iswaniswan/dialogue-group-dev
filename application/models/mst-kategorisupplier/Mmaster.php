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
                               a.id,
                               a.i_supplier_group,
                               a.e_supplier_group_name,
                               a.id_company,
                               case
                                  when
                                     a.f_status = TRUE 
                                  then
                                     'Aktif' 
                                  else
                                     'Tidak Aktif' 
                               end
                               as status, $i_menu as i_menu , '$folder' as folder 
                            FROM
                               tr_supplier_group a
                            WHERE
                                a.id_company = '$idcompany'
                            ORDER BY
                               a.e_supplier_group_name", false);

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
                    if(check_role($id_menu, 3) && $id != 'KTG02'){
                        $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }else{
                        $data   .= "<span class=\"label label-$warna\">$status</span>";
                    }
                    return $data;
                }
    );

		$datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $isuppliergroup = trim($data['i_supplier_group']);
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $data           = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if ($isuppliergroup != 'KTG02') {
                if(check_role($i_menu, 3)){
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
                }
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
            $this->db->from('tr_supplier_group');
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
            $this->db->update('tr_supplier_group', $data);
  }

	function cek_data($id){
      $idcompany  = $this->session->userdata('id_company');

      $this->db->select('*');
      $this->db->from('tr_supplier_group');
      $this->db->where('i_supplier_group', $id);
      $this->db->where('id_company', $idcompany);
      return $this->db->get();
  }

  function cek($id){
      $idcompany  = $this->session->userdata('id_company');

      $this->db->select('*');
      $this->db->from('tr_supplier_group');
      $this->db->where('id', $id);
      $this->db->where('id_company', $idcompany);
      return $this->db->get();
  }

  function cek_data_edit($id, $oldisuppliergroup, $isuppliergroup, $idcompany){
      $idcompany  = $this->session->userdata('id_company');

      $this->db->select('*');
      $this->db->from('tr_supplier_group');
      $this->db->where('i_supplier_group <>', $oldisuppliergroup);
      $this->db->where('i_supplier_group', $isuppliergroup);
      $this->db->where('id_company', $idcompany);
      $this->db->where('id', $id);
      return $this->db->get();
  }

  function runningid(){
      $this->db->select('max(id) AS id');
      $this->db->from('tr_supplier_group');
      return $this->db->get()->row()->id+1;
  }

	public function insert($id, $isuppliergroup, $isuppliergroupname){
      $idcompany  = $this->session->userdata('id_company');

      $data = array(
            'id'                      => $id, 
            'i_supplier_group'        => $isuppliergroup,
            'e_supplier_group_name'   => $isuppliergroupname, 
            'id_company'              => $idcompany, 
            'd_entry'                 => current_datetime(),                 
    );   
    $this->db->insert('tr_supplier_group', $data);
    }

    public function update($id, $oldisuppliergroup, $isuppliergroup, $isuppliergroupname, $idcompany){
       
        $data = array(
              'i_supplier_group'        => $isuppliergroup,
              'e_supplier_group_name'   => $isuppliergroupname,  
              'd_update'                => current_datetime(),        
    );

    $this->db->where('id', $id);
    $this->db->where('id_company', $idcompany);
    $this->db->update('tr_supplier_group', $data);
    }

    public function cancel($i_supplier_group){
        $data = array(
          'f_status'=>'f',
      );
        $this->db->where('i_supplier_group', $i_supplier_group);
        $this->db->update('tr_supplier_group', $data);
      }
}
/* End of file Mmaster.php */