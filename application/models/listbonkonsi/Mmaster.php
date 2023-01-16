<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getarea(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT *
            FROM public.tm_user_area
            WHERE username = '$username'
            AND id_company = '$id_company'
            AND i_area IN ('PB','00')
            ", FALSE);
        if ($query->num_rows()>0) {
            $key = $query->row();
            $iarea = $key->i_area;
            return 'PB';
        }else{
            return 'xx';
        }
    }

    public function customer($cari,$iarea){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        if ($iarea!='xx') {
            return $this->db->query("
                SELECT
                    a.*,
                    b.e_customer_name
                FROM
                    tr_spg a,
                    tr_customer b
                WHERE
                    a.i_customer = b.i_customer
                    AND (UPPER(a.i_customer)LIKE '%$cari%'
                    OR UPPER(b.e_customer_name)LIKE '%$cari%')
                ORDER BY
                    a.i_customer
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    a.*,
                    b.e_customer_name
                FROM
                    tr_spg a,
                    tr_customer b
                WHERE
                    a.i_customer = b.i_customer
                    AND a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username')
                        AND id_company = '$id_company')
                    AND (UPPER(a.i_customer)LIKE '%$cari%'
                    OR UPPER(b.e_customer_name)LIKE '%$cari%')
                ORDER BY
                    a.i_customer
            ", FALSE);
        }
    }

    public function data($folder,$imenu,$dfrom,$dto,$icustomer){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_notapb,
                a.i_customer,
                to_char(d_notapb, 'dd-mm-yyyy') AS d_notapb, 
                a.i_customer||' - '||e_customer_name AS customer,
                a.i_spg||' - '||b.e_spg_name AS spg,
                CASE WHEN f_notapb_cancel = 't' THEN 'Ya' ELSE 'Tidak' END AS status,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$imenu' AS imenu
            FROM
                tm_notapb a,
                tr_spg b,
                tr_customer c
            WHERE
                a.i_spg = b.i_spg
                AND a.i_customer = c.i_customer
                AND b.i_customer = '$icustomer'
                AND a.d_notapb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_notapb <= TO_DATE('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.i_notapb"
            , FALSE);
        $datatables->add('action', function ($data) {
            $inotapb   = trim($data['i_notapb']);
            $dnotapb   = $data['d_notapb'];
            $imenu     = $data['imenu'];
            $dfrom     = $data['dfrom'];
            $dto       = $data['dto'];
            $icustomer = $data['i_customer'];
            $folder    = $data['folder'];
            $data      = '';
            if(check_role($imenu, 2)||check_role($imenu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$inotapb/$icustomer/$dfrom/$dto/$dnotapb\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('i_customer');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('folder');
        $datatables->hide('imenu');
        return $datatables->generate();
    }

    public function periode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        return $this->db->get();
    }

	public function baca ($ispg,$iarea){
		$this->db->select("a.i_customer, a.i_area, a.i_spg, a.e_spg_name, b.e_area_name, c.e_customer_name");
		$this->db->from("tr_spg a");
		$this->db->join("tr_area b","a.i_area=b.i_area");
		$this->db->join("tr_customer c","a.i_customer=c.i_customer");
		$this->db->where("upper(a.i_spg) = '$ispg'");
		$this->db->where("a.i_area='$iarea'");
		return $this->db->get();
	}

    public function bacaisi($inotapb,$icustomer){
        $this->db->select("a.*, b.e_area_name, c.e_customer_name, d.e_spg_name from tm_notapb a, tr_area b, tr_customer c, tr_spg d
            where a.i_area=b.i_area and a.i_customer=c.i_customer and a.i_spg=d.i_spg
            and a.i_notapb ='$inotapb' and a.i_customer='$icustomer'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

	public function bacadetail($inotapb,$icustomer){
		$this->db->select(" a.*, b.e_product_motifname from tm_notapb_item a, tr_product_motif b
					 		where a.i_notapb = '$inotapb' and a.i_customer='$icustomer' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
					 		order by a.n_item_no ", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}

    public function batalcek($inotapb,$icustomer){
        $this->db->set(
            array(
            'i_cek'  => null,
            'd_cek'  => null,
            )
        );
        $this->db->where('i_notapb',$inotapb);
        $this->db->where('i_customer',$icustomer);
        $this->db->where('f_spb_rekap','f');
        return $this->db->update('tm_notapb');
    }

    public function getbarang($cari,$icustomer){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                b.i_product,
                b.i_price_group,
                a.i_product_motif,
                a.e_product_motifname,
                b.v_product_retail,
                c.e_product_name
            FROM
                tr_product_motif a,
                tr_product c,
                tr_product_priceco b,
                tr_customer_consigment d
            WHERE
                a.i_product = c.i_product
                AND a.i_product = b.i_product
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')
                AND d.i_customer = '$icustomer'
                AND d.i_price_groupco = b.i_price_groupco
                AND a.i_product_motif = '00'
            ORDER BY
                c.i_product,
                a.e_product_motifname,
                b.i_price_group
        ",false);
    }

    public function caribarang($iproduct,$icustomer){
        return $this->db->query("
            SELECT
                b.i_product,
                b.i_price_groupco,
                a.i_product_motif,
                a.e_product_motifname,
                b.v_product_retail,
                c.e_product_name
            FROM
                tr_product_motif a,
                tr_product c,
                tr_product_priceco b,
                tr_customer_consigment d
            WHERE
                a.i_product = c.i_product
                AND a.i_product = b.i_product
                AND d.i_customer = '$icustomer'
                AND a.i_product = '$iproduct'
                AND d.i_price_groupco = b.i_price_groupco
                AND a.i_product_motif = '00'
            ORDER BY
                c.i_product,
                a.e_product_motifname,
                b.i_price_group
        ", FALSE);
    }

    public function deleteheader($xinotapb, $iarea, $icustomer) {
        $this->db->query("DELETE FROM tm_notapb WHERE i_notapb='$xinotapb' and i_area='$iarea' and i_customer='$icustomer'");
    }
	
	public function insertheader($inotapb,$dnotapb, $iarea, $ispg, $icustomer, $nnotapbdiscount, $vnotapbdiscount, $vnotapbgross){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_notapb'          => $inotapb,
                'i_area'            => $iarea,
                'i_spg'             => $ispg,
                'i_customer'        => $icustomer,
                'd_notapb'          => $dnotapb,
                'n_notapb_discount' => $nnotapbdiscount,
                'v_notapb_discount' => $vnotapbdiscount,
                'v_notapb_gross'    => $vnotapbgross,
                'f_notapb_cancel'   => 'f',
                'd_notapb_entry'    => $dentry
            )
        );
        $this->db->insert('tm_notapb');
    }

    public function insertdetail($inotapb,$iarea,$icustomer,$dnotapb,$iproduct,$iproductmotif,$iproductgrade,$nquantity,$vunitprice,$i,$eproductname,$ipricegroupco,$eremark){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_notapb'        => $inotapb,
                'i_area'          => $iarea,
                'i_customer'      => $icustomer,
                'd_notapb'        => $dnotapb,
                'i_product'       => $iproduct,
                'i_product_motif' => $iproductmotif,
                'i_product_grade' => $iproductgrade,
                'n_quantity'      => $nquantity,
                'v_unit_price'    => $vunitprice,
                'd_notapb_entry'  => $dentry,
                'n_item_no'       => $i,
                'e_product_name'  => $eproductname,
                'i_price_groupco' => $ipricegroupco,
                'e_remark'        => $eremark
            )
        );        
        $this->db->insert('tm_notapb_item');
    }

    public function deletedetail($iproduct, $iproductgrade, $inotapb, $iarea, $icustomer, $iproductmotif, $vunitprice) {
        $this->db->query("DELETE FROM tm_notapb_item WHERE i_notapb='$inotapb' and i_product='$iproduct' and i_product_grade='$iproductgrade' 
            and i_product_motif='$iproductmotif' and i_customer='$icustomer' and v_unit_price=$vunitprice");
    }
}

/* End of file Mmaster.php */
