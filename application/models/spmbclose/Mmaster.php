<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function total(){
        return $this->db->query("       
            SELECT
                *
            FROM
                tm_spmb
            WHERE
                f_spmb_pemenuhan = 't'
                AND f_spmb_close = 'f'
            ORDER BY
                i_spmb DESC
        ", false);
    }

    public function data($folder, $total){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                ROW_NUMBER() OVER(
            ORDER BY
                i_spmb DESC) AS i,
                i_spmb,
                TO_CHAR(d_spmb, 'dd-mm-yyyy') AS d_spmb,
                b.i_area || ' - ' || b.e_area_name AS area,
                '$folder' AS folder,
                '$total' AS total
            FROM
                tm_spmb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND f_spmb_pemenuhan = 't'
                AND f_spmb_close = 'f'
            ORDER BY
                i_spmb DESC
        ", false);
        $datatables->add('action', function ($data) {
            $ispmb  = trim($data['i_spmb']);
            $i      = trim($data['i']);
            $folder = $data['folder'];
            $total  = $data['total'];
            $data   = '';
            $data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\">&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"custom-control custom-checkbox\">
                       <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                       <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                       <input name=\"ispmb".$i."\" value=\"".$ispmb."\" type=\"hidden\">";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('total');
        return $datatables->generate();
    }
    
    public function updatespmb($ispmb){
        $data = array(         
            'f_spmb_close' => 't'     
        );
        $this->db->where('i_spmb', $ispmb);
        $this->db->update('tm_spmb', $data); 
    }
}

/* End of file Mmaster.php */
