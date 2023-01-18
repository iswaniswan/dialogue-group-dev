<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($dfrom, $dto, $status, $folder, $i_menu)
    {
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);

        if($status == ''){
            $status = "";
        }else if($status == "Belum"){
            $status = "AND i_kasbank_keluar IS NULL";
        }else{
            $status = "AND NOT i_kasbank_keluar IS NULL";
        }
        // $datatables->query("    SELECT
        //                             '' AS i,
        //                             c.isupplier,
        //                             c.e_supplier_name,
        //                             c.fjenis,
        //                             c.i_nota,
        //                             to_char(c.d_nota, 'dd-mm-yyyy') AS d_nota,
        //                             c.vnota,
        //                             array_agg(d.i_kasbank_keluar||' - '||d.v_total) AS i_reff,
        //                             c.v_sisa,
        //                             '$folder' AS folder,
        //                             '$i_menu' AS i_menu,
        //                             '$dfrom' AS dfrom,
        //                             '$dto' AS dto,
        //                             '$username' AS username
        //                         FROM
        //                             (
        //                             SELECT
        //                                 a.isupplier, b.e_supplier_name, a.fjenis, a.i_nota,
        //                                 --to_char(a.d_nota, 'dd-mm-yyyy') AS d_nota,
        //                         a.d_nota, a.vnota, a.v_sisa
        //                             FROM
        //                                 (
        //                                 SELECT
        //                                     i_supplier AS isupplier, 'Faktur Pembelian' AS fjenis, i_nota, d_nota, v_total AS vnota, v_sisa
        //                                 FROM
        //                                     tm_notabtb
        //                             UNION ALL
        //                                 SELECT
        //                                     partner AS isupplier, 'Faktur Makloon Jahit' AS fjenis, i_nota, d_nota, v_netto AS vnota, v_sisa
        //                                 FROM
        //                                     tm_notamakloonjahit
        //                             UNION ALL
        //                                 SELECT
        //                                     i_supplier AS isupplier, 'Faktur Makloon Bis2an' AS fjenis, i_nota, d_nota, v_netto AS vnota, v_sisa
        //                                 FROM
        //                                     tm_notamakloonbis2an
        //                             UNION ALL
        //                                 SELECT
        //                                     partner AS isupplier, 'Faktur Makloon Packing' AS fjenis, i_nota, d_nota, v_netto AS vnota, v_sisa
        //                                 FROM
        //                                     tm_notamakloonpacking ) AS a
        //                             INNER JOIN tr_supplier b ON
        //                                 (trim(a.isupplier) = trim(b.i_supplier))) AS c
        //                         LEFT JOIN tm_kasbank_keluar_item d ON
        //                             (c.i_nota = d.i_nota)
        //                         WHERE
        //                             c.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
        //                             AND c.d_nota <= to_date('$dto', 'dd-mm-yyyy')
        //                         GROUP BY
        //                             c.isupplier,
        //                             c.e_supplier_name,
        //                             c.fjenis,
        //                             c.i_nota,
        //                             c.d_nota,
        //                             c.vnota,
        //                             c.v_sisa
        //                         /*ORDER BY isupplier, d_nota DESC*/ ",FALSE);

        $datatables->query("    SELECT
                                    '' AS no,
                                    a.i_kasbank_keluar,
                                    to_char(a.d_kasbank_keluar, 'dd-mm-yyyy') AS d_kasbank_keluar,
                                    c.e_nama_kas,
                                    b.i_pembayaran,
                                    to_char(b.tanggal, 'dd-mm-yyyy') AS tanggal,
                                    b.v_total,
                                    b.partner,
                                    '$folder' AS folder,
                                    '$i_menu' AS i_menu,
                                    '$dfrom' AS dfrom,
                                    '$dto' AS dto,
                                    '$username' AS username
                                FROM
                                    tm_kasbank_keluar a
                                RIGHT JOIN tm_permintaan_pembayaranap b ON
                                    (a.i_pembayaran = b.i_pembayaran)
                                LEFT JOIN tm_kas_bank c ON
                                    (a.i_kasbank = c.i_kode_kas)
                                WHERE
                                b.tanggal >= to_date('$dfrom', 'dd-mm-yyyy')
                                AND b.tanggal <= to_date('$dto', 'dd-mm-yyyy')
                                $status
                                ORDER BY a.i_kasbank_keluar ",FALSE);

        $datatables->edit('v_total', function ($data) {
            return number_format($data['v_total']);
        });

        // $datatables->edit('i_reff', function ($data) {
        //     return '<span>'.str_replace("}", "", str_replace("{", "", str_replace("NULL","", str_replace('"',"", str_replace(",", "<br>", $data['i_reff']))))).'</span>';
        // });

        // $datatables->edit('v_sisa', function ($data) {
        //     return number_format($data['v_sisa']);
        // });

        // $datatables->edit('id', function ($data) {
        //     if ($data['f_giro_batal']=='t') {
        //         $data = '<p class="h2 text-danger">'.$data['id'].'</p>';
        //     }else{
        //         $data = $data['id'];
        //     }
        //     return $data;
        // });

        $datatables->add('action', function ($data) {
            $id                  = trim($data['i_pembayaran']);
            $partner             = $data['partner'];
            $status              = trim($data['i_kasbank_keluar']);
            if($status != ''){
                $status = "Sudah";
            }else{
                $status = "Belum";
            }
            $folder              = $data['folder'];
            $i_menu              = $data['i_menu'];
            $username            = $data['username'];
            $dfrom               = $data['dfrom'];
            $dto                 = $data['dto'];
            $data                = '';
            
            $data = "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$partner/$dfrom/$dto/$status/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            
            return $data;
        });

        $datatables->hide('partner');
        $datatables->hide('i_menu');
        $datatables->hide('username');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('folder');
        
        return $datatables->generate();
    }

    public function partner() {
        return $this->db->query(" select i_supplier, e_supplier_name from tr_supplier order by e_supplier_name ", FALSE);
    }

    public function jenis() {
        return $this->db->query(" select i_jenis, e_jenis_name from tr_jenis_faktur order by e_jenis_name ", FALSE);
    }

    public function baca($ipembayaran,$partner){
        $query = $this->db->query("
            select a.i_pembayaran, to_char(a.tanggal, 'dd-mm-yyyy') as tanggal, a.partner, b.e_supplier_name, to_char(a.permintaan_pembayaran, 'dd-mm-yyyy') as permintaan_pembayaran, 
            a.v_total, a.v_sisa, a.e_remark, a.i_status_dokumen, c.e_status
            from tm_permintaan_pembayaranap a
            inner join tr_supplier b on (b.i_supplier = a.partner)
            inner join tm_status_dokumen c on (c.i_status = a.i_status_dokumen)
            where a.i_pembayaran = '$ipembayaran' and a.partner = '$partner'
        ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function getdetailedit($partner, $jenis, $ipembayaran){
        return $this->db->query("
          select ROW_NUMBER() OVER(order by a.jatuh_tempo) as no, a.i_nota, to_char(a.d_nota, 'dd-mm-yyyy') as d_nota, a.jenis as i_jenis, b.e_jenis_name as jenis, 
          to_char(a.jatuh_tempo, 'dd-mm-yyyy') as jatuh_tempo, v_total as saldo, a.e_remark 
          from tm_permintaan_pembayaranap_item a
          left join tr_jenis_faktur b on (a.jenis = b.i_jenis)
          where i_pembayaran = '$ipembayaran'
          order by jatuh_tempo
          ", false);
    }
}

/* End of file Mmaster.php */