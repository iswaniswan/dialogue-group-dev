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

    public function bacabank(){
        return $this->db->order_by('i_bank','ASC')->get('tr_bank')->result();
    }

    public function bacaarea($iarea, $username, $idcompany){
        if ($iarea=='00') {
            return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
        }else{
            $this->db->select(" i_area, e_area_name FROM tr_area WHERE i_area IN (SELECT i_area FROM public.tm_user_area WHERE username = '$username' AND id_company = '$idcompany') ORDER BY i_area", false);
            return $this->db->get()->result();
        }
    }

    public function tunai($cari,$iarea,$drtunaix){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_tunai
            FROM
                tm_tunai a,
                tr_customer b,
                tr_area c
            WHERE
                (a.i_rtunai ISNULL
                OR a.v_sisa>0)
                AND a.d_tunai <= '$drtunaix'
                AND a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND a.f_tunai_cancel = 'f'
                AND a.i_customer = b.i_customer
                AND a.i_area = c.i_area
                AND a.v_sisa > 0
                AND (UPPER(a.i_tunai) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%')
            ORDER BY
                a.i_tunai
        ", FALSE);
    }

    public function getdetailtunai($itunai,$iarea,$drtunaix){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                a.*,
                to_char(a.d_tunai, 'dd-mm-yyyy') AS dtunai,
                b.i_customer,
                b.e_customer_name,
                c.e_area_name
            FROM
                tm_tunai a,
                tr_customer b,
                tr_area c
            WHERE
                (a.i_rtunai ISNULL
                OR a.v_sisa>0)
                AND a.d_tunai <= '$drtunaix'
                AND a.i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND a.f_tunai_cancel = 'f'
                AND a.i_customer = b.i_customer
                AND a.i_area = c.i_area
                AND a.v_sisa > 0
                AND a.i_tunai = '$itunai'
            ORDER BY
                a.i_tunai
        ", FALSE);
    }

    public function runningnumber($iarea,$thbl){
        $th   = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no as max 
            from tm_dgu_no
            where i_modul='RTN'
            and substr(e_periode,1,4)='$th'
            and i_area='$iarea' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nortn  =$terakhir+1;
            $this->db->query(" update tm_dgu_no
                set n_modul_no=$nortn
                where i_modul='RTN'
                and substr(e_periode,1,4)='$th'
                and i_area='$iarea'", false);
            settype($nortn,"string");
            $a=strlen($nortn);
            while($a<5){
                $nortn="0".$nortn;
                $a=strlen($nortn);
            }
            $nortn  ="RTN-".$thbl."-".$nortn;
            return $nortn;
        }else{
            $nortn  ="00001";
            $nortn  ="RTN-".$thbl."-".$nortn;
            $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
              values ('RTN','$iarea','$asal',1)");
            return $nortn;
        }
    }

    public function insert($irtunai,$drtunai,$iarea,$eremark,$vjumlah,$ibank){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'    => $iarea,
                'i_rtunai'  => $irtunai,
                'd_rtunai'  => $drtunai,
                'd_entry'   => $dentry,
                'e_remark'  => $eremark,
                'v_jumlah'  => $vjumlah,
                'i_bank'    => $ibank
            )
        );
        $this->db->insert('tm_rtunai');
    }

    public function insertdetail($irtunai,$iarea,$itunai,$iareatunai,$vjumlah,$i){
        $this->db->set(
            array(
                'i_area'        => $iarea,
                'i_rtunai'      => $irtunai,
                'i_tunai'       => $itunai,
                'i_area_tunai'  => $iareatunai,
                'v_jumlah'      => $vjumlah,
                'n_item_no'     => $i
            )
        );
        $this->db->insert('tm_rtunai_item');
    }

    public function updatetunai($irtunai,$iarea,$itunai,$iareatunai,$vjumlah){
        $this->db->query("update tm_tunai set i_area_rtunai='$iarea', i_rtunai='$irtunai', v_sisa=v_sisa-$vjumlah where i_tunai='$itunai' and i_area='$iareatunai'");
    }
}

/* End of file Mmaster.php */