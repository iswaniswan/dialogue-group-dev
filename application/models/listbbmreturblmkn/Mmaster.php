<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function bacaarea($username, $idcompany){
      $query = $this->db->query("
                           SELECT 
                              i_area 
                           FROM 
                              public.tm_user_area 
                           WHERE 
                              username='$username' and 
                              id_company='$idcompany'
                         ",false);
      if($query->num_rows()>0){
          $ar =  $query->row();
          $area = $ar->i_area;
      }else{
          $area='';
      }
      return $area;
    }

    public function data($area){
        $datatables = new Datatables(new CodeigniterAdapter);
        if($area == '00'){
         $datatables->query("
                           SELECT
                              a.i_bbm,
                              a.d_bbm,
                              a.i_refference_document as i_ttb, 
                              a.d_refference_document as d_ttb,
                              a.i_area,
                              c.i_customer, 
                              b.e_customer_name
                           FROM 
                              tm_bbm a, 
                              tr_customer b, 
                              tm_ttbretur c
                           WHERE 
                              c.i_customer=b.i_customer 
                              and a.i_area=c.i_area
                              and c.i_ttb=a.i_refference_document 
                              and a.f_bbm_cancel='f'
                              and a.i_bbm not in (select i_refference from tm_kn where a.i_bbm=tm_kn.i_refference and a.i_area=tm_kn.i_area)
                           ORDER BY 
                              a.i_area, 
                              a.i_bbm"
                        , FALSE);
        }else{
         $datatables->query("
                           SELECT
                              a.i_bbm,
                              a.d_bbm,
                              a.i_refference_document as i_ttb, 
                              a.d_refference_document as d_ttb,
                              a.i_area,
                              c.i_customer, 
                              b.e_customer_name
                           FROM 
                              tm_bbm a, 
                              tr_customer b, 
                              tm_ttbretur c
                           WHERE 
                              c.i_customer=b.i_customer 
                              and a.i_area=c.i_area
                              and c.i_ttb=a.i_refference_document 
                              and a.f_bbm_cancel='f'
                              and a.i_area='$area'
                              and a.i_bbm not in (select i_refference from tm_kn where a.i_bbm=tm_kn.i_refference and a.i_area=tm_kn.i_area)
                           ORDER BY 
                              a.i_area, 
                              a.i_bbm"
                        , FALSE);
        }
        $datatables->edit('d_bbm', function($data){
         return date("d-m-Y", strtotime($data['d_bbm']));
        });

        $datatables->edit('d_ttb', function($data){
         return date("d-m-Y", strtotime($data['d_ttb']));
        });

        $datatables->edit('i_customer', function($data){
         return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->hide('e_customer_name');

         return $datatables->generate();
   }
}

/* End of file Mmaster.php */
