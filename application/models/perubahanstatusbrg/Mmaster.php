<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($folder, $i_menu, $dfrom, $dto)
    {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_konversi_grade
            WHERE
                i_status <> '5'
                and d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND username = '" . $this->session->userdata('username') . "'
                        AND id_company = '$id_company')

        ", FALSE);
        if ($this->session->userdata('i_departement') == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND username = '" . $this->session->userdata('username') . "'
                        AND id_company = '$id_company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                d.e_bagian_name,
                a.e_remark,
                a.i_status,
                c.e_status_name,
                l.i_level,
                l.e_level_name,
                '$i_menu' as i_menu,
                '$folder' as folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                c.label_color 
            FROM
                tm_konversi_grade a 
            INNER JOIN tr_status_document c 
                ON (c.i_status = a.i_status) 
            INNER JOIN tr_bagian d 
                ON (a.i_bagian = d.i_bagian 
                AND a.id_company = d.id_company) 
            LEFT JOIN public.tr_menu_approve e on 
                (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on 
                (e.i_level = l.i_level)
            WHERE
                a.i_status <> '5' 
                AND a.d_document BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                AND a.id_company = '$id_company' 
                $bagian 
            ORDER BY
                a.d_document DESC
            ", FALSE);

        /* $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        }); */
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $i_level  = $data['i_level'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';

            if (check_role($i_menu, 2)) {
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye mr-3 fa-lg text-success'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if ($i_level == $this->level || 1 == $this->level) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box mr-3 fa-lg text-primary'></i></a>";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian', 'inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('b.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function kelompok($cari, $ibagian)
    {
        $cari = str_replace("'", "", $cari);
        if ($this->departement != '1') {
            $bagian = "AND i_bagian = '$ibagian'";
        } else {
            $bagian = "";
        }
        return $this->db->query("SELECT DISTINCT
                i_kode_kelompok,
                e_nama_kelompok
            FROM
                tr_kelompok_barang
            WHERE
                f_status = 't'
                /* AND i_kode_kelompok IN (
                SELECT
                    i_kode_kelompok
                FROM
                    tr_bagian_kelompokbarang
                WHERE
                    e_nama_kelompok ILIKE '%$cari%'
                    AND id_company = '" . $this->session->userdata('id_company') . "'
                    $bagian ) */
                AND id_company = '$this->company'
            ORDER BY
                e_nama_kelompok
        ", FALSE);
    }

    public function jenis($cari, $ikelompok, $ibagian)
    {
        $jenis = "";
        /* if ($this->departement != '1') {
        } */
        if (($ikelompok != '' || $ikelompok != null) && $ikelompok != 'all') {
            $jenis = "AND i_kode_kelompok = '$ikelompok' ";
        } else {
            $jenis = "";
        }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT
                DISTINCT i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                e_type_name ILIKE '%$cari%'
                AND f_status = 't'
                AND id_company = '$this->company'
                $jenis
            ORDER BY
                e_type_name
        ", FALSE);
    }

    public function material($cari, $ikategori, $ijenis, $ibagian)
    {
        $kategori = "";
        $jenis    = "";
        if (($ikategori != '' || $ikategori != null) && $ikategori != 'all') {
            $kategori = "AND i_kode_kelompok = '$ikategori' ";
        } else {
            $kategori = "";
        }

        if (($ijenis != '' || $ijenis != null) && $ijenis != 'all') {
            $jenis = "AND i_type_code = '$ijenis' ";
        } else {
            $jenis = "";
        }
        return $this->db->query("SELECT
                a.id,
                a.i_product_base,
                a.e_product_basename||' '||c.e_color_name e_product_basename,
                a.i_kode_kelompok,
                b.e_satuan_name,
                a.i_satuan_code
            FROM
                tr_product_base a 
            INNER JOIN tr_satuan b ON (
                b.i_satuan_code = a.i_satuan_code 
                AND a.id_company = b.id_company
            )
            INNER JOIN tr_color c ON (
                c.i_color = a.i_color 
                AND a.id_company = c.id_company
            )
            WHERE
                a.f_status = 't'
                AND (
                    i_product_base ILIKE '%$cari%' OR e_product_basename ILIKE '%$cari%'
                )
                AND a.id_company = '$this->company'
                $kategori
                $jenis
            ORDER BY
                i_product_base
        ", FALSE);
    }

    public function getmaterial($imaterial)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_product_base,
                a.e_product_basename,
                a.i_kode_kelompok,
                b.e_satuan_name,
                a.i_satuan_code
            FROM
                tr_product_base a,
                tr_satuan b
            WHERE
                a.i_satuan_code = b.i_satuan_code
                AND a.f_status = 't'
                AND a.id = $imaterial
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
                AND b.id_company = '" . $this->session->userdata('id_company') . "'
        ", FALSE);
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("SELECT  substring(i_document, 1, 3) AS kode 
            FROM tm_konversi_grade 
            WHERE i_status <> '5' AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'PSB';
        }
        $query  = $this->db->query("SELECT max(substring(i_document, 10, 4)) AS max
            FROM tm_konversi_grade
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5' AND i_bagian = '$ibagian'
            AND id_company = '$id_company' AND i_document ILIKE '%$kode%'
        ", false);
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
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_konversi_grade');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_konversi_grade');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  SIMPAN DATA  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_konversi_grade');
        return $this->db->get()->row()->id + 1;
    }

    public function simpan($id, $idocument, $ddocument, $ibagian, $eremarkh)
    {
        $data = array(
            'id'                => $id,
            'id_company'        => $this->session->userdata('id_company'),
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'e_remark'          => $eremarkh,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_konversi_grade', $data);
    }

    public function simpandetail($id, $imaterial, $nquantity, $igradeawal, $igradeakhir, $eremark)
    {
        $data = array(
            'id_company'        => $this->session->userdata('id_company'),
            'id_document'       => $id,
            'id_product_base'   => $imaterial,
            'n_quantity'        => $nquantity,
            'i_grade_awal'      => $igradeawal,
            'i_grade_akhir'     => $igradeakhir,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_konversi_grade_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
                                    SELECT
                                       a.id,
                                       a.i_bagian,
                                       a.i_document,
                                       to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                       a.e_remark,
                                       b.e_bagian_name,
                                       a.i_status 
                                    FROM
                                       tm_konversi_grade a 
                                       INNER JOIN
                                          tr_bagian b 
                                          ON (b.i_bagian = a.i_bagian 
                                          AND a.id_company = b.id_company) 
                                    WHERE
                                       a.id = '$id' 
                                       AND a.id_company = '" . $this->session->userdata('id_company') . "'
                                            ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
                                    SELECT
                                       a.id_product_base,
                                       c.i_product_base,
                                       c.e_product_basename,
                                       a.n_quantity,
                                       a.i_grade_awal,
                                       a.i_grade_akhir,
                                       a.e_remark 
                                    FROM
                                       tm_konversi_grade_item a 
                                       INNER JOIN
                                          tr_product_base c 
                                          ON (c.id = a.id_product_base) 
                                    WHERE
                                       a.id_document = '$id' 
                                       AND a.id_company = '" . $this->session->userdata('id_company') . "' 
                                    ORDER BY
                                       a.id_product_base ASC
                                ", FALSE);
    }

    public function updateheader($id, $idocument, $ddocument, $ibagian, $eremarkh)
    {
        $data = array(
            'i_document'   => $idocument,
            'd_document'   => $ddocument,
            'i_bagian'     => $ibagian,
            'e_remark'     => $eremarkh,
            'd_update'     => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_konversi_grade', $data);
    }

    public function deletedetail($id)
    {
        $this->db->query("DELETE FROM tm_konversi_grade_item WHERE id_document='$id'", false);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_konversi_grade', $data);
    }
}
/* End of file Mmaster.php */