<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder)
    {
        $datatables = new Datatables(new CodeigniterAdapter);

        $idcompany = $this->session->userdata('id_company');
        $datatables->query(
            "SELECT 
                0 as no,
                a.id,
                a.i_product_base,
                a.e_product_basename,
                e.e_color_name,
                a.i_kode_group_barang,
                b.e_nama_group_barang,
                a.i_kode_kelompok,
                c.e_nama_kelompok,
                a.i_type_code,
                d.e_type_name,
                case
                    when
                        a.f_status = TRUE 
                    then
                        'Aktif' 
                    else
                        'Tidak Aktif' 
                end
                as status, 
                '$i_menu' as i_menu, 
                '$folder' as folder , a.d_entry, a.d_update
            FROM
                tr_product_base a 
            INNER JOIN
                tr_group_barang b 
                ON a.i_kode_group_barang = b.i_kode_group_barang
                AND a.id_company = b.id_company
            INNER JOIN
                tr_kelompok_barang c 
                ON a.i_kode_kelompok = c.i_kode_kelompok 
                AND a.id_company = c.id_company
            INNER JOIN
                tr_item_type d 
                ON a.i_type_code = d.i_type_code
                AND a.id_company = d.id_company
            inner join tr_color e on (a.i_color = e.i_color AND a.id_company = e.id_company)
            WHERE 
                a.id_company = '$idcompany'
            ORDER BY a.d_entry desc nulls last, a.d_update desc nulls last
            ",
            FALSE
        );
        $datatables->edit(
            'status',
            function ($data) {
                $id         = trim($data['id']);
                $folder     = $data['folder'];
                $id_menu    = $data['i_menu'];
                $status     = $data['status'];
                if ($status == 'Aktif') {
                    $warna = 'success';
                } else {
                    $warna = 'danger';
                }
                $data    = '';
                if (check_role($id_menu, 3)) {
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                } else {
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

        $datatables->add('action', function ($data) {
            $iproductbase  = trim($data['i_product_base']);
            $id            = trim($data['id']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$iproductbase/$id/\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$iproductbase/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg'></i></a>";
            }
            // if(check_role($i_menu, 4)){
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iproductbase\"); return false;'><i class='fa fa-trash'></i></a>";
            // }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_kode_group_barang');
        $datatables->hide('i_kode_kelompok');
        $datatables->hide('i_type_code');
        $datatables->hide('id');
        $datatables->hide('d_entry');
        $datatables->hide('d_update');
        return $datatables->generate();
    }

    public function status($id)
    {
        $this->db->select('f_status');
        $this->db->from('tr_product_base');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row    = $query->row();
            $status = $row->f_status;
            if ($status == 't') {
                $stat = 'f';
            } else {
                $stat = 't';
            }
        }
        $data = array(
            'f_status' => $stat
        );
        $this->db->where('id', $id);
        $this->db->update('tr_product_base', $data);
    }

    public function cekkode($kode)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT i_product_base FROM tr_product_base WHERE i_product_base ='$kode' and id_company=$idcompany", FALSE);
    }

    public function getdivisi($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT a.id, a.i_kode_divisi, a.e_nama_divisi FROM tr_divisi_new a WHERE (a.i_kode_divisi ILIKE '%$cari%' OR a.e_nama_divisi ILIKE '%$cari%') ORDER BY  a.i_kode_divisi ASC", FALSE);
    }

    public function getgroup($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_group_barang WHERE id_company = '$idcompany' AND i_kode_group_barang = 'GRB0003' AND f_status = 't' AND (i_kode_group_barang like '%$cari%' OR e_nama_group_barang like '%$cari%') ORDER BY i_kode_group_barang", FALSE);
    }

    public function getkelompok($igroupbrg, $idivisi)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_kelompok_barang WHERE id_company = '$idcompany' AND i_kode_group_barang='$igroupbrg' AND f_status = 't' AND id_divisi = '$idivisi' ORDER BY i_kode_kelompok", FALSE);
    }

    public function getkelompokedit($cari, $igroupbrg, $idivisi)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_kelompok_barang WHERE e_nama_kelompok ILIKE '%$cari%' AND id_company = '$idcompany' AND i_kode_group_barang='$igroupbrg' AND f_status = 't' AND id_divisi = '$idivisi' ORDER BY i_kode_kelompok", FALSE);
    }

    public function getjenis($ikelompok)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_item_type WHERE id_company='$idcompany' AND i_kode_kelompok = '$ikelompok' AND f_status = 't' ORDER BY i_type_code", FALSE);
    }

    public function getbrand($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_brand WHERE id_company='$idcompany' AND (i_brand like '%$cari%' OR e_brand_name like '%$cari%') AND f_status = 't' ORDER BY i_brand", FALSE);
    }


    public function getstyle($cari, $i_brand)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query(
            "SELECT * FROM tr_style WHERE id_company='$idcompany' 
            AND id_brand  IN (SELECT id FROM tr_brand WHERE id_company = '$this->id_company' AND i_brand = '$i_brand')
            AND (i_style like '%$cari%' OR e_style_name like '%$cari%') AND f_status = 't' ORDER BY i_style",
            FALSE
        );
    }

    public function getbarangwip($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT ON (i_product_wip) i_product_wip, i_color, e_product_wipname FROM tr_product_wip WHERE id_company='$idcompany' AND f_status = 't' AND (i_product_wip like '%$cari%' OR e_product_wipname like '%$cari%') ORDER BY i_product_wip", FALSE);
    }

    public function getwarnamotif($cari, $i_product_base)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM  tr_color WHERE id_company='$idcompany' AND f_status = 't' AND (i_color like '%$cari%' OR e_color_name like '%$cari%') AND i_color NOT IN (SELECT i_color FROM tr_product_base a WHERE a.i_product_base = '$i_product_base') ORDER BY i_color", FALSE);
    }

    public function getsatuanbarang($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_satuan WHERE id_company='$idcompany' AND f_status = 't' AND (i_satuan_code like '%$cari%' OR e_satuan_name like '%$cari%') ORDER BY i_satuan_code", FALSE);
    }

    public function getstatusproduksi($cari)
    {
        return $this->db->query("SELECT * FROM tr_status_produksi WHERE (i_status_produksi like '%$cari%' OR e_status_produksi like '%$cari%') ORDER BY i_status_produksi", FALSE);
    }

    public function getkodebarang($i_productwip)
    {
        $iperiode = $this->session->userdata('id_company');
        return $this->db->query("
                                SELECT 
                                	a.i_product_wip, 
                                	a.e_product_wipname,
                                	a.i_kode_group_barang,
                                	b.e_nama_group_barang,
                                	a.i_kode_kelompok, 
                                	c.e_nama_kelompok,
                                	a.i_type_code,
                                	d.e_type_name,
                                	a.i_satuan_code,
                                	e.e_satuan_name,
                                	a.i_status_produksi,
                                	f.e_status_produksi,
                                	a.i_color,
                                	g.e_color_name,
                                	a.i_style,
                                	h.e_style_name,
                                	a.i_brand,
                                    i.e_brand_name,
                                    a.n_panjang,
                                    a.n_lebar,
                                    a.n_tinggi,
                                    a.n_berat
                                FROM 
                                	tr_product_wip a
                                	INNER JOIN
                                		tr_group_barang b
                                		ON (a.i_kode_group_barang = b.i_kode_group_barang
                                		AND a.id_company = b.id_company)
                                	INNER JOIN 
                                		tr_kelompok_barang c
                                		ON (a.i_kode_kelompok = c.i_kode_kelompok 
                                		AND a.id_company = c.id_company) 
                                	INNER JOIN 
                                		tr_item_type d
                                		ON (a.i_type_code = d.i_type_code
                                		AND a.id_company = d.id_company)
                                	INNER JOIN 
                                		tr_satuan e
                                		ON (a.i_satuan_code = e.i_satuan_code
                                		AND a.id_company = e.id_company)
                                	INNER JOIN 
                                		tr_status_produksi f
                                		ON (a.i_status_produksi = f.i_status_produksi)
                                	INNER JOIN 
                                		tr_color g
                                		ON (a.i_color = g.i_color 
                                		AND a.id_company = g.id_company)
                                	INNER JOIN 
                                		tr_style h 
                                		ON (a.i_style = h.i_style
                                		AND a.id_company = h.id_company)
                                	INNER JOIN 
                                		tr_brand i
                                		ON (a.i_brand = i.i_brand
                                		AND a.id_company = i.id_company)
                                WHERE 
                                	a.i_product_wip='$i_productwip'
                                	AND a.id_company = '$iperiode'", FALSE);
    }

    public function insert($iproductbase, $eproductbasename, /* $i_productwip, $i_product_wip_color, */ $icolor, $isatuancode, $ikodegroupbarang, $ikodekelompok, $itypecode, $ibrand, $istyle, $istatusproduksi, $vhjp, $vgrosir, $esuratpenawaran, $dsuratpenawaran, $npanjang, $nlebar, $ntinggi, $isatuanukuran, $nberat, $isatuanberat, $eremark, $dproductregister, $iclass, $dlaunch, $dstp, $id_product_base)
    {
        // $iproductwip = explode('|', $this->input->post('iproductwip'));
        // $i_productwip = $iproductwip[0];
        // $i_product_wip_color = $iproductwip[1];

        // if($vgrosir == "" || $vgrosir == null){
        //     $vgrosir = 0;
        // }
        // if($vhjp == "" || $vhjp == null){
        //     $vhjp = 0;
        // }
        // $iproductbasenew = '';
        // if($iproductbase == "" || $iproductbase ==  null ){
        //     $wip = strlen($i_productwip);
        //     if($wip == '7'){
        //         $qwarna = $this->db->query("SELECT i_product_base FROM tr_product_base where i_product_wip='$i_productwip' ORDER BY i_product_base DESC LIMIT 1");
        //         if ($qwarna->num_rows() > 0) {
        //             $row_warna = $qwarna->row();
        //             $iproductbase2= $row_warna->i_product_base;
        //             $imotif= substr($iproductbase2, 8, 1);
        //             $imotif2= substr($iproductbase2, 0, 8);
        //             $ikodemotif=$imotif+1;
        //             $iproductbasenew =$imotif2.$ikodemotif;
        //         } else{
        //             $iproductbasenew = $i_productwip.'00';
        //         }
        //     }else{
        //        $iproductbasenew = $i_productwip; 
        //     }
        // }else{
        //     $iproductbasenew = $iproductbase;
        // }

        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->get_where("tr_product_base", [
            "id_company" => $this->id_company,
            "upper(i_product_base)" => strtoupper($iproductbase),
            "i_color" => $icolor,
        ]);

        if ($query->num_rows() <= 0) {

            $data = array(
                'i_product_base'      => strtoupper($iproductbase),
                'e_product_basename'  => strtoupper($eproductbasename),
                'i_product_wip'       => strtoupper($iproductbase),
                'i_color'             => $icolor,
                'i_satuan_code'       => $isatuancode,
                'i_kode_group_barang' => $ikodegroupbarang,
                'i_kode_kelompok'     => $ikodekelompok,
                'i_type_code'         => $itypecode,
                'i_brand'             => $ibrand,
                'i_style'             => $istyle,
                'i_status_produksi'   => $istatusproduksi,
                'v_unitprice'         => $vhjp,
                'v_grosir'            => $vgrosir,
                'e_surat_penawaran'   => $esuratpenawaran,
                'd_surat_penawaran'   => $dsuratpenawaran,
                'n_panjang'           => $npanjang,
                'n_lebar'             => $nlebar,
                'n_tinggi'            => $ntinggi,
                'i_satuan_ukuran'     => $isatuanukuran,
                'n_berat'             => $nberat,
                'i_satuan_berat'      => $isatuanberat,
                'i_color_wip'         => $icolor,
                'e_remark'            => $eremark,
                'd_entry'             => current_datetime(),
                'id_company'          => $idcompany,
                'd_daftar'            => $dproductregister,
                'id_class_product'    => $iclass,
                'd_launching'         => $dlaunch,
                'd_stp'               => $dstp,
                'id_product_base_tambahan' => $id_product_base
            );

            $this->db->insert('tr_product_base', $data);

            $data = array(
                'i_product_wip'       => strtoupper($iproductbase),
                'e_product_wipname'   => strtoupper($eproductbasename),
                'i_kode_kelompok'     => $ikodekelompok,
                'i_type_code'         => $itypecode,
                'i_kode_group_barang' => 'GRB0002',
                'i_brand'             => $ibrand,
                'i_style'             => $istyle,
                'i_color'             => $icolor,
                'i_status_produksi'   => $istatusproduksi,
                'i_satuan_code'       => $isatuancode,
                'n_panjang'           => $npanjang,
                'n_lebar'             => $nlebar,
                'n_tinggi'            => $ntinggi,
                'i_satuan_ukuran'     => $isatuanukuran,
                'n_berat'             => $nberat,
                'i_satuan_berat'      => $isatuanberat,
                'i_supplier'          => null,
                'e_remark'            => $eremark,
                'id_company'          => $idcompany,
                'd_entry'             => current_datetime()
            );
            $this->db->insert('tr_product_wip', $data);
        }
    }

    public function cek_data($iproductbase, $id)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query(
            "SELECT DISTINCT
            a.*,
            to_char(a.d_daftar, 'dd-mm-yyyy') as d_daftar,
            to_char(a.d_surat_penawaran, 'dd-mm-yyyy') as d_surat_penawaran,
            to_char(a.d_launching, 'dd-mm-yyyy') as d_launch,
            to_char(a.d_stp, 'dd-mm-yyyy') as d_stps,
            b.e_satuan_name,
            c.e_color_name,
            d.e_nama_group_barang,
            e.e_nama_kelompok,
            f.e_type_name,
            g.e_brand_name,
            h.e_style_name,
            i.e_status_produksi,
            e.id_divisi,
            j.e_nama_divisi,
            p.i_product_base ||' - '||p.e_product_basename||' '||q.e_color_name e_product_tambahan
        FROM
            tr_product_base a
        LEFT JOIN
            tr_satuan b
            ON (b.i_satuan_code = a.i_satuan_code AND b.id_company = a.id_company)
        LEFT JOIN
            tr_color c
            ON (c.i_color = a.i_color AND c.id_company = a.id_company)
        LEFT JOIN 
            tr_group_barang d
            ON (d.i_kode_group_barang = a.i_kode_group_barang AND d.id_company = a.id_company)
        LEFT JOIN
            tr_kelompok_barang e
            ON (e.i_kode_kelompok = a.i_kode_kelompok AND e.id_company = a.id_company)
        LEFT JOIN
            tr_item_type f
            ON (f.i_type_code = a.i_type_code AND f.id_company = a.id_company)
        LEFT JOin 
            tr_brand g
            ON (g.i_brand = a.i_brand AND g.id_company = a.id_company)
        LEFT JOIN 
            tr_style h
            ON (h.i_style = a.i_style AND h.id_company = a.id_company)
        LEFT JOIN 
            tr_status_produksi i
            ON (i.i_status_produksi = a.i_status_produksi)
        LEFT JOIN
            tr_divisi_new j
            ON (j.id = e.id_divisi)
        LEFT JOIN tr_product_base p ON (p.id = a.id_product_base_tambahan)
        LEFT JOIN tr_color q ON (q.i_color = p.i_color AND p.id_company = q.id_company)
        WHERE 
            a.id_company = '$idcompany'
            AND a.i_product_base = '$iproductbase'
            AND a.id = '$id'
        ORDER BY    
            a.i_product_base
        ", FALSE);
    }

    public function update(
        $id,
        $eproductbasename,
        $iproductbase,
        $icolor,
        $icolor_old,
        $isatuancode,
        $ikodegroupbarang,
        $ikodekelompok,
        $itypecode,
        $ibrand,
        $istyle,
        $istatusproduksi,
        $vhjp,
        $vgrosir,
        $esuratpenawaran,
        $dsuratpenawaran,
        $npanjang,
        $nlebar,
        $ntinggi,
        $isatuanukuran,
        $nberat,
        $isatuanberat,
        $eremark,
        $dproductregister,
        $iclass,
        $dlaunch,
        $dstp,
        $id_product_base
    ) {

        $idcompany = $this->session->userdata('id_company');
        if ($vgrosir == "") {
            $vgrosir == 0;
        }

        $data    = array(
            'e_product_basename'  => strtoupper($eproductbasename),
            'i_color'             => $icolor,
            'i_satuan_code'       => $isatuancode,
            'i_kode_group_barang' => $ikodegroupbarang,
            'i_kode_kelompok'     => $ikodekelompok,
            'i_type_code'         => $itypecode,
            'i_brand'             => $ibrand,
            'i_style'             => $istyle,
            'i_status_produksi'   => $istatusproduksi,
            'v_unitprice'         => $vhjp,
            'v_grosir'            => $vgrosir,
            'e_surat_penawaran'   => $esuratpenawaran,
            'd_surat_penawaran'   => $dsuratpenawaran,
            'n_panjang'           => $npanjang,
            'n_lebar'             => $nlebar,
            'n_tinggi'            => $ntinggi,
            'i_satuan_ukuran'     => $isatuanukuran,
            'n_berat'             => $nberat,
            'i_satuan_berat'      => $isatuanberat,
            'e_remark'            => $eremark,
            'd_daftar'            => $dproductregister,
            'd_update'            => current_datetime(),
            'id_class_product'    => $iclass,
            'd_launching'         => $dlaunch,
            'd_stp'               => $dstp,
            'id_product_base_tambahan' => $id_product_base
        );

        $product_wip = $this->db->query("SELECT i_color FROM tr_product_wip WHERE id_company = '$idcompany' AND i_product_wip = '$iproductbase' AND i_color='$icolor_old'")->row();

        if($product_wip != NULL) {
            $this->db->where('id', $id);
            $this->db->where('i_product_base', strtoupper($iproductbase));
            $this->db->where('id_company', $idcompany);
            $this->db->update('tr_product_base', $data);

            $this->db->set('i_color', $icolor);
            $this->db->where('i_product_wip', strtoupper($iproductbase));
            $this->db->where('id_company', $idcompany);
            $this->db->where('i_color', $icolor_old);
            $this->db->update('tr_product_wip');
        }

    }

    public function data_export()
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.id,
                upper(trim(i_product_base)) AS i_product_base,
                a.e_product_basename,
                b.e_color_name,
                f.e_nama_divisi,
                c.e_nama_kelompok,
                d.e_type_name,
                e.e_class_name,
                id_class_product,
                (select s.e_style_name from tr_style s where s.i_style = a.i_style and s.id_company = a.id_company)as series,
                (select b.e_brand_name from tr_brand b where b.i_brand = a.i_brand and b.id_company = a.id_company)as brand
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color
                    AND a.id_company = b.id_company)
            INNER JOIN tr_kelompok_barang c ON
                (c.i_kode_kelompok = a.i_kode_kelompok
                    AND a.id_company = c.id_company)
            INNER JOIN tr_item_type d ON
                (d.i_type_code = a.i_type_code
                    AND a.id_company = d.id_company)
            LEFT JOIN tr_class_product e ON
                (e.id = a.id_class_product)
            LEFT JOIN tr_divisi_new f ON
                (f.id = c.id_divisi)
            WHERE 
                a.f_status = 't'
                AND a.id_company = '$idcompany'
            ORDER BY
                a.i_product_base,
                a.e_product_basename ASC
        ", FALSE);
    }

    public function get_kategori_penjualan($name)
    {
        return $this->db->query("SELECT id FROM tr_class_product WHERE e_class_name ILIKE '%$name%'", FALSE);
    }

    public function update_product($id_product, $id_class_product)
    {
        $this->db->query("UPDATE tr_product_base SET id_class_product = '$id_class_product' WHERE id = '$id_product' ", FALSE);
    }

    public function get_product($cari)
    {
        return $this->db->query(
            "SELECT a.id, i_product_base||' - '||e_product_basename||' '||e_color_name as text
            FROM tr_product_base a
            INNER JOIN tr_color b ON (
                b.i_color = a.i_color AND a.id_company = b.id_company
            )
            WHERE a.id_company = '$this->id_company' AND (
                i_product_base ILIKE '%$cari%' 
                OR e_product_basename ILIKE '%$cari%'
                OR e_color_name ILIKE '%$cari%'
            )
            ORDER BY 2
        "
        );
    }
}

/* End of file Mmaster.php */