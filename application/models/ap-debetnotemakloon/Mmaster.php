<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder, $dfrom, $dto){
      if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
      }else{
          $and   = "";
      }

      $cek = $this->db->query("
          SELECT
              i_bagian
          FROM
              tm_dn_ap_retur_makloon
          WHERE
              i_status <> '5'
              AND id_company = '".$this->session->userdata('id_company')."'
              $and
              AND i_bagian IN (
                  SELECT
                      i_bagian
                  FROM
                      tr_departement_cover
                  WHERE
                      i_departement = '".$this->session->userdata('i_departement')."'
                      AND id_company = '".$this->session->userdata('id_company')."'
                      AND username = '".$this->session->userdata('username')."')

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
                      AND id_company = '".$this->session->userdata('id_company')."'
                      AND username = '".$this->session->userdata('username')."')";
          }
      }
      $datatables = new Datatables(new CodeigniterAdapter);
	    $datatables->query("
                          SELECT DISTINCT
                            '0' as no,
                          	a.id,
                          	a.i_document,
                          	to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                          	a.id_supplier,
                          	d.i_supplier,
                            d.e_supplier_name,
                            a.i_bagian,
                          	b.id_document_reff,
                          	c.i_document as i_document_reff,
                          	a.v_total,
                          	a.e_remark,
                          	a.i_status,
                          	e.e_status_name, 
                            e.label_color,
                            a.i_bagian_referensi,
                            '$i_menu' as i_menu,
                            '$folder' as folder,
                            '$dfrom' as dfrom,
                            '$dto' as dto
                          FROM
                          	tm_dn_ap_retur_makloon a
                          	LEFT JOIN 
                          		tm_dn_ap_retur_makloon_item b
                          		ON (a.id = b.id_document 
                          		AND a.id_company = b.id_company) 
                          	LEFT JOIN 
                          		(
                          		  SELECT 
                          			  id, 
                          			  i_document,
                          			  id_company
                          		  FROM
                          			  tm_retur_makloonaksesories
                          		  UNION ALL
                          		  SELECT
                          			  id,
                          			  i_document,
                          			  id_company
                          		  FROM 
                          			  tm_retur_makloonbb
                          		  UNION ALL
                          		  SELECT 
                          			  id,
                          			  i_document,
                          			  id_company
                          		  FROM 
                          			  tm_retur_makloonbp
                          		) c ON (b.id_document_reff = c.id
                          		AND b.id_company = c.id_company)
                          	INNER JOIN
                          		tr_supplier d
                          		ON (a.id_supplier = d.id
                          		AND a.id_company = d.id_company)
                          	INNER JOIN
                          		tr_status_document e
                              ON (a.i_status = e.i_status)
                          WHERE 
                            a.i_status <> '5'
                            AND a.id_company = '".$this->session->userdata('id_company')."'
                            $bagian
                          ORDER BY
                            a.i_document
		                    ", FALSE);

  		$datatables->edit('e_status_name', function ($data) {
        return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
      });

      $datatables->edit('v_total', function($data){
        return 'Rp. '.number_format($data['v_total'],2);
      });

      $datatables->add('action', function ($data) {
          $id         = trim($data['id']);
          $i_menu     = $data['i_menu'];
          $i_status   = $data['i_status'];
          $folder     = $data['folder'];
          $dfrom      = $data['dfrom'];
          $dto        = $data['dto'];
          $isupplier  = $data['id_supplier'];
          $ibagianreff= $data['i_bagian_referensi'];
          $data       = '';

          if(check_role($i_menu, 2)){
              $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$dfrom/$dto/$id/$isupplier/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
          }
          if (check_role($i_menu, 3)) {
              if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                  $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$dfrom/$dto/$id/$isupplier/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
              }
          }
          if (check_role($i_menu, 7)) {
              if ($i_status == '2') {
                  $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$dfrom/$dto/$id/$isupplier/$ibagianreff\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
              }
          }
          if (check_role($i_menu, 4) && ($i_status=='1')) {
              $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
          }
          return $data;
      });
      $datatables->hide('id');
      $datatables->hide('i_menu');
      $datatables->hide('i_status');
      $datatables->hide('folder');
      $datatables->hide('dfrom');
      $datatables->hide('dto');
      $datatables->hide('label_color');
      $datatables->hide('id');
      $datatables->hide('i_bagian');
      $datatables->hide('id_document_reff');
      $datatables->hide('i_supplier');
      $datatables->hide('id_supplier');
      $datatables->hide('i_bagian_referensi');

      return $datatables->generate();
	}

  public function bagian(){
      $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
      $this->db->where('username', $this->session->userdata('username'));
      $this->db->where('a.id_company', $this->session->userdata('id_company'));
      $this->db->order_by('e_bagian_name');
      return $this->db->get();
  }

  public function runningnumber($thbl,$tahun,$ibagian){
      $cek = $this->db->query("
          SELECT 
              substring(i_document, 1, 2) AS kode 
          FROM tm_dn_ap_retur_makloon
          WHERE i_status <> '5'
          AND i_bagian = '$ibagian'
          AND id_company = '".$this->session->userdata('id_company')."'
          ORDER BY id DESC");
      if ($cek->num_rows()>0) {
          $kode = $cek->row()->kode;
      }else{
          $kode = 'DN';
      }
      $query  = $this->db->query("
          SELECT
              max(substring(i_document, 9, 6)) AS max
          FROM
              tm_dn_ap_retur_makloon
          WHERE to_char (d_document, 'yyyy') >= '$tahun'
          AND i_status <> '5'
          AND i_bagian = '$ibagian'
          AND id_company = '".$this->session->userdata('id_company')."'
          AND substring(i_document, 1, 2) = '$kode'
          AND substring(i_document, 4, 2) = substring('$thbl',1,2)
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

  public function getsupplier(){
      return $this->db->query("
                                SELECT DISTINCT
                                	a.id_supplier,
                                	b.i_supplier, 
                                	b.e_supplier_name
                                FROM 
                                	( SELECT id_supplier, i_status, id_company, f_debet_nota_retur
                                	  FROM tm_retur_makloonaksesories
                                	  UNION ALL
                                	  SELECT id_supplier, i_status, id_company, f_debet_nota_retur
                                	  FROM tm_retur_makloonbb
                                	  UNION ALL
                                	  SELECT id_supplier, i_status, id_company, f_debet_nota_retur 
                                	  FROM tm_retur_makloonbp
                                	 ) a 
                                	 INNER JOIN 
                                		tr_supplier b
                                		ON (a.id_supplier = b.id
                                		AND a.id_company = b.id_company)
                                WHERE
                                	a.i_status = '6' 
                                  AND a.id_company = '".$this->session->userdata('id_company')."'
                                  AND a.f_debet_nota_retur = 'f'
                              ", FALSE);
  }

  public function getreferensi($isupplier) {
      return $this->db->query("
                                SELECT DISTINCT
                                	a.id_supplier,
                                  a.id,
                                  a.i_document,
                                  a.i_bagian,
                                  a.group
                                FROM 
                                  ( SELECT DISTINCT 
                                      a.id, 
                                      a.i_document, 
                                      a.id_supplier, 
                                      a.i_status, 
                                      a.id_company, 
                                      b.n_sisa, 
                                      a.f_debet_nota_retur, 
                                      a.i_bagian, 
                                      'gudang aksesoris' as group
                                    FROM tm_retur_makloonaksesories a
                                    INNER JOIN tm_retur_makloonaksesories_item b
                                      ON (a.id = b.id_document AND a.id_company = b.id_company)
                                    
                                    UNION ALL
                                    SELECT DISTINCT 
                                      a.id, 
                                      a.i_document, 
                                      a.id_supplier, 
                                      a.i_status, 
                                      a.id_company, 
                                      b.n_sisa, 
                                      a.f_debet_nota_retur, 
                                      a.i_bagian, 
                                      'gudang bahan baku' as group
                                    FROM tm_retur_makloonbb a
                                    INNER JOIN tm_retur_makloonbb_item b
                                      ON (a.id = b.id_document AND a.id_company = b.id_company)
                                    
                                    UNION ALL
                                    SELECT DISTINCT 
                                      a.id, 
                                      a.i_document, 
                                      a.id_supplier, 
                                      a.i_status, 
                                      a.id_company, 
                                      b.n_sisa, 
                                      a.f_debet_nota_retur, 
                                      a.i_bagian, 
                                      'gudang aksesoris packing' as group
                                    FROM tm_retur_makloonbp a
                                    INNER JOIN tm_retur_makloonbp_item b
                                      ON (a.id = b.id_document AND a.id_company = b.id_company)
                                  ) a 
                                WHERE
                                  	a.i_status = '6' 
                                  	AND a.id_company = '".$this->session->userdata('id_company')."'
                                  	AND a.id_supplier = '$isupplier'
                                    AND a.n_sisa > 0 
                                    AND a.f_debet_nota_retur = 'f'
                              ", FALSE);
  }

  public function getdetailreff($id, $idsupplier, $ibagian){
      return $this->db->query("
                              SELECT DISTINCT
                                	a.id_supplier,
                                	a.id,
                                  a.i_document,
                                  to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                	a.id_referensi_nota,
                                  d.i_document as i_faktur,
                                  to_char(d.d_document, 'dd-mm-yyyy') as d_faktur,
                                	a.id_material,
                                	b.i_material, 
                                	b.e_material_name, 
                                	b.i_satuan_code,
                                	c.e_satuan_name,
                                	a.n_quantity,
                                  a.n_sisa,
                                  d.v_price,
                                  a.i_bagian
                                FROM 
                                	( SELECT
                                       a.id,
                                       a.i_document,
                                       a.d_document,
                                       a.id_supplier,
                                       a.i_status,
                                       a.id_company,
                                       b.id_material,
                                       sum(b.n_quantity) as n_quantity,
                                       sum(b.n_sisa) as n_sisa,
                                       a.id_referensi_nota,
                                       a.i_bagian,
                                       a.f_debet_nota_retur 
                                    FROM
                                       tm_retur_makloonaksesories a 
                                       INNER JOIN
                                          tm_retur_makloonaksesories_item b 
                                          ON (a.id = b.id_document 
                                          AND a.id_company = b.id_company)
                                          GROUP BY
                                            a.id,
                                            a.i_document,
                                            a.d_document,
                                            a.id_supplier,
                                            a.i_status,
                                            a.id_company,
                                            b.id_material,
                                            n_quantity,
                                            n_sisa,
                                            a.id_referensi_nota,
                                            a.i_bagian,
                                            a.f_debet_nota_retur 
                                       UNION ALL
                                       SELECT
                                          a.id,
                                          a.i_document,
                                          a.d_document,
                                          a.id_supplier,
                                          a.i_status,
                                          a.id_company,
                                          b.id_material,
                                          sum(b.n_quantity) as n_quantity,
                                          sum(b.n_sisa) as n_sisa,
                                          a.id_referensi_nota,
                                          a.i_bagian,
                                          a.f_debet_nota_retur 
                                       FROM
                                          tm_retur_makloonbb a 
                                          INNER JOIN
                                             tm_retur_makloonbb_item b 
                                             ON (a.id = b.id_document 
                                             AND a.id_company = b.id_company) 
                                          GROUP BY
                                            a.id,
                                            a.i_document,
                                            a.d_document,
                                            a.id_supplier,
                                            a.i_status,
                                            a.id_company,
                                            b.id_material,
                                            a.id_referensi_nota,
                                            a.i_bagian,
                                            a.f_debet_nota_retur 
                                          
                                          UNION ALL
                                          SELECT
                                             a.id,
                                             a.i_document,
                                             a.d_document,
                                             a.id_supplier,
                                             a.i_status,
                                             a.id_company,
                                             b.id_material,
                                             sum(b.n_quantity) as n_quantity,
                                             sum(b.n_sisa) as n_sisa,
                                             a.id_referensi_nota,
                                             a.i_bagian,
                                             a.f_debet_nota_retur 
                                          FROM
                                             tm_retur_makloonbp a 
                                             INNER JOIN
                                                tm_retur_makloonbp_item b 
                                                ON (a.id = b.id_document 
                                                AND a.id_company = b.id_company) 
                                             GROUP BY
                                               a.id,
                                               a.i_document,
                                               a.d_document,
                                               a.id_supplier,
                                               a.i_status,
                                               a.id_company,
                                               b.id_material,
                                               a.id_referensi_nota,
                                               a.i_bagian,
                                               a.f_debet_nota_retur 
                                	 ) a 

                                	 INNER JOIN 
  				                        	tr_material b 
  				                        	ON (a.id_material = b.id
  				                        	AND a.id_company = b.id_company)
  				                         INNER JOIN 
  				                        	tr_satuan c
  				                        	ON (b.i_satuan_code = c.i_satuan_code
  				                        	AND c.id_company = b.id_company)
  				                         INNER JOIN
                                   ( SELECT a.id, a.i_document, a.d_document, a.id_company, b.id_material, b.v_price
                                      FROM tm_notamakloonbis2an a
                                      INNER JOIN 
                                      tm_notamakloonbis2an_item b
                                      ON (a.id = b.id_document
                                      AND a.id_company =b.id_company)

                                      UNION ALL
                                      SELECT a.id, a.i_document, a.d_document, a.id_company, b.id_material, b.v_price
                                      FROM tm_notamakloonquilting a
                                      INNER JOIN 
                                      tm_notamakloonquilting_item b
                                      ON (a.id = b.id_document
                                      AND a.id_company = b.id_company)
                                    ) d 
                                  ON (a.id_referensi_nota = d.id
                                  AND a.id_company = d.id_company
                                  AND a.id_material = d.id_material)
                                WHERE
                                	a.i_status = '6' 
                                	AND a.id_company = '".$this->session->userdata('id_company')."'
                                  AND a.id_supplier = '$idsupplier'
                                  AND a.id = '$id'
                                  AND a.n_sisa > 0
                                  AND f_debet_nota_retur = 'f'
                                  AND a.i_bagian = '$ibagian' 
                                ", FALSE);
  }

  public function cek_kode($kode, $ibagian){
      $this->db->select('i_document');
      $this->db->from('tm_dn_ap_retur_makloon');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function cek_kodeedit($kode, $kodeold, $ibagian){
      $this->db->select('i_document');
      $this->db->from('tm_dn_ap_retur_makloon');
      $this->db->where('i_document', $kode);
      $this->db->where('i_document <>', $kodeold);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status','5');
      return $this->db->get();
  }

  public function runningid(){
      $this->db->select('max(id) AS id');
      $this->db->from('tm_dn_ap_retur_makloon');
      return $this->db->get()->row()->id+1;
  }

  public function insertheader($id, $inoteretur, $dnoteretur, $ibagian, $isupplier, $ifakpajak, $dfakpajak, $ifaksup, $vtotalppn, $vtotaldpp, $vtotalfa, $eremark, $ibagianref){
      $dentry = date("Y-m-d H:i:s");

      $data = array(
                      'id'                  => $id,
                      'id_company'          => $this->session->userdata('id_company'),
                      'i_document'          => $inoteretur,
                      'd_document'          => $dnoteretur,    
                      'i_bagian'            => $ibagian,
                      'id_supplier'         => $isupplier,
                      'i_faktur_supplier'   => $ifaksup,
                      'i_faktur_pajak'      => $ifakpajak,
                      'd_faktur_pajak'      => $dfakpajak,
                      'v_total_ppn'         => $vtotalppn,
                      'v_total_dpp'         => $vtotaldpp,
                      'v_total'             => $vtotalfa,
                      'v_sisa'              => $vtotalfa,
                      'e_remark'            => $eremark,
                      'd_entry'             => current_datetime(),
                      'i_bagian_referensi'  => $ibagianref
      );
      $this->db->insert('tm_dn_ap_retur_makloon', $data);
  }

  public function insertdetail($id, $ireferensi, $idmaterial, $nquantity, $vprice, $vpricetotal, $dpp, $ppn){
      $data = array(
            'id_document'      => $id, 
            'id_company'       => $this->session->userdata('id_company'),
            'id_document_reff' => $ireferensi,
            'id_material'      => $idmaterial,
            'n_quantity'       => $nquantity,
            'v_price'          => $vprice,
            'v_price_total'    => $vpricetotal,
            'v_dpp'            => $dpp,
            'v_ppn'            => $ppn
      );    
      $this->db->insert('tm_dn_ap_retur_makloon_item', $data);
  }

  public function bacaheader($id, $idsupplier){
      return $this->db->query("
                                SELECT DISTINCT
                                	a.id, 
                                	a.i_document, 
                                  to_char(a.d_document,'dd-mm-yyyy') as d_document,
                                  a.i_bagian,
                                	a.id_supplier, 
                                	d.i_supplier, 
                                	d.e_supplier_name,
                                	b.id_document_reff, 
                                	c.i_document as i_document_referensi,
                                	to_char(c.d_document, 'dd-mm-yyyy') as d_referensi,
                                	a.i_faktur_supplier, 
                                	a.i_faktur_pajak,
                                	to_char(a.d_faktur_pajak, 'dd-mm-yyyy') as d_pajak,
                                	a.v_total_ppn,
                                	a.v_total_dpp,
                                  a.v_total,
                                  a.e_remark,
                                  a.i_status,
                                  a.i_bagian_referensi, 
                                  c.f_debet_nota_retur
                                FROM 
                                	tm_dn_ap_retur_makloon a
                                	LEFT JOIN 
                                		tm_dn_ap_retur_makloon_item b 
                                		ON (a.id = b.id_document
                                		AND a.id_company = b.id_company)
                                	LEFT JOIN 
                                		( SELECT id, i_document, d_document, i_bagian, f_debet_nota_retur, id_company
                                		  FROM tm_retur_makloonaksesories 
                                		  UNION ALL
                                		  SELECT id, i_document, d_document, i_bagian, f_debet_nota_retur, id_company
                                		  FROM tm_retur_makloonbb 
                                		  UNION ALL 
                                		  SELECT id, i_document, d_document, i_bagian, f_debet_nota_retur, id_company 
                                		  FROM tm_retur_makloonbp
                                		) c
                                		ON (b.id_document_reff = c.id
                                		AND b.id_company = c.id_company
                                		AND a.i_bagian_referensi = c.i_bagian)
                                	INNER JOIN 
                                		tr_supplier d 
                                		ON (a.id_supplier = d.id
                                		AND a.id_company = d.id_company)
                                WHERE
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.id = '$id'
                                  AND a.id_supplier = '$idsupplier'
                              ", FALSE);
  }

  public function bacadetail($id, $idsupplier){
      return $this->db->query("
                              SELECT
                              	b.id_document, 
                              	b.id_material,
                              	d.i_material, 
                              	d.e_material_name, 
                              	d.i_satuan_code,
                              	b.n_quantity,
                              	b.v_price, 
                              	b.v_price_total, 
                              	b.v_dpp, 
                              	b.v_ppn
                              FROM 
                              	tm_dn_ap_retur_makloon a
                              	LEFT JOIN 
                              		tm_dn_ap_retur_makloon_item b 
                              		ON (a.id = b.id_document
                              		AND a.id_company = b.id_company)
                              	LEFT JOIN 
                              		( SELECT id, i_document, d_document, i_bagian, id_company
                              		  FROM tm_retur_makloonaksesories 
                              		  UNION ALL
                              		  SELECT id, i_document, d_document, i_bagian, id_company
                              		  FROM tm_retur_makloonbb 
                              		  UNION ALL 
                              		  SELECT id, i_document, d_document, i_bagian, id_company 
                              		  FROM tm_retur_makloonbp
                              		) c
                              		ON (b.id_document_reff = c.id
                              		AND b.id_company = c.id_company
                              		AND a.i_bagian_referensi = c.i_bagian)
                              	INNER JOIN 
                              		tr_material d 
                              		ON (b.id_material = d.id
                              		AND a.id_company = d.id_company)
                              	INNER JOIN 
                              		tr_satuan e
                              		ON (d.i_satuan_code = e.i_satuan_code
                              		AND d.id_company = e.id_company)
                              WHERE
                              	a.id_company = '".$this->session->userdata('id_company')."'
                              	AND b.id_document = '$id'
                                AND a.id_supplier = '$idsupplier'
                              ", FALSE);
  }

  public function updateheader($id, $inoteretur, $dnoteretur, $ibagian, $isupplier, $ifakpajak, $dfakpajak, $ifaksup, $vtotalppn, $vtotaldpp, $vtotalfa, $eremark, $ibagianref){
      $data = array(
                    'id_company'          => $this->session->userdata('id_company'),
                    'i_document'          => $inoteretur,
                    'd_document'          => $dnoteretur,    
                    'i_bagian'            => $ibagian,
                    'id_supplier'         => $isupplier,
                    'i_faktur_supplier'   => $ifaksup,
                    'i_faktur_pajak'      => $ifakpajak,
                    'd_faktur_pajak'      => $dfakpajak,
                    'v_total_ppn'         => $vtotalppn,
                    'v_total_dpp'         => $vtotaldpp,
                    'v_total'             => $vtotalfa,
                    'v_sisa'              => $vtotalfa,
                    'e_remark'            => $eremark,
                    'd_update'            => current_datetime(),
                    'i_bagian_referensi'  => $ibagianref
      );
      $this->db->where('id', $id);
      $this->db->update('tm_dn_ap_retur_makloon', $data);
  }

  public function deletedetail($id){
      return $this->db->query("DELETE FROM tm_dn_ap_retur_makloon_item WHERE id_document = '$id'", FALSE);
  }

  public function estatus($istatus) {
      $this->db->select('e_status_name');
      $this->db->from('tr_status_document');
      $this->db->where('i_status',$istatus);
      return $this->db->get()->row()->e_status_name;
  }

  public function changestatus($id,$istatus) {
      if ($istatus=='6') {
        $query0 = $this->db->query("
                                    SELECT
                                    	a.i_bagian_referensi,
                                    	b.id_document_reff
                                    FROM
                                    	tm_dn_ap_retur_makloon a
                                    	INNER JOIN 
                                    		tm_dn_ap_retur_makloon_item b
                                    		ON (a.id = b.id_document 
                                    		AND a.id_company = b.id_company)
                                    WHERE 
                                    	a.id = '$id'
                                    	AND a.id_company = '".$this->session->userdata('id_company')."'
                                  ", FALSE);
        if($query0->num_rows() > 0){
          foreach($query0->result() as $key){
            $query = $this->db->query("
                                        SELECT 
                                        	a.id,
                                        	a.i_document,
                                        	a.i_bagian
                                        FROM
                                        	( SELECT id, i_document, i_bagian, id_company
                                        	  FROM tm_retur_makloonaksesories
                                        	  UNION ALL
                                        	  SELECT id, i_document, i_bagian, id_company
                                        	  FROM tm_retur_makloonbb
                                        	  UNION ALL
                                        	  SELECT id, i_document, i_bagian, id_company
                                        	  FROM tm_retur_makloonbp
                                        	) a
                                        WHERE
                                        	a.id = '$key->id_document_reff'
                                        	AND a.i_bagian = '$key->i_bagian_referensi'
                                        	AND a.id_company = '".$this->session->userdata('id_company')."'
                                    	", FALSE);
            if ($query->num_rows()>0) {
              foreach($query->result() as $row){
                $this->db->query("
                                  UPDATE 
                                    tm_retur_makloonaksesories 
                                  SET 
                                    f_debet_nota_retur = 't'
                                  WHERE 
                                    id = '$row->id'
                                    AND i_bagian = '$row->i_bagian'
                                    AND id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
                $this->db->query("
                                  UPDATE 
                                    tm_retur_makloonbb 
                                  SET 
                                    f_debet_nota_retur = 't'
                                  WHERE 
                                    id = '$row->id'
                                    AND i_bagian = '$row->i_bagian'
                                    AND id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
                $this->db->query("
                                UPDATE 
                                  tm_retur_makloonbp 
                                SET 
                                  f_debet_nota_retur = 't'
                                WHERE 
                                  id = '$row->id'
                                  AND i_bagian = '$row->i_bagian'
                                  AND id_company = '".$this->session->userdata('id_company')."'
                              ", FALSE);
              }  
            }
          }
        }
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
      $this->db->update('tm_dn_ap_retur_makloon', $data);
    }
}
/* End of file Mmaster.php */