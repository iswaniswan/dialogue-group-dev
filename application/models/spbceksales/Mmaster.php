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

    public function bacaarea(){
      return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function data($dfrom, $dto, $iarea, $folder, $siareana){
        if ($siareana==true) {
            $sql = " a.i_spb, 
                    to_char(a.d_spb, 'dd-mm-yyyy') AS dspb,
                    b.e_customer_name,                
                    c.e_area_name,                
                    a.i_spb_old,
                    a.i_cek, 
                    to_char(a.d_cek, 'dd-mm-yyyy') AS dcek ";
        }else{
            $sql = " a.i_spb, 
                    to_char(a.d_spb, 'dd-mm-yyyy') AS dspb, 
                    b.e_customer_name,
                    c.e_area_name,
                    a.i_spb_old,
                    a.i_cek_cabang AS i_cek, 
                    to_char(a.d_cek_cabang, 'dd-mm-yyyy') AS dcek ";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                '$folder' AS folder,
                '$iarea'  AS iarea,
                '$dfrom'  AS dfrom,
                '$dto'    AS dto,
                 $sql
            FROM
                tm_spb a
            LEFT JOIN tr_customer b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_customer_tmp x ON
                (a.i_customer = x.i_customer
                AND a.i_spb = x.i_spb
                AND a.i_area = x.i_area) ,
                tr_area c
            WHERE
                a.i_area = c.i_area
                AND ((a.i_approve1 ISNULL
                AND a.f_spb_stockdaerah = 'f')
                OR a.f_spb_stockdaerah = 't')
                AND a.i_notapprove ISNULL
                AND a.f_spb_cancel = 'f'
                AND a.i_area = '$iarea'
                AND (a.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy'))
            ORDER BY
                a.d_spb,
                a.i_area,
                a.i_spb", false);
        $datatables->edit('i_cek', function($data){
            $dcek = $data['dcek'];
            if($data['i_cek'] != ""){
                return $dcek;
            }else{
                return "Belum";
            }
        });
        $datatables->add('action', function ($data) {
            $ispb   = trim($data['i_spb']);
            $folder = $data['folder'];
            $iarea  = $data['iarea'];
            $dfrom  = $data['dfrom'];
            $dto    = $data['dto'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/cek/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('dcek');
        return $datatables->generate();
    }

    public function baca($ispb, $iarea, $siareana){
        if ($siareana==true) {
            $sql = "tm_spb.i_cek, 
                    tm_spb.d_cek, ";
        }else{
            $sql = "tm_spb.i_cek_cabang AS i_cek, 
                    tm_spb.d_cek_cabang AS d_cek, ";
        }
        $this->db->select("
                to_char(tm_spb.d_spb, 'dd-mm-yyyy') AS dspb,
                tm_spb.d_spb,
                $sql,
                tm_spb.i_spb,
                tm_spb.i_spb_old,
                tm_spb.i_area,
                tr_price_group.e_price_groupname,
                tr_price_group.i_price_group,
                tm_promo.e_promo_name,
                tm_spb.i_spb_program,
                tm_spb.v_spb,
                tm_spb.n_spb_discount1,
                tm_spb.n_spb_discount2,
                tm_spb.n_spb_discount4,
                tm_spb.n_spb_discount3,
                tm_spb.v_spb_discount1,
                tm_spb.v_spb_discount2,
                tm_spb.v_spb_discount4,
                tm_spb.v_spb_discount3,
                tr_customer_area.e_area_name,
                tr_customer.e_customer_name,
                tr_customer.e_customer_address,
                tm_spb.i_customer,
                tm_spb.i_spb_po,
                tm_spb.f_spb_consigment,
                tm_spb.n_spb_toplength,
                tm_spb.f_spb_stockdaerah,
                tm_spb.f_spb_plusppn,
                tm_spb.f_spb_plusdiscount,
                tm_spb.f_spb_pkp,
                tm_spb.v_spb_discounttotal,
                tm_spb.i_salesman,
                tr_salesman.e_salesman_name,
                tm_spb.v_spb_after,
                tm_spb.e_remark1,
                tm_spb.e_customer_pkpnpwp,
                x.e_customer_name AS e_customer_namex,
                x.e_customer_address AS e_customer_addressx
            FROM
                tm_spb
            LEFT JOIN tm_promo ON
                (tm_spb.i_spb_program = tm_promo.i_promo)
            LEFT JOIN tr_customer ON
                (tm_spb.i_customer = tr_customer.i_customer)
            LEFT JOIN tr_customer_tmp x ON
                (tm_spb.i_customer = x.i_customer
                AND tm_spb.i_spb = x.i_spb
                AND tm_spb.i_area = x.i_area)
            INNER JOIN tr_salesman ON
                (tm_spb.i_salesman = tr_salesman.i_salesman)
            LEFT JOIN tr_customer_area ON
                (tm_spb.i_customer = tr_customer_area.i_customer)
            INNER JOIN tr_price_group ON
                (tm_spb.i_price_group = tr_price_group.i_price_group)
            WHERE
                tm_spb.i_spb = '$ispb'
                AND tm_spb.i_area = '$iarea'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($ispb, $iarea){
        $this->db->select("
                a.i_product,
                a.e_product_name,
                b.e_product_motifname,
                a.v_unit_price,
                a.n_order,
                a.i_product_motif
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

    public function updateheader($ispb,$iarea,$ecek1,$user,$siareana){      
        $daerah='';
        $this->db->select("*");
        $this->db->from("tm_spb"); 
        $this->db->where('i_spb',$ispb);
        $this->db->where('i_area',$iarea);      
        $query = $this->db->get();      
        if ($query->num_rows() > 0){        
            foreach($query->result() as $tmp){          
                $daerah=$tmp->f_spb_stockdaerah;        
            }              
            $dspbupdate   = current_datetime();        
            if ($siareana==true) {
                if($daerah=='f'){            
                    $data = array(              
                        'e_cek'         => $ecek1,
                        'd_cek'         => $dspbupdate,
                        'i_cek'         => $user
                    );
                    $this->db->where('i_spb', $ispb);
                    $this->db->where('i_area', $iarea);
                    $this->db->update('tm_spb', $data); 
                }elseif($daerah=='t'){
                    $data = array(
                      'e_cek'           => $ecek1,
                      'd_cek'           => $dspbupdate,
                      'i_cek'           => $user,
                      'f_spb_valid'     => 't'
                  );
                    $this->db->where('i_spb', $ispb);
                    $this->db->where('i_area', $iarea);
                    $this->db->update('tm_spb', $data); 
                }
            }else{
                if($daerah=='f'){
                    $data = array(                      
                        'e_cek_cabang' => $ecek1,
                        'd_cek_cabang' => $dspbupdate,
                        'i_cek_cabang' => $user
                    );
                    $this->db->where('i_spb', $ispb);
                    $this->db->where('i_area', $iarea);
                    $this->db->update('tm_spb', $data); 
                }elseif($daerah=='t'){
                    $data = array(
                      'e_cek_cabang' => $ecek1,
                      'd_cek_cabang' => $dspbupdate,
                      'i_cek_cabang' => $user,
                      'f_spb_valid'  => 't'
                  );
                    $this->db->where('i_spb', $ispb);
                    $this->db->where('i_area', $iarea);
                    $this->db->update('tm_spb', $data); 
                }
            }
        }
    }
}

/* End of file Mmaster.php */
