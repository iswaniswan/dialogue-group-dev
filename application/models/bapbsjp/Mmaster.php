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

    public function bacasj($cari,$iarea,$periode){
        $cari      = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_sjp 
            FROM
                tm_sjp
            WHERE
                i_area = '$iarea'
                AND i_bapb ISNULL
                AND d_sjp >= '$periode'
                AND (UPPER(i_sjp) LIKE '%$cari%'
                OR UPPER(i_sjp_old) LIKE '%$cari%')
            ORDER BY
                i_sjp", 
        FALSE);
    }

    public function bacasjx($iarea,$isj,$periode){
        return $this->db->query("
            SELECT
                *,
                to_char(d_sjp,'dd-mm-yyyy') AS dsjp 
            FROM
                tm_sjp
            WHERE
                i_area = '$iarea'
                AND i_sjp = '$isj'
                AND d_sjp >= '$periode'
                AND i_bapb ISNULL
            ORDER BY
                i_sjp",
        FALSE);
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
                i_ekspedisi", 
        FALSE);
    }

    public function bacaexx($iekspedisi){
        return $this->db->query("
            SELECT 
                *
            FROM
                tr_ekspedisi
            WHERE i_ekspedisi = '$iekspedisi'
            ORDER BY
                i_ekspedisi", 
        FALSE);
    }

    public function runningnumber($iarea,$thbl){      
        $th         = substr($thbl,0,2);
        $this->db->select("
                MAX(substr(i_bapb, 11, 6)) AS MAX
            FROM
                tm_bapbsjp
            WHERE
                substr(i_bapb,
                6,
                2)= '$th'
                AND i_area = '$iarea'", 
        false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobapb  =$terakhir+1;
            settype($nobapb,"string");
            $a=strlen($nobapb);
            while($a<6){
                $nobapb="0".$nobapb;
                $a=strlen($nobapb);
            }
            $nobapb  ="BAPB-".$thbl."-".$nobapb;
            return $nobapb;
        }else{
            $nobapb  ="000001";
            $nobapb  ="BAPB-".$thbl."-".$nobapb;
            return $nobapb;
        }
    }

    public function insertheader($ibapb, $dbapb, $iarea, $idkbkirim, $nbal, $ibapbold, $vbapb, $vkirim){
        $this->db->set(
            array(
                'i_bapb'      => $ibapb,
                'd_bapb'      => $dbapb,
                'i_dkb_kirim' => $idkbkirim,
                'i_area'      => $iarea,
                'n_bal'       => $nbal,
                'i_bapb_old'  => $ibapbold,
                'v_bapb'      => $vbapb,
                'v_kirim'     => $vkirim
            )
        );        
        $this->db->insert('tm_bapbsjp');
    }

    public function insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$vsj){
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->where('i_bapb', $ibapb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_sjp', $isj);
        $this->db->delete('tm_bapbsjp_item');

        $this->db->set(
            array(
                'i_bapb'    => $ibapb,
                'i_area'    => $iarea,
                'i_sjp'     => $isj,
                'd_bapb'    => $dbapb,
                'd_sjp'     => $dsj,
                'e_remark'  => $eremark,
                'v_sj'      => $vsj
            )
        );
        $this->db->insert('tm_bapbsjp_item');
    }

    public function updatesj($ibapb,$isj,$iarea,$dbapb){
        $this->db->set(
            array(
                'i_bapb' => $ibapb, 
                'd_bapb' => $dbapb
            )
        );
        $this->db->where('i_sjp',$isj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_sjp');
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
