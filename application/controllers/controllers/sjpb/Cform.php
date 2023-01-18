<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020203';

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
        $iarea     = $this->mmaster->cekuser($username, $idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea($iarea, $username, $idcompany),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getspmb(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $iarea  = $this->input->get('iarea', FALSE);
            $tgl    = date('Y-m-d'); /*pendefinisian tanggal awal*/
            $dfrom  = date('Y-m-d', strtotime('-3 month', strtotime($tgl))); /*operasi penjumlahan*/ 
            $data   = $this->mmaster->getspmb($cari,$iarea,$dfrom);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_spmb,  
                    'text'  => $kuy->i_spmb." - ".$kuy->d_spmb
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailspmb(){
        header("Content-Type: application/json", true);
        $ispmb  = $this->input->post('ispmb', FALSE);
        $iarea  = $this->input->post('iarea', FALSE);
        $query  = array(
            'detail' => $this->mmaster->getdetailspmb($ispmb, $iarea)->result_array()
        );
        echo json_encode($query);  
    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $iarea  = $this->input->get('iarea', FALSE);
            $data   = $this->mmaster->getcustomer($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name." - ".$kuy->i_spg
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iproduct') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iproduct   = strtoupper($this->input->get('iproduct'));
            var_dump($iproduct);
            $data       = $this->mmaster->getproduct($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,  
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailproduct(){
        header("Content-Type: application/json", true);        
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $tgl    = date('d-m-Y');
        $tmp    = explode("-",$tgl);
        $thak   = substr($tmp[2],2,2);
        $blak   = $tmp[1];
        $blaw   = $tmp[1];
        $thaw=$thak;
        for($z=1;$z<=3;$z++){
            settype($blaw,'integer');
            $blaw=$blaw-1;
            if($blaw==0){
                $blaw=12;
                $thaw=$thaw-1;
            }
        }
        settype($blaw,'string');
        if(strlen($blaw)==1){
            $blaw='0'.$blaw;
        }
        $peraw=$thaw.$blaw;
        $perak=$thak.$blak;
        $fpaw ='FP-'.$peraw;
        $fpak ='FP-'.$perak;
        $iproduct = $this->input->post('iproduct');
        $data = $this->mmaster->getdetailproduct($iproduct,$fpaw,$fpak,$username,$idcompany);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dsjpb  = $this->input->post('dsj', TRUE);
        if($dsjpb!=''){
            $tmp=explode("-",$dsjpb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjpb=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iarea  = $this->input->post('iarea', TRUE);
        $ispmb  = $this->input->post('ispmb', TRUE);
        $dspmb  = $this->input->post('dspmb', TRUE);
        if($dspmb!=''){
            $tmp=explode("-",$dspmb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspmb=$th."-".$bl."-".$hr;
        }
        $eareaname  = $this->input->post('eareaname', TRUE);
        $icustomer  = $this->input->post('icustomer',TRUE);
        $ispg       = $this->input->post('ispg',TRUE);
        $vsjpb      = $this->input->post('vsj',TRUE);
        $vsjpb      = str_replace(',','',$vsjpb);
        $jml        = $this->input->post('jml', TRUE);
        if($dsjpb!='' && $ispmb!='' && $icustomer!=''){
            $gaono = true;
            for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i, TRUE);
                if($cek=='on'){
                    $gaono = false;
                }
                if(!$gaono) break;
            }
            if(!$gaono){
                $this->db->trans_begin();
                $istore             = $this->input->post('istore', TRUE);
                if($istore=='PB'){
                    $istorelocation = '00';
                }else{
                    $istorelocation = 'PB';     
                }
                $istorelocationbin  = '00';
                $areasj             = $iarea;
                $isjpb              = $this->mmaster->runningnumbersj($areasj,$thbl);
                $this->mmaster->insertsjpb($isjpb, $ispmb, $icustomer, $iarea, $ispg, $dsjpb, $vsjpb);
                for($i=1;$i<=$jml;$i++){
                    $cek=$this->input->post('chk'.$i, TRUE);
                    if($cek=='on'){
                        $iproduct       = substr($this->input->post('iproduct'.$i, TRUE),0,7);
                        $ipricegroup    = substr($this->input->post('iproduct'.$i, TRUE),7,2);
                        $iproductgrade  = 'A';
                        $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                        $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                        $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                        $vunitprice     = str_replace(',','',$vunitprice);
                        $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                        $ndeliver       = str_replace(',','',$ndeliver);
                        $norder         = $this->input->post('norder'.$i, TRUE);
                        $norder         = str_replace(',','',$norder);
                        if($ndeliver>0){
                            $this->mmaster->insertsjpbdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver, $vunitprice,$isjpb,$iarea,$i,$dsjpb,$ipricegroup);
                            $trans = $this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                            if(isset($trans)){
                                foreach($trans as $itrans){
                                    $q_aw  = $itrans->n_quantity_awal;
                                    $q_ak  = $itrans->n_quantity_akhir;
                                    $q_in  = $itrans->n_quantity_in;
                                    $q_out = $itrans->n_quantity_out;
                                    break;
                                }
                            }else{
                                $trans = $this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                                if(isset($trans)){
                                    foreach($trans as $itrans)
                                    {
                                        $q_aw  = $itrans->n_quantity_stock;
                                        $q_ak  = $itrans->n_quantity_stock;
                                        $q_in  = 0;
                                        $q_out = 0;
                                        break;
                                    }
                                }else{
                                    $q_aw   = 0;
                                    $q_ak   = 0;
                                    $q_in   = 0;
                                    $q_out  = 0;
                                }
                            }
                            $this->mmaster->inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isjpb,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                            $th = substr($dsjpb,0,4);
                            $bl = substr($dsjpb,5,2);
                            $emutasiperiode=$th.$bl;
                            if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                                $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode);
                            }else{
                                $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode,$q_aw);
                            }
                            if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                                $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$q_ak);
                            }else{
                                $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver,$q_aw);
                            }
                        }
                    }
                }
                if(($this->db->trans_status()=== False)){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Input SJPB No:'.$isjpb);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isjpb
                    );
                }
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
