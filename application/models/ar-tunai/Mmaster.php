<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($folder, $i_menu, $dfrom, $dto)
    {
        $sql_in_bagian = "SELECT i_bagian
                            FROM tr_departement_cover
                            WHERE i_departement = '$this->i_departement'
                                AND username = '$this->username'
                                AND id_company = '$this->id_company'";

        $sql_cek = "SELECT i_bagian
                    FROM tm_dt
                    WHERE i_status <> '5'
                        AND d_dt BETWEEN to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy') AND i_company = '$this->id_company'
                        AND i_bagian IN ($sql_in_bagian)";

        $cek = $this->db->query($sql_cek, FALSE);

        $bagian = "";
        if ($this->i_departement != '1') {
            $bagian = "AND a.i_bagian IN ($sql_in_bagian)";
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }  
        } 

        $datatables = new Datatables(new CodeigniterAdapter);

        $sql = "SELECT
                    a.i_tunai AS id,
                    0 AS no,
                    a.d_tunai,
                    a.i_tunai_id,
                    b.e_area,
                    c.e_customer_name,
                    ts.e_sales,
                    a.v_jumlah,
                    a.v_sisa,
                    a.i_status,
                    tsd.e_status_name,
                    tsd.label_color,
                    f.i_level,
                    l.e_level_name,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    '$i_menu' as i_menu,
                    '$folder' AS folder
                FROM tm_tunai a 
                JOIN tm_tunai_item ti ON ti.i_tunai = a.i_tunai
                JOIN tr_area b ON b.id = a.id_area
                JOIN tr_customer c ON c.id = a.id_customer
                JOIN tr_salesman ts ON ts.id = a.id_salesman
                JOIN tr_status_document tsd ON tsd.i_status = a.i_status
                LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
                LEFT JOIN public.tr_level l ON f.i_level = l.i_level
                WHERE a.id_company = '$this->id_company' AND
                    a.i_status <> '5'AND
                    a.d_tunai BETWEEN to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy')
                    $bagian
                ORDER BY a.d_tunai asc";
        
        // var_dump($sql); die();

        $datatables->query($sql, false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        // $datatables->edit('n_print', function ($data) {
        //     if ($data['n_print'] == '0') {
        //         $data = "<span class='label label-primary'>Belum</span>";
        //     } else {
        //         $data = "<span class='label label-info'>Sudah " . $data['n_print'] . 'x' . "</span>";
        //     }
        //     return $data;
        // });

        /** reformat v_jumlah & v_sisa */
        $datatables->edit('v_jumlah', function ($data) {
            return 'Rp. ' . number_format($data['v_jumlah'], 0, ",", ".");
        });

        $datatables->edit('v_sisa', function ($data) {
            return 'Rp. ' . number_format($data['v_sisa'], 0, ",", ".");
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
        $id_company = $this->session->userdata('id_company');
        $username = $this->session->userdata('username');

        $where = "WHERE tua.id_company = '$id_company' AND tua.username = '$username'";

        $sql = "SELECT ta.id, ta.i_area, ta.e_area 
                FROM tm_user_area tua
                INNER JOIN tr_area ta ON ta.i_area = tua.i_area
                $where";
                

        return $this->db->query($sql);
    }

    public function get_all_customer($q='', $id_area=null)
    {
        $id_company = $this->session->userdata('id_company');

        $where = " AND id_company = '$id_company'";

        if ($id_area != null) {
            $where .= " AND id_area = $id_area";
        }

        if ($q != '') {
            $where .= " AND e_customer_name ILIKE '%$q%'";
        }

        $sql = "SELECT * 
                FROM tr_customer tc  
                WHERE f_status = 't' $where";

        // var_dump($sql);

        return $this->db->query($sql);
    }

    public function get_all_salesman($q='', $id_area=null, $id_customer=null)
    {
        $id_company = $this->session->userdata('id_company');

        $where = "WHERE ts.f_status = 't' AND tcs.id_company = '$id_company'";

        if ($q != '') {
            $where .= " AND ts.e_sales ILIKE '%$q%'";
        }
        
        if ($id_area != null) {
            $where .= " AND tcs.id_area = $id_area";
        }

        if ($id_customer != null) {
            $where .= " AND tcs.id_customer = '$id_customer'";
        }

        $sql = "SELECT ts.* 
                FROM tr_customer_salesman tcs 
                INNER JOIN tr_salesman ts ON ts.id = tcs.id_salesman
                $where";

        // var_dump($sql);

        return $this->db->query($sql);
    }

    public function get_all_daftar_tagihan($q='', $id_area=null)
    {
        $id_company = $this->session->userdata('id_company');

        $where = "WHERE td.i_status = '6' AND td.i_company = '$id_company'";

        if ($q != '') {
            $where .= " AND i_dt_id ILIKE '%$q%'";
        }

        if ($id_area != null) {
            $where .= " AND i_area = $id_area";
        }

        $sql = "SELECT * 
                FROM tm_dt td
                $where";

        // var_dump($sql);

        return $this->db->query($sql);
    }

    public function get_all_nota_penjualan($q='', $id_dt=null)
    {
        $id_company = $this->session->userdata('id_company');

        $where = "WHERE tnp.i_status = '6' AND tnp.id_company = '$id_company'";
        
        if ($q != '') {
            $where .= " AND i_dt_id ILIKE '%$q%'";
        }

        if ($id_customer != null) {
            $where .= " AND tnp.id_customer = $id_customer";
        }

        $sql = "SELECT * 
                FROM tm_nota_penjualan tnp  
                INNER JOIN tm_dt_item tdi ON tdi.i_nota = tnp.id
                $where";

        // var_dump($sql);

        return $this->db->query($sql);
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
        $this->db->insert('tm_tunai', $data);
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
        $this->db->insert('tm_tunai_detail', $data);
    }

    public function insert_tunai($i_tunai_id, $d_dt, $ibagian, $id_company=null, $id_area, $id_customer, $id_sales, $i_dt, $e_remark, $v_jumlah)
    {
        $id_company = $this->session->userdata('id_company');

        /** default v_sisa sama dengan v_jumlah */
        $data = [
            'i_tunai_id' => $i_tunai_id,
            'd_tunai' => $d_dt,
            'id_bagian' => $ibagian,
            'id_company' => $id_company,
            'id_area' => $id_area,
            'id_customer' => $id_customer,
            'id_salesman' => $id_sales,
            'i_dt' => $i_dt,
            'e_remark' => $e_remark,
            'v_jumlah' => $v_jumlah,
            'v_sisa' => $v_jumlah
        ];

        $this->db->insert('tm_tunai', $data);
    }

    public function update_tunai($i_tunai_id, $d_dt, $ibagian, $id_company=null, $id_area, $id_customer, $id_sales, $i_dt, $e_remark, $v_jumlah, $id)
    {
        $id_company = $this->session->userdata('id_company');

        /** default v_sisa sama dengan v_jumlah */
        $data = [
            'i_tunai_id' => $i_tunai_id,
            'd_tunai' => $d_dt,
            'id_bagian' => $ibagian,
            'id_company' => $id_company,
            'id_area' => $id_area,
            'id_customer' => $id_customer,
            'id_salesman' => $id_sales,
            'i_dt' => $i_dt,
            'e_remark' => $e_remark,
            'v_jumlah' => $v_jumlah,
            'v_sisa' => $v_jumlah
        ];

        $this->db->where('i_tunai', $id);
        $this->db->update('tm_tunai', $data);
    }

    public function insert_tunai_item($i_tunai, $id_nota, $v_jumlah, $n_item_no)
    {
        $data = [
            'i_tunai' => $i_tunai,
            'id_nota' => $id_nota,
            'v_jumlah' => $v_jumlah,
            'n_item_no' => $n_item_no
        ];

        $this->db->insert('tm_tunai_item', $data);
    }

    public function delete_tunai_item($i_tunai)
    {
        $this->db->where('i_tunai', $i_tunai);
        $this->db->delete('tm_tunai_item');
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        $sql = "SELECT a.*, 
                    tb.e_bagian_name,
                    ta.e_area,
                    ts.e_sales,
                    tc.e_customer_name,
                    td.i_dt_id
                    FROM tm_tunai a
                    INNER JOIN tm_tunai_item tti ON tti.i_tunai = a.i_tunai
                    INNER JOIN tr_bagian tb ON tb.id = a.id_bagian
                    INNER JOIN tr_area ta ON ta.id = a.id_area
                    INNER JOIN tr_salesman ts ON ts.id = a.id_salesman
                    INNER JOIN tr_customer tc ON tc.id = a.id_customer
                    INNER JOIN tm_dt td ON td.i_dt = a.i_dt
                    WHERE a.i_tunai = '$id'";

        // var_dump($sql); die();

        return $this->db->query($sql);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        $sql = "SELECT a.*, tti.i_tunai_item, tti.id_nota, tti.v_jumlah, tti.n_item_no,
                td.i_dt_id, 
                tnp.i_document, tnp.d_document, tnp.v_bersih
            FROM tm_tunai a
            INNER JOIN tm_tunai_item tti ON tti.i_tunai = a.i_tunai
            INNER JOIN tm_dt td ON td.i_dt = a.i_dt
            INNER JOIN tm_nota_penjualan tnp ON tnp.id = tti.id_nota
            WHERE a.i_tunai = '$id'";

        return $this->db->query($sql);
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
            $sql_awal = "SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                        FROM tm_tunai a
                        JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
                        WHERE a.i_tunai = '$id'
                        GROUP BY 1,2";
            
            // var_dump($sql_awal); die();

            $awal = $this->db->query($sql_awal)->row();
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
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_tunai');", FALSE);
            }
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('i_tunai', $id);
        $this->db->update('tm_tunai', $data);
    }

    public function updateprint($id)
    {
        $this->db->query("UPDATE tm_dt SET n_print = n_print + 1 WHERE i_dt = $id");
    }

    public function generate_nomor_dokumen($id_bagian) {

        $kode = 'TN';

        $sql = "SELECT count(*) 
                FROM tm_tunai tt
                INNER JOIN tr_bagian tb ON tb.id = tt.id_bagian 
                WHERE tb.id = '$id_bagian'
                    AND to_char(d_tunai, 'yyyy-mm') = to_char(now(), 'yyyy-mm')";

        $query = $this->db->query($sql);
        $result = $query->row()->count;
        $count = intval($result) + 1;
        $generated = $kode . '-' . date('ym') . '-' . sprintf('%04d', $count);

        return $generated;
    }
}
/* End of file Mmaster.php */