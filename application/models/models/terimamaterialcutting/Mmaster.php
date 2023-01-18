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

        $cek = $this->db->query("SELECT i_bagian FROM tm_masuk_material_cutting a WHERE i_status <> '5' AND id_company = '$this->id_company' $and AND i_bagian IN ( SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')", FALSE);
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
        $datatables->query("SELECT
                DISTINCT 0 AS NO,
                a.id AS id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                d.e_bagian_name,
                e.i_document document_referensi,
                a.e_remark,
                e_status_name,
                label_color,
                g.i_level,
                l.e_level_name,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_masuk_material_cutting a
                INNER JOIN tr_status_document b ON (b.i_status = a.i_status)
                INNER JOIN tr_bagian d ON (d.i_bagian = a.i_bagian AND a.id_company = d.id_company)
                LEFT JOIN tm_stb_material_cutting e ON (e.id = a.id_document_referensi)
                LEFT JOIN tr_menu_approve g ON (
                    a.i_approve_urutan = g.n_urut
                    AND g.i_menu = '$i_menu'
                )
                LEFT JOIN public.tr_level l ON (g.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->company' $and $bagian
            ORDER BY
            a.id");

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
            $i_level    = $data['i_level'];
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-3'></i></a>";
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
        $datatables->hide('i_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
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

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_material_cutting 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BBM';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_masuk_material_cutting
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
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

    /*----------  CEK NO DOKUMEN  ----------*/

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_material_cutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->id_company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI DATA REFERENSI  ----------*/

    public function data_referensi($cari, $i_bagian)
    {
        return $this->db->query("SELECT DISTINCT a.id||'|'||id_company id, i_document||' - '||to_char(d_document, 'dd FMMonth yyyy') i_document
                FROM tm_stb_material_cutting a, tm_stb_material_cutting_item b
                WHERE a.id = b.id_document AND id_company_receive = '$this->id_company' 
                AND i_bagian_receive = '$i_bagian' AND i_document ILIKE '%$cari%' AND a.i_status = '6' AND n_quantity_sisa > 0
                AND b.id_document::varchar||b.id_product_wip::varchar||b.id_material::varchar NOT IN (
                    SELECT id_document_referensi::varchar||id_product_wip::varchar||id_material::varchar 
                    FROM tm_masuk_material_cutting_item a, tm_masuk_material_cutting b 
                    WHERE a.id_document = b.id AND b.i_status IN ('1','2','3','6')
                )
            ORDER BY 1
        ");
    }

    /*----------  DETAIL DATA REFERENSI  ----------*/

    public function detail_referensi($id, $i_bagian)
    {
        $id = explode('|',$id)[0];
        return $this->db->query("SELECT a.*, bb.i_product_wip, bb.e_product_wipname, cc.e_color_name, b.i_material, b.e_material_name, c.e_satuan_name,
        p.v_gelar, p.v_set, 
        case when p.v_gelar is null or p.v_gelar = 0 then 0 else (floor(a.n_quantity * p.v_set / NULLIF(p.v_gelar,0) + 0.01)::int) / p.v_set::int end AS jumlah_gelar
        FROM tm_stb_material_cutting_item a
        LEFT JOIN tr_material b ON (a.id_material = b.id) 
        LEFT JOIN tr_satuan c ON (
            c.i_satuan_code = b.i_satuan_code 
            AND b.id_company = c.id_company
        )
        LEFT JOIN tr_product_wip bb ON (a.id_product_wip = bb.id) 
        LEFT JOIN tr_color cc ON (
            cc.i_color = bb.i_color 
            AND bb.id_company = cc.id_company
        )
        LEFT JOIN tr_polacutting_new p ON (
            p.id_product_wip = a.id_product_wip 
            AND a.id_material = p.id_material and p.f_marker_utama = true and p.v_gelar <> 0 
        )
        INNER JOIN tr_type_makloon q ON (q.id = ANY(p.id_type_makloon))
        WHERE id_document = '$id' and (q.e_type_makloon_name ILIKE '%CUTTING%' or q.e_type_makloon_name ILIKE '%AUTO%') 
        /*AND id_document::varchar||a.id_product_wip::varchar||a.id_material::varchar NOT IN (
            SELECT id_document_referensi::varchar||id_product_wip::varchar||id_material::varchar 
            FROM tm_masuk_material_cutting_item a, tm_masuk_material_cutting b 
            WHERE a.id_document = b.id AND b.i_status IN ('1','2','3','6') AND id_document_referensi = '$id'
        )*/
        and a.id not in (select a.id_item_keluar FROM tm_masuk_material_cutting_item a, tm_masuk_material_cutting b 
            WHERE a.id_document = b.id AND b.i_status IN ('1','2','3','6') AND id_document_referensi = '$id' )
        ORDER BY bb.i_product_wip, b.i_material");
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_material_cutting');
        return $this->db->get()->row()->id + 1;
    }

    public function simpan($id, $i_document, $d_document, $i_bagian, $i_referensi, $e_remark)
    {
        $explode = explode('|',$i_referensi);
        $id_referensi = $explode[0];
        $id_company_referensi = $explode[1];
        $data = array(
            'id' => $id,
            'id_company' => $this->id_company,
            'i_bagian' => $i_bagian,
            'i_document' => $i_document,
            'd_document' => $d_document,
            'id_document_referensi' => $id_referensi,
            'id_company_referensi' => $id_company_referensi,
            'e_remark' => $e_remark,
        );
        $this->db->insert('tm_masuk_material_cutting', $data);
    }

    public function simpandetail($id, $id_material, $id_product_wip, $n_quantity, $e_remark_item, $n_quantity_gelar, $v_gelar, $v_set, $id_item_keluar)
    {
        $data = array(
            'id_document' => $id,
            'id_material' => $id_material,
            'id_product_wip' => $id_product_wip,
            'n_quantity' => $n_quantity,
            'n_jumlah_gelar' => $n_quantity_gelar,
            'v_gelar' => $v_gelar,
            'v_set' => $v_set,
            'e_remark' => $e_remark_item,
            'id_item_keluar' => $id_item_keluar,
        );
        $this->db->insert('tm_masuk_material_cutting_item', $data);
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("SELECT a.*, c.id||'|'||c.id_company id_document_referensi, b.e_bagian_name, c.i_document||' - '||to_char(c.d_document, 'dd FMMonth yyyy') i_document_referensi
            FROM tm_masuk_material_cutting a 
                INNER JOIN tr_bagian b ON (
                    b.i_bagian = a.i_bagian 
                    AND a.id_company = b.id_company
                )
            INNER JOIN tm_stb_material_cutting c ON (c.id = a.id_document_referensi)
            WHERE a.id = '$id'");
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("SELECT a.*, bb.i_product_wip, bb.e_product_wipname, cc.e_color_name, b.i_material, b.e_material_name, c.e_satuan_name, d.n_quantity n_quantity_reff, d.n_quantity_sisa n_quantity_reff_sisa 
            FROM tm_masuk_material_cutting_item a 
            INNER JOIN tr_material b ON (b.id = a.id_material)
            INNER JOIN tr_satuan c ON (
                c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company
            )
            /*INNER JOIN (
                SELECT a.id, b.id_product_wip, b.id_material, b.n_quantity, b.n_quantity_sisa FROM tm_masuk_material_cutting a
                INNER JOIN tm_stb_material_cutting_item b ON (b.id_document = a.id_document_referensi)
                WHERE a.id = '$id'
            ) d ON (
                d.id = a.id_document AND a.id_material = d.id_material and a.id_product_wip = d.id_product_wip
            )*/
            inner join tm_stb_material_cutting_item d on (a.id_item_keluar = d.id)
            INNER JOIN tr_product_wip bb ON (a.id_product_wip = bb.id) 
            INNER JOIN tr_color cc ON (
                cc.i_color = bb.i_color 
                AND bb.id_company = cc.id_company
            )
            WHERE a.id_document = '$id'
            ORDER BY a.id");
    }

    /*----------  UPDATE DATA  ----------*/

    public function update($id, $i_document, $d_document, $i_bagian, $i_referensi, $e_remark)
    {
        $explode = explode('|',$i_referensi);
        $id_referensi = $explode[0];
        $id_company_referensi = $explode[1];
        $data = array(
            'i_bagian' => $i_bagian,
            'i_document' => $i_document,
            'd_document' => $d_document,
            'id_document_referensi' => $id_referensi,
            'id_company_referensi' => $id_company_referensi,
            'e_remark' => $e_remark,
            'd_update' => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_material_cutting', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_material_cutting_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_material_cutting a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2", FALSE)->row();
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_material_cutting');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_material_cutting', $data);
    }
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
        $this->db->update('tm_masuk_material_cutting', $data);
    } */

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {
        /*----------  Cek ada data atau tidak  ----------*/

        $query = $this->db->query("SELECT b.id_item_keluar, id_document_referensi, id_product_wip, id_material, n_quantity
            FROM tm_masuk_material_cutting a, tm_masuk_material_cutting_item b 
            WHERE a.id = b.id_document AND a.id = $id");

        /*----------  Jika Data Ada  ----------*/

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {

                /*----------  Cek Sisa Di Item Tidak Kurang Dari Pemenuhan  ----------*/

                // $ceksisa1 = $this->db->query("SELECT n_quantity_sisa
                //     FROM tm_stb_material_cutting_item
                //     WHERE id_document = $key->id_document_referensi
                //         AND id_material = $key->id_material
                //         AND id_product_wip = $key->id_product_wip
                //         /*AND n_quantity_sisa >= $key->n_quantity*/
                // ", FALSE);
                $ceksisa1 = $this->db->query("SELECT n_quantity_sisa
                    FROM tm_stb_material_cutting_item
                    WHERE id_document = $key->id_document_referensi
                        AND id = $key->id_item_keluar
                ", FALSE);
                if ($ceksisa1->num_rows() > 0) {

                    /*----------  Update Sisa Di Packing  ----------*/

                    $this->db->query("UPDATE tm_stb_material_cutting_item
                        SET 
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                        WHERE 
                            id_document = $key->id_document_referensi
                            AND id = $key->id_item_keluar
                    ", FALSE);
                } else {
                    return "gagal";
                }
            }
        }else{
            return "gagal";
        }
    }
}
/* End of file Mmaster.php */