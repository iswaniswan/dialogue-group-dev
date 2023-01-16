<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function data($dfrom, $dto, $folder, $title){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT	
                '$dfrom' as dfrom,
                '$dto' as dto,
                e_area_name,
                i_salesman,
                e_salesman_name,
                sum(v_sj) as v_sj, 
                sum(v_nota) as v_nota,
                '$title' as title,
                '$folder' as folder  
            FROM (
                SELECT 
                    sum(a.v_nota_gross) as v_nota, 
                    sum(a.v_nota_discounttotal) as v_nota_discounttotal, 
                    0 as v_sj, 
                    0 as v_sj_discounttotal, 
                    a.i_area,
                    b.e_area_name, 
                    a.i_salesman, 
                    c.e_salesman_name
                FROM 
                    tm_nota a, 
                    tr_area b, 
                    tr_salesman c
                WHERE 
                    a.d_sj >= '$dfrom'
                    and a.d_sj <= '$dto'
                    and a.f_nota_cancel='f'
                    and a.i_area=b.i_area 
                    and a.i_salesman=c.i_salesman 
                    and not i_nota isnull
                GROUP BY 
                    a.i_area, 
                    b.e_area_name, 
                    a.i_salesman, 
                    c.e_salesman_name 
            UNION ALL
                SELECT 
                    0 as v_nota, 
                    0 as v_nota_discounttotal, 
                    sum(a.v_nota_gross) as v_sj, 
                    sum(a.v_nota_discounttotal) as v_sj_discounttotal, 
                    a.i_area, 
                    b.e_area_name, 
                    a.i_salesman, 
                    c.e_salesman_name
                FROM 
                    tm_nota a, 
                    tr_area b, 
                    tr_salesman c
                WHERE 
                    a.d_sj >= '$dfrom'
                    and a.d_sj <= '$dto'
                    and a.f_nota_cancel='f'
                    and a.i_area=b.i_area 
                    and a.i_salesman=c.i_salesman 
                    and i_nota isnull
            GROUP BY 
                a.i_area, 
                b.e_area_name, 
                a.i_salesman, 
                c.e_salesman_name 
            ) AS x
            GROUP BY 
                x.i_area, 
                x.e_area_name, 
                x.i_salesman, 
                x.e_salesman_name 
            ORDER BY 
                x.i_area, 
                x.i_salesman 
            ",false);
        
        $datatables->edit('v_sj', function($data){
            return number_format($data['v_sj']);
        });

        $datatables->edit('dfrom', function($data){
            return date("d-m-Y", strtotime($data['dfrom']));
        });

        $datatables->edit('dto', function($data){
            return date("d-m-Y", strtotime($data['dto']));
        });

        $datatables->edit('v_nota', function($data){
            return number_format($data['v_nota']);
        });

        $datatables->add('action', function ($data) {
            $isalesman  = $data['i_salesman'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $title      = $data['title'];
            $data       = '';
           // $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$inota/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        return $datatables->generate();
    }

    public function cetak($dfrom, $dto){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        return $this->db->query("
                            select	
                                sum(v_nota) as v_nota, 
                                sum(v_sj) as v_sj
                            from (
                                select 
                                    sum(a.v_nota_gross) as v_nota, 
                                    sum(a.v_nota_discounttotal) as v_nota_discounttotal, 
                                    0 as v_sj, 
                                    0 as v_sj_discounttotal, 
                                    a.i_area, b.e_area_name, 
                                    a.i_salesman, 
                                    c.e_salesman_name
                                from 
                                    tm_nota a, 
                                    tr_area b, 
                                    tr_salesman c
                                where 
                                    a.d_sj >= '$dfrom'
                                    and a.d_sj <= '$dto'
                                    and a.f_nota_cancel='f'
                                    and a.i_area=b.i_area
                                    and a.i_salesman=c.i_salesman 
                                    and not i_nota isnull
                                group by 
                                    a.i_area, 
                                    b.e_area_name, 
                                    a.i_salesman, 
                                    c.e_salesman_name 
                                union all
                                select 
                                    0 as v_nota, 
                                    0 as v_nota_discounttotal, 
                                    sum(a.v_nota_gross) as v_sj, 
                                    sum(a.v_nota_discounttotal) as v_sj_discounttotal, 
                                    a.i_area, b.e_area_name, 
                                    a.i_salesman, 
                                    c.e_salesman_name
                                from 
                                    tm_nota a, 
                                    tr_area b, 
                                    tr_salesman c
                                where 
                                    a.d_sj >= '$dfrom'
                                    and a.d_sj <= '$dto'
                                    and a.f_nota_cancel='f'
                                    and a.i_area=b.i_area 
                                    and a.i_salesman=c.i_salesman 
                                    and i_nota isnull
                                group by 
                                    a.i_area, 
                                    b.e_area_name, 
                                    a.i_salesman, 
                                    c.e_salesman_name 
                            ) as x
        ", false);
    }

    public function total($dfrom, $dto){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        return $this->db->query("
        SELECT
            sum(v_sj) as totalsj,
            sum(v_nota) as totalnota
        FROM(
            SELECT	
            e_area_name,
            i_salesman,
            e_salesman_name,
            sum(v_sj) as v_sj, 
            sum(v_nota) as v_nota
            FROM (
                SELECT 
                    sum(a.v_nota_gross) as v_nota, 
                    sum(a.v_nota_discounttotal) as v_nota_discounttotal, 
                    0 as v_sj, 
                    0 as v_sj_discounttotal, 
                    a.i_area,
                    b.e_area_name, 
                    a.i_salesman, 
                    c.e_salesman_name
                FROM 
                    tm_nota a, 
                    tr_area b, 
                    tr_salesman c
                WHERE 
                    a.d_sj >='$dfrom'
                    and a.d_sj <='$dto'
                    and a.f_nota_cancel='f'
                    and a.i_area=b.i_area 
                    and a.i_salesman=c.i_salesman 
                    and not i_nota isnull
                GROUP BY 
                    a.i_area, 
                    b.e_area_name, 
                    a.i_salesman, 
                    c.e_salesman_name 
            UNION ALL
                SELECT 
                    0 as v_nota, 
                    0 as v_nota_discounttotal, 
                    sum(a.v_nota_gross) as v_sj, 
                    sum(a.v_nota_discounttotal) as v_sj_discounttotal, 
                    a.i_area, 
                    b.e_area_name, 
                    a.i_salesman, 
                    c.e_salesman_name
                FROM 
                    tm_nota a, 
                    tr_area b, 
                    tr_salesman c
                WHERE 
                    a.d_sj >= '$dfrom' 
                    and a.d_sj <= '$dto'
                    and a.f_nota_cancel='f'
                    and a.i_area=b.i_area 
                    and a.i_salesman=c.i_salesman 
                    and i_nota isnull
                GROUP BY 
                    a.i_area, 
                    b.e_area_name, 
                    a.i_salesman, 
                    c.e_salesman_name 
                ) AS x
            GROUP BY 
                x.i_area, 
                x.e_area_name, 
                x.i_salesman, 
                x.e_salesman_name) as b
        ", false);
    }
}

/* End of file Mmaster.php */
