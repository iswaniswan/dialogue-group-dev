<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT (b.i_store),
                b.e_store_name,
                c.i_store_location,
                c.e_store_locationname
            FROM
                tr_area a,
                tr_store b,
                tr_store_location c
            WHERE
                a.i_store = b.i_store
                AND b.i_store = c.i_store
                AND b.i_store = 'AA'
                AND i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
            ORDER BY
                b.i_store,
                c.i_store_location
        ", FALSE);
    }

    public function data($tgl,$iperiode){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_product,
                i_product_grade,
                e_product_groupname,
                b.e_product_name,
                saldoawal,
                sj,
                convplus,
                convminus,
                sjp,
                bbk,
                sjr,
                bbm,
                sido,
                bbmap,
                sjbr,
                bbkretur,
                (saldoawal+convplus+sjr+bbm+sido+bbmap+sjbr)-(sj+convminus+sjp+bbk+bbkretur) AS saldoakhir
            FROM
                f_mutasi_stock_pusat_saldoakhir_daily('$iperiode', '$tgl') a,
                tr_product b,
                tr_product_type c,
                tr_product_group d
            WHERE
                a.i_product = b.i_product
                AND b.i_product_type = c.i_product_type
                AND c.i_product_group = d.i_product_group
            ORDER BY
                a.i_product_grade,
                d.e_product_groupname,
                b.e_product_name"
        , FALSE);
        /*$datatables->edit('n_deliver', function ($data) {
            return number_format($data['n_deliver']);
        });
        $datatables->edit('v_unit_price', function ($data) {
            return number_format($data['v_unit_price']);
        });
        $datatables->edit('v_product_mill', function ($data) {
            return number_format($data['v_product_mill']);
        });
        $datatables->edit('total_harga_beli', function ($data) {
            return number_format($data['total_harga_beli']);
        });
        $datatables->edit('total_harga_jual', function ($data) {
            return number_format($data['total_harga_jual']);
        });*/
        return $datatables->generate();
    }

    public function baca($tgl,$iperiode){      
        return $this->db->query("
            SELECT
                a.i_product,
                i_product_grade,
                e_product_groupname,
                b.e_product_name,
                saldoawal,
                sj,
                convplus,
                convminus,
                sjp,
                bbk,
                sjr,
                bbm,
                sido,
                bbmap,
                sjbr,
                bbkretur,
                (saldoawal+convplus+sjr+bbm+sido+bbmap+sjbr)-(sj+convminus+sjp+bbk+bbkretur) AS saldoakhir
            FROM
                f_mutasi_stock_pusat_saldoakhir_daily('$iperiode', '$tgl') a,
                tr_product b,
                tr_product_type c,
                tr_product_group d
            WHERE
                a.i_product = b.i_product
                AND b.i_product_type = c.i_product_type
                AND c.i_product_group = d.i_product_group
            ORDER BY
                a.i_product_grade,
                d.e_product_groupname,
                b.e_product_name"
        , FALSE);
    }
}

/* End of file Mmaster.php */
