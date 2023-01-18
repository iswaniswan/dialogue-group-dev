<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder, $total){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                ROW_NUMBER() OVER(
                ORDER BY x.i_spb) AS i,
                x.i_spb,
                x.dspb,
                x.e_area_name,
                x.e_customer_name,
                x.i_area,
                '$folder' AS folder,
                '$total' AS total
            FROM
                (
                SELECT
                    DISTINCT(a.i_spb || a.i_area) AS ispbiarea,
                    a.i_spb,
                    TO_CHAR(d_spb, 'dd-mm-yyyy') AS dspb,
                    c.e_area_name,
                    e_customer_name,
                    a.i_area
                FROM
                    tm_spb a,
                    tr_customer b,
                    tr_area c,
                    tm_spb_item d
                LEFT JOIN tm_op e ON
                    (d.i_op = e.i_op
                    AND e.f_op_close = 't')
                WHERE
                    a.i_customer = b.i_customer
                    AND a.i_area = c.i_area
                    AND a.f_spb_cancel = 'f'
                    AND a.f_spb_valid = 'f'
                    AND a.f_spb_siapnotagudang = 'f'
                    AND a.f_spb_siapnotasales = 'f'
                    AND a.f_spb_pemenuhan = 't'
                    AND a.i_store = 'AA'
                    AND a.i_spb = d.i_spb
                    AND a.i_area = d.i_area
                ORDER BY
                    a.i_spb) AS x", false);
        $datatables->add('action', function ($data) {
            $ispb   = trim($data['i_spb']);
            $iarea  = trim($data['i_area']);
            $i      = trim($data['i']);
            $folder = $data['folder'];
            $total = $data['total'];
            $data   = '';
            $data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\">&nbsp;&nbsp;&nbsp;&nbsp;
                       <a href=\"#\" onclick='show(\"$folder/cform/detail/$ispb/$iarea\",\"#main\"); return false;'>
                       <i class='fa fa-pencil'></i></a>&nbsp;&nbsp;<label class=\"custom-control custom-checkbox\">
                       <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                       <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                       <input name=\"ispb".$i."\" value=\"".$ispb."\" type=\"hidden\">
                       <input name=\"iarea".$i."\" value=\"".$iarea."\" type=\"hidden\">";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('i');
        $datatables->hide('folder');
        $datatables->hide('total');
        return $datatables->generate();
    }

    public function total(){
        return $this->db->query("       
                SELECT
                ROW_NUMBER() OVER(
                ORDER BY x.i_spb) AS i,
                x.i_spb,
                x.dspb,
                x.e_area_name,
                x.e_customer_name,
                x.i_area
            FROM
                (
                SELECT
                    DISTINCT(a.i_spb || a.i_area) AS ispbiarea,
                    a.i_spb,
                    TO_CHAR(d_spb, 'dd-mm-yyyy') AS dspb,
                    c.e_area_name,
                    e_customer_name,
                    a.i_area
                FROM
                    tm_spb a,
                    tr_customer b,
                    tr_area c,
                    tm_spb_item d
                LEFT JOIN tm_op e ON
                    (d.i_op = e.i_op
                    AND e.f_op_close = 't')
                WHERE
                    a.i_customer = b.i_customer
                    AND a.i_area = c.i_area
                    AND a.f_spb_cancel = 'f'
                    AND a.f_spb_valid = 'f'
                    AND a.f_spb_siapnotagudang = 'f'
                    AND a.f_spb_siapnotasales = 'f'
                    AND a.f_spb_pemenuhan = 't'
                    AND a.i_store = 'AA'
                    AND a.i_spb = d.i_spb
                    AND a.i_area = d.i_area
                ORDER BY
                    a.i_spb) AS x", false);
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
    
    public function updatespb($ispb, $iarea){
        $data = array(         
            'f_spb_siapnotagudang' => 't'     
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }
}

/* End of file Mmaster.php */
