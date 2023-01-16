<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($dfrom,$dto,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        select
                          distinct d.i_sjp, 
                          a.d_sjp,  
                          a.i_area, 
                          a.d_sjp_receive as terima,
                          a.d_sjp_receive, 
                          b.f_spmb_consigment,
                          c.e_area_name,
                          a.v_sjp, 
                          a.v_sjp_receive,
                          '$dfrom' as dfrom,
                          '$dto' as dto,
                          '$folder' as folder
                        from 
                          tm_sjp a,
                          tm_spmb b, 
                          tr_area c, 
                          tm_sjp_item d
                        where 
                          a.i_spmb=b.i_spmb 
                          and a.i_area=c.i_area 
                          and b.i_area=c.i_area
                          and a.i_sjp=d.i_sjp 
                          and d.n_quantity_deliver<>d.n_quantity_receive
                          and not a.d_sjp_receive is null 
                          and a.f_sjp_cancel='f'
                          and (a.d_sjp >= to_date('$dfrom','dd-mm-yyyy') 
                          and a.d_sjp <= to_date('$dto','dd-mm-yyyy'))
                        order by 
                          a.i_area, 
                          d.i_sjp"
                        );
        
        $datatables->add('action', function ($data) {
            $isjp           = trim($data['i_sjp']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $folder         = trim($data['folder']);
            $data           = '';
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjp/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
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


        $datatables->edit('terima', function ($data) {
          if ($data['terima']!=null) {
              $data = '<span class="label label-info label-rouded">Sudah</span>';
          }else{
              $data = '<span class="label label-danger label-rouded">Belum</span>';
          }
          return $data;
        });

      $datatables->edit('f_spmb_consigment', function ($data) {
        if ($data['f_spmb_consigment']=='t') {
            $data = 'Ya';
        }else{
            $data = 'Tidak';
        }
        return $data;
      });

      $datatables->edit('i_area', function($data){
        return '('.($data['i_area']).')'.($data['e_area_name']);
      });

        $datatables->hide('folder');
        $datatables->hide('v_sjp');
        $datatables->hide('v_sjp_receive');
        $datatables->hide('e_area_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();  
    }

    function baca($isjp){
      return $this->db->query(" 
                        select                  
                          distinct(c.i_store), 
                          a.*, 
                          b.e_area_name 
                        from 
                          tm_sjp a, 
                          tr_area b, 
                          tm_sjp_item c
                        where 
                          a.i_area=b.i_area 
                          and a.i_sjp=c.i_sjp 
                          and a.i_area=c.i_area
                          and a.i_sjp ='$isjp'"
                        , false);
    }

    function bacadetail($isjp){
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
                                  and a.n_quantity_deliver<>a.n_quantity_receive
                                  and a.i_product=b.i_product 
                                  and a.i_product_motif=b.i_product_motif
                                order by 
                                  a.n_item_no"
                                , false);
    }

    public function cekdata($dfrom,$dto){
      $query = $this->db->query("
                        select
                          d.i_sjp, 
                          a.d_sjp, 
                          f_spmb_consigment, 
                          a.i_area, 
                          c.e_area_name, 
                          a.v_sjp, 
                          a.v_sjp_receive, 
                          a.d_sjp_receive,
                          d.i_product, 
                          d.e_product_name, 
                          d.n_quantity_deliver, 
                          d.n_quantity_receive, 
                          d.v_unit_price, 
                          a.i_spmb
                        from 
                          tm_sjp a, 
                          tm_spmb b, 
                          tr_area c, 
                          tm_sjp_item d
                        where 
                          a.i_spmb=b.i_spmb 
                          and a.i_area=c.i_area 
                          and b.i_area=c.i_area
                          and a.i_sjp=d.i_sjp 
                          and d.n_quantity_deliver<>d.n_quantity_receive
                          and not a.d_sjp_receive is null 
                          and a.f_sjp_cancel='f'
                          and (a.d_sjp_receive >= to_date('$dfrom','dd-mm-yyyy') 
                          and a.d_sjp_receive <= to_date('$dto','dd-mm-yyyy'))
                        order by 
                        a.i_area, 
                        d.i_sjp
                      ");
      if($query->num_rows() > 0){
        return $query->result();
      }
    }
}

/* End of file Mmaster.php */
