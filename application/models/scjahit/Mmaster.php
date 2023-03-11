<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }


        if ($this->session->userdata("i_departement") == "1") {
            $bagian = "";
        } else {
            $bagian = " AND a.i_bagian IN ( SELECT i_bagian FROM tr_departement_cover WHERE
                        i_departement = '$this->i_departement' AND username = '$this->username' AND id_company = '$this->id_company')";
            
        }

        $datatables->query("SELECT
                DISTINCT
                0 AS NO,
                a.id,
                a.i_document,
                a.d_document,
                a.i_bagian,
                b.e_bagian_name,
                a.e_group_jahit,
                a.e_remark,
                a.i_status,
                l.i_level,
                l.e_level_name,
                e_status_name,
                label_color,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_schedule_jahit a            
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            LEFT JOIN tr_bagian b
                ON (b.i_bagian = a.i_bagian AND b.id_company = a.id_company)
            LEFT JOIN public.tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (e.i_level = l.i_level)
            WHERE a.i_status <> '5' AND a.id_company = '$this->id_company' $bagian $and
            ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id      = $data['id'];
            $idocument = trim($data['i_document']);
            $i_menu  = $data['i_menu'];
            $i_status = $data['i_status'];
            $i_level = $data['i_level'];
            $folder  = $data['folder'];
            $dfrom  = $data['dfrom'];
            $dto  = $data['dto'];
            $data    = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && ($i_status == '2')) {
                if (($i_level == $this->session->userdata('i_level')) || $this->session->userdata('i_level') == 1) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 3)  && ($i_status == '6')) {
                $data .= "<a href=\"#\" title='Realisasi' onclick='show(\"$folder/cform/realisasi/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-check-circle-o fa-lg mr-3 text-warning'></i></a>";
            }
            if (check_role($i_menu, 6)) {
                $data .= "<a href=\"" . base_url($folder . '/cform/download/' . $id) . "\" title='Export'><i class='ti-download text-success fa-lg mr-3'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_bagian');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('i_status');
        $datatables->hide('e_level_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');

        return $datatables->generate();
    }

    public function status(/* $iproduct, $icolor,  */$id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('f_status');
        $this->db->from('tr_polacutting_new');
        $this->db->where('id_product_wip', $id);
        $this->db->limit(1, 'ASC');
        /* $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor); */
        $this->db->where('id_company', $idcompany);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row    = $query->row();
            $status = $row->f_status;
            if ($status == 't') {
                $stat = 'f';
            } else {
                $stat = 't';
            }
        }
        $data = array(
            'f_status' => $stat
        );
        $this->db->where('id_product_wip', $id);
        /* $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor); */
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_polacutting_new', $data);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function runningnumber($thbl, $ibagian)
    {
        $cek = $this->db->query("SELECT substring(i_document, 1, 3) AS kode 
            FROM tm_schedule_jahit 
            WHERE i_status <> '5' AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SCJ';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_schedule_jahit
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            AND i_document ILIKE '%$kode%'
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

    public function productwip($cari, $id_referensi)
    {
        return $this->db->query("SELECT 
                a.id,
                i_product_wip,
                upper(e_product_wipname||' '||e_color_name) AS e_product_wipname
            FROM tr_product_wip a
            INNER JOIN tr_color b ON (b.i_color = a.i_color AND a.id_company = b.id_company)
            INNER JOIN tm_uraianjahit_item c ON (c.id_product_wip = a.id)
            WHERE 
                a.f_status = 't'
                AND c.id_document = '$id_referensi'
                -- AND a.id_company = '$this->id_company'
                and c.n_quantity_sisa > 0
                AND (i_product_wip ILIKE '%$cari%' OR e_product_wipname ILIKE '%$cari%')
            ORDER BY i_product_wip
        ", FALSE);
    }

    public function get_detail_wip($id_product, $id_referensi)
    {
        return $this->db->query("SELECT 
                a.id,
                i_product_wip,
                upper(e_product_wipname) AS e_product_wipname,
                upper(e_color_name) AS e_color_name,
                COALESCE((SELECT sum(n_quantity_sisa) n_quantity FROM tm_uraianjahit_item 
                WHERE id_document = '$id_referensi' AND id_product_wip = '$id_product'),0) n_quantity
            FROM tr_product_wip a, tr_color b
            WHERE 
                a.i_color = b.i_color AND a.id_company = b.id_company
                AND a.f_status = 't'
                -- AND a.id_company = '$this->id_company'
                AND a.id = '$id_product'
        ", FALSE);
    }

    public function get_bisbisan($cari, $i_material)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.id,
                n_bisbisan,
                b.e_jenis_potong
            FROM
                tr_material_bisbisan a
            INNER JOIN tr_jenis_potong b ON
                (b.id = a.id_jenis_potong)
            INNER JOIN tr_material c ON 
                (c.id = a.id_material)
            WHERE
                c.i_material = '$i_material'
                AND c.id_company = '$idcompany'
                AND (b.e_jenis_potong ILIKE '%$cari%')
                AND a.f_status = 't'
            ORDER BY 3,2
        ", FALSE);
    }

    public function productwipref($cari, $i_product_wip, $i_color)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                DISTINCT 
                a.i_product_wip,
                initcap(e_product_wipname) AS e_product_wipname,
                a.i_color,
                initcap(e_color_name) AS e_color_name
            FROM
                tr_product_wip a
            INNER JOIN tr_polacutting_new b ON
                (b.id_product_wip = a.id)
            INNER JOIN tr_color c ON
                (c.i_color = a.i_color
                    AND a.id_company = c.id_company)
            WHERE
                a.f_status = 't'
                AND a.id_company = '$idcompany'
                AND a.i_product_wip = '$i_product_wip'
                AND a.i_color <> '$i_color'
                AND (a.i_product_wip ILIKE '%$cari%'
                    OR e_product_wipname ILIKE '%$cari%'
                    OR e_color_name ILIKE '%$cari%')
            ORDER BY
                i_product_wip
        ", FALSE);
    }

    public function getdetailref($i_product_wip, $i_color)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                DISTINCT 
                a.*,
                e.i_material,
                e.e_material_name,
                v_bisbisan,
                n_bisbisan,
                c.e_jenis_potong,
                a.e_bagian AS bagian,
                string_agg(trim(e_bagian_name),', ') AS gudang
            FROM
                tr_polacutting_new a
            LEFT JOIN tr_material_bisbisan b ON
                (b.id_material = a.id_material
                    AND a.id_bisbisan = b.id)
            LEFT JOIN tr_material e ON 
                (e.id = a.id_material)
            LEFT JOIN tr_jenis_potong c ON
                (c.id = b.id_jenis_potong)
            LEFT JOIN tr_product_wip d ON
                (d.id = a.id_product_wip)
            LEFT JOIN tr_bagian_kelompokbarang h ON (h.i_kode_kelompok = e.i_kode_kelompok AND e.id_company = h.id_company)
            LEFT JOIN tr_bagian i ON (i.i_bagian = h.i_bagian AND h.id_company = i.id_company)
            WHERE
                a.f_status = 't'
                AND a.id_company = '$idcompany'
                AND d.i_product_wip = '$i_product_wip'
                AND d.i_color = '$i_color'
            GROUP BY 1,e.i_material,e.e_material_name,n_bisbisan,c.e_jenis_potong,a.e_bagian
            ORDER BY
                e.e_material_name ASC
        ", FALSE);
    }

    public function material($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT 
                i_material,
                e_material_name,
                b.e_satuan_name
            FROM tr_material a
            INNER JOIN tr_satuan b ON (b.i_satuan_code = a.i_satuan_code AND a.id_company = b.id_company)
            WHERE 
                a.id_company = '$idcompany'
                AND
                a.f_status = 't'
                AND (i_material ILIKE '%$cari%' 
                     OR e_material_name ILIKE '%$cari%')
                AND i_kode_group_barang NOT IN ('GRB0003')
            ORDER BY i_material
        ", FALSE);
    }

    public function getkategori()
    {
        return $this->db->query("SELECT id,e_nama_kategori FROM tr_kategori_jahit WHERE f_status = 't'
        ", false);
    }

    public function getunit($cari, $kategori)
    {
        return $this->db->query("SELECT id,e_nama_unit FROM tr_unit_jahit WHERE id_kategori_jahit = '$kategori' AND e_nama_unit ILIKE '%$cari%'  AND f_status = 't'
        ", false);
    }

    public function getdetailmaterial($i_material)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT i_material, string_agg(trim(e_bagian_name),', ') AS e_bagian_name  FROM tr_material a
            LEFT JOIN tr_bagian_kelompokbarang b ON (b.i_kode_kelompok = a.i_kode_kelompok AND a.id_company = b.id_company)
            LEFT JOIN tr_bagian c ON (c.i_bagian = b.i_bagian AND b.id_company = c.id_company)
            WHERE i_material = '$i_material' AND a.f_status = 't'
            AND a.id_company = '$idcompany'
            GROUP BY 1
        ", FALSE);
    }

    public function cekdata($iproduct, $icolor, $imaterial)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_product_wip, i_material, i_color');
        $this->db->from('tr_polacutting');
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_material', $imaterial);
        $this->db->where('i_color', $icolor);
        return $this->db->get();
    }

    /* public function insertdetail($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproductwip,$icolor,$n_bagibis,$bagian,$bis3,$bis4){
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_product_wip' => $iproductwip,
            'i_material'    => $imaterial,
            'i_color'       => $icolor,
            'v_gelar'       => $vgelar,
            'v_set'         => $vset,
            'f_bisbisan'    => $fbis,
            'v_toset'       => $vtoset,
            'n_bagibis'     => $n_bagibis,
            'id_company'    => $idcompany,
            'd_entry'       => current_datetime(),
            'bagian'        => $bagian,
            'n_bis3'        => $bis3,
            'n_bis4_5'      => $bis4,
        );
        $this->db->insert('tr_polacutting', $data);
    } */

    /*----------  RUNNING ID  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_schedule_jahit');
        return $this->db->get()->row()->id + 1;
    }

    public function insertheader($id, $idocument, $ddocument, $ibagian, $keterangan, $id_referensi, $group_jahit, $id_company_referensi)
    {
        // $tgl = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 4);
        $i_periode = $this->db->query("SELECT i_periode FROM tm_uraianjahit WHERE id = '$id_referensi'")->row()->i_periode;
        $this->db->query("INSERT 
        INTO tm_schedule_jahit(id, i_document, d_document, i_bagian, e_remark, d_entry, id_referensi, id_company, e_group_jahit, i_periode, id_company_referensi)
        VALUES ($id,'$idocument', '$ddocument', '$ibagian', '$keterangan', now(), '$id_referensi','$this->id_company','$group_jahit', '$i_periode', '$id_company_referensi')
        ");
    }


    public function insertdetail($id, $id_product, $n_quantity, $e_note, $f_uraian, $d_schedule)
    {
        $data = array(
            'id_document' => $id,
            'id_product_wip' => $id_product,
            'n_quantity' => $n_quantity,
            'e_remark' => $e_note,
            'f_uraian_jahit' => $f_uraian,
            'd_schedule' => $d_schedule
        );
        $this->db->insert('tm_schedule_jahit_item_new', $data);
        /* $this->db->query("INSERT 
        INTO tm_schedule_jahit_item(i_document, d_schedule, id_product_wip, i_color, n_quantity_wip, id_kategori_jahit, id_unit_jahit, e_remark) 
        VALUES ('$idocument', '$tanggal', '$ibarang', '$icolor', '$nqty', '$ikategori', '$iunit', '$eremark')
        "); */
    }

    public function updateheader($id, $idocument, $ddocument, $ibagian, $keterangan, $id_referensi, $group_jahit, $id_company_referensi)
    {
        // $tgl = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 4);

        $this->db->query("UPDATE tm_schedule_jahit
        SET d_document = '$ddocument', i_bagian = '$ibagian', e_remark = '$keterangan', id_referensi = '$id_referensi', e_group_jahit = '$group_jahit', id_company_referensi = '$id_company_referensi', i_status = '1', d_update = now()
        WHERE id = '$id'
        ");
    }

    public function updatedetail($idocument, $tanggal, $ibarang, $icolor, $nqty, $ikategori, $iunit, $eremark)
    {
        $this->db->query("INSERT 
        INTO tm_schedule_jahit_item(i_document, d_schedule, id_product_wip, i_color, n_quantity_wip, id_kategori_jahit, id_unit_jahit, e_remark) 
        VALUES ('$idocument', '$tanggal', '$ibarang', '$icolor', '$nqty', '$ikategori', '$iunit', '$eremark')
        ");
    }

    public function dataheader($idocument)
    {
        // $this->db->select('a.*')->distinct();
        return $this->db->query("SELECT 
            a.*,
            c.i_document i_document_referensi,
            /* to_char(c.d_document,'DD FMMonth YYYY') AS periode, */
            to_char(to_date(a.i_periode,'yyyymm'), 'FMMonth YYYY') periode,
            b.e_bagian_name
        FROM tm_schedule_jahit a
        LEFT JOIN tr_bagian b
        ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
        LEFT JOIN tm_uraianjahit c
        ON (c.id = a.id_referensi)
        WHERE a.id = '$idocument'");
    }

    public function detail($idocument)
    {
        /* return $this->db->query("SELECT DISTINCT
        a.*,
        b.i_product_wip,
        b.e_product_wipname,
        c.i_color,
        c.e_color_name,
        d.e_nama_kategori,
        e.e_nama_unit
        FROM tm_schedule_jahit_item a
        INNER JOIN tr_product_wip b
        ON (b.id = a.id_product_wip AND b.i_color = a.i_color)
        INNER JOIN tr_color c
        ON (c.i_color = a.i_color AND c.id_company = b.id_company)
        LEFT JOIN tr_kategori_jahit d
        ON (d.id = a.id_kategori_jahit)
        LEFT JOIN tr_unit_jahit e
        ON (e.id = a.id_unit_jahit)
        WHERE a.i_document = '$idocument' "); */
        return $this->db->query("SELECT
                DISTINCT a.*,
                b.i_product_wip,
                b.e_product_wipname,
                c.i_color,
                c.e_color_name,
                COALESCE (e.n_quantity,
                0) n_quantity_uraian,
                COALESCE (e.n_quantity_sisa,
                0) n_quantity_uraian_sisa
            FROM
                tm_schedule_jahit_item_new a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color
                    AND c.id_company = b.id_company)
            INNER JOIN tm_schedule_jahit d ON
                (d.id = a.id_document)
            LEFT JOIN tm_uraianjahit_item e ON
                (e.id_document = d.id_referensi
                    AND a.id_product_wip = e.id_product_wip)
            WHERE
                a.id_document = '$idocument'
            ORDER BY
                a.d_schedule DESC,
                b.i_product_wip,
                b.e_product_wipname,
                c.e_color_name ASC");
    }

    public function getdataitem()
    {
        $this->db->select("a.*");
        $this->db->from('tm_schedule_jahit_item a');
        return $this->db->get();
    }

    public function delete($iproduct, $icolor, $cek)
    {
        $idcompany  = $this->session->userdata('id_company');
        if ($cek != 'on') {
            $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        } else {
            $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' /* AND i_color = '$icolor' */ AND id_company = '$idcompany' ", FALSE);
        }
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $this->db->where('id_product_wip', $row->id);
                $this->db->delete('tr_polacutting_new');
            }
        }
        /* if($cek!='on'){

            $this->db->where('id_product_wip', $iproduct);
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_polacutting_new');
        }else{
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_polacutting_new');
        } */
    }

    /* public function delete($iproduct,$icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tr_polacutting');
    } */

    public function deletewip($iproduct, $icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        if ($query->num_rows() > 0) {
            $this->db->where('id_product_wip', $query->row()->id);
            /* $this->db->where('i_color', $icolor); */
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_product_wip_item');
        }
    }

    public function deletedetail($idocument)
    {
        $this->db->where('id_document', $idocument);
        $this->db->delete('tm_schedule_jahit_item_new');
    }

    public function getformat()
    {
        return $this->db->query("SELECT i_document FROM tm_schedule_jahit ORDER BY i_document DESC LIMIT 1");
    }

    public function bagian()
    {
        return $this->db->query("
				SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
                AND a.i_type = '10'
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function bagianpembuat()
    {
        return $this->db->query("
                SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
                INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
                LEFT JOIN tr_type c on (a.i_type = c.i_type)
                LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
                WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
                ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    /* DATA REFERENSI */
    public function get_data_referensi($cari, $ibagian)
    {
        return $this->db->query("SELECT DISTINCT
                a.id,
                i_document,
                to_char(to_date(i_periode,'yyyymm'), 'FMMonth YYYY') periode,
                c.name as company_name_doc, a.e_remark
            FROM
                tm_uraianjahit a
            INNER JOIN tm_uraianjahit_item b ON (b.id_document = a.id AND b.n_quantity_sisa <> '0')
            inner join public.company c ON (c.id = a.id_company)
            WHERE
                i_status = '6'
                AND (a.id_company = '$this->id_company' OR a.id_company_bagian = '$this->id_company')
                AND i_document ILIKE '%$cari%'
                AND i_bagian = '$ibagian'
            ORDER BY
                i_document
        ", FALSE);
    }

    /*----------  GET DATA DETAIL REFERENSI  ----------*/

    public function get_detail_referensi($id, $ibagian)
    {
        return $this->db->query("SELECT
                DISTINCT 
                d.id AS id_product_wip,
                d.i_product_wip,
                upper(d.e_product_wipname) AS e_product_name,
                n_quantity,
                n_quantity_sisa,
                upper(trim(h.e_color_name)) AS e_color_name,
                a.e_remark,
                e_type_name,
                regexp_replace(regexp_replace(e_type_name, '[^\w]+', ''),' ','') grup
            FROM
                tm_uraianjahit_item a
            INNER JOIN tr_product_wip d ON
                (d.id = a.id_product_wip
                    AND a.id_company = d.id_company)
            INNER JOIN tr_product_base b ON
                (b.i_product_wip = d.i_product_wip
                    AND d.i_color = b.i_color
                    AND b.id_company = d.id_company)
            INNER JOIN tr_item_type t ON
                (t.i_type_code = b.i_type_code
                    AND b.id_company = t.id_company)
            INNER JOIN tr_color h ON
                (h.i_color = d.i_color
                    AND d.id_company = h.id_company)
            WHERE
                a.id_document = '$id'
                AND a.n_quantity_sisa <> 0
            ORDER BY
                e_type_name,
                d.i_product_wip,
                e_product_name,
                e_color_name;
        ", FALSE);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_schedule_jahit a
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
                    $this->db->query("UPDATE
                            tm_uraianjahit_item dummy
                        SET
                            n_quantity_sisa = n_quantity_sisa - (subquery.n_quantity)
                        FROM
                            (
                            SELECT
                                id_referensi,
                                b.id_product_wip,
                                /* a.id_company, */
                                sum(b.n_quantity) n_quantity
                            FROM
                                tm_schedule_jahit a
                            INNER JOIN tm_schedule_jahit_item_new b ON
                                a.id = b.id_document
                            WHERE
                                a.id = '$id'
                                /* AND b.f_uraian_jahit = 't' */
                            GROUP BY 1,2) AS subquery
                        WHERE
                            dummy.id_document = subquery.id_referensi
                            AND subquery.id_product_wip = dummy.id_product_wip");
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_schedule_jahit');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_schedule_jahit', $data);
    }

    public function product($cari, $id_company_referensi)
    {
        return $this->db->query("SELECT 
                a.id,
                i_product_wip,
                upper(e_product_wipname||' '||e_color_name) AS e_product_wipname
            FROM tr_product_wip a
            INNER JOIN tr_color b ON (
                b.i_color = a.i_color AND a.id_company = b.id_company
            )
            WHERE 
                a.f_status = 't'
                AND a.id_company = '$id_company_referensi'
                AND (i_product_wip ILIKE '%$cari%' OR e_product_wipname ILIKE '%$cari%')
            ORDER BY 2
        ", FALSE);
    }

    public function get_detail_product($id_product)
    {
        return $this->db->query("SELECT 
                a.id,
                i_product_wip,
                upper(e_product_wipname) AS e_product_wipname,
                upper(e_color_name) AS e_color_name
            FROM tr_product_wip a, tr_color b
            WHERE 
                a.i_color = b.i_color AND a.id_company = b.id_company
                AND a.f_status = 't'
                AND a.id_company = '$this->id_company'
                AND a.id = '$id_product'
        ", FALSE);
    }

    public function insert_realisasi($id_item,$id_product,$n_realisasi,$e_note)
    {
        $data = array(
            'id_document_item' => $id_item,
            'id_product_wip' => $id_product,
            'n_quantity' => $n_realisasi,
            'e_remark' => $e_note,
        );
        $this->db->insert('tm_schedule_jahit_item_detail',$data);
    }

    public function detail_item($idocument)
    {
        return $this->db->query(
            "SELECT
                a.id, a.d_schedule,
                a.id_product_wip,
                b.i_product_wip,
                b.e_product_wipname,
                c.e_color_name,
                COALESCE (e.n_quantity,
                0) n_quantity_uraian,
                COALESCE (e.n_quantity_sisa,
                0) n_quantity_uraian_sisa,
                a.n_quantity,
                a.e_remark,
                f.*
            FROM
                tm_schedule_jahit_item_new a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color
                    AND c.id_company = b.id_company)
            INNER JOIN tm_schedule_jahit d ON
                (d.id = a.id_document)
            LEFT JOIN tm_uraianjahit_item e ON
                (e.id_document = d.id_referensi
                    AND a.id_product_wip = e.id_product_wip)
            LEFT JOIN (
                SELECT a.id_document_item, a.id_product_wip id_product, b.i_product_wip AS i_product, e_product_wipname AS e_product, e_color_name e_color,
                a.n_quantity n_realisasi, a.e_remark e_note
                FROM tm_schedule_jahit_item_detail a
                INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)
                INNER JOIN tr_color c ON (
                    c.i_color = b.i_color AND b.id_company = c.id_company
                )
                WHERE id_document_item IN (
                    SELECT id FROM tm_schedule_jahit_item_new WHERE id_document = '$idocument'
                )
            ) f ON (f.id_document_item = a.id)
            WHERE
                a.id_document = '$idocument'
            ORDER BY
                a.id ASC,
                a.d_schedule DESC,
                b.i_product_wip,
                b.e_product_wipname,
                c.e_color_name ASC;"
        );
    }

    public function delete_detail($id)
    {
        $this->db->query("DELETE FROM tm_schedule_jahit_item_detail WHERE id_document_item IN (
            SELECT id FROM tm_schedule_jahit_item_new WHERE id_document = '$id'
        )");
    }

    public function get_export($id)
    {
        return $this->db->query(
            "SELECT
                a.id,
                i_product_wip,
                e_product_wipname,
                e_color_name,
                b.id id_color,
                c.n_quantity,
                c.n_quantity - COALESCE (d.n_quantity,0) AS n_quantity_sisa
            FROM
                tr_product_wip a
            JOIN tr_color b ON
                b.i_color = a.i_color
                AND a.id_company = b.id_company
            JOIN tm_uraianjahit_item c ON
                c.id_product_wip = a.id
            LEFT JOIN (
                SELECT
                    b.id_referensi,
                    a.id_product_wip,
                    sum(a.n_quantity) AS n_quantity
                FROM
                    tm_schedule_jahit_item_new a
                INNER JOIN tm_schedule_jahit b ON
                    (b.id = a.id_document)
                WHERE
                    b.id_referensi = '$id'
                    AND b.i_status NOT IN ('4', '5', '7', '9')
                GROUP BY
                    1,
                    2) d ON
                (d.id_referensi = c.id_document AND c.id_product_wip = d.id_product_wip AND c.id_company = a.id_company)
            WHERE
                a.f_status = 't'
                AND c.id_document = '$id'
                AND c.n_quantity > 0
            ORDER BY
                2;"
        );
        // $this->db->select("aa.id, i_product_wip, e_product_wipname, e_color_name, b.id id_color, n_quantity");
        // $this->db->from("tr_product_wip a");
        // $this->db->join("tr_color b", "b.i_color = a.i_color AND a.id_company = b.id_company");
        // $this->db->join("tm_uraianjahit_item c", "c.id_product_wip = a.id");
        // $this->db->where("a.f_status", "t");
        // $this->db->where("a.id_company", $this->id_company);
        // $this->db->where("c.id_document", $id);
        // /* $this->db->where("c.n_quantity > 0"); */
        // $this->db->order_by(2);
        // return $this->db->get();
    }
}
/* End of file Mmaster.php */
