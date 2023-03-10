<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DAFTAR DATA SPB  ----------*/    

    public function data($folder,$i_menu,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_alokasi_kas_bank a
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company)
            ", FALSE);
        if ($this->departement=='1') {
            $bagian = "";
        }else{
            if ($cek->num_rows()>0) {                
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 0 AS NO,
                a.id,
                a.id_jenis_alokasi,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                b.e_partner_name,
                CASE 
                    WHEN a.id_jenis_alokasi = 1 THEN f.i_document
                    WHEN a.id_jenis_alokasi = 2 THEN g.i_document
                    WHEN a.id_jenis_alokasi = 3 THEN h.i_document
                END AS i_referensi,
                c.e_jenis_name,
                a.v_jumlah,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_alokasi_kas_bank a
            INNER JOIN (
                SELECT 
		            id as id_partner,
		            e_customer_name as e_partner_name,
		            id_company,
		            'customer' as group_partner
	            FROM 
	            	tr_customer 
	            UNION ALL 
	            SELECT 
	            	id as id_partner, 
	            	e_bagian_name as e_partner_name,
	            	id_company,
	            	'bagian' as group_partner
	            FROM 
	            	tr_bagian 
	            UNION ALL 
	            SELECT 
	            	id as id_partner, 
	            	e_nama_karyawan as e_partner_name,
	            	id_company,
	            	'karyawan' as group_partner
	            FROM 
	            	tr_karyawan 
	            UNION ALL 
	            SELECT 
	            	id as ida_partner,
	            	e_supplier_name as e_partner_name,
	            	id_company,
	            	'supplier' as group_partner
	            FROM 
	            	tr_supplier
            ) b ON (a.id_customer = b.id_partner
                AND a.group_partner = b.group_partner 
                AND a.id_company = b.id_company)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tr_jenis_alokasi_piutang c ON 
                (c.id = a.id_jenis_alokasi)
            LEFT JOIN tm_kas_masuk_piutang f ON
                (f.id = a.id_referensi
                AND a.id_company = f.id_company)
            LEFT JOIN tm_giro_cair g ON
                (g.id = a.id_referensi
                AND a.id_company = g.id_company)
            LEFT JOIN tm_kas_konversi_masuk h ON
                (h.id = a.id_referensi
                AND a.id_company = h.id_company)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company
                $and
                $bagian
            ORDER BY
                a.id ASC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('v_jumlah', function ($data) {
            return number_format($data['v_jumlah']);
        });

        $datatables->add('action', function ($data) {
            $id          = trim($data['id']);
            $idjenis     = trim($data['id_jenis_alokasi']);
            $i_menu      = $data['i_menu'];
            $folder      = $data['folder'];
            $i_status    = $data['i_status'];
            $dfrom       = $data['dfrom'];
            $dto         = $data['dto'];
            $data        = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$idjenis\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$idjenis\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && ($i_status=='2')) {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$idjenis\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
            }   

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_jenis_alokasi');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        return $datatables->generate();
    }

    /*----------  DAFTAR DATA KN  ----------*/    

    public function datareferensi($folder,$i_menu,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                id,
                id_item,
                i_referensi,
                d_referensi,
                e_partner_name,
                id_jenis_alokasi,
                e_jenis_name,
                e_kas_name,
                jumlah,
                sisa,
                e_remark,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                (
                SELECT
                    a.id,
                    b.id AS id_item,
                    a.i_document AS i_referensi,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_referensi,
                    c.e_partner_name,
                    1 AS id_jenis_alokasi,
                    e.e_jenis_name,
                    d.e_kas_name,
                    b.n_nilai AS jumlah,
                    b.n_sisa AS sisa,
                    b.e_remark
                FROM
                    tm_kas_masuk_piutang a
                INNER JOIN tm_kas_masuk_piutang_item b ON
                    (b.id_document = a.id)
                INNER JOIN (
                    SELECT 
                        id as id_partner,
                        e_customer_name as e_partner_name,
                        id_company
                    FROM 
                        tr_customer 
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        e_bagian_name as e_partner_name,
                        id_company
                    FROM 
                        tr_bagian 
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        e_nama_karyawan as e_partner_name,
                        id_company
                    FROM 
                        tr_karyawan
                    UNION ALL 
                    SELECT 
                        id as id_partner, 
                        e_supplier_name as e_partner_name, 
                        id_company 
                    FROM 
                        tr_supplier 
                ) c on (b.id_partner = c.id_partner
                AND b.id_company = c.id_company)
                INNER JOIN tr_kas_bank d ON
                    (d.id = a.id_kas_bank
                    AND a.id_company = d.id_company)
                INNER JOIN tr_jenis_alokasi_piutang e ON
                    (e.id = 1)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->company'
                    AND b.n_sisa > 0
                    $and
            UNION ALL
                SELECT
                    a.id,
                    b.id AS id_item,
                    a.i_document AS i_referensi,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_referensi,
                    c.e_customer_name AS e_customer,
                    2 AS id_jenis_alokasi,
                    e.e_jenis_name,
                    d.e_kas_name,
                    b.v_jumlah AS jumlah,
                    b.v_sisa AS sisa,
                    a.e_remark
                FROM
                    tm_giro_cair a
                INNER JOIN tm_giro_cair_item b ON
                    (b.id_document = a.id)
                INNER JOIN tr_customer c ON
                    (c.id = a.id_customer
                    AND a.id_company = c.id_company)
                INNER JOIN tr_kas_bank d ON
                    (d.id = a.id_kas_bank
                    AND a.id_company = d.id_company)
                INNER JOIN tr_jenis_alokasi_piutang e ON
                    (e.id = 2)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->company'
                    AND b.v_sisa > 0
                    $and
            UNION ALL
                SELECT
                    a.id,
                    b.id AS id_item,
                    a.i_document AS i_referensi,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_referensi,
                    c.e_partner_name,
                    3 AS id_jenis_alokasi,
                    e.e_jenis_name,
                    d.e_kas_name,
                    b.n_nilai AS jumlah,
                    b.n_sisa AS sisa,
                    a.e_remark
                FROM
                    tm_kas_konversi_masuk a
                INNER JOIN tm_kas_konversi_masuk_item b ON
                    (b.id_document = a.id)
                INNER JOIN (
                    SELECT 
                        id as id_partner,
                        e_customer_name as e_partner_name,
                        id_company,
                        'customer' as group_partner 
                    FROM 
                        tr_customer 
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        e_bagian_name as e_partner_name,
                        id_company,
                        'bagian' as group_partner
                    FROM 
                        tr_bagian 
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        e_nama_karyawan as e_partner_name,
                        id_company,
                        'karyawan' as group_partner
                    FROM 
                        tr_karyawan
                    UNION ALL 
                    SELECT 
                        id as id_partner, 
                        e_supplier_name as e_partner_name, 
                        id_company,
                        'supplier' as group_partner 
                    FROM 
                        tr_supplier 
                ) c on (b.id_customer = c.id_partner
                AND b.id_company = c.id_company
                AND a.e_partner_type = c.group_partner)
                INNER JOIN tr_kas_bank d ON
                    (d.id = a.id_kas_bank
                    AND a.id_company = d.id_company)
                INNER JOIN tr_jenis_alokasi_piutang e ON
                    (e.id = 3)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->company'
                    AND b.n_sisa > 0
                    $and) AS x
            ORDER BY
                4
        ", FALSE);

        $datatables->edit('jumlah', function ($data) {
            return number_format($data['jumlah']);
        });

        $datatables->edit('sisa', function ($data) {
            return number_format($data['sisa']);
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $iditem   = trim($data['id_item']);
            $idjenis  = trim($data['id_jenis_alokasi']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            
            if(check_role($i_menu, 1)){
                $data .= "<a href=\"#\" title='Tambah Data Alokasi' onclick='show(\"$folder/cform/tambah/$id/$dfrom/$dto/$idjenis/$iditem\",\"#main\"); return false;'><i class='ti-new-window'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_item');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_jenis_alokasi');
        return $datatables->generate();
    }

    /*----------  DATA HEADER KN  ----------*/
    /*
    * Bercocok Tanam Diprogram
    * Jenis Alokasi{
        Jika 1 = Transfer
        Jika 2 = Giro
        Jika 3 = Tunai
    }
    */
    
    public function getdataref($id,$idjenis,$iditem)
    {
        if ($idjenis==1) {  
            /*----------  Baca Transfer  ----------*/                                
            return $this->db->query("
                SELECT
                    a.id,
                    a.i_document,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                    b.n_sisa AS v_sisa,
                    c.id_partner,
                    c.i_partner,
                    c.e_partner_name,
                    c.e_partner_address,
                    c.e_city_name,
                    c.group_partner
                FROM
                    tm_kas_masuk_piutang a
                INNER JOIN tm_kas_masuk_piutang_item b ON
                    (b.id_document = a.id)
                INNER JOIN (
                    SELECT 
                        a.id as id_partner,
                        a.i_customer as i_partner,
                        a.e_customer_name as e_partner_name,
                        a.e_customer_address as e_partner_address,
                        b.e_city_name,
                        a.id_company,
                        'customer' as group_partner
                    FROM 
                        tr_customer a
                        INNER JOIN tr_city b ON (
                            a.id_city = b.id 
                        )
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        i_bagian as i_partner,
                        e_bagian_name as e_partner_name,
                        i_lokasi as e_partner_address,
                        '' as e_city_name,
                        id_company,
                        'bagian' as group_partner
                    FROM 
                        tr_bagian 
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        e_nik as i_partner,
                        e_nama_karyawan as e_partner_name,
                        e_alamat as e_partner_address,
                        e_kota as e_city_name,
                        id_company,
                        'karyawan' as group_partner
                    FROM 
                        tr_karyawan
                    UNION ALL 
                    SELECT 
                        id as id_partner, 
                        i_supplier as i_partner,
                        e_supplier_name as e_partner_name, 
                        e_supplier_address as e_partner_address,
                        e_supplier_city as e_city_name,
                        id_company,
                        'supplier' as group_partner
                    FROM 
                        tr_supplier 
                ) c on (b.id_partner = c.id_partner
                AND b.id_company = c.id_company)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->company'
                    AND b.n_sisa > 0
                    AND a.id = '$id'
                    AND b.id = '$iditem'
                ", FALSE);
        }elseif ($idjenis==2) {
            /*----------  Baca Giro  ----------*/
            return $this->db->query("
                SELECT
                    a.id,
                    a.i_document,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                    b.v_sisa,
                    a.id_customer as id_partner,
                    c.i_customer as i_partner,
                    c.e_customer_name as e_partner_name,
                    c.e_customer_address as e_partner_address,
                    c.id_city,
                    d.e_city_name,
                    'customer' as group_partner
                FROM
                    tm_giro_cair a
                INNER JOIN tm_giro_cair_item b ON
                    (b.id_document = a.id)
                INNER JOIN tr_customer c ON
                    (c.id = a.id_customer
                    AND a.id_company = c.id_company)
                INNER JOIN tr_city d ON
                    (d.id = c.id_city)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->company'
                    AND b.v_sisa > 0
                    AND a.id = '$id'
                    AND b.id = '$iditem'
                ", FALSE);
        }elseif ($idjenis==3) {
            /*----------  Baca Tunai (Konversi)  ----------*/
            return $this->db->query("
                SELECT
                    a.id,
                    a.i_document,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                    b.n_sisa AS v_sisa,
                    c.id_partner,
                    c.i_partner,
                    c.e_partner_name,
                    c.e_partner_address,
                    c.e_city_name,
                    c.group_partner
                FROM
                    tm_kas_konversi_masuk a
                INNER JOIN tm_kas_konversi_masuk_item b ON
                    (b.id_document = a.id)
                INNER JOIN (
                    SELECT 
                        a.id as id_partner,
                        a.i_customer as i_partner,
                        a.e_customer_name as e_partner_name,
                        a.e_customer_address as e_partner_address,
                        b.e_city_name,
                        a.id_company,
                        'customer' as group_partner
                    FROM 
                        tr_customer a
                        INNER JOIN tr_city b ON (
                            a.id_city = b.id 
                        )
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        i_bagian as i_partner,
                        e_bagian_name as e_partner_name,
                        i_lokasi as e_partner_address,
                        '' as e_city_name,
                        id_company,
                        'bagian' as group_partner
                    FROM 
                        tr_bagian 
                    UNION ALL 
                    SELECT
                        id as id_partner, 
                        e_nik as i_partner,
                        e_nama_karyawan as e_partner_name,
                        e_alamat as e_partner_address,
                        e_kota as e_city_name,
                        id_company,
                        'karyawan' as group_partner
                    FROM 
                        tr_karyawan
                    UNION ALL 
                    SELECT 
                        id as id_partner, 
                        i_supplier as i_partner,
                        e_supplier_name as e_partner_name, 
                        e_supplier_address as e_partner_address,
                        e_supplier_city as e_city_name,
                        id_company,
                        'supplier' as group_partner
                    FROM 
                        tr_supplier 
                ) c on (b.id_customer = c.id_partner
                AND b.id_company = c.id_company)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->company'
                    AND b.n_sisa > 0
                    AND a.id = '$id'
                    AND b.id = '$iditem'
                ", FALSE);
        }
    }

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/    

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/
    
    public function runningnumber($thbl,$tahun,$ibagian) 
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode
            FROM tm_alokasi_kas_bank
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'AKB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_alokasi_kas_bank
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 3) = '$kode'
                AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
            ", FALSE);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 6){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "000001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    /*----------  CEK DOKUMEN SUDAH ADA  ----------*/

    public function cek_kode($kode,$ibagian) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_alokasi_kas_bank');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI REFERENSI  ----------*/
    
    public function referensi($cari,$idcustomer)
    {
        return $this->db->query("
            SELECT 
                x.id,
                x.i_document,
                x.d_document,
                x.id_partner,
                x.i_status,
                x.v_sisa,
                x.id_company,
                x.groupfaktur
            FROM 
            (
                SELECT DISTINCT
                    id,
                    i_document,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                    id_customer as id_partner,
                    i_status, 
                    v_sisa,
                    id_company,
                    'Faktur Penjualan' AS groupfaktur
                FROM 
                    tm_nota_penjualan 
                UNION ALL
                SELECT DISTINCT 
                    id,
                    i_document,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document, 
                    id_partner, 
                    i_status,
                    v_sisa,
                    id_company,
                    'Faktur Penjualan Bahan Baku' AS groupfaktur
                FROM 
                    tm_nota_penjualan_bb
            ) AS x
            WHERE
                x.i_status = '6'
                AND x.id_partner = '$idcustomer'
                AND x.v_sisa > 0
                AND x.i_document ILIKE '%$cari%'
                AND x.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY 
                2
        ", FALSE);

        // return $this->db->query("
        //     SELECT
        //         DISTINCT id,
        //         i_document,
        //         to_char(d_document, 'dd-mm-yyyy') AS d_document
        //     FROM
        //         tm_nota_penjtualan
        //     WHERE
        //         i_status = '6'
        //         AND id_customer = '$idcustomer'
        //         AND v_sisa > 0
        //         AND (i_document ILIKE '%$cari%')
        //         AND id_company = '$this->company'
        //     ORDER BY
        //         3,
        //         2
        //     ", FALSE);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/    

    public function getdetailref($idnota,$idcustomer)
    {
        return $this->db->query("
            SELECT 
                x.id,
                x.i_document,
                x.d_document,
                x.id_partner,
                x.i_status,
                x.v_sisa,
                x.id_company,
                x.groupfaktur
            FROM 
            (
                SELECT DISTINCT
                    id,
                    i_document,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                    id_customer as id_partner,
                    i_status, 
                    v_sisa,
                    id_company,
                    'Faktur Penjualan' AS groupfaktur
                FROM 
                    tm_nota_penjualan 
                UNION ALL
                SELECT DISTINCT 
                    id,
                    i_document,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document, 
                    id_partner, 
                    i_status,
                    v_sisa,
                    id_company,
                    'Faktur Penjualan Bahan Baku' AS groupfaktur
                FROM 
                    tm_nota_penjualan_bb
            ) AS x
            WHERE
                x.i_status = '6'
                AND x.id_partner = '$idcustomer'
                AND x.v_sisa > 0
                AND x.id = '$idnota'
                AND x.id_company = '".$this->session->userdata('id_company')."'
        ", FALSE);
        // return $this->db->query("
        //     SELECT
        //         DISTINCT id,
        //         i_document,
        //         to_char(d_document, 'dd-mm-yyyy') AS d_document,
        //         v_sisa
        //     FROM
        //         tm_nota_penjualan
        //     WHERE
        //         i_status = '6'
        //         AND id_customer = '$idcustomer'
        //         AND v_sisa > 0
        //         AND id = $idnota
        //         AND id_company = '$this->company'
        //     ", FALSE);
    }

    /*----------  RUNNING ID  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_alokasi_kas_bank');
        return $this->db->get()->row()->id+1;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomer,$idreferensi,$idreferensiitem,$idjenis,$vjumlah,$vlebih,$eremarkh, $grouppartner)
    {
        $data = array(
            'id'                  => $id,
            'id_company'          => $this->company,
            'i_document'          => $idocument,
            'd_document'          => $ddocument,
            'i_bagian'            => $ibagian,
            'id_customer'         => $idcustomer,
            'e_customer_name'     => $ecustomer,
            'id_referensi'        => $idreferensi,
            'id_referensi_detail' => $idreferensiitem,
            'id_jenis_alokasi'    => $idjenis,
            'v_jumlah'            => $vjumlah,
            'v_lebih'             => $vlebih,
            'e_remark'            => $eremarkh,
            'group_partner'       => $grouppartner,
        );
        $this->db->insert('tm_alokasi_kas_bank', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/
    
    public function insertdetail($id,$idreferensi,$idreferensiitem,$idnota,$vbayar,$vsisa,$eremark,$groupfaktur)
    {
        $data = array(
            'id_company'          => $this->company,
            'id_document'         => $id,
            'id_referensi'        => $idreferensi,
            'id_referensi_detail' => $idreferensiitem,
            'id_referensi_nota'   => $idnota,
            'v_jumlah'            => $vbayar,
            'v_sisa'              => $vsisa,
            'e_remark'            => $eremark,
            'group_faktur'        => $groupfaktur
        );
        $this->db->insert('tm_alokasi_kas_bank_item', $data);
    }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/
    
    public function editheader($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.id_referensi,
                a.id_referensi_detail,
                a.id_jenis_alokasi,
                a.i_bagian,
                e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                CASE
                    WHEN a.id_jenis_alokasi = 1 THEN f.i_document
                    WHEN a.id_jenis_alokasi = 2 THEN g.i_document
                    WHEN a.id_jenis_alokasi = 3 THEN h.i_document
                END AS i_referensi,
                CASE
                    WHEN a.id_jenis_alokasi = 1 THEN to_char(f.d_document, 'dd-mm-yyyy')
                    WHEN a.id_jenis_alokasi = 2 THEN to_char(g.d_document, 'dd-mm-yyyy')
                    WHEN a.id_jenis_alokasi = 3 THEN to_char(h.d_document, 'dd-mm-yyyy')
                END AS d_referensi,
                a.id_customer,
                c.i_partner,
                c.e_partner_name,
                a.e_remark,
                a.i_status,
                a.v_jumlah,
                c.e_partner_address,
                c.e_city_name
            FROM
                tm_alokasi_kas_bank a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN (
                SELECT 
                    a.id AS id_partner,
                    a.i_customer AS i_partner,
                    a.e_customer_name AS e_partner_name,
                    a.e_customer_address AS e_partner_address,
                    b.e_city_name,
                    a.id_company
                FROM    
                    tr_customer a 
                    INNER JOIN tr_city b ON (a.id_city = b.id)
                UNION ALL 
                SELECT 
                    id AS id_partner, 
                    i_bagian AS i_partner,
                    e_bagian_name AS e_partner_name,
                    i_lokasi As e_partner_address,
                    '' AS e_city_name,
                    id_company
                FROM 
                    tr_bagian 
                UNION ALL
                SELECT 
                    id AS id_partner,
                    e_nik AS i_partner, 
                    e_nama_karyawan AS e_partner_name,
                    e_alamat AS e_partner_address,
                    e_kota AS e_city_name,
                    id_company
                FROM 
                    tr_karyawan
                UNION ALL 
                SELECT 
                    id AS id_partner,
                    i_supplier AS i_partner,
                    e_supplier_name AS e_partner_name,
                    e_supplier_address AS e_partner_address,
                    e_supplier_city AS e_city_name,
                    id_company
                FROM 
                    tr_supplier 
            ) c ON (a.id_customer = c.id_partner
            AND a.id_company = c.id_company)
            LEFT JOIN tm_kas_masuk_piutang f ON
                (f.id = a.id_referensi
                AND a.id_company = f.id_company)
            LEFT JOIN tm_giro_cair g ON
                (g.id = a.id_referensi
                AND a.id_company = g.id_company)
            LEFT JOIN tm_kas_konversi_masuk h ON
                (h.id = a.id_referensi
                AND a.id_company = h.id_company)
            WHERE
                a.id = $id
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM  ----------*/
    
    public function edititem($id)
    {
        return $this->db->query("
            SELECT DISTINCT
                a.*,
                b.v_sisa_nota,
                b.v_nota,
                b.i_nota, 
                b.id_nota, 
                b.d_nota,
                b.group_faktur
            FROM
                tm_alokasi_kas_bank_item a
                INNER JOIN tm_alokasi_kas_bank c ON (
                    a.id_document = c.id
                    AND a.id_company = c.id_company
                )
                INNER JOIN (
                    SELECT 
                        id AS id_nota, 
                        i_document AS i_nota,
                        to_char(d_document, 'dd-mm-yyyy') AS d_nota,
                        id_company,
                        v_sisa AS v_sisa_nota,
                        v_bersih AS v_nota,
                        'Faktur Penjualan' AS group_faktur,
                        id_customer as id_partner
                    FROM 
                        tm_nota_penjualan
                    UNION ALL
                    SELECT 
                        id AS id_nota,
                        i_document AS i_nota, 
                        to_char(d_document, 'dd-mm-yyyy') AS d_nota,
                        id_company,
                        v_sisa AS v_sisa_nota,
                        v_bersih AS v_nota,
                        'Faktur Penjualan Bahan Baku' AS group_faktur,
                        id_partner
                    FROM 
                        tm_nota_penjualan_bb
                ) b ON (a.id_referensi_nota = b.id_nota
                AND a.group_faktur = b.group_faktur
                AND c.id_customer = b.id_partner
                AND a.id_company = b.id_company)
            WHERE 
                a.id_document = '$id'
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY 
                a.id
        ", FALSE);
        // return $this->db->query("
        //     SELECT
        //         DISTINCT a.*,
        //         b.v_bersih AS v_sisa_nota,
        //         b.i_document AS i_nota,
        //         b.id AS id_nota,
        //         to_char(b.d_document, 'dd-mm-yyyy') AS d_nota
        //     FROM
        //         tm_alokasi_kas_bsank_item a
        //     INNER JOIN tm_nota_penjualan b ON
        //         (b.id = a.id_referensi_nota
        //         AND a.id_company = b.id_company)
        //     WHERE
        //         a.id_document = $id
        //     ORDER BY
        //         a.id
        // ", FALSE);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_alokasi_kas_bank');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  DELETE DETAIL PAS EDIT  ----------*/
    
    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_alokasi_kas_bank_item');
    }

    /*----------  UPDATE HEADER  ----------*/
    
    public function updateheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomer,$idreferensi,$idreferensiitem,$idjenis,$vjumlah,$vlebih,$eremarkh)
    {
        $data = array(
            'id_company'          => $this->company,
            'i_document'          => $idocument,
            'd_document'          => $ddocument,
            'i_bagian'            => $ibagian,
            'id_customer'         => $idcustomer,
            'e_customer_name'     => $ecustomer,
            'id_referensi'        => $idreferensi,
            'id_referensi_detail' => $idreferensiitem,
            'id_jenis_alokasi'    => $idjenis,
            'v_jumlah'            => $vjumlah,
            'v_lebih'             => $vlebih,
            'e_remark'            => $eremarkh,
            'd_update'            => current_datetime()
        );
        $this->db->where('id', $id);
        $this->db->update('tm_alokasi_kas_bank', $data);
    }

    /*----------  UPDATE SISA KN  ----------*/

    public function updatesisa($id)
    {
        $query = $this->db->query("
            SELECT 
                a.id_referensi, 
                a.id_referensi_detail,
                b.id_jenis_alokasi, 
                sum(a.v_jumlah) AS v_jumlah
            FROM 
                tm_alokasi_kas_bank_item a, 
                tm_alokasi_kas_bank b
            WHERE 
                a.id_document = b.id
                AND a.id_document = '$id'
                AND a.id_company = '$this->company'
            GROUP BY
                1,2,3
            ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                if ($key->id_jenis_alokasi==1) {
                    $nsisa = $this->db->query("
                        SELECT
                            n_sisa
                        FROM
                            tm_kas_masuk_piutang
                        WHERE
                            id              = '$key->id_referensi'
                            AND id_company  = '$this->company'
                            AND n_sisa      >= '$key->v_jumlah'
                        ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                            UPDATE
                                tm_kas_masuk_piutang
                            SET
                                n_sisa = n_sisa - $key->v_jumlah
                            WHERE
                                id              = '$key->id_referensi'
                                AND id_company  = '$this->company'
                                AND n_sisa      >= '$key->v_jumlah'
                            ", FALSE);
                        $this->db->query("
                            UPDATE
                                tm_kas_masuk_piutang_item
                            SET
                                n_sisa = n_sisa - $key->v_jumlah
                            WHERE
                                id_document     = '$key->id_referensi'
                                AND id          = '$key->id_referensi_detail'
                                AND id_company  = '$this->company'
                                AND n_sisa      >= '$key->v_jumlah'
                            ", FALSE);
                    } else {
                        die();
                    }
                }elseif ($key->id_jenis_alokasi==2) {
                    $nsisa = $this->db->query("
                        SELECT
                            v_sisa
                        FROM
                            tm_giro_cair_item
                        WHERE
                            id              = '$key->id_referensi_detail'
                            AND id_document = '$key->id_referensi'
                            AND id_company  = '$this->company'
                            AND v_sisa      >= '$key->v_jumlah'
                        ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                            UPDATE
                                tm_giro_cair_item
                            SET
                                v_sisa = v_sisa - $key->v_jumlah
                            WHERE
                                id              = '$key->id_referensi_detail'
                                AND id_document = '$key->id_referensi'
                                AND id_company  = '$this->company'
                                AND v_sisa      >= '$key->v_jumlah'
                            ", FALSE);
                    } else {
                        die();
                    }
                }elseif ($key->id_jenis_alokasi==3) {
                    $nsisa = $this->db->query("
                        SELECT
                            n_sisa
                        FROM
                            tm_kas_konversi_masuk_item
                        WHERE
                            id              = '$key->id_referensi_detail'
                            AND id_document = '$key->id_referensi'
                            AND id_company  = '$this->company'
                            AND n_sisa      >= '$key->v_jumlah'
                        ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                            UPDATE
                                tm_kas_konversi_masuk
                            SET
                                n_sisa_document = n_sisa_document - $key->v_jumlah
                            WHERE
                                id              = '$key->id_referensi'
                                AND id_company  = '$this->company'
                                AND n_sisa_document >= '$key->v_jumlah'
                            ", FALSE);
                        $this->db->query("
                            UPDATE
                                tm_kas_konversi_masuk_item
                            SET
                                n_sisa = n_sisa - $key->v_jumlah
                            WHERE
                                id_document     = '$key->id_referensi'
                                AND id          = '$key->id_referensi_detail'
                                AND id_company  = '$this->company'
                                AND n_sisa      >= '$key->v_jumlah'
                            ", FALSE);
                    } else {
                        die();
                    }
                }
            }
        }
    }    

    /*----------  UPDATE SISA NOTA  ----------*/

    public function updatesisanota($id)
    {
        $query = $this->db->query("
            SELECT 
                id_referensi_nota, 
                v_jumlah,
                group_faktur
            FROM 
                tm_alokasi_kas_bank_item
            WHERE 
                id_document = '$id'
                AND id_company = '$this->company'
            ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                if($key->group_faktur == 'Faktur Penjualan'){
                    $nsisa = $this->db->query("
                        SELECT
                            v_sisa
                        FROM
                            tm_nota_penjualan
                        WHERE
                            id              = '$key->id_referensi_nota'
                            AND id_company  = '$this->company'
                            AND v_sisa      >= '$key->v_jumlah'
                    ", FALSE);
                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                            UPDATE
                                tm_nota_penjualan
                            SET
                                v_sisa = v_sisa - $key->v_jumlah
                            WHERE
                                id              = '$key->id_referensi_nota'
                                AND id_company  = '$this->company'
                                AND v_sisa      >= '$key->v_jumlah'
                            ", FALSE);
                    } else {
                        die();
                    }
                }else if($key->group_faktur ==  'Faktur Penjualan Bahan Baku'){
                    $nsisa = $this->db->query("
                        SELECT
                            v_sisa
                        FROM
                            tm_nota_penjualan_bb
                        WHERE
                            id              = '$key->id_referensi_nota'
                            AND id_company  = '$this->company'
                            AND v_sisa      >= '$key->v_jumlah'
                    ", FALSE);
                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                            UPDATE
                                tm_nota_penjualan_bb
                            SET
                                v_sisa = v_sisa - $key->v_jumlah
                            WHERE
                                id              = '$key->id_referensi_nota'
                                AND id_company  = '$this->company'
                                AND v_sisa      >= '$key->v_jumlah'
                            ", FALSE);
                    } else {
                        die();
                    }
                }   
            }
        }
    }    

    /*----------  RUBAH STATUS  ----------*/
    
    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
    
    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_alokasi_kas_bank', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/
}
/* End of file Mmaster.php */