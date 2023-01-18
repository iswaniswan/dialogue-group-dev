<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT tm_gradeb.kode_brg_wip, tm_gradeb.i_kodebrg, tm_gradeb.harga, tm_gradeb.d_update, tm_gradeb.f_status_aktif,  $i_menu as i_menu FROM tm_gradeb"); 

    $datatables->edit('f_status_aktif', function ($data) {
      $f_status_aktif = trim($data['f_status_aktif']);
      if($f_status_aktif == 'f'){
         return  "Tidak Aktif";
      }else {
        return "Aktif";
      }
    });

		$datatables->add('action', function ($data) {
            $ikodebrgwip= trim($data['kode_brg_wip']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-wip-hargagradeb/cform/view/$ikodebrgwip/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-wip-hargagradeb/cform/edit/$ikodebrgwip/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if(check_role($i_menu, 4)){
              $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ikodebrgwip\"); return false;'><i class='fa fa-trash'></i></a>";
          }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tm_gradeb');
     $this->db->join('tm_barang_wip', 'tm_gradeb.i_kodebrg = tm_barang_wip.i_kodebrg');  
    $this->db->where('kode_brg_wip', $id);

    return $this->db->get();
    }
    
    function get_wipbarang(){
        $this->db->select('*');
        $this->db->from('tm_barang_wip');
    return $this->db->get();
    }


	public function insert($ikodebrgwip, $ikodebrg, $harga, $bulan, $tahun){
        $dentry = date("Y-m-d H:i:s");
        $kode = $this->db->query("SELECT kode_brg_wip FROM tm_gradeb ORDER BY kode_brg_wip DESC LIMIT 1");
        if ($kode->num_rows() > 0) {
            $row_kode = $kode->row();

            $kode1  = $row_kode->kode_brg_wip;
            $kode2  = substr($kode1,3,strlen($kode1)-1); 
            $kodejml= $kode2+1;
            switch(strlen($kodejml)) {
              case 1:
                $icode  = 'HGB'.'000'.$kodejml;
              break;
              case 2:
                $icode  = 'HGB'.'00'.$kodejml;
              break;
              case 3:
                $icode  = 'HGB'.'0'.$kodejml;
              break;
              default:
                $icode  = 'HGB'.$kodejml;
            }        
            $ikodebrgwip = $icode;
        }else
        $ikodebrgwip = "HGB0001";
          
        $data = array(
              'kode_brg_wip'    => $ikodebrgwip,
              'i_kodebrg'       => $ikodebrg, 
              'harga'           => $harga, 
              'bulan'           => $bulan,
              'tahun'           => $tahun,
              'd_entry'         => $dentry,        
    );
    
    $this->db->insert('tm_gradeb', $data);
    }

    public function update($ikodebrgwip, $ikodebrg, $harga, $bulan, $tahun){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
              'kode_brg_wip'    => $ikodebrgwip,
              'i_kodebrg'       => $ikodebrg, 
              'harga'           => $harga, 
              'bulan'           => $bulan,
              'tahun'           => $tahun, 
              'd_update'        => $dupdate,  

    );

    $this->db->where('kode_brg_wip', $ikodebrgwip);
    $this->db->update('tm_gradeb', $data);
    }

    public function cancel($ikodebrgwip){
      $data = array(
        'f_status_aktif'=>'f',
    );
      $this->db->where('kode_brg_wip', $ikodebrgwip);
      $this->db->update('tm_gradeb', $data);
    }

}

/* End of file Mmaster.php */