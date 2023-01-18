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

    // public function data($i_menu, $from, $to){
    //     $icoa = PiutangDagang;
    //     $datatables = new Datatables(new CodeigniterAdapter);
    //     $datatables->query("SELECT b.e_bank_name, a.i_kas_masuk, to_char(a.d_kas_masuk, 'dd-mm-yyyy') AS d_bank, 
    //     c.n_nilai, c.n_nilai- n_pakai as sisa,'$i_menu' as i_menu
    //     from tm_kas_masuk a
    //     inner join tr_bank b on a.i_bank = b.i_bank
    //     inner join tm_kas_masuk_detail c on a.i_kas_masuk = c.i_kas_masuk-- and a.i_customer = c.i_customer
    //     where a.i_status = '6' 
    //     and c.n_nilai- n_pakai > 0 
    //     and a.d_kas_masuk >= '$from' 
    //     and a.d_kas_masuk <= '$to'
    //     ", false);
    //     $datatables->add('action', function ($data) {
    //         $ikbank     = trim($data['i_kas_masuk']);
    //         // $folder     = $data['folder'];
    //         // $iarea      = $data['iarea'];
    //         $dfrom      = $data['dfrom'];
    //         $dto        = $data['dto'];
    //         $data       = '';
    //         $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/proses/$ikbank/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
    //         return $data;
    //     });
    //     // $datatables->hide('folder');
    //     $datatables->hide('i_menu');
    //     $datatables->hide('dfrom');
    //     $datatables->hide('dto');
    //     return $datatables->generate();
    // }

    function data($i_menu, $from,$to){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("SELECT b.e_bank_name, a.i_kas_masuk, to_char(a.d_kas_masuk, 'dd-mm-yyyy') AS d_bank, 
                            c.n_nilai, c.n_nilai- n_pakai as sisa,'$i_menu' as i_menu,'$from' AS dfrom,'$to' AS dto
                            from tm_kas_masuk a
                            inner join tr_bank b on a.i_bank = b.i_bank
                            inner join tm_kas_masuk_detail c on a.i_kas_masuk = c.i_kas_masuk
                            where a.i_status = '6' 
                            and c.n_nilai - n_pakai > 0 
		 					and a.d_kas_masuk >= to_date('$from','dd-mm-yyyy')
		 					and a.d_kas_masuk <= to_date('$to','dd-mm-yyyy')",false);
		$datatables->add('action', function ($data) {
			$ikbank     = trim($data['i_kas_masuk']);
			$from      = $data['dfrom'];
            $to        = $data['dto'];
			$i_menu     = $data['i_menu'];
			$data       = '';
			if(check_role($i_menu, 3)){
				$data .= "<a href=\"#\" onclick='show(\"ar-alokasikasbankmasuk/cform/proses/$ikbank/$from/$to\",\"#main\"); return false;'>&nbsp;&nbsp;<i class='fa fa-pencil'></i></a>";
			}
			return $data;
		});
		$datatables->hide('i_menu');
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

    public function baca($ikbank,$dfrom,$dto){
        // $icoa = PiutangDagang;
        $this->db->select(" b.e_bank_name, a.i_kas_masuk, to_char(a.d_kas_masuk, 'dd-mm-yyyy') AS d_bank, a.i_bank, b.i_coa,
        c.n_nilai, c.n_nilai- n_pakai as sisa
        from tm_kas_masuk a
        inner join tr_bank b on a.i_bank = b.i_bank
        inner join tm_kas_masuk_detail c on a.i_kas_masuk = c.i_kas_masuk
        where a.i_status = '6' 
        and c.n_nilai- n_pakai > 0 
         and a.d_kas_masuk >= to_date('$dfrom','dd-mm-yyyy')
         and a.d_kas_masuk <= to_date('$dto','dd-mm-yyyy') and a.i_kas_masuk = '$ikbank'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    function bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany){
        // if ($username != 'admin') {
            $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
            // where username = 'mimin' and a.i_departement = '1' and a.i_level = '1'
        // } 
        // else {
        //     $where = "";
        // }
        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
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

    public function getcustomer($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT * FROM tr_customer
            WHERE
                (UPPER(i_customer) LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%')
        ", FALSE);
    }

    public function getdetailcustomer($icustomer){
        return $this->db->query("
            SELECT * from tr_customer
            WHERE i_customer = '$icustomer'
        ", FALSE);
    }

    public function getnota($cari,$icustomer){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT i_faktur_code, d_faktur from tm_faktur_do_t where i_customer = '$icustomer'
                AND f_faktur_cancel = 'f'
                AND v_sisa_alo>0
                AND (UPPER(i_faktur_code) LIKE '%$cari%')
            ORDER BY
                i_faktur_code", 
        FALSE);
    }

    public function getdetailnota($inota,$icustomer){
        return $this->db->query("SELECT i_faktur_code as i_nota, d_faktur as d_nota, v_total_faktur, v_sisa_alo as v_sisa 
                                FROM tm_faktur_do_t 
                                WHERE i_faktur_code = '$inota' 
                                AND i_customer = '$icustomer'
                                ORDER BY i_faktur_code",
                                FALSE);
    }

    public function runningnumberpl($iloc, $thbl){
        $th	= substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
            $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='AL'
                            and i_area='$iloc'
                            and e_periode='$asal' 
                            and substring(e_periode,1,4)='$th' for update", false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                  $terakhir=$row->max;
                }
                $nobonmk  =$terakhir+1;
                $this->db->query(" update tm_dgu_no 
                                  set n_modul_no=$nobonmk
                                  where i_modul='AL'
                                  and e_periode='$asal' 
                                  and i_area='$iloc'
                                  and substring(e_periode,1,4)='$th'", false);
                settype($nobonmk,"string");
                $a=strlen($nobonmk);
                while($a<5){
                  $nobonmk="0".$nobonmk;
                  $a=strlen($nobonmk);
                }
                    $nobonmk  ="AL-".$iloc."-".$thbl."-".$nobonmk;
                return $nobonmk;
            }else{
                $nobonmk  ="00001";
                $nobonmk  ="AL-".$iloc."-".$thbl."-".$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('AL','$iloc','$asal',1)");
                return $nobonmk;
            }
    }

    public function inserttransheader($icoabang, $ipelunasan, $iarea, $egirodescription, $fclose, $dbukti){
                    // inserttransheader($ireff, $iarea, $egirodescription, $fclose, $dalokasi);
        $dentry   = current_datetime();
        $egirodescription = str_replace("'", "''", $egirodescription);
        $this->db->query("
            INSERT INTO tm_jurnal_transharian 
            (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi,i_coa_bank)
            VALUES 
            ('$ipelunasan','$iarea','$dentry','$egirodescription','$fclose','$dbukti','$dbukti','$icoabang')"
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

    public function insertheader($ialokasi, $ikbank, $iarea, $icustomer, $dbank, $dalokasi, $ebankname, $vjumlah, $vlebih, $icoabank,$dept){
        $dentry   = current_datetime();
        $this->db->query("
            INSERT INTO tm_alokasi 
            (i_alokasi,i_kbank,i_area,i_customer,d_alokasi,e_bank_name,v_jumlah,v_lebih,d_entry,i_coa_bank,i_bagian)
            VALUES
            ('$ialokasi','$ikbank','$iarea','$icustomer','$dalokasi','$ebankname',$vjumlah,$vlebih,'$dentry','$icoabank','$dept')
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
        $this->db->select('a.n_nilai - a.n_pakai as v_sisa',false);
        $this->db->from('tm_kas_masuk_detail a');
        $this->db->join('tm_kas_masuk b','a.i_kas_masuk = b.i_kas_masuk');
        $this->db->where('a.i_kas_masuk',$ikbank);
        $this->db->where('b.i_bank',$icoabank);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $xx) {
                $sisa = $xx->v_sisa - $pengurang;
                if ($sisa < 0) {
                    return false;
                    break;
                } else {
                    $this->db->query("UPDATE tm_kas_masuk_detail 
                        SET n_pakai=$pengurang 
                        WHERE i_kas_masuk='$ikbank' ");

                    $this->db->query("UPDATE tm_kas_masuk 
                        SET f_alokasi_ar ='t' 
                        WHERE i_kas_masuk='$ikbank' ");
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
        $this->db->select("v_sisa_alo as v_sisa",false);
        $this->db->from("tm_faktur_do_t");
        $this->db->where("i_faktur_code",$inota);
        // select v_sisa_alo from tm_faktur_do_t where i_faktur_code = 'FP-2004-000001'
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $xx) {
                $sisa = $xx->v_sisa - $vsisa;
                if ($sisa < 0) {
                    return false;
                    break;
                } else {
                    $this->db->query("
                        UPDATE tm_faktur_do_t 
                        SET v_sisa_alo = v_sisa_alo - $vsisa, 
                        v_grand_sisa = v_grand_sisa - $vsisa
                        WHERE i_faktur_code = '$inota'
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