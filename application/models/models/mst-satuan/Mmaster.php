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
                                i_satuan_code, 
                                e_satuan_name, 
                                id_company,
                                case when 
                                    f_status = TRUE 
                                then 
                                    'Aktif' 
                                else 
                                    'Tidak Aktif' 
                                end as status, 
                                '$i_menu' as i_menu, 
                                '$folder' as folder 
                            FROM 
                                tr_satuan
                            WHERE
                                id_company = '$idcompany'
                            ORDER BY
                                i_satuan_code
                            ", FALSE);

        $datatables->edit(
        'status', 
                function ($data) {
                    $id         = trim($data['i_satuan_code']);
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
            $isatuancode = trim($data['i_satuan_code']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$isatuancode/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isatuancode/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');

        return $datatables->generate();
	}

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_satuan');
        $this->db->where('i_satuan_code', $id);
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
        $this->db->where('i_satuan_code', $id);
        $this->db->update('tr_satuan', $data);
    }

    public function cekkode($kode){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT i_satuan_code FROM tr_satuan WHERE i_satuan_code ='$kode' and id_company = '$idcompany'", FALSE);
    }
    
	function cek_data($id, $idcompany){
        $this->db->select('*');
        $this->db->from('tr_satuan');
        $this->db->where('i_satuan_code', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

	public function insert($isatuancode, $esatuan, $idcompany){
 
        $data = array(
              'i_satuan_code'   => $isatuancode,
              'e_satuan_name'   => $esatuan,  
              'id_company'      => $idcompany,           
              'd_entry'         => current_datetime(),          
    );
    
    $this->db->insert('tr_satuan', $data);
    }

    public function update($id, $isatuancode, $esatuan, $idcompany){
        
        $data = array(
            'i_satuan_code'   => $isatuancode,
            'e_satuan_name'   => $esatuan,         
            'd_update'        => current_datetime(),    

    );

    $this->db->where('id', $id);
    $this->db->where('id_company', $idcompany);
    $this->db->update('tr_satuan', $data);
    }
}
/* End of file Mmaster.php */