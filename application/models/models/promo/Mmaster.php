<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacajenis(){
        return $this->db->order_by('i_promo_type','ASC')->get('tr_promo_type')->result();
    }

    public function bacagroup(){
        $this->db->select('*');
        $this->db->from('tr_product_group');
        $this->db->where('f_spb', 'true');
        $this->db->order_by('e_product_groupname');
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            return $query->result();
        }
    }

    public function getgroup($cari, $kode){
        $cari = str_replace("'", "", $cari);
        if($kode=='2'||$kode=='4'||$kode=='5'||$kode=='6'||$kode==''){
            $this->db->select("
                    DISTINCT(i_price_group) AS i_price_group
                FROM
                    tr_product_price
                WHERE
                    i_price_group LIKE '%$cari%'
                    AND i_price_group NOT IN (
                    SELECT
                        i_price_group
                    FROM
                        tr_price_group)
                ORDER BY
                    i_price_group", 
            false);
        }else{
            $this->db->select("
                    DISTINCT(i_price_group) AS i_price_group
                FROM
                    tr_product_price
                WHERE 
                    i_price_group LIKE '%$cari%'
                ORDER BY
                    i_price_group", 
            false);
        }
        return $this->db->get();
    }

    public function product($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                c.e_product_name AS nama
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')", 
        FALSE);
    }

    public function getproduct($iproduct){
        return $this->db->query("
            SELECT
                a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                c.e_product_name AS nama,
                c.v_product_retail AS harga
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND UPPER(a.i_product) = '$iproduct'", 
        FALSE);
    }

    public function customer($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_customer,
                e_customer_name
            FROM
                tr_customer
            WHERE
                (UPPER(i_customer) LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%')", 
        FALSE);
    }

    public function getcustomer($icustomer){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_customer
            WHERE
                i_customer = '$icustomer'", 
        FALSE);
    }

    public function customergroup($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_customer_group,
                e_customer_groupname
            FROM
                tr_customer_group
            WHERE
                (UPPER(i_customer_group) LIKE '%$cari%'
                OR UPPER(e_customer_groupname) LIKE '%$cari%')", 
        FALSE);
    }

    public function getcustomergroup($icustomergroup){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_customer_group
            WHERE
                i_customer_group = '$icustomergroup'", 
        FALSE);
    }

    public function area($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_area,
                e_area_name
            FROM
                tr_area
            WHERE
                (UPPER(i_area) LIKE '%$cari%'
                OR UPPER(e_area_name) LIKE '%$cari%')", 
        FALSE);
    }

    public function getarea($iarea){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area = '$iarea'", 
        FALSE);
    }

    public function runningnumber(){
        $query  = $this->db->query("SELECT to_char(current_timestamp,'yymm') as c");
        $row    = $query->row();
        $thbl   = $row->c;
        $th     = substr($thbl,0,2);
        $this->db->select(" 
                MAX(substr(i_promo, 9, 5)) AS MAX
            FROM
                tm_promo
            WHERE
                substr(i_promo,
                4,
                2)= '$th' ", 
        false);
        $query = $this->db->get();        
        if($query->num_rows() > 0){            
            foreach($query->result() as $row){              
                $terakhir=$row->max;
            }
            $nopromo  =$terakhir+1;
            settype($nopromo,"string");
            $a=strlen($nopromo);
            while($a<5){              
                $nopromo="0".$nopromo;
                $a=strlen($nopromo);
            }
            $nopromo  ="PR-".$thbl."-".$nopromo;
            return $nopromo;
        }else{
            $nopromo  ="00001";
            $nopromo  ="PR-".$thbl."-".$nopromo;
            return $nopromo;
        }
    }

    public function insertheader($ipromo,$dpromo,$ipromotype,$dpromostart,$dpromofinish,$epromoname,$fallproduct,$fallcustomer,$fcustomergroup,$npromodiscount1,$npromodiscount2,$fallbaby,$fallreguler,$fallarea,$ipricegroup,$fallnb){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_promo'           => $ipromo,
                'd_promo'           => $dpromo,
                'i_promo_type'      => $ipromotype,
                'd_promo_start'     => $dpromostart,
                'd_promo_finish'    => $dpromofinish,
                'e_promo_name'      => $epromoname,
                'f_all_product'     => $fallproduct,
                'f_all_baby'        => $fallbaby,
                'f_all_reguler'     => $fallreguler,
                'f_all_nb'          => $fallnb,
                'f_all_customer'    => $fallcustomer,
                'f_all_area'        => $fallarea,
                'f_customer_group'  => $fcustomergroup,
                'n_promo_discount1' => $npromodiscount1,
                'n_promo_discount2' => $npromodiscount2,
                'i_price_group'     => $ipricegroup,
                'd_entry'           => $dentry
            )
        );
        $this->db->insert('tm_promo');
    }

    public function insertdetailp($ipromo,$ipromotype,$iproduct,$iproductgrade,$eproductname,$nquantitymin,$vunitprice,$iproductmotif){
        $this->db->set(
            array(
                'i_promo'           => $ipromo,
                'i_promo_type'      => $ipromotype,
                'i_product'         => $iproduct,
                'i_product_grade'   => $iproductgrade,
                'i_product_motif'   => $iproductmotif,
                'n_quantity_min'    => $nquantitymin,
                'v_unit_price'      => $vunitprice,
                'e_product_name'    => $eproductname
            )
        );        
        $this->db->insert('tm_promo_item');
    }

    public function areacus($icustomer){
        $this->db->select('i_area');
        $this->db->from('tr_customer');
        $this->db->where('i_customer',$icustomer);
        return $this->db->get();
    }

    public function insertdetailc($ipromo,$ipromotype,$icustomer,$ecustomername,$ecustomeraddress,$iarea){
        $this->db->set(
            array(
                'i_promo'           => $ipromo,
                'i_promo_type'      => $ipromotype,
                'i_customer'        => $icustomer,
                'e_customer_name'   => $ecustomername,
                'e_customer_address'=> $ecustomeraddress,
                'i_area'            => $iarea
            )
        );        
        $this->db->insert('tm_promo_customer');
    }

    public function areacusgroup($icustomergroup){
        $this->db->select('distinct(i_area) as i_area');
        $this->db->from('tr_customer');
        $this->db->where('i_customer_group',$icustomergroup);
        return $this->db->get();
    }

    public function insertdetailg($ipromo,$ipromotype,$icustomergroup,$ecustomergroupname,$iarea){
        $this->db->set(
            array(
                'i_promo'               => $ipromo,
                'i_promo_type'          => $ipromotype,
                'i_customer_group'      => $icustomergroup,
                'e_customer_groupname'  => $ecustomergroupname,
                'i_area'                => $iarea
            )
        );
        $this->db->insert('tm_promo_customergroup');
    }

    public function insertdetaila($ipromo,$ipromotype,$iarea,$eareaname){
        $this->db->set(
            array(
                'i_promo'       => $ipromo,
                'i_promo_type'  => $ipromotype,
                'i_area'        => $iarea,
                'e_area_name'   => $eareaname,
            )
        );        
        $this->db->insert('tm_promo_area');
    }
}

/* End of file Mmaster.php */