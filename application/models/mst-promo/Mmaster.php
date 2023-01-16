<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and = "AND d_promo BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and = "";
        }
		$datatables = new Datatables(new CodeigniterAdapter);

		$datatables->query("SELECT
                0 AS NO,
                id_promo AS id,
                i_promo_code,
                to_char(d_promo, 'DD FMMonth YYYY') AS d_promo,
                e_promo_name,
                b.e_promo_type_name,
                to_char(d_promo_start, 'DD FMMonth YYYY') AS d_promo_start ,
                to_char(d_promo_finish, 'DD FMMonth YYYY') AS d_promo_finish, 
                CASE
                    WHEN a.f_status = FALSE THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END AS status,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_promo a
            INNER JOIN tr_promo_type b ON
                (b.id_promo_type = a.id_promo_type)
            WHERE
                a.id_company = '$this->id_company'
                $and", fALSE);

        $datatables->edit('status', function ($data) {
            $id         = trim($data['id']);
            $folder     = $this->folder;
            $status     = $data['status'];
            if ($status=='Aktif') {
                $warna = 'success';
            }else{
                $warna = 'danger';
            }
            $data    = '';
            if(check_role($this->i_menu, 3)){
                $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
            }else{
                $data   .= "<span class=\"label label-$warna\">$status</span>";
            }
            return $data;
        });

		$datatables->add('action', function ($data) {
            $id     = trim($data['id']);
            $folder = $this->folder;
            $dfrom  = $data['dfrom'];
            $dto    = $data['dto'];
            $data   = '';
            if(check_role($this->i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            if(check_role($this->i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id');

        return $datatables->generate();
	}

    public function status($id)
    {
        $this->db->select('f_status');
        $this->db->from('tm_promo');
        $this->db->where('id_promo', $id);
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
        $this->db->where('id_promo', $id);
        $this->db->update('tm_promo', $data);
    }

    /** Get Type */
    public function get_type($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                id_promo_type, 
                e_promo_type_name 
            FROM 
                tr_promo_type
            WHERE 
                (e_promo_type_name ILIKE '%$cari%')
                AND id_company = '$this->id_company' 
                AND f_status = 't' 
            ORDER BY 1 ASC
        ", FALSE);
    }

    /** Get Valid */
    public function get_valid($id_promo_type)
    {
        return $this->db->query("SELECT 
                n_valid
            FROM 
                tr_promo_type
            WHERE 
                id_promo_type = '$id_promo_type'
        ", FALSE);
    }

    /** Get Group */
    public function get_group($cari)
    {
        return $this->db->query("SELECT 
                DISTINCT
                id, 
                i_harga, 
                e_harga 
            FROM 
                tr_harga_kode
            WHERE 
                (e_harga ILIKE '%$cari%' OR i_harga ILIKE '%$cari%')
                AND id_company = '$this->id_company' 
                AND f_status = 't' 
            ORDER BY 2 ASC
        ", FALSE);
    }

    public function bacajenis(){
        return $this->db->order_by('id_promo_type','ASC')->get_where('tr_promo_type',['id_company'=> $this->id_company])->result();
    }

    public function bacagroup(){
        $this->db->select('*');
        $this->db->from('tr_brand');
        // $this->db->where('f_spb', 'true');
        $this->db->order_by('e_brand_name');
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            return $query->result();
        }
    }

    public function product($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.id AS kode,
                a.i_product_base || ' - '||e_product_basename AS nama
            FROM
                tr_product_base a
            WHERE
                (i_product_base ILIKE '%$cari%'
                OR e_product_basename ILIKE '%$cari%')
                AND id_company = '$this->id_company' ", 
        FALSE);
    }

    public function get_detail_product($iproduct){
        return $this->db->query("
            SELECT
                v_unitprice
            FROM
                tr_product_base
            WHERE
                id  = '$iproduct'", 
        FALSE);
    }

    public function customer($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                id,
                i_customer,
                e_customer_name
            FROM
                tr_customer
            WHERE
                (UPPER(i_customer) LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%')
                AND id_company = '$this->id_company'", 
        FALSE);
    }

    public function get_detail_customer($icustomer){
        return $this->db->query("
            SELECT
                e_customer_address
            FROM
                tr_customer
            WHERE
                id = '$icustomer'", 
        FALSE);
    }

    public function get_area($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                id AS i_area,
                i_area AS kode,
                e_area AS e_area_name
            FROM
                tr_area
            WHERE
                (UPPER(i_area) ILIKE '%$cari%'
                OR UPPER(e_area) ILIKE '%$cari%')
            ORDER BY i_area", 
        FALSE);
    }

    public function getarea($iarea){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area = '$iarea'", 
        FALSE);
    }

    public function runningnumber(){
        $query  = $this->db->query("SELECT to_char(current_timestamp,'yymm') as c");
        $row    = $query->row();
        $thbl   = $row->c;
        $th     = substr($thbl,0,2);
        $this->db->select(" 
                MAX(substr(i_promo_code, 9, 5)) AS MAX
            FROM
                tm_promo
            WHERE
                substr(i_promo_code,
                4,
                2)= '$th' ", 
        false);
        $query = $this->db->get();        
        if($query->num_rows() > 0){            
            foreach($query->result() as $row){              
                $terakhir=$row->max;
            }
            $nopromo  =$terakhir+1;
            settype($nopromo,"string");
            $a=strlen($nopromo);
            while($a<5){              
                $nopromo="0".$nopromo;
                $a=strlen($nopromo);
            }
            $nopromo  ="PR-".$thbl."-".$nopromo;
            return $nopromo;
        }else{
            $nopromo  ="00001";
            $nopromo  ="PR-".$thbl."-".$nopromo;
            return $nopromo;
        }
    }

    public function save($i_promo_code){
        $query = $this->db->query("SELECT max(id_promo)+1 AS id FROM tm_promo", TRUE);
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

        $f_all_product = ($this->input->post('f_all_product') == 'on') ? TRUE : FALSE;
        $f_all_customer = ($this->input->post('f_all_customer') == 'on') ? TRUE : FALSE;
        $f_all_area = ($this->input->post('f_all_area') == 'on') ? TRUE : FALSE;
        $f_customer_group = ($this->input->post('f_customer_group') == 'on') ? TRUE : FALSE;
        $f_product_group = ($this->input->post('f_product_group') == 'on') ? TRUE : FALSE;
        $n_promo_discount1 = ($this->input->post('n_promo_discount1') != '') ? str_replace(",", "", $this->input->post('n_promo_discount1')) : 0;
        $n_promo_discount2 = ($this->input->post('n_promo_discount2') != '') ? str_replace(",", "", $this->input->post('n_promo_discount2')) : 0;
        $table = array(
            'id_company'        => $this->id_company,
            'id_promo'          => $id,
            'i_promo_code'      => $i_promo_code,
            'id_promo_type'     => $this->input->post('id_promo_type'),
            'e_promo_name'      => ucwords(strtolower($this->input->post('e_promo_name'))),
            'd_promo'           => date('Y-m-d', strtotime($this->input->post('d_promo'))),
            'd_promo_start'     => date('Y-m-d', strtotime($this->input->post('d_promo_start'))),
            'd_promo_finish'    => date('Y-m-d', strtotime($this->input->post('d_promo_finish'))),
            'id_harga'          => $this->input->post('id_harga'),
            'n_promo_discount1' => $n_promo_discount1,
            'n_promo_discount2' => $n_promo_discount2,
            'f_all_product'     => $f_all_product,
            'f_all_customer'    => $f_all_customer,
            'f_all_area'        => $f_all_area,
            'f_customer_group'  => $f_customer_group,
            'f_product_group'   => $f_product_group,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_promo', $table);

        /* Simpan Promo Product */
        if ($f_all_product == FALSE) {
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                $i = 0;
                foreach ($this->input->post('i_product') as $i_product) {
                    if ($i_product != '' || $i_product != null) {
                        $harga = ($this->input->post('v_unit_price')[$i] != '') ? str_replace(",", "", $this->input->post('v_unit_price')[$i]) : 0;
                        $item = array(
                            'id_promo'              => $id,
                            'id_product'            => $i_product,
                            'v_unit_price'          => $harga,
                            'n_quantity_min'        => $this->input->post('n_quantity_min')[$i],
                        );
                        $this->db->insert('tm_promo_item', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Customer */
        if ($f_all_customer == FALSE) {
            if (is_array($this->input->post('i_customer')) || is_object($this->input->post('i_customer'))) {
                $i = 0;
                foreach ($this->input->post('i_customer') as $i_customer) {
                    if ($i_customer != '' || $i_customer != null) {
                        $item = array(
                            'id_promo'     => $id,
                            'id_customer'  => $i_customer,
                        );
                        $this->db->insert('tm_promo_customer', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Area */
        if ($f_all_area == FALSE) {
            if (is_array($this->input->post('i_area')) || is_object($this->input->post('i_area'))) {
                $i = 0;
                foreach ($this->input->post('i_area') as $i_area) {
                    if ($i_area != '' || $i_area != null) {
                        $item = array(
                            'id_promo'  => $id,
                            'id_area'   => $i_area,
                        );
                        $this->db->insert('tm_promo_area', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }
    }

    /** Get Data Untuk Edit */
    public function get_data($id)
    {
        return $this->db->query("SELECT
                a.*,
                to_char(a.d_promo,'DD FMMonth YYYY') AS dpromo,
                to_char(a.d_promo_start,'DD FMMonth YYYY') AS dstart,
                to_char(a.d_promo_finish,'DD FMMonth YYYY') AS dfinish,
                b.e_promo_type_name,
	            c.e_harga
            FROM
                tm_promo a
            INNER JOIN tr_promo_type b ON
                (b.id_promo_type = a.id_promo_type)
            INNER JOIN tr_harga_kode c ON 
                (c.id = a.id_harga)
            WHERE
                a.id_promo = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Detail */
    public function get_data_detail($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_product_base,
                initcap(b.e_product_basename) AS e_product_name
            FROM
                tm_promo_item a
            INNER JOIN tr_product_base b ON
                (b.id = a.id_product)
            WHERE
                a.id_promo = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Customer */
    public function get_data_customer($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_customer,
                b.e_customer_name,
                b.e_customer_address
            FROM
                tm_promo_customer a
            INNER JOIN tr_customer b ON
                (b.id = a.id_customer)
            WHERE
                a.id_promo = '$id'
        ", FALSE);
    }

    /** Get Data Untuk Edit Area */
    public function get_data_area($id)
    {
        return $this->db->query("SELECT
                a.*,
                b.i_area,
                b.e_area AS e_area_name
            FROM
                tm_promo_area a
            INNER JOIN tr_area b ON
                (b.id = a.id_area)
            WHERE
                a.id_promo = '$id'
        ", FALSE);
    }

    /** Update Data */
    public function update()
    {
        $id = $this->input->post('id');
        $f_all_product = ($this->input->post('f_all_product') == 'on') ? TRUE : FALSE;
        $f_all_customer = ($this->input->post('f_all_customer') == 'on') ? TRUE : FALSE;
        $f_all_area = ($this->input->post('f_all_area') == 'on') ? TRUE : FALSE;
        $f_customer_group = ($this->input->post('f_customer_group') == 'on') ? TRUE : FALSE;
        $f_product_group = ($this->input->post('f_product_group') == 'on') ? TRUE : FALSE;
        $n_promo_discount1 = ($this->input->post('n_promo_discount1') != '') ? str_replace(",", "", $this->input->post('n_promo_discount1')) : 0;
        $n_promo_discount2 = ($this->input->post('n_promo_discount2') != '') ? str_replace(",", "", $this->input->post('n_promo_discount2')) : 0;
        $table = array(
            'id_company'        => $this->id_company,
            'id_promo'          => $id,
            'id_promo_type'     => $this->input->post('id_promo_type'),
            'e_promo_name'      => ucwords(strtolower($this->input->post('e_promo_name'))),
            'd_promo'           => date('Y-m-d', strtotime($this->input->post('d_promo'))),
            'd_promo_start'     => date('Y-m-d', strtotime($this->input->post('d_promo_start'))),
            'd_promo_finish'    => date('Y-m-d', strtotime($this->input->post('d_promo_finish'))),
            'id_harga'          => $this->input->post('id_harga'),
            'n_promo_discount1' => $n_promo_discount1,
            'n_promo_discount2' => $n_promo_discount2,
            'f_all_product'     => $f_all_product,
            'f_all_customer'    => $f_all_customer,
            'f_all_area'        => $f_all_area,
            'f_customer_group'  => $f_customer_group,
            'f_product_group'   => $f_product_group,
            'd_entry'           => current_datetime(),
        );
        $this->db->where('id_promo', $id);
        $this->db->update('tm_promo', $table);

        /* Simpan Promo Product */
        if ($f_all_product == FALSE) {
            if (is_array($this->input->post('i_product')) || is_object($this->input->post('i_product'))) {
                $this->db->where('id_promo', $id);
                $this->db->delete('tm_promo_item');
                $i = 0;
                foreach ($this->input->post('i_product') as $i_product) {
                    if ($i_product != '' || $i_product != null) {
                        $harga = ($this->input->post('v_unit_price')[$i] != '') ? str_replace(",", "", $this->input->post('v_unit_price')[$i]) : 0;
                        $item = array(
                            'id_promo'              => $id,
                            'id_product'            => $i_product,
                            'v_unit_price'          => $harga,
                            'n_quantity_min'        => $this->input->post('n_quantity_min')[$i],
                        );
                        $this->db->insert('tm_promo_item', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Customer */
        if ($f_all_customer == FALSE) {
            if (is_array($this->input->post('i_customer')) || is_object($this->input->post('i_customer'))) {
                $this->db->where('id_promo', $id);
                $this->db->delete('tm_promo_customer');
                $i = 0;
                foreach ($this->input->post('i_customer') as $i_customer) {
                    if ($i_customer != '' || $i_customer != null) {
                        $item = array(
                            'id_promo'     => $id,
                            'id_customer'  => $i_customer,
                        );
                        $this->db->insert('tm_promo_customer', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }

        /* Simpan Promo Area */
        if ($f_all_area == FALSE) {
            if (is_array($this->input->post('i_area')) || is_object($this->input->post('i_area'))) {
                $this->db->where('id_promo', $id);
                $this->db->delete('tm_promo_area');
                $i = 0;
                foreach ($this->input->post('i_area') as $i_area) {
                    if ($i_area != '' || $i_area != null) {
                        $item = array(
                            'id_promo'  => $id,
                            'id_area'   => $i_area,
                        );
                        $this->db->insert('tm_promo_area', $item);
                    }
                    $i++;
                }
            } else {
                die;
            }
        }
    }
}

/* End of file Mmaster.php */