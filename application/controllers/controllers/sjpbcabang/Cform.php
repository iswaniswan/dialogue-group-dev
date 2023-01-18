<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020210';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->getarea($iarea, $username, $idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = strtoupper($this->input->get('iarea'));
            $data       = $this->mmaster->getcustomer($cari, $iarea);
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
        if($this->input->get('q') != '' && $this->input->get('istore') != '' && $this->input->get('icustomer') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $istore     = strtoupper($this->input->get('istore'));
            $icustomer  = strtoupper($this->input->get('icustomer'));
            $data       = $this->mmaster->getproduct($cari, $istore, $icustomer);
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
        $iproduct = $this->input->post('iproduct');
        $istore   = $this->input->post('istore');
        $icustomer= $this->input->post('icustomer');
        $data = $this->mmaster->getdetailproduct($iproduct, $istore, $icustomer);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dsj    = $this->input->post('dsj', TRUE);
        $iarea  = $this->input->post('iarea',TRUE);
        if($dsj!=''){
            $tmp    = explode("-",$dsj);
            $th     = $tmp[2];
            $bl     = $tmp[1];
            $hr     = $tmp[0];
            $dsjpb  = $th."-".$bl."-".$hr;
            $thbl   = $th.$bl;
            $isjpb  = $this->mmaster->runningnumbersj($iarea,$thbl);
            $tmpsj  = explode("-",$isjpb);
            $firstsj= $tmpsj[0];
            $lastsj = $tmpsj[2];
            $newsj  = $firstsj."-".$thbl."-".$lastsj;
        }
        $iarea              = $this->input->post('iarea', TRUE);
        $icustomer          = $this->input->post('icustomer',TRUE);
        $jml                = $this->input->post('jml', TRUE);
        $ispg               = $this->input->post('ispg',TRUE);
        $vsjpb              = $this->input->post('nilai',TRUE);
        $vsjpb              = str_replace(',','',$vsjpb);
        $istore             = $this->input->post('istore', TRUE);
        $istore             = $this->input->post('istore', TRUE);
        $istorelocation     = $this->input->post('istorelocation', TRUE);
        $istorelocationbin  = $this->input->post('istorelocationbin', TRUE);
        if($dsj!='' && $iarea!='' && $icustomer!=''){
            $this->db->trans_begin();
            $this->mmaster->insertsjpb($isjpb, $icustomer, $iarea, $ispg, $dsjpb, $vsjpb);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct = substr($this->input->post('iproduct'.$i, TRUE),0,7);
                $ndeliver = $this->input->post('ndeliver'.$i, TRUE);
                $ndeliver = str_replace(',','',$ndeliver);
                if (($iproduct!=''||$iproduct!=null)&&($ndeliver!=''||$ndeliver>0)) {
                    $x++;
                    $ipricegroup    = substr($this->input->post('iproduct'.$i, TRUE),7,2);
                    $iproductgrade  = 'A';
                    $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                    $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice     = $this->input->post('productprice'.$i, TRUE);
                    $vunitprice     = str_replace(',','',$vunitprice);
                    $norder         = $this->input->post('norder'.$i, TRUE);
                    $norder         = str_replace(',','',$norder);
                    $this->mmaster->insertsjpbdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver, $vunitprice,$isjpb,$iarea,$i,$dsjpb,$ipricegroup);
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
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input SJPB Cabang No : '.$isjpb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isjpb
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
