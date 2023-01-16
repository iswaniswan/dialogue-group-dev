<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

        $datatables->query("  
                            SELECT
                                0 AS NO,
                                a.id,
                                a.i_product_wip,
                                a.e_product_wipname,
                                c.e_type_name,
                                b.e_nama_kelompok,
                                d.e_nama_group_barang,
                                a.id_company,
                                ab.e_color_name,
                                CASE
                                    WHEN a.f_status = TRUE THEN 'Aktif'
                                    ELSE 'Tidak Aktif'
                                END AS status,
                                '$i_menu' AS i_menu ,
                                '$folder' AS folder, a.d_entry, a.d_update
                            FROM
                                tr_product_wip a
                            LEFT JOIN tr_color ab ON 
                                (ab.i_color = a.i_color AND a.id_company = ab.id_company)
                            LEFT JOIN tr_kelompok_barang b ON
                                a.i_kode_kelompok = b.i_kode_kelompok AND a.id_company = b.id_company
                            LEFT JOIN tr_item_type c ON
                                a.i_type_code = c.i_type_code AND a.id_company = c.id_company
                            LEFT JOIN tr_group_barang d ON
                                a.i_kode_group_barang = d.i_kode_group_barang AND a.id_company = d.id_company
                            WHERE
                                a.id_company = '$idcompany'
                            order by a.d_entry desc nulls last, a.d_update desc nulls last
        ", FALSE);

        $datatables->edit('status', 
            function ($data) {
                $id           = $data['i_product_wip'];
                $folder       = $data['folder'];
                $id_menu      = $data['i_menu'];
                $status       = $data['status'];
                if ($status=='Aktif') {
                    $warna = 'success';
                }else{
                    $warna = 'danger';
                }
                $data      = '';
                if(check_role($id_menu, 3)){
                    $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                }else{
                    $data   .= "<span class=\"label label-$warna\">$status</span>";
                }
                return $data;
            }
        );

        $datatables->add('action', function ($data) {
            $id       = $data['id'];
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $data     = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class=' ti-pencil-alt'></i></a>";
            }
            return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');
        $datatables->hide('id');
        $datatables->hide('d_entry');
        $datatables->hide('d_update');

        return $datatables->generate();
    }

    public function status($id){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('f_status');
        $this->db->from('tr_product_wip');
        $this->db->where('i_product_wip', $id);
        $this->db->where('id_company', $idcompany);
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
        $this->db->where('i_product_wip', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_product_wip', $data);
    }

    public function material($cari){
        return $this->db->query("
                                SELECT 
                                    id, 
                                    i_material, 
                                    e_material_name
                                FROM
                                    tr_material 
                                WHERE
                                    i_material ILIKE '%$cari%'
                                    AND e_material_name ILIKE '%$cari%'
                                    AND f_status = 't'
                                    AND id_company = '".$this->session->userdata('id_company')."' 
                                ORDER BY
                                    i_material,
                                    e_material_name
                                ", FALSE);
    }

    public function get_groupbarang(){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_kode_group_barang, e_nama_group_barang');
        $this->db->from('tr_group_barang');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->order_by('e_nama_group_barang','ASC');
        return $this->db->get();
    }

    public function get_kelompokbarang(){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_kode_kelompok, e_nama_kelompok');
        $this->db->from('tr_kelompok_barang');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->order_by('e_nama_kelompok','ASC');
        return $this->db->get();
    }

    public function get_jenis(){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_type_code, e_type_name');      
        $this->db->from('tr_item_type');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->order_by('e_type_name','ASC');
        return $this->db->get();
    }

    public function get_brand(){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_brand, e_brand_name');
        $this->db->from('tr_brand');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->order_by('e_brand_name','ASC');
        return $this->db->get();
    }

    public function get_style(){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_style, e_style_name');
        $this->db->from('tr_style');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->order_by('e_style_name','ASC');
        return $this->db->get();
    }

    public function get_statusproduksi(){
        $this->db->select('i_status_produksi, e_status_produksi');
        $this->db->from('tr_status_produksi');
        $this->db->order_by('e_status_produksi','ASC');
        return $this->db->get();
    }

    public function get_satuan(){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_satuan_code, e_satuan_name AS e_satuan');
        $this->db->from('tr_satuan');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->order_by('e_satuan_name','ASC');
        return $this->db->get();
    }

    public function get_satuanberat(){
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_satuan_code, e_satuan_name AS e_satuan');
        $this->db->from('tr_satuan');
        $this->db->where('f_status', 't');
        $this->db->where('id_company', $idcompany);
        $this->db->like('UPPER(e_satuan_name)', 'GRAM');
        $this->db->order_by('e_satuan_name','ASC');
        return $this->db->get();
    }

    public function get_color($cari){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                i_color,
                e_color_name
            FROM
                tr_color
            WHERE
                id_company = '$idcompany'
                AND f_status = 't'
                AND (i_color ILIKE '%$cari%'
                OR e_color_name ILIKE '%$cari%')
        ", FALSE);
    }

    public function getgroup($cari){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT i_kode_group_barang,
                e_nama_group_barang
            FROM
                tr_group_barang
            WHERE
                i_kode_group_barang = 'GRB0002'
                AND id_company = '$idcompany'
                AND f_status = 't'
                AND (i_kode_group_barang ILIKE '%$cari%'
                OR e_nama_group_barang ILIKE '%$cari%')
        ", FALSE);
    }

    public function getkelompok($cari,$igroupbrg){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                i_kode_kelompok,
                e_nama_kelompok
            FROM
                tr_kelompok_barang
            WHERE
                f_status = 't'
                AND id_company = '$idcompany'
                AND i_kode_group_barang = '$igroupbrg'
                AND (i_kode_kelompok ILIKE '%$cari%'
                OR e_nama_kelompok ILIKE '%$cari%')
            ORDER BY
                e_nama_kelompok
        ", FALSE);
    }

    public function getjenis($cari,$ikelompok){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                f_status = 't'
                AND id_company = '$idcompany'
                AND i_kode_kelompok = '$ikelompok'
                AND (i_type_code ILIKE '%$cari%'
                OR e_type_name ILIKE '%$cari%')
            ORDER BY
                e_type_name
        ", FALSE);
    }

    public function cek_kode($kode)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_product_wip');
        $this->db->from('tr_product_wip');
        $this->db->where('i_product_wip', $kode);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    public function supplier($cari){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                i_supplier,
                e_supplier_name
            FROM
                tr_supplier a,
                tr_supplier_group b
            WHERE
                a.i_supplier_group = b.i_supplier_group AND a.id_company = b.id_company
                AND a.id_company = '$idcompany'
                AND b.f_status = 't'
                AND b.i_supplier_group = 'KTG02'
                AND (e_supplier_name ILIKE '%$cari%' 
                    OR i_supplier ILIKE '%$cari%')
            ORDER BY e_supplier_name
        ", FALSE);
    }

    public function cekada($ikode,$icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT i_product_wip
            FROM tr_product_wip
            WHERE i_product_wip = '$ikode'
            AND i_color = '$icolor'
            AND id_company = '$idcompany'
        ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tr_product_wip');
        return $this->db->get()->row()->id+1;
    }

    public function insert($id, $ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $icolor)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id'                  => $id,
            'i_product_wip'       => $ikodebrg,
            'e_product_wipname'   => $enamabrg,  
            'i_kode_kelompok'     => $ikelompok,
            'i_type_code'         => $ijenisbrg,
            'i_kode_group_barang' => $igroupbrg,
            'i_brand'             => $ibrand,
            'i_style'             => $istyle,
            'i_color'             => $icolor,
            'i_status_produksi'   => $istatusproduksi,
            'i_satuan_code'       => $isatuan,
            'n_panjang'           => $npanjang,
            'n_lebar'             => $nlebar,
            'n_tinggi'            => $ntinggi,
            'i_satuan_ukuran'     => $isatuanukuran,
            'n_berat'             => $nberat,
            'i_satuan_berat'      => $isatuanberat,
            'i_supplier'          => $isupplier,
            'e_remark'            => $edeskripsi,
            'id_company'          => $idcompany,
            'd_entry'             => current_datetime(),
            'd_update'            => current_datetime()
        );
        $this->db->insert('tr_product_wip', $data);
    }

    public function insertdetail($id,$imaterial,$nquantity,$bagian){
        $data = array(
            'id_company'     => $this->session->userdata('id_company'),
            'id_product_wip' => $id,
            'id_material'    => $imaterial,
            'n_quantity'     => $nquantity,
            'bagian'         => $bagian,
        );
        $this->db->insert('tr_product_wip_item', $data);
    }


    public function update($ikodebrgold, $ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $icolor){
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
                        'i_product_wip'       => $ikodebrg,
                        'e_product_wipname'   => $enamabrg,  
                        'i_kode_kelompok'     => $ikelompok,
                        'i_type_code'         => $ijenisbrg,
                        'i_kode_group_barang' => $igroupbrg,
                        'i_brand'             => $ibrand,
                        'i_style'             => $istyle,
                        'i_color'             => $icolor,
                        'i_status_produksi'   => $istatusproduksi,
                        'i_satuan_code'       => $isatuan,
                        'n_panjang'           => $npanjang,
                        'n_lebar'             => $nlebar,
                        'n_tinggi'            => $ntinggi,
                        'i_satuan_ukuran'     => $isatuanukuran,
                        'n_berat'             => $nberat,
                        'i_satuan_berat'      => $isatuanberat,
                        'i_supplier'          => $isupplier,
                        'e_remark'            => $edeskripsi,
                        'd_update'            => current_datetime()
        );
        $this->db->where('i_product_wip', $ikodebrgold);
        $this->db->where('i_color', $icolor);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_product_wip', $data);
     // return $this->db->query(" 
     //                            INSERT
     //                            INTO
     //                            tr_product_wip (i_product_wip, e_product_wipname, i_kode_kelompok, i_type_code, i_kode_group_barang, i_brand, i_style, i_color, i_status_produksi, i_satuan_code, n_panjang, n_lebar, n_tinggi, i_satuan_ukuran, n_berat, i_satuan_berat, i_supplier, e_remark, id_company, d_entry)
     //                            VALUES ('$ikodebrg', '$enamabrg', '$ikelompok', '$ijenisbrg', '$igroupbrg', '$ibrand', '$istyle', '$icolor', '$istatusproduksi', '$isatuan', '$npanjang', '$nlebar', '$ntinggi', '$isatuanukuran', '$nberat', '$isatuanberat', '$isupplier', '$edeskripsi', '$idcompany', '$dentry')
     //                            ON
     //                            CONFLICT (i_product_wip, i_color, id_company) DO
     //                            UPDATE
     //                            SET
     //                            i_product_wip = include.i_product_wip,
     //                            i_color = excluded.i_color,
     //                            id_company = excluded.id_company
                                
     //                        ", false);
    }

    public function get_data($id){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT 
                a.id,
                a.i_product_wip,
                a.e_product_wipname,
                a.i_kode_group_barang,
                b.e_nama_group_barang,
                a.i_kode_kelompok,
                c.e_nama_kelompok,
                a.i_style,
                a.i_brand,
                a.i_status_produksi,
                a.i_satuan_code,
                a.i_supplier,
                d.e_supplier_name,
                a.e_remark,
                a.i_type_code,
                e_type_name,
                a.n_panjang,
                a.n_lebar,
                a.n_tinggi,
                a.n_berat,
                a.i_satuan_berat,
                a.i_satuan_ukuran,
                f.e_satuan_name
            FROM
                tr_product_wip a
            INNER JOIN tr_group_barang b ON
                (b.i_kode_group_barang = a.i_kode_group_barang and a.id_company = b.id_company)
            INNER JOIN tr_kelompok_barang c ON
                (c.i_kode_kelompok = a.i_kode_kelompok and a.id_company = c.id_company)
            INNER JOIN tr_item_type e ON
                (e.i_type_code = a.i_type_code and a.id_company = e.id_company)
            LEFT JOIN tr_supplier d ON
                (d.i_supplier = a.i_supplier and a.id_company = d.id_company)
            LEFT JOIN tr_satuan f ON 
                (f.i_satuan_code = a.i_satuan_ukuran and a.id_company =f.id_company)
            WHERE
                a.id ='$id'
            AND 
                a.id_company = '$idcompany'
        ", false);
    }

    public function get_datadetail($id){
        return $this->db->query("
                                SELECT DISTINCT
                                	a.id_product_wip,
                                    a.id_material,
                                    c.i_material,
                                	c.e_material_name,
                                    a.n_quantity,
                                    c.i_satuan_code,
                                    d.e_satuan_name,
                                    a.bagian
                                FROM
                                	tr_product_wip_item a
                                	INNER JOIN 
                                		tr_product_wip b ON (a.id_product_wip = b.id AND a.id_company = b.id_company)
                                	INNER JOIN 
                                        tr_material c ON (a.id_material = c.id AND a.id_company = c.id_company)
                                    INNER JOIN
                                        tr_satuan d ON (c.i_satuan_code = d.i_satuan_code AND c.id_company = d.id_company)
                                WHERE 
                                	a.id_product_wip = '$id'
                                	AND a.id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
    }

    public function get_detail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT 
                a.i_color, e_color_name
            FROM tr_product_wip a
            INNER JOIN tr_color b ON 
                (b.i_color = a.i_color and a.id_company = b.id_company)
            WHERE a.id = '$id' AND a.id_company = '$idcompany'
        ", FALSE);
    }

    public function delete($kode)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('i_product_wip', $kode);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tr_product_wip');
    }

    public function deletedetail($id){
        $company = $this->session->userdata('id_company');
        $this->db->where('id_product_wip', $id);
        $this->db->where('id_company', $company);
        $this->db->delete('tr_product_wip_item');
    }

    public function getsatuanmaterial($idmaterial){
        return $this->db->query("
                                SELECT 
                                	a.i_satuan_code,
                                	b.e_satuan_name
                                FROM
                                	tr_material a
                                	INNER JOIN 
                                		tr_satuan b ON (a.i_satuan_code = b.i_satuan_code and a.id_company = b.id_company)
                                WHERE
                                	a.id = '$idmaterial'
                                	and a.id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
    }
}

/* End of file Mmaster.php */