<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
/*(a.d_faktur_date_from ||' s/d '||a.d_faktur_date_to) as periode, */
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
        $where = "AND d_retur BETWEEN '$dfrom' AND '$dto'";
    }else{
        $where = "";
    }

    $cek = $this->db->query("
                            SELECT
                                i_bagian
                            FROM
                                tm_retur_belibahanbaku
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
		$datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("
                        SELECT DISTINCT
                          0 as no,
                        	a.id,
                        	a.i_retur_beli,
                        	to_char(a.d_retur,'dd-mm-YYYY') as d_retur,
                        	c.i_btb,
                        	a.i_supplier,
                        	d.e_supplier_name,
                        	a.i_status,
                        	e.e_status_name,
                          a.id_company,
                        	e.label_color as label,
                        	'$i_menu' as i_menu,
                          '$folder' as folder,
                          '$dfrom' as dfrom,
                          '$dto' as dto
                        FROM 
                        	tm_retur_belibahanbaku a
                        	LEFT JOIN 
                        		tm_retur_belibahanbaku_item b ON (a.id = b.id_retur_beli AND a.id_company = b.id_company)
                        	LEFT JOIN 
                        		tm_btb c ON (b.id_btb = c.id and a.id_company = c.id_company)
                        	LEFT JOIN 
                        		tr_supplier d ON (a.i_supplier = d.i_supplier and a.id_company = d.id_company)
                        	LEFT JOIN 
                        		tr_status_document e ON (a.i_status = e.i_status)
                        WHERE 
                        	a.i_status != '5'
                        AND
                          a.id_company = '$idcompany'
                          $where
                          $bagian
                        ORDER BY
                          a.i_retur_beli
                        ", FALSE);

    $datatables->edit('e_status_name', function ($data) {
      return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status_name'].'</span>';
    });

    $datatables->add('action', function ($data) {
      $id        = trim($data['id']);
      $iretur    = trim($data['i_retur_beli']);
      $isupplier = trim($data['i_supplier']);
      $i_status  = trim($data['i_status']);
      $dfrom     = trim($data['dfrom']);
      $dto       = trim($data['dto']);
      $i_menu    = $data['i_menu'];
      $folder    = $data['folder'];
      $data = '';

          if(check_role($i_menu, 2)){
              $data .= "<a href=\"#\" title='View' onclick='show(\"$folder/cform/view/$id/$iretur/$isupplier/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
          }
          if(check_role($i_menu, 3) && $i_status !='6' && $i_status != '9'){
              $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$iretur/$isupplier/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp";
          }
          if(check_role($i_menu, 7)){
            if ($i_status == '2') {
              $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$iretur/$isupplier/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
            }
          }
          if (check_role($i_menu, 4) && ($i_status=='1')) {
            $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
          }
          return $data;
    });
            
    $datatables->hide('i_menu');
    $datatables->hide('folder');
    $datatables->hide('i_supplier');
    $datatables->hide('i_status');
    $datatables->hide('label');
    $datatables->hide('id');
    $datatables->hide('dfrom');
    $datatables->hide('dto');
    $datatables->hide('id_company');

    return $datatables->generate();
	}

  public function bacagudang(){
        // $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        // $this->db->from('tr_bagian a');
        // $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        // $this->db->where('a.f_status', 't');
        // $this->db->where('i_departement', $this->session->userdata('i_departement'));
        // $this->db->where('username', $this->session->userdata('username'));
        // $this->db->where('a.id_company', $this->session->userdata('id_company'));        
        // $this->db->order_by('e_bagian_name');
        // return $this->db->get();

        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
          INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
          LEFT JOIN tr_type c on (a.i_type = c.i_type)
          LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
          WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
          ORDER BY 4, 3 ASC NULLS LAST
        ", false);
  }

  public function cek_kode($kode,$ibagian){
      $this->db->select('i_retur_beli');
      $this->db->from('tm_retur_belibahanbaku');
      $this->db->where('i_retur_beli', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->idcompany);
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function runningid(){
      $this->db->select('max(id) AS id');
      $this->db->from('tm_retur_belibahanbaku');
      return $this->db->get()->row()->id+1;
  }

  public function runningnumber($thbl,$tahun,$ibagian){
      $cek = $this->db->query("
                              SELECT 
                                substring(i_retur_beli, 1, 3) AS kode 
                              FROM 
                                tm_retur_belibahanbaku 
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
          $kode = 'RPB';
      }
      $query  = $this->db->query("
                                SELECT
                                    max(substring(i_retur_beli, 10, 6)) AS max
                                FROM
                                    tm_retur_belibahanbaku
                                WHERE 
                                  to_char (d_retur, 'yyyy') >= '$tahun'
                                  AND i_status <> '5'
                                  AND i_bagian = '$ibagian'
                                  AND substring(i_retur_beli, 1, 3) = '$kode'
                                  AND substring(i_retur_beli, 5, 2) = substring('$thbl',1,2)
                                  AND id_company = '$this->idcompany'
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

  public function bacasupplier($cari){
    $idepartement = $this->session->userdata('i_departement');
    return $this->db->query("
                              SELECT DISTINCT 
                              	a.i_supplier, 
                              	c.e_supplier_name
                              FROM 
                              	tm_btb a 
                                LEFT JOIN tm_btb_item b ON (a.id = b.id_btb AND a.id_company = b.id_company)
                              	INNER JOIN tr_supplier c ON (a.i_supplier = c.i_supplier AND a.id_company = c.id_company)
                              WHERE 
                              	a.id_company = '$this->id_company' AND a.i_status = '6' AND b.n_retur_sisa > 0
                              	/*AND a.i_bagian IN (
                              		SELECT
                              			i_bagian 
                              		FROM 
                              			tr_bagian a 
                              			LEFT JOIN tr_type b ON 
                              				(a.i_type = b.i_type)
                              		WHERE 
                              			b.i_departement = '$idepartement'
                              			AND a.id_company = '$this->id_company'
                                )*/
                                AND (a.i_supplier ILIKE '%$cari%' OR c.e_supplier_name ILIKE '%$cari%')
                              ORDER BY 
                                a.i_supplier
                            ", FALSE);
  }  

  public function bacanota($isupplier){
    $idept = $this->session->userdata('i_departement');
    return $this->db->query("
                              SELECT DISTINCT
                                 a.*,
                                 c.e_supplier_name 
                              FROM
                                 tm_btb a 
                                 JOIN
                                    tm_btb_item b 
                                    ON (a.id = b.id_btb 
                                    and a.id_company = b.id_company) 
                                 JOIN
                                    tr_supplier c 
                                    ON (a.i_supplier = c.i_supplier 
                                    and a.id_company = c.id_company) 
                              WHERE
                                 a.i_supplier = '$isupplier' 
                                 AND a.i_status = '6' 
                                 AND b.n_retur_sisa <> 0 
                                 AND a.id_company = '$this->idcompany'
                              ", FALSE);
  }

  public function getmemo($idnota){
        return $this->db->query("SELECT to_char(a.d_btb, 'dd-mm-yyyy') as d_nota from tm_btb a where a.id = '$idnota' AND a.id_company = '$this->idcompany'", false);
  }

  public function getmemodetail($isupplier, $idnota, $ibagian){
        return $this->db->query("
                                  SELECT DISTINCT
                                  a.id_btb,
                                  b.i_sj_supplier,
                                  a.i_material,
                                  c.e_material_name,
                                  a.n_quantity,
                                  a.n_retur_sisa,
                                  a.v_price,
                                  a.i_satuan_code,
                                  d.e_satuan_name
                                FROM
                                  tm_btb_item a
                                  inner join tm_btb b ON (a.id_btb = b.id AND a.id_company = b.id_company)
                                  inner join tr_material c ON (a.i_material = c.i_material AND a.id_company = c.id_company)
                                  inner JOIN tr_satuan d ON (a.i_satuan_code = d.i_satuan_code AND a.id_company = d.id_company)
                                  inner join tr_bagian e on (e.i_bagian = '$ibagian' and e.id_company = '$this->idcompany')
                                  inner join tr_type f on (e.i_type = f.i_type and f.i_kode_group_barang = c.i_kode_group_barang)
                                WHERE 
                                    b.i_status = '6'
                                    AND a.id_btb = '$idnota'
                                    AND b.i_supplier = '$isupplier'
                                    AND a.n_retur_sisa <> 0
                                    AND b.id_company = '$this->idcompany'
                                ORDER BY 
                                    a.i_material
                                ", FALSE);
  }  

  public function insertheader($id, $iretur, $dateretur, $ibagian, $isupplier, $esupplier, $vtot, $eremark){
    $dentry = date("Y-m-d H:s:i");
    $idcompany  = $this->session->userdata('id_company');
    $data = array(
        'id'              => $id,
        'i_retur_beli'    => $iretur,
        'd_retur'         => $dateretur,
        'i_bagian'        => $ibagian,
        'i_supplier'      => $isupplier,
        'e_supplier_name' => $esupplier,
        'v_total'         => $vtot,
        'i_status'        => '1',
        'e_remark'        => $eremark,
        'id_company'      => $idcompany,    
        'd_entry'         => $dentry,  
    );
    $this->db->insert('tm_retur_belibahanbaku', $data);
  }

  public function insertdetail($id, $idbtb, $isj, $imaterial, $isatuan, $nquantity, $vunitprice, $edesc){
    $idcompany  = $this->session->userdata('id_company');
    $data = array(
      'id_retur_beli'  => $id,
      'id_btb'         => $idbtb,
      'i_sj_supplier'  => $isj,
      'i_material'     => $imaterial,
      'i_satuan_code'  => $isatuan,
      'n_quantity'     => $nquantity,
      'v_price'        => $vunitprice,
      'e_remark'       => $edesc,
      'id_company'     => $idcompany, 
    );
    $this->db->insert('tm_retur_belibahanbaku_item', $data);
  }

  // public function insertreturbeli($idn, $inota){
  //   $data = array(
  //               'i_no_retur_beli_faktur'  => $idn,
  //               'i_no_dn_retur'           => $idn,
  //               'i_pembelian_nofaktur'    => $inota
  //             );
  //   $this->db->insert('tm_retur_beli_faktur', $data);
  // }

	public function cek_data($id, $iretur, $isupplier){
    return $this->db->query("
                            SELECT DISTINCT
                              a.id,
                            	a.i_bagian,
                              b.e_bagian_name,
                              a.i_retur_beli,
                              to_char(a.d_retur, 'dd-mm-YYYY') as d_retur,
                              a.i_supplier,
                              a.e_supplier_name,
                              a.e_remark,
                              a.v_total,
                              c.id_btb,
                              d.i_btb,
                              to_char(d.d_btb, 'dd-mm-yyyy') as d_btb,
                              a.i_status
                            FROM
                            	tm_retur_belibahanbaku a 
                            	LEFT JOIN 
                            		tr_bagian b 
                            		ON (a.i_bagian = b.i_bagian and a.id_company = b.id_company)
                            	LEFT JOIN 
				                      	tm_retur_belibahanbaku_item c
				                      	ON (a.id = c.id_retur_beli and a.id_company = c.id_company)
				                      LEFT JOIN 
				                      	tm_btb d 
				                      	ON (c.id_btb = d.id and a.id_company = d.id_company)
                            WHERE 
                            	a.id = '$id'
                            	AND a.i_retur_beli = '$iretur'
                              AND a.i_supplier = '$isupplier'
                              AND a.id_company = '$this->idcompany'
                            ", FALSE);
  }

  public function cek_datadetail($id, $iretur, $isupplier){
    return $this->db->query("
                            SELECT 
                              a.id,
                            	a.i_material,
                            	c.e_material_name,
                            	e.n_quantity AS n_quantity_btb,
                              e.n_retur_sisa as n_quantity_sisa_retur,
                              a.n_quantity AS n_quantity_retur,
                              a.i_satuan_code,
                              d.e_satuan_name,
                              a.v_price,
                              a.e_remark,
                            	f.i_sj_supplier
                            FROM
                            	tm_retur_belibahanbaku_item a 
                            	LEFT JOIN 
                            		tm_retur_belibahanbaku b 
                            		ON (a.id_retur_beli = b.id and a.id_company = b.id_company)
                            	LEFT JOIN
                            		tr_material c 
                            		ON (a.i_material = c.i_material and a.id_company = c.id_company)
                            	LEFT JOIN 
                            		tr_satuan d
                            		ON (a.i_satuan_code = d.i_satuan_code and a.id_company = d.id_company)
                            	LEFT JOIN 
				                      	tm_btb_item e
				                      	ON (a.id_btb = e.id_btb AND a.i_material = e.i_material and a.id_company = e.id_company)
				                      LEFT JOIN 
				                      	tm_btb f
				                      	ON (a.id_btb = f.id and a.id_company = f.id_company)
                            WHERE 
                            	a.id_retur_beli = '$id'
                            	AND b.i_retur_beli = '$iretur'
                              AND b.i_supplier = '$isupplier'
                              AND b.id_company = '$this->idcompany'
                            ", false);
  }

  public function updateheader($idretur, $iretur, $ibagian, $dateretur, $vtot, $eremark){
    $dupdate = date("Y-m-d H:s:i");
    $data = array(
      'i_retur_beli'=> $iretur,
      'i_bagian'    => $ibagian,
      'd_retur'     => $dateretur,
      'v_total'     => $vtot,
      'e_remark'    => $eremark,
      'd_update'    => $dupdate,
    );
    $this->db->where('id', $idretur);
    $this->db->where('id_company', $this->idcompany);
    $this->db->update('tm_retur_belibahanbaku', $data);
  }

  public function updatedetail($id, $idretur, $imaterial, $isatuan, $nquantity, $edesc){
    $data = array(
      'n_quantity'     => $nquantity,
      'e_remark'       => $edesc,
    );
    $this->db->where('id', $id);
    $this->db->where('id_retur_beli', $idretur);
    $this->db->where('i_material', $imaterial);
    $this->db->where('i_satuan_code', $isatuan);
    $this->db->where('id_company', $this->idcompany);
    $this->db->update('tm_retur_belibahanbaku_item', $data);
  }

   public function estatus($istatus){
      $this->db->select('e_status_name');
      $this->db->from('tr_status_document');
      $this->db->where('i_status',$istatus);
      return $this->db->get()->row()->e_status_name;
  }

  public function changestatus($id,$istatus){
    if ($istatus=='6') {
        $query = $this->db->query("
            SELECT 
               id_btb, 
               i_material, 
               n_quantity 
            FROM 
               tm_retur_belibahanbaku_item
            WHERE 
               id_retur_beli = '$id' 
               AND id_company = '$this->idcompany'
        ", FALSE);
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                $this->db->query("
                    UPDATE
                        tm_btb_item
                    SET
                        n_retur = n_retur + $key->n_quantity,
                        n_retur_sisa = n_retur_sisa - $key->n_quantity
                    WHERE
                        id_btb = '$key->id_btb'
                        AND i_material = '$key->i_material'
                        AND id_company = '$this->idcompany'
                ", FALSE);
            }
        }
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
    $this->db->update('tm_retur_belibahanbaku', $data);
  }
}
/* End of file Mmaster.php */
