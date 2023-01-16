<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto){
		$datatables = new Datatables(new CodeigniterAdapter);
        $dfrom=substr($dfrom,6,4).'-'.substr($dfrom,3,2).'-'.substr($dfrom,0,2);
        $dto  =substr($dto,6,4).'-'.substr($dto,3,2).'-'.substr($dto,0,2);
        $iperiode=substr($dto,6,4).substr($dto,3,2);
        $datatables->query("
                            SELECT 
                                data.i_supplier AS supplier, 
                                SUM(data.totalop) AS op, 
                                SUM(data.v_netto) AS netto, 
                                SUM(data.v_discount) AS diskon, 
                                SUM(data.v_gross) AS kotor
                            FROM(
                                SELECT 
                                    a.i_supplier, 
                                    0 AS v_netto, 
                                    0 AS v_gross, 
                                    0 AS totalop, 
                                    0 AS v_discount  
                                FROM 
                                    tr_supplier a
                                UNION ALL
                                SELECT 
                                    a.i_supplier, 
                                    c.v_netto, 
                                    c.v_gross, 
                                    sum(b.v_product_mill*b.n_order) AS totalop, 
                                    c.v_discount 
                                FROM 
                                    tm_op_item b
                                LEFT JOIN 
                                    tm_op a using(i_op)
                                LEFT JOIN 
                                    tm_dtap_item c ON b.i_op=c.i_op
                                WHERE 
                                    a.f_op_cancel = false 
                                    AND a.d_op>='$dfrom' 
                                    AND a.d_op<='$dto'
                                GROUP BY 
                                    a.i_supplier,v_netto, 
                                    b.v_product_mill,b.n_order, 
                                    v_discount, v_gross
                                ) AS data
                            GROUP BY 
                                i_supplier
                            ORDER BY 
                                i_supplier"
                            ,false);

        $datatables->edit('op', function ($data) {
            return number_format($data['op'],2);
        });
        
        $datatables->edit('netto', function ($data) {
            return number_format($data['netto'],2);
        });

        $datatables->edit('diskon', function ($data) {
            return number_format($data['diskon'],2);
        });

        $datatables->edit('kotor', function ($data) {
            return number_format($data['kotor'],2);
        });

        return $datatables->generate();
    }
    
    public function total($dfrom, $dto){
        return $this->db->query("
                    SELECT
                        SUM(total.op) as totop,
                        SUM(total.netto) as totnet,
                        SUM(total.diskon) as totdis,
                        SUM(total.kotor) as totkor
                    FROM(
                        SELECT 
                        SUM(data.totalop) AS op, 
                        SUM(data.v_netto) AS netto, 
                        SUM(data.v_discount) AS diskon, 
                        SUM(data.v_gross) AS kotor
                        FROM(
                        SELECT 
                            a.i_supplier, 
                            0 AS v_netto, 
                            0 AS v_gross, 
                            0 AS totalop, 
                            0 AS v_discount  
                        FROM 
                            dgu.tr_supplier a
                        UNION ALL
                        SELECT 
                            a.i_supplier, 
                            c.v_netto, 
                            c.v_gross, 
                            sum(b.v_product_mill*b.n_order) AS totalop, 
                            c.v_discount 
                        FROM 
                           tm_op_item b
                        LEFT JOIN 
                            tm_op a using(i_op)
                        LEFT JOIN 
                            tm_dtap_item c ON b.i_op=c.i_op
                        WHERE 
                            a.f_op_cancel = false 
                            AND a.d_op>='$dfrom' 
                            AND a.d_op<='$dto'
                        GROUP BY 
                            a.i_supplier,
                            v_netto, 
                            b.v_product_mill,
                            b.n_order, 
                            v_discount,
                            v_gross
                        ) AS data
                    ) AS total"
                    , FALSE);
    }
}

/* End of file Mmaster.php */