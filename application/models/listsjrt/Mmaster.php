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

    public function data($dfrom,$dto,$iarea,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        select
                          a.i_sjr,
                          a.d_sjr,
                          a.i_area,
                          b.e_area_name,
                          a.i_ttb,
                          a.d_sjr_receive as terima,
                          a.f_sjr_cancel,
                          '$dfrom' as dfrom,
                          '$dto' as dto,
                          '$iarea' as iarea,
                          '$folder' as folder 
                        from
                          tm_sjrt a,
                          tr_area b 
                        where
                          a.i_area = b.i_area 
                          and a.i_area = '09' 
                          and a.d_sjr >= to_date('$dfrom', 'dd-mm-yyyy') 
                          and a.d_sjr <= to_date('$dto', 'dd-mm-yyyy') 
                        ORDER BY
                          a.i_sjr desc"
                        );
        
        $datatables->add('action', function ($data) {
            $isjr           = trim($data['i_sjr']);
            $dsjr           = trim($data['d_sjr']);
            $ittb           = trim($data['i_ttb']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $fsjrcancel     = trim($data['f_sjr_cancel']);
            $iarea          = trim($data['i_area']);
            $folder         = trim($data['folder']);
            $data           = '';
            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjr/$iarea/$dfrom/$dto/$ittb\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if($fsjrcancel == 'f'){
                    $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isjr\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
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

        $datatables->edit('i_sjr', function ($data) {
          $i_sjr = "<h2><b>".$data['i_sjr']."</b></h2>";
          $fsjrcancel = $data['f_sjr_cancel'];
          if($fsjrcancel == 't'){
              return $i_sjr;
          }else{
              return $data['i_sjr'];
          }
        });

        $datatables->edit('terima', function ($data) {
          if ($data['terima']!=null) {
              $data = '<span class="label label-info label-rouded">Sudah</span>';
          }else{
              $data = '<span class="label label-danger label-rouded">Belum</span>';
          }
          return $data;
      });

        $datatables->hide('folder');
        $datatables->hide('f_sjr_cancel');
        $datatables->hide('i_area');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();  
    }

    function baca($isjrt,$iarea){
        $query = $this->db->query(" 
                              select
                                a.*, 
                                b.e_area_name 
                              from 
                                tm_sjrt a, 
                                tr_area b
                              where 
                                a.i_area=b.i_area
                                and a.i_sjr ='$isjrt' 
                                and a.i_area='$iarea'"
                                , false);
		  if ($query->num_rows() > 0){
		  	return $query->row();
		  }
    }

    function bacadetail($isjrt,$iarea,$ittb){
        $query = $this->db->query(" 
                                select
                                  a.i_sjr,
                                  a.d_sjr,
                                  a.i_area,
                                  a.i_product,
                                  a.i_product_grade,
                                  a.i_product_motif,
                                  a.n_quantity_deliver,
                                  a.v_unit_price,
                                  a.e_product_name,
                                  a.e_remark,
                                  b.e_product_motifname 
                                from 
                                  tm_sjrt_item a, 
                                  tr_product_motif b
                                where 
                                  a.i_sjr = '$isjrt' 
                                  and a.i_area='$iarea' 
                                  and a.i_product=b.i_product 
                                  and a.i_product_motif=b.i_product_motif
                                order by 
                                a.n_item_no"
                                , false);
		  if ($query->num_rows() > 0){
		  	return $query->result();
		  }
    }

    public function bacadetailspb($isjrt,$ittb,$iarea){
      return $this->db->query("
                            select
                              a.*, 
                              b.e_product_motifname 
                            from 
                              tm_ttbretur_item a, 
                              tr_product_motif b, 
                              tm_ttbretur c
                            where 
                              a.i_ttb = '$ittb' 
                              and a.i_area='$iarea' 
                              and a.i_product1 not in (select i_product from tm_sjrt_item 
                              where 
                                i_sjr='$isjrt' 
                                and i_area='$iarea')
                              and a.i_ttb=c.i_ttb 
                              and a.i_area=c.i_area
                              and a.i_product1=b.i_product 
                              and a.i_product1_motif=b.i_product_motif
                            order by 
                            a.n_item_no"
                            ,false);
    }

    function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,
			                      $vunitprice,$ittb,$dttb,$isjr,$dsjr,$iarea,$eremark,$i){
      $th=substr($dsjr,0,4);
      $bl=substr($dsjr,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				      'i_sjr'			          => $isjr,
				      'd_sjr'			          => $dsjr,
				      'i_area'		          => $iarea,
				      'i_product'       		=> $iproduct,
				      'i_product_motif'   	=> $iproductmotif,
				      'i_product_grade'   	=> $iproductgrade,
				      'e_product_name'    	=> $eproductname,
				      'n_quantity_deliver'	=> $ndeliver,
				      'v_unit_price'		    => $vunitprice,
              'e_remark'            => $eremark,
              'n_item_no'           => $i
    		    ));
    	
    	$this->db->insert('tm_sjrt_item');
    }

    function updatesjheader($isjr,$iarea,$dsjr,$vsjnetto){
      $query 		  = $this->db->query("SELECT current_timestamp as c");
		  $row   		  = $query->row();
		  $dsjupdate  = $row->c;
    	$this->db->set(
    		array(
				      'v_sjr'	      => $vsjnetto,
				      'd_sjr'       => $dsjr,
              'd_sjr_update'=> $dsjupdate
    		      ));
    	$this->db->where('i_sjr',$isjr);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjrt');
    }

    public function deletesjdetail($isjr, $iarea, $iproduct, $iproductgrade, $iproductmotif){
		  $this->db->query("DELETE FROM tm_sjrt_item WHERE i_sjr='$isjr'
                        and i_area='$iarea'
										    and i_product='$iproduct' 
                        and i_product_grade='$iproductgrade' 
                        and i_product_motif='$iproductmotif'
                      ");
    }

    public function cancel($isjr, $iarea){
			$this->db->query("update tm_sjrt set f_sjr_cancel='t' WHERE i_sjr='$isjr' and i_area='$iarea'");
    }
}

/* End of file Mmaster.php */
