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
                                a.i_kode_kas, 
                                a.e_kas_name, 
                                b.e_kas_type_name, 
                                a.i_coa,
                                d.e_coa_name,
                                a.id_company,
                                case when a.f_status = TRUE then 'Aktif' else 'Tidak' end as status, 
                                '$i_menu' as i_menu, 
                                '$folder' as folder 
                            FROM 
			                    tr_kas_bank a
                                LEFT JOIN 
                                    tr_kas_type b ON (a.i_kas_type = b.i_kas_type)
                                LEFT JOIN 
                                    tr_bank c ON (a.i_bank = c.i_bank and c.id_company = a.id_company )
                                LEFT JOIN 
                                    tr_coa d ON (a.i_coa = d.i_coa)
                            WHERE
                                a.id_company = '$idcompany'
                            ORDER BY
                                a.i_kode_kas
		                    ", FALSE);

        $datatables->edit('status', function ($data) {
            $id         = trim($data['i_kode_kas']);
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
        });

        // $datatables->edit('e_coa_name', function($data){
        //     return $data['i_coa'].' - '.$data['e_coa_name'];
        // });

		$datatables->add('action', function ($data) {
            $ijenis = trim($data['i_kode_kas']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$ijenis/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ijenis/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');
        // $datatables->hide('i_coa');

        return $datatables->generate();
	}

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_kas_bank');
        $this->db->where('i_kode_kas', $id);
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
        $this->db->where('i_kode_kas', $id);
        $this->db->update('tr_kas_bank', $data);
    }

    public function get_header($id) {
        return $this->db->query("
        	                    SELECT 
                                    a.*,
                                    b.e_kas_type_name, 
                                    d.e_coa_name,
                                    c.e_bank_name
                                FROM 
			                        tr_kas_bank a
                                    LEFT JOIN 
                                        tr_kas_type b ON (a.i_kas_type = b.i_kas_type)
                                    LEFT JOIN 
                                        tr_bank c ON (a.i_bank = c.i_bank)
                                    LEFT JOIN 
                                        tr_coa d ON (a.i_coa = d.i_coa)
                                WHERE 
                                    a.i_kode_kas = '$id'
                                ", FALSE);
        
    }
    
    function get_jenisvoucher(){
        $this->db->select('*');
        $this->db->from('tr_kas_bank');
    	return $this->db->get();
    }

	public function insert($ijenis, $ejenisvoucher, $jeniskas, $jenisbank, $norek, $namarek, $coa){
        $dentry = date("Y-m-d H:i:s");
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
              'i_kode_kas'      => $ijenis,
              'e_kas_name'      => $ejenisvoucher, 
              'i_coa'           => $coa, 
              'i_bank'          => $jenisbank, 
              'i_kas_type'      => $jeniskas, 
              'e_nomor_rekening'=> $norek, 
              'e_nama_rekening' => $namarek,
              'id_company'      => $idcompany,
              'd_entry'         => $dentry,             
              
    );
    
    $this->db->insert('tr_kas_bank', $data);
    }

    public function update($id, $ijenis, $ejenisvoucher, $jeniskas, $jenisbank, $norek, $namarek, $coa){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'id'              => $id,
            'i_kode_kas'      => $ijenis,
            'e_kas_name'      => $ejenisvoucher,
            'i_coa'           => $coa, 
            'i_bank'          => $jenisbank, 
            'i_kas_type'      => $jeniskas,
            'e_nomor_rekening'=> $norek, 
            'e_nama_rekening' => $namarek,
            'd_update'        => $dupdate,               
    );

    $this->db->where('id', $id);
    $this->db->update('tr_kas_bank', $data);
    }


    public function jeniskas() {
        return $this->db->query("SELECT i_kas_type, e_kas_type_name FROM tr_kas_type WHERE f_status = 't' ORDER BY e_kas_type_name", FALSE);
    }

    public function cekkode($kode){
        return $this->db->query("SELECT i_kode_kas FROM tr_kas_bank WHERE i_kode_kas = '$kode'", FALSE);
    }

    public function jenisbank($cari) {
        $cari = str_replace("'", "", $cari);
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("   SELECT 
                                        i_bank, 
                                        e_bank_name 
                                    FROM 
                                        tr_bank 
                                    WHERE 
                                        f_status = 't' 
                                    AND id_company = '$idcompany'
                                    AND (i_bank ilike '%$cari%' or e_bank_name ilike '%$cari%')", FALSE);
        
    }

    public function coa($cari,$kas) {
        $cari = str_replace("'", "", $cari);
        $jeniskas = $this->db->query("SELECT i_kas_type, e_kas_type_name FROM tr_kas_type WHERE i_kas_type = '$kas'", false)->row()->e_kas_type_name;
        return $this->db->query("
                                SELECT
                                    a.i_coa,
                                    a.e_coa_name
                                FROM
                                	tr_coa a
                                	LEFT JOIN 
                                		tr_coa_ledger b
                                		ON (a.i_coa_ledger = b.i_coa_ledger)
                                WHERE	
                                	a.e_coa_name ilike '%$jeniskas%' 
                                    OR b.e_coa_ledger_name ilike '%$jeniskas%'
                                ORDER BY
                                    a.i_coa
                                ", FALSE);
    }
}

/* End of file Mmaster.php */