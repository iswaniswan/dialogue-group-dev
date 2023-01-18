<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom,$dto){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                  SELECT  
                     a.i_customer,
                     b.e_customer_name,
                     x.e_customer_statusname, 
                     a.i_area,
                     z.e_area_name, 
                     d.e_city_name,
                     a.i_spb, 
                     a.d_spb,
                     c.d_approve1, 
                     c.d_approve2, 
                     (c.d_approve1)-(a.d_spb) AS selisihspbapp,
                     a.i_sj, 
                     a.d_sj, 
                     (a.d_sj)-(c.d_approve1) AS selisihsj,
                     a.i_nota, 
                     a.d_nota, 
                     (a.d_nota)-(a.d_sj) AS selisihnota,
                     (a.d_nota)-(a.d_spb) AS selisihspbnota,
                     a.d_sj_receive,
                     (a.d_sj_receive) - (a.d_nota) AS selisihterima, 
                     (a.d_sj_receive) - (a.d_spb) AS selisihterimaspb,
                     a.v_nota_netto, 
                     b.i_customer_status
                  FROM 
                     tm_nota a,  
                     tm_spb c, 
                     tr_customer b
                  LEFT JOIN 
                     tr_customer_status x ON (b.i_customer_status = x.i_customer_status)
                  LEFT JOIN 
                     tr_area z ON (b.i_area  = z.i_area )
                  LEFT JOIN 
                     tr_city d ON (b.i_area  = d.i_area and b.i_city = d.i_city )
                  WHERE 
                     a.i_customer = b.i_customer 
                     AND ((a.d_sj_receive >= to_date('$dfrom','yyyy-mm-dd') 
                     AND a.d_sj_receive <= to_date('$dto','yyyy-mm-dd')))
                     AND a.f_nota_cancel='f'
                     AND not a.i_nota isnull
                     AND a.i_spb=c.i_spb 
                     AND c.f_spb_cancel='f' 
                     AND a.i_area=c.i_area
                  ORDER BY 
                     a.d_spb ASC, 
                     a.i_spb ASC"
                , FALSE);

         $datatables->edit('v_nota_netto', function ($data) {
            return number_format($data['v_nota_netto'],2);
         });
        
         $datatables->edit('d_spb', function ($data) {
            $d_spb = $data['d_spb'];
            if($d_spb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_spb) );
            }
         });
         
         $datatables->edit('d_approve1', function ($data) {
            $d_approve1 = $data['d_approve1'];
            if($d_approve1 == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_approve1) );
            }
         });

         $datatables->edit('d_approve2', function ($data) {
            $d_approve2 = $data['d_approve2'];
            if($d_approve2 == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_approve2) );
            }
         });

         $datatables->edit('d_approve1', function ($data) {
            $d_approve1 = $data['d_approve1'];
            $d_approve2 = $data['d_approve2'];
            if($d_approve1 == '' || $d_approve2 == ''){
               return '';
            }else{
               if($d_approve1 > $d_approve2){
                  return date("d-m-Y", strtotime($d_approve1) );
               }elseif($d_approve2 > $d_approve1){
                  return date("d-m-Y", strtotime($d_approve2) );
               }elseif($d_approve2 == $d_approve1){
                 return date("d-m-Y", strtotime($d_approve2) );
               }else{
                 return date("d-m-Y", strtotime($d_apb) );
               }
            }
         });
        
         $datatables->edit('d_sj', function ($data) {
           $d_sj = $data['d_sj'];
           if($d_sj == ''){
               return '';
           }else{
               return date("d-m-Y", strtotime($d_sj) );
           }
         });
         $datatables->edit('d_nota', function ($data) {
            $d_nota = $data['d_nota'];
            if($d_nota == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_nota) );
            }
         });
         $datatables->edit('d_sj_receive', function ($data) {
            $d_sj_receive = $data['d_sj_receive'];
            if($d_sj_receive == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sj_receive) );
            }
         });

         $datatables->hide('d_approve2');
         $datatables->hide('i_customer_status');

         return $datatables->generate();
   }
}

/* End of file Mmaster.php */
