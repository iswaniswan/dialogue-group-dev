<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    // herdin 24 Maret 2020
    // public function bacasupplier(){
    //     return $this->db->order_by('i_supplier','ASC')->get('tr_supplier')->result();
    // }

    public function data($i_menu, $dfrom,$dto){
            // $th = date('y', strtotime($tahun));
            // $thbl = $th.$bulan ;
            $datatables = new Datatables(new CodeigniterAdapter);
            $datatables->query("SELECT ROW_NUMBER() OVER(ORDER BY a.i_faktur_code) as i, a.i_faktur_code, 
            a.i_customer, b.e_customer_name, STRING_AGG (c.i_alokasi ||' Tanggal '|| d.d_alokasi,','ORDER BY c.i_alokasi) as alokasi,
            a.v_total_faktur, a.v_sisa_alo, 
            case when a.v_sisa_alo = '0' then 'Lunas' else 'Belum Lunas' end as status_lunas, '$i_menu' as i_menu
            from tm_faktur_do_t a
            inner join tr_customer b on a.i_customer = b.i_customer
            inner join tm_alokasi_item c on  a.i_faktur_code = c.i_nota
            inner join tm_alokasi d on c.i_alokasi = d.i_alokasi
            where d.d_alokasi >= '$dfrom' and d.d_alokasi <= '$dto'
            Group by a.i_faktur_code, a.i_customer, b.e_customer_name, a.v_total_faktur, a.v_sisa_alo", FALSE);

    $datatables->add('action', function ($data) {
        $ifaktur    = trim($data['i_faktur_code']);
        $i_menu     = $data['i_menu'];
        // $i_status    = trim($data['i_status']);
        // $i_departement= trim($data['i_departement']);
        // $i_level      = trim($data['i_level']);

        $data       = '';

        if(check_role($i_menu, 2)){
            // $data .= "<a href=\"#\" onclick='show(\"ar-reportinquiryhistorypiutang/cform/view/$ifaktur/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            $data .= "<a href=\"#\" onclick='show(\"ar-reportinquiryhistorypiutang/cform/viewdetail/$ifaktur/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
        }
        return $data;

        });
        $datatables->hide('i_menu');
        // $datatables->hide('i_menu');
        return $datatables->generate();
    }

    function cek_data($ifaktur){

        $this->db->select(" b.i_nota, a.i_customer, a.e_bank_name, c.e_customer_name
                            from tm_alokasi a
                            inner join tm_alokasi_item b on a.i_alokasi = b.i_alokasi
                            inner join tr_customer c on a.i_customer = c.i_customer
                            where b.i_nota = '$ifaktur'",false);
        return $this->db->get();
    }

    function cek_datadet($ifaktur){     
        $this->db->select(" a.i_alokasi, a.i_kbank, b.d_alokasi, c.d_kas_masuk, a.v_jumlah
                            from tm_alokasi_item a
                            inner join tm_alokasi b on a.i_alokasi = b.i_alokasi
                            inner join tm_kas_masuk c on a.i_kbank = c.i_kas_masuk
                            inner join tr_bank d on a.i_coa_bank = d.i_bank
                            where a.i_nota='$ifaktur'",false);
        return $this->db->get();
    }

}

/* End of file Mmaster.php */
