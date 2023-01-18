<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data ($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND g.d_spbb BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                0 AS no,
                b.i_product_wip,
                b.e_product_wipname,
                d.e_color_name,
                c.i_material,
                a.id_material,
                c.e_material_name,
                f.e_satuan_name,
                sum(a.n_panjang_kain) AS permintaan,
                sum(COALESCE (e.n_quantity, 0)) AS pemenuhan,
                (sum(a.n_panjang_kain) - sum(COALESCE (e.n_quantity, 0))) AS selisih,
                round((sum(COALESCE (e.n_quantity, 0)) / sum(a.n_panjang_kain)) * 100, 2) AS persentase,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_spbb_item a
            INNER JOIN tm_spbb g ON
                (g.id = a.id_spbb)
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product)
            INNER JOIN tr_color d ON
                (d.i_color = b.i_color
                AND b.id_company = d.id_company)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN tr_satuan f ON
                (f.i_satuan_code = c.i_satuan_code
                AND c.id_company = f.id_company)
            LEFT JOIN (
                SELECT
                    a.id_spbb,
                    a.id_product_wip,
                    a.id_material,
                    a.n_quantity
                FROM
                    tm_keluar_produksibb_itemdetail a,
                    tm_keluar_produksibb b
                WHERE
                    a.id_document = b.id
                    AND b.i_status = '6'
                    AND a.id_company = '".$this->session->userdata('id_company')."') e ON
                (e.id_spbb = a.id_spbb
                AND a.id_product = e.id_product_wip
                AND e.id_material = a.id_material)
            WHERE
                g.i_status = '6'
                $where
                AND g.id_company = '".$this->session->userdata('id_company')."'
            GROUP BY
                2, 3, 4, 5, 6, 7, 8
            ",
            FALSE
        );

        $datatables->add('action', function ($data) {
            $id            = $data['id_material'];
            $imaterial     = $data['i_material'];
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $dfrom         = $data['dfrom'];
            $dto           = $data['dto'];
            $data          = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/detail/$id/$dfrom/$dto/$imaterial\",\"#main\"); return false;'><i class='ti-eye'></i></a>";
            }
            return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('id_material');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function detail($imaterial,$dfrom,$dto)
    {
        return $this->db->query("
            SELECT
                DISTINCT c.i_material,
                c.e_material_name,
                f.e_satuan_name AS e_satuan,
                g.i_spbb,
                sum(a.n_panjang_kain) AS permintaan,
                sum(COALESCE (e.n_quantity, 0)) AS pemenuhan,
                round(sum(COALESCE (e.n_quantity, 0)) / sum(p.v_gelar), 2) AS jumlah_gelar
            FROM
                tm_spbb_item a
            INNER JOIN tm_spbb g ON
                (g.id = a.id_spbb)
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product)
            INNER JOIN tr_color d ON
                (d.i_color = b.i_color
                AND b.id_company = d.id_company)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN tr_satuan f ON
                (f.i_satuan_code = c.i_satuan_code
                AND c.id_company = f.id_company)
            INNER JOIN tr_polacutting p ON
                (p.i_product_wip = b.i_product_wip
                AND c.i_material = p.i_material
                AND p.id_company = '".$this->session->userdata('id_company')."')
            LEFT JOIN (
                SELECT
                    a.id_spbb,
                    a.id_product_wip,
                    a.id_material,
                    a.n_quantity
                FROM
                    tm_keluar_produksibb_itemdetail a,
                    tm_keluar_produksibb b
                WHERE
                    a.id_document = b.id
                    AND b.i_status = '6'
                    AND a.id_company = '".$this->session->userdata('id_company')."') e ON
                (e.id_spbb = a.id_spbb
                AND a.id_product = e.id_product_wip
                AND e.id_material = a.id_material)
            WHERE
                g.i_status = '6'
                AND a.id_material = '$imaterial'
                AND g.d_spbb BETWEEN '".date('Y-m-d', strtotime($dfrom))."' AND '".date('Y-m-d', strtotime($dto))."'
                AND g.id_company = '".$this->session->userdata('id_company')."'
            GROUP BY
                1, 2, 3, 4
        ", FALSE);
    }

    public function export($dfrom,$dto)
    {
        return $this->db->query("
            SELECT
                b.i_product_wip AS i_product,
                b.e_product_wipname AS e_product_name,
                d.e_color_name,
                c.i_material,
                c.e_material_name,
                f.e_satuan_name AS e_satuan,
                g.i_spbb,
                sum(a.n_panjang_kain) AS permintaan,
                sum(COALESCE (e.n_quantity, 0)) AS pemenuhan,
                round(sum(COALESCE (e.n_quantity, 0)) / sum(p.v_gelar), 2) AS jumlah_gelar,
                round((sum(COALESCE (e.n_quantity, 0)) / sum(a.n_panjang_kain)), 2) AS persentase
            FROM
                tm_spbb_item a
            INNER JOIN tm_spbb g ON
                (g.id = a.id_spbb)
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product)
            INNER JOIN tr_color d ON
                (d.i_color = b.i_color
                AND b.id_company = d.id_company)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN tr_satuan f ON
                (f.i_satuan_code = c.i_satuan_code
                AND c.id_company = f.id_company)
            INNER JOIN tr_polacutting p ON
                (p.i_product_wip = b.i_product_wip
                AND c.i_material = p.i_material
                AND p.id_company = '".$this->session->userdata('id_company')."')
            LEFT JOIN (
                SELECT
                    a.id_spbb,
                    a.id_product_wip,
                    a.id_material,
                    a.n_quantity
                FROM
                    tm_keluar_produksibb_itemdetail a,
                    tm_keluar_produksibb b
                WHERE
                    a.id_document = b.id
                    AND b.i_status = '6'
                    AND a.id_company = '".$this->session->userdata('id_company')."') e ON
                (e.id_spbb = a.id_spbb
                AND a.id_product = e.id_product_wip
                AND e.id_material = a.id_material)
            WHERE
                g.i_status = '6'
                AND g.d_spbb BETWEEN '".date('Y-m-d', strtotime($dfrom))."' AND '".date('Y-m-d', strtotime($dto))."'
                AND g.id_company = '".$this->session->userdata('id_company')."'
            GROUP BY
                1, 2, 3, 4, 5, 6, 7
        ", FALSE);
    }
}

/* End of file Mmaster.php */
