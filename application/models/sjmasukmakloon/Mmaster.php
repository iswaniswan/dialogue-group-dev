<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    /*----------  DAFTAR DATA MASUK MAKLOON  ----------*/

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $and = "";
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }
        $cek = $this->db->query("SELECT i_bagian FROM tm_masuk_makloon a WHERE i_status <> '5' AND id_company = '$this->id_company' $and AND i_bagian IN 
        (SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')");
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
        $datatables->query(
            "SELECT 0 AS NO, a.id AS id, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                d.e_supplier_name, string_agg(DISTINCT e.i_document,', ') AS i_referensi, a.e_remark, e_status_name,
                label_color, l.i_level, l.e_level_name, a.i_status, '$i_menu' AS i_menu, '$folder' AS folder, '$dfrom' AS dfrom, '$dto' AS dto
            FROM
                tm_masuk_makloon a
            INNER JOIN (SELECT DISTINCT id_document, id_document_reff FROM tm_masuk_makloon_item) aa ON (aa.id_document = a.id)
            INNER JOIN tr_status_document b ON (b.i_status = a.i_status)
            INNER JOIN tr_supplier d ON (d.id = a.id_supplier)
            INNER JOIN tm_keluar_makloon e ON (e.id = aa.id_document_reff)
            LEFT JOIN public.tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (f.i_level = l.i_level)
            WHERE
                a.i_status <> '5' AND a.id_company = '$this->id_company'
                $and $bagian
            GROUP BY
                2,3,4,5,7,8,9,10,11
            ORDER BY
                a.id DESC"
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->edit('i_referensi', function ($data) {
            return '<span>' . str_replace(",", "<br>", $data['i_referensi']) . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = $data['id'];
            $i_status   = trim($data['i_status']);
            $i_level    = trim($data['i_level']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success mr-3 fa-lg'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
                }
            }            
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close fa-lg text-danger'></i></a>";
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
        return $datatables->generate();
    }

    /*----------  TYPE MAKLOON  ----------*/

    public function type($i_menu)
    {
        $this->db->select('b.id, e_type_makloon_name AS e_name')->distinct();
        $this->db->from('tr_makloon_menu a');
        $this->db->join('tr_type_makloon b', 'b.id = a.id_makloon AND a.id_company = b.id_company', 'inner');
        $this->db->where('b.f_status', 't');
        $this->db->where('a.id_company', $this->id_company);
        $this->db->where('a.i_menu', $i_menu);
        $this->db->order_by('e_type_makloon_name');
        return $this->db->get();
    }

    /*----------  BACA BAGIAN PEMBUAT  ----------*/

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->i_departement);
        $this->db->where('a.f_status', 't');
        $this->db->where('i_level', $this->i_level);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->id_company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ");
    }

    /*----------  BACA PARTNER  ----------*/

    public function partner($ibagian, $cari)
    {
        return $this->db->query("SELECT DISTINCT id_supplier AS id, b.e_supplier_name AS e_name
            FROM
                tm_keluar_makloon a
            INNER JOIN tr_supplier b ON (b.id = a.id_supplier)
            INNER JOIN tm_sj_makloon_keluar_item d ON (d.id_document = a.id)
            INNER JOIN tm_sj_makloon_keluar_masuk_item e ON (e.id_document = a.id and e.id_keluar = d.id_keluar)
            WHERE
                e.n_quantity_sisa > 0 AND a.i_bagian_receive = '$ibagian'
                AND b.e_supplier_name ILIKE '%$cari%' AND a.i_status = '6'
                AND a.id_company = '$this->id_company'
            ORDER BY 2
        ", FALSE);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("SELECT  substring(i_document, 1, 2) AS kode 
            FROM tm_masuk_makloon WHERE i_status <> '5' AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SJ';
        }
        $query  = $this->db->query("SELECT max(substring(i_document, 9, 4)) AS max
            FROM tm_masuk_makloon
            WHERE to_char (d_document, 'yymm') = '$thbl' AND i_status <> '5'
            AND i_bagian = '$ibagian' AND id_company = '$this->id_company'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
        ");
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

    /*----------  CEK NO DOKUMEN  ----------*/

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_makloon');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->id_company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI DATA REFERENSI  ----------*/

    public function datareferensi($cari, $idpartner, $idtype)
    {
        return $this->db->query("SELECT DISTINCT
                a.id,
                i_document || ', Makloon : '||initcap(REPLACE (e_type_makloon_name,'MAKLOON','')) i_document,
                to_char(d_document, 'dd/mm/yyyy') AS d_document
            FROM
                tm_keluar_makloon a
            INNER JOIN tm_sj_makloon_keluar_item d ON (d.id_document = a.id)
            INNER JOIN tm_sj_makloon_keluar_masuk_item e ON (e.id_document = a.id and e.id_keluar = d.id_keluar)
            INNER JOIN tr_type_makloon c ON (c.id = a.id_type_makloon)
            WHERE 
                id_supplier = '$idpartner'
                AND i_bagian_receive = '$idtype'
                AND i_status = '6'
                AND n_quantity_sisa > 0
                AND a.id_company = '$this->id_company'
                AND (a.i_document ILIKE '%$cari%' OR e_type_makloon_name ILIKE '%$cari%')
            ORDER BY 2
        ", FALSE);
    }

    /*----------  REFERENSI HEADER  ----------*/

    public function ref($id)
    {
        $in_str = "'" . implode("', '", $id) . "'";
        $where  = "WHERE id IN (" . $in_str . ")";
        return $this->db->query("SELECT 
                max(to_char(d_document,'dd-mm-yyyy')) AS d_date,
                string_agg(to_char(d_document, 'dd-mm-yyyy'),', ') AS d_document,
                id_type_makloon
            FROM
                tm_keluar_makloon
            $where
            GROUP BY 3
        ", FALSE);
    }

    /*----------  DETAIL DATA REFERENSI  ----------*/

    public function detailreferensi($id)
    {
        // $in_str = "'" . implode("', '", $id) . "'";
        // $where  = "AND a.id_document IN (" . $in_str . ")";
        // return $this->db->query("SELECT
        //         e.id, a.id_material_keluar, i_document, a.id_document, b.i_material as i_material_keluar,
        //         b.e_material_name, bb.e_satuan_name, a.n_quantity_keluar,
        //         e.id_material_masuk, c.i_material AS i_material_masuk,
        //         c.e_material_name AS e_material_masuk,
        //         cc.e_satuan_name AS e_satuan_masuk,
        //         e.n_quantity_masuk, e.n_quantity_sisa,
        //         dd.e_product_wipname,
        //         dd.i_product_wip,
        //         e.id_keluar
        //     FROM
        //         tm_sj_makloon_keluar_item a
        //     INNER JOIN tm_keluar_makloon aa ON (aa.id = a.id_document)
        //     INNER JOIN tm_sj_makloon_keluar_masuk_item e ON (a.id_document = e.id_document
        //                                                         AND a.id_keluar = e.id_keluar
        //                                                         AND (a.id_material_keluar = e.id_material_keluar
        //                                                             OR (a.id_material_keluar IS NULL
        //                                                                 AND e.id_material_keluar IS NULL) AND e.id_material_masuk IS NOT NULL ))
        //     INNER JOIN tr_material b ON (b.id = a.id_material_keluar)
        //     INNER JOIN tr_satuan bb ON (bb.i_satuan_code = b.i_satuan_code AND b.id_company = bb.id_company)
        //     INNER JOIN tr_material c ON (c.id = e.id_material_masuk)
        //     INNER JOIN tr_satuan cc ON (cc.i_satuan_code = c.i_satuan_code AND c.id_company = cc.id_company)
        //     INNER JOIN tr_product_wip dd ON (dd.id = e.id_product)
        //     WHERE e.n_quantity_sisa > 0
        //     $where
        //     ORDER BY dd.i_product_wip, a.id_material_keluar");

        $in_str = "'" . implode("', '", $id) . "'";
        $where  = "AND a.id_document IN (" . $in_str . ")";
        return $this->db->query("SELECT
            a.id, a.id_document, i_document,
            a.id_material_masuk, b.i_material AS i_material_masuk,
            b.e_material_name AS e_material_masuk,
            bb.e_satuan_name AS e_satuan_masuk,
            a.n_quantity_masuk, a.n_quantity_sisa,
            dd.e_product_wipname,
            dd.i_product_wip,
            a.id_keluar
        FROM
            tm_sj_makloon_keluar_masuk_item a
        INNER JOIN tm_keluar_makloon aa ON (aa.id = a.id_document)
        INNER JOIN tr_material b ON (b.id = a.id_material_masuk)
        INNER JOIN tr_satuan bb ON (bb.i_satuan_code = b.i_satuan_code AND b.id_company = bb.id_company)
        INNER JOIN tr_product_wip dd ON (dd.id = a.id_product)
        WHERE a.n_quantity_sisa > 0
        $where
        ORDER BY dd.i_product_wip, a.id_keluar");
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_makloon');
        return $this->db->get()->row()->id + 1;
    }

    public function simpan($id, $idocument, $ddocument, $ibagian, $idtype, $idpartner, $eremark)
    {
        $data = array(
            'id' => $id,
            'id_company' => $this->id_company,
            'i_document' => $idocument,
            'd_document' => $ddocument,
            'i_bagian' => $ibagian,
            'id_supplier' => $idpartner,
            'id_type_makloon' => $idtype,
            'e_remark' => $eremark,
        );
        $this->db->insert('tm_masuk_makloon', $data);
    }

    public function simpandetail($id, $iddocument, $idreferensiitem, $idmateriallist, $nqtylist, $eremark)
    {
        $data = array(
            'id_document' => $id,
            'id_document_reff' => $iddocument,
            'id_document_reff_item' => $idreferensiitem,
            'id_material' => $idmateriallist,
            'n_quantity' => $nqtylist,
            'e_remark' => $eremark
        );
        $this->db->insert('tm_masuk_makloon_item', $data);
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("SELECT a.i_bagian, b.e_bagian_name, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_type_makloon, a.id_supplier, d.e_supplier_name, a.e_remark, a.i_status
            FROM
                tm_masuk_makloon a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_supplier d ON (d.id = a.id_supplier)
            WHERE a.id = $id
        ");
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE MULTIPLE  ----------*/

    public function dataeditreferensi($id)
    {
        return $this->db->query("SELECT DISTINCT b.id, i_document  || ', Makloon : '||initcap(REPLACE (e_type_makloon_name,'MAKLOON','')) i_document, to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM tm_masuk_makloon_item a
            INNER JOIN tm_keluar_makloon b ON (b.id = a.id_document_reff)
            INNER JOIN tr_type_makloon c ON (c.id = b.id_type_makloon)
            WHERE a.id_document = $id
            ORDER BY 2
            ");
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE TANGGAL REFERENSI  ----------*/

    public function tanggalreferensi($id)
    {
        return $this->db->query("SELECT DISTINCT  max(to_char(d_document, 'dd-mm-yyyy')) AS d_document
            FROM tm_masuk_makloon_item a
            INNER JOIN tm_keluar_makloon b ON (b.id = a.id_document_reff)
            WHERE a.id_document = $id")->row()->d_document;
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("SELECT b.id, b.id_document, b.id_product as id_product_wip, d.i_product_wip, d.e_product_wipname, c.i_document, b.id_material_masuk, e.i_material, e.e_material_name, 
            f.e_satuan_name, b.n_quantity_masuk, b.n_quantity_sisa, g.i_material i_material_list, a.id_material id_material_list, g.e_material_name e_material_list, 
            h.e_satuan_name e_satuan_list, a.n_quantity n_quantity_list, a.e_remark , b.id_keluar
            FROM tm_masuk_makloon_item a
            INNER JOIN tm_sj_makloon_keluar_masuk_item b ON (b.id = a.id_document_reff_item)
            INNER JOIN tm_keluar_makloon c ON (c.id = b.id_document)
            INNER JOIN tr_product_wip d ON (d.id = b.id_product)
            INNER JOIN tr_material e ON (e.id = b.id_material_masuk)
            INNER JOIN tr_satuan f ON (f.i_satuan_code = e.i_satuan_code AND e.id_company = f.id_company)
            INNER JOIN tr_material g ON (g.id = a.id_material)
            INNER JOIN tr_satuan h ON (h.i_satuan_code = g.i_satuan_code AND g.id_company = h.id_company)
            WHERE a.id_document = '$id'
            ORDER BY b.id_product, b.id_keluar, b.id_document");
    }

    /*----------  UPDATE DATA  ----------*/

    public function update($id, $idocument, $ddocument, $ibagian, $idtype, $idpartner, $eremark)
    {
        $data = array(
            'i_document' => $idocument,
            'd_document' => $ddocument,
            'i_bagian' => $ibagian,
            'id_supplier' => $idpartner,
            'id_type_makloon' => $idtype,
            'e_remark' => $eremark,
            'd_update' => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloon', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_makloon_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    /* public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloon', $data);
    } */

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_masuk_makloon a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_makloon');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloon', $data);
    }

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {

        /*----------  Cek ada data atau tidak  ----------*/

        $query = $this->db->query("SELECT id_document_reff_item, id_material, n_quantity
            FROM tm_masuk_makloon_item WHERE id_document = $id");

        /*----------  Jika Data Ada  ----------*/

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {

                /*----------  Cek Sisa Di Item Tidak Kurang Dari Pemenuhan  ----------*/

                $ceksisa1 = $this->db->query("SELECT n_quantity_sisa
                    FROM tm_sj_makloon_keluar_masuk_item
                    WHERE id = $key->id_document_reff_item
                        AND id_material_masuk = $key->id_material
                        AND n_quantity_sisa >= $key->n_quantity
                ", FALSE);
                if ($ceksisa1->num_rows() > 0) {

                    /*----------  Update Sisa Di Packing  ----------*/

                    $this->db->query("UPDATE tm_sj_makloon_keluar_masuk_item
                        SET 
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                        WHERE 
                            id = $key->id_document_reff_item
                            AND id_material_masuk = $key->id_material
                            AND n_quantity_sisa >= $key->n_quantity
                    ", FALSE);
                } else {
                    return "gagal";
                    /* $data = array(
                        'i_status'  => '4',
                    );
                    $this->db->where('id', $id);
                    $this->db->update('tm_masuk_makloon', $data);
                    break; */
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
                NULL AS v_price,
                b.n_quantity_list AS n_quantity_material,
                NULL AS n_quatity_wip,
                NULL AS n_quatity_base,
                NULL AS total,
                '$title' AS title
            FROM
                tm_masuk_makloon a
            INNER JOIN tm_masuk_makloon_item b ON
                (b.id_document = a.id)
            WHERE
                a.id = $id
        ", FALSE);
    }
}
/* End of file Mmaster.php */