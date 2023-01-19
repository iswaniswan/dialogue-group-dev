<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacasupplier(){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_supplier
            ORDER BY i_supplier
        ", FALSE);
    }

    public function data($tahun,$bulan,$isupplier, $folder){
        $iperiode   = $tahun.$bulan;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT b.i_product, b.e_product_name,
                            b.n_order AS pcsop, (b.n_order*b.v_product_mill) AS rpop,
                            CASE WHEN b.n_delivery ISNULL THEN 0 ELSE b.n_delivery END AS pcsdo,
                            (b.n_delivery*b.v_product_mill) AS rpdo,
                            CASE WHEN b.n_delivery ISNULL THEN b.n_order
                            ELSE b.n_order-b.n_delivery
                            END AS pending,
                            b.n_order AS totalpcs,
                            '$folder' AS folder
                            FROM
                            tm_op a
                            LEFT JOIN tm_op_item b ON (a.i_op = b.i_op)
                            INNER JOIN tr_supplier c ON (a.i_supplier = c.i_supplier)
                            WHERE
                            to_char(a.d_op::timestamp with time zone, 'yyyymm'::text)= '$iperiode'
                            AND a.i_supplier = '$isupplier' AND a.f_op_cancel = 'f'
                            ORDER BY a.i_op ", FALSE);

        $datatables->edit('rpop', function ($data) {
            return number_format($data['rpop']);
        });
        $datatables->edit('rpdo', function ($data) {
            return number_format($data['rpdo']);
        });
        $datatables->edit('totalpcs', function ($data) {
            if($data['pcsdo']!=0 && $data['pcsop']!=0){
                return number_format($data['pcsdo']/$data['pcsop']*100)."%";
            }else{
                return 0;
            }
        });
        
        $datatables->hide('folder');

        return $datatables->generate();
    }
}

/* End of file Mmaster.php */