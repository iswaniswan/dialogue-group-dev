<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($i_menu,$folder,$status,$dfrom,$dto)
    {
        if ($status =='all' || $status=='') {
            $fstatus  = "";
        }else{
            $fstatus = "AND f_status_aktif = '$status' ";            
        }
        if ($dfrom!='') {
            $dfrom    = date('Y-m-d', strtotime($dfrom));
            $datefrom = "AND a.d_mulai >= '$dfrom' ";
        }else{
            $datefrom = "";
        }
        if ($dto!='') {
            $dto    = date('Y-m-d', strtotime($dto));
            if ($dfrom!=date('Y-m-d')) {
                $dateto = "AND a.d_berlaku <= '$dto' ";
            }else{
                $dateto = "";
            }
        }else{
            $dateto = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);

        $datatables->query(
            "SELECT
                a.i_price,
                a.i_product_motif,
                b.e_product_basename,
                a.v_price,
                to_char(a.d_mulai, 'dd-mm-yyyy') AS d_mulai,
                to_char(a.d_berlaku, 'dd-mm-yyyy') AS d_berlaku,
                CASE
                    WHEN a.f_status_aktif = 't' THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                '$i_menu' AS i_menu,
                '$folder' AS folder
            FROM
                tm_price_barangjadi a
            INNER JOIN tr_product_base b ON
                (a.i_product_motif = b.i_product_motif
                AND a.i_color = b.i_color)
            INNER JOIN tr_color c ON
                (a.i_color = c.i_color)
            WHERE 
                i_product_grade = 'B'
                $fstatus
                $datefrom
                $dateto
            ORDER BY
                b.e_product_basename",
            false
        );

        $datatables->edit(
            'v_price',
            function ($data) {
                $data = "Rp. " . number_format($data['v_price']);
                return $data;
            }
        );

        $datatables->edit(
            'status', 
            function ($data) {
                $id         = trim($data['i_price']);
                $folder     = $data['folder'];
                $id_menu    = $data['i_menu'];
                $status     = $data['status'];
                if ($status=='Aktif') {
                    $warna = 'success';
                }else{
                    $warna = 'danger';
                }
                $data    = '';
                if(check_role($id_menu, 3)){
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

        $datatables->add(
            'action',
            function ($data) {
                $i_price = trim($data['i_price']);
                $i_menu  = $data['i_menu'];
                $folder  = $data['folder'];
                $data    = '';
                if (check_role($i_menu, 2)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$i_price/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
                }
                if (check_role($i_menu, 3)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_price/\",\"#main\"); return false;'><i class='ti-pencil'></i></a>";
                }
                return $data;
            }
        );
        $datatables->hide('i_price');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function status($id){
        $this->db->select('*');
        $this->db->from('tm_price_barangjadi');
        $this->db->where('i_price', $id);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row    = $query->row();
            $status = $row->f_status_aktif;
            if ($status=='t') {
                $stat = 'f';
            }else{
                $stat = 't';
            }
        }
        $data = array(
            'f_status_aktif' => $stat 
        );
        $this->db->where('i_price', $id);
        $this->db->update('tm_price_barangjadi', $data);
    }

    public function get_kodekelompok()
    {
        return $this->db->query(
            "SELECT
                i_kode_kelompok,
                e_nama
            FROM
                tm_kelompok_barang
            WHERE
                i_kode_group_barang = 'GRB0003'
            ORDER BY
                e_nama",
            FALSE
        );
    }

    public function getjenis($cari,$ikodekelompok)
    {
        $cari = str_replace("'", "", $cari);
        if ($ikodekelompok != 'semua' && $ikodekelompok!='') {
            $where = "AND i_kode_kelompok = '$ikodekelompok'";
        } else {
            $where = '';
        }

        return $this->db->query(
            "SELECT
                i_type_code AS id,
                e_type_name AS name
            FROM
                tr_item_type
            WHERE
                i_kode_group_barang = 'GRB0003'
                AND UPPER(e_type_name) LIKE '%$cari%'
                $where
            ORDER BY
                e_type_name",
            FALSE
        );
    }

    public function getproduct($cari,$ikodekelompok,$ikodejenis)
    {
        $cari = str_replace("'", "", $cari);
        if ($ikodejenis !='semua' && $ikodejenis !='') {
            $where = "AND i_type_code = '$ikodejenis'";
        }else{
            $where = "";
        }

        if ($ikodekelompok != 'semua' && $ikodekelompok != '') {
            $and = "AND i_kode_kelompok = '$ikodekelompok'";
        }else{
            $and = "";
        }

        return $this->db->query(
            "
            SELECT
                i_product_motif AS id,
                e_product_basename AS name
            FROM
                tr_product_base
            WHERE
                i_product_motif <> ''
                AND (UPPER(e_product_basename) LIKE '%$cari%'
                OR UPPER(i_product_motif) LIKE '%$cari%')
                $where
                $and
            ORDER BY
                e_product_basename
            ",
            FALSE
        );
    }

    public function cek_kelompok($ikelompok)
    {
        if ($ikelompok!='' && $ikelompok!='semua') {
            $and = "AND i_kode_kelompok = '$ikelompok' ";
        }else{
            $and = "";
        }
        return $this->db->query(
            "SELECT
                i_kode_kelompok,
                e_nama
            FROM
                tm_kelompok_barang
            WHERE
                i_kode_group_barang = 'GRB0003'
                $and
            ORDER BY
                e_nama",
            FALSE
        );
    }

    public function cek_jenis($jenis)
    {
        if ($jenis != 'semua' && $jenis!='') {
            $where = "AND i_type_code = '$jenis'";
        } else {
            $where = '';
        }

        return $this->db->query(
            "SELECT
                i_type_code AS id,
                e_type_name AS name
            FROM
                tr_item_type
            WHERE
                i_kode_group_barang = 'GRB0003'
                $where
            ORDER BY
                e_type_name",
            FALSE
        );
    }

    public function cek_product($product)
    {
        if ($product !='semua' && $product !='') {
            $where = "AND i_product_motif = '$product'";
        }else{
            $where = "";
        }

        return $this->db->query(
            "
            SELECT
                i_product_motif AS id,
                e_product_basename AS name
            FROM
                tr_product_base
            WHERE
                i_product_motif <> ''
                $where
            ORDER BY
                e_product_basename
            ",
            FALSE
        );
    }

    public function get_hargas($ikodekelompok, $ikodejenis, $iproduct)
    { 
        if ($ikodejenis !='semua' && $ikodejenis !='') {
            $where = "AND a.i_type_code = '$ikodejenis'";
        }else{
            $where = "";
        }

        if ($ikodekelompok != 'semua' && $ikodekelompok != '') {
            $and = "AND a.i_kode_kelompok = '$ikodekelompok'";
        }else{
            $and = "";
        }

        if ($iproduct != 'semua' && $iproduct != '') {
            $end = "AND a.i_product_motif = '$iproduct'";
        }else{
            $end = "";
        }

        return $this->db->query(
            "
            SELECT
                a.i_product_motif,
                a.e_product_basename,
                a.i_color,
                b.e_color_name ,
                to_char(c.d_berlaku, 'dd-mm-yyyy') AS d_berlaku_old,
                to_char(c.d_mulai, 'dd-mm-yyyy') AS d_mulai
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color)
            LEFT JOIN (
                SELECT
                    max(d_berlaku) AS d_berlaku, i_product_motif, i_color, max(d_mulai) AS d_mulai
                FROM
                    tm_price_barangjadi
                WHERE
                    f_status_aktif = 't'
                GROUP BY
                    i_product_motif, i_color ) AS c ON
                (a.i_product_motif = c.i_product_motif
                AND a.i_color = c.i_color)
            WHERE
                a.f_status_product = 't'
                $where
                $and
                $end
            ORDER BY
                e_product_basename
            ",
            FALSE
        );
    }

    public function insert($kodebrg, $icolor, $harga, $dateberlaku)
    {
        $dentry = date("Y-m-d");
        $data = array(
            'i_product_motif'     => $kodebrg,
            'i_color'             => $icolor,
            'v_price'             => $harga,
            'd_mulai'             => $dateberlaku,
            'd_entry'             => $dentry,
            'i_product_grade'     => 'B',
        );
        $this->db->insert('tm_price_barangjadi', $data);
    }

    public function cek_data($i_price)
    {
        return $this->db->query(
            "SELECT
                a.i_price,
                a.i_product_motif,
                b.e_product_basename,
                a.i_color,
                c.e_color_name,
                a.v_price,
                to_char(a.d_berlaku, 'dd-mm-yyyy') AS d_berlaku,
                to_char(a.d_mulai, 'dd-mm-yyyy') AS d_mulai,
                a.f_status_aktif,
                d.e_nama AS kelompok,
                e.e_type_name AS TYPE,
                to_char(f.d_berlaku, 'dd-mm-yyyy') AS d_berlaku_old
            FROM
                tm_price_barangjadi a
            INNER JOIN tr_product_base b ON
                (a.i_product_motif = b.i_product_motif
                AND a.i_color = b.i_color)
            INNER JOIN tr_color c ON
                (a.i_color = c.i_color)
            INNER JOIN tm_kelompok_barang d ON
                (b.i_kode_kelompok = d.i_kode_kelompok)
            INNER JOIN tr_item_type e ON
                (b.i_type_code = e.i_type_code)
            LEFT JOIN (
                SELECT
                    max(d_berlaku) AS d_berlaku, max(d_mulai) AS d_mulai, i_product_motif, i_color
                FROM
                    tm_price_barangjadi
                WHERE
                    f_status_aktif = 't'
                GROUP BY
                    i_product_motif, i_color ) AS f ON
                (a.i_product_motif = f.i_product_motif
                AND a.i_color = f.i_color)
            WHERE
                a.i_price = '$i_price' ",
            false
        );
    }

    public function update($i_price, $kodebrg, $harga, $dateberlaku, $aktif)
    {
        $dupdate = date("Y-m-d");
        $data = array(
            'v_price'           => $harga,
            'd_berlaku'         => $dateberlaku,
            'f_status_aktif'    => $aktif,
            'd_update'          => $dupdate,
        );
        $this->db->where('i_price', $i_price);
        $this->db->update('tm_price_barangjadi', $data);
    }
}
/* End of file Mmaster.php */