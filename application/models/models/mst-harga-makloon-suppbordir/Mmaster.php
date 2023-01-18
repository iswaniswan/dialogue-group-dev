<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function getkelompok($id){
    $query = $this->db->query("SELECT i_kode_kelompok FROM tr_supplier WHERE i_supplier = '$id'", FALSE);
    foreach($query->result() as $row){
      $kodekelompok = $row->i_kode_kelompok;
    }
    if($kodekelompok == 'all'){
      return $this->db->query("
                              SELECT 
                                a.i_kode_kelompok,
                                a.e_nama
                              FROM
                                tm_kelompok_barang a
                                LEFT JOIN tr_supplier b
                                ON (a.i_kode_kelompok = b.i_kode_kelompok)
                              WHERE 
                                i_kode_group_barang = 'GRB0002'
                              ", FALSE);
    }else{
      return $this->db->query("
                              SELECT 
                                a.i_kode_kelompok,
                                a.e_nama
                              FROM
                                tm_kelompok_barang a
                                LEFT JOIN tr_supplier b
                                ON (a.i_kode_kelompok = b.i_kode_kelompok)
                              WHERE 
                                b.i_supplier = '$id'
                                AND i_kode_group_barang = 'GRB0002'
                              ", FALSE);
    }
  }

	public function data($dfrom, $folder, $i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    
    $query = $this->db->query("SELECT d_akhir FROM tm_price_makloon_supplier_bordir", FALSE);
    foreach($query->result() as $row){
      if($row->d_akhir != null){
        $dakhir = $row->d_akhir;

        $tmp   = explode('-', $dakhir);
        $day   = $tmp[2];
        $month = $tmp[1];
        $year  = $tmp[0];
        $dakhir = $day.'-'.$month.'-'.$year;
      }else{
        $dakhir = '01-01-5000';
      }
    }
		
    $datatables->query("
                        SELECT DISTINCT 
                          z.nomor,
                          z.d_akhir_tmp,
                          z.i_supplier,
                          z.e_supplier_name,
                          z.i_material,
                          z.e_namabrg,
                          z.i_type_code,
                          z.e_type_name,
                          z.e_satuan,
                          z.v_price,
                          z.d_berlaku,
                          z.d_akhir,
                          z.f_status_aktif,
                          z.i_menu,
                          z.dfrom,
                          z.folder
                        FROM(
                              SELECT ROW_NUMBER() OVER (ORDER BY a.i_supplier) as nomor,
                                 case when a.d_akhir is not null then a.d_akhir else '5000-01-01' end as d_akhir_tmp,
                                 a.i_supplier,
                                 b.e_supplier_name,
                                 a.i_material,
                                 c.e_namabrg,
                                 d.e_satuan,
                                 a.v_price,
                                 a.i_type_code,
                                 e.e_type_name,
                                 a.d_berlaku,
                                 a.d_akhir,
                                 a.f_status_aktif,
                                 '$i_menu' as i_menu,
                                 '$dfrom' as dfrom,
                                 '$folder' as folder
                              FROM
                                 tm_price_makloon_supplier_bordir a 
                                 INNER JOIN
                                    tr_supplier b 
                                    ON a.i_supplier = b.i_supplier 
                                 INNER JOIN
                                    tm_barang_wip c 
                                    on a.i_material = c.i_kodebrg 
                                 INNER JOIN
                                    tr_satuan d 
                                    on a.i_satuan_code = d.i_satuan_code 
                                 INNER JOIN
                                    tr_item_type e 
                                    on a.i_type_code = e.i_type_code 
                              WHERE
                                 c.status_aktif = 't'  
                        ) as z
                        WHERE
                          z.d_berlaku <= to_date('$dfrom', 'dd-mm-yyyy')
                          AND z.d_akhir_tmp >= to_date('$dakhir','dd-mm-yyyy')
                        ORDER BY
                          z.d_berlaku 
                        ",false);

        $datatables->edit('f_status_aktif', function ($data) {
          $f_status_aktif = trim($data['f_status_aktif']);
          if($f_status_aktif == 't'){
            return '<span class="label label-success label-rouded">Aktif</span>';
          }else {
            return '<span class="label label-danger label-rouded">Tidak Aktif</span>';
          }
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
        $datatables->edit('v_price', function($data){
          return number_format($data['v_price']);
        });
        $datatables->edit('e_supplier_name', function($data){
          return $data['i_supplier'].' - '.$data['e_supplier_name'];
        });
        
        $datatables->add('action', function ($data) {
          $kodebrg        = trim($data['i_material']);
          $isupplier      = trim($data['i_supplier']);
          $f_status_aktif = trim($data['f_status_aktif']);
          $dberlaku       = $data['d_berlaku'];
          $folder         = trim($data['folder']);
          $dfrom = $data['dfrom'];
          $i_menu = $data['i_menu'];
          $data = '';
          if(check_role($i_menu, 2)){
              $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$isupplier/$kodebrg/$dberlaku/$dfrom/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
          }
          if(check_role($i_menu, 3) && $f_status_aktif != 'f'){
              $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isupplier/$kodebrg/$dberlaku/$dfrom/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
          }
          if(check_role($i_menu, 4) && $f_status_aktif != 'f'){
            $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isupplier\", \"$kodebrg\"); return false;'><i class='fa fa-trash'></i></a>";
          }

          return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('i_supplier');
        $datatables->hide('i_type_code');
        $datatables->hide('dfrom');
        $datatables->hide('d_akhir_tmp');
        $datatables->hide('folder');
      return $datatables->generate();
	}

	public function cek_data($isupplier, $kodebrg, $dberlaku){
		return $this->db->query("
                            SELECT
                               a.i_supplier,
                               b.e_supplier_name,
                               a.i_material,
                               c.e_namabrg,
                               a.i_satuan_code,
                               d.e_satuan,
                               a.v_price,
                               a.d_update,
                               a.i_kode_group_barang,
                               a.i_type_makloon,
                               a.i_kode_kelompok,
                               f.e_nama,
                               a.i_type_code,
                               a.d_berlaku,
                               a.i_tipe,
                               a.d_akhir 
                            FROM
                               tm_price_makloon_supplier_bordir a 
                               JOIN
                                  tr_supplier b 
                                  ON a.i_supplier = b.i_supplier 
                               JOIN
                                  tm_barang_wip c 
                                  ON a.i_material = c.i_kodebrg 
                               JOIN
                                  tr_satuan d 
                                  ON a.i_satuan_code = d.i_satuan_code 
                               LEFT JOIN
                                  tm_kelompok_barang f 
                                  ON a.i_kode_kelompok = f.i_kode_kelompok 
                            WHERE
                               a.i_material = '$kodebrg' 
                               AND a.i_supplier = '$isupplier' 
                               AND a.d_berlaku = '$dberlaku'
                            ", false);
  }

  public function get_hargas($ikodekelompok, $ikodejenis, $isupplier, $imaterial){
    $where = '';
    if($ikodejenis != 'AJB'){
      $where .= "AND c.i_type_code = '$ikodejenis'";
    }

    if($ikodekelompok != 'AKB'){
      $where .= "and a.i_kelbrg_wip = '$ikodekelompok'";
    }
    if($imaterial != 'BRG'){
      $where .= "and a.i_kodebrg = '$imaterial'";
    }
    if($ikodejenis == 'AJB'){
      $q = $this->db->query("select * from tm_barang_wip where i_kodebrg = '$imaterial'");
    }
    $this->db->select("
                        a.*
                      FROM
                        tm_barang_wip a
                        LEFT JOIN tr_item_type c ON (a.i_jenisbrg_wip = c.i_type_code)
                        LEFT JOIN tm_kelompok_barang b ON (a.i_kelbrg_wip = b.i_kode_kelompok)
                        LEFT JOIN tr_satuan d ON (a.i_satuan_code = d.i_satuan_code)
                      WHERE
                        a.i_kodebrg||'$isupplier' 
                        NOT IN (
                          SELECT 
                            i_material||i_supplier 
                          FROM 
                            tm_price_makloon_supplier_bordir a
                          )
                        AND a.status_aktif = 't'
                        $where
                      ", FALSE);
    return $this->db->get();
  }
  
  public function cek_sup($isupplier){
    return $this->db->query("SELECT * FROM tr_supplier WHERE i_supplier='$isupplier'", FALSE);
  }

  public function cek_group($igroupbrg){
    return $this->db->query("SELECT * FROM tm_group_barang WHERE i_kode_group_barang='$igroupbrg'", FALSE);
  }

  public function satuan(){
    return $this->db->query("SELECT * FROM tr_satuan ORDER BY i_satuan_code", FALSE);
      $this->db->select('*');
      $this->db->from('tr_satuan');
      return $this->db->get();
  }

  public function delete($isupplier, $kodebrg){
    $this->db->query(" DELETE FROM tm_price_makloon_supplier_bordir WHERE i_supplier = '$isupplier' and kode_brg = '$kodebrg'");
  }

  public function getkel($igroupbrg) {
        $this->db->select("i_kode_kelompok, e_nama");
        $this->db->from('tm_kelompok_barang');
        $this->db->where('i_kode_group_barang', $igroupbrg);
        $this->db->order_by('i_kode_kelompok');
        return $this->db->get();
  }

  public function getjenis($ikodekelompok) {
        $this->db->select("i_type_code, e_type_name");
        $this->db->from('tr_item_type');
        if($ikodekelompok != 'AKB'){

        $this->db->where('i_kode_kelompok', $ikodekelompok);
        }
        $this->db->order_by('i_type_code');
        return $this->db->get();
  }

   public function getmaterial($isupplier, $ikodejenis, $ikodekelompok) {

    $where = '';
      if($ikodejenis != 'AJB'){
        $where .= "AND a.i_jenisbrg_wip = '$ikodejenis'";
      }

      if($ikodekelompok != 'AKB'){
        $where .= "and a.i_kelbrg_wip = '$ikodekelompok'";
      }

       $this->db->select("
                          a.*
                        FROM
                          tm_barang_wip a
                          LEFT JOIN tr_item_type c ON (a.i_jenisbrg_wip = c.i_type_code)
                          LEFT JOIN tm_kelompok_barang b ON (a.i_kelbrg_wip = b.i_kode_kelompok)
                          LEFT JOIN tr_satuan d ON (a.i_satuan_code = d.i_satuan_code)
                        WHERE
                          a.i_kodebrg||'$isupplier' 
                          NOT IN (
                            SELECT 
                              i_material||i_supplier 
                            FROM 
                              tm_price_makloon_supplier_bordir a
                            )
                          AND a.status_aktif = 't'
                          $where
                        ", false); 
      return $this->db->get();
  }

  public function get_supplier(){
    return $this->db->query("SELECT i_supplier, e_supplier_name FROM tr_supplier WHERE i_type_makloon = 'JNM0003' ORDER BY i_supplier", FALSE)->result();
  }

  public function get_groupbarang(){
    $this->db->select(" * from tm_group_barang where i_kode_group_barang not in('GB005','GB006') order by i_kode_group_barang");
    return $this->db->get();
  }

  public function get_satuan(){
    return $this->db->query("SELECT * FROM tr_satuan ORDER BY i_satuan", FALSE)->result();
  }

  public function insert($isupplier, $kodebrg, $harga, $ipriceno, $dberlaku, $igroupbrg, $itypemakloon, $ikodekelompok, $ikodejenis, $isatuan,  $itipe){
    $dentry = date("Y-m-d");

    $data = array(
        'i_supplier'          => $isupplier,
        'i_material'          => $kodebrg,
        'v_price'             => $harga,
        'i_satuan_code'       => $isatuan,
        'd_berlaku'           => $dberlaku,
        'i_kode_group_barang' => $igroupbrg,
        'i_kode_kelompok'     => $ikodekelompok,
        'i_type_code'         => $ikodejenis,
        'i_price_no'          => $ipriceno,
        'i_tipe'              => $itipe,
        'd_entry'             => $dentry,
        'i_type_makloon'      => $itypemakloon
    );
    $this->db->insert('tm_price_makloon_supplier_bordir', $data);
  }

  public function update($isupplier, $kodebrg, $harga, $itipe, $isatuan, $dsebelum, $dberlaku){
        $dupdate = date("Y-m-d");

        $data = array(
         'i_satuan_code' => $isatuan,
         'v_price'       => $harga,
         'i_tipe'        => $itipe,
         'd_update'      => $dupdate
        );
          $this->db->where('i_material', $kodebrg);
          $this->db->where('i_supplier', $isupplier);
          $this->db->where('d_berlaku', $dsebelum);
          $this->db->update('tm_price_makloon_supplier_bordir', $data);
  }

  public function updatetglakhir($isupplier, $kodebrg,$dsebelum, $dberlaku){
    $dupdate = date("Y-m-d");
    $dakhir = date('Y-m-d', strtotime('-1 days', strtotime( $dberlaku ))); //kurang tanggal sebanyak 1 hari

    $data = array(
     'd_akhir'       => $dakhir,
     'f_status_aktif'=> 'f',
     'd_update'      => $dupdate
    );
      $this->db->where('i_material', $kodebrg);
      $this->db->where('i_supplier', $isupplier);
      $this->db->where('d_berlaku', $dsebelum);
      $this->db->update('tm_price_makloon_supplier_bordir', $data);
  }

  public function cancel($isupplier, $kodebrg){
    $data = array(
            'f_status_aktif'=>'f',
    );
    $this->db->where('i_supplier', $isupplier);
    $this->db->where('i_material', $kodebrg);
    $this->db->update('tm_price_makloon_supplier_bordir', $data);
  }
}
/* End of file Mmaster.php */