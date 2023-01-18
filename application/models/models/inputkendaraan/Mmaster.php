<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacaarea(){
    return $this->db->order_by('i_area','desc')->get('tr_area')->result();
  }

  public function bacajeniskendaraan(){
    return $this->db->order_by('i_kendaraan_jenis','ASC')->get('tr_kendaraan_jenis')->result();
  }

  public function bacajenisbbm(){
    return $this->db->order_by('i_kendaraan_bbm','ASC')->get('tr_kendaraan_bbm')->result();
  }

  public function insert($ikendaraan,$iperiode,$iarea,$ikendaraanjenis,$ikendaraanbbm,$epengguna,$dpajak){
    $data = array(
        'i_kendaraan' => $ikendaraan,
        'i_periode' => $iperiode,
        'i_area' => $iarea,
        'i_kendaraan_jenis' => $ikendaraanjenis,
        'i_kendaraan_bbm' => $ikendaraanbbm,
        'e_pengguna' => $epengguna,
        'd_pajak' => $dpajak,
    );
    
    $this->db->insert('tr_kendaraan', $data);
  }
}

/* End of file Mmaster.php */
