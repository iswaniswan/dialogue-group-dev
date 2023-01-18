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
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $iarea = $row->i_area;
        }
        return $iarea;
    }

    public function getarea($iarea, $username, $idcompany){
        if ($iarea=='00') {
            $this->db->select('a.i_area, a.e_area_name, a.i_store, b.i_store_location, b.i_store_locationbin');
            $this->db->from('tr_area a');
            $this->db->join('tr_store_location b','a.i_store=b.i_store');
            $this->db->where('a.f_area_consigment','t');
            $this->db->where('b.i_store_location','PB');
            $this->db->order_by('a.i_area');
            return $this->db->get()->result();
        }else{
            $this->db->select('*');
            $this->db->from('tr_area a');
            $this->db->join('tr_store_location b','a.i_store=b.i_store');
            $this->db->where('a.f_area_consigment','t');
            $this->db->where('a.i_area','PB');
            $this->db->order_by('a.i_area');
            return $this->db->get()->result();
        }
    }

    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_customer,
                e_customer_name,
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
                a.i_customer", 
        FALSE);
    } 

    public function getproduct($cari, $istore, $icustomer){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                e.i_product,
                a.e_product_name
            FROM
                tm_ic a,
                tr_product_motif b,
                tr_product c,
                tr_customer_consigment d,
                tr_product_priceco e
            WHERE
                (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(a.e_product_name) LIKE '%$cari%')
                AND b.i_product_motif = '00'
                AND a.i_store_location = '00'
                AND d.i_customer = '$icustomer'
                AND e.i_price_groupco = d.i_price_groupco
                AND c.i_product = e.i_product
                AND a.i_product = e.i_product
                AND a.i_product = b.i_product
                AND a.i_product = c.i_product
                AND i_store = '$istore'
            GROUP BY
                e.i_product,
                e.i_product_grade,
                a.i_product_motif,
                b.e_product_motifname,
                e.v_product_retail,
                a.i_product_grade,
                a.i_store,
                a.i_store_location,
                a.i_store_locationbin,
                a.e_product_name,
                a.n_quantity_stock,
                a.f_product_active
            ORDER BY
                e.i_product", 
        FALSE);
    } 

    public function getdetailproduct($iproduct, $istore, $icustomer){
        return $this->db->query("
            SELECT
                e.i_product,
                e.i_product_grade,
                a.i_product_motif,
                b.e_product_motifname,
                e.v_product_retail,
                a.i_product_grade,
                a.i_store,
                a.i_store_location,
                a.i_store_locationbin,
                a.e_product_name,
                a.n_quantity_stock,
                a.f_product_active
            FROM
                tm_ic a,
                tr_product_motif b,
                tr_product c,
                tr_customer_consigment d,
                tr_product_priceco e
            WHERE
                a.i_product = '$iproduct'
                AND b.i_product_motif = '00'
                AND a.i_store_location = '00'
                AND d.i_customer = '$icustomer'
                AND e.i_price_groupco = d.i_price_groupco
                AND c.i_product = e.i_product
                AND a.i_product = e.i_product
                AND a.i_product = b.i_product
                AND a.i_product = c.i_product
                AND i_store = '$istore'
            GROUP BY
                e.i_product,
                e.i_product_grade,
                a.i_product_motif,
                b.e_product_motifname,
                e.v_product_retail,
                a.i_product_grade,
                a.i_store,
                a.i_store_location,
                a.i_store_locationbin,
                a.e_product_name,
                a.n_quantity_stock,
                a.f_product_active
            ORDER BY
                e.i_product", 
        FALSE);
    } 

    public function insertsjpb($isjpb, $icustomer, $iarea, $ispg, $dsjpb, $vsjpb){
        $dsjentry = current_datetime();
        $this->db->set(
            array(
                'i_sjpb'        => $isjpb,
                'i_sjp'         => '',
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

    public function runningnumbersj($iareasj,$thbl){
        $th   = substr($thbl,0,4);
        $asal = $thbl;
        $thbl = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no AS max 
            FROM tm_dgu_no 
            WHERE i_modul='SB'
            AND i_area='$iareasj'
            AND e_periode='$asal' 
            for update
        ", false);
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
                AND i_area = '$iareasj'
                AND e_periode = '$asal' 
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

    public function insertsjpbdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isjpb,$iarea,$i,$dsjpb,$ipricegroup){
        $this->db->set(
            array(
                'i_product'       => $iproduct,
                'i_product_grade' => $iproductgrade,
                'i_product_motif' => $iproductmotif,
                'e_product_name'  => $eproductname,
                'n_deliver'       => $ndeliver,
                'v_unit_price'    => $vunitprice,
                'i_sjpb'          => $isjpb,
                'i_area'          => $iarea,
                'n_item_no'       => $i,        
                'd_sjpb'          => $dsjpb,
                'i_price_group'   => $ipricegroup
            )
        );
        $this->db->insert('tm_sjpb_item');
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $this->db->select('n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out');
        $this->db->from('tm_ic_trans');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        $this->db->order_by('i_trans','desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $this->db->select('n_quantity_stock');
        $this->db->from('tm_ic');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        /*$this->db->order_by('i_trans','desc');*/
        $query = $this->db->get();
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

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada=false;
        $this->db->select('i_product');
        $this->db->from('tm_mutasi');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        $this->db->where('e_mutasi_periode',$emutasiperiode);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
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

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $ada=false;
        $this->db->select('i_product');
        $this->db->from('tm_ic');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }
}

/* End of file Mmaster.php */