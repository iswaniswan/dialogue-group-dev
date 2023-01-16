<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10212';

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
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function gettoko(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->gettoko($cari);
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
        $data  = $this->mmaster->getdetailproduct($iproduct);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibbk       = $this->input->post('ibbk', TRUE);
        $ibbktype   = '03';
        $ibbkold    = $this->input->post('ibbkold', TRUE);
        $dbbk       = $this->input->post('dbbk', TRUE);
        $thbl       = date('Ym', strtotime($dbbk));
        $dbbk       = date('Y-m-d', strtotime($dbbk));
        $icustomer  = $this->input->post('icustomer', TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if($dbbk!='' && $icustomer!='' && $jml!='' && $jml!='0'){
            $this->db->trans_begin();
            $istore            = 'AA';
            $istorelocation    = '01';
            $istorelocationbin = '00';
            $ibbk              = $this->mmaster->runningnumber($thbl);
            $this->mmaster->insertheader($ibbk, $ibbktype, $dbbk, $icustomer, $ibbkold, $eremark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct = $this->input->post('iproduct'.$i, TRUE);
                if (($iproduct!=''||$iproduct!=null)) {
                    $x++;
                    $iproductgrade    = 'A';
                    $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                    $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice       = $this->input->post('vunitprice'.$i, TRUE);
                    $vunitprice       = str_replace(',','',$vunitprice);
                    $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                    $eremark          = $this->input->post('eremark'.$i, TRUE);
                    if($nquantity>0){
                        $this->mmaster->insertdetail( $ibbk,$ibbktype,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$vunitprice,$eremark,$i,$thbl);
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
                                $q_aw  = 0;
                                $q_ak  = 0;
                                $q_in  = 0;
                                $q_out = 0;
                            }
                        }
                        $this->mmaster->inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$nquantity,$q_aw,$q_ak);
                        $th=substr($dbbk,0,4);
                        $bl=substr($dbbk,5,2);
                        $emutasiperiode=$th.$bl;
                        if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                            $this->mmaster->updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                            $this->mmaster->updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$q_ak);
                        }else{
                            $this->mmaster->inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nquantity);
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
                $this->Logger->write('Input BBK-Hadiah No:'.$ibbk);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibbk
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
