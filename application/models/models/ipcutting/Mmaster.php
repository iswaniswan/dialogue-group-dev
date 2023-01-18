<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public $idcompany;
    public $i_menu = '2090101';

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


    public function referensi($cari)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT
                DISTINCT a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                to_char(to_date(a.i_periode, 'yyyymm'), 'FMMonth yyyy') AS i_periode
            FROM
                tm_fccutting a
            WHERE
                a.i_status = '6'
                AND a.id_company = '4'
                AND a.i_document ILIKE '%$cari%'
                /* AND a.id NOT IN (
                SELECT id_referensi FROM tm_ipcutting WHERE i_status IN ('1', '2', '3', '6') AND id_referensi IS NOT NULL) */
            ORDER BY
                i_document,
                d_document
		      ", FALSE);
    }

    public function getdataitem($idreff)
    {
        return $this->db->query("SELECT
                DISTINCT 
                a.id,
                a.id_product_wip,
                d.id AS id_material,
                c.i_product_wip,
                c.e_product_wipname,
                a.n_fc_cutting AS n_quantity_wip,
                a.n_fc_cutting AS n_qty_wip,
                a.n_fc_cutting AS n_quantity_wip_sisa,
                c.i_color,
                e.id AS id_color,
                e.e_color_name,
                d.i_material,
                d.e_material_name,
                0 AS n_quantity,
                0 AS n_quantity_sisa,
                a.e_remark,
                g.e_nama_group_barang,
                h.e_nama_kelompok,
                b.e_bagian,
                b.v_gelar, 
                b.v_gelar AS n_gelar, 
                b.v_set AS n_set,
                b.id_bisbisan,
                b.v_bisbisan,
                f.v_panjang_bis,
                CASE WHEN b.id_bisbisan NOTNULL THEN b.v_bisbisan ELSE a.n_fc_cutting / b.v_set END AS n_panjang_gelar,
                CASE WHEN b.id_bisbisan NOTNULL THEN ((1 / b.v_set) * b.v_gelar) + (b.v_bisbisan / f.v_panjang_bis) ELSE ((1 / b.v_set) * b.v_gelar) END AS n_panjang_kain,
                TRUE AS f_auto_cutter,
                b.f_badan,
                b.f_print,
                b.f_bordir,
                b.f_quilting
            FROM
                tm_fccutting_item_new a
            INNER JOIN tr_product_wip c ON
                (a.id_product_wip = c.id
                    AND a.id_company = c.id_company)
            INNER JOIN tr_polacutting_new b ON
                (b.id_product_wip = a.id_product_wip AND b.f_cutting = 't' AND b.id_bisbisan ISNULL)
            INNER JOIN tr_material d ON
                (b.id_material = d.id
                    AND b.id_company = d.id_company)
            INNER JOIN tr_color e ON
                (c.i_color = e.i_color AND c.id_company = e.id_company)
            LEFT JOIN tr_material_bisbisan f ON 
                (f.id_material = b.id_material)
            LEFT JOIN tr_group_barang g ON 
                (g.i_kode_group_barang = c.i_kode_group_barang)
            LEFT JOIN tr_kelompok_barang h ON 
                (h.i_kode_kelompok = c.i_kode_kelompok AND h.i_kode_group_barang = g.i_kode_group_barang)
            WHERE
                a.id_forecast = '$idreff'
                AND a.id_company = '$this->id_company'
                AND a.n_fc_cutting > 0
            ORDER BY
                a.id_product_wip
            /* LIMIT 100 */
        ", FALSE);
    }

    public function getdataheader($idreff)
    {
        return $this->db->query("
	                          SELECT
	                              to_char(d_document, 'dd-mm-yyyy') as d_document
	                          FROM 
	                              tm_fccutting
	                          WHERE
	                              id = '$idreff'
	                          ", FALSE);
    }

    public function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                a.i_bagian,
                b.e_bagian_name,
                a.id_referensi,
                d.i_document as i_fc,
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
                tm_ipcutting a
            INNER JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            LEFT JOIN tm_fccutting d ON (a.id_referensi = d.id AND a.id_company = d.id_company)
            INNER JOIN tr_status_document e ON (a.i_status = e.i_status)
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE 
                a.id_company = '$this->idcompany'
                AND a.i_status <> '5'
                $where
            ORDER BY 
                a.i_document,
                a.d_document desc
                        ", FALSE);
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
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

            if(check_role($i_menu, 6)){
                $data     .= "<a href=\"".base_url($folder.'/cform/export_excel/'.$id.'/'.$dfrom.'/'.$dto)."\" title='Export'><i class='ti-download text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status != '4' && $i_status != '6' && $i_status != '9' && $i_status != '2')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"5\"); return false;'><i class='ti-close text-danger'></i></a>";
            }


            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('i_bagian');
        $datatables->hide('e_bagian_name');
        $datatables->hide('id_referensi');
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
        $this->db->from('tm_ipcutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_ipcutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_ipcutting');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_ipcutting 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'IP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
            tm_ipcutting
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
            AND id_company = '" . $this->session->userdata("id_company") . "'
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    function insertheader($id, $ibonm, $dbonm, $ikodemaster, $ireff, $eremark)
    {

        $data = array(
            'id'                 => $id,
            'id_company'         => $this->idcompany,
            'i_document'         => $ibonm,
            'd_document'         => $dbonm,
            'i_bagian'           => $ikodemaster,
            'id_referensi'       => $ireff,
            'e_remark'           => $eremark,
            'i_status'           => '1',
            'd_entry'            => current_datetime(),
        );
        $this->db->insert('tm_ipcutting', $data);
    }

    function insertdetail($id, $ireff, $id_product_wip, $id_material, $id_fccutting_item, $e_bagian, $n_gelar, $n_set, $n_panjang_gelar, $n_panjang_kain, $f_auto_cutter, $edesc, $n_qty_wip)
    {

        $data = array(
            'id_company' => $this->id_company,
            'id_document' => $id,
            'id_fccutting_item' => $id_fccutting_item,
            'id_product_wip' => $id_product_wip,
            'id_material' => $id_material,
            'e_bagian' => $e_bagian,
            'n_gelar' => $n_gelar,
            'n_set' => $n_set,
            'n_panjang_gelar' => $n_panjang_gelar,
            'n_panjang_kain' => $n_panjang_kain,
            'f_auto_cutter' => $f_auto_cutter,
            /* 'f_badan' => $f_badan,
            'f_print' => $f_print,
            'f_bordir' => $f_bordir,
            'f_quilting' => $f_quilting, */
            'e_remark' => $edesc,
            'n_qty_wip' => $n_qty_wip,
        );
        $this->db->insert('tm_ipcutting_item', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_ipcutting a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2", FALSE)->row();
            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0) {
                    $data = array(
                        'i_status'  => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_schedule');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_ipcutting', $data);
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
        return $this->db->query("SELECT 
                a.id,
                a.i_document, 
                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                a.id_referensi as id_reff,
                d.i_document as i_reff,
                to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
                a.i_bagian,
                b.e_bagian_name,
                a.e_remark,
                a.i_status
            FROM tm_ipcutting a
            LEFT JOIN tm_fccutting d ON (a.id_referensi = d.id AND a.id_company = d.id_company)
            INNER JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            WHERE a.id  = '$id'
            AND a.i_bagian = '$ibagian'
            AND a.id_company = '$this->idcompany'
            ", FALSE);
    }

    public function cek_datadetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                c.i_product_wip,
                c.e_product_wipname,
                c.i_color,
                e.id AS id_color,
                e.e_color_name,
                d.i_material,
                d.e_material_name,
                a.e_remark,
                f.n_fc_cutting AS n_quantity_wip_sisa
            FROM
                tm_ipcutting_item a
            INNER JOIN tr_product_wip c ON
                (a.id_product_wip = c.id
                    AND a.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (a.id_material = d.id
                    AND a.id_company = d.id_company)
            INNER JOIN tr_color e ON
                (c.i_color = e.i_color
                    AND c.id_company = e.id_company)
            LEFT JOIN tm_fccutting_item_new f ON
                (f.id = a.id_fccutting_item)
            WHERE
                a.id_document = '$id'
                AND a.id_company = '$this->idcompany'
            ORDER BY c.i_product_wip, a.id ", FALSE);
    }

    public function updateheader($id, $ikodemaster, $ibonm, $dbonm, $eremark, $ireff)
    {
        $data = array(
            'i_document'    => $ibonm,
            'i_bagian'      => $ikodemaster,
            'd_document'    => $dbonm,
            'id_referensi'  => $ireff,
            'e_remark'      => $eremark,
            'd_update'      => current_datetime(),
            'i_status'      => '1',
        );

        $this->db->where('id', $id);
        $this->db->update('tm_ipcutting', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_ipcutting_item');
    }
}
/* End of file Mmaster.php */