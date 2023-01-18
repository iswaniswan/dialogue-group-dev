<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function cek_data($ikodelokasi){
      $this->db->select(" a.*, b.e_color_name 
                        from tm_ic a 
                        inner join tr_color b on a.i_color = b.i_color
                        where a.i_kode_lokasi = '$ikodelokasi' 
                        and f_product_active = 't' ",false);
      return $this->db->get();
  }

    public function bacagudang($username,$idcompany){
      #$this->db->select("* from tr_master_gudang where i_kode_master in('G08','G13','GD10004')",false);
      $this->db->select("   *
                          from
                            tm_sub_bagian
                          where
                            i_kode_lokasi in(
                            select
                              i_kode_lokasi
                            from
                              public.tm_user
                            where
                              username = '$username'
                              and id_company = '$idcompany')",false);
      return $this->db->get()->result();
    }
}

/* End of file Mmaster.php */
