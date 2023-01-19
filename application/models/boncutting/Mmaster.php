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
                tm_keluar_cutting_new
            WHERE
                i_status <> '5'
                and d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
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
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_tujuan,
                b.e_bagian_name,
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
                tm_keluar_cutting_new a 
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
                a.i_document ASC
            ",
            false
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add("action", function ($data) {
            $id = trim($data["id"]);
            $i_menu = $data["i_menu"];
            $folder = $data["folder"];
            $i_status = $data["i_status"];            
            $i_level = $data['i_level'];
            $dfrom = $data["dfrom"];
            $dto = $data["dto"];
            $bagian = $data["i_tujuan"];
            $data = "";

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$bagian\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }

            if (check_role($i_menu, 3)) {
                if (
                    $i_status == "1" ||
                    $i_status == "2" ||
                    $i_status == "3" ||
                    $i_status == "7"
                ) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$bagian\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$bagian\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 5)) {
                if ($i_status == "6") {
                    $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer text-warning'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 4) && $i_status == "1") {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
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
                substring(i_document, 1, 3) AS kode 
            FROM tm_keluar_cutting_new 
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
        $query = $this->db->query("SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_keluar_cutting_new
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 4) = '$thbl'
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
        $this->db->select("i_document");
        $this->db->from("tm_keluar_cutting_new");
        $this->db->where("i_document", $kode);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select("i_document");
        $this->db->from("tm_keluar_cutting_new");
        $this->db->where("i_document", $kode);
        $this->db->where("i_document <>", $kodeold);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    /*----------  CARI BARANG  ----------*/

    public function product($cari)
    {
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query("SELECT 
                a.id,
                a.bagian,
                a.i_panel,
                c.i_material,
                c.e_material_name,
                e.e_color_name 
            FROM 
                tm_panel_item a
            INNER JOIN tm_panel b ON 
                (a.id_product_wip = b.id_product_wip)
            INNER JOIN tr_material c ON
                (a.id_material = c.id AND b.id_company = c.id_company)
            INNER JOIN tr_product_wip d ON
                (b.id_product_wip = d.id AND b.id_company = d.id_company)
            LEFT JOIN tr_color e ON
                (d.i_color = e.i_color AND b.id_company = e.id_company)
            WHERE a.f_status = 't'
                AND (a.bagian ILIKE '%$cari%' 
                OR a.i_panel ILIKE '%$cari%'
                OR e.e_color_name ILIKE '%$cari%')
                AND b.id_company = '$this->id_company'",false);
    }

    public function getqty($id,$bagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d',strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("SELECT
                coalesce(a.n_saldo_akhir,0) AS n_saldo_akhir,
                b.bagian,
                b.i_panel
            FROM
                produksi.f_mutasi_saldoawal_pengesettan($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$bagian') a
            INNER JOIN tm_panel_item b ON
                (a.id_panel_item = b.id)
            WHERE
                id_panel_item = $id
                AND id_company = '$this->id_company' 
                ", FALSE);
    }

    public function jeniskeluar()
    {
        $jenis = array(1,2);
        $this->db->where_in('id',$jenis);
        return $this->db->get("tr_jenis_barang_keluar");
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id)
    {
        return $this->db->query("SELECT 
                a.id,
                a.id_product_wip,
                a.i_panel,
                a.bagian,
                a.n_qty_penyusun,
                c.i_product_wip,
                UPPER(c.e_product_wipname) AS e_product_wipname,
                d.id AS id_color,
                d.e_color_name,
                b.id AS id_material,
                b.i_material,
                UPPER(b.e_material_name) AS e_material_name
                FROM tm_panel_item a               
                INNER JOIN tr_material b ON
                (b.id = a.id_material)
                INNER JOIN tr_product_wip c ON
                (c.id = a.id_product_wip)
                INNER JOIN tr_color d ON
                (d.i_color = c.i_color
                AND d.id_company = c.id_company)
                INNER JOIN tm_panel e ON
                (e.id_product_wip = a.id_product_wip)
                WHERE
                a.id_product_wip = '$id'
                AND a.f_status = 't'
                AND c.id_company = '4'
                ORDER BY
                c.i_product_wip,
                b.i_material ASC",
            false
        );
    }

    /*----------  SIMPAN DATA  ----------*/

    public function runningid()
    {
        $this->db->select("max(id) AS id");
        $this->db->from("tm_keluar_cutting_new");
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
            "i_document" => $ibonk,
            "d_document" => $datebonk,
            "i_bagian" => $ibagian,
            "i_tujuan" => $itujuan,
            "e_remark" => $eremarkh,
            "i_status" => "1",
            "d_entry" => current_datetime(),
            "id_jenis_barang_keluar" => $ijenis,
        ];
        $this->db->insert("tm_keluar_cutting_new", $data);
    }

    public function insertdetail($id,$idproduct,$nquantity,$edesc) {
        $data = [
            "id_company" => $this->session->userdata("id_company"),
            "id_document" => $id,
            "id_panel_item" => $idproduct,
            "n_quantity" => $nquantity,
            "e_remark" => $edesc,
        ];
        $this->db->insert("tm_keluar_cutting_item_new", $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query(
            "
                                    SELECT
                                       a.id,
                                       a.i_document,
                                       to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                       a.i_bagian,
                                       a.i_tujuan,
                                       a.e_remark,
                                       a.i_status,
                                       a.id_jenis_barang_keluar
                                    FROM
                                       tm_keluar_cutting_new a 
                                    WHERE
                                       a.id = '$id'
                                    AND 
                                       a.id_company = '$idcompany' 
                                    ORDER BY
                                       d_document asc
                                ",
            false
        );
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id,$bagian)
    {
        $idcompany = $this->session->userdata("id_company");
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d',strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query(
            "
                                    SELECT 
                                        * ,b.id as id_panel, b.bagian , b.i_panel , d.e_color_name , f.n_saldo_akhir
                                        FROM tm_keluar_cutting_item_new a
                                        INNER JOIN
                                        tm_keluar_cutting_new e ON (a.id_document = e.id)
                                        INNER JOIN 
                                        tm_panel_item b ON (a.id_panel_item = b.id)
                                        INNER JOIN 
                                        tr_product_wip c ON (b.id_product_wip = c.id AND c.id_company = a.id_company)
                                        INNER JOIN 
                                        tr_color d ON (c.i_color = d.i_color AND d.id_company = a.id_company) 
                                        INNER JOIN 
                                        f_mutasi_saldoawal_pengesettan($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$bagian') f ON (a.id_panel_item = f.id_panel_item) 
                                    WHERE
                                        a.id_document = '$id'
                                    AND 
                                        a.id_company = '$idcompany'
                              ",
            false
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
            "i_document" => $ibonk,
            "d_document" => $datebonk,
            "i_bagian" => $ibagian,
            "i_tujuan" => $itujuan,
            "e_remark" => $eremarkh,
            "d_update" => current_datetime(),
            "id_jenis_barang_keluar" => $ijenis,
        ];
        $this->db->where("id", $id);
        $this->db->update("tm_keluar_cutting_new", $data);
    }

    public function deletedetail($id)
    {
        $this->db->query(
            "DELETE FROM tm_keluar_cutting_item_new WHERE id_document='$id'",
            false
        );
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_keluar_cutting_new a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2", FALSE)->row();
            if ($istatus == '3') {
            	if ($awal->i_approve_urutan - 1 == 0 ) {
            		$data = array(
	                    'i_status'  => $istatus,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
            	}
            	$this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_cutting_new');", FALSE);
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_cutting_new', $data);
    }
}
/* End of file Mmaster.php */
