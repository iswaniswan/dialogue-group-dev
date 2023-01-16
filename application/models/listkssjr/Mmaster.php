<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($iperiode,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select distinct
                                tm_ic_convertion.i_ic_convertion,
                                tm_ic_convertion.i_refference,
                                tm_sjr.i_area,
                                tr_area.e_area_name,
                                tm_ic_convertion.d_ic_convertion,
                                tm_ic_convertion.f_ic_convertioncancel,
                                '$iperiode' as iperiode,
                                '$folder' as folder,
                                '$i_menu' as i_menu
                            from
                                tm_ic_convertion,
                                tm_sjr,
                                tr_area 
                            where
                                tm_ic_convertion.i_refference = tm_sjr.i_sjr 
                                and tm_sjr.i_area = tr_area.i_area 
                                and to_char(d_ic_convertion, 'yyyymm') = '$iperiode'"
                        , FALSE);

        $datatables->add('action', function ($data) {
            $id                    = trim($data['i_ic_convertion']);
            $iicconvertion         = trim($data['i_refference']);
            $i_menu                = trim($data['i_menu']);
            $ficconvertioncancel   = $data['f_ic_convertioncancel'];
            $folder     = $data['folder'];
            $iperiode   = $data['iperiode'];
            $data       = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$iicconvertion/$iperiode\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $ficconvertioncancel == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$iicconvertion\",\"$iperiode\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('d_ic_convertion', function($data){
            return date("d-m-Y", strtotime($data['d_ic_convertion']));
        });

        $datatables->edit('f_ic_convertioncancel', function ($data) {
            if ($data['f_ic_convertioncancel']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->hide('iperiode');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function cancel($iicconvertion,$istore,$istorelocation,$istorelocationbin) {
  		$this->db->query("update tm_ic_convertion set f_ic_convertioncancel='t' WHERE i_ic_convertion='$iicconvertion'");
  		$this->db->query("update tm_bbk set f_bbk_cancel='t' where i_refference_document ='$iicconvertion'");
		  $this->db->select(" d_refference_document from tm_bbk where i_refference_document ='$iicconvertion'",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
          $dreff=$row->d_refference_document;
          $th=substr($dreff,0,4);
		      $bl=substr($dreff,5,2);
		      $emutasiperiode=$th.$bl;
        }
      }
		  $this->db->select(" * from tm_bbk_item where i_refference_document ='$iicconvertion'",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
				  $this->db->query("update tm_ic set n_quantity_stock=n_quantity_stock+$row->n_quantity
								    where i_product ='$row->i_product' and i_product_grade ='$row->i_product_grade'
								    and i_product_motif ='$row->i_product_motif' and i_store ='$istore' 
								    and i_store_location ='$istorelocation' and i_store_locationbin ='$istorelocationbin'");

          $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                                        and i_product_motif='$row->i_product_motif'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin' and i_refference_document='$iicconvertion'
                                        order by d_transaction desc, i_trans desc",false);
          if ($queri->num_rows() > 0){
        	  $rowtran= $queri->row();
            $nawal  = $rowtran->n_quantity_akhir;
          }else{
            $queri 		= $this->db->query("SELECT n_quantity_stock FROM tm_ic
                              where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                              and i_product_motif='$row->i_product_motif'
                              and i_store='$istore' and i_store_location='$istorelocation'
                              and i_store_locationbin='$istorelocationbin'",false);
            if ($queri->num_rows() > 0){
          	  $rowic   		= $queri->row();
              $nawal=$rowic->n_quantity_stock;
            }
          }
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
                              '$row->i_product','$row->i_product_grade','$row->i_product_motif',
                              '$istore','$istorelocation','$istorelocationbin', 
                              '$row->e_product_name', '$iicconvertion', '$now', $row->n_quantity, 0, 
                              $nawal+$row->n_quantity, $nawal
                            )
                           ",false);

          $this->db->query(" 
                            UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$row->n_quantity, 
                            n_saldo_akhir=n_saldo_akhir+$row->n_quantity
                            where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                            and i_product_motif='$row->i_product_motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                            and e_mutasi_periode='$emutasiperiode'
                           ",false);

			  }
		  }
  		$this->db->query("update tm_bbm set f_bbm_cancel='t' where i_refference_document ='$iicconvertion'");
		  $this->db->select(" d_refference_document from tm_bbm where i_refference_document ='$iicconvertion'",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
          $dreff=$row->d_refference_document;
          $th=substr($dreff,0,4);
		      $bl=substr($dreff,5,2);
		      $emutasiperiode=$th.$bl;
        }
      }
		  $this->db->select(" * from tm_bbm_item where i_refference_document ='$iicconvertion'",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
				  $this->db->query("update tm_ic set n_quantity_stock=n_quantity_stock-$row->n_quantity
								    where i_product ='$row->i_product' and i_product_grade ='$row->i_product_grade'
								    and i_product_motif ='$row->i_product_motif' and i_store ='$istore' 
								    and i_store_location ='$istorelocation' and i_store_locationbin ='$istorelocationbin'");

          $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                                        and i_product_motif='$row->i_product_motif'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin' and i_refference_document='$iicconvertion'
                                        order by d_transaction desc, i_trans desc",false);
          if ($queri->num_rows() > 0){
        	  $rowtran= $queri->row();
            $nawal  = $rowtran->n_quantity_akhir;
          }else{
            $queri 		= $this->db->query("SELECT n_quantity_stock FROM tm_ic
                              where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                              and i_product_motif='$row->i_product_motif'
                              and i_store='$istore' and i_store_location='$istorelocation'
                              and i_store_locationbin='$istorelocationbin'",false);
            if ($queri->num_rows() > 0){
          	  $rowic   		= $queri->row();
              $nawal=$rowic->n_quantity_stock;
            }
          }
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
                              '$row->i_product','$row->i_product_grade','$row->i_product_motif',
                              '$istore','$istorelocation','$istorelocationbin', 
                              '$row->e_product_name', '$iicconvertion', '$now', 0, $row->n_quantity, 
                              $nawal-$row->n_quantity, $nawal
                            )
                           ",false);

          $this->db->query(" 
                            UPDATE tm_mutasi set n_mutasi_bbm=n_mutasi_bbm-$row->n_quantity, 
                            n_saldo_akhir=n_saldo_akhir-$row->n_quantity
                            where i_product='$row->i_product' and i_product_grade='$row->i_product_grade' 
                            and i_product_motif='$row->i_product_motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                            and e_mutasi_periode='$emutasiperiode'
                           ",false);

			  }
		  }
#		  $this->db->query('DELETE FROM tm_bbm_item where i_refference_document =\''.$iicconvertion.'\'');
    }

    public function bacadetail($irefference){
        return $this->db->query("
                                    select 
                                        * 
                                    from 
                                        tm_ic_convertion
                                    where 
                                        i_refference = '$irefference'
                                    order by 
                                        i_product"
                                    , false);
    }

    public function bacaheaderdetail($irefference){
        return $this->db->query("
                                select distinct
                                    a.i_refference,
                                    a.d_ic_convertion,
                                    a.f_ic_convertioncancel,
                                    b.i_area,
                                    c.e_area_name 
                                from
                                    tm_ic_convertion a,
                                    tm_sjr b,
                                    tr_area c 
                                where
                                    a.i_refference = '$irefference' 
                                    and a.i_refference = b.i_sjr 
                                    and b.i_area = c.i_area"
                                , false);
    }
}

/* End of file Mmaster.php */
