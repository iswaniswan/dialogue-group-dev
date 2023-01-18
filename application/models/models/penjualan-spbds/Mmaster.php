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
                tm_spb
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
                                 0 AS NO,
                                 a.id, 
                                 a.i_document, 
                                 to_char(a.d_document, 'dd-mm-yyyy') as d_document, 
                                 a.id_customer, 
                                 b.e_customer_name,
                                 a.i_referensi_op, 
                                 e_remark,
                                 e_status_name,
                                 label_color,
                                 a.i_status,
                                 '$i_menu' AS i_menu,
                                 '$folder' AS folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto 
                              FROM
                                 tm_spb a 
                                INNER JOIN
                                    tr_customer b 
                                    ON (a.id_customer = b.id AND a.id_company = b.id_company) 
                                 INNER JOIN
                                    tr_status_document d 
                                    ON (d.i_status = a.i_status) 
                              WHERE
                                 a.i_status <> '5' 
                                 AND a.d_document BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND a.id_company = '$id_company' $bagian 
                              GROUP BY
                                 a.id,
                                 a.i_document,
                                 a.d_document,
                                 b.e_customer_name,
                                 e_status_name,
                                 label_color,
                                 a.i_status
                          ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
          });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            
            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
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
        $datatables->hide('id_customer');
        return $datatables->generate();
  }

  public function bagian(){
      $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
      $this->db->where('username', $this->session->userdata('username'));
      $this->db->where('a.id_company', $this->session->userdata('id_company'));
      $this->db->order_by('e_bagian_name');
      return $this->db->get();
  }

  public function area($cari){
    $idcompany    = $this->session->userdata('id_company');
    return $this->db->query("
                            SELECT  
                              a.id,
                              a.i_area,
                              a.e_area
                            FROM
                              tr_area a                             
                            WHERE 
                              (a.i_area like '%$cari%' or a.e_area like '%$cari%')
                            ORDER BY 
                              a.e_area
                            ", FALSE);
  }

  public function customer($cari, $iarea){
    $idcompany    = $this->session->userdata('id_company');
    return $this->db->query("
                            SELECT  
                              a.id,
                              a.i_customer,
                              a.e_customer_name,
                              a.id_area
                            FROM
                              tr_customer a  
                            JOIN tr_area b ON a.id_area = b.id       
                            JOIN tr_type_industry bb on a.i_type_industry = bb.i_type_industry AND a.id_company = bb.id_company
                            JOIN tr_type_spb c on c.id_type_industry = bb.id                         
                            WHERE 
                              a.id_company = '$idcompany'
                              AND a.id_area = '$iarea'
                              AND c.id = '1'
                              AND a.f_status = 't'
                              AND (a.i_customer like '%$cari%' or a.e_customer_name like '%$cari%')
                            ORDER BY 
                              a.id
                            ", FALSE);
  }

  public function sales($cari, $iarea, $icustomer, $ddocument){
    $idcompany    = $this->session->userdata('id_company');
    return $this->db->query("      
                              SELECT DISTINCT
                                 a.id_salesman,
                                 b.e_sales 
                              FROM
                                 tr_customer_salesman a 
                                 JOIN
                                    tr_salesman b 
                                    ON a.id_salesman = b.id 
                                    AND a.id_company = b.id_company
                                  WHERE 
                                    a.id_area = '$iarea'
                                    AND
                                    a.id_customer = '$icustomer'
                                    AND 
                                    a.e_periode = '$ddocument'
                            ", FALSE);
  }

  public function getdiskon($icustomer){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("            
                                    SELECT 
                                        a.v_customer_discount,
                                        a.v_customer_discount2,
                                        a.v_customer_discount3,
                                        a.id_harga_kode,
                                        b.e_harga
                                    FROM
                                        tr_customer a
                                    JOIN 
                                        tr_harga_kode b 
                                    ON a.id_harga_kode = b.id AND a.id_company = b.id_company
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND 
                                        a.id = '$icustomer'
                                ", FALSE);
  }

  public function kelompok($cari,$ibagian){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                  SELECT
                                      i_kode_kelompok,
                                      e_nama_kelompok
                                  FROM
                                      tr_kelompok_barang
                                  WHERE
                                      f_status = 't'
                                      AND i_kode_kelompok IN (
                                      SELECT
                                          i_kode_kelompok
                                      FROM
                                          tr_bagian_kelompokbarang
                                      WHERE
                                          e_nama_kelompok ILIKE '%$cari%'
                                          AND id_company = '".$this->session->userdata('id_company')."'
                                          AND i_bagian = '$ibagian' )
                                      AND id_company = '".$this->session->userdata('id_company')."'
                                  ORDER BY
                                      e_nama_kelompok
                                ", FALSE);
    }

    public function jenis($cari,$ikelompok,$ibagian){
        $jenis = "";
        if ($this->session->userdata('i_departement')!='5' || $this->session->userdata('i_departement')!='1') {
            if (($ikelompok != '' || $ikelompok != null) && $ikelompok!='all') {
                $jenis = "AND i_kode_kelompok = '$ikelompok' ";
            }else{
                $jenis = "AND i_kode_kelompok IN 
                (SELECT
                    i_kode_kelompok
                FROM
                    tr_bagian_kelompokbarang
                WHERE
                    i_bagian = '$ibagian'
                    AND id_company = '".$this->session->userdata('id_company')."')";
            }
        }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                e_type_name ILIKE '%$cari%'
                AND f_status = 't'
                AND id_company = '".$this->session->userdata('id_company')."'
                $jenis
            ORDER BY
                e_type_name
        ", FALSE);
    }

    public function product($cari,$ikategori,$ijenis,$ibagian, $ibrand, $kodeharga)
    {
        $kategori = "";
        $jenis    = "";
        $brand    = "";
        if ($this->session->userdata('i_departement')!='5' || $this->session->userdata('i_departement')!='1') {
            if (($ikategori != '' || $ikategori != null) && $ikategori!='all') {
                $kategori = "AND i_kode_kelompok = '$ikategori' ";
            }else{
                $kategori = "AND i_kode_kelompok 
                IN (SELECT
                        i_kode_kelompok
                    FROM
                        tr_bagian_kelompokbarang
                    WHERE
                        i_bagian = '$ibagian'
                        AND id_company = '".$this->session->userdata('id_company')."')";
            }

            if (($ijenis != '' || $ijenis != null) && $ijenis!='all') {
                $jenis = "AND i_type_code = '$ijenis' ";
            }else{
                $jenis = "AND i_type_code 
                IN (SELECT
                        i_type_code
                    FROM
                        tr_item_type
                    WHERE
                        f_status = 't'
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND i_kode_kelompok IN 
                            (SELECT
                                i_kode_kelompok
                            FROM
                                tr_bagian_kelompokbarang
                            WHERE
                                i_bagian = '$ibagian'
                                AND id_company = '".$this->session->userdata('id_company')."'))";
            }
            if (($ibrand != '' || $ibrand != null)) {
               $brand = "AND i_brand = '$ibrand' ";
            }else{
               $brand = "";
            }
        }
        return $this->db->query("
                                    SELECT
                                        a.id,
                                        a.i_product_base,
                                        a.e_product_basename,
                                        a.i_kode_kelompok
                                    FROM
                                        tr_product_base a
                                    JOIN 
                                        tr_harga_jualbrgjd b 
                                      ON 
                                        a.id = b.id_product_base
                                    WHERE
                                         a.f_status = 't'
                                        AND b.id_harga_kode = '$kodeharga'
                                        AND (i_product_base ILIKE '%$cari%' 
                                        OR e_product_basename ILIKE '%$cari%')
                                        AND a.id_company = '".$this->session->userdata('id_company')."'
                                        $kategori
                                        $jenis
                                        $brand
                                    ORDER BY
                                        i_product_base
                                ", FALSE);
  }

  public function getproduct($eproduct, $tgl, $kodeharga){
        $idcompany  = $this->session->userdata('id_company');
        $dberlaku  = date('Y-m-d', strtotime($tgl));
        $dakhir    = date('Y-m-d', strtotime('+1 year', strtotime($tgl)));

        return $this->db->query("            
                                  SELECT 
                                      * 
                                  FROM (
                                          SELECT 
                                              a.id as id_product, 
                                              a.i_product_base,
                                              a.e_product_basename,
                                              b.v_price,
                                              a.i_brand,
                                              b.d_berlaku,
                                              CASE
                                                  WHEN d_akhir ISNULL THEN '$dakhir'
                                                  ELSE d_akhir
                                              END AS d_akhir
                                          FROM
                                              tr_product_base a
                                          JOIN 
                                              tr_harga_jualbrgjd b 
                                              ON a.id = id_product_base AND a.id_company = b.id_company
                                          WHERE
                                            a.id_company = '$idcompany'
                                            AND a.id = '$eproduct'
                                            AND b.id_harga_kode = '$kodeharga'
                                        ) as x 
                                  WHERE 
                                      x.d_berlaku <= '$dberlaku'
                                      AND x.d_akhir >= '$dberlaku' 
                                ", FALSE);
  }

  public function runningnumber($thbl,$tahun,$ibagian) {
       $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_spb 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SPB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_spb
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
      $this->db->from('tm_spb');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function cek_kodeedit($kode,$kodeold, $ibagian) {
      $this->db->select('i_document');
      $this->db->from('tm_spb');
      $this->db->where('i_document', $kode);
      $this->db->where('i_document <>', $kodeold);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

    /*----------  SIMPAN DATA  ----------*/
    
  public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_spb');
        return $this->db->get()->row()->id+1;
  }

  public function insertheader($id, $idocument, $ddocument, $ibagian, $dsend, $iarea, $icustomer, $isales, $ireferensiop, $nkotor, $ndiskontotal, $vdpp, $vppn, $nbersih, $eremarkh, $kodeharga){
        $data = array(
                        'id'                  => $id,
                        'id_company'          => $this->session->userdata('id_company'),
                        'i_document'          => $idocument,
                        'd_document'          => $ddocument,
                        'i_bagian'            => $ibagian,
                        'd_estimate'          => $dsend,
                        'id_customer'         => $icustomer,
                        'id_area'             => $iarea,
                        'id_sales'            => $isales,
                        'i_referensi_op'      => $ireferensiop,
                        'v_kotor'             => $nkotor,
                        'v_diskon'            => $ndiskontotal,
                        'v_ppn'               => $vppn,
                        'v_dpp'               => $vdpp,
                        'v_bersih'            => $nbersih,
                        'e_remark'            => $eremarkh,
                        'id_harga_kode'       => $kodeharga,
                        'd_entry'             => current_datetime(),
        );
        $this->db->insert('tm_spb', $data);
  }

  public function insertdetail($id, $idproduct, $nquantity, $vharga, $ndiskon1, $ndiskon2, $ndiskon3, $adddiskon, $vdiskon1, $vdiskon2, $vdiskon3, $vtotal, $vtotaldis, $eremark){
        $data = array(
                          'id_company'        => $this->session->userdata('id_company'),
                          'id_document'       => $id,
                          'id_product'        => $idproduct,
                          'n_quantity'        => $nquantity,
                          'n_quantity_sisa'   => $nquantity,
                          'v_price'           => $vharga,
                          'n_diskon1'         => $ndiskon1,
                          'n_diskon2'         => $ndiskon2,
                          'n_diskon3'         => $ndiskon3,
                          'v_diskon1'         => $vdiskon1,
                          'v_diskon2'         => $vdiskon2,
                          'v_diskon3'         => $vdiskon3,
                          'v_diskontambahan'  => $adddiskon,
                          'v_total_discount'  => $vtotaldis,
                          'v_total'           => $vtotal,
                          'e_remark'          => $eremark,
        );
        $this->db->insert('tm_spb_item', $data);
    }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
    
    public function changestatus($id,$istatus){
      $idcompany  = $this->session->userdata('id_company');
      if ($istatus=='6') {
          $data = array(
              'i_status'  => $istatus,
              'e_approve' => $this->session->userdata('username'),
              'd_approve' => date('Y-m-d'),
          );
      }else{
          $data = array(
              'i_status'  => $istatus,
          );
      }
      $this->db->where('id', $id);
      $this->db->where('id_company', $idcompany);
      $this->db->update('tm_spb', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function get_dataheader($id) {
        return $this->db->query("
                                  SELECT 
                                    a.id,
                                    a.id_company,
                                    a.i_document, 
                                    to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                    a.i_bagian,
                                    to_char(a.d_estimate, 'dd-mm-yyyy') as d_estimate,
                                    a.id_customer, 
                                    b.e_customer_name,
                                    a.id_area,
                                    c.e_area,
                                    a.id_sales,
                                    d.e_sales,
                                    a.i_referensi_op,
                                    a.v_diskon,
                                    a.v_kotor,
                                    a.v_ppn,
                                    a.v_dpp,
                                    a.v_bersih,
                                    a.i_status,
                                    a.e_remark,
                                    f.i_brand,
                                    a.id_harga_kode,
                                    g.e_harga
                                  FROM tm_spb a
                                  JOIN tr_customer b 
                                    ON a.id_customer = b.id AND a.id_company = b.id_company
                                  JOIN tr_area c 
                                    ON a.id_area = c.id 
                                  JOIN tr_salesman d 
                                    ON a.id_sales = d.id AND a.id_company = d.id_company
                                  JOIN tm_spb_item e 
                                    ON a.id = e.id_document AND a.id_company = e.id_company
                                  JOIN tr_product_base f 
                                    ON e.id_product = f.id AND a.id_company = f.id_company
                                  JOIN tr_harga_kode g 
                                    ON a.id_harga_kode = g.id AND a.id_company = g.id_company
                                  WHERE a.id = '$id'
                                  AND a.id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function get_datadetail($id) {
        return $this->db->query("
                                  SELECT 
                                    a.id_company,
                                    a.id_document,
                                    a.id_product,
                                    b.i_product_base,
                                    b.e_product_basename,
                                    a.n_quantity,
                                    a.v_price,
                                    a.n_diskon1,
                                    a.n_diskon2,
                                    a.n_diskon3,
                                    a.v_diskon1,
                                    a.v_diskon2,
                                    a.v_diskon3,
                                    a.v_diskontambahan,
                                    a.v_total_discount,
                                    a.v_total,
                                    a.e_remark 
                                  FROM tm_spb_item a
                                  JOIN tr_product_base b 
                                    ON a.id_product = b.id AND a.id_company = b.id_company
                                  WHERE a.id_document = '$id'
                                  AND a.id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
    }

    public function updateheader($id, $idocument, $ddocument, $ibagian, $dsend, $iarea, $icustomer, $isales, $ireferensiop, $nkotor, $ndiskontotal, $vdpp, $vppn, $nbersih, $eremarkh, $kodeharga){
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
                        'i_document'          => $idocument,
                        'd_document'          => $ddocument,
                        'i_bagian'            => $ibagian,
                        'd_estimate'          => $dsend,
                        'id_customer'         => $icustomer,
                        'id_area'             => $iarea,
                        'id_sales'            => $isales,
                        'i_referensi_op'      => $ireferensiop,
                        'v_kotor'             => $nkotor,
                        'v_diskon'            => $ndiskontotal,
                        'v_ppn'               => $vppn,
                        'v_dpp'               => $vdpp,
                        'v_bersih'            => $nbersih,
                        'e_remark'            => $eremarkh,
                        'id_harga_kode'       => $kodeharga,
                        'd_update'            => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb', $data);
    }

    public function deletedetail($id){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->query("DELETE FROM tm_spb_item WHERE id_document='$id' AND id_company = $idcompany", false);
    }
}
/* End of file Mmaster.php */