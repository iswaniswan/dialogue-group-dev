<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getproduct($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product,
                c.e_product_name
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')
            ORDER BY
                c.i_product,
                a.e_product_motifname", 
        FALSE);
    } 

    public function getdetailproduct($iproduct){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                c.e_product_name AS nama,
                c.v_product_mill AS harga
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND a.i_product = '$iproduct'
            ORDER BY
                c.i_product,
                a.e_product_motifname", 
        FALSE);
    } 

    public function runningnumber($thbl){
        $th   = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no AS max 
            FROM tm_dgu_no 
            WHERE i_modul='BM'
            AND i_area='00'
            AND substring(e_periode,1,4)='$th' 
            for update
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobm  =$terakhir+1;
            $this->db->query("
                UPDATE tm_dgu_no 
                SET n_modul_no=$nobm
                WHERE i_modul='BM'
                AND i_area='00'
                AND substring(e_periode,1,4)='$th'
            ", false);
            settype($nobm,"string");
            $a=strlen($nobm);
            while($a<6){
                $nobm="0".$nobm;
                $a=strlen($nobm);
            }
            $nobm  ="BM-".$thbl."-".$nobm;
            return $nobm;
        }else{
            $nobm  ="000001";
            $nobm  ="BM-".$thbl."-".$nobm;
            $this->db->query(" 
                INSERT INTO tm_dgu_no
                (i_modul, i_area, e_periode, n_modul_no) 
                VALUES 
                ('BM','00','$asal',1)
            ");
            return $nobm;
        }
    }

    public function insertheader($ibm, $dbm, $eremark){
        $now      = current_datetime();
        $this->db->set(
            array(
                'i_bm'      => $ibm,
                'd_bm'      => $dbm,
                'e_remark'  => $eremark,
                'd_entry'   => $now
            )
        );
        $this->db->insert('tm_bm');
    }

    public function insertdetail($ibm,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i){
        $this->db->set(
            array(
                'i_bm'              => $ibm,
                'i_product'         => $iproduct,
                'i_product_grade'   => $iproductgrade,
                'i_product_motif'   => $iproductmotif,
                'n_quantity'        => $nquantity,
                'e_product_name'    => $eproductname,
                'e_remark'          => $eremark,
                'n_item_no'         => $i
            )
        );        
        $this->db->insert('tm_bm_item');
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $this->db->select('n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out');
        $this->db->from('tm_ic_trans');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        $this->db->order_by('i_trans','desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $this->db->select('n_quantity_stock');
        $this->db->from('tm_ic');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        /*$this->db->order_by('i_trans','desc');*/
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function inserttransbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibm,$q_in,$q_out,$qbm,$q_aw,$q_ak){
        $now      = current_datetime();
        $query=$this->db->query(" 
            INSERT INTO tm_ic_trans
            (i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
            i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
            n_quantity_in, n_quantity_out,n_quantity_akhir, n_quantity_awal)
            VALUES 
            ('$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin','$eproductname', '$ibm', '$now', $qbm, 0, $q_ak+$qbm, $q_ak
            )
        ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada=false;
        $this->db->select('i_product');
        $this->db->from('tm_mutasi');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        $this->db->where('e_mutasi_periode',$emutasiperiode);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updatemutasibmelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbm,$emutasiperiode){
        $query=$this->db->query(" 
            UPDATE tm_mutasi 
            SET n_mutasi_bbm = n_mutasi_bbm + $qbm, 
                n_saldo_akhir = n_saldo_akhir + $qbm
            WHERE i_product='$iproduct' 
                AND i_product_grade = '$iproductgrade' 
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore' 
                AND i_store_location = '$istorelocation' 
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
    }

    public function insertmutasibmelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbm,$emutasiperiode){
        $query=$this->db->query(" 
            INSERT INTO tm_mutasi
            (i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
            e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
            VALUES
            ('$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qbm,0,0,0,$qbm,0,'f')
        ",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $ada=false;
        $this->db->select('i_product');
        $this->db->from('tm_ic');
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->where('i_store',$istore);
        $this->db->where('i_store_location',$istorelocation);
        $this->db->where('i_store_locationbin',$istorelocationbin);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updateicbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbm,$q_ak){
        $query=$this->db->query(" 
            UPDATE tm_ic 
            SET n_quantity_stock = n_quantity_stock + $qbm
            WHERE i_product = '$iproduct' 
                AND i_product_grade = '$iproductgrade' 
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore' 
                AND i_store_location = '$istorelocation' 
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function inserticbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbm){
        $query=$this->db->query(" 
            INSERT INTO tm_ic 
            VALUES
            ('$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qbm, 't')
        ",false);
    }
}

/* End of file Mmaster.php */