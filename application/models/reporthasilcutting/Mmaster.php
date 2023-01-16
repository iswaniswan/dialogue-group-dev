<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data ($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "b.d_bonk BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                0 AS no,
                a.i_product,
                a.e_product_name,
                sum(a.n_quantity_product) AS quantity_product,
                a.i_material,
                ma.e_material_name,
                e_satuan,
                sum(a.n_quantity_material) AS quantity_material
            FROM
                tm_bonkeluar_cutting_item a
            JOIN tm_bonkeluar_cutting b ON
                a.i_bonk = b.i_bonk
            JOIN tr_material ma ON
                a.i_material = ma.i_material
            JOIN tr_satuan c ON
                (c.i_satuan_code = ma.i_satuan_code)
            WHERE
                /*b.i_status = '6'*/
                $where
            GROUP BY
                a.i_product,
                a.e_product_name,
                a.i_material,
                ma.e_material_name,
                e_satuan
            ",
            FALSE
        );
        return $datatables->generate();
    }

    public function getCutting($dfrom, $dto){
        //header("Content-Type: application/json", true);   
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');

        if(isset($dfrom)){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dfrom1 = $year.'-'.$month.'-'.$day;
        }
        if(isset($dto)){
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dto1 = $year.'-'.$month.'-'.$day;
        }
        if(!isset($dfrom))$dfrom=date('Y-m-d');
        if(!isset($dto))$dto=date('Y-m-d');
        $this->db->select("a.i_product, a.e_product_name, sum(a.n_quantity_product) as quantity_product, a.i_material, ma.e_material_name, sum(a.n_quantity_material) as quantity_material 
         from tm_bonkeluar_cutting_item  a
         join tm_bonkeluar_cutting b on a.i_bonk = b.i_bonk
         join tr_material ma on a.i_material = ma.i_material
         where b.d_bonk >= '$dfrom1' 
         and b.d_bonk <= '$dto1' 
         group by a.i_product, a.e_product_name, a.i_material, ma.e_material_name",false);
        $data = $this->db->get();
        return $data;
    }
}
/* End of file Mmaster.php */