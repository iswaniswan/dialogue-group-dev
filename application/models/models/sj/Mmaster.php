<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function area(){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->order_by('i_area');
        return $this->db->get();
    }

    public function getspb($iarea) {
        $this->db->select("
                a.*,
                b.e_customer_name,
                c.e_area_name,
                TO_CHAR(a.d_spb, 'dd-mm-yyyy') AS dspb
            FROM
                tm_spb a,
                tr_customer b,
                tr_area c
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = c.i_area
                AND a.i_area = '$iarea'
                AND a.i_nota ISNULL
                AND NOT a.i_approve1 ISNULL
                AND NOT a.i_approve2 ISNULL
                AND NOT a.i_store ISNULL
                AND a.f_spb_cancel = 'f'
                AND ( a.f_spb_stockdaerah = 't' )
                AND a.i_sj ISNULL
            ORDER BY
                a.i_spb ",false);
        return $this->db->get();
    }

    public function getcus($iarea, $ispb) {
        $this->db->select(" 
                a.*,
                b.e_customer_name,
                c.e_area_name,
                TO_CHAR(a.d_spb, 'dd-mm-yyyy') AS dspb
            FROM
                tm_spb a,
                tr_customer b,
                tr_area c
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = c.i_area
                AND a.i_area = '$iarea'
                AND a.i_spb = '$ispb'
                AND a.i_nota ISNULL
                AND NOT a.i_approve1 ISNULL
                AND NOT a.i_approve2 ISNULL
                AND NOT a.i_store ISNULL
                AND a.f_spb_cancel = 'f'
                AND ( a.f_spb_stockdaerah = 't' )
                AND a.i_sj ISNULL
            ORDER BY
                a.i_spb",false);
        return $this->db->get();
    }

    public function detail($spb,$iarea){
        $this->db->select(" 
                a.i_product AS kode,
                a.i_product_motif AS motif,
                b.n_deliver,
                a.e_product_motifname AS namamotif,
                b.n_order AS n_qty,
                c.e_product_name AS nama,
                b.v_unit_price AS harga,
                b.i_product_grade AS grade
            FROM
                tr_product_motif a,
                tr_product c,
                tm_spb_item b
            WHERE
                a.i_product = c.i_product
                AND b.i_product_motif = a.i_product_motif
                AND c.i_product = b.i_product
                AND b.i_spb = '$spb'
                AND i_area = '$iarea'
            ORDER BY
                b.n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function cekdaerah($ispb,$iarea){
        $this->db->select('f_spb_stockdaerah');
        $this->db->from('tm_spb');
        $this->db->where('i_spb', $ispb); 
        $this->db->where('i_area', $iarea);
        $query= $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $qq){
                $stockdaerah=$qq->f_spb_stockdaerah;
            }
            return $stockdaerah;
        }
    }

    public function ceknota($sjpot){
        /*$this->db->select('ad_sj');
        $this->db->from('tm_nota');
        $this->db->like('i_sj', '$sjpot', 'after');
        $this->db->order_by('d_sj', 'desc');
        return $this->db->get();*/
        return $this->db->query("SELECT d_sj FROM tm_nota WHERE i_sj LIKE '$sjpot%' ORDER BY d_sj DESC");
    }

    public function getjum($ispb, $iarea){
        $this->db->select("
                a.i_product AS kode
            FROM
                tr_product_motif a,
                tr_product c,
                tm_spb_item b
            WHERE
                a.i_product = c.i_product
                AND b.i_product_motif = a.i_product_motif
                AND c.i_product = b.i_product
                AND b.i_spb = '$ispb'
                AND b.i_area = '$iarea' ",false);
        return $this->db->get();
    }

    public function getdata($ispb, $iarea){
        $this->db->select("              
                v_spb,
                n_spb_discount1,
                n_spb_discount2,
                n_spb_discount3,
                v_spb_discount1,
                v_spb_discount2,
                v_spb_discount3,
                v_spb_discounttotal,
                i_customer,
                i_salesman,
                n_spb_toplength,
                f_spb_consigment,
                f_spb_plusppn
            FROM
                tm_spb
            WHERE
                i_spb = '$ispb'
                AND i_area = '$iarea'",false);         
        return $this->db->get();
    }

    public function cekkons($ispb,$iarea){
        $consigment='f';
        $this->db->select('f_spb_consigment');      
        $this->db->from('tm_spb');
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $query=$this->db->get();      
        if ($query->num_rows() > 0){        
            foreach($query->result() as $qq){            
                $consigment=$qq->f_spb_consigment;        
            }    
        }    
        return $consigment;
    }

    public function ceksj($ispb,$iarea){
        $this->db->select('*');
        $this->db->from('tm_nota');
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $query = $this->db->get();
        if ($query->num_rows() > 0){        
            return true;      
        }else{          
            return false;      
        }  
    }

    public function runningnumbersj($iarea,$thbl,$kons){      
        $th   = substr($thbl,0,4);      
        $asal = $thbl;      
        $thbl = substr($thbl,2,2).substr($thbl,4,2);      
        if($kons=='t' && $iarea!='PB'){        
            $this->db->select(" n_modul_no as max from tm_dgu_no 
            where i_modul='SJ'
            and e_periode='$asal' 
            and i_area='BK' for update", false);
        }else{
            $this->db->select(" n_modul_no as max from tm_dgu_no 
            where i_modul='SJ'
            and e_periode='$asal' 
            and i_area='$iarea' for update", false);    
        }    
        $query = $this->db->get();    
        if ($query->num_rows() > 0){      
            foreach($query->result() as $row){        
                $terakhir=$row->max;    
            }    
            $nosj  =$terakhir+1;    
            if($kons=='t' && $iarea!='PB'){      
                $this->db->query(" update tm_dgu_no           
                    set n_modul_no=$nosj          
                    where i_modul='SJ'          
                    and e_periode='$asal'           
                    and i_area='BK'", false);  
            }else{      
                $this->db->query(" update tm_dgu_no           
                    set n_modul_no=$nosj          
                    where i_modul='SJ'          
                    and e_periode='$asal'           
                    and i_area='$iarea'", false);  
            }  
            settype($nosj,"string");  
            $a=strlen($nosj);  
            while($a<4){    
                $nosj="0".$nosj;    
                $a=strlen($nosj);
            }
            if($kons=='t' && $iarea!='PB'){  
                $nosj  ="SJ-".$thbl."-BK".$nosj;
            }else{    
                $nosj  ="SJ-".$thbl."-".$iarea.$nosj;
            }
            return $nosj;
        }else{  
            $nosj  ="0001";  
            if($kons=='t' && $iarea!='PB'){      
                $nosj  ="SJ-".$thbl."-BK".$nosj;      
                $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)        
                    values ('SJ','BK','$asal',1)");  
            }else{      
                $nosj  ="SJ-".$thbl."-".$iarea.$nosj;      
                $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)        
                    values ('SJ','$iarea','$asal',1)");  
            }  
            return $nosj;
        }
    }

    public function insertsjheader($ispb,$dspb,$isj,$dsj,$iarea,$isalesman,$icustomer,$nspbdiscount1,$nspbdiscount2,$nspbdiscount3,$vspbdiscount1,$vspbdiscount2,$vspbdiscount3,$vspbdiscounttotal,$vspbgross,$vspbnetto,$isjold,$fentpusat,$iareareff){
        $query        = $this->db->query("SELECT f_customer_plusppn, f_customer_plusdiscount from tr_customer where i_customer='$icustomer'");      
        $row          = $query->row();      
        $plusppn  = $row->f_customer_plusppn;      
        $plusdiscount = $row->f_customer_plusdiscount;      
        $que      = $this->db->query("SELECT f_spb_consigment from tm_spb where i_spb='$ispb' and i_area='$iarea'");      
        $row      = $que->row();      
        $kons     = $row->f_spb_consigment;      
        if ($kons=='t'){        
            $dkb    ='SYSTEM';        
            $ddkb   =$dsj;    
        } else {        
            $dkb    =NULL;        
            $ddkb   =NULL;    
        }          
        $query        = $this->db->query("SELECT current_timestamp as c");          
        $row          = $query->row();          
        $dsjentry = $row->c;          
        if($iarea=='PB'){            
            $this->db->set(
                array(      
                    'i_sj'                => $isj,
                    'i_sj_old'            => $isjold,
                    'i_spb'               => $ispb,
                    'd_spb'               => $dspb,
                    'd_sj'                => $dsj,
                    'd_sj_receive'        => $dsj,
                    'i_area'              => $iarea,
                    'i_salesman'          => $isalesman,
                    'i_customer'          => $icustomer,
                    'f_plus_ppn'          => $plusppn,
                    'f_plus_discount'     => $plusdiscount,
                    'n_nota_discount1'    => $nspbdiscount1,
                    'n_nota_discount2'    => $nspbdiscount2,
                    'n_nota_discount3'    => $nspbdiscount3,
                    'v_nota_discount1'    => $vspbdiscount1,
                    'v_nota_discount2'    => $vspbdiscount2,
                    'v_nota_discount3'    => $vspbdiscount3,
                    'v_nota_discounttotal'=> $vspbdiscounttotal,
                    'v_nota_discount'     => $vspbdiscounttotal,
                    'v_nota_gross'        => $vspbgross,
                    'v_nota_netto'        => $vspbnetto,
                    'd_sj_entry'          => $dsjentry,
                    'i_dkb'               => $dkb,
                    'd_dkb'               => $ddkb,
                    'f_nota_cancel'       => 'f'
                )
            );
        }else{
            $this->db->set(
                array(      
                    'i_sj'                => $isj,
                    'i_sj_old'            => $isjold,
                    'i_spb'               => $ispb,
                    'd_spb'               => $dspb,
                    'd_sj'                => $dsj,
                    'i_area'              => $iarea,
                    'i_salesman'          => $isalesman,
                    'i_customer'          => $icustomer,
                    'f_plus_ppn'          => $plusppn,
                    'f_plus_discount'     => $plusdiscount,
                    'n_nota_discount1'    => $nspbdiscount1,
                    'n_nota_discount2'    => $nspbdiscount2,
                    'n_nota_discount3'    => $nspbdiscount3,
                    'v_nota_discount1'    => $vspbdiscount1,
                    'v_nota_discount2'    => $vspbdiscount2,
                    'v_nota_discount3'    => $vspbdiscount3,
                    'v_nota_discounttotal'=> $vspbdiscounttotal,
                    'v_nota_discount'     => $vspbdiscounttotal,
                    'v_nota_gross'        => $vspbgross,
                    'v_nota_netto'        => $vspbnetto,
                    'd_sj_entry'          => $dsjentry,
                    'i_dkb'               => $dkb,
                    'd_dkb'               => $ddkb,
                    'f_nota_cancel'       => 'f'
                )
            );
        }
        $this->db->insert('tm_nota');
    }

    public function updatespb($ispb,$iarea,$isj,$dsj){
        $tm_spb = array(
            'i_sj' => $isj,
            'd_sj' => $dsj
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $tm_spb);
    }

    public function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver, $vunitprice,$isj,$iarea,$i){
        $this->db->select('i_product_category, i_product_class');
        $this->db->from('tr_product');
        $this->db->where('i_product', $iproduct);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $qq){
                $i_productcategory = $qq->i_product_category;
                $i_productclass = $qq->i_product_class;
            }
            $this->db->select("
                    a.i_product_category,
                    a.e_product_categoryname,
                    b.i_product_class,
                    b.e_product_classname
                FROM
                    tr_product_category a,
                    tr_product_class b
                WHERE
                    a.i_product_category = '$i_productcategory'
                    AND b.i_product_class = '$i_productclass' 
            ",false);
            $query2=$this->db->get();
            if ($query2->num_rows() > 0){
                foreach($query2->result() as $oo){
                    $i_product_category     =$oo->i_product_category;
                    $e_product_categoryname =$oo->e_product_categoryname;
                    $i_product_class        =$oo->i_product_class;
                    $e_product_classname    =$oo->e_product_classname;
                }
            }

            $this->db->set(
                array(
                    'i_sj'                   => $isj,
                    'i_area'                 => $iarea,
                    'i_product'              => $iproduct,
                    'i_product_motif'        => $iproductmotif,
                    'i_product_grade'        => $iproductgrade,
                    'e_product_name'         => $eproductname,
                    'n_deliver'              => $ndeliver,
                    'v_unit_price'           => $vunitprice,
                    'i_product_category'     => $i_product_category,
                    'e_product_categoryname' => $e_product_categoryname,
                    'i_product_class'        => $i_product_class,
                    'e_product_classname'    => $e_product_classname,
                    'n_item_no'              => $i
                )
            );
            $this->db->insert('tm_nota_item');
        }
    }

    public function updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea,$vunitprice){
        $tm_spb_item = array(
            'n_deliver' => $ndeliver
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', $iproductmotif);
        $this->db->where('i_area', $iarea);
        $this->db->where('v_unit_price', $vunitprice);
        $this->db->update('tm_spb_item', $tm_spb_item);
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $this->db->select('n_quantity_stock');
        $this->db->from('tm_ic');
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', '00');
        $this->db->where('i_store', $istore);
        $this->db->where('i_store_location', $istorelocation);
        $this->db->where('i_store_locationbin', $istorelocationbin);
        $query=$this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak){
        $query    = $this->db->query("SELECT current_timestamp as c");        
        $row      = $query->row();        
        $now      = $row->c;
        $query=$this->db->query("                                 
            INSERT INTO
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
            '00',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            '$isj',
            '$now',
            0,
            $qsj,
            $q_ak-$qsj,
            $q_ak )
            ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada=false;
        $this->db->select('i_product');
        $this->db->from('tm_mutasi');
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', $iproductmotif);
        $this->db->where('i_store', $istore);
        $this->db->where('i_store_location', $istorelocation);
        $this->db->where('i_store_locationbin', $istorelocationbin);
        $this->db->where('e_mutasi_periode', $emutasiperiode);
        $query=$this->db->get();
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_git_penjualan = n_git_penjualan + $qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
            ", false);
    }

    public function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$qaw){
        $insert = array(
            'i_product'             => $iproduct,
            'i_product_motif'       => $iproductmotif,
            'i_product_grade'       => $iproductgrade,
            'i_store'               => $istore,
            'i_store_location'      => $istorelocation,
            'i_store_locationbin'   => $istorelocationbin,
            'e_mutasi_periode'      => $emutasiperiode,
            'n_saldo_awal'          => $qaw,
            'n_mutasi_pembelian'    => 0,
            'n_mutasi_returoutlet'  => 0,
            'n_mutasi_bbm'          => 0,
            'n_mutasi_penjualan'    => 0,
            'n_mutasi_returpabrik'  => 0,                                  
            'n_mutasi_bbk'          => 0,
            'n_saldo_akhir'         => 0,
            'n_saldo_stockopname'   => 0,
            'f_mutasi_close'        => 'f',
            'n_git_penjualan'       => $qsj,

        );
        $this->db->insert('tm_mutasi', $insert);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){      
        $ada=false;
        $this->db->select('i_product');
        $this->db->from('tm_ic');
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', '00');
        $this->db->where('i_store', $istore);
        $this->db->where('i_store_location', $istorelocation);
        $this->db->where('i_store_locationbin', $istorelocationbin);
        $query=$this->db->get();
        if ($query->num_rows() > 0){                
            $ada=true;            
        }      
        return $ada;
    }

    public function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
        $query=$this->db->query("
            UPDATE
                tm_ic
            SET
                n_quantity_stock = n_quantity_stock-$qsj
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '00'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
            ",false);
    }

    public function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj,$q_aw){      
        $query=$this->db->query("
            INSERT INTO
                tm_ic
            VALUES ( '$iproduct',
            '00',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$eproductname',
            $q_aw-$qsj,
            't' )
            ",false);
    }    
}

/* End of file Mmaster.php */
