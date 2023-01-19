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
            $area = true;
        }else{
            $area = false;
        }
        return $area;
    }

    public function bacaarea(){
      return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function data($dfrom, $dto, $iarea, $folder){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = " AND a.i_area = '$iarea' ";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_dkb,
                to_char(a.d_dkb, 'dd-mm-yyyy') AS ddkb,
                a.i_sj,
                to_char(a.d_sj, 'dd-mm-yyyy') AS dsj,
                to_char(a.d_sj_receive, 'dd-mm-yyyy') AS d_sj_receive,
                a.i_spb,
                to_char(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                b.e_customer_name,
                a.i_area,
                d.f_spb_stockdaerah,
                e.e_customer_ownername,
                a.d_sj,
                a.i_area AS iarea,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_nota a,
                tr_customer b,
                tr_area c,
                tm_spb d,
                tr_customer_owner e
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = c.i_area
                AND a.i_customer = e.i_customer
                AND a.i_spb = d.i_spb
                AND a.i_area = d.i_area
                AND a.i_nota ISNULL
                AND NOT a.i_sj ISNULL
                AND a.f_nota_cancel = 'f'
                $sql
                AND (a.d_sj >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_sj <= TO_DATE('$dto', 'dd-mm-yyyy'))
            ORDER BY
                a.d_sj,
                a.i_dkb,
                a.i_sj", false);

        $datatables->edit('f_spb_stockdaerah', function($data){
            if($data['f_spb_stockdaerah'] == "t"){
                return "Ya";
            }else{
                return "Tidak";
            }
        });
        $datatables->edit('e_customer_ownername', function($data){
            if($data['e_customer_ownername'] != "" && $data['e_customer_ownername'] != null){
                return "Ya";
            }else{
                return "Tidak";
            }
        });
        $datatables->add('action', function ($data) {
            $isj    = trim($data['i_sj']);
            $folder = $data['folder'];
            $iarea  = $data['iarea'];
            $dfrom  = $data['dfrom'];
            $dto    = $data['dto'];
            $dsj    = $data['d_sj'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/proses/$dsj/$isj/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('d_sj');
        return $datatables->generate();
    }

    public function baca($isj, $iarea){
        $this->db->select("
                a.i_spb,
                a.d_nota,
                a.d_sj,
                to_char(a.d_sj, 'dd-mm-yyyy') AS dsj,
                a.d_spb,
                to_char(a.d_spb, 'dd-mm-yyyy') AS dspb,
                a.i_spb,
                a.i_area,
                a.i_customer,
                a.v_nota_discounttotal,
                a.i_salesman,
                a.i_sj,
                a.i_nota,
                a.v_nota_discounttotal,
                a.v_nota_netto,
                a.i_dkb,
                a.d_dkb,
                a.d_sj_receive,
                j.i_spb_program,
                j.i_spb_old,
                j.i_spb_po,
                j.f_spb_consigment,
                j.n_spb_toplength,
                j.v_spb,
                j.v_spb_discounttotal,
                j.i_price_group,
                j.n_spb_discount1,
                j.n_spb_discount2,
                j.n_spb_discount3,
                j.n_spb_discount4,
                j.v_spb_discount1,
                j.v_spb_discount2,
                j.v_spb_discount3,
                j.v_spb_discount4,
                j.f_spb_plusppn,
                j.f_spb_plusdiscount,
                j.f_spb_pkp,
                j.e_customer_pkpnpwp,
                e.e_promo_name,
                f.e_customer_name,
                f.f_customer_cicil,
                g.e_salesman_name,
                h.e_area_name,
                i.e_price_groupname,
                k.n_toleransi_pusat,
                k.n_toleransi_cabang
            FROM
                tm_nota a
            INNER JOIN tm_spb j ON
                (a.i_spb = j.i_spb
                AND a.i_area = j.i_area)
            LEFT JOIN tm_promo e ON
                (j.i_spb_program = e.i_promo)
            INNER JOIN tr_customer f ON
                (a.i_customer = f.i_customer)
            INNER JOIN tr_salesman g ON
                (a.i_salesman = g.i_salesman)
            INNER JOIN tr_customer_area h ON
                (a.i_customer = h.i_customer)
            LEFT JOIN tr_price_group i ON
                (j.i_price_group = i.i_price_group)
            LEFT JOIN tr_city k ON
                (f.i_city = k.i_city
                AND f.i_area = k.i_area)
            WHERE
                a.i_sj = '$isj'
                AND a.i_area = '$iarea'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isj, $iarea){
        $this->db->select("
                a.i_product,
                a.e_product_name,
                a.v_unit_price,
                c.n_deliver,
                c.n_order,
                a.i_product_motif,
                b.e_product_motifname
            FROM
                tm_nota_item a,
                tr_product_motif b,
                tm_spb_item c,
                tm_spb d,
                tm_nota e
            WHERE
                b.i_product_motif = a.i_product_motif
                AND b.i_product = a.i_product
                AND a.i_sj = e.i_sj
                AND a.i_area = e.i_area
                AND e.i_spb = d.i_spb
                AND e.i_area = d.i_area
                AND d.i_spb = c.i_spb
                AND d.i_area = c.i_area
                AND a.i_product = c.i_product
                AND a.i_product_motif = c.i_product_motif
                AND a.i_product_grade = c.i_product_grade
                AND a.n_deliver > 0
                AND a.i_sj = '$isj'
                AND a.i_area = '$iarea'
                AND a.n_deliver > 0
            ORDER BY
                a.n_item_no", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function runningnumber($iarea,$thbl){      
        $th   = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
            where i_modul='FP'
            and substr(e_periode,1,4)='$th' 
            and i_area='$iarea' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nonota  =$terakhir+1;
            $this->db->query(" update tm_dgu_no 
                set n_modul_no=$nonota
                where i_modul='FP'
                and substr(e_periode,1,4)='$th' 
                and i_area='$iarea'", false);
            settype($nonota,"string");
            $a=strlen($nonota);        
            while($a<5){            
                $nonota="0".$nonota;
                $a=strlen($nonota);
            }
            $nonota  ="FP-".$thbl."-".$iarea.$nonota;
            return $nonota;    
        }else{      
            $nonota  ="00001";      
            $nonota  ="FP-".$thbl."-".$iarea.$nonota;
            $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
             values ('FP','$iarea','$asal',1)");
            return $nonota;
        }
    }

    public function updatespb($ispb,$iarea,$inota,$dnota,$vspbdiscounttotalafter,$vspbafter){      
        $data = array(
            'i_nota'                  => $inota,
            'd_nota'                  => $dnota,
            'v_spb_discounttotalafter'=> $vspbdiscounttotalafter, 
            'v_spb_after'             => $vspbafter
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }

    public function updatenotabaru($isj,$iarea,$inota,$dnota,$eremark,$inotaold,$djatuhtempo,$nnotatoplength,$nprice,$vspbdiscounttotalafter,$vspbafter){
        if($eremark==''){
            $eremark=null;
        }
        $dentry   = current_datetime();
        $cek_jatuh_tempo = $this->db->query("select d_jatuh_tempo from tm_nota where i_sj = '$isj' and i_area = '$iarea' and d_jatuh_tempo isnull");
        if($cek_jatuh_tempo->num_rows() > 0){            
            $data = array(              
                'n_price'               => $nprice,
                'i_nota'                => $inota,
                'd_nota'                => $dnota,
                'i_nota_old'            => $inotaold,
                'e_remark'              => $eremark,
                'd_jatuh_tempo'         => $djatuhtempo,
                'n_nota_toplength'      => $nnotatoplength,
                'v_nota_discount'       => $vspbdiscounttotalafter,
                'v_nota_discounttotal'  => $vspbdiscounttotalafter,
                'v_nota_netto'          => $vspbafter,
                'v_sisa'                => $vspbafter,
                'd_nota_entry'          => $dentry
            );
        }else{          
            $data = array(              
                'n_price'               => $nprice,
                'i_nota'                => $inota,
                'd_nota'                => $dnota,
                'i_nota_old'            => $inotaold,
                'e_remark'              => $eremark,
                'n_nota_toplength'      => $nnotatoplength,
                'v_nota_discount'       => $vspbdiscounttotalafter,
                'v_nota_discounttotal'  => $vspbdiscounttotalafter,
                'v_nota_netto'          => $vspbafter,
                'v_sisa'                => $vspbafter,
                'd_nota_entry'          => $dentry
            );
        }
        $this->db->where('i_sj', $isj);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_nota', $data); 
        $data = array(
            'i_nota'                    => $inota,
            'd_nota'                    => $dnota
        );
        $this->db->where('i_sj', $isj);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_nota_item', $data);
        $this->db->select(" a.*,b.i_store,b.i_store_location,c.d_sj from tm_nota_item a, tm_spb b, tm_nota c
            where a.i_sj='$isj' and a.i_area='$iarea' and a.i_sj=c.i_sj and a.i_area=c.i_area
            and a.i_sj=b.i_sj and a.i_area=b.i_area", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){            
                if(substr($isj,3,4)==substr($inota,3,4)){              
                    $th=substr($row->d_sj,0,4);
                    $bl=substr($row->d_sj,5,2);
                    $emutasiperiode=$th.$bl;
                    $query=$this->db->query("
                        UPDATE tm_mutasi 
                        set n_git_penjualan=n_git_penjualan-$row->n_deliver, n_saldo_akhir=n_saldo_akhir-$row->n_deliver,
                        n_mutasi_penjualan=n_mutasi_penjualan+$row->n_deliver
                        where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                        and i_product_motif='$row->i_product_motif' 
                        and i_store='$row->i_store' and i_store_location='$row->i_store_location' 
                        and e_mutasi_periode='$emutasiperiode'
                        ",false);
                }else{                  
                    $th=substr($row->d_sj,0,4);
                    $bl=substr($row->d_sj,5,2);
                    $emutasiperiode=$th.$bl;       
                    $query=$this->db->query("
                        UPDATE tm_mutasi 
                        set n_mutasi_penjualan=n_mutasi_penjualan+$row->n_deliver, n_saldo_akhir=n_saldo_akhir-$row->n_deliver
                        where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                        and i_product_motif='$row->i_product_motif' 
                        and i_store='$row->i_store' and i_store_location='$row->i_store_location' 
                        and e_mutasi_periode='$emutasiperiode'
                        ",false);
                }
            }
        }
    }

    public function inserttransheader(  $inota,$iarea,$eremark,$fclose,$dnota ){
        $dentry = current_datetime(); 
        $eremark=str_replace("'","''",$eremark);
        $this->db->query("insert into tm_jurnal_transharian 
           (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
           values
           ('$inota','$iarea','$dentry','$eremark','$fclose','$dnota','$dnota')");
    }

    public function updatenotaacc($inota,$iarea){
        $this->db->query("update tm_nota set f_posting='t' where i_nota='$inota' and i_area='$iarea' and f_nota_koreksi='f'");
        $this->db->query("update tm_notakoreksi set f_posting='t' where i_nota='$inota' and i_area='$iarea'");
    }

    public function namaacc($icoa){
        $this->db->select(" e_coa_name from tr_coa where i_coa='$icoa' ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $tmp)           
            {
                $xxx=$tmp->e_coa_name;
            }
            return $xxx;
        }
    }

    public function inserttransitemdebet($accdebet,$ipelunasan,$namadebet,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dnota){
        $dentry = current_datetime(); 
        $namadebet=str_replace("'","''",$namadebet);
        $this->db->query("insert into tm_jurnal_transharianitem
           (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry, i_area)
           values
           ('$accdebet','$ipelunasan','$namadebet','$fdebet','$fposting','$vjumlah','$dnota','$dnota','$dentry','$iarea')");
    }

    public function updatesaldodebet($accdebet,$iperiode,$vjumlah){
        $this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet+$vjumlah, v_saldo_akhir=v_saldo_akhir+$vjumlah
          where i_coa='$accdebet' and i_periode='$iperiode'");
    }

    public function inserttransitemkredit($acckredit,$ipelunasan,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dnota){
        $dentry = current_datetime(); 
        $namakredit=str_replace("'","''",$namakredit);
        $this->db->query("insert into tm_jurnal_transharianitem
           (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_area)
           values
           ('$acckredit','$ipelunasan','$namakredit','$fdebet','$fposting','$vjumlah','$dnota','$dnota','$dentry','$iarea')");
    }

    public function updatesaldokredit($acckredit,$iperiode,$vjumlah){
        $this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vjumlah, v_saldo_akhir=v_saldo_akhir-$vjumlah
          where i_coa='$acckredit' and i_periode='$iperiode'");
    }

    public function insertgldebet($accdebet,$ipelunasan,$namadebet,$fdebet,$iarea,$vjumlah,$dnota,$eremark){
        $dentry = current_datetime();
        $namadebet=str_replace("'","''",$namadebet);
        $eremark=str_replace("'","''",$eremark);
        $this->db->query("insert into tm_general_ledger
           (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
           values
           ('$ipelunasan','$accdebet','$dnota','$namadebet','$fdebet',$vjumlah,'$iarea','$dnota','$eremark','$dentry')");
    }

    public function insertglkredit($acckredit,$ipelunasan,$namakredit,$fdebet,$iarea,$vjumlah,$dnota,$eremark){
        $dentry = current_datetime();
        $namakredit=str_replace("'","''",$namakredit);
        $eremark=str_replace("'","''",$eremark);
        $this->db->query("insert into tm_general_ledger
           (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry)
           values
           ('$ipelunasan','$acckredit','$dnota','$namakredit','$fdebet','$vjumlah','$iarea','$dnota','$eremark','$dentry')");
    }
}

/* End of file Mmaster.php */
