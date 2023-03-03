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

        $cek = $this->db->query("SELECT i_bagian FROM tm_schedule_cutting a WHERE i_status <> '5' AND id_company = '$this->id_company' $and
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
                a.e_remark, e_status_name, label_color, a.i_status, l.i_level, l.e_level_name,
                '$i_menu' AS i_menu, '$folder' AS folder, '$dfrom' AS dfrom,'$dto' AS dto
            FROM
                tm_schedule_cutting a
            INNER JOIN tr_status_document b ON (b.i_status = a.i_status)
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
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye mr-2 fa-lg text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-2 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 3)  && ($i_status == '6')) {
                $data .= "<a href=\"#\" title='Realisasi' onclick='show(\"$folder/cform/realisasi/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-check-circle-o fa-lg mr-3 text-warning'></i></a>";
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

    public function runningnumber($thbl, $ibagian)
    {
        $cek = $this->db->query("SELECT substring(i_document, 1, 3) AS kode 
            FROM tm_schedule_cutting 
            WHERE i_status <> '5' AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SCC';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_schedule_cutting
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

    /*----------  CEK NO DOKUMEN  ----------*/

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_schedule_cutting');
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
        $this->db->from('tm_schedule_cutting');
        return $this->db->get()->row()->id + 1;
    }

    public function simpan($id, $i_document, $d_document, $i_bagian, $e_remark)
    {
        $data = array(
            'id' => $id,
            'id_company' => $this->id_company,
            'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            'e_remark' => $e_remark,
        );
        $this->db->insert('tm_schedule_cutting', $data);
    }

    public function simpandetail($id,$d_schedule,$jam,$id_referensi,$id_material,$id_product_wip,$n_quantity,$id_pic_cutting,$id_pic_gelar,$d_cutting,$e_remark,$id_company_referensi,$n_quantity_product,$n_jumlah_gelar,$v_set,$v_gelar){
        $data = array(
            'id_document' => $id,
            'd_schedule' => formatYmd($d_schedule),
            'jam' => $jam,
            'id_referensi' => $id_referensi,
            'id_material' => $id_material,
            'id_product_wip' => $id_product_wip,
            'n_quantity' => $n_quantity,
            'n_quantity_sisa' => $n_quantity,
            'n_quantity_product' => $n_quantity_product,
            'n_jumlah_gelar' => $n_jumlah_gelar,
            'v_gelar' => $v_gelar,
            'v_set' => $v_set,
            'id_pic_cutting' => $id_pic_cutting,
            'id_pic_gelar' => $id_pic_gelar,
            'd_cutting' => formatYmd($d_cutting),
            'e_remark' => $e_remark,
            'id_company_referensi' => $id_company_referensi
        );
        $this->db->insert('tm_schedule_cutting_item', $data);
    }

    
    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("SELECT a.*, b.e_bagian_name, to_char(a.d_document, 'dd-mm-yyyy') AS date_document
            FROM tm_schedule_cutting a
            LEFT JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            WHERE a.id = '$id'");
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id, $i_bagian)
    {
        return $this->db->query("SELECT a.*, 0 AS n_stock,
                b.i_product_wip, b.e_product_wipname, bb.e_color_name,
                c.i_material, c.e_material_name, cc.e_satuan_name,
                d.e_pic_name e_pic_name_cutting, e.e_pic_name e_pic_name_gelar, co.name,to_char(a.jam, 'HH24:MI') as jam , to_char(a.jam_realisasi, 'HH24:MI') as jam_realisasi , a.d_schedule_realisasi
            FROM
                tm_schedule_cutting_item a
            LEFT JOIN tr_material c ON (c.id = a.id_material)
            LEFT JOIN tr_satuan cc ON (
                cc.i_satuan_code = c.i_satuan_code
                AND c.id_company = cc.id_company
            )
            LEFT JOIN tr_product_wip b ON (b.id = a.id_product_wip)
            LEFT JOIN tr_color bb ON (
                bb.i_color = b.i_color
                AND b.id_company = bb.id_company
            )
            LEFT JOIN tr_pic d ON (d.id = a.id_pic_cutting)
            LEFT JOIN tr_pic e ON (e.id = a.id_pic_gelar)
            LEFT JOIN public.company co ON (co.id = a.id_company_referensi)
            WHERE
                id_document = '$id'
            ORDER BY a.id, c.i_material,c.id");
    }

    /*----------  UPDATE DATA  ----------*/

    public function update($id, $i_document, $d_document, $i_bagian, $e_remark)
    {
        $data = array(
            'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            'e_remark' => $e_remark,
            'd_update' => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_schedule_cutting', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_schedule_cutting_item');
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
        $this->db->update('tm_schedule_cutting', $data);
    } */

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_schedule_cutting a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_schedule_cutting');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_schedule_cutting', $data);
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
                tm_schedule_cutting_item
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
                tm_schedule_cutting a
            INNER JOIN tm_schedule_cutting_item b ON
                (b.id_document = a.id)
            WHERE
                a.id = $id
        ", FALSE);
    }

    /*----------  DAFTAR DATA MASUK GUDANG JADI SESUAI GUDANG PEMBUAT  ----------*/

    function data_penerimaan($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND b.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("WITH CTE AS (SELECT b.d_document, to_char(b.d_entry, 'HH24:MI:SS') t_document, a.id, g.name as perusahaan, a.id_product_wip, c.i_product_wip, initcap(c.e_product_wipname) e_product_wipname, initcap(e.e_color_name) e_color_name, 
            a.id_material, d.i_material, initcap(d.e_material_name) e_material_name, initcap(f.e_satuan_name) e_satuan_name, a.n_quantity, n_jumlah_gelar, b.e_remark,
            '$dfrom' dfrom, '$dto' dto, '$folder' folder, ROW_NUMBER() OVER (ORDER BY a.id) AS i
            FROM tm_masuk_material_cutting_item a
            INNER JOIN tm_masuk_material_cutting b ON (b.id = a.id_document)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
            INNER JOIN tr_material d ON (d.id = a.id_material)
            INNER JOIN tr_color e ON (
                e.i_color = c.i_color AND c.id_company = e.id_company
            )
            INNER JOIN tr_satuan f ON (
                f.i_satuan_code = d.i_satuan_code AND d.id_company = f.id_company
            )
            inner join public.company g on (b.id_company_referensi = g.id)
            WHERE b.i_status = '6' $and
            AND b.id_company = '$this->id_company'
            ORDER BY 1,2)
            SELECT id, i, perusahaan, id_product_wip, i_product_wip, e_product_wipname, e_color_name, 
            id_material, i_material, e_material_name, e_satuan_name, n_quantity, n_jumlah_gelar, d_document, t_document, e_remark, dfrom, dto, folder, 
            (select count(i) as jml from CTE) As jml from CTE");

        $datatables->add('action', function ($data) {
            $i              = $data["i"];
            $jml            = $data["jml"];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $id             = $data['id'];
            $id_material    = $data['id_material'];
            $id_product_wip = $data['id_product_wip'];
            $data   = '';
            $data .= "<label class='custom-control custom-checkbox'> 
                <input type='checkbox' id='chk$i' name='chk$i' class='custom-control-input'>
                <span class='custom-control-indicator'></span>
                <span class='custom-control-description'></span>
                <input id='id_material$i' name='id_material$i' value='$id_material' type='hidden'>
                <input id='id_product_wip$i' name='id_product_wip$i' value='$id_product_wip' type='hidden'>
                <input id='id$i' name='id$i' value='$id' type='hidden'>
                <input id='jml' name='jml' value='$jml' type='hidden'>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i');
        $datatables->hide('jml');
        $datatables->hide('id_material');
        $datatables->hide('id_product_wip');
        return $datatables->generate();
    }

    public function dataeditdetail_penerimaan($id_material, $id_product_wip, $id)
    {
        return $this->db->query("SELECT b.d_document, to_char(b.d_entry, 'HH24:MI:SS') t_document, a.id, a.id_product_wip, c.i_product_wip, initcap(c.e_product_wipname) e_product_wipname, 
                initcap(e.e_color_name) e_color_name, a.id_material, d.i_material, initcap(d.e_material_name) e_material_name, 
                initcap(f.e_satuan_name) e_satuan_name, a.n_quantity, 0 n_stock, b.id_company_referensi, g.name,
                a.n_jumlah_gelar, /* floor(0.01 + a.n_quantity * a.v_set / NULLIF(a.v_gelar,0)) */ floor(a.n_quantity * a.v_set / NULLIF(a.v_gelar,0) + 0.1) n_quantity_product, a.v_set, a.v_gelar
            FROM tm_masuk_material_cutting_item a
            INNER JOIN tm_masuk_material_cutting b ON (b.id = a.id_document)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
            INNER JOIN tr_material d ON (d.id = a.id_material)
            INNER JOIN tr_color e ON (
                e.i_color = c.i_color AND c.id_company = e.id_company
            )
            INNER JOIN tr_satuan f ON (
                f.i_satuan_code = d.i_satuan_code AND d.id_company = f.id_company
            )
            LEFT JOIN public.company g ON (g.id = b.id_company_referensi)
            WHERE b.i_status = '6'
            AND b.id_company = '$this->id_company'
            AND a.id IN ($id)
            ORDER BY 1,2");
    }

    public function get_pic($cari, $f_cutting, $f_gelar)
    {
        return $this->db->query("SELECT id, e_pic_name FROM tr_pic 
            WHERE f_status = 't' AND id_company = '$this->id_company' ");
    }

    public function update_realisasi($id_item, $d_schedule_real, $jam_real, $id_pic_cutting, $id_pic_gelar, $n_realisasi_gelar, $n_realisasi_product, $e_pic_cutting, $e_pic_gelar ){
        $data = array(
            'd_schedule_realisasi' => formatYmd($d_schedule_real),
            'jam_realisasi' => $jam_real,
            'id_pic_cutting' => $id_pic_cutting,
            'id_pic_gelar' => $id_pic_gelar,
            'n_realisasi_gelar' => $n_realisasi_gelar,
            'n_realisasi_product' => $n_realisasi_product,
            // 'e_pic_cutting' => $e_pic_cutting,
            // 'e_pic_gelar' => $e_pic_gelar,
        );
        $this->db->where('id', $id_item);
        $this->db->update('tm_schedule_cutting_item', $data);
    }

}
/* End of file Mmaster.php */