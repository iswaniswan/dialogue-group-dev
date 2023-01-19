<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username, $idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE)->result();
    }

    function interval($dfrom,$dto){
        if($dfrom!=''){
				$tmp=explode("-",$dfrom);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dfrom=$th."-".$bl."-".$hr;
		}
        if($dto!=''){
				$tmp=explode("-",$dto);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dto=$th."-".$bl."-".$hr;
		}
		$this->db->select("(DATE_PART('year', '$dto'::date) - DATE_PART('year', '$dfrom'::date)) * 12 +
                            (DATE_PART('month', '$dto'::date) - DATE_PART('month', '$dfrom'::date)) as inter ",false);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$tmp=$query->row();
            return $tmp->inter+1;
		}
    }

    function bacaperiode($dfrom,$dto,$iarea,$interval){
      if($dfrom!=''){
				$tmp=explode("-",$dfrom);
        $th=$tmp[2];				
        $bl=$tmp[1];
        $dt=$tmp[0];
        $tgl=$th.'-'.$bl.'-'.$dt;
			}
      if($iarea=='NA'){

        $sql=" a.nama, a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.iarea, a.icity, a.product, a.productname, a.group,";
        switch ($bl){
        case '01' :
          $sql.=" sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                  sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, 
                  sum(a.Nov) as spbnov, sum(a.Des) as spbdes ";
          break;
        case '02' :
          $sql.=" sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 
                  sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, 
                  sum(a.Des) as spbdes, sum(a.Jan) as spbjan ";
          break;
        case '03' :
          $sql.=" sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 
                  sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 
                  sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb ";
          break;
        case '04' :
          $sql.=" sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                  sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                  sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar ";
          break;
        case '05' :
          $sql.=" sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                  sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                  sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr ";
          break;
        case '06' :
          $sql.=" sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, 
                  sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 
                  sum(a.Apr) as spbapr, sum(a.May) as spbmay ";
          break;
        case '07' :
          $sql.=" sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, 
                  sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 
                  sum(a.May) as spbmay, sum(a.Jun) as spbjun ";
          break;
        case '08' :
          $sql.=" sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 
                  sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                  sum(a.Jun) as spbjun, sum(a.Jul) as spbjul ";
          break;

        case '09' :
          $sql.=" sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                  sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 
                  sum(a.Jul) as spbjul, sum(a.Aug) as spbaug ";
          break;
        case '10' :
          $sql.=" sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, 
                  sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 
                  sum(a.Aug) as spbaug, sum(a.Sep) as spbsep ";
          break;
        case '11' :
          $sql.=" sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 
                  sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                  sum(a.Sep) as spbsep, sum(a.Oct) as spbokt ";
          break;
        case '12' :
          $sql.=" sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 
                  sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, 
                  sum(a.Oct) as spbokt, sum(a.Nov) as spbnov ";
          break;
        }

        $sql.=" from ( select isi[1] as nama, isi[9] as kode, isi[10] as product, isi[2] as alamat, isi[3] as kota, isi[4] as sales, 
isi[5] as area, isi[6] as jenis, isi[7] as iarea, isi[8] as icity, isi[13] as supplier, isi[11] as productname, isi[12] as unitprice, isi[15] as group,  ";
        switch ($bl){
        case '01' :
          $sql.=" Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des ";
          break;
        case '02' :
          $sql.=" Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan ";
          break;
        case '03' :
          $sql.=" Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb ";

          break;
        case '04' :
          $sql.=" Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar ";
          break;
        case '05' :
          $sql.=" May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr ";
          break;
        case '06' :
          $sql.=" Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May ";
          break;
        case '07' :
          $sql.=" Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun ";
          break;
        case '08' :
          $sql.=" Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul ";
          break;
        case '09' :
          $sql.=" Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug ";
          break;
        case '10' :
          $sql.=" Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep ";
          break;
        case '11' :
          $sql.=" Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct ";
          break;
        case '12' :
          $sql.=" Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov ";
          break;
        }
        $sql.=" from crosstab
              ('SELECT Array [b.e_customer_name::text, b.e_customer_address::text, c.e_city_name::text, a.i_salesman::text, 
                d.e_area_name::text, e.e_customer_classname::text, a.i_area::text, b.i_city::text, a.i_customer::text, f.i_product::text, 
                f.e_product_name, f.v_unit_price::text, h.e_supplier_name::text, g.i_supplier::text, i.e_product_groupname::text] as isi, to_number(to_char(a.d_spb, ''mm''),''99'') as bln, 
                sum(f.n_order) AS jumlah
                FROM tm_spb a, tr_customer b, tr_city c, tr_area d, tr_customer_class e, tm_spb_item f, tr_product g, tr_supplier h,
                tr_product_group i, tr_product_type j
                WHERE a.f_spb_cancel = false AND b.i_customer = a.i_customer and a.f_spb_cancel=''f'' 
                AND a.i_spb=f.i_spb AND a.i_area=f.i_area AND f.i_product=g.i_product AND g.i_supplier=h.i_supplier
                and b.i_city=c.i_city and b.i_area=c.i_area and b.i_area=d.i_area
                AND b.i_customer_class=e.i_customer_class
                AND (a.d_spb >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_spb <= to_date(''$dto'',''dd-mm-yyyy''))
                AND g.i_product_type=j.i_product_type AND i.i_product_group=j.i_product_group
                GROUP BY b.e_customer_name, a.i_customer, b.e_customer_address, c.e_city_name, a.i_salesman, d.e_area_name, 
                e.e_customer_classname, a.i_area, b.i_city, f.i_product, f.e_product_name, f.v_unit_price, h.e_supplier_name, g.i_supplier,
                i.e_product_groupname,
                to_char(a.d_spb, ''mm'')
                order by a.i_customer, b.e_customer_name, to_char(a.d_spb, ''mm'')','select (SELECT EXTRACT(MONTH FROM 
                date_trunc(''month'', 
                ''$tgl''::date)::date + s.a * ''1 month''::interval))
                from generate_series(0, 11) as s(a)')
                as
                (isi text[], ";
        switch ($bl){
        case '01' :
          $sql.=" Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, 
                  Oct integer, Nov integer, Des integer) ";
          break;
        case '02' :
          $sql.=" Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, 
                  Nov integer, Des integer, Jan integer) ";
          break;
        case '03' :
          $sql.=" Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, 
                  Des integer, Jan integer, Feb integer) ";
          break;
        case '04' :
          $sql.=" Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, 

                  Jan integer, Feb integer, Mar integer) ";
          break;
        case '05' :
          $sql.=" May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, 
                  Feb integer, Mar integer, Apr integer) ";
          break;
        case '06' :
          $sql.=" Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, 
                  Mar integer, Apr integer, May integer) ";
          break;
        case '07' :
          $sql.=" Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, 
                  Apr integer, May integer, Jun integer) ";
          break;
        case '08' :
          $sql.=" Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, 
                  May integer, Jun integer, Jul integer) ";
          break;
        case '09' :
          $sql.=" Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, 
                  Jun integer, Jul integer, Aug integer) ";
          break;
        case '10' :
          $sql.=" Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, 
                  Jul integer, Aug integer, Sep integer) ";
          break;
        case '11' :
          $sql.=" Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, 
                  Aug integer, Sep integer, Oct integer) ";
          break;
        case '12' :
          $sql.=" Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, 
                  Sep integer, Oct integer, Nov integer) ";

          break;
        }
        $sql.=" ) as a
                group by a.iarea, a.icity, a.group, a.nama , a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname 
                order by a.iarea, a.icity, a.group, a.kode, a.nama, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname";
      }else{
        $sql=" a.nama, a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.iarea, a.icity, a.product, a.productname, a.group, ";
        switch ($bl){
        case '01' :
          $sql.=" sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                  sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, 

                  sum(a.Nov) as spbnov, sum(a.Des) as spbdes ";
          break;
        case '02' :
          $sql.=" sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 

                  sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, 
                  sum(a.Des) as spbdes, sum(a.Jan) as spbjan ";
          break;
        case '03' :
          $sql.=" sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 
                  sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 

                  sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb ";
          break;
        case '04' :
          $sql.=" sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                  sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 


                  sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar ";
          break;
        case '05' :
          $sql.=" sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 

                  sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                  sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr ";
          break;
        case '06' :
          $sql.=" sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, 
                  sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 

                  sum(a.Apr) as spbapr, sum(a.May) as spbmay ";
          break;
        case '07' :
          $sql.=" sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, 

                  sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 
                  sum(a.May) as spbmay, sum(a.Jun) as spbjun ";
          break;
        case '08' :
          $sql.=" sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 
                  sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                  sum(a.Jun) as spbjun, sum(a.Jul) as spbjul ";
          break;
        case '09' :
          $sql.=" sum(a.Sep) as spbsep, sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                  sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 

                  sum(a.Jul) as spbjul, sum(a.Aug) as spbaug ";
          break;
        case '10' :

          $sql.=" sum(a.Oct) as spbokt, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, 
                  sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 

                  sum(a.Aug) as spbaug, sum(a.Sep) as spbsep ";
          break;
        case '11' :
          $sql.=" sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 

                  sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                  sum(a.Sep) as spbsep, sum(a.Oct) as spbokt ";
          break;
        case '12' :
          $sql.=" sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 
                  sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, 
                  sum(a.Oct) as spbokt, sum(a.Nov) as spbnov ";
          break;
        }

        $sql.=" from ( select isi[1] as nama, isi[9] as kode, isi[10] as product, isi[2] as alamat, isi[3] as kota, isi[4] as sales, 
isi[5] as area, isi[6] as jenis, isi[7] as iarea, isi[8] as icity, isi[13] as supplier, isi[11] as productname, isi[12] as unitprice, isi[15] as group,";
        switch ($bl){
        case '01' :
          $sql.=" Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des ";
          break;
        case '02' :
          $sql.=" Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan ";
          break;
        case '03' :
          $sql.=" Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb ";
          break;
        case '04' :
          $sql.=" Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar ";
          break;

        case '05' :
          $sql.=" May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr ";
          break;
        case '06' :
          $sql.=" Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May ";
          break;
        case '07' :
          $sql.=" Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun ";
          break;
        case '08' :
          $sql.=" Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul ";
          break;
        case '09' :
          $sql.=" Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug ";
          break;
        case '10' :
          $sql.=" Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep ";
          break;
        case '11' :
          $sql.=" Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct ";
          break;
        case '12' :
          $sql.=" Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov ";
          break;
        }
        $sql.=" from crosstab
              ('SELECT Array [b.e_customer_name::text, b.e_customer_address::text, c.e_city_name::text, a.i_salesman::text, 
                d.e_area_name::text, e.e_customer_classname::text, a.i_area::text, b.i_city::text, a.i_customer::text, f.i_product::text, 
                f.e_product_name, f.v_unit_price::text, h.e_supplier_name::text, g.i_supplier::text, i.e_product_groupname::text] as isi, to_number(to_char(a.d_spb, ''mm''),''99'') as bln, 
                sum(f.n_order) AS jumlah
                FROM tm_spb a, tr_customer b, tr_city c, tr_area d, tr_customer_class e, tm_spb_item f, tr_product g, tr_supplier h, tr_product_group i, tr_product_type j
                WHERE a.f_spb_cancel = false AND b.i_customer = a.i_customer and a.f_spb_cancel=''f'' 
                AND a.i_spb=f.i_spb AND a.i_area=f.i_area AND a.i_area=''$iarea''
                and b.i_city=c.i_city and b.i_area=c.i_area and b.i_area=d.i_area
                AND b.i_customer_class=e.i_customer_class AND f.i_product=g.i_product AND g.i_supplier=h.i_supplier
                AND (a.d_spb >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_spb <= to_date(''$dto'',''dd-mm-yyyy''))
                AND g.i_product_type=j.i_product_type AND i.i_product_group=j.i_product_group
                GROUP BY b.e_customer_name, a.i_customer, b.e_customer_address, c.e_city_name, a.i_salesman, d.e_area_name, 
                e.e_customer_classname, a.i_area, b.i_city, f.i_product, f.e_product_name, f.v_unit_price, g.i_supplier, h.e_supplier_name, i.e_product_groupname, 
                to_char(a.d_spb, ''mm'')
                order by a.i_customer, b.e_customer_name, to_char(a.d_spb, ''mm'')','select (SELECT EXTRACT(MONTH FROM 
                date_trunc(''month'', 
                ''$tgl''::date)::date + s.a * ''1 month''::interval))
                from generate_series(0, 11) as s(a)')
                as
                (isi text[], ";
        switch ($bl){
        case '01' :
          $sql.=" Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, 
                  Oct integer, Nov integer, Des integer) ";
          break;
        case '02' :
          $sql.=" Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, 
                  Nov integer, Des integer, Jan integer) ";
          break;
        case '03' :
          $sql.=" Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, 
                  Des integer, Jan integer, Feb integer) ";
          break;
        case '04' :
          $sql.=" Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, 
                  Jan integer, Feb integer, Mar integer) ";
          break;
        case '05' :
          $sql.=" May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, 

                  Feb integer, Mar integer, Apr integer) ";
          break;
        case '06' :
          $sql.=" Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, 

                  Mar integer, Apr integer, May integer) ";
          break;
        case '07' :
          $sql.=" Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, 

                  Apr integer, May integer, Jun integer) ";
          break;
        case '08' :
          $sql.=" Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, 

                  May integer, Jun integer, Jul integer) ";
          break;
        case '09' :
          $sql.=" Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, 

                  Jun integer, Jul integer, Aug integer) ";
          break;
        case '10' :
          $sql.=" Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, 

                  Jul integer, Aug integer, Sep integer) ";
          break;
        case '11' :
          $sql.=" Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, 
                  Aug integer, Sep integer, Oct integer) ";
          break;
        case '12' :
          $sql.=" Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, 

                  Sep integer, Oct integer, Nov integer) ";
          break;
        }
        $sql.=" ) as a
                group by a.iarea, a.icity, a.group, a.nama, a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname
                order by a.iarea, a.icity, a.group, a.kode, a.nama , a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname";
      }
		  $this->db->select($sql,false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
   
}

/* End of file Mmaster.php */
