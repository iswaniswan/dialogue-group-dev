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

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function getreferensi($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_bbm,
                to_char(a.d_bbm, 'dd-mm-yyyy') AS d_bbm,
                b.i_ttb
            FROM
                tm_bbm a
            INNER JOIN tr_salesman d ON
                (a.i_salesman = d.i_salesman)
            INNER JOIN tm_ttbretur b ON
                (a.i_bbm = b.i_bbm
                AND a.i_area = b.i_area)
            INNER JOIN tr_area c ON
                (a.i_area = c.i_area)
            LEFT JOIN tr_customer e ON
                (b.i_customer = e.i_customer)
            LEFT JOIN tr_customer_groupar f ON
                (e.i_customer = f.i_customer)
            WHERE
                a.i_bbm_type = '05'
                AND a.i_area = '$iarea'
                AND a.i_bbm LIKE 'BBM%'
                AND NOT a.i_bbm IN(
                SELECT
                    i_refference
                FROM
                    tm_kn
                WHERE
                    i_area = '$iarea'
                    AND i_refference LIKE 'BBM%')
                AND (a.i_bbm LIKE '%$cari%'
                OR b.i_ttb LIKE '%$cari%')
            ORDER BY
                a.i_bbm
        ", FALSE);
    }

    public function getpajak($cari,$icustomer,$iproduct){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.i_seri_pajak
            FROM
                tm_nota a,
                tr_customer b,
                tm_nota_item c,
                tr_customer_groupbayar d
            WHERE
                a.i_customer = d.i_customer
                AND b.i_customer = d.i_customer
                AND a.i_nota = c.i_nota
                AND a.i_area = c.i_area
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_koreksi = 'f'
                AND a.f_nota_cancel = 'f'
                AND NOT a.i_nota ISNULL
                AND d.i_customer_groupbayar IN (
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    i_customer = '$icustomer' )
                AND UPPER(c.i_product) LIKE '%$iproduct%'
                AND (UPPER(b.i_customer) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%'
                OR UPPER(c.i_product) LIKE '%$cari%'
                OR UPPER(a.i_nota) LIKE '%$cari%')
            ORDER BY
                a.i_nota
        ", FALSE);
    }

    public function getdetailpajak($inota, $icustomer, $iproduct){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.d_nota,
                a.i_seri_pajak,
                to_char(a.d_pajak, 'dd-mm-yyyy') AS d_pajak,
                a.v_nota_netto
            FROM
                tm_nota a,
                tr_customer b,
                tm_nota_item c,
                tr_customer_groupbayar d
            WHERE
                a.i_customer = d.i_customer
                AND b.i_customer = d.i_customer
                AND a.i_nota = c.i_nota
                AND a.i_area = c.i_area
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_koreksi = 'f'
                AND a.f_nota_cancel = 'f'
                AND NOT a.i_nota ISNULL
                AND d.i_customer_groupbayar IN (
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    i_customer = '$icustomer' )
                AND UPPER(c.i_product) LIKE '%$iproduct%'
                AND a.i_nota = '$inota'
            ORDER BY
                a.i_nota
        ", FALSE);
    }

    public function getdetailref($ibbm, $iarea){
        return $this->db->query("
            SELECT
                a.i_bbm,
                a.i_area,
                a.i_salesman,
                d.e_salesman_name,
                c.e_area_name,
                b.i_customer,
                e.e_customer_name,
                e.e_customer_address,
                b.n_ttb_discount1,
                b.n_ttb_discount2,
                b.n_ttb_discount3,
                b.v_ttb_discount1,
                b.v_ttb_discount2,
                b.i_ttb,
                b.d_ttb,
                b.v_ttb_discount3,
                b.v_ttb_gross,
                b.v_ttb_discounttotal,
                b.v_ttb_netto,
                b.v_ttb_sisa,
                to_char(a.d_bbm, 'dd-mm-yyyy') AS d_bbm,
                a.i_refference_document,
                a.d_refference_document,
                f.i_customer_groupar
            FROM
                tm_bbm a
            INNER JOIN tr_salesman d ON
                (a.i_salesman = d.i_salesman)
            INNER JOIN tm_ttbretur b ON
                (a.i_bbm = b.i_bbm
                AND a.i_area = b.i_area)
            INNER JOIN tr_area c ON
                (a.i_area = c.i_area)
            LEFT JOIN tr_customer e ON
                (b.i_customer = e.i_customer)
            LEFT JOIN tr_customer_groupar f ON
                (e.i_customer = f.i_customer)
            WHERE
                a.i_bbm_type = '05'
                AND a.i_area = '$iarea'
                AND a.i_bbm LIKE 'BBM%'
                AND NOT a.i_bbm IN(
                SELECT
                    i_refference
                FROM
                    tm_kn
                WHERE
                    i_area = '$iarea'
                    AND i_refference LIKE 'BBM%')
                AND a.i_bbm = '$ibbm'
            ORDER BY
                a.i_bbm
        ", FALSE);
    }

    public function getdetailbbm($ibbm){
        return $this->db->query("
            SELECT
                a.i_bbm,
                a.i_refference_document,
                a.i_product,
                a.i_product_motif,
                a.i_product_grade,
                a.n_quantity,
                a.v_unit_price,
                a.e_remark,
                a.e_product_name,
                a.d_refference_document,
                a.e_mutasi_periode,
                b.e_product_motifname
            FROM
                tm_bbm_item a,
                tr_product_motif b
            WHERE
                a.i_bbm_type = '05'
                AND a.i_bbm = '$ibbm'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", FALSE);
    }

    public function jmldetail($ibbm){
        $jml=0;
        $this->db->select(" 
                COUNT(a.i_bbm) AS jml
            FROM
                tm_bbm_item a,
                tr_product_motif b
            WHERE
                a.i_bbm_type = '05'
                AND a.i_bbm = '$ibbm'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $xx){
                $jml=$xx->jml;
            }
        }
        return $jml;
    }

    public function runningnumberkn($th,$iarea){
        $pot=substr($th,2,2);
        $this->db->select(" max(substring(i_kn,4,3)) as no 
            from tm_kn 
            where n_kn_year=$th and i_area='$iarea' 
            and substring(i_kn,1,3)<>'KP0' 
            and substring(i_kn,1,3)<>'KP1' 
            and substring(i_kn,1,3)<>'KP2' 
            and substring(i_kn,1,3)<>'KP3' 
            and substring(i_kn,1,3)<>'KPP' 
            and substring(i_kn,1,1)<>'D'",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $nono=$row->no+1;             
            }
            settype($nono,"string");
            $a=strlen($nono);
            while($a<3){
                $nono="0".$nono;
                $a=strlen($nono);
            }
            $kn="K".$iarea.$nono.$pot;
            return $kn;
        }else{
            $kn="K".$iarea."001".$pot;
            return $kn;
        }
    }

    public function insert($iarea,$ikn,$icustomer,$irefference,$icustomergroupar,$isalesman,$ikntype,$dkn,$nknyear,$fcetak,$fmasalah,$finsentif,$vnetto,$vsisa,$vgross,$vdiscount,$eremark,$drefference,$ipajak,$dpajak){
        $dentry = current_datetime();
        if($dpajak!=''){
            $this->db->set(
                array(
                    'i_area'             => $iarea,
                    'i_kn'               => $ikn,
                    'i_customer'         => $icustomer,
                    'i_refference'       => $irefference,
                    'i_customer_groupar' => $icustomergroupar,
                    'i_salesman'         => $isalesman,
                    'i_kn_type'          => $ikntype,
                    'd_kn'               => $dkn,
                    'd_refference'       => $drefference,
                    'i_pajak'            => $ipajak,
                    'd_pajak'            => $dpajak,
                    'd_entry'            => $dentry,
                    'e_remark'           => $eremark,
                    'f_cetak'            => $fcetak,
                    'f_masalah'          => $fmasalah,
                    'f_insentif'         => $finsentif,
                    'n_kn_year'          => $nknyear,
                    'v_netto'            => $vnetto,
                    'v_gross'            => $vgross,
                    'v_discount'         => $vdiscount,
                    'v_sisa'             => $vsisa
                )
            );
        }else{
            $this->db->set(
                array(
                    'i_area'             => $iarea,
                    'i_kn'               => $ikn,
                    'i_customer'         => $icustomer,
                    'i_refference'       => $irefference,
                    'i_customer_groupar' => $icustomergroupar,
                    'i_salesman'         => $isalesman,
                    'i_kn_type'          => $ikntype,
                    'd_kn'               => $dkn,
                    'd_refference'       => $drefference,
                    'd_entry'            => $dentry,
                    'e_remark'           => $eremark,
                    'f_cetak'            => $fcetak,
                    'f_masalah'          => $fmasalah,
                    'f_insentif'         => $finsentif,
                    'n_kn_year'          => $nknyear,
                    'v_netto'            => $vnetto,
                    'v_gross'            => $vgross,
                    'v_discount'         => $vdiscount,
                    'v_sisa'             => $vsisa

                )
            );
        }
        $this->db->insert('tm_kn');
    }
}

/* End of file Mmaster.php */