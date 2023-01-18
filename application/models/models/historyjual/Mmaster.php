<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($icustomer, $iproduct, $folder, $title){
        $this->load->library('fungsi');
        $datatables = new Datatables(new CodeigniterAdapter);
        if($iproduct == 'NULL'){
            $datatables->query("
                SELECT
                    row_number() over() as no,  
                    a.i_salesman,
                    a.i_nota, 
                    a.d_nota, 
                    a.i_seri_pajak, 
                    a.d_pajak, 
                    c.i_product, 
                    c.e_product_name, 
                    c.n_deliver, 
                    c.v_unit_price,
                    '$folder' as folder,
                    '$title' as title
                FROM 
                    tm_nota a, 
                    tr_customer b, 
                    tm_nota_item c
                WHERE 
                    a.i_customer=b.i_customer 
                    and a.i_nota=c.i_nota 
                    and a.i_area=c.i_area
                    and a.f_nota_cancel='f'
                    and not a.i_nota isnull 
                    and a.i_customer='$icustomer' 
                ORDER BY 
                    a.i_salesman, 
                    a.i_nota   
            ",false);
        }else{
            $datatables->query("
            SELECT 
                row_number() over() as no,
                a.i_salesman,
                a.i_nota, 
                a.d_nota, 
                a.i_seri_pajak, 
                a.d_pajak, 
                c.i_product, 
                c.e_product_name, 
                c.n_deliver, 
                c.v_unit_price,
                '$folder' as folder,
                '$title' as title
            FROM 
                tm_nota a, 
                tr_customer b, 
                tm_nota_item c
            WHERE 
                a.i_customer=b.i_customer 
                and a.i_nota=c.i_nota 
                and a.i_area=c.i_area
                and a.f_nota_cancel='f'
                and not a.i_nota isnull 
                and a.i_customer='$icustomer' 
                and upper(c.i_product) like '%$iproduct%'
            ORDER BY 
                a.i_salesman, 
                a.i_nota 
        ",false);
        }
        
        
        $datatables->edit('n_deliver', function($data){
            return number_format($data['n_deliver']);
        });

        $datatables->edit('v_unit_price', function($data){
            return number_format($data['v_unit_price']);
        });

        $datatables->edit('d_nota', function($data){
            return date("d-m-Y", strtotime($data['d_nota']));
        });

        $datatables->edit('d_pajak', function($data){
            return date("d-m-Y", strtotime($data['d_pajak']));
        }); 

        $datatables->add('action', function ($data) {
            $inota      = $data['i_nota'];
            $folder     = $data['folder'];
            $title      = $data['title'];
            $data       = '';
            //$data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$inota\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        return $datatables->generate();
    }

    function bacacustomer($icustomer){
        return $this->db->query("select i_customer, e_customer_name from tr_customer where i_customer='$icustomer'");
    }
}

/* End of file Mmaster.php */
