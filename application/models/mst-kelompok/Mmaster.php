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
                               a.i_kode_kelompok,
                               a.e_nama_kelompok,
                               c.e_nama_divisi,
                               a.i_kode_group_barang,
                               b.e_nama_group_barang,
                               to_char(a.d_entry, 'YYYY Mon DD HH24:MI:SS') as tgl_input,
                               a.id_company,
                                case
                                    when
                                       a.f_status = TRUE 
                                    then
                                       'Aktif' 
                                    else
                                       'Tidak Aktif' 
                                end
                                as status,
                               '$i_menu' as i_menu , 
                               '$folder' as folder 
                            FROM
                               tr_kelompok_barang a 
                               JOIN
                                  tr_group_barang b 
                                  on a.i_kode_group_barang = b.i_kode_group_barang 
                                  and a.id_company = b.id_company
                                LEFT JOIN
                                  tr_divisi_new c
                                  ON (c.id = a.id_divisi)
                            WHERE
                                a.id_company = '$idcompany'
                            ORDER BY
                                a.i_kode_kelompok
                            ", FALSE);
                                        
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
            $ikelompok = trim($data['id']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$ikelompok/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ikelompok/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('i_kode_group_barang');
        $datatables->hide('id_company');
        
        return $datatables->generate();
	}

    public function status($id){
            $this->db->select('f_status');
            $this->db->from('tr_kelompok_barang');
            $this->db->where('id', $id);
            $query = $this->db->get();
            $stat = '';
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
            $this->db->update('tr_kelompok_barang', $data);
    }

    public function cekkode($kode){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT i_kode_kelompok FROM tr_kelompok_barang WHERE i_kode_kelompok ='$kode' and id_company = '$idcompany'", FALSE);
    }

    function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tr_kelompok_barang');
        return $this->db->get()->row()->id+1;
    }

	function cek_data($id, $idcompany){  
        return $this->db->query("
                                    SELECT 
                                        a.*,
                                        b.e_coa_name,
                                        c.e_nama_divisi
                                    FROM
                                        tr_kelompok_barang a
                                    LEFT JOIN 
                                        tr_coa b 
                                        ON a.i_coa = b.i_coa
                                    LEFT JOIN
                                        tr_divisi_new c
                                        ON a.id_divisi = c.id
                                    WHERE 
                                        a.id = '$id'
                                    AND 
                                        a.id_company = '$idcompany'", FALSE);      
    return $this->db->get();
    }

    function cek_group_barang(){
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tr_group_barang');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->order_by('i_kode_group_barang');
    return $this->db->get();
    }

    public function coa($cari){
        return $this->db->query("
                                    SELECT
                                        a.i_coa,
                                        a.e_coa_name,
                                        b.e_coa_ledger_name
                                    FROM
                                        tr_coa a
                                    LEFT JOIN
                                        tr_coa_ledger b 
                                        ON a.i_coa_ledger = b.i_coa_ledger
                                    WHERE 
                                        (a.i_coa ILIKE '%$cari%' OR a.e_coa_name ILIKE '%$cari%')
                                    ORDER BY 
                                        a.e_coa_name ASC
                                ",FALSE);
    }

    public function divisi($cari){
        return $this->db->query("
                                    SELECT
                                        a.id,
                                        a.i_kode_divisi,
                                        a.e_nama_divisi
                                    FROM
                                        tr_divisi_new a
                                    WHERE 
                                        (a.i_kode_divisi ILIKE '%$cari%' OR a.e_nama_divisi ILIKE '%$cari%')
                                        AND a.f_status = 't'
                                    ORDER BY 
                                        a.i_kode_divisi ASC
                                ",FALSE);
    }

	public function insert($id, $ikelompok, $enama, $igroupbarang, $idcompany, $icoa, $iddivisi){

        $data = array(  
                        'id'                   => $id,
                        'i_kode_kelompok'      => $ikelompok,
                        'e_nama_kelompok'      => $enama,
                        'i_kode_group_barang'  => $igroupbarang,
                        'i_coa'                => $icoa,
                        'id_company'           => $idcompany,
                        'd_entry'              => current_datetime(),
                        'id_divisi'            => $iddivisi        
    );    
    $this->db->insert('tr_kelompok_barang', $data);
    }

    public function update($id, $ikelompok, $enama, $igroupbarang, $icoa, $iddivisi){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(   
                        'i_kode_kelompok'      => $ikelompok,
                        'e_nama_kelompok'      => $enama, 
                        'i_kode_group_barang'  => $igroupbarang,
                        'i_coa'                => $icoa, 
                        'd_update'             => current_datetime(),
                        'id_divisi'            => $iddivisi    
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_kelompok_barang', $data);

        $data = array(   
            'i_kode_group_barang'  => $igroupbarang,
        );
        $this->db->where('i_kode_kelompok', $ikelompok);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_item_type', $data);

        $data = array(   
            'i_kode_group_barang'  => $igroupbarang,
        );
        $this->db->where('i_kode_kelompok', $ikelompok);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_material', $data);
    }
}
/* End of file Mmaster.php */