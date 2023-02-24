<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($folder, $i_menu, $dfrom, $dto)
    {
        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_giro_masuk
            WHERE
                i_status <> '5'
                and d_giro BETWEEN to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy') AND i_company = '$this->id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->i_departement'
                        AND username = '$this->username'
                        AND id_company = '$this->id_company')

        ", FALSE);
        if ($this->i_departement == '1') {
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
                        AND id_company = '$this->id_company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                0 as no,
                a.i_giro as id,
                a.i_giro_id,
                to_char(a.d_giro, 'dd-mm-yyyy') as d_giro,
                '[' || b.i_area || '] - ' || b.e_area AS e_area,
                d.e_bagian_name,
                c.i_customer||' - '|| c.e_customer_name AS e_customer_name,
                a.v_jumlah::money AS v_jumlah,
                t.i_dt_id,
                a.i_status,
                e.e_status_name,
                e.label_color,
                f.i_level,
                l.e_level_name,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' as i_menu,
                '$folder' AS folder
            FROM
                tm_giro_masuk a 
            INNER JOIN tr_area b 
                ON (a.i_area = b.id)
            INNER JOIN tr_customer c ON (c.id = a.i_customer)
            inner join tr_bagian d ON (d.i_bagian = a.i_bagian and d.id_company = a.i_company)
            -- INNER JOIN tr_salesman d ON (d.id = a.i_salesman)
            JOIN tr_status_document e
                ON (a.i_status = e.i_status) 
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            LEFT JOIN tm_dt t ON (t.i_dt = a.i_dt)
            WHERE 
                a.i_company = '$this->id_company' AND
                a.i_status <> '5'AND
                a.d_giro BETWEEN to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy')
                $bagian
            ORDER BY
                a.d_giro asc"
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
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
            "SELECT id, i_area, e_area FROM tr_area WHERE f_status = 't' ORDER BY 2,3"
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
            "SELECT substring(i_giro_id, 1, 2) AS kode FROM tm_giro_masuk WHERE i_status <> '5' AND i_bagian = '$ibagian' AND i_company = '$this->id_company' AND i_area = '$iarea' ORDER BY i_giro DESC LIMIT 1"
        );

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'GR';
        }
        if (strlen($id) > 0) {
            $query = $this->db->query(
                "SELECT max(substring(i_giro_id, 12, 4)) AS max FROM tm_giro_masuk WHERE to_char (d_giro, 'yyyy') = '$tahun' 
                AND i_status <> '5' AND i_bagian = '$ibagian' AND i_company = '$this->id_company'
                AND i_giro_id ILIKE '%$kode%' AND i_giro <> '$id' "
            );
        } else {
            $query = $this->db->query(
                "SELECT max(substring(i_giro_id, 12, 4)) AS max FROM tm_giro_masuk WHERE to_char (d_giro, 'yyyy') = '$tahun' 
                AND i_status <> '5' AND i_bagian = '$ibagian' AND i_company = '$this->id_company'
                AND i_giro_id ILIKE '%$kode%'"
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
            $number = $kode . " " . $area . $thbl . $number;
            return $number;
        } else {
            $number = "0001";
            $number = $kode . " " . $area . $thbl . $number;
            return $number;
        }
    }
    /*----------  CARI Customer  ----------*/

    public function customer($cari, $i_area)
    {
        return $this->db->query(
            "SELECT id, i_customer, '[ ' || i_customer || ' ] - ' || e_customer_name AS name
            FROM tr_customer
            WHERE id_area = '$i_area' AND (e_customer_name ILIKE '%$cari%' OR i_customer ILIKE '%$cari%') AND f_status = 't' AND id_company = '$this->id_company'
            ORDER BY 3"
        );
    }

    /*----------  CARI Salesman  ----------*/

    public function salesman($cari, $i_area, $i_customer, $d_kum)
    {
        return $this->db->query(
            "SELECT DISTINCT b.id, b.i_sales, '[ ' || b.i_sales || ' ] - ' || b.e_sales AS name
            FROM tr_customer_salesman a
            INNER JOIN tr_salesman b ON (b.id = a.id_salesman)
            WHERE a.id_company = '$this->id_company' AND a.id_customer = '$i_customer' AND a.id_area = '$i_area' AND a.e_periode = '$d_kum' AND a.f_status = 't'
            ORDER BY 3"
        );
    }

    /*----------  CARI DT  ----------*/

    public function dt($cari, $i_area, $i_customer)
    {
        return $this->db->query(
            "SELECT DISTINCT a.i_dt AS id, a.i_dt_id ||' - '||to_char(d_dt, 'DD FMMonth YYYY') AS name
            FROM tm_dt a 
            INNER JOIN tm_dt_item b on (b.i_dt = a.i_dt)
            INNER JOIN tm_nota_penjualan c on (c.id = b.i_nota)
            WHERE (i_dt_id ILIKE '%$cari%')
            AND a.i_company = '$this->id_company' 
            AND a.i_status = '6' 
            AND a.i_area = '$i_area'
            AND c.id_customer = '$i_customer'
            ORDER BY 1 ASC"
        );
    }

    /** Cek Apakah Data Sudah Ada */
    public function cek_code()
    {
        $i_giro_id = str_replace("_", "", $this->input->post('i_giro_id'));
        $i_area = $this->input->post('i_area');
        return $this->db->query("
            SELECT 
                i_giro_id
            FROM 
                tm_giro_masuk 
            WHERE 
                upper(trim(i_giro_id)) = upper(trim('$i_giro_id'))
                AND i_area = '$i_area'
                AND i_company = '$this->id_company'
        ", FALSE);
    }

    /** Cek Apakah Data Sudah Ada Pas Edit */
    public function cek_edit()
    {
        $i_giro_id = str_replace("_", "", $this->input->post('i_giro_id'));
        $i_giro_id_old = str_replace("_", "", $this->input->post('i_giro_id_old'));
        $i_area = $this->input->post('i_area');
        return $this->db->query("
             SELECT 
                 i_giro_id
             FROM 
                 tm_giro_masuk 
             WHERE 
                 trim(upper(i_giro_id)) <> trim(upper('$i_giro_id_old'))
                 AND trim(upper(i_giro_id)) = trim(upper('$i_giro_id'))
                 AND i_area = '$i_area'
                 AND i_company = '$this->id_company'
         ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/
    public function runningid()
    {
        $this->db->select('max(i_giro) AS id');
        $this->db->from('tm_giro_masuk');
        return $this->db->get()->row()->id + 1;
    }

    public function create($id, $i_giro_id, $i_bagian, $i_area, $i_customer, $e_giro_bank, $i_dt, $d_giro, $d_giro_duedate, $d_giro_terima, $v_jumlah, $e_giro_description)
    {
        $data = array(
            'i_giro' => $id,
            'i_giro_id' => $i_giro_id,
            'i_company' => $this->id_company,
            'i_bagian' => $i_bagian,
            'i_area' => $i_area,
            'i_customer' => $i_customer,
            'e_giro_bank' => $e_giro_bank,
            'i_dt' => $i_dt,
            'd_giro' => $d_giro,
            'd_giro_duedate' => $d_giro_duedate,
            'd_giro_terima' => $d_giro_terima,
            'v_jumlah' => $v_jumlah,
            'e_giro_description' => $e_giro_description
        );
        $this->db->insert('tm_giro_masuk', $data);
    }
    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query(
            "SELECT a.*, b.i_bagian, b.e_bagian_name, c.i_area as i_area_code, c.e_area, 
            d.i_customer as i_customer_code, d.e_customer_name, a.e_giro_bank,
            f.i_dt_id, to_char(f.d_dt, 'DD FMMonth YYYY') as d_dt
            FROM tm_giro_masuk a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.i_company = b.id_company)
            INNER JOIN tr_area c ON (c.id = a.i_area)
            INNER JOIN tr_customer d ON (d.id = a.i_customer)
            LEFT JOIN tm_dt f ON (f.i_dt = a.i_dt)
            WHERE a.i_giro = '$id'
            "
        );
    }

    public function update($id, $i_giro_id, $i_bagian, $i_area, $i_customer, $e_giro_bank, $i_dt, $d_giro, $d_giro_duedate, $d_giro_terima, $v_jumlah, $e_giro_description)
    {
        $data = array(
            'i_giro_id' => $i_giro_id,
            'i_company' => $this->id_company,
            'i_bagian' => $i_bagian,
            'i_area' => $i_area,
            'i_customer' => $i_customer,
            'e_giro_bank' => $e_giro_bank,
            'i_dt' => $i_dt,
            'd_giro' => $d_giro,
            'd_giro_duedate' => $d_giro_duedate,
            'd_giro_terima' => $d_giro_terima,
            'v_jumlah' => $v_jumlah,
            'e_giro_description' => $e_giro_description,
            'd_update' => current_datetime()
        );
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro_masuk', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query(
                "SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_giro_masuk a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.i_giro = '$id'
				GROUP BY 1,2"
            )->row();
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_giro_masuk');", FALSE);
            }
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('i_giro', $id);
        $this->db->update('tm_giro_masuk', $data);
    }
}
/* End of file Mmaster.php */