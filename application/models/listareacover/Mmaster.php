<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($df,$dt){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("select i_area, e_area_name, e_area_city, 0 as total from tr_area
                          where d_area_entry between '$df-01-01 00:00:00' and '$dt-01-01 00:00:00'
                          order by i_area");
        $datatables->edit('total', function ($data) {
            $i_area = $data['i_area'];
             $que = $this->db->query(" select COUNT(tr_area_cover.i_area) as total FROM tr_area_cover,tr_area where upper(tr_area_cover.i_area)='$i_area' group by tr_area.i_area",false);
            if($que->num_rows()>0){
              return $que->row()->total;
            }else{
                return 'Tidak Ada';
            }
        });
        return $datatables->generate();
	}
}

/* End of file Mmaster.php */
