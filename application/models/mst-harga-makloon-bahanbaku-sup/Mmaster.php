<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public $idcompany;

  function __construct(){
      parent::__construct();
      $this->idcompany = $this->session->id_company;
  }

  public function getsupplieradd(){
    return $this->db->query("
                            SELECT DISTINCT
                              a.i_supplier,
                              b.e_supplier_name
                            FROM
                              tr_supplier_makloon a
                              INNER JOIN 
                                tr_supplier b
                                ON (a.i_supplier = b.i_supplier 
                                and a.id_company = b.id_company)
                            WHERE
                              a.id_company = '".$this->session->userdata('id_company')."'
                            ORDER BY
                              b.e_supplier_name
                            ", FALSE);
  }

  public function gettypemakloon($isupplier){
    return $this->db->query("
                            SELECT
                              b.id,
                              a.i_type_makloon,
                              b.e_type_makloon_name
                            FROM
                              tr_supplier_makloon a
                              INNER JOIN 
                                tr_type_makloon b
                                ON (a.i_type_makloon = b.i_type_makloon
                                and a.id_company = b.id_company)
                            WHERE 
                              a.id_company = '".$this->session->userdata('id_company')."'
                              and a.i_supplier = '$isupplier'
                            ORDER BY
                              b.e_type_makloon_name
                            ", FALSE);
  }

  public function getmakloonlist($cari){
    return $this->db->query("
                            SELECT 
                              id,
                              i_type_makloon, 
                              e_type_makloon_name
                            FROM
                              tr_type_makloon
                            WHERE
                              id_company = '".$this->session->userdata('id_company')."'
                              AND i_type_makloon ILIKE '%$cari%'
                              AND e_type_makloon_name ILIKE '%$cari%'
                            ORDER BY 
                              e_type_makloon_name
                            ", FALSE);
  }

  public function getkelompokbarang($isupplier){
    return $this->db->query("
                            SELECT 
                              a.i_kode_kelompok,
                              b.e_nama_kelompok,
                              c.e_nama_group_barang
                            FROM 
                              tr_supplier_kelompokbarang a
                              INNER JOIN 
                                tr_kelompok_barang b
                                ON (a.i_kode_kelompok = b.i_kode_kelompok
                                AND a.id_company = b.id_company)
                              INNER JOIN
                                tr_group_barang c
                                ON (b.i_kode_group_barang = c.i_kode_group_barang
                                AND b.id_company = c.id_company)
                            WHERE 
                              a.id_company = '".$this->session->userdata('id_company')."'
                              AND a.i_supplier = '$isupplier'
	                          ", FALSE);
    
  }

  public function getsuppliermakloon($itypemakloon){
    return $this->db->query("
                              SELECT 
                                a.id, 
                                a.i_supplier,
                                b.e_supplier_name 
                              FROM 
                                tr_supplier_makloon a
                                INNER JOIN 
                                  tr_supplier b
                                  ON (a.i_supplier = b.i_supplier AND a.id_company = b.id_company)
                              WHERE 
                                a.i_type_makloon = '$itypemakloon' 
                                AND a.id_company = '$this->idcompany' 
                              ORDER BY 
                                b.e_supplier_name
                              ", FALSE);
  }

  function data($dfrom, $idtypemakloon, $folder, $i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);

    if($idtypemakloon == '0'){
      $idmakloon = '';
    }else{
      $idmakloon = "AND z.id_type_makloon = '$idtypemakloon'";
    }
    $datatables->query("
                      SELECT 
                      	0 as no,
                        z.d_akhir_tmp,
                        z.id,
                      	z.id_supplier,
                      	z.i_supplier,
                        z.e_supplier_name,
                        z.id_product,
                      	z.i_product,
                      	z.e_product,
                      	z.i_type_code,
                      	z.e_type_name,
                      	z.i_satuan_code_int,
                      	z.e_satuan_name_int,
                      	z.v_price_int,
                      	z.i_satuan_code_eks,
                      	z.e_satuan_name_eks,
                      	z.v_price_eks,
                      	z.d_berlaku,
                      	z.d_akhir,
                      	z.id_company,
                        z.id_type_makloon,
                        z.f_status,
                        '$i_menu' as i_menu,
                        '$folder' as folder,
                        '$dfrom' as dfrom
                      FROM 
                      (
                        SELECT 
                        	0 as no,
                          case when b.d_akhir is not null then b.d_akhir else '5000-01-01' end as d_akhir_tmp,
                          a.id,
                        	a.id_supplier,
                        	g.i_supplier,
                          g.e_supplier_name,
                          b.id_product,
                        	b.i_product,
                        	c.e_product,
                        	c.i_type_code,
                        	f.e_type_name,
                        	b.i_satuan_code_int,
                        	d.e_satuan_name as e_satuan_name_int,
                        	b.v_price_int,
                        	b.i_satuan_code_eks,
                        	e.e_satuan_name as e_satuan_name_eks,
                        	b.v_price_eks,
                        	b.d_berlaku,
                        	b.d_akhir,
                        	a.id_company,
                          a.id_type_makloon,
                          a.f_status
                        FROM 
                        	tr_harga_makloon_supplier a
                        	INNER JOIN
                        		tr_harga_makloon_supplier_item b
                        		ON (a.id = b.id_harga AND a.id_company = b.id_company)
                        	INNER JOIN 
                        		(
                        			SELECT 
                        				id as id_product,
                        				i_material as i_product,
                        				e_material_name as e_product,
                        				i_type_code as i_type_code,
                        				id_company
                        			FROM
                        				tr_material
                        			WHERE
                        				id_company = '$this->idcompany'
                        				AND f_status = 't'
                        			UNION ALL
                        			SELECT
                        				id as id_product,
                        				i_product_wip as i_product,
                        				e_product_wipname as e_product,
                        				i_type_code as i_type_code,
                        				id_company
                        			FROM
                        				tr_product_wip
                        			WHERE
                        				id_company = '$this->idcompany'
                        				AND f_status = 't'
                        			UNION ALL
                        			SELECT
                        				id as id_product,
                        				i_product_base as i_product,
                        				e_product_basename as e_product,
                        				i_type_code as i_type_code,
                        				id_company
                        			FROM
                        				tr_product_base 
                        			WHERE
                        				id_company = '$this->idcompany'
                        				AND f_status = 't'
                        		) c ON (b.id_product = c.id_product AND b.i_product = c.i_product AND b.id_company = c.id_company)
                        	INNER JOIN
                        		tr_satuan d
                        		ON (b.i_satuan_code_int = d.i_satuan_code AND b.id_company = d.id_company)
                        	INNER JOIN 
                        		tr_satuan e 
                        		ON (b.i_satuan_code_eks = e.i_satuan_code AND b.id_company = e.id_company)
                        	INNER JOIN 
                        		tr_item_type f
                        		ON (c.i_type_code = f.i_type_code AND c.id_company = f.id_company AND b.id_company = f.id_company)
                        	INNER JOIN
                        		tr_supplier g
                        		ON (a.id_supplier = g.id AND a.id_company = g.id_company)
                        WHERE 
                        	a.id_company = '$this->idcompany'
                      ) as z
                      WHERE
                      	z.d_berlaku <= to_date('$dfrom', 'dd-mm-yyyy')
                        AND z.d_akhir_tmp >= to_date('$dfrom','dd-mm-yyyy')
                        $idmakloon
                        AND z.id_company = '$this->idcompany'
                        AND z.f_status = 't'
                      ORDER BY
                      	z.d_berlaku
                      ",false);

          $datatables->edit('f_status', function ($data) {
            $f_status_aktif = trim($data['f_status']);
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

          $datatables->edit('v_price_int', function($data){
            return 'Rp. '.number_format($data['v_price_int'],2);
          });

          $datatables->edit('v_price_eks', function($data){
            return 'Rp. '.number_format($data['v_price_eks'],2);
          });


          $datatables->edit('e_supplier_name', function($data){
            return $data['i_supplier'].' - '.$data['e_supplier_name'];
          });
        
        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $idsupp         = trim($data['id_supplier']);
            $idproduct      = trim($data['id_product']);
            $iproduct       = trim($data['i_product']);
            $fstatus        = trim($data['f_status']);
            $idtypemakloon  = trim($data['id_type_makloon']);
            $dberlaku       = $data['d_berlaku'];
            $dfrom          = $data['dfrom'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$dberlaku/$dfrom/$idtypemakloon/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$dberlaku/$dfrom/$idtypemakloon/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            //if(check_role($i_menu, 4) && $fstatus != 'f'){
            //   $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$idsupp\", \"$idproduct\",\"$iproduct\"); return false;'><i class='fa fa-trash'></i></a>";
            // }

			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('id');
        $datatables->hide('id_supplier');
        $datatables->hide('i_supplier');
        $datatables->hide('id_product');
        $datatables->hide('i_type_code');
        $datatables->hide('i_satuan_code_eks');
        $datatables->hide('e_satuan_name_eks');
        $datatables->hide('i_satuan_code_int');
        $datatables->hide('v_price_eks');
        $datatables->hide('d_akhir_tmp');
        $datatables->hide('id_company');
        $datatables->hide('id_type_makloon');
        $datatables->hide('f_status');
      return $datatables->generate();
  }

  public function getnamemakloon($idtypemakloon){
    if($idtypemakloon == '0'){
      $etypemakloonname = "Semua Supplier";
    }else{
      $data = $this->db->query("SELECT e_type_makloon_name FROM tr_type_makloon WHERE id = '$idtypemakloon' AND id_company='$this->idcompany'", FALSE);
      foreach($data->result() as $row){
        $etypemakloonname = $row->e_type_makloon_name;
      }
    }
    return $etypemakloonname;
  }

	public function cek_data($id, $dberlaku){
    $tmp   = explode('-', $dberlaku);
    $day   = $tmp[2];
    $month = $tmp[1];
    $year  = $tmp[0];
    $dberlaku = $year.'-'.$month.'-'.$day;
    return $this->db->query(" 
                            SELECT
                              z.id,
                              z.id_harga,
                            	z.id_supplier,
                            	z.i_supplier,
                              z.e_supplier_name,
                              z.i_type_pajak,
                              z.id_product,
                            	z.i_product,
                            	z.e_product,
                            	z.i_type_code,
                              z.e_type_name,
                              z.i_kode_kelompok,
                              z.e_nama_kelompok,
                              z.i_kode_group_barang,
                            	z.i_satuan_code_int,
                            	z.e_satuan_name_int,
                            	z.v_price_int,
                            	z.i_satuan_code_eks,
                            	z.e_satuan_name_eks,
                            	z.v_price_eks,
                            	to_char(z.d_berlaku,'dd-mm-YYYY') as d_berlaku,
                            	z.d_akhir,
                            	z.id_company,
                              z.id_type_makloon,
                              z.f_status
                            FROM 
                            (
                              SELECT 
                                a.id, 
                                b.id_harga,
                              	a.id_supplier,
                              	g.i_supplier,
                                g.e_supplier_name,
                                g.i_type_pajak,
			      	                  b.id_product,
                              	b.i_product,
                              	c.e_product,
                              	c.i_type_code,
                                f.e_type_name,
                                c.i_kode_kelompok,
                                h.e_nama_kelompok,
                                h.i_kode_group_barang,
                              	b.i_satuan_code_int,
                              	d.e_satuan_name as e_satuan_name_int,
                              	b.v_price_int,
                              	b.i_satuan_code_eks,
                              	e.e_satuan_name as e_satuan_name_eks,
                              	b.v_price_eks,
                              	b.d_berlaku,
                              	b.d_akhir,
                              	a.id_company,
			      	                  a.id_type_makloon,
			      	                  a.f_status
                              FROM 
                              	tr_harga_makloon_supplier a
                              	INNER JOIN
                              		tr_harga_makloon_supplier_item b
                              		ON (a.id = b.id_harga AND a.id_company = b.id_company)
                              	INNER JOIN 
                              		(
                              			SELECT 
                              				id as id_product,
                              				i_material as i_product,
                              				e_material_name as e_product,
                                      i_type_code as i_type_code,
                                      i_kode_kelompok,
                              				id_company
                              			FROM
                              				tr_material
                              			WHERE
                              				id_company = '$this->idcompany'
                              				AND f_status = 't'
                              			UNION ALL
                              			SELECT
                              				id as id_product,
                              				i_product_wip as i_product,
                              				e_product_wipname as e_product,
                                      i_type_code as i_type_code,
                                      i_kode_kelompok,
                              				id_company
                              			FROM
                              				tr_product_wip
                              			WHERE
                              				id_company = '$this->idcompany'
                              				AND f_status = 't'
                              			UNION ALL
                              			SELECT
                              				id as id_product,
                              				i_product_base as i_product,
                              				e_product_basename as e_product,
                                      i_type_code as i_type_code,
                                      i_kode_kelompok,
                              				id_company
                              			FROM
                              				tr_product_base 
                              			WHERE
                              				id_company = '$this->idcompany'
                              				AND f_status = 't'
                              		) c ON (b.id_product = c.id_product AND b.i_product = c.i_product AND b.id_company = c.id_company)
                              	INNER JOIN
                              		tr_satuan d
                              		ON (b.i_satuan_code_int = d.i_satuan_code AND b.id_company = d.id_company)
                              	INNER JOIN 
                              		tr_satuan e 
                              		ON (b.i_satuan_code_eks = e.i_satuan_code AND b.id_company = e.id_company)
                              	INNER JOIN 
                              		tr_item_type f
                              		ON (c.i_type_code = f.i_type_code AND c.id_company = f.id_company AND b.id_company = f.id_company)
                              	INNER JOIN
                              		tr_supplier g
                                  ON (a.id_supplier = g.id AND a.id_company = g.id_company)
                                INNER JOIN
                                  tr_kelompok_barang h
                                  ON (c.i_kode_kelompok = h.i_kode_kelompok AND c.id_company = h.id_company)
                              WHERE 
                              	a.id_company = '$this->idcompany'
                            ) as z
                            WHERE
                            	z.d_berlaku = '$dberlaku'
                            	AND z.id = '$id'
                              AND z.id_harga = '$id'
                              AND z.id_company = '$this->idcompany'
                          ", FALSE);
  }

  public function get_hargas($ikodekelompok, $ikodejenis, $isupplier, $iproduct, $itypemakloon){
    $ikodegroupbrg='';
    $query = $this->db->query("
                              SELECT 
                                i_kode_group_barang 
                              FROM 
                                tr_kelompok_barang 
                              WHERE 
                                i_kode_kelompok = '$ikodekelompok'
                              ", FALSE);
    foreach($query->result() as $row){
      $ikodegroupbrg = $row->i_kode_group_barang;
    }

      $where = '';
      $product = '';
      if($ikodejenis != 'AJB'){
        $where .= "AND i_type_code = '$ikodejenis'";
      }

      if($ikodekelompok != 'AKB'){
        $where .= "AND i_kode_kelompok = '$ikodekelompok'";
      }

      if($iproduct != 'BRG'){
        $product .= "AND c.i_product = '$iproduct'";
      }

      $this->db->select("
                        c.id_product,
                        c.i_product,
                      	c.e_product_name,
                        c.i_kode_group_barang,
                        c.id_company,
                        c.i_kode_group_barang,
                        c.i_type_code,
                        c.i_kode_kelompok,
                        c.i_satuan_code, 
                        c.e_satuan_name
                      FROM
                      (
                        SELECT 
                          a.id as id_product,
                        	a.i_material as i_product,
                        	a.e_material_name as e_product_name,
                        	a.id_company,
                          a.i_kode_group_barang,
                          a.i_type_code,
                          a.i_kode_kelompok,
                          a.i_satuan_code, 
                          b.e_satuan_name
                        FROM
                          tr_material a
                          INNER JOIN
                            tr_satuan b ON (a.i_satuan_code = b.i_satuan_code AND a.id_company = b.id_company)
                        WHERE 
                        	a.f_status = 't'
                          AND a.id_company = '$this->idcompany'
                          $where
                        UNION ALL
                        SELECT 
                          a.id as id_product,
                        	a.i_product_wip as i_product,
                        	a.e_product_wipname as e_product_name,
                        	a.id_company,
                          a.i_kode_group_barang,
                          a.i_type_code,
                          a.i_kode_kelompok,
                          a.i_satuan_code, 
                          b.e_satuan_name
                        FROM
                          tr_product_wip a
                          INNER JOIN
                          tr_satuan b ON (a.i_satuan_code = b.i_satuan_code AND a.id_company = b.id_company)
                        WHERE 
                        	a.f_status = 't'
                          AND a.id_company = '$this->idcompany'
                          $where
                        UNION ALL
                        SELECT
                          a.id as id_product,
                        	a.i_product_base as i_product,
                        	a.e_product_basename as e_product_name,
                        	a.id_company,
                          a.i_kode_group_barang,
                          a.i_type_code,
                          a.i_kode_kelompok,
                          a.i_satuan_code, 
                          b.e_satuan_name
                        FROM
                          tr_product_base a
                          INNER JOIN
                          tr_satuan b ON (a.i_satuan_code = b.i_satuan_code AND a.id_company = b.id_company)
                        WHERE 
                        	a.f_status = 't'
                          AND a.id_company = '$this->idcompany'
                          $where
                      ) as c
                      WHERE 
                      	c.i_kode_group_barang = '$ikodegroupbrg'
                      	AND c.id_product || c.i_product || '$isupplier'
                      	NOT IN (
                                  SELECT 
                                    a.id_product||a.i_product||b.id_supplier 
                                  FROM 
                                    tr_harga_makloon_supplier_item a
                                    INNER JOIN 
                      				      tr_harga_makloon_supplier b
                      				      ON (a.id_harga = b.id AND a.id_company = b.id_company)
                        )
                        AND c.id_company = '$this->idcompany'
                        $product
                        ", FALSE);
    return $this->db->get();
  }

  public function cek_sup($isupplier, $itypemakloon){
    return $this->db->query("
                            SELECT 
                              a.i_supplier,
                              a.i_type_makloon,
                              b.id as id_supplier,
                              b.e_supplier_name,
                              b.i_type_pajak,
                              c.id as id_type_makloon,
                              b.f_pkp
                            FROM 
                              tr_supplier_makloon a
                              INNER JOIN 
                                tr_supplier b 
                                ON (a.i_supplier = b.i_supplier AND a.id_company = b.id_company)
                              INNER JOIN
                                tr_type_makloon c
                                ON (a.i_type_makloon = c.i_type_makloon AND a.id_company = c.id_company)
                            WHERE 
                              b.id='$isupplier' 
                              AND c.id = '$itypemakloon'
                              AND a.id_company='$this->idcompany'
                            ", FALSE);
  }

  public function cek_group($igroupbrg){
    return $this->db->query("SELECT * FROM tr_group_barang WHERE i_kode_group_barang='$igroupbrg' AND id_company='$this->idcompany'", FALSE);
  }

  public function getkel($igroupbrg) {
        $this->db->select("i_kode_kelompok, e_nama");
        $this->db->from('tm_kelompok_barang');
        $this->db->where('i_kode_group_barang', $igroupbrg);
        $this->db->order_by('i_kode_kelompok');
        return $this->db->get();
  }

  public function getjenis($ikodekelompok) {
    $where = '';
    if($ikodekelompok != 'AKB'){
      $where .= "WHERE i_kode_kelompok = '$ikodekelompok'";
    }else{
      $where .= '';
    }
    return $this->db->query("SELECT i_type_code, e_type_name FROM tr_item_type $where ORDER BY i_type_code", FALSE);
  }

  public function getmaterial($isupplier, $ikodejenis, $ikodekelompok, $itypemakloon) {
    $ikodegroupbrg='';
    $query = $this->db->query("
                              SELECT 
                                i_kode_group_barang 
                              FROM 
                                tr_kelompok_barang 
                              WHERE 
                                i_kode_kelompok = '$ikodekelompok'
                              ", FALSE);
    foreach($query->result() as $row){
      $ikodegroupbrg = $row->i_kode_group_barang;
    }
    $where = '';
    if($ikodejenis != 'AJB'){
      $where .= "AND i_type_code = '$ikodejenis'";
    }
    if($ikodekelompok != 'AKB'){
      $where .= "AND i_kode_kelompok = '$ikodekelompok'";
    }
    
    $this->db->select(" 
                        a.id_product,
                      	a.i_product,
                      	a.e_product_name,
                      	a.i_kode_group_barang
                      FROM
                      (
                        SELECT 
                          id as id_product,
                        	i_material as i_product,
                        	e_material_name as e_product_name,
                        	id_company,
                        	i_kode_group_barang
                        FROM
                        	tr_material 
                        WHERE 
                        	f_status = 't'
                          AND id_company = '$this->idcompany'
                          $where
                        UNION ALL
                        SELECT 
                          id as id_product,
                        	i_product_wip as i_product,
                        	e_product_wipname as e_product_name,
                        	id_company,
                        	i_kode_group_barang
                        FROM
                        	tr_product_wip
                        WHERE 
                        	f_status = 't'
                          AND id_company = '$this->idcompany'
                          $where
                        UNION ALL
                        SELECT
                          id as id_product,
                        	i_product_base as i_product,
                        	e_product_basename as e_product_name,
                        	id_company,
                        	i_kode_group_barang
                        FROM
                        	tr_product_base
                        WHERE 
                        	f_status = 't'
                          AND id_company = '$this->idcompany'
                          $where
                      ) as a
                      WHERE 
                      	a.i_kode_group_barang = '$ikodegroupbrg'
                      	AND a.id_product || a.i_product || '$isupplier'
                      	NOT IN (
                                  SELECT 
                                    a.id_product||a.i_product||b.id_supplier 
                                  FROM 
                                    tr_harga_makloon_supplier_item a
                                    INNER JOIN 
                      				      tr_harga_makloon_supplier b
                      				      ON (a.id_harga = b.id AND a.id_company = b.id_company)
                        )
                      	AND a.id_company = '$this->idcompany'
                    ", FALSE); 
    
    return $this->db->get();
  }

  public function getrumus($satuan_awal, $satuan_akhir, $idcompany){
    return $this->db->query("SELECT * FROM tr_konversi_satuan WHERE i_satuan_code = '$satuan_awal' AND i_satuan_code_konversi = '$satuan_akhir' AND id_company = '$idcompany'", FALSE);
}

  public function get_supplier($itypemakloon){
    return $this->db->query("
                            SELECT 
                              a.id,
                              a.i_supplier, 
                              a.e_supplier_name 
                            FROM 
                              tr_supplier a
                              INNER JOIN
                                tr_supplier_makloon b
                                ON (a.i_supplier = b.i_supplier AND a.id_company = b.id_company)
                            WHERE 
                              b.i_type_makloon = '$itypemakloon' 
                              AND a.id_company='$this->idcompany' 
                            ORDER BY 
                              a.i_supplier,
                              a.e_supplier_name
                            ", FALSE)->result();
  }

  public function get_groupbarang($itypemakloon){
    return $this->db->query("SELECT i_kode_group_barang FROM tr_group_barang WHERE i_kode_group_barang IN (SELECT i_kode_group_barang FROM tr_type_makloon WHERE i_type_makloon='$itypemakloon' AND id_company = '$this->idcompany') AND id_company='$this->idcompany'", FALSE)->result();
  }

  public function get_satuan(){
    return $this->db->query("SELECT * FROM tr_satuan WHERE id_company='$this->idcompany' AND f_status = 't' ORDER BY i_satuan_code", FALSE)->result();
  }

  public function runningid()
  {
      $this->db->select('max(id) AS id');
      $this->db->from('tr_harga_makloon_supplier');
      return $this->db->get()->row()->id+1;
  }

  public function insertheader($id, $isupplier, $itypemakloon){
    $data = array(
        'id'              => $id,
        'id_company'      => $this->idcompany,
        'id_supplier'     => $isupplier,
        'id_type_makloon' => $itypemakloon,
        'd_entry'         => current_datetime()
    );
    $this->db->insert('tr_harga_makloon_supplier', $data);
  }

  public function insertdetail($id, $kodebrg, $idkodebrg, $isatuanint, $vpriceint, $isatuaneks, $vpriceeks, $irumuskonversi, $dberlaku, $itypepajak){
    $data = array(
        'id_harga'          => $id,
        'id_company'        => $this->idcompany,
        'id_product'        => $idkodebrg,
        'i_product'         => $kodebrg,
        'i_satuan_code_int' => $isatuanint,
        'v_price_int'       => $vpriceint,
        'i_satuan_code_eks' => $isatuaneks,
        'v_price_eks'       => $vpriceeks,
        'i_rumus_konversi'  => $irumuskonversi,
        'd_berlaku'         => $dberlaku,
        'i_type_pajak'      => $itypepajak
    );
    $this->db->insert('tr_harga_makloon_supplier_item', $data);
  }

  public function update($id, $isupplier, $kodebrg, $idkodebrg, $hargaint, $itipe, $isatuanint, $hargaeks, $isatuaneks, $dsebelum, $dberlaku, $dakhirsebelum, $status, $itypemakloon){

        $data = array(
         'i_satuan_code_int' => $isatuanint,
         'v_price_int'       => $hargaint,
         'i_satuan_code_eks' => $isatuaneks,
         'v_price_eks'       => $hargaeks,
         'i_type_pajak'      => $itipe,
         'd_berlaku'         => $dberlaku
        );
        $this->db->where('id_harga', $id);
        $this->db->where('id_product', $idkodebrg);
        $this->db->where('i_product', $kodebrg);
        $this->db->where('d_berlaku', $dsebelum);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tr_harga_makloon_supplier_item', $data);

        $dakhir = date('Y-m-d', strtotime('-1 days', strtotime( $dberlaku ))); 
        $data2 = array(
          'd_akhir'       => $dakhir
        );
        $this->db->where('id_harga', $id);
        $this->db->where('id_product', $idkodebrg);
        $this->db->where('i_product', $kodebrg);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where('d_akhir', $dakhirsebelum);
        $this->db->update('tr_harga_makloon_supplier_item', $data2);

        $data3 = array(
          'd_update'  => current_datetime(),
          'f_status'  => $status
        );
        $this->db->where('id', $id);
        $this->db->where('id_supplier', $isupplier);
        $this->db->where('id_type_makloon', $itypemakloon);
        $this->db->update('tr_harga_makloon_supplier', $data3);
  }

  public function updatetglakhir($id, $idbaru, $isupplier, $kodebrg, $idkodebrg, $hargaint, $itipe, $isatuanint, $hargaeks, $isatuaneks, $dsebelum, $dberlaku, $dakhirsebelum, $status, $itypemakloon, $irumuskonversi){
    $dupdate = date("Y-m-d");
    $dakhir = date('Y-m-d', strtotime('-1 days', strtotime( $dberlaku ))); //kurang tanggal sebanyak 1 hari
    $dentry = date("Y-m-d");

    $data = array(
      'id'              => $idbaru,
      'id_company'      => $this->idcompany,
      'id_supplier'     => $isupplier,
      'id_type_makloon' => $itypemakloon,
      'd_entry'         => current_datetime()
    );
    $this->db->insert('tr_harga_makloon_supplier', $data);

    $data2 = array(
      'id_harga'          => $idbaru,
      'id_company'        => $this->idcompany,
      'id_product'        => $idkodebrg,
      'i_product'         => $kodebrg,
      'i_satuan_code_int' => $isatuanint,
      'v_price_int'       => $hargaint,
      'i_satuan_code_eks' => $isatuaneks,
      'v_price_eks'       => $hargaeks,
      'i_rumus_konversi'  => $irumuskonversi,
      'd_berlaku'         => $dberlaku,
      'i_type_pajak'      => $itipe
    );
    $this->db->insert('tr_harga_makloon_supplier_item', $data2);

    $data3 = array(
                'd_akhir' => $dakhir,
    );
      $this->db->where('id_harga', $id);
      $this->db->where('id_product', $idkodebrg);
      $this->db->where('i_product', $kodebrg);
      $this->db->where('id_company', $this->idcompany);
      $this->db->where('d_berlaku', $dsebelum);
      $this->db->update('tr_harga_makloon_supplier_item', $data3);

      $data4 = array(
        'd_update'  => current_datetime(),
        'f_status'  => $status
      );
      $this->db->where('id', $id);
      $this->db->where('id_supplier', $isupplier);
      $this->db->where('id_type_makloon', $itypemakloon);
      $this->db->update('tr_harga_makloon_supplier', $data4);
  }
}
/* End of file Mmaster.php */
