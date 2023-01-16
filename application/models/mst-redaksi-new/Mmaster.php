<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($folder, $i_menu, $dfrom, $dto)
    {
        $idcompany = $this->session->userdata('id_company');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 as no,
                a.id,
                a.id_product_wip,
                CONCAT(c.i_product_wip,' - ',c.e_product_wipname) as i_product_wip,
                b.e_color_name,
                a.e_remark,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' as i_menu,
                '$folder' AS folder
            FROM
                tm_panel a 
                INNER JOIN tr_product_wip c ON
                (c.id = a.id_product_wip AND c.id_company = $idcompany)
                INNER JOIN tr_color b ON
                (b.i_color = c.i_color AND b.id_company = $idcompany)
        ", false);

        $datatables->add('action', function ($data) {
            $id         = trim($data['id_product_wip']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';

            if (check_role($i_menu, 2)) {
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }

            if (check_role($i_menu, 3)) {
                $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
            }

            if (check_role($i_menu, 5)) {
                $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer text-warning'></i></a>&nbsp;&nbsp;&nbsp;";
            }

            // if (check_role($i_menu, 4)) {
            //     $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            // }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_product_wip');
        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('b.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
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
        return $this->db->query(" 
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
                                  b.e_bagian_name");
    }

    public function jeniskeluar()
    {
        return $this->db->get("tr_jenis_barang_keluar");
    }

    public function doc($imenu)
    {
        $this->db->select('doc_qe');
        $this->db->from('public.tm_menu');
        $this->db->where('i_menu', $imenu);
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian, $itujuan)
    {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
        SELECT 
            a.i_bagian,
            b.e_no_doc as kode
        FROM
            tr_tujuan_menu a
        INNER JOIN
            tr_kategori_jahit b 
            ON (b.id = a.id_kategori)
        WHERE
            id_company = '$id_company'
            AND a.i_menu = '$this->i_menu'
            AND a.i_bagian = '$itujuan'
        ");

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SJ';
        }
        $count = strlen($kode);
        $start = $count + 2;
        $sub = $start + 7;
        $query  = $this->db->query("
            SELECT
                max(substring(i_keluar_pengadaan, $sub, 6)) AS max
            FROM
                tm_keluar_pengadaan
            WHERE to_char (d_keluar_pengadaan, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND i_tujuan = '$itujuan'
            AND id_company = '$id_company'
            AND substring(i_keluar_pengadaan, 1, $count) = '$kode'
            AND substring(i_keluar_pengadaan, $start, 2) = substring('$thbl', 1, 2)
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

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_keluar_pengadaan');
        $this->db->from('tm_keluar_pengadaan');
        $this->db->where('i_keluar_pengadaan', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select('i_keluar_pengadaan');
        $this->db->from('tm_keluar_pengadaan');
        $this->db->where('i_keluar_pengadaan', $kode);
        $this->db->where('i_keluar_pengadaan <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI BARANG  ----------*/

    public function product($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("            
        SELECT 
            a.id,
            a.i_product_wip,
            UPPER(a.e_product_wipname) AS e_product_wipname,
            b.id as id_color,
            a.i_color,
            b.e_color_name                
        FROM
            tr_product_wip a
        INNER JOIN tr_color b ON
            (a.i_color = b.i_color
            AND a.id_company = b.id_company)
        WHERE (a.id) NOT IN (SELECT id_product_wip FROM tm_panel)
            AND a.id_company = '$idcompany'
            AND a.f_status = 't'
            AND b.f_status = 't'
            AND (a.i_product_wip ILIKE '%$cari%'
            OR a.e_product_wipname ILIKE '%$cari%'
            OR b.e_color_name ILIKE '%$cari%')
        ORDER BY
            a.i_product_wip ASC
                                ", FALSE);
    }

    public function material($cari)
    {
        return $this->db->query("            
            SELECT 
                id,
                i_material,
                e_material_name
            FROM tr_material
            WHERE
                id_company = $this->id_company
                AND (i_material ILIKE '%$cari%' OR e_material_name ILIKE '%$cari%')
                                ", FALSE);
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id, $color)
    {
        return $this->db->query("SELECT DISTINCT
                a.id AS id_product_wip,
                a.i_product_wip,
                UPPER(a.e_product_wipname) AS e_product_wipname,
                d.id AS id_color,
                d.e_color_name,
                b.id_material,
                c.i_material,
                c.e_material_name
            FROM
                tr_product_wip a
            INNER JOIN tr_product_wip_item b ON 
                (b.id_product_wip = a.id)
            INNER JOIN tr_material c ON 
                (c.id = b.id_material)
            INNER JOIN tr_color d ON
                (a.i_color = d.i_color
                AND a.id_company = d.id_company)
            WHERE
                a.f_status = 't'
                AND a.id = '$id'
                AND d.id = '$color'
                AND a.id_company = '" . $this->session->userdata('id_company') . "'
            ORDER BY
                a.i_product_wip
        ", FALSE);
    }

    public function getstok($idproduct, $ibagian)
    {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("            
            SELECT DISTINCT 
                a.id,
                a.i_product_wip,
                CASE
                 WHEN c.saldo_akhir IS NULL THEN 0
                 WHEN c.saldo_akhir < 0 THEN 0 ELSE c.saldo_akhir
                END AS saldo_akhir
            FROM
                tr_product_wip a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color
                AND a.id_company = b.id_company)
                LEFT JOIN (SELECT * FROM produksi.f_mutasi_saldoawal_pengadaan_baru($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                (c.id_product_wip = a.id AND c.id_company = '$idcompany')
            WHERE
                a.id = '$idproduct'
                AND a.id_company = '$idcompany'
                AND a.f_status = 't'
                AND b.f_status = 't'
            ORDER BY
                a.i_product_wip ASC
                                ", FALSE);
    }


    /*----------  SIMPAN DATA  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_panel');
        return $this->db->get()->row()->id + 1;
    }

    public function insertheader($id, $idproduct, $eremarkh)
    {
        $data = array(
            'id'                  => $id,
            'id_product_wip'      => $idproduct,
            'e_remark'            => $eremarkh,
            'd_entry'             => current_datetime(),
            'id_company'          => $this->session->userdata('id_company')
        );
        $this->db->insert('tm_panel', $data);
    }

    public function insertdetail($idproduct, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $print, $bordir)
    {
        $data = array(
            'id_product_wip' => $idproduct,
            'id_material'    => $imaterial,
            'i_panel'        => $ipanel,
            'bagian'         => $ebagian,
            'e_remark'       => $edesc,
            'n_qty_penyusun' => $n_qty_penyusun,
            'n_panjang_cm'   => $n_panjang_cm,
            'n_lebar_cm'     => $n_lebar_cm,
            'f_print'        => $print,
            'f_bordir'       => $bordir
        );
        $this->db->insert('tm_panel_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
        SELECT 
            a.id,
            a.id_product_wip,
            b.i_product_wip,
            b.e_product_wipname,
            c.i_color,
            c.e_color_name,
            a.e_remark
        FROM tm_panel a
        JOIN tr_product_wip b ON 
        (b.id = a.id_product_wip AND b.id_company = 4)
        JOIN tr_color c ON 
        (c.i_color = b.i_color AND c.id_company = $idcompany)
        WHERE
            a.id_product_wip = $id
                                ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
        SELECT 
            a.*,
            c.i_material,
            c.e_material_name,
            d.i_product_wip,
            d.i_color 
        FROM tm_panel_item a
        INNER JOIN tm_panel b ON (b.id_product_wip = a.id_product_wip)
        INNER JOIN tr_material c ON (c.id = a.id_material)
        INNER JOIN tr_product_wip d ON (d.id = a.id_product_wip)
        WHERE
            a.id_product_wip = $id and a.f_status = true
                              ", FALSE);
    }


    public function updateheader($id, $idproduct, $eremarkh)
    {
        $data = array(
            'id_product_wip'       => $idproduct,
            'e_remark'            => $eremarkh,
            'd_update'           => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_panel', $data);
    }

    public function updatedetail($id, $idproduct, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $status, $print, $bordir)
    {
        $data = array(
            'id'             => $id,
            'id_product_wip' => $idproduct,
            'id_material'    => $imaterial,
            'i_panel'        => $ipanel,
            'bagian'         => $ebagian,
            'e_remark'       => $edesc,
            'n_qty_penyusun' => $n_qty_penyusun,
            'n_panjang_cm'   => $n_panjang_cm,
            'n_lebar_cm'     => $n_lebar_cm,
            'f_status'       => $status,
            'f_print'        => $print,
            'f_bordir'       => $bordir
        );
        $this->db->where('id',$id);
        $this->db->update('tm_panel_item', $data);
    }

    public function deletedetail($id)
    {
        $this->db->query("DELETE FROM tm_panel_item WHERE id_product_wip ='$id'", false);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_keluar_pengadaan a
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_pengadaan');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_pengadaan', $data);
    }

    public function changestatus_20211213($id, $istatus)
    {
        if ($istatus == '6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_pengadaan', $data);
    }
}
/* End of file Mmaster.php */