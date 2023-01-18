<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder)
    {
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("
            SELECT
                0 AS NO,
                username,
                a.i_departement,
                e_departement_name,
                a.i_level,
                e_level_name,
                array_agg(e_bagian_name) AS e_bagian_name,
                CASE
                    WHEN a.f_status = TRUE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                '$folder' AS folder,
                '$i_menu' AS i_menu
            FROM
                tr_departement_cover a
            INNER JOIN public.tr_departement b ON
                (b.i_departement = a.i_departement)
            INNER JOIN public.tr_level c ON
                (c.i_level = a.i_level)
            INNER JOIN tr_bagian d ON
                (d.i_bagian = a.i_bagian
                AND a.id_company = d.id_company)
            WHERE a.id_company = '".$this->session->userdata('id_company')."'
            GROUP BY
                1,2,3,4,5,6,8,9,10", false);

        $datatables->edit('e_bagian_name', function ($data) {
            return '<span>'.str_replace('"','', str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['e_bagian_name'])))).'</span>';
        });       
        $datatables->edit('status', 
            function ($data) {
                $username           = trim($data['username']);
                $idepartement       = trim($data['i_departement']);
                $ilevel             = trim($data['i_level']);
                $folder             = $data['folder'];
                $id_menu            = $data['i_menu'];
                $status             = $data['status'];
                if ($status=='Aktif') {
                    $warna = 'success';
                }else{
                    $warna = 'danger';
                }
                $data    = '';
                $combine = $username.'|'.$idepartement.'|'.$ilevel;
                if(check_role($id_menu, 3)){
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$combine\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

		$datatables->add('action', function ($data) {
            $username      = trim($data['username']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $idepartement  = $data['i_departement'];
            $ilevel        = $data['i_level'];
            $data          = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$username/$idepartement/$ilevel\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_level');
        $datatables->hide('i_departement');
        $datatables->hide('folder');
        return $datatables->generate();
	}

    public function status($username,$depart,$id_company,$ilevel)
    {
        $this->db->select('f_status');
        $this->db->from('tr_departement_cover');
        $this->db->where('username', $username);
        $this->db->where('i_departement', $depart);
        $this->db->where('id_company', $id_company);
        $this->db->where('i_level', $ilevel);
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
        $this->db->where('username', $username);
        $this->db->where('i_departement', $depart);
        $this->db->where('id_company', $id_company);
        $this->db->where('i_level', $ilevel);
        $this->db->update('tr_departement_cover', $data);
    }

    public function get_user($cari,$idept,$ilevel)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT 
                username
            FROM public.tm_user_deprole
            WHERE f_status = 't' 
                AND username ILIKE '%$cari%'
                AND i_departement = '$idept' 
                AND i_level = '$ilevel' 
                AND id_company = '".$this->session->userdata('id_company')."' 
            ORDER BY username", FALSE);
    }

    public function bacadept()
    {
        $this->db->select("*");
        $this->db->from("public.tr_departement");
        $this->db->where("f_status","t");
        $this->db->order_by("e_departement_name");
        return $this->db->get();
    }

    public function bacalevel()
    {
        $this->db->select("*");
        $this->db->from("public.tr_level");
        $this->db->where("f_status","t");
        $this->db->order_by("e_level_name");
        return $this->db->get();
    }

    public function get_bagian($idept,$cari)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_bagian,
                e_bagian_name
            FROM
                tr_bagian a
            INNER JOIN tr_type b
                ON (b.i_type = a.i_type)
            WHERE
                a.f_status = 't'
                AND e_bagian_name ILIKE '%$cari%'
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                e_bagian_name
        ", FALSE);
    }

	public function delete($username,$ilevel,$idept)
    {
        $this->db->where('username', $username);
        $this->db->where('i_level', $ilevel);
        $this->db->where('i_departement', $idept);
        $this->db->where('id_company', $this->session->userdata('id_company'));        
        $this->db->delete('tr_departement_cover');
    }

	public function insert($username,$ilevel,$idept,$ibagian)
    {
        $data = array(
            'i_departement' => $idept,
            'i_level'       => $ilevel,
            'i_bagian'      => $ibagian,
            'id_company'    => $this->session->userdata('id_company'),
            'username'      => $username,
        );
        $this->db->insert('tr_departement_cover', $data);
    }

    public function cek_data($username,$idepartement,$ilevel)
    {
        $this->db->distinct()->select('username, i_level, i_departement');
        $this->db->from('tr_departement_cover');
        $this->db->where('username', $username);
        $this->db->where('i_departement', $idepartement);
        $this->db->where('i_level', $ilevel);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        return $this->db->get();
    }

    public function cek_datadetail($username,$idepartement,$ilevel)
    {
        $this->db->select('a.i_bagian, e_bagian_name');
        $this->db->from('tr_departement_cover a');
        $this->db->join('tr_bagian b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company');
        $this->db->where('username', $username);
        $this->db->where('i_departement', $idepartement);
        $this->db->where('i_level', $ilevel);
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        return $this->db->get();
    }
}
/* End of file Mmaster.php */