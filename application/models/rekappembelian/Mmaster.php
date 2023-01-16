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
  
        $sql = "
              SELECT ROW_NUMBER() OVER  (ORDER BY cb.imaterial) as nomor,
                     cb.imaterial,
                     cb.ematerial,
                     cb.kategori,
                     cb.jenis,
                     CASE
                        WHEN
                           (
                              cb.Jan isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Jan 
                     END
                     AS Jan, 
                     CASE
                        WHEN
                           (
                              cb.Feb isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Feb 
                     END
                     AS Feb, 
                     CASE
                        WHEN
                           (
                              cb.Mar isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Mar 
                     END
                     AS Mar, 
                     CASE
                        WHEN
                           (
                              cb.Apr isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Apr 
                     END
                     AS Apr, 
                     CASE
                        WHEN
                           (
                              cb.May isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.May 
                     END
                     AS May, 
                     CASE
                        WHEN
                           (
                              cb.Jun isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Jun 
                     END
                     AS Jun, 
                     CASE
                        WHEN
                           (
                              cb.Jul isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Jul 
                     END
                     AS Jul, 
                     CASE
                        WHEN
                           (
                              cb.Aug isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Aug 
                     END
                     AS Aug, 
                     CASE
                        WHEN
                           (
                              cb.Sep isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Sep 
                     END
                     AS Sep, 
                     CASE
                        WHEN
                           (
                              cb.Oct isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Oct 
                     END
                     AS Oct, 
                     CASE
                        WHEN
                           (
                              cb.Nov isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Nov 
                     END
                     AS Nov, 
                     CASE
                        WHEN
                           (
                              cb.Des isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Des 
                     END
                     AS Des 
              FROM
              (
                     SELECT
                        * 
                     FROM
                        CROSSTAB ('
                     
                        SELECT
                           a.imaterial, a.ematerial, a.kategori, a.jenis, a.bln, concat(sum(a.qty_op), '' | '', sum(a.qty_btb), '' | '', sum(a.nilai_op), '' | '', sum(a.nilai_btb)) as qty 
                        FROM
                           (
                              SELECT DISTINCT
                                 a.i_material as imaterial,
                                 c.e_material_name as ematerial,
                                 d.e_nama_kelompok as kategori,
				                     e.e_type_name as jenis,
                                 to_number(to_char(b.d_op, ''mm''), ''99'') AS bln,
                                 0 as qty_btb,
                                 sum(a.n_quantity) as qty_op,
                                 0 as nilai_btb,
                                 sum(a.n_quantity * a.v_price) as nilai_op 
                              FROM
                                 tm_opbb_item a 
                                 LEFT JOIN
                                    tm_opbb b 
                                    ON (a.id_op = b.id AND a.id_company = b.id_company) 
                                 INNER JOIN
                                    tr_material c 
                                    ON (a.i_material = c.i_material and a.id_company = c.id_company) 
                                 INNER JOIN 
                                   tr_kelompok_barang d 
                                   ON (c.i_kode_kelompok = d.i_kode_kelompok and c.id_company = d.id_company)
                                 INNER JOIN 
                                   tr_item_type e 
                                   ON (c.i_type_code = e.i_type_code AND d.i_kode_kelompok = e.i_kode_kelompok and c.id_company = e.id_company and d.id_company = e.id_company)
                              WHERE
                                 (
                                    b.d_op >= to_date(''$dfrom'', ''dd-mm-yyyy'') 
                                    AND b.d_op <= to_date(''$dto'', ''dd-mm-yyyy'')
                                 )
                                 $where
                                 AND b.i_status = ''6''
                                 and a.id_company = ''$this->idcompany''
                              GROUP BY
                                 imaterial,
                                 ematerial,
                                 kategori,
                                 jenis,
                                 to_char(b.d_op, ''mm'') 
                              UNION ALL
                              SELECT DISTINCT
                                 a.i_material as imaterial,
                                 c.e_material_name as ematerial,
                                 d.e_nama_kelompok as kategori,
				                     e.e_type_name as jenis,
                                 to_number(to_char(b.d_btb, ''mm''), ''99'') AS bln,
                                 sum(a.n_quantity) as qty_btb,
                                 0 as qty_op,
                                 sum(a.n_quantity * a.v_price) as nilai_btb,
                                 0 as nilai_op 
                              FROM
                                 tm_btb_item a 
                                 LEFT JOIN
                                    tm_btb b 
                                    ON (a.id_btb = b.id and a.id_company = b.id_company) 
                                 INNER JOIN
                                    tr_material c 
                                    ON (a.i_material = c.i_material and a.id_company = c.id_company) 
                                 INNER JOIN 
                                   tr_kelompok_barang d 
                                   ON (c.i_kode_kelompok = d.i_kode_kelompok and c.id_company = d.id_company)
                                 INNER JOIN 
                                   tr_item_type e 
                                   ON (c.i_type_code = e.i_type_code AND d.i_kode_kelompok = e.i_kode_kelompok and c.id_company = e.id_company and d.id_company = e.id_company)
                              WHERE
                                 (
                                    b.d_btb >= to_date(''$dfrom'', ''dd-mm-yyyy'') 
                                    AND b.d_btb <= to_date(''$dto'', ''dd-mm-yyyy'')
                                 )
                                 $where
                                 AND b.i_status = ''6''
                                 and a.id_company = ''$this->idcompany''
                              GROUP BY
                                 imaterial,
                                 ematerial,
                                 kategori,
                                 jenis,
                                 to_char(b.d_btb, ''mm'') 
                           )
                           AS a 
                        GROUP BY
                           a.imaterial, a.ematerial, a.kategori, a.jenis, a.bln 
                        ORDER BY
                           imaterial',
                     'SELECT 
                     (
                            SELECT
                               EXTRACT(MONTH 
                            FROM
                               date_trunc(''month'', ''$tgl''::date)::date + s.a * ''1 month''::interval)) 
                     FROM
                        generate_series(0, 11) as s(a)')
               AS
              (
                 imaterial text,
                 ematerial text,
                 kategori text,
                 jenis text,";
                    switch ($bl){
                        case '01' :
                          $sql.="Jan text, Feb text, Mar text, Apr text, May text, Jun text, Jul text, Aug text, Sep text, 
                                 Oct text, Nov text, Des text) ";
                          break;
                        case '02' :
                          $sql.="Feb text, Mar text, Apr text, May text, Jun text, Jul text, Aug text, Sep text, Oct text, 
                                 Nov text, Des text, Jan text) ";
                          break;
                        case '03' :
                          $sql.="Mar text, Apr text, May text, Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, 
                                 Des text, Jan text, Feb text) ";
                          break;
                        case '04' :
                          $sql.="Apr text, May text, Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, Des text, 
                                 Jan text, Feb text, Mar text) ";
                          break;
                        case '05' :
                          $sql.="May text, Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, Des text, Jan text, 
                                 Feb text, Mar text, Apr text) ";
                          break;
                        case '06' :
                          $sql.="Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, Des text, Jan text, Feb text, 
                                 Mar text, Apr text, May text) ";
                          break;
                        case '07' :
                          $sql.="Jul text, Aug text, Sep text, Oct text, Nov text, Des text, Jan text, Feb text, Mar text, 
                                 Apr text, May text, Jun text) ";
                          break;
                        case '08' :
                          $sql.="Aug text, Sep text, Oct text, Nov text, Des text, Jan text, Feb text, Mar text, Apr text, 
                                 May text, Jun text, Jul text) ";
                          break;
                        case '09' :
                          $sql.="Sep text, Oct text, Nov text, Des text, Jan text, Feb text, Mar text, Apr text, May text, 
                                 Jun text, Jul text, Aug text) ";
                          break;
                        case '10' :
                          $sql.="Oct text, Nov text, Des text, Jan text, Feb text, Mar text, Apr text, May text, Jun text, 
                                 Jul text, Aug text, Sep text) ";
                          break;
                        case '11' :
                          $sql.="Nov text, Des text, Jan text, Feb text, Mar text, Apr text, May text, Jun text, Jul text, 
                                 Aug text, Sep text, Oct text) ";
                          break;
                        case '12' :
                          $sql.="Des text, Jan text, Feb text, Mar text, Apr text, May text, Jun text, Jul text, Aug text, 
                                 Sep text, Oct text, Nov text) ";
                          break;
                    }
                    $sql.="
                            ) AS cb
                            order by imaterial";
        return $this->db->query($sql);
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
  
        $sql = "
              SELECT ROW_NUMBER() OVER  (ORDER BY cb.row_name[1], cb.row_name[3]) as nomor,
                    cb.row_name[1] As isupplier,
                    cb.row_name[2] As esupplier,
                    cb.row_name[3] As imaterial,
                    cb.row_name[4] As ematerial,
                    cb.kategori,
                    cb.jenis,
                     CASE
                        WHEN
                           (
                              cb.Jan isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Jan 
                     END
                     AS Jan, 
                     CASE
                        WHEN
                           (
                              cb.Feb isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Feb 
                     END
                     AS Feb, 
                     CASE
                        WHEN
                           (
                              cb.Mar isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Mar 
                     END
                     AS Mar, 
                     CASE
                        WHEN
                           (
                              cb.Apr isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Apr 
                     END
                     AS Apr, 
                     CASE
                        WHEN
                           (
                              cb.May isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.May 
                     END
                     AS May, 
                     CASE
                        WHEN
                           (
                              cb.Jun isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Jun 
                     END
                     AS Jun, 
                     CASE
                        WHEN
                           (
                              cb.Jul isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Jul 
                     END
                     AS Jul, 
                     CASE
                        WHEN
                           (
                              cb.Aug isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Aug 
                     END
                     AS Aug, 
                     CASE
                        WHEN
                           (
                              cb.Sep isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Sep 
                     END
                     AS Sep, 
                     CASE
                        WHEN
                           (
                              cb.Oct isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Oct 
                     END
                     AS Oct, 
                     CASE
                        WHEN
                           (
                              cb.Nov isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Nov 
                     END
                     AS Nov, 
                     CASE
                        WHEN
                           (
                              cb.Des isnull
                           )
                        THEN
                           concat('0', '|', '0', '|', '0', '|', '0') 
                        ELSE
                           cb.Des 
                     END
                     AS Des 
              FROM
              (
                     SELECT
                        * 
                     FROM
                        CROSSTAB ('
                     
                        SELECT
                           ARRAY[a.isupplier::text, a.esupplier::text, a.imaterial::text, a.ematerial::text] As row_name, a.kategori, a.jenis, a.bln, concat(sum(a.qty_op), '' | '', sum(a.qty_btb), '' | '', sum(a.nilai_op), '' | '', sum(a.nilai_btb)) as qty 
                        FROM
                           (
                              SELECT DISTINCT
                                 b.i_supplier as isupplier, 
                                 s.e_supplier_name as esupplier,
                                 a.i_material as imaterial,
                                 c.e_material_name as ematerial,
                                 d.e_nama_kelompok as kategori,
                             e.e_type_name as jenis,
                                 to_number(to_char(b.d_op, ''mm''), ''99'') AS bln,
                                 0 as qty_btb,
                                 sum(a.n_quantity) as qty_op,
                                 0 as nilai_btb,
                                 sum(a.n_quantity * a.v_price) as nilai_op 
                              FROM
                                 tm_opbb_item a 
                                 LEFT JOIN
                                    tm_opbb b 
                                    ON (a.id_op = b.id AND a.id_company = b.id_company)
                                 INNER JOIN tr_supplier s on (b.i_supplier = s.i_supplier AND b.id_company = s.id_company)
                                 INNER JOIN
                                    tr_material c 
                                    ON (a.i_material = c.i_material and a.id_company = c.id_company) 
                                 INNER JOIN 
                                   tr_kelompok_barang d 
                                   ON (c.i_kode_kelompok = d.i_kode_kelompok and c.id_company = d.id_company)
                                 INNER JOIN 
                                   tr_item_type e 
                                   ON (c.i_type_code = e.i_type_code AND d.i_kode_kelompok = e.i_kode_kelompok and c.id_company = e.id_company and d.id_company = e.id_company)
                              WHERE
                                 (
                                    b.d_op >= to_date(''$dfrom'', ''dd-mm-yyyy'') 
                                    AND b.d_op <= to_date(''$dto'', ''dd-mm-yyyy'')
                                 )
                                 $where
                                 AND b.i_status = ''6''
                                 and a.id_company = ''$this->idcompany''
                              GROUP BY
                                 b.i_supplier, 
                                 s.e_supplier_name,
                                 imaterial,
                                 ematerial,
                                 kategori,
                                 jenis,
                                 to_char(b.d_op, ''mm'') 
                              UNION ALL
                              SELECT DISTINCT
                                 b.i_supplier as isupplier, 
                                 s.e_supplier_name as esupplier,
                                 a.i_material as imaterial,
                                 c.e_material_name as ematerial,
                                 d.e_nama_kelompok as kategori,
                             e.e_type_name as jenis,
                                 to_number(to_char(b.d_btb, ''mm''), ''99'') AS bln,
                                 sum(a.n_quantity) as qty_btb,
                                 0 as qty_op,
                                 sum(a.n_quantity * a.v_price) as nilai_btb,
                                 0 as nilai_op 
                              FROM
                                 tm_btb_item a 
                                 LEFT JOIN
                                    tm_btb b 
                                    ON (a.id_btb = b.id and a.id_company = b.id_company) 
                                 INNER JOIN tr_supplier s on (b.i_supplier = s.i_supplier AND b.id_company = s.id_company)
                                 INNER JOIN
                                    tr_material c 
                                    ON (a.i_material = c.i_material and a.id_company = c.id_company) 
                                 INNER JOIN 
                                   tr_kelompok_barang d 
                                   ON (c.i_kode_kelompok = d.i_kode_kelompok and c.id_company = d.id_company)
                                 INNER JOIN 
                                   tr_item_type e 
                                   ON (c.i_type_code = e.i_type_code AND d.i_kode_kelompok = e.i_kode_kelompok and c.id_company = e.id_company and d.id_company = e.id_company)
                              WHERE
                                 (
                                    b.d_btb >= to_date(''$dfrom'', ''dd-mm-yyyy'') 
                                    AND b.d_btb <= to_date(''$dto'', ''dd-mm-yyyy'')
                                 )
                                 $where
                                 AND b.i_status = ''6''
                                 and a.id_company = ''$this->idcompany''
                              GROUP BY
                                 b.i_supplier, 
                                 s.e_supplier_name,
                                 imaterial,
                                 ematerial,
                                 kategori,
                                 jenis,
                                 to_char(b.d_btb, ''mm'') 
                           )
                           AS a 
                        GROUP BY
                           isupplier, esupplier, a.imaterial, a.ematerial, a.kategori, a.jenis, a.bln 
                        ORDER BY
                           imaterial',
                     'SELECT 
                     (
                            SELECT
                               EXTRACT(MONTH 
                            FROM
                               date_trunc(''month'', ''$tgl''::date)::date + s.a * ''1 month''::interval)) 
                     FROM
                        generate_series(0, 11) as s(a)')
               AS
              (
                 row_name text[],
                 kategori text,
                 jenis text,";
                    switch ($bl){
                        case '01' :
                          $sql.="Jan text, Feb text, Mar text, Apr text, May text, Jun text, Jul text, Aug text, Sep text, 
                                 Oct text, Nov text, Des text) ";
                          break;
                        case '02' :
                          $sql.="Feb text, Mar text, Apr text, May text, Jun text, Jul text, Aug text, Sep text, Oct text, 
                                 Nov text, Des text, Jan text) ";
                          break;
                        case '03' :
                          $sql.="Mar text, Apr text, May text, Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, 
                                 Des text, Jan text, Feb text) ";
                          break;
                        case '04' :
                          $sql.="Apr text, May text, Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, Des text, 
                                 Jan text, Feb text, Mar text) ";
                          break;
                        case '05' :
                          $sql.="May text, Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, Des text, Jan text, 
                                 Feb text, Mar text, Apr text) ";
                          break;
                        case '06' :
                          $sql.="Jun text, Jul text, Aug text, Sep text, Oct text, Nov text, Des text, Jan text, Feb text, 
                                 Mar text, Apr text, May text) ";
                          break;
                        case '07' :
                          $sql.="Jul text, Aug text, Sep text, Oct text, Nov text, Des text, Jan text, Feb text, Mar text, 
                                 Apr text, May text, Jun text) ";
                          break;
                        case '08' :
                          $sql.="Aug text, Sep text, Oct text, Nov text, Des text, Jan text, Feb text, Mar text, Apr text, 
                                 May text, Jun text, Jul text) ";
                          break;
                        case '09' :
                          $sql.="Sep text, Oct text, Nov text, Des text, Jan text, Feb text, Mar text, Apr text, May text, 
                                 Jun text, Jul text, Aug text) ";
                          break;
                        case '10' :
                          $sql.="Oct text, Nov text, Des text, Jan text, Feb text, Mar text, Apr text, May text, Jun text, 
                                 Jul text, Aug text, Sep text) ";
                          break;
                        case '11' :
                          $sql.="Nov text, Des text, Jan text, Feb text, Mar text, Apr text, May text, Jun text, Jul text, 
                                 Aug text, Sep text, Oct text) ";
                          break;
                        case '12' :
                          $sql.="Des text, Jan text, Feb text, Mar text, Apr text, May text, Jun text, Jul text, Aug text, 
                                 Sep text, Oct text, Nov text) ";
                          break;
                    }
                    $sql.="
                            ) AS cb
                            order by nomor";
        return $this->db->query($sql);
    }
}

/* End of file Mmaster.php */