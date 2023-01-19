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
            select a.i_kn, to_char(a.d_kn, 'dd-mm-yyyy') as d_kn, b.e_customer_name, a.i_nota, 
            a.v_gross, a.v_discount, a.v_netto, a.v_dpp, a.v_ppn, a.v_total, a.e_remark, c.e_status,
            a.i_status_dokumen, a.i_customer, '$i_menu' as i_menu, '$folder' as folder
            from tm_notakredit a 
            inner join tr_customer b on (a.i_customer = b.i_customer)
            inner join tm_status_dokumen c on (a.i_status_dokumen = c.i_status)
            where a.d_kn between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy')
            order by a.d_kn
        ", FALSE);

        // $datatables->edit('i_status', function ($data) {
        //     return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        // });
          $datatables->edit('i_kn', function ($data) {
            if ($data['i_status_dokumen']=='9') {
                $data = '<p class="h2 text-danger">'.$data['i_kn'].'</p>';
            }else{
                $data = $data['i_kn'];
            }
            return $data;
          });

          $datatables->edit('v_gross', function ($data) {
            $data = "Rp. ".number_format($data['v_gross']);
            return $data;
          });

           $datatables->edit('v_discount', function ($data) {
            $data = "Rp. ".number_format($data['v_discount']);
            return $data;
          });

            $datatables->edit('v_netto', function ($data) {
            $data = "Rp. ".number_format($data['v_netto']);
            return $data;
          });

        $datatables->edit('v_dpp', function ($data) {
            $data = "Rp. ".number_format($data['v_dpp']);
            return $data;
          });

          $datatables->edit('v_ppn', function ($data) {
            $data = "Rp. ".number_format($data['v_ppn']);
            return $data;
          });

          $datatables->edit('v_total', function ($data) {
            $data = "Rp. ".number_format($data['v_total']);
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
            $i_kn        = trim($data['i_kn']);
            $i_customer       = trim($data['i_customer']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $i_departement   = trim($this->session->userdata('i_departement'));
            $i_level         = trim($this->session->userdata('i_level'));
            $i_status       = $data['i_status_dokumen'];

            $data          = '';
            $data         .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$i_kn/$i_customer\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            //$data         .= "<a href=\"#\" title='Print' onclick='printx(\"$id\",\"$ibagian\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
            if ($i_status != '6') {
                if (check_role($i_menu, 3)) {
                    if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                        $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$i_kn/$i_customer\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                        // $data         .= "<a href=\"#\" title='Batal Kirim' onclick='batalkirim(\"$id\",\"$ibagian\",\"1\"); return false;'><i class='ti-reload'></i></a>&nbsp;&nbsp;&nbsp;";
                        // $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    if ((($i_departement == '3' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {    
                        $data         .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$i_kn/$i_customer\",\"#main\"); return false;'><i class='fa fa-check-square '></i></a>&nbsp;&nbsp;&nbsp;";
                    }    

                }
                if (check_role($i_menu, 4)) {
                    if ($i_status != '9') {
                        $data .= "<a href=\"#\" title='Cancel' onclick='cancel(\"$i_kn\",\"$i_customer\"); return false;'><i class='ti-close'></i></a>";
                    }
                }
            }
            return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_customer');
        $datatables->hide('i_status_dokumen');
        return $datatables->generate();
    }
    
    public function customer() {
        return $this->db->query("
             select a.i_customer, b.e_customer_name  from tm_bbmretur a
             inner join tr_customer b on (a.i_customer = b.i_customer)
             inner join (
               select i_bbm, sum(n_quantity_sisa) as sisa from tm_bbmretur_item group by i_bbm
             )as c on (a.i_bbm = c.i_bbm)
             where a.i_status_dokumen = '6' and c.sisa > 0
             order by e_customer_name asc
        ", FALSE);
    }

    public function customer2($ikn) {
        return $this->db->query("
            select a.i_customer, b.e_customer_name from 
            (
              select a.i_customer from tm_bbmretur a
              inner join (
               select i_bbm, sum(n_quantity_sisa) as sisa from tm_bbmretur_item group by i_bbm
              )as c on (a.i_bbm = c.i_bbm)
              where a.i_status_dokumen = '6' and c.sisa > 0
              union all
              select i_customer from tm_notakredit where i_kn = '$ikn'
            ) as a
            inner join tr_customer b on (a.i_customer = b.i_customer)
            group by a.i_customer, b.e_customer_name
            order by e_customer_name asc
        ", FALSE);
    }

    public function nonotaretur($cari,$partner) {
        $cari = str_replace("'", "", $cari);
        return $this->db->query(" 
            select distinct(b.i_nota) from tm_bbmretur a
            inner join (
               select i_bbm, sum(n_quantity_sisa) as sisa from tm_bbmretur_item group by i_bbm
            )as c on (a.i_bbm = c.i_bbm)
            inner join tm_ttbretur b on (a.i_ttb = b.i_ttb)
            where a.i_status_dokumen = '6' and c.sisa > 0 and a.i_customer = '$partner' and (b.i_nota ilike '%$cari%')", FALSE);
        
    }

    public function gethead($kode, $partner){
        return $this->db->query("
         select case when b.n_discount is null then 0 else b.n_discount end as n_discount, c.f_customer_pkp as pkp, d.e_lokasi_name from tm_bbmretur a
         inner join tm_ttbretur b on (a.i_ttb = b.i_ttb)
         inner join tr_customer c on (a.i_customer = c.i_customer)
         inner join public.tr_lokasi d on (a.i_kode_lokasi = d.i_kode_lokasi)
         where b.i_nota = '$kode' and a.i_status_dokumen = '6' limit 1
        ", false);
    }

    public function getdetail($kode, $partner){
          return $this->db->query("
            select a.i_bbm, a.i_product, a.i_color, e.v_unit_price as v_price, a.n_quantity_sisa as sisa, b.e_product_basename, 
            c.e_color_name, f.e_lokasi_name
            from tm_bbmretur_item a
                inner join tm_bbmretur d on (a.i_bbm = d.i_bbm)
                inner join tm_ttbretur_item e on (d.i_ttb = e.i_ttb)
                inner join tr_product_base b on (a.i_product = b.i_product_motif)
                inner join tr_color c on (a.i_color = c.i_color)
                inner join public.tr_lokasi f on (f.i_kode_lokasi = d.i_kode_lokasi)
                where e.i_nota = '$kode' and d.i_status_dokumen = '6' and a.n_quantity_sisa > 0
            order by b.e_product_basename
            ", false);
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
                i_modul = 'KN'
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
                    i_modul = 'KN'
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
            $nobonmk  ="KN-".$lok."-".$thbl."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="00001";
            $nobonmk  ="KN-".$lok."-".$thbl."-".$nobonmk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('KN', '$lok', '$asal', 1)
            ");
            return $nobonmk;
        }
    }

    public function insertheader($ikn, $customer, $nota, $datekn, $pkp, $gross, $ndiscount, $discount, $netto, $dpp, $ppn, $total, $remark ) {
        $dentry = current_datetime();
        $data = array(
            'i_kn'            => $ikn,
            'd_kn'            => $datekn,
            'i_customer'      => $customer,
            'i_nota'          => $nota,
            'pkp'             => $pkp,
            'e_remark'        => $remark,
            'v_gross'         => $gross,
            'n_discount'      => $ndiscount,
            'v_discount'      => $discount,
            'v_netto'         => $netto,
            'v_dpp'           => $dpp,
            'v_ppn'           => $ppn,
            'v_total'         => $total,
            'v_sisa'          => $total,
            'd_entry'         => $dentry,
        );
        $this->db->insert('tm_notakredit', $data);
    }

    public function insertdetail($ikn,$customer,$i_bbm, $i_product, $i_color,$qty,$v_price,$v_gross,$edesc,$x) {
        $data = array(
            'i_kn'            => $ikn,
            'i_customer'      => $customer,
            'i_bbm'           => $i_bbm,
            'i_product'       => $i_product,
            'i_color'         => $i_color,
            'n_quantity'      => $qty,
            'v_price'         => $v_price,
            'v_tot'           => $v_gross,
            'e_remark'        => $edesc,
            'i_no_item'       => $x,
        );
        $this->db->insert('tm_notakredit_item', $data);
    }

    public function send($kode){
      $data = array(
          'i_status_dokumen'    => '2'
      );

      $this->db->where('i_kn', $kode);
      $this->db->update('tm_notakredit', $data);
    }

    public function baca($ikn,$icustomer){
        $query = $this->db->query("
            select a.i_kn, to_char(a.d_kn, 'dd-mm-yyyy') as d_kn, b.e_customer_name, a.i_nota, 
            a.v_gross, a.v_discount, a.v_netto, a.v_dpp, a.v_ppn, a.v_total, a.e_remark,
            a.i_status_dokumen, a.i_customer, a.n_discount, a.pkp
            from tm_notakredit a 
            inner join tr_customer b on (a.i_customer = b.i_customer)
            where a.i_kn = '$ikn' and a.i_customer = '$icustomer'
        ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function getdetailedit($ikn, $icustomer, $nota){
          return $this->db->query("
               select a.*, b.n_quantity as qty, b.e_remark from (
                 select a.i_bbm, a.i_product, a.i_color, e.v_unit_price as v_price, a.n_quantity_sisa as sisa, b.e_product_basename, 
                            c.e_color_name, f.e_lokasi_name
                            from tm_bbmretur_item a
                                inner join tm_bbmretur d on (a.i_bbm = d.i_bbm)
                                inner join tm_ttbretur_item e on (d.i_ttb = e.i_ttb)
                                inner join tr_product_base b on (a.i_product = b.i_product_motif)
                                inner join tr_color c on (a.i_color = c.i_color)
                                inner join public.tr_lokasi f on (f.i_kode_lokasi = d.i_kode_lokasi)
                                where e.i_nota = '$nota' and d.i_status_dokumen = '6' and a.n_quantity_sisa > 0
                            order by b.e_product_basename
                ) as a left join (
                    select a.i_bbm, a.i_product, a.i_color, a.n_quantity, a.e_remark from tm_notakredit_item a 
                    inner join tm_notakredit b on (a.i_kn = b.i_kn)
                    where a.i_kn = '$ikn'
                ) as b on (a.i_bbm = b.i_bbm and a.i_product = b.i_product and a.i_color = b.i_color)
            ", false);
    }

    public function getdetailapprove($ikn, $icustomer, $nota){
          return $this->db->query("
               select a.*, b.n_quantity as qty, b.e_remark from (
                 select a.i_bbm, a.i_product, a.i_color, e.v_unit_price as v_price, a.n_quantity_sisa as sisa, b.e_product_basename, 
                            c.e_color_name, f.e_lokasi_name
                            from tm_bbmretur_item a
                                inner join tm_bbmretur d on (a.i_bbm = d.i_bbm)
                                inner join tm_ttbretur_item e on (d.i_ttb = e.i_ttb)
                                inner join tr_product_base b on (a.i_product = b.i_product_motif)
                                inner join tr_color c on (a.i_color = c.i_color)
                                inner join public.tr_lokasi f on (f.i_kode_lokasi = d.i_kode_lokasi)
                                where e.i_nota = '$nota' and d.i_status_dokumen = '6'
                            order by b.e_product_basename
                ) as a inner join (
                    select a.i_bbm, a.i_product, a.i_color, a.n_quantity, a.e_remark from tm_notakredit_item a 
                    inner join tm_notakredit b on (a.i_kn = b.i_kn)
                    where a.i_kn = '$ikn'
                ) as b on (a.i_bbm = b.i_bbm and a.i_product = b.i_product and a.i_color = b.i_color)
            ", false);
    }

    public function updateheader($ikn, $customer, $nota, $datekn, $pkp, $gross, $ndiscount, $discount, $netto, $dpp, $ppn, $total, $remark) {
        $dentry = current_datetime();
        $data = array(
            'd_kn'            => $datekn,
            'i_customer'      => $customer,
            'i_nota'          => $nota,
            'pkp'             => $pkp,
            'e_remark'        => $remark,
            'v_gross'         => $gross,
            'n_discount'      => $ndiscount,
            'v_discount'      => $discount,
            'v_netto'         => $netto,
            'v_dpp'           => $dpp,
            'v_ppn'           => $ppn,
            'v_total'         => $total,
            'v_sisa'          => $total,
            'd_update'        => $dentry,
        );
        $this->db->where('i_kn',$ikn);
        $this->db->update('tm_notakredit', $data);
    }

    function deletedetail($ikn) {
         $this->db->query("DELETE FROM tm_notakredit_item WHERE i_kn='$ikn'");
    }

    public function change($kode){
      $data = array(
          'i_status_dokumen'    => '3'
      );

      $this->db->where('i_kn', $kode);
      $this->db->update('tm_notakredit', $data);
    }

    public function reject($kode){
      $data = array(
          'i_status_dokumen'    => '4'
      );

    $this->db->where('i_kn', $kode);
      $this->db->update('tm_notakredit', $data);
    }

    public function approve($ikn){
      $now = date("Y-m-d");
      $data = array(
            'i_status_dokumen'    => '6',
            'd_approve' => $now
      );

      $this->db->where('i_kn', $ikn);
      $this->db->update('tm_notakredit', $data);
    }

    function update_bbm($i_bbm, $i_product, $i_color,$qty) {
        $this->db->query("
            update tm_bbmretur_item set n_quantity_sisa = n_quantity_sisa - $qty where i_bbm = '$i_bbm' and i_product = '$i_product' and i_color = '$i_color'
        ");
    }









    public function getdetailview($isj, $partner, $nota){
          return $this->db->query("
                select a.i_sj_masuk as i_sj, a.i_product as i_wip, e_product_basename as e_namabrg, a.i_color, a.n_quantity as qty_sudahnota, c.e_color_name, a.v_price from tm_notamakloonpacking_item a 
                    inner join tr_product_base b on (a.i_product = b.i_product_motif)
                    inner join tr_color c on (a.i_color = c.i_color)
                    where a.i_nota = '$nota' and a.partner = '$partner'
                    order by a.i_sj_masuk, a.i_product
            ", false);
    }

    public function cancel($kode, $partner){
        $data = array(
             'i_status_dokumen'    => '9',
        );
        
       $this->db->where('i_kn', $kode);
       $this->db->update('tm_notakredit', $data);
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
