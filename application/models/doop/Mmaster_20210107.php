<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  function data($i_menu,$folder,$dfrom,$dto){
      $id_company = $this->session->userdata('id_company');
      $cek = $this->db->query("
          SELECT
              i_bagian
          FROM
              tm_sj
          WHERE
              i_status <> '5'
              and d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$id_company'
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
                      AND username = '".$this->session->userdata('username')."'
                      AND id_company = '$id_company')";
          }
      }

      $datatables = new Datatables(new CodeigniterAdapter);
      $datatables->query("                              
                            SELECT DISTINCT
                                0 AS NO,
                                a.id,
                                a.i_document,
                                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                a.id_type_spb,
                                a.id_customer,
                                b.e_customer_name,
                                a.id_document_reff,
                                ba.i_document as document_referensi,
                                a.e_remark,
                                e_status_name,
                                label_color,
                                a.i_status,
                                '$i_menu' AS i_menu,
                                '$folder' AS folder,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto
                            FROM
                                tm_sj a
                            INNER JOIN tr_status_document d ON
                                (d.i_status = a.i_status)
                            INNER JOIN tm_spb ba ON
                                (a.id_document_reff = ba.id AND ba.id_company = a.id_company)
                            LEFT JOIN tr_customer b ON
                                (b.id = a.id_customer AND b.id_company = a.id_company)
                            WHERE
                                a.i_status <> '5'
                            AND a.d_document BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                            AND a.id_company = '$id_company'
                            $bagian
                        ", FALSE);

           $datatables->edit('i_status', function ($data) {
              return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
          });

          $datatables->add('action', function ($data) {
              $id            = trim($data['id']);
              $idtypespb     = trim($data['id_type_spb']);
              $i_status      = trim($data['i_status']);
              $dfrom         = trim($data['dfrom']);
              $dto           = trim($data['dto']);
              $i_menu        = $data['i_menu'];
              $folder        = trim($data['folder']);
              $data       = '';
              if(check_role($i_menu, 2)){
                  $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$idtypespb/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
              }
              if (check_role($i_menu, 3)) {
                  if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                      $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$idtypespb/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                  }
              }
              if (check_role($i_menu, 7)) {
                  if ($i_status == '2') {
                      $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$idtypespb/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                  }
              }
              if (check_role($i_menu, 4) && ($i_status=='1')) {
                  $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
              }
                       
              return $data;
          });
          $datatables->hide('i_menu');
          $datatables->hide('folder');
          $datatables->hide('label_color');
          $datatables->hide('e_status_name');
          $datatables->hide('id_document_reff');
          $datatables->hide('id');
          $datatables->hide('dfrom');
          $datatables->hide('dto');
          $datatables->hide('id_customer');
          $datatables->hide('id_document_reff');
          $datatables->hide('id_type_spb');
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

  public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_sj');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
  }

  public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_sj');
        return $this->db->get()->row()->id+1;
  }

  public function runningnumber($thbl, $ibagian){
       $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_sj
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
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
                tm_sj
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl', 1, 2)
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

  public function jenisspb($cari){
      return $this->db->query("    
                                SELECT
                                    DISTINCT a.id,
                                    a.e_type_name
                                FROM
                                  tr_type_spb a            
                                WHERE
                                    (e_type_name ILIKE '%$cari%')
                                ORDER BY id
                              ", FALSE);      
  }

  public function area($cari){
      return $this->db->query("    
                                SELECT
                                    DISTINCT a.id,
                                    a.i_area,
                                    a.e_area
                                FROM
                                  tr_area a            
                                WHERE
                                    a.f_status = 't'
                                    AND (i_area LIKE '%$cari%' OR e_area ILIKE '%$cari%')
                                ORDER BY id
                              ", FALSE);      
  }

  public function customer($cari, $iarea, $ijenis){
      if($ijenis == '1'){
        return $this->db->query("    
                                  SELECT
                                      DISTINCT a.id,
                                      a.i_customer,
                                      a.e_customer_name
                                  FROM
                                    tr_customer a   
                                  JOIN tm_spb b ON a.id = b.id_customer AND a.id_company = b.id_company            
                                  WHERE
                                      a.f_status = 't'
                                      AND b.i_status = '6'
                                      AND b.id_area = '$iarea'
                                      AND (a.e_customer_name ILIKE '%$cari%')
                                      AND a.id_company = '".$this->session->userdata('id_company')."'
                                  ORDER BY id
                                ", FALSE);
      }else if($ijenis == '2'){
        return $this->db->query("    
                                  SELECT
                                      DISTINCT a.id,
                                      a.i_customer,
                                      a.e_customer_name
                                  FROM
                                    tr_customer a   
                                  JOIN tm_spb_ds b ON a.id = b.id_customer AND a.id_company = b.id_company            
                                  WHERE
                                      a.f_status = 't'
                                      AND b.i_status = '6'
                                      AND b.id_area = '$iarea'
                                      AND (a.e_customer_name ILIKE '%$cari%')
                                      AND a.id_company = '".$this->session->userdata('id_company')."'
                                  ORDER BY id
                                ", FALSE);
      }else if($ijenis == '3'){
        return $this->db->query("    
                                  SELECT
                                      DISTINCT a.id,
                                      a.i_customer,
                                      a.e_customer_name
                                  FROM
                                    tr_customer a   
                                  JOIN tm_spb_distributor b ON a.id = b.id_customer AND a.id_company = b.id_company
                                  WHERE
                                      a.f_status = 't'
                                      AND b.i_status = '6'
                                      AND (a.e_customer_name ILIKE '%$cari%')
                                      AND a.id_company = '".$this->session->userdata('id_company')."'
                                  ORDER BY id
                                ", FALSE);
      }
      
  }

  public function referensi($cari, $icustomer, $ijenis){
        if($ijenis == '1'){
          return $this->db->query("    
                                    SELECT DISTINCT
                                       a.i_document,
                                       a.id,
                                       to_char(d_document, 'dd-mm-yyyy') as d_document 
                                    FROM
                                       tm_spb a 
                                    INNER JOIN tm_spb_item c 
                                      ON a.id = c.id_document AND a.id_company = c.id_company
                                    WHERE
                                       a.i_status = '6' 
                                       AND a.id_customer = '$icustomer' 
                                       AND COALESCE(c.n_quantity_sisa, 0) > 0 
                                       AND
                                       (
                                          TRIM(a.i_document) ILIKE '$cari%'
                                       )  
                                      AND a.id NOT IN
                                          (SELECT id_document_reff FROM tm_sj WHERE i_status = '6' AND id_type_spb = '1')
                                  ", FALSE);
        }else if($ijenis == '2'){
           return $this->db->query(" 
                                    SELECT DISTINCT
                                       a.i_document,
                                       a.id,
                                       to_char(d_document, 'dd-mm-yyyy') as d_document 
                                    FROM
                                       tm_spb_ds a 
                                    INNER JOIN tm_spb_ds_item c 
                                      ON a.id = c.id_document AND a.id_company = c.id_company
                                    WHERE
                                       a.i_status = '6' 
                                       AND a.id_customer = '$icustomer' 
                                       AND COALESCE(c.n_quantity_sisa, 0) > 0 
                                       AND
                                       (
                                          TRIM(a.i_document) ILIKE '$cari%'
                                       ) 
                                       AND a.id NOT IN
                                          (SELECT id_document_reff FROM tm_sj WHERE i_status = '6' AND id_type_spb = '2')
                                  ", FALSE);
        }else if($ijenis == '3'){
           return $this->db->query("    
                                    SELECT DISTINCT
                                       a.i_document,
                                       a.id,
                                       to_char(d_document, 'dd-mm-yyyy') as d_document 
                                    FROM
                                       tm_spb_distributor a 
                                    INNER JOIN tm_spb_distributor_item c 
                                      ON a.id = c.id_document AND a.id_company = c.id_company
                                    WHERE
                                       a.i_status = '6' 
                                       AND a.id_customer = '$icustomer' 
                                       AND COALESCE(c.n_quantity_sisa, 0) > 0 
                                       AND
                                       (
                                          TRIM(a.i_document) ILIKE '$cari%'
                                       )
                                       AND a.id NOT IN
                                          (SELECT id_document_reff FROM tm_sj WHERE i_status = '6' AND id_type_spb = '3')
                                  ", FALSE);
        }

  }

  public function getdetailrefeks($id, $ijenis){
      if($ijenis == '1'){
        return $this->db->query("
                                  SELECT
                                     a.i_document,
                                     a.id,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     b.id_product,
                                     c.i_product_base,
                                     c.e_product_basename,
                                     b.n_quantity,
                                     b.n_quantity_sisa,
                                     d.n_customer_toplength
                                  FROM
                                      tm_spb a 
                                     INNER JOIN
                                        tm_spb_item b 
                                        ON (a.id = b.id_document AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_product_base c 
                                        ON (b.id_product = c.id AND b.id_company = c.id_company) 
                                     INNER JOIN 
                                        tr_customer d 
                                        ON (a.id_customer = d.id AND a.id_company = d.id_company)
                                  WHERE
                                     COALESCE (b.n_quantity_sisa, 0) > 0  
                                     AND a.id = '$id'
                                  ORDER BY
                                     a.i_document,
                                     c.e_product_basename ASC
                                ", FALSE);
      }else if($ijenis == '2'){
        return $this->db->query("
                                  SELECT
                                     a.i_document,
                                     a.id,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     b.id_product,
                                     c.i_product_base,
                                     c.e_product_basename,
                                     b.n_quantity,
                                     b.n_quantity_sisa,
                                     d.n_customer_toplength
                                  FROM
                                      tm_spb_ds a 
                                     INNER JOIN
                                        tm_spb_ds_item b 
                                        ON (a.id = b.id_document AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_product_base c 
                                        ON (b.id_product = c.id AND b.id_company = c.id_company) 
                                     INNER JOIN 
                                        tr_customer d 
                                        ON (a.id_customer = d.id AND a.id_company = d.id_company)
                                  WHERE
                                     COALESCE (b.n_quantity_sisa, 0) > 0  
                                     AND a.id = '$id'
                                  ORDER BY
                                     a.i_document,
                                     c.e_product_basename ASC
                                ", FALSE);
      }else if($ijenis == '3'){
        return $this->db->query("
                                  SELECT
                                     a.i_document,
                                     a.id,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     b.id_product,
                                     c.i_product_base,
                                     c.e_product_basename,
                                     b.n_quantity,
                                     b.n_quantity_sisa,
                                     d.n_customer_toplength
                                  FROM
                                      tm_spb_distributor a 
                                     INNER JOIN
                                        tm_spb_distributor_item b 
                                        ON (a.id = b.id_document AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_product_base c 
                                        ON (b.id_product = c.id AND b.id_company = c.id_company) 
                                     INNER JOIN 
                                        tr_customer d 
                                        ON (a.id_customer = d.id AND a.id_company = d.id_company)
                                  WHERE
                                     COALESCE (b.n_quantity_sisa, 0) > 0  
                                     AND a.id = '$id'
                                  ORDER BY
                                     a.i_document,
                                     c.e_product_basename ASC
                                ", FALSE);
      }
  }    

  public function changestatus($id,$istatus){
        $iapprove  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if ($istatus=='6') {
            $query = $this->db->query("
                SELECT id_document, id_product, n_quantity, n_quantity_sisa, id_document_reff
                FROM tm_sj_item
                WHERE id_document = '$id' ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                   $nsisa =  $this->db->query("
                        SELECT
                            n_quantity_sisa
                        FROM
                            tm_spb_item                       
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product = '$key->id_product'
                            AND id_company  = '".$this->session->userdata('id_company')."'
                            AND n_quantity_sisa >= '$key->n_quantity'
                    ", FALSE);

                  if($nsisa->num_rows()>0){
                    $this->db->query("
                        UPDATE
                            tm_spb_item
                        SET
                            n_quantity_sisa      = n_quantity_sisa - $key->n_quantity
                        WHERE
                            id_document     = '$key->id_document_reff'
                            AND id_product = '$key->id_product'
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
        $this->db->update('tm_sj', $data);
  }

  public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
  }

  public function insertheader($id, $ibagian, $isj, $datedocument, $icustomer, $ireferensi, $eremark, $iarea, $ijenis, $ncustop){
        $data = array(
                        'id'                    => $id,
                        'id_company'            => $this->session->userdata('id_company'),
                        'i_document'            => $isj,
                        'd_document'            => $datedocument,
                        'id_type_spb'           => $ijenis,
                        'id_document_reff'      => $ireferensi,
                        'i_bagian'              => $ibagian,
                        'id_customer'           => $icustomer,
                        'n_customer_toplength'  => $ncustop,
                        'id_area'               => $iarea,
                        'e_remark'              => $eremark,
                        'd_entry'               => current_datetime(),            
        );
        $this->db->insert('tm_sj', $data);
  }

  public function insertdetail($id, $iproduct, $nquantitymemo, $nsisa, $nquantity, $edesc, $ireferensi){               
        $data = array(   
                        'id_company'            => $this->session->userdata('id_company'),
                        'id_document'           => $id,
                        'id_document_reff'      => $ireferensi,
                        'id_product'            => $iproduct,
                        'n_quantity'            => $nquantity,
                        'n_quantity_sisa'       => $nquantity,
                        'e_remark'              => $edesc,         
        );
        $this->db->insert('tm_sj_item', $data);
  }

  public function baca_header($id, $idtypespb){
      if($idtypespb == '1'){
        return $this->db->query(" 
                                  SELECT
                                     a.id,
                                     a.id_company,
                                     a.i_document,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     a.i_bagian,
                                     e.e_bagian_name,
                                     c.i_bagian as ibagian_reff,
                                     a.id_document_reff,
                                     c.i_document as i_referensi,
                                     to_char(c.d_document, 'dd-mm-yyyy') as d_referensi,
                                     a.id_customer,
                                     b.e_customer_name,
                                     a.id_area,
                                     f.e_area,
                                     a.i_status,
                                     d.e_status_name,
                                     a.e_remark,
                                     a.id_type_spb,
                                     g.e_type_name,
                                     a.n_customer_toplength
                                  FROM
                                     tm_sj a 
                                     INNER JOIN
                                        tr_area f
                                        ON a.id_area = f.id 
                                     INNER JOIN
                                        tr_customer b 
                                        ON a.id_customer = b.id AND a.id_company = b.id_company
                                     INNER JOIN
                                        tm_spb c 
                                        ON a.id_document_reff = c.id AND a.id_company = c.id_company 
                                     INNER JOIN
                                        tr_status_document d 
                                        ON d.i_status = a.i_status
                                     INNER JOIN
                                        tr_bagian e 
                                        ON e.i_bagian = a.i_bagian AND a.id_company = e.id_company
                                     INNER JOIN 
                                        tr_type_spb g 
                                        ON a.id_type_spb = g.id AND a.id_company = g.id_company
                                  WHERE
                                     a.id = '$id'
                                " ,FALSE); 
      }else if($idtypespb == '2'){
          return $this->db->query(" 
                                  SELECT
                                     a.id,
                                     a.id_company,
                                     a.i_document,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     a.i_bagian,
                                     e.e_bagian_name,
                                     c.i_bagian as ibagian_reff,
                                     a.id_document_reff,
                                     c.i_document as i_referensi,
                                     to_char(c.d_document, 'dd-mm-yyyy') as d_referensi,
                                     a.id_customer,
                                     b.e_customer_name,
                                     a.id_area,
                                     f.e_area,
                                     a.i_status,
                                     d.e_status_name,
                                     a.e_remark,
                                     a.id_type_spb,
                                     g.e_type_name,
                                     a.n_customer_toplength 
                                  FROM
                                     tm_sj a 
                                     INNER JOIN
                                        tr_area f
                                        ON a.id_area = f.id 
                                     INNER JOIN
                                        tr_customer b 
                                        ON a.id_customer = b.id AND a.id_company = b.id_company
                                     INNER JOIN
                                        tm_spb_ds c 
                                        ON a.id_document_reff = c.id AND a.id_company = c.id_company 
                                     INNER JOIN
                                        tr_status_document d 
                                        ON d.i_status = a.i_status
                                     INNER JOIN
                                        tr_bagian e 
                                        ON e.i_bagian = a.i_bagian AND a.id_company = e.id_company
                                     INNER JOIN 
                                        tr_type_spb g 
                                        ON a.id_type_spb = g.id AND a.id_company = g.id_company
                                  WHERE
                                     a.id = '$id'
                                " ,FALSE); 
      }else if($idtypespb == '3'){
          return $this->db->query(" 
                                  SELECT
                                     a.id,
                                     a.id_company,
                                     a.i_document,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     a.i_bagian,
                                     e.e_bagian_name,
                                     c.i_bagian as ibagian_reff,
                                     a.id_document_reff,
                                     c.i_document as i_referensi,
                                     to_char(c.d_document, 'dd-mm-yyyy') as d_referensi,
                                     a.id_customer,
                                     b.e_customer_name,
                                     a.id_area,
                                     f.e_area,
                                     a.i_status,
                                     d.e_status_name,
                                     a.e_remark,
                                     a.id_type_spb,
                                     g.e_type_name,
                                     a.n_customer_toplength 
                                  FROM
                                     tm_sj a 
                                     INNER JOIN
                                        tr_area f
                                        ON a.id_area = f.id 
                                     INNER JOIN
                                        tr_customer b 
                                        ON a.id_customer = b.id AND a.id_company = b.id_company
                                     INNER JOIN
                                        tm_spb_distributor c 
                                        ON a.id_document_reff = c.id AND a.id_company = c.id_company 
                                     INNER JOIN
                                        tr_status_document d 
                                        ON d.i_status = a.i_status
                                     INNER JOIN
                                        tr_bagian e 
                                        ON e.i_bagian = a.i_bagian AND a.id_company = e.id_company
                                     INNER JOIN 
                                        tr_type_spb g 
                                        ON a.id_type_spb = g.id AND a.id_company = g.id_company
                                  WHERE
                                     a.id = '$id'
                                " ,FALSE); 
      }
  }

  public function baca_detail($id, $idtypespb){
      if($idtypespb == '1'){
          return $this->db->query("
                                SELECT DISTINCT ON (a.id_product)
                                   b.id,
                                   b.id_company,
                                   b.i_document,
                                   b.id_document_reff,
                                   a.id_product,
                                   c.i_product_base,
                                   c.e_product_basename,
                                   d.n_quantity as nquantity_permintaan,
                                   d.n_quantity_sisa as nquantity_pemenuhan,
                                   a.n_quantity,
                                   a.n_quantity_sisa,
                                   d.v_price,
                                   d.n_diskon1,
                                   d.n_diskon2,
                                   d.n_diskon3,
                                   d.v_diskontambahan,
                                   a.e_remark 
                                FROM
                                   tm_sj_item a 
                                   JOIN
                                      tm_sj b 
                                      ON b.id = a.id_document 
                                   JOIN
                                      tr_product_base c 
                                      ON a.id_product = c.id 
                                   JOIN
                                      tm_spb_item d 
                                      ON b.id_document_reff = d.id_document
                                   WHERE b.id = '$id'
                              ", FALSE);
      }else if($idtypespb == '2'){
          return $this->db->query("
                                SELECT DISTINCT ON (a.id_product)
                                   b.id,
                                   b.id_company,
                                   b.i_document,
                                   b.id_document_reff,
                                   a.id_product,
                                   c.i_product_base,
                                   c.e_product_basename,
                                   d.n_quantity as nquantity_permintaan,
                                   d.n_quantity_sisa as nquantity_pemenuhan,
                                   a.n_quantity,
                                   a.n_quantity_sisa,
                                   d.v_price,
                                   d.n_diskon1,
                                   d.n_diskon2,
                                   d.n_diskon3,
                                   d.v_diskontambahan,
                                   a.e_remark 
                                FROM
                                   tm_sj_item a 
                                   JOIN
                                      tm_sj b 
                                      ON b.id = a.id_document 
                                   JOIN
                                      tr_product_base c 
                                      ON a.id_product = c.id 
                                   JOIN
                                      tm_spb_ds_item d 
                                      ON b.id_document_reff = d.id_document
                                   WHERE b.id = '$id'
                              ", FALSE);
      }else if($idtypespb == '3'){
          return $this->db->query("
                                SELECT DISTINCT ON (a.id_product)
                                   b.id,
                                   b.id_company,
                                   b.i_document,
                                   b.id_document_reff,
                                   a.id_product,
                                   c.i_product_base,
                                   c.e_product_basename,
                                   d.n_quantity as nquantity_permintaan,
                                   d.n_quantity_sisa as nquantity_pemenuhan,
                                   a.n_quantity,
                                   a.n_quantity_sisa,
                                   d.v_price,
                                   d.n_diskon1,
                                   d.n_diskon2,
                                   d.n_diskon3,
                                   d.v_diskontambahan,
                                   a.e_remark 
                                FROM
                                   tm_sj_item a 
                                   JOIN
                                      tm_sj b 
                                      ON b.id = a.id_document 
                                   JOIN
                                      tr_product_base c 
                                      ON a.id_product = c.id 
                                   JOIN
                                      tm_spb_distributor_item d 
                                      ON b.id_document_reff = d.id_document
                                   WHERE b.id = '$id'
                              ", FALSE);
      }
  }

  public function updateheader($id, $ibagian, $isj, $datedocument, $icustomer, $ireferensi, $eremark, $iarea, $ijenis, $ncustop){
        $idcompany = $this->session->userdata('id_company');
        $data = array(
                        'i_document'            => $isj,
                        'd_document'            => $datedocument,
                        'id_document_reff'      => $ireferensi,
                        'i_bagian'              => $ibagian,
                        'id_customer'           => $icustomer,
                        'n_customer_toplength'  => $ncustop,
                        'id_area'               => $iarea,
                        'id_type_spb'           => $ijenis,
                        'e_remark'              => $eremark,
                        'd_update'              => current_datetime(),              
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_sj', $data);
  }

  public function deletedetail($id){
        $idcompany = $this->session->userdata('id_company');
        $this->db->query("DELETE FROM tm_sj_item WHERE id_document='$id' AND id_company = '$idcompany'", false);
  }

  public function runningidspb($ijenis){
      if($ijenis == '1'){
          $this->db->select('max(id) AS id');
          $this->db->from('tm_spb');
          return $this->db->get()->row()->id+1;
      }else if($ijenis == '2'){
          $this->db->select('max(id) AS id');
          $this->db->from('tm_spb_ds');
          return $this->db->get()->row()->id+1;
      }else if($ijenis == '3'){
          $this->db->select('max(id) AS id');
          $this->db->from('tm_spb_distributor');
          return $this->db->get()->row()->id+1;
      }
  }

  public function runningnumberspb($thbl, $ibagian, $ijenis){
      if($ijenis == '1'){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_spb
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SPB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_spb
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 3) = '$kode'
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
      }else if($ijenis == '2'){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 5) AS kode 
            FROM tm_spb_ds 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SPBDS';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 12, 6)) AS max
            FROM
                tm_spb_ds
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            AND substring(i_document, 1, 5) = '$kode'
            AND substring(i_document, 7, 2) = substring('$thbl',1,2)
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
      }else if($ijenis == '3'){
         $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 4) AS kode
            FROM tm_spb_distributor
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SPBD';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 11, 6)) AS max
            FROM
                tm_spb_distributor
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '".$this->session->userdata("id_company")."'
                AND substring(i_document, 1, 4) = '$kode'
                AND substring(i_document, 6, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
            ", FALSE);
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
  }

  public function insertspbnew($idbaru, $ibagian, $idocument, $datedocument, $iarea, $icustomer, $ireferensi, $datereferensi, $ndiskontotal, $nkotor, $nbersih, $vdpp, $vppn, $eremark, $ijenis){
      if($ijenis == '1'){
        $query = $this->db->query("
                                    SELECT d_estimate, id_area, id_sales, i_referensi_op
                                    FROM tm_spb
                                    WHERE id = '$ireferensi' AND d_document = '$datereferensi'
                                  ", FALSE);
        if($query->num_rows()>0) {
          foreach ($query->result() as $key) {
              $destimate    = $key->d_estimate;
              $idarea       = $key->id_area;
              $idsales      = $key->id_sales;
              $ireferensiop = $key->i_referensi_op;
          }
        }

        $data = array(
                        'id'               => $idbaru,
                        'id_company'       => $this->session->userdata('id_company'),
                        'i_document'       => $idocument,
                        'd_document'       => $datedocument,
                        'i_bagian'         => $ibagian,
                        'd_estimate'       => $destimate,
                        'id_customer'      => $icustomer,
                        'id_area'          => $idarea,
                        'id_sales'         => $idsales, 
                        'id_spb_referensi' => $ireferensi,
                        'v_diskon'         => $ndiskontotal,
                        'v_kotor'          => $nkotor,
                        'v_ppn'            => $vppn,
                        'v_bersih'         => $nbersih,
                        'v_dpp'            => $vdpp,
                        'e_remark'         => $eremark,
                        'd_entry'          => current_datetime(),            
        );
        $this->db->insert('tm_spb', $data);

      }else if($ijenis == '2'){
         $query = $this->db->query("
                                    SELECT d_estimate, id_area, id_sales, i_referensi_op
                                    FROM tm_spb_ds
                                    WHERE id = '$ireferensi' AND d_document = '$datereferensi'
                                  ", FALSE);
        if($query->num_rows()>0) {
          foreach ($query->result() as $key) {
              $destimate    = $key->d_estimate;
              $idarea       = $key->id_area;
              $idsales      = $key->id_sales;
              $ireferensiop = $key->i_referensi_op;
          }
        }

        $data = array(
                        'id'               => $idbaru,
                        'id_company'       => $this->session->userdata('id_company'),
                        'i_document'       => $idocument,
                        'd_document'       => $datedocument,
                        'i_bagian'         => $ibagian,
                        'd_estimate'       => $destimate,
                        'id_customer'      => $icustomer,
                        'id_area'          => $idarea,
                        'id_sales'         => $idsales, 
                        'id_spb_referensi' => $ireferensi,
                        'v_diskon'         => $ndiskontotal,
                        'v_kotor'          => $nkotor,
                        'v_ppn'            => $vppn,
                        'v_bersih'         => $nbersih,
                        'v_dpp'            => $vdpp,
                        'e_remark'         => $eremark,
                        'd_entry'          => current_datetime(),            
        );
        $this->db->insert('tm_spb_ds', $data);

      }else if ($ijenis == '3'){
         $query = $this->db->query("
                                    SELECT id_area, i_referensi
                                    FROM tm_spb_distributor
                                    WHERE id = '$ireferensi' AND d_document = '$datereferensi'
                                  ", FALSE);
        if($query->num_rows()>0) {
          foreach ($query->result() as $key) {
              $idarea       = $key->id_area;
              $ireferensiop = $key->i_referensi;
          }
        }

        $data = array(
                        'id'               => $idbaru,
                        'id_company'       => $this->session->userdata('id_company'),
                        'i_document'       => $idocument,
                        'd_document'       => $datedocument,
                        'i_bagian'         => $ibagian,
                        'i_referensi'      => $ireferensiop,
                        'id_customer'      => $icustomer,
                        'id_area'          => $idarea,
                        'id_spb_referensi' => $ireferensi,
                        'v_diskon'         => $ndiskontotal,
                        'v_kotor'          => $nkotor,
                        'v_ppn'            => $vppn,
                        'v_bersih'         => $nbersih,
                        'v_dpp'            => $vdpp,
                        'e_remark'         => $eremark,
                        'd_entry'          => current_datetime(),            
        );
        $this->db->insert('tm_spb_distributor', $data);
      }
  }   

  public function insertdetailspb($idbaru, $iproduct, $nsisa, $vprice, $_1ndiskon, $_2ndiskon, $_3ndiskon, $_1vdiskon, $_2vdiskon, $_3vdiskon, $vdiskonadd, $vtdiskon, $vtotal, $vtotalbersih, $edesc, $nsisab, $ijenis){
      if($ijenis == '1'){
        $data = array(
                        'id_company'        => $this->session->userdata('id_company'),
                        'id_document'       => $idbaru,
                        'id_product'        => $iproduct,
                        'n_quantity'        => $nsisab,
                        'n_quantity_sisa'   => $nsisab,
                        'v_price'           => $vprice,
                        'n_diskon1'         => $_1ndiskon,
                        'n_diskon2'         => $_2ndiskon,
                        'n_diskon3'         => $_3ndiskon,
                        'v_diskon1'         => $_1vdiskon,
                        'v_diskon2'         => $_2vdiskon,
                        'v_diskon3'         => $_3vdiskon,
                        'v_diskontambahan'  => $vdiskonadd,
                        'v_total_discount'  => $vtdiskon,
                        'v_total'           => $vtotal,
                        'e_remark'          => $edesc,
        );
        $this->db->insert('tm_spb_item', $data);

      }else if($ijenis == '2'){
        $data = array(
                        'id_company'        => $this->session->userdata('id_company'),
                        'id_document'       => $idbaru,
                        'id_product'        => $iproduct,
                        'n_quantity'        => $nsisab,
                        'n_quantity_sisa'   => $nsisab,
                        'v_price'           => $vprice,
                        'n_diskon1'         => $_1ndiskon,
                        'n_diskon2'         => $_2ndiskon,
                        'n_diskon3'         => $_3ndiskon,
                        'v_diskon1'         => $_1vdiskon,
                        'v_diskon2'         => $_2vdiskon,
                        'v_diskon3'         => $_3vdiskon,
                        'v_diskontambahan'  => $vdiskonadd,
                        'v_total_discount'  => $vtdiskon,
                        'v_total'           => $vtotal,
                        'e_remark'          => $edesc,
        );
        $this->db->insert('tm_spb_ds_item', $data);

      }else if($ijenis == '3'){
        $data = array(
                        'id_company'        => $this->session->userdata('id_company'),
                        'id_document'       => $idbaru,
                        'id_product'        => $iproduct,
                        'n_quantity'        => $nsisab,
                        'n_quantity_sisa'   => $nsisab,
                        'v_price'           => $vprice,
                        'n_diskon1'         => $_1ndiskon,
                        'n_diskon2'         => $_2ndiskon,
                        'n_diskon3'         => $_3ndiskon,
                        'v_diskon1'         => $_1vdiskon,
                        'v_diskon2'         => $_2vdiskon,
                        'v_diskon3'         => $_3vdiskon,
                        'v_diskontambahan'  => $vdiskonadd,
                        'v_total_discount'  => $vtdiskon,
                        'v_total'           => $vtotal,
                        'e_remark'          => $edesc,
        );
        $this->db->insert('tm_spb_distributor_item', $data);
      }
  }

  public function updateheaderspbold($ireferensi, $dreferensi, $nkotorold, $nbersihold, $vdppold, $vppnold, $ijenis){
      if($ijenis == '1'){
        $idcompany = $this->session->userdata('id_company');

        $data = array(
                        'v_kotor'           => $nkotorold,
                        'v_ppn'             => $vppnold,
                        'v_bersih'          => $nbersihold,
                        'v_dpp'             => $vdppold,
        );
        $this->db->where('id', $ireferensi);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb', $data);

      }else if($ijenis == '2'){
        $idcompany = $this->session->userdata('id_company');

        $data = array(
                        'v_kotor'           => $nkotorold,
                        'v_ppn'             => $vppnold,
                        'v_bersih'          => $nbersihold,
                        'v_dpp'             => $vdppold,
        );
        $this->db->where('id', $ireferensi);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb_ds', $data);

      }else if($ijenis == '3'){
        $idcompany = $this->session->userdata('id_company');

        $data = array(
                        'v_kotor'           => $nkotorold,
                        'v_ppn'             => $vppnold,
                        'v_bersih'          => $nbersihold,
                        'v_dpp'             => $vdppold,
        );
        $this->db->where('id', $ireferensi);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb_distributor', $data);
      }
  }

  public function updatedetailspbold($ireferensi, $iproduct, $nquantity, $vtotalold, $vtotalbersihold, $ijenis){
      $idcompany = $this->session->userdata('id_company');
      if($ijenis == '1'){
        $data = array(
                        'n_quantity'        => $nquantity,
                        'n_quantity_sisa'   => 0,
                        'v_total'           => $vtotalold,
        );
        $this->db->where('id_document', $ireferensi);
        $this->db->where('id_product', $iproduct);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb_item', $data);

      }else if($ijenis == '2'){
        $data = array(
                        'n_quantity'        => $nquantity,
                        'n_quantity_sisa'   => 0,
                        'v_total'           => $vtotalold,
        );
        $this->db->where('id_document', $ireferensi);
        $this->db->where('id_product', $iproduct);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb_ds_item', $data);

      }else if($ijenis == '3'){
        $data = array(
                        'n_quantity'        => $nquantity,
                        'n_quantity_sisa'   => 0,
                        'v_total'           => $vtotalold,
        );
        $this->db->where('id_document', $ireferensi);
        $this->db->where('id_product', $iproduct);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb_distributor_item', $data);
      }
  }

  public function updatestatus($id){
        $iapprove  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        
        $data = array(
            'i_status'  => '6',
            'i_approve' => $iapprove,
            'd_approve' => date('Y-m-d'),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_sj', $data);
  }
}
/* End of file Mmaster.php */