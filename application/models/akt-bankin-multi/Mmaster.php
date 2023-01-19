<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('id_company',$idcompany);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iarea = $kuy->i_area; 
        }else{
            $iarea = '';
        }
        return $iarea;
    }

    public function cekearea($iarea){
        $this->db->select('e_area_name');
        $this->db->from('tr_area');
        $this->db->where('i_area',$iarea);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $earea = $kuy->e_area_name; 
        }else{
            $earea = '';
        }
        return $earea;
    }

    public function cekperiode(){
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

    public function bacabank(){
        return $this->db->order_by('i_bank','ASC')->get('tr_bank')->result();
    }

    public function getcoabank($ibank){
        $this->db->select('i_coa');
        $this->db->from('tr_bank');
        $this->db->where('i_bank', $ibank);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $icoabank = $kuy->i_coa; 
        }else{
            $icoabank = '';
        }
        return $icoabank;
    }

    public function bacaarea($iarea){
        if ($iarea=="00") {
            return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
        }else{
            $this->db->select('*');
            $this->db->from('tr_area');
            $this->db->where('i_area', $iarea);
            return $this->db->get()->result();
        }
    }

    public function bacasaldo($area,$tanggal,$icoabank){     
        $tmp    = explode("-", $tanggal);
        $thn    = $tmp[0];
        $bln    = $tmp[1];
        $tgl    = $tmp[2];
        $dsaldo = $thn."/".$bln."/".$tgl;
        $dtos   = $this->fungsi->dateAdd("d",1,$dsaldo);
        $tmp1   = explode("-", $dtos,strlen($dtos));
        $th     = $tmp1[0];
        $bl     = $tmp1[1];
        $dt     = $tmp1[2];
        $dtos   = $th.$bl;
        $this->db->select('v_saldo_awal');
        $this->db->from('tm_coa_saldo');
        $this->db->where('i_periode',$dtos);
        $this->db->where('i_coa',$icoabank);
        $query = $this->db->get();
        $saldo=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $saldo=$row->v_saldo_awal;
            }
        }

        $this->db->select("
                SUM(b.v_bank) AS v_bank
            FROM
                tm_kbank b
            WHERE
                b.i_periode = '$dtos'
                AND b.d_bank <= '$tanggal'
                AND b.f_debet = 't'
                AND b.i_coa_bank = '$icoabank'
                AND b.f_kbank_cancel = 'f'
        ",false);
        $query = $this->db->get();
        $kredit=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $kredit=$row->v_bank;
            }
        }
        $this->db->select("
                SUM(b.v_bank) AS v_bank
            FROM
                tm_kbank b
            WHERE
                b.i_periode = '$dtos'
                AND b.d_bank <= '$tanggal'
                AND b.f_debet = 'f'
                AND b.i_coa_bank = '$icoabank'
                AND b.f_kbank_cancel = 'f'
        ",false);
        $query = $this->db->get();
        $debet=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $debet=$row->v_bank;
            }
        }
        if($saldo=='' || $saldo==null){
            $saldo=0;
        }
        if($debet=='' || $debet==null){
            $debet=0;
        }
        if($kredit=='' || $kredit==null){
            $kredit=0;
        }
        $saldo = $saldo + $debet - $kredit;
        return $saldo;
    }

    public function bacacoa($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_coa,
                e_coa_name
            FROM
                tr_coa
            WHERE
                (UPPER(i_coa) LIKE '%$cari%'
                OR UPPER(e_coa_name) LIKE '%$cari%')
                AND (NOT i_coa LIKE '110-2%')
        ", FALSE);
    }

    public function getdetailcoa($icoa){
        return $this->db->query("
            SELECT
                e_coa_name
            FROM
                tr_coa
            WHERE
                i_coa = '$icoa'
                AND (NOT i_coa LIKE '110-2%')
        ", FALSE);
    }

    public function bacagiro($cari, $area, $xtgl){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                bayar,
                v_jumlah
            FROM
                (
                SELECT
                    a.i_giro AS bayar,
                    a.d_giro_cair AS tgl,
                    a.v_jumlah
                FROM
                    tm_giro a,
                    tr_customer_groupar b
                WHERE
                    a.i_customer = b.i_customer
                    AND a.i_area = '$area'
                    AND (a.f_giro_tolak = 'f'
                    AND a.f_giro_batal = 'f')
                    AND a.v_sisa>0
                    AND a.v_sisa = a.v_jumlah
                    AND NOT a.d_giro_cair ISNULL
                    AND a.d_giro_cair <= '$xtgl'
            UNION ALL
                SELECT
                    a.i_tunai AS bayar,
                    a.d_tunai AS tgl,
                    a.v_jumlah
                FROM
                    tm_tunai a,
                    tr_customer_groupar b,
                    tm_rtunai c,
                    tm_rtunai_item d
                WHERE
                    a.i_customer = b.i_customer
                    AND a.i_area = '$area'
                    AND c.i_rtunai = d.i_rtunai
                    AND c.i_area = d.i_area
                    AND a.i_area = d.i_area_tunai
                    AND a.i_tunai = d.i_tunai
                    AND a.d_tunai <= '$xtgl'
                    AND a.f_tunai_cancel = 'f'
                    AND c.f_rtunai_cancel = 'f'
            UNION ALL
                SELECT
                    a.i_kum AS bayar,
                    d_kum AS tgl,
                    a.v_jumlah
                FROM
                    tm_kum a,
                    tr_customer_groupar b
                WHERE
                    a.i_customer = b.i_customer
                    AND a.i_area = '$area'
                    AND a.v_sisa>0
                    AND a.v_sisa = a.v_jumlah
                    AND a.f_close = 'f'
                    AND a.f_kum_cancel = 'f'
                    AND d_kum <= '$xtgl' 
            )AS a
            WHERE 
                UPPER(bayar) LIKE '%$cari%'
                AND bayar NOT IN(
                    SELECT
                        x.i_giro
                    FROM
                        tm_kbank x
                    WHERE
                        x.f_kbank_cancel = 'f'
                        AND x.i_area = '$area' AND UPPER(x.i_giro) LIKE '%$cari%'
                )
            ORDER BY
                a.bayar
        ", FALSE);
    }

    public function runningnumberrvb($th,$bl,$icoabank,$iarea){
        $periode = $th.$bl;
        if($periode > "1912"){
            $this->db->select(" max(substr(i_rvb,9,5)) as max 
                from tm_rvb 
                where substr(i_rvb,4,2)='$th' /*and substr(i_rvb,6,2)='$bl'*/ and i_coa_bank='$icoabank'", false);

            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $terakhir=$row->max;
                }
                $norvb  =$terakhir+1;
                settype($norvb,"string");
                $a=strlen($norvb);
                while($a<5){
                    $norvb="0".$norvb;
                    $a=strlen($norvb);
                }
                if($icoabank==BankBCA){
                    $norvb  ="05-".$th.$bl.$norvb;
                    return $norvb;
                }else{
                    $norvb  = "07-".$th.$bl.$norvb;
                    return $norvb;
                }
            }else{
                if($icoabank==BankBCA){
                    $norvb  ="00001";
                    $norvb  ="05-".$th.$bl.$norvb;
                    return $norvb;
                }else{
                    $norvb  ="00001";
                    $norvb  ="07-".$th.$bl.$norvb;
                    return $norvb;  
                }
            }
        }else{ /*CUTOFF COA 2019*/
            if($periode < "2001"){
                if($icoabank == BankBCA){
                    $icoabank = BankBCA2019; //COA BCA LAMA
                }elseif($icoabank == BankBCA2019){
                    $icoabank = BankBCA2019; //COA BCA LAMA
                }elseif($icoabank == BankBRI2019){
                    $icoabank = BankBRI2019; //COA BCA LAMA
                }else{
                    $icoabank = BankBRI2019; //COA BRI LAMA
                }
            }

            $this->db->select(" max(substr(i_rvb,9,5)) as max 
                from tm_rvb 
                where substr(i_rvb,4,2)='$th' /*and substr(i_rvb,6,2)='$bl'*/ and i_coa_bank='$icoabank'", false);

            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $terakhir=$row->max;
                }
                $norvb  =$terakhir+1;
                settype($norvb,"string");
                $a=strlen($norvb);
                while($a<5){
                    $norvb="0".$norvb;
                    $a=strlen($norvb);
                }
                
                if($icoabank==BankBCA2019){
                    $norvb  ="05-".$th.$bl.$norvb;
                    return $norvb;
                }else{
                    $norvb  = "07-".$th.$bl.$norvb;
                    return $norvb;
                }
            }else{
                if($icoabank==BankBCA2019){
                    $norvb  ="00001";
                    $norvb  ="05-".$th.$bl.$norvb;
                    return $norvb;
                }else{
                    $norvb  ="00001";
                    $norvb  ="07-".$th.$bl.$norvb;
                    return $norvb;  
                }
            }
        }
    }

    public function runningnumberrv($th,$bl,$iarea,$irvtype,$icoabank){
        $periode = $th.$bl;        
        if($periode > "1912"){
            $this->db->select(" max(substr(i_rv,9,5)) as max 
                from tm_rv 
                where 
                substr(i_rv,4,2)='$th' /*and substr(i_rv,6,2)='$bl'*/ and i_area='$iarea'
                and i_rv_type='$irvtype' and i_coa='$icoabank'", false);

            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $terakhir=$row->max;
                }
                $norv  =$terakhir+1;
                settype($norv,"string");
                $a=strlen($norv);
                while($a<5){
                    $norv="0".$norv;
                    $a=strlen($norv);
                }

                if($icoabank==BankBCA){
                    $norv  ="05-".$th.$bl.$norv;
                    return $norv;
                }else{
                    $norv  ="07-".$th.$bl.$norv;
                    return $norv;
                }
            }else{
                if($icoabank==BankBCA){
                    $norv  ="00001";
                    $norv  ="05-".$th.$bl.$norv;
                    return $norv;
                }else{
                    $norv  ="00001";
                    $norv  ="07-".$th.$bl.$norv;
                    return $norv;
                }
            }
        }else{

            if($periode < "2001"){
                if($icoabank == BankBCA){
                    $icoabank = BankBCA2019; /*COA BCA LAMA*/
                }elseif($icoabank == BankBCA2019){
                    $icoabank = BankBCA2019; /*COA BCA LAMA*/
                }elseif($icoabank == BankBRI2019){
                    $icoabank = BankBRI2019; /*COA BCA LAMA*/
                }else{
                    $icoabank = BankBRI2019; /*COA BRI LAMA*/
                }
            }

            $this->db->select(" max(substr(i_rv,9,5)) as max 
                from tm_rv 
                where 
                substr(i_rv,4,2)='$th' /*and substr(i_rv,6,2)='$bl'*/ and i_area='$iarea'
                and i_rv_type='$irvtype' and i_coa='$icoabank'", false);

            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $terakhir=$row->max;
                }
                $norv  =$terakhir+1;
                settype($norv,"string");
                $a=strlen($norv);
                while($a<5){
                    $norv="0".$norv;
                    $a=strlen($norv);
                }
                if($icoabank==BankBCA2019){
                    $norv  ="05-".$th.$bl.$norv;
                    return $norv;
                }else{
                    $norv  ="07-".$th.$bl.$norv;
                    return $norv;
                }
            }else{
                if($icoabank==BankBCA2019){
                    $norv  ="00001";
                    $norv  ="05-".$th.$bl.$norv;
                    return $norv;
                }else{
                    $norv  ="00001";
                    $norv  ="07-".$th.$bl.$norv;
                    return $norv;
                }
            }
        }
    }

    public function runningnumberbank($th,$bl,$iarea,$icoabank){
        $this->db->select(" max(substr(i_kbank,9,5)) as max from tm_kbank 
            where substr(i_kbank,4,2)='$th' /*and substr(i_kbank,6,2)='$bl'*/ and i_coa_bank='$icoabank'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nogj  =$terakhir+1;
            settype($nogj,"string");
            $a=strlen($nogj);
            while($a<5){
                $nogj="0".$nogj;
                $a=strlen($nogj);
            }
            $nogj  ="BM-".$th.$bl."-".$nogj;
            return $nogj;
        }else{
            $nogj  ="00001";
            $nogj  ="BM-".$th.$bl."-".$nogj;
            return $nogj;
        }
    }

    public function insert($iareax,$ikbank,$iperiode,$icoa,$vkb,$dkb,$ecoaname,$edescription,$fdebet,$icoabank,$igiro){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'        => $iareax,
                'i_kbank'       => $ikbank,             
                'i_periode'     => $iperiode,
                'i_coa'         => $icoa,
                'v_bank'        => $vkb,
                'v_sisa'        => $vkb,
                'd_bank'        => $dkb,
                'e_coa_name'    => $ecoaname,
                'e_description' => $edescription,
                'd_entry'       => $dentry,
                'f_debet'       => $fdebet,
                'i_coa_bank'    => $icoabank,
                'i_giro'        => $igiro
            )
        );
        $this->db->insert('tm_kbank');
    }

    public function inserttransheader( $inota,$iarea,$eremark,$fclose,$dkn,$icoabank){
        $dentry  = current_datetime();
        $eremark = str_replace("'","''",$eremark);
        $this->db->query("insert into tm_jurnal_transharian 
                         (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi,i_coa_bank)
                              values
                         ('$inota','$iarea','$dentry','$eremark','$fclose','$dkn','$dkn','$icoabank')");
    }

    public function namaacc($icoa){
        $this->db->select("e_coa_name");
        $this->db->from("tr_coa");
        $this->db->where("i_coa",$icoa);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $tmp){
                $xxx=$tmp->e_coa_name;
            }
            return $xxx;
        }
    }

    public function inserttransitemdebet($accdebet,$ikn,$namadebet,$fdebet,$fposting,$iarea,$eremark,$vjumlah,$dkn,$icoabank){
        $dentry = current_datetime();
        $namadebet=str_replace("'","''",$namadebet);
        $this->db->query("insert into tm_jurnal_transharianitem
           (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry, i_area,i_coa_bank)
           values
           ('$accdebet','$ikn','$namadebet','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry','$iarea','$icoabank')");
    }

    public function updatesaldodebet($accdebet,$iperiode,$vjumlah){        
        $this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet+$vjumlah, v_saldo_akhir=v_saldo_akhir+$vjumlah where i_coa='$accdebet' and i_periode='$iperiode'");
    }

    public function inserttransitemkredit($acckredit,$ikn,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dkn,$icoabank){
        $dentry = current_datetime();
        $namakredit=str_replace("'","''",$namakredit);
        $this->db->query("insert into tm_jurnal_transharianitem
           (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_area,i_coa_bank)
           values
           ('$acckredit','$ikn','$namakredit','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry','$iarea','$icoabank')");
    }

    public function updatesaldokredit($acckredit,$iperiode,$vjumlah){
        $this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vjumlah, v_saldo_akhir=v_saldo_akhir-$vjumlah where i_coa='$acckredit' and i_periode='$iperiode'");
    }

    public function insertgldebet($accdebet,$ikn,$namadebet,$fdebet,$iarea,$vjumlah,$dkn,$eremark,$icoabank){
        $dentry = current_datetime();
        $eremark=str_replace("'","''",$eremark);
        $namadebet=str_replace("'","''",$namadebet);
        $this->db->query("insert into tm_general_ledger
                         (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry,i_coa_bank)
                              values
                         ('$ikn','$accdebet','$dkn','$namadebet','$fdebet',$vjumlah,'$iarea','$dkn','$eremark','$dentry','$icoabank')");
    }

    public function insertglkredit($acckredit,$ikn,$namakredit,$fdebet,$iarea,$vjumlah,$dkn,$eremark,$icoabank){
        $dentry = current_datetime();
        $eremark=str_replace("'","''",$eremark);
        $namakredit=str_replace("'","''",$namakredit);
        $this->db->query("insert into tm_general_ledger
                         (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry,i_coa_bank)
                              values
                         ('$ikn','$acckredit','$dkn','$namakredit','$fdebet','$vjumlah','$iarea','$dkn','$eremark','$dentry','$icoabank')");
    }

    public function insertrvitem($irv,$iarea,$icoa,$ecoaname,$vrv,$edescription,$ikk,$irvtype,$iareax,$icoabank){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'       => $iarea,
                'i_rv'         => $irv,
                'i_coa'        => $icoa,
                'e_coa_name'   => $ecoaname,
                'v_rv'         => $vrv,
                'e_remark'     => $edescription,
                'i_kk'         => $ikk,
                'i_rv_type'    => $irvtype,
                'i_area_kb'    => $iareax,
                'i_coa_bank'   => $icoabank
            )
        );
        $this->db->insert('tm_rv_item');
    }

    public function insertrvb($irvb,$icoabank,$irv,$iarea,$irvtype){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_rvb'       => $irvb,
                'i_coa_bank'  => $icoabank,
                'i_rv'        => $irv,
                'i_area'      => $iarea,
                'i_rv_type'   => $irvtype,
                'd_entry'     => $dentry,
            )
        );
        $this->db->insert('tm_rvb');
    }

    public function insertrv($irv,$iarea,$iperiode,$icoa,$drv,$tot,$eremark,$irvtype){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_rv'        => $irv,
                'i_area'      => $iarea,
                'i_periode'   => $iperiode,
                'i_coa'       => $icoa,
                'd_rv'        => $drv,
                'v_rv'        => $tot,
                'd_entry'     => $dentry,
                'i_rv_type'   => $irvtype
            )
        );
        $this->db->insert('tm_rv');
    }
}

/* End of file Mmaster.php */