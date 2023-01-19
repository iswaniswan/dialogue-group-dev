<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
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
                              ",FALSE)->result();
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

    public function data($dfrom,$dto,$departemen,$folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        $thn = substr($dfrom,6,4);
        $datatables->query("
                              select
                                 a.i_bbkretur,
                                 a.d_bbkretur,
                                 a.i_supplier,
                                 b.e_supplier_name,
                                 a.f_bbkretur_cancel as status,
                                 '$dfrom' as dfrom,
                                 '$dto' as dto,
                                 '$folder' as folder,
                                 '$departemen' as departemen

                              from
                                 tm_bbkretur a,
                                 tr_supplier b 
                              where
                                 a.i_supplier = b.i_supplier 
                                 and a.d_bbkretur >= to_date('$dfrom', 'dd-mm-yyyy') 
                                 and a.d_bbkretur <= to_date('$dto', 'dd-mm-yyyy') 
                              order by
                                 a.i_bbkretur
                                
                              "
                              );
        
        $datatables->add('action', function ($data) {
            $ibbk          = trim($data['i_bbkretur']);
            $dbbk          = trim($data['d_bbkretur']);
            $isupplier     = trim($data['i_supplier']);
            $dfrom         = trim($data['dfrom']);
            $dto           = trim($data['dto']);
            $folder        = trim($data['folder']);
            $status        = trim($data['status']);
            $departemen    = trim($data['departemen']);
            $data          = '';
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ibbk/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
           
            if($status != 't' && ($departemen == '6' || $departemen == '1')){
              $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ibbk\"); return false;'><i class='fa fa-trash'></i></a>";
            }

			return $data;
        });

      $datatables->edit('d_bbkretur', function ($data) {
          $d_bbkretur = $data['d_bbkretur'];
          if($d_bbkretur == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_bbkretur) );
          }
      });

      $datatables->edit('i_supplier', function ($data) {
        $i_supplier = $data['i_supplier'];
        $e_supplier_name = $data['e_supplier_name'];
        return '('.$i_supplier.')'.' - '.$e_supplier_name;
      });

      $datatables->edit('status', function ($data) {
        if ($data['status']=='t') {
            $data = '<span class="label label-success label-rouded">Batal</span>';
        }else{
            $data = '<span class="label label-danger label-rouded">Tidak</span>';
        }
        return $data;
      });


        $datatables->hide('folder');
        $datatables->hide('e_supplier_name');
        $datatables->hide('dto');
        $datatables->hide('dfrom');
        $datatables->hide('departemen');

        return $datatables->generate();  
    }

    public function jumlah($ibbk){
      return $this->db->query("
                              select
                                a.i_product
                              from
                                tm_bbkretur_item a
                              where
                                a.i_bbkretur = '$ibbk' 
                              ",false);
    }

    public function delete($ibbkretur) {
  		$this->db->query("update tm_bbkretur set f_bbkretur_cancel='t' WHERE i_bbkretur='$ibbkretur'");
######
		  $ibbkretur=trim($ibbkretur);
		  $query = $this->db->query("select b.e_product_name, a.d_bbkretur, b.n_quantity, b.i_product, b.i_product_grade, b.i_product_motif 
                          from tm_bbkretur a, tm_bbkretur_item b WHERE a.i_bbkretur=b.i_bbkretur and a.i_bbkretur='$ibbkretur'");
		  foreach($query->result() as $row){
			  $jml    = $row->n_quantity;
			  $product= $row->i_product;
			  $grade  = $row->i_product_grade;
			  $motif  = $row->i_product_motif;
			  $eproductname = $row->e_product_name;
        $dbbkretur    = $row->d_bbkretur;
        $istore				    = 'AA';
				$istorelocation		= '01';
				$istorelocationbin= '00';
        $th=substr($dbbkretur,0,4);
			  $bl=substr($dbbkretur,5,2);
			  $emutasiperiode=$th.$bl;
			  $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' and i_refference_document='$ibbkretur'
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
                                '$product','$grade','$motif','$istore','$istorelocation','$istorelocationbin', 
                                '$eproductname', '$ibbkretur', '$now', $jml, 0, $row->n_quantity_akhir+$jml, $row->n_quantity_akhir
                              )
                           ",false);
        }
        if( ($jml!='') && ($jml!=0) ){
          $this->db->query(" 
                            UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$jml, n_saldo_akhir=n_saldo_akhir+$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                            and e_mutasi_periode='$emutasiperiode'
                           ",false);
          $this->db->query(" 
                            UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                           ",false);
        }

		  }
######
    }

    public function baca($ibbk){
      return $this->db->query("
                              select
                                 a.*,
                                 b.e_supplier_name 
                              from
                                 tm_bbkretur a,
                                 tr_supplier b 
                              where
                                 a.i_supplier = b.i_supplier 
                                 and i_bbkretur = '$ibbk'"
                            ,false);
    }

    public function bacadetail($ibbk){
      return $this->db->query("
                              select
                                 a.*,
                                 b.e_product_motifname 
                              from
                                 tm_bbkretur_item a,
                                 tr_product_motif b 
                              where
                                 a.i_bbkretur = '$ibbk' 
                                 and a.i_product = b.i_product 
                                 and a.i_product_motif = b.i_product_motif 
                              order by
                                 a.n_item_no

                              "
                              ,false);
    }
    
    public function getproduct($cari){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
                              select
                                 a.i_product,
                                 c.e_product_name
                              from
                                 tr_product_motif a,
                                 tr_product c 
                              where
                                 a.i_product = c.i_product 
                                 and 
                                 (
                                    upper(a.i_product) like '%$cari%' 
                                    or upper(c.e_product_name) like '%$cari%'
                                 )
                              order by
                                 a.e_product_motifname asc" 
                              ,FALSE);
    }

    public function getdetailproduct($iproduct){
      return $this->db->query("
                              select
                                 a.i_product,
                                 a.i_product_motif,
                                 a.e_product_motifname,
                                 c.e_product_name,
                                 c.v_product_retail
                              from
                                 tr_product_motif a,
                                 tr_product c 
                              where
                                 a.i_product = c.i_product 
                                 and a.i_product = '$iproduct'
                              order by
                                 a.e_product_motifname asc"
                              ,FALSE);
    }

    public function updateheader($ibbkretur, $dbbkretur, $isupplier, $eremark, $vbbkretur){
      $query 	= $this->db->query("SELECT current_timestamp as c");
	   $row   	= $query->row();
	   $now	   = $row->c;
    	$this->db->set(
    		array(
			      'd_bbkretur'            => $dbbkretur,
			      'i_supplier' 	          => $isupplier,
               'e_remark'              => $eremark,
               'v_bbkretur'            => $vbbkretur,
               'd_update'              => $now
    		)
    	);
      $this->db->where("i_bbkretur",$ibbkretur);      
    	$this->db->update('tm_bbkretur');
    }

    public function insertheader($ibbkretur, $dbbkretur, $isupplier, $eremark, $vbbkretur){
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
    	$this->db->set(
    		array(
			      'i_bbkretur'            => $ibbkretur,
			      'd_bbkretur'            => $dbbkretur,
			      'i_supplier' 	         => $isupplier,
               'e_remark'              => $eremark,
               'v_bbkretur'            => $vbbkretur,
               'd_entry'               => $now
    		)
    	);
    	$this->db->insert('tm_bbkretur');
    }

    public function updatedetail($ibbkretur,$isupplier,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$vunitprice,$eremark,$i,$thbl){
    	$this->db->set(
    		array(
					'v_unit_price'		      => $vunitprice
    		)
    	);
    	$this->db->where('i_bbkretur',$ibbkretur);
    	$this->db->where('i_product',$iproduct);
    	$this->db->where('i_product_motif',$iproductmotif);
    	$this->db->where('i_product_grade',$iproductgrade);
    	$this->db->update('tm_bbkretur_item');
    }

    public function insertdetail($ibbkretur,$isupplier,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$vunitprice,$eremark,$i,$thbl){
    	$this->db->set(
    		array(
					'i_bbkretur'            => $ibbkretur,
					'i_supplier'            => $isupplier,
					'i_product'	 	         => $iproduct,
					'i_product_grade'	      => $iproductgrade,
					'i_product_motif'	      => $iproductmotif,
					'n_quantity'		      => $nquantity,
					'v_unit_price'		      => $vunitprice,
					'e_product_name'	      => $eproductname,
					'e_remark'		         => $eremark,
               'e_mutasi_periode'      => $thbl,
               'n_item_no'             => $i
    		)
    	);
    	
    	$this->db->insert('tm_bbkretur_item');
    }

    public function deletedetail($ibbkretur, $iproduct, $iproductmotif, $iproductgrade){
		  $this->db->query("
                           DELETE FROM 
                              tm_bbkretur_item 
                           WHERE 
                              i_bbkretur='$ibbkretur' 
                              and i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' 
                              and i_product_motif='$iproductmotif'
                        ");
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
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
                                 and i_product_motif='$iproductmotif'
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

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
      $query=$this->db->query(" 
                              select
                                 n_quantity_stock 
                              from
                                 tm_ic 
                              where
                                 i_product = '$iproduct' 
                                 and i_product_grade = '$iproductgrade' 
                                 and i_product_motif = '$iproductmotif' 
                                 and i_store = '$istore' 
                                 and i_store_location = '$istorelocation' 
                                 and i_store_locationbin = '$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    public function inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$qbbk,$q_aw,$q_ak){
      $query 	= $this->db->query("SELECT current_timestamp as c");
	   $row   	= $query->row();
	   $now	   = $row->c;
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
                                  '$eproductname', '$ibbk', '$now', 0, $qbbk, $q_ak-$qbbk, $q_ak
                                )
                              ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
      $ada=false;
      $query=$this->db->query(" 
                              select
                                 i_product 
                              from
                                 tm_mutasi 
                              where
                                 i_product = '$iproduct' 
                                 and i_product_grade = '$iproductgrade' 
                                 and i_product_motif = '$iproductmotif' 
                                 and i_store = '$istore' 
                                 and i_store_location = '$istorelocation' 
                                 and i_store_locationbin = '$istorelocationbin' 
                                 and e_mutasi_periode = '$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
			$ada=true;
		}
      return $ada;
    }

    public function updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
      $query=$this->db->query(" 
                              update
                                 tm_mutasi 
                              set
                                 n_mutasi_returpabrik = n_mutasi_returpabrik+$qbbk, n_saldo_akhir = n_saldo_akhir-$qbbk 
                              where
                                 i_product = '$iproduct' 
                                 and i_product_grade = '$iproductgrade' 
                                 and i_product_motif = '$iproductmotif' 
                                 and i_store = '$istore' 
                                 and i_store_location = '$istorelocation' 
                                 and i_store_locationbin = '$istorelocationbin' 
                                 and e_mutasi_periode = '$emutasiperiode'
                              ",false);
    }

    public function insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                    			        n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','AA','01','00','$emutasiperiode',0,0,0,0,0,$qbbk,0,$qbbk,0,'f')
                              ",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
      $ada=false;
      $query=$this->db->query("
                              select
                                 i_product 
                              from
                                 tm_ic 
                              where
                                 i_product = '$iproduct' 
                                 and i_product_grade = '$iproductgrade' 
                                 and i_product_motif = '$iproductmotif' 
                                 and i_store = '$istore' 
                                 and i_store_location = '$istorelocation' 
                                 and i_store_locationbin = '$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
			$ada=true;
		}
      return $ada;
    }

    public function updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$q_ak){
      $query=$this->db->query(" 
                              update
                                 tm_ic 
                              set
                                 n_quantity_stock = n_quantity_stock-$qbbk 
                              where
                                 i_product = '$iproduct' 
                                 and i_product_grade = '$iproductgrade' 
                                 and i_product_motif = '$iproductmotif' 
                                 and i_store = '$istore' 
                                 and i_store_location = '$istorelocation' 
                                 and i_store_locationbin = '$istorelocationbin'
                              ",false);
    }

    public function inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qbbk){
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0, 't'
                                )
                              ",false);
    }

    public function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ibbkretur,$ntmp,$eproductname){
      $queri 		= $this->db->query("
                                    select
                                       n_quantity_akhir,
                                       i_trans 
                                    FROM
                                       tm_ic_trans 
                                    where
                                       i_product = '$iproduct' 
                                       and i_product_grade = '$iproductgrade' 
                                       and i_product_motif = '$iproductmotif' 
                                       and i_store = '$istore' 
                                       and i_store_location = '$istorelocation' 
                                       and i_store_locationbin = '$istorelocationbin' 
                                    order by
                                       i_trans desc
                                    ",false);
      if ($queri->num_rows() > 0){
    	   $row   		= $queri->row();
         $que 	      = $this->db->query("SELECT current_timestamp as c");
	      $ro 	      = $que->row();
	      $now	      = $ro->c;
         if($ntmp!=0 || $ntmp!=''){
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
                                     '$eproductname', '$ibbkretur', '$now', $ntmp, 0, $row->n_quantity_akhir+$ntmp, $row->n_quantity_akhir
                                   )
                                 ",false);
         }
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

    public function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk,$emutasiperiode){
      $query=$this->db->query(" 
                              update
                                 tm_mutasi 
                              set
                                 n_mutasi_returpabrik = n_mutasi_returpabrik-$qbbk, n_saldo_akhir = n_saldo_akhir+$qbbk 
                              where
                                 i_product = '$iproduct' 
                                 and i_product_grade = '$iproductgrade' 
                                 and i_product_motif = '$iproductmotif' 
                                 and i_store = '$istore' 
                                 and i_store_location = '$istorelocation' 
                                 and i_store_locationbin = '$istorelocationbin' 
                                 and e_mutasi_periode = '$emutasiperiode'
                              ",false);
    }

    public function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qbbk){
      $query=$this->db->query(" 
                              update
                                 tm_ic 
                              set
                                 n_quantity_stock = n_quantity_stock+$qbbk 
                              where
                                 i_product = '$iproduct' 
                                 and i_product_grade = '$iproductgrade' 
                                 and i_product_motif = '$iproductmotif' 
                                 and i_store = '$istore' 
                                 and i_store_location = '$istorelocation' 
                                 and i_store_locationbin = '$istorelocationbin'
                              ",false);
    }

}

  /* End of file Mmaster.php */
