<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($folder, $i_menu, $dfrom, $dto)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 as no,
                a.i_dt as id,
                a.i_dt_id,
                to_char(a.d_dt, 'dd-mm-yyyy') as d_dt,
                '[' || b.i_area || '] - ' || b.e_area AS e_area,
                v_jumlah::money AS v_jumlah,
                a.i_status,
                c.e_status_name,
                c.label_color,
                f.i_level,
                l.e_level_name,
                n_print,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' as i_menu,
                '$folder' AS folder
            FROM
                tm_dt a 
            JOIN tr_area b 
                ON (a.i_area = b.id) 
            JOIN tr_status_document c 
                ON (a.i_status = c.i_status) 
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE 
                a.i_company = '$this->id_company' AND
                a.i_status <> '5'AND
                a.d_dt BETWEEN to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy')
                AND b.i_area IN (SELECT i_area FROM public.tm_user_area WHERE id_company = '$this->id_company' AND username = '$this->username')
            ORDER BY
                a.d_dt asc
        ", false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print'] == '0') {
                $data = "<span class='label label-primary'>Belum</span>";
            } else {
                $data = "<span class='label label-info'>Sudah " . $data['n_print'] . 'x' . "</span>";
            }
            return $data;
        });

        $datatables->add('action', function ($data) {
            $id = trim($data['id']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom = $data['dfrom'];
            $dto = $data['dto'];
            $i_level = $data['i_level'];
            $data = '';

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success mr-3 fa-lg'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 5)) {
                if ($i_status == '6' or $i_status == '4') {
                    $id = encrypt_url($id);
                    $data .= "<a href=\"#\" title='Cetak DT' onclick='cetak(\"$id\"); return false;'><i class='ti-printer fa-lg mr-2 text-warning'></i></a>";
                    // $data .= "<a href=\"" . base_url($folder . '/cform/cetak/' . encrypt_url($id)) . "\" title='Print' target='_blank'><i class='ti-printer text-warning mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3 fa-lg'></i></a>";
            }
            return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function bagian()
    {
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function area()
    {
        return $this->db->query(
            "SELECT id, i_area, e_area FROM tr_area WHERE f_status = 't' 
            AND i_area IN (SELECT i_area FROM public.tm_user_area WHERE id_company = '$this->id_company' AND username = '$this->username')
            ORDER BY 2,3"
        );
    }

    public function doc($imenu)
    {
        $this->db->select('doc_qe');
        $this->db->from('public.tm_menu');
        $this->db->where('i_menu', $imenu);
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian, $iarea, $id)
    {
        $area = $this->db->query("SELECT i_area FROM tr_area WHERE id = '$iarea' ")->row()->i_area;
        $cek = $this->db->query(
            "SELECT substring(i_dt_id, 1, 2) AS kode FROM tm_dt WHERE i_status <> '5' AND i_bagian = '$ibagian' AND i_company = '$this->id_company' AND i_area = '$iarea' ORDER BY i_dt DESC LIMIT 1"
        );

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'DT';
        }
        if (strlen($id) > 0) {
            $query = $this->db->query(
                "SELECT max(substring(i_dt_id, 12, 4)) AS max FROM tm_dt WHERE to_char (d_dt, 'yyyy') = '$tahun' 
                AND i_status <> '5' AND i_bagian = '$ibagian' AND i_company = '$this->id_company'
                AND i_dt_id ILIKE '%$kode%' AND i_dt <> '$id' "
            );
        } else {
            $query = $this->db->query(
                "SELECT max(substring(i_dt_id, 12, 4)) AS max FROM tm_dt WHERE to_char (d_dt, 'yyyy') = '$tahun' 
                AND i_status <> '5' AND i_bagian = '$ibagian' AND i_company = '$this->id_company'
                AND i_dt_id ILIKE '%$kode%'"
            );
        }
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
            $number = $kode . "-" . $area . '-' . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "0001";
            $number = $kode . "-" . $area . '-' . $thbl . "-" . $number;
            return $number;
        }
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_keluar_pengadaan');
        $this->db->from('tm_dt');
        $this->db->where('i_keluar_pengadaan', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select('i_keluar_pengadaan');
        $this->db->from('tm_dt');
        $this->db->where('i_keluar_pengadaan', $kode);
        $this->db->where('i_keluar_pengadaan <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI BARANG  ----------*/

    public function nota($cari, $i_area)
    {
        return $this->db->query(
            "SELECT a.id, a.i_document, a.d_document, c.i_customer, '[ ' || c.i_customer || ' ] - ' || c.e_customer_name AS e_customer_name
            FROM tm_nota_penjualan a
            INNER JOIN tr_customer c ON (c.id = a.id_customer)
            INNER JOIN (SELECT DISTINCT id_area, b.id_document FROM tm_sj a
            INNER JOIN tm_sj_item c ON (c.id_document = a.id)
            INNER JOIN tm_nota_penjualan_item b ON (b.id_document_reff = c.id)) b ON (b.id_document = a.id)
            WHERE b.id_area = '$i_area' AND (a.i_document ILIKE '%$cari%' OR c.e_customer_name ILIKE '%$cari%' OR c.i_customer ILIKE '%$cari%')"
        );
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailnota($id)
    {
        return $this->db->query(
            "SELECT b.e_customer_name, d_document as d_nota, to_char(d_document, 'DD FMMonth YYYY') AS d_document, to_char(d_jatuh_tempo, 'DD FMMonth YYYY') AS d_jatuh_tempo, v_bersih, v_sisa 
            FROM tm_nota_penjualan a
            INNER JOIN tr_customer b ON (b.id = a.id_customer)
            WHERE a.id = '$id'"
        );
    }

    /*----------  SIMPAN DATA  ----------*/
    public function runningid()
    {
        $this->db->select('max(i_dt) AS id');
        $this->db->from('tm_dt');
        return $this->db->get()->row()->id + 1;
    }

    public function create_header($id, $i_dt_id, $ibagian, $i_area, $d_dt, $v_jumlah)
    {
        $data = array(
            'i_dt' => $id,
            'i_dt_id' => $i_dt_id,
            'i_bagian' => $ibagian,
            'i_company' => $this->id_company,
            'i_area' => $i_area,
            'd_dt' => $d_dt,
            'v_jumlah' => $v_jumlah,
        );
        $this->db->insert('tm_dt', $data);
    }

    public function create_detail($id, $i_nota, $d_nota, $v_sisa, $v_bayar, $n_item_no)
    {
        $data = array(
            'i_dt' => $id,
            'i_nota' => $i_nota,
            'd_nota' => $d_nota,
            'v_sisa' => $v_sisa,
            'v_bayar' => $v_bayar,
            'n_item_no' => $n_item_no,
        );
        $this->db->insert('tm_dt_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query(
            "SELECT DISTINCT a.*, to_char(a.d_dt, 'dd-mm-yyyy') as d_dt, b.e_bagian_name, '['||c.i_area||'] - '||c.e_area as e_area_name 
            FROM tm_dt a, tr_bagian b, tr_area c WHERE a.i_bagian = b.i_bagian AND b.id_company = a.i_company AND a.i_area = c.id AND a.i_dt = '$id'
            "
        );
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query(
            "SELECT a.*, b.i_document, c.i_customer, c.e_customer_name AS e_customer, '[ ' || c.i_customer || ' ] - ' || c.e_customer_name AS e_customer_name, e_city_name,
            b.d_document, to_char(b.d_document, 'DD FMMonth YYYY') as d_nota, to_char(d_jatuh_tempo, 'DD FMMonth YYYY') AS d_jatuh_tempo
            FROM tm_dt_item a
            INNER JOIN tm_nota_penjualan b ON (b.id = a.i_nota)
            INNER JOIN tr_customer c ON (c.id = b.id_customer)
            INNER JOIN tr_city d ON (d.id = c.id_city)
            WHERE a.i_dt = '$id' ORDER BY n_item_no"
        );
    }


    public function update_header($id, $i_dt_id, $ibagian, $i_area, $d_dt, $v_jumlah)
    {
        $data = array(
            'i_dt_id' => $i_dt_id,
            'i_bagian' => $ibagian,
            'i_company' => $this->id_company,
            'i_area' => $i_area,
            'd_dt' => $d_dt,
            'v_jumlah' => $v_jumlah,
            'd_update' => current_datetime()
        );
        $this->db->where('i_dt', $id);
        $this->db->update('tm_dt', $data);
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM tm_dt_item WHERE i_dt = '$id'");
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query(
                "SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_dt a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.i_dt = '$id'
				GROUP BY 1,2")->row();
            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0) {
                    $data = array(
                        'i_status' => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan' => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status' => $istatus,
                        'i_approve_urutan' => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan' => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_dt');", FALSE);
            }
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('i_dt', $id);
        $this->db->update('tm_dt', $data);
    }

    public function updateprint($id)
    {
        $this->db->query("UPDATE tm_dt SET n_print = n_print + 1 WHERE i_dt = $id");
    }
}
/* End of file Mmaster.php */