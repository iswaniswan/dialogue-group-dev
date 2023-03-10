<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu, $folder)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');

        $datatables->query("
                            SELECT
                                0 AS NO,
                                a.id,
                                a.i_sales,
                                a.e_sales,
                                a.id_area,
                                b.e_area,
                                a.id_company,
                                CASE
                                    WHEN a.f_status = TRUE THEN 'Aktif'
                                    ELSE 'Tidak Aktif'
                                END AS status,
                                $i_menu AS i_menu,
                                '$folder' AS folder
                            FROM
                                tr_salesman a
                            JOIN 
                                tr_area b
                                ON a.id_area = b.id
                            WHERE
                                a.id_company = '$idcompany'
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
            $data   = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('id_company');
        $datatables->hide('id_area');

        return $datatables->generate();
    }

    public function status($id)
    {
        $this->db->select('f_status');
        $this->db->from('tr_salesman');
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
        $this->db->update('tr_salesman', $data);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tr_salesman');
        return $this->db->get()->row()->id+1;
    }

    public function cek_kode($kode)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('*');
        $this->db->from('tr_salesman');
        $this->db->where('i_sales', $kode);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }

    public function area($cari){
        $this->db->select("
                            * 
                            FROM
                                tr_area
                            WHERE
                                f_status = 't'
                            AND
                                (e_area ILIKE '%$cari%')
                            ORDER BY 
                                i_area ASC", FALSE);
        return $this->db->get();
    }

    public function cek_data($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tr_salesman');
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
    }    

    public function insert($id, $isales, $esales, $iarea, $ekota, $ealamat, $ekodepos, $etelepon)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
                        'id'            => $id,
                        'id_company'    => $idcompany,
                        'i_sales'       => $isales,
                        'e_sales'       => $esales,    
                        'id_area'       => $iarea, 
                        'e_kota'        => $ekota,
                        'e_telepon'     => $etelepon,
                        'e_alamat'      => $ealamat,
                        'e_kodepos'     => $ekodepos,  
                        'd_entry'       => current_datetime(),        
        );

        $this->db->insert('tr_salesman', $data);
    }

    public function cek_datas($id)
    {
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select("a.id,
                                a.i_sales,
                                a.e_sales,
                                a.id_area,
                                b.e_area,
                                a.e_telepon,
                                a.e_kota,
                                a.e_kodepos,
                                a.e_alamat,
                                a.id_company
                            FROM
                                tr_salesman a
                            JOIN 
                                tr_area b
                                ON a.id_area = b.id
                            WHERE
                                a.id_company = '$idcompany'", FALSE);
        return $this->db->get();
    }   

    public function update($id, $isales, $esales, $iarea, $ekota, $ealamat, $ekodepos, $etelepon){
        $idcompany  = $this->session->userdata('id_company');
        
        $data = array(
                        'id_company'    => $idcompany,
                        'i_sales'       => $isales,
                        'e_sales'       => $esales,    
                        'id_area'       => $iarea, 
                        'e_kota'        => $ekota,
                        'e_telepon'     => $etelepon,
                        'e_alamat'      => $ealamat,
                        'e_kodepos'     => $ekodepos,      
                        'd_update'      => current_datetime(), 
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tr_salesman', $data);
    }
}

/* End of file Mmaster.php */