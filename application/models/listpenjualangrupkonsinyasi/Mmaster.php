<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

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

    public function bacaperiode($dfrom,$dto,$interval){
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];                
            $bl=$tmp[1];
            $dt=$tmp[0];
            $tgl=$th.'-'.$bl.'-'.$dt;
        }
        $sql=" a.kode, a.nama, a.alamat, a.area, a.iarea, ";
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

        $sql.=" from ( select kode, nama, alamat, area, iarea, ";
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
        ('SELECT a.i_customer_groupar, b.e_customer_name, b.e_customer_address, d.e_area_name, b.i_area,
        to_number(to_char(c.d_nota, ''mm''),''99'') as bln, 
        sum(f.n_deliver*f.v_unit_price) AS jumlah
        FROM tr_customer_groupar a, tr_customer b, tm_nota c, tr_area d, tm_spb e, tm_nota_item f
        WHERE b.i_customer = a.i_customer_groupar and a.i_customer=c.i_customer and c.i_nota=f.i_nota
        AND c.i_nota=e.i_nota AND c.i_area=e.i_area AND e.f_spb_consigment=''t'' AND c.f_nota_cancel=''f''
        AND NOT c.i_nota IS NULL AND b.i_area=c.i_area and b.i_area=d.i_area
        AND (c.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND c.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
        AND c.i_spb=e.i_spb and c.i_area=e.i_area
        GROUP BY a.i_customer_groupar, b.e_customer_name, b.e_customer_address, d.e_area_name, 
        b.i_area,to_char(c.d_nota, ''mm'')
        order by a.i_customer_groupar, b.e_customer_name, to_char(c.d_nota, ''mm'')',
        'select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
        ''$tgl''::date)::date + s.a * ''1 month''::interval))
        from generate_series(0, 11) as s(a)')
        as
        (kode text, nama text, alamat text, area text, iarea text,";
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
        group by a.iarea, a.nama, a.kode, a.alamat, a.area
        order by a.iarea, a.kode, a.nama, a.alamat, a.area";
        $this->db->select($sql,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}
