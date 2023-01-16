<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($bulan, $tahun, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $iperiode = $tahun.$bulan; 
        $datatables->query("
                            SELECT
                                a.i_area, 
                                c.e_area_name,
                                a.i_salesman, 
                                b.e_salesman_name, 
                                a.n_jumlah_kunjungan, 
                                a.n_jumlah_order,
                                ((a.n_jumlah_order/a.n_jumlah_kunjungan)*100) AS hasil,
                                trunc(a.n_jumlah_kunjungan/a.n_jumlah_hari) AS rata,
                                (a.n_jumlah_order/a.n_jumlah_hari) AS efektif,
                                '$iperiode' as iperiode,
                                '$i_menu' as i_menu
                            FROM 
                                tm_kunjungan a, 
                                tr_salesman b, 
                                tr_area c
                            WHERE 
                                a.i_salesman=b.i_salesman
                                and a.i_area=c.i_area 
                                and a.i_periode='$iperiode'
                            ORDER BY 
                                a.i_area, 
                                a.i_salesman "
                        ,false);
		$datatables->add('action', function ($data) {
            $i_salesman = trim($data['i_salesman']);
            $i_menu     = $data['i_menu'];
            $iperiode   = $data['iperiode'];
            $data       = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"rekaprrkh/cform/edit/$iperiode/$i_salesman/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('i_area', function ($data) {
        $iarea = $data['i_area'];
        $earea = $data['e_area_name'];
        return $iarea." - ".$earea;

        });

        $datatables->edit('hasil', function ($data) {
            $hasil = $data['hasil'];
            return substr($hasil,0,5);
        });

        $datatables->edit('efektif', function ($data) {
            $efektif = $data['efektif'];
            return substr($efektif,0,5);
        });

        $datatables->hide('i_menu');
        $datatables->hide('e_area_name');
        $datatables->hide('iperiode');

        return $datatables->generate();
    }
    
    function detail($iperiode, $isalesman){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT
                                a.i_area, 
                                a.i_salesman,
                                d.e_salesman_name, 
                                a.d_rrkh as hari,
                                a.d_rrkh, 
                                a.i_customer, 
                                e.e_customer_classname,
                                b.e_customer_name, 
                                c.e_kunjungan_typename, 
                                a.f_kunjungan_realisasi,
                                a.e_remark
                            FROM 
                                tm_rrkh_item a, 
                                tr_customer b, 
                                tr_kunjungan_type c, 
                                tr_salesman d, 
                                tr_customer_class e
                            WHERE 
                                a.i_customer=b.i_customer 
                                AND a.i_area=b.i_area 
                                AND a.i_kunjungan_type=c.i_kunjungan_type
                                AND a.i_salesman=d.i_salesman 
                                AND b.i_customer_class=e.i_customer_class
                                AND to_char(a.d_rrkh,'yyyymm')='$iperiode' 
                                AND a.i_salesman='$isalesman'"
                        ,false);

        $datatables->edit('d_rrkh', function ($data) {
            $d_rrkh = $data['d_rrkh'];
            return date("d-m-Y", strtotime($d_rrkh));

        });

        $datatables->edit('hari', function ($data) {
            $hari = $data['hari'];
            $ts = strtotime($hari);
            $day=date('w', $ts);
            switch($day){       
                case 0 : {
                    return 'Minggu';
                }break;
                case 1 : {
                    return 'Senin';
                }break;
                case 2 : {
                    return 'Selasa';
                }break;
                case 3 : {
                    return 'Rabu';
                }break;
                case 4 : {
                    return 'Kamis';
                }break;
                case 5 : {
                    return "Jum'at";
                }break;
                case 6 : {
                    return 'Sabtu';
                }break;
                default: {
                    return 'Tidak Terdeteksi';
                }break;
            }

        });

        $datatables->edit('i_salesman', function ($data) {
            $isalesman = $data['i_salesman'];
            $esalesman = $data['e_salesman_name'];
            return "(".$isalesman.") ".$esalesman;
        });

        $datatables->edit('i_customer', function ($data) {
            $icustomer = $data['i_customer'];
            $ecustomer = $data['e_customer_name'];
            return "(".$icustomer.") ".$ecustomer;
        });
        $datatables->edit('f_kunjungan_realisasi', function ($data) {
            $real = $data['f_kunjungan_realisasi'];
            if($real == 't'){
                return "YA";
            }else{
                return "TIDAK";
            }
        });

        $datatables->hide('e_customer_name');
        $datatables->hide('e_salesman_name');

        return $datatables->generate();
	}
}

/* End of file Mmaster.php */
