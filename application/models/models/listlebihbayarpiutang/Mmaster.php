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
    public function data($iarea, $folder){
        $this->load->library('fungsi');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        SELECT 
                            b.e_customer_name, 
                            a.i_customer, 
                            a.i_pelunasan,
                            a.d_bukti,
                            a.v_jumlah,
                            a.v_lebih,
                            a.f_giro_tolak,
                            c.e_jenis_bayarname,
                            '$iarea' as iarea,
                            '$folder' as folder
                        FROM 
                            tr_customer b, 
                            tm_pelunasan_lebih a left join tr_jenis_bayar c on(a.i_jenis_bayar=c.i_jenis_bayar)
                        WHERE 
                            a.i_customer=b.i_customer and 
                            a.f_pelunasan_cancel='f' and 
                            a.v_lebih>0 and 
                            a.i_jenis_bayar<>'04' and 
                            a.f_giro_tolak='f' and 
                            a.f_giro_batal='f' and 
                            a.i_area='$iarea' 
                        ORDER BY 
                        a.i_pelunasan"
                    ,false);

        $datatables->edit('v_jumlah', function($data){
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('v_lebih', function($data){
            return number_format($data['v_lebih']);
        });

        $datatables->edit('e_jenis_bayarname', function($data){
            if($data['f_giro_tolak'] == 't'){
                return 'Giro Tolak';
            }else{
                return $data['e_jenis_bayarname'];
            }
        });

        $datatables->edit('d_bukti', function($data){
            return date("d-m-Y", strtotime($data['d_bukti']));
        });

        $datatables->edit('e_customer_name', function($data){
            return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->add('action', function ($data) {
            $ipelunasan     = $data['i_pelunasan'];
            $folder         = $data['folder'];
            $iarea          = $data['iarea'];
            $data           = '';
            //$data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ikhp/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('iarea');
        $datatables->hide('f_giro_tolak');
        $datatables->hide('i_customer');

        return $datatables->generate();
    }

    /*function update($iikhp,$iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro){
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
    }*/
}

/* End of file Mmaster.php */
