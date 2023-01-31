<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($i_menu, $folder, $dfrom, $dto)
    {
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_forecast_produksi
            WHERE
                i_status <> '5'
                AND to_date(periode,'YYYYmm') between to_date('$dfrom','01-mm-yyyy') 
                AND to_date('$dto','01-mm-yyyy') 
                AND id_company = '$this->company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')

        ", FALSE);
        if ($this->departement == '1') {
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
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                0 AS NO, a.id, a.i_document,
                bulan(to_date(a.periode, 'YYYYmm')) || ' ' || substring(a.periode, 1, 4) AS periode,
                substring(a.periode, 1, 4) AS tahun, substring(a.periode, 5, 6) AS bulan,
                case when a.f_over_budget = TRUE then 'Ya' else 'Tidak' end as over_budget,
                c.e_bagian_name,
                (
                    SELECT sum(greatest(n_fc_berjalan,qty_do) + n_fc_next + n_quantity_fc) FROM produksi.tm_forecast_produksi_item 
                    WHERE id_company = '$this->company' AND id_forecast = a.id
                ) as totalqty_prod,
                (
                    SELECT sum(n_quantity) FROM produksi.tm_forecast_produksi_item 
                    WHERE id_company = '$this->company' AND id_forecast = a.id
                ) as totalqty,
                e_remark_supplier, a.i_status, e_status_name, label_color,
                a.i_bagian, l.i_level, l.e_level_name,
                '$i_menu' AS i_menu, '$folder' AS folder, '$dfrom' AS dfrom,'$dto' AS dto
            FROM
                tm_forecast_produksi a
            INNER JOIN tr_bagian c ON (a.i_bagian = c.i_bagian AND a.id_company = c.id_company)
            INNER JOIN tr_status_document d ON (d.i_status = a.i_status)
            LEFT JOIN public.tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l on (e.i_level = l.i_level)
            WHERE
                to_date(a.periode, 'YYYYmm') BETWEEN to_date('$dfrom', '01-mm-yyyy') AND to_date('$dto', '01-mm-yyyy')
                AND a.i_status <> '5'
                AND a.id_company = '$this->company' $bagian
        ",
            FALSE
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rounded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id           = $data['id'];
            $i_menu       = $data['i_menu'];
            $i_status     = $data['i_status'];
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_bagian     = $data['i_bagian'];
            $totalqty     = $data['totalqty'];
            $tahun        = $data['tahun'];
            $bulan        = $data['bulan'];
            $i_level      = $data['i_level'];
            $data         = '';

            if (check_role($i_menu, 6)) {
                $data     .= "<a href=\"" . base_url($folder . '/cform/export_excel/' . $i_bagian . '/' . $tahun . '/' . $bulan . '/' . $dfrom . '/' . $dto . '/' . $id) . "\" title='Export'><i class='ti-download fa-lg mr-3 text-success'></i></a>";
            }

            if (check_role($i_menu, 2)) {
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$i_bagian/$tahun/$bulan/$dfrom/$dto/$id/\",\"#main\"); return false;'><i class='ti-eye fa-lg mr-3'></i></a>";
            }

            if (check_role($i_menu, 3)) { //&& ($tahun.$bulan) > date('Ym')
                if (($i_status == '1' || $i_status == '2' || $i_status == '3')) {
                    $data    .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$dfrom/$dto/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt text-success fa-lg mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if ($i_level == $this->session->userdata('i_level') || 1 == $this->session->userdata('i_level')) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$i_bagian/$tahun/$bulan/$dfrom/$dto/$id/\",\"#main\"); return false;'><i class='ti-check-box text-primary fa-lg -mr-3'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger fa-lg'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('label_color');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_bagian');
        $datatables->hide('tahun');
        $datatables->hide('bulan');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
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
        $now = date('Y-m-d');
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu , a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut from tm_forecast_produksi a
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
                $this->db->query("delete from tm_menu_approve where i_menu = '$this->i_menu' and i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_forecast_produksi');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_forecast_produksi', $data);
    }

    function cek_datadet($idcompany)
    {
        return $this->db->query(
            "SELECT
                c.id AS id_product_base,
                i_product_base,
                e_product_basename,
                c.v_unitprice AS v_harga,
                0 AS n_quantity,
                0 AS n_quantity_sisa,
                '' AS e_remark,
                d.e_color_name
            FROM
                tr_product_base c
            INNER JOIN tr_color d ON
                (c.i_color = d.i_color
                AND c.id_company = d.id_company)
            WHERE
                c.id_company = '$idcompany'
            ORDER BY
                c.e_product_basename
        ",
            FALSE
        );
    }

    public function simpan($id, $idcompany, $ibagian, $periode, $eremarkh, $idocument, $f_over_budget)
    {
        $this->db->query("INSERT INTO tm_forecast_produksi(id, id_company, i_bagian, periode, e_remark_supplier, i_document, f_over_budget) 
            VALUES ('$id', '$idcompany', '$ibagian', '$periode', '$eremarkh', '$idocument','$f_over_budget')
            ON CONFLICT (id) DO UPDATE 
                SET i_bagian = excluded.i_bagian, 
                    d_entry = excluded.d_entry, 
                    i_document = excluded.i_document;
            ", false);
    }

    public function update($id, $idcompany, $ibagian, $eremarkh)
    {
        $this->db->query("UPDATE tm_forecast_produksi set i_bagian = '$ibagian', e_remark_supplier = '$eremarkh', d_update = now(), i_status = '1' where id = '$id'", false);
    }

    public function simpandetail(
        $idcompany,
        $id,
        $id_product,
        $persen_up,
        $qty,
        $e_remark,
        $qty_fc,
        $qty_stock,
        $n_fc_berjalan,
        $estimasi,
        $n_fc_next,
        $nquantity_stock_wip,
        $nquantity_stock_jahit,
        $nquantity_stock_pengadaan,
        $nquantity_stock_packing,
        $nquantity_tmp
    ) {
        $data = array(
            'id_company'      => $idcompany,
            'id_forecast'     => $id,
            'id_product'      => $id_product,
            'persen_up'       => $persen_up,
            'n_quantity'      => $qty,
            'n_quantity_sisa' => $qty,
            'n_quantity_fc'   => $qty_fc,
            'n_quantity_stock' => $qty_stock,
            'e_remark'        => $e_remark,
            'n_quantity_wip'    => $nquantity_stock_wip,
            'n_quantity_unitjahit'  => $nquantity_stock_jahit,
            'n_quantity_pengadaan'  => $nquantity_stock_pengadaan,
            'n_quantity_packing'  => $nquantity_stock_packing,
            'qty_do'    => $estimasi,
            'n_fc_berjalan' => $n_fc_berjalan,
            'n_fc_next' => $n_fc_next
        );

        $this->db->insert('tm_forecast_produksi_item', $data);
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("
				SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function get_bagian($ibagian, $idcompany)
    {
        $this->db->select(" i_bagian, e_bagian_name from tr_bagian where i_bagian = '$ibagian' and id_company = '$idcompany' ", false);
        return $this->db->get();
    }

    public function cek_produk($idproduct)
    {
        return $this->db->query("select id from tr_product_base where id = '$idproduct' and id_company = '$this->company' ", FALSE);
    }

    public function dataheader($idcompany, $periode)
    {
        $this->db->select("id, e_remark_supplier, coalesce(i_status,'1') as i_status from tm_forecast_produksi where id_company = '$idcompany' and periode = '$periode' ", false);
        return $this->db->get();
    }

    public function datadetail($idcompany, $periode, $id)
    {
        $i_periode = $periode;
        $i_periode_now = date('Ym');

        $dfrom = substr($i_periode_now, 0, 4) . '-' . substr($i_periode_now, 4, 2) . '-01';
        $dto   = date('Y-m-t', strtotime($dfrom));
        $i_periode_last = date('Ym', strtotime('-1 month', strtotime(substr($periode, 0, 4) . '-' . substr($periode, 4, 2))));
        $i_periode_next = date('Ym', strtotime('+1 month', strtotime(substr($periode, 0, 4) . '-' . substr($periode, 4, 2))));
        // var_dump($dfrom);
        // var_dump($dto);
        // var_dump($i_periode_last);
        // var_dump($i_periode);
        // var_dump($i_periode_next); die;
        // var_dump(expression)

        return $this->db->query("
        WITH cte AS (
            SELECT
                c.id_company ,
                c.id AS id_product_base,
                c.i_product_base,
                e_product_basename,
                d.e_color_name ,
                e.e_class_name,
                i.e_type_name AS sub_kategori,
                g.e_brand_name AS brand ,
                h.e_style_name AS STYLE
            FROM
                tr_product_base c
            INNER JOIN tr_color d ON
                (c.i_color = d.i_color
                    AND c.id_company = d.id_company)
            INNER JOIN (
                SELECT
                    DISTINCT b.id_product
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (periode,
                        id_customer) id
                    FROM
                        tm_forecast_distributor
                    WHERE
                        periode IN ('$i_periode_last', '$i_periode', '$i_periode_next')
                            AND id_company = '$this->id_company'
                            AND i_status = '6'
                        ORDER BY
                            periode,
                            id_customer,
                            CASE
                                WHEN d_update NOTNULL THEN d_update
                                ELSE d_entry
                            END DESC NULLS LAST
                 ) AS a
                INNER JOIN tm_forecast_distributor_item b ON
                    (a.id = b.id_forecast)
              ) AS f ON
                (c.id = f.id_product)
            LEFT JOIN tr_class_product e ON
                (c.id_class_product = e.id)
            LEFT JOIN tr_item_type i ON
                (c.i_type_code = i.i_type_code
                    AND c.id_company = i.id_company)
            LEFT JOIN tr_brand g ON
                (c.i_brand = g.i_brand
                    AND c.id_company = g.id_company)
            LEFT JOIN tr_style h ON
                (c.i_style = h.i_style
                    AND c.id_company = h.id_company)
            WHERE
                c.id_company = '$this->id_company'   
            )   
              SELECT
                a.id_company,
                a.id_product_base,
                a.i_product_base,
                a.e_product_basename,
                a.e_color_name,
                a.e_remark,
                COALESCE(cc.n_quantity, 0) AS n_quantity_fc,
                COALESCE(cc.n_quantity_sisa, 0) AS n_quantity_sisa,
                a.kategori,
                a.n_quantity_stock,
                a.n_quantity_wip,
                a.n_quantity_unitjahit,
                a.n_quantity_pengadaan,
                a.n_packing,
                a.v_harga,
                COALESCE(e.n_persen_up, 0) AS persen_up,
                COALESCE(b.qty_do, 0) AS qty_do,
                COALESCE(c.n_fc_berjalan, 0) AS n_fc_berjalan,
                COALESCE(d.n_fc_next, 0) AS n_fc_next,
                GREATEST ( 
                (COALESCE(cc.n_quantity, 0) + GREATEST(COALESCE(b.qty_do, 0), COALESCE(c.n_fc_berjalan, 0)) + COALESCE(d.n_fc_next, 0)) - 
                 GREATEST( (n_quantity_stock + n_quantity_wip + n_quantity_unitjahit + n_quantity_pengadaan + n_packing), 0)
              ,
                0) AS n_quantity,
                a.kategori,
                sub_kategori,
                brand ,
                STYLE
            FROM
                (
                SELECT
                    a.id_company ,
                    a.id_product_base,
                    a.i_product_base,
                    a.e_product_basename,
                    a.e_color_name,
                    '' AS e_remark,
                    a.e_class_name AS kategori,
                    sub_kategori,
                    brand ,
                    STYLE,
                    COALESCE(sum(e.n_saldo_akhir), 0) AS n_quantity_stock,
                    COALESCE(sum(f.n_saldo_akhir), 0) AS n_quantity_wip,
                    COALESCE(sum(g.saldo_akhir), 0) AS n_quantity_unitjahit,
                    COALESCE (sum(h.n_saldo_akhir),
                    0) AS n_quantity_pengadaan,
                    COALESCE (sum(i.n_saldo_akhir), 0) AS n_packing,
                    0 AS v_harga,
                    0 AS persen_up
                FROM
                    cte a
                LEFT JOIN f_mutasi_gudang_jadi ('$this->id_company',
                    '$i_periode',
                    '9999-01-01',
                    '9999-01-31',
                    '$dfrom',
                    '$dto',
                    '') e ON
                    (e.id_product_base = a.id_product_base
                        AND a.id_company = e.id_company)
                LEFT JOIN f_mutasi_wip ('$this->id_company',
                    '$i_periode',
                    '9999-01-01',
                    '9999-01-31',
                    '$dfrom',
                    '$dto',
                    '') f ON
                    (f.id_product_base = a.id_product_base
                        AND a.id_company = f.id_company)
                LEFT JOIN (
                    SELECT
                        id_company ,
                        id_product_base,
                        sum(saldo_akhir) AS saldo_akhir
                    FROM
                        f_mutasi_unitjahit('$this->id_company',
                        '$i_periode',
                        '9999-01-01',
                        '9999-01-31',
                        '$dfrom',
                        '$dto',
                        '')
                    GROUP BY
                        1,
                        2
              ) g ON
                    (g.id_product_base = a.id_product_base
                        AND a.id_company = g.id_company)
                LEFT JOIN (
                    SELECT
                        a.*,
                        c.id id_product_base
                    FROM
                        f_mutasi_saldoawal_pengadaan_newbie ('$this->id_company',
                        '$i_periode',
                        '9999-01-01',
                        '9999-01-31',
                        '$dfrom',
                        '$dto',
                        '') a
                    INNER JOIN tr_product_wip b ON
                        (b.id = a.id_product_wip)
                    INNER JOIN tr_product_base c ON
                        (c.i_product_wip = b.i_product_wip
                            AND b.i_color = c.i_color
                            AND c.id_company = b.id_company)) h ON
                    (h.id_product_base = a.id_product_base
                        AND a.id_company = h.id_company)
                LEFT JOIN f_mutasi_packing ('$this->id_company',
                    '$i_periode',
                    '9999-01-01',
                    '9999-01-31',
                    '$dfrom',
                    '$dto',
                    '') i ON
                    (i.id_product_base = a.id_product_base
                        AND a.id_company = i.id_company)
                GROUP BY
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10 
              ) AS a
            LEFT JOIN (
                SELECT
                    b.id_product,
                    b.id_company,
                    sum(b.n_quantity) AS qty_do
                FROM
                    tm_sj a
                INNER JOIN tm_sj_item b ON
                    (b.id_document = a.id)
                WHERE
                    a.i_status = '6'
                    AND a.id_company = '$this->id_company'
                    AND to_char(a.d_document, 'YYYYMM') = '$i_periode_last'
                GROUP BY
                    1,
                    2
              ) b ON
                (b.id_product = a.id_product_base
                    AND a.id_company = b.id_company)
            LEFT JOIN (
                SELECT
                    b.id_company,
                    b.id_product,
                    sum(b.n_quantity) AS n_fc_berjalan
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (id_customer) a.id
                    FROM
                        tm_forecast_distributor a
                    WHERE
                        a.id_company = '$this->id_company'
                        AND a.periode = '$i_periode_last'
                        AND i_status = '6'
                    ORDER BY
                        id_customer,
                        COALESCE(d_update, d_entry) DESC
                  ) AS a
                INNER JOIN tm_forecast_distributor_item b ON
                    (a.id = b.id_forecast)
                GROUP BY
                    1,
                    2
              ) c ON
                (c.id_product = a.id_product_base
                    AND a.id_company = c.id_company)
            LEFT JOIN (
                SELECT
                    b.id_company,
                    b.id_product,
                    sum(b.n_quantity) AS n_quantity,
                    sum(b.n_quantity_sisa) AS n_quantity_sisa
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (id_customer) a.id
                    FROM
                        tm_forecast_distributor a
                    WHERE
                        a.id_company = '$this->id_company'
                        AND a.periode = '$i_periode'
                        AND i_status = '6'
                    ORDER BY
                        id_customer,
                        COALESCE(d_update, d_entry) DESC
                  ) AS a
                INNER JOIN tm_forecast_distributor_item b ON
                    (a.id = b.id_forecast)
                GROUP BY
                    1,
                    2
              ) cc ON
                (cc.id_product = a.id_product_base
                    AND a.id_company = cc.id_company)
            LEFT JOIN (
                SELECT
                    b.id_company,
                    b.id_product,
                    sum(b.n_quantity) AS n_fc_next
                FROM
                    (
                    SELECT
                        DISTINCT ON
                        (id_customer) a.id
                    FROM
                        tm_forecast_distributor a
                    WHERE
                        a.id_company = '$this->id_company'
                        AND a.periode = '$i_periode_next'
                        AND i_status = '6'
                    ORDER BY
                        id_customer,
                        COALESCE(d_update, d_entry) DESC
                  ) AS a
                INNER JOIN tm_forecast_distributor_item b ON
                    (a.id = b.id_forecast)
                GROUP BY
                    1,
                    2
              ) d ON
                (d.id_product = a.id_product_base
                    AND a.id_company = d.id_company)
            LEFT JOIN tm_forecast_produksi_item_tmp e ON
                (e.id_product_base = a.id_product_base)
            ORDER BY
                kategori,
                i_product_base
        ");

        // TERBARU : 12-10-2022
        // return $this->db->query("
        //     WITH cte as (
        //       select c.id_company , c.id as id_product_base, c.i_product_base, e_product_basename, d.e_color_name , e.e_class_name,
        //       i.e_type_name as sub_kategori,  g.e_brand_name as brand , h.e_style_name as style
        //       from tr_product_base c 
        //       INNER JOIN tr_color d ON (c.i_color = d.i_color AND c.id_company = d.id_company)
        //       INNER JOIN (
        //          select distinct b.id_product from (
        //               SELECT distinct on (periode, id_customer) id from tm_forecast_distributor
        //               where periode IN ('$i_periode_last','$i_periode','$i_periode_next') AND id_company = '$idcompany' AND i_status = '6'
        //               ORDER BY periode, id_customer, CASE WHEN d_update NOTNULL THEN d_update ELSE d_entry END DESC NULLS last
        //          ) as a
        //          inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
        //       ) as f on (c.id = f.id_product)
        //       left join tr_class_product e on (c.id_class_product = e.id)
        //       left join tr_item_type i on (c.i_type_code = i.i_type_code and c.id_company = i.id_company)
        //       left join tr_brand g on (c.i_brand = g.i_brand and c.id_company = g.id_company)
        //       left join tr_style h on (c.i_style = h.i_style and c.id_company = h.id_company)
        //       WHERE c.id_company = '$idcompany'   
        //     )   
        //       select a.id_company, a.id_product_base, a.i_product_base, a.e_product_basename, a.e_color_name, a.e_remark, COALESCE(cc.n_quantity,0) as n_quantity_fc, COALESCE(cc.n_quantity_sisa,0) as n_quantity_sisa,
        //       a.kategori,  a.n_quantity_stock, a.n_quantity_wip, a.n_quantity_unitjahit, a.n_quantity_pengadaan, a.v_harga, coalesce(e.n_persen_up,0) as persen_up,
        //       COALESCE(b.qty_do,0) AS qty_do,  COALESCE(c.n_fc_berjalan,0) AS n_fc_berjalan,  COALESCE(d.n_fc_next,0) AS n_fc_next,
        //       GREATEST ( 
        //         (COALESCE(cc.n_quantity,0) + GREATEST(COALESCE(b.qty_do,0), COALESCE(c.n_fc_berjalan,0)) + COALESCE(d.n_fc_next,0)) - 
        //          GREATEST( (n_quantity_stock + n_quantity_wip + n_quantity_unitjahit + n_quantity_pengadaan),0)
        //       ,0) as n_quantity, a.kategori, sub_kategori, brand , style
        //       from (
        //           select a.id_company ,a.id_product_base, a.i_product_base, a.e_product_basename, a.e_color_name, '' as e_remark,
        //           a.e_class_name as kategori, sub_kategori, brand , style, 
        //           coalesce(sum(e.n_saldo_akhir),0) AS n_quantity_stock, 
        //           COALESCE(sum(f.n_saldo_akhir),0) AS n_quantity_wip, 
        //           COALESCE(sum(g.saldo_akhir),0) AS n_quantity_unitjahit, 
        //           COALESCE (sum(h.n_saldo_akhir),0) AS n_quantity_pengadaan,
        //           0 AS v_harga, 0 AS persen_up 
        //           from cte a 
        //           LEFT JOIN f_mutasi_gudang_jadi ('$idcompany','$i_periode_last','9999-01-01','9999-01-31','$dfrom','$dto','') e ON (e.id_product_base = a.id_product_base AND a.id_company = e.id_company) 
        //           LEFT JOIN f_mutasi_wip ('$idcompany','$i_periode_last','9999-01-01','9999-01-31','$dfrom','$dto','') f ON (f.id_product_base = a.id_product_base AND a.id_company = f.id_company) 
        //           LEFT JOIN (
        //             select id_company , id_product_base, sum(saldo_akhir) as saldo_akhir from f_mutasi_unitjahit('$idcompany','$i_periode_last','9999-01-01','9999-01-31','$dfrom','$dto','') group by 1,2
        //           ) g ON (g.id_product_base =a.id_product_base AND a.id_company = g.id_company)
        //           LEFT JOIN (SELECT a.*, c.id id_product_base FROM f_mutasi_saldoawal_pengadaan_newbie ('$idcompany','$i_periode_last','9999-01-01','9999-01-31','$dfrom','$dto','') a INNER JOIN tr_product_wip b ON (b.id = a.id_product_wip) INNER JOIN tr_product_base c ON (c.i_product_wip = b.i_product_wip AND b.i_color = c.i_color AND c.id_company = b.id_company)) h ON (h.id_product_base =a.id_product_base AND a.id_company = h.id_company) 
        //           GROUP BY 1,2,3,4,5,6,7,8,9,10 
        //       ) as a 
        //       LEFT JOIN (
        //           SELECT b.id_product, b.id_company, sum(b.n_quantity) AS qty_do FROM tm_sj a
        //           INNER JOIN tm_sj_item b ON (b.id_document = a.id) 
        //           WHERE a.i_status = '6' AND a.id_company = '$idcompany'
        //           AND to_char(a.d_document,'YYYYMM') = '$i_periode_last' 
        //           GROUP BY 1,2
        //       ) b ON (b.id_product = a.id_product_base AND a.id_company = b.id_company)
        //       LEFT JOIN (
        //           select b.id_company, b.id_product, sum(b.n_quantity) as n_fc_berjalan from (
        //                select DISTINCT ON (id_customer) a.id from tm_forecast_distributor a
        //                where a.id_company = '$idcompany' and a.periode = '$i_periode_last' and i_status = '6'
        //                ORDER  BY id_customer, coalesce(d_update,d_entry) DESC
        //           ) as a
        //           inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
        //           group by 1,2
        //       ) c on (c.id_product = a.id_product_base AND a.id_company = c.id_company)
        //       LEFT JOIN (
        //           select b.id_company, b.id_product, sum(b.n_quantity) as n_quantity,  sum(b.n_quantity_sisa) as n_quantity_sisa from (
        //                select DISTINCT ON (id_customer) a.id from tm_forecast_distributor a
        //                where a.id_company = '$idcompany' and a.periode = '$i_periode' and i_status = '6'
        //                ORDER  BY id_customer, coalesce(d_update,d_entry) DESC
        //           ) as a
        //           inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
        //           group by 1,2
        //       ) cc on (cc.id_product = a.id_product_base AND a.id_company = cc.id_company)
        //       LEFT JOIN (
        //           select b.id_company, b.id_product, sum(b.n_quantity) as n_fc_next from (
        //                select DISTINCT ON (id_customer) a.id from tm_forecast_distributor a
        //                where a.id_company = '$idcompany' and a.periode = '$i_periode_next' and i_status = '6'
        //                ORDER  BY id_customer, coalesce(d_update,d_entry) DESC
        //           ) as a
        //           inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
        //           group by 1,2
        //       ) d on (d.id_product = a.id_product_base AND a.id_company = d.id_company)
        //       left join tm_forecast_produksi_item_tmp e on (e.id_product_base = a.id_product_base)
        //       ORDER BY kategori, i_product_base
        // ", FALSE);



        // return $this->db->query("SELECT DISTINCT a.id_company,a.id AS id_product_base,a.i_product_base,a.e_product_basename,b.e_color_name,'' AS e_remark,
        //         ab.e_class_name AS kategori, COALESCE(e.saldo_akhir, 0) AS n_quantity_stock, COALESCE(f.saldo_akhir, 0) AS n_quantity_wip, COALESCE(g.saldo_akhir, 0) AS n_quantity_unitjahit,
        //         COALESCE(h.saldo_akhir,0) AS n_quantity_pengadaan,0 AS v_harga, 0 AS persen_up,COALESCE(l.n_quantity, 0) AS qty_do,
        //         COALESCE(i.n_quantity,0) AS n_fc_berjalan,COALESCE(j.n_quantity,0) AS n_quantity_fc,COALESCE(k.n_quantity,0) AS n_fc_next,
        //         (COALESCE(j.n_quantity,0) + GREATEST(COALESCE(l.n_quantity, 0), COALESCE(i.n_quantity, 0)) + COALESCE(k.n_quantity, 0)) - GREATEST( (e.saldo_akhir + f.saldo_akhir + g.saldo_akhir + h.saldo_akhir), 0) AS n_quantity
        //     FROM
        //         tr_product_base a
        //     INNER JOIN tr_color b ON
        //         (b.i_color = a.i_color
        //             AND a.id_company = b.id_company)
        //     INNER JOIN tr_class_product ab ON 
        //         (ab.id = a.id_class_product)
        //     INNER JOIN (SELECT id_product FROM tm_forecast_distributor_item WHERE id_forecast IN ( SELECT id FROM tm_forecast_distributor WHERE i_status = '6' AND id_company = '$idcompany' AND periode IN ('$i_periode_last', '$periode', '$i_periode_next'))) c ON
        //         (c.id_product = a.id) 
        //     LEFT JOIN f_mutasi_saldoawal_gdjadi_new ('$idcompany','$periode','9999-01-01','9999-01-31','$dfrom','$dto','') e ON
        //         (e.id_product_base = a.id AND a.id_company = e.id_company)
        //     LEFT JOIN f_mutasi_saldoawal_wip_new ('$idcompany','$periode','9999-01-01','9999-01-31','$dfrom','$dto','') f ON
        //         (f.id_product_base = a.id AND a.id_company = f.id_company)
        //     LEFT JOIN f_mutasi_saldoawal_unitjahit_new ('$idcompany','$periode','9999-01-01','9999-01-31','$dfrom','$dto','') g ON
        //         (g.id_product_base = a.id AND a.id_company = g.id_company)
        //     LEFT JOIN f_mutasi_saldoawal_pengadaan_new ('$idcompany','$periode','9999-01-01','9999-01-31','$dfrom','$dto','') h ON
        //         (h.id_product_base = a.id AND a.id_company = h.id_company)
        //     LEFT JOIN (SELECT id_product, n_quantity FROM tm_forecast_distributor_item WHERE id_forecast IN ( SELECT id FROM tm_forecast_distributor WHERE i_status = '6' AND id_company = '$idcompany' AND periode = '$i_periode_last')) i ON
        //         (i.id_product = a.id)
        //     LEFT JOIN (SELECT id_product, n_quantity FROM tm_forecast_distributor_item WHERE id_forecast IN ( SELECT id FROM tm_forecast_distributor WHERE i_status = '6' AND id_company = '$idcompany' AND periode = '$periode')) j ON
        //         (j.id_product = a.id)
        //     LEFT JOIN (SELECT id_product, n_quantity FROM tm_forecast_distributor_item WHERE id_forecast IN ( SELECT id FROM tm_forecast_distributor WHERE i_status = '6' AND id_company = '$idcompany' AND periode = '$i_periode_next')) k ON
        //         (k.id_product = a.id)
        //     LEFT JOIN (SELECT id_product, n_quantity FROM tm_sj_item WHERE id_document IN ( SELECT id FROM tm_sj WHERE i_status = '6' AND id_company = '$idcompany' AND to_char(d_document,'YYYYMM') = '$i_periode_last')) l ON
        //         (l.id_product = a.id)
        //     ORDER BY 7,3
        // ");

        /** BACKUP 2022-04-14 */

        //  return $this->db->query("
        //     WITH cte as (
        //          select a.id_company , a.id_product AS id_product_base, c.i_product_base, e_product_basename, d.e_color_name , e.e_class_name,
        //          sum(a.n_quantity) AS n_quantity_fc, sum(a.n_quantity_sisa) AS n_quantity_sisa
        //          FROM 
        //          tm_forecast_distributor_item a 
        //          INNER JOIN (SELECT id, id_company,
        //                 i_status,
        //                 periode,
        //                 d_entry,
        //                 d_approve
        //             FROM
        //                 tm_forecast_distributor
        //             WHERE
        //                 periode IN ('$i_periode')
        //                 AND id_company = '$idcompany'
        //                 AND i_status = '6'
        //             ORDER BY CASE WHEN d_update NOTNULL THEN d_update ELSE d_entry END DESC NULLS LAST) b ON (b.id = a.id_forecast) 
        //          INNER JOIN tr_product_base c ON (c.id = a.id_product) 
        //          INNER JOIN tr_color d ON (c.i_color = d.i_color AND c.id_company = d.id_company)
        //          left join tr_class_product e on (c.id_class_product = e.id)
        //          WHERE b.i_status = '6' and b.id_company = '$idcompany' AND b.periode = '$periode'
        //          group by 1,2,3,4,5,6
        //     )

        //         select a.id_company, a.id_product_base, a.i_product_base, a.e_product_basename, a.e_color_name, a.e_remark, a.n_quantity_fc, a.n_quantity_sisa,
        //         a.kategori,  a.n_quantity_stock, a.n_quantity_wip, a.n_quantity_unitjahit, a.n_quantity_pengadaan, a.v_harga, a.persen_up,
        //         COALESCE(b.qty_do,0) AS qty_do,  COALESCE(c.n_fc_berjalan,0) AS n_fc_berjalan,  COALESCE(d.n_fc_next,0) AS n_fc_next,
        //         (a.n_quantity_fc+ GREATEST(COALESCE(b.qty_do,0), COALESCE(c.n_fc_berjalan,0)) + COALESCE(d.n_fc_next,0)) - 
        //         GREATEST( (n_quantity_stock + n_quantity_wip + n_quantity_unitjahit + n_quantity_pengadaan),0) as n_quantity
        //         from (
        //             select a.id_company ,a.id_product_base, a.i_product_base, a.e_product_basename, a.e_color_name, '' as e_remark, a.n_quantity_fc, a.n_quantity_sisa,
        //             a.e_class_name as kategori,
        //             coalesce(sum(e.saldo_akhir),0) AS n_quantity_stock, 
        //             COALESCE(sum(f.saldo_akhir),0) AS n_quantity_wip, 
        //             COALESCE(sum(g.saldo_akhir),0) AS n_quantity_unitjahit, 
        //             COALESCE (sum(h.saldo_akhir),0) AS n_quantity_pengadaan,
        //             0 AS v_harga, 0 AS persen_up 
        //             from cte a 
        //             LEFT JOIN f_mutasi_saldoawal_gdjadi_new ('$idcompany','$i_periode','9999-01-01','9999-01-31','$dfrom','$dto','') e ON (e.id_product_base = a.id_product_base AND a.id_company = e.id_company) 
        //             LEFT JOIN f_mutasi_saldoawal_wip_new ('$idcompany','$i_periode','9999-01-01','9999-01-31','$dfrom','$dto','') f ON (f.id_product_base = a.id_product_base AND a.id_company = f.id_company) 
        //             LEFT JOIN f_mutasi_saldoawal_unitjahit_new ('$idcompany','$i_periode','9999-01-01','9999-01-31','$dfrom','$dto','') g ON (g.id_product_base =a.id_product_base AND a.id_company = g.id_company) 
        //             LEFT JOIN f_mutasi_saldoawal_pengadaan_new ('$idcompany','$i_periode','9999-01-01','9999-01-31','$dfrom','$dto','') h ON (h.id_product_base =a.id_product_base AND a.id_company = h.id_company) 
        //             GROUP BY 1,2,3,4,5,6,7,8,9
        //         ) as a 
        //         LEFT JOIN (
        //             SELECT b.id_product, b.id_company, sum(b.n_quantity) AS qty_do FROM tm_sj a
        //             INNER JOIN tm_sj_item b ON (b.id_document = a.id) 
        //             WHERE a.i_status = '6' AND a.id_company = '$this->id_company'
        //             AND to_char(a.d_document,'YYYYMM') = '$i_periode_last' 
        //             GROUP BY 1,2
        //         ) b ON (b.id_product = a.id_product_base AND a.id_company = b.id_company)
        //         LEFT JOIN (
        //             select b.id_company, b.id_product, sum(b.n_quantity) as n_fc_berjalan from (
        //                  select DISTINCT ON (id_customer) a.id from tm_forecast_distributor a
        //                  where a.id_company = '$this->id_company' and a.periode = '$i_periode_last' and i_status = '6'
        //                  ORDER  BY id_customer, coalesce(d_update,d_entry) DESC
        //             ) as a
        //             inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
        //             group by 1,2
        //         ) c on (c.id_product = a.id_product_base AND a.id_company = c.id_company)
        //         LEFT JOIN (
        //             select b.id_company, b.id_product, sum(b.n_quantity) as n_fc_next from (
        //                  select DISTINCT ON (id_customer) a.id from tm_forecast_distributor a
        //                  where a.id_company = '$this->id_company' and a.periode = '$i_periode_next' and i_status = '6'
        //                  ORDER  BY id_customer, coalesce(d_update,d_entry) DESC
        //             ) as a
        //             inner join tm_forecast_distributor_item b on (a.id = b.id_forecast)
        //             group by 1,2
        //         ) d on (d.id_product = a.id_product_base AND a.id_company = d.id_company)
        //     ORDER BY kategori, i_product_base
        // "); 

        // $query = $this->db->query("SELECT periode FROM tm_forecast_produksi WHERE periode = '$periode' AND id_company = '$idcompany' AND i_status IN ('1', '3')", FALSE);
        // if($query->num_rows()>0){
        //     return $this->db->query("SELECT
        //             b.id_product AS id_product_base,
        //             c.i_product_base,
        //             e_product_basename,
        //             c.id_class_product,
        //             (SELECT e_class_name FROM tr_class_product WHERE id = c.id_class_product) as kategori,
        //             b.v_harga,
        //             b.persen_up,
        //             b.n_quantity_fc,
        //             /* b.n_quantity_stock, */
        //             COALESCE (e.saldo_akhir,0) AS n_quantity_stock, 
        //             COALESCE (f.saldo_akhir,0) AS n_quantity_wip,
        //             COALESCE (g.saldo_akhir,0) AS n_quantity_unitjahit,
        //             COALESCE (h.saldo_akhir,0) AS n_quantity_pengadaan,
        //             b.n_quantity,
        //             b.n_quantity_sisa,
        //             b.e_remark,
        //             d.e_color_name,
        //             COALESCE (dd.qty_do,0) AS qty_do
        //         FROM
        //             tm_forecast_produksi a
        //         INNER JOIN tm_forecast_produksi_item b ON
        //             (a.id = b.id_forecast)
        //         INNER JOIN tr_product_base c ON
        //             (b.id_product = c .id)
        //         INNER JOIN tr_color d ON
        //             (c.i_color = d.i_color
        //             AND c.id_company = d.id_company)
        //         LEFT JOIN f_mutasi_saldoawal_gdjadi_new ('$idcompany',
        //             '$periode',
        //             '9999-01-01',
        //             '9999-01-31',
        //             '$dfrom',
        //             '$dto',
        //             '') e ON
        //             (e.id_product_base = c.id
        //                 AND c.id_company = e.id_company)
        //         LEFT JOIN f_mutasi_saldoawal_wip_new ('$idcompany',
        //             '$periode',
        //             '9999-01-01',
        //             '9999-01-31',
        //             '$dfrom',
        //             '$dto',
        //             '') f ON
        //             (f.id_product_base = c.id
        //                 AND c.id_company = f.id_company)
        //         LEFT JOIN f_mutasi_saldoawal_unitjahit_new ('$idcompany',
        //             '$periode',
        //             '9999-01-01',
        //             '9999-01-31',
        //             '$dfrom',
        //             '$dto',
        //             '') g ON
        //             (g.id_product_base = c.id
        //                 AND c.id_company = g.id_company)
        //         LEFT JOIN f_mutasi_saldoawal_pengadaan_new ('$idcompany',
        //             '$periode',
        //             '9999-01-01',
        //             '9999-01-31',
        //             '$dfrom',
        //             '$dto',
        //             '') h ON
        //             (h.id_product_base = c.id
        //                 AND c.id_company = h.id_company)
        //         LEFT JOIN (SELECT b.id_product, b.id_company, sum(b.n_quantity) AS qty_do FROM tm_sj a
        //             INNER JOIN tm_sj_item b ON (b.id_document = a.id) 
        //             WHERE a.i_status = '6' AND a.id_company = '$this->id_company'
        //             AND to_char(a.d_document,'YYYYMM') = '$i_periode' 
        //             GROUP BY 1,2) dd ON (dd.id_product = b.id_product AND b.id_company = dd.id_company)
        //         WHERE
        //             a.id_company = '$idcompany'
        //             AND a.periode = '$periode'
        //             /* AND a.id = '$id' */
        //         ORDER BY
        //             kategori
        //             ", FALSE);
        // }else{

        // }
    }


    public function dataheader_edit($id)
    {
        $this->db->select("id, i_bagian, i_status, substring(periode,5,2) as  bulan, substring(periode,1,4) as tahun, e_remark_supplier from tm_forecast_produksi where id = '$id' ", false);
        return $this->db->get();
    }

    public function datadetail_edit($id)
    {
        return $this->db->query("
             select a.id_company, b.id as id_product_base, b.i_product_base, b.e_product_basename, c.e_color_name, a.e_remark, a.n_quantity_fc, a.n_quantity_sisa,
             d.e_class_name as kategori, e.e_type_name AS sub_kategori, e_brand_name AS brand, e_style_name AS style, a.n_quantity_stock , a.n_quantity_wip , a.n_quantity_unitjahit, a.n_quantity_pengadaan, a.n_quantity_packing, v_harga, a.persen_up,
             a.qty_do , n_fc_berjalan , n_fc_next , n_quantity , 

              GREATEST ( 
                (COALESCE(a.n_quantity_fc,0) + GREATEST(COALESCE(qty_do,0), COALESCE(n_fc_berjalan,0)) + COALESCE(n_fc_next,0)) - 
                 GREATEST( (n_quantity_stock + n_quantity_wip + n_quantity_unitjahit + n_quantity_pengadaan + n_quantity_packing),0)
              ,0) as n_quantity_tmp
             from tm_forecast_produksi_item a
             inner join tr_product_base b on (a.id_product = b.id)
             inner join tr_color c on (b.i_color = c.i_color and  b.id_company = c.id_company)
             inner join tr_class_product d on (b.id_class_product = d.id)
             inner join tr_item_type e on (b.i_type_code = e.i_type_code and e.id_company = b.id_company)
             inner join tr_brand f on (f.i_brand = b.i_brand and f.id_company = b.id_company)
             inner join tr_style g on (g.i_style = b.i_style and g.id_company = b.id_company)
             where id_forecast = '$id'
             order by d.e_class_name, b.i_product_base,c.e_color_name asc
        ");
    }

    /*----------  CARI BARANG  ----------*/

    public function product($cari)
    {
        return $this->db->query("            
            SELECT
                a.id,
                i_product_base,
                e_product_basename,
                e_color_name,
                id_class_product,
                (SELECT e_class_name FROM tr_class_product WHERE id = a.id_class_product) as kategori
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color
                AND a.id_company = b.id_company)
            WHERE
                a.f_status = 't'
                AND (i_product_base ILIKE '%$cari%' 
                OR e_product_basename ILIKE '%$cari%')
                AND a.id_company = '" . $this->company . "'
            ORDER BY
                2 ASC
        ", FALSE);
    }

    public function getclassproduct($id)
    {
        return $this->db->query("            
        SELECT 
        id_class_product,
        (SELECT e_class_name FROM tr_class_product WHERE id = a.id_class_product) as kategori
        FROM 
        tr_product_base a
        WHERE id = " . $id, FALSE);
        return $this->db->get();
    }

    public function barang($cari, $ibagian, $ddocument)
    {

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

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_forecast_produksi');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $periode, $ibagian)
    {
        $cek = $this->db->query("SELECT substring(i_document, 1, 2) AS kode
            FROM tm_forecast_produksi
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'FP';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 4)) AS max
            FROM
                tm_forecast_produksi
            WHERE i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND i_document ILIKE '%$kode%'
                AND periode = '$periode'
            ", FALSE);
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

    public function hapusdetail($idcompany, $id)
    {
        return $this->db->query(" 
        delete from tm_forecast_produksi_item where id_forecast = '$id'
        ", FALSE);
    }

    public function get_list_barang_template_overbudget()
    {
        $id_company = $this->session->userdata('id_company');

        $sql = "SELECT tpb.id, tpb.i_product_base i_product_wip, tpb.e_product_basename e_product_wipname,
                        tpb.i_color, tc.e_color_name, tpb.id_company, tpb.i_satuan_code, ts.e_satuan_name
                FROM tr_product_base tpb
                INNER JOIN tr_satuan ts ON (
                        tpb.i_satuan_code = ts.i_satuan_code AND tpb.id_company = ts.id_company
                        )
                INNER JOIN tr_color tc ON (
                        tpb.i_color = tc.i_color AND tpb.id_company = tc.id_company
                ) 
                WHERE tpb.id_company = '$id_company' AND tpb.f_status = true 
                ORDER BY 3 asc";

        return $this->db->query($sql, false);
    }
}
/* End of file Mmaster.php */