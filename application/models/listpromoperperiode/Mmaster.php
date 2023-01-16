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

    public function bacaarea($username, $idcompany, $iarea){
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

    public function interval($dfrom,$dto){
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dfrom=$th."-".$bl."-".$hr;
        }
        if($dto!=''){
            $tmp=explode("-",$dto);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dto=$th."-".$bl."-".$hr;
        }
        $this->db->select("(DATE_PART('year', '$dto'::date) - DATE_PART('year', '$dfrom'::date)) * 12 +
            (DATE_PART('month', '$dto'::date) - DATE_PART('month', '$dfrom'::date)) as inter ",false);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $tmp=$query->row();
            return $tmp->inter+1;
        }
    }

    public function bacaperiode($dfrom,$dto,$iarea,$interval){
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];                
            $bl=$tmp[1];
            $dt=$tmp[0];
            $tgl=$th.'-'.$bl.'-'.$dt;
        }
        $sql="isi[1] as area, isi[2] as areanya, isi[3] as kode, isi[4] as nama, isi[5] as program, isi[6] as promo, ";
        $sql.=" jan, feb, mar,apr, may, jun, jul, aug,sep, oct, nov, des ";
        $sql.=" from crosstab (
        'select Array [i_area::text, e_area_name::text, i_customer::text, e_customer_name::text, i_spb_program::text, 
        e_promo_name::text] as isi, 
        to_number(to_char(d_spb, ''mm''),''99'') as bln, sum(vdis1+vdis2) as biaya FROM v_promo
        WHERE 
        d_spb >= to_date(''$dfrom'',''dd-mm-yyyy'') AND d_spb <= to_date(''$dto'',''dd-mm-yyyy'') and vdis1+vdis2>0
        group by i_area, e_area_name, i_customer, e_customer_name, i_spb_program, e_promo_name, to_char(d_spb,''mm'')
        order by i_spb_program, e_promo_name, i_area, i_customer, e_customer_name, to_char(d_spb,''mm'')',
        'select mm from generate_series(1,12) mm')
        as
        (isi text[], ";
        $sql.=" Jan integer, Feb integer, Mar integer, Apr integer, May integer, Jun integer, Jul integer, Aug integer, Sep integer, 
        Oct integer, Nov integer, Des integer) ";
        $this->db->select($sql,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}
