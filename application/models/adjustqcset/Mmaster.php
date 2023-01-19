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
                tm_adjustment_qcset
            WHERE
                i_status <> '5'
                and d_adjustment between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        /*AND i_level = '".$this->session->userdata('i_level')."'*/
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
                              SELECT
                                 0 as no,
                                 a.id,
                                 a.i_adjustment,
                                 to_char(a.d_adjustment, 'dd-mm-yyyy') as d_adjustment,
                                 d.e_bagian_name,
                                 a.e_remark,
                                 a.i_status,
                                 c.e_status_name,
                                 '$i_menu' as i_menu,
                                 '$folder' as folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto,
                                 c.label_color 
                              FROM
                                 tm_adjustment_qcset a 
                                 INNER JOIN
                                    tr_status_document c 
                                    ON (c.i_status = a.i_status) 
                                 INNER JOIN
                                    tr_bagian d 
                                    ON (a.i_bagian = d.i_bagian 
                                    AND a.id_company = d.id_company) 
                              WHERE
                                 a.i_status <> '5' 
                                 AND a.d_adjustment BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND a.id_company = '$id_company' 
                                 $bagian 
                              ORDER BY
                                 a.id ASC
                            ",FALSE);

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
            $data          = '';

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

            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
                } 
            }

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
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
        return $datatables->generate();
  }

  public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('b.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.i_type', '08');
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
  }

  public function runningnumber($thbl,$tahun,$ibagian) {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_adjustment, 1, 3) AS kode 
            FROM tm_adjustment_qcset 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'ADJ';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_adjustment, 10, 6)) AS max
            FROM
                tm_adjustment_qcset
            WHERE to_char (d_adjustment, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND substring(i_adjustment, 1, 3) = '$kode'
            AND substring(i_adjustment, 5, 2) = substring('$thbl',1,2)
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
      $this->db->select('i_adjustment');
      $this->db->from('tm_adjustment_qcset');
      $this->db->where('i_adjustment', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function cek_kodeedit($kode,$kodeold, $ibagian) {
      $this->db->select('i_adjustment');
      $this->db->from('tm_adjustment_qcset');
      $this->db->where('i_adjustment', $kode);
      $this->db->where('i_adjustment <>', $kodeold);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
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
  public function runningid()
  {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_adjustment_qcset');
        return $this->db->get()->row()->id+1;
  }

  public function simpan($id,$idocument,$ddocument,$ibagian,$eremarkh)
  {
        $data = array(
                        'id'                    => $id,
                        'id_company'            => $this->session->userdata('id_company'),
                        'i_adjustment'          => $idocument,
                        'd_adjustment'          => $ddocument,
                        'i_bagian'              => $ibagian,
                        'e_remark'              => $eremarkh,
                        'd_entry'               => current_datetime(),
        );
        $this->db->insert('tm_adjustment_qcset', $data);
  }

  public function simpandetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark)
  {
        $data = array(
                        'id_company'            => $this->session->userdata('id_company'),
                        'id_adjustment'         => $id,
                        'id_product_wip'        => $idproductwip,
                        'id_material'           => $idmaterial,
                        'n_quantity_wip'        => $nquantitywip,
                        'n_quantity_material'   => $nquantitymat,
                        'e_remark'              => $eremark,
        );
        $this->db->insert('tm_adjustment_qcset_item', $data);
  }

  /*----------  DATA EDIT HEADER  ----------*/
  public function dataedit($id) {
        return $this->db->query("
                                  SELECT
                                      a.id,
                                      a.i_bagian,
                                      a.i_adjustment,
                                      to_char(a.d_adjustment, 'dd-mm-yyyy') AS d_adjustment,
                                      a.e_remark,
                                      b.e_bagian_name,
                                      a.i_status
                                  FROM
                                      tm_adjustment_qcset a
                                  INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
                                  WHERE a.id = '$id'
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
                                  a.n_quantity_material,
                                  a.e_remark,
                                  d.e_color_name
                                      FROM tm_adjustment_qcset_item a
                                      INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)
                                      INNER JOIN tr_material c ON (c.id = a.id_material)
                                      INNER JOIN tr_color d ON (d.i_color = b.i_color AND b.id_company = d.id_company)            
                                      WHERE a.id_adjustment = '$id'
                                      ORDER BY a.id_product_wip, c.i_material, b.i_product_wip ASC
                              ", FALSE);
  }


  public function updateheader($id,$idocument,$ddocument,$ibagian,$eremarkh)
  {
        $data = array(
            'i_adjustment'   => $idocument,
            'd_adjustment'   => $ddocument,
            'i_bagian'     => $ibagian,
            'e_remark'     => $eremarkh,
            'd_update'      => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_adjustment_qcset', $data);
  }

  public function deletedetail($id){
        $this->db->query("DELETE FROM tm_adjustment_qcset_item WHERE id_adjustment='$id'", false);
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
        $this->db->update('tm_adjustment_qcset', $data);
  }  
}
/* End of file Mmaster.php */