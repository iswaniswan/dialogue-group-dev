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

    public function getspg($username, $idcompany){
        return $this->db->query("
            SELECT
                a.i_customer,
                a.i_area,
                a.e_spg_name,
                b.e_area_name,
                c.e_customer_name
            FROM
                tr_spg a,
                tr_area b,
                tr_customer c
            WHERE
                UPPER(a.i_spg) = '$username'
                AND a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND a.i_area = b.i_area
                AND a.i_customer = c.i_customer
        ", false);
    }

    public function data($folder, $icustomer, $iarea){
        if ($icustomer=='ADMINMO') {
            $sql = "a.i_customer = b.i_customer
            AND a.d_sjpb_receive IS NULL
            AND a.f_sjpb_cancel = 'f'";
        }else{
            $sql = "a.i_customer = b.i_customer
            AND a.i_customer = '$icustomer'
            AND a.i_area = '$iarea'
            AND a.d_sjpb_receive IS NULL
            AND a.f_sjpb_cancel = 'f'";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_sjpb,
                to_char(d_sjpb, 'dd-mm-yyyy') AS d_sjpb,
                a.i_area,
                e_customer_name,
                i_sjp,
                '$folder' AS folder 
            FROM
                tm_sjpb a,
                tr_customer b
            WHERE
                $sql
            ORDER BY
                i_sjpb DESC
        ", false);
        $datatables->add('action', function ($data) {
            $isjpb  = trim($data['i_sjpb']);
            $iarea  = trim($data['i_area']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjpb/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($isjpb, $iarea){
        $this->db->select("
                a.*,
                to_char(d_sjpb, 'dd-mm-yyyy') AS dsjpb,
                to_char(d_sjp, 'dd-mm-yyyy') AS dsjp,
                b.e_customer_name,
                c.e_area_name
            FROM
                tm_sjpb a,
                tr_customer b,
                tr_area c
            WHERE
                a.i_customer = b.i_customer
                AND a.f_sjpb_cancel = 'f'
                AND a.i_area = c.i_area
                AND a.i_sjpb = '$isjpb'
                AND a.i_area = '$iarea' 
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isjpb, $iarea){
        $this->db->select("
                a.i_sjpb,
                a.d_sjpb,
                a.i_area,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                a.n_deliver,
                a.n_receive,
                a.v_unit_price,
                a.e_product_name,
                b.e_product_motifname
            FROM
                tm_sjpb_item a,
                tr_product_motif b
            WHERE
                a.i_sjpb = '$isjpb'
                AND a.i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function updatesjheader($isjpb,$iarea,$dsjreceive,$vsjnetto,$vsjrec){
        $dsjupdate= current_datetime();
        $this->db->set(
            array(
                'v_sjpb_receive' => $vsjrec,
                'd_sjpb_receive' => $dsjreceive,
                'd_sjpb_update'  => $dsjupdate

            )
        );
        $this->db->where('i_sjpb',$isjpb);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_sjpb');
    }

    public function deletesjdetail( $isjp, $isjpb, $iarea, $iproduct, $iproductgrade, $iproductmotif, $ndeliver){
        $cek = $this->db->query("
            SELECT
                *
            FROM
                tm_sjpb_item
            WHERE
                i_sjpb = '$isjpb'
                AND i_area = '$iarea'
                AND i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
        ");
        if($cek->num_rows()>0){
            $this->db->query("
                DELETE
                FROM
                    tm_sjpb_item
                WHERE
                    i_sjpb = '$isjpb'
                    AND i_area = '$iarea'
                    AND i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
            ");
        }
    }

    public function updatemutasi04($icustomer,$iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj){
        if( ($qsj=='')||($qsj==null) ) $qsj=0;
        $query=$this->db->query(" 
            UPDATE
                tm_mutasi_consigment
            SET
                n_mutasi_daripusat = n_mutasi_daripusat-$qsj,
                n_saldo_akhir = n_saldo_akhir-$qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if($emutasiperiodesj==$emutasiperiode){
            $query=$this->db->query("
                UPDATE
                    tm_mutasi
                SET
                    n_mutasi_git = n_mutasi_git + $qsj
                WHERE
                    i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
                    AND i_store = '$istore'
                    AND i_store_location = '$istorelocation'
                    AND i_store_locationbin = '$istorelocationbin'
                    AND e_mutasi_periode = '$emutasiperiodesj'
            ",false);
        }
    }

    public function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
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

    public function insertsjpbdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isjpb,$iarea,$i,$dsjpb,$nreceive){
        $this->db->set(
            array(
                'i_sjpb'          => $isjpb,
                'i_area'          => $iarea,
                'i_product'       => $iproduct,
                'i_product_motif' => $iproductmotif,
                'i_product_grade' => $iproductgrade,
                'n_deliver'       => $ndeliver,
                'n_receive'       => $nreceive,
                'v_unit_price'    => $vunitprice,
                'e_product_name'  => $eproductname,
                'd_sjpb'          => $dsjpb,
                'n_item_no'       => $i
            )
        );
        $this->db->insert('tm_sjpb_item');
    }

    public function approve($iadj, $iarea, $user){
        $now = current_datetime();
        $this->db->set(
            array(
                'i_approve' => $user,
                'd_approve' => $now
            )
        );
        $this->db->where('i_adj',$iadj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_adj');
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
                d_transaction DESC
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
        $query=$this->db->query(" 
            SELECT
                n_quantity_stock
            FROM
                tm_ic_consigment
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode){
        $hasil = 'kosong';
        $query = $this->db->query("
            SELECT
                i_product
            FROM
                tm_mutasi_consigment
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if ($query->num_rows() > 0){
            $hasil='ada';
        }
        return $hasil;
    }

    public function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode,$emutasiperiodesj,$iarea){
        if( ($qsj=='')||($qsj==null) ) $qsj=0;
        $query=$this->db->query(" 
            UPDATE
                tm_mutasi_consigment
            SET
                n_mutasi_daripusat = n_mutasi_daripusat + $qsj,
                n_saldo_akhir = n_saldo_akhir + $qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if($emutasiperiodesj==$emutasiperiode){
            $query = $this->db->query(" 
                UPDATE
                    tm_mutasi
                SET
                    n_mutasi_git = n_mutasi_git-$qsj
                WHERE
                    i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
                    AND i_store = '$iarea'
                    AND i_store_location = 'PB'
                    AND i_store_locationbin = '00'
                    AND e_mutasi_periode = '$emutasiperiodesj'
            ",false);
        }
    }

    public function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode,$emutasiperiodesj,$iarea){
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
        $query=$this->db->query("
            INSERT
                INTO
                tm_mutasi_consigment ( i_product,
                i_product_motif,
                i_product_grade,
                i_customer,
                e_mutasi_periode,
                n_saldo_awal,
                n_mutasi_daripusat,
                n_mutasi_darilang,
                n_mutasi_penjualan,
                n_mutasi_kepusat,
                n_saldo_akhir,
                n_saldo_stockopname,
                f_mutasi_close,
                n_mutasi_git)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$icustomer',
            '$emutasiperiode',
            0,
            $qsj,
            0,
            0,
            0,
            $qsj,
            0,
            'f',
            0)
        ",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
        $ada=false;
        $query=$this->db->query("
            SELECT
                i_product
            FROM
                tm_ic_consigment
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
        ",false);
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$q_ak){
        if( ($q_ak=='')||($q_ak==null) ) {
            $q_ak=0;
        }
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
        $query=$this->db->query(" 
            UPDATE
                tm_ic_consigment
            SET
                n_quantity_stock = $q_ak + $qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
        ",false);
    }

    public function insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$qsj){
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
        $query=$this->db->query(" 
            INSERT
                INTO
                tm_ic_consigment
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$icustomer',
            '$eproductname',
            $qsj,
            't' )
        ",false);
    }
}

/* End of file Mmaster.php */