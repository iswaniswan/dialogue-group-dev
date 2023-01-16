<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea()
    {
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'NA';
        }else{
            return 'XX';
        }
    }

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

    public function bacaarea($username,$idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE);
    }

    public function data($dfrom,$dto,$folder,$i_menu,$status){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        SELECT 
        a.i_op, b.e_supplier_name, a.n_print,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$folder' as folder,
                '$status' as status 
        from dgu.tm_op a, dgu.tr_supplier b, dgu.tm_spb c, dgu.tm_spmb_item d
        where 
        a.i_supplier=b.i_supplier
        and d.i_op=a.i_op 
        and d.i_spmb=c.i_spmb 
        and not c.i_spmb isnull
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
        $n_print            = $data['n_print'];
        $data='';
        if($n_print < 1){
            $data      .= "<a href=\"javascript:yyy('".$iop."');\"><i class='fa fa-print'></i></a>";
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
        return $datatables->generate();
    }
    function baca($iop)
    {
        $this->db->select(" * from dgu.tm_op
                            inner join dgu.tr_supplier on (tm_op.i_supplier=tr_supplier.i_supplier)
                            inner join dgu.tr_op_status on (tm_op.i_op_status=tr_op_status.i_op_status)
                            inner join dgu.tr_area on (tm_op.i_area=tr_area.i_area)
                            where tm_op.i_op = '$iop'
                            order by tm_op.i_op desc",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    function bacadetail($iop)
    {
      $reff='';
      $this->db->select(" i_reff from dgu.tm_op where tm_op.i_op = '$iop'",false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              foreach($query->result() as $tes){
          $reff=$tes->i_reff;
        }
          }
      if(substr($reff,0,3)=='SPB'){
            $this->db->select("a.*, b.e_remark from dgu.tm_op_item a, dgu.tm_spb_item b, dgu.tm_op c where a.i_op='$iop'
                           and a.i_op=c.i_op
                           and a.i_op=b.i_op and c.i_reff=b.i_spb and c.i_area=b.i_area 
                           and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                           and a.i_product_grade=b.i_product_grade order by a.n_item_no",false);
      }else{
            $this->db->select("a.*, b.e_remark from dgu.tm_op_item a, dgu.tm_spmb_item b, dgu.tm_op c where a.i_op='$iop'
                           and a.i_op=c.i_op
                           and a.i_op=b.i_op and c.i_reff=b.i_spmb and c.i_area=b.i_area 
                           and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                           and a.i_product_grade=b.i_product_grade order by a.n_item_no",false);
      }
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              return $query->result();
          }
    }

    function bacadetailspb($iop)
    {
        $this->db->select("a.*,e.i_op, e.d_op from dgu.tm_spb_item a, dgu.tm_spb b, dgu.tm_spmb c, dgu.tm_spmb_item d, dgu.tm_op e
                         where e.i_op='$iop'
                         and a.i_spb=b.i_spb and a.i_area=b.i_area and b.i_spmb=c.i_spmb
                         and c.i_spmb=d.i_spmb
                         and d.i_op=e.i_op 
                         and a.i_product=d.i_product and a.i_product_motif=d.i_product_motif
                         and a.i_product_grade=d.i_product_grade order by a.i_product, a.i_product_motif, a.i_product_grade, a.i_spb",false);
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


