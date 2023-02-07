<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function __construct(){
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

    public function data($i_menu,$folder,$dfrom,$dto)
    {
        $idcompany = $this->session->id_company;
         if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_masuk_retur_wip a
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
        if ($this->session->userdata('i_departement')=='4' || $this->session->userdata('i_departement')=='1') {
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

        $sql = "SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian,
                concat(b.e_bagian_name, ' - ', c2.name) AS e_bagian_name,
                c.i_document as i_reff,
                a.e_remark,
                a.i_status,
                d.e_status_name,
                d.label_color,
                f.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM tm_masuk_retur_wip a
            INNER JOIN tr_bagian b ON (a.id_bagian_pengirim = b.id)
            LEFT JOIN tm_retur_produksi_gdjd c ON (a.id_document_reff = c.id)
            INNER JOIN tr_status_document d ON (a.i_status = d.i_status)                    
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            LEFT JOIN public.company c2 ON c2.id = b.id_company
            WHERE a.i_status <> '5'
                AND a.id_company = '$idcompany'
                $where
                $bagian
            ORDER BY a.i_document, a.d_document";

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query($sql, FALSE
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id            = trim($data['id']);
            $ibagian       = trim($data['i_bagian']);
            $i_status      = trim($data['i_status']);
            $dfrom         = trim($data['dfrom']);
            $dto           = trim($data['dto']);
            $i_level       = $data['i_level'];
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $data          = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success mr-2'></i></a>";
            }
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2'></i></a>";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-2'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9' && $i_status!='2')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close text-danger'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function bagianpembuat(){
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */

        $sql = "SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement 
                FROM tr_bagian a 
                INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
                LEFT JOIN tr_type c on (a.i_type = c.i_type)
                LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
                WHERE a.f_status = 't' 
                    AND b.i_departement = '$this->i_departement' 
                    AND username = '$this->username' 
                    AND a.id_company = '$this->id_company' 
                ORDER BY 4, 3 ASC NULLS LAST";

        // var_dump($sql); die();

        return $this->db->query($sql, false);
    }

    public function bagianpengirim($cari, $ibagian)
    {
        $cari = str_replace("'", "", $cari);

        // $sql = "SELECT
        //             b.id,
        //             a.i_bagian,
        //             b.e_bagian_name,
        //             c.name
        //         FROM tr_tujuan_menu a
        //         JOIN tr_bagian b ON (
        //                 a.i_bagian = b.i_bagian AND a.id_company = b.id_company
        //                 )
        //         JOIN public.company c ON c.id = b.id_company                    
        //         WHERE a.i_menu = '$this->i_menu'
        //             AND a.i_bagian ILIKE '%$cari%'
        //             AND b.e_bagian_name ILIKE '%$cari%'
        //         ORDER BY b.e_bagian_name";

        $sql = "SELECT DISTINCT tb.id, trpg.i_bagian, tb.e_bagian_name, c.name 
                FROM tm_retur_produksi_gdjd trpg  
                INNER JOIN tr_bagian tb ON tb.i_bagian = trpg.i_bagian  AND tb.id_company = trpg.id_company
                INNER JOIN public.company c ON c.id = trpg.id_company 
                WHERE trpg.id_bagian_tujuan = '$ibagian'
                    AND trpg.i_status = '6'
                    AND (trpg.d_document >= '2023-02-01' AND  trpg.d_document <= '2023-02-28')
                    AND trpg.i_document ILIKE '%$cari%'";

        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }    

    public function referensi($cari, $iasal, $itujuan=null)
    {
        $cari = str_replace("'", "", $cari);

        $sql = "SELECT DISTINCT a.id,
                    a.i_document,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document
                FROM tm_retur_produksi_gdjd a
                LEFT JOIN tm_retur_produksi_gdjd_item b ON (
                                                            a.id = b.id_document 
                                                            AND a.id_company = b.id_company
                                                            )
                INNER JOIN tr_bagian tb ON (
                                            tb.i_bagian = a.i_bagian 
                                            AND tb.id_company = a.id_company
                                            )
                WHERE tb.id = '$iasal'
                    AND a.i_tujuan = '$itujuan'
                    AND a.i_status = '6'
                    AND a.i_document ILIKE '%$cari%'
                    AND a.id NOT IN (
                                    SELECT id_document_reff 
                                    FROM tm_masuk_retur_wip 
                                    WHERE i_status IN ('1', '2', '3', '6', '8')
                                )
                ORDER BY i_document, d_document";       
                
        // var_dump($sql);

        return $this->db->query($sql, FALSE);
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_retur_wip');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

//    public function getdataheader($idreff, $ipengirim){
//        return $this->db->query("SELECT
//            to_char(d_document, 'dd-mm-yyyy') as d_document
//            FROM
//                tm_retur_produksi_gdjd
//            WHERE
//                id = '$idreff'
//                AND i_bagian = '$ipengirim'
//                AND id_company = '$this->idcompany'
//                                ", FALSE);
//    }

    public function getdataheader($idreff, $ipengirim){
        return $this->db->query("SELECT
            to_char(d_document, 'dd-mm-yyyy') as d_document
            FROM 
                tm_retur_produksi_gdjd
            WHERE
                id = '$idreff'", FALSE);
    }

//    public function getdataitem($idreff, $ipengirim)
//    {
//        return $this->db->query("SELECT
//                a.*,
//                b.i_product_base,
//                b.e_product_basename
//            FROM
//                tm_retur_produksi_gdjd_item a
//            INNER JOIN tr_product_base b ON
//                (b.id = a.id_product)
//            INNER JOIN tm_retur_produksi_gdjd c ON
//                (c.id = a.id_document )
//            WHERE
//                a.id_document = '$idreff'
//            ORDER BY
//                a.id", FALSE);
//    }

    public function getdataitem($idreff, $ipengirim)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_base,
                b.e_product_basename
            FROM
                tm_retur_produksi_gdjd_item a
            INNER JOIN tr_product_base b ON
                (b.id = a.id_product)
            INNER JOIN tm_retur_produksi_gdjd c ON
                (c.id = a.id_document )
            WHERE
                c.id = '$idreff'
            ORDER BY
                a.id", FALSE);
    }

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_retur_wip');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl, $tahun, $ibagian){
       $cek = $this->db->query("SELECT 
                substring(i_document, 1, 4) AS kode 
            FROM tm_masuk_retur_wip
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBMR';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 11, 4)) AS max
            FROM
                tm_masuk_retur_wip
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 4) = '$kode'
            AND substring(i_document, 6, 2) = substring('$thbl',1,2)
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

    public function generate_nomor_dokumen($id_bagian) {

        $kode = 'BBMR';

        $sql = "SELECT count(*) 
                FROM tm_masuk_retur_wip tmrw
                INNER JOIN tr_bagian tb ON tb.i_bagian = tmrw.i_bagian AND tb.id_company = tmrw.id_company
                WHERE tb.id = '$id_bagian'
                    AND to_char(d_document, 'yyyy-mm') = to_char(now(), 'yyyy-mm')
                    AND i_status <> '5'";

        $query = $this->db->query($sql);
        $result = $query->row()->count;
        $count = intval($result) + 1;
        $generated = $kode . '-' . date('ym') . '-' . sprintf('%04d', $count);

        return $generated;
    }
    

    public function insertheader($id, $ibonm, $datebonm, $ikodemaster, $iasal, $ireff, $eremark)
    {
//        $id_bagian_pengirim = $this->db->query("SELECT id FROM tr_bagian WHERE i_bagian = '$iasal' AND id_company = '$this->id_company' ")->row()->id;
        $data = array(
            'id'                 => $id,
            'id_company'         => $this->idcompany,
            'i_document'         => $ibonm,
            'd_document'         => $datebonm,
            'i_bagian'           => $ikodemaster,
            'id_bagian_pengirim' => $iasal,
            'id_document_reff'   => $ireff,
            'e_remark'           => $eremark,
            'd_entry'            => current_datetime(),
        );
        $this->db->insert('tm_masuk_retur_wip', $data);
    }

    public function insertdetail($id, $ireff, $ibonm, $idproduct, $idcolor, $nquantity, $nquantitymasuk, $edesc)
    {
        if($nquantitymasuk>0){

            $sql = "SELECT a.id 
                    FROM tr_product_wip a, tr_product_base b 
                    WHERE a.i_product_wip = b.i_product_wip 
                        AND b.i_color = a.i_color 
                        AND b.id_company = a.id_company 
                        AND a.id_company = '$this->id_company' 
                        AND b.id = '$idproduct'";

            $query = $this->db->query($sql);

            $id_product_wip = $query->row()->id;
            
            $data = array(
                'id_company'        => $this->idcompany,
                'id_document'       => $id,
                'id_document_reff'  => $ireff,
                'id_product_wip'    => $id_product_wip,
                'n_quantity'        => $nquantitymasuk,
                'n_quantity_reff'   => $nquantity,
                'e_remark'          => $edesc,
            );
            $this->db->insert('tm_masuk_retur_wip_item', $data);
        }
    }

    public function cek_data($id, $ibagian=null)
    {
        $sql = "SELECT
                    a.id,
                    a.i_document,
                    to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                    a.id_document_reff AS id_reff,
                    d.i_document as i_reff,
                    to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
                    a.i_bagian,
                    b.e_bagian_name,
                    c.i_bagian AS i_bagian_pengirim,
                    c.e_bagian_name as e_bagian_pengirim,
                    a.id_bagian_pengirim,
                    c2.name,
                    a.e_remark,
                    a.i_status 
                FROM tm_masuk_retur_wip a 
                INNER JOIN tm_retur_produksi_gdjd d ON (a.id_document_reff = d.id) 
                INNER JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company) 
                INNER JOIN tr_bagian c ON (a.id_bagian_pengirim = c.id) 
                LEFT JOIN public.company c2 ON c2.id = c.id_company
                WHERE a.id  = '$id'";

        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function cek_datadetail($id, $ibagian){
        return $this->db->query("SELECT
                a.*,
                c.id AS id_product,
                c.i_product_base,
                c.e_product_basename
            FROM
                tm_masuk_retur_wip_item a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN tr_product_base c ON
                (c.i_product_wip = b.i_product_wip
                    AND b.i_color = c.i_color
                    AND c.id_company = b.id_company)
            WHERE
                a.id_document = '$id'
            ORDER BY
                a.id
        ", FALSE);
    }

    public function updateheader($id, $ikodemaster, $ibonm, $datebonm, $eremark, $iasal, $ireff)
    {        
        $data = array(
            'i_document'          => $ibonm,
            'i_bagian'            => $ikodemaster,
            'd_document'          => $datebonm,
            'id_bagian_pengirim'  => $iasal,
            'id_document_reff'    => $ireff,
            'e_remark'            => $eremark,
            'd_update'            => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_masuk_retur_wip', $data);
    }

    public function deletedetail($id){
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_retur_wip_item');
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_retur_wip a
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
                    $this->db->query("UPDATE
                            tm_retur_produksi_gdjd_item query
                        SET
                            n_quantity = subquery.n_quantity,
                            n_sisa_retur = 0
                        FROM
                            (SELECT id_document_reff, c.id id_product, n_quantity
                            FROM
                                tm_masuk_retur_wip_item a
                            INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip)
                            INNER JOIN tr_product_base c ON (c.i_product_wip = b.i_product_wip AND b.i_color = c.i_color AND c.id_company = b.id_company)
                            WHERE
                                id_document = '$id') AS subquery
                        WHERE
                            query.id_product = subquery.id_product
                            AND query.id_document = subquery.id_document_reff");
                    /* $query = $this->db->query("SELECT id_document, id_product, n_quantity, id_reff
                        FROM tm_masuk_retur_wip_item
                        WHERE id_document = '$id' ", FALSE);
                    if ($query->num_rows()>0) {
                        foreach ($query->result() as $key) {
                            $nsisa =  $this->db->query("SELECT
                                    n_sisa
                                FROM
                                    tm_keluar_jahit_item                       
                                WHERE
                                    id_keluar_jahit = '$key->id_reff'
                                    AND id_product = '$key->id_product'
                                    AND id_company = '$this->id_company'
                                    AND n_sisa >= '$key->n_quantity'
                            ", FALSE);

                            if($nsisa->num_rows()>0){
                                $this->db->query("UPDATE
                                        tm_keluar_jahit_item
                                    SET
                                        n_sisa = n_sisa - $key->n_quantity
                                    WHERE
                                        id_keluar_jahit = '$key->id_reff'
                                        AND id_product = '$key->id_product'
                                        AND id_company = '$this->id_company'
                                        AND n_sisa >= '$key->n_quantity'
                                ", FALSE);
                            }else{
                                die();
                            }
                        }
                    } */
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_retur_wip');", FALSE);
                }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_retur_wip', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function get_bagian_by_id($id_bagian)
    {
        $sql = "SELECT * FROM tr_bagian WHERE id='$id_bagian'";

        return $this->db->query($sql);
    }
}
/* End of file Mmaster.php */