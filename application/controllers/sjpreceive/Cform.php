<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020209';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->model($this->global['folder'].'/mmaster');
    }    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        echo $this->mmaster->data($this->global['folder'], $username, $idcompany);
    }

    public function edit(){
        $isjp  = $this->uri->segment(4);
        $iarea = $this->uri->segment(5);
        $query = $this->mmaster->jmlitem($isjp, $iarea);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'jmlitem'       => $query->num_rows(),
            'isi'           => $this->mmaster->baca($isjp,$iarea),
            'detail'        => $this->mmaster->bacadetail($isjp,$iarea)
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isj        = $this->input->post('isj', TRUE);
        $dsjreceive = $this->input->post('dreceive', TRUE);
        if($dsjreceive!=''){
            $tmp=explode("-",$dsjreceive);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjreceive=$th."-".$bl."-".$hr;
            $thbl   = substr($th,2,2).$bl;
            $tmpsj  = explode("-",$isj);
            $firstsj= $tmpsj[0];
            $lastsj = $tmpsj[2];
            $newsj  = $firstsj."-".$thbl."-".$lastsj;               
        }
        $ispmb      = $this->input->post('ispmb', TRUE);
        $dspmb      = $this->input->post('dspmb', TRUE);
        if($dspmb!=''){
            $tmp=explode("-",$dspmb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspmb=$th."-".$bl."-".$hr;
        }
        $dsj = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsj=$th."-".$bl."-".$hr;
        }
        $iarea      = $this->input->post('iarea', TRUE);
        $vspbnetto  = $this->input->post('vsj', TRUE);
        $vspbnetto  = str_replace(',','',$vspbnetto);
        $vsjrec     = $this->input->post('vsjrec', TRUE);
        $vsjrec     = str_replace(',','',$vsjrec);
        $jml        = $this->input->post('jml', TRUE);
        $gaono=true;
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $gaono=false;
            }
            if(!$gaono) break;
        }
        if( (!$gaono)&&($dsjreceive!='')&&($vsjrec != 0)&&($vsjrec != '') ){
            $this->db->trans_begin();
            $istore	  			= $this->input->post('istore', TRUE);
            $istorelocation     = $this->input->post('istorelocation', TRUE);
            $istorelocationbin  = '00';
            $this->mmaster->updatesjheader($isj,$iarea,$dsjreceive,$vspbnetto,$vsjrec);
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
                if($ntmp == ''){
                    $ntmp=0;
                }
                $norder         = $this->input->post('norder'.$i, TRUE);
                $norder         = str_replace(',','',$norder);
                if($norder == '') {
                    $norder = $ndeliver;
                }
                $th = substr($dsjreceive,0,4);
                $bl = substr($dsjreceive,5,2);
                $emutasiperiode = $th.$bl;
                $thsj = substr($dsj,0,4);
                $blsj = substr($dsj,5,2);
                $emutasiperiodesj = $thsj.$blsj;
                if($cek=='on'){
                    $eproductname = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice       = $this->input->post('vproductmill'.$i, TRUE);
                    $vunitprice       = str_replace(',','',$vunitprice);
                    $eremark          = $this->input->post('eremark'.$i, TRUE);
                    if($eremark==''){
                        $eremark=null;
                    }
                    $this->mmaster->updatesjdetail($iproduct,$iproductgrade,$iproductmotif,$isj,$dsj,$iarea,$nreceive,$ntmp);
                    if( ($ntmp!='') && ($ntmp!=0) ){
                        $this->mmaster->deletetrans( $iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,
                            $ntmp,$eproductname);
                        $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode,$emutasiperiodesj);
                        $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp);
                    }                
                    if($nreceive>0){
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
                        $this->mmaster->inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$nreceive,$q_aw,$q_ak);
                        $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode);
                        if($ada=='ada'){
                            $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode,$emutasiperiodesj);
                        }else{
                            $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode,$emutasiperiodesj);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                            $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$q_ak);
                        }else{
                            $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nreceive);
                        }
                    }
                }
            }
            $sjnew=0;
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update SJP Receive No:'.$isj.' Area:'.$iarea);
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
