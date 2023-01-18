<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021008';

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function product(){
        $user = $this->session->userdata('username');
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->product1($cari, $user);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,  
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name." - GRADE ".$kuy->i_product_grade
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetail(){
        header("Content-Type: application/json", true);
        $iproduct = strtoupper($this->input->post('iproduct', FALSE));
        $grade   = strtoupper($this->input->post('grade', FALSE));
        $user   = $this->session->userdata('username');
        $data  = $this->mmaster->getproduct1($user,$iproduct,$grade);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iicconvertion = $this->input->post('iicconvertion', TRUE);
        $dicconvertion = $this->input->post('dicconvertion', TRUE);
        $th            = date('Y', strtotime($dicconvertion));
        $bl            = date('m', strtotime($dicconvertion));
        $thbl          = date('ym', strtotime($dicconvertion));
        $tehbl         = date('Ym', strtotime($dicconvertion));
        $dicconvertion = date('Y-m-d', strtotime($dicconvertion));
        $jml1          = $this->input->post('jml1', TRUE);
        $jml2          = $this->input->post('jml2', TRUE);
        if ((isset($dicconvertion) && $dicconvertion != '') && ($iicconvertion == '') && ($jml1>0) && ($jml2>0)){
            $this->db->trans_begin();
            $iicconvertion      = $this->mmaster->runningnumber($thbl);
            $ibbk               = $this->mmaster->runningnumberbbk($tehbl);
            $ibbm               = $this->mmaster->runningnumberbbm($tehbl);
            $dbbk               = $dicconvertion;
            $dbbm               = $dicconvertion;
            $periode            = $tehbl;
            $istore             = 'AA';
            $istorelocation     = '01';
            $istorelocationbin  = '00';
            $eremark            = 'Konversi Stock';
            $ibbktype           = '04';
            $ibbmtype           = '03';
            $iarea              = '00';
            /*---- Start IF JML == 1 ----*/
            if ($jml1==1) {
                /*------Start For--------*/                
                for($i=1;$i<=$jml1;$i++){
                    $iproduct      = $this->input->post('iproduct'.$i, TRUE);
                    $iproductgrade = $this->input->post('iproductgrade'.$i, TRUE);
                    $iproductmotif = $this->input->post('iproductmotif'.$i, TRUE);
                    $ficconvertion = 't';
                    $nicconvertion = $this->input->post('nicconvertion'.$i, TRUE);
                    $eproductname  = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice    = $this->input->post('vproductretail'.$i, TRUE);
                    $this->mmaster->insertheader($iicconvertion,$dicconvertion,$iproduct,$iproductgrade,$eproductname,$iproductmotif,$ficconvertion,$nicconvertion);
                    $this->mmaster->insertbbkdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbk,$eremark,$ibbktype,$periode);
                    /*----------------------Comment-----------------*/
                    $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw = $itrans->n_quantity_awal;
                            $q_ak = $itrans->n_quantity_akhir;
                            $q_in = $itrans->n_quantity_in;
                            $q_out= $itrans->n_quantity_out;
                            break;
                        }
                    }else{
                        $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
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
                    }
                    $this->mmaster->inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$iicconvertion,$q_in,$q_out,$nicconvertion,$q_aw,$q_ak);
                    $emutasiperiode=$th.$bl;
                    if($ibbktype=='05'){
                        if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                            $this->mmaster->updatemutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                        }
                    }else{
                        if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                            $this->mmaster->updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                        }
                    }
                    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                        $this->mmaster->updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$q_ak);
                    }else{
                        $this->mmaster->inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nicconvertion);
                    }
                }
                /*---------End For------------*/
                $this->mmaster->insertbbkheader($iicconvertion,$dicconvertion,$ibbk,$dbbk,$ibbktype,$eremark,$iarea);
                $this->mmaster->insertbbmheader($iicconvertion,$dicconvertion,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea);
                for($i=1;$i<=$jml2;$i++){
                    $iproduct      = $this->input->post('2iproduct'.$i, TRUE);
                    $iproductgrade = $this->input->post('2iproductgrade'.$i, TRUE);
                    $iproductmotif = $this->input->post('2iproductmotif'.$i, TRUE);
                    $nicconvertion = $this->input->post('2nicconvertion'.$i, TRUE);
                    $eproductname  = $this->input->post('2eproductname'.$i, TRUE);
                    $vunitprice    = $this->input->post('2vproductretail'.$i, TRUE);
                    $this->mmaster->insertdetail($iicconvertion,$dicconvertion,$iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion);
                    $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw =$itrans->n_quantity_awal;
                            $q_ak =$itrans->n_quantity_akhir;
                            $q_in =$itrans->n_quantity_in;
                            $q_out=$itrans->n_quantity_out;
                            break;
                        }
                    }else{
                        $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
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
                    }
                    $this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$iicconvertion,$q_in,$q_out,$nicconvertion,$q_aw,$q_ak);
                    $emutasiperiode = $th.$bl;
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                        $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }
                    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                        $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$nicconvertion);
                    }else{
                        $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nicconvertion);
                    }
                    $this->mmaster->insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbm,$eremark,$ibbmtype,$periode);
                }
                /*---- END IF JML == 1 ----*/
            }else{
                /*----Start For----*/
                for($i=1;$i<=$jml2;$i++){
                    $iproduct      = $this->input->post('2iproduct'.$i, TRUE);
                    $iproductgrade = $this->input->post('2iproductgrade'.$i, TRUE);
                    $iproductmotif = $this->input->post('2iproductmotif'.$i, TRUE);
                    $ficconvertion = 'f';
                    $nicconvertion = $this->input->post('2nicconvertion'.$i, TRUE);
                    $eproductname  = $this->input->post('2eproductname'.$i, TRUE);
                    $vunitprice    = $this->input->post('2vproductretail'.$i, TRUE);
                    $this->mmaster->insertheader($iicconvertion,$dicconvertion,$iproduct,$iproductgrade,$eproductname,$iproductmotif,$ficconvertion,$nicconvertion);
                    $this->mmaster->insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbm,$eremark,$ibbmtype,$periode);
                    $trans = $this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw =$itrans->n_quantity_awal;
                            $q_ak =$itrans->n_quantity_akhir;
                            $q_in =$itrans->n_quantity_in;
                            $q_out=$itrans->n_quantity_out;
                            break;
                        }
                    }else{
                        $trans = $this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
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
                    }
                    $this->mmaster->inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$iicconvertion,$q_in,$q_out,$nicconvertion,$q_aw,$q_ak);
                    $emutasiperiode=$th.$bl;
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){$this->mmaster->updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }
                    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                        $this->mmaster->updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$q_ak);
                    }else{
                        $this->mmaster->inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nicconvertion);
                    }
                }
                /*--------- End Start ----------*/
                $this->mmaster->insertbbkheader($iicconvertion,$dicconvertion,$ibbk,$dbbk,$ibbktype,$eremark,$iarea);
                $this->mmaster->insertbbmheader($iicconvertion,$dicconvertion,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea);
                for($i=1;$i<=$jml1;$i++){
                    $iproduct      = $this->input->post('iproduct'.$i, TRUE);
                    $iproductgrade = $this->input->post('iproductgrade'.$i, TRUE);
                    $iproductmotif = $this->input->post('iproductmotif'.$i, TRUE);
                    $nicconvertion = $this->input->post('nicconvertion'.$i, TRUE);
                    $eproductname  = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice    = $this->input->post('vproductretail'.$i, TRUE);
                    $this->mmaster->insertbbkdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbk,$eremark,$ibbktype,$periode);
                    $this->mmaster->insertdetail($iicconvertion,$dicconvertion,$iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion);
                    $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw =$itrans->n_quantity_awal;
                            $q_ak =$itrans->n_quantity_akhir;
                            $q_in =$itrans->n_quantity_in;
                            $q_out=$itrans->n_quantity_out;
                            break;
                        }
                    }else{
                        $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                        if(isset($trans)){
                            foreach($trans as $itrans){
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
                    $this->mmaster->inserttrans4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$iicconvertion,$q_in,$q_out,$nicconvertion,$q_aw,$q_ak);
                    $emutasiperiode=$th.$bl;
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                        $this->mmaster->updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }
                    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                        $this->mmaster->updateic4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$q_ak);
                    }else{
                        $this->mmaster->insertic4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nicconvertion);
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
                $this->Logger->write('Input Konversi Stok No:'.$iicconvertion);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iicconvertion
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
