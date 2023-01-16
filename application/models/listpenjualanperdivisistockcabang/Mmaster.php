<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($bulan,$tahun){
       $iperiode = $tahun.$bulan;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        SELECT 
                           x.i_area, 
                           x.e_area_name, 
                           x.e_product_groupname,
                           SUM(x.jumlah) AS jumlah, 
                           SUM(x.sisasaldo) AS sisasaldo, 
                           SUM(x.git) AS git
                        FROM (
                           SELECT 
                              a.i_area, 
                              b.e_area_name, 
                              d.i_product_group, 
                              d.e_product_groupname, 
                              SUM(e.n_deliver*j.v_product_retail) AS jumlah,
                              0 AS sisasaldo, 
                              0 AS git
                           FROM 
                              tm_nota a, 
                              tr_area b, 
                              tr_product_group d, 
                              tm_spb c, 
                              tm_nota_item e, 
                              tr_product g, 
                              tr_product_type h, 
                              tr_product_price j 
                           WHERE 
                              c.f_spb_cancel='f' 
                              AND c.i_spb=a.i_spb 
                              AND c.i_area=a.i_area
                              AND j.i_product=g.i_product 
                              AND j.i_price_group='00'
                              AND a.i_area=b.i_area 
                              AND to_char(a.d_nota, 'yyyymm')='$iperiode' 
                              AND NOT a.i_nota is null 
                              AND NOT a.i_sj like '%-%-00%' 
                              AND h.i_product_group=d.i_product_group 
                              AND a.f_nota_cancel='f' 
                              AND c.f_spb_consigment='f' 
                              AND e.i_product=g.i_product 
                              AND g.i_product_type=h.i_product_type 
                              AND a.i_sj=e.i_sj and a.i_area=e.i_area 
                           GROUP BY 
                              a.i_area, 
                              d.i_product_group, 
                              d.e_product_groupname, 
                              b.e_area_name 
                           
                           UNION ALL
                           SELECT 
                              b.i_area, 
                              b.e_area_name, 
                              d.i_product_group, 
                              d.e_product_groupname, 
                              0 AS jumlah, 
                              SUM(i.n_saldo_akhir*j.v_product_retail) AS sisasaldo, 
                              SUM((i.n_mutasi_git+n_git_penjualan)*j.v_product_retail) AS git 
                           FROM 
                              tr_area b, 
                              tr_product_group d, 
                              tr_product g, 
                              tr_product_type h, 
                              tm_mutasi i, 
                              tr_product_price j 
                           WHERE 
                              b.i_area=i.i_store 
                              AND d.i_product_group=h.i_product_group 
                              AND g.i_product=i.i_product 
                              AND i.i_store_location<>'PB' 
                              AND i.i_store<>'PB' 
                              AND h.i_product_type=g.i_product_type 
                              AND i.e_mutasi_periode='$iperiode' 
                              AND j.i_product=g.i_product 
                              AND j.i_price_group='00'
                           GROUP BY 
                              b.i_area, 
                              d.i_product_group, 
                              d.e_product_groupname, 
                              b.e_area_name, 
                              b.i_store
                           
                           UNION ALL 
                           SELECT 
                              a.i_area, 
                              b.e_area_name, 
                              'PB' AS i_product_group, 
                              'Modern Outlet ' AS e_product_groupname, 
                              SUM(e.n_deliver*j.v_product_retail) AS jumlah,
                              0 AS sisasaldo, 
                              0 AS git
                           FROM 
                              tm_nota a, 
                              tr_area b, 
                              tr_product_group d, 
                              tm_spb c, 
                              tm_nota_item e, 
                              tr_product g, 
                              tr_product_type h, 
                              tr_product_price j 
                           WHERE 
                              c.f_spb_cancel='f' 
                              AND c.i_spb=a.i_spb 
                              AND c.i_area=a.i_area
                              AND j.i_product=g.i_product 
                              AND j.i_price_group='00'
                              AND a.i_area=b.i_area 
                              AND to_char(a.d_nota, 'yyyymm')='$iperiode' 
                              AND not a.i_nota is null 
                              AND not a.i_sj like '%-%-00%' 
                              AND h.i_product_group=d.i_product_group 
                              AND a.f_nota_cancel='f' 
                              AND c.f_spb_consigment='t' 
                              AND e.i_product=g.i_product 
                              AND g.i_product_type=h.i_product_type 
                              AND a.i_sj=e.i_sj
                              AND a.i_area=e.i_area 
                           GROUP BY 
                              a.i_area, 
                              d.i_product_group, 
                              d.e_product_groupname, 
                              b.e_area_name
                           
                           UNION ALL
                           SELECT 
                              b.i_area, 
                              b.e_area_name, 
                              'PB' AS i_product_group, 
                              'Modern Outlet ' AS e_product_groupname, 
                              0 AS jumlah, 
                              SUM(i.n_saldo_akhir*j.v_product_retail) AS sisasaldo, 
                              SUM((i.n_mutasi_git+n_git_penjualan)*j.v_product_retail) AS git 
                           FROM 
                              tr_area b, 
                              tr_product_group d, 
                              tr_product g, 
                              tr_product_type h, 
                              tm_mutasi i, 
                              tr_product_price j 
                           WHERE 
                              b.i_area=i.i_store 
                              and d.i_product_group=h.i_product_group 
                              and g.i_product=i.i_product 
                              and h.i_product_type=g.i_product_type 
                              and i.e_mutasi_periode='$iperiode' 
                              and (i.i_store_location='PB' or i.i_store='PB') 
                              and j.i_product=g.i_product 
                              and j.i_price_group='00'
                           GROUP BY 
                              b.i_area, 
                              d.i_product_group, 
                              d.e_product_groupname, 
                              b.e_area_name
                        ) x 
                        GROUP BY 
                           x.i_area, 
                           x.e_area_name, 
                           x.i_product_group, 
                           x.e_product_groupname
                        ORDER BY 
                           x.i_area, 
                           x.e_product_groupname"
                , FALSE);

        /*$datatables->edit('jumlah', function ($data) {
            return number_format($data['jumlah'],2);
        });
        $datatables->edit('jml_netto', function ($data) {
            return number_format($data['jml_netto'],2);
        });*/
        return $datatables->generate();
    }

    public function total($bulan, $tahun){  
       $iperiode = $tahun.$bulan; 
        return $this->db->query("
                              SELECT 
                                 SUM(x.jumlah) AS jumlah, 
                                 SUM(x.sisasaldo) AS sisasaldo, 
                                 SUM(x.git) AS git
                              FROM (
                                   SELECT 
                                      a.i_area, 
                                      b.e_area_name, 
                                      d.i_product_group, 
                                      d.e_product_groupname, 
                                      SUM(e.n_deliver*j.v_product_retail) AS jumlah,
                                      0 AS sisasaldo, 
                                      0 AS git
                                   FROM 
                                      tm_nota a, 
                                      tr_area b, 
                                      tr_product_group d, 
                                      tm_spb c, 
                                      tm_nota_item e, 
                                      tr_product g, 
                                      tr_product_type h, 
                                      tr_product_price j 
                                   WHERE 
                                      c.f_spb_cancel='f' 
                                      AND c.i_spb=a.i_spb 
                                      AND c.i_area=a.i_area
                                      AND j.i_product=g.i_product 
                                      AND j.i_price_group='00'
                                      AND a.i_area=b.i_area 
                                      AND to_char(a.d_nota, 'yyyymm')='$iperiode' 
                                      AND NOT a.i_nota is null 
                                      AND NOT a.i_sj like '%-%-00%' 
                                      AND h.i_product_group=d.i_product_group 
                                      AND a.f_nota_cancel='f' 
                                      AND c.f_spb_consigment='f' 
                                      AND e.i_product=g.i_product 
                                      AND g.i_product_type=h.i_product_type 
                                      AND a.i_sj=e.i_sj and a.i_area=e.i_area 
                                   GROUP BY 
                                      a.i_area, 
                                      d.i_product_group, 
                                      d.e_product_groupname, 
                                      b.e_area_name 

                                   UNION ALL
                                   SELECT 
                                      b.i_area, 
                                      b.e_area_name, 
                                      d.i_product_group, 
                                      d.e_product_groupname, 
                                      0 AS jumlah, 
                                      SUM(i.n_saldo_akhir*j.v_product_retail) AS sisasaldo, 
                                      SUM((i.n_mutasi_git+n_git_penjualan)*j.v_product_retail) AS git 
                                   FROM 
                                      tr_area b, 
                                      tr_product_group d, 
                                      tr_product g, 
                                      tr_product_type h, 
                                      tm_mutasi i, 
                                      tr_product_price j 
                                   WHERE 
                                      b.i_area=i.i_store 
                                      AND d.i_product_group=h.i_product_group 
                                      AND g.i_product=i.i_product 
                                      AND i.i_store_location<>'PB' 
                                      AND i.i_store<>'PB' 
                                      AND h.i_product_type=g.i_product_type 
                                      AND i.e_mutasi_periode='$iperiode' 
                                      AND j.i_product=g.i_product 
                                      AND j.i_price_group='00'
                                   GROUP BY 
                                      b.i_area, 
                                      d.i_product_group, 
                                      d.e_product_groupname, 
                                      b.e_area_name, 
                                      b.i_store

                                   UNION ALL 
                                   SELECT 
                                      a.i_area, 
                                      b.e_area_name, 
                                      'PB' AS i_product_group, 
                                      'Modern Outlet ' AS e_product_groupname, 
                                      SUM(e.n_deliver*j.v_product_retail) AS jumlah,
                                      0 AS sisasaldo, 
                                      0 AS git
                                   FROM 
                                      tm_nota a, 
                                      tr_area b, 
                                      tr_product_group d, 
                                      tm_spb c, 
                                      tm_nota_item e, 
                                      tr_product g, 
                                      tr_product_type h, 
                                      tr_product_price j 
                                   WHERE 
                                      c.f_spb_cancel='f' 
                                      AND c.i_spb=a.i_spb 
                                      AND c.i_area=a.i_area
                                      AND j.i_product=g.i_product 
                                      AND j.i_price_group='00'
                                      AND a.i_area=b.i_area 
                                      AND to_char(a.d_nota, 'yyyymm')='$iperiode' 
                                      AND not a.i_nota is null 
                                      AND not a.i_sj like '%-%-00%' 
                                      AND h.i_product_group=d.i_product_group 
                                      AND a.f_nota_cancel='f' 
                                      AND c.f_spb_consigment='t' 
                                      AND e.i_product=g.i_product 
                                      AND g.i_product_type=h.i_product_type 
                                      AND a.i_sj=e.i_sj
                                      AND a.i_area=e.i_area 
                                   GROUP BY 
                                      a.i_area, 
                                      d.i_product_group, 
                                      d.e_product_groupname, 
                                      b.e_area_name

                                   UNION ALL
                                   SELECT 
                                      b.i_area, 
                                      b.e_area_name, 
                                      'PB' AS i_product_group, 
                                      'Modern Outlet ' AS e_product_groupname, 
                                      0 AS jumlah, 
                                      SUM(i.n_saldo_akhir*j.v_product_retail) AS sisasaldo, 
                                      SUM((i.n_mutasi_git+n_git_penjualan)*j.v_product_retail) AS git 
                                   FROM 
                                      tr_area b, 
                                      tr_product_group d, 
                                      tr_product g, 
                                      tr_product_type h, 
                                      tm_mutasi i, 
                                      tr_product_price j 
                                   WHERE 
                                      b.i_area=i.i_store 
                                      and d.i_product_group=h.i_product_group 
                                      and g.i_product=i.i_product 
                                      and h.i_product_type=g.i_product_type 
                                      and i.e_mutasi_periode='$iperiode' 
                                      and (i.i_store_location='PB' or i.i_store='PB') 
                                      and j.i_product=g.i_product 
                                      and j.i_price_group='00'
                                   GROUP BY 
                                      b.i_area, 
                                      d.i_product_group, 
                                      d.e_product_groupname, 
                                      b.e_area_name
                                ) x 
                                GROUP BY 
                                   x.i_area, 
                                   x.e_area_name, 
                                   x.i_product_group, 
                                   x.e_product_groupname
                                ORDER BY 
                                   x.i_area, 
                                   x.e_product_groupname"
                        , FALSE);
   }
}                          

/*                            End of file Mmaster.php */
