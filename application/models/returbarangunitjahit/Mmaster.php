<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	/*----------  DAFTAR SJ MAKLOON  ----------*/    
    
    function data($i_menu,$folder,$dfrom,$dto){
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
                tm_retur_makloon_unitjahit a
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
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                b.e_supplier_name,
                c.i_document AS i_document_reff,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_retur_makloon_unitjahit a
            INNER JOIN tr_supplier b ON
                (b.id = a.id_supplier)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tm_masuk_makloon_unitjahit c ON
                (c.id = a.id_document_reff)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
                $bagian
            ORDER BY
                a.id", 
        FALSE);
            
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

    /*----------  BACA DATA PARTNER YANG ADA DIREFERENSI  ----------*/    

    public function partner($cari)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.id_supplier AS id,
                c.e_supplier_name AS e_name
            FROM
                tm_masuk_makloon_unitjahit a
            INNER JOIN tm_masuk_makloon_unitjahit_item b ON
                (b.id_document = a.id)
            INNER JOIN tr_supplier c ON
                (c.id = a.id_supplier)
            WHERE
                a.i_status = '6'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                AND b.n_quantity_sisa > 0
            ORDER BY
                2
        ", FALSE);
    }

    /*----------  CEK KODE  ----------*/

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_retur_makloon_unitjahit');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  RUNNING NO DOK  ----------*/

    public function runningnumber($thbl,$tahun,$ibagian) {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_retur_makloon_unitjahit 
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
                tm_retur_makloon_unitjahit
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

    public function referensi($cari,$idpartner)
    {
        $iperiode = date('Y-m-d', strtotime('-3 year', strtotime(date('Y-m-d'))));
        return $this->db->query("
            SELECT
                DISTINCT a.i_document,
                a.id,
                to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_masuk_makloon_unitjahit a
            INNER JOIN tm_masuk_makloon_unitjahit_item b ON
                (a.id = b.id_document)
            WHERE
                a.i_status = '6'
                /*AND COALESCE(b.n_quantity_sisa, 0) > 0*/
                AND a.id_supplier = '$idpartner'
                AND d_document >= '$iperiode'
                AND (TRIM(a.i_document) ILIKE '%$cari%')
        ", FALSE);
    }

    /*----------  DETAIL REFERENSI  ----------*/    

    public function getdetailreff($id)
    {
        return $this->db->query("
            SELECT
                a.id_product_wip,
                b.i_product_wip,
                b.e_product_wipname,
                c.e_color_name,
                sum(a.n_quantity) AS n_quantity
            FROM
                tm_masuk_makloon_unitjahit_item a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color
                AND b.id_company = c.id_company)
            WHERE
                a.id_document = '$id'
                /*AND a.n_quantity_sisa > 0*/
            GROUP BY
                1,2,3,4
            ORDER BY
                2
        ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_retur_makloon_unitjahit');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ireff,$ddocument,$ibagian,$ipartner,$eremarkh)
    {
        $data = array(
            'id'               => $id,
            'id_company'       => $this->session->userdata('id_company'),
            'i_document'       => $idocument,
            'id_document_reff' => $ireff,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_supplier'      => $ipartner,
            'e_remark'         => $eremarkh,
            'd_entry'          => current_datetime(),
        );
        $this->db->insert('tm_retur_makloon_unitjahit', $data);
    }

    public function simpandetail($id,$idreff,$idproduct,$nquantity,$eremark)
    {
        $data = array(
            'id_company'        => $this->session->userdata('id_company'),
            'id_document'       => $id,
            'id_document_reff'  => $idreff,
            'id_product_wip'    => $idproduct,
            'n_quantity'        => $nquantity,
            'n_quantity_sisa'   => $nquantity,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_retur_makloon_unitjahit_item', $data);
    }

    public function changestatus($id,$istatus) {
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
        $this->db->update('tm_retur_makloon_unitjahit', $data);
    }

     /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian,
                a.id_supplier,
                b.e_supplier_name,
                a.i_status,
                a.e_remark,
                e.e_bagian_name,
                a.id_document_reff,
                c.i_document AS i_document_reff,
                to_char(c.d_document, 'dd-mm-yyyy') AS d_document_reff
            FROM 
                tm_retur_makloon_unitjahit a
            INNER JOIN tr_supplier b 
                ON (b.id = a.id_supplier)
            INNER JOIN tr_status_document d 
                ON (d.i_status = a.i_status)
            INNER JOIN tr_bagian e 
                ON (e.i_bagian = a.i_bagian 
                AND a.id_company = e.id_company)
            INNER JOIN tm_masuk_makloon_unitjahit c 
                ON (c.id = a.id_document_reff) 
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
                c.e_color_name,
                a.n_quantity AS n_quantity,
                a.e_remark,
                sum(d.n_quantity) AS n_quantity_reff
            FROM
                tm_retur_makloon_unitjahit_item a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color
                AND b.id_company = c.id_company)
            INNER JOIN tm_masuk_makloon_unitjahit_item d ON 
                (d.id_document = a.id_document_reff 
                AND a.id_product_wip = d.id_product_wip)
            WHERE
                a.id_document = '$id'
                /*AND a.n_quantity_sisa > 0*/
            GROUP BY
                1,2,3,4,5,6
            ORDER BY
                2
        ", FALSE);
    }


    public function update($id,$idocument,$idreff,$ddocument,$ibagian,$ipartner,$eremarkh)
    {
        $data = array(
            'id_company'       => $this->session->userdata('id_company'),
            'i_document'       => $idocument,
            'id_document_reff' => $idreff,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_supplier'      => $ipartner,
            'e_remark'         => $eremarkh,
            'd_update'         => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_retur_makloon_unitjahit', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id) {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_retur_makloon_unitjahit_item');
    }
    

    public function updatekeluar($id_document_reff, $id_product_wip, $nquantity)
    {
        $this->db->query("
            update tm_retur_makloon_unitjahit_item set n_quantity_wip_sisa = n_quantity_wip_sisa - $nquantity where id_document = '$id_document_reff' and  id_product_wip = '$id_product_wip'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
