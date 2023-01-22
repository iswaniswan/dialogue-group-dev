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

        $cek = $this->db->query("SELECT i_bagian FROM tm_stb_material a WHERE i_status <> '5' AND id_company = '$this->id_company' $and
                AND i_bagian IN (SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')");
        if ($this->i_departement == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT 0 AS NO, a.id AS id, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                g.e_bagian_name, a.i_bagian, e.e_bagian_name e_bagian_name_receive, e_status_name, label_color, a.i_status, l.i_level, l.e_level_name,
                '$i_menu' AS i_menu, '$folder' AS folder, '$dfrom' AS dfrom,'$dto' AS dto
            FROM
                tm_stb_material a
            INNER JOIN tr_status_document b ON (b.i_status = a.i_status)
            INNER JOIN tr_bagian g ON (g.i_bagian = a.i_bagian AND a.id_company = g.id_company)
            INNER JOIN tr_bagian e ON (e.i_bagian = a.i_bagian_receive AND a.id_company = e.id_company)
            LEFT JOIN public.tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (f.i_level = l.i_level)
            WHERE a.i_status <> '5' AND a.id_company = '$this->id_company' $and $bagian
            ORDER BY a.id ASC");

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
            $i_bagian   = trim($data['i_bagian']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
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
        $datatables->hide('i_bagian');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /*----------  BACA BAGIAN PEMBUAT  ----------*/

    public function bagian($i_type = null)
    {
        $sql = "SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement, cc.name
                    FROM tr_bagian a 
                    INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
                    LEFT JOIN tr_type c on (a.i_type = c.i_type)
                    LEFT JOIN public.tm_menu d on (
                                d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement
                            )
                    LEFT JOIN public.company cc ON cc.id = a.id_company
                    WHERE a.f_status = 't' 
                        AND b.i_departement = '$this->i_departement' 
                        AND username = '$this->username' 
                        AND a.id_company = '$this->id_company' 
                        AND c.i_type = '$i_type'
                    ORDER BY 4, 3 ASC NULLS LAST";

        return $this->db->query($sql, false);
    }

    public function bagian_receive($i_bagian, $id_company_tujuan='4')
    {
        $sql = "SELECT tb.id, tb.i_bagian, tb.e_bagian_name, cc.id AS id_company, cc.name 
                    FROM tr_bagian tb
                    LEFT JOIN public.company cc ON cc.id = tb.id_company
                    WHERE i_bagian IN ('$i_bagian') 
                        AND id_company = '$id_company_tujuan'";

        $query = $this->db->query($sql);
         var_dump($this->db->last_query());
        return $query;
    }

    public function product($cari, $dfrom, $dto, $i_bagian)
    {
        $dfrom = formatYmd($dfrom);
        $dto = formatYmd($dto);
        return $this->db->query("SELECT
                d.id,
                d.i_material,
                d.e_material_name,
                e.e_satuan_name,
                round(sum(coalesce(b.n_quantity,0))) n_quantity
            FROM
                tm_schedule_jahit a
            INNER JOIN tm_schedule_jahit_item_new b ON
                (b.id_document = a.id)
            LEFT JOIN tr_polacutting_new c ON
                (c.id_product_wip = b.id_product_wip)
            LEFT JOIN tr_material d ON
                (d.id = c.id_material)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = d.i_satuan_code
                    AND d.id_company = e.id_company)
            WHERE
                a.i_status = '6' AND
                d.i_kode_group_barang = (
                    SELECT i_kode_group_barang FROM tr_type a
                    LEFT JOIN tr_bagian b ON (b.i_type = a.i_type)
                    WHERE id_company = '$this->id_company'AND i_bagian = '$i_bagian'
                )
                /* AND a.d_document BETWEEN '$dfrom' AND '$dto' */
                AND c.f_marker_utama = 't'
                AND (d.i_material ILIKE '%$cari%' OR d.e_material_name ILIKE '%$cari%')
            GROUP BY
                1,2,3,4");
    }

    /* public function get_stock($id_material, $dfrom, $dto, $i_bagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        $query = $this->db->query("SELECT coalesce(n_saldo_akhir,0) n_stock 
            FROM produksi.f_mutasi_material($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$i_bagian') 
            WHERE id_material = '$id_material'");
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return $this->db->query("SELECT 0 n_stock");
        }
    } */

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function runningnumber($thbl, $ibagian)
    {
        $query = $this->db->query("SELECT b.e_no_doc_retur, b.e_no_doc FROM tr_bagian a 
            INNER JOIN tr_kategori_jahit b ON (b.id = a.id_kategori_jahit) 
            WHERE id_company = '$this->id_company' AND a.i_bagian = '$ibagian'");
        if ($query->num_rows() > 0) {
            $kode = $query->row()->e_no_doc;
        } else {
            $kode = 'SJ';
        }
        // var_dump($kode);
        if (strlen($kode) == 4) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 11, 4)) AS max 
                FROM tm_stb_material
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian_receive = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 3) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 10, 4)) AS max 
                FROM tm_stb_material
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian_receive = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 2) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 9, 4)) AS max 
                FROM tm_stb_material
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian_receive = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        }
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
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
    /* public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_stb_material 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SJ';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 9, 4)) AS max
            FROM
                tm_stb_material
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
    } */

    /*----------  CEK NO DOKUMEN  ----------*/

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_stb_material');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->id_company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_stb_material');
        return $this->db->get()->row()->id + 1;
    }

    public function simpan($id, $i_document, $d_document, $i_bagian, $i_bagian_receive, $e_remark, $for_caset, $id_company_receive)
    {
        // var_dump($i_bagian_receive);
        $for_caset = "{" . $for_caset . "}";
        $data = array(
            'id' => $id,
            'id_company' => $this->id_company,
            'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            'i_bagian_receive' => $i_bagian_receive,
            'e_remark' => $e_remark,
            'id_company_receive' => $id_company_receive
            /* 'id_referensi' => $for_caset */
        );
        $this->db->insert('tm_stb_material', $data);
    }

    public function simpandetail($id, $id_material, $id_product_wip, $n_quantity, $e_remark, $id_memo_item)
    {
        $data = array(
            'id_document' => $id,
            'id_material' => $id_material,
            // 'id_product_wip' => $id_product_wip,
            'n_quantity' => $n_quantity,
            'n_quantity_sisa' => $n_quantity,
            'e_remark' => $e_remark,
            'id_referensi_item' => $id_memo_item
        );
        $this->db->insert('tm_stb_material_item', $data);
    }


    public function dataedit($id)
    {
        $sql = "SELECT a.*, b.e_bagian_name, c.e_bagian_name e_bagian_receive_name, cc.name AS company_receive_name, to_char(a.d_document, 'dd-mm-yyyy') AS date_document
                    FROM tm_stb_material a
                    LEFT JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
                    LEFT JOIN tr_bagian c ON (c.i_bagian = a.i_bagian_receive AND a.id_company = c.id_company)
                    LEFT JOIN public.company cc ON cc.id = a.id_company_receive
                    WHERE a.id = '$id'";

        return $this->db->query($sql);
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id, $i_bagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("SELECT a.*, ab.id_product, c.i_material, c.e_material_name, cc.e_satuan_name, coalesce(n_saldo_akhir,0) n_stock,
        b.i_product_wip, b.e_product_wipname, bb.e_color_name, ab.n_quantity_sisa n_sisa
        FROM tm_stb_material_item a
        LEFT JOIN tm_memo_permintaan_item ab ON (ab.id = a.id_referensi_item)
        LEFT JOIN tr_material c ON (c.id = a.id_material)
        LEFT JOIN tr_satuan cc ON (cc.i_satuan_code = c.i_satuan_code AND c.id_company = cc.id_company)
        LEFT JOIN tr_product_wip b ON (b.id = ab.id_product)
        LEFT JOIN tr_color bb ON (bb.i_color = b.i_color AND b.id_company = bb.id_company)
        LEFT JOIN (SELECT id_material, n_saldo_akhir FROM produksi.f_mutasi_material($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$i_bagian') ) ccc ON (ccc.id_material = a.id_material)
        WHERE a.id_document = '$id'
        ORDER BY i_product_wip, e_product_wipname, e_color_name, c.i_material, c.id");
    }

    /*----------  UPDATE DATA  ----------*/

    public function update($id, $i_document, $d_document, $i_bagian, $i_bagian_receive, $e_remark)
    {
        // $explode = explode('|', $i_bagian_receive);
        /* $i_bagian_receive = $explode[0];
        $id_company_receive = $explode[1]; */
        $data = array(
            // 'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            // 'i_bagian_receive' => $i_bagian_receive,
            'e_remark' => $e_remark,
            'd_update' => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_stb_material', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_stb_material_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    /* public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_stb_material', $data);
    } */

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, a.i_bagian, coalesce(max(b.n_urut),1) as n_urut 
                from tm_stb_material a
                inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
                where a.id = '$id'
                group by 1,2,3", FALSE)->row();
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_stb_material');", FALSE);
                $this->update_sisa($id,$awal->i_bagian);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_stb_material', $data);
    }

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function update_sisa($id, $i_bagian)
    {
        $query = $this->dataeditdetail($id,$i_bagian);
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                if ($key->n_quantity > $key->n_stock) {
                    return;
                    // die;
                }else{
                    if ($key->n_quantity > $key->n_sisa) {
                        return;
                        // die;
                    }else{
                        $this->db->query(
                            "UPDATE tm_memo_permintaan_item 
                                SET n_quantity_sisa = (n_quantity_sisa - $key->n_quantity) 
                                    WHERE id = '$key->id_referensi_item' 
                            ");
                    }
                }
            }
        }
    }

    /*----------  DAFTAR DATA MASUK GUDANG JADI SESUAI GUDANG PEMBUAT  ----------*/

    function data_schedule($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND ab.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("WITH CTE AS (
            SELECT DISTINCT 0 as no, a.id, ab.i_bagian, c.i_product_wip, c.e_product_wipname, d.e_color_name, 
                e.i_material, e.e_material_name, f.e_satuan_name, a.n_quantity_sisa, ab.i_document, 
                ab.d_document, g.e_bagian_name, ROW_NUMBER() OVER (ORDER BY a.id) AS i, h.i_type, ab.i_tujuan, ab.d_kirim, i.e_bagian_name as tujuan_name, j.name as company_name
            FROM tm_memo_permintaan_item a
            INNER JOIN tm_memo_permintaan ab ON (ab.id = a.id_document)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product)
            INNER JOIN tr_color d ON (
                d.i_color = c.i_color AND c.id_company = d.id_company
            )
            INNER JOIN tr_material e ON (e.id = a.id_material)
            INNER JOIN tr_satuan f ON (
                f.i_satuan_code = e.i_satuan_code AND e.id_company = f.id_company
            )
            INNER JOIN tr_bagian g ON (
                g.i_bagian = ab.i_bagian AND ab.id_company = g.id_company
            )
            INNER JOIN tr_type h ON (
                h.id = ab.id_type_penerima
            )
            LEFT JOIN tr_bagian i ON (
                i.id = ab.i_tujuan
            )
            left join public.company j ON (
                j.id = i.id_company
            )
            WHERE ab.i_status = '6' AND a.n_quantity_sisa > 0
            $and
            AND g.i_type IN (
                SELECT i_type FROM tr_bagian WHERE i_bagian IN (
                    SELECT i_bagian FROM tr_departement_cover WHERE id_company = '$this->id_company' AND username = '$this->username'
                ) AND id_company = '$this->id_company'
            )
            ORDER BY 
            -- g.e_bagian_name, c.i_product_wip, d.e_color_name, e.i_material
            ab.d_document)
            SELECT no, id, i_bagian, i, i_product_wip, e_product_wipname, e_color_name, i_material, e_material_name, e_satuan_name, n_quantity_sisa, i_type,
            i_document, d_document, e_bagian_name, i_tujuan, tujuan_name, company_name, d_kirim, (select count(i) as jml from CTE) As jml from CTE");

        $datatables->add('action', function ($data) {
            $i        = $data["i"];
            $jml      = $data["jml"];
            $id       = $data['id'];
            $i_bagian = $data['i_bagian'];
            $i_type = $data['i_type'];
            $i_tujuan = $data['i_tujuan'];
            $d_kirim = $data['d_kirim'];
            $tujuan_name = $data['tujuan_name'];
            $company_name = $data['company_name'];
            $data     = '';
            $data    .= "
                <label class='custom-control custom-checkbox'> 
                <input type='checkbox' id='chk$i' name='chk$i' class='custom-control-input'>
                <span class='custom-control-indicator'></span>
                <span class='custom-control-description'></span>
                <input id='id$i' name='id$i' value='$id' type='hidden'>
                <input id='i_bagian$i' name='i_bagian$i' value='$i_bagian' type='hidden'>
                <input id='i_type$i' name='i_type$i' value='$i_type' type='hidden'>
                <input id='i_tujuan$i' name='i_tujuan$i' value='$i_tujuan' type='hidden'>
                <input id='d_kirim$i' name='d_kirim$i' value='$d_kirim' type='hidden'>
                <input id='tujuan_name$i' name='tujuan_name$i' value='$tujuan_name' type='hidden'>
                <input id='company_name$i' name='company_name$i' value='$company_name' type='hidden'>
                <input id='jml' name='jml' value='$jml' type='hidden'>
            ";
            return $data;
        });

        $datatables->edit('tujuan_name', function($data) {
            $tujuan_name = $data['tujuan_name'];
            $company_name = $data['company_name'];
            $data = $tujuan_name . ' [' . $company_name . ']';
            return $data;
        });
        $datatables->hide('i');
        $datatables->hide('jml');
        $datatables->hide('id');
        $datatables->hide('i_bagian');
        $datatables->hide('i_type');
        $datatables->hide('i_tujuan');
        // $datatables->hide('d_kirim');
        $datatables->hide('company_name');
        return $datatables->generate();
    }

    public function data_schedule2($dfrom, $dto, $i_product_wip, $i_material)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND ab.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        $or = "";
        if ($i_product_wip) {
            $i_product_wip = to_pg_array($i_product_wip);
            $or = "AND a.id_product IN ($i_product_wip)";
        }

        $like = "";
        if ($i_material) {
            $i_material = to_pg_array($i_material);
            $like = "AND a.id_material IN ($i_material)";
        }

        // $datatables = new Datatables(new CodeigniterAdapter);
        /** original query */
        /*
        $sql = "WITH CTE AS (
            SELECT DISTINCT 0 as no, a.id, ab.i_bagian, c.i_product_wip, c.e_product_wipname, d.e_color_name, 
                e.i_material, e.e_material_name, f.e_satuan_name, a.n_quantity_sisa, ab.i_document, 
                ab.d_document, g.e_bagian_name, ROW_NUMBER() OVER (ORDER BY a.id) AS i, h.i_type, ab.i_tujuan, ab.d_kirim, i.e_bagian_name as tujuan_name, j.name as company_name
            FROM tm_memo_permintaan_item a
            INNER JOIN tm_memo_permintaan ab ON (ab.id = a.id_document)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product)
            INNER JOIN tr_color d ON (
                d.i_color = c.i_color AND c.id_company = d.id_company
            )
            INNER JOIN tr_material e ON (e.id = a.id_material)
            INNER JOIN tr_satuan f ON (
                f.i_satuan_code = e.i_satuan_code AND e.id_company = f.id_company
            )
            INNER JOIN tr_bagian g ON (
                g.i_bagian = ab.i_bagian AND ab.id_company = g.id_company
            )
            INNER JOIN tr_type h ON (
                h.id = ab.id_type_penerima
            )
            LEFT JOIN tr_bagian i ON (
                i.id = ab.i_tujuan
            )
            left join public.company j ON (
                j.id = i.id_company
            )
            WHERE ab.i_status = '6' AND a.n_quantity_sisa > 0
            $and $or $like
            AND g.i_type IN (
                SELECT i_type FROM tr_bagian WHERE i_bagian IN (
                    SELECT i_bagian FROM tr_departement_cover WHERE id_company = '$this->id_company' AND username = '$this->username'
                ) AND id_company = '$this->id_company'
            )
            ORDER BY 
            -- g.e_bagian_name, c.i_product_wip, d.e_color_name, e.i_material
            ab.d_document)
            SELECT no, id, i_bagian, i, i_product_wip, e_product_wipname, e_color_name, i_material, e_material_name, e_satuan_name, n_quantity_sisa, i_type,
            i_document, d_document, e_bagian_name, i_tujuan, tujuan_name, company_name, d_kirim, (select count(i) as jml from CTE) As jml from CTE";
        */

        $sql ="WITH CTE AS ( 
                            SELECT DISTINCT 0 as no, 
                                a.id, ab.i_bagian, c.i_product_wip, c.e_product_wipname, d.e_color_name, e.i_material, 
                                e.e_material_name, f.e_satuan_name, a.n_quantity_sisa, ab.i_document, ab.d_document, 
                                g.e_bagian_name, j2.name AS company_pembuat, ROW_NUMBER() OVER (ORDER BY a.id) AS i, 
                                h.i_type, ab.i_tujuan, ab.d_kirim, i.e_bagian_name as tujuan_name, i.id_company AS id_company_tujuan,
                                j.name as company_name 
                            FROM tm_memo_permintaan_item a 
                            INNER JOIN tm_memo_permintaan ab ON (ab.id = a.id_document) 
                            INNER JOIN tr_product_wip c ON (c.id = a.id_product) 
                            INNER JOIN tr_color d ON ( d.i_color = c.i_color AND c.id_company = d.id_company ) 
                            INNER JOIN tr_material e ON (e.id = a.id_material) 
                            INNER JOIN tr_satuan f ON ( f.i_satuan_code = e.i_satuan_code AND e.id_company = f.id_company ) 
                            INNER JOIN tr_bagian g ON ( g.i_bagian = ab.i_bagian AND ab.id_company = g.id_company ) 
                            INNER JOIN tr_type h ON ( h.id = ab.id_type_penerima ) 
                            LEFT JOIN tr_bagian i ON ( i.id = ab.i_tujuan ) 
                            LEFT JOIN public.company j ON ( j.id = i.id_company ) 
                            LEFT JOIN public.company j2 ON ( j2.id = ab.id_company ) 
                            WHERE ab.i_status = '6' 
                                AND a.n_quantity_sisa > 0 
                                $and $or $like
                                AND g.i_type IN ( 
                                                    SELECT i_type 
                                                        FROM tr_bagian 
                                                        WHERE i_bagian IN ( 
                                                                                SELECT i_bagian 
                                                                                FROM tr_departement_cover 
                                                                                WHERE username = '$this->username' 
                                                                            )                                                             
                                                ) 
                                AND (ab.id_company = '$this->id_company' OR ab.id_company_penerima = '$this->id_company')
                            ORDER BY ab.d_document
                        ) 
                    SELECT no, id, i_bagian, i, i_product_wip, e_product_wipname, e_color_name, i_material,
                            e_material_name, e_satuan_name, n_quantity_sisa, i_type, i_document, d_document, 
                            e_bagian_name, company_pembuat, i_tujuan, tujuan_name, id_company_tujuan,
                            company_name, d_kirim, (select count(i) as jml FROM CTE) As jml 
                    FROM CTE";

//        var_dump($sql);

        return $this->db->query($sql);
    }

    public function data_schedule_material($dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND ab.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        return $this->db->query("WITH CTE AS (
            SELECT DISTINCT 0 as no, a.id, ab.i_bagian, a.id_material, c.i_product_wip, c.e_product_wipname, d.e_color_name, 
                e.i_material, e.e_material_name, f.e_satuan_name, a.n_quantity_sisa, ab.i_document, 
                ab.d_document, g.e_bagian_name, ROW_NUMBER() OVER (ORDER BY a.id) AS i, h.i_type, ab.i_tujuan, ab.d_kirim, i.e_bagian_name as tujuan_name, j.name as company_name
            FROM tm_memo_permintaan_item a
            INNER JOIN tm_memo_permintaan ab ON (ab.id = a.id_document)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product)
            INNER JOIN tr_color d ON (
                d.i_color = c.i_color AND c.id_company = d.id_company
            )
            INNER JOIN tr_material e ON (e.id = a.id_material)
            INNER JOIN tr_satuan f ON (
                f.i_satuan_code = e.i_satuan_code AND e.id_company = f.id_company
            )
            INNER JOIN tr_bagian g ON (
                g.i_bagian = ab.i_bagian AND ab.id_company = g.id_company
            )
            INNER JOIN tr_type h ON (
                h.id = ab.id_type_penerima
            )
            LEFT JOIN tr_bagian i ON (
                i.id = ab.i_tujuan
            )
            left join public.company j ON (
                j.id = i.id_company
            )
            WHERE ab.i_status = '6' AND a.n_quantity_sisa > 0
            $and
            AND g.i_type IN (
                SELECT i_type FROM tr_bagian WHERE i_bagian IN (
                    SELECT i_bagian FROM tr_departement_cover WHERE id_company = '$this->id_company' AND username = '$this->username'
                ) AND id_company = '$this->id_company'
            )
            ORDER BY 
            -- g.e_bagian_name, c.i_product_wip, d.e_color_name, e.i_material
            ab.d_document)
            SELECT DISTINCT id_material, i_material, e_material_name from CTE");
    }

    public function data_schedule_wip($dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND ab.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        return $this->db->query("WITH CTE AS (
            SELECT DISTINCT 0 as no, a.id, ab.i_bagian, a.id_product, c.i_product_wip, c.e_product_wipname, d.e_color_name, 
                e.i_material, e.e_material_name, f.e_satuan_name, a.n_quantity_sisa, ab.i_document, 
                ab.d_document, g.e_bagian_name, ROW_NUMBER() OVER (ORDER BY a.id) AS i, h.i_type, ab.i_tujuan, ab.d_kirim, i.e_bagian_name as tujuan_name, j.name as company_name
            FROM tm_memo_permintaan_item a
            INNER JOIN tm_memo_permintaan ab ON (ab.id = a.id_document)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product)
            INNER JOIN tr_color d ON (
                d.i_color = c.i_color AND c.id_company = d.id_company
            )
            INNER JOIN tr_material e ON (e.id = a.id_material)
            INNER JOIN tr_satuan f ON (
                f.i_satuan_code = e.i_satuan_code AND e.id_company = f.id_company
            )
            INNER JOIN tr_bagian g ON (
                g.i_bagian = ab.i_bagian AND ab.id_company = g.id_company
            )
            INNER JOIN tr_type h ON (
                h.id = ab.id_type_penerima
            )
            LEFT JOIN tr_bagian i ON (
                i.id = ab.i_tujuan
            )
            left join public.company j ON (
                j.id = i.id_company
            )
            WHERE ab.i_status = '6' AND a.n_quantity_sisa > 0
            $and
            AND g.i_type IN (
                SELECT i_type FROM tr_bagian WHERE i_bagian IN (
                    SELECT i_bagian FROM tr_departement_cover WHERE id_company = '$this->id_company' AND username = '$this->username'
                ) AND id_company = '$this->id_company'
            )
            ORDER BY 
            -- g.e_bagian_name, c.i_product_wip, d.e_color_name, e.i_material
            ab.d_document)
            SELECT DISTINCT i_product_wip, e_product_wipname||' - '||initcap(e_color_name) as e_product_wipname, id_product from CTE");
    }

    public function data_detail($id)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query(
            "SELECT a.*, b.i_product_wip, b.e_product_wipname, c.e_color_name, 
            d.i_material, d.e_material_name, e.e_satuan_name, COALESCE (n_saldo_akhir,0) n_stock,
            CASE 
                WHEN COALESCE (n_saldo_akhir,0) > a.n_quantity_sisa THEN a.n_quantity_sisa 
                WHEN COALESCE (n_saldo_akhir,0) < a.n_quantity_sisa AND COALESCE (n_saldo_akhir,0) > 0 THEN COALESCE (n_saldo_akhir,0) 
                ELSE 0
            END n_sisa
            FROM tm_memo_permintaan_item a
            INNER JOIN tr_product_wip b ON (b.id = a.id_product)
            INNER JOIN tr_color c ON (
                c.i_color = b.i_color AND b.id_company = c.id_company
            )
            INNER JOIN tr_material d ON (d.id = a.id_material)
            INNER JOIN tr_satuan e ON (
                e.i_satuan_code = d.i_satuan_code AND d.id_company = e.id_company
            )
            LEFT JOIN f_mutasi_material('$this->id_company', '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '') f ON (f.id_material = a.id_material)
            WHERE a.id IN ($id)
            ORDER BY i_product_wip, e_product_wipname, e_color_name, i_material
        ");
    }

    public function query_table_bagian($id)
    {
        $sql = "SELECT tb.*, c.name 
                    FROM tr_bagian tb
                    INNER JOIN public.company c on c.id = tb.id_company 
                    WHERE tb.id = '$id'";

        return $this->db->query($sql);
    }
}
/* End of file Mmaster.php */