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
                            a.i_ikhp, 
                            a.i_area, 
                            a.i_bukti,
                            a.d_bukti, 
                            b.e_area_name,
                            c.e_ikhp_typename,
                            a.v_terima_tunai, 
                            a.v_terima_giro, 
                            a.v_keluar_tunai, 
                            a.v_keluar_giro, 
                            a.i_ikhp_type, 
                            a.i_coa, 
                            a.i_cek,
                            '$dfrom' as dfrom,
                            '$dto' as dto,
                            '$folder' as folder
                        FROM 
                            tm_ikhp a, 
                            tr_area b, 
                            tr_ikhp_type c
                        WHERE 
                            a.i_area=b.i_area and 
                            a.i_ikhp_type=c.i_ikhp_type and 
                            a.i_area='$iarea' and 
                            not (a.v_terima_giro=0 and 
                            a.v_keluar_giro=0 and 
                            a.v_keluar_tunai=0 and 
                            a.v_terima_tunai=0) and
                            a.d_bukti >= '$dfrom' and
                            a.d_bukti <= '$dto'
                      ORDER BY 
                        a.d_bukti desc, 
                        a.i_ikhp desc"
                    ,false);

        $datatables->edit('v_terima_tunai', function($data){
            return number_format($data['v_terima_tunai']);
        });

        $datatables->edit('v_terima_giro', function($data){
            return number_format($data['v_terima_giro']);
        });

        $datatables->edit('v_keluar_tunai', function($data){
            return number_format($data['v_keluar_tunai']);
        });

        $datatables->edit('v_keluar_giro', function($data){
            return number_format($data['v_keluar_giro']);
        });

        $datatables->edit('d_bukti', function($data){
            return date("d-m-Y", strtotime($data['d_bukti']));
        });

        $datatables->add('action', function ($data) {
            $ikhp           = $data['i_ikhp'];
            $folder         = $data['folder'];
            $iarea          = $data['i_area'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ikhp/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_ikhp');
        $datatables->hide('i_area');
        $datatables->hide('i_ikhp_type');
        $datatables->hide('i_coa');
        $datatables->hide('i_cek');

        return $datatables->generate();
    }

    function update($iikhp,$iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro){
    	$this->db->set(
    		array(
				'i_area'			=> $iarea,
				'd_bukti'			=> $dbukti,
				'i_bukti'			=> $ibukti,
				'i_coa'				=> $icoa,
				'i_ikhp_type'		=> $iikhptype,
				'v_terima_tunai'	=> $vterimatunai,
				'v_terima_giro'		=> $vterimagiro,
				'v_keluar_tunai'	=> $vkeluartunai,
				'v_keluar_giro'		=> $vkeluargiro
    		)
    	);
    	$this->db->where('i_ikhp',$iikhp);
    	$this->db->update('tm_ikhp');
    }

    function baca($iikhpkeluar){
       $query = $this->db->query(" 
                                SELECT 
                                    * 
                                FROM 
                                    tm_ikhp a, 
                                    tr_area b, 
                                    tr_ikhp_type c 
                                WHERE 
                                    a.i_ikhp=$iikhpkeluar and 
                                    a.i_area=b.i_area and 
                                    a.i_ikhp_type=c.i_ikhp_type"
                                ,false);
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacaikhptype(){
		$query = $this->db->query(" select * from tr_ikhp_type order by i_ikhp_type", false);
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }
}

/* End of file Mmaster.php */
