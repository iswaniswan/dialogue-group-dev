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
        $datatables->query("
                        SELECT 
                            a.i_kn, 
                            a.d_kn,
                            a.i_refference,
                            a.d_refference, 
                            a.i_area,
                            a.i_customer,
                            a.i_salesman,
                            a.v_netto,
                            a.v_sisa,
                            b.e_customer_name,
                            e.e_salesman_name,
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
                            left join tr_customer_groupar d on (a.i_customer=d.i_customer)
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_area c on (a.i_area=c.i_area)
                            inner join tr_salesman e on (a.i_salesman=e.i_salesman)
                        WHERE 
                            a.i_kn_type='02' 
                            and a.i_area='$iarea' 
                            and a.d_kn >='$dfrom' 
                            and a.d_kn <='$dto' 
                        ORDER BY 
                            a.i_kn desc"
                    ,false);
        
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

        $datatables->edit('e_customer_name', function($data){
            return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->edit('i_salesman', function($data){
            return '('.($data['i_salesman']).')'.($data['e_salesman_name']);
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
        $datatables->hide('e_salesman_name');
        return $datatables->generate();
    }

    function bacakn($ikn,$nknyear,$iarea){
 		return $this->db->query(" select distinct (a.i_kn||a.i_area||a.i_customer||a.i_salesman),
                                    a.*, b.e_customer_name, b.e_customer_address, c.e_area_name, d.i_customer_groupar, e.e_salesman_name from tm_kn a
                                    left join tr_customer_groupar d on (a.i_customer=d.i_customer)
                                    inner join tr_customer b on (a.i_customer=b.i_customer)
                                    inner join tr_area c on (a.i_area=c.i_area)
                                    inner join tr_salesman e on (a.i_salesman=e.i_salesman)
                                    where a.i_kn_type='02' 
                                    and a.i_kn='$ikn'
                                    and a.n_kn_year=$nknyear
                                    and a.i_area='$iarea'",false);
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
       return $this->db->query("select a.i_bbm, a.i_area, a.i_salesman, d.e_salesman_name, c.e_area_name, b.i_customer, e.e_customer_name, e.e_customer_address,
                                b.v_ttb_gross, b.v_ttb_discounttotal, b.v_ttb_netto, v_ttb_sisa, a.d_bbm, f.i_customer_groupar
                                from tm_bbm a, tr_salesman d, tm_ttbretur b, tr_area c, tr_customer e, tr_customer_groupar f
                                where a.i_bbm_type='05' and a.i_area='$iarea' 
                                and a.i_salesman=d.i_salesman 
                                and a.i_bbm=b.i_bbm and a.i_area=b.i_area
                                and a.i_area=c.i_area
                                and b.i_customer=e.i_customer
                                and e.i_customer=f.i_customer and a.i_bbm not in(select i_refference from tm_kn)
                                order by a.i_bbm",false)->result();
    }

    function update($iarea,$ikn,$icustomer,$irefference,$icustomergroupar,$isalesman,$ikntype,$dkn,$nknyear,$fcetak,$fmasalah,
					$finsentif,$vnetto,$vsisa,$vgross,$vdiscount,$eremark,$drefference){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dupdate	= $row->c;
    	$this->db->set(
    		array(
				'i_area'		    => $iarea,
				'i_kn'			    => $ikn,
				'i_customer' 	    => $icustomer,
				'i_refference' 	    => $irefference,
				'i_customer_groupar'=> $icustomergroupar,
				'i_salesman' 		=> $isalesman,
				'i_kn_type' 		=> $ikntype,
				'd_kn' 				=> $dkn,
				'd_refference'	    => $drefference,
				'd_update' 			=> $dupdate,
				'e_remark' 			=> $eremark,
				'f_cetak' 			=> $fcetak,
				'f_masalah' 		=> $fmasalah,
				'f_insentif' 		=> $finsentif,
				'n_kn_year' 		=> $nknyear,
				'v_netto' 			=> $vnetto,
				'v_gross' 			=> $vgross,
				'v_discount' 		=> $vdiscount,
				'v_sisa'			=> $vsisa,
				'f_kn_cancel'		=> 'f'

    		)
    	);
    	$this->db->where("i_kn",$ikn);
    	$this->db->where("n_kn_year",$nknyear);
    	$this->db->where("i_area",$iarea);
    	$this->db->update('tm_kn');
    }

    public function cancel($ikn,$nknyear,$iarea){
		$this->db->query("update tm_kn set f_kn_cancel='t' WHERE i_kn='$ikn' and n_kn_year=$nknyear and i_area='$iarea'");
    }

    public function getreferensi($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                            select a.*, d.e_salesman_name, c.e_area_name, b.i_customer, e.e_customer_name, e.e_customer_address,
                            b.v_ttb_gross, b.v_ttb_discounttotal, b.v_ttb_netto, v_ttb_sisa, a.d_bbm, f.i_customer_groupar
                            from tm_bbm a, tr_salesman d, tm_ttbretur b, tr_area c, tr_customer e, tr_customer_groupar f
                            where a.i_bbm_type='05' and a.i_area='$iarea' 
                            and a.i_salesman=d.i_salesman 
                            and a.i_bbm=b.i_bbm and a.i_area=b.i_area
                            and a.i_area=c.i_area
                            and b.i_customer=e.i_customer
                            and e.i_customer=f.i_customer and a.i_bbm not in(select i_refference from tm_kn)
                            order by a.i_bbm
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

}

/* End of file Mmaster.php */
