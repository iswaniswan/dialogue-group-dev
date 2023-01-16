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
            $sql=" a.kode, ";
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

            $sql.=" from ( select kode, ";
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
            ('SELECT (a.i_area||b.i_city) as kode,
            to_number(to_char(a.d_nota, ''mm''),''99'') as bln, 
            sum(a.v_nota_gross) AS jumlah
            FROM tm_nota a, tr_customer b
            WHERE a.f_nota_cancel = false AND b.i_customer = a.i_customer and a.f_nota_cancel=''f''
            AND NOT a.i_nota IS NULL AND NOT b.i_city IS NULL and trim(b.i_city)<>''''
            AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
            GROUP BY b.i_city, a.i_area, to_char(a.d_nota, ''mm'')',
            'select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
            ''$tgl''::date)::date + s.a * ''1 month''::interval))
            from generate_series(0, 11) as s(a)')
            as
            (kode text,";
            switch ($bl){
                case '01' :
                $sql.=" Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, 
                Oct numeric, Nov numeric, Des numeric) ";
                break;
                case '02' :
                $sql.=" Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, 
                Nov numeric, Des numeric, Jan numeric) ";
                break;
                case '03' :
                $sql.=" Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, 
                Des numeric, Jan numeric, Feb numeric) ";
                break;
                case '04' :
                $sql.=" Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, 
                Jan numeric, Feb numeric, Mar numeric) ";
                break;
                case '05' :
                $sql.=" May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, 
                Feb numeric, Mar numeric, Apr numeric) ";
                break;
                case '06' :
                $sql.=" Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, 
                Mar numeric, Apr numeric, May numeric) ";
                break;
                case '07' :
                $sql.=" Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, 
                Apr numeric, May numeric, Jun numeric) ";
                break;
                case '08' :
                $sql.=" Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, 
                May numeric, Jun numeric, Jul numeric) ";
                break;
                case '09' :
                $sql.=" Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, 
                Jun numeric, Jul numeric, Aug numeric) ";
                break;
                case '10' :
                $sql.=" Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, 
                Jul numeric, Aug numeric, Sep numeric) ";
                break;
                case '11' :
                $sql.=" Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, 
                Aug numeric, Sep numeric, Oct numeric) ";
                break;
                case '12' :
                $sql.=" Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, 
                Sep numeric, Oct numeric, Nov numeric) ";
                break;
            }
            $sql.=" ) as a
            group by a.kode
            order by a.kode";
        }else{
            $sql=" a.kode, ";
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

            $sql.=" from ( select kode, ";
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
            ('SELECT (a.i_area||b.i_city) as kode,
            to_number(to_char(a.d_nota, ''mm''),''99'') as bln, 
            sum(a.v_nota_gross) AS jumlah
            FROM tm_nota a, tr_customer b
            WHERE a.f_nota_cancel = false AND b.i_customer = a.i_customer and a.f_nota_cancel=''f''
            AND NOT a.i_nota IS NULL AND NOT b.i_city IS NULL and trim(b.i_city)<>'''' AND a.i_area=''$iarea''
            AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
            GROUP BY b.i_city, a.i_area, to_char(a.d_nota, ''mm'')',
            'select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
            ''$tgl''::date)::date + s.a * ''1 month''::interval))
            from generate_series(0, 11) as s(a)')
            as
            (kode text,";
            switch ($bl){
                case '01' :
                $sql.=" Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, 
                Oct numeric, Nov numeric, Des numeric) ";
                break;
                case '02' :
                $sql.=" Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, 
                Nov numeric, Des numeric, Jan numeric) ";
                break;
                case '03' :
                $sql.=" Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, 
                Des numeric, Jan numeric, Feb numeric) ";
                break;
                case '04' :
                $sql.=" Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, 
                Jan numeric, Feb numeric, Mar numeric) ";
                break;
                case '05' :
                $sql.=" May numeric, Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, 
                Feb numeric, Mar numeric, Apr numeric) ";
                break;
                case '06' :
                $sql.=" Jun numeric, Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, 
                Mar numeric, Apr numeric, May numeric) ";
                break;
                case '07' :
                $sql.=" Jul numeric, Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, 
                Apr numeric, May numeric, Jun numeric) ";
                break;
                case '08' :
                $sql.=" Aug numeric, Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, 
                May numeric, Jun numeric, Jul numeric) ";
                break;
                case '09' :
                $sql.=" Sep numeric, Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, 
                Jun numeric, Jul numeric, Aug numeric) ";
                break;
                case '10' :
                $sql.=" Oct numeric, Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, 
                Jul numeric, Aug numeric, Sep numeric) ";
                break;
                case '11' :
                $sql.=" Nov numeric, Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, 
                Aug numeric, Sep numeric, Oct numeric) ";
                break;
                case '12' :
                $sql.=" Des numeric, Jan numeric, Feb numeric, Mar numeric, Apr numeric, May numeric, Jun numeric, Jul numeric, Aug numeric, 
                Sep numeric, Oct numeric, Nov numeric) ";
                break;
            }
            $sql.=" ) as a
            group by a.kode
            order by a.kode";
        }
        $this->db->select($sql,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}
