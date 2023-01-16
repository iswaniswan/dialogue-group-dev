<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function product1($cari, $user){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_product,
                e_product_name,
                i_product_grade
            FROM
                f_listproduct_b('$user','%$cari%')
        ", FALSE);
    }

    public function getproduct1($user,$iproduct,$grade){
        return $this->db->query("
            SELECT
                *
            FROM
                f_listproduct_b('$user','$iproduct')
            WHERE i_product = '$iproduct'
                AND i_product_grade = '$grade' 
        ", FALSE);
    }

    public function runningnumber($thbl){
        $th = substr($thbl,0,2);
        $this->db->select(" 
                MAX(substr(i_ic_convertion, 9, 6)) AS MAX
            FROM
                tm_ic_convertionbkb
            WHERE
                substr(i_ic_convertion,
                4,
                2)= '$th'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir = $row->max;
            }
            $noso = $terakhir+1;
            settype($noso,"string");
            $a = strlen($noso);
            while($a<6){
                $noso="0".$noso;
                $a=strlen($noso);
            }
            $noso  ="KS-".$thbl."-".$noso;
            return $noso;
        }else{
            $noso  ="000001";
            $noso  ="KS-".$thbl."-".$noso;
            return $noso;
        }
    }

    public function runningnumberbbk($thbl){
        $th   = substr($thbl,0,4);
        $asal = $thbl;
        $thbl = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select("
                n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'BBK'
                AND i_area = '00'
                AND SUBSTRING(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobbk  =$terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nobbk
                WHERE
                    i_modul = 'BBK'
                    AND i_area = '00'
                    AND SUBSTRING(e_periode, 1, 4)= '$th'
            ", false);
            settype($nobbk,"string");
            $a = strlen($nobbk);
            while($a<6){
                $nobbk="0".$nobbk;
                $a=strlen($nobbk);
            }
            $nobbk  ="BBK-".$thbl."-".$nobbk;
            return $nobbk;
        }else{
            $nobbk  ="000001";
            $nobbk  ="BBK-".$thbl."-".$nobbk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('BBK',
                '00',
                '$asal',
                1)
            ");
            return $nobbk;
        }
    }

    public function runningnumberbbm($thbl){
        $th   = substr($thbl,0,4);
        $asal = $thbl;
        $thbl = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select("
                n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'BBM'
                AND i_area = '00'
                AND SUBSTRING(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobbm = $terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nobbm
                WHERE
                    i_modul = 'BBM'
                    AND i_area = '00'
                    AND SUBSTRING(e_periode, 1, 4)= '$th'
            ", false);
            settype($nobbm,"string");
            $a = strlen($nobbm);
            while($a<6){
                $nobbm="0".$nobbm;
                $a=strlen($nobbm);
            }
            $nobbm  ="BBM-".$thbl."-".$nobbm;
            return $nobbm;
        }else{
            $nobbm  ="000001";
            $nobbm  ="BBM-".$thbl."-".$nobbm;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('BBM',
                '00',
                '$asal',
                1)
            ");
            return $nobbm;
        }
    }

    public function insertheader($iicconvertion,$dicconvertion,$iproduct,$iproductgrade,$eproductname,$iproductmotif,$ficconvertion,$nicconvertion){
        $dentry   = current_datetime();
        $this->db->set(
            array(
                'i_ic_convertion' => $iicconvertion,
                'd_ic_convertion' => $dicconvertion,
                'i_product'       => $iproduct,
                'i_product_motif' => $iproductmotif,
                'i_product_grade' => $iproductgrade,
                'e_product_name'  => $eproductname,
                'f_ic_convertion' => $ficconvertion,
                'n_ic_convertion' => $nicconvertion,
                'd_entry'         => $dentry
            )
        );   
        $this->db->insert('tm_ic_convertionbkb');
    }

    public function insertbbkdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbk,$eremark,$ibbktype,$periode){
        $this->db->set(
            array(
                'i_bbk'                => $ibbk,
                'i_bbk_type'           => $ibbktype,
                'i_refference_document'=> $iicconvertion,
                'i_product'            => $iproduct,
                'i_product_motif'      => $iproductmotif,
                'i_product_grade'      => $iproductgrade,
                'e_product_name'       => $eproductname,
                'n_quantity'           => $nicconvertion,
                'v_unit_price'         => $vunitprice,
                'e_mutasi_periode'     => $periode,
                'e_remark'             => $eremark
            )
        );        
        $this->db->insert('tm_bbk_item');
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query = $this->db->query("
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
                i_trans DESC
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query = $this->db->query("
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

    public function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbbk,$q_aw,$q_ak){
        $now   = current_datetime();
        $query = $this->db->query(" 
            INSERT
                INTO
                tm_ic_trans ( i_product,
                i_product_grade,
                i_product_motif,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_product_name,
                i_refference_document,
                d_transaction,
                n_quantity_in,
                n_quantity_out,
                n_quantity_akhir,
                n_quantity_awal)
            VALUES ( '$iproduct',
            '$iproductgrade',
            '$iproductmotif',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            '$ibbk',
            '$now',
            0,
            $qbbk,
            $q_ak-$qbbk,
            $q_ak )
        ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada    = false;
        $query  = $this->db->query("
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

    public function updatemutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbk = n_mutasi_bbk + $qbbk,
                n_saldo_akhir = n_saldo_akhir-$qbbk
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

    public function insertmutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
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
            'AA',
            '01',
            '00',
            '$emutasiperiode',
            0,
            0,
            0,
            0,
            0,
            0,
            $qbbk,
            $qbbk,
            0,
            'f')
        ",false);
    }

    public function updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbk = n_mutasi_bbk + $qbbk,
                n_saldo_akhir = n_saldo_akhir-$qbbk
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

    public function insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
        $query = $this->db->query("
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
            'AA',
            '01',
            '00',
            '$emutasiperiode',
            $qbbk,
            0,
            0,
            0,
            0,
            0,
            $qbbk,
            0,
            0,
            'f')
        ",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $ada   = false;
        $query = $this->db->query("
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

    public function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$q_ak){
        $query = $this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock-$qbbk
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbk){
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            0,
            't' )
        ",false);
    }

    public function insertbbkheader($iicconvertion,$dicconvertion,$ibbk,$dbbk,$ibbktype,$eremark,$iarea){
        $this->db->set(
            array(
                'i_bbk'                 => $ibbk,
                'i_bbk_type'            => $ibbktype,
                'i_refference_document' => $iicconvertion,
                'd_refference_document' => $dicconvertion,
                'd_bbk'                 => $dbbk,
                'e_remark'              => $eremark,
                'i_area'                => $iarea
            )
        );        
        $this->db->insert('tm_bbk');
    }

    public function insertbbmheader($iicconvertion,$dicconvertion,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea){
        $this->db->set(
            array(
                'i_bbm'                 => $ibbm,
                'i_bbm_type'            => $ibbmtype,
                'i_refference_document' => $iicconvertion,
                'd_refference_document' => $dicconvertion,
                'd_bbm'                 => $dbbm,
                'e_remark'              => $eremark,
                'i_area'                => $iarea
            )
        );        
        $this->db->insert('tm_bbm');
    }

    public function insertdetail($iicconvertion,$dicconvertion,$iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion){
        $this->db->set(
            array(
                'i_ic_convertion' => $iicconvertion,
                'i_product'       => $iproduct,
                'i_product_motif' => $iproductmotif,
                'i_product_grade' => $iproductgrade,
                'e_product_name'  => $eproductname,
                'n_ic_convertion' => $nicconvertion
            )
        );        
        $this->db->insert('tm_ic_convertionbkbitem');
    }

    public function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak){
        $now   = current_datetime();
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic_trans ( i_product,
                i_product_grade,
                i_product_motif,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_product_name,
                i_refference_document,
                d_transaction,
                n_quantity_in,
                n_quantity_out,
                n_quantity_akhir,
                n_quantity_awal)
            VALUES ( '$iproduct',
            '$iproductgrade',
            '$iproductmotif',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            '$ido',
            '$now',
            $qdo,
            0,
            $q_ak + $qdo,
            $q_ak )
        ",false);
    }

    public function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbm = n_mutasi_bbm + $qdo,
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
        $query = $this->db->query("
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
            $qdo,
            0,
            0,
            0,
            $qdo,
            0,
            'f')
        ",false);
    }

    public function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak){
        $query = $this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock + $qdo
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
        $query = $this->db->query(" 
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            $qdo,
            't' )
        ",false);
    }

    public function inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$qbbm,$q_aw,$q_ak){
        $now   = current_datetime();
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic_trans ( i_product,
                i_product_grade,
                i_product_motif,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_product_name,
                i_refference_document,
                d_transaction,
                n_quantity_in,
                n_quantity_out,
                n_quantity_akhir,
                n_quantity_awal)
            VALUES ( '$iproduct',
            '$iproductgrade',
            '$iproductmotif',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            '$ibbm',
            '$now',
            $qbbm,
            0,
            $q_ak + $qbbm,
            $q_ak )
        ",false);
    }

    public function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbm,$eremark,$ibbmtype,$periode){
        $this->db->set(
            array(
                'i_bbm'                 => $ibbm,
                'i_bbm_type'            => $ibbmtype,
                'i_refference_document' => $iicconvertion,
                'i_product'             => $iproduct,
                'i_product_motif'       => $iproductmotif,
                'i_product_grade'       => $iproductgrade,
                'e_product_name'        => $eproductname,
                'n_quantity'            => $nicconvertion,
                'v_unit_price'          => $vunitprice,
                'e_mutasi_periode'      => $periode,
                'e_remark'              => $eremark
            )
        );        
        $this->db->insert('tm_bbm_item');
    }

    public function updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode){
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbm = n_mutasi_bbm + $qbbm,
                n_saldo_akhir = n_saldo_akhir + $qbbm
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

    public function insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$emutasiperiode){
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
            'AA',
            '01',
            '00',
            '$emutasiperiode',
            0,
            0,
            0,
            $qbbm,
            0,
            0,
            0,
            $qbbm,
            0,
            'f')
        ",false);
    }

    public function updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbm,$q_ak){
        $query = $this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock + $qbbm
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbm){
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            $qbbm,
            't' )
        ",false);
    }

    public function inserttrans4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak){
        $now = current_datetime();
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic_trans ( i_product,
                i_product_grade,
                i_product_motif,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_product_name,
                i_refference_document,
                d_transaction,
                n_quantity_in,
                n_quantity_out,
                n_quantity_akhir,
                n_quantity_awal)
            VALUES ( '$iproduct',
            '$iproductgrade',
            '$iproductmotif',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            '$ido',
            '$now',
            0,
            $qdo,
            $q_ak-$qdo,
            $q_ak )
        ",false);
    }

    public function updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbk = n_mutasi_bbk + $qdo,
                n_saldo_akhir = n_saldo_akhir-$qdo
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

    public function insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query = $this->db->query("
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
            $qdo,
            0,
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

    public function updateic4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak){
        $query = $this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock-$qdo
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function insertic4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo){
        $query = $this->db->query("
            INSERT
                INTO
                tm_ic
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            0,
            't' )
        ",false);
    }
}

/* End of file Mmaster.php */