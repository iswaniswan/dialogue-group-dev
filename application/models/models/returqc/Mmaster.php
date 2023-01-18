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

    public function data($i_menu,$folder,$dfrom,$dto){
        $idcompany  = $this->session->userdata('id_company');

        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
        $cek = $this->db->query("
                                  SELECT
                                      i_bagian
                                  FROM
                                      tm_retur_keluar_qc
                                  WHERE
                                      i_status <> '5'
                                      $where
                                      AND id_company = '$this->idcompany'
                                      AND i_bagian IN (
                                          SELECT
                                              i_bagian
                                          FROM
                                              tr_departement_cover
                                          WHERE
                                              i_departement = '".$this->session->userdata('i_departement')."'
                                              AND username = '".$this->session->userdata('username')."'
                                              AND id_company = '$this->idcompany'
                                          )
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
                                                  AND id_company = '$this->idcompany')";
            }
        }
        /* Call Datatable */
		    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT DISTINCT
                               0 as no,
                               a.id,
                               a.i_document,
                               to_char(a.d_document, 'dd-mm-YYYY') as d_document,
                               a.i_tujuan,
                               b.e_bagian_name,
                               a.e_remark,
                               a.i_status,
                               c.e_status_name,
                               a.id_company,
                               c.label_color as label,
                               '$i_menu' as i_menu,
                               '$folder' as folder,
                               '$dfrom' as dfrom,
                               '$dto' as dto 
                            FROM
                               tm_retur_keluar_qc a 
                               INNER JOIN
                                  tr_bagian b 
                                  ON (a.i_tujuan = b.i_bagian 
                                  and a.id_company = b.id_company) 
                               INNER JOIN
                                  tr_status_document c 
                                  ON (a.i_status = c.i_status) 
                            WHERE
                               a.i_status != '5' 
                               AND a.id_company = '$idcompany' 
                               $where
                               $bagian 
                            ORDER BY
                               a.i_document

                          ",FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status_name'].'</span>';
        });
      
        $datatables->add('action', function ($data) {
          $id        = trim($data['id']);
          $i_status  = trim($data['i_status']);
          $dfrom     = trim($data['dfrom']);
          $dto       = trim($data['dto']);
          $i_menu    = $data['i_menu'];
          $folder    = $data['folder'];
          $data = '';
    
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='View' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $i_status !='6' && $i_status != '9'){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp";
            }
            if(check_role($i_menu, 7)){
              if ($i_status == '2') {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
              }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
              $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id');
        $datatables->hide('i_tujuan');
        $datatables->hide('id_company');
        $datatables->hide('label');
        $datatables->hide('i_status');

        return $datatables->generate();
    }

    public function getreferensi($cari, $ipartner){
        return $this->db->query("
                                  SELECT 
                                  	id,
                                  	i_document
                                  FROM
                                  	tm_masuk_qc
                                  WHERE
                                  	i_bagian_pengirim = '$ipartner'
                                      AND id_company = '$this->idcompany'
                                      AND i_status = '6'
                                  ORDER BY
                                      i_document
                                ", FALSE);
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
        $this->db->where('a.i_type', '11');
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function bacatujuan($cari, $imenu){
        return $this->db->query("
                                  SELECT DISTINCT
                                    a.id,
                                    a.i_bagian,
                                    b.e_bagian_name 
                                  FROM
                                    tr_tujuan_menu a 
                                    INNER JOIN
                                       tr_bagian b 
                                       ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company) 
                                  WHERE
                                    (a.i_bagian like '%$cari%' or b.e_bagian_name like '%$cari%')
                                    AND a.id_company = '$this->idcompany'
                                    AND a.i_menu = '$imenu'
                                  ORDER BY
                                     b.e_bagian_name
                                ", FALSE);
    }
    
    /*----------  CARI BARANG  ----------*/
    public function product($cari, $idreff, $ipartner) {
        $idcompany = $this->session->userdata('id_company');

        $where = '';
        if($idreff == '' || $idreff == NULL){
            $where .= '';
        }else{
            $where .= "AND a.id_document = '$idreff' AND b.i_bagian_pengirim = '$ipartner'";
        }

        return $this->db->query("
                                  SELECT DISTINCT
                                     a.id_product,
                                     c.i_product_base,
                                     UPPER(c.e_product_basename) AS e_product_basename,
                                     d.e_color_name 
                                  FROM
                                     tm_masuk_qc_item a
                                     LEFT JOIN
                                  	tm_masuk_qc b
                                  	ON (a.id_document = b.id AND a.id_company = b.id_company)
                                     INNER JOIN
                                  	tr_product_base c
                                  	ON (a.id_product = c.id AND a.id_company = c.id_company)
                                     INNER JOIN
                                        tr_color d 
                                        ON (c.i_color = d.i_color 
                                        AND a.id_company = d.id_company) 
                                  WHERE
                                     a.id_company = '$idcompany' 
                                     AND b.i_status = '6'
                                     AND 
                                     (
                                        c.i_product_base ILIKE '%$cari%' 
                                        OR c.e_product_basename ILIKE '%$cari%' 
                                     )
                                     $where
                                  ORDER BY
                                     c.i_product_base ASC
                                ", FALSE);
    }

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_retur_keluar_qc');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }
    
    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_retur_keluar_qc');
        return $this->db->get()->row()->id+1;
    }
    
    public function runningnumber($thbl,$tahun,$ibagian){
        $cek = $this->db->query("
                                  SELECT 
                                    substring(i_document, 1, 2) AS kode 
                                  FROM 
                                    tm_retur_keluar_qc
                                  WHERE 
                                    i_status <> '5'
                                    AND i_bagian = '$ibagian'
                                    AND id_company = '$this->idcompany'
                                  ORDER BY 
                                    id DESC
                                ", FALSE);
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJ';
        }
        $query  = $this->db->query("
                                    SELECT
                                        max(substring(i_document, 9, 6)) AS max
                                    FROM
                                        tm_retur_keluar_qc
                                    WHERE 
                                      to_char (d_document, 'yyyy') >= '$tahun'
                                      AND i_status <> '5'
                                      AND i_bagian = '$ibagian'
                                      AND substring(i_document, 1, 2) = '$kode'
                                      AND substring(i_document, 4, 2) = substring('$thbl',1,2)
                                      AND id_company = '$this->idcompany'
                                  ", FALSE);
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
    
    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
    
    public function changestatus($id,$istatus){
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
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_retur_keluar_qc', $data);
    }
    
    public function insertheader($id, $idocument, $dateretur, $ibagian, $idpartner, $ireff, $eremark){
        $dentry = date("Y-m-d H:s:i");
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
                      'id'              => $id,
                      'i_document'      => $idocument,
                      'd_document'      => $dateretur,
                      'i_bagian'        => $ibagian,
                      'i_tujuan'        => $idpartner,
                      'id_reff'         => $ireff,
                      'i_status'        => '1',
                      'e_remark'        => $eremark,
                      'id_company'      => $idcompany,    
                      'd_entry'         => $dentry,  
        );
        $this->db->insert('tm_retur_keluar_qc', $data);
    }
    
    public function insertdetail($id,$idproduct,$nquantity,$edesc){
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
                      'id_document'         => $id,
                      'id_product_base'     => $idproduct,
                      'n_quantity'          => $nquantity,
                      'n_sisa'              => $nquantity,
                      'e_remark'            => $edesc,
                      'id_company'          => $idcompany, 
        );
        $this->db->insert('tm_retur_keluar_qc_item', $data);
    }
    
    public function cek_data($id){
        return $this->db->query("
                                  SELECT 
                                     a.id,
                                     a.i_bagian,
                                     b.e_bagian_name,
                                     a.i_tujuan, 
                                     c.e_bagian_name as e_tujuan_name,
                                     a.i_document,
                                     to_char(a.d_document, 'dd-mm-YYYY') as d_document,
                                     a.e_remark,
                                     a.i_status,
                                     a.id_reff,
                                     d.i_document as i_document_reff
                                  FROM
                                     tm_retur_keluar_qc a 
                                     INNER JOIN
                                        tr_bagian b 
                                        ON (a.i_bagian = b.i_bagian 
                                        and a.id_company = b.id_company) 
                                     INNER JOIN
                                         tr_bagian c
                                         ON (a.i_tujuan = c.i_bagian
                                         and a.id_company = c.id_company)
                                      LEFT JOIN
                                          tm_masuk_qc d
                                          ON (a.id_reff = d.id AND a.id_company = d.id_company)
                                  WHERE
                                     a.id = '$id'
                                     AND a.id_company = '$this->idcompany'
                                ", FALSE);
    }
    
    public function cek_datadetail($id){
        return $this->db->query("   
                                  SELECT
                                     a.id,
                                     a.id_document,
                                     a.id_product_base,
                                     c.i_product_base,
                                     c.e_product_basename,
                                     a.n_quantity,
                                     d.e_color_name,
                                     a.e_remark
                                  FROM
                                     tm_retur_keluar_qc_item a 
                                     LEFT JOIN
                                        tm_retur_keluar_qc b 
                                        ON (a.id_document = b.id 
                                        and a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_product_base c 
                                        ON (a.id_product_base = c.id 
                                        and a.id_company = c.id_company) 
                                     INNER JOIN
                                  	tr_color d
                                  	ON (c.i_color = d.i_color 
                                  	and a.id_company = d.id_company)
                                  WHERE
                                     a.id_document = '$id' 
                                     AND a.id_company = '$this->idcompany'
                                ", false);
    }

    public function deletedetail($id){
        $this->db->query("DELETE FROM tm_retur_keluar_qc_item WHERE id_document='$id'", false);
    }
    
    public function updateheader($id, $idocument, $ddocument, $ibagian, $idpartner, $ireff, $eremark){
        $dupdate = date("Y-m-d H:s:i");
        $data = array(
                        'i_document'  => $idocument,
                        'i_bagian'    => $ibagian,
                        'd_document'  => $ddocument,
                        'i_tujuan'    => $idpartner,
                        'id_reff'     => $ireff,
                        'e_remark'    => $eremark,
                        'd_update'    => $dupdate,
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_retur_keluar_qc', $data);
    }
}
/* End of file Mmaster.php */