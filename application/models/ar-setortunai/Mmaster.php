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

        $sql_rv = "SELECT a.i_rv, a.i_rv_refference 
                    FROM tm_rv_item a
                    INNER JOIN tm_rv c ON (c.i_rv  = a.i_rv)
                    INNER JOIN tr_rv_refference_type d ON (
                                                            a.i_rv_refference_type = d.i_rv_refference_type AND
                                                            d.i_company = c.i_company AND 
                                                            d.i_rv_refference_type_id = 'TN'
                                                        )
                    WHERE c.i_company = '4'";

        $sql = "SELECT ts.i_st AS id, 
                    0 AS NO,
                    ts.d_st, 
                    ts.i_st_id, 
                    ta.e_area, 
                    ts.v_jumlah, 
                    q_rv.i_rv AS referensi,
                    ts.i_status,
                    tsd.e_status_name,
                    tsd.label_color,
                    tma.i_level,
                    tl.e_level_name,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    '$i_menu' as i_menu,
                    '$folder' AS folder
                FROM tm_st ts 
                INNER JOIN tr_area ta ON ta.id = ts.id_area 
                INNER JOIN tr_status_document tsd ON tsd.i_status = ts.i_status 
                LEFT JOIN tr_menu_approve tma ON (tma.n_urut = ts.i_approve_urutan AND tma.i_menu = '$i_menu')
                LEFT JOIN public.tr_level tl ON tl.i_level = tma.i_level
                LEFT JOIN ($sql_rv) AS q_rv on (q_rv.i_rv_refference = ts.i_st)
                WHERE ts.id_company = '$this->id_company' AND
                    ts.i_status <> '5'AND
                    ts.d_st BETWEEN to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy')
                    $bagian
                ORDER BY ts.d_st DESC";
        
        // var_dump($sql); die();

        $datatables->query($sql, false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rounded">' . $data['e_status_name'] . '</span>';
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

    public function get_all_tunai_item($q='')
    {
        $id_company = $this->session->userdata('id_company');

        $where = "WHERE tt.i_status = '6' AND tt.id_company = '$id_company'";
        
        if ($q != '') {
            $where .= " AND tt.i_tunai_id ILIKE '%$q%'";
        }

        $sql = "SELECT DISTINCT ON (tt.i_tunai) 
                    tt.i_tunai, tt.i_tunai_id, tt.d_tunai, tc.e_customer_name, tt.v_jumlah 
                FROM tm_tunai tt
                INNER JOIN tm_tunai_item tti ON tti.i_tunai = tt.i_tunai 
                LEFT JOIN tr_customer tc ON tc.id = tt.id_customer 
                $where";

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
        $this->db->insert('tm_st', $data);
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
        $this->db->insert('tm_st_detail', $data);
    }

    public function insert_setor_tunai($i_st_id, $d_dt, $ibagian, $id_company=null, $id_area, $id_bank, $e_remark, $v_jumlah)
    {
        $id_company = $this->session->userdata('id_company');

        $data = [
            'i_st_id' => $i_st_id,
            'd_st' => $d_dt,
            'id_bagian' => $ibagian,
            'id_company' => $id_company,
            'id_area' => $id_area,
            'id_bank' => $id_bank,
            'e_remark' => $e_remark,
            'v_jumlah' => $v_jumlah,
        ];

        $this->db->insert('tm_st', $data);
    }

    public function update_setor_tunai($i_st_id, $d_dt, $ibagian, $id_company=null, $id_area, $id_bank, $e_remark, $v_jumlah, $id)
    {
        $id_company = $this->session->userdata('id_company');

        $data = [
            'i_st_id' => $i_st_id,
            'd_st' => $d_dt,
            'id_bagian' => $ibagian,
            'id_company' => $id_company,
            'id_area' => $id_area,
            'id_bank' => $id_bank,
            'e_remark' => $e_remark,
            'v_jumlah' => $v_jumlah,
        ];

        $this->db->where('i_st', $id);
        $this->db->update('tm_st', $data);
    }

    public function insert_setor_tunai_item($i_st, $i_tunai, $v_jumlah, $n_item_no)
    {
        $data = [
            'i_st' => $i_st,
            'i_tunai' => $i_tunai,
            'v_jumlah' => $v_jumlah,
            'n_item_no' => $n_item_no
        ];

        $this->db->insert('tm_st_item', $data);
    }

    public function delete_setor_tunai_item($i_st)
    {
        $this->db->where('i_st', $i_st);
        $this->db->delete('tm_st_item');
    }

    public function update_sisa_tunai($id)
    {
        $all_setor_tunai = $this->dataeditdetail($id);

        foreach ($all_setor_tunai->result() as $setor_tunai) {
            $i_tunai = $setor_tunai->i_tunai;
            $v_jumlah = $setor_tunai->v_jumlah;            

            $sql = "UPDATE tm_tunai 
                SET v_sisa = v_sisa - $v_jumlah 
                WHERE i_tunai = $i_tunai ";

            $this->db->query($sql);
        }        
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        $sql = "SELECT ts.*,
                    tb.e_bagian_name,
                    ta.e_area,
                    tb2.e_bank_name                    
                    FROM tm_st ts
                    INNER JOIN tm_st_item tti ON tti.i_st = ts.i_st
                    INNER JOIN tr_bagian tb ON tb.id = ts.id_bagian
                    INNER JOIN tr_area ta ON ta.id = ts.id_area
                    INNER JOIN tr_bank tb2 ON tb2.id = ts.id_bank
                    WHERE ts.i_st = '$id'";

        // var_dump($sql); die();

        return $this->db->query($sql);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        $sql = "SELECT ts.*,
                tb.e_bagian_name,
                ta.e_area,
                tb2.e_bank_name,
                tt.i_tunai,
                tt.i_tunai_id,
                tt.d_tunai,
                tc.e_customer_name
                FROM tm_st ts
                INNER JOIN tm_st_item tti ON tti.i_st = ts.i_st
                INNER JOIN tr_bagian tb ON tb.id = ts.id_bagian
                INNER JOIN tr_area ta ON ta.id = ts.id_area
                INNER JOIN tr_bank tb2 ON tb2.id = ts.id_bank
                INNER JOIN tm_tunai tt ON tt.i_tunai = tti.i_tunai
                INNER JOIN tr_customer tc ON tc.id = tt.id_customer
                WHERE ts.i_st = '$id'";

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

    private function change_status_insert_menu_approve($id, $i_menu, $i_level, $username)
    {
        $now = date('Y-m-d');

        $sql = "INSERT INTO tm_menu_approve 
                    (i_menu, i_level, i_document, e_approve, d_approve, e_database) 
                VALUES
                    ('$i_menu','$i_level','$id','$username','$now','tm_st')";

        $this->db->query($sql, FALSE);
    }

    private function change_status_get_status_approval($id, $i_menu)
    {
        $sql = "SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
            FROM tm_st a
            JOIN tr_menu_approve b on (b.i_menu = '$i_menu')
            WHERE a.i_st = '$id'
            GROUP BY 1,2";

        return $this->db->query($sql, FALSE);
    }

    private function change_status_delete_approval($id, $i_menu, $i_level)
    {
        $sql = "DELETE FROM tm_menu_approve
                WHERE i_menu = '$i_menu' AND i_level = '$i_level' AND i_document = '$id'";

        $this->db->query($sql, FALSE);
    }

    public function changestatus($id, $istatus)
    {
        $approval = $this->change_status_get_status_approval($id, $this->i_menu)->row();

        $data = [
            'i_status' => $istatus
        ];        
            
        /** change request */
        if ($istatus == '3') {
            if ($approval->i_approve_urutan - 1 != 0) {
                $data = [
                    'i_approve_urutan' => $approval->i_approve_urutan - 1
                ];
            } 
            $this->change_status_delete_approval($id, $this->i_menu, $this->i_level);
        }
        
        /** approve */
        if ($istatus == '6') {
            $data = [
                'i_approve_urutan' => $approval->i_approve_urutan + 1,
            ];

            if ($approval->i_approve_urutan + 1 > $approval->n_urut) {
                $data = [
                    'i_status' => $istatus,
                    'i_approve_urutan' => $approval->i_approve_urutan + 1,
                    'e_approve' => $this->username,
                    'd_approve' => date('Y-m-d'),
                ];

                /** update sisa ke table tm_tunai */                
                $this->update_sisa_tunai($id);
            } 

            $this->change_status_insert_menu_approve($id, $this->i_menu, $this->i_level, $this->username);
        }        

        $this->db->where('i_st', $id);
        $this->db->update('tm_st', $data);
    }

    public function updateprint($id)
    {
        $this->db->query("UPDATE tm_dt SET n_print = n_print + 1 WHERE i_dt = $id");
    }

    public function generate_nomor_dokumen($id_bagian) {

        $kode = 'RTN';

        $sql = "SELECT count(*) 
                FROM tm_st tt
                INNER JOIN tr_bagian tb ON tb.id = tt.id_bagian 
                WHERE tb.id = '$id_bagian'
                    AND to_char(d_st, 'yyyy-mm') = to_char(now(), 'yyyy-mm')";

        $query = $this->db->query($sql);
        $result = $query->row()->count;
        $count = intval($result) + 1;
        $generated = $kode . '-' . date('ym') . '-' . sprintf('%04d', $count);

        return $generated;
    }

    public function get_all_bank($q='')
    {
        $id_company = $this->session->userdata('id_company');

        $where = "WHERE tb.f_status = 't' AND tb.id_company = '$id_company'";
        
        if ($q != '') {
            $where .= " AND tb.e_bank_name ILIKE '%$q%'";
        }

        $sql = "SELECT * 
                FROM tr_bank tb
                $where";

        // var_dump($sql);

        return $this->db->query($sql);
    }
}
/* End of file Mmaster.php */