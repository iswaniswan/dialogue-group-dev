<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($folder, $i_menu, $dfrom, $dto){
     $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_keluar_pinjamanqcset
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
                $bagian = "AND z.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND z.i_bagian IN (SELECT
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
                                 z.id,
                                 z.id_company,
                                 z.i_document,
                                 to_char(z.d_document, 'dd-mm-yyyy') as d_document,
                                 z.i_bagian,
                                 z.id_partner,
                                 z.e_partner,
                                 z.i_status,
                                 z.e_remark, 
                                 c.e_status_name,
                                 '$i_menu' as i_menu,
                                 '$folder' as folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto,
                                 c.label_color 
                              FROM
                                (
                                    SELECT
                                       a.id,
                                       a.id_company,
                                       a.i_document,
                                       a.d_document,
                                       a.i_bagian,
                                       a.id_partner,
                                       b.e_supplier_name as e_partner,
                                       a.i_status,
                                       a.e_remark 
                                    FROM
                                       tm_keluar_pinjamanqcset a 
                                       JOIN
                                          tr_supplier b 
                                          on a.id_partner = b.id AND a.i_partner = b.i_supplier
                                       UNION ALL
                                       SELECT
                                          a.id,
                                          a.id_company,
                                          a.i_document,
                                          a.d_document,
                                          a.i_bagian,
                                          a.id_partner,
                                          b.e_nama_karyawan as e_partner,
                                          a.i_status,
                                          a.e_remark 
                                       FROM
                                          tm_keluar_pinjamanqcset a 
                                          JOIN
                                             tr_karyawan b 
                                             on a.id_partner = b.id AND a.i_partner = b.e_nik
                                 )
                                 as z
                                 JOIN
                                    tr_status_document c 
                                    ON (c.i_status = z.i_status) 
                                 JOIN
                                    tr_bagian d 
                                    ON (z.i_bagian = d.i_bagian AND z.id_company = d.id_company) 
                              WHERE
                                 z.d_document between to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND z.id_company= '$id_company' 
                                 AND z.i_status <> '5'
                              $bagian
                              ORDER BY i_document ASC
        ",false);

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
        $datatables->hide('id');
        $datatables->hide('i_bagian');
        $datatables->hide('id_partner');
        return $datatables->generate();
  }

  public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('b.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
  }

  public function runningnumber($thbl,$tahun,$ibagian) {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_keluar_pinjamanqcset 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_keluar_pinjamanqcset
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

  public function cek_kode($kode,$ibagian) {
      $this->db->select('i_document');
      $this->db->from('tm_keluar_pinjamanqcset');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function partner($cari){
      $id_company = $this->session->userdata('id_company');
      $cari = str_replace("'", "", $cari);
      return $this->db->query("   
                                SELECT
                                   id,
                                   i_partner,
                                   e_partner,
                                   id_company 
                                FROM
                                   (
                                      SELECT
                                         id,
                                         i_supplier as i_partner,
                                         e_supplier_name as e_partner,
                                         id_company 
                                      FROM
                                         tr_supplier 
                                      UNION ALL
                                      SELECT
                                         id,
                                         e_nik as i_partner,
                                         e_nama_karyawan as e_partner,
                                         id_company 
                                      FROM
                                         tr_karyawan 
                                   )
                                   as a 
                                WHERE
                                   a.id_company = '$id_company' 
                                   AND a.i_partner ILIKE '%$cari%' 
                                   AND a.e_partner ILIKE '%$cari%'
                                ORDER BY
                                    a.e_partner
                              ", FALSE);
  }

  public function referensi($cari, $id, $ipartner){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
                                SELECT
                                   a.id,
                                   a.i_document 
                                FROM
                                   tm_masuk_makloonqcset a 
                                   JOIN
                                      tr_supplier b 
                                      ON a.id_supplier = b.id 
                                WHERE
                                   a.id_supplier = '$id' 
                                   AND b.i_supplier = '$ipartner'
                                   AND a.i_document ILIKE '%$cari%'
                                ORDER BY
                                  i_document
                              ", FALSE);
  }

  /*----------  CARI BARANG  ----------*/

  public function product($cari) {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("            
                                  SELECT DISTINCT 
                                      a.id,
                                      a.i_product_wip,
                                      UPPER(a.e_product_wipname) AS e_product_wipname,
                                      b.e_color_name
                                  FROM
                                      tr_product_wip a
                                  INNER JOIN tr_color b ON
                                      (a.i_color = b.i_color
                                      AND a.id_company = b.id_company)
                                  WHERE
                                      a.id_company = '$idcompany'
                                      AND a.f_status = 't'
                                      AND b.f_status = 't'
                                      AND (a.i_product_wip ILIKE '%$cari%'
                                      OR a.e_product_wipname ILIKE '%$cari%')
                                  ORDER BY
                                      a.i_product_wip ASC
        ", FALSE);
  }

    /*----------  DETAIL BARANG  ----------*/

  public function detailproduct($id) {
        return $this->db->query("            
                                  SELECT
                                      a.id AS id_product_wip,
                                      a.i_product_wip,
                                      UPPER(a.e_product_wipname) AS e_product_wipname,
                                      d.id AS id_color,
                                      d.e_color_name,
                                      c.id AS id_material,
                                      c.i_material,
                                      UPPER(c.e_material_name) AS e_material_name
                                  FROM
                                      tr_product_wip a
                                  INNER JOIN tr_product_wip_item b ON
                                      (a.id = b.id_product_wip
                                      AND a.id_company = b.id_company)
                                  INNER JOIN tr_material c ON
                                      (b.id_material = c.id
                                      AND a.id_company = c.id_company)
                                  INNER JOIN tr_color d ON
                                      (a.i_color = d.i_color
                                      AND a.id_company = d.id_company)
                                  WHERE
                                      a.f_status = 't'
                                      AND a.id = '$id'
                                      AND a.id_company = '".$this->session->userdata('id_company')."'
                                  ORDER BY
                                      a.i_product_wip,
                                      c.i_material ASC
        ", FALSE);
  }

    /*----------  SIMPAN DATA  ----------*/
    
  public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_pinjamanqcset');
        return $this->db->get()->row()->id+1;
  }

  public function insertheader($id, $idocument, $datedocument, $ibagian, $dateback, $idpartner, $epartner, $ireff, $eremarkh){
        $data = array(
                      'id'           => $id,
                      'id_company'   => $this->session->userdata('id_company'),
                      'i_document'   => $idocument,
                      'd_document'   => $datedocument,
                      'i_bagian'     => $ibagian,
                      'd_back'       => $dateback,
                      'id_reff'      => $ireff,
                      'id_partner'   => $idpartner,
                      'i_partner'    => $epartner,
                      'e_remark'     => $eremarkh,
                      'd_entry'      => current_datetime(),
        );
        $this->db->insert('tm_keluar_pinjamanqcset', $data);
  }

  public function insertdetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark){
        $data = array(
                        'id_company'      => $this->session->userdata('id_company'),
                        'id_document'     => $id,
                        'id_product_wip'  => $idproductwip,
                        'id_material'     => $idmaterial,
                        'n_quantity_wip'  => $nquantitywip,
                        'n_sisa_wip'      => $nquantitywip,
                        'n_quantity'      => $nquantitymat,
                        'n_sisa_material' => $nquantitymat,
                        'e_remark'        => $eremark,
        );
        $this->db->insert('tm_keluar_pinjamanqcset_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id) {
        return $this->db->query("
                                  SELECT
                                     q.id,
                                     q.i_bagian,
                                     q.e_bagian_name,
                                     q.i_document,
                                     q.d_document,
                                     q.d_back,
                                     q.id_reff,
                                     q.i_reff,
                                     q.id_partner,
                                     q.i_partner,
                                     q.epartner,
                                     q.i_status,
                                     q.e_remark
                                  FROM
                                  (
                                    SELECT
                                       a.id,
                                       a.i_bagian,
                                       b.e_bagian_name,
                                       a.i_document,
                                       to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                       to_char(a.d_back, 'dd-mm-yyyy') AS d_back,
                                       a.id_reff,
                                       c.i_document as i_reff,
                                       a.id_partner,
                                       a.i_partner,
                                       d.e_supplier_name as epartner,
                                       a.i_status,
                                       a.e_remark
                                    FROM
                                       tm_keluar_pinjamanqcset a 
                                       INNER JOIN
                                          tr_bagian b 
                                          ON (b.i_bagian = a.i_bagian 
                                          AND a.id_company = b.id_company) 
                                       LEFT JOIN
                                          tm_masuk_makloonqcset c 
                                          ON (a.id_reff = c.id 
                                          AND a.id_company = c.id_company) 
                                       INNER JOIN
                                          tr_supplier d 
                                          ON (a.id_partner = d.id 
                                          AND a.i_partner = d.i_supplier 
                                          AND a.id_company = d.id_company) 
                                       UNION ALL
                                       SELECT
                                          a.id,
                                          a.i_bagian,
                                          b.e_bagian_name,
                                          a.i_document,
                                          to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                          to_char(a.d_back, 'dd-mm-yyyy') AS d_back,
                                          a.id_reff,
                                          c.i_document as i_reff,
                                          a.id_partner,
                                          a.i_partner,
                                          d.e_nama_karyawan as epartner,
                                          a.i_status,
                                          a.e_remark
                                       FROM
                                          tm_keluar_pinjamanqcset a 
                                          INNER JOIN
                                             tr_bagian b 
                                             ON (b.i_bagian = a.i_bagian 
                                             AND a.id_company = b.id_company) 
                                          LEFT JOIN
                                             tm_masuk_makloonqcset c 
                                             ON (a.id_reff = c.id 
                                             AND a.id_company = c.id_company) 
                                          INNER JOIN
                                             tr_karyawan d 
                                             ON (a.id_partner = d.id 
                                             AND a.i_partner = d.e_nik 
                                             AND a.id_company = d.id_company)
                                  )as q
                                  WHERE q.id = '$id'
        ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id) {
        return $this->db->query("
                                    SELECT
                                       a.id_product_wip,
                                       b.i_product_wip,
                                       b.e_product_wipname,
                                       a.id_material,
                                       c.i_material,
                                       c.e_material_name,
                                       a.n_quantity_wip,
                                       a.n_quantity as n_quantity_material,
                                       a.e_remark,
                                       d.e_color_name 
                                    FROM
                                       tm_keluar_pinjamanqcset_item a 
                                       INNER JOIN
                                          tr_product_wip b 
                                          ON (b.id = a.id_product_wip) 
                                       INNER JOIN
                                          tr_material c 
                                          ON (c.id = a.id_material) 
                                       INNER JOIN
                                          tr_color d 
                                          ON (d.i_color = b.i_color 
                                          AND b.id_company = d.id_company) 
                                    WHERE
                                       a.id_document = '$id' 
                                    ORDER BY
                                       a.id_product_wip,
                                       c.i_material,
                                       b.i_product_wip ASC
                                    ", FALSE);
    }

    public function cek_kodeedit($kode,$kodeold, $ibagian) {
      $this->db->select('i_document');
      $this->db->from('tm_masuk_pinjamanqcset');
      $this->db->where('i_document', $kode);
      $this->db->where('i_document <>', $kodeold);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }


    public function updateheader($id, $idocument, $datedocument, $ibagian, $dateback, $idpartner, $epartner, $ireff, $eremarkh)
    {
        $idcompany = $this->session->userdata('id_company');
        $data = array(
                      'i_document'   => $idocument,
                      'd_document'   => $datedocument,
                      'i_bagian'     => $ibagian,
                      'd_back'       => $dateback,
                      'id_reff'      => $ireff,
                      'id_partner'   => $idpartner,
                      'i_partner'    => $epartner,
                      'e_remark'     => $eremarkh,
                      'd_update'      => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_keluar_pinjamanqcset', $data);
    }

    public function deletedetail($id){
        $idcompany = $this->session->userdata('id_company');
        $this->db->query("DELETE FROM tm_keluar_pinjamanqcset_item WHERE id_document='$id' AND id_company = '$idcompany'", false);
    }

   public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_pinjamanqcset', $data);
    }  
}
/* End of file Mmaster.php */