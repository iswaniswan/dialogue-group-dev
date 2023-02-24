<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($folder, $i_menu, $dfrom, $dto, $i_area, $i_rv_type)
    {
        $dfrom = formatYmd($dfrom);
        $dto = formatYmd($dto);
        if ($i_area == 'all') {
            $and = "AND b.i_area IN (SELECT i_area FROM public.tm_user_area WHERE id_company = '$this->id_company' AND username = '$this->username')";
        } else {
            $and = "AND a.i_area = '$i_area' ";
        }
        if ($i_rv_type == 'all') {
            $or = "AND a.i_rv_type IN (SELECT i_rv_type FROM public.tm_user_kas_rv WHERE username = '$this->username' AND i_company = '$this->id_company')";
        } else {
            $or = "AND a.i_rv_type = '$i_rv_type' ";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                0 as no,
                a.i_rv as id,
                a.i_rv_id,
                to_char(a.d_rv, 'dd-mm-yyyy') as d_rv,
                d.e_rv_type_name,
                '[' || b.i_area || '] - ' || b.e_area AS e_area,
                v_rv::money AS v_jumlah,
                a.i_status,
                c.e_status_name,
                c.label_color,
                f.i_level,
                l.e_level_name,
                n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' as i_menu,
                '$folder' AS folder
            FROM
                tm_rv a 
            INNER JOIN tr_area b 
                ON (a.i_area = b.id) 
            INNER JOIN tr_status_document c 
                ON (a.i_status = c.i_status)
            INNER JOIN public.tr_rv_type d 
                ON (d.i_rv_type = a.i_rv_type)
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE 
                a.i_company = '$this->id_company' AND a.i_status <> '5'AND a.d_rv BETWEEN '$dfrom' AND '$dto'
                $and $or
            ORDER BY
                a.d_rv ASC"
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='label label-primary'>Belum</span>";
            } else {
                $data = "<span class='label label-info'>Sudah " . $data['n_print'] . 'x' . "</span>";
            }
            return $data;
        });

        $datatables->add('action', function ($data) {
            $id = trim($data['id']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom = $data['dfrom'];
            $dto = $data['dto'];
            $i_level = $data['i_level'];
            $data = '';

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success mr-3 fa-lg'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 5)) {
                if ($i_status == '6' or $i_status == '4') {
                    $id = encrypt_url($id);
                    $data .= "<a href=\"#\" title='Cetak DT' onclick='cetak(\"$id\"); return false;'><i class='ti-printer fa-lg mr-2 text-warning'></i></a>";
                    // $data .= "<a href=\"" . base_url($folder . '/cform/cetak/' . encrypt_url($id)) . "\" title='Print' target='_blank'><i class='ti-printer text-warning mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3 fa-lg'></i></a>";
            }
            return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function bagian()
    {
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function area()
    {
        return $this->db->query(
            "SELECT id, i_area, e_area FROM tr_area WHERE f_status = 't' 
            AND i_area IN (SELECT i_area FROM public.tm_user_area WHERE id_company = '$this->id_company' AND username = '$this->username')
            ORDER BY 2,3"
        );
    }

    public function get_area($cari)
    {
        return $this->db->query(
            "SELECT id, i_area, e_area FROM tr_area WHERE f_status = 't' 
            AND (i_area ILIKE '%$cari%' OR e_area ILIKE '%$cari%')
            ORDER BY 2,3"
        );
    }

    public function rvtype()
    {
        return $this->db->query(
            "SELECT i_rv_type, i_rv_type_id , initcap(e_rv_type_name) AS e_rv_type_name
            FROM tr_rv_type
            WHERE i_company = '4' AND f_rv_type_active = 'true' 
            AND i_rv_type IN (SELECT i_rv_type FROM tm_user_kas_rv WHERE username = '$this->username' AND i_company = '$this->id_company')
            ORDER BY 3 ASC"
        );
    }

    public function coa_type($cari, $i_rv_type)
    {
        return $this->db->query(
            "SELECT id as i_coa, i_coa as i_coa_id, e_coa_name
            FROM tr_coa a
            INNER JOIN tr_rv_type b ON (
                b.i_coa_group = a.i_coa_ledger AND b.i_coa_group <> a.i_coa AND b.i_rv_type = '$i_rv_type'
            )
            WHERE 
                (e_coa_name ILIKE '%$cari%' OR i_coa ILIKE '%$cari%')
                AND f_status = 'true'
            ORDER BY 2 ASC;"
        );
    }

    public function reference_type($cari)
    {
        return $this->db->query(
            "SELECT 
                i_rv_refference_type, e_rv_refference_type_name
            FROM 
                tr_rv_refference_type
            WHERE 
                (e_rv_refference_type_name ILIKE '%$cari%')
                AND i_company = '$this->id_company' 
                AND f_rv_refference_type_active = 'true' 
            ORDER BY 2 ASC"
        );
    }

    public function runningnumber($thbl, $tahun, $i_bagian, $i_area, $i_rv_type, $i_coa, $id)
    {
        if (strlen($i_bagian) > 0 && strlen($i_area) > 0 && strlen($i_rv_type) > 0 && strlen($i_coa) > 0) {
            $cek = $this->db->query(
                "SELECT substring(i_rv_id, 1, 2) AS kode 
            FROM tm_rv WHERE i_status <> '5' 
            AND i_bagian = '$i_bagian' AND i_company = '$this->id_company' 
            AND i_area = '$i_area' AND i_rv_type = '$i_rv_type' AND i_coa = '$i_coa' ORDER BY i_rv DESC LIMIT 1"
            );

            if ($cek->num_rows() > 0) {
                $kode = $cek->row()->kode;
            } else {
                $kode = 'RV';
            }
            if (strlen($id) > 0) {
                $query = $this->db->query(
                    "SELECT max(substring(i_rv_id, 9, 4)) AS max FROM tm_rv WHERE to_char (d_rv, 'yymm') = '$thbl' 
                AND i_status <> '5' AND i_bagian = '$i_bagian' AND i_company = '$this->id_company' 
                AND i_area = '$i_area' AND i_rv_type = '$i_rv_type' AND i_coa = '$i_coa' AND i_rv_id ILIKE '%$kode%' AND i_rv <> '$id' "
                );
            } else {
                $query = $this->db->query(
                    "SELECT max(substring(i_rv_id, 9, 4)) AS max FROM tm_rv WHERE to_char (d_rv, 'yymm') = '$thbl' 
                AND i_status <> '5' AND i_bagian = '$i_bagian' AND i_company = '$this->id_company' 
                AND i_area = '$i_area' AND i_rv_type = '$i_rv_type' AND i_coa = '$i_coa' AND i_rv_id ILIKE '%$kode%'"
                );
            }
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $no = $row->max;
                }
                $number = $no + 1;
                settype($number, "string");
                $n = strlen($number);
                while ($n < 4) {
                    $number = "0" . $number;
                    $n = strlen($number);
                }
                $number = $kode . "-" . $thbl . "-" . $number;
                return $number;
            } else {
                $number = "0001";
                $number = $kode . "-" . $thbl . "-" . $number;
                return $number;
            }
        } else {
            return "RV-$thbl-0001";
        }
    }

    public function coa($cari, $i_rv_type)
    {
        $query = $this->db->query("SELECT f_kas_kecil, f_kas_besar, f_kas_bank FROM tr_rv_type WHERE i_rv_type = '$i_rv_type'", FALSE);
        if ($query->num_rows() > 0) {
            $f_kas_kecil = $query->row()->f_kas_kecil;
            $f_kas_besar = $query->row()->f_kas_besar;
            $f_kas_bank = $query->row()->f_kas_bank;
            return $this->db->query("SELECT 
                    id AS i_coa, i_coa AS i_coa_id , e_coa_name
                FROM 
                    tr_coa
                WHERE 
                    (e_coa_name ILIKE '%$cari%' OR i_coa ILIKE '%$cari%')
                    AND f_status = 'true' 
                    AND 
                        CASE
                            WHEN '$f_kas_kecil' = 't' AND '$f_kas_besar' = 'f' AND '$f_kas_bank' = 'f' THEN f_kas_kecil = 't'
                            WHEN '$f_kas_kecil' = 'f' AND '$f_kas_besar' = 't' AND '$f_kas_bank' = 'f' THEN f_kas_besar = 't'
                            WHEN '$f_kas_kecil' = 'f' AND '$f_kas_besar' = 'f' AND '$f_kas_bank' = 't' THEN f_kas_bank = 't'
                        END
                ORDER BY 3 ASC
            ", FALSE);
        } else {
            return $this->db->query("SELECT 
                    id AS i_coa, i_coa AS i_coa_id , e_coa_name
                FROM 
                    tr_coa
                WHERE 
                    (e_coa_name ILIKE '%$cari%' OR i_coa ILIKE '%$cari%')
                    AND f_status = 'true' 
                ORDER BY 3 ASC
            ", FALSE);
        }
    }

    public function referensi($cari, $i_area, $i_rv_refference_type)
    {
        $query = $this->db->query("SELECT f_tunai, f_giro, f_transfer FROM tr_rv_refference_type WHERE i_rv_refference_type = '$i_rv_refference_type'");
        if ($query->num_rows() > 0) {
            $f_tunai = $query->row()->f_tunai;
            $f_giro = $query->row()->f_giro;
            $f_transfer = $query->row()->f_transfer;
            if ($f_tunai == 'f' && $f_giro == 'f' && $f_transfer == 't') {
                return $this->db->query(
                    "SELECT
                        i_kum AS id, i_kum_id AS kode, b.e_area, v_jumlah::money as v_jumlah, 'TF' AS referensi
                    FROM
                        tm_kum a
                    INNER JOIN tr_area b ON (b.id = a.i_area)
                    WHERE
                        i_status = '6'
                        AND a.i_company = '$this->id_company'
                        AND i_kum_id ILIKE '%$cari%'
                        AND a.i_area = '$i_area'
                        AND i_kum NOT IN (
                        SELECT
                            i_rv_refference AS i_kum
                        FROM
                            tm_rv_item a,
                            tm_rv b
                        WHERE
                            b.i_rv = a.i_rv
                            AND b.i_status = '6'
                            AND i_rv_refference_type = '$i_rv_refference_type'
                            AND b.i_company = '$this->id_company'
                            AND i_rv_refference NOTNULL )
                    ORDER BY
                        1 DESC"
                );
            }
        }
    }

    public function get_detail_referensi($id, $i_rv_refference_type)
    {
        return $this->db->query(
            "SELECT * FROM
                (
                /* SELECT
                    i_giro AS id,
                    i_giro_id AS kode,
                    i_area as araa,
                    v_jumlah,
                    'GR' AS referensi
                FROM
                    tm_giro
                WHERE
                    f_giro_batal = 'f'
                    AND i_company = '$this->id_company'
                    AND f_giro_cair = 't'
            UNION ALL
                SELECT
                    i_st AS id,
                    i_st_id AS kode,
                    i_area as araa,
                    v_jumlah,
                    'TN' AS referensi
                FROM
                    tm_st
                WHERE
                    f_st_cancel = 'f'
                    AND i_company = '$this->id_company'
            UNION ALL */
                SELECT
                    i_kum AS id,
                    i_kum_id AS kode,
                    i_area as araa,
                    v_jumlah,
                    'TF' AS referensi
                FROM
                    tm_kum
                WHERE
                    i_status = '6'
                    AND i_company = '$this->id_company'
            ) AS x
            WHERE
                id = '$id'
                AND referensi = (
                SELECT
                    i_rv_refference_type_id
                FROM
                    tr_rv_refference_type
                WHERE
                    i_rv_refference_type = '$i_rv_refference_type')
            ORDER by 1 desc
        ",
            FALSE
        );
    }
    /*----------  SIMPAN DATA  ----------*/
    public function runningid()
    {
        $this->db->select('max(i_rv) AS id');
        $this->db->from('tm_rv');
        return $this->db->get()->row()->id + 1;
    }

    public function create_header($id, $i_bagian, $i_rv_id, $i_rv_type, $i_area, $i_coa, $d_rv, $v_rv, $e_remark)
    {
        $i_rv_id = $this->runningnumber(format_ym($d_rv), format_Y($d_rv), $i_bagian, $i_area, $i_rv_type, $i_coa, $id);
        $data = array(
            'i_rv' => $id,
            'i_company' => $this->id_company,
            'i_bagian' => $i_bagian,
            'i_rv_id' => $i_rv_id,
            'i_rv_type' => $i_rv_type,
            'i_area' => $i_area,
            'i_coa' => $i_coa,
            'd_rv' => $d_rv,
            'v_rv' => $v_rv,
            'e_remark' => $e_remark
        );
        $this->db->insert('tm_rv', $data);
    }

    public function create_detail($id, $i_area_item, $i_coa_item, $d_bukti, $e_coa_name, $v_rv_item, $e_remark_item, $no, $i_rv_refference_type, $i_rv_refference)
    {
        $data = array(
            'i_rv' => $id,
            'i_area' => $i_area_item,
            'i_coa' => $i_coa_item,
            'd_bukti' => $d_bukti,
            'e_coa_name' => $e_coa_name,
            'v_rv' => $v_rv_item,
            'v_rv_saldo' => $v_rv_item,
            'e_remark' => $e_remark_item,
            'n_item_no' => $no,
            'i_rv_refference_type' => $i_rv_refference_type,
            'i_rv_refference' => $i_rv_refference,
        );
        $this->db->insert('tm_rv_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query(
            "SELECT 
                a.*,
                to_char(d_rv, 'DD FMMonth YYYY') AS date_rv,
                b.i_area as i_area_id,b.e_area,c.i_coa as i_coa_id, c.e_coa_name,d.i_rv_type_id,d.e_rv_type_name,
                e.e_bagian_name
            FROM 
                tm_rv a
            INNER JOIN tr_area b ON 
                (b.id = a.i_area)
            INNER JOIN tr_coa c ON 
                (c.id = a.i_coa)
            INNER JOIN tr_rv_type d ON 
                (d.i_rv_type = a.i_rv_type)
            INNER JOIN tr_bagian e ON 
                (e.i_bagian = a.i_bagian AND a.i_company = e.id_company)
            WHERE a.i_rv = '$id'"
        );
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query(
            "SELECT
                a.*,
                to_char(d_bukti, 'DD FMMonth YYYY') AS date_bukti,
                d.i_coa as i_coa_id,
                d.e_coa_name,
                b.e_rv_refference_type_name,
                h.i_area as i_area_id,
                h.e_area,
                CASE 
                    /*WHEN b.f_tunai = 't' AND b.f_giro = 'f' AND b.f_transfer = 'f' THEN e.i_st_id|| ' #( '||e.v_jumlah||')' || ' # '||rr.e_area_name 
                    WHEN b.f_tunai = 'f' AND b.f_giro = 't' AND b.f_transfer = 'f' THEN f.i_giro_id|| ' #( '||f.v_jumlah||')'|| ' # '||rr.e_area_name*/
                    WHEN b.f_tunai = 'f' AND b.f_giro = 'f' AND b.f_transfer = 't' THEN g.i_kum_id|| ' - '||g.v_jumlah
                END AS i_referensi
            FROM
                tm_rv_item a
            INNER JOIN tr_coa d ON
                (d.id = a.i_coa)
            LEFT JOIN tr_rv_refference_type b ON
                (b.i_rv_refference_type = a.i_rv_refference_type)
            /*LEFT JOIN tm_st e ON 
                (e.i_st = a.i_rv_refference)*/
            /*LEFT JOIN tm_giro f ON 
                (f.i_giro = a.i_rv_refference)*/
            LEFT JOIN tm_kum g ON 
                (g.i_kum = a.i_rv_refference) 
            left join tr_area h on (h.id = a.i_area)
            WHERE
                i_rv = '$id'
            ORDER BY
                n_item_no ASC"
        );
    }


    public function update_header($id, $i_bagian, $i_rv_id, $i_rv_type, $i_area, $i_coa, $d_rv, $v_rv, $e_remark)
    {
        $i_rv_id = $this->runningnumber(format_ym($d_rv), format_Y($d_rv), $i_bagian, $i_area, $i_rv_type, $i_coa, $id);
        $data = array(
            'i_company' => $this->id_company,
            'i_bagian' => $i_bagian,
            'i_rv_id' => $i_rv_id,
            'i_rv_type' => $i_rv_type,
            'i_area' => $i_area,
            'i_coa' => $i_coa,
            'd_rv' => $d_rv,
            'v_rv' => $v_rv,
            'e_remark' => $e_remark,
            'd_update' => current_datetime()
        );
        $this->db->where('i_rv', $id);
        $this->db->update('tm_rv', $data);
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM tm_rv_item WHERE i_rv = '$id'");
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query(
                "SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_rv a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.i_rv = '$id'
				GROUP BY 1,2"
            )->row();
            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0) {
                    $data = array(
                        'i_status' => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan' => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status' => $istatus,
                        'i_approve_urutan' => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan' => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_rv');", FALSE);
            }
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('i_rv', $id);
        $this->db->update('tm_rv', $data);
    }

    public function updateprint($id)
    {
        $this->db->query("UPDATE tm_rv SET n_print = n_print + 1 WHERE i_rv = $id");
    }
}
/* End of file Mmaster.php */