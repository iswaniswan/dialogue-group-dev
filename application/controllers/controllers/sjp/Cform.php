<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020208';

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
            'store'     => $this->mmaster->bacastore($iarea, $username, $idcompany)->result()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getspmb(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('istorelocation') !='' && $this->input->get('iarea') !='') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $iarea  = $this->input->get('iarea', FALSE);
            $istorelocation  = $this->input->get('istorelocation', FALSE);
            $data   = $this->mmaster->getspmb($cari,$iarea,$istorelocation);
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
        $query  = array(
            'detail' => $this->mmaster->getdetailspmb($ispmb)->result_array()
        );
        echo json_encode($query);  
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

        $isjold = $this->input->post('isjold', TRUE);
        $dsj    = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iarea      = $this->input->post('iarea', TRUE);
        $ispmb      = $this->input->post('ispmb', TRUE);
        $dspmb      = $this->input->post('dspmb', TRUE);
        if($dspmb!=''){
            $tmp=explode("-",$dspmb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspmb=$th."-".$bl."-".$hr;
        }
        $vspbnetto=$this->input->post('vsj', TRUE);
        $vspbnetto= str_replace(',','',$vspbnetto);
        $jml      = $this->input->post('jml', TRUE);
        if($dsj!='' && $iarea!='' && $ispmb!=''){
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
                $istorelocation     = $this->input->post('istorelocation', TRUE);
                $istorelocationbin  = '00';
                $isjtype            = '01';
                $isj                = $this->mmaster->runningnumbersj($iarea,$thbl);
                $this->mmaster->insertsjheader($ispmb,$dspmb,$isj,$dsj,$iarea,$vspbnetto,$isjold);
                for($i=1;$i<=$jml;$i++){
                    $cek=$this->input->post('chk'.$i, TRUE);
                    if($cek=='on'){
                        $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                        $iproductgrade  = 'A';
                        $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                        $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                        $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                        $vunitprice     = str_replace(',','',$vunitprice);
                        $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                        $ndeliver       = str_replace(',','',$ndeliver);
                        $norder         = $this->input->post('norder'.$i, TRUE);
                        $norder         = str_replace(',','',$norder);
                        $eremark        = $this->input->post('eremark'.$i, TRUE);
                        if($eremark==''){
                            $eremark=null;
                        }
                        if($norder>0){
                            $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,$ndeliver,
                                $vunitprice,$ispmb,$dspmb,$isj,$dsj,$iarea,$istore,$istorelocation,
                                $istorelocationbin,$eremark,$i,$i);
                            $this->mmaster->updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea);
                            $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
                            if(isset($trans)){
                                foreach($trans as $itrans){
                                    $q_aw =$itrans->n_quantity_stock;
                                    $q_ak =$itrans->n_quantity_stock;
                                    $q_in =0;
                                    $q_out=0;
                                    break;
                                }
                            }else{
                                $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
                                if(isset($trans)){
                                    foreach($trans as $itrans)
                                    {
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
                            $this->mmaster->inserttrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$isj,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                            $th=substr($dsj,0,4);
                            $bl=substr($dsj,5,2);
                            $emutasiperiode=$th.$bl;
                            $ada=$this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$emutasiperiode);
                            if($ada=='ada'){
                                $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                            }else{
                                $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                            }
                            if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00')){
                                $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$q_ak);
                            }else{
                                $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$ndeliver);
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
                    $this->Logger->write('Input SJP No:'.$isj);
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
