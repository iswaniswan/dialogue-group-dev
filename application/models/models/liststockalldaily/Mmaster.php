<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($tgl,$iperiode){
        if($iperiode <= '201901'){
            $this->db->select("
                    i_product,
                    e_product_name,
                    sum(hiji) AS hiji,
                    sum(dua) AS dua,
                    sum(tilu) AS tilu,
                    sum(opat) AS opat,
                    sum(lima) AS lima,
                    sum(tujuh) AS tujuh,
                    sum(salapan) AS salapan,
                    sum(sapuluh) AS sapuluh,
                    sum(sabelas) AS sabelas,
                    sum(duabelas) AS duabelas,
                    sum(tujuhbelas) AS tujuhbelas,
                    sum(duatilu) AS duatilu,
                    sum(tiluhiji) AS tiluhiji,
                    sum(AA) AS AA,
                    sum(PB) AS PB
                FROM
                    (
                    SELECT
                        i_product, e_product_name, hiji, dua, tilu, opat, lima, tujuh, salapan, sapuluh, sabelas, duabelas, tujuhbelas, duatilu, tiluhiji, AA, PB
                    FROM
                        crosstab( 'SELECT i_product, e_product_name, i_store, sum(qty) as qty from f_all_saldoakhir_daily(''$iperiode'', ''$tgl'') 
                            group by i_product, e_product_name, i_store', 'select distinct (i_store) as i_store from f_all_saldoakhir_daily(''$iperiode'', ''$tgl'') 
                            order by i_store' )AS (i_product TEXT, e_product_name TEXT, hiji integer, dua integer, tilu integer, opat integer, lima integer, tujuh integer, salapan integer, sapuluh integer, sabelas integer, duabelas integer, tujuhbelas integer, duatilu integer, tiluhiji integer, AA integer, PB integer) ) AS a
                GROUP BY
                    i_product,
                    e_product_name
                ORDER BY
                    i_product,
                    e_product_name
            ",false);        
        }else{
            $this->db->select("
                i_product,
                e_product_name,
                sum(hiji) AS hiji,
                sum(dua) AS dua,
                sum(tilu) AS tilu,
                sum(opat) AS opat,
                sum(lima) AS lima,
                sum(tujuh) AS tujuh,
                sum(salapan) AS salapan,
                sum(sapuluh) AS sapuluh,
                sum(sabelas) AS sabelas,
                sum(duabelas) AS duabelas,
                sum(tilubelas) AS tilubelas,
                sum(tujuhbelas) AS tujuhbelas,
                sum(duatilu) AS duatilu,
                sum(tiluhiji) AS tiluhiji,
                sum(AA) AS AA,
                sum(PB) AS PB
            FROM
                (
                SELECT
                    i_product, e_product_name, hiji, dua, tilu, opat, lima, tujuh, salapan, sapuluh, sabelas, duabelas, tilubelas, tujuhbelas, duatilu, tiluhiji, AA, PB
                FROM
                    crosstab( 'SELECT i_product, e_product_name, i_store, sum(qty) as qty from f_all_saldoakhir_daily(''$iperiode'', ''$tgl'') 
                        group by i_product, e_product_name, i_store', 'select distinct (i_store) as i_store from f_all_saldoakhir_daily(''$iperiode'', ''$tgl'') 
                        order by i_store' )AS (i_product TEXT, e_product_name TEXT, hiji integer, dua integer, tilu integer, opat integer, lima integer, tujuh integer, salapan integer, sapuluh integer, sabelas integer, duabelas integer, tilubelas integer, tujuhbelas integer, duatilu integer, tiluhiji integer, AA integer, PB integer) ) AS a
            GROUP BY
                i_product,
                e_product_name
            ORDER BY
                i_product,
                e_product_name
            ",false);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
        $query->free_result();
    }

    public function bacastore($tgl,$iperiode){
        $this->db->select("
            DISTINCT (i_store) AS i_store
            FROM
                f_all_saldoakhir_daily('$iperiode', '$tgl')
            ORDER BY
                i_store
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
