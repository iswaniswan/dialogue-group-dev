<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea()
    {
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
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
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'NA';
        }else{
            return 'XX';
        }
    }

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

    public function data($dfrom,$dto,$iarea,$folder,$i_menu,$xarea){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_sjr,
                a.i_area,
                to_char(d_sjr, 'dd-mm-yyyy') AS d_sjr,
                i_sjr_old, 
                b.e_area_name,
                d_sjr_receive AS terima,
                f_sjr_cancel AS status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$folder' AS folder,
                '$i_menu' AS i_menu,
                '$xarea' AS xarea
            FROM
                tm_sjr a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                $sql
                AND a.d_sjr >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_sjr <= to_date('$dto', 'dd-mm-yyyy')
                AND NOT a.d_sjr_receive IS NULL
            ORDER BY
                a.i_sjr DESC"
        , FALSE);

        $datatables->add('action', function ($data) {
            $i_sjr          = trim($data['i_sjr']);
            $f_sjr_cancel   = $data['status'];
            $i_area         = $data['i_area'];
            $xarea          = $data['xarea'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_sjr/$i_area/$dfrom/$dto/$xarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $f_sjr_cancel == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$i_sjr\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
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
        $datatables->hide('xarea');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cancel($isj, $iarea){
        return $this->db->query("UPDATE tm_sjr set f_sjr_cancel='t' WHERE i_sjr='$isj' and i_area='$iarea'");
    }

    public function baca($isjr,$iarea){
        $query = $this->db->query("
            SELECT
                DISTINCT(c.i_store),
                c.i_store_location,
                c.i_store_locationbin,
                a.*,
                b.e_area_name
            FROM
                tm_sjr a,
                tr_area b,
                tm_sjr_item c
            WHERE
                a.i_area = b.i_area
                AND a.i_sjr = c.i_sjr
                AND a.i_area = c.i_area
                AND a.i_sjr = '$isjr'
                AND a.i_area = '$iarea'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isjr,$iarea){
        $query = $this->db->query("
            SELECT
                a.i_sjr,
                a.d_sjr,
                a.i_area,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                a.n_quantity_receive,
                a.n_quantity_retur,
                a.v_unit_price,
                a.e_product_name,
                a.i_store,
                a.i_store_location,
                a.i_store_locationbin,
                a.e_remark,
                b.e_product_motifname
            FROM
                tm_sjr_item a,
                tr_product_motif b
            WHERE
                a.i_sjr = '$isjr'
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

    public function updatesjheader($isj,$iarea,$isjold,$dsj,$vsjnetto,$vsjrec){
        $this->db->set(
            array(
                'i_sjr_old'     => $isjold,
                'v_sjr'        => $vsjnetto,
                'v_sjr_receive' => $vsjrec,
                'd_sjr_receive' => $dsj,
                'd_sjr_update'  => current_datetime(),
            )
        );
        $this->db->where('i_sjr',$isj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_sjr');
    }

    public function deletesjdetail($isj, $iarea, $iproduct, $iproductgrade, $iproductmotif) {
        $this->db->query("
            DELETE
            FROM
                tm_sjr_item
            WHERE
                i_sjr = '$isj'
                AND i_area = '$iarea'
                AND i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
        ");
    }

    public function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj){
        $queri = $this->db->query("
            SELECT
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
                AND i_refference_document = '$isj'
        ");
        if($queri->num_rows()>0){
            $row        = $queri->row();
            $query=$this->db->query("
                DELETE
                FROM
                    tm_ic_trans
                WHERE
                    i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
                    AND i_store = '$istore'
                    AND i_store_location = '$istorelocation'
                    AND i_store_locationbin = '$istorelocationbin'
                    AND i_refference_document = '$isj'
            ",false);
        }else{
            $queri = $this->db->query("
                SELECT
                    LAST_VALUE
                FROM
                    seq_ic_trans
            ");
            if($queri->num_rows()>0){
                $row          = $queri->row();
                $row->i_trans=$row->last_value;
            }
        }
        return $row->i_trans;
    }

    public function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
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

    public function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,$vunitprice,$isj,$dsj,$iarea, $istore,$istorelocation,$istorelocationbin,$eremark,$i){
        $th=substr($dsj,0,4);
        $bl=substr($dsj,5,2);
        $pr=$th.$bl;
        $this->db->set(
            array(
                'i_sjr'              => $isj,
                'd_sjr'              => $dsj,
                'i_area'             => $iarea,
                'i_product'          => $iproduct,
                'i_product_motif'    => $iproductmotif,
                'i_product_grade'    => $iproductgrade,
                'e_product_name'     => $eproductname,
                'n_quantity_retur'   => $nretur,
                'n_quantity_receive' => $nreceive,
                'v_unit_price'       => $vunitprice,
                'i_store'            => $istore,
                'i_store_location'   => $istorelocation,
                'i_store_locationbin'=> $istorelocationbin, 
                'e_remark'           => $eremark,
                'e_mutasi_periode'   => $pr,
                'n_item_no'          => $i
            )
        );        
        $this->db->insert('tm_sjr_item');
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
                d_transaction DESC
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
                tm_ic_trans ( i_product, i_product_grade, i_product_motif, i_store, i_store_location, i_store_locationbin, e_product_name, i_refference_document, d_transaction, n_quantity_in, n_quantity_out, n_quantity_akhir, n_quantity_awal, i_trans)
            VALUES ( '$iproduct', '$iproductgrade', '$iproductmotif', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', '$isj', '$now', $q_in + $qsj, $q_out, $q_ak + $qsj, $q_aw, $trans )
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
