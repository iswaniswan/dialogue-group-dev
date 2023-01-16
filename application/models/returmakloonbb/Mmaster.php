<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
/*(a.d_faktur_date_from ||' s/d '||a.d_faktur_date_to) as periode, */
	function data($i_menu, $folder, $dfrom, $dto){
    $datatables = new Datatables(new CodeigniterAdapter);
    if ($dfrom!='' && $dto!='') {
      $dfrom = date('Y-m-d', strtotime($dfrom));
      $dto   = date('Y-m-d', strtotime($dto));
      $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
    }else{
      $where = "";
    }

    $query = $this->db->query("
                              SELECT 
                              	a.i_bagian 
                              FROM 
                              	tr_bagian a
                              	INNER JOIN 
                              		tr_departement_cover b
                              		ON(b.i_bagian = a.i_bagian 
                              		AND a.id_company = b.id_company)
                              WHERE
                              	a.f_status = 't'
                              	AND b.i_departement = '".$this->session->userdata('i_departement')."'
                              	AND b.username = '".$this->session->userdata('username')."'
                                AND a.id_company = '".$this->session->userdata('id_company')."' 
                              ", FALSE);
    if($query->num_rows() > 0){
      foreach($query->result() as $row){
        $ibagian = $row->i_bagian;
      }
    }

    $datatables->query("
                        SELECT 
                          '0' as no,
                        	a.id,
                        	a.i_document,
                          to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                          a.i_bagian,
                          a.id_supplier,
                          c.i_supplier,
                          c.e_supplier_name,
                        	a.id_referensi_nota,
                        	b.i_document as i_document_referensi,
                        	a.e_remark,
                          a.i_status,
                          d.e_status_name,
                          d.label_color,
                          '$i_menu' AS i_menu,
                          '$folder' AS folder,
                          '$dfrom' AS dfrom,
                          '$dto' AS dto
                        FROM
                        	tm_retur_makloonbb a
                        	LEFT JOIN
                        		(
                        		  SELECT 
                        			  id, 
                        			  i_document, 
                        			  id_company,
                        			  i_bagian_referensi
                        		  FROM 
                        			  tm_notamakloonbis2an 
                        		  UNION ALL 
                        		  SELECT 
                        			  id,
                        			  i_document,
                        			  id_company,
                        			  i_bagian_referensi
                        		  FROM
                        			  tm_notamakloonquilting
                        		) b ON (a.id_referensi_nota = b.id 
                        		AND a.id_company = b.id_company
                                AND a.i_bagian = b.i_bagian_referensi)
                        	INNER JOIN
                        		tr_supplier c
                        		ON (a.id_supplier = c.id 
                        		AND a.id_company = c.id_company) 
                        	INNER JOIN
                        		tr_status_document d
                        		ON (a.i_status = d.i_status)
                        WHERE 
                        	a.i_status <> '5'
                          AND a.id_company = '".$this->session->userdata('id_company')."'
                          $where
                        ORDER BY
                        	a.i_document,
                        	a.d_document
	
			", FALSE);

      $datatables->edit('e_status_name', function ($data) {
        return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
      });

      $datatables->add('action', function ($data) {
      $id         = trim($data['id']);
      $i_menu     = $data['i_menu'];
      $i_status   = $data['i_status'];
      $idsupplier = $data['id_supplier'];
      $isupplier  = $data['i_supplier'];
      $ibagian    = $data['i_bagian'];
      $folder     = $data['folder'];
      $dfrom      = $data['dfrom'];
      $dto        = $data['dto'];
      $data       = '';
          if(check_role($i_menu, 2)){
              $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$idsupplier/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
          }
          if (check_role($i_menu, 3)) {
              if ($i_status != '6' && $i_status != '9') {
                  $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$idsupplier/$ibagian/$dfrom/$dto/$isupplier/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
              }
          }
          if (check_role($i_menu, 7)) {
            if ($i_status == '2') {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$idsupplier/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
            }
          }
          if (check_role($i_menu, 4)  && ($i_status=='1')) {
            $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
          }
          return $data;
      });
      $datatables->hide('id');
      $datatables->hide('id_supplier');
      $datatables->hide('id_referensi_nota');
      $datatables->hide('i_menu');
      $datatables->hide('i_status');
      $datatables->hide('folder');
      $datatables->hide('dfrom');
      $datatables->hide('dto');
      $datatables->hide('label_color');
      $datatables->hide('i_bagian');
      $datatables->hide('i_supplier');
    
      return $datatables->generate();
  }

  /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/
  public function bagian()
  {
      $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
      $this->db->where('a.f_status', 't');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
      $this->db->where('i_level', $this->session->userdata('i_level'));
      $this->db->where('username', $this->session->userdata('username'));
      $this->db->where('a.id_company', $this->session->userdata('id_company'));        
      $this->db->order_by('e_bagian_name');
      return $this->db->get();
  }

  public function cekkode($kode,$ibagian)
  {
      $this->db->select('i_document');
      $this->db->from('tm_retur_makloonbb');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata("id_company"));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function cek_kodeedit($kode,$kodeold, $ibagian) {
    $this->db->select('i_document');
    $this->db->from('tm_retur_makloonbb');
    $this->db->where('i_document', $kode);
    $this->db->where('i_document <>', $kodeold);
    $this->db->where('i_bagian', $ibagian);
    $this->db->where('id_company', $this->session->userdata('id_company'));
    $this->db->where_not_in('i_status', '5');
    return $this->db->get();
}

  public function runningid()
  {
      $this->db->select('max(id) AS id');
      $this->db->from('tm_retur_makloonbb');
      return $this->db->get()->row()->id+1;
  }

    /*----------  RUNNING NO DOKUMEN  ----------*/    
    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_retur_makloonbb
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJ';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_retur_makloonbb
            WHERE to_char (d_document, 'yyyy') >= '".date('Y')."'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
            AND to_char (d_document, 'yyyy') >= '$tahun'
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

    public function getsupplier($ibagian){
        return $this->db->query("
                                SELECT DISTINCT
                                	a.i_partner,
                                  b.id,
                                  b.i_supplier,
                                	b.e_supplier_name 
                                FROM
                                	(
                                	  SELECT 
                                		  i_partner,
                                		  i_status,
                                		  id_company
                                	  FROM
                                		  tm_notamakloonbis2an
                                	  WHERE
                                		  i_bagian_referensi = '$ibagian'
                                	  UNION ALL 
                                	  SELECT 
                                		  i_partner,
                                		  i_status,
                                		  id_company
                                	  FROM
                                		  tm_notamakloonquilting 
                                	  WHERE 
                                		  i_bagian_referensi = '$ibagian'
                                	) a
                                	INNER JOIN 
                                		tr_supplier b
                                		ON (a.i_partner = b.i_supplier 
                                		AND a.id_company = b.id_company)
                                WHERE
                                    a.id_company = '".$this->session->userdata('id_company')."'
                                    AND a.i_status IN ('11','12','13')
                                ", FALSE);
    }

    public function getreferensi($isupplier, $ibagian){
        return $this->db->query("
                                SELECT
                                	a.id,
                                  a.i_document,
                                  a.group
                                FROM
                                	(
                                	    SELECT
                                		    id,
                                		    i_partner,
                                		    i_document,
                                		    i_status,
                                        id_company,
                                        'Faktur Bis-bisan' as group
                                	    FROM
                                        tm_notamakloonbis2an
                                	    WHERE
                                		    i_bagian_referensi = '$ibagian'
                                	    UNION ALL
                                	    SELECT
                                		    id,
                                		    i_partner,
                                		    i_document,
                                		    i_status,
                                        id_company,
                                        'Faktur Quilting' as group
                                	    FROM
                                        tm_notamakloonquilting
                                	    WHERE 
                                		    i_bagian_referensi = '$ibagian'
                                	) a
                                WHERE 
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.i_partner = '$isupplier'
                                	AND a.i_status IN ('11','12','13')                  
        ", FALSE);
    }

    public function getheader($ibagian, $id){
        return $this->db->query("
                                SELECT 
                                  a. d_document
                                FROM 
                                	(
                                	  SELECT 
                                		  id,
                                		  to_char(d_document, 'dd-mm-yyyy') as d_document,
                                      id_company
                                	  FROM
                                      tm_notamakloonbis2an
                                	  WHERE
                                		  i_bagian_referensi = '$ibagian'
                                	  UNION ALL
                                	  SELECT 
                                		  id,
                                		  to_char(d_document, 'dd-mm-yyyy') as d_document,
                                      id_company
                                	  FROM 
                                      tm_notamakloonquilting
                                	  WHERE
                                		  i_bagian_referensi = '$ibagian'
                                	) a 
                                WHERE 
                                	a.id = '$id'
                                	AND a.id_company = '".$this->session->userdata('id_company')."'

                                ", FALSE);
    }

    public function getitem($ibagian, $id){
        return $this->db->query("
                                SELECT 
                                	a.id_material, 
                                	a.n_quantity,
                                  a.n_sisa,
                                  b.i_material,
                                  b.e_material_name, 
                                  b.i_satuan_code,
                                  c.e_satuan_name,
                                  a.id_reff,
                                  a.i_reff
                                FROM 
                                	(
                                		SELECT 
                                      a.i_partner,
                                      a.i_status,
                                      a.id_company,
                                      b.id_document,
                                      b.id_material,
                                      b.n_quantity,
                                      b.n_sisa,
                                      c.id as id_reff,
                                      c.i_document as i_reff
                                    FROM
                                      tm_notamakloonbis2an a
                                      LEFT JOIN
                                		    tm_notamakloonbis2an_item b
                                		    ON (a.id = b.id_document 
                                        AND a.id_company = b.id_company)
                                      LEFT JOIN 
                                        tm_masuk_makloon_bb c
                                        ON (b.id_referensi_item = c.id
                                        AND b.id_company = c.id_company)
                                    WHERE
                                        a.i_bagian_referensi = '$ibagian'
                                    UNION ALL 
                                    SELECT 
                                      a.i_partner,
                                      a.i_status,
                                      a.id_company,
                                      b.id_document,
                                      b.id_material,
                                      b.n_quantity,
                                      b.n_sisa,
                                      c.id as id_reff,
                                      c.i_document as i_reff
                                    FROM
                                      tm_notamakloonquilting a
                                      LEFT JOIN 
                                		    tm_notamakloonquilting_item b
                                		    ON (a.id = b.id_document
                                        AND a.id_company = b.id_company)
                                      LEFT JOIN 
                                        tm_masuk_makloon_bb c
                                        ON (b.id_referensi_item = c.id
                                        AND b.id_company = c.id_company)
                                    WHERE 
                                      a.i_bagian_referensi = '$ibagian'
                                    ) a
                                  INNER JOIN 
                                		tr_material b 
                                		ON (a.id_material = b.id
                                		AND a.id_company = b.id_company) 
                                	INNER JOIN 
                                		tr_satuan c
                                		ON (b.i_satuan_code = c.i_satuan_code
                                		AND b.id_company = c.id_company)
                                WHERE 
                                  a.id_document = '$id'
                                  AND a.n_sisa <> 0
                                	AND a.id_company = '".$this->session->userdata('id_company')."'                              	
                                ", FALSE);
    }

    public function insertheader($id, $ibagian, $idocument, $ddocument, $idsupplier, $idreferensi, $eremark){
        $data = array(
            'id'                => $id,
            'id_company'        => $this->session->userdata('id_company'),
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_supplier'       => $idsupplier,
            'id_referensi_nota' => $idreferensi,
            'e_remark'          => $eremark,
            'd_entry'           => current_datetime()        
        );
        $this->db->insert('tm_retur_makloonbb', $data);
    }

    public function insertdetail($id, $idmaterial, $nquantity, $nretur, $edesc, $idreff){
        $data = array(
                    'id_company'      => $this->session->userdata('id_company'),
                    'id_document'     => $id,
                    'id_material'     => $idmaterial,
                    'n_quantity'      => $nretur,
                    'e_remark'        => $edesc,
                    'n_sisa'          => $nretur,
                    'id_referensi_sj' => $idreff
        );
        $this->db->insert('tm_retur_makloonbb_item', $data);
    }

    public function deletedetail($id){
        return $this->db->query("DELETE FROM tm_retur_makloonbb_item WHERE id_document='$id'", FALSE);
    }

    public function bacaheader($id, $idsupplier, $ibagian){
      return $this->db->query("
                              SELECT 
                              	a.id,
                              	a.i_document,
                                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                to_char(b.d_document, 'dd-mm-yyyy') as d_referensi,
                              	a.id_referensi_nota,
                              	b.i_document as i_document_referensi,
                              	a.id_supplier,
                              	c.i_supplier,
                              	c.e_supplier_name,
                              	a.i_status,
                                a.e_remark,
                                a.i_bagian,
                                d.e_bagian_name,
                                b.id_type_makloon,
                                b.groupmakloon
                              FROM 
                              	tm_retur_makloonbb a 
                              	LEFT JOIN 
                              		(
                              		  SELECT 
                              			  a.id, 
                                      a.i_document,
                                      a.d_document,
                                      a.id_company,
                                      b.id_type_makloon,
                                      'cutting' as groupmakloon
                              		  FROM 
                                      tm_notamakloonbis2an a
                                      INNER JOIN 
                                        tm_keluar_makloon_bb b 
                                        ON (a.id_referensi = b.id 
                                        AND a.i_bagian_referensi = b.i_bagian 
                                        AND a.id_company = b.id_company)
                              		  WHERE 
                              			  a.i_bagian_referensi = '$ibagian'
                              		  UNION ALL
                              		  SELECT 
                              			  a.id,
                                      a.i_document,
                                      a.d_document,
                                      a.id_company,
                                      b.id_type_makloon,
                                      'quilting' as groupmakloon
                              		  FROM 
                                      tm_notamakloonquilting a
                                      INNER JOIN 
                                        tm_keluar_makloon_bb b 
                                        ON (a.id_referensi = b.id 
                                        AND a.i_bagian_referensi = b.i_bagian 
                                        AND a.id_company = b.id_company)
                              		  WHERE
                              			  a.i_bagian_referensi = '$ibagian'
                              		) b ON (a.id_referensi_nota = b.id
                              		AND a.id_company = b.id_company)
                              	INNER JOIN 
                              		tr_supplier c
                              		ON (a.id_supplier = c.id 
                                  AND a.id_company = c.id_company)
                                INNER JOIN 
                                  tr_bagian d 
                                  ON (a.i_bagian = d.i_bagian
                                  AND a.id_company = d.id_company)
                              WHERE
                              	a.id_company = '".$this->session->userdata('id_company')."'
                              	AND a.id = '$id'
                              	AND a.id_supplier = '$idsupplier'
      ", FALSE);
    }

    public function bacadetail($id, $idsupplier, $ibagian){
      return $this->db->query("
                                SELECT 
                                	a.id_document,
                                	a.id_material,
                                	c.i_material,
                                	c.e_material_name, 
                                	c.i_satuan_code,
                                	d.e_satuan_name,
                                	a.n_quantity as n_retur,
                                	e.n_quantity as n_faktur,
                                	e.n_sisa,
                                  a.e_remark,
                                  e.id_reff,
                                  e.i_reff
                                FROM
                                	tm_retur_makloonbb_item a
                                	LEFT JOIN
                                		tm_retur_makloonbb b
                                		ON (a.id_document = b.id 
                                		AND a.id_company = b.id_company)
                                	INNER JOIN 
                                		tr_material c 
                                		ON (a.id_material = c.id
                                		AND a.id_company = c.id_company)
                                	INNER JOIN
                                		tr_satuan d
                                		ON (c.i_satuan_code = d.i_satuan_code
                                		AND a.id_company = d.id_company)
                                	INNER JOIN
                                		(
                                		  SELECT 
                                			  a.id, 
                                			  a.i_document,
                                			  a.id_company,
                                			  b.id_material,
                                			  b.n_quantity,
                                        b.n_sisa,
                                        c.id as id_reff,
                                        c.i_document as i_reff
                                		  FROM 
                                			  tm_notamakloonbis2an a
                                			  LEFT JOIN 
                                			  	tm_notamakloonbis2an_item b 
                                			  	ON (a.id = b.id_document 
                                          AND a.id_company = b.id_company) 
                                        LEFT JOIN 
                                          tm_masuk_makloon_bb c 
                                          ON (b.id_referensi_item = c.id
                                          AND b.id_company = c.id_company)
                                		  WHERE 
                                			  a.i_bagian_referensi = '$ibagian'
                                		  UNION ALL
                                		  SELECT 
                                			  a.id, 
                                			  a.i_document,
                                			  a.id_company,
                                			  b.id_material,
                                			  b.n_quantity,
                                        b.n_sisa,
                                        c.id as id_reff,
                                        c.i_document as i_reff
                                		  FROM 
                                			  tm_notamakloonquilting a
                                			LEFT JOIN 
                                				tm_notamakloonquilting_item b
                                				ON (a.id = b.id_document
                                        AND a.id_company = b.id_company)
                                      LEFT JOIN 
                                        tm_masuk_makloon_bb c 
                                        ON (b.id_referensi_item = c.id
                                        AND b.id_company = c.id_company)
                                		  WHERE
                                			a.i_bagian_referensi = '$ibagian'
                                		) e ON (b.id_referensi_nota = e.id
                                		AND a.id_company = e.id_company
                                    AND a.id_material = e.id_material
                                    AND a.id_referensi_sj = e.id_reff)
                                WHERE
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.id_document = '$id'
                                  AND b.id_supplier = '$idsupplier'
                                ORDER BY e.i_reff
      ", FALSE);
    }

    public function updateheader($id, $ibagian, $idocument, $ddocument, $idsupplier, $idreferensi, $eremark){
      $data = array(
          'id_company'        => $this->session->userdata('id_company'),
          'i_document'        => $idocument,
          'd_document'        => $ddocument,
          'i_bagian'          => $ibagian,
          'id_supplier'       => $idsupplier,
          'id_referensi_nota' => $idreferensi,
          'e_remark'          => $eremark,
          'd_update'          => current_datetime()        
      );
      $this->db->where('id', $id);
      $this->db->update('tm_retur_makloonbb', $data);
    } 

    public function updatedetail($id, $idmaterial, $nquantity, $nretur, $edesc){
      $data = array(
                  'id_company'    => $this->session->userdata('id_company'),
                  'id_document'   => $id,
                  'id_material'   => $idmaterial,
                  'n_quantity'    => $nretur,
                  'e_remark'      => $edesc
      );
      $this->db->where('id_document', $id);
      $this->db->where('id_material', $idmaterial);
      $this->db->update('tm_retur_makloonbb_item', $data);
    }

    public function estatus($istatus) {
      $this->db->select('e_status_name');
      $this->db->from('tr_status_document');
      $this->db->where('i_status',$istatus);
      return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id,$istatus,$groupmakloon,$ibagian) {
        if ($istatus=='6') {
            $query = $this->db->query("
               SELECT 
                	b.id_referensi_nota,
                	a.id_material,
                	a.n_quantity,
                	b.id_supplier,
                	b.i_bagian,
                	a.id_referensi_sj
                FROM 
                	tm_retur_makloonbb_item a
                	INNER JOIN tm_retur_makloonbb b ON (
                		a.id_document = b.id
                		AND a.id_company = b.id_company
                	)
                	INNER JOIN (
                		SELECT 
                			b.id,
                			b.i_bagian_referensi,
                			c.id as id_reff,
                			b.id_company,
                			a.id_material
                		FROM 
                			tm_notamakloonbis2an_item a
                			INNER JOIN tm_notamakloonbis2an b ON (
                				a.id_document = b.id 
                				AND a.id_company = b.id_company
                			)
                			INNER JOIN tm_masuk_makloon_bb c ON (
                				a.id_referensi_item = c.id
                				AND a.id_company = c.id_company
                			)
                		WHERE 
                			b.i_bagian_referensi = '$ibagian'
                			AND b.id_company = '".$this->session->userdata('id_company')."'
                		UNION ALL 
                		SELECT 
                			b.id,
                			b.i_bagian_referensi,
                			c.id as id_reff,
                			b.id_company,
                			a.id_material
                		FROM 
                			tm_notamakloonquilting_item a 
                			INNER JOIN tm_notamakloonquilting b ON (
                				a.id_document = b.id 
                				AND a.id_company = b.id_company 
                			)
                			INNER JOIN tm_masuk_makloon_bb c ON (
                				a.id_referensi_item = c.id
                				AND a.id_company = c.id_company
                			)
                		WHERE 
                			b.i_bagian_referensi = '$ibagian'
                			AND b.id_company = '".$this->session->userdata('id_company')."'
                	) c ON (
                		b.id_referensi_nota = c.id
                		AND b.i_bagian = c.i_bagian_referensi
                		AND b.id_company = c.id_company
                		AND a.id_referensi_sj = c.id_reff
                		AND a.id_material = c.id_material
                	)
                WHERE 
                	a.id_document = '$id'
                	AND b.id_company = '".$this->session->userdata('id_company')."'", FALSE);
            if ($query->num_rows()>0) {
               foreach ($query->result() as $key) {
                  if($groupmakloon == 'quilting'){
                    $this->db->query("
                                      UPDATE
                                        tm_notamakloonquilting_item
                                      SET
                                        n_sisa = n_sisa - $key->n_quantity,
                                        n_retur = n_retur + $key->n_quantity
                                      WHERE
                                        id_document = '$key->id_referensi_nota'
                                        AND id_referensi_item = '$key->id_referensi_sj'
                                        AND id_material = '$key->id_material'
                                        AND id_company = '".$this->session->userdata('id_company')."'
                    ", FALSE);
                  }else if($groupmakloon == 'cutting'){
                    $this->db->query("
                                      UPDATE
                                        tm_notamakloonbis2an_item
                                      SET
                                        n_sisa = n_sisa - $key->n_quantity,
                                        n_retur = n_retur + $key->n_quantity
                                      WHERE
                                        id_document = '$key->id_referensi_nota'
                                        AND id_referensi_item = '$key->id_referensi_sj'
                                        AND id_material = '$key->id_material'
                                        AND id_company = '".$this->session->userdata('id_company')."'
                    ", FALSE);
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
        $this->db->update('tm_retur_makloonbb', $data);
      }
}
