<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder){
        $thbl = date('Ym');
        $iperiode = date('Ym', strtotime('-1 month', strtotime($thbl)));
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_op,
                to_char(d_op, 'dd-mm-yyyy') AS d_op, 
                e_supplier_name,
                n_print,
                '$folder' AS folder
            FROM
                tm_op a,
                tr_supplier b
            WHERE
                a.i_supplier = b.i_supplier
                AND to_char(d_op, 'yyyymm') >= '202001'
            ORDER BY
                a.i_op DESC
        ", FALSE);
        $datatables->add('action', function ($data) {
            $id             = trim($data['i_op']);
            $folder         = $data['folder'];
            $n_print        = $data['n_print'];
            $data           = '';
            if ($n_print < 1) {
                $data          .= "<a href=\"#\" onclick='printx(\"$id\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
            }
            return $data;
        });

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print']=='0') {
                $data = '<span class="label label-info label-rouded">BELUM</span>';
            }else{
                $data = '<span class="label label-success label-rouded">SUDAH</span>';
            }
            return $data;
        });
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($id){   
        $this->db->select("
                *
            FROM
                tm_op
            INNER JOIN tr_supplier ON
                (tm_op.i_supplier = tr_supplier.i_supplier)
            INNER JOIN tr_op_status ON
                (tm_op.i_op_status = tr_op_status.i_op_status)
            INNER JOIN tr_area ON
                (tm_op.i_area = tr_area.i_area)
            WHERE
                tm_op.i_op = '$id'
            ORDER BY
                tm_op.i_op DESC
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacadetail($id){
        $reff='';
        $this->db->select("i_reff FROM tm_op WHERE tm_op.i_op = '$id'",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $tes){
                $reff=$tes->i_reff;
            }
        }
        if(substr($reff,0,3)=='SPB'){
            $this->db->select("a.*, b.e_remark from tm_op_item a, tm_spb_item b, tm_op c 
                where a.i_op='$id '
                and a.i_op=c.i_op
                and a.i_op=b.i_op and c.i_reff=b.i_spb and c.i_area=b.i_area 
                and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                and a.i_product_grade=b.i_product_grade order by a.i_product asc",false);
        }else{
            $this->db->select("a.*, b.e_remark from tm_op_item a, tm_spmb_item b, tm_op c 
                where a.i_op='$id '
                and a.i_op=c.i_op
                and a.i_op=b.i_op and c.i_reff=b.i_spmb and c.i_area=b.i_area 
                and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                and a.i_product_grade=b.i_product_grade order by a.i_product asc",false);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function close($id){
        return $this->db->query("
            UPDATE
                tm_op
            SET
                n_print = n_print + 1
            WHERE
                i_op = '$id'
        ",false);
    }

    public function company($id_company){
        return $this->db->query("
            SELECT
                *
            FROM
                public.company a,
                public.constant b
            WHERE
                a.id = b.id_company
                AND id = '$id_company'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
