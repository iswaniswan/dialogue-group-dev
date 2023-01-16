<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $id_company = $this->id_company;
        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_stockopname_gdjadi
            WHERE
                i_status <> '5'
                AND d_document BETWEEN to_date('$dfrom','01-mm-yyyy') AND to_date('$dto','01-mm-yyyy') AND id_company = '$id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$id_company')
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
                          i_departement = '$this->i_departement'
                          AND username = '$this->username'
                          AND id_company = '$id_company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 as no,
                a.id,
                a.id_company,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_bagian,
                a.i_periode,
                d.e_bagian_name,
                a.i_status,
                a.e_remark,
                c.e_status_name,
                c.label_color,
                f.i_level,
                l.e_level_name,
                '$i_menu' as i_menu,
                '$folder' as folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto 
            FROM
                tm_stockopname_gdjadi a 
            INNER JOIN
                tr_status_document c 
                ON (c.i_status = a.i_status) 
            INNER JOIN
                tr_bagian d 
                ON (a.id_company = d.id_company and a.i_bagian = d.i_bagian) 
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE
                a.d_document between to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                AND a.id_company = '$id_company'
                AND a.i_status <> '5' 
                $bagian 
            ORDER BY
                d_document DESC,
                a.i_document DESC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id        = trim($data['id']);
            $i_menu    = $data['i_menu'];
            $folder    = $data['folder'];
            $i_status  = $data['i_status'];
            $dfrom     = $data['dfrom'];
            $dto       = $data['dto'];
            $data      = '';

            if (check_role($i_menu, 2)) {
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('id_company');
        $datatables->hide('i_status');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        $datatables->hide('i_bagian');
        return $datatables->generate();
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_stockopname_gdjadi');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_stockopname_gdjadi
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SO';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 9, 4)) AS max
            FROM
              tm_stockopname_gdjadi
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
            AND id_company = '$this->id_company'
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

    public function bagian()
    {
        /* 
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->id_company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
        */

        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement 
            FROM tr_bagian a 
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
        $this->db->from('tm_stockopname_gdjadi');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->id_company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function get_bagian($ibagian, $idcompany)
    {
        return $this->db->query("SELECT i_bagian, e_bagian_name FROM tr_bagian WHERE i_bagian = '$ibagian' AND id_company = '$idcompany'", FALSE);
    }

    public function dataheader($idcompany, $i_document, $ibagian)
    {
        return $this->db->query("SELECT id FROM tm_stockopname_gdjadi WHERE id_company = '$idcompany'  AND i_bagian = '$ibagian' AND i_document = '$i_document'", FALSE);
    }

    public function datadetail($idcompany, $ibagian)
    {

        $id_company     = $this->id_company;
        $i_periode      = date("Ym");
        $dfrom          = date("Y-m-01");
        $dto            = date("Y-m-d");
        if ($dfrom == $dto) {
            $d_jangka_awal  = '9999-01-01';
            $d_jangka_akhir = '9999-01-31';
        } else {
            $d_jangka_awal = date('Y-m-01');
            $d_jangka_akhir = date('Y-m-d', strtotime("-1 days"));
        }

        /*$dfrom          = $ddocument->format('Y-m-01');
          $dto            = $ddocument->format('Y-m-d');

          return $this->db->query("             
                                    SELECT
                                       x.id_company,
                                       x.i_material,
                                       a.id,
                                       a.e_material_name,
                                       b.e_satuan_name,
                                       0 as n_quantity,
                                       '' as e_remark 
                                    FROM
                                       f_mutasi_saldoawal_pengadaan('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x 
                                       INNER JOIN
                                          tr_material a 
                                          ON (a.id_company = x.id_company 
                                          AND a.i_material = x.i_material) 
                                       INNER JOIN
                                          tr_satuan b 
                                          ON (a.id_company = b.id_company 
                                          AND a.i_satuan_code = b.i_satuan_code)
                                  ", FALSE); */

        // $id_company     = $this->id_company;
        /* 
        return $this->db->query(" SELECT 
                aa.id_document ,
                x.i_product_wip,
                x.e_product_wipname,
                x.i_color , 
                c.e_color_name ,
                a.id_company ,
                x.i_satuan_code,
                b.e_satuan_name,
                aa.n_quantity ,
                aa.e_remark 
            FROM tm_stockopname_gdjadi a
            INNER JOIN 
                tm_stockopname_gdjadi_item aa
                ON
                (a.id = aa.id_document AND a.id_company = aa.id_company)
            INNER JOIN 
                tr_product_wip x
                ON
                (aa.id_material = x.id AND aa.id_company = x.id_company)
            INNER JOIN
                tr_satuan b 
                ON
                (a.id_company = b.id_company AND x.i_satuan_code = b.i_satuan_code)
            INNER JOIN 
                tr_color c 
                ON 
                (a.id_company = c.id_company AND x.i_color = c.i_color) 
            WHERE 
                a.id_company = '5' 
                AND 
                a.id_document = '$id'
            ORDER BY 3 ASC;
        ", FALSE);  
    */

        return $this->db->query(" SELECT 
                a.id,
                a.i_product_base i_product_wip,
                a.e_product_basename e_product_wipname,
                a.i_color , 
                c.e_color_name ,
                a.id_company,
                a.i_satuan_code,
                b.e_satuan_name,
                x.n_saldo_akhir saldo_akhir
                FROM
                    f_mutasi_packing('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
                INNER JOIN 
                    tr_product_base a
                    ON
                    (a.id = x.id_product_base AND a.id_company = $id_company)
                INNER JOIN
                    tr_satuan b 
                    ON
                    (b.id_company = a.id_company AND a.i_satuan_code = b.i_satuan_code)
                INNER JOIN 
                    tr_color c 
                    ON 
                    (c.i_color = a.i_color AND c.id_company = $id_company) 
                ORDER BY 3
        ", FALSE);
    }

    /*----------  CARI BARANG  ----------*/
    public function barang($cari, $ibagian, $ddocument)
    {
        /* 
          $ddocument      = DateTime::createFromFormat('d-m-Y', $ddocument);
          $id_company     = $this->id_company;
          $i_periode      = $ddocument->format('Ym');
          $d_jangka_awal  = '9999-01-01';
          $d_jangka_akhir = '9999-01-31';
          $dfrom          = $ddocument->format('Y-m-01');
          $dto            = $ddocument->format('Y-m-d');

          return $this->db->query(" SELECT
                x.id_company,
                x.i_material,
                a.id,
                a.e_material_name,
                b.e_satuan_name 
            FROM
                f_mutasi_saldoawal_pengadaan('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x 
            INNER JOIN
                tr_material a 
                ON (a.id_company = x.id_company 
                AND a.i_material = x.i_material) 
            INNER JOIN
                tr_satuan b 
                ON (a.id_company = b.id_company 
                AND a.i_satuan_code = b.i_satuan_code)
        ", FALSE); 
        */

        $id_company     = $this->id_company;

        return $this->db->query(" SELECT 
            a.id,
            a.i_product_base i_product_wip,
            a.e_product_basename e_product_wipname,
            a.i_color , 
            c.e_color_name ,
            a.id_company,
            a.i_satuan_code,
            b.e_satuan_name
        FROM tr_product_base a
        INNER JOIN
            tr_satuan b 
            ON
            (a.id_company = b.id_company AND a.i_satuan_code = b.i_satuan_code)
        INNER JOIN 
            tr_color c 
            ON 
            (a.id_company = c.id_company AND a.i_color = c.i_color) 
        WHERE a.id_company = '$id_company' 
        AND (a.i_product_base ILIKE '%$cari%' OR a.e_product_basename ILIKE '%$cari%')
        ORDER BY 3 asc
        ", FALSE);
    }

    public function simpan($id, $idcompany, $ibagian, $idocument, $ddocument, $iperiode, $eremarkh)
    {
        $dentry = current_datetime();
        $data = array(
            'id'           => $id,
            'id_company'   => $idcompany,
            'i_document'   => $idocument,
            'd_document'   => $ddocument,
            'i_bagian'     => $ibagian,
            'i_periode'    => $iperiode,
            'e_remark'     => $eremarkh,
        );
        $this->db->insert('tm_stockopname_gdjadi', $data);
    }

    public function simpandetail($idcompany, $id, $idmaterial, $qty, $qty_repair, $qty_gradeb, $eremark)
    {
        $data = array(
            'id_company'        => $idcompany,
            'id_document'       => $id,
            'id_product'        => $idmaterial,
            'n_quantity'        => $qty,
            'n_quantity_repair' => $qty_repair,
            'n_quantity_gradeb' => $qty_gradeb,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_stockopname_gdjadi_item', $data);
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
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_stockopname_gdjadi a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_stockopname_gdjadi');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_stockopname_gdjadi', $data);
    }


    public function dataheader_edit($id)
    {
        return $this->db->query("SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.e_remark,
                a.i_status,
                a.i_bagian,
                b.e_bagian_name 
            FROM
                tm_stockopname_gdjadi a 
            INNER JOIN
                tr_bagian b ON (a.i_bagian = b.i_bagian 
                AND a.id_company = b.id_company) 
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    public function datadetail_edit($id)
    {
        /* 
        return $this->db->query("
                                  SELECT
                                     a.id_material as id,
                                     b.i_material,
                                     b.e_material_name,
                                     c.e_satuan_name,
                                     a.n_quantity,
                                     a.e_remark 
                                  FROM
                                     tm_stockopname_gdjadi_item a 
                                     INNER JOIN
                                        tr_material b 
                                        ON (a.id_material = b.id) 
                                     INNER JOIN
                                        tr_satuan c 
                                        ON (b.i_satuan_code = c.i_satuan_code 
                                        AND b.id_company = c.id_company) 
                                  WHERE
                                     id_document = '$id'
                                ", FALSE);
       */

        $id_company     = $this->id_company;

        return $this->db->query(" SELECT 
                a.id_product id,
                a.id_document,
                x.i_product_base i_product_wip,
                x.e_product_basename e_product_wipname,
                x.i_color, 
                c.e_color_name,
                a.id_company,
                a.n_quantity,
                a.n_quantity_repair,
                a.n_quantity_gradeb,
                a.e_remark 
            FROM 
                tm_stockopname_gdjadi_item a
            INNER JOIN 
                tr_product_base x ON
                (a.id_product = x.id)
            INNER JOIN 
                tr_color c ON 
                (c.id_company = x.id_company AND x.i_color = c.i_color) 
            WHERE a.id_document = '$id' 
            ORDER BY 3 ASC
        ", FALSE);
    }

    public function updateheader($id, $eremarkh)
    {
        $data = array(
            'e_remark' => $eremarkh,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_stockopname_gdjadi', $data);
    }

    public function hapusdetail($id)
    {
        $this->db->where("id_document", $id);
        $this->db->delete("tm_stockopname_gdjadi_item");
        // return $this->db->query("DELETE FROM tm_stockopname_gdjadi_item WHERE id_document = '$id'", FALSE);
    }
}
/* End of file Mmaster.php */