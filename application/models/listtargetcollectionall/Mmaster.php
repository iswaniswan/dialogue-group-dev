<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($iperiode,$akhir, $iuser,$idcompany,$folder){
      /* $iperiode   = $tahun.$bulan ;
        if ($iarea=='00') {
            $sql = "AND a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')";
        }else{
            $sql = "";
        } */
        
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT i_area, area, target,blmbayar,realisasitdktelat, 
                            CASE WHEN realisasitdktelat <> 0 THEN (realisasitdktelat / target)* 100
                            ELSE 0 END AS persentdktelat, realisasitelat, CASE WHEN realisasitelat <> 0 THEN (realisasitelat/target)*100
                            ELSE 0 END AS persentelat, realisasi, CASE WHEN realisasi <> 0 THEN (realisasi/target)*100
                            ELSE 0 END AS persenrealisasi FROM(
                            
                              SELECT i_area, i_area||'-'||e_area_name AS area, SUM(total) AS target, SUM(blmbayar) AS blmbayar,
                              SUM(realisasitdktelat) AS realisasitdktelat, SUM(realisasitelat) AS realisasitelat,SUM(realisasi) AS realisasi/*,
                              , SUM(totalnon) AS totalnon,
                              SUM(realisasinon) AS realisasinon,  SUM(tdktelat) AS tdktelat,SUM(telat) AS telat 
                              SUM(realisasitelat) AS realisasitelat*/ FROM(
                                SELECT a.i_area, a.e_area_name, SUM(b.v_target_tagihan) AS total, SUM(b.v_realisasi_tagihan) AS realisasi, 
                                0 AS totalnon, 0 AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat,
                                0 AS realisasitelat
                                FROM tr_area a
                                LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t'
                                AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                AND b.f_insentif='t'
                                GROUP BY a.i_area, a.e_area_name
                                
                                UNION ALL
                              
                                SELECT a.i_area, a.e_area_name, 0 AS total, 0 AS realisasi, SUM(b.v_target_tagihan) AS totalnon, 
                                SUM(b.v_realisasi_tagihan) AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat,
                                0 AS realisasitdktelat, 0 AS realisasitelat
                                FROM tr_area a
                                LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t'
                                AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                AND b.f_insentif='f'
                                GROUP BY a.i_area, a.e_area_name
                              
                                UNION ALL
                              
                                SELECT x.i_area, x.e_area_name, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 
                                SUM(x.v_target_tagihan) AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                FROM (
                                  SELECT a.i_area, a.e_area_name, b.i_nota, SUM(b.v_target_tagihan) AS v_target_tagihan, 
                                  SUM(b.v_realisasi_tagihan) AS v_realisasi_tagihan
                                  FROM 
                                  tr_area a
                                  LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                                  WHERE a.f_area_real='t'
                                  AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                  AND b.f_insentif='t' AND substring(b.kelompok,1,2)='02'
                                  GROUP BY a.i_area, a.e_area_name, b.i_nota
                                ) AS x
                                WHERE x.v_realisasi_tagihan=0
                                GROUP BY x.i_area, x.e_area_name
                              -----------------------------------------------------------------------------------------------------------------------------
                                UNION ALL
                              
                                SELECT a.i_area, a.e_area_name, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 0  AS blmbayar, 
                                SUM(tdktelat) AS tdktelat, SUM(telat) AS telat, SUM(realisasitdktelat) AS realisasitdktelat, 
                                SUM(realisasitelat) AS realisasitelat
                                FROM (
                                  SELECT a.i_area, a.e_area_name, 0  AS blmbayar, SUM(b.v_target_tagihan) AS tdktelat,
                                  0 AS telat, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                  SUM(b.v_realisasi_tagihan) AS realisasitdktelat, 0 AS realisasitelat
                                  FROM (
                                    SELECT a.i_area, a.e_area_name, b.i_nota, avg(b.n_lamabayar) AS n_lama
                                    FROM tr_area a
                                    LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                                    WHERE a.f_area_real='t'
                                    AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                    AND b.f_insentif='t' AND substring(b.kelompok,1,2)='00'
                                    GROUP BY a.i_area, a.e_area_name, b.i_nota
                                  ) AS c, tr_area a
                                  LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                                  WHERE a.f_area_real='t'AND b.f_insentif='t' AND a.i_area=c.i_area 
                                  AND b.i_nota=c.i_nota
                                  AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                  GROUP BY a.i_area, a.e_area_name, c.n_lama
                                
                                  UNION ALL
                                
                                  SELECT a.i_area, a.e_area_name, 0  AS blmbayar, 0 AS tdktelat,
                                  SUM(b.v_target_tagihan) AS telat, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                  0 AS realisasitdktelat, SUM(b.v_realisasi_tagihan) AS realisasitelat
                                  FROM (
                                    SELECT a.i_area, a.e_area_name, b.i_nota, avg(b.n_lamabayar) AS n_lama
                                    FROM tr_area a
                                    LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                                    WHERE a.f_area_real='t'
                                    AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                    AND b.f_insentif='t' AND substring(b.kelompok,1,2)='01'
                                    GROUP BY a.i_area, a.e_area_name, b.i_nota
                                  ) AS c, tr_area a
                                  LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                                  WHERE a.f_area_real='t'AND b.f_insentif='t' AND a.i_area=c.i_area 
                                  AND b.i_nota=c.i_nota
                                  AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                  GROUP BY a.i_area, a.e_area_name, c.n_lama
                                ) AS a
                                GROUP BY a.i_area, a.e_area_name
                                
                              ) AS b
                              GROUP BY b.i_area, b.e_area_name
                            )AS c", FALSE);
        $datatables->add('nota', function ($data) {
          $iarea      = trim($data['i_area']);
          $iperiode   = $data['iperiode'];
          $folder     = $data['folder'];
          $akhir      = $data['akhir'];
          $data       = '';
          $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$iperiode/$akhir/$iarea/\",\"#main\")'><i class='fa fa-external-link'></i></a>";
          return $data;
        });
        $datatables->add('sales', function ($data) {
          $iarea      = trim($data['i_area']);
          $iperiode   = $data['iperiode'];
          $folder     = $data['folder'];
          $akhir      = $data['akhir'];
          $data       = '';
          $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Sales\" onclick='window.open(\"$folder/cform/sales/$iperiode/$akhir/$iarea/\",\"#main\")'><i class='fa fa-external-link-square'></i></a>";
          return $data;
        });

        $datatables->edit('target', function ($data) {
            return number_format($data['target']);
        });
        $datatables->edit('blmbayar', function ($data) {
            return number_format($data['blmbayar']);
        });
        $datatables->edit('realisasitdktelat', function ($data) {
          return number_format($data['realisasitdktelat']);
        });
        $datatables->edit('persentdktelat', function ($data) {
          return number_format($data['persentdktelat'],2)." %";
        });
        $datatables->edit('realisasitelat', function ($data) {
          return number_format($data['realisasitelat']);
        });
        $datatables->edit('persentelat', function ($data) {
          return number_format($data['persentelat'],2)." %";
        });
        $datatables->edit('realisasi', function ($data) {
          return number_format($data['realisasi']);
        });
        $datatables->edit('persenrealisasi', function ($data) {
          return number_format($data['persenrealisasi'],2)." %";
        });
        
        $datatables->hide('i_area');
        $datatables->hide('iperiode');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    function baca($iperiode,$iuser)
    {
#      $this->db->SELECT("* FROM f_target_collection_rekapkodearea('$iperiode','$akhir');",false);
      return $this->db->query(" SELECT i_area, e_area_name, SUM(total) AS total, SUM(realisasi) AS realisasi, SUM(totalnon) AS totalnon,
                                SUM(realisasinon) AS realisasinon, SUM(blmbayar) AS blmbayar, SUM(tdktelat) AS tdktelat, SUM(telat) AS telat,
                                SUM(realisasitdktelat) AS realisasitdktelat, SUM(realisasitelat) AS realisasitelat FROM(
                                  SELECT a.i_area, a.e_area_name, SUM(b.v_target_tagihan) AS total, SUM(b.v_realisasi_tagihan) AS realisasi, 
                                  0 AS totalnon, 0 AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat,
                                  0 AS realisasitelat
                                  FROM tr_area a
                                  LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                  WHERE a.f_area_real='t' AND b.e_periode='$iperiode' 
                                  AND b.f_insentif='t'
                                  GROUP BY a.i_area, a.e_area_name
                                  
                                  UNION ALL
                                  
                                  SELECT a.i_area, a.e_area_name, 0 AS total, 0 AS realisasi, SUM(b.v_target_tagihan) AS totalnon, 
                                  SUM(b.v_realisasi_tagihan) AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat,
                                  0 AS realisasitdktelat, 0 AS realisasitelat
                                  FROM tr_area a
                                  LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                  WHERE a.f_area_real='t' AND b.e_periode='$iperiode' 
                                  AND b.f_insentif='f'
                                  GROUP BY a.i_area, a.e_area_name

                                  UNION ALL
                                
                                  SELECT x.i_area, x.e_area_name, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 
                                  SUM(x.v_target_tagihan) AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                  FROM (
                                    SELECT a.i_area, a.e_area_name, b.i_nota, SUM(b.v_target_tagihan) AS v_target_tagihan, 
                                    SUM(b.v_realisasi_tagihan) AS v_realisasi_tagihan
                                    FROM 
                                    tr_area a
                                    LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                    WHERE a.f_area_real='t' AND b.e_periode='$iperiode' 
                                    AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='02'
                                    GROUP BY a.i_area, a.e_area_name, b.i_nota
                                  ) AS x
                                  WHERE x.v_realisasi_tagihan=0
                                  GROUP BY x.i_area, x.e_area_name
                                  
                                  UNION ALL
                                
                                  SELECT a.i_area, a.e_area_name, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 0  AS blmbayar, 
                                  SUM(tdktelat) AS tdktelat, SUM(telat) AS telat, SUM(realisasitdktelat) AS realisasitdktelat, 
                                  SUM(realisasitelat) AS realisasitelat
                                  FROM (
                                  SELECT a.i_area, a.e_area_name, 0  AS blmbayar, SUM(b.v_target_tagihan) AS tdktelat,
                                  0 AS telat, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                  SUM(b.v_realisasi_tagihan) AS realisasitdktelat, 0 AS realisasitelat FROM(
                                    SELECT a.i_area, a.e_area_name, b.i_nota, avg(b.n_lamabayar) AS n_lama
                                    FROM tr_area a
                                    LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                    WHERE a.f_area_real='t' AND b.e_periode='$iperiode' 
                                    AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='00'
                                    GROUP BY a.i_area, a.e_area_name, b.i_nota
                                  ) AS c, tr_area a
                                  LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                  WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t' AND a.i_area=c.i_area 
                                  AND b.i_nota=c.i_nota
                                  AND a.i_area in (SELECT i_area FROM tm_user_area WHERE i_user='$iuser')
                                  GROUP BY a.i_area, a.e_area_name, c.n_lama
                                
                                  UNION ALL
                                
                                  SELECT a.i_area, a.e_area_name, 0  AS blmbayar, 0 AS tdktelat,
                                  SUM(b.v_target_tagihan) AS telat, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                  0 AS realisasitdktelat, SUM(b.v_realisasi_tagihan) AS realisasitelat
                                  FROM (
                                    SELECT a.i_area, a.e_area_name, b.i_nota, avg(b.n_lamabayar) AS n_lama
                                    FROM tr_area a
                                    LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                    WHERE a.f_area_real='t' AND b.e_periode='$iperiode' 
                                    AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='01'
                                    GROUP BY a.i_area, a.e_area_name, b.i_nota
                                  ) AS c, tr_area a
                                  LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                  WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t' AND a.i_area=c.i_area 
                                  AND b.i_nota=c.i_nota
                                  GROUP BY a.i_area, a.e_area_name, c.n_lama
                                )as a
                                GROUP BY a.i_area, a.e_area_name
                                ) AS a
                                GROUP BY a.i_area, a.e_area_name
                                ORDER BY a.i_area");
		  
    }
    function detail($iarea,$iperiode)
    {
      return $this->db->query(" SELECT a.i_area, a.e_area_name, a.i_customer, a.e_customer_name, a.e_customer_classname, a.i_salesman, a.e_salesman_name, 
                                a.i_nota, a.d_nota, SUM(total) AS total, SUM(realisasi) AS realisasi, SUM(totalnon) AS totalnon, 
                                SUM(realisasinon) AS realisasinon, SUM(blmbayar) AS blmbayar, SUM(tdktelat) AS tdktelat, SUM(telat) AS telat,
                                SUM(realisasitdktelat) AS realisasitdktelat, SUM(realisasitelat) AS realisasitelat FROM(
                                SELECT a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, SUM(b.v_target_tagihan) AS total, SUM(b.v_realisasi_tagihan) AS realisasi, 
                                0 AS totalnon, 0 AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat,
                                0 AS realisasitelat
                                FROM tr_area a
                                LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t'
                                GROUP BY a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota
                                UNION ALL
                                SELECT a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, 0 AS total, 0 AS realisasi, SUM(b.v_target_tagihan) AS totalnon, 
                                SUM(b.v_realisasi_tagihan) AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat,
                                0 AS realisasitdktelat, 0 AS realisasitelat
                                FROM tr_area a
                                LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='f'
                                GROUP BY a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota
                                UNION ALL
                                SELECT x.i_area, x.e_area_name, x.i_customer, x.e_customer_name, x.e_customer_classname, x.i_salesman, 
                                x.e_salesman_name, x.i_nota, x.d_nota, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 
                                SUM(x.v_target_tagihan) AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                FROM (
                                SELECT a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, SUM(b.v_target_tagihan) AS v_target_tagihan, 
                                SUM(b.v_realisasi_tagihan) AS v_realisasi_tagihan
                                FROM 
                                tr_area a
                                LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='02' 
                                GROUP BY a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota
                                ) AS x
                                WHERE x.v_realisasi_tagihan=0
                                GROUP BY x.i_area, x.e_area_name, x.i_customer, x.e_customer_name, x.e_customer_classname, x.i_salesman, 
                                x.e_salesman_name, x.i_nota, x.d_nota
                                UNION ALL
                                SELECT a.i_area, a.e_area_name, a.i_customer, a.e_customer_name, a.e_customer_classname, a.i_salesman, 
                                a.e_salesman_name, a.i_nota, a.d_nota, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 
                                0  AS blmbayar, 
                                SUM(tdktelat) AS tdktelat, SUM(telat) AS telat, SUM(realisasitdktelat) AS realisasitdktelat, 
                                SUM(realisasitelat) AS realisasitelat
                                FROM (
                                SELECT a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, 0  AS blmbayar,
                                SUM(b.v_target_tagihan) AS tdktelat,
                                0 AS telat,
                                0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                SUM(b.v_realisasi_tagihan) AS realisasitdktelat,
                                0 AS realisasitelat
                                FROM (
                                SELECT a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, avg(b.n_lamabayar) AS n_lama
                                FROM tr_area a
                                LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='00' 
                                GROUP BY a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota
                                ) AS c, tr_area a
                                LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t' AND a.i_area=c.i_area 
                                AND b.i_nota=c.i_nota
                                GROUP BY a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, c.n_lama
                                
                                UNION ALL
                                
                                SELECT a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, 0  AS blmbayar,
                                0 AS tdktelat, SUM(b.v_target_tagihan) AS telat, 
                                0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                0 AS realisasitdktelat, SUM(b.v_realisasi_tagihan) AS realisasitelat
                                FROM (
                                SELECT a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, avg(b.n_lamabayar) AS n_lama
                                FROM tr_area a
                                LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='01' 
                                GROUP BY a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota
                                
                                ) AS c, tr_area a
                                LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t' AND a.i_area=c.i_area 
                                AND b.i_nota=c.i_nota
                                GROUP BY a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_salesman, 
                                b.e_salesman_name, b.i_nota, b.d_nota, c.n_lama
                                )as a
                                GROUP BY a.i_area, a.e_area_name, a.i_customer, a.e_customer_name, a.e_customer_classname, a.i_salesman, 
                                a.e_salesman_name, a.i_nota, a.d_nota
                                ) AS a
                                GROUP BY a.i_area, a.e_area_name, a.i_customer, a.e_customer_name, a.e_customer_classname, a.i_salesman, 
                                a.e_salesman_name, a.i_nota, a.d_nota
                                ORDER BY a.i_area, a.i_nota");
    }
    function detailsales($iarea,$iperiode)
    {
      return $this->db->query("SELECT	a.i_salesman, a.e_salesman_name, SUM(total) AS total, SUM(realisasi) AS realisasi, SUM(totalnon) AS totalnon, 
                                SUM(realisasinon) AS realisasinon, SUM(blmbayar) AS blmbayar, SUM(tdktelat) AS tdktelat, SUM(telat) AS telat,
                                SUM(realisasitdktelat) AS realisasitdktelat, SUM(realisasitelat) AS realisasitelat FROM(
                                SELECT b.i_salesman, b.e_salesman_name, SUM(b.v_target_tagihan) AS total, SUM(b.v_realisasi_tagihan) AS realisasi, 
                                0 AS totalnon, 0 AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat,
                                0 AS realisasitelat
                                FROM tm_collection_item b
                                WHERE b.e_periode='$iperiode' AND b.f_insentif='t'
                                GROUP BY b.i_salesman, b.e_salesman_name
                                UNION ALL
                                SELECT b.i_salesman, b.e_salesman_name, 0 AS total, 0 AS realisasi, SUM(b.v_target_tagihan) AS totalnon, 
                                SUM(b.v_realisasi_tagihan) AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat,
                                0 AS realisasitdktelat, 0 AS realisasitelat
                                FROM tm_collection_item b
                                WHERE b.e_periode='$iperiode' AND b.f_insentif='f'
                                GROUP BY b.i_salesman, b.e_salesman_name
                                UNION ALL

                                SELECT x.i_salesman, x.e_salesman_name, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 
                                SUM(x.v_target_tagihan) AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                FROM (
                                SELECT b.i_salesman, b.e_salesman_name, 
                                SUM(b.v_target_tagihan)-SUM(b.v_realisasi_tagihan) AS v_target_tagihan, 
                                0 AS v_realisasi_tagihan
                                FROM tm_collection_item b
                                WHERE b.e_periode='$iperiode' AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='02'
                                GROUP BY b.i_salesman, b.e_salesman_name
                                ) AS x
                                GROUP BY x.i_salesman, x.e_salesman_name

                                UNION ALL

                                SELECT a.i_salesman, a.e_salesman_name, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, 
                                0  AS blmbayar, SUM(tdktelat) AS tdktelat, SUM(telat) AS telat, SUM(realisasitdktelat) AS realisasitdktelat, 
                                SUM(realisasitelat) AS realisasitelat
                                FROM (
                                SELECT b.i_salesman, b.e_salesman_name, 0  AS blmbayar,
                                SUM(b.v_target_tagihan) AS tdktelat, 0 AS telat, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                SUM(b.v_realisasi_tagihan) AS realisasitdktelat, 0 AS realisasitelat
                                FROM (
                                SELECT b.i_salesman, b.e_salesman_name, b.i_nota
                                FROM tm_collection_item b
                                WHERE b.e_periode='$iperiode' AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='00'
                                GROUP BY b.i_salesman, b.e_salesman_name, b.i_nota
                                ) AS c, tm_collection_item b
                                WHERE b.e_periode='$iperiode' AND b.f_insentif='t' AND b.i_nota=c.i_nota
                                GROUP BY b.i_salesman, b.e_salesman_name

                                UNION ALL
                                                                                    
                                SELECT b.i_salesman, b.e_salesman_name, 0  AS blmbayar,
                                0 AS tdktelat, SUM(b.v_target_tagihan) AS telat, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon,
                                0 AS realisasitdktelat, SUM(b.v_realisasi_tagihan) AS realisasitelat
                                FROM (
                                SELECT b.i_salesman, b.e_salesman_name, b.i_nota
                                FROM tm_collection_item b
                                WHERE b.e_periode='$iperiode' AND b.f_insentif='t' AND substring(b.e_kelompok,1,2)='01'
                                GROUP BY b.i_salesman, b.e_salesman_name, b.i_nota
                                ) AS c, tm_collection_item b
                                WHERE b.e_periode='$iperiode' AND b.f_insentif='t' AND b.i_nota=c.i_nota
                                GROUP BY b.i_salesman, b.e_salesman_name

                                )as a
                                GROUP BY a.i_salesman, a.e_salesman_name
                                ) AS a
                                GROUP BY a.i_salesman, a.e_salesman_name
                                ORDER BY a.e_salesman_name, a.i_salesman");
    }
    function detaildivisi($iarea,$iperiode)
    {
      return $this->db->query(" SELECT a.i_area, a.e_area_name, a.i_nota, a.i_customer, a.e_customer_name, 
                                a.i_product_group, a.e_product_groupname, a.d_jatuh_tempo, 
                                to_char(a.d_jatuh_tempo_plustoleransi, 'yyyy-mm-dd') AS d_jatuh_tempo_plustoleransi, 
                                a.d_nota, SUM(a.total) AS total, SUM(a.realisasi) AS realisasi, SUM(a.totalnon) AS totalnon, 
                                SUM(a.realisasinon) AS realisasinon, SUM(blmbayar) AS blmbayar, SUM(tdktelat) AS tdktelat, 
                                SUM(telat) AS telat, SUM(realisasitdktelat) AS realisasitdktelat, SUM(realisasitelat) AS realisasitelat 
                                FROM(
                                  SELECT a.i_area, a.e_area_name, b.i_nota, b.i_customer, b.e_customer_name, b.i_product_group, c.e_product_groupname, b.d_jatuh_tempo, b.d_jatuh_tempo_plustoleransi, b.d_nota, b.v_target_tagihan AS total, b.v_realisasi_tagihan AS realisasi, 0 AS totalnon, 0 AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                  FROM tr_area a
                                  LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                  LEFT JOIN tr_product_group c ON (b.i_product_group=c.i_product_group)
                                  WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t'
                                  
                                  UNION ALL

                                  SELECT a.i_area, a.e_area_name, b.i_nota, b.i_customer, b.e_customer_name, b.i_product_group, c.e_product_groupname, b.d_jatuh_tempo, b.d_jatuh_tempo_plustoleransi, b.d_nota, 0 AS total, 0 AS realisasi, b.v_target_tagihan AS totalnon, b.v_realisasi_tagihan AS realisasinon, 0 AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                  FROM tr_area a
                                  LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                  LEFT JOIN tr_product_group c ON (b.i_product_group=c.i_product_group)
                                  WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='f'
                                  
                                  UNION ALL

                                  SELECT x.i_area, x.e_area_name, x.i_nota, x.i_customer, x.e_customer_name, x.i_product_group, x.e_product_groupname, x.d_jatuh_tempo, x.d_jatuh_tempo_plustoleransi, x.d_nota, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, x.v_target_tagihan AS blmbayar, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                  FROM (
                                    SELECT a.i_area, a.e_area_name, b.i_nota, b.i_customer, b.e_customer_name, b.i_product_group, c.e_product_groupname, b.d_jatuh_tempo, b.d_jatuh_tempo_plustoleransi, b.d_nota, 0 AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon, b.v_target_tagihan, 0 AS tdktelat, 0 AS telat, 0 AS realisasitdktelat, 0 AS realisasitelat
                                    FROM 
                                    tr_area a
                                    LEFT JOIN tm_collection_item b on(a.i_area=b.i_area)
                                    LEFT JOIN tr_product_group c ON (b.i_product_group=c.i_product_group)
                                    WHERE a.f_area_real='t' AND b.e_periode='$iperiode' AND b.f_insentif='t'
                                  ) AS x
                                  WHERE x.realisasi=0
                                ) AS a 
                                GROUP BY a.i_area, a.e_area_name, a.i_nota, a.i_customer, a.e_customer_name, a.i_product_group, a.e_product_groupname, a.d_jatuh_tempo, a.d_nota, a.d_jatuh_tempo_plustoleransi
                                ORDER BY a.i_area, a.i_product_group, a.i_nota
                            ");
    }
    function detailcetak($iarea,$iperiode)
    {
      $this->db->SELECT("	e_periode, i_area, e_area_name, i_customer, e_customer_name, e_customer_classname, i_nota, d_nota, 
                          SUM(total) AS total, SUM(realisasi) AS realisasi, SUM(totalnon) AS totalnon, SUM(realisasinon) AS realisasinon 
                          FROM(
                          SELECT b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota, SUM(b.sisa) AS total, SUM(b.bayar) AS realisasi, 0 AS totalnon, 0 AS realisasinon
                          FROM tr_area a
                          LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                          WHERE a.f_area_real='t' AND b.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='t'
                          GROUP BY b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota
                          UNION ALL
                          SELECT b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota, SUM(b.bayar) AS total, 0 AS realisasi, 0 AS totalnon, 0 AS realisasinon
                          FROM tr_area a
                          LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area AND b.v_nota_netto=0 AND b.sisa=0)
                          WHERE a.f_area_real='t' AND b.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='t'
                          GROUP BY b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota
                          UNION ALL
                          SELECT b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota, 0 AS total, 0 AS realisasi, SUM(b.sisa) AS totalnon, SUM(b.bayar) AS realisasinon
                          FROM tr_area a
                          LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area)
                          WHERE a.f_area_real='t' AND b.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='f'
                          GROUP BY b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota
                          UNION ALL
                          SELECT b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota, 0 AS total, 0 AS realisasi, SUM(b.bayar) AS totalnon, 0 AS realisasinon
                          FROM tr_area a
                          LEFT JOIN f_target_collection_rekapkodealokasi('$iperiode','$akhir') b on(a.i_area=b.i_area AND b.v_nota_netto=0 AND b.sisa=0)
                          WHERE a.f_area_real='t' AND b.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='f'
                          GROUP BY b.e_periode, a.i_area, a.e_area_name, b.i_customer, b.e_customer_name, b.e_customer_classname, b.i_nota, 
                          b.d_nota
                          ) AS a 
                          GROUP BY e_periode, i_area, e_area_name, i_customer, e_customer_name, e_customer_classname, i_nota, d_nota
                          ORDER BY a.i_area, a.i_nota",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function cetaksales($iarea,$iperiode)
    {
      $this->db->SELECT("	e_periode,i_area, e_area_name, i_salesman, e_salesman_name, SUM(total) AS total, SUM(realisasi) AS realisasi, 
                          SUM(totalnon) AS totalnon, SUM(realisasinon) AS realisasinon FROM(
                          SELECT b.e_periode, a.i_area, a.e_area_name, e.i_salesman, e.e_salesman_name, SUM(b.sisa) AS total, 
                          SUM(b.bayar) AS realisasi, 0 AS totalnon, 0 AS realisasinon
                          FROM tr_area a
                          LEFT JOIN tm_collection_credit b on(a.i_area=b.i_area)
                          LEFT JOIN tr_salesman e ON (b.i_salesman=e.i_salesman)
                          WHERE a.f_area_real='t' AND a.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='t'
                          GROUP BY a.i_area, a.e_area_name, e.i_salesman, e_salesman_name, b.e_periode
                          UNION ALL
                          SELECT b.e_periode, a.i_area, a.e_area_name, e.i_salesman, e.e_salesman_name, SUM(b.bayar) AS total, 
                          0 AS realisasi, 0 AS totalnon, 0 AS realisasinon
                          FROM tr_area a
                          LEFT JOIN tm_collection_credit b on(a.i_area=b.i_area AND b.v_nota_netto=0 AND b.sisa=0)
                          LEFT JOIN tr_salesman e ON (b.i_salesman=e.i_salesman)
                          WHERE a.f_area_real='t' AND a.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='t'
                          GROUP BY a.i_area, a.e_area_name, e.i_salesman, e_salesman_name, b.e_periode
                          UNION ALL
                          SELECT b.e_periode, a.i_area, a.e_area_name, e.i_salesman, e.e_salesman_name, 0 AS total, 0 AS realisasi, 
                          SUM(b.sisa) AS totalnon, SUM(b.bayar) AS realisasinon
                          FROM tr_area a
                          LEFT JOIN tm_collection_credit b on(a.i_area=b.i_area)
                          LEFT JOIN tr_salesman e ON (b.i_salesman=e.i_salesman)
                          WHERE a.f_area_real='t' AND a.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='f'
                          GROUP BY a.i_area, a.e_area_name, e.i_salesman, e_salesman_name, b.e_periode
                          UNION ALL
                          SELECT b.e_periode, a.i_area, a.e_area_name, e.i_salesman, e.e_salesman_name, 0 AS total, 0 AS realisasi, 
                          SUM(b.bayar) AS totalnon, 0 AS realisasinon
                          FROM tr_area a
                          LEFT JOIN tm_collection_credit b on(a.i_area=b.i_area AND b.v_nota_netto=0 AND b.sisa=0)
                          LEFT JOIN tr_salesman e ON (b.i_salesman=e.i_salesman)
                          WHERE a.f_area_real='t' AND a.i_area='$iarea' AND b.e_periode='$iperiode' AND b.f_insentif='f'
                          GROUP BY a.i_area, a.e_area_name, e.i_salesman, e_salesman_name, b.e_periode
                          ) AS a 
                          GROUP BY i_area, e_area_name, i_salesman, e_salesman_name, e_periode
                          ORDER BY a.i_area, a.i_salesman",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  return $query->result();
		  }
    }
    function bacaexcel($iperiode,$istore,$cari)
    {
		  $this->db->SELECT("	a.*, b.e_product_name FROM tm_mutasi a, tr_product b
						              WHERE e_mutasi_periode = '$iperiode' AND a.i_product=b.i_product
						              AND i_store='$istore' ORDER BY b.e_product_name ",false);#->limit($num,$offset);
		  $query = $this->db->get();
      return $query;
    }
	function bacaarea($num,$offset,$area1,$area2,$area3,$area4,$area5)
    {
		if($area1=='00' or $area2=='00' or $area3=='00' or $area4=='00' or $area5=='00'){
			$this->db->SELECT(" distinct (b.i_store), b.e_store_name, c.i_store_location, c.e_store_locationname
                        FROM tr_area a, tr_store b, tr_store_location c
                        WHERE a.i_store=b.i_store AND b.i_store=c.i_store
                        ORDER BY b.i_store, c.i_store_location", false)->limit($num,$offset);
		}else{
			$this->db->SELECT(" distinct (b.i_store), b.e_store_name, c.i_store_location, c.e_store_locationname
                        FROM tr_area a, tr_store b, tr_store_location c
                        WHERE a.i_store=b.i_store AND b.i_store=c.i_store
                        AND (a.i_area = '$area1' or a.i_area = '$area2' or a.i_area = '$area3'
                        or a.i_area = '$area4' or a.i_area = '$area5')
                        ORDER BY b.i_store, c.i_store_location", false)->limit($num,$offset);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }
	function cariarea($cari,$num,$offset,$area1,$area2,$area3,$area4,$area5)
    {
		if($area1=='00' or $area2=='00' or $area3=='00' or $area4=='00' or $area5=='00'){
			$this->db->SELECT("distinct ON (a.i_store) a.i_store, a.i_area, b.e_store_name, c.i_store_location, c.e_store_locationname 
                 FROM tr_area a, tr_store b , tr_store_location c
                 WHERE (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%')
                 AND a.i_store=b.i_store AND b.i_store=c.i_store
							   ORDER BY a.i_store ", FALSE)->limit($num,$offset);
		}else{
			$this->db->SELECT("distinct ON (a.i_store) a.i_store, a.i_area, b.e_store_name, c.i_store_location, c.e_store_locationname
                 FROM tr_area a, tr_store b, tr_store_location c
                 WHERE b.i_store=c.i_store AND (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%')
							   AND (i_area = '$area1' or i_area = '$area2' or i_area = '$area3'
							   or i_area = '$area4' or i_area = '$area5') ORDER BY a.i_store ", FALSE)->limit($num,$offset);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
  }
  function bacaareas($iperiode)
  {
    $this->db->SELECT(" distinct i_area FROM tm_collection_credit WHERE e_periode='$iperiode' ",false);
	  $query = $this->db->get();
	  if ($query->num_rows() > 0){
		  return $query->result();
	  }
  }
  function detailall($iperiode)
  {
    $this->db->SELECT("	a.i_area, a.i_salesman, e_salesman_name, SUM(total) AS total, SUM(realisasi) AS realisasi, 
                        SUM(totalnon) AS totalnon, SUM(realisasinon) AS realisasinon FROM(
                        SELECT b.i_area, b.i_salesman, b.e_salesman_name, SUM(b.v_target_tagihan) AS total, 
                        SUM(b.v_realisasi_tagihan) AS realisasi, 0 AS totalnon, 0 AS realisasinon
                        FROM f_target_collection_rekapkodealokasi('$iperiode','$akhir') b
                        WHERE b.e_periode='$iperiode' AND b.f_insentif='t'
                        GROUP BY b.i_area, b.i_salesman, b.e_salesman_name
                        UNION ALL
                        SELECT b.i_area, b.i_salesman, b.e_salesman_name, 0 AS total, 0 AS realisasi, 
                        SUM(b.v_target_tagihan) AS totalnon, SUM(b.v_realisasi_tagihan) AS realisasinon
                        FROM f_target_collection_rekapkodealokasi('$iperiode','$akhir') b
                        WHERE b.e_periode='$iperiode' AND b.f_insentif='f'
                        GROUP BY b.i_area, b.i_salesman, b.e_salesman_name
                        ) AS a 
                        GROUP BY a.i_area, a.i_salesman, a.e_salesman_name
                        ORDER BY a.i_area, a.i_salesman",false);
	  $query = $this->db->get();
	  if ($query->num_rows() > 0){
		  return $query->result();
	  }
  }
}
?>