<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea()
    {
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'NA';
        }else{
            return 'XX';
        }
    }

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE);
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu,$xarea){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" SELECT
                                c.e_area_name,
                                a.i_ttb,
                                to_char(a.d_ttb,'dd-mm-yyyy') AS d_ttb,
                                a.i_nota,
                                to_char(a.d_nota,'dd-mm-yyyy') AS d_nota,
                                a.i_customer,
                                b.e_customer_name,
                                a.i_area,
                                a.n_ttb_year,
                                d.f_nota_koreksi,
                                a.f_ttb_cancel AS status,
                                '$folder' AS folder,
                                '$i_menu' AS i_menu,
                                '$xarea' AS xarea,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto
                            from
                                tm_ttbtolak a,
                                tr_customer b,
                                tr_area c,
                                tm_nota d
                            where
                                a.i_nota = d.i_nota
                                and a.i_area = c.i_area
                                and a.i_area = d.i_area
                                and a.i_customer = b.i_customer
                                $sql
                                and a.d_ttb >= to_date('$dfrom', 'dd-mm-yyyy')
                                and a.d_ttb <= to_date('$dto', 'dd-mm-yyyy')
                            order by
                                a.i_ttb desc ", FALSE);

        $datatables->add('action', function ($data) {
            $ittb           = $data['i_ttb'];
            $iarea          = $data['i_area'];
            $ttbyear        = $data['n_ttb_year'];
            $status         = $data['status'];
            $nota           = $data['i_nota'];
            $fnotakoreksi   = $data['f_nota_koreksi'];
            $xarea          = $data['xarea'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';

            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ittb/$iarea/$ttbyear/$fnotakoreksi/$dfrom/$dto/$xarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            
            if(check_role($i_menu, 4) && $status == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$ittb\",\"$iarea\",\"$ttbyear\",\"$nota\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('i_ttb', function ($data) {
            if ($data['status']!='f') {
                $data = '<span class="text-danger font-medium"><b>'.$data['i_ttb'].'</b></span>';
            }else{
                $data = '<span class="font-medium"><b>'.$data['i_ttb'].'</b></span>';
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('n_ttb_year');
        $datatables->hide('status');
        $datatables->hide('f_nota_koreksi');
        $datatables->hide('i_customer');
        $datatables->hide('xarea');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        
        return $datatables->generate();
    }

    public function cancel($ittb, $iarea, $tahun, $inota){
        $this->db->query("
            UPDATE tm_ttbtolak 
            SET f_ttb_cancel='t' 
            WHERE i_ttb='$ittb' 
            AND i_area='$iarea' 
            AND n_ttb_year=$tahun");

        $this->db->query("update tm_nota set f_ttb_tolak='f' WHERE i_nota='$inota'");
       
    }

    public function baca($iarea,$ittb,$tahun){
        $query = $this->db->query(" SELECT
                                        a.d_ttb,
                                        a.n_ttb_year,
                                        a.d_nota,
                                        a.i_nota,
                                        a.d_receive1,
                                        a.v_ttb_gross,
                                        a.i_area,
                                        a.n_ttb_discount1,
                                        a.n_ttb_discount2,
                                        a.n_ttb_discount3,
                                        a.v_ttb_discount1,
                                        a.v_ttb_discount2,
                                        a.v_ttb_discount3,
                                        a.i_salesman,
                                        a.v_ttb_discounttotal,
                                        a.f_ttb_plusppn,
                                        a.f_ttb_plusdiscount,
                                        a.v_ttb_netto,
                                        a.i_customer,
                                        b.i_bbm,
                                        c.e_area_name,
                                        d.e_customer_name,
                                        e.e_salesman_name,
                                        f.e_customer_pkpnpwp,
                                        a.f_ttb_cancel
                                    FROM
                                        tm_ttbtolak a ,
                                        tm_bbm b ,
                                        tr_area c ,
                                        tr_customer d ,
                                        tr_salesman e ,
                                        tr_customer_pkp f
                                    where
                                        a.i_area = '$iarea'
                                        and a.i_ttb = '$ittb'
                                        and a.n_ttb_year = $tahun
                                        and a.i_ttb = b.i_refference_document
                                        and a.i_area = c.i_area
                                        and a.i_customer = d.i_customer
                                        and a.i_customer = f.i_customer
                                        and a.i_salesman = e.i_salesman ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($iarea,$ittb,$tahun,$koreksi,$nota){
        $query = $this->db->query(" SELECT
                                        a.i_product,
                                        a.i_product_motif,
                                        c.e_product_name,
                                        a.v_unit_price,
                                        a.n_deliver,
                                        b.n_quantity,
                                        d.e_product_motifname,
                                        b.e_ttb_remark
                                    from
                                        tm_nota_item a
                                    inner join tr_product c on
                                        (c.i_product = a.i_product)
                                    inner join tr_product_motif d on
                                        (d.i_product_motif = a.i_product_motif
                                        and d.i_product = a.i_product)
                                    left join tm_ttbtolak_item b on
                                        (b.i_ttb = '$ittb'
                                        and b.i_area = '$iarea'
                                        and b.n_ttb_year = $tahun
                                        and b.i_product = a.i_product
                                        and b.i_product_motif = a.i_product_motif)
                                    where
                                        a.i_nota = '$nota'
                                        and a.i_area = '$iarea'
                                    order by
                                        a.n_item_no ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacacustomer($cari,$iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer) a.i_customer, e_customer_name 
            FROM
                tr_customer a
            LEFT JOIN tr_customer_pkp b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_price_group c ON
                (a.i_price_group = c.n_line
                OR a.i_price_group = c.i_price_group)
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer)
            LEFT JOIN tr_customer_discount f ON
                (a.i_customer = f.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND (UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%')
            ORDER BY
                a.i_customer
        ", FALSE);
    }

    public function detailcustomer($icustomer,$iarea){
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer) *
            FROM
                tr_customer a
            LEFT JOIN tr_customer_pkp b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_price_group c ON
                (a.i_price_group = c.n_line
                OR a.i_price_group = c.i_price_group)
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer)
            LEFT JOIN tr_customer_discount f ON
                (a.i_customer = f.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
            ORDER BY
                a.i_customer
        ", FALSE);
    }

    public function bacasalesman($cari,$iarea,$per){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT a.i_salesman,
                a.e_salesman_name
            FROM
                tr_customer_salesman a,
                tr_salesman b
            WHERE
                (upper(a.e_salesman_name) LIKE '%$cari%'
                OR upper(a.i_salesman) LIKE '%$cari%')
                AND a.i_area = '$iarea'
                AND a.i_salesman = b.i_salesman
                AND b.f_salesman_aktif = 'true'
                AND a.e_periode = '$per'
        ", FALSE);
    }

    public function bacaalasan($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                *
            FROM
                tr_alasan_retur
            WHERE
                upper(i_alasan_retur) LIKE '%$cari%'
                OR upper(e_alasan_returname)= '%$cari%'
            ORDER BY
                i_alasan_retur
        ", FALSE);
    }

    public function product($cari,$customer,$kdharga) {
        $cari = str_replace("'", "", $cari);
        $i_area = substr($customer,0, 2);
        if ($i_area=='PB') {
            return $this->db->query("
                SELECT
                    b.i_product AS kode,
                    b.i_price_group,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    round(b.v_product_retail*0.75) AS harga,
                    c.e_product_name AS nama
                FROM
                    tr_product_motif a,
                    tr_product c,
                    tr_product_priceco b,
                    tr_customer_consigment d
                WHERE
                    a.i_product = c.i_product
                    AND a.i_product = b.i_product
                    AND d.i_customer = '$customer'
                    AND d.i_price_groupco = b.i_price_groupco
                    AND a.i_product_motif = '00'
                    AND (UPPER(a.i_product) LIKE '%$cari%' 
                    OR UPPER(c.e_product_name) LIKE '%$cari%')
                ORDER BY
                    c.i_product,
                    a.e_product_motifname,
                    b.i_price_group
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND b.i_price_group = '$kdharga'
                    AND (UPPER(a.i_product) LIKE '%$cari%' 
                    OR UPPER(c.e_product_name) LIKE '%$cari%')
            ", FALSE);
        }
    }

    public function detailproduct($iproduct,$customer,$kdharga) {
        $i_area = substr($customer,0, 2);
        if ($i_area=='PB') {
            return $this->db->query("
                SELECT
                    b.i_product AS kode,
                    b.i_price_group,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    round(b.v_product_retail*0.75) AS harga,
                    c.e_product_name AS nama
                FROM
                    tr_product_motif a,
                    tr_product c,
                    tr_product_priceco b,
                    tr_customer_consigment d
                WHERE
                    a.i_product = c.i_product
                    AND a.i_product = b.i_product
                    AND d.i_customer = '$customer'
                    AND d.i_price_groupco = b.i_price_groupco
                    AND a.i_product_motif = '00'
                    AND UPPER(a.i_product) = '$iproduct' 
                ORDER BY
                    c.i_product,
                    a.e_product_motifname,
                    b.i_price_group
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND b.i_price_group = '$kdharga'
                    AND UPPER(a.i_product) = '$iproduct' 
            ", FALSE);
        }
    }

    function updateheader(	$ittb,$iarea,$tahun,$dttb,$dreceive1,$eremark,
							$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,
							$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,
							$vttbgross)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dupdate= $row->c;
    	$this->db->set(
    		array(
			'd_ttb'					=> $dttb,
			'd_receive1'			=> $dreceive1,
			'e_ttb_remark'			=> $eremark,
			'd_update'				=> $dupdate,
			'n_ttb_discount1'		=> $nttbdiscount1,
			'n_ttb_discount2'		=> $nttbdiscount2,
			'n_ttb_discount3'		=> $nttbdiscount3,
			'v_ttb_discount1'		=> $vttbdiscount1,
			'v_ttb_discount2'		=> $vttbdiscount2,
			'v_ttb_discount3'		=> $vttbdiscount3,
			'v_ttb_gross'			=> $vttbgross,
			'v_ttb_discounttotal'	=> $vttbdiscounttotal,
			'v_ttb_netto'			=> $vttbnetto
							
    		)
    	);
		$this->db->where('i_ttb',$ittb);
		$this->db->where('i_area',$iarea);
		$this->db->where('n_ttb_year',$tahun);
    	$this->db->update('tm_ttbtolak');
    }

    public function deletedetail($iproduct, $iproductgrade, $ittb, $iproductmotif,$nquantity,$istore,
								 $istorelocation,$istorelocationbin,$tahun,$iarea) 
    {
		$this->db->query("DELETE FROM tm_ttbtolak_item WHERE i_ttb='$ittb' and i_area='$iarea'
						  and i_product='$iproduct' and i_product_motif='$iproductmotif' 
						  and i_product_grade='$iproductgrade'");
		$this->db->query("DELETE FROM tm_bbm_item WHERE i_refference_document='$ittb' and to_char(d_refference_document,'yyyy')='$tahun'
						  and i_product='$iproduct' and i_product_motif='$iproductmotif' 
						  and i_product_grade='$iproductgrade'");
		return TRUE;
    }

    function updatebbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea)
    {
    	$this->db->set(
    		array(
				'i_refference_document'	=> $ittb,
				'd_refference_document'	=> $dttb,
				'd_bbm'					=> $dbbm,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea
    		)
    	);
    	$this->db->where('i_bbm',$ibbm);
		$this->db->where('i_bbm_type',$ibbmtype);
    	$this->db->update('tm_bbm');
    }

    public function updateheaderdetail($ittb,$iarea,$tahun,$dttb,$dreceive1,$ettbremark,$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,$vttbgross,$icustomer,$ibbm){
        $dupdate= current_datetime();
        $this->db->set(
            array(
                'd_ttb'                 => $dttb,
                'd_receive1'            => $dreceive1,
                'd_update'              => $dupdate,
                'n_ttb_discount1'       => $nttbdiscount1,
                'n_ttb_discount2'       => $nttbdiscount2,
                'n_ttb_discount3'       => $nttbdiscount3,
                'v_ttb_discount1'       => $vttbdiscount1,
                'v_ttb_discount2'       => $vttbdiscount2,
                'v_ttb_discount3'       => $vttbdiscount3,
                'v_ttb_gross'           => $vttbgross,
                'v_ttb_discounttotal'   => $vttbdiscounttotal,
                'v_ttb_netto'           => $vttbnetto,
                'v_ttb_sisa'            => $vttbnetto,
                'f_ttb_cancel'          => 'f',
                'i_customer'            => $icustomer,
                'n_ttb_year'            => $tahun,
            )
        );
        $this->db->where('i_ttb',$ittb);
        $this->db->where('i_area',$iarea);
        $this->db->where('n_ttb_year',$tahun);
        $this->db->update('tm_ttbretur');

        $query    = $this->db->query("
            SELECT 
                i_customer_groupar 
            FROM tr_customer_groupar 
            WHERE i_customer='$icustomer'");
        $row      = $query->row();
        $icustomergroupar= $row->i_customer_groupar;
        $this->db->set(
            array(
              'i_customer'          => $icustomer,
              'i_customer_groupar'  => $icustomergroupar,
              'v_gross'             => $vttbgross,
              'v_discount'          => $vttbdiscounttotal,
              'v_netto'             => $vttbnetto,
              'v_sisa'              => $vttbnetto
          )
        );
        $this->db->where('i_refference',$ibbm);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_kn');
    }

    public function updatedetail($iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$xtahun,$ndeliver,$i){
        $query=$this->db->query("
            SELECT
                *
            FROM
                tm_ttbretur_item
            WHERE
                i_ttb = '$ittb'
                AND i_area = '$iarea'
                AND i_product1 = '$iproduct'
                AND i_product1_grade = '$iproductgrade'
                AND i_product1_motif = '$iproductmotif'
        ");
        if($query->num_rows()==0){
            $this->db->set(
                array(
                    'i_area'              => $iarea,
                    'i_ttb'               => $ittb,
                    'd_ttb'               => $dttb,
                    'i_product1'          => $iproduct,
                    'i_product1_grade'    => $iproductgrade,
                    'i_product1_motif'    => $iproductmotif,
                    'n_quantity'          => $nquantity,
                    'v_unit_price'        => $vunitprice,
                    'e_ttb_remark'        => $ettbremark,
                    'n_ttb_year'          => $tahun,
                    'n_item_no'           => $i
                )
            );
            $this->db->insert('tm_ttbretur_item');
        }else{
            $this->db->set(
                array(
                    'd_ttb'               => $dttb,
                    'n_quantity'          => $nquantity,
                    'v_unit_price'        => $vunitprice,
                    'e_ttb_remark'        => $ettbremark,
                    'n_ttb_year'          => $tahun,
                    'n_item_no'           => $i
                )
            );
            $this->db->where('i_ttb',$ittb);
            $this->db->where('i_area',$iarea);
            $this->db->where('n_ttb_year',$xtahun);
            $this->db->where('i_product1',$iproduct);
            $this->db->where('i_product1_grade',$iproductgrade);
            $this->db->where('i_product1_motif',$iproductmotif);
            $this->db->update('tm_ttbretur_item');
        }
    }

    public function updatebbm($ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$vunitprice){
        $this->db->set(
            array(
                'v_unit_price'      => $vunitprice
            )
        );
        $this->db->where('trim(i_refference_document)',$ittb);
        $this->db->where('d_refference_document',$dttb);
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_product_grade',$iproductgrade);
        $this->db->where('i_product_motif',$iproductmotif);
        $this->db->update('tm_bbm_item');
    }

    function runningnumberbbm($thbl){
        $th	    = substr($thbl,0,4);
        $asal   = $thbl;
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='BBM'
                        and substr(e_periode,1,4)='$th' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
              $terakhir=$row->max;
            }
            $nobbm  =$terakhir+1;
      $this->db->query(" update tm_dgu_no 
                          set n_modul_no=$nobbm
                          where i_modul='BBM'
                          and substr(e_periode,1,4)='$th' ", false);
            settype($nobbm,"string");
            $a=strlen($nobbm);
            while($a<6){
              $nobbm="0".$nobbm;
              $a=strlen($nobbm);
            }
      
            $nobbm  ="BBM-".$thbl."-".$nobbm;
            return $nobbm;
        }else{
            $nobbm  ="000001";
            $nobbm  ="BBM-".$thbl."-".$nobbm;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('BBM','00',$asal,1)");
            return $nobbm;
        }
    }
    
    function insertheader(	$iarea,$ittb,$dttb,$icustomer,$isalesman,$inota,$dnota,$nttbdiscount1,$nttbdiscount2,
							$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$fttbpkp,$fttbplusppn,
							$fttbplusdiscount,$vttbgross,$vttbdiscounttotal,$vttbnetto,$ettbremark,$fttbcancel,
							$dreceive1,$tahun,$isj)
    {
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
					'i_area'				=> $iarea,
					'i_ttb'					=> $ittb,
					'd_ttb'					=> $dttb,
					'i_customer'			=> $icustomer,
					'i_salesman'			=> $isalesman,
					'i_nota'				=> $inota,
					'd_nota'				=> $dnota,
					'n_ttb_discount1'		=> $nttbdiscount1,
					'n_ttb_discount2'		=> $nttbdiscount2,
					'n_ttb_discount3'		=> $nttbdiscount3,
					'v_ttb_discount1'		=> $vttbdiscount1,
					'v_ttb_discount2'		=> $vttbdiscount2,
					'v_ttb_discount3'		=> $vttbdiscount3,
					'f_ttb_pkp'				=> $fttbpkp,
					'f_ttb_plusppn'			=> $fttbplusppn,
					'f_ttb_plusdiscount'	=> $fttbplusdiscount,
					'v_ttb_gross'			=> $vttbgross,
					'v_ttb_discounttotal'	=> $vttbdiscounttotal,
					'v_ttb_netto'			=> $vttbnetto,
					'e_ttb_remark'			=> $ettbremark,
					'f_ttb_cancel'			=> $fttbcancel,
					'd_receive1'			=> $dreceive1,
					'd_entry'				=> $dentry,
					'n_ttb_year'			=> $tahun,
                    'i_ttb_refference'=> $isj
    		)
    	);
    	$this->db->insert('tm_ttbtolak');
		$this->db->query("update tm_nota set f_ttb_tolak = 't' where i_nota='$inota'",false);
    }

    function insertbbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isalesman)
    {
    	$this->db->set(
    		array(
				'i_bbm'					=> $ibbm,
				'i_bbm_type'			=> $ibbmtype,
				'i_refference_document'	=> $ittb,
				'd_refference_document'	=> $dttb,
				'd_bbm'					=> $dbbm,
				'e_remark'				=> $eremark,
				'i_area'				=> $iarea,
				'i_salesman'			=> $isalesman
    		)
    	);
    	
    	$this->db->insert('tm_bbm');
    }

    function insertdetail($iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver,$i,$isj)
    {
    	$this->db->set(
    		array(
					'i_area'    			=> $iarea,
					'i_ttb'			    	=> $ittb,
					'd_ttb'		    		=> $dttb,
					'i_product'		  	=> $iproduct,
					'i_product_grade'	=> $iproductgrade,
					'i_product_motif'	=> $iproductmotif,
					'n_quantity'  		=> $nquantity,
					'n_deliver'		  	=> $ndeliver,
					'v_unit_price'  	=> $vunitprice,
					'e_ttb_remark'  	=> $ettbremark,
					'n_ttb_year'	  	=> $tahun,
          'n_item_no'       => $i,
          'i_ttb_refference'=> $isj
    		)
    	);
    	
    	$this->db->insert('tm_ttbtolak_item');
    }

    function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$ittb,$ibbm,$eremark,$dttb,$ibbmtype,$i)
    {
      $th=substr($dttb,0,4);
      $bl=substr($dttb,5,2);
      $pr=$th.$bl;
    	$this->db->set(
    		array(
				'i_bbm'					=> $ibbm,
				'i_bbm_type'					=> $ibbmtype,
				'i_refference_document'	=> $ittb,
				'i_product'				=> $iproduct,
				'i_product_motif'		=> $iproductmotif,
				'i_product_grade'		=> $iproductgrade,
				'e_product_name'		=> $eproductname,
				'n_quantity'			=> $nquantity,
				'v_unit_price'			=> $vunitprice,
				'e_remark'				=> $eremark,
				'd_refference_document'	=> $dttb,
        'e_mutasi_periode'      => $pr,
        'n_item_no'             => $i
    		)
    	);
    	
    	$this->db->insert('tm_bbm_item');
    }

    function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                from tm_ic_trans
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                order by d_transaction desc",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $query=$this->db->query(" SELECT n_quantity_stock
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				return $query->result();
			}
    }
    function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', $q_in+$qsj, $q_out, $q_ak+$qsj, $q_aw
                                )
                              ",false);
    }
    function inserttrans44($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak,$tra)
    {
      $query 	= $this->db->query("SELECT current_timestamp as c");
	    $row   	= $query->row();
	    $now	  = $row->c;
      $query=$this->db->query(" 
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal,i_trans)
                                VALUES 
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin', 
                                  '$eproductname', '$isj', '$now', $q_in+$qsj, $q_out, $q_ak+$qsj, $q_aw, $tra
                                )
                              ",false);
    }
    function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
    {
      $hasil='kosong';
      $query=$this->db->query(" SELECT i_product
                                from tm_mutasi
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
      if ($query->num_rows() > 0){
				$hasil='ada';
			}
      return $hasil;
    }
    function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi 
                                set n_mutasi_bbm=n_mutasi_bbm+$qsj, n_saldo_akhir=n_saldo_akhir+$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                insert into tm_mutasi 
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,0,0,$qsj,0,0,0,$qsj,0,'f')
                              ",false);
    }
    function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
    {
      $ada=false;
      $query=$this->db->query(" SELECT i_product
                                from tm_ic
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
      if ($query->num_rows() > 0){
				$ada=true;
			}
      return $ada;
    }
    function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=$q_ak+$qsj

                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
    function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver)
    {
      $query=$this->db->query(" 
                                insert into tm_ic 
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', $ndeliver, 't'
                                )
                              ",false);
    }
    function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj)
    {
      $urut=null;
      $queri 		= $this->db->query("SELECT i_trans FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' and i_refference_document='$isj'");
      if($queri->num_rows()>0){
        foreach($queri->result() as $tes){
          $urut=$tes->i_trans;
        }
      }
      $query=$this->db->query(" 
                                DELETE FROM tm_ic_trans 
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation'
                                and i_store_locationbin='$istorelocationbin' and i_refference_document='$isj'
                              ",false);
      return $urut;
    }
    function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
    {
      $query=$this->db->query(" 
                                UPDATE tm_mutasi set n_mutasi_bbm=n_mutasi_bbm-$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }
    function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
    {
      $query=$this->db->query(" 
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
}

/* End of file Mmaster.php */
