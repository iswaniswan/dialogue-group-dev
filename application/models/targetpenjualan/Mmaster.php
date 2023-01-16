<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }      

    public function bagian()
    {
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
            INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            LEFT JOIN tr_type c on (a.i_type = c.i_type)
            LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
            WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
            ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function getidbagian()
    {
        return $this->db->query("SELECT
        i_bagian
    FROM
        tr_departement_cover
    WHERE
        i_departement = '".$this->session->userdata('i_departement')."'
        AND id_company = '".$this->session->userdata('id_company')."'
        AND username = '".$this->session->userdata('username')."'", false);
    }

    public function kodearea()
    {
        return $this->db->query("SELECT DISTINCT id, i_area, e_area FROM tr_area WHERE f_status = true
        ", false);
    }

    public function insert($company,$bagian,$periode,$kodearea,$kota,$sales,$target){
        $target = explode(".",$target);
        $target = implode($target);
        $data = array(
                    'id_company'  => $company,
                    'i_bagian'    => $bagian,
                    'i_periode'   => $periode,
                    'id_area'     => $kodearea,
                    'id_city'     => $kota,
                    'id_salesman' => $sales,
                    'v_target'    => $target
                );
        $this->db->insert('tr_target_penjualan',$data);
    }

    public function delete($periode,$area){
        $this->db->query("DELETE FROM tr_target_penjualan WHERE i_periode = '".$periode."' AND id_area = ".$area, FALSE);
    }


    public function cek_data($periode, $area, $imenu){
        return $this->db->query("SELECT
        0 AS no,
        b.e_bagian_name,
        a.i_periode,
        c.e_area,
        d.e_sales,
        g.e_city_name,
        a.v_target,
        a.i_status,
        f.e_status_name,
        e.i_level,
        l.e_level_name,
        a.id_company
    FROM
        tr_target_penjualan a
    INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
    INNER JOIN tr_area c ON (c.id = a.id_area)
    INNER JOIN tr_salesman d ON (d.id = a.id_salesman AND a.id_company = d.id_company)
    INNER JOIN tr_city g ON (g.id = a.id_city)
    LEFT JOIN public.tr_menu_approve e ON (e.i_menu = '".$imenu."' AND e.n_urut = a.i_approve_urutan)
    LEFT JOIN tr_status_document f ON (f.i_status = a.i_status)
    LEFT JOIN public.tr_level l ON (e.i_level = l.i_level)
    WHERE a.i_status <> '5'
    AND 
        (a.id_company = '".$this->session->userdata('id_company')."'
        AND i_periode = '".$periode."'
        AND e_area LIKE '".$area."')
        AND (a.i_bagian IN (SELECT
                i_bagian
            FROM
                tr_departement_cover
            WHERE
                i_departement = '".$this->i_departement."'
                AND id_company = '".$this->id_company."'
                AND username = '".$this->username."'))", FALSE);
    }

    public function kodecity($idarea)
    {
        return $this->db->query("SELECT DISTINCT id, i_city, e_city_name FROM tr_city WHERE f_status = true AND id_area = ".$idarea, false);
    }

    public function kodesalesman($idarea)
    {
        return $this->db->query("SELECT DISTINCT id, i_sales, e_sales FROM tr_salesman WHERE f_status = true AND id_area = ".$idarea, false);
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_cutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

	public function data($i_menu,$folder,$dfrom,$dto){
        $idcompany  = $this->session->userdata('id_company');

        if ($dfrom!='' && $dto!='') {
            $dfrom = date("Yd", strtotime($dfrom));
            $dto   = date("Yd", strtotime($dto)) + 1;
            $where = "AND i_periode BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tr_target_penjualan
            WHERE
                i_status <> '5'
                AND id_company = '".$this->session->userdata('id_company')."'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        id_company = '".$this->session->userdata('id_company')."')

        ", FALSE);

        if ($this->session->userdata('i_departement')=='4' || $this->session->userdata('i_departement')=='1') {
            $bagian = "";
        }else{
            if ($cek->num_rows()>0) {                
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND username = '".$this->session->userdata('username')."')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT ON (i_periode,e_area)
                0 AS no,
                a.id,
                b.e_bagian_name,
                a.i_periode,
                a.id_area,
                c.e_area,
                d.e_sales,
                g.e_city_name,
                a.i_status,
                f.e_status_name,
                e.i_level,
                l.e_level_name,
                a.id_company,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tr_target_penjualan a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_area c ON (c.id = a.id_area)
            INNER JOIN tr_salesman d ON (d.id = a.id_salesman AND a.id_company = d.id_company)
            INNER JOIN tr_city g ON (g.id = a.id_city)
            LEFT JOIN public.tr_menu_approve e ON (e.i_menu = '$i_menu' AND e.n_urut = a.i_approve_urutan)
            LEFT JOIN tr_status_document f ON (f.i_status = a.i_status)
            LEFT JOIN public.tr_level l ON (e.i_level = l.i_level)
            WHERE a.i_status <> '5'
            AND 
                a.id_company = '$idcompany'
                $where
                $bagian
        ", FALSE);

        
        $datatables->add('action', function ($data) {
            $id      = trim($data['id']);
            $i_menu  = $data['i_menu'];
            $i_status= $data['i_status'];
            $i_level = $data['i_level'];
            $folder  = $data['folder'];
            $dfrom   = $data['dfrom'];
            $dto     = $data['dto'];
            $id_area = $data['id_area'];
            $bagian  = $data['e_bagian_name'];
            $periode = $data['i_periode'];
            $area    = $data['e_area'];
            $data    = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$bagian/$periode/$area/$i_menu/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$bagian/$periode/$area/$i_menu/$dfrom/$dto/$id_area\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('e_status_name');
        $datatables->hide('folder');
        $datatables->hide('id_area');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_company');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        
        return $datatables->generate();
    }

    public function getsalesman($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                id,
                i_sales,
                e_sales
            FROM
                tr_salesman
            WHERE
                id_area = '$iarea'
                AND (UPPER(e_sales) LIKE '%$cari%'
                OR UPPER(i_sales) LIKE '%$cari%')
            ORDER BY
                id", 
        FALSE);
    } 

    public function getarea($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                id,
                e_area
            FROM
                tr_area
            WHERE
                f_status = 't'
                AND (UPPER(e_area) LIKE '%$cari%'
                OR UPPER(i_area) LIKE '%$cari%')
            ORDER BY
                id",
        FALSE);
    } 

    public function getcity($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                id,
                i_city,
                e_city_name
            FROM
                tr_city
            WHERE
                id_area = '$iarea'
                AND (UPPER(e_city_name) LIKE '%$cari%'
                OR UPPER(i_city) LIKE '%$cari%')
            ORDER BY
                e_city_name", 
        FALSE);
    }

    public function cekdata($periode, $area){
        $this->db->select('*');
        $this->db->from('tr_target_penjualan');
        $this->db->where('i_periode',$periode);
        $this->db->where('kode_area',$area);
        return $this->db->get();
    }

    public function changestatus($id,$istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				from tr_target_penjualan a
				inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
				where a.id = '$id'
				group by 1,2", FALSE)->row();
            if ($istatus == '3') {
            	if ($awal->i_approve_urutan - 1 == 0 ) {
            		$data = array(
	                    'i_status'  => $istatus,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
            	}
            	$this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
                    $query = $this->db->query("SELECT f_budgeting, d_pp FROM tm_pp WHERE id = '$id' ", FALSE);
                    if ($query->num_rows()>0) {
                        $budgeting = $query->row()->f_budgeting;
                        $iperiode  = date('Ym', strtotime($query->row()->d_pp));
                        if ($budgeting=='t') {
                            $getitem = $this->db->query("SELECT i_material, n_quantity FROM tm_pp_item WHERE id_pp = '$id' ", FALSE);
                            if ($getitem->num_rows()>0) {
                                foreach ($getitem->result() as $key) {
                                    $this->db->query("UPDATE
                                        tm_budgeting_item_material x
                                    SET
                                        n_budgeting_sisa = n_budgeting_sisa - $key->n_quantity
                                    FROM
                                        tm_budgeting y,
                                        tr_material z
                                    WHERE
                                        y.id = x.id_document
                                        AND x.id_material = z.id
                                        AND to_char(y.d_document, 'YYYYmm') = '$iperiode'
                                        AND z.i_material = '$key->i_material' ", FALSE);
                                }
                            }
                        }
                    }
            		$data = array(
	                    'i_status'  => $istatus,
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->session->userdata('username'),
                        'd_approve' => date('Y-m-d'),
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
                $now = date('Y-m-d');
            	$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_pp');", FALSE);
            }
        }
        else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tr_target_penjualan', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tr_target_penjualan');
        return $this->db->get()->row()->id+1;
    }

}

/* End of file Mmaster.php */