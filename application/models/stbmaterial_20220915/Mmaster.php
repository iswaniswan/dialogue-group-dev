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
                g.e_bagian_name, a.i_bagian, a.i_bagian_receive, e.e_bagian_name e_bagian_name_receive, e_status_name, label_color, a.i_status, l.i_level, l.e_level_name,
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
            $i_bagian_receive   = trim($data['i_bagian_receive']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$i_bagian/$i_bagian_receive\",\"#main\"); return false;'><i class='ti-eye mr-2 fa-lg text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$i_bagian/$i_bagian_receive\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$i_bagian/$i_bagian_receive\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-2 fa-lg'></i></a>";
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

    public function bagian_receive()
    {
        return $this->db->query("SELECT id, i_bagian, e_bagian_name FROM tr_bagian WHERE id_company = '$this->id_company' AND i_bagian IN (
            SELECT i_bagian FROM tr_tujuan_menu WHERE id_company = '$this->id_company' AND i_menu = '$this->i_menu' ORDER BY id
            )");
    }

    public function bagian_receive_schedule()
    {
        return $this->db->query("SELECT id, i_bagian, e_bagian_name FROM tr_bagian WHERE id_company = '$this->id_company' AND i_bagian IN (
            SELECT i_bagian FROM tr_tujuan_menu WHERE id_company = '$this->id_company' AND i_menu = '$this->i_menu' AND i_type = '07' ORDER BY id
            )");
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
            LEFT JOIN tr_product_wip_item c ON
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
                    /* and a.i_kode_group_barang <> 'GRB0001' */
                )
                 AND b.d_schedule BETWEEN '$dfrom' AND '$dto' 
                AND (d.i_material ILIKE '%$cari%' OR d.e_material_name ILIKE '%$cari%')
            GROUP BY
                1,2,3,4");
    }

    public function get_stock($id_material, $dfrom, $dto, $i_bagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        $query = $this->db->query("SELECT coalesce(n_saldo_akhir,0) n_stock 
            FROM produksi.f_mutasi_material($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$i_bagian') 
            WHERE id_material = '$id_material'");
        if ($query->num_rows()>0) {
            return $query;
        }else{
            return $this->db->query("SELECT 0 n_stock");
        }

    }

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

    public function simpan($id, $i_document, $d_document, $i_bagian, $i_bagian_receive, $e_remark)
    {
        $data = array(
            'id' => $id,
            'id_company' => $this->id_company,
            'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            'i_bagian_receive' => $i_bagian_receive,
            'e_remark' => $e_remark,
        );
        $this->db->insert('tm_stb_material', $data);
    }

    public function simpandetail($id, $id_material, $n_quantity, $e_remark)
    {
        $data = array(
            'id_document' => $id,
            'id_material' => $id_material,
            'n_quantity' => $n_quantity,
            'n_quantity_sisa' => $n_quantity,
            'e_remark' => $e_remark,
        );
        $this->db->insert('tm_stb_material_item', $data);
    }

    
    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("SELECT a.*, b.e_bagian_name, c.e_bagian_name e_bagian_receive_name, to_char(a.d_document, 'dd-mm-yyyy') AS date_document
            FROM tm_stb_material a
            LEFT JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            LEFT JOIN tr_bagian c ON (c.i_bagian = a.i_bagian_receive AND a.id_company = c.id_company)
            WHERE a.id = '$id'");
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id, $i_bagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("SELECT c.i_material, c.e_material_name, cc.e_satuan_name, a.*, coalesce(n_saldo_akhir,0) n_stock
        FROM tm_stb_material_item a
        LEFT JOIN tr_material c ON (c.id = a.id_material)
        LEFT JOIN tr_satuan cc ON (cc.i_satuan_code = c.i_satuan_code AND c.id_company = cc.id_company)
        LEFT JOIN (SELECT id_material, n_saldo_akhir FROM produksi.f_mutasi_material($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$i_bagian') ) ccc ON (ccc.id_material = a.id_material)
        WHERE id_document = '$id'
        ORDER BY c.i_material, c.id");
    }

    /*----------  UPDATE DATA  ----------*/

    public function update($id, $i_document, $d_document, $i_bagian, $i_bagian_receive, $e_remark)
    {
        $data = array(
            'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            'i_bagian_receive' => $i_bagian_receive,
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
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_stb_material a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_stb_material');", FALSE);
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

    public function updatesisa($id)
    {

        /*----------  Cek ada data atau tidak  ----------*/

        $query = $this->db->query("
            SELECT 
                id_document_reff,
                id_material,
                id_material_list,
                n_quantity,
                n_quantity_list
            FROM 
                tm_stb_material_item
            WHERE id_document = $id
        ", FALSE);

        /*----------  Jika Data Ada  ----------*/

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {

                /*----------  Cek Sisa Di Item Tidak Kurang Dari Pemenuhan  ----------*/

                $ceksisa1 = $this->db->query("
                    SELECT 
                        n_quantity_sisa, n_quantity_list_sisa
                    FROM 
                        tm_memo_bb_item
                    WHERE 
                        id_document = $key->id_document_reff
                        AND id_material = $key->id_material
                        AND id_material_list = $key->id_material_list
                        AND n_quantity_sisa >= $key->n_quantity
                        AND n_quantity_list_sisa >= $key->n_quantity_list
                ", FALSE);
                if ($ceksisa1->num_rows() > 0) {

                    /*----------  Update Sisa Di Packing  ----------*/

                    $this->db->query("
                        UPDATE 
                            tm_memo_bb_item
                        SET 
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity,
                            n_quantity_list_sisa = n_quantity_list_sisa - $key->n_quantity_list
                        WHERE 
                            id_document = $key->id_document_reff
                            AND id_material = $key->id_material
                        AND id_material_list = $key->id_material_list
                        AND n_quantity_sisa >= $key->n_quantity
                        AND n_quantity_list_sisa >= $key->n_quantity_list
                    ", FALSE);
                } else {
                    die();
                }
            }
        }
    }

    /*----------  SIMPAN KE JURNAL  ----------*/

    public function simpanjurnal($id, $title)
    {
        $this->db->query("
            INSERT
                INTO
                tm_jurnal_dokumen (id_company,
                id_document,
                i_document,
                i_periode,
                id_material,
                id_product_wip,
                id_product_base,
                i_coa,
                e_coa,
                id_payment_type,
                v_price,
                n_quantity_material,
                n_quantity_wip,
                n_quantity_base,
                n_total,
                title)
            SELECT
                a.id_company,
                b.id_document,
                a.i_document,
                to_char(a.d_document, 'yyyymm') AS i_periode,
                b.id_material_list AS id_material,
                NULL AS id_product_wip,
                NULL AS id_product_base,
                '110-81000' AS i_coa,
                'BAHAN BAKU (BENANG/KAIN, QUILTING, EMBOSS)' AS e_coa,
                NULL AS id_payment_type,
                b.v_unitprice_list AS v_price,
                b.n_quantity_list AS n_quantity_material,
                NULL AS n_quatity_wip,
                NULL AS n_quatity_base,
                b.v_unitprice_list * b.n_quantity_list AS total,
                '$title' AS title
            FROM
                tm_stb_material a
            INNER JOIN tm_stb_material_item b ON
                (b.id_document = a.id)
            WHERE
                a.id = $id
        ", FALSE);
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

        /* $cek = $this->db->query("SELECT i_bagian FROM tm_stb_material a WHERE i_status <> '5' AND id_company = '$this->id_company' $and
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
        } */
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("WITH CTE AS (SELECT DISTINCT 0 AS no, ab.i_periode, c.i_product_wip, initcap(c.e_product_wipname) e_product_wipname,  a.id_product_wip, e.e_color_name, 
            b.id_material, d.i_material, initcap(d.e_material_name) e_material_name, initcap(f.e_satuan_name) e_satuan_name, a.n_fc_cutting, b.n_quantity, round(b.n_quantity * a.n_fc_cutting,4) qty,
            string_agg(g.d_document::varchar,', ') tanggal_schedule, '$dfrom' dfrom, '$dto' dto, '$folder' folder, ROW_NUMBER() OVER (ORDER BY a.id) AS i
            FROM tm_fccutting_detail a
            INNER JOIN tm_fccutting ab ON (ab.id = a.id_forecast)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
            INNER JOIN tr_color e ON (e.i_color = c.i_color AND c.id_company = e.id_company)
            INNER JOIN tr_product_wip_item b ON (b.id_product_wip = a.id_product_wip)
            INNER JOIN tr_material d ON (d.id = b.id_material)
            INNER JOIN tr_satuan f ON (f.i_satuan_code = d.i_satuan_code AND d.id_company = f.id_company)
            LEFT JOIN (
                SELECT DISTINCT to_char(d_document, 'DD-MM-YYYY') d_document, to_char(d_document, 'YYYYMM') i_periode, b.id_product_wip, sum(b.n_quantity) n_quantity_schedule 
                FROM tm_schedule_jahit a
                INNER JOIN tm_schedule_jahit_item_new b ON (b.id_document  = a.id)
                WHERE a.i_status = '6'
                GROUP BY 1,2,3
            ) g ON (g.id_product_wip = a.id_product_wip AND ab.i_periode = g.i_periode)
            WHERE ab.i_status = '6' AND ab.id_company = '$this->id_company'
            AND 49 = any(b.id_type_makloon) $and
            GROUP BY 12,2,3,4,5,6,7,8,9,10,11,12,a.id
            ORDER BY id_product_wip, e_color_name, i_material)
            SELECT no, i, i_periode, i_product_wip, e_product_wipname, id_product_wip, e_color_name, 
            id_material, i_material, e_material_name, e_satuan_name, n_fc_cutting, 
            n_quantity, qty, tanggal_schedule, dfrom, dto, folder, (select count(i) as jml from CTE) As jml from CTE");

        $datatables->add('action', function ($data) {
            $i           = $data["i"];
            $jml         = $data["jml"];
            $folder      = $data['folder'];
            $dfrom       = $data['dfrom'];
            $dto         = $data['dto'];
            $i_periode   = $data['i_periode'];
            $id_material = $data['id_material'];
            $data   = '';
            $data .= "<label class='custom-control custom-checkbox'> 
                <input type='checkbox' id='chk$i' name='chk$i' class='custom-control-input'>
                <span class='custom-control-indicator'></span>
                <span class='custom-control-description'></span>
                <input id='id_material$i' name='id_material$i' value='$id_material' type='hidden'>
                <input id='i_periode$i' name='i_periode$i' value='$i_periode' type='hidden'>
                <input id='jml' name='jml' value='$jml' type='hidden'>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i');
        $datatables->hide('jml');
        $datatables->hide('i_periode');
        $datatables->hide('n_fc_cutting');
        $datatables->hide('n_quantity');
        $datatables->hide('id_material');
        $datatables->hide('id_product_wip');
        return $datatables->generate();
    }

    public function dataeditdetail_schedule($id_material, $i_periode)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("SELECT
                DISTINCT b.id_material,
                d.i_material,
                initcap(d.e_material_name) e_material_name,
                initcap(f.e_satuan_name) e_satuan_name,
                round(b.n_quantity * a.n_fc_cutting, 4) n_quantity,
                coalesce(n_saldo_akhir, 0) n_stock
            FROM
                tm_fccutting_detail a
            INNER JOIN tm_fccutting ab ON
                (ab.id = a.id_forecast)
            INNER JOIN tr_product_wip_item b ON
                (b.id_product_wip = a.id_product_wip)
            INNER JOIN tr_material d ON
                (d.id = b.id_material)
            INNER JOIN tr_satuan f ON
                (f.i_satuan_code = d.i_satuan_code
                    AND d.id_company = f.id_company)
            LEFT JOIN (
                SELECT
                    DISTINCT to_char(d_document, 'DD-MM-YYYY') d_document,
                    to_char(d_document, 'YYYYMM') i_periode,
                    b.id_product_wip,
                    sum(b.n_quantity) n_quantity_schedule
                FROM
                    tm_schedule_jahit a
                INNER JOIN tm_schedule_jahit_item_new b ON
                    (b.id_document = a.id)
                WHERE
                    a.i_status = '6'
                GROUP BY 1,2,3 ) g ON
                (g.id_product_wip = a.id_product_wip
                    AND ab.i_periode = g.i_periode)
            LEFT JOIN (
                SELECT id_material, n_saldo_akhir 
                FROM produksi.f_mutasi_material($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '') ) 
                ccc ON (ccc.id_material = b.id_material
            )
            WHERE
                ab.i_status = '6'
                AND ab.id_company = '$this->id_company'
                AND 49 = ANY(b.id_type_makloon)
                AND b.id_material IN ($id_material)
                AND ab.i_periode IN ($i_periode)
            ORDER BY
                i_material");
    }
}
/* End of file Mmaster.php */