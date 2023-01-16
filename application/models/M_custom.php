<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_custom extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->i_level           = $this->session->userdata('i_level');
        $this->i_departement     = $this->session->userdata('i_departement');
        $this->i_apps            = $this->session->userdata('i_apps');
        $this->id_company        = $this->session->userdata('id_company');
    }

    function cek_role($i_menu, $id)
    {
        $i_level = $this->session->userdata('i_level');
        $i_departement = $this->session->userdata('i_departement');
        $i_apps = $this->session->userdata('i_apps');

        $query = $this->db->query("select a.i_menu, a.e_menu, a.i_parent, a.n_urut, c.e_name, a.e_folder, a.doc_qe from tm_menu a
        left join tm_user_role b on(a.i_menu = b.i_menu)
        left join tm_user_power c on(b.id_user_power = c.id)        

        where c.id = '$id'
        and b.i_level = '$i_level'
        and b.i_departement = '$i_departement'
        and a.i_menu = '$i_menu'
        and b.i_apps = '$i_apps'
        and a.f_status = 't'
                
        order by a.n_urut");
        $query = $query->result_array();
        return $query;
    }

    function check_role_folder($e_folder, $id)
    {
        $i_level = $this->session->userdata('i_level');
        $i_departement = $this->session->userdata('i_departement');
        $i_apps = $this->session->userdata('i_apps');

        $query = $this->db->query("select a.i_menu, a.e_menu, a.i_parent, a.n_urut, c.e_name, a.e_folder, a.doc_qe from tm_menu a
        left join tm_user_role b on(a.i_menu = b.i_menu)
        left join tm_user_power c on(b.id_user_power = c.id)        

        where c.id = '$id'
        and b.i_level = '$i_level'
        and b.i_departement = '$i_departement'
        and a.e_folder = '$e_folder'
        and b.i_apps = '$i_apps'
        and a.f_status = 't'
                
        order by a.n_urut");
        $query = $query->result_array();
        return $query;
    }

    public function mutasi_material()
    {
        $datefrom = date('Y-m-01');
        $dateto = date('Y-m-t');
        $iperiode = date('Ym');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT 0 AS no, b.i_material, b.e_material_name, c.e_satuan_name, n_saldo_akhir
            FROM f_mutasi_material ('$this->id_company',
                    '$iperiode',
                    '9999-01-01',
                    '9999-01-31',
                    '$datefrom',
                    '$dateto',
                    '') a
            INNER JOIN tr_material b ON (b.id = a.id_material)
            INNER JOIN tr_satuan c ON (c.i_satuan_code = b.i_satuan_code AND b.id_company = c.id_company)
            ORDER BY 2
        ");
        return $datatables->generate();
    }

    public function mutasi_wip()
    {
        $datefrom = date('Y-m-01');
        $dateto = date('Y-m-t');
        $iperiode = date('Ym');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT 
                0 AS no,
                y.i_product_base,
                y.e_product_basename,
                z.e_color_name,
                sum(stok_jadi) AS stok_jadi,
                sum(stok_wip) AS stok_wip,
                sum(stok_jahit) AS stok_jahit,
                sum(stok_pengadaan) AS stok_pengadaan
            FROM (
                SELECT id_company, id_product_base, n_saldo_akhir AS stok_jadi, 0 AS stok_wip, 0 AS stok_jahit, 0 AS stok_pengadaan 
                FROM produksi.f_mutasi_gudang_jadi ('$this->id_company',
                    '$iperiode',
                    '9999-01-01',
                    '9999-01-31',
                    '$datefrom',
                    '$dateto',
                    '')
                UNION ALL 
                SELECT id_company, id_product_base,  0 AS stok_jadi, n_saldo_akhir AS stok_wip, 0 AS stok_jahit, 0 AS stok_pengadaan 
                FROM produksi.f_mutasi_wip ('$this->id_company',
                    '$iperiode',
                    '9999-01-01',
                    '9999-01-31',
                    '$datefrom',
                    '$dateto',
                    '')
                UNION ALL 
                SELECT id_company, id_product_base, 0 AS stok_jadi, 0 AS stok_wip, saldo_akhir AS stok_jahit, 0 AS stok_pengadaan 
                FROM produksi.f_mutasi_unitjahit ('$this->id_company',
                    '$iperiode',
                    '9999-01-01',
                    '9999-01-31',
                    '$datefrom',
                    '$dateto',
                    '')
                UNION ALL 
                SELECT a.id_company, c.id id_product_base, 0 AS stok_jadi, 0 AS stok_wip, 0 AS stok_jahit, n_saldo_akhir AS stok_pengadaan 
                FROM produksi.f_mutasi_saldoawal_pengadaan_newbie ('$this->id_company',
                    '$iperiode',
                    '9999-01-01',
                    '9999-01-31',
                    '$datefrom',
                    '$dateto',
                    '') a
                INNER JOIN produksi.tr_product_wip b ON (b.id = a.id_product_wip)
                INNER JOIN produksi.tr_product_base c ON (c.i_product_wip = b.i_product_wip AND b.i_color = c.i_color AND c.id_company = b.id_company)
                ) AS x
            INNER JOIN produksi.tr_product_base y ON (y.id = x.id_product_base AND x.id_company = y.id_company)
            INNER JOIN produksi.tr_color z ON (z.i_color = y.i_color AND y.id_company = z.id_company)
            GROUP BY 4,2,3
            ORDER BY 2
        ");
        return $datatables->generate();
    }

    public function get_notif($id_company, $i_level, $username, $i_departement)
    {
        return $this->db->query("
            with cte as (
                select distinct b.i_menu as i_menu  from tm_user_role a
                inner join tm_menu b on (a.i_menu = b.i_menu)
                where a.i_departement = '$i_departement' and a.i_level = '$i_level' and a.id_user_power = '7' and b.e_database is not null
            )
            select * from (
                /*forecast distributor*/
                select i_menu, e_menu, e_folder, total, '01-' || substring(dfrom, 5, 2) || '-' || substring(dfrom, 0, 5) as dfrom  , 
                '01-' || substring(dto, 5, 2) || '-' || substring(dto, 0, 5) as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(periode) as dfrom, max(periode) as dto  from tm_forecast_distributor a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20701')
                    inner join tm_menu f on (f.i_menu = '20701')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x		
                union all
                select i_menu, e_menu, e_folder, total, '01-' || substring(dfrom, 5, 2) || '-' || substring(dfrom, 0, 5) as dfrom  , 
                '01-' || substring(dto, 5, 2) || '-' || substring(dto, 0, 5) as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(periode) as dfrom, max(periode) as dto  
                    from tm_forecast_produksi a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090701')
                    inner join tm_menu f on (f.i_menu = '2090701')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_budgeting a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090702')
                    inner join tm_menu f on (f.i_menu = '2090702')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_berlaku) as dfrom, max(d_berlaku) as dto  
                    from tr_supplier_materialprice a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2010501')
                    inner join tm_menu f on (f.i_menu = '2010501')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_pp) as dfrom, max(d_pp) as dto  
                    from tm_pp a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20201')
                    inner join tm_menu f on (f.i_menu = '20201')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_op) as dfrom, max(d_op) as dto  
                    from tm_opbb a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20202')
                    inner join tm_menu f on (f.i_menu = '20202')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_nota) as dfrom, max(d_nota) as dto  
                    from tm_notabtb a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2040101')
                    inner join tm_menu f on (f.i_menu = '2040101')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_btb) as dfrom, max(d_btb) as dto  
                    from tm_btb a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20207')
                    inner join tm_menu f on (f.i_menu = '20207')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_keluar_makloon_pengadaan a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090303')
                    inner join tm_menu f on (f.i_menu = '2090303')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_qcset) as dfrom, max(d_keluar_qcset) as dto  
                    from tm_keluar_qcset a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090202')
                    inner join tm_menu f on (f.i_menu = '2090202')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_keluar_makloon_pengadaan_retur a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090311')
                    inner join tm_menu f on (f.i_menu = '2090311')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto 
                    from tm_masuk_pengadaan a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090301')
                    inner join tm_menu f on (f.i_menu = '2090301')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_pengadaan) as dfrom, max(d_keluar_pengadaan) as dto  
                    from tm_keluar_pengadaan a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090302')
                    inner join tm_menu f on (f.i_menu = '2090302')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_pengadaan_retur) as dfrom, max(d_keluar_pengadaan_retur) as dto  
                    from tm_keluar_pengadaan_retur a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090312')
                    inner join tm_menu f on (f.i_menu = '2090312')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x		
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_jahit) as dfrom, max(d_keluar_jahit) as dto  
                    from tm_keluar_jahit a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090403')
                    inner join tm_menu f on (f.i_menu = '2090403')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x		
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_unitjahit a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090401')
                    inner join tm_menu f on (f.i_menu = '2090401')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_keluar_makloonqcset a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090207')
                    inner join tm_menu f on (f.i_menu = '2090207')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto 
                    from tm_masuk_qc a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090501')
                    inner join tm_menu f on (f.i_menu = '2090501')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_qc) as dfrom, max(d_keluar_qc) as dto  
                    from tm_keluar_qc a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090503')
                    inner join tm_menu f on (f.i_menu = '2090503')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_stockopname_qcset a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090205')
                    inner join tm_menu f on (f.i_menu = '2090205')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_retur_produksi_gdjd a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2050118')
                    inner join tm_menu f on (f.i_menu = '2050118')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_stockopname_pengadaan a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090308')
                    inner join tm_menu f on (f.i_menu = '2090308')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_retur_wip a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090508')
                    inner join tm_menu f on (f.i_menu = '2090508')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_stockopname_unitjahit a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090409')
                    inner join tm_menu f on (f.i_menu = '2090409')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x
                union all
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_stockopname_qc a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090507')
                    inner join tm_menu f on (f.i_menu = '2090507')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all
                /** Gudang Jadi **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_gudang_jadi a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2050102')
                    inner join tm_menu f on (f.i_menu = '2050102')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End Gudang Jadi **/
                union all
                /** STB Cutting **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_keluar_cutting a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090105')
                    inner join tm_menu f on (f.i_menu = '2090105')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End STB Cutting **/
                union all
                /** STB Cutting **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_qcset a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090201')
                    inner join tm_menu f on (f.i_menu = '2090201')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End STB Cutting **/
                UNION ALL
                /** SJ WIP **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_keluar_makloonqc a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090504')
                    inner join tm_menu f on (f.i_menu = '2090504')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End SJ WIP **/
                UNION ALL
                /** Terima Retur Jahit **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_retur_masuk_pengadaan a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090305')
                    inner join tm_menu f on (f.i_menu = '2090305')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End Terima Retur Jahit **/
                UNION ALL
                /** Penerimaan Pengadaan **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_pengadaan_fgudang a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090310')
                    inner join tm_menu f on (f.i_menu = '2090310')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End Penerimaan Pengadaan **/
                UNION ALL
                /** Penerimaan Pengadaan Packing **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_packing_fgudang a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090609')
                    inner join tm_menu f on (f.i_menu = '2090609')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End Penerimaan Pengadaan Packing **/
                UNION ALL
                /** SPB Distributor **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_spb_distributor a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20718')
                    inner join tm_menu f on (f.i_menu = '20718')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End SPB Distributor **/
                UNION ALL
                /** SJ Penjualan **/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_sj a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2050113')
                    inner join tm_menu f on (f.i_menu = '2050113')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                /** End SJ Penjualan **/
                union all /*forecast cutting*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_fccutting a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090100')
                    inner join tm_menu f on (f.i_menu = '2090100')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all /*schedule cutting*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_schedule a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090101')
                    inner join tm_menu f on (f.i_menu = '2090101')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all /*STB cutting*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_stb_cutting a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2050204')
                    inner join tm_menu f on (f.i_menu = '2050204')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all /*Penerimaan cutting*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_cutting a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090103')
                    inner join tm_menu f on (f.i_menu = '2090103')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all /*FC Jahit*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_fcjahit a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090412')
                    inner join tm_menu f on (f.i_menu = '2090412')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all /*Uraian Jahit*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_uraianjahit a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090413')
                    inner join tm_menu f on (f.i_menu = '2090413')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
                union all /*Retur Jahit Ke Pengadaan*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_retur_jahit_topengadaan a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090405')
                    inner join tm_menu f on (f.i_menu = '2090405')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x		
                union all /*Retur WIP Ke Jahit*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_retur_keluar_wip a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090505')
                    inner join tm_menu f on (f.i_menu = '2090505')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x		
                union all /*Penerimaan Retur WIP*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_masuk_retur_jahit a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090407')
                    inner join tm_menu f on (f.i_menu = '2090407')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x		
                union all /*STB Retur Jahit*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
                    from tm_stbjahit_retur a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090406')
                    inner join tm_menu f on (f.i_menu = '2090406')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x		
                union all /*Harga Jual Barang jadi*/
                select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
                to_char(dto, 'dd-mm-yyyy') as dto from (
                    select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_berlaku) as dfrom, max(d_berlaku) as dto  
                    from tr_harga_jualbrgjd a
                    inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2010503')
                    inner join tm_menu f on (f.i_menu = '2010503')
                    where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
                    group by 1,2,3
                ) as x	
            ) as x
        ", FALSE);
    }

    public function get_menu()
    {
        return $this->db->query(" SELECT
                a.i_menu,
                a.e_menu,
                a.e_folder,
                a.i_parent,
                a.n_urut,
                icon
            FROM
                tm_menu a
                LEFT JOIN tm_user_role b ON
                (a.i_menu = b.i_menu)
                LEFT JOIN tm_user_power c ON
                (b.id_user_power = c.id)
            WHERE
                c.id = '2'
                AND b.i_level = '$this->i_level'
                AND b.i_departement = '$this->i_departement'
                AND a.i_parent = '0'
                AND b.i_apps = '$this->i_apps'
                AND a.f_status = 't'
            ORDER BY
        a.n_urut
        ");
    }

    public function get_submenu($i_menu)
    {
        return $this->db->query(" SELECT
                a.i_menu,
                a.e_menu,
                a.e_folder,
                a.i_parent,
                a.n_urut,
                icon
            FROM
                tm_menu a
                LEFT JOIN tm_user_role b ON
                (a.i_menu = b.i_menu)
                LEFT JOIN tm_user_power c ON
                (b.id_user_power = c.id)
            WHERE
                c.id = '2'
                AND b.i_level = '$this->i_level'
                AND b.i_departement = '$this->i_departement'
                AND a.i_parent = '$i_menu'
                AND b.i_apps = '$this->i_apps'
                AND a.f_status = 't'
            ORDER BY
        a.n_urut
        ");
    }
}

/* End of file M_custom.php */
