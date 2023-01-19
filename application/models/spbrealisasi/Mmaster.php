<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('i_area','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $area = true;
        }else{
            $area = false;
        }
        return $area;
    }

    public function data($folder, $siareana, $username, $id_company){
        if ($siareana==true) {
            $sql = "SELECT
                        i_spb,
                        tm_spb.i_area,
                        TO_CHAR(d_spb, 'dd-mm-yyyy') AS dspb,
                        e_customer_name,
                        UPPER(e_product_groupname) AS e_product_groupname,
                        e_area_name,
                        f_spb_stockdaerah,
                        '$folder' AS folder
                    FROM
                        tm_spb
                    INNER JOIN tr_customer ON
                        (tm_spb.i_customer = tr_customer.i_customer)
                    INNER JOIN tr_salesman ON
                        (tm_spb.i_salesman = tr_salesman.i_salesman)
                    INNER JOIN tr_customer_area ON
                        (tm_spb.i_customer = tr_customer_area.i_customer)
                    INNER JOIN tr_price_group ON
                        (tm_spb.i_price_group = tr_price_group.i_price_group)
                    INNER JOIN tr_product_group ON
                        (tr_product_group.i_product_group = tm_spb.i_product_group)
                    WHERE
                        ((NOT tm_spb.i_approve1 ISNULL
                        AND NOT tm_spb.i_approve2 ISNULL
                        AND tm_spb.i_store ISNULL
                        AND tm_spb.i_store_location ISNULL
                        AND f_spb_stockdaerah = 'f'
                        AND tm_spb.f_spb_cancel = 'f')
                        OR (NOT tm_spb.i_approve1 ISNULL
                        AND NOT tm_spb.i_approve2 ISNULL
                        AND tm_spb.i_store ISNULL
                        AND tm_spb.i_store_location ISNULL
                        AND f_spb_stockdaerah = 't'
                        AND tm_spb.f_spb_cancel = 'f'
                        AND (tm_spb.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$id_company')) ) )
                        AND SUBSTRING(tm_spb.i_customer, 3, 3)!= '000'
                    ORDER BY
                        tm_spb.i_spb";
        }else{
            $sql = "SELECT
                        i_spb,
                        tm_spb.i_area,
                        TO_CHAR(d_spb, 'dd-mm-yyyy') AS dspb,
                        e_customer_name,
                        UPPER(e_product_groupname) AS e_product_groupname,
                        e_area_name,
                        f_spb_stockdaerah,
                        '$folder' AS folder
                    FROM
                        tm_spb
                    INNER JOIN tr_customer ON
                        (tm_spb.i_customer = tr_customer.i_customer)
                    INNER JOIN tr_salesman ON
                        (tm_spb.i_salesman = tr_salesman.i_salesman)
                    INNER JOIN tr_customer_area ON
                        (tm_spb.i_customer = tr_customer_area.i_customer)
                    INNER JOIN tr_price_group ON
                        (tm_spb.i_price_group = tr_price_group.i_price_group)
                    INNER JOIN tr_product_group ON
                        (tr_product_group.i_product_group = tm_spb.i_product_group)
                    WHERE
                        NOT tm_spb.i_approve1 ISNULL
                        AND NOT tm_spb.i_approve2 ISNULL
                        AND tm_spb.i_store ISNULL
                        AND tm_spb.i_store_location ISNULL
                        AND f_spb_stockdaerah = 't'
                        AND tm_spb.f_spb_cancel = 'f'
                        AND tm_spb.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$id_company')
                        AND SUBSTRING(tm_spb.i_customer, 3, 3)!= '000'
                    ORDER BY
                        tm_spb.i_spb";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("$sql", false);
        $datatables->edit('f_spb_stockdaerah', function($data){
            if($data['f_spb_stockdaerah'] == "t"){
                return "Ya";
            }else{
                return "Tidak";
            }
        });
        $datatables->add('action', function ($data) {
            $ispb   = trim($data['i_spb']);
            $iarea  = trim($data['i_area']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ispb/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($ispb, $iarea){
        $this->db->select(" *, to_char(d_spb, 'dd-mm-yyyy') AS dspb 
            FROM
                tm_spb
            LEFT JOIN tm_promo ON
                (tm_spb.i_spb_program = tm_promo.i_promo)
            INNER JOIN tr_customer ON
                (tm_spb.i_customer = tr_customer.i_customer)
            INNER JOIN tr_salesman ON
                (tm_spb.i_salesman = tr_salesman.i_salesman)
            INNER JOIN tr_customer_area ON
                (tm_spb.i_customer = tr_customer_area.i_customer)
            INNER JOIN tr_price_group ON
                (tm_spb.i_price_group = tr_price_group.i_price_group)
            WHERE
                i_spb = '$ispb'
                AND tm_spb.i_area = '$iarea'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($ispb, $iarea){
        $this->db->select("
                a.*,
                b.e_product_motifname
            FROM
                tm_spb_item a,
                tr_product_motif b
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function getstore($fspbstokdaerah, $cari){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $car        = str_replace("'", "", $cari);
        if($fspbstokdaerah=='f'){
            return $this->db->query("
                SELECT
                    a.i_store,
                    e_store_name 
                FROM
                    tr_store a,
                    tr_store_location b
                WHERE
                    a.i_store IN(
                    SELECT
                        i_store
                    FROM
                        tr_area
                    WHERE
                        i_area = '00')
                    AND a.i_store = b.i_store
                    AND (UPPER(a.i_store) LIKE '%$cari%' 
                    OR UPPER(a.e_store_name) LIKE '%$cari%')
                ORDER BY
                    a.i_store",false);
        }else{
            return $this->db->query("
                SELECT
                    a.i_store,
                    e_store_name 
                FROM
                    tr_store a,
                    tr_store_location b
                WHERE
                    a.i_store IN(
                    SELECT
                        i_store
                    FROM
                        tr_area
                    WHERE
                        (i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$id_company')))
                    AND a.i_store = b.i_store
                    AND (UPPER(a.i_store) LIKE '%$cari%' 
                    OR UPPER(a.e_store_name) LIKE '%$cari%')
                ORDER BY
                    a.i_store",false);
        }
    }

    public function getdetstore($istore,$fspbstokdaerah){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        if($fspbstokdaerah=='f'){
            return $this->db->query("
                SELECT
                    *
                FROM
                    tr_store a,
                    tr_store_location b
                WHERE
                    a.i_store IN(
                    SELECT
                        i_store
                    FROM
                        tr_area
                    WHERE
                        i_area = '00')
                    AND a.i_store = b.i_store
                    AND a.i_store = '$istore'
                ORDER BY
                    a.i_store",false);
        }else{
            return $this->db->query("
                SELECT
                    *
                FROM
                    tr_store a,
                    tr_store_location b
                WHERE
                    a.i_store IN(
                    SELECT
                        i_store
                    FROM
                        tr_area
                    WHERE
                        (i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$id_company')))
                    AND a.i_store = b.i_store
                    AND a.i_store = '$istore'
                ORDER BY
                    a.i_store",false);
        }
    }

    public function itemspb($ispb, $iarea){
        $this->db->select('i_product, i_product_motif, i_product_grade');
        $this->db->from('tm_spb_item');
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->order_by('n_item_no');
        return $this->db->get();
    }

    public function stock1($thbl, $iproduct, $iproductgrade){
        return $this->db->query("
            SELECT n_saldo_akhir AS qty 
            FROM f_mutasi_stock_pusat_saldoakhir('$thbl') 
            WHERE i_product = '$iproduct' 
            AND i_product_grade = '$iproductgrade'",false);
    }

    public function stock2($thbl, $iproduct, $iproductgrade, $istore){
        return $this->db->query("
            SELECT n_saldo_akhir AS qty
            FROM f_mutasi_stock_daerah_all_saldoakhir('$thbl') 
            WHERE i_product = '$iproduct' 
            AND i_product_grade = '$iproductgrade'
            AND i_store = '$istore' ",false);
    }

    public function updateheader($dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, 
      $fspbplusppn, $fspbplusdiscount, $fspbvalid, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment, $ispb,$iarea,$istore,$istorelocation,$fspbstockdaerah, $fspbsiapnotagudang, $fspbcancel,$fspbsiapnotasales, $vspbdiscounttotalafter,$vspbafter){
        $dspbupdate = current_datetime(); 
        if($fspbstockdaerah=='f'){
            $data = array(
                'd_spb'                   => $dspb,
                'i_customer'              => $icustomer,
                'i_spb_po'                => $ispbpo,
                'n_spb_toplength'         => $nspbtoplength,
                'i_salesman'              => $isalesman,
                'i_price_group'           => $ipricegroup,
                'd_spb_receive'           => $dspb,
                'f_spb_op'                => 't',
                'e_customer_pkpnpwp'      => $ecustomerpkpnpwp,
                'f_spb_pkp'               => $fspbpkp,
                'f_spb_plusppn'           => $fspbplusppn,
                'f_spb_plusdiscount'      => $fspbplusdiscount,
                'f_spb_stockdaerah'       => $fspbstockdaerah,
                'f_spb_valid'             => $fspbvalid,
                'f_spb_cancel'            => $fspbcancel,
                'n_spb_discount1'         => $nspbdiscount1,
                'n_spb_discount2'         => $nspbdiscount2,
                'n_spb_discount3'         => $nspbdiscount3,
                'v_spb_discount1'         => $vspbdiscount1,
                'v_spb_discount2'         => $vspbdiscount2,
                'v_spb_discount3'         => $vspbdiscount3,
                'v_spb_discounttotalafter'=> $vspbdiscounttotalafter,
                'v_spb_after'             => $vspbafter,
                'f_spb_consigment'        => $fspbconsigment,
                'd_spb_update'            => $dspbupdate,
                'i_store'                 => $istore,
                'i_store_location'        => $istorelocation,
                'f_spb_siapnotagudang'    => $fspbsiapnotagudang,
                'f_spb_siapnotasales'     => $fspbsiapnotasales
            );      
        }else{
            $data = array(
                'd_spb'                   => $dspb,
                'i_customer'              => $icustomer,
                'i_spb_po'                => $ispbpo,
                'n_spb_toplength'         => $nspbtoplength,
                'i_salesman'              => $isalesman,
                'i_price_group'           => $ipricegroup,
                'd_spb_receive'           => $dspb,
                'f_spb_op'                => $fspbop,
                'e_customer_pkpnpwp'      => $ecustomerpkpnpwp,
                'f_spb_pkp'               => $fspbpkp,
                'f_spb_plusppn'           => $fspbplusppn,
                'f_spb_plusdiscount'      => $fspbplusdiscount,
                'f_spb_stockdaerah'       => $fspbstockdaerah,
                'f_spb_valid'             => 't',
                'f_spb_cancel'            => $fspbcancel,
                'n_spb_discount1'         => $nspbdiscount1,
                'n_spb_discount2'         => $nspbdiscount2,
                'n_spb_discount3'         => $nspbdiscount3,
                'v_spb_discount1'         => $vspbdiscount1,
                'v_spb_discount2'         => $vspbdiscount2,
                'v_spb_discount3'         => $vspbdiscount3,
                'v_spb_discounttotalafter'=> $vspbdiscounttotalafter,
                'v_spb_after'             => $vspbafter,
                'f_spb_consigment'        => $fspbconsigment,
                'd_spb_update'            => $dspbupdate,
                'i_store'                 => $istore,
                'i_store_location'        => $istorelocation,
                'f_spb_siapnotagudang'    => $fspbsiapnotagudang,
                'f_spb_siapnotasales'     => $fspbsiapnotasales
            );      
        }
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }

    public function updateheadernsj($ispb,$iarea,$istore,$istorelocation,$fspbstockdaerah, $fspbsiapnotagudang, $fspbcancel, $fspbvalid,$fspbsiapnotasales,$isj,$dsj){
        $data = array(
            'i_store'             => $istore,
            'i_store_location'    => $istorelocation,
            'f_spb_stockdaerah'   => $fspbstockdaerah,
            'f_spb_valid'         => $fspbvalid,
            'f_spb_siapnotagudang'=> $fspbsiapnotagudang,
            'f_spb_siapnotasales' => $fspbsiapnotasales,
            'f_spb_cancel'        => $fspbcancel
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }

    public function langsungnota($ispb,$iarea){
        $data = array(
            'f_spb_siapnotagudang'    => 't',
            'f_spb_siapnotasales'     => 'f',
            'f_spb_op'                => 'f',
            'f_spb_pemenuhan'         => 't'
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }

    public function lansgungop($ispb,$iarea){
        $data = array(
            'f_spb_pemenuhan'         => 'f'
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 

    }

    public function deletedetail($iproduct, $iarea, $iproductgrade, $ispb, $iproductmotif){
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', $iproductmotif);
        $this->db->delete('tm_spb_item');
    }

    public function insertdetail($ispb,$iarea,$iproduct,$iproductgrade,$eproductname,$norder,$vunitprice,$ndeliver,$iproductmotif,$nstock,$eremark,$i,$iproductstatus){
        $this->db->set(
            array(
                'i_spb'             => $ispb,
                'i_area'            => $iarea,
                'i_product'         => $iproduct,
                'i_product_grade'   => $iproductgrade,
                'i_product_motif'   => $iproductmotif,
                'n_order'           => $norder,
                'n_deliver'         => $ndeliver,
                'n_stock'           => $nstock,
                'v_unit_price'      => $vunitprice,
                'e_product_name'    => $eproductname,
                'e_remark'          => $eremark,
                'n_item_no'         => $i,
                'i_product_status'  => $iproductstatus
            )
        );        
        $this->db->insert('tm_spb_item');
    }
}

/* End of file Mmaster.php */
