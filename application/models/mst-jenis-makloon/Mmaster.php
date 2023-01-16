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
                               id,
                               i_type_makloon,
                               e_type_makloon_name,
                               id_company,
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
                               tr_type_makloon a 
                            WHERE
                               id_company = '$idcompany'
                            ORDER BY e_type_makloon_name", FALSE);

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
            $folder     = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            // if(check_role($i_menu, 4)){
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$itype\"); return false;'><i class='ti-close'></i></a>";
            // }
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
            $this->db->from('tr_type_makloon');
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
            $this->db->update('tr_type_makloon', $data);
    }

    function cek_data($id, $idcompany){
        $this->db->select('*');
        $this->db->from('tr_type_makloon');
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    function cek_data_edit($oldid, $id){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('*');
        $this->db->from('tr_type_makloon');
        $this->db->where('i_type_makloon <>', $oldid);
        $this->db->where('i_type_makloon', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }
    public function insert($itype, $etype){

        $idcompany  = $this->session->userdata('id_company');
 
        $data = array(
              'i_type_makloon'          => $itype,
              'e_type_makloon_name'     => $etype, 
              'id_company'              => $idcompany,      
              'd_entry'                 => current_datetime(),     
    );
    
    $this->db->insert('tr_type_makloon', $data);
    }

    public function update($id, $olditype,$itype, $etype, $idcompany){
        
        $data = array(
             'i_type_makloon'           => $itype,
             'e_type_makloon_name'      => $etype,           
             'd_update'                 => current_datetime(),   
    );

    $this->db->where('id', $id);
    $this->db->where('i_type_makloon', $olditype);
    $this->db->where('id_company', $idcompany);
    $this->db->update('tr_type_makloon', $data);
    }
}
/* End of file Mmaster.php */