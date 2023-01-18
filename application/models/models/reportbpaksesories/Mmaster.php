<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function getmutasi($from, $to){
      $dfromback = date('d-m-Y',strtotime($from . "first day of previous month"));
      $dtoback = date('d-m-Y',strtotime($from . "last day of previous month"));
     
      $periode1 = date("Ym", strtotime($from));
      $periode2 = date("Ym", strtotime($dfromback));

      $this->db->SELECT("* from f_mutasi_bp_aksesories('$periode1', '$periode2', to_date('$from','dd-mm-yyyy'), to_date('$to','dd-mm-yyyy'))", false);
      return $this->db->get();
  }
}
/* End of file Mmaster.php */