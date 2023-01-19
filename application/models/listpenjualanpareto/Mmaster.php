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

        $sql=" a.nama, a.kode, a.alamat, a.kota, a.area, a.jenis, a.top, a.tgldaftar,";
        switch ($bl){
        case '01' :
          $sql.=" sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 
                  sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, 
                  sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, 
                  sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes ";
          break;
        case '02' :
          $sql.=" sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 
                  sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, 
                  sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, 
                  sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan ";
          break;
        case '03' :
          $sql.=" sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, 
                  sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, 
                  sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb ";
          break;
        case '04' :
          $sql.=" sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, 
                  sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, 
                  sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar ";
          break;
        case '05' :
          $sql.=" sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, 
                  sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, 
                  sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr ";
          break;
        case '06' :
          $sql.=" sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 
                  sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                  sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, 
                  sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, 
                  sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay ";
          break;
        case '07' :
          $sql.=" sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 
                  sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, 
                  sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, 
                  sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun ";
          break;
        case '08' :
          $sql.=" sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, 
                  sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, 
                  sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul ";
          break;
        case '09' :
          $sql.=" sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, 
                  sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, 
                  sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug ";
          break;
        case '10' :
          $sql.=" sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, 
                  sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, 
                  sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, 
                  sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep ";
          break;
        case '11' :
          $sql.=" sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                  sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, 
                  sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, 
                  sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt ";
          break;
        case '12' :
          $sql.=" sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, 
                  sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, 
                  sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, 
                  sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov ";
          break;
        }

        $sql.=" from ( select nama, kode, alamat, kota, area, jenis, top, tgldaftar, ";
        switch ($bl){
        case '01' :
          $sql.=" 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 
                  0 as Nv, 0 as Ds, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des ";
          break;
        case '02' :
          $sql.=" 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 
                  0 as Ds, 0 as Ja, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan ";
          break;
        case '03' :
          $sql.=" 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 
                  0 as Ja, 0 as Fb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb ";
          break;
        case '04' :
          $sql.=" 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 
                  0 as Fb, 0 as Mr, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar ";
          break;
        case '05' :
          $sql.=" 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 
                  0 as Mr, 0 as Ap, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr ";
          break;
        case '06' :
          $sql.=" 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 
                  0 as Ap, 0 as Me, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May ";
          break;
        case '07' :
          $sql.=" 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 
                  0 as Me, 0 as Jn, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun ";
          break;
        case '08' :
          $sql.=" 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 
                  0 as Jn, 0 as Jl, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul ";
          break;
        case '09' :
          $sql.=" 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 
                  0 as Jl, 0 as Ag, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug ";
          break;
        case '10' :
          $sql.=" 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 
                  0 as Ag, 0 as Sp, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep ";
          break;
        case '11' :
          $sql.=" 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 
                  0 as Sp, 0 as Ok, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct ";
          break;
        case '12' :
          $sql.=" 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 
                  0 as Ok, 0 as Nv, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov ";
          break;
        }
        $sql.=" from crosstab
              ('SELECT b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_nota_toplength as text) as top, b.d_signin, 
              to_number(to_char(a.d_nota, ''mm''),''99'') as bln, 
              sum(a.v_nota_gross) AS jumlah
              FROM tr_city c, tr_area d, tr_customer_class e, tr_customer b
              left join tm_nota a on (a.f_nota_cancel = false AND b.i_customer = a.i_customer AND NOT a.i_nota IS NULL
                                      AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy'')))
              WHERE b.f_pareto=''t''
              and b.i_city=c.i_city and b.i_area=c.i_area and b.i_area=d.i_area
              AND b.i_customer_class=e.i_customer_class
              GROUP BY b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_nota_toplength as text), b.d_signin, 
              to_char(a.d_nota, ''mm'')
              order by b.i_customer, b.e_customer_name, to_char(a.d_nota, ''mm'')','select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
              ''$tgl''::date)::date + s.a * ''1 month''::interval))
              from generate_series(0, 11) as s(a)')
              as
              (nama text, kode text, alamat text, kota text, area text, jenis text, top text, tgldaftar date, ";
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
 
        $sql.=" Union ALL ";
 
        $sql.=" select nama, kode, alamat, kota, area, jenis, top, tgldaftar, ";
        switch ($bl){
        case '01' :
          $sql.=" Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 
                  0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds ";
          break;
        case '02' :
          $sql.=" Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 
                  0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja ";
          break;
        case '03' :
          $sql.=" Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 
                  0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb ";
          break;
        case '04' :
          $sql.=" Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 
                  0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr ";
          break;
        case '05' :
          $sql.=" May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 
                  0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap ";
          break;
        case '06' :
          $sql.=" Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 
                  0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me ";
          break;
        case '07' :
          $sql.=" Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 
                  0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn ";
          break;
        case '08' :
          $sql.=" Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 
                  0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl ";
          break;
        case '09' :
          $sql.=" Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 
                  0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag ";
          break;
        case '10' :
          $sql.=" Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 
                  0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp ";
          break;
        case '11' :
          $sql.=" Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 
                  0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok ";
          break;
        case '12' :
          $sql.=" 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 
                  0 as Ok, 0 as Nv, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov ";
          break;
        }
        $sql.=" from crosstab
              ('SELECT b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_spb_toplength as text) as top, b.d_signin,
              to_number(to_char(a.d_spb, ''mm''),''99'') as bln, 
              sum(a.v_spb) AS jumlah
              FROM tr_city c, tr_area d, tr_customer_class e, tr_customer b
              left join tm_spb a on (a.f_spb_cancel = false AND b.i_customer = a.i_customer 
              AND (a.d_spb >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_spb <= to_date(''$dto'',''dd-mm-yyyy'')))
              WHERE b.f_pareto=''t''
              AND b.i_city=c.i_city and b.i_area=c.i_area and b.i_area=d.i_area
              AND b.i_customer_class=e.i_customer_class
              GROUP BY b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_spb_toplength as text), b.d_signin, 
              to_char(a.d_spb, ''mm'')
              order by b.i_customer, b.e_customer_name, to_char(a.d_spb, ''mm'')','select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
              ''$tgl''::date)::date + s.a * ''1 month''::interval))
              from generate_series(0, 11) as s(a)')
              as
              (nama text, kode text, alamat text, kota text, area text, jenis text, top text, tgldaftar date, ";
        switch ($bl){
        case '01' :
          $sql.="Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, 
                 Oct integer, Nov integer, Des integer) ";
          break;
        case '02' :
          $sql.="Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, 
                 Nov integer, Des integer, Jan integer) ";
          break;
        case '03' :
          $sql.="Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, 
                 Des integer, Jan integer, Feb integer) ";
          break;
        case '04' :
          $sql.="Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, 
                 Jan integer, Feb integer, Mar integer) ";
          break;
        case '05' :
          $sql.="May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, 
                 Feb integer, Mar integer, Apr integer) ";
          break;
        case '06' :
          $sql.="Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, 
                 Mar integer, Apr integer, May integer) ";
          break;
        case '07' :
          $sql.="Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, 
                 Apr integer, May integer, Jun integer) ";
          break;
        case '08' :
          $sql.="Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, 
                 May integer, Jun integer, Jul integer) ";
          break;
        case '09' :
          $sql.="Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, 
                 Jun integer, Jul integer, Aug integer) ";
          break;
        case '10' :
          $sql.="Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, 
                 Jul integer, Aug integer, Sep integer) ";
          break;
        case '11' :
          $sql.="Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, 
                 Aug integer, Sep integer, Oct integer) ";
          break;
        case '12' :
          $sql.="Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, 
                 Sep integer, Oct integer, Nov integer) ";
          break;
        }
        $sql.=" ) as a
                where not a.top isnull
                group by a.nama, a.kode, a.alamat, a.kota, a.area, a.jenis, a.top, a.tgldaftar
                order by a.kode, a.nama, a.alamat, a.kota, a.area, a.jenis";

#####perarea
      }else{
        $sql=" a.nama, a.kode, a.alamat, a.kota, a.area, a.jenis, a.top, a.tgldaftar,";
        switch ($bl){
        case '01' :
          $sql.=" sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 
                  sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, 
                  sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, 
                  sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes ";
          break;
        case '02' :
          $sql.=" sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 
                  sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, 
                  sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, 
                  sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan ";
          break;
        case '03' :
          $sql.=" sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, 
                  sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, 
                  sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb ";
          break;
        case '04' :
          $sql.=" sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, 
                  sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, 
                  sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar ";
          break;
        case '05' :
          $sql.=" sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, 
                  sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, 

                  sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr ";
          break;
        case '06' :
          $sql.=" sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, 
                  sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                  sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, 
                  sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, 
                  sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay ";
          break;
        case '07' :
          $sql.=" sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, 
                  sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, 
                  sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, 
                  sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun ";
          break;
        case '08' :
          $sql.=" sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, 
                  sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, 
                  sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, 
                  sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, 
                  sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul ";
          break;
        case '09' :
          $sql.=" sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, 
                  sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, 
                  sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, 
                  sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, 
                  sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug ";
          break;
        case '10' :
          $sql.=" sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, 
                  sum(a.Mar) as notamar, sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, 
                  sum(a.Aug) as notaaug, sum(a.Sep) as notasep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, 
                  sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, 
                  sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep ";
          break;
        case '11' :
          $sql.=" sum(a.Nov) as notanov, sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, 
                  sum(a.Apr) as notaapr, sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, 
                  sum(a.Sep) as notasep, sum(a.Oct) as notaokt, sum(a.Nv) as spbnov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, 
                  sum(a.Fb) as spbfeb, sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, 

                  sum(a.Jl) as spbjul, sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt ";
          break;
        case '12' :
          $sql.=" sum(a.Des) as notades, sum(a.Jan) as notajan, sum(a.Feb) as notafeb, sum(a.Mar) as notamar, sum(a.Apr) as notaapr, 
                  sum(a.May) as notamay, sum(a.Jun) as notajun, sum(a.Jul) as notajul, sum(a.Aug) as notaaug, sum(a.Sep) as notasep, 
                  sum(a.Oct) as notaokt, sum(a.Nov) as notanov, sum(a.Ds) as spbdes, sum(a.Ja) as spbjan, sum(a.Fb) as spbfeb, 
                  sum(a.Mr) as spbmar, sum(a.Ap) as spbapr, sum(a.Me) as spbmay, sum(a.Jn) as spbjun, sum(a.Jl) as spbjul, 
                  sum(a.Ag) as spbaug, sum(a.Sp) as spbsep, sum(a.Ok) as spbokt, sum(a.Nv) as spbnov ";
          break;
        }

        $sql.=" from ( select nama, kode, alamat, kota, area, jenis, top, tgldaftar, ";
        switch ($bl){
        case '01' :
          $sql.=" 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 
                  0 as Nv, 0 as Ds, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des ";
          break;
        case '02' :
          $sql.=" 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 
                  0 as Ds, 0 as Ja, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan ";
          break;
        case '03' :
          $sql.=" 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 
                  0 as Ja, 0 as Fb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb ";
          break;
        case '04' :
          $sql.=" 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 
                  0 as Fb, 0 as Mr, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar ";
          break;
        case '05' :
          $sql.=" 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 
                  0 as Mr, 0 as Ap, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr ";
          break;
        case '06' :
          $sql.=" 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 
                  0 as Ap, 0 as Me, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May ";
          break;
        case '07' :
          $sql.=" 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 
                  0 as Me, 0 as Jn, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun ";
          break;
        case '08' :
          $sql.=" 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 
                  0 as Jn, 0 as Jl, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul ";
          break;
        case '09' :
          $sql.=" 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 
                  0 as Jl, 0 as Ag, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug ";
          break;
        case '10' :
          $sql.=" 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 
                  0 as Ag, 0 as Sp, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep ";
          break;
        case '11' :
          $sql.=" 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 
                  0 as Sp, 0 as Ok, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct ";
          break;
        case '12' :
          $sql.=" 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 
                  0 as Ok, 0 as Nv, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov ";
          break;
        }
        $sql.=" from crosstab
              ('SELECT b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_nota_toplength as text) as top, b.d_signin, 
              to_number(to_char(a.d_nota, ''mm''),''99'') as bln, 
              sum(a.v_nota_gross) AS jumlah
              FROM tr_city c, tr_area d, tr_customer_class e, tr_customer b
              left join tm_nota a on (a.f_nota_cancel = false AND b.i_customer = a.i_customer AND NOT a.i_nota IS NULL
                                      AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy'')))
              WHERE b.f_pareto=''t'' and b.i_area=''$iarea''
              and b.i_city=c.i_city and b.i_area=c.i_area and b.i_area=d.i_area
              AND b.i_customer_class=e.i_customer_class
              GROUP BY b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_nota_toplength as text), b.d_signin, 
              to_char(a.d_nota, ''mm'')
              order by b.i_customer, b.e_customer_name, to_char(a.d_nota, ''mm'')','select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
              ''$tgl''::date)::date + s.a * ''1 month''::interval))
              from generate_series(0, 11) as s(a)')
              as
              (nama text, kode text, alamat text, kota text, area text, jenis text, top text, tgldaftar date, ";
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
 
        $sql.=" Union ALL ";
 
        $sql.=" select nama, kode, alamat, kota, area, jenis, top, tgldaftar, ";
        switch ($bl){
        case '01' :
          $sql.=" Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 
                  0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds ";
          break;
        case '02' :
          $sql.=" Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 
                  0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja ";
          break;
        case '03' :
          $sql.=" Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 
                  0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb ";
          break;
        case '04' :
          $sql.=" Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 
                  0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr ";
          break;
        case '05' :
          $sql.=" May, Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 
                  0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap ";
          break;
        case '06' :
          $sql.=" Jun, Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 
                  0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me ";
          break;
        case '07' :
          $sql.=" Jul, Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok, 
                  0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn ";
          break;
        case '08' :
          $sql.=" Aug, Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, 0 as Ag, 0 as Sp, 0 as Ok, 0 as Nv, 
                  0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl ";
          break;
        case '09' :
          $sql.=" Sep, Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, 0 as Sp, 0 as Ok, 0 as Nv, 0 as Ds, 
                  0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag ";
          break;
        case '10' :
          $sql.=" Oct, Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, 0 as Ok, 0 as Nv, 0 as Ds, 0 as Ja, 
                  0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp ";
          break;
        case '11' :
          $sql.=" Nov, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, 0 as Nv, 0 as Ds, 0 as Ja, 0 as Fb, 
                  0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 0 as Ok ";
          break;
        case '12' :
          $sql.=" 0 as Ds, 0 as Ja, 0 as Fb, 0 as Mr, 0 as Ap, 0 as Me, 0 as Jn, 0 as Jl, 0 as Ag, 0 as Sp, 
                  0 as Ok, 0 as Nv, Des, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov ";
          break;
        }
        $sql.=" from crosstab
              ('SELECT b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_spb_toplength as text) as top, b.d_signin,
              to_number(to_char(a.d_spb, ''mm''),''99'') as bln, 
              sum(a.v_spb) AS jumlah
              FROM tr_city c, tr_area d, tr_customer_class e, tr_customer b
              left join tm_spb a on (a.f_spb_cancel = false AND b.i_customer = a.i_customer 
              AND (a.d_spb >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_spb <= to_date(''$dto'',''dd-mm-yyyy'')))
              WHERE b.f_pareto=''t'' and b.i_area=''$iarea''
              AND b.i_city=c.i_city and b.i_area=c.i_area and b.i_area=d.i_area
              AND b.i_customer_class=e.i_customer_class
              GROUP BY b.e_customer_name, b.i_customer, b.e_customer_address, c.e_city_name, d.e_area_name, 
              e.e_customer_classname, cast(a.n_spb_toplength as text), b.d_signin, 
              to_char(a.d_spb, ''mm'')
              order by b.i_customer, b.e_customer_name, to_char(a.d_spb, ''mm'')','select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
              ''$tgl''::date)::date + s.a * ''1 month''::interval))
              from generate_series(0, 11) as s(a)')
              as
              (nama text, kode text, alamat text, kota text, area text, jenis text, top text, tgldaftar date, ";
        switch ($bl){
        case '01' :
          $sql.="Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, 
                 Oct integer, Nov integer, Des integer) ";
          break;
        case '02' :
          $sql.="Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, 
                 Nov integer, Des integer, Jan integer) ";
          break;
        case '03' :
          $sql.="Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, 
                 Des integer, Jan integer, Feb integer) ";
          break;
        case '04' :
          $sql.="Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, 
                 Jan integer, Feb integer, Mar integer) ";
          break;
        case '05' :
          $sql.="May integer, Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, 
                 Feb integer, Mar integer, Apr integer) ";
          break;
        case '06' :
          $sql.="Jun integer, Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, 
                 Mar integer, Apr integer, May integer) ";
          break;
        case '07' :
          $sql.="Jul integer, Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, 
                 Apr integer, May integer, Jun integer) ";
          break;
        case '08' :
          $sql.="Aug integer, Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, 
                 May integer, Jun integer, Jul integer) ";
          break;
        case '09' :
          $sql.="Sep integer, Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, 
                 Jun integer, Jul integer, Aug integer) ";
          break;
        case '10' :
          $sql.="Oct integer, Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, 
                 Jul integer, Aug integer, Sep integer) ";
          break;
        case '11' :
          $sql.="Nov integer, Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, 
                 Aug integer, Sep integer, Oct integer) ";
          break;
        case '12' :
          $sql.="Des integer, Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, 
                 Sep integer, Oct integer, Nov integer) ";
          break;
        }
        $sql.=" ) as a
                where not a.top isnull
                group by a.nama, a.kode, a.alamat, a.kota, a.area, a.jenis, a.top, a.tgldaftar
                order by a.kode, a.nama, a.alamat, a.kota, a.area, a.jenis";
      }
		  $this->db->select($sql,false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
   
}

/* End of file Mmaster.php */
