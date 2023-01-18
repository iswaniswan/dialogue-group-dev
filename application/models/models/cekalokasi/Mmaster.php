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
                d.e_bank_name,
                '(' || c.i_customer || ') - ' || c.e_customer_name AS customer,
                v_jumlah-v_lebih AS v_jumlah,
                v_lebih,
                i_kbank,
                CASE WHEN d_cek IS NULL THEN 'Belum'
                ELSE TO_CHAR(d_cek, 'dd-mm-yyyy') END AS cek,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iarea' AS iarea,
                '$folder' AS folder
            FROM
                tm_alokasi a,
                tr_area b,
                tr_customer c,
                tr_bank d
            WHERE
                a.i_area = b.i_area
                AND a.i_customer = c.i_customer
                AND a.i_area = '$iarea'
                AND a.f_alokasi_cancel = 'f'
                AND a.i_coa_bank = d.i_coa
                AND a.d_alokasi >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_alokasi <= TO_DATE('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.d_alokasi,
                a.i_area,
                a.i_alokasi
        ", FALSE);
        $datatables->add('action', function ($data) {
            $ialokasi = trim($data['i_alokasi']);
            $ikbank   = trim($data['i_kbank']);
            $folder   = $data['folder'];
            $iarea    = $data['iarea'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            $data    .= "<a href=\"#\" onclick='show(\"$folder/cform/cek/$ialokasi/$iarea/$dfrom/$dto/$ikbank\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function bacapl($iarea,$ialokasi,$ikbank){
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
                d.d_bank
            FROM
                tm_alokasi a
            INNER JOIN tr_area b ON
                (a.i_area = b.i_area)
            INNER JOIN tr_customer c ON
                (a.i_customer = c.i_customer)
            INNER JOIN tm_kbank d ON
                (a.i_kbank = d.i_kbank
                AND a.i_coa_bank = d.i_coa_bank)
            WHERE
                a.i_alokasi = '$ialokasi'
                AND a.i_area = '$iarea'
                AND a.i_kbank = '$ikbank'
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }   
    }

    public function bacadetailpl($iarea,$ialokasi,$ikbank){
        $this->db->select("
                a.*,
                b.v_sisa AS v_sisa_nota,
                b.v_nota_netto AS v_nota,
                a.e_remark
            FROM
                tm_alokasi_item a
            INNER JOIN tm_nota b ON
                (a.i_nota = b.i_nota)
            WHERE
                a.i_alokasi = '$ialokasi'
                AND a.i_area = '$iarea'
                AND a.i_kbank = '$ikbank'
            ORDER BY
                a.i_alokasi,
                a.i_area
        ",FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        } 
    }

    public function updatecek($ecek,$user,$ialokasi,$ikbank,$iarea){
        $dentry = current_datetime();
        $data = array(
            'e_cek' => $ecek,
            'd_cek' => $dentry,
            'i_cek' => $user
        );
        $this->db->where('i_alokasi', $ialokasi);
        $this->db->where('i_kbank', $ikbank);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_alokasi', $data);
    }
}

/* End of file Mmaster.php */
