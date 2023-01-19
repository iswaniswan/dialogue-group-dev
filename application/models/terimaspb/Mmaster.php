<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder, $username, $id_company, $total){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                ROW_NUMBER() OVER(ORDER BY tm_spb.i_spb) AS i,
                tm_spb.i_spb,
                tm_spb.i_area,
                to_char(tm_spb.d_spb, 'dd-mm-yyyy') AS dspb,
                tr_customer.e_customer_name,
                e_area_name,
                tm_spb.f_spb_stockdaerah,
                '$folder' AS folder,
                '$total' AS total
            FROM
                tm_spb
            INNER JOIN tr_customer ON
                (tm_spb.i_customer = tr_customer.i_customer)
            INNER JOIN tr_salesman ON
                (tm_spb.i_salesman = tr_salesman.i_salesman)
            INNER JOIN tr_customer_area ON
                (tm_spb.i_customer = tr_customer_area.i_customer)
            INNER JOIN tr_price_group ON
                (tm_spb.i_price_group = tr_price_group.i_price_group)
            WHERE
                ((tm_spb.f_spb_stockdaerah = 'f'
                AND NOT tm_spb.i_approve1 IS NULL
                AND NOT tm_spb.i_approve2 IS NULL
                AND tm_spb.i_store ISNULL
                AND tm_spb.i_store_location ISNULL
                AND tm_spb.d_spb_storereceive ISNULL)
                OR (tm_spb.f_spb_stockdaerah = 't'
                AND tm_spb.d_spb_storereceive ISNULL))
                AND tm_spb.f_spb_cancel = 'f'
                AND tm_spb.i_nota IS NULL
                AND tm_spb.i_sj IS NULL
                AND tm_spb.i_area IN(
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$id_company')
            ORDER BY
                tm_spb.i_area,
                tm_spb.i_spb", false);
        $datatables->edit('f_spb_stockdaerah', function($data){
            if($data['f_spb_stockdaerah'] == "t"){
                return "Ya";
            }else{
                return "Tidak";
            }
        });
        $datatables->add('action', function ($data) {
            $ispb   = trim($data['i_spb']);
            $iarea  = trim($data['i_area']);
            $i      = trim($data['i']);
            $folder = $data['folder'];
            $total  = $data['total'];
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

    public function total($username, $id_company){
        return $this->db->query("       
                SELECT 
                    ROW_NUMBER() OVER(
                    ORDER BY tm_spb.i_spb) AS i,
                    tm_spb.i_spb,
                    tm_spb.i_area,
                    TO_CHAR(tm_spb.d_spb, 'dd-mm-yyyy') AS dspb,
                    tr_customer.e_customer_name,
                    e_area_name,
                    tm_spb.f_spb_stockdaerah,
                    'terimaspb' AS folder
                FROM
                    tm_spb
                INNER JOIN tr_customer ON
                    (tm_spb.i_customer = tr_customer.i_customer)
                INNER JOIN tr_salesman ON
                    (tm_spb.i_salesman = tr_salesman.i_salesman)
                INNER JOIN tr_customer_area ON
                    (tm_spb.i_customer = tr_customer_area.i_customer)
                INNER JOIN tr_price_group ON
                    (tm_spb.i_price_group = tr_price_group.i_price_group)
                WHERE
                    ((tm_spb.f_spb_stockdaerah = 'f'
                    AND NOT tm_spb.i_approve1 IS NULL
                    AND NOT tm_spb.i_approve2 IS NULL
                    AND tm_spb.i_store ISNULL
                    AND tm_spb.i_store_location ISNULL
                    AND tm_spb.d_spb_storereceive ISNULL)
                    OR (tm_spb.f_spb_stockdaerah = 't'
                    AND tm_spb.d_spb_storereceive ISNULL))
                    AND tm_spb.f_spb_cancel = 'f'
                    AND tm_spb.i_nota IS NULL
                    AND tm_spb.i_sj IS NULL
                    AND tm_spb.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$id_company')
                ORDER BY
                    tm_spb.i_area,
                    tm_spb.i_spb", false);
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
        $dspbstorereceive = current_datetime(); 
        $data = array(         
            'd_spb_storereceive' => $dspbstorereceive     
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data); 
    }
}

/* End of file Mmaster.php */
