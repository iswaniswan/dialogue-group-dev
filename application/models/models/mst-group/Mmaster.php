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
    
    public function getkategoripartner(){
        return $this->db->query("
                                SELECT
                                    i_supplier_group,
                                    e_supplier_group_name
                                FROM
                                    tr_supplier_group
                                WHERE
                                    id_company = '$this->idcompany'
                                ORDER BY
                                    e_supplier_group_name
                                ", FALSE);
    }

    public function getpartner($isuppliergroup){
        $isuppliregroup = trim($isuppliergroup);
        return $this->db->query("
                                SELECT 
                                    i_supplier as i_kepala_pusat, 
                                    e_supplier_name as e_pusat, 
                                    i_level as level
                                FROM 
                                    tr_supplier 
                                WHERE 
                                    f_status = 't'
                                    and i_supplier_group = '$isuppliergroup'
                                    and i_level  != 'PLV00'
                                    and id_company = '$this->idcompany'
                                UNION ALL
                                SELECT 
                                    i_customer as i_kepala_pusat,
                                    e_customer_name as e_pusat,
                                    i_level as level
                                FROM
                                    tr_customer
                                WHERE 
                                    f_status = 't'
                                    and i_supplier_group = '$isuppliergroup'
                                    and i_level != 'PLV00'
                                    and id_company = '$this->idcompany'
                                GROUP BY
                                    i_customer,
                                    i_kepala_pusat,
                                    e_pusat,
                                    level
                                ORDER BY
                                    i_kepala_pusat,
                                    e_pusat
                                ", FALSE);
    }

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT DISTINCT
                                a.i_supplier as i_kepala_pusat,
                                a.e_supplier_name as e_pusat,
                                a.i_level as ilevel,
                                b.e_level_name as level,
                                c.e_supplier_group_name as groupname,
                                a.i_kepala_pusat as pusat,
                                '$i_menu' as i_menu, 
                                '$folder' as folder
                            FROM
                                tr_supplier a,
                                tr_level_perusahaan b,
                                tr_supplier_group c
                            WHERE
                                a.i_level = b.i_level
                                and a.i_supplier_group = c.i_supplier_group 
                                and a.f_status = 't'
                                and a.i_level = 'PLV00'
                                and a.id_company = '$this->idcompany'
                            GROUP BY
                                a.i_supplier,
                                a.e_supplier_name,
                                a.i_level,
                                b.e_level_name,
                                c.e_supplier_group_name,
                                a.i_kepala_pusat,
                                i_menu,
                                folder
                            ORDER BY
                                i_kepala_pusat,
                                e_pusat,
                                level
                            ", FALSE);

		$datatables->add('action', function ($data) {
            $ipartner = trim($data['i_kepala_pusat']);
            $ilevel   = trim($data['ilevel']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $pusat    = $data['pusat'];
            $data     = '';
            $ada = false;
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$ipartner/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $query =  $this->db->query("
                                            SELECT
                                                i_kepala_pusat
                                            FROM
                                                tr_supplier
                                            WHERE 
                                                id_company = '$this->idcompany'
                                            UNION ALL
                                            SELECT
                                                i_kepala_pusat
                                            FROM 
                                                tr_customer
                                            WHERE
                                                id_company = '$this->idcompany'
                                            GROUP BY
                                                i_kepala_pusat
                                            ",FALSE);
                if($query->num_rows() > 0){
                    foreach($query->result() as $row){
                        $pusat = $row->i_kepala_pusat;
                        if($pusat == $ipartner){
                            $ada = true;
                        }
                    }
                    if($ada != true){
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ipartner/$ilevel/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
                    }
                }
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('pusat');
        $datatables->hide('ilevel');

        return $datatables->generate();
    }

    function datacabang($i_menu, $folder, $ipusat){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT DISTINCT
                                a.i_supplier as i_kepala_pusat,
                                a.e_supplier_name as e_pusat,
                                a.i_level as ilevel,
                                b.e_level_name as level,
                                c.e_supplier_group_name as groupname,
                                a.i_kepala_pusat as pusat,
                                '$i_menu' as i_menu, 
                                '$folder' as folder
                            FROM
                                tr_supplier a,
                                tr_level_perusahaan b,
                                tr_supplier_group c
                            WHERE
                                a.i_level = b.i_level
                                and a.i_supplier_group = c.i_supplier_group 
                                and a.f_status = 't'
                                and a.i_level = 'PLV01'
                                and a.i_kepala_pusat = '$ipusat'
                                and a.id_company = '$this->idcompany'
                            UNION ALL
                            SELECT DISTINCT
                                a.i_customer as i_kepala_pusat,
                                a.e_customer_name as e_pusat,
                                a.i_level as ilevel,
                                b.e_level_name as level,
                                c.e_supplier_group_name as groupname,
                                a.i_kepala_pusat as pusat,
                                '$i_menu' as i_menu,
                                '$folder' as folder
                            FROM
                                tr_customer a,
                                tr_level_perusahaan b,
                                tr_supplier_group c
                            WHERE
                                a.i_level = b.i_level
                                and a.i_supplier_group = c.i_supplier_group
                                and a.f_status = 't'
                                and a.i_level = 'PLV01'
                                and a.i_kepala_pusat = '$ipusat'
                                and a.id_company = '$this->idcompany'
                            GROUP BY
                                a.i_customer,
                                a.i_level,
                                i_kepala_pusat,
                                e_pusat,
                                level,
                                groupname,
                                pusat,
                                i_menu,
                                folder
                            ORDER BY
                                i_kepala_pusat,
                                e_pusat,
                                level
                            ", FALSE);
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('pusat');
        $datatables->hide('ilevel');

        return $datatables->generate();
    }

    public function update($isuppliergroup, $levelgroup, $ipartner){
        $dupdate = date("Y-m-d H:i:s");
        $query1 = $this->db->query("
                                    SELECT
                                        i_customer
                                    FROM
                                        tr_customer
                                    WHERE 
                                        id_company = '$this->idcompany'
                                    ", FALSE);
        if($query1->num_rows()>0){
            foreach($query1->result() as $row1){
                if($ipartner == $row1->i_customer){
                    $data = array(
                                    'i_supplier_group'  => $isuppliergroup,
                                    'i_level'           => $levelgroup,  
                                    'i_kepala_pusat'    => null,       
                                    'd_update'          => $dupdate
                            );
                    $this->db->where('i_customer', $ipartner);
                    $this->db->where('id_company', $this->idcompany);
                    $this->db->update('tr_custdomer', $data);
                }else{
                    $query2 = $this->db->query("
                                                SELECT
                                                    i_supplier
                                                FROM
                                                    tr_supplier
                                                WHERE
                                                    id_company = '$this->idcompany'
                                                ",FALSE);
                    if($query2->num_rows()>0){
                        foreach($query2->result() as $row2){
                            if($ipartner == $row2->i_supplier){
                                $data = array(
                                            'i_supplier_group' => $isuppliergroup,
                                            'i_level'          => $levelgroup, 
                                            'i_kepala_pusat'   => null,          
                                            'd_update'         => $dupdate
                                        );
                                $this->db->where('i_supplier', $ipartner);
                                $this->db->where('id_company', $this->idcompany);
                                $this->db->update('tr_supplier', $data);
                            }
                        }
                    }
                }
            }
        }else{
            $query2 = $this->db->query("
                                        SELECT
                                            i_supplier
                                        FROM
                                            tr_supplier
                                        WHERE
                                            id_company = '$this->idcompany'
                                        ",FALSE);
            if($query2->num_rows()>0){
                foreach($query2->result() as $row2){
                    if($ipartner == $row2->i_supplier){
                        $data = array(
                                    'i_supplier_group' => $isuppliergroup,
                                    'i_level'          => $levelgroup, 
                                    'i_kepala_pusat'   => null,          
                                    'd_update'         => $dupdate
                        );
                        $this->db->where('i_supplier', $ipartner);
                        $this->db->where('id_company', $this->idcompany);
                        $this->db->update('tr_supplier', $data);
                    }
                }
            }
        }
    }

    public function baca($ipartner){
        return $this->db->query("
                                SELECT DISTINCT
                                    a.i_supplier as i_kepala_pusat,
                                    a.e_supplier_name as e_pusat,
                                    a.i_level as ilevel,
                                    b.e_level_name as e_level,
                                    a.i_supplier_group as isuppliergroup,
                                    c.e_supplier_group_name as esuppliergroupname
                                FROM
                                    tr_supplier a,
                                    tr_level_perusahaan b,
                                    tr_supplier_group c
                                WHERE
                                    a.i_level = b.i_level
                                    and a.i_supplier_group = c.i_supplier_group 
                                    and a.f_status = 't'
                                    and a.i_supplier = '$ipartner'
                                    and a.id_company = '$this->idcompany'
                                UNION ALL
                                SELECT DISTINCT
                                    a.i_customer as i_kepala_pusat,
                                    a.e_customer_name as e_pusat,
                                    a.i_level as ilevel,
                                    b.e_level_name as e_level,
                                    a.i_supplier_group as isuppliergroup,
                                    c.e_supplier_group_name as esuppliergroupname
                                FROM
                                    tr_customer a,
                                    tr_level_perusahaan b,
                                    tr_supplier_group c
                                WHERE
                                    a.i_level = b.i_level
                                    and a.i_supplier_group = c.i_supplier_group
                                    and a.f_status = 't'
                                    and a.i_customer = '$ipartner'
                                    and a.id_company = '$this->idcompany'
                                GROUP BY
                                    a.i_customer,
                                    i_kepala_pusat,
                                    e_pusat,
                                    ilevel,
                                    e_level,
                                    isuppliergroup,
                                    esuppliergroupname
                                ",FALSE);
    }

    public function bacalevel($ilevel){
        return $this->db->query("
                                SELECT
                                    i_level,
                                    e_level_name
                                FROM
                                    tr_level_perusahaan
                                WHERE 
                                    i_level != '$ilevel'
                                    and id_company = '$this->idcompany'
                                ", FALSE)->result();
    }

    public function getpusat($isuppliergroup, $ipartner){
        return $this->db->query("
                                SELECT DISTINCT
                                    i_supplier as i_kepala_pusat, 
                                    e_supplier_name as e_pusat, 
                                    i_level as level
                                FROM 
                                    tr_supplier 
                                WHERE 
                                    f_status = 't'
                                    and i_supplier_group = '$isuppliergroup'
                                    and i_level = 'PLV00'
                                    and i_supplier != '$ipartner'
                                    and id_company = '$this->idcompany'
                                UNION ALL
                                SELECT 
                                    i_customer as i_kepala_pusat,
                                    e_customer_name as e_pusat,
                                    i_level as level
                                FROM
                                    tr_customer
                                WHERE 
                                    f_status = 't'
                                    and i_supplier_group = '$isuppliergroup'
                                    and i_level = 'PLV00'
                                    and i_customer != '$ipartner'
                                    and id_company = '$this->idcompany'
                                GROUP BY
                                    i_customer,
                                    i_kepala_pusat,
                                    e_pusat,
                                    level
                                ORDER BY
                                    i_kepala_pusat,
                                    e_pusat
                                ", FALSE);
    }

    public function updatelevel($ipartner, $ilevel, $ipusat){
        $dupdate = date("Y-m-d H:i:s");
        $query1 = $this->db->query("
                                    SELECT
                                        i_customer
                                    FROM
                                        tr_customer
                                    WHERE
                                        id_company ='$this->idcompany'
                                    ", FALSE);
        if($query1->num_rows()>0){
            foreach($query1->result() as $row1){
                if($ipartner == $row1->i_customer){
                    $data = array(
                                    'i_level'         => $ilevel,
                                    'i_kepala_pusat'  => $ipusat,           
                                    'd_update'        => $dupdate
                            );
                    $this->db->where('i_customer', $ipartner);
                    $this->db->where('id_company', $this->idcompany);
                    $this->db->update('tr_customer', $data);
                }else{
                    $query2 = $this->db->query("
                                                SELECT
                                                    i_supplier
                                                FROM
                                                    tr_supplier
                                                WHERE
                                                    id_company = '$this->idcompany'
                                                ",FALSE);
                    if($query2->num_rows()>0){
                        foreach($query2->result() as $row2){
                            if($ipartner == $row2->i_supplier){
                                $data = array(
                                            'i_level'         => $ilevel,
                                            'i_kepala_pusat'  => $ipusat,              
                                            'd_update'        => $dupdate
                                        );
                                $this->db->where('i_supplier', $ipartner);
                                $this->db->where('id_company', $this->idcompany);
                                $this->db->update('tr_supplier', $data);
                            }
                        }
                    }
                }
            }
        }else{
            $query2 = $this->db->query("
                                        SELECT
                                            i_supplier
                                        FROM
                                            tr_supplier
                                        WHERE
                                            id_company = '$this->idcompany'
                                        ",FALSE);
            if($query2->num_rows()>0){
                foreach($query2->result() as $row2){
                    if($ipartner == $row2->i_supplier){
                        $data = array(
                                    'i_level'         => $ilevel,
                                    'i_kepala_pusat'  => $ipusat,              
                                    'd_update'        => $dupdate
                                );
                        $this->db->where('i_supplier', $ipartner);
                        $this->db->where('id_company', $this->idcompany);
                        $this->db->update('tr_supplier', $data);
                    }
                }
            }
        }
    }

}

/* End of file Mmaster.php */
