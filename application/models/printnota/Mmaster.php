<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
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
        ", FALSE);
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_nota,
                to_char(d_nota, 'dd-mm-yyyy') AS d_nota,    
                e_customer_name,
                n_print,
                '$folder' AS folder
            FROM
                tm_nota a,
                tr_customer b
            WHERE
                a.i_customer = b.i_customer
                AND f_ttb_tolak = 'f'
                AND NOT a.i_nota ISNULL
                AND a.f_nota_cancel = 'f'
                AND a.n_print = 0
                AND a.i_area = '$iarea'
                AND a.d_nota >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_nota <= to_date('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.i_nota DESC
        ", FALSE);
        $datatables->add('action', function ($data) {
            $id             = trim($data['i_nota']);
            $n_print        = $data['n_print'];
            $folder         = $data['folder'];
            $data           = '';
            if ($n_print == 0) {
                $data          .= "<a href=\"#\" title='Print Exclude' onclick='printy(\"$id\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
            }
            if ($this->session->userdata('username')=='admin') {
                $data          .= "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"#\" title='Print Include' onclick='printx(\"$id\",\"#main\"); return false;'><i class='fa fa-print'></i></a>";
            }
            return $data;
        });

        $datatables->edit('n_print', function ($data) {
            if ($data['n_print']=='0') {
                $data = '<span class="label label-info label-rouded">BELUM</span>';
            }else{
                $data = '<span class="label label-success label-rouded">SUDAH</span>';
            }
            return $data;
        });
        $datatables->hide('folder');

        return $datatables->generate();
    }

    public function baca($id){   
        $this->db->select("
                *
            FROM
                tm_nota
            INNER JOIN tm_spb ON
                (tm_nota.i_spb = tm_spb.i_spb
                AND tm_nota.i_area = tm_spb.i_area)
            INNER JOIN tr_customer ON
                (tm_nota.i_customer = tr_customer.i_customer)
            INNER JOIN tr_customer_owner ON
                (tm_nota.i_customer = tr_customer_owner.i_customer)
            INNER JOIN tr_salesman ON
                (tm_nota.i_salesman = tr_salesman.i_salesman)
            LEFT JOIN tr_customer_pkp ON
                (tm_nota.i_customer = tr_customer_pkp.i_customer)
            LEFT JOIN tr_customer_va ON
                (tm_nota.i_customer = tr_customer_va.i_customer)
            WHERE
                tm_nota.i_nota = '$id'
                AND tm_nota.n_print = 0
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacadetail($id){
        $this->db->select("
                *
            FROM
                tm_nota_item
            INNER JOIN tr_product_motif ON
                (tm_nota_item.i_product_motif = tr_product_motif.i_product_motif
                AND tm_nota_item.i_product = tr_product_motif.i_product)
            WHERE
                tm_nota_item.i_nota = '$id'
            ORDER BY
                tm_nota_item.n_item_no
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function close($id){
        $date = current_datetime();
        return $this->db->query("
            UPDATE
                tm_nota
            SET
                d_nota_print = '$date',
                n_print = n_print + 1
            WHERE
                i_nota = '$id'
        ",false);
    }

    public function company($id_company){
        return $this->db->query("
            SELECT
                *
            FROM
                public.company
            WHERE
                id = '$id_company'
        ", FALSE);
    }
}

/* End of file Mmaster.php */
