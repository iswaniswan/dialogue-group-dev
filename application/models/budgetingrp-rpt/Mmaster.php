<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

   public $idcompany;

   function __construct(){
       parent::__construct();
       $this->idcompany = $this->session->id_company;
   }

   public function bacakategori(){
      return $this->db->query("
                              SELECT
                                 *
                              FROM
                                 tr_kelompok_barang
                              WHERE
                                 id_company = '$this->idcompany'
                              ORDER BY 
                                 i_kode_kelompok
                              ",FALSE)->result();
   }

   public function getnamekategori($ikategori){
      return $this->db->query("
                              SELECT
                                 e_nama_kelompok
                              FROM
                                 tr_kelompok_barang
                              WHERE
                                 i_kode_kelompok = '$ikategori'
                                 AND id_company = '$this->idcompany'
                              ", FALSE);
   }

   public function getjenisbarang($ikategori){
      return $this->db->query("
                              SELECT
                                 *
                              FROM
                                 tr_item_type
                              WHERE
                                 i_kode_kelompok = '$ikategori'
                                 and id_company = '$this->idcompany'
                              ORDER BY
                                 i_type_code
                              ",FALSE);
   }

   public function getnamejenis($ijenis){
      return $this->db->query("
                              SELECT
                                 e_type_name 
                              FROM
                                 tr_item_type
                              WHERE
                                 i_type_code = '$ijenis'
                                 and id_company = '$this->idcompany'
                              ", FALSE);
   }

	public function bacaperiodeold($dfrom,$dto,$ikategori,$ijenis,$interval){
      $where = '';
      if($ikategori=='ALL' && $ijenis=='ALL'){
         $where.='';
      }else if($ikategori!='ALL' && $ijenis=='ALL'){
         $where.="AND c.i_kode_kelompok=''$ikategori''";
      }else if($ikategori != 'ALL' && $ijenis != 'ALL'){
         $where.="AND c.i_kode_kelompok=''$ikategori''
                  AND c.i_type_code=''$ijenis''";
      }

        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];				
            $bl=$tmp[1];
            $dt=$tmp[0];
            $tgl=$th.'-'.$bl.'-'.$dt;
            $periode = $th.$bl;
        }

        if($dto!=''){
            $tmp2=explode("-",$dto);
            $th2=$tmp2[2];        
            $bl2=$tmp2[1];
            $dt2=$tmp2[0];
            $periode2 = $th2.$bl2;
        }
  
        return $this->db->query("
           SELECT  ROW_NUMBER() OVER  (ORDER BY datas[2]) as nomor, 
           datas[1] as e_nama_group_barang , datas[2] as e_nama_kelompok, jan::numeric, feb::numeric, mar::numeric, apr::numeric, may::numeric, 
           jun::numeric, jul::numeric, aug::numeric, sep::numeric, oct::numeric, nov::numeric, des::numeric from CROSSTAB (
                $$ select 
                     Array[e.e_nama_group_barang::text, d.e_nama_kelompok::text] as datas,
                     to_number(to_char(b.d_document, 'mm'), '99') AS bln,
                     coalesce(sum(a.n_budgeting * v_price_adj),0) as total
                from tm_budgeting_item_material a
                inner join tm_budgeting b on (a.id_document = b.id)
                inner join tr_material c on (a.id_material = c.id)
                inner join tr_kelompok_barang d on (c.i_kode_kelompok = d.i_kode_kelompok and c.id_company = d.id_company)
                inner join tr_group_barang e on (c.i_kode_group_barang = e.i_kode_group_barang and c.id_company = e.id_company)
                where b.id_company = '$this->company' and to_char(b.d_document, 'yyyymm') between '$periode' and '$periode2' $where
                group by 1,2 $$,
                $$ SELECT (
                     select EXTRACT(MONTH from date_trunc('month', '$dfrom'::date)::date + s.a * '1 month'::interval)
                ) from generate_series(0, 11) as s(a)$$
           ) as (
             datas text[], jan text, feb text, mar text, apr text, may text, 
             jun text, jul text, aug text, sep text, oct text, nov text, des text )
           order by 3 desc nulls last, 4 desc nulls last, 5 desc nulls last, 6 desc nulls last, 7 desc nulls last, 
           8 desc nulls last, 9 desc nulls last, 10 desc nulls last, 11 desc nulls last, 12 desc nulls last
        ", FALSE);
    }

    public function interval($dfrom,$dto){
        if($dfrom!=''){
	    			$tmp=explode("-",$dfrom);
	    			$th=$tmp[2];
	    			$bl=$tmp[1];
	    			$hr=$tmp[0];
	    			$dfrom=$th."-".$bl."-".$hr;
	    }
        if($dto!=''){
	    			$tmp=explode("-",$dto);
	    			$th=$tmp[2];
	    			$bl=$tmp[1];
	    			$hr=$tmp[0];
	    			$dto=$th."-".$bl."-".$hr;
	    }
	    $this->db->select("(DATE_PART('year', '$dto'::date) - DATE_PART('year', '$dfrom'::date)) * 12 +
                           (DATE_PART('month', '$dto'::date) - DATE_PART('month', '$dfrom'::date)) as inter ",false);
        return  $this->db->get();
    }

    public function bacaperiode($dfrom,$dto,$ikategori,$ijenis,$interval){
      $where = '';
      if($ikategori=='ALL' && $ijenis=='ALL'){
         $where.='';
      }else if($ikategori!='ALL' && $ijenis=='ALL'){
         $where.="AND c.i_kode_kelompok=''$ikategori''";
      }else if($ikategori != 'ALL' && $ijenis != 'ALL'){
         $where.="AND c.i_kode_kelompok=''$ikategori''
                  AND c.i_type_code=''$ijenis''";
      }

        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];        
            $bl=$tmp[1];
            $dt=$tmp[0];
            $tgl=$th.'-'.$bl.'-'.$dt;
            $periode = $th.$bl;
        }
  
         if($dto!=''){
            $tmp2=explode("-",$dto);
            $th2=$tmp2[2];        
            $bl2=$tmp2[1];
            $dt2=$tmp2[0];
            $periode2 = $th2.$bl2;
        }
  
        return $this->db->query("
           SELECT  ROW_NUMBER() OVER  (ORDER BY datas[2]) as nomor, 
           datas[1] as e_nama_group_barang , datas[2] as e_nama_kelompok, coalesce(jan::numeric,0) as jan, coalesce(feb::numeric,0) as feb, coalesce(mar::numeric,0) as mar, coalesce(apr::numeric,0) as apr, coalesce(may::numeric,0) as may, coalesce(jun::numeric,0) as jun, coalesce(jul::numeric,0) as jul, coalesce(aug::numeric,0) as aug, coalesce(sep::numeric,0) as sep, coalesce(oct::numeric,0) as oct, coalesce(nov::numeric,0) as nov, coalesce(des::numeric,0) as des  from CROSSTAB (
                $$ select 
                     Array[e.e_nama_group_barang::text, d.e_nama_kelompok::text] as datas,
                     to_number(to_char(b.d_document, 'mm'), '99') AS bln,
                     coalesce(sum(a.n_budgeting * v_price_adj),0) as total
                from tm_budgeting_item_material a
                inner join tm_budgeting b on (a.id_document = b.id)
                inner join tr_material c on (a.id_material = c.id)
                inner join tr_kelompok_barang d on (c.i_kode_kelompok = d.i_kode_kelompok and c.id_company = d.id_company)
                inner join tr_group_barang e on (c.i_kode_group_barang = e.i_kode_group_barang and c.id_company = e.id_company)
                where b.id_company = '$this->company' and to_char(b.d_document, 'yyyymm') between '$periode' and '$periode2' $where
                group by 1,2 $$,
                $$ SELECT (
                     select EXTRACT(MONTH from date_trunc('month', '$dfrom'::date)::date + s.a * '1 month'::interval)
                ) from generate_series(0, 11) as s(a)$$
           ) as (
             datas text[], jan text, feb text, mar text, apr text, may text, 
             jun text, jul text, aug text, sep text, oct text, nov text, des text )
           order by 2 desc, 3 asc
        ", FALSE);
    }
}

/* End of file Mmaster.php */