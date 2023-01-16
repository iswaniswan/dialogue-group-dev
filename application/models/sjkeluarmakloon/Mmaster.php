<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    /*----------  DAFTAR DATA MASUK GUDANG JADI SESUAI GUDANG PEMBUAT  ----------*/
    
    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_keluar_makloon a
            WHERE
                i_status <> '5'
                AND id_company = '$this->id_company'
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND id_company = '$this->id_company'
                        AND username = '$this->username')

        ", FALSE);
        if ($this->i_departement=='1') {
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
                        i_departement = '$this->i_departement'
                        AND id_company = '$this->id_company'
                        AND username = '$this->username')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                DISTINCT 0 AS NO,
                a.id AS id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                to_char(a.d_estimate, 'dd-mm-yyyy') AS d_estimate,
                d.e_supplier_name,
                e.e_bagian_name,
                e_status_name,
                label_color,
                a.i_status,
                l.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_makloon a
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            INNER JOIN tr_supplier d ON
                (d.id = a.id_supplier)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = a.i_bagian_receive AND a.id_company = e.id_company)
            LEFT JOIN public.tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (f.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->id_company'
                $and
                $bagian
            ORDER BY
                a.id ASC
            ", FALSE
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = $data['id'];
            $i_status   = trim($data['i_status']);
            $i_level    = trim($data['i_level']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye mr-2 fa-lg text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        $datatables->hide('i_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /*----------  TYPE MAKLOON  ----------*/    

    public function type($i_menu)
    {
        $this->db->select('b.id, e_type_makloon_name AS e_name')->distinct();
        $this->db->from('tr_makloon_menu a');
        $this->db->join('tr_type_makloon b','b.id = a.id_makloon AND a.id_company = b.id_company','inner');
        $this->db->where('b.f_status', 't');
        $this->db->where('a.id_company', $this->id_company);
        $this->db->where('a.i_menu', $i_menu);
        $this->db->order_by('e_type_makloon_name');
        return $this->db->get();
    }

    /*----------  BACA BAGIAN PEMBUAT  ----------*/    

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->i_departement);
        $this->db->where('a.f_status', 't');
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->id_company);
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

    public function bagian_receive()
    {
        return $this->db->query("SELECT id, i_bagian, e_bagian_name FROM tr_bagian WHERE id_company = '$this->id_company' AND i_bagian IN (
            SELECT i_bagian FROM tr_tujuan_menu WHERE id_company = '$this->id_company' AND i_menu = '$this->i_menu' ORDER BY id
            )");
    }

    /*----------  BACA PARTNER  ----------*/    

    public function partner($idtype,$cari)
    {
        return $this->db->query("SELECT
                DISTINCT b.id,
                b.e_supplier_name AS e_name
            FROM
                tr_supplier b 
            INNER JOIN tr_supplier_makloon c ON
                (c.i_supplier = b.i_supplier
                AND b.id_company = c.id_company)
            INNER JOIN tr_type_makloon e ON
                (e.i_type_makloon = c.i_type_makloon
                AND c.id_company = e.id_company)
            WHERE
                e.id = $idtype
                AND b.e_supplier_name ILIKE '%$cari%'
                AND b.id_company = '$this->id_company'
            ORDER BY
                2
        ", FALSE);
    }  

    /*----------  DETAIL PARTNER  ----------*/
    
    public function detailpartner($idsupplier)
    {
        return $this->db->query("SELECT i_type_pajak, n_diskon FROM tr_supplier WHERE id = $idsupplier ", FALSE);
    }  

    public function product_wip($cari, $dfrom, $dto)
    {
        $dfrom = formatYmd($dfrom);
        $dto = formatYmd($dto);
        return $this->db->query("SELECT DISTINCT
                c.id,
                c.i_product_wip,
                c.e_product_wipname,
                d.e_color_name
                /* sum(coalesce(b.n_quantity,0)) n_quantity */
            FROM
                tm_schedule_jahit a
            INNER JOIN tm_schedule_jahit_item_new b ON
                (b.id_document = a.id)
            LEFT JOIN tr_product_wip c ON
                (c.id = b.id_product_wip)
            LEFT JOIN tr_color d ON (d.i_color = c.i_color AND c.id_company = d.id_company)
            WHERE
                a.i_status = '6'
                -- AND a.d_document BETWEEN '$dfrom' AND '$dto'
                AND (c.i_product_wip ILIKE '%$cari%' OR c.e_product_wipname ILIKE '%$cari%')
            ORDER BY 2,3
            /* GROUP BY
                1,2,3 */");
    }

    public function product_material($cari, $dfrom, $dto, $id_wip)
    {
        $dfrom = formatYmd($dfrom);
        $dto = formatYmd($dto);
        return $this->db->query("SELECT
                a.id,
                a.i_material,
                a.e_material_name,
                e.e_satuan_name
            FROM
                tr_material a
            INNER JOIN tr_product_wip_item c ON
                (c.id_material = a.id
                    AND c.id_product_wip = '$id_wip'
                    AND (c.f_cutting = 't'
                        OR c.f_print = 't'
                        OR c.f_bordir = 't'
                        OR c.f_quilting = 't'
                        OR c.bagian = 'BIS-BISAN'))
            INNER JOIN tr_satuan e ON
                (e.i_satuan_code = a.i_satuan_code
                    AND a.id_company = e.id_company)
            WHERE (a.i_material ILIKE '%$cari%' OR a.e_material_name ILIKE '%$cari%')
            ORDER BY 2,3");
    }

    public function product($cari, $dfrom, $dto, $idtype)
    {
        $dfrom = formatYmd($dfrom);
        $dto = formatYmd($dto);
        return $this->db->query("SELECT
                d.id,
                d.i_material,
                d.e_material_name,
                e.e_satuan_name,
                round(sum(coalesce(b.n_quantity,0) * coalesce(c.n_quantity,0)),4) n_quantity
            FROM
                tm_schedule_jahit a
            INNER JOIN tm_schedule_jahit_item_new b ON
                (b.id_document = a.id)
            LEFT JOIN (
                SELECT a.id_product_wip, a.id_material, a.f_marker_utama, a.id_type_makloon,
                    case 
                        when COALESCE(c.v_panjang_bis,0) > 0 then ((1/a.v_set) * a.v_gelar) + 
                        (COALESCE (v_bisbisan,0)/COALESCE (v_panjang_bis,0)) 
                        else ((1/a.v_set) * a.v_gelar) 
                    end as n_quantity
                FROM tr_polacutting_new a
                left join tr_material_bisbisan c on (c.id = a.id_bisbisan and a.id_material = c.id_material)
                INNER JOIN tr_type_makloon b ON (b.id = ANY(a.id_type_makloon))
                WHERE id_type_makloon NOTNULL AND a.f_budgeting = 't' and a.f_status = 't'
            ) c ON
                (c.id_product_wip = b.id_product_wip /* AND '$idtype' = ANY(c.id_type_makloon)
                     AND (c.f_cutting = 't'
                        OR c.f_print = 't'
                        OR c.f_bordir = 't'
                        OR c.f_quilting = 't'
                        OR c.bagian = 'BIS-BISAN') */)
            LEFT JOIN tr_material d ON
                (d.id = c.id_material)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = d.i_satuan_code
                    AND d.id_company = e.id_company)
            WHERE
                a.i_status = '6'
                /* AND a.d_document BETWEEN '$dfrom' AND '$dto' */
                AND (d.i_material ILIKE '%$cari%' OR d.e_material_name ILIKE '%$cari%')
            GROUP BY
                1,2,3,4
            ORDER BY 3,2");
    }

    public function detail_product($id_material,$dfrom,$dto)
    {
        $dfrom = formatYmd($dfrom);
        $dto = formatYmd($dto);
        return $this->db->query("SELECT
                d.id,
                d.i_material,
                d.e_material_name,
                e.e_satuan_name,
                round(sum(b.n_quantity * c.n_quantity),4) n_quantity
            FROM
                tm_schedule_jahit a
            INNER JOIN tm_schedule_jahit_item_new b ON
                (b.id_document = a.id)
            LEFT JOIN tr_product_wip_item c ON
                (c.id_product_wip = b.id_product_wip
                    AND (c.f_cutting = 't'
                        OR c.f_print = 't'
                        OR c.f_bordir = 't'
                        OR c.f_quilting = 't'
                        OR c.bagian = 'BIS-BISAN'))
            LEFT JOIN tr_material d ON
                (d.id = c.id_material)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = d.i_satuan_code
                    AND d.id_company = e.id_company)
            WHERE
                a.i_status = '6'
                AND a.d_document BETWEEN '$dfrom' AND '$dto'
                AND d.id = '$id_material'
            GROUP BY
                1,2,3,4");
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_keluar_makloon 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJ';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 9, 4)) AS max
            FROM
                tm_keluar_makloon
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 4){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "0001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    /*----------  CEK NO DOKUMEN  ----------*/
    
    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_makloon');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->id_company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI DATA REFERENSI  ----------*/
    
    public function datareferensi($cari,$idpartner)
    {
        return $this->db->query("            
            SELECT DISTINCT
                a.id,
                i_document
            FROM
                tm_memo_bb a
            INNER JOIN tm_memo_bb_item b ON
                (a.id = b.id_document)
            WHERE 
                id_partner = '$idpartner'
                AND e_partner_type = 'supplier'
                AND id_jenis = '1'
                AND i_status = '6'
                AND n_quantity_sisa > 0
                AND n_quantity_list_sisa > 0
                AND a.id_company = '$this->id_company'
            ORDER BY 2
        ", FALSE);
    }

    /*----------  REFERENSI HEADER  ----------*/
    
    public function ref($id)
    {
        return $this->db->query("            
            SELECT 
                to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_memo_bb
            WHERE 
                id = $id
        ", FALSE);
    }

    /*----------  DETAIL DATA REFERENSI  ----------*/    

    public function detailreferensi($id,$tgl)
    {
        $idcompany = $this->id_company;
        $dberlaku  = date('Y-m-d', strtotime($tgl));
        $dakhir    = date('Y-m-d', strtotime('+1 year', strtotime($tgl))); /*tamabah tanggal sebanyak 1 tahun*/
        return $this->db->query("
            SELECT
                id_material,
                b.i_material,
                b.e_material_name,
                bb.e_satuan_name,
                n_quantity,
                n_quantity_sisa,
                id_material_list,
                c.i_material AS i_material_list,
                c.e_material_name AS e_material_list,
                cc.e_satuan_name AS e_satuan_list,
                n_quantity_list,
                n_quantity_list_sisa,
                y.v_unitprice,
                x.v_unitprice AS v_unitprice_list
            FROM
                tm_memo_bb_item a
            INNER JOIN tm_memo_bb aa ON
                (aa.id = a.id_document)
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_satuan bb ON
                (bb.i_satuan_code = b.i_satuan_code
                AND b.id_company = bb.id_company)
            INNER JOIN tr_material c ON
                (c.id = a.id_material_list)
            INNER JOIN tr_satuan cc ON
                (cc.i_satuan_code = c.i_satuan_code
                AND c.id_company = cc.id_company)
            INNER JOIN (
                SELECT
                    *
                FROM
                    (
                    SELECT
                        a.id_supplier,
                        /*a.id_type_makloon,*/
                        b.v_price_int AS v_unitprice,
                        b.id_product,
                        d_berlaku,
                        CASE
                            WHEN d_akhir ISNULL THEN '$dakhir'
                            ELSE d_akhir
                        END AS d_akhir
                    FROM
                        tr_harga_makloon_supplier a
                    INNER JOIN tr_harga_makloon_supplier_item b ON
                        (b.id_harga = a.id)
                    WHERE
                        a.f_status = 't'
                        AND a.id_company = '$idcompany' ) AS x
                WHERE
                    x.d_berlaku <= '$dberlaku'
                    AND x.d_akhir >= '$dberlaku' ) x ON
                (x.id_supplier = aa.id_partner 
                /*AND aa.id_type_makloon = x.id_type_makloon*/
                AND x.id_product = a.id_material_list)
            INNER JOIN (
                SELECT
                    *
                FROM
                    (
                    SELECT
                        a.id_supplier,
                        /*a.id_type_makloon,*/
                        b.v_price_int AS v_unitprice,
                        b.id_product,
                        d_berlaku,
                        CASE
                            WHEN d_akhir ISNULL THEN '$dakhir'
                            ELSE d_akhir
                        END AS d_akhir
                    FROM
                        tr_harga_makloon_supplier a
                    INNER JOIN tr_harga_makloon_supplier_item b ON
                        (b.id_harga = a.id)
                    WHERE
                        a.f_status = 't'
                        AND a.id_company = '$idcompany' ) AS y
                WHERE
                    y.d_berlaku <= '$dberlaku'
                    AND y.d_akhir >= '$dberlaku' ) y ON
                (y.id_supplier = aa.id_partner
                /*AND aa.id_type_makloon = y.id_type_makloon*/
                AND y.id_product = a.id_material)
            WHERE
                id_document = $id
            ORDER BY
                1,
                2
            ",
            FALSE
        );
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/    

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_makloon');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$destimate,$ibagian,$ibagianreceive,$idtype,$idpartner,$eremark)
    {
        $data = array(
            'id'                => $id,
            'id_company'        => $this->id_company,
            'i_bagian'          => $ibagian,
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'd_estimate'        => $destimate,
            'i_bagian_receive'  => $ibagianreceive,
            'id_type_makloon'   => $idtype,
            'id_supplier'       => $idpartner,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_keluar_makloon', $data);
    }

    public function simpandetail($id,$idmaterial,$nquantity,$idmateriallist,$nquantitylist,$eremark, $id_wip)
    {
        if ($idmateriallist=='' || $idmateriallist==null) {
            $idmateriallist = $idmaterial;
        }
        if ($nquantitylist=='' || $nquantitylist==null) {
            $nquantitylist = $nquantity;
        }
        $data = array(
            'id_document'           => $id,
            'id_material'           => $idmaterial,
            'n_quantity'            => $nquantity,
            'n_quantity_sisa'       => $nquantity,
            'id_material_list'      => $idmateriallist,
            'n_quantity_list'       => $nquantitylist,
            'n_quantity_list_sisa'  => $nquantitylist,
            'e_remark'              => $eremark,
            'id_product_wip'        => $id_wip,
        );
        $this->db->insert('tm_keluar_makloon_item', $data);
    }

    public function simpandetailkeluar($id, $id_keluar, $id_wip, $idmateriallist, $nquantitylist, $eremark)
    {
        $data = array(
            'id_document'           => $id,
            'id_keluar'             => $id_keluar,
            'id_product'            => $id_wip,
            'id_material_keluar'    => $idmateriallist,
            'n_quantity_keluar'     => $nquantitylist,
            'e_remark'              => $eremark,
        );
        $this->db->insert('tm_sj_makloon_keluar_item', $data);
    }

    public function simpandetailmasuk($id, $id_keluar, $id_wip, $idmateriallist, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa)
    {
        $data = array(
            'id_document'           => $id,
            'id_keluar'             => $id_keluar,
            'id_product'            => $id_wip,
            'id_material_keluar'    => $idmateriallist,
            'id_material_masuk'     => $idmateriallist2,
            'n_quantity_masuk'      => $nquantitylist2,
            'n_quantity_sisa'       => $nquantitylist2sisa,
        );
        $this->db->insert('tm_sj_makloon_keluar_masuk_item', $data);
    }

    /* public function simpandetail($id,$idreff,$idmaterial,$nqty,$idmateriallist,$nqtylist,$eremark,$vunitprice,$vunitpricelist)
    {
        $data = array(
            'id_company'            => $this->id_company,
            'id_document'           => $id,
            'id_material'           => $idmaterial,
            'id_document_reff'      => $idreff,
            'n_quantity'            => $nqty,
            'n_quantity_sisa'       => $nqty,
            'id_material_list'      => $idmateriallist,
            'n_quantity_list'       => $nqtylist,
            'n_quantity_list_sisa'  => $nqtylist,
            'e_remark'              => $eremark,
            'v_unitprice'           => $vunitprice,
            'v_unitprice_list'      => $vunitpricelist,
        );
        $this->db->insert('tm_keluar_makloon_item', $data);
    } */

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/
    
    public function dataedit($id)
    {
        return $this->db->query("SELECT a.*, b.e_bagian_name, c.e_bagian_name e_bagian_receive_name, d.e_supplier_name, e.e_type_makloon_name, to_char(a.d_document, 'dd-mm-yyyy') AS date_document, to_char(a.d_estimate, 'dd-mm-yyyy') AS date_estimate
            FROM tm_keluar_makloon a
            LEFT JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            LEFT JOIN tr_bagian c ON (c.i_bagian = a.i_bagian_receive AND a.id_company = c.id_company)
            LEFT JOIN tr_supplier d ON (d.id = a.id_supplier)
            LEFT JOIN tr_type_makloon e ON (e.id = a.id_type_makloon)
            WHERE a.id = '$id'");
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("SELECT b.i_product_wip, b.e_product_wipname, c.i_material, c.e_material_name, cc.e_satuan_name, d.i_material i_material_list, d.e_material_name e_material_name_list, dd.e_satuan_name e_satuan_name_list, a.* 
        FROM tm_keluar_makloon_item a
        LEFT JOIN tr_product_wip b ON (b.id = a.id_product_wip)
        LEFT JOIN tr_material c ON (c.id = a.id_material)
        LEFT JOIN tr_satuan cc ON (cc.i_satuan_code = c.i_satuan_code AND c.id_company = cc.id_company)
        LEFT JOIN tr_material d ON (d.id = a.id_material_list)
        LEFT JOIN tr_satuan dd ON (dd.i_satuan_code = d.i_satuan_code AND d.id_company = dd.id_company)
        WHERE id_document = '$id'
        ORDER BY b.i_product_wip, c.i_material, d.i_material");
    }

    public function datadetailprod($id)
    {
        return $this->db->query("SELECT DISTINCT a.id_product, b.i_product_wip, b.e_product_wipname FROM tm_sj_makloon_keluar_item a inner join tr_product_wip b ON (b.id = a.id_product) WHERE a.id_document = '$id' order by a.id_product DESC");
    }

    public function dataeditdetailkeluarmasuk($id)
    {
        // return $this->db->query("
        //     SELECT * FROM (
        //         SELECT b.id AS id_product, b.i_product_wip, b.e_product_wipname, d.e_color_name, c.i_material, c.e_material_name, cc.e_satuan_name, a.id_document, a.id_keluar, a.id_material_keluar as id_material, a.n_quantity_keluar AS n_quantity_keluar, 0 AS n_quantity_masuk, a.v_harga AS v_harga, 0 AS n_quantity_sisa, a.e_remark AS e_remark 
        //         FROM tm_sj_makloon_keluar_item a
        //         INNER JOIN tr_product_wip b ON (b.id = a.id_product)
        //         INNER JOIN tr_color d ON (d.i_color = b.i_color AND d.id_company = b.id_company)
        //         LEFT JOIN tr_material c ON (c.id = a.id_material_keluar)
        //         LEFT JOIN tr_satuan cc ON (cc.i_satuan_code = c.i_satuan_code AND c.id_company = cc.id_company)
        //         WHERE a.id_document = '$id'
        //         UNION ALL
        //         SELECT DISTINCT bb.id, bb.i_product_wip, bb.e_product_wipname, d.e_color_name, c.i_material, c.e_material_name, cc.e_satuan_name, a.id_document, a.id_keluar, a.id_material_masuk as id_material, 0 AS n_quantity_keluar, a.n_quantity_masuk AS n_quantity_masuk, 0 AS v_harga, a.n_quantity_sisa AS n_quantity_sisa, NULL AS e_remark
        //         FROM tm_sj_makloon_keluar_masuk_item a
        //         LEFT JOIN tm_sj_makloon_keluar_item b ON (b.id_keluar = a.id_keluar)
        //         INNER JOIN tr_product_wip bb ON (bb.id = b.id_product)
        //         INNER JOIN tr_color d ON (d.i_color = bb.i_color AND d.id_company = bb.id_company)
        //         LEFT JOIN tr_material c ON (c.id = a.id_material_masuk)
        //         LEFT JOIN tr_satuan cc ON (cc.i_satuan_code = c.i_satuan_code AND c.id_company = cc.id_company)
        //         WHERE a.id_document = '$id'
        //     ) AS x;
        // ");
        return $this->db->query("SELECT
            a.id,
            b.id,
            a.id_document,
            a.id_keluar,
            a.id_product,
            bb.i_product_wip,
            bb.e_product_wipname,
            d.e_color_name,
            a.id_material_keluar,
            c.i_material as i_material_keluar,
            c.e_material_name as e_material_name_keluar,
            cc.e_satuan_name as e_satuan_name_keluar,
            a.n_quantity_keluar,
            a.v_harga,
            b.id_material_masuk,
            m.i_material as i_material_masuk,
            m.e_material_name as e_material_name_masuk,
            dd.e_satuan_name as e_satuan_name_masuk,
            b.n_quantity_masuk,
            a.e_remark
        FROM
            tm_sj_makloon_keluar_item a
        INNER JOIN tm_sj_makloon_keluar_masuk_item b ON
            (a.id_document = b.id_document
                AND a.id_keluar = b.id_keluar
                AND (a.id_material_keluar = b.id_material_keluar
                    OR (a.id_material_keluar IS NULL
                        AND b.id_material_keluar IS NULL) ))
        LEFT JOIN tr_product_wip bb ON
            (bb.id = a.id_product)
        LEFT JOIN tr_color d ON
            (d.i_color = bb.i_color
                AND d.id_company = bb.id_company)
        LEFT JOIN tr_material c ON
            (c.id = a.id_material_keluar)
        LEFT JOIN tr_satuan cc ON
            (cc.i_satuan_code = c.i_satuan_code
                AND c.id_company = cc.id_company)
        LEFT JOIN tr_material m ON
            (m.id = b.id_material_masuk)
        LEFT JOIN tr_satuan dd ON
            (dd.i_satuan_code = m.i_satuan_code
                AND m.id_company = dd.id_company)
        WHERE a.id_document = '$id' ORDER BY a.id_keluar, a.id, b.id");
    }

    /*----------  UPDATE DATA  ----------*/   

    public function update($id, $idocument, $ddocument, $destimate, $ibagian, $ibagianreceive, $idtype, $idpartner, $eremark)
    {
        $data = array(
            'i_bagian'          => $ibagian,
            'd_document'        => $ddocument,
            'd_estimate'        => $destimate,
            'i_bagian_receive'  => $ibagianreceive,
            'id_type_makloon'   => $idtype,
            'id_supplier'       => $idpartner,
            'e_remark'          => $eremark,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_keluar_makloon', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/    

    public function deletekeluar($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_sj_makloon_keluar_item');
    }

    public function deletemasuk($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_sj_makloon_keluar_masuk_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    
    /* public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloon', $data);
    } */

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_keluar_makloon a
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->session->userdata('username'),
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_makloon');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloon', $data);
    }

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {

        /*----------  Cek ada data atau tidak  ----------*/
        
        $query = $this->db->query("
            SELECT 
                id_document_reff,
                id_material,
                id_material_list,
                n_quantity,
                n_quantity_list
            FROM 
                tm_keluar_makloon_item
            WHERE id_document = $id
        ", FALSE);

        /*----------  Jika Data Ada  ----------*/
        
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {

                /*----------  Cek Sisa Di Item Tidak Kurang Dari Pemenuhan  ----------*/
                
                $ceksisa1 = $this->db->query("
                    SELECT 
                        n_quantity_sisa, n_quantity_list_sisa
                    FROM 
                        tm_memo_bb_item
                    WHERE 
                        id_document = $key->id_document_reff
                        AND id_material = $key->id_material
                        AND id_material_list = $key->id_material_list
                        AND n_quantity_sisa >= $key->n_quantity
                        AND n_quantity_list_sisa >= $key->n_quantity_list
                ", FALSE);
                if ($ceksisa1->num_rows()>0) {

                    /*----------  Update Sisa Di Packing  ----------*/
                    
                    $this->db->query("
                        UPDATE 
                            tm_memo_bb_item
                        SET 
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity,
                            n_quantity_list_sisa = n_quantity_list_sisa - $key->n_quantity_list
                        WHERE 
                            id_document = $key->id_document_reff
                            AND id_material = $key->id_material
                        AND id_material_list = $key->id_material_list
                        AND n_quantity_sisa >= $key->n_quantity
                        AND n_quantity_list_sisa >= $key->n_quantity_list
                    ", FALSE);
                }else{
                    die();
                }
            }
        }
    }

    /*----------  SIMPAN KE JURNAL  ----------*/

    public function simpanjurnal($id,$title)
    {
        $this->db->query("
            INSERT
                INTO
                tm_jurnal_dokumen (id_company,
                id_document,
                i_document,
                i_periode,
                id_material,
                id_product_wip,
                id_product_base,
                i_coa,
                e_coa,
                id_payment_type,
                v_price,
                n_quantity_material,
                n_quantity_wip,
                n_quantity_base,
                n_total,
                title)
            SELECT
                a.id_company,
                b.id_document,
                a.i_document,
                to_char(a.d_document, 'yyyymm') AS i_periode,
                b.id_material_list AS id_material,
                NULL AS id_product_wip,
                NULL AS id_product_base,
                '110-81000' AS i_coa,
                'BAHAN BAKU (BENANG/KAIN, QUILTING, EMBOSS)' AS e_coa,
                NULL AS id_payment_type,
                b.v_unitprice_list AS v_price,
                b.n_quantity_list AS n_quantity_material,
                NULL AS n_quatity_wip,
                NULL AS n_quatity_base,
                b.v_unitprice_list * b.n_quantity_list AS total,
                '$title' AS title
            FROM
                tm_keluar_makloon a
            INNER JOIN tm_keluar_makloon_item b ON
                (b.id_document = a.id)
            WHERE
                a.id = $id
        ", FALSE);
    }    
}
/* End of file Mmaster.php */