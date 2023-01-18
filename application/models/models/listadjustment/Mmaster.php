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

    public function data($dfrom,$dto,$iarea,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $thn = substr($dfrom,6,4);
        if($iarea == 'NA'){
          $datatables->query("
                              select
                                c.e_area_name,
                                a.i_adj,
                                a.d_adj,
                                a.e_remark,
                                a.f_adj_cancel as status,
                                a.i_approve,
                                a.i_area,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$iarea' as area,
                                '$folder' as folder  
                              from
                                 tr_area c,
                                 tm_adj a 
                              where
                                 a.i_area = c.i_area 
                                 and a.d_adj >= to_date('$dfrom', 'dd-mm-yyyy') 
                                 and a.d_adj <= to_date('$dto', 'dd-mm-yyyy') 
                              order by
                                 a.d_adj,
                                 a.i_area,
                                 a.i_adj desc"
                              );
        }else{
          $datatables->query("
                            select
                              c.e_area_name,
                              a.i_adj,
                              a.d_adj,
                              a.e_remark,
                              a.f_adj_cancel as status,
                              a.i_approve,
                              a.i_area,
                              '$dfrom' as dfrom,
                              '$dto' as dto,
                              '$iarea' as area,
                              '$folder' as folder   
                            from
                               tr_area c,
                               tm_adj a 
                            where
                               a.i_area = c.i_area 
                               and a.i_area = '$iarea' 
                               and a.d_adj >= to_date('$dfrom', 'dd-mm-yyyy') 
                               and a.d_adj <= to_date('$dto', 'dd-mm-yyyy') 
                            order by
                               a.d_adj,
                               a.i_area,
                               a.i_adj desc"
                            );
        }
        
        
        $datatables->add('action', function ($data) {
            $iadj          = trim($data['i_adj']);
            $iarea         = trim($data['i_area']);
            $dfrom         = trim($data['dfrom']);
            $dto           = trim($data['dto']);
            $folder        = trim($data['folder']);
            $status        = trim($data['status']);
            $iapprove      = trim($data['i_approve']);
            $area          = trim($data['area']);         
            $data          = '';
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iadj/$iarea/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
           
            if($iapprove == '' && $status == 'f'){
              $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iadj\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>";
            }

			return $data;
        });

      $datatables->edit('d_adj', function ($data) {
          $d_adj = $data['d_adj'];
          if($d_adj == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_adj) );
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

        $datatables->hide('folder');
        $datatables->hide('dto');
        $datatables->hide('dfrom');
        $datatables->hide('i_area');
        $datatables->hide('area');
        $datatables->hide('i_approve');

        return $datatables->generate();  
    }

    public function jumlah($iadj,$iarea){
      return $this->db->query("
                              select 
                                i_product 
                              from 
                                tm_adj_item 
                              where 
                                i_adj='$iadj' 
                                and i_area='$iarea'
                              ",false);
    }

    public function delete($iadj,$iarea) {
      $this->db->query("
                        update 
                          tm_adj 
                        set 
                          f_adj_cancel='t' 
                        where 
                          i_adj='$iadj' 
                          and i_area='$iarea'
                        ");
    }

    public function baca($iadj,$iarea){
      return $this->db->query("
                              select
                                 a.*,
                                 b.e_area_name 
                              from
                                 tm_adj a,
                                 tr_area b 
                              where
                                 a.i_area = b.i_area 
                                 and i_adj = '$iadj' 
                                 and a.i_area = '$iarea'
                              "
                              ,false);
    }

    public function bacadetail($iadj,$iarea){
      return $this->db->query("
                              select
                                 a.*,
                                 b.e_product_motifname 
                              from
                                 tm_adj_item a,
                                 tr_product_motif b 
                              where
                                 a.i_adj = '$iadj' 
                                 and i_area = '$iarea' 
                                 and a.i_product = b.i_product 
                                 and a.i_product_motif = b.i_product_motif 
                              order by
                                 a.n_item_no
                              "
                              ,false);
    }

    public function getproduct($store,$loc,$cari){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
                              select
                                 a.i_product,
                                 c.e_product_name,
                                 d.i_product_grade
                              from
                                 tr_product_motif a,
                                 tr_product c,
                                 tm_ic d 
                              where
                                 a.i_product = c.i_product 
                                 and c.i_product = d.i_product 
                                 and d.i_store = '$store' 
                                 and d.i_store_location = '$loc' 
                                 and (upper(a.i_product) like '%$cari%' 
                                 or upper(c.e_product_name) like '%$cari%')
                              order by
                                 c.i_product
                              " 
                              ,FALSE);
    }

    public function getdetailproduct($iproduct,$iproductgrade){
      return $this->db->query("
                              select
                                 a.i_product,
                                 a.i_product_motif,
                                 a.e_product_motifname,
                                 d.i_product_grade,
                                 c.e_product_name,
                                 c.v_product_mill
                              from
                                 tr_product_motif a,
                                 tr_product c,
                                 tm_ic d 
                              where
                                 a.i_product = c.i_product 
                                 and c.i_product = d.i_product 
                                 and d.i_product = '$iproduct'
                                 and d.i_product_grade = '$iproductgrade'
                              order by
                                 c.i_product,
                                 a.e_product_motifname
                            ",
                            FALSE);
    }

    public function getdetailproductgrade($iproduct){
      return $this->db->query("
                              select
                                 d.i_product_grade
                              from
                                 tr_product_motif a,
                                 tr_product c,
                                 tm_ic d 
                              where
                                 a.i_product = c.i_product 
                                 and c.i_product = d.i_product 
                                 and d.i_product = '$iproduct'
                              order by
                                 c.i_product,
                                 a.e_product_motifname
                            ",
                            FALSE);
    }

    public function bacaitem($iadj,$iarea,$iproduct,$iproductgrade,$iproductmotif){
      return $this->db->query("
                                select
                                   * 
                                from
                                   tm_adj_item 
                                where
                                   i_adj = '$iadj' 
                                   and i_area = '$iarea' 
                                   and i_product = '$iproduct' 
                                   and i_product_grade = '$iproductgrade' 
                                   and i_product_motif = '$iproductmotif'
                                ");
    }

    public function updateheader($iadj, $iarea, $dadj, $istockopname, $eremark, $istore, $istorelocation){
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
    	$this->db->set(
    		array(
			    'd_adj'		              => $dadj,
			    'i_stockopname'         => $istockopname,
          'i_store'               => $istore, 
          'i_store_location'      => $istorelocation,			    
          'e_remark'              => $eremark,
          'd_update'              => $now
    		)
    	);
    	$this->db->where('i_adj',$iadj);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_adj');
    }

    public function updatedetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i){
    	$this->db->set(
    		array(
					'n_quantity'		        => $nquantity,
					'e_product_name'	      => $eproductname,
					'e_remark'		          => $eremark,
          'n_item_no'             => $i
    		)
    	);
    	$this->db->where('i_adj',$iadj);
    	$this->db->where('i_area',$iarea);
    	$this->db->where('i_product',$iproduct);
    	$this->db->where('i_product_grade',$iproductgrade);
    	$this->db->where('i_product_motif',$iproductmotif);
    	$this->db->update('tm_adj_item');
    }

    public function insertdetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i){
    	$this->db->set(
    		array(
					'i_adj'	   	            => $iadj,
					'i_area'                => $iarea,
					'i_product'	 	          => $iproduct,
					'i_product_grade'	      => $iproductgrade,
					'i_product_motif'	      => $iproductmotif,
					'n_quantity'		        => $nquantity,
					'e_product_name'	      => $eproductname,
					'e_remark'		          => $eremark,
          'n_item_no'             => $i
    		)
    	);
    	$this->db->insert('tm_adj_item');
    }

    public function deletedetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade){
		  $this->db->query("DELETE FROM tm_adj_item WHERE i_adj='$iadj' and i_area='$iarea' and i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
    }
}

  /* End of file Mmaster.php */
