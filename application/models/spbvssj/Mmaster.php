<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

   public function bacaperiode($userid, $dfrom,$dto,$is_groupbrg) {
      $isadapusat = 0; 
      $areanya = "";
      $sqlnya = $this->db->query(" 
                           SELECT 
                              a.* 
                           FROM 
                              tm_user_area a, 
                              tr_area b 
                           WHERE 
                              a.i_area = b.i_area 
                              AND a.i_user = '$userid' 
                              AND a.i_area <> 'XX' 
                           ORDER BY 
                              a.i_area 
                        ");
      if ($sqlnya->num_rows() > 0){
         $hasilnya=$sqlnya->result();
         foreach ($hasilnya as $rownya) {
            if ($rownya->i_area == '00')
               $isadapusat = '1';
            $areanya.= $rownya->i_area.";";
         }
      }
      // 26-05-2015
      if ($is_groupbrg == '2'){
         $datagrup = '';
      }else{
         // ambil grup brg dari tr_product_group, relasi ke tr_product_type
         $queryxx = $this->db->query(" 
                              SELECT 
                                 i_product_group, 
                                 e_product_groupname 
                              FROM 
                                 tr_product_group 
                              ORDER BY 
                                 i_product_group 
                              ");
         if ($queryxx->num_rows() > 0){
            $datagrup = array();
            $hasilxx=$queryxx->result();
            foreach ($hasilxx as $rowxx) {
               $i_product_group = $rowxx->i_product_group;
               $e_product_groupname = $rowxx->e_product_groupname;
               $datagrup[] = array( 
                              'i_product_group'=> $i_product_group,
                              'e_product_groupname'=> $e_product_groupname,
                              'totalspb'=> 0,
                              'totalsj'=> 0,
                              'totalnota'=> 0
                           );
            }
         }
      }
      
      // query utk ambil data di tm_spb berdasarkan areanya. Jika isadapusat = 1 maka ga pake filter area
      $outputdata = array();
      if ($isadapusat == '0') {
         $listarea = explode(";", $areanya);
         foreach ($listarea as $rowarea) {
            $rowarea = trim($rowarea);
            if ($rowarea != '') {
               // query ambil nama area
               $queryxx = $this->db->query(" 
                                    SELECT 
                                       e_area_name 
                                    FROM 
                                       tr_area 
                                    WHERE 
                                       i_area = '$rowarea' 
                                    ");
                       
               if ($queryxx->num_rows() > 0){
                  $hasilxx = $queryxx->row();
                  $namaarea = $hasilxx->e_area_name;
               }
               else
                  $namaarea = '';
               
               // ============================== 26-05-2015, sama dgn yg di cform ==================================================
               // 23-05-2015 pisah berdasarkan group brg.
               if ($is_groupbrg == '1') {
                  $datanilaispb  = array();
                  $datanilaisj   = array();
                  $datanilainota = array();
                  // ambil grup brg dari tr_product_group, relasi ke tr_product_type
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          i_product_group, 
                                          e_product_groupname 
                                       FROM 
                                          tr_product_group 
                                       ORDER BY 
                                          i_product_group 
                                       ");
                  if ($queryxx->num_rows() > 0){
                     $datadetail = array();
                     $hasilxx=$queryxx->result();
                     foreach ($hasilxx as $rowxx) {
                        $i_product_group = $rowxx->i_product_group;
                        $e_product_groupname = $rowxx->e_product_groupname;
                        // spb
                        $queryxx = $this->db->query(" 
                                             SELECT 
                                                SUM((ax.n_order*ax.v_unit_price-(((ax.n_order*ax.v_unit_price)/a.v_spb)*v_spb_discounttotal))) AS v_spb_gross 
                                             FROM 
                                                tm_spb a
                                             INNER JOIN 
                                                tm_spb_item ax ON (a.i_spb = ax.i_spb AND a.i_area=ax.i_area)
                                             INNER JOIN 
                                                tr_area b on(a.i_area=b.i_area)
                                             WHERE 
                                                a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                                                AND a.d_spb <= to_date('$dto','dd-mm-yyyy')
                                                AND a.i_area = '$rowarea' 
                                                AND a.f_spb_cancel='f'
                                                AND ax.i_product 
                                             IN 
                                             (
                                                SELECT 
                                                   i_product 
                                                FROM 
                                                   tr_product xx 
                                                INNER JOIN 
                                                   tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                                INNER JOIN 
                                                   tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                                WHERE 
                                                   zz.i_product_group='$i_product_group'
                                             ) 
                                          ");       
                        if ($queryxx->num_rows() > 0){
                           $hasilxx = $queryxx->row();
                           $nilaispb = $hasilxx->v_spb_gross;
                        }else
                           $nilaispb = 0;
                           $datanilaispb[] = array( 
                                                   'i_product_group'=> $i_product_group,
                                                   'nilaispb'=> $nilaispb
                                             );
                        // sj
                        $queryxx = $this->db->query(" 
                                             SELECT 
                                                SUM((ax.n_deliver*ax.v_unit_price-(((ax.n_deliver*ax.v_unit_price)/a.v_nota_gross)*a.v_nota_discounttotal))) AS v_sj_gross 
                                             FROM 
                                                tm_nota a
                                             INNER JOIN 
                                                tm_nota_item ax ON (a.i_sj=ax.i_sj AND a.i_area=ax.i_area)
                                             INNER JOIN 
                                                tr_area b on(a.i_area=b.i_area)
                                             WHERE 
                                                a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                                                AND a.d_sj <= to_date('$dto','dd-mm-yyyy')
                                                AND a.f_nota_cancel='f' 
                                                AND a.i_area = '$rowarea'
                                                AND ax.i_product 
                                             IN 
                                             ( 
                                                SELECT 
                                                   i_product 
                                                FROM 
                                                   tr_product xx 
                                                INNER JOIN 
                                                   tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                                INNER JOIN 
                                                   tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                                WHERE 
                                                   zz.i_product_group='$i_product_group'
                                             )
                                          ");
                        if ($queryxx->num_rows() > 0){
                           $hasilxx = $queryxx->row();
                           $nilaisj = $hasilxx->v_sj_gross;
                        }
                        else
                           $nilaisj = 0;
                           $datanilaisj[] = array( 
                              'i_product_group'=> $i_product_group,
                              'nilaisj'=> $nilaisj
                           );
                        
                        // nota
                        $queryxx = $this->db->query(" 
                                             SELECT 
                                                SUM((ax.n_deliver*ax.v_unit_price-(((ax.n_deliver*ax.v_unit_price)/a.v_nota_gross)*v_nota_discounttotal))) AS v_nota_gross 
                                             FROM 
                                                tm_nota a
                                             INNER JOIN 
                                                tm_nota_item ax ON (a.i_nota=ax.i_nota AND a.i_area=ax.i_area)
                                             INNER JOIN 
                                                tr_area b on(a.i_area=b.i_area)
                                             WHERE 
                                                a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                                                AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
                                                AND a.f_nota_cancel='f' 
                                                AND NOT a.i_nota isnull 
                                                AND a.i_area = '$rowarea'
                                                AND ax.i_product 
                                             IN 
                                             ( 
                                                SELECT 
                                                   i_product 
                                                FROM 
                                                   tr_product xx 
                                                INNER JOIN 
                                                   tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                                INNER JOIN 
                                                   tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                                WHERE 
                                                   zz.i_product_group='$i_product_group'
                                             )
                                           ");
                                
                        if ($queryxx->num_rows() > 0){
                           $hasilxx = $queryxx->row();
                           $nilainota = $hasilxx->v_nota_gross;
                        }
                        else
                           $nilainota = 0;
                           $datanilainota[] = array( 
                              'i_product_group'=> $i_product_group,
                              'nilainota'=> $nilainota
                           );
                     } 
                  }
                  $outputdata[] = array(	'i_area'    => $rowarea,	
                                          'namaarea'  => $namaarea,	
                                          'nilaispb'  => $datanilaispb,
                                          'nilaisj'   => $datanilaisj,
                                          'nilainota' => $datanilainota
                                       );
                  $datanilaispb  = array();					
                  $datanilaisj   = array();
                  $datanilainota = array();
               } // end jika berdasarkan group brg
               else {
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          SUM(a.v_spb-a.v_spb_discounttotal) AS v_spb_gross 
                                       FROM 
                                          tm_spb a
                                       INNER JOIN 
                                          tr_area b on(a.i_area=b.i_area)
                                       WHERE 
                                          a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                                          AND a.d_spb <= to_date('$dto','dd-mm-yyyy')
                                          AND a.i_area = '$rowarea'
                                          AND a.f_spb_cancel='f' 
                                       ");
                          
                  if ($queryxx->num_rows() > 0){
                     $hasilxx = $queryxx->row();
                     $nilaispb = $hasilxx->v_spb_gross;
                  }
                  else
                     $nilaispb = 0;
                  
                  // sj
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          SUM(a.v_nota_netto) AS v_sj_gross 
                                       FROM 
                                          tm_nota a
                                       INNER JOIN 
                                          tr_area b ON (a.i_area=b.i_area)
                                       WHERE 
                                          a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                                          AND a.d_sj <= to_date('$dto','dd-mm-yyyy')
                                          and a.f_nota_cancel='f' 
                                          AND a.i_area = '$rowarea' 
                                       ");
                          
                  if ($queryxx->num_rows() > 0){
                     $hasilxx = $queryxx->row();
                     $nilaisj = $hasilxx->v_sj_gross;
                  }
                  else
                     $nilaisj = 0;
                  
                  // nota
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          SUM(a.v_nota_netto) AS v_nota_gross 
                                       FROM 
                                          tm_nota a
                                       INNER JOIN 
                                          tr_area b ON (a.i_area=b.i_area)
                                       WHERE 
                                          a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                                          AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
                                          AND a.f_nota_cancel='f' 
                                          AND not a.i_nota isnull 
                                          AND a.i_area = '$rowarea' 
                                       ");
                          
                  if ($queryxx->num_rows() > 0){
                     $hasilxx = $queryxx->row();
                     $nilainota = $hasilxx->v_nota_gross;
                  }
                  else
                     $nilainota = 0;
                     $outputdata[] = array(	'i_area'=> $rowarea,	
                                             'namaarea'=> $namaarea,	
                                             'nilaispb'=> $nilaispb,
                                             'nilaisj'=> $nilaisj,
                                             'nilainota'=> $nilainota
                                          );
               }
            }
         }
      }else{
         $sqlnya = $this->db->query(" 
                              SELECT 
                                 a.* 
                              FROM 
                                 tm_user_area a, 
                                 tr_area b 
                              WHERE 
                                 a.i_area = b.i_area 
                                 AND a.i_user = '$userid' 
                                 AND a.i_area <> 'XX' 
                              ORDER BY 
                                 a.i_area 
                           ");
         
         if ($sqlnya->num_rows() > 0){
            $hasilnya=$sqlnya->result();
            foreach ($hasilnya as $rownya) {
               // query ambil nama area
               $queryxx = $this->db->query(" 
                                    SELECT 
                                       e_area_name 
                                    FROM 
                                       tr_area 
                                    WHERE 
                                       i_area = '".$rownya->i_area."' 
                                    ");
                       
               if ($queryxx->num_rows() > 0){
                  $hasilxx = $queryxx->row();
                  $namaarea = $hasilxx->e_area_name;
               }
               else
                  $namaarea = '';
               // 26-05-2015 pisah berdasarkan group brg.
               if ($is_groupbrg == '1') {
                  $datanilaispb  = array();
                  $datanilaisj   = array();
                  $datanilainota = array();
                  // ambil grup brg dari tr_product_group, relasi ke tr_product_type
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          i_product_group, 
                                          e_product_groupname 
                                       FROM 
                                          tr_product_group 
                                       ORDER BY 
                                          i_product_group 
                                       ");
                  if ($queryxx->num_rows() > 0){
                     $datadetail = array();
                     $hasilxx=$queryxx->result();
                     foreach ($hasilxx as $rowxx) {
                        $i_product_group = $rowxx->i_product_group;
                        $e_product_groupname = $rowxx->e_product_groupname;
                        $queryxx = $this->db->query(" 
                                             SELECT 
                                                SUM((ax.n_order*ax.v_unit_price-(((ax.n_order*ax.v_unit_price)/a.v_spb)*v_spb_discounttotal))) AS v_spb_gross 
                                             FROM 
                                                tm_spb a
                                             INNER JOIN 
                                                tm_spb_item ax ON (a.i_spb = ax.i_spb AND a.i_area = ax.i_area)
                                             INNER JOIN 
                                                tr_area b ON (a.i_area=b.i_area)
                                             WHERE 
                                                a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                                                AND a.d_spb <= to_date('$dto','dd-mm-yyyy')
                                                AND a.i_area = '".$rownya->i_area."' 
                                                AND a.f_spb_cancel='f'
                                                AND ax.i_product 
                                             IN 
                                             ( 
                                                SELECT 
                                                   i_product 
                                                FROM 
                                                   tr_product xx 
                                                INNER JOIN 
                                                   tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                                INNER JOIN 
                                                   tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                                WHERE 
                                                   zz.i_product_group='$i_product_group'
                                             ) 
                                          ");
                                
                        if ($queryxx->num_rows() > 0){
                           $hasilxx = $queryxx->row();
                           $nilaispb = $hasilxx->v_spb_gross;
                        }
                        else
                           $nilaispb = 0;
                           $datanilaispb[] = array( 
                              'i_product_group'=> $i_product_group,
                              'nilaispb'=> $nilaispb
                           );
                        // sj
                        $queryxx = $this->db->query(" 
                                             SELECT 
                                                SUM((ax.n_deliver*ax.v_unit_price-(((ax.n_deliver*ax.v_unit_price)/a.v_nota_gross)*a.v_nota_discounttotal))) AS v_sj_gross 
                                             FROM 
                                                tm_nota a
                                             INNER JOIN 
                                                tm_nota_item ax ON (a.i_sj=ax.i_sj AND a.i_area=ax.i_area)
                                             INNER JOIN 
                                                tr_area b ON (a.i_area=b.i_area)
                                             WHERE 
                                                a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                                                AND a.d_sj <= to_date('$dto','dd-mm-yyyy')
                                                AND a.f_nota_cancel='f' 
                                                AND a.i_area = '".$rownya->i_area."'
                                                AND ax.i_product 
                                             IN 
                                             (
                                                SELECT 
                                                   i_product 
                                                FROM 
                                                   tr_product xx 
                                                INNER JOIN 
                                                   tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                                INNER JOIN 
                                                   tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                                WHERE 
                                                   zz.i_product_group='$i_product_group'
                                             )
                                          ");
                                
                        if ($queryxx->num_rows() > 0){
                           $hasilxx = $queryxx->row();
                           $nilaisj = $hasilxx->v_sj_gross;
                        }
                        else
                           $nilaisj = 0;
                           $datanilaisj[] = array( 
                                                'i_product_group'=> $i_product_group,
                                                'nilaisj'=> $nilaisj
                                             );
                        
                        // nota
                        $queryxx = $this->db->query(" 
                                             SELECT 
                                                SUM((ax.n_deliver*ax.v_unit_price-(((ax.n_deliver*ax.v_unit_price)/a.v_nota_gross)*v_nota_discounttotal))) AS v_nota_gross 
                                             FROM 
                                                tm_nota a
                                             INNER JOIN 
                                                tm_nota_item ax ON (a.i_nota=ax.i_nota AND a.i_area=ax.i_area)
                                             INNER JOIN 
                                                tr_area b ON (a.i_area=b.i_area)
                                             WHERE 
                                                a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                                                AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
                                                AND a.f_nota_cancel='f' 
                                                AND NOT a.i_nota isnull 
                                                AND a.i_area = '".$rownya->i_area."'
                                                AND ax.i_product 
                                             IN 
                                             ( 
                                                SELECT 
                                                   i_product 
                                                FROM 
                                                   tr_product xx 
                                                INNER JOIN 
                                                   tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                                INNER JOIN 
                                                   tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                                WHERE 
                                                   zz.i_product_group='$i_product_group'
                                             )
                                          ");
                                
                        if ($queryxx->num_rows() > 0){
                           $hasilxx = $queryxx->row();
                           $nilainota = $hasilxx->v_nota_gross;
                        }
                        else
                           $nilainota = 0;
                           $datanilainota[] = array( 
                              'i_product_group'=> $i_product_group,
                              'nilainota'=> $nilainota
                           );
                     } // end foreach
                  }
                  $outputdata[] = array(	'i_area'=> $rownya->i_area,	
                                          'namaarea'=> $namaarea,	
                                          'nilaispb'=> $datanilaispb,
                                          'nilaisj'=> $datanilaisj,
                                          'nilainota'=> $datanilainota
                                       );
                  $datanilaispb = array();					
                  $datanilaisj = array();
                  $datanilainota = array();					
               } // end jika berdasarkan group brg
               else {
                  // spb
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          SUM(a.v_spb-a.v_spb_discounttotal) AS v_spb_gross 
                                       FROM 
                                          tm_spb a
                                       INNER JOIN 
                                          tr_area b ON (a.i_area=b.i_area)
                                       WHERE 
                                          a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                                          AND a.d_spb <= to_date('$dto','dd-mm-yyyy')
                                          AND a.i_area = '".$rownya->i_area."' 
                                          AND a.f_spb_cancel='f' 
                                       ");
                          
                  if ($queryxx->num_rows() > 0){
                     $hasilxx = $queryxx->row();
                     $nilaispb = $hasilxx->v_spb_gross;
                  }
                  else
                     $nilaispb = 0;
                  // sj
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          SUM(a.v_nota_netto) AS v_sj_gross 
                                       FROM 
                                          tm_nota a
                                       INNER JOIN 
                                          tr_area b ON (a.i_area=b.i_area)
                                       WHERE 
                                          a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                                          AND a.d_sj <= to_date('$dto','dd-mm-yyyy')
                                          AND a.f_nota_cancel='f' 
                                          AND a.i_area = '".$rownya->i_area."' 
                                       ");
                          
                  if ($queryxx->num_rows() > 0){
                     $hasilxx = $queryxx->row();
                     $nilaisj = $hasilxx->v_sj_gross;
                  }
                  else
                     $nilaisj = 0;
                  // nota
                  $queryxx = $this->db->query(" 
                                       SELECT 
                                          SUM(a.v_nota_netto) AS v_nota_gross 
                                       FROM 
                                          tm_nota a
                                       INNER JOIN 
                                          tr_area b ON (a.i_area=b.i_area)
                                       WHERE 
                                          a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                                          AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
                                          AND a.f_nota_cancel='f' 
                                          AND not a.i_nota isnull 
                                          AND a.i_area = '".$rownya->i_area."' 
                                       ");
                          
                  if ($queryxx->num_rows() > 0){
                     $hasilxx = $queryxx->row();
                     $nilainota = $hasilxx->v_nota_gross;
                  }
                  else
                     $nilainota = 0;  
                     $outputdata[] = array(	'i_area'=> $rownya->i_area,	
                                             'namaarea'=> $namaarea,	
                                             'nilaispb'=> $nilaispb,
                                             'nilaisj'=> $nilaisj,
                                             'nilainota'=> $nilainota
                                          );
               }
            }
         }
      }
      return $outputdata;
   }

   public function bacapersales($iarea,$dfrom,$dto,$is_groupbrg){
      $outputdata = array();
      $queryxx = $this->db->query(" 
                           SELECT 
                              e_area_name 
                           FROM 
                              tr_area 
                           WHERE 
                              i_area = '".$iarea."' 
                           ");
		if ($queryxx->num_rows() > 0){
			$hasilxx = $queryxx->row();
			$namaarea = $hasilxx->e_area_name;
		}else{
         $namaarea = '';
      }
		// 27-05-2015
		if ($is_groupbrg == '2')
			$datagrup = '';
		else {
			// ambil grup brg dari tr_product_group, relasi ke tr_product_type
			$queryxx = $this->db->query(" 
                              SELECT 
                                 i_product_group, 
                                 e_product_groupname 
                              FROM 
                                 tr_product_group 
                              ORDER BY 
                                 i_product_group 
                              ");
			if ($queryxx->num_rows() > 0){
				$datagrup = array();
				$hasilxx=$queryxx->result();
				foreach ($hasilxx as $rowxx) {
					$i_product_group = $rowxx->i_product_group;
					$e_product_groupname = $rowxx->e_product_groupname;
					$datagrup[] = array( 
										      'i_product_group'=> $i_product_group,
										      'e_product_groupname'=> $e_product_groupname,
										      'totalspb'=> 0,
										      'totalsj'=> 0,
										      'totalnota'=> 0
									      );
				}
			}
		}
			
		$sqlnya	= $this->db->query(" 
                           SELECT DISTINCT 
                              a.i_salesman, 
                              b.e_salesman_name 
                           FROM 
                              tm_nota a, 
                              tr_salesman b 
                           WHERE 
                              a.i_salesman = b.i_salesman
                              AND a.i_area = '$iarea' 
                              AND ((a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                              AND a.d_spb <= to_date('$dto','dd-mm-yyyy')) 
                              OR (a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                              AND a.d_sj <= to_date('$dto','dd-mm-yyyy')) 
                              OR (a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                              AND a.d_nota <= to_date('$dto','dd-mm-yyyy') ))
                              ORDER BY a.i_salesman 
                           ");
		if ($sqlnya->num_rows() > 0){
			$hasilnya=$sqlnya->result();
			foreach ($hasilnya as $rownya) {
            // non-grup
				if ($is_groupbrg == '2') { 
					// spb
					$queryxx = $this->db->query(" 
                                    SELECT 
                                       SUM(a.v_spb-a.v_spb_discounttotal) AS v_spb_gross 
                                    FROM 
                                       tm_spb a
                                    INNER JOIN 
                                       tr_area b ON (a.i_area=b.i_area)
                                    WHERE 
                                       a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                                       AND a.d_spb <= to_date('$dto','dd-mm-yyyy')
                                       AND a.i_area = '".$iarea."' AND a.i_salesman = '".$rownya->i_salesman."' 
                                       AND a.f_spb_cancel='f' 
                                    ");
							  
					if ($queryxx->num_rows() > 0){
						$hasilxx = $queryxx->row();
						$nilaispb = $hasilxx->v_spb_gross;
					}else{
                  $nilaispb = 0;
               }
					// sj
					$queryxx = $this->db->query(" 
                                    SELECT 
                                       SUM(a.v_nota_netto) AS v_sj_gross 
                                    FROM 
                                       tm_nota a
                                    INNER JOIN 
                                       tr_area b ON (a.i_area=b.i_area)
                                    WHERE 
                                       a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                                       AND a.d_sj <= to_date('$dto','dd-mm-yyyy')
						                     AND a.f_nota_cancel='f' 
                                       AND a.i_area = '".$iarea."' 
                                       AND a.i_salesman = '".$rownya->i_salesman."' 
                                    ");
							  
					if ($queryxx->num_rows() > 0){
						$hasilxx = $queryxx->row();
						$nilaisj = $hasilxx->v_sj_gross;
					}else{
                  $nilaisj = 0;
               }
					
					// nota
					$queryxx = $this->db->query(" 
                                    SELECT 
                                       SUM(a.v_nota_netto) AS v_nota_gross 
                                    FROM 
                                       tm_nota a
                                    INNER JOIN
                                       tr_area b ON (a.i_area=b.i_area)
						                  WHERE 
                                       a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                                       AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
                                       AND a.f_nota_cancel='f' 
                                       AND NOT a.i_nota isnull 
                                       AND a.i_area = '".$iarea."' 
                                       AND a.i_salesman = '".$rownya->i_salesman."' 
                                    ");
							  
					if ($queryxx->num_rows() > 0){
						$hasilxx = $queryxx->row();
						$nilainota = $hasilxx->v_nota_gross;
					}else{
                  $nilainota = 0;
               }
							  
					$outputdata[] = array(	'i_area'=> $iarea,	
											      'namaarea'=> $namaarea,	
											      'i_salesman'=> $rownya->i_salesman,	
											      'e_salesman_name'=> $rownya->e_salesman_name,	
											      'nilaispb'=> $nilaispb,
											      'nilaisj'=> $nilaisj,
											      'nilainota'=> $nilainota
											   );
				}else { // by grup brg
					$datanilaispb     = array();
					$datanilaisj      = array();
               $datanilainota    = array();
               
					// ambil grup brg dari tr_product_group, relasi ke tr_product_type
					$queryxx = $this->db->query(" 
                                    SELECT 
                                       i_product_group, 
                                       e_product_groupname 
                                    FROM 
                                       tr_product_group 
                                    ORDER BY 
                                       i_product_group 
                                    ");
					if ($queryxx->num_rows() > 0){
						$datadetail = array();
						$hasilxx=$queryxx->result();
						foreach ($hasilxx as $rowxx) {
							$i_product_group     = $rowxx->i_product_group;
							$e_product_groupname = $rowxx->e_product_groupname;
							
							// spb
							$queryxx = $this->db->query("  
                                          SELECT 
                                             SUM((ax.n_order*ax.v_unit_price-(((ax.n_order*ax.v_unit_price)/a.v_spb)*v_spb_discounttotal))) AS v_spb_gross 
                                          FROM 
                                             tm_spb a
                                          INNER JOIN 
                                             tm_spb_item ax ON (a.i_spb = ax.i_spb AND a.i_area=ax.i_area)
                                          INNER JOIN 
                                             tr_area b ON (a.i_area=b.i_area)
                                          WHERE 
                                             a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                                             AND a.d_spb <= to_date('$dto','dd-mm-yyyy')
                                             AND a.i_area = '".$iarea."' 
                                             AND a.i_salesman = '".$rownya->i_salesman."' 
                                             AND a.f_spb_cancel='f' 
                                             AND ax.i_product 
                                          IN 
									               ( 
                                             SELECT 
                                                i_product 
                                             FROM 
                                                tr_product xx 
                                             INNER JOIN 
                                                tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                             INNER JOIN 
                                                tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                             WHERE 
                                                zz.i_product_group='$i_product_group'
									               )
									               ");
							  
							if ($queryxx->num_rows() > 0){
								$hasilxx = $queryxx->row();
								$nilaispb = $hasilxx->v_spb_gross;
							}else{
                        $nilaispb = 0;
                     }
							
							$datanilaispb[] = array( 
									                  'i_product_group' => $i_product_group,
									                  'nilaispb'        => $nilaispb
								                  );
							// sj
							$queryxx = $this->db->query(" 
                                          SELECT 
                                             SUM((ax.n_deliver*ax.v_unit_price-(((ax.n_deliver*ax.v_unit_price)/a.v_nota_gross)*a.v_nota_discounttotal))) AS v_sj_gross 
                                          FROM 
                                             tm_nota a
                                          INNER JOIN 
                                             tm_nota_item ax ON (a.i_sj=ax.i_sj AND a.i_area=ax.i_area)
                                          INNER JOIN 
                                             tr_area b ON (a.i_area=b.i_area)
                                          WHERE 
                                             a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                                             AND a.d_sj <= to_date('$dto','dd-mm-yyyy')
								                     AND a.f_nota_cancel='f' 
                                             AND a.i_area = '".$iarea."' 
                                             AND a.i_salesman = '".$rownya->i_salesman."'
                                             AND ax.i_product 
                                          IN 
									               ( 
                                             SELECT 
                                                i_product 
                                             FROM 
                                                tr_product xx 
                                             INNER JOIN 
                                                tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                             INNER JOIN 
                                                tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                             WHERE 
                                                zz.i_product_group='$i_product_group'
									               )
								                  ");
									  
							if ($queryxx->num_rows() > 0){
								$hasilxx = $queryxx->row();
								$nilaisj = $hasilxx->v_sj_gross;
							}else{
                        $nilaisj = 0;
                     }
							
							$datanilaisj[] = array( 
									'i_product_group'=> $i_product_group,
									'nilaisj'=> $nilaisj
								);
							
							// nota
							$queryxx = $this->db->query(" 
                                          SELECT 
                                             SUM((ax.n_deliver*ax.v_unit_price-(((ax.n_deliver*ax.v_unit_price)/a.v_nota_gross)*v_nota_discounttotal))) AS v_nota_gross 
                                          FROM 
                                             tm_nota a
                                          INNER JOIN 
                                             tm_nota_item ax ON (a.i_nota=ax.i_nota AND a.i_area=ax.i_area)
                                          INNER JOIN 
                                             tr_area b ON (a.i_area=b.i_area)
								                  WHERE 
                                             a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
                                             AND a.d_nota <= to_date('$dto','dd-mm-yyyy')
                                             AND a.f_nota_cancel='f' 
                                             AND NOT a.i_nota isnull 
                                             AND a.i_area = '".$iarea."' 
                                             AND a.i_salesman = '".$rownya->i_salesman."'
                                             AND ax.i_product 
                                          IN 
									               ( 
                                             SELECT 
                                                i_product 
                                             FROM 
                                                tr_product xx 
                                             INNER JOIN 
                                                tr_product_type yy ON xx.i_product_type = yy.i_product_type
                                             INNER JOIN 
                                                tr_product_group zz ON zz.i_product_group=yy.i_product_group
                                             WHERE 
                                                zz.i_product_group='$i_product_group'
									               )
								                  ");
									  
							if ($queryxx->num_rows() > 0){
								$hasilxx = $queryxx->row();
								$nilainota = $hasilxx->v_nota_gross;
							}else{
                        $nilainota = 0;
                     }
							
							$datanilainota[] = array( 
									                     'i_product_group'=> $i_product_group,
									                     'nilainota'=> $nilainota
								                     );
						} // end foreach
					}
					
					$outputdata[] = array(	'i_area'=> $iarea,	
											      'namaarea'=> $namaarea,	
											      'i_salesman'=> $rownya->i_salesman,	
											      'e_salesman_name'=> $rownya->e_salesman_name,	
											      'nilaispb'=> $datanilaispb,
											      'nilaisj'=> $datanilaisj,
											      'nilainota'=> $datanilainota
											   );
				} // end is_groupbrg = 2
			}
		}
		return $outputdata;
   }
}

/* End of file Mmaster.php */
