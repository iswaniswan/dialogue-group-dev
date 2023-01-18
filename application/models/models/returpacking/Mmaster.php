<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;
class Mmaster extends CI_Model {
    function data($i_menu, $folder, $dfrom, $dto) {
        $idcompany = $this->session->userdata('id_company');
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto = date('Y-m-d', strtotime($dto));
            $where = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_retur_produksi_gdjd
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
        if ($this->session->userdata('i_departement') == '1') {
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
        $datatables->query("SELECT
                             0 as no,
                             a.id,
                             a.i_document,
                             to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                             a.i_tujuan,
                             bc.e_bagian_name pembuat,
                             b.e_bagian_name,
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
                             tm_retur_produksi_gdjd a 
                             JOIN
                                tr_bagian b 
                                ON (a.i_tujuan = b.i_bagian AND a.id_company = b.id_company) 
                             JOIN
                                tr_bagian bc 
                                ON (a.i_bagian = bc.i_bagian AND a.id_company = bc.id_company) 
                             JOIN
                                tr_status_document c 
                                ON (a.i_status = c.i_status)                       
                            LEFT JOIN tr_menu_approve f ON
                                (a.i_approve_urutan = f.n_urut
                                AND f.i_menu = '$i_menu')
                            LEFT JOIN public.tr_level l ON
                                (f.i_level = l.i_level)
                             WHERE 
                                a.id_company = '$idcompany'
                             AND
                                a.i_status <> '5'
                          $where
                          $bagian
                          ORDER BY
                             a.i_document asc", false);
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });
        $datatables->add('action', function ($data) {
            $id = trim($data['id']);
            $i_status = trim($data['i_status']);
            $itujuan = trim($data['i_tujuan']);
            $ibagian = trim($data['i_bagian']);
            $folder = $data['folder'];
            $dfrom = $data['dfrom'];
            $dto = $data['dto'];
            $i_menu = $data['i_menu'];
            $i_level = $data['i_level'];
            $data = '';
            if (check_role($i_menu, 2)) {
                $data.= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$ibagian\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data.= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$ibagian\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data.= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$ibagian\",\"#main\"); return false;'><i class='ti-check-box mr-3 text-primary'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data.= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3'></i></a>";
            }
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
    public function estatus($istatus) {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }
    public function changestatus_20211218($id, $istatus) {
        if ($istatus == '6') {
            $data = array('i_status' => $istatus, 'e_approve' => $this->session->userdata('username'), 'd_approve' => date('Y-m-d'),);
        } else {
            $data = array('i_status' => $istatus,);
        }
        $this->db->where('id', $id);
        $this->db->update('tm_retur_produksi_gdjd', $data);
    }
    public function changestatus($id, $istatus) {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_retur_produksi_gdjd a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2", FALSE)->row();
            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0) {
                    $data = array('i_status' => $istatus,);
                } else {
                    $data = array('i_approve_urutan' => $awal->i_approve_urutan - 1,);
                }
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array('i_status' => $istatus, 'i_approve_urutan' => $awal->i_approve_urutan + 1, 'e_approve' => $this->username, 'd_approve' => date('Y-m-d'),);
                } else {
                    $data = array('i_approve_urutan' => $awal->i_approve_urutan + 1,);
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_retur_produksi_gdjd');", FALSE);
            }
        } else {
            $data = array('i_status' => $istatus,);
        }
        $this->db->where('id', $id);
        $this->db->update('tm_retur_produksi_gdjd', $data);
    }
    public function bagian() {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian', 'inner');
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
    public function cek_kode($kode, $ibagian) {
        $this->db->select('i_document');
        $this->db->from('tm_retur_produksi_gdjd');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }
    public function runningnumber($thbl, $tahun, $ibagian) {
        //var_dump($thbl);
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 4) AS kode 
            FROM tm_retur_produksi_gdjd 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'STBR';
        }
        $query = $this->db->query("
            SELECT
                max(substring(i_document, 11, 4)) AS max
            FROM
                tm_retur_produksi_gdjd
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 4) = '$kode'
            AND substring(i_document, 6, 2) = substring('$thbl',1,2)
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
            $nomer = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }
    public function runningid() {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_retur_produksi_gdjd');
        return $this->db->get()->row()->id + 1;
    }
    public function tujuan($i_menu, $idcompany) {
        return $this->db->query(" 
                                SELECT 
                                    a.*,
                                    b.e_bagian_name 
                                FROM 
                                    tr_tujuan_menu a
                                JOIN tr_bagian b 
                                ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
                                WHERE
                                  a.i_menu = '$i_menu'
                                  AND a.id_company = '$idcompany'");
    }
    public function dataproduct($cari) {
        $idcompany = $this->session->userdata('id_company');
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
    public function getproduct($eproduct, $ibagian) {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d',strtotime("-1 days"));
        $periode = date('Ym');
        return $this->db->query("SELECT 
                a.id as id_product, 
                a.i_product_base,
                a.e_product_basename,
                b.id as id_color,
                a.i_color,
                b.e_color_name,
                coalesce(n_saldo_akhir,0) n_saldo_akhir
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color AND a.id_company = b.id_company)
            LEFT JOIN (SELECT * FROM produksi.f_mutasi_packing($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) c ON
                (c.id_product_base = a.id AND c.id_company = '$idcompany')
            WHERE
                a.id_company = '$idcompany'
            AND 
                a.id = '$eproduct'
        ", FALSE);
    }
    function insertheader($id, $iretur, $ibagian, $dateretur, $itujuan, $eremarkh) {
        $idcompany = $this->session->userdata('id_company');
        $this->db->set(array('id' => $id, 'id_company' => $idcompany, 'i_document' => $iretur, 'd_document' => $dateretur, 'i_bagian' => $ibagian, 'i_tujuan' => $itujuan, 'e_remark' => $eremarkh, 'i_status' => '1', 'd_entry' => current_datetime(),));
        $this->db->insert('tm_retur_produksi_gdjd');
    }
    function insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc) {
        $idcompany = $this->session->userdata('id_company');
        $this->db->set(array('id_document' => $id, 'id_product' => $iproduct, 'n_quantity' => $nqtyproduct, 'n_sisa_retur' => $nqtyproduct, 'id_company' => $idcompany, 'e_remark' => $edesc,));
        $this->db->insert('tm_retur_produksi_gdjd_item');
    }
    function cek_data($id, $idcompany) {
        $this->db->select(" 
                           a.id,
                           a.i_document as i_retur,
                           to_char(a.d_document, 'dd-mm-yyyy') as d_retur,
                           a.i_bagian,
                           a.i_tujuan,
                           a.e_remark,
                           a.i_status 
                        FROM
                           tm_retur_produksi_gdjd a 
                        WHERE
                           a.id = '$id'
                        AND 
                           a.id_company = '$idcompany' 
                        ORDER BY
                           d_document asc", false);
        return $this->db->get();
    }
    public function dataeditdetail($id,$ibagian) {
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d',strtotime("-1 days"));
        $periode = date('Ym');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.id_document as i_retur,
                a.id_product,
                b.i_product_base,
                b.e_product_basename,
                c.id as id_color,
                b.i_color,
                c.e_color_name,
                a.n_quantity,
                a.e_remark,
                coalesce(n_saldo_akhir,0) n_saldo_akhir
            FROM
                tm_retur_produksi_gdjd_item a 
            JOIN
                tm_retur_produksi_gdjd d 
                ON a.id_document = d.id 
            JOIN
                tr_product_base b 
                ON a.id_product = b.id 
                AND d.id_company = b.id_company 
            JOIN
                tr_color c 
                ON b.i_color = c.i_color 
                AND d.id_company = c.id_company 
            LEFT JOIN (SELECT * FROM produksi.f_mutasi_packing($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian')) e ON
                (e.id_product_base = a.id_product AND e.id_company = a.id_company)
            WHERE
                a.id_document = '$id' 
                AND d.id_company = '$idcompany'
        ", FALSE);
    }
    function updateheader($id, $iretur, $dateretur, $ibagian, $itujuan, $eremarkh) {
        $idcompany = $this->session->userdata('id_company');
        $dentry = date("Y-m-d");
        $this->db->set(array('i_document' => $iretur, 'd_document' => $dateretur, 'i_bagian' => $ibagian, 'i_tujuan' => $itujuan, 'e_remark' => $eremarkh, 'd_update' => current_datetime(),));
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_retur_produksi_gdjd');
    }
    function deletedetail($id) {
        $idcompany = $this->session->userdata('id_company');
        $this->db->query("DELETE FROM tm_retur_produksi_gdjd_item WHERE id_document='$id' AND id_company = '$idcompany'");
    }
}
/* End of file Mmaster.php */
