<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu,$folder,$dfrom,$dto)
    {
        $idcompany  = $this->session->userdata('id_company');
        if ($dfrom!='' && $dto!='') {
              $dfrom = date('Y-m-d', strtotime($dfrom));
              $dto   = date('Y-m-d', strtotime($dto));
              $where = "AND d_berlaku BETWEEN '$dfrom' AND '$dto'";
        }else{
                $where = "";
        }
		$datatables = new Datatables(new CodeigniterAdapter);

		$datatables->query("
                            SELECT
                                0 AS NO,
                                id,
                                i_promo,
                                e_promo,
                                e_jenispromo,
                                n_jumlahpromo,
                                id_company,
                                d_berlaku,
                                CASE
                                    WHEN f_status = TRUE THEN 'Aktif'
                                    ELSE 'Tidak Aktif'
                                END AS status,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto,
                                '$i_menu' as i_menu,
                                '$folder' AS folder
                            FROM
                                tm_spb_promo
                            WHERE
                                id_company = '$idcompany'
                            $where
                            ORDER BY 
                                id
                        ", fALSE);

        $datatables->edit('status', 
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
            $id     = trim($data['id']);
            $folder = $data['folder'];
            $i_menu = $data['i_menu'];
            $dfrom  = $data['dfrom'];
            $dto    = $data['dto'];
            $data   = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id');
        $datatables->hide('id_company');
        $datatables->hide('d_berlaku');

        return $datatables->generate();
	}

    public function status($id)
    {
        $this->db->select('f_status');
        $this->db->from('tm_spb_promo');
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
        $this->db->update('tm_spb_promo', $data);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_spb_promo');
        return $this->db->get()->row()->id+1;
    }

    public function cek_kode($kode)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('*');
        $this->db->from('tm_spb_promo');
        $this->db->where('i_promo', $kode);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

     public function dataproduct($cari)
     {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("    
                                    SELECT  
                                        a.id,
                                        a.i_product_base,
                                        a.e_product_basename,
                                        a.i_color,
                                        b.e_color_name
                                    FROM
                                        tr_product_base a
                                    INNER JOIN tr_color b ON
                                        (b.i_color = a.i_color AND a.id_company = b.id_company)
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND
                                        (upper(a.i_product_base) LIKE '%$cari%'
                                        OR upper(a.e_product_basename) LIKE '%$cari%') 
                                ", FALSE);
    }

    public function getproduct($eproduct)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("            
                                    SELECT 
                                        a.id as id_product, 
                                        a.i_product_base,
                                        a.e_product_basename,
                                        b.id as id_color,
                                        a.i_color,
                                        b.e_color_name
                                    FROM
                                        tr_product_base a
                                    INNER JOIN tr_color b ON
                                        (b.i_color = a.i_color AND a.id_company = b.id_company)
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND 
                                        a.id = '$eproduct'
                                ", FALSE);
    }

    public function insertheader($id, $ipromo, $epromo, $ejenis, $njumlah, $dateperiode)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
                        'id'             => $id, 
                        'id_company'     => $idcompany, 
                        'i_promo'        => $ipromo,
                        'e_promo'        => $epromo,  
                        'e_jenispromo'   => $ejenis, 
                        'n_jumlahpromo'  => $njumlah,
                        'd_berlaku'      => $dateperiode,    
                        'd_entry'        => current_datetime(),        
        );

        $this->db->insert('tm_spb_promo', $data);
    }

    public function cek_data($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tm_spb_promo');
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    public function cek_datadetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select("                            
                               a.id_promo,
                               a.id_product,
                               c.i_product_base,
                               c.e_product_basename,
                               a.id_color,
                               d.e_color_name,
                               a.n_diskon 
                            FROM
                               tm_spb_promo_item a 
                               JOIN
                                  tm_spb_promo b 
                                  ON (a.id_promo = b.id 
                                  AND a.id_company = b.id_company) 
                               JOIN
                                  tr_product_base c 
                                  ON (a.id_product = c.id 
                                  AND a.id_company = c.id_company) 
                               JOIN
                                  tr_color d 
                                  ON (a.id_color = d.id 
                                  AND a.id_company = d.id_company)
                               WHERE
                                    a.id_promo = '$id'
                               AND 
                                    a.id_company = '$idcompany'
                            ", FALSE);
        return $this->db->get();
    }

    public function insertdetail($id, $idproduct, $idcolor, $ndiskon)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
                        'id_company'     => $idcompany, 
                        'id_promo'       => $id,
                        'id_product'     => $idproduct,  
                        'id_color'       => $idcolor, 
                        'n_diskon'       => $ndiskon,       
        );

        $this->db->insert('tm_spb_promo_item', $data);
    } 

    public function updateheader($id, $ipromo, $epromo, $ejenis, $njumlah, $dateperiode){
        $idcompany  = $this->session->userdata('id_company');
        
        $data = array(
                        'i_promo'        => $ipromo,
                        'e_promo'        => $epromo,  
                        'e_jenispromo'   => $ejenis, 
                        'n_jumlahpromo'  => $njumlah,
                        'd_berlaku'      => $dateperiode,     
                        'd_update'       => current_datetime(), 
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_spb_promo', $data);
    }
    public function deletedetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->where('id_promo', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_spb_promo_item');
    }
}

/* End of file Mmaster.php */