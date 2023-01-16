<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $this->db->select('i_area, e_area_name');
        $this->db->from('tr_area');
        $this->db->where('f_area_real', 't');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return $query->result();
        }
    }
    
    public function bacaperiode($dfrom,$dto){
        $th    = date('Y', strtotime($dfrom));
        $query = $this->db->query("
            SELECT
                *
            FROM
                (
                SELECT
                    *
                FROM
                    crosstab ('SELECT a.i_product, d.i_supplier, j.e_supplier_name, a.e_product_name, b.e_area_name, g.e_product_groupname, 
                                    ''BARU'' as kategori, a.i_area, sum(a.n_deliver*a.v_unit_price) AS jumlah
                                    FROM tm_nota c, tm_nota_item a, tr_area b, tr_product_group g, tm_spb h, tr_product d, tr_product_type i
                                    , tr_supplier j
                                    WHERE c.f_nota_cancel = false AND c.i_sj = a.i_sj AND c.i_area = a.i_area AND c.i_area = b.i_area 
                                    AND c.i_spb=h.i_spb and c.i_area=h.i_area and h.f_spb_consigment=''f''
                                    AND NOT c.i_nota IS NULL AND a.i_product=d.i_product and d.i_supplier=j.i_supplier
                                    AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                                    AND i.i_product_group = g.i_product_group AND i.i_product_type=d.i_product_type 
                                    AND to_char(d.d_product_register,''yyyy'')=''$th''
                                    GROUP BY a.i_area, g.e_product_groupname, a.i_product, a.e_product_name, d.i_supplier, 
                                    e_supplier_name, b.e_area_name 
                                    order by g.e_product_groupname, a.i_product',
                    'select i_area from tr_area where f_area_real=''t'' order by i_area') AS (i_product TEXT,
                    i_supplier TEXT,
                    e_supplier_name TEXT,
                    e_product_name TEXT,
                    e_area_name TEXT,
                    e_product_groupname TEXT,
                    kategori TEXT,
                    area_00 INTEGER,
                    area_01 INTEGER,
                    area_02 INTEGER,
                    area_03 INTEGER,
                    area_04 INTEGER,
                    area_05 INTEGER,
                    area_06 INTEGER,
                    area_07 INTEGER,
                    area_08 INTEGER,
                    area_09 INTEGER,
                    area_10 INTEGER,
                    area_11 INTEGER,
                    area_12 INTEGER,
                    area_13 INTEGER,
                    area_14 INTEGER,
                    area_15 INTEGER,
                    area_16 INTEGER,
                    area_17 INTEGER,
                    area_18 INTEGER,
                    area_19 INTEGER,
                    area_20 INTEGER,
                    area_21 INTEGER,
                    area_22 INTEGER,
                    area_23 INTEGER,
                    area_24 INTEGER,
                    area_25 INTEGER,
                    area_26 INTEGER,
                    area_27 INTEGER,
                    area_28 INTEGER,
                    area_29 INTEGER,
                    area_30 INTEGER,
                    area_31 INTEGER,
                    area_32 INTEGER,
                    area_33 INTEGER,
                    area_PB INTEGER)
            UNION ALL
                SELECT
                    *
                FROM
                    crosstab ('SELECT a.i_product, d.i_supplier, j.e_supplier_name, a.e_product_name, b.e_area_name, g.e_product_groupname, 
                                    ''LAMA'' as kategori, a.i_area, sum(a.n_deliver*a.v_unit_price) AS jumlah
                                    FROM tm_nota c, tm_nota_item a, tr_area b, tr_product_group g, tm_spb h, tr_product d, tr_product_type i
                                    , tr_supplier j
                                    WHERE c.f_nota_cancel = false AND c.i_sj = a.i_sj AND c.i_area = a.i_area AND c.i_area = b.i_area 
                                    AND c.i_spb=h.i_spb and c.i_area=h.i_area and h.f_spb_consigment=''f''
                                    AND NOT c.i_nota IS NULL AND a.i_product=d.i_product and d.i_supplier=j.i_supplier
                                    AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                                    AND i.i_product_group = g.i_product_group AND i.i_product_type=d.i_product_type 
                                    AND to_char(d.d_product_register,''yyyy'')<''$th''
                                    GROUP BY a.i_area, g.e_product_groupname, a.i_product, a.e_product_name, d.i_supplier, 
                                    e_supplier_name, b.e_area_name 
                                    order by g.e_product_groupname, a.i_product',
                    'select i_area from tr_area where f_area_real=''t'' order by i_area') AS (i_product TEXT,
                    i_supplier TEXT,
                    e_supplier_name TEXT,
                    e_product_name TEXT,
                    e_area_name TEXT,
                    e_product_groupname TEXT,
                    kategori TEXT,
                    area_00 INTEGER,
                    area_01 INTEGER,
                    area_02 INTEGER,
                    area_03 INTEGER,
                    area_04 INTEGER,
                    area_05 INTEGER,
                    area_06 INTEGER,
                    area_07 INTEGER,
                    area_08 INTEGER,
                    area_09 INTEGER,
                    area_10 INTEGER,
                    area_11 INTEGER,
                    area_12 INTEGER,
                    area_13 INTEGER,
                    area_14 INTEGER,
                    area_15 INTEGER,
                    area_16 INTEGER,
                    area_17 INTEGER,
                    area_18 INTEGER,
                    area_19 INTEGER,
                    area_20 INTEGER,
                    area_21 INTEGER,
                    area_22 INTEGER,
                    area_23 INTEGER,
                    area_24 INTEGER,
                    area_25 INTEGER,
                    area_26 INTEGER,
                    area_27 INTEGER,
                    area_28 INTEGER,
                    area_29 INTEGER,
                    area_30 INTEGER,
                    area_31 INTEGER,
                    area_32 INTEGER,
                    area_33 INTEGER,
                    area_PB INTEGER)
            UNION ALL
                SELECT
                    *
                FROM
                    crosstab ('SELECT a.i_product, d.i_supplier, j.e_supplier_name, a.e_product_name, b.e_area_name, 
                                    ''Modern Outlet'' as e_product_groupname, 
                                    ''BARU'' as kategori, a.i_area, sum(a.n_deliver*a.v_unit_price) AS jumlah
                                    FROM tm_nota c, tm_nota_item a, tr_area b, tr_product_group g, tm_spb h, tr_product d, tr_product_type i
                                    , tr_supplier j
                                    WHERE c.f_nota_cancel = false AND c.i_sj = a.i_sj AND c.i_area = a.i_area AND c.i_area = b.i_area 
                                    AND c.i_spb=h.i_spb and c.i_area=h.i_area and h.f_spb_consigment=''t'' 
                                    AND NOT c.i_nota IS NULL AND a.i_product=d.i_product and d.i_supplier=j.i_supplier 
                                    AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                                    AND i.i_product_group = g.i_product_group AND i.i_product_type=d.i_product_type 
                                    AND to_char(d.d_product_register,''yyyy'')=''$th''
                                    GROUP BY a.i_area, e_product_groupname, a.i_product, a.e_product_name, d.i_supplier, 
                                    e_supplier_name, b.e_area_name
                                    order by g.e_product_groupname, a.i_product',
                    'select i_area from tr_area where f_area_real=''t'' order by i_area') AS (i_product TEXT,
                    i_supplier TEXT,
                    e_supplier_name TEXT,
                    e_product_name TEXT,
                    e_area_name TEXT,
                    e_product_groupname TEXT,
                    kategori TEXT,
                    area_00 INTEGER,
                    area_01 INTEGER,
                    area_02 INTEGER,
                    area_03 INTEGER,
                    area_04 INTEGER,
                    area_05 INTEGER,
                    area_06 INTEGER,
                    area_07 INTEGER,
                    area_08 INTEGER,
                    area_09 INTEGER,
                    area_10 INTEGER,
                    area_11 INTEGER,
                    area_12 INTEGER,
                    area_13 INTEGER,
                    area_14 INTEGER,
                    area_15 INTEGER,
                    area_16 INTEGER,
                    area_17 INTEGER,
                    area_18 INTEGER,
                    area_19 INTEGER,
                    area_20 INTEGER,
                    area_21 INTEGER,
                    area_22 INTEGER,
                    area_23 INTEGER,
                    area_24 INTEGER,
                    area_25 INTEGER,
                    area_26 INTEGER,
                    area_27 INTEGER,
                    area_28 INTEGER,
                    area_29 INTEGER,
                    area_30 INTEGER,
                    area_31 INTEGER,
                    area_32 INTEGER,
                    area_33 INTEGER,
                    area_PB INTEGER)
            UNION ALL
                SELECT
                    *
                FROM
                    crosstab ('SELECT a.i_product, d.i_supplier, j.e_supplier_name, a.e_product_name, b.e_area_name, 
                                    ''Modern Outlet'' as e_product_groupname, 
                                    ''LAMA'' as kategori, a.i_area, sum(a.n_deliver*a.v_unit_price) AS jumlah
                                    FROM tm_nota c, tm_nota_item a, tr_area b, tr_product_group g, tm_spb h, tr_product d, tr_product_type i
                                    , tr_supplier j
                                    WHERE c.f_nota_cancel = false AND c.i_sj = a.i_sj AND c.i_area = a.i_area AND c.i_area = b.i_area 
                                    AND c.i_spb=h.i_spb and c.i_area=h.i_area and h.f_spb_consigment=''t''
                                    AND NOT c.i_nota IS NULL AND a.i_product=d.i_product and d.i_supplier=j.i_supplier
                                    AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                                    AND i.i_product_group = g.i_product_group AND i.i_product_type=d.i_product_type 
                                    AND to_char(d.d_product_register,''yyyy'')<''$th''
                                    GROUP BY a.i_area, e_product_groupname, a.i_product, a.e_product_name, d.i_supplier, 
                                    e_supplier_name, b.e_area_name 
                                    order by g.e_product_groupname, a.i_product',
                    'select i_area from tr_area where f_area_real=''t'' order by i_area') AS (i_product TEXT,
                    i_supplier TEXT,
                    e_supplier_name TEXT,
                    e_product_name TEXT,
                    e_area_name TEXT,
                    e_product_groupname TEXT,
                    kategori TEXT,
                    area_00 INTEGER,
                    area_01 INTEGER,
                    area_02 INTEGER,
                    area_03 INTEGER,
                    area_04 INTEGER,
                    area_05 INTEGER,
                    area_06 INTEGER,
                    area_07 INTEGER,
                    area_08 INTEGER,
                    area_09 INTEGER,
                    area_10 INTEGER,
                    area_11 INTEGER,
                    area_12 INTEGER,
                    area_13 INTEGER,
                    area_14 INTEGER,
                    area_15 INTEGER,
                    area_16 INTEGER,
                    area_17 INTEGER,
                    area_18 INTEGER,
                    area_19 INTEGER,
                    area_20 INTEGER,
                    area_21 INTEGER,
                    area_22 INTEGER,
                    area_23 INTEGER,
                    area_24 INTEGER,
                    area_25 INTEGER,
                    area_26 INTEGER,
                    area_27 INTEGER,
                    area_28 INTEGER,
                    area_29 INTEGER,
                    area_30 INTEGER,
                    area_31 INTEGER,
                    area_32 INTEGER,
                    area_33 INTEGER,
                    area_PB INTEGER) ) AS a
            ORDER BY
                a.e_product_groupname,
                a.kategori,
                a.i_product
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
