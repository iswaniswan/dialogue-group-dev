<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('i_area','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $area = '00';
        }else{
            $area = 'xx';
        }
        return $area;
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT DISTINCT 
                a.i_customer,
                e_customer_name
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar f ON
                (a.i_customer = f.i_customer)
            LEFT JOIN tr_customer_discount g ON
                (a.i_customer = g.i_customer)
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND
                    (UPPER(a.i_customer) LIKE '%$cari%' 
                OR 
                    UPPER(a.e_customer_name) LIKE '%$cari%') 
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function getdetailcus($icustomer, $iarea){
        return $this->db->query("
            SELECT
                DISTINCT a.i_customer,
                e_customer_name,
                e_customer_address,
                i_salesman,
                e_salesman_name,
                i_customer_groupar,
                n_customer_discount1,
                n_customer_discount2,
                n_customer_discount3
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar f ON
                (a.i_customer = f.i_customer)
            LEFT JOIN tr_customer_discount g ON
                (a.i_customer = g.i_customer)
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND
                    a.i_customer = '$icustomer' 
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function getsalesman($cari,$iarea,$icustomer){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT DISTINCT 
                i_salesman,
                e_salesman_name
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar f ON
                (a.i_customer = f.i_customer)
            LEFT JOIN tr_customer_discount g ON
                (a.i_customer = g.i_customer)
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND 
                    a.i_customer = '$icustomer'  
                AND
                    (UPPER(i_salesman) LIKE '%$cari%' 
                OR 
                    UPPER(e_salesman_name) LIKE '%$cari%')
        ", FALSE);
    }

    public function runningnumberkn($th,$iarea,$ikn){
        $pot=substr($th,2,2);
        $kn="KP".$iarea.$ikn.$pot;
        return $kn;
    }

    public function insert($iarea,$ikn,$icustomer,$irefference,$icustomergroupar,$isalesman,$ikntype,$dkn,$nknyear,$fcetak,$fmasalah,$finsentif,$vnetto,$vsisa,$vgross,$vdiscount,$eremark,$drefference){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'            => $iarea,
                'i_kn'              => $ikn,
                'i_customer'        => $icustomer,
                'i_refference'      => $irefference,
                'i_customer_groupar'=> $icustomergroupar,
                'i_salesman'        => $isalesman,
                'i_kn_type'         => $ikntype,
                'd_kn'              => $dkn,
                'd_refference'      => $drefference,
                'd_entry'           => $dentry,
                'e_remark'          => $eremark,
                'f_cetak'           => $fcetak,
                'f_masalah'         => $fmasalah,
                'f_insentif'        => $finsentif,
                'n_kn_year'         => $nknyear,
                'v_netto'           => $vnetto,
                'v_gross'           => $vgross,
                'v_discount'        => $vdiscount,
                'v_sisa'            => $vsisa,

            )
        );        
        $this->db->insert('tm_kn');
        /*die();*/
    }
}

/* End of file Mmaster.php */