<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekopname($iperiode){
        $komplit = false;
        $store   = '';
        $this->db->select(" i_store from tr_store where f_aktif='t' ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $store=$row->i_store;
                $this->db->select(" i_stockopname_akhir from tm_mutasi_header 
                  where e_mutasi_periode='$iperiode' and i_store='$store' ",false);
                $que = $this->db->get();
                if ($que->num_rows() > 0){
                    foreach($que->result() as $ro){
                        if($ro->i_stockopname_akhir==null || $ro->i_stockopname_akhir==''){
                            $komplit=false;
                            break 2;
                        }else{
                            $komplit=true;
                        }
                    }
                    $que->free_result();
                }else{
                    $komplit=false;
                    break;
                }
            }
            $query->free_result();
        }
        if($komplit){
            $komplit=false;
            $store='';
            $this->db->select(" i_customer from tr_customer_consigment where i_customer like 'PB%' 
                and i_customer in(select i_customer from tr_customer where f_customer_aktif=true) 
                order by i_customer ",false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $store=$row->i_customer;
                    $this->db->select(" i_stockopname_akhir from tm_mutasi_headerconsigment
                        where e_mutasi_periode='$iperiode' and i_customer='$store' ",false);
                    $que = $this->db->get();
                    if ($que->num_rows() > 0){
                        foreach($que->result() as $ro){
                            if($ro->i_stockopname_akhir==null || $ro->i_stockopname_akhir==''){
                                $komplit=false;
                                break 2;
                            }else{
                                $komplit=true;
                            }
                        }
                        $que->free_result();
                    }else{
                        $komplit=false;
                        break;
                    }
                }
                $query->free_result();
            }
        }
        $komplit=true;
        return array($komplit,$store);
    }

    public function cekap($iperiode){
        $komplit=false;
        $do='';
        $this->db->select(" i_do from tm_do where to_char(d_do,'yyyymm')='$iperiode' and f_do_cancel='f' 
          and i_do not in(select b.i_do from tm_dtap a, tm_dtap_item b 
          where a.i_dtap=b.i_dtap and a.i_supplier=b.i_supplier and to_char(a.d_dtap,'yyyymm')='$iperiode'
          and a.f_dtap_cancel='f')",false);
        $que = $this->db->get();
        if ($que->num_rows() > 0){
            foreach($que->result() as $row){
                $do=$row->i_do;
                break;
            }
            $que->free_result();
            $komplit=false;
        }else{
            $komplit=true;
        }
        return array($komplit,$do);
    }

    public function cekar($iperiode){
        $komplit=false;
        $sj='';
        $this->db->select(" i_sj from tm_nota where to_char(d_sj_receive,'yyyymm')='$iperiode' and f_nota_cancel='f' 
          and i_nota isnull",false);
        $que = $this->db->get();
        if ($que->num_rows() > 0){
            $komplit=false;
            foreach($que->result() as $row){
                $sj=$row->i_sj;
                break;
            }
        }else{
            $komplit=true;
        }
        return array($komplit,$sj);
    }

    public function pindah($iperiode){
        $update   = current_datetime();
        $entry    = current_datetime();
        $user     = $this->session->userdata('username');
        $emutasiperiode = $iperiode;
        $bldpn = substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
            $perdpn=substr($emutasiperiode,0,4)+1;
            $perdpn=$perdpn.'01';
        }else{
            $perdpn=substr($emutasiperiode,0,4);
            $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
        $xperiode=$perdpn;
        
        /*Saldo Awal Stock*/
        $this->db->query(" delete from tm_mutasi_saldoawalmo where e_mutasi_periode='$xperiode'");
        $this->db->query(" insert into tm_mutasi_saldoawalmo 
            SELECT i_product, i_product_motif, i_product_grade, i_customer, '$xperiode', n_saldo_akhir-n_mutasi_git as n_saldo_awal 
            from f_mutasi_stock_mo_cust_all_saldoakhir('$iperiode')",false);
        $this->db->query(" delete from tm_mutasi_saldoawal where e_mutasi_periode='$xperiode'");
        $this->db->query(" insert into tm_mutasi_saldoawal 
            SELECT   i_store, i_store_location, i_store_locationbin, '$xperiode', i_product, i_product_grade, i_product_motif, n_saldo_akhir-(n_mutasi_git+n_git_penjualan) as n_saldo_awal 
            from f_mutasi_stock_daerah_all_saldoakhir('$iperiode')",false);
        $this->db->query(" insert into tm_mutasi_saldoawal 
            SELECT   i_store, i_store_location, i_store_locationbin, '$xperiode', i_product, i_product_grade, i_product_motif, n_saldo_akhir-(n_mutasi_git+n_git_penjualan) as n_saldo_awal 
            from f_mutasi_stock_pusat_saldoakhir('$iperiode')",false);
        $this->db->query(" insert into tm_mutasi_saldoawal 
            SELECT   i_store, i_store_location, i_store_locationbin, '$xperiode', i_product, i_product_grade, i_product_motif, n_saldo_akhir as n_saldo_awal 
            from f_mutasi_stock_mo_pb_saldoakhir('$iperiode')",false);
        /*End Of Saldo Awal Stock*/

        /*closing*/
        $this->db->select(" * from tr_coa where i_coa like '110-11%' or i_coa like '110-2%' or i_coa like '210-51%'",false);
        $que = $this->db->get();
        if ($que->num_rows() > 0){
            foreach($que->result() as $ro){
                $this->db->select("v_saldo_akhir as jum from tm_coa_saldo where i_periode='$iperiode' and i_coa='$ro->i_coa'",false);
                $query = $this->db->get();
                if ($query->num_rows() > 0){
                    foreach($query->result() as $row){
                        if($row->jum==null){
                            $row->jum=0;
                        }
                        $coa=$ro->i_coa;
                        $qu=$this->db->query("select e_coa_name from tr_coa where i_coa like '$coa'");
                        if ($qu->num_rows() > 0){
                            foreach($qu->result() as $tes){
                                $coaname=$tes->e_coa_name;
                            }
                            $qu->free_result();
                        }

                        /*select data periode yang sudah ada*/
                        $this->db->select("* from tm_coa_saldo where i_periode='$xperiode' and i_coa='$ro->i_coa'",false);
                        $qu = $this->db->get();
                        if ($qu->num_rows()== 0){
                            $coa=$ro->i_coa;
                            $user=$this->session->userdata('user_id');
                            /*update saldo akhir=saldo+debet-kredit (yang di update saldo awal, saldo akhir)*/
                            $this->db->query(" insert into tm_coa_saldo 
                                (i_periode, i_coa, v_saldo_awal, v_mutasi_debet, v_mutasi_kredit, v_saldo_akhir, d_entry,
                                i_entry, e_coa_name)
                                values
                                ('$xperiode','$coa',$saldo,0,0,$saldo,'$entry','$user','$coaname')");
                        }else{
                            $coa=$ro->i_coa;
                            $user=$this->session->userdata('user_id');
                            $this->db->query(" update tm_coa_saldo set v_saldo_awal=$saldo, v_saldo_akhir=$saldo+v_mutasi_debet-v_mutasi_kredit, 
                               d_update='$update', i_update='$user' where i_periode='$xperiode' and i_coa='$coa'");
                        }
                        $query->free_result();
                    }
                }           
            }
            $que->free_result();

            $coasm=PiutangDagangSementara;
            $coahl=HutangLain;

            $query=$this->db->query("select i_kbank,i_periode,v_sisa,i_coa_bank from tm_kbank 
                where to_char(d_bank,'yyyymm')='$iperiode' and v_sisa>0 and (i_coa='$coasm' or i_coa = '$coahl') and f_kbank_cancel='f'
                and i_kbank like 'BM%'");
            if ($query->num_rows() > 0){
                foreach($query->result() as $rec){
                    $ikbank=$rec->i_kbank;
                    $vsisa=$rec->v_sisa;
                    $icoabank=$rec->i_coa_bank;

                    $quer=$this->db->query("select e_coa_name from tr_coa where i_coa like '$coahl'");
                    if ($quer->num_rows() > 0){
                        foreach($quer->result() as $tes){
                            $coaname=$tes->e_coa_name;
                        }
                        $quer->free_result();
                    }
                    $this->db->select("sum(v_sisa) as jum from tm_kbank 
                        where to_char(d_bank,'yyyymm')<='$iperiode' and (i_coa='$coasm' or i_coa = '$coahl') and f_kbank_cancel='f'
                        and i_kbank like 'BM%'",false);
                    $quer = $this->db->get();
                    if ($quer->num_rows() > 0){
                        $row      = $quer->row();
                        $saldo    = $row->jum;
                        $this->db->select("* from tm_coa_saldo where i_periode='$xperiode' and i_coa='$coahl'",false);
                        $quer = $this->db->get();
                        if ($quer->num_rows()== 0){
                            $this->db->query(" insert into tm_coa_saldo 
                                (i_periode, i_coa, v_saldo_awal, v_mutasi_debet, v_mutasi_kredit, v_saldo_akhir, d_entry,
                                i_entry, e_coa_name)
                                values
                                ('$xperiode','$coahl',$saldo,0,0,$saldo,'$entry','$user','$coaname')");
                            $this->db->query(" insert into tm_alokasihl_reff 
                                (i_kbank, i_periode, v_bank, i_coa_bank)
                                values
                                ('$ikbank','$iperiode',$vsisa,'$icoabank')");
                            $this->db->query("update tm_kbank set v_sisa=0 where to_char(d_bank,'yyyymm')<='$iperiode' and (i_coa='$coasm' or i_coa = '$coahl') 
                              and f_kbank_cancel='f' and i_kbank like 'BM%'");
                        }else{
                            $this->db->query("update tm_coa_saldo set v_saldo_awal=$saldo, v_saldo_akhir=$saldo+v_mutasi_debet-v_mutasi_kredit, 
                              d_update='$update', i_update='$user' where i_periode='$xperiode' and i_coa='$coahl'");
                            $this->db->query("update tm_kbank set v_sisa=0 where to_char(d_bank,'yyyymm')<='$iperiode' and (i_coa='$coasm' or i_coa = '$coahl') 
                              and f_kbank_cancel='f' and i_kbank like 'BM%'");
                            $this->db->query("delete from tm_alokasihl_reff where i_kbank='$ikbank' and i_periode='$iperiode' and i_coa_bank='$icoabank'");
                            $this->db->query(" insert into tm_alokasihl_reff 
                              (i_kbank, i_periode, v_bank, i_coa_bank)
                              values
                              ('$ikbank','$iperiode',$vsisa,'$icoabank')");
                        }
                    }
                }
                $query->free_result();
            }
        }
        $this->db->query(" update tm_periode set i_periode='$xperiode'",false);
    }
}

/* End of file Mmaster.php */
