<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return '00';
        }else{
            return 'xx';
        }
    }

    public function data($folder,$imenu,$username,$idcompany,$iarea){
        if ($iarea!='00') {
            $sql = "SELECT
                        i_promo,
                        to_char(d_promo, 'dd-mm-yyyy') AS d_promo,
                        to_char(d_promo_start, 'dd-mm-yyyy') || '  s/d  ' || to_char(d_promo_finish, 'dd-mm-yyyy') AS d_periode_promo, 
                        e_promo_name,
                        '$folder' AS folder, 
                        '$imenu' AS imenu,
                        '$iarea' AS iarea
                    FROM
                        (
                        SELECT
                            *
                        FROM
                            tm_promo
                        WHERE
                            f_all_area = 't'
                    UNION ALL
                        SELECT
                            a.*
                        FROM
                            tm_promo a
                        INNER JOIN tm_promo_area b ON
                            (a.i_promo = b.i_promo
                            AND a.i_promo_type = b.i_promo_type
                            AND b.i_area IN (
                            SELECT
                                i_area
                            FROM
                                public.tm_user_area
                            WHERE
                                username = '$username'
                                AND id_company = '$idcompany'))
                        WHERE
                            a.f_all_area = 'f') AS x
                    ORDER BY
                        x.i_promo DESC ";
        }else{
            $sql = "SELECT
                        i_promo,
                        to_char(d_promo, 'dd-mm-yyyy') AS d_promo,
                        to_char(d_promo_start, 'dd-mm-yyyy') || '  s/d  ' || to_char(d_promo_finish, 'dd-mm-yyyy') AS d_periode_promo, 
                        e_promo_name,
                        '$folder' AS folder, 
                        '$imenu' AS imenu,
                        '$iarea' AS iarea 
                    FROM
                        tm_promo a
                    ORDER BY
                        a.i_promo DESC";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("$sql"
            , FALSE);
        $datatables->add('action', function ($data) {
            $ipromo = trim($data['i_promo']);
            $imenu  = $data['imenu'];
            $iarea  = $data['iarea'];
            $folder = $data['folder'];
            $data   = '';
            if(check_role($imenu, 2)||check_role($imenu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ipromo\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($imenu, 4)){
                if($iarea == '00'){
                    $data .= "<a href=\"#\" onclick='cancel(\"$ipromo\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                }
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('imenu');
        $datatables->hide('iarea');
        return $datatables->generate();
    }

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

    public function updateheader($ipromo,$dpromo,$ipromotype,$dpromostart,$dpromofinish,$epromoname,$fallproduct,$fallcustomer,$fcustomergroup,$npromodiscount1,$npromodiscount2,$fallbaby,$fallreguler,$fallarea,$ipricegroup){
        $this->db->set(
            array(
                'd_promo'           => $dpromo,
                'i_promo_type'      => $ipromotype,
                'd_promo_start'     => $dpromostart,
                'd_promo_finish'    => $dpromofinish,
                'e_promo_name'      => $epromoname,
                'f_all_product'     => $fallproduct,
                'f_all_baby'        => $fallbaby,
                'f_all_reguler'     => $fallreguler,
                'f_all_customer'    => $fallcustomer,
                'f_all_area'        => $fallarea,
                'f_customer_group'  => $fcustomergroup,
                'n_promo_discount1' => $npromodiscount1,
                'n_promo_discount2' => $npromodiscount2,
                'i_price_group'     => $ipricegroup
            )
        );
        $this->db->where('i_promo',$ipromo);    
        $this->db->update('tm_promo');
    }

    public function deletedetailp($ipromo,$iproduct,$iproductgrade,$iproductmotif){
        $this->db->where('i_promo', $ipromo);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_product_grade', $iproductgrade);
        $this->db->where('i_product_motif', $iproductmotif);
        return $this->db->delete('tm_promo_item');
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

    public function deletedetailc($ipromo,$icustomer){
        $this->db->where('i_promo', $ipromo);
        $this->db->where('i_customer', $icustomer);
        return $this->db->delete('tm_promo_customer');
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

    public function deletedetailg($ipromo,$icustomergroup){
        $this->db->where('i_promo', $ipromo);
        $this->db->where('i_customer_group', $icustomergroup);
        return $this->db->delete('tm_promo_customergroup');
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

    public function deletedetaila($ipromo,$iarea){
        $this->db->where('i_promo', $ipromo);
        $this->db->where('i_area', $iarea);
        return $this->db->delete('tm_promo_area');
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

    public function delete($ipromo){
        $this->db->where('i_promo', $ipromo);
        $this->db->delete('tm_promo');
        $this->db->where('i_promo', $ipromo);
        $this->db->delete('tm_promo_item');
        $this->db->where('i_promo', $ipromo);
        $this->db->delete('tm_promo_customer');
        $this->db->where('i_promo', $ipromo);
        $this->db->delete('tm_promo_customergroup');
        $this->db->where('i_promo', $ipromo);
        return $this->db->delete('tm_promo_area');
    }

    public function jmlitemp($ipromo){
        $this->db->select('*');
        $this->db->from('tm_promo_item');
        $this->db->where('i_promo', $ipromo);
        return $this->db->get();
    }

    public function jmlitemc($ipromo){
        $this->db->select('*');
        $this->db->from('tm_promo_customer');
        $this->db->where('i_promo', $ipromo);
        return $this->db->get();
    }

    public function jmlitemg($ipromo){
        $this->db->select('i_customer_group');
        $this->db->distinct();
        $this->db->from('tm_promo_customergroup');
        $this->db->where('i_promo', $ipromo);
        return $this->db->get();
    }

    public function jmlitema($ipromo){
        $this->db->select('*');
        $this->db->from('tm_promo_area');
        $this->db->where('i_promo', $ipromo);
        return $this->db->get();
    }

    public function baca($ipromo){
        $this->db->select(" i_promo, i_promo_type, e_promo_name, d_promo, d_promo_start, d_promo_finish, n_promo_discount1, n_promo_discount2, f_all_product, f_all_customer, f_customer_group, f_all_baby, f_all_reguler, f_all_area, i_price_group, f_all_nb 
            from tm_promo 
            where i_promo ='$ipromo'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }
    
    public function bacadetailp($ipromo){
        $this->db->select(" a.i_promo, a.i_promo_type, a.i_product, a.i_product_grade, a.i_product_motif, a.e_product_name, a.v_unit_price, a.n_quantity_min, b.e_product_motifname 
            from tm_promo_item a, tr_product_motif b
            where a.i_promo = '$ipromo' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
            order by a.i_product", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetailc($ipromo){
        $this->db->select(" i_promo, i_promo_type, i_customer, e_customer_name, e_customer_address, i_area
            from tm_promo_customer 
            where i_promo = '$ipromo' 
            order by i_customer", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetailg($ipromo){
        $this->db->select(" distinct(i_customer_group) as i_customer_group, e_customer_groupname, i_promo
            from tm_promo_customergroup 
            where i_promo = '$ipromo' 
            order by i_customer_group", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetaila($ipromo){
        $this->db->select(" i_promo, i_promo_type, i_area, e_area_name 
            from tm_promo_area 
            where i_promo = '$ipromo' order by i_area", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */