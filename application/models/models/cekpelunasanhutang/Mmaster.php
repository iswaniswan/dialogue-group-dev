<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getsupplier($cari){
        $cari = str_replace("'", "", $cari);
        return  $this->db->query("
            SELECT
                i_supplier,
                e_supplier_name
            FROM
                tr_supplier
            WHERE
                i_supplier LIKE '%$cari%' ESCAPE '!'
                OR UPPER(e_supplier_name) LIKE '%$cari%' ESCAPE '!' 
        ", FALSE);
    }

    public function data($dfrom, $dto, $isupplier, $folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_pelunasanap,
                TO_CHAR(d_bukti, 'dd-mm-yyyy') AS d_bukti,
                e_jenis_bayarname,
                '(' || b.i_supplier || ') - ' || e_supplier_name AS supplier,
                v_jumlah-v_lebih AS jumlah,
                v_lebih,
                CASE WHEN f_posting = FALSE THEN 'N'
                ELSE 'Y' END AS posting,
                CASE WHEN d_cek IS NULL THEN 'Belum' END AS cek,
                CASE WHEN f_pelunasanap_cancel = FALSE THEN 'Tidak'
                ELSE 'Ya' END AS status_cancel,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$isupplier' AS isupplier,
                '$folder' AS folder
            FROM
                tm_pelunasanap a,
                tr_supplier b,
                tr_jenis_bayar c
            WHERE
                a.i_supplier = b.i_supplier
                AND a.i_jenis_bayar = c.i_jenis_bayar
                AND a.i_supplier = '$isupplier'
                AND a.f_pelunasanap_cancel = 'f'
                AND a.d_bukti >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_bukti <= TO_DATE('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.d_bukti,
                a.i_supplier,
                a.i_pelunasanap"
        , FALSE);
        $datatables->add('action', function ($data) {
            $ipelunasanap = trim($data['i_pelunasanap']);
            $folder       = $data['folder'];
            $isupplier    = $data['isupplier'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $data         = '';
            $data        .= "<a href=\"#\" onclick='show(\"$folder/cform/cek/$ipelunasanap/$isupplier/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('isupplier');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function sisa($isupplier,$ipl){
        $this->db->select("
                SUM(v_sisa)AS sisa
            FROM
                tm_dtap
            WHERE
                i_supplier = '$isupplier'
                AND i_dtap = '$ipl'
        ",FALSE);
        $query = $this->db->get();
        foreach($query->result() as $isi){          
            return $isi->sisa;
        }   
    }

    public function bacapl($isupplier,$ipl){
        $this->db->select("
                a.*,
                b.e_supplier_name,
                c.e_jenis_bayarname,
                b.e_supplier_address,
                b.e_supplier_city,
                d.d_giro_duedate AS d_giro_jt,
                d.d_giro_cair
            FROM
                tm_pelunasanap a
            INNER JOIN tr_supplier b ON
                (a.i_supplier = b.i_supplier)
            INNER JOIN tr_jenis_bayar c ON
                (a.i_jenis_bayar = c.i_jenis_bayar)
            LEFT JOIN tm_giro_dgu d ON
                (a.i_giro = d.i_giro
                AND a.i_supplier = d.i_supplier)
            WHERE
                UPPER(a.i_pelunasanap)= '$ipl'
                AND UPPER(a.i_supplier)= '$isupplier'
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }   
    }

    public function bacadetailpl($isupplier,$ipl){
        $this->db->select("
                a.*,
                b.v_sisa AS v_sisa_nota,
                b.v_netto AS v_nota
            FROM
                tm_pelunasanap_item a
            INNER JOIN tm_dtap b ON
                (a.i_dtap = b.i_dtap)
            WHERE
                a.i_pelunasanap = '$ipl'
                AND b.i_supplier = '$isupplier'
            ORDER BY
                a.i_pelunasanap,
                b.i_supplier
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        } 
    }

    public function updatecek($ecek,$user,$ipl,$isupplier){
        $dentry = current_datetime();
        $data = array(
            'e_cek'     => $ecek,
            'd_cek'     => $dentry,
            'i_cek'     => $user
        );
        $this->db->where('i_pelunasanap', $ipl);
        $this->db->where('i_supplier', $isupplier);
        $this->db->update('tm_pelunasanap', $data);
    }


    /*-----------------| Posting |-------------------*/
    public function namaacc($icoa){
        $this->db->select(" e_coa_name from tr_coa where i_coa='$icoa' ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $tmp){
                $xxx=$tmp->e_coa_name;
            }
            return $xxx;
        }
    }

    public function carisaldo($icoa,$iperiode){
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_coa_saldo
            WHERE
                i_coa = '$icoa'
                AND i_periode = '$iperiode'
        ", FALSE);
        if ($query->num_rows() > 0){
            $row = $query->row();
            return $row;
        }   
    }

    public function inserttransheader( $ipelunasan,$iarea,$egirodescription,$fclose,$dbukti){
        $dentry = current_datetime();
        $this->db->query("
            INSERT
                INTO
                tm_jurnal_transharian (i_refference,
                i_area,
                d_entry,
                e_description,
                f_close,
                d_refference,
                d_mutasi)
            VALUES ('$ipelunasan',
            '$iarea',
            '$dentry',
            '$egirodescription',
            '$fclose',
            '$dbukti',
            '$dbukti')
        ");
    }

    public function inserttransitemdebet($accdebet,$ipelunasan,$namadebet,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dbukti){
        $dentry = current_datetime();
        $this->db->query("
            INSERT
                INTO
                tm_jurnal_transharianitem (i_coa,
                i_refference,
                e_coa_description,
                f_debet,
                f_posting,
                v_mutasi_debet,
                d_refference,
                d_mutasi,
                d_entry)
            VALUES ('$accdebet',
            '$ipelunasan',
            '$namadebet',
            '$fdebet',
            '$fposting',
            '$vjumlah',
            '$dbukti',
            '$dbukti',
            '$dentry')
        ");
    }

    public function inserttransitemkredit($acckredit,$ipelunasan,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dbukti){
        $dentry = current_datetime();
        $this->db->query("
            INSERT
                INTO
                tm_jurnal_transharianitem (i_coa,
                i_refference,
                e_coa_description,
                f_debet,
                f_posting,
                v_mutasi_kredit,
                d_refference,
                d_mutasi,
                d_entry)
            VALUES ('$acckredit',
            '$ipelunasan',
            '$namakredit',
            '$fdebet',
            '$fposting',
            '$vjumlah',
            '$dbukti',
            '$dbukti',
            '$dentry')
        ");
    }
    public function insertgldebet($accdebet,$ipelunasan,$namadebet,$fdebet,$iarea,$vjumlah,$dbukti,$egirodescription){
        $dentry = current_datetime();
        $this->db->query("
            INSERT
                INTO
                tm_general_ledger (i_refference,
                i_coa,
                d_mutasi,
                e_coa_name,
                f_debet,
                v_mutasi_debet,
                i_area,
                d_refference,
                e_description,
                d_entry)
            VALUES ('$ipelunasan',
            '$accdebet',
            '$dbukti',
            '$namadebet',
            '$fdebet',
            $vjumlah,
            '$iarea',
            '$dbukti',
            '$egirodescription',
            '$dentry')
        ");
    }

    public function insertglkredit($acckredit,$ipelunasan,$namakredit,$fdebet,$iarea,$vjumlah,$dbukti,$egirodescription){
        $dentry = current_datetime();
        $this->db->query("
            INSERT
                INTO
                tm_general_ledger (i_refference,
                i_coa,
                d_mutasi,
                e_coa_name,
                f_debet,
                v_mutasi_kredit,
                i_area,
                d_refference,
                e_description,
                d_entry)
            VALUES ('$ipelunasan',
            '$acckredit',
            '$dbukti',
            '$namakredit',
            '$fdebet',
            '$vjumlah',
            '$iarea',
            '$dbukti',
            '$egirodescription',
            '$dentry')
        ");
    }

    public function updatepelunasan($ipl,$iarea,$dbukti){
        $this->db->query("
            UPDATE
                tm_pelunasanap
            SET
                f_posting = 't'
            WHERE
                i_pelunasanap = '$ipl'
                AND i_area = '$iarea'
                AND d_bukti = '$dbukti'
        ");
    }

    public function updatesaldodebet($accdebet,$iperiode,$vjumlah){
        $this->db->query("
            UPDATE
                tm_coa_saldo
            SET
                v_mutasi_debet = v_mutasi_debet + $vjumlah,
                v_saldo_akhir = v_saldo_akhir + $vjumlah
            WHERE
                i_coa = '$accdebet'
                AND i_periode = '$iperiode'
        ");
    }

    public function updatesaldokredit($acckredit,$iperiode,$vjumlah){
        $this->db->query("
            UPDATE
                tm_coa_saldo
            SET
                v_mutasi_kredit = v_mutasi_kredit + $vjumlah,
                v_saldo_akhir = v_saldo_akhir-$vjumlah
            WHERE
                i_coa = '$acckredit'
                AND i_periode = '$iperiode'
        ");
    }
    /*---------------| End Posting |-----------------*/
}

/* End of file Mmaster.php */
