<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($i_menu, $i_menu1, $folder, $folder1, $dfrom, $dto)
    {
        $idcompany = $this->id_company;
         if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_masuk_qc a
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
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian_pengirim,
                a.i_bagian,
                b.e_bagian_name||' - '||bb.name as e_bagian_name,
                c.i_keluar_jahit as i_referensi,
                g.e_jenis_name as jenis,
                a.i_status,
                a.e_remark,
                d.e_status_name,
                d.label_color,
                f.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_masuk_qc a
            INNER JOIN tm_keluar_jahit c
                ON (a.id_reff = c.id)
            INNER JOIN tr_bagian b
                ON (c.i_bagian = b.i_bagian AND c.id_company = b.id_company)
            INNER JOIN public.company bb ON (bb.id = b.id_company)
            INNER JOIN tr_status_document d
                ON (a.i_status = d.i_status)                    
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            LEFT JOIN tr_jenis_barang_keluar g ON
                (g.id = a.id_jenis_barang_keluar)
            WHERE
                a.i_status <> '5'
            AND 
                a.id_company = '$idcompany'
            $where
            $bagian
            UNION ALL
            SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_bagian_pengirim,
                a.i_bagian,
                d.e_bagian_name,
                b.i_document as i_referensi,
                '' as jenis,
                a.i_status,
                a.e_remark, 
                c.e_status_name,
                c.label_color, 
                f.i_level,
                l.e_level_name,
                '$i_menu1' as i_menu,
                '$folder1' as folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_masuk_packing_fgudang a   
            JOIN
                tm_keluar_produksibp b
                ON b.id = a.id_reff AND a.id_company = b.id_company                                    
            JOIN
                tr_status_document c 
                ON (c.i_status = a.i_status) 
            JOIN
                tr_bagian d 
                ON (a.i_bagian_pengirim = d.i_bagian AND a.id_company = d.id_company)                     
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)

            WHERE
                a.id_company = '$idcompany' 
                AND a.i_status <> '5'
                $where
                $bagian
            ", FALSE
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
            $data         .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success mr-2'></i></a>";
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-2'></i></a>";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box fa-lg text-primary mr-2'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9' && $i_status!='2')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close fa-lg text-danger'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_bagian_pengirim');
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
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function bagianpengirim($cari,$ibagian)
    {
        $cari = str_replace("'", "", $cari);
        /* return $this->db->query(
            "SELECT DISTINCT
                a.i_bagian,
                b.e_bagian_name
            FROM
                tr_tujuan_menu a
                JOIN tr_bagian b 
                ON (b.i_bagian = a.i_bagian AND b.id_company = a.id_company)
            WHERE
                a.id_company = '$this->idcompany'
                AND (a.i_bagian ILIKE '%$cari%'
                OR b.e_bagian_name ILIKE '%$cari%')
            ORDER BY
                b.e_bagian_name
        ", FALSE); */

        $sql = "SELECT
                    DISTINCT b.id, b.i_bagian, b.id_company, b.e_bagian_name, c.name as company_name
                FROM
                    tm_keluar_jahit a
                INNER JOIN tm_keluar_jahit_item ab ON (ab.id_keluar_jahit = a.id)
                INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
                INNER JOIN public.company c ON (c.id = b.id_company)
                WHERE a.id_company_bagian = '$this->idcompany' AND a.i_tujuan = '$ibagian'
                AND (b.i_bagian ILIKE '%$cari%'
                    OR b.e_bagian_name ILIKE '%$cari%') AND a.i_status = '6'
                    AND ab.n_quantity_product <> 0
                    AND ab.n_sisa <> 0
                ORDER BY 5, 4";

        // var_dump($sql); die();

        return $this->db->query($sql);
    }

    public function referensi($cari,$iasal)
    {
        $split = explode('|', $iasal);
        $id_company = $split[1];
        $i_bagian = $split[0];
        $cari = str_replace("'", "", $cari);
        return $this->db->query(
            "SELECT DISTINCT
                a.id,
                a.i_keluar_jahit as i_document,
                to_char(a.d_keluar_jahit, 'dd-mm-yyyy') AS d_document,
                c.e_jenis_name
            FROM
                tm_keluar_jahit a
            LEFT JOIN tm_keluar_jahit_item b
                on (a.id = b.id_keluar_jahit)
            LEFT JOIN tr_jenis_barang_keluar c
                on (c.id = a.id_jenis_barang_keluar)
            WHERE
                a.i_bagian = '$i_bagian'
                AND a.i_status = '6'
                AND a.id_company = '$id_company'
                AND b.n_quantity_product <> 0
                AND b.n_sisa <> 0
                AND a.i_keluar_jahit ILIKE '%$cari%'
            ORDER BY
                i_document,
                d_document
        ", FALSE);
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_qc');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdataheader($idreff, $ipengirim){
        return $this->db->query(
            "SELECT
                to_char(d_keluar_jahit, 'dd-mm-yyyy') as d_document,
                id_jenis_barang_keluar
            FROM 
                tm_keluar_jahit
            WHERE
                id = '$idreff'
                /* AND i_bagian = '$ipengirim'
                AND id_company = '$this->idcompany' */
            ", FALSE);
    }

    public function getdataitem($idreff, $ipengirim)
    {
        return $this->db->query(
            "SELECT DISTINCT
                b.id,
                a.id_product,
                c.i_product_base,
                c.e_product_basename,
                a.n_quantity_product,
                a.n_sisa,
                a.id_color,
                c.i_color, 
                e.e_color_name
            FROM
                tm_keluar_jahit_item a
            JOIN tm_keluar_jahit b
                ON (a.id_keluar_jahit = b.id)
            JOIN tr_product_base c
                ON (a.id_product = c.id)
            JOIN tr_color e
                ON (e.i_color = c.i_color AND c.id_company = e.id_company)
            WHERE
                b.id = '$idreff' 
                AND a.id_keluar_jahit = '$idreff'
                /* AND b.id_company = '$this->idcompany'
                AND b.i_bagian = '$ipengirim' */
                AND a.n_quantity_product <> 0
                AND a.n_sisa <> 0
            ", FALSE);
    }

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_qc');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl, $tahun, $ibagian){
       $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_qc
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBM';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_masuk_qc
            WHERE to_char (d_document, 'yymm') >= '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 3) ILIKE '%$kode%'
            /* AND substring(i_document, 5, 4) = '$thbl' */
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

    function insertheader($id, $ibonm, $datebonm, $ikodemaster, $iasal, $ireff, $eremark, $jref)
    {
        $split = explode('|', $iasal);
        $iasal = $split[0];
        $data = array(
            'id'                 => $id,
            'id_company'         => $this->idcompany,
            'i_document'         => $ibonm,
            'd_document'         => $datebonm,
            'i_bagian'           => $ikodemaster,
            'i_bagian_pengirim'  => $iasal,
            'id_reff'            => $ireff,
            'e_remark'           => $eremark,
            'd_entry'            => current_datetime(),
            'id_jenis_barang_keluar'    => $jref,
        );
        $this->db->insert('tm_masuk_qc', $data);
    }

    function insertdetail($id, $ireff, $ibonm, $idproduct, $idcolor, $nquantity, $nquantitymasuk, $edesc)
    {
        $data = array(
                      'id_company'     => $this->idcompany,
                      'id_document'    => $id,
                      'id_reff'        => $ireff,
                      'id_product'     => $idproduct,
                      'id_color'       => $idcolor,
                      'n_quantity'     => $nquantitymasuk,
                      'n_sisa'         => $nquantitymasuk,
                      'e_remark'       => $edesc,
        );
        $this->db->insert('tm_masuk_qc_item', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_qc a
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
                    $query = $this->db->query("SELECT id_document, id_product, n_quantity, id_reff
                        FROM tm_masuk_qc_item
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
                                    /* -- AND id_company = '$this->id_company' */
                                    AND n_sisa >= '$key->n_quantity'
                            ", FALSE);

                            if($nsisa->num_rows()>0){
                                $this->db->query("UPDATE
                                        tm_keluar_jahit_item
                                    SET
                                        n_quantity_product = $key->n_quantity,
                                        n_sisa = 0
                                    WHERE
                                        id_keluar_jahit = '$key->id_reff'
                                        AND id_product = '$key->id_product'
                                        /* -- AND id_company = '$this->id_company' */
                                        AND n_sisa >= '$key->n_quantity'
                                ", FALSE);
                            }else{
                                die();
                            }
                        }
                    }
                    $data = array(
	                    'i_status'  => $istatus,
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'i_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
                $now = date('Y-m-d');
            	$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_qc');", FALSE);
                }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_qc', $data);
    }

    public function changestatus_20211216($id,$istatus){
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $query = $this->db->query("
                SELECT id_document, id_product, n_quantity, id_reff
                FROM tm_masuk_qc_item
                WHERE id_document = '$id' ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $nsisa =  $this->db->query("
                        SELECT
                            n_sisa
                        FROM
                            tm_keluar_jahit_item                       
                        WHERE
                            id_keluar_jahit = '$key->id_reff'
                            AND id_product = '$key->id_product'
                            AND id_company = '".$this->session->userdata('id_company')."'
                            AND n_sisa >= '$key->n_quantity'
                    ", FALSE);

                    if($nsisa->num_rows()>0){
                        $this->db->query("
                            UPDATE
                                tm_keluar_jahit_item
                            SET
                                n_sisa = n_sisa - $key->n_quantity
                            WHERE
                                id_keluar_jahit = '$key->id_reff'
                                AND id_product = '$key->id_product'
                                AND id_company = '".$this->session->userdata('id_company')."'
                                AND n_sisa >= '$key->n_quantity'
                        ", FALSE);
                    }else{
                        die();
                    }
                }
            }
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $iapprove,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_masuk_qc', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function cek_data($id, $ibagian)
    {
        return $this->db->query(
            "SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.id_reff,
                d.i_keluar_jahit as i_reff,
                to_char(d.d_keluar_jahit, 'dd-mm-yyyy') as d_reff,
                a.i_bagian,
                b.e_bagian_name,
                d.i_bagian||'|'||d.id_company as i_bagian_pengirim,
                c.e_bagian_name as e_bagian_pengirim,
                a.e_remark,
                a.i_status,
                a.id_jenis_barang_keluar 
            FROM
                tm_masuk_qc a 
            INNER JOIN
                tm_keluar_jahit d 
                ON (a.id_reff = d.id) 
            INNER JOIN
                tr_bagian b 
                ON (a.i_bagian = b.i_bagian 
                AND a.id_company = b.id_company) 
            INNER join tr_bagian c ON (a.i_bagian_pengirim = c.i_bagian AND d.id_company_bagian = c.id_company)
            WHERE 
            a.id  = '$id'
            AND a.i_bagian = '$ibagian'
            AND a.id_company = '$this->idcompany'
        ", FALSE);
    }

    public function cek_datadetail($id, $ibagian){
        return $this->db->query(
            "SELECT
                a.id, 
                a.id_document,
                a.id_product,
                c.i_product_base,
                c.e_product_basename,
                a.n_quantity as n_quantity_masuk,
                f.n_quantity_product as n_quantity_jahit,
                f.n_sisa,
                c.i_color,
                a.id_color,
                e.e_color_name,
                a.e_remark,
                g.id_jenis_barang_keluar
            FROM
                tm_masuk_qc_item a 
            INNER JOIN
                tm_masuk_qc b 
                ON (a.id_document = b.id 
                /* AND a.id_company = b.id_company */) 
            INNER JOIN
                tm_keluar_jahit_item f 
                ON (a.id_reff = f.id_keluar_jahit 
                AND f.id_product = a.id_product 
                /* AND a.id_company = f.id_company */) 
            inner join tm_keluar_jahit g on (f.id_keluar_jahit = g.id)
            INNER JOIN
                tr_product_base c 
                ON (a.id_product = c.id 
                /* AND a.id_company = c.id_company */)  
            INNER JOIN
                tr_color e 
                ON (c.i_color = e.i_color 
                AND c.id_company = e.id_company) 
            WHERE 
                a.id_document = '$id'
                AND b.id = '$id'
                /* AND b.i_bagian = '$ibagian'
                AND b.id_company = '$this->idcompany' */
        ", FALSE);
    }

    public function updateheader($id, $ikodemaster, $ibonm, $datebonm, $eremark, $iasal, $ireff, $jref)
    {
        $split = explode('|', $iasal);
        $iasal = $split[0];
        $data = array(
                        'i_document'          => $ibonm,
                        'i_bagian'            => $ikodemaster,
                        'd_document'          => $datebonm,
                        'i_bagian_pengirim'   => $iasal,
                        'id_reff'             => $ireff,
                        'e_remark'            => $eremark,
                        'd_update'            => current_datetime(),
                        'id_jenis_barang_keluar'     => $jref,
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where('i_bagian', $ikodemaster);
        $this->db->update('tm_masuk_qc', $data);
    }

    public function deletedetail($id){
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_qc_item');
    }
    
    // public function updatedetail($id, $idproduct, $nquantity, $idcolor, $edesc)
    // {
    //     $data = array(
    //                   'n_quantity'  => $nquantity,
    //                   'n_sisa'      => $nquantity,
    //                   'e_remark'    => $edesc,
    //     );

    //     $this->db->where('id_document', $id);
    //     $this->db->where('id_product', $idproduct);
    //     $this->db->where('id_color', $idcolor);
    //     $this->db->where('id_company', $this->idcompany);
    //     $this->db->update('tm_masuk_qc_item', $data);
    // }
}
/* End of file Mmaster.php */