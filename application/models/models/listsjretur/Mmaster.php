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
        ", FALSE)->result();
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                               a.i_sjr,
                               a.d_sjr,
                               a.i_sjr_old,
                               b.e_area_name ,
                               a.d_sjr_receive as terima,
                               a.f_sjr_cancel as status,
                               '$dfrom' as dfrom,
                               '$dto' as dto,
                               '$iarea' as iarea,
                               '$i_menu' as i_menu,
                               '$folder' as folder
                            from
                               tm_sjr a,
                               tr_area b 
                            where
                               a.i_area = b.i_area 
                               and a.i_area = '$iarea' 
                               and a.d_sjr >= to_date('$dfrom', 'dd-mm-yyyy') 
                               and a.d_sjr <= to_date('$dto', 'dd-mm-yyyy') 
                            ORDER BY
                               a.i_sjr desc"
                        );
        
        $datatables->add('action', function ($data) {
            $isjr             = trim($data['i_sjr']);
            $fsjrcancel       = trim($data['status']);
            $dsjrreceive      = trim($data['terima']);
            $i_menu           = $data['i_menu'];
            $dfrom            = $data['dfrom'];
            $dto              = $data['dto'];
            $iarea            = $data['iarea'];
            $folder           = $data['folder'];
            $data             = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjr/$iarea/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }

            if(check_role($i_menu, 4) && $fsjrcancel == 'f' && $dsjrreceive == ''){
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isjr\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>";
            }

			return $data;
        });

        $datatables->edit('status', function ($data) {
          if ($data['status']=='f') {
              $data = '<span class="label label-success label-rouded">Tidak</span>';
          }else{
              $data = '<span class="label label-danger label-rouded">Ya</span>';
          }
          return $data;
        });

        $datatables->edit('d_sjr', function ($data) {
            $d_sjr = $data['d_sjr'];
            if($d_sjr == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sjr) );
            }
        });

        $datatables->edit('terima', function ($data) {
          if ($data['terima']=='') {
              $data = '<span class="label label-success label-rouded">Belum</span>';
          }else{
              $data = '<span class="label label-danger label-rouded">Sudah</span>';
          }
          return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('iarea');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');

        return $datatables->generate();  
    }

    function baca($isjr,$iarea){
        $query = $this->db->query(" 
                                  select distinct
                                    (c.i_store),
                                     c.i_store_location,
                                     a.*,
                                     b.e_area_name 
                                  from
                                     tm_sjr a,
                                     tr_area b,
                                     tm_sjr_item c 
                                  where
                                     a.i_area = b.i_area 
                                     and a.i_sjr = c.i_sjr 
                                     and a.i_area = c.i_area 
                                     and a.i_sjr = '$isjr' 
                                     and a.i_area = '$iarea'"
                                , false);
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacadetail($isjr,$iarea){
        $query = $this->db->query(" 
                                  select
                                     a.i_sjr,
                                     a.d_sjr,
                                     a.i_area,
                                     a.i_product,
                                     a.i_product_grade,
                                     a.i_product_motif,
                                     a.n_quantity_retur,
                                     a.n_quantity_receive,
                                     a.v_unit_price,
                                     a.e_product_name,
                                     a.i_store,
                                     a.i_store_location,
                                     a.i_store_locationbin,
                                     a.e_remark,
                                     b.e_product_motifname 
                                  from
                                     tm_sjr_item a,
                                     tr_product_motif b 
                                  where
                                     a.i_sjr = '$isjr' 
                                     and a.i_area = '$iarea' 
                                     and a.i_product = b.i_product 
                                     and a.i_product_motif = b.i_product_motif 
                                  order by
                                     a.n_item_no"
                                , false);
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    public function bacaproductx($iproduct) {
      return $this->db->query("
        SELECT
            a.i_product AS kode,
            a.i_product_motif AS motif,
            a.e_product_motifname AS namamotif,
            c.i_product_status,
            e.e_product_statusname,
            c.e_product_name AS nama,
            b.v_product_retail AS harga
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e
        WHERE
            d.i_product_type = c.i_product_type
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND c.i_product_status <> '4'
            AND b.i_product_grade = 'A'
            AND c.i_product = '$iproduct' "
        );
  }

  public function bacaproducticx($istore,$iproduct){
    return $this->db->query(" 
      SELECT
          a.i_product AS kode,
          a.i_product_motif AS motif,
          a.e_product_motifname AS namamotif,
          c.i_product_status,
          e.e_product_statusname,
          c.e_product_name AS nama,
          b.v_product_retail AS harga
      FROM
          tr_product_motif a,
          tr_product_price b,
          tr_product c,
          tr_product_type d,
          tr_product_status e,
          tm_ic f
      WHERE
          d.i_product_type = c.i_product_type
          AND b.i_product = a.i_product
          AND a.i_product_motif = '00'
          AND a.i_product = c.i_product
          AND c.i_product_status = e.i_product_status
          AND b.i_product_grade = 'A'
          AND a.i_product = f.i_product
          AND f.i_store = '$istore'
          AND f.f_product_active = 't'
          AND f.n_quantity_stock>0
          AND b.i_product_grade = f.i_product_grade
          AND c.i_product = '$iproduct' ");
  }


    public function insertheader($ispmb, $dspmb, $iarea, $fop, $nprint){
    	$this->db->set(
    		array(
			        'i_spmb'	=> $ispmb,
			        'd_spmb'	=> $dspmb,
			        'i_area'	=> $iarea,
			        'f_op'		=> 'f',
			        'n_print'	=> 0
    		    )
    	);	
    	$this->db->insert('tm_spmb');
    }

    public function insertdetail($ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$vunitprice,$iproductmotif,$eremark){
    	$this->db->set(
    		array(
					    'i_spmb'			=> $ispmb,
					    'i_product'			=> $iproduct,
					    'i_product_grade'	=> $iproductgrade,
					    'i_product_motif'	=> $iproductmotif,
					    'n_order'			=> $norder,
					    'v_unit_price'		=> $vunitprice,
					    'e_product_name'	=> $eproductname,
					    'e_remark'			=> $eremark
    		    )
    	);
    	
    	$this->db->insert('tm_spmb_item');
    }

    public function updateheader($ispmb, $dspmb, $iarea){
    	$this->db->set(
    		array(
			        'd_spmb'	=> $dspmb,
			        'i_area'	=> $iarea
    		)
    	);
    	$this->db->where('i_spmb',$ispmb);
    	$this->db->update('tm_spmb');
    }

    public function deletedetail($iproduct, $iproductgrade, $ispmb, $iproductmotif){
		  $this->db->query("
                      DELETE FROM 
                        tm_spmb_item 
                      WHERE 
                        i_spmb='$ispmb'
                        and i_product='$iproduct' 
                        and i_product_grade='$iproductgrade' 
                        and i_product_motif='$iproductmotif'
                    ");
		  return TRUE;
    }
	
    public function delete($isj, $iarea) 
    {
			$this->db->query("update tm_sjr set f_sjr_cancel='t' WHERE i_sjr='$isj' and i_area='$iarea'");
#####
      $this->db->select(" * from tm_sjr where i_sjr='$isj' and i_area='$iarea'");
			$qry = $this->db->get();
			if ($qry->num_rows() > 0){
        foreach($qry->result() as $qyr){  
          $dsjr=$qyr->d_sjr;
        }
      }
      $th=substr($dsjr,0,4);
		  $bl=substr($dsjr,5,2);
		  $emutasiperiode=$th.$bl;
      $que = $this->db->query("select i_store from tr_area where i_area='$iarea'");
      $st=$que->row();
      $istore=$st->i_store;
      if($istore=='AA'){
				$istorelocation		= '01';
			}else{
 				$istorelocation		= '00';
			}
			$istorelocationbin	= '00';
      $this->db->select(" * from tm_sjr_item where i_sjr='$isj' and i_area='$iarea' order by n_item_no");
			$qery = $this->db->get();
			if ($qery->num_rows() > 0){
        foreach($qery->result() as $qyre){  
          $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                        and i_product_motif='$qyre->i_product_motif'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin' and i_refference_document='$isj'
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
                                '$qyre->e_product_name', '$isj', '$now', $qyre->n_quantity_retur, 0, 
                                $row->n_quantity_akhir+$qyre->n_quantity_retur, $row->n_quantity_akhir
                              )
                             ",false);

            $this->db->query(" 
                              UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$qyre->n_quantity_retur, 
                              n_mutasi_git=n_mutasi_git-$qyre->n_quantity_retur,
                              n_saldo_akhir=n_saldo_akhir+$qyre->n_quantity_retur
                              where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                              and i_product_motif='$qyre->i_product_motif'
                              and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                             ",false);
            $this->db->query(" 
                              UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qyre->n_quantity_retur
                              where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                              and i_product_motif='$qyre->i_product_motif' and i_store='$istore' and i_store_location='$istorelocation' 
                              and i_store_locationbin='$istorelocationbin'
                             ",false);

          }
        }
      }
    }

    public function insertsjheader($isj,$dsj,$iarea,$vspbnetto,$isjold){
		  $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjentry	= $row->c;
      	$this->db->set(
      		array(
		  		      'i_sjr'				=> $isj,
		  		      'i_sjr_old'		=> $isjold,
		  		      'd_sjr'				=> $dsj,
		  		      'i_area'		  => $iarea,
		  		      'v_sjr'		    => $vspbnetto,
		  		      'd_sjr_entry'	=> $dsjentry,
		  		      'f_sjr_cancel'=> 'f'
      		)
      	);
      
      $this->db->insert('tm_sjr');
    }

    public function insertsjheader2($isj,$dsj,$iarea,$vspbnetto,$isjold){
		  $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjentry	= $row->c;
      $this->db->set(
      	array(
		  	      'i_sjr' 				=> $isj,
		  	      'i_sjr_old'			=> $isjold,
		  	      'd_sjr'				  => $dsj,
		  	      'i_area'		  	=> $iarea,
		  	      'v_sjr'		      => $vspbnetto,
		  	      'd_sjr_entry'		=> $dsjentry,
		  	      'f_sjr_cancel'	=> 'f'
      	)
      );
      
      $this->db->insert('tm_nota');
    } 

    public function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,
			                      $vunitprice,$isj,$dsj,$iarea, $istore,$istorelocation,$istorelocationbin,$eremark,$i){
      $th=substr($dsj,0,4);
      $bl=substr($dsj,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				      'i_sjr'			          => $isj,
				      'd_sjr'			          => $dsj,
				      'i_area'		          => $iarea,
				      'i_product'       		=> $iproduct,
				      'i_product_motif'   	=> $iproductmotif,
				      'i_product_grade'   	=> $iproductgrade,
				      'e_product_name'    	=> $eproductname,
				      'n_quantity_retur'  	=> $nretur,
				      'n_quantity_receive'	=> $nreceive,
				      'v_unit_price'		    => $vunitprice,
				      'i_store'         		=> $istore,
				      'i_store_location'	  => $istorelocation,
				      'i_store_locationbin'	=> $istorelocationbin, 
              'e_remark'            => $eremark,
              'e_mutasi_periode'    => $pr,
              'n_item_no'           => $i
    		)
    	);
    	
    	$this->db->insert('tm_sjr_item');
    }

    public function updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea){
      $this->db->query(" 
                        update 
                          tm_spmb_item 
                        set 
                          n_deliver = n_deliver+$ndeliver
                        where 
                          i_spmb='$ispmb' 
                          and i_area='$iarea' 
                          and i_product='$iproduct' 
                          and i_product_grade='$iproductgrade'
                          and i_product_motif='$iproductmotif' 
                      ",false);
    }

    public function updatesjheader($isj,$iarea,$isjold,$dsj,$vsjnetto){
      $query 		= $this->db->query("SELECT current_timestamp as c");
		  $row   		= $query->row();
		  $dsjupdate= $row->c;
    	$this->db->set(
    		array(
				      'i_sjr_old'	  => $isjold,
				      'v_sjr'	      => $vsjnetto,
				      'd_sjr'       => $dsj,
              'd_sjr_update'=> $dsjupdate
    		)
    	);
    	$this->db->where('i_sjr',$isj);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjr');
    }

    public function searchsjheader($isjr,$iarea){
		  return $this->db->query(" 
                              SELECT 
                                * 
                              FROM 
                                tm_sjr 
                              WHERE 
                                i_sjr='$isjr' 
                                AND i_area='$iarea' 
                              ");
    }  
      
    public function deletesjdetail($isj, $iarea, $iproduct, $iproductgrade, $iproductmotif){
	    $this->db->query("
                        DELETE FROM 
                          tm_sjr_item 
                        WHERE 
                          i_sjr='$isj'
                          and i_area='$iarea'
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
                                order by i_trans desc
                              ",false);
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
                                i_product='$iproduct' 
                                and i_product_grade='$iproductgrade' 
                                and i_product_motif='$iproductmotif'
                                and i_store='$istore' 
                                and i_store_location='$istorelocation' 
                                and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
      $ada=false;
      $query=$this->db->query(" 
                                select 
                                  i_product
                                from 
                                  tm_ic
                                where 
                                  i_product='$iproduct' 
                                  and i_product_grade='$iproductgrade' 
                                  and i_product_motif='$iproductmotif'
                                  and i_store='$istore' 
                                  and i_store_location='$istorelocation' 
                                  and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }

    public function inserttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak){
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
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    }

    public function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak){
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
                                  '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                )
                              ",false);
    }

    public function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
      $query=$this->db->query(" 
                                UPDATE 
                                  tm_mutasi 
                                set 
                                  n_mutasi_git=n_mutasi_git+$qsj
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

    public function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$q_aw,$q_ak){
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close, n_mutasi_git)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',$q_aw,0,0,0,0,0,0,$q_ak,0,'f',$qsj)
                              ",false);
    }

    public function updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
      $query=$this->db->query(" 
                                UPDATE 
                                  tm_ic 
                                set 
                                  n_quantity_stock=$q_ak-$qsj
                                where 
                                  i_product='$iproduct' 
                                  and i_product_grade='$iproductgrade' 
                                  and i_product_motif='$iproductmotif'
                                  and i_store='$istore' 
                                  and i_store_location='$istorelocation' 
                                  and i_store_locationbin='$istorelocationbin'
                              ",false);
    }

    public function insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj){
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0-$qsj, 't'
                                )
                              ",false);
    }
    function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $hasil='kosong';
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$hasil='ada';
			}
      return $hasil;
    }
    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname)
    {
      $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' 
                                    order by i_trans desc",false);
#and i_refference_document='$isj'
      if ($queri->num_rows() > 0){
    	  $row   		= $queri->row();
        $que 	= $this->db->query("SELECT current_timestamp as c");
	      $ro 	= $que->row();
	      $now	 = $ro->c;
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
                                    '$eproductname', '$isj', '$now', $ntmp, 0, $row->n_quantity_akhir+$ntmp, $row->n_quantity_akhir
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
    function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$qsj, n_mutasi_git=n_mutasi_git-$qsj, n_saldo_akhir=n_saldo_akhir+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function deletesjheader($isjr,$iarea)
	  {
		  $this->db->query(" delete from tm_sjr where i_sjr='$isjr' and i_area='$iarea' ",false);
	  }    
}

/* End of file Mmaster.php */
