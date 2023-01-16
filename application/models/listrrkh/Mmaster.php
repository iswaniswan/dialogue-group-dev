<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return '00';
        }else{
            return 'xx';
        }
    }

    public function getarea($username, $idcompany, $iarea){
        if ($iarea=='00') {
            return $this->db->query("SELECT * FROM tr_area", FALSE)->result();
        }else{        
            return $this->db->query("
                SELECT
                    *
                FROM
                    tr_area
                WHERE
                    i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
            ", FALSE)->result();
        }
    }

    public function data($folder,$imenu,$dfrom,$dto,$iarea){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                to_char(d_rrkh, 'dd-mm-yyyy') AS d_rrkh,
                a.i_salesman,
                a.i_salesman ||' - '||e_salesman_name AS salesman, 
                to_char(d_receive1, 'dd-mm-yyyy') AS d_receive1,
                f_rrkh_cancel,
                CASE WHEN f_rrkh_cancel = 't' THEN 'Ya' ELSE 'Tidak' END AS status,
                i_approve,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iarea' AS iarea,
                '$folder' AS folder,
                '$imenu' AS imenu
            FROM
                tm_rrkh a,
                tr_salesman b,
                tr_area c
            WHERE
                a.i_area = '$iarea'
                AND a.i_salesman = b.i_salesman
                AND a.i_area = c.i_area
                AND a.d_rrkh >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_rrkh <= TO_DATE('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.d_rrkh,
                a.i_salesman"
            , FALSE);
        $datatables->add('action', function ($data) {
            $isalesman = trim($data['i_salesman']);
            $imenu     = $data['imenu'];
            $dfrom     = $data['dfrom'];
            $cancel    = $data['f_rrkh_cancel'];
            $receive   = $data['d_receive1'];
            $drrkh     = $data['d_rrkh'];
            $iapprove  = $data['i_approve'];
            $dto       = $data['dto'];
            $iarea     = $data['iarea'];
            $folder    = $data['folder'];
            $data      = '';
            if(check_role($imenu, 2)||check_role($imenu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$drrkh/$isalesman/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($imenu, 4) && $cancel=='f' && $receive=='' && $iapprove==''){
                $data .= "<a href=\"#\" onclick='cancel(\"$isalesman\",\"$drrkh\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('i_approve');
        $datatables->hide('i_salesman');
        $datatables->hide('f_rrkh_cancel');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('folder');
        $datatables->hide('imenu');
        $datatables->hide('iarea');
        return $datatables->generate();
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }    

    public function bacakunjungan(){
        return $this->db->order_by('i_kunjungan_type','ASC')->get('tr_kunjungan_type')->result();
    }

    public function baca($drrkh,$isalesman,$iarea){
        $this->db->select(" a.*, b.e_area_name, c.e_salesman_name from tm_rrkh a, tr_area b, tr_salesman c
                           where a.i_area=b.i_area and a.i_salesman=c.i_salesman
                           and a.d_rrkh ='$drrkh' and a.i_salesman='$isalesman' and a.i_area='$iarea'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($drrkh,$isalesman,$iarea){
        $this->db->select(" a.*, b.e_kunjungan_typename, c.e_customer_name, d.e_city_name 
            from tr_kunjungan_type b, tr_customer c, tm_rrkh_item a
            left join tr_city d on(a.i_city=d.i_city and a.i_area=d.i_area)
            where a.d_rrkh = '$drrkh' and a.i_salesman='$isalesman' and a.i_area='$iarea'
            and a.i_kunjungan_type=b.i_kunjungan_type and a.i_customer=c.i_customer
            order by a.d_rrkh, a.i_area, a.i_salesman, a.i_customer", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }    

    public function getsalesman($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT(i_salesman),
                e_salesman_name
            FROM
                tr_customer_salesman
            WHERE
                i_area = '$iarea'
                AND (UPPER(e_salesman_name) LIKE '%$cari%'
                OR UPPER(i_salesman) LIKE '%$cari%')
            ORDER BY
                i_salesman", 
        FALSE);
    } 

    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_customer,
                a.e_customer_name
            FROM
                (
                SELECT
                    a.i_customer,
                    a.e_customer_name,
                    b.i_city,
                    b.e_city_name
                FROM
                    tr_customer a,
                    tr_city b
                WHERE
                    a.i_area = '$iarea'
                    AND a.i_area = b.i_area
                    AND a.i_city = b.i_city
                    AND (UPPER(a.i_customer) LIKE '%$cari%'
                    OR UPPER(a.e_customer_name) LIKE '%$cari%')
            UNION ALL
                SELECT
                    a.i_customer,
                    a.e_customer_name,
                    b.i_city,
                    b.e_city_name
                FROM
                    tr_customer_tmp a
                LEFT JOIN tr_city b ON
                    (a.i_area = b.i_area
                    AND a.i_city = b.i_city)
                WHERE
                    a.i_area = '$iarea'
                    AND (a.i_customer LIKE '%000'
                    OR UPPER(a.e_customer_name) LIKE '%$cari%') ) AS a
            ORDER BY
                a.i_customer DESC", 
        FALSE);
    }

    public function getcity($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_city,
                e_city_name
            FROM
                tr_city
            WHERE
                i_area = '$iarea'
                AND (UPPER(e_city_name) LIKE '%$cari%'
                OR UPPER(i_city) LIKE '%$cari%')
            ORDER BY
                e_city_name", 
        FALSE);
    }

    public function updateheader($isalesman,$drrkh,$drrkhasal,$iarea,$drec1){
        $dentry = current_datetime();
        if($drec1!=''){
            $userid = $this->session->userdata("username");
        }else{
            $userid = '';
        }
        $this->db->set(
            array(
                'd_rrkh'      => $drrkh,
                'd_update'    => $dentry,
                'i_entry'     => $userid,
                'd_receive1'  => $drec1
            )
        );
        $this->db->where('i_salesman',$isalesman);
        $this->db->where('i_area',$iarea);
        $this->db->where('d_rrkh',$drrkhasal);
        $this->db->update('tm_rrkh');
    }

    public function insertdetail($isalesman,$drrkh,$iarea,$icustomer,$ikunjungantype,$icity,$fkunjunganrealisasi,$fkunjunganvalid,$eremark,$i){
        $dentry = current_datetime();
        $userid = $this->session->userdata("username");
        if($eremark=='') {
            $eremark=null;
        }
        $this->db->set(
            array(
                'd_rrkh'                => $drrkh,
                'i_salesman'            => $isalesman,
                'i_area'                => $iarea,
                'i_customer'            => $icustomer,
                'i_kunjungan_type'      => $ikunjungantype,
                'i_city'                => $icity,
                'f_kunjungan_realisasi' => $fkunjunganrealisasi,
                'f_kunjungan_valid'     => $fkunjunganvalid,
                'e_remark'              => $eremark,
                'd_entry'               => $dentry,
                'i_entry'               => $userid,
                'n_item_no'             => $i
            )
        );
        $this->db->insert('tm_rrkh_item');
    }

    public function cancel($isalesman,$drrkh,$iarea){
        $drrkh = date('Y-m-d', strtotime($drrkh));
        $this->db->set(
            array(
                'f_rrkh_cancel' => 't'
            )
        );
        $this->db->where('i_salesman',$isalesman);    
        $this->db->where('d_rrkh',$drrkh);    
        $this->db->where('i_area',$iarea);    
        $this->db->update('tm_rrkh');
    }

    public function approve($isalesman,$drrkh,$iarea){
        $drrkh = date('Y-m-d', strtotime($drrkh));
        $this->db->set(
            array(
                'i_approve' => $this->session->userdata('username')
            )
        );
        $this->db->where('i_salesman',$isalesman);    
        $this->db->where('d_rrkh',$drrkh);    
        $this->db->where('i_area',$iarea);    
        $this->db->update('tm_rrkh');
    }

    public function batalapprove($isalesman,$drrkh,$iarea){
        $drrkh = date('Y-m-d', strtotime($drrkh));
        $this->db->set(
            array(
                'i_approve' => null
            )
        );
        $this->db->where('i_salesman',$isalesman);    
        $this->db->where('d_rrkh',$drrkh);    
        $this->db->where('i_area',$iarea);    
        $this->db->update('tm_rrkh');
    }

    public function deletedetail($drrkh,$isalesman,$iarea,$icustomer){
        $this->db->where('d_rrkh', $drrkh);
        $this->db->where('i_salesman', $isalesman);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_customer', $icustomer);
        return $this->db->delete('tm_rrkh_item');
    }
}

/* End of file Mmaster.php */