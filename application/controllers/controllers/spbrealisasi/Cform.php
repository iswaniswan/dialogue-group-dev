<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020302';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        echo $this->mmaster->data($this->global['folder'], $siareana, $username, $id_company);
    }

    public function edit(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        $ispb    = $this->uri->segment(4);
        $iarea   = $this->uri->segment(5);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'isi'       => $this->mmaster->baca($ispb,$iarea),
            'detail'    => $this->mmaster->bacadetail($ispb,$iarea)
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function getstore(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $fspbstokdaerah  = $this->input->get('fspbstokdaerah');
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getstore($fspbstokdaerah, $cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_store,  
                    'text'  => $row->i_store.' - '.$row->e_store_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetstore(){
        header("Content-Type: application/json", true);
        $istore = $this->input->post('istore');
        $fspbstokdaerah = $this->input->post('fspbstokdaerah');
        $data = $this->mmaster->getdetstore($istore, $fspbstokdaerah);
        echo json_encode($data->result_array());  
    }

    public function realisasi(){
        $username           = $this->session->userdata('username');
        $id_company         = $this->session->userdata('id_company');
        $siareana           = $this->mmaster->cekuser($username, $id_company);
        $ispb               = $this->input->post('ispb', TRUE);
        $iarea              = $this->input->post('iarea', TRUE);
        $istore             = $this->input->post('istore', TRUE);
        $estorename         = $this->input->post('estorename', TRUE);
        $istorelocation     = $this->input->post('istorelocation', TRUE);
        $estorelocationname = $this->input->post('estorelocationname', TRUE);
        $dspb   = $this->input->post('dspb');
        if ($dspb!='') {    
            $tmp=explode("-",$dspb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $thbl=$th.$bl;
        }else{
            $thbl = date('Ym');
        }
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'isi'               => $this->mmaster->baca($ispb,$iarea),
            'detail'            => $this->mmaster->bacadetail($ispb,$iarea),
            'istore'            => $istore,
            'estorename'        => $estorename,
            'istorelocation'    => $istorelocation,
            'estorelocationname'=> $estorelocationname,
            'thbl'              => $thbl
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb = $this->input->post('ispb', TRUE);
        $dspb = $this->input->post('dspb', TRUE);
        $dsj  = $this->input->post('dsj', TRUE);
        if($dsj==''){
            $dsj=null;        
            $isj=null;
        }
        $iarea          = $this->input->post('iarea', TRUE);
        $istore         = $this->input->post('istore', TRUE);
        $istorelocation = $this->input->post('istorelocation', TRUE);
        $estorename     = $this->input->post('estorename', TRUE);
        $isalesman      = $this->input->post('isalesman', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        if($istore=='AA'){
            $fspbstockdaerah    = 'f';
            $fspbsiapnotagudang = 'f';
            $fspbsiapnotasales  = 'f';
        }else{
            $fspbstockdaerah    = 't';
            $fspbsiapnotagudang = 't';
            $fspbsiapnotasales  = 't';
        }

        $ispbpo                 = $this->input->post('ispbpo', TRUE);
        $ipricegroup            = $this->input->post('ipricegroup', TRUE);
        $dspbreceive            = $this->input->post('$dspbreceive', TRUE);
        $fspbop                 = 'f';
        $ecustomerpkpnpwp       = $this->input->post('ecustomerpkpnpwp',TRUE);
        if($ecustomerpkpnpwp!=''){
            $fspbpkp        = 't';
        }else{
            $fspbpkp        = 'f';
            $ecustomerpkpnpwp=null;
        }
        $fspbconsigment         = $this->input->post('fspbconsigment',TRUE);
        if($fspbconsigment!=''){
            $fspbconsigment = "t";
        }else{
            $fspbconsigment = "f";
        }
        $fspbplusppn            = $this->input->post('fspbplusppn',TRUE);
        $fspbplusdiscount       = $this->input->post('fspbplusdiscount',TRUE);
        $nspbtoplength          = $this->input->post('nspbtoplength', TRUE);
        $nspbtoplength          = str_replace(',','',$nspbtoplength);
        $fspbvalid              = 'f';
        $fspbcancel             = 'f';
        $nspbdiscount1          = $this->input->post('ncustomerdiscount1',TRUE);
        $nspbdiscount2          = $this->input->post('ncustomerdiscount2',TRUE);
        $nspbdiscount3          = $this->input->post('ncustomerdiscount3',TRUE);
        $vspbdiscount1          = $this->input->post('vcustomerdiscount1',TRUE);
        $vspbdiscount2          = $this->input->post('vcustomerdiscount2',TRUE);
        $vspbdiscount3          = $this->input->post('vcustomerdiscount3',TRUE);
        $vspbdiscounttotal      = $this->input->post('vspbdiscounttotal',TRUE);
        $vspbdiscounttotalafter = $this->input->post('vspbdiscounttotalafter',TRUE);
        if($vspbdiscounttotalafter==''){
            $vspbdiscounttotalafter=0;
        }
        $vspbgross          = $this->input->post('vspb',TRUE);
        $vspbafter          = $this->input->post('vspbafter',TRUE);
        if($vspbafter==''){
            $vspbafter=0;
        }
        $nspbdiscount1      = str_replace(',','',$nspbdiscount1);
        $nspbdiscount2      = str_replace(',','',$nspbdiscount2);
        $nspbdiscount3      = str_replace(',','',$nspbdiscount3);
        $vspbdiscount1      = str_replace(',','',$vspbdiscount1);
        $vspbdiscount2      = str_replace(',','',$vspbdiscount2);
        $vspbdiscount3      = str_replace(',','',$vspbdiscount3);
        $vspbdiscounttotal  = str_replace(',','',$vspbdiscounttotal);
        $vspbgross          = str_replace(',','',$vspbgross);
        $vspbdiscounttotalafter = str_replace(',','',$vspbdiscounttotalafter);
        $vspbafter          = str_replace(',','',$vspbafter);
        $vspbnetto          = $vspbgross-$vspbdiscounttotal;
        $vspb               = $vspbnetto;
        $jml                = $this->input->post('jml', TRUE);
        if($istore!=''){
            $this->db->trans_begin();
            if($istore=='AA'){                
                $this->mmaster->updateheader($dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbvalid, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment, $ispb,$iarea,$istore,$istorelocation,$fspbstockdaerah, $fspbsiapnotagudang, $fspbcancel,$fspbsiapnotasales, $vspbdiscounttotalafter,$vspbafter);
                $dbbk               = $dspb;
                $istore             = 'AA';
                $istorelocation     = '01';
                $istorelocationbin  = '00';
                $eremark            = 'SPB';
                $ibbktype           = '05';
            }else{
                $isj    = '';
                if($dsj!=''){
                    $tmp=explode("-",$dsj);
                    $th=$tmp[2];
                    $bl=$tmp[1];
                    $hr=$tmp[0];
                    $dsj=$th."-".$bl."-".$hr;
                }
                $this->mmaster->updateheadernsj($ispb,$iarea,$istore,$istorelocation,$fspbstockdaerah, $fspbsiapnotagudang, $fspbcancel, $fspbvalid,$fspbsiapnotasales,$isj,$dsj);
            }
            $langsung=true;
            for($i=1;$i<=$jml;$i++){              
                $iproduct           = $this->input->post('iproduct'.$i, TRUE);              
                $iproductstatus     = $this->input->post('iproductstatus'.$i, TRUE);              
                $iproductgrade      = $this->input->post('grade'.$i, TRUE);
                $eproductname       = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice         = $this->input->post('vproductretail'.$i, TRUE);
                $vunitprice         = str_replace(',','',$vunitprice);
                $norder             = $this->input->post('norder'.$i, TRUE);
                $norder             = str_replace(',','',$norder);
                $ndeliver           = $this->input->post('ndeliver'.$i, TRUE);
                $ndeliver           = str_replace(',','',$ndeliver);
                $nstock             = $this->input->post('nstock'.$i, TRUE);
                $nstock             = str_replace(',','',$nstock);
                $iproductmotif      = $this->input->post('motif'.$i, TRUE);
                $eremark            = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($iproduct, $iarea, $iproductgrade, $ispb, $iproductmotif);
                $this->mmaster->insertdetail($ispb,$iarea,$iproduct,$iproductgrade,$eproductname,$norder, $vunitprice,$ndeliver,$iproductmotif,$nstock,$eremark,$i,$iproductstatus);

                if( ($ndeliver<$norder) && ($iproductstatus!='4')  && ($istore=='AA') ){ 
                    $langsung=false;
                }
            }
            if($langsung==true) {
                $this->mmaster->langsungnota($ispb,$iarea);
            }else{
                $this->mmaster->lansgungop($ispb,$iarea);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Approve SPB Area '.$iarea.' No:'.$ispb);
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
