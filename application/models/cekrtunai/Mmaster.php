<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function area($username, $idcompany){
        return  $this->db->query("
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
        ", FALSE);
    }

    public function data($dfrom, $dto, $iarea, $folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_area,
                a.i_rtunai AS irtunai,
                TO_CHAR(a.d_rtunai, 'dd-mm-yyyy') AS drtunai,
                a.v_jumlah AS vjumlah,
                CASE WHEN d_cek IS NULL THEN 'Belum'
                ELSE TO_CHAR(d_cek, 'dd-mm-yyyy') END AS cek,
                a.e_remark AS eremark,
                CASE WHEN f_rtunai_cancel = FALSE THEN 'Tidak'
                ELSE 'Ya' END AS status_cancel,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iarea' AS iarea,
                '$folder' AS folder
            FROM
                tm_rtunai a
            LEFT JOIN tr_area d ON
                (a.i_area = d.i_area)
            WHERE
                a.i_area = '$iarea'
                AND(a.d_rtunai >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_rtunai <= TO_DATE('$dto', 'dd-mm-yyyy'))
            ORDER BY
                irtunai"
        , FALSE);
        $datatables->add('action', function ($data) {
            $irtunai      = trim($data['irtunai']);
            $folder       = $data['folder'];
            $iarea        = $data['iarea'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $data         = '';
            $data        .= "<a href=\"#\" onclick='show(\"$folder/cform/cek/$irtunai/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function baca($iarea,$irtunai){
        $this->db->select("
                a.d_rtunai,
                a.i_rtunai,
                d.e_area_name,
                a.v_jumlah,
                a.i_area,
                a.e_remark,
                a.i_cek,
                a.e_cek,
                d_cek
            FROM
                tm_rtunai a
            LEFT JOIN tr_area d ON
                (a.i_area = d.i_area)
            WHERE
                a.i_rtunai = '$irtunai'
                AND a.i_area = '$iarea'
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }   
    }

    public function bacadetail($iarea,$irtunai){
        $this->db->select("
                a.*,
                b.e_area_name,
                TO_CHAR(c.d_tunai, 'dd-mm-yyyy') AS d_tunai,
                c.i_customer,
                c.e_remark,
                d.e_customer_name
            FROM
                tm_rtunai_item a
            LEFT JOIN tr_area b ON
                (a.i_area_tunai = b.i_area),
                tm_tunai c,
                tr_customer d
            WHERE
                a.i_rtunai = '$irtunai'
                AND a.i_area = '$iarea'
                AND c.i_customer = d.i_customer
                AND a.i_tunai = c.i_tunai
                AND a.i_area_tunai = c.i_area
            ORDER BY
                a.n_item_no
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        } 
    }

    public function cek($ecek,$user,$irtunai,$iarea){
        $dentry = current_datetime();
        $data = array(
            'e_cek' => $ecek,
            'd_cek' => $dentry,
            'i_cek' => $user
        );
        $this->db->where('i_rtunai', $irtunai);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_rtunai', $data);
    }
}

/* End of file Mmaster.php */
