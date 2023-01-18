<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT (b.i_store),
                b.e_store_name,
                c.i_store_location,
                c.e_store_locationname
            FROM
                tr_area a,
                tr_store b,
                tr_store_location c
            WHERE
                a.i_store = b.i_store
                AND b.i_store = c.i_store
                AND b.i_store = 'AA'
                AND i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = 'admin'
                    AND id_company = '8')
            ORDER BY
                b.i_store,
                c.i_store_location
        ", FALSE)->result();
    }

    public function bacagroup(){
      $this->db->select("i_product_group, e_product_groupname from tr_product_group", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }

    public function bacastatus(){
      $this->db->select("i_product_status, e_product_statusname from tr_product_status", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }

    public function bacastore($istore){
        return $this->db->query("
          SELECT
            c.i_store_location,
            b.i_store,
            a.i_area
          FROM
            tr_area a,
            tr_store b,
            tr_store_location c
          WHERE
            a.i_store = b.i_store
            AND b.i_store = c.i_store
            AND a.i_store = '$istore'"
        ,false);
    }

    public function baca($istorelocation,$iperiode,$istore,$iproductgroup,$iproductstatus){
      $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='MTS'
                          and i_area='$istore' for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        $this->db->query("update tm_dgu_no 
                          set e_periode='$iperiode'
                          where i_modul='MTS' and i_area='$istore' and i_store_location='$istorelocation'", false);
      }else{
        $this->db->query("insert into tm_dgu_no(i_modul, i_area, e_periode, i_store_location) 
                          values ('MTS','$istore','$iperiode', '$istorelocation')");
      }
      $query->free_result();
      if($iperiode>'201512'){
        $sql="";
        if($iproductgroup=='ALL'){
          $sql .=" *, n_mutasi_git as n_saldo_git from f_mutasi_stock_pusat_saldoakhir('$iperiode') ";
          if($iproductstatus!='ALL'){
            $sql .=" where substring(status,1,1)='$iproductstatus'";
          }
        }else{
          $sql .=" *, n_mutasi_git as n_saldo_git from f_mutasi_stock_pusat_saldoakhir('$iperiode') 
                   where i_product_group='$iproductgroup' ";
          if($iproductstatus!='ALL'){
            $sql .=" and substring(status,1,1)='$iproductstatus'";
          }
          $sql .=" ORDER BY i_product_grade, status, e_product_groupnameshort, e_product_name, i_product ;
";
        }
        $this->db->select($sql,false);
      }else{
        $this->db->select(" *, n_mutasi_git as n_saldo_git from f_mutasi_stock_pusat('$iperiode') ",false);
      }
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
      $query->free_result();
    }

    public function detaila($istorelocation,$iperiode,$iarea,$iproduct,$iproductgrade)
   {
    $this->db->select(" *
                   FROM vmutasidetailv2a a
                   WHERE a.periode='$iperiode' 
                     AND a.area='AA' 
                     AND a.product='$iproduct' 
                    and a.loc='$istorelocation' and a.i_product_grade='A'
                   order by dreff, urut, ireff",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0)
      {
        return $query->result();
      }
    }

    public function detailb($istorelocation,$iperiode,$iarea,$iproduct,$iproductgrade)
   {
    $this->db->select(" *
                   FROM vmutasidetailv2b a
                   WHERE a.periode='$iperiode' 
                     AND a.area='AA' 
                     AND a.product='$iproduct'
                     and a.loc='$istorelocation' and a.i_product_grade='B'
                   order by dreff, urut, ireff",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0)
      {
        return $query->result();
      }
    }

    public function detail($istorelocation,$iperiode,$iarea,$iproduct){
        $query =  $this->db->query("
                                select
                                   b.e_product_name,
                                   a.ireff,
                                   a.dreff,
                                   a.area,
                                   a.periode,
                                   a.product,
                                   e.e_customer_name,
                                   a.urut,
                                   sum(a.in) as in,
                                   sum(a.out) as out,
                                   sum(a.git) as git,
                                   sum(a.gitpenjualan) as gitpenjualan 
                                FROM
                                   tr_product b,
                                   vmutasidetail a 
                                   left join
                                      tm_nota_item c 
                                      on c.i_sj = a.ireff 
                                      and a.product = c.i_product 
                                   left join
                                      tm_spb d 
                                      on d.i_sj = c.i_sj 
                                   left join
                                      tr_customer e 
                                      on d.i_customer = e.i_customer 
                                WHERE
                                   b.i_product = a.product 
                                   and a.loc = '$istorelocation' 
                                   AND a.periode = '$iperiode' 
                                   AND a.area = '$iarea' 
                                   AND a.product = '$iproduct' 
                                group by
                                   b.e_product_name,
                                   a.ireff,
                                   a.dreff,
                                   a.area,
                                   a.periode,
                                   a.product,
                                   e.e_customer_name,
                                   a.urut 
                                order by
                                   dreff,
                                   urut,
                                   ireff
                                ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
        /*$query->free_result();*/
    }
}

/* End of file Mmaster.php */
