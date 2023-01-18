<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return '00';
        }else{
            return 'xx';
        }
    }

    public function bacaarea($username, $idcompany, $iarea){
      if ($iarea=='00') {
        return $this->db->query("SELECT * FROM tr_area", FALSE)->result();
      }else{        
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
    }

    public function interval($dfrom,$dto){
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

    function bacaperiode($dfrom,$dto,$iarea,$interval)
    {
      if($dfrom!=''){
				$tmp=explode("-",$dfrom);
        $th=$tmp[2];				
        $bl=$tmp[1];
        $dt=$tmp[0];
        $tgl=$th.'-'.$bl.'-'.$dt;
			}
      if($iarea=='NA'){

        $sql=" a.nama, a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.iarea, a.icity, a.product, a.productname, ";
        switch ($bl){
        case '01' :
          $sql.=" sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 
                  sum(a.Nov) as notanov, sum(a.Des) as notades ";
          break;
        case '02' :
          $sql.=" sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 
                  sum(a.Des) as notades, sum(a.Jan) as notajan ";
          break;
        case '03' :
          $sql.=" sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb ";
          break;
        case '04' :
          $sql.=" sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar ";
          break;
        case '05' :
          $sql.=" sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr ";
          break;
        case '06' :
          $sql.=" sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 
                  sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                  sum(a.Apr) as notaapr, sum(a.May) as notamay ";
          break;
        case '07' :
          $sql.=" sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 
                  sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun ";
          break;
        case '08' :
          $sql.=" sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul ";
          break;

        case '09' :
          $sql.=" sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug ";
          break;
        case '10' :
          $sql.=" sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, 
                  sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep ";
          break;
        case '11' :
          $sql.=" sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                  sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt ";
          break;
        case '12' :
          $sql.=" sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, 
                  sum(a.Oct) as notaokt, sum(a.Nov) as notanov ";
          break;
        }

        $sql.=" from ( select isi[1] as nama, isi[9] as kode, isi[10] as product, isi[2] as alamat, isi[3] as kota, isi[4] as sales, 
isi[5] as area, isi[6] as jenis, isi[7] as iarea, isi[8] as icity, isi[12] as supplier, isi[11] as productname, ";
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

              ('SELECT Array [b.e_customer_name::text, b.e_customer_address::text, b.e_customer_city::text, a.i_salesman::text, 
                d.e_area_name::text, e.e_customer_classname::text, a.i_area::text, b.i_city::text, a.i_customer::text, f.i_product::text, 
                f.e_product_name, h.e_supplier_name::text, g.i_supplier::text] as isi, to_number(to_char(a.d_nota, ''mm''),''99'') as bln, 
                sum(round(f.n_deliver*f.v_unit_price-(((f.n_deliver*f.v_unit_price)/a.v_nota_gross)*a.v_nota_discounttotal))) AS jumlah  
                FROM tm_nota a, tr_customer b, tr_area d, tr_customer_class e, tm_nota_item f, tr_product g, tr_supplier h
                WHERE a.f_nota_cancel = false AND b.i_customer = a.i_customer and a.f_nota_cancel=''f'' 
                AND a.i_sj=f.i_sj AND a.i_area=f.i_area AND f.i_product=g.i_product AND g.i_supplier=h.i_supplier
                AND NOT a.i_nota IS NULL and b.i_area=d.i_area
                AND b.i_customer_class=e.i_customer_class
                AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                GROUP BY b.e_customer_name, a.i_customer, b.e_customer_address, b.e_customer_city, a.i_salesman, d.e_area_name, 
                e.e_customer_classname, a.i_area, b.i_city, f.i_product, f.e_product_name, h.e_supplier_name, g.i_supplier,
                to_char(a.d_nota, ''mm'')
                order by a.i_customer, b.e_customer_name, to_char(a.d_nota, ''mm'')','select (SELECT EXTRACT(MONTH FROM 
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
                group by a.iarea, a.icity, a.nama, a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname
                order by a.iarea, a.icity, a.kode, a.nama, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname";
      }else{
        $sql=" a.nama, a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.iarea, a.icity, a.product, a.productname, ";
        switch ($bl){
        case '01' :
          $sql.=" sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 

                  sum(a.Nov) as notanov, sum(a.Des) as notades ";
          break;
        case '02' :
          $sql.=" sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 

                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 
                  sum(a.Des) as notades, sum(a.Jan) as notajan ";
          break;
        case '03' :
          $sql.=" sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 

                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb ";
          break;
        case '04' :
          $sql.=" sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 


                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar ";
          break;
        case '05' :
          $sql.=" sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 

                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr ";
          break;
        case '06' :
          $sql.=" sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 
                  sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 

                  sum(a.Apr) as notaapr, sum(a.May) as notamay ";
          break;
        case '07' :
          $sql.=" sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 

                  sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun ";
          break;
        case '08' :
          $sql.=" sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul ";
          break;
        case '09' :
          $sql.=" sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 

                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug ";
          break;
        case '10' :

          $sql.=" sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, 
                  sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 

                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep ";
          break;
        case '11' :
          $sql.=" sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 

                  sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt ";
          break;
        case '12' :
          $sql.=" sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, 
                  sum(a.Oct) as notaokt, sum(a.Nov) as notanov ";
          break;
        }

        $sql.=" from ( select isi[1] as nama, isi[9] as kode, isi[10] as product, isi[2] as alamat, isi[3] as kota, isi[4] as sales, 
isi[5] as area, isi[6] as jenis, isi[7] as iarea, isi[8] as icity, isi[12] as supplier, isi[11] as productname, ";
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
                f.e_product_name, h.e_supplier_name::text, g.i_supplier::text] as isi, to_number(to_char(a.d_nota, ''mm''),''99'') as bln, 
                sum(round(f.n_deliver*f.v_unit_price-(((f.n_deliver*f.v_unit_price)/a.v_nota_gross)*a.v_nota_discounttotal))) AS jumlah  
                FROM tm_nota a, tr_customer b, tr_city c, tr_area d, tr_customer_class e, tm_nota_item f, tr_product g, tr_supplier h
                WHERE a.f_nota_cancel = false AND b.i_customer = a.i_customer and a.f_nota_cancel=''f'' 
                AND a.i_sj=f.i_sj AND a.i_area=f.i_area AND a.i_area=''$iarea''
                AND NOT a.i_nota IS NULL and b.i_city=c.i_city and b.i_area=c.i_area and b.i_area=d.i_area
                AND b.i_customer_class=e.i_customer_class AND f.i_product=g.i_product AND g.i_supplier=h.i_supplier
                AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                GROUP BY b.e_customer_name, a.i_customer, b.e_customer_address, c.e_city_name, a.i_salesman, d.e_area_name, 
                e.e_customer_classname, a.i_area, b.i_city, f.i_product, f.e_product_name, g.i_supplier, h.e_supplier_name,
                to_char(a.d_nota, ''mm'')
                order by a.i_customer, b.e_customer_name, to_char(a.d_nota, ''mm'')','select (SELECT EXTRACT(MONTH FROM 
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
                group by a.iarea, a.icity, a.nama, a.kode, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname
                order by a.iarea, a.icity, a.kode, a.nama, a.alamat, a.kota, a.sales, a.area, a.supplier, a.jenis, a.product, a.productname";
      }
		  $this->db->select($sql,false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
}
