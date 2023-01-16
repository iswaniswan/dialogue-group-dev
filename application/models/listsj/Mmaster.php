<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function delete($isj,$iarea){
			$this->db->query(" update tm_nota set f_nota_cancel='t' WHERE i_sj='$isj' and i_area='$iarea'");
			$this->db->query(" update tm_spb set f_spb_cancel='t' WHERE i_sj='$isj' and i_area='$iarea'");
#####
      $this->db->select(" * from tm_nota where i_sj='$isj' and i_area='$iarea'");
			$qry = $this->db->get();
			if ($qry->num_rows() > 0){
        foreach($qry->result() as $qyr){  
          $dsj=$qyr->d_sj;
        }
      }
      $th=substr($dsj,0,4);
		  $bl=substr($dsj,5,2);
		  $emutasiperiode=$th.$bl;
      $query=$this->db->query(" select f_spb_consigment from tm_spb where i_sj='$isj' and i_area='$iarea'",false);
      $consigment='f';
			if ($query->num_rows() > 0){
				foreach($query->result() as $qq){
					$consigment=$qq->f_spb_consigment;
				}
			}
      $iareasj= substr($isj,8,2);
      if($iareasj=='BK')$iareasj=$iarea;
      $que = $this->db->query("select i_store from tr_area where i_area='$iareasj'");
      $st=$que->row();
      $istore=$st->i_store;
      if($istore=='AA'){
				$istorelocation		= '01';
			}else{
        if($consigment=='t')
          $istorelocation		= 'PB';
        else
  				$istorelocation		= '00';
			}
			$istorelocationbin	= '00';
      $this->db->select(" * from tm_nota_item where i_sj='$isj' and i_area='$iarea' order by n_item_no");
			$qery = $this->db->get();
			if ($qery->num_rows() > 0){
        foreach($qery->result() as $qyre){  
          $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                        and i_product_motif='$qyre->i_product_motif'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin'
                                        order by i_trans desc",false);
#and i_refference_document='$isj'
          if ($queri->num_rows() > 0){
        	  $row   		= $queri->row();
            $que 	= $this->db->query("SELECT current_timestamp as c");
	          $ro 	= $que->row();
            $now	 = $ro->c;
            $qtyakhir = $row->n_quantity_akhir+$qyre->n_deliver;
            $this->db->query(" 
                              INSERT INTO tm_ic_trans
                              (
                                i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                n_quantity_in, n_quantity_out,
                                n_quantity_akhir, n_quantity_awal)
                              VALUES 
                              (
                                '$qyre->i_product','$qyre->i_product_grade','$qyre->i_product_motif',
                                '$istore','$istorelocation','$istorelocationbin', 
                                '$qyre->e_product_name', '$isj', '$now', $qyre->n_deliver, 0, $qtyakhir, 
                                $row->n_quantity_akhir
                              )
                             ",false);

            $this->db->query(" 
                              UPDATE tm_mutasi set n_git_penjualan=n_git_penjualan-$qyre->n_deliver
                              where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                              and i_product_motif='$qyre->i_product_motif'
                              and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                             ",false);
            $this->db->query(" 
                              UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qyre->n_deliver
                              where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                              and i_product_motif='$qyre->i_product_motif' and i_store='$istore' and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                             ",false);

          }
        }
      }
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
    ", FALSE)->result();
}

    public function bacaareauser($username,$idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iarea = $kuy->i_area; 
        }else{
            $iarea = '';
        }
        return $iarea;
    }

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

    public function cekperiode(){
      $query = $this->db->query("select i_periode from tm_periode");
      if($query->num_rows() > 0){
        $periode = $query->row();
        $iperiode = $periode->i_periode;
      }else{
        $iperiode='';
      }
      return $iperiode;
    }

    public function data($dfrom,$dto,$iarea,$cekdepartemen,$area,$iperiode,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        if($iarea == '00'){
            $datatables->query("
                            select
                              a.i_nota,
                              a.i_area,
                              b.e_area_name,
                              a.i_sj,
                              a.d_sj,
                              a.i_spb,
                              a.d_spb,
                              a.i_dkb,
                              a.d_dkb,
                              a.i_customer,
                              c.e_customer_name,
                              a.v_nota_netto,
                              a.d_sj_receive,
                              a.f_nota_cancel,
                              '$dfrom' as dfrom,
                              '$dto' as dto,
                              '$cekdepartemen' as departemen,
                              '$area' as area,
                              '$folder' as folder,
                              '$iperiode' as iperiode
                            from
                              tm_nota a,
                              tr_area b,
                              tr_customer c 
                            where
                              a.i_area = b.i_area 
                              and a.i_customer = c.i_customer 
                              and 
                              (
                                 substring(a.i_sj, 9, 2) = '$iarea' 
                                 or 
                                 (
                                    substring(a.i_sj, 9, 2) = 'BK' 
                                    and a.i_area = '$iarea'
                                 )
                              )
                              and a.d_sj >= to_date('$dfrom', 'dd-mm-yyyy') 
                              and a.d_sj <= to_date('$dto', 'dd-mm-yyyy') 
                            order by
                              a.i_sj asc,
                              a.d_sj desc"
                            );
        }else{
            $datatables->query("
                            select
                              a.i_nota,
                              a.i_area,
                              b.e_area_name,
                              a.i_sj,
                              a.d_sj,
                              a.i_spb,
                              a.d_spb,
                              a.i_dkb,
                              a.d_dkb,
                              a.i_customer,
                              c.e_customer_name,
                              a.v_nota_netto,
                              a.d_sj_receive,
                              a.f_nota_cancel,
                              '$dfrom' as dfrom,
                              '$dto' as dto,
                              '$cekdepartemen' as departemen,
                              '$area' as area,
                              '$folder' as folder,
                              '$iperiode' as iperiode
                            from
                              tm_nota a,
                              tr_area b,
                              tr_customer c 
                            where
                              a.i_area = b.i_area 
                              and a.i_customer = c.i_customer 
                              and 
                              (
                                 substring(a.i_sj, 9, 2) = '$iarea' 
                                 or 
                                 (
                                    substring(a.i_sj, 9, 2) = 'BK' 
                                    and a.i_area = '$iarea'
                                 )
                              )
                              and a.d_sj >= to_date('$dfrom', 'dd-mm-yyyy') 
                              and a.d_sj <= to_date('$dto', 'dd-mm-yyyy') 
                            order by
                              a.i_sj asc,
                              a.d_sj desc"
                            );
        }
        
        $datatables->add('action', function ($data) {
            $isj            = trim($data['i_sj']);
            $dsj            = trim($data['d_sj']);
            $ispb           = trim($data['i_spb']);
            $dspb           = trim($data['d_spb']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $idkb           = trim($data['i_dkb']);
            $dkb            = trim($data['d_dkb']);
            $iarea          = trim($data['i_area']);
            $icustomer      = trim($data['i_customer']);
            $folder         = trim($data['folder']);
            $fnotacancel    = $data['f_nota_cancel'];
            $departemen     = trim($data['departemen']);  
            $area           = trim($data['area']); 
            $inota          = trim($data['i_nota']); 
            $iperiode       = $data['iperiode'];
            $data           = '';

            $dsj = substr($dsj,0,4).substr($dsj,5,2);
            $bisaedit = false;
            if($iperiode <= $dsj){
                $bisaedit = true;
            }
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isj/$iarea/$dfrom/$dto/$ispb/$bisaedit\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(($departemen == '7' && $area=='00') || ($departemen=='3' && $area=='00') || ($departemen=='1' && $area='00')
                && $fnotacancel == 'f' && trim($inota)=='' && trim($idkb)==''){
                    $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isj\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
            }

			return $data;
        });

        $datatables->edit('d_sj', function ($data) {
            $d_sj = $data['d_sj'];
            if($d_sj == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sj) );
            }
        });

        $datatables->edit('d_spb', function ($data) {
            $d_spb = $data['d_spb'];
            if($d_spb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_spb) );
            }
        });

        $datatables->edit('d_sj_receive', function ($data) {
          $d_sj_receive = $data['d_sj_receive'];
          if($d_sj_receive == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_sj_receive) );
          }
      });

        $datatables->edit('d_dkb', function ($data) {
          $d_dkb = $data['d_dkb'];
          if($d_dkb == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_dkb) );
          }
        });

      $datatables->edit('i_sj', function ($data) {
        $i_sj = "<h2><b>".$data['i_sj']."</b></h2>";
        $fnotacancel = $data['f_nota_cancel'];
        if($fnotacancel == 't'){
            return $i_sj;
        }else{
            return $data['i_sj'];
        }
      });

      $datatables->edit('i_customer', function ($data) {
          $i_customer      = $data['i_customer'];
          $e_customer_name = $data['e_customer_name'];
          if(substr($i_customer,2,3) != '000'){
              return "(".$i_customer.")"." - ".$e_customer_name;
          }else{
              return '';
          }
      });

      $datatables->edit('i_area', function ($data) {
        $i_area      = $data['i_area'];
        $e_area_name = $data['e_area_name'];
        return "(".$i_area.")"." - ".$e_area_name;
    });

      $datatables->edit('v_nota_netto', function ($data) {
        $v_nota_netto = $data['v_nota_netto'];
        return number_format($v_nota_netto);
      });

        $datatables->hide('folder');
        $datatables->hide('f_nota_cancel');
        $datatables->hide('e_customer_name');
        $datatables->hide('e_area_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('departemen');
        $datatables->hide('area');
        $datatables->hide('i_nota');
        $datatables->hide('iperiode');

        return $datatables->generate();  
    }

    function baca($isj,$iarea){
        $query = $this->db->query(" 
                              select
                                a.*,
                                c.e_customer_name,
                                b.e_area_name 
                              from
                                tm_nota a,
                                tr_area b,
                                tr_customer c 
                              where
                                a.i_area = b.i_area 
                                and a.i_customer = c.i_customer 
                                and a.i_area='$iarea'
                                and a.i_sj = '$isj'"
                                , false);
		  if ($query->num_rows() > 0){
		  	return $query->row();
		  }
    }

    function bacadetail($isj,$iarea){
        $query = $this->db->query(" 
                                select
                                  a.i_product_motif,
                                  a.i_product,
                                  a.e_product_name,
                                  b.e_product_motifname,
                                  d.v_unit_price as harga,
                                  a.v_unit_price,
                                  a.n_deliver,
                                  d.n_order,
                                  a.i_product_grade,
                                  d.n_order as n_qty,
                                  c.d_sj_print 
                                from
                                  tm_nota_item a,
                                  tr_product_motif b,
                                  tm_nota c,
                                  tm_spb_item d 
                                where
                                  a.i_sj = '$isj' 
                                  and a.i_area = '$iarea'
                                  and a.i_product = b.i_product 
                                  and a.i_sj = c.i_sj 
                                  and a.i_area = c.i_area 
                                  and c.i_spb = d.i_spb 
                                  and c.i_area = d.i_area 
                                  and a.i_product = d.i_product 
                                  and a.i_product_grade = d.i_product_grade 
                                  and a.i_product_motif = d.i_product_motif 
                                  and a.i_product_motif = b.i_product_motif 
                                order by
                                  a.n_item_no"
                                , false);
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    public function stockdaerah($ispb,$iarea){
      $query = $this->db->query(" 
                            SELECT 
                              i_store,
                              f_spb_stockdaerah,
                              f_spb_consigment 
                            FROM 
                              tm_spb
                            WHERE 
                              i_spb='$ispb' 
                              and i_area='$iarea'"
                            ,false);
      if ($query->num_rows() > 0){
       return $query->row();
      }
    }

    public function bacadetailspb($ispb,$isj,$iarea){
      return $this->db->query("
                            select
                              a.*,
                              b.e_product_motifname 
                            from
                              tm_spb_item a,
                              tr_product_motif b,
                              tm_spb c 
                            where
                              a.i_spb = '$ispb' 
                              and a.i_area = '$iarea' 
                              and a.i_product not in 
                              (
                                 select
                                    i_product 
                                 from
                                    tm_nota_item 
                                 where
                                    i_sj = '$isj' 
                                    and i_area = '$iarea'
                              )
                              and a.i_spb = c.i_spb 
                              and a.i_area = c.i_area 
                              and a.i_product = b.i_product 
                              and a.i_product_motif = b.i_product_motif 
                            order by
                              a.i_product"
                            ,false);
    }

    public function insertsjheader($ispb,$dspb,$isj,$dsj,$iarea,$isalesman,$icustomer,$nspbdiscount1,$nspbdiscount2,$nspbdiscount3,$vspbdiscount1, 
                                    $vspbdiscount2,$vspbdiscount3,$vspbdiscounttotal,$vspbgross,$vspbnetto,$isjold,$fentpusat,$iareareff){
      $query 		= $this->db->query("
                                  select 
                                    f_customer_plusppn, 
                                    f_customer_plusdiscount 
                                  from 
                                    tr_customer 
                                  where 
                                    i_customer='$icustomer'
                                  ");
      $row   		= $query->row();
      // $plusppn	= $row->f_customer_plusppn;
      $plusppn	= 'f';
      $plusdiscount	= $row->f_customer_plusdiscount;
      $que      = $this->db->query("
                                    select 
                                      f_spb_consigment 
                                    from 
                                      tm_spb 
                                    where 
                                      i_spb='$ispb' 
                                      and i_area='$iarea'
                                    ");
      $row      = $que->row();
      $kons     = $row->f_spb_consigment;
      if ($kons=='t'){
        $dkb    ='SYSTEM';
        $ddkb   =$dsj;
      } else {
        $dkb    =NULL;
        $ddkb   =NULL;
      }
      $query 		= $this->db->query("SELECT current_timestamp as c");
      $row   		= $query->row();
      $dsjentry	= $row->c;
      if($iarea=='PB'){
        $this->db->set(
                      array(	  
                            'i_sj'            		=> $isj,
                            'i_sj_old'		        => $isjold,
                            'i_spb'           		=> $ispb,
                            'd_spb'           		=> $dspb,
                            'd_sj'            		=> $dsj,
                            'd_sj_receive'        => $dsj,
                            'i_area'		          => $iarea,
                            'i_salesman'      		=> $isalesman,
                            'i_customer'      		=> $icustomer,
                            'f_plus_ppn'          => $plusppn,
                            'f_plus_discount'     => $plusdiscount,
                            'n_nota_discount1'  	=> $nspbdiscount1,
                            'n_nota_discount2'  	=> $nspbdiscount2,
                            'n_nota_discount3'   	=> $nspbdiscount3,
                            'v_nota_discount1'  	=> $vspbdiscount1,
                            'v_nota_discount2'  	=> $vspbdiscount2,
                            'v_nota_discount3'  	=> $vspbdiscount3,
                            'v_nota_discounttotal'=> $vspbdiscounttotal,
                            'v_nota_discount'     => $vspbdiscounttotal,
                            'v_nota_gross'		    => $vspbgross,
                            'v_nota_netto'    		=> $vspbnetto,
                            'd_sj_entry'      		=> $dsjentry,
                            'i_dkb'               => $dkb,
                            'd_dkb'               => $ddkb,
                            'f_nota_cancel'		    => 'f'
                      ));
    }else{
      $this->db->set(
                    array(	  
                            'i_sj'            		=> $isj,
                            'i_sj_old'		        => $isjold,
                            'i_spb'           		=> $ispb,
                            'd_spb'           		=> $dspb,
                            'd_sj'            		=> $dsj,
                            'i_area'		          => $iarea,
                            'i_salesman'      		=> $isalesman,
                            'i_customer'      		=> $icustomer,
                            'f_plus_ppn'          => $plusppn,
                            'f_plus_discount'     => $plusdiscount,
                            'n_nota_discount1'  	=> $nspbdiscount1,
                            'n_nota_discount2'  	=> $nspbdiscount2,
                            'n_nota_discount3'   	=> $nspbdiscount3,
                            'v_nota_discount1'  	=> $vspbdiscount1,
                            'v_nota_discount2'  	=> $vspbdiscount2,
                            'v_nota_discount3'  	=> $vspbdiscount3,
                            'v_nota_discounttotal'=> $vspbdiscounttotal,
                            'v_nota_discount'     => $vspbdiscounttotal,
                            'v_nota_gross'		    => $vspbgross,
                            'v_nota_netto'    		=> $vspbnetto,
                            'd_sj_entry'      		=> $dsjentry,
                            'i_dkb'               => $dkb,
                            'd_dkb'               => $ddkb,
                            'f_nota_cancel'		    => 'f'
                        ));
    }
    $this->db->insert('tm_nota');
  }

  function insertsjheader2($ispb,$dspb,$isj,$dsj,$isjtype,$iareato,$iareafrom,$isalesman,$icustomer,
                            $nspbdiscount1,$nspbdiscount2,$nspbdiscount3,$vspbdiscount1, 
                            $vspbdiscount2,$vspbdiscount3,$vspbdiscounttotal,$vspbgross,$vspbnetto,$isjold,$daerah,$fentpusat,$iareareff,$ntop){
    $query 		    = $this->db->query("SELECT f_customer_plusppn, f_customer_plusdiscount from tr_customer where i_customer='$icustomer'");
    $row   		    = $query->row();
    $plusppn	    = $row->f_customer_plusppn;
    $plusdiscount	= $row->f_customer_plusdiscount;
    $query 		    = $this->db->query("SELECT current_timestamp as c");
    $row   		    = $query->row();
    $dsjentry	    = $row->c;
    if($iarea=='PB'){
      $this->db->set(
                    array('i_sj'		              => $isj,
                          'i_sj_old'		          => $isjold,
                          'i_sj_type'		          => $isjtype,
                          'i_spb'		              => $ispb,
                          'd_spb'		              => $dspb,
                          'd_sj'		              => $dsj,
                          'd_sj_receive'          => $dsj,
                          'i_area_from'		        => $iareafrom,
                          'i_area_to'		          => $iareato,
                          'i_salesman'		        => $isalesman,
                          'i_refference_document'	=> $ispb,
                          'i_customer'		        => $icustomer,
                          'f_plus_ppn'            => $plusppn,
                          'f_plus_discount'       => $plusdiscount,
                          'n_nota_toplength'      => $ntop,
                          'n_sj_discount1'	      => $nspbdiscount1,
                          'n_sj_discount2'	      => $nspbdiscount2,
                           'n_sj_discount3' 	    => $nspbdiscount3,
                          'v_sj_discount1'	      => $vspbdiscount1,
                          'v_sj_discount2'	      => $vspbdiscount2,
                          'v_sj_discount3'	      => $vspbdiscount3,
                          'v_sj_discounttotal'	  => $vspbdiscounttotal,
                          'v_sj_gross'		        => $vspbgross,
                          'v_sj_netto'		        => $vspbnetto,
                          'd_sj_entry'		        => $dsjentry,
                          'f_sj_cancel'		        => 'f',
                          'f_sj_daerah'       	  => $daerah,
                          'f_entry_pusat'	        => $fentpusat,
                          'i_area_referensi'	    => $iareareff
                        ));
    }else{
      $this->db->set(
                    array('i_sj'		              => $isj,
                          'i_sj_old'		          => $isjold,
                          'i_sj_type'		          => $isjtype,
                          'i_spb'		              => $ispb,
                          'd_spb'		              => $dspb,
                          'd_sj'		              => $dsj,
                          'i_area_from'		        => $iareafrom,
                          'i_area_to'		          => $iareato,
                          'i_salesman'		        => $isalesman,
                          'i_refference_document'	=> $ispb,
                          'i_customer'		        => $icustomer,
                          'f_plus_ppn'            => $plusppn,
                          'f_plus_discount'       => $plusdiscount,
                          'n_nota_toplength'      => $ntop,
                          'n_sj_discount1'	      => $nspbdiscount1,
                          'n_sj_discount2'	      => $nspbdiscount2,
                           'n_sj_discount3' 	    => $nspbdiscount3,
                          'v_sj_discount1'	      => $vspbdiscount1,
                          'v_sj_discount2'	      => $vspbdiscount2,
                          'v_sj_discount3'	      => $vspbdiscount3,
                          'v_sj_discounttotal'	  => $vspbdiscounttotal,
                          'v_sj_gross'		        => $vspbgross,
                          'v_sj_netto'		        => $vspbnetto,
                          'd_sj_entry'		        => $dsjentry,
                          'f_sj_cancel'		        => 'f',
                          'f_sj_daerah'       	  => $daerah,
                          'f_entry_pusat'	        => $fentpusat,
                          'i_area_referensi'	    => $iareareff
                          ));
    }
    $this->db->insert('tm_nota');
  }

  function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isj,$iarea,$i){
    $query=$this->db->query(" 
                            select 
                              i_product_category, 
                              i_product_class 
                            from 
                              tr_product 
                            where 
                              i_product='$iproduct'"
                            ,false);
    if ($query->num_rows() > 0){
      foreach($query->result() as $qq){
        $i_productcategory	=$qq->i_product_category;
        $i_productclass	=$qq->i_product_class;
      }
      $query2=$this->db->query(" 
                              select 
                                a.i_product_category, 
                                a.e_product_categoryname, 
                                b.i_product_class, 
                                b.e_product_classname 
                              from 
                                tr_product_category a, 
                                tr_product_class b 
                              where 
                                a.i_product_category='$i_productcategory' 
                                and b.i_product_class='$i_productclass'"
                              ,false);
      if ($query2->num_rows() > 0){
        foreach($query2->result() as $oo){
          $i_product_category		=$oo->i_product_category;
          $e_product_categoryname	=$oo->e_product_categoryname;
          $i_product_class		=$oo->i_product_class;
          $e_product_classname	=$oo->e_product_classname;
        }
      }

      $this->db->set(
                    array(
                          'i_sj'			     	      => $isj,
                          'i_area'	              => $iarea,
                          'i_product'			 	      => $iproduct,
                          'i_product_motif'	 	    => $iproductmotif,
                          'i_product_grade'	 	    => $iproductgrade,
                          'e_product_name'	 	    => $eproductname,
                          'n_deliver'       	 	  => $ndeliver,
                          'v_unit_price'		 	    => $vunitprice,
                          'i_product_category' 	  => $i_product_category,
                          'e_product_categoryname'=> $e_product_categoryname,
                          'i_product_class' 		  => $i_product_class,
                          'e_product_classname' 	=> $e_product_classname,
                          'n_item_no'       	 	  => $i
                    ));
      $this->db->insert('tm_nota_item');
    }
  }    
  function updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea,$vunitprice){
    $this->db->query(" 
                      update 
                        tm_spb_item 
                      set 
                        n_deliver = $ndeliver
                      where 
                        i_spb='$ispb' 
                        and i_product='$iproduct' 
                        and i_product_grade='$iproductgrade'
                        and i_product_motif='$iproductmotif' 
                        and i_area='$iarea' 
                        and v_unit_price=$vunitprice "
                    ,false);
  }

  function cekdaerah($ispb,$iarea){
    $query=$this->db->query(" 
                            select 
                              f_spb_stockdaerah 
                            from 
                              tm_spb 
                            where 
                              i_spb='$ispb' 
                              and i_area='$iarea'"
                            ,false);
    if ($query->num_rows() > 0){
      foreach($query->result() as $qq){
        $stockdaerah=$qq->f_spb_stockdaerah;
      }
      return $stockdaerah;
    }
  }

  function cekkons($ispb,$iarea){
    $consigment='f';
    $query=$this->db->query(" 
                            select 
                              f_spb_consigment 
                            from 
                              tm_spb 
                            where 
                              i_spb='$ispb' 
                              and i_area='$iarea'",false);
    if ($query->num_rows() > 0){
      foreach($query->result() as $qq){
        $consigment=$qq->f_spb_consigment;
      }
    }
    return $consigment;
  }

  function updatespb($ispb,$iarea,$isj,$dsj){
    $this->db->query(" 
                    update 
                      tm_spb 
                    set 
                      i_sj = '$isj', 
                      d_sj='$dsj' 
                    where 
                      i_spb='$ispb' 
                      and i_area='$iarea'"
                    ,false);
  }
  
  function updatedkb($vsjnetto,$isj,$iarea){
    $query=$this->db->query(" 
                          select 
                            a.v_jumlah, 
                            b.i_dkb 
                          from 
                            tm_dkb_item a, 
                            tm_dkb b 
                          where 
                            a.i_dkb=b.i_dkb 
                            and a.i_area=b.i_area 
                            and b.f_dkb_batal='f' 
                            and a.i_sj='$isj' 
                            and a.i_area='$iarea'"
                          ,false);
    if ($query->num_rows() > 0){
      foreach($query->result() as $qq){
        $jml=$qq->v_jumlah;
        $dkb=$qq->i_dkb;
      }
      $this->db->query(" 
                      update 
                        tm_dkb 
                      set 
                        v_dkb = (v_dkb-$jml)+$vsjnetto 
                      where 
                        i_dkb='$dkb' 
                        and i_area='$iarea'"
                      ,false);
      $this->db->query(" 
                      update 
                        tm_dkb_item 
                      set 
                        v_jumlah = '$vsjnetto' 
                      where 
                        i_dkb='$dkb' 
                        and i_sj='$isj' 
                        and i_area='$iarea'"
                      ,false);
    }
  }

  function deletesjdetail($iproduct,$iproductgrade,$iproductmotif,$isj,$iarea){
    $this->db->query("
                    delete 
                    from 
                      tm_nota_item 
                    where 
                      i_sj='$isj' 
                      and i_area='$iarea' 
                      and i_product='$iproduct' 
                      and i_product_grade='$iproductgrade' 
                      and i_product_motif='$iproductmotif'"
                    ,false);
  }	

  function updatesjheader($ispb,$dspb,$isj,$dsj,$iarea,$isalesman,$icustomer,$nspbdiscount1,$nspbdiscount2,$nspbdiscount3,$vspbdiscount1, 
                        $vspbdiscount2,$vspbdiscount3,$vspbdiscounttotal,$vspbgross,$vspbnetto,$isjold){
    $query 		      = $this->db->query("SELECT f_customer_plusppn, f_customer_plusdiscount from tr_customer where i_customer='$icustomer'");
    $row   		      = $query->row();
    $plusppn	      = $row->f_customer_plusppn;
    $plusdiscount	  = $row->f_customer_plusdiscount;
    $query 		      = $this->db->query("SELECT current_timestamp as c");
    $row   		      = $query->row();
    $dsjupdate	    = $row->c;
    $this->db->set(
                  array(
    #			'i_sj_old'      	=> $isjold,
                      'i_spb'     			      => $ispb,
                      'd_spb'			            => $dspb,
                      'd_sj'	      		      => $dsj,
                      'i_area'      		      => $iarea,
                      'i_salesman'	  	      => $isalesman,
                      'i_customer'	  	      => $icustomer,
                      'f_plus_ppn'            => $plusppn,
                      'f_plus_discount'       => $plusdiscount,
                      'n_nota_discount1'      => $nspbdiscount1,
                      'n_nota_discount2'      => $nspbdiscount2,
                      'n_nota_discount3'      => $nspbdiscount3,
                      'v_nota_discount1'      => $vspbdiscount1,
                      'v_nota_discount2'      => $vspbdiscount2,
                      'v_nota_discount3'      => $vspbdiscount3,
                      'v_nota_discounttotal'	=> $vspbdiscounttotal,
                      'v_nota_discount'	      => $vspbdiscounttotal,
                      'v_nota_gross'		      => $vspbgross,
                      'v_nota_netto'		      => $vspbnetto,
                      'd_sj_update' 		      => $dsjupdate,
                      'f_nota_cancel'		      => 'f'
                      ));
    $this->db->where('i_sj',$isj);
    $this->db->where('i_area',$iarea);
    $this->db->update('tm_nota');
  }

  function searchsjheader($isj,$iarea,$isjtype,$daerah){
    return $this->db->query(" 
                            SELECT 
                              * 
                            FROM 
                              tm_nota 
                            WHERE 
                              i_sj='$isj' 
                              AND i_area_to='$iarea' 
                              AND i_sj_type='$isjtype' 
                              AND f_sj_daerah='$daerah' 
                            ");
  }

  function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
    $query=$this->db->query(" 
                            select 
                              n_quantity_awal, 
                              n_quantity_akhir, 
                              n_quantity_in, 
                              n_quantity_out 
                            from 
                              tm_ic_trans
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='00'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                            order by 
                              i_trans desc"
                            ,false);
    if ($query->num_rows() > 0){
      return $query->result();
    }
  }

  function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
    $query=$this->db->query(" 
                            select 
                              n_quantity_stock
                            from 
                              tm_ic
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='00'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                            ",false);
    if ($query->num_rows() > 0){
      return $query->result();
    }
  }

  function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak,$tra){
    $query 	= $this->db->query("SELECT current_timestamp as c");
    $row   	= $query->row();
    $now	  = $row->c;
    $query  = $this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    /*
    $query=$this->db->query(" 
                       INSERT INTO tm_ic_trans
                       (
                         i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                         i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                         n_quantity_in, n_quantity_out,
                         n_quantity_akhir, n_quantity_awal,i_trans)
                       VALUES 
                       (
                         '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                         '$eproductname', '$isj', '$now', $q_in, $q_out+$qsj, $q_ak-$qsj, $q_aw, $tra
                       )
                     ",false);
    */
  }

  function inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak){
    $query 	= $this->db->query("SELECT current_timestamp as c");
    $row   	= $query->row();
    $now	  = $row->c;
    $query  = $this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    /*
    $query=$this->db->query(" 
                       INSERT INTO tm_ic_trans
                       (
                         i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                         i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                         n_quantity_in, n_quantity_out,
                         n_quantity_akhir, n_quantity_awal)
                       VALUES 
                       (
                         '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                         '$eproductname', '$isj', '$now', $q_in, $q_out+$qsj, $q_ak-$qsj, $q_aw
                       )
                     ",false);
    */
  }

  function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
    $ada=false;
    $query=$this->db->query(" 
                            select 
                              i_product
                            from 
                              tm_mutasi
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='$iproductmotif'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                     ",false);
    if ($query->num_rows() > 0){
    $ada=true;
    }
    return $ada;
  }

  function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
    $query=$this->db->query(" 
                            UPDATE 
                              tm_mutasi 
                            set 
                              n_git_penjualan=n_git_penjualan+$qsj
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='$iproductmotif'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                            ",false);
    /*
    $query=$this->db->query(" 
                       UPDATE tm_mutasi 
                       set n_saldo_akhir=n_saldo_akhir-$qsj, n_git_penjualan=n_git_penjualan+$qsj
                       where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                       and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                       and e_mutasi_periode='$emutasiperiode'
                     ",false);
    */
    /*
    $query=$this->db->query(" 
                       UPDATE tm_mutasi 
                       set n_mutasi_penjualan=n_mutasi_penjualan+$qsj, n_saldo_akhir=n_saldo_akhir-$qsj,
                       n_git_penjualan=n_git_penjualan+$qsj
                       where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                       and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                       and e_mutasi_periode='$emutasiperiode'
                     ",false);
    */
  }

  function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$qaw){
    $query=$this->db->query(" 
                            insert into tm_mutasi 
                            (
                              i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                              e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                              n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close,n_git_penjualan)
                            values
                            (
                            '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',$qaw,0,0,0,0,0,0,0,0,'f',$qsj) "
                          ,false);
    /*
    $query=$this->db->query(" 
                       insert into tm_mutasi 
                       (
                         i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                         e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                         n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close,n_git_penjualan)
                       values
                       (
                         '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',$qaw,0,0,0,$qsj,0,0,$qaw-$qsj,0,'f',$qsj)
                     ",false);
    */
  }

  function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
    $ada=false;
    $query=$this->db->query(" 
                            select
                              i_product
                            from 
                              tm_ic
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='00'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                            ",false);
    if ($query->num_rows() > 0){
      $ada=true;
    }
    return $ada;
  }

  function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
  $query=$this->db->query(" 
                          UPDATE 
                            tm_ic 
                          set 
                            n_quantity_stock=n_quantity_stock-$qsj
                          where 
                            i_product='$iproduct' 
                            and i_product_grade='$iproductgrade' 
                            and i_product_motif='00'
                            and i_store='$istore' 
                            and i_store_location='$istorelocation' 
                            and i_store_locationbin='$istorelocationbin'
                        ",false);
  /*
  $query=$this->db->query(" 
                     UPDATE tm_ic set n_quantity_stock=$q_ak-$qsj
                     where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                     and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                   ",false);
  */
  }

  function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj,$q_aw){
    $query=$this->db->query(" 
                            insert into tm_ic 
                            values
                            (
                              '$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $q_aw-$qsj, 't'
                            )
                            ",false);
  }
  /*
  function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
  {
  $query 	= $this->db->query("SELECT current_timestamp as c");
  $row   	= $query->row();
  $now	= $row->c;
  $query=$this->db->query(" 
                     INSERT INTO tm_ic_trans
                     (
                       i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                       i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                       n_quantity_in, n_quantity_out,
                       n_quantity_akhir, n_quantity_awal)
                     VALUES 
                     (
                       '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                       '$eproductname', '$isj', $now, $q_in+$qsj, $q_out, $q_ak+$qsj, $q_aw
                     )
                   ",false);
  }
  */
  function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
    $query=$this->db->query(" 
                          UPDATE 
                            tm_mutasi 
                          set 
                            n_mutasi_bbm=n_mutasi_bbm+$qsj, 
                            n_saldo_akhir=n_saldo_akhir+$qsj
                          where 
                            i_product='$iproduct' 
                            and i_product_grade='$iproductgrade' 
                            and i_product_motif='$iproductmotif'
                            and i_store='$istore' 
                            and i_store_location='$istorelocation' 
                            and i_store_locationbin='$istorelocationbin'
                            and e_mutasi_periode='$emutasiperiode'
                          ",false);
  }

  function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
    $query=$this->db->query(" 
                            insert into tm_mutasi 
                            (
                              i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                              e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                              n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                            values
                            (
                              '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,$qsj,0,0,0,$qsj,0,'f')
                          ",false);
  }

  function updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
    $query=$this->db->query(" 
                            UPDATE 
                              tm_ic 
                            set 
                              n_quantity_stock=n_quantity_stock+$qsj
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='00'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                            ",false);
  /*
  $query=$this->db->query(" 
                     UPDATE tm_ic set n_quantity_stock=$q_ak+$qsj
                     where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                     and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                   ",false);
  */
  }

  function insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj){
    $query=$this->db->query(" 
                            insert into tm_ic 
                            values
                            (
                              '$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $qsj, 't'
                            )
                          ",false);
  }

  function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname){
    $queri 		= $this->db->query("
                                SELECT 
                                  n_quantity_akhir, 
                                  i_trans 
                                FROM 
                                  tm_ic_trans 
                                WHERE 
                                  i_product='$iproduct' 
                                  and i_product_grade='$iproductgrade' 
                                  and i_product_motif='00'
                                  and i_store='$istore' 
                                  and i_store_location='$istorelocation'
                                  and i_store_locationbin='$istorelocationbin'
                                ORDER BY 
                                 i_trans desc"
                          ,false);
    #and i_refference_document='$isj'
    if ($queri->num_rows() > 0){
    #       return $query->result();
      $row   		= $queri->row();
      $que 	= $this->db->query("SELECT current_timestamp as c");
      $ro 	= $que->row();
      $now	 = $ro->c;
      if($ntmp!=0 && $ntmp!=''){
        $query=$this->db->query(" 
                             INSERT INTO tm_ic_trans
                             (
                               i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                               i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                               n_quantity_in, n_quantity_out,
                               n_quantity_akhir, n_quantity_awal)
                             VALUES 
                             (
                               '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                               '$eproductname', '$isj', '$now', $ntmp, 0, $row->n_quantity_akhir+$ntmp, $row->n_quantity_akhir
                             )
                           ",false);
     } 
  /*
  $query=$this->db->query(" 
                           DELETE FROM tm_ic_trans 
                           where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                           and i_store='$istore' and i_store_location='$istorelocation'
                           and i_store_locationbin='$istorelocationbin' and i_refference_document='$isj'
                     ",false);
  */
    }
    if(isset($row->i_trans)){
      if($row->i_trans!=''){
        return $row->i_trans;
      }else{
        return 1;
      }
    }else{
      return 1;
    }
  }

  function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
    $query=$this->db->query(" 
                            update 
                              tm_mutasi 
                            set 
                              n_git_penjualan=n_git_penjualan-$qsj
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='$iproductmotif'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                     ",false);
    /*
    $query=$this->db->query(" 
                       UPDATE tm_mutasi set n_saldo_akhir=n_saldo_akhir+$qsj, n_git_penjualan=n_git_penjualan-$qsj
                       where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                       and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                       and e_mutasi_periode='$emutasiperiode'
                     ",false);
    */
  }

  function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
    $query=$this->db->query(" 
                            update 
                              tm_ic 
                            set 
                              n_quantity_stock=n_quantity_stock+$qsj
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='00'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                          ",false);
  }

  function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
    $query=$this->db->query(" 
                            update 
                              tm_mutasi 
                            set 
                              n_mutasi_bbm=n_mutasi_bbm-$qsj, 
                              n_saldo_akhir=n_saldo_akhir-$qsj
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='$iproductmotif'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                      ",false);
  }

  function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
    $query=$this->db->query(" 
                            update 
                              tm_ic 
                            set 
                              n_quantity_stock=n_quantity_stock-$qsj
                            where 
                              i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='$iproductmotif'
                              and i_store='$istore' 
                              and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                          ",false);
  }

  function ceksj($ispb,$iarea){
    $this->db->select("
                        * 
                      from 
                        tm_nota 
                      where 
                        i_spb='$ispb' 
                        and i_area='$iarea'"
                      ,false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      return true;
    }else{
      return false;
    }
  }
}

  /* End of file Mmaster.php */
