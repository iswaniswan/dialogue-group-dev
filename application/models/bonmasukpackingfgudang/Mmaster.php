<?php
defined("BASEPATH") or exit("No direct script access allowed");
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($i_menu, $i_menu1, $folder, $folder1, $dfrom, $dto)
    {
        $id_company = $this->idcompany;
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_masuk_packing_fgudang a
            WHERE
                i_status <> '5'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$id_company')",false);
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
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$id_company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter());
        $datatables->query("SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian_pengirim,
                a.i_bagian,
                b.e_bagian_name,
                c.i_keluar_jahit as i_referensi,
                a.i_status,
                a.e_remark,
                d.e_status_name,
                d.label_color,
                f.i_level,
                l.e_level_name,
                '$i_menu1' AS i_menu,
                '$folder1' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_masuk_qc a
            INNER JOIN tr_bagian b
                ON (a.i_bagian_pengirim = b.i_bagian AND a.id_company = b.id_company)
            LEFT JOIN tm_keluar_jahit c
                ON (a.id_reff = c.id AND a.id_company = c.id_company)
            INNER JOIN tr_status_document d
                ON (a.i_status = d.i_status)                    
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
            AND 
                a.id_company = '$id_company'
            $where
            $bagian
            UNION ALL
            SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_bagian_pengirim,
                a.i_bagian,
                d.e_bagian_name,
                b.i_document as i_referensi,
                a.i_status,
                a.e_remark, 
                c.e_status_name,
                c.label_color, 
                f.i_level,
                l.e_level_name,
                '$i_menu' as i_menu,
                '$folder' as folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_masuk_packing_fgudang a   
            JOIN
                tm_keluar_produksibp b
                ON b.id = a.id_reff AND a.id_company = b.id_company                                    
            JOIN
                tr_status_document c 
                ON (c.i_status = a.i_status) 
            JOIN
                tr_bagian d 
                ON (a.i_bagian_pengirim = d.i_bagian AND a.id_company = d.id_company)                     
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)

            WHERE
                a.id_company= '$id_company' 
                AND a.i_status <> '5'
                $where
                $bagian
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
            $ibagian = $data['i_bagian'];
            $i_menu = $data["i_menu"];
            $folder = $data["folder"];
            $i_status = $data["i_status"];
            $dfrom = $data["dfrom"];
            $dto = $data["dto"];
            $i_level       = $data['i_level'];
            $data = "";

            /* if (check_role($i_menu, 2)) { */
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            /* } */

            if (check_role($i_menu, 3)) {
                if (
                    $i_status == "1" ||
                    $i_status == "2" ||
                    $i_status == "3" ||
                    $i_status == "7"
                ) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 4) && $i_status == "1") {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3'></i></a>";
            }
            return $data;
        });
        $datatables->hide("id");
        $datatables->hide("i_menu");
        $datatables->hide("folder");
        $datatables->hide("dfrom");
        $datatables->hide("dto");
        $datatables->hide("label_color");
        $datatables->hide("i_status");
        $datatables->hide("i_bagian");
        $datatables->hide("i_bagian_pengirim");
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');

        return $datatables->generate();
    }

    public function bagianpembuat()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query(
            "SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ",
            false
        );
    }

    public function bagianpengirim($cari, $i_menu)
    {
        $cari = str_replace("'", "", $cari);

        // $sql = "SELECT DISTINCT
        //             a.i_bagian,
        //             b.e_bagian_name
        //         FROM
        //             tr_tujuan_menu a
        //             LEFT JOIN tr_bagian b ON (a.i_bagian = b.i_bagian)
        //         WHERE
        //             b.id_company = '$this->idcompany'
        //             AND a.i_bagian ILIKE '%$cari%'
        //             AND b.e_bagian_name ILIKE '%$cari%'
        //         ORDER BY
        //             b.e_bagian_name";

        $sql = "SELECT DISTINCT
                    b.id,
                    a.i_bagian,
                    b.e_bagian_name,
                    c.name
                FROM tr_tujuan_menu a
                LEFT JOIN tr_bagian b ON (a.i_bagian = b.i_bagian)
                LEFT JOIN public.company c ON c.id = b.id_company 
                WHERE a.i_bagian ILIKE '%$cari%' OR b.e_bagian_name ILIKE '%$cari%' OR c.name ILIKE '%$cari%'
                ORDER BY 4 ASC, 3 ASC";

        // var_dump($sql); die();

        return $this->db->query($sql ,false);
    }

    public function referensi($cari, $iasal, $ibagian)
    {
        $id_company = $this->id_company;

        $id_bagian = $this->db->query("SELECT id FROM tr_bagian WHERE id_company = '$id_company' AND i_bagian = '$ibagian'")->row()->id;
        $cari = str_replace("'", "", $cari);

        $sql = "SELECT DISTINCT a.id,
                        a.i_document,
                        to_char(a.d_document, 'dd-mm-yyyy') AS d_document
                    FROM tm_keluar_produksibp a
                    LEFT JOIN tm_keluar_produksibp_item b on (
                                            a.id = b.id_document AND a.id_company = b.id_company
                                        )
                    WHERE a.i_bagian = '$iasal'
                        AND a.id_partner = '$id_bagian'
                        AND a.i_status = '6'
                        AND a.id_company = '$this->idcompany'
                        AND b.n_quantity_sisa <> 0
                        AND a.i_document ILIKE '%$cari%'
                    ORDER BY i_document ASC, d_document ASC";

        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select("i_document");
        $this->db->from("tm_masuk_packing_fgudang");
        $this->db->where("i_document", $kode);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select("i_document");
        $this->db->from("tm_masuk_packing_fgudang");
        $this->db->where("i_document", $kode);
        $this->db->where("i_document <>", $kodeold);
        $this->db->where("i_bagian", $ibagian);
        $this->db->where("id_company", $this->session->userdata("id_company"));
        $this->db->where_not_in("i_status", "5");
        return $this->db->get();
    }

    public function getdataheader($idreff, $ipengirim)
    {
        return $this->db->query("SELECT
                to_char(d_document, 'dd-mm-yyyy') as d_document
            FROM 
                tm_keluar_produksibp
            WHERE
                id = '$idreff'
                AND i_bagian = '$ipengirim'
                AND id_company = '$this->idcompany'",
            false
        );
    }

    public function getdataitem($idreff, $ipengirim)
    {
        return $this->db->query("SELECT DISTINCT 
                    a.id,
                    a.id_product as id_material,
                    d.i_material,
                    d.e_material_name,
                    a.n_quantity as n_quantity,
                    a.n_quantity_sisa as n_quantity_sisa,
                    a.e_remark
                FROM
                    tm_keluar_produksibp_item a
                    LEFT JOIN tm_keluar_produksibp b
                    ON (a.id_document = b.id AND a.id_company = b.id_company)
                    INNER JOIN tr_material d
                    ON (a.id_product = d.id AND a.id_company = d.id_company)
                WHERE
                    b.id = '$idreff' 
                    AND a.id_document = '$idreff'
                    AND b.id_company = '$this->idcompany'
                    AND b.i_bagian = '$ipengirim'
                    AND a.n_quantity_sisa <> 0",
            false
        );
    }

    public function runningid()
    {
        $this->db->select("max(id) AS id");
        $this->db->from("tm_masuk_packing_fgudang");
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_packing_fgudang
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = "BBM";
        }
        $query = $this->db->query("SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
            tm_masuk_packing_fgudang
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
            AND id_company = '$this->id_company'",
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
        $datedocument,
        $ibagian,
        $ipengirim,
        $ireff,
        $eremark
    ) {
        $data = [
            "id" => $id,
            "id_company" => $this->idcompany,
            "i_document" => $idocument,
            "d_document" => $datedocument,
            "i_bagian" => $ibagian,
            "i_bagian_pengirim" => $ipengirim,
            "id_reff" => $ireff,
            "e_remark" => $eremark,
            "d_entry" => current_datetime(),
        ];
        $this->db->insert("tm_masuk_packing_fgudang", $data);
    }

    function insertdetail($id, $imaterial, $nquantity, $edesc, $ireff)
    {
        $data = [
            "id_company" => $this->idcompany,
            "id_document" => $id,
            "id_reff" => $ireff,
            "id_material" => $imaterial,
            "n_quantity" => $nquantity,
            "n_sisa" => $nquantity,
            "e_remark" => $edesc,
        ];
        $this->db->insert("tm_masuk_packing_fgudang_item", $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_packing_fgudang a
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
                    $query = $this->db->query("SELECT 
                            a.id_reff, 
                            a.id_material, 
                            a.n_quantity,
                            a.n_sisa
                        FROM 
                            tm_masuk_packing_fgudang_item a
                        LEFT JOIN tm_masuk_packing_fgudang b
                            ON (a.id_document = b.id AND a.id_company = b.id_company)
                        WHERE 
                        a.id_document = '$id' ",
                        false
                    );
                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $key) {
                            $nsisa = $this->db->query("SELECT
                                    n_quantity_sisa
                                FROM
                                    tm_keluar_produksibp_item                       
                                WHERE
                                    id_document = '$key->id_reff'
                                    AND id_product = '$key->id_material'
                                    AND id_company = '$this->id_company'
                                    AND n_quantity_sisa >= '$key->n_quantity'",
                                false
                            );

                            if ($nsisa->num_rows() > 0) {
                                $this->db->query("UPDATE
                                                tm_keluar_produksibp_item
                                                SET n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                                                WHERE id_document = '$key->id_reff'
                                                    AND id_product = '$key->id_material'
                                                    AND id_company = '$this->id_company'",
                                    false
                                );
                            } else {
                                die();
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_packing_fgudang');", FALSE);
                }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_packing_fgudang', $data);
    }

    /* public function changestatus($id, $istatus)
    {
        $dreceive = "";
        $dreceive = date("Y-m-d");
        $iapprove = $this->session->userdata("username");
        if ($istatus == "6") {
            $query = $this->db->query("SELECT 
                    a.id_reff, 
                    a.id_material, 
                    a.n_quantity,
                    a.n_sisa
                FROM 
                    tm_masuk_packing_fgudang_item a
                LEFT JOIN tm_masuk_packing_fgudang b
                    ON (a.id_document = b.id AND a.id_company = b.id_company)
                WHERE 
                a.id_document = '$id' ",
                false
            );
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query("SELECT
                            n_quantity_sisa
                        FROM
                            tm_keluar_produksibp_item                       
                        WHERE
                            id_document = '$key->id_reff'
                            AND id_product = '$key->id_material'
                            AND id_company = '$this->id_company'
                            AND n_quantity_sisa >= '$key->n_quantity'",
                        false
                    );

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("UPDATE
                                          tm_keluar_produksibp_item
                                        SET n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                                        WHERE id_document = '$key->id_reff'
                                            AND id_product = '$key->id_material'
                                            AND id_company = '$this->id_company'",
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
        $this->db->update("tm_masuk_packing_fgudang", $data);
    } */

    public function estatus($istatus)
    {
        $this->db->select("e_status_name");
        $this->db->from("tr_status_document");
        $this->db->where("i_status", $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function cek_data($id)
    {
        return $this->db->query("SELECT 
                a.id,
                a.i_document, 
                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                a.id_reff,
                d.i_document as i_reff,
                to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
                a.i_bagian,
                b.e_bagian_name,
                a.i_bagian_pengirim,
                c.e_bagian_name as e_bagian_pengirim,
                a.e_remark,
                a.i_status
            FROM
                tm_masuk_packing_fgudang a
                INNER JOIN tm_keluar_produksibp d
                ON (a.id_reff = d.id AND a.id_company = d.id_company)
                INNER JOIN tr_bagian b
                ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                INNER JOIN tr_bagian c
                ON (a.i_bagian_pengirim = c.i_bagian AND a.id_company = b.id_company)
            WHERE 
                a.id  = '$id'
                AND a.id_company = '$this->idcompany'",
            false
        );
    }

    public function cek_datadetail($id)
    {
        return $this->db->query("SELECT
                a.id, 
                a.id_document,
                a.id_material,
                d.i_material,
                d.e_material_name,
                a.n_quantity as n_quantity_masuk,
                f.n_quantity as n_quantity_keluar,
                f.n_quantity_sisa as n_quantity_sisa,
                a.e_remark
            FROM
                tm_masuk_packing_fgudang_item a 
            INNER JOIN 
                tm_masuk_packing_fgudang b
                ON (a.id_document = b.id AND a.id_company = b.id_company)
            INNER JOIN 
                tm_keluar_produksibp_item f
                ON (a.id_reff = f.id_document AND a.id_company = f.id_company)
            INNER JOIN 
                tr_material d
                ON (a.id_material = d.id AND a.id_company = d.id_company AND f.id_product = d.id AND f.id_company = d.id_company)
            WHERE 
                a.id_document = '$id'
                AND b.id = '$id'
                AND b.id_company = '$this->idcompany'",
            false
        );
    }

    public function updateheader(
        $id,
        $idocument,
        $datedocument,
        $ibagian,
        $ipengirim,
        $ireff,
        $eremark
    ) {
        $data = [
            "i_document" => $idocument,
            "d_document" => $datedocument,
            "i_bagian_pengirim" => $ipengirim,
            "id_reff" => $ireff,
            "e_remark" => $eremark,
            "d_update" => current_datetime(),
        ];

        $this->db->where("id", $id);
        $this->db->where("id_company", $this->idcompany);
        $this->db->where("i_bagian", $ibagian);
        $this->db->update("tm_masuk_packing_fgudang", $data);
    }

    public function deletedetail($id)
    {
        $this->db->query(
            "DELETE FROM tm_masuk_packing_fgudang_item WHERE id_document='$id'",
            false
        );
    }

    /*public function updatedetail($id, $imaterial, $nquantity, $edesc, $ireff){
      $data = array(
                      'id_company'          => $this->idcompany,
                      'id_document'         => $id,
                      'n_quantity'          => $nquantity,
                      'n_sisa'              => $nquantity,
                      'e_remark'            => $edesc,
      );

      $this->db->where('id_document', $id);
      $this->db->where('id_material', $imaterial);
      $this->db->where('id_company', $this->idcompany);
      $this->db->update('tm_masuk_packing_fgudang_item', $data);
  }*/
}
/* End of file Mmaster.php */
