<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query("
            SELECT DISTINCT
                0 AS NO,
                a.head,
                CASE
                    WHEN a.f_status = TRUE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                $i_menu AS i_menu,
                '$folder' AS folder
            FROM
                public.tr_departement a
            WHERE
                f_status = 't'
            ORDER BY
                a.head
        ", fALSE);

        $datatables->edit('status', 
            function ($data) {
                // $id         = trim($data['id']);
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
                    $data   .= "<a title='Update Status' return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );
        /* $datatables->hide('id');
        $datatables->hide('e_departement_name'); */
        $datatables->hide('i_menu');
        $datatables->hide('folder');

        return $datatables->generate();
	}

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_bagian');
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
        $this->db->update('tr_bagian', $data);
    }

    public function cek_kode($kode){
        $this->db->select('*');
        $this->db->from('tr_bagian');
        $this->db->where('i_bagian', $kode);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function get_lokasigudang(){
        $this->db->select('*');
        $this->db->from('tr_lokasi');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->order_by('e_lokasi_name');
        return $this->db->get();
    }

    public function get_jenisgudang(){
        $this->db->select('*');
        $this->db->from('tr_type');
        $this->db->where('f_status', 't');
        // $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->order_by('e_type_name');
        return $this->db->get();
    }

    public function get_kelompok($cari,$igroup){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_kode_kelompok,
                e_nama_kelompok
            FROM
                tr_kelompok_barang
            WHERE
                f_status = 't'
                AND i_kode_group_barang = '$igroup'
                AND (e_nama_kelompok ILIKE '%$cari%')
                AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY
                e_nama_kelompok
        ", FALSE);
    }

    public function get_detailbagian($kode){
        $this->db->select('a.*, e_nama_kelompok');
        $this->db->from('tr_bagian_kelompokbarang a');
        $this->db->join('tr_kelompok_barang b','b.i_kode_kelompok = a.i_kode_kelompok AND a.id_company = b.id_company');
        $this->db->where('i_bagian', $kode);
        $this->db->where('a.id_company', $this->session->userdata("id_company"));
        $this->db->order_by('e_nama_kelompok');
        return $this->db->get();
    }

    public function get_group($kode){
        $this->db->select('i_kode_group_barang');
        $this->db->from('tr_type');
        $this->db->where('f_status', 't');
        $this->db->where('i_type', $kode);
        $this->db->order_by('e_type_name');
        return $this->db->get()->row()->i_kode_group_barang;
    }

    public function cek_data($id){
        $this->db->select('*');
        $this->db->from('tr_bagian');
        $this->db->where('id', $id);
        return $this->db->get();
    }

    public function insert($ikode,$enama,$ilokasi,$ijenis){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'i_bagian'      => $ikode,
            'e_bagian_name' => $enama,
            'i_lokasi'      => $ilokasi,
            'i_type'        => $ijenis,
            'id_company'    => $idcompany,
        );
        $this->db->insert('tr_bagian', $data);
    }   

    public function insertdetail($ikode,$ikelompok){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'i_bagian'        => $ikode,
            'i_kode_kelompok' => $ikelompok,
            'id_company'      => $idcompany,
        );
        $this->db->insert('tr_bagian_kelompokbarang', $data);
    }

    public function deletedetail($kode)
    {
        $this->db->where('i_bagian', $kode);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->delete('tr_bagian_kelompokbarang');
    }

    public function update($id,$ikode,$enama,$ilokasi,$ijenis){
        $data = array(
            'i_bagian'      => $ikode,
            'e_bagian_name' => $enama,
            'i_lokasi'      => $ilokasi,
            'i_type'        => $ijenis,
        );
        $this->db->where('id', $id);
        $this->db->update('tr_bagian', $data);
    }
}

/* End of file Mmaster.php */