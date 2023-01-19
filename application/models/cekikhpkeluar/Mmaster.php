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
                i_ikhp,
                i_bukti,
                TO_CHAR(d_bukti, 'dd-mm-yyyy') AS d_bukti,
                e_area_name,
                e_ikhp_typename,
                v_terima_tunai,
                v_terima_giro,
                v_keluar_tunai,
                v_keluar_giro,
                CASE WHEN d_cek IS NULL THEN 'Belum'
                ELSE TO_CHAR(d_cek, 'dd-mm-yyyy') END AS cek,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iarea' AS iarea,
                '$folder' AS folder
            FROM
                tm_ikhp a,
                tr_area b,
                tr_ikhp_type c
            WHERE
                a.i_area = b.i_area
                AND a.i_ikhp_type = c.i_ikhp_type
                AND a.i_area = '$iarea'
                AND a.d_bukti >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_bukti <= TO_DATE('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.d_bukti,
                a.i_ikhp"
        , FALSE);
        $datatables->add('action', function ($data) {
            $ikhp         = trim($data['i_ikhp']);
            $folder       = $data['folder'];
            $iarea        = $data['iarea'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $data         = '';
            $data        .= "<a href=\"#\" onclick='show(\"$folder/cform/cek/$ikhp/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('i_ikhp');
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function baca($ikhp){
        $this->db->select("
                *
            FROM
                tm_ikhp a,
                tr_area b,
                tr_ikhp_type c
            WHERE
                a.i_ikhp = $ikhp
                AND a.i_area = b.i_area
                AND a.i_ikhp_type = c.i_ikhp_type
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }   
    }

    public function uraian(){
        $this->db->select('*');
        $this->db->from('tr_ikhp_type');
        $this->db->order_by('i_ikhp_type');
        return $this->db->get();
    }

    public function update($iikhp,$iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro,$ecek,$user){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'e_cek'     => $ecek,
                'd_cek'     => $dentry,
                'i_cek'     => $user
            )
        );
        $this->db->where('i_ikhp',$iikhp);
        $this->db->update('tm_ikhp');
    }
}

/* End of file Mmaster.php */
