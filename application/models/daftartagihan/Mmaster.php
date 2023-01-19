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

    public function getnota($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_nota,
                b.e_customer_name
            FROM
                tm_nota a,
                tr_customer b,
                tr_customer_groupbayar c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_customer = b.i_customer
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_cancel = 'f'
                AND a.v_sisa>0
                AND NOT (a.i_nota ISNULL
                OR TRIM(a.i_nota)= '')
                AND ( (c.i_customer_groupbayar IN(
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    SUBSTRING(i_customer, 1, 2)= '$iarea')) )
                AND (UPPER(a.i_nota) LIKE '%$cari%'
                OR a.i_nota_old LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%')
            GROUP BY
                a.i_nota,
                a.i_area,
                a.d_nota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                b.e_customer_city
            ORDER BY
                a.i_customer,
                a.i_nota", 
        FALSE);
    }

    public function getdetailnota($inota,$iarea){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.i_area,
                a.d_nota,
                to_char(a.d_nota, 'dd-mm-yyyy') AS dnota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') AS djtp,
                b.e_customer_city
            FROM
                tm_nota a,
                tr_customer b,
                tr_customer_groupbayar c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_customer = b.i_customer
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_cancel = 'f'
                AND a.v_sisa>0
                AND NOT (a.i_nota ISNULL
                OR TRIM(a.i_nota)= '')
                AND ( (c.i_customer_groupbayar IN(
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    SUBSTRING(i_customer, 1, 2)= '$iarea')) )
                AND a.i_nota = '$inota'
            GROUP BY
                a.i_nota,
                a.i_area,
                a.d_nota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                b.e_customer_city
            ORDER BY
                a.i_customer,
                a.i_nota", 
        FALSE);
    }

    public function cekdt($idt, $iarea){
        $this->db->select('*');
        $this->db->from('tm_dt');
        $this->db->where('i_dt',$idt);
        $this->db->where('i_area',$iarea);
        return $this->db->get();
    }

    public function runningnumberdt($iarea,$thbl){
        $th     = substr($thbl,0,4);
        $asal   = $thbl;
        $thn    = substr($thbl,2,2);
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" 
            n_modul_no as max 
            FROM tm_dgu_no 
            WHERE i_modul='DT'
            AND substr(e_periode,1,4)='$th' 
            AND i_area='$iarea' for update
            ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nodt  =$terakhir+1;
            $this->db->query(" 
                UPDATE tm_dgu_no 
                SET n_modul_no = $nodt
                WHERE i_modul = 'DT'
                AND substr(e_periode,1,4)='$th' 
                AND i_area='$iarea'
                ", false);
            settype($nodt,"string");
            $a=strlen($nodt);
            while($a<4){
                $nodt="0".$nodt;
                $a=strlen($nodt);
            }
            $nodt  =$nodt."-".$thn;
            return $nodt;
        }else{
            $nodt  ="0001";
            $nodt  =$nodt."-".$thn;
            $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
               values ('DT','$iarea','$asal',1)");
            return $nodt;
        }
    }

    public function insertheader($idt,$iarea,$ddt,$vjumlah,$fsisa){
        $entry = current_datetime();
        $this->db->set(
            array(
                'i_dt'      => $idt,
                'i_area'    => $iarea,
                'd_dt'      => $ddt,
                'v_jumlah'  => $vjumlah,
                'f_sisa'    => $fsisa,
                'd_entry'   => $entry
            )
        );        
        $this->db->insert('tm_dt');
    }

    public function insertdetail($idt,$ddt,$inota,$iarea,$dnota,$icustomer,$vsisa,$vjumlah,$i){
        $this->db->set(
            array(
                'i_dt'          => $idt,
                'd_dt'          => $ddt,
                'i_nota'        => $inota,
                'i_area'        => $iarea,
                'd_nota'        => $dnota,
                'i_customer'    => $icustomer,
                'v_sisa'        => $vsisa,
                'v_jumlah'      => $vjumlah,
                'n_item_no'     => $i
            )
        );
        $this->db->insert('tm_dt_item');
    }
}

/* End of file Mmaster.php */