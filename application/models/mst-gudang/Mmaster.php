<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query(
            "SELECT DISTINCT
                0 AS NO,
                a.id,
                a.i_bagian,
                a.e_bagian_name,
                c.e_lokasi_name,
                e.e_departement_name,
                b.e_type_name,
                a.id_company,
                CASE
                    WHEN a.f_status = TRUE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                $i_menu AS i_menu,
                '$folder' AS folder
            FROM
                tr_bagian a
            INNER JOIN tr_type b ON
                (b.i_type = a.i_type)
            INNER JOIN tr_lokasi c ON
                c.i_lokasi = a.i_lokasi AND c.id_company = a.id_company
            INNER JOIN tr_departement_cover d ON
                (d.i_bagian = a.i_bagian AND d.id_company = a.id_company)
            LEFT JOIN public.tr_departement e ON
                (e.i_departement = d.i_departement)
            WHERE
                a.id_company = '$idcompany'
            ORDER BY
                a.id
        ", fALSE);

        $datatables->edit('status', 
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
            $id     = trim($data['id']);
            $i_menu = $data['i_menu'];
            $kode   = $data['i_bagian'];
            $folder = $data['folder'];
            $data   = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$kode\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$kode\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
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

    public function get_jenis($id){
        $idcompany = $this->session->userdata("id_company");
        $this->db->select('*');
        $this->db->from('tr_type');
        $this->db->where('i_type', $id);
        $this->db->where('f_status', 't');
        $this->db->order_by('e_type_name');
        return $this->db->get();
    }

    public function get_jenisgudang($cari,$head){
        $cari = str_replace("'", "", $cari);
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query("
            SELECT DISTINCT
            a.i_type,
            a.e_type_name
            FROM tr_type a
            INNER JOIN public.tr_departement b
            ON (b.i_departement = a.i_departement)
            WHERE
            b.head = '$head'
        ");
    }

    public function get_jenisgudangview(){
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query("
            SELECT
            a.i_type,
            a.e_type_name
            FROM tr_type a
            INNER JOIN public.tr_departement b
            ON (b.i_departement = a.i_departement)
        ");
    }

    public function get_kelompok($cari,$igroup){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT
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
        $this->db->where('i_type', $kode);
        return $this->db->get()->row()->i_kode_group_barang;
    }

    public function get_bagian($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT DISTINCT
                a.head
            FROM
                public.tr_departement a 
            WHERE
                f_status = 't'
                AND (head ILIKE '%$cari%')
            GROUP BY
                head
            ORDER BY
                head
        ", FALSE);
    }

    public function cek_data($id){
        $this->db->select('a.*, c.i_departement, c.head');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_type b', 'b.i_type = a.i_type');
        $this->db->join('public.tr_departement c', 'c.i_departement = b.i_departement');
        $this->db->where('a.id', $id);
        return $this->db->get();
    }

    public function insert($ikode,$enama,$ilokasi,$ijenis,$jenis_bagian,$inter_exter){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'i_bagian'      => $ikode,
            'e_bagian_name' => $enama,
            'i_lokasi'      => $ilokasi,
            'i_type'        => $ijenis,
            'id_company'    => $idcompany,
            'e_jenis_bagian'=> $jenis_bagian,
            'id_kategori_jahit'=> $inter_exter,
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

    public function update($id,$ikode,$enama,$ilokasi,$ijenis,$jenis_bagian,$inter_exter){
        $data = array(
            'i_bagian'      => $ikode,
            'e_bagian_name' => $enama,
            'i_lokasi'      => $ilokasi,
            'i_type'        => $ijenis,
            'e_jenis_bagian'=> $jenis_bagian,
            'id_kategori_jahit'=> $inter_exter,
        );
        $this->db->where('id', $id);
        $this->db->update('tr_bagian', $data);
    }
}

/* End of file Mmaster.php */