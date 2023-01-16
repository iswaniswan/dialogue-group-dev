<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public $idcompany;

    function __construct(){
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);

        $datatables->query("    
                            SELECT
                               0 as no,
                               i_type_industry,
                               e_type_industry_name,
                               id_company,
                               case
                                  when
                                     f_status = TRUE 
                                  then
                                     'Aktif' 
                                  else
                                     'Tidak Aktif' 
                               end
                               as status, '$i_menu' as i_menu, '$folder' as folder 
                            FROM
                               tr_type_industry 
                            WHERE
                                id_company = '$this->idcompany'
                            ORDER BY
                               e_type_industry_name
                            ", FALSE);

    // $datatables->edit('e_supplier_groupname', function ($data) {
    //         return '<span>'.str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['e_supplier_groupname']))).'</span>';
    // });

    $datatables->edit(
    'status', 
            function ($data) {
                $id         = trim($data['i_type_industry']);
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
            $itype = trim($data['i_type_industry']);
            $i_menu = $data['i_menu'];
            $folder     = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$itype/\",\"#main\"); return false;'><i class=' ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$itype/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            // if(check_role($i_menu, 4)){
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$itype\"); return false;'><i class='ti-close'></i></a>";
            // }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');
        
        return $datatables->generate();
	}

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_type_industry');
        $this->db->where('i_type_industry', $id);
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
        $this->db->where('i_type_industry', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tr_type_industry', $data);
    }
    
	public function cek_data($id){
        $this->db->select('*');
        $this->db->from('tr_type_industry');
        $this->db->where('i_type_industry', $id);
        $this->db->where('id_company', $this->idcompany);
        return $this->db->get();
    }

    function cek_data_edit($oldid, $id){
        $this->db->select('*');
        $this->db->from('tr_type_industry');
        $this->db->where('i_type_industry <>', $oldid);
        $this->db->where('i_type_industry', $id);
        $this->db->where('id_company', $this->idcompany);
        return $this->db->get();
    }

	public function insert($itype, $etype){
        $dentry = date("Y-m-d H:i:s");

        $data = array(
              'i_type_industry'         => $itype,
              'e_type_industry_name'    => $etype, 
              'id_company'              => $this->idcompany,     
              'd_entry'                 => $dentry,        
    );
    
    $this->db->insert('tr_type_industry', $data);
    }

    public function insertdetail($itype, $ikategori){
        $dentry = date("Y-m-d H:i:s");

        $data = array(
              'i_type_industry'    => $itype,
              'i_supplier_group'   => $ikategori,      
              'd_entry'            => $dentry,        
    );
    
    $this->db->insert('tr_supplier_group_item', $data);
    }    

    public function cancel($itype){
        $data = array(
          'f_status'=>'f',
      );
        $this->db->where('i_type_industry', $itype);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tr_type_industry', $data);
    }

    public function bacakategori($itype){
        $query = $this->db->query("
                                    SELECT 
                                        a.i_supplier_group,
                                        b.e_supplier_groupname
                                    FROM 
                                        tr_supplier_group_item a
                                        LEFT JOIN tr_supplier_group b
                                        ON (a.i_supplier_group = b.i_supplier_group)
                                    WHERE 
                                        a.i_type_industry = '$itype'
                                        AND b.id_company = '$this->id_company'
                                    AND b.f_status = 't'
                                    ", FALSE);
        if($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function baca_kategori($itype){
        $cek =  $this->db->query("
                                SELECT DISTINCT
                                    i_supplier_group 
                                FROM 
                                    tr_supplier_group_item 
                                WHERE 
                                    i_supplier_group IN (SELECT i_supplier_group FROM tr_supplier_group_item WHERE i_type_industry = '$itype') 
                                ", FALSE);
        if($cek->num_rows() > 0){
            return $this->db->query("
                                    SELECT DISTINCT
                                        a.i_supplier_group,
                                        a.e_supplier_groupname
                                    FROM
                                        tr_supplier_group a
                                    WHERE 
                                        a.f_status = 't'
                                    AND
                                        a.i_supplier_group 
                                        NOT IN (
                                            SELECT 
                                                i_supplier_group
                                            FROM 
                                                tr_supplier_group_item
                                            WHERE 
                                                i_type_industry = '$itype'

                                        )
                                    ORDER BY 
                                        a.i_supplier_group ", FALSE)->result();
        }else{
            return $this->db->query("
                                SELECT 
                                    i_supplier_group, 
                                    e_supplier_groupname 
                                FROM 
                                    tr_supplier_group
                                WHERE
                                     f_status = 't'", FALSE)->result();
        }
    }

    public function update($olditype, $itype, $etype){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
             'i_type_industry'    => $itype,
             'e_type_industry_name'    => $etype,           
             'd_update'           => $dupdate, 
    );

    $this->db->where('i_type_industry', $olditype);
    $this->db->where('id_company', $this->idcompany);
    $this->db->update('tr_type_industry', $data);
    }

    public function deletedetail($itype){
        $this->db->query("
                    DELETE FROM 
                      tr_supplier_group_item 
                    WHERE 
                      i_type_industry='$itype' ");
    }

}

/* End of file Mmaster.php */