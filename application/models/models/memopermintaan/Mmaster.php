<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    /*----------  DAFTAR DATA MASUK GUDANG JADI SESUAI GUDANG PEMBUAT  ----------*/

    function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_memo_permintaan a
            WHERE
                i_status <> '5'
                AND id_company = '$this->id_company'
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND id_company = '$this->id_company'
                        AND username = '$this->username')

        ", FALSE);
        if ($this->i_departement == '1') {
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
                        i_departement = '$this->i_departement'
                        AND id_company = '$this->id_company'
                        AND username = '$this->username')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                DISTINCT 0 AS NO,
                a.id AS id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian,
                e.e_bagian_name,
                d.e_type_name,
                g.i_bagian as i_tujuan,
                h.name as company_tujuan,
                e_status_name,
                label_color,
                a.i_status,
                l.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_memo_permintaan a
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            INNER JOIN tr_type d ON
                (d.id = a.id_type_penerima)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = a.i_bagian AND a.id_company = e.id_company)
            LEFT JOIN public.tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (f.i_level = l.i_level)
            LEFT JOIN tr_bagian g ON (g.id = a.i_tujuan)
            left join public.company h ON (h.id = g.id_company)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->id_company'
                $and
                $bagian
            ORDER BY
                a.id ASC
            ",
            FALSE
        );
        $datatables->edit('company_tujuan', function ($data) {
            $i_tujuan = $data['i_tujuan'];
            $company_tujuan = $data['company_tujuan'];
            $data = $i_tujuan . ' - ' . $company_tujuan;
            return $data;
        });
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = $data['id'];
            $i_status   = trim($data['i_status']);
            $i_level    = trim($data['i_level']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_bagian   = $data['i_bagian'];
            $data       = '';

            if (check_role($i_menu, 6)) {
                $data     .= "<a href=\"" . base_url($folder . '/cform/export_excel/' . $i_bagian . '/' . $dfrom . '/' . $dto . '/' . $id) . "\" title='Export'><i class='ti-download fa-lg mr-3 text-success'></i></a>";
            }

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-eye mr-2 fa-lg text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        $datatables->hide('i_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_bagian');
        $datatables->hide('i_tujuan');
        return $datatables->generate();
    }

    /*----------  BACA BAGIAN PEMBUAT  ----------*/

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

    /*----------  BACA BAGIAN PEMBUAT  ----------*/

    public function tujuan()
    {
        $id_company = $this->session->userdata("id_company");

        /* return $this->db->query("SELECT 
                a.*,
                b.e_bagian_name 
            FROM 
                tr_tujuan_menu a
            JOIN tr_bagian b 
            ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
            WHERE
                a.i_menu = '$i_menu'
                AND a.id_company = '$idcompany'
            ORDER BY 
                b.e_bagian_name"); */
        return $this->db->query(
            "SELECT * FROM (
                SELECT b.name, a.id, a.i_bagian, a.id_company, a.e_bagian_name 
                FROM tr_bagian a
                LEFT JOIN public.company b ON (b.id = a.id_company)
                WHERE a.id_company = '$id_company' AND a.f_status = 't' AND b.f_status = 't' AND b.i_apps = '2'
                UNION ALL
                SELECT d.name, c.id, c.i_bagian, c.id_company, c.e_bagian_name 
                FROM tr_bagian c
                LEFT JOIN public.company d ON (d.id = c.id_company)
                WHERE c.f_status = 't' AND c.i_type = '10' AND d.f_status = 't' AND d.i_apps = '2' AND c.id_company != '$id_company'
                AND (
                        SELECT array_agg(id) FROM tr_type_makloon 
                        WHERE e_type_makloon_name ILIKE '%MAKLOON JAHIT%'
                    ) && c.id_type_makloon
            ) x ORDER BY 1,5;");
    }

    public function type()
    {
        return $this->db->query("SELECT * FROM tr_type WHERE i_kode_group_barang NOTNULL AND f_status = 't'", false);
    }

    public function product_wip($cari)
    {
        return $this->db->query("SELECT DISTINCT
                c.id,
                c.i_product_wip i_product,
                c.e_product_wipname e_product,
                d.e_color_name
            FROM tr_product_wip c
            LEFT JOIN tr_color d ON (d.i_color = c.i_color AND c.id_company = d.id_company)
            WHERE
                c.id_company = '$this->id_company'
                AND (c.i_product_wip ILIKE '%$cari%' OR c.e_product_wipname ILIKE '%$cari%')
            ORDER BY 2,3,4");
    }


    /*----------  CARI MARKER  ----------*/

    public function marker($cari, $id_product_wip)
    {
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query("SELECT
            DISTINCT a.id_marker,
            b.e_marker_name
        FROM
            tr_polacutting_new a
        INNER JOIN tr_marker b ON
            (b.id = a.id_marker)
        WHERE
            a.id_company = '$idcompany'
            AND a.f_status = 't'
            AND
            id_product_wip = '$id_product_wip' 
            AND b.e_marker_name ILIKE '%$cari%';", false);
    }

    public function material($cari, $id_product, $id_type, $id_marker)
    {
        // $where = '';
        // if($id_type == '2') {
        //     $where = "AND f_jahit = 't'";
        // } else if ($id_type == '3') {
        //     $where = "AND f_packing = 't'";
        // }
        return $this->db->query("SELECT DISTINCT
                d.id,
                d.i_material,
                d.e_material_name,
                e.e_satuan_name,
                f.e_nama_group_barang e_group   
            FROM
                tr_material d
            INNER JOIN tr_satuan e ON (
                e.i_satuan_code = d.i_satuan_code AND d.id_company = e.id_company
            )
            INNER JOIN tr_group_barang f ON (
                f.i_kode_group_barang = d.i_kode_group_barang AND d.id_company = f.id_company
            )
            INNER JOIN tr_polacutting_new g ON
            (
                g.id_material = d.id
                AND g.id_company = e.id_company
            )
            WHERE
                d.id_company = '$this->id_company'
                AND (d.i_material ILIKE '%$cari%'
                    OR d.e_material_name ILIKE '%$cari%')
                AND d.id IN (
                    SELECT id_material FROM tr_polacutting_new WHERE id_product_wip = '$id_product' /* AND (f_jahit = 't' OR f_packing = 't') */
                )
                AND ( d.i_kode_group_barang IN (
                    SELECT i_kode_group_barang FROM tr_type WHERE id = '$id_type'
                ) OR (g.f_jahit = 't' AND g.f_packing = 't') )
                AND g.id_marker = '$id_marker'
            ORDER BY
                2,3,4");
    }

    public function detail_material($id_material, $id_product_wip)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("SELECT
                round(1 / v_set * v_gelar,4) AS n_kebutuhan,
                COALESCE (n_saldo_akhir,0) n_saldo_akhir
            FROM tr_polacutting_new a
            LEFT JOIN f_mutasi_material('$this->id_company', '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '') b ON (b.id_material = a.id_material) 
            WHERE
                a.id_company = '$this->id_company'
                AND a.id_material = '$id_material'
                AND a.id_product_wip = '$id_product_wip' ");
    }

    // public function detail_material_upload($id_product_wip, $id_type, $id_marker)
    // {
    //     $today = date('Y-m-d');
    //     $jangkaawal = date('Y-m-01');
    //     $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
    //     $periode = date('Ym');
    //     return $this->db->query("SELECT
    //             c.id AS id_material,
    //             c.i_material,
    //             c.e_material_name,
    //             a.id_product_wip,
    //             a.id_material,
    //             a.id_marker,
    //             round(1 / v_set * v_gelar, 4) AS n_kebutuhan,
    //             COALESCE (n_saldo_akhir,
    //             0) n_saldo_akhir
    //         FROM
    //             tr_polacutting_new a
    //         LEFT JOIN f_mutasi_material('$this->id_company', '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '') b ON (b.id_material = a.id_material) 
    //         LEFT JOIN tr_material c ON
    //             (c.id = a.id_material
    //                 AND c.id_company = a.id_company)
    //         INNER JOIN tr_group_barang f ON
    //             (
    //                 f.i_kode_group_barang = c.i_kode_group_barang
    //                 AND c.id_company = f.id_company
    //             )
    //         WHERE
    //             a.id_company = '$this->id_company'
    //             AND a.id_product_wip = $id_product_wip
    //             AND ( c.i_kode_group_barang IN (
    //                 SELECT
    //                     i_kode_group_barang
    //                 FROM
    //                     tr_type
    //                 WHERE
    //                     id = '$id_type'
    //                         )
    //                 OR (a.f_jahit = 't'
    //                     AND a.f_packing = 't') )
    //             ");
    // }

    public function detail_material_onchange($id_marker, $id_product_wip, $id_type)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("SELECT DISTINCT
                a.id_material,
                c.i_material,
                c.e_material_name,
                a.id_product_wip,
                e.e_satuan_name,
                a.id_marker,
                sum(round(1 / v_set * v_gelar, 4)) AS n_kebutuhan,
                COALESCE (n_saldo_akhir,
                0) n_saldo_akhir
            FROM
                tr_polacutting_new a
            LEFT JOIN f_mutasi_material('$this->id_company', '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '') b ON (b.id_material = a.id_material) 
            LEFT JOIN tr_material c ON
                (c.id = a.id_material
                    AND c.id_company = a.id_company)
            INNER JOIN tr_satuan e ON (
                e.i_satuan_code = c.i_satuan_code AND c.id_company = e.id_company
            )
            INNER JOIN tr_group_barang f ON
                (
                    f.i_kode_group_barang = c.i_kode_group_barang
                    AND c.id_company = f.id_company
                )
            WHERE
                a.id_company = '$this->id_company'
                AND a.id_marker = '$id_marker'
                AND a.id_product_wip = '$id_product_wip'
                and a.v_gelar > 0
                AND ( c.i_kode_group_barang IN (
                    SELECT
                        i_kode_group_barang
                    FROM
                        tr_type
                    WHERE
                        id = '$id_type'
                            )
                    OR (a.f_jahit = 't'
                        AND a.f_packing = 't') )
            GROUP BY 1,2,3,4,5,6,8;
                ");
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_memo_permintaan 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'MM';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 9, 4)) AS max
            FROM
                tm_memo_permintaan
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
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

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_memo_permintaan');
        return $this->db->get()->row()->id + 1;
    }

    public function simpan_header($id, $i_document, $d_document, $d_kirim, $i_bagian, $i_tujuan, $e_remark, $id_type)
    {
        $data_header = array(
            'id' => $id,
            'id_company' => $this->id_company,
            'i_document' => $i_document,
            'd_document' => $d_document,
            'd_kirim' => $d_kirim,
            'i_bagian' => $i_bagian,
            'i_tujuan' => $i_tujuan,
            'id_type_penerima' => $id_type,
            'e_remark' => $e_remark,
        );
        $this->db->insert("tm_memo_permintaan", $data_header);
    }

    public function simpan_detail($id, $id_product, $id_marker, $id_material, $n_quantity_product, $n_kebutuhan_material, $e_note)
    {
        $data_detail = array(
            "id_document" => $id,
            "id_product" => $id_product,
            "id_marker" => $id_marker,
            "id_material" => $id_material,
            "n_quantity" => $n_kebutuhan_material,
            "n_quantity_product" => $n_quantity_product,
            "n_quantity_sisa" => $n_kebutuhan_material,
            "e_remark" => $e_note,
        );
        $this->db->insert("tm_memo_permintaan_item", $data_detail);
    }

    public function data_header($id)
    {
        $this->db->select("a.*, b.e_bagian_name, c.e_type_name, d.e_bagian_name as e_tujuan_name, e.name as company_tujuan");
        $this->db->from("tm_memo_permintaan a");
        $this->db->join("tr_bagian b", "b.i_bagian = a.i_bagian AND a.id_company = b.id_company");
        $this->db->join("tr_type c", "c.id = a.id_type_penerima");
        $this->db->join("tr_bagian d", "d.id = a.i_tujuan", 'left');
        $this->db->join("public.company e", "e.id = d.id_company", 'left');
        $this->db->where("a.id", $id);
        return $this->db->get();
    }

    public function get_product_polacutting()
    {
        return $this->db->query(
            "SELECT DISTINCT
                a.id_product_wip, b.i_product_wip, b.e_product_wipname, ba.e_color_name, a.id_marker, c.e_marker_name 
            FROM
                tr_polacutting_new a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip
                    AND b.id_company = a.id_company)
            INNER JOIN tr_color ba ON
                (ba.i_color = b.i_color
                    AND ba.id_company = b.id_company)
            INNER JOIN tr_marker c ON
                (c.id = a.id_marker)
            /* WHERE
                a.f_status = 't'
                AND a.f_marker_utama = 't' */
            ORDER BY b.i_product_wip, b.e_product_wipname, ba.e_color_name;"
        );
    }

    public function data_detail($id, $i_bagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query(
            "SELECT a.*, b.i_product_wip i_product, b.e_product_wipname e_product, c.e_color_name, d.i_material, d.e_material_name, e.e_satuan_name, a.id_marker, h.e_marker_name,
            round(1 / v_set * v_gelar,4) n_kebutuhan, coalesce(n_saldo_akhir,0) n_saldo_akhir
            FROM tm_memo_permintaan_item a
            INNER JOIN tr_product_wip b ON (b.id = a.id_product)
            INNER JOIN tr_color c ON (
                c.i_color = b.i_color AND b.id_company = c.id_company
            )
            INNER JOIN tr_material d ON (d.id = a.id_material)
            INNER JOIN tr_satuan e ON (
                e.i_satuan_code = d.i_satuan_code AND d.id_company = e.id_company
            )
            INNER JOIN tr_polacutting_new f ON (
                f.id_product_wip = a.id_product AND a.id_material = f.id_material AND a.id_marker = f.id_marker
            )
            LEFT JOIN f_mutasi_material('$this->id_company', '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$i_bagian') g ON (g.id_material = a.id_material) 
            inner join tr_marker h ON (h.id = a.id_marker) 
            WHERE a.id_document = '$id'
            and f.v_gelar > 0
            ORDER BY a.id_product
            "
        );
    }

    /*---------- GET DETAIL FOR EXPORT EXCEL ----------*/
    public function datadetail_header($id)
    {
        $this->db->select("a.*, to_char(a.d_document, 'YYYYMM') AS periode, b.e_bagian_name, c.e_type_name, d.e_bagian_name as e_tujuan_name, e.name as company_tujuan, ab.name as company_name, f.e_status_name");
        $this->db->from("tm_memo_permintaan a");
        $this->db->join("tr_bagian b", "b.i_bagian = a.i_bagian AND a.id_company = b.id_company");
        $this->db->join("public.company ab", "ab.id = a.id_company", 'left');
        $this->db->join("tr_type c", "c.id = a.id_type_penerima");
        $this->db->join("tr_bagian d", "d.id = a.i_tujuan", 'left');
        $this->db->join("public.company e", "e.id = d.id_company", 'left');
        $this->db->join("tr_status_document f", "f.i_status = a.i_status");
        $this->db->where("a.id", $id);
        return $this->db->get();
    }
    public function datadetail_edit($id)
    {
        return $this->db->query("SELECT DISTINCT a.id_product, to_char(ab.d_document, 'YYYYMM') AS periode, to_char(ab.d_document, 'DD-Mon') AS tgl_dokumen, to_char(ab.d_kirim, 'DD-Mon') AS tgl_kirim, d.e_bagian_name AS i_tujuan, e.name AS company_tujuan, b.i_product_wip i_product, b.e_product_wipname e_product, c.e_color_name, a.n_quantity_product
            FROM tm_memo_permintaan_item a
            INNER JOIN tm_memo_permintaan ab ON (ab.id = a.id_document)
            INNER JOIN tr_product_wip b ON (b.id = a.id_product)
            INNER JOIN tr_color c ON (c.i_color = b.i_color AND b.id_company = c.id_company)
            LEFT JOIN tr_bagian d ON (d.id = ab.i_tujuan)
            LEFT JOIN public.company e ON (e.id = d.id_company)
            WHERE a.id_document = '$id'
            ORDER BY a.id_product;
        ");
    }

    public function update_header($id, $i_document, $d_document, $d_kirim, $i_bagian, $i_tujuan, $e_remark, $id_type)
    {
        $data_header = array(
            'd_document' => $d_document,
            'd_kirim' => $d_kirim,
            'i_bagian' => $i_bagian,
            'i_tujuan' => $i_tujuan,
            'id_type_penerima' => $id_type,
            'e_remark' => $e_remark,
        );
        $this->db->where("id", $id);
        $this->db->update("tm_memo_permintaan", $data_header);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/

    public function delete_detail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_memo_permintaan_item');
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_memo_permintaan a
                inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
                where a.id = '$id'
                group by 1,2", FALSE)->row();
            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0) {
                    $data = array(
                        'i_status'  => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->session->userdata('username'),
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_memo_permintaan');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_memo_permintaan', $data);
    }
}
/* End of file Mmaster.php */