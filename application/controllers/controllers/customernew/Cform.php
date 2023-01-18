<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1030106';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'area'              => $this->mmaster->bacaarea(),
            'retensi'           => $this->mmaster->bacaretensi(),
            'shop'              => $this->mmaster->bacashop(),
            'status'            => $this->mmaster->bacastatus(),
            'kelamin'           => $this->mmaster->bacakelamin(),
            'agama'             => $this->mmaster->bacaagama(),
            'traversed'         => $this->mmaster->bacatraversed(),
            'class'             => $this->mmaster->bacaclass(),
            'payment'           => $this->mmaster->bacapayment(),
            'call'              => $this->mmaster->bacacall(),
            'customergroup'     => $this->mmaster->bacacustomergroup(),
            'plu'               => $this->mmaster->bacaplugroup(),
            'customertype'      => $this->mmaster->bacacustomertype(),
            'customerstatus'    => $this->mmaster->bacacustomerstatus(),
            'customergrade'     => $this->mmaster->bacacustomergrade(),
            'customerservice'   => $this->mmaster->bacacustomerservice(),
            'customersalestype' => $this->mmaster->bacacustomersalestype(),
            'pricegroup'        => $this->mmaster->bacapricegroup()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getkota(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '') {
            $filter = [];
            $iarea  = $this->input->get('iarea');
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->getkota($iarea, $cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_city,  
                    'text'  => $row->i_city.' - '.$row->e_city_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '') {
            $filter = [];
            $iarea  = $this->input->get('iarea');
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->getsalesman($iarea, $cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_salesman,  
                    'text'  => $row->e_salesman_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcustomerspecialproduct(){
        $iproducttype = $this->input->post('iproducttype');
        $query = $this->mmaster->getcustomerspecialproduct($iproducttype);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_customer_specialproduct." >".strtoupper($row->e_customer_specialproductname)."</option>";
            }
            $kop  = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\"></option>";
            echo json_encode(array(
                'kop'    => $kop
            ));
        }
    }

    public function databrg(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('kdharga') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $kdharga = strtoupper($this->input->get('kdharga', FALSE));
            $data    = $this->mmaster->bacaproduct($cari,$kdharga);
            foreach($data->result() as  $product){
                $filter[] = array(
                    'id'    => $product->kode,  
                    'text'  => $product->kode.' - '.$product->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailbar(){
        header("Content-Type: application/json", true);
        $kdharga  = $this->input->post('kdharga', FALSE);
        $iproduct = $this->input->post('iproduct', FALSE);
        $data     = $this->mmaster->bacaproductx($kdharga, $iproduct);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $icustomer                  = $this->input->post('iarea', TRUE).'000';
        $iarea                      = $this->input->post('iarea', TRUE);
        $isalesman                  = $this->input->post('isalesman', TRUE);
        $icustomergroup             = $this->input->post('icustomergroup', TRUE);
        $icustomerplugroup          = $this->input->post('icustomerplugroup', TRUE);
        $icustomerproducttype       = $this->input->post('icustomerproducttype', TRUE);
        $icustomerspecialproduct    = $this->input->post('icustomerspecialproduct', TRUE);
        $icustomerstatus            = $this->input->post('icustomerstatus', TRUE);
        $icustomergrade             = $this->input->post('icustomergrade', TRUE);
        $icustomerservice           = $this->input->post('icustomerservice', TRUE);
        $icustomersalestype         = $this->input->post('icustomersalestype', TRUE);
        $icity                      = $this->input->post('icity', TRUE);
        $esalesmanname              = $this->input->post('esalesmanname', TRUE);
        $dsurvey                    = $this->input->post('dsurvey', TRUE);
        if($dsurvey!=''){
            $tmp=explode("-",$dsurvey);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsurvey=$th."-".$bl."-".$hr;
        }else{
            $dsurvey=null;
        }
        $nvisitperiod               = $this->input->post('nvisitperiod', TRUE);
        if($nvisitperiod=='') {
            $nvisitperiod = 1;
        }
        $iretensi                   = $this->input->post('iretensi', TRUE);
        $fcustomernew               = $this->input->post('fcustomernew', TRUE);
        if($fcustomernew!=''){
            $fcustomernew           = 'f';
        }else{
            $fcustomernew           = 't';
        }
        $ecustomername              = $this->input->post('ecustomername', TRUE);
        $ecustomername              = str_replace("'","''",$ecustomername);
        $ecustomeraddress           = $this->input->post('ecustomeraddress', TRUE);
        $ecustomersign              = $this->input->post('ecustomersign', TRUE);
        $ecustomerphone             = $this->input->post('ecustomerphone', TRUE);
        $ert1                       = $this->input->post('ert1', TRUE);
        $erw1                       = $this->input->post('erw1', TRUE);
        $epostal1                   = $this->input->post('epostal1', TRUE);
        $ecustomerkelurahan1        = $this->input->post('ecustomerkelurahan1', TRUE);
        $ecustomerkecamatan1        = $this->input->post('ecustomerkecamatan1', TRUE);
        $ecustomerkota1             = $this->input->post('ecustomerkota1', TRUE);
        $ecustomerprovinsi1         = $this->input->post('ecustomerprovinsi1', TRUE);
        $efax1                      = $this->input->post('efax1', TRUE);
        $ecustomermonth             = $this->input->post('ecustomermonth', TRUE);
        $ecustomeryear              = $this->input->post('ecustomeryear', TRUE);
        $ecustomerage               = $this->input->post('ecustomerage', TRUE);
        $eshopstatus                = $this->input->post('eshopstatus', TRUE);
        $ishopstatus                = $this->input->post('ishopstatus', TRUE);
        $nshopbroad                 = $this->input->post('nshopbroad', TRUE);
        $ecustomerowner             = $this->input->post('ecustomerowner', TRUE);
        $inik                       = $this->input->post('inik', TRUE);
        $ecustomerownerttl          = $this->input->post('ecustomerownerttl', TRUE);
        $ecustomerownerage          = $this->input->post('ecustomerownerage', TRUE);
        $emarriage                  = $this->input->post('emarriage', TRUE);
        $imarriage                  = $this->input->post('imarriage', TRUE);
        $ejeniskelamin              = $this->input->post('ejeniskelamin', TRUE);
        $ijeniskelamin              = $this->input->post('ijeniskelamin', TRUE);
        $ereligion                  = $this->input->post('ereligion', TRUE);
        $ireligion                  = $this->input->post('ireligion', TRUE);
        $ecustomerowneraddress      = $this->input->post('ecustomerowneraddress', TRUE);
        $ecustomerownerphone        = $this->input->post('ecustomerownerphone', TRUE);
        $ecustomerownerhp           = $this->input->post('ecustomerownerhp', TRUE);
        $ecustomerownerfax          = $this->input->post('ecustomerownerfax', TRUE);
        $ecustomermail              = $this->input->post('ecustomermail', TRUE);
        $ecustomerownerpartner      = $this->input->post('ecustomerownerpartner', TRUE);
        $ecustomerownerpartnerttl   = $this->input->post('ecustomerownerpartnerttl', TRUE);
        $ecustomerownerpartnerage   = $this->input->post('ecustomerownerpartnerage', TRUE);
        $ert2                       = $this->input->post('ert2', TRUE);
        $erw2                       = $this->input->post('erw2', TRUE);
        $epostal2                   = $this->input->post('epostal2', TRUE);
        $ecustomerkelurahan2        = $this->input->post('ecustomerkelurahan2', TRUE);
        $ecustomerkecamatan2        = $this->input->post('ecustomerkecamatan2', TRUE);
        $ecustomerkota2             = $this->input->post('ecustomerkota2', TRUE);
        $ecustomerprovinsi2         = $this->input->post('ecustomerprovinsi2', TRUE);
        $ecustomersendaddress       = $this->input->post('ecustomersendaddress', TRUE);
        $ecustomersendphone         = $this->input->post('ecustomersendphone', TRUE);
        $ecustomercontact           = $this->input->post('ecustomercontact', TRUE);
        $ecustomercontactgrade      = $this->input->post('ecustomercontactgrade', TRUE);
        $etraversed                 = $this->input->post('etraversed', TRUE);
        $itraversed                 = $this->input->post('itraversed', TRUE);
        $fparkir                    = $this->input->post('fparkir', TRUE);
        if($fparkir!=''){
            $fparkir                = 't';
        }else{
            $fparkir                = 'f';
        }
        $fkuli                      = $this->input->post('fkuli', TRUE);
        if($fkuli!=''){
            $fkuli                  = 't';
        }else{
            $fkuli                  = 'f';
        }
        $eekspedisi1                = $this->input->post('eekspedisi1', TRUE);
        $eekspedisi2                = $this->input->post('eekspedisi2', TRUE);
        $ert3                       = $this->input->post('ert3', TRUE);
        $erw3                       = $this->input->post('erw3', TRUE);
        $epostal3                   = $this->input->post('epostal3', TRUE);
        $ecustomerkota3             = $this->input->post('ecustomerkota3', TRUE);
        $ecustomerprovinsi3         = $this->input->post('ecustomerprovinsi3', TRUE);
        $ecustomerpkpnpwp           = $this->input->post('ecustomernpwp', TRUE);
        if($ecustomerpkpnpwp!=''){
            $fspbpkp                = 't';
        }else{
            $fspbpkp                = 'f';
        }
        $ecustomernpwpname          = $this->input->post('ecustomernpwpname', TRUE);
        $ecustomernpwpaddress       = $this->input->post('ecustomernpwpaddress', TRUE);
        $ecustomerclassname         = $this->input->post('ecustomerclassname', TRUE);
        $icustomerclass             = $this->input->post('icustomerclass', TRUE);
        $epaymentmethod             = $this->input->post('epaymentmethod', TRUE);
        $ipaymentmethod             = $this->input->post('ipaymentmethod', TRUE);
        $ecustomerbank1             = $this->input->post('ecustomerbank1', TRUE);
        $ecustomerbankaccount1      = $this->input->post('ecustomerbankaccount1', TRUE);
        $ecustomerbankname1         = $this->input->post('ecustomerbankname1', TRUE);
        $ecustomerbank2             = $this->input->post('ecustomerbank2', TRUE);
        $ecustomerbankaccount2      = $this->input->post('ecustomerbankaccount2', TRUE);
        $ecustomerbankname2         = $this->input->post('ecustomerbankname2', TRUE);
        $ekompetitor1               = $this->input->post('ekompetitor1', TRUE);
        $ekompetitor1               = str_replace("'","''",$ekompetitor1);
        $ekompetitor2               = $this->input->post('ekompetitor2', TRUE);
        $ekompetitor2               = str_replace("'","''",$ekompetitor2);
        $ekompetitor3               = $this->input->post('ekompetitor3', TRUE);
        $ekompetitor3               = str_replace("'","''",$ekompetitor3);
        $nspbtoplength              = $this->input->post('ncustomertoplength', TRUE);
        $ncustomerdiscount          = $this->input->post('ncustomerdiscount', TRUE);
        $epricegroupname            = $this->input->post('epricegroupname', TRUE);
        $ipricegroup                = $this->input->post('ipricegroup', TRUE);
        $nline                      = $this->input->post('nline', TRUE);
        $fkontrabon                 = $this->input->post('fkontrabon', TRUE);
        if($fkontrabon!=''){
            $fkontrabon             = 't';
        }else{
            $fkontrabon             = 'f';
        }
        $ecall                      = $this->input->post('ecall', TRUE);
        $icall                      = $this->input->post('icall', TRUE);
        $ekontrabonhari             = $this->input->post('ekontrabonhari', TRUE);
        $ekontrabonjam1             = $this->input->post('ekontrabonjam1', TRUE);
        $ekontrabonjam2             = $this->input->post('ekontrabonjam2', TRUE);
        $etagihhari                 = $this->input->post('etagihhari', TRUE);
        $etagihjam1                 = $this->input->post('etagihjam1', TRUE);
        $etagihjam2                 = $this->input->post('etagihjam2', TRUE);
        $dspb                       = $this->input->post('dspb', TRUE);
        if($dspb!=''){
            $tmp=explode("-",$dspb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspb=$th."-".$bl."-".$hr;
            $dspbreceive=$dspb;
            $thbl=$th.$bl;
        }
        $nspbdiscount1              = $this->input->post('ncustomerdiscount1',TRUE);
        $nspbdiscount2              = $this->input->post('ncustomerdiscount2',TRUE);
        $nspbdiscount3              = $this->input->post('ncustomerdiscount3',TRUE);
        $vspbdiscount1              = $this->input->post('vcustomerdiscount1',TRUE);
        $vspbdiscount2              = $this->input->post('vcustomerdiscount2',TRUE);
        $vspbdiscount3              = $this->input->post('vcustomerdiscount3',TRUE);
        $vspbdiscounttotal          = $this->input->post('vspbdiscounttotal',TRUE);
        $vspb                       = $this->input->post('vspb',TRUE);
        $nspbdiscount1              = str_replace(',','',$nspbdiscount1);
        $nspbdiscount2              = str_replace(',','',$nspbdiscount2);
        $nspbdiscount3              = str_replace(',','',$nspbdiscount3);
        $vspbdiscount1              = str_replace(',','',$vspbdiscount1);
        $vspbdiscount2              = str_replace(',','',$vspbdiscount2);
        $vspbdiscount3              = str_replace(',','',$vspbdiscount3);
        $vspbdiscounttotal          = str_replace(',','',$vspbdiscounttotal);
        $vspb                       = str_replace(',','',$vspb);
        $ispbpo                     = $this->input->post('ispbpo', TRUE);
        if($ispbpo=='') {
            $ispbpo =' ';
        }
        if($ispbpo==' ') {
            $fspbop='f'; 
        }else{
            $fspbop='t';
        } 
        $fspbstockdaerah            = $this->input->post('fspbstockdaerah',TRUE);
        if($fspbstockdaerah!=''){
            $fspbstockdaerah        = 't';
        }else{
            $fspbstockdaerah        = 'f';
        }   
        $fspbconsigment             = 'f';
        $fspbprogram                = 'f';
        $fspbvalid                  = 'f';
        $fspbsiapnotagudang         = 'f';
        $fspbcancel                 = 'f';
        $fspbfirst                  = 't';

        $ecustomerrefference        = $this->input->post('ecustomerrefference', TRUE);
        $eremarkx                   = $this->input->post('eremarkx', TRUE);
        $fspbplusppn                = 't';
        $fspbplusdiscount           = 'f';
        $ispbold                    = $this->input->post('ispbold',TRUE);
        $jml                        = $this->input->post('jml', TRUE);
        for($i=1;$i<=$jml;$i++){          
            $iproductgroup          = $this->input->post('iproductgroup'.$i, TRUE);
            break;
        }
        if(($ecustomername!= '') && ($dspb!='') && ($iarea!='') && ($jml>0) && ($ipricegroup!='') && ($nspbtoplength!='') && ($dsurvey!='') && ($isalesman!='') && ($ncustomerdiscount!='') && ($nvisitperiod!='') && ($icustomergroup!='') && ($icustomerproducttype!='') && ($icustomerstatus!='') && ($icustomergrade!='') && ($icustomerservice!='') && ($icustomersalestype!='') && ($ipaymentmethod!='') && ((!$fspbpkp && $inik!='') || ($fspbpkp))){
            $this->db->trans_begin();
            $ispb =$this->mmaster->runningnumber($iarea, $thbl);
            $this->mmaster->insertheader($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid,$fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment, $ispbold, $eremarkx,$iproductgroup);
            for($i=1;$i<=$jml;$i++){              
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $iproductstatus   = $this->input->post('iproductstatus'.$i, TRUE);
                $iproductgrade    = 'A';
                $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice       = $this->input->post('vproductretail'.$i, TRUE);
                $vunitprice       = str_replace(',','',$vunitprice);
                $norder           = $this->input->post('norder'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);            
                if($norder>0 && ($iproduct!='' || $iproduct!=null)){                
                    $this->mmaster->insertdetail($ispb,$iarea,$iproduct,$iproductgrade,$eproductname,$norder,null,
                                  $vunitprice,$iproductmotif,$eremark,$i,$iproductstatus);            
                }        
            }
            $this->mmaster->insert($ispb,$icustomer,$iarea,$isalesman,$esalesmanname,$dsurvey,$nvisitperiod,$fcustomernew,$ecustomername,$ecustomeraddress,$ecustomersign,$ecustomerphone,$ert1,$erw1,$epostal1,$ecustomerkelurahan1,$ecustomerkecamatan1,$ecustomerkota1,$ecustomerprovinsi1,$efax1,$ecustomermonth,$ecustomeryear,$ecustomerage,$eshopstatus,$ishopstatus,$nshopbroad,$ecustomerowner,$ecustomerownerttl,$emarriage,$imarriage,$ejeniskelamin,$ijeniskelamin,$ereligion,$ireligion,$ecustomerowneraddress,$ecustomerownerphone,$ecustomerownerhp,$ecustomerownerfax,$ecustomerownerpartner,$ecustomerownerpartnerttl,$ecustomerownerpartnerage,$ert2,$erw2,$epostal2,$ecustomerkelurahan2,$ecustomerkecamatan2,$ecustomerkota2,$ecustomerprovinsi2,$ecustomersendaddress,$ecustomersendphone,$etraversed,$itraversed,$fparkir,$fkuli,$eekspedisi1,$eekspedisi2,$ert3,$erw3,$epostal3,$ecustomerkota3,$ecustomerprovinsi3,$ecustomerpkpnpwp,$fspbpkp,$ecustomernpwpname,$ecustomernpwpaddress,$ecustomerclassname,$icustomerclass,$epaymentmethod,$ipaymentmethod,$ecustomerbank1,$ecustomerbankaccount1,$ecustomerbankname1,$ecustomerbank2,$ecustomerbankaccount2,$ecustomerbankname2,$ekompetitor1,$ekompetitor2,$ekompetitor3,$nspbtoplength,$ncustomerdiscount,$epricegroupname,$ipricegroup,$nline,$fkontrabon,$ecall,$icall,$ekontrabonhari,$ekontrabonjam1,$ekontrabonjam2,$etagihhari,$etagihjam1,$etagihjam2,$icustomergroup,$icustomerplugroup,$icustomerproducttype,$icustomerspecialproduct,$icustomerstatus,$icustomergrade,$icustomerservice,$icustomersalestype,$ecustomerownerage,$ecustomerrefference,$iretensi,$icity,$ecustomercontact,$ecustomercontactgrade,$ecustomermail,$inik);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input SPB Pelanggan Baru Area '.$iarea.' No:'.$ispb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
