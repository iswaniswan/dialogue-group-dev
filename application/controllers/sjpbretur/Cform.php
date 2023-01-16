<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020212';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $query     = $this->mmaster->getspg($username, $idcompany);
        if($query->num_rows()>0){
            foreach($query->result() as $xx){
                $icustomer     = $xx->i_customer;
                $eareaname     = $xx->e_area_name;
                $espgname      = $xx->e_spg_name;
                $ecustomername = $xx->e_customer_name;
            }
        }else{
            $icustomer      = '';
            $eareaname      = '';
            $espgname       = '';
            $ecustomername  = '';
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'ispg'          => $username,
            'iarea'         => $iarea,
            'icustomer'     => $icustomer,
            'eareaname'     => $eareaname,
            'espgname'      => $espgname,
            'ecustomername' => $ecustomername,
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->getproduct($cari);
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

    public function getdetailproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $data     = $this->mmaster->getdetailproduct($iproduct);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dsj        = $this->input->post('dsj', TRUE);
        $thbl       = date('Ym', strtotime($dsj));
        $dsj        = date('Y-m-d', strtotime($dsj)); // Hasil Tahun Bulan Hari
        $iarea      = $this->input->post('iarea', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        $ispg       = $this->input->post('ispg', TRUE);
        $vspbnetto  = $this->input->post('vsj', TRUE);
        $vspbnetto  = str_replace(',','',$vspbnetto);
        $jml        = $this->input->post('jml', TRUE);
        if($dsj!='' && $iarea!=''){
            $gaono=true;
            for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i, TRUE);
                if($cek=='on'){
                    $gaono=false;
                }
                if(!$gaono) break;
            }
            if(!$gaono){
                $this->db->trans_begin();
                $isj = $this->mmaster->runningnumbersj($iarea,$thbl);
                $this->mmaster->insertsjheader($isj,$dsj,$iarea,$vspbnetto,$icustomer,$ispg);
                for($i=1;$i<=$jml;$i++){
                    $cek=$this->input->post('chk'.$i, TRUE);
                    if($cek=='on'){
                        $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                        $iproductgrade  = 'A';
                        $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                        $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                        $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                        $vunitprice     = str_replace(',','',$vunitprice);
                        $nretur         = $this->input->post('nretur'.$i, TRUE);
                        $nretur         = str_replace(',','',$nretur);
                        $nreceive       = $this->input->post('nreceive'.$i, TRUE);
                        $nreceive       = str_replace(',','',$nreceive);
                        $eremark        = $this->input->post('eremark'.$i, TRUE);
                        if($eremark==''){
                            $eremark=null;
                        }
                        if($nretur>0){
                            $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,$vunitprice,$isj,$dsj,$iarea,$eremark,$i);
                            $trans = $this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$icustomer);
                            if(isset($trans)){
                                foreach($trans as $itrans){
                                    $q_aw =$itrans->n_quantity_stock;
                                    $q_ak =$itrans->n_quantity_stock;
                                    $q_in =0;
                                    $q_out=0;
                                    break;
                                }
                            }else{
                                $q_aw=0;
                                $q_ak=0;
                                $q_in=0;
                                $q_out=0;
                            }
                            $th=substr($dsj,0,4);
                            $bl=substr($dsj,5,2);
                            $emutasiperiode=$th.$bl;
                            $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode);
                            if($ada=='ada'){
                                $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nretur,$emutasiperiode);
                            }else{
                                $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nretur,$emutasiperiode,$q_aw,$q_ak);
                            }
                            if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer)){
                                $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nretur,$q_ak);
                            }else{
                                $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$nretur);
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
                    $this->Logger->write('Input SJPB Retur No:'.$isj);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isj
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
