<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

    public function data($folder, $i_menu, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_nota_penjualan
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company)
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
                        AND id_company = $this->company) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT DISTINCT 
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                to_char(a.d_terima_faktur, 'dd-mm-yyyy') as d_terima_faktur,
                b.e_customer_name,
                a.v_bersih,
                a.v_sisa,
                a.e_remark,
                a.i_status,
                d.e_status_name,
                d.label_color,
                l.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto 
            FROM
                tm_nota_penjualan a 
            JOIN tr_customer b ON (
                a.id_customer = b.id AND a.id_company = b.id_company
            )
            JOIN tr_status_document d ON (a.i_status = d.i_status)
            LEFT JOIN public.tr_menu_approve e on (
                a.i_approve_urutan = e.n_urut and e.i_menu = '$i_menu'
            )
            LEFT JOIN public.tr_level l on 
                (e.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company 
                $and 
                $bagian
            ORDER BY
                a.id DESC
        ",
            FALSE
        );

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->edit('v_bersih', function ($data) {
            $data = "Rp. " . number_format($data['v_bersih']);
            return $data;
        });

        $datatables->edit('v_sisa', function ($data) {
            $data = "Rp. " . number_format($data['v_sisa']);
            return $data;
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3 fa-lg'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && ($i_status == '2')) {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3 fa-lg'></i></a>";
            }

            if (check_role($i_menu, 5)  && ($i_status == '6')) {
                $data .= "<a href=\"#\" title='Cetak SPB' onclick='cetak(\"$id\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-print mr-3 text-warning fa-lg'></i></a>";
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger mr-3 fa-lg'></i></a>";
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
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function awalnext()
    {
        $idcompany  = $this->session->userdata('id_company');
        $datatables = new Datatables(new CodeigniterAdapter);

        $datatables->query(
            "WITH CTE AS (
            select 
                0 as no,
                ROW_NUMBER() OVER (ORDER BY a.id) as i,
                a.id_company,
                a.id_customer, 
                a.id,
                e.e_customer_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') as d_sj,
                ARRAY[b.i_document::text, to_char(b.d_document, 'dd-mm-yyyy')::text] as i_spb
            from 
                tm_sj a
                left join tm_spb b
                    on (a.id_document_reff = b.id
                    and a.id_company = b.id_company )
                inner join tr_customer e
                    on (a.id_customer = e.id
                    and a.id_company = e.id_company)
            where 
                a.id_company = '$idcompany'
                and a.i_status = '6'
                and a.f_nota_created =  FALSE
            )
            select 
                no,
                i,
                id,
                id_customer,
                e_customer_name,
                i_document,
                d_sj,
                i_spb[1] as i_spb,
                i_spb[2] as d_spb,
                (
                    select 
                        count(i) as jml
                    from 
                        CTE
                ) as jml
            from 
                CTE
            order by 
                e_customer_name,
                d_sj  
        ",
            FALSE
        );
        //   $datatables->query("
        //                     WITH CTE AS (
        //                       select distinct on (id)
        //                          0 AS NO,
        //                                ROW_NUMBER() OVER (ORDER BY a.id) AS i,
        //                                a.id_company, a.id_customer, a.id, b.e_customer_name, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') as d_sj,
        //                       case 
        //                           when e.id is not null then ARRAY[e.i_document::text, to_char(e.d_document, 'dd-mm-yyyy')::text]
        //                           when f.id is not null then ARRAY[f.i_document::text, to_char(f.d_document, 'dd-mm-yyyy')::text]
        //                           when g.id is not null then ARRAY[g.i_document::text, to_char(g.d_document, 'dd-mm-yyyy')::text]
        //                       end as i_spb, c.e_type_name 
        //                       from tm_sj a
        //                       inner join tr_customer b on (a.id_customer = b.id) 
        //                       inner join tr_type_spb c on (a.id_type_spb = c.id)
        //                       left join tm_spb e on (a.id_document_reff = e.id and a.id_type_spb = e.id_type_spb)
        //                       left join tm_spb_ds f on (a.id_document_reff = f.id and a.id_type_spb = f.id_type_spb)
        //                       left join tm_spb_distributor g on (a.id_document_reff = g.id and a.id_type_spb = g.id_type_spb)
        //                       where
        //                       a.id_company = '$idcompany' and a.i_status = '6' and a.f_nota_created = FALSE
        //                     )
        //                     select no, i, id, id_customer, e_customer_name, i_document, d_sj, i_spb[1] as i_spb, i_spb[2] as d_spb, e_type_name, ( SELECT count(i) AS jml FROM CTE) AS jml FROM CTE
        //                     order by e_customer_name, d_sj
        //                       ", false);


        $datatables->add('action', function ($data) {
            $id  = $data['id'];
            $jml      = $data['jml'];
            $i      = $data['i'];
            $id_customer      = $data['id_customer'];
            $data    = '';
            $data  .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk" . $i . "\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"id" . $i . "\" value=\"" . $id . "\" type=\"hidden\">
                <input name=\"jml\" value=\"" . $jml . "\" type=\"hidden\">
                <input name=\"id_customer" . $i . "\" value=\"" . $id_customer . "\" type=\"hidden\">";
            //$data .= "<a href=\"#\" title='Edit' onclick='callswal(\"$id\",\"$isupplier\",\"$iop\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i');
        $datatables->hide('jml');
        $datatables->hide('id_customer');

        return $datatables->generate();
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode
            FROM tm_nota_penjualan
            WHERE 
                i_status <> '5'
                --AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'FP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_nota_penjualan
            WHERE 
                i_status <> '5'
                --AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 2) = '$kode'
                AND substring(i_document, 4, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
            ", FALSE);
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

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian', 'inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    function get_customer($id_sj, $id_customer)
    {
        $and   = " where id IN (" . $id_sj . ") and id_customer = '$id_customer' ";


        $this->db->select(" 
        id_customer, n_ppn, to_char(tgl, 'dd-mm-yyyy') as tgl, to_char(tgl, 'yyyymm') as periode, to_char(tgl, 'yyyy') as tahun, c.e_customer_name,  c.n_customer_toplength, c.f_pkp,
        x.v_kotor, x.v_diskon, x.v_ppn, x.v_dpp, x.v_bersih  from (
           select id_customer, n_ppn, max(d_document) as tgl, sum(v_kotor) as v_kotor, sum(v_diskon) as v_diskon , sum(v_ppn) as v_ppn, sum(v_dpp) as v_dpp, sum(v_bersih) as v_bersih from tm_sj $and group by id_customer, n_ppn
        ) as x 
        inner join tr_customer c on (x.id_customer = c.id) 
    ", FALSE);
        return $this->db->get();
    }


    function get_item2($idsj)
    {
        $where   = " AND id_document IN (" . $idsj . ")";
        $this->db->select(" 
        a.id,a.id_document, b.i_document, to_char(b.d_document, 'dd-mm-yyyy') as d_document, a.id_product, c.i_product_base, 
        c.e_product_basename,a.n_quantity, a.v_price, a.n_diskon1, a.v_diskon1, a.n_diskon2, a.v_diskon2,  a.n_diskon3, 
        a.v_diskon3,a.v_diskon_tambahan, a.v_diskon_total, a.v_total, a.e_remark 
        from tm_sj_item a 
        inner join tm_sj b on (a.id_document = b.id and a.id_company = b.id_company)
        inner join tr_product_base c on (a.id_product = c.id and a.id_company = c.id_company)
        WHERE a.n_quantity > 0 $where order by b.id , c.e_product_basename 
      ", FALSE);
        return $this->db->get();
    }

    function get_head_edit($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select(" 
            a.id_company, a.id, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') as d_document, to_char(a.d_terima_faktur, 'dd-mm-yyyy') as d_terima_faktur, 
            a.n_customer_toplength, b.f_pkp, to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo, a.i_pajak,  to_char(a.d_pajak, 'dd-mm-yyyy') as d_pajak,
            a.i_bagian, a.id_customer, a.e_customer_name, a.v_kotor, a.v_diskon, a.v_ppn, a.v_dpp, a.v_bersih, a.e_remark, a.i_status, c.e_bagian_name
            from tm_nota_penjualan a
            inner join tr_customer b on (a.id_customer = b.id) 
            inner join tr_bagian c on (a.i_bagian = c.i_bagian and a.id_company = c.id_company)
            where a.id = '$id'
      ", FALSE);
        return $this->db->get();
    }

    function get_item_edit($id_nota)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select(" 
        a.id id_document_refferensi, a.id_document, b.i_document, to_char(b.d_document, 'dd-mm-yyyy') as d_document, a.id_product, c.i_product_base, c.e_product_basename,
        a.n_quantity_sisa as n_quantity_sj, d.n_quantity,
        d.v_price, d.n_diskon1, d.v_diskon1, d.n_diskon2, d.v_diskon2,  d.n_diskon3, d.v_diskon3,
        d.v_diskon_tambahan, d.v_diskon_total, d.v_total, d.e_remark 
        from tm_sj_item a 
        inner join tm_sj b on (a.id_document = b.id and a.id_company = b.id_company)
        inner join tr_product_base c on (a.id_product = c.id and a.id_company = c.id_company)
        inner join tm_nota_penjualan_item d on (d.id_document_reff = a.id)
        where d.id_document = '$id_nota' and d.id_company = '$idcompany'
        order by b.id , c.e_product_basename
      ", FALSE);
        return $this->db->get();
    }

    /*----------  CEK DOKUMEN SUDAH ADA  ----------*/
    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_nota_penjualan');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  GET ID DOKUMEN  ----------*/
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_nota_penjualan');
        return $this->db->get()->row()->id + 1;
    }

    public function insertheader($id, $idocument, $ddocument, $ibagian, $id_customer, $e_customer_name, $f_pkp, $n_customer_toplength, $dreceivefaktur, $ipajak, $dpajak, $djatuhtempo, $eremark, $vkotor, $vdiskon, $vdpp, $vppn, $vbersih)
    {

        $data = array(
            'id'                    => $id,
            'id_company'            => $this->company,
            'i_document'            => $idocument,
            'd_document'            => $ddocument,
            'i_bagian'              => $ibagian,
            'id_customer'           => $id_customer,
            'e_customer_name'       => $e_customer_name,
            'd_terima_faktur'       => $dreceivefaktur,
            'n_customer_toplength'  => $n_customer_toplength,
            'd_jatuh_tempo'         => $djatuhtempo,
            'i_pajak'               => $ipajak,
            'd_pajak'               => $dpajak,
            /* 'v_kotor'               => $vkotor,
            'v_diskon'              => $vdiskon,
            'v_dpp'                 => $vdpp,
            'v_ppn'                 => $vppn,
            'v_bersih'              => $vbersih,
            'v_sisa'                => $vbersih, */
            'e_remark'              => $eremark,
            'd_entry'               => current_datetime(),
        );
        $this->db->insert('tm_nota_penjualan', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/
    public function insertdetail($id, $id_document, $idproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vtotaldiskon, $vtotal, $eremark)
    {
        $data = array(
            'id_company'        => $this->company,
            'id_document'       => $id,
            'id_document_reff'  => $id_document,
            'id_product'        => $idproduct,
            'n_quantity'        => $nquantity,
            'v_price'           => $vprice,
            'n_diskon1'         => $ndiskon1,
            'n_diskon2'         => $ndiskon2,
            'n_diskon3'         => $ndiskon3,
            /* 'v_diskon1'         => $vdiskon1,
            'v_diskon2'         => $vdiskon2,
            'v_diskon3'         => $vdiskon3, */
            'v_diskon_tambahan' => $vdiskonplus,
            /* 'v_diskon_total'    => $vtotaldiskon, */
            /* 'v_total'           => $vtotal, */
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_nota_penjualan_item', $data);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/
    public function cek_kode_edit($kode, $ibagian, $kodeold, $ibagianold)
    {
        $this->db->select('i_document');
        $this->db->from('tm_nota_penjualan');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function updateheader($id, $idocument, $ddocument, $ibagian, $id_customer, $e_customer_name, $f_pkp, $n_customer_toplength, $dreceivefaktur, $ipajak, $dpajak, $djatuhtempo, $eremark, $vkotor, $vdiskon, $vdpp, $vppn, $vbersih)
    {

        $data = array(
            'i_document'            => $idocument,
            'd_document'            => $ddocument,
            'i_bagian'              => $ibagian,
            'id_customer'           => $id_customer,
            'e_customer_name'       => $e_customer_name,
            'd_terima_faktur'       => $dreceivefaktur,
            'n_customer_toplength'  => $n_customer_toplength,
            'd_jatuh_tempo'         => $djatuhtempo,
            'i_pajak'               => $ipajak,
            'd_pajak'               => $dpajak,
            /* 'v_kotor'               => $vkotor,
            'v_diskon'              => $vdiskon,
            'v_dpp'                 => $vdpp,
            'v_ppn'                 => $vppn,
            'v_bersih'              => $vbersih,
            'v_sisa'                => $vbersih, */
            'e_remark'              => $eremark,
            'd_update'              => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_nota_penjualan', $data);
    }

    /*----------  DELETE DETAIL PAS EDIT  ----------*/
    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_nota_penjualan_item');
    }

    /* ----------------------- GET NAMA STATUS ----------------*/
    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus)
    {

        /* if ($istatus == '6') {
            $this->db->query("update tm_sj set f_nota_created = 't' where id in (select id_document_reff from tm_nota_penjualan_item where id_document = '$id')", false);

            $this->db->query("update tm_sj_item set n_quantity_sisa = 0 where id_document in (select distinct(id_document_reff) from tm_nota_penjualan_item where id_document = '$id')", false);
        }

        if ($istatus == '6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_nota_penjualan', $data); */
        $now = date('Y-m-d');
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query(
                "SELECT b.i_menu , a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                FROM tm_nota_penjualan a
				INNER JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2
            ", FALSE)->row();
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
                $this->db->query("DELETE from tm_menu_approve where i_menu = '$this->i_menu' and i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $this->db->query(
                        "UPDATE tm_sj SET f_nota_created = 't' WHERE id IN (
                            SELECT DISTINCT id_document FROM tm_sj_item WHERE id IN (
                                SELECT id_document_reff FROM tm_nota_penjualan_item WHERE id_document = '$id'
                            )
                        )", false);
                    $this->db->query(
                        "UPDATE tm_sj_item SET n_quantity_sisa = 0 WHERE id in (
                            SELECT id_document_reff FROM tm_nota_penjualan_item WHERE id_document = '$id'
                        )", false);
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => $now,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $this->db->query("
            		INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					 ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_sj');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_nota_penjualan', $data);
    }
}
/* End of file Mmaster.php */