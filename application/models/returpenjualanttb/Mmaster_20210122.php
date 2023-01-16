<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($folder,$i_menu,$dfrom,$dto, $i_lokasi){
        // $dfrom = date('Y-m-d', strtotime($dfrom));
        // $dto   = date('Y-m-d', strtotime($dto));
        $user   = $this->session->userdata('username');
        $where = '';

        if ($user !='admin') {
            $where = "and a.i_kode_lokasi = '$i_lokasi'";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            select a.i_bbm, to_char(a.d_bbm, 'dd-mm-yyyy') as d_bbm, a.i_customer, b.e_customer_name, a.i_ttb, a.e_remark,  
            a.i_status_dokumen, c.e_status, i_kode_lokasi, '$i_menu' as i_menu, '$folder' as folder
            from tm_bbmretur a
            inner join tr_customer b on (a.i_customer = b.i_customer)
            inner join tm_status_dokumen c on (a.i_status_dokumen = c.i_status)
            where a.d_bbm between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') $where
            order by a.i_bbm
        ", FALSE);

        // $datatables->edit('i_status', function ($data) {
        //     return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        // });
          $datatables->edit('i_bbm', function ($data) {
            if ($data['i_status_dokumen']=='9') {
                $data = '<p class="h2 text-danger">'.$data['i_bbm'].'</p>';
            }else{
                $data = $data['i_bbm'];
            }
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
            $i_bbm        = trim($data['i_bbm']);
            $i_customer       = trim($data['i_customer']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $i_departement   = trim($this->session->userdata('i_departement'));
            $i_level         = trim($this->session->userdata('i_level'));
            $i_lokasi       = $this->session->userdata('i_lokasi');
            $data_lokasi      = $data['i_kode_lokasi'];
            $i_status       = $data['i_status_dokumen'];

            $data          = '';
            $data         .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$i_bbm/$i_customer\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            //$data         .= "<a href=\"#\" title='Print' onclick='printx(\"$id\",\"$ibagian\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
            if ($i_status != '6') {
                if (check_role($i_menu, 3)) {
                    if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                        $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$i_bbm/$i_customer\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                        // $data         .= "<a href=\"#\" title='Batal Kirim' onclick='batalkirim(\"$id\",\"$ibagian\",\"1\"); return false;'><i class='ti-reload'></i></a>&nbsp;&nbsp;&nbsp;";
                        // $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    if ((($i_departement == '19' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {    
                        $data         .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$i_bbm/$i_customer\",\"#main\"); return false;'><i class='fa fa-check-square '></i></a>&nbsp;&nbsp;&nbsp;";
                    }    

                }
                if (check_role($i_menu, 4)) {
                    if ($i_status != '9') {
                        $data .= "<a href=\"#\" title='Cancel' onclick='cancel(\"$i_bbm\",\"$i_customer\"); return false;'><i class='ti-close'></i></a>";
                    }
                }
            }
            return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_customer');
        $datatables->hide('i_kode_lokasi');
        $datatables->hide('i_status_dokumen');
        return $datatables->generate();
    }
    
    public function gudangjadi()
    {
        $i_apps   = $this->session->userdata('i_apps');
        $i_lokasi   = $this->session->userdata('i_lokasi');
        $user   = $this->session->userdata('username');
        $where = '';

        if ($user !='admin') {
            $where = "and i_kode_lokasi = '$i_lokasi'";
        }

        return $this->db->query("
            select * from public.tr_lokasi where i_apps = '$i_apps' and e_lokasi_name ilike '%gudang jadi%' $where
        ", FALSE);
    }

    public function customer() {
        return $this->db->query("select i_customer, e_customer_name from tr_customer order by e_customer_name asc", FALSE);
    }

    public function nottb($cari,$customer) {
        $cari = str_replace("'", "", $cari);
        return $this->db->query(" 
        select * from (
            select a.i_ttb, to_char(a.d_ttb, 'dd-mm-yyyy') as d_ttb, sum(n_quantity_retursisa) as sisa
            from tm_ttbretur a inner join tm_ttbretur_item b on (a.i_ttb = b.i_ttb) 
            where i_customer = '$customer' and i_status = '6'
            group by a.i_ttb, a.d_ttb
        ) as x where sisa > 0 and i_ttb ilike '%$cari%' order by d_ttb
        ", FALSE);
    }

    // public function gethead($sj, $partner){
    //     return $this->db->query("
    //       select n_discount, pkp from tm_sj_keluarpacking where i_sj = '$sj' and i_unit_packing = '$partner'
    //     ", false);
    // }

    public function getdetail($ttb, $customer){
          return $this->db->query("
               select a.i_ttb, a.i_product, a.i_color, a.n_quantity_retursisa as qty_permintaan, b.e_product_basename, c.e_color_name 
                from tm_ttbretur_item a
                left join tr_product_base b on (b.i_product_motif = a.i_product)
                left join tr_color c on (c.i_color = a.i_color)
                where a.i_ttb = '$ttb'
               order by e_product_basename
            ", false);
    }

    public function runningnumber($thbl,$lok){
        $th = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $query = $this->db->query("
            SELECT
                n_modul_no AS max
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'BBM'
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
                    i_modul = 'BBM'
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
            $nobonmk  ="BBM-".$lok."-".$thbl."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="00001";
            $nobonmk  ="BBM-".$lok."-".$thbl."-".$nobonmk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('BBM', '$lok', '$asal', 1)
            ");
            return $nobonmk;
        }
    }

    public function insertheader($ibbm, $ikodelokasi,$customer,$ttb,$datebbm,$eremark){
        $dentry = current_datetime();
        $data = array(
            'i_bbm'           => $ibbm,
            'i_kode_lokasi'   => $ikodelokasi,
            'i_customer'      => $customer,
            'i_ttb'           => $ttb,
            'd_bbm'           => $datebbm,
            'e_remark'        => $eremark,
            'd_entry'         => $dentry,
        );
        $this->db->insert('tm_bbmretur', $data);
    }

    public function insertdetail($ibbm, $ikodelokasi,$i_product,$i_color,$qty_retur,$edesc, $x) {
        $data = array(
            'i_bbm'           => $ibbm,
            'i_kode_lokasi'   => $ikodelokasi,
            'i_product'       => $i_product,
            'i_color'         => $i_color,
            'n_quantity'      => $qty_retur,
            'n_quantity_sisa' => $qty_retur,
            'e_remark'        => $edesc,
            'i_no_item'       => $x,
        );
        $this->db->insert('tm_bbmretur_item', $data);
    }

    public function send($kode){
      $data = array(
          'i_status_dokumen'    => '2'
      );

      $this->db->where('i_bbm', $kode);
      $this->db->update('tm_bbmretur', $data);
    }

    public function baca($i_bbm,$icustomer){
        $query = $this->db->query("
            select a.i_bbm, to_char(a.d_bbm, 'dd-mm-yyyy') as d_bbm, a.i_customer, b.e_customer_name, a.i_ttb, a.e_remark,  
            a.i_status_dokumen, c.e_status, i_kode_lokasi
            from tm_bbmretur a
            inner join tr_customer b on (a.i_customer = b.i_customer)
            inner join tm_status_dokumen c on (a.i_status_dokumen = c.i_status)
            where a.i_bbm = '$i_bbm' and a.i_customer = '$icustomer'
        ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function getdetailedit($ibbm, $ttb, $customer){
          return $this->db->query("
                select b.i_bbm, a.i_ttb, b.i_product, b.i_color, b.n_quantity as qty_retur, c.qty_permintaan, c.e_product_basename, c.e_color_name, b.e_remark from tm_bbmretur a
                inner join tm_bbmretur_item b on (a.i_bbm = b.i_bbm)
                inner join (
                    select a.i_ttb, a.i_product, a.i_color, a.n_quantity_retursisa as qty_permintaan, b.e_product_basename, c.e_color_name 
                                from tm_ttbretur_item a
                                left join tr_product_base b on (b.i_product_motif = a.i_product)
                                left join tr_color c on (c.i_color = a.i_color)
                                where a.i_ttb = '$ttb'            
                ) as c on (a.i_ttb = c.i_ttb and b.i_product = c.i_product and c.i_color = b.i_color)
                where b.i_bbm = '$ibbm'
                order by e_product_basename
            ", false);
    }

    public function updateheader($ibbm, $ikodelokasi,$customer,$ttb,$datebbm,$eremark) {
        $dentry = current_datetime();
        $data = array(
            'i_kode_lokasi'   => $ikodelokasi,
            'i_customer'      => $customer,
            'i_ttb'           => $ttb,
            'd_bbm'           => $datebbm,
            'e_remark'        => $eremark,
            'd_update'        => $dentry,
        );
        $this->db->where('i_bbm',$ibbm);
        $this->db->update('tm_bbmretur', $data);
    }

    function deletedetail($ibbm) {
         $this->db->query("DELETE FROM tm_bbmretur_item WHERE i_bbm = '$ibbm'");
    }

    public function change($kode){
      $data = array(
          'i_status_dokumen'    => '3'
      );

      $this->db->where('i_bbm', $kode);
      $this->db->update('tm_bbmretur', $data);
    }

    public function reject($kode){
      $data = array(
          'i_status_dokumen'    => '4'
      );

      $this->db->where('i_bbm', $kode);
      $this->db->update('tm_bbmretur', $data);
    }


    public function approve($ibbm){
      $now = date("Y-m-d");
      $data = array(
          'i_status_dokumen'    => '6',
          'd_approve' => $now
      );

      $this->db->where('i_bbm', $ibbm);
      $this->db->update('tm_bbmretur', $data);
    }

    function update_ic($i_product, $ikodelokasi, $i_color,$qty_retur,$grade) {
        $this->db->query("
            INSERT INTO tm_ic(i_product, i_product_grade, i_kode_lokasi, f_product_active, i_color, n_quantity_stock ) 
            VALUES ('$i_product','$grade', '$ikodelokasi', 't', '$i_color','$qty_retur')
            ON CONFLICT (i_product, i_product_grade, i_kode_lokasi, i_color) DO UPDATE 
              SET n_quantity_stock = tm_ic.n_quantity_stock + excluded.n_quantity_stock;
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

    public function cancel($ibbm, $partner){
        $data = array(
          'i_status_dokumen'    => '9',
      );

      $this->db->where('i_bbm', $ibbm);
      $this->db->update('tm_bbmretur', $data);
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
