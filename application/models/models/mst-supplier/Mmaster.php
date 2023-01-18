<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    function data($i_menu, $folder)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

        $datatables->query("
                        SELECT 
                        0 as no,
                        a.id,
                        a.i_supplier, 
                        a.e_supplier_name, 
                        a.i_supplier_group, 
                        b.e_supplier_group_name, 
                        d.e_level_name,
                        a.e_supplier_city, 
                        a.e_supplier_phone, 
                        a.i_level,
                        a.i_kepala_pusat,
                        a.id_company,
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
                        '$folder' as folder 
                    FROM 
                        tr_supplier a
                        LEFT JOIN tr_supplier_group b ON a.i_supplier_group = b.i_supplier_group AND a.id_company = b.id_company
                        LEFT JOIN tr_level_perusahaan d ON a.i_level =  d.i_level AND a.id_company = d.id_company
                    WHERE
                        a.id_company = '$idcompany' and a.f_status = true
                    order by CASE a.f_status WHEN true THEN 1 else 2 end , e_supplier_name
                        ");

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
            $id             = trim($data['id']);
            $isupplier      = trim($data['i_supplier']);
            $isuppliergroup = trim($data['i_supplier_group']);
            $ipusat         = trim($data['i_kepala_pusat']);
            $ilevelcompany  = trim($data['i_level']);
            $i_menu = $data['i_menu'];
            $folder     = $data['folder'];
            $data = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$isupplier/$isuppliergroup/$ipusat/$ilevelcompany/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$isupplier/$isuppliergroup/$ipusat/$ilevelcompany/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('i_supplier_group');
        $datatables->hide('i_level');
        $datatables->hide('i_kepala_pusat');
        $datatables->hide('folder');
        $datatables->hide('id_company');

        return $datatables->generate();
    }

    public function status($id)
    {
        $this->db->select('f_status, i_supplier');
        $this->db->from('tr_supplier');
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

            $data = array(
                'f_status' => $stat
            );
            $this->db->where('i_supplier', $row->i_supplier);
            $this->db->update('tr_supplier', $data);
        }
       
    }


    public function getsuppliergroup($id)
    {
        return $this->db->query("SELECT i_supplier_group FROM tr_supplier_group WHERE i_supplier_group='$id'", FALSE);
    }

    public function get_bank($idcompany)
    {
        return $this->db->query("SELECT i_bank, e_bank_name FROM tr_bank WHERE id_company = '$idcompany' ORDER BY e_bank_name", FALSE)->result();
    }

    public function get_type_pajak()
    {
        return $this->db->query("SELECT i_type_pajak, e_type_pajak_name from tr_type_pajak", FALSE)->result();
    }

    public function getsupplier_group($idcompany)
    {
        return $this->db->query("SELECT * FROM tr_supplier_group WHERE id_company = '$idcompany' AND e_supplier_group_name NOT IN (SELECT e_supplier_group_name FROM tr_supplier_group WHERE e_supplier_group_name ilike '%Customer%')", FALSE)->result();
    }

    public function getjenisindustry($isuppliergroup, $idcompany)
    {
        return $this->db->query("
                                SELECT i_type_industry, e_type_industry_name FROM tr_type_industry WHERE id_company = '$idcompany' AND f_status = 't'
                                ", FALSE);
    }


    public function getbarang($isupplier, $idcompany)
    {
        return $this->db->query("
                                select a.i_kode_kelompok, b.e_nama_kelompok from tr_supplier_kelompokbarang a
                                inner join tr_kelompok_barang b on (a.i_kode_kelompok = b.i_kode_kelompok and a.id_company = b.id_company)
                                where a.i_supplier = '$isupplier' and a.id_company = '$idcompany'
                                ", FALSE);
    }

    public function getmakloon($isupplier, $idcompany)
    {
        return $this->db->query("
                                select a.i_type_makloon, c.e_type_makloon_name from tr_supplier_makloon a
                                inner join tr_type_makloon c on (a.i_type_makloon = c.i_type_makloon and a.id_company = c.id_company)
                                where a.i_supplier = '$isupplier' and a.id_company = '$idcompany'
                                ", FALSE);
    }


    public function cekkategori($ijenismakloon)
    {
        if ($ijenismakloon == '') {
            return $this->db->query("
                                    SELECT DISTINCT
                                        a.i_kode_kelompok,
                                        b.e_nama
                                    FROM
                                        tr_material a
                                        LEFT JOIN tr_kelompok_barang b 
                                        ON (a.i_kode_kelompok = b.i_kode_kelompok)
                                    ORDER BY 
                                        a.i_kode_kelompok
                                    ", FALSE);
        } else {
            return $this->db->query("
                                    SELECT 
                                        i_kode_kelompok, 
                                        e_nama 
                                    FROM 
                                        tr_kelompok_barang 
                                    WHERE 
                                        i_kode_group_barang 
                                        IN (
                                            SELECT 
                                                i_kode_group_barang 
                                            FROM 
                                                tr_type_makloon 
                                            WHERE 
                                                i_type_makloon = '$ijenismakloon'
                                        )
                                    ORDER BY
                                        i_kode_kelompok
                                    ", FALSE);
        }
    }

    public function cek_data($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        return $this->db->query("
                                SELECT
                                    a.*,
                                    b.e_type_industry_name,
                                    c.e_level_name,
                                    f.e_supplier_group_name,
                                    a.i_level,
                                    g.e_bank_name
                                FROM
                                    tr_supplier a
                                    LEFT JOIN tr_type_industry b 
                                    ON (a.i_type_industry = b.i_type_industry)
                                    LEFT JOIN tr_level_perusahaan c
                                    ON (a.i_level = c.i_level)
                                    LEFT JOIN tr_supplier_group f
                                    ON (a.i_supplier_group = f.i_supplier_group)
                                    LEFT JOIN tr_bank g
                                    ON (a.i_bank = g.i_bank)
                                WHERE 
                                    a.i_supplier = '$id'
                                AND 
                                    a.id_company = '$idcompany'
                                ", FALSE);
    }

    function get_type_industry($idcompany)
    {
        $this->db->select('*');
        $this->db->from('tr_type_industry');
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    function get_level_company($idcompany)
    {
        return $this->db->query("
                                SELECT
                                    *
                                FROM
                                    tr_level_perusahaan
                                WHERE
                                    id_company = '$idcompany'
                                ", FALSE);
    }

    public function cek_data_edit($isupplier, $isupplierold)
    {
        return $this->db->query("
                                SELECT i_supplier from tr_supplier where i_supplier = '$isupplier' and i_supplier <> '$isupplierold'
                                ", FALSE);
    }

    public function bacakategoriproduct($isupplier, $itypemakloon)
    {
        $cek =  $this->db->query("
                                SELECT 
                                    i_supplier_group 
                                FROM 
                                    tr_supplier_group 
                                WHERE 
                                    i_supplier_group IN (SELECT i_supplier_group FROM tr_supplier WHERE i_supplier = '$isupplier' AND i_type_makloon is null) 
                                ", FALSE);
        if ($cek->num_rows() > 0) {
            return $this->db->query("
                                    SELECT DISTINCT
                                        a.i_kode_kelompok,
                                        b.e_nama
                                    FROM
                                        tr_material a
                                        LEFT JOIN  tr_kelompok_barang b 
                                        ON (a.i_kode_kelompok = b.i_kode_kelompok)
                                    WHERE 
                                        a.i_kode_kelompok 
                                        NOT IN (
                                            SELECT 
                                                i_kode_kelompok
                                            FROM 
                                                tr_supplier
                                            WHERE 
                                                i_supplier = '$isupplier'
                                        )
                                    ORDER BY 
                                        a.i_kode_kelompok
                                    ", FALSE)->result();
        } else {
            return $this->db->query("
                                SELECT 
                                    i_kode_kelompok, 
                                    e_nama 
                                FROM 
                                    tr_kelompok_barang 
                                WHERE 
                                    i_kode_group_barang 
                                    IN (
                                        SELECT 
                                            i_kode_group_barang 
                                        FROM 
                                            tr_type_makloon 
                                        WHERE 
                                            i_type_makloon = '$itypemakloon'
                                    )
                                    AND
                                    i_kode_kelompok 
                                    NOT IN (
                                        SELECT
                                            i_kode_kelompok
                                        FROM 
                                            tr_supplier
                                        WHERE 
                                            i_supplier = '$isupplier'
                                    )
                                ORDER BY
                                        i_kode_kelompok
                                ", FALSE)->result();
        }
    }

    public function bacakategoriproduk($isupplier)
    {
        $query = $this->db->query("
                                    SELECT 
                                        a.i_kode_kelompok,
                                        b.e_nama
                                    FROM 
                                        tr_supplier a
                                        LEFT JOIN tr_kelompok_barang b
                                        ON (a.i_kode_kelompok = b.i_kode_kelompok)
                                    WHERE 
                                        a.i_supplier = '$isupplier'
                                    ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function getpusat($isuppliergroup, $ilevelcompany, $idcompany)
    {
        return $this->db->query("
                                SELECT 
                                    i_supplier as i_kepala_pusat, 
                                    e_supplier_name as e_pusat, 
                                    i_level as level
                                FROM 
                                    tr_supplier 
                                WHERE 
                                    f_status = 't'
                                    AND i_supplier_group = '$isuppliergroup'
                                    AND i_level = 'PLV00'
                                    AND id_company = '$idcompany'
                                ", FALSE);
    }

    function get_level_company_edit()
    {
        return $this->db->query("
                                SELECT
                                    *
                                FROM
                                    tr_level_perusahaan
                                WHERE
                                    i_level != 'PLV1001'
                                ", FALSE);
    }

    function getkategoriproduct($ijenismakloon, $idcompany)
    {
        return $this->db->query(" 
                                SELECT 
                                    a.i_kode_kelompok, 
                                    a.e_nama_kelompok || ' - ' || b.e_nama_group_barang as e_nama
                                FROM tr_kelompok_barang a
                                LEFT JOIN tr_group_barang b on (b.i_kode_group_barang = a.i_kode_group_barang AND a.id_company = b.id_company)
                                WHERE a.id_company = '$idcompany'
                                ORDER BY b.i_kode_group_barang, a.e_nama_kelompok
                                ", FALSE);
    }

    function getkategoriproduct2($cari, $idcompany)
    {
        return $this->db->query("
                                WITH cte as (
                                    SELECT 
                                    a.i_kode_kelompok, 
                                    a.e_nama_kelompok || ' - ' || b.e_nama_group_barang as e_nama
                                    FROM tr_kelompok_barang a
                                    LEFT JOIN tr_group_barang b on (b.i_kode_group_barang = a.i_kode_group_barang and a.id_company = b.id_company)
                                    WHERE a.id_company='$idcompany'
                                    ORDER BY b.i_kode_group_barang, a.e_nama_kelompok
                                )
                                select * from cte where e_nama ilike '%$cari%'
                               
                                ", FALSE);
    }

    function getmakloon2($cari)
    {
        return $this->db->query("
                                WITH cte as (
                                    SELECT 
                                    a.i_type_makloon, 
                                    a.e_type_makloon_name as e_nama
                                    FROM tr_type_makloon a
                                    ORDER BY e_type_makloon_name
                                )
                                select * from cte where e_nama ilike '%$cari%'
                               
                                ", FALSE);
    }

    public function getkategoripembelian()
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query(" 
                                SELECT DISTINCT
                                    a.i_kode_kelompok,
                                    b.e_nama_kelompok as e_nama
                                FROM tr_material a
                                LEFT JOIN  tr_kelompok_barang b  ON (a.i_kode_kelompok = b.i_kode_kelompok AND a.id_company = b.id_company)
                                WHERE a.id_company = '$idcompany'
                                ORDER BY b.e_nama_kelompok
                                ", FALSE);
    }

    public function deletedetail($isupplier, $idcompany)
    {
        $this->db->query(" DELETE FROM tr_supplier_makloon WHERE i_supplier='$isupplier' AND id_company = '$idcompany'");
        $this->db->query(" DELETE FROM tr_supplier_kelompokbarang WHERE i_supplier='$isupplier' AND id_company = '$idcompany'");
    }

    function get_jenismakloon()
    {
        $this->db->select('*');
        $this->db->from('tr_type_makloon');
        return $this->db->get();
    }


    public function insert($isupplier, $isuppliername, $isupplieraddres, $esupplierownername, $isupplierphone, $isupplierfax, $isupplierpostalcode, $isuppliercity, $pkp, $ftipepajak, $isuppliernpwp, $esuppliernpwpname, $isuppliernpwpaddress, $isuppliertoplength, $isuppliergroup, $itypeindustry, $ilevelcompany, $enamabank, $inorekening, $enamarekening, $isupplierdiskon, $ikepalapusat, $idcompany, $jenis_pembelian, $inter_exter)
    {
        $inter_exter = ($inter_exter=='') ? null : $inter_exter ;
        $data = array(
            'i_supplier'              => $isupplier,
            'e_supplier_name'         => $isuppliername,
            'e_supplier_address'      => $isupplieraddres,
            'e_supplier_ownername'    => $esupplierownername,
            'e_supplier_phone'        => $isupplierphone,
            'e_supplier_fax'          => $isupplierfax,
            'e_supplier_postalcode'   => $isupplierpostalcode,
            'e_supplier_city'         => $isuppliercity,
            'f_pkp'                   => $pkp,
            'i_type_pajak'            => $ftipepajak,
            'i_supplier_npwp'         => $isuppliernpwp,
            'e_npwp_name'             => $esuppliernpwpname,
            'e_npwp_address'          => $isuppliernpwpaddress,
            'n_supplier_toplength'    => $isuppliertoplength,
            'i_supplier_group'        => $isuppliergroup,
            'i_type_industry'         => $itypeindustry,
            'i_level'                 => $ilevelcompany,
            'i_bank'                  => $enamabank,
            'i_no_rekening'           => $inorekening,
            'e_nama_rekening'         => $enamarekening,
            'n_diskon'                => $isupplierdiskon,
            'i_kepala_pusat'          => $ikepalapusat,
            'id_company'              => $idcompany,
            'd_entry'                 => current_datetime(),
            'jenis_pembelian'         => $jenis_pembelian,
            'id_kategori_jahit'       => $inter_exter
        );
        $this->db->insert('tr_supplier', $data);
    }

    public function insert_kelompokbarang($isupplier, $ikategoriproduk, $idcompany)
    {
        $data = array(
            'i_supplier'       => $isupplier,
            'i_kode_kelompok'  => $ikategoriproduk,
            'id_company'       => $idcompany,
        );
        $this->db->insert('tr_supplier_kelompokbarang', $data);
    }

    public function insert_makloon($isupplier, $ijenismakloon, $idcompany)
    {
        $data = array(
            'i_supplier'        => $isupplier,
            'i_type_makloon'    => $ijenismakloon,
            'id_company'        => $idcompany,
        );
        $this->db->insert('tr_supplier_makloon', $data);
    }

    public function get_data($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        return $this->db->query("
                                SELECT
                                    a.*,
                                    b.e_type_industry_name,
                                    c.e_level_name,
                                    f.e_supplier_group_name,
                                    a.i_level,
                                    g.e_bank_name
                                FROM
                                    tr_supplier a
                                    LEFT JOIN tr_type_industry b 
                                    ON (a.i_type_industry = b.i_type_industry)
                                    LEFT JOIN tr_level_perusahaan c
                                    ON (a.i_level = c.i_level)
                                    LEFT JOIN tr_supplier_group f
                                    ON (a.i_supplier_group = f.i_supplier_group)
                                    LEFT JOIN tr_bank g
                                    ON (a.i_bank = g.i_bank)
                                WHERE 
                                    a.id = '$id'
                                AND 
                                    a.id_company = '$idcompany'
                                ", FALSE);
    }

    //update
    public function update($id, $isupplierold, $isupplier, $isuppliername, $isupplieraddres, $esupplierownername, $isupplierphone, $isupplierfax, $isupplierpostalcode, $isuppliercity, $pkp, $ftipepajak, $isuppliernpwp, $esuppliernpwpname, $isuppliernpwpaddress, $isuppliertoplength, $isuppliergroup, $itypeindustry, $ilevelcompany, $enamabank, $inorekening, $enamarekening, $isupplierdiskon, $ikepalapusat, $idcompany, $jenis_pembelian, $inter_exter)
    {
        $inter_exter = ($inter_exter=='') ? null : $inter_exter ;
        $data = array(
            'i_supplier'              => $isupplier,
            'e_supplier_name'         => $isuppliername,
            'e_supplier_address'      => $isupplieraddres,
            'e_supplier_ownername'    => $esupplierownername,
            'e_supplier_phone'        => $isupplierphone,
            'e_supplier_fax'          => $isupplierfax,
            'e_supplier_postalcode'   => $isupplierpostalcode,
            'e_supplier_city'         => $isuppliercity,
            'f_pkp'                   => $pkp,
            'i_type_pajak'            => $ftipepajak,
            'i_supplier_npwp'         => $isuppliernpwp,
            'e_npwp_name'             => $esuppliernpwpname,
            'e_npwp_address'          => $isuppliernpwpaddress,
            'n_supplier_toplength'    => $isuppliertoplength,
            'i_supplier_group'        => $isuppliergroup,
            'i_type_industry'         => $itypeindustry,
            'i_level'                 => $ilevelcompany,
            'i_bank'                  => $enamabank,
            'i_no_rekening'           => $inorekening,
            'e_nama_rekening'         => $enamarekening,
            'n_diskon'                => $isupplierdiskon,
            'i_kepala_pusat'          => $ikepalapusat,
            'd_entry'                 => current_datetime(),
            'jenis_pembelian'         => $jenis_pembelian,
            'id_kategori_jahit'       => $inter_exter
        );
        $this->db->where('i_supplier', $isupplierold);
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_supplier', $data);
    }
}

/* End of file Mmaster.php */