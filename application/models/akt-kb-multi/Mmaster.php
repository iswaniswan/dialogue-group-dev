<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('id_company',$idcompany);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iarea = $kuy->i_area; 
        }else{
            $iarea = '';
        }
        return $iarea;
    }

    public function cekearea($iarea){
        $this->db->select('e_area_name');
        $this->db->from('tr_area');
        $this->db->where('i_area',$iarea);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $earea = $kuy->e_area_name; 
        }else{
            $earea = '';
        }
        return $earea;
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

    public function getcoabank($ibank){
        $this->db->select('i_coa');
        $this->db->from('tr_bank');
        $this->db->where('i_bank', $ibank);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $icoabank = $kuy->i_coa; 
        }else{
            $icoabank = '';
        }
        return $icoabank;
    }

    public function bacaarea($iarea){
        if ($iarea=="00") {
            return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
        }else{
            $this->db->select('*');
            $this->db->from('tr_area');
            $this->db->where('i_area', $iarea);
            return $this->db->get()->result();
        }
    }

    public function bacacoa($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_coa,
                e_coa_name
            FROM
                tr_coa
            WHERE
                (UPPER(i_coa) LIKE '%$cari%'
                OR UPPER(e_coa_name) LIKE '%$cari%')
                AND (NOT i_coa LIKE '110-2%')
        ", FALSE);
    }

    public function getdetailcoa($icoa){
        return $this->db->query("
            SELECT
                e_coa_name
            FROM
                tr_coa
            WHERE
                i_coa = '$icoa'
                AND (NOT i_coa LIKE '110-2%')
        ", FALSE);
    }

	function bacakendaraan($cari,$area,$periode,$num,$offset){
        if($cari=='sikasep'){
	    	  $this->db->select(" * from tr_kendaraan a
	    						inner join tr_kendaraan_jenis b on (a.i_kendaraan_jenis=b.i_kendaraan_jenis)
	    						inner join tr_kendaraan_bbm c on(a.i_kendaraan_bbm=c.i_kendaraan_bbm)
	    						where a.i_area='$area' and a.i_periode='$periode'
	    						order by a.i_kendaraan",false)->limit($num,$offset);
        }else{
	    	  $this->db->select(" * from tr_kendaraan a
	    					    inner join tr_kendaraan_jenis b on (a.i_kendaraan_jenis=b.i_kendaraan_jenis)
	    					    inner join tr_kendaraan_bbm c on(a.i_kendaraan_bbm=c.i_kendaraan_bbm)
	    					    where a.i_area='$area' and a.i_periode='$periode'
                                and (upper(i_kendaraan) like '%$cari%' or upper(e_pengguna) like '%$cari%')
	    						order by a.i_kendaraan",false)->limit($num,$offset);
        }
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

	function carikendaraan($area,$periode,$cari,$num,$offset){
		$this->db->select(" * from tr_kendaraan a
					    inner join tr_kendaraan_jenis b on (a.i_kendaraan_jenis=b.i_kendaraan_jenis)
					    inner join tr_kendaraan_bbm c on(a.i_kendaraan_bbm=c.i_kendaraan_bbm)
					    where (upper(a.i_kendaraan) like '%$cari%' or upper(a.e_pengguna) like '%$cari%')
					    and a.i_area='$area' and a.i_periode='$periode'
					    order by a.i_kendaraan",false)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function runningnumberbankmasuk($th,$bl,$iarea,$icoabank){
		$this->db->select(" max(substr(i_kbank,9,5)) as max from tm_kbank 
		                    where substr(i_kbank,4,2)='$th' and substr(i_kbank,6,2)='$bl' and i_coa_bank='$icoabank'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$nogj  =$terakhir+1;
			settype($nogj,"string");
			$a=strlen($nogj);
			while($a<5){
			  $nogj="0".$nogj;
			  $a=strlen($nogj);
			}
			$nogj  ="BM-".$th.$bl."-".$nogj;
			return $nogj;
		}else{
			$nogj  ="00001";
			$nogj  ="BM-".$th.$bl."-".$nogj;
			return $nogj;
		}
    }

    function insert($iareax,$ikb,$iperiode,$icoa,$vkb,$dbukti,$dkb,$ecoaname,$edescription,$fdebet){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
	    if($icoa==HutangDagangSementara){
	    	  $this->db->set(
        		array(
	    			'i_area'	            => $iareax,
	    			'i_kb'	            	=> $ikb,				
	    			'i_periode'         	=> $iperiode,
	    			'i_coa'	            	=> $icoa,
	    			'd_bukti'                => $dbukti,
	    			'v_kb'		            => $vkb,
	    			'd_kb'		            => $dkb,
	    			'e_coa_name'	        => $ecoaname,
	    			'e_description'	      => $edescription,
	    			'd_entry'           	=> $dentry,
	    			'f_debet'	            => $fdebet,
	    			'v_sisa'	            => $vkb
        		)
        	);
        	$this->db->insert('tm_kb');
        }else{
	    	 	$this->db->set(
        		array(
	    			'i_area'	            => $iareax,
	    			'i_kb'	            	=> $ikb,				
	    			'i_periode'         	=> $iperiode,
	    			'i_coa'	            	=> $icoa,
	    			'd_bukti'                => $dbukti,
	    			'v_kb'		            => $vkb,
	    			'd_kb'		            => $dkb,
	    			'e_coa_name'	        => $ecoaname,
	    			'e_description'	      => $edescription,
	    			'd_entry'           	=> $dentry,
	    			'f_debet'	            => $fdebet,
                    'v_sisa'	            => $vkb
        		)
        	);
        	$this->db->insert('tm_kb');
        }   	
    }

    function insertx($iareax,$ikbank,$iperiode,$icoa,$vkb,$dkb,$ecoaname,$edescription,$fdebet,$icoabank){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	            => $iareax,
				'i_kbank'	            => $ikbank,				
				'i_periode'         	=> $iperiode,
				'i_coa'	            	=> $icoa,
				'v_bank'	            => $vkb,
				'v_sisa'	            => $vkb,
				'd_bank'	            => $dkb,
				'e_coa_name'	        => $ecoaname,
				'e_description'	      => $edescription,
				'd_entry'           	=> $dentry,
				'f_debet'	            => $fdebet,
				'i_coa_bank'          => $icoabank
    		)
    	);
    	$this->db->insert('tm_kbank');
    }

    function insertkk($iareax,$ikk,$iperiode,$icoa,$vkk,$dkk,$ecoaname,$edescription,$fdebet,$icoabank){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	            => $iareax,
				'i_kk'	            	=> $ikk,				
				'i_periode'         	=> $iperiode,
				'i_coa'	            	=> $icoa,
				'v_kk'		            => $vkk,
				'd_kk'		            => $dkk,
				'e_coa_name'	        => $ecoaname,
				'e_description'	        => $edescription,
				'd_entry'           	=> $dentry,
				'f_debet'	            => $fdebet,
    		)
    	);
    	$this->db->insert('tm_kk');
    }

    function update($iarea,$ikk,$iperiode,$icoa,$ikendaraan,$vkk,$dkk,$ecoaname,$edescription,$ejamin,$ejamout,$nkm,$etempat,$fdebet,$dbukti,$enamatoko,$epengguna,$ibukti){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dupdate= $row->c;
    	$this->db->set(
    		array(
				'i_kendaraan'	=> $ikendaraan,
				'i_coa'		=> $icoa,
				'v_kk'		=> $vkk,
				'i_bukti_pengeluaran'	=> $ibukti,
				'd_kk'		=> $dkk,
				'e_coa_name'	=> $ecoaname,
				'e_description'	=> $edescription,
				'e_jam_in'	=> $ejamin,
				'e_jam_out'	=> $ejamout,
				'n_km'		=> $nkm,
				'e_tempat'	=> $etempat,
				'd_update'	=> $dupdate,
				'd_bukti'	=> $dbukti,
				'f_debet'	=> $fdebet,
				'e_nama_toko'	=> $enamatoko,
				'e_pengguna'	=> $epengguna
    		)
    	);
		$this->db->where('i_area',$iarea);
		$this->db->where('i_kk',$ikk);
		$this->db->where('i_periode',$iperiode);
   	$this->db->update('tm_kk');
    }

    function insertpv($ipv,$iarea,$iperiode,$icoa,$dpv,$tot,$eremark,$ipvtype){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_pv'	            	=> $ipv,
				'i_area'	            => $iarea,
				'i_periode'         	=> $iperiode,
				'i_coa' 	            => $icoa,
				'd_pv'		            => $dpv,
				'v_pv'		            => $tot,
				'd_entry'           	=> $dentry,
				'i_pv_type'           => $ipvtype
    		)
    	);
    	$this->db->insert('tm_pv');
    }

    function insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vpv,$edescription,$ikb,$ipvtype,$iareax){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	            => $iarea,
				'i_pv'	            	=> $ipv,
				'i_coa'              	=> $icoa,
				'e_coa_name'	        => $ecoaname,
				'v_pv'		            => $vpv,
				'e_remark'    	      => $edescription,
				'i_kk'                => $ikb,
				'i_pv_type'           => $ipvtype,
				'i_area_kb'           => $iareax
    		)
    	);
    	$this->db->insert('tm_pv_item');
    }

	function runningnumberpv($th,$bl,$iarea,$ipvtype){
		$this->db->select(" max(substr(i_pv,11,6)) as max 
		                    from tm_pv 
		                    where substr(i_pv,4,2)='$th' and substr(i_pv,6,2)='$bl' and i_area='$iarea'
		                    and i_pv_type='$ipvtype'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$nopv  =$terakhir+1;
			settype($nopv,"string");
			$a=strlen($nopv);
			while($a<6){
			  $nopv="0".$nopv;
			  $a=strlen($nopv);
			}
			$nopv  ="PV-".$th.$bl."-".$iarea.$nopv;
			return $nopv;
		}else{
			$nopv  ="000001";
			$nopv  ="PV-".$th.$bl."-".$iarea.$nopv;
			return $nopv;
		}
    }

    function runningnumberrvb($th,$bl,$icoa,$iarea){
		$this->db->select(" max(substr(i_rvb,11,6)) as max 
		                    from tm_rvb 
		                    where substr(i_rvb,4,2)='$th' and substr(i_rvb,6,2)='$bl' and i_coa_bank='$icoa'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$norvb  =$terakhir+1;
			settype($norvb,"string");
			$a=strlen($norvb);
			while($a<6){
			  $norvb="0".$norvb;
			  $a=strlen($norvb);
			}
			$norvb  ="RV-".$th.$bl."-".$iarea.$norvb;
			return $norvb;
		}else{
			$norvb  ="000001";
			$norvb  ="RV-".$th.$bl."-".$iarea.$norvb;
			return $norvb;
		}
    }
  
  function runningnumberrv($th,$bl,$iarea,$irvtype){
		$this->db->select(" max(substr(i_rv,11,6)) as max 
		                    from tm_rv 
		                    where substr(i_rv,4,2)='$th' and substr(i_rv,6,2)='$bl' and i_area='$iarea'
		                    and i_rv_type='$irvtype'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$norv  =$terakhir+1;
			settype($norv,"string");
			$a=strlen($norv);
			while($a<6){
			  $norv="0".$norv;
			  $a=strlen($norv);
			}
			$norv  ="RV-".$th.$bl."-".$iarea.$norv;
			return $norv;
		}else{
			$norv  ="000001";
			$norv  ="RV-".$th.$bl."-".$iarea.$norv;
			return $norv;
		}
    }

    function insertrvb($irvb,$icoabank,$irv,$iarea,$irvtype){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_rvb'                 => $irvb,
				'i_coa_bank' 	        => $icoabank,
				'i_rv'	            	=> $irv,
				'i_area'	            => $iarea,
				'i_rv_type'             => $irvtype,
				'd_entry'           	=> $dentry,
    		)
    	);
    	$this->db->insert('tm_rvb');
    }

    function insertrv($irv,$iarea,$iperiode,$icoa,$drv,$tot,$eremark,$irvtype){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_rv'	            	=> $irv,
				'i_area'	            => $iarea,
				'i_periode'         	=> $iperiode,
				'i_coa' 	            => $icoa,
				'd_rv'		            => $drv,
				'v_rv'		            => $tot,
				'd_entry'           	=> $dentry,
				'i_rv_type'           => $irvtype
    		)
    	);
    	$this->db->insert('tm_rv');
    }

    function insertrvitem( $irv,$iarea,$icoabank,$ecoaname,$vrv,$ireff,$ikodebm,$irvtype,$iareax,$icoa){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	          => $iarea,
				'i_rv'	              => $irv,
				'i_coa'               => $icoabank,
				'e_coa_name'	      => $ecoaname,
				'v_rv'		          => $vrv,
				'e_remark'    	      => $ireff,
				'i_kk'                => $ikodebm,
				'i_rv_type'           => $irvtype,
				'i_area_kb'           => $iareax,
				'i_coa_bank'          => $icoa
    		)
    	);
    	$this->db->insert('tm_rv_item');
    }

    function runningnumberkk($th,$bl,$iarea,$icoabank)  {
		$this->db->select(" max(substr(i_kk,9,5)) as max from tm_kk where substr(i_kk,4,2)='$th' and substr(i_kk,6,2)='$bl' and i_area='$iarea'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$nogj  =$terakhir+1;
			settype($nogj,"string");
			$a=strlen($nogj);
			while($a<5){
			  $nogj="0".$nogj;
			  $a=strlen($nogj);
			}
			$nogj  ="KK-".$th.$bl."-".$nogj;
			return $nogj;
		}else{
			$nogj  ="00001";
			$nogj  ="KK-".$th.$bl."-".$nogj;
			return $nogj;
		}
    }

	function runningnumberkb($th,$bl,$iarea){
		$this->db->select(" max(substr(i_kb,9,5)) as max from tm_kb where substr(i_kb,4,2)='$th' and substr(i_kb,6,2)='$bl'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			  $terakhir=$row->max;
			}
			$nogj  =$terakhir+1;
			settype($nogj,"string");
			$a=strlen($nogj);
			while($a<5){
			  $nogj="0".$nogj;
			  $a=strlen($nogj);
			}
			$nogj  ="KB-".$th.$bl."-".$nogj;
			return $nogj;
		}else{
			$nogj  ="00001";
			$nogj  ="KB-".$th.$bl."-".$nogj;
			return $nogj;
		}
    }

	function bacasaldo($area,$tanggal,$icoa){	    
		$tmp = explode("-", $tanggal);
		$thn	= $tmp[0];
		$bln	= $tmp[1];
		$tgl 	= $tmp[2];
		$dsaldo	= $thn."/".$bln."/".$tgl;
		$dtos	= $this->mmaster->dateAdd("d",-1,$dsaldo);
		$tmp1 	= explode("-", $dtos,strlen($dtos));
		$th	= $tmp1[0];
		$bl	= $tmp1[1];
		$dt	= $tmp1[2];
		$dtos	= $th.$bl;
		$this->db->select(" v_saldo_awal from tm_coa_saldo where i_periode='$dtos' and i_coa='$icoa' ",false);
		$query = $this->db->get();
		$saldo=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$saldo=$row->v_saldo_awal;
			}
		}
		$this->db->select(" sum(x.v_kb) as v_kb from 
		                    (
		                    select sum(b.v_kb) as v_kb from tm_rv x, tm_rv_item z, tm_kb b
							where x.i_rv=z.i_rv and x.i_area=z.i_area and x.i_rv_type=z.i_rv_type and x.i_rv_type='01'
							and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='t' 
							and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
                            UNION ALL
		                    select sum(b.v_kb) as v_kb from tm_pv x, tm_pv_item z, tm_kb b
							where x.i_pv=z.i_pv and x.i_area=z.i_area and x.i_pv_type=z.i_pv_type and x.i_pv_type='01'
							and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='t' 
							and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
							) as x ",false);
		$query = $this->db->get();
		$kredit=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$kredit=$row->v_kb;
			}
		}
		if($kredit==null)$kredit=0;
		$this->db->select(" sum(x.v_kb) as v_kb from 
		                    (
		                    select sum(b.v_kb) as v_kb from tm_rv x, tm_rv_item z, tm_kb b
							where x.i_rv=z.i_rv and x.i_area=z.i_area and x.i_rv_type=z.i_rv_type and x.i_rv_type='01'
							and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='f' 
							and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
                            UNION ALL							
		                    select sum(b.v_kb) as v_kb from tm_pv x, tm_pv_item z, tm_kb b
							where x.i_pv=z.i_pv and x.i_area=z.i_area and x.i_pv_type=z.i_pv_type and x.i_pv_type='01'
							and b.i_periode='$dtos' and x.i_area='$area' and b.d_kb<='$tanggal' and b.f_debet='f' 
							and b.f_kb_cancel='f' and z.i_kk=b.i_kb and z.i_area_kb=b.i_area and x.i_periode=b.i_periode
							) as x ",false);
		$query = $this->db->get();
		$debet=0;
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$debet=$row->v_kb;
			}
		}
		$coaku=KasKecil.$area;
		  $kasbesar=KasBesar;
     	  $bank=Bank;
		  $this->db->select(" sum(v_bank) as v_bank from tm_kbank a 
		                      where a.i_periode='$dtos' and a.i_area='$area' and a.d_bank<'$tanggal' and a.f_debet='t' and 
		                      a.f_kbank_cancel='f' and a.i_coa='$coaku'
		                      and a.v_bank not in (select b.v_kk as v_bank from tm_kk b where b.d_kk < '$tanggal' and b.i_area='$area' 
		                      and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '$bank%' and b.i_periode='$dtos')
		                      ",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){$coaku=KasKecil;
    	  $bank=Bank;
			  foreach($query->result() as $row){
				  $debet=$debet+$row->v_bank;
			  }
		  }
		  $this->db->select(" sum(v_kk) as v_kk from tm_kk a where a.i_periode='$dtos' and a.i_area='$area' and a.d_kk<'$tanggal' 
		                      and a.f_debet='t' and a.f_kk_cancel='f' and a.i_coa='$coaku'
		                      and a.v_kk not in (select b.v_kb as v_kk from tm_kb b where b.d_kb < '$tanggal' and b.i_area='$area' 
		                      and b.f_kb_cancel='f' and b.f_debet='f' and b.i_coa like '$coaku' 
		                      and b.i_periode='$dtos')",false);							
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
			  foreach($query->result() as $row){
				  $debet=$debet+$row->v_kk;
			  }
		  }
		if($debet==null)$debet=0;
		$saldo=$saldo+$debet-$kredit;
		return $saldo;
    }

	function dateAdd($interval,$number,$dateTime) {
		$dateTime = (strtotime($dateTime) != -1) ? strtotime($dateTime) : $dateTime;
		$dateTimeArr=getdate($dateTime);
		$yr=$dateTimeArr['year'];
		$mon=$dateTimeArr['mon'];
		$day=$dateTimeArr['mday'];
		$hr=$dateTimeArr['hours'];
		$min=$dateTimeArr['minutes'];
		$sec=$dateTimeArr['seconds'];
		switch($interval) {
		    case "s"://seconds
		        $sec += $number;
		        break;
		    case "n"://minutes
		        $min += $number;
		        break;
		    case "h"://hours
		        $hr += $number;
		        break;
		    case "d"://days
		        $day += $number;
		        break;
		    case "ww"://Week
		        $day += ($number * 7);
		        break;
		    case "m": //similar result "m" dateDiff Microsoft
		        $mon += $number;
		        break;
		    case "yyyy": //similar result "yyyy" dateDiff Microsoft
		        $yr += $number;
		        break;
		    default:
		        $day += $number;
		}      
		$dateTime = mktime($hr,$min,$sec,$mon,$day,$yr);
		$dateTimeArr=getdate($dateTime);
		$nosecmin = 0;
		$min=$dateTimeArr['minutes'];
		$sec=$dateTimeArr['seconds'];
		if ($hr==0){$nosecmin += 1;}
		if ($min==0){$nosecmin += 1;}
		if ($sec==0){$nosecmin += 1;}
		if ($nosecmin>2){     
			return(date("Y-m-d",$dateTime));
		} else {     
			return(date("Y-m-d G:i:s",$dateTime));
		}
    }
    
	function area($iarea){
		$this->db->select(" e_area_name from tr_area where i_area='$iarea'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row)
			{
				$nama=$row->e_area_name;
				return $nama;
			}
		}
    }

	function bacakgroup($cari,$num,$offset){
		$this->db->select(" * from tr_kk_group where upper(i_kk_group) like '%$cari%' or upper(e_kk_groupname) like '%$cari%' order by i_kk_group",false)->limit($num,$offset);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

	function bacapv($ipv,$iarea){
		$this->db->select(" * from tm_pv a, tm_pv_item b
		                    where a.i_pv=b.i_pv and a.i_area=b.i_area and a.i_area='$iarea' and a.i_pv='$ipv' 
		                    and a.i_pv_type= b.i_pv_type and a.i_pv_type='01'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

	function bacapvprint($cari,$area,$periode,$num,$offset){
        if($cari=='sikasep'){
	      	$this->db->select(" distinct on (a.i_pv) *, a.v_pv as v_pv from tm_pv a, tm_pv_item b 
	      	                    where a.i_pv=b.i_pv and a.i_area=b.i_area 
	      	                    and a.i_area='$area' and a.i_periode='$periode' and a.i_pv_type='01'",false)->limit($num,$offset);
        }else{
	      	$this->db->select(" distinct on (a.i_pv) *, a.v_pv as v_pv from tm_pv a, tm_pv_item b 
	      	                    where a.i_pv=b.i_pv and a.i_area=b.i_area 
	      	                    and a.i_area='$area' and a.i_periode='$periode' and a.i_pv_type='01'
	      	                    and upper(a.i_pv) like '%$cari%'",false)->limit($num,$offset);
        }
  	    $query = $this->db->get();
  	    if ($query->num_rows() > 0){
  	    	return $query->result();
  	    }
    }

	function inserttransheader(	$inota,$iarea,$eremark,$fclose,$dkn ){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$eremark=str_replace("'","''",$eremark);
		$this->db->query("insert into tm_jurnal_transharian 
						 (i_refference, i_area, d_entry, e_description, f_close,d_refference,d_mutasi)
						  	  values
					  	 ('$inota','$iarea','$dentry','$eremark','$fclose','$dkn','$dkn')");
    }
    
	function inserttransitemdebet($accdebet,$ikn,$namadebet,$fdebet,$fposting,$iarea,$eremark,$vjumlah,$dkn){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namadebet=str_replace("'","''",$namadebet);
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_debet, d_refference, d_mutasi, d_entry, i_area)
						  	  values
					  	 ('$accdebet','$ikn','$namadebet','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry','$iarea')");
    }
    
	function inserttransitemkredit($acckredit,$ikn,$namakredit,$fdebet,$fposting,$iarea,$egirodescription,$vjumlah,$dkn){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$namakredit=str_replace("'","''",$namakredit);
		$this->db->query("insert into tm_jurnal_transharianitem
						 (i_coa, i_refference, e_coa_description, f_debet, f_posting, v_mutasi_kredit, d_refference, d_mutasi, d_entry, i_area)
						  	  values
					  	 ('$acckredit','$ikn','$namakredit','$fdebet','$fposting','$vjumlah','$dkn','$dkn','$dentry','$iarea')");
    }
    
	function insertgldebet($accdebet,$ikn,$namadebet,$fdebet,$iarea,$vjumlah,$dkn,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$eremark=str_replace("'","''",$eremark);
		$namadebet=str_replace("'","''",$namadebet);
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_debet,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ikn','$accdebet','$dkn','$namadebet','$fdebet',$vjumlah,'$iarea','$dkn','$eremark','$dentry')");
    }
    
	function insertglkredit($acckredit,$ikn,$namakredit,$fdebet,$iarea,$vjumlah,$dkn,$eremark){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
		$eremark=str_replace("'","''",$eremark);
		$namakredit=str_replace("'","''",$namakredit);
		$this->db->query("insert into tm_general_ledger
						 (i_refference,i_coa,d_mutasi,e_coa_name,f_debet,v_mutasi_kredit,i_area,d_refference,e_description,d_entry)
						  	  values
					  	 ('$ikn','$acckredit','$dkn','$namakredit','$fdebet','$vjumlah','$iarea','$dkn','$eremark','$dentry')");
    }
    
	function updatekk($ikn,$iarea,$iperiode){
		$this->db->query("update tm_kk set f_posting='t' where i_kk='$ikn' and i_area='$iarea' and i_periode='$iperiode'");
    }
    
	function updatesaldodebet($accdebet,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_debet=v_mutasi_debet+$vjumlah, v_saldo_akhir=v_saldo_akhir+$vjumlah
						  where i_coa='$accdebet' and i_periode='$iperiode'");
    }
    
	function updatesaldokredit($acckredit,$iperiode,$vjumlah){
		$this->db->query("update tm_coa_saldo set v_mutasi_kredit=v_mutasi_kredit+$vjumlah, v_saldo_akhir=v_saldo_akhir-$vjumlah
						  where i_coa='$acckredit' and i_periode='$iperiode'");
    }
    
	function namaacc($icoa){
		$this->db->select(" e_coa_name from tr_coa where i_coa='$icoa' ",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $tmp)			
			{
				$xxx=$tmp->e_coa_name;
			}
			return $xxx;
		}
    }
}

/* End of file Mmaster.php */