<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function getAll($dfrom,$dto,$iarea){
      if($iarea=='NA'){
        return $this->db->query(" select a.i_nota, b.e_customer_name, a.i_seri_pajak, a.d_pajak, a.v_nota_netto, d.e_customer_pkpname, b.f_customer_pkp,
                            a.v_nota_discount, a.v_nota_discounttotal, a.v_nota_discount1, a.v_nota_discount2, a.v_nota_discount3, 
                            a.v_nota_discount4
                            from tm_nota a, tr_customer b
                            left join tr_customer_pkp d on(b.i_customer=d.i_customer) 
                            where (a.d_pajak >= to_date('$dfrom', 'dd-mm-yyyy') 
                            and a.d_pajak <= to_date('$dto', 'dd-mm-yyyy')) and a.i_customer=b.i_customer
                            and not a.i_faktur_komersial isnull 
                            and not a.i_seri_pajak isnull and a.f_nota_cancel='f'
                            order by a.i_area, a.d_nota, a.i_nota",false);
      }else{
        return $this->db->query(" select a.i_nota, b.e_customer_name, a.i_seri_pajak, a.d_pajak, a.v_nota_netto, d.e_customer_pkpname, b.f_customer_pkp,
                            a.v_nota_discount, a.v_nota_discounttotal, a.v_nota_discount1, a.v_nota_discount2, a.v_nota_discount3, 
                            a.v_nota_discount4
                            from tm_nota a, tr_customer b
                            left join tr_customer_pkp d on(b.i_customer=d.i_customer) 
                            where (a.d_pajak >= to_date('$dfrom', 'dd-mm-yyyy') 
                            and a.d_pajak <= to_date('$dto', 'dd-mm-yyyy')) and a.i_customer=b.i_customer
                            and not a.i_faktur_komersial isnull 
                            and not a.i_seri_pajak isnull and a.f_nota_cancel='f' and a.i_area='$iarea'
                            order by a.i_area, a.d_nota, a.i_nota",false);
      }
    }
}

/* End of file Mmaster.php */