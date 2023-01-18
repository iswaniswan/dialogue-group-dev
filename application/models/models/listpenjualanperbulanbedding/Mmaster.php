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

    public function bacaperiode($dfrom,$dto,$iarea,$interval){
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];                
            $bl=$tmp[1];
            $dt=$tmp[0];
            $tgl=$th.'-'.$bl.'-'.$dt;
        }
        if($iarea=='NA'){
            $sql=" a.kode, a.nama, a.alamat, a.kota, a.area, a.jenis, a.iarea, a.icity, ";
            switch ($bl){
                case '01' :
                $sql.=" sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, 
                sum(a.Nov) as notanov, sum(a.Des) as notades ";
                break;
                case '02' :
                $sql.=" sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, 
                sum(a.Des) as notades, sum(a.Jan) as notajan ";
                break;
                case '03' :
                $sql.=" sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                sum(a.Jan) as notajan, sum(a.Feb) as notafeb ";
                break;
                case '04' :
                $sql.=" sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                sum(a.Feb) as notafeb, sum(a.Mar) as notamar ";
                break;
                case '05' :
                $sql.=" sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr ";
                break;
                case '06' :
                $sql.=" sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, 
                sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                sum(a.Apr) as notaapr, sum(a.May) as notamay ";
                break;
                case '07' :
                $sql.=" sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, 
                sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                sum(a.May) as notamay, sum(a.Jun) as notajun ";
                break;
                case '08' :
                $sql.=" sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                sum(a.Jun) as notajun, sum(a.Jul) as notajul ";
                break;
                case '09' :
                $sql.=" sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                sum(a.Jul) as notajul, sum(a.Aug) as notaaug ";
                break;
                case '10' :
                $sql.=" sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, 
                sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                sum(a.Aug) as notaaug, sum(a.Sep) as notasep ";
                break;
                case '11' :
                $sql.=" sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                sum(a.Sep) as notasep, sum(a.Oct) as notaoct ";
                break;
                case '12' :
                $sql.=" sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, 
                sum(a.Oct) as notaoct, sum(a.Nov) as notanov ";
                break;
            }

            $sql.=" from ( select kode, nama, alamat, kota, area, jenis, iarea, icity, ";
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
            ('SELECT a.i_customer, b.e_customer_name, b.e_customer_address, c.e_city_name, d.e_area_name, e.e_customer_classname, 
            a.i_area, b.i_city, to_number(to_char(a.d_nota, ''mm''), ''99'') as bln, sum(f.n_deliver*f.v_unit_price) AS jumlah
            FROM tm_nota a, tr_customer b, tr_city c, tr_area d, tr_customer_class e, tm_nota_item f, tr_product g, tr_product_type h, 
            tm_spb i
            WHERE a.f_nota_cancel = false AND b.i_customer = a.i_customer and a.f_nota_cancel=''f'' AND NOT a.i_nota IS NULL 
            and b.i_city=c.i_city and b.i_area=c.i_area 
            and b.i_area=d.i_area AND b.i_customer_class=e.i_customer_class
            and a.i_sj=f.i_sj and a.i_area=f.i_area and f.i_product=g.i_product and g.i_product_type=h.i_product_type 
            and h.i_product_group=''01'' and a.i_spb=i.i_spb and a.i_area=i.i_area
            and i.f_spb_consigment=''f''
            AND (a.d_nota >= to_date(''$dfrom'', ''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'', ''dd-mm-yyyy'')) 
            GROUP BY a.i_customer, b.e_customer_name, b.e_customer_address, c.e_city_name, d.e_area_name, e.e_customer_classname, 
            a.i_area, b.i_city, to_char(a.d_nota, ''mm'') 
            order by a.i_customer, b.e_customer_name, to_char(a.d_nota, ''mm'')',
            'select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
            ''$tgl''::date)::date + s.a * ''1 month''::interval))
            from generate_series(0, 11) as s(a)')
            as
            (kode text, nama text, alamat text, kota text, area text, jenis text, iarea text, icity text,";
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
            group by a.iarea, a.icity, a.nama, a.kode, a.alamat, a.kota, a.area, a.jenis
            order by a.iarea, a.icity, a.kode, a.nama, a.alamat, a.kota, a.area, a.jenis";
#                order by a.kode, a.nama, a.alamat, a.kota, a.sales, a.area, a.jenis";
        }else{
            $sql=" a.kode, a.nama, a.alamat, a.kota, a.area, a.jenis, a.iarea, a.icity, ";
            switch ($bl){
                case '01' :
                $sql.=" sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, 
                sum(a.Nov) as notanov, sum(a.Des) as notades ";
                break;
                case '02' :
                $sql.=" sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, 
                sum(a.Des) as notades, sum(a.Jan) as notajan ";
                break;
                case '03' :
                $sql.=" sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                sum(a.Jan) as notajan, sum(a.Feb) as notafeb ";
                break;
                case '04' :
                $sql.=" sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 

                sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                sum(a.Feb) as notafeb, sum(a.Mar) as notamar ";
                break;
                case '05' :
                $sql.=" sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 

                sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr ";
                break;
                case '06' :
                $sql.=" sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, 
                sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                sum(a.Apr) as notaapr, sum(a.May) as notamay ";
                break;
                case '07' :
                $sql.=" sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, 
                sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                sum(a.May) as notamay, sum(a.Jun) as notajun ";
                break;
                case '08' :
                $sql.=" sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 

                sum(a.Jun) as notajun, sum(a.Jul) as notajul ";
                break;
                case '09' :
                $sql.=" sum(a.Sep) as notasep, sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 

                sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                sum(a.Jul) as notajul, sum(a.Aug) as notaaug ";
                break;
                case '10' :
                $sql.=" sum(a.Oct) as notaoct, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, 

                sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                sum(a.Aug) as notaaug, sum(a.Sep) as notasep ";
                break;
                case '11' :
                $sql.=" sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                sum(a.Sep) as notasep, sum(a.Oct) as notaoct ";
                break;
                case '12' :
                $sql.=" sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, 
                sum(a.Oct) as notaoct, sum(a.Nov) as notanov ";
                break;
            }

            $sql.=" from ( select kode, nama, alamat, kota, area, jenis, iarea, icity, ";
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
            ('SELECT a.i_customer, b.e_customer_name, b.e_customer_address, c.e_city_name, d.e_area_name, e.e_customer_classname, 
            a.i_area, b.i_city, to_number(to_char(a.d_nota, ''mm''), ''99'') as bln, sum(f.n_deliver*f.v_unit_price) AS jumlah
            FROM tm_nota a, tr_customer b, tr_city c, tr_area d, tr_customer_class e, tm_nota_item f, tr_product g, tr_product_type h, 
            tm_spb i
            WHERE a.f_nota_cancel = false AND b.i_customer = a.i_customer and a.f_nota_cancel=''f'' AND NOT a.i_nota IS NULL 
            and b.i_city=c.i_city and b.i_area=c.i_area 
            and b.i_area=d.i_area AND b.i_customer_class=e.i_customer_class AND a.i_area=''$iarea'' 
            and a.i_sj=f.i_sj and a.i_area=f.i_area and f.i_product=g.i_product and g.i_product_type=h.i_product_type 
            and h.i_product_group=''01'' and a.i_spb=i.i_spb and a.i_area=i.i_area
            and i.f_spb_consigment=''f''
            AND (a.d_nota >= to_date(''$dfrom'', ''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'', ''dd-mm-yyyy'')) 
            GROUP BY a.i_customer, b.e_customer_name, b.e_customer_address, c.e_city_name, d.e_area_name, e.e_customer_classname, 
            a.i_area, b.i_city, to_char(a.d_nota, ''mm'') 
            order by a.i_customer, b.e_customer_name, to_char(a.d_nota, ''mm'')','select (SELECT EXTRACT(MONTH FROM date_trunc
            (''month'',''$tgl''::date)::date + s.a * ''1 month''::interval))
            from generate_series(0, 11) as s(a)')
            as
            (kode text, nama text, alamat text, kota text, area text, jenis text, iarea text, icity text,";
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
            group by a.iarea, a.icity, a.nama, a.kode, a.alamat, a.kota, a.area, a.jenis
            order by a.iarea, a.icity, a.kode, a.nama, a.alamat, a.kota, a.area, a.jenis";
        }
        $this->db->select($sql,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}
