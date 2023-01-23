<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    /*----------  DAFTAR DATA MASUK GUDANG JADI SESUAI GUDANG PEMBUAT  ----------*/
    
    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_masuk_unit_packing a
            WHERE
                i_status <> '5'
                AND id_company = '".$this->session->userdata('id_company')."'
                $and
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
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                    DISTINCT 0 AS NO,
                    a.id AS id,
                    a.i_document,
                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                    d.e_bagian_name,
                    a.id_document_reff,
                    CASE
                        WHEN f.i_document ISNULL AND e.i_keluar_qc NOTNULL THEN e.i_keluar_qc
                        WHEN f.i_document NOTNULL AND e.i_keluar_qc ISNULL THEN f.i_document
                        ELSE 'Tanpa Referensi'
                    END AS i_referensi,
                    a.e_remark,
                    e_status_name,
                    label_color,
                    g.i_level,
                    l.e_level_name,
                    a.i_status,
                    '$i_menu' AS i_menu,
                    '$folder' AS folder,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto
                FROM
                    tm_masuk_unit_packing a
                INNER JOIN tr_status_document b ON
                    (b.i_status = a.i_status)
                INNER JOIN tm_masuk_unit_packing_item c ON
                    (c.id_document = a.id)
                INNER JOIN tr_bagian d ON
                    (d.id = a.id_bagian_pengirim)
                LEFT JOIN tm_keluar_qc e ON
                    (e.id = a.id_document_reff
                    AND d.i_bagian = e.i_bagian)
                LEFT JOIN tm_keluar_gudang_jadi f ON
                    (f.id = a.id_document_reff
                    AND a.id_bagian_pengirim = f.id_bagian_tujuan)                  
                LEFT JOIN tr_menu_approve g ON
                    (a.i_approve_urutan = g.n_urut
                    AND g.i_menu = '$i_menu')
                LEFT JOIN public.tr_level l ON
                    (g.i_level = l.i_level)
                WHERE
                    a.i_status <> '5'
                    AND a.id_company = '$this->company'
                    $and
                    $bagian
                ORDER BY
                    a.id
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
            $id         = $data['id'];
            $id_reff    = $data['id_document_reff'];
            $i_status   = trim($data['i_status']);
            $i_level    = $data['i_level'];
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$id_reff\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_document_reff');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        return $datatables->generate();
    }

    /*----------  BACA BAGIAN PEMBUAT  ----------*/    

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('a.f_status', 't');
        $this->db->where('i_level', $this->session->userdata('i_level'));
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

    /*----------  BACA BAGIAN PENGIRIM  ----------*/    

    public function pengirim($i_menu,$ibagian,$cari)
    {        
        $idcompany = $this->session->userdata('id_company');

        // $sql = "SELECT id, i_bagian 
        //             FROM tr_tujuan_menu
        //             WHERE i_menu = '$i_menu' AND id_company = '$idcompany'";

        // $sql = "SELECT tb.id, e_bagian_name AS e_name, c.name AS company_name
        //         FROM tr_bagian tb
        //         LEFT JOIN public.company c ON c.id = tb.id_company
        //         WHERE i_type IN (
        //                         SELECT i_type
        //                         FROM tr_bagian
        //                         WHERE i_bagian IN (
        //                                         SELECT i_bagian
        //                                         FROM tr_tujuan_menu
        //                                         WHERE i_menu = '$i_menu' AND id_company = '$idcompany'
        //                                         )
        //                             AND id_company = '$idcompany'
        //                         )
        //             AND id_company = '$idcompany'
        //             AND e_bagian_name ILIKE '%$cari%'
        //             AND i_bagian <> '$ibagian'
        //         ORDER BY 2";

        $sql = "SELECT DISTINCT tb.id, tb.e_bagian_name AS e_name, c2.name AS company_name
                    FROM tm_keluar_qc tkq
                    LEFT JOIN public.company c ON c.id = tkq.id_company_tujuan
                    LEFT JOIN public.company c2 ON c2.id = tkq.id_company 
                    LEFT JOIN tr_bagian tb ON tb.i_bagian = tkq.i_bagian AND tb.id_company = tkq.id_company 
                    WHERE tkq.id_company_tujuan = '$idcompany' AND tkq.i_tujuan = '$ibagian'";
                
                // var_dump($sql);
                            
        return $this->db->query($sql, FALSE);
    }    

    public function jeniskeluar(){
        return $this->db->get("tr_jenis_barang_keluar");
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_unit_packing 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBM';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_masuk_unit_packing
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
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

    /*----------  CEK NO DOKUMEN  ----------*/
    
    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_unit_packing');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI DATA REFERENSI  ----------*/
    
    public function datareferensi($cari,$ipengirim,$ibagian)
    {
        $idcompany = $this->session->userdata('id_company');

        // $sql = "SELECT id, i_document, id_bagian
        //         FROM (
        //                 SELECT DISTINCT a.id, i_document, a.id_bagian_tujuan AS id_bagian
        //                 FROM tm_keluar_gudang_jadi a
        //                 INNER JOIN tr_bagian b ON (b.id = a.id_bagian_tujuan)
        //                 INNER JOIN tm_keluar_gudang_jadi_item c ON (c.id_document = a.id)
        //                 WHERE a.id_company = '$idcompany'
        //                     AND a.i_status = '6'
        //                     AND c.n_quantity_sisa > 0
        //                 UNION ALL
        //                 SELECT DISTINCT a.id, a.i_keluar_qc AS i_document, c.id AS id_bagian
        //                 FROM tm_keluar_qc a
        //                 INNER JOIN tm_keluar_qc_item b ON (b.id_keluar_qc = a.id)
        //                 INNER JOIN tr_bagian c ON (
        //                                 c.i_bagian = a.i_bagian AND a.id_company = c.id_company
        //                             )
        //                 WHERE a.id_company = '$idcompany'
        //                     AND i_status = '6'
        //                     AND b.n_sisa > 0 
        //                     AND a.i_tujuan = '$ibagian'
        //             ) AS x
        //         WHERE id_bagian = '$ipengirim'
        //         ORDER BY 1";

        $sql = "SELECT id, i_document, id_bagian
                FROM (
                        SELECT DISTINCT a.id, i_document, a.id_bagian_tujuan AS id_bagian
                        FROM tm_keluar_gudang_jadi a
                        INNER JOIN tr_bagian b ON (b.id = a.id_bagian_tujuan)
                        INNER JOIN tm_keluar_gudang_jadi_item c ON (c.id_document = a.id)
                        WHERE a.id_company = '$idcompany'
                        AND a.i_status = '6'
                        AND c.n_quantity_sisa > 0
                    UNION ALL
                    SELECT DISTINCT tkq.id, tkq.i_keluar_qc AS i_document, tb.id AS id_bagian
                    FROM tm_keluar_qc tkq
                    INNER JOIN tm_keluar_qc_item tkqi ON (tkqi.id_keluar_qc = tkq.id)
                    INNER JOIN tr_bagian tb ON (
                                    tb.i_bagian = tkq.i_bagian AND tb.id_company = tkq.id_company
                                )
                    WHERE tkq.id_company_tujuan = '$idcompany'
                        AND tkq.i_tujuan = '$ibagian'
                        AND i_status = '6'
                        AND tkqi.n_sisa > 0 
                    ) AS x
                WHERE id_bagian = '$ipengirim'
                ORDER BY 1";

        // var_dump($sql); die();                                

        return $this->db->query($sql, FALSE);
    }

    /*----------  DETAIL DATA REFERENSI  ----------*/    

    public function detailreferensi($id,$ipengirim,$ibagian)
    {
        $idcompany = $this->session->userdata('id_company');

        $sql = "SELECT *
                FROM (
                    SELECT
                        a.id,
                        a.id_bagian_tujuan AS id_bagian,
                        id_product_base AS id_product,
                        i_product_base AS i_product,
                        e_product_basename AS e_product,
                        e_color_name,
                        n_quantity_sisa
                    FROM tm_keluar_gudang_jadi a
                    INNER JOIN tr_bagian b ON (b.id = a.id_bagian_tujuan)
                    INNER JOIN tm_keluar_gudang_jadi_item c ON (c.id_document = a.id)
                    INNER JOIN tr_product_base d ON (d.id = c.id_product_base)
                    INNER JOIN tr_color e ON (
                                            e.i_color = d.i_color AND d.id_company = e.id_company
                                        )
                    WHERE a.id_company = '$idcompany'
                        AND a.i_status = '6'
                        AND c.n_quantity_sisa > 0

                    UNION ALL

                    SELECT
                        a.id,
                        c.id AS id_bagian,
                        id_product,
                        i_product_base AS i_product,
                        e_product_basename AS e_product,
                        e_color_name,
                        n_sisa AS n_quantity_sisa
                    FROM tm_keluar_qc a
                    INNER JOIN tm_keluar_qc_item b ON (b.id_keluar_qc = a.id)
                    INNER JOIN tr_bagian c ON (
                                                c.i_bagian = a.i_bagian AND a.id_company = c.id_company
                                            )
                    INNER JOIN tr_product_base d ON (d.id = b.id_product)
                    INNER JOIN tr_color e ON (
                                            e.i_color = d.i_color AND d.id_company = e.id_company
                                        )                    
                    WHERE a.id = '$id'
                    ) AS x
                ORDER BY 4 ";

                        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function product($cari){
        return $this->db->query("SELECT
                a.id,
                a.i_product_base,
                a.e_product_basename,
                b.e_color_name
            FROM
                tr_product_base a,
                tr_color b
            WHERE
                a.i_color = b.i_color
                AND b.id_company = a.id_company 
                AND a.id_company = '$this->company'
                AND (a.i_product_base ILIKE '%$cari%'
                    OR a.e_product_basename ILIKE '%$cari%')
            ORDER BY
                a.i_product_base
        ", FALSE);
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/    

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_unit_packing');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$ipengirim,$ijenis,$ireff,$eremark)
    {
        $data = array(
            'id'                 => $id,
            'id_company'         => $this->session->userdata('id_company'),
            'i_document'         => $idocument,
            'd_document'         => $ddocument,
            'i_bagian'           => $ibagian,
            'id_bagian_pengirim' => $ipengirim,
            'id_document_reff'   => $ireff,
            'e_remark'           => $eremark,
            'd_entry'            => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
        );
        $this->db->insert('tm_masuk_unit_packing', $data);
    }

    public function simpandetail($id,$ireff,$idproduct,$nquantity,$nquantityreff,$eremark)
    {
        $data = array(
            'id_company'         => $this->session->userdata('id_company'),
            'id_document'        => $id,
            'id_document_reff'   => $ireff,
            'id_product_base'    => $idproduct,
            'n_quantity'         => $nquantity,
            'n_quantity_sisa'    => $nquantity,
            'n_quantity_reff'    => $nquantityreff,
            'e_remark'           => $eremark,
        );
        $this->db->insert('tm_masuk_unit_packing_item', $data);
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/
    
    public function dataedit($id)
    {
        return $this->db->query("SELECT
                a.id,
                a.i_document,
                a.i_bagian,
                a.id_bagian_pengirim,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                id_document_reff,
                /* CASE
                    WHEN f.i_document ISNULL THEN e.i_keluar_qc
                    ELSE f.i_document
                END AS i_referensi, */
                CASE
                    WHEN f.i_document ISNULL AND e.i_keluar_qc NOTNULL THEN e.i_keluar_qc
                    WHEN f.i_document NOTNULL AND e.i_keluar_qc ISNULL THEN f.i_document
                    ELSE 'Tanpa Referensi'
                END AS i_referensi,
                b.e_bagian_name,
                d.e_bagian_name AS e_bagian_pengirim,
                a.e_remark,
                a.i_status,
                a.id_jenis_barang_keluar
            FROM
                tm_masuk_unit_packing a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_bagian d ON
                (d.id = a.id_bagian_pengirim)
            LEFT JOIN tm_keluar_qc e ON
                (e.id = a.id_document_reff
                AND d.i_bagian = e.i_bagian)
            LEFT JOIN tm_keluar_gudang_jadi f ON
                (f.id = a.id_document_reff
                AND a.id_bagian_pengirim = f.id_bagian_tujuan)
            WHERE
                a.id = '$id'",FALSE
        );
        return $this->db->get();
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("SELECT
                i_product_base AS i_product,
                a.id_product_base AS id_product,
                e_product_basename AS e_product,
                e_color_name,
                a.e_remark,
                a.n_quantity,
                CASE
                    WHEN n_sisa ISNULL THEN h.n_quantity_sisa
                    ELSE n_sisa
                END AS n_quantity_sisa,
                a.n_quantity_reff
            FROM
                tm_masuk_unit_packing_item a
            INNER JOIN tm_masuk_unit_packing b ON
                (b.id = a.id_document)
            INNER JOIN tr_product_base c ON
                (c.id = a.id_product_base)
            INNER JOIN tr_color d ON
                (d.i_color = c.i_color
                AND c.id_company = d.id_company)
            INNER JOIN tr_bagian i ON
                (i.id = b.id_bagian_pengirim)
            LEFT JOIN tm_keluar_qc e ON
                (e.id = b.id_document_reff
                AND i.i_bagian = e.i_bagian)
            LEFT JOIN tm_keluar_gudang_jadi f ON
                (f.id = b.id_document_reff
                AND b.id_bagian_pengirim = f.id_bagian_tujuan)
            LEFT JOIN tm_keluar_qc_item g ON
                (g.id_keluar_qc = e.id
                AND a.id_product_base = g.id_product)
            LEFT JOIN tm_keluar_gudang_jadi_item h ON
                (h.id_document = f.id
                AND a.id_product_base = h.id_product_base)
            WHERE
                a.id_document = '$id'
            ORDER BY
                1
            ",
            FALSE
        );
    }

    /*----------  UPDATE DATA  ----------*/   

    public function update($id,$idocument,$ddocument,$ibagian,$ipengirim,$ijenis,$ireff,$eremark)
    {
        $data = array(
            'id_company'         => $this->session->userdata('id_company'),
            'i_document'         => $idocument,
            'd_document'         => $ddocument,
            'i_bagian'           => $ibagian,
            'id_bagian_pengirim' => $ipengirim,
            'id_document_reff'   => $ireff,
            'e_remark'           => $eremark,
            'd_update'           => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
        );
        $this->db->where('id',$id);
        $this->db->update('tm_masuk_unit_packing', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/    

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_unit_packing_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_unit_packing a
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
                    $this->updatesisa($id);
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_unit_packing');", FALSE);
                }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_unit_packing', $data);
    }
    /* public function changestatus($id,$istatus)
    {
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
        $this->db->update('tm_masuk_unit_packing', $data);
    } */

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {
        /*----------  Cek ada diqc  ----------*/
        
        $cekada = $this->db->query("SELECT
                id_document_reff
            FROM
                tm_masuk_unit_packing a
            INNER JOIN tr_bagian b ON
                (b.id = a.id_bagian_pengirim)
            INNER JOIN tm_keluar_qc c ON
                (c.id = a.id_document_reff
                AND b.i_bagian = c.i_bagian)
            WHERE
                a.id = $id
        ", FALSE);

        if ($cekada->num_rows()>0) {
            $idreff = $cekada->row()->id_document_reff;
        }else{
            $idreff = 0;
        }

        if ($idreff!=0) {                
                $query = $this->db->query("SELECT 
                    id_document_reff,
                    id_product_base,
                    n_quantity
                FROM 
                    tm_masuk_unit_packing_item
                WHERE id_document = $id
            ", FALSE);
            
            /*----------  Jika Ada Di qc Update qc, Jika Tidak Ada Update Di Gudang Lain  ----------*/
            
            if ($cekada->num_rows()>0) {
                if ($query->num_rows()>0) {
                    foreach ($query->result() as $key) {
                        
                        /*----------  Cek Sisa Di qc Tidak Kurang Dari Pemenuhan  ----------*/
                        
                        $ceksisa1 = $this->db->query("SELECT 
                                n_sisa
                                FROM 
                                tm_keluar_qc_item
                                WHERE 
                                id_keluar_qc = $key->id_document_reff
                                AND id_product = $key->id_product_base
                                AND n_sisa >= $key->n_quantity
                                ", FALSE);
                        if ($ceksisa1->num_rows()>0) {
                            
                            /*----------  Update Sisa Di qc  ----------*/
                            
                            $this->db->query("UPDATE 
                                tm_keluar_qc_item
                            SET 
                                n_sisa = n_sisa - $key->n_quantity
                            WHERE 
                            id_keluar_qc = $key->id_document_reff
                            AND id_product = $key->id_product_base
                            AND n_sisa >= $key->n_quantity
                            ", FALSE);
                        }else{
                            die();
                        }
                    }
                }
            }else{
                if ($query->num_rows()>0) {
                    foreach ($query->result() as $key) {

                        /*----------  Cek Sisa Di Gudang Lain Tidak Kurang Dari Pemenuhan  ----------*/
                        
                        $ceksisa2 = $this->db->query("SELECT 
                            n_quantity_sisa
                        FROM 
                            tm_keluar_gudang_jadi_item
                        WHERE 
                            id_document = $key->id_document_reff
                            AND id_product_base = $key->id_product_base
                            AND n_quantity_sisa >= $key->n_quantity
                            ", FALSE);
                            if ($ceksisa2->num_rows()>0) {
                                    
                                    /*----------  Update Sisa Di Gudang Lain  ----------*/
                                    
                                $this->db->query("UPDATE 
                                        tm_keluar_gudang_jadi_item
                                    SET 
                                        n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                                    WHERE 
                                        id_document = $key->id_document_reff
                                        AND id_product_base = $key->id_product_base
                                        AND n_quantity_sisa >= $key->n_quantity
                                    ", FALSE);
                            }else{
                                die();
                        }
                    }
                }
            }
        }
    }

    public function idbagian($ibagian)
    {
        return $this->db->query("SELECT 
            id
        FROM 
            tr_bagian
        WHERE 
            i_bagian = '$ibagian'
            AND id_company = '$this->company'
        ", FALSE);
    }
    
}
/* End of file Mmaster.php */