<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
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
        ", FALSE);
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_sjp,
                a.i_area,
                to_char(d_sjp, 'dd-mm-yyyy') AS d_sjp,
                i_sjp_old, 
                b.e_area_name,
                to_char(d_sjp_receive , 'dd-mm-yyyy') AS d_sjp_receive,
                d_sjp_receive AS terima,
                CASE WHEN c.f_spmb_consigment = 't' THEN 'Ya' ELSE 'Tidak' END AS konsinyasi,
                f_sjp_cancel AS status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$folder' AS folder,
                '$i_menu' AS i_menu
            FROM
                tm_sjp a,
                tr_area b,
                tm_spmb c
            WHERE
                a.i_area = b.i_area
                AND a.i_spmb = c.i_spmb
                $sql
                AND a.d_sjp_receive >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_sjp_receive <= to_date('$dto', 'dd-mm-yyyy')
                AND NOT a.d_sjp_receive IS NULL
            ORDER BY
                a.i_sjp DESC"
        , FALSE);

        $datatables->add('action', function ($data) {
            $i_sjp          = trim($data['i_sjp']);
            $f_sjp_cancel   = $data['status'];
            $i_area         = $data['i_area'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_sjp/$i_area/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            /*if(check_role($i_menu, 4) && $f_sjp_cancel == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$i_sjp\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }*/
            return $data;
        });

        $datatables->edit('status', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->edit('terima', function ($data) {
            if ($data['terima']!=null) {
                $data = '<span class="label label-info label-rouded">Sudah</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Belum</span>';
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function baca($isjp,$iarea){
        $query = $this->db->query("
            SELECT
                DISTINCT(c.i_store),
                a.*,
                b.e_area_name
            FROM
                tm_sjp a,
                tr_area b,
                tm_sjp_item c
            WHERE
                a.i_area = b.i_area
                AND a.i_sjp = c.i_sjp
                AND a.i_area = c.i_area
                AND a.i_sjp = '$isjp'
                AND a.i_area = '$iarea'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isjp,$iarea){
        $query = $this->db->query("
            SELECT
                a.i_sjp,
                a.d_sjp,
                a.i_area,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                a.n_quantity_receive,
                a.n_quantity_deliver,
                a.v_unit_price,
                a.e_product_name,
                a.i_store,
                a.i_store_location,
                a.i_store_locationbin,
                a.e_remark,
                b.e_product_motifname
            FROM
                tm_sjp_item a,
                tr_product_motif b
            WHERE
                a.i_sjp = '$isjp'
                AND a.i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function product($cari) {
        $cari = str_replace("'", "", $cari);      
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
                AND (upper(a.i_product) LIKE '%$cari%'
                OR upper(a.e_product_name) LIKE '%$cari%')
                AND a.i_product = c.i_product
            ORDER BY
                a.e_product_name
            ",false);
    }

    public function detailproduct($iproduct) {
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
                AND a.i_product = c.i_product
                AND b.i_price_group = '00'
                AND a.i_product = '$iproduct'
            ORDER BY
                a.e_product_name
            ",false);
    }

    public function updatesjheader($isj,$iarea,$isjold,$dsj,$vsjnetto,$vsjpec){
        $this->db->set(
            array(
                'i_sjp_old'     => $isjold,
                'v_sjp'         => $vsjnetto,
                'v_sjp_receive' => $vsjpec,
                'd_sjp_receive' => $dsj,
                'd_sjp_update'  => current_datetime(),
            )
        );
        $this->db->where('i_sjp',$isj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_sjp');
    }

    public function deletesjdetail($isj, $iarea, $iproduct, $iproductgrade, $iproductmotif) {
        $this->db->query("
            DELETE
            FROM
                tm_sjp_item
            WHERE
                i_sjp = '$isj'
                AND i_area = '$iarea'
                AND i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
        ");
    }

    public function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname){
        $queri = $this->db->query("
            SELECT
                n_quantity_akhir,
                i_trans
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
        ");
        if ($queri->num_rows() > 0){
            $row  = $queri->row();
            $now  = current_datetime();
            $query=$this->db->query("
                INSERT
                    INTO
                    tm_ic_trans ( i_product, i_product_grade, i_product_motif, i_store, i_store_location, i_store_locationbin, e_product_name, i_refference_document, d_transaction, n_quantity_in, n_quantity_out, n_quantity_akhir, n_quantity_awal)
                VALUES ( '$iproduct', '$iproductgrade', '$iproductmotif', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', '$isj', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir )
            ",false);
            if($row->i_trans!=''){
                return $row->i_trans;
            }else{
                return 1;
            }
        }
    }

    public function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
        $query=$this->db->query(" 
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbm = n_mutasi_bbm-$qsj,
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

    public function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
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

    public function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$ndeliver,$vunitprice,$isj,$dsj,$iarea, $istore,$istorelocation,$istorelocationbin,$eremark,$i){
        $th=substr($dsj,0,4);
        $bl=substr($dsj,5,2);
        $pr=$th.$bl;
        $this->db->set(
            array(
                'i_sjp'               => $isj,
                'd_sjp'               => $dsj,
                'i_area'              => $iarea,
                'i_product'           => $iproduct,
                'i_product_motif'     => $iproductmotif,
                'i_product_grade'     => $iproductgrade,
                'e_product_name'      => $eproductname,
                'n_quantity_deliver'  => $ndeliver,
                'n_quantity_receive'  => $nreceive,
                'n_saldo'             => $nreceive,
                'v_unit_price'        => $vunitprice,
                'i_store'             => $istore,
                'i_store_location'    => $istorelocation,
                'i_store_locationbin' => $istorelocationbin, 
                'e_remark'            => $eremark,
                'e_mutasi_periode'    => $pr,
                'n_item_no'           => $i
            )
        );        
        $this->db->insert('tm_sjp_item');
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

    public function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak,$trans){
        $now    = current_datetime();
        $query=$this->db->query(" 
            INSERT
                INTO
                tm_ic_trans ( i_product, i_product_grade, i_product_motif, i_store, i_store_location, i_store_locationbin, e_product_name, i_refference_document, d_transaction, n_quantity_in, n_quantity_out, n_quantity_akhir, n_quantity_awal)
            VALUES ( '$iproduct', '$iproductgrade', '$iproductmotif', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', '$isj', '$now', $qsj, 0, $q_ak + $qsj, $q_ak )
        ",false);
    }

    public function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $hasil='kosong';
        $query=$this->db->query(" 
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
        if($query->num_rows() > 0){
            $hasil='ada';
        }
        return $hasil;
    }

    public function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
        $query=$this->db->query(" 
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbm = n_mutasi_bbm + $qsj,
                n_saldo_akhir = n_saldo_akhir + $qsj
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

    public function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
        $query=$this->db->query("
            INSERT
                INTO
                tm_mutasi ( i_product, i_product_motif, i_product_grade, i_store, i_store_location, i_store_locationbin, e_mutasi_periode, n_saldo_awal, n_mutasi_pembelian, n_mutasi_returoutlet, n_mutasi_bbm, n_mutasi_penjualan, n_mutasi_returpabrik, n_mutasi_bbk, n_saldo_akhir, n_saldo_stockopname, f_mutasi_close)
            VALUES ( '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$emutasiperiode', 0, 0, 0, $qsj, 0, 0, 0, $qsj, 0, 'f')
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
    
    public function updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
        if( ($qsj=='')||($qsj==null) ) {
            $qsj=0;
        }
        $query=$this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = $q_ak + $qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj){
        $query=$this->db->query("
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qsj, 't' )
        ",false);
    }
}

/* End of file Mmaster.php */
