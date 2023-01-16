<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }
        $cek = $this->db->query("SELECT i_bagian FROM tm_stockopname_material
               WHERE i_status <> '5' $and AND id_company = '$this->id_company'
                    AND i_bagian IN (SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement'
                    AND i_level = '$this->i_level' AND username = '$this->username' AND id_company = '$this->id_company')");
        if ($this->i_departement == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (SELECT i_bagian FROM tr_departement_cover
                       WHERE i_departement = '$this->i_departement' AND i_level = '$this->i_level'
                           AND username = '$this->username' AND id_company = '$this->id_company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT 0 as no, a.id, a.id_company, a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                a.i_bagian, a.i_periode, d.e_bagian_name, a.i_status,
                a.e_remark, c.e_status_name, c.label_color, l.i_level, l.e_level_name, 
                '$i_menu' as i_menu, '$folder' as folder, '$dfrom' AS dfrom,'$dto' AS dto
            FROM tm_stockopname_material a
            INNER JOIN tr_status_document c ON (c.i_status = a.i_status) 
            INNER JOIN tr_bagian d ON (a.id_company = d.id_company and a.i_bagian = d.i_bagian) 
            LEFT JOIN public.tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (f.i_level = l.i_level)
            WHERE a.id_company = '$this->id_company' AND a.i_status <> '5' 
               $bagian $and
            ORDER BY d_document DESC, a.i_document DESC");

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
            $i_level   = trim($data['i_level']);
            $dfrom     = $data['dfrom'];
            $dto       = $data['dto'];
            $data      = '';

            if (check_role($i_menu, 2)) {
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye mr-2 fa-lg text-success'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-2 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg'></i></a>";
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
        $datatables->hide('i_bagian');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    //BARU
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_stockopname_material');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
          SELECT 
              substring(i_document, 1, 2) AS kode 
          FROM tm_stockopname_material
          WHERE i_status <> '5'
          AND i_bagian = '$ibagian'
          AND id_company = '$this->id_company'
          ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SO';
        }
        $query  = $this->db->query("
          SELECT
              max(substring(i_document, 9, 3)) AS max
          FROM
            tm_stockopname_material
          WHERE to_char (d_document, 'yyyy') >= '$tahun'
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
            while ($n < 3) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->i_departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->id_company);
        // $this->db->where('a.i_type', '01');
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT id, i_bagian, e_bagian_name FROM tr_bagian WHERE id_company = '$this->id_company' AND i_bagian IN (
            SELECT i_bagian FROM tr_tujuan_menu WHERE id_company = '$this->id_company' AND i_menu = '$this->i_menu' ORDER BY id
        )");
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_stockopname_material');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function get_bagian($ibagian, $idcompany)
    {
        $this->db->select(" i_bagian, e_bagian_name from tr_bagian where i_bagian = '$ibagian' and id_company = '$idcompany' ", false);
        return $this->db->get();
    }

    public function dataheader($idcompany, $i_document, $ibagian)
    {
        $this->db->select("id from tm_stockopname_material where id_company = '$idcompany' and i_bagian = '$ibagian' and i_document = '$i_document' ", false);
        return $this->db->get();
    }

    public function datadetail($idcompany, $ddocument, $ibagian)
    {
        $ddocument = DateTime::createFromFormat('d-m-Y', $ddocument);
        $id_company = $this->id_company;
        $i_periode = $ddocument->format('Ym');
        $d_jangka_awal = '9999-01-01';
        $d_jangka_akhir = '9999-01-31';
        $dfrom = $ddocument->format('Y-m-01');
        $dto = $ddocument->format('Y-m-d');

        return $this->db->query("
              select x.id_company, a.i_material, a.id, a.e_material_name, b.e_satuan_name, 0 as n_quantity, '' as e_remark from f_mutasi_material('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
       inner join tr_material a on (a.id_company = x.id_company and a.id = x.id_material)
        inner join tr_satuan b on (a.id_company = b.id_company and a.i_satuan_code = b.i_satuan_code)
        ", FALSE);
    }


    /*----------  CARI BARANG  ----------*/
    public function barang($cari, $ibagian, $ddocument)
    {

        $ddocument = DateTime::createFromFormat('d-m-Y', $ddocument);
        $id_company = $this->id_company;
        $i_periode = $ddocument->format('Ym');
        $d_jangka_awal = '9999-01-01';
        $d_jangka_akhir = '9999-01-31';
        $dfrom = $ddocument->format('Y-m-01');
        $dto = $ddocument->format('Y-m-d');

        return $this->db->query("            
            select x.id_company, a.i_material, a.id, a.e_material_name, b.e_satuan_name from f_mutasi_material('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x
       inner join tr_material a on (a.id_company = x.id_company and a.id = x.id_material)
        inner join tr_satuan b on (a.id_company = b.id_company and a.i_satuan_code = b.i_satuan_code)
        ", FALSE);
    }

    public function simpan($id, $ibagian, $idocument, $ddocument, $iperiode, $eremarkh)
    {
        $data = array(
            'id'           => $id,
            'id_company'   => $this->id_company,
            'i_document'   => $idocument,
            'd_document'   => $ddocument,
            'i_bagian'     => $ibagian,
            'i_periode'    => $iperiode,
            'e_remark'     => $eremarkh,
        );
        $this->db->insert('tm_stockopname_material', $data);
    }

    public function simpandetail($id, $idmaterial, $qty, $eremark)
    {
        $data = array(
            'id_document'     => $id,
            'id_material'     => $idmaterial,
            'n_quantity'      => $qty,
            'e_remark'        => $eremark,
        );
        $this->db->insert('tm_stockopname_material_item', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    /* public function changestatus($id, $istatus)
    {
        $dreceive = '';
        $dreceive = date('Y-m-d');
        $iapprove = $this->username;
        if ($istatus == '6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $iapprove,
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->id_company);
        $this->db->update('tm_stockopname_material', $data);
    } */

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_stockopname_material a
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
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_stockopname_material');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_stockopname_material', $data);
    }

    public function dataheader_edit($id)
    {
        $this->db->select("
    a.id, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') as d_document, a.e_remark , a.i_status, a.i_bagian, b.e_bagian_name
    from tm_stockopname_material a
    inner join tr_bagian b on (a.i_bagian = b.i_bagian and a.id_company = b.id_company) 
    where a.id = '$id' ", false);
        return $this->db->get();
    }

    public function datadetail_edit($id)
    {
        return $this->db->query("
          select a.id_material as id, b.i_material, b.e_material_name, 
          c.e_satuan_name, a.n_quantity, a.e_remark
          from tm_stockopname_material_item a
          inner join tr_material b on (a.id_material = b.id)
          inner join tr_satuan c on (b.i_satuan_code = c.i_satuan_code and b.id_company = c.id_company)
          where id_document = '$id'
        ", FALSE);
    }

    public function updateheader($id, $eremarkh)
    {
        $data = array(
            'e_remark' => $eremarkh,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_stockopname_material', $data);
    }

    public function hapusdetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_stockopname_material_item');
    }
}
/* End of file Mmaster.php */