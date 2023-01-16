<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $row   = $querty->row();
            $iarea = $row->i_area;
            return $iarea;
        }
    }

    public function customer($idcompany, $ispg){
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
                UPPER(a.i_spg) = '$ispg'
                AND a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = 'admin'
                    AND id_company = '$idcompany')
                AND a.i_area = b.i_area
                AND a.i_customer = c.i_customer
        ", FALSE);
    }

    public function cekperiode(){
        $date = date('Ym');
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = $date;
        }
        return $iperiode;
    }

    public function bacadetail($icustomer){
        $this->db->select("
                    a.*,
                    b.e_product_name,
                    c.e_product_motifname
                FROM
                    tm_ic_consigment a,
                    tr_product b,
                    tr_product_motif c
                WHERE
                    a.i_product = b.i_product
                    AND a.i_product = c.i_product
                    AND a.i_product_motif = c.i_product_motif
                    AND a.i_customer = '$icustomer'
                ORDER BY
                    a.i_product
        ", FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query;
        }else{
            return 0;
        }
    }

    public function runningnumber($icustomer,$iarea,$thbl){
        $th = substr($thbl,0,2);
        $this->db->select("
                MAX(substr(i_sopb, 9, 2)) AS MAX
            FROM
                tm_sopb
            WHERE
                substr(i_sopb,
                4,
                2)= '$th'
                AND i_area = '$iarea'
                AND i_customer = '$icustomer'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $noso  =$terakhir+1;
            settype($noso,"string");
            $a=strlen($noso);
            while($a<2){
                $noso="0".$noso;
                $a=strlen($noso);
            }
            $noso  ="SO-".$thbl."-".$noso;
            return $noso;
        }else{
            $noso  ="01";
            $noso  ="SO-".$thbl."-".$noso;
            return $noso;
        }
    }

    public function insertheader($istockopname,$dstockopname,$icustomer,$iarea,$ispg){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_sopb'    => $istockopname,
                'd_sopb'    => $dstockopname,
                'i_customer'=> $icustomer,
                'i_spg'     => $ispg,
                'i_area'    => $iarea,
                'd_entry'   => $dentry
            )
        );
        $this->db->insert('tm_sopb');
        /*update ke mutasi header*/
        $emutasiperiode='20'.substr($istockopname,3,4);
        $bldpn=substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
            $perdpn=substr($emutasiperiode,0,4)+1;
            $perdpn=$perdpn.'01';
        }else{
            $perdpn=substr($emutasiperiode,0,4);
            $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi_headerconsigment
            WHERE
                i_customer = '$icustomer'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if($query->num_rows()>0){
            $this->db->query("   
                UPDATE
                    tm_mutasi_headerconsigment
                SET
                    i_stockopname_akhir = '$istockopname'
                WHERE
                    i_customer = '$icustomer'
                    AND e_mutasi_periode = '$emutasiperiode'
            ",false);
        }else{
            $this->db->query("   
                INSERT
                    INTO
                    tm_mutasi_headerconsigment
                VALUES ('$icustomer',
                '$emutasiperiode',
                NULL,
                '$istockopname')
            ",false);
        }
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi_headerconsigment
            WHERE
                i_customer = '$icustomer'
                AND e_mutasi_periode = '$perdpn'
        ",false);
        if($query->num_rows()>0){
            $this->db->query("   
                UPDATE
                    tm_mutasi_headerconsigment
                SET
                    i_stockopname_awal = '$istockopname'
                WHERE
                    i_customer = '$icustomer'
                    AND e_mutasi_periode = '$perdpn'    
            ",false);
        }else{
            $this->db->query("   
                INSERT
                    INTO
                    tm_mutasi_headerconsigment
                VALUES ('$icustomer',
                '$perdpn',
                '$istockopname',
                NULL)    
            ",false);
        }
        /*end update ke mutasi header*/
    }

    public function insertdetail($iproduct,$iproductgrade,$eproductname,$nstockopname,$istockopname,$icustomer,$iproductmotif,$dstockopname,$iarea,$i){
        $pr='20'.substr($istockopname,3,4);
        $this->db->set(
            array(
                'i_sopb'           => $istockopname,
                'd_sopb'           => $dstockopname,
                'i_customer'       => $icustomer,
                'i_product'        => $iproduct,
                'i_product_grade'  => $iproductgrade,
                'e_product_name'   => $eproductname,
                'i_product_motif'  => $iproductmotif,
                'n_sopb'           => $nstockopname,
                'i_area'           => $iarea,
                'e_mutasi_periode' => $pr,
                'n_item_no'        => $i
            )
        );        
        $this->db->insert('tm_sopb_item');
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
        $query = $this->db->query("
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

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode){
        $ada   = false;
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
            $ada=true;
        }
        return $ada;
    }

    public function updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qdo,$emutasiperiode){
        $query = $this->db->query(" 
            UPDATE
                tm_mutasi_consigment
            SET
                n_saldo_stockopname = $qdo
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        $bldpn=substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
            $perdpn=substr($emutasiperiode,0,4)+1;
            $perdpn=$perdpn.'01';
        }else{
            $perdpn=substr($emutasiperiode,0,4);
            $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi_consigment
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$perdpn'
        ",false);
        if($query->num_rows() > 0){
            $query = $this->db->query(" 
                UPDATE
                    tm_mutasi_consigment
                SET
                    n_saldo_awal = $qdo,
                    n_saldo_akhir =($qdo + n_mutasi_daripusat + n_mutasi_darilang)- (n_mutasi_penjualan + n_mutasi_kepusat)
                WHERE
                    i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
                    AND i_customer = '$icustomer'
                    AND e_mutasi_periode = '$perdpn'
            ",false);
        }elseif($query->num_rows()== 0){
            $query = $this->db->query(" 
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
                    n_mutasi_git,
                    f_mutasi_close)
                VALUES ( '$iproduct',
                '$iproductmotif',
                '$iproductgrade',
                '$icustomer',
                '$perdpn',
                $qdo,
                0,
                0,
                0,
                0,
                $qdo,
                0,
                0,
                'f')       
            ",false);
        }    
    }

    public function insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qdo,$emutasiperiode){
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
                n_mutasi_git,
                f_mutasi_close)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$icustomer',
            '$emutasiperiode',
            0,
            0,
            0,
            0,
            0,
            0,
            $qdo,
            0,
            'f')
        ",false);
        $bldpn=substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
            $perdpn=substr($emutasiperiode,0,4)+1;
            $perdpn=$perdpn.'01';
        }else{
            $perdpn=substr($emutasiperiode,0,4);
            $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi_consigment
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$perdpn'
        ",false);
        if($query->num_rows() > 0){
            $query = $this->db->query("
                UPDATE
                    tm_mutasi_consigment
                SET
                    n_saldo_awal = $qdo,
                    n_saldo_akhir =($qdo + n_mutasi_daripusat + n_mutasi_darilang)- (n_mutasi_penjualan + n_mutasi_kepusat)
                WHERE
                    i_product = '$iproduct'
                    AND i_product_grade = '$iproductgrade'
                    AND i_product_motif = '$iproductmotif'
                    AND i_customer = '$icustomer'
                    AND e_mutasi_periode = '$perdpn'
            ",false);
        }elseif($query->num_rows()== 0){
            $query = $this->db->query("
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
                    n_mutasi_git,
                    f_mutasi_close)
                VALUES ( '$iproduct',
                '$iproductmotif',
                '$iproductgrade',
                '$icustomer',
                '$perdpn',
                $qdo,
                0,
                0,
                0,
                0,
                $qdo,
                0,
                0,
                'f')
            ",false);
        }
    }
}

/* End of file Mmaster.php */