<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function getAll($no){
      return $this->db->query(" select a.i_nota, a.d_nota, a.i_customer, b.e_customer_name, a.i_salesman, a.i_faktur_komersial, 
                                a.i_seri_pajak, a.d_pajak, a.v_nota_gross, a.v_nota_discounttotal, a.v_nota_ppn, a.v_nota_gross, a.v_nota_netto,
                                a.f_pajak_pengganti, a.i_area, a.d_pajak_print, n_pajak_print, b.f_customer_pkp,
                                d.e_customer_pkpname, d.e_customer_pkpaddress, d.e_customer_pkpnpwp, c.f_spb_pkp
                                from tm_nota a, tm_spb c, tr_customer b
                                left join tr_customer_pkp d on(b.i_customer=d.i_customer) 
                                where a.i_nota like '$no' and a.i_customer=b.i_customer and a.i_spb=c.i_spb and a.i_area=c.i_area
                                and not a.i_faktur_komersial isnull and not a.i_seri_pajak isnull and a.f_nota_cancel='f'
                                order by a.i_area, a.d_nota, a.i_nota",false);
    }
}

/* End of file Mmaster.php */