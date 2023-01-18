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
                                        tm_stockopname_bp
                                    WHERE
                                        i_status <> '5'
                                        AND d_document BETWEEN to_date('$dfrom','01-mm-yyyy') AND to_date('$dto','01-mm-yyyy') AND id_company = '$id_company'
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
                              SELECT
                                 0 as no,
                                 a.id,
                                 a.id_company,
                                 a.i_document,
                                 to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                 a.i_bagian,
                                 a.i_periode,
                                 d.e_bagian_name,
                                 a.i_status,
                                 a.e_remark,
                                 c.e_status_name,
                                 c.label_color,
                                 '$i_menu' as i_menu,
                                 '$folder' as folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto 
                              FROM
                                 tm_stockopname_bp a 
                                 INNER JOIN
                                    tr_status_document c 
                                    ON (c.i_status = a.i_status) 
                                 INNER JOIN
                                    tr_bagian d 
                                    ON (a.id_company = d.id_company 
                                    and a.i_bagian = d.i_bagian) 
                              WHERE
                                 a.d_document between to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND a.id_company = '$id_company'
                                 AND a.i_status <> '5' 
                                 $bagian 
                              ORDER BY
                                 d_document DESC,
                                 a.i_document DESC
                            ", FALSE);

          $datatables->edit('e_status_name', function ($data) {
              return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
          });

          $datatables->add('action', function ($data) {
              $id        = trim($data['id']);
              $i_menu    = $data['i_menu'];
              $folder    = $data['folder'];
              $i_status  = $data['i_status'];
              $dfrom     = $data['dfrom'];
              $dto       = $data['dto'];
              $data      = '';
              
              if(check_role($i_menu, 2)){
                  $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
              }
              
              if (check_role($i_menu, 3)) {
                  if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                      $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                  }
              }

              if (check_role($i_menu, 7)) {
                  if ($i_status == '2') {
                      $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                  }
              }   

              if (check_role($i_menu, 4)  && ($i_status=='1')) {
                  $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
              }

              return $data;
          });
          $datatables->hide('id');
          $datatables->hide('i_menu');       
          $datatables->hide('folder');
          $datatables->hide('dfrom');
          $datatables->hide('dto');
          $datatables->hide('label_color');
          $datatables->hide('id_company');
          $datatables->hide('i_status');
          $datatables->hide('i_bagian');
          return $datatables->generate();
    }

    public function runningid() {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_stockopname_bp');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl, $tahun, $ibagian){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_stockopname_bp
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SO';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
              tm_stockopname_bp
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
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

    public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_stockopname_bp');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function get_bagian($ibagian, $idcompany){
        return $this->db->query("
                                  SELECT
                                    i_bagian, e_bagian_name 
                                    FROM
                                       tr_bagian 
                                    WHERE
                                        i_bagian = '$ibagian' 
                                    AND 
                                        id_company = '$idcompany'
                                ", FALSE);
    }

    public function dataheader($idcompany, $i_document, $ibagian){
        return $this->db->query("
                                  SELECT
                                      id 
                                  FROM
                                     tm_stockopname_bp 
                                  WHERE
                                    id_company = '$idcompany' 
                                    AND i_bagian = '$ibagian' 
                                    AND i_document = '$i_document'
                                ", FALSE);
    }

    public function datadetail($idcompany, $ddocument, $ibagian) {
          $ddocument      = DateTime::createFromFormat('d-m-Y', $ddocument);
          $id_company     = $this->session->userdata('id_company');
          $i_periode      = $ddocument->format('Ym');
          $d_jangka_awal  = '9999-01-01';
          $d_jangka_akhir = '9999-01-31';
          $dfrom          = $ddocument->format('Y-m-01');
          $dto            = $ddocument->format('Y-m-d');

          return $this->db->query("             
                                    SELECT
                                       x.id_company,
                                       x.i_material,
                                       a.id,
                                       a.e_material_name,
                                       b.e_satuan_name,
                                       0 as n_quantity,
                                       '' as e_remark 
                                    FROM
                                       f_mutasi_saldoawal_bp('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x 
                                       INNER JOIN
                                          tr_material a 
                                          ON (a.id_company = x.id_company 
                                          AND a.i_material = x.i_material) 
                                       INNER JOIN
                                          tr_satuan b 
                                          ON (a.id_company = b.id_company 
                                          AND a.i_satuan_code = b.i_satuan_code)
                                  ", FALSE);
    }

    /*----------  CARI BARANG  ----------*/
    public function barang($cari, $ibagian, $ddocument) {

          $ddocument      = DateTime::createFromFormat('d-m-Y', $ddocument);
          $id_company     = $this->session->userdata('id_company');
          $i_periode      = $ddocument->format('Ym');
          $d_jangka_awal  = '9999-01-01';
          $d_jangka_akhir = '9999-01-31';
          $dfrom          = $ddocument->format('Y-m-01');
          $dto            = $ddocument->format('Y-m-d');

          return $this->db->query("            
                                    SELECT
                                       x.id_company,
                                       x.i_material,
                                       a.id,
                                       a.e_material_name,
                                       b.e_satuan_name 
                                    FROM
                                       f_mutasi_saldoawal_bp('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x 
                                       INNER JOIN
                                          tr_material a 
                                          ON (a.id_company = x.id_company 
                                          AND a.i_material = x.i_material) 
                                       INNER JOIN
                                          tr_satuan b 
                                          ON (a.id_company = b.id_company 
                                          AND a.i_satuan_code = b.i_satuan_code)
                                  ", FALSE);
    }

    public function simpan($id,$idcompany,$ibagian,$idocument,$ddocument,$iperiode, $eremarkh){
          $dentry = current_datetime();
          $data = array(
                        'id'           => $id,
                        'id_company'   => $idcompany,
                        'i_document'   => $idocument,
                        'd_document'   => $ddocument,
                        'i_bagian'     => $ibagian,
                        'i_periode'    => $iperiode,
                        'e_remark'     => $eremarkh,
                        'd_entry'      => $dentry,
          );
          $this->db->insert('tm_stockopname_bp', $data);
    }

    public function simpandetail($idcompany, $id, $idmaterial, $qty, $eremark){
          $data = array(
                        'id_company'      => $idcompany,
                        'id_document'     => $id,
                        'id_material'     => $idmaterial,
                        'n_quantity'      => $qty,
                        'e_remark'        => $eremark,
          );
      $this->db->insert('tm_stockopname_bp_item', $data);
    }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus){
        $dreceive = '';
        $dreceive = date('Y-m-d');
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $data = array(
                            'i_status'  => $istatus,
                            'e_approve' => $iapprove,
                            'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                            'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->update('tm_stockopname_bp', $data);
    }

    public function dataheader_edit($id){
        return $this->db->query("
                                  SELECT
                                     a.id,
                                     a.i_document,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     a.e_remark,
                                     a.i_status,
                                     a.i_bagian,
                                     b.e_bagian_name 
                                  FROM
                                     tm_stockopname_bp a 
                                     INNER JOIN
                                        tr_bagian b 
                                        ON (a.i_bagian = b.i_bagian 
                                        AND a.id_company = b.id_company) 
                                  WHERE
                                     a.id = '$id'
                                ", FALSE);
    }

    public function datadetail_edit($id) {
        return $this->db->query("
                                  SELECT
                                     a.id_material as id,
                                     b.i_material,
                                     b.e_material_name,
                                     c.e_satuan_name,
                                     a.n_quantity,
                                     a.e_remark 
                                  FROM
                                     tm_stockopname_bp_item a 
                                     INNER JOIN
                                        tr_material b 
                                        ON (a.id_material = b.id) 
                                     INNER JOIN
                                        tr_satuan c 
                                        ON (b.i_satuan_code = c.i_satuan_code 
                                        AND b.id_company = c.id_company) 
                                  WHERE
                                     id_document = '$id'
                                ", FALSE);
    }

    public function updateheader($id, $eremarkh){
        $data=array(
                    'e_remark' => $eremarkh,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_stockopname_bp', $data);
    } 

    public function hapusdetail($id) {
          return $this->db->query(" 
                                    DELETE
                                    FROM
                                       tm_stockopname_bp_item 
                                    WHERE
                                       id_document = '$id'
                                  ", FALSE);
    } 
}
/* End of file Mmaster.php */