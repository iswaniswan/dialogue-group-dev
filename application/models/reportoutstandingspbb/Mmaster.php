<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($dfrom,$dto){
        $dfrom = date('Y-m-d', strtotime($dfrom));
        $dto   = date('Y-m-d', strtotime($dto));

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" 
                            select
                                ROW_NUMBER() OVER(ORDER BY x.i_spbb) as i,
                               x.i_spbb,
                               x.d_spbb,
                               x.i_product,
                               x.e_product_name,
                               x.i_bonk,
                               x.e_color_name,
                               x.qtyspbb,
                               x.qtybonk,
                               sum(x.qtyspbb - x.qtybonk) as sisa 
                            from
                               (
                                  SELECT
                                     a.i_spbb,
                                     to_char(c.d_spbb, 'dd-mm-yyyy') as d_spbb,
                                     a.i_product,
                                     a.e_product_name,
                                     case
                                        when
                                           STRING_AGG (d.i_bonk, ' , ' ORDER BY d.i_bonk) isnull 
                                        then
                                           '-' 
                                        else
                                           STRING_AGG (d.i_bonk, ' , ' ORDER BY d.i_bonk) 
                                     end
                                     as i_bonk, b.e_color_name, a.n_quantity as qtyspbb, 
                                     case
                                        when
                                           sum(d.n_quantity) isnull 
                                        then
                                           '0' 
                                        else
                                           sum(d.n_quantity) 
                                     end
                                     as qtybonk 
                                  from
                                     tm_spbb_item a 
                                     inner join
                                        tr_color b 
                                        on a.i_color = b.i_color 
                                     inner join
                                        tm_spbb c 
                                        on a.i_spbb = c.i_spbb 
                                     left join
                                        tm_bonmkeluar_cutting_item d 
                                        on a.i_spbb = d.i_spbb 
                                        and b.i_color = d.i_color 
                                        and a.i_product = d.i_product 
                                        and a.i_color = d.i_color 
                                     inner join
                                        tr_material e 
                                        on a.i_material = e.i_material 
                                  where
                                    a.d_spbb >= '$dfrom' 
                                    and a.d_spbb <= '$dto' 
                                  group by
                                     a.i_product, a.e_product_name, a.i_spbb, c.d_spbb, b.e_color_name, d.i_bonk, a.n_quantity 
                               )
                               as x 
                            group by
                               x.i_spbb, x.d_spbb, x.i_product, x.e_product_name, x.e_color_name, x.i_bonk, x.qtyspbb, x.qtybonk 
                            order by
                               x.i_spbb", FALSE);
        return $datatables->generate();
    }

    public function bacaexport($dfrom, $dto){
      $dfrom = date('Y-m-d', strtotime($dfrom));
      $dto   = date('Y-m-d', strtotime($dto));
      return $this->db->query("
                                SELECT
                                   ROW_NUMBER() OVER(ORDER BY a.i_product) as i,
                                   a.i_product,
                                   a.e_product_name,
                                   a.i_spbb,
                                   case
                                      when
                                         STRING_AGG (d.i_bonk, ' , ' ORDER BY d.i_bonk) isnull 
                                      then
                                         '-' 
                                      else
                                         STRING_AGG (d.i_bonk, ' , ' ORDER BY d.i_bonk) 
                                   end
                                   as i_bonk, b.e_color_name, a.n_quantity as qtyspbb, 
                                   case
                                      when
                                         sum(d.n_quantity) isnull 
                                      then
                                         '0' 
                                      else
                                         sum(d.n_quantity) 
                                   end
                                   as qtybonk 
                                from
                                   tm_spbb_item a 
                                   inner join
                                      tr_color b 
                                      on a.i_color = b.i_color 
                                   inner join
                                      tm_spbb c 
                                      on a.i_spbb = c.i_spbb 
                                   left join
                                      tm_bonmkeluar_cutting_item d 
                                      on a.i_spbb = d.i_spbb 
                                      and b.i_color = d.i_color 
                                      and a.i_product = d.i_product 
                                      and a.i_color = d.i_color 
                                   inner join
                                      tr_material e 
                                      on a.i_material = e.i_material 
                                where
                                   a.d_spbb >= '$dfrom' 
                                   and a.d_spbb <= '$dto' 
                                group by
                                   a.i_product, a.e_product_name, a.i_spbb, b.e_color_name, d.i_bonk, a.n_quantity",FALSE)->result();
    }
}
/* End of file Mmaster.php */