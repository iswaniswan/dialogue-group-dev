<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username,$idcompany){
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
                  AND id_company = '$idcompany')
      ", FALSE);
      if ($query->num_rows()>0) {
          $kuy   = $query->row();
          $iarea = $kuy->i_area; 
      }else{
        $iarea = '';
      }
      return $iarea;
    }

    // public function bacacustomer($username,$iarea){
    //   $query = $this->db->query("
    //                           select
    //                              a.i_customer,
    //                              a.i_area,
    //                              a.e_spg_name,
    //                              a.i_user,
    //                              b.e_area_name,
    //                              c.e_customer_name 
    //                           from
    //                              tr_spg a,
    //                              tr_area b,
    //                              tr_customer c 
    //                           where
    //                              upper(a.i_spg) = '$username' 
    //                              and a.i_area = '$iarea' 
    //                              and a.i_area = b.i_area 
    //                              and a.i_customer = c.i_customer
    //                           ", FALSE);
    //   if ($query->num_rows()>0) {
    //       $kuy   = $query->row();
    //       $icustomer = $kuy->i_customer; 
    //   }else{
    //     $icustomer = '';
    //   }
    //   return $icustomer;
    // }

    public function data($iuser,$dfrom,$dto,$iarea,$icustomer,$folder){
        $datatables = new Datatables(new CodeigniterAdapter);
        if($iuser==''){
          if($iarea='00'){
            $datatables->query("
                              select
                                 a.i_sopb,
                                 a.d_sopb,
                                 a.i_customer,
                                 b.e_customer_name,
                                 a.f_sopb_cancel as status,
                                 '$dfrom' as dfrom,
                                 '$dto' as dto,
                                 '$iarea' as iarea,
                                 '$icustomer' as icustomer, 
                                 '$folder' as folder,
                                 '$iuser' as iuser
                              from
                                 tm_sopb a,
                                 tr_customer b 
                              where
                                 a.i_customer = b.i_customer 
                                 and a.d_sopb >= to_date('$dfrom', 'dd-mm-yyyy') 
                                 and a.d_sopb <= to_date('$dto', 'dd-mm-yyyy') 
                              order by
                                 a.i_area,
                                 i_customer,
                                 a.i_sopb"
                            );
          }else{  
            $datatables->query("
                            select
                               a.i_sopb,
                               a.d_sopb,
                               a.i_customer,
                               b.e_customer_name,
                               a.f_sopb_cancel as status,
                               '$dfrom' as dfrom,
                               '$dto' as dto,
                               '$iarea' as iarea,
                               '$icustomer' as icustomer, 
                               '$folder' as folder,
                               '$iuser' as iuser
                            from
                               tm_sopb a,
                               tr_customer b 
                            where
                               a.i_customer = b.i_customer 
                               and a.d_sopb >= to_date('$dfrom', 'dd-mm-yyyy') 
                               and a.d_sopb <= to_date('$dto', 'dd-mm-yyyy') 
                            order by
                               a.i_area,
                               i_customer,
                               a.i_sopb"
                            );
          }
        }else{
          $datatables->query("
                              select
                                a.i_sopb,
                                a.d_sopb,
                                a.i_customer,
                                b.e_customer_name,
                                a.f_sopb_cancel as status,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$iarea' as iarea,
                                '$icustomer' as icustomer, 
                                '$folder' as folder,
                                '$iuser' as iuser
                              from
                                 tm_sopb a,
                                 tr_customer b 
                              where
                                 a.i_customer = '$icustomer' 
                                 and a.i_customer = b.i_customer 
                                 and a.d_sopb >= to_date('$dfrom', 'dd-mm-yyyy') 
                                 and a.d_sopb <= to_date('$dto', 'dd-mm-yyyy') 
                              order by
                                 a.i_area,
                                 i_customer,
                                 a.i_sopb"
                            );
        }

        $datatables->edit('d_sopb', function ($data) {
            $d_sopb = $data['d_sopb'];
            if($d_sopb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_sopb) );
            }
        });

        $datatables->edit('status', function ($data) {
          if ($data['status']=='t') {
              $data = '<span class="label label-success label-rouded">Batal</span>';
          }else{
              $data = '<span class="label label-danger label-rouded">Tidak</span>';
          }
          return $data;
        });

        $datatables->edit('i_customer', function ($data) {
          return '('.$data['i_customer'].')'.' - '.$data['e_customer_name'];
        });

        $datatables->add('action', function ($data) {
          $isopb              = trim($data['i_sopb']);
          $icust              = trim($data['i_customer']);
          $status             = trim($data['status']);
          $dfrom              = trim($data['dfrom']);
          $dto                = trim($data['dto']);
          $folder             = trim($data['folder']);
          $data               = '';
          $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isopb/$icust/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
          if($status != 't'){
                  $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isopb\",\"$icust\"); return false;'><i class='fa fa-trash'></i></a>";
          }

          return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('e_customer_name');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('icustomer');
        $datatables->hide('iuser');
        return $datatables->generate();  
      }

      public function delete($isopb, $icustomer) {
        $this->db->query("
                        update
                           tm_sopb 
                        set
                           f_sopb_cancel = 't' 
                        WHERE
                           i_sopb = '$isopb' 
                           and i_customer = '$icustomer'
                        ");
        return TRUE;
      }

      public function baca($isopb,$icustomer){
        return $this->db->query("
                                select
                                   a.i_sopb,
                                   to_char(a.d_sopb, 'dd-mm-yyyy') as d_sopb,
                                   a.i_spg,
                                   a.i_customer,
                                   a.i_area,
                                   b.e_customer_name,
                                   c.e_spg_name 
                                from
                                   tm_sopb a,
                                   tr_customer b,
                                   tr_spg c 
                                where
                                   a.i_customer = b.i_customer 
                                   and a.i_spg = c.i_spg 
                                   and a.i_sopb = '$isopb' 
                                   and a.i_customer = '$icustomer'
                                ",false);
      }

      public function bacadetail($isopb,$icustomer){
        return $this->db->query("
                                select
                                   * 
                                from
                                   tm_sopb_item 
                                   inner join
                                      tr_product_motif 
                                      on (tm_sopb_item.i_product = tr_product_motif.i_product 
                                      and tm_sopb_item.i_product_motif = tr_product_motif.i_product_motif) 
                                where
                                   tm_sopb_item.i_sopb = '$isopb' 
                                   and tm_sopb_item.i_customer = '$icustomer' 
                                order by
                                   tm_sopb_item.n_item_no
                                ",false);
      }

      public function insertdetail( $iproduct, $iproductgrade, $eproductname, $nstockopname,$istockopname, $icustomer, $iproductmotif,$dstockopname,$iarea,$i){
        $pr='20'.substr($istockopname,3,4);
    	  $this->db->set(
    	  	array(
    	  	'i_sopb'			        => $istockopname,
			  	'd_sopb'			        => $dstockopname,
			  	'i_customer'				  => $icustomer,
			  	'i_product' 			    => $iproduct,
			  	'i_product_grade'		  => $iproductgrade,
			  	'e_product_name'		  => $eproductname,
			  	'i_product_motif'		  => $iproductmotif,
			  	'n_sopb'         		  => $nstockopname,
			  	'i_area'				      => $iarea,
          'e_mutasi_periode'    => $pr,
          'n_item_no'           => $i
    	  	)
    	  );
    	
    	  $this->db->insert('tm_sopb_item');
      }

      public function updateheader($istockopname, $dstockopname, $icustomer){
        $query 	= $this->db->query("SELECT to_char(current_timestamp,'yyyy-mm-dd') as c");
		    $row   	= $query->row();
		    $dentry	= $row->c;
    	  $data = array(
                 	'i_sopb' 			 => $istockopname,
                 	'd_sopb' 			 => $dstockopname,
			         		'i_customer' 	 => $icustomer,
			  					'f_sopb_cancel'=> 'f',
                  'd_update'     => $dentry
              );
		    $this->db->where('i_sopb', $istockopname);
		    $this->db->where('i_customer', $icustomer);
		    $this->db->update('tm_sopb', $data); 
        $emutasiperiode='20'.substr($istockopname,3,4);
        $bldpn=substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
          $perdpn=substr($emutasiperiode,0,4)+1;
          $perdpn=$perdpn.'01';
        }else{
          $perdpn=substr($emutasiperiode,0,4);
          $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
      
        $query=$this->db->query(" select * from tm_mutasi_headerconsigment
                                  where i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                                ",false);
        if($query->num_rows()>0){
          $query=$this->db->query("   
                                  UPDATE tm_mutasi_headerconsigment
                                  set i_stockopname_akhir='$istockopname'
                                  where i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                                ",false);
        }else{
          $query=$this->db->query("   
                                  insert into tm_mutasi_headerconsigment values
                                  ('$icustomer','$emutasiperiode',null,'$istockopname')
                                  ",false);
        }
        $query=$this->db->query(" select * from tm_mutasi_headerconsigment
                                  where i_customer='$icustomer' and e_mutasi_periode='$perdpn'
                                ",false);
        if($query->num_rows()>0){
          $query=$this->db->query("   
                                  UPDATE tm_mutasi_headerconsigment
                                  set i_stockopname_awal='$istockopname'
                                  where i_customer='$icustomer' and e_mutasi_periode='$perdpn'
                                ",false);
        }else{
          $query=$this->db->query("   
                                  insert into tm_mutasi_headerconsigment values
                                  ('$icustomer','$perdpn','$istockopname',null)
                                  ",false);
        }
      }

      public function deletedetail( $iproduct, $iproductgrade, $istockopname, $icustomer, $iproductmotif){
		    $this->db->query("	DELETE FROM tm_sopb_item WHERE i_sopb='$istockopname'
		  					            and i_product_motif='$iproductmotif'
		  					            and i_product='$iproduct' 
		  					            and i_product_grade='$iproductgrade'
		  					            and i_customer='$icustomer'");
  	  	return TRUE;
      }

      public function qic($iproduct,$iproductgrade,$iproductmotif,$icustomer){
        $query=$this->db->query(" SELECT n_quantity_stock from tm_ic_consigment
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_customer='$icustomer'
                                ",false);
        if ($query->num_rows() > 0){
			  	return $query->result();
			  }
      }
    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function inserttrans4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      if($qdo>$q_ak){
        $qtmp=$qdo-$q_ak;
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
                                    '$eproductname', '$ido', '$now', $q_in+$qtmp, $q_out, $q_ak+$qtmp, $q_aw
                                  )
                                ",false);
      }elseif($qdo<$q_ak){
        $qtmp=$q_ak-$qdo;
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
                                    '$eproductname', '$ido', '$now', $q_in, $q_out+$qtmp, $q_ak-$qtmp, $q_aw
                                  )
                                ",false);
      }else{
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
                                    '$eproductname', '$ido', '$now', $q_in, $q_out, $q_ak, $q_aw
                                  )
                                ",false);
      }
    }
    function updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qdo,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi_consigment 
                                set n_saldo_stockopname=$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$emutasiperiode'
                              ",false);
      $bldpn=substr($emutasiperiode,4,2)+1;
      if($bldpn==13)
      {
        $perdpn=substr($emutasiperiode,0,4)+1;
        $perdpn=$perdpn.'01';
      }else{
        $perdpn=substr($emutasiperiode,0,4);
        $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
      }
      $query=$this->db->query(" select * from tm_mutasi_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$perdpn'
                              ",false);
  		  if($query->num_rows() > 0){
            $query=$this->db->query(" 
                                UPDATE tm_mutasi_consigment
                                set n_saldo_awal=$qdo, n_saldo_akhir=($qdo+n_mutasi_daripusat+n_mutasi_darilang)-
                                (n_mutasi_penjualan+n_mutasi_kepusat)
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$perdpn'
                              ",false);
		  }elseif($query->num_rows()== 0){
               $query=$this->db->query(" 
                                insert into tm_mutasi_consigment
                                (
                                  i_product, i_product_motif, i_product_grade, i_customer, e_mutasi_periode, n_saldo_awal,
                                  n_mutasi_daripusat, n_mutasi_darilang, n_mutasi_penjualan, n_mutasi_kepusat, n_saldo_akhir,
                                  n_saldo_stockopname, n_mutasi_git, f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$icustomer',
                                  '$perdpn',$qdo,0,0,0,0,$qdo,0,0,'f')
                              ",false);
		  }
      
    }
    function insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qdo,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi_consigment
                                (
                                  i_product, i_product_motif, i_product_grade, i_customer, e_mutasi_periode, n_saldo_awal,
                                  n_mutasi_daripusat, n_mutasi_darilang, n_mutasi_penjualan, n_mutasi_kepusat, n_saldo_akhir,
                                  n_saldo_stockopname, n_mutasi_git, f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$icustomer',
                                  '$emutasiperiode',0,0,0,0,0,0,$qdo,0,'f')
                              ",false);
      $bldpn=substr($emutasiperiode,4,2)+1;
      if($bldpn==13)
      {
        $perdpn=substr($emutasiperiode,0,4)+1;
        $perdpn=$perdpn.'01';
      }else{
        $perdpn=substr($emutasiperiode,0,4);
        $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
      }
      
      $query=$this->db->query(" select * from tm_mutasi_consigment
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$perdpn'
                              ",false);
  		  if($query->num_rows() > 0){
            $query=$this->db->query(" 
                                UPDATE tm_mutasi_consigment
                                set n_saldo_awal=$qdo, n_saldo_akhir=($qdo+n_mutasi_daripusat+n_mutasi_darilang)-
                                (n_mutasi_penjualan+n_mutasi_kepusat)
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer' and e_mutasi_periode='$perdpn'
                              ",false);
		  }elseif($query->num_rows()== 0){
      $query=$this->db->query(" 
                                insert into tm_mutasi_consigment
                                (
                                  i_product, i_product_motif, i_product_grade, i_customer, e_mutasi_periode, n_saldo_awal,
                                  n_mutasi_daripusat, n_mutasi_darilang, n_mutasi_penjualan, n_mutasi_kepusat, n_saldo_akhir,
                                  n_saldo_stockopname, n_mutasi_git, f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$icustomer','$perdpn',
                                  $qdo,0,0,0,0,$qdo,0,0,'f')
                              ",false);
      }
    }
    function updateic4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$qdo,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic_consigment set n_quantity_stock=$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_customer='$icustomer'
                              ",false);
    }
    function insertic4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$qdo)
    {
      $query=$this->db->query(" 
                                insert into tm_ic_consigment
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$icustomer', '$eproductname',$qdo, 't'
                                )
                              ",false);
    }
    function jumlahitem($icustomer,$iperiode)
    {
		  $this->db->select("a.i_product from tm_ic_consigment a, tr_product b, tr_product_motif c
						             where a.i_product=b.i_product and a.i_product=c.i_product and a.i_product_motif=c.i_product_motif
							           and a.i_customer='$icustomer' ", false);
		  $query = $this->db->get();
      return $query->num_rows();
    }
    function bacamutasi($icustomer,$iperiode)
    {
		  $this->db->select("a.*, b.e_product_name, c.e_product_motifname from tm_ic_consigment a, tr_product b, tr_product_motif c
						             where a.i_product=b.i_product and a.i_product=c.i_product and a.i_product_motif=c.i_product_motif
							           and a.i_customer='$icustomer' order by a.i_product", false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
        return $query->result();
		  }
    }
}

/* End of file Mmaster.php */
