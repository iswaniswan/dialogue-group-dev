<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
      return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacabank(){
      return $this->db->order_by('i_bank','ASC')->get('tr_bank')->result();
    }

    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer,
                c.i_salesman) a.i_customer,
                a.e_customer_name
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_customer_salesman c ON
                (a.i_customer = c.i_customer
                AND a.i_area = c.i_area)
            LEFT JOIN tr_customer_owner d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND (UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(a.e_customer_name) LIKE '%$cari%')
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function getdetailcustomer($iarea, $icustomer){
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer,
                c.i_salesman) a.i_customer,
                a.e_customer_name,
                b.i_customer_groupar,
                c.i_salesman,
                c.e_salesman_name,
                b.i_customer_groupar,
                d.e_customer_setor
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_customer_salesman c ON
                (a.i_customer = c.i_customer
                AND a.i_area = c.i_area)
            LEFT JOIN tr_customer_owner d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function cek($iarea,$ikum,$tahun){
        $this->db->select("i_kum");
        $this->db->from("tm_kum");
        $this->db->where('i_area',$iarea); 
        $this->db->where('i_kum',$ikum);
        $this->db->where('n_kum_year',$tahun);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function insert($ikum,$dkum,$tahun,$ebankname,$iarea,$icustomer,$icustomergroupar,$isalesman,$eremark,$vjumlah,$vsisa,$ibank){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'                => $iarea,
                'i_kum'                 => $ikum,
                'i_customer'            => $icustomer,
                'i_customer_groupar'    => $icustomergroupar,
                'i_salesman'            => $isalesman,
                'd_kum'                 => $dkum,
                'd_entry'               => $dentry,
                'e_bank_name'           => $ebankname,
                'e_remark'              => $eremark,
                'n_kum_year'            => $tahun,
                'v_jumlah'              => $vjumlah,
                'v_sisa'                => $vsisa,
                'i_bank'                => $ibank
                
            )
        );        
        $this->db->insert('tm_kum');
    }
}

/* End of file Mmaster.php */