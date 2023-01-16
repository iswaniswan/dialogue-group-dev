<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data ($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE c.d_schedule BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "
            SELECT
                ROW_NUMBER() OVER(
            ORDER BY
                c.i_schedule) AS i,
                a.i_schedule,
                b.i_spbb,
                a.i_product,
                a.e_product_name,
                a.n_quantity AS qtysched,
                b.n_quantity AS qtyspbb
            FROM
                tm_schedule_item a
            LEFT JOIN tm_spbb_item b ON
                a.i_schedule = b.i_schedule
            INNER JOIN tm_schedule c ON
                a.i_schedule = c.i_schedule
            $where
            ",
            FALSE
        );
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
