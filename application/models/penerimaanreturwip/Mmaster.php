<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public $idcompany;
    public $i_menu = '2090407';

    function __construct()
    {
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany = $this->session->id_company;
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_masuk_retur_jahit a
            WHERE
                i_status <> '5'
                AND id_company = '" . $this->session->userdata('id_company') . "'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND username = '" . $this->session->userdata('username') . "')

        ", FALSE);
        if ($this->session->userdata('i_departement') == '4' || $this->session->userdata('i_departement') == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND username = '" . $this->session->userdata('username') . "')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian_pengirim,
                a.i_bagian,
                b.e_bagian_name||' - '||cc.name AS e_bagian_name,
                a.id_reff,
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
            FROM
                tm_masuk_retur_jahit a
            INNER JOIN tm_retur_keluar_wip c ON (a.id_reff = c.id)
            INNER JOIN tr_bagian b ON (
                b.i_bagian = c.i_bagian AND c.id_company = b.id_company
            )
            INNER JOIN public.company cc ON (cc.id = b.id_company)
            INNER JOIN tr_status_document d ON (a.i_status = d.i_status)                    
            LEFT JOIN tr_menu_approve f ON (
                a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu'
            )
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE
                a.i_status <> '5' AND a.id_company = '$idcompany'
            $where
            $bagian
            ORDER BY 
                a.i_document,
                a.d_document
            ",
            FALSE
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
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
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success mr-3'></i></a>";
            }
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-3'></i></a>";
                }
            }
            // if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9' && $i_status!='2')) {
            //     $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close text-danger'></i></a>";
            // }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_bagian_pengirim');
        $datatables->hide('id_reff');
        $datatables->hide('id');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function bagianpembuat()
    {
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

    public function bagianpengirim($cari, $ibagian)
    {
        /* return $this->db->query(
            "SELECT
                a.i_bagian,
                b.e_bagian_name
            FROM
                tr_tujuan_menu a
                JOIN tr_bagian b 
                 ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            WHERE
                b.id_company = '$this->idcompany'
                AND a.i_menu = '$this->i_menu'
                AND a.i_bagian ILIKE '%$cari%'
                AND b.e_bagian_name ILIKE '%$cari%'
            ORDER BY
                b.e_bagian_name
        ", FALSE); */
        return $this->db->query(
            "SELECT DISTINCT a.id, a.i_bagian, a.e_bagian_name||' - '||d.name as e_bagian_name 
            FROM tr_bagian a
            INNER JOIN tm_retur_keluar_wip b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tm_retur_keluar_wip_item c ON (c.id_document = b.id)
            INNER JOIN public.company d ON (d.id = a.id_company)
            WHERE b.i_tujuan = '$ibagian' AND b.id_bagian_company = '$this->idcompany'
            AND a.e_bagian_name ILIKE '%$cari%' AND c.n_sisa > 0 AND b.i_status = '6'
            ORDER BY e_bagian_name ASC;"
        );
    }

    public function referensi($cari, $iasal, $ibagian)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query(
            "SELECT DISTINCT
                a.id,
                a.i_document as i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_retur_keluar_wip a
            INNER JOIN tm_retur_keluar_wip_item b
                on (a.id = b.id_document AND a.id_company = b.id_company)
            WHERE
                a.i_bagian = '$iasal'
                AND a.i_tujuan = '$ibagian'
                AND a.id_bagian_company = '$this->idcompany'
                AND b.n_quantity <> 0
                AND b.n_sisa <> 0
                AND a.i_document ILIKE '%$cari%'
                /* AND a.i_status IN ('1', '2', '3', '6') */
                AND a.i_status IN ('6')
            ORDER BY
                i_document,
                d_document
        ",
            FALSE
        );
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_retur_jahit');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdataheader($idreff, $ipengirim)
    {
        return $this->db->query(
            "SELECT to_char(d_document, 'dd-mm-yyyy') as d_document
            FROM tm_retur_keluar_wip
            WHERE id = '$idreff' /* AND i_bagian = '$ipengirim' AND id_company = '$this->idcompany' */
            ",
            FALSE
        );
    }

    public function getdataitem($idreff, $ipengirim)
    {
        return $this->db->query(
            "SELECT DISTINCT
                b.id,
                a.id_product,
                c.i_product_base,
                c.e_product_basename,
                a.n_quantity as n_quantity_product,
                a.n_sisa,
                e.id as id_color,
                c.i_color, 
                e.e_color_name
            FROM
                tm_retur_keluar_wip_item a
                JOIN tm_retur_keluar_wip b ON (a.id_document = b.id)
                JOIN tr_product_base c ON (a.id_product = c.id)
                JOIN tr_color e ON (c.i_color = e.i_color AND c.id_company = e.id_company)
            WHERE
                b.id = '$idreff' 
                AND a.id_document = '$idreff'
                /* AND b.id_company = '$this->idcompany'
                AND b.i_bagian = '$ipengirim' */
                AND a.n_quantity <> 0
                AND a.n_sisa <> 0
            ",
            FALSE
        );
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_retur_jahit');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_retur_jahit
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
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_masuk_retur_jahit
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
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

    function insertheader($id, $ibonm, $datebonm, $ikodemaster, $iasal, $ireff, $eremark)
    {

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
        );
        $this->db->insert('tm_masuk_retur_jahit', $data);
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
        $this->db->insert('tm_masuk_retur_jahit_item', $data);
    }

    function updateqtymasuk($id, $idproduct, $nquantitymasuk)
    {
        $data = array(
            'id_product'     => $idproduct,
            'n_quantity'     => $nquantitymasuk,
            'n_sisa'         => $nquantitymasuk,
        );
        $this->db->where('id_document', $id);
        $this->db->update('tm_retur_keluar_wip_item', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_masuk_retur_jahit a
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
                    $this->db->query(
                        "UPDATE tm_retur_keluar_wip_item a
                        SET n_quantity = b.n_quantity,n_sisa = 0
                        FROM (select id_reff, id_product, n_quantity 
                        from tm_masuk_retur_jahit_item where id_document = '$id') AS b
                        WHERE a.id_document = b.id_reff and a.id_product = b.id_product 
                    ", FALSE);

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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_retur_jahit');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_retur_jahit', $data);
    }

    public function changestatus_20211216($id, $istatus)
    {
        $iapprove = $this->session->userdata('username');
        if ($istatus == '6') {
            $query = $this->db->query("
                SELECT id_document, id_product, n_quantity, id_reff
                FROM tm_masuk_retur_jahit_item
                WHERE id_document = '$id' ", FALSE);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa =  $this->db->query("
                        SELECT
                            n_sisa
                        FROM
                            tm_retur_keluar_wip_item                       
                        WHERE
                            id_document = '$key->id_reff'
                            AND id_product = '$key->id_product'
                            AND id_company = '" . $this->session->userdata('id_company') . "'
                            AND n_sisa >= '$key->n_quantity'
                    ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                            UPDATE
                                tm_retur_keluar_wip_item
                            SET
                                n_sisa = n_sisa - $key->n_quantity
                            WHERE
                                id_document = '$key->id_reff'
                                AND id_product = '$key->id_product'
                                AND id_company = '" . $this->session->userdata('id_company') . "'
                                AND n_sisa >= '$key->n_quantity'
                        ", FALSE);
                    } else {
                        die();
                    }
                }
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
        $this->db->update('tm_masuk_retur_jahit', $data);
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
        return $this->db->query(
            "SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.id_reff,
                d.i_document as i_reff,
                to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
                a.i_bagian,
                b.e_bagian_name,
                a.i_bagian_pengirim,
                c.e_bagian_name ||' - '|| e.name as e_bagian_pengirim,
                a.e_remark,
                a.i_status 
            FROM
                tm_masuk_retur_jahit a 
            INNER JOIN tm_retur_keluar_wip d ON (a.id_reff = d.id ) 
            INNER JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company) 
            INNER JOIN tr_bagian c ON (c.i_bagian = d.i_bagian AND d.id_company = c.id_company)
            INNER JOIN public.company e ON (e.id = c.id_company)
            WHERE a.id  = '$id'
            ",
            FALSE
        );
    }

    public function cek_datadetail($id, $ibagian)
    {
        return $this->db->query(
            "SELECT
                a.id,
                a.id_document,
                a.id_product,
                c.i_product_base,
                c.e_product_basename,
                a.n_quantity as n_quantity_masuk,
                f.n_quantity as n_quantity_jahit,
                f.n_sisa,
                c.i_color,
                a.id_color,
                e.e_color_name,
                a.e_remark
            from tm_masuk_retur_jahit_item a
            inner join tm_masuk_retur_jahit b on (a.id_document = b.id)
            inner join tm_retur_keluar_wip_item f on (a.id_reff = f.id_document and f.id_product = a.id_product)
            inner join tr_product_base c on (a.id_product = c.id)
            inner join tr_color e on (c.i_color = e.i_color and c.id_company = e.id_company)
            WHERE a.id_document = '$id' AND b.id = '$id'
            ",
            FALSE
        );
    }

    public function updateheader($id, $ikodemaster, $ibonm, $datebonm, $eremark, $iasal, $ireff)
    {
        $data = array(
            'i_document'          => $ibonm,
            'i_bagian'            => $ikodemaster,
            'd_document'          => $datebonm,
            'i_bagian_pengirim'   => $iasal,
            'id_reff'             => $ireff,
            'e_remark'            => $eremark,
            'i_status'            => '1',
            'd_update'            => current_datetime(),
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where('i_bagian', $ikodemaster);
        $this->db->update('tm_masuk_retur_jahit', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_retur_jahit_item');
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
    //     $this->db->update('tm_masuk_retur_jahit_item', $data);
    // }
}
/* End of file Mmaster.php */