<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $idcompany, $folder)
    {
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                a.username,
                b.name,
                c.e_departement_name,
                d.e_level_name,
                a.id_company,
                a.i_departement,
                a.i_level,
                CASE
                    WHEN a.f_status = TRUE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                '$i_menu' AS i_menu,
                '$folder' AS folder
            FROM
                tm_user_deprole a
            INNER JOIN company b ON
                a.id_company = b.id
            INNER JOIN tr_departement c ON
                a.i_departement = c.i_departement
            INNER JOIN tr_level d ON
                a.i_level = d.i_level
            WHERE
                a.id_company = '$idcompany'
                AND a.i_apps = '".$this->session->userdata('i_apps')."'
            ORDER BY a.username
        ", false);

        $datatables->edit('status', 
            function ($data) {
                $id         = trim($data['id_company']);
                $dept       = trim($data['i_departement']);
                $i_level    = trim($data['i_level']);
                $folder     = $data['folder'];
                $id_menu    = $data['i_menu'];
                $status     = $data['status'];
                if ($status=='Aktif') {
                    $warna = 'success';
                }else{
                    $warna = 'danger';
                }
                $combine = $id.'|'.$dept.'|'.$i_level;
                $data    = '';
                if(check_role($id_menu, 3)){
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$combine\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

        $datatables->add('action', function ($data) {
            $username   = trim($data['username']);
            $ilevel     = trim($data['i_level']);
            $idept      = trim($data['i_departement']);
            $icompany   = trim($data['id_company']);
            $folder     = $data['folder'];
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$username/$icompany/$idept/$ilevel\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 4)){
                $data .= "<a href=\"#\" title='Hapus' onclick='hapus(\"$username\",\"$ilevel\",\"$idept\",\"$icompany\"); return false;'><i class='ti-trash'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_level');
        $datatables->hide('i_departement');
        $datatables->hide('id_company');
        return $datatables->generate();
    }

    public function status($id_company,$i_departement,$i_level){
        $this->db->select('f_status');
        $this->db->from('public.tm_user_deprole');
        $this->db->where('id_company', $id_company);
        $this->db->where('i_departement', $i_departement);
        $this->db->where('i_level', $i_level);
        $this->db->where('i_apps', $this->session->userdata('i_apps')); 
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
        $this->db->where('id_company', $id_company);
        $this->db->where('i_departement', $i_departement);
        $this->db->where('i_level', $i_level);
        $this->db->where('i_apps', $this->session->userdata('i_apps'));
        $this->db->update('public.tm_user_deprole', $data);
    }

    public function get_user($cari)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT 
                * 
            FROM public.tm_user 
            WHERE f_status = 't' 
            AND username ILIKE '%$cari%'
            AND id_company = '".$this->session->userdata('id_company')."' 
            ORDER BY username", FALSE);
    }

    public function bacacompany(){
        $this->db->select("DISTINCT ON (name) id, name");
        $this->db->from("public.company");
        $this->db->where("f_status","t");
        $this->db->where("i_apps",$this->session->userdata('i_apps'));
        $this->db->order_by("name");
        return $this->db->get();
    }

    public function bacadepart(){
        $this->db->select("a.*")->distinct();
        $this->db->from("public.tr_departement a");
        $this->db->join("public.tm_user_role b","b.i_departement = a.i_departement","inner");
        $this->db->where("f_status","t");
        $this->db->where("i_apps",$this->session->userdata('i_apps'));
        $this->db->order_by("e_departement_name");
        return $this->db->get();
    }

    public function bacalevel(){
        $this->db->select("a.*")->distinct();
        $this->db->from("public.tr_level a");
        $this->db->join("public.tm_user_role b","b.i_level = a.i_level","inner");
        $this->db->where("f_status","t");
        $this->db->where("i_apps",$this->session->userdata('i_apps'));
        $this->db->order_by("e_level_name");
        return $this->db->get();
    }

    public function cek_data($ilevel,$idept,$icompany,$username){
        $this->db->select('*');
        $this->db->from('tm_user_deprole');
        $this->db->where('id_company', $icompany);
        $this->db->where('username', $username);
        $this->db->where('i_departement', $idept);
        $this->db->where('i_level', $ilevel);
        $this->db->where('i_apps', $this->session->userdata('i_apps'));
        return $this->db->get();
    }

    public function insert($iuser,$icompany,$idept,$ilevel){
        $data = array(
            'id_company'    => $icompany,
            'username'      => $iuser,
            'i_departement' => $idept,
            'i_level'       => $ilevel,
            'i_apps'        => $this->session->userdata('i_apps'),
        );
        $this->db->insert('public.tm_user_deprole', $data);
    }

    public function update($iuser,$icompany,$idept,$ilevel,$iuserold,$icompanyold,$ideptold,$ilevelold){
        $data = array(
            'id_company'    => $icompany,
            'username'      => $iuser,
            'i_departement' => $idept,
            'i_level'       => $ilevel,
            'i_apps'        => $this->session->userdata('i_apps'),
        );

        $this->db->where('username', $iuserold);
        $this->db->where('i_departement', $ideptold);
        $this->db->where('i_level', $ilevelold);
        $this->db->where('id_company', $icompanyold);
        $this->db->where('i_apps', $this->session->userdata('i_apps'));
        $this->db->update('public.tm_user_deprole', $data);
    }

    public function delete($username,$ilevel,$idepartement,$idcompany)
    {
        $this->db->where('username', $username);
        $this->db->where('i_level', $ilevel);
        $this->db->where('i_departement', $idepartement);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_apps', $this->session->userdata('i_apps'));
        $this->db->delete('public.tm_user_deprole');
    }
}
/* End of file Mmaster.php */