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
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_spb
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
        $datatables->query("SELECT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_customer,
                b.e_customer_name,
                a.i_referensi,
                e_remark,
                e_status_name,
                label_color,
                l.i_level,
                l.e_level_name,
                a.i_status,
                a.e_jenis_spb,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_spb a
            INNER JOIN tr_customer b ON
                (a.id_customer = b.id)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            LEFT JOIN public.tr_menu_approve e on 
                (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on 
                (e.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company 
                AND a.i_promo NOTNULL
                $and 
                $bagian
            ORDER BY
                a.id ASC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
			$i_status = $data['i_status'];
			if ($i_status == '2') {
				$data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
			}
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $idcustomer = trim($data['id_customer']);
            $ddocument  = $data['d_document'];
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $i_status   = $data['i_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $jenis      = $data['e_jenis_spb'];
            $i_level    = $data['i_level'];
            $data       = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if ($i_level == $this->level || 1 == $this->level) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
                }   
            }

            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print SPB' onclick='cetak(\"$id\",\"$ddocument\",\"$idcustomer\",\"$jenis\"); return false;'><i class='ti-printer text-warning mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_customer');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        $datatables->hide('e_jenis_spb');
        return $datatables->generate();
    }

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/    

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */

        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				LEFT JOIN tr_type c on (a.i_type = c.i_type)
				LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/
    
    public function runningnumber($thbl,$tahun,$ibagian) 
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 3) AS kode
            FROM tm_spb
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SPB';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_spb
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
        $this->db->from('tm_spb');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /** Get Promo */
    public function get_promo($cari, $tanggal)
    {
        $tanggal = date('Y-m-d', strtotime($tanggal));
        return $this->db->query("SELECT 
                DISTINCT
                id_promo, 
                i_promo_code, 
                e_promo_name 
            FROM 
                tm_promo
            WHERE 
                (e_promo_name ILIKE '%$cari%' 
                OR i_promo_code ILIKE '%$cari%')
                AND id_company = '$this->company' 
                AND f_status = 'f'
                AND (d_promo_start <= '$tanggal' 
                AND d_promo_finish >= '$tanggal')
            ORDER BY 3 ASC
        ", FALSE);
    }

    /** Get Detail Promo */
    public function get_promo_detail($ipromo)
    {
        return $this->db->query("SELECT a.*, b.f_plus_discount
            FROM 
                tm_promo a, tr_promo_type b
            WHERE 
                a.id_promo_type = b.id_promo_type
                AND a.id_promo = '$ipromo'
        ", FALSE);
    }

     /** Get Customer */
    public function get_customer($cari, $i_area, $i_promo, $f_all_customer)
    {
        if ($f_all_customer == 't') {
            return $this->db->query("SELECT 
                    id, i_customer , e_customer_name
                FROM 
                    tr_customer
                WHERE 
                    (e_customer_name ILIKE '%$cari%' OR i_customer ILIKE '%$cari%')
                    AND id_company = '$this->company' 
                    AND f_status = 'true' 
                    AND id_area = '$i_area'
                ORDER BY 3 ASC
            ", FALSE);
        } else {
            return $this->db->query("SELECT 
                    id, i_customer , e_customer_name
                FROM 
                    tr_customer
                WHERE 
                    (e_customer_name ILIKE '%$cari%' OR i_customer ILIKE '%$cari%')
                    AND id_company = '$this->company' 
                    AND f_status = 'true' 
                    AND id_area = '$i_area'
                    AND id IN (SELECT id_customer FROM tm_promo_customer WHERE id_promo = '$i_promo')
                ORDER BY 3 ASC
            ", FALSE);
        }
    }

    /*----------  CARI PELANGGAN  ----------*/
    
    public function customer($cari,$iarea)
    {
        return $this->db->query("
            SELECT
                a.id,
                i_customer,
                e_customer_name
            FROM
                tr_customer a
            INNER JOIN tr_type_industry b ON
                (b.i_type_industry = a.i_type_industry
                AND a.id_company = b.id_company)
            /* INNER JOIN tr_type_spb c ON
                (c.id_type_industry = b.id) */
            WHERE
                a.id_company = $this->company
                AND (i_customer ILIKE '%$cari%'
                OR e_customer_name ILIKE '%$cari%')
                AND a.f_status = 't'
                AND a.id_area = '$iarea'
                /* AND c.i_type = '3' */
            ORDER BY
                e_customer_name
            ", FALSE);
    }

    /*----------  GET DISKON SESUAI PELANGGAN  ----------*/

    public function getdetailcustomer($idcustomer)
    {
        return $this->db->query("            
            SELECT 
                v_customer_discount,
                v_customer_discount2,
                v_customer_discount3,
                id_harga_kode,
                i_harga||' - '||e_harga AS e_harga_kode,
                e_customer_name
            FROM
                tr_customer a
            INNER JOIN tr_harga_kode b ON
                (b.id = a.id_harga_kode)
            WHERE
                a.id = $idcustomer
            ", FALSE);
    }  

    /** Get Detail Customer */
    public function get_customer_detail($icustomer)
    {
        return $this->db->query("SELECT
                id_harga_kode,
                v_customer_discount AS n_customer_discount1,
                v_customer_discount2 AS n_customer_discount2,
                v_customer_discount3 AS n_customer_discount3,
                i_harga||' - '||e_harga AS e_harga_kode,
                e_customer_name
            FROM
                tr_customer a
            INNER JOIN tr_harga_kode b ON
                (b.id = a.id_harga_kode)
            WHERE
                a.id = '$icustomer'
        ", FALSE);
    }  

    /** Get Area */
    public function get_area($cari, $i_promo, $f_all_area)
    {
        if ($f_all_area == 't') {
            return $this->db->query("SELECT 
                DISTINCT
                    id, 
                    i_area, 
                    e_area 
                FROM 
                    tr_area
                WHERE 
                    (e_area ILIKE '%$cari%' OR i_area ILIKE '%$cari%')
                    AND f_status = true
                ORDER BY 2 ASC
                ", FALSE);
        } else {
            return $this->db->query("SELECT 
                DISTINCT
                    id,
                    i_area, 
                    e_area 
                FROM 
                    tr_area
                WHERE 
                    (e_area ILIKE '%$cari%' OR i_area ILIKE '%$cari%')
                    AND f_status = true
                    AND id IN (SELECT id_area FROM tm_promo_area WHERE id_promo = '$i_promo')
                ORDER BY 2 ASC
                ", FALSE);
        }
    }

     

    /*----------  DATA MASTER AREA  ----------*/
    
    public function area(){
        return $this->db->query("
            SELECT
                id,
                i_area,
                e_area
            FROM
                tr_area
            WHERE
                f_status = 't'
            ORDER BY
                i_area
        ", FALSE);
    } 

    /*----------  DATA MASTER SALESMAN  ----------*/
    
    public function sales(){
        return $this->db->query("
            SELECT
                id,
                i_sales,
                e_sales
            FROM
                tr_salesman
            WHERE
                f_status = 't'
            ORDER BY
                i_sales
        ", FALSE);
    }

    /*----------  DATA KELOMPOK BARANG  ----------*/    

    public function kelompok($cari,$ibagian)
    {
        return $this->db->query("
            SELECT
                i_kode_kelompok,
                e_nama_kelompok
            FROM
                tr_kelompok_barang
            WHERE
                f_status = 't'
                AND i_kode_kelompok IN (
                    SELECT
                        i_kode_kelompok
                    FROM
                        tr_bagian_kelompokbarang
                    WHERE
                        e_nama_kelompok ILIKE '%$cari%'
                        AND id_company = $this->company
                        AND i_bagian = '$ibagian' )
                        AND id_company = $this->company
                    ORDER BY
                        e_nama_kelompok
            ", FALSE);
    }

    /*----------  DATA JENIS BARANG  ----------*/

    public function jenis($cari,$ikelompok,$ibagian)
    {
        $jenis = "";
        if ($this->departement!='5' || $this->departement!='1') {
            if (($ikelompok != '' || $ikelompok != null) && $ikelompok!='all') {
                $jenis = "AND i_kode_kelompok = '$ikelompok' ";
            }else{
                $jenis = "AND i_kode_kelompok IN 
                (SELECT
                    i_kode_kelompok
                FROM
                    tr_bagian_kelompokbarang
                WHERE
                    i_bagian = '$ibagian'
                    AND id_company = $this->company)";
            }
        }
        return $this->db->query("
            SELECT
                DISTINCT i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                e_type_name ILIKE '%$cari%'
                AND f_status = 't'
                AND id_company = $this->company
                $jenis
            ORDER BY
                e_type_name
        ", FALSE);
    }

    /** Get Product */
    public function get_product($cari, $i_price_group, $i_promo, $f_all_product)
    {
        if ($f_all_product == 't') {
            return $this->db->query("SELECT DISTINCT
                    a.id,
                    i_product_base,
                    e_product_basename
                FROM
                    tr_product_base a
                INNER JOIN tr_harga_jualbrgjd b ON 
                    (a.id = id_product_base 
                    AND a.id_company = b.id_company AND id_harga_kode = $i_price_group)
                WHERE
                    a.f_status = 't'
                    AND (i_product_base ILIKE '%$cari%' 
                    OR e_product_basename ILIKE '%$cari%')
                    AND a.id_company = '$this->company'
                ORDER BY
                    i_product_base
            ", FALSE);
        } else {
            return $this->db->query("SELECT
                    a.id_product AS id,
                    i_product_base,
                    e_product_basename
                FROM
                    tm_promo_item a
                INNER JOIN tr_product_base ab ON 
                    (ab.id = a.id_product)
                WHERE
                    (ab.e_product_basename ILIKE '%$cari%'
                        OR ab.i_product_base ILIKE '%$cari%')
                    AND ab.id_company = '$this->company'
                    AND ab.f_status = TRUE
                    AND a.id_promo = '$i_promo'
                ORDER BY
                    i_product_base ASC
            ", FALSE);
        }
    }


    public function get_product_price($i_price_group, $i_product, $i_promo, $f_all_product)
    {
        if ($f_all_product == 't') {
            return $this->db->query("SELECT DISTINCT
                    v_price,
                    1 AS n_quantity_min
                FROM
                    tr_product_base a
                INNER JOIN tr_harga_jualbrgjd b ON 
                    (a.id = id_product_base 
                    AND a.id_company = b.id_company AND id_harga_kode = $i_price_group)
                WHERE
                    a.f_status = 't'
                    AND a.id = '$i_product' 
                    AND a.id_company = '$this->company'
        ", FALSE);
        } else {
            return $this->db->query("SELECT
                    a.v_unit_price AS v_price,
                    a.n_quantity_min
                FROM
                    tm_promo_item a
                INNER JOIN tr_product_base ab ON 
                    (ab.id = a.id_product)
                WHERE
                    a.id_product = '$i_product'
                    AND a.id_promo = '$i_promo'
            ", FALSE);
        }
    }

    /*----------  BACA BARANG JADI  ----------*/
    
    public function product($cari,$ikategori,$ijenis,$ibagian,$idharga)
    {
        $kategori = "";
        $jenis    = "";
        if ($this->departement!='5' || $this->departement!='1') {
            if (($ikategori != '' || $ikategori != null) && $ikategori!='all') {
                $kategori = "AND i_kode_kelompok = '$ikategori' ";
            }else{
                $kategori = "AND i_kode_kelompok 
                IN (SELECT
                        i_kode_kelompok
                    FROM
                        tr_bagian_kelompokbarang
                    WHERE
                        i_bagian = '$ibagian'
                        AND id_company = $this->company)";
            }

            if (($ijenis != '' || $ijenis != null) && $ijenis!='all') {
                $jenis = "AND i_type_code = '$ijenis' ";
            }else{
                $jenis = "AND i_type_code 
                IN (SELECT
                        i_type_code
                    FROM
                        tr_item_type
                    WHERE
                        f_status = 't'
                        AND id_company = $this->company
                        AND i_kode_kelompok IN 
                            (SELECT
                                i_kode_kelompok
                            FROM
                                tr_bagian_kelompokbarang
                            WHERE
                                i_bagian = '$ibagian'
                                AND id_company = $this->company))";
            }
        }
        return $this->db->query("SELECT DISTINCT
                a.id,
                i_product_base,
                e_product_basename
            FROM
                tr_product_base a
            INNER JOIN tr_harga_jualbrgjd b ON 
                (a.id = id_product_base 
                AND a.id_company = b.id_company AND id_harga_kode = $idharga)
            WHERE
                a.f_status = 't'
                AND (i_product_base ILIKE '%$cari%' 
                OR e_product_basename ILIKE '%$cari%')
                AND a.id_company = $this->company
                $kategori
                $jenis
            ORDER BY
                i_product_base
        ", FALSE);
    }

    /*----------  GET DETAIL BARANG JADI  ----------*/    

    public function getproduct($idproduct,$idharga,$ddocument,$idcustomer)
    {
        $dberlaku  = date('Y-m-d', strtotime($ddocument));
        $dakhir    = date('Y-m-d', strtotime('+1 year', strtotime($ddocument))); /*tambah tanggal sebanyak 1 tahun*/
        $periode   = date('Ym', strtotime($ddocument));

        return $this->db->query("
            SELECT
                x.*,
                CASE
                    WHEN e.id IS NOT NULL THEN COALESCE(d.n_quantity_fc, 0)
                    ELSE 9999
                END AS fc
            FROM
                (
                SELECT
                    a.id_company,
                    a.id AS id_product,
                    a.i_product_base,
                    a.e_product_basename,
                    b.v_price,
                    b.d_berlaku,
                    CASE
                        WHEN d_akhir ISNULL THEN '$dakhir'
                        ELSE d_akhir
                    END AS d_akhir,
                    $idcustomer AS id_customer,
                    '$periode' AS periode
                FROM
                    tr_product_base a
                INNER JOIN tr_harga_jualbrgjd b ON
                    (a.id = id_product_base
                    AND a.id_company = b.id_company)
                WHERE
                    a.id_company = $this->company
                    AND a.id = '$idproduct'
                    AND id_harga_kode = $idharga ) AS x
            LEFT JOIN f_get_forecast_distributor($this->company,
                '$periode',$idcustomer) d ON
                (d.id_company = x.id_company
                AND d.periode = x.periode
                AND d.id_customer = x.id_customer
                AND d.id_product = x.id_product)
            LEFT JOIN tr_customer_transfer e ON
                (x.id_company = e.id_company
                AND x.id_customer = e.id_customer)
            WHERE
                x.d_berlaku <= '$dberlaku'
                AND x.d_akhir >= '$dberlaku'
            ", FALSE);
    }  

    /*----------  RUNNING ID SPBD  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_spb');
        return $this->db->get()->row()->id+1;
    }

    /*----------  NOT RUNNING ID SPBD  ----------*/
    
    public function notrunningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_spb');
        return $this->db->get()->row()->id;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp,$idharga,$etypespb,$ipromo)
    {
        $data = array(
            'id'                => $id,
            'id_company'        => $this->company,
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_customer'       => $idcustomer,
            'id_area'           => $idarea,
            'id_salesman'       => $idsales,
            'i_referensi'       => $ireferensi,
            'v_bruto'           => $vkotor,
            'v_discount'        => $vdiskon,
            'v_dpp'             => $vdpp,
            'v_ppn'             => $vppn,
            'v_netto'           => $vbersih,
            'id_harga_kode'     => $idharga,
            'e_remark'          => $eremarkh,
            'e_jenis_spb'       => $etypespb,
            'i_promo'           => $ipromo,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_spb', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/
    
    public function insertdetail($id,$idproduct,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$ndiskon4,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskon4,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark)
    {
        $data = array(
            'id_company'        => $this->company,
            'id_document'       => $id,
            'id_product'        => $idproduct,
            'n_quantity'        => $nquantity,
            'n_quantity_sisa'   => $nquantity,
            'v_price'           => $vprice,
            'n_diskon1'         => $ndiskon1,
            'n_diskon2'         => $ndiskon2,
            'n_diskon3'         => $ndiskon3,
            'n_diskon4'         => $ndiskon4,
            'v_diskon1'         => $vdiskon1,
            'v_diskon2'         => $vdiskon2,
            'v_diskon3'         => $vdiskon3,
            'v_diskon4'         => $vdiskon4,
            'v_diskon_tambahan' => $vdiskonplus,
            'v_diskon_total'    => $vtotaldiskon,
            'v_total'           => $vtotal,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_spb_item', $data);
    }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/
    
    public function editheader($id)
    {
        return $this->db->query("SELECT
                a.id,
                a.i_bagian,
                e_bagian_name,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                id_customer,
                i_customer,
                e.e_customer_name,
                a.id_harga_kode,
                i_harga,
                e_harga,
                a.id_area,
                a.id_salesman,
                e_area,
                i_area,
                i_referensi,
                e_remark,
                i_status,
                v_netto AS v_bersih,
                v_discount AS v_diskon,
                v_dpp,
                v_ppn,
                v_bruto AS v_kotor,
                e.v_customer_discount,
                e.v_customer_discount2,
                e.v_customer_discount3,
                e_customer_address,
                e_city_name,
                e_sales,
                to_char(e.d_join, 'dd-mm-yyyy') AS d_join,
                h.*,
                n_valid, f_plus_discount
            FROM
                tm_spb a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_harga_kode c ON
                (c.id = a.id_harga_kode)
            INNER JOIN tr_area d ON
                (d.id = a.id_area)
            INNER JOIN tr_customer e ON
                (e.id = a.id_customer)
            INNER JOIN tr_city f ON 
                (f.id = e.id_city)
            INNER JOIN tr_salesman g ON 
                (g.id = a.id_salesman)
            INNER JOIN tm_promo h ON 
                (h.id_promo = a.i_promo)
            INNER JOIN tr_promo_type i ON 
                (i.id_promo_type = h.id_promo_type)
            WHERE
                a.id = $id
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM  ----------*/
    
    public function edititem($id,$jenis)
    {
        return $this->db->query("SELECT DISTINCT
                a.*,
                b.i_product_base,
                b.e_product_basename,
                c.id_customer,
                to_char(c.d_document, 'yyyymm'),
                a.n_quantity_sisa as fc, 
                e_satuan_name ,
                COALESCE(n_quantity_min,1) AS n_quantity_min 
            FROM
                tm_spb_item a 
            INNER JOIN
                tr_product_base b 
                ON (b.id = a.id_product) 
            INNER JOIN
                tr_satuan s 
                ON (s.i_satuan_code = b.i_satuan_code 
                AND b.id_company = s.id_company) 
            INNER JOIN
                tm_spb c 
                ON (a.id_document = c.id) 
            LEFT JOIN tm_promo_item i ON (i.id_product = a.id_product AND c.i_promo = i.id_promo)
            WHERE
                a.id_document = '$id'
            ORDER BY
                a.id
        ", FALSE);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_spb');
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
        $this->db->delete('tm_spb_item');
    }

    /*----------  UPDATE HEADER  ----------*/
    
    public function updateheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp,$idharga,$ipromo)
    {
        $data = array(
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_customer'       => $idcustomer,
            'id_area'           => $idarea,
            'id_salesman'       => $idsales,
            'i_referensi'       => $ireferensi,
            'v_bruto'           => $vkotor,
            'v_discount'        => $vdiskon,
            'v_dpp'             => $vdpp,
            'v_ppn'             => $vppn,
            'v_netto'           => $vbersih,
            'id_harga_kode'     => $idharga,
            'e_remark'          => $eremarkh,
            'i_promo'           => $ipromo,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_spb', $data);
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
        /* if ($istatus=='6') {
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
        $this->db->update('tm_spb', $data); */
        $now = date('Y-m-d');
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("
            	SELECT b.i_menu , a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut from tm_spb a
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
            	$this->db->query("delete from tm_menu_approve where i_menu = '$this->i_menu' and i_level = '$this->level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
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
					 ('$this->i_menu','$this->level','$id','$this->username','$now','tm_spb');", FALSE);
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_spb', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/

    /*----------  BACA HISTORY PENJUALAN 6 BULAN KE BELAKANG  ----------*/
    
    public function history($ddocument,$idcustomer)
    {
        $periode1 = date('Ym', strtotime('-1 month', strtotime($ddocument)));
        $periode2 = date('Ym', strtotime('-2 month', strtotime($ddocument)));
        $periode3 = date('Ym', strtotime('-3 month', strtotime($ddocument)));
        $periode4 = date('Ym', strtotime('-4 month', strtotime($ddocument)));
        $periode5 = date('Ym', strtotime('-5 month', strtotime($ddocument)));
        $periode6 = date('Ym', strtotime('-6 month', strtotime($ddocument)));
        return $this->db->query("
            SELECT
                sum(v_total1) AS v_total1,
                sum(v_total2) AS v_total2,
                sum(v_total3) AS v_total3,
                sum(v_total4) AS v_total4,
                sum(v_total5) AS v_total5,
                sum(v_total6) AS v_total6
            FROM(
                SELECT
                    sum(v_bersih) AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode1'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    sum(v_bersih) AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode2'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    sum(v_bersih) AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode3'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    sum(v_bersih) AS v_total4,
                    0 AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode4'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    sum(v_bersih) AS v_total5,
                    0 AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode5'
                    AND id_customer = '$idcustomer'
                UNION ALL
                SELECT
                    0 AS v_total1,
                    0 AS v_total2,
                    0 AS v_total3,
                    0 AS v_total4,
                    0 AS v_total5,
                    sum(v_bersih) AS v_total6
                FROM
                    tm_nota_penjualan
                WHERE
                    id_company = '$this->company'
                    AND i_status = '6'
                    AND to_char(d_document, 'yyyymm') = '$periode6'
                    AND id_customer = '$idcustomer'
            ) AS x
        ", FALSE);
    }

    /*----------  BACA SISA PIUTANG CUSTOMER  ----------*/

    public function piutang($idcustomer)
    {
        return $this->db->query("
            SELECT
                coalesce(sum(v_sisa),0) AS piutang
            FROM
                tm_nota_penjualan
            WHERE
                id_company = '$this->company'
                AND i_status = '6'
                AND id_customer = '$idcustomer'
                AND v_sisa > 0
        ", FALSE)->row()->piutang;
    }
    
    /*----------  UPDATE STATUS PRINT  ----------*/
    
    public function updateprint($id)
    {
        $this->db->query("
            UPDATE tm_spb SET n_print = n_print + 1 WHERE id = $id
        ", FALSE);
    }    
    
}
/* End of file Mmaster.php */