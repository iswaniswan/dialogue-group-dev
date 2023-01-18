<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekstatus($idcompany,$username){
        $query = $this->db->select("i_status from public.tm_user where id_company='$idcompany' 
                                     and username='$username'",FALSE);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $ar =  $query->row();
            $status = $ar->i_status;
        }else{
            $status='';
        }
        return $status;
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

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($dfrom, $dto, $status, $folder){
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
                        select a.i_area, a.e_area_shortname, a.i_customer, 
                        a.no||'/'||a.kode||'/'||a.e_area_shortname||'/'||a.romawi||'/'||substring(a.d_kn,7,4) as komplit,
                        a.f_kn_cancel, a.e_customer_name, a.i_salesman, a.e_customer_pkpnpwp, 
                        a.d_kn, a.v_gross, a.v_discount, a.v_netto, a.dpp, a.ppn, a.kode, a.sipkp, a.i_kn, 
                        a.dfrom, a.dto, a.folder, a.status from (
                        SELECT 
                            case
	                            when (d.e_customer_pkpnpwp isnull or trim(d.e_customer_pkpnpwp)='') then 'NonPKP'
	                            else 'PKP'
                            end as sipkp,
                            case
	                            when substring(b.i_kn,1,2)='KP' then substring(b.i_kn,3,4)
	                            else substring(b.i_kn,2,5)
                            end as no,
                            case
	                            when substring(b.i_kn,1,1)='K' then 'KN'
	                            when substring(b.i_kn,1,1)='D' then 'DN'
                            end as kode,
                            case
	                            when to_char(b.d_kn,'mm')='01' then 'I'
	                            when to_char(b.d_kn,'mm')='02' then 'II'
	                            when to_char(b.d_kn,'mm')='03' then 'III'
	                            when to_char(b.d_kn,'mm')='04' then 'IV'
	                            when to_char(b.d_kn,'mm')='05' then 'V'
	                            when to_char(b.d_kn,'mm')='06' then 'VI'
	                            when to_char(b.d_kn,'mm')='07' then 'VII'
	                            when to_char(b.d_kn,'mm')='08' then 'VIII'
	                            when to_char(b.d_kn,'mm')='09' then 'IX'
	                            when to_char(b.d_kn,'mm')='10' then 'X'
	                            when to_char(b.d_kn,'mm')='11' then 'XI'
	                            when to_char(b.d_kn,'mm')='12' then 'XII'
                            end as romawi,
                            b.i_area, a.e_area_shortname, b.i_customer, b.i_kn, b.f_kn_cancel, c.e_customer_name, b.i_salesman, d.e_customer_pkpnpwp, 
                            to_char(b.d_kn,'dd-mm-yyyy') as d_kn, b.v_gross, b.v_discount, b.v_netto, to_char(b.v_netto/1.1,'999,999,999') as dpp, to_char((b.v_netto/1.1) *0.1,'999,999,999') as ppn,
                            
                            '$dfrom' as dfrom,
                            '$dto' as dto,
                            '$folder' as folder,
                            '$status' as status
                        FROM 
                            tm_kn b, tr_area a, tr_customer c, tr_customer_pkp d
                        WHERE 
                            a.i_area=b.i_area and b.i_customer=c.i_customer and b.i_customer=d.i_customer
                            and b.d_kn>='$dfrom' and b.d_kn<='$dto' 
                        ) as a
                        ORDER BY 
                            a.i_area,  a.i_kn"
                    ,false);
        
        $datatables->edit('v_netto', function($data){
            return number_format($data['v_netto']);
        });

        $datatables->edit('v_gross', function($data){
            return number_format($data['v_gross']);
        });

        $datatables->edit('v_discount', function($data){
            return number_format($data['v_discount']);
        });

        $datatables->edit('d_kn', function($data){
            return date("d-m-Y", strtotime($data['d_kn']));
        });

        $datatables->edit('e_customer_name', function($data){
            return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->add('action', function ($data) {
            $ikn            = $data['i_kn'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $vgross         = $data['v_gross'];
            $vnetto         = $data['v_netto'];
            $vdiscount      = $data['v_discount'];
            $fkncancel      = $data['f_kn_cancel'];
            $status         = $data['status'];
            $icustomer      = $data['i_customer'];
            $data           = '';
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('status');
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */
