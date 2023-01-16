<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getpartner(){
      $this->db->select("i_unit_jahit, e_unitjahit_name from tr_unit_jahit order by e_unitjahit_name", false);
      return $this->db->get()->result();
    }

  public function getQCset($dfrom, $dto){
        //header("Content-Type: application/json", true);   
        $pisah1 = explode("-", $dfrom);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];
        $iperiode = $thn1.$bln1;
        if($bln1 == 1) {
          $bln_query = 12;
          $thn_query = $thn1-1;
        }else {
          $bln_query = $bln1-1;
          $thn_query = $thn1;
          if ($bln_query < 10){
            $bln_query = "0".$bln_query;
          }
        }
        $pisah1 = explode("-", $dto);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];

        $this->db->select("* from f_mutasi_qcset($bln_query, $thn_query, '$dfrom','$dto', $bln1, $thn1)",false);
        $data = $this->db->get();
        return $data;
  }

  function data($dto,$jenis,$partner){
    $datatables = new Datatables(new CodeigniterAdapter);
        if ($jenis == "semua") {
            $datatables->query("
               select  ROW_NUMBER() OVER(order by e_supplier_name, i_nota) as no, e_supplier_name, e_supplier_address, top, jenis, i_nota, d_pajak, jatuh_tempo, saldo, umur, telat, status, keterangan
                from (
                    select  e_supplier_name, e_supplier_address, top || ' Hari' as top,
                    'Faktur Makloon Bis Bisan' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo, umur || ' Hari' as umur, telat || ' Hari' as telat, 
                    case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                    case when v_netto = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan  from (
                        select a.i_supplier, c.e_supplier_name, c.e_supplier_address, c.top,
                        a.i_sj_keluar, a.i_nota, a.i_pajak, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                        to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                        to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                        a.v_netto, a.v_sisa
                        from tm_notamakloonbis2an a
                        inner join (
                            select i_supplier, e_supplier_name, e_supplier_address, 
                            case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                        ) as c on (a.i_supplier = c.i_supplier) 
                        where a.i_status_dokumen = '6' and a.i_supplier = case when '$partner' = 'semua' then a.i_supplier else '$partner' end
                    ) as x
                    where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                    UNION ALL
                    select e_supplier_name, e_supplier_address, top || ' Hari' as top,
                    'Faktur Makloon Jahit' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo , umur || ' Hari' as umur, telat || ' Hari' as telat, 
                    case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                    case when v_total = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan from (
                        select a.partner, c.e_supplier_name, c.e_supplier_address, c.top,
                        a.i_sj_keluar, a.i_nota, a.no_pajakmakloon, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                        to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                        to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                        a.v_total, a.v_sisa
                        from tm_notamakloonjahit a
                        inner join (
                            select i_supplier, e_supplier_name, e_supplier_address, 
                            case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                        ) as c on (a.partner = c.i_supplier) 
                        where a.i_status_dokumen = '6' and a.partner = case when '$partner' = 'semua' then a.partner else '$partner' end
                    ) as x
                    where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                    UNION ALL
                    select e_supplier_name, e_supplier_address, top || ' Hari' as top,
                    'Faktur Makloon Packing' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo, umur || ' Hari' as umur, telat || ' Hari' as telat, 
                    case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                    case when v_total = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan from (
                        select a.partner, c.e_supplier_name, c.e_supplier_address, c.top,
                        a.i_sj_keluar, a.i_nota, a.no_pajakmakloon, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                        to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                        to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                        a.v_total, a.v_sisa
                        from tm_notamakloonpacking a
                        inner join (
                            select i_supplier, e_supplier_name, e_supplier_address, 
                            case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                        ) as c on (a.partner = c.i_supplier)
                        where a.i_status_dokumen = '6' and a.partner = case when '$partner' = 'semua' then a.partner else '$partner' end
                    ) as x
                    where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                    UNION ALL
                    select e_supplier_name, e_supplier_address, top || ' Hari' as top,
                    'Faktur Pembelian' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo, umur || ' Hari' as umur, telat || ' Hari' as telat, 
                    case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                    case when v_total = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan from (
                    select a.i_supplier, c.e_supplier_name, c.e_supplier_address, c.top,
                    a.i_nota, a.i_pajak, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                    to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                    to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                    a.v_total, a.v_sisa
                    from tm_notabtb a
                    inner join (
                        select i_supplier, e_supplier_name, e_supplier_address, 
                        case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                    ) as c on (a.i_supplier = c.i_supplier)
                    where a.f_nota_cancel = 'f' and a.i_supplier = case when '$partner' = 'semua' then a.i_supplier else '$partner' end
                    ) as x
                    where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                ) as x
            ",false);
        }else if ($jenis=="JNM0002") {
            if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.i_supplier = '$partner'";
            }
            $datatables->query("
            select ROW_NUMBER() OVER(order by i_nota) as no, e_supplier_name, e_supplier_address, top || ' Hari' as top,
                'Faktur Makloon Bis Bisan' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo, umur || ' Hari' as umur, telat || ' Hari' as telat, 
                case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                case when v_netto = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan  from (
                    select a.i_supplier, c.e_supplier_name, c.e_supplier_address, c.top,
                    a.i_sj_keluar, a.i_nota, a.i_pajak, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                    to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                    to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                    a.v_netto, a.v_sisa
                    from tm_notamakloonbis2an a
                    inner join (
                        select i_supplier, e_supplier_name, e_supplier_address, 
                        case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                    ) as c on (a.i_supplier = c.i_supplier)
                    where a.i_status_dokumen = '6' $partner
                ) as x
                where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                order by e_supplier_name
            ",false);
        }else if ($jenis=="JNM0006") {
            if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.partner = '$partner'";
            }
            $datatables->query("
                select ROW_NUMBER() OVER(order by i_nota) as no, e_supplier_name, e_supplier_address, top || ' Hari' as top,
                'Faktur Makloon Jahit' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo , umur || ' Hari' as umur, telat || ' Hari' as telat, 
                case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                case when v_total = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan from (
                    select a.partner, c.e_supplier_name, c.e_supplier_address, c.top,
                    a.i_sj_keluar, a.i_nota, a.no_pajakmakloon, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                    to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                    to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                    a.v_total, a.v_sisa
                    from tm_notamakloonjahit a
                    inner join (
                        select i_supplier, e_supplier_name, e_supplier_address, 
                        case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                    ) as c on (a.partner = c.i_supplier)
                    where a.i_status_dokumen = '6' $partner
                ) as x
                where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                order by e_supplier_name
            ",false);
        } else if ($jenis=="JNM0007") {
            if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.partner = '$partner'";
            }
            $datatables->query("
                select ROW_NUMBER() OVER(order by i_nota) as no, e_supplier_name, e_supplier_address, top || ' Hari' as top,
                'Faktur Makloon Packing' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo, umur || ' Hari' as umur, telat || ' Hari' as telat, 
                case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                case when v_total = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan from (
                    select a.partner, c.e_supplier_name, c.e_supplier_address, c.top,
                    a.i_sj_keluar, a.i_nota, a.no_pajakmakloon, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                    to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                    to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                    a.v_total, a.v_sisa
                    from tm_notamakloonpacking a
                    inner join (
                        select i_supplier, e_supplier_name, e_supplier_address, 
                        case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                    ) as c on (a.partner = c.i_supplier)
                    where a.i_status_dokumen = '6' $partner
                ) as x
                where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                order by e_supplier_name
            ",false);
        } else if ($jenis=="KTG0001") {
             if ($partner == 'semua') {
                $partner = "";
            } else {
                $partner = "and a.i_supplier = '$partner'";
            }
            $datatables->query("
                select ROW_NUMBER() OVER(order by e_supplier_name,i_nota) as no, e_supplier_name, e_supplier_address, top || ' Hari' as top,
                'Faktur Pembelian' as jenis, i_nota, to_char(d_pajak, 'dd-mm-yyyy') as d_pajak, jatuh_tempo, v_sisa as saldo, umur || ' Hari' as umur, telat || ' Hari' as telat, 
                case when telat > 0 then 'Jatuh Tempo' else 'Belum Jatuh Tempo' end as status, 
                case when v_total = v_sisa then 'Nota Utuh' else 'Nota Sisa' end as keterangan from (
                select a.i_supplier, c.e_supplier_name, c.e_supplier_address, c.top,
                a.i_nota, a.i_pajak, a.d_pajak, a.d_pajak::date+c.top as jatuh_tempo,
                to_date('$dto','dd-mm-yyyy') - a.d_pajak::date as umur, 
                to_date('$dto','dd-mm-yyyy') - (a.d_pajak::date+c.top) as Telat,
                a.v_total, a.v_sisa
                from tm_notabtb a
                inner join (
                    select i_supplier, e_supplier_name, e_supplier_address, 
                    case when n_supplier_toplength is null then 0 else n_supplier_toplength end as top from tr_supplier
                ) as c on (a.i_supplier = c.i_supplier)
                where a.f_nota_cancel = 'f' $partner
                ) as x
                where d_pajak <= to_date('$dto','dd-mm-yyyy') and v_sisa > 0
                order by e_supplier_name
            ",false);
        }
        
        
        $datatables->edit('saldo', function ($data) {
            $data = "Rp. ".number_format($data['saldo']);
            return $data;
        });
        // $datatables->edit('f_bonk_cancel', function ($data) {
        //     $f_bonk_cancel = trim($data['f_bonk_cancel']);
        //     if($f_bonk_cancel == 'f'){
        //         return  "Aktif";
        //     }else {
        //         return "Batal";
        //     }
        // });
            // $datatables->edit('e_status', function ($data) {
            //   return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
            // });

            // $datatables->add('action', function ($data) {
            // $i_sj    = trim($data['i_sj']);
            // $i_menu     = $data['i_menu'];
            // $i_status    = trim($data['i_status']);
            // $i_departement= trim($data['i_departement']);
            // $i_level      = trim($data['i_level']);
            
            // $data       = '';

            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"penerimaanbarangunitjahit/cform/view/$i_sj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }

            // if(check_role($i_menu, 3)){
            //     if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
            //         $data .= "<a href=\"#\" onclick='show(\"penerimaanbarangunitjahit/cform/edit/$i_sj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            //     }

            //     if ((($i_departement == '18' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
            //         $data .= "<a href=\"#\" onclick='show(\"penerimaanbarangunitjahit/cform/approval/$i_sj\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
            //     }
            // }
            // if ($i_status!='6') {
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sj\"); return false;'><i class='fa fa-trash'></i></a>";
            // }
            
            
            // return $data;
            // });
            
        // $datatables->hide('icolor');
        // $datatables->hide('git');

        return $datatables->generate();
  }

   public function partner($cari,$jenis) {
        $cari = str_replace("'", "", $cari);
        if ($jenis == "semua" || $jenis == "KTG0001") {
            return $this->db->query(" 
            select i_supplier, e_supplier_name, urut from (
                select i_supplier, e_supplier_name, '2' as urut from tr_supplier
                union all
                select 'semua' as i_supplier, 'Semua Partner' as e_supplier_name, '1' as urut
            ) as x
            where e_supplier_name ilike '%$cari%'
            order by urut, e_supplier_name", FALSE);
        } else {
            return $this->db->query(" 
            select i_supplier, e_supplier_name, urut from (
                select i_supplier, e_supplier_name, '2' as urut from tr_supplier where i_type_makloon = '$jenis' 
                union all
                select 'semua' as i_supplier, 'Semua Partner' as e_supplier_name, '1' as urut
            ) as x
            where e_supplier_name ilike '%$cari%'
            order by urut, e_supplier_name", FALSE);
        }
    }
}
/* End of file Mmaster.php */