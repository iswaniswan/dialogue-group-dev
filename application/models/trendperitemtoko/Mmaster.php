<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function cekarea($username, $idcompany){
    $this->db->select('i_area');
    $this->db->from('public.tm_user_area');
    $this->db->where('username', $username);
    $this->db->where('id_company', $idcompany);
    $this->db->where('i_area', '00');
    $query = $this->db->get();
    if ($query->num_rows()>0) {
        return '00';
    }else{
        return 'xx';
    }
  }

  public function bacaarea($username, $idcompany, $iarea){
    if ($iarea=='00') {
      return $this->db->query("SELECT * FROM tr_area", FALSE)->result();
    }else{        
      return $this->db->query("
          SELECT
              *
          FROM
              tr_area
          WHERE
              i_area IN (
              SELECT
                  i_area
              FROM
                  public.tm_user_area
              WHERE
                  username = '$username'
                  AND id_company = '$idcompany')
      ", FALSE)->result();
    }
  }

  function baca($iarea){
      if($iarea=="NA"){
        return $this->db->query("
                          SELECT
                            * 
                          FROM 
                            tt_trenditemtoko 
                          ORDER BY 
                            i_customer, 
                            i_product"
                          , false);
      }else{
        return $this->db->query(" 
                          SELECT
                            * 
                          FROM 
                            tt_trenditemtoko 
                          WHERE 
                            i_area = '$iarea' 
                          ORDER BY 
                            i_customer, 
                            i_product"
                          , false);
      }
    }
}

/* End of file Mmaster.php */