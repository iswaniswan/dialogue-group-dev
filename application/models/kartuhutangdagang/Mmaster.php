<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmaster extends CI_Model
{

    public function bacaperiode($periode)
    {
        $tanggal_awal = '01-' . substr($periode, 4, 2) . '-' . substr($periode, 0, 4);
        $tanggal_akhir = date('d-m-Y', strtotime('+1 month', strtotime($tanggal_awal)));
        return $this->db->query("select a.i_supplier, a.e_supplier_name, sum(a.saldo_awal) as v_saldo_awal, sum(a.dpp) as dpp, sum(a.ppn) as ppn, sum(a.debet) as v_debet, sum(a.kredit) as v_kredit, ((sum(a.saldo_awal) + sum(a.debet)) - sum(a.kredit)) as v_saldo_akhir from(
/*Saldo Awal*/
select a.i_supplier, a.e_supplier_name, sum(a.nilai) as saldo_awal, 0 as dpp, 0 as ppn, 0 as debet, 0 as kredit, 0 as saldo_akhir from(
select *, case when (v_sisa - v_netto) < 0 then 0 else v_netto end as nilai from (
SELECT a.i_supplier, a.f_dtap_cancel, a.i_dtap, a.d_dtap, a.d_due_date, c.e_supplier_name, a.v_netto, a.v_sisa, to_char(a.d_dtap, 'YYMM')  as d_nota
from tr_supplier c, tm_dtap a
where a.i_supplier=c.i_supplier
and a.d_dtap >= to_date('01-01-2018', 'dd-mm-yyyy') AND a.d_dtap < to_date('$tanggal_awal', 'dd-mm-yyyy')
and a.f_dtap_cancel = 'f' and v_sisa>0
and to_char(a.d_dtap, 'YYMM') = a.n_dtap_year::text
union all
SELECT a.i_supplier, 'f' as f_dtap_cancel, b.i_nota as i_dtap, b.d_nota as d_dtap, z.d_due_date, c.e_supplier_name, b.v_jumlah as v_netto, b.v_sisa, to_char(b.d_nota, 'YYMM') as d_nota
from tr_supplier c, tm_alokasi_bk a, tm_alokasi_bk_item b, tm_dtap z
where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
and a.d_alokasi >= to_date('01-01-2018', 'dd-mm-yyyy')
and a.f_alokasi_cancel = 'f'
and b.i_nota = z.i_dtap
and b.i_supplier = z.i_supplier
and to_char(b.d_nota, 'YYMM') = z.n_dtap_year::text
union all
SELECT a.i_supplier, 'f' as f_dtap_cancel, b.i_nota as i_dtap, b.d_nota as d_dtap, z.d_due_date, c.e_supplier_name, b.v_jumlah as v_netto, b.v_sisa, to_char(b.d_nota, 'YYMM') as d_nota
from tr_supplier c, tm_alokasi_kb a, tm_alokasi_kb_item b, tm_dtap z
where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
and a.d_alokasi >= to_date('01-01-2018', 'dd-mm-yyyy')
and a.f_alokasi_cancel = 'f'
and b.i_nota = z.i_dtap
and b.i_supplier = z.i_supplier
and to_char(b.d_nota, 'YYMM') = z.n_dtap_year::text
) as a

where a.i_dtap||a.d_dtap||a.i_supplier||a.d_nota not in(
SELECT b.i_nota||b.d_nota||a.i_supplier||to_char(b.d_nota, 'YYMM') as d_nota
from tr_supplier c, tm_alokasi_bk a, tm_alokasi_bk_item b
where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
and a.d_alokasi >= to_date('01-01-2015', 'dd-mm-yyyy') and a.d_alokasi < to_date('$tanggal_awal', 'dd-mm-yyyy')
and a.f_alokasi_cancel = 'f'
union all
SELECT b.i_nota||b.d_nota||a.i_supplier||to_char(b.d_nota, 'YYMM') as d_nota
from tr_supplier c, tm_alokasi_kb a, tm_alokasi_kb_item b
where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
and a.d_alokasi >= to_date('01-01-2015', 'dd-mm-yyyy') and a.d_alokasi < to_date('$tanggal_awal', 'dd-mm-yyyy')
and a.f_alokasi_cancel = 'f'
)
and a.d_dtap < to_date('$tanggal_awal', 'dd-mm-yyyy')
) as a
group by a.i_supplier, a.e_supplier_name
union all
/*DEBET*/
select a.i_supplier, a.e_supplier_name, 0 as saldo_awal, sum(a.dpp) as dpp, sum(a.ppn) as ppn, sum(a.v_netto) as debet, 0 as kredit, 0 as saldo_akhir from(
select a.i_supplier, b.e_supplier_name, case when b.f_supplier_pkp = 't' then (sum(a.v_netto) - sum(a.v_ppn)) else 0 end as dpp, case when b.f_supplier_pkp = 't' then sum(a.v_ppn) else 0 end as ppn, sum(a.v_netto) as v_netto from tm_dtap a, tr_supplier b
where a.i_supplier = b.i_supplier
and a.d_dtap >= to_date('$tanggal_awal', 'dd-mm-yyyy') and a.d_dtap < to_date('$tanggal_akhir', 'dd-mm-yyyy')
and a.f_dtap_cancel = 'f'
group by a.i_supplier, b.e_supplier_name, b.f_supplier_pkp
) as a
group by a.i_supplier, a.e_supplier_name
union all
/*KREDIT*/
select a.i_supplier, a.e_supplier_name, 0 as saldo_awal, 0 as dpp, 0 as ppn, 0 as debet, sum(a.jumlah) as kredit, 0 as saldo_akhir from(
SELECT a.i_supplier, c.e_supplier_name, sum(b.v_jumlah) as jumlah
from tr_supplier c, tm_alokasi_bk a, tm_alokasi_bk_item b
where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
and a.d_alokasi >= to_date('$tanggal_awal', 'dd-mm-yyyy') and a.d_alokasi < to_date('$tanggal_akhir', 'dd-mm-yyyy')
and a.f_alokasi_cancel = 'f'
group by a.i_supplier, c.e_supplier_name
union all
SELECT a.i_supplier, c.e_supplier_name, sum(b.v_jumlah) as jumlah
from tr_supplier c, tm_alokasi_kb a, tm_alokasi_kb_item b
where a.i_alokasi=b.i_alokasi and a.i_supplier=c.i_supplier
and a.d_alokasi >= to_date('$tanggal_awal', 'dd-mm-yyyy') and a.d_alokasi < to_date('$tanggal_akhir', 'dd-mm-yyyy')
and a.f_alokasi_cancel = 'f'
group by a.i_supplier, c.e_supplier_name
) as a
group by a.i_supplier, a.e_supplier_name
) as a
group by a.i_supplier, a.e_supplier_name
", false);
    }
}

/* End of file Mmaster.php */