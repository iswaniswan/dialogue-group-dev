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

     public function bacaarea($iarea, $username, $idcompany){
        if ($iarea=='00') {
            $this->db->select('*');
            $this->db->from('tr_area');
            $this->db->order_by('i_area');
            return $this->db->get()->result();
        }else{
            $this->db->select("
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
                ORDER BY
                    i_area
            ", FALSE);
            return $this->db->get()->result();
        }
    }

    public function getspmb($cari,$iarea,$dfrom){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT a.i_spmb,
                TO_CHAR(a.d_spmb, 'dd-mm-yyyy') AS d_spmb
            FROM
                tm_spmb a,
                tm_spmb_item b
            WHERE
                a.i_spmb = b.i_spmb
                AND a.f_spmb_consigment = 't'
                AND a.f_spmb_cancel = 'f'
                AND a.d_spmb >= '$dfrom'
                AND a.i_area = b.i_area
                AND UPPER(a.i_spmb) LIKE '%$cari%'
            ORDER BY
                a.i_spmb DESC
        ", FALSE);
    }

    public function getdetailspmb($ispmb, $iarea){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                b.n_deliver,
                a.e_product_motifname AS namamotif,
                b.n_saldo AS n_qty,
                c.e_product_name AS nama,
                b.v_unit_price AS harga,
                b.i_product_grade AS grade,
                b.n_order
            FROM
                tr_product_motif a,
                tr_product c,
                tm_spmb_item b
            WHERE
                a.i_product = c.i_product
                AND b.i_product_motif = a.i_product_motif
                AND c.i_product = b.i_product
                AND b.i_spmb = '$ispmb'
                AND i_area = '$iarea'
            ORDER BY
                b.n_item_no
        ", FALSE);
    }

    public function getcustomer($cari,$iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_customer,
                a.e_customer_name,
                i_spg 
            FROM
                tr_customer a,
                tr_customer_consigment b,
                tr_spg c
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = b.i_customer
                AND a.i_customer = c.i_customer
                AND (UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(a.e_customer_name) LIKE '%$cari%')
            ORDER BY
                a.i_customer
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

    public function runningnumbersj($iareasj,$thbl){
        $th     = substr($thbl,0,4);
        $asal   = $thbl;
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" 
            n_modul_no as max 
            FROM tm_dgu_no 
            WHERE i_modul='SB'
            AND e_periode='$asal'
            AND i_area='$iareasj' 
            FOR UPDATE
        ", FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nosj = $terakhir+1;
            $this->db->query(" 
                UPDATE tm_dgu_no 
                SET n_modul_no = $nosj
                WHERE i_modul = 'SB'
                AND e_periode='$asal'
                AND i_area = '$iareasj'
            ", false);
            settype($nosj,"string");
            $a=strlen($nosj);
            while($a<4){
                $nosj="0".$nosj;
                $a=strlen($nosj);
            }
            $nosj  ="SB-".$thbl."-".$iareasj.$nosj;
            return $nosj;
        }else{
            $nosj  ="0001";
            $nosj  ="SB-".$thbl."-".$iareasj.$nosj;
            $this->db->query(" 
                INSERT INTO tm_dgu_no
                (i_modul, i_area, e_periode, n_modul_no) 
                VALUES 
                ('SB','$iareasj','$asal',1)
            ");
            return $nosj;
        }
    }

    public function insertsjpb($isjpb, $ispmb, $icustomer, $iarea, $ispg, $dsjpb, $vsjpb){
        $dsjentry = current_datetime();
        $this->db->set(
            array(      
                'i_sjpb'        => $isjpb,
                'i_spmb'        => $ispmb,
                'i_customer'    => $icustomer,
                'i_area'        => $iarea,
                'i_spg'         => $ispg,
                'd_sjpb'        => $dsjpb,
                'v_sjpb'        => $vsjpb,
                'f_sjpb_cancel' => 'f',
                'd_sjpb_entry'  => $dsjentry
            )
        );
        $this->db->insert('tm_sjpb');
    }

    public function insertsjpbdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isjpb,$iarea,$i,$dsjpb,$ipricegroup){
        $this->db->set(
            array(
                'i_sjpb'          => $isjpb,
                'i_area'          => $iarea,
                'i_product'       => $iproduct,
                'i_product_motif' => $iproductmotif,
                'i_product_grade' => $iproductgrade,
                'n_deliver'       => $ndeliver,
                'v_unit_price'    => $vunitprice,
                'e_product_name'  => $eproductname,
                'd_sjpb'          => $dsjpb,
                'n_item_no'       => $i,
                'i_price_group'   => $ipricegroup
            )
        );
        $this->db->insert('tm_sjpb_item');
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query=$this->db->query(" 
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

    public function inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak){
        $now = current_datetime();
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
            '$isj',
            '$now',
            0,
            $qsj,
            $q_ak-$qsj,
            $q_ak )
        ",false);
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
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
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
                AND i_product_motif = 'iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if ($query->num_rows() > 0){
            $hasil = true;
        }
        return $hasil;
    }

    public function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbk = n_mutasi_bbk + $qsj,
                n_saldo_akhir = n_saldo_akhir-$qsj
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

    public function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$qaw){
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
                f_mutasi_close)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$emutasiperiode',
            $qaw,
            0,
            0,
            0,
            0,
            0,
            $qsj,
            $qaw-$qsj,
            0,
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

    public function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
        $query=$this->db->query(" 
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock-$qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj,$q_aw){
        $query=$this->db->query(" 
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
            $q_aw-$qsj,
            't' )
        ",false);
    }
}

/* End of file Mmaster.php */