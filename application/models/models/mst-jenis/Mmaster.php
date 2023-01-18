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
                                a.i_type_code,
                                a.e_type_name,
                                a.i_kode_kelompok,
                                b.e_nama_kelompok,
                                a.i_kode_group_barang,
                                c.e_nama_group_barang,
                                to_char(a.d_entry, 'YYYY Mon DD HH24:MI:SS') as tgl_input,
                                a.id_company,
                                case when a.f_status = TRUE then 'Aktif' else 'Tidak Aktif' end as status,
                                $i_menu AS i_menu,
                                '$folder' as folder
                            FROM
                                tr_item_type a
                            JOIN tr_kelompok_barang b ON
                                a.i_kode_kelompok = b.i_kode_kelompok 
                                and a.id_company = b.id_company
                            JOIN tr_group_barang c ON
                                b.i_kode_group_barang = c.i_kode_group_barang
                                and a.id_company = c.id_company
                            WHERE
                                a.id_company = '$idcompany'
                            ORDER BY
                                a.i_type_code
                            ", FALSE);

        $datatables->edit(
        'status', 
                function ($data) {
                    $id         = trim($data['i_type_code']);
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
            $itypecode = trim($data['i_type_code']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$itypecode/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$itypecode/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_kode_kelompok');
        $datatables->hide('i_kode_group_barang');
        $datatables->hide('id_company');
        
        return $datatables->generate();
	}

     public function status($id){
            $this->db->select('f_status');
            $this->db->from('tr_item_type');
            $this->db->where('i_type_code', $id);
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
            $this->db->where('i_type_code', $id);
            $this->db->update('tr_item_type', $data);
    }

    public function cekkode($kode){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT i_type_code FROM tr_item_type WHERE i_type_code ='$kode' and id_company='$idcompany'", FALSE);
    }
    
	function cek_data($id, $idcompany){
        
        $this->db->select('a.*, b.e_nama_group_barang');
        $this->db->from('tr_item_type a');
        $this->db->join('tr_group_barang b', 'a.i_kode_group_barang=b.i_kode_group_barang','left');
        $this->db->where('i_type_code', $id);
        $this->db->where('a.id_company', $idcompany);
    return $this->db->get();
    }
    
    function get_jenis($idcompany){
        $this->db->select('a.*');
        $this->db->from('tr_item_type a');
        $this->db->where('a.id_company', $idcompany);
    return $this->db->get();
    }

    function get_kelompok($idcompany){

        $this->db->select('*');
        $this->db->from('tr_kelompok_barang');
        $this->db->where('id_company', $idcompany);
        $this->db->where('f_status', 't');
        $this->db->order_by('i_kode_kelompok', 'ASC');
        return $this->db->get();
    }

    function getkelompoknya($ikelompokbrg){
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tr_kelompok_barang');
        $this->db->join('tr_group_barang','tr_kelompok_barang.i_kode_group_barang = tr_group_barang.i_kode_group_barang');
        $this->db->where('i_kode_kelompok', $ikelompokbrg);
        $this->db->where('tr_kelompok_barang.id_company', $idcompany);
        $this->db->order_by('i_kode_kelompok', 'ASC');
        return $this->db->get();
    }

    function get_group($idcompany){
        
        $this->db->select('*');
        $this->db->from('tr_group_barang');
        $this->db->where('id_company', $idcompany);
    return $this->db->get();
    }

    public function getjenis($ikelompokbrg) {
        $this->db->select("i_kode_kelompok, e_nama");
        $this->db->from('tr_kelompok_barang');
        $this->db->where('i_kode_group_barang', $igroupbrg);
        $this->db->order_by('i_kode_kelompok');
        return $this->db->get();
  }

	public function insert($itypecode, $ikelompok, $etypename, $igroupbrg, $idcompany){

        $data = array(
              'i_type_code'         => $itypecode,
              'i_kode_kelompok'     => $ikelompok,
              'e_type_name'         => $etypename,
              'i_kode_group_barang' => $igroupbrg,  
              'id_company'          => $idcompany, 
              'd_entry'             => current_datetime(),         
    );
    
    $this->db->insert('tr_item_type', $data);
    }

    public function update($id, $itypecode, $ikelompok, $etypename, $igroupbrg, $idcompany){
        
        $data = array(
            'i_type_code'        => $itypecode,
            'i_kode_kelompok'    => $ikelompok, /*PRIMARY (KELOMPOK=KATEGORI)*/
            'e_type_name'        => $etypename, 
            'i_kode_group_barang'=> $igroupbrg,    
            'd_update'           => current_datetime(),   
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_item_type', $data);

        $data = array(
            'i_kode_kelompok'    => $ikelompok,
        );

        $this->db->where('i_type_code', $itypecode);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_material', $data);
    }
}
/* End of file Mmaster.php */