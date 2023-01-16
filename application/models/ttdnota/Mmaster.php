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

    public function bacaarea($iarea){
        if ($iarea=='00') {
            return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
        }
    }

    public function area($iarea){
        $this->db->select('e_area_name');
        $this->db->from('tr_area');
        $this->db->where('i_area', $iarea);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row   = $query->row();
            $earea = $row->e_area_name; 
        }else{
            $earea = 'NA';
        }
        return $earea;
    }

    public function getAll($dfrom, $dto, $iarea){
        if ($iarea=='NA') {
            return $this->db->query("
                SELECT
                    a.i_nota,
                    TO_CHAR(a.d_nota, 'dd-mm-yyyy') AS d_nota,
                    a.i_sj,
                    b.e_customer_name,
                    a.v_nota_netto,
                    a.v_nota_discount,
                    a.v_nota_discounttotal,
                    a.v_nota_discount1,
                    a.v_nota_discount2,
                    a.v_nota_discount3,
                    a.v_nota_discount4
                FROM
                    tm_nota a,
                    tr_customer b
                WHERE
                    (a.d_nota >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                    AND a.d_nota <= TO_DATE('$dto', 'dd-mm-yyyy'))
                    AND a.i_customer = b.i_customer
                    AND NOT a.i_nota ISNULL
                    AND a.f_nota_cancel = 'f'
                ORDER BY
                    a.i_area,
                    a.d_nota,
                    a.i_nota
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    a.i_nota,
                    TO_CHAR(a.d_nota, 'dd-mm-yyyy') AS d_nota,
                    a.i_sj,
                    b.e_customer_name,
                    a.v_nota_netto,
                    a.v_nota_discount,
                    a.v_nota_discounttotal,
                    a.v_nota_discount1,
                    a.v_nota_discount2,
                    a.v_nota_discount3,
                    a.v_nota_discount4
                FROM
                    tm_nota a,
                    tr_customer b
                WHERE
                    (a.d_nota >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                    AND a.d_nota <= TO_DATE('$dto', 'dd-mm-yyyy'))
                    AND a.i_customer = b.i_customer
                    AND NOT a.i_nota ISNULL
                    AND a.f_nota_cancel = 'f'
                    AND a.i_area = '$iarea'
                ORDER BY
                    a.i_area,
                    a.d_nota,
                    a.i_nota
            ", FALSE);
        }
    }
}

/* End of file Mmaster.php */
