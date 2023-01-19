<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  public function bacaarea($username,$idcompany){
    return $this->db->query("
                            select distinct
                               on (b.i_store) b.i_store,
                               b.e_store_name,
                               c.i_store_location,
                               c.e_store_locationname,
                               a.i_area 
                            from
                               tr_area a,
                               tr_store b,
                               tr_store_location c 
                            where
                               a.i_store = b.i_store 
                               and b.i_store = c.i_store 
                               and 
                               (
                                  a.i_area in 
                                  (
                                     select
                                        i_area 
                                     from
                                        public.tm_user_area 
                                     where
                                        username = '$username'
                                        and id_company = '$idcompany'
                                  )
                               )
                            order by
                               b.i_store,
                               c.i_store_location
                          ", FALSE)->result();
  }

  public function bacastore($istore){
    return $this->db->query("
                            select
                                c.i_store_location,
                                b.i_store,
                                a.i_area
                            from 
                                tr_area a,
                                tr_store b,
                                tr_store_location c
                            where 
                                a.i_store = b.i_store
                                and b.i_store = c.i_store
                                and a.i_store = '$istore'"
                            ,false);
  }


  public function data($istore,$istorelocation,$folder){
      $username = $this->session->userdata('username');
      $datatables = new Datatables(new CodeigniterAdapter);
      $datatables->query("
                          select
                              ROW_NUMBER() OVER(order by a.i_product desc) as no,
                              b.e_store_name as e_area_name,
                              a.i_store,
                              a.i_product,
                              a.i_product_grade,
                              c.e_product_motifname,
                              a.e_product_name,
                              a.n_quantity_stock
                          from
                              tm_ic a,
                              tr_store b,
                              tr_product_motif c 
                          where
                              a.i_store = b.i_store 
                              and a.i_store = '02' 
                              and a.i_store_location = '00' 
                              and a.i_product_motif = c.i_product_motif 
                              and a.i_product = c.i_product "
                        );
      return $datatables->generate();  
  }
}

  /* End of file Mmaster.php */
