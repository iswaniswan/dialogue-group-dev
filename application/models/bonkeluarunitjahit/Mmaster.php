<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany  = $this->session->userdata('id_company');
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_keluar_jahit BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);

        $cek = $this->db->query("
                SELECT
                    i_bagian
                FROM
                    tm_keluar_jahit
                WHERE
                    i_status <> '5'
                    AND id_company = '" . $this->session->userdata('id_company') . "'
                    $where
                    AND i_bagian IN (
                        SELECT
                            i_bagian
                        FROM
                            tr_departement_cover
                        WHERE
                            i_departement = '" . $this->session->userdata('i_departement') . "'
                            AND id_company = '" . $this->session->userdata('id_company') . "'
                            AND username = '" . $this->session->userdata('username') . "')

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
                            AND id_company = '" . $this->session->userdata('id_company') . "'
                            AND username = '" . $this->session->userdata('username') . "')";
            }
        }

        $datatables->query("SELECT
                0 as no,
                a.id,
                a.i_keluar_jahit,
                to_char(a.d_keluar_jahit, 'dd-mm-yyyy') as d_keluar_jahit,
                a.i_tujuan,
                ab.e_bagian_name AS unit_jahit,
                b.e_bagian_name||' - '||bb.name as e_bagian_name,
                e_jenis_name,
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
                tm_keluar_jahit a 
            JOIN
                tr_bagian b 
                ON (a.i_tujuan = b.i_bagian AND a.id_company_bagian = b.id_company) 
            JOIN public.company bb on bb.id = b.id_company
            JOIN
                tr_bagian ab 
                ON (ab.i_bagian = a.i_bagian AND a.id_company = ab.id_company) 
            JOIN
                tr_status_document c 
                ON (a.i_status = c.i_status) 
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            LEFT JOIN tr_jenis_barang_keluar m ON
                (m.id = a.id_jenis_barang_keluar)
            WHERE 
                a.id_company = '$idcompany' AND a.i_status <> '5'
                $where
                $bagian
            ORDER BY
                a.i_keluar_jahit asc", false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id           = trim($data['id']);
            $i_status     = trim($data['i_status']);
            $itujuan      = trim($data['i_tujuan']);
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];
            $i_level      = $data['i_level'];
            $i_bagian      = $data['i_bagian'];

            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$i_bagian\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg'></i></a>";
            }

            if (check_role($i_menu, 5) && ($i_status == '6')) {
                $data .= "<a href=\"#\" title='Print STB' onclick='cetak(\"$id\",\"$dfrom\",\"$dto\",\"$i_bagian\"); return false;'><i class='ti-printer text-warning fa-lg mr-3'></i></a>";
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
        $datatables->hide('i_bagian');
        $datatables->hide('i_tujuan');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_keluar_jahit a
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_jahit');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_jahit', $data);
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
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_jahit', $data);
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
      $this->db->where('username', $this->session->userdata('username'));
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

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_keluar_jahit');
        $this->db->from('tm_keluar_jahit');
        $this->db->where('i_keluar_jahit', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian, $itujuan)
    {
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $query = $this->db->query("SELECT b.e_no_doc_retur, b.e_no_doc FROM tr_bagian a 
            INNER JOIN tr_kategori_jahit b ON (b.id = a.id_kategori_jahit) 
            WHERE id_company = '$this->id_company' AND a.i_bagian = '$ibagian'");
        if ($query->num_rows() > 0) {
            $kode = $query->row()->e_no_doc;
        } else {
            $kode = 'STB';
        }
        // var_dump($kode);
        if (strlen($kode) == 4) {
            $sql  = $this->db->query("SELECT max(substring(i_keluar_jahit, 11, 4)) AS max 
                FROM tm_keluar_jahit
                WHERE to_char (d_keluar_jahit, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_keluar_jahit ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 3) {
            $sql  = $this->db->query("SELECT max(substring(i_keluar_jahit, 10, 4)) AS max 
                FROM tm_keluar_jahit
                WHERE to_char (d_keluar_jahit, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_keluar_jahit ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 2) {
            $sql  = $this->db->query("SELECT max(substring(i_keluar_jahit, 9, 4)) AS max 
                FROM tm_keluar_jahit
                WHERE to_char (d_keluar_jahit, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_keluar_jahit ILIKE '$kode%'
            ", false);
        }
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
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

    public function runningnumber_old($thbl, $tahun, $ibagian, $itujuan)
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
                max(substring(i_keluar_jahit, $sub, 4)) AS max
            FROM
                tm_keluar_jahit
            WHERE to_char (d_keluar_jahit, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND i_keluar_jahit ILIKE '%$kode%'
            AND substring(i_keluar_jahit, 1, $count) = '$kode'
            AND substring(i_keluar_jahit, $start, 4) = '$thbl'
            AND id_company = '" . $this->session->userdata("id_company") . "'
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

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_jahit');
        return $this->db->get()->row()->id + 1;
    }

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
                                  AND a.id_company = '$idcompany'"); */
        return $this->db->query("SELECT a.id_company, a.id, a.i_bagian, a.e_bagian_name||' - '||b.name as e_bagian_name, b.name
            FROM tr_bagian a
            INNER JOIN public.company b ON (b.id = a.id_company)
            WHERE a.f_status = 't' AND b.f_status = 't' AND i_type = '23'
            ORDER BY a.id_company, a.e_bagian_name ASC");
    }

    public function dataproduct($cari, $itujuan)
    {
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT  
                a.id,
                a.i_product_base,
                a.e_product_basename,
                a.i_color,
                b.e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color AND a.id_company = b.id_company)
            WHERE
                a.id_company = '$id_company_tujuan'
            AND a.f_status = 't'
            AND
                (a.i_product_base ILIKE '%$cari%'
                OR a.e_product_basename ILIKE '%$cari%') ", FALSE);
    }

    public function getproduct($eproduct, $itujuan)
    {
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT 
                a.id as id_product, 
                a.i_product_base,
                a.e_product_basename,
                b.id as id_color,
                a.i_color,
                b.e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color AND a.id_company = b.id_company)
            WHERE
                a.id_company = '$id_company_tujuan'
            AND 
                a.id = '$eproduct'
        ", FALSE);
    }

    public function getproduct3($idproduct, $eproduct, $itujuan)
    {
        $split_product = explode("-", $eproduct);
        $i_product = trim($split_product[0]);
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT 
                a.id as id_product, 
                a.i_product_base,
                a.e_product_basename,
                b.id as id_color,
                a.i_color,
                b.e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color AND a.id_company = b.id_company)
            WHERE
                a.id_company = '$id_company_tujuan'
            AND 
                a.i_product_base = '$i_product' and a.f_status = true
            AND
                a.id <> '$idproduct'
        ", FALSE);
    }

    public function getproduct2($id_company_tujuan, $itujuan)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT 
                a.id as id_product, 
                a.i_product_base,
                a.e_product_basename,
                b.id as id_color,
                a.i_color,
                b.e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color AND a.id_company = b.id_company)
            WHERE
                a.id_company = '$id_company_tujuan'
                AND a.f_status = 't'
        ", FALSE);
    }

    public function getstok($idproduct, $ibagian)
    {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        if ($jangkaawal == $today) {
            $jangkaawal = '9999-01-01';
            $jangkaakhir = '9999-01-01';
        }
        $periode = date('Ym');
        return $this->db->query("SELECT DISTINCT 
                a.id,
                a.i_product_base,
                CASE
                    WHEN c.saldo_akhir IS NULL THEN 0
                    WHEN c.saldo_akhir < 0 THEN 0 ELSE c.saldo_akhir
                END AS saldo_akhir,
                CASE
                    WHEN c.saldo_akhir_repair IS NULL THEN 0
                    WHEN c.saldo_akhir_repair < 0 THEN 0 ELSE c.saldo_akhir_repair
                END AS saldo_akhir_repair
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color
                AND a.id_company = b.id_company)
            LEFT JOIN (SELECT * FROM produksi.f_mutasi_unitjahit($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                (c.id_product_base = a.id)
            WHERE
                a.id = '$idproduct'
                AND a.f_status = 't'
                AND b.f_status = 't'
            ORDER BY
                a.i_product_base ASC", FALSE);
    }

    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark, $ijenisbarang)
    {
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_company'             => $idcompany,
            'id'                     => $id,
            'i_keluar_jahit'         => $ibonk,
            'd_keluar_jahit'         => $datebonk,
            'i_bagian'               => $ibagian,
            'i_tujuan'               => $itujuan,
            'id_company_bagian'      => $id_company_tujuan,
            'id_jenis_barang_keluar' => $ijenisbarang,
            'e_remark'               => $eremark,
            'd_entry'                => current_datetime(),
        );
        $this->db->insert('tm_keluar_jahit', $data);
    }

    public function insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_keluar_jahit'   => $id,
            'id_product'        => $iproduct,
            'id_color'          => $icolor,
            'n_quantity_product' => $nqtyproduct,
            'n_sisa'            => $nqtyproduct,
            'id_company'        => $idcompany,
            'e_remark'          => $edesc,
        );
        $this->db->insert('tm_keluar_jahit_item', $data);
    }

    public function cek_data($id, $idcompany)
    {
        return $this->db->query("SELECT
                a.*,
                to_char(a.d_keluar_jahit, 'dd-mm-yyyy') as d_keluar_jahit
            FROM
                tm_keluar_jahit a
            WHERE                                        
                a.id = '$id'
            AND
                a.id_company = '$idcompany' ", FALSE);
    }

    public function cek_data_print($id, $idcompany)
    {
        $sql = "SELECT a.*, 
                        a.i_keluar_jahit AS i_document,
                        to_char(a.d_keluar_jahit, 'dd-mm-yyyy') as date_document,
                    b.e_bagian_name,
                    b2.e_bagian_name AS e_bagian_receive_name,
                    c.name AS e_company_receive_name
                    FROM tm_keluar_jahit a
                    INNER JOIN tr_bagian b ON b.i_bagian = a.i_bagian AND b.id_company = a.id_company
                    INNER JOIN tr_bagian b2 ON b2.i_bagian = a.i_tujuan AND b2.id_company = a.id_company_bagian
                    INNER JOIN public.company c ON c.id = a.id_company_bagian
                    WHERE a.id = '$id'
                    AND a.id_company = '$idcompany'";

        return $this->db->query($sql, FALSE);
    }

    public function cek_datadetail($id, $idcompany, $i_bagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        if ($jangkaawal == $today) {
            $jangkaawal = '9999-01-01';
            $jangkaakhir = '9999-01-01';
        }
        $periode = date('Ym');

        return $this->db->query(
            "SELECT
                a.id_keluar_jahit,
                a.id_product,
                b.i_product_base,
                b.e_product_basename,
                a.id_color,
                c.i_color,
                c.e_color_name,
                a.n_quantity_product,
                a.e_remark ,
                coalesce(e.saldo_akhir,0) as saldo_akhir
            FROM
                tm_keluar_jahit_item a 
                inner join tm_keluar_jahit d ON a.id_keluar_jahit = d.id 
                inner join tr_product_base b ON a.id_product = b.id /* AND d.id_company = b.id_company */ 
                inner join tr_color c ON c.i_color = b.i_color AND b.id_company = c.id_company 
                left join (
                    SELECT id_product_base, id_company, saldo_akhir  
                    FROM produksi.f_mutasi_unitjahit($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$i_bagian')               
                ) e on (e.id_product_base = a.id_product AND e.id_company = a.id_company)
            WHERE
                a.id_keluar_jahit = '$id'
            ORDER BY b.i_product_base ASC
        ", FALSE);
    }

    public function updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark, $ijenisbarang)
    {
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_keluar_jahit'         => $ibonk,
            'd_keluar_jahit'         => $datebonk,
            'i_bagian'               => $ibagian,
            'i_tujuan'               => $itujuan,
            'id_company_bagian'      => $id_company_tujuan,
            'id_jenis_barang_keluar' => $ijenisbarang,
            'e_remark'               => $eremark,
            'd_update'               => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_keluar_jahit', $data);
    }

    public function deletedetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('id_keluar_jahit', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_keluar_jahit_item');
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
}
/* End of file Mmaster.php */