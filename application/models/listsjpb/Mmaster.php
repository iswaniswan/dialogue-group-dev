<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacacustomer($iarea){
      if($iarea == 00 || $iarea=='PB'){
        return $this->db->query("
            SELECT
              a.*,
              b.e_customer_name
            FROM
              tr_spg a,
              tr_customer b

            WHERE
              a.i_customer = b.i_customer 
            ORDER BY
              a.i_customer", FALSE)->result();
      }else{
        return $this->db->query("
            SELECT
              a.*,
              b.e_customer_name
            FROM
              tr_spg a,
              tr_customer b,
              tr_customer_consigment c

            WHERE
              a.i_customer = b.i_customer 
              AND b.i_customer = c.i_customer
              AND c.i_area_real = '$iarea'
            ORDER BY
              a.i_customer", FALSE)->result();
      }
    }

    public function data($dfrom,$dto,$icustomer,$iarea,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        if($iarea == '00' || $iarea == 'PB'){
          if($icustomer == 'AS'){
            $datatables->query("
                          SELECT
                            a.i_sjpb,
                            a.d_sjpb, 
                            a.i_sjp,
                            a.i_customer,
                            c.e_customer_name,
                            a.d_sjpb_receive,
                            a.v_sjpb,
                            a.f_sjpb_cancel,
                            '$i_menu' as i_menu,
                            '$dfrom' as dfrom,
                            '$dto' as dto,
                            '$iarea' as iarea
                          FROM 
                            tm_sjpb a, 
                            tr_area b, 
                            tr_customer c
                          WHERE 
                            a.i_area=b.i_area 
                            AND a.i_customer=c.i_customer
                            AND (a.d_sjpb >= to_date('$dfrom','dd-mm-yyyy') 
                            AND a.d_sjpb <= to_date('$dto','dd-mm-yyyy'))
                          ORDER BY 
                            a.i_sjpb asc", FALSE);
          }else{
            $datatables->query("
                          SELECT
                            a.i_sjpb,
                            a.d_sjpb, 
                            a.i_sjp,
                            a.i_customer,
                            c.e_customer_name,
                            a.d_sjpb_receive,
                            a.v_sjpb,
                            a.f_sjpb_cancel,
                            '$i_menu' as i_menu,
                            '$dfrom' as dfrom,
                            '$dto' as dto,
                            '$iarea' as iarea
                          FROM 
                            tm_sjpb a, 
                            tr_area b, 
                            tr_customer c
                          WHERE 
                            a.i_area=b.i_area 
                            AND a.i_customer=c.i_customer
                            AND (a.d_sjpb >= to_date('$dfrom','dd-mm-yyyy') 
                            AND a.d_sjpb <= to_date('$dto','dd-mm-yyyy'))
                            AND a.i_customer = '$icustomer'
                          ORDER BY 
                            a.i_sjpb asc", FALSE);
          }
        }else{
          if($icustomer == 'AS'){
            $datatables->query("
                            SELECT
                              a.i_sjpb,
                              a.d_sjpb, 
                              a.i_sjp,
                              a.i_customer,
                              c.e_customer_name,
                              a.d_sjpb_receive,
                              a.v_sjpb,
                              a.f_sjpb_cancel,
                              '$i_menu' as i_menu,
                              '$dfrom' as dfrom,
                              '$dto' as dto,
                              '$iarea' as iarea
                            FROM 
                              tm_sjpb a, 
                              tr_area b, 
                              tr_customer c
                            WHERE 
                              a.i_area=b.i_area 
                              AND a.i_customer=c.i_customer
                              AND a.i_area_entry='$iarea'
                              AND (a.d_sjpb >= to_date('$dfrom','dd-mm-yyyy') 
                              AND a.d_sjpb <= to_date('$dto','dd-mm-yyyy'))
                            ORDER BY 
                              a.i_sjpb asc", FALSE);
          }else{
            $datatables->query("
                            SELECT
                              a.i_sjpb,
                              a.d_sjpb, 
                              a.i_sjp,
                              a.i_customer,
                              c.e_customer_name,
                              a.d_sjpb_receive,
                              a.v_sjpb,
                              a.f_sjpb_cancel,
                              '$i_menu' as i_menu,
                              '$dfrom' as dfrom,
                              '$dto' as dto,
                              '$iarea' as iarea
                            FROM 
                              tm_sjpb a, 
                              tr_area b, 
                              tr_customer c
                            WHERE 
                              a.i_area=b.i_area 
                              AND a.i_customer=c.i_customer
                              AND a.i_area_entry='$iarea'
                              AND a.i_customer='$icustomer'
                              AND (a.d_sjpb >= to_date('$dfrom','dd-mm-yyyy') 
                              AND a.d_sjpb <= to_date('$dto','dd-mm-yyyy'))
                            ORDER BY 
                              a.i_sjpb asc", FALSE);
          }    
        }

        $datatables->add('action', function ($data) {
          $i_sjpb           = trim($data['i_sjpb']);
          $i_sjp            = trim($data['i_sjp']);
          $i_customer       = trim($data['i_customer']);
          $f_sjpb_cancel    = trim($data['f_sjpb_cancel']);
          $d_sjpb_terima    = trim($data['d_sjpb_receive']);
          $i_menu           = $data['i_menu'];
          $dfrom            = $data['dfrom'];
          $dto              = $data['dto'];
          $iarea            = $data['iarea'];
          $data             = '';
          
            if(check_role($i_menu, 3)){
              if(($iarea=='PB' || $iarea=='00') && ($this->session->userdata('i_departement')=='7') || ($this->session->userdata('i_departement')=='1')){
                  $data .= "<a href=\"#\" onclick='show(\"listsjpb/cform/edit/$i_sjpb/$iarea/$i_customer/$dfrom/$dto/$i_sjp\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                if($f_sjpb_cancel == 'f' && $d_sjpb_terima == ''){
                  if(check_role($i_menu, 4)){
                    $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sjpb\",\"$i_customer\"); return false;'><i class='fa fa-trash'></i></a>";
                  }
                }
              }
            }

          return $data;
        });

        $datatables->edit('d_sjpb', function ($data) {
            $d_sjpb = $data['d_sjpb'];
            if($d_sjpb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjpb));
            }
        });

        $datatables->edit('d_sjpb_receive', function ($data) {
            $d_sjpb_terima = $data['d_sjpb_receive'];
            if($d_sjpb_terima == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjpb_terima) );
            }
        });

        $datatables->edit('f_sjpb_cancel', function ($data) {
            $f_sjpb_cancel = $data['f_sjpb_cancel'];
            $i_sjpb = $data['i_sjpb'];
            if($f_sjpb_cancel == 't'){
                return "<td><h2>$i_sjpb</h2></td>";
            }else{
                return $i_sjpb;
            }
        });

        $datatables->edit('v_sjpb', function ($data) {
            $v_sjpb         = $data['v_sjpb'];
            return number_format($v_sjpb);
        });

        $datatables->edit('i_customer', function ($data) {
            $i_customer      = $data['i_customer'];
            $e_customer_name = $data['e_customer_name'];
            if($i_customer == ''){
                return '';
            }else{
                return "(".$i_customer.")"." - ".$e_customer_name;
            }
        });

        $datatables->hide('i_menu');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('iarea');
        $datatables->hide('i_sjp');
        $datatables->hide('e_customer_name');
        $datatables->hide('f_sjpb_cancel');

        return $datatables->generate();  
    }

    function baca($isj,$icustomer){
      return $this->db->query(" 
                          SELECT 
                            a.*, 
                            c.e_customer_name, 
                            b.e_area_name, 
                            b.i_store
                          FROM 
                            tm_sjpb a, 
                            tr_area b, 
                            tr_customer c
                          WHERE 
                            a.i_area=b.i_area 
                            AND a.i_customer=c.i_customer
                            AND a.i_sjpb ='$isj' "
                          , false);
    }

    function bacadetail($isj, $icustomer){
      $this->db->select(" a.*, b.e_product_motifname, c.v_sjpb from tm_sjpb_item a 
                          inner join tr_product_motif b on (a.i_product=b.i_product  
                          and a.i_product_motif=b.i_product_motif) inner join tm_sjpb c 
                          on (a.i_sjpb=c.i_sjpb and a.i_area=c.i_area) left join tm_sjp_item d
                          on (a.i_product_grade=d.i_product_grade and a.i_product_motif=d.i_product_motif
                          and a.i_product=d.i_product and c.i_sjp=d.i_sjp and c.i_area=d.i_area )
                          where a.i_sjpb ='$isj' order by a.n_item_no", false);                    
                          
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        return $query->result();
      }
    }

    function updateheader2($isjpb, $iarea, $icustomer, $ispg, $tglsj, $vsjpb, $tglupdate){
      $query = "update tm_sjpb set v_sjpb = '$vsjpb', d_sjpb_update = '$tglupdate' where i_sjpb = '$isjpb' and i_area = '$iarea' and i_customer = '$icustomer' and i_spg = '$ispg' and d_sjpb = '$tglsj'";
      $this->db->query($query);
    }

    function updatedetail2($isjpb, $iarea, $iproduct, $iproductgrade, $ndeliver, $vproductmill){
      $query = "update tm_sjpb_item set n_deliver = '$ndeliver', v_unit_price = '$vproductmill' where i_sjpb = '$isjpb' and i_area = '$iarea' and i_product = '$iproduct' and i_product_grade = '$iproductgrade'";
      $this->db->query($query);
    }

    public function delete($isjpb,$icustomer){
			$this->db->query(" update tm_sjpb set f_sjpb_cancel='t' WHERE i_sjpb='$isjpb' and i_customer='$icustomer'");
#####
      $query      = $this->db->query(" select * from tm_sjpb where i_sjpb='$isjpb' and i_customer='$icustomer'");
      $hasil      = $query->row();
      $dsjpb      = $hasil->d_sjpb;
      $isjp      = $hasil->i_sjp;
      $iarea      = $hasil->i_area;
			
      $th=substr($dsjpb,0,4);
		  $bl=substr($dsjpb,5,2);
		  $emutasiperiode=$th.$bl;
      $this->db->query(" update tm_sjp set i_sjpb=NULL, d_sjpb=NULL where i_sjp='$isjp' and i_area='$iarea' ");
      $this->db->query(" update tm_sjp_item set n_saldo=n_quantity_receive where i_sjp='$isjp' and i_area='$iarea' ");
      $que = $this->db->query("select i_store from tr_area where i_area='$iarea'");
      $st=$que->row();
      $istore=$st->i_store;
      if($istore=='PB'){
				$istorelocation		= '00';
			}else{
        $istorelocation		= 'PB';
			}
			$istorelocationbin	= '00';
      $this->db->select(" * from tm_sjpb_item where i_sjpb='$isjpb' and i_area='$iarea' order by n_item_no");
			$qery = $this->db->get();
			if ($qery->num_rows() > 0){
        foreach($qery->result() as $qyre){  
          $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                        and i_product_motif='$qyre->i_product_motif'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin'
                                        order by d_transaction desc, i_trans desc",false);
          if ($queri->num_rows() > 0){
        	  $row   		= $queri->row();
            $que 	= $this->db->query("SELECT current_timestamp as c");
	          $ro 	= $que->row();
	          $now	 = $ro->c;
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
                                '$qyre->e_product_name', '$isjpb', '$now', $qyre->n_deliver, 0, $row->n_quantity_akhir+$qyre->n_deliver, 
                                $row->n_quantity_akhir
                              )
                             ",false);

            $this->db->query(" 
                              UPDATE tm_mutasi set n_mutasi_penjualan=n_mutasi_penjualan-$qyre->n_deliver, 
                              n_saldo_akhir=n_saldo_akhir+$qyre->n_deliver
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
}

/* End of file Mmaster.php */
