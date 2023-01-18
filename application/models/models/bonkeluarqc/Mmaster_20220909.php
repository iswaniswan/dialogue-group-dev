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
            $where = "AND d_keluar_qc BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);

        $cek = $this->db->query("
                                    SELECT
                                        i_bagian
                                    FROM
                                        tm_keluar_qc
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

        $datatables->query("SELECT DISTINCT
                             0 as no,
                             a.id,
                             a.i_keluar_qc,
                             to_char(a.d_keluar_qc, 'dd-mm-yyyy') as d_keluar_qc,
                             a.i_tujuan,
                             ab.e_bagian_name,
                             b.e_bagian_name e_bagian_tujuan,
                             cc.e_jenis_name,
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
                             tm_keluar_qc a 
                             JOIN
                                tr_bagian b 
                                ON (a.i_tujuan = b.i_bagian AND a.id_company = b.id_company) 
                             JOIN
                                tr_bagian ab 
                                ON (ab.i_bagian = a.i_bagian AND a.id_company = b.id_company AND ab.i_type = '23') 
                             JOIN
                                tr_status_document c 
                                ON (a.i_status = c.i_status)   
                             JOIN
                             tr_jenis_barang_keluar cc 
                                ON (cc.id = a.id_jenis_barang_keluar)                        
                            LEFT JOIN tr_menu_approve f ON
                                (a.i_approve_urutan = f.n_urut
                                AND f.i_menu = '$i_menu')
                            LEFT JOIN public.tr_level l ON
                                (f.i_level = l.i_level)
                             WHERE 
                                a.id_company = '$idcompany'
                             AND
                                a.i_status <> '5'
                          $where
                          $bagian
                          ORDER BY
                             a.i_keluar_qc asc", false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id           = trim($data['id']);
            $ibagian      = $data['i_bagian'];
            $i_status     = trim($data['i_status']);
            $itujuan      = trim($data['i_tujuan']);
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];
            $i_level      = $data['i_level'];

            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye mr-2 text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$ibagian\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box mr-2 text-primary'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
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

    public function changestatus_20211216($id, $istatus)
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
        $this->db->update('tm_keluar_qc', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_keluar_qc a
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_qc');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_qc', $data);
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
        $this->db->select('i_keluar_qc');
        $this->db->from('tm_keluar_qc');
        $this->db->where('i_keluar_qc', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function jeniskeluar()
    {
        return $this->db->get("tr_jenis_barang_keluar");
    }

    public function runningnumber($thbl, $tahun, $ibagian, $itujuan)
    {
        //var_dump($thbl);
        $id_company = $this->id_company;
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
            AND i_bagian = '$itujuan'
        ");

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'STB';
        }
        $count = strlen($kode);
        $start = $count + 2;
        $sub   = $count + 7;
        $query  = $this->db->query("
            SELECT
                max(substring(i_keluar_qc, $sub, 6)) AS max
            FROM
                tm_keluar_qc
            WHERE to_char (d_keluar_qc, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND i_tujuan = '$itujuan'
            AND id_company = '$id_company'
            AND substring(i_keluar_qc, 1, $count) = '$kode'
            AND substring(i_keluar_qc, $start, 2) = substring('$thbl', 1, 2)
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
        $this->db->from('tm_keluar_qc');
        return $this->db->get()->row()->id + 1;
    }

    public function tujuan($i_menu, $idcompany)
    {
        return $this->db->query(
            "SELECT 
                a.*,
                b.e_bagian_name 
            FROM 
                tr_tujuan_menu a
            JOIN tr_bagian b 
            ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
            WHERE
                a.i_menu = '$i_menu'
                AND a.id_company = '$idcompany'
            ORDER BY b.e_bagian_name
        ");
    }

    public function dataproduct($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("    
                                    SELECT  
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
                                        a.id_company = '$idcompany'
                                    AND
                                        (upper(a.i_product_base) LIKE '%$cari%'
                                        OR upper(a.e_product_basename) LIKE '%$cari%') 
                                ", FALSE);
    }

    public function getproduct($eproduct)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("            
                                    SELECT 
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
                                        a.id_company = '$idcompany'
                                    AND 
                                        a.id = '$eproduct'
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
                /* CASE
                 WHEN c.saldo_akhir IS NULL THEN 0 
                 WHEN c.saldo_akhir < 0 THEN 0 ELSE c.saldo_akhir
                END AS saldo_akhir, */
                CASE
                 WHEN c.n_saldo_akhir IS NULL THEN 0 
                 WHEN c.n_saldo_akhir < 0 THEN 0 ELSE c.n_saldo_akhir
                END AS saldo_akhir,
                CASE
                 WHEN c.n_saldo_akhir_repair IS NULL THEN 0 
                 WHEN c.n_saldo_akhir_repair < 0 THEN 0 ELSE c.n_saldo_akhir_repair
                END AS saldo_akhir_repair
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color
                AND a.id_company = b.id_company)
            LEFT JOIN (SELECT * FROM produksi.f_mutasi_wip($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                (c.id_product_base = a.id AND c.id_company = '$idcompany')
            WHERE
                a.id = '$idproduct'
                AND a.id_company = '$idcompany'
                AND a.f_status = 't'
                AND b.f_status = 't'
            ORDER BY
                a.id ASC
                                ", FALSE);
    }

    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $ijenis, $eremark)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_company'        => $idcompany,
            'id'                => $id,
            'i_keluar_qc'       => $ibonk,
            'd_keluar_qc'       => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'e_remark'          => $eremark,
            'd_entry'           => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
        );
        $this->db->insert('tm_keluar_qc', $data);
    }

    public function insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_keluar_qc'      => $id,
            'id_product'        => $iproduct,
            'id_color'          => $icolor,
            'n_quantity_product' => $nqtyproduct,
            'n_sisa'            => $nqtyproduct,
            'id_company'        => $idcompany,
            'e_remark'          => $edesc,
        );
        $this->db->insert('tm_keluar_qc_item', $data);
    }

    public function cek_data($id, $idcompany)
    {
        return $this->db->query(" 
                                    SELECT
                                        a.id,
                                        a.i_keluar_qc,
                                        to_char(a.d_keluar_qc, 'dd-mm-yyyy') as d_keluar_qc,
                                        a.i_bagian,
                                        a.i_tujuan,
                                        a.i_status,
                                        a.e_remark,
                                        a.id_jenis_barang_keluar,
                                        e_jenis_name
                                    FROM
                                       tm_keluar_qc a
                                    INNER JOIN tr_jenis_barang_keluar b ON (b.id = a.id_jenis_barang_keluar)
                                    WHERE                                        
                                        a.id = '$id'
                                    AND
                                        a.id_company = '$idcompany' 
                                ", FALSE);
    }

    public function cek_datadetail($id, $idcompany, $ibagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query(" 
                                    SELECT
                                       a.id_keluar_qc,
                                       a.id_product,
                                       b.i_product_base,
                                       b.e_product_basename,
                                       a.id_color,
                                       c.i_color,
                                       c.e_color_name,
                                       e.saldo_akhir,
                                       e.saldo_akhir_repair,
                                       a.n_quantity_product,
                                       a.e_remark 
                                    FROM
                                       tm_keluar_qc_item a 
                                       JOIN
                                          tm_keluar_qc d 
                                          ON a.id_keluar_qc = d.id 
                                       JOIN
                                          tr_product_base b 
                                          ON a.id_product = b.id 
                                          AND d.id_company = b.id_company 
                                       JOIN
                                          tr_color c 
                                          ON a.id_color = c.id 
                                          AND d.id_company = c.id_company 
                                       JOIN
                                          (SELECT DISTINCT 
                                          a.id,
                                          CASE
                                           WHEN c.n_saldo_akhir IS NULL THEN 0 
                                           WHEN c.n_saldo_akhir < 0 THEN 0 ELSE c.n_saldo_akhir
                                          END AS saldo_akhir,
                                          CASE
                                           WHEN c.n_saldo_akhir_repair IS NULL THEN 0 
                                           WHEN c.n_saldo_akhir_repair < 0 THEN 0 ELSE c.n_saldo_akhir_repair
                                          END AS saldo_akhir_repair
                                      FROM
                                          tr_product_base a
                                      INNER JOIN tr_color b ON
                                          (a.i_color = b.i_color
                                          AND a.id_company = b.id_company)
                                      LEFT JOIN (SELECT * FROM produksi.f_mutasi_wip($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                                          (c.id_product_base = a.id AND c.id_company = '$idcompany')
                                      WHERE
                                          a.id_company = '$idcompany'
                                          AND a.f_status = 't'
                                          AND b.f_status = 't') e
                                          ON e.id = a.id_product
                                    WHERE
                                       a.id_keluar_qc = '$id' 
                                       AND d.id_company = '$idcompany'
                                    ", FALSE);
    }

    public function view_datadetail($id, $idcompany)
    {
        return $this->db->query(" 
                                    SELECT
                                       a.id_keluar_qc,
                                       a.id_product,
                                       b.i_product_base,
                                       b.e_product_basename,
                                       a.id_color,
                                       c.i_color,
                                       c.e_color_name,
                                       a.n_quantity_product,
                                       a.e_remark 
                                    FROM
                                       tm_keluar_qc_item a 
                                       JOIN
                                          tm_keluar_qc d 
                                          ON a.id_keluar_qc = d.id 
                                       JOIN
                                          tr_product_base b 
                                          ON a.id_product = b.id 
                                          AND d.id_company = b.id_company 
                                       JOIN
                                          tr_color c 
                                          ON a.id_color = c.id 
                                          AND d.id_company = c.id_company 
                                    WHERE
                                       a.id_keluar_qc = '$id' 
                                       AND d.id_company = '$idcompany'
                                    ", FALSE);
    }

    public function updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $ijenis, $eremark)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_keluar_qc'       => $ibonk,
            'd_keluar_qc'       => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'e_remark'          => $eremark,
            'd_update'          => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_keluar_qc', $data);
    }

    public function deletedetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('id_keluar_qc', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_keluar_qc_item');
    }

    public function doc($imenu)
    {
        $this->db->select('doc_qe');
        $this->db->from('public.tm_menu');
        $this->db->where('i_menu', $imenu);
        return $this->db->get();
    }

    public function jenis_barang($itujuan)
    {
        /* $type = $this->db->query("SELECT i_type FROM tr_bagian WHERE i_bagian = '$itujuan' AND id_company = '$this->id_company' ")->row()->i_type;
        if ($type=='04') {
            return $this->db->query("SELECT * FROM tr_jenis_barang_keluar WHERE id <> '1' ");
        }else{
            return $this->db->query("SELECT * FROM tr_jenis_barang_keluar WHERE id = '1' ");
        } */
        return $this->db->query(
            "SELECT * FROM tr_jenis_barang_keluar WHERE id IN (
                SELECT UNNEST(id_jenis_barang_keluar) 
                FROM tr_tujuan_menu 
                WHERE i_menu = '$this->i_menu' 
                AND id_company = '$this->id_company' 
                AND i_bagian = '$itujuan'
            ) ORDER BY id
            "
        );
    }
}
/* End of file Mmaster.php */