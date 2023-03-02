<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DAFTAR DATA SPB  ----------*/    

    public function data($folder,$i_menu,$dfrom,$dto)
    {
        $id_company = $this->session->userdata('id_company');

        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $sql_in_bagian = "SELECT i_bagian
                            FROM tr_departement_cover
                            WHERE i_departement = '$this->departement'
                                AND username = '$this->username'
                                AND id_company = $id_company";

        $cek = $this->db->query("
            SELECT tb.i_bagian
            FROM tm_alokasi_kas a
            INNER JOIN tr_bagian tb ON tb.id = a.id_bagian
            WHERE i_status <> '5'
                AND a.id_company = $id_company
                AND i_bagian IN ($sql_in_bagian)"
        , FALSE);
        if ($this->departement=='1') {
            $bagian = "";
        }else{
            if ($cek->num_rows()>0) {                
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND a.i_bagian IN ($sql_in_bagian) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);

        $sql = "SELECT DISTINCT 
                tap.i_alokasi AS id,
                0 AS NO,
                tap.d_alokasi,
                tap.i_alokasi_id,
                tc.e_customer_name,
                ta.e_area,
                tr.i_rv_id,
                tap.e_bank_name,
                tap.v_jumlah,
                tap.v_lebih,                
                tap.i_status,
                tsd.e_status_name,
                tsd.label_color,
                tma.i_level,
                tl.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto 
                FROM tm_alokasi_piutang tap
                INNER JOIN tm_alokasi_piutang_item tapi ON tapi.i_alokasi = tap.i_alokasi
                INNER JOIN tr_status_document tsd ON tsd.i_status = tap.i_status 
                LEFT JOIN tr_menu_approve tma ON (tma.n_urut = tap.i_approve_urutan AND tma.i_menu = '$i_menu')
                LEFT JOIN public.tr_level tl ON tl.i_level = tma.i_level
                LEFT JOIN tr_customer tc ON tc.id = tap.id_customer
                LEFT JOIN tr_area ta ON ta.id = tap.id_area
                LEFT JOIN tm_rv tr ON tr.i_rv = tap.i_rv
                WHERE tap.id_company = '$id_company' AND
                    tap.i_status <> '5'AND
                    tap.d_alokasi BETWEEN to_date('$dfrom','yyyy-mm-dd') AND to_date('$dto','yyyy-mm-dd')
                    $bagian
                ORDER BY tap.i_alokasi DESC";

        // var_dump($sql); die();
        
        $datatables->query($sql, FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('v_jumlah', function ($data) {
            return "Rp. " . number_format($data['v_jumlah'], 2, ",", ".");
        });

        $datatables->edit('v_lebih', function ($data) {
            return "Rp. " . number_format($data['v_lebih'], 2, ",", ".");
        });

        $datatables->add('action', function ($data) {
            $id          = trim($data['id']);
            // $idjenis     = trim($data['id_jenis_alokasi']);
            $i_menu      = $data['i_menu'];
            $folder      = $data['folder'];
            $i_status    = $data['i_status'];
            $dfrom       = $data['dfrom'];
            $dto         = $data['dto'];
            $data        = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && ($i_status=='2')) {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
            }   

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        // $datatables->hide('id_jenis_alokasi');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_status');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
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

    public function get_data_referensi($dfrom, $dto, $folder, $id_menu)
    {
        $id_company = $this->session->userdata('id_company');
        $dfrom  = date('Y-m-d', strtotime($dfrom));
        $dto    = date('Y-m-d', strtotime($dto));          

        $datatables = new Datatables(new CodeigniterAdapter);

        $sql = "SELECT 
                    0 AS no,
                    tri.i_rv_item AS id,
                    tri.d_bukti, 
                    to_char(tri.d_bukti, 'YYYYMM') AS i_periode,
                    tc.e_coa_name,
                    tr.i_rv_id,
                    tri.i_area,
                    ta.e_area,
                    trrt.e_rv_refference_type_name,
                    tri.v_rv AS jumlah,
                    tri.v_rv_saldo AS sisa,
                    tri.e_remark,
                    '$id_menu' AS id_menu,
                    '$folder' AS folder,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto
                FROM tm_rv_item tri
                INNER JOIN tm_rv tr ON tr.i_rv = tri.i_rv 
                INNER JOIN tr_coa tc ON tc.id = tr.i_coa
                INNER JOIN tr_coa tc2 ON tc2.id = tri.i_coa AND tc2.f_alokasi_bank_masuk = 't'
                LEFT JOIN tr_rv_refference_type trrt ON	(trrt.i_rv_refference_type = tri.i_rv_refference_type)
                LEFT JOIN tr_area ta ON ta.id = tri.i_area
                WHERE
                    tr.i_company = '$id_company'
                    AND tr.i_status = '6'
                    AND tri.d_bukti BETWEEN '$dfrom' AND '$dto'
                    AND tri.v_rv_saldo > 0
                    AND tc.e_coa_name NOT LIKE '%Kas Besar%'";        

        // var_dump($sql); die();

        $datatables->query($sql, FALSE);

        $datatables->edit('jumlah', function ($data) {
            return "Rp." . number_format($data['jumlah'], 0, ",", ".");
        });

        $datatables->edit('sisa', function ($data) {
            return "Rp." . number_format($data['sisa'], 0, ",", ".");
        });

        /** Cek Hak Akses, Apakah User Bisa Edit */
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $i_area     = $data['i_area'];
            $folder = $data['folder'];
            $id_menu = $data['id_menu'];
            $data       = '';
            
            if (check_role($id_menu, 1)) {
                $onclick = "show('$folder/cform/tambah/$id/$dfrom/$dto','#main'); return false;";
                $data.= "<a href='#' onclick=$onclick title='Tambah Data'><i class='fa fa-check-circle-o success darken-4 fa-lg mr-1'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_periode');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_menu');
        $datatables->hide('i_area');
        $datatables->hide('folder');
        return $datatables->generate();
    }
    
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
        $this->db->select('a.id, a.i_bagian, e_bagian_name, i_type')->distinct();
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

    public function insert_alokasi_piutang($i_alokasi_id, $i_rv, $i_rv_item, $d_alokasi, $e_bank_name, $v_jumlah,
                                        $id_company=null, $id_area, $id_customer, $id_bagian)
    {
        $id_company = $this->session->userdata('id_company');

        $data = [
            'i_alokasi_id' => $i_alokasi_id,
            'i_rv' => $i_rv,
            'i_rv_item' => $i_rv_item,
            'd_alokasi' => $d_alokasi,
            'e_bank_name' => $e_bank_name,
            'v_jumlah' => $v_jumlah,
            'id_company' => $id_company,
            'id_area' => $id_area,            
            'id_customer' => $id_customer,
            'id_bagian' => $id_bagian,
        ];

        $this->db->insert('tm_alokasi_piutang', $data);
    }

    /** untuk detail update keterangan saja */
    public function update_alokasi_piutang_item($id, $e_remark)
    {
        $data = [
            'e_remark' => $e_remark
        ]; 

        $this->db->where('i_alokasi_item', $id);
        $this->db->update('tm_alokasi_piutang_item', $data);
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

    public function insert_alokasi_piutang_item($i_alokasi, $i_alokasi_item=null, $i_rv_item, $id_nota, $d_nota, 
                                    $v_jumlah, $v_sisa, $n_item_no=null, $e_remark, $id_company=null, $id_area)
    {
        if ($id_company == null) {
            $id_company = $this->session->userdata('id_company');
        }

        $data = [
            'i_alokasi' => $i_alokasi,
            // 'i_alokasi_item' => $i_alokasi_item,
            'i_rv_item' => $i_rv_item,
            'id_nota' => $id_nota,
            'd_nota' => $d_nota,
            'v_jumlah' => $v_jumlah,
            'v_sisa' => $v_sisa,
            'n_item_no' => $n_item_no,
            'e_remark' => $e_remark,
            'id_company' => $id_company,
            'id_area' => $id_area
        ];

        $this->db->insert('tm_alokasi_piutang_item', $data);
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

    public function data_header($id)
    {
        $sql = "SELECT tap.*,
                    tr.i_rv,
                    tr.i_rv_id,
                    tr.d_rv,
                    tb.e_bagian_name,
                    ta.e_area,
                    tc.e_customer_name,
                    tc2.e_coa_name,
                    tri.v_rv_saldo + tap.v_jumlah AS v_rv_saldo
                FROM tm_alokasi_piutang tap
                INNER JOIN tm_alokasi_piutang_item tapi ON tapi.i_alokasi = tap.i_alokasi
                INNER JOIN tm_rv tr ON tr.i_rv = tap.i_rv
                INNER JOIN tm_rv_item tri ON tri.i_rv_item = tap.i_rv_item
                INNER JOIN tr_bagian tb ON tb.id = tap.id_bagian
                INNER JOIN tr_area ta ON ta.id = tap.id_area
                INNER JOIN tr_customer tc ON tc.id = tap.id_customer
                INNER JOIN tr_coa tc2 ON tc2.id = tr.i_coa
                WHERE tap.i_alokasi = '$id'";

        // var_dump($sql); die();

        return $this->db->query($sql);
    }

    public function data_detail($id)
    {
        $sql = "SELECT tapi.*,
                tnp.i_document,
                tnp.d_document,
                tnp.v_sisa AS v_nilai,
                tap.v_lebih,
                tnp.v_sisa + tapi.v_Jumlah AS v_nota
                FROM tm_alokasi_piutang_item tapi
                INNER JOIN tm_alokasi_piutang tap ON tap.i_alokasi = tapi.i_alokasi
                INNER JOIN tm_nota_penjualan tnp ON tnp.id = tapi.id_nota
                WHERE tapi.i_alokasi = $id";

        return $this->db->query($sql);
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

    public function update_header($id, $d_alokasi)
    {
        $data = [
            'd_alokasi' => $d_alokasi
        ];

        $this->db->where('i_alokasi', $id);
        $this->db->update('tm_alokasi_piutang', $data);
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
    
    // public function estatus($istatus)
    // {
    //     $this->db->select('e_status_name');
    //     $this->db->from('tr_status_document');
    //     $this->db->where('i_status',$istatus);
    //     return $this->db->get()->row()->e_status_name;
    // }
    
    // public function changestatus($id,$istatus)
    // {
    //     if ($istatus=='6') {
    //         $data = array(
    //             'i_status'  => $istatus,
    //             'e_approve' => $this->username,
    //             'd_approve' => date('Y-m-d'),
    //         );
    //     }else{
    //         $data = array(
    //             'i_status'  => $istatus,
    //         );
    //     }
    //     $this->db->where('id', $id);
    //     $this->db->update('tm_alokasi_kas_bank', $data);
    // }    

    private function change_status_insert_menu_approve($id, $i_menu, $i_level, $username)
    {
        $now = date('Y-m-d');

        $sql = "INSERT INTO tm_menu_approve 
                    (i_menu, i_level, i_document, e_approve, d_approve, e_database) 
                VALUES
                    ('$i_menu','$i_level','$id','$username','$now','tm_alokasi_piutang')";

        $this->db->query($sql, FALSE);
    }

    private function change_status_get_status_approval($id, $i_menu)
    {
        $sql = "SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
            FROM tm_alokasi_piutang a
            JOIN tr_menu_approve b on (b.i_menu = '$i_menu')
            WHERE a.i_alokasi = '$id'
            GROUP BY 1,2";

        return $this->db->query($sql, FALSE);
    }

    private function change_status_delete_approval($id, $i_menu, $i_level)
    {
        $sql = "DELETE FROM tm_menu_approve
                WHERE i_menu = '$i_menu' AND i_level = '$i_level' AND i_document = '$id'";

        $this->db->query($sql, FALSE);
    }

    public function changestatus($id, $istatus)
    {
        $approval = $this->change_status_get_status_approval($id, $this->i_menu)->row();

        $data = [
            'i_status' => $istatus
        ];        
            
        /** change request */
        if ($istatus == '3') {
            if ($approval->i_approve_urutan - 1 != 0) {
                $data = [
                    'i_approve_urutan' => $approval->i_approve_urutan - 1
                ];
            } 
            $this->change_status_delete_approval($id, $this->i_menu, $this->i_level);
        }
        
        /** approve */
        if ($istatus == '6') {
            $data = [
                'i_approve_urutan' => $approval->i_approve_urutan + 1,
            ];

            if ($approval->i_approve_urutan + 1 > $approval->n_urut) {
                $data = [
                    'i_status' => $istatus,
                    'i_approve_urutan' => $approval->i_approve_urutan + 1,
                    'e_approve' => $this->username,
                    'd_approve' => date('Y-m-d'),
                ];

                /** update nota dan voucher */
                $this->update_action_approve($id);
            } 

            $this->change_status_insert_menu_approve($id, $this->i_menu, $this->i_level, $this->username);
        }        

        $this->db->where('i_alokasi', $id);
        $this->db->update('tm_alokasi_piutang', $data);
    }

    private function update_action_approve($id)
    {
        $all_item = $this->data_detail($id);

        foreach($all_item->result() as $item) {
            $id_nota = $item->id_nota;
            $v_jumlah = $item->v_jumlah;
            $e_remark = $item->e_remark;
            $this->db->query("UPDATE tm_nota_penjualan SET v_sisa = v_sisa - '$v_jumlah', e_remark = '$e_remark' WHERE id = '$id_nota' ", FALSE);

            $i_rv_item = $item->i_rv_item;
            $this->db->query("UPDATE tm_rv_item SET v_rv_saldo = v_rv_saldo - '$v_jumlah' WHERE i_rv_item = '$i_rv_item' ", FALSE);
        }
    }

    /*----------  END RUBAH STATUS  ----------*/

    public function get_data_rv($id)
    {
        $sql = "SELECT 
                a.i_rv_id,
                a.i_rv,      
                b.i_rv_item,
                b.d_bukti,
                a.i_area,
                c.e_area,
                d.e_coa_name,
                b.v_rv,
                b.v_rv_saldo   
            FROM tm_rv a
            INNER JOIN tm_rv_item b ON (b.i_rv = a.i_rv)
            INNER JOIN tr_area c ON (c.id = a.i_area)
            INNER JOIN tr_coa d ON (d.id = a.i_coa)
            WHERE b.i_rv_item = '$id'";

        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function area()
    {
        $id_company = $this->session->userdata('id_company');
        $username = $this->session->userdata('username');

        $where = "WHERE tua.id_company = '$id_company' AND tua.username = '$username'";

        $sql = "SELECT ta.id, ta.i_area, ta.e_area 
                FROM tm_user_area tua
                INNER JOIN tr_area ta ON ta.i_area = tua.i_area
                $where";
                

        return $this->db->query($sql);
    }

    public function get_all_customer($q='', $id_area=null)
    {
        $id_company = $this->session->userdata('id_company');

        $where = " AND id_company = '$id_company'";

        if ($id_area != null) {
            $where .= " AND id_area = $id_area";
        }

        if ($q != '') {
            $where .= " AND e_customer_name ILIKE '%$q%'";
        }

        $sql = "SELECT * 
                FROM tr_customer tc  
                WHERE f_status = 't' $where";

        // var_dump($sql);

        return $this->db->query($sql);
    }

    public function generate_nomor_dokumen($id_bagian) {

        $kode = 'AL';

        $sql = "SELECT count(*) 
                FROM tm_alokasi_piutang tap
                INNER JOIN tr_bagian tb ON tb.id = tap.id_bagian 
                WHERE tb.id = '$id_bagian'
                    AND to_char(d_alokasi, 'yyyy-mm') = to_char(now(), 'yyyy-mm')";

        $query = $this->db->query($sql);
        $result = $query->row()->count;
        $count = intval($result) + 1;
        $generated = $kode . '-' . date('ym') . '-' . sprintf('%04d', $count);

        return $generated;
    }

    public function get_nota($q, $id_area, $id_customer)
    {
        $id_company = $this->session->userdata('id_company');

        $sql = "SELECT
                    id,
                    i_document,
                    to_char(d_document, 'DD FMMonth YYYY') AS d_document
                FROM tm_nota_penjualan
                WHERE i_status = '6'
                    AND (i_document ILIKE '%$q%')
                    AND id_customer = '$id_customer'
                    AND id_company = '$id_company'
                    AND v_sisa > 0
                ORDER BY i_document ASC ";
        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function get_detail_nota($id_nota)
    {
        $sql = "SELECT
                    to_char(d_document, 'DD FMMonth YYYY') AS dnota,
                    d_document,
                    v_bersih,
                    v_sisa
                FROM tm_nota_penjualan
                WHERE id = '$id_nota'";

        return $this->db->query($sql, FALSE);
    }
}
/* End of file Mmaster.php */