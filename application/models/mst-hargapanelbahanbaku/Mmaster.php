<?php
defined("BASEPATH") or exit("No direct script access allowed");
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($dberlaku, $isupplier, $i_menu, $folder)
    {
        $datatables = new Datatables(new CodeigniterAdapter());
        $idcompany = $this->session->userdata("id_company");
        // $now = date("Y-m-d");

        $where = "";
        if ($isupplier == "ALL") {
            $dberlaku = date('Y-m-d', strtotime($dberlaku));
            // $where .= "WHERE 
            //       x.d_berlaku <= to_date('$now', 'yyyy-mm-dd')
            //       AND x.d_akhir_tmp >= to_date('$now','yyyy-mm-dd')";
            $where .= "WHERE '$dberlaku' between x.d_berlaku and x.d_akhir_tmp";
        } else {
            $dberlaku = date('Y-m-d', strtotime($dberlaku));
            // $where .= "WHERE 
            //       <= '$dberlaku'
            //      AND  >= '$dberlaku'
            //      AND x.i_supplier = '$isupplier'";
            $where .= "WHERE '$dberlaku' between x.d_berlaku and x.d_akhir_tmp AND x.i_supplier = '$isupplier'";
        }

        $datatables->query(
            "SELECT DISTINCT
			x.no,
			x.d_akhir_tmp,
			x.id_hargabarang,
			x.i_supplier,
			x.e_supplier_name,
            x.id_panel_item,
			x.i_panel,
            x.bagian,
            x.i_color,
            x.e_color_name,
            x.id_marker,
            x.e_marker_name,
			x.v_price,
			x.d_berlaku,
			x.d_akhir,
			x.i_menu,
			x.folder,
			x.tanggal_berlaku,
			x.isupplier,
			x.id_company,
			x.i_level, 
			x.e_level_name,
			x.i_status, 
			x.e_status_name, 
			x.label_color,
			x.status
		FROM(
			SELECT
			0 as no,
			case when a.d_akhir is not null then a.d_akhir else '5000-01-01' end as d_akhir_tmp,
			a.id as id_hargabarang,
			a.i_supplier,
			b.e_supplier_name,
			a.id_panel_item,
			d.i_panel,
            d.bagian,
            e.i_color,
            g.e_color_name,
            a.id_marker,
            c.e_marker_name,
			a.v_price,
			a.d_berlaku,
			a.d_akhir,
			a.id_company,
			f.i_level, 
			l.e_level_name,
			a.i_status, 
			e_status_name, 
			label_color, 
			case
				when
					a.f_status = TRUE 
				then
					'Aktif' 
				else
					'Tidak Aktif' 
			end
			as status, 
			'$i_menu' as i_menu, 
			'$folder' as folder,
			'$dberlaku' as tanggal_berlaku,
			'$isupplier' as isupplier
			FROM
			tr_supplier_panelprice a 
			inner join tr_status_document h on (h.i_status = a.i_status)
			LEFT JOIN
				tr_supplier b 
				ON a.i_supplier = b.i_supplier and a.id_company = b.id_company
			LEFT JOIN
				tr_marker c 
				on a.id_marker = c.id
            LEFT JOIN 
                tm_panel_item d
                ON a.id_panel_item = d.id and a.id_marker = d.id_marker
            INNER JOIN
                tr_product_wip e
                ON d.id_product_wip = e.id
			left join tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
			left join public.tr_level l on (f.i_level = l.i_level)
            inner join
                tr_color g
                ON e.i_color = g.i_color and e.id_company = g.id_company
			WHERE
				a.id_company = '$idcompany'
				AND a.i_status <> '5'
		) AS x
		$where
		ORDER BY
			x.i_supplier,x.d_berlaku DESC",
            false
        );
        $datatables->edit("i_panel", function($data) {
            $i_panel = $data["i_panel"];
            $e_color_name = $data["e_color_name"];
            $combine = $i_panel . $e_color_name;
            return $combine;
        });
        $datatables->edit("status", function ($data) {
            $idhargabarang = trim($data["id_hargabarang"]);
            $id = trim($data["i_supplier"]);
            $kode = trim($data["id_panel_item"]);
            $folder = $data["folder"];
            $id_menu = $data["i_menu"];
            $status = $data["status"];
            if ($status == "Aktif") {
                $warna = "success";
            } else {
                $warna = "danger";
            }
            $data = "";
            $combine = $id . "|" . $kode . "|" . $idhargabarang;
            if (check_role($id_menu, 3)) {
                $data .= "<a href=\"#\" title='Update Status' onclick='status(\"$combine\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
            } else {
                $data .= "<span class=\"label label-$warna\">$status</span>";
            }
            return $data;
        });

		$datatables->edit('e_status_name', function ($data) {
			$i_status = $data['i_status'];
			if ($i_status == '2') {
				$data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
			}
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit("v_price", function ($data) {
            return "Rp. " . number_format($data["v_price"], 4);
        });

        // $datatables->edit("d_berlaku", function ($data) {
        //     $d_berlaku = $data["d_berlaku"];
        //     if ($d_berlaku == "") {
        //         return "";
        //     } else {
        //         return date("d-m-Y", strtotime($d_berlaku));
        //     }
        // });

        $datatables->edit("d_akhir", function ($data) {
            $d_akhir = $data["d_akhir"];
            if ($d_akhir == "") {
                return "";
            } else {
                return date("d-m-Y", strtotime($d_akhir));
            }
        });

        $datatables->add("action", function ($data) {
            $id = $data["id_hargabarang"];
            $kodepanel = trim($data["id_panel_item"]);
            $isupplier = trim($data["i_supplier"]);
            $i_menu = $data["i_menu"];
            $i_level = $data["i_level"];
            $i_status = $data["i_status"];
            $folder = $data["folder"];
            $dberlaku = $data["d_berlaku"];
            $dfrom = $data["tanggal_berlaku"];
            $suppfilter = $data["isupplier"];
            $data = "";
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$isupplier/$kodepanel/\",\"#main\"); return false;'><i class='ti-eye mr-2 text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$isupplier/$kodepanel/$dberlaku/$suppfilter/$dfrom/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2'></i></a>";
            }
			if (check_role($i_menu, 7) && $i_status == '2') {
			    if (($i_level == $this->session->userdata('i_level') || $this->session->userdata('i_level') == 1) ) {
			        $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$isupplier/$kodepanel/$dberlaku/$suppfilter/$dfrom/\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
			    }
			} 

            return $data;
        });
        $datatables->hide("i_menu");
        $datatables->hide("folder");
        $datatables->hide("i_supplier");
        $datatables->hide("i_color");
        $datatables->hide("e_color_name");
        $datatables->hide("bagian");
        $datatables->hide("id_marker");
        $datatables->hide("id_panel_item");
        $datatables->hide("d_akhir_tmp");
        $datatables->hide("tanggal_berlaku");
        $datatables->hide("isupplier");
        $datatables->hide("id_hargabarang");
        $datatables->hide("id_company");
		$datatables->hide('i_status');
		$datatables->hide('label_color');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
    $datatables->hide('e_status_name');

        return $datatables->generate();
    }

    public function status($isupplier, $id_panel_item, $id)
    {
        $this->db->select("f_status");
        $this->db->from("tr_supplier_panelprice");
        $this->db->where("id", $id);
        $this->db->where("trim(i_supplier)", trim($isupplier));
        $this->db->where("id_panel_item", $id_panel_item);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $status = $row->f_status;
            if ($status == "t") {
                $stat = "f";
            } else {
                $stat = "t";
            }
        }
        $data = [
            "f_status" => $stat,
        ];
        $this->db->where("id", $id);
        $this->db->where("trim(i_supplier)", trim($isupplier));
        $this->db->where("id_panel_item", $id_panel_item);
        $this->db->update("tr_supplier_panelprice", $data);
    }

    public function supplierlist($cari, $idcompany)
    {
        return $this->db->query(
            "
                              SELECT
                                distinct
                                a.i_supplier,
                                b.e_supplier_name
                              FROM 
                                tr_supplier_panelprice a
                                LEFT JOIN tr_supplier b
                                ON (a.i_supplier = b.i_supplier and a.id_company = b.id_company)
                              WHERE
                                a.id_company = '$idcompany'
                              AND b.i_supplier_group = 'KTG02'
                              AND
                                (a.i_supplier ilike '%$cari%' or b.e_supplier_name ilike '%$cari%') 
                              ORDER BY 
                                b.e_supplier_name
                              ",
            false
        );
    }

    public function marker($cari)
    {
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query("SELECT
            *
        FROM
            tr_marker a
        WHERE f_status = 't'
            AND e_marker_name ILIKE '%$cari%';", false);
            // AND
            // d.id = '$id_product_wip' 
    }

    public function panel($cari, $marker)
    {
        return $this->db->query("SELECT 
                a.id,
                a.bagian,
                a.i_panel,
                e.e_color_name 
            FROM 
                tm_panel_item a
            INNER JOIN tm_panel b ON 
                (a.id_product_wip = b.id_product_wip)
            INNER JOIN tr_product_wip d ON
                (b.id_product_wip = d.id AND b.id_company = d.id_company)
            LEFT JOIN tr_color e ON
                (d.i_color = e.i_color AND b.id_company = e.id_company)
            WHERE a.f_status = 't'
                AND (a.bagian ILIKE '%$cari%' 
                OR a.i_panel ILIKE '%$cari%'
                OR e.e_color_name ILIKE '%$cari%')
                AND b.id_company = '$this->id_company'
                AND (a.f_print = 't'
                OR a.f_bordir = 't')
                AND a.id_marker = '$marker'
        ", FALSE);
    }

    public function supplier($cari, $idcompany)
    {
        return $this->db->query(
            "
                              SELECT
                                 i_supplier,
                                 e_supplier_name,
                                 f_pkp,
                                 i_type_pajak
                              FROM
                                 tr_supplier 
                              WHERE
                                 id_company = '$idcompany'
                              AND i_supplier_group = 'KTG02'
                              AND f_status = 't'
                              AND
                                 (
                                    i_supplier ilike '%$cari%' 
                                    or e_supplier_name ilike '%$cari%'
                                 )
                              ORDER BY
                                 e_supplier_name
                              ",
            false
        );
    }

    public function getkelompokbarang($isupplier, $idcompany)
    {
        return $this->db->query(
            "
                            SELECT 
                              a.i_kode_kelompok,
                              c.e_nama_kelompok
                            FROM
                              tr_supplier_kelompokbarang a
                              LEFT JOIN tr_supplier b
                              ON (a.i_supplier = b.i_supplier and a.id_company = b.id_company)
                              LEFT JOIN tr_kelompok_barang c
                              ON (a.i_kode_kelompok = c.i_kode_kelompok and a.id_company = c.id_company)
                            WHERE 
                              a.i_supplier = '$isupplier'
                            AND
                              a.id_company = '$idcompany'
                            ",
            false
        );
    }

    public function getjenisbarang($ikodekelompok, $idcompany)
    {
        $this->db->select("i_type_code, e_type_name");
        $this->db->from("tr_item_type");
        $this->db->where("id_company", $idcompany);
        if ($ikodekelompok != "AKB") {
            $this->db->where("i_kode_kelompok", $ikodekelompok);
        }
        $this->db->order_by("i_type_code");
        return $this->db->get();
    }

    public function getmaterial(
        $cari,
        $isupplier,
        $isubkategori,
        $ikategori,
        $idcompany
    ) {
        $where = "";
        if ($isupplier != '' || $isupplier != NULL) {
            // $where .= " AND a.i_supplier = '$isupplier'";
        }
        if (($isubkategori != '' || $isubkategori != NULL) && $isubkategori != "all") {
            $where .= " AND a.i_type_code = '$isubkategori'";
        }

        if (($ikategori != '' || $ikategori != NULL) && $ikategori != "all") {
            $where .= " AND a.i_kode_kelompok = '$ikategori'";
        }

        return $this->db->query(
            "
                              SELECT
                                a.i_material,
                                a.e_material_name,
                                b.i_satuan_code,
                                b.e_satuan_name
                              FROM
                                tr_material a
                                LEFT JOIN tr_satuan b
                                ON (a.i_satuan_code = b.i_satuan_code and a.id_company = b.id_company)
                                LEFT JOIN tr_item_type c
                                ON (a.i_type_code = c.i_type_code and a.id_company = c.id_company)
                                LEFT JOIN tr_kelompok_barang d
                                ON (a.i_kode_kelompok = d.i_kode_kelompok and a.id_company = d.id_company)
                              WHERE 
                                a.id_company = '$idcompany'
                                AND a.f_status = 't'
                                AND (a.i_material ILIKE '%$cari%' OR
                                     a.e_material_name ILIKE '%$cari%')
                                $where  
                              ORDER BY
                                a.i_material, a.e_material_name
                            ",
            false
        );
    }

    public function getnamasupplier($isupplier, $idcompany)
    {
        return $this->db->query(
            "
                              SELECT
                                a.i_supplier,
                                a.e_supplier_name,
                                a.f_pkp,
                                a.i_type_pajak,
                                b.e_type_pajak_name
                              FROM
                                tr_supplier a
                                INNER JOIN 
                              	tr_type_pajak b 
                              	ON (a.i_type_pajak = b.i_type_pajak)
                              WHERE
                                i_supplier = '$isupplier' 
                                AND id_company = '$idcompany'",
            false
        );
    }

    public function getinput(
        $ikodekelompok,
        $ikodejenis,
        $isupplier,
        $imaterial,
        $idcompany
    ) {
        $where = "";
        if ($ikodejenis != "AJB") {
            $where .= " AND c.i_type_code = '$ikodejenis'";
        }

        if ($ikodekelompok != "AKB") {
            $where .= " AND d.i_kode_kelompok = '$ikodekelompok'";
        }
        if ($imaterial != "BRG") {
            $where .= " AND a.i_material = '$imaterial'";
        }

        if ($ikodejenis == "AJB") {
            $q = $this->db
                ->query(
                    "SELECT * FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany'",
                    false
                )
                ->row();
        }

        return $this->db->query(
            "                            
                        SELECT
                          a.i_material,
                          a.e_material_name,
                          a.i_satuan_code,
                          b.e_satuan_name,
                          a.i_kode_kelompok,
                          d.e_nama_kelompok,
                          a.i_type_code,
                          c.e_type_name
                        FROM
                          tr_material a
                          LEFT JOIN tr_satuan b
                          ON (a.i_satuan_code = b.i_satuan_code and a.id_company = b.id_company)
                          LEFT JOIN tr_item_type c
                          ON (a.i_type_code = c.i_type_code and a.id_company = c.id_company)
                          LEFT JOIN tr_kelompok_barang d
                          ON (a.i_kode_kelompok = d.i_kode_kelompok and a.id_company = d.id_company)
                        WHERE 
                          a.id_company = '$idcompany'
                        AND
                          a.i_material || '$isupplier' 
                          NOT IN (
                                  SELECT i_material || i_supplier FROM tr_supplier_materialprice WHERE id_company = '$idcompany' and f_status = 't')
                          AND a.f_status = 't'
                          $where  
                        ORDER BY a.i_material ASC
                        ",
            false
        );
    }

    public function getinputnew(
        $isupplier,
        $ikodekelompok,
        $ikodejenis
    ) {
        $where = "";
        if ($ikodejenis !=  '') {
            $where .= " AND a.i_type_code = '$ikodejenis'";
        }

        if ($ikodekelompok != '') {
            $where .= " AND a.i_kode_kelompok = '$ikodekelompok'";
        }

        return $this->db->query(
            "                            
                        SELECT
                          a.i_material,
                          a.e_material_name,
                          a.i_satuan_code,
                          b.e_satuan_name,
                          a.i_kode_kelompok,
                          d.e_nama_kelompok,
                          a.i_type_code,
                          c.e_type_name
                        FROM
                          tr_material a
                          LEFT JOIN tr_satuan b
                          ON (a.i_satuan_code = b.i_satuan_code and a.id_company = b.id_company)
                          LEFT JOIN tr_item_type c
                          ON (a.i_type_code = c.i_type_code and a.id_company = c.id_company)
                          LEFT JOIN tr_kelompok_barang d
                          ON (a.i_kode_kelompok = d.i_kode_kelompok and a.id_company = d.id_company)
                        WHERE 
                          a.id_company = '$this->id_company'
                        AND
                          a.i_material || '$isupplier' 
                          NOT IN (
                                  SELECT i_material || i_supplier FROM tr_supplier_materialprice WHERE id_company = '$this->id_company' and f_status = 't')
                          AND a.f_status = 't'
                          $where  
                        ORDER BY a.i_material ASC
                        ",
            false
        );
    }

    public function getsatuan($idcompany)
    {
        return $this->db
            ->query(
                "SELECT i_satuan_code, e_satuan_name FROM tr_satuan WHERE id_company = '$idcompany'  AND f_status='t' ORDER BY e_satuan_name",
                false
            )
            ->result();
    }

    public function getrumus($satuan_awal, $satuan_akhir, $idcompany)
    {
        return $this->db->query(
            "SELECT * FROM tr_konversi_satuan WHERE i_satuan_code = '$satuan_awal' AND i_satuan_code_konversi = '$satuan_akhir' AND id_company = '$idcompany'",
            false
        );
    }

    public function insert(
        $isupplier,
        $barang,
        $marker,
        $harga,
        $hargakonversi,
        $norder,
        $dateberlaku,
        $fppn
    ) {
        $idcompany = $this->session->userdata("id_company");
        $dentry = date("Y-m-d");

        $this->db->query("INSERT INTO tr_supplier_panelprice (i_supplier,id_panel_item,id_marker,v_price,v_harga_konversi,n_order,d_berlaku,f_ppn,id_company,d_entry, i_status) VALUES
					('$isupplier','$barang','$marker','$harga','$hargakonversi','$norder','$dateberlaku','$fppn','$idcompany','$dentry', '6');", FALSE);
    }

    function cek_data($isupplier, $ikodebrg, $id, $idcompany)
    {
        return $this->db->query(
            "
                SELECT
                    a.*,
                    to_char(a.d_berlaku, 'dd-mm-yyyy') AS d_berlaku,
                    b.e_supplier_name,
                    b.f_pkp,
                    b.i_type_pajak,
                    f.e_type_pajak_name,
                    c.i_panel, c.bagian,
                    e.e_color_name,
                    g.e_marker_name
                FROM
                    tr_supplier_panelprice a
                LEFT JOIN tr_supplier b ON
                    (a.i_supplier = b.i_supplier
                        AND a.id_company = b.id_company)
                LEFT JOIN tm_panel_item c ON
                    (a.id_panel_item = c.id and a.id_marker = c.id_marker)
                LEFT JOIN tr_product_wip d ON
                    (c.id_product_wip = d.id
                        AND a.id_company = d.id_company)
                LEFT JOIN tr_color e ON
                    (d.i_color = e.i_color
                        AND d.id_company = e.id_company
                        AND e.id_company = a.id_company)
                LEFT JOIN tr_type_pajak f ON
                    (b.i_type_pajak = f.i_type_pajak)
                LEFT JOIN tr_marker g ON
                    (a.id_marker = g.id)
                WHERE 
                a.id_company = '$idcompany'
                AND a.i_supplier = '$isupplier' 
                AND a.id_panel_item = '$ikodebrg'
                AND a.id = '$id'
                ORDER BY 
                a.i_supplier,
                a.id_panel_item
                              ",
            false
        );
    }

    public function update(
        $id,
        $isupplier,
        $id_panel_item,
        $marker,
        $harga,
        $hargakonversi,
        $norder,
        $dateberlaku,
        $fppn,
        $idcompany
    ) {
        $data = [
            "i_supplier" => $isupplier,
            "id_panel_item" => $id_panel_item,
            "id_marker" => $marker,
            "v_price" => $harga,
            "v_harga_konversi" => $harga,
            "n_order" => $norder,
            "d_berlaku" => $dateberlaku,
            "f_ppn" => $fppn,
            "i_status" => '6',
            "d_update" => current_datetime(),
        ];
        $this->db->where("id_panel_item", $id_panel_item);
        $this->db->where("i_supplier", $isupplier);
        $this->db->where("id", $id);
        $this->db->where("id_company", $idcompany);
        $this->db->update("tr_supplier_panelprice", $data);
    }

    public function updatetglakhir(
        $id,
        $isupplier,
        $id_panel_item,
        $marker,
        $harga,
        $hargakonversi,
        $norder,
        $fppn,
        $dateberlaku,
        $dateberlakusebelum,
        $idcompany
    ) {
        $dakhir = date("Y-m-d", strtotime("-1 days", strtotime($dateberlaku))); //kurang tanggal sebanyak 1 hari

        $data = [
            "i_supplier" => $isupplier,
            "id_panel_item" => $id_panel_item,
            "id_marker" => $id_marker,
            "v_price" => $harga,
            "v_harga_konversi" => $harga,
            "n_order" => $norder,
            "d_berlaku" => $dateberlaku,
            "f_ppn" => $fppn,
            "i_status" => '6',
            "id_company" => $idcompany,
            "d_entry" => current_datetime(),
        ];
        $this->db->insert("tr_supplier_panelprice", $data);

        $data2 = [
            "d_akhir" => $dakhir,
            "d_update" => current_datetime(),
        ];
        $this->db->where("id_panel_item", $id_panel_item);
        $this->db->where("i_supplier", $isupplier);
        $this->db->where("d_berlaku", $dateberlakusebelum);
        $this->db->where("id", $id);
        $this->db->where("id_company", $idcompany);
        $this->db->update("tr_supplier_panelprice", $data2);
    }

	public function changestatus($id,$istatus)
    {
    	$now = date('Y-m-d');
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				from tr_supplier_materialprice a
				inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
				where a.id = '$id'
				group by 1,2", FALSE)->row();
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
            	$this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
            		$data = array(
	                    'i_status'  => $istatus,
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
            	$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tr_supplier_materialprice');", FALSE);
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tr_supplier_materialprice', $data);
    }
    
    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function getkategori($kode,$cari)
    {
        return $this->db->query("SELECT i_kode_kelompok,e_nama_kelompok FROM tr_kelompok_barang WHERE f_status = 't' AND id_company = '$this->id_company' AND i_kode_group_barang IN ('GRB0001','GRB0004','GRB0005') and (e_nama_kelompok ilike '%$cari%')
        ", false);
    }

    public function getsubkategori($kode,$cari)
    {
        return $this->db->query("SELECT i_type_code,e_type_name FROM tr_item_type WHERE f_status = 't' AND id_company = '$this->id_company' AND i_kode_kelompok = '$kode' and (e_type_name ilike '%$cari%')
        ", false);
    }

    /* referensi cutting schedule */

    public function getformat()
    {
        return $this->db->query("SELECT i_document FROM tm_schedule_new ORDER BY i_document DESC LIMIT 1");
    }

    public function bagian() {
        return $this->db->query("
				SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function getperiode(){
        return $this->db->query("SELECT i_periode FROM tm_fccutting WHERE i_status = '6' ");
    }

    public function getdataitem($periode)
        {
            return $this->db->query("SELECT DISTINCT
            a.id_company,
            a.id_product_wip, 
            c.i_product_wip, 
            c.e_product_wipname,
            d.e_color_name,
            CASE 
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Print, Bordir, Quilting'
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Print, Bordir'
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Bordir'
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN ''
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Print, Quilting'
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Quilting'
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Print'
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Bordir, Quilting'
            END progress,
            COALESCE(sum(a.n_fc_cutting), 0) AS n_fc_cutting,
            COALESCE(sum(a.n_fc_perhitungan), 0) AS n_fc_perhitungan,
            COALESCE(sum(a.n_kondisi_stock), 0) AS n_kondisi_stock
            FROM tm_fccutting_item_new a
            INNER JOIN tm_fccutting b ON
            (a.id_forecast = b.id AND b.i_periode = '$periode')
            LEFT JOIN tr_product_wip c
            ON (c.id = a.id_product_wip)
            LEFT JOIN tr_color d
            ON (d.i_color = c.i_color AND d.id_company = '$this->id_company')
            INNER JOIN tr_product_wip_item e
            ON (e.id_product_wip = a.id_product_wip)
            GROUP BY a.id_company ,a.id_product_wip,c.i_product_wip ,c.e_product_wipname, d.e_color_name");
        }
}
/* End of file Mmaster.php */
