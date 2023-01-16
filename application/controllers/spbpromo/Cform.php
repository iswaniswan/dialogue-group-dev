<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1030102';

    public function __construct(){
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
        $idcompany  = $this->session->userdata('id_company');
		$username 	= $this->session->userdata('username');
		$dspb 		= date('Y-m-d');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'customer'  => $this->mmaster->bacapelanggan(),
            'group'     => $this->mmaster->bacagroup(),
            'promo'     => $this->mmaster->bacapromo($username, $idcompany, $dspb)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

	public function getarea(){
        $ipromo = $this->input->post('ipromo');
        $query  = $this->mmaster->getpromo($ipromo);
        if ($query->num_rows()>0) {
            foreach($query->result() as $pro){
                $a = $pro->f_all_area;
            }
        }else{
            die();
        }
        $qarea = $this->mmaster->cariarea($ipromo, $a);
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

    public function getpelanggan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari 	 = strtoupper($this->input->get('q'));
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
            	$c    	 = $pro->f_all_customer;
            	$g    	 = $pro->f_customer_group;
            	$type 	 = $pro->i_promo_type;
            	$disc1   = $pro->n_promo_discount1;
            	$disc2   = $pro->n_promo_discount2;
            }
            $data 	     = $this->mmaster->getpelanggan($cari, $iarea, $ipromo, $c, $g, $type, $per);
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

    public function getdetailpel(){
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
        	$c    	 = $pro->f_all_customer;
        	$g    	 = $pro->f_customer_group;
        	$type 	 = $pro->i_promo_type;
        	$disc1   = $pro->n_promo_discount1;
        	$disc2   = $pro->n_promo_discount2;
        }      
        $data = $this->mmaster->getdetailpel($icustomer, $iarea, $ipromo, $c, $g, $type, $per, $disc1, $disc2);
        echo json_encode($data->result_array());  
    }

    public function getsales(){
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
            $data = $this->mmaster->getsales($iarea, $cari, $per);
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

    public function getdetailsal(){
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
        $data = $this->mmaster->getdetailsal($isalesman, $per, $iarea);      
        echo json_encode($data->result_array());  
    }

    public function getdetailbar(){
        header("Content-Type: application/json", true);
        $kdharga  = strtoupper($this->input->post('kdharga', FALSE));
        $ipromo   = strtoupper($this->input->post('ipromo', FALSE));
        $group    = $this->input->post('group', FALSE);
        $iproduct = $this->input->post('iproduct', FALSE);
        $kdgroup  = $this->input->post('kdgroup', FALSE);
        $data = $this->mmaster->bacaproductx($kdharga,$iproduct,$ipromo,$kdgroup,$group);
        echo json_encode($data->result_array());  
    }

    public function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $kdharga = strtoupper($this->input->get('kdharga', FALSE));
            $ipromo  = strtoupper($this->input->get('ipromo', FALSE));
            $groupbarang = $this->input->get('group', FALSE);
            $kdgroup = $this->input->get('kdgroup', FALSE);
            $data = $this->mmaster->bacaproduct($cari,$kdharga,$groupbarang,$ipromo,$kdgroup);
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

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb    = $this->input->post('ispb', TRUE);
        $dspb    = $this->input->post('dspb', TRUE);         
        if($dspb!=''){   
            $tmp=explode("-",$dspb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspb=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
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

        if($ispbpo=='') $ispbpo=' ';
        $nspbtoplength      = $this->input->post('nspbtoplength', TRUE);
        $isalesman          = $this->input->post('isalesmanx',TRUE);
        $esalesmanname      = $this->input->post('esalesmannamex',TRUE);
        $ipricegroup        = $this->input->post('ipricegroup',TRUE);
        $inota              = $this->input->post('inota',TRUE);
        $dspbreceive        = $this->input->post('dspbreceive',TRUE);
        $fspbop             = 'f';

        $ecustomerpkpnpwp   = $this->input->post('ecustomerpkpnpwp',TRUE);
        if($ecustomerpkpnpwp!=''){
            $fspbpkp = 't';
        }else{
            $fspbpkp = 'f';
            $ecustomerpkpnpwp=null;
        }
        $fspbconsigment      = $this->input->post('fspbconsigment',TRUE);
        if($fspbconsigment!=''){
            $fspbconsigment ="t";
        }else{
            $fspbconsigment ="f";
        }
        $fspbplusppn        = $this->input->post('fspbplusppn',TRUE);
        $fspbplusdiscount   = $this->input->post('fspbplusdiscount',TRUE);
        $ispbprogram   		= $this->input->post('ipromo',TRUE);
        $fspbprogram        = 't';
        $fspbvalid          = 'f';
        $fspbsiapnotagudang = 'f';
        $fspbcancel         = 'f';

        $nspbdiscount1      = $this->input->post('ncustomerdiscount1',TRUE);
        $nspbdiscount2      = $this->input->post('ncustomerdiscount2',TRUE);
        $nspbdiscount3      = $this->input->post('ncustomerdiscount3',TRUE);
        $nspbdiscount4      = $this->input->post('ncustomerdiscount4',TRUE);
        $vspbdiscount1      = $this->input->post('vcustomerdiscount1',TRUE);
        $vspbdiscount2      = $this->input->post('vcustomerdiscount2',TRUE);
        $vspbdiscount3      = $this->input->post('vcustomerdiscount3',TRUE);
        $vspbdiscount4      = $this->input->post('vcustomerdiscount4',TRUE);
        $vspbdiscounttotal  = $this->input->post('vspbdiscounttotal',TRUE);
        $vspb               = $this->input->post('vspb',TRUE);
        $nspbdiscount1      = str_replace(',','',$nspbdiscount1);
        $nspbdiscount2      = str_replace(',','',$nspbdiscount2);
        $nspbdiscount3      = str_replace(',','',$nspbdiscount3);
        $nspbdiscount4      = str_replace(',','',$nspbdiscount4);
        $vspbdiscount1      = str_replace(',','',$vspbdiscount1);
        $vspbdiscount2      = str_replace(',','',$vspbdiscount2);
        $vspbdiscount3      = str_replace(',','',$vspbdiscount3);
        $vspbdiscount4      = str_replace(',','',$vspbdiscount4);
        $vspbdiscounttotal  = str_replace(',','',$vspbdiscounttotal);
        $vspb               = str_replace(',','',$vspb);
        $ispbold            = $this->input->post('ispbold',TRUE);
        $jml                = $this->input->post('jml', TRUE);

        if(($icustomer!='') && ($dspb!='') && ($iarea!='') && ($jml>0)){
            $bener = "false";
            $this->db->trans_begin();
            for($i=1;$i<=$jml;$i++){
            	$iproduct  		= $this->input->post('motif'.$i, TRUE);
            	$eproductname   = $this->input->post('eproductname'.$i, TRUE);
            	$norder         = $this->input->post('norder'.$i, TRUE);
            	if(($iproduct=='') || ($eproductname=='') || ($norder=='')){
            		$this->db->trans_rollback();
            		$data = array(
            			'sukses' => false
            		);
            		die;            
            	}        
            }
            $ispb =$this->mmaster->runningnumber($iarea, $thbl);
            $this->mmaster->insertheader($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1,$nspbdiscount2, $nspbdiscount3, $nspbdiscount4, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscount4, $vspbdiscounttotal, $vspb, $fspbconsigment, $ispbprogram, $ispbold, $eremarkx, $iproductgroup);
            for($i=1;$i<=$jml;$i++){              
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);        
                $iproductstatus = $this->input->post('iproductstatus'.$i, TRUE);    
                $iproductgrade  = 'A';              
                $iproductmotif  = $this->input->post('motif'.$i, TRUE);              
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);            
                $vunitprice     = $this->input->post('vproductretail'.$i, TRUE);          
                $vunitprice     = str_replace(',','',$vunitprice);              
                $norder         = $this->input->post('norder'.$i, TRUE);              
                $eremark        = $this->input->post('eremark'.$i, TRUE);              
                if($norder>0 && ($iproduct!='' || $iproduct!=null)){                
                    $this->mmaster->insertdetail( $ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,null,$vunitprice,$iproductmotif,$eremark,$i);            
                }        
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input SPB Promo Group : '.$iproductgroup.' Area '.$iarea.' No:'.$ispb);
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
