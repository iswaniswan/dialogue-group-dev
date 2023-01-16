<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea()
    {
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'NA';
        }else{
            return 'XX';
        }
    }

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE);
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu,$xarea){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_area,
                e_area_name,
                i_ttb AS id,
                to_char(d_ttb, 'dd-mm-yyyy') AS dttb,
                '('||b.i_customer||')  '|| e_customer_name AS customer,
                a.i_salesman,
                to_char(d_receive1, 'dd-mm-yyyy') AS d_sales,
                e_alasan_returname,
                a.i_bbm,
                to_char(a.d_bbm, 'dd-mm-yyyy') AS d_bbm,
                f.f_bbm_cancel AS status,
                n_ttb_year,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$folder' AS folder,
                '$i_menu' AS i_menu,
                '$xarea' AS xarea
            FROM
                tr_customer b,
                tr_area c,
                tm_ttbretur a
            LEFT JOIN tr_alasan_retur d ON
                (a.i_alasan_retur = d.i_alasan_retur)
            LEFT JOIN tm_bbm f ON
                (a.i_bbm = f.i_bbm
                AND a.i_area = f.i_area)
            WHERE
                a.i_area = c.i_area
                AND a.i_customer = b.i_customer
                $sql
                AND a.d_ttb >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_ttb <= to_date('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.i_ttb DESC"
        , FALSE);

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $status         = $data['status'];
            $i_area         = $data['i_area'];
            $xarea          = $data['xarea'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $tahun          = $data['n_ttb_year'];
            $ibbm           = $data['i_bbm'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$i_area/$dfrom/$dto/$xarea/$tahun\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $status == 't' && $ibbm == ''){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$i_area\",\"$tahun\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('id', function ($data) {
            if ($data['status']!='f') {
                $data = '<span class="text-danger font-medium"><b>'.$data['id'].'</b></span>';
            }else{
                $data = '<span class="font-medium"><b>'.$data['id'].'</b></span>';
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('n_ttb_year');
        $datatables->hide('status');
        $datatables->hide('xarea');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cancel($ittb, $iarea, $tahun){
        $this->db->query("
            UPDATE tm_ttbretur 
            SET f_ttb_cancel='t' 
            WHERE i_ttb='$ittb' 
            AND i_area='$iarea' 
            AND n_ttb_year=$tahun");
        $this->db->query("
            DELETE FROM tm_bbm_item 
            WHERE i_refference_document='$ittb'
            AND to_char(d_refference_document,'yyyy')='$tahun'");
        $this->db->query("
            UPDATE tm_bbm 
            SET f_bbm_cancel='t' 
            WHERE i_refference_document='$ittb' 
            AND to_char(d_refference_document,'yyyy')='$tahun'");
    }

    public function baca($id,$iarea,$tahun){
        $query = $this->db->query("
            SELECT
                a.i_area,
                a.i_ttb,
                to_char(a.d_ttb, 'dd-mm-yyyy') AS d_ttb,
                a.i_customer,
                a.i_salesman,
                a.i_bbm,
                a.i_bbm AS ibbm,
                a.d_bbm,
                a.n_ttb_discount1,
                a.n_ttb_discount2,
                a.n_ttb_discount3,
                a.v_ttb_discount1,
                a.v_ttb_discount2,
                a.v_ttb_discount3,
                a.v_ttb_gross,
                a.v_ttb_discounttotal,
                a.v_ttb_netto,
                a.v_ttb_sisa,
                a.f_ttb_cancel,
                a.f_ttb_pkp,
                a.f_ttb_plusdiscount,
                a.f_ttb_plusppn,
                to_char(a.d_receive1, 'dd-mm-yyyy') AS d_receive1,
                a.e_ttb_remark,
                a.d_entry,
                a.n_ttb_year,
                b.i_bbm,
                d.e_customer_name,
                c.i_area,
                c.e_area_name,
                e.i_salesman,
                e.e_salesman_name,
                f.e_customer_pkpnpwp,
                b.f_bbm_cancel,
                g.*,
                h.*
            FROM
                tm_ttbretur a
            LEFT JOIN tm_bbm b ON
                (a.i_ttb = b.i_refference_document
                AND a.i_salesman = b.i_salesman
                AND a.d_bbm = b.d_bbm)
            INNER JOIN tr_area c ON
                (a.i_area = c.i_area)
            INNER JOIN tr_customer d ON
                (a.i_customer = d.i_customer)
            INNER JOIN tr_salesman e ON
                (a.i_salesman = e.i_salesman)
            LEFT JOIN tr_customer_pkp f ON
                (a.i_customer = f.i_customer)
            INNER JOIN tr_price_group g ON
                (g.n_line = d.i_price_group
                OR g.i_price_group = d.i_price_group)
            LEFT JOIN tr_alasan_retur h ON
                (a.i_alasan_retur = h.i_alasan_retur)
            WHERE
                a.i_area = '$iarea'
                AND a.i_ttb = '$id'
                AND a.n_ttb_year = $tahun
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($id,$iarea,$tahun){
        $query = $this->db->query("
            SELECT
                i_area,
                i_ttb,
                d_ttb,
                i_nota,
                d_nota,
                i_product1,
                i_product1_grade,
                i_product1_motif,
                i_product2,
                i_product2_grade,
                e_product_name,
                v_unit_price,
                e_product_motifname,
                e_ttb_remark,
                i_product2_motif,
                n_quantity,
                n_quantity_receive,
                v_unit_price,
                n_ttb_year
            FROM
                tm_ttbretur_item
            INNER JOIN tr_product ON
                (tr_product.i_product = tm_ttbretur_item.i_product1)
            INNER JOIN tr_product_motif ON
                (tr_product_motif.i_product_motif = tm_ttbretur_item.i_product1_motif
                AND tr_product_motif.i_product = tm_ttbretur_item.i_product1)
            WHERE
                tm_ttbretur_item.i_ttb = '$id'
                AND tm_ttbretur_item.i_area = '$iarea'
                AND tm_ttbretur_item.n_ttb_year = $tahun
            ORDER BY
                tm_ttbretur_item.n_item_no
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacacustomer($cari,$iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer) a.i_customer, e_customer_name 
            FROM
                tr_customer a
            LEFT JOIN tr_customer_pkp b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_price_group c ON
                (a.i_price_group = c.n_line
                OR a.i_price_group = c.i_price_group)
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer)
            LEFT JOIN tr_customer_discount f ON
                (a.i_customer = f.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND (UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%')
            ORDER BY
                a.i_customer
        ", FALSE);
    }

    public function detailcustomer($icustomer,$iarea){
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer) *
            FROM
                tr_customer a
            LEFT JOIN tr_customer_pkp b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_price_group c ON
                (a.i_price_group = c.n_line
                OR a.i_price_group = c.i_price_group)
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer)
            LEFT JOIN tr_customer_discount f ON
                (a.i_customer = f.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
            ORDER BY
                a.i_customer
        ", FALSE);
    }

    public function bacasalesman($cari,$iarea,$per){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT a.i_salesman,
                a.e_salesman_name
            FROM
                tr_customer_salesman a,
                tr_salesman b
            WHERE
                (upper(a.e_salesman_name) LIKE '%$cari%'
                OR upper(a.i_salesman) LIKE '%$cari%')
                AND a.i_area = '$iarea'
                AND a.i_salesman = b.i_salesman
                AND b.f_salesman_aktif = 'true'
                AND a.e_periode = '$per'
        ", FALSE);
    }

    public function bacaalasan($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                *
            FROM
                tr_alasan_retur
            WHERE
                upper(i_alasan_retur) LIKE '%$cari%'
                OR upper(e_alasan_returname)= '%$cari%'
            ORDER BY
                i_alasan_retur
        ", FALSE);
    }

    public function product($cari,$customer,$kdharga) {
        $cari = str_replace("'", "", $cari);
        $i_area = substr($customer,0, 2);
        if ($i_area=='PB') {
            return $this->db->query("
                SELECT
                    b.i_product AS kode,
                    b.i_price_group,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    round(b.v_product_retail*0.75) AS harga,
                    c.e_product_name AS nama
                FROM
                    tr_product_motif a,
                    tr_product c,
                    tr_product_priceco b,
                    tr_customer_consigment d
                WHERE
                    a.i_product = c.i_product
                    AND a.i_product = b.i_product
                    AND d.i_customer = '$customer'
                    AND d.i_price_groupco = b.i_price_groupco
                    AND a.i_product_motif = '00'
                    AND (UPPER(a.i_product) LIKE '%$cari%' 
                    OR UPPER(c.e_product_name) LIKE '%$cari%')
                ORDER BY
                    c.i_product,
                    a.e_product_motifname,
                    b.i_price_group
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND b.i_price_group = '$kdharga'
                    AND (UPPER(a.i_product) LIKE '%$cari%' 
                    OR UPPER(c.e_product_name) LIKE '%$cari%')
            ", FALSE);
        }
    }

    public function detailproduct($iproduct,$customer,$kdharga) {
        $i_area = substr($customer,0, 2);
        if ($i_area=='PB') {
            return $this->db->query("
                SELECT
                    b.i_product AS kode,
                    b.i_price_group,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    round(b.v_product_retail*0.75) AS harga,
                    c.e_product_name AS nama
                FROM
                    tr_product_motif a,
                    tr_product c,
                    tr_product_priceco b,
                    tr_customer_consigment d
                WHERE
                    a.i_product = c.i_product
                    AND a.i_product = b.i_product
                    AND d.i_customer = '$customer'
                    AND d.i_price_groupco = b.i_price_groupco
                    AND a.i_product_motif = '00'
                    AND UPPER(a.i_product) = '$iproduct' 
                ORDER BY
                    c.i_product,
                    a.e_product_motifname,
                    b.i_price_group
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND b.i_price_group = '$kdharga'
                    AND UPPER(a.i_product) = '$iproduct' 
            ", FALSE);
        }
    }

    public function updateheader($ittb,$iarea,$tahun,$xtahun,$dttb,$dreceive1,$eremark,$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,$vttbgross,$icustomer,$ibbm,$isalesman,$ialasanretur,$ipricegroup,$inota){
        $dupdate= current_datetime();
        $this->db->set(
            array(
                'd_ttb'                 => $dttb,
                'd_receive1'            => $dreceive1,
                'e_ttb_remark'          => $eremark,
                'd_update'              => $dupdate,
                'i_salesman'            => $isalesman,
                'n_ttb_discount1'       => $nttbdiscount1,
                'n_ttb_discount2'       => $nttbdiscount2,
                'n_ttb_discount3'       => $nttbdiscount3,
                'v_ttb_discount1'       => $vttbdiscount1,
                'v_ttb_discount2'       => $vttbdiscount2,
                'v_ttb_discount3'       => $vttbdiscount3,
                'v_ttb_gross'           => $vttbgross,
                'v_ttb_discounttotal'   => $vttbdiscounttotal,
                'v_ttb_netto'           => $vttbnetto,
                'v_ttb_sisa'            => $vttbnetto,
                'f_ttb_cancel'          => 'f',
                'i_customer'            => $icustomer,
                'i_alasan_retur'        => $ialasanretur,
                'i_price_group'         => $ipricegroup,
                'n_ttb_year'            => $tahun,
                'i_nota'                => $inota
            )
        );
        $this->db->where('i_ttb',$ittb);
        $this->db->where('i_area',$iarea);
        $this->db->where('n_ttb_year',$xtahun);
        $this->db->update('tm_ttbretur');

        $query    = $this->db->query("
            SELECT 
                i_customer_groupar 
            FROM tr_customer_groupar 
            WHERE i_customer='$icustomer'");
        $row      = $query->row();
        $icustomergroupar= $row->i_customer_groupar;
        $this->db->set(
            array(
              'i_customer'          => $icustomer,
              'i_customer_groupar'  => $icustomergroupar,
              'v_gross'             => $vttbgross,
              'v_discount'          => $vttbdiscounttotal,
              'v_netto'             => $vttbnetto,
              'v_sisa'              => $vttbnetto
          )
        );
        $this->db->where('i_refference',$ibbm);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_kn');
    }

    public function updateheaderdetail($ittb,$iarea,$tahun,$dttb,$dreceive1,$ettbremark,$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,$vttbgross,$icustomer,$ibbm){
        $dupdate= current_datetime();
        $this->db->set(
            array(
                'd_ttb'                 => $dttb,
                'd_receive1'            => $dreceive1,
                'd_update'              => $dupdate,
                'n_ttb_discount1'       => $nttbdiscount1,
                'n_ttb_discount2'       => $nttbdiscount2,
                'n_ttb_discount3'       => $nttbdiscount3,
                'v_ttb_discount1'       => $vttbdiscount1,
                'v_ttb_discount2'       => $vttbdiscount2,
                'v_ttb_discount3'       => $vttbdiscount3,
                'v_ttb_gross'           => $vttbgross,
                'v_ttb_discounttotal'   => $vttbdiscounttotal,
                'v_ttb_netto'           => $vttbnetto,
                'v_ttb_sisa'            => $vttbnetto,
                'f_ttb_cancel'          => 'f',
                'i_customer'            => $icustomer,
                'n_ttb_year'            => $tahun,
            )
        );
        $this->db->where('i_ttb',$ittb);
        $this->db->where('i_area',$iarea);
        $this->db->where('n_ttb_year',$tahun);
        $this->db->update('tm_ttbretur');

        $query    = $this->db->query("
            SELECT 
                i_customer_groupar 
            FROM tr_customer_groupar 
            WHERE i_customer='$icustomer'");
        $row      = $query->row();
        $icustomergroupar= $row->i_customer_groupar;
        $this->db->set(
            array(
              'i_customer'          => $icustomer,
              'i_customer_groupar'  => $icustomergroupar,
              'v_gross'             => $vttbgross,
              'v_discount'          => $vttbdiscounttotal,
              'v_netto'             => $vttbnetto,
              'v_sisa'              => $vttbnetto
          )
        );
        $this->db->where('i_refference',$ibbm);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_kn');
    }

    public function deletedetail($iarea, $ittb, $iproduct, $iproductgrade, $iproductmotif, $nttbyear){
        $this->db->query("
            DELETE
            FROM
                tm_ttbretur_item
            WHERE
                i_ttb = '$ittb'
                AND i_product1 = '$iproduct'
                AND i_product1_motif = '$iproductmotif'
                AND i_product1_grade = '$iproductgrade'
                AND n_ttb_year = $nttbyear
                AND i_area = '$iarea'
        ");
    }

    public function updatedetail($iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$xtahun,$ndeliver,$i){
        $query=$this->db->query("
            SELECT
                *
            FROM
                tm_ttbretur_item
            WHERE
                i_ttb = '$ittb'
                AND i_area = '$iarea'
                AND i_product1 = '$iproduct'
                AND i_product1_grade = '$iproductgrade'
                AND i_product1_motif = '$iproductmotif'
        ");
        if($query->num_rows()==0){
            $this->db->set(
                array(
                    'i_area'              => $iarea,
                    'i_ttb'               => $ittb,
                    'd_ttb'               => $dttb,
                    'i_product1'          => $iproduct,
                    'i_product1_grade'    => $iproductgrade,
                    'i_product1_motif'    => $iproductmotif,
                    'n_quantity'          => $nquantity,
                    'v_unit_price'        => $vunitprice,
                    'e_ttb_remark'        => $ettbremark,
                    'n_ttb_year'          => $tahun,
                    'n_item_no'           => $i
                )
            );
            $this->db->insert('tm_ttbretur_item');
        }else{
            $this->db->set(
                array(
                    'd_ttb'               => $dttb,
                    'n_quantity'          => $nquantity,
                    'v_unit_price'        => $vunitprice,
                    'e_ttb_remark'        => $ettbremark,
                    'n_ttb_year'          => $tahun,
                    'n_item_no'           => $i
                )
            );
            $this->db->where('i_ttb',$ittb);
            $this->db->where('i_area',$iarea);
            $this->db->where('n_ttb_year',$xtahun);
            $this->db->where('i_product1',$iproduct);
            $this->db->where('i_product1_grade',$iproductgrade);
            $this->db->where('i_product1_motif',$iproductmotif);
            $this->db->update('tm_ttbretur_item');
        }
    }

    public function updatebbm($ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$vunitprice){
        $this->db->set(
            array(
                'v_unit_price'      => $vunitprice
            )
        );
        $this->db->where('trim(i_refference_document)',$ittb);
        $this->db->where('d_refference_document',$dttb);
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->update('tm_bbm_item');
    }
}

/* End of file Mmaster.php */
