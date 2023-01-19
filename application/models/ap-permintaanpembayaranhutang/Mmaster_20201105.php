<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($folder,$i_menu,$dfrom,$dto){
        // $dfrom = date('Y-m-d', strtotime($dfrom));
        // $dto   = date('Y-m-d', strtotime($dto));
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select a.i_pembayaran, to_char(a.tanggal, 'dd-mm-yyyy') as tanggal, a.partner, b.e_supplier_name, to_char(a.permintaan_pembayaran, 'dd-mm-yyyy') as pp, 
            a.v_total, a.v_sisa, a.e_remark, a.i_status_dokumen, c.e_status, '$i_menu' as i_menu, '$folder' as folder
            from tm_permintaan_pembayaranap a
            inner join tr_supplier b on (b.i_supplier = a.partner)
            inner join tm_status_dokumen c on (c.i_status = a.i_status_dokumen)
            where a.tanggal between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy')
            order by a.tanggal
        ", FALSE);

        // $datatables->edit('i_status', function ($data) {
        //     return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        // });
          $datatables->edit('i_pembayaran', function ($data) {
            if ($data['i_status_dokumen']=='9') {
                $data = '<p class="h2 text-danger">'.$data['i_pembayaran'].'</p>';
            }else{
                $data = $data['i_pembayaran'];
            }
            return $data;
          });

          $datatables->edit('v_total', function ($data) {
            $data = "Rp. ".number_format($data['v_total']);
            return $data;
          });

          $datatables->edit('v_sisa', function ($data) {
            $data = "Rp. ".number_format($data['v_sisa']);
            return $data;
          });
          // $datatables->edit('status', function ($data) {
          //     $f_cancel = trim($data['f_cancel']);
          //     $qty           = trim($data['qty']);
          //     if($qty <> '0'){
          //       return  "Sudah Di Proses Oleh Gudang";
          //     }else if($f_cancel == 'f'){
          //       return "Menunggu Proses Gudang";
          //     } else if($f_cancel == 't') {
          //       return "Di Batalkan";
          //     }
          // });

        $datatables->add('action', function ($data) {
            $i_pembayaran        = trim($data['i_pembayaran']);
            $partner       = trim($data['partner']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $i_departement   = trim($this->session->userdata('i_departement'));
            $i_level         = trim($this->session->userdata('i_level'));
            $i_status       = $data['i_status_dokumen'];

            $data          = '';
            $data         .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$i_pembayaran/$partner\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            //$data         .= "<a href=\"#\" title='Print' onclick='printx(\"$id\",\"$ibagian\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
            if ($i_status != '6') {
                if (check_role($i_menu, 3)) {
                    if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                        $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$i_pembayaran/$partner\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                        // $data         .= "<a href=\"#\" title='Batal Kirim' onclick='batalkirim(\"$id\",\"$ibagian\",\"1\"); return false;'><i class='ti-reload'></i></a>&nbsp;&nbsp;&nbsp;";
                        // $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    if ((($i_departement == '3' && $i_level == '9') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {    
                        $data         .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$i_pembayaran/$partner\",\"#main\"); return false;'><i class='fa fa-check-square '></i></a>&nbsp;&nbsp;&nbsp;";
                    }    

                }
                if (check_role($i_menu, 4)) {
                    if ($i_status != '9') {
                        $data .= "<a href=\"#\" title='Cancel' onclick='cancel(\"$i_pembayaran\",\"$partner\"); return false;'><i class='ti-close'></i></a>";
                    }
                }
            }
            return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('partner');
        $datatables->hide('i_status_dokumen');
        return $datatables->generate();
    }
    
    public function partner() {
        return $this->db->query(" select i_supplier, e_supplier_name from tr_supplier order by e_supplier_name ", FALSE);
    }

    public function jenis() {
        return $this->db->query(" select i_jenis, e_jenis_name from tr_jenis_faktur order by e_jenis_name ", FALSE);
    }
    // public function nosjkeluar($cari,$partner) {
    //     $cari = str_replace("'", "", $cari);
    //     return $this->db->query(" select distinct(i_sj), d_sj from tm_sj_keluarpacking where i_status = '6' and i_unit_packing = '$partner' and i_sj ilike '%$cari%'", FALSE);
        
    // }

    // public function gethead($sj, $partner){
    //     return $this->db->query("
    //       select n_discount, pkp from tm_sj_keluarpacking where i_sj = '$sj' and i_unit_packing = '$partner'
    //     ", false);
    // }

    public function getdetail($partner, $jenis, $jtawal, $jtakhir){
        if ($jenis == "semua") {
            return $this->db->query("
               select ROW_NUMBER() OVER(order by jatuh_tempo) as no, i_nota, d_nota, jenis, i_jenis, to_char(jatuh_tempo, 'dd-mm-yyyy') as jatuh_tempo, saldo from 
               (
                select i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota,
                            'Faktur Makloon Bis Bisan' as jenis, 'JNM0002' as i_jenis, jatuh_tempo, v_sisa as saldo from (
                                select a.i_supplier, a.i_nota, a.d_nota , a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                                from tm_notamakloonbis2an a
                                inner join (
                                    select i_supplier, e_supplier_name, e_supplier_address, 
                                    case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                                ) as c on (a.i_supplier = c.i_supplier)
                                where a.i_status_dokumen = '6' and a.i_supplier = '$partner'
                            ) as x
                     where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
                     union all
                     select i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota,
                            'Faktur Makloon Jahit' as jenis, 'JNM0006' as i_jenis, jatuh_tempo, v_sisa as saldo from (
                                select a.partner, a.i_nota, a.d_nota, a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                                from tm_notamakloonjahit a
                                inner join (
                                    select i_supplier, e_supplier_name, e_supplier_address, 
                                    case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                                ) as c on (a.partner = c.i_supplier)
                                where a.i_status_dokumen = '6' and a.partner = '$partner'
                            ) as x
                            where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
                     union all
                     select i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota,
                            'Faktur Makloon Packing' as jenis,'JNM0007' as i_jenis, jatuh_tempo, v_sisa as saldo from (
                                select a.partner, a.i_nota, a.d_nota, a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                                from tm_notamakloonpacking a
                                inner join (
                                    select i_supplier, e_supplier_name, e_supplier_address, 
                                    case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                                ) as c on (a.partner = c.i_supplier)
                                where a.i_status_dokumen = '6' and a.partner = '$partner'
                            ) as x
                            where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
                     union all
                     select i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota, 
                            'Faktur Pembelian' as jenis,'KTG0001' as i_jenis, jatuh_tempo, v_sisa as saldo from (
                        select a.i_supplier, a.i_nota, a.d_nota, a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                        from tm_notabtb a
                        inner join (
                            select i_supplier, e_supplier_name, e_supplier_address, 
                            case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                        ) as c on (a.i_supplier = c.i_supplier)
                        where a.f_nota_cancel = 'f' and a.i_supplier = '$partner'
                                ) as x
                            where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
               ) as x  
            ",false);
        }else if ($jenis=="JNM0002") {
            if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.i_supplier = '$partner'";
            }
            return $this->db->query("
                select ROW_NUMBER() OVER(order by i_nota) as no, i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota,
                'Faktur Makloon Bis Bisan' as jenis,'JNM0002' as i_jenis, to_char(jatuh_tempo, 'dd-mm-yyyy') as jatuh_tempo, v_sisa as saldo from (
                    select a.i_supplier, a.i_nota, a.d_nota , a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                    from tm_notamakloonbis2an a
                    inner join (
                        select i_supplier, e_supplier_name, e_supplier_address, 
                        case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                    ) as c on (a.i_supplier = c.i_supplier)
                    where a.i_status_dokumen = '6' $partner
                ) as x
                where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
                order by jatuh_tempo
            ",false);
        }else if ($jenis=="JNM0006") {
            if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.partner = '$partner'";
            }
            return $this->db->query("
                select ROW_NUMBER() OVER(order by i_nota) as no, i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota,
                'Faktur Makloon Jahit' as jenis,'JNM0006' as i_jenis, to_char(jatuh_tempo, 'dd-mm-yyyy') as jatuh_tempo, v_sisa as saldo from (
                    select a.partner, a.i_nota, a.d_nota, a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                    from tm_notamakloonjahit a
                    inner join (
                        select i_supplier, e_supplier_name, e_supplier_address, 
                        case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                    ) as c on (a.partner = c.i_supplier)
                    where a.i_status_dokumen = '6' $partner
                ) as x
                where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
                order by jatuh_tempo
            ",false);
        } else if ($jenis=="JNM0007") {
            if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.partner = '$partner'";
            }
            return $this->db->query("
                 select ROW_NUMBER() OVER(order by i_nota) as no, i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota,
                'Faktur Makloon Packing' as jenis,'JNM0007' as i_jenis,  to_char(jatuh_tempo, 'dd-mm-yyyy') as jatuh_tempo, v_sisa as saldo from (
                    select a.partner, a.i_nota, a.d_nota, a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                    from tm_notamakloonpacking a
                    inner join (
                        select i_supplier, e_supplier_name, e_supplier_address, 
                        case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                    ) as c on (a.partner = c.i_supplier)
                    where a.i_status_dokumen = '6' $partner
                ) as x
                 where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
                order by jatuh_tempo
            ",false);
        } else if ($jenis=="KTG0001") {
             if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.i_supplier = '$partner'";
            }
            return $this->db->query("
                select ROW_NUMBER() OVER(order by i_nota) as no, i_nota, to_char(d_nota, 'dd-mm-yyyy') as d_nota, 
                'Faktur Pembelian' as jenis,'KTG0001' as i_jenis,  to_char(jatuh_tempo, 'dd-mm-yyyy') as jatuh_tempo, v_sisa as saldo from (
                select a.i_supplier, a.i_nota, a.d_nota, a.d_nota::date+c.top as jatuh_tempo, a.v_sisa
                from tm_notabtb a
                inner join (
                    select i_supplier, e_supplier_name, e_supplier_address, 
                    case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                ) as c on (a.i_supplier = c.i_supplier)
                where a.f_nota_cancel = 'f' $partner
                    ) as x
                where v_sisa > 0 and jatuh_tempo between to_date('$jtawal', 'dd-mm-yyyy') and to_date('$jtakhir', 'dd-mm-yyyy')
                order by jatuh_tempo
            ",false);
        }
    }

    public function runningnumber($thbl){
        $th = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $lok     = $this->session->userdata('i_lokasi');
        $query = $this->db->query("
            SELECT
                n_modul_no AS max
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'PPH'
                AND i_area = '$lok'
                AND e_periode = '$asal'
                AND substring(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobonmk  =$terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nobonmk
                WHERE
                    i_modul = 'PPH'
                    AND e_periode = '$asal'
                    AND i_area = '$lok'
                    AND substring(e_periode, 1, 4)= '$th'
            ", false);
            settype($nobonmk,"string");
            $a=strlen($nobonmk);
            while($a<5){
                $nobonmk="0".$nobonmk;
                $a=strlen($nobonmk);
            }
            $nobonmk  ="PPH-".$lok."-".$thbl."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="00001";
            $nobonmk  ="PPH-".$lok."-".$thbl."-".$nobonmk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('PPH', '$lok', '$asal', 1)
            ");
            return $nobonmk;
        }
    }

    public function insertheader($ipermintaan, $partner, $datepermintaan, $datebayar, $jumlah, $remark) {
        // i_pembayaran, tanggal, partner, permintaan_pembayaran, v_total, v_sisa

        $dentry = current_datetime();
        $data = array(
            'i_pembayaran'          => $ipermintaan,
            'tanggal'               => $datepermintaan,
            'partner'               => $partner,
            'permintaan_pembayaran' => $datebayar,
            'v_total'          => $jumlah,
            'v_sisa'           => $jumlah,
            'e_remark'         => $remark,
            'i_status_dokumen' => '1',
            'd_entry'          => $dentry,
        );
        $this->db->insert('tm_permintaan_pembayaranap', $data);
    }

    public function insertdetail($ipermintaan,$partner,$i_nota, $d_nota, $ijenis, $jatuh_tempo, $saldo, $edesc, $x) {
        $data = array(
            'i_pembayaran'    => $ipermintaan,
            'partner'         => $partner,
            'i_nota'          => $i_nota,
            'd_nota'          => $d_nota,
            'jatuh_tempo'     => $jatuh_tempo,
            'v_total'         => $saldo,
            'e_remark'        => $edesc,
            'jenis'           => $ijenis,
            'i_no_item'       => $x,
        );
        $this->db->insert('tm_permintaan_pembayaranap_item', $data);
    }

    public function send($kode){
      $data = array(
          'i_status_dokumen'    => '2'
      );

      $this->db->where('i_pembayaran', $kode);
      $this->db->update('tm_permintaan_pembayaranap', $data);
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

    public function updateheader($ipermintaan, $partner, $datepermintaan, $datebayar, $jumlah, $remark) {
        $dentry = current_datetime();
        $data = array(
            'tanggal'               => $datepermintaan,
            'partner'               => $partner,
            'permintaan_pembayaran' => $datebayar,
            'v_total'               => $jumlah,
            'v_sisa'                => $jumlah,
            'e_remark'              => $remark,
            'i_status_dokumen'      => '1',
            'd_update'              => $dentry,
        );
        $this->db->where('i_pembayaran',$ipermintaan);
        $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    function deletedetail($ipermintaan) {
         $this->db->query("DELETE FROM tm_permintaan_pembayaranap_item WHERE i_pembayaran='$ipermintaan'");
    }

    public function change($kode){
      $data = array(
          'i_status_dokumen'    => '3'
      );

      $this->db->where('i_pembayaran', $kode);
      $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    public function reject($kode){
      $data = array(
          'i_status_dokumen'    => '4'
      );

      $this->db->where('i_pembayaran', $kode);
      $this->db->update('tm_permintaan_pembayaranap', $data);
    }

    function cek_approve($ipermintaan, $inota,$partner) {
        return $this->db->query("
            select case when b.i_nota is null then 'kosong' else 'ada' end as validasi, b.i_nota from tm_permintaan_pembayaranap a
            inner join tm_permintaan_pembayaranap_item b on (a.i_pembayaran = b.i_pembayaran and a.partner = b.partner)
            where a.i_pembayaran <> '$ipermintaan' and a.i_status_dokumen = '6' and i_nota = '$inota' limit 1
        ", false);
    }


    public function approve($ipermintaan, $inota,$partner){
      $now = date("Y-m-d");
      $data = array(
          'i_status_dokumen'    => '6',
          'd_approve' => $now
      );

      $this->db->where('i_pembayaran', $ipermintaan);
      $this->db->update('tm_permintaan_pembayaranap', $data);
    }


    function updatesaldo($i_nota,$ijenis) {

        if ($ijenis=="JNM0002") {
            $this->db->query("UPDATE  tm_notamakloonbis2an set v_sisa = 0 WHERE i_nota='$i_nota'");
        }else if ($ijenis=="JNM0006") {
            $this->db->query("UPDATE  tm_notamakloonjahit set v_sisa = 0 WHERE i_nota='$i_nota'");
        } else if ($ijenis=="JNM0007") {
            $this->db->query("UPDATE  tm_notamakloonpacking set v_sisa = 0 WHERE i_nota='$i_nota'");
        } else if ($ijenis=="KTG0001") {
            $this->db->query("UPDATE  tm_notabtb set v_sisa = 0 WHERE i_nota='$i_nota'");
        }
        
    }

    // public function getdetailview($isj, $partner, $nota){
    //       return $this->db->query("
    //             select a.i_sj_masuk as i_sj, a.i_product as i_wip, e_product_basename as e_namabrg, a.i_color, a.n_quantity as qty_sudahnota, c.e_color_name, a.v_price from tm_notamakloonpacking_item a 
    //                 inner join tr_product_base b on (a.i_product = b.i_product_motif)
    //                 inner join tr_color c on (a.i_color = c.i_color)
    //                 where a.i_nota = '$nota' and a.partner = '$partner'
    //                 order by a.i_sj_masuk, a.i_product
    //         ", false);
    // }

    public function cancel($ipembayaran, $partner){
        $data = array(
             'i_status_dokumen'    => '9',
        );
        
        $this->db->where('i_pembayaran', $ipembayaran);
        $this->db->where('partner', $partner);
        $this->db->update('tm_permintaan_pembayaranap', $data);
    }









    // public function gudang()
    // {
    //     $dep = $this->session->userdata('i_departement');
    //     return $this->db->query("
    //         SELECT * FROM public.tr_departement where i_departement = '$dep'
    //     ", FALSE);
    // }

    // public function gudangjadi()
    // {
    //     $i_apps   = $this->session->userdata('i_apps');
    //     return $this->db->query("
    //         select * from public.tr_lokasi where i_apps = '$i_apps' and e_lokasi_name ilike '%gudang jadi%'
    //     ", FALSE);
    // }

    // public function ppic($cari)
    // {
    //     $cari = str_replace("'", "", $cari);
    //     return $this->db->query("
    //         SELECT
    //             i_karyawan AS id, 
    //             e_nama_karyawan AS name
    //         FROM tm_karyawan
    //         WHERE (UPPER(e_nama_karyawan) LIKE '%$cari%')
    //         ORDER BY e_nama_karyawan ASC
    //     ", FALSE);
    // }

    // public function product($cari){
    //     $cari = str_replace("'", "", $cari);
    //     return $this->db->query("            
    //         SELECT
    //             a.i_product_motif,
    //             a.e_product_basename,
    //             a.i_color,
    //             b.e_color_name
    //         FROM
    //             tr_product_base a
    //         INNER JOIN tr_color b ON
    //             (b.i_color = a.i_color)
    //         WHERE (i_product_motif ILIKE '%$cari%' OR e_product_basename iLIKE '%$cari%') order by a.e_product_basename /*AND f_status_product = 't'*/
    //     ", FALSE);
    // }

    // public function getproduct($iproduct, $icolor, $ikodelokasi){
    //     return $this->db->query("            
    //         SELECT i_product_motif, e_product_basename, i_color, e_color_name, qty_ic - qty_sisa as qty_reserved from (
    //             SELECT
    //             i_product_motif,
    //             e_product_basename,
    //             a.i_color,
    //             e_color_name,
    //             case when x.n_qty is null then 0 else x.n_qty end as qty_ic,
    //             case when y.n_qty_sisa is null then 0 else y.n_qty_sisa end as qty_sisa
    //             FROM
    //             tr_product_base a
    //             INNER JOIN tr_color b ON (b.i_color = a.i_color)
    //             left join (
    //                 select i_product, i_kode_lokasi, i_color, sum(n_quantity_stock) as n_qty from tm_ic 
    //                 where i_kode_lokasi = '$ikodelokasi' and i_product = '$iproduct' and i_color = '$icolor'
    //                 group by i_product, i_kode_lokasi, i_color
    //             ) as x on (x.i_product = a.i_product_motif and x.i_color = a.i_color)
    //             left join (
    //                 select  b.i_product, b.i_color, sum(n_qty_sisa) as n_qty_sisa from tm_permintaanpengeluarangdjadi a
    //                 inner join tm_permintaanpengeluarangdjadi_detail b on (a.i_memo = b.i_memo and b.i_kode_lokasi = a.i_kode_lokasi)
    //                 where a.f_cancel = 'f' and b.i_product = '$iproduct' and b.i_color = '$icolor'
    //                 group by b.i_product, b.i_color
    //             ) as y on (y.i_product = a.i_product_motif and y.i_color = a.i_color)
    //             WHERE a.i_product_motif = '$iproduct' and a.i_color = '$icolor'
    //         ) as final
    //     ", FALSE);
    // }


    // public function bacadetail($imemo,$ikodelokasi){
    //     $query = $this->db->query("
    //         select i_product_motif, e_product_basename, i_color, e_color_name, qty_ic - qty_sisa as qty_reserved, n_qty, e_remark,sisa_now
    //         from (
    //             SELECT
    //             a.i_product as i_product_motif,
    //             c.e_product_basename,
    //             a.i_color,
    //             b.e_color_name,
    //             case when x.n_qty is null then 0 else x.n_qty end as qty_ic,
    //                 case when y.n_qty_sisa is null then 0 else y.n_qty_sisa end as qty_sisa,
    //                 a.n_qty, a.e_remark, a.n_qty_sisa as sisa_now
    //             FROM
    //             tm_permintaanpengeluarangdjadi_detail a
    //             inner join tr_color b on (a.i_color = b.i_color)
    //             left join (
    //                 select i_product, i_kode_lokasi, i_color, sum(n_quantity_stock) as n_qty from tm_ic 
    //                 where i_kode_lokasi = '$ikodelokasi'
    //                 group by i_product, i_kode_lokasi, i_color
    //             ) as x on (x.i_product = a.i_product and x.i_color = a.i_color)
    //             left join (
    //                 select  b.i_product, b.i_color, sum(n_qty_sisa) as n_qty_sisa from tm_permintaanpengeluarangdjadi a
    //                 inner join tm_permintaanpengeluarangdjadi_detail b on (a.i_memo = b.i_memo and b.i_kode_lokasi = a.i_kode_lokasi)
    //                 where a.f_cancel = 'f' and b.i_memo <> '$imemo' 
    //                 group by b.i_product, b.i_color
    //             ) as y on (y.i_product = a.i_product and y.i_color = a.i_color)
    //             inner join tr_product_base c on (a.i_product = c.i_product_motif)
    //             WHERE 
    //             a.i_memo = '$imemo' 
    //             AND a.i_kode_lokasi = '$ikodelokasi'
    //             ORDER BY i_no_item
    //         ) as x
    //     ", FALSE);
    //     if ($query->num_rows() > 0) {
    //         return $query->result();
    //     }
    // }

    // public function cancelitem($ibonk,$icolor,$iproduct,$ibagian){
    //     $this->db->where('i_bonmk', $ibonk);
    //     $this->db->where('i_product', $iproduct);
    //     $this->db->where('i_color', $icolor);
    //     $this->db->where('i_kode_master', $ibagian);
    //     return $this->db->delete('tm_bonmkeluar_pinjamanbj_detail');
    // }

    // public function updatestatus($ibonk,$status,$ibagian){
    //     $dentry = current_datetime();
    //     if ($status=='6') {
    //         $data = array(
    //             'i_status' => $status,
    //             'd_approve' => $dentry,
    //         );
    //     }else{
    //         $data = array(
    //             'i_status' => $status,
    //         );
    //     }
    //     $this->db->where('i_bonmk', $ibonk);
    //     $this->db->where('i_kode_master', $ibagian);
    //     $this->db->update('tm_bonmkeluar_pinjamanbj', $data);
    // }
}

/* End of file Mmaster.php */
