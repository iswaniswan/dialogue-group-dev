<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username, $idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE)->result();
    }

    public function cekuser($username, $id_company){
        $this->db->select('i_area');
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

    public function data($dfrom, $dto, $iarea, $folder){
        $icoa = PiutangDagang;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                c.e_bank_name,
                a.i_kbank,
                to_char(a.d_bank, 'dd-mm-yyyy') AS d_bank,
                a.i_area ||' - '||b.e_area_name AS area,
                a.i_giro,
                a.v_bank,
                a.v_sisa,
                a.e_description,
                '$folder' AS folder,
                '$iarea' AS iarea,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_kbank a,
                tr_area b,
                tr_bank c
            WHERE
                a.i_area = b.i_area
                AND a.f_kbank_cancel = 'false'
                AND a.d_bank >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.f_debet = FALSE
                AND a.d_bank <= TO_DATE('$dto', 'dd-mm-yyyy')
                AND a.v_sisa>0
                AND UPPER(a.i_area)= '$iarea'
                AND a.i_coa_bank = c.i_coa
                AND a.i_coa = '$icoa'
            ORDER BY
                a.i_kbank,
                a.d_bank
        ", false);
        $datatables->add('action', function ($data) {
            $ikbank     = trim($data['i_kbank']);
            $folder     = $data['folder'];
            $iarea      = $data['iarea'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/proses/$ikbank/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cekedit($idt, $tgl, $iarea){
        $this->db->select('i_alokasi');
        $this->db->from('tm_alokasi');
        $this->db->where('i_dt', $idt);
        $this->db->where('i_area_dt', $iarea);
        /*$this->db->where('d_dt', $tgl);*/
        $this->db->where('f_alokasi_cancel', 'f');
        return $this->db->get();
    }

    public function baca($ikbank,$iarea,$dfrom,$dto){
        $icoa = PiutangDagang;
        $this->db->select("
                c.e_bank_name,
                a.i_kbank,
                to_char(a.d_bank, 'dd-mm-yyyy') AS d_bank,
                a.i_giro,
                a.i_area,
                a.v_bank,
                b.e_area_name,
                a.v_sisa,
                a.i_coa_bank,
                a.e_description
            FROM
                tm_kbank a,
                tr_area b,
                tr_bank c
            WHERE
                a.i_area = b.i_area
                AND a.f_kbank_cancel = 'false'
                AND a.d_bank >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.f_debet = FALSE
                AND a.d_bank <= TO_DATE('$dto', 'dd-mm-yyyy')
                AND a.v_sisa>0
                AND UPPER(a.i_area)= '$iarea'
                AND a.i_coa_bank = c.i_coa
                AND a.i_coa = '$icoa'
                AND i_kbank = '$ikbank'
            ORDER BY
                a.i_kbank,
                a.d_bank
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function groupbayar($icustomer){
        $this->db->select('i_customer_groupbayar');
        $this->db->from('tr_customer_groupbayar');
        $this->db->where('i_customer',$icustomer);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $group = $row->i_customer_groupbayar;
        }else{
            $group = "";
        }
        return $group;
    }

    public function getdt($cari,$dfrom,$dto){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT DISTINCT 
                a.i_dt,
                to_char(a.d_dt, 'dd-mm-yyyy') AS d_dt,
                a.i_area
            FROM
                tm_dt a,
                tm_dt_item b,
                tm_nota c
            WHERE
                a.i_dt = b.i_dt
                AND a.i_area = b.i_area
                AND a.d_dt = b.d_dt
                AND b.i_nota = c.i_nota
                AND b.i_customer = c.i_customer
                AND b.i_area = c.i_area
                AND c.v_sisa>0
                AND c.f_nota_cancel = 'f'
                AND a.d_dt >= '$dfrom'
                AND a.d_dt <= '$dto'
                AND a.i_area IN(
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND (UPPER(a.i_dt) LIKE '%$cari%')
        ", FALSE);
    }

    public function getcustomer($cari,$iarea,$idt){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT DISTINCT 
                a.i_customer,
                c.e_customer_name
            FROM
                tm_dt_item a
            LEFT JOIN tm_dt b ON
                (a.i_dt = b.i_dt
                AND a.i_area = b.i_area
                AND a.d_dt = b.d_dt
                AND f_dt_cancel = 'f'),
                tr_customer c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_area = '$iarea'
                AND a.i_dt = '$idt'
                AND (UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(c.e_customer_name) LIKE '%$cari%'
                OR UPPER(a.i_dt) LIKE '%$cari%')
        ", FALSE);
    }

    public function getdetailcustomer($icustomer,$iarea,$idt){
        return $this->db->query("
            SELECT DISTINCT 
                a.i_customer,
                c.e_customer_name,
                c.e_customer_address,
                c.e_customer_city
            FROM
                tm_dt_item a
            LEFT JOIN tm_dt b ON
                (a.i_dt = b.i_dt
                AND a.i_area = b.i_area
                AND a.d_dt = b.d_dt
                AND f_dt_cancel = 'f'),
                tr_customer c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_area = '$iarea'
                AND a.i_dt = '$idt'
                AND a.i_customer = '$icustomer'
        ", FALSE);
    }

    public function getnota($cari, $igroup, $idt){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                c.i_nota,
                to_char(c.d_nota, 'dd-mm-yyyy') AS d_nota
            FROM
                tr_customer_groupbayar b,
                tm_nota c
            WHERE
                b.i_customer_groupbayar = '$igroup'
                AND NOT c.i_nota IS NULL
                AND c.f_nota_cancel = 'f'
                AND c.i_nota 
                    IN (SELECT i_nota FROM tm_dt_item WHERE i_dt = '$idt')
                AND c.i_customer = b.i_customer
                AND c.v_sisa>0
                AND (UPPER(c.i_nota) LIKE '%$cari%')
            ORDER BY
                c.i_nota", 
        FALSE);
    }

    public function getdetailnota($inota,$igroup,$idt){
        return $this->db->query("
            SELECT
                c.v_nota_netto AS v_jumlah,
                c.i_customer,
                c.i_nota,
                to_char(c.d_nota, 'dd-mm-yyyy') AS d_nota,
                c.v_sisa
            FROM
                tr_customer_groupbayar b,
                tm_nota c
            WHERE
                b.i_customer_groupbayar = '$igroup'
                AND NOT c.i_nota IS NULL
                AND c.f_nota_cancel = 'f'
                AND c.i_nota 
                    IN (SELECT i_nota FROM tm_dt_item WHERE i_dt = '$idt')
                AND c.i_customer = b.i_customer
                AND c.v_sisa>0
                AND c.i_nota = '$inota'
            ORDER BY
                c.i_nota", 
        FALSE);
    }

    public function runningnumberpl($iarea, $thbl){
        $th   = substr($thbl, 0, 4);
        $asal = $thbl;
        $thbl = substr($thbl, 2, 2) . substr($thbl, 4, 2);
        $this->db->select(" n_modul_no AS max 
            FROM tm_dgu_no
            WHERE i_modul='BAL'
            AND substr(e_periode,1,4)='$th'
            AND i_area='$iarea' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $terakhir = $row->max;
            }
            $noal  = $terakhir + 1;
            $this->db->query(" 
                UPDATE tm_dgu_no
                SET n_modul_no=$noal
                WHERE i_modul='BAL'
                AND substr(e_periode,1,4)='$th'
                AND i_area='$iarea'", false);
            settype($noal, "string");
            $a = strlen($noal);
            while ($a < 5) {
                $noal = "0" . $noal;
                $a = strlen($noal);
            }
            $noal  = "AL-" . $thbl . "-" . $noal;
            return $noal;
        }else{
            $noal  = "00001";
            $noal  = "AL-" . $thbl . "-" . $noal;
            $this->db->query(" 
                INSERT INTO tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('BAL','$iarea','$asal',1)");
            return $noal;
        }
    }

    public function inserttransheader($ipelunasan, $iarea, $egirodescription, $fclose, $dbukti){
        $dentry   = current_datetime();
        $egirodescription = str_replace("'", "''", $egirodescription);
        $this->db->query("
            INSERT INTO tm_jurnal_transharian 
            (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
            VALUES 
            ('$ipelunasan','$iarea','$dentry','$egirodescription','$fclose','$dbukti','$dbukti')"
        );
    }

    public function namaacc($icoa){
        $this->db->select("e_coa_name"); 
        $this->db->from("tr_coa"); 
        $this->db->where("i_coa",$icoa);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $tmp) {
                $xxx = $tmp->e_coa_name;
            }
            return $xxx;
        }
    }

    public function carisaldo($icoa, $iperiode){
        $this->db->select('*');
        $this->db->from('tm_coa_saldo');
        $this->db->where('i_coa', $icoa);
        $this->db->where('i_periode', $iperiode);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        }
    }

    public function inserttransitemdebet($accdebet, $ipelunasan, $namadebet, $fdebet, $fposting, $iarea, $egirodescription, $vjumlah, $dbukti, $icoabank){
        $dentry   = current_datetime();
        $this->db->query("
            INSERT INTO tm_jurnal_transharianitem 
            (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry, i_coa_bank)
            VALUES 
            ('$accdebet','$ipelunasan','$namadebet','$fdebet','$fposting','$vjumlah','$dbukti','$dbukti','$dentry','$icoabank')
        ");
    }

    public function updatesaldodebet($accdebet, $iperiode, $vjumlah){
        $this->db->query("
            UPDATE tm_coa_saldo 
            SET v_mutasi_debet = v_mutasi_debet+$vjumlah, 
            v_saldo_akhir = v_saldo_akhir + $vjumlah
            WHERE i_coa='$accdebet' AND i_periode='$iperiode'
        ");
    }

    public function inserttransitemkredit($acckredit, $ipelunasan, $namakredit, $fdebet, $fposting, $iarea, $egirodescription, $vjumlah, $dbukti, $icoabank){
        $dentry   = current_datetime();
        $this->db->query("
            INSERT INTO tm_jurnal_transharianitem 
            (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_coa_bank)
            VALUES 
            ('$acckredit','$ipelunasan','$namakredit','$fdebet','$fposting','$vjumlah','$dbukti','$dbukti','$dentry','$icoabank')
        ");
    }

    public function updatesaldokredit($acckredit, $iperiode, $vjumlah){
        $this->db->query("
            UPDATE tm_coa_saldo 
            SET v_mutasi_kredit = v_mutasi_kredit+$vjumlah, 
            v_saldo_akhir = v_saldo_akhir-$vjumlah
            WHERE i_coa='$acckredit' AND i_periode='$iperiode'
        ");
    }

    public function insertgldebet($accdebet, $ipelunasan, $namadebet, $fdebet, $iarea, $vjumlah, $dbukti, $egirodescription,$icoabank){
        $dentry   = current_datetime();
        $egirodescription = str_replace("'", "''", $egirodescription);
        $this->db->query("
            INSERT INTO tm_general_ledger 
            (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry,i_coa_bank)
            VALUES 
            ('$ipelunasan','$accdebet','$dbukti','$namadebet','$fdebet',$vjumlah,'$iarea','$dbukti','$egirodescription','$dentry','$icoabank')
        ");
    }

    public function insertglkredit($acckredit, $ipelunasan, $namakredit, $fdebet, $iarea, $vjumlah, $dbukti, $egirodescription,$icoabank){
        $dentry   = current_datetime();
        $egirodescription = str_replace("'", "''", $egirodescription);
        $this->db->query("
            INSERT INTO tm_general_ledger
            (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry,i_coa_bank)
            VALUES
            ('$ipelunasan','$acckredit','$dbukti','$namakredit','$fdebet','$vjumlah','$iarea','$dbukti','$egirodescription','$dentry','$icoabank')
        ");
    }

    public function insertheader($ialokasi, $ikbank, $iarea, $icustomer, $dbank, $dalokasi, $ebankname, $vjumlah, $vlebih, $icoabank, $igiro, $idt, $areadt){
        $dentry   = current_datetime();
        $this->db->query("
            INSERT INTO tm_alokasi 
            (i_alokasi,i_kbank,i_area,i_customer,d_alokasi,e_bank_name,v_jumlah,v_lebih,d_entry,i_coa_bank,i_giro,i_dt,i_area_dt)
            VALUES
            ('$ialokasi','$ikbank','$iarea','$icustomer','$dalokasi','$ebankname',$vjumlah,$vlebih,'$dentry','$icoabank','$igiro','$idt', '$areadt')
        ");
    }

    public function updatesaldo($group, $icustomer, $pengurang){
        $this->db->query("
            UPDATE tr_customer_groupar 
            SET v_saldo = v_saldo - $pengurang 
            WHERE i_customer = '$icustomer' 
            AND i_customer_groupar = '$group'
        ");
    }

    public function updatebank($ikbank, $icoabank, $iarea, $pengurang){
        $this->db->select('v_sisa');
        $this->db->from('tm_kbank');
        $this->db->where('i_kbank',$ikbank);
        $this->db->where('i_coa_bank',$icoabank);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $xx) {
                $sisa = $xx->v_sisa - $pengurang;
                if ($sisa < 0) {
                    return false;
                    break;
                } else {
                    $this->db->query("
                        UPDATE tm_kbank 
                        SET v_sisa=v_sisa-$pengurang 
                        WHERE i_kbank='$ikbank' 
                        AND i_coa_bank='$icoabank'
                    ");
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public function updatekn($group, $iarea, $igiro, $pengurang, $asal){
        $this->db->select("v_sisa");
        $this->db->from("tm_kn");
        $this->db->where("i_kn",$igiro);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $xx) {
                $sisa = $xx->v_sisa - $pengurang;
                if ($sisa < 0) {
                    return false;
                    break;
                } else {
                    $this->db->query("
                        UPDATE tm_kn SET v_sisa = v_sisa - $pengurang + $asal
                        WHERE i_kn = '$igiro' 
                        AND (i_area ='$iarea' 
                            OR i_customer 
                            IN(SELECT i_customer 
                                FROM tr_customer_groupbayar
                                WHERE i_customer_groupbayar='$group'
                                )
                            )"
                    );
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public function insertdetail($ialokasi, $ikbank, $iarea, $inota, $dnota, $vjumlah, $vsisa, $i, $eremark, $icoabank){
        $this->db->select('i_alokasi');
        $this->db->from('tm_alokasi_item');
        $this->db->where('i_alokasi',$ialokasi);
        $this->db->where('i_area',$iarea);
        $this->db->where('i_nota',$inota);
        $this->db->where('i_kbank',$ikbank);
        $this->db->where('i_coa_bank',$icoabank);
        $tmp = $this->db->get();
        if ($tmp->num_rows() > 0) {
            $this->db->query("
                UPDATE tm_alokasi_item 
                SET d_nota='$dnota',
                v_jumlah=$vjumlah,
                v_sisa=$vsisa,
                n_item_no=$i,
                e_remark='$eremark'
                WHERE i_alokasi='$ialokasi' 
                AND i_area='$iarea' 
                AND i_nota='$inota' 
                AND i_kbank='$ikbank' 
                AND i_coa_bank='$icoabank'");
        } else {
            $this->db->query("
                INSERT INTO tm_alokasi_item
                ( i_alokasi,i_kbank,i_area,i_nota,d_nota,v_jumlah,v_sisa,n_item_no,e_remark,i_coa_bank)
                VALUES
                ('$ialokasi','$ikbank','$iarea','$inota','$dnota',$vjumlah,$vsisa,$i,'$eremark','$icoabank')
            ");
        }
    }

    public function updatenota($inota, $vsisa){
        $this->db->select("v_sisa");
        $this->db->from("tm_nota");
        $this->db->where("i_nota",$inota);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $xx) {
                $sisa = $xx->v_sisa - $vsisa;
                if ($sisa < 0) {
                    return false;
                    break;
                } else {
                    $this->db->query("
                        UPDATE tm_nota 
                        SET v_sisa = v_sisa - $vsisa 
                        WHERE i_nota='$inota'
                        ");
                    return true;
                }
            }
        } else {
            return false;
        }
    }
}

/* End of file Mmaster.php */