<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    // $datatables->query("select a.i_bonm, a.d_bonm, a.i_referensi, a.e_remark, a.f_bonm_cancel,
    //             '$i_menu' as i_menu                
    //             FROM tm_bonmmasuk_packing a");
    $datatables->query("SELECT a.i_op_code , a.d_op , a.i_customer , b.e_customer_name , a.i_op_reff ,
                        a.v_total_gross , a.v_total_discount , a.v_total_netto , a.f_op_cancel , a.i_status , a.i_promo , 
                        '$i_menu' as i_menu , c.e_status 
                        from tm_op a
                        inner join tr_customer b on b.i_customer = a.i_customer 
                        inner join tm_status_dokumen c on c.i_status = a.i_status ");

    // $datatables->edit('f_bonm_cancel', function ($data) {
    //       $f_bonm_cancel = trim($data['f_bonm_cancel']);
    //       if($f_bonm_cancel != 't'){
      $datatables->edit('f_op_cancel', function ($data) {
          $f_op_cancel = trim($data['f_op_cancel']);
          if($f_op_cancel != 't'){
            return "Aktif";
          }else{
             return "Batal";
          }
      });

    $datatables->add('action', function ($data) {
            // $ibonm          = trim($data['i_bonm']);
            // $f_bonm_cancel  = trim($data['i_bonm']);
            // $i_menu         = $data['i_menu'];
            // $data           = '';
            $iopcode        = trim($data['i_op_code']);
            $f_op_cancel    = $data['f_op_cancel'];
            $i_menu         = $data['i_menu'];
            $i_status       = trim($data['i_status']);
            $data           = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"konverisoutstandingforecastspb/cform/view/$iopcode/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            
            if(check_role($i_menu, 3) && $f_op_cancel == 'f' && $i_status !='6' && $i_status != '4'){
                $data .= "<a href=\"#\" onclick='show(\"konverisoutstandingforecastspb/cform/edit/$iopcode/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }   
            if(check_role($i_menu, 1)&& $f_op_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
                $data .= "<a href=\"#\" onclick='show(\"konverisoutstandingforecastspb/cform/approve/$iopcode/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            }
            // if($f_op_cancel !='t'){
            //   $data .= "<a href=\"#\" title='Print' onclick='printx(\"$iopcode\"); return false;'><i class='fa fa-print'></i></a>&nbsp;&nbsp;";
            // }
            if ($f_op_cancel!='t' && $i_status != '6' && $i_status !='4') {
                $data .= "<a href=\"#\" onclick='cancel(\"$iopcode\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            
      return $data;
        });
        $datatables->hide('v_total_gross'); 
        $datatables->hide('v_total_discount');
        $datatables->hide('v_total_netto');
        $datatables->hide('i_customer');                
        $datatables->hide('f_op_cancel');
        $datatables->hide('i_status');
        $datatables->hide('i_menu');
        return $datatables->generate();
  }

  public function bacacustomer(){
    $this->db->select('i_customer, e_customer_name, e_customer_address');
    $this->db->from('tr_customer');
    $this->db->order_by('e_customer_name','ASC');
    return $this->db->get()->result();
  }

  public function bacapromo(){
    $this->db->select('i_promo, e_promo_name');
    $this->db->from('tm_promo');
    $this->db->order_by('i_promo','ASC');
    return $this->db->get()->result();
  }

  public function getcustaddress($icust){
    // return $this->db->query("
    //     SELECT * from tr_customer
    //     where i_customer = '$icust'
    //   ", false);
    return $this->db->query("
        SELECT a.i_customer, a.e_customer_address, b.i_fc from tr_customer a
        left join tm_fc_distributor b on b.i_customer = a.i_customer 
        where a.i_customer = '$icust'
      ", false);
  }

  public function gethead($iproduct){
    return $this->db->query("SELECT * from tm_barang_wip
                             where i_kodebrg = '$iproduct'", false);
 }

 public function getdetail($iproduct){
  return $this->db->query(" SELECT a.*, b.e_material_name, c.e_namabrg, d.e_color_name
                            from tr_polacutting a
                            inner join tr_material b on a.i_material = b.i_material
                            inner join tm_barang_wip c on a.i_product = c.i_kodebrg
                            inner join tr_color d on a.i_color = d.i_color
                            where i_product = '$iproduct'", false);
}

  public function getreferensi($iasal) {
    $this->db->select("i_bonk, d_bonk from tm_bonmkeluar_qc where i_bagian='$iasal'");
        return $this->db->get();
  }

  public function getdataitem($ireff){
        $ireff        = $this->input->post('ireff');

        $this->db->select("a.i_bonk, a.i_product, b.e_product_basename, a.i_color, c.e_color_name, a.n_quantity from tm_bonmkeluar_qc_detail a
                          join tr_product_base b on a.i_product = b.i_product_motif
                          join tr_color c on a.i_color = c.i_color
                          where a.i_bonk = '$ireff'",false);
        $data = $this->db->get();
        return $data;
  }

  // function runningnumber($lok,$yearmonth){
  //       $bl = substr($yearmonth,4,2);
  //       $th = substr($yearmonth,0,4);
  //       $thn = substr($yearmonth,2,2);
  //       // $area= substr($ibagian,5,2);
  //       $area= $lok;
  //       $asal= substr($yearmonth,0,4);
  //       $yearmonth= substr($yearmonth,0,4);

  //       $this->db->select(" n_modul_no as max from tm_dgu_no 
  //                           where i_modul='SPB'
  //                           and i_area='$area'
  //                           and e_periode='$asal' 
  //                           and substring(e_periode,1,4)='$th' for update", false);
  //       $query = $this->db->get();
  //       if ($query->num_rows() > 0){
  //         foreach($query->result() as $row){
  //           $terakhir=$row->max;
  //         }
  //         $nopp  =$terakhir+1;
  //               $this->db->query("update tm_dgu_no 
  //                           set n_modul_no=$nopp
  //                           where i_modul='SPB'
  //                           and e_periode='$asal' 
  //                           and i_area='$area'
  //                           and substring(e_periode,1,4)='$th'", false);
  //         settype($nopp,"string");
  //         $a=strlen($nopp);
  
  //         //u/ 0
  //         while($a<5){
  //           $nopp="0".$nopp;
  //           $a=strlen($nopp);
  //         }
  //           $nopp  ="SPB-".$area."-".$thn.$bl."-".$nopp;
  //         return $nopp;
  //       }else{
  //         $nopp  ="0001";
  //         $nopp  ="SPB-".$area."-".$thn.$bl."-".$nopp;
  //         $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
  //                            values ('SPB','$area','$asal',1)");
  //         return $nopp;
  //       }
  //   }

  function runningnumber($lok,$yearmonth){
    $bl = substr($yearmonth,4,2);
    $th = substr($yearmonth,0,4);
    $thn = substr($yearmonth,2,2);
    // $area= $lok;
    $area= 'PB';
    $asal= substr($yearmonth,0,4);
    $yearmonth= substr($yearmonth,0,4);

    $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='SPB'
                        and i_area='$area'
                        and e_periode='$asal' 
                        and substring(e_periode,1,4)='$th' for update", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $nopp  =$terakhir+1;
            $this->db->query("update tm_dgu_no 
                        set n_modul_no=$nopp
                        where i_modul='SPB'
                        and e_periode='$asal' 
                        and i_area='$area'
                        and substring(e_periode,1,4)='$th'", false);
      settype($nopp,"string");
      $a=strlen($nopp);

      //u/ 0
      while($a<5){
        $nopp="0".$nopp;
        $a=strlen($nopp);
      }
        $nopp  ="SPB-".$lok."-".$thn.$bl."-".$nopp;
      return $nopp;
    }else{
      $nopp  ="00001";
      $nopp  ="SPB-".$lok."-".$thn.$bl."-".$nopp;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('SPB','$area','$asal',1)");
      return $nopp;
    }
}

    function bacabagian($ilevel, $idepart, $lokasi, $username,$idcompany){
      $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
      // return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
      return $this->db->query(" SELECT trim(a.i_departement) as i_departement
                                from public.tm_user_deprole a
                                inner join public.tr_departement b on a.i_departement = b.i_departement
                                inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
  }

  //   public function //insertheader($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment,$ispbold,$eremarkx, $iproductgroup){       
  //     insertheader($ispb, $dspb, $icustomer, $ibranch, $vspbbersih, $vspb, $vspbdiscounttotal, $iarea){
  //     $dentry  = current_datetime();
  //     $this->db->set(       
  //         array(                       
  //             'i_op_code'         => $ispb,
  //             'i_customer'        => $icustomer,
  //             'i_branch'          => $ibranch,
  //             'd_op'              => $dspb,
  //             'v_total_gross'     => $vspb,
  //             'v_total_discount'  => $vspbdiscounttotal,
  //             'v_total_netto'     => $vspbbersih,
  //             'd_entry'           => $dentry,
  //             'i_area'            => $iarea
  //         )
  //     );
  //     $this->db->insert('tm_op');
  // }

  // public function //insertdetail($ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i){      
  //     insertdetail( $ispb, $iproduct, $eproductname, $vunitprice, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $norder, $eremark, $i, $iarea, $icolor){
  //     $dentry  = current_datetime();
  //     if($eremark=='') {        
  //         $eremark=null;    
  //     }      
  //     $this->db->set(         
  //         array(               
  //             'i_op_code'             => $ispb,
  //             'i_product'             => $iproduct,
  //             'e_product_name'        => $eproductname,
  //             'n_customer_discount1'  => $nspbdiscount1,
  //             'n_customer_discount2'  => $nspbdiscount2,
  //             'n_customer_discount3'  => $nspbdiscount3,
  //             'n_count'               => $norder,
  //             'v_price'               => $vunitprice,
  //             'e_remark'              => $eremark,
  //             'd_entry'               => $dentry,
  //             'i_area'                => $iarea,
  //             'i_color'                => $icolor
  //         )
  //     );
  //     $this->db->insert('tm_op_item');
  // }

    // function insertheader($ibonm, $ibagian, $datebonm, $iasal, $ireff, $eremark){
    function insertheader($ispb, $datespb, $datebatas, $icustomer, $iporeff, $ipromo, $vgross, $ndiskon, $vdiskon, $vnetto, $eremark){
        $dentry = date("Y-m-d ");
        
        $data = array(
                      // 'i_bonm'          => $ibonm,
                      // 'd_bonm'          => $datebonm,
                      // 'i_asal'          => $iasal,
                      // 'i_referensi'     => $ireff,
                      // 'i_bagian'        => $ibagian,
                      // 'd_entry'         => $dentry,
                      // 'e_remark'        => $eremark,
                      'i_op_code'         => $ispb,
                      'd_op'              => $datespb,
                      'd_delivery_limit'  => $datespb,
                      'i_customer'        => $icustomer,
                      'i_op_reff'         => $iporeff,
                      'i_promo'           => $ipromo,
                      'v_total_gross'     => $vgross,
                      'n_total_discount'  => $ndiskon,
                      'v_total_discount'  => $vdiskon,
                      'v_total_netto'     => $vnetto,
                      'd_entry'           => $dentry,
                      'e_op_remark'       => $eremark,
        );
        $this->db->insert('tm_op', $data);
    }

    // function updatereceive($ireff, $datebonm){
    //        $data = array(
    //               'f_packing'          => 't',
    //               'd_qc_receive'       => $datebonm,
                      
    //     );
    //     $this->db->where('i_bonk', $ireff);
    //     $this->db->update('tm_bonmkeluar_qc', $data);   
    // }

    // function insertdetail($ibonm, $iproduct, $icolorpro, $nquantitypro, $nquantitymasuk, $edesc, $no){        
    function insertdetail($ispb, $iproduct, $vharga, $nquantity, $ndisc, $edesc, $no){
        $data = array(
                      'i_op_code'     => $ispb,
                      'i_product'     => $iproduct,
                      'v_price'       => $vharga,
                      'n_count'       => $nquantity,
                      'n_residual'    => $nquantity,
                      'n_disc'        => $ndisc,
                      'e_remark'      => $edesc,
                      'n_item_no'     => $no,
        );
        $this->db->insert('tm_op_item', $data);
    } 

    // function cek_data($ibonm) {
    //     $this->db->select("a.i_bonm, to_char(a.d_bonm, 'dd-mm-yyyy') as d_bonm,  a.i_bagian, a.i_asal, a.i_referensi, a.e_remark
    //             from tm_bonmmasuk_packing a 
    //             where a.i_bonm = '$ibonm'", false);
    function cek_data($ispb) {
      $this->db->select("a.i_op_code, to_char(a.d_op, 'dd-mm-yyyy') as d_op, to_char(a.d_delivery_limit, 'dd-mm-yyyy') as d_delivery_limit
              ,  a.i_op_reff, a.v_total_gross, a.v_total_discount, a.v_total_netto, i_promo, a.e_op_remark, a.i_customer, b.e_customer_address, a.i_status
              from tm_op a 
              inner join tr_customer b on b.i_customer = a.i_customer
              where a.i_op_code = '$ispb'", false);
        return $this->db->get();
    }

    public function sendd($ispb){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_op_code', $ispb);
      $this->db->update('tm_op', $data);
    }


    function cek_bagian() {
        $this->db->select(" * from tm_sub_bagian", false);
        return $this->db->get();
    }

    function cek_dept(){
        $this->db->select(" a.i_sub_bagian, a.e_sub_bagian
                                FROM tm_sub_bagian a", false);;
        return $this->db->get();
    }

    function cek_referensi() {
        $this->db->select("i_bonk as referensi, d_bonk as ddate from tm_bonmkeluar_qc", false);
        return $this->db->get();
    }

    // function cek_datadetail($ibonm, $iasal){
    //     $this->db->select("a.i_bonm, a.i_product, b.e_product_basename, a.i_color, c.e_color_name, a.n_quantity_keluar_product, a.n_quantity_masuk, a.e_remark
    //                      from tm_bonmmasuk_packing_detail a
    //                      join tr_product_base b on a.i_product = b.i_product_motif
    //                      join tr_color c on a.i_color = c.i_color
    //              where a.i_bonm = '$ibonm'", false);
    function cek_datadetail($ispb, $iasal){
        $this->db->select("a.i_op_code, a.i_product, b.e_product_basename, a.n_disc, a.n_count, a.v_price , a.e_remark
                        from tm_op_item a
                        inner join tr_product_base b ON b.i_product_motif = a.i_product 
                where a.i_op_code = '$ispb'", false);
        return $this->db->get();
    }

    function get_outstanding_head($icustomer, $dspb, $ipromo){
      $this->db->select(" sum((b.n_quantity-coalesce(c.n_quantity_op, 0))*d.v_unitprice) as v_total_gross
                    , sum(coalesce((((b.n_quantity-coalesce(c.n_quantity_op, 0))*d.v_unitprice)*e.n_disc/100), 0)) as v_total_discount
                    , sum((((b.n_quantity-coalesce(c.n_quantity_op, 0))*d.v_unitprice)-coalesce((((b.n_quantity-coalesce(c.n_quantity_op, 0))*d.v_unitprice)*e.n_disc/100), 0))) as v_total_netto
                      from tm_fc_distributor a
                      inner join tm_fc_distributor_detail b on b.i_fc = a.i_fc
                      left join (select b.i_product, sum(b.n_count) as n_quantity_op from tm_op a
                        inner join tm_op_item b on b.i_op_code = a.i_op_code 
                        where a.f_op_cancel = 'f' AND (a.i_status <> '7'  or a.i_status <> '9' or a.i_status <> '5')
                        and a.i_customer = '$icustomer'
                        group by b.i_product , b.e_product_name
                        order by b.i_product ) c on c.i_product = b.i_product
                      inner join tr_product_base d on d.i_product_motif = b.i_product
                      left join (select a.i_promo, b.i_product_base, b.n_disc from tm_promo a
                        inner join tm_promo_item b on b.i_promo = a.i_promo
                        where '$dspb' >= a.d_from and '$dspb' <= a.d_to and a.i_promo='$ipromo') e on e.i_product_base = b.i_product
                      where date_part('month', d_fc) = EXTRACT(month FROM now())and date_part('year', d_fc)= EXTRACT(year FROM now())
                      and a.i_customer = '$icustomer' and (b.n_quantity-coalesce(c.n_quantity_op,0))>0
                      ",false);

      return $this->db->get();
    }

    function get_outstanding_detail($icustomer, $dspb, $ipromo){
      $this->db->select(" b.i_product, d.e_product_basename, sum(b.n_quantity) as n_quantity_fc, coalesce(c.n_quantity_op,0) as n_quantity_op 
                      ,(b.n_quantity-coalesce(c.n_quantity_op,0)) as n_outstanding
                      , d.v_unitprice , coalesce(e.n_disc,0) as n_disc
                      ,((b.n_quantity-coalesce(c.n_quantity_op,0))*d.v_unitprice) as v_subtotal 
                      ,coalesce((((b.n_quantity-coalesce(c.n_quantity_op,0))*d.v_unitprice)*e.n_disc/100),0) as v_disc
                      ,(((b.n_quantity-coalesce(c.n_quantity_op,0))*d.v_unitprice)-coalesce((((b.n_quantity-coalesce(c.n_quantity_op,0))*d.v_unitprice)*e.n_disc/100),0)) as v_subtotaldisc
                      from tm_fc_distributor a
                      inner join tm_fc_distributor_detail b on b.i_fc = a.i_fc
                      left join (select b.i_product, sum(b.n_count) as n_quantity_op from tm_op a
                        inner join tm_op_item b on b.i_op_code = a.i_op_code 
                        where a.f_op_cancel = 'f' AND (a.i_status <> '7'  or a.i_status <> '9' or a.i_status <> '5')
                        and a.i_customer = '$icustomer'
                        group by b.i_product , b.e_product_name
                        order by b.i_product ) c on c.i_product = b.i_product
                      inner join tr_product_base d on d.i_product_motif = b.i_product
                      left join (select a.i_promo, b.i_product_base, b.n_disc from tm_promo a
                        inner join tm_promo_item b on b.i_promo = a.i_promo
                        where '$dspb' >= a.d_from and '$dspb' <= a.d_to and a.i_promo='$ipromo') e on e.i_product_base = b.i_product
                      where date_part('month', d_fc) = EXTRACT(month FROM now())and date_part('year', d_fc)= EXTRACT(year FROM now())
                      and a.i_customer = '$icustomer' and (b.n_quantity-coalesce(c.n_quantity_op,0))>0
                      group by b.i_product, d.e_product_basename, coalesce(c.n_quantity_op,0),(b.n_quantity-coalesce(c.n_quantity_op,0)), d.v_unitprice
                          , coalesce(e.n_disc,0)
                          ,coalesce((((b.n_quantity-coalesce(c.n_quantity_op,0))*d.v_unitprice)*e.n_disc/100),0)
                      order by b.i_product ",false);

      return $this->db->get();
    }
    
    public function updateheader($ispb, $datespb, $datebatas, $icustomer, $iporeff, $ipromo, $vgross, $vdiskon, $vnetto, $eremark){
        $dupdate = date("d F Y H:i:s");
        $data = array(
                      'd_op'              => $datespb,
                      'd_delivery_limit'  => $datebatas,
                      'i_customer'        => $icustomer,
                      'i_op_reff'         => $iporeff,
                      // 'i_promo'           => $ipromo,
                      'v_total_gross'     => $vgross,
                      'v_total_discount'  => $vdiskon,
                      'v_total_netto'     => $vnetto,
                      'e_op_remark'       => $eremark,
                      'd_update'          => $dupdate,
    );

    $this->db->where('i_op_code', $ispb);
    $this->db->update('tm_op', $data);
  }

  function deletedetail($ispb) {
        $this->db->query("DELETE from tm_op_item where i_op_code='$ispb' ");
  }

  public function cancel($ispb){
        $data = array(
          'f_op_cancel'=>'t',
          'i_status'=>'7',
      );
        $this->db->where('i_op_code', $ispb);
        $this->db->update('tm_op', $data);
  }

  public function approve($ispb){
    $data = array(
            'i_status'     =>'6',
    );
    $this->db->where('i_op_code', $ispb);
    $this->db->update('tm_op', $data);
  }

  public function change_approve($ispb){
    $data = array(
            'i_status'     =>'3',
    );
    $this->db->where('i_op_code', $ispb);
    $this->db->update('tm_op', $data);
  }

  public function reject_approve($ispb){
    $data = array(
            'i_status'      =>'4',
    );
    $this->db->where('i_op_code', $ispb);
    $this->db->update('tm_op', $data);
  }

}
/* End of file Mmaster.php */