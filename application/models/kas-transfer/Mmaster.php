<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public $idcompany;
  //public $i_menu = '2090609';

  function __construct(){
      parent::__construct();
      $this->idcompany = $this->session->id_company;
  }

  function data($i_menu, $folder, $dfrom, $dto){
     $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
                                  SELECT
                                      i_bagian
                                  FROM
                                      tm_kas_transfer
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
                             0 as no,
                             a.id,
                             a.id_company,
                             a.i_document,
                             to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                             a.i_bagian,
                             e.e_kas_name,
                             a.v_nilai,
                             f.e_kas_name as e_kas_name_tujuan ,
                             a.i_status,
                             a.e_remark, 
                             c.e_status_name,
                             c.label_color, 
                             '$i_menu' as i_menu,
                             '$folder' as folder,
                             '$dfrom' AS dfrom,
                             '$dto' AS dto
                            FROM
                            tm_kas_transfer a   
                            JOIN tr_status_document c 
                              ON (c.i_status = a.i_status) 
                            LEFT JOIN tr_kas_bank e 
                              ON (a.id_kas_bankaw = e.id AND a.id_company = e.id_company) 
                            LEFT JOIN tr_kas_bank f 
                              ON (a.id_kas_bankak = f.id AND a.id_company = e.id_company) 
                            WHERE
                              a.d_document between to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                              AND a.id_company= '$id_company' 
                            $bagian
                            ORDER BY d_document ASC
                          ",FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('v_nilai', function ($data) {
            $data = "Rp. ".number_format($data['v_nilai']);
            return $data;
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
      $this->db->from('tm_kas_transfer');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata("id_company"));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function cek_kodeedit($kode,$kodeold,$ibagian) {
      $this->db->select('i_document');
      $this->db->from('tm_kas_transfer');
      $this->db->where('i_document', $kode);
      $this->db->where('i_document <>', $kodeold);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function kasbank($cari){
      $idcompany    = $this->session->userdata('id_company');
      return $this->db->query("
                                SELECT
                                a.id,
                                a.i_kode_kas,
                                a.e_kas_name,
                                a.i_bank,
                                b.e_coa_name
                                    FROM
                                tr_kas_bank a
                                    INNER JOIN tr_coa b on (a.i_coa = b.i_coa) 
                                    WHERE 
                                (a.i_kode_kas like '%$cari%' or a.e_kas_name like '%$cari%') AND a.id_company = '$idcompany' AND a.f_status = 't'
                                AND  ((b.i_coa_ledger = '110-12000' and b.i_coa <> b.i_coa_ledger) or (b.i_coa_ledger = '110-20000' and b.i_coa <> b.i_coa_ledger))
                                    ORDER BY 
                                a.e_kas_name
                              ", FALSE);
  }

  public function kasbanktujuan($ikasbankaw){
      $idcompany    = $this->session->userdata('id_company');
      return $this->db->query("
                                SELECT
                                a.id,
                                a.i_kode_kas,
                                a.e_kas_name,
                                a.i_bank,
                                b.e_coa_name
                                    FROM
                                tr_kas_bank a
                                    INNER JOIN tr_coa b on (a.i_coa = b.i_coa) 
                                    WHERE 
                                a.id <> '$ikasbankaw' AND a.id_company = '$idcompany' AND a.f_status = 't'
                                AND  ((b.i_coa_ledger = '110-12000' and b.i_coa <> b.i_coa_ledger) or (b.i_coa_ledger = '110-20000' and b.i_coa <> b.i_coa_ledger))
                                    ORDER BY 
                                a.e_kas_name
                              ", FALSE);
  }

  public function customer($cari){
      $idcompany    = $this->session->userdata('id_company');
      return $this->db->query("
                                SELECT DISTINCT
                                  a.id,
                                  a.e_customer_name
                                FROM
                                  tr_customer a                        
                                WHERE 
                                  (a.e_customer_name like '%$cari%')
                                AND a.id_company = '$idcompany'
                                AND a.f_status = 't'
                                ORDER BY 
                                  a.e_customer_name
                              ", FALSE);
  }

  function getcustomer($idcustomer){
      $idcompany    = $this->session->userdata('id_company');

      if($idcustomer != ''){
          $in_str = "'".implode("', '", $idcustomer)."'";
      }else{
          $in_str = "";
      }
       
      if (strpos($in_str,'ALCUS') !== false) {
          //echo "a";
          $where = '';      
      }else{
           $where  = "AND a.id IN (".$in_str.")";
          //echo "b";
      }
      return $this->db->query("
                                SELECT DISTINCT
                                  a.id,
                                  a.i_customer,
                                  a.e_customer_name
                                FROM
                                  tr_customer a                        
                                WHERE 
                                a.id_company = '$idcompany'
                                AND a.f_status = 't'
                                $where
                                ORDER BY a.i_customer ASC
                              ", FALSE);
  }

  public function getdataheader($idreff, $ipengirim){
      return $this->db->query("
                                SELECT
                                    to_char(d_document, 'dd-mm-yyyy') as d_document
                                FROM 
                                    tm_keluar_produksibp
                                WHERE
                                    id = '$idreff'
                                    AND i_bagian = '$ipengirim'
                                    AND id_company = '$this->idcompany'
                              ", FALSE);
  }

  public function getdataitem($idreff, $ipengirim){
      return $this->db->query("
                              SELECT DISTINCT 
                                a.id,
                                a.id_product as id_material,
                                d.i_material,
                                d.e_material_name,
                                a.n_quantity as n_quantity,
                                a.n_quantity_sisa as n_quantity_sisa,
                                a.e_remark
                              FROM
                                tm_keluar_produksibp_item a
                                LEFT JOIN tm_keluar_produksibp b
                                  ON (a.id_document = b.id AND a.id_company = b.id_company)
                                INNER JOIN tr_material d
                                  ON (a.id_product = d.id AND a.id_company = d.id_company)
                              WHERE
                                b.id = '$idreff' 
                                AND a.id_document = '$idreff'
                                AND b.id_company = '$this->idcompany'
                                AND b.i_bagian = '$ipengirim'
                                AND a.n_quantity_sisa <> 0
                              ", FALSE);
  }

  public function runningid(){
      $this->db->select('max(id) AS id');
      $this->db->from('tm_kas_transfer');
      return $this->db->get()->row()->id+1;
  }

  public function runningnumber($thbl, $tahun, $ibagian){
      $cek = $this->db->query("
          SELECT 
              substring(i_document, 1, 2) AS kode 
          FROM tm_kas_transfer
          WHERE i_status <> '5'
          AND i_bagian = '$ibagian'
          AND id_company = '".$this->session->userdata("id_company")."'
          ORDER BY id DESC");
      if ($cek->num_rows()>0) {
          $kode = $cek->row()->kode;
      }else{
          $kode = 'TF';
      }
      $query  = $this->db->query("
          SELECT
              max(substring(i_document, 9, 6)) AS max
          FROM
            tm_kas_transfer
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

  function insertheader($id, $idocument, $datedocument, $ibagian, $ikasbankaw, $ikasbankak, $vnilai, $eremark){
      $data = array(
                      'id'                 => $id,
                      'id_company'         => $this->idcompany,
                      'i_document'         => $idocument,
                      'd_document'         => $datedocument,
                      'i_bagian'           => $ibagian,
                      'id_kas_bankaw'      => $ikasbankaw,
                      'id_kas_bankak'      => $ikasbankak,
                      'v_nilai'            => $vnilai,
                      'e_remark'           => $eremark,
                      'd_entry'            => current_datetime(),
      );
      $this->db->insert('tm_kas_transfer', $data);
  }

  public function updateheader($id, $idocument, $datedocument, $ibagian, $ikasbankaw, $ikasbankak, $vnilai, $eremark){
      $data = array(                      
                      'i_document'         => $idocument,
                      'd_document'         => $datedocument,
                      'i_bagian'           => $ibagian,
                      'id_kas_bankaw'      => $ikasbankaw,
                      'id_kas_bankak'      => $ikasbankak,
                      'v_nilai'            => $vnilai,
                      'e_remark'           => $eremark,
                      'd_update'           => current_datetime(),
      );

      $this->db->where('id', $id);
      $this->db->where('id_company', $this->idcompany);
      $this->db->update('tm_kas_transfer', $data);
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
      $this->db->where('id_company', $this->idcompany);
      $this->db->update('tm_kas_transfer', $data);
  }

  public function cek_data($id){
      return $this->db->query("
                              SELECT 
                                a.id,
                                a.i_document, 
                                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                                a.i_bagian,
                                b.e_bagian_name,
                                a.v_nilai,
                                a.e_remark,
                                a.i_status,
                                c.e_kas_name as e_kas_nameaw, 
                                d.e_kas_name as e_kas_nameak, 
                                e.e_coa_name as e_coa_nameaw, 
                                f.e_coa_name as e_coa_nameak,
                                a.id_kas_bankaw, 
                                a.id_kas_bankak
                              FROM
                                 tm_kas_transfer a
                                INNER JOIN tr_bagian b
                                  ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                                LEFT JOIN tr_kas_bank c
                                  ON (a.id_kas_bankaw = c.id AND a.id_company = c.id_company)
                                LEFT JOIN tr_kas_bank d
                                  ON (a.id_kas_bankak = d.id AND a.id_company = d.id_company)
                                LEFT JOIN tr_coa e
                                  ON (c.i_coa = e.i_coa)
                                LEFT JOIN tr_coa f
                                  ON (d.i_coa = f.i_coa) 
                                WHERE 
                                  a.id  = '$id'
                                  AND a.id_company = '$this->idcompany'
                              ", FALSE);
  }
}
/* End of file Mmaster.php */