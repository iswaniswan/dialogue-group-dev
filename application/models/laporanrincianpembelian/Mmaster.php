<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
      public $idcompany;

      function __construct(){
          parent::__construct();
          $this->idcompany = $this->session->id_company;
      }
    // herdin 24 Maret 2020
    public function bacasupplier(){
        return $this->db->query("SELECT i_supplier, e_supplier_name FROM tr_supplier WHERE id_company='$this->idcompany' ORDER BY e_supplier_name", FALSE)->result();
    }

    public function getesupplier($isupplier){
        $esupplier = '';
        $query = $this->db->query("SELECT e_supplier_name FROM tr_supplier WHERE i_supplier='$isupplier' AND id_company='$this->idcompany'", FALSE);
        foreach($query->result() as $key){
            $esupplier = $key->e_supplier_name;
        }
        return $esupplier;
    }

    public function data($supplier,$dfrom,$dto){

        $tmp = explode('-', $dfrom);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dfrom1 = $th.'-'.$bl.'-'.$hr;

        $tmp = explode('-', $dto);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dto1 = $th.'-'.$bl.'-'.$hr;
        
        $datatables = new Datatables(new CodeigniterAdapter);
        $where = '';
        $where2 = '';
        if ($supplier == 'ALL'){
            $where = '';
            $where2 = '';
        }else{
            $where = " sp.i_supplier = '$supplier' and ";
            $where2 = "o.i_supplier = '$supplier' and ";
        }
        

        $datatables->query("
                        SELECT
                           0 AS i,
                           y.i_material,
                           ma.e_material_name,
                           y.i_op,
                           to_char(y.d_op,'dd-mm-yyyy') as d_op,
                           y.i_supplier || ' - ' || su.e_supplier_name as supplier,
                           case
                              when
                                 STRING_AGG (x.i_btb, ' , '
                        ORDER BY
                           x.i_btb) isnull 
                        then
                           '-' 
                        else
                           STRING_AGG (x.i_btb, ' , '
                        ORDER BY
                           x.i_btb) 
                           end
                           as i_btb, y.n_quantity as qtyop, 
                           case
                              when
                                 sum(x.n_quantity) is not null 
                              then
                                 sum(x.n_quantity) 
                              when
                                 sum(x.n_quantity) isnull 
                              then
                                 '0' 
                           end
                           as qtybtb, 
                           case
                              when
                                 y.f_op_close = 't' 
                              then
                                 '0' 
                              when
                                 y.n_quantity - x.n_quantity isnull 
                              then
                                 '0' 
                              else
                                 y.n_quantity - x.n_quantity 
                           end
                           as qtyos, 
                           case
                              when
                                 y.f_op_close = 'f' 
                              then
                                 '0' 
                              when
                                 y.n_quantity - x.n_quantity isnull 
                              then
                                 '0' 
                              else
                                 y.n_quantity - x.n_quantity 
                           end
                           as qtyhangus 
                        from
                           (
                              select
                                 o.i_supplier,
                                 spd.id_op,
                                 o.i_op,
                                 sp.i_sj_supplier,
                                 sp.d_sj_supplier,
                                 sp.i_btb,
                                 spd.i_material,
                                 spd.n_quantity,
                                 sp.id_company
                              from
                                 tm_btb sp 
                                 inner join
                                    tm_btb_item spd 
                                    on (sp.id = spd.id_btb AND sp.id_company = spd.id_company) 
                                 inner join
                                    tm_opbb o 
                                    on (o.i_supplier = sp.i_supplier 
                                    and o.id = spd.id_op AND o.id_company = sp.id_company AND o.id_company = spd.id_company) 
                              where
                                 $where
                                 o.d_op between '$dfrom1' and '$dto1'
                                 and sp.i_status = '6'
                                 and o.id_company = '$this->idcompany'
                              order by
                                 o.i_op
                           )
                           as x 
                           full join
                              (
                                 select
                                    o.i_supplier,
                                    o.id,
                                    o.i_op,
                                    o.d_op,
                                    oi.i_material,
                                    oi.n_quantity,
                                    s.e_satuan_name,
                                    oi.v_price,
                                    o.f_op_close,
                                    o.id_company
                                 from
                                    tm_opbb_item oi 
                                    inner join
                                       tm_opbb o 
                                       on (o.id = oi.id_op and o.id_company = oi.id_company) 
                                    inner join 
                        		tm_pp_item pi
                        		ON (oi.id_pp = pi.id_pp and oi.i_material = pi.i_material and oi.id_company = pi.id_company)
                        	    inner join 
                        		tm_pp p
                        		ON (oi.id_pp = p.id and pi.id_pp = p.id and oi.id_company = p.id_company and pi.id_company = p.id_company)
                                    inner join
                                       tr_satuan s 
                                       on (s.i_satuan_code = pi.i_satuan_code and s.id_company = pi.id_company) 
                                 where
                        	         $where2 
                        	         o.d_op between '$dfrom1' and '$dto1' 
                                    and o.i_status = '6'
                                    and o.id_company = '$this->idcompany'
                                 order by
                                    o.i_op
                              )
                              as y 
                              on (x.i_supplier = y.i_supplier 
                              and y.id = x.id_op
                              and x.i_material = y.i_material and y.id_company = x.id_company) 
                           inner join
                              tr_supplier su 
                              on (y.i_supplier = su.i_supplier and y.id_company = su.id_company) 
                           inner join
                              tr_material ma 
                              on (y.i_material = ma.i_material 
                              and x.i_material = ma.i_material and y.id_company = ma.id_company and x.id_company = ma.id_company) 
                        group by
                           y.i_material,
                           ma.e_material_name,
                           y.id,
                           y.i_op,
                           y.d_op,
                           x.d_sj_supplier,
                           y.i_supplier,
                           su.e_supplier_name,
                           x.i_btb,
                           y.n_quantity,
                           y.f_op_close,
                           x.n_quantity
                        order by
                           y.i_op
            "
        , FALSE);
        return $datatables->generate();
    }

    public function bacaexport($supplier,$dfrom,$dto){
        $tmp = explode('-', $dfrom);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dfrom1 = $th.'-'.$bl.'-'.$hr;

        $tmp = explode('-', $dto);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dto1 = $th.'-'.$bl.'-'.$hr;

        $where = '';
        $where2 = '';
        if ($supplier == 'ALL'){
            $where = '';
            $where2 = '';
        }else{
            $where = " sp.i_supplier = '$supplier' and ";
            $where2 = "o.i_supplier = '$supplier' and ";
        }
        return $this->db->query("
                                SELECT ROW_NUMBER() OVER(
                                    ORDER BY y.i_op) AS i,
                                    y.i_material,
                                    ma.e_material_name,
                                    y.i_op,
                                    to_char(y.d_op,'dd-mm-yyyy') as d_op,
                                    y.i_supplier || ' - ' || su.e_supplier_name as supplier,
                                    case
                                       when
                                          STRING_AGG (x.i_btb, ' , '
                                 ORDER BY
                                    x.i_btb) isnull 
                                 then
                                    '-' 
                                 else
                                    STRING_AGG (x.i_btb, ' , '
                                 ORDER BY
                                    x.i_btb) 
                                    end
                                    as i_btb, y.n_quantity as qtyop, 
                                    case
                                       when
                                          sum(x.n_quantity) is not null 
                                       then
                                          sum(x.n_quantity) 
                                       when
                                          sum(x.n_quantity) isnull 
                                       then
                                          '0' 
                                    end
                                    as qtybtb, 
                                    case
                                       when
                                          y.f_op_close = 't' 
                                       then
                                          '0' 
                                       when
                                          y.n_quantity - x.n_quantity isnull 
                                       then
                                          '0' 
                                       else
                                          y.n_quantity - x.n_quantity 
                                    end
                                    as qtyos, 
                                    case
                                       when
                                          y.f_op_close = 'f' 
                                       then
                                          '0' 
                                       when
                                          y.n_quantity - x.n_quantity isnull 
                                       then
                                          '0' 
                                       else
                                          y.n_quantity - x.n_quantity 
                                    end
                                    as qtyhangus 
                                 from
                                    (
                                       select
                                          o.i_supplier,
                                          spd.id_op,
                                          o.i_op,
                                          sp.i_sj_supplier,
                                          sp.d_sj_supplier,
                                          sp.i_btb,
                                          spd.i_material,
                                          spd.n_quantity
                                       from
                                          tm_btb sp 
                                          inner join
                                             tm_btb_item spd 
                                             on (sp.id = spd.id_btb) 
                                          inner join
                                             tm_opbb o 
                                             on (o.i_supplier = sp.i_supplier 
                                             and o.id = spd.id_op) 
                                       where
                                          $where
                                          o.d_op between '$dfrom1' and '$dto1'
                                          and o.i_status != '5'
                                          and o.id_company = '$this->idcompany'
                                       order by
                                          o.i_op
                                    )
                                    as x 
                                    full join
                                       (
                                          select
                                             o.i_supplier,
                                             o.id,
                                             o.i_op,
                                             o.d_op,
                                             oi.i_material,
                                             oi.n_quantity,
                                             s.e_satuan_name,
                                             oi.v_price,
                                             o.f_op_close 
                                          from
                                             tm_opbb_item oi 
                                             inner join
                                                tm_opbb o 
                                                on (o.id = oi.id_op) 
                                             inner join 
                                         tm_pp_item pi
                                         ON (oi.id_pp = pi.id_pp and oi.i_material = pi.i_material)
                                         inner join 
                                         tm_pp p
                                         ON (oi.id_pp = p.id and pi.id_pp = p.id)
                                             inner join
                                                tr_satuan s 
                                                on (s.i_satuan_code = pi.i_satuan_code) 
                                          where
                                         $where2 
                                             o.d_op between '$dfrom1' and '$dto1' 
                                             and o.i_status != '5'
                                             and o.id_company = '$this->idcompany'
                                          order by
                                             o.i_op
                                       )
                                       as y 
                                       on (x.i_supplier = y.i_supplier 
                                       and y.id = x.id_op
                                       and x.i_material = y.i_material) 
                                    inner join
                                       tr_supplier su 
                                       on (y.i_supplier = su.i_supplier) 
                                    inner join
                                       tr_material ma 
                                       on (y.i_material = ma.i_material 
                                       and x.i_material = ma.i_material) 
                                 group by
                                    y.i_material,
                                    ma.e_material_name,
                                    y.id,
                                    y.i_op,
                                    y.d_op,
                                    x.d_sj_supplier,
                                    y.i_supplier,
                                    su.e_supplier_name,
                                    x.i_btb,
                                    y.n_quantity,
                                    y.f_op_close,
                                    x.n_quantity
                                 order by
                                    y.i_op",FALSE)->result();
        }


  public function exportdata($isupplier,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_btb BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        if ($isupplier == 'ALL') {
            $supplier = "";            
        }else{
            $supplier = "AND a.i_supplier = '$isupplier'";
        }
        return $this->db->query("
                                  SELECT
                                    to_char(a.d_sj_supplier, 'yyyy-mm-dd') as d_sj_supplier,
                                    a.i_sj_supplier,
                                    h.i_faktur_supplier,
                                    d.i_supplier,
                                    d.e_supplier_name,
                                    e.i_material,
                                    e.e_material_name,
                                    co.i_coa as NoPerkiraan,
                                    co.e_coa_name as NamaPerkiraan,
                                    CASE
                                      WHEN b.f_pkp = 't' THEN 'Pkp' 
                                      ELSE 'Non Pkp' 
                                    END
                                    AS status_pajak, 
                                    f.e_satuan_name, 
                                    d.n_diskon, c.v_price, 
                                    c.n_quantity, 
                                    c.v_price * c.n_quantity - (d.n_diskon * (c.v_price * c.n_quantity) / 100) as total, 
                                    CASE 
                                      WHEN b.f_pkp = 't' THEN (c.v_price * c.n_quantity - (d.n_diskon * (c.v_price * c.n_quantity) / 100)) / 1.1 
                                    ELSE c.v_price * c.n_quantity - (d.n_diskon * (c.v_price * c.n_quantity) / 100) 
                                    END
                                    AS dpp, 
                                    CASE
                                      WHEN b.f_pkp = 't' THEN ((c.v_price * c.n_quantity - (d.n_diskon * (c.v_price * c.n_quantity) / 100)) / 1.1) * 0.1 
                                      ELSE  0 
                                    END
                                    AS ppn 
                                  FROM
                                    tm_btb a 
                                  INNER JOIN
                                      tm_btb_item c 
                                      ON (a.id = c.id_btb) 
                                  INNER JOIN
                                      tm_opbb b 
                                      ON (c.id_op = b.id) 
                                  INNER JOIN
                                      tr_supplier d 
                                      ON (a.id_company = d.id_company 
                                      AND a.i_supplier = d.i_supplier) 
                                  INNER JOIN
                                      tr_material e 
                                      ON (c.i_material = e.i_material 
                                      AND c.id_company = e.id_company) 
                                  LEFT JOIN
                                      tr_kelompok_barang g 
                                      ON (e.i_kode_kelompok = g.i_kode_kelompok 
                                      AND e.id_company = g.id_company) 
                                  LEFT JOIN 
                                      tr_coa co
                                      ON (co.i_coa = g.i_coa)
                                  INNER JOIN
                                      tr_satuan f 
                                      ON (e.i_satuan_code = f.i_satuan_code 
                                      AND e.id_company = f.id_company) 
                                  LEFT JOIN
                                      (
                                        SELECT
                                            id_btb,
                                            h.i_faktur_supplier 
                                        FROM
                                            tm_notabtb_item g 
                                        LEFT JOIN
                                            tm_notabtb h 
                                            ON (h.id = g.id_nota 
                                            AND h.id_company = g.id_company) 
                                        WHERE
                                            h.i_status <> '1' 
                                            AND h.i_status <> '5' 
                                        GROUP BY
                                            id_btb,
                                            h.i_faktur_supplier 
                                      )
                                      AS h 
                                      ON (c.id_btb = h.id_btb) 
                                  WHERE
                                    a.i_status = '6' 
                                  AND a.id_company = '$this->idcompany' 
                                  AND c.n_quantity > 0 
                                  $where $supplier 
                                  ORDER BY
                                    d_sj_supplier,
                                    d.e_supplier_name
                                ", FALSE);

        // left join tm_notabtb_item g on (c.id_btb = g.id_btb and c.id_company = g.id_company and c.i_material = g.i_material)
        //     left join tm_notabtb h on (h.id = g.id_nota and h.id_company = g.id_company and h.i_status <> '1' and h.i_status <> '5')
    }
}
/* End of file Mmaster.php */