<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function gettoko($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_customer,
                e_customer_name 
            FROM
                tr_customer
            WHERE
                (UPPER(i_customer) LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%')
            ORDER BY
                i_customer", 
        FALSE);
    } 

    public function getproduct($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                c.e_product_name AS nama
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')
            ORDER BY
                c.i_product,
                a.e_product_motifname",
        FALSE);
    } 

    public function getdetailproduct($iproduct){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                c.e_product_name AS nama,
                c.v_product_mill AS harga
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND a.i_product = '$iproduct'
            ORDER BY
                c.i_product,
                a.e_product_motifname",
        FALSE);
    } 

    public function runningnumber($thbl){
        $th   = substr($thbl,0,4);
        $asal = $thbl;
        $thbl = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" 
                n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'BBH'
                AND i_area = '00'
                AND SUBSTRING(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobbk = $terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nobbk
                WHERE
                    i_modul = 'BBH'
                    AND i_area = '00'
                    AND SUBSTRING(e_periode, 1, 4)= '$th'
            ", false);
            settype($nobbk,"string");
            $a = strlen($nobbk);
            while($a<4){
                $nobbk="0".$nobbk;
                $a=strlen($nobbk);
            }
            $nobbk = "BBK-".$thbl."-KH".$nobbk;
            return $nobbk;
        }else{
            $nobbk = "KH0001";
            $nobbk = "BBK-".$thbl."-".$nobbk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('BBH',
                '00',
                '$asal',
                1)
            ");
            return $nobbk;
        }
    }

    public function insertheader($ibbk, $ibbktype, $dbbk, $icustomer, $ibbkold, $eremark){
        $this->db->set(
            array(
                'i_bbk'                 => $ibbk,
                'i_bbk_type'            => $ibbktype,
                'd_bbk'                 => $dbbk,
                'i_area'                => '00',
                'i_supplier'            => $icustomer,
                'e_remark'              => $eremark,
                'i_refference_document' => 'HADIAH'
            )
        );
        $this->db->insert('tm_bbk');
    }

    public function insertdetail($ibbk,$ibbktype,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$vunitprice,$eremark,$i,$thbl){
        $this->db->set(
            array(
                'i_bbk'                 => $ibbk,
                'i_bbk_type'            => $ibbktype,
                'i_product'             => $iproduct,
                'i_product_grade'       => $iproductgrade,
                'i_product_motif'       => $iproductmotif,
                'n_quantity'            => $nquantity,
                'v_unit_price'          => $vunitprice,
                'e_product_name'        => $eproductname,
                'e_remark'              => $eremark,
                'i_refference_document' => 'HADIAH',
                'e_mutasi_periode'      => $thbl,
                'n_item_no'             => $i
            )
        );        
        $this->db->insert('tm_bbk_item');
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query = $this->db->query("
            SELECT
                n_quantity_awal,
                n_quantity_akhir,
                n_quantity_in,
                n_quantity_out
            FROM
                tm_ic_trans
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
            ORDER BY
                i_trans DESC    
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query = $this->db->query("
            SELECT
                n_quantity_stock
            FROM
                tm_ic
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbbk,$q_aw,$q_ak){
        $now   = current_datetime();
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic_trans ( i_product,
                i_product_grade,
                i_product_motif,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_product_name,
                i_refference_document,
                d_transaction,
                n_quantity_in,
                n_quantity_out,
                n_quantity_akhir,
                n_quantity_awal)
            VALUES ( '$iproduct',
            '$iproductgrade',
            '$iproductmotif',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            '$ibbk',
            '$now',
            0,
            $qbbk,
            $q_ak-$qbbk,
            $q_ak )
        ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada   = false;
        $query = $this->db->query("
            SELECT
                i_product
            FROM
                tm_mutasi
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbk = n_mutasi_bbk + $qbbk,
                n_saldo_akhir = n_saldo_akhir-$qbbk
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
    }

    public function insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
        $query = $this->db->query("
            INSERT
                INTO
                tm_mutasi ( i_product,
                i_product_motif,
                i_product_grade,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_mutasi_periode,
                n_saldo_awal,
                n_mutasi_pembelian,
                n_mutasi_returoutlet,
                n_mutasi_bbm,
                n_mutasi_penjualan,
                n_mutasi_returpabrik,
                n_mutasi_bbk,
                n_saldo_akhir,
                n_saldo_stockopname,
                f_mutasi_close)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            'AA',
            '01',
            '00',
            '$emutasiperiode',
            0,
            0,
            0,
            0,
            0,
            0,
            $qbbk,
            $qbbk,
            0,
            'f')
        ",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $ada   = false;
        $query = $this->db->query("
            SELECT
                i_product
            FROM
                tm_ic
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$q_ak){
        $query  = $this->db->query(" 
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock-$qbbk
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbk){
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            0,
            't' )
        ",false);
    }
}

/* End of file Mmaster.php */