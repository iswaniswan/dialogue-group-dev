<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function hitung($iperiode){
        if($iperiode>'201512'){
            $x = $this->db->query(" 
                    SELECT
                        a.*
                    FROM
                        (
                        SELECT
                            n_saldo_akhir-(n_mutasi_git + n_git_penjualan) AS n_saldo_stockopname,
                            CASE WHEN i_store_location = 'PB' THEN 0
                            ELSE n_mutasi_git END AS n_mutasi_git,
                            CASE WHEN i_store_location = 'PB' THEN 0
                            ELSE n_git_penjualan END AS n_git_penjualan,
                            i_store,
                            i_store_location,
                            i_product,
                            i_product_grade,
                            i_product_motif,
                            e_product_name
                        FROM
                            f_mutasi_stock_daerah_all_saldoakhir('$iperiode')
                        WHERE
                            i_store <> 'PB'
                            AND NOT e_product_name ISNULL
                    UNION ALL
                        SELECT
                            n_saldo_akhir-(n_mutasi_git + n_git_penjualan) AS n_saldo_stockopname,
                            CASE WHEN i_store_location = 'PB' THEN 0
                            ELSE n_mutasi_git END AS n_mutasi_git,
                            CASE WHEN i_store_location = 'PB' THEN 0
                            ELSE n_git_penjualan END AS n_git_penjualan,
                            i_store,
                            i_store_location,
                            i_product,
                            i_product_grade,
                            i_product_motif,
                            e_product_name
                        FROM
                            f_mutasi_stock_pusat_saldoakhir ('$iperiode') a
                        WHERE
                            NOT a.e_product_name ISNULL
                    UNION ALL
                        SELECT
                            n_saldo_akhir AS n_saldo_stockopname,
                            0 AS n_mutasi_git,
                            0 AS n_git_penjualan,
                            i_store,
                            i_store_location,
                            i_product,
                            i_product_grade,
                            i_product_motif,
                            e_product_name
                        FROM
                            f_mutasi_stock_mo_pb_saldoakhir ('$iperiode') a
                        WHERE
                            NOT a.e_product_name ISNULL ) AS a
                ",false);
        }else{
            $x = $this->db->query("
                    SELECT
                        n_saldo_akhir-(n_mutasi_git + n_git_penjualan) AS n_saldo_stockopname,
                        CASE WHEN i_store_location = 'PB' THEN 0
                        ELSE n_mutasi_git END AS n_mutasi_git,
                        CASE WHEN i_store_location = 'PB' THEN 0
                        ELSE n_git_penjualan END AS n_git_penjualan,
                        i_store,
                        i_store_location,
                        i_product,
                        i_product_grade,
                        i_product_motif,
                        e_product_name
                    FROM
                        f_mutasi_stock_daerah_all('$iperiode')
                    WHERE
                        i_store <> 'PB'
                        AND NOT e_product_name ISNULL
                    UNION ALL
                    SELECT
                        n_saldo_akhir-(n_mutasi_git + n_git_penjualan) AS n_saldo_stockopname,
                        CASE WHEN i_store_location = 'PB' THEN 0
                        ELSE n_mutasi_git END AS n_mutasi_git,
                        CASE WHEN i_store_location = 'PB' THEN 0
                        ELSE n_git_penjualan END AS n_git_penjualan,
                        i_store,
                        i_store_location,
                        i_product,
                        i_product_grade,
                        i_product_motif,
                        e_product_name
                    FROM
                        f_mutasi_stock_pusat ('$iperiode') a
                    WHERE
                        NOT a.e_product_name ISNULL
                    UNION ALL
                    SELECT
                        n_saldo_akhir AS n_saldo_stockopname,
                        0 AS n_mutasi_git,
                        0 AS n_git_penjualan,
                        i_store,
                        i_store_location,
                        i_product,
                        i_product_grade,
                        i_product_motif,
                        e_product_name
                    FROM
                        f_mutasi_stock_mo_pb ('$iperiode') a
                    WHERE
                        NOT a.e_product_name ISNULL
                ",false);
        }
        if ($x->num_rows() > 0){
            $this->db->query("
                DELETE
                FROM
                    tm_stockopname_gabungan
                WHERE
                    e_periode = '$iperiode'
            ",false);
            $i=0;
            foreach($x->result() as $xx){
                $i++;
                $jml      = $xx->n_saldo_stockopname;
                $iproduct = $xx->i_product;
                $nama     = $xx->e_product_name;
                $grade    = $xx->i_product_grade;
                $motif    = $xx->i_product_motif;
                $qu = $this->db->query("
                    SELECT
                        *
                    FROM
                        tm_stockopname_gabungan
                    WHERE
                        e_periode = '$iperiode'
                        AND i_product = '$iproduct'
                        AND i_product_motif = '$motif'
                        AND i_product_grade = '$grade'
                ",false);
                if ($qu->num_rows() > 0){
                    $this->db->query("
                        UPDATE
                            tm_stockopname_gabungan
                        SET
                            n_stockopname = n_stockopname + $jml
                        WHERE
                            e_periode = '$iperiode'
                            AND i_product = '$iproduct'
                            AND i_product_motif = '$motif'
                            AND i_product_grade = '$grade'
                    ",false);
                }else{
                    $query  = $this->db->query("SELECT to_char(current_timestamp,'yyyy-mm-dd hh:mi:ss') as c");
                    $row    = $query->row();
                    $entry  = current_datetime();
                    $this->db->query("
                        INSERT
                            INTO
                            tm_stockopname_gabungan
                        VALUES ('$iperiode',
                        '$iproduct',
                        '$grade',
                        '$motif',
                        '$nama',
                        $jml,
                        '$entry',
                        $i)
                    ",false);
                }
            }

            if($iperiode>'201512'){
                $query = $this->db->query("
                    SELECT
                        a.i_customer,
                        CASE WHEN (a.n_saldo_akhir-a.n_mutasi_git) < 0 THEN 0
                        ELSE (a.n_saldo_akhir-a.n_mutasi_git) END AS n_saldo_stockopname,
                        a.i_product,
                        a.i_product_grade,
                        a.i_product_motif,
                        a.e_product_name
                    FROM
                        f_mutasi_stock_mo_cust_all_saldoakhir('$iperiode') a
                    WHERE
                        NOT a.e_product_name ISNULL
                        AND UPPER(a.e_customer_name) NOT LIKE 'CLAND%'
                    ORDER BY
                        a.i_customer
                ",false);
            }else{
                $query=$this->db->query("
                    SELECT
                        a.i_customer,
                        CASE WHEN (a.n_saldo_akhir-a.n_mutasi_git) < 0 THEN 0
                        ELSE (a.n_saldo_akhir-a.n_mutasi_git) END AS n_saldo_stockopname,
                        a.i_product,
                        a.i_product_grade,
                        a.i_product_motif,
                        a.e_product_name
                    FROM
                        f_mutasi_stock_mo_cust_all('$iperiode') a
                    WHERE
                        NOT a.e_product_name ISNULL
                    ORDER BY
                        a.i_customer
                ",false);
            }
            if ($query->num_rows() > 0){
                foreach($query->result() as $tmp){
                    $i++;
                    $customer = $tmp->i_customer;
                    $iproduct = $tmp->i_product;
                    $grade    = $tmp->i_product_grade;
                    $motif    = $tmp->i_product_motif;
                    $jml      = $tmp->n_saldo_stockopname;
                    $nama     = $tmp->e_product_name;              
                    $qu = $this->db->query("
                        SELECT
                            *
                        FROM
                            tm_stockopname_gabungan
                        WHERE
                            e_periode = '$iperiode'
                            AND i_product = '$iproduct'
                            AND i_product_motif = '$motif'
                            AND i_product_grade = '$grade'
                     ",false);
                    if ($qu->num_rows() > 0){
                        $this->db->query("
                            UPDATE
                                tm_stockopname_gabungan
                            SET
                                n_stockopname = n_stockopname + $jml
                            WHERE
                                e_periode = '$iperiode'
                                AND i_product = '$iproduct'
                                AND i_product_motif = '$motif'
                                AND i_product_grade = '$grade'
                         ",false);
                    }else{
                        $entry    = current_datetime();
                        $this->db->query("
                            INSERT
                                INTO
                                tm_stockopname_gabungan
                            VALUES ('$iperiode',
                            '$iproduct',
                            '$grade',
                            '$motif',
                            '$nama',
                            $jml,
                            '$entry',
                            $i)
                        ",false);
                    }
                }
            }

            $yeye=substr($iperiode,2,2);
            $beel=substr($iperiode,4,2);
            if($beel=='12'){
                $beel='01';
                settype($yeye,"integer");
                $yeye=$yeye+1;
                settype($yeye,"string");
            }else{
                settype($beel,"integer");
                $beel=$beel+1;
                settype($beel,"string");
                $a=strlen($beel);
                if($a==1)$beel='0'.$beel;
            }
            $nextperiode=$yeye.$beel;
            $qie = $this->db->query("
                SELECT
                    b.i_product,
                    b.i_product_grade,
                    b.i_product_motif,
                    b.e_product_name,
                    SUM(b.n_quantity) AS n_quantity
                FROM
                    tm_notapb a,
                    tm_notapb_item b
                WHERE
                    a.i_notapb = b.i_notapb
                    AND a.i_area = b.i_area
                    AND a.i_customer = b.i_customer
                    AND TO_CHAR(a.d_notapb, 'yyyymm')= '$iperiode'
                    AND a.i_spb LIKE '%-$nextperiode-%'
                    AND a.f_notapb_cancel = 'f'
                GROUP BY
                    b.i_product,
                    b.i_product_grade,
                    b.i_product_motif,
                    b.e_product_name
            ",false);
            if ($qie->num_rows() > 0){
                foreach($qie->result() as $mt){
                    $i++;
                    $iproduct = $mt->i_product;
                    $grade    = $mt->i_product_grade;
                    $motif    = $mt->i_product_motif;
                    $jml      = $mt->n_quantity;
                    $nama     = $mt->e_product_name;
                    $qz = $this->db->query("
                        SELECT
                            *
                        FROM
                            tm_stockopname_gabungan
                        WHERE
                            e_periode = '$iperiode'
                            AND i_product = '$iproduct'
                            AND i_product_motif = '$motif'
                            AND i_product_grade = '$grade'
                    ",false);
                    if($qz->num_rows() > 0){
                        $this->db->query("
                            UPDATE
                                tm_stockopname_gabungan
                            SET
                                n_stockopname = n_stockopname + $jml
                            WHERE
                                e_periode = '$iperiode'
                                AND i_product = '$iproduct'
                                AND i_product_motif = '$motif'
                                AND i_product_grade = '$grade'
                        ",false);
                    }else{
                        $entry = current_datetime();
                        $this->db->query("
                            INSERT
                                INTO
                                tm_stockopname_gabungan
                            VALUES ('$iperiode',
                            '$iproduct',
                            '$grade',
                            '$motif',
                            '$nama',
                            $jml,
                            '$entry',
                            $i)
                        ",false);
                    }
                }
            }
            $this->db->query("
                DELETE
                FROM
                    tm_hpp
                WHERE
                    e_periode = '$iperiode'
            ");

            $qu = $this->db->query("
                SELECT
                    e_periode,
                    i_product,
                    '00' AS i_product_motif,
                    'A' AS i_product_grade,
                    e_product_name,
                    SUM(n_stockopname) AS n_stockopname
                FROM
                    tm_stockopname_gabungan
                WHERE
                    e_periode = '$iperiode'
                GROUP BY
                    e_periode,
                    i_product,
                    e_product_name
                ORDER BY
                    i_product
            ",false);
            if ($qu->num_rows() > 0){
                $tes=0;
                foreach($qu->result() as $xx){
                    $tes++;
                    $jml=$xx->n_stockopname;
                    $xx->e_product_name=str_replace("'","''",$xx->e_product_name);
                    $hrg=0;
                    $jmlbeli=0;
                    $qi = $this->db->query("
                        SELECT
                            *
                        FROM
                            f_history_beli('$iperiode',
                            '$xx->i_product')
                    ",false);
                    if ($qi->num_rows() > 0){
                        $jmlx=$jml;
                        foreach($qi->result() as $yy){
                            $hrg=$yy->hargabeli;
                            $hrgdisc=(($yy->hargabeli*75)/100);
                            $jmlasal=$jml-$jmlbeli;
                            $jmlbeli=$jmlbeli+$yy->beli;
                            if($jml>=$jmlbeli){
                                $qa = $this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tm_hpp
                                    WHERE
                                        e_periode = '$iperiode'
                                        AND i_product = '$xx->i_product'
                                        AND v_harga = $hrg
                                        AND i_product_grade = 'A'
                                        AND i_product_motif = '00'
                                ",false);
                                if ($qa->num_rows() > 0){
                                    $this->db->query("
                                        UPDATE
                                            tm_hpp
                                        SET
                                            n_opname = n_opname + $yy->beli,
                                            n_opname_total = n_opname_total + $xx->n_stockopname
                                        WHERE
                                            e_periode = '$iperiode'
                                            AND i_product = '$xx->i_product'
                                            AND v_harga = $hrg
                                            AND i_product_grade = 'A'
                                            AND i_product_motif = '00'
                                    ",false);
                                }else{
                                    $this->db->query("
                                        INSERT
                                            INTO
                                            tm_hpp
                                        VALUES ('$iperiode',
                                        '$xx->i_product',
                                        '00',
                                        'A',
                                        '$xx->e_product_name',
                                        0,
                                        $yy->beli,
                                        $hrg,
                                        'f',
                                        $xx->n_stockopname)
                                    ",false);
                                }
                            }else{
                                $qa=$this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tm_hpp
                                    WHERE
                                        e_periode = '$iperiode'
                                        AND i_product = '$xx->i_product'
                                        AND v_harga = $hrg
                                        AND i_product_grade = 'A'
                                        AND i_product_motif = '00'
                                ",false);
                                if ($qa->num_rows() > 0){
                                    $this->db->query("
                                        UPDATE
                                            tm_hpp
                                        SET
                                            n_opname = n_opname + $jmlasal,
                                            n_opname_total = n_opname_total + $xx->n_stockopname
                                        WHERE
                                            e_periode = '$iperiode'
                                            AND i_product = '$xx->i_product'
                                            AND v_harga = $hrg
                                            AND i_product_grade = 'A'
                                            AND i_product_motif = '00'
                                    ",false);
                                }else{
                                    $this->db->query("
                                        INSERT
                                            INTO
                                            tm_hpp
                                        VALUES ('$iperiode',
                                        '$xx->i_product',
                                        '00',
                                        'A',
                                        '$xx->e_product_name',
                                        0,
                                        $jmlasal,
                                        $hrg,
                                        'f',
                                        $xx->n_stockopname)
                                    ",false);
                                }
                                break;
                            }
                        }
                    }else{
                        $qa = $this->db->query("
                            SELECT
                                v_product_mill
                            FROM
                                tr_harga_beli
                            WHERE
                                i_product = '$xx->i_product'
                                AND i_price_group = '00'
                        ",false);
                        if ($qa->num_rows() > 0){
                            foreach($qa->result() as $txt){
                                $pangaos = $txt->v_product_mill;
                                $qa = $this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tm_hpp
                                    WHERE
                                        e_periode = '$iperiode'
                                        AND i_product = '$xx->i_product'
                                        AND v_harga = $pangaos
                                ",false);
                                if ($qa->num_rows() > 0){
                                    $this->db->query("
                                        UPDATE
                                            tm_hpp
                                        SET
                                            n_opname = n_opname + $jmlasal,
                                            n_opname_total = n_opname_total + $xx->n_stockopname
                                        WHERE
                                            e_periode = '$iperiode'
                                            AND i_product = '$xx->i_product'
                                            AND v_harga = $pangaos
                                    ",false);
                                }else{
                                    $qa=$this->db->query("
                                        SELECT
                                            *
                                        FROM
                                            tm_hpp
                                        WHERE
                                            e_periode = '$iperiode'
                                            AND i_product = '$xx->i_product'
                                            AND v_harga = 0
                                    ",false);
                                    if ($qa->num_rows() == 0){
                                        $this->db->query("
                                            INSERT
                                                INTO
                                                tm_hpp
                                            VALUES ('$iperiode',
                                            '$xx->i_product',
                                            '00',
                                            'A',
                                            '$xx->e_product_name',
                                            0,
                                            $jml,
                                            0,
                                            'f',
                                            $xx->n_stockopname)
                                        ",false);
                                    }else{
                                        $this->db->query("
                                            UPDATE
                                                tm_hpp
                                            SET
                                                n_opname = n_opname + $jmlasal,
                                                n_opname_total = n_opname_total + $xx->n_stockopname,
                                                v_harga = $pangaos
                                            WHERE
                                                e_periode = '$iperiode'
                                                AND i_product = '$xx->i_product'
                                                AND v_harga = 0
                                        ",false);
                                    }
                                }
                            }
                        }else{
                            $qa = $this->db->query("
                                SELECT
                                    *
                                FROM
                                    tm_hpp
                                WHERE
                                    e_periode = '$iperiode'
                                    AND i_product = '$xx->i_product'
                                    AND v_harga = 0
                            ",false);
                            if ($qa->num_rows() == 0){
                                $this->db->query("
                                    INSERT
                                        INTO
                                        tm_hpp
                                    VALUES ('$iperiode',
                                    '$xx->i_product',
                                    '00',
                                    'A',
                                    '$xx->e_product_name',
                                    0,
                                    $jml,
                                    0,
                                    'f',
                                    $xx->n_stockopname)
                                ",false);
                            }
                        }
                    }
                    $qa = $this->db->query("
                        SELECT
                            v_product_mill
                        FROM
                            tr_harga_beli
                        WHERE
                            i_product = '$xx->i_product'
                            AND i_product_grade = 'A'
                            AND i_price_group = '00'
                    ",false);
                    if ($qa->num_rows() > 0){
                        foreach($qa->result() as $txt){
                            $qx=$this->db->query("
                                SELECT
                                    *
                                FROM
                                    tm_hpp
                                WHERE
                                    e_periode = '$iperiode'
                                    AND i_product = '$xx->i_product'
                                    AND (v_harga = 0
                                    OR v_harga ISNULL)
                            ",false);

                            if ($qx->num_rows() == 0){
                                $this->db->query("
                                    UPDATE
                                        tm_hpp
                                    SET
                                        v_harga = $txt->v_product_mill
                                    WHERE
                                        e_periode = '$iperiode'
                                        AND i_product = '$xx->i_product'
                                        AND (v_harga = 0
                                        OR v_harga ISNULL)
                                ",false);
                            }else{
                                $this->db->query("
                                    UPDATE
                                        tm_hpp
                                    SET
                                        v_harga = $txt->v_product_mill
                                    WHERE
                                        e_periode = '$iperiode'
                                        AND i_product = '$xx->i_product'
                                        AND (v_harga = 0
                                        OR v_harga ISNULL)
                                ",false);
                            }
                        }
                    }
                    /*yang ga ketemu harganya ambil dari master*/
                    $qa = $this->db->query("
                        SELECT
                            v_product_mill
                        FROM
                            tr_product
                        WHERE
                            i_product = '$xx->i_product'
                    ",false);
                    if ($qa->num_rows() > 0){
                        foreach($qa->result() as $txt){
                            $qx=$this->db->query("
                                SELECT
                                    *
                                FROM
                                    tm_hpp
                                WHERE
                                    e_periode = '$iperiode'
                                    AND i_product = '$xx->i_product'
                                    AND (v_harga = 0
                                    OR v_harga ISNULL)
                            ",false);
                        if ($qx->num_rows() == 0){
                            $this->db->query("
                                UPDATE
                                    tm_hpp
                                SET
                                    v_harga = $txt->v_product_mill
                                WHERE
                                    e_periode = '$iperiode'
                                    AND i_product = '$xx->i_product'
                                    AND (v_harga = 0
                                    OR v_harga ISNULL)
                            ",false);
                        }else{
                            $this->db->query("
                                UPDATE
                                    tm_hpp
                                SET
                                    v_harga = $txt->v_product_mill
                                WHERE
                                    e_periode = '$iperiode'
                                    AND i_product = '$xx->i_product'
                                    AND (v_harga = 0
                                    OR v_harga ISNULL)
                            ",false);
                        }
                    }
                }
                $qz=$this->db->query("
                    SELECT
                        i_product_a
                    FROM
                        tr_product_ab
                    WHERE
                        i_product_b = '$xx->i_product'
                ",false);
                if ($qz->num_rows() > 0){
                    foreach($qz->result() as $tx){
                        $qa=$this->db->query("
                            SELECT
                                v_product_mill
                            FROM
                                tr_harga_beli
                            WHERE
                                i_product = '$tx->i_product_a'
                                AND i_price_group = '00'
                        ",false);
                        if ($qa->num_rows() > 0){
                            foreach($qa->result() as $txt){
                                $pangaos=(($txt->v_product_mill*75)/100);
                                $qa=$this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tm_hpp
                                    WHERE
                                        e_periode = '$iperiode'
                                        AND i_product = '$xx->i_product'
                                        AND v_harga = $pangaos
                                ",false);
                                if ($qa->num_rows()==0){
                                    $this->db->query("
                                        UPDATE
                                            tm_hpp
                                        SET
                                            v_harga = $pangaos
                                        WHERE
                                            e_periode = '$iperiode'
                                            AND i_product = '$xx->i_product'
                                            AND (v_harga = 0
                                            OR v_harga ISNULL)
                                    ",false);
                                }
                            }
                        }
                    }
                }
            } 
        }

        $qi=$this->db->query("
            SELECT
                a.d_dtap,
                b.i_product,
                b.e_product_name,
                b.v_pabrik,
                SUM(b.n_jumlah) AS n_jumlah,
                b.i_product_motif
            FROM
                tm_dtap a,
                tm_dtap_item b
            WHERE
                a.i_dtap = b.i_dtap
                AND a.i_area = b.i_area
                AND a.i_supplier = b.i_supplier
                AND a.f_dtap_cancel = 'f'
                AND TO_CHAR(a.d_dtap, 'yyyymm')= '$iperiode'
                AND a.n_dtap_year = b.n_dtap_year
            GROUP BY
                a.d_dtap,
                b.i_product,
                b.e_product_name,
                b.v_pabrik,
                b.i_product_motif
            ORDER BY
                a.d_dtap DESC
        ",false);
        if ($qi->num_rows() > 0){
            foreach($qi->result() as $yy){
                $yy->e_product_name=str_replace("'","''",$yy->e_product_name);
                $beli=$yy->n_jumlah;
                $qa=$this->db->query("
                    SELECT
                        *
                    FROM
                        tm_hpp
                    WHERE
                        e_periode = '$iperiode'
                        AND i_product = '$yy->i_product'
                        AND v_harga = $yy->v_pabrik
                ",false);
                if ($qa->num_rows() > 0){
                    $this->db->query("
                        UPDATE
                            tm_hpp
                        SET
                            n_beli = n_beli + $beli,
                            f_beli = 't',
                            v_harga = $yy->v_pabrik
                        WHERE
                            e_periode = '$iperiode'
                            AND i_product = '$yy->i_product'
                            AND v_harga = $yy->v_pabrik
                    ",false);
                }else{
                    $this->db->query("
                        INSERT
                            INTO
                            tm_hpp
                        VALUES ('$iperiode',
                        '$yy->i_product',
                        '00',
                        'A',
                        '$yy->e_product_name',
                        $yy->n_jumlah,
                        0,
                        $yy->v_pabrik,
                        't',
                        0)
                    ",false);
                }
            }
        }
        $xy=$this->db->query("
            SELECT
                *
            FROM
                tm_hpp
            WHERE
                e_periode = '$iperiode'
            ORDER BY
                e_product_name,
                i_product
        ");
        if ($xy->num_rows() > 0){
            return $xy->result();
        }
    }
    }
}

/* End of file Mmaster.php */
