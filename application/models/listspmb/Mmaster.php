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

    public function bacastore($username,$idcompany){
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
      if($query->num_rows() > 0){
        $ar    = $query->row();
        $area  = $ar->i_area;
      }else{
        $area = '';
      }

      if($area=='00'){
        return $this->db->query("
                                select 
                                  distinct c.i_store,
                                  a.i_store_location,
                                  a.e_store_locationname,
                                  b.e_store_name 
                                from
                                  tr_store_location a,
                                  tr_store b,
                                  tr_area c 
                                where
                                  a.i_store = b.i_store 
                                  and b.i_store = c.i_store 
                                order by
                                  c.i_store
                                ", FALSE)->result();
      }else{
        return $this->db->query("
                                select
                                  distinct c.i_store,
                                  a.i_store_location,
                                  a.e_store_locationname,
                                  b.e_store_name 
                                from
                                  tr_store_location a,
                                  tr_store b,
                                  tr_aread c 
                                where
                                  a.i_store = b.i_store 
                                  and b.i_store = c.i_store 
                                  and c.i_area = '$area'
                                order by
                                  c.i_store
                                ", FALSE)->result();
      }
    }

    public function data($dfrom,$dto,$istore,$folder){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        if($istore == '00'){
          $xistore = 'AA';
        }else{
          $xistore = $istore;
        }
        $datatables->query("
                            SELECT
                                a.i_spmb,
                                a.d_spmb,
                                b.e_area_name,
                                a.f_spmb_consigment AS kons,
                                a.f_spmb_cancel AS status,
                                a.f_spmb_acc,
                                a.i_approve2,
                                a.f_spmb_close,
                                a.i_area,
                                ARRAY_AGG(c.i_spb)::TEXT AS i_spb,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$istore' as istore,
                                '$folder' as folder
                            FROM
                                tm_spmb a
                            INNER JOIN tr_area b ON
                                (a.i_area = b.i_area)
                            LEFT JOIN tm_spb c ON
                                (a.i_spmb = c.i_spmb
                                AND a.i_area = c.i_area)
                            WHERE
                                b.i_store = '$xistore'
                                AND a.d_spmb >= to_date('$dfrom', 'dd-mm-yyyy')
                                AND a.d_spmb <= to_date('$dto', 'dd-mm-yyyy')
                            GROUP BY
                                a.i_spmb,
                                a.d_spmb,
                                b.e_area_name,
                                a.f_spmb_consigment,
                                a.f_spmb_cancel,
                                a.f_spmb_acc,
                                a.i_approve2,
                                a.f_spmb_close,
                                a.i_area"
                          );
        
        $datatables->add('action', function ($data) {
            $ispmb          = trim($data['i_spmb']);
            $dspmb         = trim($data['d_spmb']);
            $kons           = trim($data['kons']);
            $fspmbcancel    = trim($data['status']);
            $fspmbacc       = trim($data['f_spmb_acc']);
            $iapprove       = trim($data['i_approve2']);
            $fspmbclose     = trim($data['f_spmb_close']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $folder         = trim($data['folder']);
            $spb            = trim($data['i_spb']);
            $iarea          = trim($data['i_area']);
            $data           = '';

            $tmp = explode('-',$dspmb);
            $tgl = $tmp[2];
            $bln = $tmp[1];
            $thn = $tmp[0];
            $dspmb = $tgl.'-'.$bln.'-'.$thn;

            $thak = substr($thn,2,2);
            $blak = $bln;
            $blaw = intval($bln);
            $thaw = intval($thn);

            for($z=1;$z<=3;$z++){
              $blaw = $blaw-1;
              if($blaw==0){
                $blaw=12;
                $thaw=$thaw-1;
              }
            }
            $thaw = Strval($thaw);
            $thaw = substr($thaw,2,2);
            $blaw = substr($blaw,0,2);
            if(strlen($blaw)==1){
              $blaw = '0'.$blaw;
            }
            $peraw = $thaw.$blaw;
            $perak = $thak.$blak;

            $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ispmb/$iarea/$dfrom/$dto/$peraw/$perak\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if($iapprove == ''  || $iapprove == 'null'){
              if($fspmbcancel == 'f'){
                    $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ispmb\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>";
              }
            }

			return $data;
        });

      $datatables->edit('d_spmb', function ($data) {
          $d_spmb = $data['d_spmb'];
          if($d_spmb == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_spmb) );
          }
      });

      $datatables->edit('i_spb', function ($data) {
        $i_spb = $data['i_spb'];
        if($i_spb == NULL){
            return '';
        }else{
            return $i_spb;
        }
    });

      $datatables->edit('i_spmb', function ($data) {
        $i_spmb = "<h2><b>".$data['i_spmb']."</b></h2>";
        $fspmbcancel = $data['status'];
        if($fspmbcancel == 't'){
            return $i_spmb;
        }else{
            return $data['i_spmb'];
        }
      });

      $datatables->edit('status', function ($data) {
          $fspmbcancel      = $data['status'];
          $fspmbacc         = $data['f_spmb_acc'];
          $fspmbclose       = $data['f_spmb_close'];
          $iapprove         = $data['i_approve2'];

          if($fspmbcancel ==  't'){
              return 'Batal';
          }elseif($fspmbacc == 't'){
              return 'Gudang';
          }elseif(($fspmbacc=='t') && ($iapprove == null)){
            return 'ACC Gudang';
          }elseif(($fspmbacc=='t') && ($iapprove != null)){
            return 'Approved Gudang';
          }elseif($fspmbclose == 't'){
            return 'Close';
          }
      });

      $datatables->edit('kons', function ($data) {
        $fspmbcons      = $data['kons'];

        if($fspmbcons ==  't'){
          return 'Ya';
        }else{
          return 'Tidak';
        }
      });

        $datatables->hide('folder');
        $datatables->hide('i_approve2');
        $datatables->hide('f_spmb_acc');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_spmb_close');
        $datatables->hide('istore');
        $datatables->hide('i_area');

        return $datatables->generate();  
    }

    public function delete($ispmb){
      $this->db->query("
                       UPDATE 
                        tm_spmb
                      SET
                        f_spmb_cancel = 't' 
                      WHERE 
                        i_spmb='$ispmb'
                      ");
      return true;
    }

    public function deleterekap($ispmb){
      $query=$this->db->query("
                              select 
                                i_spb 
                              from 
                                tm_spb 
                              where 
                                i_spmb='$ispmb'
                              ");
      if($query->num_rows()>0){
        foreach($query->result() as $row){
          $spb=$row->i_spb;
        }
        $this->db->update("
                          update 
                            tm_spb 
                          set 
                            i_spmb=null, 
                            f_spb_rekap='f' 
                          where 
                            i_spb='$spb'
                          ");
      }
    
      $que=$this->db->query("
                            select 
                              i_orderpb 
                            from 
                              tm_orderpb 
                            where 
                              i_spmb='$ispmb'
                            ");
      if($que->num_rows()>0){
        foreach($que->result() as $row){
          $orderpb=$row->i_orderpb;
        }
        $this->db->update("
                          update 
                            tm_orderpb 
                          set 
                            i_spmb=null, 
                            f_orderpb_rekap='f'
                          where 
                            i_orderpb='$orderpb'
                          ");
      }
    }

    public function baca($ispmb){
      return $this->db->query("
                              select
                                a.*,
                                b.e_area_name
                              from
                                tm_spmb a,
                                tr_area b
                              where 
                                a.i_area = b.i_area
                                and a.i_spmb ='$ispmb'
                                "
                            ,false);
    }

    public function bacadetail($ispmb){
      return $this->db->query("
                              select
                                a.*,
                                b.e_product_motifname 
                              from
                                tm_spmb_item a,
                                tr_product_motif b 
                              where
                                a.i_spmb = '$ispmb' 
                                and a.i_product = b.i_product 
                                and a.i_product_motif = b.i_product_motif 
                              order by
                                a.i_product asc"
                              ,false);
    }

    public function getproduct($cari){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
                              select
                                a.i_product,
                                c.e_product_name,
                                a.i_product_motif
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
                                a.e_product_motifname asc
                              ", 
      					              FALSE);
    }

    public function getdetailproduct($iproduct){
      return $this->db->query("
                              select
                                a.i_product,
                                a.i_product_motif,
                                a.e_product_motifname,
                                c.e_product_name,
                                c.v_product_mill 
                              from
                                tr_product_motif a,
                                tr_product c 
                              where
                                a.i_product = c.i_product 
                                and a.i_product = '$iproduct'
                              order by
                                a.e_product_motifname asc
                            ",FALSE);
    }

    public function getdetailrata($iproduct,$iproductmotif,$iarea,$fperaw,$fperak){
      return $this->db->query("
                              select 
                                  i_product,
                                  trunc(sum(n_deliver*v_unit_price)/3) as vrata,
                                  trunc(sum(n_deliver)/3) as nrata
                              from 
                                  tm_nota_item
                              where 
                                  i_nota>'$fperaw' 
                                  and i_nota<'$fperak' 
                                  and i_product='$iproduct' 
                                  and i_product_motif='00' 
                                  and i_area='$iarea'
                              group by 
                                  i_product
                              ",FALSE);
    }

    public function updateheader($ispmb, $dspmb, $iarea, $ispmbold, $eremark){
    	$this->db->set(
    		array(
			        'd_spmb'	  => $dspmb,
			        'i_spmb_old'=> $ispmbold,
			        'i_area'	  => $iarea,
              'e_remark'  => $eremark
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
    }

    function insertdetail($ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$nacc,$vunitprice,$iproductmotif,$eremark,$iarea,$i){
    	$this->db->set(
    		array(
					'i_spmb'	   	    => $ispmb,
					'i_product'	 	    => $iproduct,
					'i_product_grade'	=> $iproductgrade,
					'i_product_motif'	=> $iproductmotif,
					'n_order'		      => $norder,
					'n_acc' 		      => $nacc,
					'v_unit_price'		=> $vunitprice,
					'e_product_name'	=> $eproductname,
					'i_area'		      => $iarea,
					'e_remark'		    => $eremark,
          'n_item_no'       => $i
    		)
    	);
    	$this->db->insert('tm_spmb_item');
    }

}

  /* End of file Mmaster.php */
