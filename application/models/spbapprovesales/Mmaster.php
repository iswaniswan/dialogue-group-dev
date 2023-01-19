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
            $area = true;
        }else{
            $area = false;
        }
        return $area;
    }

    public function data($folder, $siareana, $username, $id_company){
        if ($siareana==true) {
            $sql = "SELECT
                        a.i_spb,
                        to_char(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                        b.e_customer_name,
                        c.e_area_name,
                        a.i_spb_old,
                        a.i_area,
                        a.i_product_group,
                        a.i_spb_program,
                        '$folder' AS folder
                    FROM
                        tm_spb a,
                        tr_customer b,
                        tr_area c
                    WHERE
                        a.i_customer = b.i_customer
                        AND a.i_area = c.i_area
                        AND a.i_approve1 ISNULL
                        AND a.i_notapprove ISNULL
                        AND a.f_spb_cancel = 'f'
                        AND ((a.f_spb_stockdaerah = 't'
                        AND NOT a.i_approve2 ISNULL
                        AND (a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$id_company')))
                        OR (a.f_spb_stockdaerah = 'f'
                        AND NOT a.i_cek ISNULL))
                    ORDER BY
                        a.d_spb,
                        a.i_area,
                        a.i_spb";
        }else{
            $sql = "SELECT
                        a.i_spb,
                        to_char(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                        b.e_customer_name,
                        c.e_area_name,
                        a.i_spb_old,
                        a.i_area,
                        a.i_product_group,
                        a.i_spb_program,
                        '$folder' AS folder
                    FROM
                        tm_spb a,
                        tr_customer b,
                        tr_area c
                    WHERE
                        a.i_customer = b.i_customer
                        AND a.i_area = c.i_area
                        AND a.i_approve1 ISNULL
                        AND NOT a.i_approve2 ISNULL
                        AND a.i_notapprove ISNULL
                        AND a.f_spb_cancel = 'f'
                        AND ((a.i_area IN (
                        SELECT
                            i_area
                        FROM
                            public.tm_user_area
                        WHERE
                            username = '$username'
                            AND id_company = '$id_company')))
                    ORDER BY
                        a.d_spb,
                        a.i_area,
                        a.i_spb";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("$sql", false);
        $datatables->add('action', function ($data) {
            $ispb   = trim($data['i_spb']);
            $iarea  = trim($data['i_area']);
            $group  = trim($data['i_product_group']);
            $prog   = trim($data['i_spb_program']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/approve/$ispb/$iarea/$group/$prog\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('i_spb_program');
        $datatables->hide('i_product_group');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function baca($ispb, $iarea){
        $this->db->select(" *, to_char(d_spb, 'dd-mm-yyyy') AS dspb 
            FROM
                tm_spb
            LEFT JOIN tm_promo ON
                (tm_spb.i_spb_program = tm_promo.i_promo)
            INNER JOIN tr_customer ON
                (tm_spb.i_customer = tr_customer.i_customer)
            INNER JOIN tr_salesman ON
                (tm_spb.i_salesman = tr_salesman.i_salesman)
            INNER JOIN tr_customer_area ON
                (tm_spb.i_customer = tr_customer_area.i_customer)
            INNER JOIN tr_price_group ON
                (tm_spb.i_price_group = tr_price_group.i_price_group)
            WHERE
                i_spb = '$ispb'
                AND tm_spb.i_area = '$iarea'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($ispb, $iarea){
        $this->db->select("
                a.*,
                b.e_product_motifname
            FROM
                tm_spb_item a,
                tr_product_motif b
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function baca_plapon($i_customer){
        $this->db->select('*');
        $this->db->from('tr_customer_groupar');
        $this->db->where('i_customer', $i_customer);
        return $this->db->get();
    }

    public function nota_sisa($icustomer){
        $this->db->select("sum(v_sisa) as sisa from tm_nota where i_customer = '$icustomer' and f_nota_cancel = 'f' and not i_nota isnull");
        $query =  $this->db->get();
        $row = $query->row_array();
        return $row['sisa'];
    }

    public function updatesaldo($icustomer, $saldobaru){
        $update = array(
            'v_saldo' => $saldobaru 
        );
        $this->db->where('i_customer', $icustomer);
        $this->db->update('tr_customer_groupar', $update);
    }  

    public function baca_nilai($i_customer){      
        $thbln = date('ym');      
        return $this->db->query("select (x.v_spb - x.diskon) as nilai_spb from(select sum(v_spb) as v_spb, sum(v_spb_discounttotal) as diskon from tm_spb where i_customer = '$i_customer' and i_spb like '%SPB-$thbln-%' and f_spb_cancel = 'f' and i_nota isnull) as x");    
    }

    public function baca_area_pusat(){
        $user = $this->session->userdata('username');
        $this->db->select("*");
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$user);
        $this->db->where('i_area','00');
        return $this->db->get();
    }

    public function approve($ispb,$iarea,$eapprove1,$user) {
        $dentry   = current_datetime();       
        if($iarea=='01' || $iarea=='02'){        
            $this->db->select(" 
                a.f_spb_stockdaerah, a.i_customer, b.i_customer_status                            
                from tm_spb a, tr_customer b                             
                where a.i_spb = '$ispb' and a.i_area='$iarea' and a.i_customer=b.i_customer", false);
            $query = $this->db->get();            
            if ($query->num_rows() > 0){                
                foreach($query->result() as $row){
                    $daerah=$row->f_spb_stockdaerah;
                    $custom=$row->i_customer_status;
                    $kode  =$row->i_customer;
                }
            }
            if($daerah=='f'){          
                if($custom!='4'){            
                    $this->db->select("v_flapond, v_saldo"); 
                    $this->db->from("tr_customer_groupar");
                    $this->db->where('i_customer',$kode);
                    $que = $this->db->get();
                    if ($que->num_rows() > 0){                      
                        foreach($que->result() as $ro){                        
                            $flapond=$ro->v_flapond;                        
                            $saldo  =$ro->v_saldo;                    
                        }
                    }
                    if($flapond>0){              
                        $data = array(                  
                            'e_approve2' => 'SYSTEM',
                            'd_approve2' => $dentry,
                            'i_approve2' => 'SYSTEM PLAFOND',
                            'e_approve1' => $eapprove1,
                            'd_approve1' => $dentry,
                            'i_approve1' => $user
                        );
                        $this->db->where('i_spb', $ispb);
                        $this->db->where('i_area', $iarea);
                        $this->db->update('tm_spb', $data);
                    }else{                  
                        $data = array(                      
                            'e_approve1' => $eapprove1,
                            'd_approve1' => $dentry,
                            'i_approve1' => $user
                        );
                        $this->db->where('i_spb', $ispb);
                        $this->db->where('i_area', $iarea);
                        $this->db->update('tm_spb', $data);
                    }
                }else{            
                    $data = array(
                        'e_approve1' => $eapprove1,
                        'd_approve1' => $dentry,
                        'i_approve1' => $user
                    );
                    $this->db->where('i_spb', $ispb);
                    $this->db->where('i_area', $iarea);
                    $this->db->update('tm_spb', $data);
                }
            }else{          
                $data = array(              
                    'e_approve1' => $eapprove1,
                    'd_approve1' => $dentry,
                    'i_approve1' => $user
                );
                $this->db->where('i_spb', $ispb);
                $this->db->where('i_area', $iarea);
                $this->db->update('tm_spb', $data);
            }  
        }else{    
            $data = array(
                'e_approve1' => $eapprove1,
                'd_approve1' => $dentry,
                'i_approve1' => $user
            );
            $this->db->where('i_spb', $ispb);
            $this->db->where('i_area', $iarea);
            $this->db->update('tm_spb', $data);
        }
    }

    public function notapprove($ispb,$iarea,$eapprove,$user){
        $dentry   = current_datetime();       
        $data = array(
            'e_notapprove' => $eapprove,
            'd_notapprove' => $dentry,
            'i_notapprove' => $user
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }
}

/* End of file Mmaster.php */
