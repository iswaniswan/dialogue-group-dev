<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data(){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_area,
                i_ttb,
                d_ttb,
                i_customer||' - '||e_customer_name AS customer,
                v_ttb_netto,
                i_bbm,
                d_bbm,
                d_receive1
            FROM
                f_ttb_blm_bbmkn('$username', '%%')"
        , FALSE);
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
