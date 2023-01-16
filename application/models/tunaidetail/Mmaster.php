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
            $area = '00';
        }else{
            $area = 'xx';
        }
        return $area;
    }

    public function bacaarea($iarea, $username, $idcompany){
        if ($iarea=='00') {
            return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
        }else{
            $this->db->select(" i_area, e_area_name FROM tr_area WHERE i_area IN (SELECT i_area FROM public.tm_user_area WHERE username = '$username' AND id_company = '$idcompany') ORDER BY i_area", false);
            return $this->db->get()->result();
        }
    }

    public function getcustomer($cari, $iarea, $per){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT 
                a.i_customer,
                a.e_customer_name
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_customer_salesman c ON
                (a.i_customer = c.i_customer
                AND a.i_area = c.i_area
                AND c.e_periode = '$per')
            LEFT JOIN tr_customer_owner d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND (UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(a.e_customer_name) LIKE '%$cari%'
                OR UPPER(c.e_salesman_name) LIKE '%$cari%')
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function getdetailcus($icustomer, $per, $iarea){
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer,
                a.e_customer_name,
                c.e_salesman_name) a.*,
                b.i_customer_groupar,
                c.e_salesman_name,
                c.i_salesman,
                d.e_customer_setor
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_customer_salesman c ON
                (a.i_customer = c.i_customer
                AND a.i_area = c.i_area
                AND c.e_periode = '$per')
            LEFT JOIN tr_customer_owner d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function getsalesman($cari,$iarea,$per){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT a.i_salesman,
                    a.e_salesman_name
                FROM
                    tr_customer_salesman a,
                    tr_salesman b
                WHERE
                    (UPPER(a.e_salesman_name) LIKE '%$cari%'
                    OR UPPER(a.i_salesman) LIKE '%$cari%')
                    AND a.i_area = '$iarea'
                    AND a.i_salesman = b.i_salesman
                    AND b.f_salesman_aktif = 'true'
                    AND a.e_periode = '$per'", 
        FALSE);
    }

    public function nota($cari,$iarea,$icustomer,$dtunaix){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_nota,
                to_char(a.d_nota, 'dd-mm-yyyy') AS d_nota
            FROM
                tm_nota a
            LEFT JOIN tr_area b
                    USING(i_area)
            LEFT JOIN tr_customer c
                    USING (i_customer)
            LEFT JOIN tr_customer_groupbayar d ON
                a.i_customer = d.i_customer
            WHERE
                a.d_nota <= '$dtunaix'
                AND a.v_sisa IS NOT NULL
                AND a.i_customer = '$icustomer'
                AND a.i_area = '$iarea'
                AND a.f_nota_cancel = 'f'
                AND (UPPER(a.i_nota) LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%')
        ", FALSE);
    }

    public function getdetailnota($inota,$iarea,$icustomer,$dtunaix){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.d_nota,
                to_char(a.d_nota, 'dd-mm-yyyy') AS dnota,
                a.v_sisa,
                a.i_area,
                b.e_area_name,
                a.i_customer,
                c.e_customer_name,
                a.e_remark
            FROM
                tm_nota a
            LEFT JOIN tr_area b
                    USING(i_area)
            LEFT JOIN tr_customer c
                    USING (i_customer)
            LEFT JOIN tr_customer_groupbayar d ON
                a.i_customer = d.i_customer
            WHERE
                a.d_nota <= '$dtunaix'
                AND a.v_sisa IS NOT NULL
                AND a.i_customer = '$icustomer'
                AND a.i_area = '$iarea'
                AND a.f_nota_cancel = 'f'
                AND a.i_nota = '$inota'
        ", FALSE);
    }

    public function runningnumber($iarea,$thbl){
        $th   = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no as max 
            from tm_dgu_no
            where i_modul='TN'
            and substr(e_periode,1,4)='$th'
            and i_area='$iarea' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $notn  =$terakhir+1;
            $this->db->query(" update tm_dgu_no
                set n_modul_no=$notn
                where i_modul='TN'
                and substr(e_periode,1,4)='$th'
                and i_area='$iarea'", false);
            settype($notn,"string");
            $a=strlen($notn);
            while($a<6){
                $notn="0".$notn;
                $a=strlen($notn);
            }
            $notn  ="TN-".$thbl."-".$notn;
            return $notn;
        }else{
            $notn  ="000001";
            $notn  ="TN-".$thbl."-".$notn;
            $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
              values ('TN','$iarea','$asal',1)");
            return $notn;
        }
    }

    public function insert($itunai,$dtunai,$iarea,$icustomer,$icustomergroupar,$isalesman,$eremark,$vjumlah,$vsisa,$lebihbayar){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'            => $iarea,
                'i_tunai'           => $itunai,
                'i_customer'        => $icustomer,
                'i_customer_groupar'=> $icustomergroupar,
                'i_salesman'        => $isalesman,
                'd_tunai'           => $dtunai,
                'd_entry'           => $dentry,
                'e_remark'          => $eremark,
                'v_jumlah'          => $vjumlah,
                'v_sisa'            => $vsisa,
                'f_lebihbayar'      => $lebihbayar
            )
        );
        $this->db->insert('tm_tunai');
    }

    public function insertdetail($itunai,$iarea,$inota,$vjumlah,$i){
        $this->db->set(
            array(
                'i_tunai'   => $itunai,
                'i_area'    => $iarea,
                'i_nota'    => $inota,
                'v_jumlah'  => $vjumlah,
                'n_item_no' => $i
            )
        );
        $this->db->insert('tm_tunai_item');
    }
}

/* End of file Mmaster.php */