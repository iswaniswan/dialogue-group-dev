<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu,$idcompany,$folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        if ($this->session->userdata('i_departement')=='1') {
            $and = "";
        }else{
            $and = "AND username = '".$this->session->userdata('username')."' ";
        }
        $datatables->query("
            SELECT
                0 AS no,
                username,
                e_name,
                CASE
                    WHEN f_status = TRUE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                $i_menu AS i_menu ,
                '$folder' AS folder
            FROM
                public.tm_user
            WHERE
                id_company = '$idcompany'
                $and
        ",FALSE);
        
        $datatables->edit('status', 
            function ($data) {
                $id         = trim($data['username']);
                $folder     = $data['folder'];
                $id_menu    = $data['i_menu'];
                $status     = $data['status'];
                if ($status=='Aktif') {
                    $warna = 'success';
                }else{
                    $warna = 'danger';
                }
                $data    = '';
                if(check_role($id_menu, 3) && $this->session->userdata('i_departement')=='1'){
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

		$datatables->add('action', function ($data) {
            $iuser      = trim($data['username']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $data       = '';
            /*if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$iuser/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }*/
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iuser/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        return $datatables->generate();
	}

    public function cek_kode($kode){
        $this->db->select('*');
        $this->db->from('public.tm_user');
        $this->db->where('username', $kode);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        return $this->db->get();
    }

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('public.tm_user');
        $this->db->where('username', $id);
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
        $this->db->where('username', $id);
        $this->db->update('public.tm_user', $data);
    }

	public function cek_data($iuserold, $idcompany){
        $this->db->select('a.*, d.i_level, d.e_level_name');
        $this->db->from('public.tm_user as a');
        $this->db->join('public.tm_user_deprole as c', 'c.id_company = a.id_company and c.username=a.username', 'left');
        $this->db->join('public.tr_level as d', 'd.i_level = c.i_level', 'left');
        $this->db->where('a.username', $iuserold);
        $this->db->where('a.id_company', $idcompany);

        return $this->db->get();
    }

    public function bacadept(){
        $this->db->select(" * from public.tr_departement where f_status = 't' ",false);
        return $this->db->get();
    }

    public function bacalevel(){
        $this->db->select(" * from public.tr_level where f_status = 't' ",false);
        return $this->db->get();
    }
        
    public function get_user(){
        $this->db->select('*');
        $this->db->from('tm_user');
        return $this->db->get();
    }

	public function insert($idcompany, $iuser, $eusername, $password){
        $data = array(          
            'id_company' => $idcompany,  
            'username'   => $iuser,
            'e_password' => $password,
            'e_name'     => $eusername,
            'createdat'  => current_datetime(),
        );
        
        $this->db->insert('public.tm_user', $data);        
    }

    public function insertdeproll($idcompany, $departement, $Level, $iuser){
        $data = array(  
            'id_company'     => $idcompany,
            'username'       => $iuser,
            'i_departement'  => $departement,
            'i_level'        => $Level,
            'i_apps'         => '2'
        );
        
        $this->db->insert('public.tm_user_deprole', $data);        
    }

    public function update($iuser, $eusername, $idcompany, $iuserold){
        $data = array(
            'username'   => $iuser,
            'e_name'     => $eusername,
            'modifiedat' => current_datetime(),
        );

        $this->db->where('username', $iuserold);
        $this->db->where('id_company', $idcompany);
        $this->db->update('public.tm_user', $data);
    }

    public function updatepass($idcompany, $passwordbaru, $iuserold){
        $data = array(
              'e_password' => $passwordbaru,
        );
        $this->db->where('username', $iuserold);
        $this->db->where('id_company', $idcompany);
        $this->db->update('public.tm_user', $data);
    }
}
/* End of file Mmaster.php */