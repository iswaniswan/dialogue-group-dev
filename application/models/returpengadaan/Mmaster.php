<?php
defined("BASEPATH") or exit("No direct script access allowed");

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany = $this->session->userdata("id_company");
        $cek = $this->db->query(
            "SELECT
                    i_bagian
                FROM
                    tm_retur_masuk_pengadaan
                WHERE
                    i_status <> '5'
                    AND d_document BETWEEN to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
                    AND i_bagian IN (
                        SELECT
                            i_bagian
                        FROM
                            tr_departement_cover
                        WHERE
                            i_departement = '$this->i_departement'
                            AND username = '$this->username'
                            AND id_company = '$idcompany')",
            false
        );
        if ($this->session->userdata("i_departement") == "1") {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian =
                    "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" .
                    $this->session->userdata("i_departement") .
                    "'
                        AND username = '" .
                    $this->session->userdata("username") .
                    "'
                        AND id_company = '$idcompany')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query(
            "SELECT 
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document,'dd-mm-YYYY') as d_document,
                a.i_bagian,
                a.id_document_reff,
                d.i_document as i_reff,
                b.e_bagian_name ||' - '|| bb.name AS e_bagian_name,
                a.e_remark, 
                a.i_status,
                e.e_status_name,
                e.label_color,
                f.i_level,
                l.e_level_name,
                '$i_menu' as i_menu, 
                '$folder' as folder,
                '$dfrom' as dfrom,
                '$dto' as dto
            FROM 
                tm_retur_masuk_pengadaan a
            INNER JOIN tm_stbjahit_retur d
                ON (a.id_document_reff = d.id)
            INNER JOIN tr_bagian b
                ON (b.i_bagian = d.i_bagian AND d.id_company = b.id_company)
            INNER JOIN public.company bb ON (bb.id = b.id_company)
            INNER JOIN tr_status_document e
                ON (a.i_status = e.i_status)
            LEFT JOIN tr_menu_approve f 
                ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l 
                ON (f.i_level = l.i_level)
            WHERE 
            a.id_company = '$idcompany'
            AND a.i_status <> '5'
            $bagian",
            false
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add("action", function ($data) {
            $id = trim($data["id"]);
            $i_menu = $data["i_menu"];
            $folder = $data["folder"];
            $dfrom = $data["dfrom"];
            $dto = $data["dto"];
            $i_status = trim($data["i_status"]);
            $i_level  = $data['i_level'];
            $data = "";

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye fa-lg mr-3 text-success'></i></a>";
            }
            if (
                check_role($i_menu, 3) &&
                $i_status != "5" &&
                $i_status != "6" &&
                $i_status != "9"
            ) {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box fa-lg mr-3 text-primary'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && $i_status == "1") {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close fa-lg mr-3 text-danger'></i></a>";
            }

            return $data;
        });
        $datatables->hide("i_menu");
        $datatables->hide("folder");
        $datatables->hide("label_color");
        $datatables->hide("dfrom");
        $datatables->hide("dto");
        $datatables->hide("id");
        $datatables->hide("i_bagian");
        $datatables->hide("id_document_reff");
        $datatables->hide("i_status");
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function bagianpembuat()
    {
        /* $this->db->select("a.id, a.i_bagian, e_bagian_name");
        $this->db->from("tr_bagian a");
        $this->db->join(
            "tr_departement_cover b",
            "b.i_bagian = a.i_bagian AND a.id_company = b.id_company",
            "inner"
        );
        $this->db->where(
            "i_departement",
            $this->session->userdata("i_departement")
        );
        $this->db->where("username", $this->session->userdata("username"));
        $this->db->where(
            "a.id_company",
            $this->session->userdata("id_company")
        );
        $this->db->order_by("e_bagian_name");
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
            INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            LEFT JOIN tr_type c on (a.i_type = c.i_type)
            LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
            WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
            ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function referensi($cari,$ipengirim)
    {
        $split = explode('|', $ipengirim);
        $id_company_pengirim = $split[0];
        $ipengirim = $split[1];
        $cari = str_replace("'", "", $cari);
        return $this->db->query(
            "SELECT
                DISTINCT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_stbjahit_retur a
            LEFT JOIN tm_stbjahit_retur_item b
                                ON
                (a.id = b.id_document
                    AND a.id_company = b.id_company)
            WHERE
                a.i_status = '6'
                AND a.id_bagian_company = '$this->id_company'
                AND b.n_sisa > '0'
                AND a.i_document ILIKE '%$cari%'
                AND a.i_bagian = '$ipengirim'
                AND a.id_company = '$id_company_pengirim'
            ORDER BY
                i_document,
                d_document",
            false
        );
        /* return $this->db->query("SELECT DISTINCT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_retur_jahit_topengadaan a
                LEFT JOIN tm_retur_jahit_topengadaan_item b
                    on (a.id = b.id_document AND a.id_company = b.id_company)
            WHERE
                a.i_status = '6'
                AND a.id_company = '$this->idcompany'
                AND b.n_sisa_wip <> '0'
                AND a.i_document ILIKE '%$cari%'
            ORDER BY
                i_document,
                d_document",
            false
        ); */
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select("i_document");
        $this->db->from("tm_retur_masuk_pengadaan");
        $this->db->where("i_document", $kode);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    public function getdataitem($idreff)
    {
        return $this->db->query("SELECT
                a.id,
                b.id idreff,
                b.i_document,
                to_char(b.d_document, 'dd-mm-yyyy') d_document,
                c.id id_product_wip,
                c.i_product_wip,
                c.e_product_wipname,
                a.n_quantity n_quantity_wip,
                a.n_sisa n_quantity_wip_sisa,
                c.i_color,
                e.id AS id_color,
                e.e_color_name,
                /* a.id_material,
                d.i_material,
                d.e_material_name,
                a.n_quantity_material,
                a.n_sisa_material,    */
                a.e_remark
            FROM
                tm_stbjahit_retur_item a
            INNER JOIN tm_stbjahit_retur b
                ON (a.id_document = b.id)
            INNER JOIN tr_product_base ab ON
                (ab.id = a.id_product)
            INNER JOIN tr_product_wip c
                ON
                (ab.i_product_wip = c.i_product_wip
                    AND c.i_color = ab.i_color
                    AND ab.id_company = c.id_company)
            INNER JOIN tr_color e
                ON
                (c.i_color = e.i_color
                    AND c.id_company = e.id_company)
            WHERE
                a.id_document = '$idreff'
                -- AND b.id_company = '$this->id_company'
                /* AND a.f_item_complete = 'f' */
                AND a.n_sisa > '0'
            ORDER BY
                a.id; 
        ");
        // return $this->db->query("SELECT  
        //         a.id,
        //         b.id as idreff,
        //         b.i_document,
        //         to_char(b.d_document, 'dd-mm-yyyy') as d_document,
        //         a.id_product_wip,
        //         c.i_product_wip,
        //         c.e_product_wipname,
        //         a.n_quantity_wip as n_quantity_wip,
        //         a.n_sisa_wip as n_quantity_wip_sisa,
        //         c.i_color, 
        //         e.id as id_color,
        //         e.e_color_name,        
        //         /* a.id_material,
        //         d.i_material,
        //         d.e_material_name,
        //         a.n_quantity_material,
        //         a.n_sisa_material,    */                    
        //         a.e_remark
        //         FROM
        //         tm_retur_jahit_topengadaan_item a
        //         INNER JOIN tm_retur_jahit_topengadaan b
        //         ON (a.id_document = b.id AND a.id_company = b.id_company)
        //         INNER JOIN tr_product_wip c
        //         ON (a.id_product_wip = c.id AND a.id_company = c.id_company)
        //         /* INNER JOIN tr_material d
        //         ON (a.id_material = d.id AND a.id_company = d.id_company) */
        //         INNER JOIN tr_color e
        //         ON (c.i_color = e.i_color AND c.id_company = e.id_company)
        //     WHERE
        //         b.id = '$idreff' 
        //         AND a.id_document = '$idreff'
        //         AND b.id_company = '$this->idcompany'
        //         /* AND a.f_item_complete = 'f' */
        //         AND a.n_sisa_wip <> '0'
        //     ",
        //     false
        // );
    }

    public function runningid()
    {
        $this->db->select("max(id) AS id");
        $this->db->from("tm_retur_masuk_pengadaan");
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query(
            "SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_retur_masuk_pengadaan 
            WHERE i_status <> '5'
            AND id_company = '$this->id_company'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = "SJ";
        }
        $query = $this->db->query(
            "SELECT
                max(substring(i_document, 9, 4)) AS max
            FROM
                tm_retur_masuk_pengadaan
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_document ILIKE '%$kode%'
            AND id_company = '$this->id_company'
            -- AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
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
            while ($n < 4) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "0001";
            $nomer = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    function insertheader(
        $id,
        $idocument,
        $ibagian,
        $datedocument,
        $ireff,
        $eremark,
        $itujuan
    ) {
        $split = explode("|",$itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $data = [
            "id" => $id,
            "id_company" => $this->idcompany,
            "i_document" => $idocument,
            "d_document" => $datedocument,
            "id_document_reff" => $ireff,
            "i_bagian" => $ibagian,
            "e_remark" => $eremark,
            "d_entry" => current_datetime(),
            "i_tujuan" => $itujuan
        ];
        $this->db->insert("tm_retur_masuk_pengadaan", $data);
    }

    function insertdetail(
        $id,
        $ireff,
        $idproductwip,
        /* $idmaterial, */
        $nquantitywipmasuk,
        /* $nquantitybahanmasuk, */
        $edesc
    ) {
        $data = [
            "id_company" => $this->idcompany,
            "id_document" => $id,
            "id_document_reff" => $ireff,
            "id_product_wip" => $idproductwip,
            "n_quantity_wip" => $nquantitywipmasuk,
            /* "id_material" => $idmaterial,
            "n_quantity_material" => $nquantitybahanmasuk, */
            "e_remark" => $edesc,
        ];
        $this->db->insert("tm_retur_masuk_pengadaan_item", $data);
    }

    /* public function changestatus($id, $istatus)
    {
        $dreceive = "";
        $dreceive = date("Y-m-d");
        $iapprove = $this->session->userdata("username");
        if ($istatus == "6") {
            $query = $this->db->query("SELECT 
                                        a.id_document_reff, 
                                        a.id_product_wip, 
                                        a.id_material, 
                                        a.n_quantity_wip, 
                                        a.n_quantity_material
                                      FROM 
                                        tm_retur_masuk_pengadaan_item a
                                        INNER JOIN tm_retur_masuk_pengadaan b
                                          ON (a.id_document = b.id AND a.id_company = b.id_company)
                                      WHERE 
                                        a.id_document = '$id' ",
                false
            );
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $this->db->query(
                        "
                                      UPDATE
                                          tm_retur_jahit_topengadaan_item
                                      SET
                                          n_sisa_wip = n_sisa_wip - $key->n_quantity_wip,
                                          n_sisa_material = n_sisa_material - $key->n_quantity_material
                                      WHERE
                                          id_document = '$key->id_document_reff'
                                          AND id_product_wip = '$key->id_product_wip'   
                                          AND id_material = '$key->id_material'                       
                                          AND id_company = '" .
                            $this->session->userdata("id_company") .
                            "'
                                  ",
                        false
                    );
                }
            }
            $data = [
                "i_status" => $istatus,
                "i_approve" => $iapprove,
                "d_approve" => date("Y-m-d"),
            ];
        } else {
            $data = [
                "i_status" => $istatus,
            ];
        }
        $this->db->where("id", $id);
        $this->db->where("id_company", $this->idcompany);
        $this->db->update("tm_retur_masuk_pengadaan", $data);
    } */

    public function tujuan($i_menu, $idcompany)
    {
        /* return $this->db->query(" 
                                SELECT 
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
        return $this->db->query("SELECT b.name, a.id, a.i_bagian, a.id_company, a.e_bagian_name 
            FROM tr_bagian a
            INNER JOIN public.company b ON (b.id = a.id_company)
            WHERE a.f_status = 't' AND a.i_type = '10' AND b.f_status = 't' AND b.i_apps = '2'
            AND (
                    SELECT array_agg(id) FROM tr_type_makloon 
                    WHERE e_type_makloon_name ILIKE '%MAKLOON JAHIT%'
                ) && a.id_type_makloon
            ORDER BY 1,5;");
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                FROM tm_retur_masuk_pengadaan a
                INNER JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $query = $this->db->query("SELECT id_document_reff, 
                        id_product_wip, n_quantity_wip, c.id id_product_base 
                        FROM tm_retur_masuk_pengadaan_item a
                        INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)
                        INNER JOIN tr_product_base c ON (c.i_product_wip = b.i_product_wip AND b.i_color = c.i_color AND c.id_company = b.id_company)
                        WHERE id_document = '$id' ");
                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $key) {
                            $nsisa = $this->db->query("SELECT n_sisa, id_reference FROM tm_stbjahit_retur_item 
                                WHERE id_document = '$key->id_document_reff' AND id_product = '$key->id_product_base'
                                /* AND id_company = '$this->id_company' */ AND n_sisa >= '$key->n_quantity_wip'");
                            if ($nsisa->num_rows() > 0) {
                                $n_sisa = $nsisa->row()->n_sisa;
                                $id_reference = $nsisa->row()->id_reference;

                                if ($id_reference) {
                                     if ($n_sisa > 0) {
                                        $this->db->query("UPDATE tm_stbjahit_retur_item SET n_quantity = $key->n_quantity_wip, n_sisa = 0
                                        WHERE id_document = '$key->id_document_reff' AND id_product = '$key->id_product_base'
                                        /* AND id_company = '$this->id_company' */ AND n_sisa >= '$key->n_quantity_wip'");
                                        $this->db->query("UPDATE tm_masuk_unitjahit_item SET qty_sisa_bs = qty_bs - ($key->n_quantity_wip) WHERE id_product_wip = '$key->id_product_wip' /* AND id_company = '$this->id_company' */ AND id_reff = '$id_reference'");
                                    } else {
                                        $this->db->query("UPDATE tm_retur_masuk_pengadaan_item SET n_quantity_wip = 0 WHERE id_product_wip = '$key->id_product_wip' AND id_document = '$id'");
                                    }
                                }
                               
                            } else {
                                die;
                            }
                            /* $this->db->query("UPDATE tm_stbjahit_retur_item SET n_sisa_wip = n_sisa_wip - $key->n_quantity_wip
                            WHERE id_document = '$key->id_document_reff' AND id_product_wip = '$key->id_product_wip' AND id_company = '$this->id_company'"); */
                        }
                    }
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'i_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_retur_masuk_pengadaan');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_retur_masuk_pengadaan', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select("e_status_name");
        $this->db->from("tr_status_document");
        $this->db->where("i_status", $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function cek_data($id)
    {
        return $this->db->query(
            "SELECT 
                a.id,
                a.i_document, 
                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                a.id_document_reff,
                b.i_document as i_reff,
                to_char(a.d_document, 'dd-mm-yyyy') as d_reff,
                a.i_bagian,
                a.i_tujuan,
                c.e_bagian_name,
                e.e_bagian_name||' - '||ee.name as e_tujuan_name,
                a.e_remark,
                a.i_status,
                b.id_company||'|'||b.i_bagian as i_bagian_referensi
            FROM
                tm_retur_masuk_pengadaan a
            INNER JOIN tm_retur_masuk_pengadaan_item d
                ON (a.id = d.id_document AND a.id_document_reff = d.id_document_reff)
            INNER JOIN tm_stbjahit_retur b
                ON (a.id_document_reff = b.id)
            INNER JOIN tr_bagian c
                ON (a.i_bagian = c.i_bagian AND a.id_company = c.id_company)
            INNER JOIN tr_bagian e
                ON (e.i_bagian = b.i_bagian AND b.id_company = e.id_company)
            INNER JOIN public.company ee ON (ee.id = e.id_company)
            WHERE 
                a.id  = '$id'",
            false
        );
    }

    public function cek_datadetail($id)
    {
        return $this->db->query("SELECT
                a.id,
                a.id_document,
                a.id_product_wip,
                c.i_product_wip,
                c.e_product_wipname,
                a.n_quantity_wip n_quantity_wip_masuk,
                f.n_quantity n_quantity_wip_keluar,
                f.n_sisa n_quantity_wip_sisa,
                c.i_color,
                e.id AS id_color,
                e.e_color_name,
                a.e_remark
            FROM
                tm_retur_masuk_pengadaan_item a
            INNER JOIN tm_retur_masuk_pengadaan b ON
                (a.id_document = b.id
                    /* AND a.id_company = b.id_company */)
            INNER JOIN tr_product_wip c ON
                (a.id_product_wip = c.id
                    /* AND a.id_company = c.id_company
                    AND a.id_product_wip = c.id
                    AND a.id_company = c.id_company */)
            INNER JOIN tr_product_base bs ON
                (bs.i_product_wip = c.i_product_wip
                    AND c.i_color = bs.i_color
                    AND bs.id_company = c.id_company)
            INNER JOIN tm_stbjahit_retur_item f ON
                (a.id_document_reff = f.id_document
                    /* AND a.id_company = f.id_company */
                    AND f.id_product = bs.id)
            INNER JOIN tr_color e ON
                (c.i_color = e.i_color
                    AND c.id_company = e.id_company)
            WHERE
                a.id_document = '$id'
                /* AND b.id_company = '$this->id_company' */
            ORDER BY a.id");
        // return $this->db->query("SELECT
        //         a.id, 
        //         a.id_document,
        //         a.id_product_wip,
        //         c.i_product_wip,
        //         c.e_product_wipname,
        //         a.n_quantity_wip as n_quantity_wip_masuk,
        //         f.n_quantity_wip as n_quantity_wip_keluar,
        //         f.n_sisa_wip as n_quantity_wip_sisa,
        //         c.i_color,
        //         e.id as id_color,
        //         e.e_color_name,
        //         /* a.id_material,
        //         g.i_material,
        //         g.e_material_name,
        //         a.n_quantity_material as n_quantity_material_masuk,
        //         f.n_quantity_material as n_quantity_material_keluar,
        //         f.n_sisa_material as n_quantity_ma_sisa, */
        //         a.e_remark
        //     FROM
        //         tm_retur_masuk_pengadaan_item a 
        //     INNER JOIN 
        //         tm_retur_masuk_pengadaan b
        //         ON (a.id_document = b.id AND a.id_company = b.id_company)
        //     INNER JOIN 
        //         tm_stbjahit_retur_item f
        //         ON (a.id_document_reff = f.id_document AND a.id_company = f.id_company)
        //     INNER JOIN 
        //         tr_product_wip c
        //         ON (a.id_product_wip = c.id 
        //         AND a.id_company = c.id_company AND f.id_product_wip = c.id AND f.id_company = c.id_company) 
        //     INNER JOIN 
        //         tr_color e
        //         ON (c.i_color = e.i_color AND c.id_company = e.id_company)
        //         /* INNER JOIN 
        //         tr_material g
        //         ON (a.id_material = g.id AND a.id_company = g.id_company AND f.id_material = g.id AND f.id_company = g.id_company)  */
        //     WHERE 
        //         a.id_document = '$id'
        //         AND b.id = '$id'
        //         AND b.id_company = '$this->idcompany'",
        //     false
        // );
    }

    public function updateheader(
        $id,
        $idocument,
        $ibagian,
        $datedocument,
        $ireff,
        $eremark,
        $itujuan
    ) {
        $split = explode("|",$itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $data = [
            "i_document" => $idocument,
            "d_document" => $datedocument,
            "id_document_reff" => $ireff,
            "i_bagian" => $ibagian,
            "e_remark" => $eremark,
            "d_update" => current_datetime(),
            "i_tujuan" => $itujuan
        ];

        $this->db->where("id", $id);
        // $this->db->where("id_company", $this->idcompany);
        $this->db->update("tm_retur_masuk_pengadaan", $data);
    }

    public function deletedetail($id)
    {
        $this->db->query(
            "DELETE FROM tm_retur_masuk_pengadaan_item WHERE id_document='$id'",
            false
        );
    }

    // public function updatedetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc){
    //     $data = array(
    //                     'n_quantity_wip'      => $nquantitywipmasuk,
    //                     'n_quantity_material' => $nquantitybahanmasuk,
    //                     'e_remark'            => $edesc,
    //     );

    //     $this->db->where('id_document', $id);
    //     $this->db->where('id_document_reff', $ireff);
    //     $this->db->where('id_product_wip', $idproductwip);
    //     $this->db->where('id_material', $idmaterial);
    //     $this->db->where('id_company', $this->idcompany);
    //     $this->db->update('tm_retur_masuk_pengadaan_item', $data);
    // }
}
/* End of file Mmaster.php */
