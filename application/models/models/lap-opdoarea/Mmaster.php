<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username, $idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE);
    }

    public function data($tahun,$bulan,$iarea){
        $iperiode   = $tahun.$bulan;
        $datatables = new Datatables(new CodeigniterAdapter);
        if($iarea == 'NA'){
            $datatables->query("
                        SELECT 
                            a.i_product, 
                            a.e_product_name,
                            SUM(b.n_order) AS pcsop, 
                            SUM(b.n_order*b.v_product_mill) AS rpop,
                            SUM(a.n_deliver) AS pcsdo, 
                            SUM(a.n_deliver*a.v_product_mill) AS rpdo,
                            b.n_order AS totalpcs,
                            a.n_deliver AS totalrp,
                            d.e_supplier_name
                        FROM 
                            tm_op_item b
                        LEFT JOIN 
                            tm_do c ON (b.i_op=c.i_op)
                        LEFT JOIN 
                            tm_do_item a ON (a.i_product=b.i_product and c.i_do=a.i_do and a.n_deliver>0)
                        INNER JOIN 
                            tr_supplier d ON (a.i_supplier=d.i_supplier)
                        WHERE 
                            to_char(a.d_do::timestamp with time zone, 'yyyymm'::text)='$iperiode'
                        GROUP BY 
                            a.i_product, 
                            a.e_product_name, 
                            c.i_supplier,
                            b.n_order,
                            a.n_deliver,
                            d.e_supplier_name
                        ORDER BY 
                            a.i_product"
                        , FALSE);
        }else{
            $datatables->query("
                        SELECT 
                            a.i_product, 
                            a.e_product_name,
                            SUM(b.n_order) AS pcsop, 
                            SUM(b.n_order*b.v_product_mill) AS rpop,
                            SUM(a.n_deliver) AS pcsdo, 
                            SUM(a.n_deliver*a.v_product_mill) AS rpdo,
                            b.n_order AS totalpcs,
                            a.n_deliver AS totalrp,
                            d.e_supplier_name
                        FROM 
                            tm_op_item b
                        LEFT JOIN 
                            tm_do c ON (b.i_op=c.i_op)
                        LEFT JOIN 
                            tm_do_item a ON (a.i_product=b.i_product and c.i_do=a.i_do and a.n_deliver>0)
                        INNER JOIN 
                            tr_supplier d ON (a.i_supplier=d.i_supplier)
                        WHERE 
                            to_char(a.d_do::timestamp with time zone, 'yyyymm'::text)='$iperiode'
                            AND c.i_area='$iarea'
                        GROUP BY 
                            a.i_product, 
                            a.e_product_name, 
                            c.i_supplier,
                            b.n_order,
                            a.n_deliver,
                            d.e_supplier_name
                        ORDER BY 
                            a.i_product"
                        , FALSE);
        }

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
        $datatables->edit('totalrp', function ($data) {
            if($data['rpdo']!=0 && $data['rpop']!=0){
                return number_format($data['rpdo']/$data['rpop']*100)."%";
            }else{
                return 0;
            }
            
        });
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */