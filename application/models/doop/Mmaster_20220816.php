<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    /*----------  LIST DATA SJ  ----------*/

    public function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        $cek = $this->db->query(
            "
            SELECT
                i_bagian
            FROM
                tm_sj a
            WHERE
                i_status <> '5'
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')",
            FALSE
        );

        if ($this->departement == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT DISTINCT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_customer,
                b.e_customer_name,
                a.id_document_reff,
                ba.i_document AS document_referensi,
                a.e_remark,
                e_status_name,
                label_color,
                l.i_level,
                l.e_level_name,
                a.i_status,
                a.n_print,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_sj a
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            LEFT JOIN tm_spb ba ON
                (a.id_document_reff = ba.id AND 
                ba.id_company = a.id_company)
            LEFT JOIN tr_customer b ON
                (b.id = a.id_customer AND 
                b.id_company = a.id_company)
            LEFT JOIN public.tr_menu_approve e on 
                (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on 
                (e.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->company'
                $and
                $bagian",
            FALSE
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->edit(
            'n_print',
            function ($data) {
                if ($data['n_print'] > 0) {
                    $status = 'Sudah ' . $data['n_print'] . ' x';
                    $warna  = 'info';
                } else {
                    $status = 'Belum';
                    $warna  = 'danger';
                }
                return '<span class="label label-' . $warna . ' label-rouded">' . $status . '</span>';
            }
        );

        $datatables->add(
            'action',
            function ($data) {
                $id            = trim($data['id']);
                $id_customer   = trim($data['id_customer']);
                $i_status      = trim($data['i_status']);
                $dfrom         = trim($data['dfrom']);
                $dto           = trim($data['dto']);
                $i_menu        = $data['i_menu'];
                $folder        = trim($data['folder']);
                $i_level       = $data['i_level'];
                $d_document    = $data['d_document'];
                $data = '';
                if (check_role($i_menu, 2)) {
                    $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$d_document/$dfrom/$dto/$id_customer\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-2'></i></a>";
                }
                if (check_role($i_menu, 3)) {
                    if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$d_document/$dfrom/$dto/id_customer\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-2'></i></a>";
                    }
                }
                if (check_role($i_menu, 7) && $i_status == '2') {
                    if ($i_level == $this->i_level || 1 == $this->i_level) {
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$d_document/$dfrom/$dto/$id_customer\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-2'></i></a>";
                    }
                }
                if (check_role($i_menu, 5)) {
                    if ($i_status == '6') {
                        $data .= "<a href=\"#\" title='Print Harga' onclick='cetak($id,\"y\"); return false;'><i class='ti-printer fa-lg mr-2 text-warning'></i></a>";
                        $data .= "<a href=\"#\" title='Print Non Harga' class='text-success' onclick='cetak($id,\"t\"); return false;'><i class='ti-printer fa-lg mr-2'></i></a>";
                    }
                }
                if (check_role($i_menu, 4) && ($i_status == '1')) {
                    $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-2'></i></a>";
                }

                return $data;
            }
        );
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_status');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_customer');
        $datatables->hide('id_document_reff');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian', 'inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    /*----------  RUNNING NO DOKUMEN BERDASARKAN TANGGAL DAN BAGIAN PEMBUAT  ----------*/

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query(
            "SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_sj
            WHERE i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->company'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SJ';
        }
        $query = $this->db->query(
            "SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_sj
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND substring(i_document, 1, 2) = '$kode'
                AND substring(i_document, 4, 2) = substring('$thbl', 1, 2)
                AND id_company = '$this->company'",
            FALSE
        );
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /*----------  CEK DOKUMENT SUDAH ADA  ----------*/

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_sj');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI AREA BERDASARKAN JENIS SPB  ----------*/

    public function area($cari)
    {
        return $this->db->query("SELECT
                DISTINCT a.id,
                a.i_area,
                a.e_area
            FROM
                tr_area a
            JOIN tm_spb b ON 
                (b.id_area = a.id)
            JOIN tm_spb_item c ON
                (c.id_document = b.id)
            WHERE
                a.f_status = 't'
                AND b.i_status = '6'
                AND b.id_company = $this->company
                AND (i_area LIKE '%$cari%' 
                    OR e_area ILIKE '%$cari%')
                AND c.n_quantity_sisa > 0
            ORDER BY id
            ", FALSE);
    }

    /*----------  CARI CUSTOMER BERDASARKAN AREA DAN JENIS SPB  ----------*/

    public function customer($cari, $iarea)
    {
        return $this->db->query("SELECT
            DISTINCT a.id,
                a.i_customer,
                a.e_customer_name
            FROM
                tr_customer a
            JOIN tm_spb b ON 
                (a.id = b.id_customer 
                AND b.id_company = a.id_company)
            JOIN tm_spb_item c ON 
                (c.id_document = b.id)
            WHERE
                a.f_status = 't'
                AND b.i_status = '6'
                AND b.id_area = '$iarea'
                AND (a.e_customer_name ILIKE '%$cari%' 
                OR a.i_customer ILIKE '%$cari%')
                AND a.id_company = '$this->company'
                AND c.n_quantity_sisa > 0
            ORDER BY 3
        ", FALSE);
    }

    /*----------  CARI REFERENSI BERDASARKAN JENIS SPB DAN CUSTOMER  ----------*/

    public function referensi($cari, $icustomer, $iarea)
    {
        return $this->db->query("SELECT DISTINCT
                a.i_document ||' ('||e_jenis_name||')' i_document,
                a.id,
                to_char(d_document, 'dd-mm-yyyy') as d_document 
            FROM
                tm_spb a 
            INNER JOIN tm_spb_item c ON (
                a.id = c.id_document) AND (a.id_company = c.id_company
            )
            INNER JOIN tr_jenis_barang_keluar d ON (d.id = a.id_jenis_barang_keluar)            
            WHERE
                a.i_status = '6' 
                AND a.id_area = '$iarea'
                AND a.id_customer = '$icustomer' 
                AND COALESCE(c.n_quantity_sisa, 0) > 0
                AND (TRIM(a.i_document) ILIKE '$cari%')
                AND a.id NOT IN
                    (SELECT 
                        id_document_reff 
                    FROM 
                        tm_sj 
                    WHERE 
                        i_status = '6')
        ", FALSE);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/

    public function getdetailrefeks($id,$i_customer,$d_document=null)
    {
        if ($d_document!=null || $d_document!='') {
            $i_periode = date('Ym', strtotime($d_document));
            $dfrom = date('Y-m', strtotime($d_document)).'-01';
            $dto = date('Y-m-t', strtotime($d_document));
        }else{
            $i_periode = date('Ym');
            $dfrom = date('Y-m-01');
            $dto = date('Y-m-t');
        }


        return $this->db->query("SELECT
                a.i_document,
                a.id,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.id_harga_kode,
                b.id_product,
                c.i_product_base,
                c.e_product_basename,
                b.n_quantity,
                b.n_quantity_sisa,
                b.v_price,
                b.n_diskon1,
                b.n_diskon2,
                b.n_diskon3,
                b.v_diskon_tambahan,
                d.n_customer_toplength,
                d.e_customer_name,
                e.e_color_name,
                COALESCE(CASE WHEN a.id_jenis_barang_keluar = '1' THEN f.n_saldo_akhir ELSE f.n_saldo_akhir_gradeb END, 0) AS n_stock,
                COALESCE(CASE WHEN a.id_jenis_barang_keluar = '1' THEN f.n_saldo_akhir-COALESCE(h.n_quantity_sj,0) ELSE f.n_saldo_akhir_gradeb-COALESCE(h.n_quantity_sj,0) END, 0) AS n_stock_outstanding,
                CASE
                    WHEN COALESCE(CASE WHEN a.id_jenis_barang_keluar = '1' THEN f.n_saldo_akhir ELSE f.n_saldo_akhir_gradeb END, 0) >= b.n_quantity_sisa THEN b.n_quantity_sisa
                    WHEN COALESCE(CASE WHEN a.id_jenis_barang_keluar = '1' THEN f.n_saldo_akhir ELSE f.n_saldo_akhir_gradeb END, 0) < b.n_quantity_sisa 
                    AND COALESCE(CASE WHEN a.id_jenis_barang_keluar = '1' THEN f.n_saldo_akhir ELSE f.n_saldo_akhir_gradeb END, 0) > 0 
                    THEN COALESCE(CASE WHEN a.id_jenis_barang_keluar = '1' THEN f.n_saldo_akhir ELSE f.n_saldo_akhir_gradeb END, 0)
                    ELSE 0
                END AS n_do,
                COALESCE(n_quantity_fc, 0) AS n_quantity_fc
                /* COALESCE(n_saldo_akhir, 0) AS n_stock,
                CASE
                    WHEN COALESCE(f.n_saldo_akhir, 0) >= b.n_quantity_sisa THEN b.n_quantity_sisa
                    WHEN COALESCE(f.n_saldo_akhir, 0) < b.n_quantity_sisa AND COALESCE(f.n_saldo_akhir, 0) > 0 THEN COALESCE(f.n_saldo_akhir, 0)
                    ELSE 0
                END AS n_do */
            FROM
                tm_spb a 
            INNER JOIN tm_spb_item b ON 
                (a.id = b.id_document 
                AND a.id_company = b.id_company) 
            INNER JOIN tr_product_base c ON 
                (b.id_product = c.id 
                AND b.id_company = c.id_company) 
            INNER JOIN tr_customer d 
                ON (a.id_customer = d.id 
                AND a.id_company = d.id_company)
            INNER JOIN tr_color e
                ON (e.i_color = c.i_color 
                AND c.id_company = e.id_company)
            LEFT JOIN produksi.f_mutasi_gudang_jadi ('$this->id_company','$i_periode','9999-01-01','9999-01-31','$dfrom','$dto','') f ON (f.id_product_base = c.id AND c.id_company = f.id_company) 
            LEFT JOIN (SELECT id_product, sum(n_quantity_fc) AS n_quantity_fc FROM produksi.f_get_forecast_distributor('$this->id_company','$i_periode','$i_customer') GROUP BY 1) g ON (g.id_product = c.id)
            LEFT JOIN (SELECT id_product, sum(n_quantity) AS n_quantity_sj FROM tm_sj a, tm_sj_item b WHERE a.id = b.id_document AND a.i_status IN ('1','2','3') AND a.id_company = '$this->id_company' AND a.id_document_reff <> '$id' GROUP BY 1) h ON (h.id_product = c.id)
            WHERE 
                COALESCE (b.n_quantity_sisa, 0) > 0
                AND a.id = '$id'
            ORDER BY
                a.i_document,
                c.e_product_basename ASC
        ", FALSE);
    }

    /*----------  RUNNING ID SJ  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_sj');
        return $this->db->get()->row()->id + 1;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id, $isj, $datedocument, $ibagian, $ireferensi, $icustomer, $ecustomername, $ncustop, $idarea, $idharga, $eremark)
    {
        $data = array(
            'id'                    => $id,
            'id_company'            => $this->company,
            'i_document'            => $isj,
            'd_document'            => $datedocument,
            'i_bagian'              => $ibagian,
            'id_document_reff'      => $ireferensi,
            'id_customer'           => $icustomer,
            'e_customer_name'       => $ecustomername,
            'n_customer_toplength'  => $ncustop,
            'id_area'               => $idarea,
            'id_harga_kode'         => $idharga,
            'e_remark'              => $eremark,
        );
        $this->db->insert('tm_sj', $data);
    }

    /*----------  SIMPAN DARA ITEM  ----------*/

    public function insertdetail($id, $ireferensi, $iproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vdiskontotal, $vtotal, $edesc)
    {
        $data = array(
            'id_company'            => $this->company,
            'id_document'           => $id,
            'id_document_reff'      => $ireferensi,
            'id_product'            => $iproduct,
            'n_quantity'            => $nquantity,
            'n_quantity_sisa'       => $nquantity,
            'v_price'               => $vprice,
            'n_diskon1'             => $ndiskon1,
            'n_diskon2'             => $ndiskon2,
            'n_diskon3'             => $ndiskon3,
            'v_diskon1'             => $vdiskon1,
            'v_diskon2'             => $vdiskon2,
            'v_diskon3'             => $vdiskon3,
            'v_diskon_tambahan'     => $vdiskonplus,
            'v_diskon_total'        => $vdiskontotal,
            'v_total'               => $vtotal,
            'e_remark'              => $edesc,
        );
        $this->db->insert('tm_sj_item', $data);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode, $ibagian, $kodeold, $ibagianold)
    {
        $this->db->select('i_document');
        $this->db->from('tm_sj');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  BACA EDIT HEADER  ----------*/

    public function baca_header($id)
    {
        return $this->db->query("SELECT
                a.id,
                a.id_company,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian,
                e.e_bagian_name,
                c.i_bagian AS ibagian_reff,
                a.id_document_reff,
                c.i_document AS i_referensi,
                to_char(c.d_document, 'dd-mm-yyyy') AS d_referensi,
                a.id_customer,
                i_customer,
                b.e_customer_name,                    
                e_city_name,e_customer_address,
                a.id_area,
                i_area,
                f.e_area,
                a.i_status,
                d.e_status_name,
                a.e_remark,
                a.n_customer_toplength,
                a.id_harga_kode,
                a.v_diskon,
                a.v_kotor,
                a.v_ppn,
                a.v_bersih,
                a.v_dpp,
                '' as e_jenis_spb
            FROM
                tm_sj a
            INNER JOIN tr_area f ON
                a.id_area = f.id
            INNER JOIN tr_customer b ON
                a.id_customer = b.id
                AND a.id_company = b.id_company
            INNER JOIN tm_spb c ON
                a.id_document_reff = c.id
                AND a.id_company = c.id_company
            INNER JOIN tr_status_document d ON
                d.i_status = a.i_status
            INNER JOIN tr_bagian e ON
                e.i_bagian = a.i_bagian
                AND a.id_company = e.id_company
            INNER JOIN tr_city h ON 
                (h.id = b.id_city)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    /*----------  BACA EDIT DETAIL  ----------*/

    public function baca_detail($id,$d_document=null)
    {
        if ($d_document!=null || $d_document!='') {
            $i_periode = date('Ym', strtotime($d_document));
            $dfrom = date('Y-m', strtotime($d_document)).'-01';
            $dto = date('Y-m-t', strtotime($d_document));
        }else{
            $i_periode = date('Ym');
            $dfrom = date('Y-m-01');
            $dto = date('Y-m-t');
        }
        return $this->db->query("SELECT
                DISTINCT ON
                (a.id_product) b.id,
                b.id_company,
                b.i_document,
                b.id_document_reff,
                a.id_product,
                c.i_product_base,
                c.e_product_basename,
                e_satuan_name,
                d.n_quantity AS nquantity_permintaan,
                d.n_quantity_sisa AS nquantity_pemenuhan,
                a.n_quantity,
                a.n_quantity_sisa,
                d.v_price,
                d.n_diskon1,
                d.n_diskon2,
                d.n_diskon3,
                d.v_diskon_tambahan,
                a.e_remark,
                /* f.saldo_akhir */
                COALESCE(CASE WHEN e.id_jenis_barang_keluar = '1' THEN f.n_saldo_akhir ELSE f.n_saldo_akhir_gradeb END, 0) saldo_akhir
            FROM
                tm_sj_item a
            JOIN tm_sj b ON
                b.id = a.id_document
            JOIN tr_product_base c ON
                a.id_product = c.id
            JOIN tr_satuan s ON
                s.i_satuan_code = c.i_satuan_code
                AND c.id_company = s.id_company
            JOIN tm_spb_item d ON
                (b.id_document_reff = d.id_document)
                AND (d.id_product = a.id_product)
            JOIN tm_spb e ON (e.id = d.id_document)
            LEFT JOIN f_mutasi_gudang_jadi ('$this->id_company','$i_periode','9999-01-01','9999-01-31','$dfrom','$dto','') f ON 
                (f.id_product_base = c.id AND c.id_company = f.id_company) 
            WHERE
                b.id = '$id'
        ", FALSE);
    }

    /*----------  UPDATE HEADER  ----------*/

    public function updateheader($id, $isj, $datedocument, $ibagian, $ireferensi, $icustomer, $ecustomername, $ncustop, $idarea, $idharga, $eremark)
    {
        $data = array(
            'id_company'            => $this->company,
            'i_document'            => $isj,
            'd_document'            => $datedocument,
            'i_bagian'              => $ibagian,
            'id_document_reff'      => $ireferensi,
            'id_customer'           => $icustomer,
            'e_customer_name'       => $ecustomername,
            'n_customer_toplength'  => $ncustop,
            'id_area'               => $idarea,
            'id_harga_kode'         => $idharga,
            'e_remark'              => $eremark,
            'd_update'              => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_sj', $data);
    }

    /*----------  DELETE DETAIL SAAT UPDATE  ----------*/

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_sj_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus)
    {
        /* if ($istatus == '6') {
            $this->updatesisa($id);
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_sj', $data); */
        $now = date('Y-m-d');
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("
            	SELECT b.i_menu , a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut from tm_sj a
				inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
				where a.id = '$id'
				group by 1,2", FALSE)->row();

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
                $this->db->query("delete from tm_menu_approve where i_menu = '$this->i_menu' and i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $this->updatesisa($id);
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => $now,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $this->db->query("
            		INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					 ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_sj');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_sj', $data);
    }

    /*----------  END UPDATE STATUS DOKUMEN  ----------*/

    /*----------  UPDATE SISA  ----------*/

    public function updatesisa($id)
    {
        $idtypespb = $this->db->query("
            SELECT 
                i_type_spb 
            FROM 
                tm_sj
            WHERE 
                id = $id
            ", FALSE)->row()->i_type_spb;
        $query = $this->db->query("
            SELECT 
                id_document, 
                id_product, 
                n_quantity, 
                n_quantity_sisa, 
                id_document_reff
            FROM 
                tm_sj_item
            WHERE 
                id_document = '$id' 
            ", FALSE);
        if ($idtypespb == '1') {
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query("
                        SELECT
                            n_quantity_sisa
                        FROM
                            tm_spb_item
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product  = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                    ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                        UPDATE
                            tm_spb_item
                        SET
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product  = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                        ", FALSE);
                    } else {
                        die();
                    }
                }
            }
        } else if ($idtypespb == '2') {
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query("
                        SELECT
                            n_quantity_sisa
                        FROM
                            tm_spb_ds_item
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product  = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                    ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                        UPDATE
                            tm_spb_ds_item
                        SET
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product  = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                        ", FALSE);
                    } else {
                        die();
                    }
                }
            }
        } else if ($idtypespb == '3') {
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query("
                        SELECT
                            n_quantity_sisa
                        FROM
                            tm_spb_distributor_item
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product  = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                    ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                        UPDATE
                            tm_spb_distributor_item
                        SET
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product  = '$key->id_product'
                            AND id_company  = '$this->company'
                            AND n_quantity_sisa >= '$key->n_quantity'
                        ", FALSE);
                    } else {
                        die();
                    }
                }
            }
        }
    }

    /*----------  RUNNING ID SPB TURUNAN  ----------*/

    public function runningidspb($ijenis)
    {
        if ($ijenis == '1') {
            $this->db->select('max(id) AS id');
            $this->db->from('tm_spb');
            return $this->db->get()->row()->id + 1;
        } else if ($ijenis == '2') {
            $this->db->select('max(id) AS id');
            $this->db->from('tm_spb_ds');
            return $this->db->get()->row()->id + 1;
        } else if ($ijenis == '3') {
            $this->db->select('max(id) AS id');
            $this->db->from('tm_spb_distributor');
            return $this->db->get()->row()->id + 1;
        }
    }

    /*----------  RUNNING NOMOR DOKUMEN SPB TURUNAN  ----------*/

    public function runningnumberspb($thbl, $tahun, $ibagian, $ijenis)
    {
        if ($ijenis == '1') {
            $cek = $this->db->query(
                "
                SELECT 
                    substring(i_document, 1, 3) AS kode 
                FROM 
                    tm_spb
                WHERE 
                    i_status <> '5'
                    AND i_bagian = '$ibagian'
                    AND id_company = '$this->company'
                ORDER BY 
                    id DESC"
            );
            if ($cek->num_rows() > 0) {
                $kode = $cek->row()->kode;
            } else {
                $kode = 'SPB';
            }
            $query = $this->db->query("
                SELECT
                    max(substring(i_document, 10, 6)) AS max
                FROM
                    tm_spb
                WHERE to_char (d_document, 'yyyy') >= '$tahun'
                    AND i_status <> '5'
                    AND i_bagian = '$ibagian'
                    AND substring(i_document, 1, 3) = '$kode'
                    AND id_company = '$this->company'
            ", false);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $no = $row->max;
                }
                $number = $no + 1;
                settype($number, "string");
                $n = strlen($number);
                while ($n < 6) {
                    $number = "0" . $number;
                    $n = strlen($number);
                }
                $number = $kode . "-" . $thbl . "-" . $number;
                return $number;
            } else {
                $number = "000001";
                $nomer  = $kode . "-" . $thbl . "-" . $number;
                return $nomer;
            }
        } else if ($ijenis == '2') {
            $cek = $this->db->query("
                SELECT 
                    substring(i_document, 1, 5) AS kode 
                FROM 
                    tm_spb_ds 
                WHERE 
                    i_status <> '5'
                    AND i_bagian = '$ibagian'
                    AND id_company = '$this->company'
                ORDER BY id DESC", FALSE);
            if ($cek->num_rows() > 0) {
                $kode = $cek->row()->kode;
            } else {
                $kode = 'SPBDS';
            }
            $query = $this->db->query("
                SELECT
                    max(substring(i_document, 12, 6)) AS max
                FROM
                    tm_spb_ds
                WHERE 
                    to_char (d_document, 'yyyy') >= '$tahun'
                    AND i_status <> '5'
                    AND i_bagian = '$ibagian'
                    AND id_company = '$this->company'
                    AND substring(i_document, 1, 5) = '$kode'
                    AND substring(i_document, 7, 2) = substring('$thbl',1,2)
                ", FALSE);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $no = $row->max;
                }
                $number = $no + 1;
                settype($number, "string");
                $n = strlen($number);
                while ($n < 6) {
                    $number = "0" . $number;
                    $n = strlen($number);
                }
                $number = $kode . "-" . $thbl . "-" . $number;
                return $number;
            } else {
                $number = "000001";
                $nomer  = $kode . "-" . $thbl . "-" . $number;
                return $nomer;
            }
        } else if ($ijenis == '3') {
            $cek = $this->db->query(
                "
                SELECT
                    substring(i_document, 1, 4) AS kode
                FROM 
                    tm_spb_distributor
                WHERE 
                    i_status <> '5'
                    AND i_bagian = '$ibagian'
                    AND id_company = '$this->company'
                    ORDER BY id DESC LIMIT 1",
                FALSE
            );

            if ($cek->num_rows() > 0) {
                $kode = $cek->row()->kode;
            } else {
                $kode = 'SPBD';
            }
            $query = $this->db->query("
                SELECT
                    max(substring(i_document, 11, 6)) AS max
                FROM
                    tm_spb_distributor
                WHERE 
                    i_status <> '5'
                    AND i_bagian = '$ibagian'
                    AND id_company = '$this->company'
                    AND substring(i_document, 1, 4) = '$kode'
                    AND substring(i_document, 6, 2) = substring('$thbl',1,2)
                    AND to_char (d_document, 'yyyy') >= '$tahun'
                ", FALSE);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $no = $row->max;
                }
                $number = $no + 1;
                settype($number, "string");
                $n = strlen($number);
                while ($n < 6) {
                    $number = "0" . $number;
                    $n = strlen($number);
                }
                $number = $kode . "-" . $thbl . "-" . $number;
                return $number;
            } else {
                $number = "000001";
                $nomer  = $kode . "-" . $thbl . "-" . $number;
                return $nomer;
            }
        }
    }

    /*----------  INSERT HEADER SPB TURUNAN  ----------*/

    public function insertspbnew($idbaru, $ibagian, $idocument, $datedocument, $iarea, $icustomer, $ireferensi, $datereferensi, $ndiskontotal, $nkotor, $nbersih, $vdpp, $vppn, $eremark, $ijenis, $idkodeharga, $ejenisspb)
    {
        if ($ijenis == '1') {
            $query = $this->db->query("
                SELECT 
                    d_estimate, 
                    id_area, 
                    id_sales, 
                    i_referensi_op
                FROM 
                    tm_spb
                WHERE 
                    id = '$ireferensi' 
                    AND d_document = '$datereferensi'
                ", FALSE);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $destimate    = $key->d_estimate;
                    $idarea       = $key->id_area;
                    $idsales      = $key->id_sales;
                    $ireferensiop = $key->i_referensi_op;
                }
            }

            $data = array(
                'id'               => $idbaru,
                'id_company'       => $this->company,
                'i_document'       => $idocument,
                'd_document'       => $datedocument,
                'i_bagian'         => $ibagian,
                'd_estimate'       => $destimate,
                'id_customer'      => $icustomer,
                'id_area'          => $idarea,
                'id_sales'         => $idsales,
                'id_spb_referensi' => $ireferensi,
                'v_diskon'         => $ndiskontotal,
                'v_kotor'          => $nkotor,
                'v_ppn'            => $vppn,
                'v_bersih'         => $nbersih,
                'v_dpp'            => $vdpp,
                'id_harga_kode'    => $idkodeharga,
                'e_remark'         => $eremark,
                'd_entry'          => current_datetime(),
            );
            $this->db->insert('tm_spb', $data);
        } else if ($ijenis == '2') {
            $query = $this->db->query("
                SELECT
                    d_estimate,
                    id_area,
                    id_sales,
                    i_referensi_op
                FROM
                    tm_spb_ds
                WHERE
                    id = '$ireferensi'
                    AND d_document = '$datereferensi'
            ", FALSE);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $destimate    = $key->d_estimate;
                    $idarea       = $key->id_area;
                    $idsales      = $key->id_sales;
                    $ireferensiop = $key->i_referensi_op;
                }
            }

            $data = array(
                'id'               => $idbaru,
                'id_company'       => $this->company,
                'i_document'       => $idocument,
                'd_document'       => $datedocument,
                'i_bagian'         => $ibagian,
                'd_estimate'       => $destimate,
                'id_customer'      => $icustomer,
                'id_area'          => $idarea,
                'id_sales'         => $idsales,
                'id_spb_referensi' => $ireferensi,
                'v_diskon'         => $ndiskontotal,
                'v_kotor'          => $nkotor,
                'v_ppn'            => $vppn,
                'v_bersih'         => $nbersih,
                'v_dpp'            => $vdpp,
                'id_harga_kode'    => $idkodeharga,
                'e_remark'         => $eremark,
                'd_entry'          => current_datetime(),
            );
            $this->db->insert('tm_spb_ds', $data);
        } else if ($ijenis == '3') {
            $query = $this->db->query("
                SELECT
                    id_area,
                    i_referensi
                FROM
                    tm_spb_distributor
                WHERE
                    id = '$ireferensi'
                    AND d_document = '$datereferensi'
                ", FALSE);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $idarea       = $key->id_area;
                    $ireferensiop = $key->i_referensi;
                }
            }

            $data = array(
                'id'               => $idbaru,
                'id_company'       => $this->company,
                'i_document'       => $idocument,
                'd_document'       => $datedocument,
                'i_bagian'         => $ibagian,
                'i_referensi'      => $ireferensiop,
                'id_customer'      => $icustomer,
                'id_area'          => $idarea,
                'id_spb_referensi' => $ireferensi,
                'v_diskon'         => $ndiskontotal,
                'v_kotor'          => $nkotor,
                'v_ppn'            => $vppn,
                'v_bersih'         => $nbersih,
                'v_dpp'            => $vdpp,
                'id_harga_kode'    => $idkodeharga,
                'e_remark'         => $eremark,
                'e_jenis_spb'      => $ejenisspb,
                'd_entry'          => current_datetime(),
            );
            $this->db->insert('tm_spb_distributor', $data);
        }
    }

    /*----------  INSERT DETAIL SPB TURUNAN  ----------*/

    public function insertdetailspb($idbaru, $iproduct, $nsisa, $vprice, $_1ndiskon, $_2ndiskon, $_3ndiskon, $_1vdiskon, $_2vdiskon, $_3vdiskon, $vdiskonadd, $vtdiskon, $vtotal, $vtotalbersih, $edesc, $nsisab, $ijenis)
    {
        if ($ijenis == '1') {
            $data = array(
                'id_company'        => $this->company,
                'id_document'       => $idbaru,
                'id_product'        => $iproduct,
                'n_quantity'        => $nsisab,
                'n_quantity_sisa'   => $nsisab,
                'v_price'           => $vprice,
                'n_diskon1'         => $_1ndiskon,
                'n_diskon2'         => $_2ndiskon,
                'n_diskon3'         => $_3ndiskon,
                'v_diskon1'         => $_1vdiskon,
                'v_diskon2'         => $_2vdiskon,
                'v_diskon3'         => $_3vdiskon,
                'v_diskontambahan'  => $vdiskonadd,
                'v_total_discount'  => $vtdiskon,
                'v_total'           => $vtotal,
                'e_remark'          => $edesc,
            );
            $this->db->insert('tm_spb_item', $data);
        } else if ($ijenis == '2') {
            $data = array(
                'id_company'        => $this->company,
                'id_document'       => $idbaru,
                'id_product'        => $iproduct,
                'n_quantity'        => $nsisab,
                'n_quantity_sisa'   => $nsisab,
                'v_price'           => $vprice,
                'n_diskon1'         => $_1ndiskon,
                'n_diskon2'         => $_2ndiskon,
                'n_diskon3'         => $_3ndiskon,
                'v_diskon1'         => $_1vdiskon,
                'v_diskon2'         => $_2vdiskon,
                'v_diskon3'         => $_3vdiskon,
                'v_diskontambahan'  => $vdiskonadd,
                'v_total_discount'  => $vtdiskon,
                'v_total'           => $vtotal,
                'e_remark'          => $edesc,
            );
            $this->db->insert('tm_spb_ds_item', $data);
        } else if ($ijenis == '3') {
            $data = array(
                'id_company'        => $this->company,
                'id_document'       => $idbaru,
                'id_product'        => $iproduct,
                'n_quantity'        => $nsisab,
                'n_quantity_sisa'   => $nsisab,
                'v_price'           => $vprice,
                'n_diskon1'         => $_1ndiskon,
                'n_diskon2'         => $_2ndiskon,
                'n_diskon3'         => $_3ndiskon,
                'v_diskon1'         => $_1vdiskon,
                'v_diskon2'         => $_2vdiskon,
                'v_diskon3'         => $_3vdiskon,
                'v_diskon_tambahan' => $vdiskonadd,
                'v_diskon_total'    => $vtdiskon,
                'v_total'           => $vtotal,
                'e_remark'          => $edesc,
            );
            $this->db->insert('tm_spb_distributor_item', $data);
        }
    }

    /*----------  UPDATE HEADER SPB  ----------*/

    public function updateheaderspbold($ireferensi, $dreferensi, $nkotorold, $nbersihold, $vdppold, $vppnold, $ijenis)
    {
        if ($ijenis == '1') {
            $data = array(
                'v_kotor'           => $nkotorold,
                'v_ppn'             => $vppnold,
                'v_bersih'          => $nbersihold,
                'v_dpp'             => $vdppold,
            );
            $this->db->where('id', $ireferensi);
            $this->db->where('id_company', $this->company);
            $this->db->update('tm_spb', $data);
        } else if ($ijenis == '2') {
            $data = array(
                'v_kotor'           => $nkotorold,
                'v_ppn'             => $vppnold,
                'v_bersih'          => $nbersihold,
                'v_dpp'             => $vdppold,
            );
            $this->db->where('id', $ireferensi);
            $this->db->where('id_company', $this->company);
            $this->db->update('tm_spb_ds', $data);
        } else if ($ijenis == '3') {
            $data = array(
                'v_kotor'           => $nkotorold,
                'v_ppn'             => $vppnold,
                'v_bersih'          => $nbersihold,
                'v_dpp'             => $vdppold,
            );
            $this->db->where('id', $ireferensi);
            $this->db->where('id_company', $this->company);
            $this->db->update('tm_spb_distributor', $data);
        }
    }

    /*----------  UPDATE DETAIL SPB  ----------*/

    public function updatedetailspbold($ireferensi, $iproduct, $nquantity, $vtotalold, $vtotalbersihold, $ijenis)
    {
        if ($ijenis == '1') {
            $data = array(
                'n_quantity'        => $nquantity,
                'n_quantity_sisa'   => 0,
                'v_total'           => $vtotalold,
            );
            $this->db->where('id_document', $ireferensi);
            $this->db->where('id_product', $iproduct);
            $this->db->where('id_company', $this->company);
            $this->db->update('tm_spb_item', $data);
        } else if ($ijenis == '2') {
            $data = array(
                'n_quantity'        => $nquantity,
                'n_quantity_sisa'   => 0,
                'v_total'           => $vtotalold,
            );
            $this->db->where('id_document', $ireferensi);
            $this->db->where('id_product', $iproduct);
            $this->db->where('id_company', $this->company);
            $this->db->update('tm_spb_ds_item', $data);
        } else if ($ijenis == '3') {
            $data = array(
                'n_quantity'        => $nquantity,
                'n_quantity_sisa'   => 0,
                'v_total'           => $vtotalold,
            );
            $this->db->where('id_document', $ireferensi);
            $this->db->where('id_product', $iproduct);
            $this->db->where('id_company', $this->company);
            $this->db->update('tm_spb_distributor_item', $data);
        }
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function updatestatus($id)
    {
        $data = array(
            'i_status'  => '6',
            'e_approve' => $this->username,
            'd_approve' => date('Y-m-d'),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->company);
        $this->db->update('tm_sj', $data);
    }

    /*----------  UPDATE STATUS PRINT  ----------*/

    public function updateprint($id)
    {
        $this->db->query("
            UPDATE tm_sj SET n_print = n_print + 1 WHERE id = $id
        ", FALSE);
    }
}
/* End of file Mmaster.php */