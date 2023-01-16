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

        public function bacaareauser($idcompany,$username){
            return $this->db->query("
                SELECT
                    *
                FROM
                    public.tm_user_area where id_company='$idcompany' and username='$username' order by i_area
            ", FALSE)->result();
    }

   /* public function cekstatus($idcompany,$username){
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
    }*/

    /*public function cekperiode(){
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
    } */

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($dfrom, $dto, $iarea, $folder){
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
                            distinct a.i_kum,
                            g.i_dt, 
                            g.d_dt,
                            a.d_kum, 
                            a.e_bank_name,
                            c.e_customer_name,
                            e.e_customer_setor,
                            a.v_jumlah,
                            a.f_kum_cancel,
                            a.i_customer,			
                            a.e_remark,
                            a.v_sisa,
                            a.f_close,
                            a.n_kum_year,
                            d.i_area,
                            f.i_giro,
                            '$dfrom' as dfrom,
                            '$dto' as dto,
                            '$folder' as folder
                        FROM
                            tm_kum a
                            left join tr_customer c on (a.i_customer=c.i_customer)
                            left join tr_area d on (a.i_area=d.i_area)
                            left join tr_customer_owner e on(a.i_customer=e.i_customer)
                            left join tm_pelunasan f on (a.i_kum=f.i_giro and a.d_kum=f.d_giro and f.f_pelunasan_cancel='f' 
                            and f.f_giro_tolak='f' and f.f_giro_batal='f' and a.i_area=f.i_area)
                            left join tm_dt g on(f.i_dt=g.i_dt and f.d_dt=g.d_dt and f.i_area=g.i_area 
                            and f.f_pelunasan_cancel='f' and f.f_giro_tolak='f' and f.f_giro_batal='f' and g.i_area=a.i_area)
                        WHERE 
                            ((f.i_jenis_bayar!='02' and 
                            f.i_jenis_bayar!='01' and 
                            f.i_jenis_bayar!='04' and 
                            f.i_jenis_bayar='03') or ((f.i_jenis_bayar='03') is null)) and 
                            a.i_area='$iarea' and
                            (a.d_kum >= '$dfrom' and
                            a.d_kum <= '$dto') and 
                            a.f_kum_cancel='f'
                        ORDER BY 
                            a.d_kum desc"
                    ,false);
    
        $datatables->edit('i_kum', function($data){
            if($data['f_kum_cancel']=='t'){
                return '<h1>'.($data['i_kum']).'</h1>';
            }else{
                return ($data['i_kum']);
            }
        });

        $datatables->edit('v_jumlah', function($data){
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('d_dt', function($data){
            return date("d-m-Y", strtotime($data['d_dt']));
        });

        $datatables->edit('d_kum', function($data){
            return date("d-m-Y", strtotime($data['d_kum']));
        });

        $datatables->edit('i_customer', function($data){
            if($data['i_customer']==''){
                return 'Belum Ada';
            }
                return ($data['i_customer']);
        });

        $datatables->edit('e_customer_name', function($data){
            return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->edit('e_customer_setor', function($data){
            return trim(($data['e_customer_setor']).'/'.trim(($data['e_remark'])));
        });

        $datatables->add('action', function ($data) {
            $ikum           = $data['i_kum'];
            $folder         = $data['folder'];
            $iarea          = $data['i_area'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $nkumyear       = $data['n_kum_year'];
            $fclose         = $data['f_close'];
            $vsisa          = $data['v_sisa'];
            $data           = '';
            $kum            = str_replace('/','|',$ikum);
            if(($fclose == 'f') && ($vsisa>0)){
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$kum/$nkumyear/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_kum_cancel');
        $datatables->hide('i_customer');
        $datatables->hide('e_remark');
        $datatables->hide('v_sisa');
        $datatables->hide('f_close');
        $datatables->hide('n_kum_year');
        $datatables->hide('i_area');
        $datatables->hide('i_giro');

        return $datatables->generate();
    }

    public function baca($iarea, $ikum, $tahun){
		$query = $this->db->query("	select * from tm_kum a
					                left join tr_customer_salesman e on(a.i_customer=e.i_customer and a.i_area=e.i_area and a.i_salesman=e.i_salesman)
					                left join tr_customer c on(a.i_customer=c.i_customer)
					                left join tr_area d on(a.i_area=d.i_area)
					                where a.i_kum='$ikum' and a.i_area='$iarea' and a.n_kum_year='$tahun'",false);
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function update($ikum,$tahun,$icustomer,$icustomergroupar,$iareaasal){
    	$this->db->set(
    		array(
				'i_customer'		    => $icustomer,
				'i_customer_groupar'    => $icustomergroupar
    		)
    	);
    	$this->db->where('i_kum',$ikum);
    	$this->db->where('i_area',$iareaasal);
    	$this->db->where('n_kum_year',$tahun);
    	$this->db->update('tm_kum');
    }

    /*function bacacustomer($iarea){
        $this->db->select("               
                            a.i_customer,
                            a.e_customer_name, 
					        b.i_customer_groupar, 
					        c.e_salesman_name, 
					        c.i_salesman,
					        d.e_customer_setor 
                        FROM 
                            tr_customer a 
				            left join tr_customer_groupar b on(a.i_customer=b.i_customer) 
				            left join tr_customer_salesman c on(a.i_customer=c.i_customer and a.i_area=c.i_area) 
				            left join tr_customer_owner d on(a.i_customer=d.i_customer)
                        WHERE
                            a.i_area='$iarea'
                        ORDER BY 
                            a.i_customer "
                        ,FALSE);
        $query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}	
	}*/
}

/* End of file Mmaster.php */
