<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu,$folder,$dfrom,$dto){
        $idcompany  = $this->id_company;

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
                tm_batalop
            WHERE
                i_status <> '5'
                AND id_company = '".$this->id_company."'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->id_departement."'
                        AND id_company = '".$this->id_company."'
                        AND username = '".$this->username."')

        ", FALSE);
        if ($this->id_departement=='4' || $this->id_departement=='1') {
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
                        i_departement = '".$this->id_departement."'
                        AND id_company = '".$this->id_company."'
                        AND username = '".$this->username."')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                a.id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                e_bagian_name,
                e_remark,
                a.i_status,
                e_status_name,
                label_color,
                a.id_company,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_batalop a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_status_document c ON
                (c.i_status = a.i_status)
            WHERE
                a.i_status <> '5'
            AND 
                a.id_company = '$idcompany'
                $where
                $bagian
            ORDER BY
                a.id DESC ", FALSE);
        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });
        
        $datatables->add('action', function ($data) {
            $id      = trim($data['id']);
            $i_menu  = $data['i_menu'];
            $i_status= $data['i_status'];
            $folder  = $data['folder'];
            $dfrom   = $data['dfrom'];
            $dto     = $data['dto'];
            $data    = '';
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
            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('id_company');
        
        return $datatables->generate();
    }

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->id_departement);
        $this->db->where('i_level', $this->id_level);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->id_company);
        $this->db->where('a.f_status', 't');
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_batalop');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->id_company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_batalop');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->id_company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_batalop 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->id_company."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_batalop
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->id_company."'
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

    public function get_op($cari){
        return $this->db->query("
            SELECT
                DISTINCT
                a.id,
                a.i_op
            FROM
                tm_opbb a,
                tm_opbb_item b
            WHERE
                a.id = b.id_op
                AND a.i_status = '6'
                AND b.n_sisa > 0
                AND a.i_op ILIKE '%$cari%'
                AND a.id_company = '$this->id_company'
            ORDER BY 2
        ", FALSE);
    }

    public function get_item_op($cari,$iop){
        return $this->db->query("
            SELECT
                a.id,
                a.id_op,
                a.id_pp,
                a.i_material,
                b.e_material_name,
                n_sisa
            FROM
                tm_opbb_item a
            JOIN tr_material b ON
                (
                    b.i_material = a.i_material
                        AND a.id_company = b.id_company
                )
            WHERE
                a.n_sisa > 0
                AND a.id_op = '$iop'
                AND (
                    a.i_material ILIKE '%$cari%'
                        OR b.e_material_name ILIKE '%$cari%'
                )
            ORDER BY
                a.i_material 
        ", FALSE);
    }

    public function get_item_op_detail($iop,$imaterial){
        return $this->db->query("
            SELECT
                a.id,
                a.id_op,
                a.id_pp,
                a.i_material,
                b.e_material_name,
                n_sisa
            FROM
                tm_opbb_item a
            JOIN tr_material b ON
                (
                    b.i_material = a.i_material
                        AND a.id_company = b.id_company
                )
            WHERE
                a.n_sisa > 0
                AND a.id_op = '$iop'
                AND a.i_material = '$imaterial'
            ORDER BY
                a.i_material 
        ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_batalop');
        return $this->db->get()->row()->id+1;
    }

    public function insertheader($id,$ibagian,$idocument,$ddocument,$remark)
    {
        $data = array(
            'id'          => $id,
            'i_document'  => $idocument,
            'd_document'  => $ddocument,
            'i_bagian'    => $ibagian,
            'e_remark'    => $remark,
            'id_company'  => $this->id_company,  
            'd_entry'     => current_datetime(),
        );
        $this->db->insert('tm_batalop', $data);
    }

    public function insertdetail($id,$iop,$idpp,$imaterial,$nquantity,$eremark)
    {
        $data = array(
            'id_document'   => $id,
            'id_op'         => $iop,
            'id_pp'         => $idpp,
            'i_product'     => $imaterial,
            'n_quantity'    => $nquantity,
            'e_remark'      => $eremark,  
            'id_company'    => $this->id_company,   
        );
        $this->db->insert('tm_batalop_item', $data);
    }

    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $query = $this->db->query("SELECT i_product, n_quantity, id_op, id_pp FROM tm_batalop_item WHERE id_document = '$id' ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $this->db->query("UPDATE
                        tm_opbb_item
                    SET
                        n_sisa = n_sisa - $key->n_quantity
                    WHERE
                        id_op = '$key->id_op'
                        AND trim(i_material) = trim('$key->i_product')
                        AND n_sisa >= '$key->n_quantity' ", FALSE);

                    $this->db->query("UPDATE
                        tm_pp_item
                    SET
                        n_sisa = n_sisa + $key->n_quantity
                    WHERE
                        id_pp = '$key->id_pp'
                        AND trim(i_material) = trim('$key->i_product') ", FALSE);
                }
                $data = array(
                    'i_status'  => $istatus,
                    'e_approve' => $this->username,
                    'd_approve' => date('Y-m-d'),
                );
            }else{
                $data = array(
                    'i_status'  => $istatus,
                    'e_approve' => $this->username,
                    'd_approve' => date('Y-m-d'),
                );
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_batalop', $data);
    }

    public function dataheader($id)
    {
        $this->db->select('a.*, b.e_bagian_name');
        $this->db->from('tm_batalop a');
        $this->db->join('tr_bagian b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.id', $id);
        return $this->db->get();
    }

    public function datadetail($id)
    {
        $this->db->select('a.*, b.e_material_name, c.i_op, e_supplier_name');
        $this->db->from('tm_batalop_item a');
        $this->db->join('tr_material b','b.i_material = a.i_product AND a.id_company = b.id_company','inner');
        $this->db->join('tm_opbb c','c.id = a.id_op AND a.id_company = c.id_company','inner');
        $this->db->where('a.id_document',$id);
        $this->db->order_by('a.id','ASC');
        return $this->db->get();
    }

    public function updateheader($id,$ibagian,$idocument,$ddocument,$remark)
    {
        $data = array(
            'id'                => $id,
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'i_status'          => 1,
            'e_remark'          => $remark,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_batalop', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_batalop_item');
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
}
/* End of file Mmaster.php */
