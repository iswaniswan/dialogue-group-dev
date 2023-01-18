<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    function pindah($iperiode,$iarea){
      $this->db->select(" i_area from tr_area_mapping where i_area_mapping='$iarea'",false);
		  $query = $this->db->get();
		  if ($query->num_rows() > 0){
		  	foreach($query->result() as $row){
	        $iarea=$row->i_area;	
        }
	    }

	    $coa_sekarang = $this->db->query("select i_coa from tr_coa where i_area = '$iarea' and e_coa_name like '%Kas Kecil%'")->row()->i_coa;

#	  $this->db->select(" v_saldo_awal from tm_coa_saldo where i_periode='$iperiode' and substr(i_coa,6,2)='$iarea' 
#                        and substr(i_coa,1,5)='111.2' ",false);
      $this->db->select(" v_saldo_awal from tm_coa_saldo where i_periode='$iperiode' and i_coa = '$coa_sekarang' ",false);
		  $query = $this->db->get();
		  $saldo=0;
		  if ($query->num_rows() > 0){
		  	foreach($query->result() as $row){
		  		$saldo=$row->v_saldo_awal;
		  	}
		  }

		  $kasbesar=KasBesar;
		  $bank=Bank;

		  $data_debet_kredit = $this->db->query("select x.i_coa, z.i_area, sum(z.debet) as debet, sum(z.kredit) as kredit from(
			select x.i_area, sum(x.v_kk) as debet, 0 as kredit from(
			select a.* from(
			select i_kb as i_kk, d_kb as d_kk, f_debet, e_description, '' as i_kendaraan, 0 as n_km, v_kb as v_kk, a.i_area, d_bukti, i_coa
			from tm_kb a
			inner join tr_area on (a.i_area=tr_area.i_area)
			where a.i_periode='$iperiode' and to_char(a.d_kb,'yyyymm')='$iperiode'
			and a.f_debet='t' and a.f_kb_cancel='f' and a.i_coa in(select i_coa from tr_coa where i_area <> '' and e_coa_name like '%Kas Kecil%')
			and a.v_kb not in (select b.v_kk as v_kb from tm_kk b where 
			b.d_kk = a.d_kb and b.i_area=a.i_area and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '900-000%' 
			and b.i_periode='$iperiode')
			union all
			select i_kbank as i_kk, d_bank as d_kk, f_debet, e_description, '' as i_kendaraan, 0 as n_km, v_bank as v_kk, a.i_area, d_bank as d_bukti, i_coa
			from tm_kbank a
			inner join tr_area on (a.i_area=tr_area.i_area)
			where a.i_periode='$iperiode'
			and to_char(a.d_bank,'yyyymm')='$iperiode'
			and a.f_debet='t' and a.f_kbank_cancel='f' and a.i_coa in(select i_coa from tr_coa where i_area <> '' and e_coa_name like '%Kas Kecil%')
			and a.v_bank not in (select b.v_kk as v_kb from tm_kk b where 
			b.d_kk = a.d_bank and b.i_area=a.i_area and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '$bank%' 
			and b.i_periode='$iperiode')
			union all
			select a.i_kk as i_kk, a.d_kk, a.f_debet, a.e_description, a.i_kendaraan, a.n_km, a.v_kk, a.i_area, d_bukti, i_coa
			from tm_kk a, tr_area b
			where to_char(a.d_kk,'yyyymm')='$iperiode'
			and a.i_area=b.i_area and a.f_kk_cancel='f'
			) as a
			order by a.i_area, a.d_kk, a.i_kk
			
			) as x
			where x.f_debet = 'f' or (substr(x.i_kk,1,2) = 'KB' or substr(x.i_kk,1,2) = 'BK')
			group by x.i_area
			union all
			/* Pemisah */
			select x.i_area, 0 as debet, sum(x.v_kk) as kredit from(
			
			select a.* from(
			select i_kb as i_kk, d_kb as d_kk, f_debet, e_description, '' as i_kendaraan, 0 as n_km, v_kb as v_kk, a.i_area, d_bukti, i_coa
			from tm_kb a
			inner join tr_area on (a.i_area=tr_area.i_area)
			where a.i_periode='$iperiode' and to_char(a.d_kb,'yyyymm')='$iperiode'
			and a.f_debet='t' and a.f_kb_cancel='f' and a.i_coa in(select i_coa from tr_coa where i_area <> '' and e_coa_name like '%Kas Kecil%')
			and a.v_kb not in (select b.v_kk as v_kb from tm_kk b where 
			b.d_kk = a.d_kb and b.i_area=a.i_area and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '900-000%' 
			and b.i_periode='$iperiode')
			union all
			select i_kbank as i_kk, d_bank as d_kk, f_debet, e_description, '' as i_kendaraan, 0 as n_km, v_bank as v_kk, a.i_area, d_bank as d_bukti, i_coa
			from tm_kbank a
			inner join tr_area on (a.i_area=tr_area.i_area)
			where a.i_periode='$iperiode'
			and to_char(a.d_bank,'yyyymm')='$iperiode'
			and a.f_debet='t' and a.f_kbank_cancel='f' and a.i_coa in(select i_coa from tr_coa where i_area <> '' and e_coa_name like '%Kas Kecil%')
			and a.v_bank not in (select b.v_kk as v_kb from tm_kk b where 
			b.d_kk = a.d_bank and b.i_area=a.i_area and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '$bank%' 
			and b.i_periode='$iperiode')
			union all
			select a.i_kk as i_kk, a.d_kk, a.f_debet, a.e_description, a.i_kendaraan, a.n_km, a.v_kk, a.i_area, d_bukti, i_coa
			from tm_kk a, tr_area b
			where to_char(a.d_kk,'yyyymm')='$iperiode'
			and a.i_area=b.i_area and a.f_kk_cancel='f'
			) as a
			order by a.i_area, a.d_kk, a.i_kk
			
			) as x
			where x.f_debet = 't'
			group by x.i_area
			) as z 
			inner join tr_coa x on(z.i_area = x.i_area and x.e_coa_name like '%Kas Keci%')
			where z.i_area = '$iarea'
			group by z.i_area, x.i_coa")->row();

      //$debet = $data_debet_kredit->debet;
      $debet = (isset($data_debet_kredit->debet)) ? $data_debet_kredit->debet : 0 ;
      //$kredit = $data_debet_kredit->kredit;
      $kredit = (isset($data_debet_kredit->kredit)) ? $data_debet_kredit->kredit : 0 ;
			$saldo=$saldo+$debet-$kredit;

			$this->db->query("update tm_coa_saldo set v_mutasi_debet = '$debet', v_mutasi_kredit = '$kredit', v_saldo_akhir = '$saldo' 
			where i_coa = '$coa_sekarang' 
			and i_periode = '$iperiode'");

// 		$this->db->select(" sum(v_kk) as v_kk from tm_kk
// 							where i_periode='$iperiode' and i_area='$iarea' and f_debet='t' and f_kk_cancel='f'",false);						 
// 		$query = $this->db->get();
// 		$kredit=0;
// 		if ($query->num_rows() > 0){
// 			foreach($query->result() as $row){
// 				$kredit=$row->v_kk;
// 			}
// 		}
// 		$this->db->select(" sum(v_kk) as v_kk from tm_kk
// 							          where i_periode='$iperiode' and i_area='$iarea' and f_debet='f' and f_kk_cancel='f'",false);							
// 		$query = $this->db->get();
// 		$debet=0;
// 		if ($query->num_rows() > 0){
// 			foreach($query->result() as $row){
// 				$debet=$row->v_kk;
// 			}
// 		}
// 		$coaku=$coa_sekarang;
// 		$kasbesar=KasBesar;
//     $bank=Bank;
// 		$this->db->select(" sum(v_bank) as v_bank from tm_kbank a where a.i_periode='$iperiode' and a.i_area='$iarea'
// 		                    and a.f_debet='t' and a.f_kbank_cancel='f' and a.i_coa='$coaku'
// 		                    and a.v_bank not in (select b.v_kk as v_bank from tm_kk b where b.d_kk = a.d_bank and b.i_area='$iarea' 
// 	                      and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '$bank%' and b.i_periode='$iperiode')",false);							
// 		$query = $this->db->get();
// 		if ($query->num_rows() > 0){
// 			foreach($query->result() as $row){
// 				$debet=$debet+$row->v_bank;
// 			}
// 		}
		
// #		$coaku=KasKecil.$iarea;
// #		$this->db->select(" sum(v_bank) as v_bank from tm_kbank 
// #		                    where i_periode='$iperiode' and i_area='$iarea' and f_debet='t' and f_kbank_cancel='f' 
// #		                    and i_coa='$coaku'",false);							
// #		$query = $this->db->get();
// #		if ($query->num_rows() > 0){
// #			foreach($query->result() as $row){
// #				$debet=$debet+$row->v_bank;
// #			}
// #		}

// 	  $this->db->select(" sum(v_kb) as v_kb from tm_kb a where a.i_periode='$iperiode' and a.i_area='$iarea'
// 	                      and a.f_debet='t' and a.f_kb_cancel='f' and a.i_coa='$coaku'
// 	                      and a.v_kb not in (select b.v_kk as v_kb from tm_kk b where b.d_kk = a.d_kb and b.i_area='$iarea' 
// 	                      and b.f_kk_cancel='f' and b.f_debet='f' and b.i_coa like '$kasbesar' and b.i_periode='$iperiode')",false);
// #	  $this->db->select(" sum(v_kb) as v_kb from tm_kb 
// #	                      where i_periode='$iperiode' and i_area='$iarea' and f_debet='t' and f_kb_cancel='f' 
// #	                      and i_coa='$coaku'",false);							
// 	  $query = $this->db->get();
// 	  if ($query->num_rows() > 0){
// 		  foreach($query->result() as $row){
// 			  $debet=$debet+$row->v_kb;
// 		  }
// 	  }
	  // $saldo=$saldo+$debet-$kredit;

    $emutasiperiode=$iperiode;
    $bldpn=substr($emutasiperiode,4,2)+1;
    if($bldpn==13)
    {
      $perdpn=substr($emutasiperiode,0,4)+1;
      $perdpn=$perdpn.'01';
    }else{
      $perdpn=substr($emutasiperiode,0,4);
      $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
    }
    $xperiode=$perdpn;
    $this->db->select(" v_saldo_awal from tm_coa_saldo where i_periode='$perdpn' and i_coa='$coa_sekarang' ",false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      $query 	= $this->db->query("SELECT current_timestamp as c");
      $row   	= $query->row();
  	  $update	= $row->c;
      $coa=$coa_sekarang;
      $user=$this->session->userdata('username');
      $this->db->query(" update tm_coa_saldo set v_saldo_awal=$saldo, v_saldo_akhir=$saldo, d_update='$update', i_update='$user'
                         where i_periode='$xperiode' and i_coa='$coa'");
    }else{
      $query 	= $this->db->query("SELECT current_timestamp as c");
      $row   	= $query->row();
  	  $entry	= $row->c;
      $coa=$coa_sekarang;
      $query=$this->db->query(" select e_coa_name from tr_coa where i_coa='$coa'");
      if ($query->num_rows() > 0){
        foreach($query->result() as $tes){
          $nama=$tes->e_coa_name;
        }
      }
      $user=$this->session->userdata('username');
      $this->db->query(" insert into tm_coa_saldo 
                         (i_periode, i_coa, v_saldo_awal, v_mutasi_debet, v_mutasi_kredit, v_saldo_akhir, d_entry,
                          i_entry, e_coa_name)
                         values
                         ('$xperiode','$coa',$saldo,0,0,$saldo,'$entry','$user','$nama')");
    }
  }
}

/* End of file Mmaster.php */