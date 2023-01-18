<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

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

    public function sumperiode($dfrom,$dto){
        $perfrom=substr($dfrom,6,4).substr($dfrom,3,2);
        $perto  =substr($dto,6,4).substr($dto,3,2);
        $query = $this->db->query("
            SELECT
                a.bln,
                sum(a.total) AS total
            FROM
                (
                SELECT
                    a.i_area, a.e_area_name, substring(b.e_periode, 5, 2) AS bln, sum(b.v_target_tagihan) AS total, sum(b.v_realisasi_tagihan) AS realisasi
                FROM
                    tm_collection c, tm_collection_item b, tr_area a
                WHERE
                    a.i_area = b.i_area
                    AND b.e_periode = c.e_periode
                    AND b.e_periode >= '$perfrom'
                    AND b.e_periode <= '$perto'
                GROUP BY
                    a.i_area, a.e_area_name, substring(b.e_periode, 5, 2)) AS a
            GROUP BY
                a.bln
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function bacaperiode($dfrom,$dto,$interval){
        $perfrom = substr($dfrom,6,4).substr($dfrom,3,2);
        $perto   = substr($dto,6,4).substr($dto,3,2);
        $query   = $this->db->query("
            SELECT
                a.i_area,
                a.e_area_name,
                a.bln,
                sum(a.total) AS total,
                sum(a.realisasi) AS realisasi
            FROM
                (
                SELECT
                    a.i_area, a.e_area_name, substring(b.e_periode, 5, 2) AS bln, sum(b.v_target_tagihan) AS total, sum(b.v_realisasi_tagihan) AS realisasi
                FROM
                    tm_collection c, tm_collection_item b, tr_area a
                WHERE
                    a.i_area = b.i_area
                    AND c.e_periode = b.e_periode
                    AND b.e_periode >= '$perfrom'
                    AND b.e_periode <= '$perto'
                    AND b.f_insentif = 't'
                GROUP BY
                    a.i_area, a.e_area_name, substring(b.e_periode, 5, 2)) AS a
            GROUP BY
                a.i_area,
                a.e_area_name,
                a.bln
            ORDER BY
                a.i_area,
                a.e_area_name,
                a.bln
            ", FALSE);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
