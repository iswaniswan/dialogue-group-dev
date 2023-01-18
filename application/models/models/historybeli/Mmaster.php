<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($isupplier, $iproduct, $folder, $title){
        $this->load->library('fungsi');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        select
                            row_number() over() as no, 
                            a.i_dtap,
                            a.d_dtap,
                            a.i_pajak,
                            a.d_pajak,
                            c.i_product,
                            c.e_product_name,
                            c.n_jumlah,
                            c.v_netto,
                            a.i_supplier,
                            b.e_supplier_name,
                            '$folder' as folder,
                            '$title' as title
                        from
                            tm_dtap a,
                            tr_supplier b,
                            tm_dtap_item c 
                        where
                            a.i_supplier = b.i_supplier 
                            and a.i_dtap = c.i_dtap 
                            and a.i_area = c.i_area 
                            and a.f_dtap_cancel = 'f' 
                            and a.i_supplier = '$isupplier' 
                            and upper(c.i_product) like '%$iproduct%' 
                        ORDER BY
                            a.d_dtap desc,
                            a.i_dtap
                        ",false);
        
        
        $datatables->edit('n_jumlah', function($data){
            return number_format($data['n_jumlah']);
        });

        $datatables->edit('v_netto', function($data){
            return number_format($data['v_netto']);
        });

        $datatables->edit('d_dtap', function($data){
            return date("d-m-Y", strtotime($data['d_dtap']));
        });

        $datatables->edit('d_pajak', function($data){
            return date("d-m-Y", strtotime($data['d_pajak']));
        }); 

        $datatables->add('action', function ($data) {
            $idtap      = $data['i_dtap'];
            $folder     = $data['folder'];
            $title      = $data['title'];
            $data       = '';
            //$data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$inota\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('i_supplier');
        $datatables->hide('e_supplier_name');
        return $datatables->generate();
    }

    function bacasupplier($isupplier){
        return $this->db->query("select i_supplier, e_supplier_name from tr_supplier where i_supplier='$isupplier'");
    }
}

/* End of file Mmaster.php */
