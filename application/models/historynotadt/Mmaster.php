<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area where f_area_real='t' order by i_area
        ", FALSE)->result();
    }

    public function cekperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = '';
        }
        return $iperiode;
    } 

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_supplier');
        $this->db->where('username',$username);
        $this->db->where('i_supplier','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $supplier = '00';
        }else{
            $supplier = 'xx';
        }
        return $supplier;
    }

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($dfrom, $dto, $iarea, $folder, $title){
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
            SELECT distinct a.i_nota, 
                a.d_nota, 
                a.i_area,
                b.e_customer_name,
                a.i_customer,
                a.v_nota_discounttotal, 
                a.v_nota_netto, 
                a.v_sisa, 
                a.f_nota_cancel,
                '$dfrom' as dfrom,
                '$dto' as dto,
                '$folder' as folder,
                '$title' as title
            FROM 
                tm_nota a, 
                tr_customer b, 
                tm_dt c, 
                tm_dt_item d
            WHERE 
                a.i_customer=b.i_customer 
                and c.f_dt_cancel='f' and c.i_dt=d.i_dt and c.i_area=d.i_area and c.d_dt=d.d_dt and a.i_nota=d.i_nota
                and a.f_ttb_tolak='f' and a.f_nota_cancel='f' and not a.i_nota isnull
                and a.i_area='$iarea' and
                a.d_nota >= '$dfrom'AND
                a.d_nota <= '$dto'
            ORDER BY 
                a.i_nota  
        ",false);
        
        $datatables->edit('v_nota_discounttotal', function($data){
            return number_format($data['v_nota_discounttotal']);
        });

        $datatables->edit('v_nota_netto', function($data){
            return number_format($data['v_nota_netto']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->edit('d_nota', function($data){
            return date("d-m-Y", strtotime($data['d_nota']));
        });

        $datatables->edit('e_customer_name', function($data){
            return ($data['i_customer']).'-'.($data['e_customer_name']);
        });
        

        $datatables->add('action', function ($data) {
            $inota      = $data['i_nota'];
            $folder     = $data['folder'];
            $iarea      = $data['i_area'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $title      = $data['title'];
            $data       = '';
            $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$inota\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_customer');
        $datatables->hide('f_nota_cancel');
        return $datatables->generate();
    }

    public function total($dfrom, $dto, $iarea){
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
            sum(jumnil) as nilai,
            sum(jumsis) as sisa
        FROM(
            SELECT	
                distinct a.i_nota, 
                sum(a.v_nota_netto) as jumnil,
                sum(a.v_sisa) as jumsis
            FROM 
                tm_nota a, 
                tr_customer b, 
                tm_dt c, 
                tm_dt_item d
            WHERE 
                a.i_customer=b.i_customer 
                and c.f_dt_cancel='f' 
                and c.i_dt=d.i_dt 
                and c.i_area=d.i_area 
                and c.d_dt=d.d_dt 
                and a.i_nota=d.i_nota
                and a.f_ttb_tolak='f' 
                and a.f_nota_cancel='f' 
                and not a.i_nota isnull
                and a.i_area='00' 
                and a.d_nota >= '$dfrom' 
                and a.d_nota <= '$dto'
            GROUP BY
                a.i_nota
        ) as b
        ", false);
    }

    function bacadetail($inota){
        $this->db->select("distinct(a.i_nota), a.d_nota, a.v_nota_netto, b.v_jumlah, b.v_sisa, c.i_dt, c.d_dt
                                from tm_nota a
                                left join tm_dt_item b on(b.i_nota=a.i_nota)
                                inner join tm_dt c on(b.i_dt=c.i_dt and c.f_dt_cancel='f' 
                                and b.d_dt=c.d_dt and b.i_area=c.i_area)
                                where a.i_nota='$inota' order by c.i_dt",false);

        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
