<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = '';
        }
        return $iperiode;
    } 

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($tahun,$bulan,$folder){
        $this->load->library('fungsi');
        $iperiode = $tahun.$bulan;
        $tanggal_awal = '01-'.substr($iperiode, 4, 2).'-'.substr($iperiode, 0, 4);
        $periodesebelum = date('Ym', strtotime('-1 month', strtotime($tanggal_awal)));
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("                         
                        select
                           x.i_area,
                           sum(x.saldo_awal) as saldo_awal,
                           sum(x.dpp) as dpp,
                           sum(x.ppn) as ppn,
                           sum(x.penjualan) as penjualan,
                           sum(x.dn) as dn,
                           sum(x.dpp1) as dpp1,
                           sum(x.ppn1) as ppn1,
                           sum(x.kn) as kn,
                           (sum(x.penjualan) - sum(x.kn)) as penjualan_netto,
                           sum(x.alokasi_bm) as alokasi_bm,
                           sum(x.alokasi_knr) as alokasi_knr,
                           sum(x.alokasi_kn) as alokasi_kn,
                           sum(x.alokasi_hll) as alokasi_hll,
                           sum(x.pembulatan) as pembulatan,
                           (
                           (sum(x.saldo_awal) + sum(x.penjualan) + sum(x.dn)) - (sum(x.kn) + sum(x.alokasi_bm) + sum(x.alokasi_knr) + sum(x.alokasi_kn) + sum(x.alokasi_hll) + sum(x.pembulatan) )
                           )
                           as saldo_akhir 
from
(
   select
      z.i_area,
      sum(z.v_sisa) as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from
      (
         SELECT
            x.i_nota,
            x.i_area,
            x.e_area_shortname,
            x.e_area_name,
            x.i_customer,
            x.e_customer_name,
            x.e_customer_address,
            x.e_customer_phone,
            x.e_salesman_name,
            x.n_customer_toplength,
            x.i_sj,
            x.d_nota,
            x.d_jatuh_tempo,
            sum(x.v_sisa) as v_sisa,
            x.v_nota_netto,
            x.d_jatuh_tempo_plustoleransi,
            x.n_toleransi,
            x.e_product_groupname,
            x.e_remark 
         from
            (
               select
                  a.i_nota,
                  a.i_area,
                  b.e_area_shortname,
                  b.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  d.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  a.d_nota,
                  a.d_jatuh_tempo,
                  a.v_sisa,
                  a.v_nota_netto,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, 
                  case
                     when
                        h.f_spb_consigment = 'f' 
                     then
                        i.e_product_groupname 
                     else
                        'Modern Outlet' 
                  end
                  as e_product_groupname, '' as e_remark 
               from
                  tm_nota a 
                  left join
                     tr_area b 
                     on (a.i_area = b.i_area) 
                  left join
                     tr_customer c 
                     on (a.i_customer = c.i_customer 
                     and a.i_area = c.i_area 
                     and b.i_area = c.i_area) 
                  left join
                     tr_salesman d 
                     on (a.i_salesman = d.i_salesman) 
                  left join
                     tr_city g 
                     on (c.i_city = g.i_city 
                     and c.i_area = g.i_area) 
                  left join
                     tm_spb h 
                     on a.i_spb = h.i_spb 
                     and a.i_area = h.i_area 
                  left join
                     tr_product_group i 
                     on h.i_product_group = i.i_product_group 
               where
                  to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and a.f_nota_cancel = 'f' 
                  and a.v_sisa > 0 
                  and not a.i_nota isnull 
               union all
               /*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
               select
                  j.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  j.d_nota,
                  a.d_jatuh_tempo,
                  j.v_jumlah,
                  j.v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, i.e_product_groupname, j.e_remark 
               from
                  tm_alokasi_item j, 
                  tm_alokasi k, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(k.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and not a.i_nota isnull 
                  and j.i_alokasi = k.i_alokasi 
                  and j.i_kbank = k.i_kbank 
                  and j.i_area = k.i_area 
                  and k.f_alokasi_cancel = 'f' 
                  and a.i_nota = j.i_nota 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 'f' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  l.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  l.d_nota,
                  a.d_jatuh_tempo,
                  l.v_jumlah,
                  (
                     l.v_sisa + l.v_jumlah
                  )
                  as v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, i.e_product_groupname, l.e_remark 
               from
                  tm_alokasikn_item l, 
                  tm_alokasikn m, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(m.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and not a.i_nota isnull 
                  and a.i_nota = l.i_nota 
                  and l.i_alokasi = m.i_alokasi 
                  and l.i_kn = m.i_kn 
                  and l.i_area = m.i_area 
                  and m.f_alokasi_cancel = 'f' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 'f' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  n.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  n.d_nota,
                  a.d_jatuh_tempo,
                  n.v_jumlah,
                  n.v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, i.e_product_groupname, n.e_remark 
               from
                  tm_alokasiknr_item n, 
                  tm_alokasiknr o, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(o.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and not a.i_nota isnull 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and a.i_nota = n.i_nota 
                  and n.i_alokasi = o.i_alokasi 
                  and n.i_kn = o.i_kn 
                  and n.i_area = o.i_area 
                  and o.f_alokasi_cancel = 'f' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 'f' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  p.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  p.d_nota,
                  a.d_jatuh_tempo,
                  p.v_jumlah,
                  p.v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, i.e_product_groupname, p.e_remark 
               from
                  tm_alokasihl_item p, 
                  tm_alokasihl q, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(q.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and not a.i_nota isnull 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and a.i_nota = p.i_nota 
                  and p.i_alokasi = q.i_alokasi 
                  and p.i_area = q.i_area 
                  and q.f_alokasi_cancel = 'f' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 'f' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  a.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  p.d_refference as d_nota,
                  a.d_jatuh_tempo,
                  p.v_mutasi_debet as v_jumlah,
                  p.v_mutasi_debet as v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, i.e_product_groupname, p.e_description as e_remark 
               from
                  tm_general_ledger p, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(p.d_mutasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and not a.i_nota isnull 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and a.i_nota = substring(p.i_refference, 15, 15) 
                  and p.f_debet = 't' 
                  and p.i_coa = '610-2902' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 'f' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 							/*--------------------------------------Konsinyasi-----------------------------------------------------------------------------------------------------*/
               union all
               select
                  j.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  j.d_nota,
                  a.d_jatuh_tempo,
                  j.v_jumlah,
                  j.v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, 'Modern Outlet' as e_product_groupname, j.e_remark 
               from
                  tm_alokasi_item j, 
                  tm_alokasi k, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(k.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and not a.i_nota isnull 
                  and j.i_alokasi = k.i_alokasi 
                  and j.i_kbank = k.i_kbank 
                  and j.i_area = k.i_area 
                  and k.f_alokasi_cancel = 'f' 
                  and a.i_nota = j.i_nota 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 't' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  l.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  l.d_nota,
                  a.d_jatuh_tempo,
                  l.v_jumlah,
                  (
                     l.v_sisa + l.v_jumlah
                  )
                  as v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, 'Modern Outlet' as e_product_groupname, l.e_remark 
               from
                  tm_alokasikn_item l, 
                  tm_alokasikn m, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(m.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and not a.i_nota isnull 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and a.i_nota = l.i_nota 
                  and l.i_alokasi = m.i_alokasi 
                  and l.i_kn = m.i_kn 
                  and l.i_area = m.i_area 
                  and m.f_alokasi_cancel = 'f' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 't' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  n.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  n.d_nota,
                  a.d_jatuh_tempo,
                  n.v_jumlah,
                  n.v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, 'Modern Outlet' as e_product_groupname, n.e_remark 
               from
                  tm_alokasiknr_item n, 
                  tm_alokasiknr o, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(o.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and not a.i_nota isnull 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and a.i_nota = n.i_nota 
                  and n.i_alokasi = o.i_alokasi 
                  and n.i_kn = o.i_kn 
                  and n.i_area = o.i_area 
                  and o.f_alokasi_cancel = 'f' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 't' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  p.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  p.d_nota,
                  a.d_jatuh_tempo,
                  p.v_jumlah,
                  p.v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, 'Modern Outlet' as e_product_groupname, p.e_remark 
               from
                  tm_alokasihl_item p, 
                  tm_alokasihl q, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(q.d_alokasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and not a.i_nota isnull 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and a.i_nota = p.i_nota 
                  and p.i_alokasi = q.i_alokasi 
                  and p.i_area = q.i_area 
                  and q.f_alokasi_cancel = 'f' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 't' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
               union all
               select
                  a.i_nota,
                  a.i_area,
                  area.e_area_shortname,
                  area.e_area_name,
                  a.i_customer,
                  c.e_customer_name,
                  c.e_customer_address,
                  c.e_customer_phone,
                  b.e_salesman_name,
                  c.n_customer_toplength,
                  a.i_sj,
                  p.d_refference as d_nota,
                  a.d_jatuh_tempo,
                  p.v_mutasi_debet as v_jumlah,
                  p.v_mutasi_debet as v_sisa,
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat 
                     else
                        a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang 
                  end
                  as d_jatuh_tempo_plustoleransi, 
                  case
                     when
                        substring(a.i_sj, 9, 2) = '00' 
                     then
                        g.n_toleransi_pusat 
                     else
                        g.n_toleransi_cabang 
                  end
                  as n_toleransi, i.e_product_groupname, p.e_description as e_remark 
               from
                  tm_general_ledger p, 
                  tr_customer c, 
                  tr_city g, 
                  tr_salesman b, 
                  tm_spb h, 
                  tr_product_group i, 
                  tr_area area, 
                  tm_nota a 
               where
                  to_char(p.d_mutasi, 'yyyymm') > '$periodesebelum' 
                  and to_char(a.d_nota, 'yyyymm') <= '$periodesebelum' 
                  and not a.i_nota isnull 
                  and a.i_salesman = b.i_salesman 
                  and a.f_nota_cancel = 'f' 
                  and a.i_nota = substring(p.i_refference, 15, 15) 
                  and p.f_debet = 't' 
                  and p.i_coa = '610-2902' 
                  and c.i_city = g.i_city 
                  and c.i_area = g.i_area 
                  and a.i_salesman = b.i_salesman 
                  and h.i_product_group = i.i_product_group 
                  and a.i_spb = h.i_spb 
                  and a.i_area = h.i_area 
                  and h.f_spb_consigment = 't' 
                  and area.i_area = a.i_area 
                  and a.i_customer = c.i_customer 
            )
            as x 
         group by
            x.i_nota, x.i_area, x.e_area_shortname, x.e_area_name, x.i_customer, x.e_customer_name, x.e_salesman_name, x.n_customer_toplength, x.i_sj, x.d_nota, x.d_jatuh_tempo, x.d_jatuh_tempo_plustoleransi, x.n_toleransi, x.e_product_groupname, x.e_remark, x.v_nota_netto, x.e_customer_address, x.e_customer_phone 
         order by
            x.i_area asc, x.i_nota asc, x.i_customer asc 
      )
      as z 
   group by
      z.i_customer, z.e_customer_name, z.i_area 
   union all
   /* Penjualan DPP, PPN*/
   select
      a.i_area,
      0 as saldo_awal,
      sum(a.v_nota_netto)/1.1 as dpp,
      sum(a.v_nota_netto)/11 as ppn,
      sum(a.v_nota_netto) as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from
      tm_nota a,
      tr_customer b 
   where
      a.i_customer = b.i_customer 
      and to_char(a.d_nota, 'yyyymm') = '$iperiode' 
      and a.f_nota_cancel = 'f' 
   group by
      a.i_area 
   union all
   /* Penjualan DPP */
   select
      a.i_area,
      0 as saldo_awal,
      sum(a.v_nota_netto) as dpp,
      0 as ppn,
      sum(a.v_nota_netto) as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from
      tm_nota a,
      tr_customer b 
   where
      a.i_customer = b.i_customer 
      and to_char(a.d_nota, 'yyyymm') = '$iperiode' 
      and a.f_nota_cancel = 'f' 
   group by
      a.i_area 
   /* KN DPP1, PPN1*/
   union all
   select
      a.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
 sum(v_netto)/1.1 as dpp1, 
 sum(v_netto)/11 as ppn1, 
      sum(v_netto) as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from 
    tm_kn a, 
    tr_customer_groupar b, 
    tr_customer c
   where 
    upper(substring(i_kn,1,1))='K' and 
    f_kn_cancel='f' and 
    a.i_customer=b.i_customer and 
    b.i_customer=c.i_customer and 
    to_char(a.d_kn,'yyyymm')='$iperiode' and 
    c.f_customer_pkp = 't'
   group by
      a.i_area 
   union all
   /*KN DPP1*/
   select
      a.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
      sum(v_netto) as dpp1, 
 0 as ppn1, 
      sum(v_netto) as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from 
    tm_kn a, 
    tr_customer_groupar b, 
    tr_customer c
   where 
    upper(substring(i_kn,1,1))='K' and 
    f_kn_cancel='f' and 
    a.i_customer=b.i_customer and 
    b.i_customer=c.i_customer and 
    to_char(a.d_kn,'yyyymm')='$iperiode' AND 
    c.f_customer_pkp = 'f' 
   group by
      a.i_area 
   union all
   /* DN */
   select
      a.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as penjualan,
      0 as ppn,
      sum(v_netto) as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from 
    tm_kn a, 
    tr_customer_groupar b
   where 
    upper(substring(i_kn,1,1))='D' and 
    f_kn_cancel='f' and 
    a.i_customer=b.i_customer and 
    to_char(a.d_kn,'yyyymm')='$iperiode'
   group by
      a.i_area 
   union all
   /* Alokasi */
   /* Alokasi BM */
   select
      d.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      a.v_jumlah as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from
      tm_alokasi_item a,
      tm_alokasi b,
      tr_customer c,
      tm_nota d 
   where
      a.i_alokasi = b.i_alokasi 
      and a.i_kbank = b.i_kbank 
      and a.i_area = b.i_area 
      and d.i_customer = c.i_customer 
      and b.f_alokasi_cancel = 'f' 
      and a.i_nota = d.i_nota 
      and to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
   union all
   /* Alokasi KNR */
   select
      d.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      a.v_jumlah as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from
      tm_alokasiknr_item a,
      tm_alokasiknr b,
      tr_customer c,
      tm_nota d 
   where
      a.i_alokasi = b.i_alokasi 
      and a.i_area = b.i_area 
      and a.i_kn = b.i_kn 
      and d.i_customer = c.i_customer 
      and b.f_alokasi_cancel = 'f' 
      and a.i_nota = d.i_nota 
      and to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
   union all
   /* Alokasi KN */
   select
      d.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      a.v_jumlah as alokasi_kn,
      0 as alokasi_hll,
      0 as pembulatan 
   from
      tm_alokasikn_item a,
      tm_alokasikn b,
      tr_customer c,
      tm_nota d 
   where
      a.i_alokasi = b.i_alokasi 
      and a.i_area = b.i_area 
      and a.i_kn = b.i_kn 
      and d.i_customer = c.i_customer 
      and b.f_alokasi_cancel = 'f' 
      and a.i_nota = d.i_nota 
      and to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
   union all
   /* Alokasi HLL */
   select
      d.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      a.v_jumlah as alokasi_hll,
      0 as pembulatan 
   from
      tm_alokasihl_item a,
      tm_alokasihl b,
      tr_customer c,
      tm_nota d 
   where
      a.i_alokasi = b.i_alokasi 
      and a.i_area = b.i_area 
      and d.i_customer = c.i_customer 
      and b.f_alokasi_cancel = 'f' 
      and a.i_nota = d.i_nota 
      and to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
   union all
   /* Pembulatan */
   select
      a.i_area,
      0 as saldo_awal,
      0 as dpp,
      0 as ppn,
      0 as penjualan,
      0 as dn,
      0 as dpp1,
      0 as ppn1,
      0 as kn,
      0 as alokasi_bm,
      0 as alokasi_knr,
      0 as alokasi_kn,
      0 as alokasi_hll,
      p.v_mutasi_debet as pembulatan 
   from
      tm_general_ledger p,
      tr_customer c,
      tr_city g,
      tr_salesman b,
      tm_spb h,
      tr_product_group i,
      tr_area area,
      tm_nota a 
   where
      to_char(p.d_mutasi, 'yyyymm') = '$iperiode' 
      and not a.i_nota isnull 
      and a.i_salesman = b.i_salesman 
      and a.f_nota_cancel = 'f' 
      and a.i_nota = substring(p.i_refference, 15, 15) 
      and p.f_debet = 't' 
      and p.i_coa = '610-2902' 
      and c.i_city = g.i_city 
      and c.i_area = g.i_area 
      and a.i_salesman = b.i_salesman 
      and h.i_product_group = i.i_product_group 
      and a.i_spb = h.i_spb 
      and a.i_area = h.i_area 
      and area.i_area = a.i_area 
      and a.i_customer = c.i_customer 
)
as x 
group by
x.i_area"
                     ,false);
      
        $datatables->edit('penjualan', function($data){
            return number_format($data['penjualan']);
        });

        $datatables->edit('dpp', function($data){
            return number_format($data['dpp']);
        });

         $datatables->edit('ppn', function($data){
          return number_format($data['ppn']);
         });

         $datatables->edit('saldo_awal', function($data){
             return number_format($data['saldo_awal']);
         });
        
         $datatables->edit('saldo_akhir', function($data){
          return number_format($data['saldo_akhir']);
         });

         $datatables->edit('kn', function($data){
            return number_format($data['kn']);
         });
 
         $datatables->edit('dpp1', function($data){
             return number_format($data['dpp1']);
         });

         $datatables->edit('ppn1', function($data){
            return number_format($data['ppn1']);
         });

         $datatables->edit('alokasi_bm', function($data){
            return number_format($data['alokasi_bm']);
        });

         $datatables->edit('penjualan_netto', function($data){
            return number_format($data['penjualan_netto']);
         });

        $datatables->edit('alokasi_knr', function($data){
            return number_format($data['alokasi_knr']);
        });

         $datatables->edit('alokasi_kn', function($data){
          return number_format($data['alokasi_kn']);
         });

         $datatables->edit('alokasi_hll', function($data){
             return number_format($data['alokasi_hll']);
         });
        
         $datatables->edit('pembulatan', function($data){
          return number_format($data['pembulatan']);
         });
         
         $datatables->hide('dn');
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */