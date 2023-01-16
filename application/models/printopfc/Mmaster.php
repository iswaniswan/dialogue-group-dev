<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekstatus($idcompany,$username){
        $query = $this->db->select("i_status from public.tm_user where id_company='$idcompany' 
                                     and username='$username'",FALSE);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $ar =  $query->row();
            $status = $ar->i_status;
        }else{
            $status='';
        }
        return $status;
    }

    public function data($dfrom,$dto,$folder,$i_menu,$status){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        SELECT 
        a.i_op, b.e_supplier_name,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$folder' as folder,
                '$status' as status 
        from dgu.tm_opfc a, dgu.tr_supplier b
        where a.i_supplier=b.i_supplier and f_op_cancel='f'
        and a.d_op >= to_date('$dfrom','dd-mm-yyyy') and a.d_op <= to_date('$dto','dd-mm-yyyy')
        order by a.i_op desc"
        , FALSE);

        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('status');
        $datatables->add('action', function ($data) {
        $folder         = $data['folder'];
        $dfrom          = $data['dfrom'];
        $dto            = $data['dto'];
        $iop            = $data['i_op'];
        $data='';
        $data      .= "<a href=\"javascript:yyy('".$iop."');\"><i class='fa fa-print'></i></a>";
            return $data;
        });
        return $datatables->generate();
    }
    function baca($iop)
    {
        $this->db->select(" * from dgu.tm_opfc
                            inner join dgu.tr_supplier on (tm_opfc.i_supplier=tr_supplier.i_supplier)
                            inner join dgu.tr_op_status on (tm_opfc.i_op_status=tr_op_status.i_op_status)
                            inner join dgu.tr_area on (tm_opfc.i_area=tr_area.i_area)
                            where tm_opfc.i_op = '$iop' and f_op_cancel='f'
                            order by tm_opfc.i_op desc",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    function bacadetail($iop)
    {
        $this->db->select(" a.* from dgu.tm_opfc_item a, tm_opfc c where a.i_op='$iop'
                           and a.i_op=c.i_op and f_op_cancel='f'
                           order by a.n_item_no",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
          }
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


