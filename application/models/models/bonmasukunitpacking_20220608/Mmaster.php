<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public $idcompany;
    public $i_menu = '2090601';

    function __construct(){
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

    function data($i_menu,$folder,$dfrom,$dto)
    {
        $idcompany = $this->session->id_company;
         if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        $cek = $this->db->query("
                                    SELECT
                                        i_bagian
                                    FROM
                                        tm_masuk_qc
                                    WHERE
                                        i_status <> '5'
                                        AND id_company = '".$this->session->userdata('id_company')."'
                                        $where
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
                                    0 as no,
                                    a.id,
                                    a.i_document,
                                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                    a.i_bagian_pengirim,
                                    a.i_bagian,
                                    b.e_bagian_name,
                                    a.id_reff,
                                    c.i_keluar_qc as i_reff,
                                    a.e_remark,
                                    a.i_status,
                                    d.e_status_name,
                                    d.label_color,
                                    '$i_menu' AS i_menu,
                                    '$folder' AS folder,
                                    '$dfrom' AS dfrom,
                                    '$dto' AS dto
                                FROM
                                    tm_masuk_packing a
                                INNER JOIN tr_bagian b
                                    ON (a.i_bagian_pengirim = b.i_bagian AND a.id_company = b.id_company)
                                LEFT JOIN tm_keluar_qc c
                                    ON (a.id_reff = c.id AND a.id_company = c.id_company)
                                INNER JOIN tr_status_document d
                                    ON (a.i_status = d.i_status)
                                WHERE
                                    a.i_status <> '5'
                                AND 
                                    a.id_company = '$idcompany'
                                $where
                                $bagian
                                ORDER BY
                                    a.i_document,
                                    a.d_document
                            ", FALSE
        );

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

    public function bagianpembuat(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function bagianpengirim($cari)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                    SELECT
                                        a.i_bagian,
                                        b.e_bagian_name
                                    FROM
                                        tr_tujuan_menu a
                                        JOIN tr_bagian b 
                                         ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                                    WHERE
                                        b.id_company = '$this->idcompany'
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
        return $this->db->query("
                                    SELECT DISTINCT
                                        a.id,
                                        a.i_keluar_qc as i_document,
                                        to_char(a.d_keluar_qc, 'dd-mm-yyyy') AS d_document
                                    FROM
                                        tm_keluar_qc a
                                        LEFT JOIN tm_keluar_qc_item b
                                            on (a.id = b.id_keluar_qc AND a.id_company = b.id_company)
                                    WHERE
                                        a.i_bagian = '$iasal'
                                        AND a.i_status = '6'
                                        AND a.id_company = '$this->idcompany'
                                        AND b.n_quantity_product <> 0
                                        AND b.n_sisa <> 0
                                        AND a.i_keluar_qc ILIKE '%$cari%'
                                    ORDER BY
                                        i_document,
                                        d_document
                                ", FALSE);
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_packing');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdataheader($idreff, $ipengirim){
        return $this->db->query("
                                    SELECT
                                        to_char(d_keluar_qc, 'dd-mm-yyyy') as d_document
                                    FROM 
                                        tm_keluar_qc
                                    WHERE
                                        id = '$idreff'
                                        AND i_bagian = '$ipengirim'
                                        AND id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function getdataitem($idreff, $ipengirim)
    {
        return $this->db->query("
                                    SELECT DISTINCT
                                      b.id,
                                      a.id_product,
                                      c.i_product_base,
                                      c.e_product_basename,
                                      a.n_quantity_product,
                                      a.n_sisa,
                                      a.id_color,
                                      c.i_color, 
                                      e.e_color_name
                                    FROM
                                      tm_keluar_qc_item a
                                      JOIN tm_keluar_qc b
                                        ON (a.id_keluar_qc= b.id AND a.id_company = b.id_company)
                                      JOIN tr_product_base c
                                        ON (a.id_product = c.id AND a.id_company = c.id_company)
                                      JOIN tr_color e
                                        ON (a.id_color = e.id AND c.id_company = e.id_company)
                                    WHERE
                                      b.id = '$idreff' 
                                      AND a.id_keluar_qc = '$idreff'
                                      AND b.id_company = '$this->idcompany'
                                      AND b.i_bagian = '$ipengirim'
                                      AND a.n_quantity_product <> 0
                                      AND a.n_sisa <> 0
                                ", FALSE);
    }

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_packing');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl, $tahun, $ibagian){
       $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_packing
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
                tm_masuk_packing
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

    function insertheader($id, $ibonm, $datebonm, $ikodemaster, $iasal, $ireff, $eremark)
    {

        $data = array(
                        'id'                 => $id,
                        'id_company'         => $this->idcompany,
                        'i_document'         => $ibonm,
                        'd_document'         => $datebonm,
                        'i_bagian'           => $ikodemaster,
                        'i_bagian_pengirim'  => $iasal,
                        'id_reff'            => $ireff,
                        'e_remark'           => $eremark,
                        'd_entry'            => current_datetime(),
        );
        $this->db->insert('tm_masuk_packing', $data);
    }

    function insertdetail($id, $ireff, $ibonm, $idproduct, $idcolor, $nquantity, $nquantitymasuk, $edesc)
    {
        $data = array(
                        'id_company'         => $this->idcompany,
                        'id_document'        => $id,
                        'id_reff'            => $ireff,
                        'id_product'         => $idproduct,
                        'id_color'           => $idcolor,
                        'n_quantity'         => $nquantitymasuk,
                        'n_sisa'             => $nquantitymasuk,
                        'e_remark'           => $edesc,
        );
        $this->db->insert('tm_masuk_packing_item', $data);
    }

    public function changestatus($id,$istatus){
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $query = $this->db->query("
                                        SELECT id_document, id_product, n_quantity, id_reff
                                        FROM tm_masuk_packing_item
                                        WHERE id_document = '$id' 
                                    ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $nsisa =  $this->db->query("
                                                    SELECT
                                                        n_sisa
                                                    FROM
                                                        tm_keluar_qc_item                       
                                                    WHERE
                                                        id_keluar_qc = '$key->id_reff'
                                                        AND id_product = '$key->id_product'
                                                        AND id_company = '".$this->session->userdata('id_company')."'
                                                        AND n_sisa >= '$key->n_quantity'
                                                ", FALSE);

                    if($nsisa->num_rows()>0){
                        $this->db->query("
                                            UPDATE
                                                tm_keluar_qc_item
                                            SET
                                                n_sisa = n_sisa - $key->n_quantity
                                            WHERE
                                                id_keluar_qc = '$key->id_reff'
                                                AND id_product = '$key->id_product'
                                                AND id_company = '".$this->session->userdata('id_company')."'
                                                AND n_sisa >= '$key->n_quantity'
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
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_masuk_packing', $data);
    }

    public function estatus($istatus){
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
                                     d.i_keluar_qc as i_reff,
                                     to_char(d.d_keluar_qc, 'dd-mm-yyyy') as d_reff,
                                     a.i_bagian,
                                     b.e_bagian_name,
                                     a.i_bagian_pengirim,
                                     c.e_bagian_name as e_bagian_pengirim,
                                     a.e_remark,
                                     a.i_status 
                                  FROM
                                     tm_masuk_packing a 
                                    INNER JOIN
                                        tm_keluar_qc d 
                                        ON (a.id_reff = d.id 
                                        AND a.id_company = d.id_company) 
                                    INNER JOIN
                                        tr_bagian b 
                                        ON (a.i_bagian = b.i_bagian 
                                        AND a.id_company = b.id_company) 
                                    INNER JOIN
                                        tr_bagian c 
                                        ON (a.i_bagian_pengirim = c.i_bagian 
                                        AND a.id_company = b.id_company) 
                                  WHERE 
                                    a.id  = '$id'
                                    AND a.i_bagian = '$ibagian'
                                    AND a.id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function cek_datadetail($id, $ibagian){
        return $this->db->query("
                                  SELECT
                                    a.id, 
                                    a.id_document,
                                    a.id_product,
                                    c.i_product_base,
                                    c.e_product_basename,
                                    a.n_quantity as n_quantity_masuk,
                                    f.n_quantity_product as n_quantity_qc,
                                    f.n_sisa,
                                    c.i_color,
                                    a.id_color,
                                    e.e_color_name,
                                    a.e_remark
                                  FROM
                                       tm_masuk_packing_item a 
                                      INNER JOIN
                                          tm_masuk_packing b 
                                          ON (a.id_document = b.id 
                                          AND a.id_company = b.id_company) 
                                      INNER JOIN
                                          tm_keluar_qc_item f 
                                          ON (a.id_reff = f.id_keluar_qc 
                                          AND a.id_product = f.id_product
                                          AND a.id_company = f.id_company) 
                                      INNER JOIN
                                          tr_product_base c 
                                          ON (a.id_product = c.id 
                                          AND a.id_company = c.id_company)  
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

    public function updateheader($id, $ikodemaster, $ibonm, $datebonm, $eremark, $ireff)
    {
        $data = array(
                      'i_document'  => $ibonm,
                      'i_bagian'    => $ikodemaster,
                      'd_document'  => $datebonm,
                      'id_reff'     => $ireff,
                      'e_remark'    => $eremark,
                      'd_update'    => current_datetime(),
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where('i_bagian', $ikodemaster);
        $this->db->update('tm_masuk_packing', $data);
    }

    public function deletedetail($id){
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_packing_item');
    }

    /*public function updatedetail($id, $idproduct, $nquantity, $idcolor, $edesc)
    {
        $data = array(
                      'n_quantity'  => $nquantity,
                      'n_sisa'      => $nquantity,
                      'e_remark'    => $edesc,
        );

        $this->db->where('id_document', $id);
        $this->db->where('id_product', $idproduct);
        $this->db->where('id_color', $idcolor);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_masuk_packing_item', $data);
    }*/
}
/* End of file Mmaster.php */