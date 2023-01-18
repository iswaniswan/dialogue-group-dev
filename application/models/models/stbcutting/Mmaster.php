<?php
defined("BASEPATH") or exit("No direct script access allowed");

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != "" && $dto != "") {
            $dfrom = formatYmd($dfrom);
            $dto = formatYmd($dto);
            $and = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and = "";
        }
        $cek = $this->db->query("SELECT i_bagian FROM tm_stb_cutting a WHERE i_status <> '5' AND id_company = '$this->id_company' $and
                AND i_bagian IN (SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')");
        if ($this->i_departement == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT 0 AS NO, a.id AS id, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.e_remark, e_status_name, label_color, a.i_status, l.i_level, l.e_level_name,
                '$i_menu' AS i_menu, '$folder' AS folder, '$dfrom' AS dfrom,'$dto' AS dto
            FROM
                tm_stb_cutting a
            INNER JOIN tr_status_document b ON (b.i_status = a.i_status)
            LEFT JOIN public.tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (f.i_level = l.i_level)
            WHERE a.i_status <> '5' AND a.id_company = '$this->id_company' $and $bagian
            ORDER BY a.id ASC");

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = $data['id'];
            $i_status   = trim($data['i_status']);
            $i_level    = trim($data['i_level']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye mr-2 fa-lg text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        $datatables->hide('i_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function data_referensi($dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = formatYmd($dfrom);
            $dto   = formatYmd($dto);
            $and   = "AND aa.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("WITH CTE AS (SELECT a.id, id_company_referensi, b.name, c.i_product_wip, 
                a.d_schedule, c.e_product_wipname||' '||d.e_color_name e_product,
                e.i_material, e.e_material_name, a.d_schedule_realisasi, a.n_quantity||' '|| f.e_satuan_name n_quantity, coalesce(a.n_realisasi_gelar,0) n_jumlah_gelar,
                ROW_NUMBER() OVER (ORDER BY a.id) AS i
            FROM tm_schedule_cutting_item a
            INNER JOIN tm_schedule_cutting aa ON (aa.id = a.id_document)
            INNER JOIN public.company b ON (b.id = a.id_company_referensi)
            INNER JOIN tr_product_wip c ON (c.id = a.id_product_wip)
            INNER JOIN tr_color d ON (
                d.i_color = c.i_color AND c.id_company = d.id_company
            )
            INNER JOIN tr_material e ON (e.id = a.id_material)
            INNER JOIN tr_satuan f ON (
                f.i_satuan_code = e.i_satuan_code AND e.id_company = f.id_company
            )
            WHERE aa.i_status = '6' $and
            AND aa.id_company = '$this->id_company'
            AND e.i_kode_group_barang = 'GRB0001'
            AND a.id NOT IN (
                SELECT DISTINCT id_schedule_item FROM tm_stb_cutting_item a, tm_stb_cutting b 
                WHERE b.id = a.id_document AND b.i_status = '6'
            )
            ORDER BY a.d_schedule, c.i_product_wip, e.i_material)
            SELECT id, id_company_referensi, name, i, i_product_wip, d_schedule, e_product, 
            i_material, e_material_name, d_schedule_realisasi, n_quantity, n_jumlah_gelar,
            (SELECT count(i) AS jml FROM CTE) AS jml FROM CTE");

        $datatables->add('action', function ($data) {
            $i = $data["i"];
            $jml = $data["jml"];
            $id = $data['id'];
            $disable = '';

            if ($data['d_schedule_realisasi'] == "") {
                $disable = ' disabled';
            }

            $id_company_referensi = $data['id_company_referensi'];
            /* $id_material    = $data['id_material'];
            $id_product_wip = $data['id_product_wip']; */
            $data   = '';
            $data .= "<label class='custom-control custom-checkbox'> 
                <input type='checkbox' id='chk$i' name='chk$i' class='custom-control-input' ".$disable.">
                <span class='custom-control-indicator'></span>
                <span class='custom-control-description'></span>
                <input id='id$i' name='id$i' value='$id' type='hidden'>
                <input id='id_company_referensi$i' name='id_company_referensi$i' value='$id_company_referensi' type='hidden'>
                <input id='jml' name='jml' value='$jml' type='hidden'>";
            return $data;
        });
        $datatables->hide('i');
        $datatables->hide('jml');
        $datatables->hide('id_company_referensi');
        return $datatables->generate();
    }

    public function bagian()
    {
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement 
            FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON (
                b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            )
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (
                d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement
            )
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' 
            AND username = '$this->username' AND a.id_company = '$this->id_company'
			ORDER BY 4, 3 ASC NULLS LAST");
    }

    public function runningnumber($thbl, $tahun)
    {
        $cek = $this->db->query(
            "SELECT  substring(i_document, 1, 3) AS kode 
            FROM tm_stb_cutting 
            WHERE i_status <> '5' AND id_company = '$this->id_company'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = "STB";
        }
        $query = $this->db->query("SELECT max(substring(i_document, 10, 4)) AS max
            FROM tm_stb_cutting
            WHERE to_char (d_document, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND id_company = '$this->id_company'
            AND substring(i_document, 1, 3) ILIKE '%$kode%'");
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
            $nomer = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    public function data_detail($id_schedule)
    {
        return $this->db->query("SELECT a.id_product_wip, a.id_material, a.id AS id_schedule_item, c.id AS id_panel_item, 
                e.i_product_wip, e.e_product_wipname||' '|| f.e_color_name e_product_wipname,
                d.i_material, d.e_material_name, c.i_panel, c.bagian, 
                ((a.n_quantity * b.v_set)/ b.v_gelar) * c.n_qty_penyusun n_jumlah_awal,
                FLOOR((a.n_quantity * b.v_set)/ b.v_gelar + 0.01) * c.n_qty_penyusun  n_jumlah,
                c.n_qty_penyusun, a.n_jumlah_gelar
            FROM tm_schedule_cutting_item a 
            INNER JOIN tr_polacutting_new b ON (
                b.id_product_wip = a.id_product_wip AND a.id_material = b.id_material
            )
            INNER JOIN tr_material d ON (d.id = a.id_material)
            INNER JOIN tm_panel_item c ON (
                c.id_product_wip = b.id_product_wip AND b.id_material = c.id_material 
                AND (b.v_gelar * 100)=c.n_panjang_gelar AND b.v_set = c.n_hasil_gelar 
            )
            INNER JOIN tr_product_wip e ON (e.id = a.id_product_wip)
            INNER JOIN tr_color f ON (f.i_color = e.i_color AND e.id_company = f.id_company)
            WHERE b.f_status = 't' AND d.i_kode_group_barang = 'GRB0001'
            AND a.id IN ($id_schedule) AND b.f_marker_utama = 't'
            ORDER BY 1,2");
    }

    public function runningid()
    {
        $this->db->select("max(id) AS id");
        $this->db->from("tm_stb_cutting");
        return $this->db->get()->row()->id + 1;
    }

    public function simpan_header($id, $i_document, $d_document, $i_bagian, $id_company_tujuan, $e_remark, $id_jenis_barang_keluar)
    {
        $data = array(
            'id' => $id,
            'id_company' => $this->id_company,
            'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            'id_company_tujuan' => $id_company_tujuan,
            'id_jenis_barang_keluar' => $id_jenis_barang_keluar,
            'e_remark' => $e_remark
        );
        $this->db->insert('tm_stb_cutting', $data);
    }

    public function simpan_item($id, $id_schedule_item, $id_panel_item, $n_quantity_stb_cutting, $n_quantity_panel, $n_quantity_selisih, $n_quantity, $e_remark_item, $n_qty_penyusun, $n_jumlah_gelar)
    {
        $data = array(
            'id_document' => $id,
            'id_schedule_item' => $id_schedule_item,
            'id_panel_item' => $id_panel_item,
            'n_quantity_stb_hasil' => $n_quantity_stb_cutting,
            'n_quantity_panel' => $n_quantity_panel,
            'n_quantity_selisih' => $n_quantity_selisih,
            'n_quantity' => $n_quantity,
            'n_quantity_sisa' => $n_quantity,
            'e_remark' => $e_remark_item,
            'n_quantity_penyusun' => $n_qty_penyusun,
            'n_jumlah_gelar' => $n_jumlah_gelar,
        );
        $this->db->insert('tm_stb_cutting_item', $data);
    }

    public function get_data_headers($id)
    {
        $this->db->select('a.*, b.e_bagian_name, c.name, d.e_jenis_name');
        $this->db->from('tm_stb_cutting a');
        $this->db->join('tr_bagian b', 'b.i_bagian = a.i_bagian and a.id_company = b.id_company');
        $this->db->join('public.company c', 'c.id = a.id_company_tujuan');
        $this->db->join('tr_jenis_barang_keluar d', 'd.id = a.id_jenis_barang_keluar','left');
        $this->db->where('a.id', $id);
        return $this->db->get();
    }
    
    public function get_data_items($id)
    {
        $this->db->select('a.*, b.i_panel, bagian, i_material, e_material_name');
        $this->db->from('tm_stb_cutting_item a');
        $this->db->join('tm_panel_item b', 'b.id = a.id_panel_item');
        $this->db->join('tr_material c', 'c.id = b.id_material');
        $this->db->where('a.id_document', $id);
        $this->db->order_by('a.id');
        return $this->db->get();
    }

    public function update_header($id, $i_document, $d_document, $i_bagian, $e_remark, $id_jenis_barang_keluar)
    {
        $data = array(
            'i_document' => $i_document,
            'd_document' => $d_document,
            'i_bagian' => $i_bagian,
            'id_jenis_barang_keluar' => $id_jenis_barang_keluar,
            'e_remark' => $e_remark
        );
        $this->db->where('id', $id);
        $this->db->update('tm_stb_cutting', $data);
    }

    public function update_item($id_item, $id, $id_schedule_item, $id_panel_item, $n_quantity_stb_cutting, $n_quantity_panel, $n_quantity_selisih, $n_quantity, $e_remark_item)
    {
        $data = array(
            'id_document' => $id,
            'id_schedule_item' => $id_schedule_item,
            'id_panel_item' => $id_panel_item,
            'n_quantity_stb_hasil' => $n_quantity_stb_cutting,
            'n_quantity_panel' => $n_quantity_panel,
            'n_quantity_selisih' => $n_quantity_selisih,
            'n_quantity' => $n_quantity,
            'n_quantity_sisa' => $n_quantity,
            'e_remark' => $e_remark_item,
        );
        $this->db->where('id', $id_item);
        $this->db->update('tm_stb_cutting_item', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				from tm_stb_cutting a
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
                        'e_approve' => $this->session->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_stb_cutting');", FALSE);
            }
        } else {
            $data = [
                "i_status" => $istatus,
            ];
        }
        $this->db->where("id", $id);
        $this->db->update("tm_stb_cutting", $data);
    }

    public function estatus($istatus)
    {
        $this->db->select("e_status_name");
        $this->db->from("tr_status_document");
        $this->db->where("i_status", $istatus);
        return $this->db->get()->row()->e_status_name;
    }
}
/* End of file Mmaster.php */
