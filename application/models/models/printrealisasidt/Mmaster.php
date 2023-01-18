<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
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

    public function cekarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
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
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return '00';
        }else{
            return 'XX';
        }
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu,$xarea){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if ($xarea=='00') {
            $sql = "
                SELECT
                    i_dt, 
                    a.i_area,
                    to_char(d_dt, 'dd-mm-yyyy') AS d_dt, 
                    v_jumlah,
                    n_print,
                    '$folder' AS folder
                FROM
                    tm_dt a,
                    tr_area b
                WHERE
                    a.i_area = b.i_area
                    AND a.f_dt_cancel = 'f'
                    AND a.d_dt >= to_date('$dfrom', 'dd-mm-yyyy')
                    AND a.d_dt <= to_date('$dto', 'dd-mm-yyyy')
                    AND a.i_area = '$iarea'
                ORDER BY
                    a.i_dt DESC
            ";
        }else{
            $sql = "
                SELECT
                    i_dt, 
                    a.i_area,
                    to_char(d_dt, 'dd-mm-yyyy') AS d_dt, 
                    v_jumlah,
                    n_print,
                    '$folder' AS folder
                FROM
                    tm_dt a,
                    tr_area b
                WHERE
                    a.i_area = b.i_area
                    AND a.d_dt >= to_date('$dfrom', 'dd-mm-yyyy')
                    AND a.d_dt <= to_date('$dto', 'dd-mm-yyyy')
                    AND a.i_area = '$iarea'
                    AND a.f_dt_cancel = 'f'
                    AND a.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
                ORDER BY
                    a.i_dt DESC
            ";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("$sql", FALSE);
        $datatables->add('action', function ($data) {
            $i_dt           = trim($data['i_dt']);
            $i_area         = $data['i_area'];
            $folder         = $data['folder'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='printdt(\"$i_dt\",\"$i_area\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
            return $data;
        });

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print']=='0') {
                $data = '<span class="label label-info label-rouded">Belum</span>';
            }else{
                $data = '<span class="label label-success label-rouded">Sudah</span>';
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($idt,$iarea){   
        $this->db->select("
                *
            FROM
                (
                SELECT
                    DISTINCT ON
                    (a.i_dt) b.e_area_name, a.i_dt, a.i_area, a.d_dt, e.i_nota
                FROM
                    tr_area b, tm_dt_item e, tm_dt a
                WHERE
                    a.i_dt = '$idt'
                    AND a.i_area = '$iarea'
                    AND a.i_area = b.i_area
                    AND a.f_dt_cancel = 'f'
                    AND a.i_dt = e.i_dt
                    AND a.i_area = e.i_area
                    AND a.d_dt = e.d_dt ) AS a
            LEFT JOIN tm_alokasi_item d ON
                (d.i_nota = a.i_nota)
            LEFT JOIN tm_alokasi c ON
                (a.i_area = c.i_area
                AND c.f_alokasi_cancel = 'f'
                AND c.i_alokasi = d.i_alokasi
                AND c.i_area = d.i_area
                AND c.i_kbank = d.i_kbank)
            LEFT JOIN tm_alokasikn_item f ON
                (f.i_nota = a.i_nota)
            LEFT JOIN tm_alokasikn e ON
                (a.i_area = e.i_area
                AND e.f_alokasi_cancel = 'f'
                AND e.i_alokasi = f.i_alokasi
                AND e.i_area = f.i_area
                AND e.i_kn = f.i_kn)
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacadetail($idt,$iarea){
        $this->db->select("
                b.*
            FROM
                (
                SELECT
                    a.*, d.v_jumlah AS jumlahitem, d.i_alokasi, c.i_giro, c.d_alokasi, d.e_remark
                FROM
                    (
                    SELECT
                        a.*, c.e_customer_name, c.e_customer_city, b.d_jatuh_tempo
                    FROM
                        tm_dt_item a, tr_customer c, tm_nota b, tm_dt f
                    WHERE
                        a.i_dt = '$idt'
                        AND a.i_area = '$iarea'
                        AND f.f_dt_cancel = 'f'
                        AND a.i_dt = f.i_dt
                        AND a.i_area = f.i_area
                        AND a.d_dt = f.d_dt
                        AND a.i_nota = b.i_nota
                        AND a.i_customer = c.i_customer ) AS a
                LEFT JOIN tm_alokasi_item d ON
                    (d.i_nota = a.i_nota)
                LEFT JOIN tm_alokasi c ON
                    (a.i_area = c.i_area
                    AND c.f_alokasi_cancel = 'f'
                    AND c.i_alokasi = d.i_alokasi
                    AND c.i_area = d.i_area
                    AND c.i_kbank = d.i_kbank)
            UNION ALL
                SELECT
                    a.*, d.v_jumlah AS jumlahitem, d.i_alokasi, c.i_kbank, c.d_alokasi, d.e_remark
                FROM
                    (
                    SELECT
                        a.*, c.e_customer_name, c.e_customer_city, b.d_jatuh_tempo
                    FROM
                        tm_dt_item a, tr_customer c, tm_nota b, tm_dt f
                    WHERE
                        a.i_dt = '$idt'
                        AND a.i_area = '$iarea'
                        AND f.f_dt_cancel = 'f'
                        AND a.i_dt = f.i_dt
                        AND a.i_area = f.i_area
                        AND a.d_dt = f.d_dt
                        AND a.i_nota = b.i_nota
                        AND a.i_customer = c.i_customer ) AS a
                INNER JOIN tm_alokasihl_item d ON
                    (d.i_nota = a.i_nota)
                INNER JOIN tm_alokasihl c ON
                    (a.i_area = c.i_area
                    AND c.f_alokasi_cancel = 'f'
                    AND c.i_alokasi = d.i_alokasi
                    AND c.i_area = d.i_area)
            UNION ALL
                SELECT
                    a.*, f.v_jumlah AS jumlahitem, f.i_alokasi, e.i_kn, e.d_alokasi, f.e_remark
                FROM
                    (
                    SELECT
                        a.*, c.e_customer_name, c.e_customer_city, b.d_jatuh_tempo
                    FROM
                        tm_dt_item a, tr_customer c, tm_nota b, tm_dt f
                    WHERE
                        a.i_dt = '$idt'
                        AND a.i_area = '$iarea'
                        AND f.f_dt_cancel = 'f'
                        AND a.i_dt = f.i_dt
                        AND a.i_area = f.i_area
                        AND a.d_dt = f.d_dt
                        AND a.i_nota = b.i_nota
                        AND a.i_customer = c.i_customer ) AS a
                INNER JOIN tm_alokasikn_item f ON
                    (f.i_nota = a.i_nota)
                INNER JOIN tm_alokasikn e ON
                    (a.i_area = e.i_area
                    AND e.f_alokasi_cancel = 'f'
                    AND e.i_alokasi = f.i_alokasi
                    AND e.i_area = f.i_area
                    AND e.i_kn = f.i_kn)
            UNION ALL
                SELECT
                    a.*, f.v_jumlah AS jumlahitem, f.i_alokasi, e.i_kn, e.d_alokasi, f.e_remark
                FROM
                    (
                    SELECT
                        a.*, c.e_customer_name, c.e_customer_city, b.d_jatuh_tempo
                    FROM
                        tm_dt_item a, tr_customer c, tm_nota b, tm_dt f
                    WHERE
                        a.i_dt = '$idt'
                        AND a.i_area = '$iarea'
                        AND f.f_dt_cancel = 'f'
                        AND a.i_dt = f.i_dt
                        AND a.i_area = f.i_area
                        AND a.d_dt = f.d_dt
                        AND a.i_nota = b.i_nota
                        AND a.i_customer = c.i_customer ) AS a
                INNER JOIN tm_alokasiknr_item f ON
                    (f.i_nota = a.i_nota)
                INNER JOIN tm_alokasiknr e ON
                    (a.i_area = e.i_area
                    AND e.f_alokasi_cancel = 'f'
                    AND e.i_alokasi = f.i_alokasi
                    AND e.i_area = f.i_area
                    AND e.i_kn = f.i_kn) ) AS b
            ORDER BY
                b.n_item_no,
                b.d_alokasi,
                b.jumlahitem DESC
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function company($id_company){
        return $this->db->query("
            SELECT
                *
            FROM
                public.company a,
                public.constant b
            WHERE
                a.id = b.id_company
                AND id = '$id_company'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
