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

    public function bacastore($iarea, $username, $idcompany){
        if ($iarea=='00') {
            $this->db->select('distinct(c.i_store) AS i_store,a.i_store_location,a.e_store_locationname,b.e_store_name');
            $this->db->from('tr_store_location a');
            $this->db->join('tr_store b','b.i_store = a.i_store');
            $this->db->join('tr_area c','c.i_store = b.i_store');
            $this->db->order_by('c.i_store');
            return $this->db->get()->result();
        }else{
            $this->db->select("
                    DISTINCT(c.i_store) AS i_store ,
                    a.i_store_location,
                    a.e_store_locationname,
                    b.e_store_name
                FROM
                    tr_store_location a,
                    tr_store b,
                    tr_area c
                WHERE
                    a.i_store = b.i_store
                    AND b.i_store = c.i_store
                    AND c.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
                ORDER BY
                    c.i_store
            ", FALSE);
            return $this->db->get()->result();
        }
    }

    /*public function jmldetail($istore,$istorelocation,$iperiode){
        $this->db->select('a.i_product');
        $this->db->from('tm_mutasi a');
        $this->db->join('tr_product b','b.i_product = a.i_product');
        $this->db->join('tr_product_motif c','c.i_product_motif = a.i_product_motif');
        $this->db->where('a.e_mutasi_periode',$iperiode);
        $this->db->where('a.i_store',$istore);
        $this->db->where('a.i_store_location',$istorelocation);
        $query = $this->db->get();
        return $query->num_rows();
    }*/

    public function bacamutasi($istore,$istorelocation,$iperiode,$fpaw,$fpak,$username,$idcompany){
        /*$this->db->select("
                a.*,
                b.e_product_name,
                c.e_product_motifname,
                b.v_product_mill
            FROM
                tm_mutasi a,
                tr_product b,
                tr_product_motif c
            WHERE
                a.i_product = b.i_product
                AND a.i_product = c.i_product
                AND a.i_product_motif = c.i_product_motif
                AND a.e_mutasi_periode = '$iperiode'
                AND a.i_store = '$istore'
                AND a.i_store_location = '$istorelocation'
            ORDER BY
                a.i_product
        ", false);*/
        $this->db->select("
                c.i_product_motif,
                a.i_product,
                b.e_product_name,
                c.e_product_motifname,
                b.v_product_mill,
                COALESCE(sum(x.vrata),0) AS vrata,
                COALESCE(sum(x.nrata),0) AS nrata
            FROM
                tm_mutasi a
            INNER JOIN tr_product b ON
                (a.i_product = b.i_product)
            INNER JOIN tr_product_motif c ON
                (a.i_product = c.i_product
                AND a.i_product_motif = c.i_product_motif)
            LEFT JOIN (
                SELECT
                    TRUNC(SUM(n_deliver*v_unit_price)/ 3) AS vrata,
                    TRUNC(SUM(n_deliver)/ 3) AS nrata,
                    i_product
                FROM
                    tm_nota_item
                WHERE
                    i_nota > '$fpaw'
                    AND i_nota < '$fpak'
                    AND i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')
                    GROUP BY i_product) AS x ON (x.i_product = b.i_product)
            WHERE
                a.e_mutasi_periode = '$iperiode'
                AND a.i_store = '$istore'
                AND a.i_store_location = '$istorelocation'
            GROUP BY
                c.i_product_motif,
                a.i_product,
                b.e_product_name,
                c.e_product_motifname,
                b.v_product_mill
            ORDER BY
                i_product
        ", FALSE);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query;
        }else{
            return 0;
        }
    }

    public function runningnumber($thbl){
        $th   = '20'.substr($thbl,0,2);
        $asal='20'.$thbl;
        $thbl=substr($thbl,0,2).substr($thbl,2,2);
        $this->db->select(" 
            n_modul_no as max 
            FROM tm_dgu_no 
            WHERE i_modul='SPM'
            AND substr(e_periode,1,4)='$th' 
            FOR UPDATE
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nospmb  =$terakhir+1;
            $this->db->query(" 
                UPDATE tm_dgu_no 
                SET n_modul_no = $nospmb
                WHERE i_modul = 'SPM'
                AND substr(e_periode,1,4) = '$th' 
            ", false);
            settype($nospmb,"string");
            $a=strlen($nospmb);
            while($a<6){
                $nospmb="0".$nospmb;
                $a=strlen($nospmb);
            }
            $nospmb  ="SPMB-".$thbl."-".$nospmb;
            return $nospmb;
        }else{
            $nospmb  ="000001";
            $nospmb  ="SPMB-".$thbl."-".$nospmb;
            $this->db->query(" 
                INSERT INTO tm_dgu_no
                (i_modul, i_area, e_periode, n_modul_no) 
                VALUES ('SPM','00','$asal',1)");
            return $nospmb;
        }
    }

    public function insertheader($ispmb, $dspmb, $iarea, $fop, $nprint, $ispmbold, $eremark){
        $this->db->set(
            array(
                'i_spmb'    => $ispmb,
                'd_spmb'    => $dspmb,
                'i_area'    => $iarea,
                'f_op'      => 'f',
                'n_print'   => 0,
                'i_spmb_old'=> $ispmbold,
                'e_remark'  => $eremark,
                'd_entry'   => current_datetime()
            )
        );        
        $this->db->insert('tm_spmb');
    }

    public function insertdetail($ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$nacc,$vunitprice,$iproductmotif,$eremark,$iarea,$i){
        $this->db->set(
            array(
                'i_spmb'            => $ispmb,
                'i_product'         => $iproduct,
                'i_product_grade'   => $iproductgrade,
                'i_product_motif'   => $iproductmotif,
                'n_order'           => $norder,
                'n_acc'             => $nacc,
                'v_unit_price'      => $vunitprice,
                'e_product_name'    => $eproductname,
                'i_area'            => $iarea,
                'e_remark'          => $eremark,
                'n_item_no'         => $i
            )
        );
        $this->db->insert('tm_spmb_item');
    }
}

/* End of file Mmaster.php */