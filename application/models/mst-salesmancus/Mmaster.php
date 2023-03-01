<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
	
	function __data($i_menu, $folder){
        $idcompany = $this->session->userdata('id_company');
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                               0 as no,
                               a.id,
                               a.id_company,
                               a.id_area,
                               d.e_area,
                               a.id_customer,
                               b.e_customer_name,
                               a.id_salesman,
                               c.e_sales,
                               a.e_periode,
                               case when a.f_status = TRUE then 'Aktif' else 'Tidak Aktif' end as status, 
                               '$i_menu' as i_menu,
                               '$folder' as folder
                            FROM
                               tr_customer_salesman a 
                               JOIN
                                  tr_customer b 
                                  ON a.id_customer = b.id 
                               JOIN
                                  tr_salesman c 
                                  ON a.id_salesman = c.id 
                               JOIN
                                  tr_area d 
                                  ON a.id_area = d.id
                               WHERE 
                                    a.id_company = '$idcompany'
                            ", FALSE);
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
			$id              = $data['id'];
            $epriode         = trim($data['e_periode']);
            $i_menu          = $data['i_menu'];
            $folder          = $data['folder'];
            $data            = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$epriode\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$epriode\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('id_company');
        $datatables->hide('id_area');
        $datatables->hide('id_salesman');
        $datatables->hide('id_customer');
        $datatables->hide('e_periode');
        $datatables->hide('e_area');
        $datatables->hide('e_customer_name');
        return $datatables->generate();
	}

    public function data($i_menu, $folder, $year=null, $month=null){
        if ($year == null) {
            $year = date('Y');
        }

        if ($month == null) {
            $month = date('m');
        }

        $e_periode = $year.$month;

        $idcompany = $this->session->userdata('id_company');
		$datatables = new Datatables(new CodeigniterAdapter);

        $sql = "SELECT DISTINCT ON (a.id_salesman)
                    0 AS no,
                    a.id,
                    a.id_company,
                    a.id_area,
                    d.e_area,
                    a.id_customer,
                    b.e_customer_name,
                    a.id_salesman,
                    c.e_sales,
                    a.e_periode,
                    case when a.f_status = TRUE then 'Aktif' else 'Tidak Aktif' end as status, 
                    '$i_menu' as i_menu,
                    '$folder' as folder
                FROM tr_customer_salesman a 
                JOIN tr_customer b ON a.id_customer = b.id 
                JOIN tr_salesman c ON a.id_salesman = c.id 
                JOIN tr_area d ON a.id_area = d.id
                WHERE a.id_company = '$idcompany' AND a.e_periode = '$e_periode'";

        // var_dump($sql); die();

        $datatables->query($sql, FALSE);

        $datatables->edit('status', function ($data) {
                    $id         = trim($data['id']);
                    $folder     = $data['folder'];
                    $id_menu    = $data['i_menu'];
                    $status     = $data['status'];
                    
                    $warna = 'danger';
                    if ($status=='Aktif') {
                        $warna = 'success';
                    }

                    $data = "<span class=\"label label-$warna\">$status</span>";
                    if(check_role($id_menu, 3)){
                        $data = "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }

                    return $data;
                }
        );

		$datatables->add('action', function ($data) {
			$id              = $data['id'];
            $epriode         = trim($data['e_periode']);
            $i_menu          = $data['i_menu'];
            $folder          = $data['folder'];
            $id_salesman = $data['id_salesman'];
            $data            = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id_salesman/$epriode\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id_salesman/$epriode\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }

            return $data;
        });
        
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('id_company');
        $datatables->hide('id_area');
        $datatables->hide('id_salesman');
        $datatables->hide('id_customer');
        $datatables->hide('e_periode');
        $datatables->hide('e_area');
        $datatables->hide('e_customer_name');
        return $datatables->generate();
	}

    public function area($cari){
        return $this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tr_area
                                    WHERE
                                        f_status = 't'
                                        AND (i_area ILIKE '%$cari%'
                                        OR e_area ILIKE '%$cari%')
                                ", FALSE);
    }

    public function customer($cari, $idcompany){
        return $this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tr_customer
                                    WHERE
                                        id_company = '$idcompany'
                                    AND
                                        f_status = 't'
                                    AND (i_customer ILIKE '%$cari%'
                                        OR e_customer_name ILIKE '%$cari%')
                                ", FALSE);
    }

    public function salesman($cari, $idcompany){
        return $this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tr_salesman
                                    WHERE
                                        id_company = '$idcompany'
                                    AND
                                        f_status = 't'
                                    AND (i_sales ILIKE '%$cari%'
                                        OR e_sales ILIKE '%$cari%')
                                ", FALSE);
    }

    public function brand($cari, $idcompany){
        return $this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tr_brand
                                    WHERE
                                        id_company = '$idcompany'
                                    AND
                                        f_status = 't'
                                    AND (i_brand ILIKE '%$cari%'
                                        OR e_brand_name ILIKE '%$cari%')
                                ", FALSE);
    }

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tr_customer_salesman');
        return $this->db->get()->row()->id+1;
    }

	function cek_data($iarea, $icustomer, $isalesman, $ibrand, $iperiode){
		 return $this->db->query("
                                    SELECT
                                        *
                                    FROM
                                        tr_customer_salesman
                                    WHERE
                                        id_area = '$iarea'
                                    AND
                                        id_customer = '$icustomer'
                                    AND
                                        id_salesman = '$isalesman'
                                    /* AND
                                        id_brand = '$ibrand' */
                                    AND
                                        e_periode = '$iperiode'
                                ", FALSE); 
	}

	public function insert($id, $iarea, $icustomer, $isalesman, $ibrand, $iperiode, $idcompany){
        $now = current_datetime();
        $this->db->query("
            INSERT INTO tr_customer_salesman (id, id_company, id_customer, id_salesman, id_area, e_periode, d_entry) 
            VALUES ('$id', '$idcompany', '$icustomer', '$isalesman', '$iarea', '$iperiode', now())
            ON CONFLICT (id_company, id_customer, id_salesman, id_area, e_periode) DO UPDATE 
            SET d_update = '$now'
        ");
        /* $data = array(
                        'id' 	      => $id,
                        'id_company'  => $idcompany,
                        'id_customer' => $icustomer,
                        'id_salesman' => $isalesman,
                        'id_area'     => $iarea,
                        'e_periode'   => $iperiode,
                        'd_entry'     => current_datetime(),
        );
    	$this->db->insert('tr_customer_salesman', $data); */
    } 
  
    function get_data($id_salesman, $periode=null){
        $id_company = $this->session->userdata('id_company');

        $sql = "SELECT
                    a.id,
                    a.id_company,
                    a.id_area,
                    d.e_area,
                    a.id_customer,
                    b.e_customer_name,
                    a.id_salesman,
                    c.e_sales,
                    a.e_periode
                FROM tr_customer_salesman a 
                JOIN tr_customer b ON a.id_customer = b.id 
                JOIN tr_salesman c ON a.id_salesman = c.id 
                JOIN tr_area d ON a.id_area = d.id
                WHERE a.id_salesman = '$id_salesman' 
                    AND a.id_company = '$id_company'
                    AND a.e_periode = '$periode'";

        return $this->db->query($sql, FALSE); 
    }

    public function update($id, $iarea, $icustomer, $isalesman, $ibrand, $iperiode, $idcompany){
        $data = array(
                        'id'          => $id,
                        'id_company'  => $idcompany,
                        'id_customer' => $icustomer,
                        'id_salesman' => $isalesman,
                        'id_area'     => $iarea,
                        /* 'id_brand'    => $ibrand, */
                        'e_periode'   => $iperiode,
                        'd_update'    => current_datetime(),
    );

    $this->db->where('id', $id);
    $this->db->where('id_company', $idcompany);
    $this->db->update('tr_customer_salesman', $data);
    }

    /** delete insert */
    public function update_batch($id=null, $iarea, $icustomer, $isalesman, $ibrand, $iperiode, $idcompany, $items){
        
        $sql = "DELETE FROM tr_customer_salesman WHERE id_salesman = '$isalesman' AND e_periode='$iperiode'";
        $this->db->query($sql);

        foreach ($items as $item) {

            $data = [
                'id_company'  => $idcompany,
                'id_customer' => $item['id_customer'],
                'id_salesman' => $isalesman,
                'id_area'     => $iarea,
                'e_periode'   => $iperiode,
            ];
            $this->db->insert('tr_customer_salesman', $data);
        }
    }

    public function get_all_customer_salesman($id_salesman, $periode)
    {
        $sql = "SELECT tcs.*, 
                tc.i_customer, 
                tc.e_customer_name,
                ta.e_area,
                tc2.e_city_name,
                tc.e_customer_address
                FROM tr_customer_salesman tcs
                INNER JOIN tr_customer tc ON tc.id = tcs.id_customer
                INNER JOIN tr_area ta ON ta.id = tc.id_area
                INNER JOIN tr_city tc2 ON tc2.id = tc.id_city
                WHERE tcs.id_salesman = '$id_salesman' AND tcs.e_periode='$periode'";

        return $this->db->query($sql);
    }

    public function status($id)
    {
        $this->db->select('f_status');
        $this->db->from('tr_customer_salesman');
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
        $this->db->update('tr_customer_salesman', $data);
    }

    public function get_customer_export($id_customer=null, $id_company=null)
    {
        $_id_company = $this->session->userdata('id_company');
        if ($id_company != null) {
            $_id_company = $id_company;
        }

        $where = "WHERE tc.f_status = 't' AND tc.id_company = '$_id_company'";

        if ($id_customer != null) {
            $where .= " AND tc.id_customer = '$id_customer'";
        }

        $sql = "SELECT tc.id, 
                    tc.i_customer, 
                    tc.e_customer_name,
                    ta.e_area,
                    tc2.e_city_name,
                    tc.e_customer_address
                FROM tr_customer tc
                INNER JOIN tr_area ta ON ta.id = tc.id_area
                INNER JOIN tr_city tc2 ON tc2.id = tc.id_city
                $where
                ORDER BY ta.e_area ASC, tc.e_customer_name ASC";

        return $this->db->query($sql);
    }

    public function get_salesman($id_salesman)
    {
        $this->db->where('id', $id_salesman);
        return $this->db->get('tr_salesman');
    }

}