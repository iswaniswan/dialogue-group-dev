<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($folder, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query("    SELECT
                                    0 as no,
                                    a.id,
                                    a.e_nama_karyawan,
                                    a.e_telpon,
                                    c.e_departement_name,
                                    a.id_company,
                                    b.name,      
                                    a.f_status,                              
                                    case when a.f_status = TRUE then 'Aktif' else 'Tidak Aktif' end as status,
                                    $i_menu as i_menu , 
                                    '$folder' as folder
                                FROM
                                    tr_karyawan a
                                JOIN
                                    public.company b
                                    ON a.id_company = b.id
                                JOIN 
				                    public.tr_departement c
				                    ON a.i_departement = c.i_departement
                                WHERE
                                    a.id_company = '$idcompany'
                                ORDER BY
                                    id_company");

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
            $id         = trim($data['id']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $f_status   = $data['f_status'];
            $data       = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_status != 'f' ){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('f_status');
        $datatables->hide('id_company');

        return $datatables->generate();
	}

    public function status($id){
            $this->db->select('f_status');
            $this->db->from('tr_karyawan');
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
            $this->db->update('tr_karyawan', $data);
    }
    
    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tr_karyawan');
        return $this->db->get()->row()->id+1;
    }

    public function cek_company($id_company){
        return $this->db->query("select * FROM public.company WHERE  id = '$id_company' ORDER BY name", false);

    }

	public function insert($id, $ektp, $enamakaryawan, $etelp, $ekota, $ealamat, $enik, $company, $departement, $ilevel){
        
        $data = array(
                        'id'                => $id,
                        'e_nik'             => $enik,
                        'e_nama_karyawan'   => $enamakaryawan,
                        'e_alamat'          => $ealamat,
                        'e_no_ktp'          => $ektp,
                        'e_telpon'          => $etelp,
                        'id_company'        => $company,
                        'i_departement'     => $departement,
                        'd_entry'           => current_datetime(),        
                        'e_kota'            => $ekota,
                        'i_level'           => $ilevel
        );
    $this->db->insert('tr_karyawan', $data);
    }

    public function cek_data($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
                                SELECT 
                                    a.*,
                                    b.e_level_name
                                FROM
                                    tr_karyawan a
                                    LEFT JOIN 
                                        public.tr_level b
                                        ON (a.i_level = b.i_level)
                                WHERE
                                    a.id='$id'
                                    AND a.id_company = '$idcompany'
                                ", FALSE);
        return $this->db->get();
    }


    public function update($id, $ektp, $enamakaryawan, $etelp, $ekota, $ealamat, $enik, $company, $departement, $ilevel){
        $data = array(
                        'e_nik'             => $enik,
                        'e_nama_karyawan'   => $enamakaryawan,
                        'e_alamat'          => $ealamat,
                        'e_no_ktp'          => $ektp,
                        'e_telpon'          => $etelp,
                        'id_company'        => $company,
                        'i_departement'     => $departement,
                        'd_update'          => current_datetime(),
                        'e_kota'            => $ekota,
                        'i_level'           => $ilevel
    );

    $this->db->where('id', $id);
    $this->db->update('tr_karyawan', $data);
    }
}
/* End of file Mmaster.php */