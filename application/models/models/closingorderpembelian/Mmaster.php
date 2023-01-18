<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_op BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            WITH query AS (
            SELECT
                NO,
                ROW_NUMBER() OVER (
                ORDER BY a.id) AS i,
                id,
                i_op,
                d_op,
                e_supplier_name,
                i_sj_supplier,
                d_sj,
                i_btb,
                d_btb,
                f_op_close,
                i_menu
                FROM(
                    SELECT
                        DISTINCT ON
                        (i_op) 0 AS no,
                        a.id,
                        i_op,
                        to_char(d_op, 'dd-mm-yyyy') AS d_op,
                        a.e_supplier_name,
                        i_sj_supplier,
                        to_char(d_sj_supplier, 'dd-mm-yyyy') AS d_sj,
                        i_btb,
                        to_char(d_btb, 'dd-mm-yyyy') AS d_btb,
                        f_op_close,
                        '$i_menu' AS i_menu
                    FROM
                        tm_opbb a
                    INNER JOIN tm_opbb_item b ON
                        (b.id_op = a.id)
                    LEFT JOIN tm_btb_item c ON
                        (c.id_op = b.id_op
                        AND b.i_material = c.i_material)
                    LEFT JOIN tm_btb d ON
                        (d.id = c.id_btb)
                    WHERE
                        a.i_status = '6'
                        AND d.i_status = '6'
                        AND a.id_company = '".$this->session->userdata('id_company')."'
                        $and) AS a)
                SELECT
                    NO,
                    i,
                    id,
                    i_op,
                    d_op,
                    e_supplier_name,
                    i_sj_supplier,
                    d_sj,
                    i_btb,
                    d_btb,
                    f_op_close,
                    i_menu,
                    (
                    SELECT
                        count(i) AS jml
                    FROM
                        query) AS jml
                FROM
                    query
        ", FALSE);

        $datatables->edit('f_op_close', function ($data) {
            if($data['f_op_close'] == 'f'){
                return '<span class="label label-danger"><b>Belum</b></span>';
            }else{
                return '<span class="label label-success"><b>Sudah</b></span>';
            }
        });

        $datatables->add('action', function ($data) {
            $id     = $data['id'];
            $i      = $data['i'];
            $jml      = $data['jml'];
            $iop    = trim($data['i_op']);
            $close  = $data['f_op_close'];
            $data   = '';
            if($close == 'f'){
                $data  .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"id".$i."\" value=\"".$id."\" type=\"hidden\">
                <input name=\"jml\" value=\"".$jml."\" type=\"hidden\">
                <input name=\"iop".$i."\" value=\"".$iop."\" type=\"hidden\">";
            }else{
                $data .= "<a href=\"#\" onclick='unclosing(\"$id\",\"$iop\"); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('id');
        $datatables->hide('i');
        $datatables->hide('jml');
        return $datatables->generate();
    }

    public function closing($id)
    {
        $data = array(
            'f_op_close' => 't', 
        );
        $this->db->where('id', $id);
        $this->db->update('tm_opbb', $data);
    }

    public function unclosing($id)
    {
        $data = array(
            'f_op_close' => 'f', 
        );
        $this->db->where('id', $id);
        return $this->db->update('tm_opbb', $data);
    }
}
/* End of file Mmaster.php */