<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                          select distinct
                            a.i_sjp,
                            c.e_area_name,
                            a.d_sjp,
                            a.d_sjp_receive,
                            c.i_area,
                            '$folder' as folder
                          from
                             tm_sjp a,
                             tm_sjp_item b,
                             tr_area c 
                          where
                             a.i_sjp = b.i_sjp 
                             and a.i_area = b.i_area 
                             and a.i_area = c.i_area 
                             and b.i_area = c.i_area 
                             and 
                             (
                                b.n_quantity_receive is null 
                                or to_char(a.d_sjp::timestamp with time zone, 'yyyymm'::text) < to_char(a.d_sjp_receive::timestamp with time zone, 'yyyymm'::text)
                             )
                          order by
                             a.i_sjp
                            
                          "
                            );
        $datatables->add('action', function ($data) {
            $isjp           = trim($data['i_sjp']);
            $iarea          = trim($data['i_area']);
            $folder         = trim($data['folder']);
            $data           = '';
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjp/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
			return $data;
        });

        $datatables->edit('d_sjp', function ($data) {
            $d_sjp = $data['d_sjp'];
            if($d_sjp == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjp) );
            }
        });

        $datatables->edit('d_sjp_receive', function ($data) {
            $d_sjp_receive = $data['d_sjp_receive'];
            if($d_sjp_receive == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjp_receive) );
            }
        });

        $datatables->hide('folder');
        $datatables->hide('i_area');

        return $datatables->generate();  
    }

    public function baca($isjp,$iarea){
      return $this->db->query("
                              select distinct
                              (c.i_store),
                                 a.*,
                                 b.e_area_name 
                              from
                                 tm_sjp a,
                                 tr_area b,
                                 tm_sjp_item c 
                              where
                                 a.i_area = b.i_area 
                                 and a.i_sjp = c.i_sjp 
                                 and a.i_area = c.i_area 
                                 and a.i_sjp = '$isjp' 
                                 and a.i_area = '$iarea'
                              ",FALSE);
    }

    public function bacadetail($isjp,$iarea){
      return $this->db->query("
                              select
                                 a.i_sjp,
                                 a.d_sjp,
                                 a.i_area,
                                 a.i_product,
                                 a.i_product_grade,
                                 a.i_product_motif,
                                 a.n_quantity_receive,
                                 a.n_quantity_deliver,
                                 a.v_unit_price,
                                 a.e_product_name,
                                 a.i_store,
                                 a.i_store_location,
                                 a.i_store_locationbin,
                                 a.e_remark,
                                 b.e_product_motifname 
                              from
                                 tm_sjp_item a,
                                 tr_product_motif b 
                              where
                                 a.i_sjp = '$isjp' 
                                 and a.i_area = '$iarea' 
                                 and a.i_product = b.i_product 
                                 and a.i_product_motif = b.i_product_motif 
                              order by
                                 a.n_item_no
                              ",FALSE);
    }
}

  /* End of file Mmaster.php */
