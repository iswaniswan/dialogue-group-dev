<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*public function interval($dfrom,$dto){
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dfrom=$th."-".$bl."-".$hr;
        }
        if($dto!=''){
            $tmp=explode("-",$dto);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dto=$th."-".$bl."-".$hr;
        }
        $this->db->select("(DATE_PART('year', '$dto'::date) - DATE_PART('year', '$dfrom'::date)) * 12 +
           (DATE_PART('month', '$dto'::date) - DATE_PART('month', '$dfrom'::date)) as inter ",false);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $tmp=$query->row();
            return $tmp->inter+1;
        }
    }
    
    public function bacaperiode($dfrom,$dto,$interval){
        $query = $this->db->query("
            SELECT
                a.kode,
                a.bln,
                sum(a.omset) AS omset,
                sum(a.retur) AS retur
            FROM
                (
                SELECT
                    (a.i_area || '.' || c.i_customer_class || '.' || c.e_customer_classname) AS kode, to_number(to_char(a.d_nota, 'mm'), '99') AS bln, sum(a.v_nota_netto) AS omset, 0 AS retur
                FROM
                    tm_nota a, tr_customer b, tr_customer_class c
                WHERE
                    a.f_nota_cancel = 'f'
                    AND NOT a.i_nota IS NULL
                    AND a.i_customer = b.i_customer
                    AND b.i_customer_class = c.i_customer_class
                    AND (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                    AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy'))
                GROUP BY
                    a.i_area, c.i_customer_class, c.e_customer_classname, to_char(a.d_nota, 'mm')
            UNION ALL
                SELECT
                    (a.i_area || '.' || c.i_customer_class || '.' || c.e_customer_classname) AS kode, to_number(to_char(a.d_kn, 'mm'), '99') AS bln, 0 AS omset, sum(a.v_netto) AS jumlah
                FROM
                    tm_kn a, tr_customer b, tr_customer_class c
                WHERE
                    a.f_kn_cancel = 'f'
                    AND (a.d_kn >= to_date('$dfrom', 'dd-mm-yyyy')
                    AND a.d_kn <= to_date('$dto', 'dd-mm-yyyy'))
                    AND a.i_customer = b.i_customer
                    AND b.i_customer_class = c.i_customer_class
                GROUP BY
                    a.i_area, c.i_customer_class, c.e_customer_classname, to_char(a.d_kn, 'mm') ) AS a
            GROUP BY
                a.kode,
                a.bln
            ORDER BY
                a.kode,
                a.bln
            ", FALSE);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }*/
    
//--------------------------------------------------------------------------
    function bacaperiode($dfrom,$dto,$interval)
    {
      $sql =" a.kode, a.bln, sum(a.omset) as omset, sum(a.retur) as retur 
              from( 
              SELECT (c.i_customer_class||'.'||c.e_customer_classname) as kode, to_number(to_char(a.d_nota, 'mm'), '99') as bln, 
              sum(a.v_nota_netto) AS omset, 0 as retur 
              FROM tm_nota a, tr_customer b, tr_customer_class c
              WHERE a.f_nota_cancel='f' AND NOT a.i_nota IS NULL and a.i_customer=b.i_customer and b.i_customer_class=c.i_customer_class
              AND (a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy') AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy')) 
              GROUP BY c.i_customer_class, c.e_customer_classname, to_char(a.d_nota, 'mm') 
              union all 
              SELECT (c.i_customer_class||'.'||c.e_customer_classname) as kode, to_number(to_char(a.d_kn, 'mm'), '99') as bln, 0 as omset, 
              sum(a.v_netto) AS jumlah 
              FROM tm_kn a, tr_customer b, tr_customer_class c
              WHERE a.f_kn_cancel='f' AND (a.d_kn >= to_date('$dfrom', 'dd-mm-yyyy') AND a.d_kn <= to_date('$dto', 'dd-mm-yyyy'))
              and a.i_customer=b.i_customer and b.i_customer_class=c.i_customer_class
              GROUP BY c.i_customer_class, c.e_customer_classname, to_char(a.d_kn, 'mm') 
              ) as a 
              group by a.kode, a.bln
              order by a.kode, a.bln";
          $this->db->select($sql,false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              return $query->result();
          }
    }
    function interval($dfrom,$dto)
    {
      if($dfrom!=''){
                $tmp=explode("-",$dfrom);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dfrom=$th."-".$bl."-".$hr;
            }
      if($dto!=''){
                $tmp=explode("-",$dto);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dto=$th."-".$bl."-".$hr;
            }
          $this->db->select("(DATE_PART('year', '$dto'::date) - DATE_PART('year', '$dfrom'::date)) * 12 +
                         (DATE_PART('month', '$dto'::date) - DATE_PART('month', '$dfrom'::date)) as inter ",false);
          $query = $this->db->get();
          if($query->num_rows() > 0){
              $tmp=$query->row();
        return $tmp->inter+1;
          }
    }
//--------------------------------------------------------------------------
}

/* End of file Mmaster.php */
