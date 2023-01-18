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
		$datatables = new Datatables(new CodeigniterAdapter);
    $idcompany  = $this->session->userdata('id_company');

		 $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_dn_ap_retur_beli
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
                                 0 AS no,
                                 a.id, 
                                 a.i_document,
                                 to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                 a.id_supplier,
                                 e.e_supplier_name,
                                 array_agg(distinct(c.i_document)) AS i_reff,
                                 a.v_total,
                                 a.e_remark,
                                 a.i_status,
                                 e_status_name,
                                 a.id_company,
                                 a.i_bagian,
                                 label_color,  
                                 '$i_menu' AS i_menu,
                                 '$folder' AS folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto
                              FROM
                                 tm_dn_ap_retur_beli a
                                 INNER JOIN 
                                    tm_dn_ap_retur_beli_item b 
                                    ON (a.id = b.id_document AND a.id_company = b.id_company)
                                 INNER JOIN tm_nota_retur_beli c 
                                    ON (b.id_document_reff = c.id AND b.id_company = c.id_company)
                                 INNER JOIN
                                    tr_status_document d 
                                    ON (d.i_status = a.i_status) 
                                 INNER JOIN 
                                    tr_supplier e 
                                    ON (a.id_supplier = e.id AND a.id_company = e.id_company)
                              WHERE
                                 a.i_status <> '5' 
                              AND
                                 a.id_company = '$idcompany' 
                                 $bagian
                              GROUP BY 
                                 a.id,
                                 a.i_document,
                                 a.d_document,
                                 a.id_supplier,
                                 e.e_supplier_name,
                                 a.v_total,
                                 a.e_remark,
                                 e_status_name,
                                 label_color
                              ORDER BY
                                 a.i_document ASC
                            ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('i_reff', function ($data) {
            return '<span>'.str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['i_reff']))).'</span>';
        });
        
        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $idsupplier = $data['id_supplier'];
            $i_menu     = $data['i_menu'];
            $i_status   = $data['i_status'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$dfrom/$dto/$id/$idsupplier/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$dfrom/$dto/$id/$idsupplier/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$dfrom/$dto/$id/$idsupplier/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
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
        $datatables->hide('id');
        $datatables->hide('i_bagian');
        $datatables->hide('id');
        $datatables->hide('id_supplier');
        $datatables->hide('id_company');

        return $datatables->generate();
	}

  public function bagian(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->idcompany);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
  }

  public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_dn_ap_retur_beli');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
  }

  public function runningnumber($thbl,$tahun,$ibagian){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_dn_ap_retur_beli 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->idcompany'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'DN';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_dn_ap_retur_beli
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->idcompany'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
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

  public function supplier($cari){
        return $this->db->query("
                                  SELECT 
                                    a.*, b.id as id_nota_retur
                                  FROM
                                    tr_supplier a
                                  JOIN tm_nota_retur_beli b 
                                    ON (a.id = b.id_supplier AND a.id_company = b.id_company)
                                  WHERE b.id 
                                        NOT IN 
                                        (SELECT id_document FROM tm_dn_ap_retur_beli_item)
                                  AND b.i_status = '6'
                                  AND a.id_company = '$this->idcompany'
                                ", FALSE);
  }

  public function getreferensi($isupplier) {
        return $this->db->query("
                                  SELECT 
                                    a.id, 
                                    a.i_document, 
                                    to_char(a.d_document, 'dd-mm-yyyy') as d_document 
                                  FROM 
                                    tm_nota_retur_beli a
                                  JOIN 
                                    tr_supplier b 
                                    ON (a.id_supplier = b.id AND a.id_company = b.id_company)
                                  WHERE a.id 
                                      NOT IN (SELECT id_document FROM tm_dn_ap_retur_beli_item)  
                                  AND a.i_status = '6' 
                                  AND a.id_supplier = '$isupplier'
                                  AND a.id_company = '$this->idcompany'
                                ", FALSE);
  }

  public function getdetailreff($ireferensi, $isupplier){
        $in_str = "'".implode("', '", $ireferensi)."'";
        $and   = "AND a.id_document IN (".$in_str.")";
        return $this->db->query("
                                  SELECT 
                                    a.id_document, 
                                    b.i_document,
                                    a.id_material,
                                    c.i_material, 
                                    c.e_material_name, 
                                    a.n_retur, 
                                    a.v_price 
                                  FROM 
                                    tm_nota_retur_beli_item a
                                  JOIN tm_nota_retur_beli b 
                                    ON (a.id_document = b.id AND a.id_company = b.id_company)
                                  JOIN tr_material c 
                                    ON (a.id_material = c.id AND a.id_company =  c.id_company)
                                  WHERE
                                    b.id_supplier = '$isupplier'
                                  AND a.id_company = '$this->idcompany'
                                  $and
                                ", FALSE);
  }

  public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_dn_ap_retur_beli');
        return $this->db->get()->row()->id+1;
  }

  public function insertheader($id, $inoteretur, $datenoteretur, $ibagian, $isupplier, $vtotalfa, $eremark){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
                      'id'                   => $id,
                      'i_document'           => $inoteretur,
                      'd_document'           => $datenoteretur, 
                      'i_bagian'             => $ibagian,
                      'id_supplier'          => $isupplier, 
                      'v_total'              => $vtotalfa,  
                      'v_sisa'               => $vtotalfa,   
                      'e_remark'             => $eremark,
                      'id_company'           => $idcompany,
        );
        $this->db->insert('tm_dn_ap_retur_beli', $data);
  }

  public function insertdetail($id, $idnotaretur, $idmaterial, $nquantity, $vprice, $vpricetotal){
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
                      'id_document'         => $id, 
                      'id_document_reff'    => $idnotaretur,
                      'id_material'         => $idmaterial,
                      'n_quantity'          => $nquantity,
                      'v_price'             => $vprice,
                      'v_price_total'       => $vpricetotal,
                      'id_company'          => $idcompany,
        );
        $this->db->insert('tm_dn_ap_retur_beli_item', $data);
  }

  /*function cekreturbelibb($ireferensi, $irefer){
      $this->db->select("i_retur_beli from tm_retur_belibahanbaku where id='$ireferensi' and i_retur_beli='$irefer' and id_company = '$this->idcompany' ", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $i_nota = $kuy->i_retur_beli; 
        }else{
            $i_nota = '';
        }
        return $i_nota;
  }

  function cekreturaksesoris($ireferensi, $irefer){
      $this->db->select("i_retur_beli from tm_retur_beliaksesories where id='$ireferensi' and i_retur_beli='$irefer' and id_company = '$this->idcompany'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $i_nota = $kuy->i_retur_beli; 
        }else{
            $i_nota = '';
        }
        return $i_nota;
  }

  function cekreturplastik($ireferensi, $irefer){
      $this->db->select("i_retur_beli from tm_retur_belibahanpembantu where id='$ireferensi' and i_retur_beli='$irefer' and id_company = '$this->idcompany'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $i_nota = $kuy->i_retur_beli; 
        }else{
            $i_nota = '';
        }
        return $i_nota;
  }

  function cekreturgdjd($ireferensi, $irefer){
      $this->db->select("i_retur_beli from tm_retur_beligdjd where id='$ireferensi' and i_retur_beli='$irefer' and id_company = '$this->idcompany'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $i_nota = $kuy->i_retur_beli; 
        }else{
            $i_nota = '';
        }
        return $i_nota;
  }

  function updatenotabb($isupplier, $ireferensi, $irefer){
        $data = array(
                        'f_debet_nota_retur' => 't'
        );
        $this->db->where('id', $ireferensi);
        $this->db->where('i_retur_beli', $irefer);
        $this->db->where('i_supplier',$isupplier);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_retur_belibahanbaku', $data);
  }

  function updatenotaaks($isupplier, $ireferensi, $irefer){
        $data = array(
                        'f_debet_nota_retur' => 't'
        );
        $this->db->where('id', $ireferensi);
        $this->db->where('i_retur_beli', $irefer);
        $this->db->where('i_supplier',$isupplier);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_retur_beliaksesories', $data);
  }

  function updatenotapl($isupplier, $ireferensi, $irefer){
        $data = array(
                        'f_debet_nota_retur' => 't'     
        );
        $this->db->where('id', $ireferensi);
        $this->db->where('i_retur_beli', $irefer);
        $this->db->where('i_supplier',$isupplier);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_retur_belibahanpembantu', $data);
  }

  function updatenotagj($isupplier, $ireferensi, $irefer){
        $data = array(
                        'f_debet_nota_retur' => 't'     
        );
        $this->db->where('id', $ireferensi);
        $this->db->where('i_retur_beli', $irefer);
        $this->db->where('i_supplier',$isupplier);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_retur_beligdjd', $data);
  }*/

  public function cek_header($id, $idsupplier){
        $this->db->select("    
                              DISTINCT a.id,
                              a.i_document,
                              a.d_document,
                              a.i_bagian,
                              a.id_supplier,
                              d.e_supplier_name,
                              b.id_document_reff,
                              c.i_document as i_document_reff,
                              to_char(c.d_document, 'dd-mm-yyyy') as d_document_reff, 
                              a.v_total,
                              a.i_status,
                              a.e_remark
                            FROM 
                              tm_dn_ap_retur_beli a
                            JOIN tm_dn_ap_retur_beli_item b 
                              ON (a.id = b.id_document AND a.id_company = b.id_company)
                            JOIN tm_nota_retur_beli c
                              ON (b.id_document_reff = c.id AND a.id_supplier = c.id_supplier AND a.id_company = c.id_company)
                            JOIN tr_supplier d
                              ON (a.id_supplier = d.id AND a.id_company = d.id_company)
                            WHERE
                              a.id = '$id' 
                              AND a.id_supplier = '$idsupplier' 
                              AND a.id_company = '$this->idcompany'
                          ", FALSE);
        return $this->db->get();
  }

  public function cek_detail($id){
        $this->db->select("
                              a.id_company, 
                              a.id_document, 
                              a.id_document_reff, 
                              c.i_document as i_document_ref, 
                              a.id_material, 
                              d.i_material, 
                              d.e_material_name,
                              a.n_quantity, 
                              a.v_price, 
                              a.v_price_total
                            FROM 
                              tm_dn_ap_retur_beli_item a 
                            JOIN tm_dn_ap_retur_beli b 
                              ON (a.id_document = b.id AND a.id_company = b.id_company)
                            JOIN tm_nota_retur_beli c
                              ON (a.id_document_reff = c.id AND b.id_supplier = c.id_supplier AND a.id_company = c.id_company)
                            JOIN tr_material d 
                              ON (a.id_material = d.id AND a.id_company = d.id_company)
                            WHERE
                              a.id_document = '$id' 
                              AND a.id_company = '$this->idcompany'
                        ", FALSE);
        return $this->db->get();
  }

  public function updateheader($id, $inoteretur, $datenoteretur, $isupplier, $vtotalfa, $eremark){
        $dupdate = date("Y-m-d H:i:s");

        $data = array(
                      'i_document'        => $inoteretur,
                      'd_document'        => $datenoteretur, 
                      'v_total'           => $vtotalfa,     
                      'v_sisa'            => $vtotalfa,
                      'e_remark'          => $eremark,
                      'd_update'          => $dupdate,      
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_dn_ap_retur_beli', $data);
  }

  public function deletedetail($id){
        $this->db->query("DELETE FROM tm_dn_ap_retur_beli_item WHERE id_document='$id'", false);
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
                          'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_dn_ap_retur_beli', $data);
  }
}
/* End of file Mmaster.php */