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

    public function bacastore($username, $idcompany){
        return $this->db->query("
            SELECT
                DISTINCT(b.i_store) AS i_store ,
                a.i_store_location,
                a.e_store_locationname,
                b.e_store_name
            FROM
                tr_store_location a,
                tr_store b,
                tr_area c
            WHERE
                a.i_store = b.i_store
                AND c.i_store = b.i_store 
                AND c.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
            ORDER BY
                b.i_store
        ",FALSE);
    }

    public function cekperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query      = $this->db->get();
        $row        = $query->row();
        $iperiode   = $row->i_periode;
        return $iperiode;
    }

    public function getdetail($istore,$istorelocation,$iperiode){
        return $this->db->query("
            SELECT
                a.*,
                b.e_product_name,
                c.e_product_motifname
            FROM
                tm_mutasi a,
                tr_product b,
                tr_product_motif c
            WHERE
                a.i_product = b.i_product
                AND a.i_product = c.i_product
                AND a.i_product_motif = c.i_product_motif
                AND a.e_mutasi_periode = '$iperiode'
                AND a.i_store = '$istore'
                AND a.i_store_location = '$istorelocation'
            ORDER BY
                a.i_product
        ", FALSE);
    }

    public function runningnumber($iarea,$thbl){
        $th = substr($thbl,0,2);
        $this->db->select(" 
                max(substr(i_stockopname,9,2)) AS max 
            FROM tm_stockopname
            WHERE substr(i_stockopname,4,2)='$th' 
            AND i_area='$iarea'
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

    public function insertheader($istockopname, $dstockopname, $istore, $istorelocation, $iarea){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_stockopname'     => $istockopname,
                'd_stockopname'     => $dstockopname,
                'i_store'           => $istore,
                'i_store_location'  => $istorelocation,
                'i_area'            => $iarea,
                'd_entry'           => $dentry
            )
        );
        $this->db->insert('tm_stockopname');
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
                tm_mutasi_header
            WHERE
                i_store = '$istore'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if($query->num_rows()>0){
            $this->db->query("   
                UPDATE
                    tm_mutasi_header
                SET
                    i_stockopname_akhir = '$istockopname'
                WHERE
                    i_store = '$istore'
                    AND e_mutasi_periode = '$emutasiperiode'    
            ",false);
        }else{
            $this->db->query("
                INSERT
                    INTO
                    tm_mutasi_header
                VALUES ('$istore',
                '$emutasiperiode',
                NULL,
                '$istockopname',
                '$istorelocation')
            ",false);
        }
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi_header
            WHERE
                i_store = '$istore'
                AND e_mutasi_periode = '$perdpn'
        ",false);
        if($query->num_rows()>0){
            $this->db->query("
                UPDATE
                    tm_mutasi_header
                SET
                    i_stockopname_awal = '$istockopname'
                WHERE
                    i_store = '$istore'
                    AND e_mutasi_periode = '$perdpn'
            ",false);
        }else{
            $this->db->query("
                INSERT
                    INTO
                    tm_mutasi_header
                VALUES ('$istore',
                '$perdpn',
                '$istockopname',
                NULL,
                '$istorelocation')
            ",false);
        }
        /*end update ke mutasi header*/
    }

    public function insertdetail($iproduct, $iproductgrade, $eproductname, $nstockopname, $istockopname, $istore, $istorelocation, $istorelocationbin, $iproductmotif, $dstockopname, $iarea, $i){
        $pr = '20'.substr($istockopname,3,4);
        $this->db->set(
            array(
                'i_stockopname'       => $istockopname,
                'd_stockopname'       => $dstockopname,
                'i_store'             => $istore,
                'i_store_location'    => $istorelocation,
                'i_store_locationbin' => $istorelocationbin,
                'i_product'           => $iproduct,
                'i_product_grade'     => $iproductgrade,
                'e_product_name'      => $eproductname,
                'i_product_motif'     => $iproductmotif,
                'n_stockopname'       => $nstockopname,
                'i_area'              => $iarea,
                'e_mutasi_periode'    => $pr,
                'n_item_no'           => $i
            )
        );
        $this->db->insert('tm_stockopname_item');
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada = false;
        $query = $this->db->query("
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

    public function updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_saldo_stockopname = $qdo
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
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
        $que=$this->db->query("
            SELECT
                *
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
        if($que->num_rows()>1){
            foreach($que->result() as $row){
                $gitasal=$row->n_mutasi_git;
                $gitpenjualanasal=$row->n_git_penjualan;
            }
        }else{
            $gitasal=0;
            $gitpenjualanasal=0;
        } 
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_saldo_awal = $qdo,
                n_saldo_akhir =($qdo + $gitasal + $gitpenjualanasal + n_mutasi_pembelian + n_mutasi_returoutlet + n_mutasi_bbm)-(n_mutasi_penjualan + n_mutasi_returpabrik + n_mutasi_bbk),
                n_mutasi_gitasal = $gitasal,
                n_git_penjualanasal = $gitpenjualanasal
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$perdpn'
        ",false);
    }

    public function insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
            INSERT
                INTO
                tm_mutasi ( i_product,
                i_product_motif,
                i_product_grade,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_mutasi_periode,
                n_saldo_awal,
                n_mutasi_pembelian,
                n_mutasi_returoutlet,
                n_mutasi_bbm,
                n_mutasi_penjualan,
                n_mutasi_returpabrik,
                n_mutasi_bbk,
                n_saldo_akhir,
                n_saldo_stockopname,
                f_mutasi_close)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$emutasiperiode',
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $qdo,
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
        $que = $this->db->query("
            SELECT
                *
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
        if($que->num_rows()>1){
            foreach($que->result() as $row){
                $gitasal=$row->n_mutasi_git;
                $gitpenjualanasal=$row->n_git_penjualan;
            }
        }else{
            $gitasal=0;
            $gitpenjualanasal=0;
        }
        $query=$this->db->query("
            INSERT
                INTO
                tm_mutasi ( i_product,
                i_product_motif,
                i_product_grade,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_mutasi_periode,
                n_saldo_awal,
                n_mutasi_pembelian,
                n_mutasi_returoutlet,
                n_mutasi_bbm,
                n_mutasi_penjualan,
                n_mutasi_returpabrik,
                n_mutasi_bbk,
                n_saldo_akhir,
                n_saldo_stockopname,
                f_mutasi_close,
                n_mutasi_git,
                n_mutasi_pesan,
                n_mutasi_ketoko,
                n_mutasi_daritoko,
                n_git_penjualan,
                n_mutasi_gitasal,
                n_git_penjualanasal)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$perdpn',
            $qdo,
            0,
            0,
            0,
            0,
            0,
            0,
            $qdo + $gitasal + $gitpenjualanasal,
            0,
            'f',
            0,
            0,
            0,
            0,
            0,
            $gitasal,
            $gitpenjualanasal)
        ",false);
    }
}

/* End of file Mmaster.php */