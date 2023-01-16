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
                                a.i_op,
                                a.d_op,
                                a.i_op_old,
                                c.i_customer,
                                a.i_reff :: character varying AS i_reff,
                                a.d_reff,
                                a.i_area,
                                c.i_spb_old,
                                e.i_spmb_old,
                                b.e_supplier_name,
                                a.f_op_cancel,
                                a.f_op_close,
                                a.n_op_print,
                                f.i_do,
                                f.d_do,
                                a.i_supplier,
                                d.e_area_name,
                                g.e_customer_name,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$cekdepartemen' as departemen,
                                '$folder' as folder,
                                '$title' as title
                            FROM 
                                tm_op a
                                LEFT JOIN tm_do f ON f.i_area = a.i_area AND f.i_op = a.i_op
                                LEFT JOIN tm_spb c ON c.i_spb = a.i_reff::bpchar AND c.i_area = a.i_area
                                LEFT JOIN tm_spmb e ON e.i_spmb = a.i_reff::bpchar AND e.i_area = a.i_area
                                JOIN tr_supplier b ON a.i_supplier = b.i_supplier
                                JOIN tr_area d ON a.i_area = d.i_area
                                LEFT JOIN tr_customer g ON c.i_customer = g.i_customer AND c.i_area = g.i_area AND c.i_area = d.i_area
                            WHERE
                                d_op >= '$dfrom' and
                                d_op <= '$dto'
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

        $datatables->edit('i_customer', function($data){
            if($data['i_customer']!=''){
                return '('.($data['i_customer']).')'.($data['e_customer_name']);
            }else{
                return '()';
            }
           
        });

        $datatables->edit('i_spb_old', function($data){
            $i_spb_old = trim($data['i_spb_old']);
            if($i_spb_old != null){
                return ($data['i_spb_old']);
            }else{
                return ($data['i_spmb_old']);
            }
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
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ireff/$iop/$isupplier/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $cek_do = $this->db->query("select i_op from tm_do where i_op='$iop' and f_do_cancel='f'");
            if(($departemen == '6' || $departemen == '1') && $fopcancel = 'f' && $cek_do->num_rows() == 0){
                $data .= "<a href=\"#\" onclick='hapus(\"$iop\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_spmb_old');
        $datatables->hide('i_op_old');
        $datatables->hide('f_op_close');
        $datatables->hide('i_supplier');
        $datatables->hide('departemen');
        $datatables->hide('e_area_name');
        $datatables->hide('e_customer_name');
        $datatables->hide('d_do');
        return $datatables->generate();
    }

    function bacaop($iop,$area){
      $this->db->select(" *, tr_op_status.e_op_statusname from tm_op
            left join tm_spb on (tm_spb.i_spb=tm_op.i_reff and tm_spb.i_area=tm_op.i_area)
            left join tr_op_status on (tm_op.i_op_status=tr_op_status.i_op_status)
            left join tr_supplier on (tr_supplier.i_supplier=tm_op.i_supplier)
            left join tr_customer on (tm_spb.i_customer=tr_customer.i_customer)
            left join tr_salesman on (tm_spb.i_salesman=tr_salesman.i_salesman)
            left join tr_customer_area on (tm_spb.i_customer=tr_customer_area.i_customer)
            left join tm_spmb on (tm_spmb.i_spmb=tm_op.i_reff)
            left join tr_area on (tm_spmb.i_area=tr_area.i_area or tm_spb.i_area=tr_area.i_area)
            where tm_op.i_op ='$iop' and tm_op.i_area ='$area'", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->row();
      }
    }

    function bacadetailop($iop,$area){
      $this->db->select(" * from tm_op_item
                        inner join tm_op on (tm_op.i_op=tm_op_item.i_op)
                        left join tm_spb on (tm_spb.i_spb=tm_op.i_reff and tm_spb.i_area=tm_op.i_area)
                        left join tm_spmb on (tm_spmb.i_spmb=tm_op.i_reff and tm_spmb.i_area=tm_op.i_area)
                        left join tr_product_motif
                        on (tr_product_motif.i_product_motif=tm_op_item.i_product_motif
                        and tr_product_motif.i_product=tm_op_item.i_product)
                        inner join tr_area on (tr_area.i_area=tm_op.i_area)
                        where tm_op.i_op = '$iop'
                        order by n_item_no", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }

    public function getop(){
        $this->db->select('*');
        $this->db->from('tr_op_status');
        //$this->db->where('i_op_status',);
        return $this->db->get();
    }

    function cekproduct($iproduct){
        $query=$this->db->query("select i_supplier from tr_product where i_product='$iproduct'",false);
        if ($query->num_rows() > 0){
           return $query->row();
        }
    }

    function insertheader( $iop, $dop, $isupplier, $iarea, $iopstatus, $ireff, $eopremark, $ndeliverylimit, $ntoplength, $dreff, $old, $iopold){
      $query   = $this->db->query("SELECT current_timestamp as c");
      $row     = $query->row();
      $dentry  = $row->c;
      $this->db->set(
         array(
            'i_op'               => $iop,
            'i_supplier'         => $isupplier,
            'i_area'             => $iarea,
            'i_op_status'        => $iopstatus,
            'i_reff'             => $ireff,
            'd_op'               => $dop,
            'd_entry'            => $dentry,
            'e_op_remark'        => $eopremark,
            'n_delivery_limit'   => $ndeliverylimit,
            'n_top_length'       => $ntoplength,
            'n_op_print'         => 0,
            'i_op_old'           => $iopold,
            'd_reff'             => $dreff,
            'f_op_close'         => 'f',
            'f_op_cancel'        => 'f'
         )
      );
      $this->db->insert('tm_op');
    }

    function insertdetail($iop,$iproduct,$iproductgrade,$eproductname,$norder,$vproductmill,$iproductmotif,$i){
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

      $this->db->insert('tm_op_item');
    }

    function updatespb($ireff,$iop,$iproduct,$iproductgrade,$iproductmotif,$iarea,$norder){
       $tmp=explode('-',$ireff);
       if($tmp[0]=='SPB'){
            $data = array(
                            'i_op'  => $iop
                        );
          $this->db->where('i_spb', $ireff);
          $this->db->where('i_area', $iarea);
          $this->db->where('i_product', $iproduct);
          $this->db->where('i_product_grade', $iproductgrade);
          $this->db->where('i_product_motif', $iproductmotif);
          $this->db->update('tm_spb_item', $data);
          $data = array(
                        'f_spb_pemenuhan'    => 't'
                        );
          $this->db->where('i_spb', $ireff);
          $this->db->where('i_area', $iarea);
          $this->db->update('tm_spb', $data);
       }else if($tmp[0]=='SPMB'){
        $this->db->query("  update tm_spmb_item set i_op='$iop'
                            where upper(i_spmb)='$ireff' and i_product='$iproduct' and i_product_grade='$iproductgrade'
                            and i_product_motif='$iproductmotif'",false);
         $query = $this->db->query(" select distinct(b.i_spmb) as no , a.i_area, c.e_area_name as name, '' as e_customer_name
                                    from tm_spmb_item b, tm_spmb a, tr_area c
                                    where not a.i_approve1 isnull
                                    and not a.i_approve2 isnull
                                    and not a.i_store isnull
                                    and not a.i_store_location isnull
                                    and a.f_op = 't'
                                    and (b.n_saldo>0)
                                    and upper(a.i_spmb)='$ireff'
                                    and a.i_spmb=b.i_spmb
                                    and a.i_area=c.i_area ",false);
         if($query->num_rows()==0){
            $this->db->query("   update tm_spmb set  f_spmb_pemenuhan='t' where upper(i_spmb)='$ireff'  ",false);
         }
       }
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
      $this->db->update('tm_op');
    }

    public function deletedetail($iproduct, $iproductgrade, $iop, $iproductmotif){
      $this->db->query("DELETE FROM tm_op_item WHERE i_op='$iop'
                     and i_product='$iproduct' and i_product_grade='$iproductgrade'
                     and i_product_motif='$iproductmotif'");
      return TRUE;
    }
  
}

/* End of file Mmaster.php */
