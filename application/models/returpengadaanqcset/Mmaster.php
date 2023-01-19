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
            $where = "AND d_retur BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_retur_keluar_pengadaan
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
                             a.i_retur,
                             to_char(a.d_retur, 'dd-mm-yyyy') as d_retur,
                             a.i_tujuan,
                             b.e_bagian_name,
                             a.e_remark,
                             a.id_company,
                             a.i_status,
                             c.e_status_name,
                             a.i_bagian,
                             c.label_color as label,
                             f.i_level,
                             l.e_level_name,
                             '$dfrom' AS dfrom,
                             '$dto' AS dto,
                             '$i_menu' as i_menu,
                             '$folder' AS folder
                          FROM
                             tm_retur_keluar_pengadaan a 
                             JOIN
                                tr_bagian b 
                                ON (a.i_tujuan = b.i_bagian AND a.id_company = b.id_company) 
                             JOIN
                                tr_status_document c 
                                ON (a.i_status = c.i_status) 
                            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
                            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
                            WHERE 
                                a.id_company = '$idcompany'
                             AND
                                a.i_status <> '5'
                          $where
                          $bagian
                          ORDER BY
                             a.i_retur asc", false);

        //   $datatables->edit('e_status_name', function ($data) {
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id           = trim($data['id']);
            $i_status     = trim($data['i_status']);
            $itujuan      = trim($data['i_tujuan']);
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];

            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg mr-3'></i></a>";
            }

            return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('label');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_company');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_tujuan');

        return $datatables->generate();
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    /*  public function changestatus($id,$istatus){
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
        $this->db->update('tm_retur_keluar_pengadaan', $data);
    } */


    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_retur_keluar_pengadaan a
                inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
                where a.id = '$id'
                group by 1,2", FALSE)->row();
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {

                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->session->userdata('username'),
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_retur_keluar_pengadaan');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_retur_keluar_pengadaan', $data);
    }

    public function bagian()
    {
        /* 
    $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
    $this->db->from('tr_bagian a');
    $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
    $this->db->where('i_departement', $this->session->userdata('i_departement'));
    $this->db->where('username', $this->session->userdata('username'));
    $this->db->where('a.id_company', $this->session->userdata('id_company'));
    $this->db->order_by('e_bagian_name');
    return $this->db->get();
    */

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
        $this->db->select('i_retur');
        $this->db->from('tm_retur_keluar_pengadaan');
        $this->db->where('i_retur', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        //var_dump($thbl);
        $cek = $this->db->query("
            SELECT 
                substring(i_retur, 1, 4) AS kode 
            FROM tm_retur_keluar_pengadaan 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'STBR';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_retur, 11, 4)) AS max
            FROM
                tm_retur_keluar_pengadaan
            WHERE to_char (d_retur, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_retur, 1, 4) = '$kode'
            AND substring(i_retur, 6, 4) = substring('$thbl',1,4)
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

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_retur_keluar_pengadaan');
        return $this->db->get()->row()->id + 1;
    }

    public function tujuan($i_menu, $idcompany)
    {
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
                                  AND a.id_company = '$idcompany'
                                  AND a.i_bagian = 'PST01'
                                ORDER BY 
                                  b.e_bagian_name");
    }

    public function referensi($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
         SELECT DISTINCT
              a.id,
              a.i_document,
              to_char(a.d_document, 'dd-mm-yyyy') AS d_document
          FROM
              tm_masuk_pengadaan a
              LEFT JOIN tm_masuk_pengadaan_item b
                  on (a.id = b.id_document AND a.id_company = b.id_company)
          WHERE
              a.i_status = '6'
              AND a.id_company = '$idcompany'
              AND b.n_quantity_wip_sisa <> '0'
              AND b.n_quantity_sisa <> '0'
              AND a.i_document ILIKE '%$cari%'
          ORDER BY
              i_document,
              d_document
      ", FALSE);
    }

    public function product($cari)
    {
        $idcompany = $this->session->userdata('id_company');

        return $this->db->query("            
                                  SELECT DISTINCT 
                                      a.id,
                                      a.i_product_wip,
                                      UPPER(a.e_product_wipname) AS e_product_wipname,
                                      c.e_color_name
                                  FROM
                                      tr_product_wip a
                                  INNER JOIN tr_color c ON
                                      (c.i_color = a.i_color
                                      AND c.id_company = a.id_company)
                                  INNER JOIN tm_panel d ON
                                      (a.id = d.id_product_wip)
                                  WHERE
                                      a.id_company = '$idcompany'
                                      AND a.f_status = 't'
                                      AND c.f_status = 't'
                                      AND (a.i_product_wip ILIKE '%$cari%'
                                      OR a.e_product_wipname ILIKE '%$cari%')   
                                  ORDER BY
                                      a.i_product_wip ASC
                              ", FALSE);
    }

    /*----------  CARI REMARK  ----------*/

    public function marker($cari, $id_product_wip)
    {
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query("SELECT
            DISTINCT a.id_marker,
            b.e_marker_name
        FROM
            tr_polacutting_new a
        INNER JOIN tr_marker b ON
            (b.id = a.id_marker)
        WHERE
            a.id_company = '$idcompany'
            AND a.f_status = 't'
            AND
            id_product_wip = '$id_product_wip' 
            AND b.e_marker_name ILIKE '%$cari%';", false);
    }

    public function alasanretur()
    {
        $this->db->select('id, e_alasan_name');
        return $this->db->get('produksi.tr_alasan_retur_repair');
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id, $id_marker)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("            
            SELECT DISTINCT a.*,c.id as id_product_wip,c.i_color,d.i_material,d.e_material_name FROM tm_panel_item a
            INNER JOIN tm_panel b ON (a.id_product_wip = b.id_product_wip AND b.id_company = $idcompany)
            INNER JOIN tr_product_wip c ON (c.id = $id AND c.id = a.id_product_wip AND c.id = b.id_product_wip AND c.id_company = $idcompany)
            LEFT JOIN tr_material d ON (d.id = a.id_material)
            WHERE a.id_marker = '$id_marker'
                              ", FALSE);
    }

    function insertheader($id, $iretur, $ibagian, $dateretur, $itujuan, $eremarkh, $ireferensi)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->set(
            array(
                'id'                    => $id,
                'id_company'            => $idcompany,
                'i_retur'               => $iretur,
                'd_retur'               => $dateretur,
                'i_bagian'              => $ibagian,
                'i_tujuan'              => $itujuan,
                'id_document_reff'      => $ireferensi,
                'e_remark'              => $eremarkh,
                'i_status'              => '1',
                'd_entry'               => current_datetime(),
            )
        );
        $this->db->insert('tm_retur_keluar_pengadaan');
    }

    function insertdetail($id, $idproductwip, $idmarker, $idmaterial, $nqtysum, $nquantity, $eremark, $n_qty_retur, $n_qty_penyusun, $id_panel_item, $n_qty_kekurangan)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->set(
            array(
                'id_retur_keluar'           => $id,
                'id_product_wip'            => $idproductwip,
                'id_marker'                 => $idmarker,
                'id_material'               => $idmaterial,
                'n_quantity_retur'          => $nqtysum,
                'id_company'                => $idcompany,
                'n_quantity_set_return'     => $nquantity,
                'e_alasan_retur_repair'     => $eremark,
                'n_qty_retur'               => $n_qty_retur,
                'n_qty_penyusun'            => $n_qty_penyusun,
                'id_panel_item'             => $id_panel_item,
                'n_qty_kekurangan'          => $n_qty_kekurangan
            )
        );
        $this->db->insert('tm_retur_keluar_pengadaan_item');
    }

    function cek_data($id, $idcompany)
    {
        $this->db->select(" 
                           a.id,
                           a.i_retur,
                           to_char(a.d_retur, 'dd-mm-yyyy') as d_retur,
                           a.i_bagian,
                           a.i_tujuan,
                           a.e_remark,
                           a.i_status,
                           a.id_document_reff,
                           b.i_document 
                        FROM
                           tm_retur_keluar_pengadaan a 
                        LEFT JOIN 
                           tm_masuk_pengadaan b
                        ON a.id_document_reff = b.id AND a.id_company = b.id_company
                        WHERE
                           a.id = '$id'
                        AND 
                           a.id_company = '$idcompany' 
                        ORDER BY
                           d_retur asc", false);
        return $this->db->get();
    }

    public function dataviewdetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
                              SELECT
                                 b.id_product_wip,
                                 c.i_product_wip,
                                 c.e_product_wipname,
                                 d.id,
                                 c.i_color,
                                 d.e_color_name,
                                 b.n_quantity_retur, 
                                 b.id_material,
                                 n_quantity_set_return,
                                 e.i_material,
                                 e.e_material_name,
                                 b.e_remark                                   
                              FROM
                                 tm_retur_keluar_pengadaan a 
                                 JOIN
                                    tm_retur_keluar_pengadaan_item b 
                                    ON (a.id = b.id_retur_keluar) 
                                 JOIN
                                   tr_product_wip c 
                                    ON (b.id_product_wip = c.id AND a.id_company = c.id_company) 
                                 JOIN
                                    tr_color d 
                                    ON (c.i_color = d.i_color AND a.id_company = d.id_company) 
                                 JOIN
                                    tr_material e 
                                    ON (b.id_material = e.id AND a.id_company = e.id_company) 
                              WHERE
                                 a.id = '$id'
                              AND 
                                 a.id_company = '$idcompany'
                          ", FALSE);
    }

    public function dataeditdetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT a.* ,c.i_product_wip ,c.e_product_wipname , g.e_color_name ,f.i_material ,f.e_material_name , e.bagian , a.id_marker, d.e_marker_name, e.i_panel, max
            FROM tm_retur_keluar_pengadaan_item a
            inner JOIN tm_retur_keluar_pengadaan b ON (b.id = a.id_retur_keluar AND b.id_company = a.id_company)
            inner join tr_product_wip c ON (a.id_product_wip = c.id AND c.id_company = a.id_company)
            inner JOIN tm_panel_item e ON (a.id_panel_item = e.id)
            inner join tr_material f ON (f.id = a.id_material)
            inner JOIN tr_color g  ON (g.i_color = c.i_color AND g.id_company = a.id_company)
            inner join tr_marker d ON (d.id = a.id_marker)
            left join (
                   select id_product_wip ,max(n_quantity_set_return) as max from tm_retur_keluar_pengadaan_item where id_retur_keluar = $id group by id_product_wip 
            ) as h on a.id_product_wip = h.id_product_wip
            WHERE a.id_retur_keluar = $id AND a.id_company = $idcompany", FALSE);
    }

    function updateheader($id, $iretur, $dateretur, $ibagian, $itujuan, $eremarkh, $ireferensi)
    {
        $idcompany  = $this->session->userdata('id_company');
        $dentry = date("Y-m-d");
        $this->db->set(
            array(
                'i_retur'           => $iretur,
                'd_retur'           => $dateretur,
                'i_bagian'          => $ibagian,
                'id_document_reff'  => $ireferensi,
                'i_tujuan'          => $itujuan,
                'e_remark'          => $eremarkh,
                'd_update'          => current_datetime(),
            )
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_retur_keluar_pengadaan');
    }

    function deletedetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->query("DELETE FROM tm_retur_keluar_pengadaan_item WHERE id_retur_keluar='$id' AND id_company = '$idcompany'");
    }

    public function doc($imenu)
    {
        $this->db->select('doc_qe');
        $this->db->from('public.tm_menu');
        $this->db->where('i_menu', $imenu);
        return $this->db->get();
    }
}
/* End of file Mmaster.php */