<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

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

    public function bacapromo($username, $idcompany, $dspb){
        $this->db->select("
                *
            FROM
                (
                SELECT
                    DISTINCT(i_promo) AS tes,
                    *
                FROM
                    tm_promo
                WHERE
                    f_all_area = 't'
                    AND d_promo_start <= '$dspb'
                    AND d_promo_finish >= '$dspb'
                    AND f_all_reguler = 'f'
            UNION ALL
                SELECT
                    DISTINCT(a.i_promo) AS tes,
                    a.*
                FROM
                    tm_promo a
                INNER JOIN tm_promo_area b ON
                    (a.i_promo = b.i_promo
                    AND b.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany'))
                WHERE
                    a.f_all_area = 'f'
                    AND d_promo_start <= '$dspb'
                    AND d_promo_finish >= '$dspb'
                    AND f_all_reguler = 'f' ) AS x
            ORDER BY
                x.i_promo", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            return $query->result();
        }
    }

    public function egroup($igroup){
        $this->db->select('*');
        $this->db->from('tr_product_group');
        $this->db->where('f_spb', 'true');
        $this->db->where('i_product_group', $igroup);
        $this->db->order_by('e_product_groupname');
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            $e = $query->row();
            $egroup = $e->e_product_groupname;
        }
        return $egroup;
    }

    public function getpromo($ipromo){
        $this->db->select('*');
        $this->db->from('tm_promo');
        $this->db->where('i_promo', $ipromo);
        return $this->db->get();
    }

    public function cariarea($ipromo, $a){
        $username = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        if($a=='f'){   
            $this->db->select(" 
                DISTINCT(a.i_area) AS i_area,
                    b.*
                FROM
                    tm_promo_area a,
                    tr_area b
                WHERE
                    a.i_promo = '$ipromo'
                    AND a.i_area = b.i_area
                    AND a.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$id_company')
                ORDER BY
                    a.i_area", false
            );
        }else{
            $this->db->select("
                    *
                FROM
                    tr_area
                WHERE
                    i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$id_company') ",false);
        }
        return $query = $this->db->get();
    }

    public function getareanya($iarea){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->where('i_area', $iarea);
        return $this->db->get();
    }

    public function getpelanggan($cari, $iarea, $promo, $c, $g, $type, $per){
        $cari = str_replace("'", "", $cari);
        if(($c=='f') && ($g=='f')){           
            $query = $this->db->query("
                SELECT DISTINCT
                    x.i_customer, x.e_customer_name
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customer y
                INNER JOIN tm_promo yy ON
                    (y.i_promo = yy.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND (UPPER(x.i_customer) LIKE '%$cari%'
                    OR UPPER(x.e_customer_name) LIKE '%$cari%')
                    AND x.i_customer = y.i_customer
                    AND x.i_area = '$iarea' "
            , false);              
        }else if(($c=='t') && ($g=='f')){           
            $query = $this->db->query("
                SELECT DISTINCT
                    x.i_customer, x.e_customer_name
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer)
                WHERE
                    x.i_area = '$iarea'
                    AND x.f_approve = 't'
                    AND (UPPER(x.i_customer) LIKE '%$cari%'
                    OR UPPER(x.e_customer_name) LIKE '%$cari%')"
            , false);       
        }else if(($c=='f') && ($g=='t')){           
            $query = $this->db->query("
                SELECT DISTINCT
                    x.i_customer, x.e_customer_name
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customergroup y
                INNER JOIN tm_promo p ON
                    (y.i_promo = p.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND (UPPER(x.i_customer) LIKE '%$cari%'
                    OR UPPER(x.e_customer_name) LIKE '%$cari%')
                    AND x.i_customer_group = y.i_customer_group
                    AND x.i_area = y.i_area
                    AND x.i_area = '$iarea' 
            ", false);       
        }
        return $query;   
    }

    public function getsales($iarea, $cari, $per){
        $cari = str_replace("'", "", $cari);
        return  $this->db->query("
            SELECT DISTINCT 
                a.i_salesman,
                a.e_salesman_name
            FROM
                tr_customer_salesman a,
                tr_salesman b
            WHERE
                (UPPER(a.e_salesman_name) LIKE '%$cari%'
                OR UPPER(a.i_salesman) LIKE '%$cari%')
                AND a.i_area = '$iarea'
                AND a.i_salesman = b.i_salesman
                AND b.f_salesman_aktif = 'true'
                AND a.e_periode = '$per' "
            );
    }

    public function getdetailpel($icustomer, $iarea, $promo, $c, $g, $type, $per, $disc1, $disc2){
        if(($c=='f') && ($g=='f')){           
            $query = $this->db->query("
                SELECT
                    *, 
                    $type AS type,
                    $disc1 AS disc1,
                    $disc2 AS disc2
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customer y
                INNER JOIN tm_promo yy ON
                    (y.i_promo = yy.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND x.i_customer = y.i_customer
                    AND x.i_area = '$iarea' 
                    AND x.i_customer = '$icustomer'
            ", false);              
        }else if(($c=='t') && ($g=='f')){           
            $query = $this->db->query("
                SELECT
                    *, 
                    $type AS type,
                    $disc1 AS disc1,
                    $disc2 AS disc2
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer)
                WHERE
                    x.i_area = '$iarea'
                    AND x.f_approve = 't'
                    AND x.i_customer = '$icustomer'
            ", false);       
        }else if(($c=='f') && ($g=='t')){           
            $query = $this->db->query("
                SELECT
                    *, 
                    $type AS type,
                    $disc1 AS disc1,
                    $disc2 AS disc2
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customergroup y
                INNER JOIN tm_promo p ON
                    (y.i_promo = p.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND x.i_customer_group = y.i_customer_group
                    AND x.i_area = y.i_area
                    AND x.i_area = '$iarea'
                    AND x.i_customer = '$icustomer' 
            ", false);       
        }
        return $query; 
    }

    public function getdetailsal($isalesman, $per, $iarea){
        return  $this->db->query("
            SELECT DISTINCT 
                a.i_salesman,
                a.e_salesman_name
            FROM
                tr_customer_salesman a,
                tr_salesman b
            WHERE
                a.i_salesman = '$isalesman'
                AND a.i_area = '$iarea'
                AND a.i_salesman = b.i_salesman
                AND b.f_salesman_aktif = 'true'
                AND a.e_periode = '$per' "
            );
    }

    public function bacapelanggan(){
      $this->db->select('*');
      $this->db->from('tr_customer');
      return $this->db->get();
    }

    public function getcustomer($iarea) {
      $this->db->select("i_customer, e_customer_name");
      $this->db->from('tr_customer');
      $this->db->where('i_area', $iarea);
      $this->db->order_by('e_customer_name');
      return $this->db->get();
    }

    public function bacaproduct($cari,$kdharga,$groupbarang,$ipromo,$kdgroup) {
        $this->db->select("*");
        $this->db->from("tm_promo");
        $this->db->where("i_promo",$ipromo);
        $query = $this->db->get();      
        foreach($query->result() as $pro){         
            $p    = $pro->f_all_product;         
            $b    = $pro->f_all_baby;         
            $r    = $pro->f_all_reguler;         
            $tipe = $pro->i_promo_type;      
        }
        $cari = str_replace("'", "", $cari);      
        if($p=='f'){         
            if( ($tipe=='1') || ($tipe=='3')){            
                if($b=='t'){               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND (UPPER(c.i_product) LIKE '%$cari%'
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product 
                        " ,false);               
                    }else{
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND c.i_product_status = g.i_product_status
                                AND (UPPER(c.i_product) LIKE '%$cari%' 
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product" ,false);
                    }            
                }else{               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND (UPPER(c.i_product) LIKE '%$cari%' 
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }else{                            
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND c.i_product_status = g.i_product_status
                                AND e.i_product_group = f.i_product_group
                                AND (UPPER(c.i_product) LIKE '%$cari%' 
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }            
                }         
            }else{            
                if($kdgroup!='G0031'){
                    $query = $this->db->query("                        
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND e.i_product_group = f.i_product_group
                            AND f.i_product_group = '$groupbarang'
                            AND a.i_product_motif = '00'
                            AND (UPPER(c.i_product) LIKE '%$cari%' 
                            OR UPPER(c.e_product_name) LIKE '%$cari%')
                            AND a.i_product = c.i_product
                            AND c.i_product_status = g.i_product_status
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }else{
                    $query = $this->db->query("
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND a.i_product_motif = '00'
                            AND e.i_product_group = f.i_product_group
                            AND c.i_product_status = g.i_product_status
                            AND (UPPER(c.i_product) LIKE '%$cari%' 
                            OR UPPER(c.e_product_name) LIKE '%$cari%')
                            AND a.i_product = c.i_product
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }         
            }      
        }else{         
            $query = $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.i_product_status,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga,
                    g.e_product_statusname,
                    '1' AS n_quantity_min
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c,
                    tr_product_status g
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND c.i_product_status = g.i_product_status
                    AND (UPPER(b.i_product) LIKE '%$cari%' 
                    OR UPPER(b.e_product_name) LIKE '%$cari%')
                    AND b.i_price_group = '$kdharga'
                ORDER BY
                    a.i_product
            ",false);
        }
        return $query;    
    }

    public function bacaproductx($kdharga,$iproduct,$ipromo,$kdgroup,$groupbarang) {
        $this->db->select("*");
        $this->db->from("tm_promo");
        $this->db->where("i_promo",$ipromo);
        $query = $this->db->get();      
        foreach($query->result() as $pro){         
            $p    = $pro->f_all_product;         
            $b    = $pro->f_all_baby;         
            $r    = $pro->f_all_reguler;         
            $tipe = $pro->i_promo_type;      
        }     
        if($p=='f'){         
            if( ($tipe=='1') || ($tipe=='3')){            
                if($b=='t'){               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product 
                        " ,false);               
                    }else{
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND c.i_product_status = g.i_product_status
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product" ,false);
                    }            
                }else{               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }else{                            
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND c.i_product_status = g.i_product_status
                                AND e.i_product_group = f.i_product_group
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }            
                }         
            }else{            
                if($kdgroup!='G0031'){
                    $query = $this->db->query("                        
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND e.i_product_group = f.i_product_group
                            AND f.i_product_group = '$groupbarang'
                            AND a.i_product_motif = '00'
                            AND c.i_product = '$iproduct'
                            AND a.i_product = c.i_product
                            AND c.i_product_status = g.i_product_status
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }else{
                    $query = $this->db->query("
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND a.i_product_motif = '00'
                            AND e.i_product_group = f.i_product_group
                            AND c.i_product_status = g.i_product_status
                            AND c.i_product = '$iproduct'
                            AND a.i_product = c.i_product
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }         
            }      
        }else{         
            $query = $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.i_product_status,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga,
                    g.e_product_statusname,
                    '1' AS n_quantity_min
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c,
                    tr_product_status g
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND c.i_product_status = g.i_product_status
                    AND c.i_product = '$iproduct'
                    AND b.i_price_group = '$kdharga'
                ORDER BY
                    a.i_product
            ",false);
        }
        return $query;
    }

    public function runningnumber($iarea,$thbl){      
        $th     = substr($thbl,0,4);      
        $asal   = $thbl;      
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'SPB'
                AND substr(e_periode, 1, 4)= '$th'
                AND i_area = '$iarea' FOR
            UPDATE", false);
        $query = $this->db->get();        
        if ($query->num_rows() > 0){           
            foreach($query->result() as $row){             
                $terakhir=$row->max;           
            }           
            $nospb  =$terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nospb
                WHERE
                    i_modul = 'SPB'
                    AND substr(e_periode, 1, 4)= '$th'
                    AND i_area = '$iarea' ", false);            
            settype($nospb,"string");            
            $a=strlen($nospb);            
            while($a<6){               
                $nospb="0".$nospb;               
                $a=strlen($nospb);           
            }           
            $nospb  ="SPB-".$thbl."-".$nospb;           
            return $nospb;       
        }else{         
            $nospb  ="000001";         
            $nospb  ="SPB-".$thbl."-".$nospb;         
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('SPB',
                '$iarea',
                '$asal',
                1) ");         
            return $nospb;     
        } 
    }

    public function insertheader($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $nspbdiscount4, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscount4, $vspbdiscounttotal, $vspb, $fspbconsigment, $ispbprogram, $ispbold, $eremarkx, $iproductgroup){       
        $dentry  = current_datetime();     
        $this->db->set(       
            array(           
                'i_spb'                 => $ispb,           
                'd_spb'                 => $dspb,
                'i_customer'            => $icustomer,
                'i_area'                => $iarea,
                'i_spb_po'              => $ispbpo,
                'i_spb_program'         => $ispbprogram,
                'n_spb_toplength'       => $nspbtoplength,
                'i_salesman'            => $isalesman,
                'i_price_group'         => $ipricegroup,
                'd_spb_receive'         => $dspb,
                'f_spb_op'              => $fspbop,
                'e_customer_pkpnpwp'    => $ecustomerpkpnpwp,
                'f_spb_pkp'             => $fspbpkp,
                'f_spb_plusppn'         => $fspbplusppn,
                'f_spb_plusdiscount'    => $fspbplusdiscount,
                'f_spb_stockdaerah'     => $fspbstockdaerah,
                'f_spb_program'         => $fspbprogram,
                'f_spb_valid'           => $fspbvalid,
                'f_spb_siapnotagudang'  => $fspbsiapnotagudang,
                'f_spb_cancel'          => $fspbcancel,
                'n_spb_discount1'       => $nspbdiscount1,
                'n_spb_discount2'       => $nspbdiscount2,
                'n_spb_discount3'       => $nspbdiscount3,
                'n_spb_discount4'       => $nspbdiscount4,
                'v_spb_discount1'       => $vspbdiscount1,
                'v_spb_discount2'       => $vspbdiscount2,
                'v_spb_discount3'       => $vspbdiscount3,
                'v_spb_discount4'       => $vspbdiscount4,
                'v_spb_discounttotal'   => $vspbdiscounttotal,
                'v_spb'                 => $vspb,
                'f_spb_consigment'      => $fspbconsigment,
                'd_spb_entry'           => $dentry,
                'i_spb_old'             => $ispbold,
                'i_product_group'       => $iproductgroup,
                'e_remark1'             => $eremarkx
            )
        );
        $this->db->insert('tm_spb');
    }

    public function insertdetail($ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i){      
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->set(         
            array(               
                'i_spb'           => $ispb,
                'i_area'          => $iarea,
                'i_product'       => $iproduct,
                'i_product_status'=> $iproductstatus,
                'i_product_grade' => $iproductgrade,
                'i_product_motif' => $iproductmotif,
                'n_order'         => $norder,
                'n_deliver'       => $ndeliver,
                'v_unit_price'    => $vunitprice,
                'e_product_name'  => $eproductname,
                'e_remark'        => $eremark,
                'n_item_no'       => $i
            )
        );
        $this->db->insert('tm_spb_item');
    }
}

/* End of file Mmaster.php */
