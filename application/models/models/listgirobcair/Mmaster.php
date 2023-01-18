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
                SELECT	distinct on (a.d_giro, a.i_giro) a.i_giro, 
                    tr_area.e_area_name, 
                    a.d_giro, 
                    a.i_rv, 
                    a.d_rv, 
                    tm_dt.i_dt, 
                    tm_dt.d_dt, 
                    tr_customer.i_customer, 
                    tr_customer.e_customer_name, 
                    a.e_giro_bank, 
                    a.v_jumlah, 
                    a.v_sisa, 
                    a.f_posting, 
                    a.f_giro_batal, 
                    a.i_area, 
                    tm_pelunasan.i_pelunasan,
                    '$dfrom' as dfrom,
                    '$dto' as dto,
                    '$folder' as folder,
                    '$title' as title

                FROM 
                    tm_giro a 
                    inner join tr_area on(a.i_area=tr_area.i_area)
                    inner join tr_customer on(a.i_customer=tr_customer.i_customer)
                    left join tm_pelunasan on(a.i_giro=tm_pelunasan.i_giro)
                    left join tm_dt on(tm_pelunasan.i_dt=tm_dt.i_dt)
                WHERE 
                    ((tm_pelunasan.i_jenis_bayar!='02' and 
                    tm_pelunasan.i_jenis_bayar!='03' and 
                    tm_pelunasan.i_jenis_bayar!='04' and 
                    tm_pelunasan.i_jenis_bayar='01') or 
                    ((tm_pelunasan.i_jenis_bayar='01') is null)) and							
                    a.i_area='$iarea' and
                    (a.d_giro >= '$dfrom' and
                    a.i_giro = tm_pelunasan.i_giro and 
                    a.i_area = tm_pelunasan.i_area and
                    a.d_giro <= '$dto') and 
                    a.f_giro_cair='f' and a.f_giro_batal='f' and 
                    a.f_giro_batal_input='f' and 
                    a.f_giro_tolak='f' 
                ORDER BY 
                    a.d_giro desc, 
                    a.i_giro desc"
                ,false);
        
        $datatables->edit('v_jumlah', function($data){
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->edit('d_giro', function($data){
            return date("d-m-Y", strtotime($data['d_giro']));
        });

        $datatables->edit('d_dt', function($data){
            return date("d-m-Y", strtotime($data['d_dt']));
        });

        $datatables->edit('e_customer_name', function($data){
            return ($data['i_customer']).'-'.($data['e_customer_name']);
        });

        $datatables->add('action', function ($data) {
            $igiro          = $data['i_giro'];
            $folder         = $data['folder'];
            $title          = $data['title'];
            $iarea          = $data['i_area'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $ipelunasan     = $data['i_pelunasan'];
            $idt            = $data['i_dt'];
            $fposting       = $data['f_posting'];
            $data       = '';

            if($fposting == 'f'){
                $ipelunasan = ($ipelunasan == '' || empty($ipelunasan))?0:$ipelunasan;
                $idt = ($idt == '' || empty($idt))?0:$idt;
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/detail/$igiro/$iarea/$dfrom/$dto/$ipelunasan/$idt\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_posting');
        $datatables->hide('i_area');
        $datatables->hide('f_giro_batal');
        $datatables->hide('i_pelunasan');
        $datatables->hide('i_customer');
        $datatables->hide('title');
        return $datatables->generate();
    }

    function bacadetailpl($iarea,$ipl,$idt){
		$this->db->select(" a.*, b.v_nota_netto as v_nota from tm_pelunasan_item a
				            inner join tm_nota b on (a.i_nota=b.i_nota and a.i_area=b.i_area)
					        where a.i_pelunasan = '$ipl' 
					        and a.i_area='$iarea'
					        and a.i_dt='$idt'
					        order by a.i_pelunasan,a.i_area ",FALSE);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}	
    }
}

/* End of file Mmaster.php */
