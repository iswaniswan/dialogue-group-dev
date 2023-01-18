<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {    

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT
                               ROW_NUMBER () OVER (
                            ORDER BY
                               id) as no,
                               id,
                               e_class_name,
                               $i_menu as i_menu,
                              '$folder' as folder,
                               case
                                  when
                                     f_status = TRUE 
                                  then
                                     'Aktif' 
                                  else
                                     'Tidak Aktif' 
                               end
                               as status 
                            FROM tr_class_product ", FALSE);
        
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
                    // if(check_role($id_menu, 3)){
                    //     $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    // }else{
                    //     $data   .= "<span class=\"label label-$warna\">$status</span>";
                    // }
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                    return $data;
                }
        );

		// $datatables->add('action', function ($data) {
  //           $ikelompok = trim($data['i_kode_klasifikasi']);
  //           $i_menu = $data['i_menu'];
  //           $data = '';
  //           if(check_role($i_menu, 2)){
  //               $data .= "<a href=\"#\" onclick='show(\"mst-klasifikasi/cform/view/$ikelompok/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
  //           }
  //           if(check_role($i_menu, 3)){
  //               $data .= "<a href=\"#\" onclick='show(\"mst-klasifikasi/cform/edit/$ikelompok/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
  //           }
  //           // if(check_role($i_menu, 4)){
  //           //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ikelompok\"); return false;'><i class='ti-close'></i></a>";
  //           // }
		// 	return $data;
  //   });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        
        return $datatables->generate();
	}

  public function status($id){
            $this->db->select('f_status_aktif');
            $this->db->from('tm_klasifikasi_barang');
            $this->db->where('i_kode_klasifikasi', $id);
            $query = $this->db->get();
            if ($query->num_rows()>0) {
                $row    = $query->row();
                $status = $row->f_status_aktif;
                if ($status=='t') {
                    $stat = 'f';
                }else{
                    $stat = 't';
                }
            }
            $data = array(
                'f_status_aktif' => $stat 
            );
            $this->db->where('i_kode_klasifikasi', $id);
            $this->db->update('tm_klasifikasi_barang', $data);
  }

	function cek_data($id){
        $this->db->select('*');
        $this->db->from('tm_klasifikasi_barang a');
        
        $this->db->where('i_kode_klasifikasi', $id);
    return $this->db->get();
    }
    
    function get_kelompok(){
        $this->db->select('*');
        $this->db->from('tm_klasifikasi_barang');
    return $this->db->get();
    }

    function cek_group_barang(){
        $this->db->select('i_menu as i_menu_klasifikasi, e_menu, i_parent, n_urut, e_folder');
        $this->db->from('tm_menu');
    return $this->db->get();
    }

	public function insert($ikelompok, $enama, $igroupbarang, $ivalidasi){
        $dentry = date("Y-m-d H:i:s");

        $data = array(  
              'i_kode_klasifikasi'  => $ikelompok,
              'e_nama'              => $enama,
              'i_menu_klasifikasi'  => $igroupbarang,
              'f_validasi'          => $ivalidasi,
              'd_entry'             => $dentry,        
    );    
    $this->db->insert('tm_klasifikasi_barang', $data);
    }

    public function update($ikelompok, $enama, $igroupbarang, $ivalidasi){
        $dupdate = date("Y-m-d H:i:s");                
        $data = array(   
             'i_kode_klasifikasi'   => $ikelompok,
             'e_nama'               => $enama, 
             'i_menu_klasifikasi'   => $igroupbarang,
             'f_validasi'           => $ivalidasi,
             'd_update'             => $dupdate, 
    );

    $this->db->where('i_kode_klasifikasi', $ikelompok);
    $this->db->update('tm_klasifikasi_barang', $data);
    }

    public function cancel($ikelompok){
        $data = array(
          'f_status_aktif'=>'f',
      );
        $this->db->where('i_kode_klasifikasi', $ikelompok);
        $this->db->update('tm_klasifikasi_barang', $data);
      }
}
/* End of file Mmaster.php */