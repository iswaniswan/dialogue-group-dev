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
                tm_keluar_makloon_pengadaan_retur
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
        if ($this->session->userdata('i_departement')=='4' || $this->session->userdata('i_departement')=='1') {
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
                                a.e_remark, 
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
                                tm_keluar_makloon_pengadaan_retur a
                            INNER JOIN tr_supplier b ON
                                (b.id = a.id_supplier)
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
                                a.id
                        ", FALSE);
        
    $datatables->edit('i_status', function ($data) {
        $i_status = $data['i_status'];
        if ($i_status == '2') {
            $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
        }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name']. '</span>';
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

            /* 
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            } */
            // if (check_role($i_menu, 5)) {
            //     if ($i_status == '6') {
            //         $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
            //     }
            // }
            // if (check_role($i_menu, 4) && ($i_status=='1')) {
            //     $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            // }
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

        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
            INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            LEFT JOIN tr_type c on (a.i_type = c.i_type)
            LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
            WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
            ORDER BY 4, 3 ASC NULLS LAST
        ", false);

    }

    /*----------  CEK KODE  ----------*/

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_makloon_pengadaan_retur');
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
                substring(i_document, 1, 3) AS kode 
            FROM tm_keluar_makloon_pengadaan_retur 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJR';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_keluar_makloon_pengadaan_retur
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
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

    public function partner($cari,$i_menu)
    {
        return $this->db->query("SELECT
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

    public function product($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("   SELECT DISTINCT 
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
                                        a.i_product_wip ASC
                                ", FALSE);
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id)
    {
        return $this->db->query("   SELECT
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
                                        c.i_material ASC
                                ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_makloon_pengadaan_retur');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$ipartner,$eremarkh)
    {
        $data = array(
                        'id'                => $id,
                        'id_company'        => $this->session->userdata('id_company'),
                        'i_document'        => $idocument,
                        'd_document'        => $ddocument,
                        'i_bagian'          => $ibagian,
                        'id_supplier'       => $ipartner,
                        'e_remark'          => $eremarkh,
                        // 'n_quantity_wip'    => $nquantitywip,
                        // 'e_remark_wip'      => $eremarkwip,
                        'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_keluar_makloon_pengadaan_retur', $data);
    }

    public function simpandetail($id,$idproductwip,$idmaterial,$nquantity,$eremark,$nquantitywip1,$eremarkwip1)
    {
        $data = array(
                        'id_company'        => $this->session->userdata('id_company'),
                        'id_document_retur' => $id,
                        'id_product_wip'    => $idproductwip,
                        'id_material'       => $idmaterial,
                        'n_quantity'        => $nquantity,
                        'e_remark'          => $eremark,
                        'n_quantity_wip'    => $nquantitywip1,
                        'e_remark_wip'      => $eremarkwip1,
        );
        $this->db->insert('tm_keluar_makloon_pengadaan_retur_item', $data);
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
                                        a.id_supplier,
                                        c.e_supplier_name,
                                        a.e_remark,
                                        b.e_bagian_name,
                                        a.i_status
                                    FROM
                                        tm_keluar_makloon_pengadaan_retur a
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
                                        a.n_quantity,
                                        a.e_remark,
                                        a.n_quantity_wip,
                                        a.e_remark_wip,
                                        d.e_color_name
                                    FROM
                                        tm_keluar_makloon_pengadaan_retur_item a
                                    INNER JOIN tr_product_wip b ON
                                        (b.id = a.id_product_wip)
                                    INNER JOIN tr_material c ON
                                        (c.id = a.id_material)
                                    INNER JOIN tr_color d ON
                                        (d.i_color = b.i_color 
                                        AND b.id_company = d.id_company)            
                                    WHERE
                                        a.id_document_retur = '$id'
                                    ORDER BY
                                        a.id_product_wip, 
                                        c.i_material,
                                        b.i_product_wip
                                    ASC
                                ", FALSE);
    }
    public function perbarang($id)
    {
        return $this->db->query("
                                    SELECT
                                        a.id_product_wip,
                                        b.i_product_wip,
                                        b.e_product_wipname,
                                        a.id_material,
                                        c.i_material,
                                        c.e_material_name,
                                        a.n_quantity,
                                        a.e_remark,
                                        d.e_color_name
                                    FROM
                                        tm_keluar_makloon_pengadaan_retur_item a
                                    INNER JOIN tr_product_wip b ON
                                        (b.id = a.id_product_wip)
                                    INNER JOIN tr_material c ON
                                        (c.id = a.id_material)
                                    INNER JOIN tr_color d ON
                                        (d.i_color = b.i_color 
                                        AND b.id_company = d.id_company)            
                                    WHERE
                                        a.id_document_retur = '$id'
                                    ORDER BY
                                        a.id_product_wip, 
                                        c.i_material,
                                        b.i_product_wip
                                    ASC LIMIT 1
                                ", FALSE);
    }

    /*----------  UPDATE DATA  ----------*/    

    public function update($id,$idocument,$ddocument,$ibagian,$ipartner,$eremarkh)
    {
        $data = array(
                        'id_company'   => $this->session->userdata('id_company'),
                        'i_document'   => $idocument,
                        'd_document'   => $ddocument,
                        'i_bagian'     => $ibagian,
                        'id_supplier'  => $ipartner,
                        'e_remark'     => $eremarkh,
                        'd_update'     => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloon_pengadaan_retur', $data);
    }
/* 
    public function updatedetail($id,$idproductwip,$idmaterial,$nquantity,$eremark,$nquantitywip1,$eremarkwip1)
    {
        $data = array(
                        'id_company'        => $this->session->userdata('id_company'),
                        'id_product_wip'    => $idproductwip,
                        'id_material'       => $idmaterial,
                        'n_quantity'        => $nquantity,
                        'e_remark'          => $eremark,
                        'n_quantity_wip'    => $nquantitywip1,
                        'e_remark_wip'      => $eremarkwip1,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloon_pengadaan_retur_item', $data);
    } */

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document_retur', $id);
        $this->db->delete('tm_keluar_makloon_pengadaan_retur_item');
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
        $this->db->update('tm_keluar_makloon_pengadaan_retur', $data);
    }   */ 


    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_keluar_makloon_pengadaan_retur a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_makloon_pengadaan_retur');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_makloon_pengadaan_retur', $data);
    }

}
/* End of file Mmaster.php */