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

    public function data($folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                c.i_customer,
                i_adj,
                to_char(d_adj, 'dd-mm-yyyy') AS dadj,
                e_customer_name,
                e_remark,
                '$folder' AS folder
            FROM
                tr_customer c,
                tm_adjmo a
            WHERE
                a.i_customer = c.i_customer
                AND a.i_approve ISNULL
                AND a.f_adj_cancel = 'f'
            ORDER BY
                a.d_adj,
                a.i_customer,
                a.i_adj DESC
        ", false);
        $datatables->add('action', function ($data) {
            $iadj   = trim($data['i_adj']);
            $icust  = trim($data['i_customer']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iadj/$icust\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('i_customer');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($iadj, $icustomer){
        $this->db->select("
                a.*,
                to_char(a.d_adj, 'dd-mm-yyyy') AS dadj,
                b.e_customer_name
            FROM
                tm_adjmo a,
                tr_customer b
            WHERE
                a.i_customer = b.i_customer
                AND i_adj = '$iadj'
                AND a.i_customer = '$icustomer'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($iadj, $icustomer){
        $this->db->select("
                a.*,
                b.e_product_motifname
            FROM
                tm_adjmo_item a,
                tr_product_motif b
            WHERE
                a.i_adj = '$iadj'
                AND i_customer = '$icustomer'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function approve($iadj, $icustomer, $user){
        $now = current_datetime();
        $this->db->set(
            array(
                'i_approve' => $user,
                'd_approve' => $now
            )
        );
        $this->db->where('i_adj',$iadj);
        $this->db->where('i_customer',$icustomer);
        $this->db->update('tm_adjmo');
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
                d_transaction DESC
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

    public function runningnumberbbm($thbl,$iarea){
        $th   = substr($thbl,2,2);
        $thbl = substr($thbl,2,4);
        $this->db->select("
                MAX(substr(i_bbmadj, 10, 6)) AS MAX
            FROM
                tm_bbmadj
            WHERE
                substr(i_bbmadj,
                5,
                2)= '$th'
                AND i_area = '$iarea'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobbm  =$terakhir+1;
            settype($nobbm,"string");
            $a=strlen($nobbm);
            while($a<6){
                $nobbm="0".$nobbm;
                $a=strlen($nobbm);
            }
            $nobbm  ="BBM-".$thbl."-".$nobbm;
            return $nobbm;
        }else{
            $nobbm  ="000001";
            $nobbm  ="BBM-".$thbl."-".$nobbm;
            return $nobbm;
        }
    }

    public function insertheaderbbm($ibbm,$iadj,$dadj,$iarea,$eremark){
        $query    = $this->db->query("SELECT current_timestamp as c, to_char(current_timestamp,'yyyy-mm-dd') as d");
        $row    = $query->row();
        $now    = $row->c;
        $tgl    = $row->d;
        $query  = $this->db->query("
            INSERT
                INTO
                tm_bbmadj (i_adj,
                d_adj,
                i_bbmadj,
                d_bbmadj,
                i_area,
                e_remark,
                d_entry)
            VALUES ('$iadj',
            '$dadj',
            '$ibbm',
            '$row->d',
            '$iarea',
            '$eremark',
            '$row->c')
        ",false);
    }

    public function insertdetailbbm($ibbm,$iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$nreceive,$vproductmill,$eremark,$eproductname,$dadj,$i){
        $query = $this->db->query("SELECT current_timestamp as c, to_char(current_timestamp,'yyyymm') as d");
        $row   = $query->row();
        $now   = $row->d;
        $query = $this->db->query(" 
            INSERT
                INTO
                tm_bbmadj_item (i_bbmadj,
                i_adj,
                i_area,
                i_product,
                i_product_motif,
                i_product_grade,
                n_quantity,
                v_unit_price,
                e_remark,
                e_product_name,
                d_adj,
                e_mutasi_periode,
                n_item_no)
            VALUES ('$ibbm',
            '$iadj',
            '$iarea',
            '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$nreceive',
            '$vproductmill',
            '$eremark',
            '$eproductname',
            '$dadj',
            '$now',
            $i)
        ",false);
    }

    public function inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$qbm,$q_aw,$q_ak){
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
            $qbm,
            0,
            $q_ak + $qbm,
            $q_ak )
        ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada=false;
        $query=$this->db->query("
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

    public function updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbm,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbm = n_mutasi_bbm + $qbm,
                n_saldo_akhir = n_saldo_akhir + $qbm
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

    public function insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbm,$emutasiperiode){
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
            $qbm,
            0,
            0,
            0,
            $qbm,
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

    public function updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbm,$q_ak){
        $query = $this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock + $qbm
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbm){
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
            $qbm,
            't' )
        ",false);
    }

    public function runningnumberbbk($thbl,$iarea){
        $th   = substr($thbl,2,2);
        $thbl = substr($thbl,2,4);
        $this->db->select("
                MAX(substr(i_bbkadj, 10, 6)) AS MAX
            FROM
                tm_bbkadj
            WHERE
                substr(i_bbkadj,
                5,
                2)= '$th'
                AND i_area = '$iarea'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir = $row->max;
            }
            $nobbk  =$terakhir+1;
            settype($nobbk,"string");
            $a=strlen($nobbk);
            while($a<6){
                $nobbk="0".$nobbk;
                $a=strlen($nobbk);
            }
            $nobbk  ="BBK-".$thbl."-".$nobbk;
            return $nobbk;
        }else{
            $nobbk  ="000001";
            $nobbk  ="BBK-".$thbl."-".$nobbk;
            return $nobbk;
        }
    }

    public function insertheaderbbk($ibbk,$iadj,$dadj,$iarea,$eremark){
        $query = $this->db->query("SELECT current_timestamp as c, to_char(current_timestamp,'yyyy-mm-dd') as d");
        $row   = $query->row();
        $now   = $row->c;
        $tgl   = $row->d;
        $query = $this->db->query("
            INSERT
                INTO
                tm_bbkadj (i_adj,
                d_adj,
                i_bbkadj,
                d_bbkadj,
                i_area,
                e_remark,
                d_entry)
            VALUES ('$iadj',
            '$dadj',
            '$ibbk',
            '$row->d',
            '$iarea',
            '$eremark',
            '$row->c')
        ",false);
    }

    public function insertdetailbbk($ibbk,$iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$nreceive,$vproductmill,$eremark,$eproductname,$dadj,$i){
        $query  = $this->db->query("SELECT current_timestamp as c, to_char(current_timestamp,'yyyymm') as d");
        $row    = $query->row();
        $now    = $row->c;
        $peri   = $row->d;
        $query  = $this->db->query(" 
            INSERT
                INTO
                tm_bbkadj_item (i_bbkadj,
                i_adj,
                i_area,
                i_product,
                i_product_motif,
                i_product_grade,
                n_quantity,
                v_unit_price,
                e_remark,
                e_product_name,
                d_adj,
                e_mutasi_periode,
                n_item_no)
            VALUES ('$ibbk',
            '$iadj',
            '$iarea',
            '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$nreceive',
            '$vproductmill',
            '$eremark',
            '$eproductname',
            '$dadj',
            '$peri',
            $i)  
        ",false);
    }

    public function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbk,$q_aw,$q_ak){
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
            $qbk,
            $q_ak-$qbk,
            $q_ak )
        ",false);
    }

    public function updatemutasibbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbk,$emutasiperiode){
        $query = $this->db->query(" 
            UPDATE
                tm_mutasi
            SET
                n_mutasi_bbk = n_mutasi_bbk + $qbk,
                n_saldo_akhir = n_saldo_akhir-$qbk
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

    public function insertmutasibbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbk,$emutasiperiode){
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
            $qbk,
            0,
            0,
            'f')
        ",false);
    }

    public function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbk,$q_ak){
        $query = $this->db->query(" 
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock-$qbk
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
    }

    public function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbm){
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