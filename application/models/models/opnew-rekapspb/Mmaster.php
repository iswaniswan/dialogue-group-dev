<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function data($i_menu){
		    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select distinct(b.i_spmb) as no, a.d_spmb as tgl, a.i_spmb_old as asal, a.i_area as i_area, c.e_area_name as e_area_name, 
                            '' as e_customer_name, e.i_op, e.d_op, d.i_spb, d.d_spb,'$i_menu' as i_menu
                            from tm_spmb_item b, tm_spmb a
                            left join tm_op e on (a.i_spmb=e.i_reff and a.i_area=e.i_area and e.f_op_close='f')
                            inner join tm_spb d on(not d.i_spmb isnull and a.i_spmb=d.i_spmb and a.i_area=d.i_area and d.f_spb_stockdaerah='t'
                            and d.f_spb_cancel='f' and d.i_store isnull and not d.i_approve1 isnull and not d.i_approve2 isnull)
                            , tr_area c
                            where not a.i_approve2 isnull and not a.i_store isnull and not a.i_store_location isnull 
                            and a.f_op = 't' and a.f_spmb_pemenuhan='f'
                            and (b.n_deliver<b.n_acc and b.n_acc>0 and b.n_saldo>0)
                            and a.i_spmb=b.i_spmb 
                            and a.i_area=c.i_area 
                            and a.f_spmb_opclose='f'
                            order by tgl, no
                            ");
        $datatables->add('action', function ($data) {
        $i_spb = trim($data['no']);
        $i_area = trim($data['i_area']);
        $i_menu = $data['i_menu'];
        $data = '';
        if(check_role($i_menu, 3)){
            $data .= "<a href=\"#\" onclick='show(\"opnew_rekapspb/cform/edit/$i_spb/$i_area/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
        }
        return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_area');
        return $datatables->generate();
	  }

    /*function baca($ispb,$iarea){
      $tmp=explode('-',$ispb);
      if($tmp[0]=='SPB'){
        $this->db->select("* from tm_spb
                            inner join tr_customer on (tm_spb.i_customer=tr_customer.i_customer)
                            inner join tr_salesman on (tm_spb.i_salesman=tr_salesman.i_salesman)
                            inner join tr_customer_area on (tm_spb.i_customer=tr_customer_area.i_customer)
                            inner join tr_store on (tm_spb.i_store=tr_store.i_store)
                            inner join tr_store_location on (tm_spb.i_store_location = tr_store_location.i_store_location)
                            inner join tr_price_group on (tm_spb.i_price_group=tr_price_group.i_price_group)
                            where i_spb ='$ispb' and tm_spb.i_area='$iarea'", false);
      }else if($tmp[0]=='SPMB'){
        $this->db->select("a.*, b.e_area_name, 'STOCK '||a.i_area||'-'||b.e_area_name as e_customer_name from tm_spmb a, tr_area b
                            where a.i_spmb ='$ispb' and a.i_area='$iarea' and a.i_area=b.i_area", false);
      }
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->row();
      }
    }

    function bacadetail($ispb,$iarea){
      $tmp=explode('-',$ispb);
         if($tmp[0]=='SPB'){
            $this->db->select(" a.*, b.i_store, b.i_store_location, b.i_price_group, c.e_product_motifname, d.v_product_mill
                           from tm_spb_item a, tm_spb b, tr_product_motif c, tr_product d
                           where b.i_spb = '$ispb' and b.i_area='$iarea' and b.i_spb=a.i_spb and b.i_area=a.i_area
                           and a.i_product=d.i_product and a.n_deliver<a.n_order and d.i_product_status<>'4'
                           and a.i_product_motif=c.i_product_motif and a.i_product=c.i_product and a.i_op isnull
                           order by a.n_item_no", false);
         }else if($tmp[0]=='SPMB'){
            $this->db->select(" a.*, b.i_store, b.i_store_location, c.e_product_motifname, d.v_product_mill
                           from tm_spmb_item a, tm_spmb b, tr_product_motif c, tr_product d
                           where b.i_spmb = '$ispb' and b.i_spmb=a.i_spmb and a.i_product=d.i_product
                           and b.i_area='$iarea' and a.n_deliver<a.n_acc and a.n_acc>0 and a.n_saldo>0 and a.n_stock<a.n_acc
                           and a.i_product_motif=c.i_product_motif and a.i_product=c.i_product and d.i_product_status<>'4'
                           order by a.n_item_no", false);
         }
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         return $query->result();
      }
    }

    function cekproduct($iproduct){
      $query=$this->db->query("select i_supplier from tr_product where i_product='$iproduct'",false);
        if ($query->num_rows() > 0){
           return $query->row();
        }
    }

    function runningnumber($thbl){
      $th = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
      $this->db->select("n_modul_no as max from tm_dgu_no where i_modul='OP' and substr(e_periode,1,4)='$th' for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
         foreach($query->result() as $row){
           $terakhir=$row->max;
         }
         $noop  =$terakhir+1;
      $this->db->query("update tm_dgu_no set n_modul_no=$noop where i_modul='OP' and substr(e_periode,1,4)='$th' ", false);
         settype($noop,"string");
         $a=strlen($noop);
         while($a<6){
           $noop="0".$noop;
           $a=strlen($noop);
         }
         $noop  ="OP-".$thbl."-".$noop;
         return $noop;
      }else{
         $noop  ="000001";
         $noop  ="OP-".$thbl."-".$noop;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) values ('OP','00',$asal,1)");
         return $noop;
      }
    }

    function insertheader($iop, $dop, $isupplier, $iarea, $iopstatus, $ireff, $eopremark, $ndeliverylimit, $ntoplength, $dreff, $old, $iopold){
      $dentry = $this->db->query('SELECT current_timestamp AS c')->row()->c;
      $data = array(
        'i_op'              => $iop,
        'i_supplier'        => $isupplier,
        'i_area'            => $iarea,
        'i_op_status'       => $iopstatus,
        'i_reff'            => $ireff,
        'd_op'              => $dop,
        'd_entry'           => $dentry,
        'e_op_remark'       => $eopremark,
        'n_delivery_limit'  => $ndeliverylimit,
        'n_top_length'      => $ntoplength,
        'n_op_print'        => 0,
        'i_op_old'          => $iopold,
        'd_reff'            => $dreff,
        'f_op_close'        => 'f',
        'f_op_cancel'       => 'f'
      );
      $this->db->insert('tm_op', $data);
    }

    function insertdetail($iop,$iproduct,$iproductgrade,$eproductname,$norder,$vproductmill,$iproductmotif,$i){
      $data = array(
        'i_op'            => $iop,
        'i_product'       => $iproduct,
        'i_product_grade' => $iproductgrade,
        'i_product_motif' => $iproductmotif,
        'n_order'         => $norder,
        'v_product_mill'  => $vproductmill,
        'e_product_name'  => $eproductname,
        'n_item_no'       => $i
      );
      $this->db->insert('tm_op_item', $data);
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
        $data = array(
          'i_op'  => $iop
        );
        $this->db->where('upper(i_spmb)',$ireff);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', $iproductmotif);
        $this->db->update('tm_spmb_item', $data);

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
          $data = array(
            'f_spmb_pemenuhan'    => 't'
          );
          $this->db->where('upper(i_spmb)',$ireff);
          $this->db->where('i_area', $iarea);
          $this->db->update('tm_spmb', $data);
        }
      }
    }

    function bacaop($iop,$area){
      $this->db->select(" * from tm_op
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

    public function deletedetail($iproduct, $iproductgrade, $iop, $iproductmotif){
      $this->db->query("DELETE FROM tm_op_item WHERE i_op='$iop' and i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
      return TRUE;
    }

    public function getop(){
      $this->db->select('*');
      $this->db->from('tr_op_status');
      //$this->db->where('i_op_status',);
      return $this->db->get();
    }*/
}

/* End of file Mmaster.php */
