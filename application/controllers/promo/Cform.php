<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10302';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'promo'     => $this->mmaster->bacajenis(),
            'group'     => $this->mmaster->bacagroup()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getgroup(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('ipromotype') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $ipromotype = strtoupper($this->input->get('ipromotype', FALSE));
            $data       = $this->mmaster->getgroup($cari,$ipromotype);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_price_group,  
                    'text'  => $kuy->i_price_group
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function product(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->product($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->kode,  
                    'text'  => $kuy->kode." - ".$kuy->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailp(){
        header("Content-Type: application/json", true);
        $iproduct = strtoupper($this->input->post('iproduct', FALSE));
        $data = $this->mmaster->getproduct($iproduct);
        echo json_encode($data->result_array());  
    }

    public function customer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->customer($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailc(){
        header("Content-Type: application/json", true);
        $icustomer = strtoupper($this->input->post('icustomer', FALSE));
        $data = $this->mmaster->getcustomer($icustomer);
        echo json_encode($data->result_array());  
    }

    public function customergroup(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->customergroup($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer_group,  
                    'text'  => $kuy->i_customer_group." - ".$kuy->e_customer_groupname
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailg(){
        header("Content-Type: application/json", true);
        $icustomergroup = strtoupper($this->input->post('icustomergroup', FALSE));
        $data = $this->mmaster->getcustomergroup($icustomergroup);
        echo json_encode($data->result_array());  
    }

    public function area(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->area($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_area,  
                    'text'  => $kuy->i_area." - ".$kuy->e_area_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetaila(){
        header("Content-Type: application/json", true);
        $iarea = strtoupper($this->input->post('iarea', FALSE));
        $data  = $this->mmaster->getarea($iarea);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ipromo     = $this->input->post('ipromo', TRUE);
        $dpromo     = $this->input->post('dpromo', TRUE);
        $ipromotype = $this->input->post('ipromotype', TRUE);
        if($dpromo!=''){
            $tmp=explode("-",$dpromo);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dpromo=$th."-".$bl."-".$hr;
        }
        $dpromostart    = $this->input->post('dpromostart', TRUE);
        if($dpromostart!=''){
            $tmp=explode("-",$dpromostart);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dpromostart=$th."-".$bl."-".$hr;
        }
        $dpromofinish   = $this->input->post('dpromofinish', TRUE);
        if($dpromofinish!=''){
            $tmp=explode("-",$dpromofinish);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dpromofinish=$th."-".$bl."-".$hr;
        }
        $ipricegroup        = $this->input->post('ipricegroup', TRUE);
        $npromodiscount1    = $this->input->post('npromodiscount1', TRUE);
        $npromodiscount2    = $this->input->post('npromodiscount2', TRUE);
        $epromoname         = $this->input->post('epromoname', TRUE);
        $productgroup       = $this->input->post('productgroup',TRUE);
        if ($productgroup=='sp') {
            $fallproduct = "t";
        }else{
            $fallproduct = "f";
        }
        if ($productgroup=='00') {
            $fallreguler = "t";
        }else{
            $fallreguler = "f";
        }
        if ($productgroup=='01') {
            $fallbaby = "t";
        }else{
            $fallbaby = "f";
        }
        if ($productgroup=='02') {
            $fallnb = "t";
        }else{
            $fallnb = "f";
        }
        $fallcustomer     = $this->input->post('fallcustomer',TRUE);
        if($fallcustomer!=''){
            $fallcustomer = "t";
        }else{
            $fallcustomer = "f";
        }
        $fcustomergroup     = $this->input->post('fcustomergroup',TRUE);
        if($fcustomergroup!=''){
            $fcustomergroup = "t";
        }else{
            $fcustomergroup = "f";
        }
        $fallarea       = $this->input->post('fallarea',TRUE);
        if($fallarea!=''){
            $fallarea = "t";
        }else{
            $fallarea = "f";
        }
        $jmlp       = $this->input->post('jmlp', TRUE);
        $jmlc       = $this->input->post('jmlc', TRUE);
        $jmlg       = $this->input->post('jmlg', TRUE);
        $jmla       = $this->input->post('jmla', TRUE);
        if(($dpromo!='' && $ipromotype!='' && $dpromostart!='' && $dpromofinish!='' && $epromoname!='')){
            $this->db->trans_begin();
            $ipromo =$this->mmaster->runningnumber();
            if(($jmlp!='0' || $jmlc!='0' || $jmlg!='0' || $jmla!='0')){
                $this->mmaster->insertheader($ipromo,$dpromo,$ipromotype,$dpromostart,$dpromofinish,$epromoname,$fallproduct,$fallcustomer,$fcustomergroup,$npromodiscount1,$npromodiscount2,$fallbaby,$fallreguler,$fallarea,$ipricegroup,$fallnb);
                if($jmlp!='0'){
                    for($i=1;$i<=$jmlp;$i++){                      
                        $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                        $iproductgrade    = 'A';
                        $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                        $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                        $vunitprice       = $this->input->post('vunitprice'.$i, TRUE);
                        $vunitprice2      = str_replace(',','',$vunitprice);
                        $nquantitymin     = $this->input->post('nquantitymin'.$i, TRUE);
                        $this->mmaster->insertdetailp($ipromo,$ipromotype,$iproduct,$iproductgrade,$eproductname,$nquantitymin,$vunitprice2,$iproductmotif);
                    }
                }
                if($jmlc!='0'){
                    for($i=1;$i<=$jmlc;$i++){                      
                        $icustomer        = $this->input->post('icustomer'.$i, TRUE);                      
                        $ecustomername    = $this->input->post('ecustomername'.$i, TRUE);
                        $ecustomeraddress = $this->input->post('ecustomeraddress'.$i, TRUE);
                        $qu = $this->mmaster->areacus($icustomer);
                        if ($qu->num_rows() > 0){
                            foreach($qu->result() as $w){                           
                                $this->mmaster->insertdetailc($ipromo,$ipromotype,$icustomer,$ecustomername,$ecustomeraddress,$w->i_area);
                            }
                        }
                    }
                }
                if($jmlg!='0'){
                    for($i=1;$i<=$jmlg;$i++){                      
                        $icustomergroup       = $this->input->post('icustomergroup'.$i, TRUE);                      
                        $ecustomergroupname   = $this->input->post('ecustomergroupname'.$i, TRUE);
                        $qu = $this->mmaster->areacusgroup($icustomergroup);
                        if ($qu->num_rows() > 0){
                            foreach($qu->result() as $w){                           
                                $this->mmaster->insertdetailg($ipromo,$ipromotype,$icustomergroup,$ecustomergroupname,$w->i_area);
                            }
                        }
                    }
                }
                if($jmla!='0'){             
                    for($i=1;$i<=$jmla;$i++){                      
                        $iarea        = $this->input->post('iarea'.$i, TRUE);
                        $eareaname    = $this->input->post('eareaname'.$i, TRUE);
                        $this->mmaster->insertdetaila($ipromo,$ipromotype,$iarea,$eareaname);
                    }
                }
            }else if(($jmlp=='0' && $jmlc=='0' && $jmlg=='0' && $jmla=='0' && $fallproduct=='t' && $fallcustomer=='t')||($jmlp=='0' && $jmlc=='0' && $jmlg=='0' && $jmla=='0' && $fallbaby=='t' && $fallcustomer=='t')||($jmlp=='0' && $jmlc=='0' && $jmlg=='0' && $jmla=='0' && $fallreguler=='t' && $fallcustomer=='t')){
                $this->mmaster->insertheader($ipromo,$dpromo,$ipromotype,$dpromostart,$dpromofinish,$epromoname,$fallproduct,$fallcustomer,$fcustomergroup,$npromodiscount1,$npromodiscount2,$fallbaby,$fallreguler,$fallarea,$ipricegroup,$fallnb);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Promo No:'.$ipromo);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ipromo
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
