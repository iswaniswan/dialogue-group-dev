<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
      return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacaretensi(){
      return $this->db->order_by('i_retensi','ASC')->get('tr_retensi')->result();
    }

    public function bacashop(){
      return $this->db->order_by('i_shop_status','ASC')->get('tr_shop_status')->result();
    }

    public function bacastatus(){
      return $this->db->order_by('i_marriage','ASC')->get('tr_marriage')->result();
    }

    public function bacakelamin(){
      return $this->db->order_by('i_jeniskelamin','ASC')->get('tr_jeniskelamin')->result();
    }

    public function bacaagama(){
      return $this->db->order_by('i_religion','ASC')->get('tr_religion')->result();
    }

    public function bacatraversed(){
      return $this->db->order_by('i_traversed','ASC')->get('tr_traversed')->result();
    }

    public function bacaclass(){
      return $this->db->order_by('i_customer_class','ASC')->get('tr_customer_class')->result();
    }

    public function bacapayment(){
      return $this->db->order_by('i_paymentmethod','ASC')->get('tr_paymentmethod')->result();
    }

    public function bacacall(){
      return $this->db->order_by('i_call','ASC')->get('tr_call')->result();
    }

    public function bacacustomergroup(){
      return $this->db->order_by('i_customer_group','ASC')->get('tr_customer_group')->result();
    }

    public function bacaplugroup(){
      return $this->db->order_by('i_customer_plugroup','ASC')->get('tr_customer_plugroup')->result();
    }

    public function bacacustomertype(){
      return $this->db->order_by('i_customer_producttype','ASC')->get('tr_customer_producttype')->result();
    }

    public function bacacustomerstatus(){
      return $this->db->order_by('i_customer_status','ASC')->get('tr_customer_status')->result();
    }

    public function bacacustomergrade(){
      return $this->db->order_by('i_customer_grade','ASC')->get('tr_customer_grade')->result();
    }

    public function bacacustomerservice(){
      return $this->db->order_by('i_customer_service','ASC')->get('tr_customer_service')->result();
    }

    public function bacacustomersalestype(){
      return $this->db->order_by('i_customer_salestype','ASC')->get('tr_customer_salestype')->result();
    }

    public function bacapricegroup(){
      return $this->db->order_by('i_price_group','ASC')->get('tr_price_group')->result();
    }

    public function getkota($iarea,$cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_city,
                e_city_name
            FROM
                tr_city
            WHERE
                (UPPER(i_city) LIKE '%$cari%'
                OR UPPER(e_city_name) LIKE '%$cari%')
                AND i_area = '$iarea'
            ORDER BY
                i_city", 
        FALSE);
    }

    public function getsalesman($iarea,$cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                *
            FROM
                tr_salesman
            WHERE
                (UPPER(i_salesman) LIKE '%$cari%'
                OR UPPER(e_salesman_name) LIKE '%$cari%')
                AND f_salesman_aktif = 'true'
            ORDER BY
                i_salesman", 
        FALSE);
    }

    public function getcustomerspecialproduct($iproducttype) {
        $this->db->select("
                *
            FROM
                tr_customer_specialproduct
            WHERE
                i_customer_producttype = '$iproducttype'
            ORDER BY
                i_customer_specialproduct ",false);
        return $this->db->get();
    }

    public function bacaproduct($cari,$kdharga) {      
        $cari = str_replace("'", "", $cari);      
        return $this->db->query("
            SELECT
                DISTINCT a.i_product AS kode,
                c.e_product_name AS nama
            FROM
                tr_product_motif a,
                tr_product_price b,
                tr_product c,
                tr_product_type d,
                tr_product_status e
            WHERE
                b.i_product = a.i_product
                AND c.i_product_status = e.i_product_status
                AND a.i_product = c.i_product
                AND d.i_product_type = c.i_product_type
                AND b.i_price_group = '$kdharga'
                AND a.i_product_motif = '00'
                AND c.f_product_pricelist = 't'
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')", 
        FALSE);
    }

    public function bacaproductx($kdharga, $iproduct){
        return $this->db->query(" 
            SELECT
                DISTINCT a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                d.i_product_group,
                c.e_product_name AS nama,
                b.v_product_retail AS harga,
                c.i_product_status,
                e.e_product_statusname
            FROM
                tr_product_motif a,
                tr_product_price b,
                tr_product c,
                tr_product_type d,
                tr_product_status e
            WHERE
                b.i_product = a.i_product
                AND c.i_product_status = e.i_product_status
                AND a.i_product = c.i_product
                AND d.i_product_type = c.i_product_type
                AND b.i_price_group = '$kdharga'
                AND a.i_product_motif = '00'
                AND c.f_product_pricelist = 't'
                AND a.i_product = '$iproduct'",
        FALSE);
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

    public function insertheader($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment,$ispbold,$eremarkx,$iproductgroup){       
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

    public function insertdetail($ispb,$iarea,$iproduct,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i,$iproductstatus){      
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->set(
            array(
                'i_spb'             => $ispb,
                'i_area'            => $iarea,
                'i_product'         => $iproduct,
                'i_product_status'  => $iproductstatus,
                'i_product_grade'   => $iproductgrade,
                'i_product_motif'   => $iproductmotif,
                'n_order'           => $norder,
                'n_deliver'         => $ndeliver,
                'v_unit_price'      => $vunitprice,
                'e_product_name'    => $eproductname,
                'e_remark'          => $eremark,
                'n_item_no'         => $i
            )
        );
        $this->db->insert('tm_spb_item');
    }

    public function insert($ispb,$icustomer,$iarea,$isalesman,$esalesmanname,$dsurvey,$nvisitperiod,$fcustomernew,$ecustomername,$ecustomeraddress,$ecustomersign,$ecustomerphone,$ert1,$erw1,$epostal1,$ecustomerkelurahan1,$ecustomerkecamatan1,$ecustomerkota1,$ecustomerprovinsi1,$efax1,$ecustomermonth,$ecustomeryear,$ecustomerage,$eshopstatus,$ishopstatus,$nshopbroad,$ecustomerowner,$ecustomerownerttl,$emarriage,$imarriage,$ejeniskelamin,$ijeniskelamin,$ereligion,$ireligion,$ecustomerowneraddress,$ecustomerownerphone,$ecustomerownerhp,$ecustomerownerfax,$ecustomerownerpartner,$ecustomerownerpartnerttl,$ecustomerownerpartnerage,$ert2,$erw2,$epostal2,$ecustomerkelurahan2,$ecustomerkecamatan2,$ecustomerkota2,$ecustomerprovinsi2,$ecustomersendaddress,$ecustomersendphone,$etraversed,$itraversed,$fparkir,$fkuli,$eekspedisi1,$eekspedisi2,$ert3,$erw3,$epostal3,$ecustomerkota3,$ecustomerprovinsi3,$ecustomerpkpnpwp,$fspbpkp,$ecustomernpwpname,$ecustomernpwpaddress,$ecustomerclassname,$icustomerclass,$epaymentmethod,$ipaymentmethod,$ecustomerbank1,$ecustomerbankaccount1,$ecustomerbankname1,$ecustomerbank2,$ecustomerbankaccount2,$ecustomerbankname2,$ekompetitor1,$ekompetitor2,$ekompetitor3,$nspbtoplength,$ncustomerdiscount,$epricegroupname,$ipricegroup,$nline,$fkontrabon,$ecall,$icall,$ekontrabonhari,$ekontrabonjam1,$ekontrabonjam2,$etagihhari,$etagihjam1,$etagihjam2,$icustomergroup,$icustomerplugroup,$icustomerproducttype,$icustomerspecialproduct,$icustomerstatus,$icustomergrade,$icustomerservice,$icustomersalestype,$ecustomerownerage,$ecustomerrefference,$iretensi,$icity,$ecustomercontact,$ecustomercontactgrade,$ecustomermail,$inik){
        $dentry  = current_datetime();
        $ecustomername=str_replace("'","''",$ecustomername);
        if($nshopbroad==''){
            $nshopbroad=0;
        }
        $this->db->query("
            INSERT
                INTO
                tr_customer_tmp
            VALUES ('$ispb',
            '$icustomer',
            '$iarea',
            '$isalesman',
            '$ipricegroup',
            '$icustomerclass',
            '$icustomerplugroup',
            '$icustomergroup',
            '$icustomerstatus',
            '$icustomerproducttype',
            '$icustomerspecialproduct',
            '$icustomergrade',
            '$icustomerservice',
            '$icustomersalestype',
            '$icity',
            '$ishopstatus',
            '$imarriage',
            '$ijeniskelamin',
            '$ireligion',
            '$itraversed',
            '$ipaymentmethod',
            '$icall',
            '$esalesmanname',
            '$dsurvey',
             $nvisitperiod,
            '$fcustomernew',
            '$ecustomername',
            '$ecustomeraddress',
            '$ecustomersign',
            '$ecustomerphone',
            '$ert1',
            '$erw1',
            '$epostal1',
            '$ecustomerkelurahan1',
            '$ecustomerkecamatan1',
            '$ecustomerkota1',
            '$ecustomerprovinsi1',
            '$efax1',
            '$ecustomermonth',
            '$ecustomeryear',
            '$ecustomerage',
            '$nshopbroad',
            '$ecustomerowner',
            '$ecustomerownerttl',
            '$ecustomerowneraddress',
            '$ecustomerownerphone',
            '$ecustomerownerhp',
            '$ecustomerownerfax',
            '$ecustomerownerpartner',
            '$ecustomerownerpartnerttl',
            '$ecustomerownerpartnerage',
            '$ert2',
            '$erw2',
            '$epostal2',
            '$ecustomerkelurahan2',
            '$ecustomerkecamatan2',
            '$ecustomerkota2',
            '$ecustomerprovinsi2',
            '$ecustomersendaddress',
            '$ecustomersendphone',
            '$fparkir',
            '$fkuli',
            '$eekspedisi1',
            '$eekspedisi2',
            '$ert3',
            '$erw3',
            '$epostal3',
            '$ecustomerkota3',
            '$ecustomerprovinsi3',
            '$ecustomerpkpnpwp',
            '$fspbpkp',
            '$ecustomernpwpname',
            '$ecustomernpwpaddress',
            '$ecustomerbank1',
            '$ecustomerbankaccount1',
            '$ecustomerbankname1',
            '$ecustomerbank2',
            '$ecustomerbankaccount2',
            '$ecustomerbankname2',
            '$ekompetitor1',
            '$ekompetitor2',
            '$ekompetitor3',
             $nspbtoplength,
            '$ncustomerdiscount',
            '$fkontrabon',
            '$ekontrabonhari',
            '$ekontrabonjam1',
            '$ekontrabonjam2',
            '$etagihhari',
            '$etagihjam1',
            '$etagihjam2',
            '$dentry',
            '$ecustomerownerage',
            'f',
            NULL,
            NULL,
            NULL,
            '$ecustomercontact',
            '$ecustomercontactgrade',
            '$ecustomersendphone',
            '$ecustomermail',
            '$ecustomerrefference',
            'f',
            't',
            NULL,
            NULL,
            NULL,
            '$iretensi',
            '$inik')"
        );

    }
}

/* End of file Mmaster.php */
