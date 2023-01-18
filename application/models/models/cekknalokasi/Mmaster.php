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
                e_area_name,
                i_alokasi,
                TO_CHAR(d_alokasi, 'dd-mm-yyyy') AS d_alokasi,
                i_kn,
                '(' || c.i_customer || ') - ' || e_customer_name AS customer,
                v_jumlah-v_lebih AS v_jumlah,
                v_lebih,
                CASE WHEN d_cek IS NULL THEN 'Belum' 
                ELSE TO_CHAR(d_cek, 'dd-mm-yyyy') END AS cek,
                CASE WHEN f_alokasi_cancel = FALSE THEN 'Tidak'
                ELSE 'Ya' END AS status_cancel,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iarea' AS iarea,
                '$folder' AS folder
            FROM
                tm_alokasikn a,
                tr_area b,
                tr_customer c
            WHERE
                a.i_area = b.i_area
                AND a.i_customer = c.i_customer
                AND a.i_area = '$iarea'
                AND a.f_alokasi_cancel = 'f'
                AND a.d_alokasi >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_alokasi <= TO_DATE('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.d_alokasi,
                a.i_area,
                a.i_alokasi
"
        , FALSE);
        $datatables->add('action', function ($data) {
            $ialokasi = trim($data['i_alokasi']);
            $ikn      = trim($data['i_kn']);
            $folder   = $data['folder'];
            $iarea    = $data['iarea'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            $data    .= "<a href=\"#\" onclick='show(\"$folder/cform/cek/$ialokasi/$iarea/$dfrom/$dto/$ikn\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function bacapl($iarea,$ialokasi,$ikn){
        $this->db->select("
                a.*,
                b.e_area_name,
                c.e_customer_name,
                '' AS e_jenis_bayarname,
                '' AS d_dt,
                c.e_customer_address,
                c.e_customer_city,
                '' AS d_giro_jt,
                '' AS d_giro_cair,
                d.d_kn
            FROM
                tm_alokasikn a
            INNER JOIN tr_area b ON
                (a.i_area = b.i_area)
            INNER JOIN tr_customer c ON
                (a.i_customer = c.i_customer)
            INNER JOIN tm_kn d ON
                (a.i_kn = d.i_kn
                AND a.i_area = d.i_area)
            WHERE
                a.i_alokasi = '$ialokasi'
                AND a.i_area = '$iarea'
                AND a.i_kn = '$ikn'

        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }   
    }

    public function bacadetailpl($iarea,$ialokasi,$ikn){
        $this->db->select("
                a.*,
                b.v_sisa AS v_sisa_nota,
                b.v_nota_netto AS v_nota,
                a.e_remark
            FROM
                tm_alokasikn_item a
            INNER JOIN tm_nota b ON
                (a.i_nota = b.i_nota)
            WHERE
                a.i_alokasi = '$ialokasi'
                AND a.i_area = '$iarea'
                AND a.i_kn = '$ikn'
            ORDER BY
                a.i_alokasi,
                a.i_area
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        } 
    }

    public function updatecek($ecek,$user,$ialokasi,$ikn,$iarea){
        $dentry = current_datetime();
        $data = array(
            'e_cek' => $ecek,
            'd_cek' => $dentry,
            'i_cek' => $user
        );
        $this->db->where('i_alokasi', $ialokasi);
        $this->db->where('i_kn', $ikn);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_alokasikn', $data);
    }
}

/* End of file Mmaster.php */
