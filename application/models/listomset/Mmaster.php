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

    public function area($username, $idcompany){
        return  $this->db->query("
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
        ", FALSE);
    }

    public function interval($dfrom,$dto){
        if($dfrom!=''){
            $dfrom = date('Y-m-d', strtotime($dfrom));
        }
        if($dto!=''){
            $dto = date('Y-m-d', strtotime($dto));
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
        $bl  = date('m', strtotime($dfrom));
        $tgl = date('Y-m-d', strtotime($dfrom));
        if($iarea=='NA'){            
            $sql=" a.area, a.iarea, ";
            switch ($bl){
                case '01' :
                $sql.=" sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, 
                sum(a.Nov) as spbnov, sum(a.Des) as spbdes ";
                break;
                case '02' :
                $sql.=" sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 
                sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, 
                sum(a.Des) as spbdes, sum(a.Jan) as spbjan ";
                break;
                case '03' :
                $sql.=" sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 
                sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 
                sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb ";
                break;
                case '04' :
                $sql.=" sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar ";
                break;
                case '05' :
                $sql.=" sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr ";
                break;
                case '06' :
                $sql.=" sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, 
                sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 
                sum(a.Apr) as spbapr, sum(a.May) as spbmay ";
                break;
                case '07' :
                $sql.=" sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, 
                sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 
                sum(a.May) as spbmay, sum(a.Jun) as spbjun ";
                break;
                case '08' :
                $sql.=" sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 
                sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                sum(a.Jun) as spbjun, sum(a.Jul) as spbjul ";
                break;
                case '09' :
                $sql.=" sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 
                sum(a.Jul) as spbjul, sum(a.Aug) as spbaug ";
                break;
                case '10' :
                $sql.=" sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, 
                sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 
                sum(a.Aug) as spbaug, sum(a.Sep) as spbsep ";
                break;
                case '11' :
                $sql.=" sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 
                sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                sum(a.Sep) as spbsep, sum(a.Oct) as spboct ";
                break;
                case '12' :
                $sql.=" sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 
                sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, 
                sum(a.Oct) as spboct, sum(a.Nov) as spbnov ";
                break;
            }

            $sql.=" from ( select area, iarea, ";
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
            $sql.=" from public.crosstab
            ('SELECT d.e_area_name, a.i_area, to_number(to_char(a.d_spb, ''mm''),''99'') as bln, sum(a.v_spb) AS jumlah
            FROM tm_spb a, tr_area d
            WHERE a.f_spb_cancel = false AND a.i_area=d.i_area 
            AND (a.d_spb >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_spb <= to_date(''$dto'',''dd-mm-yyyy''))
            GROUP BY d.e_area_name, a.i_area,
            to_char(a.d_spb, ''mm'')
            order by a.i_area','select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
            ''$tgl''::date)::date + s.a * ''1 month''::interval))
            from generate_series(0, 11) as s(a)')
            as
            (area text, iarea text,";
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
            group by a.area, a.iarea
            order by a.iarea";
        }else{
            $sql=" a.area, a.iarea, ";
            switch ($bl){
                case '01' :
                $sql.=" sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, 
                sum(a.Nov) as spbnov, sum(a.Des) as spbdes ";
                break;
                case '02' :
                $sql.=" sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 
                sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, 
                sum(a.Des) as spbdes, sum(a.Jan) as spbjan ";
                break;
                case '03' :
                $sql.=" sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 
                sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 
                sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb ";
                break;
                case '04' :
                $sql.=" sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar ";
                break;
                case '05' :
                $sql.=" sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr ";
                break;
                case '06' :
                $sql.=" sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, 
                sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 
                sum(a.Apr) as spbapr, sum(a.May) as spbmay ";
                break;
                case '07' :
                $sql.=" sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, 
                sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 
                sum(a.May) as spbmay, sum(a.Jun) as spbjun ";
                break;
                case '08' :
                $sql.=" sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, 
                sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, 
                sum(a.Jun) as spbjun, sum(a.Jul) as spbjul ";
                break;
                case '09' :
                $sql.=" sum(a.Sep) as spbsep, sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, 
                sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, 
                sum(a.Jul) as spbjul, sum(a.Aug) as spbaug ";
                break;
                case '10' :
                $sql.=" sum(a.Oct) as spboct, sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, 
                sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, 
                sum(a.Aug) as spbaug, sum(a.Sep) as spbsep ";
                break;
                case '11' :
                $sql.=" sum(a.Nov) as spbnov, sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, 
                sum(a.Apr) as spbapr, sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, 
                sum(a.Sep) as spbsep, sum(a.Oct) as spboct ";
                break;
                case '12' :
                $sql.=" sum(a.Des) as spbdes, sum(a.Jan) as spbjan, sum(a.Feb) as spbfeb, sum(a.Mar) as spbmar, sum(a.Apr) as spbapr, 

                sum(a.May) as spbmay, sum(a.Jun) as spbjun, sum(a.Jul) as spbjul, sum(a.Aug) as spbaug, sum(a.Sep) as spbsep, 
                sum(a.Oct) as spboct, sum(a.Nov) as spbnov ";
                break;
            }

            $sql.=" from ( select area, iarea, ";
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
            $sql.=" from public.crosstab
            ('SELECT d.e_area_name, a.i_area, to_number(to_char(a.d_spb, ''mm''),''99'') as bln, sum(a.v_spb) AS jumlah
            FROM tm_spb a, tr_area d
            WHERE a.f_spb_cancel = false AND a.i_area=d.i_area and a.i_area=''$iarea''
            AND (a.d_spb >= to_date(''$dfrom'',''dd-mm-yyyy'') AND a.d_spb <= to_date(''$dto'',''dd-mm-yyyy''))
            GROUP BY d.e_area_name, a.i_area,
            to_char(a.d_spb, ''mm'')
            order by a.i_area','select (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
            ''$tgl''::date)::date + s.a * ''1 month''::interval))
            from generate_series(0, 11) as s(a)')
            as
            (area text, iarea text,";
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
            group by a.area, a.iarea
            order by a.iarea";
        }
        $this->db->select($sql,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
