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
    public function data($dfrom, $dto, $iarea, $status, $folder){
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
        if($iarea == "NA"){
            $datatables->query("
                            SELECT 
                                distinct a.i_kn, 
                                a.d_kn,
                                a.i_refference,
                                a.d_refference, 
                                f.i_ttb,
                                f.d_ttb,
                                a.i_area,
                                a.i_customer,
                                a.i_salesman,
                                a.v_netto,
                                a.v_sisa,
                                b.e_customer_name,
                                c.e_area_name, 
                                d.i_customer_groupar, 
                                a.n_kn_year, 
                                a.f_kn_cancel,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$folder' as folder,
                                '$status' as status
                            FROM 
                                tm_kn a
                                inner join tr_customer b on (a.i_customer=b.i_customer)
                                inner join tr_area c on (a.i_area=c.i_area)
                                inner join tr_customer_groupar d on (b.i_customer=d.i_customer)
                                inner join tr_salesman e on (a.i_salesman=e.i_salesman)
                                inner join tm_ttbretur f on (a.i_area=f.i_area and a.i_refference=f.i_bbm)
                            WHERE 
                                a.i_kn_type='01' and
                                a.d_kn >= '$dfrom' and
                                a.d_kn <= '$dto'
                            ORDER BY 
                                a.i_kn desc"
                        ,false);
        }else{
            $datatables->query("
                            SELECT 
                                distinct a.i_kn, 
                                a.d_kn,
                                a.i_refference,
                                a.d_refference, 
                                f.i_ttb,
                                f.d_ttb,
                                a.i_area,
                                a.i_customer,
                                a.i_salesman,
                                a.v_netto,
                                a.v_sisa, 
                                b.e_customer_name, 
                                c.e_area_name, 
                                d.i_customer_groupar, 
                                a.n_kn_year,
                                a.f_kn_cancel,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$folder' as folder,
                                '$status' as status

                            FROM
                                tm_kn a
                                inner join tr_customer b on (a.i_customer=b.i_customer)
                                inner join tr_area c on (a.i_area=c.i_area)
                                inner join tr_customer_groupar d on (b.i_customer=d.i_customer)
                                inner join tr_salesman e on (a.i_salesman=e.i_salesman)
                                inner join tm_ttbretur f on (a.i_area=f.i_area and a.i_refference=f.i_bbm)
                            WHERE 
                                a.i_kn_type='01' and 
                                a.i_area='$iarea' and
                                a.d_kn >= '$dfrom' and
                                a.d_kn <= '$dto'
                            ORDER BY 
                                a.i_kn desc"
                        ,false);
        }
        
        
        $datatables->edit('v_netto', function($data){
            return number_format($data['v_netto']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->edit('d_kn', function($data){
            return date("d-m-Y", strtotime($data['d_kn']));
        });

        $datatables->edit('d_refference', function($data){
            return date("d-m-Y", strtotime($data['d_refference']));
        });

        $datatables->edit('d_ttb', function($data){
            return date("d-m-Y", strtotime($data['d_ttb']));
        });

        $datatables->edit('e_customer_name', function($data){
            return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->add('action', function ($data) {
            $ikn            = $data['i_kn'];
            $folder         = $data['folder'];
            $iarea          = $data['i_area'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $icsg           = $data['i_customer_groupar'];
            $nknyear        = $data['n_kn_year'];
            $vnetto         = $data['v_netto'];
            $vsisa          = $data['v_sisa'];
            $irefference    = $data['i_refference'];
            $fkncancel      = $data['f_kn_cancel'];
            $status         = $data['status'];
            $icustomer      = $data['i_customer'];
            $data           = '';
            if($status == '1'){
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ikn/$nknyear/$iarea/$dfrom/$dto/$irefference/$icustomer\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                if($fkncancel == 'f' && $vsisa != 0 && $vnetto == $vsisa){
                    $data .= "<a href=\"#\" onclick='hapus(\"$ikn\",\"$nknyear\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                }
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_customer_groupar');
        $datatables->hide('n_kn_year');
        $datatables->hide('e_area_name');
        $datatables->hide('e_customer_name');
        $datatables->hide('f_kn_cancel');
        $datatables->hide('status');
        return $datatables->generate();
    }

    function bacakn($ikn,$nknyear,$iarea){
 		return $this->db->query(" select distinct (a.i_kn||a.i_area||a.i_customer||a.i_salesman),
                                    a.*, b.e_customer_name, b.e_customer_address, c.e_area_name, d.i_customer_groupar, 
				                    e.e_salesman_name, g.n_ttb_discount1, g.n_ttb_discount2, g.n_ttb_discount3,
                                    g.v_ttb_discount1, g.v_ttb_discount2, g.v_ttb_discount3, g.v_ttb_gross, g.v_ttb_discounttotal,g.v_ttb_netto
					                from tm_kn a
				                    inner join tr_customer b on (a.i_customer=b.i_customer)
				                    inner join tr_area c on (a.i_area=c.i_area)
				                    left join tr_customer_groupar d on (b.i_customer=d.i_customer)
				                    inner join tr_salesman e on (a.i_salesman=e.i_salesman)
                                    inner join tm_bbm f on (a.i_refference=f.i_bbm)
                                    inner join tm_ttbretur g on (f.i_refference_document=g.i_ttb and f.d_refference_document=g.d_ttb and f.i_area=g.i_area)
			                        where a.i_kn_type='01'
					                and a.i_kn='$ikn'
					                and a.n_kn_year=$nknyear
					                and a.i_area='$iarea'",false);
    }
    
    public function getreferensi($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_bbm,
                to_char(a.d_bbm, 'dd-mm-yyyy') AS d_bbm,
                b.i_ttb
            FROM
                tm_bbm a
            INNER JOIN tr_salesman d ON
                (a.i_salesman = d.i_salesman)
            INNER JOIN tm_ttbretur b ON
                (a.i_bbm = b.i_bbm
                AND a.i_area = b.i_area)
            INNER JOIN tr_area c ON
                (a.i_area = c.i_area)
            LEFT JOIN tr_customer e ON
                (b.i_customer = e.i_customer)
            LEFT JOIN tr_customer_groupar f ON
                (e.i_customer = f.i_customer)
            WHERE
                a.i_bbm_type = '05'
                AND a.i_area = '$iarea'
                AND a.i_bbm LIKE 'BBM%'
                AND NOT a.i_bbm IN(
                SELECT
                    i_refference
                FROM
                    tm_kn
                WHERE
                    i_area = '$iarea'
                    AND i_refference LIKE 'BBM%')
                AND (a.i_bbm LIKE '%$cari%'
                OR b.i_ttb LIKE '%$cari%')
            ORDER BY
                a.i_bbm
        ", FALSE);
    }

    public function getdetailbbm($ibbm){
        return $this->db->query("
            SELECT
                a.i_bbm,
                a.i_refference_document,
                a.i_product,
                a.i_product_motif,
                a.i_product_grade,
                a.n_quantity,
                a.v_unit_price,
                a.e_remark,
                a.e_product_name,
                a.d_refference_document,
                a.e_mutasi_periode,
                b.e_product_motifname
            FROM
                tm_bbm_item a,
                tr_product_motif b
            WHERE
                a.i_bbm_type = '05'
                AND a.i_bbm = '$ibbm'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", FALSE);
    }

    public function jmldetail($ibbm){
        $jml=0;
        $this->db->select(" 
                COUNT(a.i_bbm) AS jml
            FROM
                tm_bbm_item a,
                tr_product_motif b
            WHERE
                a.i_bbm_type = '05'
                AND a.i_bbm = '$ibbm'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
        ",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $xx){
                $jml=$xx->jml;
            }
        }
        return $jml;
    }


    public function getpajak($cari,$icustomer,$iproduct){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.i_seri_pajak
            FROM
                tm_nota a,
                tr_customer b,
                tm_nota_item c,
                tr_customer_groupbayar d
            WHERE
                a.i_customer = d.i_customer
                AND b.i_customer = d.i_customer
                AND a.i_nota = c.i_nota
                AND a.i_area = c.i_area
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_koreksi = 'f'
                AND a.f_nota_cancel = 'f'
                AND NOT a.i_nota ISNULL
                AND d.i_customer_groupbayar IN (
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    i_customer = '$icustomer' )
                AND UPPER(c.i_product) LIKE '%$iproduct%'
                AND (UPPER(b.i_customer) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%'
                OR UPPER(c.i_product) LIKE '%$cari%'
                OR UPPER(a.i_nota) LIKE '%$cari%')
            ORDER BY
                a.i_nota
        ", FALSE);
    }

    public function getdetailpajak($inota, $icustomer, $iproduct){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.d_nota,
                a.i_seri_pajak,
                to_char(a.d_pajak, 'dd-mm-yyyy') AS d_pajak,
                a.v_nota_netto
            FROM
                tm_nota a,
                tr_customer b,
                tm_nota_item c,
                tr_customer_groupbayar d
            WHERE
                a.i_customer = d.i_customer
                AND b.i_customer = d.i_customer
                AND a.i_nota = c.i_nota
                AND a.i_area = c.i_area
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_koreksi = 'f'
                AND a.f_nota_cancel = 'f'
                AND NOT a.i_nota ISNULL
                AND d.i_customer_groupbayar IN (
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    i_customer = '$icustomer' )
                AND UPPER(c.i_product) LIKE '%$iproduct%'
                AND a.i_nota = '$inota'
            ORDER BY
                a.i_nota
        ", FALSE);
    }

    public function getdetailref($ibbm, $iarea){
        return $this->db->query("
            SELECT
                a.i_bbm,
                a.i_area,
                a.i_salesman,
                d.e_salesman_name,
                c.e_area_name,
                b.i_customer,
                e.e_customer_name,
                e.e_customer_address,
                b.n_ttb_discount1,
                b.n_ttb_discount2,
                b.n_ttb_discount3,
                b.v_ttb_discount1,
                b.v_ttb_discount2,
                b.i_ttb,
                b.d_ttb,
                b.v_ttb_discount3,
                b.v_ttb_gross,
                b.v_ttb_discounttotal,
                b.v_ttb_netto,
                b.v_ttb_sisa,
                to_char(a.d_bbm, 'dd-mm-yyyy') AS d_bbm,
                a.i_refference_document,
                a.d_refference_document,
                f.i_customer_groupar
            FROM
                tm_bbm a
            INNER JOIN tr_salesman d ON
                (a.i_salesman = d.i_salesman)
            INNER JOIN tm_ttbretur b ON
                (a.i_bbm = b.i_bbm
                AND a.i_area = b.i_area)
            INNER JOIN tr_area c ON
                (a.i_area = c.i_area)
            LEFT JOIN tr_customer e ON
                (b.i_customer = e.i_customer)
            LEFT JOIN tr_customer_groupar f ON
                (e.i_customer = f.i_customer)
            WHERE
                a.i_bbm_type = '05'
                AND a.i_area = '$iarea'
                AND a.i_bbm LIKE 'BBM%'
                AND NOT a.i_bbm IN(
                SELECT
                    i_refference
                FROM
                    tm_kn
                WHERE
                    i_area = '$iarea'
                    AND i_refference LIKE 'BBM%')
                AND a.i_bbm = '$ibbm'
            ORDER BY
                a.i_bbm
        ", FALSE);
    }

    function bacabbmdetail($ibbm){
		$this->db->select(" a.i_bbm, a.i_refference_document, a.i_product, a.i_product_motif, a.i_product_grade, a.n_quantity, a.v_unit_price,
                            a.e_remark, a.e_product_name, a.d_refference_document, a.e_mutasi_periode, b.e_product_motifname
					        from tm_bbm_item a, tr_product_motif b where a.i_bbm_type='05' and a.i_bbm='$ibbm' 
                            and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                            order by a.n_item_no",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    public function bacabbm($iarea){
       return $this->db->query(" select a.i_bbm, a.i_area, a.i_salesman, d.e_salesman_name, c.e_area_name, b.i_customer, e.e_customer_name, e.e_customer_address,
					        b.n_ttb_discount1, b.n_ttb_discount2, b.n_ttb_discount3, b.v_ttb_discount1, b.v_ttb_discount2, b.i_ttb, b.d_ttb,
                            b.v_ttb_discount3, b.v_ttb_gross, b.v_ttb_discounttotal, b.v_ttb_netto, b.v_ttb_sisa, a.d_bbm, a.i_refference_document,
                            a.d_refference_document, f.i_customer_groupar
					        from tm_bbm a
					        inner join tr_salesman d on (a.i_salesman=d.i_salesman)
					        inner join tm_ttbretur b on (a.i_bbm=b.i_bbm and a.i_area=b.i_area)
					        inner join tr_area c on (a.i_area=c.i_area)
					        left join tr_customer e on (b.i_customer=e.i_customer)
					        left join tr_customer_groupar f on (e.i_customer=f.i_customer)
					        where a.i_bbm_type='05' and a.i_area='$iarea' and a.i_bbm like 'BBM%'
                            and not a.i_bbm in(select i_refference from tm_kn where i_area='$iarea' and i_refference like 'BBM%')
					        order by a.i_bbm",false)->result();
    }

    public function bacapajak($icustomer){
        return $this->db->query("select a.i_nota, a.d_nota, a.i_seri_pajak, a.d_pajak, a.v_nota_netto
                                from tm_nota a, tr_customer b, tm_nota_item c, tr_customer_groupbayar d
                                where a.i_customer=d.i_customer and b.i_customer=d.i_customer
                                and a.i_nota=c.i_nota and a.i_area=c.i_area and a.f_ttb_tolak='f' and a.f_nota_koreksi='f'
                                and a.f_nota_cancel='f' and not a.i_nota isnull 
                                and d.i_customer_groupbayar in 
                                (select i_customer_groupbayar from tr_customer_groupbayar where i_customer='$icustomer' )
                                ORDER BY a.i_nota ",false)->result();
    }


    function insertdetail($itunai,$iarea,$inota,$vjumlah,$i){
        $this->db->select("	v_jumlah from tm_tunai_item where i_tunai='$itunai' and i_area='$iarea' and i_nota='$inota'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		    $this->db->set(
      		    array(
			    	'i_tunai'           => $itunai,
                    'i_area'            => $iarea,
                    'i_nota'            => $inota,
                    'v_jumlah'          => $vjumlah,
                    'n_item_no'         => $i
      		    )
      	    );
      	    $this->db->where('i_tunai',$itunai);
      	    $this->db->where('i_area',$iarea);
      	    $this->db->where('i_nota',$inota);
      	    $this->db->update('tm_tunai_item');
        }else{
        $this->db->set(
      		array(
				    'i_tunai'          => $itunai,
                    'i_area'           => $iarea,
                    'i_nota'           => $inota,
                    'v_jumlah'         => $vjumlah,
                    'n_item_no'        => $i
      		)
      	);
      	$this->db->insert('tm_tunai_item');
      }
    }

    function update($iarea,$ikn,$icustomer,$irefference,$icustomergroupar,$isalesman,$ikntype,$dkn,$nknyear,$fcetak,$fmasalah,
					$finsentif,$vnetto,$vsisa,$vgross,$vdiscount,$eremark,$drefference,$ipajak,$dpajak){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dupdate	= $row->c;
      if($dpajak!=''){
      	$this->db->set(
      		array(
				  'i_area'			    => $iarea,
				  'i_kn'			    => $ikn,
				  'i_customer' 		    => $icustomer,
				  'i_refference' 	    => $irefference,
				  'i_customer_groupar'  => $icustomergroupar,
				  'i_salesman' 		    => $isalesman,
				  'i_kn_type' 		    => $ikntype,
				  'd_kn' 				=> $dkn,
   				  'i_pajak' 			=> $ipajak,
				  'd_pajak' 			=> $dpajak,
				  'd_refference'	    => $drefference,
				  'd_update' 			=> $dupdate,
				  'e_remark' 			=> $eremark,
				  'f_cetak' 			=> $fcetak,
				  'f_masalah' 		    => $fmasalah,
				  'f_insentif' 		    => $finsentif,
				  'n_kn_year' 		    => $nknyear,
				  'v_netto' 			=> $vnetto,
				  'v_gross' 			=> $vgross,
				  'v_discount' 		    => $vdiscount,
				  'v_sisa'				=> $vsisa,
				  'f_kn_cancel'		    => 'f'

      		));
      }else{
    	  $this->db->set(
      		array(
				  'i_area'			    => $iarea,
				  'i_kn'			    => $ikn,
				  'i_customer' 		    => $icustomer,
				  'i_refference' 	    => $irefference,
				  'i_customer_groupar'  => $icustomergroupar,
				  'i_salesman' 		    => $isalesman,
				  'i_kn_type' 		    => $ikntype,
				  'd_kn' 				=> $dkn,
				  'd_refference'	    => $drefference,
				  'd_update' 			=> $dupdate,
				  'e_remark' 			=> $eremark,
				  'f_cetak' 			=> $fcetak,
				  'f_masalah' 		    => $fmasalah,
				  'f_insentif' 		    => $finsentif,
				  'n_kn_year' 		    => $nknyear,
				  'v_netto' 			=> $vnetto,
				  'v_gross' 			=> $vgross,
				  'v_discount' 		    => $vdiscount,
				  'v_sisa'				=> $vsisa,
				  'f_kn_cancel'		    => 'f'

      		));
      }
    	$this->db->where("i_kn",$ikn);
    	$this->db->where("n_kn_year",$nknyear);
    	$this->db->where("i_area",$iarea);
    	$this->db->update('tm_kn');
    }

    public function cancel($ikn,$nknyear,$iarea){
		$this->db->query("update tm_kn set f_kn_cancel='t' WHERE i_kn='$ikn' and n_kn_year=$nknyear and i_area='$iarea'");
        $this->db->select("i_refference from tm_kn WHERE i_kn='$ikn' and n_kn_year=$nknyear and i_area='$iarea' ");
        $query = $this->db->get();
        if ($query->num_rows() > 0){
	        foreach($query->result() as $kn){
                $irefference=$kn->i_refference;
            }
            $this->db->set(
                array(
                    'f_kn' => 'f'
                )
            );
        $this->db->where("i_bbm",$irefference);
        $this->db->where("i_bbm_type",'05');
        $this->db->update('tm_bbm');
        }
    }
}

/* End of file Mmaster.php */
