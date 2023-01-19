<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function cekarea($username, $idcompany)
    {
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return '00';
        } else {
            return 'xx';
        }
    }

    public function bacaperiode($isupplier, $dfrom, $dto)
    {
        $where = '';
        if ($isupplier != 'AS') {
            $where .= "and a.i_supplier='$isupplier'";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select i_dtap, d_dtap, d_due_date, i_supplier||' - '||e_supplier_name as i_supplier, v_netto, v_sisa from (
            SELECT a.i_supplier, a.f_dtap_cancel, a.i_dtap, a.d_dtap, a.d_due_date, c.e_supplier_name, a.v_netto, a.v_sisa, to_char(a.d_dtap, 'YYMM')  as d_nota
            from tr_supplier c, tm_dtap a
            where a.i_supplier=c.i_supplier
            and a.d_dtap >= to_date('$dfrom', 'dd-mm-yyyy') AND a.d_dtap <= to_date('$dto', 'dd-mm-yyyy')
            and a.f_dtap_cancel = 'f' $where and v_sisa>0
            and to_char(a.d_dtap, 'YYMM') = a.n_dtap_year::text
            union all
            SELECT a.i_supplier, 'f' as f_dtap_cancel, b.i_nota as i_dtap, z.d_dtap as d_dtap, z.d_due_date, c.e_supplier_name, b.v_jumlah as v_netto, b.v_sisa, to_char(b.d_nota, 'YYMM') as d_nota
            from tr_supplier c, tm_alokasi_bk a, tm_alokasi_bk_item b, tm_dtap z
            where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
            and a.d_alokasi >= to_date('$dfrom', 'dd-mm-yyyy')
            and a.f_alokasi_cancel = 'f' $where
            and b.i_nota = z.i_dtap
            and b.i_supplier = z.i_supplier
            and to_char(b.d_nota, 'YYMM') = z.n_dtap_year::text
            union all
            SELECT a.i_supplier, 'f' as f_dtap_cancel, b.i_nota as i_dtap, z.d_dtap as d_dtap, z.d_due_date, c.e_supplier_name, b.v_jumlah as v_netto, b.v_sisa, to_char(b.d_nota, 'YYMM') as d_nota
            from tr_supplier c, tm_alokasi_kb a, tm_alokasi_kb_item b, tm_dtap z
            where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
            and a.d_alokasi >= to_date('$dfrom', 'dd-mm-yyyy')
            and a.f_alokasi_cancel = 'f' $where
            and b.i_nota = z.i_dtap
            and b.i_supplier = z.i_supplier
            and to_char(b.d_nota, 'YYMM') = z.n_dtap_year::text
            ) as a

            where a.i_dtap||a.d_dtap||a.i_supplier||a.d_nota not in(
            SELECT b.i_nota||b.d_nota||a.i_supplier||to_char(b.d_nota, 'YYMM') as d_nota
             from tr_supplier c, tm_alokasi_bk a, tm_alokasi_bk_item b
             where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
             and a.d_alokasi >= to_date('$dfrom', 'dd-mm-yyyy') and a.d_alokasi <= to_date('$dto', 'dd-mm-yyyy')
             and a.f_alokasi_cancel = 'f' $where
             union all
             SELECT b.i_nota||b.d_nota||a.i_supplier||to_char(b.d_nota, 'YYMM') as d_nota
             from tr_supplier c, tm_alokasi_kb a, tm_alokasi_kb_item b
             where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
             and a.d_alokasi >= to_date('$dfrom', 'dd-mm-yyyy') and a.d_alokasi <= to_date('$dto', 'dd-mm-yyyy')
             and a.f_alokasi_cancel = 'f' $where
            )

            ORDER BY a.i_supplier, a.i_dtap");

        $datatables->edit('v_netto', function ($data) {
            return number_format($data['v_netto']);
        });

        $datatables->edit('v_sisa', function ($data) {
            return number_format($data['v_sisa']);
        });
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */