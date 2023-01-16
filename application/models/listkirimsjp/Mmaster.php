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

    public function data($dfrom,$dto,$iarea,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        if($iarea == 'NA'){
          $datatables->query("
                              select
                                 a.i_area,
                                 a.i_spb,
                                 b.d_spb,
                                 b.i_customer,
                                 d.e_customer_name,
                                 a.i_product,
                                 a.e_product_name,
                                 a.n_order 
                              from
                                 tm_spb_item a,
                                 tm_spb b,
                                 tr_area c,
                                 tr_customer d 
                              where
                                 a.i_spb = b.i_spb 
                                 and a.i_area = b.i_area 
                                 and a.i_area = c.i_area 
                                 and b.i_area = c.i_area 
                                 and b.i_customer = d.i_customer 
                                 and b.f_spb_cancel = 'f' 
                                 and b.i_sj isnull 
                                 and 
                                 (
                                    to_date('$dfrom', 'dd-mm-yyyy') <= b.d_spb 
                                    and to_date('$dto', 'dd-mm-yyyy') >= b.d_spb
                                 )
                              order by
                                 a.i_area asc
                                
                              "
                        );
        }else{
          $datatables->query("
                              select
                                a.i_area,
                                a.i_spb,
                                b.d_spb,
                                b.i_customer,
                                d.e_customer_name,
                                a.i_product,
                                a.e_product_name,
                                a.n_order 
                              from
                                 tm_spb_item a,
                                 tm_spb b,
                                 tr_area c,
                                 tr_customer d 
                              where
                                 a.i_spb = b.i_spb 
                                 and a.i_area = b.i_area 
                                 and a.i_area = c.i_area 
                                 and b.i_area = c.i_area 
                                 and b.i_customer = d.i_customer 
                                 and b.f_spb_cancel = 'f' 
                                 and b.i_sj isnull 
                                 and 
                                 (
                                    to_date('$dfrom', 'dd-mm-yyyy') <= b.d_spb 
                                    and to_date('$dto', 'dd-mm-yyyy') >= b.d_spb
                                 )
                                 and a.i_area = '$iarea' 
                              order by
                                 a.i_area asc
                            "
                        );
        }
        

        $datatables->edit('d_spb', function ($data) {
            $d_spb = $data['d_spb'];
            if($d_spb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_spb) );
            }
        });
        return $datatables->generate();  
      }
}

/* End of file Mmaster.php */
