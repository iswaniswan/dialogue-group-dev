<?php
defined("BASEPATH") or exit("No direct script access allowed");

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public $idcompany;
    public $i_menu = "2090401";

    function __construct()
    {
        parent::__construct();
        $this->idcompany = $this->session->id_company;
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

    public function bagianpengirim($cari, $ibagian)
    {
        $cari = str_replace("'", "", $cari);
        /* return $this->db->query(
            "SELECT DISTINCT
                a.i_bagian,
                b.e_bagian_name
            FROM
                tr_tujuan_menu a
            JOIN tr_bagian b 
                ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            WHERE
                b.id_company = '$this->idcompany'
                AND a.i_menu = '$this->i_menu'
                AND a.id_company = '$this->idcompany'
                AND a.i_bagian ILIKE '%$cari%'
                AND b.e_bagian_name ILIKE '%$cari%'
                AND i_bagian = '$ibagian'
            ORDER BY
                b.e_bagian_name",
            false
        ); */
        return $this->db->query(
            "SELECT DISTINCT a.id, a.i_bagian, a.e_bagian_name||' - '||d.name as e_bagian_name 
            FROM tr_bagian a
            INNER JOIN tm_keluar_pengadaan b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tm_keluar_pengadaan_item_new c ON (c.id_keluar_pengadaan = b.id)
            INNER JOIN public.company d ON (d.id = a.id_company)
            WHERE b.i_tujuan = '$ibagian' AND b.id_company_bagian = '$this->idcompany'
            AND a.e_bagian_name ILIKE '%$cari%' AND c.n_sisa_wip > 0 AND b.i_status = '6'
            ORDER BY e_bagian_name ASC;",
            false
        );
    }

    public function referensi($cari, $iasal, $ibagian)
    {
        $cari = str_replace("'", "", $cari);

        /** dokumen gantung, yg punya status draft, change request, wait approve  */
        $sql_pending_penerimaan = "SELECT tmui.id_referensi_item
                                    FROM tm_masuk_unitjahit_item tmui 
                                    INNER JOIN tm_masuk_unitjahit tmu ON tmu.id = tmui.id_document 
                                    WHERE tmu.i_status IN ('1', '2', '3')";

        return $this->db->query(
            "SELECT DISTINCT
                a.id||'|'||c.id as id,
                a.i_keluar_pengadaan||' - '||c.e_jenis_name as i_document,
                to_char(a.d_keluar_pengadaan, 'dd-mm-yyyy') AS d_document, 
                a.e_remark
            FROM
                tm_keluar_pengadaan a
            INNER JOIN tm_keluar_pengadaan_item_new b
                    on (a.id = b.id_keluar_pengadaan AND a.id_company = b.id_company)
            INNER JOIN tr_jenis_barang_keluar c ON (c.id = a.id_jenis_barang_keluar)
            WHERE
                a.i_bagian = '$iasal'
                AND a.i_status = '6'
                AND a.id_company_bagian = '$this->idcompany'
                AND b.n_sisa_wip <> 0
                AND a.i_keluar_pengadaan ILIKE '%$cari%'
                AND a.i_tujuan = '$ibagian'
                AND b.id NOT IN ($sql_pending_penerimaan)
                 /*and a.id not in (select id_reff from tm_masuk_unitjahit where i_status in ('2','6'))*/
            ORDER BY
                i_document,
                d_document
        ",false);
    }

    public function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != "" && $dto != "") {
            $dfrom = date("Y-m-d", strtotime($dfrom));
            $dto = date("Y-m-d", strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query(
            "SELECT 
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document,'dd-mm-YYYY') as d_document,
                a.i_bagian,
                b.e_bagian_name,
                a.i_bagian_pengirim,
                c.e_bagian_name||' - '||cc.name as e_bagian_pengirim,
                a.id_reff,
                d.i_keluar_pengadaan,
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
                tm_masuk_unitjahit a
            INNER JOIN tr_bagian b
                ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_status_document e
                ON (a.i_status = e.i_status)                            
            LEFT JOIN tm_keluar_pengadaan d
                ON (a.id_reff = d.id /* AND a.id_company = d.id_company */)
            LEFT JOIN tr_bagian c
                ON (c.i_bagian = d.i_bagian AND d.id_company = c.id_company)
            LEFT JOIN public.company cc ON (cc.id = c.id_company)
            LEFT JOIN tr_menu_approve f ON
                    (a.i_approve_urutan = f.n_urut
                    AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                    (f.i_level = l.i_level)
            WHERE 
                a.id_company = '$this->idcompany'
                AND a.i_status <> '5'
                $where
            ORDER BY
                a.i_document,
                d_document",
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
            $ibagian = trim($data["i_bagian"]);
            $i_menu = $data["i_menu"];
            $folder = $data["folder"];
            $dfrom = $data["dfrom"];
            $dto = $data["dto"];
            $i_status = trim($data["i_status"]);
            $i_level = $data['i_level'];
            $data = "";

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3 fa-lg'></i></a>";
            }
            if (
                check_role($i_menu, 3) &&
                $i_status != "5" &&
                $i_status != "6" &&
                $i_status != "9"
            ) {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
                }
            }
            if (
                check_role($i_menu, 4) &&
                ($i_status != "4" &&
                    $i_status != "6" &&
                    $i_status != "9" &&
                    $i_status != "2")
            ) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close text-danger mr-3 fa-lg'></i></a>";
            }

            return $data;
        });
        $datatables->hide("i_menu");
        $datatables->hide("folder");
        $datatables->hide("id");
        $datatables->hide("i_bagian");
        // $datatables->hide("e_bagian_name");
        $datatables->hide("i_bagian_pengirim");
        $datatables->hide("id_reff");
        $datatables->hide("i_status");
        $datatables->hide("label_color");
        $datatables->hide("dfrom");
        $datatables->hide("dto");
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select("i_document");
        $this->db->from("tm_masuk_unitjahit");
        $this->db->where("i_document", $kode);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select("i_document");
        $this->db->from("tm_masuk_unitjahit");
        $this->db->where("i_document", $kode);
        $this->db->where("i_document <>", $kodeold);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    public function getdataheader($idreff, $ipengirim)
    {
        $sql = "SELECT to_char(d_keluar_pengadaan, 'dd-mm-yyyy') as d_document
                FROM tm_keluar_pengadaan
                WHERE id = '$idreff' AND i_bagian = '$ipengirim' AND id_company_bagian = '$this->idcompany'";
        return $this->db->query($sql, false);
    }

    public function getdataitem($idreff, $ipengirim)
    {
        /** dokumen gantung, yg punya status draft, change request, wait approve  */
        $sql_pending_penerimaan = "SELECT tmui.id_referensi_item
                                    FROM tm_masuk_unitjahit_item tmui 
                                    INNER JOIN tm_masuk_unitjahit tmu ON tmu.id = tmui.id_document 
                                    WHERE tmu.i_status IN ('1', '2', '3')";

        return $this->db->query(
            "SELECT DISTINCT 
                a.id,
                a.id_product_wip,
                '0' as id_material,
                c.i_product_wip,
                c.e_product_wipname,
                a.n_quantity_product_wip as n_quantity_wip,
                a.n_sisa_wip as n_quantity_wip_sisa,
                c.i_color, 
                e.id as id_color,
                e.e_color_name,
                '0' as i_material,
                '0' as e_material_name,
                a.n_quantity_product_wip as n_quantity,
                a.n_sisa_wip as n_quantity_sisa,
                a.e_remark,
                to_char(a.i_periode, 'FMMonth YYYY') periode,
                to_char(a.i_periode, 'YYYY-MM') i_periode
            FROM
                tm_keluar_pengadaan_item_new a
                LEFT JOIN tm_keluar_pengadaan b
                ON (a.id_keluar_pengadaan = b.id/*  AND a.id_company = b.id_company */)
                INNER JOIN tr_product_wip c
                ON (a.id_product_wip = c.id/*  AND a.id_company = c.id_company */)
                INNER JOIN tr_color e
                ON (c.i_color = e.i_color AND c.id_company = e.id_company)
            WHERE
                b.id = '$idreff' 
                AND a.id_keluar_pengadaan = '$idreff'
                AND b.id_company_bagian = '$this->idcompany'
                AND b.i_bagian = '$ipengirim'
                AND a.n_sisa_wip <> 0
                AND a.id NOT IN ($sql_pending_penerimaan)
                ",false);
    }

    public function runningid()
    {
        $this->db->select("max(id) AS id");
        $this->db->from("tm_masuk_unitjahit");
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query(
            "SELECT substring(i_document, 1, 3) AS kode 
                FROM tm_masuk_unitjahit
                WHERE i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '" .
                $this->session->userdata("id_company") .
                "'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = "BBM";
        }
        $query = $this->db->query(
            "SELECT
              max(substring(i_document, 10, 4)) AS max
          FROM
            tm_masuk_unitjahit
          WHERE to_char (d_document, 'yymm') = '$thbl'
          AND i_status <> '5'
          AND i_bagian = '$ibagian'
          AND i_document ILIKE '%$kode%'
          /* AND substring(i_document, 5, 2) = substring('$thbl',1,2) */
          AND id_company = '" .
                $this->session->userdata("id_company") .
                "'
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
        $ibonm,
        $dbonm,
        $ikodemaster,
        $iasal,
        $ireff,
        $eremark,
        $ijenis
    ) {
        $i_document = $this->runningnumber(format_ym($dbonm), format_Y($dbonm), $ikodemaster);
        $data = [
            "id" => $id,
            "id_company" => $this->idcompany,
            "i_document" => $i_document,
            "d_document" => $dbonm,
            "i_bagian" => $ikodemaster,
            "i_bagian_pengirim" => $iasal,
            "id_reff" => $ireff,
            "e_remark" => $eremark,
            "d_entry" => current_datetime(),
            "id_jenis_barang_keluar" => $ijenis
        ];
        $this->db->insert("tm_masuk_unitjahit", $data);
    }



    function insert_detail($id, $id_reff, $id_product_wip, $n_quantity, $n_quantity_bs, $e_note, $id_referensi_item) {
        $data = [
            "id_company" => $this->idcompany,
            "id_document" => $id,
            "id_reff" => $id_reff,
            "id_product_wip" => $id_product_wip,
            "n_quantity_wip" => $n_quantity,
            "n_quantity_wip_sisa" => $n_quantity,
            "qty_bs" => $n_quantity_bs,
            "qty_sisa_bs" => $n_quantity_bs,
            "e_remark" => $e_note,
            "id_referensi_item"=>$id_referensi_item
            // "id_material" => $idmaterial,
            // "n_quantity" => $nquantitybahanmasuk,
            // "n_quantity_sisa" => $nquantitybahanmasuk,
        ];
        $this->db->insert("tm_masuk_unitjahit_item", $data);
    }

    function insertdetail(
        $id,
        $ireff,
        $idproductwip,
        $idmaterial,
        $nquantitywipmasuk,
        $nquantitybahanmasuk,
        $edesc,
        $nquantitybs,
        $id_referensi_item
    ) {
        $data = [
            "id_company" => $this->idcompany,
            "id_document" => $id,
            "id_reff" => $ireff,
            "id_product_wip" => $idproductwip,
            "n_quantity_wip" => $nquantitywipmasuk,
            "n_quantity_wip_sisa" => $nquantitywipmasuk,
            "id_material" => $idmaterial,
            "n_quantity" => $nquantitybahanmasuk,
            "qty_bs" => $nquantitybs,
            "qty_sisa_bs" => $nquantitybs,
            "n_quantity_sisa" => $nquantitybahanmasuk,
            "e_remark" => $edesc,
            "id_referensi_item"=>$id_referensi_item
        ];
        $this->db->insert("tm_masuk_unitjahit_item", $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_unitjahit a
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
                    $query = $this->db->query(
                        " SELECT 
                            a.id_referensi_item,
                            a.id_product_wip,
                            a.n_quantity_wip, 
                            a.n_quantity,
                            a.id_reff,
                            b.i_bagian_pengirim
                        FROM 
                            tm_masuk_unitjahit_item a
                        LEFT JOIN tm_masuk_unitjahit b ON (a.id_document = b.id 
                            AND a.id_company = b.id_company)
                        WHERE 
                            a.id_document = '$id' ",
                        false
                    );
                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $key) {
                            $nsisa = $this->db->query(
                                "SELECT
                                    n_sisa_wip
                                FROM
                                    tm_keluar_pengadaan_item_new                       
                                WHERE
                                    id = '$key->id_referensi_item'
                                    AND n_sisa_wip >= '$key->n_quantity_wip'",
                                false
                            );

                            if ($nsisa->num_rows() > 0) {
                                $this->db->query(
                                    "UPDATE
                                        tm_keluar_pengadaan_item_new
                                    SET
                                        n_quantity_product_wip = $key->n_quantity_wip,
                                        n_sisa_wip = 0,
                                        f_item_complete = 't'
                                    WHERE
                                        id = '$key->id_referensi_item'",
                                    false
                                );

                                $this->db->query(
                                    "UPDATE
                                        tm_keluar_pengadaan
                                    SET
                                        f_receive_jahit = 't',
                                        d_receive_jahit = current_date
                                    WHERE
                                        id = '$key->id_reff'
                                        AND i_bagian = '$key->i_bagian_pengirim'
                                        AND id_company = '$this->id_company'",
                                    false
                                );
                            } else {
                                $this->db->query(
                                    "UPDATE
                                        tm_masuk_unitjahit_item
                                    SET
                                        n_quantity_wip = 0, 
                                        n_quantity_wip_sisa = 0
                                    WHERE
                                        id_product_wip = '$key->id_product_wip'
                                        AND id_document = '$id'",
                                    false
                                );
                            }
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_unitjahit');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_unitjahit', $data);
    }

    public function changestatus_20211214($id, $istatus)
    {
        if ($istatus == "6") {
            $query = $this->db->query(
                "
                                      SELECT 
                                        a.id_reff, 
                                        a.id_product_wip, 
                                        a.id_material, 
                                        a.n_quantity_wip, 
                                        a.n_quantity,
                                        b.i_bagian_pengirim
                                      FROM 
                                        tm_masuk_unitjahit_item a
                                        LEFT JOIN tm_masuk_unitjahit b
                                          ON (a.id_document = b.id AND a.id_company = b.id_company)
                                      WHERE 
                                        a.id_document = '$id' 
                                      ",
                false
            );
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query(
                        "
                                            SELECT
                                                n_sisa_wip,
                                                n_sisa_material
                                            FROM
                                                tm_keluar_pengadaan_item_new                       
                                            WHERE
                                                id_keluar_pengadaan = '$key->id_reff'
                                                AND id_product_wip = '$key->id_product_wip'
                                                AND id_material = '$key->id_material'
                                                AND id_company = '" .
                            $this->session->userdata("id_company") .
                            "'
                                                AND n_sisa_wip >= '$key->n_quantity_wip'
                                                AND n_sisa_material >= '$key->n_quantity'
                                        ",
                        false
                    );

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query(
                            "
                                      UPDATE
                                          tm_keluar_pengadaan_item_new
                                      SET
                                          n_sisa_wip = n_sisa_wip - $key->n_quantity_wip,
                                          n_sisa_material = n_sisa_material - $key->n_quantity
                                      WHERE
                                          id_keluar_pengadaan = '$key->id_reff'
                                          AND id_product_wip = '$key->id_product_wip'
                                          AND id_material = '$key->id_material'
                                          AND id_company = '" .
                                $this->session->userdata("id_company") .
                                "'
                                  ",
                            false
                        );

                        $this->db->query(
                            "
                                      UPDATE
                                          tm_keluar_pengadaan
                                      SET
                                          f_receive_jahit = 't',
                                          d_receive_jahit = NOW()
                                      WHERE
                                          id = '$key->id_reff'
                                          AND i_bagian = '$key->i_bagian_pengirim'
                                          AND id_company = '" .
                                $this->session->userdata("id_company") .
                                "'
                                  ",
                            false
                        );
                    } else {
                        die();
                    }
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
        $this->db->update("tm_masuk_unitjahit", $data);
    }

    public function estatus($istatus)
    {
        $this->db->select("e_status_name");
        $this->db->from("tr_status_document");
        $this->db->where("i_status", $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function cek_data($id, $ibagian)
    {
        return $this->db->query(
            "SELECT 
                a.id,
                a.i_document, 
                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                a.id_reff,
                d.i_keluar_pengadaan as i_reff,
                to_char(d.d_keluar_pengadaan, 'dd-mm-yyyy') as d_reff,
                a.i_bagian,
                b.e_bagian_name,
                a.i_bagian_pengirim,
                c.e_bagian_name as e_bagian_pengirim,
                a.e_remark,
                a.i_status,
                a.id_jenis_barang_keluar,
                f.e_jenis_name
            FROM
                tm_masuk_unitjahit a
            INNER JOIN tr_bagian b
                ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            LEFT JOIN tm_keluar_pengadaan d
                ON (a.id_reff = d.id)
            LEFT JOIN tr_bagian c
                ON (c.i_bagian = d.i_bagian AND d.id_company = c.id_company)
            LEFT JOIN tr_jenis_barang_keluar f ON (f.id = a.id_jenis_barang_keluar)
            WHERE 
                a.id  = '$id'
                AND a.i_bagian = '$ibagian'
                AND a.id_company = '$this->idcompany'
            ",
            false
        );
    }

    public function cek_datadetail($id, $ibagian)
    {
        return $this->db->query(
            "SELECT DISTINCT
                /* a.id, */ 
                a.id_referensi_item,
                a.id_document,
                a.id_product_wip,
                c.i_product_wip,
                c.e_product_wipname,
                a.n_quantity_wip as n_quantity_wip_masuk,
                f.n_quantity_product_wip as n_quantity_wip_cutting,
                f.n_sisa_wip as n_quantity_wip_sisa,
                c.i_color,
                e.id as id_color,
                e.e_color_name,
                a.id_material,
                '0' as i_material,
                '0' as e_material_name,
                a.n_quantity as n_quantity_masuk,
                f.n_quantity_product_wip as n_quantity_cutting,
                f.n_sisa_wip as n_quantity_sisa,
                a.e_remark,
                a.qty_bs,
                a.qty_sisa_bs,
                to_char(f.i_periode, 'FMMonth YYYY') periode,
                to_char(f.i_periode, 'YYYY-MM') i_periode
            FROM
                tm_masuk_unitjahit_item a 
            INNER JOIN tr_product_wip c ON
                (a.id_product_wip = c.id)
            INNER JOIN tr_color e ON
                (c.i_color = e.i_color
                    AND c.id_company = e.id_company)
            LEFT JOIN tm_masuk_unitjahit b ON
                (a.id_document = b.id)
            LEFT JOIN tm_keluar_pengadaan_item_new f ON
                (a.id_referensi_item = f.id)
            WHERE 
                a.id_document = '$id'
                AND b.id = '$id'
                AND b.i_bagian = '$ibagian'
                AND b.id_company = '$this->idcompany'",
            false
        );
    }

    public function updateheader(
        $id,
        $ikodemaster,
        $ibonm,
        $dbonm,
        $eremark,
        $ireff,
        $ijenis
    ) {
        $data = [
            /* "i_document" => $ibonm,
            "i_bagian" => $ikodemaster, */
            "d_document" => $dbonm,
            "id_reff" => $ireff,
            "e_remark" => $eremark,
            "d_update" => current_datetime(),
            "id_jenis_barang_keluar" => $ijenis
        ];

        $this->db->where("id", $id);
        $this->db->where("id_company", $this->idcompany);
        $this->db->where("i_bagian", $ikodemaster);
        $this->db->update("tm_masuk_unitjahit", $data);
    }

    public function deletedetail($id)
    {
        $this->db->query(
            "DELETE FROM tm_masuk_unitjahit_item WHERE id_document='$id'",
            false
        );
    }

    // public function updatedetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc)
    // {
    //     $data = array(
    //                     'n_quantity_wip'      => $nquantitywipmasuk,
    //                     'n_quantity_wip_sisa' => $nquantitywipmasuk,
    //                     'n_quantity'          => $nquantitybahanmasuk,
    //                     'n_quantity_sisa'     => $nquantitybahanmasuk,
    //                     'e_remark'            => $edesc,
    //     );

    //     $this->db->where('id_document', $id);
    //     $this->db->where('id_product_wip', $idproductwip);
    //     $this->db->where('id_material', $idmaterial);
    //     $this->db->where('id_company', $this->idcompany);
    //     $this->db->update('tm_masuk_unitjahit_item', $data);
    // }
}
/* End of file Mmaster.php */
