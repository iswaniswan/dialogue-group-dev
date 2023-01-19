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

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($bulan, $tahun, $iarea){
        $this->load->library('fungsi');
        $iperiode = $tahun.$bulan;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                                x.i_customer,
                                x.e_customer_name,
                                x.e_customer_address,
                                x.i_salesman, 
                                x.f_spb_consigment,
                                sum(x.nota) as nota,
                                sum(x.bersih) as bersih,
                                sum(x.alokasi_bm) as alokasi_bm,
                                sum(x.alokasi_kn) as alokasi_kn,
                                sum(x.alokasi_knr) as alokasi_knr,
                                sum(x.alokasi_hll) as alokasi_hll
                            from(
                            /*nota*/
                                select 
                                    a.i_customer,
                                    b.e_customer_name,
                                    b.e_customer_address,
                                    a.i_salesman,
                                    c.f_spb_consigment,
                                    sum(a.v_nota_gross) as nota,
                                    0 as bersih,
                                    0 as alokasi_bm,
                                    0 as alokasi_kn,
                                    0 as alokasi_knr,
                                    0 as alokasi_hll
                                from
                                    tm_nota a,
                                    tr_customer b,
                                    tm_spb c
                                where
                                    a.f_nota_cancel = 'f' 
                                    and to_char(a.d_nota, 'yyyymm') = '$iperiode' 
                                    and not a.i_nota isnull 
                                    and a.i_area = '$iarea' 
                                    and a.i_customer = b.i_customer 
                                    and a.i_spb = c.i_spb 
                                    and a.i_area = c.i_area 
                                group by
                                    a.i_customer, 
                                    b.e_customer_name,
                                    b.e_customer_address,
                                    a.i_salesman,
                                    c.f_spb_consigment
                                union all
                                /*bersih*/
                                select 
                                    a.i_customer,
                                    b.e_customer_name,
                                    b.e_customer_address,
                                    a.i_salesman,
                                    c.f_spb_consigment,
                                    0 as nota,
                                    sum(a.v_nota_netto) as bersih,
                                    0 as alokasi_bm,
                                    0 as alokasi_kn,
                                    0 as alokasi_knr,
                                    0 as alokasi_hll
                                from
                                    tm_nota a,
                                    tr_customer b,
                                    tm_spb c
                                where
                                    a.f_nota_cancel = 'f' 
                                    and to_char(a.d_nota, 'yyyymm') = '$iperiode' 
                                    and not a.i_nota isnull 
                                    and a.i_area = '$iarea' 
                                    and a.i_customer = b.i_customer 
                                    and a.i_spb = c.i_spb 
                                    and a.i_area = c.i_area 
                                group by
                                    a.i_customer, 
                                    b.e_customer_name,
                                    b.e_customer_address,
                                    a.i_salesman,
                                    c.f_spb_consigment
                                union all
                                /*Alokasi BM*/
                                select 
                                    c.i_customer,
                                    e.e_customer_name,
                                    e.e_customer_address,
                                    c.i_salesman,
                                    d.f_spb_consigment,
                                    0 as nota,
                                    0 as bersih,
                                    sum(a.v_jumlah) as alokasi_bm,
                                    0 as alokasi_kn,
                                    0 as alokasi_knr,
                                    0 as alokasi_hll
                                from
                                    tm_alokasi_item a,
                                    tm_alokasi b,
                                    tm_nota c,
                                    tm_spb d,
                                    tr_customer e
                                where
                                    c.f_nota_cancel = 'f' and
                                    not c.i_nota isnull and
                                    c.i_spb = d.i_spb and 
                                    a.i_nota = c.i_nota and
                                    a.i_alokasi = b.i_alokasi and
                                    a.i_kbank = b.i_kbank and
                                    b.f_alokasi_cancel = 'f' and
                                    c.i_customer = e.i_customer and
                                    a.i_area = c.i_area and
                                    c.i_area = d.i_area and 
                                    a.i_area = '$iarea' and
                                    to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
                                group by
                                    c.i_customer, 
                                    e.e_customer_name,  
                                    e.e_customer_address, 
                                    c.i_salesman, 
                                    d.f_spb_consigment
                                union all
                                /*Alokasi KN*/
                                select 
                                    c.i_customer,
                                    e.e_customer_name,
                                    e.e_customer_address,
                                    c.i_salesman,
                                    d.f_spb_consigment,
                                    0 as nota,
                                    0 as bersih,
                                    0 as alokasi_bm,
                                    sum(a.v_jumlah) as alokasi_kn,
                                    0 as alokasi_knr,
                                    0 as alokasi_hll
                                from
                                    tm_alokasikn_item a,
                                    tm_alokasikn b,
                                    tm_nota c,
                                    tm_spb d,
                                    tr_customer e
                                where
                                    c.f_nota_cancel = 'f' and
                                    not c.i_nota isnull and
                                    c.i_spb = d.i_spb and 
                                    a.i_nota = c.i_nota and
                                    a.i_alokasi = b.i_alokasi and
                                    a.i_kn = b.i_kn and
                                    b.f_alokasi_cancel = 'f' and
                                    c.i_customer = e.i_customer and
                                    a.i_area = c.i_area and
                                    c.i_area = d.i_area and 
                                    a.i_area = '$iarea' and
                                    to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
                                group by
                                    c.i_customer, 
                                    e.e_customer_name,  
                                    e.e_customer_address, 
                                    c.i_salesman, 
                                    d.f_spb_consigment
                                    union all
                                /*Alokasi KN*/
                                select 
                                    c.i_customer,
                                    e.e_customer_name,
                                    e.e_customer_address,
                                    c.i_salesman,
                                    d.f_spb_consigment,
                                    0 as nota,
                                    0 as bersih,
                                    0 as alokasi_bm,
                                    0 as alokasi_kn,
                                    sum(a.v_jumlah) as alokasi_knr,
                                    0 as alokasi_hll
                                from
                                    tm_alokasiknr_item a,
                                    tm_alokasiknr b,
                                    tm_nota c,
                                    tm_spb d,
                                    tr_customer e
                                where
                                    c.f_nota_cancel = 'f' and
                                    not c.i_nota isnull and
                                    c.i_spb = d.i_spb and 
                                    a.i_nota = c.i_nota and
                                    a.i_alokasi = b.i_alokasi and
                                    a.i_kn = b.i_kn and
                                    b.f_alokasi_cancel = 'f' and
                                    c.i_customer = e.i_customer and
                                    a.i_area = c.i_area and
                                    c.i_area = d.i_area and 
                                    a.i_area = '$iarea' and
                                    to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
                                group by
                                    c.i_customer, 
                                    e.e_customer_name,  
                                    e.e_customer_address, 
                                    c.i_salesman, 
                                    d.f_spb_consigment
                                union all
                                /*Alokasi HLL*/
                                select 
                                    c.i_customer,
                                    e.e_customer_name,
                                    e.e_customer_address,
                                    c.i_salesman,
                                    d.f_spb_consigment,
                                    0 as nota,
                                    0 as bersih,
                                    0 as alokasi_bm,
                                    0 as alokasi_kn,
                                    0 as alokasi_knr,
                                    sum(a.v_jumlah) as alokasi_hll
                                from
                                    tm_alokasihl_item a,
                                    tm_alokasihl b,
                                    tm_nota c,
                                    tm_spb d,
                                    tr_customer e
                                where
                                    c.f_nota_cancel = 'f' and
                                    not c.i_nota isnull and
                                    c.i_spb = d.i_spb and 
                                    a.i_nota = c.i_nota and
                                    a.i_alokasi = b.i_alokasi and
                                    b.f_alokasi_cancel = 'f' and
                                    c.i_customer = e.i_customer and
                                    a.i_area = c.i_area and
                                    c.i_area = d.i_area and 
                                    a.i_area = '$iarea' and
                                    to_char(b.d_alokasi, 'yyyymm') = '$iperiode' 
                                group by
                                    c.i_customer, 
                                    e.e_customer_name,  
                                    e.e_customer_address, 
                                    c.i_salesman, 
                                    d.f_spb_consigment
                            ) as x
                            group by
                            x.i_customer, 
                            x.e_customer_name,
                            x.e_customer_address,
                            x.i_salesman,
                            x.f_spb_consigment"
                        ,false);
        
        
        $datatables->edit('nota', function($data){
            return number_format($data['nota']);
        });

        $datatables->edit('bersih', function($data){
            return number_format($data['bersih']);
        });

        $datatables->edit('alokasi_bm', function($data){
            return number_format($data['alokasi_bm']);
        });

        $datatables->edit('alokasi_kn', function($data){
            return number_format($data['alokasi_kn']);
        });

        $datatables->edit('alokasi_knr', function($data){
            return number_format($data['alokasi_knr']);
        });

        $datatables->edit('alokasi_hll', function($data){
            return number_format($data['alokasi_hll']);
        });

        $datatables->edit('f_spb_consigment', function($data){
            if ($data['f_spb_consigment']=='f'){
                return 'Tidak';
            }else if ($data['f_spb_consigment'] ==  't'){
                return 'Ya';
            }
        });

        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
