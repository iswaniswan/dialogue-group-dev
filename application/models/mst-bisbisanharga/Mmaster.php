<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tm_harga_bisbisan.i_harga_bisbisan, tm_harga_bisbisan.i_supplier, tr_supplier.e_supplier_name, tm_harga_bisbisan.e_jenis_potong, 
        tm_harga_bisbisan.v_price, tm_harga_bisbisan.d_update, tm_harga_bisbisan.f_status_aktif, $i_menu as i_menu 
         FROM tm_harga_bisbisan 
         JOIN tr_supplier ON tm_harga_bisbisan.i_supplier = tr_supplier.i_supplier");

        $datatables->edit('f_status_aktif', function ($data) {
            $f_status_aktif = trim($data['f_status_aktif']);
            if($f_status_aktif == 'f'){
               return  "Tidak Aktif";
            }else {
              return "Aktif";
            }
        });

		$datatables->edit('e_jenis_potong', function ($data) {
          $jenis_potong = trim($data['e_jenis_potong']);
          if($jenis_potong == '1'){
             return "Potong Serong";
          }else if($jenis_potong == '2'){
            return "Potong Lurus";
          }else{
            return "Potong Spiral";
          }
        });
        
        $datatables->add('action', function ($data) {
            $id = trim($data['i_harga_bisbisan']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-bisbisanharga/cform/view/$id/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-bisbisanharga/cform/edit/$id/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if(check_role($i_menu, 4)){
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$id\"); return false;'><i class='fa fa-trash'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_supplier');
        
        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tm_harga_bisbisan');
    $this->db->join('tr_supplier', 'tm_harga_bisbisan.i_supplier = tr_supplier.i_supplier'); 
    $this->db->where('i_harga_bisbisan', $id);

    return $this->db->get();
    }
    
    function get_harga(){
        $this->db->select('*');
        $this->db->from('tm_harga_bisbisan');
    return $this->db->get();
    }

    function get_supplier(){
        $this->db->select('*');
        $this->db->from('tr_supplier');
    return $this->db->get();
    }

	public function insert($id, $isupplier, $ejenispotong, $eharga){
        $dentry = date("Y-m-d");
        $qid = $this->db->query("SELECT i_harga_bisbisan FROM tm_harga_bisbisan ORDER BY i_harga_bisbisan DESC LIMIT 1");
        if ($qid->num_rows() > 0) {
            $row_kode = $qid->row();

            $kode1  = $row_kode->i_harga_bisbisan;
            $kode2  = substr($kode1,3,strlen($kode1)-1); 
            $kodejml= $kode2+1;
            switch(strlen($kodejml)) {
              case 1:
                $icode  = 'HBB'.'000'.$kodejml;
              break;
              case 2:
                $icode  = 'HBB'.'00'.$kodejml;
              break;
              case 3:
                $icode  = 'HBB'.'0'.$kodejml;
              break;
              default:
                $icode  = 'HBB'.$kodejml;
            }        
            $id = $icode;
        }else
        $id = "HBB0001";

            $data = array(
                'i_harga_bisbisan'=> $id,
                'i_supplier'      => $isupplier, 
                'e_jenis_potong'  => $ejenispotong,
                'v_price'         => $eharga,         
                'd_entry'         => $dentry,        
    );
    
    $this->db->insert('tm_harga_bisbisan', $data);
    }

    public function update($id, $isupplier, $ejenispotong, $eharga){
        $dupdate = date("Y-m-d");
        $data = array(
            'i_harga_bisbisan'  => $id,
            'i_supplier'        => $isupplier, 
            'e_jenis_potong'    => $ejenispotong,
            'v_price'           => $eharga,         
            'd_update'          => $dupdate, 

    );

    $this->db->where('i_harga_bisbisan', $id);
    $this->db->update('tm_harga_bisbisan', $data);
    }

    public function cancel($id){
        $data = array(
          'f_status_aktif'=>'f',
      );
        $this->db->where('i_harga_bisbisan', $id);
        $this->db->update('tm_harga_bisbisan', $data);
      }

}

/* End of file Mmaster.php */