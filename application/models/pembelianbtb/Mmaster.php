<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany  = $this->id_company;

        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "and d_btb BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }

        $cek = $this->db->query("
            SELECT
                bagian_pembuat as i_bagian
            FROM
                tm_btb
            WHERE
                i_status <> '5'
                $where
                AND bagian_pembuat IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'                        
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND username = '" . $this->session->userdata('username') . "')
        ", FALSE);
        if ($this->session->userdata('i_departement') == '4' || $this->session->userdata('i_departement') == '1' || $this->session->userdata('i_departement') == '2') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.bagian_pembuat = '$i_bagian' ";
            } else {
                $bagian = "AND a.bagian_pembuat IN (SELECT
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
        $datatables->query("WITH cte AS (
                SELECT
                    DISTINCT
                    0 AS no,
                    a.id,
                    i_btb,
                    to_char(d_btb, 'dd-mm-yyyy') AS d_btb,
                    ab.e_bagian_name,
                    i_sj_supplier,
                    i_pp,
                    to_char(d_pp, 'dd-mm-yyyy') AS d_pp,
                    i_op,
                    to_char(d_op, 'dd-mm-yyyy') AS d_op,
                    a.e_supplier_name,
                    a.e_remark,
                    a.i_status,
                    e_status_name,
                    a.id_company,
                    label_color,
                    g.i_level,
			        l.e_level_name,
                    '$i_menu' AS i_menu,
                    '$folder' AS folder,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto
                FROM
                    tm_btb a
                INNER JOIN tr_bagian ab ON (ab.i_bagian = a.i_bagian AND a.id_company = ab.id_company)
                INNER JOIN tm_btb_item b ON
                    (b.id_btb = a.id)
                INNER JOIN tm_opbb_item d ON
                    (d.id_op = b.id_op and b.i_material = d.i_material)
                INNER JOIN tm_opbb c ON
                    (c.id = d.id_op)
                INNER JOIN tm_pp e ON
                    (e.id = d.id_pp)
                INNER JOIN tr_status_document f ON
                    (f.i_status = a.i_status)
                LEFT JOIN tr_menu_approve g ON
                    (a.i_approve_urutan = g.n_urut
                    AND g.i_menu = '$i_menu')
                LEFT JOIN public.tr_level l ON
                    (g.i_level = l.i_level)
                WHERE
                    a.i_status <> '5'
                AND
                    a.id_company = '$idcompany'
                    $where
                    $bagian
                ORDER BY 
                    a.id DESC
            )
            select no, id, i_btb, d_btb, e_bagian_name, i_sj_supplier, string_agg(i_pp || ' (' || d_pp || ') ', ', ') as i_pp, i_op, d_op,
            e_supplier_name, e_remark, i_status, e_status_name, id_company, label_color, i_menu, folder, dfrom, dto, i_level, e_level_name
            from cte
            group by  no, id, i_btb, d_btb, e_bagian_name, i_sj_supplier, i_op, d_op,
            e_supplier_name, e_remark, i_status, e_status_name, id_company, label_color, i_menu, folder, dfrom, dto, i_level, e_level_name, e_bagian_name
            order by id desc
        ", FALSE);

        /* $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        }); */

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id      = trim($data['id']);
            $i_menu  = $data['i_menu'];
            $i_status = $data['i_status'];
            $i_level = $data['i_level'];
            $folder  = $data['folder'];
            $dfrom   = $data['dfrom'];
            $dto     = $data['dto'];
            $data    = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 5) && $i_status == '6') {
                // $data .= "<a href=\"#\" title='Print Harga' onclick='printx(\"$iop\",\"$idpp\",\"$idop\",\"#main\"); return false;'><i class='ti-printer mr-3'></i></a>";
                $data .= "<a href=\"#\" title='Print BTB' onclick='printnonharga(\"$id\",\"#main\"); return false;'><i class='ti-printer fa-lg text-success mr-3'></i></a>";
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->session->userdata('i_level') || $this->session->userdata('i_level') == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box fa-lg text-primary mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 3) && ($i_status != '1' && $i_status != '7' && $i_status != '9')) {
                $data .= "<a href=\"#\" title='Edit SJ Supplier' onclick='show(\"$folder/cform/edit_sj/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg text-primary mr-3'></i></a>";
            }

            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close fa-lg text-danger mr-3'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('id_company');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');

        return $datatables->generate();
    }

    function dataop($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "and d_op BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }

        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and = "and d_pp BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and = "";
        }

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_pp
            WHERE
                i_status <> '5'
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '" . $this->session->userdata('i_departement') . "'
                        AND i_level = '" . $this->session->userdata('i_level') . "'
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND username = '" . $this->session->userdata('username') . "')
                AND id_company = '" . $this->session->userdata('id_company') . "'
        ", FALSE);
        if ($this->session->userdata('i_departement') == '4' || $this->session->userdata('i_departement') == '1' || $this->session->userdata('i_departement') == '2') {
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
                        AND i_level = '" . $this->session->userdata('i_level') . "'
                        AND id_company = '" . $this->session->userdata('id_company') . "'
                        AND username = '" . $this->session->userdata('username') . "')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                DISTINCT 
                0 AS no,
                a.id,
                i_op,
                to_char(d_op, 'dd-mm-yyyy') AS d_op,
                e_bagian_name,
                e_supplier_name,
                string_agg(CASE WHEN b.i_material_supplier ISNULL THEN b.i_material ELSE b.i_material_supplier END , ', ') AS i_material_supplier,
                string_agg(m.e_material_name, '| ') AS e_material_name,
                sum(n_quantity) AS qty,
                sum(n_quantity-COALESCE(qty_btb,0)) AS sisa,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto                
            FROM
                tm_opbb a
            INNER JOIN tm_opbb_item b ON
                (b.id_op = a.id)
            INNER JOIN tm_pp d ON d.id = b.id_pp
            INNER JOIN tr_bagian c ON
                (c.i_bagian = d.i_bagian AND d.id_company = c.id_company)
            LEFT JOIN tr_material m ON (m.id_company = b.id_company AND m.i_material = b.i_material)
            LEFT JOIN (SELECT DISTINCT id_op,i_material, n_quantity AS qty_btb, a.id_company  FROM tm_btb_item a, tm_btb b WHERE a.id_btb = b.id AND a.id_company = '4' AND b.i_status IN ('1','2','3')) op ON 
            (op.i_material = b.i_material AND b.id_op = op.id_op AND op.id_company = b.id_company)
            WHERE
                a.i_status = '6'
                AND a.f_op_close = 'f'
                AND (b.n_quantity - COALESCE(qty_btb, 0)) > 0
                AND a.f_op_close = 'f'
                AND a.id_company = '$this->id_company'
                $where 
            GROUP BY 
                2,3,4,5
            ORDER BY 
                3 DESC
        ", FALSE);

        $datatables->edit('i_material_supplier', function ($data) {
            return '<span>' . preg_replace('/(.+?),(.+?),(.+?),(.+?),(.+?),/', '$1,$2,$3,$4,$5<br>', $data['i_material_supplier']) . '</span>';
        });

        $datatables->edit('e_material_name', function ($data) {
            $nama = str_replace("|", "<br>", $data['e_material_name']);
            return "<span>$nama</span>";
        });

        $datatables->add('action', function ($data) {
            $id      = trim($data['id']);
            $i_menu  = $data['i_menu'];
            $folder  = $data['folder'];
            $dfrom   = $data['dfrom'];
            $dto     = $data['dto'];
            $data    = '';
            if (check_role($i_menu, 1)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/tambah/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='fa-lg ti-new-window'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');

        return $datatables->generate();
    }

    public function gudang()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata("id_company"));
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

    public function getdataop($id)
    {
        return $this->db->query(
            "SELECT
                DISTINCT 
                a.id,
                i_op,
                b.id_pp,
                to_char(d_op, 'dd-mm-yyyy') AS d_op,
                c.i_pp,
                to_char(d_op, 'dd-mm-yyyy') AS d_pp,
                a.i_supplier,
                a.e_supplier_name,
                d.i_bagian
            FROM
                tm_opbb a
            INNER JOIN tm_opbb_item b ON
                (b.id_op = a.id)
            INNER JOIN tm_pp d ON d.id = b.id_pp
            INNER JOIN tm_pp c ON
                (c.id = b.id_pp)
            WHERE
                a.id = '$id'
                AND a.i_status = '6'
                AND n_sisa > 0
        ", FALSE);
    }

    public function getdataopitem($id)
    {
        return $this->db->query(
            "SELECT
                e_bagian_name,
                b.i_material,
                e_material_name,
                e_satuan_name,
                /* n_sisa, */
                (b.n_quantity - COALESCE (g.n_quantity,0)) AS n_sisa,
                v_price,
                b.id_pp,
                b.i_satuan_code,
                f.e_operator,
                f.n_faktor,
                d.i_satuan_code as i_satuan_code_konversi,
                /* cast(n_sisa * e.n_toleransi / 100 as decimal(10,2)) as toleransi,
                cast(n_sisa +  (n_sisa * e.n_toleransi / 100) as decimal(10,2)) as maximum */
                CAST((b.n_quantity - COALESCE (g.n_quantity,0)) * e.n_toleransi / 100 AS decimal(10, 2)) AS toleransi,
	            CAST((b.n_quantity - COALESCE (g.n_quantity,0)) + ((b.n_quantity - COALESCE (g.n_quantity,0)) * e.n_toleransi / 100) AS decimal(10, 2)) AS maximum
            FROM
                tm_opbb a
            INNER JOIN tm_opbb_item b ON
                (b.id_op = a.id)
            INNER JOIN tr_bagian c ON
                (c.i_bagian = a.i_bagian 
                AND a.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (d.i_material = b.i_material 
                AND b.id_company = d.id_company)
            LEFT JOIN tr_material_konversi f ON
                (d.id = f.id_material 
                AND d.id_company = f.id_company and f.f_default = true)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = b.i_satuan_code 
                AND b.id_company = e.id_company)
            LEFT JOIN (
                SELECT b.id_op, b.i_material, b.n_quantity, b.id_company  
                FROM tm_btb a
                INNER JOIN tm_btb_item b ON (b.id_btb = a.id)
                WHERE a.i_status IN ('2','6') AND a.id_company = '$this->id_company'
            ) g ON (g.id_op = b.id_op AND b.i_material = g.i_material AND g.id_company = b.id_company)
            WHERE
                a.id = '$id'
                AND a.i_status = '6'
                AND (b.n_quantity - COALESCE (g.n_quantity,0)) > 0
                AND d.i_kode_group_barang = CASE
                    WHEN (
                    SELECT
                        i_kode_group_barang
                    FROM
                        tr_type
                    WHERE
                        i_departement = '$this->i_departement') ISNULL THEN d.i_kode_group_barang
                    ELSE (
                    SELECT
                        i_kode_group_barang
                    FROM
                        tr_type
                    WHERE
                        i_departement = '$this->i_departement')
                END
            ORDER BY d.i_material, e_material_name
        ", FALSE);
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_btb');
        $this->db->from('tm_btb');
        $this->db->where('i_btb', $kode);
        $this->db->where('bagian_pembuat', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_btb');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_btb, 1, 3) AS kode 
            FROM tm_btb 
            WHERE i_status <> '5'
            AND bagian_pembuat = '$ibagian'
            AND id_company = '$this->id_company'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BTB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_btb, 10, 6)) AS max
            FROM
                tm_btb
            WHERE to_char (d_btb, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND bagian_pembuat = '$ibagian'
            AND substring(i_btb, 1, 3) = '$kode'
            AND substring(i_btb, 5, 2) = substring('$thbl',1,2)
            AND id_company = '$this->id_company'
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
				from tm_btb a
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
                    $query = $this->db->query("SELECT id_op, i_material, n_quantity, id_pp 
                        FROM tm_btb_item
                        WHERE id_btb = '$id' ", FALSE);
                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $key) {
                            $this->db->query("UPDATE
                                    tm_opbb_item
                                SET
                                    n_sisa = n_sisa - $key->n_quantity
                                WHERE
                                    id_op = '$key->id_op'
                                    AND id_pp = '$key->id_pp'
                                    AND i_material = '$key->i_material'
                                    AND id_company = '$this->id_company'
                            ", FALSE);
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_btb');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_btb', $data);
        /* if ($istatus=='6') {
            $query = $this->db->query("
                SELECT id_op, i_material, n_quantity, id_pp 
                FROM tm_btb_item
                WHERE id_btb = '$id' ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $this->db->query("
                        UPDATE
                            tm_opbb_item
                        SET
                            n_sisa = n_sisa - $key->n_quantity
                        WHERE
                            id_op = '$key->id_op'
                            AND id_pp = '$key->id_pp'
                            AND i_material = '$key->i_material'
                            AND id_company = '".$this->session->userdata('id_company')."'
                    ", FALSE);
                }
            }
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
        $this->db->update('tm_btb', $data); */
    }

    public function bagian($cari, $ibagian)
    {
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
        /* return $this->db->query("
            SELECT
                *
            FROM
                tr_bagian
            WHERE
                e_bagian_name ILIKE '%$cari%'
                AND i_type IN (
                SELECT
                    i_type
                FROM
                    tr_bagian
                WHERE
                    i_bagian = '$ibagian')
                AND id_company = '$this->id_company'
                AND id_company = '" . $this->session->userdata('id_company') . "'
        ", FALSE); */
    }

    public function satuan()
    {
        return $this->db->order_by('e_satuan_name', 'ASC')->where('f_status', 't')->where('id_company', $this->session->userdata('id_company'))->get('tr_satuan');
    }

    public function insertheader($id, $ibtb, $dbtb, $isj, $dsj, $isupplier, $ibagian, $remark, $esupplier, $igudang)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id'                => $id,
            'i_btb'             => $ibtb,
            'd_btb'             => $dbtb,
            'i_sj_supplier'     => $isj,
            'd_sj_supplier'     => $dsj,
            'i_supplier'        => $isupplier,
            'i_bagian'          => $igudang,
            'e_supplier_name'   => $esupplier,
            'bagian_pembuat'    => $igudang,
            'e_remark'          => $remark,
            'id_company'        => $idcompany,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_btb', $data);
    }

    public function updateheader($id, $ibtb, $dbtb, $isj, $dsj, $isupplier, $ibagian, $remark, $esupplier, $igudang)
    {
        $data = array(
            'i_btb'             => $ibtb,
            'd_btb'             => $dbtb,
            'i_sj_supplier'     => $isj,
            'd_sj_supplier'     => $dsj,
            'i_supplier'        => $isupplier,
            'i_bagian'          => $ibagian,
            'e_remark'          => $remark,
            'd_update'          => current_datetime(),
            'e_supplier_name'   => $esupplier,
            'i_status'          => '1',
            'bagian_pembuat'    => $igudang,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_btb', $data);
    }

    public function update_sj($id, $isj, $dsj)
    {
        $data = array(
            'i_sj_supplier'     => $isj,
            'd_sj_supplier'     => $dsj,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_btb', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_btb', $id);
        $this->db->delete('tm_btb_item');
    }

    public function insertdetail($id, $idop, $imaterial, $isatuaneks, $nquantityeks, $isatuan, $nquantity, $price, $idpp, $e_note, $n_toleransi, $eoperator, $nfaktor, $ikonversi)
    {
        $idcompany  = $this->session->userdata('id_company');

        if ($isatuaneks != '' || $isatuaneks != null) {
            $satuaneks = $isatuaneks;
        } else {
            $satuaneks = $isatuan;
        }
        if (($nquantityeks != '' || $nquantityeks != null) && $nquantityeks > 0) {
            $quantityex = $nquantityeks;
        } else {
            $quantityex = $nquantity;
        }
        $data = array(
            'id_btb'            => $id,
            'id_op'             => $idop,
            'i_material'        => $imaterial,
            'i_satuan_code_eks' => $satuaneks,
            'n_quantity_eks'    => $quantityex,
            'i_satuan_code'     => $isatuan,
            'n_quantity'        => $nquantity,
            'n_quantity_sisa'   => $nquantity,
            'v_price'           => $price,
            'n_retur_sisa'      => $nquantity,
            'id_company'        => $idcompany,
            'id_pp'             => $idpp,
            'e_remark'          => $e_note,
            'n_toleransi'       => $n_toleransi,
            'd_entry'           => current_datetime(),
            'e_operator'        => $eoperator,
            'n_faktor'          => $nfaktor,
            'i_satuan_code_konversi' => $ikonversi, 
        );
        $this->db->insert('tm_btb_item', $data);
    }

    public function getdata($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.id,
                i_btb,
                to_char(d_btb, 'dd-mm-yyyy') AS d_btb,
                a.i_supplier,
                a.e_supplier_name,
                i_sj_supplier,
                to_char(d_sj_supplier, 'dd-mm-yyyy') AS d_sj,
                a.e_remark,
                i_op,
                to_char(d_op, 'dd-mm-yyyy') AS d_op,
                i_pp,
                to_char(d_pp, 'dd-mm-yyyy') AS d_pp,
                c.id_op,
                a.i_status,
                a.i_bagian,
                f.e_bagian_name,
                bagian_pembuat,
                g.e_bagian_name AS e_bagian
            FROM
                tm_btb a
            INNER JOIN tm_btb_item b ON
                (b.id_btb = a.id)
            INNER JOIN tm_opbb_item c ON
                (c.id_op = b.id_op
                AND b.i_material = c.i_material 
                AND c.id_company = b.id_company)
            INNER JOIN tm_opbb d ON
                (d.id = c.id_op)
            INNER JOIN tm_pp e ON
                (e.id = c.id_pp)
            INNER JOIN tr_bagian f ON
                (f.i_bagian = a.i_bagian 
                AND a.id_company = f.id_company)
            LEFT JOIN tr_bagian g ON 
                (a.bagian_pembuat = g.i_bagian 
                AND g.id_company = a.id_company)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    public function getdataitem($id)
    {
        return $this->db->query("SELECT
                a.i_material,
                e_material_name,
                n_quantity_eks,
                a.n_quantity,
                a.i_satuan_code_eks,
                a.i_satuan_code,
                e_satuan_name,
                a.v_price,
                e_bagian_name,
                /* n_sisa, */
                (COALESCE (f.n_quantity,0) - COALESCE (g.n_quantity,0)) as n_sisa,
                a.id_pp,
                a.e_remark,
                a.n_toleransi as toleransi,
                a.e_operator,
                a.n_faktor,
                a.i_satuan_code_konversi,
                /* cast(n_sisa +  (n_sisa * c.n_toleransi / 100) as decimal(10,2)) as maximum, */
                CAST((COALESCE (f.n_quantity,0) - COALESCE (g.n_quantity,0)) + ((COALESCE (f.n_quantity,0) - COALESCE (g.n_quantity,0)) * c.n_toleransi / 100) AS decimal(10, 2)) AS maximum,
                coalesce(f.n_quantity,0) qty_op
            FROM
                tm_btb_item a
            INNER JOIN tr_material b ON
                (b.i_material = a.i_material 
                AND a.id_company = b.id_company)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = a.i_satuan_code 
                AND a.id_company = c.id_company)
            INNER JOIN tm_opbb_item f ON 
                (
                (f.id_op = a.id_op 
                AND a.i_material = f.i_material 
                AND a.id_company = f.id_company)
                and (a.id_pp = f.id_pp or a.id_pp isnull) )
            INNER JOIN tm_opbb d ON
                (d.id = f.id_op)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = d.i_bagian 
                AND d.id_company = e.id_company)            
            LEFT JOIN (
                SELECT b.id_op, b.i_material, b.n_quantity, b.id_company  
                FROM tm_btb a
                INNER JOIN tm_btb_item b ON (b.id_btb = a.id)
                WHERE a.i_status IN ('2','6') AND a.id <> '$id' AND a.id_company = '$this->id_company'
            ) g ON (g.id_op = f.id_op AND f.i_material = g.i_material AND g.id_company = f.id_company)
            WHERE
                a.id_btb = '$id'
            order by e_material_name
        ", FALSE);
    }

    public function cetak_btb($idbtb)
    {
        return $this->db->query(
            "
                select a.id, a.i_btb, to_char(d_btb, 'dd-mm-yyyy') as d_btb, a.i_sj_supplier,  to_char(d_sj_supplier, 'dd-mm-yyyy') as d_sj_supplier  , e_supplier_name, b.i_op
                , c.e_bagian_name , d.e_lokasi_name 
                from tm_btb a 
                inner join (
                    select distinct a.id_btb, b.i_op as i_op from tm_btb_item a
                    inner join tm_opbb b on (a.id_op = b.id and a.id_company = b.id_company)
                    where id_btb = '$idbtb'
                ) as b on (a.id = b.id_btb)
                inner join tr_bagian c on (c.i_bagian = a.i_bagian and a.id_company = c.id_company)
                inner join tr_lokasi d on (c.i_lokasi = d.i_lokasi and c.id_company = d.id_company)
                where a.id = '$idbtb'
            ",
            FALSE
        );
    }

    public function cetak_item_btb($idbtb)
    {
        return $this->db->query(
            "
                select ROW_NUMBER() OVER(order by c.e_material_name) as no, a.i_material, c.e_material_name, b.e_satuan_name , a.n_quantity from tm_btb_item a 
                inner join tr_satuan b on (a.i_satuan_code = b.i_satuan_code and a.id_company = b.id_company)
                inner join tr_material c on (a.i_material = c.i_material and a.id_company = c.id_company)
                where a.id_btb = '$idbtb'
            ",
            FALSE
        );
    }

    public function get_approve($idbtb)
    {
        return $this->db->query("SELECT
                e_approve ||', '|| to_char(d_approve, 'dd FMMonth YYYY') approve
            FROM
                tm_menu_approve
            WHERE
                i_menu = '$this->i_menu' AND i_document = '$idbtb' AND e_database = 'tm_btb'
            ORDER BY
                id,
                d_approve ASC
            ;
        ");
    }

    public function get_sisa($id)
    {
        return $this->db->query(
            "SELECT b.n_quantity, a.n_quantity as n_quantity_pemenuhan, 
                b.n_quantity - COALESCE((
                    SELECT sum(n_quantity) FROM tm_btb aa, tm_btb_item bb 
                    WHERE bb.id_btb = aa.id AND aa.i_status = '6' 
                    AND bb.id_op = b.id_op AND b.i_material = bb.i_material
                    AND aa.id_company = '$this->id_company'
                ),0) AS n_quantity_sisa 
            FROM tm_btb_item a
            INNER JOIN tm_opbb_item b ON (b.id_op = a.id_op AND a.i_material = b.i_material)
            WHERE a.id_btb = '$id';"
        );
    }
}

/* End of file Mmaster.php */
