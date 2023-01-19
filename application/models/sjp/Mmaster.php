<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('i_area','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $area = '00';
        }else{
            $area = 'xx';
        }
        return $area;
    }

    public function bacastore($iarea, $username, $idcompany){
        if ($iarea=='00') {
            return $this->db->query("
                SELECT
                    DISTINCT(b.i_store) AS i_store ,
                    a.i_store_location,
                    a.e_store_locationname,
                    b.e_store_name
                FROM
                    tr_store_location a,
                    tr_store b
                WHERE
                    a.i_store = b.i_store
                ORDER BY
                    b.i_store
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    DISTINCT(b.i_store) AS i_store ,
                    a.i_store_location,
                    a.e_store_locationname,
                    b.e_store_name
                FROM
                    tr_store_location a,
                    tr_store b,
                    tr_area c
                WHERE
                    a.i_store = b.i_store
                    AND c.i_store = b.i_store 
                    AND c.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
                ORDER BY
                    b.i_store
            ",FALSE);
        }
    }

    public function getspmb($cari,$iarea,$istorelocation){
        $cari = str_replace("'", "", $cari);
        if($istorelocation=='PB' || $iarea=='PB'){
            return $this->db->query("
                SELECT
                    DISTINCT
                    a.i_spmb,
                    to_char(a.d_spmb, 'dd-mm-yyyy') AS d_spmb 
                FROM
                    tm_spmb a,
                    tm_spmb_item b
                WHERE
                    a.i_area = '$iarea'
                    AND a.f_spmb_cancel = 'f'
                    AND a.i_spmb = b.i_spmb
                    AND a.i_area = b.i_area
                    AND a.f_spmb_acc = 't'
                    AND a.f_spmb_close = 'f'
                    AND a.f_spmb_consigment = 't'
                    AND (a.i_store != ''
                    AND a.i_store_location != ''
                    AND a.f_spmb_pemenuhan = 'true')
                    AND UPPER(a.i_spmb) LIKE '%$cari%'
                ORDER BY
                    a.i_spmb DESC
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    DISTINCT
                    a.i_spmb,
                    to_char(a.d_spmb, 'dd-mm-yyyy') AS d_spmb 
                FROM
                    tm_spmb a
                INNER JOIN tm_spmb_item b ON
                    b.i_spmb = a.i_spmb
                    AND a.i_area = b.i_area
                LEFT JOIN tr_area c ON
                    c.i_area = a.i_area
                WHERE
                    c.i_store = '$iarea'
                    AND a.f_spmb_cancel = 'f'
                    AND a.i_spmb = b.i_spmb
                    AND a.i_area = b.i_area
                    AND a.f_spmb_acc = 't'
                    AND a.f_spmb_close = 'f'
                    AND a.f_spmb_consigment = 'f'
                    AND (a.i_store != ''
                    AND a.i_store_location != ''
                    AND a.f_spmb_pemenuhan = 'true')
                    AND UPPER(a.i_spmb) LIKE '%$cari%'
                ORDER BY
                    a.i_spmb DESC
            ", FALSE);
        }        
    }

    public function getdetailspmb($ispmb){
        return $this->db->query("
            SELECT
                CASE WHEN d.n_quantity_stock > b.n_saldo THEN b.n_saldo
                WHEN d.n_quantity_stock <= 0 THEN 0
                WHEN d.n_quantity_stock < b.n_saldo THEN d.n_quantity_stock END AS stock,
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                b.n_acc AS n_order,
                d.n_quantity_stock AS n_deliver,
                b.n_saldo AS n_qty,
                c.e_product_name AS nama,
                c.v_product_retail AS harga,
                b.i_product_grade AS grade
            FROM
                tr_product_motif a
            INNER JOIN tr_product_price c ON
                (a.i_product = c.i_product)
            INNER JOIN tm_spmb_item b ON
                (b.i_product_motif = a.i_product_motif
                AND c.i_product = b.i_product)
            LEFT JOIN tm_ic d ON
                (d.i_product = a.i_product
                AND a.i_product_motif = d.i_product_motif
                AND c.i_product_grade = d.i_product_grade
                AND d.i_store = 'AA'
                AND d.i_store_location = '01'
                AND d.i_store_locationbin = '00')
            WHERE
                c.i_price_group = '00'
                AND c.i_product_grade = 'A'
                AND b.i_spmb = '$ispmb'
                AND b.n_deliver<b.n_acc
            ORDER BY
                b.i_product ASC
            /*SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                b.n_acc AS n_order,
                b.n_saldo AS n_qty,
                c.e_product_name AS nama,
                c.v_product_retail AS harga,
                b.i_product_grade AS grade
            FROM
                tr_product_motif a,
                tr_product_price c,
                tm_spmb_item b
            WHERE
                a.i_product = c.i_product
                AND b.i_product_motif = a.i_product_motif
                AND c.i_product = b.i_product
                AND c.i_price_group = '00'
                AND c.i_product_grade = 'A'
                AND b.i_spmb = '$ispmb'
                AND b.n_deliver<b.n_acc
            ORDER BY
                b.i_product ASC*/
        ", FALSE);
    }

    public function getproduct($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product,
                a.e_product_name
            FROM
                tr_product a,
                tr_product_price b,
                tr_product_motif c
            WHERE
                a.i_product = b.i_product
                AND b.i_price_group = '00'
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(a.e_product_name) LIKE '%$cari%')
                AND a.i_product = c.i_product
                AND a.i_product_status <> '4'
            ORDER BY
                a.e_product_name", 
        FALSE);
    } 

    public function getdetailproduct($iproduct){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.e_product_name AS nama,
                b.v_product_retail AS harga,
                c.i_product_motif AS motif,
                c.e_product_motifname AS namamotif
            FROM
                tr_product a,
                tr_product_price b,
                tr_product_motif c
            WHERE
                a.i_product = b.i_product
                AND b.i_price_group = '00'
                AND a.i_product = '$iproduct'
                AND a.i_product = c.i_product
                AND a.i_product_status <> '4'
            ORDER BY
                a.e_product_name", 
        FALSE);
    } 

    public function runningnumbersj($iarea,$thbl){
        $th     = substr($thbl,0,4);
        $asal   = $thbl;
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" 
            n_modul_no as max 
            FROM tm_dgu_no 
            WHERE i_modul='SJP'
            AND substr(e_periode,1,4)='$th' 
            AND i_area='$iarea' 
            FOR UPDATE
        ", FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nosj  =$terakhir+1;
            $this->db->query(" 
                UPDATE tm_dgu_no 
                SET n_modul_no = $nosj
                WHERE i_modul = 'SJP'
                AND substr(e_periode,1,4)='$th' 
                AND i_area = '$iarea'
            ", false);
            settype($nosj,"string");
            $a=strlen($nosj);
            while($a<4){
                $nosj="0".$nosj;
                $a=strlen($nosj);
            }
            $nosj  ="SJP-".$thbl."-".$iarea.$nosj;
            return $nosj;
        }else{
            $nosj  ="0001";
            $nosj  ="SJP-".$thbl."-".$iarea.$nosj;
            $this->db->query(" 
                INSERT INTO tm_dgu_no
                (i_modul, i_area, e_periode, n_modul_no) 
                VALUES 
                ('SJP','$iarea','$asal',1)
            ");
            return $nosj;
        }
    }

    public function insertsjheader($ispmb,$dspmb,$isj,$dsj,$iarea,$vspbnetto,$isjold){
        $dsjentry   = current_datetime();
        $this->db->set(
            array(
                'i_sjp'         => $isj,
                'i_sjp_old'     => $isjold,
                'i_spmb'        => $ispmb,
                'd_spmb'        => $dspmb,
                'd_sjp'         => $dsj,
                'i_area'        => $iarea,
                'v_sjp'         => $vspbnetto,
                'd_sjp_entry'   => $dsjentry,
                'f_sjp_cancel'  => 'f'
            )
        );        
        $this->db->insert('tm_sjp');
    }

    public function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,$ndeliver,$vunitprice,$ispmb,$dspmb,$isj,$dsj,$iarea,$istore,$istorelocation,$istorelocationbin,$eremark,$i){
        $th=substr($dsj,0,4);
        $bl=substr($dsj,5,2);
        $pr=$th.$bl;
        $this->db->set(
            array(
                'i_sjp'                 => $isj,
                'd_sjp'                 => $dsj,
                'i_area'                => $iarea,
                'i_product'             => $iproduct,
                'i_product_motif'       => $iproductmotif,
                'i_product_grade'       => $iproductgrade,
                'e_product_name'        => $eproductname,
                'n_quantity_order'      => $norder,
                'n_quantity_deliver'    => $ndeliver,
                'v_unit_price'          => $vunitprice,
                'i_store'               => $istore,
                'i_store_location'      => $istorelocation,
                'i_store_locationbin'   => $istorelocationbin, 
                'e_remark'              => $eremark,
                'e_mutasi_periode'      => $pr,
                'n_item_no'             => $i
            )
        );
        $this->db->insert('tm_sjp_item');
    }

    public function updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea){
        $this->db->query(" 
            UPDATE
                tm_spmb_item
            SET
                n_deliver = n_deliver + $ndeliver,
                n_saldo = n_saldo-$ndeliver
            WHERE
                i_spmb = '$ispmb'
                AND i_area = '$iarea'
                AND i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
        ",false);
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query=$this->db->query(" 
            SELECT
                n_quantity_stock
            FROM
                tm_ic
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '00'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function inserttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak){
        $now      = current_datetime();
        $query=$this->db->query(" 
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
            '00',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            '$isj',
            '$now',
            0,
            $qsj,
            $q_ak-$qsj,
            $q_ak )
        ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $hasil='kosong';
        $query=$this->db->query(" 
            SELECT
                i_product
            FROM
                tm_mutasi
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '00'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if ($query->num_rows() > 0){
            $hasil='ada';
        }
        return $hasil;
    }

    public function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_git = n_mutasi_git + $qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '00'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiod'                             
        ",false);
    }

    public function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        $query=$this->db->query(" 
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
                n_mutasi_git,
                f_mutasi_close)
            VALUES ( '$iproduct',
            '00',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$emutasiperiode',
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $qsj,
            'f')
        ",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $ada=false;
        $query=$this->db->query(" 
            SELECT
                i_product
            FROM
                tm_ic
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '00'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
        $query=$this->db->query(" 
            UPDATE
                tm_ic
            SET
                n_quantity_stock = $q_ak-$qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '00'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver){
        $query=$this->db->query(" 
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct',
            '00',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            0-$ndeliver,
            't' )
        ",false);
    }
}

/* End of file Mmaster.php */