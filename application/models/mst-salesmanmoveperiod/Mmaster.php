<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	
	public function update($periodelama, $periodebaru){
      $now = current_datetime();
      $idcompany  = $this->session->userdata('id_company');
      return $this->db->query("INSERT INTO tr_customer_salesman(id_company, id_customer, id_salesman, id_area, e_periode, f_status, d_entry) 
         (SELECT id_company, id_customer, id_salesman, id_area, '$periodebaru' AS e_periode , f_status, now()::timestamp
         FROM tr_customer_salesman WHERE e_periode = '$periodelama' AND id_company = '$idcompany')
         ON CONFLICT (id_company, id_customer, id_salesman, id_area, e_periode) DO UPDATE SET d_update = '$now'", FALSE); 
   }
}
/* End of file Mmaster.php */