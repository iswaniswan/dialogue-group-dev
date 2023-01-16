<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmaster extends CI_Model {

    public function simpan($nlama,$dfrom,$dto){
        $dproses  = current_datetime();
        $query=$this->db->query("
            SELECT
                SUM(jml) AS jml,
                SUM(jmlreal) AS jmlreal,
                i_area,
                i_salesman,
                e_salesman_name,
                e_area_name
            FROM
                (
                SELECT
                    0 AS jml,
                    0 AS jmlreal,
                    a.f_rrkh_cancel,
                    a.d_rrkh,
                    b.i_area,
                    b.i_salesman,
                    b.i_customer,
                    d.e_salesman_name,
                    c.e_area_name
                FROM
                    tm_rrkh a,
                    tm_rrkh_item b,
                    tr_area c,
                    tr_salesman d
                WHERE
                    a.i_area = b.i_area
                    AND a.i_salesman = b.i_salesman
                    AND a.d_rrkh = b.d_rrkh
                    AND b.i_area = c.i_area
                    AND b.f_kunjungan_valid = 't'
                    AND b.i_salesman = d.i_salesman
                    AND a.d_rrkh >= '$dfrom'
                    AND a.d_rrkh <= '$dto'
                    AND a.f_rrkh_cancel = 'f'
            UNION ALL
                SELECT
                    COUNT(*) AS jml,
                    0 AS jmlreal,
                    'f' AS f_rrkh_cancel,
                    NULL AS d_rrkh,
                    b.i_area,
                    b.i_salesman,
                    b.i_customer,
                    d.e_salesman_name,
                    c.e_area_name
                FROM
                    tm_rrkh a,
                    tm_rrkh_item b,
                    tr_area c,
                    tr_salesman d
                WHERE
                    a.i_area = b.i_area
                    AND a.i_salesman = b.i_salesman
                    AND a.d_rrkh = b.d_rrkh
                    AND b.i_area = c.i_area
                    AND b.f_kunjungan_valid = 't'
                    AND b.i_salesman = d.i_salesman
                    AND a.d_rrkh >= '$dfrom'
                    AND a.d_rrkh <= '$dto'
                    AND a.f_rrkh_cancel = 'f'
                GROUP BY
                    b.i_area,
                    b.i_salesman,
                    b.i_customer,
                    d.e_salesman_name,
                    c.e_area_name
            UNION ALL
                SELECT
                    0 AS jml,
                    COUNT(*) AS jmlreal,
                    'f' AS f_rrkh_cancel,
                    NULL AS d_rrkh,
                    b.i_area,
                    b.i_salesman,
                    b.i_customer,
                    d.e_salesman_name,
                    c.e_area_name
                FROM
                    tm_rrkh a,
                    tm_rrkh_item b,
                    tr_area c,
                    tr_salesman d
                WHERE
                    a.i_area = b.i_area
                    AND a.i_salesman = b.i_salesman
                    AND a.d_rrkh = b.d_rrkh
                    AND b.i_area = c.i_area
                    AND b.i_salesman = d.i_salesman
                    AND a.d_rrkh >= '$dfrom'
                    AND a.d_rrkh <= '$dto'
                    AND a.f_rrkh_cancel = 'f'
                    AND b.i_kunjungan_type = '01'
                    AND b.f_kunjungan_realisasi = 't'
                    AND b.f_kunjungan_valid = 't'
                GROUP BY
                    b.i_area,
                    b.i_salesman,
                    b.i_customer,
                    d.e_salesman_name,
                    c.e_area_name ) AS a
            GROUP BY
                a.i_area,
                a.i_salesman,
                e_salesman_name,
                e_area_name
            ORDER BY
                a.i_area,
                a.i_salesman");
        if ($query->num_rows() > 0){
            $tmp=explode("-",$dfrom);
            $th1=$tmp[0];
            $bl1=$tmp[1];
            $tmp=explode("-",$dto);
            $th2=$tmp[0];
            $iperiode=$th1.$bl1;
            $this->db->where('i_periode', $iperiode);
            $this->db->delete('tm_kunjungan');
            foreach($query->result() as $row){
                $this->db->query("
                    INSERT
                        INTO
                        tm_kunjungan
                    VALUES('$iperiode',
                    '$row->i_area',
                    '$row->i_salesman',
                    $nlama,
                    $row->jml,
                    $row->jmlreal,
                    '$dproses')
                ");
            }
        }
       
        /*order*/
        $query=$this->db->query(" 
            SELECT
                a.i_area,
                a.i_salesman,
                e.e_salesman_name,
                a.i_spb,
                a.d_spb,
                a.i_customer,
                c.i_kunjungan_type,
                c.f_kunjungan_realisasi,
                a.v_spb,
                a.v_spb_discounttotal
            FROM
                tr_customer b,
                tr_salesman e,
                tm_spb a
            LEFT JOIN tm_rrkh_item c ON
                (a.i_area = c.i_area
                AND a.i_salesman = c.i_salesman
                AND c.d_rrkh = a.d_spb
                AND a.i_customer = c.i_customer
                AND c.i_kunjungan_type = '01'
                AND c.f_kunjungan_realisasi = 't'
                AND c.f_kunjungan_valid = 't')
            LEFT JOIN tm_rrkh d ON
                (c.i_area = d.i_area
                AND c.i_salesman = d.i_salesman
                AND c.d_rrkh = d.d_rrkh
                AND d.d_rrkh >= '$dfrom'
                AND d.d_rrkh <= '$dto'
                AND d.f_rrkh_cancel >= 'f')
            WHERE
                a.i_customer = b.i_customer
                AND a.d_spb >= '$dfrom'
                AND a.d_spb <= '$dto'
                AND a.f_spb_cancel = 'f'
                AND a.i_salesman = e.i_salesman
        ");
        if($query->num_rows() > 0){
            $tmp=explode("-",$dfrom);
            $th1=$tmp[0];
            $bl1=$tmp[1];
            $tmp=explode("-",$dto);
            $th2=$tmp[0];
            $iperiode=$th1.$bl1;
            $this->db->where('i_periode', $iperiode);
            $this->db->delete('tm_kunjungan_item');
            foreach($query->result() as $row){
                if($row->i_kunjungan_type=='' && $row->f_kunjungan_realisasi==''){
                    $this->db->query("
                        INSERT
                            INTO
                            tm_kunjungan_item
                        VALUES('$iperiode',
                        '$row->i_area',
                        '$row->i_salesman',
                        '$row->i_customer',
                        '$row->e_salesman_name',
                        '$row->i_spb',
                        '$row->d_spb',
                        NULL,
                        NULL,
                        $row->v_spb,
                        $row->v_spb_discounttotal)
                    ");
                }elseif($row->i_kunjungan_type!='' && $row->f_kunjungan_realisasi==''){
                    $this->db->query("
                        INSERT
                            INTO
                            tm_kunjungan_item
                        VALUES('$iperiode',
                        '$row->i_area',
                        '$row->i_salesman',
                        '$row->i_customer',
                        '$row->e_salesman_name',
                        '$row->i_spb',
                        '$row->d_spb',
                        '$row->i_kunjungan_type',
                        NULL,
                        $row->v_spb,
                        $row->v_spb_discounttotal)
                    ");
                }elseif($row->i_kunjungan_type=='' && $row->f_kunjungan_realisasi!=''){
                        $this->db->query("
                            INSERT
                                INTO
                                tm_kunjungan_item
                            VALUES('$iperiode',
                            '$row->i_area',
                            '$row->i_salesman',
                            '$row->i_customer',
                            '$row->e_salesman_name',
                            '$row->i_spb',
                            '$row->d_spb',
                            NULL,
                            '$row->f_kunjungan_realisasi',
                            $row->v_spb,
                            $row->v_spb_discounttotal)
                        ");
                }else{
                    $this->db->query("
                        INSERT
                            INTO
                            tm_kunjungan_item
                        VALUES('$iperiode',
                        '$row->i_area',
                        '$row->i_salesman',
                        '$row->i_customer',
                        '$row->e_salesman_name',
                        '$row->i_spb',
                        '$row->d_spb',
                        '$row->i_kunjungan_type',
                        '$row->f_kunjungan_realisasi',
                        $row->v_spb,
                        $row->v_spb_discounttotal)
                    ");
                }
            }
        }
    }

    public function baca($nlama,$dfrom,$dto){
        $tmp=explode("-",$dfrom);
        $th1=$tmp[0];
        $bl1=$tmp[1];
        $hr1=$tmp[2];
        $tmp=explode("-",$dto);
        $th2=$tmp[0];
        $bl2=$tmp[1];
        $hr2=$tmp[2];
        $iperiode=$th1.$bl1;
        if( ($th1==$th2)&&($bl1==$bl2)&&($hr1=='01')&&($hr2=='28'||$hr2=='29'||$hr2=='30'||$hr2=='31') ){
            $query=$this->db->query("
                SELECT
                    a.i_periode,
                    a.i_area,
                    a.i_salesman,
                    a.n_jumlah_hari,
                    a.n_jumlah_kunjungan AS jml,
                    a.n_jumlah_order AS jmlreal,
                    b.e_salesman_name,
                    c.e_area_name
                FROM
                    tm_kunjungan a,
                    tr_salesman b,
                    tr_area c
                WHERE
                    a.i_periode = '$iperiode'
                    AND a.i_area = c.i_area
                    AND a.i_salesman = b.i_salesman
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ");
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }else{
            $query=$this->db->query("
                SELECT
                    SUM(jml) AS jml,
                    SUM(jmlreal) AS jmlreal,
                    i_area,
                    i_salesman,
                    e_salesman_name,
                    e_area_name
                FROM
                    (
                    SELECT
                        0 AS jml,
                        0 AS jmlreal,
                        a.f_rrkh_cancel,
                        a.d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.f_kunjungan_valid = 't'
                        AND b.i_salesman = d.i_salesman
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                UNION ALL
                    SELECT
                        COUNT(*) AS jml,
                        0 AS jmlreal,
                        'f' AS f_rrkh_cancel,
                        NULL AS d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.f_kunjungan_valid = 't'
                        AND b.i_salesman = d.i_salesman
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                    GROUP BY
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                UNION ALL
                    SELECT
                        0 AS jml,
                        COUNT(*) AS jmlreal,
                        'f' AS f_rrkh_cancel,
                        NULL AS d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.i_salesman = d.i_salesman
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                        AND b.i_kunjungan_type = '01'
                        AND b.f_kunjungan_realisasi = 't'
                        AND b.f_kunjungan_valid = 't'
                    GROUP BY
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name ) AS a
                GROUP BY
                    a.i_area,
                    a.i_salesman,
                    e_salesman_name,
                    e_area_name
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ");
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }
    }

    public function bacakunjungan($isalesman,$dfrom,$dto){
        $tmp=explode("-",$dfrom);
        $th1=$tmp[2];
        $bl1=$tmp[1];
        $hr1=$tmp[0];
        $tmp=explode("-",$dto);
        $th2=$tmp[2];
        $bl2=$tmp[1];
        $hr2=$tmp[0];
        $iperiode=$th1.$bl1;
        if( ($th1==$th2)&&($bl1==$bl2)&&($hr1=='01')&&($hr2=='28'||$hr2=='29'||$hr2=='30'||$hr2=='31') ){
            $query=$this->db->query("
                SELECT
                    a.*,
                    c.e_salesman_name,
                    d.e_customer_name,
                    e.e_kunjungan_typename,
                    f.e_area_name
                FROM
                    tm_rrkh_item a,
                    tm_rrkh b,
                    tr_salesman c,
                    tr_customer d,
                    tr_kunjungan_type e,
                    tr_area f
                WHERE
                    a.d_rrkh = b.d_rrkh
                    AND a.i_area = b.i_area
                    AND a.i_salesman = b.i_salesman
                    AND b.i_salesman = c.i_salesman
                    AND b.i_area = c.i_area
                    AND a.i_customer = d.i_customer
                    AND b.f_rrkh_cancel = 'f'
                    AND a.i_kunjungan_type = '01'
                    AND a.f_kunjungan_valid = 't'
                    AND a.i_kunjungan_type = e.i_kunjungan_type
                    AND a.i_salesman = '$isalesman'
                    AND b.i_area = f.i_area
                    AND TO_CHAR(b.d_rrkh, 'yyyymm')= '$iperiode'
                ORDER BY
                    a.d_rrkh
            ");
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }else{
            $query=$this->db->query("
                SELECT
                    SUM(jml) AS jml,
                    SUM(jmlreal) AS jmlreal,
                    i_area,
                    i_salesman,
                    e_salesman_name,
                    e_area_name
                FROM
                    (
                    SELECT
                        0 AS jml,
                        0 AS jmlreal,
                        a.f_rrkh_cancel,
                        a.d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.f_kunjungan_valid = 't'
                        AND b.i_salesman = d.i_salesman
                        AND b.i_area = d.i_area
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                UNION ALL
                    SELECT
                        COUNT(*) AS jml,
                        0 AS jmlreal,
                        'f' AS f_rrkh_cancel,
                        NULL AS d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.f_kunjungan_valid = 't'
                        AND b.i_salesman = d.i_salesman
                        AND b.i_area = d.i_area
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                    GROUP BY
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                UNION ALL
                    SELECT
                        0 AS jml,
                        COUNT(*) AS jmlreal,
                        'f' AS f_rrkh_cancel,
                        NULL AS d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.i_salesman = d.i_salesman
                        AND b.i_area = d.i_area
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                        AND b.i_kunjungan_type = '01'
                        AND b.f_kunjungan_realisasi = 't'
                        AND b.f_kunjungan_valid = 't'
                    GROUP BY
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name ) AS a
                GROUP BY
                    a.i_area,
                    a.i_salesman,
                    e_salesman_name,
                    e_area_name
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ");
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }
    }

    public function bacaorder($isalesman,$dfrom,$dto){
        $tmp=explode("-",$dfrom);
        $th1=$tmp[2];
        $bl1=$tmp[1];
        $hr1=$tmp[0];
        $tmp=explode("-",$dto);
        $th2=$tmp[2];
        $bl2=$tmp[1];
        $hr2=$tmp[0];
        $iperiode=$th1.$bl1;
        if( ($th1==$th2)&&($bl1==$bl2)&&($hr1=='01')&&($hr2=='28'||$hr2=='29'||$hr2=='30'||$hr2=='31') ){
            $query=$this->db->query("
                SELECT
                    a.d_spb,
                    a.i_salesman,
                    a.i_customer,
                    a.i_area,
                    SUM(a.v_spb-a.v_discount) AS v_spb,
                    b.e_salesman_name,
                    c.e_area_name,
                    d.e_kunjungan_typename,
                    e.e_customer_name,
                    f.e_customer_classname
                FROM
                    tr_salesman b,
                    tr_area c,
                    tr_customer e,
                    tr_customer_class f,
                    tm_kunjungan_item a
                LEFT JOIN tr_kunjungan_type d ON
                    (a.i_kunjungan_type = d.i_kunjungan_type)
                WHERE
                    a.i_periode = '$iperiode'
                    AND a.i_area = c.i_area
                    AND a.i_salesman = b.i_salesman
                    AND a.i_area = b.i_area
                    AND a.i_salesman = '$isalesman'
                    AND e.i_customer_class = f.i_customer_class
                    AND e.i_customer = a.i_customer
                GROUP BY
                    a.d_spb,
                    a.i_salesman,
                    a.i_customer,
                    a.i_area,
                    b.e_salesman_name,
                    c.e_area_name,
                    d.e_kunjungan_typename,
                    e.e_customer_name,
                    f.e_customer_classname
                ORDER BY
                    a.i_customer,
                    a.d_spb
            ");
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }else{
            $query=$this->db->query("
                SELECT
                    SUM(jml) AS jml,
                    SUM(jmlreal) AS jmlreal,
                    i_area,
                    i_salesman,
                    e_salesman_name,
                    e_area_name
                FROM
                    (
                    SELECT
                        0 AS jml,
                        0 AS jmlreal,
                        a.f_rrkh_cancel,
                        a.d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.f_kunjungan_valid = 't'
                        AND b.i_salesman = d.i_salesman
                        AND b.i_area = d.i_area
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                UNION ALL
                    SELECT
                        COUNT(*) AS jml,
                        0 AS jmlreal,
                        'f' AS f_rrkh_cancel,
                        NULL AS d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.f_kunjungan_valid = 't'
                        AND b.i_salesman = d.i_salesman
                        AND b.i_area = d.i_area
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                    GROUP BY
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                UNION ALL
                    SELECT
                        0 AS jml,
                        COUNT(*) AS jmlreal,
                        'f' AS f_rrkh_cancel,
                        NULL AS d_rrkh,
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name
                    FROM
                        tm_rrkh a,
                        tm_rrkh_item b,
                        tr_area c,
                        tr_salesman d
                    WHERE
                        a.i_area = b.i_area
                        AND a.i_salesman = b.i_salesman
                        AND a.d_rrkh = b.d_rrkh
                        AND b.i_area = c.i_area
                        AND b.i_salesman = d.i_salesman
                        AND b.i_area = d.i_area
                        AND a.d_rrkh >= '$dfrom'
                        AND a.d_rrkh <= '$dto'
                        AND a.f_rrkh_cancel >= 'f'
                        AND b.i_kunjungan_type = '01'
                        AND b.f_kunjungan_realisasi = 't'
                        AND b.f_kunjungan_valid = 't'
                    GROUP BY
                        b.i_area,
                        b.i_salesman,
                        b.i_customer,
                        d.e_salesman_name,
                        c.e_area_name ) AS a
                GROUP BY
                    a.i_area,
                    a.i_salesman,
                    e_salesman_name,
                    e_area_name
                ORDER BY
                    a.i_area,
                    a.i_salesman
            ");
            if ($query->num_rows() > 0){
                return $query->result();
            }
        }
    }
}

/* End of file Mmaster.php */
