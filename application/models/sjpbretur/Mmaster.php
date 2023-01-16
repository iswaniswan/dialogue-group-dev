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

    public function getproduct($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.e_product_name AS nama
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
            ORDER BY
                a.e_product_name",
        FALSE);
    }

    public function runningnumbersj($iarea,$thbl){
        $th   = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" 
                n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'SBR'
                AND substr(e_periode,
                1,
                4)= '$th'
                AND i_area = '$iarea' FOR
            UPDATE
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nosj = $terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nosj
                WHERE
                    i_modul = 'SBR'
                    AND substr(e_periode,
                    1,
                    4)= '$th'
                    AND i_area = '$iarea'
            ", false);
            settype($nosj,"string");
            $a=strlen($nosj);
            while($a<4){
                $nosj="0".$nosj;
                $a=strlen($nosj);
            }
            $nosj  ="SBR-".$thbl."-".$iarea.$nosj;
            return $nosj;
        }else{
            $nosj  ="0001";
            $nosj  ="SBR-".$thbl."-".$iarea.$nosj;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('SBR',
                '$iarea',
                '$asal',
                1)
            ");
            return $nosj;
        }
    } 

    public function insertsjheader($isj,$dsj,$iarea,$vspbnetto,$icustomer,$ispg){
        $dsjentry = current_datetime();
        $this->db->set(
            array(
                'i_sjpbr'       => $isj,
                'i_area'        => $iarea,
                'i_customer'    => $icustomer,
                'i_spg'         => $ispg,
                'd_sjpbr'       => $dsj,
                'v_sjpbr'       => $vspbnetto,
                'd_sjpbr_entry' => $dsjentry,
                'f_sjpbr_cancel'=> 'f'
            )
        );        
        $this->db->insert('tm_sjpbr');
    }

    public function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,$vunitprice,$isj,$dsj,$iarea,$eremark,$i){
        $th=substr($dsj,0,4);
        $bl=substr($dsj,5,2);
        $pr=$th.$bl;
        $this->db->set(
            array(
                'i_sjpbr'            => $isj,
                'i_area'             => $iarea,
                'd_sjpbr'            => $dsj,
                'i_product'          => $iproduct,
                'i_product_motif'    => $iproductmotif,
                'i_product_grade'    => $iproductgrade,
                'e_product_name'     => $eproductname,
                'n_quantity_retur'   => $nretur,
                'n_quantity_receive' => $nreceive,
                'v_unit_price'       => $vunitprice,
                'e_remark'           => $eremark,
                'e_mutasi_periode'   => $pr,
                'n_item_no'          => $i
            )        
        );        
        $this->db->insert('tm_sjpbr_item');
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
        $hasil='kosong';
        $query=$this->db->query("
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

    public function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode){
        $query=$this->db->query(" 
            UPDATE
                tm_mutasi_consigment
            SET
                n_mutasi_kepusat = n_mutasi_kepusat + $qsj,
                n_mutasi_git = n_mutasi_git + $qsj,
                n_saldo_akhir = n_saldo_akhir-$qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
    }

    public function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qsj,$emutasiperiode,$q_aw,$q_ak){
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
            $q_aw,
            0,
            0,
            0,
            $qsj,
            $q_ak-$qsj,
            0,
            'f',
            $qsj)
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
        $query=$this->db->query("
            UPDATE
                tm_ic_consigment
            SET
                n_quantity_stock = $q_ak-$qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
        ",false);
    }

    public function insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$qsj){
        $query=$this->db->query("
            INSERT
                INTO
                tm_ic_consigment
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$icustomer',
            '$eproductname',
            0-$qsj,
            't' )
        ",false);
    }
}

/* End of file Mmaster.php */