<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  function data($i_menu, $folder, $dfrom, $dto){
    if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE a.d_document BETWEEN '$dfrom' AND '$dto'";
    }else{
        $where = "";
    }
    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT 
                              0 as no,
                              a.id,
                            	a.i_document,
                              to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                              a.i_bagian,
                            	a.i_bagian_pengirim,
                            	b.e_bagian_pengirim,
                            	a.id_document_reff,
                            	d.i_document as i_document_reff,
                            	a.e_remark,
                            	a.i_status,
                            	c.e_status_name,
                              c.label_color as label,
                              '$i_menu' as i_menu,
                              '$folder' as folder,
                              '$dfrom' as dfrom,
                              '$dto' as dto
                            FROM
                            	tm_masuk_pinjamanbp a
                            	INNER JOIN 
                                (SELECT
                                  i_bagian as i_bagian_pengirim,
                                  e_bagian_name as e_bagian_pengirim,
                                  id_company
                                FROM
                                  tr_bagian
                                UNION ALL
                                SELECT 
                                  i_supplier as i_bagian_pengirim,
                                  e_supplier_name as e_bagian_pengirim,
                                  id_company
                                FROM
                                  tr_supplier 
                                UNION ALL
                                SELECT
                                  i_customer as i_bagian_pengirim,
                                  e_customer_name as e_bagian_pengirim,
                                  id_company
                                FROM
                                  tr_customer
                                UNION ALL
                                SELECT 
                                  e_nik as i_bagian_pengirim,
                                  e_nama_karyawan as e_bagian_pengirim,
                                  id_company
                                FROM 
                                  tr_karyawan 
                                ) b ON (a.i_bagian_pengirim =  b.i_bagian_pengirim AND a.id_company = b.id_company)
                            	INNER JOIN 
                            		tr_status_document c
                            		ON (a.i_status = c.i_status)
                            	LEFT JOIN
                            		tm_keluar_pinjamanbp d
                            		ON (a.id_document_reff = d.id 
                            		AND a.id_company = d.id_company)
                              $where
                              AND a.id_company = '".$this->session->userdata('id_company')."'
                              AND a.i_status <> '5'
                            ORDER BY
                              a.i_document,
                              a.d_document
                          ", FALSE);

            $datatables->edit('e_status_name', function ($data) {
              return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status_name'].'</span>';
            });
        
        $datatables->add('action', function ($data) {
          $id             = trim($data['id']);
          $ibagian        = trim($data['i_bagian']);
          $i_menu         = $data['i_menu'];
          $folder         = $data['folder'];
          $dfrom          = $data['dfrom'];
          $dto            = $data['dto'];
          $i_status       = trim($data['i_status']);
          $data           = '';

          if(check_role($i_menu, 2)){
            $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
          }
          if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
              $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
          }
          if (check_role($i_menu, 7)) {
              if ($i_status == '2') {
                  $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
              }
          }
          if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9' && $i_status!='2')) {
              $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close'></i></a>";
          }
          return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id');
        $datatables->hide('i_bagian');
        $datatables->hide('i_bagian_pengirim');
        $datatables->hide('id_document_reff');
        $datatables->hide('i_status');
        $datatables->hide('label');

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

  public function partner(){
    return $this->db->query("
                            SELECT DISTINCT
                              a.id_partner,
                              b.i_partner,
                              b.e_partner_name,
                              a.i_partner_group 
                            FROM
                              tm_keluar_pinjamanbp a 
                              INNER JOIN
                                 (
                                    SELECT
                                       id as id_partner,
                                       i_bagian as i_partner,
                                       e_bagian_name as e_partner_name,
                                       id_company,
                                       'bagian' as i_partner_group 
                                    FROM
                                       tr_bagian 
                                    WHERE
                                       id_company = '".$this->session->userdata('id_company')."' 
                                       AND f_status = 't' 
                                    UNION ALL
                                    SELECT
                                       id as id_partner,
                                       i_supplier as i_partner,
                                       e_supplier_name as e_partner_name,
                                       id_company,
                                       'supplier' as i_partner_group 
                                    FROM
                                       tr_supplier 
                                    WHERE
                                       id_company = '".$this->session->userdata('id_company')."' 
                                       AND f_status = 't' 
                                    UNION ALL
                                    SELECT
                                       id as id_partner,
                                       i_customer as i_partner,
                                       e_customer_name as e_partner_name,
                                       id_company,
                                       'customer' as i_partner_group 
                                    FROM
                                       tr_customer 
                                    WHERE
                                       id_company = '".$this->session->userdata('id_company')."' 
                                       AND f_status = 't' 
                                    UNION ALL
                                    SELECT
                                       id as id_partner,
                                       e_nik as i_partner,
                                       e_nama_karyawan as e_partner_name,
                                       id_company,
                                       'karyawan' as i_partner_group 
                                    FROM
                                       tr_karyawan 
                                    WHERE
                                       id_company = '".$this->session->userdata('id_company')."' 
                                       AND f_status = 't'
                                 )
                                 b 
                                 ON (a.id_partner = b.id_partner 
                                 AND a.id_company = b.id_company
                                 AND a.i_partner_group = b.i_partner_group)
                              LEFT JOIN
                                tm_keluar_pinjamanbp_item c
                                ON (a.id = c.id_document
                                AND a.id_company = c.id_company)
                            WHERE
                              a.id_company = '".$this->session->userdata('id_company')."' 
                              AND a.i_status = '6' 
                              AND c.n_quantity_sisa <> '0'
                            ORDER BY
                              b.e_partner_name
                          ", FALSE);
  } 

 public function referensi($cari, $idpartner, $ipartner, $ipartnergroup){
    return $this->db->query("
                            SELECT DISTINCT
                            	a.id,
                            	a.i_document,
                            	a.d_document 
                            FROM
                            	tm_keluar_pinjamanbp a
                            	LEFT JOIN 
                            		tm_keluar_pinjamanbp_item b
                            		ON (a.id = b.id_document 
                                AND a.id_company = b.id_company)
                              INNER JOIN
                              (
                                 SELECT
                                    id as id_partner,
                                    i_bagian as i_partner,
                                    e_bagian_name as e_partner_name,
                                    id_company,
                                    'bagian' as i_partner_group 
                                 FROM
                                    tr_bagian 
                                 WHERE
                                    id_company = '".$this->session->userdata('id_company')."' 
                                    AND f_status = 't' 
                                 UNION ALL
                                 SELECT
                                    id as id_partner,
                                    i_supplier as i_partner,
                                    e_supplier_name as e_partner_name,
                                    id_company,
                                    'supplier' as i_partner_group 
                                 FROM
                                    tr_supplier 
                                 WHERE
                                    id_company = '".$this->session->userdata('id_company')."' 
                                    AND f_status = 't' 
                                 UNION ALL
                                 SELECT
                                    id as id_partner,
                                    i_customer as i_partner,
                                    e_customer_name as e_partner_name,
                                    id_company,
                                    'customer' as i_partner_group 
                                 FROM
                                    tr_customer 
                                 WHERE
                                    id_company = '".$this->session->userdata('id_company')."' 
                                    AND f_status = 't' 
                                 UNION ALL
                                 SELECT
                                    id as id_partner,
                                    e_nik as i_partner,
                                    e_nama_karyawan as e_partner_name,
                                    id_company,
                                    'karyawan' as i_partner_group 
                                 FROM
                                    tr_karyawan 
                                 WHERE
                                    id_company = '".$this->session->userdata('id_company')."' 
                                    AND f_status = 't'
                              )
                              c
                              ON (a.id_partner = c.id_partner 
                              AND a.id_company = c.id_company
                              AND a.i_partner_group = c.i_partner_group)
                            WHERE 
                            	a.id_company = '".$this->session->userdata('id_company')."'
                            	AND a.i_status = '6'
                              AND a.id_partner = '$idpartner'
                              AND a.i_partner_group = '$ipartnergroup'
                              AND (a.i_document ILIKE '%$cari%')
                            	AND b.n_quantity_sisa <> '0'
                            ORDER BY
                            	a.i_document,
                            	a.d_document
                            ", FALSE);
 }

  public function getdetailref($id){
    return $this->db->query("
                              SELECT 
                              	to_char(d_document, 'dd-mm-yyyy') as d_document
                              FROM
                              	tm_keluar_pinjamanbp
                              WHERE 
                              	id = '$id'
                              	AND id_company = '".$this->session->userdata('id_company')."'
                            ", FALSE);
  }

  public function getdetailrefitem($id){
    return $this->db->query("
                            SELECT
                            	a.id,
                            	b.id_document,
                            	b.id_material,
                            	c.i_material,
                            	c.e_material_name,
                            	b.n_quantity,
                            	b.n_quantity_sisa
                            FROM
                            	tm_keluar_pinjamanbp a
                            	LEFT JOIN 
                            		tm_keluar_pinjamanbp_item b
                            		ON (a.id = b.id_document
                            		AND a.id_company = b.id_company)
                            	INNER JOIN
                            		tr_material c
                            		ON (b.id_material = c.id 
                            		AND b.id_company = c.id_company)
                            WHERE 
                              b.id_document = '$id'
                              AND b.n_quantity_sisa <> '0'
                              AND a.id_company = '".$this->session->userdata('id_company')."'
                          ", FALSE);
  }

  public function runningid(){
    $this->db->select('max(id) AS id');
    $this->db->from('tm_masuk_pinjamanbp');
    return $this->db->get()->row()->id+1;
  }

  public function runningnumber($thbl, $tahun, $ibagian){
    $cek = $this->db->query("
                            SELECT 
                              substring(i_document, 1, 3) AS kode 
                            FROM 
                              tm_masuk_pinjamanbp
                            WHERE 
                              i_status <> '5'
                              AND i_bagian = '$ibagian'
                              AND id_company = '".$this->session->userdata("id_company")."'
                            ORDER BY 
                              id DESC");
    if ($cek->num_rows()>0) {
        $kode = $cek->row()->kode;
    }else{
        $kode = 'BBM';
    }
    $query  = $this->db->query("
                                SELECT
                                  max(substring(i_document, 10, 6)) AS max
                                FROM
                                  tm_masuk_pinjamanbp
                                WHERE 
                                  to_char (d_document, 'yyyy') >= '$tahun'
                                  AND i_status <> '5'
                                  AND i_bagian = '$ibagian'
                                  AND substring(i_document, 1, 3) = '$kode'
                                  AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                                  AND id_company = '".$this->session->userdata("id_company")."'
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
    $this->db->select('i_document');
    $this->db->from('tm_masuk_pinjamanbp');
    $this->db->where('i_document', $kode);
    $this->db->where('i_bagian', $ibagian);
    $this->db->where('id_company', $this->session->userdata('id_company'));
    $this->db->where_not_in('i_status', '5');
    return $this->db->get();
  }

  public function cek_kodeedit($kode,$kodeold, $ibagian) {
    $this->db->select('i_document');
    $this->db->from('tm_masuk_pinjamanbp');
    $this->db->where('i_document', $kode);
    $this->db->where('i_document <>', $kodeold);
    $this->db->where('i_bagian', $ibagian);
    $this->db->where('id_company', $this->session->userdata('id_company'));
    $this->db->where_not_in('i_status', '5');
    return $this->db->get();
  }

    public function insertheader($id, $ibagian, $ikeluar, $datekeluar, $imemo, $ipartner, $eremark){
      $idcompany = $this->session->userdata('id_company');  
        $data = array(
                      'id'                => $id,
                      'id_company'        => $idcompany,
                      'i_document'        => $ikeluar,
                      'd_document'        => $datekeluar,
                      'id_document_reff'  => $imemo,
                      'i_bagian'          => $ibagian,
                      'i_bagian_pengirim' => $ipartner,
                      'i_status'          => '1',
                      'e_remark'          => $eremark,
                      'd_entry'           => current_datetime()

        );
        $this->db->insert('tm_masuk_pinjamanbp', $data);
    }

    public function insertdetail($id, $imaterial,$nquantity, $edesc, $imemo){             
      $idcompany = $this->session->userdata('id_company');  
        $data = array(        

            'id_document'      => $id,
            'id_company'       => $idcompany,
            'id_document_reff' => $imemo,
            'id_material'      => $imaterial,
            'n_quantity'       => $nquantity,
            'n_quantity_sisa'  => $nquantity,
            'e_remark'         => $edesc,
        );
        $this->db->insert('tm_masuk_pinjamanbp_item', $data);
    }

	  public function baca_header($id){
        return $this->db->query("
                                SELECT 
                                	a.id, 
                                	a.i_document,
                                	to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                	a.i_bagian,
                                	c.e_bagian_name, 
                                	a.i_bagian_pengirim, 
                                	b.e_bagian_pengirim, 
                                	a.id_document_reff,
                                	d.i_document as i_document_reff,
                                  to_char(d.d_document, 'dd-mm-yyyy') as d_document_reff,
                                  a.e_remark,
                                  a.i_status
                                FROM
                                	tm_masuk_pinjamanbp a
                                	INNER JOIN 
                                    (SELECT
                                       i_bagian as i_bagian_pengirim,
                                       e_bagian_name as e_bagian_pengirim,
                                       id_company 
                                    FROM
                                       tr_bagian 
                                    UNION ALL
                                    SELECT
                                       i_supplier as i_bagian_pengirim,
                                       e_supplier_name as e_bagian_pengirim,
                                       id_company 
                                    FROM
                                       tr_supplier 
                                    UNION ALL
                                    SELECT
                                       i_customer as i_bagian_pengirim,
                                       e_customer_name as e_bagian_pengirim,
                                       id_company 
                                    FROM
                                       tr_customer 
                                    UNION ALL
                                    SELECT
                                       e_nik as i_bagian_pengirim,
                                       e_nama_karyawan as e_bagian_pengirim,
                                       id_company 
                                    FROM
                                       tr_karyawan
                                    ) b ON (a.i_bagian_pengirim = b.i_bagian_pengirim AND a.id_company = b.id_company) 
                                	INNER JOIN 
                                		tr_bagian c
                                		ON (a.i_bagian = c.i_bagian AND a.id_company = c.id_company)
                                	LEFT JOIN 
                                		tm_keluar_pinjamanbp d
                                		ON (a.id_document_reff = d.id AND a.id_company = d.id_company)
                                WHERE 
                                	a.id = '$id' 
                                	AND a.id_company = '".$this->session->userdata('id_company')."'
                              ", false);
    }

    public function baca_detail($id){
        return $this->db->query("
                                SELECT 
                                	a.id,
                                	a.id_document,
                                	c.id_document,
                                	a.id_material,
                                	d.i_material, 
                                	d.e_material_name,
                                	a.n_quantity,
                                    c.n_quantity as qty_masuk,
                                  c.n_quantity_sisa,
                                  a.e_remark
                                FROM
                                	tm_masuk_pinjamanbp_item a
                                	LEFT JOIN 
                                		tm_masuk_pinjamanbp b
                                		ON (a.id_document = b.id
                                		AND a.id_company = b.id_company)
                                	LEFT  JOIN 
                                		tm_keluar_pinjamanbp_item c
                                		ON (a.id_document_reff = c.id_document
                                		AND a.id_company = c.id_company
                                    AND a.id_material = c.id_material)
                                	INNER JOIN 
                                		tr_material d
                                		ON (a.id_material = d.id
                                		AND a.id_company = d.id_company)
                                WHERE
                                	a.id_document = '$id'
                                  AND a.id_company = '".$this->session->userdata('id_company')."'
                                ", false);
    }

    public function updateheader($id, $ikeluar, $ibagian, $datekeluar, $eremark){
      $data = array(
          'i_bagian'    => $ibagian,
          'i_document'  => $ikeluar,
          'd_document'  => $datekeluar,
          'e_remark'    => $eremark,
          'd_update'    => current_datetime()
      );

      $this->db->where('id', $id);
      $this->db->update('tm_masuk_pinjamanbp', $data);
    }

    public function updatedetail($id, $imaterial,$nquantity, $edesc){
      $data = array(
                    'n_quantity'      => $nquantity,
                    'n_quantity_sisa' => $nquantity,
                    'e_remark'        => $edesc
      );
      $this->db->where('id_document', $id);
      $this->db->where('id_material', $imaterial);
      $this->db->update('tm_masuk_pinjamanbp_item', $data);
    }

    public function changestatus($id,$istatus){
      $iapprove  = $this->session->userdata('username');
      $idcompany = $this->session->userdata('id_company');
      if ($istatus=='6') {
          $query = $this->db->query("
              SELECT id_document, id_material, n_quantity, n_quantity_sisa, id_document_reff
              FROM tm_masuk_pinjamanbp_item
              WHERE id_document = '$id' ", FALSE);
          if ($query->num_rows()>0) {
              foreach ($query->result() as $key) {
                 $nsisa =  $this->db->query("
                      SELECT
                          n_quantity_sisa
                      FROM
                          tm_keluar_pinjamanbp_item                       
                      WHERE
                          id_document     = '$key->id_document_reff'
                          AND id_material = '$key->id_material'
                          AND id_company  = '".$this->session->userdata('id_company')."'
                          AND n_quantity_sisa >= '$key->n_quantity'
                  ", FALSE);

                if($nsisa->num_rows()>0){
                  $this->db->query("
                      UPDATE
                          tm_keluar_pinjamanbp_item
                      SET
                          n_quantity_sisa  = n_quantity_sisa - $key->n_quantity
                      WHERE
                          id_document     = '$key->id_document_reff'
                          AND id_material = '$key->id_material'
                          AND id_company  = '".$this->session->userdata('id_company')."'
                          AND n_quantity_sisa >= '$key->n_quantity'
                  ", FALSE);
                  }else{
                      die();
                  }
              }
          }
          $data = array(
              'i_status'  => $istatus,
              'i_approve' => $iapprove,
              'd_approve' => date('Y-m-d'),
          );
      }else{
          $data = array(
              'i_status' => $istatus,
          );
      }
      $this->db->where('id', $id);
      $this->db->where('id_company', $idcompany);
      $this->db->update('tm_masuk_pinjamanbp', $data);
    }
}
/* End of file Mmaster.php */
