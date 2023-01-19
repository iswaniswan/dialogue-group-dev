<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public $idcompany;
  public $i_menu = '2090401';

  function __construct(){
      parent::__construct();
      $this->idcompany = $this->session->id_company;
  }

  public function data($i_menu, $folder, $dfrom, $dto){
     $idcompany = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_retur_masuk_packing
            WHERE
                i_status <> '5'
                and d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$idcompany')

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
                        AND id_company = '$idcompany')";
            }
        }

      $datatables = new Datatables(new CodeigniterAdapter);
      $datatables->query("
                          SELECT 
                            0 as no,
                            a.id,
                            a.i_document,
                            to_char(a.d_document,'dd-mm-YYYY') as d_document,
                            a.i_bagian_pengirim,
                            f.e_bagian_name as e_bagian_pengirim,
                            a.i_bagian,
                            b.e_bagian_name,
                            a.id_document_reff,
                            d.i_document as i_reff,
                            a.e_remark, 
                            a.i_status,
                            e.e_status_name,
                            e.label_color,
                            '$i_menu' as i_menu, 
                            '$folder' as folder,
                            '$dfrom' as dfrom,
                            '$dto' as dto
                          FROM 
                            tm_retur_masuk_packing a
                            INNER JOIN tr_bagian b
                              ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                            INNER JOIN tr_bagian c
                              ON (a.i_bagian = c.i_bagian AND a.id_company = c.id_company)
                            INNER JOIN tm_retur_produksi_gdjd d
                              ON (a.id_document_reff = d.id AND a.id_company = d.id_company)
                            INNER JOIN tr_status_document e
                              ON (a.i_status = e.i_status)
                            INNER JOIN tr_bagian f 
                              ON (a.i_bagian_pengirim = f.i_bagian AND a.id_company = f.id_company)
                          WHERE 
                            a.id_company = '$idcompany'
                            AND a.i_status <> '5'
                            $bagian
                        ", FALSE);

      $datatables->edit('i_status', function ($data) {
          return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
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
              $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
          }
          if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
              $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
          }
          if (check_role($i_menu, 7)) {
              if ($i_status == '2') {
                  $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
              }
          }
          if (check_role($i_menu, 4) && $i_status == '1') {
              $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close'></i></a>";
          }
            
            
      return $data;
      });
      $datatables->hide('i_menu');
      $datatables->hide('folder');
      $datatables->hide('label_color');
      $datatables->hide('dfrom');
      $datatables->hide('dto');
      $datatables->hide('id');
      $datatables->hide('i_bagian');
      $datatables->hide('e_bagian_name');
      $datatables->hide('id_document_reff');
      $datatables->hide('e_status_name');      
      $datatables->hide('i_bagian_pengirim');
      return $datatables->generate();
  }

  public function bagianpembuat(){
      $this->db->select('a.id, a.i_bagian, e_bagian_name');
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
      $this->db->where('username', $this->session->userdata('username'));
      $this->db->where('a.id_company', $this->session->userdata('id_company'));
      $this->db->order_by('e_bagian_name');
      return $this->db->get();
  }

  public function partner($cari){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
          SELECT DISTINCT
             a.id,
             a.i_bagian,
             a.e_bagian_name
          FROM
              tr_bagian a
          JOIN 
              tm_retur_produksi_gdjd b
              ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
          WHERE
              a.id_company = '$this->idcompany'
              AND b.i_status = '6'
              AND a.i_bagian ILIKE '%$cari%'
              AND a.e_bagian_name ILIKE '%$cari%'
          ORDER BY
              a.e_bagian_name
      ", FALSE);
  }

  public function referensi($cari,$ipartner){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
         SELECT DISTINCT
            a.id,
            a.i_document,
            to_char(a.d_document, 'dd-mm-yyyy') AS d_document
          FROM
            tm_retur_produksi_gdjd a
            LEFT JOIN tm_retur_produksi_gdjd_item b
              on (a.id = b.id_document AND a.id_company = b.id_company)
          WHERE
            a.i_bagian = '$ipartner'
            AND a.i_status = '6'
            AND a.id_company = '$this->idcompany'
            AND b.n_sisa_retur <> '0'
            AND a.i_document ILIKE '%$cari%'
          ORDER BY
            i_document,
            d_document
      ", FALSE);
  }

  public function cek_kode($kode,$ibagian){
      $this->db->select('i_document');
      $this->db->from('tm_retur_masuk_packing');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata("id_company"));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function getdataitem($idreff, $ipartner){
      return $this->db->query("
                                SELECT
                                   a.id,
                                   b.id as idreff,
                                   b.i_document,
                                   to_char(b.d_document, 'dd-mm-yyyy') as d_document,
                                   a.id_product as id_product_base,
                                   c.i_product_base,
                                   c.e_product_basename,
                                   a.n_quantity,
                                   a.n_sisa_retur,
                                   c.i_color,
                                   e.id as id_color,
                                   e.e_color_name,
                                   a.e_remark 
                                FROM
                                   tm_retur_produksi_gdjd_item a 
                                   INNER JOIN
                                      tm_retur_produksi_gdjd b 
                                      ON (a.id_document = b.id 
                                      AND a.id_company = b.id_company) 
                                   INNER JOIN
                                      tr_product_base c 
                                      ON (a.id_product = c.id 
                                      AND a.id_company = c.id_company) 
                                   INNER JOIN
                                      tr_color e 
                                      ON (c.i_color = e.i_color 
                                      AND c.id_company = e.id_company) 
                                WHERE
                                   b.id = '$idreff' 
                                   AND a.id_document = '$idreff' 
                                   AND b.id_company = '$this->idcompany' 
                                   AND b.i_bagian = '$ipartner'
                              ", FALSE);
  }

  public function runningid(){
      $this->db->select('max(id) AS id');
      $this->db->from('tm_retur_masuk_packing');
      return $this->db->get()->row()->id+1;
  }

  public function runningnumber($thbl, $tahun, $ibagian){
      $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_retur_masuk_packing
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
                tm_retur_masuk_packing
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

  function insertheader($id, $idocument, $ibagian, $datedocument, $ipartner, $ireff, $eremark){

      $data = array(
                      'id'                 => $id,
                      'id_company'         => $this->idcompany,
                      'i_document'         => $idocument,
                      'd_document'         => $datedocument,
                      'id_document_reff'   => $ireff,
                      'i_bagian'           => $ibagian,
                      'i_bagian_pengirim'  => $ipartner,
                      'e_remark'           => $eremark,
                      'd_entry'            => current_datetime(),
      );
      $this->db->insert('tm_retur_masuk_packing', $data);
  }

  function insertdetail($id, $ireff, $idproduct, $icolorpro, $nquantitywip, $nquantitywipmasuk, $edesc){
      $data = array(
                      'id_company'         => $this->idcompany,
                      'id_document'        => $id,
                      'id_document_reff'   => $ireff,
                      'id_product_base'    => $idproduct,
                      'n_quantity'         => $nquantitywipmasuk,
                      'n_sisa'             => $nquantitywipmasuk,
                      'e_remark'           => $edesc,
      );
      $this->db->insert('tm_retur_masuk_packing_item', $data);
  }

  public function changestatus($id,$istatus){
      $dreceive = '';
      $dreceive = date('Y-m-d');
      $iapprove = $this->session->userdata('username');
      if ($istatus=='6') {
          $query = $this->db->query("
              SELECT 
                a.id_document_reff, 
                a.id_product_base, 
                a.n_quantity,
                b.i_bagian_pengirim
              FROM 
                tm_retur_masuk_packing_item a
                INNER JOIN tm_retur_masuk_packing b
                  ON (a.id_document = b.id AND a.id_company = b.id_company)
              WHERE 
                a.id_document = '$id' 
              ", FALSE);
          if ($query->num_rows()>0) {
              foreach ($query->result() as $key) {
                  $this->db->query("
                      UPDATE
                          tm_retur_produksi_gdjd_item
                      SET
                          n_sisa_retur = n_sisa_retur - $key->n_quantity
                      WHERE
                          id_document = '$key->id_document_reff'
                          AND id_product = '$key->id_product_base'                          
                          AND id_company = '".$this->session->userdata('id_company')."'
                  ", FALSE);
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
      $this->db->where('id_company', $this->idcompany);
      $this->db->update('tm_retur_masuk_packing', $data);
  }

  public function estatus($istatus){
      $this->db->select('e_status_name');
      $this->db->from('tr_status_document');
      $this->db->where('i_status',$istatus);
      return $this->db->get()->row()->e_status_name;
  }

  public function cek_data($id, $ibagian){
      return $this->db->query("
                                SELECT 
                                  a.id,
                                  a.i_document, 
                                  to_char(a.d_document,'dd-mm-yyyy') as d_document,
                                  a.id_document_reff,
                                  d.i_document as i_reff,
                                  to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
                                  a.i_bagian_pengirim,
                                  c.e_bagian_name as e_bagian_pengirim,
                                  a.i_bagian,
                                  b.e_bagian_name,
                                  a.e_remark,
                                  a.i_status
                                FROM
                                  tm_retur_masuk_packing a
                                  INNER JOIN tm_retur_produksi_gdjd d
                                    ON (a.id_document_reff = d.id AND a.id_company = d.id_company)
                                  INNER JOIN tr_bagian b
                                    ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                                  INNER JOIN tr_bagian c
                                    ON (a.i_bagian_pengirim = c.i_bagian AND a.id_company = c.id_company)
                                WHERE 
                                  a.id  = '$id'
                                  AND a.i_bagian = '$ibagian'
                                  AND a.id_company = '$this->idcompany'
                              ", FALSE);
  }

  public function cek_datadetail($id, $ibagian){
      return $this->db->query("
                                SELECT
                                  a.id, 
                                  a.id_document,
                                  a.id_product_base,
                                  c.i_product_base,
                                  c.e_product_basename,
                                  a.n_quantity as n_quantity_masuk,
                                  f.n_quantity as n_quantity_keluar,
                                  f.n_sisa_retur as n_quantity_sisa,
                                  c.i_color,
                                  e.id as id_color,
                                  e.e_color_name,
                                  a.e_remark
                                FROM
                                  tm_retur_masuk_packing_item a 
                                  INNER JOIN 
                                    tm_retur_masuk_packing b
                                    ON (a.id_document = b.id AND a.id_company = b.id_company)
                                  INNER JOIN 
                                    tm_retur_produksi_gdjd_item f
                                    ON (a.id_document_reff = f.id_document AND a.id_company = f.id_company)
                                  INNER JOIN 
                                    tr_product_base c
                                    ON (a.id_product_base = c.id 
                                    AND a.id_company = c.id_company AND f.id_product = c.id AND f.id_company = c.id_company) 
                                  INNER JOIN 
                                    tr_color e
                                    ON (c.i_color = e.i_color AND c.id_company = e.id_company)
                                WHERE 
                                  a.id_document = '$id'
                                  AND b.id = '$id'
                                  AND b.i_bagian = '$ibagian'
                                  AND b.id_company = '$this->idcompany'
                              ", FALSE);
  }

  public function updateheader($id, $idocument, $ibagian, $datedocument, $ipartner, $ireff, $eremark){
      $data = array(
                      'i_document'         => $idocument,
                      'd_document'         => $datedocument,
                      'i_bagian'           => $ibagian,
                      'i_bagian_pengirim'  => $ipartner,
                      'e_remark'           => $eremark,
                      'd_update'           => current_datetime(),
      );

      $this->db->where('id', $id);
      $this->db->where('id_company', $this->idcompany);
      $this->db->update('tm_retur_masuk_packing', $data);
  }

  public function deletedetail($id){
      $this->db->where('id_document', $id);
      $this->db->delete('tm_retur_masuk_packing_item');
  }

  /*public function updatedetail($id, $ireff, $idproduct, $icolorpro, $nquantitywip, $nquantitywipmasuk, $edesc){
      $data = array(
                      'n_quantity'         => $nquantitywipmasuk,
                      'e_remark'           => $edesc,
      );

      $this->db->where('id_document', $id);
      $this->db->where('id_product_base', $idproduct);
      $this->db->where('id_document_reff', $ireff);
      $this->db->where('id_company', $this->idcompany);
      $this->db->update('tm_retur_masuk_packing_item', $data);
  }*/
}
/* End of file Mmaster.php */