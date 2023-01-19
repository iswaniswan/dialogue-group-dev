<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    /*----------  DEKLARASI SESSION  ----------*/
    
    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

    /*----------  DAFTAR DATA SPB  ----------*/

    public function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_keluar_produksiak a
            WHERE
                i_status <> '5'
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')
        ",FALSE);
        if ($this->departement == '1') {
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
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("                              
            SELECT DISTINCT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_partner,
                e.e_bagian_name,
                a.id_document_reff,
                ba.i_document as document_referensi,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_produksiak a
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tm_memo_ak ba ON
                (a.id_document_reff = ba.id AND ba.id_company = a.id_company)
            INNER JOIN tr_bagian e ON
                (e.id = a.id_partner AND e.id_company = a.id_company)
            WHERE
                a.i_status <> '5'
                $and
                AND a.id_company = '$this->company'
                $bagian
            ",
            FALSE
        );

        $datatables->edit(
            'i_status',
            function ($data) {
                return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
            }
        );

        $datatables->add(
            'action',
            function ($data) {
                $id            = trim($data['id']);
                $i_status      = trim($data['i_status']);
                $dfrom         = trim($data['dfrom']);
                $dto           = trim($data['dto']);
                $i_menu        = $data['i_menu'];
                $folder        = trim($data['folder']);
                $data = '';
                if (check_role($i_menu, 2)) {
                    $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }
                if (check_role($i_menu, 3)) {
                    if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }
                if (check_role($i_menu, 7)) {
                    if ($i_status == '2') {
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }
                if (check_role($i_menu, 4) && ($i_status == '1')) {
                    $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
                }

                return $data;
            }
        );
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status_name');
        $datatables->hide('id_document_reff');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_partner');
        $datatables->hide('id_document_reff');
        return $datatables->generate();
    }

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian', 'inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_produksiak');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_produksiak');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query(
            "
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_keluar_produksiak
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->company'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'STB';
        }
        $query = $this->db->query(
            "
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_keluar_produksiak
            WHERE  
                to_char (d_document, 'yyyy') >= '$tahun'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND substring(i_document, 1, 3) = '$kode'
                AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                AND id_company = '$this->company'
                AND to_char (d_document, 'yyyy') >= '" . date('Y') . "'
        ",
            false
        );
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    public function partner($cari)
    {
        return $this->db->query("    
            SELECT DISTINCT
               a.id,
               a.e_bagian_name AS e_name,
               a.i_bagian AS kode,
               'bagian' AS grouppartner 
            FROM
               tr_bagian a 
            JOIN
                tm_memo_ak b ON (a.id = b.id_partner 
                AND a.id_company = b.id_company )
            JOIN 
                tm_memo_ak_item c ON (c.id_document = b.id)
            WHERE
               a.f_status = 't' 
               AND b.id_jenis = '4' 
               AND 
               (
                  e_bagian_name ILIKE '%$cari%'
               )
               AND a.id_company = '$this->company'
               AND b.i_status = '6' 
               AND b.e_partner_type = 'bagian' 
               AND c.n_quantity_sisa > 0
            ORDER BY id 
            ", FALSE);
    }

    public function referensi($cari, $partner)
    {
        return $this->db->query("
            SELECT DISTINCT
               a.i_document,
               a.id,
               to_char(d_document, 'dd-mm-yyyy') as d_document 
            FROM
               tm_memo_ak a 
               INNER JOIN
               tm_memo_ak_item b 
                  ON (a.id = b.id_document AND a.id_company = b.id_company) 
               INNER JOIN
                  tr_material c 
                  ON (b.id_material = c.id AND b.id_company = c.id_company) 
            WHERE
               a.i_status = '6' 
               AND id_jenis = '4' 
               AND COALESCE(b.n_quantity_sisa, 0) > 0 
               AND COALESCE(b.n_quantity_list_sisa, 0) > 0 
               AND a.id_partner = '$partner'
               AND 
               (
                  TRIM(a.i_document) ILIKE '$cari%'
               )
                              ",
            FALSE
        );
    }

    public function getdetailrefeks($id)
    {
        return $this->db->query(
            "
                SELECT
                    a.i_document,
                    a.id,
                    to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                    b.id_material,
                    c.i_material,
                    c.e_material_name,
                    b.n_quantity,
                    b.n_quantity_sisa,
                    d.e_satuan_name,
                    a.e_pic_eks 
                FROM
                    tm_memo_ak a 
                    INNER JOIN
                        tm_memo_ak_item b 
                        ON (a.id = b.id_document AND a.id_company = b.id_company) 
                    INNER JOIN
                        tr_material c 
                        ON (b.id_material = c.id AND b.id_company = c.id_company) 
                    INNER JOIN tr_satuan d ON
                        (d.i_satuan_code = c.i_satuan_code
                        AND c.id_company = d.id_company)
                WHERE
                    COALESCE (b.n_quantity_sisa, 0) > 0  
                    AND a.id = '$id'
                ORDER BY
                    a.i_document,
                    c.e_material_name ASC
                                ",
            FALSE
        );
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '6') {
            $query = $this->db->query(
                "
                SELECT id_document, id_product, n_quantity, n_quantity_sisa, id_document_reff
                FROM tm_keluar_produksiak_item
                WHERE id_document = '$id' ",
                FALSE
            );
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query(
                        "
                        SELECT
                            n_quantity_sisa, n_quantity_list_sisa
                        FROM
                            tm_memo_ak_item                       
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_material = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                            AND n_quantity_list_sisa >= '$key->n_quantity'
                        ",
                        FALSE
                    );

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query(
                            "
                        UPDATE
                            tm_memo_ak_item
                        SET
                            n_quantity_sisa      = n_quantity_sisa - $key->n_quantity,
                            n_quantity_list_sisa = n_quantity_list_sisa - $key->n_quantity
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_material = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                            AND n_quantity_list_sisa >= '$key->n_quantity'
                    ",
                            FALSE
                        );
                    } else {
                        die();
                    }
                }
            }
            $data = array(
                'i_status'  => $istatus,
                // 'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->company);
        $this->db->update('tm_keluar_produksiak', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function insertheader($id, $ibagian, $ikeluar, $datekeluar, $imemo, $partner, $eremark)
    {
        $data = array(
            'id'               => $id,
            'id_company'       => $this->company,
            'i_document'       => $ikeluar,
            'd_document'       => $datekeluar,
            'id_document_reff' => $imemo,
            'i_bagian'         => $ibagian,
            'id_partner'       => $partner,
            'e_remark'         => $eremark,
            'd_entry'          => current_datetime(),
        );
        $this->db->insert('tm_keluar_produksiak', $data);
    }

    public function insertdetail($id, $imaterial, $nquantitymemo, $nsisa, $nquantity, $edesc, $imemo)
    {
        $data = array(
            'id_company'       => $this->company,
            'id_document'      => $id,
            'id_document_reff' => $imemo,
            'id_product'       => $imaterial,
            'n_quantity'       => $nquantity,
            'n_quantity_sisa'  => $nquantity,
            'e_remark'         => $edesc,
        );
        $this->db->insert('tm_keluar_produksiak_item', $data);
    }

    public function baca_header($id)
    {
        return $this->db->query(
            " SELECT DISTINCT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_partner,
                b.e_bagian_name as e_partner_name,
                a.id_document_reff,
                ba.i_document AS document_referensi,
                to_char(ba.d_document, 'dd-mm-yyyy') AS d_referensi,
                a.i_bagian,
                ga.e_bagian_name,
                a.e_remark,
                e_status_name,
                a.i_status
            FROM
                tm_keluar_produksiak a
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tm_memo_ak ba ON
                (a.id_document_reff = ba.id AND ba.id_company = a.id_company)
            INNER JOIN tr_bagian b ON
                (b.id = a.id_partner AND b.id_company = a.id_company)
            INNER JOIN tr_bagian ga ON
                (ga.i_bagian = a.i_bagian AND ga.id_company = a.id_company)
            WHERE
                a.id = '$id'
            ",
            FALSE
        );
    }

    public function baca_detail($id)
    {
        return $this->db->query(
            "SELECT DISTINCT
                b.id,
                b.id_company,
                b.i_document,
                b.id_document_reff,
                a.id_product as id_material,
                c.i_material,
                c.e_material_name,
                d.n_quantity as nquantity_permintaan,
                d.n_quantity_sisa as nquantity_pemenuhan,
                a.n_quantity,
                a.n_quantity_sisa,
                e.e_satuan_name,
                a.e_remark 
            FROM
                tm_keluar_produksiak_item a 
                JOIN
                    tm_keluar_produksiak b 
                    ON b.id = a.id_document 
                JOIN
                    tr_material c 
                    ON a.id_product = c.id 
                JOIN
                    tm_memo_ak_item d 
                    ON b.id_document_reff = d.id_document 
                    AND a.id_company = b.id_company 
                    AND a.id_product = d.id_material
                INNER JOIN tr_satuan e ON
                    (e.i_satuan_code = c.i_satuan_code
                    AND c.id_company = e.id_company)
                WHERE b.id = '$id'
            ",
            FALSE
        );
    }

    public function updateheader($id, $ibagian, $ikeluar, $datekeluar, $imemo, $partner, $eremark)
    {
        $data = array(
            'i_document'       => $ikeluar,
            'd_document'       => $datekeluar,
            'id_document_reff' => $imemo,
            'id_partner'       => $partner,
            'i_bagian'         => $ibagian,
            'e_remark'         => $eremark,
            'd_update'         => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->company);
        $this->db->update('tm_keluar_produksiak', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->where('id_company', $this->company);
        $this->db->delete('tm_keluar_produksiak_item');
    }
}
/* End of file Mmaster.php */