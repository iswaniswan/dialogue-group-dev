<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function typemakloon(){
        return $this->db->query("
                                SELECT
                                    *
                                FROM
                                    tm_type_makloon
                                WHERE
                                    f_status_aktif = 't'
                                ORDER BY
                                    i_type_makloon
                                ", FALSE)->result();
    }

    public function getmakloon($itypemakloon){
        return $this->db->query("
                                SELECT
                                    e_type_makloon
                                FROM
                                    tm_type_makloon
                                WHERE
                                    f_status_aktif = 't'
                                    AND i_type_makloon = '$itypemakloon'
                                ORDER BY
                                    i_type_makloon
                                ", FALSE);
    }

    public function getsupplier($id){
        if($id == 'ALL'){
            return $this->db->query("
                                    SELECT  DISTINCT
                                        'ALL' as i_supplier,
                                        'Semua Supplier' as e_supplier_name
                                    FROM
                                        tr_supplier
                                    ",FALSE);
        }else{
            return $this->db->query("
                                SELECT
                                    i_supplier,
                                    e_supplier_name
                                FROM
                                    tr_supplier
                                WHERE
                                    i_type_makloon = '$id'
                                ", FALSE);
        }
    }

    public function getsuppliername($isupplier){
        return $this->db->query("
                                SELECT
                                    e_supplier_name
                                FROM
                                    tr_supplier
                                WHERE
                                    i_supplier = '$isupplier'
                                ", FALSE);
    }

    public function getfaktur($periode, $isupplier){
        if($isupplier == 'ALL'){
            return $this->db->query(" 
                                    SELECT 
                                        a.no_faktur,
                                        a.nama_supplier
                                    FROM
                                        f_report_faktur_jasamakloon('$periode') a
                                ", FALSE);
        }else{
            return $this->db->query(" 
                                    SELECT 
                                        a.no_faktur,
                                        a.nama_supplier
                                    FROM
                                        f_report_faktur_jasamakloon('$periode') a
                                    WHERE 
                                        a.kode_supplier = '$isupplier'
                                ", FALSE);
        }
        
    }

    public function data($bulan, $tahun, $isupplier, $ifaktur, $i_menu, $folder){
        $periode = $tahun.$bulan;

        $datatables = new Datatables(new CodeigniterAdapter);
        if($isupplier == 'ALL' && $ifaktur == 'ALL'){
            $datatables->query("
                                SELECT 
                                    a.no_faktur,
                                    a.tgl_faktur,
                                    a.nama_supplier,
                                    a.nama_makloon,
                                    a.nilai_gross,
                                    a.nilai_discount,
                                    a.nilai_netto,
                                    a.kode_supplier,
                                    a.kode_makloon,
                                    a.dpp,
                                    a.ppn,
                                    '$i_menu' as imenu,
                                    '$folder' as folder
                                FROM
                                    f_report_faktur_jasamakloon('$periode') a
                                ", FALSE);
        }else if($isupplier != 'ALL' && $ifaktur == 'ALL'){
            $datatables->query("
                                SELECT 
                                    a.no_faktur,
                                    a.tgl_faktur,
                                    a.nama_supplier,
                                    a.nama_makloon,
                                    a.nilai_gross,
                                    a.nilai_discount,
                                    a.nilai_netto,
                                    a.kode_supplier,
                                    a.kode_makloon,
                                    a.dpp,
                                    a.ppn,
                                    '$i_menu' as imenu,
                                    '$folder' as folder
                                FROM
                                    f_report_faktur_jasamakloon('$periode') a
                                WHERE 
                                    a.kode_supplier = '$isupplier'
                                ", FALSE);
        }else if($isupplier == 'ALL' && $ifaktur != 'ALL'){
            $datatables->query("
                                SELECT 
                                    a.no_faktur,
                                    a.tgl_faktur,
                                    a.nama_supplier,
                                    a.nama_makloon,
                                    a.nilai_gross,
                                    a.nilai_discount,
                                    a.nilai_netto,
                                    a.kode_supplier,
                                    a.kode_makloon,
                                    a.dpp,
                                    a.ppn,
                                    '$i_menu' as imenu,
                                    '$folder' as folder
                                FROM
                                    f_report_faktur_jasamakloon('$periode') a
                                WHERE 
                                    a.no_faktur = '$ifaktur'
                                ", FALSE);

        }else if($isupplier != 'ALL' && $ifaktur != 'ALL'){
            $datatables->query("
                                SELECT 
                                    a.no_faktur,
                                    a.tgl_faktur,
                                    a.nama_supplier,
                                    a.nama_makloon,
                                    a.nilai_gross,
                                    a.nilai_discount,
                                    a.nilai_netto,
                                    a.kode_supplier,
                                    a.kode_makloon,
                                    a.dpp,
                                    a.ppn,
                                    '$i_menu' as imenu,
                                    '$folder' as folder
                                FROM
                                    f_report_faktur_jasamakloon('$periode') a
                                WHERE 
                                    a.kode_supplier = '$isupplier'
                                    AND a.no_faktur = '$ifaktur'
                                ", FALSE);
        }
        
        
        $datatables->add('action', function ($data) {
            $no_faktur   = trim($data['no_faktur']);
            $imenu       = trim($data['imenu']);
            $folder      = trim($data['folder']);
            $isupplier   = trim($data['kode_supplier']);
            $kodemakloon = trim($data['kode_makloon']);
            $data = '';
            if(check_role($imenu, 2)){
                if($kodemakloon ==  'JNM0002'){
                    $data .= "<a href=\"#\" onclick='show(\"fakturmakloonbis2an/cform/view/$no_faktur/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }else if($kodemakloon == 'JNM0007'){
                    $data .= "<a href=\"#\" onclick='show(\"ap-fakturmakloonpacking/cform/view/$no_faktur/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }else if($kodemakloon == 'JNM0006'){
                    $data .= "<a href=\"#\" onclick='show(\"ap-fakturmakloonjahit/cform/view/$no_faktur/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }else if($kodemakloon == 'JNM0003'){
                    $data .= "<a href=\"#\" onclick='show(\"ap-fakturmakloonbordir/cform/view/$no_faktur/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }else if($kodemakloon == 'JNM0005'){
                    $data .= "<a href=\"#\" onclick='show(\"ap-fakturmakloonembosh/cform/view/$no_faktur/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }else if($kodemakloon == 'JNM0008'){
                    $data .= "<a href=\"#\" onclick='show(\"ap-fakturmakloonqwilting/cform/view/$no_faktur/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }else if($kodemakloom == 'JNM0001'){
                    $data .= "<a href=\"#\" onclick='show(\"ap-fakturmakloonprint/cform/view/$no_faktur/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
			 return $data;
        });

        $datatables->edit('tgl_faktur', function ($data) {
          if($data['tgl_faktur'] == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($data['tgl_faktur']) );
          }
        });

        $datatables->edit('nilai_gross', function ($data) {
            return number_format($data['nilai_gross']);
        });

        $datatables->edit('nilai_discount', function ($data) {
            return number_format($data['nilai_discount']);
        });

        $datatables->edit('nilai_netto', function ($data) {
            return number_format($data['nilai_netto']);
        });

        $datatables->edit('dpp', function ($data) {
            return number_format($data['dpp']);
        });

        $datatables->edit('ppn', function ($data) {
            return number_format($data['ppn']);
        });

        $datatables->hide('imenu');
        $datatables->hide('folder');
        $datatables->hide('kode_supplier');
        $datatables->hide('kode_makloon');

        return $datatables->generate();
    }

    public function bacaexport($bulan, $tahun, $itypemakloon, $isupplier, $ifaktur){
        $periode = $tahun.$bulan;
        if($isupplier == 'ALL' && $itypemakloon == 'ALL' && $ifaktur == 'ALL'){
            return $this->db->query("
                                    SELECT
                                        * 
                                    FROM
                                        f_report_faktur_jasamakloon_item('$periode') a
                                    ORDER BY
                                        a.tgl_faktur
                                    ");
        }else if($isupplier != 'ALL' && $itypemakloon != 'ALL' && $ifaktur == 'ALL'){
            return $this->db->query("
                                    SELECT
                                        * 
                                    FROM
                                        f_report_faktur_jasamakloon_item('$periode') a
                                    WHERE
                                        a.kode_supplier = '$isupplier'
                                    ORDER BY
                                        a.tgl_faktur
                                    ");
        }else if($isupplier == 'ALL' && $itypemakloon == 'ALL' && $ifaktur != 'ALL'){
            return $this->db->query("
                                    SELECT
                                        * 
                                    FROM
                                        f_report_faktur_jasamakloon_item('$periode') a
                                    WHERE
                                        a.no_faktur = '$ifaktur'
                                    ORDER BY
                                        a.tgl_faktur
                                    ");
        }else if($isupplier != 'ALL' && $itypemakloon != 'ALL' && $ifaktur != 'ALL'){
            return $this->db->query("
                                    SELECT
                                        * 
                                    FROM
                                        f_report_faktur_jasamakloon_item('$periode') a
                                    WHERE
                                        a.kode_supplier = '$isupplier'
                                        AND a.no_faktur = '$ifaktur'
                                    ORDER BY
                                        a.tgl_faktur
                                    ");
        }

    }

    public function getnofaktur($periode, $ifaktur){
        return $this->db->query("
                                SELECT 
                                    a.no_faktur
                                FROM
                                    f_report_faktur_jasamakloon('$periode') a
                                WHERE
                                    a.no_faktur = '$ifaktur'
                                ",FALSE);
    }

    public function bacaheader($bulan, $tahun, $itypemakloon, $isupplier, $ifaktur){
        $periode = $tahun.$bulan;
        
        if($isupplier == 'ALL' && $itypemakloon == 'ALL' && $ifaktur == 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                    ) AS a
                                    ");
        }else if($isupplier != 'ALL' && $itypemakloon != 'ALL' && $ifaktur == 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                        WHERE
                                            kode_supplier = '$isupplier'
                                    ) AS a
                                    ");
        }else if($isupplier == 'ALL' && $itypemakloon == 'ALL' && $ifaktur != 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                        WHERE
                                            no_faktur = '$ifaktur'
                                    ) AS a
                                    ");
        }else if($isupplier != 'ALL' && $itypemakloon != 'ALL' && $ifaktur != 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                        WHERE
                                            no_faktur = '$ifaktur'
                                            AND kode_supplier = '$isupplier'
                                    ) AS a
                                    ");
        }

    }

}

/* End of file Mmaster.php */