<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function bagianpembuat(){
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
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

    public function bagianpengirim($cari)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT
                                        a.i_bagian,
                                        b.e_bagian_name
                                    FROM
                                        tr_tujuan_menu a
                                        LEFT JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                                    WHERE
                                        b.id_company = '$this->id_company'
                                        AND a.i_menu = '$this->i_menu'
                                        AND a.i_bagian ILIKE '%$cari%'
                                        AND b.e_bagian_name ILIKE '%$cari%'
                                    ORDER BY
                                        b.e_bagian_name
                                ", FALSE);
    }

    public function referensi($cari,$iasal)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT DISTINCT
                                        a.id,
                                        a.i_document,
                                        to_char(a.d_document, 'dd-mm-yyyy') AS d_document
                                    FROM
                                        tm_keluar_cutting a
                                        LEFT JOIN tm_keluar_cutting_item b
                                            on (a.id = b.id_document AND a.id_company = b.id_company)
                                    WHERE
                                        /* a.i_bagian = '$iasal'
                                        AND */ a.i_status = '6'
                                        AND a.id_company = '$this->id_company'
                                        AND b.n_quantity_wip_sisa <> 0
                                        AND b.n_quantity_sisa <> 0
                                        AND a.i_document ILIKE '%$cari%'
                                    ORDER BY
                                        a.i_document,
                                        d_document
                                ", FALSE);
    }
    
    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE a.d_document BETWEEN '$dfrom' AND '$dto' AND a.id_company = '$this->id_company' AND a.i_status <> '5'";
        }else{
            $where = "WHERE id_company = '$this->id_company' AND a.i_status <> '5'";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    0 as no,
                    a.id,
                    a.i_document,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                    a.i_bagian,
                    a.i_bagian_pengirim,
                    b.e_bagian_name,
                    a.id_reff,
                    c.i_document as i_reff,
                    a.e_remark,
                    a.i_status,
                    d.e_status_name,
                    d.label_color,
                    f.i_level,
                    l.e_level_name,
                    '$i_menu' AS i_menu,
                    '$folder' AS folder,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto
                FROM
                    tm_masuk_qcset a
                INNER JOIN tr_bagian b
                    ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                LEFT JOIN tm_keluar_cutting c
                    ON (a.id_reff = c.id AND a.id_company = c.id_company)
                INNER JOIN tr_status_document d
                    ON (a.i_status = d.i_status)
                LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
                LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
                $where
                ORDER BY
                    a.i_document,
                    a.d_document
            ", FALSE
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id            = trim($data['id']);
            $ibagian       = trim($data['i_bagian']);
            $i_status      = trim($data['i_status']);
            $dfrom         = trim($data['dfrom']);
            $dto           = trim($data['dto']);
            $e_bagian_name = $data['e_bagian_name'];
            $i_menu        = $data['i_menu'];
            $i_level       = $data['i_level'];
            $folder        = $data['folder'];
            $data          = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9' && $i_status!='2')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close text-danger'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_bagian_pengirim');
        $datatables->hide('id_reff');
        $datatables->hide('e_bagian_name');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_qcset');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode,$kodeold,$ibagian) {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_qcset');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdataheader($idreff, $ipengirim){
        return $this->db->query("
                                    SELECT
                                        to_char(d_document, 'dd-mm-yyyy') as d_document
                                    FROM 
                                        tm_keluar_cutting
                                    WHERE
                                        id = '$idreff'
                                        AND i_bagian = '$ipengirim'
                                        AND id_company = '$this->id_company'
                                ", FALSE);
    }

    public function getdataitem($idreff, $ipengirim)
    {
        return $this->db->query("
                                    SELECT DISTINCT
                                    	a.id,
                                    	a.id_product_wip,
                                    	a.id_material,
                                    	c.i_product_wip,
                                        c.e_product_wipname,
                                        a.n_quantity_wip,
                                        a.n_quantity_wip_sisa,
                                        c.id as id_color,
                                    	c.i_color, 
                                    	e.e_color_name,
                                    	d.i_material,
                                    	d.e_material_name,
                                    	a.n_quantity,
                                    	a.n_quantity_sisa,
                                        a.e_remark
                                    FROM
                                    	tm_keluar_cutting_item a
                                    	LEFT JOIN tm_keluar_cutting b
                                    		ON (a.id_document = b.id AND a.id_company = b.id_company)
                                    	INNER JOIN tr_product_wip c
                                    		ON (a.id_product_wip = c.id AND a.id_company = c.id_company)
                                    	INNER JOIN tr_material d
                                    		ON (a.id_material = d.id AND a.id_company = d.id_company)
                                    	INNER JOIN tr_color e
                                    		ON (c.i_color = e.i_color AND c.id_company = e.id_company)
                                    WHERE
                                    	b.id = '$idreff' 
                                    	AND a.id_document = '$idreff'
                                    	AND b.id_company = '$this->id_company'
                                        /* AND b.i_bagian = '$ipengirim' */
                                        AND a.n_quantity_wip_sisa <> 0
                                        AND a.n_quantity_sisa <> 0
                                ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_qcset');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl,$tahun, $ibagian){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_qcset 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBM';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_masuk_qcset
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
            AND id_company = '".$this->session->userdata("id_company")."'
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

    function insertheader($id, $ibonm, $dbonm, $ikodemaster, $iasal, $ireff, $eremark)
    {

        $data = array(
                        'id'                        => $id,
                        'id_company'                => $this->id_company,
                        'i_document'                => $ibonm,
                        'd_document'                => $dbonm,
                        'i_bagian'                  => $ikodemaster,
                        'i_bagian_pengirim'         => $iasal,
                        'id_reff'                   => $ireff,
                        'e_remark'                  => $eremark,
                        'd_entry'                   => current_datetime(),
        );
        $this->db->insert('tm_masuk_qcset', $data);
    }

    function insertdetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc)
    {
        $data = array(
                        'id_company'                => $this->id_company,
                        'id_document'               => $id,
                        'id_reff'                   => $ireff,
                        'id_product_wip'            => $idproductwip,
                        'n_quantity_wip'            => $nquantitywipmasuk,
                        'n_quantity_wip_sisa'       => $nquantitywipmasuk,
                        'id_material'               => $idmaterial,
                        'n_quantity'                => $nquantitybahanmasuk,
                        'n_quantity_sisa'           => $nquantitybahanmasuk,
                        'e_remark'                  => $edesc,
        );
        $this->db->insert('tm_masuk_qcset_item', $data);
    }

    public function changestatus($id,$istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_masuk_qcset a
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
                    $query = $this->db->query("SELECT id_document, id_product_wip, id_material, n_quantity_wip, n_quantity, id_reff
                        FROM tm_masuk_qcset_item
                        WHERE id_document = '$id' ", FALSE);
                    if ($query->num_rows()>0) {
                        foreach ($query->result() as $key) {
                            $nsisa = $this->db->query("SELECT
                                                            n_quantity_wip_sisa 
                                                        FROM
                                                            tm_keluar_cutting_item
                                                        WHERE
                                                            id_document = '$key->id_reff'
                                                            AND id_product_wip = '$key->id_product_wip'
                                                            AND id_company = '".$this->session->userdata('id_company')."'
                                                            AND n_quantity_wip_sisa >= '$key->n_quantity_wip'
                                                    ", FALSE);
                            if($nsisa->num_rows()>0){
                                $this->db->query("UPDATE
                                                        tm_keluar_cutting_item
                                                    SET
                                                        n_quantity_wip_sisa = n_quantity_wip_sisa - $key->n_quantity_wip,
                                                        n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                                                    WHERE
                                                        id_document = '$key->id_reff'
                                                        AND id_product_wip = '$key->id_product_wip'
                                                        AND id_material = '$key->id_material'
                                                        AND id_company = '".$this->session->userdata('id_company')."'
                                                ", FALSE);
                            }else{
                                die();
                            }
                        }
                    }
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'i_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_qcset');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_qcset', $data);
    }


    /* public function changestatus($id,$istatus){
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $query = $this->db->query("
                SELECT id_document, id_product_wip, id_material, n_quantity_wip, n_quantity, id_reff
                FROM tm_masuk_qcset_item
                WHERE id_document = '$id' ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query("
                                                SELECT
                                                    n_quantity_wip_sisa 
                                                FROM
                                                    tm_keluar_cutting_item
                                                WHERE
                                                    id_document = '$key->id_reff'
                                                    AND id_product_wip = '$key->id_product_wip'
                                                    AND id_company = '".$this->session->userdata('id_company')."'
                                                    AND n_quantity_wip_sisa >= '$key->n_quantity_wip'
                                            ", FALSE);
                    if($nsisa->num_rows()>0){
                        $this->db->query("
                                            UPDATE
                                                tm_keluar_cutting_item
                                            SET
                                                n_quantity_wip_sisa = n_quantity_wip_sisa - $key->n_quantity_wip,
                                                n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                                            WHERE
                                                id_document = '$key->id_reff'
                                                AND id_product_wip = '$key->id_product_wip'
                                                AND id_material = '$key->id_material'
                                                AND id_company = '".$this->session->userdata('id_company')."'
                                        ", FALSE);
                    }else{
                        die();
                    }
                }
            }
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $iapprove,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->id_company);
        $this->db->update('tm_masuk_qcset', $data);
    } */

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function cek_data($id, $ibagian)
    {
        return $this->db->query("
                                    SELECT 
                                    	a.id,
                                    	a.i_document, 
                                    	to_char(a.d_document,'dd-mm-yyyy') as d_document,
                                    	a.id_reff,
                                    	d.i_document as i_reff,
                                    	to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
                                    	a.i_bagian,
                                    	b.e_bagian_name,
                                    	/* a.i_bagian_pengirim,
                                        c.e_bagian_name as e_bagian_pengirim, */
                                        a.e_remark,
                                        a.i_status
                                    FROM
                                    	tm_masuk_qcset a
                                    	LEFT JOIN tm_keluar_cutting d
                                    		ON (a.id_reff = d.id AND a.id_company = d.id_company)
                                    	INNER JOIN tr_bagian b
                                    		ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                                    	/* INNER JOIN tr_bagian c
                                    		ON (a.i_bagian_pengirim = c.i_bagian AND a.id_company = b.id_company) */
                                    WHERE 
                                    	a.id  = '$id'
                                    	AND a.i_bagian = '$ibagian'
                                    	AND a.id_company = '$this->id_company'
                                ", FALSE);
    }

    public function cek_datadetail($id, $ibagian){
        return $this->db->query("
                                    SELECT
                                    	a.id, 
                                    	a.id_document,
                                        a.id_product_wip,
                                        c.i_product_wip,
                                    	c.e_product_wipname,
                                    	a.n_quantity_wip as n_quantity_wip_masuk,
                                        f.n_quantity_wip as n_quantity_wip_cutting,
                                        f.n_quantity_wip_sisa,
                                        c.i_color,
                                        e.id as id_color,
                                        e.e_color_name,
                                        a.id_material,
                                        d.i_material,
                                    	d.e_material_name,
                                    	a.n_quantity as n_quantity_masuk,
                                        f.n_quantity as n_quantity_cutting,
                                        f.n_quantity_sisa,
                                    	a.e_remark
                                    FROM
                                    	tm_masuk_qcset_item a 
                                    	LEFT JOIN 
                                    		tm_masuk_qcset b
                                    		ON (a.id_document = b.id AND a.id_company = b.id_company)
                                    	LEFT JOIN 
                                    		tm_keluar_cutting_item f
                                    		ON (a.id_reff = f.id_document AND a.id_company = f.id_company)
                                    	INNER JOIN 
                                    		tr_product_wip c
                                    		ON (a.id_product_wip = c.id 
                                            AND a.id_company = c.id_company AND f.id_product_wip = c.id AND f.id_company = c.id_company) 
                                    	INNER JOIN 
                                    		tr_material d
                                    		ON (a.id_material = d.id 
                                            AND a.id_company = d.id_company AND f.id_material = d.id AND f.id_company = d.id_company)
                                    	INNER JOIN 
                                    		tr_color e
                                    		ON (c.i_color = e.i_color AND c.id_company = e.id_company)
                                    WHERE 
                                    	a.id_document = '$id'
                                    	AND b.id = '$id'
                                    	AND b.i_bagian = '$ibagian'
                                        AND b.id_company = '$this->id_company'
                                ", FALSE);
    }

    public function updateheader($id, $ikodemaster, $ibonm, $dbonm, $eremark)
    {
        $data = array(
                        'i_document'         => $ibonm,
                        'i_bagian'           => $ikodemaster,
                        'd_document'         => $dbonm,
                        'e_remark'           => $eremark,
                        'd_update'           => current_datetime(),
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $this->id_company);
        $this->db->where('i_bagian', $ikodemaster);
        $this->db->update('tm_masuk_qcset', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->where('id_company', $this->id_company);
        $this->db->delete('tm_masuk_qcset_item');
    }

    // public function updatedetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc)
    // {
    //     $data = array(
    //                     'n_quantity_wip'      => $nquantitywipmasuk,
    //                     'n_quantity_wip_sisa' => $nquantitywipmasuk,
    //                     'n_quantity'          => $nquantitybahanmasuk,
    //                     'n_quantity_sisa'     => $nquantitybahanmasuk,
    //                     'e_remark'            => $edesc,
    //     );

    //     $this->db->where('id_document', $id);
    //     $this->db->where('id_product_wip', $idproductwip);
    //     $this->db->where('id_material', $idmaterial);
    //     $this->db->where('id_company', $this->id_company);
    //     $this->db->update('tm_masuk_qcset_item', $data);
    // }
}
/* End of file Mmaster.php */
