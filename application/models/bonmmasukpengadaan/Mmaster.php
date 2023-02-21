<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public $idcompany;
    public $i_menu = '2090301';

    function __construct()
    {
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

    public function bagianpembuat()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
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

    public function bagianpengirim($cari,$ibagian)
    {
        $cari = str_replace("'", "", $cari);

        $sql = "SELECT DISTINCT
                    a.i_bagian,
                    b.e_bagian_name
                FROM tm_keluar_qcset a
                JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                WHERE b.id_company = '$this->idcompany'
                    AND a.i_tujuan = '$ibagian'
                    AND a.i_bagian ILIKE '%$cari%'
                    AND b.e_bagian_name ILIKE '%$cari%'";

        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function referensi($cari, $iasal)
    {
        $cari = str_replace("'", "", $cari);

        $sql = "SELECT DISTINCT a.id,
                                a.i_keluar_qcset as i_document,
                                to_char(a.d_keluar_qcset, 'dd-mm-yyyy') AS d_document,
                                c.e_jenis_name
                            FROM tm_keluar_qcset a
                                LEFT JOIN tm_keluar_qcset_item b ON (a.id = b.id_keluar_qcset AND a.id_company = b.id_company)
                                LEFT JOIN tr_jenis_barang_keluar c ON (a.id_jenis_barang_keluar = c.id)
                            WHERE a.id NOT IN (SELECT id_reff FROM tm_masuk_pengadaan WHERE i_status NOT IN ('4','5','9'))
                                AND a.i_bagian = '$iasal'
                                AND a.i_status = '6'
                                AND a.id_company = '$this->idcompany'
                                AND b.n_quantity_product_wip <> 0
                                AND b.n_quantity_penyusun <> 0
                                AND b.n_quantity_akhir <> 0
                                AND a.i_keluar_qcset ILIKE '%$cari%'";

        // var_dump($sql); die();

        return $this->db->query($sql, FALSE);
    }

    public function data($i_menu, $i_menu1, $folder,$folder1, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }

        $cek1 = $this->db->query("SELECT
                i_bagian
            FROM
                tm_masuk_pengadaan a
            WHERE
                i_status <> '5'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$this->idcompany')

        ", FALSE);
        if ($this->i_departement=='1') {
            $bagian1 = "";
        }else{
            if ($cek1->num_rows()>0) {                
                $i_bagian1 = $cek1->row()->i_bagian;
                $bagian1 = "AND a.i_bagian = '$i_bagian1' ";
            }else{
                $bagian1 = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$this->idcompany')";
            }
        }

        $cek2 = $this->db->query("SELECT
                i_bagian
            FROM
                tm_masuk_pengadaan_fgudang a
            WHERE
                i_status <> '5'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$this->idcompany')

        ", FALSE);
        if ($this->i_departement=='1') {
            $bagian2 = "";
        }else{
            if ($cek2->num_rows()>0) {                
                $i_bagian2 = $cek2->row()->i_bagian;
                $bagian2 = "AND a.i_bagian = '$i_bagian2' ";
            }else{
                $bagian2 = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$this->idcompany')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                a.i_bagian,
                b.e_bagian_name,
                a.i_bagian_pengirim,
                c.e_bagian_name as e_bagian_pengirim,
                d.i_keluar_qcset as i_referensi,
                a.e_remark, 
                a.i_status,
                e.e_status_name,
                e.label_color,
                f.i_level,
                l.e_level_name,
                '$i_menu' as i_menu, 
                '$folder' as folder,
                '$dfrom' as dfrom,
                '$dto' as dto
            FROM 
                tm_masuk_pengadaan a
            INNER JOIN tr_bagian b
                ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_bagian c
                ON (a.i_bagian_pengirim = c.i_bagian AND a.id_company = c.id_company)
            LEFT JOIN tm_keluar_qcset d
                ON (a.id_reff = d.id AND a.id_company = d.id_company)
            INNER JOIN tr_status_document e
                ON (a.i_status = e.i_status)
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE 
                a.id_company = '$this->idcompany'
                AND a.i_status <> '5'
                $where
                $bagian1
            UNION ALL
            SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_bagian,
                e.e_bagian_name,
                a.i_bagian_pengirim,
                d.e_bagian_name as e_bagian_pengirim,
                b.i_document as i_referensi,
                a.e_remark, 
                a.i_status,
                c.e_status_name,
                c.label_color, 
                f.i_level,
                l.e_level_name,
                '$i_menu1' as i_menu,
                '$folder1' as folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_masuk_pengadaan_fgudang a   
            JOIN
                tm_keluar_produksiak b
                ON b.id = a.id_reff AND a.id_company = b.id_company                                    
            JOIN
                tr_status_document c 
                ON (c.i_status = a.i_status) 
            JOIN
                tr_bagian d 
                ON (a.i_bagian_pengirim = d.i_bagian AND a.id_company = d.id_company) 
            JOIN
                tr_bagian e 
                ON (a.i_bagian = e.i_bagian AND a.id_company = e.id_company) 
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE
                a.id_company = '$this->idcompany' 
                AND a.i_status <> '5'
                $where
                $bagian2
            ", FALSE);
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $ibagian        = trim($data['i_bagian']);
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $i_status       = trim($data['i_status']);           
            $i_level = $data['i_level'];
            $data           = '';

            /* if (check_role($i_menu, 2)) { */
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            /* } */
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status != '4' && $i_status != '6' && $i_status != '9' && $i_status != '2')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close text-danger'></i></a>";
            }


            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('i_bagian');
        $datatables->hide('e_bagian_name');
        $datatables->hide('i_bagian_pengirim');
        $datatables->hide('i_status');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_pengadaan');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_pengadaan');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdataheader($idreff, $ipengirim)
    {
        return $this->db->query("
                                SELECT
                                    to_char(d_keluar_qcset, 'dd-mm-yyyy') as d_document,
                                    id_jenis_barang_keluar
                                FROM 
                                    tm_keluar_qcset
                                WHERE
                                    id = '$idreff'
                                    AND i_bagian = '$ipengirim'
                                    AND id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function getdataitem($idreff, $ipengirim)
    {
        return $this->db->query("
                                SELECT DISTINCT 
                                    a.id_keluar_qcset as id ,
                                    a.id_product_wip,
                                    c.i_product_wip,
                                    c.e_product_wipname,
                                    a.n_quantity_product_wip as n_quantity_wip,
                                    c.i_color, 
                                    e.id as id_color,
                                    e.e_color_name
                                FROM
                                    tm_keluar_qcset_item a
                                    LEFT JOIN tm_keluar_qcset b
                                        ON (a.id_keluar_qcset = b.id AND a.id_company = b.id_company)
                                    INNER JOIN tr_product_wip c
                                        ON (a.id_product_wip = c.id AND a.id_company = c.id_company)
                                    INNER JOIN tr_color e
                                        ON (c.i_color = e.i_color AND c.id_company = e.id_company)
                                WHERE
                                	b.id = '$idreff' 
                                	AND a.id_keluar_qcset = '$idreff'
                                	AND b.id_company = '$this->idcompany'
                                    AND b.i_bagian = '$ipengirim'
                                    AND a.n_quantity_penyusun <> 0
                                    AND a.n_quantity_akhir <> 0
                                GROUP BY
                                    1,2,3,4,5,6,7,8
                                ", FALSE);
    }

    public function datavalidasi($idreff, $ipengirim)
    {
        return $query = "
        SELECT id 
        FROM produksi.tm_keluar_qcset 
        WHERE id NOT IN (SELECT id_reff FROM produksi.tm_masuk_pengadaan WHERE i_status NOT IN ('4','5','9'))
        AND id = $idreff AND i_bagian = '$ipengirim'";
    }


    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_pengadaan');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_pengadaan 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BBM';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
              tm_masuk_pengadaan
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 4) = substring('$thbl',1,4)
            AND id_company = '" . $this->session->userdata("id_company") . "'
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 4) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "0001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    function insertheader($id, $ibonm, $dbonm, $ikodemaster, $iasal, $ireff, $eremark)
    {

        $data = array(
            'id'                 => $id,
            'id_company'         => $this->idcompany,
            'i_document'         => $ibonm,
            'd_document'         => $dbonm,
            'i_bagian'           => $ikodemaster,
            'i_bagian_pengirim'  => $iasal,
            'id_reff'            => $ireff,
            'e_remark'           => $eremark,
            'd_entry'            => current_datetime(),
        );
        $this->db->insert('tm_masuk_pengadaan', $data);
    }

    function insertdetail($id, $idreff, $idproduct, $nquantitywip, $nquantityterima, $edesc)
    {
        $data = array(
            'id_company'                => $this->idcompany,
            'id_document'               => $id,
            'id_reff'                   => $idreff,
            'id_product_wip'            => $idproduct,
            'n_quantity_wip'            => $nquantitywip,
            'n_quantity_terima'            => $nquantityterima,
            'e_remark'                  => $edesc,
        );
        $this->db->insert('tm_masuk_pengadaan_item', $data);
    }

    public function changestatus_20211210($id, $istatus)
    {
        $dreceive = '';
        $dreceive = date('Y-m-d');
        $iapprove = $this->session->userdata('username');
        if ($istatus == '6') {
            $query = $this->db->query("
                                        SELECT 
                                          a.id_reff, 
                                          a.id_product_wip, 
                                          a.n_quantity_wip, 
                                          b.i_bagian_pengirim
                                        FROM 
                                          tm_masuk_pengadaan_item a
                                          LEFT JOIN tm_masuk_pengadaan b
                                            ON (a.id_document = b.id AND a.id_company = b.id_company)
                                        WHERE 
                                          a.id_document = '$id' 
                                        ", FALSE);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                        $this->db->query("
                                          UPDATE
                                              tm_keluar_qcset_item
                                          SET
                                              n_quantity_product_wip = $key->n_quantity_wip,
                                              n_quantity_akhir = $key->n_quantity * n_quantity_penyusun
                                          WHERE
                                              id_keluar_qcset = '$key->id_reff'
                                              AND id_product_wip = '$key->id_product_wip'
                                              AND id_company = '" . $this->session->userdata('id_company') . "'
                                          ", FALSE);

                        $this->db->query("
                                            UPDATE
                                                tm_keluar_qcset
                                            SET
                                                f_receive_pengadaan = 't',
                                                d_receive_pengadaan = NOW()
                                            WHERE
                                                id = '$key->id_reff'
                                                AND i_bagian = '$key->i_bagian_pengirim'
                                                AND id_company = '" . $this->session->userdata('id_company') . "'
                                        ", FALSE); 
                }
            }
            else {
                die();
            }
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $iapprove,
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_masuk_pengadaan', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_pengadaan a
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
                    $query = $this->db->query("SELECT 
                        a.id_reff, 
                        a.id_product_wip, 
                        a.n_quantity_terima,
                        b.i_bagian_pengirim
                    FROM 
                        tm_masuk_pengadaan_item a
                        LEFT JOIN tm_masuk_pengadaan b
                        ON (a.id_document = b.id AND a.id_company = b.id_company)
                    WHERE 
                        a.id_document = '$id'", FALSE);
                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $key) {
                            $this->db->query("UPDATE
                                    tm_keluar_qcset_item
                                SET
                                    n_quantity_product_wip = $key->n_quantity_terima,
                                    n_quantity_akhir = $key->n_quantity_terima * n_quantity_penyusun
                                WHERE
                                    id_keluar_qcset = '$key->id_reff'
                                    AND id_product_wip = '$key->id_product_wip'
                                    AND id_company = '" . $this->session->userdata('id_company') . "'
                                ", FALSE);
                            $this->db->query("UPDATE
                                    tm_keluar_qcset
                                SET
                                    f_receive_pengadaan = 't',
                                    d_receive_pengadaan = NOW()
                                WHERE
                                    id = '$key->id_reff'
                                    AND i_bagian = '$key->i_bagian_pengirim'
                                    AND id_company = '" . $this->session->userdata('id_company') . "'
                            ", FALSE);
                    }
                    
                }
                else {
                    die();
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_pengadaan');", FALSE);
                }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_pengadaan', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function cek_data($id, $ibagian)
    {
        return $this->db->query("
                                SELECT 
                                	a.id,
                                	a.i_document, 
                                	to_char(a.d_document,'dd-mm-yyyy') as d_document,
                                	a.id_reff,
                                	d.i_keluar_qcset as i_reff,
                                	to_char(d.d_keluar_qcset, 'dd-mm-yyyy') as d_reff,
                                	a.i_bagian,
                                	b.e_bagian_name,
                                	a.i_bagian_pengirim,
                                    c.e_bagian_name as e_bagian_pengirim,
                                    a.e_remark,
                                    a.i_status,
                                    d.id_jenis_barang_keluar,
                                    e.e_jenis_name
                                FROM
                                	tm_masuk_pengadaan a
                                	LEFT JOIN tm_keluar_qcset d
                                		ON (a.id_reff = d.id AND a.id_company = d.id_company)
                                	INNER JOIN tr_bagian b
                                		ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                                	INNER JOIN tr_bagian c
                                		ON (a.i_bagian_pengirim = c.i_bagian AND a.id_company = b.id_company)
                                    LEFT JOIN tr_jenis_barang_keluar e
                                        ON (d.id_jenis_barang_keluar = e.id)
                                WHERE 
                                	a.id  = '$id'
                                	AND a.i_bagian = '$ibagian'
                                	AND a.id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function cek_datadetail($id, $ibagian)
    {
        return $this->db->query("
                                SELECT
                                	a.id, 
                                	a.id_document,
                                    a.id_reff,
                                    a.id_product_wip,
                                    c.i_product_wip,
                                	c.e_product_wipname,
                                	a.n_quantity_wip,
                                    a.n_quantity_terima,
                                    c.i_color,
                                    e.id as id_color,
                                    e.e_color_name,
                                	a.e_remark
                                FROM
                                	tm_masuk_pengadaan_item a 
                                	LEFT JOIN 
                                		tm_masuk_pengadaan b
                                		ON (a.id_document = b.id AND a.id_company = b.id_company)
                                	LEFT JOIN 
                                		tm_keluar_qcset_item f
                                		ON (a.id_reff = f.id_keluar_qcset AND a.id_company = f.id_company AND a.id_product_wip = f.id_product_wip)
                                	INNER JOIN 
                                		tr_product_wip c
                                		ON (a.id_product_wip = c.id AND a.id_company = c.id_company)
                                	INNER JOIN 
                                		tr_color e
                                		ON (c.i_color = e.i_color AND c.id_company = e.id_company)
                                WHERE 
                                	a.id_document = '$id'
                                	AND b.id = '$id'
                                	AND b.i_bagian = '$ibagian'
                                  AND b.id_company = '$this->idcompany'
                                GROUP BY
                                    1,2,3,4,5,6,7,8,9,10
                                ", FALSE);
    }

    public function updateheader($id, $ikodemaster, $ibonm, $dbonm, $eremark, $ireff)
    {
        $data = array(
            'i_document' => $ibonm,
            'i_bagian'   => $ikodemaster,
            'd_document' => $dbonm,
            'id_reff'    => $ireff,
            'e_remark'   => $eremark,
            'd_update'   => current_datetime(),
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where('i_bagian', $ikodemaster);
        $this->db->update('tm_masuk_pengadaan', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->delete('tm_masuk_pengadaan_item');
    }

    // public function updatedetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc)
    // {
    //     $data = array(
    //         'n_quantity_wip'      => $nquantitywipmasuk,
    //         'n_quantity_wip_sisa' => $nquantitywipmasuk,
    //         'n_quantity'          => $nquantitybahanmasuk,
    //         'n_quantity_sisa'     => $nquantitybahanmasuk,
    //         'e_remark'            => $edesc,
    //     );

    //     $this->db->where('id_document', $id);
    //     $this->db->where('id_product_wip', $idproductwip);
    //     $this->db->where('id_material', $idmaterial);
    //     $this->db->where('id_company', $this->idcompany);
    //     $this->db->update('tm_masuk_pengadaan_item', $data);
    // }
}
/* End of file Mmaster.php */