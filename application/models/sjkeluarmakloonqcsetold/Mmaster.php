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
                tm_keluar_makloonqcset
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
                b.e_supplier_name,
                i_permintaan,
                c.e_type_makloon_name,
                e_status_name,
                label_color,
                f.i_level,
                l.e_level_name,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_makloonqcset a
            INNER JOIN tr_supplier b ON
                (b.id = a.id_supplier)
            INNER JOIN tr_type_makloon c ON
                (c.id = a.id_type_makloon)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
                $bagian
            ORDER BY
                a.id", 
        FALSE);
            
        $datatables->edit('i_status', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
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
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status_name');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }
    
    /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/
    
    public function bagian()
    {
        /* 
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
        */

        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement 
            FROM tr_bagian a 
            INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            LEFT JOIN tr_type c on (a.i_type = c.i_type)
            LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
            WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
            ORDER BY 4, 3 ASC NULLS LAST
        ", false);

    }
    
    /*----------  DATA TYPE MAKLOON SESUAI MENU  ----------*/

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

    public function cek_kode($kode,$ibagian,$itype)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_makloonqcset');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_type_makloon', $itype);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  RUNNING NO DOK  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian,$itype)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_keluar_makloonqcset 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_type_makloon = '$itype'
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
                tm_keluar_makloonqcset
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_type_makloon = '$itype'
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

    public function partner($cari,$idtype)
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
                c.id = '$idtype'
                AND b.f_status = 't'
                AND (e_supplier_name ILIKE '%$cari%')
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                b.e_supplier_name
        ", FALSE);
    }

    /*----------  DETAIL SUPPLIER  ----------*/

    public function partnerdetail($id)
    {
        return $this->db->query("
            SELECT
                f_pkp,
                n_diskon
            FROM
                tr_supplier
            WHERE
                id = '$id'
        ", FALSE);
    }    

    /*----------  CARI BARANG  ----------*/

    public function product($cari,$idtype,$idsupplier,$ddocument)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT
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
                    h.i_color,
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
                    /*SELECT
                        id AS id_product,
                        i_material AS i_product,
                        e_material_name AS e_product,
                        i_type_code AS i_type_code,
                        id_company
                    FROM
                        tr_material
                    WHERE
                        id_company = '$idcompany'
                        AND f_status = 't'
                UNION ALL*/
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
                        AND f_status = 't'
                /*UNION ALL
                    SELECT
                        id AS id_product,
                        i_product_base AS i_product,
                        e_product_basename AS e_product,
                        i_type_code AS i_type_code,
                        id_company
                    FROM
                        tr_product_base
                    WHERE
                        id_company = '$idcompany'
                        AND f_status = 't' */) c ON
                    (b.id_product = c.id_product AND b.i_product = c.i_product AND b.id_company = c.id_company)
                INNER JOIN tr_supplier g ON
                    (a.id_supplier = g.id AND a.id_company = g.id_company)
                INNER JOIN 
                    (
                        SELECT i_color, e_color_name FROM tr_color WHERE id_company = '$idcompany' AND f_status = 't' 
                    ) h ON (c.i_color = h.i_color)
                WHERE
                    a.id_company = '$idcompany' ) AS z
            WHERE
                z.d_berlaku <= to_date('$ddocument', 'dd-mm-yyyy')
                AND z.d_akhir_tmp >= to_date('$ddocument', 'dd-mm-yyyy')
                AND z.id_type_makloon = '$idtype'
                AND z.id_supplier = '$idsupplier'
                AND z.id_company = '$idcompany'
                AND (z.e_product ILIKE '%$cari%')
            ORDER BY
                z.i_product
        ", FALSE);
    }

    /*----------  HARGA BARANG  ----------*/

    public function price($id,$idtype,$idsupplier,$ddocument)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("            
            SELECT
                z.id_product,
                z.i_product,
                z.e_product,
                z.v_price_int AS v_price
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
                    /*SELECT
                        id AS id_product,
                        i_material AS i_product,
                        e_material_name AS e_product,
                        i_type_code AS i_type_code,
                        id_company
                    FROM
                        tr_material
                    WHERE
                        id_company = '$idcompany'
                        AND f_status = 't'
                UNION ALL*/
                    SELECT
                        id AS id_product,
                        i_product_wip AS i_product,
                        e_product_wipname AS e_product,
                        i_type_code AS i_type_code,
                        id_company
                    FROM
                        tr_product_wip
                    WHERE
                        id_company = '$idcompany'
                        AND f_status = 't'
                /*UNION ALL
                    SELECT
                        id AS id_product,
                        i_product_base AS i_product,
                        e_product_basename AS e_product,
                        i_type_code AS i_type_code,
                        id_company
                    FROM
                        tr_product_base
                    WHERE
                        id_company = '$idcompany'
                        AND f_status = 't'*/ ) c ON
                    (b.id_product = c.id_product
                    AND b.i_product = c.i_product
                    AND b.id_company = c.id_company)
                INNER JOIN tr_supplier g ON
                    (a.id_supplier = g.id
                    AND a.id_company = g.id_company)
                WHERE
                    a.id_company = '$idcompany' ) AS z
            WHERE
                z.d_berlaku <= to_date('$ddocument', 'dd-mm-yyyy')
                AND z.d_akhir_tmp >= to_date('$ddocument', 'dd-mm-yyyy')
                AND z.id_type_makloon = '$idtype'
                AND z.id_supplier = '$idsupplier'
                AND z.id_company = '$idcompany'
                AND z.id_product = '$id'
            ORDER BY
                z.i_product
        ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_makloonqcset');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$dperkiraan,$ibagian,$itype,$ipartner,$ipermintaan,$dpermintaan,$fpkp,$ndiskon,$eremarkh)
    {
        $data = array(
            'id'              => $id,
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'd_perkiraan'     => $dperkiraan,
            'i_bagian'        => $ibagian,
            'id_type_makloon' => $itype,
            'id_supplier'     => $ipartner,
            'i_permintaan'    => $ipermintaan,
            'd_permintaan'    => $dpermintaan,
            'f_pkp'           => $fpkp,
            'n_diskon'        => $ndiskon,
            'e_remark'        => $eremarkh,
            'd_entry'         => current_datetime(),
        );
        $this->db->insert('tm_keluar_makloonqcset', $data);
    }

    public function simpandetail($id,$idproduct,$qty,$vprice,$eremark)
    {
        $data = array(
            'id_company'      => $this->session->userdata('id_company'),
            'id_document'     => $id,
            'id_product_wip'  => $idproduct,
            'n_quantity'      => $qty,
            'n_quantity_sisa' => $qty,
            'v_price'         => $vprice,
            'e_remark'        => $eremark,
        );
        $this->db->insert('tm_keluar_makloonqcset_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                a.id_type_makloon,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                to_char(a.d_perkiraan, 'dd-mm-yyyy') AS d_perkiraan,
                a.id_supplier,
                c.e_supplier_name,
                a.n_diskon,
                a.f_pkp,
                a.i_permintaan,
                to_char(a.d_permintaan, 'dd-mm-yyyy') AS d_permintaan,
                a.e_remark,
                b.e_bagian_name,
                e_type_makloon_name,
                a.i_status
            FROM
                tm_keluar_makloonqcset a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_supplier c ON
                (c.id = a.id_supplier)
            INNER JOIN tr_type_makloon d ON
                (d.id = a.id_type_makloon)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    { 
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                a.id_product_wip,
                b.i_product_wip,
                b.e_product_wipname,
                a.n_quantity,
                a.v_price,
                a.e_remark,
                b.e_product_wipname || coalesce(' - ', '') || c.e_color_name as namabarang
            FROM
                tm_keluar_makloonqcset_item a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN (SELECT i_color, e_color_name FROM tr_color WHERE id_company = '$idcompany' AND f_status = 't' ) c ON
                (c.i_color = b.i_color)
            WHERE
                a.id_document = '$id'
            ORDER BY
                a.id ASC
        ", FALSE);
    }
    

    /*----------  UPDATE DATA  ----------*/    

    public function update($id,$idocument,$ddocument,$dperkiraan,$ibagian,$itype,$ipartner,$ipermintaan,$dpermintaan,$fpkp,$ndiskon,$eremarkh)
    {
        $data = array(
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'd_perkiraan'     => $dperkiraan,
            'i_bagian'        => $ibagian,
            'id_type_makloon' => $itype,
            'id_supplier'     => $ipartner,
            'i_permintaan'    => $ipermintaan,
            'd_permintaan'    => $dpermintaan,
            'f_pkp'           => $fpkp,
            'i_status'        => '1',
            'n_diskon'        => $ndiskon,
            'e_remark'        => $eremarkh,
            'd_update'        => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloonqcset', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_makloonqcset_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    /* 
    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloonqcset', $data);
    } 
    */


    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_keluar_makloonqcset a
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
                if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
                    
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_makloonqcset');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloonqcset', $data);
    }
}
/* End of file Mmaster.php */