<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
      return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_customer,
                e_customer_name
            FROM
                tr_customer a
            LEFT JOIN tr_customer_area d ON
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
                *
            FROM
                tr_customer a
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function getdt($cari, $iarea, $date){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_dt,
                to_char(d_dt, 'dd-mm-yyyy') AS d_dt
            FROM
                tm_dt
            WHERE
                i_area = '$iarea'
                AND d_dt >= '$date'
                AND i_dt LIKE '%$cari%'", 
        FALSE);
    }

    public function getdetaildt($iarea, $idt){
        return $this->db->query("
            SELECT
                to_char(d_dt, 'dd-mm-yyyy') AS d_dt
            FROM
                tm_dt
            WHERE
                i_area = '$iarea'
                AND i_dt = '$idt'", 
        FALSE);
    }

    public function runningnumberrv($rvth){
        $this->db->select(" trim(to_char(count(i_rv)+1,'000000')) as no 
            from tm_giro where to_char(d_rv,'yyyy')='$rvth'",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $rv=$row->no;
            }
            return $rv;
        }else{
            $rv='000001';
            return $rv;
        }
    }

    public function insert($igiro,$iarea,$icustomer,$irv,$dgiro,$drv,$dgiroduedate,$egirodescription,$egirobank,$vjumlah,$vsisa,$idt,$dgiroterima){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_giro'            => $igiro,
                'i_area'            => $iarea,
                'i_customer'        => $icustomer,
                'i_rv'              => $irv,
                'd_giro'            => $dgiro,
                'd_rv'              => $drv,
                'd_giro_duedate'    => $dgiroduedate,
                'd_entry'           => $dentry,
                'e_giro_description'=> $egirodescription,
                'e_giro_bank'       => $egirobank,
                'v_jumlah'          => $vjumlah,
                'v_sisa'            => $vsisa,
                'i_dt'              => $idt,
                'd_giro_terima'     => $dgiroterima
            )
        );
        $this->db->insert('tm_giro');
    }
}

/* End of file Mmaster.php */