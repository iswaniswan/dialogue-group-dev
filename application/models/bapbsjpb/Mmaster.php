<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacasj($cari,$periode){
        $cari      = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_sjpb
            FROM
                tm_sjpb a,
                tm_sjpb_item b
            WHERE
                a.i_sjpb = b.i_sjpb
                AND a.i_area = b.i_area
                AND a.i_bapb ISNULL
                AND a.f_sjpb_cancel = 'f'
                AND a.i_area_entry ISNULL
                AND a.d_sjpb >= '$periode'
                AND (UPPER(a.i_sjpb) LIKE '%$cari%')
            GROUP BY
                a.i_sjpb,
                a.d_sjpb
            ORDER BY
                i_sjpb DESC", 
        FALSE);
    }

    public function bacasjx($periode,$isj){
        return $this->db->query("
            SELECT
                a.i_sjpb,
                a.d_sjpb,
                to_char(a.d_sjpb, 'dd-mm-yyyy') AS dsjpb,
                SUM(b.n_deliver * b.v_unit_price) AS v_sjpb
            FROM
                tm_sjpb a,
                tm_sjpb_item b
            WHERE
                a.i_sjpb = b.i_sjpb
                AND a.i_area = b.i_area
                AND a.i_bapb ISNULL
                AND a.f_sjpb_cancel = 'f'
                AND a.i_area_entry ISNULL
                AND a.d_sjpb >= '$periode'
                AND a.i_sjpb = '$isj'
            GROUP BY
                a.i_sjpb,
                a.d_sjpb
            ORDER BY
                i_sjpb DESC",
        FALSE);
    }

    public function runningnumber($iarea,$thbl){      
        $th         = substr($thbl,0,2);
        $this->db->select("
                MAX(substr(i_bapb, 11, 6)) AS MAX
            FROM
                tm_bapbsjpb
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
            $nobapb  = $terakhir+1;
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

    public function insertheader($ibapb, $dbapb, $iarea, $vbapb){
        $dentry  = current_datetime();
        $this->db->set(
            array(
                'i_bapb'  => $ibapb,
                'd_bapb'  => $dbapb,
                'i_area'  => $iarea,
                'v_bapb'  => $vbapb,
                'd_entry' => $dentry
            )
        );
        $this->db->insert('tm_bapbsjpb');
    }

    public function insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$vsj){
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->where('i_bapb', $ibapb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_sjpb', $isj);
        $this->db->delete('tm_bapbsjpb_item');

        $this->db->set(
            array(
                'i_bapb'    => $ibapb,
                'i_area'    => $iarea,
                'i_sjpb'    => $isj,
                'd_bapb'    => $dbapb,
                'd_sjpb'    => $dsj,
                'e_remark'  => $eremark,
                'v_sjpb'    => $vsj
            )
        );
        $this->db->insert('tm_bapbsjpb_item');
    }

    public function updatesj($ibapb,$isj,$iarea,$dbapb){
        $this->db->set(
            array(
                'i_bapb' => $ibapb, 
                'd_bapb' => $dbapb
            )
        );
        $this->db->where('i_sjpb',$isj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_sjpb');
    }

    public function updatesjb($ibapb,$iarea,$nilaitotal){
        $this->db->set(
            array(
                'v_bapb' => $nilaitotal
            )
        );
        $this->db->where('i_bapb',$ibapb);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_bapbsjpb');
    }
}

/* End of file Mmaster.php */
