<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($folder, $i_menu, $dfrom, $dto)
    {
        $idcompany = $this->session->userdata('id_company');
        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_keluar_pengadaan
            WHERE
                i_status <> '5'
                and d_keluar_pengadaan between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND username = '" . $this->session->userdata('username') . "'
                        AND id_company = '$idcompany')

        ", FALSE);
        if ($this->session->userdata('i_departement') == '1') {
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
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND username = '" . $this->session->userdata('username') . "'
                        AND id_company = '$idcompany')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 as no,
                a.id,
                a.i_keluar_pengadaan,
                to_char(a.d_keluar_pengadaan, 'dd-mm-yyyy') as d_keluar_pengadaan,
                a.i_tujuan,
                (SELECT e_bagian_name FROM tr_bagian WHERE i_bagian = a.i_bagian AND id_company = a.id_company) as e_bagian_name,
                (SELECT e_bagian_name||' - '||xx.name as e_bagian_name FROM tr_bagian x, public.company xx WHERE x.id_company = xx.id AND i_bagian = a.i_tujuan AND id_company = a.id_company_bagian) as e_tujuan_name,
                to_char(a.d_receive_jahit, 'dd-mm-yyyy') as d_receive_jahit,
                m.e_jenis_name,
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
                '$folder' AS folder,
                m.id as id_jenis
            FROM
                tm_keluar_pengadaan a 
            JOIN tr_bagian b 
                ON (a.i_tujuan = b.i_bagian AND a.id_company = b.id_company) 
            JOIN tr_status_document c 
                ON (a.i_status = c.i_status) 
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            LEFT JOIN tr_jenis_barang_keluar m ON
                (m.id = a.id_jenis_barang_keluar)
            WHERE 
                a.id_company = '$idcompany' AND
                a.i_status <> '5'AND
                a.d_keluar_pengadaan BETWEEN to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy')
                $bagian
            ORDER BY
                a.i_keluar_pengadaan asc
        ", false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $i_status   = $data['i_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_level = $data['i_level'];
            $id_jenis = $data['id_jenis'];
            $data       = '';

            if (check_role($i_menu, 2)) {
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success mr-3 fa-lg'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 5)) {
                if ($i_status == '6' or $i_status == '4') {
                    // $data .= "<a href=\"#\" title='Print' target='_blank' onclick='cetak($id); return false;'><i class='ti-printer text-warning mr-3 fa-lg'></i></a>";
                    $data .= "<a href=\"".base_url($folder.'/cform/cetak/'.encrypt_url($id))."\" title='Print' target='_blank'><i class='ti-printer text-warning mr-3 fa-lg'></i></a>";
                    if($id_jenis == '1') {
                        $data .= "<a href=\"".base_url($folder.'/cform/cetak2/'.encrypt_url($id))."\" title='Print Barcode' target='_blank'><i class='ti-printer text-info mr-3 fa-lg'></i></a>";
                        $data .= "<a href=\"".base_url($folder.'/cform/cetak3/'.encrypt_url($id))."\" title='Print QR Code' target='_blank'><i class='ti-printer text-success mr-3 fa-lg'></i></a>";
                    }
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3 fa-lg'></i></a>";
            }
            return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_company');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_tujuan');
        $datatables->hide('i_bagian');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        $datatables->hide('id_jenis');
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
        /* return $this->db->query("SELECT 
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
        return $this->db->query(
            "SELECT b.name, a.id, a.i_bagian, a.id_company, a.e_bagian_name 
            FROM tr_bagian a
            INNER JOIN public.company b ON (b.id = a.id_company)
            WHERE a.f_status = 't' AND a.i_type = '10' AND b.f_status = 't' AND b.i_apps = '2'
            AND (
                    SELECT array_agg(id) FROM tr_type_makloon 
                    WHERE e_type_makloon_name ILIKE '%MAKLOON JAHIT%'
                ) && a.id_type_makloon
            ORDER BY 1,5");
    }

    public function jeniskeluar()
    {
        //return $this->db->get("tr_jenis_barang_keluar");
        return $this->db->query(" select * from tr_jenis_barang_keluar where id in (1,2)");
    }

    public function doc($imenu)
    {
        $this->db->select('doc_qe');
        $this->db->from('public.tm_menu');
        $this->db->where('i_menu', $imenu);
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian, $itujuan, $id)
    {
        $id_company = $this->session->userdata('id_company');
        $split = explode("|",$itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $cek = $this->db->query("SELECT 
            b.e_no_doc as kode
        FROM
            tr_bagian a
        INNER JOIN
            tr_kategori_jahit b 
            ON (b.id = a.id_kategori_jahit)
        WHERE
            a.i_bagian = '$itujuan'
            AND a.id_company = '$id_company_tujuan'
        ");

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SJ';
        }
        $count = strlen($kode);
        $start = $count + 2;
        $sub = $start + 7;

        if(strlen($id) > 0){
            $query  = $this->db->query(
                "SELECT
                    max(substring(i_keluar_pengadaan, $sub, 4)) AS max
                FROM
                    tm_keluar_pengadaan
                WHERE to_char (d_keluar_pengadaan, 'yyyy') = '$tahun'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id <> $id
                /* AND i_tujuan = '$itujuan' */
                AND id_company = '$id_company'
                AND substring(i_keluar_pengadaan, 1, $count) = '$kode'
                AND substring(i_keluar_pengadaan, $start, 2) = substring('$thbl', 1, 2)
            ", false);
        }else{
            $query  = $this->db->query(
                "SELECT
                    max(substring(i_keluar_pengadaan, $sub, 4)) AS max
                FROM
                    tm_keluar_pengadaan
                WHERE to_char (d_keluar_pengadaan, 'yyyy') = '$tahun'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                /* AND i_tujuan = '$itujuan' */
                AND id_company = '$id_company'
                AND substring(i_keluar_pengadaan, 1, $count) = '$kode'
                AND substring(i_keluar_pengadaan, $start, 2) = substring('$thbl', 1, 2)
            ", false);
        }
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

    public function product($cari, $ibagian)
    {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        if ($today = $jangkaawal) {
            $jangkaawal = date('9999-01-01');
            $jangkaakhir = date('9999-12-31');
        }
        $periode = date('Ym');
        return $this->db->query("            
            SELECT DISTINCT 
                a.id,
                a.i_product_wip,
                UPPER(a.e_product_wipname) AS e_product_wipname,
                b.id as id_color,
                b.e_color_name,
                c.n_saldo_akhir AS saldo_akhir
            FROM
                tr_product_wip a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color
                AND a.id_company = b.id_company)
                LEFT JOIN (SELECT * FROM produksi.f_mutasi_saldoawal_pengadaan_newbie($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                (c.id_product_wip = a.id AND c.id_company = '$idcompany')
            WHERE
                a.id_company = '$idcompany'
                AND a.f_status = 't'
                AND b.f_status = 't'
                AND (a.i_product_wip ILIKE '%$cari%'
                OR a.e_product_wipname ILIKE '%$cari%')
            ORDER BY
                a.i_product_wip ASC
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
                d.e_color_name
            FROM
                tr_product_wip a
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

        if ($jangkaawal == $today) {
            $jangkaawal = '9999-01-01';
            $jangkaakhir = '9999-01-31';
        }
        return $this->db->query("            
            SELECT DISTINCT 
                a.id,
                a.i_product_wip,
                CASE
                 WHEN c.n_saldo_akhir IS NULL THEN 0
                 WHEN c.n_saldo_akhir < 0 THEN 0 ELSE c.n_saldo_akhir
                END AS saldo_akhir
            FROM
                tr_product_wip a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color
                AND a.id_company = b.id_company)
                LEFT JOIN (SELECT * FROM produksi.f_mutasi_saldoawal_pengadaan_newbie($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                (a.id = c.id_product_wip AND c.id_company = '$idcompany')
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
        $this->db->from('tm_keluar_pengadaan');
        return $this->db->get()->row()->id + 1;
    }

    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $ijenis, $eremarkh)
    {
        $split = explode("|",$itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $data = array(
            'id'                  => $id,
            'id_company'          => $this->session->userdata('id_company'),
            'i_keluar_pengadaan'  => $ibonk,
            'd_keluar_pengadaan'  => $datebonk,
            'i_bagian'            => $ibagian,
            'i_tujuan'            => $itujuan,
            'id_company_bagian'   => $id_company_tujuan,
            'e_remark'            => $eremarkh,
            'i_status'            => '1',
            'd_entry'             => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
        );
        $this->db->insert('tm_keluar_pengadaan', $data);
    }

    public function insertdetail($id, $idproductwip, $color, $nquantitywip, $nquantitymat, $eremark, $i_periode)
    {
        $data = array(
            'id_company'                => $this->session->userdata('id_company'),
            'id_keluar_pengadaan'       => $id,
            'id_product_wip'            => $idproductwip,
            'id_color_wip'              => $color,
            'n_quantity_product_wip'    => $nquantitywip,
            'n_sisa_wip'                => $nquantitywip,
            'e_remark'                  => $eremark,
            'i_periode'                 => $i_periode
        );
        $this->db->insert('tm_keluar_pengadaan_item_new', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT
                a.*,
                to_char(a.d_keluar_pengadaan, 'dd-mm-yyyy') as d_keluar_pengadaan
            FROM
                tm_keluar_pengadaan a 
            WHERE
                a.id = '$id'
            AND 
                a.id_company = '$idcompany' 
            ORDER BY
                a.d_keluar_pengadaan asc", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query(
            "SELECT
                b.id_product_wip,
                c.i_product_wip,
                c.e_product_wipname,
                d.id,
                c.i_color,
                d.e_color_name,
                b.n_quantity_product_wip as n_quantity_wip, 
                b.e_remark,
                e.n_saldo_akhir AS saldo_akhir,
                to_char(b.i_periode, 'FMMonth YYYY') periode,
                to_char(b.i_periode, 'YYYY-MM') i_periode
            FROM
                tm_keluar_pengadaan a 
                JOIN
                tm_keluar_pengadaan_item_new b 
                ON (a.id = b.id_keluar_pengadaan) 
                JOIN
                tr_product_wip c 
                ON (b.id_product_wip = c.id AND a.id_company = c.id_company) 
                JOIN
                tr_color d 
                ON (c.i_color = d.i_color AND a.id_company = d.id_company) 
                LEFT JOIN (SELECT * FROM produksi.f_mutasi_saldoawal_pengadaan_newbie($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', 'PGD05')) e ON
                (e.id_product_wip = b.id_product_wip AND e.id_company = '$idcompany')
            WHERE
                a.id = '$id'
            AND 
                a.id_company = '$idcompany'
        ", FALSE);
    }


    public function updateheader($id, $ibonk, $datebonk, $ibagian, $itujuan, $ijenis, $eremarkh)
    {
        $split = explode("|",$itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $data = array(
            'i_keluar_pengadaan' => $ibonk,
            'd_keluar_pengadaan' => $datebonk,
            'i_bagian'           => $ibagian,
            'i_tujuan'           => $itujuan,
            'id_company_bagian'  => $id_company_tujuan,
            'e_remark'           => $eremarkh,
            'd_update'           => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_pengadaan', $data);
    }

    public function deletedetail($id)
    {
        $this->db->query("DELETE FROM tm_keluar_pengadaan_item_new WHERE id_keluar_pengadaan='$id'", false);
    }

    public function getpengadaanitembyidkeluar($id)
    {
        return $this->db->query("SELECT
            a.id_product_wip,
            c.id AS id_product_base,
            a.id_keluar_pengadaan,
            a.n_quantity_product_wip
        FROM
            tm_keluar_pengadaan_item_new a
        INNER JOIN tr_product_wip b ON
            (b.id = a.id_product_wip
                AND b.id_company = a.id_company)
        INNER JOIN tr_product_base c ON
            (c.i_product_wip = b.i_product_wip
                AND c.id_company = b.id_company
                AND c.i_color = b.i_color)
        WHERE
            a.id_keluar_pengadaan = '$id';");
    }

    public function getmaxidtrproses()
    {
        return $this->db->query("SELECT MAX(id::numeric) as id FROM tr_proses");
    }

    public function inserttrproses($data)
    {
        $this->db->insert_batch('tr_proses',$data);
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

    public function get_print($id)
    {
        return $this->db->query(
            /* "SELECT c.id, c.i_product_wip, upper(trim(c.e_product_wipname)) e_product_wipname,
                upper(trim(d.e_color_name)) e_color_name, jml, a.n_quantity_product_wip, b.i_keluar_pengadaan,
                ab.e_bagian_name, f.i_material, upper(trim(f.e_material_name)) e_material_name, upper(trim(f.bagian)) bagian, f.n_qty_penyusun, b.d_keluar_pengadaan
            FROM tm_keluar_pengadaan_item_new a
            INNER JOIN tm_keluar_pengadaan b ON (b.id = a.id_keluar_pengadaan)
            INNER JOIN tr_bagian ab ON (ab.i_bagian = b.i_tujuan AND b.id_company = ab.id_company)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
            INNER JOIN tr_color d ON (d.i_color = c.i_color AND c.id_company = d.id_company)
            LEFT JOIN (
                SELECT count(id_material) jml, id_product_wip FROM (SELECT DISTINCT id_material, id_product_wip FROM tm_panel_item) a GROUP BY id_product_wip
            ) e ON (e.id_product_wip = a.id_product_wip)
            LEFT JOIN (
                SELECT id_product_wip, b.i_material, b.e_material_name, a.bagian, a.n_qty_penyusun FROM tm_panel_item a
                INNER JOIN tr_material b ON (b.id = a.id_material)
            ) f ON (f.id_product_wip = a.id_product_wip)
            WHERE a.id_keluar_pengadaan = '$id'
            ORDER BY 2,9,11
            " */
            "SELECT *, jml FROM (
                SELECT c.id, c.i_product_wip, upper(trim(c.e_product_wipname)) e_product_wipname,
                    upper(trim(d.e_color_name)) e_color_name, a.n_quantity_product_wip, b.i_keluar_pengadaan,
                    ab.e_bagian_name, f.i_material, upper(trim(f.e_material_name)) e_material_name, upper(trim(f.bagian)) bagian, f.n_qty_penyusun, b.d_keluar_pengadaan,
                    'panel_item' AS grup, NULL AS e_satuan_name, b.id_company, a.id_keluar_pengadaan
                FROM tm_keluar_pengadaan_item_new a
                INNER JOIN tm_keluar_pengadaan b ON (b.id = a.id_keluar_pengadaan)
                INNER JOIN tr_bagian ab ON (ab.i_bagian = b.i_tujuan AND b.id_company = ab.id_company)
                INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
                INNER JOIN tr_color d ON (d.i_color = c.i_color AND c.id_company = d.id_company)
                LEFT JOIN (
                    SELECT id_product_wip, b.i_material, b.e_material_name, a.bagian, a.n_qty_penyusun FROM tm_panel_item a
                    INNER JOIN tr_material b ON (b.id = a.id_material)
                ) f ON (f.id_product_wip = a.id_product_wip)
                WHERE a.id_keluar_pengadaan = '$id'
                UNION ALL
                SELECT DISTINCT  c.id, c.i_product_wip, upper(trim(c.e_product_wipname)) e_product_wipname,
                    upper(trim(d.e_color_name)) e_color_name, a.n_quantity_product_wip, b.i_keluar_pengadaan,
                    ab.e_bagian_name, f.i_material, upper(trim(f.e_material_name)) e_material_name, upper(trim(f.e_bagian)) bagian, round(1 / f.v_set * f.v_gelar,4) AS n_qty_penyusun, b.d_keluar_pengadaan, 'polacutting' AS grup, f.e_satuan_name, b.id_company, a.id_keluar_pengadaan
                FROM tm_keluar_pengadaan_item_new a
                INNER JOIN tm_keluar_pengadaan b ON (b.id = a.id_keluar_pengadaan)
                INNER JOIN tr_bagian ab ON (ab.i_bagian = b.i_tujuan AND b.id_company = ab.id_company)
                INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
                INNER JOIN tr_color d ON (d.i_color = c.i_color AND c.id_company = d.id_company)
                LEFT JOIN (
                    SELECT id_product_wip, b.i_material, b.e_material_name, a.e_bagian, a.v_gelar, a.v_set, a.id_company, b.i_satuan_code, c.e_satuan_name FROM tr_polacutting_new a
                    INNER JOIN tr_material b ON (b.id = a.id_material)
                    INNER JOIN tr_satuan c ON (c.i_satuan_code = b.i_satuan_code AND c.id_company = b.id_company)
                    WHERE b.i_kode_group_barang = 'GRB0005'
                ) f ON (f.id_product_wip = a.id_product_wip AND f.id_company = a.id_company)
                WHERE a.id_keluar_pengadaan = '$id'
                ORDER BY 1,2,9,11
            ) AS x
            INNER JOIN (
                SELECT sum(jml) AS jml, id_product_wip FROM (
                    SELECT count(id_material) jml, id_product_wip FROM (SELECT DISTINCT id_material, id_product_wip FROM tm_panel_item) a GROUP BY id_product_wip
                    UNION ALL
                    SELECT count(id_material) jml, id_product_wip FROM (SELECT id_material, id_product_wip FROM tr_polacutting_new a INNER JOIN tr_material b ON (b.id = a.id_material) WHERE b.i_kode_group_barang = 'GRB0005') a GROUP BY id_product_wip
                ) x GROUP BY x.id_product_wip
            ) e ON (e.id_product_wip = x.id)
            WHERE x.id_keluar_pengadaan = '$id'
            ;"
        );
    }

    public function get_print_header($id)
    {
        return $this->db->query("SELECT
                a.i_keluar_pengadaan,
                d_keluar_pengadaan,
                b.e_bagian_name AS bagian,
                c.e_bagian_name AS tujuan,
                d.e_status_name,
                a.d_approve,
                e.e_jenis_name
            FROM
                tm_keluar_pengadaan a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                    AND b.id_company = a.id_company)
            INNER JOIN tr_bagian c ON
                (c.i_bagian = a.i_tujuan
                    AND c.id_company = a.id_company)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tr_jenis_barang_keluar e ON
                (e.id = a.id_jenis_barang_keluar)
            WHERE
                a.id = '$id';");
    }

    public function get_print_product($id)
    {
        return $this->db->query(
            "SELECT
                a.id_product_wip,
                c.id AS id_product_base,
                a.id_keluar_pengadaan,
                c.i_product_base,
                c.e_product_basename,
                d.e_color_name,
                a.n_quantity_product_wip
            FROM
                tm_keluar_pengadaan_item_new a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip
                    AND b.id_company = a.id_company)
            INNER JOIN tr_product_base c ON
                (c.i_product_wip = b.i_product_wip
                    AND c.id_company = b.id_company
                    AND c.i_color = b.i_color)
            INNER JOIN tr_color d ON
                (d.id = a.id_color_wip
                    AND d.id_company = a.id_company)
            WHERE
                a.id_keluar_pengadaan = '$id';
            "
        );
    }

    public function get_print_barcode($id)
    {
        return $this->db->query("SELECT
                a.id,
                a.id_product_base,
                a.id_product_wip,
                c.i_product_base,
                c.e_product_basename,
                b.d_keluar_pengadaan,
                b.i_tujuan,
                d.e_color_name
            FROM
                tr_proses a
            INNER JOIN tm_keluar_pengadaan b ON
                (b.id = a.id_keluar_pengadaan)
            INNER JOIN tr_product_base c ON
                (c.id = a.id_product_base)
            INNER JOIN tr_color d ON
                (d.i_color = c.i_color and d.id_company = c.id_company)
            WHERE
                a.id_keluar_pengadaan = '$id'
                AND b.id_jenis_barang_keluar = '1';");
    }
}
/* End of file Mmaster.php */