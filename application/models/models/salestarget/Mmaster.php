<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getarea($cari, $iperiode){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                tr_area.i_area,
                tr_area.e_area_name
            FROM
                tm_target
            RIGHT JOIN tr_area ON
                (tm_target.i_area = tr_area.i_area
                AND tm_target.i_periode = '$iperiode')
            WHERE
                tr_area.i_area LIKE '%$cari%'
                OR tr_area.e_area_name LIKE '%$cari%'
            ORDER BY
                tr_area.i_area", 
        FALSE);
    }

    public function getvarea($iperiode, $iarea){
        return $this->db->query("
            SELECT
                COALESCE (v_target,0) AS v_target
            FROM
                tm_target
            RIGHT JOIN tr_area ON
                (tm_target.i_area = tr_area.i_area
                AND tm_target.i_periode = '$iperiode')
            WHERE
                tr_area.i_area = '$iarea'
            ORDER BY
                tr_area.i_area", 
        FALSE);
    }

    public function getsalesman($cari, $iperiode, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT ON
                (tr_customer_salesman.i_salesman)
                tr_customer_salesman.i_salesman,
                tr_customer_salesman.e_salesman_name
            FROM
                tr_customer_salesman
            LEFT JOIN tm_target_itemsls ON
                (tm_target_itemsls.i_salesman = tr_customer_salesman.i_salesman
                AND tm_target_itemsls.i_periode = '$iperiode'
                AND tm_target_itemsls.i_area = '$iarea'
                AND tm_target_itemsls.i_periode = tr_customer_salesman.e_periode
                AND tm_target_itemsls.i_area = tr_customer_salesman.i_area)
            WHERE
                tr_customer_salesman.i_area = '$iarea'
                AND tr_customer_salesman.e_periode = '$iperiode'
                AND (tr_customer_salesman.i_salesman LIKE '%$cari%'
                OR tr_customer_salesman.e_salesman_name LIKE '%$cari%')", 
        FALSE);
    }

    public function getvsalesman($iperiode, $iarea, $isalesman){
        return $this->db->query("
            SELECT
                DISTINCT ON
                (tr_customer_salesman.i_salesman)
                COALESCE (v_target,0) AS v_target
            FROM
                tr_customer_salesman
            LEFT JOIN tm_target_itemsls ON
                (tm_target_itemsls.i_salesman = tr_customer_salesman.i_salesman
                AND tm_target_itemsls.i_periode = '$iperiode'
                AND tm_target_itemsls.i_area = '$iarea'
                AND tm_target_itemsls.i_periode = tr_customer_salesman.e_periode
                AND tm_target_itemsls.i_area = tr_customer_salesman.i_area)
            WHERE
                tr_customer_salesman.i_area = '$iarea'
                AND tr_customer_salesman.e_periode = '$iperiode'
                AND tr_customer_salesman.i_salesman = '$isalesman'", 
        FALSE);
    }

    public function getcity($cari, $iperiode, $iarea, $isalesman){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                tr_city.i_city,
                tr_city.e_city_name
            FROM
                tm_target_itemkota
            RIGHT JOIN tr_city ON
                (tm_target_itemkota.i_city = tr_city.i_city
                AND tm_target_itemkota.i_periode = '$iperiode'
                AND tm_target_itemkota.i_salesman = '$isalesman')
            WHERE
                tr_city.i_area = '$iarea'
                AND (tr_city.e_city_name LIKE '%$cari%'
                OR tr_city.i_city LIKE '%$cari%')", 
        FALSE);
    }

    public function getvcity($iperiode, $iarea, $isalesman, $icity){
        return $this->db->query("
            SELECT
                COALESCE (v_target,0) AS v_target
            FROM
                tm_target_itemkota
            RIGHT JOIN tr_city ON
                (tm_target_itemkota.i_city = tr_city.i_city
                AND tm_target_itemkota.i_periode = '$iperiode'
                AND tm_target_itemkota.i_salesman = '$isalesman')
            WHERE
                tr_city.i_area = '$iarea'
                AND tr_city.i_city = '$icity'", 
        FALSE);
    }

    public function insert($iperiode,$iarea,$isalesman,$icity, $vareatarget, $vsalesmantarget, $vcitytarget){
        $dentry = current_datetime();
        $this->db->select('*');
        $this->db->from('tm_target');
        $this->db->where('i_periode', $iperiode);
        $this->db->where('i_area', $iarea);
        $query = $this->db->get();
        if($query->num_rows()==0){
            $this->db->set(
                array(
                    'i_periode' => $iperiode,
                    'i_area'    => $iarea,
                    'v_target'  => $vareatarget,
                    'd_entry'   => $dentry
                )
            );        
            $this->db->insert('tm_target');
        }else{
            $target = array(
                'v_target' => $vareatarget 
            );
            $this->db->where('i_periode', $iperiode);
            $this->db->where('i_area', $iarea);
            $this->db->update('tm_target', $target);
        }

        $this->db->select('*');
        $this->db->from('tm_target_itemsls');
        $this->db->where('i_periode', $iperiode);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_salesman', $isalesman);
        $query = $this->db->get();
        if($query->num_rows()==0){
            $this->db->set(
                array(
                    'i_periode' => $iperiode,
                    'i_area'    => $iarea,
                    'i_salesman'=> $isalesman,
                    'v_target'  => $vsalesmantarget
                )
            );        
            $this->db->insert('tm_target_itemsls');
        }else{
            $targetsales = array(
                'v_target' => $vsalesmantarget 
            );
            $this->db->where('i_periode', $iperiode);
            $this->db->where('i_area', $iarea);
            $this->db->where('i_salesman', $isalesman);
            $this->db->update('tm_target_itemsls', $targetsales);
        }

        $this->db->select('*');
        $this->db->from('tm_target_itemkota');
        $this->db->where('i_periode', $iperiode);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_salesman', $isalesman);
        $this->db->where('i_city', $icity);
        $query = $this->db->get();
        if($query->num_rows()==0){
            $this->db->set(
                array(
                    'i_periode' => $iperiode,
                    'i_area'    => $iarea,
                    'i_city'    => $icity,
                    'i_salesman'=> $isalesman,
                    'v_target'  => $vcitytarget,
                    'd_entry'   => $dentry,
                    'd_process' => $dentry
                )
            );        
            $this->db->insert('tm_target_itemkota');
        }else{
            $targetkota = array(
                'v_target' => $vcitytarget 
            );
            $this->db->where('i_periode', $iperiode);
            $this->db->where('i_area', $iarea);
            $this->db->where('i_salesman', $isalesman);
            $this->db->where('i_city', $icity);
            $this->db->update('tm_target_itemkota', $targetkota);
        }
    }
}

/* End of file Mmaster.php */