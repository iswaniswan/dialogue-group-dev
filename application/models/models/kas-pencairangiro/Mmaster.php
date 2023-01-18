<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function __construct(){
      parent::__construct();
  }

  public function data($i_menu, $folder, $dfrom, $dto){
     $idcompany = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_giro_kliring
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
                            a.i_bagian,
                            b.id_document_reff,
                            c.i_document as i_kliring,
                            a.id_giro,
                            d.i_giro as i_giro,
                            a.e_remark, 
                            a.i_status_giro,
                            g.e_status_name as e_status_giro,
                            g.label_color as label_giro,
                            a.i_status,
                            f.e_status_name as e_status,
                            f.label_color as label_status,
                            '$i_menu' as i_menu, 
                            '$folder' as folder,
                            '$dfrom' as dfrom,
                            '$dto' as dto
                          FROM 
                            tm_giro_cair a
                            INNER JOIN tm_giro_cair_item b
                              ON (a.id = b.id_document AND a.id_company = b.id_company)
                            INNER JOIN tm_giro_kliring c
                              ON (b.id_document_reff = c.id AND b.id_company = c.id_company)
                            INNER JOIN tm_giro d
                              ON (a.id_giro = d.id AND a.id_company = d.id_company)
                            INNER JOIN tr_status_document f
                              ON (a.i_status = f.i_status)
                            INNER JOIN tr_status_giro g
                              ON (a.i_status_giro = g.i_status)
                          WHERE 
                            a.id_company = '$idcompany'
                            AND a.i_status <> '5'
                            $bagian
                        ", FALSE);

      $datatables->edit('e_status', function ($data) {
          return '<span class="label label-'.$data['label_status'].' label-rouded">'.$data['e_status'].'</span>';
      });

      $datatables->edit('e_status_giro', function ($data) {
          return '<span class="label label-'.$data['label_giro'].' label-rouded">'.$data['e_status_giro'].'</span>';
      });

      $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
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
          if (check_role($i_menu, 4) && $i_status == '1') {
              $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close'></i></a>";
          }            
            
      return $data;
      });

      $datatables->hide('id');
      $datatables->hide('i_bagian');
      $datatables->hide('id_document_reff');
      $datatables->hide('id_giro');
      $datatables->hide('i_status_giro');
      $datatables->hide('label_giro');
      $datatables->hide('i_status');
      $datatables->hide('label_status');
      $datatables->hide('i_menu');
      $datatables->hide('folder');
      $datatables->hide('dfrom');
      $datatables->hide('dto');
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

  public function cek_kode($kode,$ibagian){
      $this->db->select('i_document');
      $this->db->from('tm_giro_cair');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata("id_company"));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function runningid(){
      $this->db->select('max(id) AS id');
      $this->db->from('tm_giro_cair');
      return $this->db->get()->row()->id+1;
  }

  public function runningnumber($thbl, $tahun, $ibagian){
      $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_giro_cair 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'GRC';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_giro_cair
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
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

  public function kasbank($cari){
    $idcompany    = $this->session->userdata('id_company');
    return $this->db->query("
                              SELECT DISTINCT
                                a.id,
                                a.i_kode_kas,
                                a.e_kas_name
                              FROM
                                tr_kas_bank a     
                              JOIN tm_giro_kliring b
                                ON a.id = b.id_kas_bank AND a.id_company = b.id_company      
                              JOIN 
                                tm_giro_kliring_item c
                                ON b.id = c.id_document AND b.id_company = c.id_company
                              WHERE 
                                (a.i_kode_kas like '%$cari%' or a.e_kas_name like '%$cari%')
                              AND a.id_company = '$idcompany'
                              AND a.f_status = 't'
                              AND b.i_status = '6'
                              AND c.i_status_giro = '2'
                              ORDER BY 
                                a.e_kas_name
                            ", FALSE);
  }

  public function customer($cari, $ikasbank){
    return $this->db->query("                        
                            SELECT DISTINCT ON (b.id_customer)
                            	b.id_customer, 
                            	c.i_customer, 
                            	c.e_customer_name
                            FROM 
                            	tm_giro_kliring a 
                            	LEFT JOIN tm_giro_kliring_item b ON (
                            		a.id = b.id_document 
                            		AND a.id_company = b.id_company
                            	)
                            	INNER JOIN tr_customer c ON (
                            		b.id_customer = c.id 
                            		AND b.id_company = c.id_company 
                            	)
                            WHERE 
                            	a.id_company = '".$this->session->userdata('id_company')."'
                            	AND c.f_status = 't'
                            	AND a.id_kas_bank = '$ikasbank'
                            	AND a.i_status = '6'
                            	AND b.i_status_giro = '2' 
                            	AND (c.i_customer ILIKE '%$cari%'
                            	OR c.e_customer_name ILIKE '%$cari%')
                            ORDER BY 
                                b.id_customer, 
                                c.e_customer_name
                            ", FALSE);
  }

  public function kliring($cari, $ikasbank, $icustomer){
    $idcompany    = $this->session->userdata('id_company');
    return $this->db->query("                             
                              SELECT DISTINCT ON (a.id)
                                 a.id,
                                 a.i_document 
                              FROM
                                 tm_giro_kliring a 
                                 JOIN
                                    tm_giro_kliring_item b 
                                    ON a.id = b.id_document 
                                    AND a.id_company = b.id_company
                              WHERE 
                                (a.i_document like '%$cari%')
                              AND a.id_company = '$idcompany'
                              AND a.id_kas_bank = '$ikasbank'
                              AND b.id_customer = '$icustomer'
                              AND a.i_status = '6'
                              AND b.i_status_giro = '2'
                              ORDER BY 
                                a.id,
                                a.i_document
                            ", FALSE);
  }

  public function getgiro($cari, $ikasbank, $icustomer, $ikriling){
    $idcompany    = $this->session->userdata('id_company');
    return $this->db->query("
                              SELECT
                                 a.id,
                                 a.i_giro,
                                 a.i_document 
                              FROM
                                 tm_giro a 
                                 JOIN
                                    tm_giro_kliring_item b 
                                    ON a.id = id_document_reff 
                                    AND a.id_company = b.id_company 
                                 JOIN
                                    tm_giro_kliring c 
                                    ON b.id_document = c.id 
                                    AND a.id_company = c.id_company
                              WHERE 
                                (a.i_giro like '%$cari%')
                              AND a.id_company = '$idcompany'
                              AND c.id_kas_bank ='$ikasbank'
                              AND b.id_customer = '$icustomer'
                              AND c.id = '$ikriling'
                              AND c.i_status = '6'
                              AND b.i_status_giro = '2'
                              AND a.i_status = '6'
                              AND a.i_status_giro = '2'
                              ORDER BY 
                                a.i_giro
                            ", FALSE);
  }

  function getitemgiro($ireferensigiro, $ikriling, $ikasbank, $icustomer){
      return $this->db->query("                               
                                SELECT
                                   a.id as id_kliring,
                                   to_char(a.d_document, 'dd-mm-yyyy') as d_kliring,
                                   b.id_document_reff as id_giro,
                                   to_char(c.d_giro, 'dd-mm-yyyy') as d_giro,
                                   a.id_bank,
                                   d.e_bank_name,
                                   a.id_penyetor,
                                   e.e_nama_karyawan,
                                   b.v_jumlah 
                                FROM
                                   tm_giro_kliring a 
                                   JOIN
                                      tm_giro_kliring_item b 
                                      ON a.id = b.id_document 
                                      AND a.id_company = a.id_company 
                                   JOIN
                                      tm_giro c 
                                      ON b.id_document_reff = c.id 
                                      AND a.id_company = b.id_company 
                                   JOIN
                                      tr_bank d 
                                      ON a.id_bank = d.id 
                                   JOIN
                                      tr_karyawan e 
                                      ON a.id_penyetor = e.id 
                                      AND a.id_company = e.id_company
                                WHERE
                                   a.id = '$ikriling'
                                   AND b.id_document_reff = '$ireferensigiro'
                                   AND a.id_kas_bank = '$ikasbank'
                                   AND b.id_customer = '$icustomer' 
                                   AND b.v_sisa <> 0
                                   --AND c.v_sisa <> 0
                                   AND a.i_status = '6'
                                   AND b.i_status_giro = '2'
                                   AND c.i_status = '6'
                                   AND c.i_status_giro = '2'                                
                              ",FALSE);
  }

  function insertheader($id, $idocument, $ibagian, $datedocument, $ikasbank, $icustomer, $ikriling, $ireferensigiro, $eremark){
      $idcompany    = $this->session->userdata('id_company');
      $data = array(
                      'id'                 => $id,
                      'id_company'         => $idcompany,
                      'i_document'         => $idocument,
                      'd_document'         => $datedocument,
                      'i_bagian'           => $ibagian,
                      'id_document_reff'   => $ikriling,
                      'id_giro'            => $ireferensigiro,
                      'id_kas_bank'        => $ikasbank,
                      'id_customer'        => $icustomer,
                      'e_remark'           => $eremark,
                      'd_entry'            => current_datetime(),
      );
      $this->db->insert('tm_giro_cair', $data);
  }

  function insertdetail($id, $idkliring, $idbank, $idpenyetor, $jumlah){
      $idcompany    = $this->session->userdata('id_company');
      $data = array(
                      'id_company'         => $idcompany,
                      'id_document'        => $id,
                      'id_document_reff'   => $idkliring,
                      'id_penyetor'        => $idpenyetor,
                      'id_bank'            => $idbank,
                      'v_jumlah'           => $jumlah,
                      'v_sisa'             => $jumlah,
      );
      $this->db->insert('tm_giro_cair_item', $data);
  }
   
  public function changestatus($id,$istatus){
      $idcompany    = $this->session->userdata('id_company');     
      $dreceive = '';
      $dreceive = date('Y-m-d');
      $iapprove = $this->session->userdata('username');
      if ($istatus=='6') {
          $query = $this->db->query("
                                      SELECT 
                                        b.id_document_reff, 
                                        b.id_giro, 
                                        a.v_jumlah,
                                        a.id_document_reff as id_kliring
                                      FROM 
                                        tm_giro_cair_item a
                                        INNER JOIN tm_giro_cair b
                                          ON (a.id_document = b.id AND a.id_company = b.id_company)
                                      WHERE 
                                        a.id_document = '$id' 
                                        AND b.id_company = '".$this->session->userdata('id_company')."'
                                      ", FALSE);
          if ($query->num_rows()>0) {
              foreach ($query->result() as $key) {
                  $this->db->query("
                                      UPDATE
                                          tm_giro
                                      SET
                                          i_status_giro = '4'
                                      WHERE
                                          id = '$key->id_giro'                  
                                          AND id_company = '".$this->session->userdata('id_company')."'
                                  ", FALSE);

                  $this->db->query("
                                      UPDATE
                                          tm_giro_kliring_item
                                      SET
                                          v_sisa = v_sisa - $key->v_jumlah,
                                          i_status_giro = '4'
                                      WHERE
                                          id_document_reff = '$key->id_giro'   
                                          AND id_document = '$key->id_kliring'               
                                          AND id_company = '".$this->session->userdata('id_company')."'
                                  ", FALSE);

                //   $this->db->query("
                //                       UPDATE
                //                           tm_giro_kliring_item
                //                       SET
                //                           i_status_giro = '4'
                //                       WHERE
                //                           id = '$key->id_document_reff'                  
                //                           AND id_company = '".$this->session->userdata('id_company')."'
                //                   ", FALSE);
              }
          }
          $data = array(
              'i_status'          => $istatus,
              'i_approve'         => $iapprove,
              'd_approve'         => date('Y-m-d'),
              'i_status_giro'     => '4',
          );
      }else{
          $data = array(
              'i_status' => $istatus,
          );
      }
      $this->db->where('id', $id);
      $this->db->where('id_company', $idcompany);
      $this->db->update('tm_giro_cair', $data);
  }

  public function estatus($istatus){
      $this->db->select('e_status_name');
      $this->db->from('tr_status_document');
      $this->db->where('i_status',$istatus);
      return $this->db->get()->row()->e_status_name;
  }

  public function cek_data($id, $idcompany){   
      return $this->db->query("
                                SELECT
                                   a.id,
                                   a.i_document,
                                   to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                   a.i_bagian,
                                   c.e_bagian_name,
                                   a.id_document_reff,
                                   f.i_document as i_kliring,
                                   a.id_giro,
                                   g.i_giro,
                                   a.id_kas_bank,
                                   d.e_kas_name,
                                   a.id_customer,
                                   e.e_customer_name,
                                   a.i_status,
                                   a.e_remark 
                                FROM
                                   tm_giro_cair a 
                                   JOIN
                                      tm_giro_cair_item b 
                                      ON a.id = b.id_document 
                                      AND a.id_company = b.id_company 
                                   JOIN
                                      tr_bagian c 
                                      ON a.i_bagian = c.i_bagian 
                                      AND a.id_company = b.id_company 
                                   JOIN
                                      tr_kas_bank d 
                                      ON a.id_kas_bank = d.id 
                                      AND a.id_company = d.id_company 
                                   JOIN
                                      tr_customer e 
                                      ON a.id_customer = e.id 
                                      AND a.id_company = e.id_company 
                                   JOIN
                                      tm_giro_kliring f 
                                      ON a.id_document_reff = f.id 
                                      AND a.id_company = f.id_company 
                                   JOIN
                                      tm_giro g 
                                      ON a.id_giro = g.id 
                                      AND a.id_company = g.id_company
                                WHERE 
                                  a.id = '$id'
                                  AND b.id_company = '$idcompany'
                              ", FALSE);
  }

  public function cek_datadetail($id, $idcompany){
      return $this->db->query("                             
                                SELECT
                                   b.id,
                                   b.id_document_reff,
                                   c.i_document as i_kliring,
                                   b.id_giro,
                                   d.i_giro,
                                   a.id_penyetor,
                                   e.e_nama_karyawan,
                                   a.id_bank,
                                   f.e_bank_name,
                                   a.v_jumlah,
                                   k.v_sisa 
                                FROM
                                   tm_giro_cair_item a 
                                   JOIN
                                      tm_giro_cair b 
                                      ON a.id_document = b.id 
                                      AND a.id_company = b.id_company 
                                   JOIN
                                      tm_giro_kliring c 
                                      ON b.id_document_reff = c.id 
                                      AND a.id_company = c.id_company 
                                   JOIN
                                      tm_giro_kliring_item k 
                                      ON c.id = k.id_document
                                      AND b.id_giro = k.id_document_reff 
                                      AND c.id_company = k.id_company 
                                   JOIN
                                      tm_giro d 
                                      ON b.id_giro = d.id 
                                      AND k.id_document_reff = d.id
                                      AND a.id_company = d.id_company 
                                   JOIN
                                      tr_karyawan e 
                                      ON a.id_penyetor = e.id 
                                      AND a.id_company = e.id_company 
                                   JOIN
                                      tr_bank f 
                                      ON a.id_bank = f.id
                                WHERE 
                                  a.id_document = '$id'
                                  AND b.id_company = '$idcompany'
                              ", FALSE);
  }

  public function updateheader($id, $idocument, $ibagian, $datedocument, $ikasbank, $icustomer, $ikriling, $ireferensigiro, $eremark){
      $idcompany    = $this->session->userdata('id_company');  
      $data = array(
                      'i_document'         => $idocument,
                      'd_document'         => $datedocument,
                      'i_bagian'           => $ibagian,
                      'id_document_reff'   => $ikriling,
                      'id_giro'            => $ireferensigiro,
                      'id_kas_bank'        => $ikasbank,
                      'id_customer'        => $icustomer,
                      'e_remark'           => $eremark,
                      'd_update'           => current_datetime(),
      );

      $this->db->where('id', $id);
      $this->db->where('id_company', $idcompany);
      $this->db->update('tm_giro_cair', $data);
  }

  function deletedetail($id){
      $idcompany    = $this->session->userdata('id_company'); 
      return $this->db->query("DELETE FROM tm_giro_cair_item WHERE id_document = '$id' AND id_company = '$idcompany'", FALSE);
  }
}
/* End of file Mmaster.php */