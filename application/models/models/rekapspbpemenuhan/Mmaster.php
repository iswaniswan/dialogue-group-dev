<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function total($username, $idcompany){
        return $this->db->query("       
            SELECT
                DISTINCT ROW_NUMBER() OVER(
            ORDER BY
                a.i_spb) AS i,
                a.i_spb,
                TO_CHAR(a.d_spb, 'dd-mm-YYYY') AS d_spb,
                a.i_salesman,
                CASE WHEN SUBSTRING(a.i_customer, 3, 3) <> '000' THEN '(' || a.i_customer || ') ' || b.e_customer_name
                ELSE x.e_customer_name END AS customer,
                a.v_spb - a.v_spb_discounttotal AS vspb,
                a.i_area,
                a.f_spb_cancel,
                a.i_approve1,
                a.i_approve2,
                a.i_notapprove,
                a.i_store,
                a.i_nota,
                a.f_spb_stockdaerah,
                a.f_spb_siapnotagudang,
                a.f_spb_op,
                a.f_spb_opclose,
                a.f_spb_siapnotasales,
                i_dkb
            FROM
                tm_spb a
            LEFT JOIN tr_customer b ON
                (a.i_customer = b.i_customer
                AND a.i_area = b.i_area
                AND b.i_customer_status <> '4')
            LEFT JOIN tr_customer_tmp x ON
                (a.i_customer = x.i_customer
                AND a.i_spb = x.i_spb
                AND a.i_area = x.i_area
                AND x.i_customer LIKE '%000'
                AND b.i_customer_status <> '4')
            LEFT JOIN tm_nota d ON
                (a.i_spb = d.i_spb
                AND a.i_area = d.i_area
                AND d.f_nota_cancel = 'f'),
                tr_area c
            WHERE
                NOT a.i_approve1 ISNULL
                AND NOT a.i_approve2 ISNULL
                AND NOT a.i_store ISNULL
                AND a.i_sj ISNULL
                AND a.i_area = c.i_area
                AND a.f_spb_cancel = 'f'
                AND a.f_spb_stockdaerah = 't'
                AND a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND a.f_spb_rekap = 'f'
            ORDER BY
                a.i_spb", false);
    }

    public function data($folder, $total, $username, $idcompany){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT ROW_NUMBER() OVER(
            ORDER BY
                a.i_spb) AS i,
                a.i_spb,
                TO_CHAR(a.d_spb, 'dd-mm-YYYY') AS d_spb,
                a.i_salesman,
                CASE WHEN SUBSTRING(a.i_customer, 3, 3) <> '000' THEN '(' || a.i_customer || ') ' || b.e_customer_name
                ELSE x.e_customer_name END AS customer,
                a.i_area,
                a.v_spb - a.v_spb_discounttotal AS vspb,
                a.f_spb_cancel,
                a.i_approve1,
                a.i_approve2,
                a.i_notapprove,
                a.i_store,
                a.i_nota,
                a.i_sj,
                a.f_spb_stockdaerah,
                a.f_spb_siapnotagudang,
                a.f_spb_op,
                a.f_spb_opclose,
                a.f_spb_siapnotasales,
                i_dkb,
                '$folder' AS folder,
                '$total' AS total
            FROM
                tm_spb a
            LEFT JOIN tr_customer b ON
                (a.i_customer = b.i_customer
                AND a.i_area = b.i_area
                AND b.i_customer_status <> '4')
            LEFT JOIN tr_customer_tmp x ON
                (a.i_customer = x.i_customer
                AND a.i_spb = x.i_spb
                AND a.i_area = x.i_area
                AND x.i_customer LIKE '%000'
                AND b.i_customer_status <> '4')
            LEFT JOIN tm_nota d ON
                (a.i_spb = d.i_spb
                AND a.i_area = d.i_area
                AND d.f_nota_cancel = 'f'),
                tr_area c
            WHERE
                NOT a.i_approve1 ISNULL
                AND NOT a.i_approve2 ISNULL
                AND NOT a.i_store ISNULL
                AND a.i_sj ISNULL
                AND a.i_area = c.i_area
                AND a.f_spb_cancel = 'f'
                AND a.f_spb_stockdaerah = 't'
                AND a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND a.f_spb_rekap = 'f'
            ORDER BY
                a.i_spb", false);

        $datatables->edit('f_spb_cancel', function($data){
            $f_spb_cancel          = $data['f_spb_cancel'];
            $i_approve1            = $data['i_approve1'];
            $i_notapprove          = $data['i_notapprove'];
            $i_approve2            = $data['i_approve2'];
            $i_store               = $data['i_store'];
            $i_nota                = $data['i_nota'];
            $f_spb_stockdaerah     = $data['f_spb_stockdaerah'];
            $f_spb_siapnotagudang  = $data['f_spb_siapnotagudang'];
            $f_spb_op              = $data['f_spb_op'];
            $f_spb_opclose         = $data['f_spb_opclose'];
            $f_spb_siapnotasales   = $data['f_spb_siapnotasales'];
            $i_sj                  = $data['i_sj'];
            $i_dkb                 = $data['i_dkb'];
            if($f_spb_cancel == 't'){
                return 'Batal';
            }elseif(($i_approve1 == null) && ($i_notapprove == null)){
                return 'Sales';
            }elseif(
                ($i_approve1 == null) && ($i_notapprove != null)){
                return 'Reject (sls)';
            }elseif(($i_approve1 != null) && ($i_approve2 == null) & ($i_notapprove == null)){
                return 'Keuangan';
            }elseif(($i_approve1 != null) && ($i_approve2 == null) && ($i_notapprove != null)){
                return 'Reject (ar)';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store == null)){
                return 'Gudang';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 'f')){
                return 'Pemenuhan SPB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 't') && ($f_spb_opclose == 'f')){
                return 'Proses OP';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_siapnotasales == 'f') && ($f_spb_opclose == 't')){
                return 'OP Close';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 'f')){
                return 'Siap SJ (sales)';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj == null)){
                return 'Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj == null)){
                return 'Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb == null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                return 'Siap DKB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                return 'Siap Nota';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 't') && ($i_sj == null)){
                return 'Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb == null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                return 'Siap DKB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb != null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                return 'Siap Nota';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota != null) ){
                return 'Sudah dinotakan';
            }elseif(($row->i_nota != null)){
                return 'Sudah dinotakan';
            }else{
                return 'Unknown';
            }
        });
        $datatables->add('action', function ($data) {
            $ispb   = trim($data['i_spb']);
            $iarea  = trim($data['i_area']);
            $i      = trim($data['i']);
            $folder = $data['folder'];
            $total  = $data['total'];
            $data   = '';
            $data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\"><label class=\"custom-control custom-checkbox\">
                       <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                       <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                       <input name=\"ispb".$i."\" value=\"".$ispb."\" type=\"hidden\">
                       <input name=\"iarea".$i."\" value=\"".$iarea."\" type=\"hidden\">";
            return $data;
        });
        $datatables->hide('i');
        $datatables->hide('folder');
        $datatables->hide('total');
        $datatables->hide('i_approve1');
        $datatables->hide('i_approve2');
        $datatables->hide('i_notapprove');
        $datatables->hide('i_store');
        $datatables->hide('i_nota');
        $datatables->hide('i_sj');
        $datatables->hide('i_dkb');
        $datatables->hide('f_spb_stockdaerah');
        $datatables->hide('f_spb_siapnotagudang');
        $datatables->hide('f_spb_op');
        $datatables->hide('f_spb_opclose');
        $datatables->hide('f_spb_siapnotasales');
        return $datatables->generate();
    }

    public function runningnumberspmb($thbl){
        $th  = '20'.substr($thbl,0,2);
        $asal= '20'.$thbl;
        $thbl= substr($thbl,0,2).substr($thbl,2,2);
        $this->db->select("
                n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'SPM'
                AND substr(e_periode,
                1,
                4)= '$th' FOR
            UPDATE
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nospmb  =$terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nospmb
                WHERE
                    i_modul = 'SPM'
                    AND substr(e_periode,
                    1,
                    4)= '$th'
            ", false);
            settype($nospmb,"string");
            $a=strlen($nospmb);
            while($a<6){
                $nospmb="0".$nospmb;
                $a=strlen($nospmb);
            }
            $nospmb  ="SPMB-".$thbl."-".$nospmb;
            return $nospmb;
        }else{
            $nospmb  ="000001";
            $nospmb  ="SPMB-".$thbl."-".$nospmb;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('SPM',
                '00',
                '$asal',
                1)
            ");
            return $nospmb;
        }
    }

    public function insertheader($ispmb, $dspmb, $iarea, $eremark){
        $this->db->set(
            array(
                'i_spmb'     => $ispmb,
                'd_spmb'     => $dspmb,
                'i_area'     => $iarea,
                'i_approve2' => 'SYSTEM',
                'd_approve2' => $dspmb,
                'f_spmb_acc' => 't',
                'n_print'    => 0,
                'e_remark'   => $eremark
            )
        );        
        $this->db->insert('tm_spmb');
    }

    public function updatespb($ispb,$iarea,$ispmb){
        $this->db->query("
            UPDATE
                tm_spb
            SET
                f_spb_rekap = 't',
                i_spmb = '$ispmb'
            WHERE
                i_spb = '$ispb'
                AND i_area = '$iarea'
        ");
        $query = $this->db->query("
            SELECT
                e_remark
            FROM
                tm_spmb
            WHERE
                i_spmb = '$ispmb'
        ");
        if($query->num_rows()>0){
            foreach($query->result() as $xx){
                $rem=$xx->e_remark;
            }
            if($rem!=''){
                $this->db->query("
                    UPDATE
                        tm_spmb
                    SET
                        e_remark = '$rem - $ispb'
                    WHERE
                        i_spmb = '$ispmb'
                ");
            }else{
                $this->db->query("
                    UPDATE
                        tm_spmb
                    SET
                        e_remark = '$ispb'
                    WHERE
                        i_spmb = '$ispmb'
                ");
            }
        }
    }
    
    public function insertdetail($ispb,$iarea,$ispmb){
        $que = $this->db->query("
            SELECT
                a.*,
                b.v_product_retail
            FROM
                tm_spb_item a,
                tr_product_price b
            WHERE
                i_spb = '$ispb'
                AND i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_grade = b.i_product_grade
                AND b.i_price_group = '00'
        ");
        if($que->num_rows()>0){
            foreach($que->result() as $row){
                $jmlpsn=$row->n_order-$row->n_stock;
                if($jmlpsn>0){
                    $query=$this->db->query("
                        SELECT
                            COUNT(*) AS brs
                        FROM
                            tm_spmb_item
                        WHERE
                            i_spmb = '$ispmb'
                    ");
                    if($query->num_rows()>0){
                        $br=$query->row();
                        $baris=$br->brs+1;
                    }else{
                        $baris=1;
                    }
                    $query = $this->db->query("
                        SELECT
                            a.*,
                            b.v_product_retail
                        FROM
                            tm_spmb_item a,
                            tr_product_price b
                        WHERE
                            a.i_spmb = '$ispmb'
                            AND a.i_product = '$row->i_product'
                            AND a.i_product = b.i_product
                            AND a.i_product_grade = b.i_product_grade
                            AND b.i_price_group = '00'
                            AND a.i_product_grade = '$row->i_product_grade'
                            AND a.i_product_motif = '$row->i_product_motif'
                    ");
                    if($query->num_rows()>0){
                        foreach($query->result() as $xx){
                            $rem=$xx->e_remark;
                        }
                        $this->db->query("
                            UPDATE
                                tm_spmb_item
                            SET
                                n_order = n_order + $jmlpsn,
                                n_acc = n_acc + $jmlpsn,
                                n_saldo = n_saldo + $jmlpsn,
                                e_remark = '$rem - $row->e_remark'
                            WHERE
                                i_spmb = '$ispmb'
                                AND i_product = '$row->i_product'
                                AND i_product_grade = '$row->i_product_grade'
                                AND i_product_motif = '$row->i_product_motif'
                        ");

                    }else{
                        $this->db->query("
                            INSERT
                                INTO
                                tm_spmb_item
                            VALUES('$ispmb',
                            '$row->i_product',
                            '$row->i_product_grade',
                            '$row->i_product_motif',
                            '$row->e_product_name',
                            $jmlpsn,
                            0,
                            $row->v_product_retail,
                            '$row->e_remark',
                            NULL,
                            '$row->i_area',
                            0,
                            $baris,
                            $jmlpsn,
                            $jmlpsn)
                        ");
                    }
                }
            }
        }
    }
}

/* End of file Mmaster.php */
