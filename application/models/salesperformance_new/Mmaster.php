<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($dfrom,$dto){
        if($dfrom){
            $thbl  = date('Ym', strtotime($dfrom));
            $dfrom = date('Y-m-d', strtotime($dfrom));
        }

        if($dto){
            $thnbln = date('Ym', strtotime($dto));
            $dto    = date('Y-m-d', strtotime($dto));
        }
        $this->load->database('101');
        $query = $this->db->query("
            SELECT
                a.i_periode,
                SUM(a.target) AS target,
                SUM(a.spb) AS spb,
                SUM(a.sj) AS sj,
                SUM(a.nota) AS nota
            FROM
                (
                SELECT
                    i_periode,
                    SUM(v_target) AS target,
                    0 AS spb,
                    0 AS sj,
                    0 AS nota
                FROM
                    tm_target
                WHERE
                    i_periode >= '$thbl'
                    AND i_periode <= '$thnbln'
                GROUP BY
                    i_periode
            UNION ALL
                SELECT
                    TO_CHAR(d_spb, 'yyyymm') AS i_periode,
                    0 AS target,
                    SUM(v_spb-v_spb_discounttotal) AS spb,
                    0 AS sj,
                    0 AS nota
                FROM
                    tm_spb
                WHERE
                    d_spb >= '$dfrom'
                    AND d_spb <= '$dto'
                    AND f_spb_cancel = 'f'
                GROUP BY
                    TO_CHAR(d_spb, 'yyyymm')
            UNION ALL
                SELECT
                    TO_CHAR(d_sj, 'yyyymm') AS i_periode,
                    0 AS target,
                    0 AS spb,
                    SUM(v_nota_netto) AS sj,
                    0 AS nota
                FROM
                    tm_nota
                WHERE
                    d_sj >= '$dfrom'
                    AND d_sj <= '$dto'
                    AND f_nota_cancel = 'f'
                GROUP BY
                    TO_CHAR(d_sj, 'yyyymm')
            UNION ALL
                SELECT
                    TO_CHAR(d_nota, 'yyyymm') AS i_periode,
                    0 AS target,
                    0 AS spb,
                    0 AS sj,
                    SUM(v_nota_netto) AS nota
                FROM
                    tm_nota
                WHERE
                    d_nota >= '$dfrom'
                    AND d_nota <= '$dto'
                    AND f_nota_cancel = 'f'
                    AND NOT i_nota ISNULL
                GROUP BY
                    TO_CHAR(d_nota, 'yyyymm') ) AS a
            GROUP BY
                a.i_periode
            ORDER BY
                a.i_periode
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
        $this->load->database();
    }
}
