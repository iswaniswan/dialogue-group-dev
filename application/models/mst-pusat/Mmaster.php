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
                           SELECT 0 as no,
                                a.id,
                                a.i_customer, 
                                a.e_customer_name, 
                                a.i_supplier_group, 
                                b.e_supplier_group_name, 
                                d.e_level_name,
                                c.e_city_name, 
                                a.e_customer_phone, 
                                a.i_level,
                                a.i_kepala_pusat,
                                a.f_status,
                                a.id_company,
                                a.id_area,
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
                                tr_customer a
                                LEFT JOIN tr_supplier_group b
                                 ON a.i_supplier_group = b.i_supplier_group and a.id_company = b.id_company
                                LEFT JOIN tr_city c
                                 ON a.id_city = c.id
                                LEFT JOIN tr_level_perusahaan d
                                 ON a.i_level =  d.i_level and a.id_company = d.id_company
                            WHERE
                                a.id_company = '$idcompany'
                            ORDER BY a.d_update DESC", false);

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
            $f_status       = trim($data['f_status']);
            $ilevelcompany  = trim($data['i_level']);
            $icustomergroup = trim($data['i_supplier_group']);
            $ipusat         = trim($data['i_kepala_pusat']);
            $iarea          = $data['id_area'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $data           = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$icustomergroup/$ilevelcompany/$iarea/\",\"#main\"); return false;'><i class='fa-lg ti-eye text-success mr-3'></i></a>";
            }
            if (check_role($i_menu, 3) && $f_status != 'f') {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$icustomergroup/$ilevelcompany/$iarea/\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg'></i></a>";
            }
            return $data;
        });

        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');
        $datatables->hide('id');
        $datatables->hide('f_status');
        $datatables->hide('i_supplier_group');
        $datatables->hide('i_level');
        $datatables->hide('i_kepala_pusat');
        $datatables->hide('id_area');

        return $datatables->generate();
    }

    public function status($id)
    {
        $this->db->select('f_status');
        $this->db->from('tr_customer');
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
        $this->db->update('tr_customer', $data);
    }

    public function cek_data($icustomer)
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
                                    tr_customer a
                                    LEFT JOIN tr_type_industry b 
                                    ON (a.i_type_industry = b.i_type_industry and a.id_company = b.id_company)
                                    LEFT JOIN tr_level_perusahaan c
                                    ON (a.i_level = c.i_level and a.id_company = c.id_company)
                                    LEFT JOIN tr_supplier_group f
                                    ON (a.i_supplier_group = f.i_supplier_group and a.id_company = f.id_company)
                                    LEFT JOIN tr_bank g
                                    ON (a.i_bank = g.i_bank)
                                WHERE 
                                    a.i_customer = '$icustomer'
                                AND 
                                    a.id_company = '$idcompany'
                                ", FALSE);
    }

    public function bank()
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT i_bank, e_bank_name FROM tr_bank WHERE id_company = '$idcompany' ORDER BY e_bank_name", FALSE)->result();
    }

    public function area()
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT id as id_area, i_area, e_area FROM tr_area ORDER BY e_area", FALSE)->result();
    }

    public function get_group()
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_supplier_group WHERE id_company = '$idcompany' AND e_supplier_group_name IN (SELECT e_supplier_group_name FROM tr_supplier_group WHERE e_supplier_group_name ilike '%Customer%' or e_supplier_group_name ilike '%Distributor%')", FALSE)->result();
    }

    public function get_type_industry()
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('*');
        $this->db->from('tr_type_industry');
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    public function get_level_company()
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('*');
        $this->db->from('tr_level_perusahaan');
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    public function getpusat($isuppliergroup, $ilevelcompany)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
                                SELECT 
                                    i_customer as i_kepala_pusat, 
                                    e_customer_name as e_pusat, 
                                    i_level as level
                                FROM 
                                    tr_customer 
                                WHERE 
                                    f_status = 't'
                                    AND i_supplier_group = '$isuppliergroup'
                                    AND i_level = 'PLV00'
                                    AND id_company = '$idcompany'
                                ", FALSE);
    }

    public function getcity($iarea)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
                                SELECT 
                                    id,
                                    i_city,
                                    e_city_name
                                FROM 
                                    tr_city 
                                WHERE 
                                    f_status = 't'
                                AND     
                                    id_area = '$iarea'
                                ORDER BY 
                                    e_city_name
                                ", FALSE);
    }

    public function harga()
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
                                    SELECT
                                       id,
                                       i_harga,
                                       e_harga 
                                    FROM
                                       tr_harga_kode 
                                    WHERE
                                       id_company = '$idcompany' 
                                    ORDER BY
                                       e_harga

                                ", FALSE)->result();
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tr_customer');
        return $this->db->get()->row()->id + 1;
    }

    public function insert($id, $icustomer, $ecustomername, $ecustomeraddress, $ecity, $epostalcode, $ecustomerphone, $ecustomerfax, $ecustomercontact, $ncustomerdiscount, $fkonsinyasi, $ncustomertop, $fpkp, $icustomernpwp, $ecustomernpwpname, $icustomerpwpaddress, $ibank, $inorekening, $enamarekening, $igroup, $itypeindustry, $ilevelcompany, $ikepalapusat, $ncustomerdiscount2, $ncustomerdiscount3, $iarea, $iharga, $e_shipping_address, $e_billing_address)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id'                      => $id,
            'i_customer'              => $icustomer,
            'e_customer_name'         => $ecustomername,
            'e_customer_address'      => $ecustomeraddress,
            'id_city'                 => $ecity,
            'e_customer_postalcode'   => $epostalcode,
            'e_customer_phone'        => $ecustomerphone,
            'e_customer_fax'          => $ecustomerfax,
            'e_customer_contact'      => $ecustomercontact,
            'v_customer_discount'     => $ncustomerdiscount,
            'f_customer_konsinyasi'   => $fkonsinyasi,
            'n_customer_toplength'    => $ncustomertop,
            'f_pkp'                   => $fpkp,
            'i_customer_npwp'         => $icustomernpwp,
            'e_customer_npwp'         => $ecustomernpwpname,
            'i_customer_npwp_address' => $icustomerpwpaddress,
            'i_bank'                  => $ibank,
            'i_no_rekening'           => $inorekening,
            'e_nama_rekening'         => $enamarekening,
            'i_supplier_group'        => $igroup,
            'i_type_industry'         => $itypeindustry,
            'i_level'                 => $ilevelcompany,
            'i_kepala_pusat'          => $ikepalapusat,
            'id_company'              => $idcompany,
            'id_area'                 => $iarea,
            'v_customer_discount2'    => $ncustomerdiscount2,
            'v_customer_discount3'    => $ncustomerdiscount3,
            'id_harga_kode'           => $iharga,
            'd_entry'                 => current_datetime(),
            'e_shipping_address'      => $e_shipping_address,
            'e_billing_address'       => $e_billing_address
        );
        $this->db->insert('tr_customer', $data);
    }

    public function get_data($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT a.*, b.e_city_name
                                    FROM tr_customer  a
                                    left JOIN tr_city b
                                    ON a.id_city = b.id
                                    WHERE a.id = '$id' 
                                    AND a.id_company = '$idcompany'", FALSE);
    }

    public function update($id, $icustomer, $ecustomername, $ecustomeraddress, $ecity, $epostalcode, $ecustomerphone, $ecustomerfax, $ecustomercontact, $ncustomerdiscount, $fkonsinyasi, $ncustomertop, $fpkp, $icustomernpwp, $ecustomernpwpname, $icustomerpwpaddress, $ibank, $inorekening, $enamarekening, $igroup, $itypeindustry, $ilevelcompany, $ikepalapusat, $ncustomerdiscount2, $ncustomerdiscount3, $iarea, $iharga, $e_shipping_address, $e_billing_address)
    {
        $data = array(
            'i_customer'              => $icustomer,
            'e_customer_name'         => $ecustomername,
            'e_customer_address'      => $ecustomeraddress,
            'id_city'                 => $ecity,
            'e_customer_postalcode'   => $epostalcode,
            'e_customer_phone'        => $ecustomerphone,
            'e_customer_fax'          => $ecustomerfax,
            'e_customer_contact'      => $ecustomercontact,
            'v_customer_discount'     => $ncustomerdiscount,
            'f_customer_konsinyasi'   => $fkonsinyasi,
            'n_customer_toplength'    => $ncustomertop,
            'f_pkp'                   => $fpkp,
            'i_customer_npwp'         => $icustomernpwp,
            'e_customer_npwp'         => $ecustomernpwpname,
            'i_customer_npwp_address' => $icustomerpwpaddress,
            'i_bank'                  => $ibank,
            'i_no_rekening'           => $inorekening,
            'e_nama_rekening'         => $enamarekening,
            'i_supplier_group'        => $igroup,
            'i_type_industry'         => $itypeindustry,
            'i_level'                 => $ilevelcompany,
            'i_kepala_pusat'          => $ikepalapusat,
            'id_area'                 => $iarea,
            'v_customer_discount2'    => $ncustomerdiscount2,
            'v_customer_discount3'    => $ncustomerdiscount3,
            'id_harga_kode'           => $iharga,
            'd_update'                => current_datetime(),
            'e_shipping_address'      => $e_shipping_address,
            'e_billing_address'       => $e_billing_address
        );

        $this->db->where('id', $id);
        $this->db->update('tr_customer', $data);
    }
}
/* End of file Mmaster.php */