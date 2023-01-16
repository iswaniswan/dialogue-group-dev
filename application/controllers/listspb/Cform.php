<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020105';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
            'i_area'    => $this->mmaster->cekarea(),
            'area'      => $this->mmaster->bacaarea($username, $idcompany)->result()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $iarea  = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
        if($area==''){
            $area=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area,
            'total'         => $this->mmaster->total($dfrom,$dto,$area)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }/*

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
    }*/

    public function balik(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ispb   = $this->input->post('ispb');
        $iarea  = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->balik($ispb, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Balik Status SPB No:'.$ispb.' Area:'.$iarea);
            echo json_encode($data);
        }
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ispb   = $this->input->post('ispb');
        $iarea  = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ispb, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SPB Per Divisi Area '.$iarea.' No:'.$ispb);
            echo json_encode($data);
        }
    }

    /*********************************| START EDIT SPB PROMO |****************************************/

    public function editspbpromo(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $idcompany  = $this->session->userdata('id_company');
        $username   = $this->session->userdata('username');
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ispb   = $this->uri->segment(4);
            $iarea  = $this->uri->segment(5);
            $ipromo = $this->uri->segment(6);
            $dspb   = $this->uri->segment(7);
            $xarea  = $this->uri->segment(8);
            $dfrom  = $this->uri->segment(9);
            $dto    = $this->uri->segment(10);
            $this->db->select(" * from tm_spb where i_spb = '$ispb' and i_area='$iarea'");
            $query  = $this->db->get();
            foreach($query->result() as $row){
                $pesan=$row->e_notapprove;
                $status=$row->i_notapprove;
            }
            $qnilaispb  = $this->mmaster->bacadetailnilaispbpromo($ispb,$iarea);
            if($qnilaispb->num_rows()>0){
                $row_nilaispb  = $qnilaispb->row();
                $nilaispb = $row_nilaispb->nilaispb;
            }else{
                $nilaispb = 0;
            }
            $qnilaiorderspb   = $this->mmaster->bacadetailnilaiorderspbpromo($ispb,$iarea);
            if($qnilaiorderspb->num_rows()>0){
                $row_nilaiorderspb   = $qnilaiorderspb->row();
                $nilaiorderspb  = $row_nilaiorderspb->nilaiorderspb;
            }else{
                $nilaiorderspb  = 0;
            }
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'ispb'          => $ispb,
                'xarea'         => $xarea,
                'status'        => $status,
                'pesan'         => $pesan,
                'ipromo'        => $ipromo,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'i_menu'        => $this->i_menu,
                'nilaiorderspb' => $nilaiorderspb,
                'nilaispb'      => $nilaispb,
                'isi'           => $this->mmaster->bacaspbpromo($ispb,$iarea),
                'detail'        => $this->mmaster->bacadetailspbpromo($ispb,$iarea),
                'customer'      => $this->mmaster->bacapelanggan(),
                'group'         => $this->mmaster->bacagroup(),
                'promo'         => $this->mmaster->bacapromospb($username, $idcompany, $dspb)
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformeditpromo', $data);
    }

    public function getareapromo(){
        $ipromo = $this->input->post('ipromo');
        $query  = $this->mmaster->getpromo($ipromo);
        if ($query->num_rows()>0) {
            foreach($query->result() as $pro){
                $a = $pro->f_all_area;
            }
        }else{
            die();
        }
        $qarea = $this->mmaster->cariareapromo($ipromo, $a);
        if($qarea->num_rows()>0) {
            $c  = "";
            foreach($qarea->result() as $row) {
                $c.="<option value=".$row->i_area." >".$row->i_area." - ".$row->e_area_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Area -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Ada Area</option>";
            echo json_encode(array(
                'kop'    => $kop,
            ));
        }
    }

    public function getpelangganpromo(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = $this->input->get('i_area');
            $ipromo  = $this->input->get('i_promo');
            $dspb    = $this->input->get('dspb');
            if($dspb!=''){          
                $tmp=explode('-',$dspb);          
                $yy=$tmp[2];          
                $bl=$tmp[1];          
                $per=$yy.$bl;      
            }      else{
                $per = date('Y-m-d');
            }
            $query   = $this->mmaster->getpromo($ipromo);
            foreach($query->result() as $pro){
                $c       = $pro->f_all_customer;
                $g       = $pro->f_customer_group;
                $type    = $pro->i_promo_type;
                $disc1   = $pro->n_promo_discount1;
                $disc2   = $pro->n_promo_discount2;
            }
            $data        = $this->mmaster->getpelangganpromo($cari, $iarea, $ipromo, $c, $g, $type, $per);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_customer,  
                    'text'  => $row->i_customer.' - '.$row->e_customer_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailpelpromo(){
        header("Content-Type: application/json", true);
        $icustomer = $this->input->post('icustomer');
        $dspb      = $this->input->post('dspb');
        $iarea     = $this->input->post('iarea');
        $ipromo    = $this->input->post('ipromo');
        $per='';
        if($dspb!=''){          
            $tmp=explode('-',$dspb);          
            $yy=$tmp[2];          
            $bl=$tmp[1];          
            $per=$yy.$bl;      
        }
        $query   = $this->mmaster->getpromo($ipromo);
        foreach($query->result() as $pro){
            $c       = $pro->f_all_customer;
            $g       = $pro->f_customer_group;
            $type    = $pro->i_promo_type;
            $disc1   = $pro->n_promo_discount1;
            $disc2   = $pro->n_promo_discount2;
        }      
        $data = $this->mmaster->getdetailpelpromo($icustomer, $iarea, $ipromo, $c, $g, $type, $per, $disc1, $disc2);
        echo json_encode($data->result_array());  
    }

    public function getsalespromo(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $iarea  = $this->input->get('i_area');
            $dspb   = $this->input->get('d_spb');
            if($dspb!=''){
                $tmp=explode('-',$dspb);
                $yy=$tmp[2];
                $bl=$tmp[1];
                $per=$yy.$bl;
            }
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getsalespromo($iarea, $cari, $per);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_salesman,  
                    'text'  => $row->e_salesman_name.' - '.$row->i_salesman
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailsalpromo(){
        header("Content-Type: application/json", true);
        $isalesman = $this->input->post('isalesman');
        $dspb      = $this->input->post('dspb');
        $iarea     = $this->input->post('iarea');
        $per='';
        if($dspb!=''){          
            $tmp=explode('-',$dspb);          
            $yy=$tmp[2];          
            $bl=$tmp[1];          
            $per=$yy.$bl;      
        }      
        $data = $this->mmaster->getdetailsalpromo($isalesman, $per, $iarea);      
        echo json_encode($data->result_array());  
    }

    public function getdetailbarpromo(){
        header("Content-Type: application/json", true);
        $kdharga  = strtoupper($this->input->post('kdharga', FALSE));
        $ipromo   = strtoupper($this->input->post('ipromo', FALSE));
        $group    = $this->input->post('group', FALSE);
        $iproduct = $this->input->post('iproduct', FALSE);
        $kdgroup  = $this->input->post('kdgroup', FALSE);
        $data = $this->mmaster->bacaproductxpromo($kdharga,$iproduct,$ipromo,$kdgroup,$group);
        echo json_encode($data->result_array());  
    }

    public function databrgpromo(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $kdharga = strtoupper($this->input->get('kdharga', FALSE));
            $ipromo  = strtoupper($this->input->get('ipromo', FALSE));
            $groupbarang = $this->input->get('group', FALSE);
            $kdgroup = $this->input->get('kdgroup', FALSE);
            $data = $this->mmaster->bacaproductpromo($cari,$kdharga,$groupbarang,$ipromo,$kdgroup);
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

    public function updatespbpromo(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb    = $this->input->post('ispb', TRUE);
        $dspb    = $this->input->post('dspb', TRUE);         
        if($dspb!=''){   
            $dspb = date('Y-m-d', strtotime($dspb));
        }else{
            $dspb = date('Y-m-d');
        }
        $iproductgroup      = $this->input->post('productgroup', TRUE);
        $icustomer          = $this->input->post('icustomer', TRUE);
        $fspbstockdaerah    = $this->input->post('fspbstockdaerah',TRUE);             
        if($fspbstockdaerah!=''){                
            $fspbstockdaerah= 't';             
        }else{                
            $fspbstockdaerah= 'f';             
        }
        $ecustomername      = $this->input->post('ecustomername', TRUE);
        $ecumstomeraddress  = $this->input->post('ecumstomeraddress', TRUE);
        $eremarkx           = $this->input->post('eremarkx', TRUE);
        $iarea              = $this->input->post('iarea', TRUE);
        $eareaname          = $this->input->post('eareaname', TRUE);
        $ispbpo             = $this->input->post('ispbpo', TRUE);
        $nspbtoplength      = $this->input->post('nspbtoplength', TRUE);
        $isalesman          = $this->input->post('isalesmanx',TRUE);
        $esalesmanname      = $this->input->post('esalesmannamex',TRUE);
        $ipricegroup        = $this->input->post('ipricegroup',TRUE);
        $inota              = $this->input->post('inota',TRUE);
        $isj                = $this->input->post('isj',TRUE);
        $dsj                = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $dsj = date('Y-m-d', strtotime($dsj));
        }else{
            $dsj = date('Y-m-d');
        }
        $istore             = $this->input->post('istore',TRUE);
        $istorelocation     = $this->input->post('istorelocation',TRUE);
        $istorelocationbin  = '00';
        $dspbreceive        = $this->input->post('dspbreceive',TRUE);
        $ispbprogram        = $this->input->post('ipromo',TRUE);
        $fspb_op            = $this->input->post('f_spb_op',TRUE);
        if($fspb_op!=''){
            $fspbop     = 't';
        }else{
            $fspbop     = 'f';
        }
        $ecustomerpkpnpwp = $this->input->post('ecustomerpkpnpwp',TRUE);
        if($ecustomerpkpnpwp!=''){
            $fspbpkp = 't';
        }else{
            $fspbpkp = 'f';
        }
        $fspbconsigment      = $this->input->post('fspbconsigment',TRUE);
        if($fspbconsigment!=''){
            $fspbconsigment = 't';
        }else{
            $fspbconsigment = 'f';
        }
        $fspbplusppn        = $this->input->post('fspbplusppn',TRUE);
        $fspbplusdiscount   = $this->input->post('fspbplusdiscount',TRUE);
        $fspbvalid          = 'f';
        $fspbprogramx       = $this->input->post('f_spb_program',TRUE);
        if($fspbprogramx!=''){
            $fspbprogram   = 't';
        }else{
            $fspbprogram   = 'f';
        }
        $fspbsiapnotagudang  = $this->input->post('fspbsiapnotagudang',TRUE);
        if($fspbsiapnotagudang!=''){
            $fspbsiapnota  = 't';
        }else{
            $fspbsiapnota  = 'f';
        }
        $fspbcancel = 'f';
        $nspbtoplength      = $this->input->post('nspbtoplength',TRUE);
        $nspbdiscount1      = $this->input->post('ncustomerdiscount1',TRUE);
        $nspbdiscount2      = $this->input->post('ncustomerdiscount2',TRUE);
        $nspbdiscount3      = $this->input->post('ncustomerdiscount3',TRUE);
        $nspbdiscount4      = $this->input->post('ncustomerdiscount4',TRUE);
        $vspbdiscount1      = $this->input->post('vcustomerdiscount1',TRUE);
        $vspbdiscount2      = $this->input->post('vcustomerdiscount2',TRUE);
        $vspbdiscount3      = $this->input->post('vcustomerdiscount3',TRUE);
        $vspbdiscount4      = $this->input->post('vcustomerdiscount4',TRUE);
        $vspbdiscount1x     = $this->input->post('vcustomerdiscount1x',TRUE);
        $vspbdiscount2x     = $this->input->post('vcustomerdiscount2x',TRUE);
        $vspbdiscount3x     = $this->input->post('vcustomerdiscount3x',TRUE);
        $vspbdiscount4x     = $this->input->post('vcustomerdiscount4x',TRUE);
        $vspbdiscounttotal  = $this->input->post('vspbdiscounttotal',TRUE);
        $vspbdiscounttotalafter = $this->input->post('vspbdiscounttotalafter',TRUE);
        $vspb               = $this->input->post('vspb',TRUE);
        $vspbx              = $this->input->post('vspbx',TRUE);
        $vspbafter          = $this->input->post('vspbafter',TRUE);
        $nspbdiscount1      = str_replace(',','',$nspbdiscount1);
        $nspbdiscount2      = str_replace(',','',$nspbdiscount2);
        $nspbdiscount3      = str_replace(',','',$nspbdiscount3);
        $nspbdiscount4      = str_replace(',','',$nspbdiscount4);
        $vspbdiscount1      = str_replace(',','',$vspbdiscount1);
        $vspbdiscount2      = str_replace(',','',$vspbdiscount2);
        $vspbdiscount3      = str_replace(',','',$vspbdiscount3);
        $vspbdiscount4      = str_replace(',','',$vspbdiscount4);
        $vspbdiscount1x     = str_replace(',','',$vspbdiscount1x);
        $vspbdiscount2x     = str_replace(',','',$vspbdiscount2x);
        $vspbdiscount3x     = str_replace(',','',$vspbdiscount3x);
        $vspbdiscount4x     = str_replace(',','',$vspbdiscount4x);
        $vspbdiscounttotal  = str_replace(',','',$vspbdiscounttotal);
        $vspbdiscounttotalafter = str_replace(',','',$vspbdiscounttotalafter);
        $vspb               = str_replace(',','',$vspb);
        $vspbx              = str_replace(',','',$vspbx);
        $vspbafter          = str_replace(',','',$vspbafter);
        $ispbold            = $this->input->post('ispbold',TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if(($icustomer!='') && ($dspb!='')){
            $this->db->trans_begin();
            $this->mmaster->updateheaderpromo($ispb, $iarea, $dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman,$ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp,$fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid,$fspbsiapnota, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $nspbdiscount4,$vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscount4, $vspbdiscounttotal, $vspb,$fspbconsigment,$ispbold, $eremarkx, $ispbprogram, $iproductgroup);
            for($i=1;$i<=$jml;$i++){
                $iproduct               = $this->input->post('iproduct'.$i, TRUE);
                $iproductstatus         = $this->input->post('iproductstatus'.$i, TRUE);
                $iproductgrade          = 'A';
                $iproductmotif          = $this->input->post('motif'.$i, TRUE);
                $eproductname           = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice             = $this->input->post('vproductretail'.$i, TRUE);
                $vunitprice             = str_replace(',','',$vunitprice);
                $norder                 = $this->input->post('norder'.$i, TRUE);
                $eremark                = $this->input->post('eremark'.$i, TRUE);
                $ndeliver =$this->input->post('ndeliver'.$i, TRUE);
                if($ndeliver==''){
                    $ndeliver=null;
                }
                $this->mmaster->deletedetail($ispb, $iarea, $iproduct, $iproductgrade, $iproductmotif);
                if($norder>0){
                    $this->mmaster->insertdetail( $ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i);
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update SPB Promo Area '.$iarea.' No:'.$ispb);
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

    /*********************************| END EDIT SPB PROMO |****************************************/

    /*********************************| START EDIT SPB REGULER |****************************************/   
    public function editspb(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ispb          = $this->uri->segment(4);
            $iarea         = $this->uri->segment(5);
            $ipricegroup   = $this->uri->segment(6);
            $xarea         = $this->uri->segment(7);
            $dfrom         = $this->uri->segment(8);
            $dto           = $this->uri->segment(9);
            $qnilaispb     = $this->mmaster->bacadetailnilaispb($ispb,$iarea,$ipricegroup);
            if($qnilaispb->num_rows()>0){
                $row_nilaispb  = $qnilaispb->row();
                $nilaispb      = $row_nilaispb->nilaispb;
            }else{
                $nilaispb = 0;
            }
            $qnilaiorderspb  = $this->mmaster->bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup);
            if($qnilaiorderspb->num_rows()>0){
                $row_nilaiorderspb  = $qnilaiorderspb->row();
                $nilaiorderspb      = $row_nilaiorderspb->nilaiorderspb;
            }else{
                $nilaiorderspb  = 0;
            }
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title']." REGULER",
                'title_list'    => 'List '.$this->global['title']." REGULER",
                'ispb'          => $ispb,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'xarea'         => $xarea,
                'i_menu'        => $this->i_menu,
                'departement'   => $this->session->userdata('i_departement'),
                'nilaispb'      => $nilaispb,
                'nilaiorderspb' => $nilaiorderspb,
                'group'         => $this->mmaster->bacagroup(),
                'isi'           => $this->mmaster->bacaspb($ispb,$iarea),
                'detail'        => $this->mmaster->bacadetailspb($ispb,$iarea,$ipricegroup)
            );   
        }

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformeditspb', $data);
    }

    public function getdetailbarreguler(){
        header("Content-Type: application/json", true);
        $kdharga  = strtoupper($this->input->post('kdharga', FALSE));
        $istore   = strtoupper($this->input->post('istore', FALSE));
        $fstock   = $this->input->post('fstock', FALSE);
        $group    = $this->input->post('group', FALSE);
        $iproduct = $this->input->post('iproduct', FALSE);
        if ($fstock!='t') {
            $data = $this->mmaster->bacaproductxreguler($kdharga,$group,$iproduct);
        }else{
            $data = $this->mmaster->bacaproducticxreguler($kdharga,$istore,$fstock,$group,$iproduct);
        }
        echo json_encode($data->result_array());  
    }

    public function databrgreguler(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $kdharga = strtoupper($this->input->get('kdharga', FALSE));
            $iarea   = strtoupper($this->input->get('iarea', FALSE));
            $istore  = strtoupper($this->input->get('istore', FALSE));
            $fstock  = $this->input->get('fstock', FALSE);
            $groupbarang = $this->input->get('group', FALSE);
            if ($fstock!='t') {
                $data = $this->mmaster->bacaproductreguler($cari,$kdharga,$groupbarang);
            }else{
                $data = $this->mmaster->bacaproducticreguler($cari,$kdharga,$istore,$fstock,$groupbarang);
            }
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

    public function updatespbreguler(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb    = $this->input->post('ispb', TRUE);
        $dspb    = $this->input->post('dspb', TRUE);         
        if($dspb!=''){   
            $dspb = date('Y-m-d', strtotime($dspb));
        }else{
            $dspb = date('Y-m-d');
        }
        $iproductgroup      = $this->input->post('productgroup', TRUE);
        $icustomer          = $this->input->post('icustomer', TRUE);
        $fspbstockdaerah    = $this->input->post('fspbstockdaerah',TRUE);             
        if($fspbstockdaerah!=''){                
            $fspbstockdaerah= 't';             
        }else{                
            $fspbstockdaerah= 'f';             
        }
        $ecustomername      = $this->input->post('ecustomername', TRUE);
        $ecumstomeraddress  = $this->input->post('ecumstomeraddress', TRUE);
        $eremarkx           = $this->input->post('eremarkx', TRUE);
        $iarea              = $this->input->post('iarea', TRUE);
        $eareaname          = $this->input->post('eareaname', TRUE);
        $ispbpo             = $this->input->post('ispbpo', TRUE);
        $nspbtoplength      = $this->input->post('nspbtoplength', TRUE);
        $isalesman          = $this->input->post('isalesmanx',TRUE);
        $esalesmanname      = $this->input->post('esalesmannamex',TRUE);
        $ipricegroup        = $this->input->post('ipricegroup',TRUE);
        $inota              = $this->input->post('inota',TRUE);
        $isj                = $this->input->post('isj',TRUE);
        $dsj                = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $dsj = date('Y-m-d', strtotime($dsj));
        }else{
            $dsj = date('Y-m-d');
        }
        $istore             = $this->input->post('istore',TRUE);
        $istorelocation     = $this->input->post('istorelocation',TRUE);
        $istorelocationbin  = '00';
        $dspbreceive        = $this->input->post('dspbreceive',TRUE);
        $fspb_op            = $this->input->post('f_spb_op',TRUE);
        if($fspb_op!=''){
            $fspbop     = 't';
        }else{
            $fspbop     = 'f';
        }
        $ecustomerpkpnpwp = $this->input->post('ecustomerpkpnpwp',TRUE);
        if($ecustomerpkpnpwp!=''){
            $fspbpkp = 't';
        }else{
            $fspbpkp = 'f';
        }
        $fspbconsigment      = $this->input->post('fspbconsigment',TRUE);
        if($fspbconsigment!=''){
            $fspbconsigment = 't';
        }else{
            $fspbconsigment = 'f';
        }
        $fspbplusppn        = $this->input->post('fspbplusppn',TRUE);
        $fspbplusdiscount   = $this->input->post('fspbplusdiscount',TRUE);
        $fspbprogramx       = $this->input->post('f_spb_program',TRUE);
        if($fspbprogramx!=''){
            $fspbprogram   = 't';
        }else{
            $fspbprogram   = 'f';
        }
        $fspbsiapnotagudang  = $this->input->post('fspbsiapnotagudang',TRUE);
        if($fspbsiapnotagudang!=''){
            $fspbsiapnota  = 't';
        }else{
            $fspbsiapnota  = 'f';
        }
        $fspbcancel         = 'f';
        $fspbprogram        = 'f';
        $fspbvalid          = 'f';
        $nspbtoplength      = $this->input->post('nspbtoplength',TRUE);
        $nspbdiscount1      = $this->input->post('ncustomerdiscount1',TRUE);
        $nspbdiscount2      = $this->input->post('ncustomerdiscount2',TRUE);
        $nspbdiscount3      = $this->input->post('ncustomerdiscount3',TRUE);
        $nspbdiscount4      = $this->input->post('ncustomerdiscount4',TRUE);
        $vspbdiscount1      = $this->input->post('vcustomerdiscount1',TRUE);
        $vspbdiscount2      = $this->input->post('vcustomerdiscount2',TRUE);
        $vspbdiscount3      = $this->input->post('vcustomerdiscount3',TRUE);
        $vspbdiscount4      = $this->input->post('vcustomerdiscount4',TRUE);
        $vspbdiscount1x     = $this->input->post('vcustomerdiscount1x',TRUE);
        $vspbdiscount2x     = $this->input->post('vcustomerdiscount2x',TRUE);
        $vspbdiscount3x     = $this->input->post('vcustomerdiscount3x',TRUE);
        $vspbdiscount4x     = $this->input->post('vcustomerdiscount4x',TRUE);
        $vspbdiscounttotal  = $this->input->post('vspbdiscounttotal',TRUE);
        $vspbdiscounttotalafter = $this->input->post('vspbdiscounttotalafter',TRUE);
        $vspb               = $this->input->post('vspb',TRUE);
        $vspbx              = $this->input->post('vspbx',TRUE);
        $vspbafter          = $this->input->post('vspbafter',TRUE);
        $nspbdiscount1      = str_replace(',','',$nspbdiscount1);
        $nspbdiscount2      = str_replace(',','',$nspbdiscount2);
        $nspbdiscount3      = str_replace(',','',$nspbdiscount3);
        $nspbdiscount4      = str_replace(',','',$nspbdiscount4);
        $vspbdiscount1      = str_replace(',','',$vspbdiscount1);
        $vspbdiscount2      = str_replace(',','',$vspbdiscount2);
        $vspbdiscount3      = str_replace(',','',$vspbdiscount3);
        $vspbdiscount4      = str_replace(',','',$vspbdiscount4);
        $vspbdiscount1x     = str_replace(',','',$vspbdiscount1x);
        $vspbdiscount2x     = str_replace(',','',$vspbdiscount2x);
        $vspbdiscount3x     = str_replace(',','',$vspbdiscount3x);
        $vspbdiscount4x     = str_replace(',','',$vspbdiscount4x);
        $vspbdiscounttotal  = str_replace(',','',$vspbdiscounttotal);
        $vspbdiscounttotalafter = str_replace(',','',$vspbdiscounttotalafter);
        $vspb               = str_replace(',','',$vspb);
        $vspbx              = str_replace(',','',$vspbx);
        $vspbafter          = str_replace(',','',$vspbafter);
        $ispbold            = $this->input->post('ispbold',TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if(($icustomer!='') && ($ispb!='')){
            $this->db->trans_begin();
            $this->mmaster->updateheaderreguler($ispb, $iarea, $dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman,$ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp,$fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid,$fspbsiapnota, $fspbcancel, $nspbdiscount1,$nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2,$vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment, $ispbold, $eremarkx);
            for($i=1;$i<=$jml;$i++){
                $iproduct               = $this->input->post('iproduct'.$i, TRUE);
                $iproductstatus         = $this->input->post('iproductstatus'.$i, TRUE);
                $iproductgrade          = 'A';
                $iproductmotif          = $this->input->post('motif'.$i, TRUE);
                $eproductname           = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice             = $this->input->post('vproductretail'.$i, TRUE);
                $vunitprice             = str_replace(',','',$vunitprice);
                $norder                 = $this->input->post('norder'.$i, TRUE);
                $eremark                = $this->input->post('eremark'.$i, TRUE);
                $ndeliver               = $this->input->post('ndeliver'.$i, TRUE);
                $ndeliverx              = $this->input->post('ndeliverx'.$i, TRUE);
                $eremark                = $this->input->post('eremark'.$i, TRUE);
                if($ndeliver==''){
                    $ndeliver=null;
                }
                if($ndeliverx==''){
                    $ndeliverx=null;
                }
                $this->mmaster->deletedetail($ispb, $iarea, $iproduct, $iproductgrade, $iproductmotif);
                if($norder>0){
                    $this->mmaster->insertdetail($ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i);
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update SPB Reguler Area '.$iarea.' No:'.$ispb);
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
    /*********************************| END EDIT SPB REGULER |****************************************/

    /*********************************| START EDIT SPB MO |****************************************/   
    public function editspbmo(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ispb          = $this->uri->segment(4);
            $iarea         = $this->uri->segment(5);
            $ipricegroup   = $this->uri->segment(6);
            $xarea         = $this->uri->segment(7);
            $dfrom         = $this->uri->segment(8);
            $dto           = $this->uri->segment(9);
            $qnilaispb     = $this->mmaster->bacadetailnilaispb($ispb,$iarea,$ipricegroup);
            if($qnilaispb->num_rows()>0){
                $row_nilaispb  = $qnilaispb->row();
                $nilaispb      = $row_nilaispb->nilaispb;
            }else{
                $nilaispb = 0;
            }
            $qnilaiorderspb  = $this->mmaster->bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup);
            if($qnilaiorderspb->num_rows()>0){
                $row_nilaiorderspb  = $qnilaiorderspb->row();
                $nilaiorderspb      = $row_nilaiorderspb->nilaiorderspb;
            }else{
                $nilaiorderspb  = 0;
            }
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title']." MO",
                'title_list'    => 'List '.$this->global['title']." MO",
                'ispb'          => $ispb,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'xarea'         => $xarea,
                'i_menu'        => $this->i_menu,
                'departement'   => $this->session->userdata('i_departement'),
                'nilaispb'      => $nilaispb,
                'nilaiorderspb' => $nilaiorderspb,
                'group'         => $this->mmaster->bacagroup(),
                'isi'           => $this->mmaster->bacaspb($ispb,$iarea),
                'detail'        => $this->mmaster->bacadetailspb($ispb,$iarea,$ipricegroup)
            );   
        }

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformeditspbmo', $data);
    }
    /*********************************| END EDIT SPB MO |****************************************/


    /*********************************| START EDIT SPB PELANGGAN BARU |***********************************/    
    public function editcustomernew(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ispb           = $this->uri->segment(4);
            $iarea          = $this->uri->segment(5);
            $ipricegroup    = $this->uri->segment(6);
            $xarea          = $this->uri->segment(7);
            $dfrom          = $this->uri->segment(8);
            $dto            = $this->uri->segment(9);
            $queryitem      = $this->db->query("select * from tm_spb_item where i_spb = '$ispb' and i_area='$iarea'");
            $qnilaiorderspb = $this->mmaster->bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup);
            if($qnilaiorderspb->num_rows()>0){
                $row_nilaiorderspb  = $qnilaiorderspb->row();
                $nilaiorderspb  = $row_nilaiorderspb->nilaiorderspb;
            }else{
                $nilaiorderspb  = 0;
            }
            $data   = array(
                'folder'            => $this->global['folder'],
                'title'             => "Edit ".$this->global['title'],
                'title_list'        => 'List '.$this->global['title'],
                'ispb'              => $ispb,
                'jmlitem'           => $queryitem->num_rows(),
                'xarea'             => $xarea,
                'ipricegroup'       => $ipricegroup,
                'dfrom'             => $dfrom,
                'dto'               => $dto,
                'i_menu'            => $this->i_menu,
                'departement'       => $this->session->userdata('username'),
                'nilaiorderspb'     => $nilaiorderspb,
                'area'              => $this->mmaster->bacaareax(),
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
                'pricegroup'        => $this->mmaster->bacapricegroup(),
                'isi'               => $this->mmaster->bacacustomernew($ispb,$iarea)->row(),
                'isispb'            => $this->mmaster->bacaspbcustomernew($ispb,$iarea),
                'isidetail'         => $this->mmaster->bacadetailcustomernew($ispb,$iarea,$ipricegroup)->result()
            );   
            
        }

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformeditcustomernew', $data);
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

    public function updatespbcustomernew(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb                       = $this->input->post('ispb', TRUE);
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
        $esalesmanname              = $this->input->post('esalesmanname', TRUE);
        $dsurvey                    = $this->input->post('dsurvey', TRUE);
        $icity                      = $this->input->post('icity', TRUE);
        if($dsurvey!=''){
            $dsurvey = date('Y-m-d', strtotime($dsurvey));
        }else{
            $dsurvey = date('Y-m-d');
        }
        $nvisitperiod               = $this->input->post('nvisitperiod', TRUE);
        if($nvisitperiod=='') {
            $nvisitperiod=0;
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
            $fparkir = 't';
        }else{
            $fparkir = 'f';
        }
        $fkuli                      = $this->input->post('fkuli', TRUE);
        if($fkuli!=''){
            $fkuli = 't';
        }else{
            $fkuli = 'f';
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
            $fspbpkp = 't';
        }else{
            $fspbpkp = 'f';
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
        $ecustomerremark            = $this->input->post('ecustomerremark', TRUE);
        $ecustomerpayment           = $this->input->post('ecustomerpayment', TRUE);
        $ecustomerpriority          = $this->input->post('ecustomerpriority', TRUE);
        $fkontrabon                 = $this->input->post('fkontrabon', TRUE);
        if($fkontrabon!=''){
            $fkontrabon = 't';
        }else{
            $fkontrabon = 'f';
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
            $thbl           = date('Ym', strtotime($dspb));
            $dspb           = date('Y-m-d', strtotime($dspb));
            $dspbreceive    = $dspb;
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
            $ispbpo=' ';
        }
        if($ispbpo==' ') {
            $fspbop='f'; 
        }else {
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
        $eremarkx                   = $this->input->post('eremarkx', TRUE);
        $fspbplusppn                = 't';
        $fspbplusdiscount           = 'f';
        $ispbold                    = $this->input->post('ispbold',TRUE);
        $jml                        = $this->input->post('jml', TRUE);
        $ecustomerrefference        = $this->input->post('ecustomerrefference', TRUE);
        for($i=1;$i<=$jml;$i++){
            $iproductgroup          = $this->input->post('iproductgroup'.$i, TRUE);
            break;
        }
        if (($ecustomername!= '') && ($dspb!='') && ($iarea!='') && ($jml>0) && ($ipricegroup!='') && ($nspbtoplength!='') && ($dsurvey!='') && ($isalesman!='') && ($ncustomerdiscount!='') && ($nvisitperiod!='') && ($icustomergroup!='') && ($icustomerproducttype!='') && ($icustomerstatus!='') && ($icustomergrade!='') && ($icustomerservice!='') && ($icustomersalestype!='') && ($ipaymentmethod!='')){
            $this->db->trans_begin();
            $this->mmaster->deleteheaderspb($ispb, $iarea);
            $this->mmaster->insertheadercustomernew($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2,$vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment, $ispbold, $eremarkx,$iproductgroup);
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                if ($iproduct!=''||$iproduct!=null) {
                    $iproductstatus = $this->input->post('iproductstatus'.$i, TRUE);
                    $iproductgrade  = 'A';
                    $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                    $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice     = $this->input->post('vproductretail'.$i, TRUE);
                    $vunitprice     = str_replace(',','',$vunitprice);
                    $norder         = $this->input->post('norder'.$i, TRUE);
                    $eremark        = $this->input->post('eremark'.$i, TRUE);
                    if($norder>0){
                        $this->mmaster->deletedetailspb( $ispb,$iarea,$iproduct,$iproductgrade,$iproductmotif);
                        $this->mmaster->insertdetailcustomernew( $ispb,$iarea,$iproduct,$iproductgrade,$eproductname,$norder,null,$vunitprice,$iproductmotif,$eremark,$i,$iproductstatus);
                    }
                }
            }
            $this->mmaster->deleteheadercustomernew($ispb, $iarea);
            $this->mmaster->insertcustomernew($ispb,$icustomer,$iarea,$isalesman,$esalesmanname,$dsurvey,$nvisitperiod,$fcustomernew,$ecustomername,$ecustomeraddress,$ecustomersign,$ecustomerphone,$ert1,$erw1,$epostal1,$ecustomerkelurahan1,$ecustomerkecamatan1,$ecustomerkota1,$ecustomerprovinsi1,$efax1,$ecustomermonth,$ecustomeryear,$ecustomerage,$eshopstatus,$ishopstatus,$nshopbroad,$ecustomerowner,$ecustomerownerttl,$emarriage,$imarriage,$ejeniskelamin,$ijeniskelamin,$ereligion,$ireligion,$ecustomerowneraddress,$ecustomerownerphone,$ecustomerownerhp,$ecustomerownerfax,$ecustomerownerpartner,$ecustomerownerpartnerttl,$ecustomerownerpartnerage,$ert2,$erw2,$epostal2,$ecustomerkelurahan2,$ecustomerkecamatan2,$ecustomerkota2,$ecustomerprovinsi2,$ecustomersendaddress,$ecustomersendphone,$etraversed,$itraversed,$fparkir,$fkuli,$eekspedisi1,$eekspedisi2,$ert3,$erw3,$epostal3,$ecustomerkota3,$ecustomerprovinsi3,$ecustomerpkpnpwp,$fspbpkp,$ecustomernpwpname,$ecustomernpwpaddress,$ecustomerclassname,$icustomerclass,$epaymentmethod,$ipaymentmethod,$ecustomerbank1,$ecustomerbankaccount1,$ecustomerbankname1,$ecustomerbank2,$ecustomerbankaccount2,$ecustomerbankname2,$ekompetitor1,$ekompetitor2,$ekompetitor3,$nspbtoplength,$ncustomerdiscount,$epricegroupname,$ipricegroup,$nline,$fkontrabon,$ecall,$icall,$ekontrabonhari,$ekontrabonjam1,$ekontrabonjam2,$etagihhari,$etagihjam1,$etagihjam2,$icustomergroup,$icustomerplugroup,$icustomerproducttype,$icustomerspecialproduct,$icustomerstatus,$icustomergrade,$icustomerservice,$icustomersalestype,$ecustomerownerage,$ecustomerrefference,$iretensi,$icity,$ecustomercontact,$ecustomercontactgrade,$ecustomermail,$inik);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update SPB Pelanggan Baru Kodelang : '.$icustomer.' Area : '.$iarea.' No :'.$ispb);
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
    /*********************************| END EDIT SPB PELANGGAN BARU |***********************************/    
}

/* End of file Cform.php */
