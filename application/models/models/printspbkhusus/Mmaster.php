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

    public function cekarea(){
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
            return '00';
        }else{
            return 'XX';
        }
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu,$xarea){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if ($xarea=='00') {
            $sql = "";
        }else{
            $sql = "
                    AND a.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
            ";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_spb,
                to_char(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                a.i_customer,
                e_customer_name,
                a.i_area,
                f_spb_cancel,
                a.i_approve1,
                a.n_print,
                a.i_sj,
                d.i_nota,
                to_char(d_sj_receive, 'dd-mm-yyyy') AS d_sj_receive,
                i_notapprove,
                a.i_approve2,
                a.i_store,
                f_spb_siapnotagudang,
                f_spb_op,
                f_spb_opclose,
                f_spb_siapnotasales,
                i_dkb,
                f_spb_stockdaerah,
                '$folder' AS folder
            FROM
                tm_spb a
            LEFT JOIN tm_nota d ON
                (a.i_spb = d.i_spb
                AND a.i_area = d.i_area),
                tr_customer b,
                tr_customer_owner c
            WHERE
                a.i_customer = b.i_customer
                AND a.i_customer = c.i_customer
                AND (a.n_print = 0
                OR a.n_print ISNULL)
                AND a.d_spb >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_spb <= to_date('$dto', 'dd-mm-yyyy')
                $sql
                AND a.i_area = '$iarea'
            ORDER BY
                a.i_spb DESC
        ", FALSE);
        $datatables->add('action', function ($data) {
            $id             = trim($data['i_spb']);
            $i_area         = $data['i_area'];
            $i_customer     = $data['i_customer'];
            $folder         = $data['folder'];
            $status         = $data['f_spb_cancel'];
            $data           = '';
            $query = $this->db->query("
                SELECT
                    *
                FROM
                    tr_customer_groupar
                WHERE
                    i_customer = '$i_customer'
            ", FALSE);
            if ($query->num_rows()>0) {
                $que = $query->row();
                $plafond = $que->v_saldo;
            }else{
                $plafond = 0;
            }
            if ($status == 'f' && $plafond > 0) {
                $data          .= "<a href=\"#\" onclick='printspb(\"$id\",\"$i_area\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
            }
            return $data;
        });

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print']=='0') {
                $data = '<span class="label label-info label-rouded">BELUM</span>';
            }else{
                $data = '<span class="label label-success label-rouded">SUDAH</span>';
            }
            return $data;
        });

        $datatables->edit('i_approve1', function ($data) {
            if ($data['i_approve1']!=null) {
                $data = '<span class="label label-success label-rouded">YA</span>';
            }else{
                $data = '<span class="label label-warning label-rouded">TIDAK</span>';
            }
            return $data;
        });

        $datatables->edit('f_spb_cancel', function ($data) {
            $f_spb_cancel           = $data['f_spb_cancel'];
            $i_approve1             = $data['i_approve1'];
            $i_approve2             = $data['i_approve2'];
            $i_notapprove           = $data['i_notapprove'];
            $i_store                = $data['i_store'];
            $f_spb_stockdaerah      = $data['f_spb_stockdaerah'];
            $f_spb_siapnotagudang   = $data['f_spb_siapnotagudang'];
            $f_spb_op               = $data['f_spb_op'];
            $f_spb_opclose          = $data['f_spb_opclose'];
            $f_spb_siapnotasales    = $data['f_spb_siapnotasales'];
            $i_nota                 = $data['i_nota'];
            $i_sj                   = $data['i_sj'];
            $i_dkb                  = $data['i_dkb'];
            $d_sj_receive           = $data['d_sj_receive'];
            if(($f_spb_cancel == 't')){
                $status='Batal';
            }elseif(($i_approve1 == null) && ($i_notapprove == null)){
                $status='Sales';
            }elseif(($i_approve1 == null) && ($i_notapprove != null)){
                $status='Reject (sls)';
            }elseif(($i_approve1 != null) && ($i_approve2 == null) & ($i_notapprove == null)){
                $status='Keuangan';
            }elseif(($i_approve1 != null) && ($i_approve2 == null) && ($i_notapprove != null)){
                $status='Reject (ar)';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store == null)){
                $status='Gudang';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 'f')){
                $status='Pemenuhan SPB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 't') && ($f_spb_opclose == 'f')){
                $status='Proses OP';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') &&
                ($f_spb_siapnotagudang == 'f') && ($f_spb_siapnotasales == 'f') && ($f_spb_opclose == 't')){
                $status='OP Close';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 'f')){
                $status='Siap SJ (sales)';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') &&
                ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj == null)){
                $status='Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && 
                ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj == null)){
                $status='Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb == null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                $status='Siap DKB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                $status='Siap Nota';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 't') && ($i_sj == null)){
                $status='Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb == null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                $status='Siap DKB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb != null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                $status='Siap Nota';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota != null) && ($d_sj_receive != null)){
                $status='Sudah diterima';             
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota != null) && ($d_sj_receive != null)){
                $status='Sudah dinotakan';            
            }elseif(($i_nota != null)){
                $status='Sudah dinotakan';
            }else{
                $status='Unknown';      
            }
            $data = '<span class="label label-inverse label-rouded">'.strtoupper($status).'</span>';
            return $data;
            /*return $status;*/
        });
        $datatables->hide('folder');
        $datatables->hide('i_notapprove');
        $datatables->hide('i_customer');
        $datatables->hide('i_approve2');
        $datatables->hide('i_store');
        $datatables->hide('i_sj');
        $datatables->hide('i_nota');
        $datatables->hide('d_sj_receive');
        $datatables->hide('f_spb_siapnotagudang');
        $datatables->hide('f_spb_op');
        $datatables->hide('f_spb_opclose');
        $datatables->hide('f_spb_siapnotasales');
        $datatables->hide('i_dkb');
        $datatables->hide('f_spb_stockdaerah');
        return $datatables->generate();
    }

    public function baca($ispb,$area){   
        $this->db->select("
                a.*,
                b.e_customer_name,
                b.e_customer_address,
                b.e_customer_city,
                b.f_customer_pkp,
                b.d_signin,
                c.*,
                d.*,
                e.*,
                f.* ,
                g.e_customer_ownername,
                h.e_customer_pkpname
            FROM
                tm_spb a,
                tr_customer b,
                tr_salesman c,
                tr_customer_class d,
                tr_price_group e,
                tr_customer_groupar f ,
                tr_customer_owner g,
                tr_customer_pkp h
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$area'
                AND a.i_customer = b.i_customer
                AND a.i_customer = f.i_customer
                AND a.i_salesman = c.i_salesman
                AND a.i_customer = g.i_customer
                AND a.i_customer = h.i_customer
                AND (e.n_line = b.i_price_group
                OR e.i_price_group = b.i_price_group)
                AND b.i_customer_class = d.i_customer_class
            ORDER BY
                a.i_spb DESC
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacadetail($ispb,$area){
        $this->db->select("
                a.i_spb,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                a.n_order,
                a.n_deliver,
                a.n_stock,
                a.v_unit_price,
                substr(a.e_product_name, 1, 46) AS e_product_name,
                a.i_op,
                a.i_area,
                a.e_remark,
                a.n_item_no,
                tr_product.i_product_status
            FROM
                tm_spb_item a
            INNER JOIN tr_product ON
                (a.i_product = tr_product.i_product)
            INNER JOIN tr_product_motif ON
                (a.i_product_motif = tr_product_motif.i_product_motif
                AND a.i_product = tr_product_motif.i_product)
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$area'
            ORDER BY
                a.n_item_no
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacapiutang($ispb,$area){
        $this->db->select("
                i_customer
            FROM
                tm_spb
            WHERE
                i_spb = '$ispb'
                AND i_area = '$area'
                ",false);
        $quer = $this->db->get();
        $cust='';      
        $saldo=0;
        if ($quer->num_rows() > 0){
            foreach($quer->result() as $rowi){
                $cust=$rowi->i_customer;
            }
            $this->db->select("
                    sum(v_sisa) AS sisa
                FROM
                    tm_nota
                WHERE
                    i_customer = '$cust'
                    AND f_nota_cancel = 'f'
                    AND NOT i_nota ISNULL
            ",false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $saldo=$row->sisa;
                }
            }
        }
        return $saldo;
    }

    public function bacanotapiutang($ispb,$area){
        $this->db->select("
                i_customer
            FROM
                tm_spb
            WHERE
                i_spb = '$ispb'
                AND i_area = '$area'
        ",false);
        $quer = $this->db->get();
        $cust='';      
        $nota=0;
        if ($quer->num_rows() > 0){
            foreach($quer->result() as $rowi){
                $cust=$rowi->i_customer;
            }
            $this->db->select("
                    i_nota
                FROM
                    tm_nota
                WHERE
                    i_customer = '$cust'
                    AND f_nota_cancel = 'f'
                    AND NOT i_nota ISNULL
            ",false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $nota=$row->i_nota;
                }
            }
        }
        return $nota;
    }

    public function baca_nilai($i_customer){
        $thbln = date('ym');
        return $this->db->query("
            SELECT
                (x.v_spb - x.diskon) AS nilai_spb
            FROM
                (
                SELECT
                    sum(v_spb) AS v_spb, sum(v_spb_discounttotal) AS diskon
                FROM
                    tm_spb
                WHERE
                    i_customer = '$i_customer'
                    AND i_spb LIKE '%SPB-$thbln-%'
                    AND f_spb_cancel = 'f' ) AS x
        ", FALSE);
    }

    public function close($id,$iarea){
        return $this->db->query("
            UPDATE
                tm_spb
            SET
                n_print = n_print + 1
            WHERE
                i_spb = '$id'
                AND i_area = '$iarea'
        ",false);
    }
    public function company($id_company){
        return $this->db->query("
            SELECT
                *
            FROM
                public.company
            WHERE
                id = '$id_company'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
