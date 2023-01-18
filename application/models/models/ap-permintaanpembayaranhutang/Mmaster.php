<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($folder,$i_menu,$dfrom,$dto){
        // $dfrom = date('Y-m-d', strtotime($dfrom));
        // $dto   = date('Y-m-d', strtotime($dto));
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
                                  SELECT
                                      i_bagian
                                  FROM
                                      tm_permintaan_pembayaranap
                                  WHERE
                                      i_status <> '5'
                                      and d_ppap between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$id_company'
                                      AND i_bagian IN (
                                          SELECT
                                              i_bagian
                                          FROM
                                              tr_departement_cover
                                          WHERE
                                              i_departement = '".$this->session->userdata('i_departement')."'
                                              AND username = '".$this->session->userdata('username')."'
                                              AND id_company = '$id_company')

                              ", FALSE);
        if ($this->session->userdata('i_departement')=='1') {
            $bagian = "";
        }else{
            if ($cek->num_rows()>0) {                
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        /*AND i_level = '".$this->session->userdata('i_level')."'*/
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$id_company')";
            }
        }

        
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT DISTINCT
                               0 as no,
                               a.id,
                               d.e_bagian_name,
                               a.i_ppap,
                               to_char(a.d_ppap, 'dd-mm-yyyy') as d_ppap,
                               b.e_supplier_name,
                               to_char(a.d_req_ppap, 'dd-mm-yyyy') as d_req_ppap,
                               a.v_total,
                               a.v_sisa,
                               a.e_remark,
                               a.i_status,
                               c.e_status_name,
                               '$i_menu' as i_menu,
                               '$folder' as folder,
                               '$dfrom' AS dfrom,
                               '$dto' AS dto,
                               c. label_color,
                               e.i_jenis_faktur
                            FROM
                                tm_permintaan_pembayaranap a 
                                INNER JOIN
                                   tr_supplier b 
                                   ON (b.i_supplier = a.i_supplier 
                                   AND a.id_company = b.id_company) 
                                INNER JOIN
                                   tr_status_document c 
                                   ON (c.i_status = a.i_status) 
                                INNER JOIN
                                   tr_bagian d 
                                   ON (a.i_bagian = d.i_bagian 
                                   AND a.id_company = d.id_company) 
                                LEFT JOIN
                                    tm_permintaan_pembayaranap_item e
                                    ON (a.id = e.id_ppap)
                            WHERE
                               a.d_ppap BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                               AND a.id_company = '$id_company' 
                               $bagian 
                            ORDER BY 
                               a.i_ppap desc
        ", FALSE);

        // $datatables->edit('i_status', function ($data) {
        //     return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        // });
          $datatables->edit('i_ppap', function ($data) {
            if ($data['i_status']=='9') {
                $data = '<p class="h2 text-danger">'.$data['i_ppap'].'</p>';
            }else{
                $data = $data['i_ppap'];
            }
            return $data;
          });

          $datatables->edit('v_total', function ($data) {
            $data = "Rp. ".number_format($data['v_total']);
            return $data;
          });

          $datatables->edit('v_sisa', function ($data) {
            $data = "Rp. ".number_format($data['v_sisa']);
            return $data;
          });
          
          $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
          });

        $datatables->add('action', function ($data) {
            $id        = trim($data['id']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $i_status      = $data['i_status'];
            $dfrom   = $data['dfrom'];
            $dto     = $data['dto'];
            $ijenis  = $data['i_jenis_faktur'];
            $data          = '';

            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$ijenis/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            //$data         .= "<a href=\"#\" title='Print' onclick='printx(\"$id\",\"$ibagian\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
           // if ($i_status != '6') {
                if (check_role($i_menu, 3)) {
                    if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                        $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$ijenis/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }

                if (check_role($i_menu, 7)) {
                    if ($i_status == '2') {
                        $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$ijenis/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }   

                if (check_role($i_menu, 5)) {
                    if ($i_status == '6') {
                        $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
                    } 
                }

                if (check_role($i_menu, 4)  && ($i_status=='1')) {
                    $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
                }
            //}
            return $data;
        });
            
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_jenis_faktur');
        $datatables->hide('e_bagian_name');
        return $datatables->generate();
    }
    
    public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function partner() {
        $company = $this->session->userdata('id_company');
        return $this->db->query(" 

                                  SELECT DISTINCT
                                     a.i_supplier,
                                     a.e_supplier_name 
                                  FROM
                                     (
                                        SELECT
                                          a.i_supplier,
                                          a.e_supplier_name,
                                          a.id_company,
                                          b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                            tm_notabtb b 
                                            ON a.i_supplier = b.i_supplier 
                                            AND a.id_company = b.id_company 

                                        UNION ALL
                                        SELECT
                                           a.i_supplier,
                                           a.e_supplier_name,
                                           a.id_company,
                                           b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                            tm_notamakloonbis2an b 
                                            ON a.i_supplier = b.i_partner 
                                            AND a.id_company = b.id_company 

                                        UNION ALL
                                        SELECT
                                            a.i_supplier,
                                            a.e_supplier_name,
                                            a.id_company,
                                            b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                            tm_notamakloonbordir b 
                                            ON a.i_supplier = b.i_partner 
                                            AND a.id_company = b.id_company 
                                        
                                        UNION ALL
                                        SELECT
                                            a.i_supplier,
                                            a.e_supplier_name,
                                            a.id_company,
                                            b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                            tm_notamakloonembosh b 
                                            on a.i_supplier = b.i_partner 
                                            AND a.id_company = b.id_company 

                                        UNION ALL
                                        SELECT
                                            a.i_supplier,
                                            a.e_supplier_name,
                                            a.id_company,
                                            b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                            tm_notamakloonjahit b 
                                            ON a.i_supplier = b.i_partner 
                                            AND a.id_company = b.id_company

                                        UNION ALL
                                        SELECT
                                            a.i_supplier,
                                            a.e_supplier_name,
                                            a.id_company,
                                            b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                            tm_notamakloonpacking b 
                                            ON a.i_supplier = b.i_partner 
                                            AND a.id_company = b.id_company 
                                        
                                        UNION ALL
                                        SELECT
                                            a.i_supplier,
                                            a.e_supplier_name,
                                            a.id_company,
                                            b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                            tm_notamakloonprint b 
                                            ON a.i_supplier = b.i_partner 
                                            AND a.id_company = b.id_company 
                                        
                                        UNION ALL
                                        SELECT
                                            a.i_supplier,
                                            a.e_supplier_name,
                                            a.id_company,
                                            b.i_status 
                                        FROM
                                          tr_supplier a 
                                          JOIN
                                              tm_notamakloonquilting b 
                                              ON a.i_supplier = b.i_partner 
                                              AND a.id_company = b.id_company 
                                      )
                                      AS a 
                                  WHERE
                                    a.id_company = '$company' 
                                    AND a.i_status = '11' 
                                  ORDER BY
                                    a.e_supplier_name
                                ", FALSE);
    }

    public function jenis() {
        return $this->db->query("
                                    SELECT
                                       i_jenis_faktur,
                                       e_jenis_faktur_name 
                                    FROM
                                       tr_jenis_faktur 
                                    WHERE
                                       i_type = '1'
                                    ORDER BY
                                       i_jenis_faktur 
                                ", FALSE);
    }

    public function runningnumber($thbl,$tahun,$ibagian) {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_ppap, 1, 4) AS kode 
            FROM tm_permintaan_pembayaranap 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'PPAP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_ppap, 11, 6)) AS max
            FROM
                tm_permintaan_pembayaranap
            WHERE to_char (d_ppap, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND substring(i_ppap, 1, 4) = '$kode'
            AND substring(i_ppap, 6, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 6){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "000001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function cek_kode($kode,$ibagian) {
        $this->db->select('i_ppap');
        $this->db->from('tm_permintaan_pembayaranap');
        $this->db->where('i_ppap', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode,$kodeold, $ibagian) {
        $this->db->select('i_ppap');
        $this->db->from('tm_permintaan_pembayaranap');
        $this->db->where('i_ppap', $kode);
        $this->db->where('i_ppap <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdetail($partner, $jenis, $jtawal, $jtakhir){
       
        if ($partner == 'semua') {
            $partner = "";
        } else {
            $partner = "and z.i_supplier = '$partner'";
        }
        $company = $this->session->userdata('id_company');
        return $this->db->query("
                                SELECT 
	                                z.no, z.id, z.i_nota, z.d_nota, z.i_supplier, z.jenis, 
	                                z.i_jenis, z.jatuh_tempo, z.saldo, z.v_total
                                FROM
                                	( 
                                	/*PEMBELIAN*/
                                	    SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		   SELECT 
                                			    'Faktur Pembelian' AS jenis, '1' AS i_jenis,
                                			    a.id, a.i_supplier, a.i_nota, a.d_nota,
                                			    a.d_nota::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_sisa, a.v_total
                                		    FROM 
                                			    tm_notabtb a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_supplier = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                                    AND a.id_company = '".$this->session->userdata('id_company')."'
                                                    AND i_jenis_faktur = '$jenis'
                                		    ))
                                	/*BIS2AN*/	
                                	    UNION ALL
                                	    SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		   SELECT 
                                			    'Faktur Makloon Bis2an' AS jenis, '2' AS i_jenis,
                                			    a.id, a.i_partner AS i_supplier, a.i_document AS i_nota, a.d_document AS d_nota,
                                			    a.d_document::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_total_sisa AS v_sisa, a.v_total
                                		    FROM 
                                			    tm_notamakloonbis2an a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_partner = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                               AND i_jenis_faktur = '$jenis'
                                		    ))
                                    /*JAHIT*/
                                        UNION ALL  
                                        SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		   SELECT 
                                			    'Faktur Makloon Jahit' AS jenis, '3' AS i_jenis,
                                			    a.id, a.i_partner AS i_supplier, a.i_document AS i_nota, a.d_document AS d_nota,
                                			    a.d_document::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_total_sisa AS v_sisa, a.v_total
                                		    FROM 
                                			    tm_notamakloonjahit a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_partner = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                                    AND i_jenis_faktur = '$jenis'
                                		    ))
                                    /*PACKING*/
                                        UNION ALL
                                        SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		    SELECT 
                                			    'Faktur Makloon Packing' AS jenis, '4' AS i_jenis,
                                			    a.id, a.i_partner AS i_supplier, a.i_document AS i_nota, a.d_document AS d_nota,
                                			    a.d_document::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_total_sisa AS v_sisa, a.v_total
                                		    FROM 
                                			    tm_notamakloonpacking a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_partner = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                                    AND i_jenis_faktur = '$jenis'
                                		    ))
                                    /*BORDIR*/
                                        UNION ALL
                                        SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		   SELECT 
                                			    'Faktur Makloon Bordir' as jenis, '5' AS i_jenis,
                                			    a.id, a.i_partner AS i_supplier, a.i_document AS i_nota, a.d_document AS d_nota,
                                			    a.d_document::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_total_sisa AS v_sisa, a.v_total
                                		    FROM 
                                			    tm_notamakloonbordir a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_partner = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                                    AND i_jenis_faktur = '$jenis'
                                		    ))
                                    /*PRINT*/
                                        UNION ALL
                                        SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		   SELECT 
                                			    'Faktur Makloon Print' AS jenis, '6' AS i_jenis,
                                			    a.id, a.i_partner AS i_supplier, a.i_document AS i_nota, a.d_document AS d_nota,
                                			    a.d_document::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_total_sisa AS v_sisa, a.v_total
                                		    FROM 
                                			    tm_notamakloonprint a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_partner = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                                    AND i_jenis_faktur = '$jenis'
                                		    ))
                                	/*EMBOSH*/
                                        UNION ALL   
                                        SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		   SELECT 
                                			    'Faktur Makloon Embosh' AS jenis, '7' AS i_jenis,
                                			    a.id, a.i_partner AS i_supplier, a.i_document AS i_nota, a.d_document AS d_nota,
                                			    a.d_document::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_total_sisa AS v_sisa, a.v_total
                                		    FROM 
                                			    tm_notamakloonembosh a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_partner = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                                    AND i_jenis_faktur = '$jenis'
                                		    ))
                                    /*QUILTING*/
                                        UNION ALL
                                        SELECT 
                                		    ROW_NUMBER() OVER (ORDER BY i_nota) AS no,
                                		    id, i_nota, to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                		    jenis, i_jenis, i_supplier, to_char(jatuh_tempo, 'dd-mm-yyyy') AS jatuh_tempo,
                                		    v_sisa AS saldo, v_total
                                	    FROM
                                		(
                                		    SELECT 
                                			    'Faktur Makloon Quilting' AS jenis, '8' AS i_jenis,
                                			    a.id, a.i_partner AS i_supplier, a.i_document AS i_nota, a.d_document AS d_nota,
                                			    a.d_document::date + (CASE WHEN c.n_supplier_toplength is null
                                			    THEN 0::int ELSE c.n_supplier_toplength::int END) AS jatuh_tempo,
                                			    a.v_total_sisa AS v_sisa, a.v_total
                                		    FROM 
                                			    tm_notamakloonquilting a 
                                			INNER JOIN 
                                				tr_supplier c
                                				ON (a.i_partner = c.i_supplier 
                                				AND a.id_company = c.id_company)
                                		    WHERE 
                                			    a.i_status = '11'
                                			    AND a.id_company = '".$this->session->userdata('id_company')."'
                                		) AS x
                                	    WHERE
                                		    v_sisa > 0 
                                		    AND jatuh_tempo BETWEEN to_date('$jtawal','dd-mm-yyyy') AND to_date('$jtakhir','dd-mm-yyyy')
                                		    AND (id NOT IN 
                                		    ( 
                                		        SELECT 
                                		    	    a.id_nota
                                		        FROM 
                                		    	    tm_permintaan_pembayaranap_item a
                                		    	    INNER JOIN 
                                		    	    	tm_permintaan_pembayaranap b
                                		    	    	ON (a.id_ppap = b.id)
                                		        WHERE 
                                		    	    i_status = '6'
                                                    AND i_jenis_faktur = '$jenis'
                                		    ))
                                	) z
                                WHERE  
                                    i_jenis = '$jenis'
                                    $partner
                                ", FALSE);
    }

    public function estatus($istatus) {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id,$istatus) {
        if ($istatus=='6') {
            $data = array(
                          'i_status'  => $istatus,
                          'e_approve' => $this->session->userdata('username'),
                          'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                          'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_permintaan_pembayaranap');
        return $this->db->get()->row()->id+1;
    }

    public function insertheader($id_company, $id, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark) {
        // i_pembayaran, tanggal, partner, permintaan_pembayaran, v_total, v_sisa
        $data = array(
                      'id_company'       => $id_company,
                      'id'               => $id,
                      'i_bagian'         => $ibagian,
                      'i_ppap'           => $ippap,
                      'd_ppap'           => $dppap,
                      'i_supplier'       => $partner,
                      'd_req_ppap'       => $drppap,
                      'v_total'          => $jumlah,
                      'v_sisa'           => $jumlah,
                      'e_remark'         => $remark,
        );
        $this->db->insert('tm_permintaan_pembayaranap', $data);
    }

    public function insertdetail($id_nota,$id,$ijenis,$edesc) {
        $data = array(
                      'id_company'       => $this->session->userdata('id_company'),
                      'id_nota'          => $id_nota,
                      'id_ppap'          => $id,
                      'i_jenis_faktur'   => $ijenis,
                      'e_remark'         => $edesc,
        );
        $this->db->insert('tm_permintaan_pembayaranap_item', $data);
    }

    public function baca($id){
        $query = $this->db->query("
                                    select
                                       0 as no,
                                       a.id,
                                       a.i_bagian,
                                       a.i_ppap,
                                       to_char(a.d_ppap, 'dd-mm-yyyy') as d_ppap,
                                       b.i_supplier,
                                       to_char(a.d_req_ppap, 'dd-mm-yyyy') as d_req_ppap,
                                       a.v_total,
                                       a.v_sisa,
                                       a.e_remark,
                                       a.i_status,
                                       c.e_status_name,
                                       e.i_jenis_faktur,
                                       f.e_jenis_faktur_name,
                                       d.e_bagian_name
                                    from
                                       tm_permintaan_pembayaranap a 
                                       inner join
                                          tr_supplier b 
                                          on (b.i_supplier = a.i_supplier 
                                          and a.id_company = b.id_company) 
                                       inner join
                                          tr_status_document c 
                                          on (c.i_status = a.i_status) 
                                       inner join
                                          tr_bagian d 
                                          on (a.i_bagian = d.i_bagian) 
                                       left join
                                          tm_permintaan_pembayaranap_item e
                                          on (a.id = e.id_ppap)
                                       inner join 
                                          tr_jenis_faktur f
                                          on (e.i_jenis_faktur = f.i_jenis_faktur)
                                    where
                                       a.id = '$id'
                                ", false);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function getdetailedit($id, $ijenis){
        return $this->db->query("
                                SELECT 
	                                ROW_NUMBER() OVER (ORDER BY i_nota) AS no, d.id, d.id_nota,
	                                a.i_nota, to_char(a.d_nota, 'dd-mm-yyyy') AS d_nota,
	                                e.e_jenis_faktur_name as jenis, d.i_jenis_faktur as i_jenis,
	                                to_char(a.d_nota::date + (CASE WHEN c.n_supplier_toplength isnull 
	                                THEN 0::int ELSE c.n_supplier_toplength::int END),'dd-mm-yyyy') as jatuh_tempo,
	                                a.v_sisa AS saldo, a.v_total, d.e_remark
                                FROM 
                                	(
                                		/*PEMBELIAN*/
                                		SELECT 
                                			id, i_supplier, i_nota, d_nota, v_sisa, v_total, id_company, '1' as i_jenis
                                		FROM
                                			tm_notabtb 
                                		/*BIS2AN*/
                                		UNION ALL
                                		SELECT 
                                			id, i_partner AS i_supplier, i_document AS i_nota, 
                                			d_document AS d_nota, v_total_sisa AS v_sisa, v_total, id_company,
                                			'2' as i_jenis
                                		FROM
                                			tm_notamakloonbis2an
                                		/*QUILTING*/
                                		UNION ALL
                                		SELECT 
                                			id, i_partner AS i_supplier, i_document AS i_nota, 
                                			d_document AS d_nota, v_total_sisa AS v_sisa, v_total, id_company,
                                			'8' as i_jenis
                                		FROM
                                			tm_notamakloonquilting 
                                		/*PRINT*/
                                		UNION ALL
                                		SELECT 
                                			id, i_partner AS i_supplier, i_document AS i_nota, 
                                			d_document AS d_nota, v_total_sisa AS v_sisa, v_total, id_company,
                                			'6' as i_jenis
                                		FROM
                                			tm_notamakloonprint 
                                		/*BORDIR*/
                                		UNION ALL
                                		SELECT 
                                			id, i_partner AS i_supplier, i_document AS i_nota, 
                                			d_document AS d_nota, v_total_sisa AS v_sisa, v_total, id_company,
                                			'5' as i_jenis
                                		FROM
                                			tm_notamakloonbordir 
                                		/*EMBOSH*/
                                		UNION ALL
                                		SELECT 
                                			id, i_partner AS i_supplier, i_document AS i_nota, 
                                			d_document AS d_nota, v_total_sisa AS v_sisa, v_total, id_company,
                                			'7' as i_jenis
                                		FROM
                                			tm_notamakloonembosh
                                		/*JAHIT*/
                                		UNION ALL
                                		SELECT 
                                			id, i_partner AS i_supplier, i_document AS i_nota, 
                                			d_document AS d_nota, v_total_sisa AS v_sisa, v_total, id_company,
                                			'3' as i_jenis
                                		FROM
                                			tm_notamakloonjahit
                                		/*PACKING*/
                                		UNION ALL
                                		SELECT 
                                			id, i_partner AS i_supplier, i_document AS i_nota, 
                                			d_document AS d_nota, v_total_sisa AS v_sisa, v_total, id_company,
                                			'4' as i_jenis
                                		FROM
                                			tm_notamakloonpacking
                                	)a
                                	INNER JOIN 
                                		tr_supplier c 
                                		ON (a.i_supplier = c.i_supplier
                                		AND a.id_company = c.id_company)
                                	INNER JOIN 
                                		tm_permintaan_pembayaranap_item d
                                		ON (d.id_nota = a.id)
                                	INNER JOIN 
                                		tr_jenis_faktur e
                                		ON (d.i_jenis_faktur = e.i_jenis_faktur)
                                WHERE
                                	d.id_ppap = '$id'
                                	AND a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.id IN 
                                	(
                                	  SELECT
                                		id_nota 
                                	  FROM
                                		tm_permintaan_pembayaranap_item a
                                		LEFT JOIN 
                                			tm_permintaan_pembayaranap b
                                			ON (a.id_ppap = b.id)
                                	)
                                	AND a.i_jenis = '$ijenis'
                                ", FALSE);
    }

    public function updateheader($id_company, $id, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark) {
        $dupdate = current_datetime();
        $data = array(
                      'i_bagian'         => $ibagian,
                      'i_ppap'           => $ippap,
                      'd_ppap'           => $dppap,
                      'i_supplier'       => $partner,
                      'd_req_ppap'       => $drppap,
                      'v_total'          => $jumlah,
                      'v_sisa'           => $jumlah,
                      'e_remark'         => $remark,
                      'd_update'         => $dupdate,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    function deletedetail($id) {
         $this->db->query("DELETE FROM tm_permintaan_pembayaranap_item WHERE id_ppap='$id'");
    }

    public function change($kode){
      $data = array(
          'i_status_dokumen'    => '3'
      );

      $this->db->where('i_pembayaran', $kode);
      $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    public function reject($kode){
      $data = array(
          'i_status_dokumen'    => '4'
      );

      $this->db->where('i_pembayaran', $kode);
      $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    function cek_approve($id_ppap, $id_nota) {
       $id_company = $this->session->userdata('id_company');
        return $this->db->query("
            select /*a.id, a.i_nota, b.id_ppap*/ c.i_ppap from tm_notabtb a
            inner join tm_permintaan_pembayaranap_item b on (a.id = b.id_nota)
            inner join tm_permintaan_pembayaranap c on (b.id_ppap = c.id)
            where b.id_ppap <> '$id_ppap' and b.id_nota = '$id_nota' and c.i_status = '6' 
            and c.id_company='$id_company' limit 1
        ", false);
    }


    public function approve($id){
      $now = date("Y-m-d");
      $data = array(
          'i_status'    => '6',
          'd_approve' => $now
      );

      $this->db->where('id', $id);
      $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    function updatesaldo($i_nota,$ijenis) {

        if ($ijenis=="JNM0002") {
            $this->db->query("UPDATE  tm_notamakloonbis2an set v_sisa = 0 WHERE i_nota='$i_nota'");
        }else if ($ijenis=="JNM0006") {
            $this->db->query("UPDATE  tm_notamakloonjahit set v_sisa = 0 WHERE i_nota='$i_nota'");
        } else if ($ijenis=="JNM0007") {
            $this->db->query("UPDATE  tm_notamakloonpacking set v_sisa = 0 WHERE i_nota='$i_nota'");
        } else if ($ijenis=="KTG0001") {
            $this->db->query("UPDATE  tm_notabtb set v_sisa = 0 WHERE i_nota='$i_nota'");
        }
        
    }

    public function cancel($ipembayaran, $partner){
        $data = array(
             'i_status_dokumen'    => '9',
        );
        
        $this->db->where('i_pembayaran', $ipembayaran);
        $this->db->where('partner', $partner);
        $this->db->update('tm_permintaan_pembayaranap', $data);
    }
}
/* End of file Mmaster.php */