<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020801';

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

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
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
        $iproduct = $this->input->post('iproduct');
        $data = $this->mmaster->getdetailproduct($iproduct);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibm        = $this->input->post('ibm', TRUE);
        $dbm        = $this->input->post('dbm', TRUE);
        if($dbm!=''){
            $tmp=explode("-",$dbm);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dbm=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $eremark        = $this->input->post('eremark', TRUE);
        $jml              = $this->input->post('jml', TRUE);
        if($dbm!=''){
            $this->db->trans_begin();
            $istore            = 'AA';
            $istorelocation    = '01';
            $istorelocationbin = '00';
            $ibm    =$this->mmaster->runningnumber($thbl);
            $this->mmaster->insertheader($ibm, $dbm, $eremark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                if (($iproduct!=''||$iproduct!=null)&&($nquantity!=''||$nquantity!=0)) {
                    $x++;
                    $iproductgrade    = 'A';
                    $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                    $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                    $eremark          = $this->input->post('eremark'.$i, TRUE);
                    $this->mmaster->insertdetail($ibm,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$x);
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
                    $this->mmaster->inserttransbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibm,$q_in,$q_out,$nquantity,$q_aw,$q_ak);
                    $th=substr($dbm,0,4);
                    $bl=substr($dbm,5,2);
                    $emutasiperiode=$th.$bl;
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                        $this->mmaster->updatemutasibmelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasibmelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                    }
                    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                        $this->mmaster->updateicbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$q_ak);
                    }else{
                        $this->mmaster->inserticbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nquantity);
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
                $this->Logger->write('Input Bon M Masuk No:'.$ibm);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibm
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
