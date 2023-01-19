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
                tm_stockopname_pengesettan
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
                             tm_stockopname_pengesettan a 
                             JOIN
                                tr_bagian b 
                                ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company) 
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
                             a.i_document asc", false);

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
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];

            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
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
        $this->db->update('tm_stockopname_pengesettan', $data);
    } */


    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_stockopname_pengesettan a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_stockopname_pengesettan');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_stockopname_pengesettan', $data);
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
        $this->db->select('i_document');
        $this->db->from('tm_stockopname_pengesettan');
        $this->db->where('i_document', $kode);
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
                substring(i_document, 1, 2) AS kode 
            FROM tm_stockopname_pengesettan 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SO';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 4)) AS max
            FROM
                tm_stockopname_pengesettan
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 4) = substring('$thbl',1,4)
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
        $this->db->from('tm_stockopname_pengesettan');
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

    public function alasanretur()
    {
        $this->db->select('id, e_alasan_name');
        return $this->db->get('produksi.tr_alasan_retur_repair');
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("            
            SELECT a.*,c.id as id_product_wip,c.i_color,d.i_material,d.e_material_name FROM tm_panel_item a
            INNER JOIN tm_panel b ON (a.id_product_wip = b.id_product_wip AND b.id_company = $idcompany)
            INNER JOIN tr_product_wip c ON (c.id = $id AND c.id = a.id_product_wip AND c.id = b.id_product_wip AND c.id_company = $idcompany)
            LEFT JOIN tr_material d ON (d.id = a.id_material)
                              ", FALSE);
    }

    function insertheader($id, $idoc, $ibagian, $datedoc, $eremarkh)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->set(
            array(
                'id'                    => $id,
                'id_company'            => $idcompany,
                'i_document'            => $idoc,
                'd_document'            => $datedoc,
                'i_bagian'              => $ibagian,
                'e_remark'              => $eremarkh,
                'i_status'              => '1',
                'd_entry'               => current_datetime(),
            )
        );
        $this->db->insert('tm_stockopname_pengesettan');
    }

    function insertdetail($id, $idproductwip, $idmaterial, $nqtysobagus, $eremark, $id_panel_item)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->set(
            array(
                'id_company'                => $idcompany,
                'id_document'               => $id,
                'id_product_wip'            => $idproductwip,
                'id_material'               => $idmaterial,
                'n_so_bagus'                => $nqtysobagus,
                'e_remark'                  => $eremark,
                'id_panel_item'             => $id_panel_item,
            )
        );
        $this->db->insert('tm_stockopname_pengesettan_item');
    }

    function cek_data($id, $idcompany)
    {
        $this->db->select(" 
                           a.id,
                           a.i_document,
                           to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                           a.i_bagian,
                           a.e_remark,
                           a.i_status
                        FROM
                           tm_stockopname_pengesettan a 
                        WHERE
                           a.id = '$id'
                        AND 
                           a.id_company = '$idcompany' 
                        ORDER BY
                           d_document asc", false);
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
                                 tm_stockopname_pengesettan a 
                                 JOIN
                                    tm_stockopname_pengesettan_item b 
                                    ON (a.id = b.id_document_keluar) 
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
            SELECT a.* ,c.i_product_wip ,c.e_product_wipname , g.e_color_name ,f.i_material ,f.e_material_name , e.bagian , e.i_panel, max
            FROM tm_stockopname_pengesettan_item a
            inner JOIN tm_stockopname_pengesettan b ON (b.id = a.id_document AND b.id_company = a.id_company)
            inner join tr_product_wip c ON (a.id_product_wip = c.id AND c.id_company = a.id_company)
            inner JOIN tm_panel_item e ON (a.id_panel_item = e.id)
            inner join tr_material f ON (f.id = a.id_material)
            inner JOIN tr_color g  ON (g.i_color = c.i_color AND g.id_company = a.id_company)
            left join (
                   select id_product_wip ,max(n_so_bagus) as max from tm_stockopname_pengesettan_item where id_document = $id group by id_product_wip 
            ) as h on a.id_product_wip = h.id_product_wip
            WHERE a.id_document = $id AND a.id_company = $idcompany", FALSE);
    }

    function updateheader($id, $idoc, $ibagian, $datedoc, $eremarkh)
    {
        $idcompany  = $this->session->userdata('id_company');
        $dentry = date("Y-m-d");
        $this->db->set(
            array(
                'i_document'           => $idoc,
                'd_document'           => $datedoc,
                'i_bagian'          => $ibagian,
                'e_remark'          => $eremarkh,
                'd_update'          => current_datetime(),
            )
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_stockopname_pengesettan');
    }

    function deletedetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->query("DELETE FROM tm_stockopname_pengesettan_item WHERE id_document='$id' AND id_company = '$idcompany'");
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