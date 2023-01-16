<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070315';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
            'area'      => $this->mmaster->bacaarea($username, $idcompany)->result()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $iarea    = $this->input->post('iarea');
        
        if($iarea==''){
            $iarea=$this->uri->segment(4);
        }
        
        echo $this->mmaster->data($iarea,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
        if($area==''){
            $area=$this->uri->segment(4);
        } 
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iarea'         => $area
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isjp   = $this->input->post('isjp');
        $iarea  = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isjp, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJP Area '.$iarea.' No:'.$isjp);
            echo json_encode($data);
        }
    }

    public function edit(){
        if(($this->uri->segment(4)!='')){
            $ispmb    = $this->uri->segment(4);
            $iarea    = $this->uri->segment(5);
            
            $data   = array(
                'folder'            => $this->global['folder'],
                'title'             => "Edit ".$this->global['title'],
                'title_list'        => 'List '.$this->global['title'],
                'ispmb'             => $ispmb,
                'iarea'             => $iarea,
                'i_level'           => $this->session->userdata('i_level'),
                'i_menu'            => $this->i_menu,
                'isi'               => $this->mmaster->baca($ispmb),
                'detail'            => $this->mmaster->bacadetail($ispmb),
            );   

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj    = $this->input->post('isj', TRUE);
        $isjold = $this->input->post('isjold', TRUE);
        $dsj    = $this->input->post('dsj', TRUE);
        
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsj=$th."-".$bl."-".$hr;
            $thbl   = substr($th,2,2).$bl;
            $tmpsj  = explode("-",$isj);
            $firstsj= $tmpsj[0];
            $lastsj = $tmpsj[2];
            $newsj  = $firstsj."-".$thbl."-".$lastsj;               
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
        $eareaname  = $this->input->post('eareaname', TRUE);
        $vspbnetto= $this->input->post('vsj', TRUE);
        $vspbnetto= str_replace(',','',$vspbnetto);
        $jml            = $this->input->post('jml', TRUE);
        $gaono          = true;
        $i=0;
      
        while($i<=$jml){
        $i++;
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $gaono=false;
            }
            if(!$gaono) break;
        }
        
        if(!$gaono){
            $this->db->trans_begin();
            $this->load->model('sjp/mmaster');
            $istore = $this->input->post('istore', TRUE);

            $istorelocation     = $this->input->post('istorelocation', TRUE);
            $istorelocationbin  = '00';
            $Qseachsjdaer       = $this->mmaster->searchsjheader($isj,$iarea);
            $nserachsjdaer      = $Qseachsjdaer->num_rows();
            
            if($nserachsjdaer<=0){
                    $this->mmaster->deletesjheader($isj,$iarea); 
                    $this->mmaster->insertsjheader2($ispmb,$dspmb,$newsj,$dsj,$iarea,$vspbnetto,$isjold); 
                    $i=1;
            while($i<=$jml){
            $i++;
                $cek=$this->input->post('chk'.$i, TRUE);
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $iproductgrade  = 'A';
                $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                $ndeliver       = str_replace(',','',$ndeliver);
                $ntmp           = $this->input->post('ntmp'.$i, TRUE);
                $ntmp           = str_replace(',','',$ntmp);
            
            if($ntmp!=$ndeliver){
                $this->mmaster->deletesjdetail( $ispmb,$isj,$iarea,$iproduct, $iproductgrade,$iproductmotif,$ndeliver);
                $th=substr($dsj,0,4);
                $bl=substr($dsj,5,2);
                $emutasiperiode=$th.$bl;
                $tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$isj,$ntmp,$eproductname);
                
                if( ($ntmp!='') && ($ntmp!=0) ){
                    $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                    $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver);
                }

                $this->mmaster->nambihspmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea);
                if($cek=='on'){
                    $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                    $vunitprice     = str_replace(',','',$vunitprice);
                    $norder         = $this->input->post('norder'.$i, TRUE);
                    $norder         = str_replace(',','',$norder);
                    $eremark        = $this->input->post('eremark'.$i, TRUE);
                    if($eremark=='')$eremark=null;
                        if($norder>0){
                            $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,$ndeliver,
                            $vunitprice,$ispmb,$dspmb,$newsj,$dsj,$iarea,$istore,$istorelocation,
                            $istorelocationbin,$eremark,$i);             
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
                            
                            $this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$newsj,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                            $th             = substr($dsj,0,4);
                            $bl             = substr($dsj,5,2);
                            $emutasiperiode = $th.$bl;
                            $ada            = $this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$emutasiperiode);
                            
                            if($ada=='ada'){
                                $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                            }else{
                                $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                            }

                            if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00')){
                                $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$q_ak);
                            }else{
                                $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname);
                            }
                            
                            $this->mmaster->updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea);
                        }
                } //END CHECK ON
            }
                    }
                    $sjnew=1;
                }else{  
                    $this->mmaster->updatesjheader($isj,$iarea,$isjold,$dsj,$vspbnetto);

                    $i=0;
          while($i<=$jml){
            $i++;
                        $cek=$this->input->post('chk'.$i, TRUE);
                        $iproduct       = $this->input->post('iproduct'.$i, TRUE);
            $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                        $iproductgrade  = 'A';
                        $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                        $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                        $ndeliver       = str_replace(',','',$ndeliver);
            $ntmp       = $this->input->post('ntmp'.$i, TRUE);
                        $ntmp       = str_replace(',','',$ntmp);
                        $this->mmaster->deletesjdetail( $ispmb, $isj, $iarea, $iproduct, $iproductgrade, $iproductmotif, $ndeliver);
                        $th=substr($dsj,0,4);
                        $bl=substr($dsj,5,2);
                        $emutasiperiode=$th.$bl;
            $this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$isj,$ntmp,$eproductname);
            if( ($ntmp!='') && ($ntmp!=0) ){
                        $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ntmp,$emutasiperiode);
                        $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ntmp);
              $this->mmaster->nambihspmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ntmp,$iarea);
            }
                        if($cek=='on'){
                          $eproductname = $this->input->post('eproductname'.$i, TRUE);
                          $vunitprice       = $this->input->post('vproductmill'.$i, TRUE);
                          $vunitprice       = str_replace(',','',$vunitprice);
                          $norder           = $this->input->post('norder'.$i, TRUE);
                          $norder             = str_replace(',','',$norder);
                          $eremark          = $this->input->post('eremark'.$i, TRUE);
                          if($eremark=='')$eremark=null;
                          if($norder>0){
                              $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,$ndeliver,
                                               $vunitprice,$ispmb,$dspmb,$isj,$dsj,$iarea,$istore,$istorelocation,
                                               $istorelocationbin,$eremark,$i);                      

                              $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
                              if(isset($trans)){
                                  foreach($trans as $itrans)
                                  {
                                    $q_aw =$itrans->n_quantity_awal;
                                    $q_ak =$itrans->n_quantity_akhir;
                                    $q_in =$itrans->n_quantity_in;
                                    $q_out=$itrans->n_quantity_out;
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
                              
                            $this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$isj,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                            $th=substr($dsj,0,4);
                            $bl=substr($dsj,5,2);
                            $emutasiperiode=$th.$bl;
                            $ada=$this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$emutasiperiode);
                            if($ada=='ada')
                            {
                                $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                            }else{
                                $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                            }
                            if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00'))
                            {
                                $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$q_ak);
                            }else{
                                $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$ndeliver);
                            }
                              $this->mmaster->updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea);
                          }
                        }
                    }
                    $sjnew=0;
                }               
                if ( ($this->db->trans_status() === FALSE) )
                {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Update SJP Receive Area : '.$iarea.' No:'.$isj);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isj
                    );
                }
            }//END GAONO
            else{
                $data = array(
                    'sukses' => false
                );
            }
            $this->load->view('pesan', $data);
    }//END UPDATE
}

/* End of file Cform.php */