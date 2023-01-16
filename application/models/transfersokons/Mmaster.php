<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacacust($ispg){
        $this->db->select('i_customer');
        $this->db->from('tr_spg');
        $this->db->where('UPPER(i_spg)', strtoupper($ispg));
        return $this->db->get()->result();
    }

    public function bacaperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = '';
        }
        return $iperiode;
    }

    public function getcustomer($ispg){
        $this->db->select('i_customer');
        $this->db->from('tr_spg');
        $this->db->where('UPPER(i_spg)', strtoupper($ispg));
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row  = $query->row();
            $icus = $row->i_customer;
        }
        return $icus;
    }

    public function getspg($icustomer){
        $this->db->select('a.i_spg');
        $this->db->from('tr_spg a');
        $this->db->join('tm_user b', 'b.i_user = a.i_user','a.i_area = b.i_area1');
        $this->db->where('UPPER(i_customer)', strtoupper($icustomer));
        return $this->db->get();
        /*$query = $this->db->get();
        if ($query->num_rows()>0) {
            $row  = $query->row();
            $ispg = $row->i_spg;
        }
        return $ispg;*/
    }

    public function runningnumber($icustomer,$thbl){
        $th = substr($thbl,0,2);
        $this->db->select(" 
                max(substr(i_sopb,9,2)) AS max 
            FROM tm_sopb
            WHERE substr(i_sopb,4,2)='$th' 
            AND i_customer='$icustomer'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir = $row->max;
            }
            $noso = $terakhir+1;
            settype($noso,"string");
            $a = strlen($noso);
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

    public function insertheader($istockopname, $dstockopname, $icustomer, $iarea, $ispg){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_sopb'     => $istockopname,
                'd_sopb'     => $dstockopname,
                'i_customer' => $icustomer,
                'i_area'     => $iarea,
                'i_spg'      => $ispg,
                'd_entry'    => $dentry
            )
        );
        $this->db->insert('tm_sopb');
    }

    public function cek_data($istockopname, $icustomer, $iproduct, $iproductgrade){
        $this->db->select('*');
        $this->db->from('tm_sopb_item');
        $this->db->where('i_sopb',$istockopname);
        $this->db->where('i_customer',$icustomer);
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        return $this->db->get();
    }

    public function eproductname($iproduct){
        $this->db->select('e_product_name');
        $this->db->from('tr_product');
        $this->db->where('i_product',$iproduct);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $eproductname = $row->e_product_name;
        }else{
            $eproductname = null;
        }
        return $eproductname;
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

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode){
        $ada = false;
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
    }

    public function insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qdo,$emutasiperiode){
        $ada = false;
        $tmp=substr($emutasiperiode,4,2);
        switch($tmp){
            case '01':
            $th=substr($emutasiperiode,0,4)-1;
            $per=$th.'12';
            break;
            case '02':
            $per=substr($emutasiperiode,0,4).'01';
            break;
            case '03':
            $per=substr($emutasiperiode,0,4).'02';
            break;
            case '04':
            $per=substr($emutasiperiode,0,4).'03';
            break;
            case '05':
            $per=substr($emutasiperiode,0,4).'04';
            break;
            case '06':
            $per=substr($emutasiperiode,0,4).'05';
            break;
            case '07':
            $per=substr($emutasiperiode,0,4).'06';
            break;
            case '08':
            $per=substr($emutasiperiode,0,4).'07';
            break;
            case '09':
            $per=substr($emutasiperiode,0,4).'08';
            break;
            case '10':
            $per=substr($emutasiperiode,0,4).'09';
            break;
            case '11':
            $per=substr($emutasiperiode,0,4).'10';
            break;
            case '12':
            $per=substr($emutasiperiode,0,4).'11';
            break;
        }
        $sal = 0;
        $query = $this->db->query("
            SELECT
                n_saldo_stockopname
            FROM
                tm_mutasi_consigment
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_customer = '$icustomer'
                AND e_mutasi_periode = '$per'
        ",false);
        if ($query->num_rows() > 0){
            $isi=$query->row();
            $sal=$isi->n_saldo_stockopname;
            $ada=true;
        }
        if($sal==null){
            $sal=0;
        }
        if($ada){
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
                    f_mutasi_close)
                VALUES ('$iproduct',
                '$iproductmotif',
                '$iproductgrade',
                '$icustomer',
                '$emutasiperiode',
                $sal,
                0,
                0,
                0,
                0,
                0,
                $qdo,
                'f')
            ",false);
        }else{
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
                    f_mutasi_close)
                VALUES ('$iproduct',
                '$iproductmotif',
                '$iproductgrade',
                '$icustomer',
                '$emutasiperiode',
                $sal,
                0,
                0,
                0,
                0,
                0,
                $qdo,
                'f')
            ",false);
        }
    }
}

/* End of file Mmaster.php */