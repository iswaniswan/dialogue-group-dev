<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username,$idcompany){
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

	function data($dfrom, $dto, $area){
		$datatables = new Datatables(new CodeigniterAdapter);
        
        if($area=='NA'){
            $datatables->query("
                        SELECT 
                            a.i_nota,
                            a.d_nota,
                            a.i_customer, 
                            b.e_customer_name, 
                            c.v_nota_netto AS v_notarev,
                            a.v_nota_netto,
                            a.d_nota_update,
                            a.f_nota_cancel,
                            a.e_alasan
                        FROM 
                            tr_customer b, 
                            tm_nota a
                        LEFT JOIN 
                            tm_notakoreksi c ON (a.i_sj=c.i_sj AND a.i_area=c.i_area)
                        WHERE 
                            a.i_customer=b.i_customer 
                            AND NOT a.i_nota isnull
                            AND (a.f_nota_cancel='t' or a.f_nota_koreksi='t') and 
                            a.d_nota >= to_date('$dfrom','dd-mm-yyyy') AND
                            a.d_nota <= to_date('$dto','dd-mm-yyyy')
                        ORDER BY 
                            a.i_nota"
                        ,false);
        }else{
            $datatables->query("
                        SELECT 
                            a.i_nota,
                            a.d_nota,
                            a.i_customer, 
                            b.e_customer_name, 
                            c.v_nota_netto AS v_notarev,
                            a.v_nota_netto,
                            a.d_nota_update,
                            a.f_nota_cancel,
                            a.e_alasan
                        FROM 
                            tr_customer b, 
                            tm_nota a
                        LEFT JOIN 
                            tm_notakoreksi c ON (a.i_sj=c.i_sj AND a.i_area=c.i_area)
                        WHERE 
                            a.i_customer=b.i_customer 
                            AND NOT a.i_nota isnull
                            AND (a.f_nota_cancel='t' or a.f_nota_koreksi='t') AND 
                            a.i_area = '$area' AND
                            a.d_nota >= to_date('$dfrom','dd-mm-yyyy') AND
                            a.d_nota <= to_date('$dto','dd-mm-yyyy')
                        ORDER BY 
                            a.i_nota"
                        ,false);
        }
        
		

        $datatables->edit('d_nota', function ($data) {
        $d_nota = $data['d_nota'];
            if($d_nota == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_nota) );
            }
        });

        $datatables->edit('i_customer', function ($data) {
            $i_customer = $data['i_customer'];
            $e_customer_name = $data['e_customer_name'];
                return "(".$i_customer.") ".$e_customer_name;
        });

        $datatables->edit('v_nota_netto', function ($data) {
            $v_nota_netto = $data['v_nota_netto'];
                return number_format($v_nota_netto);
        });

        $datatables->edit('v_notarev', function ($data) {
            $v_notarev = $data['v_notarev'];
                return number_format($v_notarev);
        });

        $datatables->edit('d_nota_update', function ($data) {
            $d_nota_update = $data['d_nota_update'];
            $f_nota_cancel = $data['f_nota_cancel'];
            if($f_nota_cancel == 't'){
                return date("d-m-Y",strtotime($d_nota_update));
            }else{
                return '';
            }
        });

        $datatables->hide('e_customer_name');
        $datatables->hide('f_nota_cancel');

        return $datatables->generate();
	}
}

/* End of file Mmaster.php */
