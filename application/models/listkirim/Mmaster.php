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
        $datatables->query("
                        select
                          a.i_sj, 
                          a.d_sj, 
                          b.i_dkb, 
                          b.d_dkb, 
                          d.e_area_name, 
                          c.i_bapb, 
                          c.d_bapb, 
                          a.i_customer, 
                          e.e_customer_name, 
                          g.e_ekspedisi,
                          c.n_bal,
                          '$dfrom' as dfrom,
                          '$dto' as dto,
                          '$iarea' as iarea,
                          '$folder' as folder
                        from 
                          tr_area d, 
                          tr_customer e, 
                          tm_nota a
                          left join tm_dkb b on (a.i_dkb=b.i_dkb)
                          left join tm_bapb c on (a.i_bapb=c.i_bapb)
                          left join tm_dkb_ekspedisi f on (a.i_dkb=f.i_dkb and a.i_area=f.i_area)
                          left join tr_ekspedisi g on(f.i_ekspedisi=g.i_ekspedisi)
                        where 
                          a.i_customer=e.i_customer 
                          and a.i_area=d.i_area 
                          and b.f_dkb_batal='f'
                          and substring(a.i_dkb,10,2)='$iarea' 
                          and a.d_dkb >= to_date('$dfrom','dd-mm-yyyy') 
                          and a.d_dkb <= to_date('$dto','dd-mm-yyyy')
                        order by 
                          a.i_area, 
                          a.i_sj"
                        );

        $datatables->edit('d_sj', function ($data) {
            $d_sj = $data['d_sj'];
            if($d_sj == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sj) );
            }
        });

        $datatables->edit('d_dkb', function ($data) {
          $d_dkb = $data['d_dkb'];
          if($d_dkb == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_dkb) );
          }
        });

        $datatables->edit('d_bapb', function ($data) {
          $d_bapb = $data['d_bapb'];
          if($d_bapb == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_bapb) );
          }
        });

        $datatables->edit('i_customer', function($data){
          return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });


        $datatables->hide('folder');
        $datatables->hide('e_customer_name');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();  
      }
}

/* End of file Mmaster.php */
