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
        $this->db->from('tr_customer_group');
        $this->db->order_by('i_customer_group');
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            return $query->result();
        }
    }

    public function bacagudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', 'GD10002');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
  }

    public function getcust($groupcustomer, $iarea){
        if($groupcustomer != 1){
            $this->db->select("i_customer, i_branch, e_branch_name from tr_branch where i_code != '1' order by i_branch", false);
        }else{
            $this->db->select("i_customer, i_branch, e_branch_name");
            $this->db->from('tr_branch');
            $this->db->where('i_code', $iarea);
            $this->db->order_by('i_branch');
        }
        return $this->db->get();

      }
      public function getcust2(){
        $this->db->select("i_branch, e_branch_name, ");
        $this->db->from('tr_branch');
        $this->db->order_by('i_branch');
        return $this->db->get();
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
                tr_branch
            WHERE
                i_code = '$iarea'
                AND i_customer LIKE '%$cari%' ESCAPE '!'
                OR UPPER(e_customer_name) LIKE '%$cari%' ESCAPE '!' "
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

    public function getdetailpel($icustomer, $per){
        // $this->db->select(" *
        //     FROM
        //         tr_customer a
        //     LEFT JOIN tr_customer_pkp b ON
        //         (a.i_customer = b.i_customer)
        //     LEFT JOIN tr_price_group c ON
        //         (a.i_price_group = c.n_line
        //         OR a.i_price_group = c.i_price_group)
        //     LEFT JOIN tr_customer_area d ON
        //         (a.i_customer = d.i_customer)
        //     LEFT JOIN tr_customer_salesman e ON
        //         (a.i_customer = e.i_customer
        //         AND e.i_product_group = '01'
        //         AND e.e_periode = '$per')
        //     LEFT JOIN tr_customer_discount f ON
        //         (a.i_customer = f.i_customer)
        //     WHERE
        //         a.i_area = '$iarea'
        //         AND a.i_customer = '$icustomer'
        //         AND f_customer_aktif = 't'
        //         AND a.f_approve = 't'
        //     ORDER BY
        //         a.i_customer",false);
        $this->db->select(" * from tr_customer where i_customer = '$icustomer'",false);

        // select * from tr_material a
        // join tm_kelompok_barang b on a.i_kode_kelompok=b.i_kode_kelompok
        // where b.i_kode_master='GD10002'
        // ("a.*, c.n_customer_discount1, c.n_customer_discount2, c.n_customer_discount3, b.e_salesman_name, b.i_salesman
        //     from tr_branch a
        //     left join tr_customer_salesman b on (a.i_customer = b.i_customer and a.i_code = b.i_area)
        //     left join tr_customer_discount c on (a.i_customer = c.i_customer)
        //     where a.i_branch = '$icustomer' and b.e_periode = '$per'
        //     order by i_customer",false);
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
        return $this->db->get()->result();
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

    public function runningnumber($thbl){      
        $th     = substr($thbl,0,4);      
        $asal   = $thbl;      
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'MA'
                AND substr(e_periode, 1, 4)= '$th'FOR
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
                    i_modul = 'MA'
                    AND substr(e_periode, 1, 4)= '$th'
                     ", false);            
            settype($nospb,"string");            
            $a=strlen($nospb);            
            while($a<6){               
                $nospb="0".$nospb;               
                $a=strlen($nospb);           
            }           
            $nospb  ="MA-".$thbl."-".$nospb;           
            return $nospb;       
        }else{         
            $nospb  ="000001";         
            $nospb  ="MA-".$thbl."-".$nospb;         
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('MA',
                '00',
                '$asal',
                1) ");         
            return $nospb;     
        } 
    }

    public function insertheader($ispb, $dspb, $icustomer, $ndisc, $vspbdiscounttotal, $vspbbersih, $vspb){
        $dentry  = current_datetime();
        $this->db->set(       
            array(                       
                'i_op_code'             =>$ispb,
                'i_customer'            =>$icustomer,
                'd_op'                  =>$dspb,    
                'n_customer_discount'   =>$ndisc,  
                'v_discount_total'      =>$vspbdiscounttotal,
                'v_total_gross'         =>$vspb,
                'v_total_netto'         =>$vspbbersih,
                'd_entry'               =>$dentry
            )
        );
        $this->db->insert('tm_opaksesoris');
    }

    public function //insertdetail($ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i){      
        insertdetail($ispb, $iproduct, $eproductname, $norder, $eremark, $i, $vprice){
        $dentry  = current_datetime();
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->set(         
            array(               
                'i_op_code'     => $ispb,
                'i_product'     => $iproduct, 
                'e_product_name'=> $eproductname, 
                'e_remark'      => $eremark, 
                'd_entry'       => $dentry, 
                'n_order'       => $norder, 
                'v_price'       => $vprice, 
                'n_delivery'    => '0',
                'n_item_no'     => $i 
            )
        );
        $this->db->insert('tm_op_itemaksesoris');
    }
}

/* End of file Mmaster.php */
