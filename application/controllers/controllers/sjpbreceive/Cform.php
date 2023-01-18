<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020211';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $query     = $this->mmaster->getspg($username, $idcompany);
        if($query->num_rows()>0){
            foreach($query->result() as $xx){
                $ispg          = $username;
                $iarea         = $iarea;
                $icustomer     = $xx->i_customer;
                $eareaname     = $xx->e_area_name;
                $espgname      = $xx->e_spg_name;
                $ecustomername = $xx->e_customer_name;
            }
        }else{
            $ispg           = $username;
            $iarea          = $iarea;
            $icustomer      = '';
            $eareaname      = '';
            $espgname       = '';
            $ecustomername  = '';
        }
        echo $this->mmaster->data($this->global['folder'], $icustomer, $iarea);
    }

    public function edit(){
        $isjpb = $this->uri->segment(4);
        $iarea = $this->uri->segment(5);
        $data  = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
            'iarea'  => $iarea,
            'isjpb'  => $isjpb,
            'isi'    => $this->mmaster->baca($isjpb,$iarea),
            'detail' => $this->mmaster->bacadetail($isjpb,$iarea)
        );
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isjpb      = $this->input->post('isj', TRUE);
        $iarea      = $this->input->post('iarea', TRUE);
        $dsjreceive = $this->input->post('dreceive', TRUE);
        if($dsjreceive!=''){
            $tmp=explode("-",$dsjreceive);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjreceive=$th."-".$bl."-".$hr;
            $thbl     = substr($th,2,2).$bl;
            $tmpsj  = explode("-",$isjpb);
            $firstsj= $tmpsj[0];
            $lastsj = $tmpsj[2];
            $newsj  = $firstsj."-".$thbl."-".$lastsj;               
        }
        $isjp     = $this->input->post('isjp', TRUE);
        $dsjp = $this->input->post('dsjp', TRUE);
        if($dsjp!=''){
            $tmp=explode("-",$dsjp);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjp=$th."-".$bl."-".$hr;
        }
        $dsjpb = $this->input->post('dsj', TRUE);
        if($dsjpb!=''){
            $tmp=explode("-",$dsjpb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjpb=$th."-".$bl."-".$hr;
        }
        $icustomer  = $this->input->post('icustomer', TRUE);
        $vsjpb      = $this->input->post('vsjpb', TRUE);
        $vsjpb      = str_replace(',','',$vsjpb);
        $vsjpbrec   = $this->input->post('vsjpbrec', TRUE);
        $vsjpbrec   = str_replace(',','',$vsjpbrec);

        $istore           = $iarea;
        if($istore=='PB'){
            $istorelocation = '00';
        }else{
            $istorelocation = 'PB';     
        }
        $istorelocationbin = '00';
        $jml = $this->input->post('jml', TRUE);
        $gaono=true;
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $gaono=false;
            }
            if(!$gaono) break;
        }
        if( (!$gaono)&&($dsjreceive!='') ){
            $this->db->trans_begin();
            $this->mmaster->updatesjheader($isjpb,$iarea,$dsjreceive,$vsjpb,$vsjpbrec);
            for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i, TRUE);
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade  = 'A';
                $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                $ndeliver       = str_replace(',','',$ndeliver);
                $nreceive       = $this->input->post('nreceive'.$i, TRUE);
                $nreceive       = str_replace(',','',$nreceive);
                $ntmp           = $this->input->post('ntmp'.$i, TRUE);
                $ntmp           = str_replace(',','',$ntmp);
                $this->mmaster->deletesjdetail( $isjp, $isjpb, $iarea, $iproduct, $iproductgrade, $iproductmotif, $ndeliver);
                $th=substr($dsjreceive,0,4);
                $bl=substr($dsjreceive,5,2);
                $emutasiperiode=$th.$bl;
                $thsj=substr($dsjpb,0,4);
                $blsj=substr($dsjpb,5,2);
                $emutasiperiodesj=$thsj.$blsj;
                if( ($ntmp!='') && ($ntmp!=0) ){
                    $this->mmaster->updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode,$emutasiperiodesj);
                    $this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp);
                }
                if($cek=='on'){
                    $eproductname = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice       = $this->input->post('vunitprice'.$i, TRUE);
                    $vunitprice       = str_replace(',','',$vunitprice);
                    $this->mmaster->insertsjpbdetail( $iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isjpb,$iarea,$i,$dsjpb,$nreceive);
                    if($ndeliver>0){
                        $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$icustomer);
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
                        $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode);
                        if($ada=='ada'){
                            $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nreceive,$emutasiperiode,$emutasiperiodesj,$iarea);
                        }else{
                            $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nreceive,$emutasiperiode,$emutasiperiodesj,$iarea);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer)){
                            $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nreceive,$q_ak);
                        }else{
                            $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$nreceive);
                        }
                    }
                }else{
                    $eproductname = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice   = $this->input->post('vproductmill'.$i, TRUE);
                    $vunitprice   = str_replace(',','',$vunitprice);
                    $eremark      = $this->input->post('eremark'.$i, TRUE);
                    if($eremark==''){
                        $eremark=null;
                    }
                    $this->mmaster->insertsjpbdetail( $iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isjpb,$iarea,$i,$dsjpb,$nreceive);
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update SJPB Receive No:'.$isjpb.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $newsj
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
