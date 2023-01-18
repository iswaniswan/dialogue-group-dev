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
                SUM(x.v_bank) AS v_bank
            FROM
                (
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_rv x,
                    tm_rv_item z,
                    tm_kbank b
                WHERE
                    x.i_rv = z.i_rv
                    AND x.i_area = z.i_area
                    AND x.i_rv_type = z.i_rv_type
                    AND x.i_rv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 't'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode
            UNION ALL
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_pv x,
                    tm_pv_item z,
                    tm_kbank b
                WHERE
                    x.i_pv = z.i_pv
                    AND x.i_area = z.i_area
                    AND x.i_pv_type = z.i_pv_type
                    AND x.i_pv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 't'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode ) AS x
        ",false);
        $query = $this->db->get();
        $kredit=0;
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $kredit=$row->v_bank;
            }
        }
        $this->db->select("
                SUM(x.v_bank) AS v_bank
            FROM
                (
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_rv x,
                    tm_rv_item z,
                    tm_kbank b
                WHERE
                    x.i_rv = z.i_rv
                    AND x.i_area = z.i_area
                    AND x.i_rv_type = z.i_rv_type
                    AND x.i_rv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 'f'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode
            UNION ALL
                SELECT
                    SUM(b.v_bank) AS v_bank
                FROM
                    tm_pv x,
                    tm_pv_item z,
                    tm_kbank b
                WHERE
                    x.i_pv = z.i_pv
                    AND x.i_area = z.i_area
                    AND x.i_pv_type = z.i_pv_type
                    AND x.i_pv_type = '02'
                    AND b.i_periode = '$dtos'
                    AND x.i_area = '$area'
                    AND b.d_bank <= '$tanggal'
                    AND b.f_debet = 'f'
                    AND x.i_coa = '$icoabank'
                    AND b.f_kbank_cancel = 'f'
                    AND z.i_kk = b.i_kbank
                    AND z.i_area_kb = b.i_area
                    AND x.i_periode = b.i_periode ) AS x
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
                OR (UPPER(e_coa_name) LIKE '%$cari%'))
                AND (NOT i_coa LIKE '111-4%')
                OR i_coa IN('120-30000',
                '120-10000',
                '120-20000',
                '120-30000')
                OR i_coa LIKE '%900-%'
            ORDER BY
                i_coa
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
                AND (NOT i_coa LIKE '111-4%')
                OR i_coa IN('120-30000',
                '120-10000',
                '120-20000',
                '120-30000')
                OR i_coa LIKE '%900-%'
            ORDER BY
                i_coa
        ", FALSE);
    }

    public function runningnumberpvb($th,$bl,$icoabank,$iarea){
        $this->db->select(" max(substr(i_pvb,11,6)) as max 
            from tm_pvb 
            where substr(i_pvb,4,2)='$th' 
            and substr(i_pvb,6,2)='$bl' 
            and i_coa_bank='$icoabank'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nopvb  =$terakhir+1;
            settype($nopvb,"string");
            $a=strlen($nopvb);
            while($a<6){
                $nopvb="0".$nopvb;
                $a=strlen($nopvb);
            }
            $nopvb  ="PV-".$th.$bl."-".$iarea.$nopvb;
            return $nopvb;
        }else{
            $nopvb  ="000001";
            $nopvb  ="PV-".$th.$bl."-".$iarea.$nopvb;
            return $nopvb;
        }
    }

    public function runningnumberpv($th,$bl,$iarea,$ipvtype){
        $this->db->select(" max(substr(i_pv,11,6)) as max 
            from tm_pv 
            where substr(i_pv,4,2)='$th' 
            and substr(i_pv,6,2)='$bl' 
            and i_area='$iarea'
            and i_pv_type='$ipvtype'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nopv  =$terakhir+1;
            settype($nopv,"string");
            $a=strlen($nopv);
            while($a<6){
                $nopv="0".$nopv;
                $a=strlen($nopv);
            }
            $nopv  ="PV-".$th.$bl."-".$iarea.$nopv;
            return $nopv;
        }else{
            $nopv  ="000001";
            $nopv  ="PV-".$th.$bl."-".$iarea.$nopv;
            return $nopv;
        }
    }

    public function runningnumberbank($th,$bl,$iarea,$icoabank){
        $this->db->select(" max(substr(i_kbank,9,5)) as max from tm_kbank 
            where substr(i_kbank,4,2)='$th' and substr(i_kbank,6,2)='$bl' and i_coa_bank='$icoabank'", false);
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
            $nogj  ="BK-".$th.$bl."-".$nogj;
            return $nogj;
        }else{
            $nogj  ="00001";
            $nogj  ="BK-".$th.$bl."-".$nogj;
            return $nogj;
        }
    }

    public function insert($iareax,$ikbank,$iperiode,$icoa,$vkb,$dkb,$ecoaname,$edescription,$fdebet,$icoabank){
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
                'i_coa_bank'    => $icoabank
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

    public function insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vpv,$edescription,$ikk,$ipvtype,$iareax,$icoabank){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'     => $iarea,
                'i_pv'       => $ipv,
                'i_coa'      => $icoa,
                'e_coa_name' => $ecoaname,
                'v_pv'       => $vpv,
                'e_remark'   => $edescription,
                'i_kk'       => $ikk,
                'i_pv_type'  => $ipvtype,
                'i_area_kb'  => $iareax,
                'i_coa_bank' => $icoabank
            )
        );
        $this->db->insert('tm_pv_item');
    }

    public function insertpvb($ipvb,$icoabank,$ipv,$iarea,$ipvtype){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_pvb'       => $ipvb,
                'i_coa_bank'  => $icoabank,
                'i_pv'        => $ipv,
                'i_area'      => $iarea,
                'i_pv_type'   => $ipvtype,
                'd_entry'     => $dentry,
            )
        );
        $this->db->insert('tm_pvb');
    }

    public function insertpv($ipv,$iarea,$iperiode,$icoa,$dpv,$tot,$eremark,$ipvtype){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_pv'      => $ipv,
                'i_area'    => $iarea,
                'i_periode' => $iperiode,
                'i_coa'     => $icoa,
                'd_pv'      => $dpv,
                'v_pv'      => $tot,
                'd_entry'   => $dentry,
                'i_pv_type' => $ipvtype
            )
        );
        $this->db->insert('tm_pv');
    }
}

/* End of file Mmaster.php */