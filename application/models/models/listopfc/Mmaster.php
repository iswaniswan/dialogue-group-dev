<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*public function bacaarea($idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area where f_area_real='t' order by i_area
        ", FALSE)->result();
    }

    public function cekperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = '';
        }
        return $iperiode;
    } 

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_supplier');
        $this->db->where('username',$username);
        $this->db->where('i_supplier','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $supplier = '00';
        }else{
            $supplier = 'xx';
        }
        return $supplier;
    }*/

    public function cekdepartemen($username,$idcompany){
        $this->db->select('i_departement');
        $this->db->from('public.tm_user_deprole');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $idepartemen = $kuy->i_departement; 
        }else{
            $idepartemen = '';
        }
        return $idepartemen;
    }

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($dfrom, $dto, $cekdepartemen, $folder,$title){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT
                                i_op,
                                d_op,
                                i_reff,
                                d_reff,
                                i_area,
                                i_supplier,
                                e_supplier_name,
                                f_op_cancel,
                                f_op_close,
                                n_op_print,
                                i_do,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$cekdepartemen' as departemen,
                                '$folder' as folder,
                                '$title' as title
                            FROM
                                v_list_opfc 
                            WHERE
                                d_op >= '$dfrom' 
                                and d_op <= '$dto' 
                            ORDER BY
                               i_op desc
                            ",false);

        
        $datatables->edit('n_op_print', function($data){
            $n_op_print = trim($data['n_op_print']);
            $data = "<a><i class='fa fa-print'></i></a>";
            if($n_op_print>0){
                return $data;
            }else{
                return '';
            }
        });
        
       $datatables->edit('d_op', function($data){
            return date("d-m-Y", strtotime($data['d_op']));
        });

        $datatables->edit('d_reff', function($data){
            return date("d-m-Y", strtotime($data['d_reff']));
        });

        $datatables->edit('f_op_cancel', function($data){
            $f_op_cancel = trim($data['f_op_cancel']);
            $f_op_close = trim($data['f_op_close']);
            if($f_op_cancel == 't'){
                return 'Batal';
            }elseif(($f_op_close == 't')){
                return 'Close';
            }else{
                return 'Proses DO';
            }
        });

        $datatables->add('action', function ($data) {
            $ireff      = $data['i_reff'];
            $folder     = $data['folder'];
            $iarea      = $data['i_area'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $iop        = $data['i_op'];
            $isupplier  = $data['i_supplier'];
            $fopcancel  = $data['f_op_cancel'];
            $title      = $data['title'];
            $departemen = $data['departemen'];
            $data       = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iop/$isupplier/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            if($fopcancel == 'f'){
                $data .= "<a href=\"#\" onclick='hapus(\"$iop\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_op_close');
        $datatables->hide('i_supplier');
        $datatables->hide('departemen');
        return $datatables->generate();
    }

    public function delete($iop,$iarea) {
		$this->db->query("update tm_opfc set f_op_cancel='t' WHERE i_op='$iop' and i_area='$iarea'");
    }

    public function bacaop($iop,$iarea){
        return $this->db->query("
                        select
                           *,
                           tr_op_status.e_op_statusname
                        from
                           tm_opfc 
                           left join
                              tr_op_status 
                              on (tm_opfc.i_op_status = tr_op_status.i_op_status) 
                           left join
                              tr_supplier 
                              on (tr_supplier.i_supplier = tm_opfc.i_supplier) 
                           left join
                              tr_area 
                              on (tm_opfc.i_area = tr_area.i_area) 
                        where
                           tm_opfc.i_op = '$iop' 
                           and tm_opfc.i_area = '$iarea'
                        "
                        ,false);
    }

    public function bacadetailop($iop){
        return $this->db->query("
                        select
                            * 
                        from
                        tm_opfc_item 
                        inner join
                           tm_opfc 
                           on (tm_opfc.i_op = tm_opfc_item.i_op) 
                        left join
                           tr_product_motif 
                           on (tr_product_motif.i_product_motif = tm_opfc_item.i_product_motif 
                           and tr_product_motif.i_product = tm_opfc_item.i_product) 
                        inner join
                           tr_area 
                           on (tr_area.i_area = tm_opfc.i_area) 
                        where
                            tm_opfc.i_op = '$iop' 
                        order by
                            n_item_no
                        ",false);
    }

    public function getop(){
        $this->db->select('*');
        $this->db->from('tr_op_status');
        //$this->db->where('i_op_status',);
        return $this->db->get();
    }

    function updateheader( $iop, $dop, $isupplier, $iarea, $iopstatus, $ireff, $eopremark, $ndeliverylimit, $ntoplength, $dreff, $old, $iopold){
      $query   = $this->db->query("SELECT current_timestamp as c");
      $row     = $query->row();
      $dupdate= $row->c;
      $this->db->set(
         array(
            'i_op'              => $iop,
            'i_supplier'        => $isupplier,
            'i_area'            => $iarea,
            'i_op_status'       => $iopstatus,
            'i_reff'            => $ireff,
            'd_op'              => $dop,
            'd_update'          => $dupdate,
            'e_op_remark'       => $eopremark,
            'n_delivery_limit'  => $ndeliverylimit,
            'n_top_length'      => $ntoplength,
            'n_op_print'        => 0,
            'd_reff'            => $dreff,
            'f_op_close'        => 'f',
            'f_op_cancel'       => 'f',
            'i_op_old'          => $iopold
         )
      );
      $this->db->where('i_op', $iop);
      $this->db->update('tm_opfc');
    }

    public function deletedetail($iproduct, $iproductgrade, $iop, $iproductmotif){
      $this->db->query("
                        DELETE 
                        FROM 
                            tm_opfc_item 
                        WHERE 
                            i_op='$iop'
                            and i_product='$iproduct' 
                            and i_product_grade='$iproductgrade'
                            and i_product_motif='$iproductmotif'");
      return TRUE;
    }

    public function insertdetail($iop,$iproduct,$iproductgrade,$eproductname,$norder,$vproductmill,$iproductmotif,$i){
      $this->db->set(
         array(
               'i_op'            => $iop,
               'i_product'       => $iproduct,
               'i_product_grade' => $iproductgrade,
               'i_product_motif' => $iproductmotif,
               'n_order'         => $norder,
               'v_product_mill'  => $vproductmill,
               'e_product_name'  => $eproductname,
               'n_item_no'       => $i
         )
      );
      $this->db->insert('tm_opfc_item');
    }
}

/* End of file Mmaster.php */
