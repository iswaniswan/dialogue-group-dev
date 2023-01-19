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
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $iarea = $row->i_area;
        }
        return $iarea;
    }

    public function getarea($iarea, $username, $idcompany){
        if ($iarea=='00') {
            $this->db->select('distinct on (a.i_store) a.i_store, b.e_store_name, c.e_store_locationname');
            $this->db->from('tr_area a');
            $this->db->join('tr_store b','b.i_store = a.i_store');
            $this->db->join('tr_store_location c','c.i_store = b.i_store');
            $this->db->order_by('a.i_store');
            return $this->db->get()->result();
        }else{
            $this->db->select("
                    DISTINCT (b.i_store),
                    b.e_store_name,
                    c.i_store_location,
                    c.e_store_locationname
                FROM
                    tr_area a,
                    tr_store b,
                    tr_store_location c
                WHERE
                    a.i_store = b.i_store
                    AND b.i_store = c.i_store
                    AND a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
                ORDER BY
                    b.i_store,
                    c.i_store_location  
            ",false);
            return $this->db->get()->result();
        }
    }

    public function getso($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_stockopname,
                to_char(d_stockopname, 'dd-mm-yyyy') AS d_so,
                a.i_store,
                a.i_store_location,
                c.e_store_name,
                b.e_store_locationname
            FROM
                tm_stockopname a,
                tr_store_location b,
                tr_store c
            WHERE
                a.f_stockopname_cancel = 'f'
                AND a.i_area = '$iarea'
                AND a.i_store = c.i_store
                AND a.i_store = b.i_store
                AND a.i_store_location = b.i_store_location
                AND UPPER(a.i_stockopname) LIKE '%$cari%'
            ORDER BY
                a.i_stockopname DESC", 
        FALSE);
    } 

    public function getproduct($cari, $istore, $istorelocation){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                d.i_product_grade AS grade,
                c.e_product_name AS nama,
                c.v_product_mill AS harga
            FROM
                tr_product_motif a,
                tr_product c,
                tm_ic d
            WHERE
                a.i_product = c.i_product
                AND c.i_product = d.i_product
                AND d.i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')
            ORDER BY
                c.i_product,
                a.e_product_motifname",
        FALSE);
    } 

    public function getdetailproduct($iproduct, $istore, $istorelocation){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                d.i_product_grade AS grade,
                c.e_product_name AS nama,
                c.v_product_mill AS harga
            FROM
                tr_product_motif a,
                tr_product c,
                tm_ic d
            WHERE
                a.i_product = c.i_product
                AND c.i_product = d.i_product
                AND d.i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND a.i_product = '$iproduct'
            ORDER BY
                c.i_product,
                a.e_product_motifname",
        FALSE);
    } 

    public function runningnumber($thbl,$iarea){
        $th   = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" 
                n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'ADJ'
                AND i_area = '$iarea'
                AND SUBSTRING(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $noadj = $terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $noadj
                WHERE
                    i_modul = 'ADJ'
                    AND i_area = '$iarea'
                    AND SUBSTRING(e_periode, 1, 4)= '$th'
            ", false);
            settype($noadj,"string");
            $a = strlen($noadj);
            while($a<6){
                $noadj="0".$noadj;
                $a=strlen($noadj);
            }
            $noadj = "ADJ-".$thbl."-".$noadj;
            return $noadj;
        }else{
            $noadj = "000001";
            $noadj = "ADJ-".$thbl."-".$noadj;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('ADJ',
                '$iarea',
                '$asal',
                1)
            ");
            return $noadj;
        }
    }

    public function insertheader($iadj, $iarea, $dadj, $istockopname, $eremark, $istore, $istorelocation){
        $now      = current_datetime();
        $this->db->set(
            array(
                'i_adj'            => $iadj,
                'i_area'           => $iarea,
                'd_adj'            => $dadj,
                'i_stockopname'    => $istockopname,
                'i_store'          => $istore, 
                'i_store_location' => $istorelocation,               
                'e_remark'         => $eremark,
                'd_entry'          => $now
            )
        );
        $this->db->insert('tm_adj');
    }

    public function insertdetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i){
        $this->db->set(
            array(
                'i_adj'           => $iadj,
                'i_area'          => $iarea,
                'i_product'       => $iproduct,
                'i_product_grade' => $iproductgrade,
                'i_product_motif' => $iproductmotif,
                'n_quantity'      => $nquantity,
                'e_product_name'  => $eproductname,
                'e_remark'        => $eremark,
                'n_item_no'       => $i
            )
        );
        $this->db->insert('tm_adj_item');
    }
}

/* End of file Mmaster.php */