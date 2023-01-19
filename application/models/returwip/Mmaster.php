<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$folder,$dfrom,$dto){
        $idcompany  = $this->session->userdata('id_company');
        if ($dfrom!='' && $dto!='') {
              $dfrom = date('Y-m-d', strtotime($dfrom));
              $dto   = date('Y-m-d', strtotime($dto));
              $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
                $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
          
        $cek = $this->db->query(
            "SELECT
                i_bagian
            FROM
                tm_retur_keluar_wip a
            WHERE
                i_status <> '5'
                AND id_company = '".$this->session->userdata('id_company')."'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND username = '".$this->session->userdata('username')."')
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
                            AND id_company = '".$this->session->userdata('id_company')."'
                            AND username = '".$this->session->userdata('username')."')";
                }
            }

        $datatables->query(
            "SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_tujuan,
                b.e_bagian_name || ' - '||bb.name as e_bagian_name,
                to_char(d.d_document, 'dd-mm-yyyy') as d_document_tujuan,
                a.e_remark,
                a.id_company,
                a.i_status,
                c.e_status_name,
                a.i_bagian,
                c.label_color,
                f.i_level,
                l.e_level_name,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' as i_menu,
                '$folder' AS folder
            FROM
                tm_retur_keluar_wip a 
            JOIN tr_bagian b ON (a.i_tujuan = b.i_bagian AND a.id_bagian_company = b.id_company) 
            JOIN public.company bb ON (bb.id = b.id_company)
            JOIN tr_status_document c ON (a.i_status = c.i_status) 
            left join tm_masuk_retur_jahit d on (a.id = d.id_reff)
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE a.id_company = '$idcompany' AND a.i_status <> '5'
            $where
            $bagian
            ORDER BY
                a.i_document asc", false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id           = trim($data['id']);
            $ibagian      = $data['i_bagian'];  
            $i_status     = trim($data['i_status']);
            $itujuan      = trim($data['i_tujuan']);
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];    
            $i_level      = $data['i_level'];
            
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$ibagian\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }            
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box fa-lg text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            // if (check_role($i_menu, 4) && ($i_status=='1')) {
            //     $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            // }
                     
            return $data;
        });
      
        $datatables->hide('folder'); 
        $datatables->hide('i_menu');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_company');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_tujuan');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_retur_keluar_wip a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2", FALSE)->row();
            if ($istatus == '3') {
            	if ($awal->i_approve_urutan - 1 == 0 ) {
            		$data = array(
	                    'i_status'  => $istatus,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
            	}
            	$this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
                    $data = array(
	                    'i_status'  => $istatus,
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
                $now = date('Y-m-d');
            	$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_retur_keluar_wip');", FALSE);
                }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_retur_keluar_wip', $data);
    }

    public function changestatus_20211213($id,$istatus){
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
        $this->db->update('tm_retur_keluar_wip', $data);
    }

    public function bagian(){
      /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
      $this->db->where('username', $this->session->userdata('username'));
      $this->db->where('a.id_company', $this->session->userdata('id_company'));
      $this->db->order_by('e_bagian_name');
      return $this->db->get(); */
      return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_retur_keluar_wip');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian, $id=''){
        $split = explode("|", $ibagian);
        $id_company = $split[0];
        $ibagian = $split[1];
        $query = $this->db->query("SELECT b.e_no_doc_retur, b.e_no_doc FROM tr_bagian a 
            INNER JOIN tr_kategori_jahit b ON (b.id = a.id_kategori_jahit) 
            WHERE id_company = '$id_company' AND a.i_bagian = '$ibagian'");
        if ($query->num_rows()>0) {
            $kode = $query->row()->e_no_doc_retur;
        }else{
            $kode = 'SJR';
        }
        $and = '';
        if(strlen($id) > 0){
            $and = "AND id <> '$id' ";
        }
        // var_dump($kode);
        if (strlen($kode)==4) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 11, 4)) AS max 
                FROM tm_retur_keluar_wip
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
                $and
            ", false);
        }elseif (strlen($kode)==3) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 10, 4)) AS max 
                FROM tm_retur_keluar_wip
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
                $and;
            ", false);
        }elseif (strlen($kode)==2) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 9, 4)) AS max 
                FROM tm_retur_keluar_wip
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
                $and
            ", false);
        }
        if ($sql->num_rows() > 0){          
            foreach($sql->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 4){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "0001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function runningnumber_20220614($thbl,$tahun,$ibagian){
    //var_dump($thbl);
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_retur_keluar_wip
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'STB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_retur_keluar_wip
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
            while($n < 4){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "0001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_retur_keluar_wip');
        return $this->db->get()->row()->id+1;
    }

    public function tujuan($i_menu, $idcompany){
      /* return $this->db->query(" 
      SELECT DISTINCT
            a.i_bagian,
            b.e_bagian_name
        FROM
            tr_tujuan_menu a
            JOIN tr_bagian b 
            ON (b.i_bagian = a.i_bagian AND b.id_company = a.id_company)
        WHERE
            a.id_company = '$idcompany'
            AND a.i_menu = '2090505'
        ORDER BY
            b.e_bagian_name"); */
        return $this->db->query(
            "SELECT b.name, a.id, a.i_bagian, a.id_company, a.e_bagian_name 
            FROM tr_bagian a
            INNER JOIN public.company b ON (b.id = a.id_company)
            WHERE a.f_status = 't' AND a.i_type = '10' AND b.f_status = 't' AND b.i_apps = '2'
            AND (
                SELECT array_agg(id) FROM tr_type_makloon 
                WHERE e_type_makloon_name ILIKE '%MAKLOON JAHIT%'
            ) @> a.id_type_makloon
            ORDER BY 1,5");
    }

    public function dataproduct($cari){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("    
                                    SELECT  
                                        a.id,
                                        a.i_product_base,
                                        a.e_product_basename,
                                        a.i_color,
                                        b.e_color_name
                                    FROM
                                        tr_product_base a
                                    INNER JOIN tr_color b ON
                                        (b.i_color = a.i_color AND a.id_company = b.id_company)
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND
                                        (upper(a.i_product_base) LIKE '%$cari%'
                                        OR upper(a.e_product_basename) LIKE '%$cari%') ", FALSE);
    }

    public function alasanrepair($cari=''){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT  
                id,
                e_alasan_name,
                e_group, e_remark
            FROM
                tr_alasan_retur_repair where f_status = true and e_alasan_name ilike '%$cari%'", FALSE);
    }

    public function getproduct($eproduct){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("            
                                    SELECT 
                                        a.id as id_product, 
                                        a.i_product_base,
                                        a.e_product_basename,
                                        b.id as id_color,
                                        a.i_color,
                                        b.e_color_name
                                    FROM
                                        tr_product_base a
                                    INNER JOIN tr_color b ON
                                        (b.i_color = a.i_color AND a.id_company = b.id_company)
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND 
                                        a.id = '$eproduct'
        ", FALSE);
    }

    public function getstok($idproduct,$ibagian){
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d',strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("            
            SELECT DISTINCT 
                a.id,
                /* CASE
                 WHEN c.saldo_akhir IS NULL THEN 0 
                 WHEN c.saldo_akhir < 0 THEN 0 ELSE c.saldo_akhir
                END AS saldo_akhir, */
                CASE
                 WHEN (c.n_saldo_akhir_repair + c.n_saldo_akhir) IS NULL THEN 0 
                 WHEN (c.n_saldo_akhir_repair + c.n_saldo_akhir) < 0 THEN 0 ELSE (c.n_saldo_akhir_repair + c.n_saldo_akhir)
                END AS saldo_akhir
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color
                AND a.id_company = b.id_company)
                LEFT JOIN (SELECT * FROM produksi.f_mutasi_wip($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                (c.id_product_base = a.id AND c.id_company = '$idcompany')
            WHERE
                a.id = '$idproduct'
                AND a.id_company = '$idcompany'
                AND a.f_status = 't'
                AND b.f_status = 't'
            ORDER BY
                a.id ASC
        ", FALSE);
    }
      
    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark){	
        $idcompany  = $this->session->userdata('id_company');
        $split = explode("|", $itujuan);
        $id_company = $split[0];
        $itujuan = $split[1];
        $data = array(
                        'id_company'        => $idcompany,
                        'id'                => $id,
                        'i_document'        => $ibonk,
                        'd_document'        => $datebonk,
                        'i_bagian'          => $ibagian,
                        'i_tujuan'          => $itujuan,
                        'id_bagian_company' => $id_company,
                        'e_remark'          => $eremark,
                        'i_status'          => '1',
                        'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_retur_keluar_wip', $data);
    }

    public function insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc, $ialasan){
        $idcompany  = $this->session->userdata('id_company');
    	$data = array(
                        'id_document'   => $id,
                        'id_product'        => $iproduct,
                        'n_quantity'=> $nqtyproduct,
                        'n_sisa'            => $nqtyproduct,
                        'id_company'        => $idcompany,
                        'e_remark'          => $edesc,
                        'id_alasan_retur_repair' => $ialasan,
        );
    	$this->db->insert('tm_retur_keluar_wip_item', $data);
    }    

    public function cek_data($id, $idcompany){
        return $this->db->query("SELECT
                a.*,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                c.e_bagian_name,
                b.name
            FROM
                tm_retur_keluar_wip a
            INNER JOIN tr_bagian c ON (c.i_bagian = a.i_tujuan AND a.id_bagian_company = c.id_company)
            INNER JOIN public.company b ON (b.id = a.id_bagian_company)
            WHERE
                a.id = '$id' ", FALSE);
    }

    public function cek_datadetail($id, $idcompany, $ibagian){
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d',strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query(
            "SELECT
                a.id_document,
                a.id_product,
                b.i_product_base,
                b.e_product_basename,
                c.id as id_color,
                c.i_color,
                c.e_color_name,
                coalesce(f.saldo_akhir,0) saldo_akhir,
                a.n_quantity as n_quantity_product,
                a.e_remark,
                a.id_alasan_retur_repair,
                e.e_alasan_name 
            FROM
                tm_retur_keluar_wip_item a  
                JOIN tm_retur_keluar_wip d ON a.id_document = d.id 
                JOIN tr_product_base b ON a.id_product = b.id AND d.id_company = b.id_company 
                JOIN tr_color c ON b.i_color = c.i_color AND d.id_company = c.id_company 
                LEFT JOIN tr_alasan_retur_repair e ON e.id = a.id_alasan_retur_repair
                JOIN
                    (SELECT DISTINCT 
                    a.id,
                    CASE
                    WHEN (c.n_saldo_akhir_repair + c.n_saldo_akhir) IS NULL THEN 0 
                    WHEN (c.n_saldo_akhir_repair + c.n_saldo_akhir) < 0 THEN 0 ELSE (c.n_saldo_akhir_repair + c.n_saldo_akhir)
                    END AS saldo_akhir
                FROM
                    tr_product_base a
                INNER JOIN tr_color b ON
                    (a.i_color = b.i_color
                    AND a.id_company = b.id_company)
                    LEFT JOIN (SELECT * FROM produksi.f_mutasi_wip($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                    (c.id_product_base = a.id AND c.id_company = '$idcompany')
                WHERE
                    a.id_company = '$idcompany'
                    AND a.f_status = 't'
                    AND b.f_status = 't') f
                    ON f.id = a.id_product
            WHERE
                a.id_document = '$id' 
                AND d.id_company = '$idcompany'
            ", FALSE);
    }

    public function view_datadetail($id, $idcompany){
        return $this->db->query(
            "SELECT
                a.id_document,
                a.id_product,
                b.i_product_base,
                b.e_product_basename,
                c.id as id_color,
                c.i_color,
                c.e_color_name,
                a.n_quantity as n_quantity_product,
                a.e_remark,
                a.id_alasan_retur_repair,
                e.e_alasan_name 
            FROM
                tm_retur_keluar_wip_item a  
                JOIN tm_retur_keluar_wip d ON a.id_document = d.id 
                JOIN tr_product_base b ON a.id_product = b.id AND d.id_company = b.id_company 
                JOIN tr_color c ON b.i_color = c.i_color AND d.id_company = c.id_company 
                LEFT JOIN tr_alasan_retur_repair e ON e.id = a.id_alasan_retur_repair
            WHERE
                a.id_document = '$id' 
                AND d.id_company = '$idcompany'
            ", FALSE);
    }

    public function updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark){
        $idcompany  = $this->session->userdata('id_company');   
        $split = explode("|", $itujuan);
        $id_company = $split[0];
        $itujuan = $split[1];
        $data = array(
            'i_document'        => $ibonk,
            'd_document'        => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'id_bagian_company' => $id_company,
            'e_remark'          => $eremark,
            'i_status'          => '1',
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_retur_keluar_wip', $data);
    }

    public function deletedetail($id){
        $idcompany  = $this->session->userdata('id_company');  
        $this->db->where('id_document',$id);
        $this->db->where('id_company',$idcompany);
        $this->db->delete('tm_retur_keluar_wip_item');
    }
}
/* End of file Mmaster.php */