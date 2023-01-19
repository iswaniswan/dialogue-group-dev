<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	/*function data($iperiode,$persen,$persennon){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select row_number() over(order by i_area) as i, i_area, e_area_name, sum(total) as total, sum(realisasi) as realisasi, '$persen' as persen, sum(totalnon) as totalnon, 
							sum(realisasinon) as realisasinon, '$persennon' as persennon from(
							select a.i_area, a.e_area_name, sum(b.v_target_tagihan) as total, sum(b.v_realisasi_tagihan) as realisasi, 
							0 as totalnon, 0 as realisasinon
							from dpp.tr_area a
							left join dpp.tm_collection_item b on(a.i_area=b.i_area)
							where a.f_area_real='t' and b.e_periode='$iperiode' and b.f_insentif='t'
							group by a.i_area, a.e_area_name
							union all
							select a.i_area, a.e_area_name, 0 as total, 0 as realisasi, 
							sum(b.v_target_tagihan) as totalnon, sum(b.v_realisasi_tagihan) as realisasinon
							from dpp.tr_area a
							left join dpp.tm_collection_item b on(a.i_area=b.i_area)
							where a.f_area_real='t' and b.e_periode='$iperiode' and b.f_insentif='f'
							group by a.i_area, a.e_area_name
							) as a
							group by a.i_area, a.e_area_name, realisasinon, totalnon
							order by a.i_area",false);
		$datatables->add('action', function ($data) {
			$iarea      = trim($data['i_area']);
			$i      = trim($data['i']);
			$data = '';
			return $data;
		});
		$datatables->hide('i_area');
		  
        $datatables->edit('e_area_name', function ($data) {
		$i_area = $data['i_area'];
		$e_area_name = $data['e_area_name'];
        	if($i_area == ''){
        	    return '';
        	}else{
        	    return $i_area."-".$e_area_name;
        	}
		});
		$datatables->edit('total', function ($data) {
			$total = $data['total'];
			if($total == ''){
				return '';
			}else{
				return "RP.".number_format($total);
			}
		});
		$datatables->edit('persen', function ($data) {
			$persen = $data['persen'];
			if($persen == ''){
				return '';
			}else{
				return $persen. "%";
			}
		});
		$datatables->edit('persennon', function ($data) {
			$persennon = $data['persennon'];
			if($persennon == ''){
				return '';
			}else{
				return $persennon. "%";
			}
		});
		$datatables->edit('realisasi', function ($data) {
			$realisasi = $data['realisasi'];
			if($realisasi == ''){
				return '';
			}else{
				return "RP.".number_format($realisasi);
			}
		});
		$datatables->edit('realisasinon', function ($data) {
			$realisasinon = $data['realisasinon'];
			if($realisasinon == ''){
				return '';
			}else{
				return "RP.".number_format($realisasinon);
			}
		});
		$datatables->edit('totalnon', function ($data) {
			$totalnon = $data['totalnon'];
			if($totalnon == ''){
				return '';
			}else{
				return "RP.".number_format($totalnon);
			}
		});

        return $datatables->generate();
	}*/
	
	function baca($iperiode){
		return $this->db->query(" select i_area, e_area_name, sum(total) as total, sum(realisasi) as realisasi, sum(totalnon) as totalnon, 
                        	sum(realisasinon) as realisasinon from(
                        	select a.i_area, a.e_area_name, sum(b.v_target_tagihan) as total, sum(b.v_realisasi_tagihan) as realisasi, 
                        	0 as totalnon, 0 as realisasinon
                        	from tr_area a
                        	left join tm_collection_item b on(a.i_area=b.i_area)
                        	where a.f_area_real='t' and b.e_periode='$iperiode' and b.f_insentif='t'
                        	group by a.i_area, a.e_area_name
                        	union all
                        	select a.i_area, a.e_area_name, 0 as total, 0 as realisasi, 
                        	sum(b.v_target_tagihan) as totalnon, sum(b.v_realisasi_tagihan) as realisasinon
                        	from tr_area a
                        	left join tm_collection_item b on(a.i_area=b.i_area)
                        	where a.f_area_real='t' and b.e_periode='$iperiode' and b.f_insentif='f'
                        	group by a.i_area, a.e_area_name
                        	) as a
                        	group by a.i_area, a.e_area_name
                        	order by a.i_area",false);
	}
	
	function simpan($iperiode,$batas){
      	$x=0;
      	$periode=$iperiode;
		$a=substr($periode,0,4);
	    $b=substr($periode,4,2);
		$periode=mbulan($b)." - ".$a;
		settype($a,'integer');
		settype($b,'integer');
		if($b==12){
		  	$a=$a+1;
		  	settype($a,'string');		  
		  	settype($b,'string');
		  	$b='01';
		}else{
        	$b=$b+1;
			settype($a,'string');		  
			settype($b,'string');
			if(strlen($b)==1){
				$b='0'.$b;
			}
		}
		$bts=$a.'-'.$b.'-01';
      	$this->db->query("delete from tm_collection where e_periode='$iperiode'");
      	$this->db->query("delete from tm_collection_item where e_periode='$iperiode'");
     	$this->db->select(" * from f_target_collection_rekapkodealokasi('$iperiode','$bts')",FALSE);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
        	$que 	= $this->db->query("SELECT current_timestamp as c");
	      	$row   	= $que->row();
	      	$now	= $row->c;
	      	$id     = $this->session->userdata('username');
			foreach($query->result() as $row){
			    $x++;
          		$this->db->query("insert into tm_collection values('$iperiode', '$now', '$id')");
          		if($x>0){
          		  break;
          		}
        	}
			foreach($query->result() as $row){
          		if($row->i_as==null)$row->i_as=null;
          		if($row->e_namaas==null)$row->e_namaas=null;
          		if($row->i_salesman==null)$row->i_salesman=null;
          		if($row->e_salesman_name==null)$row->e_salesman_name=null;
          		if($row->i_area==null)$row->i_area=null;
          		if($row->e_area_name==null)$row->e_area_name=null;
          		if($row->f_spb_stockdaerah==null)$row->f_spb_stockdaerah=null;
          		if($row->f_spb_consigment==null)$row->f_spb_consigment=null;
          		if($row->f_insentif==null)$row->f_insentif=null;
          		if($row->i_nota==null)$row->i_nota=null;
          		if($row->d_nota==null)$row->d_nota=null;
          		if($row->n_top==null)$row->n_top=null;
          		if($row->d_jatuh_tempo==null)$row->d_jatuh_tempo=null;
          		if($row->n_lamatoleransi==null)$row->n_lamatoleransi=0;
          		if($row->d_jatuh_tempo_plustoleransi==null)$row->d_jatuh_tempo_plustoleransi=null;
          		$row->d_bayar=null;
          		$row->i_pelunasan=null;
          		$row->d_bukti_pelunasan=null;
          		$row->i_reff_pelunasan=null;
          		$row->i_jenis_bayar=null;
          		$row->e_jenis_bayarname=null;

          		if($row->n_lamabayar==null)$row->n_lamabayar=null;
          
          		$row->e_customer_name=str_replace("'","''",$row->e_customer_name);
          		if($row->i_customer==null)$row->i_customer=null;
          		if($row->e_customer_name==null)$row->e_customer_name=null;
          		$row->e_customer_classname=null;
          		if($row->i_customer_groupar==null)$row->i_customer_groupar=null;
          		if($row->i_customer_groupbayar==null)$row->i_customer_groupbayar=null;
          		if($row->i_product_group==null)$row->i_product_group=null;

          		if($row->v_target_tagihan==null)$row->v_target_tagihan=0;
          		if($row->v_realisasi_tagihan==null)$row->v_realisasi_tagihan=0;


          		$sql="insert into tm_collection_item values('$iperiode', '$row->i_as', '$row->e_namaas', '$row->i_salesman',
          		      '$row->e_salesman_name', '$row->i_area', '$row->e_area_name', '$row->i_customer', '$row->e_customer_name',
          		      '$row->e_customer_classname', '$row->i_customer_groupar', '$row->i_customer_groupbayar', '$row->i_product_group', ";
          		if($row->f_spb_stockdaerah==null){
          		  $sql.="null, ";
          		}else{
          		  $sql.="'$row->f_spb_stockdaerah', ";
          		}
          		if($row->f_spb_consigment==null) {
          		  $sql.="null, ";
          		}else{
          		  $sql.="'$row->f_spb_consigment', ";
          		}
          		if($row->f_insentif==null) {
          		  $sql.="null, ";
          		}else{
          		  $sql.="'$row->f_insentif', ";
          		}
          		$sql.="'$row->i_nota', '$row->d_nota', $row->n_top, '$row->d_jatuh_tempo', $row->n_lamatoleransi, ";
          		if($row->d_jatuh_tempo_plustoleransi==null) {
          		  $sql.="null, ";
          		}else{
          		  $sql.="'$row->d_jatuh_tempo_plustoleransi', ";
          		}
          		if($row->d_bayar==null) {
          		  $sql.="null, ";
          		}else{
          		  $sql.="'$row->d_bayar', ";
          		}
          		$sql.="'$row->i_pelunasan', ";
          		if($row->d_bukti_pelunasan==null) {
          		  $sql.="null, ";
          		}else{
          		  $sql.="'$row->d_bukti_pelunasan', ";
          		}
          		$sql.="'$row->i_reff_pelunasan', '$row->i_jenis_bayar', '$row->e_jenis_bayarname', ";
          		if($row->d_bukti_pelunasan==null) {
          		  $sql.="null, ";
          		}else{
          		  $sql.="'$row->n_lamabayar', ";
          		}
          		$sql.="$row->v_target_tagihan, $row->v_realisasi_tagihan, '$now','$row->kelompok')";
          		$this->db->query($sql);
        	}		
		}
    }	

}

/* End of file Mmaster.php */