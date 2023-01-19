<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DEKLARASI SESSION  ----------*/
    
    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

    public function cek_supplier($dfrom,$dto){
        return $this->db->query("
            SELECT
                DISTINCT(a.i_supplier),
                a.e_supplier_name
            FROM
                tr_supplier a
            JOIN tm_opbb b ON
                a.i_supplier = b.i_supplier
                AND b.id_company = a.id_company
            WHERE
                i_status = '6'
                AND a.id_company = $this->company
                AND b.d_op >= '".date('Y-m-d', strtotime($dfrom))."'
                AND b.d_op <= '".date('Y-m-d', strtotime($dto))."'
            ORDER BY 2
        ", FALSE);
    }

    function data($isupplier,$dfrom,$dto,$i_menu,$folder)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_op BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        if ($isupplier == 'SP') {
            $supplier = "";            
        }else{
            $supplier = "AND a.i_supplier = '$isupplier'";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT
                0 AS no,
                a.id,
                i_op,
                to_char(d_op, 'dd-mm-yyyy') AS d_op,
                e_supplier_name,
                e_bagian_name,
                b.i_material,
                e_material_name,
                n_quantity AS op,
                n_quantity - n_sisa AS btb,
                round(((n_quantity - n_sisa) / n_quantity) * 100, 2) || ' %' AS persentase,
                n_sisa AS sisa,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$isupplier' AS isupplier
            FROM
                tm_opbb a
            INNER JOIN tm_opbb_item b ON
                (b.id_op = a.id)
            INNER JOIN tr_material c ON
                (c.i_material = b.i_material 
                AND b.id_company = c.id_company)
            INNER JOIN tm_pp d ON 
                (d.id = b.id_pp)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = d.i_bagian 
                AND d.id_company = e.id_company)
            WHERE
                a.i_status = '6'
                AND a.id_company = $this->company
                $where
                $supplier
            ORDER BY
                i_op,
                b.i_material
        ", FALSE);
        $datatables->add('action', function ($data) {
            $id         = $data['id'];
            $iop        = trim($data['i_op']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $isupplier  = $data['isupplier'];
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$isupplier/$iop\",\"#main\"); return false;'><i class='ti-eye'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('isupplier');
        return $datatables->generate();
    }

    public function cek_data($id)
    {
        return $this->db->query("
            SELECT
                a.i_btb,
                b.i_material,
                e_material_name,
                e_satuan_name,
                b.n_quantity
            FROM
                tm_btb a
            INNER JOIN tm_btb_item b ON
                (b.id_btb = a.id)
            INNER JOIN tr_material c ON
                (c.i_material = b.i_material 
                AND b.id_company = c.id_company)
            INNER JOIN tr_satuan d ON
                (d.i_satuan_code = b.i_satuan_code
                AND b.id_company = d.id_company)
            /*INNER jOIN tm_opbb_item e ON
                (e.id_op = b.id_op AND b.i_material = e.i_material)*/
            WHERE
                a.id = b.id_btb
                AND a.i_status = '6'
                AND b.id_op = '$id'
            ORDER BY a.i_btb, b.i_material
        ", FALSE);
    }

    public function exportdata($isupplier,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_op BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        if ($isupplier == 'SP') {
            $supplier = "";            
        }else{
            $supplier = "AND a.i_supplier = '$isupplier'";
        }
        return $this->db->query("
            SELECT
                DISTINCT
                0 AS no, 
                i_op,
                to_char(d_op, 'dd-mm-yyyy') AS d_op,
                e_supplier_name,
                e_bagian_name,
                b.i_material,
                e_material_name,
                n_quantity AS op,
                n_quantity - n_sisa AS btb,
                n_sisa AS sisa,
                b.v_price_ppn as v_price
            FROM
                tm_opbb a
            INNER JOIN tm_opbb_item b ON
                (b.id_op = a.id)
            INNER JOIN tr_material c ON
                (c.i_material = b.i_material
                AND b.id_company = c.id_company)
            INNER JOIN tm_pp d ON 
                (d.id = b.id_pp)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = d.i_bagian
                AND d.id_company = e.id_company)
            WHERE
                a.i_status = '6'
                AND a.id_company = '$this->company'
                $where
                $supplier
            ORDER BY
                i_op,
                b.i_material
        ", FALSE);
    }
}
/* End of file Mmaster.php */ 