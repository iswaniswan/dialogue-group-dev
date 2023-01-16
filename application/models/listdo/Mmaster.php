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
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 
                a.i_do AS id,
                to_char(a.d_do, 'dd-mm-yyyy') AS d_do,
                a.i_area,
                a.i_op,
                c.i_spb,
                d.i_spmb,
                f.i_dtap,
                to_char(f.d_dtap, 'dd-mm-yyyy') AS d_dtap,
                e.e_supplier_name,
                a.f_do_cancel,
                a.i_supplier,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$folder' AS folder,
                '$i_menu' AS i_menu,
                '$iarea' AS iarea
            FROM
                tr_supplier e,
                tm_op b
            LEFT JOIN tm_spb c ON
                (b.i_reff = c.i_spb
                AND b.i_area = c.i_area)
            LEFT JOIN tm_spmb d ON
                (b.i_reff = d.i_spmb
                AND b.i_area = d.i_area),
                tm_do a
            LEFT JOIN tm_dtap_item f ON
                (a.i_do = f.i_do)
            LEFT JOIN tm_dtap g ON
                (f.i_dtap = g.i_dtap
                AND f.i_area = g.i_area
                AND f.i_supplier = g.i_supplier
                AND g.f_dtap_cancel = 'f')
            WHERE
                a.d_do >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_do <= to_date('$dto', 'dd-mm-yyyy')
                AND a.i_op = b.i_op
                AND a.i_area = b.i_area
                AND a.i_supplier = e.i_supplier
                AND a.i_area = '$iarea'
            ORDER BY
                to_char(a.d_do, 'dd-mm-yyyy') DESC"
        , FALSE);

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $iop            = trim($data['i_op']);
            $isupplier      = trim($data['i_supplier']);
            $idtap          = trim($data['i_dtap']);
            $iarea          = $data['iarea'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $status         = $data['f_do_cancel'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$isupplier/$dfrom/$dto/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $status != 't' && ($idtap == '' || $idtap == null)){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$isupplier\",\"$iop\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('f_do_cancel', function ($data) {
            if ($data['f_do_cancel']!='f') {
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }else{
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }
            return $data;
        });

        $datatables->hide('i_supplier');
        $datatables->hide('i_menu');
        $datatables->hide('iarea');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cancel($ido, $isupplier, $op){
        $ido=trim($ido);
        $this->db->select("
                n_deliver,
                i_product,
                i_product_grade,
                i_product_motif
            FROM
                tm_do_item
            WHERE
                i_do = '$ido'
                AND i_supplier = '$isupplier'
        ");
        $query = $this->db->get();
        foreach($query->result() as $row){
            $jml=$row->n_deliver;
            $product=$row->i_product;
            $grade=$row->i_product_grade;
            $motif=$row->i_product_motif;
            $this->db->query("
                UPDATE
                    tm_op_item
                SET
                    n_delivery = n_delivery-$jml
                WHERE
                    i_op = '$op'
                    AND i_product = '$product'
                    AND i_product_grade = '$grade'
                    AND i_product_motif = '$motif'
            ", FALSE);
        }
        $bb='';
        $query=$this->db->query("
            SELECT
                i_bbm
            FROM
                tm_bbm
            WHERE
                i_refference_document = '$ido'
                AND i_supplier = '$isupplier'
        ");
        foreach($query->result() as $raw){
            $bb=$raw->i_bbm;
        }
        $this->db->query("
            UPDATE
                tm_bbm
            SET
                f_bbm_cancel = 't'
            WHERE
                i_refference_document = '$ido'
                AND i_supplier = '$isupplier'   
        ");
        $this->db->query("
            UPDATE
                tm_op
            SET
                f_op_close = 'f'
            WHERE
                i_op = '$op'
        ");
        $this->db->query("
            UPDATE
                tm_do
            SET
                f_do_cancel = 't'
            WHERE
                i_do = '$ido'
                AND i_supplier = '$isupplier'
        ");
        $this->db->select("
                *
            FROM
                tm_do
            WHERE
                i_do = '$ido'
                AND i_supplier = '$isupplier'
        ");
        $qry = $this->db->get();
        if ($qry->num_rows() > 0){
            foreach($qry->result() as $qyr){
                $ddo=$qyr->d_do;
            }
        }
        $th=substr($ddo,0,4);
        $bl=substr($ddo,5,2);
        $emutasiperiode=$th.$bl;
        $istore='AA';
        $istorelocation='01';
        $istorelocationbin='00';
        $this->db->select("
                *
            FROM
                tm_do_item
            WHERE
                i_do = '$ido'
                AND i_supplier = '$isupplier'
            ORDER BY
                n_item_no
                ");
        $qery = $this->db->get();
        if ($qery->num_rows() > 0){
            foreach($qery->result() as $qyre){
                $queri = $this->db->query("
                    SELECT
                        n_quantity_akhir,
                        i_trans
                    FROM
                        tm_ic_trans
                    WHERE
                        i_product = '$qyre->i_product'
                        AND i_product_grade = '$qyre->i_product_grade'
                        AND i_product_motif = '$qyre->i_product_motif'
                        AND i_store = '$istore'
                        AND i_store_location = '$istorelocation'
                        AND i_store_locationbin = '$istorelocationbin'
                        AND i_refference_document = '$ido'
                    ORDER BY
                        d_transaction DESC,
                        i_trans DESC
                ",false);
                if ($queri->num_rows() > 0){
                    $row   = $queri->row();
                    $now   = current_datetime();
                    $this->db->query("
                        INSERT
                            INTO
                            tm_ic_trans ( i_product, i_product_grade, i_product_motif, i_store, i_store_location, i_store_locationbin, e_product_name, i_refference_document, d_transaction, n_quantity_in, n_quantity_out, n_quantity_akhir, n_quantity_awal)
                        VALUES ( '$qyre->i_product', '$qyre->i_product_grade', '$qyre->i_product_motif', '$istore', '$istorelocation', '$istorelocationbin', '$qyre->e_product_name', '$ido', '$now', 0, $qyre->n_deliver, $row->n_quantity_akhir-$qyre->n_deliver, $row->n_quantity_akhir )
                    ",false);
                }
                $this->db->query("
                    UPDATE
                        tm_mutasi
                    SET
                        n_mutasi_pembelian = n_mutasi_pembelian-$qyre->n_deliver,
                        n_saldo_akhir = n_saldo_akhir-$qyre->n_deliver
                    WHERE
                        i_product = '$qyre->i_product'
                        AND i_product_grade = '$qyre->i_product_grade'
                        AND i_product_motif = '$qyre->i_product_motif'
                        AND i_store = '$istore'
                        AND i_store_location = '$istorelocation'
                        AND i_store_locationbin = '$istorelocationbin'
                        AND e_mutasi_periode = '$emutasiperiode'
                ",false);
                $this->db->query("
                    UPDATE
                        tm_ic
                    SET
                        n_quantity_stock = n_quantity_stock-$qyre->n_deliver
                    WHERE
                        i_product = '$qyre->i_product'
                        AND i_product_grade = '$qyre->i_product_grade'
                        AND i_product_motif = '$qyre->i_product_motif'
                        AND i_store = '$istore'
                        AND i_store_location = '$istorelocation'
                        AND i_store_locationbin = '$istorelocationbin'
                ",false);
            }
        }
        return TRUE;
    }

    public function baca($id,$isupplier){
        $query = $this->db->query("
            SELECT
                a.*,
                b.*,
                c.*
            FROM
                tm_do a,
                tr_supplier b,
                tr_area c
            WHERE
                a.i_supplier = b.i_supplier
                AND a.i_area = c.i_area
                AND a.i_do = '$id'
                AND a.i_supplier = '$isupplier'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($id,$isupplier){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_product_motifname,
                c.n_order
            FROM
                tm_do_item a,
                tr_product_motif b,
                tm_op_item c
            WHERE
                a.i_do = '$id'
                AND i_supplier = '$isupplier'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
                AND a.i_product = c.i_product
                AND a.i_op = c.i_op
            ORDER BY
                a.i_product ASC
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function product($cari,$op) {
        $cari = str_replace("'", "", $cari);
            return $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.e_product_name AS nama,
                    d.v_product_mill AS harga,
                    b.n_order
                FROM
                    tr_product_motif a,
                    tr_product c,
                    tm_op_item b,
                    tr_harga_beli d
                WHERE
                    b.i_op = '$op'
                    AND a.i_product = c.i_product
                    AND (b.n_delivery<b.n_order
                    OR b.n_delivery ISNULL)
                    AND b.i_product = a.i_product
                    AND b.i_product_motif = a.i_product_motif
                    AND b.i_product = d.i_product
                    AND d.i_price_group = '00'
                    AND (UPPER(b.i_product) LIKE '%$cari%'
                    OR UPPER(c.e_product_name) LIKE '%$cari%')
            ", FALSE);
    }

    public function detailproduct($iproduct,$op) {
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                c.e_product_name AS nama,
                d.v_product_mill AS harga,
                b.n_order
            FROM
                tr_product_motif a,
                tr_product c,
                tm_op_item b,
                tr_harga_beli d
            WHERE
                b.i_op = '$op'
                AND a.i_product = c.i_product
                AND (b.n_delivery<b.n_order
                OR b.n_delivery ISNULL)
                AND b.i_product = a.i_product
                AND b.i_product_motif = a.i_product_motif
                AND b.i_product = d.i_product
                AND d.i_price_group = '00'
                AND b.i_product = '$iproduct'
        ", FALSE);
    }

    public function updateheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross,$idoold){
        $data = array(
            'i_do'      => $ido,
            'i_supplier'=> $isupplier,
            'i_op'      => $iop,
            'i_area'    => $iarea,
            'd_do'      => $ddo,
            'v_do_gross'=> $vdogross
        );
        $this->db->where('i_do', $idoold);
        $this->db->where('i_supplier', $isupplier);
        $this->db->where('i_op', $iop);
        $this->db->update('tm_do', $data);
    }

    public function updatehead($ido,$isupplier){
        $query = $this->db->query("
            SELECT
                sum(n_deliver * v_product_mill) AS v_do_gross
            FROM
                tm_do_item
            WHERE
                i_do = '$ido'
                AND i_supplier = '$isupplier'
        ", FALSE);
        if ($query->num_rows()>0) {
            $do       = $query->row();
            $vdogross = $do->v_do_gross;
            $data     = array(
                'v_do_gross'=> $vdogross

            );
            $this->db->where('i_do', $ido);
            $this->db->where('i_supplier', $isupplier);
            $this->db->update('tm_do', $data);
        }
    }

    public function deletedetail($iproduct,$iproductgrade,$ido,$isupplier,$iproductmotif,$tahun,$idoold){
        $this->db->query("
            DELETE
            FROM
                tm_do_item
            WHERE
                i_do = '$idoold'
                AND i_supplier = '$isupplier'
                AND i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
        ");
        $idoold=trim($idoold);
        $this->db->query("
            DELETE
            FROM
                tm_bbm_item
            WHERE
                i_refference_document = '$idoold'
                AND i_bbm_type = '04'
                AND to_char(d_refference_document, 'yyyy')= '$tahun'
                AND i_product = '$iproduct'
                AND i_product_motif = '$iproductmotif'
                AND i_product_grade = '$iproductgrade'
        ");
        $this->db->query("
            DELETE
            FROM
                tm_bbk_item
            WHERE
                i_refference_document = '$idoold'
                AND to_char(d_refference_document, 'yyyy')= '$tahun'
                AND i_product = '$iproduct'
                AND i_product_motif = '$iproductmotif'
                AND i_product_grade = '$iproductgrade'
        ");
        return TRUE;
    }

    public function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ido,$ntmp,$eproductname){
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
        ",false);
        if ($queri->num_rows() > 0){
            $row   = $queri->row();
            $now   = current_datetime();
            if($ntmp!=0 || $ntmp!=''){
                $query=$this->db->query("
                    INSERT
                        INTO
                        tm_ic_trans ( i_product, i_product_grade, i_product_motif, i_store, i_store_location, i_store_locationbin, e_product_name, i_refference_document, d_transaction, n_quantity_in, n_quantity_out, n_quantity_akhir, n_quantity_awal)
                    VALUES ( '$iproduct', '$iproductgrade', '$iproductmotif', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', '$ido', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir )
                ",false);
            }
        }
        if(isset($row->i_trans)){
            if($row->i_trans!=''){
                return $row->i_trans;
            }else{
                return 1;
            }
        }else{
            return 1;
        }
    }

    public function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_pembelian = n_mutasi_pembelian-$qsj,
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

    public function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
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

    public function insertdetail($iop,$ido,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vproductmill,$ddo,$eremark,$i,$idoold){
        $th=substr($ddo,0,4);
        $bl=substr($ddo,5,2);
        $pr=$th.$bl;
        $this->db->set(
            array(
                'i_do'            => $ido,
                'd_do'            => $ddo,
                'i_supplier'      => $isupplier,
                'i_product'       => $iproduct,
                'i_product_grade' => $iproductgrade,
                'i_product_motif' => $iproductmotif,
                'e_product_name'  => $eproductname,
                'n_deliver'       => $ndeliver,
                'v_product_mill'  => $vproductmill,
                'i_op'            => $iop,
                'e_remark'        => $eremark,
                'e_mutasi_periode'=> $pr,
                'n_item_no'       => $i
            )
        );
        $this->db->insert('tm_do_item');
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

    public function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak){
        $now   = current_datetime();
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic_trans ( i_product, i_product_grade, i_product_motif, i_store, i_store_location, i_store_locationbin, e_product_name, i_refference_document, d_transaction, n_quantity_in, n_quantity_out, n_quantity_akhir, n_quantity_awal)
            VALUES ( '$iproduct', '$iproductgrade', '$iproductmotif', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', '$ido', '$now', $qdo, 0, $q_ak + $qdo, $q_ak )
        ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada=false;
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
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_pembelian = n_mutasi_pembelian + $qdo,
                n_saldo_akhir = n_saldo_akhir + $qdo
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

    public function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
            INSERT
                INTO
                tm_mutasi ( i_product, i_product_motif, i_product_grade, i_store, i_store_location, i_store_locationbin, e_mutasi_periode, n_saldo_awal, n_mutasi_pembelian, n_mutasi_returoutlet, n_mutasi_bbm, n_mutasi_penjualan, n_mutasi_returpabrik, n_mutasi_bbk, n_saldo_akhir, n_saldo_stockopname, f_mutasi_close)
            VALUES ( '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$emutasiperiode', 0, $qdo, 0, 0, 0, 0, 0, $qdo, 0, 'f')
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

    public function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak){
        $query=$this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = $q_ak + $qdo
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo){
        $query=$this->db->query("
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qdo, 't' )
        ",false);
    }

    public function updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$ndeliverhidden,$ntmp){
        if($ntmp==''){
            $this->db->query("
                UPDATE
                    tm_op_item
                SET
                    n_delivery = n_delivery + $ndeliver
                WHERE
                    i_op = '$iop'
                    AND i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
            ");
        }else{
            $this->db->query("
                UPDATE
                    tm_op_item
                SET
                    n_delivery = n_delivery + $ndeliver-$ntmp
                WHERE
                    i_op = '$iop'
                    AND i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
            ");
        }
    }
}

/* End of file Mmaster.php */
