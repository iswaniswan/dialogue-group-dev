<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DAFTAR SJ MAKLOON  ----------=*/    
    
    function data($i_menu,$folder,$dfrom,$dto){
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_keluar_makloon_unitjahit
            WHERE
                i_status <> '5'
                AND id_company = '".$this->session->userdata('id_company')."'
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND username = '".$this->session->userdata('username')."')

        ", FALSE);
        if ($this->session->userdata('i_departement')=='1') {
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
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND username = '".$this->session->userdata('username')."')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                                SELECT
                                    0 AS NO,
                                    a.id,
                                    i_document,
                                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                                    to_char(d_back, 'dd-mm-yyyy') AS d_back,
                                    b.e_supplier_name,
                                    a.e_remark,
                                    e_status_name,
                                    label_color,
                                    a.i_status,
                                    '$i_menu' AS i_menu,
                                    '$folder' AS folder,
                                    '$dfrom' AS dfrom,
                                    '$dto' AS dto
                                FROM
                                    tm_keluar_makloon_unitjahit a
                                INNER JOIN tr_supplier b ON
                                    (b.id = a.id_supplier)
                                INNER JOIN tr_status_document d ON
                                    (d.i_status = a.i_status)
                                WHERE
                                    a.i_status <> '5'
                                    AND a.id_company = '".$this->session->userdata('id_company')."'
                                    $and
                                    $bagian
                                ORDER BY
                                    a.id
                            ", FALSE);
            
        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = $data['id'];
            $i_status = trim($data['i_status']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }
    
    /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/
    
    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));        
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function typemakloon($i_menu)
    {
        $this->db->select('b.id, b.e_type_makloon_name');
        $this->db->from('tr_makloon_menu a');
        $this->db->join('tr_type_makloon b','b.id = a.id_makloon AND a.id_company = b.id_company','inner');
        $this->db->where('i_menu', $i_menu);
        $this->db->where('b.f_status', 't');
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        return $this->db->get();
    }

    /*----------  CEK KODE  ----------*/

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_makloon_unitjahit');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  RUNNING NO DOK  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_keluar_makloon_unitjahit 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJ';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_keluar_makloon_unitjahit
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
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
    
    /*----------  BACA PARTNER (SUPPLIER)  ----------*/

    public function partner($cari,$i_menu,$itype)
    {
        return $this->db->query("
                                    SELECT
                                        DISTINCT b.id,
                                        b.e_supplier_name
                                    FROM
                                        tr_supplier_makloon a
                                    INNER JOIN tr_supplier b ON
                                        (b.i_supplier = a.i_supplier
                                        AND a.id_company = b.id_company)
                                    INNER JOIN tr_type_makloon c ON
                                        (c.i_type_makloon = a.i_type_makloon
                                        AND a.id_company = c.id_company)
                                    WHERE
                                        b.f_status = 't'
                                        AND (e_supplier_name ILIKE '%$cari%')
                                        AND c.id IN (
                                        SELECT
                                            id_makloon
                                        FROM
                                            tr_makloon_menu
                                        WHERE
                                            id_company = '".$this->session->userdata('id_company')."'
                                            AND i_menu = '$i_menu')
                                        AND a.id_company = '".$this->session->userdata('id_company')."'
                                    ORDER BY
                                        b.e_supplier_name
                                ", FALSE);
    }

    /*----------  CARI BARANG  ----------*/

    public function product($cari, $partner, $ddocument, $idtype)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("            
                                  /*SELECT DISTINCT 
                                      a.id,
                                      a.i_product_wip,
                                      UPPER(a.e_product_wipname) AS e_product_wipname,
                                      b.e_color_name
                                  FROM
                                      tr_product_wip a
                                  INNER JOIN tr_color b ON
                                      (a.i_color = b.i_color
                                      AND a.id_company = b.id_company)
                                  WHERE
                                      a.id_company = '$idcompany'
                                      AND a.f_status = 't'
                                      AND b.f_status = 't'
                                      AND (a.i_product_wip ILIKE '%$cari%'
                                      OR a.e_product_wipname ILIKE '%$cari%')
                                  ORDER BY
                                      a.i_product_wip ASC*/

                                SELECT
                                    z.id_product,
                                    z.i_product,
                                    z.e_product,
                                    z.v_price_int AS v_price,
                                    z.e_color_name
                                FROM
                                    (
                                    SELECT
                                        a.id_supplier,
                                        b.id_product,
                                        b.i_product,
                                        c.e_product,
                                        b.v_price_int,
                                        d_berlaku,
                                        a.id_type_makloon,
                                        a.id_company,
                                        h.e_color_name,
                                        CASE
                                            WHEN d_akhir IS NOT NULL THEN d_akhir
                                            ELSE '5000-01-01'
                                        END AS d_akhir_tmp
                                    FROM
                                        tr_harga_makloon_supplier a
                                    INNER JOIN tr_harga_makloon_supplier_item b ON
                                        (a.id = b.id_harga
                                        AND a.id_company = b.id_company)
                                    INNER JOIN (
                                        SELECT
                                            id AS id_product,
                                            i_product_wip AS i_product,
                                            e_product_wipname AS e_product,
                                            i_type_code AS i_type_code,
                                            id_company,
                                            i_color
                                        FROM
                                            tr_product_wip
                                        WHERE
                                            id_company = '$idcompany'
                                            AND f_status = 't') c ON
                                        (b.id_product = c.id_product
                                        AND b.i_product = c.i_product
                                        AND b.id_company = c.id_company)
                                    INNER JOIN tr_supplier g ON
                                        (a.id_supplier = g.id
                                        AND a.id_company = g.id_company)
                                    INNER JOIN tr_color h ON
                                        (c.i_color = h.i_color
                                        AND c.id_company = h.id_company)
                                    WHERE
                                        a.id_company = '$idcompany' ) AS z
                                WHERE
                                    z.d_berlaku <= to_date('$ddocument', 'dd-mm-yyyy')
                                    AND z.d_akhir_tmp >= to_date('$ddocument', 'dd-mm-yyyy')
                                    AND z.id_type_makloon = '$idtype'
                                    AND z.id_supplier = '$partner'
                                    AND z.id_company = '$idcompany'
                                    AND (z.e_product ILIKE '%$cari%')
                                ORDER BY
                                    z.i_product
                                ", FALSE);
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id,$partner,$ddocument,$idtype)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("            
                                  /*SELECT
                                      a.id AS id_product_wip,
                                      a.i_product_wip,
                                      UPPER(a.e_product_wipname) AS e_product_wipname,
                                      d.id AS id_color,
                                      d.e_color_name,
                                      c.id AS id_material,
                                      c.i_material,
                                      UPPER(c.e_material_name) AS e_material_name
                                  FROM
                                      tr_product_wip a
                                  INNER JOIN tr_polacutting_new b ON
                                      (a.id = b.id_product_wip
                                      AND a.id_company = b.id_company)
                                  INNER JOIN tr_material c ON
                                      (b.id_material = c.id
                                      AND a.id_company = c.id_company)
                                  INNER JOIN tr_color d ON
                                      (a.i_color = d.i_color
                                      AND a.id_company = d.id_company)
                                  WHERE
                                      a.f_status = 't'
                                      AND a.id = '$id'
                                      AND a.id_company = '".$this->session->userdata('id_company')."'
                                      AND b.f_marker_utama = 't'
                                  ORDER BY
                                      a.i_product_wip,
                                      c.i_material ASC*/

                                SELECT
                                    z.id_product_wip,
                                    z.i_product_wip,
                                    z.e_product_wipname,
                                    z.v_price_int AS v_price,
                                    z.id_color,
                                    z.e_color_name,
                                    z.id_material,
                                    z.i_material,
                                    z.e_material_name,
                                    z.n_quantity,
                                    z.i_satuan,
                                    z.e_satuan_name
                                FROM
                                    (
                                    SELECT
                                        a.id_supplier,
                                        b.id_product as id_product_wip,
                                        b.i_product as i_product_wip,
                                        c.e_product_wipname,
                                        b.v_price_int,
                                        d_berlaku,
                                        a.id_type_makloon,
                                        a.id_company,
                                        c.id_color,
                                        c.e_color_name,
                                        c.id_material,
                                        c.i_material,
                                        c.e_material_name,
                                        c.n_quantity,
                                        c.i_satuan,
                                        c.e_satuan_name,
                                        CASE
                                            WHEN d_akhir IS NOT NULL THEN d_akhir
                                            ELSE '5000-01-01'
                                        END AS d_akhir_tmp
                                     FROM
                                        tr_harga_makloon_supplier a
                                    inner JOIN tr_harga_makloon_supplier_item b ON
                                        (a.id = b.id_harga
                                        AND a.id_company = b.id_company)
                                    inner JOIN (
                                        SELECT
                                            a.id AS id_product_wip,
                                            a.i_product_wip,
                                            a.e_product_wipname AS e_product_wipname,
                                            d.id AS id_color,
                                            d.e_color_name,
                                            c.id AS id_material,
                                            c.i_material,
                                            c.e_material_name AS e_material_name,
                                            c.id_company,
                                            b.n_quantity,
                                            c.i_satuan_code as i_satuan,
                                            e.e_satuan_name
                                        FROM
                                            tr_product_wip a
                                            INNER JOIN (
                                                SELECT a.id_product_wip, id_material, a.f_marker_utama,
                                                    case 
                                                        when COALESCE(c.v_panjang_bis,0) > 0 then ((1/a.v_set) * a.v_gelar) + 
                                                        (COALESCE (v_bisbisan,0)/COALESCE (v_panjang_bis,0)) 
                                                        else ((1/a.v_set) * a.v_gelar) 
                                                    end as n_quantity
                                                FROM tr_polacutting_new a
                                                left join tr_material_bisbisan c on (c.id = a.id_bisbisan and a.id_material = c.id_material)
                                                INNER JOIN tr_type_makloon b ON (b.id = ANY(a.id_type_makloon))
                                                WHERE id_type_makloon NOTNULL AND b.e_type_makloon_name ILIKE '%CUTTING%' and a.f_budgeting = 't' and a.f_status = 't'
                                            ) b ON
                                              (a.id = b.id_product_wip
                                              AND a.id_company = b.id_company)
                                            INNER JOIN tr_material c ON
                                              (b.id_material = c.id
                                              AND a.id_company = c.id_company)
                                            INNER JOIN tr_color d ON
                                              (a.i_color = d.i_color
                                              AND a.id_company = d.id_company)
                                            INNER JOIN tr_satuan e ON
                                               (c.i_satuan_code = e.i_satuan_code
                                               AND c.id_company = e.id_company)
                                        WHERE
                                            a.id_company = '$idcompany'
                                            AND a.f_status = 't') c ON
                                        (b.id_product = c.id_product_wip
                                        AND b.i_product = c.i_product_wip
                                        AND b.id_company = c.id_company)
                                    inner JOIN tr_supplier g ON
                                        (a.id_supplier = g.id
                                        AND a.id_company = g.id_company)
                                    WHERE
                                        a.id_company = '$idcompany' ) AS z
                                WHERE
                                    z.id_product_wip = '$id'
                                    AND z.d_berlaku <= to_date('$ddocument', 'dd-mm-yyyy')
                                    AND z.d_akhir_tmp >= to_date('$ddocument', 'dd-mm-yyyy')
                                    AND z.id_type_makloon = '$idtype'
                                    AND z.id_supplier = '$partner'
                                    AND z.id_company = '$idcompany'
                                    AND b.f_marker_utama = 't'
                                ORDER BY
                                    z.id_product_wip
                                ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_makloon_unitjahit');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$itype,$ipartner,$dback,$iforecast,$dforecast,$eremarkh)
    {
        $data = array(
                        'id'                  => $id,
                        'id_company'          => $this->session->userdata('id_company'),
                        'i_document'          => $idocument,
                        'd_document'          => $ddocument,
                        'i_bagian'            => $ibagian,
                        'id_type_makloon'     => $itype,
                        'id_supplier'         => $ipartner,
                        'd_back'              => $dback,
                        'i_forecast'          => $iforecast,
                        'd_forecast'          => $dforecast,
                        'e_remark'            => $eremarkh,
                        'd_entry'             => current_datetime(),
        );
        $this->db->insert('tm_keluar_makloon_unitjahit', $data);
    }

    public function simpandetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantity,$eremark,$vharga)
    {
        $data = array(
                        'id_company'          => $this->session->userdata('id_company'),
                        'id_document'         => $id,
                        'id_product_wip'      => $idproductwip,
                        'n_quantity_wip'      => $nquantitywip,
                        'n_quantity_wip_sisa' => $nquantitywip,
                        'id_material'         => $idmaterial,
                        'n_quantity'          => $nquantity,
                        'e_remark'            => $eremark,
                        'v_price'             => $vharga,
        );
        $this->db->insert('tm_keluar_makloon_unitjahit_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
                                    SELECT
                                        a.id,
                                        a.i_bagian,
                                        a.i_document,
                                        to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                        to_char(a.d_back, 'dd-mm-yyyy') AS d_back,
                                        a.i_forecast,
                                        to_char(a.d_forecast, 'dd-mm-yyyy') AS d_forecast,
                                        a.id_supplier,
                                        c.e_supplier_name,
                                        a.e_remark,
                                        b.e_bagian_name,
                                        a.i_status
                                    FROM
                                        tm_keluar_makloon_unitjahit a
                                    INNER JOIN tr_bagian b ON
                                        (b.i_bagian = a.i_bagian
                                        AND a.id_company = b.id_company)
                                    INNER JOIN tr_supplier c ON
                                        (c.id = a.id_supplier)
                                    WHERE
                                        a.id = '$id'
                                ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
                                    SELECT
                                        a.id_product_wip,
                                        b.i_product_wip,
                                        b.e_product_wipname,
                                        a.id_material,
                                        c.i_material,
                                        c.e_material_name,
                                        a.n_quantity_wip,
                                        a.n_quantity,
                                        a.e_remark,
                                        d.e_color_name,
                                        a.v_price,
                                        e.e_satuan_name
                                    FROM
                                        tm_keluar_makloon_unitjahit_item a
                                    INNER JOIN tr_product_wip b ON
                                        (b.id = a.id_product_wip)
                                    INNER JOIN tr_material c ON
                                        (c.id = a.id_material)
                                    INNER JOIN tr_color d ON
                                        (d.i_color = b.i_color 
                                        AND b.id_company = d.id_company) 
                                    INNER JOIN tr_satuan e ON
                                        (c.i_satuan_code = e.i_satuan_code
                                        AND c.id_company = e.id_company)           
                                    WHERE
                                        a.id_document = '$id'
                                    ORDER BY
                                        a.id_product_wip, 
                                        c.i_material,
                                        b.i_product_wip
                                    ASC", FALSE);
    }

    /*----------  UPDATE DATA  ----------*/    

    public function update($id,$idocument,$ddocument,$ibagian,$ipartner,$dback,$iforecast,$dforecast,$eremarkh)
    {
        $data = array(
                        'id_company'   => $this->session->userdata('id_company'),
                        'i_document'   => $idocument,
                        'd_document'   => $ddocument,
                        'i_bagian'     => $ibagian,
                        'id_supplier'  => $ipartner,            
                        'd_back'       => $dback,
                        'i_forecast'   => $iforecast,
                        'd_forecast'   => $dforecast,
                        'e_remark'     => $eremarkh,
                        'd_update'     => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloon_unitjahit', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_makloon_unitjahit_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus($id,$istatus)
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
        $this->db->update('tm_keluar_makloon_unitjahit', $data);
    }
    

}
/* End of file Mmaster.php */
