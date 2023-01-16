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
                tm_giro_tolakan
            WHERE
                d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
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
                            d.i_document as i_kliring,
                            a.id_giro,
                            c.i_giro as i_giro,
                            a.e_remark, 
                            '$i_menu' as i_menu, 
                            '$folder' as folder,
                            '$dfrom' as dfrom,
                            '$dto' as dto
                          FROM 
                            tm_giro_tolakan a
                            INNER JOIN tm_giro_tolakan_item b
                              ON (a.id = b.id_document AND a.id_company = b.id_company)
                            INNER JOIN tm_giro c
                              ON (a.id_giro = c.id AND b.id_company = c.id_company)
                            INNER JOIN tm_giro_kliring d
                              ON (a.id_document_reff = d.id AND a.id_company = d.id_company)
                          WHERE 
                            a.id_company = '$idcompany'
                            $bagian
                        ", FALSE);

      $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';

            if(check_role($i_menu, 2)){
              $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
          }
          // if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
          //     $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
          // }
          // if (check_role($i_menu, 7)) {
          //     if ($i_status == '2') {
          //         $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
          //     }
          // }
          // if (check_role($i_menu, 4) && $i_status == '1') {
          //     $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close'></i></a>";
          // }                 
      return $data;
      });

      $datatables->hide('id');
      $datatables->hide('i_bagian');
      $datatables->hide('id_document_reff');
      $datatables->hide('i_menu');
      $datatables->hide('folder');
      $datatables->hide('dfrom');
      $datatables->hide('dto');
      $datatables->hide('id_giro');
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
      $this->db->from('tm_giro_kliring');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata("id_company"));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function runningid(){
      $this->db->select('max(id) AS id');
      $this->db->from('tm_giro_tolakan');
      return $this->db->get()->row()->id+1;
  }

  public function runningnumber($thbl, $ibagian){
      $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_giro_tolakan 
            WHERE i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'GRT';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_giro_tolakan
            WHERE to_char (d_document, 'yyyy') >= '".date('Y')."'
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

  public function referensikriling($cari){
      $idcompany    = $this->session->userdata('id_company');
      return $this->db->query("
                              SELECT DISTINCT ON (a.id)
                                a.id,
                                a.i_document
                              FROM
                                tm_giro_kliring a  
                              JOIN tm_giro_kliring_item b ON a.id = b.id_document AND a.id_company = b.id_company
                              WHERE 
                                (a.i_document like '%$cari%')
                                AND a.id_company = '$idcompany'
                                AND a.i_status = '6'
                                AND b.i_status_giro = '2'
                                AND b.v_sisa <> 0
                                --AND a.id NOT IN (SELECT id_document_reff FROM tm_giro_tolakan)
                              ORDER BY 
                                a.id
                            ", FALSE);
  }

  function getgiro($ikriling){
        return $this->db->query("
                                SELECT
                                    b.id_document_reff,
                                    c.i_document,
                                    c.i_giro 
                                FROM
                                    tm_giro_kliring a 
                                    JOIN
                                       tm_giro_kliring_item b 
                                       ON a.id = b.id_document 
                                       AND a.id_company = b.id_company 
                                    JOIN
                                       tm_giro c 
                                       ON b.id_document_reff = c.id 
                                       AND b.id_company = c.id_company 
                                WHERE
                                    a.id = '$ikriling' 
                                    AND b.i_status_giro = '2' 
                                    AND a.i_status = '6'
                                  ", FALSE);
  }

  function getitemgiro($ireferensigiro, $ikriling){
        $ireferensigiro    = $this->input->post('ireferensigiro');
        $ikriling          = $this->input->post('ikriling');

        return $this->db->query("
                                SELECT
                                   a.id as id_kliring, 
                                   c.id as id_giro,
                                   a.i_document,
                                   to_char(a.d_document, 'dd-mm-yyyy') as d_kliring,
                                   to_char(c.d_document, 'dd-mm-yyyy') as d_giro,
                                   a.id_bank,
                                   d.e_bank_name,
                                   a.id_kas_bank,
                                   e.e_kas_name,
                                   a.id_penyetor,
                                   f.e_nama_karyawan,
                                   b.v_jumlah 
                                FROM
                                    tm_giro_kliring a 
                                    JOIN
                                       tm_giro_kliring_item b 
                                       ON a.id = b.id_document 
                                       AND a.id_company = b.id_company 
                                    JOIN
                                       tm_giro c 
                                       ON b.id_document_reff = c.id 
                                       AND b.id_company = c.id_company 
                                    JOIN
                                       tr_bank d 
                                       ON a.id_bank = d.id 
                                    JOIN
                                       tr_kas_bank e 
                                       ON a.id_kas_bank = e.id 
                                    JOIN
                                       tr_karyawan f 
                                       ON a.id_penyetor = f.id 
                                WHERE
                                   b.id_document_reff = '$ireferensigiro' 
                                   AND a.id = '$ikriling' 
                                   AND a.i_status = '6' 
                                   AND b.i_status_giro = '2'
                                ", FALSE);
  }

  function insertheader($id, $idocument, $ibagian, $datedocument, $ikriling, $ireferensigiro, $eremark){
      $idcompany    = $this->session->userdata('id_company');
      $data = array(
                      'id'                 => $id,
                      'id_company'         => $idcompany,
                      'i_document'         => $idocument,
                      'd_document'         => $datedocument,
                      'i_bagian'           => $ibagian,
                      'id_document_reff'   => $ikriling,
                      'id_giro'            => $ireferensigiro,
                      'e_remark'           => $eremark,
                      'd_entry'            => current_datetime(),
      );
      $this->db->insert('tm_giro_tolakan', $data);
  }

  function insertdetail($id, $idkrilinggiro, $idgiro, $ibank, $itujuan, $ipenyetor, $jumlah){
      $idcompany    = $this->session->userdata('id_company');
      $data = array(
                      'id_company'         => $idcompany,
                      'id_document'        => $id,
                      'id_document_reff'   => $idkrilinggiro,
                      'id_penyetor'        => $ipenyetor,
                      'id_kas_bank'        => $itujuan,
                      'id_bank'            => $ibank,
                      'v_jumlah'           => $jumlah,
      );
      $this->db->insert('tm_giro_tolakan_item', $data);
  }

  /* NOMOR GIRO TIDAK DIGUNAKAN KEMBALI */
  function updatekliring($id, $idkrilinggiro, $idgiro, $jumlah){
      $query = $this->db->query("
                SELECT 
                  a.id_giro,
                  a.id_document_reff,                 
                  b.v_jumlah
                FROM 
                    tm_giro_tolakan a
                    JOIN tm_giro_tolakan_item b ON a.id = b.id_document AND a.id_company = b.id_company
                WHERE 
                  a.id_document_reff = '$idkrilinggiro' 
                  AND a.id_company = '".$this->session->userdata('id_company')."'
              ", FALSE);
      if ($query->num_rows()>0) {
          foreach ($query->result() as $key) {
              $this->db->query("
                  UPDATE
                      tm_giro_kliring_item
                  SET
                      v_sisa = v_sisa - $key->v_jumlah,
                      i_status_giro = '3'
                  WHERE
                      id_document_reff = '$key->id_giro'
                      AND id_document = '$id'                     
                      AND id_company = '".$this->session->userdata('id_company')."'
              ", FALSE);

            //   $this->db->query("
            //       UPDATE
            //           tm_giro_kliring
            //       SET
            //           i_status_kliring = '3'
            //       WHERE
            //           id = '$key->id_document_reff'                     
            //           AND id_company = '".$this->session->userdata('id_company')."'
            //   ", FALSE);

              $this->db->query("
                  UPDATE
                      tm_giro
                  SET
                      i_status_giro = '3'
                  WHERE
                       id = '$key->id_giro'                         
                      AND id_company = '".$this->session->userdata('id_company')."'
              ", FALSE);
          }
      }
  }

  /* NOMOR GIRO DIGUNAKAN KEMBALI */
  /* id_document_reff = referensi dari nomor kliring
     id_giro = referensi dari nomor giro
  */
  function updategiro($id, $idkrilinggiro, $idgiro, $jumlah){
      $query = $this->db->query("
                SELECT 
                  a.id_giro,    
                  a.id_document_reff,               
                  b.v_jumlah
                FROM 
                  tm_giro_tolakan a
                JOIN tm_giro_tolakan_item b ON a.id = b.id_document AND a.id_company = b.id_company
                WHERE 
                  a.id_giro = '$idgiro' 
                  AND a.id_company = '".$this->session->userdata('id_company')."'
              ", FALSE);
      if ($query->num_rows()>0) {
          foreach ($query->result() as $key) {
              $this->db->query("
                  UPDATE
                      tm_giro
                  SET
                      v_sisa = v_sisa + $key->v_jumlah,
                      i_status_giro = '1'
                  WHERE
                      id = '$key->id_giro'                     
                      AND id_company = '".$this->session->userdata('id_company')."'
              ", FALSE);

            //   $this->db->query("
            //       UPDATE
            //           tm_giro_kliring_item
            //       SET
                      
            //       WHERE
            //           id_document = '$key->id_document_reff'                     
            //           AND id_company = '".$this->session->userdata('id_company')."'
            //   ", FALSE);

              $this->db->query("
                  UPDATE
                      tm_giro_kliring_item
                  SET
                      v_sisa = v_sisa - $key->v_jumlah,
                      i_status_giro = '3'
                  WHERE
                      id_document_reff = '$key->id_giro'        
                      AND id_document =  '$idkrilinggiro'             
                      AND id_company = '".$this->session->userdata('id_company')."'
              ", FALSE);
          }
      }
  } 

  public function cek_data($id, $idcompany){   
      return $this->db->query("
                                SELECT
                                   a.id,
                                   a.i_document,
                                   to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                   a.i_bagian,
                                   b.e_bagian_name,
                                   a.id_document_reff,
                                   c.i_document as i_kliring,
                                   a.id_giro,
                                   d.i_giro,                                   
                                   a.e_remark 
                                FROM
                                   tm_giro_tolakan a 
                                   JOIN
                                      tr_bagian b 
                                      ON a.i_bagian = b.i_bagian 
                                      AND a.id_company = b.id_company 
                                   JOIN
                                      tm_giro_kliring c 
                                      ON a.id_document_reff = c.id 
                                      AND a.id_company = c.id_company 
                                   JOIN
                                      tm_giro d 
                                      ON a.id_giro = d.id 
                                      AND a.id_company = d.id_company 
                                WHERE 
                                  a.id  = '$id'
                                  AND a.id_company = '$idcompany'
                              ", FALSE);
  }

  public function cek_datadetail($id, $idcompany){
      return $this->db->query("
                                  SELECT DISTINCT ON (a.id_document)
                                      a.id_document,
                                      a.id_document_reff,
                                      to_char(gr.d_document, 'dd-mm-yyyy') as d_kliring,
                                      b.id_giro,
                                      to_char(d.d_document, 'dd-mm-yyyy') as d_giro,
                                      a.id_penyetor,
                                      kar.e_nama_karyawan,
                                      a.id_kas_bank,
                                      e.e_kas_name,
                                      a.id_bank,
                                      f.e_bank_name,
                                      a.v_jumlah
                                  FROM
                                      tm_giro_tolakan_item a                                      
                                   JOIN
                                      tm_giro_tolakan b 
                                      ON a.id_document = b.id 
                                      AND a.id_company = b.id_company 
                                   JOIN
                                      tm_giro_kliring_item c 
                                      ON a.id_document_reff = c.id_document 
                                      AND a.id_company = c.id_company 
                                   JOIN
                                      tm_giro_kliring gr 
                                      ON c.id_document = gr.id 
                                      AND c.id_company = gr.id_company 
                                   JOIN
                                      tm_giro d 
                                      ON b.id_giro = d.id 
                                      AND a.id_company = d.id_company 
                                   JOIN
                                      tr_karyawan kar 
                                      ON a.id_penyetor = kar.id 
                                      AND a.id_company = kar.id_company 
                                   JOIN
                                      tr_kas_bank e 
                                      ON a.id_kas_bank = e.id 
                                      AND a.id_company = e.id_company 
                                   JOIN
                                      tr_bank f 
                                      ON a.id_bank = f.id 
                                      AND a.id_company = f.id_company 
                                  WHERE 
                                    a.id_document = '$id'
                                    AND b.id_company = '$idcompany'
                              ", FALSE);
  }
}
/* End of file Mmaster.php */