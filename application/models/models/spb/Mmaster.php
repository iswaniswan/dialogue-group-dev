<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($ispb, $iarea){
        return $this->db->query("
            SELECT 
                a.e_remark1 AS emark1, 
                a.*, 
                e.e_price_groupname,
                d.e_area_name, 
                b.e_customer_name, 
                b.e_customer_address, 
                c.e_salesman_name, 
                b.f_customer_first
            FROM 
                tm_spb a
                INNER JOIN tr_customer b on (a.i_customer=b.i_customer)
                INNER JOIN tr_salesman c on (a.i_salesman=c.i_salesman)
                INNER JOIN tr_customer_area d on (a.i_customer=d.i_customer)
                LEFT JOIN tr_price_group e on (a.i_price_group=e.i_price_group)
            WHERE
                a.i_spb ='$ispb' 
                AND a.i_area='$iarea'
        ");
    }

    public function bacadetail($ispb, $iarea, $ipricegroup){
        return $this->db->query("
            SELECT 
                a.*, 
                b.e_product_motifname, 
                c.v_product_retail as hrgnew
            FROM
                tm_spb_item a, 
                tr_product_motif b, 
                tr_product_price c
            WHERE
                a.i_spb = '$ispb' 
                AND i_area='$iarea' 
                AND a.i_product=b.i_product 
                AND a.i_product_motif=b.i_product_motif
                AND a.i_product=c.i_product
                AND c.i_price_group='$ipricegroup' 
                AND a.i_product_grade=c.i_product_grade
            ORDER BY
                a.n_item_no
        ");
    }

    public function bacadetailnilaispb($ispb,$iarea,$ipricegroup){
        return $this->db->query(" 
            SELECT
                (sum(a.n_deliver * a.v_unit_price)) AS nilaispb 
            FROM 
                tm_spb_item a
            WHERE 
                a.i_spb = '$ispb' 
                AND a.i_area='$iarea' 
        ");
    }

    public function bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup){
        return $this->db->query(" 
            SELECT 
                (sum(a.n_order * a.v_unit_price)) AS nilaiorderspb 
            FROM 
                tm_spb_item a
            WHERE 
                a.i_spb = '$ispb' 
                AND a.i_area='$iarea' 
        ");
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

    public function getareanya($iarea){
        $this->db->select('*');
        $this->db->from('tr_area');
        $this->db->where('i_area', $iarea);
        return $this->db->get();
    }

    public function getpelanggan($iarea, $cari){
        /*$cari = preg_replace("/[^a-zA-Z0-9]/", "", $cari);*/
        $cari = str_replace("'", "", $cari);
        return  $this->db->query("
            SELECT
                i_customer,
                e_customer_name
            FROM
                tr_customer
            WHERE
                i_area = '$iarea'
                AND (i_customer LIKE '%$cari%' 
                OR UPPER(e_customer_name) LIKE '%$cari%')"
            );
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

    public function getdetailpel($icustomer, $per, $iarea){
        $this->db->select(" *
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
                (a.i_customer = e.i_customer
                AND e.i_product_group = '01'
                AND e.e_periode = '$per')
            LEFT JOIN tr_customer_discount f ON
                (a.i_customer = f.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
                AND f_customer_aktif = 't'
                AND a.f_approve = 't'
            ORDER BY
                a.i_customer",false);
        return $this->db->get();    
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

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }
    
    public function getcustomer($iarea) {
      $this->db->select("i_customer, e_customer_name");
      $this->db->from('tr_customer');
      $this->db->where('i_area', $iarea);
      $this->db->order_by('e_customer_name');
      return $this->db->get();
    }

    public function bacaproduct($cari,$kdharga,$groupbarang) {
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
        SELECT
            a.i_product AS kode,
            c.e_product_name AS nama
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e
        WHERE
            d.i_product_type = c.i_product_type
            AND d.i_product_group = '$groupbarang'
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND c.i_product_status <> '4'
            AND b.i_price_group = '$kdharga'
            AND b.i_product_grade = 'A'
            AND (UPPER(a.i_product) LIKE '%$cari%'
            OR UPPER(c.e_product_name) LIKE '%$cari%') "
        );
    }

    public function bacaproductic($cari,$kdharga,$istore,$groupbarang){
      $cari = str_replace("'", "", $cari);
      return $this->db->query(" 
        SELECT
          a.i_product AS kode,
          c.e_product_name AS nama
      FROM
          tr_product_motif a,
          tr_product_price b,
          tr_product c,
          tr_product_type d,
          tr_product_status e,
          tm_ic f
      WHERE
          d.i_product_type = c.i_product_type
          AND d.i_product_group = '$groupbarang'
          AND b.i_product = a.i_product
          AND a.i_product_motif = '00'
          AND a.i_product = c.i_product
          AND c.i_product_status = e.i_product_status
          AND b.i_price_group = '$kdharga'
          AND b.i_product_grade = 'A'
          AND a.i_product = f.i_product
          AND f.i_store = '$istore'
          AND f.f_product_active = 't'
          AND f.n_quantity_stock>0
          AND b.i_product_grade = f.i_product_grade
          AND (UPPER(a.i_product) LIKE '%$cari%'
          OR UPPER(c.e_product_name) LIKE '%$cari%')");
    }

    public function bacaproductx($kdharga,$group,$iproduct) {
      return $this->db->query("
        SELECT
            a.i_product AS kode,
            a.i_product_motif AS motif,
            a.e_product_motifname AS namamotif,
            c.i_product_status,
            e.e_product_statusname,
            c.e_product_name AS nama,
            b.v_product_retail AS harga
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e
        WHERE
            d.i_product_type = c.i_product_type
            AND d.i_product_group = '$group'
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND c.i_product_status <> '4'
            AND b.i_price_group = '$kdharga'
            AND b.i_product_grade = 'A'
            AND c.i_product = '$iproduct' "
        );
    }

    public function bacaproducticx($kdharga,$istore,$group,$iproduct){
      return $this->db->query(" 
        SELECT
            a.i_product AS kode,
            a.i_product_motif AS motif,
            a.e_product_motifname AS namamotif,
            c.i_product_status,
            e.e_product_statusname,
            c.e_product_name AS nama,
            b.v_product_retail AS harga
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e,
            tm_ic f
        WHERE
            d.i_product_type = c.i_product_type
            AND d.i_product_group = '$group'
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND b.i_price_group = '$kdharga'
            AND b.i_product_grade = 'A'
            AND a.i_product = f.i_product
            AND f.i_store = '$istore'
            AND f.f_product_active = 't'
            AND f.n_quantity_stock>0
            AND b.i_product_grade = f.i_product_grade
            AND c.i_product = '$iproduct' ");
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

    public function insertheader($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment,$ispbold,$eremarkx, $iproductgroup){       
        $dentry  = current_datetime();
        $this->db->set(       
            array(           
                'i_spb'                 => $ispb,           
                'd_spb'                 => $dspb,
                'i_customer'            => $icustomer,
                'i_area'                => $iarea,
                'i_spb_po'              => $ispbpo,
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
                'v_spb_discount1'       => $vspbdiscount1,
                'v_spb_discount2'       => $vspbdiscount2,
                'v_spb_discount3'       => $vspbdiscount3,
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
