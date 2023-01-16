<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		
    $datatables->query("select a.i_supplier, b.e_supplier_name, a.i_material, c.e_material_name, d.e_satuan, a.v_price, 
                      a.i_type_code, e.e_type_name, a.f_status_aktif, a.d_update, '$i_menu' as i_menu
                      FROM tm_price_makloon_supplier_unitjahit a 
                      INNER JOIN tr_supplier b ON a.i_supplier = b.i_supplier
                      INNER JOIN tr_material c on a.i_material = c.i_material
                      INNER JOIN tr_satuan d on a.i_satuan_code = d.i_satuan_code
                      INNER JOIN tr_item_type e on a.i_type_code = e.i_type_code
                      WHERE c.f_status_aktif = 't' order by a.i_supplier",false);

          $datatables->edit('f_status_aktif', function ($data) {
            $f_status_aktif = trim($data['f_status_aktif']);
            if($f_status_aktif == 'f'){
               return  "Tidak Aktif";
            }else {
              return "Aktif";
            }
        });
        
        $datatables->add('action', function ($data) {
            $kodebrg        = trim($data['i_material']);
            $isupplier      = trim($data['i_supplier']);
            $f_status_aktif = trim($data['f_status_aktif']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-harga-makloon-supjahit/cform/view/$isupplier/$kodebrg\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_status_aktif != 'f'){
                $data .= "<a href=\"#\" onclick='show(\"mst-harga-makloon-supjahit/cform/edit/$isupplier/$kodebrg\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if(check_role($i_menu, 4) && $f_status_aktif != 'f'){
              $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isupplier\", \"$kodebrg\"); return false;'><i class='fa fa-trash'></i></a>";
            }

			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_supplier');
        $datatables->hide('i_type_code');
      return $datatables->generate();
	}

	function cek_data($isupplier, $kodebrg){
		$this->db->select("a.i_supplier, b.e_supplier_name, a.i_material, c.e_material_name, a.i_satuan_code, d.e_satuan, a.v_price, a.d_update, a.i_kode_group_barang, a.i_kode_kelompok, f.e_nama, a.i_type_code, d_berlaku, a.i_tipe 
                   FROM tm_price_makloon_supplier_unitjahit a 
                   JOIN tr_supplier b ON a.i_supplier = b.i_supplier 
                   JOIN tr_material c ON a.i_material = c.i_material 
                   JOIN tr_satuan d ON a.i_satuan_code = d.i_satuan_code 
                   LEFT JOIN tm_kelompok_barang f ON a.i_kode_kelompok = f.i_kode_kelompok
                   WHERE a.i_material = '$kodebrg' AND a.i_supplier = '$isupplier'", false);
        return $this->db->get();

  }

  function cek_dataproses($ikodekelompok, $ikodejenis){

      $this->db->select('a.*, b.e_satuan as nama_satuan, d.i_kode_kelompok, d.e_nama, c.kode_jenis, c.nama_jenis');
            $this->db->from('tr_material a ');
            $this->db->join('tr_satuan b','a.i_satuan_code = b.i_satuan_code');
            $this->db->join('tm_jenis_barang c','a.i_kode_kelompok = c.i_kode_kelompok');
            $this->db->join('tm_kelompok_barang d','c.i_kode_kelompok = d.i_kode_kelompok');
            $this->db->where('d.i_kode_kelompok',$ikodekelompok);
            $this->db->where('c.kode_jenis',$ikodejenis);
            $this->db->where('a.f_status_aktif','t');
            return $this->db->get();
  }

  function cek_dataprosess($ikodekelompok, $ikodejenis){
      $this->db->select("a.*, b.i_satuan, b.e_satuan as nama_satuan, d.i_kode_kelompok, d.e_nama, a.i_type_code, c.e_type_name
                        FROM tr_material a 
                        JOIN tr_satuan b ON a.i_satuan_code = b.i_satuan_code 
                        JOIN tr_item_type c ON a.i_type_code = c.i_type_code 
                        JOIN tm_kelompok_barang d ON c.i_kode_kelompok = d.i_kode_kelompok 
                        LEFT JOIN tr_supplier_materialprice e on a.i_material=e.i_material
                        LEFT JOIN tr_supplier f on f.i_supplier=e.i_supplier
                        WHERE (a.i_material, e.i_supplier)
                        NOT IN(select i_material, i_supplier from tr_supplier_materialprice)
                        and d.i_kode_kelompok = '$ikodekelompok' AND c.i_type_code = '$ikodejenis' AND a.f_status_aktif = 't'", false);
      return $this->db->get();
  }
// a.i_material ='KAI0032'
//                         and
  function get_hargas($ikodekelompok, $ikodejenis, $isupplier, $imaterial){
      $where = '';
      if($ikodejenis != 'AJB'){
        $where .= "AND c.i_type_code = '$ikodejenis'";
      }

      if($ikodekelompok != 'AKB'){
        $where .= "and d.i_kode_kelompok = '$ikodekelompok'";
      }
      if($imaterial != 'BRG'){
        $where .= "and a.i_material = '$imaterial'";
      }
//var_dump($ikodekelompok, $ikodejenis);
      //if($ikodekelompok == 'AKB' || $ikodejenis == 'AJB'){
     if($ikodejenis == 'AJB'){
        $q = $this->db->query("select * from tr_material where i_material = '$imaterial'")->row();
       // $ikodekelompok = $q->i_kode_kelompok;
       // $ikodejenis = $q->i_type_code;
        
      }
      $this->db->select(" a.i_material as i_material, a.e_material_name, a.i_satuan_code, a.i_kode_kelompok, a.i_type_code, b.i_satuan, d.e_nama, b.e_satuan, c.e_type_name FROM tr_material a
                        JOIN tr_satuan b ON a.i_satuan_code = b.i_satuan_code 
                        JOIN tr_item_type c ON a.i_type_code = c.i_type_code 
                        JOIN tm_kelompok_barang d ON c.i_kode_kelompok = d.i_kode_kelompok 
                        where  a.i_material||'$isupplier' not in(
                        select i_material||i_supplier FROM tr_supplier_materialprice a)
                         AND a.f_status_aktif = 't' $where", false); 
      return $this->db->get();
  }

  function get_data_harga($ikodekelompok, $ikodejenis, $isupplier){
      $sql = " SELECT a.*, b.i_satuan, b.e_satuan as nama_satuan 
          FROM tr_material a 
          INNER JOIN tr_satuan b ON a.i_satuan_code = b.i_satuan_code
          INNER JOIN tr_item_type c ON a.i_type_code = c.i_type_code
          INNER JOIN tm_kelompok_barang d ON c.i_kode_kelompok = d.i_kode_kelompok
          WHERE d.i_kode_kelompok = '$ikodekelompok' 
          AND a.f_status_aktif = 't' AND c.i_type_code = '$ikodejenis' ORDER BY a.i_material "; 
          // if ($ikodejenis != '')
          //   $sql.= " AND c.kode_jenis = '$ikodejenis' "; 
          // $sql.= " ORDER BY a.i_material ";
          $query  = $this->db->query($sql);    
  
          $data_harga = array();
          if ($query->num_rows() > 0){
            $hasil = $query->result();
            foreach ($hasil as $row1) {
              $query3 = $this->db->query("SELECT v_price FROM tr_supplier_materialprice
               WHERE i_material = '$row1->i_material'
                          AND i_supplier = '$isupplier' AND i_satuan = '$row1->i_satuan'");
              if ($query3->num_rows() == 0){
                $v_price  = '';
              }
              else {
                $hasilrow = $query3->row();
                $v_price  = $hasilrow->v_price;
              }
                
              $data_harga[] = array(      
                        'i_material'=> $row1->i_material,
                        'e_material_name'=> $row1->e_material_name,
                        'i_satuan'=> $row1->i_satuan,
                        'i_satuan_konversi'=> $row1->i_satuan_konversi,
                        'nama_satuan'=> $row1->nama_satuan,
                        'v_price'=> $v_price
                      );

            } // end foreach
          }
  else
    $data_harga = '';
    return $data_harga;  
  }

  function getrumus($satuan_awal, $satuan_akhir){
        $this->db->select('*');
        $this->db->from('tm_konversi_satuan');
        
        $this->db->where('i_satuan', $satuan_awal);
        $this->db->where('i_satuan_konversi', $satuan_akhir);
        return $this->db->get();
  }
  
  function cek_data2($isupplier, $kodebrg){
      $this->db->select('a.i_supplier, b.e_supplier_name, a.i_material, c.e_nama_brg, d.e_satuan, a.v_price, a.d_update');
          $this->db->from('tr_supplier_materialprice a');
          $this->db->join('tr_supplier b','a.i_supplier = b.i_supplier');
          $this->db->join('tr_material c','a.i_material = c.i_material');
          $this->db->join('tr_satuan d','a.i_satuan = d.i_satuan');
          $this->db->join('tr_supplier_materialprice e','a.i_material = e.i_material',"left");
          $this->db->where('a.i_material', $kodebrg);
          $this->db->where('a.i_supplier', $isupplier);
          return $this->db->get();
  
  }

  function cek_sup($isupplier){
      $this->db->select('*');
          $this->db->from('tr_supplier');
          $this->db->where('i_supplier',$isupplier);
          return $this->db->get();
  }

  function cek_group($igroupbrg){
      $this->db->select('*');
          $this->db->from('tm_group_barang');
          $this->db->where('i_kode_group_barang',$igroupbrg);
          return $this->db->get();
  }

  function satuan(){
      $this->db->select('*');
      $this->db->from('tr_satuan');
      return $this->db->get();
  }

  function delete($isupplier, $kodebrg){
    $this->db->query(" delete FROM tr_supplier_materialprice WHERE i_supplier = '$isupplier' and kode_brg = '$kodebrg'");
  }

  function cek_proses($ikodekelompok, $ikodejenis, $isupplier){
        $this->db->select('distinct(a.i_material), a.*, b.i_satuan, b.e_satuan as nama_satuan, e.v_price, e.d_entry');
            $this->db->from('tr_material a ');
            $this->db->join('tr_satuan b','a.i_satuan_code = b.i_satuan_code ');
            $this->db->join('tm_jenis_barang c','a.i_kode_kelompok = c.i_kode_kelompok');
            $this->db->join('tm_kelompok_barang d','c.i_kode_kelompok = d.i_kode_kelompok');
            $this->db->join('tr_supplier_materialprice e','a.i_material = e.i_material',"left");
            $this->db->where('d.i_kode_kelompok',$ikodekelompok);
            $this->db->where('a.i_type_code',$ikodejenis);
            //$this->db->where('e.i_supplier',$isupplier);
            $this->db->where('a.f_status_aktif','t');
            $this->db->where('e.v_price',null);

            return $this->db->get();
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
        $where .= "AND c.i_type_code = '$ikodejenis'";
      }

      if($ikodekelompok != 'AKB'){
        $where .= "and d.i_kode_kelompok = '$ikodekelompok'";
      }

       $this->db->select(" a.i_material as i_material, a.e_material_name FROM tr_material a
                        JOIN tr_satuan b ON a.i_satuan_code = b.i_satuan_code 
                        JOIN tr_item_type c ON a.i_type_code = c.i_type_code 
                        JOIN tm_kelompok_barang d ON c.i_kode_kelompok = d.i_kode_kelompok 
                        where a.i_material||'$isupplier' not in(
                        select i_material||i_supplier FROM tr_supplier_materialprice a)
                          AND a.f_status_aktif = 't' $where", false); 
    
      return $this->db->get();
  }

  function get_supplier(){
      $this->db->select('*');
      $this->db->from('tr_supplier');
      $this->db->where('i_type_makloon', 'JNM0006');
      $this->db->order_by('i_supplier');
      return $this->db->get();
  }

  function get_kodekelompok(){
    $this->db->select('*');
    $this->db->from('tm_kelompok_barang');
    return $this->db->get();
  }

  function get_groupbarang(){
    $this->db->select(" * from tm_group_barang where i_kode_group_barang not in('GB005','GB006') order by i_kode_group_barang");
    return $this->db->get();
  }

  function get_satuan(){
      $this->db->select('*');
      $this->db->from('tr_satuan');
      $this->db->order_by('i_satuan');
      return $this->db->get();
  }

  public function insert($isupplier, $kodebrg, $harga, $ipriceno, $dateberlaku, $igroupbrg, $ikodekelompok, $ikodejenis, $isatuan,  $itipe){
    $dentry = date("Y-m-d");
    $data = array(
        'i_supplier'          => $isupplier,
        'i_material'          => $kodebrg,
        'v_price'             => $harga,
        'i_satuan_code'       => $isatuan,
        'd_berlaku'           => $dateberlaku,
        'i_kode_group_barang' => $igroupbrg,
        'i_kode_kelompok'     => $ikodekelompok,
        'i_type_code'         => $ikodejenis,
        'i_price_no'          => $ipriceno,
        'i_tipe'              => $itipe,
        'd_entry'             => $dentry,
    );
    $this->db->insert('tm_price_makloon_supplier_unitjahit', $data);
  }

  public function insertubah($isupplier, $kodebrg, $isatuan, $harga, $igroupbrg, $ikodekelompok, $ikodejenis, $norder, $dateberlaku){
    $tgl = date("Y-m-d");
    $data = array(
        'i_supplier'   => $isupplier,
        'i_material'   => $kodebrg,
        'v_price'      => $harga,
        'i_satuan'     => $isatuan,
        'n_order'      => $norder,
        'd_berlaku'    => $dateberlaku,
        'i_kode_group_barang' => $igroupbrg,
        'i_kode_kelompok'     => $ikodekelompok,
        'i_type_code'         => $ikodejenis,
        //'i_satuan_code'       => $satuann,
       

    );
    $this->db->insert('tm_price_makloon_supplier_unitjahit', $data);
  }

  public function update($isupplier, $kodebrg, $harga, $satuan, $itipe){
        $dupdate = date("Y-m-d");
        $data = array(
          'v_price'           => $harga,
          'i_satuan_code'     => $satuan,
          'i_tipe'            => $itipe,
          'd_update'          => $dupdate,
        );
          $this->db->where('i_material', $kodebrg);
          $this->db->where('i_supplier', $isupplier);
          $this->db->update('tm_price_makloon_supplier_unitjahit', $data);
  }

  public function cancel($isupplier, $kodebrg){
    $data = array(
            'f_status_aktif'=>'f',
    );
    $this->db->where('i_supplier', $isupplier);
    $this->db->where('i_material', $kodebrg);
    $this->db->update('tm_price_makloon_supplier_unitjahit', $data);
  }
}
/* End of file Mmaster.php */