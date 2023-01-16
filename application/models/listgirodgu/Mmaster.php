<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    public function bacaperiode($isupplier, $dfrom, $dto, $folder)
    {

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" 
                            SELECT 
                                a.i_giro,
                                a.d_giro,
                                a.i_pv,
                                a.d_pv,
                                tr_supplier.e_supplier_name,
                                a.v_jumlah,
                                a.v_sisa,
                                a.f_giro_batal,
                                a.i_supplier,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$folder' as folder,
                                a.f_posting
                            FROM 
                                tm_giro_dgu a 
                                inner join tr_supplier on (a.i_supplier=tr_supplier.i_supplier)
                            WHERE
                                a.i_supplier='$isupplier'
                                and a.d_giro >= '$dfrom' 
                                and a.d_giro <= '$dto'
                            ORDER BY 
                                a.i_giro ",false);
        $datatables->edit('v_jumlah', function ($data) {
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('v_sisa', function ($data) {
            return number_format($data['v_sisa']);
        });
        
        $datatables->edit('d_giro', function($data){
            return date("d-m-Y", strtotime($data['d_giro']));
        });

        $datatables->edit('d_pv', function($data){
            return date("d-m-Y", strtotime($data['d_pv']));
        });

        $datatables->edit('i_giro',function($data){
            if($data['f_giro_batal'] ==  't'){
                return "<h1>".$data['i_giro']."</h1>";
            }else{
                return $data['i_giro'];
            }
        });

        $datatables->add('action', function ($data) {
            $igiro      = $data['i_giro'];
            $folder     = $data['folder'];
            $ipv        = $data['i_pv'];
            $isupplier  = $data['i_supplier'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $fposting   = $data['f_posting'];
            $fgrbtl     = $data['f_giro_batal'];
            $data       = '';

            if($fposting == 'f'){
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$igiro/$ipv/$isupplier/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }

            if($fgrbtl != 't'){
                $data .= "<a href=\"#\" onclick='cancel(\"$igiro\",\"$ipv\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
            }
            
            return $data;
        });

        $datatables->hide('f_giro_batal');
        $datatables->hide('i_supplier');
        $datatables->hide('f_posting');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('folder');
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */