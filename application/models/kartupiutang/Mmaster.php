<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area where f_area_real='t' order by i_area
        ", FALSE)->result();
    }

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

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_supplier');
        $this->db->where('username',$username);
        $this->db->where('i_supplier','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $supplier = '00';
        }else{
            $supplier = 'xx';
        }
        return $supplier;
    }

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($tahun, $bulan, $iarea, $folder, $iperiode, $title){
        $this->load->library('fungsi');
        $periode=  $tahun.$bulan;
        $iperiode=  $periode;
		    $tanggal_awal = '01-'.substr($periode, 4, 2).'-'.substr($periode, 0, 4);
		    $periodesebelum =  date('Ym', strtotime('-1 month', strtotime($tanggal_awal)));

        $datatables = new Datatables(new CodeigniterAdapter);
        if($iarea == 'NA'){
			$datatables->query(" select x.i_area, '('||x.i_customer||') '||x.e_customer_name as customer, sum(x.saldo_awal) as saldo_awal, sum(x.penjualan) as penjualan, sum(x.alokasi_bm) as alokasi_bm, sum(x.alokasi_knr) as alokasi_knr, sum(x.alokasi_kn) as alokasi_kn, sum(x.alokasi_hll) as alokasi_hll, sum(x.pembulatan) as pembulatan, 
			((sum(x.saldo_awal) + sum(x.penjualan)) - (sum(x.alokasi_bm) + sum(x.alokasi_knr) + sum(x.alokasi_kn) + sum(x.alokasi_hll) + sum(x.pembulatan) )) as saldo_akhir, '$folder' AS folder, '$tahun' AS tahun,
            '$bulan' AS bulan, '$iperiode' AS iperiode, '$title' AS title from(
			 select z.i_customer, z.e_customer_name, z.i_area, sum(z.v_sisa) as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from(
			 SELECT x.i_nota, x.i_area, x.e_area_shortname, x.e_area_name, x.i_customer, x.e_customer_name, x.e_customer_address, x.e_customer_phone, x.e_salesman_name, x.n_customer_toplength, x.i_sj, x.d_nota, x.d_jatuh_tempo, sum(x.v_sisa) as v_sisa, x.v_nota_netto, x.d_jatuh_tempo_plustoleransi, x.n_toleransi, x.e_product_groupname, x.e_remark from (
			 select a.i_nota, a.i_area, b.e_area_shortname, b.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, d.e_salesman_name, c.n_customer_toplength, a.i_sj, a.d_nota, a.d_jatuh_tempo, a.v_sisa, a.v_nota_netto, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, case when h.f_spb_consigment='f' then i.e_product_groupname else 'Modern Outlet' end as e_product_groupname, '' as e_remark
			 from tm_nota a
			 left join tr_area b on (a.i_area = b.i_area) 
			 left join tr_customer c on (a.i_customer = c.i_customer and a.i_area = c.i_area and b.i_area = c.i_area)
			 left join tr_salesman d on (a.i_salesman=d.i_salesman)
			 left join tr_city g on (c.i_city = g.i_city and c.i_area = g.i_area)
			 left join tm_spb h on a.i_spb=h.i_spb and a.i_area=h.i_area 
			 left join tr_product_group i on h.i_product_group=i.i_product_group
			 where to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and a.f_nota_cancel='f' and a.v_sisa>0 and not a.i_nota isnull
			 
			 union all
			 /*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
			 select j.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, j.d_nota, a.d_jatuh_tempo, j.v_jumlah, j.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, j.e_remark from tm_alokasi_item j, tm_alokasi k, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(k.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and a.i_salesman=b.i_salesman and a.f_nota_cancel='f' and not a.i_nota isnull
			 and j.i_alokasi=k.i_alokasi and j.i_kbank=k.i_kbank and j.i_area=k.i_area and k.f_alokasi_cancel='f' and a.i_nota=j.i_nota
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select l.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, l.d_nota, a.d_jatuh_tempo, l.v_jumlah, (l.v_sisa+l.v_jumlah) as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, l.e_remark from tm_alokasikn_item l, tm_alokasikn m, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(m.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum'
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f' and not a.i_nota isnull
			 and a.i_nota=l.i_nota and l.i_alokasi=m.i_alokasi and l.i_kn=m.i_kn and l.i_area=m.i_area and m.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select n.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, n.d_nota, a.d_jatuh_tempo, n.v_jumlah, n.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, n.e_remark from tm_alokasiknr_item n, tm_alokasiknr o, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(o.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=n.i_nota and n.i_alokasi=o.i_alokasi and n.i_kn=o.i_kn and n.i_area=o.i_area and o.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select p.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_nota, a.d_jatuh_tempo, p.v_jumlah, p.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, p.e_remark from tm_alokasihl_item p, tm_alokasihl q, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(q.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=p.i_nota and p.i_alokasi=q.i_alokasi and p.i_area=q.i_area and q.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 select a.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_refference as d_nota, a.d_jatuh_tempo, p.v_mutasi_debet as v_jumlah, p.v_mutasi_debet as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, p.e_description as e_remark from tm_general_ledger p, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(p.d_mutasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=substring(p.i_refference, 15, 15) and p.f_debet='t' and p.i_coa='610-2902'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 /*--------------------------------------Konsinyasi-----------------------------------------------------------------------------------------------------*/
			 union all
			 select j.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, j.d_nota, a.d_jatuh_tempo, j.v_jumlah, j.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, j.e_remark from tm_alokasi_item j, tm_alokasi k, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(k.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and a.i_salesman=b.i_salesman and a.f_nota_cancel='f' and not a.i_nota isnull
			 and j.i_alokasi=k.i_alokasi and j.i_kbank=k.i_kbank and j.i_area=k.i_area and k.f_alokasi_cancel='f' and a.i_nota=j.i_nota
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select l.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, l.d_nota, a.d_jatuh_tempo, l.v_jumlah, (l.v_sisa+l.v_jumlah) as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, l.e_remark from tm_alokasikn_item l, tm_alokasikn m, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(m.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=l.i_nota and l.i_alokasi=m.i_alokasi and l.i_kn=m.i_kn and l.i_area=m.i_area and m.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select n.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, n.d_nota, a.d_jatuh_tempo, n.v_jumlah, n.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, n.e_remark from tm_alokasiknr_item n, tm_alokasiknr o, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(o.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=n.i_nota and n.i_alokasi=o.i_alokasi and n.i_kn=o.i_kn and n.i_area=o.i_area and o.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select p.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_nota, a.d_jatuh_tempo, p.v_jumlah, p.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, p.e_remark from tm_alokasihl_item p, tm_alokasihl q, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(q.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=p.i_nota and p.i_alokasi=q.i_alokasi and p.i_area=q.i_area and q.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 select a.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_refference as d_nota, a.d_jatuh_tempo, p.v_mutasi_debet as v_jumlah, p.v_mutasi_debet as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, p.e_description as e_remark from tm_general_ledger p, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(p.d_mutasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=substring(p.i_refference, 15, 15) and p.f_debet='t' and p.i_coa='610-2902'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 
			 ) as x
			 group by x.i_nota, x.i_area, x.e_area_shortname, x.e_area_name, x.i_customer, x.e_customer_name, x.e_salesman_name, x.n_customer_toplength, x.i_sj, x.d_nota, x.d_jatuh_tempo, x.d_jatuh_tempo_plustoleransi, x.n_toleransi, x.e_product_groupname, x.e_remark, x.v_nota_netto, x.e_customer_address, x.e_customer_phone order by x.i_area asc, x.i_nota asc, x.i_customer asc
			 ) as z
			 group by z.i_customer, z.e_customer_name, z.i_area
			 union all
			 /* Penjualan */
			 select a.i_customer, b.e_customer_name, a.i_area, 0 as saldo_awal, sum(a.v_nota_netto) as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from
			 tm_nota a, tr_customer b
			 where
			 a.i_customer = b.i_customer
			 and to_char(a.d_nota, 'yyyymm')='$iperiode'
			 and a.f_nota_cancel = 'f'
			 group by a.i_customer, b.e_customer_name, a.i_area
			 union all
			 /* Alokasi */
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, a.v_jumlah as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from tm_alokasi_item a, tm_alokasi b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_kbank = b.i_kbank
			 and a.i_area = b.i_area
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, a.v_jumlah as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from tm_alokasiknr_item a, tm_alokasiknr b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_area = b.i_area
			 and a.i_kn = b.i_kn
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, a.v_jumlah as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from tm_alokasikn_item a, tm_alokasikn b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_area = b.i_area
			 and a.i_kn = b.i_kn
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, a.v_jumlah as alokasi_hll, 0 as pembulatan from tm_alokasihl_item a, tm_alokasihl b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_area = b.i_area
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select a.i_customer, c.e_customer_name, a.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, p.v_mutasi_debet as pembulatan from tm_general_ledger p, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(p.d_mutasi, 'yyyymm')='$iperiode' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=substring(p.i_refference, 15, 15) and p.f_debet='t' and p.i_coa='610-2902'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and area.i_area=a.i_area and a.i_customer=c.i_customer
			 ) as x
			 group by x.i_area, x.i_customer, x.e_customer_name
			  ",false);
		}else{
			$datatables->query(" select x.i_area, '('||x.i_customer||') '||x.e_customer_name as customer, sum(x.saldo_awal) as saldo_awal, sum(x.penjualan) as penjualan, sum(x.alokasi_bm) as alokasi_bm, sum(x.alokasi_knr) as alokasi_knr, sum(x.alokasi_kn) as alokasi_kn, sum(x.alokasi_hll) as alokasi_hll, sum(x.pembulatan) as pembulatan, 
			((sum(x.saldo_awal) + sum(x.penjualan)) - (sum(x.alokasi_bm) + sum(x.alokasi_knr) + sum(x.alokasi_kn) + sum(x.alokasi_hll) + sum(x.pembulatan) )) as saldo_akhir, '$folder' AS folder, '$tahun' AS tahun,
            '$bulan' AS bulan, '$iperiode' AS iperiode, '$title' AS title from(
			 select z.i_customer, z.e_customer_name, z.i_area, sum(z.v_sisa) as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from(
			 SELECT x.i_nota, x.i_area, x.e_area_shortname, x.e_area_name, x.i_customer, x.e_customer_name, x.e_customer_address, x.e_customer_phone, x.e_salesman_name, x.n_customer_toplength, x.i_sj, x.d_nota, x.d_jatuh_tempo, sum(x.v_sisa) as v_sisa, x.v_nota_netto, x.d_jatuh_tempo_plustoleransi, x.n_toleransi, x.e_product_groupname, x.e_remark from (
			 select a.i_nota, a.i_area, b.e_area_shortname, b.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, d.e_salesman_name, c.n_customer_toplength, a.i_sj, a.d_nota, a.d_jatuh_tempo, a.v_sisa, a.v_nota_netto, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, case when h.f_spb_consigment='f' then i.e_product_groupname else 'Modern Outlet' end as e_product_groupname, '' as e_remark
			 from tm_nota a
			 left join tr_area b on (a.i_area = b.i_area) 
			 left join tr_customer c on (a.i_customer = c.i_customer and a.i_area = c.i_area and b.i_area = c.i_area)
			 left join tr_salesman d on (a.i_salesman=d.i_salesman)
			 left join tr_city g on (c.i_city = g.i_city and c.i_area = g.i_area)
			 left join tm_spb h on a.i_spb=h.i_spb and a.i_area=h.i_area 
			 left join tr_product_group i on h.i_product_group=i.i_product_group
			 where to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and a.f_nota_cancel='f' and a.v_sisa>0 and not a.i_nota isnull
			 
			 union all
			 /*------------------------------------------------------------------------------------------------------------------------------------------------------------*/
			 select j.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, j.d_nota, a.d_jatuh_tempo, j.v_jumlah, j.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, j.e_remark from tm_alokasi_item j, tm_alokasi k, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(k.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and a.i_salesman=b.i_salesman and a.f_nota_cancel='f' and not a.i_nota isnull
			 and j.i_alokasi=k.i_alokasi and j.i_kbank=k.i_kbank and j.i_area=k.i_area and k.f_alokasi_cancel='f' and a.i_nota=j.i_nota
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select l.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, l.d_nota, a.d_jatuh_tempo, l.v_jumlah, (l.v_sisa+l.v_jumlah) as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, l.e_remark from tm_alokasikn_item l, tm_alokasikn m, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(m.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum'
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f' and not a.i_nota isnull
			 and a.i_nota=l.i_nota and l.i_alokasi=m.i_alokasi and l.i_kn=m.i_kn and l.i_area=m.i_area and m.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select n.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, n.d_nota, a.d_jatuh_tempo, n.v_jumlah, n.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, n.e_remark from tm_alokasiknr_item n, tm_alokasiknr o, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(o.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=n.i_nota and n.i_alokasi=o.i_alokasi and n.i_kn=o.i_kn and n.i_area=o.i_area and o.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select p.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_nota, a.d_jatuh_tempo, p.v_jumlah, p.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, p.e_remark from tm_alokasihl_item p, tm_alokasihl q, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(q.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=p.i_nota and p.i_alokasi=q.i_alokasi and p.i_area=q.i_area and q.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 select a.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_refference as d_nota, a.d_jatuh_tempo, p.v_mutasi_debet as v_jumlah, p.v_mutasi_debet as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, p.e_description as e_remark from tm_general_ledger p, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(p.d_mutasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=substring(p.i_refference, 15, 15) and p.f_debet='t' and p.i_coa='610-2902'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='f' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 /*--------------------------------------Konsinyasi-----------------------------------------------------------------------------------------------------*/
			 union all
			 select j.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, j.d_nota, a.d_jatuh_tempo, j.v_jumlah, j.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, j.e_remark from tm_alokasi_item j, tm_alokasi k, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(k.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and a.i_salesman=b.i_salesman and a.f_nota_cancel='f' and not a.i_nota isnull
			 and j.i_alokasi=k.i_alokasi and j.i_kbank=k.i_kbank and j.i_area=k.i_area and k.f_alokasi_cancel='f' and a.i_nota=j.i_nota
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select l.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, l.d_nota, a.d_jatuh_tempo, l.v_jumlah, (l.v_sisa+l.v_jumlah) as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, l.e_remark from tm_alokasikn_item l, tm_alokasikn m, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(m.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=l.i_nota and l.i_alokasi=m.i_alokasi and l.i_kn=m.i_kn and l.i_area=m.i_area and m.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select n.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, n.d_nota, a.d_jatuh_tempo, n.v_jumlah, n.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, n.e_remark from tm_alokasiknr_item n, tm_alokasiknr o, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(o.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=n.i_nota and n.i_alokasi=o.i_alokasi and n.i_kn=o.i_kn and n.i_area=o.i_area and o.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 
			 select p.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_nota, a.d_jatuh_tempo, p.v_jumlah, p.v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang
			 end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, 'Modern Outlet' as e_product_groupname, p.e_remark from tm_alokasihl_item p, tm_alokasihl q, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(q.d_alokasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=p.i_nota and p.i_alokasi=q.i_alokasi and p.i_area=q.i_area and q.f_alokasi_cancel='f'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 union all
			 select a.i_nota, a.i_area, area.e_area_shortname, area.e_area_name, a.i_customer, c.e_customer_name, c.e_customer_address, c.e_customer_phone, b.e_salesman_name, c.n_customer_toplength, a.i_sj, p.d_refference as d_nota, a.d_jatuh_tempo, p.v_mutasi_debet as v_jumlah, p.v_mutasi_debet as v_sisa, case when substring(a.i_sj, 9, 2)='00' then a.d_jatuh_tempo + interval '1' day * g.n_toleransi_pusat
			 else a.d_jatuh_tempo + interval '1' day * g.n_toleransi_cabang end as d_jatuh_tempo_plustoleransi, case when substring(a.i_sj, 9, 2)='00' then g.n_toleransi_pusat
			 else g.n_toleransi_cabang
			 end as n_toleransi, i.e_product_groupname, p.e_description as e_remark from tm_general_ledger p, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(p.d_mutasi, 'yyyymm')>'$periodesebelum' and to_char(a.d_nota, 'yyyymm')<='$periodesebelum' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=substring(p.i_refference, 15, 15) and p.f_debet='t' and p.i_coa='610-2902'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and h.f_spb_consigment='t' and area.i_area=a.i_area and a.i_customer=c.i_customer
			 
			 ) as x
			 group by x.i_nota, x.i_area, x.e_area_shortname, x.e_area_name, x.i_customer, x.e_customer_name, x.e_salesman_name, x.n_customer_toplength, x.i_sj, x.d_nota, x.d_jatuh_tempo, x.d_jatuh_tempo_plustoleransi, x.n_toleransi, x.e_product_groupname, x.e_remark, x.v_nota_netto, x.e_customer_address, x.e_customer_phone order by x.i_area asc, x.i_nota asc, x.i_customer asc
			 ) as z
			 group by z.i_customer, z.e_customer_name, z.i_area
			 union all
			 /* Penjualan */
			 select a.i_customer, b.e_customer_name, a.i_area, 0 as saldo_awal, sum(a.v_nota_netto) as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from
			 tm_nota a, tr_customer b
			 where
			 a.i_customer = b.i_customer
			 and to_char(a.d_nota, 'yyyymm')='$iperiode'
			 and a.f_nota_cancel = 'f'
			 group by a.i_customer, b.e_customer_name, a.i_area
			 union all
			 /* Alokasi */
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, a.v_jumlah as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from tm_alokasi_item a, tm_alokasi b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_kbank = b.i_kbank
			 and a.i_area = b.i_area
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, a.v_jumlah as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from tm_alokasiknr_item a, tm_alokasiknr b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_area = b.i_area
			 and a.i_kn = b.i_kn
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, a.v_jumlah as alokasi_kn, 0 as alokasi_hll, 0 as pembulatan from tm_alokasikn_item a, tm_alokasikn b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_area = b.i_area
			 and a.i_kn = b.i_kn
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select d.i_customer, c.e_customer_name, d.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, a.v_jumlah as alokasi_hll, 0 as pembulatan from tm_alokasihl_item a, tm_alokasihl b, tr_customer c, tm_nota d
			 where a.i_alokasi = b.i_alokasi
			 and a.i_area = b.i_area
			 and d.i_customer = c.i_customer
			 and b.f_alokasi_cancel = 'f'
			 and a.i_nota = d.i_nota
			 and to_char(b.d_alokasi, 'yyyymm')='$iperiode'
			 union all
			 select a.i_customer, c.e_customer_name, a.i_area, 0 as saldo_awal, 0 as penjualan, 0 as alokasi_bm, 0 as alokasi_knr, 0 as alokasi_kn, 0 as alokasi_hll, p.v_mutasi_debet as pembulatan from tm_general_ledger p, tr_customer c, tr_city g, tr_salesman b, tm_spb h, tr_product_group i, tr_area area, tm_nota a
			 where to_char(p.d_mutasi, 'yyyymm')='$iperiode' and not a.i_nota isnull
			 and a.i_salesman=b.i_salesman and a.f_nota_cancel='f'
			 and a.i_nota=substring(p.i_refference, 15, 15) and p.f_debet='t' and p.i_coa='610-2902'
			 and c.i_city = g.i_city and c.i_area = g.i_area and a.i_salesman=b.i_salesman and h.i_product_group=i.i_product_group
			 and a.i_spb=h.i_spb and a.i_area=h.i_area and area.i_area=a.i_area and a.i_customer=c.i_customer
			 ) as x
			 where x.i_area = '$iarea'
			 group by x.i_area, x.i_customer, x.e_customer_name
				",false);
	}
        
		    $datatables->edit('saldo_awal', function($data){
            return number_format($data['saldo_awal']);
        });
        
        $datatables->edit('alokasi_bm', function($data){
            return number_format($data['alokasi_bm']);
        });

        $datatables->edit('alokasi_knr', function($data){
            return number_format($data['alokasi_knr']);
        });

        $datatables->edit('penjualan', function($data){
            return number_format($data['penjualan']);
        });
        
        $datatables->edit('alokasi_hll', function($data){
            return number_format($data['alokasi_hll']);
        });

        $datatables->edit('alokasi_kn', function($data){
            return number_format($data['alokasi_kn']);
        });

        $datatables->edit('pembulatan', function($data){
            return number_format($data['pembulatan']);
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('tahun');
        $datatables->hide('bulan');
        $datatables->hide('iperiode');
//        $datatables->hide('i_area');
        return $datatables->generate();
    }

    public function total($dfrom, $dto, $iarea){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        return $this->db->query("
        SELECT	sum(a.v_sisa) as jml
                from tm_nota a, tr_area b
                where
                a.i_area=b.i_area and a.i_area='$iarea' and
                a.d_nota >= '$dfrom' AND
                a.d_nota <= '$dto'
                and a.f_ttb_tolak='f'
                and a.f_nota_cancel='f'
                and not a.i_nota isnull
        ", false);
    }

    public function bacadetail($nota,$iarea){
        $this->db->select("  
        c.i_nota, c.d_nota, c.v_nota_netto, c.i_alokasi, c.d_alokasi, c.i_kbank, c.e_bank_name, c.v_jumlah,
        c.e_jenis_bayarname, c.i_jenis_bayar from 
        (
        select a.i_nota, a.d_nota, a.v_nota_netto, c.i_pelunasan as i_alokasi, c.d_bukti as d_alokasi, c.i_giro as i_kbank, c.e_bank_name, 
        b.v_jumlah, d.e_jenis_bayarname, d.i_jenis_bayar
        from tm_nota a
        left join tm_pelunasan_item b on(b.i_nota=a.i_nota)
        inner join tm_pelunasan c on(b.i_pelunasan=c.i_pelunasan and c.f_pelunasan_cancel='f'
        and c.f_giro_tolak='f' and c.f_giro_batal='f' and b.i_area=c.i_area)
        left join tr_jenis_bayar d on(d.i_jenis_bayar=c.i_jenis_bayar)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kbank, g.e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasi_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasi f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kbank=f.i_kbank)
        inner join tr_bank_old g on(f.i_coa_bank=g.i_coa)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kbank, g.e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasi_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasi f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kbank=f.i_kbank)
        inner join tr_bank g on(f.i_coa_bank=g.i_coa)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kn as i_kbank, '' as e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasikn_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasikn f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kn=f.i_kn)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, '' as i_kbank, '' as e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasihl_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasihl f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kn as i_kbank, '' as e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasiknr_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasiknr f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kn=f.i_kn)
        where a.i_nota='$nota' and a.i_area='$iarea'
        )as c
        order by c.d_alokasi, c.i_alokasi", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function cekedit($ialokasi,$ikn,$isupplier){
        $this->db->select('i_alokasi');
        $this->db->from('tm_alokasikn');
        $this->db->where('i_alokasi', $ikn);
        $this->db->where('i_supplier', $isupplier);
        $this->db->where('i_kn', $ikn);
        $this->db->where('f_alokasi_cancel', 'f');
        return $this->db->get();
    }

    public function getnota($cari, $isupplier){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_nota,
                b.e_customer_name
            FROM
                tm_nota a,
                tr_customer b,
                tr_customer_groupbayar c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_customer = b.i_customer
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_cancel = 'f'
                AND a.v_sisa>0
                AND NOT (a.i_nota ISNULL
                OR TRIM(a.i_nota)= '')
                AND ( (c.i_customer_groupbayar IN(
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    SUBSTRING(i_customer, 1, 2)= '$isupplier')) )
                AND (UPPER(a.i_nota) LIKE '%$cari%'
                OR a.i_nota_old LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%')
            GROUP BY
                a.i_nota,
                a.i_supplier,
                a.d_nota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                b.e_customer_city
            ORDER BY
                a.i_customer,
                a.i_nota", 
        FALSE);
    }

    public function getdetailnota($inota,$isupplier){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.i_supplier,
                a.d_nota,
                to_char(a.d_nota, 'dd-mm-yyyy') AS dnota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') AS djtp,
                b.e_customer_city
            FROM
                tm_nota a,
                tr_customer b,
                tr_customer_groupbayar c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_customer = b.i_customer
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_cancel = 'f'
                AND a.v_sisa>0
                AND NOT (a.i_nota ISNULL
                OR TRIM(a.i_nota)= '')
                AND ( (c.i_customer_groupbayar IN(
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    SUBSTRING(i_customer, 1, 2)= '$isupplier')) )
                AND a.i_nota = '$inota'
            GROUP BY
                a.i_nota,
                a.i_supplier,
                a.d_nota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                b.e_customer_city
            ORDER BY
                a.i_customer,
                a.i_nota", 
        FALSE);
    }

    public function deletedetail($idt,$ddt,$isupplier,$vjumlah,$xddt){
        $this->db->set(
            array(
                'v_jumlah'  => $vjumlah
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        $this->db->where('i_supplier',$isupplier);
        $this->db->update('tm_dt');

        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        /*$this->db->where('i_nota',$inota);*/
        $this->db->where('i_supplier',$isupplier);
        $this->db->delete('tm_dt_item');
    }

    public function updateheader($idt,$isupplier,$ddt,$vjumlah,$fsisa){
        $this->db->where('i_dt',$idt);
        $this->db->where('i_supplier',$isupplier);
        $this->db->delete('tm_dt');
        
        $this->db->set(
            array(
                'i_dt'      => $idt,
                'i_supplier'    => $isupplier,
                'd_dt'      => $ddt,
                'v_jumlah'  => $vjumlah,
                'f_sisa'    => $fsisa
            )
        );
        $this->db->insert('tm_dt');
    }

    public function insertdetail($idt,$ddt,$inota,$isupplier,$dnota,$icustomer,$vsisa,$vjumlah,$i){
        $this->db->set(
            array(
                'i_dt'       => $idt,
                'd_dt'       => $ddt,
                'i_nota'     => $inota,
                'i_supplier'     => $isupplier,
                'd_nota'     => $dnota,
                'i_customer' => $icustomer,
                'v_sisa'     => $vsisa,
                'v_jumlah'   => $vjumlah,
                'n_item_no'  => $i
            )
        );
        $this->db->insert('tm_dt_item');
    }

    public function cancel($idt, $isupplier){
        $this->db->set(
            array(
                'f_dt_cancel'  => 't'
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('i_supplier',$isupplier);
        return $this->db->update('tm_dt');
    }
}

/* End of file Mmaster.php */
