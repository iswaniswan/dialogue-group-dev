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
                i_dt,
                TO_CHAR(d_dt, 'dd-mm-yyyy') AS d_dt,
                b.i_area || ' - ' || e_area_name AS area,
                TO_CHAR(d_cek, 'dd-mm-yyyy') AS d_cek,
                CASE WHEN f_dt_cancel = FALSE THEN 'Tidak'
                ELSE 'Ya' END AS status_cancel,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iarea' AS iarea,
                '$folder' AS folder
            FROM
                tr_area b,
                tm_dt a
            WHERE
                a.i_area = b.i_area
                AND a.i_area = '$iarea'
                AND a.d_dt >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_dt <= TO_DATE('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.d_dt DESC"
        , FALSE);
        $datatables->add('action', function ($data) {
            $idt          = trim($data['i_dt']);
            $ddt          = trim($data['d_dt']);
            $folder       = $data['folder'];
            $iarea        = $data['iarea'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $data         = '';
            $data        .= "<a href=\"#\" onclick='show(\"$folder/cform/cek/$idt/$iarea/$dfrom/$dto/$ddt\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function baca($idt,$iarea,$tgl){
        $this->db->select("
                *
            FROM
                tm_dt
            INNER JOIN tr_area ON
                (tm_dt.i_area = tr_area.i_area)
            WHERE
                tm_dt.i_dt = '$idt'
                AND tm_dt.i_area = '$iarea'
                AND tm_dt.d_dt = '$tgl'
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }   
    }

    public function bacadetail($idt,$iarea,$tgl){
        $this->db->select("
                a.*,
                b.v_sisa AS sisanota,
                b.d_jatuh_tempo,
                c.e_customer_name,
                c.e_customer_city,
                a.v_jumlah-b.v_sisa AS jml
            FROM
                tm_dt_item a
            INNER JOIN tm_nota b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tr_customer c ON
                (b.i_customer = c.i_customer)
            WHERE
                a.i_dt = '$idt'
                AND a.i_area = '$iarea'
                AND a.d_dt = '$tgl'
            ORDER BY
                a.n_item_no
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        } 
    }

    public function updatecek($ecek,$user,$idt,$iarea,$ddt){
        $dentry = current_datetime();
        $data = array(
            'e_cek' => $ecek,
            'd_cek' => $dentry,
            'i_cek' => $user
        );
        $this->db->where('d_dt', $ddt);
        $this->db->where('i_dt', $idt);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_dt', $data);
    }
}

/* End of file Mmaster.php */
