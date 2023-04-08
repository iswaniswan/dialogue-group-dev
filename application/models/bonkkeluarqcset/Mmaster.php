<?php
defined("BASEPATH") or exit("No direct script access allowed");

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($folder, $i_menu, $dfrom, $dto)
    {
        $idcompany = $this->session->userdata("id_company");
        $cek = $this->db->query(
            "SELECT
                i_bagian
            FROM
                tm_keluar_qcset
            WHERE
                i_status <> '5'
                and d_keluar_qcset between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" .
                $this->session->userdata("i_departement") .
                "'
                        /*AND i_level = '" .
                $this->session->userdata("i_level") .
                "'*/
                        AND username = '" .
                $this->session->userdata("username") .
                "'
                        AND id_company = '$idcompany')

        ",
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
                        /*AND i_level = '" .
                    $this->session->userdata("i_level") .
                    "'*/
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
                a.i_keluar_qcset,
                to_char(a.d_keluar_qcset, 'dd-mm-yyyy') as d_keluar_qcset,
                a.i_tujuan,
                b.e_bagian_name,
                to_char(a.d_receive_pengadaan, 'dd-mm-yyyy') as d_receive_pengadaan,
                a.e_remark,
                a.id_company,
                a.i_status,
                c.e_status_name,
                a.i_bagian,
                c.label_color,
                f.i_level,
			    l.e_level_name,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' as i_menu,
                '$folder' AS folder
            FROM
                tm_keluar_qcset a 
                JOIN
                    tr_bagian b 
                    ON (a.i_tujuan = b.i_bagian AND a.id_company = b.id_company) 
                JOIN
                    tr_status_document c 
                    ON (a.i_status = c.i_status) 
                LEFT JOIN tr_menu_approve f ON
                    (a.i_approve_urutan = f.n_urut
                    AND f.i_menu = '$i_menu')
                LEFT JOIN public.tr_level l ON
                    (f.i_level = l.i_level)
                WHERE 
                    a.id_company = '$idcompany'
                AND
                    a.i_status <> '5'
            $bagian
            ORDER BY
                a.i_keluar_qcset ASC
            ",
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
            $i_status = $data["i_status"];
            $i_level = $data['i_level'];
            $dfrom = $data["dfrom"];
            $dto = $data["dto"];
            $i_bagian = $data["i_bagian"];
            $data = "";

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if (
                    $i_status == "1" ||
                    $i_status == "2" ||
                    $i_status == "3" ||
                    $i_status == "7"
                ) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 4) && $i_status == "1") {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg mr-3'></i></a>";
            }

            if (check_role($i_menu, 5) && ($i_status == '6')) {
                $data .= "<a href=\"#\" title='Print STB' onclick='cetak(\"$id\",\"$dfrom\",\"$dto\",\"$i_bagian\"); return false;'><i class='ti-printer text-warning fa-lg mr-3'></i></a>";
            }
            
            return $data;
        });

        $datatables->hide("folder");
        $datatables->hide("i_menu");
        $datatables->hide("label_color");
        $datatables->hide("dfrom");
        $datatables->hide("dto");
        $datatables->hide("id_company");
        $datatables->hide("id");
        $datatables->hide("i_status");
        $datatables->hide("i_bagian");
        $datatables->hide("i_tujuan");
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function bagian()
    {
        /* $this->db->select("a.id, a.i_bagian, e_bagian_name")->distinct();
        $this->db->from("tr_bagian a");
        $this->db->join(
            "tr_departement_cover b",
            "b.i_bagian = a.i_bagian",
            "inner"
        );
        $this->db->where(
            "i_departement",
            $this->session->userdata("i_departement")
        );
        $this->db->where("username", $this->session->userdata("username"));
        $this->db->where(
            "b.id_company",
            $this->session->userdata("id_company")
        );
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

    public function tujuan($i_menu, $idcompany)
    {
        return $this->db->query("SELECT 
                a.*,
                b.e_bagian_name 
            FROM 
                tr_tujuan_menu a
            JOIN tr_bagian b 
            ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
            WHERE
                a.i_menu = '$i_menu'
                AND a.id_company = '$idcompany'");
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $id_company = $this->session->userdata("id_company");
        $cek = $this->db->query("SELECT 
                substring(i_keluar_qcset, 1, 3) AS kode 
            FROM tm_keluar_qcset 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = "STB";
        }
        $query = $this->db->query(
            "SELECT
                max(substring(i_keluar_qcset, 10, 4)) AS max
            FROM
                tm_keluar_qcset
            WHERE to_char (d_keluar_qcset, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND substring(i_keluar_qcset, 1, 3) = '$kode'
            AND substring(i_keluar_qcset, 5, 4) = '$thbl'
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

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select("i_keluar_qcset");
        $this->db->from("tm_keluar_qcset");
        $this->db->where("i_keluar_qcset", $kode);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select("i_keluar_qcset");
        $this->db->from("tm_keluar_qcset");
        $this->db->where("i_keluar_qcset", $kode);
        $this->db->where("i_keluar_qcset <>", $kodeold);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    /*----------  CARI BARANG  ----------*/

    public function product($cari)
    {
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query("SELECT DISTINCT 
                a.id,
                a.i_product_wip,
                UPPER(a.e_product_wipname) AS e_product_wipname,
                b.e_color_name
            FROM tm_panel_item x
            INNER JOIN tr_product_wip a ON
                (a.id = x.id_product_wip)
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color
                AND a.id_company = b.id_company)
            WHERE
                a.id_company = '$idcompany'
                AND a.f_status = 't'
                AND b.f_status = 't'
                AND (a.i_product_wip ILIKE '%$cari%'
                OR a.e_product_wipname ILIKE '%$cari%')
            ORDER BY
                a.i_product_wip ASC", false);
    }

    /*----------  CARI REMARK  ----------*/

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

    public function jeniskeluar()
    {
        $jenis = array(1, 2);
        $this->db->where_in('id', $jenis);
        return $this->db->get("tr_jenis_barang_keluar");
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id, $i_bagian, $id_marker)
    {
        $i_periode = date('Ym');
        // $i_periode = date('202208');
        $d_jangka_awal  = '9999-09-09';
        $d_jangka_akhir = '9999-09-29';
        $d_from = date('Y-m-01');
        // $d_from = date('Y-08-01');
        $d_to = date('Y-m-t', strtotime($d_from));
        return $this->db->query(
            "SELECT DISTINCT a.id, a.id_product_wip, a.i_panel, a.bagian,
                a.n_qty_penyusun, c.i_product_wip, UPPER(c.e_product_wipname) AS e_product_wipname,
                d.id AS id_color, d.e_color_name, b.id AS id_material, b.i_material,
                UPPER(b.e_material_name) AS e_material_name, coalesce(n_saldo_akhir,0) n_saldo_akhir
            FROM tm_panel_item a       
            INNER JOIN tr_material b ON (b.id = a.id_material)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
            -- inner join tr_polacutting_new g ON(g.id_product_wip = a.id_product_wip AND a.id_material = g.id_material)        
            INNER JOIN tr_color d ON (
                d.i_color = c.i_color
                AND d.id_company = c.id_company
            )
            INNER JOIN tm_panel e ON (e.id_product_wip = a.id_product_wip)
            LEFT JOIN (
                SELECT id_panel_item, n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengesettan
                ('$this->id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$d_from', '$d_to', '$i_bagian')
            ) f ON (f.id_panel_item = a.id)
            WHERE a.id_product_wip = '$id' AND a.id_marker = '$id_marker'
                AND a.f_status = 't' AND c.id_company = '$this->id_company'
            ORDER BY
                c.i_product_wip, b.i_material ASC"
        );
    }

    /*----------  SIMPAN DATA  ----------*/

    public function runningid()
    {
        $this->db->select("max(id) AS id");
        $this->db->from("tm_keluar_qcset");
        return $this->db->get()->row()->id + 1;
    }

    public function insertheader(
        $id,
        $ibonk,
        $ibagian,
        $datebonk,
        $itujuan,
        $eremarkh,
        $ijenis
    ) {
        $data = [
            "id" => $id,
            "id_company" => $this->session->userdata("id_company"),
            "i_keluar_qcset" => $ibonk,
            "d_keluar_qcset" => $datebonk,
            "i_bagian" => $ibagian,
            "i_tujuan" => $itujuan,
            "e_remark" => $eremarkh,
            "i_status" => "1",
            "d_entry" => current_datetime(),
            "id_jenis_barang_keluar" => $ijenis,
        ];
        $this->db->insert("tm_keluar_qcset", $data);
    }

    public function insertdetail(
        $id,
        $idproductwip,
        $idmarker,
        $idpanel,
        $nquantitywip,
        $npenyusun,
        $nqtysisa,
        $edesc
    ) {
        $data = [
            "id_company" => $this->session->userdata("id_company"),
            "id_keluar_qcset" => $id,
            "id_product_wip" => $idproductwip,
            "id_marker" => $idmarker,
            "n_quantity_product_wip" => $nquantitywip,
            "id_panel_item" => $idpanel,
            "n_quantity_penyusun" => $npenyusun,
            "n_quantity_akhir" => $nqtysisa,
            "e_remark" => $edesc,
        ];
        $this->db->insert("tm_keluar_qcset_item", $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query(
            "
                                    SELECT
                                       a.id,
                                       a.i_keluar_qcset,
                                       to_char(a.d_keluar_qcset, 'dd-mm-yyyy') as d_keluar_qcset,
                                       a.i_bagian,
                                       a.i_tujuan,
                                       a.d_receive_pengadaan,
                                       a.e_remark,
                                       a.i_status,
                                       a.id_jenis_barang_keluar
                                    FROM
                                       tm_keluar_qcset a 
                                    WHERE
                                       a.id = '$id'
                                    AND 
                                       a.id_company = '$idcompany' 
                                    ORDER BY
                                       d_keluar_qcset asc
                                ",
            false
        );
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id, $i_bagian)
    {
        $i_periode = date('Ym');
        // $i_periode = date('202208');
        $d_jangka_awal  = '9999-09-09';
        $d_jangka_akhir = '9999-09-29';
        $d_from = date('Y-m-01');
        // $d_from = date('Y-08-01');
        $d_to = date('Y-m-t', strtotime($d_from));
        return $this->db->query(
            "SELECT
                b.id_product_wip, c.i_product_wip, c.e_product_wipname, d.id,
                c.i_color, d.e_color_name, b.n_quantity_product_wip, b.id_panel_item,
                e.i_material, e.e_material_name, b.n_quantity_penyusun, b.n_quantity_akhir,
                b.e_remark, f.i_panel, f.bagian, coalesce(g.n_saldo_akhir,0) n_saldo_akhir,
                b.id_marker, h.e_marker_name
            FROM tm_keluar_qcset_item b  
            JOIN tr_product_wip c ON (
                b.id_product_wip = c.id AND b.id_company = c.id_company
            )
            JOIN tr_color d ON (
                c.i_color = d.i_color AND b.id_company = d.id_company
            )
            JOIN tm_panel_item f ON (f.id = b.id_panel_item) 
            JOIN tr_material e ON (e.id = f.id_material)
            inner join tr_marker h ON (h.id = b.id_marker)
            LEFT JOIN (
                SELECT id_panel_item, n_saldo_akhir 
                FROM f_mutasi_saldoawal_pengesettan
                ('$this->id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$d_from', '$d_to', '$i_bagian')
            ) g ON (g.id_panel_item = f.id)
            WHERE b.id_keluar_qcset = '$id' 
            AND  b.id_company = '$this->id_company'
            ORDER BY id_product_wip"
        );
    }

    public function updateheader(
        $id,
        $ibonk,
        $datebonk,
        $ibagian,
        $itujuan,
        $eremarkh,
        $ijenis
    ) {
        $data = [
            "i_keluar_qcset" => $ibonk,
            "d_keluar_qcset" => $datebonk,
            "i_bagian" => $ibagian,
            "i_tujuan" => $itujuan,
            "e_remark" => $eremarkh,
            "d_update" => current_datetime(),
            "id_jenis_barang_keluar" => $ijenis,
        ];
        $this->db->where("id", $id);
        $this->db->update("tm_keluar_qcset", $data);
    }

    public function deletedetail($id)
    {
        $this->db->query(
            "DELETE FROM tm_keluar_qcset_item WHERE id_keluar_qcset='$id'",
            false
        );
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_keluar_qcset a
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_qcset');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_qcset', $data);
    }

    public function session_company()
    {
        $id = $this->session->userdata('id_company');

        $sql = "SELECT * FROM public.company WHERE id='$id'";

        return $this->db->query($sql);
    }

    public function get_kode_lokasi_bagian($i_bagian, $id_company=null) 
    {
        if ($id_company == null) {
            $id_company = $this->session->id_company;
        }

        $sql = "SELECT e_kode_lokasi
                FROM tr_bagian tb
                INNER JOIN tr_type tt ON tt.i_type = tb.i_type AND tb.id_company = '$id_company'
                AND tb.i_bagian = '$i_bagian'";

        return $this->db->query($sql);
    }

    public function dataedit_print($id)
    {
        $idcompany = $this->session->userdata("id_company");

        $sql = "SELECT
                    a.id,
                    a.i_keluar_qcset AS i_document,
                    to_char(a.d_keluar_qcset, 'dd-mm-yyyy') as date_document,
                    a.i_bagian,
                    a.i_tujuan,
                    a.d_receive_pengadaan,
                    a.e_remark,
                    a.i_status,
                    a.id_jenis_barang_keluar,
                    b.e_bagian_name,
                    b2.e_bagian_name AS e_bagian_receive_name
                FROM tm_keluar_qcset a 
                INNER JOIN tr_bagian b ON b.i_bagian = a.i_bagian AND b.id_company = a.id_company
                INNER JOIN tr_bagian b2 ON b2.i_bagian = a.i_tujuan AND b2.id_company = a.id_company
                WHERE a.id = '$id'
                AND a.id_company = '$idcompany' 
                ORDER BY d_keluar_qcset asc";

        return $this->db->query($sql, false);
    }
}
/* End of file Mmaster.php */
