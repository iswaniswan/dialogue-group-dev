<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany  = $this->session->userdata('id_company');
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);

        $cek = $this->db->query("
                SELECT
                    i_bagian
                FROM
                    tm_stbjahit_retur
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

        $datatables->query(
            "SELECT
                0 as no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_tujuan,
                b.e_bagian_name||' - '||bb.name as e_bagian_name,
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
                tm_stbjahit_retur a 
            JOIN
                tr_bagian b 
                ON (a.i_tujuan = b.i_bagian AND b.id_company = a.id_bagian_company) 
            JOIN public.company bb ON (bb.id = b.id_company)
            JOIN
                tr_status_document c 
                ON (a.i_status = c.i_status) 
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE 
                a.id_company = '$idcompany' AND a.i_status <> '5'
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
            $id           = trim($data['id']);
            $i_status     = trim($data['i_status']);
            $itujuan      = trim($data['i_tujuan']);
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];
            $i_level      = $data['i_level'];

            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
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

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_stbjahit_retur a
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
                    $query = $this->db->query(
                        "SELECT
                            c.id id_product_wip,
                            id_product,
                            id_reference,
                            n_quantity,
                            a.id_company
                        FROM
                            tm_stbjahit_retur_item a
                        INNER JOIN tr_product_base b ON (b.id = a.id_product)
                        INNER JOIN tr_product_wip c ON (c.i_product_wip = b.i_product_wip AND b.i_color = c.i_color AND c.id_company = b.id_company)                   
                        WHERE 
                            id_document = '$id' ",
                        false
                    );
                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $key) {
                            $nsisa = $this->db->query(
                                "SELECT
                                    qty_sisa_bs
                                FROM
                                    tm_masuk_unitjahit_item
                                WHERE
                                    id_document = '$key->id_reference'
                                    AND id_product_wip = '$key->id_product_wip'
                                    AND id_company = '$this->id_company'
                                    AND qty_sisa_bs >= '$key->n_quantity'",
                                false
                            );

                            if ($nsisa->num_rows() > 0) {
                                $this->db->query(
                                    "UPDATE
                                        tm_masuk_unitjahit_item
                                    SET
                                        qty_sisa_bs = (qty_sisa_bs) - ($key->n_quantity)
                                    WHERE
                                        id_document = '$key->id_reference'
                                        AND id_product_wip = '$key->id_product_wip'
                                        AND id_company = '$this->id_company'
                                        AND qty_sisa_bs >= '$key->n_quantity'",
                                    false
                                );
                            } else {
                                $this->db->query(
                                    "UPDATE
                                        tm_stbjahit_retur_item
                                    SET
                                        n_quantity = 0,
                                        n_sisa = 0
                                    WHERE
                                        id_product = '$key->id_product'
                                        AND id_document = '$id'",
                                    false
                                );
                            }
                        }
                    }
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_stbjahit_retur');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_stbjahit_retur', $data);
    }

    public function changestatus_20211213($id, $istatus)
    {
        if ($istatus == '6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_stbjahit_retur', $data);
    }

    public function bagian()
    {
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

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_stbjahit_retur');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $query = $this->db->query("SELECT b.e_no_doc_retur, b.e_no_doc FROM tr_bagian a 
            INNER JOIN tr_kategori_jahit b ON (b.id = a.id_kategori_jahit) 
            WHERE id_company = '$this->id_company' AND a.i_bagian = '$ibagian'");
        if ($query->num_rows() > 0) {
            $kode = $query->row()->e_no_doc_retur;
        } else {
            $kode = 'SJ';
        }
        // var_dump($kode);
        if (strlen($kode) == 4) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 11, 4)) AS max 
                FROM tm_stbjahit_retur
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 3) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 10, 4)) AS max 
                FROM tm_stbjahit_retur
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        } elseif (strlen($kode) == 2) {
            $sql  = $this->db->query("SELECT max(substring(i_document, 9, 4)) AS max 
                FROM tm_stbjahit_retur
                WHERE to_char (d_document, 'yymm') = '$thbl'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->id_company'
                AND i_document ILIKE '$kode%'
            ", false);
        }
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
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

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_stbjahit_retur');
        return $this->db->get()->row()->id + 1;
    }

    public function tujuan($i_menu, $idcompany)
    {
        /* return $this->db->query(" 
                                SELECT 
                                    a.*,
                                    b.e_bagian_name 
                                FROM 
                                    tr_tujuan_menu a
                                JOIN tr_bagian b 
                                ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
                                WHERE
                                  a.i_menu = '$i_menu'
                                  AND a.id_company = '$idcompany'"); */
        return $this->db->query("SELECT a.id_company, a.id, a.id_company||'|'||a.i_bagian as i_bagian, a.e_bagian_name||' - '||b.name as e_bagian_name, b.name
        FROM tr_bagian a
        INNER JOIN public.company b ON (b.id = a.id_company)
        WHERE a.f_status = 't' AND b.f_status = 't' AND i_type = '09'
        ORDER BY a.id_company, a.e_bagian_name ASC");
    }

    public function dataproduct($cari)
    {
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

    public function getproduct($eproduct)
    {
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

    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark)
    {
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $idcompany = $this->session->userdata('id_company');
        $thbl  = format_ym($datebonk);
        $tahun = format_Y($datebonk);
        $ibonk = $this->runningnumber($thbl, $tahun, $ibagian);
        $data = array(
            'id_company'        => $idcompany,
            'id'                => $id,
            'i_document'        => $ibonk,
            'd_document'        => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'id_bagian_company' => $id_company_tujuan,
            'e_remark'          => $eremark,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_stbjahit_retur', $data);
    }

    public function insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc, $e_bagian, $reject, $det_reject, $id_reff, $nqtyawal)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_document'   => $id,
            'id_product'    => $iproduct,
            'id_color'      => $icolor,
            'n_quantity'    => $nqtyproduct,
            'n_sisa'        => $nqtyproduct,
            'id_company'    => $idcompany,
            'e_remark'      => $edesc,
            'bagian'        => $e_bagian,
            'id_reject'     => $reject,
            'detail_reject' => $det_reject,
            'id_reference'  => $id_reff,
            'n_qty_awal'    => $nqtyawal,
        );
        $this->db->insert('tm_stbjahit_retur_item', $data);
    }

    public function cek_data($id, $idcompany)
    {
        return $this->db->query(
            "SELECT
                a.*,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document
            FROM
                tm_stbjahit_retur a
            WHERE                                        
                a.id = '$id'
            AND
                a.id_company = '$idcompany' 
        ", FALSE);
    }

    public function cek_datadetail($id, $idcompany)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_base,
                b.e_product_basename,
                c.i_color,
                c.e_color_name,
                a.n_quantity as n_quantity_product,
                e_reject_name
            FROM
                tm_stbjahit_retur_item a 
            JOIN
                tm_stbjahit_retur d 
                ON a.id_document = d.id 
            JOIN
                tr_product_base b 
                ON a.id_product = b.id 
                /* AND d.id_company = b.id_company  */
            JOIN
                tr_color c 
                ON c.i_color = b.i_color
                AND b.id_company = c.id_company 
            LEFT JOIN tr_reject e ON (e.id = a.id_reject)
            WHERE
                a.id_document = '$id' 
            ", FALSE);
    }

    public function updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark)
    {
        $split = explode("|", $itujuan);
        $id_company_tujuan = $split[0];
        $itujuan = $split[1];
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_document'        => $ibonk,
            'd_document'        => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'id_bagian_company' => $id_company_tujuan,
            'e_remark'          => $eremark,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_stbjahit_retur', $data);
    }

    public function deletedetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('id_document', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_stbjahit_retur_item');
    }

    public function getdataitem($ibagian, $dfrom, $dto)
    {
        return $this->db->query("SELECT
                a.id_document,
                'No Reff. '||i_document||', Tgl. '||to_char(d_document,'DD FMMonth, YYYY') reff,
                e.id id_product_wip,
                e.i_product_base i_product_wip,
                e.e_product_basename e_product_wipname,
                d.id AS id_color,
                d.e_color_name,
                sum(qty_sisa_bs) AS qty_sisa
            FROM
                tm_masuk_unitjahit_item a
            INNER JOIN tm_masuk_unitjahit b ON
                (b.id = a.id_document)
            INNER JOIN tr_product_wip c ON
                (c.id = a.id_product_wip)
            INNER JOIN tr_product_base e ON
                (e.i_product_wip = c.i_product_wip 
                AND c.i_color = e.i_color AND e.id_company = c.id_company)
            INNER JOIN tr_color d ON
                (d.i_color = e.i_color
                    AND e.id_company = d.id_company)
            WHERE
                b.i_status = '6'
                AND b.d_document BETWEEN '$dfrom' AND '$dto' 
                AND a.qty_sisa_bs > 0 and b.i_bagian = '$ibagian'
            GROUP BY 1,2,3,4,5,6,7
        ");
    }
}
/* End of file Mmaster.php */