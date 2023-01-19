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
            $periode = $th.$bl;
        }
        if($iarea=='NA'){
            $sql="  * 
                    FROM CROSSTAB
                    ('SELECT 
                        f.e_customer_classname||b.i_customer||b.e_customer_name||b.e_customer_address||c.e_city_name||d.e_salesman_name as komplit, 
                        f.e_customer_classname, 
                        b.i_customer, 
                        b.e_customer_name, 
                        b.e_customer_address, 
                        c.e_city_name, 
                        d.e_salesman_name,
                        to_number(to_char(a.d_nota, ''mm''),''99'') AS bln, 
                        sum(a.v_nota_gross) AS jumlah
                    FROM 
                        tm_nota a, 
                        tr_customer b, 
                        tr_city c, 
                        tr_customer_salesman d, 
                        tm_spb e, 
                        tr_customer_class f
                    WHERE 
                        a.f_nota_cancel = false 
                        AND b.i_customer = a.i_customer 
                        AND b.i_customer_class = f.i_customer_class 
                        AND NOT a.i_nota IS NULL 
                        AND b.i_city=c.i_city 
                        AND b.i_area=c.i_area 
                        AND a.i_customer = d.i_customer 
                        AND a.i_area = d.i_area 
                        AND d.e_periode =''$periode'' 
                        AND a.i_spb = e.i_spb 
                        AND a.i_area = e.i_area 
                        AND e.i_product_group = d.i_product_group
                        AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') 
                        AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                    GROUP BY 
                        f.e_customer_classname, 
                        b.i_customer, 
                        b.e_customer_name, 
                        b.e_customer_address, 
                        c.e_city_name, 
                        to_char(a.d_nota, ''mm''), 
                        d.e_salesman_name
                    ORDER BY 
                        b.i_customer, 
                        b.e_customer_name, 
                        to_char(a.d_nota, ''mm''), 
                        d.e_salesman_name',
                    'SELECT 
                        (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
                        ''$tgl''::date)::date + s.a * ''1 month''::interval))
                    FROM 
                        generate_series(0, 11) as s(a)')
                    AS
                    (komplit text, kelas text, kode text, nama text, alamat text, kota text, sales text,";
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
                $sql.=" ORDER BY 
                            kode, 
                            nama";
            }else{
              $sql=" * 
                    FROM CROSSTAB
                        ('SELECT 
                            f.e_customer_classname||b.i_customer||b.e_customer_name||b.e_customer_address||c.e_city_name||d.e_salesman_name as komplit, 
                            f.e_customer_classname, 
                            b.i_customer, 
                            b.e_customer_name, 
                            b.e_customer_address, 
                            c.e_city_name, 
                            d.e_salesman_name,
                            to_number(to_char(a.d_nota, ''mm''),''99'') AS bln, 
                            sum(a.v_nota_gross) AS jumlah
                        FROM 
                            tm_nota a, 
                            tr_customer b, 
                            tr_city c, 
                            tr_customer_salesman d, 
                            tm_spb e, 
                            tr_customer_class f
                        WHERE 
                            a.f_nota_cancel = false 
                            AND b.i_customer = a.i_customer 
                            AND a.i_area=''$iarea'' 
                            AND b.i_customer_class = f.i_customer_class 
                            AND NOT a.i_nota IS NULL 
                            AND b.i_city=c.i_city 
                            AND b.i_area=c.i_area 
                            AND a.i_customer = d.i_customer 
                            AND a.i_area = d.i_area 
                            AND d.e_periode =''$periode'' 
                            AND a.i_spb = e.i_spb 
                            AND a.i_area = e.i_area 
                            AND e.i_product_group = d.i_product_group
                            AND (a.d_nota >= to_date(''$dfrom'',''dd-mm-yyyy'') 
                            AND a.d_nota <= to_date(''$dto'',''dd-mm-yyyy''))
                        GROUP BY 
                            f.e_customer_classname, 
                            b.i_customer,
                            b.e_customer_name, 
                            b.e_customer_address, 
                            c.e_city_name, 
                            to_char(a.d_nota, ''mm''), 
                            d.e_salesman_name
                        ORDER BY 
                            b.i_customer, 
                            b.e_customer_name, 
                            to_char(a.d_nota, ''mm''), 
                            d.e_salesman_name',
                        'SELECT 
                            (SELECT EXTRACT(MONTH FROM date_trunc(''month'', 
                            ''$tgl''::date)::date + s.a * ''1 month''::interval))
                        FROM 
                            generate_series(0, 11) as s(a)')
                        AS
                            (komplit text, kelas text, kode text, nama text, alamat text, kota text, sales text,";
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
                        $sql.=" ORDER BY 
                                    kode,
                                    nama";
        }
        $this->db->select($sql,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
   
}

/* End of file Mmaster.php */
