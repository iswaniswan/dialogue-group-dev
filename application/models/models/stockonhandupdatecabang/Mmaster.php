<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacastore($cari){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $cari);
        $query     = $this->db->query("
            SELECT
                i_area
            FROM
                public.tm_user_area
            WHERE
                username = '$username'
                AND id_company = '$idcompany'
                AND i_area = '00'
        ", FALSE);
        if ($query->num_rows()>0) {
            return $this->db->query("
                SELECT
                    DISTINCT (b.i_store),
                    b.e_store_name,
                    c.i_store_location,
                    c.e_store_locationname
                FROM
                    tr_area a,
                    tr_store b,
                    tr_store_location c
                WHERE
                    a.i_store = b.i_store
                    AND b.i_store = c.i_store
                    AND b.i_store = 'AA'
                    AND a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
                    AND (UPPER(b.i_store) LIKE '%$cari%' 
                    OR UPPER(b.e_store_name) LIKE '%$cari%')
                ORDER BY
                    b.i_store,
                    c.i_store_location
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    DISTINCT (b.i_store),
                    b.e_store_name,
                    c.i_store_location,
                    c.e_store_locationname
                FROM
                    tr_area a,
                    tr_store b,
                    tr_store_location c
                WHERE
                    a.i_area = b.i_store
                    AND b.i_store = c.i_store
                    AND (a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany') )
                    AND NOT a.i_store IN ('AA', 'PB')
                    AND c.i_store_location = '00'
                    AND (UPPER(b.i_store) LIKE '%$cari%' 
                    OR UPPER(b.e_store_name) LIKE '%$cari%')
                ORDER BY
                    b.i_store,
                    c.i_store_location
            ", FALSE);
        }
    }

    public function detailstore($istore){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT (b.i_store),
                b.e_store_name,
                c.i_store_location,
                c.e_store_locationname
            FROM
                tr_area a,
                tr_store b,
                tr_store_location c
            WHERE
                a.i_area = b.i_store
                AND b.i_store = c.i_store
                AND b.i_store = '$istore'
                AND (a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany') )
                AND NOT a.i_store IN ('AA', 'PB')
                AND c.i_store_location = '00'
            ORDER BY
                b.i_store,
                c.i_store_location
        ", FALSE);
    }

    public function updateic($store,$istorelocation){
        $this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = 0
            WHERE
                i_store = '$store'
                AND i_store_location = '$istorelocation'
        ", FALSE);
    }

    public function bacasaldopusat($iperiode,$store,$istorelocation){
        return $this->db->query("
            SELECT
                i_product,
                (n_saldo_akhir-n_mutasi_git-n_git_penjualan) AS saldo_akhir,
                i_store,
                i_product_grade,
                i_product_motif,
                i_store_location
            FROM
                f_mutasi_stock_pusat_saldoakhir('$iperiode')
            WHERE
                i_store = '$store'
                AND i_store_location = '$istorelocation'
            ORDER BY
                i_product
        ", FALSE);
    }

    public function bacasaldodaerah($iperiode,$store,$istorelocation){
        return $this->db->query("
            SELECT
                i_product,
                i_store,
                i_product_grade,
                i_product_motif,
                i_store_location,
                (n_saldo_akhir)-(n_saldo_git + n_git_penjualan) AS saldo_akhir
            FROM
                (
                SELECT
                    e_product_groupname, i_product, i_product_grade, i_product_motif, sum(n_saldo_awal) AS n_saldo_awal, sum(n_mutasi_pembelian) AS n_mutasi_pembelian, sum(n_mutasi_returoutlet) AS n_mutasi_returoutlet, sum(n_mutasi_bbm) AS n_mutasi_bbm, sum(n_mutasi_penjualan) AS n_mutasi_penjualan, sum(n_mutasi_bbk) AS n_mutasi_bbk, sum(n_saldo_akhir) AS n_saldo_akhir, sum(n_mutasi_ketoko) AS n_mutasi_ketoko, sum(n_mutasi_daritoko) AS n_mutasi_daritoko, sum(n_mutasi_git) AS n_saldo_git, e_mutasi_periode, i_store, sum(n_git_penjualan) AS n_git_penjualan, sum(n_git_penjualanasal) AS n_git_penjualanasal, sum(n_mutasi_gitasal) AS n_mutasi_gitasal, sum(n_saldo_stockopname) AS n_saldo_stockopname, e_product_name, i_store_location
                FROM
                    f_mutasi_stock_daerah_saldoakhir('$iperiode', '$store', '$istorelocation')
                GROUP BY
                    i_product, i_product_grade, e_product_groupname, e_mutasi_periode, i_store, e_product_name, i_store_location, i_product_motif
                ORDER BY
                    e_product_groupname, e_product_name, i_product) AS z
        ", FALSE);
    }

    public function updatesstokic($i_product,$i_store,$i_product_grade,$i_store_location,$i_product_motif,$saldo_akhir){
        return $this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = $saldo_akhir
            WHERE
                i_product = '$i_product'
                AND i_store = '$i_store'
                AND i_product_grade = '$i_product_grade'
                AND i_store_location = '$i_store_location'
                AND i_product_motif = '$i_product_motif'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
