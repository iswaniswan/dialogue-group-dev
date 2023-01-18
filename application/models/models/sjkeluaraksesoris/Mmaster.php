<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

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

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_penjualan_ak a
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND id_company = $this->company
                        AND username = '$this->username')

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
                        AND id_company = $this->company
                        AND username = '$this->username')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("   
            SELECT
                DISTINCT 0 AS NO,
                a.id AS id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                CASE
                    WHEN a.e_partner_type = 'supplier' THEN e_supplier_name
                    WHEN a.e_partner_type = 'customer' THEN e_customer_name
                    WHEN a.e_partner_type = 'karyawan' THEN e_nama_karyawan
                    WHEN a.e_partner_type = 'bagian' THEN e_bagian_name
                END AS e_partner_name,
                UPPER(e_type_reff) AS e_type_reff,
                CASE
                    WHEN e_type_reff = 'memo' THEN j.i_document
                    ELSE i.i_document
                END AS i_referensi,
                e_status_name AS e_status,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_penjualan_ak a
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            LEFT JOIN tr_supplier e ON
                (e.id = a.id_partner)
            LEFT JOIN tr_customer f ON
                (f.id = a.id_partner)
            LEFT JOIN tr_karyawan g ON
                (g.id = a.id_partner)
            LEFT JOIN tr_bagian h ON
                (h.id = a.id_partner)
            LEFT JOIN tm_konversi_pinjaman_ak i ON
                (i.id = a.id_document_reff)
            LEFT JOIN tm_memo_ak j ON
                (j.id = a.id_document_reff)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company
                $and
                $bagian
            ORDER BY
                a.id
            ", FALSE
        );

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = $data['id'];
            $i_status   = trim($data['i_status']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
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
        $datatables->hide('e_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /*----------  BACA BAGIAN PEMBUAT  ----------*/    

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('a.f_status', 't');
        $this->db->where('i_level', $this->level);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  BACA PARTNER YANG ADA DITRANSAKSI  ----------*/    

    public function partner($cari)
    {
        return $this->db->query("
            SELECT DISTINCT
                *
            FROM
                (
                SELECT
                    a.id_partner AS id,
                    CASE
                        WHEN e_partner_type = 'supplier' THEN e_supplier_name
                        WHEN e_partner_type = 'customer' THEN e_customer_name
                        WHEN e_partner_type = 'karyawan' THEN e_nama_karyawan
                        WHEN e_partner_type = 'bagian' THEN e_bagian_name
                    END AS e_name,
                    e_partner_type AS grouppartner
                FROM
                    tm_memo_ak a
                INNER JOIN tm_memo_ak_item b ON
                    (b.id_document = a.id)
                LEFT JOIN tr_supplier e ON
                    (e.id = a.id_partner)
                LEFT JOIN tr_customer f ON
                    (f.id = a.id_partner)
                LEFT JOIN tr_karyawan g ON
                    (g.id = a.id_partner)
                LEFT JOIN tr_bagian h ON
                    (h.id = a.id_partner)
                WHERE
                    a.i_status = '6'
                    AND a.id_jenis = 2
                    AND a.id_company = $this->company
                    AND b.n_quantity_sisa > 0
            UNION ALL
                SELECT
                    a.id_partner AS id,
                    CASE
                        WHEN e_type_partner = 'supplier' THEN e_supplier_name
                        WHEN e_type_partner = 'customer' THEN e_customer_name
                        WHEN e_type_partner = 'karyawan' THEN e_nama_karyawan
                        WHEN e_type_partner = 'bagian' THEN e_bagian_name
                    END AS e_name,
                    e_type_partner AS grouppartner
                FROM
                    tm_konversi_pinjaman_ak a
                INNER JOIN tm_konversi_pinjaman_ak_item b ON
                    (b.id_document = a.id)
                LEFT JOIN tr_supplier e ON
                    (e.id = a.id_partner)
                LEFT JOIN tr_customer f ON
                    (f.id = a.id_partner)
                LEFT JOIN tr_karyawan g ON
                    (g.id = a.id_partner)
                LEFT JOIN tr_bagian h ON
                    (h.id = a.id_partner)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = $this->company
                    AND b.n_quantity_sisa > 0) AS x
            ORDER BY
                3,
                2
        ", FALSE);
    }    

    /*----------  RUNNING NO DOKUMEN  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_penjualan_ak 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = $this->company
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
                tm_penjualan_ak
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
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

    /*----------  CEK NO DOKUMEN  ----------*/
    
    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_penjualan_ak');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI DATA REFERENSI  ----------*/
    
    public function datareferensi($cari,$idpartner,$itype)
    {
        return $this->db->query("            
            SELECT DISTINCT
                *
            FROM
                (
                SELECT
                    a.id,
                    i_document,
                    'memo' AS e_type
                FROM
                    tm_memo_ak a
                INNER JOIN tm_memo_ak_item b ON
                    (b.id_document = a.id)
                WHERE
                    i_status = '6'
                    AND id_jenis = 2
                    AND a.id_company = $this->company
                    AND id_partner = $idpartner
                    AND e_partner_type = '$itype'
                    AND n_quantity_sisa > 0
                    AND i_document ILIKE '%$cari%'
            UNION ALL
                SELECT
                    a.id,
                    i_document,
                    'konversi' AS e_type
                FROM
                    tm_konversi_pinjaman_ak a
                INNER JOIN tm_konversi_pinjaman_ak_item b ON
                    (b.id_document = a.id)
                WHERE
                    i_status = '6'
                    AND a.id_company = $this->company
                    AND id_partner = $idpartner
                    AND e_type_partner = '$itype'
                    AND n_quantity_sisa > 0
                    AND i_document ILIKE '%$cari%'
            ) AS x
            ORDER BY
                3,
                2
        ", FALSE);
    }

    /*----------  DETAIL DATA REFERENSI  ----------*/    

    public function detailreferensi($id,$type,$ipartner,$grouppartner)
    {
        return $this->db->query("
            SELECT
                *
            FROM
                (
                SELECT
                    a.id,
                    'memo' AS e_type,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                    b.id_material,
                    c.i_material,
                    c.e_material_name,
                    d.e_satuan_name,
                    b.n_quantity,
                    b.n_quantity_sisa
                FROM
                    tm_memo_ak a
                INNER JOIN tm_memo_ak_item b ON
                    (b.id_document = a.id)
                INNER JOIN tr_material c ON
                    (c.id = b.id_material)
                INNER JOIN tr_satuan d ON
                    (d.i_satuan_code = c.i_satuan_code
                    AND c.id_company = d.id_company)
                WHERE
                    i_status = '6'
                    AND id_jenis = 2
                    AND a.id_company = $this->company
                    AND id_partner = $ipartner
                    AND e_partner_type = '$grouppartner'
                    AND n_quantity_sisa > 0
                    AND a.id = $id
            UNION ALL
                SELECT
                    a.id,
                    'konversi' AS e_type,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                    b.id_material,
                    c.i_material,
                    c.e_material_name,
                    d.e_satuan_name,
                    b.n_quantity,
                    b.n_quantity_sisa
                FROM
                    tm_konversi_pinjaman_ak a
                INNER JOIN tm_konversi_pinjaman_ak_item b ON
                    (b.id_document = a.id)
                INNER JOIN tr_material c ON
                    (c.id = b.id_material)
                INNER JOIN tr_satuan d ON
                    (d.i_satuan_code = c.i_satuan_code
                    AND c.id_company = d.id_company)
                WHERE
                    i_status = '6'
                    AND a.id_company = $this->company
                    AND id_partner = $ipartner
                    AND e_type_partner = '$grouppartner'
                    AND n_quantity_sisa > 0
                    AND a.id = $id) AS x
            WHERE
                x.id = $id
                AND e_type = '$type'
            ORDER BY
                3,
                2 
            ",
            FALSE
        );
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/    

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_penjualan_ak');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$ipartner,$etypepartner,$ireff,$etypereff,$eremark)
    {
        $data = array(
            'id'                => $id,
            'id_company'        => $this->company,
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_partner'        => $ipartner,
            'e_partner_type'    => $etypepartner,
            'id_document_reff'  => $ireff,
            'e_type_reff'       => $etypereff,
            'e_remark'          => $eremark,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_penjualan_ak', $data);
    }

    public function simpandetail($id,$idreff,$idproduct,$nquantity,$eremark)
    {
        $data = array(
            'id_company'         => $this->company,
            'id_document'        => $id,
            'id_document_reff'   => $idreff,
            'id_material'        => $idproduct,
            'n_quantity'         => $nquantity,
            'n_quantity_sisa'    => $nquantity,
            'e_remark'           => $eremark,
        );
        $this->db->insert('tm_penjualan_ak_item', $data);
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/
    
    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_document,
                a.i_bagian,
                b.e_bagian_name,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.e_partner_type,
                a.id_partner,
                CASE
                    WHEN a.e_partner_type = 'supplier' THEN e_supplier_name
                    WHEN a.e_partner_type = 'customer' THEN e_customer_name
                    WHEN a.e_partner_type = 'karyawan' THEN e_nama_karyawan
                    WHEN a.e_partner_type = 'bagian' THEN h.e_bagian_name
                END AS e_partner_name,
                a.id_document_reff,
                e_type_reff,
                CASE
                    WHEN e_type_reff = 'memo' THEN j.i_document
                    ELSE i.i_document
                END AS i_referensi,
                CASE
                    WHEN e_type_reff = 'memo' THEN to_char(j.d_document, 'dd-mm-yyyy')
                    ELSE to_char(i.d_document, 'dd-mm-yyyy')
                END AS d_referensi,
                a.e_remark,
                a.i_status
            FROM
                tm_penjualan_ak a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            LEFT JOIN tr_supplier e ON
                (e.id = a.id_partner)
            LEFT JOIN tr_customer f ON
                (f.id = a.id_partner)
            LEFT JOIN tr_karyawan g ON
                (g.id = a.id_partner)
            LEFT JOIN tr_bagian h ON
                (h.id = a.id_partner)
            LEFT JOIN tm_konversi_pinjaman_ak i ON
                (i.id = a.id_document_reff)
            LEFT JOIN tm_memo_ak j ON
                (j.id = a.id_document_reff)
            WHERE
                a.id = $id
            ", FALSE
        );
        return $this->db->get();
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
            SELECT
                a.id_material,
                i_material,
                e_material_name,
                e_satuan_name,
                a.e_remark,
                a.n_quantity,
                CASE
                    WHEN b.e_type_reff = 'memo' THEN e.n_quantity
                    ELSE f.n_quantity
                END AS n_quantity_reff,
                CASE
                    WHEN b.e_type_reff = 'memo' THEN e.n_quantity_sisa
                    ELSE f.n_quantity_sisa
                END AS n_quantity_sisa_reff
            FROM
                tm_penjualan_ak_item a
            INNER JOIN tm_penjualan_ak b ON
                (b.id = a.id_document)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN tr_satuan d ON
                (d.i_satuan_code = c.i_satuan_code
                AND c.id_company = d.id_company)
            LEFT JOIN tm_memo_ak_item e ON
                (e.id_document = a.id_document_reff
                AND a.id_material = e.id_material)
            LEFT JOIN tm_konversi_pinjaman_ak_item f ON
                (f.id_document = a.id_document_reff
                AND a.id_material = f.id_material)
            WHERE
                a.id_document = $id
            ORDER BY
                2
            ", FALSE
        );
    }

    /*----------  UPDATE DATA  ----------*/   

    public function update($id,$idocument,$ddocument,$ibagian,$ipartner,$etypepartner,$ireff,$etypereff,$eremark)
    {
        $data = array(
            'id_company'        => $this->company,
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_partner'        => $ipartner,
            'e_partner_type'    => $etypepartner,
            'id_document_reff'  => $ireff,
            'e_type_reff'       => $etypereff,
            'e_remark'          => $eremark,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_penjualan_ak', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/    

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_penjualan_ak_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    
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
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_penjualan_ak', $data);
    }

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {

        /*----------  Cek ada dipacking  ----------*/
        
        $itype = $this->db->query("
            SELECT
                e_type_reff
            FROM
                tm_penjualan_ak
            WHERE
                id = $id
        ", FALSE)->row()->e_type_reff;

        $query = $this->db->query("
            SELECT 
                id_document_reff,
                id_material,
                n_quantity
            FROM 
                tm_penjualan_ak_item
            WHERE id_document = $id
        ", FALSE);

        /*----------  Jika 'MEMO' Update Memo, Jika Tidak Ada Update Konversi  ----------*/
        
        if ($itype=='memo') {
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {

                    /*----------  Cek Sisa Di Memo Tidak Kurang Dari Kirim  ----------*/
                    
                    $ceksisa1 = $this->db->query("
                        SELECT 
                            n_quantity_sisa
                        FROM 
                            tm_memo_ak_item
                        WHERE 
                            id_document = $key->id_document_reff
                            AND id_material = $key->id_material
                            AND n_quantity_sisa >= $key->n_quantity
                    ", FALSE);
                    if ($ceksisa1->num_rows()>0) {

                        /*----------  Update Sisa Memo  ----------*/
                        
                        $this->db->query("
                            UPDATE 
                                tm_memo_ak_item
                            SET 
                                n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                            WHERE 
                                id_document = $key->id_document_reff
                                AND id_material = $key->id_material
                                AND n_quantity_sisa >= $key->n_quantity
                        ", FALSE);
                    }else{
                        die();
                    }
                }
            }
        }else{
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {

                    /*----------  Cek Sisa Konversi Tidak Kurang Dari Pemenuhan  ----------*/

                    $ceksisa2 = $this->db->query("
                        SELECT 
                            n_quantity_sisa
                        FROM 
                            tm_konversi_pinjaman_ak_item
                        WHERE 
                            id_document = $key->id_document_reff
                            AND id_material = $key->id_material
                            AND n_quantity_sisa >= $key->n_quantity
                    ", FALSE);
                    if ($ceksisa2->num_rows()>0) {

                        /*----------  Update Sisa Konversi  ----------*/

                        $this->db->query("
                            UPDATE 
                                tm_konversi_pinjaman_ak_item
                            SET 
                                n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                            WHERE 
                                id_document = $key->id_document_reff
                                AND id_material = $key->id_material
                                AND n_quantity_sisa >= $key->n_quantity
                        ", FALSE);
                    }else{
                        die();
                    }
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
                b.id_material AS id_material,
                NULL AS id_product_wip,
                NULL AS id_product_base,
                '110-81000' AS i_coa,
                'PENJUALAN AKSESORIS' AS e_coa,
                NULL AS id_payment_type,
                NULL AS v_price,
                b.n_quantity AS n_quantity_material,
                NULL AS n_quatity_wip,
                NULL AS n_quatity_base,
                NULL AS total,
                '$title' AS title
            FROM
                tm_penjualan_ak a
            INNER JOIN tm_penjualan_ak_item b ON
                (b.id_document = a.id)
            WHERE
                a.id = $id
        ", FALSE);
    }    
    
}
/* End of file Mmaster.php */