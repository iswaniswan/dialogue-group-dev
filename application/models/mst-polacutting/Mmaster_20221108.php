<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');
        $datatables->query("SELECT
                DISTINCT
                0 AS NO,
                d.i_product_wip,
                e_product_wipname,
                d.i_color,
                e_color_name,
                a.id_company,
                CASE
                    WHEN a.f_status = TRUE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                a.id_product_wip
            FROM
                tr_polacutting_new a
            INNER JOIN tr_product_wip d ON
                (d.id = a.id_product_wip)
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_color c ON
                (c.i_color = d.i_color
                    AND c.id_company = d.id_company)
            WHERE
                a.id_company = '$idcompany'
            ", FALSE);

        $datatables->edit(
            'status',
            function ($data) {
                /* $id         = trim($data['i_product_wip']).'|'.trim($data['i_color']); */
                $folder     = $data['folder'];
                $id_menu    = $data['i_menu'];
                $status     = $data['status'];
                $id         = $data['id_product_wip'];
                if ($status == 'Aktif') {
                    $warna  = 'success';
                } else {
                    $warna  = 'danger';
                }
                $data       = '';
                if (check_role($id_menu, 3)) {
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                } else {
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

        $datatables->add('action', function ($data) {
            /*$id      = $data['id'];*/
            $product = trim($data['i_product_wip']);
            $icolor  = trim($data['i_color']);
            $i_menu  = $data['i_menu'];
            $folder  = $data['folder'];
            $data    = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$product/$icolor\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$product/$icolor\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a";
            }

            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_color');
        $datatables->hide('id_company');
        $datatables->hide('id_product_wip');
        /* $datatables->hide('d_entry');
        $datatables->hide('d_update'); */

        return $datatables->generate();
    }

    public function status(/* $iproduct, $icolor,  */$id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('f_status');
        $this->db->from('tr_polacutting_new');
        $this->db->where('id_product_wip', $id);
        $this->db->limit(1, 'ASC');
        /* $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor); */
        $this->db->where('id_company', $idcompany);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row    = $query->row();
            $status = $row->f_status;
            if ($status == 't') {
                $stat = 'f';
            } else {
                $stat = 't';
            }
        }
        $data = array(
            'f_status' => $stat
        );
        $this->db->where('id_product_wip', $id);
        /* $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor); */
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_polacutting_new', $data);
    }

    public function productwip($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT 
                i_product_wip,
                initcap(e_product_wipname) AS e_product_wipname,
                a.i_color,
                initcap(e_color_name) AS e_color_name
            FROM tr_product_wip a, tr_color b
            WHERE 
                a.i_color = b.i_color AND a.id_company = b.id_company
                AND a.f_status = 't'
                AND a.id_company = '$idcompany'
                AND (i_product_wip ILIKE '%$cari%' 
                     OR e_product_wipname ILIKE '%$cari%' 
                     OR e_color_name ILIKE '%$cari%')
            ORDER BY i_product_wip
        ", FALSE);
    }

    public function get_bisbisan($cari, $i_material)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.id,
                n_bisbisan,
                b.e_jenis_potong
            FROM
                tr_material_bisbisan a
            INNER JOIN tr_jenis_potong b ON
                (b.id = a.id_jenis_potong)
            INNER JOIN tr_material c ON 
                (c.id = a.id_material)
            WHERE
                c.i_material = '$i_material'
                AND c.id_company = '$idcompany'
                AND (b.e_jenis_potong ILIKE '%$cari%')
                AND a.f_status = 't'
            ORDER BY 3,2
        ", FALSE);
    }

    public function get_type_makloon($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                id,
                initcap(REPLACE (e_type_makloon_name,'MAKLOON','')) AS name
            FROM
                tr_type_makloon
            WHERE
                id_company = '$idcompany'
                AND (e_type_makloon_name ILIKE '%$cari%')
                AND f_status = 't'
            ORDER BY 2
        ", FALSE);
    }

    public function productwipref($cari, $i_product_wip, $i_color)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                DISTINCT 
                a.i_product_wip,
                initcap(e_product_wipname) AS e_product_wipname,
                a.i_color,
                initcap(e_color_name) AS e_color_name
            FROM
                tr_product_wip a
            INNER JOIN tr_polacutting_new b ON
                (b.id_product_wip = a.id)
            INNER JOIN tr_color c ON
                (c.i_color = a.i_color
                    AND a.id_company = c.id_company)
            WHERE
                a.f_status = 't'
                AND a.id_company = '$idcompany'
                AND a.i_product_wip = '$i_product_wip'
                AND a.i_color <> '$i_color'
                AND (a.i_product_wip ILIKE '%$cari%'
                    OR e_product_wipname ILIKE '%$cari%'
                    OR e_color_name ILIKE '%$cari%')
            ORDER BY
                i_product_wip
        ", FALSE);
    }

    public function getdetailref($i_product_wip, $i_color)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                DISTINCT 
                a.*,
                e.i_material,
                e.e_material_name,
                v_bisbisan,
                n_bisbisan,
                c.e_jenis_potong,
                a.e_bagian AS bagian,
                string_agg(trim(e_bagian_name),', ') AS gudang,
	            jsonb_agg(j.id) type_makloon_id,  
                jsonb_agg(trim(COALESCE (e_type_makloon_name,''))) e_type_makloon_name
	            /* jsonb_agg(e_type_makloon_name) e_type_makloon_name */
            FROM
                tr_polacutting_new a
            LEFT JOIN tr_material_bisbisan b ON
                (b.id_material = a.id_material
                    AND a.id_bisbisan = b.id)
            LEFT JOIN tr_material e ON 
                (e.id = a.id_material)
            LEFT JOIN tr_jenis_potong c ON
                (c.id = b.id_jenis_potong)
            LEFT JOIN tr_product_wip d ON
                (d.id = a.id_product_wip)
            LEFT JOIN tr_bagian_kelompokbarang h ON (h.i_kode_kelompok = e.i_kode_kelompok AND e.id_company = h.id_company)
            LEFT JOIN tr_bagian i ON (i.i_bagian = h.i_bagian AND h.id_company = i.id_company)
            LEFT JOIN (SELECT id, initcap(REPLACE (e_type_makloon_name,'MAKLOON','')) e_type_makloon_name FROM tr_type_makloon j ORDER BY id) j ON (j.id = any(a.id_type_makloon))
            WHERE
                a.f_status = 't'
                AND a.id_company = '$idcompany'
                AND d.i_product_wip = '$i_product_wip'
                AND d.i_color = '$i_color'
            GROUP BY 1,e.i_material,e.e_material_name,n_bisbisan,c.e_jenis_potong,a.e_bagian
            ORDER BY
                e.e_material_name ASC
        ", FALSE);
    }

    public function material($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT 
                i_material,
                e_material_name,
                b.e_satuan_name
            FROM tr_material a
            INNER JOIN tr_satuan b ON (b.i_satuan_code = a.i_satuan_code AND a.id_company = b.id_company)
            WHERE 
                a.id_company = '$idcompany'
                AND
                a.f_status = 't'
                AND (i_material ILIKE '%$cari%' 
                     OR e_material_name ILIKE '%$cari%')
                AND i_kode_group_barang NOT IN ('GRB0003')
            ORDER BY i_material
        ", FALSE);
    }

    public function getdetailmaterial($i_material)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT i_material, string_agg(trim(e_bagian_name),', ') AS e_bagian_name  FROM tr_material a
            LEFT JOIN tr_bagian_kelompokbarang b ON (b.i_kode_kelompok = a.i_kode_kelompok AND a.id_company = b.id_company)
            LEFT JOIN tr_bagian c ON (c.i_bagian = b.i_bagian AND b.id_company = c.id_company)
            WHERE i_material = '$i_material' AND a.f_status = 't'
            AND a.id_company = '$idcompany'
            GROUP BY 1
        ", FALSE);
    }

    public function cekdata($iproduct, $icolor, $imaterial)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_product_wip, i_material, i_color');
        $this->db->from('tr_polacutting');
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_material', $imaterial);
        $this->db->where('i_color', $icolor);
        return $this->db->get();
    }

    /* public function insertdetail($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproductwip,$icolor,$n_bagibis,$bagian,$bis3,$bis4){
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_product_wip' => $iproductwip,
            'i_material'    => $imaterial,
            'i_color'       => $icolor,
            'v_gelar'       => $vgelar,
            'v_set'         => $vset,
            'f_bisbisan'    => $fbis,
            'v_toset'       => $vtoset,
            'n_bagibis'     => $n_bagibis,
            'id_company'    => $idcompany,
            'd_entry'       => current_datetime(),
            'bagian'        => $bagian,
            'n_bis3'        => $bis3,
            'n_bis4_5'      => $bis4,
        );
        $this->db->insert('tr_polacutting', $data);
    } */


    public function insertdetail($imaterial, $vgelar, $vset, $iproductwip, $icolor, $bagian, $bis3, $id_bisbisan, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon, $f_kain_utama, $f_budgeting)
    {
        if ($bis3 == '') {
            $bis3 = 0;
        }
        $idcompany  = $this->session->userdata('id_company');
        $iproductwip = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE)->row()->id;
        $imaterial = $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany' ", FALSE)->row()->id;
        // $data = array(
        //     'id_company'        => $idcompany,
        //     'id_product_wip'    => $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE)->row()->id,
        //     'id_material'       => $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany' ", FALSE)->row()->id,
        //     'e_bagian'          => $bagian,
        //     'v_gelar'           => $vgelar,
        //     'v_set'             => $vset,
        //     'v_bisbisan'        => $bis3,
        //     'id_bisbisan'       => $id_bisbisan,
        //     'f_cutting'         => $f_cutting,
        //     'f_autocutter'      => $autocutter,
        //     'f_badan'           => $badan,
        //     'f_print'           => $print,
        //     'f_bordir'          => $bordir,
        //     'f_quilting'        => $quilting

        // );
        //$this->db->insert('tr_polacutting_new', $data);

        /* $this->db->query("INSERT 
        INTO tr_polacutting_new(id_company,id_product_wip,id_material,e_bagian,v_gelar,v_set,v_bisbisan,id_bisbisan,f_cutting,f_autocutter,f_badan,f_print,f_bordir,f_quilting) 
        VALUES ('$idcompany','$iproductwip','$imaterial','$bagian','$vgelar','$vset','$bis3',$id_bisbisan,'$f_cutting','$autocutter','$badan','$print','$bordir','$quilting')
        "); */
        /* var_dump(to_pg_array($id_type_makloon));
        die; */
            
        
        // var_dump($id_type_makloon);
        if ($id_type_makloon===NULL || $id_type_makloon==='NULL') {
            $id_type_makloon = "NULL";
        }else{
            $id_type_makloon = to_pg_array($id_type_makloon);
            $id_type_makloon = "ARRAY[$id_type_makloon]";
        }

        /* var_dump($id_type_makloon);
        die; */
        /* die;
        if ($id_type_makloon!== NULL || $id_type_makloon!== 'NULL') {
            echo "kesini";
        }else{
            echo "kesana";
        } */
        
        $this->db->query("INSERT 
        INTO tr_polacutting_new
                (id_company,id_product_wip,id_material,e_bagian,v_gelar,v_set,v_bisbisan,id_bisbisan,id_type_makloon,f_kain_utama,f_budgeting) 
        VALUES ('$idcompany','$iproductwip','$imaterial','$bagian','$vgelar','$vset','$bis3',$id_bisbisan,$id_type_makloon,'$f_kain_utama','$f_budgeting')
        ");
    }

    public function updatedetail($imaterial, $vtoset, $vgelar, $vset, $fbis, $iproductwip, $icolor, $n_bagibis, $bagian, $bis3, $bis4, $id_bisbisan, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon,$f_kain_utama, $f_budgeting)
    {
        if ($bis3 == '') {
            $bis3 = 0;
        }
        $id_type_makloon = "{".to_pg_array($id_type_makloon)."}";
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_company'        => $idcompany,
            'id_product_wip'    => $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE)->row()->id,
            'id_material'       => $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany' ", FALSE)->row()->id,
            'e_bagian'          => $bagian,
            'v_gelar'           => $vgelar,
            'v_set'             => $vset,
            'v_bisbisan'        => $bis3,
            'f_cutting'         => $f_cutting,
            'f_autocutter'      => $autocutter,
            'f_badan'           => $badan,
            'f_print'           => $print,
            'f_bordir'          => $bordir,
            'f_quilting'        => $quilting,
            'id_bisbisan'       => $id_bisbisan,
            'id_type_makloon'   => $id_type_makloon,
            'f_kain_utama'      => $f_kain_utama,
            'f_budgeting'       => $f_budgeting,
            'd_update'          => current_datetime(),
        );
        $this->db->insert('tr_polacutting_new', $data);
    }

    public function insertdetailall($imaterial, $vtoset, $vgelar, $vset, $fbis, $iproductwip, $icolor, $n_bagibis, $bagian, $bis3, $bis4, $id_bisbisan, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting)
    {
        if ($bis3 == '') {
            $bis3 = 0;
        }
        
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND id_company = '$idcompany' ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $data = array(
                    'id_company'        => $idcompany,
                    'id_product_wip'    => $key->id,
                    'id_material'       => $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany' ", FALSE)->row()->id,
                    'e_bagian'          => $bagian,
                    'v_gelar'           => $vgelar,
                    'v_set'             => $vset,
                    'v_bisbisan'        => $bis3,
                    'id_bisbisan'       => $id_bisbisan,
                    'f_cutting'         => $f_cutting,
                    'f_autocutter'        => $autocutter,
                    'f_badan'             => $badan,
                    'f_print'             => $print,
                    'f_bordir'            => $bordir,
                    'f_quilting'          => $quilting,
                );
                $this->db->insert('tr_polacutting_new', $data);
            }
        }
    }

    public function updatedetailall($imaterial, $vtoset, $vgelar, $vset, $fbis, $iproductwip, $icolor, $n_bagibis, $bagian, $bis3, $bis4, $id_bisbisan, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon, $f_kain_utama, $f_budgeting)
    {
        $idcompany  = $this->session->userdata('id_company');
        $id_type_makloon = "{".to_pg_array($id_type_makloon)."}";
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND id_company = '$idcompany' ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $data = array(
                    'id_company'        => $idcompany,
                    'id_product_wip'    => $key->id,
                    'id_material'       => $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany' ", FALSE)->row()->id,
                    'e_bagian'          => $bagian,
                    'v_gelar'           => $vgelar,
                    'v_set'             => $vset,
                    'v_bisbisan'        => $bis3,
                    'id_bisbisan'       => $id_bisbisan,
                    'f_cutting'         => $f_cutting,
                    'f_autocutter'      => $autocutter,
                    'f_badan'           => $badan,
                    'f_print'           => $print,
                    'f_bordir'          => $bordir,
                    'f_quilting'        => $quilting,
                    'f_kain_utama'      => $f_kain_utama,
                    'f_budgeting'       => $f_budgeting,
                    'id_type_makloon'   => $id_type_makloon,
                    'd_update'          => current_datetime(),
                );
                $this->db->insert('tr_polacutting_new', $data);
            }
        }
    }

    public function insertdetailwip($iproductwip, $imaterial, $n_quantity, $bagian, $icolor, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon, $f_kain_utama, $f_budgeting)
    {
        

        $id_type_makloon = "{".to_pg_array($id_type_makloon)."}";
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        if ($query->num_rows() > 0) {
            $sql = $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany'", FALSE);
            if ($sql->num_rows() > 0) {
                $data = array(
                    'id_company'       => $idcompany,
                    'id_product_wip'   => $query->row()->id,
                    'id_material'      => $sql->row()->id,
                    'n_quantity'       => $n_quantity,
                    'bagian'           => $bagian,
                    'f_cutting'        => $f_cutting,
                    'f_autocutter'     => $autocutter,
                    'f_badan'          => $badan,
                    'f_print'          => $print,
                    'f_bordir'         => $bordir,
                    'f_quilting'       => $quilting,
                    'id_type_makloon'  => $id_type_makloon,
                    'f_kain_utama'     => $f_kain_utama,
                    'f_budgeting'      => $f_budgeting,
                );
                $this->db->insert('tr_product_wip_item', $data);
            }
        }
    }

    public function insertdetailwipall($iproductwip, $imaterial, $n_quantity, $bagian, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon,$f_kain_utama,$f_budgeting)
    {
        $idcompany  = $this->session->userdata('id_company');
        $id_type_makloon = "{".to_pg_array($id_type_makloon)."}";
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND id_company = '$idcompany' ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $sql = $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany'", FALSE);
                if ($sql->num_rows() > 0) {
                    $data = array(
                        'id_company'     => $idcompany,
                        'id_product_wip' => $row->id,
                        'id_material'    => $sql->row()->id,
                        'n_quantity'     => $n_quantity,
                        'bagian'         => $bagian,
                        'f_cutting'      => $f_cutting,
                        'f_autocutter'   => $autocutter,
                        'f_badan'        => $badan,
                        'f_print'        => $print,
                        'f_bordir'       => $bordir,
                        'f_quilting'     => $quilting,
                        'id_type_makloon'=> $id_type_makloon,
                        'f_kain_utama'   => $f_kain_utama,
                        'f_budgeting'    => $f_budgeting,
                    );
                    $this->db->insert('tr_product_wip_item', $data);
                }
            }
        }
    }

    public function datawip($iproduct, $icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('b.i_product_wip, e_product_wipname, b.i_color, e_color_name')->distinct();
        $this->db->from('tr_polacutting_new a');
        $this->db->join('tr_product_wip b', 'b.id = a.id_product_wip');
        $this->db->join('tr_color c', 'c.i_color = b.i_color AND b.id_company = c.id_company');
        /*$this->db->where('a.id', $id);*/
        $this->db->where('b.i_product_wip', $iproduct);
        $this->db->where('b.i_color', $icolor);
        $this->db->where('b.id_company', $idcompany);
        return $this->db->get();
    }

    public function detail(/*$id, */$iproduct, $icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a .*,
                a.e_bagian AS bagian,
                a.v_bisbisan AS n_bis3,
                b.i_material,
                b.e_material_name || ' - ' || e_satuan_name AS e_material_name,
                n_bisbisan,
                f.e_jenis_potong,
                string_agg(trim(e_bagian_name), ', ') AS gudang,		
                jsonb_agg(j.id) type_makloon_id,  	
                jsonb_agg(initcap(j.e_type_makloon_name)) e_type_makloon_name,
                jsonb_agg(initcap(j.id||'|'||j.e_type_makloon_name)) makloon
            FROM
                tr_polacutting_new a
            JOIN tr_material b ON
                b.id = a.id_material
            JOIN tr_product_wip c ON
                c.id = a.id_product_wip
            LEFT JOIN tr_material_bisbisan e ON
                e.id = a.id_bisbisan
            LEFT JOIN tr_jenis_potong f ON
                f.id = e.id_jenis_potong
            LEFT JOIN tr_satuan s ON
                s.i_satuan_code = b.i_satuan_code
                AND b.id_company = s.id_company
            LEFT JOIN tr_bagian_kelompokbarang h ON
                h.i_kode_kelompok = b.i_kode_kelompok
                AND b.id_company = h.id_company
            LEFT JOIN tr_bagian i ON
                i.i_bagian = h.i_bagian
                AND h.id_company = i.id_company	
            LEFT JOIN (SELECT id, initcap(REPLACE (e_type_makloon_name,'MAKLOON','')) e_type_makloon_name FROM tr_type_makloon j ORDER BY id) j ON (j.id = any(a.id_type_makloon))	
            WHERE
                i_product_wip = '$iproduct'
                AND i_color = '$icolor'
                AND a.id_company = '$idcompany'
            GROUP BY
                1,
                b.i_material,
                b.e_material_name,
                s.e_satuan_name,
                e.n_bisbisan,
                f.e_jenis_potong
            ORDER BY
                a.id");
        /** Query diubah 2022-07-13 */
        /* $this->db->select("aa.*, a.e_bagian AS bagian, a.v_bisbisan AS n_bis3, b.i_material, b.e_material_name||' - '||e_satuan_name AS e_material_name, n_bisbisan, f.e_jenis_potong,  string_agg(trim(e_bagian_name),', ') AS gudang");
        $this->db->from('tr_polacutting_new a');
        $this->db->join('tr_material b', 'b.id = a.id_material');
        $this->db->join('tr_product_wip c', 'c.id = a.id_product_wip');
        $this->db->join('tr_material_bisbisan e', 'e.id = a.id_bisbisan', 'left');
        $this->db->join('tr_jenis_potong f', 'f.id =  e.id_jenis_potong', 'left');
        $this->db->join('tr_satuan s', 's.i_satuan_code =  b.i_satuan_code AND b.id_company = s.id_company', 'left');
        $this->db->join('tr_bagian_kelompokbarang h', 'h.i_kode_kelompok = b.i_kode_kelompok AND b.id_company = h.id_company', 'left');
        $this->db->join('tr_bagian i', 'i.i_bagian = h.i_bagian AND h.id_company = i.id_company', 'left');
        $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor);
        $this->db->where('a.id_company', $idcompany);
        $this->db->order_by('a.id');
        $this->db->group_by('1, b.i_material, b.e_material_name, s.e_satuan_name, e.n_bisbisan, f.e_jenis_potong');
        return $this->db->get(); */
        // return $this->db->query("
        //     select a.*, a.e_bagian AS bagian, a.v_bisbisan AS n_bis3, b.i_material, b.e_material_name, n_bisbisan, f.e_jenis_potong
        //     from tr_polacutting_new a
        //     inner join tr_material b on b.id = a.id_material
        // ", FALSE);
    }

    public function delete($iproduct, $icolor, $cek)
    {
        $idcompany  = $this->session->userdata('id_company');
        if ($cek != 'on') {
            $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        } else {
            $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' /* AND i_color = '$icolor' */ AND id_company = '$idcompany' ", FALSE);
        }
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $this->db->where('id_product_wip', $row->id);
                $this->db->delete('tr_polacutting_new');
            }
        }
        /* if($cek!='on'){

            $this->db->where('id_product_wip', $iproduct);
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_polacutting_new');
        }else{
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_polacutting_new');
        } */
    }

    /* public function delete($iproduct,$icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tr_polacutting');
    } */

    public function deletewip($iproduct, $icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        if ($query->num_rows() > 0) {
            $this->db->where('id_product_wip', $query->row()->id);
            /* $this->db->where('i_color', $icolor); */
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_product_wip_item');
        }
    }


// EXPORT
    public function data_export()
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.id,
                upper(trim(i_product_base)) AS i_product_base,
                a.e_product_basename,
                b.e_color_name,
                f.e_nama_divisi,
                c.e_nama_kelompok,
                d.e_type_name,
                e.e_class_name,
                id_class_product,
                (select s.e_style_name from tr_style s where s.i_style = a.i_style and s.id_company = a.id_company)as series,
                (select b.e_brand_name from tr_brand b where b.i_brand = a.i_brand and b.id_company = a.id_company)as brand
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color
                    AND a.id_company = b.id_company)
            INNER JOIN tr_kelompok_barang c ON
                (c.i_kode_kelompok = a.i_kode_kelompok
                    AND a.id_company = c.id_company)
            INNER JOIN tr_item_type d ON
                (d.i_type_code = a.i_type_code
                    AND a.id_company = d.id_company)
            LEFT JOIN tr_class_product e ON
                (e.id = a.id_class_product)
            LEFT JOIN tr_divisi_new f ON
                (f.id = c.id_divisi)
            WHERE 
                a.f_status = 't'
                AND a.id_company = '$idcompany'
            ORDER BY
                a.i_product_base,
                a.e_product_basename ASC
        ", FALSE);
    }

    
    public function get_dataheader(){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
        a.id,
        a.i_material,
        a.e_material_name,
        a.i_satuan_code,
        c.e_satuan_name,
        a.i_type_code,
        b.e_type_name,
        a.i_kode_kelompok,
        e.e_nama_kelompok,
        a.i_kode_group_barang,
        g.e_nama_group_barang,
        a.i_supplier,
        f.e_supplier_name,
        to_char(a.d_entry,'dd-mm-yyyy') AS d_register,
        a.id_company
      FROM
        tr_material a
      JOIN tr_item_type b ON
        a.i_type_code = b.i_type_code AND a.id_company = b.id_company
      JOIN tr_satuan c ON
        a.i_satuan_code = c.i_satuan_code AND a.id_company = c.id_company
      JOIN tr_kelompok_barang e ON
        a.i_kode_kelompok = e.i_kode_kelompok AND a.id_company = e.id_company
      LEFT JOIN tr_supplier f ON
        a.i_supplier = f.i_supplier AND a.id_company = f.id_company
      JOIN tr_group_barang g ON
        a.i_kode_group_barang = g.i_kode_group_barang AND a.id_company = g.id_company
      WHERE
        a.f_status = 't' AND
        a.id_company = '$idcompany'
      order by a.d_entry desc nulls last, a.d_update desc nulls last, a.i_material, a.e_material_name");
    }

    public function get_datadetail($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.*,
                c.i_material,
                c.e_material_name,
                initcap(b.e_satuan_name) AS e_satuan_name
            FROM
                tr_material_konversi a
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code_konversi
                    AND a.id_company = b.id_company)
            LEFT JOIN tr_material c ON
                (c.id = a.id_material)
            WHERE
                a.id_material IN ('$id')
            ORDER BY
                c.i_material, c.e_material_name", FALSE);
    }

    public function get_datamaterial($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT
                a.i_material,
                a.e_material_name,
                c.e_operator,
                c.n_faktor,
                initcap(c.e_satuan_name) AS e_satuan_name_konversi,
                case when c.f_default = true then 'Ya' else 'Tidak' end as f_default,
                initcap(b.e_satuan_name) AS e_satuan_name
            FROM
                tr_material a
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code
                    AND a.id_company = b.id_company)
            LEFT JOIN (SELECT 
                a.id_material,
                a.e_operator,
                a.n_faktor,
                a.i_satuan_code_konversi,
                a.f_default,
                b.e_satuan_name 
                FROM tr_material_konversi a
                LEFT JOIN tr_satuan b 
                ON (a.i_satuan_code_konversi = i_satuan_code)) C 
            ON (c.id_material = a.id)
            WHERE
                a.id IN ('$id')
                AND c.e_operator IS NOT NULL
            ORDER BY
                a.i_material, a.e_material_name", FALSE);
    }

    public function get_databisbisan($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT a.id, a.id_jenis_potong, a.id_material, a.e_material_name, 
            a.n_bisbisan , a.v_lebar_kain_awal, b.n_hilang_lebar, v_lebar_kain_akhir , v_jumlah_roll, 
            b.n_tambah_panjang , n_panjang_bis, v_panjang_bis , b.e_jenis_potong,
            c.i_material
            from tr_material_bisbisan a 
            inner join tr_jenis_potong b on (a.id_jenis_potong = b.id)
            LEFT JOIN tr_material c ON (c.id = a.id_material)
            where a.id_material IN ('$id') and a.f_status = 't' and a.id_company = '$idcompany'
            order by c.i_material, a.e_material_name
        ", FALSE);
    }

    public function data_export_redaksi(){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT c.id as IDBarang_system , c.i_product_wip, c.e_product_wipname , e.e_color_name, d.id as IDMaterial_system, d.i_material, d.e_material_name, 
            gb.e_nama_group_barang , gb.i_kode_group_barang ,
            b.e_bagian, b.v_gelar, b.v_set, f.n_bisbisan, b.v_bisbisan, f.id_jenis_potong || '.' || g.e_jenis_potong as jenis_potong, f.v_lebar_kain_awal, 
            f.v_panjang_bis as v_panjang_bis_bahan, coalesce(b.v_bisbisan / f.v_panjang_bis,0) as v_panjang_bis_produk, 
            ((1 / b.v_set) * b.v_gelar) + coalesce(b.v_bisbisan / f.v_panjang_bis,0) as total, s.e_satuan_name ,
            coalesce(string_agg(e_type_makloon_name, ', '), '') as e_type_makloon_name
            from tr_product_wip c 
            INNER JOIN tr_polacutting_new b on (b.id_product_wip = c.id)
            INNER JOIN tr_material d on (b.id_material = d.id AND b.id_company = d.id_company)
            inner join tr_satuan s on (d.i_satuan_code = s.i_satuan_code and d.id_company = s.id_company)
            INNER JOIN tr_color e on (c.i_color = e.i_color AND c.id_company = e.id_company)
            inner join tr_group_barang gb on (d.i_kode_group_barang = gb.i_kode_group_barang AND d.id_company = gb.id_company )
            LEFT JOIN tr_material_bisbisan f ON (f.id_material = b.id_material and f.id = b.id_bisbisan)
            left join tr_jenis_potong g on (f.id_jenis_potong = g.id)
            LEFT JOIN (SELECT id, initcap(REPLACE (e_type_makloon_name,'MAKLOON','')) e_type_makloon_name FROM tr_type_makloon j ORDER BY id) j ON (j.id = any(b.id_type_makloon))
            where c.id_company = '$idcompany' and c.f_status = true
            group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15, 16, 17, 18, 19,20
            order by c.i_product_wip, e.e_color_name, gb.i_kode_group_barang 
        ", FALSE);
    }

    public function data_export_panel(){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT c.i_product_wip , c.e_product_wipname , d.e_color_name , e.i_material , e.e_material_name , 
            a.i_panel , a.bagian , a.n_qty_penyusun , n_panjang_cm, n_lebar_cm, n_panjang_gelar, n_lebar_gelar, n_hasil_gelar, n_qty_per_gelar, n_efficiency,
            case when f_print = true then 'Ya' else 'Tidak' end as print, case when f_bordir = true then 'Ya' else 'Tidak' end as bordir,   g.e_style_name as e_series_name
            from tm_panel_item a
            inner join tm_panel b on (a.id_product_wip = b.id_product_wip)
            inner join tr_product_wip c on (a.id_product_wip = c.id)
            INNER JOIN tr_color d on (c.i_color = d.i_color AND c.id_company = d.id_company)
            INNER JOIN tr_material e on (a.id_material = e.id AND b.id_company = e.id_company)
            inner join tr_style g on (c.i_style = g.i_style and c.id_company = g.id_company)
            where b.id_company = '$idcompany' and a.f_status = true 
            order by 1,2,3, 5 asc;
        ", FALSE);
    }


    public function data_export_gabungan(){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            WITH cte as (
              SELECT c.id as id_product_wip, d.id as id_material, c.i_product_wip, c.e_product_wipname , e.e_color_name, d.i_material, d.e_material_name, 
              (b.v_gelar * 100)::numeric as v_gelar, b.v_set, g.e_style_name 
              from tr_product_wip c 
              INNER JOIN tr_polacutting_new b on (b.id_product_wip = c.id)
              INNER JOIN tr_material d on (b.id_material = d.id AND b.id_company = d.id_company)
              INNER JOIN tr_color e on (c.i_color = e.i_color AND c.id_company = e.id_company)
              inner join tr_style g on (c.i_style = g.i_style and c.id_company = g.id_company)
              where c.id_company = '$idcompany' and c.f_status = true and d.i_kode_group_barang = 'GRB0001' 
              and b.v_gelar <> 0 AND b.v_bisbisan <= 0 AND d.i_material NOT ILIKE 'BIS%'
              /*and c.i_product_wip = 'DGG4412' and d.i_material = 'KAI0878'
              group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15, 16, 17, 18, 19,20*/
              order by c.i_product_wip, e.e_color_name, d.i_material 
            )
            SELECT a.*, b.i_panel, b.bagian , b.n_qty_penyusun, b.n_panjang_gelar , b.n_hasil_gelar, b.n_panjang_cm , b.n_lebar_cm from cte a
            left join tm_panel_item b on (a.id_product_wip = b.id_product_wip 
            and a.id_material = b.id_material and b.f_status = true 
            and a.v_gelar = b.n_panjang_gelar and a.v_set = b.n_hasil_gelar)
            order by i_product_wip, e_color_name, i_material , bagian
        ", FALSE);
    }







}
/* End of file Mmaster.php */