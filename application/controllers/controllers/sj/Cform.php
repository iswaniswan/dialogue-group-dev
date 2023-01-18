<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020201';

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
        $this->Logger->write('Membuka Menu Input '.$this->global['title']);
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->area(),
            'today'     => date('d-m-Y')
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
        
    }

    public function getspb(){
        $iarea = $this->input->post('iarea');
        $query = $this->mmaster->getspb($iarea);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_spb." >".$row->i_spb." - ".$row->dspb." - ".$row->e_customer_name."</option>";
                $i = $row->i_store;
            }
            $kop  = "<option value=\"\"> -- Pilih SPB -- ".$c."</option>";
            $sok  = $i;
            echo json_encode(array(
                'kop'   => $kop,
                'sok'   => $sok
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Ada SPB</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getcus(){
        $ispb  = $this->input->post('ispb');
        $iarea = $this->input->post('iarea');
        $query = $this->mmaster->getcus($iarea, $ispb);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c=$row->e_customer_name;
                $s=$row->i_customer;
                $i=$row->d_spb;
            }
            $kop  = $c;
            $sip  = $s;
            $yeuh = $i;
            echo json_encode(array(
                'tah'   => $kop,
                'sip'   => $sip,
                'spb'   => $yeuh
            ));
        }else{
            $kop  = "";
            echo json_encode(array(
                'tah'   => $kop
            ));
        }
    }

    public function proses(){
        $dsj = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $tglsjbaru=$th.$bl.$hr;
            $dsj1=$th."-".$bl."-".$hr;
            $thbl = $th.$bl;
        }
        $ispb       = $this->input->post('ispb', TRUE);
        $dspb       = $this->input->post('dspb', TRUE);
        $iarea      = $this->input->post('iarea', TRUE);
        $typearea   = $this->mmaster->cekdaerah($ispb,$iarea);
        if($typearea=='t'){
            $areasj = $iarea;      
        }else{          
            $areasj = '00';      
        }
        $tglakhir   = '';
        $sjpot      = 'SJ-'.substr(date('Y'),2,2).'%-'.$areasj;
        $query      = $this->mmaster->ceknota($sjpot);
        if($query->num_rows()>0){
            foreach($query->result() as $tmp){              
                $tglakhir=$tmp->d_sj;              
                break;          
            }      
        }
        $txttglakhir = '';
        if($tglakhir !=''){          
            $tmp=explode("-",$tglakhir);          
            $hr=$tmp[2];          
            $bl=$tmp[1];          
            $th=$tmp[0];          
            $txttglakhir=$hr."-".$bl."-".$th;          
            $tglakhir=$th.$bl.$hr;
        }
        $istore         = $this->input->post('istore',TRUE);
        $isjold         = "";
        $icustomer      = $this->input->post('icustomer',TRUE);
        $ecustomername  = $this->input->post('ecustomer',TRUE);
        $qjum           = $this->mmaster->getjum($ispb, $iarea);        
        $query          = $this->mmaster->getdata($ispb, $iarea);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $vsjgross           = $row->v_spb;
                $nsjdiscount1       = $row->n_spb_discount1;
                $nsjdiscount2       = $row->n_spb_discount2;
                $nsjdiscount3       = $row->n_spb_discount3;
                $vsjdiscount1       = $row->v_spb_discount1;
                $vsjdiscount2       = $row->v_spb_discount2;
                $vsjdiscount3       = $row->v_spb_discount3;
                $vsjdiscounttotal   = $row->v_spb_discounttotal;
                $vsjnetto           = $row->v_spb-$row->v_spb_discounttotal;
                $icustomer          = $row->i_customer;
                $isalesman          = $row->i_salesman;
                $ntop               = $row->n_spb_toplength;
                $fspbconsigment     = $row->f_spb_consigment;
                $fspbplusppn        = $row->f_spb_plusppn;
            }
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'area'              => $this->mmaster->area(),
            'iarea'             => $iarea,
            'ispb'              => $ispb,
            'dspb'              => $dspb,
            'dsj'               => $dsj,
            'txttglakhir'       => '',
            'tglakhirx'         => $tglakhir,
            'txttglakhir'       => $txttglakhir, 
            'ecustomername'     => $ecustomername,
            'jmlitem'           => $qjum->num_rows(), 
            'detail'            => $this->mmaster->detail($ispb, $iarea),
            'isj'               => '',
            'isi'               => 'xxxxx',
            'istore'            => $istore,
            'isjold'            => $isjold,
            'vsjgross'          => $vsjgross,
            'nsjdiscount1'      => $nsjdiscount1,
            'nsjdiscount2'      => $nsjdiscount2,
            'nsjdiscount3'      => $nsjdiscount3,
            'vsjdiscount1'      => $vsjdiscount1,
            'vsjdiscount2'      => $vsjdiscount2,
            'vsjdiscount3'      => $vsjdiscount3,
            'vsjdiscounttotal'  => $vsjdiscounttotal,
            'vsjnetto'          => $vsjnetto,
            'icustomer'         => $icustomer,
            'isalesman'         => $isalesman,
            'ntop'              => $ntop,
            'fspbplusppn'       => $fspbplusppn,
            'fspbconsigment'    => $fspbconsigment,
            'tglsjbaru'         => $tglsjbaru,
            'tglakhir'          => $tglakhir,
            'areasj'            => $areasj,
            'thbl'              => $thbl
        );
        $this->Logger->write('Membuka Menu Input Item '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea1 = $this->session->userdata('i_area');
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
        $iarea              = $this->input->post('iarea', TRUE);
        $ispb               = $this->input->post('ispb', TRUE);
        $dspb               = $this->input->post('dspb', TRUE);
        $isalesman          = $this->input->post('isalesman',TRUE);
        $icustomer          = $this->input->post('icustomer',TRUE);
        $nsjdiscount1       = $this->input->post('nsjdiscount1',TRUE);
        $nsjdiscount1       = str_replace(',','',$nsjdiscount1);
        $nsjdiscount2       = $this->input->post('nsjdiscount2',TRUE);
        $nsjdiscount2       = str_replace(',','',$nsjdiscount2);
        $nsjdiscount3       = $this->input->post('nsjdiscount3',TRUE);
        $nsjdiscount3       = str_replace(',','',$nsjdiscount3);
        $vsjdiscount1       = $this->input->post('vsjdiscount1',TRUE);
        $vsjdiscount1       = str_replace(',','',$vsjdiscount1);
        $vsjdiscount2       = $this->input->post('vsjdiscount2',TRUE);
        $vsjdiscount2       = str_replace(',','',$vsjdiscount2);
        $vsjdiscount3       = $this->input->post('vsjdiscount3',TRUE);
        $vsjdiscount3       = str_replace(',','',$vsjdiscount3);
        $vsjdiscounttotal   = $this->input->post('vsjdiscounttotal',TRUE);
        $vsjdiscounttotal   = str_replace(',','',$vsjdiscounttotal);
        $vsjgross           = $this->input->post('vsjgross',TRUE);
        $vsjgross           = str_replace(',','',$vsjgross);
        $vsjnetto           = $this->input->post('vsjnetto',TRUE);
        $vsjnetto           = str_replace(',','',$vsjnetto);
        $ntop               = $this->input->post('ntop',TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if($dsj!='' && $iarea!=''){
            $gaono = true;
            for($i=1;$i<=$jml;$i++){
                $cek = $this->input->post('chk'.$i, TRUE);
                $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                $ndeliver       = str_replace(',','',$ndeliver);
                if($cek=='on' && $ndeliver > 0){
                    $gaono=false;
                }
                if(!$gaono) break;
            }
            if(!$gaono){
                $this->db->trans_begin();
                $istore = $this->input->post('istore', TRUE);
                $kons   = $this->mmaster->cekkons($ispb,$iarea);
                if($istore=='AA'){
                    $istorelocation = '01';
                }else{
                    if($kons=='t'){
                        if($istore=='PB'){
                            $istorelocation = '00';
                        }else{
                            if($istore=='03' || $istore=='04' || $istore=='05' || $istore=='12'){
                                $istorelocation = 'PB';
                            }else{
                                $istorelocation = '00';
                            }
                        }
                    }else{
                        $istorelocation = '00';
                    }
                }
                $istorelocationbin  = '00';
                $eremark            = 'SPB';
                $isjtype            = '04';
                $typearea           = $this->mmaster->cekdaerah($ispb,$iarea);
                if($typearea=='t'){
                    $areasj=$iarea;
                }else{
                    $areasj='00';
                }

                if($iarea1=='00' && $iarea1!=$iarea){
                    $fentpusat = 't';
                    $iareareff = $iarea;
                    $areanumsj = $iarea;
                }elseif($iarea1!='00' && $iarea1==$iarea){
                    $fentpusat = 'f';
                    $iareareff = $iarea1;
                    $areanumsj = $iarea1;
                }else{
                    $fentpusat = 'f';
                    $iareareff = $iarea1;
                    $areanumsj = $iarea1;
                }
                $adasj = $this->mmaster->ceksj($ispb,$iarea);
                if(!$adasj){
                    $isj = $this->mmaster->runningnumbersj($areasj,$thbl,$kons);
                    $this->mmaster->insertsjheader($ispb,$dspb,$isj,$dsj,$iarea,$isalesman,$icustomer, $nsjdiscount1,$nsjdiscount2,$nsjdiscount3,$vsjdiscount1, $vsjdiscount2,$vsjdiscount3,$vsjdiscounttotal,$vsjgross,$vsjnetto,$isjold,$fentpusat,$iareareff,$ntop);
                    $this->mmaster->updatespb($ispb,$iarea,$isj,$dsj);
                    for($i=1;$i<=$jml;$i++){
                        $cek=$this->input->post('chk'.$i, TRUE);
                        $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                        $vunitprice     = str_replace(',','',$vunitprice);
                        if($cek=='on'){
                            $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                            $iproductgrade  = 'A';
                            $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                            $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                            $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                            $ndeliver       = str_replace(',','',$ndeliver);
                            $norder         = $this->input->post('norder'.$i, TRUE);
                            $norder         = str_replace(',','',$norder);
                            if($ndeliver>0){
                                $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver, $vunitprice,$isj,$iarea,$i);
                                $this->mmaster->updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea,$vunitprice);
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
                                $this->mmaster->inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                                $th=substr($dsj,0,4);
                                $bl=substr($dsj,5,2);
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
                            }else{
                                $this->mmaster->updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,0,$iarea,$vunitprice);
                            }
                        }else{
                            $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                            $iproductgrade  = 'A';
                            $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                            $this->mmaster->updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,0,$iarea,$vunitprice);
                        }
                    } /*End For*/
                }/*End IF*/
                if(($this->db->trans_status()=== False)){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false
                    );
                } elseif (!$adasj) {
                    $this->db->trans_commit();
                    $this->Logger->write('Input SJ No:'.$isj);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isj
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
}
/* End of file Cform.php */
