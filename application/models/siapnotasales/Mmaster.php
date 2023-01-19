<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder, $total){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT ROW_NUMBER() OVER(
                ORDER BY a.i_spb) AS i,
                a.i_spb,
                TO_CHAR(d_spb, 'dd-mm-yyyy') AS dspb,
                c.e_area_name,
                e_customer_name,
                a.i_area,
                a.i_price_group,
                '$folder' AS folder,
                '$total' AS total
            FROM
                tm_spb a,
                tr_customer b,
                tr_area c
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = c.i_area
                AND a.f_spb_cancel = 'f'
                AND a.f_spb_valid = 'f'
                AND a.f_spb_siapnotagudang = 't'
                AND a.f_spb_siapnotasales = 'f'
                AND a.i_store = 'AA'
                AND (UPPER(a.i_customer) LIKE '%%'
                OR UPPER(b.e_customer_name) LIKE '%%'
                OR UPPER(a.i_spb) LIKE '%%')
            ORDER BY
                a.i_spb DESC", false);
        $datatables->add('action', function ($data) {
            $ispb   = trim($data['i_spb']);
            $iarea  = trim($data['i_area']);
            $ipgroup= trim($data['i_price_group']);
            $i      = trim($data['i']);
            $folder = $data['folder'];
            $total  = $data['total'];
            $data   = '';
            $data  .= "<input id=\"jml\" name=\"jml\" value=\"".$total."\" type=\"hidden\">&nbsp;&nbsp;&nbsp;&nbsp;
                       <a href=\"#\" onclick='show(\"$folder/cform/detail/$ispb/$iarea/$ipgroup\",\"#main\"); return false;'>
                       <i class='fa fa-pencil'></i></a>&nbsp;&nbsp;<label class=\"custom-control custom-checkbox\">
                       <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                       <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                       <input name=\"ispb".$i."\" value=\"".$ispb."\" type=\"hidden\">
                       <input name=\"iarea".$i."\" value=\"".$iarea."\" type=\"hidden\">";
            return $data;
        });
        $datatables->hide('i_area');
        $datatables->hide('i_price_group');
        $datatables->hide('i');
        $datatables->hide('folder');
        $datatables->hide('total');
        return $datatables->generate();
    }

    public function total(){
        return $this->db->query("       
            SELECT
                a.i_spb,
                TO_CHAR(d_spb, 'dd-mm-yyyy') AS dspb,
                c.e_area_name,
                e_customer_name,
                a.i_area
            FROM
                tm_spb a,
                tr_customer b,
                tr_area c
            WHERE
                a.i_customer = b.i_customer
                AND a.i_area = c.i_area
                AND a.f_spb_cancel = 'f'
                AND a.f_spb_valid = 'f'
                AND a.f_spb_siapnotagudang = 't'
                AND a.f_spb_siapnotasales = 'f'
                AND a.i_store = 'AA'
                AND (UPPER(a.i_customer) LIKE '%%'
                OR UPPER(b.e_customer_name) LIKE '%%'
                OR UPPER(a.i_spb) LIKE '%%')
            ORDER BY
                a.i_spb DESC", false);
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

    public function bacadetail($ispb, $iarea, $ipgroup){
        $this->db->select("
                a.i_spb,
                a.i_product,
                a.i_product_grade,
                a.i_product_motif,
                a.n_order,
                a.n_deliver,
                a.n_stock,
                a.v_unit_price,
                a.e_product_name,
                a.i_op,
                a.i_area,
                a.e_remark ,
                a.n_item_no,
                b.e_product_motifname,
                c.v_product_retail AS hrgnew,
                a.i_product_status
            FROM
                tm_spb_item a,
                tr_product_motif b,
                tr_product_price c
            WHERE
                a.i_spb = '$ispb'
                AND i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
                AND a.i_product = c.i_product
                AND c.i_price_group = '$ipgroup'
                AND a.i_product_grade = c.i_product_grade
            ORDER BY
                a.n_item_no", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function updatespb($ispb, $iarea){
        $data = array(         
            'f_spb_siapnotasales' => 't',
            'f_spb_valid'         => 't'    
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }

    public function updateheader($ispb, $iarea, $dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment,$vspbdiscounttotalafter,$vspbafter){
        $dspbupdate   = current_datetime();
        $data = array(          
            'i_price_group'               => $ipricegroup,
            'd_spb_receive'               => $dspb,
            'n_spb_discount1'             => $nspbdiscount1,
            'n_spb_discount2'             => $nspbdiscount2,
            'n_spb_discount3'             => $nspbdiscount3,
            'v_spb_discount1'             => $vspbdiscount1,
            'v_spb_discount2'             => $vspbdiscount2,
            'v_spb_discount3'             => $vspbdiscount3,
            'v_spb_discounttotal'         => $vspbdiscounttotal,
            'v_spb'                       => $vspb,
            'v_spb_discounttotalafter'    => $vspbdiscounttotalafter,
            'v_spb_after'                 => $vspbafter,
            'd_spb_update'                => $dspbupdate
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }

    public function updatedetail($ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i){
        if($eremark==''){
            $eremark=null;
        }
        $this->db->set(
            array(
                'n_deliver' => $ndeliver
            )
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_motif', $iproductmotif);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->update('tm_spb_item');
    }
}

/* End of file Mmaster.php */
