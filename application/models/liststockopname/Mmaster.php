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

    public function data($dfrom,$dto,$iarea,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                          select
                            a.i_stockopname,
                            a.d_stockopname,
                            a.i_store,
                            a.i_store_location,
                            a.f_stockopname_cancel,
                            '$dfrom' as dfrom,
                            '$dto' as dto,
                            '$iarea' as iarea,
                            '$folder' as folder
                          from
                            tm_stockopname a,
                            tr_area b 
                          where
                            a.i_store = b.i_store 
                            and a.i_area = b.i_area 
                            and a.i_area = '$iarea' 
                            and a.d_stockopname >= to_date('$dfrom', 'dd-mm-yyyy') 
                            and a.d_stockopname <= to_date('$dto', 'dd-mm-yyyy') 
                          order by
                            a.i_stockopname desc"
                          );

        $datatables->edit('d_stockopname', function ($data) {
            $d_stockopname = $data['d_stockopname'];
            if($d_stockopname == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_stockopname) );
            }
        });

        $datatables->edit('i_stockopname', function ($data) {
          $i_stockopname        = "<h1>".$data['i_stockopname']."</h1>";
          $f_stockopname_cancel = $data['f_stockopname_cancel'];
          if($f_stockopname_cancel == 't'){
              return $i_stockopname;
          }else{
              return $data['i_stockopname'];
          }
        });
        
        $datatables->add('action', function ($data) {
          $istockopname       = trim($data['i_stockopname']);
          $istore             = trim($data['i_store']);
          $istorelocation     = trim($data['i_store_location']);
          $fstockopnamecancel = trim($data['f_stockopname_cancel']);
          $dfrom              = trim($data['dfrom']);
          $dto                = trim($data['dto']);
          $iarea              = trim($data['iarea']); 
          $folder             = trim($data['folder']);
          $data               = '';
          $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$istockopname/$istore/$istorelocation/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
          if($fstockopnamecancel != 't'){
                  $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$istockopname\",\"$istore\",\"$istorelocation\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
          }

          return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('f_stockopname_cancel');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();  
      }

      public function delete($istockopname, $istore) {
        $this->db->query("
                        update 
                          tm_stockopname 
                        set 
                          f_stockopname_cancel='t' 
                        WHERE 
                          i_stockopname='$istockopname' 
                          and i_store='$istore'
                        ");
        return TRUE;
      }

      public function bisaedit($istockopname,$istore,$istorelocation){
        $bisaedit = true;
        $sql = "e_mutasi_periode from tm_mutasi_header where i_stockopname_awal='$istockopname' and i_store='$istore'";
        $this->db->select($sql, false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $sql=" i_product from tm_mutasi
                   where e_mutasi_periode='$row->e_mutasi_periode' and i_store='$istore' and 
                   i_store_location='$istorelocation' and (n_mutasi_pembelian>0 or n_mutasi_returoutlet>0 or
                   n_mutasi_bbm>0 or n_mutasi_penjualan>0 or n_mutasi_returpabrik>0 or n_mutasi_bbk>0)";
            $this->db->select($sql, false);
            if ($query->num_rows()>0){
              $bisaedit=false;
            }
          }
        }
        return $bisaedit;
      }

      public function baca($istockopname,$istore,$istorelocation){
        return $this->db->query("
                                  select
                                    a.i_stockopname,
                                    to_char(a.d_stockopname, 'dd-mm-yyyy') as d_stockopname,
                                    a.i_store,
                                    a.i_store_location,
                                    a.i_area,
                                    b.e_store_name,
                                    c.e_store_locationname 
                                  from
                                    tm_stockopname a,
                                    tr_store b,
                                    tr_store_location c 
                                  where
                                    a.i_store = b.i_store 
                                    and a.i_store_location = c.i_store_location 
                                    and b.i_store = c.i_store 
                                    and a.i_stockopname = '$istockopname' 
                                    and a.i_store = '$istore' 
                                    and a.i_store_location = '$istorelocation'
                                  ",false);
      }

      public function bacadetail($istockopname,$istore,$istorelocation){
        return $this->db->query("
                                select
                                  * 
                                from
                                  tm_stockopname_item 
                                  inner join
                                     tr_product_motif 
                                     on (tm_stockopname_item.i_product = tr_product_motif.i_product 
                                     and tm_stockopname_item.i_product_motif = tr_product_motif.i_product_motif) 
                                where
                                  tm_stockopname_item.i_stockopname = '$istockopname' 
                                  and tm_stockopname_item.i_store = '$istore' 
                                  and tm_stockopname_item.i_store_location = '$istorelocation' 
                                order by
                                  tm_stockopname_item.i_product
                                ",false);
      }

      public function getproduct($cari,$istore,$istorelocation){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                select
                                  a.*,
                                  b.e_product_motifname 
                                from
                                  tm_ic a,
                                  tr_product_motif b 
                                where
                                  (
                                     upper(a.i_product) like '%$cari%' 
                                     or upper(a.e_product_name) like '%$cari%'
                                  )
                                  and a.i_store = '$istore' 
                                  and a.i_store_location = '$istorelocation' 
                                  and a.i_product_motif = b.i_product_motif 
                                  and a.i_product = b.i_product 
                                order by
                                  a.i_product,
                                  a.i_product_grade", 
        					              FALSE);
    }

    public function getdetailproduct($iproduct){
        return $this->db->query("
                              select
                                a.i_product,
                                a.i_product_motif,
                                a.i_product_grade,
                                a.e_product_name,
                                b.e_product_motifname 
                              from
                                tm_ic a,
                                tr_product_motif b 
                              where
                                a.i_product = '$iproduct'
                                and a.i_product_motif = b.i_product_motif 
                                and a.i_product = b.i_product 
                              order by
                                a.i_product,
                                a.i_product_grade", 
       						          FALSE);
  }
  
  function updateheader($istockopname, $dstockopname, $istore, $istorelocation){
    $query 	= $this->db->query("SELECT to_char(current_timestamp,'yyyy-mm-dd hh:mi:ss') as c");
    $row   	= $query->row();
    $dentry	= $row->c;
    $data = array(
               'i_stockopname' 			=> $istockopname,
               'd_stockopname' 			=> $dstockopname,
               'i_store' 						=> $istore,
               'i_store_location'		=> $istorelocation,
              'f_stockopname_cancel'=> 'f',
              'd_update'            => $dentry
          );
    $this->db->where('i_stockopname', $istockopname);
    $this->db->where('i_store', $istore);
    $this->db->where('i_store_location', $istorelocation);
    $this->db->update('tm_stockopname', $data); 
### update ke mutasi header
    $emutasiperiode='20'.substr($istockopname,3,4);
    $bldpn=substr($emutasiperiode,4,2)+1;
    if($bldpn==13){
      $perdpn=substr($emutasiperiode,0,4)+1;
      $perdpn=$perdpn.'01';
    }else{
      $perdpn=substr($emutasiperiode,0,4);
      $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
    }
  
    $query=$this->db->query(" select * from tm_mutasi_header
                              where i_store='$istore' and e_mutasi_periode='$emutasiperiode'
                            ",false);
    if($query->num_rows()>0){
      $query=$this->db->query("   
                              UPDATE tm_mutasi_header
                              set i_stockopname_akhir='$istockopname'
                              where i_store='$istore' and e_mutasi_periode='$emutasiperiode'
                            ",false);
    }else{
      $query=$this->db->query("   
                              insert into tm_mutasi_header values
                              ('$istore','$emutasiperiode',null,'$istockopname')
                              ",false);
    }
    $query=$this->db->query(" select * from tm_mutasi_header
                              where i_store='$istore' and e_mutasi_periode='$perdpn'
                            ",false);
    if($query->num_rows()>0){
      $query=$this->db->query("   
                              UPDATE tm_mutasi_header
                              set i_stockopname_awal='$istockopname'
                              where i_store='$istore' and e_mutasi_periode='$perdpn'
                            ",false);
    }else{
      $query=$this->db->query("   
                              insert into tm_mutasi_header values
                              ('$istore','$perdpn','$istockopname',null)
                              ",false);
    }
  }

  public function deletedetail( $iproduct, $iproductgrade, $istockopname, $istore, $istorelocation, $istorelocationbin, $iproductmotif){
		  $this->db->query("	DELETE FROM tm_stockopname_item WHERE i_stockopname='$istockopname'
							            and i_product_motif='$iproductmotif'
							            and i_product='$iproduct' 
							            and i_product_grade='$iproductgrade'
							            and i_store='$istore'
							            and i_store_location='$istorelocation'
							            and i_store_locationbin='$istorelocationbin'");
  		return TRUE;
  }

  public function insertdetail(	$iproduct, $iproductgrade, $eproductname, $nstockopname, $istockopname, $istore, $istorelocation, $istorelocationbin, $iproductmotif, $dstockopname, $iarea, $i){
      $pr='20'.substr($istockopname,3,4);
    	$this->db->set(
    		array(
    		'i_stockopname'			=> $istockopname,
				'd_stockopname'			  => $dstockopname,
				'i_store'				      => $istore,
				'i_store_location'		=> $istorelocation,
				'i_store_locationbin'	=> $istorelocationbin,
				'i_product' 			    => $iproduct,
				'i_product_grade'		  => $iproductgrade,
				'e_product_name'		  => $eproductname,
				'i_product_motif'		  => $iproductmotif,
				'n_stockopname' 		  => $nstockopname,
				'i_area'				      => $iarea,
        'e_mutasi_periode'    => $pr,
        'n_item_no'           => $i
    		));
    	
    	$this->db->insert('tm_stockopname_item');
  }

  public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
  }

  public function updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_saldo_stockopname=$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
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
      $que=$this->db->query(" Select * from tm_mutasi 
                              where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                              and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                            ",false);
      if($que->num_rows()>1){
        foreach($que->result() as $row){
          $gitasal=$row->n_mutasi_git;
          $gitpenjualanasal=$row->n_git_penjualan;
        }
      }else{
        $gitasal=0;
        $gitpenjualanasal=0;
      } 
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_saldo_awal=$qdo, n_saldo_akhir=($qdo+$gitasal+$gitpenjualanasal+n_mutasi_pembelian+n_mutasi_returoutlet
                                +n_mutasi_bbm)-(n_mutasi_penjualan+n_mutasi_returpabrik+n_mutasi_bbk),n_mutasi_gitasal=$gitasal,
                                n_git_penjualanasal=$gitpenjualanasal
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$perdpn'
                              ",false);
    }

    public function insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation',
                                  '$istorelocationbin','$emutasiperiode',0,0,0,0,0,0,0,0,$qdo,'f')
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
      $que=$this->db->query(" Select * from tm_mutasi 
                              where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                              and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              and e_mutasi_periode='$emutasiperiode'
                            ",false);
      if($que->num_rows()>1){
        foreach($que->result() as $row){
          $gitasal=$row->n_mutasi_git;
          $gitpenjualanasal=$row->n_git_penjualan;
        }
      }else{
        $gitasal=0;
        $gitpenjualanasal=0;
      }
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close,n_mutasi_git,
                                  n_mutasi_pesan,n_mutasi_ketoko,n_mutasi_daritoko,n_git_penjualan,n_mutasi_gitasal,n_git_penjualanasal
                                )
                                  values
                                  ('$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation',
                                    '$istorelocationbin','$perdpn',$qdo,0,0,0,0,0,0,$qdo+$gitasal+$gitpenjualanasal,0,'f',0,0,0,0,0,
                                    $gitasal,$gitpenjualanasal)
                              ",false);
    }
}

/* End of file Mmaster.php */
