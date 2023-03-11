<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $id_company  = $this->session->userdata('id_company');
        $i_departement = $this->session->userdata('i_departement');
        $username = $this->session->userdata('username');
        
        $where = "";
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_keluar_qc BETWEEN '$dfrom' AND '$dto' ";
        } 

        $datatables = new Datatables(new CodeigniterAdapter);

        $sql = "SELECT i_bagian
                FROM tm_keluar_qc
                WHERE i_status <> '5'
                    AND id_company = '$id_company' 
                    $where
                    AND i_bagian IN (
                                        SELECT i_bagian
                                        FROM tr_departement_cover
                                        WHERE i_departement = '$i_departement'
                                            AND id_company = '$id_company'
                                            AND username = '$username'
                                    )";

        $cek = $this->db->query($sql, FALSE);

        $bagian = "";
        if ($i_departement != '1') {            
            $session_i_departement = $this->session->userdata('i_departement');
            $session_id_company = $this->session->userdata('id_company');
            $session_username = $this->session->userdata('username');
            $bagian = "AND a.i_bagian IN (
                                            SELECT i_bagian
                                            FROM tr_departement_cover 
                                            WHERE i_departement = '$session_i_departement'
                                                AND id_company = '$session_id_company' 
                                                AND username = '$session_username'
                                        )";

            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }                                                
        }

        $TYPE_WIP = '23';

        $sql = "SELECT DISTINCT 0 as no,
                                a.id,
                                a.i_keluar_qc,
                                to_char(a.d_keluar_qc, 'dd-mm-yyyy') as d_keluar_qc,
                                a.i_tujuan,
                                ab.e_bagian_name,
                                concat(b.e_bagian_name, ' - ', c2.name) e_bagian_tujuan,
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
                    FROM tm_keluar_qc a 
                    JOIN tr_bagian b ON (
                            a.i_tujuan = b.i_bagian AND a.id_company_tujuan = b.id_company
                        ) 
                    JOIN tr_bagian ab ON (
                            ab.i_bagian = a.i_bagian AND a.id_company = ab.id_company AND ab.i_type = '$TYPE_WIP'
                        ) 
                    JOIN tr_status_document c ON a.i_status = c.i_status
                    JOIN tr_jenis_barang_keluar cc ON cc.id = a.id_jenis_barang_keluar
                    LEFT JOIN tr_menu_approve f ON (
                            a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu'
                        )
                    LEFT JOIN public.tr_level l ON f.i_level = l.i_level
                    LEFT JOIN public.company c2 ON c2.id = a.id_company_tujuan
                    WHERE a.id_company = '$id_company'
                        AND a.i_status <> '5' $where $bagian
                    ORDER BY a.id DESC";

        // var_dump($sql); die();

        $datatables->query($sql, false);

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
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye fa-lg mr-2 text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$ibagian\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-2'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$ibagian\",\"#main\"); return false;'><i class='ti-check-box fa-lg mr-2 text-primary'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close fa-lg text-danger'></i></a>";
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

    public function generate_nomor_dokumen($bagian, $tujuan) {
        $id_company = $this->id_company;

        $array_tujuan = explode('|', $tujuan);
        $id_company_tujuan = $array_tujuan[0];
        $i_bagian_tujuan = $array_tujuan[1];        

        $kode = 'STB';
        $where = "AND id_company_tujuan = '$id_company_tujuan'";

        if ($id_company_tujuan != $id_company) {
            $kode = 'SJ';
            $where = "AND NOT id_company_tujuan = '$id_company'";
        }

        $sql = "SELECT count(*) FROM tm_keluar_qc tkq
                    WHERE i_bagian = '$bagian'
                    AND id_company = '$id_company'
                    $where 
                    AND to_char(d_keluar_qc, 'yyyy-mm') = to_char(now(), 'yyyy-mm')
                    AND i_status <> '5'";

        $query = $this->db->query($sql);
        $result = $query->row()->count;
        $count = intval($result) + 1;
        $generated = $kode . '-' . date('ym') . '-' . sprintf('%04d', $count);

        return $generated;
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

    public function runningiditem()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_qc_item');
        return $this->db->get()->row()->id;
    }

    public function tujuan($i_menu, $idcompany)
    {
        /** cek
         *
        $sql = "SELECT b.name, a.id, a.i_bagian, a.id_company, a.e_bagian_name
        FROM tr_bagian a
        INNER JOIN public.company b ON (b.id = a.id_company)
        WHERE a.f_status = 't' AND a.i_type = '12' AND b.f_status = 't' AND b.i_apps = '2'
        AND (
        SELECT array_agg(id) FROM tr_type_makloon
        WHERE e_type_makloon_name ILIKE '%makloon packing%'
        ) && a.id_type_makloon
        ORDER BY 1,5";
         *
         */

        /** query tujuan menu current company semua bagian */
        $sql_company_internal = "SELECT 
                        c.name,
                        a.*,
                        b.e_bagian_name 
                    FROM 
                        tr_tujuan_menu a
                    JOIN tr_bagian b ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
                    JOIN public.company c ON c.id = a.id_company
                    WHERE
                        a.i_menu = '$i_menu'
                        AND a.id_company = '$idcompany'
                ";

        /** query tujuan menu external company bagian packing only */
        $PACKING = 12;
        $sql_company_external = "SELECT 
                        c.name,
                        a.*,
                        b.e_bagian_name 
                    FROM 
                        tr_tujuan_menu a
                    JOIN tr_bagian b ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
                    JOIN public.company c ON c.id = a.id_company
                    WHERE
                        a.i_menu = '$i_menu'
                        AND NOT a.id_company = '$idcompany'
                        AND b.i_type = '$PACKING'
                        AND (
                                SELECT array_agg(id) 
                                FROM tr_type_makloon
                                WHERE e_type_makloon_name ILIKE '%makloon packing%'
                            ) && b.id_type_makloon";

        $sql = "$sql_company_internal UNION $sql_company_external ORDER BY 1 ASC";
        
        // var_dump($sql);

        return $this->db->query($sql);
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

    /*----------  CARI REMARK  ----------*/

    public function marker($cari, $id_product_wip)
    {
        $idcompany = $this->session->userdata("id_company");

        $sql = "SELECT DISTINCT a.id_marker, b.e_marker_name
                FROM tr_polacutting_new a
                INNER JOIN tr_marker b ON (b.id = a.id_marker)
                INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
                INNER JOIN tr_product_base d ON (d.i_product_wip = c.i_product_wip)
                WHERE a.id_company = '$idcompany'
                    AND a.f_status = 't'
                    AND d.id = '$id_product_wip' 
                    AND b.e_marker_name ILIKE '%$cari%'";

        // var_dump($sql); die();

        return $this->db->query($sql, false);
    }

    public function getproduct($eproduct)
    {
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
                a.id_company = '$this->id_company'
            AND 
                a.id = '$eproduct'", FALSE);
    }

    public function getproduct_material($eproduct, $emarker)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');

        if ($this->id_company == '12') {
            $bagian = 'PL001';
        } else {
            $bagian = 'AKP001';
        }   
        return $this->db->query("SELECT
                e.id,
                e.i_material,
                e.e_material_name,
                f.e_satuan_name,
                round(1 / d.v_set * d.v_gelar,4) AS n_kebutuhan,
                coalesce(s.n_saldo_akhir,0) AS n_stock
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color
                    AND a.id_company = b.id_company)
            INNER JOIN tr_product_wip c ON
                (c.i_product_wip = a.i_product_wip
                    AND a.i_color = c.i_color
                    AND c.id_company = a.id_company)
            INNER JOIN tr_polacutting_new d ON
                (d.id_product_wip = c.id)
            INNER JOIN tr_material e ON
                (e.id = d.id_material)
            INNER JOIN tr_satuan f ON
                (f.i_satuan_code = e.i_satuan_code
                    AND e.id_company = f.id_company)
            LEFT JOIN (
                SELECT id_material, n_saldo_akhir FROM f_mutasi_material
                ($this->id_company, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$bagian') 
            ) s ON (s.id_material = e.id)
            WHERE
                a.id_company = '$this->id_company'
                AND ( e.i_kode_group_barang = 'GRB0004' OR (d.f_jahit = 't' AND d.f_packing = 't'))
                AND a.id = '$eproduct' and d.id_marker = '$emarker'", FALSE);
    }

    public function getstok($idproduct, $ibagian)
    {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');

        $sql = "SELECT DISTINCT a.id,
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
                FROM tr_product_base a
                INNER JOIN tr_color b ON (
                                        a.i_color = b.i_color AND a.id_company = b.id_company
                                        )
                LEFT JOIN (
                            SELECT * FROM produksi.f_mutasi_wip(
                                                        $idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian'
                                                        )
                        ) c ON (
                                c.id_product_base = a.id AND c.id_company = '$idcompany'
                                )
                WHERE a.id = '$idproduct'
                    AND a.id_company = '$idcompany'
                    AND a.f_status = 't'
                    AND b.f_status = 't'
                ORDER BY a.id ASC";
        
        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $ijenis, $eremark, $id_company_tujuan)
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
            'id_company_tujuan' => $id_company_tujuan
        );
        $this->db->insert('tm_keluar_qc', $data);
    }

    public function insertdetail($id, $iproduct, $imarker, $icolor, $nqtyproduct, $edesc)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_keluar_qc'      => $id,
            'id_product'        => $iproduct,
            'id_marker'        => $imarker,
            'id_color'          => $icolor,
            'n_quantity_product' => $nqtyproduct,
            'n_sisa'            => $nqtyproduct,
            'id_company'        => $idcompany,
            'e_remark'          => $edesc,
        );
        $this->db->insert('tm_keluar_qc_item', $data);
    }
    
    public function insertbundling($id, $iditem, $iproduct, $nqtyproduct)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_keluar_qc' => $id,
            'id_keluar_qc_item'      => $iditem,
            'id_product'        => $iproduct,
            'n_quantity_bundling' => $nqtyproduct,
            'id_company'        => $idcompany,
        );
        $this->db->insert('tm_keluar_qc_bundling', $data);
    }

    public function cek_data($id, $idcompany)
    {
        $sql = "SELECT a.id,
                    a.i_keluar_qc,
                    to_char(a.d_keluar_qc, 'dd-mm-yyyy') as d_keluar_qc,
                    a.i_bagian,
                    a.i_tujuan,
                    a.i_status,
                    a.e_remark,
                    a.id_jenis_barang_keluar,
                    e_jenis_name,
                    a.id_company_tujuan
                FROM tm_keluar_qc a
                INNER JOIN tr_jenis_barang_keluar b ON (b.id = a.id_jenis_barang_keluar)
                WHERE a.id = '$id' AND a.id_company = '$idcompany'";

        return $this->db->query($sql, FALSE);
    }

    public function cek_datadetail($id, $idcompany, $ibagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
        if ($this->id_company == '12') {
            $bagian = 'PL001';
        } else {
            $bagian = 'AKP001';
        }   
        /** 20220910 Alghi */
        /* return $this->db->query(
            "SELECT
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
            ", FALSE); */
        return $this->db->query(
            "SELECT
                    a.id,
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
                    a.e_remark,
                    h.id id_material,
                    i_material,
                    e_material_name,
                    e_satuan_name,
                    round(1 / v_set * v_gelar,4) AS n_kebutuhan,
                    COALESCE (n_saldo_akhir,0) n_saldo_akhir,
                    n_quantity_product * (1 / v_set * v_gelar) AS n_kebutuhan_material,
                    g.id_marker, k.e_marker_name/* ,
                    l.id AS id_bundling,
                    l.id_keluar_qc_item,
                    l.id_product as id_product_bundling,
                    m.i_product_base AS i_product_base_bundling,
                    m.e_product_basename AS e_product_basename_bundling,
                    n.e_color_name AS e_color_name_bundling,
                    l.n_quantity_bundling,
                    l.e_remark as e_remark_bundling */
                FROM
                    tm_keluar_qc_item a
                INNER JOIN tm_keluar_qc d ON (a.id_keluar_qc = d.id)
                INNER JOIN tr_product_base b ON (
                    a.id_product = b.id
                    AND d.id_company = b.id_company
                )
                INNER JOIN tr_color c ON (
                    a.id_color = c.id
                    AND d.id_company = c.id_company
                )
                INNER JOIN (
                    SELECT DISTINCT a.id,
                        CASE WHEN c.n_saldo_akhir IS NULL THEN 0
                            WHEN c.n_saldo_akhir < 0 THEN 0
                            ELSE c.n_saldo_akhir
                        END AS saldo_akhir,
                        CASE WHEN c.n_saldo_akhir_repair IS NULL THEN 0
                            WHEN c.n_saldo_akhir_repair < 0 THEN 0
                            ELSE c.n_saldo_akhir_repair
                        END AS saldo_akhir_repair
                    FROM
                        tr_product_base a
                    INNER JOIN tr_color b ON
                        (a.i_color = b.i_color
                            AND a.id_company = b.id_company)
                    LEFT JOIN (SELECT * FROM produksi.f_mutasi_wip($this->id_company,'$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON (
                        c.id_product_base = a.id
                            AND c.id_company = '$this->id_company'
                    )
                    WHERE
                        a.id_company = '$this->id_company'
                        AND a.f_status = 't'
                        AND b.f_status = 't') e ON (e.id = a.id_product)
                INNER JOIN tr_product_wip f ON (f.i_product_wip = b.i_product_wip AND b.i_color = f.i_color AND f.id_company = b.id_company)
                INNER JOIN tr_polacutting_new g ON (g.id_product_wip = f.id and g.id_company = f.id_company and g.id_company = a.id_company and g.id_marker = a.id_marker)
                INNER JOIN tr_material h ON (h.id = g.id_material)
                INNER JOIN tr_satuan i ON (
                    i.i_satuan_code = h.i_satuan_code AND h.id_company = i.id_company
                )
                LEFT JOIN f_mutasi_material('$this->id_company', '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$bagian') j ON (j.id_material = h.id)
                inner join tr_marker k ON (k.id = g.id_marker)
                /* inner join tm_keluar_qc_bundling l ON (l.id_keluar_qc_item = a.id)
                inner join tr_product_base m ON (m.id = l.id_product and m.id_company = l.id_company)
                inner join tr_color n ON (n.id = a.id_color and l.id_company = n.id_company) */
                WHERE
                    a.id_keluar_qc = '$id'                    
                    AND d.id_company = '$this->id_company'
                    AND (
                        h.i_kode_group_barang = 'GRB0004'
                        OR (g.f_jahit = 't' AND g.f_packing = 't')
                    ) 
                ORDER BY a.id_product/* , l.id_product */"
        );
    }

    public function view_datadetail($id, $idcompany)
    {
        // return $this->db->query("SELECT
        //         a.id,
        //         a.id_keluar_qc,
        //         a.id_product,
        //         b.i_product_base,
        //         b.e_product_basename,
        //         a.id_color,
        //         c.i_color,
        //         c.e_color_name,
        //         a.n_quantity_product,
        //         a.e_remark,
        //         i_material,
        //         e_material_name,
        //         e_satuan_name,
        //         round(1 / v_set * v_gelar,4) AS n_kebutuhan,
        //         n_quantity_product * round(1 / v_set * v_gelar,4) AS n_kebutuhan_material,
        //         f.id_marker, i.e_marker_name
        //     FROM
        //         tm_keluar_qc_item a 
        //     JOIN tm_keluar_qc d ON (a.id_keluar_qc = d.id)
        //     JOIN tr_product_base b ON (
        //         a.id_product = b.id AND d.id_company = b.id_company
        //     )
        //     JOIN tr_color c ON (
        //         a.id_color = c.id AND d.id_company = c.id_company
        //     )
        //     JOIN tr_product_wip e ON (
        //         e.i_product_wip = b.i_product_wip AND b.i_color = e.i_color AND e.id_company = b.id_company
        //     )
        //     JOIN tr_polacutting_new f ON (f.id_product_wip = e.id and f.id_company = e.id_company and f.id_company = a.id_company and f.id_marker = a.id_marker)
        //     JOIN tr_material g ON (g.id = f.id_material)
        //     JOIN tr_satuan h ON (
        //         h.i_satuan_code = g.i_satuan_code AND g.id_company = h.id_company
        //     )
        //     inner join tr_marker i ON (i.id = f.id_marker)
        //     WHERE
        //         a.id_keluar_qc = '$id' 
        //         AND d.id_company = '$idcompany'
        //         AND g.i_kode_group_barang = 'GRB0004' OR (f.f_jahit = 't' AND f.f_packing = 't')
        //     ORDER BY id_product
        // ", FALSE);

        $sql = "SELECT
            a.id,
            a.id_keluar_qc,
            a.id_product,
            b.i_product_base,
            b.e_product_basename,
            a.id_color,
            c.i_color,
            c.e_color_name,
            a.n_quantity_product,
            a.e_remark,
            i_material,
            e_material_name,
            e_satuan_name,
            round(1 / v_set * v_gelar,4) AS n_kebutuhan,
            n_quantity_product * round(1 / v_set * v_gelar,4) AS n_kebutuhan_material,
            f.id_marker, i.e_marker_name/* ,
            j.id AS id_bundling,
            j.id_keluar_qc_item,
            j.id_product as id_product_bundling,
            k.i_product_base AS i_product_base_bundling,
            k.e_product_basename AS e_product_basename_bundling,
            l.e_color_name AS e_color_name_bundling,
            j.n_quantity_bundling,
            j.e_remark as e_remark_bundling */
        FROM
            tm_keluar_qc_item a 
        JOIN tm_keluar_qc d ON (a.id_keluar_qc = d.id)
        JOIN tr_product_base b ON (
            a.id_product = b.id AND d.id_company = b.id_company
        )
        JOIN tr_color c ON (
            a.id_color = c.id AND d.id_company = c.id_company
        )
        /* JOIN tm_keluar_qc_bundling j ON (a.id = j.id_keluar_qc_item)
        JOIN tr_product_base k ON (
            j.id_product = k.id AND j.id_company = k.id_company
        )
        JOIN tr_color l ON (
            a.id_color = l.id AND j.id_company = l.id_company
        ) */
        JOIN tr_product_wip e ON (
            e.i_product_wip = b.i_product_wip AND b.i_color = e.i_color AND e.id_company = b.id_company
        )
        JOIN tr_polacutting_new f ON (f.id_product_wip = e.id and f.id_company = e.id_company and f.id_company = a.id_company and f.id_marker = a.id_marker)
        JOIN tr_material g ON (g.id = f.id_material)
        JOIN tr_satuan h ON (
            h.i_satuan_code = g.i_satuan_code AND g.id_company = h.id_company
        )
        inner join tr_marker i ON (i.id = f.id_marker)
        WHERE
            a.id_keluar_qc = '$id' 
            AND d.id_company = '$idcompany'
            AND (
                g.i_kode_group_barang = 'GRB0004'
                OR (f.f_jahit = 't' AND f.f_packing = 't')
            ) 
        ORDER BY a.id_product/* , j.id_product */";

        // var_dump($sql); die();

        return $this->db->query($sql);
    }

    public function view_databundling($id, $company)
    {
        $sql = "SELECT j.*, k.i_product_base, k.e_product_basename, l.e_color_name FROM tm_keluar_qc_bundling j
                INNER JOIN tm_keluar_qc_item a ON (a.id = j.id_keluar_qc_item)
                JOIN tr_product_base k ON (
                                            j.id_product = k.id AND j.id_company = k.id_company
                                        )
                JOIN tr_color l ON (
                                    k.i_color = l.i_color AND j.id_company = l.id_company
                                )
                WHERE a.id_keluar_qc = '$id' 
                    AND j.id_company = '$company'
                ORDER BY j.id_product";

        return $this->db->query($sql, FALSE);
    }

    public function updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $id_company_tujuan, $ijenis, $eremark)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_keluar_qc'       => $ibonk,
            'd_keluar_qc'       => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'id_company_tujuan' => $id_company_tujuan,
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

    public function deletebundling($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('id_keluar_qc', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_keluar_qc_bundling');
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
        $tujuan = explode('|', $itujuan);
        $tujuan_company = $tujuan[0];
        $tujuan_bagian = $tujuan[1];

        $sql = "SELECT * FROM tr_jenis_barang_keluar 
                WHERE id IN (
                    SELECT UNNEST(id_jenis_barang_keluar) 
                    FROM tr_tujuan_menu 
                    WHERE i_menu = '$this->i_menu' 
                    AND id_company = '$tujuan_company' 
                    AND i_bagian = '$tujuan_bagian'
                ) ORDER BY id";

        // var_dump($sql); die();

        return $this->db->query($sql);
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
                $this->generate_memo($id);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_qc', $data);
    }

    // public function runningnumber_memo($ibagian, $id_company=null)
    // {
    //     if ($id_company == null) {
    //         $id_company = $this->id_company;
    //     }

    //     $thbl = date('ym');
    //     $kode = "MM";
    //     $sql = "SELECT
    //                 max(substring(i_document, 9, 4)) AS max
    //             FROM
    //                 tm_memo_permintaan
    //             WHERE to_char (d_document, 'yymm') = '$thbl'
    //             AND i_status <> '5'
    //             AND i_bagian = '$ibagian'
    //             AND substring(i_document, 1, 2) = '$kode'
    //             AND substring(i_document, 4, 2) = substring('$thbl',1,2)
    //             AND id_company = '$id_company'";

    //     // var_dump($sql); die();

    //     $query = $this->db->query($sql);

    //     if ($query->num_rows() > 0) {
    //         foreach ($query->result() as $row) {
    //             $no = $row->max;
    //         }
    //         $number = $no + 1;
    //         settype($number, "string");
    //         $n = strlen($number);
    //         while ($n < 4) {
    //             $number = "0" . $number;
    //             $n = strlen($number);
    //         }
    //         $number = $kode . "-" . $thbl . "-" . $number;
    //         return $number;
    //     } else {
    //         $number = "0001";
    //         $nomer  = $kode . "-" . $thbl . "-" . $number;
    //         return $nomer;
    //     }
    // }

    public function runningnumber_memo($i_tujuan) 
    {        
        $prefix = 'MM';

        $sql = "SELECT count(*) FROM tm_memo_permintaan tmp
                    WHERE i_tujuan = '$i_tujuan'
                    AND to_char(d_document, 'yyyy-mm') = to_char(now(), 'yyyy-mm')
                    AND i_status <> '5'";

        $query = $this->db->query($sql);
        $result = $query->row()->count;
        $count = intval($result) + 1;
        $generated = $prefix . '-' . date('ym') . '-' . sprintf('%04d', $count);

        return $generated;
    }

    public function get_data_detail($id, $ibagian)
    {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
         if ($this->id_company == '12') {
            $bagian = 'PL001';
        } else {
            $bagian = 'AKP001';
        }   
        $sql = "SELECT
                    a.id_keluar_qc,
                    f.id id_product,
                    b.i_product_base,
                    b.e_product_basename,
                    a.id_color,
                    c.i_color,
                    c.e_color_name,
                    e.saldo_akhir,
                    e.saldo_akhir_repair,
                    a.n_quantity_product,
                    a.e_remark,
                    h.id id_material,
                    i_material,
                    e_material_name,
                    e_satuan_name,
                    round(1 / v_set * v_gelar,4) AS n_kebutuhan,
                    COALESCE (n_saldo_akhir,0) n_saldo_akhir,
                    n_quantity_product * (1 / v_set * v_gelar) AS n_kebutuhan_material
                FROM tm_keluar_qc_item a
                INNER JOIN tm_keluar_qc d ON (a.id_keluar_qc = d.id)
                INNER JOIN tr_product_base b ON (
                                                a.id_product = b.id AND d.id_company = b.id_company
                                            )
                INNER JOIN tr_color c ON ( 
                                            a.id_color = c.id AND d.id_company = c.id_company
                                        )
                INNER JOIN (
                            SELECT DISTINCT a.id,
                                CASE WHEN c.n_saldo_akhir IS NULL THEN 0
                                    WHEN c.n_saldo_akhir < 0 THEN 0
                                    ELSE c.n_saldo_akhir
                                END AS saldo_akhir,
                                CASE WHEN c.n_saldo_akhir_repair IS NULL THEN 0
                                    WHEN c.n_saldo_akhir_repair < 0 THEN 0
                                    ELSE c.n_saldo_akhir_repair
                                END AS saldo_akhir_repair
                            FROM tr_product_base a
                            INNER JOIN tr_color b ON (
                                                        a.i_color = b.i_color AND a.id_company = b.id_company
                                                    )
                            LEFT JOIN (
                                        SELECT * 
                                        FROM produksi.f_mutasi_wip($this->id_company,'$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')
                                    ) c ON (
                                            c.id_product_base = a.id AND c.id_company = '$this->id_company'
                                        )
                            WHERE a.id_company = '$this->id_company'
                                AND a.f_status = 't'
                                AND b.f_status = 't'
                        ) e ON (e.id = a.id_product)
                INNER JOIN tr_product_wip f ON (f.i_product_wip = b.i_product_wip AND b.i_color = f.i_color AND f.id_company = b.id_company)
                INNER JOIN tr_polacutting_new g ON (g.id_product_wip = f.id and g.id_marker = a.id_marker)
                INNER JOIN tr_material h ON (h.id = g.id_material)
                INNER JOIN tr_satuan i ON (
                    i.i_satuan_code = h.i_satuan_code AND h.id_company = i.id_company
                )
                LEFT JOIN f_mutasi_material('$this->id_company', '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$bagian') j ON (j.id_material = h.id) 
                WHERE
                    a.id_keluar_qc = '$id'
                    AND h.i_kode_group_barang = 'GRB0004'
                    AND d.id_company = '$this->id_company'
                ORDER BY a.id_product";

        // die($sql);

        return $this->db->query($sql);
    }

    public function generate_memo($id = null)
    {
        $this->db->select("i_tujuan, i_bagian, id, id_company, id_company_tujuan");
        $this->db->where("id", $id);
        
        $query = $this->db->get("tm_keluar_qc")->row();
        
        $i_bagian = $query->i_bagian;
        $i_tujuan = $query->i_tujuan;
        $id_company = $query->id_company;
        $id_company_tujuan = $query->id_company_tujuan;

        $this->db->select('max(id) AS id');
        $this->db->from('tm_memo_permintaan');
        $id = $this->db->get()->row()->id + 1;       

        // table tr_bagian
        $this->db->select('id')
            ->where(['i_bagian' => $i_tujuan, 'id_company' => $id_company_tujuan]);
        $query_bagian = $this->db->get("tr_bagian")->row();       
        
        $i_document = $this->runningnumber_memo($query_bagian->id);

        /** set pembuat sebagai memo sebagai PACKING dari sebelumnya WIP 
         * berdasarakan tujuan  */         
        $get_bagian_packing = $this->get_bagian_packing($id_company_tujuan);
        if ($get_bagian_packing != null) {
            $i_bagian = $get_bagian_packing->i_bagian;
            $id_company_tujuan = $get_bagian_packing->id_company;
        }

        $eremark = "Memo dari STB WIP " . $this->get_company($id_company)->name;
        
        $data_header = array(
            'id' => $id,
            'id_company' => $id_company_tujuan,
            'i_document' => $i_document,
            'd_document' => date('Y-m-d'),
            'd_kirim' => date('Y-m-d'),
            'i_bagian' => $i_bagian,
            'id_type_penerima' => 3,
            'i_status' => 6,
            'e_approve' => "SYSTEM",
            'd_approve' => date('Y-m-d'),
            'e_remark' => $eremark,
            'i_tujuan' => $query_bagian->id,
            /** id_company penerima masih sama dengan bagian pembuat */
            'id_company_penerima' => $this->id_company
        );

        $this->db->insert("tm_memo_permintaan", $data_header);

        $query = $this->get_data_detail($query->id, $i_bagian);    
        
        // var_dump($query->result());die();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                
                $kebutuhan_material = intval($key->n_kebutuhan_material);
                $saldo_akhir = intval($key->n_saldo_akhir);

                if ($kebutuhan_material > $saldo_akhir) {
                    die('Cek kebutuhan material');
                } else {
                    $data_detail = array(
                        "id_document" => $id,
                        "id_product" => $key->id_product,
                        "id_material" => $key->id_material,
                        "n_quantity" => $key->n_kebutuhan_material,
                        "n_quantity_sisa" => $key->n_kebutuhan_material,
                        "e_remark" => "Generate $eremark",
                        "n_quantity_product" => $key->n_quantity_product
                    );
                    $this->db->insert("tm_memo_permintaan_item", $data_detail);
                }
            }
        }
    }

    private function get_bagian_packing($id_company_tujuan=null)
    {
        if ($id_company_tujuan == null) {
            $id_company_tujuan = $this->id_company;
        }

        $TYPE_PACKING = '12';

        $sql = "SELECT * 
                FROM tr_bagian 
                WHERE i_type = '$TYPE_PACKING'
                    AND f_status = 't'
                    AND id_company = '$id_company_tujuan'";

        $query = $this->db->query($sql);

        return $query->row();
    }

    private function get_company($id)
    {
        $sql = "SELECT * 
                FROM public.company
                WHERE id='$id'";

        $query = $this->db->query($sql);

        return $query->row();
    }
}
/* End of file Mmaster.php */