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
            select a.i_memo, to_char(a.d_memo, 'dd-mm-yyyy') as d_memo, a.i_kode_lokasi, l.e_lokasi_name, to_char(a.d_back, 'dd-mm-yyyy') as d_back, c.e_tujuan_name,
             d.nama as dep, e.e_nama_karyawan as pic, a.pic_eks,a.e_remark,  b.qty, a.f_cancel , '' as status, '$i_menu' as i_menu, '$folder' as folder,  a.pembuat
            from tm_permintaanpengeluarangdjadi a
            inner join (
                select i_memo, i_kode_lokasi, sum(n_qty-n_qty_sisa) as qty from tm_permintaanpengeluarangdjadi_detail group by i_memo, i_kode_lokasi 
            ) as b on (a.i_memo = b.i_memo and a.i_kode_lokasi = b.i_kode_lokasi)
            inner join public.tr_lokasi l on (a.i_kode_lokasi = l.i_kode_lokasi)
            INNER JOIN tr_tujuan c ON (a.i_tujuan_keluar = c.i_tujuan)
            left join (
                SELECT i_departement AS id, e_departement_name AS nama FROM public.tr_departement
                UNION ALL
                SELECT i_karyawan AS id, e_nama_karyawan AS nama FROM tm_karyawan
                UNION ALL
                SELECT i_supplier AS id, e_supplier_name AS nama FROM tr_supplier
            ) as d on (a.i_departement = d.id)
            LEFT JOIN tm_karyawan e ON (e.i_karyawan = a.pic)
            /*where a.d_memo between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy')*/
        ", FALSE);

        // $datatables->edit('i_status', function ($data) {
        //     return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        // });
          $datatables->edit('i_memo', function ($data) {
            if ($data['f_cancel']=='t') {
                $data = '<p class="h2 text-danger">'.$data['i_memo'].'</p>';
            }else{
                $data = $data['i_memo'];
            }
            return $data;
          });

          $datatables->edit('status', function ($data) {
          $f_cancel = trim($data['f_cancel']);
          $qty           = trim($data['qty']);
          if($qty <> '0'){
            return  "Sudah Di Proses Oleh Gudang";
          }else if($f_cancel == 'f'){
            return "Menunggu Proses Gudang";
          } else if($f_cancel == 't') {
            return "Di Batalkan";
          }
      });

        $datatables->add('action', function ($data) {
            $i_memo        = trim($data['i_memo']);
            $i_kode_lokasi = trim($data['i_kode_lokasi']);
            $pembuat       = trim($data['pembuat']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $username      = trim($this->session->userdata('username'));
            $f_cancel      = trim($data['f_cancel']);
            $qty           = trim($data['qty']);
            // $idepartemen   = trim($this->session->userdata('i_departement'));
            // $ilevel        = trim($this->session->userdata('i_level'));
            $data          = '';
            $data         .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$i_memo/$i_kode_lokasi\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            //$data         .= "<a href=\"#\" title='Print' onclick='printx(\"$id\",\"$ibagian\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
            if ($qty == "0" && $f_cancel == 'f') {
                if (check_role($i_menu, 3)) {
                        $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$i_memo/$i_kode_lokasi\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                        // $data         .= "<a href=\"#\" title='Batal Kirim' onclick='batalkirim(\"$id\",\"$ibagian\",\"1\"); return false;'><i class='ti-reload'></i></a>&nbsp;&nbsp;&nbsp;";
                        // $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
                if (check_role($i_menu, 4)) {
                    $data .= "<a href=\"#\" title='Cancel' onclick='cancel(\"$i_memo\",\"$i_kode_lokasi\"); return false;'><i class='ti-close'></i></a>";
                }
            }
            return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('pembuat');
        $datatables->hide('i_kode_lokasi');
        $datatables->hide('f_cancel');
        $datatables->hide('qty');
        return $datatables->generate();
    }
    
    public function gudang()
    {
        $dep = $this->session->userdata('i_departement');
        return $this->db->query("
            SELECT * FROM public.tr_departement where i_departement = '$dep'
        ", FALSE);
    }

    public function gudangjadi()
    {
        $i_apps   = $this->session->userdata('i_apps');
        return $this->db->query("
            select * from public.tr_lokasi where i_apps = '$i_apps' and e_lokasi_name ilike '%gudang jadi%'
        ", FALSE);
    }

    public function tujuan()
    {
        return $this->db->query("
            SELECT
                *
            FROM tr_tujuan
        ", FALSE);
    }

    public function departemen($cari,$itujuan)
    {
        $dep = $this->session->userdata('i_departement');
        $cari = str_replace("'", "", $cari);
        if (trim($itujuan)=='1') {
            return $this->db->query("
                SELECT
                    *
                FROM
                    (
                    SELECT
                        i_departement AS id, e_departement_name AS name
                    FROM
                        public.tr_departement where i_departement <> '$dep'
                UNION ALL
                    SELECT
                        i_karyawan AS id, e_nama_karyawan AS name
                    FROM
                        tm_karyawan) AS x
                WHERE
                    (UPPER(name) LIKE '%$cari%')
                ORDER BY name
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    i_supplier AS id,
                    e_supplier_name AS name
                FROM
                    tr_supplier
                WHERE
                    (UPPER(e_supplier_name) LIKE '%$cari%')
                ORDER BY name
            ", FALSE);
        }
    }

    public function ppic($cari)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_karyawan AS id, 
                e_nama_karyawan AS name
            FROM tm_karyawan
            WHERE (UPPER(e_nama_karyawan) LIKE '%$cari%')
            ORDER BY e_nama_karyawan ASC
        ", FALSE);
    }

    public function product($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("            
            SELECT
                a.i_product_motif,
                a.e_product_basename,
                a.i_color,
                b.e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color)
            WHERE (i_product_motif ILIKE '%$cari%' OR e_product_basename iLIKE '%$cari%') order by a.e_product_basename /*AND f_status_product = 't'*/
        ", FALSE);
    }

    public function getproduct($iproduct, $icolor, $ikodelokasi){
        return $this->db->query("            
            SELECT i_product_motif, e_product_basename, i_color, e_color_name, qty_ic - qty_sisa as qty_reserved from (
                SELECT
                i_product_motif,
                e_product_basename,
                a.i_color,
                e_color_name,
                case when x.n_qty is null then 0 else x.n_qty end as qty_ic,
                case when y.n_qty_sisa is null then 0 else y.n_qty_sisa end as qty_sisa
                FROM
                tr_product_base a
                INNER JOIN tr_color b ON (b.i_color = a.i_color)
                left join (
                    select i_product, i_kode_lokasi, i_color, sum(n_quantity_stock) as n_qty from tm_ic 
                    where i_kode_lokasi = '$ikodelokasi' and i_product = '$iproduct' and i_color = '$icolor'
                    group by i_product, i_kode_lokasi, i_color
                ) as x on (x.i_product = a.i_product_motif and x.i_color = a.i_color)
                left join (
                    select  b.i_product, b.i_color, sum(n_qty_sisa) as n_qty_sisa from tm_permintaanpengeluarangdjadi a
                    inner join tm_permintaanpengeluarangdjadi_detail b on (a.i_memo = b.i_memo and b.i_kode_lokasi = a.i_kode_lokasi)
                    where a.f_cancel = 'f' and b.i_product = '$iproduct' and b.i_color = '$icolor'
                    group by b.i_product, b.i_color
                ) as y on (y.i_product = a.i_product_motif and y.i_color = a.i_color)
                WHERE a.i_product_motif = '$iproduct' and a.i_color = '$icolor'
            ) as final
        ", FALSE);
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
                i_modul = 'MGDJ'
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
                    i_modul = 'MGDJ'
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
            $nobonmk  ="MGDJ-".$lok."-".$thbl."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="00001";
            $nobonmk  ="MGDJ-".$lok."-".$thbl."-".$nobonmk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('MGDJ', '$lok', '$asal', 1)
            ");
            return $nobonmk;
        }
    }

    public function insertheader($imemo, $ikodemaster,$datebonk,$tujuankeluar, $dept, $ikodelokasi,$dateback,$pic,$epic,$remark)
    {
        $dentry = current_datetime();
        $data = array(
            'i_memo'          => $imemo,
            'd_memo'          => $datebonk,
            'pembuat'         => $ikodemaster,
            'i_kode_lokasi'   => $ikodelokasi,
            'i_tujuan_keluar' => $tujuankeluar,
            'pic'             => $pic,
            'i_departement'   => $dept,
            'e_remark'        => $remark,
            'd_entry'         => $dentry,
            'pic_eks'         => $epic,
            'd_back'          => $dateback,
        );
        $this->db->insert('tm_permintaanpengeluarangdjadi', $data);
    }

    public function baca($imemo,$ikodelokasi){
        $query = $this->db->query("
            select a.i_memo, to_char(a.d_memo, 'dd-mm-yyyy') as d_memo, a.i_kode_lokasi, l.e_lokasi_name, to_char(a.d_back, 'dd-mm-yyyy') as d_back, a.i_tujuan_keluar, 
            c.e_tujuan_name, a.pic, a.i_departement,
             d.nama as dep, e.e_nama_karyawan, a.pic_eks,a.e_remark
            from tm_permintaanpengeluarangdjadi a
            inner join public.tr_lokasi l on (a.i_kode_lokasi = l.i_kode_lokasi)
            INNER JOIN tr_tujuan c ON (a.i_tujuan_keluar = c.i_tujuan)
            left join (
               SELECT i_departement AS id, e_departement_name AS nama FROM public.tr_departement
                UNION ALL
                SELECT i_karyawan AS id, e_nama_karyawan AS nama FROM tm_karyawan
                UNION ALL
                SELECT i_supplier AS id, e_supplier_name AS nama FROM tr_supplier
                ORDER BY nama
            ) as d on (a.i_departement = d.id)
            LEFT JOIN tm_karyawan e ON (e.i_karyawan = a.pic)
            where a.i_memo = '$imemo' and a.i_kode_lokasi = '$ikodelokasi'
        ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function bacadetail($imemo,$ikodelokasi){
        $query = $this->db->query("
            select i_product_motif, e_product_basename, i_color, e_color_name, qty_ic - qty_sisa as qty_reserved, n_qty, e_remark,sisa_now
            from (
                SELECT
                a.i_product as i_product_motif,
                c.e_product_basename,
                a.i_color,
                b.e_color_name,
                case when x.n_qty is null then 0 else x.n_qty end as qty_ic,
                    case when y.n_qty_sisa is null then 0 else y.n_qty_sisa end as qty_sisa,
                    a.n_qty, a.e_remark, a.n_qty_sisa as sisa_now
                FROM
                tm_permintaanpengeluarangdjadi_detail a
                inner join tr_color b on (a.i_color = b.i_color)
                left join (
                    select i_product, i_kode_lokasi, i_color, sum(n_quantity_stock) as n_qty from tm_ic 
                    where i_kode_lokasi = '$ikodelokasi'
                    group by i_product, i_kode_lokasi, i_color
                ) as x on (x.i_product = a.i_product and x.i_color = a.i_color)
                left join (
                    select  b.i_product, b.i_color, sum(n_qty_sisa) as n_qty_sisa from tm_permintaanpengeluarangdjadi a
                    inner join tm_permintaanpengeluarangdjadi_detail b on (a.i_memo = b.i_memo and b.i_kode_lokasi = a.i_kode_lokasi)
                    where a.f_cancel = 'f' and b.i_memo <> '$imemo' 
                    group by b.i_product, b.i_color
                ) as y on (y.i_product = a.i_product and y.i_color = a.i_color)
                inner join tr_product_base c on (a.i_product = c.i_product_motif)
                WHERE 
                a.i_memo = '$imemo' 
                AND a.i_kode_lokasi = '$ikodelokasi'
                ORDER BY i_no_item
            ) as x
        ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cancelitem($ibonk,$icolor,$iproduct,$ibagian){
        $this->db->where('i_bonmk', $ibonk);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_color', $icolor);
        $this->db->where('i_kode_master', $ibagian);
        return $this->db->delete('tm_bonmkeluar_pinjamanbj_detail');
    }

    public function updatestatus($ibonk,$status,$ibagian){
        $dentry = current_datetime();
        if ($status=='6') {
            $data = array(
                'i_status' => $status,
                'd_approve' => $dentry,
            );
        }else{
            $data = array(
                'i_status' => $status,
            );
        }
        $this->db->where('i_bonmk', $ibonk);
        $this->db->where('i_kode_master', $ibagian);
        $this->db->update('tm_bonmkeluar_pinjamanbj', $data);
    }

    public function update($imemo, $dmemo,  $dback, $ikodelokasi, $tujuankeluar, $pic, $epic, $dept, $remark)
    {
        $dentry = current_datetime();
        $data = array(
            'd_memo'          => $dmemo,
            'i_tujuan_keluar' => $tujuankeluar,
            'pic'             => $pic,
            'i_departement'   => $dept,
            'e_remark'        => $remark,
            'd_update'        => $dentry,
            'pic_eks'         => $epic,
            'd_back'          => $dback,
        );;
        $this->db->where('i_memo',$imemo);
        $this->db->where('i_kode_lokasi',$ikodelokasi);
        $this->db->update('tm_permintaanpengeluarangdjadi', $data);
    }

    public function deletedetail($imemo,$ikodelokasi){   
        $this->db->where('i_memo',$imemo);
        $this->db->where('i_kode_lokasi',$ikodelokasi);
        $this->db->delete('tm_permintaanpengeluarangdjadi_detail');
    }

    public function insertdetail($imemo,$ikodelokasi,$iproduct,$icolor,$nquantity,$edesc,$x)
    {
        $data = array(
            'i_memo'        => $imemo,
            'i_kode_lokasi' => $ikodelokasi,
            'i_product'     => $iproduct,
            'i_color'       => $icolor,
            'n_qty'         => $nquantity,
            'n_qty_sisa'    => $nquantity,
            'e_remark'      => $edesc,
            'i_no_item'     => $x,
        );
        $this->db->insert('tm_permintaanpengeluarangdjadi_detail', $data);
    }

    public function cancel($imemo, $ikodelokasi){
        $data = array(
            'f_cancel' => 't',
        );
        $this->db->where('i_memo',$imemo);
        $this->db->where('i_kode_lokasi',$ikodelokasi);
        $this->db->update('tm_permintaanpengeluarangdjadi', $data);
    }
}

/* End of file Mmaster.php */
