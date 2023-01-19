<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($folder, $username, $idcompany){
		$datatables = new Datatables(new CodeigniterAdapter); 
        $datatables->query("
        	SELECT
			    i_sjp,
			    TO_CHAR(d_sjp, 'dd-mm-yyyy') AS d_sjp,
			    i_bapb,
			    b.i_area,
			    e_area_name,
			    c.i_spmb, 
			    CASE WHEN f_spmb_consigment = 't' THEN 'Ya'
			    WHEN f_spmb_consigment = 'f' THEN 'Tidak' END AS konsinyasi,
			    CASE WHEN f_sjp_cancel = 't' THEN 'Ya'
			    WHEN f_sjp_cancel = 'f' THEN 'Tidak' END AS status,
			    '$folder' AS folder
			FROM
			    tm_sjp a,
			    tr_area b,
			    tm_spmb c
			WHERE
			    a.i_area = b.i_area
			    AND a.i_spmb = c.i_spmb
			    AND (a.i_area IN (
			    SELECT
			        i_area
			    FROM
			        public.tm_user_area
			    WHERE
			        username = '$username'
			        AND id_company = '$idcompany'))
			    AND a.d_sjp_receive IS NULL
			    AND a.f_sjp_cancel = 'f'
			ORDER BY
			    a.i_sjp DESC
        ", false);
		$datatables->add('action', function ($data) {
            $isjp   = trim($data['i_sjp']);
            $iarea  = trim($data['i_area']);
            $folder = $data['folder'];
            $data   = '';
            $data  .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isjp/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
			return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('i_area');
        return $datatables->generate();
    }
    
    public function baca($isjp, $iarea){
		$query = $this->db->query(" 
			SELECT
			    DISTINCT(c.i_store),
			    c.i_store_location,
			    to_char(a.d_sjp, 'dd-mm-yyyy') AS dsjp,
			    to_char(a.d_sjp_receive, 'dd-mm-yyyy') AS dsjpreceive,
			    to_char(a.d_spmb, 'dd-mm-yyyy') AS dspmb,
			    a.*,
			    b.e_area_name
			FROM
			    tm_sjp a,
			    tr_area b,
			    tm_sjp_item c
			WHERE
			    a.i_area = b.i_area
			    AND a.i_sjp = c.i_sjp
			    AND a.i_area = c.i_area
			    AND a.f_sjp_cancel = 'f'
			    AND a.i_sjp = '$isjp'
			    AND a.i_area = '$iarea'
    	", false);
		if ($query->num_rows() > 0){
		  return $query->row();
		}
    }

    public function bacadetail($isjp, $iarea){
		$query = $this->db->query("
			SELECT
			    a.i_sjp,
			    a.d_sjp,
			    a.i_area,
			    a.n_quantity_order,
			    a.i_product,
			    a.i_product_grade,
			    a.i_product_motif,
			    a.n_quantity_receive,
			    a.n_quantity_deliver,
			    a.v_unit_price,
			    a.e_product_name,
			    a.i_store,
			    a.i_store_location,
			    a.i_store_locationbin,
			    a.e_remark,
			    b.e_product_motifname
			FROM
			    tm_sjp_item a,
			    tr_product_motif b
			WHERE
			    a.i_sjp = '$isjp'
			    AND a.i_area = '$iarea'
			    AND a.i_product = b.i_product
			    AND a.i_product_motif = b.i_product_motif
			ORDER BY
			    a.n_item_no
		", false);
		if ($query->num_rows() > 0){
		  	return $query->result();
		}
    }

    public function jmlitem($isjp, $iarea){
    	$this->db->select('*');
    	$this->db->from('tm_sjp_item');
    	$this->db->where('i_sjp',$isjp);
    	$this->db->where('i_area',$iarea);
    	return $this->db->get();
    }

    public function updatesjheader($isj,$iarea,$dsjreceive,$vsjnetto,$vsjrec){
    	$dsjupdate= current_datetime();
    	$this->db->set(
    		array(
    			'v_sjp_receive' => $vsjrec,
    			'd_sjp_receive' => $dsjreceive
    		)
    	);
    	$this->db->where('i_sjp',$isj);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_sjp');
    }

    public function updatesjdetail($iproduct,$iproductgrade,$iproductmotif,$isj,$dsj,$iarea,$nreceive,$ntmp){
    	$th=substr($dsj,0,4);
    	$bl=substr($dsj,5,2);
    	$pr=$th.$bl;
    	$this->db->query("
    		UPDATE
			    tm_sjp_item
			SET
			    n_quantity_receive = $nreceive,
			    n_saldo = $nreceive
			WHERE
			    i_sjp = '$isj'
			    AND i_area = '$iarea'
			    AND i_product = '$iproduct'
			    AND i_product = '$iproduct'
			    AND i_product_motif = '$iproductmotif'
			    AND i_product_grade = '$iproductgrade'
    	");
    }

    public function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductnam){
      	$queri = $this->db->query("
      		SELECT
			    n_quantity_akhir,
			    i_trans
			FROM
			    tm_ic_trans
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
			ORDER BY
			    i_trans DESC
      	");
      	if ($queri->num_rows() > 0){
      		$row   	= $queri->row();
      		$now	= current_datetime();
      		if($ntmp!=0 || $ntmp!=''){
      			$query=$this->db->query(" 
      				INSERT
					    INTO
					    tm_ic_trans ( i_product,
					    i_product_grade,
					    i_product_motif,
					    i_store,
					    i_store_location,
					    i_store_locationbin,
					    e_product_name,
					    i_refference_document,
					    d_transaction,
					    n_quantity_in,
					    n_quantity_out,
					    n_quantity_akhir,
					    n_quantity_awal)
					VALUES ( '$iproduct',
					'$iproductgrade',
					'$iproductmotif',
					'$istore',
					'$istorelocation',
					'$istorelocationbin',
					'$eproductname',
					'$isj',
					'$now',
					0,
					$ntmp,
					$row->n_quantity_akhir-$ntmp,
					$row->n_quantity_akhir )	
      			",false);
      		}
      	}
    }

    public function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj){
    	$query = $this->db->query("
    		UPDATE
			    tm_mutasi
			SET
			    n_mutasi_bbm = n_mutasi_bbm-$qsj,
			    n_saldo_akhir = n_saldo_akhir-$qsj
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
			    AND e_mutasi_periode = '$emutasiperiode'
		",false);
    	if($emutasiperiodesj==$emutasiperiode){
    		$query = $this->db->query(" 
				UPDATE
				    tm_mutasi
				SET
				    n_mutasi_bbk = n_mutasi_bbk-$qsj,
				    n_mutasi_git = n_mutasi_git + $qsj,
				    n_saldo_akhir = n_saldo_akhir + $qsj
				WHERE
				    i_product = '$iproduct'
				    AND i_product_grade = '$iproductgrade'
				    AND i_product_motif = '$iproductmotif'
				    AND i_store = 'AA'
				    AND i_store_location = '01'
				    AND i_store_locationbin = '00'
				    AND e_mutasi_periode = '$emutasiperiodesj'
    		",false);
    	}
    }

    public function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
    	$query=$this->db->query(" 
    		UPDATE
			    tm_ic
			SET
			    n_quantity_stock = n_quantity_stock-$qsj
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
    	",false);
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
    	$query = $this->db->query(" 
    		SELECT
			    n_quantity_awal,
			    n_quantity_akhir,
			    n_quantity_in,
			    n_quantity_out
			FROM
			    tm_ic_trans
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
			ORDER BY
			    i_trans DESC
    	",false);
    	if ($query->num_rows() > 0){
    		return $query->result();
    	}
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
    	$query=$this->db->query("
    		SELECT
			    n_quantity_stock
			FROM
			    tm_ic
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
    	",false);
    	if ($query->num_rows() > 0){
    		return $query->result();
    	}
    }

    public function inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak){
	    $now = current_datetime();
      	$query=$this->db->query(" 
      		INSERT
			    INTO
			    tm_ic_trans ( i_product,
			    i_product_grade,
			    i_product_motif,
			    i_store,
			    i_store_location,
			    i_store_locationbin,
			    e_product_name,
			    i_refference_document,
			    d_transaction,
			    n_quantity_in,
			    n_quantity_out,
			    n_quantity_akhir,
			    n_quantity_awal)
			VALUES ( '$iproduct',
			'$iproductgrade',
			'$iproductmotif',
			'$istore',
			'$istorelocation',
			'$istorelocationbin',
			'$eproductname',
			'$isj',
			'$now',
			$qsj,
			0,
			$q_ak + $qsj,
			$q_ak )
      	",false);
    }

    public function cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
    	$hasil='kosong';
    	$query=$this->db->query(" 
    		SELECT
			    i_product
			FROM
			    tm_mutasi
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
			    AND e_mutasi_periode = '$emutasiperiode'
    	",false);
    	if ($query->num_rows() > 0){
    		$hasil='ada';
    	}
    	return $hasil;
    }

    public function updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj){
    	if( ($qsj=='')||($qsj==null) ) $qsj=0;
    	$query=$this->db->query(" 
    		UPDATE
			    tm_mutasi
			SET
			    n_mutasi_bbm = n_mutasi_bbm + $qsj,
			    n_saldo_akhir = n_saldo_akhir + $qsj
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
			    AND e_mutasi_periode = '$emutasiperiode'	
    	",false);
    	if($emutasiperiodesj==$emutasiperiode){
    		$query=$this->db->query(" 
    			UPDATE
				    tm_mutasi
				SET
				    n_mutasi_bbk = n_mutasi_bbk + $qsj,
				    n_mutasi_git = n_mutasi_git-$qsj,
				    n_saldo_akhir = n_saldo_akhir-$qsj
				WHERE
				    i_product = '$iproduct'
				    AND i_product_grade = '$iproductgrade'
				    AND i_product_motif = '$iproductmotif'
				    AND i_store = 'AA'
				    AND i_store_location = '01'
				    AND i_store_locationbin = '00'
				    AND e_mutasi_periode = '$emutasiperiodesj'	
    		",false);
    	}else{
    		$query=$this->db->query(" 
    			UPDATE
				    tm_mutasi
				SET
				    n_mutasi_bbk = n_mutasi_bbk + $qsj,
				    n_saldo_akhir = n_saldo_akhir-$qsj
				WHERE
				    i_product = '$iproduct'
				    AND i_product_grade = '$iproductgrade'
				    AND i_product_motif = '$iproductmotif'
				    AND i_store = 'AA'
				    AND i_store_location = '01'
				    AND i_store_locationbin = '00'
				    AND e_mutasi_periode = '$emutasiperiode'
    		",false);
    	}
    }

    public function insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode,$emutasiperiodesj){
      	if( ($qsj=='')||($qsj==null) ) {
      		$qsj=0;
      	}
      	$query=$this->db->query("
      		INSERT
			    INTO
			    tm_mutasi ( i_product,
			    i_product_motif,
			    i_product_grade,
			    i_store,
			    i_store_location,
			    i_store_locationbin,
			    e_mutasi_periode,
			    n_saldo_awal,
			    n_mutasi_pembelian,
			    n_mutasi_returoutlet,
			    n_mutasi_bbm,
			    n_mutasi_penjualan,
			    n_mutasi_returpabrik,
			    n_mutasi_bbk,
			    n_saldo_akhir,
			    n_saldo_stockopname,
			    f_mutasi_close,
			    n_mutasi_git)
			VALUES ( '$iproduct',
			'$iproductmotif',
			'$iproductgrade',
			'$istore',
			'$istorelocation',
			'$istorelocationbin',
			'$emutasiperiode',
			0,
			0,
			0,
			$qsj,
			0,
			0,
			0,
			$qsj,
			0,
			'f',
			0)
      	",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
    	$ada = false;
    	$query=$this->db->query("
    		SELECT
			    i_product
			FROM
			    tm_ic
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
    	",false);
    	if ($query->num_rows() > 0){
    		$ada = true;
    	}
    	return $ada;
    }

    public function updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak){
    	if( ($q_ak=='')||($q_ak==null) ) {
    		$q_ak=0;
    	}
    	if( ($qsj=='')||($qsj==null) ) {
    		$qsj=0;
    	}
    	$query=$this->db->query(" 
    		UPDATE
			    tm_ic
			SET
			    n_quantity_stock = $q_ak + $qsj
			WHERE
			    i_product = '$iproduct'
			    AND i_product_grade = '$iproductgrade'
			    AND i_product_motif = '$iproductmotif'
			    AND i_store = '$istore'
			    AND i_store_location = '$istorelocation'
			    AND i_store_locationbin = '$istorelocationbin'
    	",false);
    }

    public function insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qsj){
    	if( ($qsj=='')||($qsj==null) ) {
    		$qsj=0;
    	}
      	$query=$this->db->query("
      		INSERT
			    INTO
			    tm_ic
			VALUES ( '$iproduct',
			'$iproductmotif',
			'$iproductgrade',
			'$istore',
			'$istorelocation',
			'$istorelocationbin',
			'$eproductname',
			$qsj,
			't' )
      	",false);
    }
}

/* End of file Mmaster.php */
