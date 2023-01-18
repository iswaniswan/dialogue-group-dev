<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public $idcompany;
    public $i_menu = '2090210';

    function __construct(){
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

    public function bagianpembuat(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function bagianpengirim($cari)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                SELECT DISTINCT
                                    a.id_partner,
                                    b.i_partner,
                                	b.e_partner_name
                                FROM
                                	tm_keluar_pinjamanqcset a
                                	INNER JOIN
                                		(SELECT 
                                            id as id_partner,
                                            i_supplier as i_partner, 
                                			e_supplier_name as e_partner_name,
                                			id_company
                                		 FROM 
                                			tr_supplier 
                                		 WHERE
                                			id_company = '$this->idcompany'
                                		 UNION ALL
                                		 SELECT 
                                            id as id_partner,
                                            e_nik as i_partner,
                                			e_nama_karyawan as e_partner_name,
                                			id_company
                                		 FROM 
                                			tr_karyawan
                                		 WHERE
                                			id_company = '$this->idcompany'
                                        ) b ON (a.id_partner = b.id_partner AND a.i_partner = b.i_partner AND a.id_company = b.id_company)
                                    LEFT JOIN
                                        tm_keluar_pinjamanqcset_item c
                                        ON (a.id = c.id_document AND a.id_company = c.id_company)
                                WHERE
                                	a.id_company ='$this->idcompany' 
                                    AND a.i_status = '6'
                                    AND b.e_partner_name ILIKE '%$cari%'
                                    AND c.n_sisa_wip <> 0
                                    AND c.n_sisa_material <> 0
                                ORDER BY
                                    b.e_partner_name
                                ", FALSE);
    }

    public function referensi($cari,$idpartner,$ipartner)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT DISTINCT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_keluar_pinjamanqcset a
                LEFT JOIN tm_keluar_pinjamanqcset_item b
                    on (a.id = b.id_document AND a.id_company = b.id_company)
            WHERE
                a.id_partner = '$idpartner'
                AND a.i_partner = '$ipartner'
                AND a.i_status = '6'
                AND a.id_company = '$this->idcompany'
                AND b.n_sisa_wip <> 0
                AND b.n_sisa_material <> 0
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
            $where = "WHERE a.d_document BETWEEN '$dfrom' AND '$dto' AND a.id_company = '$this->idcompany' AND a.i_status <> '5'";
        }else{
            $where = "WHERE id_company = '$this->idcompany' AND a.i_status <> '5'";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT
                                0 as no,
                                a.id,
                                a.i_document,
                                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                a.i_bagian,
                                a.i_bagian_pengirim,
                                e.e_bagian_pengirim,
                                a.id_reff,
                                c.i_document as i_reff,
                                a.e_remark,
                                a.i_status,
                                d.e_status_name,
                                d.label_color,
                                '$i_menu' AS i_menu,
                                '$folder' AS folder,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto
                            FROM
                                tm_masuk_pinjamanqcset a
                            INNER JOIN tr_bagian b
                                ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                            LEFT JOIN tm_keluar_pinjamanqcset c
                                ON (a.id_reff = c.id AND a.id_company = c.id_company)
                            INNER JOIN tr_status_document d
                                ON (a.i_status = d.i_status)
                            INNER JOIN 
                                (
                                 SELECT
                                    id as id_bagian_pengirim,
                                    i_supplier as i_bagian_pengirim,
                                    e_supplier_name as e_bagian_pengirim,
                                    id_company
                                 FROM
                                    tr_supplier 
                                 WHERE
                                    id_company = '$this->idcompany' 
                                 UNION ALL
                                 SELECT
                                    id as id_bagian_pengirim,
                                    e_nik as i_bagian_pengirim,
                                    e_nama_karyawan as e_bagian_pengirim,
                                    id_company
                                 FROM
                                    tr_karyawan 
                                 WHERE
                                    id_company = '$this->idcompany' 
                                ) e ON (a.i_bagian_pengirim = e.i_bagian_pengirim
                                    AND a.id_company = e.id_company)
                            $where
                            ORDER BY
                                a.i_document,
                                a.d_document
                            ", FALSE);

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id            = trim($data['id']);
            $ibagian       = trim($data['i_bagian']);
            $i_status      = trim($data['i_status']);
            $dfrom         = trim($data['dfrom']);
            $dto           = trim($data['dto']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $data          = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9' && $i_status!='2')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status_name');
        $datatables->hide('i_bagian');
        $datatables->hide('i_bagian_pengirim');
        $datatables->hide('id_reff');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_pinjamanqcset');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdataheader($idreff, $idpengirim, $ipengirim){
        return $this->db->query("
                                SELECT
                                    to_char(d_document, 'dd-mm-yyyy') as d_document
                                FROM 
                                    tm_keluar_pinjamanqcset
                                WHERE
                                    id = '$idreff'
                                    AND id_partner = '$idpengirim'
                                    AND i_partner = '$ipengirim'
                                    AND id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function getdataitem($idreff, $idpengirim, $ipengirim)
    {
        return $this->db->query("
                                SELECT DISTINCT
                                	a.id,
                                	a.id_product_wip,
                                	a.id_material,
                                	c.i_product_wip,
                                    c.e_product_wipname,
                                    a.n_quantity_wip,
                                    a.n_sisa_wip,
                                    c.id as id_color,
                                	c.i_color, 
                                	e.e_color_name,
                                	d.i_material,
                                	d.e_material_name,
                                	a.n_quantity,
                                	a.n_sisa_material,
                                    a.e_remark
                                FROM
                                	tm_keluar_pinjamanqcset_item a
                                	LEFT JOIN tm_keluar_pinjamanqcset b
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
                                	AND b.id_company = '$this->idcompany'
                                    AND b.id_partner = '$idpengirim'
                                    AND b.i_partner  = '$ipengirim'
                                    AND a.n_sisa_wip <> 0
                                    AND a.n_sisa_material <> 0
                                ", FALSE);
    }


    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_pinjamanqcset');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl,$tahun,$ibagian){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_pinjamanqcset 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_masuk_pinjamanqcset
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

    function insertheader($id, $idocument, $ddocument, $ibagian, $iasal, $ireff, $eremark)
    {

        $data = array(
            'id'                 => $id,
            'id_company'         => $this->idcompany,
            'i_document'         => $idocument,
            'd_document'         => $ddocument,
            'i_bagian'           => $ibagian,
            'i_bagian_pengirim'  => $iasal,
            'id_reff'            => $ireff,
            'e_remark'           => $eremark,
            'd_entry'            => current_datetime(),
        );
        $this->db->insert('tm_masuk_pinjamanqcset', $data);
    }

    function insertdetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc)
    {
        $data = array(
            'id_company'                => $this->idcompany,
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
        $this->db->insert('tm_masuk_pinjamanqcset_item', $data);
    }

    public function changestatus($id,$istatus){
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $query = $this->db->query("
                SELECT id_document, id_product_wip, id_material, n_quantity_wip, n_quantity, id_reff
                FROM tm_masuk_pinjamanqcset_item
                WHERE id_document = '$id' ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $this->db->query("
                        UPDATE
                            tm_keluar_pinjamanqcset_item
                        SET
                            n_sisa_wip = n_sisa_wip - $key->n_quantity_wip,
                            n_sisa_material = n_sisa_material - $key->n_quantity
                        WHERE
                            id_document = '$key->id_reff'
                            AND id_product_wip = '$key->id_product_wip'
                            AND id_material = '$key->id_material'
                            AND id_company = '".$this->session->userdata('id_company')."'
                    ", FALSE);
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
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_masuk_pinjamanqcset', $data);
    }

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
                                   to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                   a.id_reff,
                                   d.i_document as i_reff,
                                   to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
                                   a.i_bagian,
                                   b.e_bagian_name,
                                   a.i_bagian_pengirim,
                                   c.e_bagian_pengirim,
                                   a.e_remark,
                                   a.i_status 
                                FROM
                                   tm_masuk_pinjamanqcset a 
                                   LEFT JOIN
                                      tm_keluar_pinjamanqcset d 
                                      ON (a.id_reff = d.id 
                                      AND a.id_company = d.id_company) 
                                   INNER JOIN
                                      tr_bagian b 
                                      ON (a.i_bagian = b.i_bagian 
                                      AND a.id_company = b.id_company) 
                                   INNER JOIN
                                      (
                                         SELECT
                                            id as id_bagian_pengirim,
                                            i_supplier as i_bagian_pengirim,
                                            e_supplier_name as e_bagian_pengirim,
                                            id_company
                                         FROM
                                            tr_supplier 
                                         WHERE
                                            id_company = '$this->idcompany' 
                                         UNION ALL
                                         SELECT
                                            id as id_bagian_pengirim,
                                            e_nik as i_bagian_pengirim,
                                            e_nama_karyawan as e_bagian_pengirim,
                                            id_company
                                         FROM
                                            tr_karyawan 
                                         WHERE
                                            id_company = '$this->idcompany' 
                                      ) c ON (a.i_bagian_pengirim = c.i_bagian_pengirim and a.id_company = c.id_company)
                                WHERE
                                   a.id = '$id' 
                                   AND a.i_bagian = '$ibagian' 
                                   AND a.id_company = '$this->idcompany'
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
                                   f.n_quantity_wip as n_quantity_wip_keluar,
                                   f.n_sisa_wip,
                                   c.i_color,
                                   e.id as id_color,
                                   e.e_color_name,
                                   a.id_material,
                                   d.i_material,
                                   d.e_material_name,
                                   a.n_quantity as n_quantity_material_masuk,
                                   f.n_quantity as n_quantity_material_keluar,
                                   f.n_sisa_material,
                                   a.e_remark 
                                FROM
                                   tm_masuk_pinjamanqcset_item a 
                                   LEFT JOIN
                                      tm_masuk_pinjamanqcset b 
                                      ON (a.id_document = b.id 
                                      AND a.id_company = b.id_company) 
                                   LEFT JOIN
                                      tm_keluar_pinjamanqcset_item f 
                                      ON (a.id_reff = f.id_document 
                                      AND a.id_company = f.id_company) 
                                   INNER JOIN
                                      tr_product_wip c 
                                      ON (a.id_product_wip = c.id 
                                      AND a.id_company = c.id_company 
                                      AND f.id_product_wip = c.id 
                                      AND f.id_company = c.id_company) 
                                   INNER JOIN
                                      tr_material d 
                                      ON (a.id_material = d.id 
                                      AND a.id_company = d.id_company 
                                      AND f.id_material = d.id 
                                      AND f.id_company = d.id_company) 
                                   INNER JOIN
                                      tr_color e 
                                      ON (c.i_color = e.i_color 
                                      AND c.id_company = e.id_company) 
                                WHERE
                                   a.id_document = '$id' 
                                   AND b.id = '$id' 
                                   AND b.i_bagian = '$ibagian' 
                                   AND b.id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function updateheader($id, $idocument, $ddocument, $ibagian, $iasal, $ireff, $eremark)
    {
        $data = array(
            'i_document' => $idocument,
            'i_bagian'   => $ibagian,
            'd_document' => $ddocument,
            'e_remark'   => $eremark,
            'd_update'   => current_datetime(),
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where('i_bagian', $ibagian);
        $this->db->update('tm_masuk_pinjamanqcset', $data);
    }
    
    public function deletedetail($id) {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_pinjamanqcset_item');
    }

    // public function updatedetail($id, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc)
    // {
    //     $data = array(
    //         'n_quantity_wip'      => $nquantitywipmasuk,
    //         'n_quantity_wip_sisa' => $nquantitywipmasuk,
    //         'n_quantity'          => $nquantitybahanmasuk,
    //         'n_quantity_sisa'     => $nquantitybahanmasuk,
    //         'e_remark'            => $edesc,
    //     );

    //     $this->db->where('id_document', $id);
    //     $this->db->where('id_product_wip', $idproductwip);
    //     $this->db->where('id_material', $idmaterial);
    //     $this->db->where('id_company', $this->idcompany);
    //     $this->db->update('tm_masuk_pinjamanqcset_item', $data);
    // }
}
/* End of file Mmaster.php */
