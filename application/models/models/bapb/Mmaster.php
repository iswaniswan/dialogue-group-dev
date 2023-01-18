<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacadkb($username, $idcompany){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $this->db->select('*');
            $this->db->from('tr_dkb_kirim');
            return $this->db->get();
        }else{
            $this->db->select('*');
            $this->db->from('tr_dkb_kirim');
            $this->db->where('i_dkb_kirim','1');
            return $this->db->get();
        }
    }

    public function getpelanggan($iarea, $cari){
        $cari = str_replace("'", "", $cari);
        return  $this->db->query("
            SELECT
                i_customer,
                e_customer_name
            FROM
                tr_customer
            WHERE
                i_area = '$iarea'
                AND i_customer LIKE '%$cari%' ESCAPE '!'
                OR UPPER(e_customer_name) LIKE '%$cari%' ESCAPE '!' "
            );
    }

    public function bacasj($cari,$iarea,$icustomer){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_sj
            FROM
                tm_nota a,
                tr_customer b
            WHERE
                a.i_area = '$iarea'
                AND (SUBSTRING(a.i_sj, 9, 2) IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'))
                AND a.i_bapb ISNULL
                AND a.i_customer = b.i_customer
                AND a.i_customer = '$icustomer'
                AND (UPPER(a.i_sj) LIKE '%$cari%')
            ORDER BY
                a.i_sj", FALSE);
    }

    public function bacasjx($iarea,$icustomer,$isj){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                a.*,
                to_char(a.d_sj,'dd-mm-yyyy') AS dsj,
                b.i_customer, 
                b.e_customer_name
            FROM
                tm_nota a,
                tr_customer b
            WHERE
                a.i_area = '$iarea'
                AND (SUBSTRING(a.i_sj, 9, 2) IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'))
                AND a.i_bapb ISNULL
                AND a.i_customer = b.i_customer
                AND a.i_customer = '$icustomer'
                AND a.i_sj = '$isj'
            ORDER BY
                a.i_sj", FALSE);
    }

    public function bacaex($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT 
                i_ekspedisi,
                e_ekspedisi
            FROM
                tr_ekspedisi
            WHERE (UPPER(i_ekspedisi) LIKE '%$cari%'
                OR UPPER(e_ekspedisi) LIKE '%$cari%')
            ORDER BY
                i_ekspedisi", FALSE);
    }

    public function bacaexx($iekspedisi){
        return $this->db->query("
            SELECT 
                *
            FROM
                tr_ekspedisi
            WHERE i_ekspedisi = '$iekspedisi'
            ORDER BY
                i_ekspedisi", FALSE);
    }

    public function areanya(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $x = $query->row();
            $iarea = $x->i_area;
        }else{
            $iarea = '';
        }
        return $iarea;
    }

    public function runningnumber($iarea,$thbl){      
        $th     = substr($thbl,0,4);      
        $asal   = $thbl;      
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'BAP'
                AND substr(e_periode, 1, 4)= '$th'
                AND i_area = '$iarea' FOR
            UPDATE", false);
        $query = $this->db->get();        
        if ($query->num_rows() > 0){           
            foreach($query->result() as $row){             
                $terakhir=$row->max;           
            }           
            $nobapb  =$terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nobapb
                WHERE
                    i_modul = 'BAP'
                    AND substr(e_periode, 1, 4)= '$th'
                    AND i_area = '$iarea' ", false);            
            settype($nobapb,"string");            
            $a=strlen($nobapb);            
            while($a<4){               
                $nobapb="0".$nobapb;               
                $a=strlen($nobapb);           
            }           
            $nobapb  ="BAP-".$thbl."-".$iarea.$nobapb;       
            return $nobapb;       
        }else{         
            $nobapb  ="0001";         
            $nobapb  ="BAP-".$thbl."-".$iarea.$nobapb;         
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('BAP',
                '$iarea',
                '$asal',
                1) ");         
            return $nobapb;     
        } 
    }

    public function insertheader($ibapb, $dbapb, $iarea, $idkbkirim, $icustomer, $nbal, $ibapbold, $vbapb, $vkirim){
        $this->db->set(
            array(
                'i_bapb'      => $ibapb,
                'd_bapb'      => $dbapb,
                'i_dkb_kirim' => $idkbkirim,
                'i_area'      => $iarea,
                'i_customer'  => $icustomer,
                'n_bal'       => $nbal,
                'i_bapb_old'  => $ibapbold,
                'v_bapb'      => $vbapb,
                'v_kirim'     => $vkirim
            )
        );        
        $this->db->insert('tm_bapb');
    }

    public function insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$i,$vsj){
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->where('i_bapb', $ibapb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_sj', $isj);
        $this->db->delete('tm_bapb_item');

        $this->db->set(
            array(
                'i_bapb'    => $ibapb,
                'i_area'    => $iarea,
                'i_sj'      => $isj,
                'd_bapb'    => $dbapb,
                'd_sj'      => $dsj,
                'e_remark'  => $eremark,
                'n_item_no' => $i,
                'v_sj'      => $vsj
            )
        );
        $this->db->insert('tm_bapb_item');
    }

    public function updatesj($ibapb,$isj,$iarea,$dbapb){
        $this->db->set(
            array(
                'i_bapb' => $ibapb, 
                'd_bapb' => $dbapb
            )
        );
        $this->db->where('i_sj',$isj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_nota');
    }

    public function insertdetailekspedisi($ibapb,$iarea,$iekspedisi,$dbapb,$eremark){
        $this->db->where('i_bapb', $ibapb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_ekspedisi', $iekspedisi);
        $this->db->delete('tm_bapb_ekspedisi');
        $this->db->set(
            array(
                'i_bapb'      => $ibapb,
                'i_area'      => $iarea,
                'i_ekspedisi' => $iekspedisi,
                'd_bapb'      => $dbapb,
                'e_remark'    => $eremark
            )
        );
        $this->db->insert('tm_bapb_ekspedisi');
    }
}

/* End of file Mmaster.php */
