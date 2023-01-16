<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function getQCset($dfrom, $dto){
        //header("Content-Type: application/json", true);   
        $pisah1 = explode("-", $dfrom);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];
        $iperiode = $thn1.$bln1;
        if($bln1 == 1) {
          $bln_query = 12;
          $thn_query = $thn1-1;
        }else {
          $bln_query = $bln1-1;
          $thn_query = $thn1;
          if ($bln_query < 10){
            $bln_query = "0".$bln_query;
          }
        }
        $pisah1 = explode("-", $dto);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];

        $this->db->select("* from f_mutasi_qcset($bln_query, $thn_query, '$dfrom','$dto', $bln1, $thn1)",false);
        $data = $this->db->get();
        return $data;
  }
}
/* End of file Mmaster.php */