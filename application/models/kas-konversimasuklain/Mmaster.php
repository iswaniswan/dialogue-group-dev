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

    function data($i_menu, $folder, $dfrom, $dto){
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_kas_konversi_masuk_kelainlain
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
                              SELECT DISTINCT 
                                 0 as no,
                                 a.id,
                                 a.id_company,
                                 a.i_document,
                                 to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                 a.i_bagian,
                                 a.e_partner_type,
                                 CASE
                                     WHEN a.e_partner_type = 'customer' THEN string_agg(DISTINCT f.e_customer_name,', ') 
                                     WHEN a.e_partner_type = 'pic' THEN string_agg(DISTINCT g.e_nama_karyawan,', ')
                                 END AS e_partner_name, 
                                 a.id_document_reff,  
                                 h.i_document as referensi,                              
                                 a.i_status,
                                 a.e_remark, 
                                 c.e_status_name,
                                 c.label_color, 
                                 '$i_menu' as i_menu,
                                 '$folder' as folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto
                              FROM
                                tm_kas_konversi_masuk_kelainlain a   
                                 JOIN
                                    tm_kas_konversi_masuk_kelainlain_item b
                                    ON a.id = b.id_document AND a.id_company = b.id_company
                                 JOIN
                                    tr_status_document c 
                                    ON (c.i_status = a.i_status) 
                                 JOIN
                                    tr_kas_bank e 
                                    ON (a.id_kas_bank = e.id AND a.id_company = e.id_company) 
                                 LEFT JOIN
                                    tr_customer f 
                                    ON (a.id_partner = f.id AND a.id_company = f.id_company) 
                                 LEFT JOIN
                                    tr_karyawan g 
                                    ON (a.id_partner = g.id AND a.id_company = g.id_company) 
                                 LEFT JOIN
                                    tm_kas_masuklain_nonpiutang h 
                                    ON (a.id_document_reff = h.id AND a.id_company = h.id_company)
                              WHERE
                                 a.d_document between to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND a.id_company= '$id_company' 
                              $bagian
                              GROUP BY
                                a.id,
                                a.i_document,
                                a.d_document,
                                e.e_kas_name,
                                a.i_status,
                                c.e_status_name,
                                c.label_color,
                                h.i_document
                              ORDER BY i_document ASC
        ",FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $epartnertype   = trim($data['e_partner_type']);
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $i_status       = $data['i_status'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';

            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$epartnertype/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$epartnertype/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$epartnertype/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
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
        $datatables->hide('id_document_reff');
        $datatables->hide('e_partner_type');

        return $datatables->generate();
    }

    public function bagianpembuat(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('b.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_kas_konversi_masuk_kelainlain');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode,$kodeold,$ibagian) {
        $this->db->select('i_document');
        $this->db->from('tm_kas_konversi_masuk_kelainlain');
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
                                  SELECT DISTINCT
                                    a.id,
                                    a.i_kode_kas,
                                    a.e_kas_name,
                                    a.i_bank
                                  FROM
                                    tr_kas_bank a                        
                                  WHERE 
                                    (a.i_kode_kas like '%$cari%' or a.e_kas_name like '%$cari%')
                                  AND a.id_company = '$idcompany'
                                  AND a.f_status = 't'
                                  ORDER BY 
                                    a.e_kas_name
                                ", FALSE);
    }

    public function partner($cari){
        $idcompany    = $this->session->userdata('id_company');
        return $this->db->query("
                                  SELECT
                                     z.e_partner_type,
                                     z.id_partner,
                                     z.i_partner,
                                     z.e_partner_name,
                                     z.id_company 
                                  FROM
                                     (
                                        SELECT
                                           'customer' as e_partner_type,
                                           a.id as id_partner,
                                           a.i_customer as i_partner,
                                           a.e_customer_name as e_partner_name,
                                           a.id_company 
                                        FROM
                                           tr_customer a 
                                        WHERE
                                           a.f_status = 't'

                                        UNION ALL
                                        SELECT
                                           'pic' as e_partner_type,
                                           a.id,
                                           a.e_nik as i_partner,
                                           a.e_nama_karyawan as e_partner_name,
                                           a.id_company 
                                        FROM
                                           tr_karyawan a 
                                        WHERE
                                           a.f_status = 't'
                                     )
                                     as z 
                                     JOIN
                                        tm_kas_masuklain_nonpiutang_item b 
                                        ON z.id_partner = b.id_partner 
                                        AND z.i_partner = b.i_partner
                                        AND z.id_company = b.id_company
                                     JOIN
                                        tm_kas_masuklain_nonpiutang c 
                                        ON b.id_document = c.id
                                        AND b.id_company = c.id_company
                                     WHERE z.e_partner_name ILIKE '%$cari%'
                                     AND c.i_status = '6'
                                     AND b.n_sisa <> '0'
                                     AND z.id_company = '$idcompany'
                                     --AND c.n_sisa <> '0'
                                ", FALSE);
    }

    public function referensi($cari, $idpartner, $epartnertype){
        $idcompany    = $this->session->userdata('id_company');
        return $this->db->query("
                                  SELECT DISTINCT
                                    a.id,
                                    a.i_document,
                                    to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                    b.n_nilai
                                  FROM
                                    tm_kas_masuklain_nonpiutang a 
                                  JOIN 
                                    tm_kas_masuklain_nonpiutang_item b        
                                    ON a.id = b.id_document 
                                    AND a.id_company = b.id_company             
                                  WHERE 
                                    (a.i_document ILIKE '%$cari%')
                                    AND a.e_partner_type = '$epartnertype'
                                    AND b.id_partner = '$idpartner'
                                    AND a.id_company = '$idcompany'
                                    AND a.i_status = '6'
                                    --AND a.n_sisa <> '0'
                                    AND b.n_sisa <> '0'
                                  ORDER BY 
                                    a.i_document
                                ", FALSE);
    }

    public function customer($cari){
        $idcompany    = $this->session->userdata('id_company');
        return $this->db->query("
                                  SELECT DISTINCT
                                    a.id,
                                    a.i_customer,
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

    function getitemcustomer($icustomer){
        $idcompany    = $this->session->userdata('id_company');
        if($icustomer != ''){
            $in_str = "'".implode("', '", $icustomer)."'";
        }else{
            $in_str = "";
        }
         
        if (strpos($in_str,'ALL') !== false) {
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
                                    ORDER BY
                                       a.e_customer_name", FALSE);
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

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_kas_konversi_masuk_kelainlain');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl, $tahun, $ibagian){
        $cek = $this->db->query("
                SELECT 
                  substring(i_document, 1, 3) AS kode 
                FROM tm_kas_konversi_masuk_kelainlain
                WHERE i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '".$this->session->userdata("id_company")."'
                ORDER BY id DESC");
        if ($cek->num_rows()>0) {
          $kode = $cek->row()->kode;
        }else{
          $kode = 'KPD';
        }
        $query  = $this->db->query("
              SELECT
                  max(substring(i_document, 10, 6)) AS max
              FROM
                tm_kas_konversi_masuk_kelainlain
              WHERE to_char (d_document, 'yyyy') >= '$tahun'
              AND i_status <> '5'
              AND i_bagian = '$ibagian'
              AND substring(i_document, 1, 3) = '$kode'
              AND substring(i_document, 5, 2) = substring('$thbl',1,2)
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

    function insertheader($id, $idocument, $datedocument, $ibagian, $ikasbank, $idpartner, $epartnertype, $idreferensi, $vnilaisisa, $vnilai, $vnilaiold, $eremark){

        $data = array(
                        'id'                 => $id,
                        'id_company'         => $this->idcompany,
                        'i_document'         => $idocument,
                        'd_document'         => $datedocument,
                        'i_bagian'           => $ibagian,
                        'id_document_reff'   => $idreferensi,
                        'id_partner'         => $idpartner,
                        'e_partner_type'     => $epartnertype,
                        'id_kas_bank'        => $ikasbank,
                        'n_nilai'            => $vnilai,
                        'n_sisa'             => $vnilaisisa,
                        'n_nilai_old'        => $vnilaiold,
                        'n_sisa_document'    => $vnilai,
                        'e_remark'           => $eremark,
                        'd_entry'            => current_datetime(),
      );
      $this->db->insert('tm_kas_konversi_masuk_kelainlain', $data);
    }

    function insertdetail($id, $idcustomer, $vvalue, $edesc){
      $data = array(
                        'id_company'          => $this->idcompany,
                        'id_document'         => $id,
                        'id_customer'         => $idcustomer,
                        'n_nilai'             => $vvalue,
                        'n_sisa'              => $vvalue,
                        'e_remark'            => $edesc,
      );
      $this->db->insert('tm_kas_konversi_masuk_kelainlain_item', $data);
    }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function getpartnertype($id){
        $this->db->select('e_partner_type');
        $this->db->from('tm_kas_konversi_masuk_kelainlain');
        $this->db->where('id',$id);
        return $this->db->get()->row()->e_partner_type;
    }

    public function changestatus($id, $istatus, $epartnertype){
        $dreceive = '';
        $dreceive = date('Y-m-d');
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $query = $this->db->query("                
                                        SELECT DISTINCT
                                           a.id,
                                           a.id_document_reff,
                                           a.id_partner,
                                           a.e_partner_type,
                                           a.n_sisa,
                                           a.n_nilai,
                                           c.i_partner 
                                        FROM
                                           tm_kas_konversi_masuk_kelainlain a 
                                           JOIN
                                              tm_kas_masuklain_nonpiutang b 
                                              ON a.id_document_reff = b.id 
                                              AND a.id_company = b.id_company 
                                           JOIN
                                              tm_kas_masuklain_nonpiutang_item c 
                                              ON b.id = c.id_document 
                                              AND a.id_partner = c.id_partner 
                                              AND a.id_company = c.id_company 
                                              AND b.id_company = c.id_company 
                                        WHERE
                                            b.e_partner_type = '$epartnertype'
                                            AND
                                            a.id = '$id' 
                                      ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $this->db->query("
                                        UPDATE
                                            tm_kas_masuklain_nonpiutang_item
                                        SET
                                            n_sisa = n_sisa - $key->n_nilai
                                        WHERE
                                            id_document = '$key->id_document_reff'
                                            AND id_partner = '$key->id_partner'   
                                            AND i_partner = '$key->i_partner'                       
                                            AND id_company = '".$this->session->userdata('id_company')."'
                                    ", FALSE);
                }                
            }
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
        $this->db->update('tm_kas_konversi_masuk_kelainlain', $data);
    }

    public function cek_partner($id, $epartnertype){
        return $this->db->query("
                                  SELECT
                                     a.id_partner,
                                     CASE
                                         WHEN a.e_partner_type = 'customer' THEN  c.e_customer_name
                                         WHEN a.e_partner_type = 'pic' THEN d.e_nama_karyawan
                                     END AS e_partner_name,
                                     a.e_partner_type
                                  FROM
                                     tm_kas_konversi_masuk_kelainlain a 
                                     LEFT JOIN
                                       tr_customer c 
                                        ON (a.id_partner = c.id 
                                        AND a.id_company = c.id_company) 
                                     LEFT JOIN
                                       tr_karyawan d 
                                        ON (a.id_partner = d.id 
                                        AND a.id_company = d.id_company) 
                                  WHERE
                                     a.id = '$id' 
                                     AND a.e_partner_type = '$epartnertype'
                                     AND a.id_company = '$this->idcompany'
                                  ORDER BY a.e_partner_type ASC
                                ", FALSE);
    }

    public function cek_customer($id){
        return $this->db->query("
                                  SELECT
                                     a.id_customer,
                                     b.i_customer,
                                     b.e_customer_name
                                  FROM
                                     tm_kas_konversi_masuk_kelainlain_item a 
                                     INNER JOIN
                                       tr_customer b 
                                        ON (a.id_customer = b.id 
                                        AND a.id_company = b.id_company) 
                                  WHERE
                                     a.id_document = '$id' 
                                     AND b.id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function cek_data($id, $epartnertype){
      return $this->db->query("                              
                                SELECT
                                   a.id,
                                   a.i_document,
                                   to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                   a.i_bagian,
                                   b.e_bagian_name,
                                   a.id_document_reff,
                                   d.i_document as referensi,
                                   to_char(d.d_document, 'dd-mm-yyyy') as dreferensi,
                                   a.id_kas_bank,
                                   c.e_kas_name,
                                   e.n_nilai as nilai_ref,
                                   a.n_nilai,
                                   a.n_sisa,
                                   a.n_nilai_old,
                                   a.e_remark,
                                   a.i_status 
                                FROM
                                   tm_kas_konversi_masuk_kelainlain a 
                                   INNER JOIN
                                      tr_bagian b 
                                      ON (a.i_bagian = b.i_bagian 
                                      AND a.id_company = b.id_company) 
                                   INNER JOIN
                                      tr_kas_bank c 
                                      ON (a.id_kas_bank = c.id 
                                      AND a.id_company = c.id_company)
                                   INNER JOIN 
                                      tm_kas_masuklain_nonpiutang d
                                      ON a.id_document_reff = d.id
                                      AND a.id_company = d.id_company
                                   INNER JOIN 
                                     tm_kas_masuklain_nonpiutang_item e
                                      ON a.id_document_reff = e.id_document 
                                      AND a.id_partner = e.id_partner
                                      AND a.id_company = e.id_company
                                WHERE 
                                  a.id  = '$id'
                                  AND a.e_partner_type = '$epartnertype'
                                  AND a.id_company = '$this->idcompany'
                              ", FALSE);
    }

    public function cek_datadetail($id){
      return $this->db->query("                               
                                SELECT
                                   a.id_document,
                                   a.id_customer,
                                   b.i_customer,
                                   b.e_customer_name,
                                   a.n_nilai,
                                   a.n_sisa,
                                   e.n_sisa as sisa_detailref,
                                   a.e_remark 
                                FROM
                                   tm_kas_konversi_masuk_kelainlain_item a 
                                   JOIN
                                      tr_customer b 
                                      ON a.id_customer = b.id 
                                      AND a.id_company = b.id_company
                                   JOIN 
                                       tm_kas_konversi_masuk_kelainlain c
                                       ON a.id_document = c.id 
                                       AND a.id_company = c.id_company
                                   JOIN 
                                      tm_kas_masuklain_nonpiutang d
                                      ON c.id_document_reff = d.id
                                      AND a.id_company = d.id_company
                                   JOIN 
                                     tm_kas_masuklain_nonpiutang_item e
                                      ON d.id = e.id_document
                                      AND c.id_partner = e.id_partner
                                      AND a.id_company = e.id_company
                                WHERE 
                                     a.id_document = '$id'
                                     AND a.id_company = '$this->idcompany'
                                ORDER BY b.i_customer ASC
                              ", FALSE);
    }

    public function updateheader($id, $idocument, $datedocument, $ibagian, $ikasbank, $idpartner, $epartnertype, $idreferensi, $vnilaisisa, $vnilai, $vnilaiold, $eremark){
      $data = array(                      
                        'i_document'         => $idocument,
                        'd_document'         => $datedocument,
                        'id_document_reff'   => $idreferensi,
                        'id_partner'         => $idpartner,
                        'e_partner_type'     => $epartnertype,
                        'id_kas_bank'        => $ikasbank,
                        'n_nilai'            => $vnilai,
                        'n_sisa'             => $vnilaisisa,
                        'n_nilai_old'        => $vnilaiold,
                        'n_sisa_document'    => $vnilai,
                        'e_remark'           => $eremark,
                        'd_update'           => current_datetime(),
      );

      $this->db->where('id', $id);
      $this->db->where('id_company', $this->idcompany);
      $this->db->where('i_bagian', $ibagian);
      $this->db->update('tm_kas_konversi_masuk_kelainlain', $data);
  }

  function deletedetail($id){
      $idcompany    = $this->session->userdata('id_company'); 
      return $this->db->query("DELETE FROM tm_kas_konversi_masuk_kelainlain_item WHERE id_document = '$id' AND id_company = '$idcompany'", FALSE);
  }
}
/* End of file Mmaster.php */