<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $area){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_area, a.i_kk, a.d_kk, a.i_coa, a.e_description, a.v_kk, a.i_cek, a.d_cek, '$dfrom' as dfrom, '$dto' as dto, b.e_area_name
                            from tm_kk a, tr_area b
                            where a.i_area=b.i_area and a.f_kk_cancel='f'
                            and a.i_area='$area' and
                            a.d_kk >= to_date('$dfrom','yyyy-mm-dd') AND
                            a.d_kk <= to_date('$dto','yyyy-mm-dd')
                            ORDER BY a.i_kk",false);
		$datatables->add('action', function ($data) {
            $i_sj    = trim($data['i_kk']);
            $i_area    = trim($data['i_area']);
            $dfrom    = trim($data['dfrom']);
            $dto    = trim($data['dto']);
            $i_cek    = trim($data['i_cek']);
            $d_cek    = trim($data['d_cek']);
            $data       = '';
			return $data;
        });

        $datatables->edit('d_kk', function ($data) {
        $d_kk = $data['d_kk'];
            if($d_kk == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_kk) );
            }
        });

        $datatables->edit('i_cek', function ($data) {
            $i_cek = $data['i_cek'];
            $d_cek = $data['d_cek'];
            if($i_cek != '') {
                if($d_cek != '') {
                    $tmpck=explode('-',$d_cek);	
                    $tglck=$tmpck[2];
                    $blnck=$tmpck[1];
                    $thnck=$tmpck[0];
                    return (@$tmpck[2]!='' && $d_cek!='' )?($tglck.'-'.$blnck.'-'.$thnck):('System');
                } else {
                    return 'System';
                }
            } else {
                return 'Belum';
            }
        });

        $datatables->edit('v_kk', function ($data) {
            $v_kk = $data['v_kk'];
            if($v_kk == ''){
                return '';
            }else{
                return number_format($v_kk);
            }
        });

        $datatables->edit('i_area', function ($data) {
            $i_area = $data['i_area'];
            $areaname = $data['e_area_name'];
            if($i_area == ''){
                return '';
            }else{
                return $i_area."-".$areaname;
            }
        });
        $datatables->hide('e_area_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('d_cek');

        return $datatables->generate();
	}

	function bacaperiode($iarea,$dfrom,$dto){
		return $this->db->query("select a.*, b.e_area_name from tm_kk a, tr_area b
							    where a.i_area=b.i_area and a.f_kk_cancel='f'
							    and a.i_area='$iarea' and
							    a.d_kk >= to_date('$dfrom','dd-mm-yyyy') AND
							    a.d_kk <= to_date('$dto','dd-mm-yyyy')
							    ORDER BY a.i_kk ",false);
    }

    function update($iarea,$dfrom,$dto,$user){
	    
	    $dupdate= date("Y-m-d");
    	/*$this->db->set(array(
		  'd_cek'	=> $dupdate,
		  'i_cek'	=> $user
      	 ));*/
	    $query 	= $this->db->query("update tm_kk set d_cek='$dupdate', i_cek='$user' where f_close='f' and i_area='$iarea' and f_kk_cancel='f'
                                    and d_kk >= to_date('$dfrom','dd-mm-yyyy') AND d_kk <= to_date('$dto','dd-mm-yyyy')");
    }
}

/* End of file Mmaster.php */
