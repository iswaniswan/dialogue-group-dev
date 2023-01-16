<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($dberlaku, $i_menu, $folder){
    $datatables = new Datatables(new CodeigniterAdapter);
    $idcompany  = $this->session->userdata('id_company');
    $now = date('d-m-Y');

    $datatables->query("
                        SELECT DISTINCT
                           x.no,
                           x.d_akhir_tmp,
                           x.id_hargabarang,
                           x.id_material,
                           x.i_material,
                           x.e_material_name,
                           x.e_harga,
                           x.v_price,
                           x.d_berlaku,
                           x.d_akhir,
                           x.status,
                           x.i_menu,
                           x.folder,
                           x.tanggal_berlaku,
                           x.id_company 
                        FROM
                           (
                        SELECT
                           0 as no,
                           case
                              when
                           a.d_akhir is not null 
                              then
                           a.d_akhir 
                              else
                           '5000-01-01' 
                           end
                           as d_akhir_tmp, a.id as id_hargabarang, c.i_material, a.id_material, c.e_material_name, a.v_price, a.d_berlaku, a.d_akhir, a.id_company, d.e_harga,
                           case
                              when
                           a.f_status = TRUE 
                              then
                           'Aktif' 
                              else
                           'Tidak Aktif' 
                           end
                           as status, 
                           '$i_menu' as i_menu, 
                           '$folder' as folder, 
                           '$dberlaku' as tanggal_berlaku 
                        FROM
                           tr_harga_jualbb a 
                        LEFT JOIN
                           tr_material c on a.id_material = c.id and a.id_company = c.id_company 
                        INNER JOIN
                           tr_harga_kode d on a.id_harga_kode = d.id and a.id_company = d.id_company 
                        WHERE
                           a.id_company = '$idcompany' 
                           
                           )
                           AS x 
                        WHERE
                           x.d_berlaku <= to_date('$dberlaku', 'dd-mm-yyyy') 
                           AND x.d_akhir_tmp >= to_date('$dberlaku', 'dd-mm-yyyy')
                          ",FALSE);

        $datatables->edit(
        'status', 
                function ($data) {
                    $id             = trim($data['id_hargabarang']);
                    $kode           = trim($data['id_material']);
                    $folder         = $data['folder'];
                    $id_menu        = $data['i_menu'];
                    $status         = $data['status'];
                    if ($status=='Aktif') {
                        $warna = 'success';
                    }else{
                        $warna = 'danger';
                    }
                    $data    = '';
                    $combine = $id.'|'.$kode;
                    if(check_role($id_menu, 3)){
                        $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$combine\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }else{
                        $data   .= "<span class=\"label label-$warna\">$status</span>";
                    }
                    return $data;
                }
        );

        $datatables->edit('v_price', function ($data) {
          return "Rp. ".$data['v_price'];
        });

        $datatables->edit('d_berlaku', function ($data) {
          $d_berlaku = $data['d_berlaku'];
          if($d_berlaku == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_berlaku) );
          }
        });

        $datatables->edit('d_akhir', function ($data) {
          $d_akhir = $data['d_akhir'];
          if($d_akhir == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($d_akhir) );
          }
        });
        
        $datatables->add('action', function ($data) {
            $id         = $data['id_hargabarang'];
            $kodebrg    = trim($data['id_material']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dberlaku   = $data['d_berlaku'];
            $dfrom      = $data['tanggal_berlaku'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$kodebrg/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$kodebrg/$dberlaku/$dfrom/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }

      return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('d_akhir_tmp');
        $datatables->hide('tanggal_berlaku');
        $datatables->hide('id_hargabarang');
        $datatables->hide('id_company');
        $datatables->hide('id_material');

      return $datatables->generate();
  }

  public function status($id, $iproduct){
          $this->db->select('f_status');
          $this->db->from('tr_harga_jualbb');
          $this->db->where('id', $id);
          $this->db->where('id_material', $iproduct);
          $query = $this->db->get();
          if ($query->num_rows()>0) {
              $row    = $query->row();
              $status = $row->f_status;
              if ($status=='t') {
                  $stat = 'f';
              }else{
                  $stat = 't';
              }
          }
          $data = array(
              'f_status' => $stat
          );
          $this->db->where('id', $id);
          $this->db->where('id_material', $iproduct);
          $this->db->update('tr_harga_jualbb', $data);
  }

  public function kategoribarang($cari, $idcompany){
    return $this->db->query("
                              SELECT DISTINCT
                                 a.id,
                                 a.i_kode_kelompok,
                                 a.e_nama_kelompok
                              FROM
                                 tr_kelompok_barang a 
                              JOIN 
                                 tr_material b
                                 ON a.i_kode_kelompok = b.i_kode_kelompok AND a.id_company = b.id_company
                              WHERE
                                 a.id_company = '$idcompany'
                              AND
                                 (
                                    a.i_kode_kelompok like '%$cari%' 
                                    or a.e_nama_kelompok like '%$cari%'
                                 )
                              ORDER BY
                                 a.e_nama_kelompok
                              ", FALSE);
  }

  public function getjenisbarang($ikodekelompok, $idcompany) {
      $this->db->select("id, i_type_code, e_type_name");
      $this->db->from('tr_item_type');
      $this->db->where('id_company', $idcompany);
      if($ikodekelompok != 'AKB'){
        $this->db->where('i_kode_kelompok', $ikodekelompok);
      }
      $this->db->order_by('i_type_code');
      return $this->db->get();
  }

  public function getproduct($ikodejenis, $ikodekelompok, $idcompany) {
      $where = '';
      if($ikodejenis != 'AJB'){
        $where .= "AND c.id = '$ikodejenis'";
      }
      if($ikodekelompok != 'AKB'){
        $where .= "AND d.id = '$ikodekelompok'";
      }

      return $this->db->query("
                              SELECT
                                 DISTINCT ON (a.e_material_name, a.id) a.id,
                                 a.i_material,
                                 a.e_material_name
                              FROM
                                 tr_material a 
                                 LEFT JOIN
                                    tr_item_type c 
                                    ON (a.i_type_code = c.i_type_code 
                                    and a.id_company = c.id_company) 
                                 LEFT JOIN
                                    tr_kelompok_barang d 
                                    ON (a.i_kode_kelompok = d.i_kode_kelompok 
                                    and a.id_company = d.id_company) 
                              FULL JOIN 
                                    tr_harga_kode f ON (a.id_company = f.id_company)
                              WHERE
                                 a.id_company = '$idcompany' 
                                 AND a.id::text||f.id::text NOT IN 
                                 (
                                    SELECT
                                 id_material::text||id_harga_kode::text
                                    FROM
                                 tr_harga_jualbb 
                                    WHERE
                                 id_company = '$idcompany' 
                                 )
                                 AND a.f_status = 't' 
                                 AND f.f_status = 't'
                                 $where  
                                 ORDER BY e_material_name, a.id
                              ", FALSE);
  }

  public function getinput($idkodekelompok, $ikodejenis, $iproduct, $idcompany){
      $where = '';
      if($ikodejenis != 'AJB'){
        $where .= " AND c.id = '$ikodejenis'";
      }
      if($idkodekelompok != 'AKB'){
        $where .= " AND d.id = '$idkodekelompok'";
      }
      if($iproduct != 'BRG'){
        $where .= " AND a.id = '$iproduct'";
      }

      return $this->db->query("                            
                              SELECT
                                 a.id as id_material,
                                 a.i_material,
                                 a.e_material_name,
                                 a.i_kode_kelompok,
                                 d.e_nama_kelompok,
                                 a.i_type_code,
                                 c.e_type_name,
                                 f.id as id_harga_kode,
                                 f.e_harga
                              FROM
                                 tr_material a 
                                 LEFT JOIN
                                    tr_item_type c 
                                    ON (a.i_type_code = c.i_type_code 
                                    and a.id_company = c.id_company) 
                                 LEFT JOIN
                                    tr_kelompok_barang d 
                                    ON (a.i_kode_kelompok = d.i_kode_kelompok 
                                    and a.id_company = d.id_company) 
                                 FULL JOIN 
                                    tr_harga_kode f ON (a.id_company = f.id_company)
                              WHERE
                                 a.id_company = '$idcompany' 
                                 AND a.id::text||f.id::text NOT IN 
                                 (
                                    SELECT
                                 id_material::text||id_harga_kode::text
                                    FROM
                                 tr_harga_jualbb
                                    WHERE
                                 id_company = '$idcompany' 
                                 )
                                 AND a.f_status = 't' 
                                 AND f.f_status = 't'
                                 $where  
                                 ORDER BY a.e_material_name, f.id
                              ", FALSE); 
  }

  public function getkodeharga($idcompany){
      return $this->db->query("SELECT * FROM tr_harga_kode WHERE id_company = '$idcompany' ORDER BY e_harga", FALSE)->result();
  }

  public function insert($kodebrg, $harga, $dateberlaku, $ikodeharga){
      $idcompany  = $this->session->userdata('id_company');

      $data = array(
                      'id_material'     => $kodebrg,
                      'id_harga_kode'       => $ikodeharga,
                      'v_price'             => $harga,
                      'd_berlaku'           => $dateberlaku,         
                      'id_company'          => $idcompany, 
                      'd_entry'             => current_datetime(),
      );
      $this->db->insert('tr_harga_jualbb', $data);
  }

  function cek_data($ikodebrg, $id, $idcompany){
      return $this->db->query("                              
                              SELECT
                                 a.id,
                                 a.id_material,
                                 a.id_harga_kode,
                                 a.v_price,
                                 a.d_akhir,
                                 a.f_status,
                                 to_char(a.d_berlaku, 'dd-mm-yyyy') as d_berlaku,
                                 b.i_material,
                                 b.e_material_name,
                                 b.i_kode_kelompok,
                                 d.e_nama_kelompok,
                                 b.i_type_code,
                                 e.e_type_name,
                                 f.e_harga 
                              FROM
                                 tr_harga_jualbb a 
                                 LEFT JOIN
                                    tr_material b 
                                    ON (a.id_material = b.id and a.id_company = b.id_company) 
                                 LEFT JOIN
                                    tr_kelompok_barang d 
                                    ON (b.i_kode_kelompok = d.i_kode_kelompok and b.id_company = d.id_company) 
                                 LEFT JOIN
                                    tr_item_type e 
                                    ON (b.i_type_code = e.i_type_code  and b.id_company = e.id_company) 
                                 LEFT JOIN
                                    tr_harga_kode f 
                                    ON (a.id_harga_kode = f.id and b.id_company = e.id_company) 
                              WHERE
                                 a.id_company = '$idcompany' 
                                 AND a.id_material = '$ikodebrg' 
                                 AND a.id = '$id' 
                              ORDER BY
                                 a.id_material
                              ", FALSE);
  }

  public function update($id, $kodebrg, $ikodeharga, $harga, $dateberlaku, $idcompany){

        $data = array(
                        'id_material'     => $kodebrg,
                        'id_harga_kode'       => $ikodeharga,
                        'v_price'             => $harga,
                        'd_berlaku'           => $dateberlaku,   
                        'd_update'            => current_datetime(),
        );
        $this->db->where('id_material', $kodebrg);
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_harga_jualbb', $data);
  }

  public function updatetglakhir($id, $kodebrg, $ikodeharga, $harga, $dateberlaku, $dateberlakusebelum, $idcompany){
      $dakhir   = date('Y-m-d', strtotime('-1 days', strtotime( $dateberlaku ))); //kurang tanggal sebanyak 1 hari

      $data = array(
                      'id_material'     => $kodebrg,
                      'id_harga_kode'       => $ikodeharga,
                      'v_price'             => $harga,
                      'd_berlaku'           => $dateberlaku,         
                      'id_company'          => $idcompany, 
                      'd_entry'             => current_datetime(),
      );
      $this->db->insert('tr_harga_jualbb', $data);

      $data2 = array(
                  'd_akhir'       => $dakhir,
                  'd_update'      => current_datetime(),
      );
        $this->db->where('id_material', $kodebrg);
        $this->db->where('d_berlaku', $dateberlakusebelum);
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_harga_jualbb', $data2);
  }
}
/* End of file Mmaster.php */