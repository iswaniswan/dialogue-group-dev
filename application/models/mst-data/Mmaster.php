<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	 public function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

		$datatables->query("  
                            SELECT
                            0 as no,
                            a.id,
                            a.i_material,
                            a.e_material_name,
                            a.i_satuan_code,
                            c.e_satuan_name,
                            a.i_type_code,
                            b.e_type_name,
                            a.i_kode_kelompok,
                            e.e_nama_kelompok,
                            a.i_kode_group_barang,
                            g.e_nama_group_barang,
                            a.i_supplier,
                            f.e_supplier_name,
                            to_char(a.d_entry,'dd-mm-yyyy') AS d_register,
                            a.id_company,
                            case when a.f_status = TRUE then 'Aktif' else 'Tidak Aktif' end as status, 
                            '$i_menu' as i_menu, 
                            '$folder' as folder,
                            a.d_entry, a.d_update
                          FROM
                            tr_material a
                          JOIN tr_item_type b ON
                            a.i_type_code = b.i_type_code AND a.id_company = b.id_company
                          JOIN tr_satuan c ON
                            a.i_satuan_code = c.i_satuan_code AND a.id_company = c.id_company
                          JOIN tr_kelompok_barang e ON
                            a.i_kode_kelompok = e.i_kode_kelompok AND a.id_company = e.id_company
                          LEFT JOIN tr_supplier f ON
                            a.i_supplier = f.i_supplier AND a.id_company = f.id_company
                          JOIN tr_group_barang g ON
                            a.i_kode_group_barang = g.i_kode_group_barang AND a.id_company = g.id_company
                          WHERE
                            a.id_company = '$idcompany'
                          order by a.d_entry desc nulls last, a.d_update desc nulls last", false);

        $datatables->edit(
        'status', 
                function ($data) {
                    $id         = trim($data['id']);
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

		$datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $igroup     = trim($data['i_kode_group_barang']);
            $ikategori  = trim($data['i_kode_kelompok']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$igroup/$ikategori/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$igroup/$ikategori/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
   
			return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');
        $datatables->hide('id');
        $datatables->hide('i_kode_group_barang');
        $datatables->hide('i_kode_kelompok');
        $datatables->hide('i_type_code');
        $datatables->hide('i_satuan_code');
        $datatables->hide('i_supplier');
        $datatables->hide('d_entry');
        $datatables->hide('d_update');
        
        return $datatables->generate();
	}

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_material');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row    = $query->row();
            $status = $row->f_status;
            if ($status=='t') {
                $stat = 'f';
            }else{
                $stat = 't';
            }
        }
        $data = array(
            'f_status' => $stat 
        );
        $this->db->where('id', $id);
        $this->db->update('tr_material', $data);
    }

    /*public function getkelompokbarang($typecode){
        return $this->db->query("
                                SELECT 
                                    a.i_type_code,
                                    a.e_type_name,
                                    a.i_kode_kelompok,
                                    b.e_nama_kelompok AS e_kategori,
                                    a.i_kode_group_barang,
                                    c.e_nama_group_barang
                                FROM 
                                    tr_item_type a
                                    LEFT JOIN tr_kelompok_barang b 
                                    ON (a.i_kode_kelompok = b.i_kode_kelompok)
                                    LEFT JOIN tr_group_barang c 
                                    ON (a.i_kode_group_barang = c.i_kode_group_barang)
                                WHERE
                                    a.i_type_code = '$typecode'
                                ORDER BY
                                    e_nama_kelompok
                                ", FALSE);
    }  

    /*public function getjenisbarang(){
        $idcompany      = $this->session->userdata('id_company');
        $idepartement   = $this->session->userdata('i_departement');
        return $this->db->query("SELECT DISTINCT
                                    a.i_departement,
                                    b.i_bagian,
                                    c.i_kode_kelompok,
                                    d.e_nama_kelompok,
                                    d.i_kode_group_barang,
                                    e.e_nama_group_barang,
                                    f.i_type_code,
                                    f.e_type_name
                                FROM
                                    tr_type a
                                    LEFT JOIN 
                                        tr_bagian b
                                        ON (a.i_type = b.i_type and a.id_company=b.id_company)
                                    LEFT JOIN 
                                        tr_bagian_kelompokbarang c
                                        ON (b.i_bagian = c.i_bagian and a.id_company=c.id_company)
                                    LEFT JOIN 
                                        tr_kelompok_barang d
                                        ON (c.i_kode_kelompok = d.i_kode_kelompok and a.id_company=d.id_company)
                                    LEFT JOIN 
                                        tr_group_barang e
                                        ON (d.i_kode_group_barang = e.i_kode_group_barang and a.id_company=e.id_company)
                                    LEFT JOIN 
                                        tr_item_type f
                                        ON (d.i_kode_kelompok = f.i_kode_kelompok AND d.i_kode_group_barang = f.i_kode_group_barang AND e.i_kode_group_barang = f.i_kode_group_barang and a.id_company=f.id_company)
                                WHERE
                                    a.i_departement = '$idepartement' and a.id_company='$idcompany'", FALSE);
        return $this->db->query("SELECT * FROM tr_item_type WHERE  id_company= '$idcompany' or i_kode_group_barang='GRB0001' or i_kode_group_barang='GRB0004' or i_kode_group_barang='GRB0005' ORDER BY e_type_name", FALSE)->result();
    }*/

     public function getgroup($cari, $idcompany){
        $cari = strtoupper($this->input->get('q'));
        return $this->db->query("
            SELECT
                DISTINCT i_kode_group_barang,
                e_nama_group_barang
            FROM
                tr_group_barang
            WHERE
                i_kode_group_barang in ('GRB0001', 'GRB0003', 'GRB0004', 'GRB0005', 'GRB0006') 
                AND id_company = '$idcompany'
                AND f_status = 't'
                AND (i_kode_group_barang ILIKE '%$cari%'
                OR e_nama_group_barang ILIKE '%$cari%')
        ", FALSE);
    }

    public function getkategori($cari, $igroup, $idcompany){
        return $this->db->query("
            SELECT
                i_kode_kelompok,
                e_nama_kelompok
            FROM
                tr_kelompok_barang
            WHERE
                f_status = 't'
                AND i_kode_group_barang = '$igroup'
                AND id_company = '$idcompany'
                AND (i_kode_kelompok ILIKE '%$cari%'
                OR e_nama_kelompok ILIKE '%$cari%')
            ORDER BY
                e_nama_kelompok
        ", FALSE);
    }

    public function getjenis($cari, $ikategori, $idcompany){
        return $this->db->query("
            SELECT
                i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
            f_status = 't' and
                i_kode_kelompok = '$ikategori'
                AND id_company = '$idcompany'
                AND (i_type_code ILIKE '%$cari%'
                OR e_type_name ILIKE '%$cari%')
            ORDER BY
                e_type_name
        ", FALSE);
    }

    public function style($cari, $idcompany){
        return $this->db->query("SELECT i_style, e_style_name FROM tr_style WHERE f_status = 't' AND id_company = '$idcompany' AND (i_style like '%$cari%' or e_style_name like '%$cari%') ORDER BY e_style_name", FALSE);
    }

    public function brand($cari, $idcompany){
        return $this->db->query("SELECT i_brand, e_brand_name FROM tr_brand WHERE f_status = 't' AND id_company = '$idcompany' AND (i_brand like '%$cari%' or e_brand_name like '%$cari%') ORDER BY e_brand_name", FALSE);
    }

    public function satuan($cari, $idcompany){
        return $this->db->query("SELECT i_satuan_code, initcap(e_satuan_name) AS e_satuan_name FROM tr_satuan WHERE f_status = 't' AND id_company = '$idcompany' AND (i_satuan_code like '%$cari%' or e_satuan_name like '%$cari%') ORDER BY e_satuan_name", FALSE);
    }

    public function get_satuan_konversi($cari, $idcompany){
        return $this->db->query("SELECT i_satuan_code, initcap(e_satuan_name) AS e_satuan_name FROM tr_satuan WHERE f_status = 't' AND id_company = '$idcompany' AND (i_satuan_code like '%$cari%' or e_satuan_name like '%$cari%') ORDER BY e_satuan_name", FALSE);
    }

    public function statusproduksi($cari){
        return $this->db->query("SELECT i_status_produksi, e_status_produksi FROM tr_status_produksi WHERE (i_status_produksi like '%$cari%' or e_status_produksi like '%$cari%') ORDER BY e_status_produksi", FALSE);
    }

    public function supplier($cari, $idcompany){
        return $this->db->query("
                                SELECT distinct 
                                    i_supplier, 
                                    e_supplier_name
                                FROM 
                                    tr_supplier
                                WHERE 
                                    f_status = 't' 
                                AND
                                    id_company = '$idcompany'
                                AND
                                    (i_supplier like '%$cari%' or e_supplier_name like '%$cari%')
                                ORDER BY e_supplier_name"
                                , FALSE);
        return $this->db->get();
    }

    public function divisi($cari, $idcompany){
        return $this->db->query("SELECT id, i_kode_divisi, e_nama_divisi FROM tr_divisi WHERE id_company = '$idcompany' AND (i_kode_divisi like '%$cari%' or e_nama_divisi like '%$cari%') ORDER BY e_nama_divisi", FALSE);
    }

    public function cekgudang($username, $idcompany){
        return $this->db->query("SELECT i_bagian, e_bagian_name FROM tr_bagian WHERE f_status = 't'", FALSE)->resutl();
    }

    public function get_databarang(){
        return $this->db->query("SELECT * FROM tr_material WHERE f_status = 't'", FALSE)->result();
    }

    public function cek_data($id, $idcompany){
        return $this->db->query("
                                SELECT 
                                    a.*,
                                    to_char(a.d_entry,'dd-mm-yyyy') as d_register,
                                    b.e_type_name,
                                    c.e_nama_kelompok,
                                    d.e_satuan_name as e_satuan_barang,
                                    e.e_satuan_name as e_satuan_berat,
                                    f.e_satuan_name as e_satuan_ukuran,
                                    g.e_brand_name,
                                    h.e_style_name,
                                    i.e_status_produksi,
                                    j.e_nama_group_barang,
                                    k.e_supplier_name,
                                    a.i_divisi,
                                    l.e_nama_divisi
                                FROM
                                    tr_material a
                                    LEFT JOIN tr_item_type b
                                    ON (a.i_type_code = b.i_type_code AND a.id_company = b.id_company)
                                    LEFT JOIN tr_kelompok_barang c
                                    ON (a.i_kode_kelompok = c.i_kode_kelompok AND a.id_company = c.id_company)
                                    LEFT JOIN tr_satuan d
                                    ON (a.i_satuan_code = d.i_satuan_code AND a.id_company = d.id_company)
                                    LEFT JOIN tr_satuan e
                                    ON (a.i_satuan_berat = e.i_satuan_code AND a.id_company = e.id_company)
                                    LEFT JOIN tr_satuan f
                                    ON (a.i_satuan_ukuran = f.i_satuan_code AND a.id_company = f.id_company)
                                    LEFT JOIN tr_brand g
                                    ON (a.i_brand = g.i_brand AND a.id_company = g.id_company)
                                    LEFT JOIN tr_style h
                                    ON (a.i_style = h.i_style AND a.id_company = h.id_company)
                                    LEFT JOIN tr_status_produksi i
                                    ON (a.i_status_produksi = i.i_status_produksi)
                                    LEFT JOIN tr_group_barang j
                                    ON (a.i_kode_group_barang = j.i_kode_group_barang AND a.id_company = j.id_company)
                                    LEFT JOIN tr_supplier k
                                    ON (a.i_supplier = k.i_supplier AND a.id_company = k.id_company)
                                    LEFT JOIN tr_divisi l
                                    ON (a.i_divisi = l.i_kode_divisi AND a.id_company = l.id_company)
                                WHERE
                                    a.id = '$id'
                                AND
                                    a.id_company = '$idcompany'
                                ORDER BY 
                                    a.i_material,
                                    a.e_material_name
                                ", FALSE);
    }

    public function cek_data_detail($id, $idcompany){
        return $this->db->query("SELECT
                a.*,
                initcap(b.e_satuan_name) AS e_satuan_name
            FROM
                tr_material_konversi a
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code_konversi
                    AND a.id_company = b.id_company)
            WHERE
                a.id_material = '$id'
            ORDER BY
                a.id", FALSE);
    }

    public function cek_data_detail_bisbisan($id, $idcompany){
        return $this->db->query("
            SELECT a.id, a.id_jenis_potong, a.id_material, a.e_material_name, 
            a.n_bisbisan , a.v_lebar_kain_awal, b.n_hilang_lebar, v_lebar_kain_akhir , v_jumlah_roll, 
            b.n_tambah_panjang , n_panjang_bis, v_panjang_bis , b.e_jenis_potong
            from tr_material_bisbisan a 
            inner join tr_jenis_potong b on (a.id_jenis_potong = b.id)
            where a.id_material  = '$id' and a.f_status = 't' and a.id_company = '$idcompany'
        ", FALSE);
    }

    public function cekkode($kode, $idcompany){
        return $this->db->query("SELECT i_material FROM tr_material WHERE i_material ='$kode' AND id_company = '$idcompany'", FALSE);
    }

    public function getkode($ijenisbrg){
         $this->db->select(" 
                                  max(i_material) AS i_material  
                                FROM 
                                  tr_material 
                                WHERE 
                                  i_type_code = '$ijenisbrg'
                                AND
                                  id_company = '".$this->session->userdata('id_company')."'
                                ORDER BY i_material 
                                DESC LIMIT 1
                            ", FALSE);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $i_material = $kuy->i_material; 
        }else{
            $i_material = '';
        }
        return $i_material;
    }

	public function insert($ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi,$dateregister){  
        $idcompany  = $this->session->userdata('id_company');

        $query = $this->db->query("SELECT max(id)+1 AS id FROM tr_material", TRUE);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
            if ($id == null) {
                $id = 1;
            } else {
                $id = $id;
            }
        } else {
            $id = 1;
        }

        $data = array(
                  'id'                    => $id,
                  'i_material'            => $ikodebrg,
                  'e_material_name'       => $enamabrg,
                  'i_supplier'            => $isupplier,
                  'i_type_code'           => $ijenisbrg,
                  'i_kode_kelompok'       => $ikelompok,
                  'i_satuan_code'         => $isatuan, 
                  'i_kode_group_barang'   => $igroupbrg,
                  'n_panjang'             => $npanjang,
                  'n_lebar'               => $nlebar,
                  'n_tinggi'              => $ntinggi,
                  'n_berat'               => $nberat,
                  'i_satuan_berat'        => $isatuanberat,
                  'i_satuan_ukuran'       => $isatuanukuran,
                  'i_brand'               => $ibrand,
                  'i_style'               => $istyle,
                  'i_status_produksi'     => $istatusproduksi,
                //   'i_divisi'              => $idivisi,
                  'e_remark'              => $edeskripsi,
                  'id_company'            => $idcompany,
                  'd_entry'               => $dateregister, 
        );
        $this->db->insert('tr_material', $data);

        $jml = $this->input->post('jml');
        if($jml>0){
            for($i = 1; $i <= $jml; $i++){
                $table = array(
                    'id_company'             => $idcompany,
                    'id_material'            => $id,
                    'e_operator'             => $this->input->post('eperator'.$i, TRUE),
                    'n_faktor'               => $this->input->post('faktor'.$i, TRUE),
                    'i_satuan_code_konversi' => $this->input->post('isatuankonversi'.$i, TRUE),
                    'f_default'              => $this->input->post('default'.$i, TRUE)
                );
                $this->db->insert('tr_material_konversi', $table);
            }
        }

        $jmlbis = $this->input->post('jmlbis');
        // $this->db->query("
        //    update tr_material_bisbisan set f_status = false WHERE id_material = '$id'
        // ", FALSE);
        if($jmlbis>0){
            for($i = 1; $i <= $jmlbis; $i++){
                $n_bisbisan = $this->input->post('n_bisbisan'.$i,TRUE);
                $v_lebar_kain_awal = $this->input->post('v_lebar_kain_awal'.$i,TRUE);
                if ($n_bisbisan != '0' || $v_lebar_kain_awal != '0') {
                    // $id_jenis_potong = $this->input->post('id_jenis_potong'.$i,TRUE);
                    // $n_hilang_lebar         = $this->input->post('n_hilang_lebar'.$i,TRUE);
                    // $v_lebar_kain_akhir     = $this->input->post('v_lebar_kain_akhir'.$i,TRUE);
                    // $v_jumlah_roll          = $this->input->post('v_jumlah_roll'.$i,TRUE);
                    // $n_tambah_panjang       = $this->input->post('n_tambah_panjang'.$i,TRUE);
                    // $n_panjang_bis          = $this->input->post('n_panjang_bis'.$i,TRUE);
                    // $v_panjang_bis          = $this->input->post('v_panjang_bis'.$i,TRUE);

                    //  $this->db->query("
                    //    insert into tr_material_bisbisan(id_company, id_material, e_material_name, id_jenis_potong, n_bisbisan, v_lebar_kain_awal, n_hilang_lebar, v_lebar_kain_akhir, v_jumlah_roll, n_tambah_panjang, n_panjang_bis, v_panjang_bis)
                    //    values('$idcompany', '$id', '$enamabrg', '$id_jenis_potong', '$n_bisbisan', '$v_lebar_kain_awal', '$v_lebar_kain_akhir', '$v_jumlah_roll', '$n_tambah_panjang', '$n_panjang_bis', '$v_panjang_bis')
                    //    ON CONFLICT (id) 
                    //    DO 
                    //        UPDATE SET email
                    // ", FALSE);

                    $table = array(
                        'id_company'             => $idcompany,
                        'id_material'            => $id,
                        'e_material_name'        => $enamabrg,
                        'id_jenis_potong'        => $this->input->post('id_jenis_potong'.$i,TRUE),
                        'n_bisbisan'             => $n_bisbisan,
                        'v_lebar_kain_awal'      => $v_lebar_kain_awal,
                        'n_hilang_lebar'         => $this->input->post('n_hilang_lebar'.$i,TRUE),
                        'v_lebar_kain_akhir'     => $this->input->post('v_lebar_kain_akhir'.$i,TRUE),
                        'v_jumlah_roll'          => $this->input->post('v_jumlah_roll'.$i,TRUE),
                        'n_tambah_panjang'       => $this->input->post('n_tambah_panjang'.$i,TRUE),
                        'n_panjang_bis'          => $this->input->post('n_panjang_bis'.$i,TRUE),
                        'v_panjang_bis'          => $this->input->post('v_panjang_bis'.$i,TRUE)
                    );
                    $this->db->insert('tr_material_bisbisan', $table);
                }
            }
        }


    }

    public function get_dataheader(){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
        a.id,
        a.i_material,
        a.e_material_name,
        a.i_satuan_code,
        c.e_satuan_name,
        a.i_type_code,
        b.e_type_name,
        a.i_kode_kelompok,
        e.e_nama_kelompok,
        a.i_kode_group_barang,
        g.e_nama_group_barang,
        a.i_supplier,
        f.e_supplier_name,
        to_char(a.d_entry,'dd-mm-yyyy') AS d_register,
        a.id_company
      FROM
        tr_material a
      JOIN tr_item_type b ON
        a.i_type_code = b.i_type_code AND a.id_company = b.id_company
      JOIN tr_satuan c ON
        a.i_satuan_code = c.i_satuan_code AND a.id_company = c.id_company
      JOIN tr_kelompok_barang e ON
        a.i_kode_kelompok = e.i_kode_kelompok AND a.id_company = e.id_company
      LEFT JOIN tr_supplier f ON
        a.i_supplier = f.i_supplier AND a.id_company = f.id_company
      JOIN tr_group_barang g ON
        a.i_kode_group_barang = g.i_kode_group_barang AND a.id_company = g.id_company
      WHERE
        a.f_status = 't' AND
        a.id_company = '$idcompany'
      order by a.d_entry desc nulls last, a.d_update desc nulls last, a.i_material, a.e_material_name");
    }

    public function get_datadetail($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.*,
                c.i_material,
                c.e_material_name,
                initcap(b.e_satuan_name) AS e_satuan_name
            FROM
                tr_material_konversi a
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code_konversi
                    AND a.id_company = b.id_company)
            LEFT JOIN tr_material c ON
                (c.id = a.id_material)
            WHERE
                a.id_material IN ('$id')
            ORDER BY
                c.i_material, c.e_material_name", FALSE);
    }

    public function get_datamaterial($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT
                a.i_material,
                a.e_material_name,
                c.e_operator,
                c.n_faktor,
                initcap(c.e_satuan_name) AS e_satuan_name_konversi,
                c.f_default,
                initcap(b.e_satuan_name) AS e_satuan_name
            FROM
                tr_material a
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code
                    AND a.id_company = b.id_company)
            LEFT JOIN (SELECT 
                a.id_material,
                a.e_operator,
                a.n_faktor,
                a.i_satuan_code_konversi,
                a.f_default,
                b.e_satuan_name 
                FROM tr_material_konversi a
                LEFT JOIN tr_satuan b 
                ON (a.i_satuan_code_konversi = i_satuan_code)) C 
            ON (c.id_material = a.id)
            WHERE
                a.id IN ('$id')
                AND c.e_operator IS NOT NULL
            ORDER BY
                a.i_material, a.e_material_name", FALSE);
    }

    public function get_databisbisan($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT a.id, a.id_jenis_potong, a.id_material, a.e_material_name, 
            a.n_bisbisan , a.v_lebar_kain_awal, b.n_hilang_lebar, v_lebar_kain_akhir , v_jumlah_roll, 
            b.n_tambah_panjang , n_panjang_bis, v_panjang_bis , b.e_jenis_potong,
            c.i_material
            from tr_material_bisbisan a 
            inner join tr_jenis_potong b on (a.id_jenis_potong = b.id)
            LEFT JOIN tr_material c ON (c.id = a.id_material)
            where a.id_material IN ('$id') and a.f_status = 't' and a.id_company = '$idcompany'
            order by c.i_material, a.e_material_name
        ", FALSE);
    }

    public function update($id, $ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi,$dateregister){  

        $idcompany  = $this->session->userdata('id_company');
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
                'i_material'            => $ikodebrg,
                'e_material_name'       => $enamabrg,
                'i_supplier'            => $isupplier,
                'i_type_code'           => $ijenisbrg,
                'i_kode_kelompok'       => $ikelompok,
                'i_satuan_code'         => $isatuan, 
                'i_kode_group_barang'   => $igroupbrg,
                'n_panjang'             => $npanjang,
                'n_lebar'               => $nlebar,
                'n_tinggi'              => $ntinggi,
                'n_berat'               => $nberat,
                'i_satuan_berat'        => $isatuanberat,
                'i_satuan_ukuran'       => $isatuanukuran,
                'i_brand'               => $ibrand,
                'i_style'               => $istyle,
                'i_status_produksi'     => (!empty($istatusproduksi)) ? $istatusproduksi : NULL,
                // 'i_divisi'              => $idivisi,
                'e_remark'              => $edeskripsi,
                'd_entry'               => $dateregister, 
                'd_update'              => $dupdate
        );

        $this->db->where('id', $id);
        $this->db->update('tr_material', $data);

        $jml = $this->input->post('jml');
        if($jml>0){
            $this->db->where('id_material', $id);
            $this->db->delete('tr_material_konversi');
            for($i = 1; $i <= $jml; $i++){
                $table = array(
                    'id_company'             => $this->session->id_company,
                    'id_material'            => $id,
                    'e_operator'             => $this->input->post('eperator'.$i, TRUE),
                    'n_faktor'               => $this->input->post('faktor'.$i, TRUE),
                    'i_satuan_code_konversi' => $this->input->post('isatuankonversi'.$i, TRUE),
                    'f_default'              => $this->input->post('default'.$i, TRUE)
                );
                $this->db->insert('tr_material_konversi', $table);
            }
        }

        $jmlbis = $this->input->post('jmlbis');
        $this->db->query("
           update tr_material_bisbisan set f_status = false WHERE id_material = '$id'
        ", FALSE);
        if($jmlbis>0){
            for($i = 1; $i <= $jmlbis; $i++){
                $n_bisbisan = $this->input->post('n_bisbisan'.$i,TRUE);
                $v_lebar_kain_awal = $this->input->post('v_lebar_kain_awal'.$i,TRUE);
                if ($n_bisbisan != '0' || $v_lebar_kain_awal != '0') {
                    $table = array(
                        'id_company'             => $idcompany,
                        'id_material'            => $id,
                        'e_material_name'        => $enamabrg,
                        'id_jenis_potong'        => $this->input->post('id_jenis_potong'.$i,TRUE),
                        'n_bisbisan'             => $n_bisbisan,
                        'v_lebar_kain_awal'      => $v_lebar_kain_awal,
                        'n_hilang_lebar'         => $this->input->post('n_hilang_lebar'.$i,TRUE),
                        'v_lebar_kain_akhir'     => $this->input->post('v_lebar_kain_akhir'.$i,TRUE),
                        'v_jumlah_roll'          => $this->input->post('v_jumlah_roll'.$i,TRUE),
                        'n_tambah_panjang'       => $this->input->post('n_tambah_panjang'.$i,TRUE),
                        'n_panjang_bis'          => $this->input->post('n_panjang_bis'.$i,TRUE),
                        'v_panjang_bis'          => $this->input->post('v_panjang_bis'.$i,TRUE)
                    );
                    $this->db->insert('tr_material_bisbisan', $table);
                }
            }
        }

    }


    public function get_jenis_potong($cari){
        return $this->db->query("
            SELECT id, initcap(e_jenis_potong) AS e_jenis_potong FROM tr_jenis_potong WHERE f_status = 't' AND (e_jenis_potong like '%$cari%') ORDER BY e_jenis_potong
        ", FALSE);
    }

    public function get_jenis_potong_detail($id){
        return $this->db->query("
            SELECT n_hilang_lebar, n_tambah_panjang from tr_jenis_potong WHERE id = '$id'
        ", FALSE);
    }

}
/* End of file Mmaster.php */