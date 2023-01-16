<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    // herdin 24 Maret 2020
    public function bacasupplier(){
        return $this->db->order_by('i_supplier','ASC')->get('tr_supplier')->result();
    }

    public function total($dfrom,$dto) {
      if($dfrom!=''){
				$tmp=explode("-",$dfrom);
        $th=$tmp[2];				
        $bl=$tmp[1];
        $dt=$tmp[0];
        $tgl=$th.'-'.$bl.'-'.$dt;
        $periode = $th.$bl;
			}
      $sql=" select * from crosstab ('
             select a.i_supplier as supplier, b.e_supplier_name as esupplier, 
             to_number(to_char(a.d_nota, ''mm''), ''99'') as bln, sum(a.v_total_net) 
             AS jumlah from tm_notabtb a
             inner join tr_supplier b on a.i_supplier = b.i_supplier
             where (a.d_nota >= to_date(''$dfrom'', ''dd-mm-yyyy'') 
             AND a.d_nota <= to_date(''$dto'', ''dd-mm-yyyy''))
             group by a.i_supplier, b.e_supplier_name, to_char(a.d_nota, ''mm'')','
             select (
             SELECT EXTRACT(MONTH FROM date_trunc(''month'', ''$tgl''::date)::date + s.a * ''1 month''::interval)) 
             from generate_series(0, 11) as s(a)')
             as (supplier text, esupplier text,";
             switch ($bl){
             case '01' :
               $sql.="Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, 
                      Oct numeric, Nov numeric, Des numeric) ";
               break;
             case '02' :
               $sql.="Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, 
                      Nov numeric, Des numeric, Jan numeric) ";
               break;
             case '03' :
               $sql.="Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, 
                      Des numeric, Jan numeric, Feb numeric) ";
               break;
             case '04' :
               $sql.="Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, 
                      Jan numeric, Feb numeric, Mar numeric) ";
               break;
             case '05' :
               $sql.="May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, 
                      Feb numeric, Mar numeric, Apr numeric) ";
               break;
             case '06' :
               $sql.="Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, 
                      Mar numeric, Apr numeric, May numeric) ";
               break;
             case '07' :
               $sql.="Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, 
                      Apr numeric, May numeric, Jun numeric) ";
               break;
             case '08' :
               $sql.="Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, 
                      May numeric, Jun numeric, Jul numeric) ";
               break;
             case '09' :
               $sql.="Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, 
                      Jun numeric, Jul numeric, Aug numeric) ";
               break;
             case '10' :
               $sql.="Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, 
                      Jul numeric, Aug numeric, Sep numeric) ";
               break;
             case '11' :
               $sql.="Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, 
                      Aug numeric, Sep numeric, Oct numeric) ";
               break;
             case '12' :
               $sql.="Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, 
                      Sep numeric, Oct numeric, Nov numeric) ";
               break;
          }
          $sql.=" order by supplier";
          return $this->db->query($sql);
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
      // $query = 
      return  $this->db->get();
		  // if($query->num_rows() > 0){
			//   $tmp=$query->row();
      //   return $tmp->inter+1;
		  // }
    }

    public function data($supplier,$dfrom,$dto){
        // $th = date('y', strtotime($tahun));
        // $thbl = $th.$bulan ;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select ROW_NUMBER() OVER(
            ORDER BY
                final.i_op) AS i, final.i_supplier, final.e_supplier_name, final.i_op, final.d_op,final.qty_op, final.qty_btb , final.qty_op - final.qty_btb as sisa from (
                  select y.i_supplier, y.e_supplier_name, y.i_op, y.d_op, y.qty_op, case when x.qty_btb is null then 0 else x.qty_btb end as qty_btb from (
                    select o.i_supplier,  p.e_supplier_name, spd.i_op, sum (spd.n_qty) as qty_btb from tm_sj_pembelian sp
                    inner join tm_sj_pembelian_detail spd on (sp.i_btb = spd.i_btb)
                    inner join tm_opbb o on  (o.i_supplier = sp.i_supplier and o.i_op = spd.i_op)
                    inner join tr_supplier p on o.i_supplier=p.i_supplier
                    where sp.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f' and f_sj_cancel = 'f'
                    group by o.i_supplier, p.e_supplier_name, spd.i_op 
                    ) as x
                    full join (
                      select o.i_supplier, p.e_supplier_name, o.i_op, o.d_op, sum(n_quantity) as qty_op from tm_opbb_item oi
                      inner join tm_opbb o on (o.i_op = oi.i_op)
                      inner join tr_supplier p on o.i_supplier=p.i_supplier
                      where o.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f'
                      group by o.i_supplier, p.e_supplier_name, o.i_op, o.d_op
                    ) as y
                    on (x.i_supplier = y.i_supplier and y.i_op = x.i_op)
                ) as final
                where final.qty_op - final.qty_btb > 0"
        , FALSE);
        // $datatables->edit('n_deliver', function ($data) {
        //     return number_format($data['n_deliver']);
        // });
        // $datatables->edit('v_unit_price', function ($data) {
        //     return number_format($data['v_unit_price']);
        // });
        // $datatables->edit('total_harga_jual', function ($data) {
        //     return number_format($data['total_harga_jual']);
        // });
        $datatables->hide('i_supplier');
        return $datatables->generate();
    }

    // select ROW_NUMBER() OVER(
    //         ORDER BY
    //             final.i_op) AS i, final.i_supplier, final.e_supplier_name, final.i_op, final.d_op,final.qty_op, final.qty_btb , final.qty_op - final.qty_btb as sisa from (
    //               select y.i_supplier, y.e_supplier_name, y.i_op, y.d_op, y.qty_op, case when x.qty_btb is null then 0 else x.qty_btb end as qty_btb from (
    //                 select o.i_supplier,  p.e_supplier_name, spd.i_op, sum (spd.n_qty) as qty_btb from tm_sj_pembelian sp
    //                 inner join tm_sj_pembelian_detail spd on (sp.i_btb = spd.i_btb)
    //                 inner join tm_opbb o on  (o.i_supplier = sp.i_supplier and o.i_op = spd.i_op)
    //                 inner join tr_supplier p on o.i_supplier=p.i_supplier
    //                 where sp.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f' and f_sj_cancel = 'f'
    //                 group by o.i_supplier, p.e_supplier_name, spd.i_op 
    //                 ) as x
    //                 full join (
    //                   select o.i_supplier, p.e_supplier_name, o.i_op, o.d_op, sum(n_quantity) as qty_op from tm_opbb_item oi
    //                   inner join tm_opbb o on (o.i_op = oi.i_op)
    //                   inner join tr_supplier p on o.i_supplier=p.i_supplier
    //                   where o.i_supplier = '$supplier' and o.d_op between '$dfrom' and '$dto' and o.f_op_cancel = 'f'
    //                   group by o.i_supplier, p.e_supplier_name, o.i_op, o.d_op
    //                 ) as y
    //                 on (x.i_supplier = y.i_supplier and y.i_op = x.i_op)
    //             ) as final
    //             where final.qty_op - final.qty_btb > 0"
}

/* End of file Mmaster.php */
