<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($iperiode){      
        return $this->db->query("
            SELECT
                *
            FROM
                tm_hpp
            WHERE
                e_periode = '$iperiode'
            ORDER BY
                e_product_name,
                i_product"
        , FALSE);
    }
}

/* End of file Mmaster.php */
