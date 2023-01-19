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
            $area = "00";
        }else{
            $area = "xx";
        }
        return $area;
    }

    public function getcustomer($iarea, $cari, $username, $idcompany){
        $cari = str_replace("'", "", $cari);
        if ($iarea=='00') {
            return $this->db->query("
                SELECT
                    b.i_customer,
                    e_customer_name
                FROM
                    tr_spg a,
                    tr_customer b
                WHERE
                    a.i_customer = b.i_customer
                    AND (UPPER(b.i_customer) LIKE '%$cari%'
                    OR UPPER(b.e_customer_name) LIKE '%$cari%')
                ORDER BY
                    a.i_customer
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    b.i_customer,
                    e_customer_name
                FROM
                    tr_spg a,
                    tr_customer b
                WHERE
                    a.i_customer = b.i_customer
                    AND b.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany' )
                    AND (UPPER(b.i_customer) LIKE '%$cari%'
                    OR b.e_customer_name LIKE '%$cari%')
                ORDER BY
                    a.i_customer
            ", FALSE);
        }
    }

    public function total($username, $idcompany, $icustomer, $dfrom, $dto){
        return $this->db->query("       
            SELECT
                i_orderpb,
                TO_CHAR(d_orderpb, 'dd-mm-yyyy') AS d_orderpb,
                '(' || d.i_spg || ') ' || e_spg_name AS spg,
                b.i_customer,
                '(' || b.i_customer || ') ' || e_customer_name AS customer,
                c.i_area
            FROM
                tm_orderpb a,
                tr_customer b ,
                tr_area c,
                tr_spg d
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = b.i_area
                AND a.i_area = c.i_area
                AND a.f_orderpb_cancel = 'f'
                AND a.i_spg = d.i_spg
                AND a.i_customer = d.i_customer
                AND a.f_orderpb_rekap = 'f' 
                AND a.i_customer = '$icustomer'
                AND (a.d_orderpb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_orderpb <= TO_DATE('$dto', 'dd-mm-yyyy'))
            ORDER BY
                a.i_orderpb DESC", false);
    }

    public function data($folder, $total, $username, $idcompany, $icustomer, $dfrom, $dto){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT ROW_NUMBER() OVER(
            ORDER BY
                i_orderpb DESC) AS i,
                i_orderpb,
                TO_CHAR(d_orderpb, 'dd-mm-yyyy') AS d_orderpb,
                '(' || d.i_spg || ') ' || e_spg_name AS spg,
                b.i_customer,
                '(' || b.i_customer || ') ' || e_customer_name AS customer,
                c.i_area,
                '$folder' AS folder,
                '$total' AS total
            FROM
                tm_orderpb a,
                tr_customer b ,
                tr_area c,
                tr_spg d
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = b.i_area
                AND a.i_area = c.i_area
                AND a.f_orderpb_cancel = 'f'
                AND a.i_spg = d.i_spg
                AND a.i_customer = d.i_customer
                AND a.f_orderpb_rekap = 'f' 
                AND a.i_customer = '$icustomer'
                AND (a.d_orderpb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_orderpb <= TO_DATE('$dto', 'dd-mm-yyyy'))
            ORDER BY
                a.i_orderpb DESC", false);
        $datatables->add('action', function ($data) {
            $iorderpb   = trim($data['i_orderpb']);
            $iarea      = trim($data['i_area']);
            $icustomer  = trim($data['i_customer']);
            $i          = trim($data['i']);
            $folder     = $data['folder'];
            $total      = $data['total'];
            $data   = '';
            $data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\"><label class=\"custom-control custom-checkbox\">
                       <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                       <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                       <input name=\"iorderpb".$i."\" value=\"".$iorderpb."\" type=\"hidden\">
                       <input name=\"icustomer".$i."\" value=\"".$icustomer."\" type=\"hidden\">
                       <input name=\"iarea".$i."\" value=\"".$iarea."\" type=\"hidden\">";
            return $data;
        });
        $datatables->hide('i');
        $datatables->hide('folder');
        $datatables->hide('total');
        $datatables->hide('i_area');
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
                'f_spmb_consigment' => 't',
                'n_print'    => 0,
                'e_remark'   => $eremark
            )
        );        
        $this->db->insert('tm_spmb');
    }

    public function updateorderpb($iorderpb,$icustomer,$iarea,$ispmb){
        $this->db->query("
            UPDATE
                tm_orderpb
            SET
                f_orderpb_rekap = 't',
                i_spmb = '$ispmb'
            WHERE
                i_orderpb = '$iorderpb'
                AND i_area = '$iarea'
                AND i_customer = '$icustomer'
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
                        e_remark = '$rem - $iorderpb'
                    WHERE
                        i_spmb = '$ispmb'
                ");
            }else{
                $this->db->query("
                    UPDATE
                        tm_spmb
                    SET
                        e_remark = '$iorderpb'
                    WHERE
                        i_spmb = '$ispmb'
                ");
            }
        }
        $que = $this->db->query("
            SELECT
                a.i_customer,
                b.e_customer_name
            FROM
                tm_orderpb a,
                tr_customer b
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = b.i_area
                AND a.i_orderpb = '$iorderpb'
                AND a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
        ");
        if($que->num_rows()>0){
            foreach($que->result() as $row){
                $customer=$row->e_customer_name;
            }
            $this->db->query("
                UPDATE
                    tm_spmb_item
                SET
                    e_remark = '$customer'
                WHERE
                    i_spmb = '$ispmb'
                    AND n_item_no = 1
            ");
        }
    }
    
    public function insertdetail($iorderpb,$icustomer,$iarea,$ispmb){
        $que = $this->db->query("
            SELECT
                a.*,
                b.v_product_retail
            FROM
                tm_orderpb_item a,
                tr_product_price b
            WHERE
                i_orderpb = '$iorderpb'
                AND i_area = '$iarea'
                AND a.i_customer = '$icustomer'
                AND a.i_product = b.i_product
                AND a.i_product_grade = b.i_product_grade
                AND b.i_price_group = '00'
        ", FALSE);
        if($que->num_rows()>0){
            foreach($que->result() as $row){
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
                            n_order = n_order + $row->n_quantity_order,
                            n_acc = n_acc + $row->n_quantity_order,
                            n_saldo = n_saldo + $row->n_quantity_order,
                            e_remark = '$rem - $row->e_remark'
                        WHERE
                            i_spmb = '$ispmb'
                            AND i_product = '$row->i_product'
                            AND i_product_grade = '$row->i_product_grade'
                            AND i_product_motif = '$row->i_product_motif'
                    ");
                }else{
                    $data = array(
                        'i_spmb'            => $ispmb,
                        'i_product'         => $row->i_product,
                        'i_product_grade'   => $row->i_product_grade,
                        'i_product_motif'   => $row->i_product_motif,
                        'e_product_name'    => $row->e_product_name,
                        'n_order'           => $row->n_quantity_order,
                        'n_stock'           => 0,
                        'v_unit_price'      => $row->v_product_retail,
                        'e_remark'          => $row->e_remark,
                        'i_op'              => null,
                        'i_area'            => $row->i_area,
                        'n_deliver'         => 0,
                        'n_item_no'         => $baris,
                        'n_acc'             => $row->n_quantity_order,
                        'n_saldo'           => $row->n_quantity_order

                    );
                    $this->db->insert('tm_spmb_item', $data);
                }
            }
        }
    }
}

/* End of file Mmaster.php */
