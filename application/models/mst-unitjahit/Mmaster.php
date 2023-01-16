<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT 
        DISTINCT 
        tr_unit_jahit.id, 
        tr_unit_jahit.e_nama_unit, 
        tr_unit_jahit.e_perusahaan_name, 
        tr_unit_jahit.e_unitjahit_address,
        tr_unit_jahit.e_penanggung_jawab_name, 
        tr_unit_jahit.e_admin_name, 
        case
            when
                tr_unit_jahit.f_status = TRUE 
            then
                'Aktif' 
            else
                'Tidak Aktif' 
        end f_status,
        '$i_menu' as i_menu,
        '$folder' AS folder 
        FROM tr_unit_jahit");

        $datatables->edit('f_status', function ($data) {
            $id         = trim($data['id']);
            $folder     = $data['folder'];
            $id_menu    = $data['i_menu'];
            $status     = $data['f_status'];
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
            $iunitjahit = trim($data['id']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-unitjahit/cform/view/$iunitjahit/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-unitjahit/cform/edit/$iunitjahit/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');

        return $datatables->generate();
	}

	function cek_data($id){

    return $this->db->query("SELECT 
        a.*,
        b.e_nama_kategori
        FROM tr_unit_jahit a
        LEFT JOIN tr_kategori_jahit b
        ON (b.id = a.id_kategori_jahit)
        WHERE 
        a.id = '$id'");
    }
    
    function get_unitjahit(){
        $this->db->select('*');
        $this->db->from('tr_unit_jahit');
        return $this->db->get();
    }

	public function insert($ikategori, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname){
        $dentry = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 4);
        $data = array(
              'id_kategori_jahit'       => $ikategori,
              'e_nama_unit'             => $eunitjahitname,  
              'e_unitjahit_address'     => $eunitjahitaddress,
              'e_perusahaan_name'       => $eperusahaanname,
              'e_penanggung_jawab_name' => $epenanggungjawabname, 
              'e_admin_name'            => $eadminname, 
              'd_entry'                 => $dentry,        
    );
    
    $this->db->insert('tr_unit_jahit', $data);
    }

    public function cancel($iunitjahit){
        $data = array(
          'f_status'=>'f',
      );
        $this->db->where('id', $iunitjahit);
        $this->db->update('tr_unit_jahit', $data);
      }

    public function update($id, $ikategori, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname){
        $dupdate = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 4);
        $data = array(
            'id_kategori_jahit'       => $ikategori,
            'e_nama_unit'             => $eunitjahitname,  
            'e_unitjahit_address'     => $eunitjahitaddress,
            'e_perusahaan_name'       => $eperusahaanname,
            'e_penanggung_jawab_name' => $epenanggungjawabname, 
            'e_admin_name'            => $eadminname, 
            'd_update'                => $dupdate,  

    );

    $this->db->where('id', $id);
    $this->db->update('tr_unit_jahit', $data);
    }

    public function getkategori()
    {
        return $this->db->query("SELECT id,e_nama_kategori FROM tr_kategori_jahit WHERE f_status = 't'
        ", false);
    }

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_unit_jahit');
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
        $this->db->update('tr_unit_jahit', $data);
    }

}

/* End of file Mmaster.php */