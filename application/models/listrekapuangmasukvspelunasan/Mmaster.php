<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($tahun,$bulan){
        $iperiode   = $tahun.$bulan;
        $datatables = new Datatables(new CodeigniterAdapter);

        $coa = '110-41000';
        $datatables->query("
                            SELECT 
                                x.i_area, 
                                z.e_area_name, 
                                SUM(x.v_bank) AS v_bank, 
                                SUM(x.alokasi) AS alokasi, 
                                (SUM(x.v_bank) - SUM(x.alokasi)) AS selisih 
                            FROM(
                                SELECT 
                                    i_area, 
                                    SUM(v_bank) AS v_bank, 
                                    0 AS alokasi 
                                FROM 
                                    tm_kbank 
                                WHERE 
                                    to_char(d_bank,'yyyymm')='$iperiode'
                                    and f_kbank_cancel = 'f'
                                    and i_kbank like 'BM%'
                                    and i_coa = '$coa'
                                GROUP BY 
                                    i_area
                                UNION ALL
                                SELECT 
                                    a.i_area, 
                                    0 AS v_bank, 
                                    SUM(a.v_jumlah) AS alokasi 
                                FROM 
                                    tm_alokasi_item a, 
                                    tm_alokasi b
                                WHERE 
                                    a.i_alokasi = b.i_alokasi
                                    AND a.i_kbank = b.i_kbank
                                    AND a.i_area = b.i_area
                                    AND b.f_alokasi_cancel = 'f'
                                    AND to_char(b.d_alokasi,'yyyymm')='$iperiode'
                            GROUP BY 
                                a.i_area
                                ) AS x
                            INNER JOIN 
                                tr_area z ON (x.i_area = z.i_area)
                            GROUP BY 
                                x.i_area, 
                                z.e_area_name"
                            ,FALSE);

        $datatables->edit('v_bank', function ($data) {
            return number_format($data['v_bank']);
        });
        $datatables->edit('alokasi', function ($data) {
            return number_format($data['alokasi']);
        });
        $datatables->edit('selisih', function ($data) {
            return number_format($data['selisih']);
        });
        
        return $datatables->generate();
    }

    public function total($tahun,$bulan){  
        $iperiode   = $tahun.$bulan;
        $coa = '110-41000';
        return $this->db->query("
                        SELECT 
                            SUM(jumvbank) AS bank,
                            SUM(jumalokasi) AS alokasi,
                            SUM(jumselisih) AS selisih
                        FROM(
                            SELECT 
                                SUM(x.v_bank) AS jumvbank, 
                                SUM(x.alokasi) AS jumalokasi, 
                                (SUM(x.v_bank) - SUM(x.alokasi)) AS jumselisih 
                            FROM(
                                SELECT 
                                    i_area, 
                                    SUM(v_bank) AS v_bank, 
                                    0 AS alokasi 
                                FROM 
                                    tm_kbank 
                                WHERE 
                                    to_char(d_bank,'yyyymm')='$iperiode'
                                    and f_kbank_cancel = 'f'
                                    and i_kbank like 'BM%'
                                    and i_coa = '$coa'
                                GROUP BY 
                                    i_area
                                UNION ALL
                                SELECT 
                                    a.i_area, 
                                    0 AS v_bank, 
                                    SUM(a.v_jumlah) AS alokasi 
                                FROM 
                                    tm_alokasi_item a, 
                                    tm_alokasi b
                                WHERE 
                                    a.i_alokasi = b.i_alokasi
                                    AND a.i_kbank = b.i_kbank
                                    AND a.i_area = b.i_area
                                    AND b.f_alokasi_cancel = 'f'
                                    AND to_char(b.d_alokasi,'yyyymm')='$iperiode'
                            GROUP BY 
                                a.i_area
                                ) AS x
                            INNER JOIN 
                                tr_area z ON (x.i_area = z.i_area)
                        GROUP BY 
                            x.i_area, 
                            z.e_area_name) AS b"
                                , FALSE);
    }
}

/* End of file Mmaster.php */